<?php
use php\lib\fs,
	php\lib\str;
final class App
{
	protected static $_instance = null, $data;
	
	private function __construct(){}
	
	public static function start()
	{
		if(!self::$_instance instanceof self)
		{
			self::$_instance = new self();
			
			define('__CP__', true, true); #Запущено ли приложение.
			define('__ROOT__', 'res://'); #Корень приложения.
			define('__SRC__', __ROOT__, true); #Корень приложения.
			define('__APP__', 'res://App/', true); #Папка с основными файлами приложения.
			define('__AUTOLOAD__', __APP__, true); #Главная папка автозагрузки.
			define('__ADDONS__', __APP__.'Addons/', true); #Папка с аддонами.
			
			//if(!is_dir(__TEMP__)) mkdir(__TEMP__, 0777, true);
			
			include __APP__.'Addons/preg.php';
			include __APP__.'autoload.php';
			include __APP__.'functions.php';
			include __APP__.'execute.php';
			//include __ROOT__.'jurl.php';
			include __ROOT__.'main.php';
			

			
			//Daemon::start();
			
			return true;
		}else{
			return false;
		}
	}
	
	public static function data($name, $value=null)
	{
		if($value !== null)
		{
			self::$data[$name] = $value;
		}
		return self::$data[$name];
	}
	
	private function __wakeup(){}
	
	private function __clone(){}
}
App::start();
?>