<?php

/**
 * Class Template
 *
 * This class is a template that must be implemented into CMS's classes.
 *
 * All functions except "test()" must be implemented.
 *
 * @guidelines
 *
 *  - do not use concatenation in your database query
 *  - already extends from template in your CMS's classes
 *  - use database objects as query variables
 */
class Template {

    protected $databaseObjURL;
    protected $databaseObjContent;
    protected $databaseObjPreview;
    protected $databaseObjDate;
    protected $databaseObjTitle;

    protected function __construct() {
        // create references for pdo binder
        $this->databaseObjContent = Resources::DATABASE_OBJECT_CONTENT;
        $this->databaseObjURL = Resources::DATABASE_OBJECT_URL;
        $this->databaseObjPreview = Resources::DATABASE_OBJECT_PREVIEW;
        $this->databaseObjDate = Resources::DATABASE_OBJECT_DATE;
        $this->databaseObjTitle = Resources::DATABASE_OBJECT_TITLE;
    }

    /**
     *  MANDATORY for articles query
     *
     * Bind all needed attributes to an article query
     *
     * @param $statement
     *  The prepared statement to be executed
     */
    protected function __binderForArticle($statement) {
        $statement->bindValue(':db_obj_url', $this->databaseObjURL, PDO::PARAM_STR);
        $statement->bindValue(':db_obj_title', $this->databaseObjTitle, PDO::PARAM_STR);
        $statement->bindValue(':db_obj_preview', $this->databaseObjPreview, PDO::PARAM_STR);
        $statement->bindValue(':db_obj_content', $this->databaseObjContent, PDO::PARAM_STR);
        $statement->bindValue(':db_obj_date', $this->databaseObjDate, PDO::PARAM_STR);

        return $statement;
    }

    /**
     * Execute a statement in database
     *
     * @param type $statement
     *  A statement from $pdo->prepare()...
     * @return type
     *  The query returns
     */
    protected function __pdoExecuter($statement) {
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     *   This function must be called prior to every other function called
     *
     *   @param $pdo
     *      The pdo access to the database
     */
    protected function __check($pdo) {
        if ($pdo == NULL) {
            die("Cannot connect to database.");
        }
    }

    /**
     *  This function IS NOT implemented in child classes.
     */
    protected function test($pdo) {
        try {
            $this->articlesFind($pdo, "test");
            $this->articles($pdo, 10);
            $this->articlesCount($pdo);
        } catch (Exception $ex) {
            die($ex);
        }
    }

    /**
     * Find articles that contains the given text into the article's title
     *
     * @param $pdo
     *  The pdo access to the database
     * @param $text
     *  The text to look for
     */
    public function findArticlesByContentInTitle($pdo, $text) {
        $this->__check($pdo);

        $statement = $this->articlesFind($pdo, $text);
        $statement = $this->__binderForArticle($statement);

        return $this->__pdoExecuter($statement);
    }

    /**
     * Find articles using pagination.
     *
     * Pagination variable can be defined in the "Resources.php" file
     *
     * @param $pdo
     *  The pdo access to the database
     * @param $index
     *  The index where the pagination should start :
     *
     *  $index = 0 will let the function match 0,10 range
     */
    public function findArticlesUsingPagination($pdo, $index) {
        $this->__check($pdo);

        $statement = $this->articles($pdo, $index);
        $statement = $this->__binderForArticle($statement);

        return $this->__pdoExecuter($statement);
    }

    /**
     * Count all the articles in the database
     *
     * @param $pdo
     *  The pdo access to the database
     */
    public function countArticles($pdo) {
        $this->__check($pdo);

        $statement = $this->articlesCount($pdo);

        return $this->__pdoExecuter($statement);
    }

    /**
     *   User defined function
     *   MUST BE REIMPLEMENTED IN EACH ENGINE (WordPress, Joomla...)
     */
    protected function articlesFind($pdo, $text) {

    }

    protected function articles($pdo, $index) {

    }

    protected function articlesCount($pdo) {

    }

}
