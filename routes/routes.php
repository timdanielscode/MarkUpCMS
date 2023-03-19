<?php

use core\routing\Route;
use database\DB;

Route::setRouteKeys(['id', 'username']);

$postPaths = DB::try()->select('slug')->from('pages')->fetch();

foreach($postPaths as $postPath) {

    Route::get($postPath['slug'])->add('RenderPageController', 'render');
}

Route::middleware('login')->run(function() { 

    Route::get('/profile/[username]')->add('ProfileController', 'index');
    Route::get('/logout')->add('LogoutController', 'logout');
});


    
Route::get("/register")->add("RegisterController", "create");
Route::post("/register")->add("RegisterController", "store");
Route::get("/login")->add("LoginController", "index");
Route::post("/login")->add("LoginController", "authenticateUsers");


/* 

    Alleen voor de admins!

*/
    
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

    Route::get('/admin/media')->add('admin\MediaController', 'index');
    Route::get('/admin/media/fetch-data')->add('admin\MediaController', 'fetchData');
    Route::get('/admin/media/media-modal-fetch')->add('admin\MediaController', 'mediaModalFetch');
    Route::get('/admin/media/media-modal-fetch-preview')->add('admin\MediaController', 'mediaModalFetchPreview');
    Route::post('/admin/media')->add('admin\MediaController', 'updateFilename');
    Route::get('/admin/media/create')->add('admin\MediaController', 'create');
    Route::post('/admin/media/create')->add('admin\MediaController', 'store');
    Route::get('/admin/media/[id]/edit')->add('admin\MediaController', 'edit');
    Route::post('/admin/media/[id]/edit')->add('admin\MediaController', 'update');
    Route::get('/admin/media/[id]/delete')->add('admin\MediaController', 'delete');

    Route::get('/admin/menus')->add('admin\MenuController', 'index');
    Route::get('/admin/menus/create')->add('admin\MenuController', 'create');
    Route::post('/admin/menus/create')->add('admin\MenuController', 'store');
    Route::get('/admin/menus/[id]/edit')->add('admin\MenuController', 'edit');
    Route::post('/admin/menus/[id]/edit')->add('admin\MenuController', 'update');
    Route::get('/admin/menus/[id]/preview')->add('admin\MenuController', 'preview');
    Route::get('/admin/menus/[id]/delete')->add('admin\MenuController', 'delete');

    Route::get('/admin/categories')->add('admin\CategoryController', 'index');
    Route::get('/admin/categories/fetch-table')->add('admin\CategoryController', 'fetchTable');
    Route::get('/admin/categories/category-modal-fetch')->add('admin\CategoryController', 'categoryModalFetch');
    Route::get('/admin/categories/categories-modal-fetch-preview')->add('admin\CategoryController', 'previewCategoryPages');
    Route::post('/admin/categories')->add('admin\CategoryController', 'updateSlug');
    Route::get('/admin/categories/create')->add('admin\CategoryController', 'create');
    Route::post('/admin/categories/create')->add('admin\CategoryController', 'store');
    Route::get('/admin/categories/[id]/delete')->add('admin\CategoryController', 'delete');
    





