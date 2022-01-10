<?php
include '../safwl/common.php';
@header('Content-Type: application/json; charset=UTF-8');
if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {

} else {
    exit( "页面非法请求！");
}
if(empty($_GET['act'])){
    exit("非法访问！");
}else{
    $act=$_GET['act'];
}
if(empty($islogin) || $islogin != 1){
     exit('{"code":1,"msg":"请先登录"}');
}
switch ($act){
    //删除订单
    case 'delOrd': 
        $id = _safwl($_POST['id']);
        $sql = "delete from safwl_order where id = ".$id;
        $b = $DB->query($sql);
        if($b > 0){
            wsyslog('删除订单', "IP:".real_ip()."");
            exit('{"code":1,"msg":"删除成功"}');
        }else{
            exit('{"code":-1,"msg":"删除失败"}');
        }
        break;
    //删除卡密
    case 'delKm': 
        $id = _safwl($_POST['id']);
        $sql = "delete from safwl_km where id = ".$id;
        $b = $DB->query($sql);
        if($b > 0){
            wsyslog('删除指定卡密', "IP:".real_ip()."");
            exit('{"code":1,"msg":"删除成功"}');
        }else{
            exit('{"code":-1,"msg":"删除失败"}');
        }
        break;
    //删除商品
    case 'delSp':
        $id = _safwl($_POST['id']);
        $sql = "delete from safwl_goods where id = ".$id;
        $b = $DB->query($sql);
        if($b > 0){
           
            $sql = "delete from safwl_km where gid = ".$id;
            $DB->query($sql);
             wsyslog('删除商品', "IP:".real_ip()."");
            exit('{"code":1,"msg":"删除成功"}');
        }else{
            exit('{"code":-1,"msg":"删除失败"}');
        }
        break;
    //删除商品分类
    case 'delType':
        $id = _safwl($_POST['tid']);
        $sql = "select * from safwl_goods where tpid = ".$id;
        $res = $DB->query($sql);
        if($row = $DB->fetch($res)){
            exit('{"code":-1,"msg":"该分类下面还有商品！请先删除商品后再删除分类！"}');
        }
        $sql = "delete from safwl_type where id =".$id;
        $b =  $DB->query($sql);
        if($b > 0){
             wsyslog('删除分类', "IP:".real_ip()."");
            exit('{"code":1,"msg":"删除成功"}');
        }else{
            exit('{"code":-1,"msg":"删除失败"}');
        }
        break;
    case 'update':
        $r = update_version();
        if($r['code'] == 2){
           
            $html = ' <div class="panel panel-default" style="margin: 10px;">'
                . '  <div class="panel-heading"> <h4>发现新版本</h4>版本号：V'.$r['version'].'('.$r['buiid'].')<br></div>'
                . '<div class="panel-body">'
                    . '<form action="?zongzi_versionupdate=true" method="POST"><input type="hidden" name="updatezip" value="'.$r['updatezip'].'"><input class="btn btn-info btn-sm form-control" type="submit" value="点击更新" /></form><br>'
                    . '<pre>更新日志：<br>'.$r['version-msg'].'</pre>'
        . ' </div></div>';
            exit(json_encode(array("code" => 2, "msg" => $html)));
        }else{
            exit(json_encode($r));
        }
     
        //exit('{"code":1,"msg":"认证成功"}');
        break;

    //删除所有卡密
    case 'det_allkm':
        $sql = "delete from safwl_km";
        $b =  $DB->query($sql);
        if($b > 0){
            wsyslog('删除所有卡密', "IP:".real_ip()."");
            exit('{"code":1,"msg":"删除成功"}');
        }else{
            exit('{"code":-1,"msg":"删除失败"}');
        }
        break;
    //删除已使用卡密
    case 'det_ykm':
        $sql = "delete from safwl_km where stat = 1";
        $b = $DB->query($sql);
        if($b > 0){
            wsyslog('删除已使用卡密', "IP:".real_ip()."");
            exit('{"code":1,"msg":"删除成功"}');
        }else{
            exit('{"code":-1,"msg":"删除失败"}');
        }
        break;
    //删除所有订单
    case 'det_allOder':
        $sql = "delete from safwl_order";
        $b =  $DB->query($sql);
        if($b > 0){
            wsyslog('删除所有订单', "IP:".real_ip()."");
            exit('{"code":1,"msg":"删除成功"}');
        }else{
            exit('{"code":-1,"msg":"删除失败"}');
        }
        break;
    case 'c':
        exit('{"code":1}');
        break;
    //删除未完成订单
    case 'det_wOder':
        $sql = "delete from safwl_order where sta = 0";
        $b =  $DB->query($sql);
        if($b > 0){
             wsyslog('删除未完成订单', "IP:".real_ip()."");
            exit('{"code":1,"msg":"删除成功"}');
        }else{
            exit('{"code":-1,"msg":"删除失败"}');
        }
        break;
    //清除日志信息
    case 'detlog':
        $sql = "delete from safwl_syslog";
        $b =  $DB->query($sql);
        if($b > 0){
             wsyslog('清除日志信息', "IP:".real_ip()."");
            exit('{"code":1,"msg":"删除成功"}');
        }else{
            exit('{"code":-1,"msg":"删除失败"}');
        }
        break;
    case 'settemp_change':
        $template = _safwl($_POST['template']);
        $DB->query("insert into safwl_config set `safwl_k`='view',`safwl_v`='{$template}' on duplicate key update `safwl_v`='{$template}'");
        exit('{"code":1,"msg":"切换模板成功"}');
        break;
    default:;break;
    }
?>