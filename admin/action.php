<?php
include '../safwl/common.php';
@header('Content-Type: application/json; charset=UTF-8');
if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {

} else {
    @header('Content-Type: text/html; charset=UTF-8');
    echo "<script>
        while(true){
            alert('非法访问！');
        }
    </script>";
    exit();
}
if(empty($_GET['act'])){
    exit("非法访问！");
}else{
    $act=$_GET['act'];
}
if(empty($islogin) || $islogin != 1){
     exit('{"code":1,"msg":"请先登录"}');
}
if( empty($_SESSION['swxcjebbs']) ||  $_SESSION['swxcjebbs'] != md5($_COOKIE['admin_token']."****")){
    exit('{"code":1,"msg":"~~~"}');
}
switch ($act){
    case 'sp_qiehuan':
        $gid = intval($_POST['gid']);
        $zt = intval($_POST['zt']);
        $sql = "update safwl_goods set state = {$zt} where id = {$gid}";
        if($DB->query($sql)){
            exit('{"code":1,"msg":"操作成功！"}');
        }else{
            exit('{"code":-1,"msg":"操作失败！"}');
        }
        break;

    case 'sp_plexe':
        $str = daddslashes($_POST['str']);
        $type = daddslashes($_POST['type']);
        if(intval($type) < 0){
            if(intval($type) == -2){
                //全部下架
                $sql = "update safwl_goods set state = 0 where id in ({$str})";
                if($DB->query($sql)){
                    exit('{"code":1,"msg":"操作成功！"}');
                }else{
                    exit('{"code":-1,"msg":"操作失败！"}');
                }
            }
            if(intval($type) == -3){
                //全部上架
                $sql = "update safwl_goods set state = 1 where id in ({$str})";
                if($DB->query($sql)){
                    exit('{"code":1,"msg":"操作成功！"}');
                }else{
                    exit('{"code":-1,"msg":"操作失败！"}');
                }
            }
            if(intval($type) == -4){
                //全部删除
                //全部下架
                $sql = "delete from safwl_goods where id in ({$str})";
                if($DB->query($sql)){
                    exit('{"code":1,"msg":"操作成功！"}');
                }else{
                    exit('{"code":-1,"msg":"操作失败！"}');
                }
            }
        }else{
            //移动分类
            $sql = "update safwl_goods set tpId = $type where id in ($str)";
            if($DB->query($sql)){
                exit('{"code":1,"msg":"操作成功！"}');
            }else{
                exit('{"code":-1,"msg":"操作失败！"}');
            }
        }
        break;
    case 'getsp':
        $cid = intval($_POST['cid']);
        $sql = "select * from safwl_goods where tpId = ".$cid;
        $option = "<option value='-1'>请选择商品</option>";
        $res = $DB->query($sql);
        while ($row = $DB->fetch($res)){
            $option .= "<option value='".$row['id']."'>".$row['gName']."</option>";
        }
        if($option != ""){
            $data = array("code"=>1,"msg"=>"获取成功","data"=>$option);
        }
        exit(json_encode($data));//urlencode
        break;
    case 'getkminfo':
        $tid = intval($_POST['tid']);
        $sql1 = "select count(*) from safwl_km where gid = ".$tid;
        $sql2 = "select count(*) from safwl_km where stat = 0 and gid = ".$tid;
        $c1 = $DB->count($sql1);
        $c2 = $DB->count($sql2);
        exit('{"code":1,"msg":"获取成功","allcount":"'.$c1.'","sycount":"'.$c2.'"}');
        break;

    case 'exekm':
        $type = intval($_POST['type']);
        $str = daddslashes($_POST['str']);
        if($type == 1){
            $sql = "update safwl_km set stat = 1 where id in ($str)";
            $DB->query($sql);
            exit('{"code":1,"msg":"操作成功！"}');
        }elseif($type == 2){
            $sql = "update safwl_km set stat = 0 where id in ($str)";
            $DB->query($sql);
            exit('{"code":1,"msg":"操作成功！"}');
        }elseif($type == 3){
            $sql = "delete from safwl_km where id in ($str)";
            $DB->query($sql);
            exit('{"code":1,"msg":"操作成功！"}');
        }else{
            exit('{"code":-1,"msg":"Not Action！"}');
        }
        break;
    case 'det-ysykm':
        $sql = "delete from safwl_km where stat = 1";
        $DB->query($sql);
        exit('{"code":1,"msg":"操作成功！"}');
        break;
    case 'det-allkm':
        $sql = "delete from safwl_km ";
        $DB->query($sql);
        exit('{"code":1,"msg":"操作成功！"}');
        break;

    case 'getkmxxinfo':
        $id = intval($_POST['id']);
        $sql = "SELECT gName,km,benTime,endTime,out_trade_no,trade_no,rel,stat FROM `safwl_km` inner join safwl_goods on safwl_km.gid = safwl_goods.id where safwl_km.id = ".$id;
        $row = $DB->get_row($sql);
        if($row){
            $data = array("code" => 1,"msg" => "获取成功！",data =>$row);
        }else{
          $sql = "SELECT gName,km,benTime,endTime,out_trade_no,trade_no,rel,stat FROM `safwl_km` inner join safwl_goods on safwl_km.gid = safwl_goods.id where safwl_km.out_trade_no = ".$id;
        $row = $DB->get_row($sql);
        if($row){
            $data = array("code" => 1,"msg" => "获取成功！",data =>$row);
        }else{
            $data = array("code" => -1,"msg" => "获取失败！");
        }
        }
        exit(json_encode($data));
        break;
    case 'e_send_t':
        if($conf['mail_stmp'] && $conf['mail_port'] && $conf['mail_pwd'] && $conf['mail_port']){
            include SYSTEM_ROOT.'smtp.class.php';
            $ssl = $conf['mail_port']== 465?true:false;
            $x = new SMTP($conf['mail_stmp'],$conf['mail_port'],true,$conf['mail_name'],$conf['mail_pwd'],$ssl);
            if($x->send($conf['mail_name'],$conf['mail_name'],$conf['mail_title'],"这是一封测试邮件！表示成功！[safwl]",$conf['title'])){
                $data = array("code" => 1,"msg" => "发送成功！");
            }else{
                $data = array("code" => 1,"msg" => $x->log);
            }
        }else{
            $data = array("code" => -1,"msg" => "请先配置好邮箱信息保存！！");
        }
        exit(json_encode($data));
        break;
        default :  exit('{"code":-1,"msg":"Not Action！"}');break;
}