<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Request as RequestFacade;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request, Router $router) {
    dump($request->headers->all());

    return ['een' => 'twee'];
})->middleware('nope');

Route::get('/test/{foobar}', function (Request $request, Router $router, $foobar) {
    $internalCall = $request->create('/api/user?test=foo', 'GET');
    $internalCall->headers->replace(['foobar' => 'yup']);

    app()->instance('request', $internalCall);

    RequestFacade::swap($internalCall);
    // disable middleware
    app()->instance('middleware.disable', true);

    $internalResponse = $router->dispatch($internalCall);
    dump($internalResponse->isSuccessful());

    RequestFacade::swap($request);
    app()->instance('middleware.disable', false);

    return 'hello world';
});

