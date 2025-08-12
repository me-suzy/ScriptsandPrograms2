<?php

/************************************************************************
 +----------------------------------------------------------------------+
 |   shift.php -> Simple Shift  		     			|
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

$TITLE="Simple Shift Encoder and Decoder";
$HEADTITLE="Simple Shift Encoder and Decoder";

function shift_letter($plain,$shift){
   $cipher="";
   
   for($i=0;$i<strlen($plain);$i++){
      $p=substr($plain,$i,1);
      $p=ord($p);
   
      if(($p>=97)&&($p<=122)){
	    $c=$p + $shift;
	    if($c>122) $c=$c-26;
      }elseif(($p>=65)&&($p<=90)){
      	    $c=$p + $shift;
	    if($c>90) $c=$c-26;
      }else{
      	    $c=$p;
      }
   
      $c=chr($c);
      $cipher=$cipher.$c;
   }
   
return $cipher;
}

top();

echo "<form name=\"Form\" action=\"shift.php\" method=\"post\">\n";
echo "Enter text you want to encode or decode:<br>\n";
echo "<textarea name=\"plain\" cols=40 rows=7 class=\"txtcolor\">\n";
if ($plain){
	echo $plain;
}
echo "</textarea><br><br>\n";
echo "Shift :";
echo "<select name=\"key\" width=200 class=\"txtcolor\">\n";

for($i=0;$i<=26;$i++){
   if ($i==$key){
      echo "<option value=$i selected>$i</option>\n";
   }else{
      echo "<option value=$i>$i</option>\n";
   }
}   

echo "</select>\n";
echo "<br><br>";
echo "<input type=\"submit\" name=\"process\" value=\"Encode\" class=\"txtcolor\">\n";
echo "<input type=\"submit\" name=\"process\" value=\"Decode\" class=\"txtcolor\">\n";
echo "</form>\n";

if ($process=="Encode"){
	$cipher=shift_letter($plain,$key);
	echo "Text after encoding:<br>\n"; 
	echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}elseif ($process=="Decode"){
	$key=26-$key;
	$cipher=shift_letter($plain,$key);
	echo "Text after decoding:<br>\n"; 
	echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}

bottom();

?>