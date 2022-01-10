<?php
$title='搜索 ';
include './head.php';
?>
<?php 
if(!empty($_GET['act'])){
    $act = $_GET['act'];
}else{
    $act = "";
}
//查询商品名称
function stGName($gId,$DB){
    $sql = "select * from safwl_goods where id =".$gId;
    $rest = $DB->query($sql);
    $rowt = $DB->fetch($rest);
    return $rowt['gName'];
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
<div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
    <?php 
        if($act == ""){
    ?>
        <form action="search.php?act=query" method="POST" class="form-inline">
            <div class="form-group">
                <label>输入凭证</label>
                <select name="kind" class="form-control">
                    <option value="1" selected>卡密信息</option>
                    <option value="2" >订单编号（交易号/订单号）</option>
                    <option value="3">联系方式</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="value" placeholder="关键文本" required>
            </div>
            <button type="submit" class="btn btn-primary">搜索</button>
        </form>

        <?php
            }elseif($act == "query") {
                $type = $_POST['kind'];
                $txt = $_POST['value'];
                if($type == 1){
                    $sql = "SELECT * from safwl_km where km like '%{$txt}%'";
                }else if($type == 2){
                    $sql = "SELECT * from safwl_km where out_trade_no like '%{$txt}%' or trade_no like '%{$txt}%'";
                }else if($type == 3){
                    $sql = "SELECT * from safwl_km where rel like '%{$txt}%'";
                }
                $rsss=$DB->query($sql);
        ?>
        <a href="./">>>返回首页</a>
            <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>商品名称</th>
                        <th>卡密</th>
                        <th>订单交易号</th>
                        <th>商户单号</th>
                        <th>联系方式</th>
                        <th>创建时间</th>
                        <th>成交时间</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    while ($row = $DB->fetch($rsss)){
                        echo "<tr>";
                        echo "<td>".stGName($row['gid'],$DB)."</td>
                        <td>".$row['km']."</td>
                        <td>".$row['out_trade_no']."</td>
                        <td>".$row['trade_no']."</td>
                        <td>".$row['rel']."</td>
                        <td>".$row['benTime']."</td>
                        <td>".$row['endTime']."</td>";
                        echo "</tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
        <?php 
            }
        ?>
    </div>
</div>