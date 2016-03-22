<?php
namespace SingletonPDO;
use \PDO;
class DBQuery implements DBQueryInterface
{
    protected $execTime = 0;
    protected $DBConnection;
    /**
     * Create new instance DBQuery.
     *
     * @param DBConnectionInterface $DBConnection
     */
    public function __construct(DBConnectionInterface $DBConnection)
    {
        $this->DBConnection = $DBConnection;
    }

    /**
     * Returns the DBConnection instance.
     *
     * @return DBConnectionInterface
     */
    public function getDBConnection()
    {
        return $this->DBConnection;
    }
    /**
     * Returns the PDO instance.
     *
     * @return PDO
     */
    public function getPDO()
    {
        return $this->DBConnection->getPdoInstance();
    }

    /**
     * Change DBConnection.
     *
     * @param DBConnectionInterface $DBConnection
     *
     * @return void
     */
    public function setDBConnection(DBConnectionInterface $DBConnection)
    {
        $this->DBConnection = $DBConnection;
    }

    /**
     * Executes the SQL statement and returns query result.
     *
     * @param string $query  sql query
     * @param array  $params input parameters (name=>value) for the SQL execution
     *
     * @return mixed if successful, returns a PDOStatement on error false
     */
    public function query($query, $params = null)
    {
        try {
            $stmt  = $this->getPDO()->prepare($query);
            $start = microtime(true);
            $stmt->execute($params);
            $finish          = microtime(true);
            $this->execTime = ($finish - $start);

            return $stmt->fetch();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Executes the SQL statement and returns all rows of a result set as an associative array
     *
     * @param string $query  sql query
     * @param array  $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryAll($query, array $params = null)
    {
        $stmt  = $this->getPDO()->prepare($query);
        $start = microtime(true);
        $stmt->execute($params);
        $finish          = microtime(true);
        $this->execTime = ($finish - $start);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Executes the SQL statement returns the first row of the query result
     *
     * @param string $query  sql query
     * @param array  $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryRow($query, array $params = null)
    {
        $stmt  = $this->getPDO()->prepare($query);
        $start = microtime(true);
        $stmt->execute($params);
        $finish          = microtime(true);
        $this->execTime = ($finish - $start);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Executes the SQL statement and returns the first column of the query result.
     *
     * @param string $query  sql query
     * @param array  $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryColumn($query, array $params = null)
    {
        $stmt  = $this->getPDO()->prepare($query);
        $start = microtime(true);
        $stmt->execute($params);
        $finish          = microtime(true);
        $this->execTime = ($finish - $start);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Executes the SQL statement and returns the first field of the first row of the result.
     *
     * @param string $query  sql query
     * @param array  $params input parameters (name=>value) for the SQL execution
     *
     * @return mixed  column value
     */
    public function queryScalar($query, array $params = null)
    {
        $stmt  = $this->getPDO()->prepare($query);
        $start = microtime(true);
        $stmt->execute($params);
        $finish          = microtime(true);
        $this->execTime = ($finish - $start);

        return $stmt->fetchColumn(0);
    }

    /**
     * Executes the SQL statement.
     * This method is meant only for executing non-query SQL statement.
     * No result set will be returned.
     *
     * @param string $query  sql query
     * @param array  $params input parameters (name=>value) for the SQL execution
     *
     * @return integer number of rows affected by the execution.
     */
    public function execute($query, array $params = null)
    {
        $stmt  = $this->getPDO()->prepare($query);
        $start = microtime(true);
        $stmt->execute($params);
        $finish          = microtime(true);
        $this->execTime = ($finish - $start);

        return $stmt->rowCount();
    }

    /**
     * Returns the last query execution time in seconds
     *
     * @return float query time in seconds
     */
    public function getLastQueryTime()
    {
        return $this->execTime;
    }
}
