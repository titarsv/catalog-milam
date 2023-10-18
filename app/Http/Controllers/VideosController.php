<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Models\Videos;
use App\Models\User;
use Validator;
use Config;

class VideosController extends Controller
{
	public $videos;
	public $users;
	public $current_user;

	protected $rules = [
		'title' => 'required|unique:blog'
	];

	protected $messages = [
		'title.required' => 'Поле должно быть заполнено!',
		'title.unique' => 'Поле должно быть уникальным!'
	];

	public function __construct(Videos $videos, User $users){
		$this->videos = $videos;
		$this->users = $users;
		$this->current_user = Sentinel::getUser();
	}

	public function indexAction($data){
		$galleries = $this->videos->where('visible', 1)
		                          ->orderBy('updated_at', 'desc')
		                          ->paginate(16);

		return view('public.video_galleries')
			->with('galleries', $galleries)
			->with('seo', $data->seo);
	}

	public function showAction($data){
		$gallery = $data->seo->seotable;

		if(empty($gallery->visible)){
			abort(404);
		}

		return view('public.video_gallery')
			->with('gallery', $gallery)
			->with('seo', $data->seo);
	}

	public function adminIndexAction(){
		$galleries = $this->videos->orderBy('id', 'Desc')->paginate(10);
		return view('admin.videos.index')->with('galleries', $galleries);
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

		$id = $this->videos->insertGetId(['visible' => 0, 'file_id' => null]);
		$gallery = $this->videos->find($id);
		$gallery->saveSeo($request);
		$gallery->saveLocalization($request);

		return response()->json(['result' => 'success', 'redirect' => '/admin/videos/edit/'.$id]);
	}

	public function adminEditAction($id){
		$gallery = $this->videos->findOrFail($id);

		return view('admin.videos.edit')
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

		$gallery = $this->videos->find($id);
		foreach($gallery->videos as $video){
            $video->localization()->delete();
		}
		$gallery->videos()->delete();
		$count = 0;
		if(!empty($request->videos)){
            foreach ($request->videos as $video) {
                if (!empty($video['link'])) {
                    $p = $gallery->videos()->create([
                        'link' => $video['link'],
                        'file_id' => $video['file_id'],
                    ]);
                    $r = new Request();
                    $r->merge($video);
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

		return redirect('/admin/videos')
			->with('message-success', 'Видеогаллерея ' . $gallery->name . ' успешно обновлена.');
	}

	public function adminDestroyAction($id){
		$gallery = $this->videos->find($id);
		$title = $gallery->name;

		foreach($gallery->videos as $videos){
			$videos->localization()->delete();
		}
		$gallery->delete();

		return redirect('/admin/videos')
			->with('message-success', 'Видеогаллерея ' . $title . ' успешно удалена.');
	}
}
