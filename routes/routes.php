<?php

use core\Route;
use middleware\LoginMiddleware;
use middleware\AuthMiddleware;
 
Route::setRouteKeys(['id', 'username']);

Route::get('/')->add('HomeController', 'index');

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
}



