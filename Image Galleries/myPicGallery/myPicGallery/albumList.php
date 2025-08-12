<?php
session_start();
if($_GET['userID']){
	$_SESSION['userID'] = $_GET['userID'];
}
if(!session_is_registered($_SESSION['userID'])){	
	header("location: login.php");
}
?><LINK href="includes/style.css" rel="stylesheet" type="text/css">
<body class="tblBody"><?
require("includes/config.php");
$subDir = $_GET['subDir'];

if($subDir){
	if(strpos($subDir, "\'")){
		$subDir = str_replace("\'", "'", $subDir);
	}
	?><table width="170" class="tblBody"><?
	if ($dh = opendir($baseDir.$subDir."/")) {
		while (($file = readdir($dh)) !== false) {
			if(filetype($baseDir.$subDir."/".$file)=="dir"){
				if($file==".."){
					?><tr><td><a href="gallery.php" target="_parent">Main Album Menu</a></td></tr><?
				}
				else if($file=="."){
					$upperDir = substr($subDir, 0, strrpos($subDir, "/"));
					?><tr><td><a href="gallery.php?subDir=<?=$upperDir?>" target="_parent">Upper Album</a></td></tr><?
				}
				else if($file != "Picasa Edits"){
					?><tr><td>------------------------------</td></tr>
					<tr><td><a href="gallery.php?subDir=<?=$subDir."/".$file?>" target="_parent"><?=$file?></a></td></tr><?
				}
			}
		}
		closedir($dh);
	}
	?>
	<tr><td>------------------------------</td></tr>
	</table><?
}
else{
	?><table width="160" class="tblBody"><?
	if($userType == "admin"){
		if ($dh = opendir($baseDir)) {
			while (($file = readdir($dh)) !== false) {
				if(filetype($baseDir.$file)=="dir"){
					if(($file!=".") && ($file!="..") && ($file!="Picasa Edits")){
						?><tr><td>------------------------------</td></tr>
						<tr><td><a href="gallery.php?subDir=<?=$file?>" target="_parent"><?=$file?></a></td></tr><?
					}
				}
			}
			closedir($dh);
		}
	}
	else{
		$showDir = mysql_query("select * from album_permission where userAllowed = '$userName'")or die(mysql_query());
		while($rowShow = mysql_fetch_array($showDir)){
			?><tr><td>------------------------------</td></tr>
			<tr><td><a href="gallery.php?subDir=<?=$rowShow['dir']?>" target="_parent"><?=$rowShow['dir']?></a></td></tr><?
		}
	}
	?>
	<tr><td>------------------------------</td></tr>
	</table><?
}
?>
</body>