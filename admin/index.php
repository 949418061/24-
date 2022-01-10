 <?php
$title='后台首页';
include './header.php';
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
