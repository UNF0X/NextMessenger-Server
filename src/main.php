<?php 
use php\http\{HttpServer, HttpServerRequest, HttpServerResponse, HttpResourceHandler};
use Addons\jURL,
php\lang\Thread,
php\lang\ThreadPool,
httpclient\HttpClient,
httpclient\HttpRequest,
Addons\Database,
php\lib\fs,
php\time\Time;

//Назначение настроек
define('CONF', ['debug'=>true, 'show_methods'=>true]);
define('UserAgent', '');
define('VERIFIED', [1,2]);
define('HOST', 'url_to_site');
define('server_host', 'localhost');
define('server_port', 6560);
define('NEXT_VERSION', '0.0.1');
define('__UPLOAD_AND_LP__', 'url to longpoll and uploads');    


include('res://modules/acess_token.php');
include('res://modules/error.php');

function super_string($string){
    return str_replace('"','\\\"',$string);
}
Database::init();
$server = new HttpServer(server_port, server_host); 
echo "-> Starting NEXT MESSENGER SERVER ".NEXT_VERSION." by UNFOX at http://".server_host.":".server_port."\n";

$server->route('*', '/', function (HttpServerRequest $req, HttpServerResponse $res){
    $res->header('Access-Control-Allow-Origin',$req->header('Origin'));
    $res->header('Access-Control-Allow-Credentials',true);
    if(CONF['debug']==true){
        show_log("New connection to main from ".$req->header('X-Forwarded-For'));
    }
    $res->contentType('text/html; charset=utf-8');
    $res->body('<body bgcolor="white"><center><h1>403 Forbidden</h1></center><hr><center> Apache </center>');
});
$server->route('*', '/images/{image}', function (HttpServerRequest $req, HttpServerResponse $res){
    $image=$method=$req->attribute('image');
    if(file_exists(fs::abs('./')."/src/images/".$image)){
        $res->contentType('image/png');
        $res->body(file_get_contents(fs::abs('./')."/src/images/".$image));
    }else{
        $res->contentType('text/html; charset=utf-8');
        $res->body('<body bgcolor="white"><center><h1>404 Not Found</h1></center><hr><center> Apache </center>');
    }
});
$server->route('*', '/method/{method}', function (HttpServerRequest $req, HttpServerResponse $res) {
    // $user_data=Database::select(Database::FETCH_ALL,"select * from users where access_token='".$_POST['access_token']."'", []);
    $_GET=$req->queryParameters();

    $res->header('Access-Control-Allow-Origin','*');
    $res->header('Access-Control-Allow-Credentials',true);

    $res->contentType('application/json');
    $method=$req->attribute('method');
    if(isset($_GET['access_token']) and $_GET['access_token']!=''){
        $access_token=$_GET['access_token'];
        $access_token_data=Database::select(Database::FETCH_ALL,"select * from access_tokens where access_token=?", [$_GET['access_token']]);
        if(isset($access_token_data[0])){
            $user_data=Database::select(Database::FETCH_ALL,"select * from users where uid = ?", [$access_token_data[0]['uid']]);
            
            switch ($method) {
                case 'users.get':
                    if(isset($_GET['uid'])){
                        $get_user_data=Database::select(Database::FETCH_ALL,"select * from users where uid = ?", [$_GET['uid']]);
                        unset($get_user_data[0]['password']);
                        unset($get_user_data[0]['phone']);
                        unset($get_user_data[0]['login']);
                    }else{
                        $get_user_data=$user_data;
                        unset($get_user_data[0]['password']);
                        unset($get_user_data[0]['phone']);
                        unset($get_user_data[0]['login']);
                    }
                    if($get_user_data[0]['photo_100']==''){$get_user_data[0]['photo_100']=HOST.'/images/leaf_100.png';}
                    $res->body(json_encode(['response'=>$get_user_data[0]]));
                    break;
                
                case 'messages.createChat':
                    if(isset($_GET['title']) and $_GET['title'] != ''){
                        if(!isset($_GET['photo'])){$_GET['photo']=HOST.'/images/leaf_100.png';}
                        $date = Time::now()->toString('dd.MM.yyyy HH:mm:ss');
                      //  $ts=Time::millis();
                        Database::query('insert into chats (title, creator, users, photo, date, ts) values(?, ?, ?, ?, ?, ?)', [$_GET['title'], $user_data[0]['uid'], $user_data[0]['uid'], $_GET['photo'], $date, Time::millis()]);
                        //$chat=Database::select(Database::FETCH_ALL,"SELECT * FROM chats WHERE FIND_IN_SET('".$uid."', `users`) ORDER BY id DESC LIMIT 1", [$user_data[0]['uid']])[0];
                        $chat=Database::select(Database::FETCH_ALL,"SELECT * FROM chats WHERE users=".$user_data[0]['uid']." ORDER BY chat_id DESC LIMIT 1")[0];
                        Database::query("insert into messages (chat_id, message, create_date, from_id, server_message,ts) values(?,?,?,?,?,?)", [$chat['chat_id'], 'Chat created', $date, $user_data[0]['uid'],  1,Time::millis()]);
                        Database::query("insert into updates (uid,data,ts) values(".$user_data[0]['uid'].", '".json_encode(array_merge($chat, ['type'=>'create_chat']))."', ".$chat['ts'].")");
                        $ts=Time::millis();
                         Database::query("insert into updates (uid,data,ts) values(".$user_data[0]['uid'].", '".json_encode(['type'=>'message', 'chat_id'=>$chat['chat_id'], 'message'=>'Chat created', 'create_date'=>$date, 'from_id'=>$user_data[0]['uid'], 'server_message'=>1, 'ts'=>$ts])."', ".$ts.")");
                        $res->body(json_encode(['response'=>$chat]));                    
                    }
                    return;
                    break;

                case 'messages.lastMessage':
                $message=Database::select(Database::FETCH_ALL,"SELECT * FROM messages WHERE from_id=".$user_data[0]['uid']." and chat_id=".$_GET['chat_id']." ORDER BY id DESC LIMIT 1")[0];
                $res->body(json_encode(['response'=>$message]));   
                return;
                break;

                case 'messages.getChats':
                    $uid=$user_data[0]['uid'];
                    $dialogs=Database::select(Database::FETCH_ALL,"SELECT * FROM chats WHERE FIND_IN_SET('".$uid."', `users`)");
                    foreach ($dialogs as $key => $dialog) {
                        $last_message=Database::select(Database::FETCH_ALL,"SELECT * FROM messages WHERE chat_id=? ORDER BY id DESC LIMIT 1", [$dialog['chat_id']])[0];
                        $dialogs[$key]['last_message']=$last_message;
                    }
                    $res->body(json_encode(['response'=>['items'=>$dialogs]]));
                    return;
                    break;

                case 'messages.getChatUsers':
                    if(!isset($_GET['chat_id'])){ $res->body(Err::show(4, 'Not enough parameters!')); return;}
                    $chat=Database::select(Database::FETCH_ALL,"SELECT * FROM chats WHERE FIND_IN_SET('".$user_data[0]['uid']."', `users`) and chat_id=?",[$_GET['chat_id']]);
                    if(isset($chat[0])){
                        $users=explode(',', $chat[0]['users']);
                        $out_users=[];
                        foreach ($users as $key=>$user){
                            $user=Database::select(Database::FETCH_ALL,"select * from users where uid = ?", [$user]);
                            if(isset($user[0])){
                                unset($user[0]['password']);
                                unset($user[0]['login']);
                                unset($user[0]['phone']);
                                if($user[0]['photo_100']==''){$user[0]['photo_100']=HOST.'/images/leaf_100.png';}
                                $out_users[]=$user[0];
                            }
                        }
                        $res->body(json_encode(['response'=>$out_users]));
                        return;                    
                    }else{
                        $res->body(Err::show(12, 'Chat not found!'));
                    }
                    break;

                case 'messages.delete':
                    $uid=$user_data[0]['uid'];
                    if(isset($_GET['message_id']) and $_GET['message_id'] != '' and isset($_GET['chat_id']) and $_GET['chat_id'] != ''){
                        $message=Database::select(Database::FETCH_ALL,"SELECT * FROM messages WHERE id=? and from_id=?", [$_GET['message_id'], $uid]);
                        if(isset($message[0])){
                            Database::query('DELETE FROM messages WHERE id='.$_GET['message_id']);
                            Database::query("insert into updates (uid,data,ts,chat) values(?, '".json_encode(['chat_id'=>$_GET['chat_id'],'type'=>'message_delete', 'message_id'=>$_GET['message_id'],'ts'=>Time::millis()])."', ?, ?)",[$uid,Time::millis(), $_GET['chat_id']]);
                             $res->body(json_encode(['response'=>'ok']));
                             return;
                        }else{
                            $res->body(json_encode(['response'=>'error']));
                        }
                    }
                    break;

                case 'messages.setAcvitity':
                    if(isset($_GET['chat_id']) and $_GET['chat_id'] and isset($_GET['activity']) and $_GET['activity']!=''){
                        $ts=Time::millis();
                        Database::query("insert into updates (uid, data,ts, chat) values(?,'".json_encode(['type'=>"user_typing", 'text'=>$_GET['activity'], 'user_id'=>$user_data[0]['uid'], 'chat_id'=>$_GET['chat_id'], 'ts'=>$ts])."',?,?)", [$user_data[0]['uid'] ,$ts, $_GET['chat_id']]);
                        $res->body(json_encode(['response'=>"ok"]));
                    }else{}
                return;
                break;

                case 'chat.updatePhoto':
                    if(!isset($_GET['chat_id']) and !isset($_GET['photo']) and $_GET['chat_id']!='' and $_GET['photo'] != ''){ $res->body(Err::show(4, 'Not enough parameters!')); return;}
                        $date = Time::now()->toString('dd.MM.yyyy HH:mm:ss');
                        $chat=Database::select(Database::FETCH_ALL,"SELECT * FROM chats WHERE FIND_IN_SET('".$user_data[0]['uid']."', `users`) and chat_id=?",[$_GET['chat_id']]);
                        if(isset($chat[0])){
                            Database::query('UPDATE chats set photo="'.$_GET['photo'].'" WHERE chat_id='.$_GET['chat_id']);
                            $ts=Time::millis();
                            Database::query("insert into updates (uid, data,ts, chat) values(?,'".json_encode(['type'=>"chat_photo_update", 'user_id'=>$user_data[0]['uid'], 'chat_id'=>$_GET['chat_id'], 'ts'=>$ts, 'photo'=>$_GET['photo']])."',?,?)", [$user_data[0]['uid'] ,$ts, $_GET['chat_id']]);
                            $ts=Time::millis();
                            Database::query("insert into messages (chat_id, message, create_date, from_id, server_message,ts) values(?,?,?,?,?,?)", [$_GET['chat_id'], $user_data[0]['first_name'].' '.$user_data[0]['last_name'].' updated chat photo', $date, $user_data[0]['uid'],  1,$ts]);
                            $ts=Time::millis();
                            Database::query("insert into updates (uid,data,ts) values(".$user_data[0]['uid'].", '".json_encode(['type'=>'message', 'chat_id'=>$_GET['chat_id'],'message'=> $user_data[0]['first_name'].' '.$user_data[0]['last_name'].' updated chat photo', 'create_date'=>$date, 'from_id'=>$user_data[0]['uid'], 'server_message'=>1, 'ts'=>$ts])."', ".$ts.")");
                            $res->body(json_encode(['response'=>"ok"]));
                            return;                    
                        }else{
                            $res->body(Err::show(12, 'Chat not found!'));
                        }
                    return;
                    break;

                case 'profile.updatePhoto':
                    if(isset($_GET['photo']) and $_GET['photo'] != ''){
                     Database::query('UPDATE users set photo_100="'.$_GET['photo'].'" WHERE uid='.$user_data[0]['uid']);
                     $res->body(json_encode(['response'=>"ok"]));
                    }else{
                        $res->body(Err::show(4, 'Not enough parameters!')); return;
                    }
                    return;
                    break;

                case 'messages.edit':
                    $uid=$user_data[0]['uid'];
                    if(isset($_GET['message_id']) and $_GET['message_id'] != '' and isset($_GET['message']) and $_GET['message'] != '' and $_GET['chat_id'] != ''){
                        $message=Database::select(Database::FETCH_ALL,"SELECT * FROM messages WHERE id=? and from_id=?", [$_GET['message_id'], $uid]);
                        if(isset($message[0])){
                            Database::query('UPDATE messages set message="'.urlencode($_GET['message']).'", edited=1 WHERE id='.$_GET['message_id']);
                            $ts=Time::millis();
                             Database::query("insert into updates (uid, data,ts, chat) values(?, '".json_encode(['chat_id'=>$_GET['chat_id'],'id'=>$_GET['message_id'], 'type'=>'edit_message', 'message'=>$_GET['message'], 'ts'=>$ts, 'edited'=>1])."', ?, ?)", [$user_data[0]['uid'], $ts, $_GET['chat_id']]);
                             $res->body(json_encode(['response'=>'ok']));
                             return;
                        }else{
                            $res->body(json_encode(['response'=>'error']));
                        }
                    }
                    break;

                case 'messages.getChat':
                    if(!isset($_GET['chat_id'])){ $res->body(Err::show(4, 'Not enough parameters!')); return;}
                    $chat=Database::select(Database::FETCH_ALL,"SELECT * FROM chats WHERE FIND_IN_SET('".$user_data[0]['uid']."', `users`) and chat_id=?",[$_GET['chat_id']]);
                    if(isset($chat[0])){
                        $res->body(json_encode(['response'=>$chat[0]]));
                        return;                    
                    }else{
                        $res->body(Err::show(12, 'Chat not found!'));
                    }
                    break;

                case 'messages.ChatUserInvite':
                    if(!isset($_GET['chat_id']) and !isset($_GET['uid'])){ $res->body(Err::show(4, 'Not enough parameters!')); return;}
                    $chat=Database::select(Database::FETCH_ALL,"SELECT * FROM chats WHERE chat_id=?",[$_GET['chat_id']]);
                    if(isset($chat[0])){
                        $date = Time::now()->toString('dd.MM.yyyy HH:mm:ss');
                        if(in_array($_GET['uid'], explode(',', $chat[0]['users']))){
                            $res->body(json_encode(['error'=>['error_message'=>'User already added!', 'error_code'=>27]]));
                            return;
                        }
                        $users=$chat[0]['users'].','.$_GET['uid'];
                        Database::query('UPDATE chats set users="'.$users.'" WHERE chat_id='.$_GET['chat_id']);
                        $chat[0]['users']=$users;
                    
                        $ts=Time::millis();
                        Database::query("insert into messages (chat_id, message, create_date, from_id, server_message,ts) values(?,?,?,?,?,?)", [$chat[0]['chat_id'], $user_data[0]['first_name'].' '.$user_data[0]['last_name'].' added user '.$_GET['uid'].' to this chat', $date, $user_data[0]['uid'],  1,Time::millis()]);
                        Database::query("insert into updates (uid,data,ts) values(".$_GET['uid'].", '".json_encode(array_merge($chat[0], ['type'=>'chat_invite_user', 'ts'=>$ts]))."', ".$ts.")");
                        $ts=Time::millis();
                        Database::query("insert into updates (uid,data,ts) values(".$user_data[0]['uid'].", '".json_encode(['type'=>'message', 'chat_id'=>$chat[0]['chat_id'], 'message'=>$user_data[0]['first_name'].' '.$user_data[0]['last_name'].' added user '.$_GET['uid'].' to this chat', 'create_date'=>$date, 'from_id'=>$user_data[0]['uid'], 'server_message'=>1, 'ts'=>$ts])."', ".$ts.")");
                        return;                    
                    }else{
                        $res->body(Err::show(12, 'Chat not found!'));
                    }
                    break;

                case 'messages.get':
                    if(!isset($_GET['chat_id'])){$res->body(Err::show(4, 'Not enough parameters!')); return;}
                    $uid=$user_data[0]['uid'];
                    $chat=Database::select(Database::FETCH_ALL,"SELECT * FROM chats WHERE FIND_IN_SET('".$uid."', `users`) and chat_id=?",[$_GET['chat_id']]);
                    if(isset($chat[0])){
                        $messages=Database::select(Database::FETCH_ALL,"SELECT * FROM messages WHERE chat_id=?",[$_GET['chat_id']]);
                        $res->body(json_encode(['response'=>['messages'=>$messages]]));
                        return;
                    }
                    break;

                case 'messages.send':
                if(isset($_GET['message']) and $_GET['message'] != '' and isset($_GET['chat_id']) and $_GET['chat_id'] != ''){
                    $uid=$user_data[0]['uid'];
                    $_GET['message']=urlencode($_GET['message']);
                    unset($user_data[0]['password']);
                    unset($user_data[0]['phone']);
                    $chat=Database::select(Database::FETCH_ALL,"SELECT * FROM chats WHERE FIND_IN_SET('".$uid."', `users`) and chat_id=?",[$_GET['chat_id']]);
                    if(isset($chat[0])){
                        $date = Time::now()->toString('dd.MM.yyyy HH:mm:ss');
                       // var_dump(str_replace('"','\"',json_encode($user_data[0])));
                        $fwd="null";
                        if(isset($_GET['fwd'])){
                            $message=Database::select(Database::FETCH_ALL,"SELECT * FROM messages WHERE id=?",[$_GET['fwd']]);
                            if(isset($message[0])){
                              //  $message=json_encode($message);
                              //  $message=urldecode($message);
                              //  $message=json_decode($message,1);
                                $message[0]['fwd']=json_decode(urldecode($message[0]['fwd']),1);
                                $fwd="'".urlencode(json_encode([$message[0]]))."'";
                            }else{$fwd="null";}
                        }
                        if($user_data[0]['photo_100']==''){$user_data[0]['photo_100']=HOST.'/images/leaf_100.png';}
                        if(isset($_GET['attachment'])){$attachment=urlencode($_GET['attachment']);}else{$attachment='null';}
                        Database::query("insert into messages (chat_id, message, create_date, from_id, first_name, last_name, user_photo, fwd, ts, attachment) values(?, ?, ?, ?, ?, ?,?, ".$fwd.", ?, ?)", [$_GET['chat_id'], trim($_GET['message']), $date, $uid, $user_data[0]['first_name'],$user_data[0]['last_name'], $user_data[0]['photo_100'], Time::millis(), $attachment]);
                        $last_message=Database::select(Database::FETCH_ALL,"SELECT * FROM messages WHERE chat_id=? ORDER BY id DESC LIMIT 1", [$_GET['chat_id']])[0];
                        $res->body(json_encode(['response'=>$last_message]));
                        Database::query("insert into updates (uid,data,ts,chat) values(".$user_data[0]['uid'].", '".json_encode(array_merge($last_message, ['type'=>'message']))."', ".$last_message['ts'].", ".$_GET['chat_id'].")");
                        return;
                    }
                }else{
                    $res->body(Err::show(4, 'Not enough parameters!'));
                    return;
                }
                return;
                break;

                case 'status':
                $res->body('ok');
                return;
                break;


                default:
                    $res->body(Err::show(2, 'Undefined method!'));
                    return;
                    break;
            }
        }else{
            $res->body(Err::show(1, 'Invalid access_token!'));
            return;
        }


    }else{
        switch ($method) {
            case 'auth.login':
                if(isset($_GET['login']) and isset($_GET['password'])){
                    $user_data=Database::select(Database::FETCH_ALL,"select * from users where login = ? and password = ?", [$_GET['login'], md5($_GET['password'])]);
                    if(isset($user_data[0])){
                        $user_access_token=AccessToken::new($user_data[0]['uid']);
                        $res->body(json_encode(['response'=>['access_token'=>$user_access_token, 'user_data'=>$user_data[0]]]));
                    }else{
                        $res->body(Err::show(3, 'Incorrect login or password!'));
                    }
                }
                return;
                break;
            
            case 'auth.register':
                if(isset($_GET['login']) and $_GET['login'] != '' and isset($_GET['password']) and $_GET['password'] != '' and isset($_GET['first_name']) and $_GET['first_name'] != '' and isset($_GET['last_name']) and $_GET['last_name'] != '' and isset($_GET['nickname']) and $_GET['nickname'] != ''){
                    $date = Time::now()->toString('dd.MM.yyyy HH:mm:ss');
                    Database::query('insert into users (first_name,last_name,nickname,login,password,date) values(?, ?, ?, ?, ?, ?)', [$_GET['first_name'], $_GET['last_name'], $_GET['nickname'], $_GET['login'], md5($_GET['password']), $date]);
                    $user_data=Database::select(Database::FETCH_ALL,"select * from users where login = ? and password = ?", [$_GET['login'], md5($_GET['password'])]);
                    if(isset($user_data[0])){
                        $user_access_token=AccessToken::new($user_data[0]['uid']);
                        $res->body(json_encode(['response'=>['access_token'=>$user_access_token, 'user_data'=>$user_data[0]]]));
                    }else{
                        $res->body(Err::show(10, 'Unknown error!'));
                    }            
                }else{
                    $res->body(Err::show(4, 'Not enough parameters!'));
                }
                return;
                break;

            case 'status':
                $res->body('ok');
                return;
                break;

            default:
                $res->body(Err::show(2, 'Undefined method!'));
                return;
                break;
        }
    }

});
$server->route('*', '/longpoll/{method}', function (HttpServerRequest $req, HttpServerResponse $res) {
   // echo "POLLING\n";
    $res->header('Access-Control-Allow-Origin','*');
    $res->header('Access-Control-Allow-Credentials',true);
    $_GET=$req->queryParameters();
    $method=$req->attribute('method');
    $res->contentType('application/json');
    if(isset($_GET['access_token'])){
        $access_token=$_GET['access_token'];
        $access_token_data=Database::select(Database::FETCH_ALL,"select * from access_tokens where access_token=?", [$_GET['access_token']]);
        if(isset($access_token_data[0])){
            $user_data=Database::select(Database::FETCH_ALL,"select * from users where uid = ?", [$access_token_data[0]['uid']]);
            $uid=$user_data[0]['uid'];
            if($method=='get'){
                $res->body(json_encode(['response'=>['ts'=>Time::millis()]]));
                return;
            }elseif($method=='poll' and isset($_GET['ts']) and $_GET['ts'] != ''){
                $user_chats=Database::select(Database::FETCH_ALL,"SELECT * FROM chats WHERE FIND_IN_SET('".$uid."', `users`)");
                $start_time=Time::millis();

                $chats='';
                if(count($user_chats)<2){$user_chats[]['chat_id']=1997;}
                foreach ($user_chats as $key => $chat) {
                     $chats.=$chat['chat_id'].',';
                } 
                $chats=substr($chats, 0, -1);
                while (true) {
                    usleep(5000);
                   // $updates=Database::select(Database::FETCH_ALL,'SELECT * FROM updates where ( uid='.$uid.' and ts > '.$_GET['ts'] .') or ( chat IN ( '.$chats.' ) and ts > '.$_GET['ts'].')');
                    $updates=json_decode(file_get_contents(__UPLOAD_AND_LP__.'./request.php?'.http_build_query(['uid'=>$uid, 'chats'=>$chats, 'ts'=>$_GET['ts']])),1);
                    if(!is_array($updates)){
                        sleep(5);
                        Database::init();
                        continue;
                    }
                    if(isset($updates[0])){ 
                        foreach ($updates as $key => $update) {
                            $updates[$key]=json_decode($update['data'],1);
                        }
                        $ts=(count($updates)-1); 

                        $response=['response'=>['updates'=>$updates, 'ts'=>$updates[$ts]['ts']]];
                        $res->body(json_encode($response));
                        return;
                    }
                    if(Time::millis() - $start_time > 20000){
                        $res->body(json_encode(['response'=>['updates'=>[]]]));
                        return;
                    }
                }
            }

        }else{$res->body(Err::show(1, 'Invalid access_token!'));}
    }else{$res->body(Err::show(4, 'Not enough parameters!'));}
});

$server->route('*', '/upload', function (HttpServerRequest $req, HttpServerResponse $res) {
    $_GET=$req->queryParameters();
    file_put_contents('headers', print_r($req->headers(),1).'::'.print_r($_GET,1).'::'.print_r($req->bodyStream(),1));
    echo 'UPLOAD';
    $res->body('ok');
    //print_r();
});

/*$server->route('*', '/updater', function (HttpServerRequest $req, HttpServerResponse $res) {
    $_GET=$req->queryParameters();
    $res->contentType('application/json');

    $res->body(json_encode(['md5'=>md5('/var/www/html/main/updates/NextMessenger.jar'), 'url'=>__UPLOAD_AND_LP__.'/updates/NextMessenger.jar']));

});*/

echo "-> Server is ready to work.\n";
$server->run();
?>