<?php
error_reporting(0);
define('CACHE_FILE', 0);
define('IN_CRONLITE', true);
define('TYPE', '888');
define('SYSTEM_ROOT', dirname(__FILE__).'/');
define('ROOT', dirname(SYSTEM_ROOT).'/');
define('SYS_KEY', 'fk_key');
define('AUTH_MD5_KEY', 'xxxxp');
session_start();
date_default_timezone_set('Asia/Shanghai');

$date = date("Y-m-d H:i:s");

$scriptpath=str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$asiteurl = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$sitepath.'/';

$password_hash='!@#%!s!8#';
$fpj = md5($password_hash."--");

if(!file_exists(ROOT.'config.php')){
    header('Content-type:text/html;charset=utf-8');
    echo '你还没安装！<a href="/install/">点此安装</a>';
    exit();
}
require ROOT.'config.php';
if(!defined('SQLITE') && (!$dbconfig['user']||!$dbconfig['pwd']||!$dbconfig['dbname']))//检测安装
{
    header('Content-type:text/html;charset=utf-8');
    echo '你还没安装！<a href="/install/">点此安装</a>';
    exit();
}

//连接数据库
include_once(SYSTEM_ROOT."db.class.php");
$DB=new DB($dbconfig['host'],$dbconfig['user'],$dbconfig['pwd'],$dbconfig['dbname'],$dbconfig['port']);

$version = require SYSTEM_ROOT.'version.php';
$funcpath = SYSTEM_ROOT."function.php";
include_once($funcpath);  
if($DB->query("select * from safwl_config where 1")==FALSE)//检测安装2
{
    header('Content-type:text/html;charset=utf-8');
    echo '你还没安装！<a href="install/">点此安装</a>';
    exit();
}else{
  
   $conf = initsystem();
    update_sql();
}
$siteurl = empty($conf['siteurl'])?($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST']."/":$conf['siteurl'];

//=========== 授权验证 ======================
if(empty($_COOKIE['auth'] ) || $_COOKIE['auth'] !=md5(8700+VERSION)){
     $_COOKIE['auth'] =md5(8700+$version['version']);
}


if($conf['payapi'] == 9){
    $payapi = $conf['epay_url'];//自定义支付
}else{
    $payapi='http://www.yiqianpay.cn/';//易千支付 【默认】
}
include_once(SYSTEM_ROOT."member.php");

$ereturn = $siteurl."query.php?no=";


?>