<?php

/************************************************************************
 +----------------------------------------------------------------------+
 |   base64.php -> Base64 Cipher    					|
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

$TITLE="Base64 Encoder and Decoder";
$HEADTITLE="Base64 Encoder and Decoder";

top();

echo "<form name=\"Form\" action=\"base64.php\" method=\"post\">\n";
echo "Enter text you want to base64 encode or decode:<br>\n";
echo "<textarea name=\"plain\" cols=40 rows=7 class=\"txtcolor\">\n";
if ($plain){
	echo $plain;
}
echo "</textarea><br><br>\n";

echo "<input type=\"submit\" name=\"process\" value=\"Encode\" class=\"txtcolor\">&nbsp;&nbsp;\n";
echo "<input type=\"submit\" name=\"process\" value=\"Decode\" class=\"txtcolor\">\n";
echo "</form>\n";

if ($process=="Encode"){
	$cipher=base64_encode($plain);
	echo "Text after xoft encode:<br>\n"; 
	echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}

if ($process=="Decode"){
	$cipher=base64_decode($plain);
	echo "Text after xoft decode:<br>\n";
	echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}

bottom();

?>