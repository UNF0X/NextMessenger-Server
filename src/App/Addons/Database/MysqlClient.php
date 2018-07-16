<?php
namespace Addons\Database;

use php\sql\SqlConnection;
use php\sql\SqlDriverManager;
use php\sql\SqlException;
use php\sql\SqlStatement;

class MysqlClient extends SqlClient
{
    /**
     * @var string
     */
    public $host = 'localhost';

    /**
     * @var int
     */
    public $port = 3306;

    /**
     * @var string
     */
    public $database;

    /**
     * @var string
     */
    public $username = 'root';

    /**
     * @var string
     */
    public $password = '';

    /**
     * @var bool
     */
    public $useCompression = false;

    /**
     * @var bool
     */
    public $useSSL = false;

    /**
     * @var int
     */
    public $connectTimeout = 0;

    /**
     * @var int
     */
    public $socketTimeout = 0;

    /**
     * @var array
     */
    public $options = [];
	
	public function __construct()
	{
		
	}

    /**
     * @return SqlConnection
     */
    protected function buildClient()
    {
        SqlDriverManager::install('mysql');

        if (!$this->host || !$this->port) {
            return null;
        }

        $url = "mysql://{$this->host}:{$this->port}/{$this->database}?zeroDateTimeBehavior=convertToNull&autoReconnect=true&useUnicode=true&characterEncoding=utf-8&useServerPrepStmts=true";

        $this->options['user'] = $this->username;
        $this->options['password'] = $this->password;
        $this->options['socketTimeout'] = $this->socketTimeout;
        $this->options['connectTimeout'] = $this->connectTimeout;
        $this->options['useSSL'] = $this->useSSL ? 'true' : 'false';
        $this->options['useCompression'] = $this->useCompression ? 'true' : 'false';

        return SqlDriverManager::getConnection($url, $this->options);
    }
}