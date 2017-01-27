<?php

require 'Template.php';

/**
 *
 * @url
 *  http://www.mediawiki.com/
 *
 * @version
 *  4.6.1
 *
 * @author
 *  Alexandre Soyer
 */
class MediaWiki extends Template
{

    public function __construct()
    {
        parent::__construct();
    }

//SELECT
//`page_id` AS pageid,
//
//(SELECT CONVERT(`old_text` USING utf8)
//FROM  `mw_text`
//WHERE  `old_id` = pageid
//LIMIT 1
//) as content,
//
//(SELECT CONVERT(`rev_timestamp` USING utf8)
//FROM  `mw_revision`
//WHERE  `rev_page` = pageid
//LIMIT 1
//) as date
//
//FROM `mw_page`


    public function articlesFind($pdo, $text)
    {
        $statement = $pdo->prepare
        (
            "SELECT 
              page_title 
                AS :db_obj_title, 
              post_excerpt 
                AS :db_obj_preview,
              post_name 
                AS :db_obj_url,
              FROM " . Resources::$json['database']['table_prefix'] . 'page' . ",
                
              SELECT
                CONVERT(`old_text` USING utf8) AS :db_obj_content,
                CONVERT(`rev_timestamp` USING utf8) AS :db_obj_date 
              FROM  `mw_text` 
                WHERE  `old_id` 
                IN (
                    SELECT  `rev_text_id` 
                    FROM  `mw_revision` 
                    LEFT JOIN  `mw_page` ON  `page_latest` =  `rev_id` 
                )
            "
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


        $statement->bindValue(':pagination', (int)$index, PDO::PARAM_INT);

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