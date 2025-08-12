<?

include('config.php');

$PHP_SELF = $_SERVER['PHP_SELF'];

?>


<head>
<style>
td{font-family:Tahoma;font-size:12px;color:#000000;}
a{color:<?echo($link_color)?>; text-decoration:none;font:bold;}
a:hover{color:red}
.menu{color:#00458B;text-decoration:none;}
.quick{color:#00597D;text-decoration:none;font-size:11px;font-weight:bold;padding-top:2px;}
input,textarea, select {
	color : #000000;
	font: normal 11px Verdana, Arial, Helvetica, sans-serif;
	border-color : #000000;
}
body {
	background-color: white;
	scrollbar-face-color: #DEE3E7;
	scrollbar-highlight-color: #FFFFFF;
	scrollbar-shadow-color: #DEE3E7;
	scrollbar-3dlight-color: #D1D7DC;
	scrollbar-arrow-color:  #006699;
	scrollbar-track-color: #EFEFEF;
	scrollbar-darkshadow-color: #98AAB1;
	font-family:Tahoma;font-size:12px;color:#000000;
}


th	{
	color: #111111; font-size: 11px; font-weight : bold;
	background-color: <?echo($tb_main_color)?>; height: 25px;

}
A.nounderline
{
 text-decoration: none;
font :bold
}

    </style>
</head>


<?

$dir = ".";

//make
echo "<br><b>$title Admin</b><br><br>Make\n"
	."<form method=\"POST\" action=\"$PHP_SELF\">\n"
	."<select name=\"convert_dir\">\n"
	."<option value=\"no\">"
	."Please select directory\n";

$result = opendir($dir);

while (false !== ($fn = readdir($result))) {
	if ($fn != "." AND $fn != ".." AND is_dir($fn) AND !strstr($fn,'thumbnail_')) {
		if (!is_dir('thumbnail_'.$fn)) {
			echo("<option value=\"$fn\">$fn\n");
		}
	}
}

closedir($result);

echo "</select><input type=\"hidden\" name=\"type\" value=\"mak\"><br>\n"
	."<input type=submit value=\"Make Thumbnail Gallery\">\n"
	."</form>"
	."<br><font color=red><b>It may look like it is just frozen after you hit "
	."Make Thumbnail Gallery... just be patient!</b></font><br><hr><br>Remove";


//removal
echo "<form method=\"POST\" action=\"$PHP_SELF\">\n"
	."<select name=\"remove_dir\">\n"
	."<option value=\"no\">"
	."Please select gallery\n";

$result = opendir($dir);

while (false !== ($fn = readdir($result))) {
	if ($fn != "." AND $fn != ".." AND is_dir($fn) AND strstr($fn,'thumbnail_')) {
		echo "<option value=\"$fn\">".substr($fn, 10)."\n";
	}
}

closedir($result);

echo "</select><input type=\"hidden\" name=\"type\" value=\"rem\"><br>\n"
	."<input type=submit value=\"Remove Thumbnail Gallery\">\n"
	."</form>"
	."<br><font color=red><b>Be careful, you cannot undo (but can redo)!</b></font><br><hr>"
	."<br>Reindex";
	
//reindex
echo "<form method=\"POST\" action=\"$PHP_SELF\">\n"
	."<select name=\"reindex_dir\">\n"
	."<option value=\"no\">"
	."Please select gallery\n";

$result = opendir($dir);

while (false !== ($fn = readdir($result))) {
	if ($fn != "." AND $fn != ".." AND is_dir($fn) AND strstr($fn,'thumbnail_')) {
		echo "<option value=\"$fn\">".substr($fn, 10)."\n";
	}
}

closedir($result);

echo "</select><input type=\"hidden\" name=\"type\" value=\"rin\"><br>\n"
	."<input type=submit value=\"Reindex Thumbnail Gallery\">\n"
	."</form>"
	."<br><font color=red><b>This will make new thumbnails for an existing gallery.</b></font>"
	."<br><hr>";

if (@$_POST['type'] == "rin") {
	$_POST['remove_dir'] = $_POST['reindex_dir'];
	$_POST['convert_dir'] = substr($_POST['reindex_dir'], 10);
}

if ((@$_POST['type'] == "rem" || @$_POST['type'] == "rin") && @$_POST['remove_dir'] != "no") {
	
	$dir = @$_POST['remove_dir'];
	$a = strstr($dir,'thumbnail_');
	
	if (is_dir($dir) && $a) {
		
		$mydir = opendir($dir);
		
		while (false !== ($fn = readdir($mydir))) {
			if ($fn == "." || $fn == "..") continue; 
			$action = unlink($dir."/".$fn);		
		}
	
		$action = closedir($mydir);
		$action = rmdir($dir);
	
	} else {
		 die("Parent directory does not exist...");
	}
	
	if (@$_POST['type'] != "rin") {
		die("<br><br><b>Done! Reload the page for updated information.</b><br>");
	}
}

if ((@$_POST['type'] == "mak" || @$_POST['type'] == "rin") && @$_POST['convert_dir'] != "no") {
	$dir = @$_POST['convert_dir'];
	
	$opend = 'thumbnail_'.$dir;
	if (is_dir($dir)) {
		@$opend_result = mkdir($opend , 0777);
	} else {
		die("Parent directory does not exist...");
	}
		
	if ($opend_result) {
		$result = opendir($dir);
		while ($fn = readdir($result)) {
			if ($fn != "." AND $fn != ".." AND !is_dir($fn) AND stristr($fn,'jpg')) {
				// Resize Photo, if create info data on, will start creation
				
				//echo ($fn."<br>");
				$size = getimagesize($dir."/".$fn);
				//echo($size[0]." ".$size[1]."<br>");
		
				if ($showdetails_createinfo) {
					
					// Future Implementation
					
				}

				if ($size[0] <= $width) {
		
					//copy
					$copyfile = $opend."/".$fn;
					$original_file =$dir."/".$fn;
					copy($original_file , $copyfile);
				
				} else {
				
					$factor = $size[0] / $width;
					$new_length = intval($size[1] / $factor);
					$width2 = $width;
						
					if ($heightalso && ($new_length > $height)) {
						$factor = $height / $size[1];
						$width2 = (int)($size[0] * $factor);
						$new_length = $height;					
					}
				
					$src_img = imagecreatefromjpeg($dir."/".$fn); 
					$dst_img = imagecreatetruecolor($width2,$new_length); 
			
					$src_all = getimagesize($dir."/".$fn);
					$src_width = $src_all[0];
					$src_height = $src_all[1];
					imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0,
						$width2, $new_length, $src_width, $src_height); 

					imagejpeg($dst_img, $opend."/".$fn, $quality); 
					imagedestroy($src_img); 
					imagedestroy($dst_img);
				}
			}
		}	
		closedir($result);

		die("<br><br><b>Done! Reload the page for updated information.</b><br>");		
	}

}

?>
