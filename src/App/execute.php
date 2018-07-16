<?php
error_reporting(E_ALL ^ E_NOTICE);
return;
register_shutdown_function(function()
{
	$err = error_get_last();
	if($err)
		var_dump($err, debug_backtrace());
	return false;
});


/* */
set_exception_handler(function($e) 
{
	var_dump($e, debug_backtrace());
	return false;
});
/* */



$error_handler = function($errno, $errstr, $errline, $errcontext) 
{
	var_dump('Error #'.$errno.' Message: "'.$errstr.'" On line: '.$errline.' Context: '.$errcontext);
};
set_error_handler($error_handler);
?>