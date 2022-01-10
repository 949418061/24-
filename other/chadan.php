<?php
include '../safwl/common.php';
$seg = "<br>";
if (empty($conf['dulchad']) || $conf['dulchad'] == 2) {
	sysmsg("管理员未开启独立查单页面！<br>如需开启,请登录后台,在系统设置中开启");
}
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no"  name="viewport" />
  <title>订单查询 - <?=$conf['title']?></title>
  <meta name="keywords" content="<?php echo $conf['keywords']; ?>">
  <meta name="description" content="<?php echo $conf['description']; ?>">
   <link rel="shortcut icon" type="image/x-icon" href="<?=$siteurl?>assets/imgs/favicon.ico" media="screen" />
  <link href="<?=$siteurl?>assets/bootstrap3.3.7/css/bootstrap.min.css" rel="stylesheet"/>

</head>
<body>
    <div class="container" style="padding-top:70px;">
        <div class="col-xs-12 col-sm-12 col-lg-12 center-block" style="float: none;">
            <div class="text-center">
                <h2><b><?=$conf['title']?> - 订单查询</b></h2>
                <small><a href="<?=$siteurl?>">平台首页</a> | <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $conf['zzqq']; ?>&site=qq&menu=yes">联系客服</a> </small>
                <br><br>
                <form class="form-inline" role="form" method="POST" action="?">
                        <div class="form-group">
                          <label class="sr-only" for="name">凭证</label>
                          <input type="hidden" name="rand" value="<?=time()?>">
                          <input type="text" class="form-control" id="pz" value="<?=@$_POST['pz']?>" name="pz" placeholder="请输入联系方式/订单编号">
                        </div>

                        <button type="submit" class="btn btn-default">搜索</button>
            </form><br>
                 </div>
            <div class="table-responsive">
    <table class="table table-striped">

  <thead>
    <tr>
        <th>订单编号</th>
      <th>商品名称</th>
      <th>购买金额</th>
      <th>购买时间</th>
      <th>卡密</th>
    </tr>
  </thead>
  <tbody>
      <?php
if (!empty($_POST['rand'])) {
	$pz = _ayangw($_POST['pz']);
	$sql = "select * from ayangw_order where sta = 1 and ( out_trade_no='$pz' or trade_no = '$pz' or md5_trade_no ='$pz' or rel ='$pz') order by id desc";
	$rs = $DB->query($sql);
	while ($orderrow = $DB->fetch($rs)) {
		$out_trade_no = $orderrow['out_trade_no'];
		$trade_no = $orderrow['trade_no'];
		$number = $orderrow['number'];
		$gid = $orderrow['gid'];
		$grow = getgoods($gid);

		$sql = "select * from ayangw_km where trade_no='{$trade_no}' and out_trade_no = '{$out_trade_no}' and stat = 1 limit $number";
		$kmrs = $DB->query($sql);
		$kmlist = "";
		while ($kmrow = $DB->fetch($kmrs)) {
			if ($kmlist != "") {
				$kmlist .= $seg;
			}
			if ($kmrow['km']) {
				$kmstr = $kmrow['km'];

			} else {
				$kmstr = "卡密过期,已被管理员删除";
			}
			$kmlist .= $kmstr;
		}
		if ($kmlist == "") {
			$kmlist = "卡密过期,已被管理员删除";
		}

		?>
                <tr>
                    <td><?=$out_trade_no . "<br>" . $trade_no?></td>
                     <td><?=substr_utf8($grow['gName'], 10)?></td>
                    <td><?=$orderrow['money']?>(数量:<?=$number?>)</td>
                    <td><?=$orderrow['endTime']?></td>
                    <td><?=$kmlist?></td>
              </tr>
                <?php
}

}

?>


  </tbody>
</table>
    </div>



    </div></div>
</body>
</html>