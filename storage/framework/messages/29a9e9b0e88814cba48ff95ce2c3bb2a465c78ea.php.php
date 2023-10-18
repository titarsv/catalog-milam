<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ProductAttributes;
use App\Models\AttributeValue;
use App\Models\Attribute;
use App\Models\Action;
use Validator;
use Config;

class AttributesController extends Controller
{
    private $rules = [
        'name_ru' => 'required'
    ];

    private $messages = [
        'name_ru.required' => 'Поле должно быть заполнено!',
        'values.*.value.distinct' => 'Значения одинаковы!',
        'values.*.value.filled' => 'Поле должно быть заполнено!',
        'max_quantity.required_if' => 'Поле должно быть заполнено!',
        'image_width.numeric' => 'Значение должно быть числовым!',
        'image_height.numeric' => 'Значение должно быть числовым!',
        'max_quantity.numeric' => 'Значение должно быть числовым!',
    ];

    /**
     * Список атрибутов
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndexAction(Request $request){
        if(!empty($request->search)){
            $attributes = Attribute::select('attributes.*')->join('localization', function($leftJoin) {
                $leftJoin->on('attributes.id', '=', 'localization.localizable_id')
                    ->where('localization.localizable_type', '=', 'Attributes')
                    ->where('localization.language', '=', 'ru')
                    ->where('localization.field', 'name');
            })
            ->where('localization.value', 'like', '%'.$request->search.'%')
            ->with('localization')
            ->paginate(20);
        }else{
            $attributes = Attribute::paginate(20);
        }

        return view('admin.attributes.index')
            ->with('current_search', $request->search)
            ->with('attributes', $attributes);
    }

	/**
	 * Создание атрибутов
	 *
	 * @param Request $request
	 * @param Attribute $attributes
	 *
	 * @return $this
	 */
    public function adminStoreAction(Request $request, Attribute $attributes){
	    $validator = Validator::make($request->all(), ['name_'.Config::get('app.locale') => 'required'], ['name_'.Config::get('app.locale').'.required' => 'Поле должно быть заполнено!']);

	    if($validator->fails()){
		    return response()->json($validator);
	    }

	    $name_key = 'name_'.Config::get('app.locale');

	    $id = $attributes->insertGetId(['slug' => Str::slug(str_replace(['-', '_', ' '], '', mb_strtolower(translit($request->$name_key))))]);
	    $attribute = $attributes->find($id);
	    $attribute->saveLocalization($request);

        $attribute->load('localization');
        Action::createEntity($attribute);

	    return response()->json(['result' => 'success', 'redirect' => '/admin/attributes/edit/'.$id]);
    }

	/**
	 * Страница обновления атрибута
	 *
	 * @param $id
	 *
	 * @return $this
	 */
    public function adminEditAction($id){
        $attribute = Attribute::find($id);

        return view('admin.attributes.edit')
            ->with('languages', Config::get('app.locales_names'))
            ->with('attribute', $attribute);
    }

	/**
	 * Обновление атрибута
	 *
	 * @param Request $request
	 * @param $id
	 *
	 * @return $this
	 */
    public function adminUpdateAction(Request $request, $id){
        $rules = $this->rules;
        $rules['values.*.name_ru'] = 'distinct|filled';
        $rules['values.*.name_ua'] = 'distinct|filled';
        $rules['values.*.value'] = 'distinct|filled';

        $validator = Validator::make($request->all(), $rules, $this->messages);

        if($validator->fails()){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $attribute = Attribute::find($id);

        $attribute_data = $attribute->fullData();

        $attribute->fill($request->only(['slug']));
	    $attribute->saveLocalization($request);
        $attribute->save();

        if(!empty($request->values)){
            foreach ($request->values as $attribute_value_id => $value){
                $attribute_value = AttributeValue::find($attribute_value_id);
                $attribute_value->value = str_replace(['-', '_', ' '], '',$value['value']);
                $attribute_value->file_id = $value['file_id'];
                $attribute_value->save();
                $new_request = new Request();
                $new_request->merge([
                    'name_ru' => $value['name_ru'],
                    'name_ua' => $value['name_ua'],
                    'name_en' => $value['name_en']
                ]);
                $attribute_value->saveLocalization($new_request);
            }
        }

        Action::updateEntity($attribute->find($id), $attribute_data);

        return redirect('/admin/attributes')
            ->with('message-success', 'Атрибут ' .$attribute->name . ' успешно обновлен.');
    }

	/**
	 * Удаление атрибута
	 *
	 * @param $id
	 *
	 * @return $this
	 */
    public function adminDestroyAction($id){
	    ProductAttributes::where('attribute_id', $id)->delete();
        $attribute = Attribute::find($id);

        Action::deleteEntity($attribute);

	    $attribute->values()->delete();
        $attribute->delete();

        return redirect('/admin/attributes')
            ->with('message-success', 'Атрибут ' .$attribute->name . ' успешно удален.');
    }

	/**
	 * Создание значения атрибута
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
    public function adminStoreValueAction(Request $request){
	    $name_key = 'name_'.Config::get('app.locale');

        $attribute = Attribute::find($request->attribute_id);
        $attribute_data = $attribute->fullData();

	    $attribute_value = new AttributeValue;

	    $id = $attribute_value->insertGetId([
	    	'attribute_id' => $request->attribute_id,
		    'value' => Str::slug(str_replace(['-', '_', ' ', '/'], '', mb_strtolower(translit($request->$name_key))))
        ]);
	    $value = $attribute_value->find($id);

	    $value->saveLocalization($request);

        Action::updateEntity($attribute->find($request->attribute_id), $attribute_data);

	    return response()->json(['result' => 'success', 'html' => view('admin.attributes.value')->with('value', $value)->render()]);
    }

	/**
	 * Удаление значения атрибута
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function adminDestroyValueAction($id){
		$value = AttributeValue::find($id);

//        $attribute = Attribute::find($value->attribute_id);
//        $attribute_data = $attribute->fullData();
        ProductAttributes::where('attribute_value_id', $id)->delete();

	    $value->delete();

//        Action::updateEntity($attribute->find($id), $attribute_data);

	    return response()->json(['result' => 'success']);
    }
}
