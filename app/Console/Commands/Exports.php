<?php

namespace App\Console\Commands;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Console\Command;
use App\Models\ProductsExport;

class Exports extends Command
{
    /**
     * Название команды
     *
     * @var string
     */
    protected $name = 'generate_exports';

    /**
     * Описание команды
     *
     * @var string
     */
    protected $description = 'Generation export files';

    protected $exports;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->exports = new ProductsExport();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Relation::morphMap([
            'Pages' => \App\Models\Page::class,
            'News' => \App\Models\News::class,
            'Seo' => \App\Models\Seo::class,
            'Blog' => \App\Models\Blog::class,
            'Categories' => \App\Models\Category::class,
            'Attributes' => \App\Models\Attribute::class,
            'Values' => \App\Models\AttributeValue::class,
            'Products' => \App\Models\Product::class,
            'Sales' => \App\Models\Sale::class,
        ]);

		foreach($this->exports->whereNotNull('schedule')->get() as $export){
			$export->runScheduleEvent();
		}
    }
}
