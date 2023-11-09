<?php

use core\routing\Route;
use database\DB;
use core\Session;
use core\http\Middleware;

Middleware::route('hasNotDBConn', function() { 

    new Route(['GET' => '/'], ['InstallationController' => 'databaseSetup']);
    new Route(['POST' => '/'], ['InstallationController' => 'createConnection']);
});

Middleware::route('hasDBConn', function() { 

    Middleware::route('user', function() { 

        new Route(['GET' => '/'], ['InstallationController' => 'createUser']);
        new Route(['POST' => '/'], ['InstallationController' => 'storeUser']);
    });

    $postPaths = DB::try()->select('slug')->from('pages')->fetch();

    if(!empty($postPaths) && $postPaths !== null) {

        foreach($postPaths as $postPath) {
   
            new Route(['GET' => $postPath['slug']], ['RenderPageController' => 'render']);
        }
    } 
});

Middleware::route('notLoggedIn', function() { 

    $settedWebsiteSlug = DB::try()->select('slug')->from('websiteSlug')->first();

    if(!empty($settedWebsiteSlug) && $settedWebsiteSlug !== null) {

        new Route(['GET' => $settedWebsiteSlug[0]], ['LoginController' => 'index']);
        new Route(['POST' => $settedWebsiteSlug[0]], ['LoginController' => 'authenticateUsers']);
    } else {
        new Route(['GET' => '/login'], ['LoginController' => 'index']);
        new Route(['POST' => '/login'], ['LoginController' => 'authenticateUsers']);
    }
});

Middleware::route('login', function() { 

    new Route(['GET' => '/admin/dashboard'], ['admin\DashboardController' => 'index']);
    new Route(['GET' => '/admin/profile/' . Session::get('username')], ['admin\ProfileController' => 'index']);
    new Route(['POST' => '/admin/profile/' . Session::get('username') . '/update'], ['admin\ProfileController' => 'updateDetails']);
    new Route(['POST' => '/admin/profile/' . Session::get('username') . '/update-role'], ['admin\ProfileController' => 'updateRole']);
    new Route(['GET' => '/admin/profile/' . Session::get('username') . '/change-password'], ['admin\ProfileController' => 'editPassword']);
    new Route(['POST' => '/admin/profile/' . Session::get('username') . '/change-password'], ['admin\ProfileController' => 'updatePassword']);
    new Route(['POST' => '/admin/profile/' . Session::get('username') . '/delete'], ['admin\ProfileController' => 'delete']);
    new Route(['GET' => '/logout'], ['LogoutController' => 'logout']);
});

Middleware::route(['auth' => 'admin'], function() { 

    new Route(['GET' => '/admin/settings'], ['admin\SettingsController' => 'index']);
    new Route(['POST' => '/admin/settings/update-slug'], ['admin\SettingsController' => 'updateSlug']);
});

Middleware::route('login', function() { 

    new Route(['GET' => '/admin/posts'], ['admin\PostController' => 'index']);
    new Route(['GET' => '/admin/posts/[id]/read'], ['admin\PostController' => 'read']);
});

Middleware::route(['auth' => 'admin'], function() { 

    new Route(['GET' => '/admin/posts/create'], ['admin\PostController' => 'create']);
    new Route(['POST' => '/admin/posts/store'], ['admin\PostController' => 'store']);
    new Route(['GET' => '/admin/posts/[id]/edit'], ['admin\PostController' => 'edit']);
    new Route(['POST' => '/admin/posts/[id]/update'], ['admin\PostController' => 'update']);
    new Route(['POST' => '/admin/posts/[id]/assign-category'], ['admin\PostController' => 'assignCategory']);
    new Route(['POST' => '/admin/posts/[id]/detach-category'], ['admin\PostController' => 'detachCategory']);
    new Route(['POST' => '/admin/posts/[id]/update-slug'], ['admin\PostController' => 'updateSlug']);
    new Route(['POST' => '/admin/posts/[id]/update-metadata'], ['admin\PostController' => 'updateMetadata']);
    new Route(['POST' => '/admin/posts/[id]/link-css'], ['admin\PostController' => 'linkCss']);
    new Route(['POST' => '/admin/posts/[id]/unlink-css'], ['admin\PostController' => 'unLinkCss']);
    new Route(['POST' => '/admin/posts/[id]/include-js'], ['admin\PostController' => 'includeJs']);
    new Route(['POST' => '/admin/posts/[id]/remove-js'], ['admin\PostController' => 'removeJs']);
    new Route(['POST' => '/admin/posts/[id]/add-widget'], ['admin\PostController' => 'addWidget']);
    new Route(['POST' => '/admin/posts/[id]/remove-widget'], ['admin\PostController' => 'removeWidget']);
    new Route(['POST' => '/admin/posts/[id]/import-cdns'], ['admin\PostController' => 'importCdns']);
    new Route(['POST' => '/admin/posts/[id]/export-cdns'], ['admin\PostController' => 'exportCdns']);
    new Route(['POST' => '/admin/posts/recover'], ['admin\PostController' => 'recover']);
    new Route(['POST' => '/admin/posts/delete'], ['admin\PostController' => 'delete']);
});

Middleware::route('login', function() {

    new Route(['GET' => '/admin/users'], ['admin\UserController' => 'index']);
    new Route(['GET' => '/admin/users/[id]/read'], ['admin\UserController' => 'read']);
});

Middleware::route(['auth' => 'admin'], function() {

    new Route(['GET' => '/admin/users/create'], ['admin\UserController' => 'create']);
    new Route(['POST' => '/admin/users/create'], ['admin\UserController' => 'store']);
    new Route(['GET' => '/admin/users/[username]/edit'], ['admin\UserController' => 'edit']);
    new Route(['POST' => '/admin/users/[username]/update'], ['admin\UserController' => 'update']);
    new Route(['POST' => '/admin/users/[username]/update-role'], ['admin\UserController' => 'updateRole']);
    new Route(['POST' => '/admin/users/recover'], ['admin\UserController' => 'recover']);
    new Route(['POST' => '/admin/users/delete'], ['admin\UserController' => 'delete']);
});

Middleware::route('login', function() {

    new Route(['GET' => '/admin/css'], ['admin\CssController' => 'index']);
    new Route(['GET' => '/admin/css/[id]/read'], ['admin\CssController' => 'read']);
});

Middleware::route(['auth' => 'admin'], function() {

    new Route(['GET' => '/admin/css/create'], ['admin\CssController' => 'create']);
    new Route(['POST' => '/admin/css/store'], ['admin\CssController' => 'store']);
    new Route(['GET' => '/admin/css/[id]/edit'], ['admin\CssController' => 'edit']);
    new Route(['POST' => '/admin/css/[id]/update'], ['admin\CssController' => 'update']);
    new Route(['POST' => '/admin/css/[id]/link-pages'], ['admin\CssController' => 'linkPages']);
    new Route(['POST' => '/admin/css/[id]/unlink-pages'], ['admin\CssController' => 'unlinkPages']);
    new Route(['POST' => '/admin/css/[id]/link-all'], ['admin\CssController' => 'linkAll']);
    new Route(['POST' => '/admin/css/[id]/unlink-all'], ['admin\CssController' => 'unlinkAll']);
    new Route(['POST' => '/admin/css/recover'], ['admin\CssController' => 'recover']);
    new Route(['POST' => '/admin/css/delete'], ['admin\CssController' => 'delete']);
});

Middleware::route('login', function() {

    new Route(['GET' => '/admin/js'], ['admin\JsController' => 'index']);
    new Route(['GET' => '/admin/js/[id]/read'], ['admin\JsController' => 'read']);
});

Middleware::route(['auth' => 'admin'], function() {

    new Route(['GET' => '/admin/js/create'], ['admin\JsController' => 'create']);
    new Route(['POST' => '/admin/js/store'], ['admin\JsController' => 'store']);
    new Route(['GET' => '/admin/js/[id]/edit'], ['admin\JsController' => 'edit']);
    new Route(['POST' => '/admin/js/[id]/update'], ['admin\JsController' => 'update']);
    new Route(['POST' => '/admin/js/[id]/include-pages'], ['admin\JsController' => 'includePages']);
    new Route(['POST' => '/admin/js/[id]/remove-pages'], ['admin\JsController' => 'removePages']);
    new Route(['POST' => '/admin/js/[id]/include-all'], ['admin\JsController' => 'includeAll']);
    new Route(['POST' => '/admin/js/[id]/remove-all'], ['admin\JsController' => 'removeAll']);
    new Route(['POST' => '/admin/js/recover'], ['admin\JsController' => 'recover']);
    new Route(['POST' => '/admin/js/delete'], ['admin\JsController' => 'delete']);
});

Middleware::route('login', function() {

    new Route(['GET' => '/admin/menus'], ['admin\MenuController' => 'index']);
    new Route(['GET' => '/admin/menus/[id]/read'], ['admin\MenuController' => 'read']);
});

Middleware::route(['auth' => 'admin'], function() {

    new Route(['GET' => '/admin/menus/create'], ['admin\MenuController' => 'create']);
    new Route(['POST' => '/admin/menus/store'], ['admin\MenuController' => 'store']);
    new Route(['GET' => '/admin/menus/[id]/edit'], ['admin\MenuController' => 'edit']);
    new Route(['POST' => '/admin/menus/[id]/update'], ['admin\MenuController' => 'update']);
    new Route(['POST' => '/admin/menus/[id]/update-position'], ['admin\MenuController' => 'updatePosition']);
    new Route(['POST' => '/admin/menus/[id]/update-ordering'], ['admin\MenuController' => 'updateOrdering']);
    new Route(['POST' => '/admin/menus/recover'], ['admin\MenuController' => 'recover']);
    new Route(['POST' => '/admin/menus/delete'], ['admin\MenuController' => 'delete']);
});

Middleware::route('login', function() {

    new Route(['GET' => '/admin/media'], ['admin\MediaController' => 'index']);
    new Route(['GET' => '/admin/media/create'], ['admin\MediaController' => 'create']);
});

Middleware::route(['auth' => 'admin'], function() {

    new Route(['POST' => '/admin/media/create/update-filename'], ['admin\MediaController' => 'UPDATEFILENAME']);
    new Route(['POST' => '/admin/media/create/update-description'], ['admin\MediaController' => 'UPDATEDESCRIPTION']);
    new Route(['POST' => '/admin/media/create'], ['admin\MediaController' => 'store']);
    new Route(['POST' => '/admin/media/update'], ['admin\MediaController' => 'UPDATE']);
    new Route(['POST' => '/admin/media/update-filename'], ['admin\MediaController' => 'UPDATEFILENAME']);
    new Route(['POST' => '/admin/media/update-description'], ['admin\MediaController' => 'UDPATEDESCRIPTION']);
    new Route(['POST' => '/admin/media/delete'], ['admin\MediaController' => 'delete']);
});   

Middleware::route('login', function() {

    new Route(['GET' => '/admin/categories'], ['admin\CategoryController' => 'index']);
    new Route(['GET' => '/admin/categories/showaddable'], ['admin\CategoryController' => 'SHOWADDABLE']);
}); 

Middleware::route(['auth' => 'admin'], function() {

    new Route(['POST' => '/admin/categories/addcategory'], ['admin\CategoryController' => 'ADDCATEGORY']);
    new Route(['POST' => '/admin/categories/addpage'], ['admin\CategoryController' => 'ADDPAGE']);
    new Route(['POST' => '/admin/categories/store'], ['admin\CategoryController' => 'store']);
    new Route(['POST' => '/admin/categories/update'], ['admin\CategoryController' => 'update']);
    new Route(['POST' => '/admin/categories/slug'], ['admin\CategoryController' => 'SLUG']);
    new Route(['POST' => '/admin/categories/delete'], ['admin\CategoryController' => 'delete']);
}); 

Middleware::route('login', function() {

    new Route(['GET' => '/admin/widgets'], ['admin\WidgetController' => 'index']);
    new Route(['GET' => '/admin/widgets/[id]/read'], ['admin\WidgetController' => 'read']);
}); 

Middleware::route(['auth' => 'admin'], function() {

    new Route(['GET' => '/admin/widgets/create'], ['admin\WidgetController' => 'create']);
    new Route(['POST' => '/admin/widgets/store'], ['admin\WidgetController' => 'store']);
    new Route(['GET' => '/admin/widgets/[id]/edit'], ['admin\WidgetController' => 'edit']);
    new Route(['POST' => '/admin/widgets/[id]/update'], ['admin\WidgetController' => 'update']);
    new Route(['POST' => '/admin/widgets/delete'], ['admin\WidgetController' => 'delete']);
    new Route(['POST' => '/admin/widgets/recover'], ['admin\WidgetController' => 'recover']);
});

Middleware::route('login', function() {

    new Route(['GET' => '/admin/cdn'], ['admin\CdnController' => 'index']);
    new Route(['GET' => '/admin/cdn/[id]/read'], ['admin\CdnController' => 'read']);
});

Middleware::route(['auth' => 'admin'], function() {

    new Route(['GET' => '/admin/cdn/create'], ['admin\CdnController' => 'create']);
    new Route(['POST' => '/admin/cdn/store'], ['admin\CdnController' => 'store']);
    new Route(['GET' => '/admin/cdn/[id]/edit'], ['admin\CdnController' => 'edit']);
    new Route(['POST' => '/admin/cdn/[id]/update'], ['admin\CdnController' => 'update']);
    new Route(['POST' => '/admin/cdn/[id]/import-pages'], ['admin\CdnController' => 'importPage']);
    new Route(['POST' => '/admin/cdn/[id]/export-pages'], ['admin\CdnController' => 'exportPage']);
    new Route(['POST' => '/admin/cdn/delete'], ['admin\CdnController' => 'delete']);
    new Route(['POST' => '/admin/cdn/recover'], ['admin\CdnController' => 'recover']);
    new Route(['POST' => '/admin/cdn/[id]/import-all'], ['admin\CdnController' => 'importAll']);
    new Route(['POST' => '/admin/cdn/[id]/export-all'], ['admin\CdnController' => 'exportAll']);
});