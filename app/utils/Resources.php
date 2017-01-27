<?php

/**
 * Class Resources
 *
 *  Used to store static resources & static functions
 */
class Resources
{
    /**
     * @var
     *  a static variable for json content
     *  used to be pushed into the JSON file
     */
    public static $json;

    /*
     * sync json variable woth json content
     */
    public static function init()
    {
        $file = file_get_contents(__DIR__ . '/config.json');
        Resources::$json = json_decode($file, true);
    }

    /**
     * Update the JSON content from resources variable
     */
    public static function updateJSON()
    {
        $json = json_encode(Resources::$json);
        file_put_contents(__DIR__ . '/config.json', $json);

        $file = file_get_contents(__DIR__ . '/config.json');
        Resources::$json = json_decode($file, true);
    }

    /**
     * Authenfiate the user
     *
     * @param $user
     *  The username to check from JSON
     * @param $pass
     *  The password to check from JSON
     * @return bool
     *  True if the authentification succeed
     *  False if the authentification failed
     */
    public static function auth($user, $pass) {
        return $user === Resources::$json['auth']['user'] && $pass === Resources::$json['auth']['pass'];
    }

    /**
     * Returned a response to the view
     *
     * @param $type
     *  The type of the message :
     *      WS_TWIG_RETURN_TYPE_MSG_SUCCESS
     *      WS_TWIG_RETURN_TYPE_MSG_ERROR
     *  Default is SUCCESS
     * @param $message
     *  The message to display
     *      string : if this is an error
     *      boolean : default is true for SUCCESS
     * @return array
     *  An array interpretad in twig template
     */
    public static function response_view($type = Resources::WS_TWIG_RETURN_TYPE_MSG_SUCCESS, $message = true)
    {
        return array($type => $message);
    }

    /**
     * Twig's constants that are sent back to the view
     */
    // testing
    const WS_TWIG_RETURN_TYPE_MSG_SUCCESS = 'success';
    const WS_TWIG_RETURN_TYPE_MSG_ERROR = 'error';

    // configuration and test
    const WS_TWIG_RETURN_TYPE_POST = 'post';
    const WS_TWIG_RETURN_TYPE_TEST = 'test';
    const WS_TWIG_RETURN_TYPE_TEST_ERROR = 'error';

    // informations
    const WS_TWIG_RETURN_TYPE_STATUS = 'status';
    const WS_TWIG_RETURN_TYPE_STATUS_MESSAGE = 'message';
    const WS_TWIG_RETURN_TYPE_STATUS_CLASS = 'class';

    /**
     * Database
     */
    const DATABASE_PAGINATION = 10;

    /**
     * Database model for JSON
     */
    const DATABASE_OBJECT_TITLE = "title";
    const DATABASE_OBJECT_PREVIEW = "preview";
    const DATABASE_OBJECT_URL = "url";
    const DATABASE_OBJECT_CONTENT = "content";
    const DATABASE_OBJECT_DATE = "date";
    const DATABASE_OBJECT_COUNT = "count";

    /**
     * Global
     */
    const DEBUG = true;

    /**
     * Website
     */
    const WEBSITE_ANDROID_URL = 'http://localhost:4200/android';

}