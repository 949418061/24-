<?php
/**
 * 优惠券列表
**/
$title='优惠券列表';
include './head.php';
$act = !empty($_GET['act'])? $_GET['act']:null;


?>
<div class="container" style="padding-top:70px;">
    <div class="col-sm-12 col-md-10 center-block" style="float: none;">
     <?php
if($act == "add"){
    if($conf['iscoupon']==0){
          exit("<script language='javascript'>alert('请先开启优惠券系统！');history.go(-1);</script>");
    }
    $gid = intval($_POST['gid']);
    $type = intval($_POST['type']);
    $value = daddslashes($_POST['v']);
    $number = intval($_POST['number']);
    $endtime = $_POST['endtime'];
    $remarks = daddslashes($_POST['remarks']);
    
    if($number <=0){
        //生成数量有误
        exit("<script language='javascript'>alert('生成数量错误！');history.go(-1);</script>");
    }
    if($remarks == ""){
        //无备注
        exit("<script language='javascript'>alert('请对当前生成的优惠券进行备注,方便后期区分！');history.go(-1);</script>");
    }
    if($endtime <= $date){
          exit("<script language='javascript'>alert('优惠券过期时间请大于当前时间！');history.go(-1);</script>");
    }
   // echo '正在添加...';
    echo "<ul class='list-group'><li class='list-group-item active'>成功生成以下卡密</li>";
    for ($i = 0; $i < $number; $i++) {
            $km=random($conf['coupon_ka_num']);
            $sql = "insert into safwl_coupon values(null,'$remarks','$km',now(),'$endtime',null,$type,'$value',null,$gid,0)";
           $sql=$DB->query($sql);
            if($sql) {
                    echo "<li class='list-group-item'>$km</li>";
            }
    }
    echo '</ul>';
     echo "<ul class='list-group'><li class='list-group-item active'>卡密操作</li>";
      echo "<li class='list-group-item'><a href='export.php?act=coupon&id=$number' class='btn btn-success'>下载卡密</a>"
              . " <a href='couponlist.php' class='btn btn-warning'>返回优惠券列表</a></li>";
       echo '</ul>';
    
   
}else if($act == "deletebyid"){
    $id = intval($_GET['id']);
    if($DB->query("delete from safwl_coupon where id=$id")){
        exit("<script language='javascript'>swal({ title: '移除成功！', text: '黑名单已移除本条记录!', icon: 'success',buttons:false,});window.location.href='./couponlist.php';</script>");
    }else{
        exit("<script language='javascript'>swal({ title: '添加失败！', text: '数据库删除数据失败!', icon: 'error',buttons:false,});history.go(-1);</script>");
    }
}else{
        $numrows=$DB->count("SELECT count(*) from safwl_coupon");
        $status = 1;
    ?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
                <tr height="25">
                    <td align="center">
                        <font color="#808080"><b><span class="glyphicon glyphicon-hand-right"></span> 当前优惠券</b></br><?php echo $numrows;?>
                        </font>
                    </td>
                    <td align="center">
                        <font color="#808080"><b><a data-toggle="modal" data-target="#myModal2" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> 生成优惠券</a>
                        </font>
                    </td>
                    <td align="center">
                        <font color="#808080"><b><a class="btn btn-info"  data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-search" ></span> 搜索</a> 
                        </font>
                    </td>
               </tr>
            </tbody>
        </table>
        <table class="table table-striped">
            <thead><tr><th>优惠商品</th><th>优惠券</th><th>类型</th><th>添加时间/到期时间</th><th>面值</th><th>备注</th><th>状态</th><th>操作</th></tr></thead>
            <tbody>
            <?php

                $pagesize = 30;
                $fyzy = "";
                $numrows = 0;
                if(!empty($_GET['act']) && $_GET['act'] != null && $_GET['act'] == "sousuo"){
                    $pz = $_POST['pz'];
                    $numrows = $DB->count ("SELECT count(*) FROM safwl_coupon WHERE (coupon_ka like '%$pz%' or coupon_remarks like '%$pz%')");
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
                    $sql = "SELECT * FROM safwl_coupon WHERE coupon_ka like '%$pz%' or coupon_remarks like '%$pz%' order by id desc limit $offset,$pagesize";
                    $fyzy = "?act=sousuo&page=";
                }else{
                    $numrows = $DB->count ("SELECT count(*) from safwl_coupon WHERE {$status}");
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
                    $sql = "SELECT * FROM safwl_coupon WHERE {$status} order by id desc limit $offset,$pagesize";
                    $fyzy = "?page=";
                }

                $rs=$DB->query($sql);
                while($res = $DB->fetch($rs)){
                    if($res['coupon_goods_id'] == 0){
                        $gname = "全场通用券";
                    }else{
                        $gname = getgoods($res['coupon_goods_id']);
                        $gname = $gname['gName'];
                    }
                    if($res['coupon_type'] == 2){
                           $value = $res['coupon_value']."折";
                    }else{
                        $value = $res['coupon_value']."元";
                    }
                 
                    
                    echo '<tr><td>'.$gname.'</td><td>'.$res['coupon_ka'].'</td><td>'.coupontype($res['coupon_type']).'</td><td>'.$res['coupon_addtime'].'<br>'.$res['coupon_endtime'].'</td><td>'.$value.'</td><td>'.$res['coupon_remarks'].'</td><td>'.getcouponsta($res['coupon_status']).'</td><td><a href="?act=deletebyid&id='.$res['id'].'" class="btn btn-danger btn-xs">移除记录</a></td></tr>';
                }
            ?>
            </tbody>
        </table>
    </div>
    <?php include "fy.php";
}
?>
    </div>
</div>

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form action="?act=add" method="POST">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">生成优惠券</h4>
                </div>
                <div class="modal-body">
                      <select class="form-control" name="gid">
                        <option value="0">全场通用,不限商品</option>
                       <?php
                       $sql = "select * from safwl_goods where state = 1 order by sotr desc";
                       $rs = $DB->query($sql);
                       while ($row = $DB->fetch($rs)){
                           echo ' <option value="'.$row['id'].'">'.$row['gName'].'</option>';
                       }
                       ?>
                    </select><br>
                    <select class="form-control" name="type">
                        <option value="1">代金券（商品金额 - 代金券面值）</option>
                        <option value="2">折扣券（商品金额 * 折扣券面值）</option>
                    </select><br>
                    <input type="number" class="form-control" name="number" id="number" placeholder="生成数量"><br>
                    <input type="text" class="form-control" name="v" id="v" placeholder="卡密面值"><br>
                    <input type="text" class="form-control" value="<?=date('Y')?>/<?=date('m')+1?>/<?=date('d')?> <?=date('H:i:s')?>" name="endtime" id="endtime" placeholder="优惠券到期时间"><br>
                    <br>
                    <textarea rows="5" cols="" class="form-control" name="remarks" placeholder="优惠券备注/活动名称/...用于标记区分优惠券的文字"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">确定生成</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->

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
                    <input type="text" class="form-control" name="pz" placeholder="输入优惠券/备注！">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">立即搜索</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->
<!-- 
**********************************************
/*
 * 源码来源：52站长论坛
 * QQ:122564034
 * 最新源码下载地址：qin52.com
 * */       
**********************************************
-->