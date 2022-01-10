<?php

define('SYSTEM_ROOT_E', dirname(__FILE__) . '/');
include '../safwl/common.php';
function showalert($msg,$status,$orderid=null){
    global $ereturn;
    echo '<meta charset="utf-8"/><script>window.location.href="'.$ereturn.$orderid.'";</script>';
}

$_POST = $_GET;
ksort($_POST); //排序post参数
reset($_POST); //内部指针指向数组中的第一个元素
$sign = '';
foreach ($_POST AS $key => $val) {
    if ($val == '') continue;
    if ($key != 'sign') {
        if ($sign != '') {
            $sign .= "&";
            $urls .= "&";
        }
        $sign .= "$key=$val"; //拼接为url参数形式
        $urls .= "$key=" . urlencode($val); //拼接为url参数形式
    }
}
if (!$_POST['pay_no'] || md5($sign . $conf['epay_key']) != $_POST['sign']) { //不合法的数据 KEY密钥为你的密钥
    showalert('验证失败！', 4, '订单回调验证失败！');
} else { //合法的数据
    
	$trade_no = $_POST['pay_no'];
    $out_trade_no = $_POST['param'];
    $post = "";
    foreach ($_POST as $key => $value) {
        if($post!="") $post.="&";
        $post = $post .$key."=".$value;
    } 
    //$url = $siteurl."other/codepay_notify.php?";
    $returnmsg = mcallbackprocess_temp();
	//exit($returnmsg);
    wsyslog("订单回调！","订单编号：".$out_trade_no."|sign:".$_POST['sign']."|md5:".md5($sign . $conf['epay_key'])."、异步返回：".$returnmsg);
    showalert('您所购买的商品已付款成功，感谢购买！',1,$trade_no);

}

function mcallbackprocess_temp() {
	$trade_no = $_POST['pay_no'];
	$md5_trade_no = $_POST['param'];
	$sql = "SELECT * FROM safwl_order WHERE out_trade_no='{$md5_trade_no}' limit 1";
	global $DB, $conf;
	$res = $DB->query($sql);
    $srow = $DB->fetch($res);
	if (!$srow) {
		wsyslog("mcallbackprocess,处理失败", "订单不存在！");
		return false;
	}
	if ($srow['sta'] != 0) {
		wsyslog("mcallbackprocess,处理失败", "订单已被处理,状态：(" . $srow['sta'] . ")：" . $out_trade_no);
		return true;
	}
	$number = $srow['number'];
	$money = $srow['money'];
	$qq = $srow['rel'];
	$out_trade_no = $srow['out_trade_no'];
	$goodsrow = getgoods($srow['gid']);
	$zzmoney = 0; //增值服务
	if ($conf['sendphonedx'] && $srow['phone'] != "") {
		$zzmoney = $zzmoney + 0.1;
	}
	$allmoney = round($goodsrow['price_way'] * $number + $zzmoney, 2);
	if($money == $allmoney){
	if ($srow['coupon_id']) {
		//优惠券
		$crow = getcoupon($srow['coupon_id'], 0);
		if (!$crow) {
			wsyslog("cbp,处理失败", "订单(" . $out_trade_no . ")处理失败,优惠券(ID:" . $srow['coupon_id'] . ")无效或已被使用！");
			return false;
		}
		$value = $crow['coupon_value'];
		if ($crow['coupon_type'] == 1) {
			$allprice = round($allmoney - $value + $zzmoney, 2);
		}
		if ($crow['coupon_type'] == 2) {
			$allprice = round($allmoney * $value*0.1 + $zzmoney, 2);
		}
		if ($allprice != $money) {
			wsyslog("cbp,处理失败", "价格计算失败(系统价格：" . $money . "/计算价格" . ($allmoney) . ")：" . $out_trade_no);
			return false;
		}
		$DB->query("update safwl_coupon set coupon_status=1,order_id={$srow['id']},coupon_sytime=now() where id = " . $srow['coupon_id']);
	} elseif (!$goodsrow || $money != $allmoney) {
		if (!$goodsrow) {
			wsyslog("cbp,处理失败", "商品异常" . $out_trade_no);
		}

		wsyslog("cbp,处理失败", "价格验证失败(系统价格：" . $money . "/计算价格" . ($allmoney) . ")：" . $out_trade_no);
		return false;
	}
    }else{
    $allmoney = round($goodsrow['price'] * $number + $zzmoney, 2);
	if ($srow['coupon_id']) {
		//优惠券
		$crow = getcoupon($srow['coupon_id'], 0);
		if (!$crow) {
			wsyslog("cbp,处理失败", "订单(" . $out_trade_no . ")处理失败,优惠券(ID:" . $srow['coupon_id'] . ")无效或已被使用！");
			return false;
		}
		$value = $crow['coupon_value'];
		if ($crow['coupon_type'] == 1) {
			$allprice = round($allmoney - $value + $zzmoney, 2);
		}
		if ($crow['coupon_type'] == 2) {
			$allprice = round($allmoney * $value*0.1 + $zzmoney, 2);
		}
		if ($allprice != $money) {
			wsyslog("cbp,处理失败", "价格计算失败(系统价格：" . $money . "/计算价格" . ($allmoney) . ")：" . $out_trade_no);
			return false;
		}
		$DB->query("update safwl_coupon set coupon_status=1,order_id={$srow['id']},coupon_sytime=now() where id = " . $srow['coupon_id']);
	} elseif (!$goodsrow || $money != $allmoney) {
		if (!$goodsrow) {
			wsyslog("cbp,处理失败", "商品异常" . $out_trade_no);
		}

		wsyslog("cbp,处理失败", "价格验证失败(系统价格：" . $money . "/计算价格" . ($allmoney) . ")：" . $out_trade_no);
		return false;
	}
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
				"kmlist" => $kmlist,
			);
			$html = email_content($conf['mail_content'], $emailrow);
			if (sendemail($qq . "@qq.com", $html)) {
				wsyslog("邮箱提醒成功", "订单编号：" . $out_trade_no . ",已发送到：" . $qq . "@qq.com");
			} else {
				wsyslog("邮箱提醒失败", "订单编号：" . $out_trade_no . ",邮箱：" . $qq . "@qq.com");
			}
		}
		if ($conf['sendphonedx'] && $srow['phone'] != "") {

			$emailrow = array(
				"title" => $conf['title'],
				"goodsname" => $goodsrow['gName'],
				"goodsmoney" => $goodsrow['price'],
				"goodsokmoney" => $money,
				"out_trade_no" => $out_trade_no,
				"trade_no" => $trade_no,
				"time" => $srow['endTime'],
				"qq" => $srow['rel'],
				"qqnickname" => get_qqnick($srow['rel']),
				"kmlist" => $kmlist,
			);
			$html = email_content($conf['dx_content'], $emailrow);
			file_put_contents("1.txt", "正在测试发送手机短信..." . $html);
			$message_configs['appid'] = $conf['dx_appid'];
			$message_configs['appkey'] = $conf['dx_appkey'];
			require_once '../safwl/dx/messagesend.php';
			$submail = new MESSAGEsend($message_configs);
			$submail->setTo($srow['phone']);
			$submail->SetContent($html);
			$xsend = $submail->send();
			//  file_put_contents("2.txt", json_encode($xsend));
			if ($xsend['status'] == "success") {
				wsyslog("短信发送成功", "订单编号：" . $out_trade_no . ",已发送到：" . $srow['phone']);
			} else {
				wsyslog("短信发送失败", "订单编号：" . $out_trade_no);
			}
		}
		wsyslog("mcallbackprocess,交易成功", "订单编号：" . $md5_trade_no . ";数量：" . $number . ";成功提取数量：" . $ok . "");
		return true;

	} else {
		wsyslog("mcallbackprocess,处理失败", "修改订单状态失败：" . $out_trade_no);
		return false;
	}

}
?>