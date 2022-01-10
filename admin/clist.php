<?php
$title='商品管理';
    include 'head.php';
    $today=date("Y-m-d ").'00:00:00';
    $status = 1;
?>

    <div class="layui-row" style="padding: 5px">
    <?php
        if($my == "edit_submit"){
            $id = intval($_GET['id']);
            $garr =  array();
            $garr['gName'] = daddslashes($_POST['gname']);
            $garr['gInfo'] = daddslashes($_POST['ginfo']);
            $garr['gInfo'] = nl2br($garr['gInfo']);
            $garr['gInfo'] = str_replace(array("\r\n", "\r", "\n"),"", $garr['gInfo']);
            $imgs =  upimgs($_FILES['img']);
            $garr['imgs'] = $imgs==null?null:$imgs;
            $garr['tpId'] = daddslashes($_POST['type']);
            $garr['price'] = daddslashes($_POST['price']);
			$garr['price_way'] = daddslashes($_POST['price_way']);
            $garr['state'] = daddslashes($_POST['state']);
			$garr['stapp'] = daddslashes($_POST['stapp']);
            $garr['sotr'] = daddslashes($_POST['sotr']);
            $garr['ycss'] = daddslashes($_POST['ycss']);
			$garr['gobv'] = daddslashes($_POST['gobv']);
            foreach ($garr as $k => $value) {
                if($k=='Submit')continue;
                if($k=='imgs' && $value == null)continue;
                if($set != ""){
                    $set = $set . ",";
                }
                $set .= $k ."= '".$value."'";
            }
            $sql = "update safwl_goods set {$set} where id = ".$id;
            if($DB->query($sql)){
                 wsyslog('修改商品信息', "IP:".real_ip()."");
                exit("<script language='javascript'>swal({ title: '修改成功！', text: '商品已经更新，请检验!', icon: 'success',buttons:false,});window.location.href='./clist.php';</script>");
            }else{
                exit("<script language='javascript'>swal({ title: '修改失败！', text: '请核对商品信息是否正确', icon: 'error',buttons:false,});history.go(-1);</script>");
            } 
        }elseif($my == "edit"){
            $id = intval($_GET['id']);
            $grow = $DB->get_row("select * from safwl_goods where id = ".$id);
            $uptypes=array(
                'image/jpg',
                'image/jpeg',
                'image/png',
                'image/pjpeg',
                'image/gif',
                'image/bmp',
                'image/x-png'
            );         
            //查询所有分类
            $sql = "select * from safwl_type";
            $res =$DB->query($sql);
            $t = "";
            while ($row = $DB->fetch($res)){
                if($grow['tpId'] == $row['id']){
                    $selected = "selected";
                }else{
                    $selected = "";
                }
                $t .= "<option value='{$row['id']}' ".$selected.">{$row['tName']}</option>";
            }
    ?>
    <div class="panel panel-info">
        <div class="panel-heading"  style="background-color: #69a5f5;color:white;"><h3 class="panel-title">编辑商品</h3></div>           
            <div class="panel-body">
                <form action="?my=edit_submit&id=<?=$id?>" onsubmit="return chkgoods()" method="post" class="form-horizontal"  enctype="multipart/form-data" role="form">
                <div class="form-group">
                    <label class="col-lg-3 control-label">选择商品的分类</label>
                    <div class="col-lg-8">
                        <select class="form-control" id="type" name="type" default="">
                            <option value="">----    选择商品分类    ----</option>
                            <?php echo $t;?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">商品图片</label>
                    <div class="col-lg-8">
                        <input type="file" name="img" class="form-control" >
                        允许上传的文件类型为:<?=implode(', ',$uptypes)?>
                    </div>
                </div><br/>
                <div class="form-group">
                    <label class="col-lg-3 control-label">商品名称</label>
                    <div class="col-lg-8">
                        <input type="text" name="gname" id="gname" value="<?=$grow['gName']?>" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label">商品介绍</label>
                    <div class="col-lg-8">
					<textarea class="form-control" rows="5" cols="" name="ginfo" id="info" required="required" style="width:100%;height:300px;"><?=$grow['gInfo']?></textarea>
							<script src="../kindeditor/kindeditor-all.js"></script>
                            <script src="../kindeditor/lang/zh-CN.js"></script>
                            <script>
                              KindEditor.ready(function(K){
                              window.editor = K.create('#info');
                               });
                            </script>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">商品价格</label>
                <div class="col-lg-8">
                    <input type="text" name="price" id="price"  value="<?=$grow['price']?>" class="form-control">
                </div>
            </div>
			<div class="form-group">
                <label class="col-lg-3 control-label">代理价格</label>
                <div class="col-lg-8">
                    <input type="text" name="price_way" id="price_way"  value="<?=$grow['price_way']?>" class="form-control">
                </div>
            </div>
			<div class="form-group">
                <label class="col-lg-3 control-label">已售出数量</label>
                <div class="col-lg-8">
                    <input type="text" name="ycss" id="ycss"  value="<?=$grow['ycss']?>" class="form-control">
                </div>
			 </div>
			<div class="form-group">
				<label class="col-lg-3 control-label">最少购买</label>
                <div class="col-lg-8">
                    <input type="text" name="gobv" id="gobv"  value="<?=$grow['gobv']?>" class="form-control">
					*部分模板无效
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">是否上架</label>
                <div class="col-lg-8">
                    <select class="form-control" id="state" name="state" default="">
                        <option value='1'>上架</option>
                        <option value='0'>停售</option>
                    </select>
                </div>
            </div>
			<div class="form-group">
                        <label class="col-lg-3 control-label">操作模式</label>
                        <div class="col-lg-8">
                            <select class="form-control" id="stapp" name="stapp" default="">
							    <option value='2' <?php if($grow['stapp']==2) echo "selected"; ?>  >手工充值</option>
                                <option value='1' <?php if($grow['stapp']==1) echo "selected"; ?>  >邮寄商品</option>
                                <option value='0' <?php if($grow['stapp']==0) echo "selected"; ?>  >自动发卡</option>
                            </select>
                        </div>
                    </div>
            <div class="form-group">
                <label class="col-lg-3 control-label">商品排序</label>
                <div class="col-lg-8">
                    <input placeholder="数字越大 排名越靠前"  type="number" name="sotr" value="<?=$grow['sotr']?>" id="sotr"  class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-8">
                    <input type="submit" name="" id="" value="保存商品" class="btn btn-primary form-control"/><br/>
                </div>
            </div>
        </form>
    </div>    
</div>
    <?php
        }elseif($my == "add_submit"){
            $garr =  array();
            $garr['gName'] = daddslashes($_POST['gname']);
			if(!empty($_POST['ginfo'])){
			$garr['gInfo'] = daddslashes($_POST['ginfo']);
			}else{
            $garr['gInfo'] = daddslashes('暂无内容');
            } 
            //$garr['gInfo'] = str_replace("\r\n","<br>", $garr['gInfo']);
            $garr['gInfo'] = nl2br($garr['gInfo']);
            $garr['gInfo'] = str_replace(array("\r\n", "\r", "\n"),"", $garr['gInfo']);
            //exit($garr['gInfo']);
            $imgs =  upimgs($_FILES['img']);
            $garr['imgs'] = $imgs==null?"assets/goodsimg/df.jpg":$imgs;
            $garr['tpId'] = daddslashes($_POST['type']);
            $garr['price'] = daddslashes($_POST['price']);
			$garr['price_way'] = daddslashes($_POST['price_way']);
            $garr['state'] = daddslashes($_POST['state']);
			$garr['stapp'] = daddslashes($_POST['stapp']);
            $garr['sotr'] = daddslashes($_POST['sotr']);
            $garr['ycss'] = daddslashes($_POST['ycss']);
			$garr['gobv'] = daddslashes($_POST['gobv']);
            if($DB->insert_array("safwl_goods",$garr)){
                 wsyslog('添加商品', "IP:".real_ip()."");
                 exit("<script language='javascript'>swal({ title: '添加成功！', text: '恭喜您，新商品已经上架了!', icon: 'success',buttons:false,});window.location.href='./clist.php';</script>");
                }else{
                    exit("<script language='javascript'>swal({ title: '添加失败！', text: '请核对商品信息是否正确', icon: 'error',buttons:false,});history.go(-1);</script>");
                }
        }else if($my == "add"){
            $uptypes=array(
                'image/jpg',
                'image/jpeg',
                'image/png',
                'image/pjpeg',
                'image/gif',
                'image/bmp',
                'image/x-png'
                );           
            //查询所有分类
            $sql = "select * from safwl_type";
            $res =$DB->query($sql);
            $t = "";
            while ($row = $DB->fetch($res)){
                $t .= "<option value='{$row['id']}'>{$row['tName']}</option>";
            }
    ?>
    <div class="panel panel-info">
        <div class="panel-heading" style="background-color: #d9edf7;"><h3 class="panel-title">添加商品</h3></div>           
            <div class="panel-body">
                <form action="?my=add_submit" onsubmit="return chkgoods()" method="post" class="form-horizontal"  enctype="multipart/form-data" role="form">
                    <div class="form-group">
                        <label class="col-lg-3 control-label">选择商品的分类</label>
                        <div class="col-lg-8">
                            <select class="form-control" id="type" name="type" default="">
                                <option value="">----    选择商品分类    ----</option>
                                <?php echo $t;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">商品图片</label>
                        <div class="col-lg-8">
                            <input type="file"  name="img"  class="form-control" >
                            允许上传的文件类型为:<?=implode(', ',$uptypes)?>
                        </div>
                    </div><br/>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">商品名称</label>
                        <div class="col-lg-8">
                            <input type="text" name="gname" id="gname" class="form-control" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">商品介绍</label>
                        <div class="col-lg-8">
                            <textarea rows="5" cols=""  id="info" name="ginfo"  style="width:100%;height:300px;"></textarea>
							<script src="../kindeditor/kindeditor-all.js"></script>
                            <script src="../kindeditor/lang/zh-CN.js"></script>
                            <script>
                              KindEditor.ready(function(K){
                              window.editor = K.create('#info');
                               });
                            </script>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">商品价格</label>
                        <div class="col-lg-8">
                            <input type="text" name="price" id="price" class="form-control" required="required">
                        </div>
						</div>
						<div class="form-group">
                        <label class="col-lg-3 control-label">代理价格</label>
                        <div class="col-lg-8">
                            <input type="text" name="price_way" id="price_way" class="form-control" required="required">
                        </div>
                    </div>
						<div class="form-group">
                        <label class="col-lg-3 control-label">已售出数量</label>
                        <div class="col-lg-8">
                            <input type="text" name="ycss" id="ycss" class="form-control" required="required">
                        </div>
					</div>
						<div class="form-group">
						<label class="col-lg-3 control-label">最少购买</label>
                        <div class="col-lg-8">
                            <input type="text" name="gobv" id="gobv" value="1" class="form-control" required="required">
							*部分模板无效
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">是否上架</label>
                        <div class="col-lg-8">
                            <select class="form-control" id="state" name="state" default="">
                                <option value='1'>上架</option>
                                <option value='0'>停售</option>
                            </select>
                        </div>
                    </div>
					 <div class="form-group">
                        <label class="col-lg-3 control-label">操作模式</label>
                        <div class="col-lg-8">
                            <select class="form-control" id="stapp" name="stapp" default="">
                                <option value='0'>自动发卡</option>
                                <option value='1'>邮寄商品</option>
								<option value='2'>手工充值</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">商品排序</label>
                        <div class="col-lg-8">
                            <input placeholder="数字越大 排名越靠前" value="5" type="number" name="sotr" id="sotr"  class="form-control" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
                            <input type="submit" name="" id="" value="添加商品" class="btn btn-primary form-control"/><br/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php
        }else{
    ?>  
    <?php 
        $numrows=$DB->count("SELECT count(*) from safwl_goods");
        $sjz= $DB->count("SELECT count(*) from safwl_goods where state = 1");
        $xjz= $DB->count("SELECT count(*) from safwl_goods where state = 0");
        $sql=" 1";
    ?>
    <div class="table-responsive">
        <table class="table layui-table">
            <tbody>
                <tr height="25">
                    <td align="center">
                        <font color="#808080"><b><span class="glyphicon glyphicon-flag"></span> 商品数量</b></br>
                            <?php echo $numrows;?>
                        </font>
                    </td>
                    <td align="center">
                        <font color="#808080"><b>
                            <span class="glyphicon glyphicon-plus-sign"></span> 上架中</b>
                            <br><?=$sjz?>
                        </font>
                    </td>
                    <td align="center">
                        <font color="#808080"><b>
                            <span class="glyphicon glyphicon-ok"></span> 已下架</b>
                            <br><?=$xjz?>
                        </font>
                    </td>
                    <td align="center">
                        <font color="#808080">
                            <?php
                            if(!empty($_GET['act']) && $_GET['act'] != null && $_GET['act'] == "sousuo"){
                                echo '<a class="btn btn-info" href="clist.php"><span class="glyphicon glyphicon-search" ></span> 全部商品</a> ';
                            }else{
                                echo '<a class="btn btn-info"  data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-search" ></span> 搜索商品</a> ';
                            }
                        ?>
                    </font>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table layui-table">
        <thead>
            <tr>
                <th><input type="checkbox" onclick="allcheck(this)" /></th>
                <th>商品ID</th>
                <th>商品名称</th>
                <th>商品分类<br>发货类型</th>
                <th>商品价格<br>代理价格</th>
                <th>卡密总数</th>
                <th>剩余卡密</th>
                <th>商品状态</th>
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
    $numrows = $DB->count ("SELECT count(*) FROM safwl_goods WHERE (gName like '%$pz%' or id like '%$pz%' or tpId like '%$pz%')");
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
    $sql = "SELECT * FROM safwl_goods WHERE gName like '%$pz%' or id like '%$pz%' or tpId like '%$pz%' order by id desc";
    $fyzy = "?act=sousuo&page=";
}else{
    $numrows = $DB->count ("SELECT count(*) from safwl_goods WHERE {$status}");
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
    $sql = "SELECT * FROM safwl_goods WHERE {$status} order by id desc limit $offset,$pagesize";
    $fyzy = "?page=";
}
    $rs=$DB->query($sql);
    while($res = $DB->fetch($rs)){
		if($res['stapp']==0){
		$stappp="自动发卡";
		}else{
		if($res['stapp']==1){
		$stappp="邮寄商品";
		}else{
		$stappp="手工充值";
		}
		}
        $tp = !empty($conf['view'])?$conf['view']:"default";
        $url = $siteurl."share_".($res['id']).".html";
        $btn = "<a href='./clist.php?my=edit&id=".$res['id']."'class='btn btn-xs btn-primary'>编辑</a><a href='./ajax2.php?my=edit&id=".$res['id']."'class='btn btn-xs btn-primary'>清卡密</a><span id='{$res['id']}' class='btn btn-xs btn-primary btndel_sp'>删除</span>
            <a href='{$url}' target='_blank'><span class='btn btn-xs btn-primary glyphicon glyphicon-paperclip'></span></a>
            <select class='btn btn-xs btn-success' onchange='exAction(this.value,".$res['id'].",\"".$res['gName']."\")'>
                <option value='1'>=== 卡密导出操作 ===</option>
                <option value='2'> 导出剩余卡密</option>
                <option value='3'> 导出已使用卡密</option>
                <option value='4'> 导出全部卡密</option>
            </select>";
        echo '<tr><td><input type="checkbox" class="chkbox" ids="'.$res['id'].'" /></td><td>'.$res['id'].'</td>'
        . '<td>'.$res['gName'].'</td><td>'.stType($res['tpId'],$DB).'<br><font color="#0000FF">'.$stappp.'</font></td>'
        . '<td>'.$res['price'].'<br><font color="#0000FF">'.$res['price_way'].'</font></td><td>'.stKmCou($res['id'],$DB).'　<a href="kmlist.php?tid='.$res['id'].'" title="查看卡密" class="btn btn-xs btn-danger glyphicon glyphicon-eye-open"></a></td><td>'.stKmSy($res['id'],$DB).'　<a href="addkm.php?cid='.$res['tpId'].'&tid='.$res['id'].'" title="加卡" class="btn btn-xs btn-danger glyphicon glyphicon-plus-sign"></a></td><td>'.zt($res['state'],$res['id']).'</td>'
        . '<td>'.$btn.'</td></tr>';
    }

    //查询所有分类
    $sql = "select * from safwl_type";
    $res =$DB->query($sql);
    $t = "";
    while ($row = $DB->fetch($res)){
        $t .= "<option value='{$row['id']}'>{$row['tName']}</option>";
    }
?>
        </tbody>
    </table>
</div>
    <input  type="checkbox" onclick="allcheck(this)" /> 全选　
        <select id="select_exe">
            <option value="-1">【导航】将选中商品移动到...</option>
            <?=$t?>
            <option value="-1">【导航】将选中商品...</option>
            <option value="-2">全部下架</option>
            <option value="-3">全部上架</option>
            <option value="-4">全部删除</option>
        </select> 　
        <input type="button" value="立即执行" id="btn_exe">　
    <br>
<?php include "fy.php" ?>
<?php
    }
?>
    </div>

  
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
                    <input type="text" class="form-control" name="pz" placeholder="商品ID/商品名称/分类ID！">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">立即搜索</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->

<script src="../assets/js/safwlfk.js"></script>
<?php
//查询商品类型
function stType($tpId,$DB){
    $sql = "select * from safwl_type where id =".$tpId;
    $rest = $DB->query($sql);
    $rowt = $DB->fetch($rest);
    return $rowt['tName'];
}
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
function zt($z,$gid){
    if($z == 0){
        return "<font class='btn btn-xs btn-warning' onclick=\"gzt_sh('{$gid}','1')\">停售</font>";
    }else if($z == 1){
        return "<font  class='btn btn-xs btn-info'  onclick=\"gzt_sh('{$gid}','0')\">上架中</font>";
    }
}
function zzt($z,$id){
    if($z == 0){
        return "<a href='./clist.php?act=sj&id={$id}' class='btn btn-xs btn-warning '>上架</a>";
    }else{
        return "<a href='./clist.php?act=xj&id={$id}' class='btn btn-xs btn-warning '>下架</a>";
    }
}
?>
<script type="text/javascript">
    function exAction(v,gid,gname){
        if(v == 1){

        }
        if(v == 2){
            window.open("./export.php?act=ex_sykm&id="+gid+"&gname="+gname);
        }
        if(v == 3){
            window.open("./export.php?act=ex_ysykm&id="+gid+"&gname="+gname);
        }
        if(v == 4){
            window.open("./export.php?act=ex_allkm&id="+gid+"&gname="+gname);
        }
    }
    function chkgoods(){
        var sotr = $("#sotr").val();
        var price = $("#price").val();
		var price_way = $("#price_way").val();
        if($("#type option:selected").text() == "----    选择商品分类    ----"){
            alert("请选择商品分类！");
            return false;
        }
        if($("#gname").val() == ""){
            alert("商品名称不能为空！");
            return false;
        }
        if(checkLx(sotr) == false){
            alert("排序顺序必须是数字！");
            return false;
        }
        if(checkLx(price) == false){
            alert("价格必须是数字！");
            return false;
        }
		if(checkLx(price_way) == false){
            alert("价格必须是数字！");
            return false;
        }
        return true;
    }
    //判断是否未数字
    function checkLx(num){
        var t=num;
        if(!isNaN(t) && t !=""){
            return true;
        }else{
            return false;
        }
    }
    //判断是否是小数
    function checkFNum(c){
        var t=c;
        if(!isNaN(t) && t !=""){
            if(t.indexOf(".") != -1){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
</script>
<!-- 
**********************************************
/*
 * 源码来源：52站长论坛
 * QQ:122564034
 * 最新源码下载地址：qin52.com
 * */    
**********************************************
-->