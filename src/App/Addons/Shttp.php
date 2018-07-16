<?php
namespace Addons;
if(!defined('__CP__')) exit;

class Shttp{
	public static function get($url, array $data = []){
		$ch = new jURL($url);
		$ch->setPostData($data);
		return $ch->exec();
	}
	
	public static function post($url, array $data = []){
		$ch = new jURL($url);
		$ch->setRequestMethod('POST');
		$ch->setPostData($data);
		return $ch->exec();
	}
}
?>