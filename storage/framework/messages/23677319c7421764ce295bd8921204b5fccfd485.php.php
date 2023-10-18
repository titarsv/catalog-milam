<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Redis;
use App\Models\Product;
use App\Models\Category;
use App\Models\Seo;
use App\Models\Redirect;
use Carbon\Carbon;

class XMLSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'xmlsitemap'; //название нашей команды

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generation Sitemap.xml';//описание нашей команды

    protected $xmlbase = null;
    protected $xmlbaseua = null;
    protected $xmlbaseen = null;
    protected $redirects = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
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

        $site_url = env('APP_URL');//уберите лишние пробелы

        foreach(Redirect::all() as $redirect){
            $this->redirects[$site_url.$redirect->old_url] = $site_url.$redirect->new_url;
        }

        $base = '<?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
            </urlset>';
        $this->xmlbase = new \SimpleXMLElement($base);
        $this->xmlbaseua = new \SimpleXMLElement($base);
        $this->xmlbaseen = new \SimpleXMLElement($base);

        $this->addLink($site_url, null, date("c"), "always", "1");
        $this->addLink($site_url.'/ua', null, date("c"), "always", "1", 'ua');
        $this->addLink($site_url.'/en', null, date("c"), "always", "1", 'en');

        // Товары
        foreach (Product::where('visible', 1)->with(['seo', 'image'])->get() as $result) {
            $date = !empty($result->updated_at) ? $result->updated_at->format( "Y-m-d\TH:i:sP") : (!empty($result->created_at) ? $result->created_at->format("Y-m-d\TH:i:sP") : Carbon::now()->format("Y-m-d\TH:i:sP"));
            $this->addLink($site_url.$result->seo->url, !empty($result->image) ? $result->image->url() : null, $date, "weekly", "0.4");
            $this->addLink($site_url.'/ua'.$result->seo->url, !empty($result->image) ? $result->image->url() : null, $date, "weekly", "0.4", 'ua');
            $this->addLink($site_url.'/en'.$result->seo->url, !empty($result->image) ? $result->image->url() : null, $date, "weekly", "0.4", 'en');
        }

        // Категории
        foreach(Category::where('status', 1)->with('seo', 'image', 'attributes.values')->get() as $result){
            $params = ['and', 'count', 'product_visible', 'category_'.$result->id];
            Redis::command('bitop', $params);
            $count = Redis::bitcount('count');
            if(!empty($count)){
                $this->addLink($site_url.$result->seo->url, !empty($result->image) ? $result->image->url() : null, $result->created_at->format("Y-m-d\TH:i:sP"), "daily", "0.8");
                $this->addLink($site_url.'/ua'.$result->seo->url, !empty($result->image) ? $result->image->url() : null, $result->created_at->format("Y-m-d\TH:i:sP"), "daily", "0.8", 'ua');
                $this->addLink($site_url.'/en'.$result->seo->url, !empty($result->image) ? $result->image->url() : null, $result->created_at->format("Y-m-d\TH:i:sP"), "daily", "0.8", 'en');

                foreach($result->attributes()->where('attributes.id', '!=', 3)->where('attributes.id', '!=', 4)->get() as $attribute){
                    $attr_slug = $attribute->slug;
                    foreach($attribute->values as $value){
                        $value_slug = $value->value;

                        $params = ['and', 'count', 'product_visible', 'category_'.$result->id, 'attribute_'.$value->id];
                        Redis::command('bitop', $params);
                        $count = Redis::bitcount('count');
                        if(!empty($count)){
                            $this->addLink($site_url.$result->seo->url.'/'.$attr_slug.'-'.$value_slug, !empty($result->image) ? $result->image->url() : null, $result->created_at->format("Y-m-d\TH:i:sP"), "daily", "0.8");
                            $this->addLink($site_url.'/ua'.$result->seo->url.'/'.$attr_slug.'-'.$value_slug, !empty($result->image) ? $result->image->url() : null, $result->created_at->format("Y-m-d\TH:i:sP"), "daily", "0.8", 'ua');
                            $this->addLink($site_url.'/en'.$result->seo->url.'/'.$attr_slug.'-'.$value_slug, !empty($result->image) ? $result->image->url() : null, $result->created_at->format("Y-m-d\TH:i:sP"), "daily", "0.8", 'en');
                        }
                    }
                }
            }
        }

        $links = Seo::whereNotIn('seotable_type', ['Products', 'Categories'])
            ->where(function ($query) {
                $query->whereNull('robots')
                    ->orWhere('robots', 'not like', '%noindex%');

            })
            ->get();
        foreach($links as $link){
            if($link->url != '/'){
                $this->addLink($site_url.$link->url, null, $link->updated_at->format("Y-m-d\TH:i:sP"), "monthly", "0.2");
                $this->addLink($site_url.'/ua'.$link->url, null, $link->updated_at->format("Y-m-d\TH:i:sP"), "monthly", "0.2", 'ua');
                $this->addLink($site_url.'/en'.$link->url, null, $link->updated_at->format("Y-m-d\TH:i:sP"), "monthly", "0.2", 'en');
            }
        }

        // Путь куда нужно сохранять файл
        $this->xmlbase->saveXML(public_path()."/sitemap-ru.xml");
        $this->xmlbaseua->saveXML(public_path()."/sitemap-ua.xml");
        $this->xmlbaseen->saveXML(public_path()."/sitemap-en.xml");

        $base = '<?xml version="1.0" encoding="UTF-8"?>
			<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
			  <sitemap>
			    <loc>'.$site_url.'/sitemap-ru.xml</loc>
			    <lastmod>'.Carbon::now()->format( "Y-m-d\TH:i:sP" ).'</lastmod>
			  </sitemap>
			  <sitemap>
			    <loc>'.$site_url.'/sitemap-ua.xml</loc>
			    <lastmod>'.Carbon::now()->format( "Y-m-d\TH:i:sP" ).'</lastmod>
			  </sitemap>
			  <sitemap>
			    <loc>'.$site_url.'/sitemap-en.xml</loc>
			    <lastmod>'.Carbon::now()->format( "Y-m-d\TH:i:sP" ).'</lastmod>
			  </sitemap>
			</sitemapindex>';
        $xmlbaseindex = new \SimpleXMLElement($base);
        $xmlbaseindex->saveXML(public_path()."/sitemap.xml");
    }

    private function addLink($link, $image = null, $lastmod = null, $changefreq = null, $priority = null, $lang = ''){
        if(empty($lang)){
            $row = $this->xmlbase->addChild("url");
        }elseif($lang == 'ua'){
            $row = $this->xmlbaseua->addChild("url");
        }elseif($lang == 'en'){
            $row = $this->xmlbaseen->addChild("url");
        }
        if(isset($this->redirects[$link])){
            $row->addChild("loc", $this->redirects[$link]);
        }else{
            $row->addChild("loc", $link);
        }

        if(!empty($lastmod))
            $row->addChild("lastmod", $lastmod);
        if(!empty($changefreq))
            $row->addChild("changefreq", $changefreq);
        if(!empty($priority))
            $row->addChild("priority", $priority);

        if(!empty($image)){
            $img = $row->addChild("image:image", null, 'http://www.google.com/schemas/sitemap-image/1.1');
            $img->addChild("image:loc", $image, 'http://www.google.com/schemas/sitemap-image/1.1');
        }
    }
}
