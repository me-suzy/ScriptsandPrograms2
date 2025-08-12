<?php

///$_SESSION['printer_friendly'] = 0 ;
setcookie('printer_friendly',0,time()+60*60*24*100,'/');
Location($_SERVER["HTTP_REFERER"]);

?>