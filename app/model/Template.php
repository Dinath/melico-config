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
class Template
{


    /**
     * @var string
     *  Fill if the url in database must be prefixed with something
     *
     */
    public $websitePrependURL = "";

    /**
     * @var string
     *  The correct names for sql query, used to convert column names to custom names in JSON
     */
    protected $databaseObjURL;
    protected $databaseObjContent;
    protected $databaseObjPreview;
    protected $databaseObjDate;
    protected $databaseObjTitle;
    protected $databaseObjCount;

    /**
     * Template constructor.
     *
     * create references for pdo binders
     */
    protected function __construct()
    {
        $this->databaseObjContent = Resources::DATABASE_OBJECT_CONTENT;
        $this->databaseObjURL = Resources::DATABASE_OBJECT_URL;
        $this->databaseObjPreview = Resources::DATABASE_OBJECT_PREVIEW;
        $this->databaseObjDate = Resources::DATABASE_OBJECT_DATE;
        $this->databaseObjTitle = Resources::DATABASE_OBJECT_TITLE;
        $this->databaseObjCount = Resources::DATABASE_OBJECT_COUNT;
    }

    /**
     *  MANDATORY for articles query
     *
     * Bind all needed attributes to an article query
     *
     * @param $statement
     *  The prepared statement to be executed
     */
    private function __binderForArticle($statement)
    {

        $statement->bindValue(':db_obj_url', $this->databaseObjURL, PDO::PARAM_STR);
        $statement->bindValue(':db_obj_title', $this->databaseObjTitle, PDO::PARAM_STR);
        $statement->bindValue(':db_obj_preview', $this->databaseObjPreview, PDO::PARAM_STR);
        $statement->bindValue(':db_obj_content', $this->databaseObjContent, PDO::PARAM_STR);
        $statement->bindValue(':db_obj_date', $this->databaseObjDate, PDO::PARAM_STR);

        return $statement;
    }

    /**
     * protected for abused mysql query,
     * if no pagination, limit records query to $DATABASE_PAGINATION, default = 10
     *
     * @param $statement
     *  The statement to be prepared for execution in database
     * @return
     *  The modified statement to include LIMIT function
     *
     */
    private function __pdoLimiter($statement, $limit = Resources::DATABASE_PAGINATION)
    {
        if (strpos($statement->queryString, 'LIMIT') === false) {
            $statement->queryString .= " LIMIT " . $limit;
        }

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
    private function __pdoExecuter($statement, $test = false)
    {
        $statement = $this->__pdoLimiter($statement);

        if ($test) 
        {
            $statement->execute();
        }
        else 
        {
            try 
            {
                $statement->execute();
            }
            catch (PDOException $ex)
            {
                return $ex->getMessage();
            }
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     *   This function must be called prior to every other function called
     *
     * @param $pdo
     *      The pdo access to the database
     */
    protected function __check($pdo)
    {
        if ($pdo === NULL) {
            die("Cannot connect to database.");
        }
        else {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }
    }

    /**
     *  This function IS NOT implemented in child classes.
     *
     *  You can use it to test your brand new Engine class
     */
    public function test($pdo)
    {
        try {
            $this->findArticlesByContentInTitle($pdo, "test", true);
            $this->findArticlesUsingPagination($pdo, 10, true);
            $this->countArticles($pdo, true);
        } catch (Exception $ex) {
            die('<h1>An exception happened in your engine\'s class</h1>' . $ex);
        }
    }

    /**
     * Find articles that contains the given text into the article's title
     *
     * @param $pdo
     *  The pdo access to the database
     * @param $text
     *  The text to look for
     * @param
     *  A test to throw exception if append
     */
    public function findArticlesByContentInTitle($pdo, $text, $test = false)
    {
        $pdo = $this->__check($pdo);

        $statement = $this->articlesFind($pdo, $text);
        $statement = $this->__binderForArticle($statement);
        $statement->bindValue(':text', '%' . $text . '%', PDO::PARAM_STR);

        return $this->__pdoExecuter($statement, $test);
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
     * @param
     *  A test to throw exception if append
     *
     *  $index = 0 will let the function match 0,10 range
     */
    public function findArticlesUsingPagination($pdo, $index, $test = false)
    {
        $pdo = $this->__check($pdo);

        $statement = $this->articles($pdo, $index);
        $statement = $this->__binderForArticle($statement);
        $statement->bindValue(':pagination', (int) $index, PDO::PARAM_INT);

        return $this->__pdoExecuter($statement, $test);
    }

    /**
     * Count all the articles in the database
     *
     * @param $pdo
     *  The pdo access to the database
     * @param
     *  A test to throw exception if append
     */
    public function countArticles($pdo, $test = false)
    {
        $pdo = $this->__check($pdo);

        $statement = $this->articlesCount($pdo);
        $statement->bindValue(':db_obj_count', 'count', PDO::PARAM_STR);
        $statement = $this->__pdoLimiter($statement, 1);

        return $this->__pdoExecuter($statement, $test);
    }

    /**
     *   User defined function
     *   MUST BE REIMPLEMENTED IN EACH ENGINE (WordPress, Joomla...)
     */
    protected function articlesFind($pdo, $text) {}
    protected function articles($pdo, $index) {}
    protected function articlesCount($pdo) {}

}
