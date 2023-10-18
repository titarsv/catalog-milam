<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductsImport extends Entity
{
	use SoftDeletes;

	protected $app_url = null;

	protected $fillable = [
        'name',
        'file',
        'attachments',
        'status',
        'statistic',
        'structure',
        'schedule',
        'settings'
	];

	protected $dates = ['deleted_at'];

	protected $table = 'products_imports';

	public $schedules = [
		'everyMinute' => 60,
		'everyFiveMinutes' => 300,
		'everyTenMinutes' => 600,
		'everyThirtyMinutes' => 1800,
		'hourly' => 3600,
		'daily' => 86400,
		'weekly' => 604800,
		'monthly' => 2592000,
		'quarterly' => 7884000,
		'yearly' => 31536000,
	];

	/**
	 * TODO: Реализовать быстрое исправление ошибок
	 */
	protected $errors = [];
	protected $warnings = [];
	protected $corrections = [
		'categories' => [],
		'attributes' => [],
		'attribute_values' => [],
		'images' => []
	];
	protected $not_imported = 0;
	protected $imported = 0;

	public function __construct(array $attributes = [])
    {
        $this->app_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'];
        parent::__construct($attributes);
    }

    public function getStatisticAttribute($attr){
        return json_decode($attr);
    }

    public function setStatisticAttribute($value){
        $this->attributes['statistic'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

	public function getStructureAttribute($attr){
		return json_decode($attr);
	}

    public function setStructureAttribute($value){
        $this->attributes['structure'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

	public function getScheduleAttribute($attr){
		return json_decode($attr);
	}

    public function setScheduleAttribute($value){
        $this->attributes['schedule'] = json_encode($value);
    }

    public function getSettingsAttribute($attr){
        return json_decode($attr);
    }

    public function setSettingsAttribute($value){
        $this->attributes['settings'] = json_encode($value);
    }

	/**
	 * Добавление ошибки
	 *
	 * @param $text
	 * @param null $type
	 * @param null $value
	 */
    protected function addError($text, $type = null, $value = null){
    	if(!in_array($text, $this->errors)) {
		    $this->errors[] = $text;
			if(isset($corrections[$type])){
				$corrections[$type][] = $value;
			}
	    }
    }

	/**
	 * Добавление предупреждения
	 *
	 * @param $text
	 * @param null $type
	 */
	protected function addWarning($text, $type = null){
		if(!in_array($text, $this->warnings)) {
			$this->warnings[] = $text;
		}
	}

	/**
	 * Инициализация статистики
	 *
	 * @param $statistic
	 */
	protected function setStatistic($statistic){
		if(isset($statistic->imported)){
			$this->imported = $statistic->imported;
		}
		if(isset($statistic->not_imported)){
			$this->not_imported = $statistic->not_imported;
		}
		if(isset($statistic->warnings)){
			$this->warnings = $statistic->warnings;
		}
		if(isset($statistic->errors)){
			$this->errors = $statistic->errors;
		}
	}

	/**
	 * Сброс прогресса импорта
	 */
	public function refreshImport(){
		$settings = $this->settings;
		foreach($settings->parts as $i => $part){
			if($part->imported){
				$settings->parts[$i]->imported = false;
			}
		}
		$this->settings = $settings;
		$this->statistic = null;
		$this->status = 0;
		$this->save();
	}

    /**
     * Следующий шаг импорта
     *
     * @return array
     */
	public function runNextImportStep(){
    	$settings = $this->settings;
		if(isset($settings->corrections)){
			$this->corrections = $settings->corrections;
		}
		$this->setStatistic($this->statistic);

    	foreach($settings->parts as $i => $part){
			if($part->imported == 0){
				$path = storage_path('app/imports/'.$this->id.'/parts/'.$part->name);
				if(is_file($path)){
					$data = json_decode(file_get_contents($path));
					$structure = $this->structure;
					$prepared_data = [];
					$relation = explode('.', $settings->relation);
					foreach($data as $product){
						$product_data = [
							'action' => $settings->type,
							'tables' => []
                        ];
						foreach($structure as $title => $field){
							if(isset($product->$title) && !empty($field->type)){
								$type = explode('.', $field->type);
								if(!isset($product_data['tables'][$type[0]])){
									$product_data['tables'][$type[0]] = [];
								}
								$value = $product->$title;

								if(in_array($field->type, ['product.file_id', 'galleries.file_id', 'category.id', 'attribute_values.id'])){
								    $result = $this->preparationData($value, $field);
                                    $value = $result['value'];
                                    if(!empty($result['action']) && $product_data['action'] != 'stop'){
	                                    $product_data['action'] = $result['action'];
                                    }
								}

								if($settings->relation == $field->type){
									if($product_data['action'] == 'update'){
										if(empty($value)){
											$product_data['action'] = 'skip';
										}else{
											$p = Product::where($relation[1], $value)->first();
											if(!empty($p)){
												$product_data['original'] = $p;
											}else{
												$product_data['action'] = 'skip';
											}
										}
									}elseif($product_data['action'] == 'update_and_create'){
										$p = Product::where($relation[1], $value)->first();
										if(!empty($p)){
											$product_data['action'] = 'update';
											$product_data['original'] = $p;
										}else{
											$product_data['action'] = 'create';
										}
									}
								}

								$product_data['tables'][$type[0]][$type[1]] = $value;
							}
						}
						$prepared_data[] = $product_data;
					}
				}else{
					$this->addError('Потерян файл импорта "'.$part->name.'", колличество товаров в файле: '.$part->count);
					$this->not_imported += $part->count;
				}

//				dd($this->errors, $this->warnings, $prepared_data);
				$this->saveProducts($prepared_data);

                $settings->parts[$i]->imported = true;
				$settings->corrections = $this->corrections;
                $this->settings = $settings;
				$this->statistic = [
					'errors' => $this->errors,
					'warnings' => $this->warnings,
					'not_imported' => $this->not_imported,
					'imported' => $this->imported
				];
                $this->status = round(($this->imported + $this->not_imported) / $settings->total * 100, 2);

                $this->save();

                return ['total' => $settings->total, 'progress' => $this->status, 'statistic' => $this->statistic];
                break;
			}
	    }

		$this->settings = $settings;
		$this->statistic = [
			'errors' => $this->errors,
			'warnings' => $this->warnings,
			'not_imported' => $this->not_imported,
			'imported' => $this->imported
		];
		$this->save();

	    return ['total' => $settings->total, 'progress' => 100, 'statistic' => $this->statistic];
	}

    /**
     * Подготовка данных
     *
     * @param $value
     * @param $settings
     * @return array
     */
	protected function preparationData($value, $settings){
        $data = [
            'value' => [],
        ];
	    if($settings->type == 'product.file_id'){
            $image = $this->prepareImage(trim($value), $settings);
            $data = [
                'value' => $image['value'],
                'result' => $image['result']
            ];
            if($image['result'] == 'error'){
	            $data['action'] = $settings->not_found;
            }
            return $data;
        }elseif(in_array($settings->type, ['galleries.file_id', 'category.id'])){
	        if(!empty($settings->separator)){
                $values = explode($settings->separator, $value);
            }else{
                $values = [$value];
            }
            foreach($values as $value){
                if($settings->type == 'galleries.file_id')
                    $result = $this->prepareImage(trim($value), $settings);
                else
                    $result = $this->prepareCategory(trim($value), $settings);

                if($result['result'] == 'success'){
                    $data['value'][] = $result['value'];
                }else{
	                if($result['result'] == 'error'){
		                if(!isset($data['action']) || $data['action'] != 'stop'){
			                $data['action'] = $settings->not_found;
		                }
	                }
                }
            }
            if(count($data['value']) == count($values)){
                $data['result'] = 'success';
            }else{
                if(empty($data['value'])){
                    $data['result'] = 'error';
                }else{
                    $data['result'] = 'warning';
                }
            }
            return $data;
        }elseif($settings->type == 'attribute_values.id'){
	    	if($settings->format == 'values'){
			    $result = $this->getAttributeValues($value, $settings->attribute, $settings->separator, $settings->not_found);
			    if($result['result'] == 'success'){
				    $data['value'] = $result['value'];
			    }elseif($result['result'] == 'error'){
				    $data['action'] = 'stop';
			    }
		    }elseif($settings->format == 'attributes_and_values'){
	    		if(!empty($settings->attributes_separator)) {
				    $attributes = explode( $settings->attributes_separator, $value );
			    }else{
				    $attributes = [$value];
			    }

			    foreach ($attributes as $attribute){
	    			list($attribute_name, $values) = explode($settings->attribute_values_separator, $attribute);
				    $result = $this->getAttributeValues($value, $attribute_name, $settings->separator, $settings->not_found);
				    if($result['result'] == 'success'){
					    $data['value'] = array_merge($data['value'], $result['value']);
				    }elseif($result['result'] == 'error'){
					    $data['action'] = 'stop';
				    }
			    }
		    }

		    return $data;
        }elseif($settings->type == 'product.stock'){
            if($value == '+'){
                $value = 1;
            }elseif($value == '-'){
                $value = 0;
            }
        }

    	return ['result' => 'success', 'value' => $value];
	}

    /**
     * Подготовка изображения
     *
     * @param $value
     * @param $settings
     * @return array
     */
	protected function prepareImage($value, $settings){
        $files = new File();
        if($settings->format == 'media.name'){
            $image = $files->where('title', $value)->first();
        }elseif($settings->format == 'link'){
            if(strpos($value, $this->app_url) === 0){
                $path = str_replace([$this->app_url.'/', '/', '\\'], ['', DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $value);
                if(is_file(public_path($path))){
                    $image = $files->where('path', $path)->first();
                    if(empty($image)){
                        $image = Image::where('path', $path)->first();
                        if(!empty($img)){
                            return ['result' => 'success', 'value' => $image->file_id];
                        }
                    }else{
                        return ['result' => 'success', 'value' => $image->id];
                    }
                }
            }

            $image = $files->uploadFromUrlImages($value);
        }elseif($settings->format == 'media.id'){
            $image = $files->where('id', $value)->first();
        }
        if(!empty($image)){
        	if($settings->format == 'link'){
        		$this->addWarning('Загружено новое фото '.$settings->format.': "'.$value.'", ID: "'.$image->id.'".', 'images');
		        return ['result' => 'warning', 'value' => $image->id];
	        }else{
		        return ['result' => 'success', 'value' => $image->id];
	        }
        }else{
	        $this->addError('Фото '.$settings->format.': "'.$value.'" не найдено.', 'images');
            return ['result' => 'error', 'value' => null];
        }
    }

    /**
     * Подготовка категорий
     *
     * @param $value
     * @param $settings
     * @return array
     */
    protected function prepareCategory($value, $settings){
	    if(!empty($settings->tree_separator)){
            $br = explode($settings->tree_separator, $value);
        }else{
            $br = [$value];
        }

        $table = new Category();
        $parent = null;
        foreach ($br as $name){
            $name = trim($name);
            $result = $table->select('categories.id')
                ->leftJoin('localization', 'categories.id', '=', 'localization.localizable_id')
                ->where('localization.localizable_type', 'Categories')
                ->where('localization.field', 'name')
                ->where('localization.language', 'ru')
                ->where('localization.value', $name)
                ->where('parent_id', $parent)
                ->take(1)
                ->get()
                ->first();
            if(empty($result)){
//                dd($name, $parent);
                if($settings->not_found == 'create'){
                    $parent = $table->insertGetId([
                        'name' => $name,
                        'parent_id' => $parent,
                        'url_alias' => $table->generateUrlAlias($name, $parent)
                    ]);
                }else{
	                $this->addError('Категория: "'.$value.'" не найдена.', 'categories');
                    return ['result' => 'error', 'value' => null];
                }
            }else{
                $parent = $result->id;
            }
        }

        return ['result' => 'success', 'value' => $parent];
    }

	/**
	 * Подготовка атрибутов
	 *
	 * @param $value
	 * @param $attribute_name
	 * @param $separator
	 * @param $not_found
	 *
	 * @return array
	 */
	protected function getAttributeValues($value, $attribute_name, $separator, $not_found){
		$attribute_name = trim($attribute_name);
		$data = [
			'value' => [],
			'result' => 'success'
		];

		$attribute = Attribute::select('attributes.*')
            ->leftJoin('localization', 'attributes.id', '=', 'localization.localizable_id')
            ->where('localization.localizable_type', 'Attributes')
            ->where('localization.field', 'name')
            ->where('localization.language', 'ru')
            ->where('localization.value', $attribute_name)
            ->first();
		if(empty($attribute)){
			if($not_found == 'create'){
				$attribute_id = Attribute::insertGetId(['name' => $attribute_name, 'value' => str_replace(['-', '_'], '', Str::slug(translit($attribute_name)))]);
				$this->addWarning('Создан новый атрибут: "' . $attribute_name . '"', 'attributes');
				$data['result'] = 'warning';
			}else{
				if($not_found == 'stop') {
					$this->addError('Не найден атрибут: "' . $attribute_name . '"', 'attributes');
					$data['result'] = 'error';
				}else{
					$this->addWarning('Не найден атрибут: "' . $attribute_name . '"', 'attributes');
					$data['result'] = 'warning';
				}
			}
		}else{
			$attribute_id = $attribute->id;
		}

		if(isset($attribute_id)){
			if(!empty($separator)){
				$values_names = explode($separator, $value);
				foreach ($values_names as $value_name){
					$value_name = trim($value_name);
					$value = AttributeValue::select('attribute_values.*')
                        ->leftJoin('localization', 'attribute_values.id', '=', 'localization.localizable_id')
                        ->where('localization.localizable_type', 'Values')
                        ->where('localization.field', 'name')
                        ->where('localization.language', 'ru')
                        ->where('localization.value', $value_name)
                        ->where('attribute_id', $attribute_id)
                        ->first();
					if(empty($value)){
						if($not_found == 'create'){
							$data['value'][] = ['id' => $attribute_id, 'value' => AttributeValue::insertGetId(['attribute_id' => $attribute_id, 'name' => $value_name, 'value' => str_replace(['-', '_'], '', Str::slug(translit($value_name)))])];
							$this->addWarning('Создан новый вариант атрибута ' . $attribute_name . ': "' . $value_name . '"', 'attribute_values');
							$data['result'] = 'warning';
						}else{
							if($not_found == 'stop') {
								$this->addError('Не найден вариант атрибута "' . $attribute_name . '": "' . $value_name . '"', 'attribute_values');
								$data['result'] = 'error';
							}else{
								$this->addWarning('Не найден вариант атрибута "' . $attribute_name . '": "' . $value_name . '"', 'attribute_values');
								$data['result'] = 'warning';
							}
						}
					}else{
						$data['value'][] = ['id' => $attribute_id, 'value' => $value->id];
					}
				}
			}else{
				$this->addError('Не указан разделитель между вариантами атрибутов.');
				$data['result'] = 'error';
			}
		}

		return $data;
	}

	/**
	 * Сохранение данных
	 *
	 * @param $products
	 */
	protected function saveProducts($products){
		foreach($products as $product){
			if($product['action'] == 'skip'){
				$this->addWarning('Товар '.(!empty($product['tables']['product']['sku']) ? $product['tables']['product']['sku'].' ' : (!empty($product['tables']['product']['id']) ? $product['tables']['product']['id'].' ' : '')).'пропущен.');
				$this->not_imported++;
			}elseif($product['action'] == 'stop') {
				$this->addError('Импорт остановлен из-за ошибки!'.(!empty($product['tables']['product']['sku']) ? ' ['.$product['tables']['product']['sku'].']' : (!empty($product['tables']['product']['id']) ? ' ['.$product['tables']['product']['id'].']' : '')));
				$this->not_imported++;
				break;
			}elseif($product['action'] == 'create'){
				$product_result = $this->createProduct($product['tables']);
				if($product_result === true){
					$this->imported++;
				}else{
					$this->not_imported++;
				}
			}elseif($product['action'] == 'update'){
				$product_result = $this->updateProduct($product['tables'], $product['original']);
				if($product_result === true){
					$this->imported++;
				}else{
					$this->not_imported++;
				}
			}
		}
	}

    /**
     * Создание товара
     *
     * @param $data
     * @return bool
     */
	protected function createProduct($data){
        $products = new Product;

		if(isset($data['product']['id']) && $products::where('id', $data['product']['id'])->count()){
			$this->addError('Товар c ID: '.$data['product']['id'].' не может быть создан, так как уже существует!');
			return false;
		}elseif(isset($data['product']['sku']) && $products::where('sku', $data['product']['sku'])->count()){
			$this->addError('Товар c артикулом: '.$data['product']['sku'].' не может быть создан, так как уже существует!');
			return false;
		}

        $id = $products->insertGetId($data['product']);
        $product = $products->find($id);

        $request = new Request();
        $request_data = [];
        if(isset($data['localization']['name_ru']))
            $request_data['name_ru'] = $data['localization']['name_ru'];
        if(isset($data['localization']['name_ua']))
            $request_data['name_ua'] = $data['localization']['name_ua'];
        if(isset($data['localization']['description_ru']))
            $request_data['description_ru'] = $data['localization']['description_ru'];
        if(isset($data['localization']['description_ua']))
            $request_data['description_ua'] = $data['localization']['description_ua'];
        if(isset($data['seo']['url']))
            $request_data['url'] = $data['seo']['url'];
        if(isset($data['seo']['meta_title_ru']))
            $request_data['meta_title_ru'] = $data['seo']['meta_title_ru'];
        if(isset($data['seo']['meta_title_ua']))
            $request_data['meta_title_ua'] = $data['seo']['meta_title_ua'];
        if(isset($data['seo']['meta_description_ru']))
            $request_data['meta_description_ru'] = $data['seo']['meta_description_ru'];
        if(isset($data['seo']['meta_description_ua']))
            $request_data['meta_description_ua'] = $data['seo']['meta_description_ua'];

        $request->merge($request_data);
        $product->saveSeo($request);
        $product->saveLocalization($request);

        if(!empty($data['galleries']['file_id'])){
            $gallery = new Gallery();
            foreach($data['galleries']['file_id'] as $file_id){
                $gallery->insert([
                    'field' => 'gallery',
                    'file_id' => $file_id,
                    'parent_type' => 'Products',
                    'parent_id' => $id
                ]);
            }
        }

        if(!empty($data['attribute_values']['id']))
            $product->attributes()->createMany($data['product_attributes']['id']);

        if(!empty($data['category']['id']))
            $product->categories()->attach($data['categories']['id']);

        return true;
    }

    /**
     * Обновление товара
     *
     * @param $data
     * @param $product
     * @return bool
     */
    protected function updateProduct($data, $product){

    	if(isset($data['product']['galleries.file_id']) && is_array($data['product']['galleries.file_id'])){
		    if(is_null($product->gallery)){
			    $gallery = new Gallery();
			    $data['product']['galleries.file_id'] = $gallery->add_gallery($data['product']['galleries.file_id']);
		    }else{
			    $product->gallery->images = json_encode($data['product']['galleries.file_id']);
		    }
		    unset($data['product']['galleries.file_id']);
	    }

        $product->update($data['product']);

        $request = new Request();
        $request_data = [];
        if(isset($data['localization']['name_ru']))
            $request_data['name_ru'] = $data['localization']['name_ru'];
        if(isset($data['localization']['name_ua']))
            $request_data['name_ua'] = $data['localization']['name_ua'];
        if(isset($data['localization']['description_ru']))
            $request_data['description_ru'] = $data['localization']['description_ru'];
        if(isset($data['localization']['description_ua']))
            $request_data['description_ua'] = $data['localization']['description_ua'];
        if(isset($data['seo']['url']))
            $request_data['url'] = $data['seo']['url'];
        if(isset($data['seo']['meta_title_ru']))
            $request_data['meta_title_ru'] = $data['seo']['meta_title_ru'];
        if(isset($data['seo']['meta_title_ua']))
            $request_data['meta_title_ua'] = $data['seo']['meta_title_ua'];
        if(isset($data['seo']['meta_description_ru']))
            $request_data['meta_description_ru'] = $data['seo']['meta_description_ru'];
        if(isset($data['seo']['meta_description_ua']))
            $request_data['meta_description_ua'] = $data['seo']['meta_description_ua'];

        $request->merge($request_data);
        $product->saveSeo($request);
        $product->saveLocalization($request);

        if(!empty($data['galleries']['file_id'])){
            $gallery = new Gallery();
            $gallery->where('parent_type', 'Products')->where('parent_id', $product->id)->where('field', 'gallery')->delete();
            foreach($data['galleries']['file_id'] as $file_id){
                $gallery->insert([
                    'field' => 'gallery',
                    'file_id' => $file_id,
                    'parent_type' => 'Products',
                    'parent_id' => $product->id
                ]);
            }
        }

	    if (!empty($data['attribute_values']['id'])) {
		    $product_attributes = [];
		    foreach ($data['attribute_values']['id'] as $attribute) {
			    $product_attributes[] = [
				    'product_id' => $product->id,
				    'attribute_id' => $attribute['id'],
				    'attribute_value_id' => $attribute['value'],
			    ];
		    }

		    $product->attributes()->delete();
		    $product->attributes()->createMany($product_attributes);
	    }

        if(isset($data['category']['id'])){
            $product->categories()->sync($data['category']['id']);
        }

        return true;
    }
}
