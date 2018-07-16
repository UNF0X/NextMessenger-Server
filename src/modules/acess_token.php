<?php
use php\time\Time,
php\lib\str,
Addons\Database;
class AccessToken
{
	public static function generate($uid){
		$randString = str::random(30, 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789'.$uid);
		return $randString;
	}
	public static function new($uid){
		$date = Time::now()->toString('dd.MM.yyyy HH:mm:ss');
		$access_token=self::generate($uid);
		Database::query('insert into access_tokens (access_token,date,uid) values(?, ?, ?)', [$access_token, $date, $uid]);
		return $access_token;
	}
}
?>