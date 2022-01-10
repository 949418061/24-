<?php
$title='网站配置';
include './head.php';
?>


	<div class="layui-row" style="padding: 5px">
	<?php 
		if($_GET['mod'] == "subupadmin"){
			$user = trim($_POST['admin']);
			$oldpwd = trim($_POST['oldpwd']);
			$newpwd = trim($_POST['newpwd']);
			if(strlen($user) < 3){
				showmsg("管理用户名长度不能少于3个字符！<br><a href='./set.php?mod=admin'>返回</a>");
			}
			if(strlen($newpwd) < 6){
				showmsg("密码长度不能少于6个字符！<br><a href='./set.php?mod=admin'>返回</a>");
			}
			if(md5($oldpwd.$password_hash) != $conf['pwd']){
				showmsg("旧密码错误！<br><a href='./set.php?mod=admin'>返回</a>");
			}
			$pwd = md5($newpwd.$password_hash);
			$DB->query("update `safwl_config` set `safwl_v` ='{$pwd}' where `safwl_k`='pwd'");
			$DB->query("update `safwl_config` set `safwl_v` ='{$user}' where `safwl_k`='admin'");
                        wsyslog('修改系统配置', "IP:".real_ip().",修改了管理员信息");
			showmsg("修改成功！<br><a href='./set.php?mod=admin'>返回</a>",1);
                        exit();
		}
                if(isset($_POST['submit'])) {
                 
			foreach ($_POST as $k => $value) {
				$value=daddslashes($value);
                $value = trim($value);
				$DB->query("insert into safwl_config set `safwl_k`='{$k}',`safwl_v`='{$value}' on duplicate key update `safwl_v`='{$value}'");
			}
                        wsyslog('修改系统配置', "IP:".real_ip().",修改了系统配置");
			showmsg('修改成功！',1);
			exit();
		}else if($_GET['mod'] == "site"){
	?>
		
			<div class="panel panel-info">
			<div class="panel-heading" style="background-color: #d9edf7;"><h3 class="panel-title">网站信息配置</h3></div>
			<div class="panel-body">
				<form action="" method="post" class="form-horizontal" role="form">
					<h3>网站配置</h3>
                                        <hr>
                                        
                                        <div class="form-group">
						<label class="col-sm-2 control-label">店铺名称</label>
						<div class="col-sm-10"><input type="text" name="title" id="web_name" value="<?php echo  $conf['title'];  ?>" class="form-control" required/></div>
					</div><br/>
					<div class="form-group">
						<label class="col-sm-2 control-label">网站副标题</label>
						<div class="col-sm-10"><input type="text" name="ftitle" id="ftitle" value="<?php echo  $conf['ftitle'];  ?>" class="form-control" required/></div>
					</div><br/>
					<div class="form-group">
						<label class="col-sm-2 control-label">标题栏描述</label>
						<div class="col-sm-10"><input type="text" name="description" id="web_description" value="<?php echo $conf['description'];  ?>" class="form-control"/></div>
					</div><br/>
					<div class="form-group">
						<label class="col-sm-2 control-label">关键字</label>
						<div class="col-sm-10"><input type="text" name="keywords" id="web_keywords" value="<?php echo $conf['keywords']; ?>" class="form-control"/></div>
					</div><br/>
					<div class="form-group">
						<label class="col-sm-2 control-label">客服ＱＱ</label>
						<div class="col-sm-10"><input type="text" name="zzqq" id="web_qq" value="<?php echo $conf['zzqq']; ?>" class="form-control"/></div>
					</div><br/>
					<div class="form-group">
						<label class="col-sm-2 control-label">首页公告1</label>
						<div class="col-sm-10">
						<textarea class="form-control" rows="5" cols="" name="notice1" id="info" required="required" style="width:100%;height:200px;"><?php echo htmlspecialchars($conf['notice1']);?></textarea>
							<script src="../kindeditor/kindeditor-all.js"></script>
                            <script src="../kindeditor/lang/zh-CN.js"></script>
                            <script>
                              KindEditor.ready(function(K){
                              window.editor = K.create('#info');
                               });
                            </script>
						
						</div>
						
						
					</div><br/>
					<div class="form-group">
						<label class="col-sm-2 control-label">首页公告2</label>
						<div class="col-sm-10"><textarea class="form-control" id="web_notice2" name="notice2" rows="5"><?php echo htmlspecialchars($conf['notice2']);?></textarea></div>
					</div><br/>
					<div class="form-group">
						<label class="col-sm-2 control-label">首页公告3</label>
						<div class="col-sm-10"><textarea class="form-control" id="web_notice3" name="notice3" rows="5"><?php echo htmlspecialchars($conf['notice3']);?></textarea></div>
					</div><br/>
					<div class="form-group">
						<label class="col-sm-2 control-label">发货页面公告</label>
						<div class="col-sm-10"><textarea class="form-control" id="dd_notice" name="dd_notice" rows="5"><?php echo htmlspecialchars($conf['dd_notice']);?></textarea></div>
					</div><br/>
					<div class="form-group">
						<label class="col-sm-2 control-label">底部版权</label>
						<div class="col-sm-10"><textarea class="form-control" id="web_foot" name="foot" rows="5"><?php echo htmlspecialchars($conf['foot']);?></textarea></div>
					</div><br/>
					<h3>系统开关</h3>
                                        <hr>
                                        <div class="form-group">
						<label class="col-sm-2 control-label">订单编号</label>
						<div class="col-sm-10">
							<select class="form-control" id="tradenotype" name="tradenotype">
								<option value="1" <?php if($conf['tradenotype']==1) echo "selected"; ?> >js</option>
								<option value="2" <?php if($conf['tradenotype']==2) echo "selected"; ?>>php</option>
							</select>
						</div>
                                               
					</div><br />
                                        <div class="form-group">
						<label class="col-sm-2 control-label">开启密码提取</label>
						<div class="col-sm-10">
							<select class="form-control" id="paypasstype" name="paypasstype">
								<option value="1" <?php if($conf['paypasstype']==1) echo "selected"; ?> >需要提取密码</option>
								<option value="2" <?php if($conf['paypasstype']==2) echo "selected"; ?>>不需要提取密码</option>
							</select>
						</div>
                                               
					</div><br />
					<div class="form-group">
						<label class="col-sm-2 control-label">是否显示运行日志</label>
						<div class="col-sm-10">
							<select class="form-control" id="syslog" name="syslog">
								<option value="1" <?php if($conf['syslog']==1) echo "selected"; ?> >显示</option>
								<option value="2" <?php if($conf['syslog']==2) echo "selected"; ?>>不显示</option>
							</select>
                                                     * 部分模板生效
						</div>
					</div><br />
					<div class="form-group">
						<label class="col-sm-2 control-label">是否显示库存</label>
						<div class="col-sm-10">
							<select class="form-control" id="showKc" name="showKc">
								<option value="1" <?php if($conf['showKc']==1) echo "selected"; ?> >显示</option>
								<option value="2" <?php if($conf['showKc']==2) echo "selected"; ?>>不显示</option>
							</select>
                                                     * 部分模板生效
						</div>
					</div><br />
					<div class="form-group">
						<label class="col-sm-2 control-label">是否显示商品图片</label>
						<div class="col-sm-10">
							<select class="form-control" id="showImgs" name="showImgs">
								<option value="1" <?php if($conf['showImgs']==1) echo "selected"; ?> >显示</option>
								<option value="2" <?php if($conf['showImgs']==2) echo "selected"; ?>>不显示</option>
							</select>
                                                     * 部分模板生效
						</div>
					</div><br />
					<div class="form-group" style="display:none;">
						<label class="col-sm-2 control-label">防CC模式</label>
						<div class="col-sm-10">
							<select class="form-control" id="CC_Defender" name="CC_Defender">
								<option value="1" <?php if($conf['CC_Defender']==1) echo "selected"; ?> >开启</option>
								<option value="2" <?php if($conf['CC_Defender']==2) echo "selected"; ?>>关闭</option>
							</select>
                                                    
						</div>
					</div><br />
					<div class="form-group" style="display:none;">
						<label class="col-sm-2 control-label">反腾讯检测</label>
						<div class="col-sm-10">
							<select class="form-control" id="txprotect" name="txprotect">
								<option value="1" <?php if($conf['txprotect']==1) echo "selected"; ?> >开启</option>
								<option value="2" <?php if($conf['txprotect']==2) echo "selected"; ?>>关闭</option>
							</select>
							                          * 部分浏览器使用不了
						</div>
					</div><br />
					<div class="form-group">
						<label class="col-sm-2 control-label">防红模式</label>
						<div class="col-sm-10">
							<select class="form-control" id="qqtz" name="qqtz">
								<option value="1" <?php if($conf['qqtz']==1) echo "selected"; ?> >开启</option>
								<option value="0" <?php if($conf['qqtz']==0) echo "selected"; ?>>关闭</option>
							</select>
						</div>
					</div><br />
					<div class="form-group">
						<label class="col-sm-2 control-label">伪静态</label>
						<div class="col-sm-10">
							<select class="form-control" id="share" name="share">
								<option value="1" <?php if($conf['share']==1) echo "selected"; ?> >开启</option>
								<option value="0" <?php if($conf['share']==0) echo "selected"; ?>>关闭</option>
							</select>
							* 部分模板生效
						</div>
					</div><br />
					<div class="form-group">
						<label class="col-sm-2 control-label">库存显示模式</label>
						<div class="col-sm-10">
							<select class="form-control" id="shayld" name="shayld">
								<option value="1" <?php if($conf['shayld']==1) echo "selected"; ?> >范围库存</option>
								<option value="0" <?php if($conf['shayld']==0) echo "selected"; ?>>正常显示</option>
							</select>
							* 少于100 显示库存充足 少于30 显示 库存很多 少于10 显示库存少量* 部分模板生效
						</div>
					</div><br />
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" name="submit" value="修改保存" class="btn btn-primary form-control"/><br/>
						</div>
					</div>
				</form>
			</div>
		</div>
	<?php
		}elseif($_GET['mod'] =='pay'){
	?>
			<div class="panel panel-info">
			<div class="panel-heading" style="background-color: #d9edf7;"><h3 class="panel-title">支付接口商户配置</h3></div>
			<div class="panel-body">
				<form action="" method="post" class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-lg-3 control-label">选择支付接口</label>
						<div class="col-lg-8">
							<select class="form-control" id="payapi" name="payapi" onchange="showepayurlinput(this.value)">
							    <option value="5" <?php if($conf['payapi']==5) echo "selected"; ?>>【个人收款需挂软件】码支付 - 即时到账 立即收款</option>
								<option value="3" <?php if($conf['payapi']==3) echo "selected"; ?>>【个人收款非常稳定】幻兮支付</option>
								<option value="2" <?php if($conf['payapi']==2) echo "selected"; ?>>【个人收款需挂软件】优云宝支付</option>
                                <option value="1" <?php if($conf['payapi']==1) echo "selected"; ?>>【不推荐】易千支付 - api.yiqianpay.cn </option>
								<option value="8" <?php if($conf['payapi']==8) echo "selected"; ?>>【推荐】个人即时到账手动发货-先修改才能设置参数 </option>
								<option value="9"  <?php if($conf['payapi']==9) echo "selected"; ?>>自定义易支付接口  格式：http://域名/</option>
							</select>
							<font style="color: green;">具体提现相关手续请查看官网或联系官方结算客服!</font>
						</div>
					</div>
					<div class="form-group" id="epay_url_div"   style="<?php if($conf['payapi']!=9) echo "display: none;"; ?>">
						<label class="col-lg-3 control-label">自定义支付接口地址</label>
						<div class="col-lg-8">
							<input type="text" id="epay_url" name="epay_url" class="form-control"
							value="<?php echo $conf['epay_url']?>">
						</div>
					</div>
					<?php if($conf['payapi']==8){?>
					<div class="form-group">
						<label class="col-lg-3 control-label">支付宝收款账号</label>
						<div class="col-lg-8">
							<input type="text" id="epay_id" name="epay_id" class="form-control"
							value="<?php echo $conf['epay_id']?>">
						</div>
					</div>
	               <div class="form-group">
		            <label class="col-lg-3 control-label">收款二维码图片地址</label>
		            <div class="col-lg-8">
			        <input type="text" id="epay_alipay_pic" name="epay_alipay_pic" class="form-control"
							value="<?php echo $conf['epay_alipay_pic']?>">
		             </div>
						</div>
						<div class="form-group">
						<label class="col-lg-3 control-label">微信收款账号</label>
						<div class="col-lg-8">
							<input type="text" id="epay_wxpay_id" name="epay_wxpay_id" class="form-control"
							value="<?php echo $conf['epay_wxpay_id']?>">
						</div>
					</div>
	               <div class="form-group">
		            <label class="col-lg-3 control-label">收款二维码图片地址</label>
		            <div class="col-lg-8">
			        <input type="text" id="epay_wxpay_pic" name="epay_wxpay_pic" class="form-control"
							value="<?php echo $conf['epay_wxpay_pic']?>">
		             </div>
						</div>
					<?php }else{?>
				   <div class="form-group">
						<label class="col-lg-3 control-label">支付接口商户ID</label>
						<div class="col-lg-8">
							<input type="text" id="epay_id" name="epay_id" class="form-control"
							value="<?php echo $conf['epay_id']?>">
						</div>
					</div>
	               <div class="form-group">
		            <label class="col-lg-3 control-label">支付接口商户密钥</label>
		            <div class="col-lg-8">
			        <input type="text" id="epay_key" name="epay_key" class="form-control" value="<?php if($conf['epay_key'] != "") $key=substr($conf['epay_key'],0,8).'****************'.substr($conf['epay_key'],24,32); echo  $key;?>">
		             </div>
						</div><?php }?>
					
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-8"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/>
						</div>
					</div>
                                    <a href="https://codepay.fateqq.com/i/112173" target="_black" style="color:#2E8B57;">
                                        <font color="red" style="display: inline-block;border:1px solid red;padding-left: 2px;padding-right: 2px;">推荐</font>
                                        &nbsp;
                                       码支付注册地址 [即时到账·资金安全]>>
                                    </a>
                                    
					<br><br>
					<!--<a href="set.php?mod=epay">支付接口设置>>【提现设置 、订单查询、结算记录】(部分接口不支持后台查询，请登陆官网查询)</a>--> 
				</form>
			</div>
		</div>
	<?php
		}elseif($_GET['mod'] =='admin'){
	?>
			<div class="panel panel-info">
			<div class="panel-heading" style="background-color: #d9edf7;"><h3 class="panel-title">管理员信息配置</h3></div>
			<div class="panel-body">
				<form action="./set.php?mod=subupadmin" method="post" class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-lg-3 control-label">管理员账号</label>
						<div class="col-lg-8">
							<input type="text" id="web_admin" name="admin" class="form-control"
							value="<?php echo $conf['admin']?>" >
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">旧密码</label>
						<div class="col-lg-8">
							<input type="text" id="web_pwd" name="oldpwd" class="form-control" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">新密码</label>
						<div class="col-lg-8">
							<input type="text" id="web_pwd" name="newpwd" class="form-control" value="">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-8">
							<input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/>
						</div>
					</div>
				</form>
			</div>
		</div>
	<?php 
		}elseif($_GET['mod'] =='epay_n'){
			if(is_file('eskey.lock')){
				showmsg("无法删除！如果您是管理员，请手动删除admin/eskey.lock文件再修改！");
				exit();
			}
			$account=$_POST['account'];
			$username=$_POST['username'];
			if($account==NULL || $username==NULL){
				showmsg('保存错误,请确保每项都不为空!',3);
			} else {
				$data=get_curl($payapi.'api.php?act=change&pid='.$conf['epay_id'].'&key='.$conf['epay_key'].'&account='.$account.'&username='.$username.'&url='.$_SERVER['HTTP_HOST']);
				$arr=json_decode($data,true);
				if($arr['code']==1) {
					@file_put_contents("eskey.lock",'安装锁');
					showmsg('修改成功!');
				}else{
					showmsg($arr['msg']);
				}
			}
		}elseif($_GET['mod'] =='epay'){
			if(isset($conf['epay_id']) && isset($conf['epay_key'])){
				$purl = $payapi.'api.php?act=query&pid='.$conf['epay_id'].'&key='.$conf['epay_key'].'&url='.$_SERVER['HTTP_HOST'];
				$data=get_curl($purl);
				$arr=json_decode($data,true);
				if($arr['code']==-2) {
					showmsg('支付接口KEY校验失败！');
				}elseif(!$data){
					showmsg('获取失败，请刷新重试！');
				}
			}else{
				showmsg('你还未填写支付接口商户ID和密钥，请返回填写！');
			}

			if($arr['active']==0)showmsg('该商户已被封禁');
			$key=substr($arr['key'],0,8).'****************'.substr($arr['key'],24,32);
	?>
			<div class="panel panel-info">
			<div class="panel-heading" style="background-color: #d9edf7;"><h3 class="panel-title">支付接口设置</h3></div>
			<div class="panel-body">
				<ul class="nav nav-tabs"><li class="active"><a href="#">支付接口设置</a></li><li><a href="./set.php?mod=epay_order">订单记录</a></li><li><a href="./set.php?mod=epay_settle">结算记录</a></li></ul>
				<form action="./set.php?mod=epay_n" method="post" class="form-horizontal" role="form">
					<h4>商户信息查看：</h4>
					<div class="form-group">
						<label class="col-sm-2 control-label">商户ID</label>
						<div class="col-sm-10"><input type="text" name="pid"  value="<?php echo $arr['pid']; ?>" class="form-control" disabled/></div>
					</div><br/>
					<div class="form-group">
						<label class="col-sm-2 control-label">商户KEY</label>
						<div class="col-sm-10"><input type="text" name="key" value="<?php echo $key; ?>" class="form-control" disabled/></div>
					</div><br/>
					<div class="form-group">
						<label class="col-sm-2 control-label">商户余额</label>
						<div class="col-sm-10"><input type="text" name="money" value="<?php echo $arr['money']; ?>" class="form-control" disabled/></div>
					</div><br/>
					<h4>收款账号设置：</h4>
					<div class="form-group">
						<label class="col-sm-2 control-label">支付宝账号</label>
						<div class="col-sm-10"><input type="text" name="account" value="<?php echo $arr['account']; ?>" class="form-control"/></div>
					</div><br/>
					<div class="form-group">
						<label class="col-sm-2 control-label">支付宝姓名</label>
						<div class="col-sm-10"><input type="text" name="username" value="<?php echo $arr['username']; ?>" class="form-control"/></div>
					</div><br/>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="确定修改" class="btn btn-primary form-control"/><br/>
						</div>
					</div>
					<h4><span class="glyphicon glyphicon-info-sign"></span> 注意事项</h4>
					1.支付宝账户和支付宝真实姓名请仔细核对，一旦错误将无法结算到账！<br/>2.每笔交易会有<?php echo (100-$arr['money_rate'])?>%的手续费，这个手续费是支付宝、微信和财付通收取的，非本接口收取。<br/>3.结算是通过支付宝进行结算，每天满<?php echo $arr['settle_money']?>元自动结算，如需人工结算需要扣除<?php echo $arr['settle_fee']?>元手续费
				</form>
			</div>
		</div>
	<?php
		}elseif($_GET['mod'] =='epay_settle'){
			$data=get_curl($payapi.'api.php?act=settle&pid='.$conf['epay_id'].'&key='.$conf['epay_key'].'&limit=20&url='.$_SERVER['HTTP_HOST']);
			$arr=json_decode($data,true);c();
			if($arr['code']==-2) {
				showmsg('支付接口KEY校验失败！');
			}
			echo '<div class="panel panel-primary"><div class="panel-heading w h"><h3 class="panel-title">支付接口结算记录</h3></div>
			<div class="table-responsive">
			<table class="table table-striped">
			<thead><tr><th>ID</th><th>结算账号</th><th>结算金额</th><th>手续费</th><th>结算时间</th></tr></thead>
			<tbody>';
			foreach($arr['data'] as $res){
				echo '<tr><td><b>'.$res['id'].'</b></td><td>'.$res['account'].'</td><td><b>'.$res['money'].'</b></td><td><b>'.$res['fee'].'</b></td><td>'.$res['time'].'</td></tr>';
			}
			echo '</tbody></table></div></div>';
		}elseif($_GET['mod'] =='epay_order'){
			$data=get_curl($payapi.'api.php?act=orders&pid='.$conf['epay_id'].'&key='. $conf['epay_key'].'&limit=30&url='.$_SERVER['HTTP_HOST']);

			$arr=json_decode($data,true);
			if($arr['code']==-2) {
				showmsg('支付接口KEY校验失败！');
			}
			echo '<div class="panel panel-primary"><div class="panel-heading"><h3 class="panel-title">支付订单记录</h3></div>订单只展示前30条[<a href="set.php?mod=epay">返回</a>]
			<div class="table-responsive">
			<table class="table table-striped">
			<thead><tr><th>交易号/商户订单号</th><th>付款方式</th><th>商品名称/金额</th><th>创建时间/完成时间</th><th>状态</th></tr></thead>
			<tbody>';
			foreach($arr['data'] as $res){
				echo '<tr><td>'.$res['trade_no'].'<br/>'.$res['out_trade_no'].'</td><td>'.$res['type'].'</td><td>'.$res['name'].'<br/>￥ <b>'.$res['money'].'</b></td><td>'.$res['addtime'].'<br/>'.$res['endtime'].'</td><td>'.($res['status']==1?'<font color=green>已完成</font>':'<font color=red>未完成</font>').'</td></tr>';
			}
			echo '</tbody></table></div></div>';
		}elseif($_GET['mod']=='upimg'){
			echo '<div class="panel panel-info"><div class="panel-heading" ><h3 class="panel-title">更改首页LOGO</h3> </div><div class="panel-body">';
			if($_POST['s']==1){
				$extension=explode('.',$_FILES['file']['name']);
				if (($length = count($extension)) > 1) {
					$ext = strtolower($extension[$length - 1]);
				}
				if($ext=='png'||$ext=='gif'||$ext=='jpg'||$ext=='jpeg'||$ext=='bmp')$ext='png';
				copy($_FILES['file']['tmp_name'], ROOT.'/assets/imgs/logo.'.$ext);
				echo "成功上传文件!<br>（可能需要清空浏览器缓存才能看到效果）";
			}
			echo '<form action="set.php?mod=upimg" method="POST" enctype="multipart/form-data"><label for="file"></label><input type="file" name="file" id="file" /><input type="hidden" name="s" value="1" /><br><input type="submit" class="btn btn-primary btn-block" value="确认上传" /></form>*请上传300*82的png格式的图片<br><br>现在的图片：<br><img src="../assets/imgs/logo.png?r='.rand(10000,99999).'" style="max-width:100%">';
			echo '</div></div>';
		}elseif($_GET['mod']=='upBgimg'){
			echo '<div class="panel panel-primary"><div class="panel-heading"><h3 class="panel-title">更改首页背景图片</h3> </div><div class="panel-body">';
			if($_POST['s']==1){
				$extension=explode('.',$_FILES['file']['name']);
				if (($length = count($extension)) > 1) {
					$ext = strtolower($extension[$length - 1]);
				}
				if($ext=='png'||$ext=='gif'||$ext=='jpg'||$ext=='jpeg'||$ext=='bmp')$ext='jpg';
				copy($_FILES['file']['tmp_name'], ROOT.'/assets/imgs/400x400a0a0.'.$ext);
				echo "成功上传文件!<br>（可能需要清空浏览器缓存才能看到效果）";
			}
			echo '<form action="set.php?mod=upBgimg" method="POST" enctype="multipart/form-data"><label for="file"></label><input type="file" name="file" id="file" /><input type="hidden" name="s" value="1" /><br><input type="submit" class="btn btn-primary btn-block" value="确认上传" /></form>*请上传适合于平铺的图片<br><br>现在的图片：<br><img src="../assets/imgs/400x400a0a0.jpg?r='.rand(10000,99999).'" style="max-width:100%">';
			echo '</div></div>';
		}elseif($_GET['mod'] == "subupadmin"){
			$user = $_POST['admin'];
			$oldpwd = $_POST['oldpwd'];
			$newpwd = $_POST['newpwd'];
			if(strlen($user) < 3){
				showmsg("管理用户名长度不能少于3个字符！<br><a href='./set.php?mod=admin'>返回</a>");
			}
			if(strlen($newpwd) < 6){
				showmsg("密码长度不能少于6个字符！<br><a href='./set.php?mod=admin'>返回</a>");
			}
			if(md5($oldpwd.$password_hash) != $conf['pwd']){
				showmsg("旧密码错误！<br><a href='./set.php?mod=admin'>返回</a>");
			}
			$pwd = md5($newpwd.$password_hash);
			$DB->query("update `safwl_config` set `safwl_v` ='{$pwd}' where `safwl_k`='pwd'");
			$DB->query("update `safwl_config` set `safwl_v` ='{$user}' where `safwl_k`='admin'");
			showmsg("修改成功！<br><a href='./set.php?mod=admin'>返回</a>",1);
		}else{
			showmsg("请求失败");
		}
	?>
	</div>

<script>
	var items = $("select[default]");
	for (i = 0; i < items.length; i++) {
		$(items[i]).val($(items[i]).attr("default"));
	}
	function showepayurlinput(v){
		if(v == 9){

			$("#epay_url_div").show(500);
		}else{
			$("#epay_url_div").hide(500);
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