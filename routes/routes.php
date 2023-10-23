<?php

use core\routing\Route;
use database\DB;
use core\Session;

Route::setRouteKeys(['id', 'username']);

Route::middleware('hasNotDBConn')->run(function() { 

    Route::get('/')->add('InstallationController', 'databaseSetup');
    Route::post('/')->add('InstallationController', 'createConnection');
});

Route::middleware('hasDBConn')->run(function() { 

    Route::middleware('user')->run(function() { 

        Route::get('/')->add('InstallationController', 'createUser');
        Route::post('/')->add('InstallationController', 'storeUser');
    });

    $postPaths = DB::try()->select('slug')->from('pages')->fetch();

    if(!empty($postPaths) && $postPaths !== null) {

        foreach($postPaths as $postPath) {

            Route::get($postPath['slug'])->add('RenderPageController', 'render');
        }
    } 
});

Route::middleware('notLoggedIn')->run(function() {

    $settedWebsiteSlug = DB::try()->select('slug')->from('websiteSlug')->first();

    if(!empty($settedWebsiteSlug) && $settedWebsiteSlug !== null) {

        Route::get($settedWebsiteSlug[0])->add("LoginController", "index");
        Route::post($settedWebsiteSlug[0])->add("LoginController", "authenticateUsers");
    } else {
        Route::get("/login")->add("LoginController", "index");
        Route::post("/login")->add("LoginController", "authenticateUsers");
    }
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
});

Route::middleware(['auth' => 'admin'])->run(function() { 

    Route::get('/admin/settings')->add('admin\SettingsController', 'index');
    Route::post('/admin/settings/update-slug')->add('admin\SettingsController', 'updateSlug');
});

Route::middleware('login')->run(function() { 

    Route::get('/admin/posts/')->add('admin\PostController', 'index');
    Route::get('/admin/posts/[id]/read')->add('admin\PostController', 'read');
});

Route::middleware(['auth' => 'admin'])->run(function() { 

    Route::get('/admin/posts/create')->add('admin\PostController', 'create');
    Route::post('/admin/posts/store')->add('admin\PostController', 'store');
    Route::get('/admin/posts/[id]/edit')->add('admin\PostController', 'edit');
    Route::post('/admin/posts/[id]/update')->add('admin\PostController', 'update');
    Route::post('/admin/posts/[id]/assign-category')->add('admin\PostController', 'assignCategory');
    Route::post('/admin/posts/[id]/detach-category')->add('admin\PostController', 'detachCategory');
    Route::post('/admin/posts/[id]/update-slug')->add('admin\PostController', 'updateSlug');
    Route::post('/admin/posts/[id]/update-metadata')->add('admin\PostController', 'updateMetadata');
    Route::post('/admin/posts/[id]/link-css')->add('admin\PostController', 'linkCss');
    Route::post('/admin/posts/[id]/unlink-css')->add('admin\PostController', 'unLinkCss');
    Route::post('/admin/posts/[id]/include-js')->add('admin\PostController', 'includeJs');
    Route::post('/admin/posts/[id]/remove-js')->add('admin\PostController', 'removeJs');
    Route::post('/admin/posts/[id]/add-widget')->add('admin\PostController', 'addWidget');
    Route::post('/admin/posts/[id]/remove-widget')->add('admin\PostController', 'removeWidget');
    Route::post('/admin/posts/[id]/import-cdns')->add('admin\PostController', 'importCdns');
    Route::post('/admin/posts/[id]/export-cdns')->add('admin\PostController', 'exportCdns');

    Route::post('/admin/posts/recover')->add('admin\PostController', 'recover');
    Route::post('/admin/posts/delete')->add('admin\PostController', 'delete');
});

Route::middleware('login')->run(function() { 

    Route::get('/admin/users/')->add('admin\UserController', 'index');
    Route::get('/admin/users/[id]/read')->add('admin\UserController', 'read');
});

Route::middleware(['auth' => 'admin'])->run(function() { 

    Route::get('/admin/users/create')->add('admin\UserController', 'create');
    Route::post('/admin/users/store')->add('admin\UserController', 'store');
    Route::get('/admin/users/[username]/edit')->add('admin\UserController', 'edit');
    Route::post('/admin/users/[username]/update')->add('admin\UserController', 'update');
    Route::post('/admin/users/[username]/update-role')->add('admin\UserController', 'updateRole');
    Route::post('/admin/users/recover')->add('admin\UserController', 'recover');
    Route::post('/admin/users/delete')->add('admin\UserController', 'delete');
});

Route::middleware('login')->run(function() { 

    Route::get('/admin/css/')->add('admin\CssController', 'index');
    Route::get('/admin/css/[id]/read')->add('admin\CssController', 'read');
});

Route::middleware(['auth' => 'admin'])->run(function() { 

    Route::get('/admin/css/create')->add('admin\CssController', 'create');
    Route::post('/admin/css/store')->add('admin\CssController', 'store');
    Route::get('/admin/css/[id]/edit')->add('admin\CssController', 'edit');
    Route::post('/admin/css/[id]/update')->add('admin\CssController', 'update');
    Route::post('/admin/css/[id]/link-pages')->add('admin\CssController', 'linkPages');
    Route::post('/admin/css/[id]/unlink-pages')->add('admin\CssController', 'unlinkPages');
    Route::post('/admin/css/[id]/link-all')->add('admin\CssController', 'linkAll');
    Route::post('/admin/css/[id]/unlink-all')->add('admin\CssController', 'unlinkAll');
    Route::post('/admin/css/recover')->add('admin\CssController', 'recover');
    Route::post('/admin/css/delete')->add('admin\CssController', 'delete');
});

Route::middleware('login')->run(function() { 

    Route::get('/admin/js/')->add('admin\JsController', 'index');
    Route::get('/admin/js/[id]/read')->add('admin\JsController', 'read');
});

Route::middleware(['auth' => 'admin'])->run(function() { 

    Route::get('/admin/js/create')->add('admin\JsController', 'create');
    Route::post('/admin/js/store')->add('admin\JsController', 'store');
    Route::get('/admin/js/[id]/edit')->add('admin\JsController', 'edit');
    Route::post('/admin/js/[id]/update')->add('admin\JsController', 'update');
    Route::post('/admin/js/[id]/include-pages')->add('admin\JsController', 'includePages');
    Route::post('/admin/js/[id]/remove-pages')->add('admin\JsController', 'removePages');
    Route::post('/admin/js/[id]/include-all')->add('admin\JsController', 'includeAll');
    Route::post('/admin/js/[id]/remove-all')->add('admin\JsController', 'removeAll');
    Route::post('/admin/js/recover')->add('admin\JsController', 'recover');
    Route::post('/admin/js/delete')->add('admin\JsController', 'delete');
});

Route::middleware('login')->run(function() { 

    Route::get('/admin/menus')->add('admin\MenuController', 'index');
    Route::get('/admin/menus/[id]/read')->add('admin\MenuController', 'read');
});

Route::middleware(['auth' => 'admin'])->run(function() { 

    Route::get('/admin/menus/create')->add('admin\MenuController', 'create');
    Route::post('/admin/menus/store')->add('admin\MenuController', 'store');
    Route::get('/admin/menus/[id]/edit')->add('admin\MenuController', 'edit');
    Route::post('/admin/menus/[id]/update')->add('admin\MenuController', 'update');
    Route::post('/admin/menus/[id]/update-position')->add('admin\MenuController', 'updatePosition');
    Route::post('/admin/menus/[id]/update-ordering')->add('admin\MenuController', 'updateOrdering');
    Route::post('/admin/menus/recover')->add('admin\MenuController', 'recover');
    Route::post('/admin/menus/delete')->add('admin\MenuController', 'delete');
});

Route::middleware('login')->run(function() { 

    Route::get('/admin/media')->add('admin\MediaController', 'index');
    Route::get('/admin/media/create')->add('admin\MediaController', 'create');
});

Route::middleware(['auth' => 'admin'])->run(function() { 

    Route::post('/admin/media/create/update-filename')->add('admin\MediaController', 'UPDATEFILENAME');
    Route::post('/admin/media/create/update-description')->add('admin\MediaController', 'UPDATEDESCRIPTION');
    Route::post('/admin/media/create')->add('admin\MediaController', 'store');
    Route::post('/admin/media/update')->add('admin\MediaController', 'UPDATE');
    Route::post('/admin/media/update-filename')->add('admin\MediaController', 'UPDATEFILENAME');
    Route::post('/admin/media/update-description')->add('admin\MediaController', 'UDPATEDESCRIPTION');
    Route::post('/admin/media/delete')->add('admin\MediaController', 'delete');
});   

Route::middleware('login')->run(function() { 

    Route::get('/admin/categories')->add('admin\CategoryController', 'index');
    Route::get('/admin/categories/showaddable')->add('admin\CategoryController', 'SHOWADDABLE');
}); 

Route::middleware(['auth' => 'admin'])->run(function() { 

    Route::post('/admin/categories/addcategory')->add('admin\CategoryController', 'ADDCATEGORY');
    Route::post('/admin/categories/addpage')->add('admin\CategoryController', 'ADDPAGE');
    Route::post('/admin/categories/store')->add('admin\CategoryController', 'store');
    Route::post('/admin/categories/update')->add('admin\CategoryController', 'update');
    Route::post('/admin/categories/slug')->add('admin\CategoryController', 'SLUG');
    Route::post('/admin/categories/delete')->add('admin\CategoryController', 'delete');
}); 

Route::middleware('login')->run(function() { 

    Route::get('/admin/widgets/')->add('admin\WidgetController', 'index');
    Route::get('/admin/widgets/[id]/read')->add('admin\WidgetController', 'read');
}); 

Route::middleware(['auth' => 'admin'])->run(function() {

    Route::get('/admin/widgets/create')->add('admin\WidgetController', 'create');
    Route::post('/admin/widgets/store')->add('admin\WidgetController', 'store');
    Route::get('/admin/widgets/[id]/edit')->add('admin\WidgetController', 'edit');
    Route::post('/admin/widgets/[id]/update')->add('admin\WidgetController', 'update');
    Route::post('/admin/widgets/delete')->add('admin\WidgetController', 'delete');
    Route::post('/admin/widgets/recover')->add('admin\WidgetController', 'recover');
});

Route::middleware('login')->run(function() { 
    
    Route::get('/admin/cdn/')->add('admin\CdnController', 'index');
    Route::get('/admin/cdn/[id]/read')->add('admin\CdnController', 'read');
});

Route::middleware(['auth' => 'admin'])->run(function() {

    Route::get('/admin/cdn/create')->add('admin\CdnController', 'create');
    Route::post('/admin/cdn/store')->add('admin\CdnController', 'store');
    Route::get('/admin/cdn/[id]/edit')->add('admin\CdnController', 'edit');
    Route::post('/admin/cdn/[id]/update')->add('admin\CdnController', 'update');
    Route::post('/admin/cdn/[id]/import-pages')->add('admin\CdnController', 'importPage');
    Route::post('/admin/cdn/[id]/export-pages')->add('admin\CdnController', 'exportPage');
    Route::post('/admin/cdn/delete')->add('admin\CdnController', 'delete');
    Route::post('/admin/cdn/recover')->add('admin\CdnController', 'recover');
    Route::post('/admin/cdn/[id]/import-all')->add('admin\CdnController', 'importAll');
    Route::post('/admin/cdn/[id]/export-all')->add('admin\CdnController', 'exportAll');
});