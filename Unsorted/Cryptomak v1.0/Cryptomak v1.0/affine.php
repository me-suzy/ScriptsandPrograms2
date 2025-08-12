<?php

/************************************************************************
 +----------------------------------------------------------------------+
 |   affine.php -> Affine Cipher    					|
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

$TITLE="Affine Encoder and Decoder";
$HEADTITLE="Affine Encoder and Decoder";


/*

Affine Cipher:
	e(x) = ax + b mod 26
and
	d(y) = a^(-1) * (y - b) mod 26

*/		

function affine_encode($plain,$a,$b){
   $cipher="";
   $plain=filter($plain);
   $plain=check_length($plain);
   
   for($i=0;$i<strlen($plain);$i++){
      $p=substr($plain,$i,1);
      $p=decimal($p);
      $c=(($a*$p)+$b)%26;
      $c=ascii_letter($c);
      $cipher=$cipher.$c;
   }
   return $cipher;
}


function inv_mul($input){
   switch($input){
      case 1: $output=1;break;
      case 3: $output=9;break;
      case 5: $output=21;break;
      case 7: $output=15;break;
      case 9: $output=3;break;
      case 11: $output=19;break;
      case 15: $output=7;break;
      case 17: $output=23;break;
      case 19: $output=11;break;
      case 21: $output=5;break;
      case 23: $output=17;break;
      case 25: $output=25;break;
      default: $output=0;break;
   }
   return $output;
}


function affine_decode($plain,$a,$b){
   $cipher="";
   $plain=filter($plain);
   $plain=check_length($plain);
   
   for($i=0;$i<strlen($plain);$i++){
      $p=substr($plain,$i,1);
      $p=decimal($p);
      $c=(inv_mul($a)*($p-$b))%26;
      if($c<0) $c=$c+26;
      $c=ascii_letter($c);
      $cipher=$cipher.$c;
   }
      
   return $cipher;
}
 
top();

echo "<form name=\"Form\" action=\"affine.php\" method=\"post\">\n";
echo "Enter text you want to encode or decode:<br>\n";
echo "<textarea name=\"plain\" cols=40 rows=7 class=\"txtcolor\">\n";
if ($plain){
	$plain=filter($plain);
	$plain=check_length($plain);
	echo $plain;
}

echo "</textarea><br><br>\n";
echo "a :";
echo "<select name=\"keya\" width=200 class=\"txtcolor\">\n";
echo "<option value=1>1</option>\n";
echo "<option value=3>3</option>\n";
echo "<option value=5>5</option>\n";
echo "<option value=7>7</option>\n";
echo "<option value=9>9</option>\n";
echo "<option value=11>11</option>\n";
echo "<option value=15>15</option>\n";
echo "<option value=17>17</option>\n";
echo "<option value=19>19</option>\n";
echo "<option value=21>21</option>\n";
echo "<option value=23>23</option>\n";
echo "<option value=25>25</option>\n";
if($keya) echo "<option value=$keya selected>$keya</option>\n";
echo "</select>\n";
echo "&nbsp;&nbsp;&nbsp;";
echo "b:";
echo "<select name=\"keyb\" width=200 class=\"txtcolor\">\n";
for($i=0;$i<=25;$i++){
   if ($i==$keyb){
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
   $cipher=affine_encode($plain,$keya,$keyb);
   echo "Text after encoding:<br>\n"; 
   echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}elseif($process=="Decode"){
   $cipher=affine_decode($plain,$keya,$keyb);
   echo "Text after decoding:<br>\n"; 
   echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}

bottom();

?>