<?php

/************************************************************************
 +----------------------------------------------------------------------+
 |   permute.php -> Permutation  		     			|
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

$TITLE="Permutation Encoder and Decoder";
$HEADTITLE="Permutation Encoder and Decoder";


function permute_encode($plain,$permute){
   $perm_table="";
   $unique_table=1;
   $perm_len=strlen($permute);
   
   $plain=filter($plain);
   $plain=check_length($plain);
   
   for($i=0;$i<$perm_len;$i++){
      $k=substr($permute,$i,1);
      $perm_table[$i]=$k;
   }
   
   for($i=0;$i<$perm_len;$i++){
      for($j=$i+1;$j<$perm_len;$j++){
         if($perm_table[$i]==$perm_table[$j]){
            $unique_table=0;
         }
      }
   }

   if($unique_table==0){
      return "error: Duplicate permutation table detected";
   }
   
   for($i=0;$i<strlen($plain);$i=$i+$perm_len){
      $perm="";
      $p=substr($plain,$i,$perm_len);
      for($j=0;$j<$perm_len;$j++){
         $perm=$perm.substr($p,$perm_table[$j]-1,1);
      }
      $cipher=$cipher.$perm;   
   }

   return $cipher;
}

function permute_decode($plain,$permute){
   $perm_table="";
   $unique_table=1;
   $perm_len=strlen($permute);
   
   $plain=filter($plain);
   $plain=check_length($plain);
   
   for($i=0;$i<$perm_len;$i++){
      $k=substr($permute,$i,1);
      $perm_table[$i]=$k;
   }
   
   for($i=0;$i<$perm_len;$i++){
      for($j=$i+1;$j<$perm_len;$j++){
         if($perm_table[$i]==$perm_table[$j]){
            $unique_table=0;
         }
      }
   }

   if($unique_table==0){
      return "error: Duplicate permutation table detected";
   }
   
   for($i=0;$i<strlen($plain);$i=$i+$perm_len){
      $perm="";
      $k=0;
      
      $p=substr($plain,$i,$perm_len);
      for($j=0;$j<$perm_len;$j++){
         $perm_temp[$perm_table[$j]]=substr($p,$j,1);            
      }
   
      for($k=1;$k<=$perm_len;$k++){
         $perm=$perm.$perm_temp[$k];
      }

      $cipher=$cipher.$perm;   
   }

   return $cipher;
}


top();

?>
<script language="JavaScript">
<!--
function help(){
   var message;
   message="Permutation is permuting character position.\n";
   message=message + "Permute 3241 means:\n";
   message=message + "position 1: third plain character\n";
   message=message + "position 2: second plain character\n";
   message=message + "position 3: fourth plain character\n";
   message=message + "position 4: first plain character\n";
   message=message + "Note: 0 will be omitted.";
   alert(message);
}
//-->
</script>
<?
echo "<form name=\"Form\" action=\"permute.php\" method=\"post\">\n";
echo "Enter text you want to encode or decode:<br>\n";
echo "<textarea name=\"plain\" cols=40 rows=7 class=\"txtcolor\">\n";
if ($plain){
	$plain=filter($plain);
	$plain=check_length($plain);
	echo $plain;
}
echo "</textarea><br><br>\n";
echo "Permute :";

for($i=1;$i<=$MAXPERMUTE;$i++){
   echo "<select name=\"key[$i]\" width=200 class=\"txtcolor\">\n";
   for($j=0;$j<=$MAXPERMUTE;$j++){
      if ($j==$key[$i]){
         echo "<option value=$j selected>$j</option>\n";
      }else{
         echo "<option value=$j>$j</option>\n";
      }
   }   
   echo "</select>\n";
}

echo "<br><br>";
echo "<input type=\"submit\" name=\"process\" value=\"Encode\" class=\"txtcolor\">\n";
echo "<input type=\"submit\" name=\"process\" value=\"Decode\" class=\"txtcolor\">\n";
echo "<input type=\"button\" name=\"process\" value=\"Help\" class=\"txtcolor\" onClick=\"help()\">\n";
echo "</form>\n";

if ($process=="Encode"){
   $key_table="";

   for($i=1;$i<=$MAXPERMUTE;$i++){
      $key_table=$key_table.$key[$i];
   }
   
   $key_table=eregi_replace("0","",$key_table);
   $cipher=permute_encode($plain,$key_table);
   echo "Text after permuting $key_table:<br>\n"; 
   echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}elseif($process=="Decode"){
   $key_table="";

   for($i=1;$i<=$MAXPERMUTE;$i++){
      $key_table=$key_table.$key[$i];
   }

   $key_table=eregi_replace("0","",$key_table);
   $cipher=permute_decode($plain,$key_table);
   echo "Text after decoding:<br>\n"; 
   echo "<textarea cols=40 rows=7 class=\"txtcolor\">$cipher</textarea><br><br>\n";
}

bottom();

?>