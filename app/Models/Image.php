<?php

namespace App\Models;

use Intervention\Image\ImageManagerStatic as Img;

class Image extends Entity
{
	protected $files_dir = 'thimbnails';
	protected $files_path = DIRECTORY_SEPARATOR.'thimbnails'.DIRECTORY_SEPARATOR;
	protected $max_width = 3840;
	protected $max_height = 1920;

	protected $fillable = [
		'file_id',
		'path',
		'mime',
		'size',
		'width',
		'height',
		'crop'
	];

	public static function boot(){
		parent::boot();

		self::deleting(function($model){
			if($model->size != 'full'){
				$file_path = public_path($model->path);
				if(is_file($file_path)){
					unlink($file_path);
				}
			}
		});
	}

	public function file(){
		return $this->belongsTo('App\Models\File', 'file_id');
	}

	public function user_data(){
		return $this->hasOne('App\Models\UserData', 'image_id', 'id');
	}

	/**
	 * URL изображения
	 *
	 * @return mixed|string
	 */
	public function getUrlAttribute(){
		$url = env('APP_URL').DIRECTORY_SEPARATOR.$this->path;
        $url = str_replace('\\', '/', $url);

		return $url;
	}

	/**
	 * Создание миниатюры изображения
	 *
	 * @param string $size
	 * @param string $crop
	 *
	 * @return $this
	 */
	public function createThumbnail($size = 'full', $crop = 'original'){
		if(!is_array($size)){
			$size = explode('_', $size);
		}

		if(count($size) == 2){
			$path = $this->updateImageSize($this->path, $size[0], $size[1], $crop);
			if(!empty($path)){
				$image_data = getimagesize(public_path($path));
				return $this->find($this->insertGetId([
					'file_id' => $this->file_id,
					'path' => $path,
					'mime' => $image_data['mime'],
					'size' => implode('_', $size),
					'width' => $image_data[0],
					'height' => $image_data[1],
					'crop' => $crop
				]));
			}
		}

		return $this;
	}

	/**
	 * Создание Webp копии изображения
	 *
	 * @return null
	 */
	public function createWebp(){
		$file_path = public_path($this->path);
		if(is_file($file_path) && function_exists('imagewebp')){
			$extension = strtolower(pathinfo($this->path, PATHINFO_EXTENSION ));
			$webp_name = str_replace('.'.$extension, '_'.$extension.'.webp', basename($this->path));
			$webp_path = public_path($this->files_path.$webp_name);

			$image = $this->imageCreateFromFile($file_path);
			imagepalettetotruecolor($image);
			imagealphablending($image, true);
			imagesavealpha($image, true);
			imagewebp($image, $webp_path);
			imagedestroy($image);

			return $this->find($this->insertGetId([
				'file_id' => $this->file_id,
				'path' => $this->files_dir.DIRECTORY_SEPARATOR.$webp_name,
				'mime' => 'image/webp',
				'size' => $this->size,
				'width' => $this->width,
				'height' => $this->height,
				'crop' => $this->crop,
			]));
		}

		return null;
	}

	/**
	 * Динамическое создание объекта изображения из файла
	 *
	 * @param $filename
	 * @return resource
	 */
	public function imageCreateFromFile($filename){
		$mime = mime_content_type($filename);
		if($mime == 'image/webp'){
			return imagecreatefromwebp($filename);
		}elseif($mime == 'image/jpeg'){
			return imagecreatefromjpeg($filename);
		}elseif($mime == 'image/png'){
			return imagecreatefrompng($filename);
		}elseif($mime == 'image/gif'){
			return imagecreatefromgif($filename);
		}

		switch (strtolower(pathinfo($filename, PATHINFO_EXTENSION ))) {
			case 'jpeg':
			case 'jpg':
				return imagecreatefromjpeg($filename);
				break;

			case 'png':
				return imagecreatefrompng($filename);
				break;

			case 'gif':
				return imagecreatefromgif($filename);
				break;
		}

		return null;
	}

	/**
	 * Создание изображения заданного размера
	 * @param string $href Имя файла
	 * @param int $w Ширина
	 * @param int $h Высота
	 * @param string $method (contain|cover|crop)/(уместить/заполнить/обрезать)
	 * @return string Имя созданного файла
	 */
	public function updateImageSize($href = '', $w = 0, $h = 0, $method = 'contain'){
		if(empty($href)){
			$href = $this->path;
		}
		$href = basename($href);
		$name_parts = explode('.', $href);
		$extension = end($name_parts);

		$path = public_path($this->path);
		$new_name = '';
		if(@getimagesize($path)) {
            $mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
            if(strtolower($mime) == 'image/webp' && !function_exists('imagecreatefromwebp')) {
                return $new_name;
            }
			$image           = Img::make( $path );
			$original_width  = $image->width();
			$original_height = $image->height();

			if (($original_width < $w && $original_height < $h) || ($method != 'contain' && ($original_width < $w || $original_height < $h))) {
				return $new_name;
			}

			if ( $original_width / $w >= $original_height / $h ) {
				switch ( $method ) {
					case 'contain':
						$image->resize( $w, null, function ( $constraint ) {
							$constraint->aspectRatio();
						} );
						$new_name = $this->save_image_file( $image, $original_width, $original_height, $w, $image->height(), $extension, $href );
						break;
					case 'cover':
						$image->resize( null, $h, function ( $constraint ) {
							$constraint->aspectRatio();
						} );
						$new_name = $this->save_image_file( $image, $original_width, $original_height, $image->width(), $h, $extension, $href );
						break;
					case 'crop':
						$image->resize( null, $h, function ( $constraint ) {
							$constraint->aspectRatio();
						} );
						$new_name = $this->save_image_file( $image, $original_width, $original_height, $w, $h, $extension, $href, 'crop' );
						break;
				}
			} elseif ( $original_width / $w <= $original_height / $h ) {
				switch ( $method ) {
					case 'contain':
						$image->resize( null, $h, function ( $constraint ) {
							$constraint->aspectRatio();
						} );
						$new_name = $this->save_image_file( $image, $original_width, $original_height, $image->width(), $h, $extension, $href );
						break;
					case 'cover':
						$image->resize( $w, null, function ( $constraint ) {
							$constraint->aspectRatio();
						} );
						$new_name = $this->save_image_file( $image, $original_width, $original_height, $w, $image->height(), $extension, $href );
						break;
					case 'crop':
						$image->resize( $w, null, function ( $constraint ) {
							$constraint->aspectRatio();
						} );
						$new_name = $this->save_image_file( $image, $original_width, $original_height, $w, $h, $extension, $href, 'crop' );
						break;
				}
			} else {
				$new_name = $this->save_image_file( $image, $original_width, $original_height, $w, $h, $extension, $href, 'resize' );
			}
		}

		return $new_name;
	}

	public function save_image_file($image, $original_width, $original_height, $result_width, $result_height, $extension, $href, $method = ''){
		if($original_width != $result_width || $original_height != $result_height){
			$new_name = str_replace('.'.$extension, '_'.$result_width.'x'.$result_height.'.'.$extension, $href);
			$new_path = public_path($this->files_path . $new_name);
			switch ($method) {
				case 'resize':
					$image->resize($result_width, $result_height)->save($new_path);
					break;
				case 'crop':
					$image->crop($result_width, $result_height)->save($new_path);
					break;
				default:
					$image->save($new_path);
			}
			return $this->files_dir.DIRECTORY_SEPARATOR.$new_name;
		}else{
			return '';
		}
	}
}