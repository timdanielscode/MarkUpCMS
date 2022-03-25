<?php

use core\Route;
use core\Request;
use middleware\LoginMiddleware;
use middleware\AuthMiddleware;

$req = new Request();
 
Route::setRouteKeys(['id', 'username']);

Route::view('/example-route-view', '/route/route-view');


if(LoginMiddleware::logged_in() === true) {
    Route::get('/profile/[username]')->add('UserController', 'read');
    Route::get('/profile/[username]/edit')->add('UserController', 'edit');
    Route::post('/profile/[username]/edit')->add('UserController', 'update');
    Route::get('/logout')->add('LogoutController', 'logout');
} else {
    
    Route::get('/login')->add('LoginController', 'index');
    Route::post('/login')->add('LoginController', 'auth');
    Route::get('/login-admin')->add('admin\LoginController', 'index');
    Route::post('/login-admin')->add('admin\LoginController', 'auth');
    Route::get('/register')->add('RegisterController', 'create');
    Route::post('/register')->add('RegisterController', 'store');
}

if(AuthMiddleware::auth('admin') === true) {

    Route::get('/admin/dashboard')->add('admin\AdminController', 'index');
    Route::get('/admin/users')->add('admin\UserController', 'index');
    Route::get('/admin/users/create')->add('admin\UserController', 'create');
    Route::post('/admin/users/create')->add('admin\UserController', 'store');
    Route::get('/admin/users/[id]/username/[username]')->add('admin\UserController', 'read');
    Route::get('/admin/users/[id]/username/[username]/edit')->add('admin\UserController', 'edit');
    Route::post('/admin/users/[id]/username/[username]/edit')->add('admin\UserController', 'update');
    Route::get('/admin/users/[id]/username/[username]/delete')->add('admin\UserController', 'delete');

    Route::get('/admin/posts')->add('admin\PostController', 'index');
    Route::get('/admin/posts/create')->add('admin\PostController', 'create');
    Route::post('/admin/posts/create')->add('admin\PostController', 'store');
    Route::get('/admin/posts/[id]/edit')->add('admin\PostController', 'edit');
    Route::post('/admin/posts/[id]/edit')->add('admin\PostController', 'update');
    Route::get('/admin/posts/[id]/meta/edit')->add('admin\PostController', 'metaData');
    Route::post('/admin/posts/[id]/meta/edit')->add('admin\PostController', 'metaDataUpdate');
    Route::get('/admin/posts/[id]/preview')->add('admin\PostController', 'read');
    Route::get('/admin/posts/[id]/delete')->add('admin\PostController', 'delete');

    Route::get('/admin/css')->add('admin\CssController', 'index');
    Route::get('/admin/css/create')->add('admin\CssController', 'create');
    Route::post('/admin/css/create')->add('admin\CssController', 'store');
    Route::get('/admin/css/[id]/edit')->add('admin\CssController', 'edit');
    Route::post('/admin/css/[id]/edit')->add('admin\CssController', 'update');
    Route::get('/admin/css/[id]/delete')->add('admin\CssController', 'delete');

    Route::get('/admin/js')->add('admin\JsController', 'index');
    Route::get('/admin/js/create')->add('admin\JsController', 'create');
    Route::post('/admin/js/create')->add('admin\JsController', 'store');
    Route::get('/admin/js/[id]/edit')->add('admin\JsController', 'edit');
    Route::post('/admin/js/[id]/edit')->add('admin\JsController', 'update');
    Route::get('/admin/js/[id]/delete')->add('admin\JsController', 'delete');
}

    
Route::get($req->getUri())->add('RenderPageController', 'render');


