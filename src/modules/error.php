<?php
class Err
{	
	public static function show($code, $message){
		global $res;
		return json_encode(['error'=>['error_code'=>$code, 'error_message'=>$message]]);
	}
}
?>