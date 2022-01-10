<?php
$title='邮件发送';
include './head.php';
$trade_no = $_GET['trade_no'];
$gid = $_GET['gid'];
$out_trade_no = $_GET['out_trade_no'];
?>

	<div class="layui-row" style="padding: 5px">
<div class="panel panel-primary">
			<div class="panel-heading"><h3 class="panel-title">邮件发送配置</h3></div>
			<div class="panel-body">
				<form action="/safwl/a.php" method="post" class="form-horizontal" role="form">
					<h3>快递内容设置</h3>
                                        <hr>
                                        
                                        <div class="form-group" style="display:none;">
						<label class="col-sm-2 control-label">商品订单号</label>
						<div class="col-sm-10"><input type="text" name="trade_no"  value="<?php echo  $trade_no; ?>" class="form-control" required/></div>
					</div>
					<div class="form-group" style="display:none;">
						<label class="col-sm-2 control-label">商品id</label>
						<div class="col-sm-10"><input type="text" name="gid"  value="<?php echo $_GET['gid'];  ?>" class="form-control" required/></div>
					</div>
					<div class="form-group" style="display:none;">
						<label class="col-sm-2 control-label">商品订单号</label>
						<div class="col-sm-10"><input type="text" name="out_trade_no"  value="<?php echo $out_trade_no;  ?>" class="form-control"/></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">快递公司</label>
						<div class="col-sm-10"><input type="text" name="ke"  value="" class="form-control"/></div>
					</div><br/>
					<div class="form-group">
						<label class="col-sm-2 control-label">快递单号</label>
						<div class="col-sm-10"><input type="text" name="kaa"  value="" class="form-control"/></div>
					</div><br/>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="确认发送" class="btn btn-primary form-control"/><br/>
						</div>
					</div>
				</form>
			</div>
		