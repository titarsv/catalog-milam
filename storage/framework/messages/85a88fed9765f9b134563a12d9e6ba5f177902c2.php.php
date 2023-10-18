<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Http\Request;

use Validator;
use App\Models\Blog;
use App\Models\User;
use Config;

class BlogController extends Controller
{
    public $articles;
    public $users;
    public $current_user;

    protected $rules = [
        'title' => 'required|unique:blog'
    ];
    protected $messages = [
        'title.required' => 'Поле должно быть заполнено!',
        'title.unique' => 'Поле должно быть уникальным!'
    ];

    public function __construct(Blog $articles, User $users){
        $this->articles = $articles;
        $this->users = $users;
        $this->current_user = Sentinel::getUser();
    }

    public function indexAction($data){
        $articles = $this->articles->where('status', 1)->orderBy('updated_at', 'desc')->paginate(16);

        return view('public.blog')
            ->with('seo', $data->seo)
            ->with('articles', $articles);
    }

    public function showAction($data){
        $article = $data->seo->seotable;

        if(empty($article->status)){
            abort(404);
        }

        return view('public.article')
            ->with('article', $article)
            ->with('seo', $data->seo)
            ->with('last', $article->last())
            ->withShortcodes();
    }

    public function adminIndexAction(){
        $articles = $this->articles->orderBy('id', 'Desc')->paginate(10);
        return view('admin.blog.index')->with('articles', $articles);
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

        $id = $this->articles->insertGetId(['user_id' => $user_id, 'status' => 0, 'image_id' => null]);
        $article = $this->articles->find($id);
        $article->saveSeo($request);
        $article->saveLocalization($request);

        return response()->json(['result' => 'success', 'redirect' => '/admin/blog/edit/'.$id]);
    }

    public function adminEditAction($id){
        $article = $this->articles->findOrFail($id);

        return view('admin.blog.edit')
            ->with('article', $article)
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
        $article->fill($request->only(['status', 'image_id']));
        $article->save();
        $article->saveSeo($request);
        $article->saveLocalization($request);

        return redirect('/admin/blog')
            ->with('message-success', 'Статья ' . $article->name . ' успешно обновлена.');
    }

    public function adminDestroyAction($id){
        $article = $this->articles->find($id);

        $title = $article->title;

        $article->delete();

        return redirect('/admin/blog')
            ->with('message-success', 'Статья ' . $title . ' успешно удалена.');
    }
}
