<?php
namespace SingletonPDO;
use \PDO;
class DB implements DBConnectionInterface
{
    static private $DB = null;
    private        $dbh;
    private        $dsn;
    private        $userName;
    private        $password;

    /**
     * DB constructor.
     *
     * @param $dsn
     * @param $userName
     * @param $password
     */
    private function __construct($dsn, $userName, $password)
    {
        $this->dsn      = $dsn;
        $this->userName = $userName;
        $this->password = $password;
        $this->getPdoInstance();
    }

    /**
     * Restrict cloning facility
     * @return bool
     */
    private function __clone()
    {
        return false;
    }

    /**
     * Creates new instance representing a connection to a database
     *
     * @param string $dsn
     * @param string $userName
     * @param string $password
     *
     * @return mixed
     */
    public static function connect($dsn, $userName = '', $password = '')
    {
        if ( ! self::$DB[ $dsn ]) {
            self::$DB[ $dsn ] = new DB($dsn, $userName, $password);
        }

        return self::$DB[ $dsn ];
    }

    /**
     * Completes the current session connection, and creates a new.
     * @return string
     */
    public function reconnect()
    {
        if ($this->dbh) {
            $this->close();
            $this->getPdoInstance();
        } else {
            return 'Not connectiont yet';
        }
    }

    /**
     * Returns the PDO instance.
     * @return PDO
     */
    public function getPdoInstance()
    {
        if ($this->dbh) {
            return $this->dbh;
        } else {
            return $this->dbh = new PDO($this->dsn, $this->userName, $this->password);
        }
    }

    /**
     *  Returns the ID of the last inserted row or sequence value.
     *
     * @param string $sequenceName
     *
     * @return mixed
     */
    public function getLastInsertID($sequenceName = '')
    {
        return $this->dbh->lastInsertId($sequenceName);
    }

    /**
     * Closes the currently active DB connection.
     * It does nothing if the connection is already closed.
     * @return null
     */
    public function close()
    {
        return $this->dbh = null;
    }

    /**
     * Sets an attribute on the database handle.
     * Some of the available generic attributes are listed below;
     * some drivers may make use of additional driver specific attributes.
     *
     * @param int   $attribute
     * @param mixed $value
     *
     * @return mixed
     */
    public function setAttribute($attribute, $value)
    {
        return $this->dbh->setAttribute($attribute, $value);
    }

    /**
     * Returns the value of a database connection attribute.
     *
     * @param int $attribute
     *
     * @return mixed
     */
    public function getAttribute($attribute)
    {
        return $this->dbh->getAttribute($attribute);
    }

    /**
     * when shutdown closes the currently active DB connection
     */
    public function __destruct()
    {
        return $this->close();
    }
}

