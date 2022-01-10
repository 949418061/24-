 <?php
$title='后台首页';
include './head.php';
$r1 = $DB->count("SELECT COUNT(id) from safwl_order where date_sub(curdate(),interval 7 day) < date(benTime)");
$r2 = $DB->count("SELECT COUNT(id) from safwl_order  where sta = 1");
$r3 =$DB->count("SELECT COUNT(id) from safwl_order where date_format(curdate(),'%y%m') = date_format(benTime,'%y%m')");
$r4 = $DB->count("SELECT SUM(money) from safwl_order");
$r5 =$DB->count("SELECT COUNT(id) from safwl_order  where period_diff(date_format(now(),'%Y%m'),date_format(benTime,'%Y%m'))=1");
$r6 = $DB->count("SELECT COUNT(id) from safwl_order where YEAR(benTime) = YEAR(NOW()) and  day(benTime) = day(NOW()) and MONTH(benTime) = MONTH(now())");
$r7 =$DB->count("SELECT SUM(money) from safwl_order where YEAR(benTime) = YEAR(NOW()) and  day(benTime) = day(NOW()) and MONTH(benTime) = MONTH(now()) and sta = 1");
$r8 = $DB->count("SELECT COUNT(id) from safwl_order where to_days(now())-to_days(benTime) =1");
$r9 =$DB->count("SELECT SUM(money) from safwl_order where to_days(now())-to_days(benTime) =1");
$r10 = $DB->count("SELECT COUNT(id) from safwl_order where YEARWEEK(date_format(benTime,'%Y-%m-%d')) =YEARWEEK(now())-1");
$r11 = $DB->count("SELECT COUNT(id) from safwl_order where YEARWEEK(date_format(benTime,'%Y-%m-%d')) =YEARWEEK(now())-1");
$r12 = $DB->count("SELECT COUNT(id) from safwl_order where YEAR(benTime)=YEAR(NOW())");
?>


    <div class="layui-row">
        <div class="layui-col-xs12 layui-col-md8">
            <div class="layui-row layui-col-space10" style="padding: 5px;">

                <div class="layui-col-xs6 layui-col-md4" >

                    <div class="layui-fluid" style="padding: 0px;">
                        <div class="layui-card">
                            <div class="layui-card-header">
                                今日成交订单
                                <span class="layui-badge layui-bg-blue layuiadmin-badge">个</span>
                            </div>
                            <div class="layui-card-body">
                                <p name="content" style="font-size: 25px;padding:15px 0 15px;color: #666"><?php echo $r6?></p>
                                <p>昨日：<?php echo $r8?></p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="layui-col-xs6 layui-col-md4" >

                    <div class="layui-fluid" style="padding: 0px;">
                        <div class="layui-card">
                            <div class="layui-card-header">
                                本周成交订单
                                <span class="layui-badge layui-bg-blue layuiadmin-badge">个</span>
                            </div>
                            <div class="layui-card-body">
                                <p name="content" style="font-size: 25px;padding:15px 0 15px;color: #666"><?php echo $r1?></p>
                                <p>上周：<?php echo $r10?></p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="layui-col-xs6 layui-col-md4" >

                    <div class="layui-fluid" style="padding: 0px;">
                        <div class="layui-card">
                            <div class="layui-card-header">
                                本月成交订单
                                <span class="layui-badge layui-bg-blue layuiadmin-badge">个</span>
                            </div>
                            <div class="layui-card-body">
                                <p name="content" style="font-size: 25px;padding:15px 0 15px;color: #666"><?php echo $r3?></p>
                                <p>上月：<?php echo $r5?></p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="layui-col-xs6 layui-col-md4" >

                    <div class="layui-fluid" style="padding: 0px;">
                        <div class="layui-card">
                            <div class="layui-card-header">
                                今日成交金额
                                <span class="layui-badge layui-bg-blue layuiadmin-badge">元</span>
                            </div>
                            <div class="layui-card-body">
                                <p name="content" style="font-size: 25px;padding:15px 0 15px;color: #666"><?php if($r7 != ""){ echo round($r7,2);}else{ echo "0";};?></p>
                                <p>昨日：<?php if($r9 != ""){ echo round($r9,2);}else{ echo "0";};?></p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="layui-col-xs6 layui-col-md4" >

                    <div class="layui-fluid" style="padding: 0px;">
                        <div class="layui-card">
                            <div class="layui-card-header">
                                总完成订单
                                <span class="layui-badge layui-bg-blue layuiadmin-badge">个</span>
                            </div>
                            <div class="layui-card-body">
                                <p name="content" style="font-size: 25px;padding:15px 0 15px;color: #666"><?php echo $r2?></p>
                                <p>今年：<?php echo $r12?></p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="layui-col-xs6 layui-col-md4" >

                    <div class="layui-fluid" style="padding: 0px;">
                        <div class="layui-card">
                            <div class="layui-card-header">
                                总成交金额
                                <span class="layui-badge layui-bg-blue layuiadmin-badge">元</span>
                            </div>
                            <div class="layui-card-body">
                                <p name="content" style="font-size: 25px;padding:15px 0 15px;color: #666"><?php if($r4 != ""){ echo round($r4,2);}else{ echo "0";};?></p>
                                <p>继续加油</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="layui-col-xs12 layui-col-md4" style="padding: 5px">
            <div class="layui-fluid" style="padding: 0px;">
                <div class="layui-card">
                    <div class="layui-card-header">小喇叭</div>
                    <div class="layui-card-body">
                        <iframe src="/readme.txt" style="border: none;width: 100%;height: 220px"></iframe>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="layui-col-xs12 layui-col-md8" style="margin-top: 0px;padding: 5px;" >
            <div class="layui-fluid" style="padding: 0px;" >
                <div class="layui-card">
                    <div class="layui-card-header">声明</div>
                    <div class="layui-card-body" style="height: 254px">
                        <p style="font-size:14px;color: #666;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;禁止以下（包括但不限于）商品接入:黄、赌、毒，非法电影网站，非法聚合影视APP，非法视频聊天，非法直播，P2P,私彩,要饭网，各种盗号钓鱼软件，游戏外挂辅助，短信、电话轰炸机，直播盒子，王者荣耀CDK ,以及各种抽奖、一元夺宝、股票、金融、理财、彩票福利、洗钱、信用卡套现、花呗套现、出售公民信息资料，损害他人利益，诈骗违规违法商品。如若发现，一律冻结封停网站!情节严重者，将移交司法部处理!做一个遵纪守法的好网民。
                        <p>
                    </div>
                </div>
            </div>

        </div>
        <div class="layui-col-xs12 layui-col-md4" style="margin-top: 0px;padding: 5px" >
            <div class="layui-fluid" style="padding: 0px;">
                <div class="layui-card">
                    <div class="layui-card-header">程序介绍</div>
                    <div class="layui-card-body">
                        <table class="layui-table" style="">
                            <tr><td>程序名称</td><td>52发卡系统</td></tr>
                            <tr><td>程序版本</td><td>v2020.9.28</td></tr>
                            <tr><td>官方QQ群</td><td>185720544</td></tr>
                            <tr><td>主要特色</td><td>免签约 / 高效/ 快捷 / 响应式 / 清爽 / 极简</td></tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>