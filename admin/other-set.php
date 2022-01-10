<?php
/**
 * 系统设置
**/
$title='网站其它配置';
include './head.php';
?>

   <div class="layui-row" style="padding: 5px">
<?php 
if(isset($_POST['submit'])) {
    foreach ($_POST as $k => $value) {
        if($k=='pwd')continue;
        $value=daddslashes($value);
        $DB->query("insert into safwl_config set `safwl_k`='{$k}',`safwl_v`='{$value}' on duplicate key update `safwl_v`='{$value}'");
    }
    showmsg('修改成功！',1);
    exit();
}
if($_GET['act'] == "email"){
?>
	<div class="panel panel-info">
			<div class="panel-heading" style="background-color: #d9edf7;"><h3 class="panel-title">邮箱配置</h3></div>
		<div class="panel-body">
  			<form action="" method="post" class="form-horizontal" role="form">
                             <div class="form-group">
	  				<label class="col-sm-2 control-label">发生邮件:</label>
	  				<div class="col-sm-10">
                                            <select class="form-control" name="sendemail">
                                                <option value="1" <?=$conf['sendemail']==1?"selected":""?> >开</option>
                                                <option value="0" <?=$conf['sendemail']==0?"selected":""?>>关</option>
                                            </select>
                                            <small>购买成功后发生订单到用户邮箱</small>
                                        </div>
                                </div><br/>
				<div class="form-group">
	  				<label class="col-sm-2 control-label">邮箱SMTP服务器:</label>
	  				<div class="col-sm-10"><input type="text" name="mail_stmp" value="<?php echo $conf['mail_stmp']; ?>" class="form-control" required/></div>
				</div><br/>
     			<div class="form-group">
	  				<label class="col-sm-2 control-label">邮箱SMTP端口:</label>
	  				<div class="col-sm-10"><input type="text" name="mail_port" value="<?php echo $conf['mail_port']; ?>" class="form-control"/></div>
				</div><br/>
				<div class="form-group">
	  				<label class="col-sm-2 control-label">邮箱账号:</label>
	  				<div class="col-sm-10"><input type="text" name="mail_name" value="<?php echo $conf['mail_name']; ?>" class="form-control"/></div>
				</div><br/>
				<div class="form-group">
	  				<label class="col-sm-2 control-label">邮箱授权码:</label>
	  				<div class="col-sm-10"><input type="text" name="mail_pwd" value="<?php echo $conf['mail_pwd']; ?>" class="form-control"/></div>
				</div><br/>
				<div class="form-group">
	  				<label class="col-sm-2 control-label">邮件名称:</label>
	  				<div class="col-sm-10"><input type="text" name="mail_title" value="<?php echo $conf['mail_title']; ?>" class="form-control"/></div>
				</div><br/>
                                <div class="form-group">
	  				<label class="col-sm-2 control-label">邮件内容模板:</label>
	  				<div class="col-sm-10">
                                            
                                            <textarea name="mail_content" class="form-control" rows="4"><?php echo $conf['mail_content']; ?></textarea>
                                            <div class="panel panel-default" style="background-color: gray;color:white">
                                                <div class="panel-body">
                                                    模板支持HTML代码,如不动请勿随意乱改动!以下是模板变量:<br>
                                                     网站标题：{@title}<br>
                                                    商品名称：{@goodsname}<br>
                                                    商品价格：{@goodsmoney}<br>
                                                    成交价格：{@goodsokmoney}<br>
                                                    订单编号：{@out_trade_no}<br>
                                                    交易单号：{@trade_no}<br>
                                                    交易时间：{@time}<br>
                                                    客户QQ：{@qq}<br>
                                                    客户QQ昵称：{@qqnickname}<br>
                                                    卡密：{@kmlist}<br>
                                                    
                                                </div>
                                            </div>
                                        </div>
				</div>
				<br/>
                               
				<div class="form-group">
	  				<div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/>
	 			</div>
			</div>
  		</form>
    	<div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="button" value="发送一封测试邮件" class="btn btn-info form-control" id="email-send-test"/><br/><br/>
	 		</div>
		</div>
		<br/><br/>
		<div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
               <a class="btn btn-info form-control" href="http://www.qin52.com/thread-598546-1-1.html" target="_blank">点击查看教程</a>
	 		</div>
		</div>
		<br/><br/>
   		<div class="form-group">
       		<div class="col-sm-offset-2 col-sm-10" style="display: none;" id="tis-div"> <div class="alert alert-info" id="tis-msg">信息！请注意这个信息。 </div></div></div><br/><br/>
<pre>
<font color="green">
如果为QQ邮箱需先开通SMTP，且要填写QQ邮箱独立密码。
邮箱SMTP服务器可以百度一下，例如QQ邮箱的即为 smtp.qq.com。
邮箱SMTP端口默认为25，SSL的端口是465。</font> 
<hr>
关于邮件发送失败问题的解决方法：
1.检查空间是否未开启fsockopen函数支持，如果未开启可以到空间控制面板开启或联系主机商，
2.可能发信邮箱不支持smtp发信，可以到邮箱的设置页面进行开启。另外新注册的网易邮箱普遍不支持发信，老邮箱才可以，如果遇到这种情况请到淘宝买老邮箱账号或者使用139邮箱。
3.如果发信邮箱是QQ邮箱要申请授权码，需要到QQ邮箱网页版的设置页面进行设置并申请授权码，SMTP密码一栏即填写授权码（非QQ密码），端口是465，并开启SSL模式。
4.如果用户设置的收信邮箱为QQ邮箱，秒赞网失效提醒邮件有很大几率被腾讯屏蔽（直接做退信处理并非在垃圾信箱），所以用户注册时推荐使用网易邮箱或139邮箱做为收信邮箱。
        </pre>
			</div>
		</div>
<script src="/assets/js/safwlfk.js"></script>
<?php
        }elseif($_GET['act'] == "phone"){
?>
	<div class="panel panel-info">
			<div class="panel-heading" style="background-color: #d9edf7;"><h3 class="panel-title">手机短信配置</h3></div>
		<div class="panel-body">
  			<form action="" method="post" class="form-horizontal" role="form">
					<div class="form-group">
						<div class="col-sm-12"><div class="panel bg-success">
                                                <div class="panel-body text-center"><a href="https://www.mysubmail.com" target="_blank">>>>手机短信接口注册<<<</a></div></div></div>
					</div>
                             <div class="form-group">
	  				<label class="col-sm-2 control-label">发送短信:</label>
	  				<div class="col-sm-10">
                                            <select class="form-control" name="sendphonedx">
                                                <option value="1" <?=$conf['sendphonedx']==1?"selected":""?> >开</option>
                                                <option value="0" <?=$conf['sendphonedx']==0?"selected":""?>>关</option>
                                            </select>
                                         
                                        </div>
                                </div><br/>
				<div class="form-group">
	  				<label class="col-sm-2 control-label">APP_ID:</label>
	  				<div class="col-sm-10"><input type="text" name="dx_appid" value="<?php echo $conf['dx_appid']; ?>" class="form-control" required/></div>
				</div><br/>
     			<div class="form-group">
	  				<label class="col-sm-2 control-label">APP_KEY:</label>
	  				<div class="col-sm-10"><input type="text" name="dx_appkey" value="<?php echo $conf['dx_appkey']; ?>" class="form-control"/></div>
				</div><br/>
				
                                <div class="form-group">
	  				<label class="col-sm-2 control-label">短信内容模板:</label>
	  				<div class="col-sm-10">
                                            
                                            <textarea name="dx_content" class="form-control" rows="4"><?php echo $conf['dx_content']; ?></textarea>
                                            <div class="panel panel-default" style="background-color: gray;color:white">
                                                <div class="panel-body">
                                                    内容模板必须与短信平台模板一致,如不动请勿随意乱改动!以下是模板变量:<br>
                                                    网站标题：{@title}<br>
                                                    商品名称：{@goodsname}<br>
                                                    商品价格：{@goodsmoney}<br>
                                                    成交价格：{@goodsokmoney}<br>
                                                    订单编号：{@out_trade_no}<br>
                                                    交易单号：{@trade_no}<br>
                                                    交易时间：{@time}<br>
                                                    客户QQ：{@qq}<br>
                                                    客户QQ昵称：{@qqnickname}<br>
                                                    卡密：{@kmlist}<br>
                                                    
                                                </div>
                                            </div>
                                        </div>
				</div><br/>
                               
				<div class="form-group">
	  				<div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/><br/>
	 			</div>
			</div>
  		</form>
    	
   	

			</div>
		</div>
    
<?php
        }elseif($_GET['act'] == "paytype"){
?>
	<div class="panel panel-info">
			<div class="panel-heading" style="background-color: #d9edf7;"><h3 class="panel-title">支付接口配置</h3></div>
		<div class="panel-body">
  			<form action="" method="post" class="form-horizontal" role="form">
				<div class="form-group">
	  				<label class="col-sm-2 control-label">支付宝:</label>
	  				<div class="col-sm-10">
                                            <select class="form-control" name="switch_alipay">
                                                <option value="1" <?=$conf['switch_alipay']==1?"selected":""?> >开</option>
                                                <option value="0" <?=$conf['switch_alipay']==0?"selected":""?>>关</option>
                                            </select>
                                        </div>
				</div><br/>
                                <div class="form-group">
	  				<label class="col-sm-2 control-label">微信支付:</label>
	  				<div class="col-sm-10">
                                            <select class="form-control"  name="switch_wxpay">
                                                <option value="1" <?=$conf['switch_wxpay']==1?"selected":""?> >开</option>
                                                <option value="0" <?=$conf['switch_wxpay']==0?"selected":""?>>关</option>
                                            </select>
                                        </div>
				</div><br/>
                                <div class="form-group">
	  				<label class="col-sm-2 control-label">QQ钱包:</label>
	  				<div class="col-sm-10">
                                            <select class="form-control"  name="switch_qqpay">
                                                 <option value="1" <?=$conf['switch_qqpay']==1?"selected":""?> >开</option>
                                                <option value="0" <?=$conf['switch_qqpay']==0?"selected":""?>>关</option>
                                            </select>
                                        </div>
				</div><br/>
                                 <div class="form-group">
	  				<label class="col-sm-2 control-label">财付通:</label>
	  				<div class="col-sm-10">
                                            <select class="form-control"  name="switch_tenpay">
                                                <option value="1" <?=$conf['switch_tenpay']==1?"selected":""?> >开</option>
                                                <option value="0" <?=$conf['switch_tenpay']==0?"selected":""?>>关</option>
                                            </select>
                                        </div>
				</div><br/>
                                
				<div class="form-group">
	  				<div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="保存修改" class="btn btn-primary form-control"/><br/>
	 			</div>
			</div>
  		</form>

            </div>
    </div>
    
<?php
        }elseif($_GET['act'] == "viewconf"){
            if(!file_exists(ROOT."template/".$conf['view'].'/inc.php')){
                 showmsg('模板无需配置！',2);
            }
            if(!empty($_GET['submit']) && $_GET['submit'] == "save"){

                $array = "";
                foreach ($_POST as $key => $value) {

                    if($array!="") $array.=",";
                    //$array .= '"'.$key.'" => "'.$value.'"';
                    $array .="'".$key."'=>'".$value."'";
                }
            
                $array = '<?php return array('.$array.') ?>';
                $array =  str_replace("\\", "", $array);
                file_put_contents(ROOT."template/".$conf['view'].'/inc.php', $array);
                 showmsg('保存配置成功！',1);
                   exit();
            }
            
            $inc = include ROOT."template/".$conf['view'].'/inc.php';
            ?>
              
		<div class="panel panel-info">
			<div class="panel-heading" style="background-color: #d9edf7;"><h3 class="panel-title">首页模板参数配置</h3></div>
        <div class="panel-body" >
            <form action="?act=viewconf&submit=save" method="POST"  class="form-horizontal" role="form">
            <?php
            
               foreach ($inc as $key => $value) {
                   ?>
                  	<div class="form-group">
				<label class="col-sm-2 control-label"><?=$key?>:</label>
				<div class="col-sm-10">
                                    <textarea class="form-control" name="<?=$key?>" required rows="5"><?=$value?></textarea>
                                </div>
			</div><br/>
                    <?php
               }
            ?>
                           <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                  <font color='green'>配置中不要出现单引号（'）</font>
                    </div>
              </div>
                        
            <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="保存配置" class="btn btn-success form-control"/><br/>
                    </div>
              </div>
                </form>
        </div>
               </div>
                
             <?php
         
        }elseif($_GET['act'] == "accvtion"){
            ?>
	<div class="panel panel-info">
			<div class="panel-heading" style="background-color: #d9edf7;"><h3 class="panel-title">代理系统配置</h3></div>
		<div class="panel-body">
  			<form action="" method="post" class="form-horizontal" role="form">
				<div class="form-group">
	  				<label class="col-sm-2 control-label">开启代理访问:</label>
	  				<div class="col-sm-10">
                                            <select class="form-control" name="isaccvtion">
                                                <option value="1" <?=$conf['isaccvtion']==1?"selected":""?> >开</option>
                                                <option value="0" <?=$conf['isaccvtion']==0?"selected":""?>>关</option>
                                            </select>
                                        </div>
				</div><br/>
                               <div class="form-group">
						<label class="col-sm-2 control-label">访问公告</label>
						<div class="col-sm-10"><textarea class="form-control" id="web_notice2" name="accvtion_notice" rows="5"><?php echo htmlspecialchars($conf['accvtion_notice']);?></textarea></div>
				</div><br/>
                                <div class="form-group">
						<label class="col-sm-2 control-label">注意事项</label>
						<div class="col-sm-10"><textarea class="form-control" id="web_notice2" name="accvtion_notice2" rows="5"><?php echo htmlspecialchars($conf['accvtion_notice2']);?></textarea></div>
				</div><br/>
                                 <div class="form-group">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-10">
                                           <input type="submit" name="submit" value="保存修改" class="btn btn-primary form-control"/>
                                                </div>
				</div><br/>
                                
				  <div class="form-group">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-10">
                                                    <a href="accvtionlist.php" class="btn btn-info form-control">代理密码列表</a>
                                                </div>
				</div><br/>
                                    
                                    
			</div>
  		</form>

            </div>
    </div>
                
                <?php
        }elseif($_GET['act'] == "coupon"){
            ?>
               
	<div class="panel panel-info">
			<div class="panel-heading" style="background-color: #d9edf7;"><h3 class="panel-title">优惠券系统配置</h3></div>
		<div class="panel-body">
  			<form action="" method="post" class="form-horizontal" role="form">
				<div class="form-group">
	  				<label class="col-sm-2 control-label">优惠券系统:</label>
	  				<div class="col-sm-10">
                                            <select class="form-control" name="iscoupon">
                                                <option value="1" <?=$conf['iscoupon']==1?"selected":""?> >开启</option>
                                                <option value="0" <?=$conf['iscoupon']==0?"selected":""?>>关闭</option>
                                            </select>
                                        </div>
				</div><br/>
                               <div class="form-group">
						<label class="col-sm-2 control-label">优惠券长度：</label>
						<div class="col-sm-10">
                                                    <input type="number" value="<?=$conf['coupon_ka_num']?>" min="5" max="15" name="coupon_ka_num"  class="form-control" />
                                                    填数值：5-15，最短5，最长15
                                                </div>
				</div><br/>
                              
                                 <div class="form-group">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-10">
                                           <input type="submit" name="submit" value="保存修改" class="btn btn-primary form-control"/>
                                                </div>
				</div><br/>
                                
				  <div class="form-group">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-10">
                                                    <a href="couponlist.php" class="btn btn-info form-control">进入优惠券系统</a>
                                                </div>
				</div><br/>
                                    
                                    
			</div>
  		</form>

            </div>
    </div>
                
                <?php
        }elseif($_GET['act'] == "view"){
?>
<div class="panel panel-info">
			<div class="panel-heading" style="background-color: #d9edf7;"><h3 class="panel-title">首页模板设置</h3></div>
        <div class="panel-body" >
            <div class="panel panel-default">
    <div class="panel-body">
        <div class="col-sm-6 col-md-6">
                <div class="thumbnail">
                   <img src="<?=$siteurl."template/".$conf['view']."/".$conf['view'].".png"?>" 
                    alt="模板演示图">
                   <div class="caption">
                       <?php
                        $json = file_get_contents( ROOT."template/".$conf['view'].'/author.conf');
                         $json = (array)json_decode($json);
                       ?>
                       <h3><?=$json['temp_name']?></h3>
                       <p><?=$json['temp_summary']?></p>
                     
                   </div>
                </div>
               
           </div>
        <div style="margin-top: 20px;color:black">
            <span style="color:red;font-weight: bold">模板作者：52站长论坛</span><br>
            <span style="color:blue">更新时间：2020-10-14<br>
            <a style="color:pink" href="<?=$json['temp_gw']?>">52站长论坛官网：qin52.com</a><br>
            <span  style="color:gold">模板介绍：<br><?= str_replace("·", "<br>", $json['temp_description'])?></span><br>
            <?php
            if(file_exists( ROOT."template/".$conf['view'].'/inc.php')){
                echo '<br><a  style="color:black"  class="btn btn-info" href="?act=viewconf">模板配置</a>';
            }
            ?>  
        </div>
    </div>
</div>
                 
            
            <?php
            $temp_path = ROOT."template/";
            $handle = opendir($temp_path);
             $i = 1;
            while(($fl = readdir($handle)) !== false && $i <= 4){
                 $temp = $dir.DIRECTORY_SEPARATOR.$fl;
                 if(is_dir($temp) && $fl!='.' && $fl != '..') break;
                 if($fl!='.' && $fl != '..' && !strexists($temp, ".")){
                        $json = file_get_contents($temp_path.$temp.'/author.conf');
                        $json = (array)json_decode($json);
                   ?>
                    <div class="col-sm-6 col-md-6">
                <div class="thumbnail">
                   <img src="<?=$siteurl."template/".$json['temp_dir']."/".$json['temp_dir'].".png"?>" 
                    alt="模板演示图">
                   <div class="caption">
                       <h3><?=$json['temp_name']?></h3>
                       <p><?=$json['temp_summary']?></p>
                       <p>
                           <?php
                           if($conf['view'] == $json['temp_dir']){
                           ?>
                           <a  class="btn btn-primary" role="button" style="width: 100%;background-color: gray;border: 0px;">
                               当前使用
                           </a>   
                                <?php
                           }else{
                               ?>
                           <button class="btn btn-primary" role="button" style="width: 100%;background-color: #436EEE" onclick="temp_change('<?=$json['temp_dir']?>')">
                               使用该模板
                           </button>   
                                <?php
                           }
                           ?>
                           
                         
                       </p>
                   </div>
                </div>
           </div>   
                    <?php
                 }
            }
            ?>
            
  				
		</div>
	</div>
<?php 
	}
?>
    </div>
</div>
<script>
function temp_change(template){
var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=settemp_change",
			data : {"template":template},
			dataType : 'json',
			success : function(data) {
				 layer.close(ii);
				if(data.code == 1){
					layer.msg(data.msg);
					location.reload();
					
				}else{
					layer.msg(data.msg);
					return false;
				}
			},
			error:function(data){
				 layer.close(ii);
				layer.msg('系统错误！');
				return false;
				}
		})
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