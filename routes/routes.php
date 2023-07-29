<?php

use core\routing\Route;
use database\DB;
use core\Session;

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

Route::middleware('notLoggedIn')->run(function() {

    Route::get("/register")->add("RegisterController", "create");
    Route::post("/register")->add("RegisterController", "store");

    Route::get("/login-independentcms")->add("LoginController", "index");
    Route::post("/login-independentcms")->add("LoginController", "authenticateUsers");
});

Route::middleware('login')->run(function() { 

    Route::get('/admin/dashboard')->add('admin\DashboardController', 'index');

    Route::get('/admin/profile/' . Session::get('username'))->add('admin\ProfileController', 'index');
    Route::post('/admin/profile/' . Session::get('username') . '/update')->add('admin\ProfileController', 'updateDetails');
    Route::post('/admin/profile/' . Session::get('username') . '/update-role')->add('admin\ProfileController', 'updateRole');
    Route::get('/admin/profile/' . Session::get('username') . '/change-password')->add('admin\ProfileController', 'editPassword');
    Route::post('/admin/profile/' . Session::get('username') . '/change-password')->add('admin\ProfileController', 'updatePassword');
    Route::post('/admin/profile/' . Session::get('username') . '/delete')->add('admin\ProfileController', 'delete');

    Route::get('/logout')->add('LogoutController', 'logout');

    Route::crud('/admin/posts', '[id]')->add('admin\PostController', 'crud');
    Route::post('/admin/posts/[id]/assign-category')->add('admin\PostController', 'assignCategory');
    Route::post('/admin/posts/[id]/detach-category')->add('admin\PostController', 'detachCategory');
    Route::post('/admin/posts/[id]/update-slug')->add('admin\PostController', 'updateSlug');
    Route::post('/admin/posts/[id]/update-metadata')->add('admin\PostController', 'updateMetadata');
    Route::post('/admin/posts/[id]/link-css')->add('admin\PostController', 'linkCss');
    Route::post('/admin/posts/[id]/unlink-css')->add('admin\PostController', 'unLinkCss');
    Route::post('/admin/posts/[id]/include-js')->add('admin\PostController', 'includeJs');
    Route::post('/admin/posts/[id]/remove-js')->add('admin\PostController', 'removeJs');
    Route::get('/admin/posts/[id]/recover')->add('admin\PostController', 'recover');

    Route::crud('/admin/users', '[username]')->add('admin\UserController', 'crud');
    Route::post('/admin/users/[username]/update-role')->add('admin\UserController', 'updateRole');
    
    Route::crud('/admin/css', '[id]')->add('admin\CssController', 'crud');
    Route::post('/admin/css/[id]/link-pages')->add('admin\CssController', 'linkPages');
    Route::post('/admin/css/[id]/unlink-pages')->add('admin\CssController', 'unlinkPages');
    Route::post('/admin/css/[id]/link-all')->add('admin\CssController', 'linkAll');
    Route::post('/admin/css/[id]/unlink-all')->add('admin\CssController', 'unlinkAll');
    Route::get('/admin/css/[id]/recover')->add('admin\CssController', 'recover');

    Route::crud('/admin/js', '[id]')->add('admin\JsController', 'crud');
    Route::post('/admin/js/[id]/include-pages')->add('admin\JsController', 'includePages');
    Route::post('/admin/js/[id]/remove-pages')->add('admin\JsController', 'removePages');
    Route::post('/admin/js/[id]/include-all')->add('admin\JsController', 'includeAll');
    Route::post('/admin/js/[id]/remove-all')->add('admin\JsController', 'removeAll');

    Route::crud('/admin/menus', '[id]')->add('admin\MenuController', 'crud');
    Route::post('/admin/menus/[id]/update-position')->add('admin\MenuController', 'updatePosition');
    Route::post('/admin/menus/[id]/update-ordering')->add('admin\MenuController', 'updateOrdering');
    Route::get('/admin/menus/[id]/recover')->add('admin\MenuController', 'recover');

    Route::get('/admin/media')->add('admin\MediaController', 'index');
    Route::get('/admin/media/')->add('admin\MediaController', 'TABLE');
    Route::get('/admin/media/edit')->add('admin\MediaController', 'EDIT');
    Route::post('/admin/media/update')->add('admin\MediaController', 'UPDATE');
    Route::post('/admin/media/update-filename')->add('admin\MediaController', 'UPDATEFILENAME');

    Route::get('/admin/media/create')->add('admin\MediaController', 'create');
    Route::post('/admin/media/create')->add('admin\MediaController', 'store');

    Route::get('/admin/media/read')->add('admin\MediaController', 'READ');
    Route::get('/admin/media/[id]/delete')->add('admin\MediaController', 'delete');
    
    Route::get('/admin/categories')->add('admin\CategoryController', 'index');
    Route::get('/admin/categories/')->add('admin\CategoryController', 'TABLE');
    
    Route::get('/admin/categories/read')->add('admin\CategoryController', 'READ');
    Route::get('/admin/categories/edit')->add('admin\CategoryController', 'EDIT');
    Route::post('/admin/categories/update')->add('admin\CategoryController', 'UPDATE');
    Route::get('/admin/categories/showaddable')->add('admin\CategoryController', 'SHOWADDABLE');
    Route::post('/admin/categories/addpage')->add('admin\CategoryController', 'ADDPAGE');
    Route::post('/admin/categories/addcategory')->add('admin\CategoryController', 'ADDCATEGORY');
    Route::post('/admin/categories/slug')->add('admin\CategoryController', 'SLUG');

    Route::get('/admin/categories/create')->add('admin\CategoryController', 'create');
    Route::post('/admin/categories/store')->add('admin\CategoryController', 'store');
    Route::get('/admin/categories/[id]/delete')->add('admin\CategoryController', 'delete');
});