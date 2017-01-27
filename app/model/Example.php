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
abstract class Example extends Template
{

    public function __construct()
    {
        parent::__construct();
    }


    protected $websitePrependURL = "";

    /**
     * @content
     *  All of these fields must be filled thought the AS keyword.
     *
     *  :db_obj_title / the title of the article
     *  :db_obj_preview / the content preview of the article
     *  :db_obj_url / the url which link to the page
     *  :db_obj_content / the content of the article
     *  :db_obj_date / the last modification date of the article
     *
     * @param $pdo
     *  The PDO access to the database
     * @param $text
     *  The text to look for into the title
     * @return
     *  The statement to execute in the database (handled by super class)
     */
    public function articlesFind($pdo, $text)
    {
        $statement = $pdo->prepare
        (
            'SELECT ... FROM ' . Resources::$json['database']['table_prefix'] . '$table' . ' 
            WHERE $column_title LIKE :text'
        );

        $statement->bindValue(':text', '%' . $text . '%', PDO::PARAM_STR);

        return $statement;
    }

    /**
     * @param $pdo
     * @param $index
     * @return mixed
     */
    public function articles($pdo, $index)
    {
        $statement = $pdo->prepare
        (
            'SELECT ... FROM ... LIMIT :pagination,' . Resources::DATABASE_PAGINATION
        );

        $statement->bindValue(':pagination', (int) $index, PDO::PARAM_INT);

        return $statement;
    }

    /**
     * @param $pdo
     * @return mixed
     */
    public function countArticles($pdo)
    {
        $statement = $pdo->prepare
        (
            'SELECT COUNT(*) ... FROM ... LIMIT 1'
        );

        return $statement;
    }

}