<?php

set_include_path(dirname(__FILE__)."/../");

require '../vendor/autoload.php';
require '../app/controllers/ControllerEmail.php';
require '../app/utils/Resources.php';

/**
 * Loading the json datas
 */
Resources::init();

/**
 * Display errors if we are in debug mode
 */
$app = new \Slim\App
([
    'settings' =>
        [
            'displayErrorDetails' => Resources::DEBUG
        ]
]);

/**
 * HTTP basic authentification
 */
$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "path" => 
    [
        "/api/post/email",
        "/api/get/articles/find",
        "/api/get/articles/count",
        "/api/get/articles",
        "/api/get/website"
    ],
    "passthrough" => 
    [
        "/index.php",
        "/config"
    ],
    "users" => 
    [
        Resources::$json['auth']['user'] => Resources::$json['auth']['pass']
    ]
]));

/**
 * Contains all shared variables accross the application, instancied one time
 */
require '../app/utils/Container.php';

/**
 * Provides routes for back and api for Android device
 */
require '../app/utils/Routes.php';

/**
 * Only allows get / post
 */
$app->add(function ($req, $res, $next) 
{
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST');
});

$app->run();
