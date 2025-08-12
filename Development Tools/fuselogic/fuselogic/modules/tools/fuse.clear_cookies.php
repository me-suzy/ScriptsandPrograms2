<?php

foreach($_COOKIE as $key => $value){
    setcookie($key,'',time()-3600,"/");		
}

echo '<h3 align="center">Done!</h3>';
?>