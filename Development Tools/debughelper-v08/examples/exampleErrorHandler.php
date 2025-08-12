<html>
<html>
<head>
<style type="text/css">
.dbgError{ 
   border: 1px solid black;   
   background: #B8B8B8;
}
.dbgError .dbgDump {
   background: #F7E885;
}
.dbgError .dbgSource {
    background: white;
}

</style>
</head>
<body>

<?php

////////////////////////////////////////////////////////////////////////
/**
*
* example for debugHelper.php
* shows the embedded error handler
*
* For the lastest version go to:
* http://www.phpclasses.org/browse.html/package/879.html
*
* @author	    Lennart Groetzbach <lennartg@web.de>
* @copyright	Lennart Groetzbach <lennartg@web.de> - distributed under the LGPL
*/
////////////////////////////////////////////////////////////////

$dir = dirname(__FILE__);
require_once $dir . "/../debugHelper.php";

///////////////////////////////////////////////////////////////////////

function a($a) {
   b();
}
function b() {
   //global $debug; echo $debug->message();
}


// warning
join('', "warning"); 

// notice
$notice = array(1,3,4,6);
echo $notice[6];

a();

// fatal error, will not be caught
echo "<p>Won't be caught...";
asd();

///////////////////////////////////////////////////////////////////////

?>

</body>
</html>