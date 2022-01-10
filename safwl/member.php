<?php
if(!defined('IN_CRONLITE'))exit();

$my=isset($_GET['my'])?$_GET['my']:null;

$clientip=real_ip();
$funcd = getfileinfo($funcpath);
$funcd['s']<10000?exit():define('FUNCTIONK', true);

if(isset($_COOKIE["admin_token"]))
{
	$token=authcode(daddslashes($_COOKIE['admin_token']), 'DECODE', SYS_KEY);
	list($user, $sid) = explode("\t", $token);
	$session=md5($conf['admin'].$conf['pwd'].$password_hash);
	
	if($session==$sid) {
		$islogin=1;
	}
}


?>