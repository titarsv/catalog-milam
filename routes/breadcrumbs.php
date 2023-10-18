<?php

/**
 * Home
 */
Breadcrumbs::register('home', function($breadcrumbs) {
    $breadcrumbs->push(__('ТД «Пирана»'), rtrim(base_url('/'), '/'));
});

/**
 * User
 */
Breadcrumbs::register('user', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('app.Personal_information'), base_url('/user'));
});

Breadcrumbs::register('history', function($breadcrumbs) {
    $breadcrumbs->parent('user');
    $breadcrumbs->push(trans('app.Order_history'));
});

Breadcrumbs::register('wishlist', function($breadcrumbs) {
    $breadcrumbs->parent('user');
    $breadcrumbs->push(trans('app.Wish_list'));
});

Breadcrumbs::register('recommend', function($breadcrumbs) {
    $breadcrumbs->parent('user');
    $breadcrumbs->push(trans('app.Recommendations'));
});

Breadcrumbs::register('user-payment', function($breadcrumbs) {
    $breadcrumbs->parent('user');
    $breadcrumbs->push(trans('app.Payment_and_delivery'));
});

Breadcrumbs::register('user-contacts', function($breadcrumbs) {
    $breadcrumbs->parent('user');
    $breadcrumbs->push(trans('app.Contacts'));
});

/**
 * Categories
 */
Breadcrumbs::register('categories', function($breadcrumbs, $category) {
    $breadcrumbs->parent('home');
    $link = '';
    if(!empty($category[0])) {
        $categories = array_reverse($category[0]->get_parent_categories());
        foreach ($categories as $i => $category) {
            if (!empty($category) && $category->id > 1) {
                if (is_object($category[0])) {
                    $name = $category[0]->seo->name;
                    $link = $category[0]->link();
                } else {
                    $name = $category['name'];
                    $link = $category->link();
                }
            }
            if(isset($name))
                $breadcrumbs->push($name, $link);
        }
    }elseif(is_object($category)){
        $categories = array_reverse($category->get_parent_categories());
        foreach ($categories as $i => $category) {
            if (!empty($category) && $category->id > 1) {
                if (is_object($category)) {
                    $name = $category->seo->name;
                    $link = $category->link();
                } else {
                    $name = $category['name'];
                    $link = $category->link();
                }
            }
            if(isset($name))
                $breadcrumbs->push($name, $link);
        }
    }else{
        if (!empty($category) && $category->id > 1) {
            if (is_object($category[0])) {
                $name = $category[0]->seo->name;
                $link = $category[0]->link();
            } else {
                $name = $category['name'];
                $link = $category->link();
            }
        }
        if(isset($name))
            $breadcrumbs->push($name, $link);
    }
});

Breadcrumbs::register('filter', function($breadcrumbs, $category, $additional_crumb) {
    $breadcrumbs->parent('categories', $category);
    $breadcrumbs->push($additional_crumb->name);
});

/**
 * Articles
 */
Breadcrumbs::register('blog', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('app.blog'), base_url('/blog'));
});

Breadcrumbs::register('blog_item', function($breadcrumbs, $article) {
    $breadcrumbs->parent('blog');
    $breadcrumbs->push($article->name);
});

/**
 * News
 */
Breadcrumbs::register('news', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('app.News'), base_url('news'));
});

Breadcrumbs::register('news_item', function($breadcrumbs, $article) {
    $breadcrumbs->parent('news');
    $breadcrumbs->push($article->name);
});

/**
 * HTML Pages
 */
Breadcrumbs::register('page', function($breadcrumbs, $page) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push($page->name);
});

/**
 * Login and register
 */
Breadcrumbs::register('login', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('app.specialties'), base_url('/login'));
});

Breadcrumbs::register('registration', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('app.registration'), base_url('/registration'));
});

Breadcrumbs::register('forgotten', function($breadcrumbs) {
    $breadcrumbs->parent('login');
    $breadcrumbs->push(trans('app.Password_recovery'));
});

/**
 * Products
 */
Breadcrumbs::register('product', function($breadcrumbs, $product, $category) {
    if($category->count()) {
        $breadcrumbs->parent('categories', $category);
    }else{
        $breadcrumbs->parent('home');
    }
    $breadcrumbs->push($product->name);
});

/**
 * Search
 */
Breadcrumbs::register('search', function($breadcrumbs, $text) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Поиск').': '.$text, base_url('search'));
});

/**
 * Sales
 */
Breadcrumbs::register('sale', function($breadcrumbs, $sale) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push($sale->name);
});

Breadcrumbs::register('beauties', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('app.beauty_periods'), base_url('beauty'));
});

Breadcrumbs::register('beauty', function($breadcrumbs, $sale) {
    $breadcrumbs->parent('beauties');
    $breadcrumbs->push($sale->name);
});

Breadcrumbs::register('brands', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('app.Brands'));
});

Breadcrumbs::register('care', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('app.care'));
});

Breadcrumbs::register('cart', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('app.Basket'));
});

Breadcrumbs::register('checkout', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(__('Оформление заказа'));
});

Breadcrumbs::register('thanks', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('app.thank_you_for_the_order'));
});
