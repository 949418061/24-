<?php
    echo'<ul class="pagination">';
    $first=1;
    $prev=$page-1;
    $next=$page+1;
    $last=$pages;
    if ($page>1){
        echo '<li><a href="'.$fyzy.$first.$link.'">首页</a></li>';
        echo '<li><a href="'.$fyzy.$prev.$link.'">&laquo;</a></li>';
    } else {
        echo '<li class="disabled"><a>首页</a></li>';
        echo '<li class="disabled"><a>&laquo;</a></li>';
    }
    for ($i=1;$i<$page;$i++)
        echo '<li><a href="'.$fyzy.$i.$link.'">'.$i .'</a></li>';
        echo '<li class="disabled"><a>'.$page.'</a></li>';
    for ($i=$page+1;$i<=$pages;$i++)
        echo '<li><a href="'.$fyzy.$i.$link.'">'.$i .'</a></li>';
        echo '';
    if ($page<$pages){
        echo '<li><a href="'.$fyzy.$next.$link.'">&raquo;</a></li>';
        echo '<li><a href="'.$fyzy.$last.$link.'">尾页</a></li>';
    } else {
        echo '<li class="disabled"><a>&raquo;</a></li>';
        echo '<li class="disabled"><a>尾页</a></li>';
    }
    echo'</ul>';
?>