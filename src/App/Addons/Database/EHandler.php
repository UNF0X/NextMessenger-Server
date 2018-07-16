<?php
namespace Addons\Database;
use php\sql\SqlException;
use php\util\SharedValue;
use php\lib\str;
use Addons\Logger;
use php\lang\Thread,
	php\lang\ThreadPool;
use php\time\Time;

class EHandler
{
    private $catchPool;
    protected $dbh;
	
	const CONNECTION_ESTABLISHED = 1,
		  TRY_RETRY = 2;
    
    public function __construct()
    {
		$this->catchPool = ThreadPool::createSingle();
		
		$this->connect();
    }

    public function __call($name, array $arguments)
    {
		
		try{
			return call_user_func_array(array($this->dbh, $name), $arguments);
		}catch(SqlException $e) {
			if(!$this->tryCatch($e))
				throw $e;
		}
    }

    public function connect($tryCatch = true)
    {
		if($tryCatch)
			try{
				$this->makeConnection();
			}catch(SqlException $e)
			{
				if($this->tryCatch($e))
					return true;
				throw $e;
			}
		else
			$this->makeConnection();
    }
	
	public function tryCatch(SqlException $e, $retry = false)
	{
		if($this->isConnectionError($e->getMessage()))
		{
			return $this->catchPool->submit(function() use ($e, $retry)
			{
				if(!$retry)
					return true;
				Logger::info('[db] '.if_data($this->dbh instanceof MysqlClient, 'Lost', 'No').' connection');
				$try = 0;
				while(true)
				{
					try{
						$this->connect(false);
						Logger::info('[db] Connected');
						return true;
					}catch(SqlException $e)
					{
						if($this->isConnectionError($e->getMessage()))
						{ //No internet or server not available
							#Logger::info('[db] Connecting #'.++$try);
							Thread::sleep(1000);
							continue;
						}elseif(str::contains($e->getMessage(), 'Too many connections'))
						{ //Too many connections
							#Logger::info('[db] Connecting #'.++$try.' [Too many connections]');
							Thread::sleep(2000);
							continue;
						}else{
							return false;
						}
					}
				}
			})->get();
		}else
			return false;
	}
	
	private function makeConnection()
	{
		if($this->dbh instanceof MysqlClient)
			$this->dbh->free();
		
		$dbh = new MysqlClient;
		
		$dbh->host     = env('DB_HOST');
		$dbh->username = env('DB_USER');
		$dbh->password = env('DB_PASSWORD');
		$dbh->database = env('DB_SCHEMA');
		$dbh->logSql      = false;
		$dbh->catchErrors = true;
		
		$dbh->open();
		
        $this->dbh = $dbh;
	}
	
	private function isConnectionError($msg)
	{
		return (
			str::contains($msg, 'Could not create connection to database server')
			or
			str::contains($msg, 'Communications link failure')
			or
			str::contains($msg, 'com.mysql.jdbc.exceptions.jdbc4.CommunicationsException')
			or
			str::contains($msg, 'Could not retrieve transation read-only status server')
			or
			str::contains($msg, 'Could not create connection to database server')
			or
			str::contains($msg, 'No operations allowed after connection closed')
		);
	}
	
	function __destruct()
	{
		$this->catchPool->shutdown();
	}
}
?>