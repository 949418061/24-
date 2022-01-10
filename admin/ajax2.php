<?php
include './qq.php';
 $gName = $_GET['id'];
    $res = $DB->query("select * from safwl_km where gid=$gName and stat = 0");
    $data = "";
    while ($row = $DB->fetch($res)) {
    if($row['gid']==$gName){
	 
        $id = _safwl($row['id']);
        $sql = "delete from safwl_km where id = ".$id;
        $b = $DB->query($sql);
        if($b > 0){
            wsyslog('删除指定卡密', "IP:".real_ip()."");
        }else{
            exit('{"code":-1,"msg":"删除失败"}');
        }
      
		
	}
	
	}
	echo "<script>alert('卡密已清空');location.href='".$_SERVER["HTTP_REFERER"]."';</script>"; 
?>