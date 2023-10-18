<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Http\Request;

use Validator;
use App\Models\News;
use App\Models\User;
use App\Models\Action;
use Config;

class NewsController extends Controller
{
	public $articles;
	public $users;
	public $current_user;

	protected $rules = [
		'title' => 'required|unique:news'
	];
	protected $messages = [
		'title.required' => 'Поле должно быть заполнено!',
		'title.unique' => 'Поле должно быть уникальным!'
	];

	public function __construct(News $articles, User $users){
		$this->articles = $articles;
		$this->users = $users;
		$this->current_user = Sentinel::getUser();
	}

	public function showAction($data){
		$article = $data->seo->seotable;

		if(empty($article->published)){
			abort(404);
		}

		return view('public.news_item')
			->with('article', $article)
			->with('seo', $data->seo)
			->with('products', $article->products)
			->with('last', $article->last())
			->withShortcodes();
	}

	public function adminIndexAction(){
		$articles = $this->articles->orderBy('id', 'Desc')->paginate(10);
		return view('admin.news.index')->with('articles', $articles);
	}

	public function adminStoreAction(Request $request){
		$validator = Validator::make($request->all(), ['name_'.Config::get('app.locale') => 'required'], ['name_'.Config::get('app.locale').'.required' => 'Поле должно быть заполнено!']);

		if($validator->fails()){
			return redirect()
				->back()
				->withInput()
				->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
				->withErrors($validator);
		}

		$user_id = $this->current_user->id;

		$id = $this->articles->insertGetId(['user_id' => $user_id, 'published' => 0, 'file_id' => null]);
		$article = $this->articles->find($id);
		$article->saveSeo($request);
		$article->saveLocalization($request);

        Action::createEntity($article);

		return response()->json(['result' => 'success', 'redirect' => '/admin/news/edit/'.$id]);
	}

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
	public function adminEditAction(Request $request, $id){
		$article = $this->articles->findOrFail($id);

		return view('admin.news.edit')
			->with('article', $article)
            ->with('products', $article->productsList(!empty($request->page) ? $request->page : 1))
			->with('seo', $article->seo)
			->with('editors', localizationFields(['body', 'seo_description']))
			->with('languages', Config::get('app.locales_names'));
	}

	public function adminUpdateAction($id, Request $request){
		$rules = [
			'name_'.Config::get('app.locale') => 'required',
			'body_'.Config::get('app.locale') => 'required'
		];
		$messages = [
			'name_'.Config::get('app.locale').'.required' => 'Поле должно быть заполнено!',
			'body_'.Config::get('app.locale').'.required' =>'Поле должно быть заполнено!'
		];

		$validator = Validator::make($request->all(), $rules, $messages);

		if($validator->fails()){
			return redirect()
				->back()
				->withInput()
				->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
				->withErrors($validator);
		}

		$article = $this->articles->find($id);

        $article_data = $article->fullData();

		$article->fill($request->only(['published', 'file_id']));
		$article->save();
		$article->saveSeo($request);
		$article->saveLocalization($request);

        Action::updateEntity($this->articles->find($id), $article_data);

		return redirect('/admin/news')
			->with('message-success', 'Статья ' . $article->name . ' успешно обновлена.');
	}

	public function adminDestroyAction($id){
		$article = $this->articles->find($id);

		$title = $article->title;

        Action::deleteEntity($article);

		$article->delete();

		return redirect('/admin/news')
			->with('message-success', 'Статья ' . $title . ' успешно удалена.');
	}
}
