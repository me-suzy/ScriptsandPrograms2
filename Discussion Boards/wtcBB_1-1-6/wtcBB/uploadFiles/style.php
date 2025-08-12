<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################### //FRONT END - CSS\\ ################### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./global.php");

// set header...
header("Content-type: text/css");

$stylesheets_header = str_replace("\r\n","\n",$stylesheets_header);
$stylesheets_sub = str_replace("\r\n","\n",$stylesheets_sub);

print($stylesheets_header);
print($stylesheets_sub);

?>