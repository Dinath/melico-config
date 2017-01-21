<?php

$container = $app->getContainer();

/**
 * our html files templated using TWIG
 *
 * Add cache and routing extension
 *
 * @param $container
 *  SLIM container
 * @return \Slim\Views\Twig
 *  A twig reference
 */
$container['view'] = function ($container) 
{

    // cache system
    $dir = dirname(__DIR__);
    $view = new \Slim\Views\Twig($dir . '/views', 
    [
        'cache' => Resources::DEBUG ? false : $dir . '/tmp/cache'
    ]);

    // add twig extensions...
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    
    return $view;
};


/**
 * 404 not found page handler
 *
 * @param $c
 * @return Closure
 */
$container['notFoundHandler'] = function ($c) 
{
    return function ($request, $response) use ($c) 
    {
        return $c['view']->render($response->withStatus(404), '404.html');
    };
};

/**
*   Choose the appropriate engine from engine's folder
*
*/
$container['engine'] = function() 
{
    return new WordPress;
};

/**
 *
 * Our database connection
 *
 * @return PDO
 *  A single app instance of the database
 */
$container['pdo'] = function() 
{

    try 
    {
        $pdo = new PDO('mysql:dbname=' . 
                Resources::$json['database']['name'] . ';host=' . 
                Resources::$json['database']['host'] . ';charset=UTF8;', 
                Resources::$json['database']['user'], 
                Resources::$json['database']['pass']
        );
        if (Resources::DEBUG) 
        {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $pdo;
    }
    catch (PDOException $ex) 
    {
        print_r($ex);
        // die("Cannot connect to database. Please verify that the deamon is started and that login informations are correct.");
    }

};

// $container['db'] = function($container) {
//   return new ControllerDB($container->pdo);
// };