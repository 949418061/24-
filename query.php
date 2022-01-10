<?php

include 'safwl/common.php';
if($conf['txprotect'] == 1)
    include_once(SYSTEM_ROOT."txprotect.php");
if($conf['CC_Defender'] == 1){
    define('CC_Defender', 1); //防CC攻击开关(1为session模式)
    include_once SYSTEM_ROOT.'security.php';

}
$checkcip_row = $DB->get_row("select * from safwl_blacklist where data = '".real_ip()."' and type = 2");

if($checkcip_row){
    exit("抱歉，您已列入本站黑名单，无法使用本站!");
}


if(!empty($conf['view']) && $conf['view'] != ""){
    $t = $conf['view'];
}else{
    $t = "g15";
}

$_SESSION['createcount'] = 1;
include 'template/'.$t.'/query.php';
