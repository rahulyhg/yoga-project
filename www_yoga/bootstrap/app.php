<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new App\Base\BaseApplication();


$app->withFacades(true, [
	Illuminate\Support\Facades\Session::class => "Session",
    Illuminate\Support\Facades\Cookie::class => "Cookie",
]);

 $app->withFacades();

 //$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
	Illuminate\Contracts\Support\Session::class,
	Symfony\Component\HttpFoundation\Session\Session::class
);

/*$app->singleton(
    Illuminate\Contracts\Support\Cookie::class,
    Symfony\Component\HttpFoundation\Cookie::class
);*/
/*$app->resolving(Illuminate\Http\Request::class, function ($request, $app) {
	$request->setSession($app->session);
});*/

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/


// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

$app->middleware([
    App\Http\Middleware\ApiMiddleware::class
]);

$app->middleware([
    App\Http\Middleware\WxMiddleware::class
]);



/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/
$app->register(App\Providers\SessionServiceProvider::class);
//$app->register( \Illuminate\Session\Middleware\StartSession::class);
// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

$app->register(Overtrue\LaravelWeChat\ServiceProvider::class);


// 载入session相关配置
$app->configure('session');
/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

/*$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {


    require __DIR__.'/../routes/web.php';
});*/



return $app;
