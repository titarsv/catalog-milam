<?php

namespace App\Providers;

use App\Http\Controllers\PagesController;
use Illuminate\Database\Eloquent\Relations\Relation;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator as Pagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Xinax\LaravelGettext\Facades\LaravelGettext;
use App\Models\Wishlist;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use App\Models\ShopReview;
use App\Models\Order;
use App\Models\Paginator;
use App\Models\Image;
use App\Models\File;
use App\Models\Work;
use App\Models\Page;
use App\Models\Cart;
use App\Models\AttributeValue;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    private $user;
    private $roles_array = array();
    public function boot(Category $categories, Request $request)
    {
        if(app()->runningInConsole() && env('APP_ENV') !== 'testing'){
            return;
        }

        // Проверка БД
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            abort(503);
        }
        if(!Schema::hasTable('settings')){
            abort(503);
        }

	    Relation::morphMap([
		    'Pages' => \App\Models\Page::class,
		    'Seo' => \App\Models\Seo::class,
		    'Categories' => \App\Models\Category::class,
		    'Attributes' => \App\Models\Attribute::class,
		    'Values' => \App\Models\AttributeValue::class,
		    'Products' => \App\Models\Product::class,
	    ]);

        Pagination::useBootstrap();

        $lgl = [
            'ru' => 'ru_RU',
            'ua' => 'uk_UA',
            'en' => 'en_US',
        ];

        $locale = $request->segment(1) === 'admin' ? 'ru' : 'ua';

        $locales = Config::get('app.locales');
        foreach($locales as $loc){
            if($request->segment(1) == $loc){
                $locale = $loc;
                LaravelGettext::setLocale($lgl[$loc]);
                break;
            }
        }

        if($locale == 'ua'){
            LaravelGettext::setLocale($lgl[$locale]);
        }
        app()->setLocale($locale);

        $user = Sentinel::getUser();
        if(!is_null($user)){
            $user = User::find($user->id);
        }

        $this->user = $user;

        if(!is_null($user)){
            view()->composer([
                'admin.layouts.main',
                'admin.products.index',
                'admin.products.edit',
                'admin.categories.index',
                'admin.categories.edit',
                'admin.attributes.index',
                'admin.attributes.edit',
                'admin.attributes.value',
                'admin.sales.index',
                'admin.sales.edit',
                'admin.sales.products',
                'admin.products.import.index',
                'admin.products.import.edit',
                'admin.products.export.index',
                'admin.products.export.edit',
                'admin.media.index',
                'admin.modules.index',
                'admin.modules.bestsellers',
                'admin.modules.latest',
                'admin.modules.quiz',
                'admin.modules.slideshow',
                'admin.modules.brands',
                'admin.orders.index',
                'admin.orders.create_form',
                'admin.orders.edit_form',
                'admin.pages.index',
                'admin.pages.edit',
                'admin.news.index',
                'admin.news.edit',
                'admin.reviews.index',
                'admin.reviews.show',
                'admin.shopreviews.index',
                'admin.shopreviews.show',
                'admin.users.index',
                'admin.users.edit',
                'admin.extra_settings',
                'admin.seo_settings',
                'admin.seo.index',
                'admin.seo.edit',
                'admin.seo.redirects.index',
                'admin.seo.redirects.edit',
                'admin.actions.index',
                'admin.coupons.index',
                'admin.coupons.create',
                'admin.coupons.edit',
                'admin.works.index',
                'admin.works.edit',
                'admin.photos.index',
                'admin.photos.edit',
                'admin.videos.index',
                'admin.videos.edit',
                'admin.telegram',
            ], function ($view) use ($user){
                $orders = Order::where('status_id', 1)->count();
                $reviews = Review::where('new', 1)->count();

                $view->with([
                    'user' => $user,
                    'new_orders' => $orders,
                    'new_reviews' => $reviews
                ]);
            });

            view()->composer('public.order', function ($view){
                $view->with('user', User::find($this->user->id));
            });

            if ($this->user) {
                $roles = Sentinel::getRoles()->toArray();
                foreach ($roles as $role) {
                    $this->roles_array[] = $role['slug'];
                }
            }

            view()->composer(['public.layouts.header', 'index', 'public.product', 'public.layouts.product'], function ($view){
                $view->with('user_logged', $this->user);
            });

            view()->composer([
                'public.layouts.product',
                'public.product',
                'public.category'
            ], function ($view) {
                $view->with('user_id', $this->user->id)
                    ->with('user_logged', true)
                    ->with('user_roles', $this->roles_array);
            });

            view()->composer(['admin.media.assets'], function ($view){
                $view->with($this->mediaVariables());
            });

	        view()->composer(['admin.products.create', 'admin.seo_settings'], function ($view){
		        $view->with('languages', Config::get('app.locales_names'));
	        });

	        view()->composer(['admin.pages.fields.product'], function($view){
		        $view->with('products', Product::select(['products.*', 'localization.value'])->leftJoin('localization', function($leftJoin){
                    $leftJoin->on('products.id', '=', 'localization.localizable_id')
                            ->where('localization.localizable_type', '=', 'Products')
                            ->where('localization.language', '=', 'ru')
                            ->where('field', 'name');
                    })
                    ->orderBy('localization.value', 'asc')
                    ->get());
	        });
        } else {
            view()->composer([
                'public.layouts.header',
                'public.layouts.product',
                'public.product'
            ], function ($view) {
                $view->with('user_logged', false);
            });
        }

        view()->composer(['public.layouts.header'], function ($view) use ($categories){
            $cart = new Cart;
            $current_cart = $cart->current_cart();
            $view->with('cart', $current_cart);
        });

        view()->composer([
            'public/*',
            'users/*',
            'errors/*',
            'index',
            'login',
            'registration',
            'forgotten'
        ], function ($view) use ($user){
            $settings = new Setting;
            $view->with([
                'settings' => $settings->get_global(),
                'user' => $user ? $user : false
            ]);
        });

        view()->composer('admin.layouts.sidebar', function ($view){
            $view->with('new_reviews', Review::where('new', 1)->count());
            $view->with('new_shop_reviews', ShopReview::where('new', 1)->count());
            $view->with('new_orders', Order::where('status_id', 1)->count());
            $view->with('accepted_orders', Order::where('status_id', 3)->count());
            $view->with('completed_orders', Order::where('status_id', 4)->count());
            $view->with('canceled_orders', Order::where('status_id', 5)->count());
            $view->with('all_orders', Order::count());
        });

        view()->composer(['public.layouts.site_reviews', 'public.layouts.microdata.category'], function ($view){
            if(strpos(request()->segment(2),'page-') !== false){
                $page = (int) str_replace('page-', '', request()->segment(2));
                \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($page) {
                    return $page;
                });
            }

            $view->with('site_reviews', ShopReview::where('published', 1)
                ->orderBy('created_at', 'desc')
                ->with('user')
                ->paginate(10));
        });

        view()->composer([
            'public.layouts.pagination',
            'public.layouts.head',
            'public.category'
        ], function ($view) {
            $view->with('cp', new Paginator());
        });

        view()->composer([
            'public.layouts.microdata.local_business',
            'public.layouts.main',
            'public.layouts.header'
        ], function ($view) {
            $settings = new Setting;
            $settings = $settings->get_global();
            $view->with([
                'settings' => $settings,
                'logo' => empty($settings->ld_image) ? Image::find(1) : Image::find($settings->ld_image)
            ]);
        });

        $categories = collect(json_decode(Cache::remember('menu_categories_'.$locale, 1440, function() use ($locale) {
            $categories = Category::select(['id', 'parent_id'])->where('status', 1)->whereNull('parent_id')
                ->with(['children' => function($query) use ($locale){
                    $query->select('id', 'parent_id', 'file_id')->where('status', 1)->with(['localization' => function($query) use($locale){
                        $query->select(['field', 'language', 'value', 'localizable_type', 'localizable_id'])->where('language', $locale);
                    }])
                        ->with(['seo' => function($query){
                            $query->select(['seotable_id', 'url']);
                        }])
                        ->with('image')
                        ->withCount('children')
                        ->withCount(['products' => function($query){
                            $query->where('stock', '>', 0)->where('visible', 1);
                        }])
                        ->with(['children' => function($query) use ($locale){
                            $query->select('id', 'parent_id')->where('status', 1)->with(['localization' => function($query) use($locale){
                                $query->select(['field', 'language', 'value', 'localizable_type', 'localizable_id'])->where('language', $locale);
                            }])
                                ->with(['seo' => function($query){
                                    $query->select(['seotable_id', 'url']);
                                }])
                                ->withCount('children')
                                ->withCount(['products' => function($query){
                                    $query->where('stock', '>', 0)->where('visible', 1);
                                }]);
                        }]);
                }])
                ->with(['localization' => function($query) use($locale){
                    $query->select(['field', 'language', 'value', 'localizable_type', 'localizable_id'])->where('language', $locale);
                }])
                ->with(['seo' => function($query){
                    $query->select(['seotable_id', 'url']);
                }])
                ->withCount('children')
                ->withCount(['products' => function($query){
                    $query->where('stock', '>', 0)->where('visible', 1);
                }])
                ->get();

            $c = [];
            foreach($categories as $category){
                $c[] = $this->compactCategory($category);
            }

            return json_encode(collect($c), JSON_UNESCAPED_UNICODE);
        })));

        $categories = $this->setActive($categories);

        $pages = Page::where('status', 1)->where('sort_order', '>', 0)->orderBy('sort_order')->with(['seo', 'localization'])->take(6)->get();
	    view()->composer([
		    'public.layouts.header',
		    'public.layouts.footer',
		    'public.layouts.pages.home',
	    ], function ($view) use ($categories, $pages) {
		    $view->with('categories', $categories)
		        ->with('pages', $pages);
	    });

        view()->composer([
            'admin.pages.fields.*',
            'admin.layouts.seo',
            'admin.layouts.main',
            'admin.seo_settings',
            'admin.layouts.form.*',
        ], function ($view) {
            $view->with('locales_names', Config::get('app.locales_names'))
                ->with('main_lang', Config::get('app.locale'));
        });

        view()->composer([
            'public.layouts.header'
        ], function($view) use($locale, $user){
            $regions = [];
            $page = Page::find(3);
            $d = json_decode($page->localize(app()->getLocale(), 'body'));
            foreach($d[0]->data as $region){
                $regions[] = $region->region;
            }

            $view->with('locales', [
                'ua' => 'Укр',
                'ru' => 'Рус',
                'en' => 'En',
            ])
                ->with('main_locale', 'ua')
                ->with('current_locale', $locale)
                ->with('base_url', $locale == 'ua' ? \Request::getRequestUri() : substr(\Request::getRequestUri(), 3))
                ->with('regions', $regions);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

	public function convert_hr_to_bytes( $value ) {
		$value = strtolower( trim( $value ) );
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

	public function mediaVariables(){
		$files = new File();
		$u_bytes = $this->convert_hr_to_bytes(ini_get('upload_max_filesize'));
		$p_bytes = $this->convert_hr_to_bytes(ini_get('post_max_size'));

		$max_upload_size = min($u_bytes, $p_bytes);

		if(!$max_upload_size){
			$max_upload_size = 0;
		}

		$max_size = $files->sizeFormat($max_upload_size);
		$commonL10n = json_encode([
			'warnDelete' => 'Вы собираетесь навсегда удалить эти элементы с сайта./nЭто действие не может быть отменено./n\'Отмена\' для отмены, \'OK\' для удаления.',
			'dismiss' => 'Скрыть это уведомление',
			'collapseMenu' => 'Свернуть главное меню',
			'expandMenu' => 'Развернуть главное меню'
		]);
		$pluploadL10n = json_encode([
			'queue_limit_exceeded' => 'Вы поставили в очередь слишком много файлов.',
			'file_exceeds_size_limit' => 'Размер файла «%s» превышает максимальный для этого сайта.',
			'zero_byte_file' => 'Файл пуст. Пожалуйста, выберите другой.',
			'invalid_filetype' => 'Извините, этот тип файла недопустим по соображениям безопасности.',
			'not_an_image' => 'Файл не является изображением. Пожалуйста, выберите другой.',
			'image_memory_exceeded' => 'Превышен лимит памяти. Пожалуйста, выберите файл поменьше.',
			'image_dimensions_exceeded' => 'Размеры изображения превышают максимальные. Пожалуйста, выберите другое.',
			'default_error' => 'Во время загрузки произошла ошибка. Пожалуйста, повторите попытку позже.',
			'missing_upload_url' => 'Ошибка конфигурации. Пожалуйста, свяжитесь с администратором сервера.',
			'upload_limit_exceeded' => 'Вы можете загрузить только 1 файл.',
			'http_error' => 'Ошибка HTTP.',
			'upload_failed' => 'Загрузка не удалась.',
			'big_upload_failed' => 'Попробуйте загрузить этот файл через %1$sзагрузчик браузера%2$s.',
			'big_upload_queued' => 'Размер файла «%s» превышает максимальный для многофайлового загрузчика в сочетании с вашим браузером.',
			'io_error' => 'Ошибка ввода/вывода.',
			'security_error' => 'Ошибка безопасности.',
			'file_cancelled' => 'Загрузка отменена.',
			'upload_stopped' => 'Загрузка остановлена.',
			'dismiss' => 'Закрыть',
			'crunching' => 'Обработка…',
			'deleted' => 'перемещён в корзину.',
			'error_uploading' => 'Файл «%s» загрузить не удалось.'
		]);
		$quicktagsL10n = json_encode([
			'closeAllOpenTags' => 'Закрыть все открытые теги',
			'closeTags' => 'закрыть теги',
			'enterURL' => 'Введите адрес (URL)',
			'enterImageURL' => 'Введите адрес (URL) картинки',
			'enterImageDescription' => 'Введите описание изображения',
			'textdirection' => 'направление текста',
			'toggleTextdirection' => 'Переключить направление текста в редакторе',
			'dfw' => 'Полноэкранный режим',
			'strong' => 'Жирный',
			'strongClose' => 'Закрыть тег жирного шрифта',
			'em' => 'Курсив',
			'emClose' => 'Закрыть тег курсива',
			'link' => 'Вставить ссылку',
			'blockquote' => 'Цитата',
			'blockquoteClose' => 'Закрыть тег цитаты',
			'del' => 'Удаленный (перечёркнутый) текст',
			'delClose' => 'Закрыть тег удалённого текста',
			'ins' => 'Вставленный текст',
			'insClose' => 'Закрыть тег вставленного текста',
			'image' => 'Вставить изображение',
			'ul' => 'Маркированный список',
			'ulClose' => 'Закрыть тег маркированного списка',
			'ol' => 'Нумерованный список',
			'olClose' => 'Закрыть тег нумерованного списка',
			'li' => 'Элемент списка',
			'liClose' => 'Закрыть тег элемента списка',
			'code' => 'Код',
			'codeClose' => 'Закрыть тег кода',
			'more' => 'Вставить тег «Далее»',
		]);
		$thickboxL10n = json_encode([
			'next' => 'Далее →',
			'prev' => '← Назад',
			'image' => 'Изображение',
			'of' => 'из',
			'close' => 'Закрыть',
			'noiframes' => 'Эта функция требует поддержки плавающих фреймов. У вас отключены теги iframe, либо ваш браузер их не поддерживает.',
			'loadingAnimation' => '/images/larchik/loadingAnimation.gif'
		]);
		$_wpMediaViewsL10n = [
			'url' => 'URL',
			'addMedia' => 'Добавить медиафайл',
			'search' => 'Поиск',
			'select' => 'Выбрать',
			'cancel' => 'Отмена',
			'update' => 'Обновить',
			'replace' => 'Заменить',
			'remove' => 'Удалить',
			'back' => 'Назад',
			'selected' => 'Выбрано: %d',
			'dragInfo' => 'Отсортируйте медиафайлы путём перетаскивания.',
			'uploadFilesTitle' => 'Загрузить файлы',
			'uploadImagesTitle' => 'Загрузить изображения',
			'mediaLibraryTitle' => 'Библиотека файлов',
			'insertMediaTitle' => 'Добавить медиафайл',
			'createNewGallery' => 'Создать новую галерею',
			'createNewPlaylist' => 'Создать плей-лист',
			'createNewVideoPlaylist' => 'Создать плей-лист видео',
			'returnToLibrary' => '← Вернуться в библиотеку',
			'allMediaItems' => 'Все медиафайлы',
			'allDates' => 'Все даты',
			'noItemsFound' => 'Элементов не найдено.',
			'insertIntoPost' => 'Вставить в запись',
			'unattached' => 'Неприкреплённые',
			'mine' => 'Моё',
			'trash' => 'Корзина',
			'uploadedToThisPost' => 'Загруженные для этой записи',
			'warnDelete' => "Вы собираетесь навсегда удалить этот элемент с сайта.\nЭто действие не может быть отменено.\n'Отмена' для отмены, 'OK' для удаления.",
			'warnBulkDelete' => "Вы собираетесь навсегда удалить эти элементы с сайта.\nЭто действие не может быть отменено.\n'Отмена' для отмены, 'OK' для удаления.",
			'warnBulkTrash' => "Вы собираетесь переместить эти элементы в корзину.\n«Отмена» — оставить, «OK» — удалить.",
			'bulkSelect' => 'Множественный выбор',
			'cancelSelection' => 'Снять выделение',
			'trashSelected' => 'Удалить выбранные',
			'untrashSelected' => 'Восстановить выбранные',
			'deleteSelected' => 'Удалить выбранные',
			'deletePermanently' => 'Удалить навсегда',
			'apply' => 'Применить',
			'filterByDate' => 'Фильтр по дате',
			'filterByType' => 'Фильтр по типу',
			'searchMediaLabel' => 'Поиск медиафайлов',
			'searchMediaPlaceholder' => 'Поиск медиафайлов...',
			'noMedia' => 'Медиафайлов не найдено.',
			'attachmentDetails' => 'Параметры файла',
			'insertFromUrlTitle' => 'Вставить с сайта',
			'setFeaturedImageTitle' => 'Изображение записи',
			'setFeaturedImage' => 'Установить изображение записи',
			'createGalleryTitle' => 'Создать галерею',
			'editGalleryTitle' => 'Редактировать галерею',
			'cancelGalleryTitle' => '← Отменить создание галереи',
			'insertGallery' => 'Вставить галерею',
			'updateGallery' => 'Обновить галерею',
			'addToGallery' => 'Добавить в галерею',
			'addToGalleryTitle' => 'Добавить в галерею',
			'reverseOrder' => 'В обратном порядке',
			'imageDetailsTitle' => 'Параметры изображения',
			'imageReplaceTitle' => 'Заменить изображение',
			'imageDetailsCancel' => 'Отменить редактирование',
			'editImage' => 'Редактировать',
			'chooseImage' => 'Выбрать изображение',
			'selectAndCrop' => 'Выбрать и обрезать',
			'skipCropping' => 'Не обрезать',
			'cropImage' => 'Обрезать изображение',
			'cropYourImage' => 'Обрезать изображение',
			'cropping' => 'Обработка…',
			'suggestedDimensions' => 'Предлагаемый размер изображения: %1$s на %2$s пикселов.',
			'cropError' => 'При обрезке изображения произошла ошибка.',
			'audioDetailsTitle' => 'Параметры аудиофайла',
			'audioReplaceTitle' => 'Заменить аудиофайл',
			'audioAddSourceTitle' => 'Добавить источник аудио',
			'audioDetailsCancel' => 'Отменить редактирование',
			'videoDetailsTitle' => 'Параметры видеофайла',
			'videoReplaceTitle' => 'Заменить видеофайл',
			'videoAddSourceTitle' => 'Добавить источник видео',
			'videoDetailsCancel' => 'Отменить редактирование',
			'videoSelectPosterImageTitle' => 'Добавить постер',
			'videoAddTrackTitle' => 'Добавить субтитры',
			'playlistDragInfo' => 'Отсортируйте треки путём перетаскивания.',
			'createPlaylistTitle' => 'Создать плей-лист аудио',
			'editPlaylistTitle' => 'Изменить плей-лист',
			'cancelPlaylistTitle' => '← Отменить создание плей-листа',
			'insertPlaylist' => 'Вставить плей-лист аудио',
			'updatePlaylist' => 'Обновить плей-лист аудио',
			'addToPlaylist' => 'Добавить в плей-лист аудио',
			'addToPlaylistTitle' => 'Добавить в плей-лист',
			'videoPlaylistDragInfo' => 'Отсортируйте видеофайлы путём перетаскивания.',
			'createVideoPlaylistTitle' => 'Создать плей-лист видео',
			'editVideoPlaylistTitle' => 'Изменить плей-лист',
			'cancelVideoPlaylistTitle' => '← Отменить создание плей-листа',
			'insertVideoPlaylist' => 'Вставить плей-лист видео',
			'updateVideoPlaylist' => 'Обновить плей-лист видео',
			'addToVideoPlaylist' => 'Добавить в плей-лист видео',
			'addToVideoPlaylistTitle' => 'Добавить в плей-лист',
			'settings' => [
				'tabs' => [],
				'tabUrl' => '/admin/media-upload?chromeless=1',
				'mimeTypes' => [
					'image' => 'Изображения',
					'audio' => 'Аудио',
					'video' => 'Видео',
				],
				'captions' => '1',
				'nonce' => [
					'sendToEditor' => '091a1773c8',
				],
				'post' => [
					'id' => '0',
				],
				'defaultProps' => [
					'link' => 'none',
					'align' => '',
					'size' => '',
				],
				'attachmentCounts' => [
					'audio' => '1',
					'video' => '1',
				],
				'oEmbedProxyUrl' => '/wp-json/oembed/1.0/proxy',
				'embedExts' => [
					'mp3',
					'ogg',
					'flac',
					'm4a',
					'wav',
					'mp4',
					'm4v',
					'webm',
					'ogv',
					'flv',
				],
				'embedMimes' => [
					'mp3' => 'audio/mpeg',
					'ogg' => 'audio/ogg',
					'flac' => 'audio/flac',
					'm4a' => 'audio/mpeg',
					'wav' => 'audio/wav',
					'mp4' => 'video/mp4',
					'm4v' => 'video/mp4',
					'webm' => 'video/webm',
					'ogv' => 'video/ogg',
					'flv' => 'video/x-flv',
				],
				'contentWidth' => '',
				'months' => [
					[
						'year' => date('Y'),
						'month' => date('m'),
						'text' => trans('date.month_declensions.'.date('F')).date(' Y'),
					]
				],
				'mediaTrash' => '0',
			]
		];

		$months_data = $files->select(DB::raw('created_at, YEAR( created_at ) AS year, MONTH( created_at ) AS month'))->distinct()->orderBy('created_at', 'DESC')->get();
		if (!empty($months_data)) {
			$months_names = [
				1 => 'Январь',
				2 => 'Февраль',
				3 => 'Март',
				4 => 'Апрель',
				5 => 'Май',
				6 => 'Июнь',
				7 => 'Июль',
				8 => 'Август',
				9 => 'Сентябрь',
				10 => 'Октябрь',
				11 => 'Ноябрь',
				12 => 'Декабрь'
			];
			$months = [];
			foreach ($months_data as $month_year) {
				if (isset($months_names[$month_year->month])) {
					$months[$month_year->month.'.'.$month_year->year] = [
						'year' => $month_year->year,
						'month' => $month_year->month,
						'text' => sprintf(__('%1$s %2$d'), $months_names[$month_year->month], $month_year->year)
					];
				}
			}
			$_wpMediaViewsL10n['settings']['months'] = $months;
		}

		$wpUtilSettings = json_encode(['ajax' => ['url' => '/admin/ajax']]);

		$wpMediaModelsL10n = json_encode([
			'settings' => [
				'ajaxurl' => '\/admin\/ajax',
				'post' => ['id' => 0]
			]
		]);

		$uiAutocompleteL10n = json_encode([
			'noResults' => 'Результатов не найдено.',
			'oneResult' => 'Найден 1 результат. Для перемещения используйте клавиши вверх/вниз.',
			'manyResults' => 'Найдено результатов: %d. Для перемещения используйте клавиши вверх/вниз.',
			'itemSelected' => 'Объект выбран.'
		]);

		$wpLinkL10n = json_encode([
			'title' => 'Вставить/изменить ссылку',
			'update' => 'Обновить',
			'save' => 'Добавить ссылку',
			'noTitle' => '(без названия)',
			'noMatchesFound' => 'Результатов не найдено.',
			'linkSelected' => 'Ссылка выбрана.',
			'linkInserted' => 'Ссылка вставлена.'
		]);

		$wpColorPickerL10n = json_encode([
			'clear' => 'Сброс',
			'clearAriaLabel' => 'Очистить цвет',
			'defaultString' => 'По умолчанию',
			'defaultAriaLabel' => 'Выбрать цвет по умолчанию',
			'pick' => 'Выбрать цвет',
			'defaultLabel' => 'Значение цвета'
		]);

		$authcheckL10n = json_encode([
			'beforeunload' => 'Ваша сессия истекла. Вы можете войти снова с этой страницы или перейти на страницу входа.',
			'interval' => 180
		]);

		$attachMediaBoxL10n = json_encode([
			'error' => 'Произошла ошибка. Пожалуйста, обновите страницу и повторите попытку.'
		]);

		$imageEditL10n = json_encode([
			'error' => 'Не удалось загрузить изображение для просмотра. Пожалуйста, обновите страницу и повторите попытку.'
		]);

		$mceViewL10n = json_encode([
			'shortcodes' => [
				'wp_caption',
				'caption',
				'gallery',
				'playlist',
				'audio',
				'video',
				'embed',
				'acf',
				'toc',
				'no_toc',
				'sitemap',
				'sitemap_pages',
				'sitemap_categories',
				'sitemap_posts',
				'ratings',
				'contact-form-7',
				'contact-form',
				'wpseo_breadcrumb',
				'companies',
				'theme_of_the_week',
				'fav_company',
				'alert',
				'badge',
				'breadcrumb',
				' breadcrumb-item',
				'button',
				'button-group',
				'button-toolbar',
				' caret',
				'carousel',
				'carousel-item',
				'code',
				'collapse',
				'collapsibles',
				'column',
				'container',
				'container-fluid',
				'divider',
				'dropdown',
				'dropdown-header',
				'dropdown-item',
				'emphasis',
				'icon',
				'img',
				'embed-responsive',
				'jumbotron',
				'label',
				'lead',
				'list-group',
				'list-group-item',
				'list-group-item-heading',
				'list-group-item-text',
				'media',
				'media-body',
				'media-object',
				'modal',
				'modal-footer',
				'nav',
				'nav-item',
				'page-header',
				'panel',
				'popover',
				'progress',
				'progress-bar',
				'responsive',
				'row',
				'span',
				'tab',
				'table',
				'table-wrap',
				'tabs',
				'thumbnail',
				'tooltip',
				'well',
				'avatar',
				'avatar_upload',
			]
		]);

		$tinymce = json_encode([
			'New document' => 'Новый документ',
			'Formats' => 'Форматы',
			'Headings' => 'Заголовки',
			'Heading 1' => 'Заголовок 1',
			'Heading 2' => 'Заголовок 2',
			'Heading 3' => 'Заголовок 3',
			'Heading 4' => 'Заголовок 4',
			'Heading 5' => 'Заголовок 5',
			'Heading 6' => 'Заголовок 6',
			'Blocks' => 'Блоки',
			'Paragraph' => 'Абзац',
			'Blockquote' => 'Цитата',
			'Div' => 'Слой',
			'Preformatted' => 'Форматированный',
			'Address' => 'Адрес',
			'Inline' => 'Строки',
			'Underline' => 'Подчёркнутый',
			'Strikethrough' => 'Перечёркнутый',
			'Subscript' => 'Нижний индекс',
			'Superscript' => 'Верхний индекс',
			'Clear formatting' => 'Очистить форматирование',
			'Bold' => 'Жирный',
			'Italic' => 'Курсив',
			'Code' => 'Код',
			'Source code' => 'Исходный код',
			'Font Family' => 'Семейство шрифтов',
			'Font Sizes' => 'Размеры шрифтов',
			'Align center' => 'По центру',
			'Align right' => 'По правому краю',
			'Align left' => 'По левому краю',
			'Justify' => 'По ширине',
			'Increase indent' => 'Увеличить отступ',
			'Decrease indent' => 'Уменьшить отступ',
			'Cut' => 'Вырезать',
			'Copy' => 'Копировать',
			'Paste' => 'Вставить',
			'Select all' => 'Выделить всё',
			'Undo' => 'Отменить',
			'Redo' => 'Повторить',
			'Ok' => 'OK',
			'Cancel' => 'Отмена',
			'Close' => 'Закрыть',
			'Visual aids' => 'Визуальные подсказки',
			'Bullet list' => 'Маркированный список',
			'Numbered list' => 'Нумерованный список',
			'Square' => 'Квадрат',
			'Default' => 'По умолчанию',
			'Circle' => 'Кружок',
			'Disc' => 'Точка',
			'Lower Greek' => 'Строчные греческие буквы',
			'Lower Alpha' => 'Строчные латинские буквы',
			'Upper Alpha' => 'Заглавные латинские буквы',
			'Upper Roman' => 'Заглавные римские буквы',
			'Lower Roman' => 'Строчные римские буквы',
			'Name' => 'Имя',
			'Anchor' => 'Якорь',
			'Anchors' => 'Якоря',
			'Id should start with a letter, followed only by letters, numbers, dashes, dots, colons or underscores.' => 'Id должен начинаться с буквы, и содержать только буквы, цифры, тире, точки, запятые или знак подчеркивания.',
			'Document properties' => 'Свойства документа',
			'Robots' => 'Роботы',
			'Title' => 'Заголовок',
			'Keywords' => 'Ключевые слова',
			'Encoding' => 'Кодировка',
			'Description' => 'Описание',
			'Author' => 'Автор',
			'Image' => 'Изображение',
			'Insert/edit image' => 'Вставить/изменить картинку',
			'General' => 'Общие',
			'Advanced' => 'Дополнительно',
			'Source' => 'Источник',
			'Border' => 'Рамка',
			'Constrain proportions' => 'Сохранять пропорции',
			'Vertical space' => 'Отступ (V)',
			'Image description' => 'Описание',
			'Style' => 'Стиль',
			'Dimensions' => 'Размеры',
			'Insert image' => 'Вставить изображение',
			'Date/time' => 'Дата/время',
			'Insert date/time' => 'Вставить дату/время',
			'Table of Contents' => 'Оглавление',
			'Insert/Edit code sample' => 'Вставить/изменить фрагмент кода',
			'Language' => 'Язык',
			'Media' => 'Медиафайлы',
			'Insert/edit media' => 'Вставить/Изменить медиа',
			'Poster' => 'Постер',
			'Alternative source' => 'Альтернативный источник',
			'Paste your embed code below:' => 'Вставьте код объекта:',
			'Insert video' => 'Вставить видеофайл',
			'Embed' => 'Объект',
			'Special character' => 'Произвольный символ',
			'Right to left' => 'Справа налево',
			'Left to right' => 'Слева направо',
			'Emoticons' => 'Иконки Emoticons',
			'Nonbreaking space' => 'Неразрывный пробел',
			'Page break' => 'Разрыв страницы',
			'Paste as text' => 'Вставить как текст',
			'Preview' => 'Просмотреть',
			'Print' => 'Печать',
			'Save' => 'Сохранить',
			'Fullscreen' => 'На весь экран',
			'Horizontal line' => 'Горизонтальная линия',
			'Horizontal space' => 'Отступ (H)',
			'Restore last draft' => 'Восстановить последний черновик',
			'Insert/edit link' => 'Вставить/изменить ссылку',
			'Remove link' => 'Удалить ссылку',
			'Link' => 'Ссылка',
			'Insert link' => 'Вставить ссылку',
			'Target' => 'Цель',
			'New window' => 'Новое окно',
			'Text to display' => 'Показываемый текст',
			'Url' => 'URL',
			'The URL you entered seems to be an email address. Do you want to add the required mailto: prefix?' => 'Введённый вами адрес похож на e-mail, добавить mailto: в начало?',
			'The URL you entered seems to be an external link. Do you want to add the required http:// prefix?' => 'Введённый вами адрес похож на внешнюю ссылку, добавить http:// в начало?',
			'Color' => 'Цвет',
			'Custom color' => 'Произвольный цвет',
			'Custom...' => 'Произвольный...',
			'No color' => 'Без цвета',
			'Could not find the specified string.' => 'Не удалось найти указанную строку.',
			'Replace' => 'Заменить',
			'Next' => 'Далее',
			'Prev' => 'Назад',
			'Whole words' => 'Целые слова',
			'Find and replace' => 'Найти и заменить',
			'Replace with' => 'Замена',
			'Find' => 'Найти',
			'Replace all' => 'Заменить все',
			'Match case' => 'С учётом регистра',
			'Spellcheck' => 'Проверка орфографии',
			'Finish' => 'Завершить',
			'Ignore all' => 'Пропустить все',
			'Ignore' => 'Пропустить',
			'Add to Dictionary' => 'Добавить в словарь',
			'Insert table' => 'Вставить таблицу',
			'Delete table' => 'Удалить таблицу',
			'Table properties' => 'Свойства таблицы',
			'Row properties' => 'Свойства строки таблицы',
			'Cell properties' => 'Свойства ячейки таблицы',
			'Border color' => 'Цвет границы',
			'Row' => 'Строка',
			'Rows' => 'Строки',
			'Column' => 'Столбец',
			'Cols' => 'Столбцы',
			'Cell' => 'Ячейка',
			'Header cell' => 'Ячейка заголовка',
			'Header' => 'Заголовок',
			'Body' => 'Основная часть',
			'Footer' => 'Нижняя часть',
			'Insert row before' => 'Вставить строку до',
			'Insert row after' => 'Вставить строку после',
			'Insert column before' => 'Вставить столбец до',
			'Insert column after' => 'Вставить столбец после',
			'Paste row before' => 'Вставить строку таблицы до',
			'Paste row after' => 'Вставить строку таблицы после',
			'Delete row' => 'Удалить строку',
			'Delete column' => 'Удалить столбец',
			'Cut row' => 'Вырезать строку таблицы',
			'Copy row' => 'Копировать строку таблицы',
			'Merge cells' => 'Объединить ячейки таблицы',
			'Split cell' => 'Разделить ячейку таблицы',
			'Height' => 'Высота',
			'Width' => 'Ширина',
			'Caption' => 'Подпись',
			'Alignment' => 'Выравнивание',
			'H Align' => 'Выравнивание по горизонтали',
			'Left' => 'Слева',
			'Center' => 'По центру',
			'Right' => 'Справа',
			'None' => 'Нет',
			'V Align' => 'Выравнивание по вертикали',
			'Top' => 'Сверху',
			'Middle' => 'Посередине',
			'Bottom' => 'Снизу',
			'Row group' => 'Группа строк',
			'Column group' => 'Группа столбцов',
			'Row type' => 'Тип строки',
			'Cell type' => 'Тип ячейки',
			'Cell padding' => 'Отступы в ячейках',
			'Cell spacing' => 'Отступы между ячейками',
			'Scope' => 'Атрибут scope',
			'Insert template' => 'Вставить шаблон',
			'Templates' => 'Шаблоны',
			'Background color' => 'Цвет фона',
			'Text color' => 'Цвет текста',
			'Show blocks' => 'Показать блоки',
			'Show invisible characters' => 'Показать невидимые символы',
			'Words: {0}' => 'Слов: {0}',
			'Paste is now in plain text mode. Contents will now be pasted as plain text until you toggle this option off.' => 'Выбран режим вставки простого текста. Содержимое будет вставляться в виде простого текста, пока вы не отключите этот режим. Если вы хотите вставить текст с форматированием из Microsoft Word, попробуйте отключить этот режим. Редактор автоматически очистит текст, скопированный из Word.',
			'Rich Text Area. Press ALT-F9 for menu. Press ALT-F10 for toolbar. Press ALT-0 for help' => 'Область редактирования. Нажмите Alt-Shift-H, чтобы получить больше информации.',
			'Rich Text Area. Press Control-Option-H for help.' => 'Область редактирования. Нажмите Control-Option-H, чтобы получить больше информации.',
			'You have unsaved changes are you sure you want to navigate away?' => 'Сделанные вами изменения будут отменены, если вы уйдёте с этой страницы.',
			'Your browser doesn\'t support direct access to the clipboard. Please use the Ctrl+X/C/V keyboard shortcuts instead.' => 'Ваш браузер не поддерживает прямой доступ к буферу обмена. Используйте горячие клавиши или меню «Правка» вашего браузера.',
			'Insert' => 'Вставить',
			'File' => 'Файл',
			'Edit' => 'Изменить',
			'Tools' => 'Инструменты',
			'View' => 'Просмотр',
			'Table' => 'Таблица',
			'Format' => 'Формат',
			'Toolbar Toggle' => 'Показать/скрыть панель инструментов',
			'Insert Read More tag' => 'Вставить тег «Далее»',
			'Insert Page Break tag' => 'Вставить тег разрыва страницы',
			'Read more...' => 'Тег «Далее»',
			'Distraction-free writing mode' => 'Полноэкранный режим',
			'No alignment' => 'Без выравнивания',
			'Remove' => 'Удалить',
			'Edit ' => 'Изменить',
			'Paste URL or type to search' => 'Введите URL или слово для поиска',
			'Apply' => 'Применить',
			'Link options' => 'Настройки ссылки',
			'Visual' => 'Визуально',
			'Text' => 'Текст',
			'Keyboard Shortcuts' => 'Горячие клавиши',
			'Default shortcuts,' => 'Стандартные комбинации,',
			'Additional shortcuts,' => 'Дополнительные комбинации,',
			'Focus shortcuts:' => 'Клавиши фокуса:',
			'Inline toolbar (when an image, link or preview is selected)' => 'Всплывающая панель (при выборе изображения, ссылки или объекта)',
			'Editor menu (when enabled)' => 'Меню редактора (если включено)',
			'Editor toolbar' => 'Панель редактора',
			'Elements path' => 'Пути элементов',
			'Ctrl + Alt + letter:' => 'Ctrl + Alt + буква:',
			'Shift + Alt + letter:' => 'Shift + Alt + буква:',
			'Cmd + letter:' => 'Cmd + буква:',
			'Ctrl + letter:' => 'Ctrl + буква:',
			'Letter' => 'Буква',
			'Action' => 'Действие',
			'Warning: the link has been inserted but may have errors. Please test it.' => 'Внимание: ссылка добавлена, но может содержать ошибки. Пожалуйста, проверьте её.',
			'To move focus to other buttons use Tab or the arrow keys. To return focus to the editor press Escape or use one of the buttons.' => 'Чтобы переместить фокус на другие кнопки, используйте Tab или клавиши со стрелками. Чтобы вернуть фокус в редактор, нажмите Escape или одну из кнопок.',
			'When starting a new paragraph with one of these formatting shortcuts followed by a space, the formatting will be applied automatically. Press Backspace or Escape to undo.' => 'Если новый абзац начинается с одной из этих комбинаций и пробела, произойдёт автоматическое форматирование. Нажмите Backspace или Escape, чтобы отменить.',
			'The following formatting shortcuts are replaced when pressing Enter. Press Escape or the Undo button to undo.' => 'Следующие комбинации заменяются при нажатии Enter. Нажмите Escape или кнопку отмены, чтобы отменить.',
			'The next group of formatting shortcuts are applied as you type or when you insert them around plain text in the same paragraph. Press Escape or the Undo button to undo.' => 'Следующая группа комбинаций заменяется по мере набора или при обрамлении простого текста в том же параграфе. Нажмите Escape или кнопку отмены, чтобы отменить.'
		]);

		return [
			'max_size' => $max_size,
			'commonL10n' => $commonL10n,
			'wpUtilSettings' => $wpUtilSettings,
			'wpMediaModelsL10n' => $wpMediaModelsL10n,
			'pluploadL10n' => $pluploadL10n,
			'thickboxL10n' => $thickboxL10n,
			'quicktagsL10n' => $quicktagsL10n,
			'uiAutocompleteL10n' => $uiAutocompleteL10n,
			'wpLinkL10n' => $wpLinkL10n,
			'wpColorPickerL10n' => $wpColorPickerL10n,
			'authcheckL10n' => $authcheckL10n,
			'attachMediaBoxL10n' => $attachMediaBoxL10n,
			'imageEditL10n' => $imageEditL10n,
			'mceViewL10n' => $mceViewL10n,
			'_wpMediaViewsL10n' => json_encode($_wpMediaViewsL10n),
			'tinymce' => $tinymce
		];
	}

	private function compactCategory($category){
        $data =  [
            'id' => $category->id,
            'name' => $category->name,
            'link' => $category->link(),
            'image' => !empty($category->image) ? $category->image->webp([360, 360], ['alt' => $category->name]) : '',
            'count' => $category->products_count
        ];

        if($category->children_count){
            $children = [];
            foreach($category->children as $child){
                $children[] = $this->compactCategory($child);
            }

            $data['children'] = $children;
        }

        return collect($data);
    }

    private function setActive($categories){
        foreach($categories as $i => $category){
            if(is_object($category)){
                $categories[$i]->active = request()->url() == $category->link;
                if(!empty($category->children)){
                    $categories[$i]->children = $this->setActive($category->children);
                    if(!$categories[$i]->active && !empty($categories[$i]->children)){
                        foreach($categories[$i]->children as $children){
                            if(!empty($children->active)){
                                $categories[$i]->active = true;
                                break;
                            }
                        }
                    }else{
                        break;
                    }
                }
            }else{
                $categories[$i]['active'] = request()->url() == $category->link;
                if(!empty($category['children'])){
                    $categories[$i]['children'] = $this->setActive($category['children']);
                    if(!$categories[$i]['active'] && !empty($categories[$i]['children'])){
                        foreach($categories[$i]['children'] as $children){
                            if(!empty($children['active'])){
                                $categories[$i]['active'] = true;
                                break;
                            }
                        }
                    }else{
                        break;
                    }
                }
            }
        }

        return $categories;
    }
}
