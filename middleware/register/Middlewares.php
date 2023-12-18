<?php
/**
 * To register both middleware and 'route' middleware inside the properties
 */

namespace middleware\register;

class Middlewares {

    public $middlewares = [

    ];

    public $routeMiddlewares = [

        "login"     =>      "LoginMiddleware",
        "notLoggedIn"   =>      "NoLoginMiddleware",
        "auth"      =>      "AuthMiddleware",
        "user"  => "UserMiddleware",
        "hasNotDBConn"  => "HasNotDBConnectionMiddleware"
    ];
}

