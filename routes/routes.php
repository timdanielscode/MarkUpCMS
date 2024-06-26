<?php

use core\http\Route;
use database\DB;
use core\http\Middleware;

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

Middleware::route(['login' => false], function() { 

    $settedWebsiteSlug = DB::try()->select('slug')->from('websiteSlug')->first();

    if(!empty($settedWebsiteSlug) && $settedWebsiteSlug !== null) {

        new Route(['GET' => $settedWebsiteSlug[0]], ['LoginController' => 'index']);
        new Route(['POST' => $settedWebsiteSlug[0]], ['LoginController' => 'authenticateUsers']);
    }
});

Middleware::route(['login' => true], function() { 

    new Route(['GET' => '/logout'], ['LogoutController' => 'logout']);
});

Middleware::route(['login' => true], function() { 

    new Route(['GET' => '/admin/dashboard'], ['admin\DashboardController' => 'index']);
    new Route(['GET' => '/admin/profile/[id]'], ['admin\ProfileController' => 'index']);
    new Route(['POST' => '/admin/profile/[id]/update'], ['admin\ProfileController' => 'updateDetails']);
    new Route(['POST' => '/admin/profile/[id]/update-role'], ['admin\ProfileController' => 'updateRole']);
    new Route(['GET' => '/admin/profile/[id]/change-password'], ['admin\ProfileController' => 'editPassword']);
    new Route(['POST' => '/admin/profile/[id]/change-password'], ['admin\ProfileController' => 'updatePassword']);
    new Route(['POST' => '/admin/profile/[id]/delete'], ['admin\ProfileController' => 'delete']);
});

Middleware::route(['auth' => 'admin'], function() { 

    new Route(['GET' => '/admin/settings'], ['admin\SettingsController' => 'index']);
    new Route(['POST' => '/admin/settings/update-slug'], ['admin\SettingsController' => 'updateSlug']);
});

Middleware::route(['login' => true], function() { 

    new Route(['GET' => '/admin/pages'], ['admin\PageController' => 'index']);
    new Route(['GET' => '/admin/pages/[id]/read'], ['admin\PageController' => 'read']);
});

Middleware::route(['auth' => 'admin'], function() { 

    new Route(['GET' => '/admin/pages/create'], ['admin\PageController' => 'create']);
    new Route(['POST' => '/admin/pages/store'], ['admin\PageController' => 'store']);
    new Route(['GET' => '/admin/pages/[id]/edit'], ['admin\PageController' => 'edit']);
    new Route(['POST' => '/admin/pages/[id]/update'], ['admin\PageController' => 'update']);
    new Route(['POST' => '/admin/pages/[id]/assign-category'], ['admin\PageController' => 'assignCategory']);
    new Route(['POST' => '/admin/pages/[id]/detach-category'], ['admin\PageController' => 'detachCategory']);
    new Route(['POST' => '/admin/pages/[id]/update-slug'], ['admin\PageController' => 'updateSlug']);
    new Route(['POST' => '/admin/pages/[id]/update-metadata'], ['admin\PageController' => 'updateMetadata']);
    new Route(['POST' => '/admin/pages/[id]/link-css'], ['admin\PageController' => 'linkCss']);
    new Route(['POST' => '/admin/pages/[id]/unlink-css'], ['admin\PageController' => 'unLinkCss']);
    new Route(['POST' => '/admin/pages/[id]/include-js'], ['admin\PageController' => 'includeJs']);
    new Route(['POST' => '/admin/pages/[id]/remove-js'], ['admin\PageController' => 'removeJs']);
    new Route(['POST' => '/admin/pages/[id]/add-widget'], ['admin\PageController' => 'addWidget']);
    new Route(['POST' => '/admin/pages/[id]/remove-widget'], ['admin\PageController' => 'removeWidget']);
    new Route(['POST' => '/admin/pages/[id]/import-cdns'], ['admin\PageController' => 'importCdns']);
    new Route(['POST' => '/admin/pages/[id]/export-cdns'], ['admin\PageController' => 'exportCdns']);
    new Route(['POST' => '/admin/pages/recover'], ['admin\PageController' => 'recover']);
    new Route(['POST' => '/admin/pages/delete'], ['admin\PageController' => 'delete']);
});

Middleware::route(['login' => true], function() {

    new Route(['GET' => '/admin/users'], ['admin\UserController' => 'index']);
    new Route(['GET' => '/admin/users/[id]/read'], ['admin\UserController' => 'read']);
});

Middleware::route(['auth' => 'admin'], function() {

    new Route(['GET' => '/admin/users/create'], ['admin\UserController' => 'create']);
    new Route(['POST' => '/admin/users/store'], ['admin\UserController' => 'store']);
    new Route(['GET' => '/admin/users/[id]/edit'], ['admin\UserController' => 'edit']);
    new Route(['POST' => '/admin/users/[id]/update'], ['admin\UserController' => 'update']);
    new Route(['POST' => '/admin/users/[id]/update-role'], ['admin\UserController' => 'updateRole']);
    new Route(['POST' => '/admin/users/recover'], ['admin\UserController' => 'recover']);
    new Route(['POST' => '/admin/users/delete'], ['admin\UserController' => 'delete']);
});

Middleware::route(['login' => true], function() {

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

Middleware::route(['login' => true], function() {

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

Middleware::route(['login' => true], function() {

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

Middleware::route(['login' => true], function() {

    new Route(['GET' => '/admin/media'], ['admin\MediaController' => 'index']);
});

Middleware::route(['auth' => 'admin'], function() {
        
    new Route(['POST' => '/admin/media'], ['admin\MediaController' => 'store']);
    new Route(['POST' => '/admin/media/folder'], ['admin\MediaController' => 'folder']);
    new Route(['POST' => '/admin/media/update/filename'], ['admin\MediaController' => 'updateFilename']);
    new Route(['POST' => '/admin/media/update/description'], ['admin\MediaController' => 'updateDescription']);
    new Route(['POST' => '/admin/media/delete'], ['admin\MediaController' => 'delete']);
});   

Middleware::route(['login' => true], function() {

    new Route(['GET' => '/admin/categories/apply'], ['admin\CategoryController' => 'index']);
    new Route(['GET' => '/admin/categories/[id]/apply'], ['admin\CategoryController' => 'index']);
}); 

Middleware::route(['auth' => 'admin'], function() {

    new Route(['POST' => '/admin/categories/addcategory'], ['admin\CategoryController' => 'assignDetachCategories']);
    new Route(['POST' => '/admin/categories/addpage'], ['admin\CategoryController' => 'assignDetachPages']);
    new Route(['POST' => '/admin/categories/[id]/store'], ['admin\CategoryController' => 'store']);
    new Route(['POST' => '/admin/categories/update'], ['admin\CategoryController' => 'update']);
    new Route(['POST' => '/admin/categories/slug'], ['admin\CategoryController' => 'slug']);
    new Route(['POST' => '/admin/categories/delete'], ['admin\CategoryController' => 'delete']);
}); 

Middleware::route(['login' => true], function() {

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

Middleware::route(['login' => true], function() {

    new Route(['GET' => '/admin/metas'], ['admin\MetaController' => 'index']);
    new Route(['GET' => '/admin/metas/[id]/read'], ['admin\MetaController' => 'read']);
});

Middleware::route(['auth' => 'admin'], function() {

    new Route(['GET' => '/admin/metas/create'], ['admin\MetaController' => 'create']);
    new Route(['POST' => '/admin/metas/store'], ['admin\MetaController' => 'store']);
    new Route(['GET' => '/admin/metas/[id]/edit'], ['admin\MetaController' => 'edit']);
    new Route(['POST' => '/admin/metas/[id]/update'], ['admin\MetaController' => 'update']);
    new Route(['POST' => '/admin/metas/[id]/import-pages'], ['admin\MetaController' => 'importPage']);
    new Route(['POST' => '/admin/metas/[id]/export-pages'], ['admin\MetaController' => 'exportPage']);
    new Route(['POST' => '/admin/metas/delete'], ['admin\MetaController' => 'delete']);
    new Route(['POST' => '/admin/metas/recover'], ['admin\MetaController' => 'recover']);
    new Route(['POST' => '/admin/metas/[id]/import-all'], ['admin\MetaController' => 'importAll']);
    new Route(['POST' => '/admin/metas/[id]/export-all'], ['admin\MetaController' => 'exportAll']);
});