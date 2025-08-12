<?
$pagetitle = "Image Admin";
include("cn_auth.php");

### If "Image Admin" is disabled
if($set[images] != "on") {
	print E("The image feature has been turned off and will not be used.");
}

if($_GET['display'] != "gallery") {
	include("cn_head.php");
} else {
?>
<html>
<head>
<title>CzarNews Image Gallery</title>
<link rel="stylesheet" href="sv_styles.css" type="text/css">
<script type="text/javascript">
<!--
function formFill(thefield,txt){
	window.opener.document.theform[thefield].value='' + window.opener.document.theform[thefield].value + '' + txt + ''
	window.close()
}
//-->
</script>
</head>
<body>
<?
}

if(isset($_REQUEST['op'])) {
	if($_REQUEST['op'] == "add") {
		if($_POST['go'] == "true") {
			### Add a new record into DB
			if($_POST['name'] == "") { print E("You must type in a name for the image"); }
			if(empty($_FILES['image'])) {
				print E("You must select an image to upload");
			} else {
				// Format filename to remove illegal characters
				$_FILES['image']['name'] = cn_FileFormat($_FILES['image']['name']);
				// Upload image code
				$file_path = cn_FileUpload("image");
				$thumb_path = cn_ImageThumbnail($file_path);
				$file_name = substr($file_path,(strrpos($file_path,"/")+1));
				$thumb_name = substr($thumb_path,(strrpos($thumb_path,"/")+1));
			}
			$name = cn_htmltrans($_POST['name']);
			$text = cn_htmltrans($_POST['text']);
			if(!$file_path) {
				echo E("Could not upload image.  Please check to ensure that the image upload directory's CHMOD permission have been set to 777.  You can change the CHMOD settings through any FTP client.");
			}
			$q[add] = mysql_query ("INSERT INTO $t_img (id,author,type,name,text,filename,thumbname,date) VALUES ('','$useri[id]','news','$name','$text','" . $file_name . "','" . $thumb_name . "','$now')", $link) or E("Could not insert image:<br>" . mysql_error());
			echo S("New image has been added<p>Image: '<b>$file_name</b>'<br>Thumbnail: '<b>$thumb_name</b>'</p>");
			exit;
		}
	### Set variables for adding
	$button_txt = "Upload Image";
	} elseif($_REQUEST['op'] == "edit") {
		if($_POST['go'] == "true") {
			
			if($_POST['name'] == "") { print E("You must type in a name for the image"); }
			if(!empty($_FILES['image']['name'])) {
				// Format filename to remove illegal characters
				$_FILES['image']['name'] = cn_FileFormat($_FILES['image']['name']);
				// Upload image code
				cn_FileDelete($_POST['oldimage']);
				$file_path = cn_FileUpload("image","y");
				$thumb_path = cn_ImageThumbnail($file_path);
				$file_name = substr($file_path,(strrpos($file_path,"/")+1));
				$thumb_name = substr($thumb_path,(strrpos($thumb_path,"/")+1));
				
				$updsql = ", filename='$file_name', thumbname='$thumb_name'";
				$updtxt = "<p>New Image Uploaded</p><p>Image: <b>$file_name</b><br>Thumbnail: <b>$thumb_name</b></p>";
			} else {
				$updsql = "";
				$updtxt = "";
			}
			
			### Save changes into DB
			$name = cn_htmltrans($_POST['name']);
			$text = cn_htmltrans($_POST['text']);
			$q[update] = mysql_query("UPDATE $t_img SET name='$name', type='news', text='$text' $updsql WHERE id = '$_POST[id]'", $link) or E("Could not update image:<br>" . mysql_error());
			echo S("Image has been edited $updtxt");
			exit;
		}
	### Set variables for editing
	$button_txt = "Save Image";
	$q[edit] = mysql_query("SELECT * FROM $t_img WHERE id = '$_REQUEST[id]'", $link) or E("Couldn't retieve image info:<br>" . mysql_error());
	$ev = mysql_fetch_array($q[edit]);
	} elseif($_REQUEST['op'] == "del") {
	
		$q[del] = mysql_query("SELECT name FROM $t_img WHERE id = '$_REQUEST[id]'", $link) or E("Couldn't select image:<br>" . mysql_error());
		$dv = mysql_fetch_array($q[del], MYSQL_ASSOC);
	
		if($_POST['go'] == "true") {
			### Delete image and record
			if(cn_FileDelete($dv[filename])) {
				$q[del2] = mysql_query("DELETE FROM $t_img WHERE id = '$_POST[id]'", $link) or E("Couldn't delete image:<br>" . mysql_error());
				echo S("Image has been deleted");
				exit;
			} else {
				print E("Unable to delete image from server.  Please CHOMD your image directory 777 so images can be removed when deleted.");
			}
		}
		?>
		<form method="post" action="<? print $_SERVER['PHP_SELF']; ?>" name="theform">
		Are you sure you want to delete "<b><?=$dv[name]?></b>"?<br><br>
		<input type="hidden" name="op" value="<? print $_REQUEST['op']; ?>">
		<input type="hidden" name="id" value="<? print $_REQUEST['id']; ?>">
		<input type="hidden" name="go" value="true">
		<input type="submit" name="submit" value="Yes" class="input">&nbsp;&nbsp;<input type="button" onClick="javascript:location.href='<? print $_SERVER['PHP_SELF']; ?>'" value="No" class="input">
		<?
		exit;
	}
	$q[info] = mysql_query("SELECT * FROM $t_img ORDER BY date ASC", $link) or E("Couldn't select image:<br>" . mysql_error());
	$num = mysql_num_rows($q[info]);
	?>
	
	<form method="post" action="<? print $_SERVER['PHP_SELF']; ?>" name="theform" enctype="multipart/form-data">
	<? if(!empty($ev[thumbname])) {?>
		<blockquote>
		<img src="uploads/<? print $ev[thumbname]; ?>" border="0"><br>
		[ <a href="uploads/<? print $ev[filename]; ?>">Full Size</a> ]
		</blockquote>
	<? } elseif(!empty($ev[filename])) {?>
		<blockquote>
		<img src="uploads/<? print $ev[filename]; ?>" border="0">
		</blockquote>
	<? } ?>
	<table  width="100%" border="0" cellspacing="1" cellpadding="2" align="center">
	<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
	Name:
	</td><td bgcolor="<? print $MenuBg1; ?>">
	<input type="text" name="name" size="25" class="input" value="<? print cn_htmltrans($ev[name],"html"); ?>">
	</td></tr>
	<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
	Description:
	</td><td bgcolor="<? print $MenuBg1; ?>">
	<input type="text" name="text" size="40" class="input" value="<? print cn_htmltrans($ev[text],"html"); ?>">
	</td></tr>
	<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
	Image:
	</td><td bgcolor="<? print $MenuBg1; ?>">
	<input type="file" name="image" size="25" class="input">
	<input type="hidden" name="oldimage" value="<? print $ev[filename]; ?>">
	</td></tr>
	<tr><td bgcolor="<? print $MenuBg2; ?>">&nbsp;
	</td><td bgcolor="<? print $MenuBg1; ?>">
	<input type="hidden" name="op" value="<? print $_REQUEST['op']; ?>">
	<input type="hidden" name="id" value="<? print $_REQUEST['id']; ?>">
	<input type="hidden" name="go" value="true">
	<input type="submit" name="submit" value="<? print $button_txt; ?>" class="input">&nbsp;&nbsp;
	<input type="button" name="cancel" value="Cancel" class="input" onClick="javascript:location.href='<? print $_SERVER['PHP_SELF']; ?>'">
	</td></tr>
	</table><br>
	
	<?
} else {
	
	$q[info] = mysql_query("SELECT * FROM $t_img ORDER BY date ASC", $link) or E("Couldn't select images:<br>" . mysql_error());
	$num = mysql_num_rows($q[info]);
	if($_GET['display'] == "gallery") {
		print "<b>$num</b> Image(s) Found<hr size=\"1\" color=\"#000000\" />";
	} else {
		print "<b>$num</b> Image(s) Found<br /><br /><a href=\"?op=add\">[ Add Image ]</a> or click on a image below to edit";
	}
	if($num == "0") {
		print "<br /><br />No images found.";
	}
	?>
	<table border="0" cellpadding="1" cellspacing="1" width="100%" align="center">
	<?
	$i=1;
	while ($r = mysql_fetch_array($q[info], MYSQL_ASSOC)) {
		if($_GET['display'] == "gallery") {
			if($i > "3") { print "<tr>"; }
			if(empty($r[thumbname])) { $img = $r[filename]; } else { $img = $r[thumbname]; }
			?>
			<td align="center" valign="middle">
			<table border="0" cellpadding="1" cellspacing="1"><tr><td align="center" valign="middle" bgcolor="#000000" colspan="3">
			<a href="javascript:formFill('<? print $_GET['field']; ?>','<? print "{img:$r[id]}"; ?>');">
			<img src="<? print "$set[scripturl]$set[img_dir]/$img"; ?>" border="0" alt="<? print "$r[name]: " . cn_cutstr($r[text]); ?>">
			</a>
			</td>
			</tr><tr>
			<td align="left"><a href="javascript:formFill('<? print $_GET['field']; ?>','<? print "{img:$r[id]|left}"; ?>');" title="Align Left">
			<img src="<? print $set[scripturl] . "images/cn_align_l.gif"; ?>" border="0" alt="Align Left">
			</a></td>
			<td align="center"><a href="javascript:formFill('<? print $_GET['field']; ?>','<? print "{img:$r[id]|center}"; ?>');" title="Align Center">
			<img src="<? print $set[scripturl] . "images/cn_align_c.gif"; ?>" border="0" alt="Align Center">
			</a></td>
			<td align="right"><a href="javascript:formFill('<? print $_GET['field']; ?>','<? print "{img:$r[id]|right}"; ?>');" title="Align Right">
			<img src="<? print $set[scripturl] . "images/cn_align_r.gif"; ?>" border="0" alt="Align Right">
			</a></td>
			</tr></table>
			<? print cn_cutstr($r[name]); ?>
			</td>
			<?
			if($i > "3") { $i=0; print "<tr>"; }
		} else {
			?>
			<tr><td><?=$i?>)</td>
			<td bgcolor="#EEEEEE" width="70%">&nbsp;<a href="?op=edit&id=<?=$r[id]?>"><b><? echo $r[name]; ?></b></a> <? echo "(ID: $r[id])"; ?></td>
			<td><a href="?op=del&id=<?=$r[id]?>">[Delete]</a></td></tr>
			<?
		}
		$i++;
	}
	?>
	</table><br>
	
	<?
}

if($_GET['display'] != "gallery") {
	include("cn_foot.php");
} else {
?>
</body>
</html>
<?
}
?>