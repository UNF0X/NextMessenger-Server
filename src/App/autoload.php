<?
use php\lib\str;
use php\io\Stream;

function __autoload($className){
	$file = str::replace(__AUTOLOAD__.$className.'.php', '\\', '/');
	if(!Stream::exists($file)){
		print 'Connot find class file in "'.$file.'"';
		exit;
	}else{
		include $file;
	}
}
?>