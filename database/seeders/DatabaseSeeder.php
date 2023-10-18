<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FilesTableSeeder::class);
        $this->call(AttributesTableSeeder::class);
        $this->call(AttributeValuesTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(CategoryAttributesTableSeeder::class);
        $this->call(ImageTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(OrderStatusSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(ProductsReviewSeeder::class);
	    $this->call(SeoTableSeeder::class);
	    $this->call(LocalizationTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(ProductCategoriesTableSeeder::class);
        $this->call(ProductAttributesTableSeeder::class);
        $this->call(SentinelUsersTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(UserDataSeeder::class);
        $this->call(CartTableSeeder::class);
        $this->call(BlogTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(GalleriesTableSeeder::class);
        $this->call(ModuleSlideshowTableSeeder::class);
        $this->call(ModuleLatestTableSeeder::class);
        $this->call(ModuleBestsellersTableSeeder::class);
        $this->call(NewpostCitiesTableSeeder::class);
        $this->call(NewpostRegionsTableSeeder::class);
        $this->call(NewpostWarehousesTableSeeder::class);
        $this->call(SalesTableSeeder::class);
        $this->call(SalesProductsTableSeeder::class);
        $this->call(NewsProductsTableSeeder::class);
        $this->call(WorksTableSeeder::class);
        $this->call(PhotosTableSeeder::class);
        $this->call(PhotoItemsTableSeeder::class);
        $this->call(VideosTableSeeder::class);
        $this->call(VideoItemsTableSeeder::class);
    }
}
