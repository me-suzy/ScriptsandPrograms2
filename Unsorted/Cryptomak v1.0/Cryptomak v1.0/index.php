<?php

/************************************************************************
 +----------------------------------------------------------------------+
 |   index.php -> CryptoMAK Cipher Tools    				|
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

top();

echo "</td><td width=150></td><td>\n";
echo "<div align=\"left\">\n";
echo "<ul>\n";
echo "Cipher:\n";
$text=file("cipherlist.txt");
foreach($text as $line){
   $item=explode(":",$line);
   list($url,$name)=$item;   
   echo "<li><a href=\"$url\">$name</a>\n";
} 
echo "</ul>\n";

echo "<ul>\n";
echo "Tool:";
$text=file("toollist.txt");
foreach($text as $line){
   $item=explode(":",$line);
   list($url,$name)=$item;   
   echo "<li><a href=\"$url\">$name</a>\n";
} 
echo "</ul>";
echo "</div>";

bottom();

?>