<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Models\Photos;
use App\Models\User;
use Validator;
use Config;

class PhotosController extends Controller
{
    public $photos;
    public $users;
    public $current_user;

    protected $rules = [
        'title' => 'required|unique:blog'
    ];

    protected $messages = [
        'title.required' => 'Поле должно быть заполнено!',
        'title.unique' => 'Поле должно быть уникальным!'
    ];

    public function __construct(Photos $photos, User $users){
        $this->photos = $photos;
        $this->users = $users;
        $this->current_user = Sentinel::getUser();
    }

    public function indexAction($data){
        $galleries = $this->photos->where('visible', 1)
            ->orderBy('updated_at', 'desc')
            ->paginate(16);

        return view('public.galleries')
            ->with('galleries', $galleries)
            ->with('seo', $data->seo);
    }

    public function showAction($data){
        $gallery = $data->seo->seotable;

        if(empty($gallery->visible)){
            abort(404);
        }

        return view('public.gallery')
            ->with('gallery', $gallery)
            ->with('seo', $data->seo);
    }

    public function adminIndexAction(){
        $galleries = $this->photos->orderBy('id', 'Desc')->paginate(10);
        return view('admin.photos.index')->with('galleries', $galleries);
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

        $id = $this->photos->insertGetId(['visible' => 0, 'file_id' => null]);
        $gallery = $this->photos->find($id);
        $gallery->saveSeo($request);
        $gallery->saveLocalization($request);

        return response()->json(['result' => 'success', 'redirect' => '/admin/photos/edit/'.$id]);
    }

    public function adminEditAction($id){
        $gallery = $this->photos->findOrFail($id);

        return view('admin.photos.edit')
            ->with('gallery', $gallery)
            ->with('seo', $gallery->seo)
            ->with('editors', localizationFields(['description', 'result', 'review', 'seo_description']))
            ->with('languages', Config::get('app.locales_names'));
    }

    public function adminUpdateAction($id, Request $request){
        $rules = [
            'name_'.Config::get('app.locale') => 'required'
        ];
        $messages = [
            'name_'.Config::get('app.locale').'.required' => 'Поле должно быть заполнено!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $gallery = $this->photos->find($id);
        foreach($gallery->photos as $photo){
            $photo->localization()->delete();
        }
        $gallery->photos()->delete();
        $count = 0;
        if(!empty($request->photos)){
            foreach($request->photos as $photo){
                if(!empty($photo['file_id'])){
                    $p = $gallery->photos()->create([
                        'file_id' => $photo['file_id'],
                    ]);
                    $r = new Request();
                    $r->merge($photo);
                    $p->saveLocalization($r);
                    $count++;
                }
            }
        }

        $gallery->fill($request->only(['visible', 'file_id']));
        $gallery->count = $count;
        $gallery->save();
        $gallery->saveSeo($request);
        $gallery->saveLocalization($request);

        return redirect('/admin/photos')
            ->with('message-success', 'Фотогаллерея ' . $gallery->name . ' успешно обновлена.');
    }

    public function adminDestroyAction($id){
        $gallery = $this->photos->find($id);
        $title = $gallery->name;

        foreach($gallery->photos as $photo){
            $photo->localization()->delete();
        }
        $gallery->photos()->delete();
        $gallery->delete();

        return redirect('/admin/photos')
            ->with('message-success', 'Фотогаллерея ' . $title . ' успешно удалена.');
    }
}
