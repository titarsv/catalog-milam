<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class SentinelUsersTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();
        DB::table('roles')->truncate();
        DB::table('role_users')->truncate();

        $adminRole = Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'Admin',
            'slug' => 'admin',
            'permissions' => [
                'orders.list' => true,
                'orders.view' => true,
                'orders.update' => true,
                'users.list' => true,
                'users.view' => true,
                'users.create' => true,
                'users.update' => true,
                'reviews.list' => true,
                'reviews.view' => true,
                'reviews.update' => true,
                'reviews.delete' => true,
                'shopreviews.list' => true,
                'shopreviews.view' => true,
                'shopreviews.update' => true,
                'shopreviews.delete' => true,
                'pages.list' => true,
                'pages.view' => true,
                'pages.create' => true,
                'pages.update' => true,
                'pages.delete' => true,
                'products.list' => true,
                'products.view' => true,
                'products.create' => true,
                'products.update' => true,
                'products.delete' => true,
                'attributes.list' => true,
                'attributes.view' => true,
                'attributes.create' => true,
                'attributes.update' => true,
                'attributes.delete' => true,
                'categories.list' => true,
                'categories.view' => true,
                'categories.create' => true,
                'categories.update' => true,
                'categories.delete' => true,
                'sales.list' => true,
                'sales.view' => true,
                'sales.create' => true,
                'sales.update' => true,
                'sales.delete' => true,
                'export.list' => true,
                'export.view' => true,
                'export.create' => true,
                'export.update' => true,
                'export.delete' => true,
                'import.list' => true,
                'import.view' => true,
                'import.create' => true,
                'import.update' => true,
                'import.delete' => true,
                'news.list' => true,
                'news.view' => true,
                'news.create' => true,
                'news.update' => true,
                'news.delete' => true,
                'blog.list' => true,
                'blog.view' => true,
                'blog.create' => true,
                'blog.update' => true,
                'blog.delete' => true,
                'report.lists' => true,
                'report.view' => true,
                'settings.view' => true,
                'settings.update' => true,
                'cache.update' => true,
                'redis.update' => true,
                'modules.list' => true,
                'modules.view' => true,
                'modules.update' => true,
                'seo.settings' => true,
                'seo.list' => true,
                'seo.view' => true,
                'seo.create' => true,
                'seo.update' => true,
                'seo.delete' => true,
                'redirects.list' => true,
                'redirects.view' => true,
                'redirects.create' => true,
                'redirects.update' => true,
                'redirects.delete' => true,
                'media.list' => true,
                'media.create' => true,
                'actions.list' => true,
                'actions.view' => true,
                'coupons.list' => true,
                'coupons.view' => true,
                'coupons.create' => true,
                'coupons.update' => true,
                'coupons.delete' => true,
                'works.list' => true,
                'works.view' => true,
                'works.update' => true,
                'works.delete' => true,
                'photos.list' => true,
                'photos.view' => true,
                'photos.update' => true,
                'photos.delete' => true,
                'videos.list' => true,
                'videos.view' => true,
                'videos.update' => true,
                'videos.delete' => true,
            ]
        ]);

        $managerRole = Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'Manager',
            'slug' => 'manager',
            'permissions' => [
                'orders.list' => true,
                'orders.view' => true,
                'orders.update' => true,
                'users.list' => true,
                'users.view' => true,
                'users.create' => true,
                'users.update' => true,
                'reviews.list' => true,
                'reviews.view' => true,
                'reviews.update' => true,
                'reviews.delete' => true,
                'shopreviews.list' => true,
                'shopreviews.view' => true,
                'shopreviews.update' => true,
                'shopreviews.delete' => true,
                'media.list' => true,
                'media.create' => true,
            ]
        ]);

	    $moderatorRole = Sentinel::getRoleRepository()->createModel()->create([
		    'name' => 'Moderator',
		    'slug' => 'moderator',
		    'permissions' => [
			    'pages.list' => true,
			    'pages.view' => true,
			    'pages.create' => true,
			    'pages.update' => true,
			    'pages.delete' => true,
                'products.list' => true,
                'products.view' => true,
                'products.create' => true,
                'products.update' => true,
                'products.delete' => true,
                'attributes.list' => true,
                'attributes.view' => true,
                'attributes.create' => true,
                'attributes.update' => true,
                'attributes.delete' => true,
                'categories.list' => true,
                'categories.view' => true,
                'categories.create' => true,
                'categories.update' => true,
                'categories.delete' => true,
                'sales.list' => true,
                'sales.view' => true,
                'sales.create' => true,
                'sales.update' => true,
                'sales.delete' => true,
                'export.list' => true,
                'export.view' => true,
                'export.create' => true,
                'export.update' => true,
                'export.delete' => true,
                'import.list' => true,
                'import.view' => true,
                'import.create' => true,
                'import.update' => true,
                'import.delete' => true,
			    'news.list' => true,
			    'news.view' => true,
			    'news.create' => true,
			    'news.update' => true,
			    'news.delete' => true,
                'blog.list' => true,
                'blog.view' => true,
                'blog.create' => true,
                'blog.update' => true,
                'blog.delete' => true,
                'modules.list' => true,
                'modules.view' => true,
                'modules.update' => true,
                'redis.update' => true,
                'media.list' => true,
                'media.create' => true,
                'works.list' => true,
                'works.view' => true,
                'works.update' => true,
                'works.delete' => true,
                'photos.list' => true,
                'photos.view' => true,
                'photos.update' => true,
                'photos.delete' => true,
                'videos.list' => true,
                'videos.view' => true,
                'videos.update' => true,
                'videos.delete' => true,
		    ]
	    ]);

        $marketerRole = Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'Marketer',
            'slug' => 'marketer',
            'permissions' => [
                'orders.list' => true,
                'orders.view' => true,
                'report.lists' => true,
                'report.view' => true,
                'export.list' => true,
                'export.view' => true,
                'export.create' => true,
                'export.update' => true,
                'export.delete' => true,
                'seo.list' => true,
                'seo.view' => true,
                'seo.create' => true,
                'seo.update' => true,
                'seo.delete' => true,
                'redirects.list' => true,
                'redirects.view' => true,
                'redirects.create' => true,
                'redirects.update' => true,
                'redirects.delete' => true,
                'coupons.list' => true,
                'coupons.view' => true,
                'coupons.create' => true,
                'coupons.update' => true,
                'coupons.delete' => true,
            ]
        ]);

        $userRole = Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'User',
            'slug' => 'user',
            'permissions' => null
        ]);

        $unRegUserRole = Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'UnRegisterUser',
            'slug' => 'unregistered',
            'permissions' => null
        ]);

        $admin = [
            'email'    => 'admin@laravel.com',
            'password' => 'pass',
            'permissions' => null,
            'first_name' => 'John',
            'last_name'  => 'Doe'
        ];

        $manager = [
            'email'     => 'manager@laravel.com',
            'password'  => 'pass',
            'permissions' => null,
            'first_name' => 'Alex',
            'last_name'  => 'Linpus'
        ];

        $moderator = [
            'email'     => 'moderator@laravel.com',
            'password'  => 'pass',
            'permissions' => null,
            'first_name' => 'Ivan',
            'last_name'  => 'Vanko'
        ];

        $marketer = [
            'email'     => 'marketer@laravel.com',
            'password'  => 'pass',
            'permissions' => null,
            'first_name' => 'Marcos',
            'last_name'  => 'Cortez'
        ];

        $users = [
            [
                'email'    => 'user1@laravel.com',
                'password' => 'pass',
                'permissions' => null,
                'first_name' => 'Иван',
                'last_name'  => 'Иванов'
            ]
        ];

        $unRegusers = [
            [
                'email'    => 'unReguser1@laravel.com',
                'password' => 'null',
                'permissions' => null,
            ]
        ];

        $adminRole->users()->attach(Sentinel::registerAndActivate($admin));
        $managerRole->users()->attach(Sentinel::registerAndActivate($manager));
        $moderatorRole->users()->attach(Sentinel::registerAndActivate($moderator));
        $marketerRole->users()->attach(Sentinel::registerAndActivate($marketer));

        foreach($users as $user){
            $userRole->users()->attach(Sentinel::registerAndActivate($user));
        }
        foreach($unRegusers as $user){
            $unRegUserRole->users()->attach(Sentinel::registerAndActivate($user));
        }
    }
}