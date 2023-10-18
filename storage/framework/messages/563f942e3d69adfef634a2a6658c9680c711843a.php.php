<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Action;

class MediaController extends Controller
{
	protected $files;
	protected $destinationDir = 'uploads';
	protected $destinationPath = DIRECTORY_SEPARATOR.'uploads';
	protected $destinationPathSmall = DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'cache';

	public function __construct(File $files){
		$this->files = $files;
		$this->destinationDir = env('UPLOADS_DIR', 'uploads');
		$this->destinationPath = DIRECTORY_SEPARATOR.env('UPLOADS_DIR', 'uploads');
		$this->destinationPathSmall = DIRECTORY_SEPARATOR.env('UPLOADS_DIR', 'uploads').DIRECTORY_SEPARATOR.'cache';
	}

	/**
	 * Страница медиафайлов
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index(){
		return view('admin.media.index')
			->with('active', File::count())
			->with('is_trash', false)
			->with('trashed', File::onlyTrashed()->count());
	}

	public function trash(){
		return view('admin.media.index')->with('trash', true)
			->with('active', File::count())
            ->with('is_trash', true)
			->with('trashed', File::onlyTrashed()->count());
	}

	/**
	 * Загрузка медиафайла
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function upload(Request $request){
		$file = $request->file('async-upload');
		$destinationPath = public_path().$this->destinationPath;

		$type = $file->guessExtension();
		$newFileName = $this->generate_filename($file, $destinationPath);
        $file->move($destinationPath, $newFileName);
        $hash = md5_file($destinationPath.DIRECTORY_SEPARATOR.$newFileName);
        $isset = File::where('hash', $hash)->first();
        if(empty($isset)){
            $file = $this->files->createFile([
                'title' => $file->getClientOriginalName(),
                'path' => $this->destinationDir.DIRECTORY_SEPARATOR.$newFileName,
                'type' => in_array($type, ['jpg', 'jpeg', 'png', 'gif']) ? 'image' : ($type == 'mp4' ? 'video' : $type),
                'hash' => $hash
            ]);

            Action::createEntity($file);

            $response = [
                'success' => true,
                'data' => $file->data
            ];
        }else{
            if(is_file($destinationPath.DIRECTORY_SEPARATOR.$newFileName))
                unlink($destinationPath.DIRECTORY_SEPARATOR.$newFileName);

            $response = [
                'success' => false,
                'data' => [
                    'message' => 'Такой файл уже существует в хранилище под названием "'.$isset->title.'"'
                ]
            ];
        }

		return response()->json($response);
	}

	/**
	 * Генерация уникального имени файла
	 *
	 * @param $file
	 * @param string $path
	 * @return mixed
	 */
	public function generate_filename($file, $path = ''){
		if(empty($path))
			$path = public_path().$this->destinationPath;

		$originalName = str_replace(' ', '_', translit($file->getClientOriginalName()));

		if(is_file($path.DIRECTORY_SEPARATOR.$originalName)) {
			$paths = explode('.', $originalName);
			$extension = end($paths);

			$type = $file->guessExtension();
			$extensions = [
				'jpeg' => 'jpg',
				'jpg' => 'jpg',
				'png' => 'png',
				'gif' => 'gif',
				'mp4' => 'mp4'
			];
			if(isset($extensions[$type])){
				$try_extension = $extensions[$type];
			}else{
				$try_extension = strtolower($extension);
			}

			$i = 2;
			$originalName = preg_replace('/(.+)(_\(\d+\))?\.'.$extension.'/', '$1_('.$i.').'.$try_extension, $originalName);
			while(is_file($path.DIRECTORY_SEPARATOR.$originalName)){
				$originalName = preg_replace('/(.+)(_\(\d+?\))\.'.$extension.'/', '$1_('.$i.').'.$try_extension, $originalName);
				$i++;
			}
		}

		return $originalName;
	}

	/**
	 * Формирование размера файла
	 *
	 * @param $value
	 *
	 * @return mixed
	 */
	public function convert_hr_to_bytes($value){
		$value = strtolower(trim($value));
		$bytes = (int) $value;

		if ( false !== strpos( $value, 'g' ) ) {
			$bytes *= 1024*1024*1024;
		} elseif ( false !== strpos( $value, 'm' ) ) {
			$bytes *= 1024*1024;
		} elseif ( false !== strpos( $value, 'k' ) ) {
			$bytes *= 1024;
		}

		// Deal with large (float) values which run into the maximum integer size.
		return min( $bytes, PHP_INT_MAX );
	}
}
