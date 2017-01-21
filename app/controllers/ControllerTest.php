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
     * @param $email
     * @param $website
     * @return array
     */
    public static function all($pdo, $email, $website)
    {
        return array(
            'db' => ControllerTest::database($pdo),
            'email' => ControllerTest::email($email),
            'website' => ControllerTest::website($website)
        );
    }

    /**
     * @param $pdo
     * @return array
     */
    private static function database($pdo)
    {

        try {
            new PDO('mysql:dbname=' .
                Resources::$json['database']['name'] . ';host=' .
                Resources::$json['database']['host'] . ';charset=UTF8;',
                Resources::$json['database']['user'],
                Resources::$json['database']['pass']
            );
        } catch (PDOException $ex) {
            return (
            array('message' => "Impossible de se connecter à la base de données. " . $ex)
            );
        }

        return array('success' => true);
    }

    /**
     * @return array
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

        if ($controllerEmail->send() === '') {
            return array('success' => true);
        } else {
            return (
            array('message' => "Impossible d'envoyer l'email de test.")
            );
        }
    }

    /**
     * @param $website
     * @return array
     */
    private static function website($website)
    {
        if (ControllerTest::__urlExists($website['url'])) {
            if (strpos($website, '"') === true) {
                return (
                array('message' => "L'URL ne doit pas contenir de guillements (\").")
                );
            }
            return array('success' => true);
        } else {
            return (
            array('message' => "L'URL renseignée n'est pas bonne.")
            );
        }
    }

    /**
     * @param null $url
     * @return bool
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