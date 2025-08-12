<?php

/************************************************************************
 +----------------------------------------------------------------------+
 |   xoftcoder.php -> Xoft Cipher    					|
 +----------------------------------------------------------------------+  						        |	
 |								        |
 | (c) 2002 by M.Abdullah Khaidar (khaidarmak@yahoo.com)                |
 |								        |	
 | This program is free software. You can redistribute it and/or modify |
 | it under the terms of the GNU General Public License as published by |
 | the Free Software Foundation; either version 2 of the License.       |
 |                                                                      |
 +----------------------------------------------------------------------+
 ************************************************************************/ 

include "main.php";
include "xoft.php";

$TITLE="Xoft Encoder and Decoder";
$HEADTITLE="Xoft Encoder and Decoder";

top();

echo "<form name=\"Form\" action=\"xoftcoder.php\" method=\"post\">\n";
echo "Enter text you want to xoft encode or decode:<br>\n";
echo "<textarea name=\"plain\" cols=40 rows=7 class=\"txtcolor\">\n";
if ($plain){
	echo $plain;
}
echo "</textarea><br><br>\n";
echo "Key : <input type=\"text\" name=\"key\" class=\"txtcolor\" value=\"$key\"><br><br>\n";
echo "<input type=\"submit\" name=\"process\" value=\"Encode\" class=\"txtcolor\">&nbsp;&nbsp;\n";
echo "<input type=\"submit\" name=\"process\" value=\"Decode\" class=\"txtcolor\">\n";
echo "</form>\n";

if ($process=="Encode"){
	$cipher=xoft_encode($plain,$key);
	echo "Text after xoft encode:<br>\n"; 
	echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}

if ($process=="Decode"){
	$cipher=xoft_decode($plain,$key);
	echo "Text after xoft decode:<br>\n";
	echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}

bottom();

?>