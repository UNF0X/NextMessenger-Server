<?php
namespace Addons;

use php\sql\SqlException;
use Addons\Database\MysqlClient;
use Addons\Database\SqlClient;
use Addons\Database\EHandler;
use php\lib\arr;
use php\lib\str;

final class Database
{
	private static $dbh, $init = false;
	
	private function __construct(){}
	
	const
		FETCH = 2, // Returns first fetched row.
		FETCH_VALUE = 4, // Returns first fetched row.
		FETCH_ALL = 8,  // Returns all fetched data in array.
		FETCH_ALL_VALUES = 16, // Returns all fetched values (value(-s) names store in argument $sub).
		
		RESULT_BOOL = 32, // Returns true if first fetched row not empty.
		RESULT_OBJECT = 64, // Returns fetched object,
		RESULT_NUM = 128, // Returns fetched data with reseted keys;
		RESULT_INT = 256; // Returns fetched data with reseted keys;
	
	private static function connect()
	{
		self::$dbh = new EHandler();
	}
	
	public static function init()
	{
		if(self::$init === false)
		{
			self::connect();
			return self::$init = true;
		}
		return false;
	}
	
	public static function select($type, $sql, array $input_parameters = [], $sub = null, callable $callback = null)
	{
		if(self::$init === true)
		{
			while(true)
			{
				$sth = self::$dbh->query($sql, $input_parameters);
				if(($type & self::FETCH) == self::FETCH)
				{
					$retry = false;
					while(true)
					{
						try{
							foreach($sth AS $record)
							{
								$data = $record->toArray(); break;
							}
						}catch(SqlException $e)
						{
							if(str::contains($e->getMessage(), 'Illegal operation on empty result set'))
							{
								$data = false;
							}elseif(str::contains($e->getMessage(), 'No operations allowed after statement closed'))
							{
								continue 2;
							}else{
								if(self::$dbh->tryCatch($e, $retry))
								{
									$retry = true;
									continue;
								}else
									var_dump($sql, $data, 'Uncatchable database exception', $e);exit;
							}
						}
						break;
					}
					if(($type & self::RESULT_BOOL) == self::RESULT_BOOL)
					{
						return (bool)$data;
					}elseif(($type & self::RESULT_NUM) == self::RESULT_NUM and is_array($data))
					{
						return array_values($data);
					}elseif(($type & self::RESULT_OBJECT) == self::RESULT_OBJECT and is_array($data))
					{
						return (object)$data;
					}
					return $data;
				}elseif(($type & self::FETCH_VALUE) == self::FETCH_VALUE)
				{
					if($sub != null)
					{
						$retry = false;
						while(true)
						{
							try{
								foreach($sth AS $record)
									break;
								if($record)
									if(is_array($sub))
									{
										$data = [];
										if(($type & self::RESULT_NUM) == self::RESULT_NUM)
											$values = array_values($record->toArray());
										foreach($sub AS $s)
										{
											if(($type & self::RESULT_NUM) == self::RESULT_NUM)
											{
												$data[] = $values[$s];
											}else{
												$data[] = $record->get($s);
											}
										}
									}else{
										$data = (($type & self::RESULT_NUM) == self::RESULT_NUM)?array_values($record->toArray())[$sub]:$record->get($sub);
									}
								else
									$data = false;
							}catch(SqlException $e)
							{
								if($e->getMessage() == 'java.sql.SQLException: Illegal operation on empty result set.')
								{
									$data = false;
								}elseif(str::contains($e->getMessage(), 'No operations allowed after statement closed'))
								{
									continue 2;
								}else{
									if(self::$dbh->tryCatch($e, $retry))
									{
										$retry = true;
										continue;
									}else
										var_dump($sql, $data, 'Uncatchable database exception', $e);exit;
								}
							}
							break;
						}
					}else{
						throw new \Exception('Sub must contain column(-s) name.');
					}
					$result = (($type & self::RESULT_NUM) == self::RESULT_NUM)?(is_array($data)?array_values($data):$data):$data;
					if(($type & self::RESULT_BOOL) == self::RESULT_BOOL)
					{
						return (bool)$result;
					}elseif(($type & self::RESULT_INT) == self::RESULT_INT)
					{
						return (int)$result;
					}
					return $result;
				}elseif(($type & self::FETCH_ALL) == self::FETCH_ALL)
				{
					$result = [];
					$callable = is_callable($callback);
					$retry = false;
					while(true)
					{
						try{
							foreach($sth AS $record)
							{
								if($callback!=null)
								{
									$data = $callback($record->toArray());
									if($data !== false)
									{
										$result[] = (($type & self::RESULT_NUM) == self::RESULT_NUM)?array_values($data):$data;
									}
								}else{
									$result[] = (($type & self::RESULT_NUM) == self::RESULT_NUM)?array_values($record->toArray()):$record->toArray();
								}
							}
						}catch(SqlException $e)
						{
							if($e->getMessage() == 'java.sql.SQLException: Illegal operation on empty result set.')
							{
								$data = false;
							}elseif(str::contains($e->getMessage(), 'No operations allowed after statement closed'))
							{
								continue 2;
							}else{
								if(self::$dbh->tryCatch($e, $retry))
								{
									$retry = true;
									continue;
								}else
									var_dump($sql, $data, 'Uncatchable database exception', $e);exit;
							}
						}
						break;
					}
					if(($type & self::RESULT_BOOL) == self::RESULT_BOOL)
					{
						return (bool)$result;
					}elseif(($type & self::RESULT_OBJECT) == self::RESULT_OBJECT and is_array($result))
					{
						return (object)$result;
					}
					return $result;
				}elseif(($type & self::FETCH_ALL_VALUES) == self::FETCH_ALL_VALUES)
				{
					if($sub != null)
					{
						$result = [];
						$callable = is_callable($callback);
						$retry = false;
						while(true)
						{
							try{
								foreach($sth AS $record)
								{
									if($callable)
									{
										if(is_array($sub))
										{
											$data = [];
											foreach($sub AS $s)
												$data[] = $record->get($sub);
										}else{
											$data = $record->get($sub);
										}
										$data = $callback($data);
										if($data !== false)
										{
											$result[] = (($type & self::RESULT_NUM) == self::RESULT_NUM)?(is_array($data)?array_values($data):$data):$data;
										}
									}else{
										if(is_array($sub))
										{
											$data = [];
											foreach($sub AS $s)
												$data[] = $record->get($sub);
										}else{
											$data = $record->get($sub);
										}
										$result[] = (($type & self::RESULT_NUM) == self::RESULT_NUM)?(is_array($data)?array_values($data):$data):$data;
									}
								}
							}catch(SqlException $e)
							{
								if($e->getMessage() == 'java.sql.SQLException: Illegal operation on empty result set.')
								{
									if($result === null)
										$result = is_array($sub)?[]:'';
								}elseif(str::contains($e->getMessage(), 'No operations allowed after statement closed'))
								{
									continue 2;
								}else{
									if(self::$dbh->tryCatch($e, $retry))
									{
										$retry = true;
										continue;
									}else
										var_dump($sql, $data, 'Uncatchable database exception', $e);exit;
								}
							}
							break;
						}
						if(($type & self::RESULT_BOOL) == self::RESULT_BOOL)
						{
							return (bool)$result;
						}elseif(($type & self::RESULT_OBJECT) == self::RESULT_OBJECT and is_array($result))
						{
							return (object)$result;
						}
						return $result;
					}else{
						throw new \Exception('Sub must contain column(-s) name.');
					}
				}
				return $sth;
			}
		}else{
			throw new Exception('Database class not inited.');
		}
	}
	
	public static function query($sql, $data = [])
	{
		if(self::$init === true)
		{
			if(!is_array($data))
				$data = [$data];
			$retry = false;
			while(true)
			{
				try{
					$sth = self::$dbh->query($sql, $data)->execute();
				}catch(SqlException $e)
				{
					if(self::$dbh->tryCatch($e, $retry))
					{
						$retry = true;
						continue;
					}else
						file_put_contents('error', print_r($sql,1).print_r($data,1).print_r($e,1));
						var_dump($sql, $data, 'Uncatchable database exception', $e);exit;
				}
				break;
			}
			return $sth;
		}else{
			throw new Exception('Database class not inited.');
		}
	}
	
	public static function getLastInsertId()
	{
		return self::$dbh->lastInsertId();
	}
	
	public static function test()
	{
		var_dump('Sleep 2');
		sleep(2);
		var_dump(debug_backtrace());
	}
	
	public function dbh()
	{
		return self::$dbh;
	}
}
?>