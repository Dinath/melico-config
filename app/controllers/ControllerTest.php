<?php

/**
 * Class ControllerTest
 *
 * Allows the backend to check for valid informations and then, allows to download the Android app
 */
class ControllerTest
{

    /**
     * @param $pdo
     *  The pdo params from view
     * @param $email
     *  The email params from view
     * @param $website
     *  The website params from view
     * @return array
     *  An array of response for twig
     */
    public static function all($pdo, $email, $website)
    {
        return array(
            'db' => ControllerTest::database($pdo),
            'email' => ControllerTest::email($email),
            'website' => ControllerTest::website($website),
        );
    }

    /**
     * @param $pdo
     *  The PDO access to the database
     * @return array
     *  A response for twig, see Resources::response_view
     */
    private static function database($pdo)
    {
        try
        {
            new PDO('mysql:dbname=' .
                Resources::$json['database']['name'] . ';host=' .
                Resources::$json['database']['host'] . ';charset=UTF8;',
                Resources::$json['database']['user'],
                Resources::$json['database']['pass']
            );
        }
        catch (PDOException $ex)
        {
            return Resources::response_view(
                Resources::WS_TWIG_RETURN_TYPE_TEST_ERROR,
                "Impossible de se connecter à la base de données."
            );
        }

        return Resources::response_view();
    }

    /**
     * Test the email sending
     *
     * @return array
     *  A response for twig, see Resources::response_view
     */
    private static function email()
    {

        $controllerEmail = new ControllerEmail();

        $email = [];
        $email['subject'] = "Message de test";
        $email['from'] = "no-reply@meli.co";
        $email['from-name'] = "Meli.co";
        $email['content'] = "Bonjour,<br /><br />Ceci est un message de test d'envoi d'e-mail.";

        $controllerEmail->init($email);

        // send the email and check if it is sent
        if ($controllerEmail->send() === '')
        {
            return Resources::response_view();
        }
        else
        {
            return Resources::response_view(Resources::WS_TWIG_RETURN_TYPE_TEST_ERROR, "Impossible d'envoyer l'email de test.");
        }
    }

    /**
     * @param $website
     *  Test the website to be ok for
     *      URL
     * @return array
     *  A response for twig, see Resources::response_view
     */
    private static function website($website)
    {
        if (ControllerTest::__urlExists($website['url']))
        {
            if (strpos($website['url'], '"') === true)
            {
                return Resources::response_view(Resources::WS_TWIG_RETURN_TYPE_TEST_ERROR, "L'URL ne doit pas contenir de guillements (\").");
            }
            return Resources::response_view();
        }

        return Resources::response_view(Resources::WS_TWIG_RETURN_TYPE_TEST_ERROR, "L'URL renseignée n'est pas bonne.");
    }

    /**
     * @param null $url
     *  The url to check, by default it is NULL
     * @return bool
     *  true if the address is ok
     *  false if we cannot reach it
     */
    private static function __urlExists($url = NULL)
    {
        if ($url == NULL) return false;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode >= 200 && $httpcode < 300) {
            return true;
        } else {
            return false;
        }
    }
}