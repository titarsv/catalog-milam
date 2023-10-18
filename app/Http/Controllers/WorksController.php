<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\User;
use Validator;
use Config;

class WorksController extends Controller
{
    public $works;
    public $users;
    public $current_user;

    protected $rules = [
        'title' => 'required|unique:blog'
    ];

    protected $messages = [
        'title.required' => 'Поле должно быть заполнено!',
        'title.unique' => 'Поле должно быть уникальным!'
    ];

    public function __construct(Work $works, User $users){
        $this->works = $works;
        $this->users = $users;
        $this->current_user = Sentinel::getUser();
    }

    public function indexAction($data){
        $works = $this->works->where('visible', 1)
            ->orderBy('updated_at', 'desc')
            ->withCount('gallery')
            ->paginate(8);

        return view('public.works')
            ->with('works', $works)
            ->with('seo', $data->seo);
    }

    public function showAction($data){
        $work = $data->seo->seotable;

        if(empty($work->visible)){
            abort(404);
        }

        $work->loadCount('gallery');
        $work->load('gallery');

        return view('public.work')
            ->with('work', $work)
            ->with('seo', $data->seo)
            ->with('recommended', $work->recommended());
    }

    public function adminIndexAction(){
        $works = $this->works->orderBy('id', 'Desc')->paginate(10);
        return view('admin.works.index')->with('works', $works);
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

        $id = $this->works->insertGetId(['visible' => 0, 'file_id' => null]);
        $work = $this->works->find($id);
        $work->saveSeo($request);
        $work->saveLocalization($request);

        return response()->json(['result' => 'success', 'redirect' => '/admin/works/edit/'.$id]);
    }

    public function adminEditAction($id){
        $work = $this->works->findOrFail($id);

        return view('admin.works.edit')
            ->with('work', $work)
            ->with('seo', $work->seo)
            ->with('editors', localizationFields(['description', 'result', 'review', 'seo_description']))
            ->with('languages', Config::get('app.locales_names'));
    }

    public function adminUpdateAction($id, Request $request){
        $rules = [
            'name_'.Config::get('app.locale') => 'required',
            'description_'.Config::get('app.locale') => 'required'
        ];
        $messages = [
            'name_'.Config::get('app.locale').'.required' => 'Поле должно быть заполнено!',
            'description_'.Config::get('app.locale').'.required' =>'Поле должно быть заполнено!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $work = $this->works->find($id);
        $work->fill($request->only(['visible', 'file_id', 'rating']));
        $work->confirmed = !empty($request->review_ru) || !empty($request->review_ua);
        $work->review_date = date('Y-m-d H:i:s', strtotime($request->review_date));
        $work->save();
        $work->saveSeo($request);
        $work->saveLocalization($request);
        $work->saveGalleries($request);

        return redirect('/admin/works')
            ->with('message-success', 'Работа ' . $work->name . ' успешно обновлена.');
    }

    public function adminDestroyAction($id){
        $works = $this->works->find($id);

        $title = $works->title;

        $works->delete();

        return redirect('/admin/works')
            ->with('message-success', 'Работа ' . $title . ' успешно удалена.');
    }
}
