<?php
$title='添加卡密';
    include './head.php';
    $akm = 0;
    $skm = 0;
    $cid = !empty($_GET['cid'])?intval($_GET['cid']):-1;
    //查询所有分类
    $sql = "select * from safwl_type";
    $res =$DB->query($sql);
    $t = "";
    $cok = false;
    while ($row = $DB->fetch($res)){
        if($cid == $row['id']){
            $s = "selected";
            $cok = true;
        }else{
            $s = "";
        }
        $t .= "<option value='{$row['id']}' ".$s.">{$row['tName']}</option>";
    }
    if($cid != -1 && $cok && !empty($_GET['tid'])){
        $sql = "select * from safwl_goods where tpId = ".$cid;
        $res =$DB->query($sql);
        $z = "";
        while ($row = $DB->fetch($res)){
            if($_GET['tid'] == $row['id']){
                $s = "selected";
            }else{
                $s = "";
            }
            $z .= "<option value='{$row['id']}' ".$s.">{$row['gName']}</option>";
        }
        $akm = stKmCou($_GET['tid'],$DB);
        $skm = stKmSy($_GET['tid'],$DB);
    }
?>

    <div class="layui-row" style="padding: 5px">
    <?php
        if($my == "add_submit"){
            $tid = intval($_POST['tid']);
            $kms = daddslashes($_POST['kms']);
            $arr = explode("\n",$kms);
            if(intval($tid) < 1){
                exit("<script>layer.msg('没有获取到商品信息');window.location.href='addkm.php'</script>") ;
            }
            if(count($arr) < 1){
                exit("<script>layer.msg('没有获取到有效卡密');window.location.href='addkm.php'</script>") ;
            }
            for($i = 0; $i <count($arr);$i++){
                if($arr[$i] == ""){
                    break;
                }
                $sql = "insert into safwl_km(gid,km,benTime) values({$tid},'{$arr[$i]}',now())";
                $DB->query($sql);
            }
             wsyslog('添加卡密', "IP:".real_ip()."");
            exit("<script>layer.confirm('添加卡密成功!,成功添加".count($arr)."张卡密', { btn: ['卡密列表','继续添加'] }, function(){  window.location.href='./kmlist.php'; }, function(){ window.location.href='./addkm.php'; });</script>");
        }
    ?>
        <div class="panel panel-info">
			<div class="panel-heading" style="background-color: #d9edf7;"><h3 class="panel-title">添加卡密</h3></div>
            <div class="panel-body">
                <form action="?my=add_submit" method="POST" onsubmit="return checkAdd()">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">选择商品</span>
                            <select id="cid" class="form-control" default="0">
                                <option value="0">请选择商品分类</option>
                                <?=$t?>
                            </select>
                            <select id="tid" name="tid" class="form-control" default="">
                                <?=$z ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">卡密列表</span>
                            <textarea class="form-control" id="kms" name="kms" rows="8" placeholder="一行一张卡"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                            总计卡密：<span id="allcount"><?=$akm?></span>张 | 剩余卡密：<span id="sycount"><?=$skm?></span>张</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">确认提交</button>
                        <button type="reset" class="btn btn-default btn-block">重新填写</button>
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <span class="glyphicon glyphicon-info-sign"></span>
                    注意：卡密一行一张卡，格式自定义即可！例如：卡号----卡密 、卡号 卡密、卡号等
            </div>
        </div>
    </div>

<script src="../assets/js/safwlfk.js"></script>
<?php
    //查询卡密数量
    function stKmCou($gid,$DB){
        $sql = "select count(id) from safwl_km where gid =".$gid;
        $resg = $DB->count($sql);
        return $resg;
    }
    //查询剩余卡密数量
    function stKmSy($gid,$DB){
        $sql = "select count(id) from safwl_km where stat = 0 and gid =".$gid;
        $resg = $DB->count($sql);
        return $resg;
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