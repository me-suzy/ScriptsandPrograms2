<?php

/************************************************************************
 +----------------------------------------------------------------------+
 |   vigenere.php -> Vigenere Cipher  		     			|
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

$TITLE="Vigenere Encoder and Decoder";
$HEADTITLE="Vigenere Encoder and Decoder";

function vigenere_encode($plain,$key){
   $cipher="";

   $plain=filter($plain);
   $plain=check_length($plain);
   
   $key=filter($key);
   $key=check_length($key);
   
   if(strlen($key)==0) return "error: please input key";
   
   for($i=0;$i<strlen($plain);$i=$i+strlen($key)){
      $pblock=substr($plain,$i,strlen($key));
   
      for($j=0;$j<strlen($pblock);$j++){
         $p=substr($pblock,$j,1);
         $k=substr($key,$j,1);
         
         $p=decimal($p);
         $k=decimal($k);
   
         $c=($p+$k)%26;
         $c=ascii_letter($c);
         $cipher=$cipher.$c;
      }
   }
   
   return $cipher;
}

function vigenere_decode($plain,$key){
   $cipher="";
   
   $plain=filter($plain);
   $plain=check_length($plain);
   
   $key=filter($key);
   $key=check_length($key);
   
   if(strlen($key)==0) return "error: please input key";
      
   for($i=0;$i<strlen($plain);$i=$i+strlen($key)){
      $pblock=substr($plain,$i,strlen($key));
   
      for($j=0;$j<strlen($pblock);$j++){
         $p=substr($pblock,$j,1);
         $k=substr($key,$j,1);
         
         $p=decimal($p);
         $k=decimal($k);
   
         $c=($p-$k)%26;
         if($c<0)$c=$c+26;
         $c=ascii_letter($c);
         $cipher=$cipher.$c;
      }
   }
   
   return $cipher;
}


top();

echo "<form name=\"Form\" action=\"vigenere.php\" method=\"post\">\n";
echo "Enter text you want to encode or decode:<br>\n";
echo "<textarea name=\"plain\" cols=40 rows=7 class=\"txtcolor\">\n";
if ($plain){
	$plain=filter($plain);
	$plain=check_length($plain);
	echo $plain;
}
echo "</textarea><br><br>\n";
echo "Key : <input type=\"text\" name=\"key\" class=\"txtcolor\" value=\"$key\"><br><br>\n";
echo "<input type=\"submit\" name=\"process\" value=\"Encode\" class=\"txtcolor\">&nbsp;&nbsp;\n";
echo "<input type=\"submit\" name=\"process\" value=\"Decode\" class=\"txtcolor\">\n";
echo "</form>\n";

if ($process=="Encode"){
	$cipher=vigenere_encode($plain,$key);
	echo "Text after encoding:<br>\n"; 
	echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}

if ($process=="Decode"){
	$cipher=vigenere_decode($plain,$key);
	echo "Text after decoding:<br>\n";
	echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}

bottom();

?>