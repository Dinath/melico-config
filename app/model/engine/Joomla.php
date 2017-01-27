<?php

require_once __DIR__ . '/../Template.php';

/**
 *
 * @url
 *  https://www.joomla.org
 *
 * @version
 *  4.6.1
 *
 * @author
 *  Alexandre Soyer
 */
class Joomla extends Template
{

    public $websitePrependURL = "index.php/";

    public function __construct()
    {
        parent::__construct();
    }

    public function articles($pdo, $index)
    {
        $statement = $pdo->prepare
        (
            'SELECT 
              `title` 
                AS :db_obj_title, 
              `introtext` 
                AS :db_obj_preview,
              `alias` 
                AS :db_obj_url,
              `fulltext` 
                AS :db_obj_content,
              `publish_up` 
              AS :db_obj_date
            FROM ' . Resources::$json['database']['table_prefix'] . 'content' . '
            WHERE `state` = 1
            ORDER BY `publish_up` DESC LIMIT :pagination,' . Resources::DATABASE_PAGINATION
        );

        return $statement;
    }

    public function articlesFind($pdo, $text)
    {
        $statement = $pdo->prepare
        (
            'SELECT 
              `title` 
                AS :db_obj_title, 
              `introtext` 
                AS :db_obj_preview,
              `alias` 
                AS :db_obj_url,
              `fulltext` 
                AS :db_obj_content,
              `publish_up` 
              AS :db_obj_date
            FROM ' . Resources::$json['database']['table_prefix'] . 'content' . '
            WHERE `state` = 1 and `title` LIKE :text 
            ORDER BY `publish_up` DESC'
        );

        return $statement;
    }

    public function articlesCount($pdo)
    {
        $statement = $pdo->prepare
        (
            'SELECT COUNT(*) AS :db_obj_count 
            FROM ' . Resources::$json['database']['table_prefix'] . 'content' . '
            WHERE `state` = 1'
        );

        return $statement;
    }

}