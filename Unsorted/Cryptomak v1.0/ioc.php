<?php

/************************************************************************
 +----------------------------------------------------------------------+
 |   ioc.php -> Index of Coincidence    				|
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

$TITLE="Index of Coincidence";
$HEADTITLE="Index of Coincidence";

top();

echo "<form name=\"Form\" action=\"ioc.php\" method=\"post\">\n";
echo "Enter text you want to calculate:<br>\n";
echo "<textarea name=\"plain\" cols=40 rows=7 class=\"txtcolor\">\n";
if ($plain){
	$plain=filter($plain);
	$plain=check_length($plain);
	echo $plain;
}
echo "</textarea><br><br>\n";

echo "Key Length:";
echo "<select name=\"keylength\" width=200 class=\"txtcolor\">\n";

for($i=1;$i<=$MAXKEYLENGTH;$i++){
   if ($i==$keylength){
      echo "<option value=$i selected>$i</option>\n";
   }else{
      echo "<option value=$i>$i</option>\n";
   }
}   
echo "</select>\n";
echo "<br><br>";

echo "<input type=\"submit\" name=\"process\" value=\"Calculate\" class=\"txtcolor\">&nbsp;&nbsp;\n";
echo "</form>\n";

if ($process=="Calculate"){
   $plain=filter($plain);
   $plain=check_length($plain);

   for($i=0;$i<26;$i++){
      for($j=1;$j<=$keylength;$j++){
         $freq[$i][$j]=0;
      }
   }

   for($i=0;$i<strlen($plain);$i=$i+$keylength){
      $pblock=substr($plain,$i,$keylength);
      for($m=1;$m<=$keylength;$m++){
         $p=substr($pblock,$m-1,1);
         $p=decimal($p);
         $freq[$p][$m]++;   
      }
   }
   
   for($m=1;$m<=$keylength;$m++){
      $sum=0;
      $n=0; 
      for($p=0;$p<26;$p++){
         $sum=$sum + $freq[$p][$m]*($freq[$p][$m]-1);
         $n=$n+$freq[$p][$m];
      }
      if($n==1||$n==0){
        $ioc[$m]="division by zero";
      }else{
         $ioc[$m]=$sum/($n*($n-1));
      }
   }
   
   echo "Index of coincidence with m=$keylength:<br>";
   for($m=1;$m<=count($ioc);$m++){
      echo number_format($ioc[$m],3)."<br>";
   }
   	
}

bottom();

?>