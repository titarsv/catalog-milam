<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductsExport extends Entity
{
	use SoftDeletes;

	protected $fillable = [
		'name',
		'type',
		'filters',
		'structure',
		'schedule',
		'url',
	];

	protected $dates = ['deleted_at'];

    public $entity_type = 'export';
	protected $table = 'products_exports';

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

	protected $schedules_names = [
		'everyMinute' => 'каждую минуту',
		'everyFiveMinutes' => 'каждые пять минут',
		'everyTenMinutes' => 'каждые десять минут',
		'everyThirtyMinutes' => 'каждые тридцать минут',
		'hourly' => 'каждый час',
		'daily' => 'каждый день в полночь',
		'weekly' => 'каждую неделю',
		'monthly' => 'каждый месяц',
		'quarterly' => 'раз в квартал',
		'yearly' => 'каждый год',
	];

	protected $field_types = [
		'product.id' => 'ID товара',
		'product.name' => 'Название товара',
		'product.sku' => 'Артикул товара',
		'product.description' => 'Описание товара',
		'product.price' => 'Цена товара',
		'product.original_price' => 'Оригинальная цена товара',
		'product.sale_price' => 'Цена товара со скидкой',
		'product.min_price' => 'Цена с учётом скидки (если есть)',
		'product.max_price' => 'Цена без скидки',
		'product.link' => 'Ссылка на товар',
		'product.image.link' => 'Ссылка на фото товара',
		'product.image.title' => 'Название фото товара',
		'product.gallery.links' => 'Ссылки всех фото из галлереи товара',
		'product.gallery.title' => 'Название всех фото из галлереи товара',
		'product.stock' => 'Наличие товара',
		'product.category.name' => 'Название первой категории',
		'product.categories.name' => 'Названя всех категорий',
		'product.categories.tree' => 'Дерево категорий',
		'product.categories.slug' => 'Алиас первой категории',
		'product.attribute' => 'Значения атрибута товара',
		'product.attributes' => 'Значения всех атрибутов товара',
		'custom' => 'Свой вариант',
	];

	protected $modifications = [
		'' => '',
		'replace_all' => 'Замена всего содержимого',
		'replace_part' => 'Замена части содержимого',
		'add_prefix' => 'Добавление перед',
		'add_suffix' => 'Добавление после',
		'add_num' => 'Увеличение на',
		'multiple' => 'Увеличение в',
		'translit' => 'Транслит',
		'strip_tags' => 'Удалить HTML'
	];

	public function getStructureAttribute($attr)
	{
		return json_decode($attr);
	}

	public function getFiltersAttribute($attr)
	{
		return json_decode($attr);
	}

	public function getScheduleAttribute($attr)
	{
		return json_decode($attr);
	}

	public function getSchedulesNames(){
		return $this->schedules_names;
	}

	public function getFieldTypes(){
		return $this->field_types;
	}

	public function getModifications(){
		return $this->modifications;
	}

    /**
     * Создание файла экспорта
     *
     * @param $id
     * @param string $output
     * @param int $limit
     * @param int $offset
     * @return array|null
     */
	public function generateFile($id, $output = '', $limit = 0, $offset = 0){
		$export = $this->find($id);

		if(!empty($export)){
			$products = $this->getFilteredProducts($export->filters, $limit, $offset);

			$fields = $export->structure;
			if(in_array($export->type, ['xml', 'json'])){
                $data = [];
            }else{
                $titles = [];
                foreach($fields as $field){
                    $titles[] = $field->name;
                }
                $data = [$titles];
            }
			foreach ($products as $product){
				$data[] = $this->fields($fields, $product);
			}

			if($export->type == 'csv'){
				if(empty($output)){
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->fromArray($data, NULL, 'A1');

					$streamedResponse = new StreamedResponse();
					$streamedResponse->setCallback(function () use ($spreadsheet) {
						$writer = new Csv($spreadsheet);
						$writer->save('php://output');
					});

					$streamedResponse->setStatusCode(200);
					$streamedResponse->headers->set('Content-Type', 'text/csv');
					$streamedResponse->headers->set('Content-Disposition', 'attachment; filename="'.$export->name.'.csv"');
					return $streamedResponse->send();
				}else{
					$file = storage_path('app/exports/temp/'.$output.'.'.$export->type);
					if(is_file($file)){
						$spreadsheet = IOFactory::load($file);
					}else{
						$spreadsheet = new Spreadsheet();
					}
					$sheet = $spreadsheet->getActiveSheet();
					$last_row = $sheet->getHighestDataRow();
					if($last_row == 1){
						$last_row = 0;
					}else{
						array_shift($data);
					}
					$sheet->fromArray($data, NULL, 'A'.($last_row+1));

					$writer = new Csv( $spreadsheet );
					$writer->save($file);

					return ['total' => $this->getFilteredProducts($export->filters, 0, 0, true), 'saved' => $offset + $products->count()];
				}
			}elseif($export->type == 'xls'){
				if(empty($output)) {
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->fromArray($data, NULL, 'A1');

					$streamedResponse = new StreamedResponse();
					$streamedResponse->setCallback( function () use ( $spreadsheet ) {
						$writer = new Xls( $spreadsheet );
						$writer->save( 'php://output' );
					} );

					$streamedResponse->setStatusCode( 200 );
					$streamedResponse->headers->set( 'Content-Type', 'text/csv' );
					$streamedResponse->headers->set( 'Content-Disposition', 'attachment; filename="' . $export->name . '.xls"' );

					return $streamedResponse->send();
				}else{
					$file = storage_path('app/exports/temp/'.$output.'.'.$export->type);
					if(is_file($file)){
						$spreadsheet = IOFactory::load($file);
					}else{
						$spreadsheet = new Spreadsheet();
					}
					$sheet = $spreadsheet->getActiveSheet();
					$last_row = $sheet->getHighestDataRow();
					if($last_row == 1){
						$last_row = 0;
					}else{
						array_shift($data);
					}
					$sheet->fromArray($data, NULL, 'A'.($last_row+1));

					$writer = new Xls( $spreadsheet );
					$writer->save($file);

					return ['total' => $this->getFilteredProducts($export->filters, 0, 0, true), 'saved' => $offset + $products->count()];
				}
			}elseif($export->type == 'rss'){
				array_shift($data);
				$items = '';
				foreach ($data as $product){
					$items .= '<item>';
					foreach ($product as $key => $value) {
					    if(in_array($key, ['title', 'description']))
						    $items .= '<g:'.$key.'><![CDATA['.$value.']]></g:'.$key.'>';
					    else
						    $items .= '<g:'.$key.'>'.$value.'</g:'.$key.'>';
					}
					$items .= '</item>';
				}

				if(empty($output)) {
					$rss = '<?xml version="1.0" encoding="UTF-8" ?>';
					$rss .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">';
					$rss .= '<channel>';
					$rss .= '<title>'.$export->name.'</title>';
					$rss .= '<link>'.env('APP_URL').'/</link>';
					$rss .= '<g:description>RSS 2.0 product data feed</g:description>';
					$rss .= $items;
					$rss .= '</channel>';
					$rss .= '</rss>';

					return response( $rss )
						->header( 'Content-Type', 'text/xml' );
				}else{
					$file = storage_path('app/exports/temp/'.$output.'.'.$export->type);
					$total = $this->getFilteredProducts($export->filters, 0, 0, true);
					$saved = $offset + $products->count();
					if($saved < $total){
						file_put_contents($file, $items);
					}else{
                        $rss = '<?xml version="1.0" encoding="UTF-8" ?>';
						$rss .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">';
						$rss .= '<channel>';
						$rss .= '<title>'.$export->name.'</title>';
						$rss .= '<link>'.env('APP_URL').'/</link>';
						$rss .= '<g:description>RSS 2.0 product data feed</g:description>';
						if(is_file($file)){
							$rss .= file_get_contents($file);
						}
						$rss .= $items;
						$rss .= '</channel>';
						$rss .= '</rss>';
						file_put_contents($file, $rss);
					}

					return ['total' => $total, 'saved' => $saved];
				}
            }elseif($export->type == 'xml'){
                if(empty($output)) {
                    $xml_data = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Products></Products>');
                    $this->array_to_xml($data, $xml_data);

                    return response($xml_data->asXML())
                        ->header('Content-Type', 'application/xml')
                        ->header('Content-Description', 'File Transfer')
                        ->header('Content-Disposition', 'attachment; filename=' . $export->name.'.'.$export->type)
                        ->header('Content-Transfer-Encoding', 'binary');
                }else{
                    $file = storage_path('app/exports/temp/'.$output.'.'.$export->type);
                    $total = $this->getFilteredProducts($export->filters, 0, 0, true);
                    $saved = $offset + $products->count();
                    if($offset && is_file($file)){
                        $xml_data = simplexml_load_file($file);
                    }else{
                        $xml_data = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Products></Products>');
                    }

                    $this->array_to_xml($data, $xml_data);

                    file_put_contents($file, $xml_data->asXML());

                    return ['total' => $total, 'saved' => $saved];
                }
            }elseif($export->type == 'json'){
                if(empty($output)){
                    return response(json_encode($data, JSON_UNESCAPED_UNICODE))
                        ->header('Content-Type', 'application/json')
                        ->header('Content-Description', 'File Transfer')
                        ->header('Content-Disposition', 'attachment; filename=' . $export->name.'.'.$export->type)
                        ->header('Content-Transfer-Encoding', 'binary');
                }else{
                    $file = storage_path('app/exports/temp/'.$output.'.'.$export->type);
                    $total = $this->getFilteredProducts($export->filters, 0, 0, true);
                    $saved = $offset + $products->count();
                    if($offset && is_file($file)){
                        $json = json_encode(array_merge(json_decode(file_get_contents($file), true), $data), JSON_UNESCAPED_UNICODE);
                    }else{
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
                    }

                    file_put_contents($file, $json);

                    return ['total' => $total, 'saved' => $saved];
                }
			}
		}

		return null;
	}

	private function array_to_xml($data, &$xml_data){
        foreach($data as $key => $value){
            if(is_array($value)) {
                if(is_numeric($key)){
                    $key = 'Product';
                }
                $subnode = $xml_data->addChild($key);
                $this->array_to_xml($value, $subnode, $key);
            } else {
                if($value !== ''){
                    $xml_data->addChild("$key",htmlspecialchars("$value"));
                }
            }
        }
    }

	/**
	 * Получение коллекции товаров
	 *
	 * @param $filters
	 * @param $limit
	 * @param $offset
	 * @param bool $count
	 *
	 * @return mixed
	 */
	private function getFilteredProducts($filters, $limit, $offset, $count = false){
        $query = Product::select(['products.id', 'products.external_id', 'products.sku', 'products.stock', 'products.price', 'products.original_price', 'products.sale_price', 'products.sale', 'products.sale_from', 'products.sale_to', 'products.file_id', 'products.sort_priority']);
		$relations = [];
		foreach($filters as $group){
			foreach ($group as $condition){
				if($condition->criterion == 'category'){
					$relations[] = ['product_categories', 'product_categories.product_id', '=', 'products.id'];
				}elseif($condition->criterion == 'attribute'){
					$relations[] = ['product_attributes', 'product_attributes.product_id', '=', 'products.id'];
				}
			}

			$relation = isset($group[0]->relations) && $group[0]->relations == 'OR' ? 'orWhere' : 'where';

			$query->{$relation}(function ($query) use($group){
				foreach ($group as $condition){
					$relation = isset($condition->relations) && $condition->relations=='OR' ? 'orWhere' : 'where';
					if($condition->criterion == 'category') {
						if ($condition->criterion == 'with_child') {
							$category = Category::find($condition->value);
							$query->{$relation . 'In'}('product_categories.category_id', array_merge([$category->id], $category->get_children_categories($category->id)));
						} else {
							$query->{$relation}('product_categories.category_id', $condition->value);
						}
					}elseif($condition->criterion == 'attribute'){
						if(!empty($condition->value)){
							$query->{$relation}('product_attributes.attribute_value_id', $condition->value);
						}elseif(!empty($condition->attribute)){
							$query->{$relation}('product_attributes.attribute_id', $condition->attribute);
						}
					}elseif($condition->criterion == 'status'){
						$query->{$relation}('products.stock', $condition->value);
					}elseif($condition->criterion == 'price'){
						$query->{$relation}('products.price', $condition->condition, $condition->value);
					}
				}
			});
		}
		foreach ($relations as $relation){
			$query->leftJoin($relation[0], $relation[1], $relation[2], $relation[3]);
		}

        if($count){
            return $query->count();
        }

		if($limit > 0){
			$query->limit($limit);
		}
		if(!empty($offset)){
			$query->offset($offset);
		}

		$products = $query->with(['localization' => function($query){
            $query->where('language', 'ru');
        },
            'categories' => function($query){
                $query->with(['localization' => function($query){
                    $query->where('language', 'ru');
                }]);
            },
            'attributes',
            'image',
            'gallery' => function($query){
                $query->with('image');
            },
            'attributes.info',
            'attributes.value' => function($query){
                $query->with(['localization' => function($query){
                    $query->where('language', 'ru');
                }]);
            }
        ])->get();

		return $products;
	}

	/**
	 * Заполнение полей
	 *
	 * @param $settings
	 * @param $product
	 *
	 * @return array
	 */
	private function fields($settings, $product){
		$fields = [];
		$attribute_index = 0;
		foreach($settings as $field){
			if($field->field->type == 'product.attribute'){
				$fields[$field->field->type][] = [
					'id' => $field->field->attribute
				];
			}else{
				$fields[$field->field->type] = '';
			}
		}

		if(isset($fields['product.id']))
			$fields['product.id'] = $product->id;
		if(isset($fields['product.name']))
			$fields['product.name'] = !empty($product->name) ? $product->name : '';
		if(isset($fields['product.sku']))
			$fields['product.sku'] = !empty($product->sku) ? $product->sku : '';
		if(isset($fields['product.description']))
			$fields['product.description'] =  !empty($product->description) ? $product->description : $product->name;
		if(isset($fields['product.price']))
			$fields['product.price'] = round($product->price, 2);
		if(isset($fields['product.original_price']))
			$fields['product.original_price'] = round($product->original_price, 2);
		if(isset($fields['product.old_price']))
			$fields['product.old_price'] = $product->price < $product->old_price ? round($product->old_price, 2) : '';
		if(isset($fields['product.min_price']))
			$fields['product.min_price'] = (empty($product->sale_price) || $product->price == $product->sale_price) ? '' : ($product->sale_price < $product->price ? round($product->sale_price, 2) : round($product->price, 2));
		if(isset($fields['product.max_price']))
			$fields['product.max_price'] = round($product->original_price, 2);
		if(isset($fields['product.link']))
			$fields['product.link'] = $product->link();
		if(isset($fields['product.image.link']))
			$fields['product.image.link'] = empty($product->image) ? env('APP_URL').'/uploads/no_image.jpg' : $product->image->url();
		if(isset($fields['product.stock']))
			$fields['product.stock'] = (bool)$product->stock ? 'in_stock' : 'out_of_stock';
		if(isset($fields['product.category.name']))
			$fields['product.category.name'] = !empty($product->categories->first()) ? $product->categories->first()->name : '';
		if(isset($fields['product.categories.name']))
			$fields['product.categories.name'] = implode('; ', $product->categories->pluck('name')->toArray());
		if(isset($fields['product.categories.slug']))
			$fields['product.categories.slug'] = !empty($product->categories->first()) ? $product->categories->first()->slug : '';
		if(isset($fields['product.image.title']))
			$fields['product.image.title'] = empty($product->image) ? '' : $product->image->title;
        if(isset($fields['product.gallery.links'])) {
            $titles = [];
            if(!empty($product->gallery)) {
                foreach($product->gallery as $image){
                    $titles[] = $image->image->url();
                }
            }

            $fields['product.gallery.links'] = implode('; ', $titles);
        }
		if(isset($fields['product.gallery.title'])) {
			$titles = [];
			if(!empty($product->gallery)) {
				foreach($product->gallery as $image){
					$titles[] = $image->image->title;
				}
			}

			$fields['product.gallery.title'] = implode('; ', $titles);
		}
		if(isset($fields['product.categories.tree'])) {
			$categories = [];
			foreach ($product->categories()->with('children')->get() as $cat){
				$category = $cat->name;
				while($cat->parent_id > 0){
					$cat = Category::find($cat->parent_id);
					if(empty($cat))
						break;

					$category = $cat->name.' > '.$category;
				}

				$categories[] = $category;
			}

			$fields['product.categories.tree'] = implode('; ', $categories);
		}
		if(isset($fields['product.attribute'])) {
			foreach ($fields['product.attribute'] as $i => $attr){
				$values = [];
				$attributes = $product->attributes()->where('attribute_id', $attr['id'])->with('value')->get();
				foreach($attributes as $attr){
					$values[] = $attr->value->name;
				}

				$fields['product.attribute'][$i]['value'] = implode('; ', $values);
			}
		}
		if(isset($fields['product.attributes'])) {
			$values = [];
			$attributes = [];

			foreach ($product->attributes()->with('info', 'value')->get() as $attr){
				$values[$attr->info->name][] = $attr->value->name;
			}
			foreach ($values as $attribute => $vals){
				$attributes[] = $attribute.': '.implode(', ', $vals);
			}

			$fields['product.attributes'] = implode('; ', $attributes);
		}

		$product_data = [];

		foreach($settings as $field){
			if(is_string($field->field)){
				$key = $field->field;
			}elseif(isset($field->field->type)){
				$key = $field->field->type;
			}elseif(isset($field->field->custom)){
				$key = 'custom';
			}

			if($key == 'custom'){
				$value = $field->field->custom;
			}elseif($key == 'product.attribute' && isset($fields[$key][$attribute_index])){
				$value = $fields[$key][$attribute_index]['value'];
				$attribute_index++;
			}elseif(isset($key) && isset($fields[$key])){
				$value = $fields[$key];
			}

			if(isset($value)){
				$product_data[$field->name] = $this->modificate($value, $field->modifications);
			}
		}

		return $product_data;
	}

	/**
	 * Модификаторы
	 *
	 * @param $value
	 * @param $modifications
	 *
	 * @return float|int|mixed|string
	 */
	private function modificate($value, $modifications){
		foreach($modifications as $modification){
			if(!empty($modification->type)){
				switch ($modification->type) {
					case 'replace_all':
						if($value == $modification->from){
							$value = $modification->to;
						}
						break;
					case 'replace_part':
						$value = str_replace($modification->from, $modification->to, $value);
						break;
					case 'add_prefix':
						$value = !empty($value) ? $modification->value . ' ' .  $value : '';
						break;
					case 'add_suffix':
						$value = !empty($value) ? $value . ' ' . $modification->value : '';
						break;
					case 'add_num':
						$value += $modification->value;
						break;
					case 'multiple':
						$value = $value * $modification->value;
						break;
					case 'translit':
						$value = translit($value);
						break;
					case 'strip_tags':
						$value = strip_tags($value);
						break;
				}
			}
		}

		return $value;
	}

	/**
	 * Создание экспорта по расписанию
	 */
	public function runScheduleEvent(){
		$schedule = $this->schedule;

		if(isset($schedule->method) && isset($this->schedules[$schedule->method])){
			if((!isset($schedule->nextRun) || $schedule->nextRun <= time()) && (!isset($schedule->status) || $schedule->status == 1)){
				$this->startGeneration();
			}elseif($schedule->status < 1){
				$this->runNextGenerationStep();
			}

			if(isset($this->schedule->status) && $this->schedule->status == 1 && isset($schedule->nextRun)){
				echo 'Следующая генерация '.$this->url.'.'.$this->type.' будет запущена: '.date('Y-m-d H:i:s', $this->schedule->nextRun).PHP_EOL;
			}
		}
	}

	/**
	 * Начало генерации экспорта
	 */
	protected function startGeneration(){
        $schedule = $this->schedule;
		$result = $this->generateFile($this->id, $this->url, 1000, 0);
		$schedule->nextRun = time();
        $schedule->status = $result['saved'] / $result['total'];
        $schedule->offset = $result['saved'];

		if($schedule->status == 1){
			$schedule = $this->completeGeneration($schedule);
		}

        $this->schedule = json_encode($schedule);
		$this->save();
		echo round($schedule->status*100).'% '.$this->url.'.'.$this->type.PHP_EOL;
	}

	/**
	 * Продолжение генерации экспорта
	 */
	protected function runNextGenerationStep(){
        $schedule = $this->schedule;
        if($schedule->status < 1){
            $result = $this->generateFile($this->id, $this->url, 1000, $schedule->offset);
            $schedule->status = $result['saved'] / $result['total'];
            $schedule->offset = $result['saved'];

            if($schedule->status == 1){
	            $schedule = $this->completeGeneration($schedule);
            }

            $this->schedule = json_encode($schedule);
            $this->save();
	        echo round($schedule->status*100).'% '.$this->url.'.'.$this->type.PHP_EOL;
        }
	}

	/**
	 * Окончание генерации экспорта
	 *
	 * @param $schedule
	 *
	 * @return mixed
	 */
	public function completeGeneration($schedule){
		$file = storage_path('app/exports/temp/'.$this->url.'.'.$this->type);
		$destination = public_path('exports/'.$this->url.'.'.$this->type);
		rename($file, $destination);

		$schedules = $this->schedules;

		if(isset($schedule->method) && isset($schedules[$schedule->method])){
			if(in_array($schedule->method, ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'])){
				$schedule->nextRun = strtotime(date('Y-m-d', time() + $schedules[$schedule->method]));
			}else{
				$schedule->nextRun = time() + $schedules[$schedule->method];
			}
		}

		$schedule->updated_at = time();

		return $schedule;
	}
}
