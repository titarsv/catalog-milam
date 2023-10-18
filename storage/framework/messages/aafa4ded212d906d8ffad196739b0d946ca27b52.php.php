<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Models\Seo;
use App\Models\Redirect;
use App\Models\Setting;
use App\Models\Image;
use App\Models\Action;
use Validator;
use Config;

class SeoController extends Controller
{
	private $user;
	private $settings;

	function __construct(Setting $settings){
		$this->user = Sentinel::check();
		$this->settings = $settings;
	}

    private $rules = [
        'url' => 'required|unique:seo',
    ];

    private $messages = [
        'name.required' => 'Поле должно быть заполнено!',
        'meta_title.required' => 'Поле должно быть заполнено!',
        'url.required' => 'Поле должно быть заполнено!',
        'url.unique' => 'Значение должно быть уникальным для каждой записи!'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if($request->search){
            $current_search = $request->search;
            $seo = Seo::where('url', 'like', '%' . $current_search . '%')->get();

            // Пагинация
            $paginator_options = [
                'path' => url($request->url()),
            ];

            $per_page = 20;
            $current_page = $request->page ? $request->page : 1;
            $current_page_redirects = $seo->slice(($current_page - 1) * $per_page, $per_page)->all();
            $seo = new LengthAwarePaginator($current_page_redirects, count($seo), $per_page, $current_page, $paginator_options);
            $seo->appends(['search' => $current_search]);
        }else{
            $current_search = '';
            $seo = Seo::paginate(20);
        }

        return view('admin.seo.index')
            ->with('seo', $seo)
            ->with('current_search', $current_search);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('admin.seo.create')
            ->with('languages', Config::get('app.locales_names'))
            ->with('editors', localizationFields(['body', 'seo_description']));
    }

    /**
     * Создание новой сео записи
     *
     * @param Request $request
     * @param Seo $seo
     * @return $this
     */
    public function store(Request $request, Seo $seo){
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $seo->fill($request->except('_token'));
        $seo->seotable_id = !empty($request->seotable_id) ? $request->seotable_id : 0;
        $seo->action = !empty($request->action) ? $request->action : 'showAction';
        $seo->save();
        $seo->saveLocalization($request);

        Action::createEntity($seo);

        return redirect('/admin/seo/list')
            ->with('message-success', 'Запись ' . $seo->name . ' успешно добавлена.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $seo = Seo::find($id);

        return view('admin.seo.edit')
            ->with('seo', $seo)
	        ->with('languages', Config::get('app.locales_names'))
	        ->with('editors', localizationFields(['seo_description']));
    }

	/**
	 * @param Request $request
	 * @param $id
	 * @param Seo $seo
	 *
	 * @return $this
	 */
    public function update(Request $request, $id, Seo $seo){
        $rules = $this->rules;
        $rules['url'] = 'required|unique:seo,url,'.$id;

        $validator = Validator::make($request->all(), $rules, $this->messages);

        if($validator->fails()){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $seo = $seo->find($id);

        $seo_data = $seo->fullData();

        $seo->fill($request->only(['canonical', 'robots', 'url']));
        $seo->save();
        $seo->saveLocalization($request);

        Action::updateEntity($seo->find($id), $seo_data);

        return redirect('/admin/seo/list')
            ->with('message-success', 'Запись ' . $seo->name . ' успешно обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $seo = Seo::find($id);

        Action::deleteEntity($seo);

        $seo->delete();

        return redirect('/admin/seo/list')
            ->with('message-success', 'Запись ' . $seo->name . ' успешно удалена.');
    }

	public function seoSettings(){
		$settings = $this->settings->get_all();

		$image_sizes = config('image.sizes');

		return view('admin.seo_settings')
			->with('user', $this->user)
			->with('settings', $settings)
			->with('image_sizes', $image_sizes)
			->with('image', empty($settings->ld_image) ? Image::find(1) : Image::find($settings->ld_image));
	}

	public function seoUpdate(Request $request, Setting $settings){
		$rules = [
			'meta_title' => 'required',
			'ld_type' => 'required',
			'ld_name' => 'required',
			'ld_description' => 'required',
		];

		$messages = [
			'meta_title.required' => 'Поле Title должно быть заполнено!',
			'ld_type.required' => 'Поле Тип должно быть заполнено!',
			'ld_name.required' => 'Поле Название организации должно быть заполнено!',
			'ld_description.required' => 'Поле Описание должно быть заполнено!',
		];

		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()
				->back()
				->withInput()
				->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
				->withErrors($validator);
		}

		$data = $request->except('_token');
		$data['social'] = array_diff($data['social'], ['']);

		$settings->update_settings($data, true);

		return back()->with('message-success', 'Настройки успешно сохранены!');
	}

	public function redirects(Request $request, Redirect $redirects){
		if($request->search){
			$current_search = $request->search;
			$redirects = $redirects->where('old_url', 'like', '%' . $current_search . '%')->orWhere('new_url', 'like', '%' . $current_search . '%')->get();

			// Пагинация
			$paginator_options = [
				'path' => url($request->url()),
			];

			$per_page = 50;
			$current_page = $request->page ? $request->page : 1;
			$current_page_redirects = $redirects->slice(($current_page - 1) * $per_page, $per_page)->all();
			$redirects = new LengthAwarePaginator($current_page_redirects, count($redirects), $per_page, $current_page, $paginator_options);
		}else{
			$current_search = '';
			$redirects = $redirects::paginate(50);
		}

		return view('admin.seo.redirects.index')
			->with('current_search', $current_search)
			->with('redirects', $redirects);
	}

	public function createRedirect(){
		return view('admin.seo.redirects.create');
	}

	public function storeRedirect(Request $request, Redirect $redirects){
		$validator = Validator::make($request->all(), [
			'old_url' => 'required|unique:redirects,old_url',
			'new_url' => 'required'
		], [
			'old_url.required' => 'Обязательное поле',
			'old_url.unique' => 'Поле должно быть уникальным',
			'new_url.required' => 'Обязательное поле'
		]);

		if ($validator->fails()) {
			return redirect()
				->back()
				->withInput()
				->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
				->withErrors($validator);
		}

		$redirects->fill($request->except('_token'));
		$redirects->save();

		$redirects->where('new_url', $request->old_url)->update(['new_url' => $request->new_url]);

        Action::createEntity($redirects);

		return redirect('/admin/seo/redirects')
			->with('message-success', 'Редирект успешно добавлен.');
	}

	public function destroyRedirect($id){
		$redirect = Redirect::find($id);

        Action::deleteEntity($redirect);

		$redirect->delete();

		return redirect('/admin/seo/redirects')
			->with('message-success', 'Редирект успешно удалён.');
	}

	public function editRedirect($id){
		$redirect = Redirect::find($id);

		return view('admin.seo.redirects.edit')
			->with('redirect', $redirect);
	}

	public function updateRedirect(Request $request, Redirect $redirects, $id){
		$validator = Validator::make($request->all(), [
			'old_url' => 'required|unique:redirects,old_url,'.$id,
			'new_url' => 'required'
		], [
			'old_url.required' => 'Обязательное поле',
			'old_url.unique' => 'Поле должно быть уникальным',
			'new_url.required' => 'Обязательное поле'
		]);

		if ($validator->fails()) {
			return redirect()
				->back()
				->withInput()
				->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
				->withErrors($validator);
		}

		$redirect = $redirects->find($id);

        $redirect_data = $redirect->fullData();

		$redirect->fill($request->except('_token'));
		$redirect->save();

        Action::updateEntity($redirects->find($id), $redirect_data);

		return redirect('/admin/seo/redirects')
			->with('message-success', 'Запись успешно обновлена.');
	}
}
