<?php

namespace App\Models;

use Dompdf\Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class File extends Entity
{
	protected $files_path =  DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
	protected $max_width = 3840;
	protected $max_height = 1920;

	protected $fillable = [
		'path',
		'title',
		'type',
		'data'
	];

	protected $casts = [
		'data' => 'array',
	];

	use SoftDeletes;
	protected $dates = ['deleted_at'];

    public $entity_type = 'file';
	protected $table = 'files';

    public function __construct(){
        parent::__construct();
        $this->files_path = DIRECTORY_SEPARATOR.env('UPLOADS_DIR', 'uploads').DIRECTORY_SEPARATOR;
    }

    public function images(){
        return $this->hasMany('App\Models\Image', 'file_id');
    }

    public function createFile($data){
        if(!isset($data['data'])){
            $data['data'] = json_encode([]);
        }

        $file = $this->find($this->insertGetId($data));
        if($file->type == 'image'){
            $file->createImage();
        }
        $file->data = $file->getFullData();
        $file->save();

        return $file;
    }

    public function updateData($data = null){
        $full_data = $this->getFullData();
        if(!empty($data)){
            if(!empty($data['subtype']))
                $full_data['subtype'] = $data['subtype'];
            if(!empty($data['sizes'])){
                foreach($data['sizes'] as $size => $size_data){
                    if(!isset($full_data['sizes'][$size])){
                        $full_data['sizes'][$size] = $size_data;
                    }else{
                        foreach($size_data as $crop => $links){
                            if(!isset($full_data['sizes'][$size][$crop])){
                                $full_data['sizes'][$size][$crop] = $links;
                            }else{
                                foreach($links as $type => $link){
                                    $full_data['sizes'][$size][$crop][$type] = $link;
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->data = $full_data;
        $this->save();
    }

    public function fileData($path = null){
        if(empty($path))
            $filepath = public_path().DIRECTORY_SEPARATOR.$this->path;
        else
            $filepath = public_path().DIRECTORY_SEPARATOR.$path;
        if(is_file($filepath)){
            try {
                $data                          = getimagesize( $filepath );
                $data['filesize']              = filesize( $filepath );
                $data['filesizeHumanReadable'] = $this->sizeFormat( $data['filesize'] );

                if(empty($data['mime'])){
                    $finfo = finfo_open(FILEINFO_MIME);
                    $mimetype = explode(';', finfo_file($finfo, $filepath));
                    finfo_close($finfo);
                    $data['mime'] = $mimetype[0];
                }
            }catch (Exception $e){
                $data = [0 => 0, 1 => 0, 'mime' => ' / ', 'filesize' => '', 'filesizeHumanReadable' => ''];
            }
        }else{
            $data = [0 => 0, 1 => 0, 'mime' => ' / ', 'filesize' => '', 'filesizeHumanReadable' => ''];
        }

        return $data;
    }

    public function sizeFormat($bytes, $decimals = 0){
        $quant = array(
            'TB' => 1024*1024*1024*1024,
            'GB' => 1024*1024*1024,
            'MB' => 1024*1024,
            'KB' => 1024,
            'B'  => 1,
        );

        if ( 0 === $bytes ) {
            return number_format( 0, abs(intval( $decimals )) ) . ' B';
        }

        foreach ( $quant as $unit => $mag ) {
            if ( doubleval( $bytes ) >= $mag ) {
                return number_format( $bytes / $mag, $decimals ) . ' ' . $unit;
            }
        }

        return false;
    }

    public function getFullData(){
        $url = str_replace(env('APP_URL'), '', $this->url());
        $filedata = $this->fileData();
        if(empty($filedata['mime'])){
            $mime = [];
        }else{
            $mime = explode('/', $filedata['mime']);
        }
        $sizes = [];
        $s = $this->images;
        $user = Sentinel::getUser();
        if($s->count()) {
            foreach ($s as $size) {
                $sizes[$size->size == 'full' ? $size->size : $size->id.'_'.$size->size] = [
                    'url' => str_replace(env('APP_URL'), '', $size->url),
                    'height' => $size->height,
                    'width' => $size->width,
                    'orientation' => 'landscape',
                ];
            }
        }
        if($s->count()) {
            foreach($s as $size){
                $sizes[$size->size == 'full' ? $size->size : $size->size][$size->crop][$size->mime == 'image/webp' ? 'webp' : 'url'] = str_replace(env('APP_URL'), '', $size->url);
            }
        }
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'filename' => $this->title,
            'url' => $url,
            'link' => $url,
            'alt' => $this->title,
            'author' => 'admin',
            'description' => '',
            'caption' => '',
            'name' => $this->title,
            'status' => 'inherit',
            'uploadedTo' => 0,
            'date' => isset($this->created_at) ? $this->created_at->timestamp : time(),
            'modified' => empty($this->updated_at) ? (isset($this->created_at) ? $this->created_at->timestamp : time()) : $this->updated_at->timestamp,
            'menuOrder' => 0,
            'mime' => empty($filedata['mime']) || empty(trim($filedata['mime'])) ? 'image/jpeg' : trim($filedata['mime']),
            'type' => empty($mime) || empty(trim($mime[0])) ? 'image' : trim($mime[0]),
            'subtype' => empty($mime) ? '' : $mime[1],
            'icon' => !empty($mime) && trim($mime[0]) == 'video' ? '/images/larchik/video.png' : '/images/larchik/default.png',
            'dateFormatted' =>  empty($this->updated_at) ? (!empty($this->created_at) ? $this->created_at->format('d.m.Y') : date('d.m.Y')) : $this->updated_at->format('d.m.Y'),
            'nonces' => [
                "update" => '',
                "delete" => $this->id > 1 ? '77118a539c' : '',
                "edit" => ''
            ],
            'editLink' => '',
            'meta' => false,
            'authorName' => !empty($user) ? $user->email : '',
            'filesizeInBytes' => $filedata['filesize'],
            'filesizeHumanReadable' => $filedata['filesizeHumanReadable'],
            'context' => '',
            'height' => isset($filedata[1]) ? $filedata[1] : '',
            'width' => isset($filedata[0]) ? $filedata[0] : '',
            'orientation' => "landscape",
            'sizes' => $sizes,
            'compat' => [
                'item' => '',
                'meta' => '',
            ],
        ];

        return $data;
    }

    /**
     * Загрузка по URL
     *
     * @param $url
     * @return mixed
     */
    public function uploadFromUrlImages($url, $author = 1){
        $destinationPath = public_path().$this->files_path;
        $parts = explode('/', $url);
        $parts = explode('?', end($parts));
        $originalName = $parts[0];
        $newFileName = $originalName;

        $file_data = file_get_contents($url);
        $pattern = "/^content-type\s*:\s*(.*)$/i";
        if(($header = array_values(preg_grep($pattern, $http_response_header))) && (preg_match($pattern, $header[0], $match) !== false)){
            $contentType = explode('/', $match[1]);
        }else{
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_exec($ch);
            $contentType = explode('/', curl_getinfo($ch, CURLINFO_CONTENT_TYPE));
        }
        $hash = md5($file_data);

        $extension = $contentType[1];
        if($extension == 'jpeg')
            $extension = 'jpg';
        if(strpos($newFileName, '.'.$extension) != strlen($newFileName) - strlen($extension) - 1){
            $newFileName .= '.'.$extension;
        }

        $isset = $this->where('hash', $hash)->first();

        if(!empty($isset)){
            return $isset;
        }elseif(is_file($destinationPath.'\\'.$newFileName)){
            $newFileName = $this->generate_filename($newFileName, $extension, $path = '');
        }

        file_put_contents($destinationPath.$newFileName, $file_data);

        $file = $this->createFile([
            'title' => $originalName,
            'path' => substr($this->files_path, 1).$newFileName,
            'type' => $contentType[0],
            'hash' => $hash,
            'author' => $author
        ]);

        return $file;
    }

    /**
     * Загрузка по пути
     *
     * @param $path
     * @return mixed
     */
    public function uploadFromPathImages($path){
        $destinationPath = public_path().$this->files_path;
        $originalName = basename($path);
        $newFileName = $originalName;

        $file_data = file_get_contents($path);

        $finfo = finfo_open(FILEINFO_MIME);
        $mimetype = explode(';', finfo_file($finfo, $path));
        finfo_close($finfo);
        $contentType = explode('/', $mimetype[0]);
        $hash = md5($file_data);

        $extension = $contentType[1];
        if($extension == 'jpeg')
            $extension = 'jpg';
        if(strpos($newFileName, '.'.$extension) != strlen($newFileName) - strlen($extension) - 1){
            $newFileName .= '.'.$extension;
        }

        $isset = $this->where('hash', $hash)->first();

        if(!empty($isset)){
            return $isset;
        }elseif(is_file($destinationPath.'\\'.$newFileName)){
            $newFileName = $this->generate_filename($newFileName, $extension, $path);
        }

        file_put_contents($destinationPath.$newFileName, $file_data);

        $file = $this->createFile([
            'title' => $originalName,
            'path' => substr($this->files_path, 1).$newFileName,
            'type' => $contentType[0],
            'hash' => $hash
        ]);

        return $file;
    }

    /**
     * Генерация уникального имени файла
     *
     * @param $name
     * @param $try_extension
     * @param string $path
     * @return mixed
     */
    public function generate_filename($name, $try_extension, $path = ''){
        if(empty($path))
            $path = public_path().$this->files_path;

        $originalName = str_replace(' ', '_', translit($name));

        if(is_file($path.DIRECTORY_SEPARATOR.$originalName)) {
            $paths = explode('.', $originalName);
            $extension = end($paths);

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
     * Создание основного изображения
     *
     * @return null
     */
    public function createImage(){
        if($this->type == 'image'){
            $filedata = $this->fileData();
            $images = new Image();
            $id = $images->insertGetId([
                'file_id' => $this->id,
                'path' => $this->path,
                'mime' => $filedata['mime'],
                'size' => 'full',
                'width' => $filedata[0],
                'height' => $filedata[1],
                'crop' => 'original',
            ]);

            return $id;
        }

        return null;
    }

    /**
     * Получение нужного размера изображения
     *
     * @param string $size Размер изображения ('full', 'product', 'product_list', 'blog', [100, 100])
     * @param string $crop (contain|cover|crop)/(уместить/заполнить/обрезать)
     *
     * @return mixed|string
     */
    public function url($size = 'full', $crop = 'cover'){
        if($size == 'full' || $this->type !== 'image'){
            $url = env('APP_URL').DIRECTORY_SEPARATOR.$this->path;
        }else{
            $thumbnail = $this->getOrCreateThumbnail($size, $crop);
            $url = $thumbnail->url;
        }
        $url = str_replace('\\', '/', $url);

        return $url;
    }

    /**
     * Получение нужного размера изображения
     *
     * @param string $size Размер изображения ('full', 'product', 'product_list', 'blog', [100, 100])
     * @param string $crop (contain|cover|crop)/(уместить/заполнить/обрезать)
     *
     * @return mixed|string
     */
    public function url_webp($size = 'full', $crop = 'cover'){
        if($this->type === 'image'){
            if(is_array($size)){
                $size = implode('_', $size);
            }

            if(!empty($this->data['sizes'])){
                if(isset($this->data['subtype']))
                    $original_mime = $this->data['subtype'];
                if(isset($this->data['sizes'][$size][$crop]['url']))
                    $thumbnail_url = $this->data['sizes'][$size][$crop]['url'];
                if(isset($this->data['sizes'][$size][$crop]['webp']))
                    $webp_url = $this->data['sizes'][$size][$crop]['webp'];
            }

            if(!isset($original_mime) || !isset($thumbnail_url) || !isset($webp_url)) {
                $thumbnail = $this->getOrCreateThumbnail($size, $crop);
                $original_mime = $thumbnail->mime;
                $thumbnail_url = $thumbnail->url;

                $data = [
                    'sizes' => [
                        $size => [
                            $crop => [
                                'url' => $thumbnail_url
                            ]
                        ]
                    ],
                    'subtype' => $original_mime
                ];

                if(!isset($webp_url)){
                    $webp = $this->images()->where('size', $size)->where('crop', $crop)->where('mime', 'image/webp')->first();
                    if(empty($webp)){
                        $webp = $thumbnail->createWebp();
                        if(!empty($webp)){
                            $webp_url = $webp->url;
                            $data['sizes'][$size][$crop]['webp'] = $webp_url;
                        }
                    }else{
                        $webp_url = $webp->url;
                        $data['sizes'][$size][$crop]['webp'] = $webp_url;
                    }
                }

                $this->updateData($data);
            }
        }

        return !empty($webp_url) ? $webp_url : null;
    }

    /**
     * Получение нужной миниатюры
     *
     * @param string $size
     * @param string $crop
     *
     * @return Model|mixed|null|object|static
     */
    public function getOrCreateThumbnail($size = 'full', $crop = 'cover'){
        if(is_array($size)){
            $size = implode('_', $size);
        }
        $thumbnail = $this->images()->where('size', $size)->where('crop', $crop)->first();
        if(empty($thumbnail)){
            $thumbnail = $this->createThumbnail($size, $crop);
        }

        return $thumbnail;
    }

    /**
     * Создание миниатюры изображения
     *
     * @param string $size
     * @param string $crop
     *
     * @return mixed
     */
    public function createThumbnail($size = 'full', $crop = 'cover'){
        $image = $this->images()->where('size', 'full')->first();
        if(empty($image)){
            $image = Image::find($this->createImage());
            $this->updateData();
        }

        return $image->createThumbnail($size, $crop);
    }

    /**
     * Вывод оптимизированного изображения
     *
     * @param string $size
     * @param array $attributes
     * @param bool $lazy
     * @param string $crop
     *
     * @return array|string
     * @throws \Throwable
     */
    public function webp($size = 'full', $attributes = [], $lazy = false, $crop = 'cover'){
        if($this->type === 'image'){
            if(is_array($size)){
                $size = implode('_', $size);
            }

            if(!empty($this->data['sizes'])){
                if(isset($this->data['subtype']))
                    $original_mime = $this->data['subtype'];
                if(isset($this->data['sizes'][$size][$crop]['url']))
                    $thumbnail_url = $this->data['sizes'][$size][$crop]['url'];
                if(isset($this->data['sizes'][$size][$crop]['webp']))
                    $webp_url = $this->data['sizes'][$size][$crop]['webp'];
            }

            if(!isset($original_mime) || !isset($thumbnail_url) || !isset($webp_url)) {
                $thumbnail = $this->getOrCreateThumbnail($size, $crop);
                $original_mime = $thumbnail->mime;
                $thumbnail_url = $thumbnail->url;

                $data = [
                    'sizes' => [
                        $size => [
                            $crop => [
                                'url' => $thumbnail_url
                            ]
                        ]
                    ],
                    'subtype' => $original_mime
                ];

                if(!isset($webp_url)){
                    $webp = $this->images()->where('size', $size)->where('crop', $crop)->where('mime', 'image/webp')->first();
                    if(empty($webp)){
                        $webp = $thumbnail->createWebp();
                        if(!empty($webp)){
                            $webp_url = $webp->url;
                            $data['sizes'][$size][$crop]['webp'] = $webp_url;
                        }
                    }else{
                        $webp_url = $webp->url;
                        $data['sizes'][$size][$crop]['webp'] = $webp_url;
                    }
                }

                $this->updateData($data);
            }

            if(empty($thumbnail_url)){
                $thumbnail_url = $this->link();
            }

            $view = view('public.layouts.webp')
                ->with('original', $thumbnail_url)
                ->with('original_mime', $original_mime)
                ->with('attributes', $attributes)
                ->with('lazy', $lazy);

            if(!empty($webp_url)){
                return $view->with('webp', $webp_url)->render();
            }elseif(!empty($thumbnail)){
                return $view->render();
            }
        }

        return view('public.layouts.webp')
            ->with('attributes', $attributes)
            ->render();
    }

    public function getNameAttribute(){
        return str_replace(['uploads\\', 'uploads/'], '', $this->path);
    }

    protected function dataMap(){
        return [
            'attributes' => [],
            'relations' => [
                'images' => [
                    'attributes' => [
                        'id' => '',
                        'file_id' => '',
                        'path' => '',
                        'mime' => '',
                        'size' => '',
                        'width' => '',
                        'height' => '',
                        'crop' => ''
                    ]
                ]
            ]
        ];
    }

    public function fieldsNames(){
        return [
            'attributes.title' => [
                'name' => 'Название файла'
            ],
            'attributes.path' => [
                'name' => 'Файл',
                'type' => 'file'
            ]
        ];
    }
}