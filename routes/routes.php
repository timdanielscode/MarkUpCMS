<?php

use core\routing\Route;
use database\DB;

Route::setRouteKeys(['id', 'username']);

$postPaths = DB::try()->select('slug')->from('pages')->fetch();

if(!empty($postPaths) && $postPaths !== null) {

    foreach($postPaths as $postPath) {

        Route::get($postPath['slug'])->add('RenderPageController', 'render');
    }
} else {
    
    Route::middleware('user')->run(function() { 

        Route::get('/')->add('InstallationController', 'create');
        Route::post('/')->add('InstallationController', 'store');
    });
}


Route::middleware('login')->run(function() { 

    Route::get('/profile/[username]')->add('ProfileController', 'index');
    Route::get('/logout')->add('LogoutController', 'logout');
});

Route::middleware('notLoggedIn')->run(function() {

    Route::get("/register")->add("RegisterController", "create");
    Route::post("/register")->add("RegisterController", "store");
    Route::get("/login")->add("LoginController", "index");
    Route::post("/login")->add("LoginController", "authenticateUsers");
});
    
Route::middleware(['auth' => 'admin'])->run(function() { 

    Route::get('/admin/dashboard')->add('admin\AdminController', 'index');

    Route::crud('/admin/users', '[username]')->add('admin\UserController', 'crud');

    Route::crud('/admin/posts', '[id]')->add('admin\PostController', 'crud');
    Route::get('/admin/posts/[id]/meta/edit')->add('admin\PostController', 'metaData');
    Route::post('/admin/posts/[id]/meta/edit')->add('admin\PostController', 'metaDataUpdate');

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
});    





