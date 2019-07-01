<?php


namespace Antevenio\DddExample\Infrastructure\Repository;

use Compeek\PDOWrapper\NotConnectedException;
use PDO;

class PdoWrapper extends PDO
{

    /**
     * @var array PDO constructor args
     */
    protected $args;
    /**
     * @var bool whether the client has been connected for the first time
     */
    protected $firstConnected;
    /**
     * @var PDO|null
     */
    protected $pdo;
    /**
     * @var array
     */
    protected $pdoAttributes;

    /**
     * PdoWrapper constructor.
     * TODO: inject Logger and log all the sql statements
     *
     * @param $dsn
     * @param null $username
     * @param null $password
     * @param array|null $options
     * @param bool $lazyConnect
     */
    public function __construct(
        $dsn,
        $username = null,
        $password = null,
        array $options = null,
        $lazyConnect = false
    ) {
        $this->args = array_slice(func_get_args(), 0, 4);
        $this->firstConnected = false;
        $this->pdo = null;
        $this->pdoAttributes = [];
        if (!$lazyConnect) {
            $this->connect();
        }
    }

    /**
     * Determines whether the client is connected to the database
     *
     * @return bool
     */
    public function isConnected()
    {
        return $this->pdo !== null;
    }

    /**
     * Connects to the database
     *
     * To connect to the database, a new PDO object is created and hidden within the wrapper. Any related PDO statements
     * are lazily recreated by the PDO statement wrapper.
     */
    public function connect()
    {
        if ($this->isConnected()) {
            return;
        }
        $this->firstConnected = true;
        $this->pdo = new PDO($this->args[0], $this->args[1], $this->args[2], $this->args[3]);
        foreach ($this->pdoAttributes as $attribute => $value) {
            $this->pdo->setAttribute($attribute, $value);
        }
    }

    /**
     * Disconnects from the database
     *
     * To disconnect from the database, the PDO object and any related PDO statement objects are destroyed so that they
     * are garbage collected, causing the PDO driver to drop the connection.
     */
    public function disconnect()
    {
        if (!$this->isConnected()) {
            return;
        }
        $this->pdo = null;
    }

    /**
     * Disconnects from and reconnects to the database
     */
    public function reconnect()
    {
        $this->disconnect();
        $this->connect();
    }

    /**
     * Requires a connection to the database
     *
     * @throws PdoDisconnectedException
     */
    protected function requireConnection()
    {
        if ($this->isConnected()) {
            return;
        }
        if ($this->firstConnected) {
            throw new PdoDisconnectedException();
        }
        $this->connect();
    }

    public static function getAvailableDrivers()
    {
        return parent::getAvailableDrivers();
    }

    public function errorCode()
    {
        $this->requireConnection();
        return $this->pdo->errorCode();
    }

    public function errorInfo()
    {
        $this->requireConnection();
        return $this->pdo->errorInfo();
    }

    /**
     * @param int $attribute
     * @return mixed
     * @throws NotConnectedException
     */
    public function getAttribute($attribute)
    {
        $this->requireConnection();
        return $this->pdo->getAttribute($attribute);
    }

    /**
     * @param int $attribute
     * @param mixed $value
     * @return bool
     * @throws NotConnectedException
     */
    public function setAttribute($attribute, $value)
    {
        $this->requireConnection();
        $result = $this->pdo->setAttribute($attribute, $value);
        if ($result) {
            $this->pdoAttributes[$attribute] = $value;
        }
        return $result;
    }

    public function inTransaction()
    {
        $this->requireConnection();
        return $this->pdo->inTransaction();
    }

    public function beginTransaction()
    {
        $this->requireConnection();
        return $this->pdo->beginTransaction();
    }

    public function commit()
    {
        $this->requireConnection();
        return $this->pdo->commit();
    }

    public function rollBack()
    {
        $this->requireConnection();
        return $this->pdo->rollBack();
    }

    public function quote($string, $parameter_type = PDO::PARAM_STR)
    {
        $this->requireConnection();
        return $this->pdo->quote($string, $parameter_type);
    }

    public function prepare($statement, $driver_options = null)
    {
        $this->requireConnection();
        return $this->pdo->prepare($statement, is_array($driver_options) ? $driver_options : []);
    }

    public function query($statement)
    {
        $this->requireConnection();
        return $this->pdo->query($statement);
    }

    public function exec($statement)
    {
        $this->requireConnection();
        return $this->pdo->exec($statement);
    }

    public function lastInsertId($name = null)
    {
        $this->requireConnection();
        return $this->pdo->lastInsertId($name);
    }

    public function executeAtomically(callable $function)
    {
        $this->pdo->beginTransaction();
        try {
            $return = call_user_func($function, $this);
            $this->pdo->commit();
            return $return ?: true;
        } catch (\Exception $exception) {
            $this->pdo->rollback();
            throw $exception;
        }
    }
}
