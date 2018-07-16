<?php
namespace Addons\Database;

use php\sql\SqlConnection,
	php\sql\SqlDriverManager,
	php\sql\SqlException,
	php\sql\SqlStatement;

/**
 * Class SqlClient
 * @package bundle\sql
 */
abstract class SqlClient
{
    /**
     * @var SqlConnection
     */
    private $client;

    /**
     * @var bool
     */
    private $closed = false;

    /**
     * @var bool
     */
    public $autoOpen = true;

    /**
     * @var bool
     */
    public $catchErrors = true;

    /**
     * @var bool
     */
    protected $autoCommit = true;

    /**
     * @var int
     */
    public $transactionIsolation = 1;

    /**
     * @var bool
     */
    public $logSql = false;

    /**
     * @return SqlConnection
     */
    abstract protected function buildClient();


    /**
     * @param $target
     * @return mixed
     */
    protected function applyImpl($target)
    {
        if ($this->autoOpen) {
            $this->open();
        }
    }

    /**
     * Open database.
     */
    public function open()
    {
        if (!$this->isOpened()) {
            try {
                $this->client = $this->buildClient();
                $this->client->transactionIsolation = $this->getTransactionIsolation();

            } catch (SqlException $e) {
                $this->processSqlException($e);
                return;
            }

            $this->closed = false;
        }
    }

    /**
     * @return bool
     */
    public function isOpened()
    {
        return !$this->closed && !!$this->client;
    }

    /**
     * Close connection.
     * @throws SqlException
     */
    public function close()
    {
        $this->closed = true;
		
        if ($this->client) {
            $this->client->close();
        }
    }

    /**
     * @param array $sql
     * @param array $arguments
     * @return SqlStatement
     * @throws SqlException
     */
    public function query($sql, $arguments = [])
    {
        try {
            if ($this->logSql) {
                echo chr(0)."SQL -> $sql, arguments = " . json_encode($arguments)."\n".chr(0);
            }

            if (!$this->client) {
                throw new SqlException("Cannot query(), sql client is not connected.");
            }

            return $this->client->query($sql, $arguments);
        } catch (SqlException $e) {
            $this->processSqlException($e);
        }

        return null;
    }

    protected function processSqlException(SqlException $e) {
        if ($this->catchErrors) {
            echo $e->getMessage() . " at line {$e->getLine()}, {$e->getFile()}";
            return null;
        } else {
            throw $e;
        }
    }

    /**
     * Makes all changes made since the previous
     * commit/rollback permanent and releases any database locks
     * currently held by this Connection object.
     *
     * @throws SqlException
     */
    public function commit()
    {
        $this->client->commit();
    }

    /**
     * Undoes all changes made in the current transaction
     * and releases any database locks currently held
     * by this Connection object.
     *
     * @throws SqlException
     */
    public function rollback()
    {
        $this->client->rollback();
    }

    /**
     * @param string $name
     * @return string
     *
     * @throws SqlException
     */
    public function identifier($name)
    {
        return $this->client->identifier($name);
    }

    /**
     * See SqlConnection::TRANSACTION_* constants.
     *
     * @return int
     */
    public function getTransactionIsolation()
    {
        return $this->transactionIsolation;
    }

    /**
     * @param int $value
     */
    public function setTransactionIsolation($value)
    {
        $this->transactionIsolation = $value;

        if ($this->client) {
            $this->client->transactionIsolation = $value;
        }
    }

    /**
     * @return boolean
     */
    public function isAutoCommit()
    {
        return $this->autoCommit;
    }

    /**
     * @param boolean $autoCommit
     */
    public function setAutoCommit($autoCommit)
    {
        $this->autoCommit = $autoCommit;

        if ($this->client) {
            $this->client->autoCommit = $autoCommit;
        }
    }

    /**
     * @non-getter
     * @return array
     */
    public function getCatalogs()
    {
        return $this->client->getCatalogs();
    }

    /**
     * @non-getter
     * @return array
     */
    public function getMetaData()
    {
        return $this->client->getMetaData();
    }

    /**
     * @non-getter
     * @return array
     */
    public function getSchemas()
    {
        return $this->client->getSchemas();
    }

    public function __destruct()
    {
		var_dump('Destruncting SqlClient');
        if ($this->isOpened()) {
            $this->close();
        }
    }

    public function free()
    {
        if ($this->isOpened()) {
            $this->close();
        }
    }
}