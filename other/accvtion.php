<?php

?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?= $conf['title'] ?> - <?php echo $conf['ftitle']; ?></title>
	<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="../assets/css/nifty.min.css">
	<script type="text/javascript" src="../assets/jquery/jquery.js"></script>
	<script type="text/javascript" src="../assets/bootstrap/js/bootstrap.min.js"></script>
  	<!--[if lt IE 9]>
	    <script src="//lib.baomitu.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	    <script src="//lib.baomitu.com/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<div class="container" style="padding-top:50PX;">
	<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;">
		<div class="panel panel-primary">
			<div class="panel-heading"><h3 class="panel-title">访问公告</h3></div>
			<div class="panel-body">
			<?=$conf['accvtion_notice']?>
			</div>
		</div>
	</div>
</div>
<div class="container" style="padding-top:40PX;">
	<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;">
		<div class="panel panel-primary">
			<div class="panel-heading"><h3 class="panel-title">请输入访问密码</h3></div>
			<div class="panel-body">
				<form action="" method="post" class="form-horizontal" role="form">
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                                                <input type="hidden" name="accvtion_check" value="<?=time()?>">
						<input type="password" name="pass" class="form-control" placeholder="输入您的访问密码" required="required"/>
					</div><br/>
					<div class="form-group">
						<div class="col-xs-12" style="height: 30px;"><input type="submit" value="验证密码" class="btn btn-primary form-control"/></div>
					</div>
				</form>
				
			</div>
			
		</div>
	</div>
</div>
<div class="container" style="padding-top:40PX;">
	<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;">
		<div class="panel panel-primary">
			<div class="panel-heading"><h3 class="panel-title">注意事项</h3></div>
			<div class="panel-body">
		
                           <?=$conf['accvtion_notice2']?>
                        </div>
		</div>
	</div>
</div>

 