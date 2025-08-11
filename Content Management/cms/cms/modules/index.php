<html><head>
<title>Rama CMS modules list</title>
</head><body>
<?php
    if ($handle = opendir(".")) {
	while (false !== ($file = readdir($handle))) {
	    if (is_dir($file) && $file != "." && $file != "..") {
		if(is_file("$file/index.php")){ echo "<a href=\"../modules.php?mod=$file&scr=index.php&hei=400\">$file</a><br>"; };
		if(is_file("$file/index.html")){ echo "<a href=\"../modules.php?mod=$file&scr=index.html&hei=400\">$file</a><br>"; };
		if(is_file("$file/index.htm")){ echo "<a href=\"../modules.php?mod=$file&scr=index.htm&hei=400\">$file</a><br>"; };
	    }
	}
	closedir($handle);
    }
?>
</body></html>
