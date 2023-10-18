<?php

if(!function_exists('translit')){
    function translit($string, $reverse = false){
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => "",    'ы' => 'y',   'ъ' => "",
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => "",    'Ы' => 'Y',   'Ъ' => "",
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        if($reverse){
            unset($converter['ь']);
            unset($converter['ъ']);
            unset($converter['Ь']);
            unset($converter['Ъ']);
            $converter = array_flip($converter);
        }
        return strtr($string, $converter);
    }
}

if(!function_exists('localizationFields')){
	function localizationFields($fields){
		$locales = config()->get('app.locales');
		$localization_fields = [];
		foreach($fields as $field){
			foreach($locales as $locale){
				$localization_fields[] = $field.'_'.$locale;
			}
		}

		return $localization_fields;
	}
}

if(!function_exists('base_url')){
    function base_url($path = '/'){
        return env('APP_URL').rtrim(app()->getLocale() != 'ua' ? '/'.app()->getLocale().$path : $path, '/');
    }
}

if(!function_exists('formatted_price')){
    function formatted_price($price){
        $currencies = config()->get('app.currencies');
        $currency = config()->get('app.currency');

        if($currency == 'uah' || !isset($currencies[$currency])){
            return number_format($price, 2, ',', ' ').' '.$currencies[$currency];
        }else{
            $rate = config()->get('app.rate');
            return $currencies[$currency].' '.number_format($price / $rate, 2, ',', ' ');
        }
    }
}