<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Models\ModuleBestsellers;
use App\Models\Moduleslideshow;
use App\Models\ModuleLatest;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Module;
use App\Models\Action;
use App\Models\File;
use App\Models\Page;
use App\Models\Blog;
use App\Models\Sale;
use App\Models\Seo;
use App;

class PagesController extends Controller
{
    protected $rules = [
        'name' => 'required|unique:pages'
    ];
    protected $messages = [
        'name.required' => 'Поле должно быть заполнено!',
        'name.unique' => 'Значение должно быть уникальным!',
        'body.required' => 'Поле должно быть заполнено!'
    ];

    public function indexAction(){
        $seo = Seo::where('url', '/')->first();
        $page = $seo->seotable;

        if(empty($page->status)){
            abort(404);
        }

        if($page->template == 'public.page'){
            $fields = null;
        }else{
            $d = $this->setFieldsProducts($this->setFieldsImages(json_decode($page->localize(App::getLocale(), 'body'))));
            $fields = [];
            foreach($d as $field){
                if($field->type == 'repeater'){
                    $fields[$field->slug] = $field->data;
                }else{
                    $fields[$field->slug] = isset($field->value) ? $field->value : '';
                }
            }
        }

        return view(empty($page->template) ? 'public.page' : $page->template)
            ->with('page', $page)
            ->with('fields', $fields)
            ->with('articles', Blog::where('status', 1)->get())
            ->with('seo', $seo)
            ->withShortcodes();
    }

	/**
	 * Отображение страницы
	 *
	 * @param $data
	 *
	 * @return $this
	 */
	public function showAction($data){
		$page = $data->seo->seotable;

		if(empty($page->status)){
			abort(404);
		}

		if($page->template == 'public.page'){
			$fields = null;
		}else{
			$d = $this->setFieldsProducts($this->setFieldsImages(json_decode($page->localize(App::getLocale(), 'body'))));
			$fields = [];
			foreach($d as $field){
				if($field->type == 'repeater'){
					$fields[$field->slug] = $field->data;
				}else{
					$fields[$field->slug] = isset($field->value) ? $field->value : '';
				}
			}
		}

		return view(empty($page->template) ? 'public.page' : $page->template)
			->with('page', $page)
			->with('fields', $fields)
			->with('seo', $data->seo)
			->withShortcodes();
	}

    /**
     * Список страниц
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndexAction(Page $content){
        return view('admin.pages.index')
            ->with('content', $content->paginate(10));
    }

	/**
	 * Создание страницы
	 *
	 * @param Request $request
	 * @param Page $pages
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function adminStoreAction(Request $request, Page $pages){
		$validator = Validator::make($request->all(), ['name_'.Config::get('app.locale') => 'required'], ['name_'.Config::get('app.locale').'.required' => 'Поле должно быть заполнено!']);

		if($validator->fails()){
			return response()->json($validator);
		}

		$id = $pages->insertGetId(['parent_id' => null, 'template' => 'public.page', 'status' => 0, 'sort_order' => 0]);
		$page = $pages->find($id);
		$page->saveSeo($request);
		$page->saveLocalization($request);

        Action::createEntity($page);

		return response()->json(['result' => 'success', 'redirect' => '/admin/pages/edit/'.$id]);
	}

	/**
	 * Страница обновления страницы
	 *
	 * @param $id
	 * @param Page $pages
	 *
	 * @return mixed
	 */
    public function adminEditAction($id, Page $pages, Setting $settings){
    	$page = $pages->find($id);
	    $templates = [(object)[
            'name' => 'page',
            'id' => 'public.page'
        ]];
	    foreach(Storage::disk('local')->allFiles('/resources/views/public/layouts/pages') as $file){
		    $parts = explode('/', $file);
		    $templates[] = (object)[
		    	'name' => str_replace('.blade.php', '', end($parts)),
			    'id' => str_replace(['resources/views/', '.blade.php', '/'], ['', '', '.'], $file)
		    ];
	    }

	    if($page->template == 'public.page'){
	    	$fields = null;
	    }else{
		    $fields = [];
		    foreach(Config::get('app.locales_names') as $locale => $locale_name){
		    	$setting = $settings->get_setting('template_'.$page->template);
			    $fields[$locale] = $this->updateTemplateData(!empty($setting) ? $settings->get_setting('template_'.$page->template)->fields : [], json_decode($page->localize($locale, 'body')));
		    }

		    foreach($fields as $lang => $lang_fields){
			    $fields[$lang] = $this->setFieldsImages($lang_fields);
		    }
	    }

	    $all_pages = [
            (object)[
                'name' => 'Не выбрано',
                'id' => null
            ]
        ];
	    foreach($pages->where('id', '!=', $id)->get() as $p){
            $all_pages[] = (object)[
                'name' => $p->name,
                'id' => $p->id
            ];
        }

        return view('admin.pages.edit')
	        ->with('templates', $templates)
	        ->with('fields', $fields)
	        ->with('pages', $all_pages)
	        ->with('seo', $page->seo)
            ->with('page', $page)
	        ->with('languages', Config::get('app.locales_names'))
            ->with('main_lang', Config::get('app.locale'))
            ->with('languages', Config::get('app.locales_names'))
	        ->with('editors', localizationFields(['body', 'seo_description']));
    }

	/**
	 * Обновление страницы
	 *
	 * @param $id
	 * @param Request $request
	 * @param Setting $settings
	 *
	 * @return $this
	 */
    public function adminUpdateAction($id, Request $request, Setting $settings){
        $rules = [
	        'name_'.Config::get('app.locale') => 'required'
        ];
        $messages = [
	        'name_'.Config::get('app.locale').'.required' => 'Поле должно быть заполнено!'
        ];
        if($request->template == 'public.page'){
	        $rules['body_'.Config::get('app.locale')] = 'required';
	        $messages['body_'.Config::get('app.locale').'.required'] = 'Поле должно быть заполнено!';
        }else{
	        $fields = [];
	        foreach(Config::get('app.locales_names') as $locale => $locale_name){
	            $locale_settings = $settings->get_setting('template_'.$request->template);
		        $fields[$locale] = !empty($locale_settings) ? $locale_settings->fields : [];
	        }
	        if(!empty($request->fields)){
		        $fields = $this->fillInFields($fields, $request->fields);
		        $data = [];
		        foreach(Config::get('app.locales_names') as $locale => $locale_name){
			        $data['body_'.$locale] = json_encode($fields[$locale]);
		        }
		        $request->request->add($data);
	        }else{
		        $data = [];
		        foreach(Config::get('app.locales_names') as $locale => $locale_name){
			        $data['body_'.$locale] = '';
		        }
		        $request->request->add($data);
	        }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $page = Page::find($id);

        $page_data = $page->fullData();

	    $page->fill($request->only(['template', 'status', 'fields']));
	    $page->parent_id = !empty($request->parent_id) ? $request->parent_id : null;
        $page->sort_order = !empty($request->sort_order) ? $request->sort_order : 0;
	    $page->save();
	    $page->saveSeo($request);
	    $page->saveLocalization($request);

        Action::updateEntity(Page::find($id), $page_data);

        return redirect('/admin/pages')
            ->with('content', $page->paginate(10))
            ->with('message-success', 'Страница ' . $page->name . ' успешно обновлена.');
    }

    /**
     * Удаление страницы
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminDestroyAction($id){
        $page = Page::find($id);
        $page->delete();

        Action::deleteEntity($page);

        return redirect('/admin/pages')
            ->with('products', $page->paginate(10))
            ->with('message-success', 'Страница ' . $page->name . ' успешно удалена.');
    }

	/**
	 * Список шаблонов страниц
	 *
	 * @return $this
	 */
    public function adminTemplatesAction(){
	    $files = [];
		foreach(Storage::disk('local')->allFiles('/resources/views/public/layouts/pages') as $file){
			$parts = explode('/', $file);
			$files[] = [
				'path' => str_replace(['resources/views/', '.blade.php', '/'], ['', '', '.'], $file),
				'name' => str_replace('.blade.php', '', end($parts))
			];
		}

	    return view('admin.pages.templates.templates')
		    ->with('files', $files);
    }

	/**
	 * Страница настройки шаблона
	 *
	 * @param $name
	 * @param Setting $settings
	 *
	 * @return $this
	 */
	public function adminTemplateAction($name, Setting $settings){
    	$template = $settings->get_setting('template_'.$name);

    	if(empty($template)){
		    $template = (object)[
			    'path' => "resources/views/$name.blade.php",
			    'name' => $name,
			    'fields' => []
		    ];
	    }

	    return view('admin.pages.templates.template')
		    ->with('template', $template);
	}

	/**
	 * Обновление настроек шаблона
	 *
	 * @param $name
	 * @param Request $request
	 * @param Setting $settings
	 *
	 * @return $this
	 */
	public function adminUpdateTemplateAction($name, Request $request, Setting $settings){
		$template = (object)[
			'path' => "resources/views/$name.blade.php",
			'name' => $name,
			'fields' => $this->refreshFieldsKeys($request->fields)
		];

		$settings->update_setting('template_'.$name, $template);

		return redirect('/admin/pages/template/'.$name)
			->with('message-success', 'Шаблон "' . $name . '" обновлён.');
	}

	/**
	 * Сброс ключей в массивах сохраняемых данных
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	protected function refreshFieldsKeys($fields){
		$fields = array_values((array)$fields);

		foreach($fields as $key => $field){
			if(isset($field['fields'])){
				$fields[$key]['fields'] = $this->refreshFieldsKeys($field['fields']);
			}
		}

    	return $fields;
	}

	/**
	 * Добавление данных в настройки полей
	 *
	 * @param $fields
	 * @param $request
	 *
	 * @return mixed
	 */
	protected function fillInFields($fields, $request){
        foreach(Config::get('app.locales') as $lang){
            if(!isset($request[$lang])){
                $request[$lang] = [];
            }
        }
		$request = $this->mergeLangFields($request);
    	foreach($fields as $lang => $lang_fields){
    		foreach($lang_fields as $i => $field){
			    if($field->type == 'repeater'){
				    $fields[$lang][$i]->data = $request[$lang][$field->slug];
			    }else{
				    if(isset($request[$lang][$field->slug])){
					    $fields[$lang][$i]->value = $request[$lang][$field->slug];
			        }elseif(isset($request['all'][$field->slug])){
					    $fields[$lang][$i]->value = $request['all'][$field->slug];
				    }
			    }
		    }
	    }

		return $fields;
	}

	/**
	 * Формирование полного набора данных для каждого языка
	 *
	 * @param $fields
	 *
	 * @return mixed
	 */
	protected function mergeLangFields($fields){
    	if(isset($fields['all'])){
    		foreach($fields['all'] as $key => $data){
			    foreach($fields as $lng => $lang_fields){
				    if($lng != 'all'){
						if(isset($lang_fields[$key]) && is_array($data)){
							$fields[$lng][$key] = $this->mergeRepeaterFields($fields[$lng][$key], $data);
						}else{
							$fields[$lng][$key] = $data;
						}
				    }
			    }
		    }
	    }

	    unset($fields['all']);

    	return $fields;
	}

	/**
	 * Формирование полного набора данных для повторителей
	 *
	 * @param $source
	 * @param $merged
	 *
	 * @return mixed
	 */
	protected function mergeRepeaterFields($source, $merged){
    	foreach($merged as $i => $data){
    		if(isset($source[$i]) && is_array($data)){
			    $source[$i] = $this->mergeRepeaterFields($source[$i], $data);
		    }else{
			    $source[$i] = $data;
		    }
	    }

	    return $source;
	}

	/**
	 * Подгрузка изобрпжений в данные
	 *
	 * @param $fields
	 *
	 * @return mixed
	 */
	protected function setFieldsImages($fields){
    	$images = new File();

    	if(empty($fields)){
		    $fields = [];
	    }

		foreach($fields as $i => $field){
			if($field->type == 'repeater'){
				$fields[$i]->data = $this->setRepeaterImages($fields[$i]->fields, $fields[$i]->data);
			}elseif($field->type == 'oembed'){
				if(!empty($field->value)){
					$fields[$i]->value = [
						'id' => $field->value,
						'image' => $images->find($field->value)
					];
				}
			}
		}

		return $fields;
	}

	/**
	 * Подгрузка изобрпжений в данные повторителя
	 *
	 * @param $fields
	 * @param $data
	 *
	 * @return mixed
	 */
	protected function setRepeaterImages($fields, $data){
    	foreach($data as $i => $fields_data){
    		foreach($fields as $field){
    			if(isset($fields_data->{$field->slug})){
    				if($field->type == 'repeater'){
					    $data[$i]->{$field->slug} = $this->setRepeaterImages($field->fields, $fields_data->{$field->slug});
				    }elseif($field->type == 'oembed'){
					    $images = new File();
					    $data[$i]->{$field->slug} = [
						    'id' => $fields_data->{$field->slug},
						    'image' => $images->find($fields_data->{$field->slug})
					    ];
				    }
			    }
		    }
	    }

	    return $data;
	}

    /**
     * Подгрузка товаров в данные
     *
     * @param $fields
     *
     * @return mixed
     */
    protected function setFieldsProducts($fields){
        $products = new Product();

        if(empty($fields)){
            $fields = [];
        }

        foreach($fields as $i => $field){
            if($field->type == 'repeater'){
                $fields[$i]->data = $this->setRepeaterProducts($fields[$i]->fields, $fields[$i]->data);
            }elseif($field->type == 'product'){
                if(!empty($field->value)){
                    $fields[$i]->value = [
                        'id' => $field->value,
                        'product' => $products->find($field->value)
                    ];
                }
            }
        }

        return $fields;
    }

    /**
     * Подгрузка товаров в данные повторителя
     *
     * @param $fields
     * @param $data
     *
     * @return mixed
     */
    protected function setRepeaterProducts($fields, $data){
        foreach($data as $i => $fields_data){
            foreach($fields as $field){
                if(isset($fields_data->{$field->slug})){
                    if($field->type == 'repeater'){
                        $data[$i]->{$field->slug} = $this->setRepeaterProducts($field->fields, $fields_data->{$field->slug});
                    }elseif($field->type == 'product'){
                        $products = new Product();
                        $data[$i]->{$field->slug} = [
                            'id' => $fields_data->{$field->slug},
                            'product' => $products->find($fields_data->{$field->slug})
                        ];
                    }
                }
            }
        }

        return $data;
    }

	/**
	 * Обновление настроек шаблона
	 *
	 * @param $template
	 * @param $data
	 *
	 * @return mixed
	 */
	protected function updateTemplateData($template, $data){
		foreach($template as $i => $field){
			if(isset($data[$i]) && ($field->type == $data[$i]->type || (in_array($field->type, ['text', 'textarea', 'wysiwyg']) && in_array($data[$i]->type, ['text', 'textarea', 'wysiwyg'])))){
				if($field->type == 'repeater'){
					$template[$i]->fields = $this->updateTemplateData($template[$i]->fields , $data[$i]->fields);
					if(isset($data[$i]->data)){
						$template[$i]->data = $data[$i]->data;
					}else{
						$template[$i]->data = [];
					}
				}elseif(isset($data[$i]->value)){
					$template[$i]->value = $data[$i]->value;
				}
			}elseif($field->type == 'repeater'){
				$template[$i]->data = [];
			}
		}

    	return $template;
	}
}
