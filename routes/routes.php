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

    Route::crud('/admin/posts', '[id]')->add('admin\PostController', 'crud');
    Route::get('/admin/posts/[id]/meta/edit')->add('admin\PostController', 'metaData');
    Route::post('/admin/posts/[id]/meta/edit')->add('admin\PostController', 'metaDataUpdate');
    
    Route::crud('/admin/users', '[username]')->add('admin\UserController', 'crud');
    
    Route::crud('/admin/css', '[id]')->add('admin\CssController', 'crud');
    Route::crud('/admin/js', '[id]')->add('admin\JsController', 'crud');

    Route::crud('/admin/menus', '[id]')->add('admin\MenuController', 'crud');

    Route::get('/admin/media')->add('admin\MediaController', 'index');
    Route::get('/admin/media/create')->add('admin\MediaController', 'create');
    Route::post('/admin/media/create')->add('admin\MediaController', 'store');
    Route::get('/admin/media/fetch-data')->add('admin\MediaController', 'fetchData');
    Route::get('/admin/media/media-modal-fetch')->add('admin\MediaController', 'mediaModalFetch');
    Route::get('/admin/media/media-modal-fetch-preview')->add('admin\MediaController', 'mediaModalFetchRead');
    Route::post('/admin/media')->add('admin\MediaController', 'update');
    Route::get('/admin/media/[id]/delete')->add('admin\MediaController', 'delete');
    










    
    Route::get('/admin/categories')->add('admin\CategoryController', 'index');
    //Route::get('/admin/categories/pagination')->add('admin\CategoryController', 'PAGINATION');
    Route::get('/admin/categories/')->add('admin\CategoryController', 'TABLE');
    
    Route::get('/admin/categories/read')->add('admin\CategoryController', 'READ');
    Route::get('/admin/categories/edit')->add('admin\CategoryController', 'EDIT');
    Route::post('/admin/categories/update')->add('admin\CategoryController', 'UPDATE');
    Route::get('/admin/categories/showaddable')->add('admin\CategoryController', 'SHOWADDABLE');
    Route::post('/admin/categories/addpage')->add('admin\CategoryController', 'ADDPAGE');
    Route::post('/admin/categories/addcategory')->add('admin\CategoryController', 'ADDCATEGORY');
    Route::post('/admin/categories/slug')->add('admin\CategoryController', 'SLUG');

    Route::get('/admin/categories/create')->add('admin\CategoryController', 'create');
    Route::post('/admin/categories/create')->add('admin\CategoryController', 'store');
    Route::get('/admin/categories/[id]/delete')->add('admin\CategoryController', 'delete');
});    





