<?php
if($_SERVER['HTTP_HOST'] === 'localhost'){
    echo phpinfo();
}else{
    echo 'Sory, you can only see it when <b>$_SERVER[\'HTTP_HOST\'] = "localhost"</b>';
}
?>