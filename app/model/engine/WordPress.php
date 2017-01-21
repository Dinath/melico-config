<?php

require 'Template.php';

/**
 *
 * @version
 *  4.6.1
 *
 * @author
 *  Alexandre Soyer
 */
class WordPress extends Template
{

    public function __construct()
    {
        parent::__construct();
    }

    public function articlesFind($pdo, $text)
    {
        $statement = $pdo->prepare
        (
            "SELECT 
              post_title 
                AS :db_obj_title, 
              post_excerpt 
                AS :db_obj_preview,
              post_name 
                AS :db_obj_url,
              post_content 
                AS :db_obj_content,
              post_date 
                AS :db_obj_date
            FROM " . Resources::$json['database']['table_prefix'] . 'posts' . " 
                AS post 
            WHERE post_type = 'post'
                AND post_status = 'publish' 
                AND post_title LIKE :text 
            ORDER BY post_date DESC"
        );

        $statement->bindValue(':text', '%' . $text . '%', PDO::PARAM_STR);

        return $statement;
    }

    public function articles($pdo, $index)
    {
        $statement = $pdo->prepare
        (
            'SELECT 
              post_title 
                AS :db_obj_title, 
              post_excerpt 
                AS :db_obj_preview,
              post_name 
                AS :db_obj_url,
              post_content 
                AS :db_obj_content,
              post_date 
                AS :db_obj_date
            FROM ' . Resources::$json['database']['table_prefix'] . 'posts' . ' AS post 
            WHERE post_type = "post" AND post_status = "publish" 
            ORDER BY post_date DESC LIMIT :pagination,' . Resources::DATABASE_PAGINATION
        );


        $statement->bindValue(':pagination', (int) $index, PDO::PARAM_INT);

        return $statement;
    }

    public function countArticles($pdo)
    {
        $statement = $pdo->prepare
        (
            'SELECT COUNT(*) as count 
              FROM ' . Resources::$json['database']['table_prefix'] . 'posts' . ' 
              WHERE post_type = "post" AND post_status = "publish"'
        );

        return $statement;
    }

}