<HTML>
<HEAD>
<title>Upload Directory</title>
</HEAD>

<BODY>
For Upload files<br>
Files spool
<?php 
echo "<table>\n"; 

$handle=opendir('./upload/');  
            while (false!==($file = readdir($handle))) {  
                if ($file != "." && $file != ".." 
&& $file != "update.php" && $file != "write.php" && $file != "modify.php" && $file != "index.php" && $file != "_editor.php" && $file != "_editori.php" && $file != "_editoru.php" && $file != "i3" && $file != "help") { 
                    $filename = str_replace(".tpl"," ",$file);   
                    echo "<tr><td align=\"center\"><b><a href=\"index.php?page=$filename\">$filename</a></b></td></tr>\n 
"; 
            }     
            } 
            closedir($handle);

echo "</table>\n";			 
?>
</BODY>

</HTML>