<?php 
$GLOBALS["rootdp"] = '../'; 

require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");

include ($GLOBALS["rootdp"]."include/access.php");

require_once ($GLOBALS["rootdp"]."include/settings.php");
require_once ($GLOBALS["rootdp"]."include/functions.php");
require_once ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."adminfunctions.php");
require_once ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."adminbutton.php");


$GLOBALS["tabindex"] = 1024;


?>
