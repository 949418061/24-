<?php
/**
 * 访问密码管理
**/
$title='访问密码管理';
include './head.php';
$act = !empty($_GET['act'])? $_GET['act']:null;
function getaccstatus($s){
    if($s == 1){
        return "可用";
    }
    if($s == 0){
        return "不可用";
    }
}
function getacccount($pid){
    global  $DB;
    $sql = "select count(id) from safwl_accpasslogin where pid = ".$pid;
    $c = $DB->count($sql);
    return $c;
}
if($act == "add"){
    $pass = daddslashes($_POST['pass']);
    $remarks = daddslashes($_POST['remarks']);
	$sql=$DB->count("select count(*) from safwl_accpass where pass = '$pass'");
	if($sql > 0){
	 exit('<script language="JavaScript">;alert("密码已经存在,请重新输入");location.href="./accvtionlist.php";</script>');	
	}
    if($DB->query("insert into safwl_accpass values(null,'$pass','$remarks',now(),1)")){
        exit("<script language='javascript'>swal({ title: '密码添加成功！', text: '代理可通过该密码访问网站!', icon: 'success',buttons:false,});window.location.href='./accvtionlist.php';</script>");
    }else{
        exit("<script language='javascript'>swal({ title: '添加失败！', text: '数据库插入数据失败!', icon: 'error',buttons:false,});history.go(-1);</script>");
    }
}else if($act == "deletebyid"){
    $id = intval($_GET['id']);
    if($DB->query("delete from safwl_accpass where id=$id")){
        exit("<script language='javascript'>swal({ title: '移除成功！', text: '已移除本条记录!', icon: 'success',buttons:false,});window.location.href='./accvtionlist.php';</script>");
    }else{
        exit("<script language='javascript'>swal({ title: '添加失败！', text: '数据库删除数据失败!', icon: 'error',buttons:false,});history.go(-1);</script>");
    }
}elseif($act == "deleteloginlog"){
    if($DB->query("delete from safwl_accpasslogin")){
        exit("<script language='javascript'>swal({ title: '移除成功！', text: '已移除本条记录!', icon: 'success',buttons:false,});window.location.href='./accvtionlist.php';</script>");
    }else{
        exit("<script language='javascript'>swal({ title: '添加失败！', text: '数据库删除数据失败!', icon: 'error',buttons:false,});history.go(-1);</script>");
    }
}elseif($act == "querylv"){
    ?>
    <div class="container" style="padding-top:70px;">
    <div class="col-sm-12 col-md-10 center-block" style="float: none;">
    <?php 
        $pid = intval($_GET['id']);
        if($pid > 0){
             $numrows=$DB->count("SELECT count(*) from safwl_accpasslogin where pid = ".$pid);
              $status = " pid = ".$pid;
               $fyzy = "?act=querylv&id=$pid&page=";
        }else{
             $numrows=$DB->count("SELECT count(*) from safwl_accpasslogin");
              $status = 1;
               $fyzy = "?act=querylv&page=";
        }
       
       
    ?>
    <div class="table-responsive">
        <a href="./accvtionlist.php"  class="btn btn-sm btn-success">返回密码设置 >> </a>
         <a href="./accvtionlist.php?act=deleteloginlog"  class="btn btn-sm btn-danger">清空日志 </a>
        <table class="table table-striped">
            <thead><tr><th>ID</th><th>密码</th><th>访问时间</th><th>访问IP</th></tr></thead>
            <tbody>
            <?php

                $pagesize = 30;
         
                
                $numrows = $DB->count ("SELECT count(*) from safwl_accpasslogin WHERE {$status}");
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
                $sql = "SELECT * FROM safwl_accpasslogin WHERE {$status} order by id desc limit $offset,$pagesize";
               // exit($sql);
               
                

                $rs=$DB->query($sql);
                while($res = $DB->fetch($rs)){
                    echo '<tr><td>'.$res['id'].'</td><td>'.$res['pass'].'</td><td>'.$res['time'].'</td><td>'.$res['ip'].'</td></tr>';
                }
            ?>
            </tbody>
        </table>
    </div>
    <?php include "fy.php" ?>
    </div>
</div>    
        
    <?php
    
}else{
    
?>
<div class="container" style="padding-top:70px;">
    <div class="col-sm-12 col-md-10 center-block" style="float: none;">
    <?php 
        $numrows=$DB->count("SELECT count(*) from safwl_accpass");
        $status = 1;
    ?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
                <tr height="25">
                    <td align="center">
                        <font color="#808080"><b><span class="glyphicon glyphicon-hand-right"></span> 当前密码</b></br><?php echo $numrows;?>
                        </font>
                    </td>
                    <td align="center">
                        <font color="#808080"><b><a data-toggle="modal" data-target="#myModal2" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> 添加访问密码</a>
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
            <thead><tr><th>ID</th><th>密码</th><th>添加时间</th><th>访问次数</th><th>备注</th><th>状态</th><th>操作</th></tr></thead>
            <tbody>
            <?php

                $pagesize = 30;
                $fyzy = "";
                $numrows = 0;
                if(!empty($_GET['act']) && $_GET['act'] != null && $_GET['act'] == "sousuo"){
                    $pz = $_POST['pz'];
                    $numrows = $DB->count ("SELECT count(*) FROM safwl_accpass WHERE (pass like '%$pz%' or remarks like '%$pz%')");
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
                    $sql = "SELECT * FROM safwl_accpass WHERE pass like '%$pz%' or remarks like '%$pz%' order by id desc limit $offset,$pagesize";
                    $fyzy = "?act=sousuo&page=";
                }else{
                    $numrows = $DB->count ("SELECT count(*) from safwl_accpass WHERE {$status}");
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
                    $sql = "SELECT * FROM safwl_accpass WHERE {$status} order by id desc limit $offset,$pagesize";
                    $fyzy = "?page=";
                }

                $rs=$DB->query($sql);
                while($res = $DB->fetch($rs)){
                    echo '<tr><td>'.$res['id'].'</td><td>'.$res['pass'].'</td><td>'.$res['addtime'].'</td><td>'.getacccount($res['id']).'</td><td>'.$res['remarks'].'</td><td>'.getaccstatus($res['status']).'</td><td><a href="?act=querylv&id='.$res['id'].'" class="btn btn-info btn-xs">查看访问记录</a> <a href="?act=deletebyid&id='.$res['id'].'" class="btn btn-danger btn-xs">移除记录</a></td></tr>';
                }
            ?>
            </tbody>
        </table>
    </div>
    <?php include "fy.php" ?>
    </div>
</div>

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form action="?act=add" method="POST">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">添加访问密码</h4>
                </div>
                <div class="modal-body">
               
                    <input type="text" class="form-control" name="pass" id="pass" placeholder="验证密码"><br>
                    <br>
                    <textarea rows="5" cols="" placeholder="备注信息" class="form-control" name="remarks"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">确定添加</button>
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
                    <input type="text" class="form-control" name="pz" placeholder="输入密码备注！">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">立即搜索</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->
<?php } ?>
<!-- 
/*
 * 源码来源：52站长论坛
 * QQ:122564034
 * 最新源码下载地址：qin52.com
 * */
-->