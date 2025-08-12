<?php

///$_SESSION['printer_friendly'] = 1 ;
setcookie('printer_friendly',1,time()+60*60*24*100,'/');
Location($_SERVER["HTTP_REFERER"]);

?>