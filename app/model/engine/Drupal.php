<?php

require 'Template.php';

/**
 *
 * @version
 *  0.1
 *
 * @author
 *  Alexandre Soyer
 */
class Drupal extends Template
{

    public function __construct()
    {
        parent::__construct();
    }

    public function articlesFind($pdo, $text)
    {
        $statement = $pdo->prepare
        (
            "SELECT ... WHERE table LIKE :text"
        );

        $statement->bindValue(':text', '%' . $text . '%', PDO::PARAM_STR);

        return $statement;
    }

    public function articles($pdo, $index)
    {
        $statement = $pdo->prepare
        (
            'SELECT ... LIMIT :pagination,' . Resources::DATABASE_PAGINATION
        );


        $statement->bindValue(':pagination', (int) $index, PDO::PARAM_INT);

        return $statement;
    }

    public function countArticles($pdo)
    {
        $statement = $pdo->prepare
        (
            'SELECT COUNT(*) ...'
        );

        return $statement;
    }

}