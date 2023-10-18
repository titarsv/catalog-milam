<?php
$isSecure = (!empty($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] != 'off');
$method = ($isSecure ? 'https://' : 'http://');
$parts = explode('?', $_SERVER['REQUEST_URI']);

$data = explode(PHP_EOL, file_get_contents(__DIR__.'/../storage/app/redirects.csv'));
$redirects = [];
foreach($data as $row){
    $cells = explode(',', $row);
    if(count($cells) == 2){
        $redirects[$cells[0]] = $cells[1];
    }
}
if(isset($redirects[$_SERVER['REQUEST_URI']])){
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: https://milam.in.ua".$redirects[$_SERVER['REQUEST_URI']]);
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'GET' && $parts[0] != strtolower($parts[0])){
    header("HTTP/1.1 301 Moved Permanently");
    if(count($parts) == 1){
        header('Location: '.strtolower($_SERVER['REQUEST_URI']));
    }else{
        header('Location: '.strtolower(preg_replace("/([^\/])$/i",'$1/', $parts[0])).'?'.$parts[1]);
    }
    exit();
}

//if(strpos($_SERVER['REQUEST_URI'], '/admin') !== 0){
//    if(empty($_COOKIE['lang']) && in_array(substr($_SERVER['REQUEST_URI'], 0, 3), ['/ru', '/en'])){
//        header("HTTP/1.1 302 Found");
//        header("Location: ".(strlen($_SERVER['REQUEST_URI']) > 3 ? substr($_SERVER['REQUEST_URI'], 3) : '/'));
//        exit();
//    }elseif(!empty($_COOKIE['lang'])){
//        $currnt_lang = $_SERVER['REQUEST_URI'] === '/ru' || strpos($_SERVER['REQUEST_URI'], '/ru/') === 0 ? 'ru' : ($_SERVER['REQUEST_URI'] === '/en' || strpos($_SERVER['REQUEST_URI'], '/en/') === 0 ? 'en' : 'ua');
//        if($_COOKIE['lang'] !== $currnt_lang){
//            if($currnt_lang === 'ua'){
//                $url = rtrim($_SERVER['REQUEST_URI'] == '/ua' ? '/' : substr($_SERVER['REQUEST_URI'], 3), '/');
//            }else{
//                $url = rtrim('/ua'.$_SERVER['REQUEST_URI'], '/');
//            }
//            header("HTTP/1.1 302 Found");
//            header("Location: ".$url);
//            exit();
//        }
//    }
//}

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists(__DIR__.'/../storage/framework/maintenance.php')) {
    require __DIR__.'/../storage/framework/maintenance.php';
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

if (file_exists(__DIR__ . '/../vendor/zerospam/laravel-gettext/src/Xinax/LaravelGettext/Support/helpers.php')) {
    require __DIR__ . '/../vendor/zerospam/laravel-gettext/src/Xinax/LaravelGettext/Support/helpers.php';
}
require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = tap($kernel->handle(
    $request = Request::capture()
))->send();

$kernel->terminate($request, $response);
