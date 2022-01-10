<?php
$title='订单管理';
include 'head.php';
$today=date("Y-m-d ").'00:00:00';
$status = 1;
?>

    <div class="layui-row" style="padding: 5px">
    <?php 
    	$numrows=$DB->count("SELECT count(*) from safwl_order");
    	$todaynumrows= $DB->count("SELECT COUNT(id) from safwl_order where benTime > '$today' ");
        $todayoknumrows = $DB->count("SELECT sum(money) from safwl_order where benTime > '$today' and sta = 1");
        $allokp = $DB->count("SELECT sum(money) from safwl_order where sta = 1");
        $aliokp = $DB->count("SELECT sum(money) from safwl_order where benTime > '$today' and sta = 1 and type = 'alipay'");
        $wxokp = $DB->count("SELECT sum(money) from safwl_order where benTime > '$today' and sta = 1 and type = 'wxpay'");
        $qqokp = $DB->count("SELECT sum(money) from safwl_order where benTime > '$today' and sta = 1 and (type = 'qqpay' or type = 'tenpay')");
	   $sql=" 1";
    ?>
        <div class="table-responsive">
            <table class="table layui-table">
                <tbody>
                    <tr height="25">
                        <td align="center"><font color="#808080">
                            <b><span class="glyphicon glyphicon-flag"></span> 当前订单总数</b>
                            </br><?php echo  $numrows;?></font>
                        </td>
                        <td align="center"><font color="#808080">
                            <b><span class="glyphicon glyphicon-plus-sign"></span> 今日总订单</b>
                            <br><?=$todaynumrows?></font>
                        </td>
                        <td align="center"><font color="#808080">
                            <b><span class="glyphicon glyphicon-leaf"></span> 总成交金额</b>
                            </br><?php echo sprintf("%.2f", $allokp);?>￥</font>
                        </td>
                        <td align="center"><font color="#808080"><b>
                        <?php
                            if(!empty($_GET['act']) && $_GET['act'] != null && $_GET['act'] == "sousuo"){
                                echo '<a class="btn btn-info" href="list.php"><span class="glyphicon glyphicon-search" ></span> 全部订单</a> ';
                            }else{
                                echo '<a class="btn btn-info"  data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-search" ></span> 搜索</a> ';
                            }
                        ?>
                        </font></td>
                    </tr>
                    <tr height="25">
                        <td align="center" colspan="2">
                            <button type="button" class="btn btn-warning" id="det_wOder">删除未成功订单</button>
                        </td>
                        <td align="center" colspan="2">
                            <button type="button" class="btn btn-danger" id="det_allOder">删除所有</button>
                        </td>
                    </tr>
                    <tr height="25">
                        <td align="center"><font color="#808080">
                            <b><span class="glyphicon glyphicon-ok"></span> 今日成交</b>
                            <br><?=sprintf("%.2f", $todayoknumrows); ?></font>
                        </td>
                        <td align="center"><font color="#808080">
                            <b><span class="	glyphicon glyphicon-retweet"></span> 支付宝</b>
                            <br><?=sprintf("%.2f", $aliokp);?>￥</font>
                        </td>
                        <td align="center"><font color="#808080">
                            <b><span class="	glyphicon glyphicon-retweet"></span> 微信</b>
                            <br><?=sprintf("%.2f", $wxokp);?>￥</font>
                        </td>
                        <td align="center"><font color="#808080">
                            <b><span class="	glyphicon glyphicon-retweet"></span> QQ</b>
                            <br><?=sprintf("%.2f", $qqokp);?>￥</font>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table layui-table">
                <thead>
                    <tr>
                        <th style="color:green;">订单编号/商户订单编号</th>
                        <th style="color:black">(ID)商品名称</th>
                        <th style="color:#4B0082">QQ/手机</th>
                        <th style="color:#3A5FCD">提取码<br>优惠券</th>
                        <th style="color:#1874CD;">创建时间<br>成交时间</th>
                        <th style="color:#00688B;">数量</th>
                        <th style="color:#CD3333">金额</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                </thead>
            <tbody>
            <?php
                $pagesize = 30;
                $fyzy = "";
                $numrows = 0;
                if(!empty($_GET['act']) && $_GET['act'] != null && $_GET['act'] == "sousuo"){
                    $pz = $_POST['pz'];
                    $numrows = $DB->count ("SELECT count(*) FROM safwl_order WHERE (out_trade_no like '%$pz%' or trade_no like '%$pz%' or rel like '%$pz%')");
                    $pages = intval ( $numrows / $pagesize );
                    if ($numrows % $pagesize) {
                        $pages ++;
                    }
                    if (isset ( $_GET ['page'] )) {
                        $page = intval ( $_GET ['page'] );
                    } else {
                        $page = 1;
                    }
                    $offset = $pagesize * ($page - 1);
                    $sql = "SELECT * FROM safwl_order WHERE out_trade_no like '%$pz%' or trade_no like '%$pz%' or rel  like '%$pz%' order by id desc ";
                    $fyzy = "?act=sousuo&page=";
                }else{
                    $zeroinfo = "";
                    $numrows = $DB->count ("SELECT count(*) from safwl_order WHERE {$status}");
                    $pages = intval ( $numrows / $pagesize );
                    if ($numrows % $pagesize) {
                        $pages ++;
                    }
                    if (isset ( $_GET ['page'] )) {
                        $page = intval ( $_GET ['page'] );
                    } else {
                        $page = 1;
                    }
                    $offset = $pagesize * ($page - 1);
                    $sql = "SELECT * FROM safwl_order WHERE{$sql} order by id desc limit $offset,$pagesize";
                    $fyzy = "?page=";
                }
            $rs=$DB->query($sql);
            while($res = $DB->fetch($rs)){
                echo '<tr><td  style="color:green;">'.$res['out_trade_no'].'<br>'.($res['trade_no']==""?"无":$res['trade_no']).'</td>'
                    . '<td style="color:black">(#'.$res['gid'].')'.substr_utf8(getName($res['gid'],$DB),10).'</td><td style="color:#4B0082">'.$res['rel'].'<br>'.($res['phone']==""?"无":$res['phone']).'</td><td style="color:	#3A5FCD">'.($res['paypass']==""?"未设置":$res['paypass']).'<br><font color=red>'.($res['coupon_id']==0?"无券":getcoupon_ka($res['coupon_id'])).'</font></td>'
                    . '<td style="color:#1874CD;">'.$res['benTime']."<br><font color=green>".($res['endTime']==""?"未知":$res['endTime']).'<font></td><td style="color:#00688B">'.$res['number'].'</td><td  style="color:#CD3333">￥'.$res['money'].'('.getPayType($res['type']).')'.'</td><td>'.zt($res['sta']).'</td>'
                    . '<td><span id="'.$res['id'].'" class="btn btn-xs btn-primary btndel">删除</span><span onclick="kmxxi('.$res['out_trade_no'].')" class="btn btn-xs btn-info">详细</span></td></tr>';
            }
        ?>
        </tbody>
    </table>
</div>
<?php include "fy.php" ?>
    </div>
<script src="../assets/js/safwlfk.js"></script>
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="?act=sousuo" method="POST">	
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    				<h4 class="modal-title" id="myModalLabel">搜索记录</h4>
    			</div>
    			<div class="modal-body">
                    <input type="text" class="form-control" name="pz" placeholder="订单编号/商户订单编号/联系方式！">
                </div>
    			<div class="modal-footer">
    				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">立即搜索</button>
    			</div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->
<?php
function getName($id,$DB){
    $sql = "select * from safwl_goods where id =".$id;
    $res = $DB->query($sql);
    $row = $DB->fetch($res);
    return $row['gName'];
}
function getPayType($str){
    if($str == "qqpay"){
        return "QQ钱包";
    }
    if($str == "tenpay"){
        return "财付通";
    }
    if($str == "alipay"){
        return "支付宝";
    }
    if($str == "wxpay"){
        return "微信";
    }
}
function zt($z){
    if($z == 0){
        return "<font color='red'>未完成</font>";
    }else if($z == 1){
        return "<font color=green>已完成</font>";
    }elseif($z == 1){
        return "<font color=blue>需补单</font>";
    }
}
?>
<!-- 
**********************************************
/*
 * 源码来源：52站长论坛
 * QQ:122564034
 * 最新源码下载地址：qin52.com
 * */  
**********************************************
-->