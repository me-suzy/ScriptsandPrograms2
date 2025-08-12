<?php

/************************************************************************
 +----------------------------------------------------------------------+
 |   freqdist.php -> Frequency Distribution    				|
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

$TITLE="Frequency Distribution";
$HEADTITLE="Frequency Distribution";

top();

echo "<form name=\"Form\" action=\"freqdist.php\" method=\"post\">\n";
echo "Enter text you want to calculate:<br>\n";
echo "<textarea name=\"plain\" cols=40 rows=7 class=\"txtcolor\">\n";
if ($plain){
	$plain=filter($plain);
	$plain=check_length($plain);
	echo $plain;
}
echo "</textarea><br><br>\n";
echo "<input type=\"submit\" name=\"process\" value=\"Calculate\" class=\"txtcolor\">&nbsp;&nbsp;\n";
echo "</form>\n";

if ($process=="Calculate"){
   $plain=filter($plain);
   $plain=check_length($plain);

   for($i=0;$i<26;$i++){
      $freq[$i]=0;
   }

   for($i=0;$i<strlen($plain);$i++){
      $p=substr($plain,$i,1);
      $p=decimal($p);
      $freq[$p]++;
   }
   
   if(strlen($plain)==0){
      echo "No strings calculated";
   }else{
      echo "<table border=1 cellspacing=0 bordercolor=\"#000000\">\n";
      echo "<tr><td align=\"center\">letter</td><td align=\"center\">frequency</td><td align=\"center\">distribution</td>";
      echo "<td align=\"center\">letter</td><td align=\"center\">frequency</td><td align=\"center\">distribution</td></tr>";
      for($i=0;$i<13;$i++){
         echo "<tr>";
         echo "<td align=\"center\">".ascii_letter($i)."</td><td align=\"center\">$freq[$i]</td><td align=\"center\">".number_format($freq[$i]/strlen($plain),3)."</td>";
         echo "<td align=\"center\">".ascii_letter($i+13)."</td><td align=\"center\">".$freq[$i+13]."</td><td align=\"center\">".number_format($freq[($i+13)]/strlen($plain),3)."</td>";
         echo "</tr>";
      }
      echo "</table>";
   }	
}

bottom();

?>