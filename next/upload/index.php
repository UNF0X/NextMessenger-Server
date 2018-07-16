<?php


function getExtension( $filename ) {
	return end( explode( '.', $filename ) );
}

$uploaddir = '/var/www/html/main/upload/files/';
$name=$_FILES['file']['name'];
$extention=getExtension($name);
$name=str_replace('.'.$extention, '', $name);
$name=time().'.'.$extention;

$uploadfile = $uploaddir . basename($name);

if($_FILES["file"]["size"] > 1024*100*1024){
	echo json_encode(['error'=>'large size!']);
	exit;
}

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
	echo json_encode(['response'=>['url'=>'https://url_to_site/upload/files/'.$name, 'size'=>$_FILES['file']['size'], 'filename'=>$_FILES['file']['name'], 'd_filename'=>$name]]);
} else {
	echo "Возможная атака с помощью файловой загрузки!\n";
}

?>