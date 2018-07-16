<?

use php\lib\str,
	php\time\Time,
	php\lang\Thread,
	php\format\JsonProcessor,
	php\lang\Process,
	Addons\Preg;

function declofnum($number, $titles){
    $cases = [2, 0, 1, 1, 1, 2];
    return $number." ".$titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
}
function regex($wrd, $msg){
    global $params;
    if(preg_match_all($wrd, $msg, $matches, PREG_SET_ORDER)){
        $params = $matches[0];
        return $params;
    }
}
/*function json_decode($string,$objective=false){
	$json = new JsonProcessor(JsonProcessor::DESERIALIZE_AS_ARRAYS);

	return $json->parse($string);

}*/
function yesno($expression)
{
    return($expression ? 'Yes' : 'No');
}

function genLetters($length){
	$letters = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";
	while($length--)
		$code .= $letters{rand(0,strlen($letters)-1)};
	return $code;
}

function page($array, $inPage){ //Разделяет массив на страницы
	$pages = array();
	for($i=0;$i<ceil(count($array)/$inPage);$i++){
			$pages[] = array_slice($array, $i*$inPage, $inPage);
	}
	return $pages;
}

function time()
{
	return round(Time::seconds());
}

function http_build_query($a,$b='',$c=0){
	if (!is_array($a)) return $a;
	foreach ($a as $k=>$v){
		if($c){
			if( is_numeric($k) ){
				$k=$b."[]";
			}
			else{
				$k=$b."[$k]";
			}
		}
		else{   
			if (is_int($k)){
				$k=$b.$k;
			}
		}
		if (is_array($v)||is_object($v)){
			$r[] = http_build_query($v,$k,1);
				continue;
		}
		$r[] = urlencode($k) . "=" . urlencode($v);
	}
	return implode("&",$r);
}

function parse_str($str) {
	$arr = array();
	$pairs = explode('&', $str);
	foreach ($pairs as $i) {
	  list($name,$value) = explode('=', $i, 2);

	  if( isset($arr[$name]) ) {
		if( is_array($arr[$name]) ) {
		  $arr[$name][] = urldecode($value);
		}
		else {
		  $arr[$name] = array($arr[$name], $value);
		}
	  }
	  else {
		$arr[$name] = urldecode($value);
	  }
	}
	return $arr;
}
function wait($millis)
{
	return Thread::sleep($millis);
}

function php_similar_char($txt1, $len1, $txt2, $len2)
{
	$pos1 = 0;
	$pos2 = 0;

	$max = 0;
	for ($p = 0; $p < $len1; $p++) {
		for ($q = 0; $q < $len2; $q++) {
			for ($l = 0; ($p + $l < $len1) && ($q + $l < $len2) && ($txt1[$p + $l] == $txt2[$q + $l]); $l++);
			if ($l > $max) {
				$max = $l;
				$pos1 = $p;
				$pos2 = $q;
			}
		}
	}

	if (($sum = $max)) {
		if ($pos1 && $pos2)
			$sum += php_similar_char($txt1, $pos1, $txt2, $pos2);
		if (($pos1 + $max < $len1) && ($pos2 + $max < $len2)) {
			$sum += php_similar_char(substr($txt1, $pos1 + $max), $len1 - $pos1 - $max,
			substr($txt2, $pos2 + $max), $len2 - $pos2 - $max);
		}
	}
	return $sum;
}

function text_similar($first, $second, &$percent = null)
{
	$sim = php_similar_char($first, strlen($first), $second, strlen($second));
	$percent = (float)($sim * 200.0 / (strlen($first) + strlen($second)));
	return (int)$sim;
}

function array_last($array) {
    if (count($array) < 1)
        return null;

    $keys = array_keys($array);
    return $array[$keys[sizeof($keys) - 1]];
}

function str_replace_once($search, $replace, $text) 
{
	$pos = str::pos($text, $search);
	return $pos!==false ? substr_replace($text, $replace, $pos, str::length($search)) : $text; 
}

function parse_between($str, $first, $second)
{
	$pos1 = str::pos($str, $first);
	$pos2 = str::pos($str, $second, $pos1);
	return str::sub($str, $pos1+str::length($first), $pos2);
}

function parse_between_count($str, $first, $second, $count = 1)
{
	$start = 0;
	$data = [];
	
	for($i=0;$i<$count;$i++)
	{
		$pos1 = str::pos($str, $first, $start);
		$pos2 = str::pos($str, $second, $pos1);
		$pos3 = $pos1+str::length($first);
		$data[] = str::sub($str, $pos3, $pos2);
		$start += $pos3;
	}
	return $data;
}

function add_msg($msg, &$var, $separator = '<hr>'){
	$var = (strlen($var)==0)?$msg:$var.$separator.$msg;
}

function chanse($list){ //[[50, 'Trash'], [1, 'Ultimate']] - 50% Trash, 1% - Ultimate
	$sum = 0;
	foreach($list AS $chanse){
		$random[] = [$sum, ($sum+($chanse[0]*100)), $chanse[1]];
		$sum += $chanse[0]*100;
	}
	$number = mt_rand(0, $sum);
	foreach($random AS $rand){
		if($number >= $rand[0] and $number <= $rand[1]){
			return $rand[2];
			break;
		}
	}
}

function ctime($name){
	if(isset($GLOBALS['microtime'][$name])){
		$start = $GLOBALS['microtime'][$name];
		unset($GLOBALS['microtime'][$name]);
		return '('.round(microtime(true) - $start, 5)." sec)";
	}else{
		$GLOBALS['microtime'][$name] = microtime(true);
	}
}

function is_int2($var){ //Проверяет состоит ли текст только из чисел
	return (bool)!strcmp((int)$var,$var);
}

function if_data($condition, $r_1 = null, $r_2 = null)
{
	return $condition ? $r_1 : $r_2;
}

function if_true($condition, $r_1 = null, $r_2 = null)
{
	return $condition ? $r_1 : $r_2;
}

function if_false($condition, $r_1 = null, $r_2 = null)
{
	return !$condition ? $r_1 : $r_2;
}

function in_range($val, $min, $max)
{
	return ($val >= $min && $val <= $max);
}

function env($name, $set=null, $clear = false)
{
	static $data;
	if($set !== null)
	{
		$data[$name] = $set;
		return;
	}elseif($clear === true)
	{
		unset($data[$name]);
	}
	if($data === null AND $name !== null)
	{
		$contents = file_get_contents(__APP__.'.env');
		foreach(explode("\n", $contents) AS $content)
		{
			if(strlen($content) > 0 and $content[0] !== '#')
			{
				list($key, $value) = explode('=', $content, 2);
				$data[$key] = trim($value);
			}
		}
	}
	return $data[$name];
}

function time_difference($time, $params='ymdhis')
{
	$YEAR   = 31536000;
	$MONTH  = 2592000;
	$WEEK   = 604800;
	$DAY    = 86400;
	$HOUR   = 3600;
	$MINUTE = 60;
	$result = [];
	
	if($time < 0)
	{
		$result['invert'] = false;
	}else{
		$result['invert'] = true;
	}
	
	$time = abs($time);
	
	if(strpos($params, 'y') !== false)
	{
		$result['y'] = floor( $time / $YEAR );
		$time = $time - ( $result['y'] * $YEAR );
	}
	
	if(strpos($params, 'm') !== false)
	{
		$result['m'] = floor( $time / $MONTH );
		$time = $time - ( $result['m'] * $MONTH );
	}
	
	if(strpos($params, 'w') !== false)
	{
		$result['w'] = floor( $time / $WEEK );
		$time = $time - ( $result['w'] * $WEEK );
	}
		
	if(strpos($params, 'd') !== false)
	{
		$result['d'] = floor( $time / $DAY );
		$time = $time - ( $result['d'] * $DAY );
	}
	
	if(strpos($params, 'h') !== false)
	{
		$result['h'] = floor( $time / $HOUR );
		$time = $time - ( $result['h'] * $HOUR );
	}
	
	if(strpos($params, 'i') !== false)
	{
		$result['i'] = floor( $time / $MINUTE );
		$time = $time - ( $result['i'] * $MINUTE );
	}
	
	if(strpos($params, 's') !== false)
	{
		$result['s'] = $time;
	}
	
	return $result;
}

function time_difference_string($time, $params='ymdhis'){
	$diff = time_difference($time, $params);

	foreach($diff AS $key => $value)
	{
		switch($key){
			case 's':
				if(!$value) break;
				$add[] = declofnum($value, ['секунда', 'секунды', 'секунд']);
				break;
			case 'i':
				if(!$value) break;
				$add[] = declofnum($value, ['минута', 'минуты', 'минут']);
				break;
			case 'h':
				if(!$value) break;
				$add[] = declofnum($value, ['час', 'часа', 'часов']);
				break;
			case 'd':
				if(!$value) break;
				$add[] = declofnum($value, ['день', 'дня', 'дней']);
				break;
			case 'w':
				if(!$value) break;
				$add[] = declofnum($value, ['неделя', 'недели', 'недель']);
				break;
			case 'm':
				if(!$value) break;
				$add[] = declofnum($value, ['месяц', 'месяца', 'месяцев']);
				break;
			case 'y':
				if(!$value) break;
				$add[] = declofnum($value, ['год', 'года', 'лет']);
				break;
		}
	}
	$text = '';
	$count = count($add);
	for($i=0;$i<$count;$i++)
	{
		if($i+2==$count)
		{
			$text .= $add[$i].' и ';
		}elseif($i+1==$count)
		{
			$text .= $add[$i];
		}else{
			$text .= $add[$i].', ';
		}
	}
	return $text;
}

function strtosec($string, $addTime = false){
	$YEAR   = 31536000;
	$MONTH  = 2592000;
	$WEEK   = 604800;
	$DAY    = 86400;
	$HOUR   = 3600;
	$MINUTE = 60;
	
	$result = 0;
	
	$number = 0;
	$symb = null;
	foreach(explode(' ', $string) AS $str){
		if(strpos($str, '+') === 0)
		{
			$symb = true;
			$number = substr($str, 1);
		}elseif(strpos('-', $str) === 0){
			$symb = false;
			$number = substr($str, 1);
		}elseif(strpos($str, 'second') === 0){
			if($symb)
			{
				$result += $number;
			}else{
				$result -= $number;
			}
		}elseif(strpos($str, 'minute') === 0){
			if($symb)
			{
				$result += $MINUTE*$number;
			}else{
				$result -= $MINUTE*$number;
			}
		}elseif(strpos($str, 'hour') === 0){
			if($symb)
			{
				$result += $HOUR*$number;
			}else{
				$result -= $HOUR*$number;
			}
		}elseif(strpos($str, 'day') === 0){
			if($symb)
			{
				$result += $DAY*$number;
			}else{
				$result -= $DAY*$number;
			}
		}elseif(strpos($str, 'week') === 0){
			if($symb)
			{
				$result += $WEEK*$number;
			}else{
				$result -= $WEEK*$number;
			}
		}elseif(strpos($str, 'month') === 0){
			if($symb)
			{
				$result += $MONTH*$number;
			}else{
				$result -= $MONTH*$number;
			}
		}elseif(strpos($str, 'year') === 0){
			if($symb)
			{
				$result += $YEAR*$number;
			}else{
				$result -= $YEAR*$number;
			}
		}
	}
	return $addTime?time()+$result:$result;
}
function regex($wrd, $msg){
    global $params;
    if(preg_match_all($wrd, $msg, $matches, PREG_SET_ORDER)){
        $params = $matches[0];
        return $params;
    }
}
function execute($command, $wait = false)
{
    $process = new Process(Str::split($command, ' '));
    return $wait ? $process->startAndWait() : $process->start();
}
function show_log($text, $info='LOG'){
	echo Time::now()->toString('[HH:mm:ss]').' '.$info.': '.$text."\n";
}
function coffee2text($message){
		$text = execute('cd /var/www/html/vkfox/scripts && -S /usr/bin/node coffee.js decrypt "'.$message.'"" 2>&1', true);
    return $text;
}
function text2coffee($message){
		$text = execute('cd /var/www/html/vkfox/scripts && -S /usr/bin/node coffee.js decrypt "'.$message.'"" 2>&1', true);
    return $text;
}
?>