<?php

/************************************************************************
 +----------------------------------------------------------------------+
 |   column.php -> Columnar Transposition  				|
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

$TITLE="Columnar Transposition Encoder and Decoder";
$HEADTITLE="Columnar Transposition Encoder and Decoder";

function column_encode($plain,$column){
   $cipher="";
   $padding="";
   $t=0;
   
   $plain=filter($plain);
   $plain=check_length($plain);
   $plain_length=strlen($plain);
   $row=ceil($plain_length/$column);
   
   $padding_length=($row * $column) - $plain_length;
   for($i=1;$i<=$padding_length;$i++){
      $padding=$padding."x";
   }
   $plain=$plain.$padding;
   
   for($i=1;$i<=$row;$i++){
      for($j=1;$j<=$column;$j++){
         $temp[$i][$j]=substr($plain,$t,1);
         $t++;
      }
   }
   
   for($i=1;$i<=$column;$i++){
      for($j=1;$j<=$row;$j++){
         $cipher=$cipher . $temp[$j][$i];
      }
   }
    
   return $cipher;
}

function column_decode($plain,$column){
   $cipher="";
   $t=0;
   
   $plain=filter($plain);
   $plain=check_length($plain);
   
   $plain_length=strlen($plain);
   $row=ceil($plain_length/$column);
   
   $padding_length=($row * $column) - $plain_length;
   for($i=1;$i<=$padding_length;$i++){
      $padding=$padding."x";
   }
   $plain=$plain.$padding;
   
   for($i=1;$i<=$column;$i++){
      for($j=1;$j<=$row;$j++){
         $temp[$j][$i]=substr($plain,$t,1);
         $t++;
      }
   }
   
   for($i=1;$i<=$row;$i++){
      for($j=1;$j<=$column;$j++){
         $cipher=$cipher . $temp[$i][$j];
      }
   }

   return $cipher;
}


top();

echo "<form name=\"Form\" action=\"column.php\" method=\"post\">\n";
echo "Enter text you want to encode or decode:<br>\n";
echo "<textarea name=\"plain\" cols=40 rows=7 class=\"txtcolor\">\n";
if ($plain){
	$plain=filter($plain);
	$plain=check_length($plain);
	echo $plain;
}
echo "</textarea><br><br>\n";
echo "Column :";
echo "<select name=\"key\" width=200 class=\"txtcolor\">\n";
for($i=1;$i<=$MAXCOLUMN;$i++){
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
	$cipher=column_encode($plain,$key);
	echo "Text after encoding:<br>\n"; 
	echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}elseif($process=="Decode"){
	$cipher=column_decode($plain,$key);
	echo "Text after decoding:<br>\n"; 
	echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}

bottom();

?>