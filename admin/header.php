<?php
/*
 * 源码来源：52站长论坛
 * QQ:122564034
 * 最新源码下载地址：qin52.com
 * */  

    include '../safwl/common.php';
    @header('Content-Type: text/html; charset=UTF-8');
    if($islogin==1){
        if( empty($_SESSION['swxcjebbs']) ||  $_SESSION['swxcjebbs'] != md5($_COOKIE['admin_token']."****")){
              exit("<script language='javascript'>window.location.href='./login.php';</script>");
        }
		$r3 =$DB->count("select COUNT(id) from safwl_km");
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8"/>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title><?=$title?> - 后台管理中心</title>
    <link rel="stylesheet" href="../assets/css/c/layui.css" media="all">
    <link rel="stylesheet" href="../assets/css/c/admin.css" media="all">
    <script type="text/javascript" src="../assets/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="../assets/jquery/jquery.cookie.js"></script>
    <script type="text/javascript" src="../assets/jquery/jquery.md5.js"></script>
    <script type="text/javascript" src="../assets/js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../assets/layer/layer.js"></script>
    <script type="text/javascript" src="../assets/js/safwl.js"></script>
	<script src="../assets/js/layui.js"></script>
	
	
    <!--[if lt IE 9]>
        <script src="//cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  
</head>
<body class="layui-layout-body">

<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <!-- 头部区域 -->
            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item layadmin-flexible" lay-unselect>
                    <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                        <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="/" target="_blank" title="前台">
                        <i class="layui-icon layui-icon-website"></i>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;" layadmin-event="refresh" title="刷新">
                        <i class="layui-icon layui-icon-refresh-3"></i>
                    </a>
                </li>

            </ul>
            <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">

                <li class="layui-nav-item" lay-unselect>
                    <a lay-href="http://www.qin52.com" layadmin-event="message" lay-text="官方网站" title="官方网站">
                        <i class="layui-icon layui-icon-dialogue"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" >
                    <a href="https://jq.qq.com/?_wv=1027&k=5AgdVw8" target="_blank">
                        <i class="layui-icon layui-icon-theme"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="note">
                        <i class="layui-icon layui-icon-note"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="fullscreen">
                        <i class="layui-icon layui-icon-screen-full"></i>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;">
                        <cite>安全退出</cite>
                    </a>
                    <dl class="layui-nav-child">
                        <dd style="text-align: center;"><a href="./login.php?logout">退出</a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item layui-hide-xs" >
                    <a href="javascript:;" ><i class="layui-icon layui-icon-more-vertical"></i></a>
                </li>
                <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm">
                    <a href="javascript:;" ><i class="layui-icon layui-icon-more-vertical"></i></a>
                </li>
            </ul>
        </div>

        <!-- 侧边菜单 -->
        <div class="layui-side layui-side-menu">
            <div class="layui-side-scroll">
                <div class="layui-logo" lay-href="">
                    <span>后台管理系统</span>
                </div>

                <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">
                    <li data-name="home" class="layui-nav-item">
                        <a href="javascript:;" lay-tips="主页" lay-direction="2">
                            <i class="layui-icon layui-icon-home"></i>
                            <cite>主页</cite>
                        </a>
                        <dl class="layui-nav-child">
							<dd data-name="console" >
                                <a href="/" target="_blank">前台首页</a>
                            </dd>
                        </dl>
                    </li>

                    <li data-name="sycm" class="layui-nav-item">
                        <a href="javascript:;" lay-tips="订单管理" lay-direction="2">
                            <i class="layui-icon layui-icon-chart-screen"></i>
                            <cite>订单管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd >
                                <a lay-href="./list.php">订单管理</a>
                            </dd>
							<dd>
                                <a lay-href="./list_q.php">手动发货</a>
                            </dd>
							<dd>
                                <a lay-href="./list_a.php">邮寄订单</a>
                            </dd>
							<dd>
                                <a lay-href="./list_52.php">代充订单</a>
                            </dd>
                        </dl>
                    </li>
                    <li data-name="component" class="layui-nav-item">
                        <a href="javascript:;" lay-tips="卡密管理" lay-direction="2">
                            <i class="layui-icon layui-icon-form"></i>
                            <cite>卡密管理<span class="layui-badge" id="Pending-1"><?php echo $r3;?></span></cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd data-name="anim">
                                <a lay-href="./kmlist.php">卡密列表</a>
                            </dd>
                            <dd data-name="anim">
                                <a lay-href="./addkm.php">添加卡密</a>
                            </dd>
                        </dl>
                    </li>

                    <li data-name="app" class="layui-nav-item">
                        <a href="javascript:;" lay-tips="商品管理" lay-direction="2">
                            <i class="layui-icon layui-icon-app"></i>
                            <cite>商品管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd>
                                <a lay-href="./clist.php">商品管理</a>
                            </dd>
							<dd>
                                <a lay-href="./clist.php?my=add">添加商品</a>
                            </dd>
							<dd>
                                <a lay-href="./typelist.php">商品分类管理</a>
                            </dd>
                        </dl>
                    </li>
                    <li data-name="senior" class="layui-nav-item">
                        <a href="javascript:;" lay-tips="代理管理" lay-direction="2">
                            <i class="layui-icon layui-icon-senior"></i>
                            <cite>代理管理</cite>
                        </a>
                        <dl class="layui-nav-child">
						    <dd>
                                <a lay-href="./other-set.php?act=accvtion">代理配置</a>
                            </dd>
							<dd>
                                <a lay-href="./accvtionlist.php">代理列表</a>
                            </dd>
							<dd>
                                <a lay-href="http://www.qin52.com">52站长论坛</a>
                            </dd>
                        </dl>
                    </li>
                    <li data-name="user" class="layui-nav-item">
                        <a href="javascript:;" lay-tips="优惠券管理" lay-direction="2">
                            <i class="layui-icon layui-icon-gift"></i>
                            <cite>优惠券管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd>
                                <a lay-href="./other-set.php?act=coupon">优惠券配置</a>
                            </dd>
							<dd>
                                <a lay-href="./couponlist.php">优惠券列表</a>
                            </dd>
                        </dl>
                    </li>
					<li data-name="template" class="layui-nav-item">
                        <a href="javascript:;" lay-tips="模板管理" lay-direction="2">
                            <i class="layui-icon layui-icon-templeate-1"></i>
                            <cite>模板管理</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="./other-set.php?act=view">网站模板</a></dd>
							<dd><a lay-href="./set.php?mod=upimg">修改logo</a></dd>
							<dd><a lay-href="./set.php?mod=upBgimg">修改背景</a></dd>
                        </dl>
                    </li>
					<li data-name="set" class="layui-nav-item">
                        <a href="javascript:;" lay-tips="管理员账号配置" lay-direction="2">
                            <i class="layui-icon layui-icon-user"></i>
                            <cite>修改密码</cite>
                        </a>
                        <dl class="layui-nav-child">
                          <dd><a lay-href="./set.php?mod=admin">管理员账号配置</a></dd>
                        </dl>
                    </li>
                    <li data-name="set" class="layui-nav-item">
                        <a href="javascript:;" lay-tips="系统设置" lay-direction="2">
                            <i class="layui-icon layui-icon-set"></i>
                            <cite>系统设置</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="./set.php?mod=site">网站信息配置</a></dd>
                            <dd><a lay-href="./other-set.php?act=phone">手机短信配置</a></dd>
                            <dd><a lay-href="./other-set.php?act=email">发件邮箱配置</a></dd>
                            <dd><a lay-href="./other-set.php?act=paytype">支付接口开关</a></dd>
							 <dd><a lay-href="./set.php?mod=pay">支付接口配置</a></dd>
							 <dd><a lay-href="./blacklist.php">黑名单管理</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>
        </div>
       
        <!-- 页面标签 -->
        <div class="layadmin-pagetabs" id="LAY_app_tabs">
            <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-down">
                <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;"></a>
                        <dl class="layui-nav-child layui-anim-fadein">
                            <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                            <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                            <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>
            <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                <ul class="layui-tab-title" id="LAY_app_tabsheader">
                    <li class="layui-this"><i class="layui-icon layui-icon-home"></i></li>
                </ul>
            </div>
        </div>


        <!-- 主体内容 -->
        <div class="layui-body" id="LAY_app_body">
            <div class="layadmin-tabsbody-item layui-show">
                <iframe src="./qin52.php" frameborder="0" class="layadmin-iframe"></iframe>
            </div>
        </div>

        <!-- 辅助元素，一般用于移动设备下遮罩 -->
        <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
</div>
<script>
    layui.config({
        base: '/static/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use('index');
</script>
<?php 
    }else{
        exit("<script language='javascript'>window.location.href='./login.php';</script>");
    }  
?>
<!-- 
**********************************************
          /*
 * 源码来源：52站长论坛
 * QQ:122564034
 * 最新源码下载地址：qin52.com
 * */         ~~      
**********************************************
-->