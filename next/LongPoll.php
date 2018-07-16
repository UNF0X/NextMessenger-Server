<?php
$host = 'localhost';
$db   = '';
$user = '';
$pass = '$unfox';
$charset = 'utf8';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => true,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

$data = json_encode($pdo->query('SELECT * FROM updates where ( uid='.$_GET['uid'].' and ts > '.$_GET['ts'] .') or ( chat IN ( '.$_GET['chats'].' ) and ts > '.$_GET['ts'].')')->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
$pdo=null;
exit($data);
?>