<?php

namespace App\Providers;

use App\Shortcodes\BoldShortcode;
use Illuminate\Support\ServiceProvider;
use Shortcode;

class ShortcodesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
//        Shortcode::register('b', BoldShortcode::class);
//        Shortcode::register('i', 'App\Shortcodes\ItalicShortcode@custom');
        Shortcode::register('a', 'App\Shortcodes\LinkShortcode@custom');
//        Shortcode::register('cases', 'App\Shortcodes\CasesShortcode@render');
//        Shortcode::register('portfolio', 'App\Shortcodes\PortfolioShortcode@render');
        Shortcode::register('username', 'App\Shortcodes\UsernameShortcode@render');
        Shortcode::register('base_url', 'App\Shortcodes\BaseUrlShortcode@render');
        Shortcode::register('breadcrumbs', 'App\Shortcodes\BreadcrumbsShortcode@render');
        Shortcode::register('contact_form', 'App\Shortcodes\ContactFormShortcode@render');
    }
}
