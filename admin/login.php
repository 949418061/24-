<?php
/**
 * 登录
**/
$verifycode = 1;//验证码开关

if(!function_exists("imagecreate") || !file_exists('code.php'))$verifycode=0;
include '../safwl/common.php';
@header('Content-Type: text/html; charset=UTF-8');
if(isset($_POST['user']) && isset($_POST['pass'])){
	$user=daddslashes($_POST['user']);
	$pass=daddslashes($_POST['pass']);
	$code=daddslashes($_POST['code']);
	$pass = md5($pass.$password_hash);
	if ($verifycode==1 && (!$code || strtolower($code) != $_SESSION['vc_code'])) {
		unset($_SESSION['vc_code']);
		exit("<script language='javascript'>alert('验证码错误！');history.go(-1);</script>");
	}elseif($user == $conf['admin'] &&  $pass== $conf['pwd']) {
		unset($_SESSION['vc_code']);
		$session=md5($user.$pass.$password_hash);
		$token=authcode("{$user}\t{$session}", 'ENCODE', SYS_KEY);
		setcookie("admin_token", $token, time() + 604800);
		wsyslog("登陆后台成功!","登陆IP:".real_ip());
                $_SESSION['swxcjebbs'] = md5($token."****");
		exit("<script language='javascript'>alert('登陆管理中心成功！');window.location.href='./';</script>");
	}else {
		unset($_SESSION['vc_code']);
		exit("<script language='javascript'>alert('用户名或密码不正确！');history.go(-1);</script>");
	}
}elseif(isset($_GET['logout'])){
    unset($_SESSION['swxcjebbs']);
	setcookie("admin_token", "", time() - 604800);
	echo ("<script language='javascript'>alert('您已成功注销本次登陆！');window.location.href='./login.php';</script>");
}elseif($islogin==5){
	exit("<script language='javascript'>alert('您已登陆！');window.location.href='./';</script>");
}
$title='用户登录';
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title><?php echo $title ?></title>
	<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/nifty.min.css">
	<script type="text/javascript" src="../assets/jquery/jquery.js"></script>
	<script type="text/javascript" src="../assets/bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="../assets/css/login.css" media="all">
	<style>
        html {
            background-color: #fff;
            color: #666;
            background-image: url("../assets/imgs/loginbg.png");
        }
    </style>
  	<!--[if lt IE 9]>
	    <script src="//lib.baomitu.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	    <script src="//lib.baomitu.com/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body style="background-color: rgba(255,255,255,.15);">
<div class="container layadmin-user-login layadmin-user-display-show layui-container" style="padding-top:70px;">
	<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;padding: 50px;box-shadow: 0 1px 20px 5px rgba(0,0,0,.05);">
		<div class="layadmin-user-login-main">
			<div class="layadmin-user-login-box layadmin-user-login-header"><h2 class="">管理员登陆</h2></div>
			<div class="panel-body">
				<form action="./login.php" method="post" class="form-horizontal" role="form">
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
						<input type="text" name="user" value="<?php echo @$_POST['user'];?>" class="form-control" placeholder="用户名" required="required"/>
					</div><br/>
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
						<input type="password" name="pass" class="form-control" placeholder="密码" required="required"/>
					</div><br/>
				<!-- 验证码 -->
				<?php if($verifycode==1){?>
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-adjust"></span></span>
						<input type="text" class="form-control input-lg" name="code" placeholder="输入验证码" autocomplete="off" required>
						<span class="input-group-addon" style="padding: 0">
							<img src="./code.php?r=<?php echo time();?>"height="35"onclick="this.src='./code.php?r='+Math.random();" title="点击更换验证码">
						</span>
					</div><br/>
				<?php }?>
					<div class="form-group">
						<div class="col-xs-12" style="height: 30px;"><input type="submit" value="登陆" class="btn btn-primary form-control"/></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>

</html>
<!-- 
**********************************************
/*
 * 源码来源：52站长论坛
 * QQ:122564034
 * 最新源码下载地址：qin52.com
 * */ 
**********************************************
-->