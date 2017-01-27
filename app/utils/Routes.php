<?php

require "../app/controllers/ControllerTest.php";


require '../app/model/engine/WordPress.php';
require '../app/model/engine/Joomla.php';

/**
 * Redirect to the home page
 */
$app->get('/', function ($request, $response) {
    return $this->view->render($response, 'index.html',
        [
            Resources::WS_TWIG_RETURN_TYPE_STATUS =>
                empty(Resources::$json["auth"]["user"]) ||
                empty(Resources::$json["auth"]["pass"]) ?
                    array('register' => 'true') :
                    array('' => '')
        ]);
});


/**
 * Authentification provider
 */
$app->post('/', function ($request, $response) {
    // auth success
    if (Resources::auth($request->getParams()['auth-user'], $request->getParams()['auth-pass'])) {
        return $this->view->render($response, 'index.html',
            [
                Resources::WS_TWIG_RETURN_TYPE_POST => Resources::$json
            ]);
    } // auth failed
    else {
        return $this->view->render($response, 'index.html',
            [
                Resources::WS_TWIG_RETURN_TYPE_STATUS =>
                    array(
                        Resources::WS_TWIG_RETURN_TYPE_STATUS_MESSAGE
                        => "Vos identifiants sont incorrects.",
                        Resources::WS_TWIG_RETURN_TYPE_STATUS_CLASS
                        => "error"
                    )
            ]);
    }
});

/**
 *  Redirect the user to its personnal Android app download page
 */
$app->post('/android', function ($request, $response) {

    // check for correct user / pass
    if (Resources::auth($request->getParams()['auth']['user'], $request->getParams()['auth']['pass'])) {

        // construct the new url with encoded url strings to melico website
        $url = ';user=' . urlencode(Resources::$json['auth']['user']);
        $url .= ';pass=' . urlencode(Resources::$json['auth']['pass']);
        $url .= ';url=' . urlencode(Resources::$json['website']['url']);
        $url .= ';website=' . urlencode(Resources::$json['website']['name']);
        $url .= ';email=' . urlencode(Resources::$json['email']['contact']);

        // redirect to melico
        return $response->withStatus(302)->withHeader('Location', Resources::WEBSITE_ANDROID_URL . $url);
    }

});


/**
 *  user registration for the first time
 */
$app->post('/signin', function ($request, $response) {

    // get current login / pass
    $user = Resources::$json['auth']['user'];
    $pass = Resources::$json['auth']['pass'];

    // check if for quotes
    if (strpos($user, '"') !== false || strpos($pass, '"') !== false) {
        return $this->view->render($response, 'index.html',
            [
                Resources::WS_TWIG_RETURN_TYPE_STATUS
                => array(
                    Resources::WS_TWIG_RETURN_TYPE_STATUS_MESSAGE
                    => 'Vos informations ne doivent pas contenir de guillements (").',
                    Resources::WS_TWIG_RETURN_TYPE_STATUS_CLASS
                    => "error"
                )
            ]
        );
    }
    // avoiding password reset
    if (empty($user) && empty($pass)) {
        $nUser = $request->getParams()['auth-user'];
        $nPass = $request->getParams()['auth-pass'];
        $nPass2 = $request->getParams()['auth-pass2'];

        // check the two given passwords
        if ($nPass === $nPass2) {
            Resources::$json['auth']['user'] = $nUser;
            Resources::$json['auth']['pass'] = $nPass;
            Resources::updateJSON();
        } // passwords provided are not equals
        else {
            return $this->view->render($response, 'index.html',
                [
                    Resources::WS_TWIG_RETURN_TYPE_STATUS
                    => array(
                        Resources::WS_TWIG_RETURN_TYPE_STATUS_MESSAGE
                        => "Vos deux mots de passes doivent être identiques.",
                        Resources::WS_TWIG_RETURN_TYPE_STATUS_CLASS
                        => "error"
                    )
                ]
            );
        }

        // sucessfully registration
        return $this->view->render($response, 'index.html',
            [
                Resources::WS_TWIG_RETURN_TYPE_STATUS
                => array(
                    'auth'
                    => 'success'
                )
            ]
        );
    }
}

);


/**
 *  POST the new configuration
 */
$app->post('/config', function ($request, $response)
{
    // authentification success
    if (Resources::auth($request->getParams()['auth']['user'], $request->getParams()['auth']['pass']))
    {
        // merging json file
        $json = array_merge(Resources::$json, $request->getParams());
        Resources::$json = $json;
        Resources::updateJSON();

        // testing all block
        return $this->view->render($response, 'index.html',
            [
                Resources::WS_TWIG_RETURN_TYPE_POST
                => Resources::$json,
                Resources::WS_TWIG_RETURN_TYPE_TEST
                => ControllerTest::all
                (
                    $this->pdo,
                    Resources::$json['email'],
                    Resources::$json['website']
                )
            ]
        );
    }
    // if this does not match, simply render the index auth form
    return $this->view->render($response, 'index.html');
});


/**
 *  Send an simple email :)
 */
$app->post('/api/post/email', function ($request, $response) {
    // get datas
    $data = $request->getParsedBody();

    // place the email into a new object
    $email = [];
    $email['subject'] = filter_var($data['subject'], FILTER_SANITIZE_STRING);
    $email['from'] = filter_var($data['from'], FILTER_SANITIZE_STRING);
    $email['from-name'] = filter_var($data['from-name'], FILTER_SANITIZE_STRING);
    $email['content'] = filter_var($data['content'], FILTER_SANITIZE_STRING);

    // email controller loading
    $controllerEmail = new ControllerEmail();
    $controllerEmail->init($email);

    // email sender
    $sent = $controllerEmail->send();

    // the email is gone
    if ($sent === '') {
        return $response->withStatus(200);
    }

    // there were an error, display it on front
    return $response->withStatus(500)->write(json_encode($sent));
});


/**
 *  get the website informations
 */
$app->get("/api/get/website", function ($request, $response) {
    return $response->withStatus(200)->write(json_encode
    (
        Resources::$json['website']
    ));
});


/**
 *  find articles using text in titles
 */
$app->get("/api/get/articles/find/{text}", function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $text = $pagination = $args['text'];

    if (strlen($text) < Resources::$json['ws']['min-param-length']) {
        return $response->withStatus(500)->write(
            ("Your query must contains at least " . Resources::$json['ws']['min-param-length'] . ' caracters.')
        );
    }
    $elements = $this->engine->findArticlesByContentInTitle($this->pdo, $text);

    return $response->withStatus(200)->write(json_encode($elements));
}

);

/**
 *  return the number of all articles in database
 */
$app->get("/api/get/articles/count", function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $elements = $this->engine->countArticles($this->pdo);
    return $response->withStatus(200)->write(json_encode($elements));
}

);

/**
 *  find 10 articles, use pagination to browse them
 */
$app->get("/api/get/articles/{pagination}", function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {

    $pagination = $args['pagination'];

    // checking for bad param
    if (!is_numeric($pagination)) {
        return $response->withStatus(500)->write(("Not Allowed. Use a numeric value."));
    }
    if ($pagination % 10 != 0) {
        return $response->withStatus(500)->write(("Not Allowed. Use a 10 multiple."));
    }

    $elements = $this->engine->findArticlesUsingPagination($this->pdo, $pagination);

    // concatenate website url from resources in the query
    for ($i = 0; $i < count($elements); $i++) {
        $elements[$i]['url'] = Resources::$json['website']['url'] . "/" . $this->engine->websitePrependURL . $elements[$i]['url'];
    }
    return $response->withStatus(200)->write(json_encode($elements));
}

);