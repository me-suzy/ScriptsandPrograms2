<?php

/************************************************************************
 +----------------------------------------------------------------------+
 |   mono.php -> Monoalphabetic Substitution     			|
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

$TITLE="Monoalphabetic Substitution Encoder and Decoder";
$HEADTITLE="Monoalphabetic Substitution Encoder and Decoder";


function mono($plain,$key){
   $unique_table=1;
   $detected_table="";
   $from="abcdefghijklmnopqrstuvwxyz";
   $cipher="";

   if(eregi("--",$key)){
      return "error: Empty substitution detected";
   }

   for($i=0;$i<=25;$i++){
      $k=substr($key,$i,1);
      $check_table[$i]=$k;
   }

   for($i=0;$i<=25;$i++){
      for($j=$i+1;$j<=25;$j++){
         if($check_table[$i]==$check_table[$j]){
            $unique_table=0;
            $detected_table=$detected_table.ascii_letter($i).ascii_letter($j);
         }
      }
   }

   $plain=filter($plain);
   $cipher=strtr($plain,$from,$key);

   if($unique_table==1){ 
      return $cipher;
   }else{
      $table_error="error: Same substitution table detected:\n";

      for($i=0;$i<strlen($detected_table);$i=$i+2){
         $d1=substr($detected_table,$i,1);
         $d2=substr($detected_table,$i+1,1);
         $table_error=$table_error."table ".$d1." and table ".$d2. "\n";
      } 

   return $table_error;
   }
}


top();

echo "<form name=\"Form\" action=\"mono.php\" method=\"post\">\n";
echo "Enter text you want to encode or decode:<br>\n";
echo "<font size=-1>[- note: lowercase characters and no space -]</font><br>\n";
echo "<textarea name=\"plain\" cols=40 rows=7 class=\"txtcolor\">\n";
if ($plain){
	$plain=filter($plain);
	$plain=check_length($plain);
	echo $plain;
}
echo "</textarea><br><br>\n";

echo "<table border=1 cellspacing=0 bordercolor=\"#000000\">\n";
echo "<th colspan=13>Substitution Table :</th>\n";
echo "<tr>\n";

for($i=0;$i<=12;$i++){
   echo "<td align=center>".ascii_letter($i)."</td>\n";
}

echo "</tr><tr>\n";

for($i=0;$i<=12;$i++){
   echo "<td>\n";
   echo "<select name=\"key[$i]\" width=200 class=\"txtcolor\">\n";
   echo "<option>--</option>\n"; 

   for($j=0;$j<=25;$j++){
      echo "<option value=".ascii_letter($j).">".ascii_letter($j)."</option>\n";
   }

   if (ascii_letter($j)==$key[$i]){
         echo "<option value=".$key[$i]." selected>".$key[$i]."</option>\n";
      }

   echo "</select>\n";
   echo "</td>\n";
    
}   

echo "</tr><tr>\n";
for($i=13;$i<=25;$i++){
   echo "<td align=center>".ascii_letter($i)."</td>\n";
}

echo "</tr><tr>\n";
for($i=13;$i<=25;$i++){
   echo "<td>\n";
   echo "<select name=\"key[$i]\" width=200 class=\"txtcolor\">\n";
   echo "<option>--</option>\n"; 

   for($j=0;$j<=25;$j++){
      echo "<option value=".ascii_letter($j).">".ascii_letter($j)."</option>\n";
   }

   if (ascii_letter($j)==$key[$i]){
         echo "<option value=".$key[$i]." selected>".$key[$i]."</option>\n";
      }
   echo "</select>\n";    
   echo "</td>\n";
}

echo "</tr>\n"; 
echo "</table>\n";
echo "<br><br>";
echo "<input type=\"submit\" name=\"process\" value=\"Encode/Decode\" class=\"txtcolor\">\n";
echo "</form>\n";

if ($process=="Encode/Decode"){
   $key_table="";
   for($i=0;$i<=25;$i++){
      $key_table=$key_table.$key[$i];
   }
   $cipher=mono($plain,$key_table);
   echo "Text after encoding or decoding:<br>\n"; 
   echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}

bottom();

?>