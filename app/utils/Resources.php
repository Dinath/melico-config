<?php

class Resources
{
    public static $json;

    public static function init()
    {
        $file = file_get_contents(__DIR__ . '/config.json');
        Resources::$json = json_decode($file, true);
    }

    public static function updateJSON() {

        $json = json_encode(Resources::$json);
        
        file_put_contents(__DIR__ . '/config.json', $json);
        $file = file_get_contents(__DIR__ . '/config.json');
        
        Resources::$json = json_decode($file, true);
        // Resources::$json = $json;
    }

    public static function auth($user, $pass) {
        return $user === Resources::$json['auth']['user'] && $pass === Resources::$json['auth']['pass'];
    }

    /**
     * Database
     */
    const DATABASE_PAGINATION = 10;

    const DATABASE_OBJECT_TITLE = "title";
    const DATABASE_OBJECT_PREVIEW = "preview";
    const DATABASE_OBJECT_URL = "url";
    const DATABASE_OBJECT_CONTENT = "content";
    const DATABASE_OBJECT_DATE = "date";

    /**
     * Global
     */
    const DEBUG = true;
    const WEBSITE_ANDROID_URL = 'http://localhost:4200/android';

}