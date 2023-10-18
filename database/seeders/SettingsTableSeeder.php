<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = array(
            array('id' => '1','key' => 'meta_title','value' => 'Milam','autoload' => '1'),
            array('id' => '2','key' => 'meta_description','value' => 'Milam','autoload' => '1'),
            array('id' => '3','key' => 'meta_keywords','value' => NULL,'autoload' => '1'),
            array('id' => '5','key' => 'terms','value' => NULL,'autoload' => '1'),
            array('id' => '6','key' => 'main_phone_1','value' => '+38 (050) 327 25 26','autoload' => '1'),
            array('id' => '8','key' => 'other_phones','value' => '["+38 (057) 719 46 19"]','autoload' => '1'),
            array('id' => '9','key' => 'notify_emails','value' => '["piwzoleg@gmail.com","zayavkiclient@gmail.com"]','autoload' => '1'),
            array('id' => '10','key' => 'images_sizes','value' => '{}','autoload' => '0'),
            array('id' => '11','key' => 'liqpay_api_public_key','value' => '','autoload' => '0'),
            array('id' => '12','key' => 'liqpay_api_private_key','value' => '','autoload' => '0'),
            array('id' => '13','key' => 'liqpay_api_currency','value' => 'UAH','autoload' => '0'),
            array('id' => '14','key' => 'liqpay_api_sandbox','value' => '1','autoload' => '0'),
            array('id' => '15','key' => 'ld_name','value' => 'ТОВ Торговий Дім «Пірана»','autoload' => '1'),
            array('id' => '16','key' => 'ld_description','value' => 'Завод побутової хімії','autoload' => '1'),
            array('id' => '17','key' => 'ld_region','value' => 'Харківська','autoload' => '1'),
            array('id' => '18','key' => 'ld_city','value' => 'Васищеве','autoload' => '1'),
            array('id' => '19','key' => 'ld_street','value' => 'вул. Промислова, 4','autoload' => '1'),
            array('id' => '20','key' => 'ld_postcode','value' => '62495','autoload' => '1'),
            array('id' => '21','key' => 'ld_phone','value' => '+38 (050) 327 25 26','autoload' => '1'),
            array('id' => '22','key' => 'ld_payments','value' => '["cash","credit card"]','autoload' => '1'),
            array('id' => '23','key' => 'ld_image','value' => '2797','autoload' => '1'),
            array('id' => '24','key' => 'ld_type','value' => 'Store','autoload' => '1'),
            array('id' => '25','key' => 'ld_latitude','value' => '49.8554099404479','autoload' => '1'),
            array('id' => '26','key' => 'ld_longitude','value' => '36.30919069807644','autoload' => '1'),
            array('id' => '27','key' => 'ga_token','value' => '{"access_token":null,"token_type":null,"created":null,"expires_in":null}','autoload' => '1'),
            array('id' => '28','key' => 'gtm','value' => '<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({\'gtm.start\':
new Date().getTime(),event:\'gtm.js\'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!=\'dataLayer\'?\'&l=\'+l:\'\';j.async=true;j.src=
\'https://www.googletagmanager.com/gtm.js?id=\'+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,\'script\',\'dataLayer\',\'GTM-MXTGMR3\');</script>','autoload' => '1'),
            array('id' => '29','key' => 'gtm_noscript','value' => '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MXTGMR3"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>','autoload' => '1'),
            array('id' => '30','key' => 'ld_opening_hours','value' => '{"Mo":{"trigger":"on","hours_from":"09","minutes_from":"00","hours_to":"17","minutes_to":"00"},"Tu":{"trigger":"on","hours_from":"09","minutes_from":"00","hours_to":"17","minutes_to":"00"},"We":{"trigger":"on","hours_from":"09","minutes_from":"00","hours_to":"17","minutes_to":"00"},"Th":{"trigger":"on","hours_from":"09","minutes_from":"00","hours_to":"17","minutes_to":"00"},"Fr":{"trigger":"on","hours_from":"09","minutes_from":"00","hours_to":"17","minutes_to":"00"},"Sa":{"trigger":"on","hours_from":"09","minutes_from":"00","hours_to":"17","minutes_to":"00"},"Su":{"trigger":"on","hours_from":"09","minutes_from":"00","hours_to":"13","minutes_to":"00"}}','autoload' => '1'),
            array('id' => '31','key' => 'social','value' => '["https:\\/\\/www.instagram.com\\/himia_milam\\/"]','autoload' => '1'),
            array('id' => '32','key' => 'fb_pixel','value' => NULL,'autoload' => '1'),
            array('id' => '33','key' => 'delivery_information_ru','value' => NULL,'autoload' => '1'),
            array('id' => '34','key' => 'newpost_cities_last_update','value' => '1595503490','autoload' => '0'),
            array('id' => '35','key' => 'newpost_warehouses_last_update','value' => '1595503492','autoload' => '0'),
            array('id' => '36','key' => 'newpost_api_key','value' => 'de3a7a9105c46f543fc242b09020efa8','autoload' => '0'),
            array('id' => '37','key' => 'newpost_regions_update_period','value' => '15552000','autoload' => '0'),
            array('id' => '38','key' => 'newpost_cities_update_period','value' => '2592000','autoload' => '0'),
            array('id' => '39','key' => 'newpost_warehouses_update_period','value' => '604800','autoload' => '0'),
            array('id' => '40','key' => 'newpost_regions_last_update','value' => '1595503506','autoload' => '0'),
            array('id' => '41','key' => 'delivery_information_ua','value' => NULL,'autoload' => '1'),
            array('id' => '42','key' => 'landing_center_ru','value' => NULL,'autoload' => '1'),
            array('id' => '43','key' => 'landing_center_ua','value' => NULL,'autoload' => '1'),
            array('id' => '44','key' => 'landing_bottom_ru','value' => NULL,'autoload' => '1'),
            array('id' => '45','key' => 'landing_bottom_ua','value' => NULL,'autoload' => '1'),
            array('id' => '46','key' => 'site_message_ru','value' => NULL,'autoload' => '1'),
            array('id' => '47','key' => 'site_message_ua','value' => NULL,'autoload' => '1'),
            array('id' => '48','key' => 'site_message_bg_ru','value' => NULL,'autoload' => '1'),
            array('id' => '49','key' => 'site_message_bg_ua','value' => NULL,'autoload' => '1'),
            array('id' => '50','key' => 'site_message_enabled','value' => '0','autoload' => '1'),
            array('id' => '51','key' => 'sms_payment','value' => NULL,'autoload' => '1'),
            array('id' => '52','key' => 'sms_delivery','value' => NULL,'autoload' => '1'),
            array('id' => '53','key' => 'sms_promo','value' => NULL,'autoload' => '1'),
            array('id' => '54','key' => 'template_public.layouts.pages.home','value' => '{"path":"resources\\/views\\/public.layouts.pages.home.blade.php","name":"public.layouts.pages.home","fields":[{"name":"Слайдер","slug":"slider","type":"repeater","fields":[{"name":"Изображение","slug":"image","type":"oembed"},{"name":"Мобильное изображение","slug":"image_mob","type":"oembed"},{"name":"Текст","slug":"text","type":"text","langs":"1"},{"name":"Кнопка","slug":"button","type":"text","langs":"1"},{"name":"Ссылка","slug":"link","type":"text","langs":"0"}]},{"name":"Заголовок блока популярные позиции","slug":"popular_title","type":"text","langs":"1"},{"name":"Популярные позиции","slug":"popular","type":"repeater","fields":[{"name":"Товар","slug":"product","type":"product"}]},{"name":"Заголовок блока о нас","slug":"about_title","type":"text","langs":"1"},{"name":"Текст блока о нас","slug":"about_text","type":"wysiwyg","langs":"1"},{"name":"Изображение блока о нас","slug":"about_image","type":"oembed"},{"name":"Бренды","slug":"brands","type":"repeater","fields":[{"name":"Логотип","slug":"logo","type":"oembed"},{"name":"Название","slug":"name","type":"text","langs":"1"},{"name":"Описание","slug":"description","type":"textarea","langs":"1"}]}]}','autoload' => '0'),
            array('id' => '55','key' => 'template_public.layouts.pages.about','value' => '{"path":"resources\\/views\\/public.layouts.pages.about.blade.php","name":"public.layouts.pages.about","fields":[{"name":"Заголовок первого блока","slug":"screen_1_title","type":"text","langs":"1"},{"name":"Текст первого блока","slug":"screen_1_text","type":"wysiwyg","langs":"1"},{"name":"Изображение первого блока","slug":"screen_1_image","type":"oembed"},{"name":"Заголовок второго блока","slug":"screen_2_title","type":"text","langs":"1"},{"name":"Текст второго блока","slug":"screen_2_text","type":"wysiwyg","langs":"1"},{"name":"Изображение 1 второго блока","slug":"screen_2_image_1","type":"oembed"},{"name":"Изображение 2 второго блока","slug":"screen_2_image_2","type":"oembed"},{"name":"Заголовок третьего блока","slug":"screen_3_title","type":"text","langs":"1"},{"name":"Текст третьего блока","slug":"screen_3_text","type":"wysiwyg","langs":"1"},{"name":"Изображение 1 третьего блока","slug":"screen_3_image_1","type":"oembed"},{"name":"Изображение 2 третьего блока","slug":"screen_3_image_2","type":"oembed"}]}','autoload' => '0'),
            array('id' => '56','key' => 'template_public.layouts.pages.contacts','value' => '{"path":"resources\\/views\\/public.layouts.pages.contacts.blade.php","name":"public.layouts.pages.contacts","fields":[{"name":"Заголовок первого блока","slug":"screen_1_title","type":"text","langs":"1"},{"name":"Адрес производства","slug":"screen_1_address","type":"wysiwyg","langs":"1"},{"name":"Телефоны","slug":"screen_1_phones","type":"repeater","fields":[{"name":"Телефон","slug":"phone","type":"text","langs":"0"}]},{"name":"Режим работы","slug":"screen_1_schedule","type":"wysiwyg","langs":"1"},{"name":"Email","slug":"screen_1_email","type":"text","langs":"0"},{"name":"Кнопка","slug":"screen_1_button","type":"text","langs":"1"},{"name":"Instagram","slug":"screen_1_instagram","type":"text","langs":"0"},{"name":"Изображение первого блока","slug":"screen_1_image","type":"oembed"},{"name":"Мобильное изображение первого блока","slug":"screen_1_image_mob","type":"oembed"},{"name":"Заголовок второго блока","slug":"screen_2_title","type":"text","langs":"1"},{"name":"Подзаголовок второго блока","slug":"screen_2_subtitle","type":"text","langs":"1"},{"name":"Контакты отдела сбыта","slug":"sales_department_contacts","type":"repeater","fields":[{"name":"Телефоны","slug":"phones","type":"repeater","fields":[{"name":"Телефон","slug":"phone","type":"text","langs":"0"}]},{"name":"Email","slug":"email","type":"text","langs":"0"},{"name":"Контактное лицо","slug":"contact","type":"text","langs":"1"}]},{"name":"Контакты отдела снабжения","slug":"supply_department_contacts","type":"repeater","fields":[{"name":"Телефоны","slug":"phones","type":"repeater","fields":[{"name":"Телефон","slug":"phone","type":"text","langs":"0"}]},{"name":"Email","slug":"email","type":"text","langs":"0"},{"name":"Контактное лицо","slug":"contact","type":"text","langs":"1"}]}]}','autoload' => '0'),
            array('id' => '57','key' => 'template_public.layouts.pages.partners','value' => '{"path":"resources\\/views\\/public.layouts.pages.partners.blade.php","name":"public.layouts.pages.partners","fields":[{"name":"Дистрибьюторы","slug":"distributors","type":"repeater","fields":[{"name":"Область","slug":"region","type":"text","langs":"1"},{"name":"Контакты","slug":"contacts","type":"repeater","fields":[{"name":"Дистрибьтор","slug":"distributor","type":"text","langs":"1"},{"name":"Город","slug":"city","type":"text","langs":"1"},{"name":"Телефон","slug":"phone","type":"text","langs":"0"},{"name":"Email","slug":"email","type":"text","langs":"0"}]}]},{"name":"Поставщики","slug":"suppliers","type":"repeater","fields":[{"name":"Название","slug":"name","type":"text","langs":"1"},{"name":"Описание","slug":"description","type":"text","langs":"1"}]}]}','autoload' => '0'),
            array('id' => '58','key' => 'products_meta_title_ru','value' => '[product_name] - от производителя | ТД Пирана','autoload' => '1'),
            array('id' => '59','key' => 'products_meta_title_ua','value' => '[product_name] - від виробника | ТД Пірана','autoload' => '1'),
            array('id' => '60','key' => 'products_meta_title_en','value' => '[product_name] - from manufacturer | TH Pirana','autoload' => '1'),
            array('id' => '61','key' => 'products_meta_description_ru','value' => '⭐️ [product_name] ⚡ Достичь чистоты и свежести в доме легко вместе с Торговым Домом Пирана','autoload' => '1'),
            array('id' => '62','key' => 'products_meta_description_ua','value' => '⭐️ [product_name] ⚡ Досягти чистоти та свіжості в будинку легко разом з с Торговим Дімом Пірана','autoload' => '1'),
            array('id' => '63','key' => 'products_meta_description_en','value' => '⭐️ [product_name] ⚡ Achieving cleanliness and freshness in the house is easy with Pirana Trading House','autoload' => '1'),
            array('id' => '64','key' => 'products_meta_keywords_ru','value' => NULL,'autoload' => '1'),
            array('id' => '65','key' => 'products_meta_keywords_ua','value' => NULL,'autoload' => '1'),
            array('id' => '66','key' => 'products_meta_keywords_en','value' => NULL,'autoload' => '1'),
            array('id' => '67','key' => 'categories_meta_title_ru','value' => 'ᐈ [category_name] выбирайте в Украине от производителя | ТД Пирана','autoload' => '1'),
            array('id' => '68','key' => 'categories_meta_title_ua','value' => 'ᐈ [category_name] обирайте в Україні від виробника | ТД Пірана','autoload' => '1'),
            array('id' => '69','key' => 'categories_meta_title_en','value' => 'ᐈ [category_name] chose in Ukraine from the manufacturer | THPirana','autoload' => '1'),
            array('id' => '70','key' => 'categories_meta_description_ru','value' => '⭐️ [category_name] ⚡ Откройте для себя чистоту и свежесть вместе с торговыми марками Милам и Милам Chemical от Торгового Дома Пирана!','autoload' => '1'),
            array('id' => '71','key' => 'categories_meta_description_ua','value' => '⭐️ [category_name] ⚡ Відкрийте для себе чистоту та свіжість разом з торговельними марками Милам та Милам Chemical від Торгового Діма Пірана!','autoload' => '1'),
            array('id' => '72','key' => 'categories_meta_description_en','value' => '⭐️ [category_name] ⚡ Discover purity and freshness with brands of the Milam and Milam Chemical from Pirana Trading House','autoload' => '1'),
            array('id' => '73','key' => 'categories_meta_keywords_ru','value' => NULL,'autoload' => '1'),
            array('id' => '74','key' => 'categories_meta_keywords_ua','value' => NULL,'autoload' => '1'),
            array('id' => '75','key' => 'categories_meta_keywords_en','value' => NULL,'autoload' => '1')
        );

	    DB::table('settings')->insert($settings);
    }
}
