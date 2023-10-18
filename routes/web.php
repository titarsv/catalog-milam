<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * Admin routing
 */
Route::middleware(['admin'])->prefix('admin')->group(function(){
	Route::get('/', 'AdminController@dash');
	Route::post('/ajax/{method}', 'AjaxController@back');
    Route::get('/check_product/{id}', 'OneSController@checkProduct');

	Route::middleware(['role:settings.view'])->get('/settings', 'SettingsController@index');
	Route::middleware(['role:settings.update'])->post('/settings', 'SettingsController@update');
	Route::middleware(['role:settings.view'])->get('/delivery-and-payment', 'SettingsController@extraIndex');
	Route::middleware(['role:settings.update'])->post('/delivery-and-payment', 'SettingsController@extraUpdate');
	Route::middleware(['role:settings.update'])->get('/delivery-and-payment/newpost-update', 'SettingsController@newpostUpdate');
	Route::middleware(['role:settings.update'])->get('/telegram', 'SettingsController@adminTelegramAction');
	Route::middleware(['role:settings.update'])->post('/telegram', 'SettingsController@updateTelegramAction');

	Route::middleware(['role:cache.update'])->get('/cacheflush', function() {
		Cache::flush();
		return redirect()->back()->with('message-success', 'Кэш успешно очищен!');
	});

	Route::group(['prefix' => 'categories'], function(){
        Route::middleware(['role:categories.list'])->get('/', 'CategoriesController@adminIndexAction');
        Route::middleware(['role:categories.create'])->get('/create', 'CategoriesController@adminCreateAction');
        Route::middleware(['role:categories.create'])->post('/create', 'CategoriesController@adminStoreAction');
        Route::middleware(['role:categories.delete'])->get('/delete/{id}', 'CategoriesController@adminDestroyAction');
        Route::middleware(['role:categories.view'])->get('/edit/{id}', 'CategoriesController@adminEditAction');
        Route::middleware(['role:categories.update'])->post('/edit/{id}', 'CategoriesController@adminUpdateAction');
        Route::middleware(['role:categories.create'])->get('/prom', 'CategoriesController@promImport');
        Route::middleware(['role:categories.list'])->post('/children/{id}', 'CategoriesController@adminChildrenAction');
        Route::middleware(['role:categories.list'])->get('/livesearch', 'CategoriesController@adminLivesearchAction');
        Route::middleware(['role:products.update'])->post('/update_product_order', 'CategoriesController@adminUpdateProductOrderAction');
    });

    Route::group(['prefix' => 'sales'], function(){
        Route::middleware(['role:sales.list'])->get('/', 'SalesController@adminIndexAction');
        Route::middleware(['role:sales.create'])->get('/create', 'SalesController@adminCreateAction');
        Route::middleware(['role:sales.create'])->post('/create', 'SalesController@adminStoreAction');
        Route::middleware(['role:sales.delete'])->get('/delete/{id}', 'SalesController@adminDestroyAction');
        Route::middleware(['role:sales.view'])->get('/edit/{id}', 'SalesController@adminEditAction');
        Route::middleware(['role:sales.update'])->post('/edit/{id}', 'SalesController@adminUpdateAction');
        Route::middleware(['role:sales.create,sales.update'])->post('/add_product', 'AjaxController@adminAddSaleProduct');
        Route::middleware(['role:sales.create,sales.update'])->post('/remove_product', 'AjaxController@adminRemoveSaleProduct');
    });

	Route::group(['prefix' => 'attributes'], function(){
		Route::middleware(['role:attributes.list'])->match(['get', 'post'],'/', 'AttributesController@adminIndexAction');
		Route::middleware(['role:attributes.create'])->post('/create', 'AttributesController@adminStoreAction');
		Route::middleware(['role:attributes.delete'])->get('/delete/{id}', 'AttributesController@adminDestroyAction');
		Route::middleware(['role:attributes.view'])->get('/edit/{id}', 'AttributesController@adminEditAction');
		Route::middleware(['role:attributes.update'])->post('/edit/{id}', 'AttributesController@adminUpdateAction');
		Route::group(['prefix' => 'values'], function(){
			Route::middleware(['role:attributes.create,attributes.update'])->post('/create', 'AttributesController@adminStoreValueAction');
			Route::middleware(['role:attributes.delete'])->post('/delete/{id}', 'AttributesController@adminDestroyValueAction');
		});
	});

	Route::group(['prefix' => 'products'], function(){
		Route::middleware(['role:products.list'])->any('/', 'ProductsController@adminIndexAction');
		Route::middleware(['role:products.create'])->get('/create', 'ProductsController@adminCreateAction');
		Route::middleware(['role:products.create'])->post('/create', 'ProductsController@adminStoreAction');
		Route::middleware(['role:products.delete'])->get('/delete/{id}', 'ProductsController@destroy');
		Route::middleware(['role:products.view'])->get('/edit/{id}', 'ProductsController@adminEditAction');
		Route::middleware(['role:products.update'])->post('/edit/{id}', 'ProductsController@adminUpdateAction');
		Route::middleware(['role:products.update'])->post('/update_price/{id}', 'ProductsController@adminUpdatePriceAction');
		Route::middleware(['role:products.update'])->post('/update_stock/{id}', 'ProductsController@adminUpdateStockAction');
        Route::middleware(['role:products.create'])->get('/duplicate/{id}', 'ProductsController@adminDuplicateAction');
		Route::middleware(['role:products.create'])->get('/getattributevalues', 'ProductsController@getAttributes');
		Route::middleware(['role:products.create'])->post('/getattributevalues', 'ProductsController@getAttributeValues');
		Route::middleware(['role:products.update'])->post('/updatestok/{id}', 'ProductsController@adminUpdateVisibilityAction');
		Route::middleware(['role:products.update,products.delete'])->get('/get_filtered_ids', 'ProductsController@adminGetFilteredIdsAction');
		Route::middleware(['role:products.update'])->post('/mass_action/{id}', 'ProductsController@massAction');
		Route::middleware(['role:products.update'])->post('/get_data/{sku}', 'ProductsController@getProductData');
		Route::middleware(['role:products.update'])->post('/livesearch', 'AjaxController@adminLiveSearchAction');
        Route::middleware(['role:products.create'])->get('/prom', 'ProductsController@promImport');
        Route::middleware(['role:products.create'])->get('/prom/filters', 'ProductsController@promFiltersImport');
		Route::group(['prefix' => 'export'], function(){
			Route::middleware(['role:export.list'])->any('/', 'ProductsController@exportList');
			Route::middleware(['role:export.create'])->get('/create', 'ProductsController@createExport');
			Route::middleware(['role:export.create'])->post('/create', 'ProductsController@storeExport');
			Route::middleware(['role:export.delete'])->get('/delete/{id}', 'ProductsController@destroyExport');
			Route::middleware(['role:export.view'])->get('/edit/{id}', 'ProductsController@editExport');
			Route::middleware(['role:export.update'])->post('/edit/{id}', 'ProductsController@updateExport');
			Route::middleware(['role:export.list'])->get('/download/{id}', 'ProductsController@downloadExport');
			Route::middleware(['role:export.list'])->post('/refresh/{id}', 'ProductsController@refreshExport');
		});
		Route::group(['prefix' => 'import'], function(){
			Route::middleware(['role:import.list'])->get('/', 'ProductsController@import');
			Route::middleware(['role:import.create'])->post('/upload', 'ProductsController@uploadImportFile');
			Route::middleware(['role:import.view'])->get('/edit/{id}', 'ProductsController@editImport');
			Route::middleware(['role:import.update'])->post('/edit/{id}', 'ProductsController@updateImport');
			Route::middleware(['role:import.delete'])->get('/delete/{id}', 'ProductsController@destroyImport');
			Route::middleware(['role:import.update'])->post('/next_import_step/{id}', 'ProductsController@nextImportStep');
			Route::middleware(['role:import.update'])->post('/refresh_import/{id}', 'ProductsController@refreshImport');
		});
        Route::group(['prefix' => 'redis'], function(){
            Route::middleware(['role:redis.update'])->get('/', 'ProductsController@adminRedisSyncAction');
            Route::middleware(['role:redis.update'])->post('/progress', 'ProductsController@adminRedisSyncProgress');
        });
	});

    Route::group(['prefix' => 'blog'], function(){
        Route::middleware(['role:blog.list'])->get('/', 'BlogController@adminIndexAction');
        Route::middleware(['role:blog.create'])->post('/create', 'BlogController@adminStoreAction');
        Route::middleware(['role:blog.view'])->get('/edit/{id}', 'BlogController@adminEditAction');
        Route::middleware(['role:blog.update'])->post('/edit/{id}', 'BlogController@adminUpdateAction');
        Route::middleware(['role:blog.delete'])->get('/delete/{id}', 'BlogController@adminDestroyAction'); //softDelete
    });

//	Route::group(['prefix' => 'news'], function(){
//		Route::middleware(['role:news.list'])->get('/', 'NewsController@adminIndexAction');
//		Route::middleware(['role:news.create'])->post('/create', 'NewsController@adminStoreAction');
//		Route::middleware(['role:news.view'])->get('/edit/{id}', 'NewsController@adminEditAction');
//		Route::middleware(['role:news.update'])->post('/edit/{id}', 'NewsController@adminUpdateAction');
//		Route::middleware(['role:news.delete'])->get('/delete/{id}', 'NewsController@adminDestroyAction'); //softDelete
//        Route::middleware(['role:news.create,news.update'])->post('/add_product', 'AjaxController@adminAddNewsProduct');
//        Route::middleware(['role:news.create,news.update'])->post('/remove_product', 'AjaxController@adminRemoveNewsProduct');
//	});

	Route::group(['prefix' => 'users'], function(){
		Route::middleware(['role:users.list,users.list.users'])->get('/', 'UserController@index');
		Route::middleware(['role:users.create'])->get('/create', 'UserController@create');
		Route::middleware(['role:users.create'])->post('/create', 'UserController@store');
		Route::middleware(['role:users.view'])->get('/edit/{id}', 'UserController@edit');
		Route::middleware(['role:users.update'])->post('/edit/{id}', 'UserController@update');
		Route::middleware(['role:users.view'])->get('/stat/{id}', 'UserController@statistic');
		Route::middleware(['role:users.view'])->get('/reviews/{id}', 'UserController@reviews');
		Route::middleware(['role:users.view'])->get('/shopreviews/{id}', 'UserController@shopreviews');
		Route::middleware(['role:users.view'])->get('/wishlist/{id}', 'UserController@adminWishlist');
		Route::middleware(['role:users.delete'])->get('/delete/{id}', 'UserController@destroy'); //softDelete
		Route::middleware(['role:users.view'])->get('/export', 'UserController@export');
		Route::middleware(['role:users.create'])->post('/import', 'UserController@import');
	});

	Route::middleware(['role:users.list,users.list.managers'])->get('/managers', 'UserController@managers');
	Route::middleware(['role:users.list,users.list.moderators'])->get('/moderators', 'UserController@moderators');
	Route::middleware(['role:users.list,users.list.marketers'])->get('/marketers', 'UserController@marketers');

	Route::group(['prefix' => 'orders'], function(){
		Route::middleware(['role:orders.list'])->get('/', 'OrdersController@index');
		Route::middleware(['role:orders.create'])->get('/create', 'OrdersController@create');
		Route::middleware(['role:orders.create'])->post('/create', 'OrdersController@store');
		Route::middleware(['role:orders.view'])->get('/edit/{id}', 'OrdersController@edit');
		Route::middleware(['role:orders.update'])->post('/edit/{id}', 'OrdersController@update');
		Route::middleware(['role:orders.update'])->post('/edit/{id}/add_products', 'OrdersController@addProducts');
		Route::middleware(['role:orders.update'])->post('/edit/{id}/change_qty', 'OrdersController@changeQty');
		Route::middleware(['role:orders.update'])->post('/edit/{id}/remove_product', 'OrdersController@removeProduct');
		Route::middleware(['role:orders.update'])->post('/delivery', 'OrdersController@delivery');
		Route::middleware(['role:orders.delete'])->get('/delete/{id}', 'OrdersController@destroy'); //softDelete
		Route::middleware(['role:orders.list'])->get('/invoice/{id}', 'OrdersController@invoice');
		Route::middleware(['role:orders.update'])->post('/get_product_data', 'OrdersController@get_product_data');
		Route::middleware(['role:orders.update'])->post('/update_product_data', 'OrdersController@update_product_data');
		Route::middleware(['role:orders.update'])->post('/update_ttn', 'OrdersController@updateTtn');
		Route::middleware(['role:orders.update'])->post('/get_ttn_form', 'OrdersController@getTtnForm');
		Route::middleware(['role:orders.view'])->post('/send_email/{id}', 'OrdersController@sendEmail');
		Route::middleware(['role:orders.view'])->post('/send_sms/{id}', 'OrdersController@sendSms');
		Route::middleware(['role:orders.view'])->post('/get_sms_club_balance', 'OrdersController@getSmsClubBalance');
	});

	Route::group(['prefix' => 'pages'], function(){
		Route::middleware(['role:pages.list'])->get('/', 'PagesController@adminIndexAction');
		Route::middleware(['role:pages.create'])->post('/create', 'PagesController@adminStoreAction');
		Route::middleware(['role:pages.view'])->get('/edit/{id}', 'PagesController@adminEditAction');
		Route::middleware(['role:pages.update'])->post('/edit/{id}', 'PagesController@adminUpdateAction');
		Route::middleware(['role:pages.delete'])->get('/delete/{id}', 'PagesController@adminDestroyAction');
		Route::middleware(['role:pages.create,pages.update'])->get('/templates', 'PagesController@adminTemplatesAction');
		Route::middleware(['role:pages.create,pages.update'])->get('/template/{name}', 'PagesController@adminTemplateAction');
		Route::middleware(['role:pages.create,pages.update'])->post('/template/{name}', 'PagesController@adminUpdateTemplateAction');
	});

	Route::group(['prefix' => 'modules'], function(){
		Route::middleware(['role:modules.list'])->get('/', 'ModulesController@index');
		Route::middleware(['role:modules.view'])->get('/settings/{name}', function($name) {
			$controller = App::make('\App\Http\Controllers\Module' . $name . 'Controller');
			return $controller->callAction('index', []);
		});
		Route::middleware(['role:modules.update'])->post('/settings/{name}', function($name) {
			$controller = App::make('\App\Http\Controllers\Module' . $name . 'Controller');
			return $controller->callAction('save', []);
		});
        Route::group(['prefix' => 'latest'], function(){
            Route::middleware(['role:modules.update'])->post('/add_product', 'AjaxController@adminAddLatestProduct');
            Route::middleware(['role:modules.update'])->post('/remove_product', 'AjaxController@adminRemoveLatestProduct');
	    });
        Route::group(['prefix' => 'bestsellers'], function(){
            Route::middleware(['role:modules.update'])->post('/add_product', 'AjaxController@adminAddBestsellerProduct');
            Route::middleware(['role:modules.update'])->post('/remove_product', 'AjaxController@adminRemoveBestsellerProduct');
        });
	});

	Route::group(['prefix' => 'reviews'], function(){
		Route::middleware(['role:reviews.list'])->get('/', 'ReviewsController@index');
		Route::middleware(['role:reviews.view'])->get('/show/{id}', 'ReviewsController@show');
		Route::middleware(['role:reviews.update'])->post('/show/{id}', 'ReviewsController@update');
		Route::middleware(['role:reviews.delete'])->get('/delete/{id}', 'ReviewsController@destroy'); //softDelete
	});

	Route::group(['prefix' => 'shopreviews'], function(){
		Route::middleware(['role:shopreviews.list'])->get('/', 'ShopReviewsController@index');
		Route::middleware(['role:shopreviews.view'])->get('/show/{id}', 'ShopReviewsController@show');
		Route::middleware(['role:shopreviews.update'])->post('/show/{id}', 'ShopReviewsController@update');
		Route::middleware(['role:shopreviews.delete'])->get('/delete/{id}', 'ShopReviewsController@destroy'); //softDelete
	});

	Route::group(['prefix' => 'seo'], function(){
		Route::middleware(['role:seo.settings'])->get('/', 'SettingsController@seoSettings');
		Route::middleware(['role:seo.settings'])->post('/', 'SettingsController@seoUpdate');
		Route::middleware(['role:seo.list'])->get('/list', 'SeoController@index');
		Route::middleware(['role:seo.create'])->get('/create', 'SeoController@create');
		Route::middleware(['role:seo.create'])->post('/create', 'SeoController@store');
		Route::middleware(['role:seo.delete'])->get('/delete/{id}', 'SeoController@destroy');
		Route::middleware(['role:seo.view'])->get('/edit/{id}', 'SeoController@edit');
		Route::middleware(['role:seo.update'])->post('/edit/{id}', 'SeoController@update');
		Route::group(['prefix' => 'redirects'], function(){
			Route::middleware(['role:redirects.list'])->any('/', 'SeoController@redirects');
			Route::middleware(['role:redirects.create'])->get('/create', 'SeoController@createRedirect');
			Route::middleware(['role:redirects.create'])->post('/create', 'SeoController@storeRedirect');
			Route::middleware(['role:redirects.delete'])->get('/delete/{id}', 'SeoController@destroyRedirect');
			Route::middleware(['role:redirects.view'])->get('/edit/{id}', 'SeoController@editRedirect');
			Route::middleware(['role:redirects.update'])->post('/edit/{id}', 'SeoController@updateRedirect');
		});
	});

	Route::group(['prefix' => 'media'], function(){
		Route::middleware(['role:media.list'])->get('/', 'MediaController@index');
		Route::middleware(['role:media.list'])->get('/trash', 'MediaController@trash');
	});

	Route::middleware(['role:media.create'])->post('/async-upload', 'MediaController@upload');
	Route::middleware(['role:media.list'])->match(['get', 'post'], '/ajax', 'AjaxController@index');

    Route::group(['prefix' => 'actions'], function(){
        Route::middleware(['role:actions.list'])->get('/', 'ActionsController@index');
        Route::middleware(['role:actions.view'])->get('/show/{id}', 'ActionsController@show');
    });

    Route::group(['prefix' => 'coupons'], function(){
        Route::middleware(['role:coupons.list'])->get('/', 'CouponsController@adminIndexAction');
        Route::middleware(['role:coupons.create'])->get('/create', 'CouponsController@adminCreateAction');
        Route::middleware(['role:coupons.create'])->post('/generate_code', 'CouponsController@adminGenerateCodeAction');
        Route::middleware(['role:coupons.create'])->post('/create', 'CouponsController@adminStoreAction');
        Route::middleware(['role:coupons.view'])->get('/edit/{id}', 'CouponsController@adminEditAction');
        Route::middleware(['role:coupons.update'])->post('/edit/{id}', 'CouponsController@adminUpdateAction');
        Route::middleware(['role:coupons.delete'])->get('/delete/{id}', 'CouponsController@adminDestroyAction');
    });

    Route::group(['prefix' => '1c'], function(){
        Route::get('/products', 'OneSController@getProducts');
        Route::get('/product/{id}', 'OneSController@getProduct');
        Route::get('/categories', 'OneSController@getCategories');
        Route::get('/attributes', 'OneSController@getAttributes');
        Route::get('/images', 'OneSController@getImages');
    });

    Route::group(['prefix' => 'works'], function(){
        Route::middleware(['role:works.list'])->get('/', 'WorksController@adminIndexAction');
        Route::middleware(['role:works.update'])->post('/create', 'WorksController@adminStoreAction');
        Route::middleware(['role:works.view'])->get('/edit/{id}', 'WorksController@adminEditAction');
        Route::middleware(['role:works.update'])->post('/edit/{id}', 'WorksController@adminUpdateAction');
        Route::middleware(['role:works.delete'])->get('/delete/{id}', 'WorksController@adminDestroyAction'); //softDelete
    });

    Route::group(['prefix' => 'photos'], function(){
        Route::middleware(['role:photos.list'])->get('/', 'PhotosController@adminIndexAction');
        Route::middleware(['role:photos.update'])->post('/create', 'PhotosController@adminStoreAction');
        Route::middleware(['role:photos.view'])->get('/edit/{id}', 'PhotosController@adminEditAction');
        Route::middleware(['role:photos.update'])->post('/edit/{id}', 'PhotosController@adminUpdateAction');
        Route::middleware(['role:photos.delete'])->get('/delete/{id}', 'PhotosController@adminDestroyAction'); //softDelete
    });

    Route::group(['prefix' => 'videos'], function(){
        Route::middleware(['role:videos.list'])->get('/', 'VideosController@adminIndexAction');
        Route::middleware(['role:videos.update'])->post('/create', 'VideosController@adminStoreAction');
        Route::middleware(['role:videos.view'])->get('/edit/{id}', 'VideosController@adminEditAction');
        Route::middleware(['role:videos.update'])->post('/edit/{id}', 'VideosController@adminUpdateAction');
        Route::middleware(['role:videos.delete'])->get('/delete/{id}', 'VideosController@adminDestroyAction'); //softDelete
    });
});

/**
 * Frontend routing
 */
$prefixes = Config::get('app.locales');
if(in_array(Config::get('app.locale'), $prefixes)){
	unset($prefixes[array_search('ua', $prefixes)]);
}
$prefixes[] = '';

foreach($prefixes as $prefix){
	$params = [];
	if(!empty($prefix)){
		$params = ['prefix' => '{locale}', 'where' => ['locale' => 'ru|en'], 'middleware' => 'setlocale'];
	}
	Route::group($params, function(){
        Route::get('/', ['as'=>'home', 'uses'=>'PagesController@indexAction']);

		/**
		 * Service routing
         *
		 */
		Route::get('/api/{method?}', 'ApiController@index');
		Route::post('/ajax/{method}', 'AjaxController@front');
		Route::post('/products/filter', 'ProductsController@filterAction');

		/**
		 * Authorization routing
		 */
		Route::get('/login', 'LoginController@login');
		Route::post('/login', 'LoginController@authenticate');
		Route::get('/logout', 'LoginController@logout');

        //Social Login
        Route::get('/login/{provider?}',[
            'uses' => 'LoginController@getSocialAuth',
            'as'   => 'auth.getSocialAuth'
        ]);
        Route::get('/login/callback/{provider?}',[
            'uses' => 'LoginController@getSocialAuthCallback',
            'as'   => 'auth.getSocialAuthCallback'
        ]);

		Route::post('/sendmail', 'UserController@sendMail');
        Route::get('/sitemap', 'SitemapController@index');
        Route::get('/sitemap-products/{page?}', 'SitemapController@products');
	});
}

Route::middleware(['setlocale'])->match(['get', 'post'], '{url}', function($url){
    $redirect = \App\Models\Redirect::where('old_url', '/'.str_replace(' ', '+', urldecode($url)))->where('old_url', '!=', 'new_url')->first();
    if(!empty($redirect) && $redirect->old_url != $redirect->new_url){
        return redirect($redirect->new_url, 301);
    }
    $data = new stdClass();
    $locales = Config::get('app.locales');
    App::setLocale('ua');
    foreach($locales as $locale){
        if(substr($url, 0, 3) == $locale.'/'){
            $url = substr($url, 3);
            App::setLocale($locale);
        }
    }
    $seo = \App\Models\Seo::where('url', '/'.$url)->first();

    if(empty($seo)){
        $parts = explode('/', $url);
        if(count($parts) > 1){
            $params = '';
            foreach (array_reverse($parts) as $part){
                $params = '/'.$part.$params;
                $alias = preg_replace("/".str_replace('/', '\/', $params)."$/", '', '/'.$url);
                $seo = \App\Models\Seo::where('url', $alias)->first();
                if(!empty($seo)){
                    break;
                }
            }
        }

        if(empty($seo)){
            if(!empty($parts) && $category = \App\Models\Category::where('slug', explode('_', $parts[0])[0])->count()){
                $params = '';
                foreach (array_reverse($parts) as $part){
                    $params = '/'.$part.$params;
                }
                $data->seo->seotable = $category->id;
                $controller = App::make('\App\Http\Controllers\CategoriesController');
                $data->params = explode('/', trim($params, '/'));
                $data->request = Request();
                return $controller->callAction('showAction', ['data' => $data]);
            }

            if(isset($part) && in_array(substr($part, -4), ['.jpg', '.png', 'jpeg', 'webp'])){
//                $image = new \App\Models\Image();
                return redirect('/uploads/no_image.jpg', 301);
            }

            abort(404);
        }else{
            $data->params = explode('/', trim($params, '/'));
        }
    }

    $data->seo = $seo;
    $data->request = Request();

    $controller = App::make('\App\Http\Controllers\\' . $seo->seotable_type . 'Controller');
    return $controller->callAction($seo->action, ['data' => $data]);

})->where('url', '([A-Za-z0-9А-Яа-я\.\-_\/,;"\' ]+)');