<?php

// user settings
//maximum image size in bytes
$maxsize = "2500000";

// approved filetypes
$ft1 = "image/jpeg";
$ft2 = "image/pjpeg";
$ft3 = "image/gif";
$ft4 = "image/png";

// relative path to image directory (no trailing slash!)
$image_dir = "/images";

// do you want export filename only or with relative path from website root?
// this is defautl value which can be overriden fom initial link
$filename_with_path = "yes";	// options - yes/no

/* end of user settings, don't mess with code unless you knou what you doing
/**************************************************************************/

include('_i3/inc/config.php');
include('_i3/lang/lang_'.$settings[language].'.php');


// globals fix for PHP 4.2 and higher
global $PHP_SELF,$HTTP_POST_VARS, $HTTP_POST_FILES, $HTTP_GET_VARS;


if(isset($HTTP_GET_VARS[formname])) {
	$formname = $HTTP_GET_VARS[formname];
}
if(isset($HTTP_POST_VARS[formname])) {
	$formname = $HTTP_POST_VARS[formname];
}

if(isset($HTTP_GET_VARS[inputname])) {
	$inputname = $HTTP_GET_VARS[inputname];
}
if(isset($HTTP_POST_VARS[inputname])) {
	$inputname = $HTTP_POST_VARS[inputname];
}

if(isset($HTTP_GET_VARS[path])) {
	$path = $HTTP_GET_VARS[path];
}
if(isset($HTTP_POST_VARS[path])) {
	$path = $HTTP_POST_VARS[path];
}


if(isset($HTTP_GET_VARS[activedir])) {
	$activedir = $HTTP_GET_VARS[activedir];
}
if(isset($HTTP_POST_VARS[activedir])) {
	$activedir = $HTTP_POST_VARS[activedir];
}
if (!isset($activedir)) { 
	$activedir_show = $activedir;
	$activedir = ".".$image_dir."";
} else {
	$activedir_show = $activedir;
	$activedir = ".".$image_dir."/".$activedir."";
}


if(isset($path)) {
	$filename_with_path = $path;
}


if(isset($HTTP_POST_VARS[upload])) {

	if($HTTP_POST_VARS[subfolders] == "") {
		$newimg = "".$activedir."/".$HTTP_POST_FILES[img][name]."";
	} else {
		$newimg = "".$activedir."/".$HTTP_POST_VARS[subfolders]."/".$HTTP_POST_FILES[img][name]."";
	}
	//image selection check
	if ($HTTP_POST_FILES[img][name] == "") {
		$error = 1;
		$errormes .= "<font style=\"font: 9.9px verdana; color: #CE0000;\"><b>".$lang['error'].":</b> ".$lang['alert_no_image']."&nbsp;<br></font>";
	}
	
	// image size check
	if ($HTTP_POST_FILES[img][size] > $maxsize) {
		$error = 1;
		$errormes .= "<font style=\"font: 9.9px verdana; color: #CE0000;\"><b>".$lang['error'].":</b> ".$lang['alert_file_oversize']."&nbsp;<br></font>";
	}

	// image file type check
	if (($HTTP_POST_FILES[img][type] == $ft1) or ($HTTP_POST_FILES[img][type] == $ft2) or ($HTTP_POST_FILES[img][type] == $ft3) or ($HTTP_POST_FILES[img][type] == $ft4)) {

		//checks if file exists
		if (file_exists($newimg) != FALSE ) {
			$error = 1;
			$errormes .= "<font style=\"font: 9.9px verdana; color: #CE0000;\"><b>".$lang['error'].":</b> ".$lang['alert_file_exists']."&nbsp;<br></font>";
		}

		if (!copy($HTTP_POST_FILES[img][tmp_name],$newimg) != FALSE )  {
			$error = 1;
			$errormes .= "<font style=\"font: 9.9px verdana; color: #CE0000;\"><b>".$lang['error'].":</b> ".$lang['alert_no_dir']."&nbsp;<br></font>";
		}
	} else {
		$error = 1;
		$errormes .= "<font style=\"font: 9.9px verdana; color: #CE0000;\"><b>".$lang['error'].":</b> ".$lang['alert_file_unaproved']." [<a href=\"javascript:void\" title=\"".$tn[113]." (".$HTTP_POST_FILES[img][type].") format!\">?</a>]&nbsp;<br></font>";
	}

}

?>

<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=<?=$lang['charset']?>">
		<title>Labs4.com - imgXplorer</title>
	<style type="text/css">
	<!--	
		body { background-color: #FFFFFF; color: #000000; }
		.h {  cursor: hand; }
		.framed { border: 1px solid black; background-color: #FFF3BD;	}
		a { text-decoration: none; color: #CE0000; }
	-->
	</style>
	
	<SCRIPT type="text/javascript">
	<!--
	var is_selected = 0;
	var export_path = <?php if($filename_with_path == "yes") { echo "1"; } else { echo "0"; } ?>;
	
	function showimage() {
			document.images.preview.src = '<?=$activedir?>/' + document.select.files.options[document.select.files.selectedIndex].value
			document.images.preview.alt = document.select.files.options[document.select.files.selectedIndex].value + ' - <?=$lang['click_to_insert']?>'
	}

	function changeFolder(form) {
		var myindex = form.subfolders.selectedIndex;
			if (form.subfolders.options[myindex].value != 0) {
				location = form.subfolders.options[myindex].value + '&formname=<?=$formname?>&inputname=<?=$inputname?>&path=<?=$filename_with_path?>';
		}
	}

	function Write_Input() {
		if (is_selected == 0)  {
			alert("<?=$lang['alert_no_image_select']?>");
		} else {
	
		window.focus();

			if(export_path == 1) {
				if (window.opener && !window.opener.closed) window.opener.document.<?php echo $formname; ?>.<?php echo $inputname; ?>.value = '<?=$activedir?>/' + document.select.files.options[document.select.files.selectedIndex].value
			} else {
				if (window.opener && !window.opener.closed) window.opener.document.<?php echo $formname; ?>.<?php echo $inputname; ?>.value = document.select.files.options[document.select.files.selectedIndex].value
			}

		}
	}

	function showDetail() {
		if (is_selected == 0)  {
		alert("<?=$lang['alert_no_detail']?>.");
		} else {
	
		var w = open ('', 'detail', 'width=380,height=350,titlebar=0,resizable=yes');
		
		var detailHTML = "";
			detailHTML += "<HTML><BODY>"
			detailHTML += "<FORM name=\"mydetail\" onSubmit=\"return false\">"
			detailHTML += "<TABLE CELLSPACING=1 CELLPADDING=2 BORDER=0 WIDTH=\"100%\" BGCOLOR=\"#000000\">"
			detailHTML += "<TR><TD bgcolor=\"#FFF3BD\" COLSPAN=4><FONT style=\"font: 11.9px verdana; color: #000000;\"><b><?=$lang['detail_preview']?></b></FONT></TD></TR>"
			detailHTML += "<TR><TD ALIGN=right bgcolor=\"#FFFFFF\"><FONT style=\"font: 11.9px verdana; color: #003366;\"><?=$lang['filename']?>:</FONT></TD>"
			detailHTML += "<TD bgcolor=\"#FFFFFF\"><input type=\"text\" name=\"filename\" style=\"font: 11.9px verdana; width: 120px; border: 0px solid; text-align: right\"></TD>"
			detailHTML += "<TD ALIGN=right bgcolor=\"#FFFFFF\"><FONT style=\"font: 11.9px verdana; color: #003366;\"><?=$lang['size']?>:</FONT></TD>"
			detailHTML += "<TD bgcolor=\"#FFFFFF\"><font style=\"font: 11.9px verdana;\"><input type=\"text\" name=\"filesize\" style=\"font: 11.9px verdana; width: 48px; border: 0px solid; text-align: right;\"> <?=$lang['bytes']?></font></TD></TR>"
			detailHTML += "<TR><TD colspan=2 bgcolor=\"#FFFFFF\">&nbsp;</TD>"
			detailHTML += "<TD align=right bgcolor=\"#FFFFFF\"><FONT style=\"font: 11.9px verdana; color: #003366; border: 0px solid\"><?=$lang['measures']?>:</FONT></TD>"
			detailHTML += "<TD bgcolor=\"#FFFFFF\"><font style=\"font: 11.9px verdana;\"><input type=\"text\" name=\"filewidth\" style=\"font: 11.9px verdana; width: 32px; border: 0px solid; text-align: right\">x<input type=\"text\" name=\"fileheight\" style=\"font: 11.9px verdana; width: 32px; border: 0px solid\">px</FONT></TD></TR>"
			detailHTML += "<TR><TD colspan=4 align=center bgcolor=\"#FFFFFF\"><br><IMG name=\"detail\" onLoad=\"document.mydetail.filewidth.value = document.images.detail.width; document.mydetail.fileheight.value = document.images.detail.height; document.mydetail.filesize.value = document.images.detail.fileSize;\"><br><br></TD></TR>"
			detailHTML += "<TR><TD colspan=4 align=center bgcolor=\"#FFF3BD\"><input type=\"button\" name=\"close\" value=\"<?=$lang['close_this_window']?>\" onClick=\"window.close();\" style=\"width:150px; font: 9.9px verdana; text-align: center; color: #ffffff; background-color: #CE0000;\"></TD></TR>"
			detailHTML += "</TABLE></HTML></FORM>"

			w.document.open();
			w.document.write(detailHTML);

			detailImage = '<?=$activedir?>/' + document.select.files.options[document.select.files.selectedIndex].value;
			detailImageName = document.select.files.options[document.select.files.selectedIndex].value;
			w.document.images.detail.src = detailImage;
			w.document.mydetail.filename.value = detailImageName;
		
			w.document.close();
		}
	}
	-->
	</SCRIPT>
	</head>

	<body  leftmargin="2" marginwidth="2" topmargin="2" marginheight="2">
		<form name="select" action="<?=$PHP_SELF?>" method="POST"  enctype="multipart/form-data">
			<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="black">
				<tr>
					<td colspan="3" bgcolor="#fff3bd"><font style="font: 11.9px verdana; font-weight: bold; color: #CE0000;"><?=$lang['current_folder']?>: <a href="<?=$PHP_SELF?>?formname=<?=$formname?>&inputname=<?=$inputname?>&path=<?=$filename_with_path?>">..</a>/<a href="<?=$PHP_SELF?>?activedir=<?=$activedir_show?>&formname=<?=$formname?>&inputname=<?=$inputname?>&path=<?=$filename_with_path?>"><?=$activedir_show?></a></font></td>
				</tr>
				<tr>
					<td valign="top" bgcolor="white" style="width: 150px; line-height: 10px;"><font style="font: 9.9px verdana; color: #003366;">&nbsp;<?=$lang['subfolders']?>:</font><br>
						<select name="subfolders" size="7" style="font: 9.9px verdana; color: #000000; width=140px;" onChange="changeFolder(this.form); return false;">
<?
	@chdir($activedir);
	$handle=opendir('.');
		while ($folder = readdir($handle)) {
			if(is_dir($folder)) { 
				$dirlist[] = $folder;
			} 
		}
		closedir($handle);
		
		asort($dirlist);
		while (list ($key, $folder) = each ($dirlist)) {
			if ($folder != ".." && $folder != ".") {
				echo "<option value=\"".$PHP_SELF."?activedir=".$folder."\">".$folder."</option>";
			}
		}
?>
						</select>
						<font style="font: 9.9px verdana; color: #000000;">
						<?
						if(isset($HTTP_POST_VARS[upload])) {
							if($error==1) {
								echo $errormes;
							} else {
								echo "<font style=\"font: 9.9px verdana; color: #003366;\"><br>".$lang['file']." <b>".$img_name."</b> ".$lang['upload_success'].".<br></font>";
							}
						} else {
							echo "<br>".$lang['file_upload_into']."<br>".$lang['curr_selected_dir']."";
						}	
						?>
					</td>
					<td valign="top" bgcolor="white"><font style="font: 9.9px verdana; color: #003366;"><?=$lang['images']?>:</font><br>
						<select name="files" size="11" style="font: 9.9px verdana; color: #000000; width=140px;" onChange="is_selected = 1; showimage(); return false;">
<?
	$handle=opendir('.');
		while ($file = readdir($handle)) {
			if(is_file($file)) {
				$filelist[] = $file; 
			}
		}
		closedir($handle);

		asort($filelist);
		while (list ($key, $file) = each($filelist)) {
			ereg(".gif | .jpg",$file); 
				if ($file != "." && $file != ".." && (!is_dir($file))) {
					echo "<option value=\"".$file."\">".$file."</option>";
				}
		}
?>
						</select></td>
					<td valign="top" bgcolor="white"><font style="font: 9.9px verdana; color: #003366;"><?=$lang['preview_deform']?></font><br>
						<img class="h" name="preview" src="_i3/img/pix.gif" width="120" height="120" border="1" alt="<?=$lang['preview_image']?>" OnClick="Write_Input();"><br>
					<font style="font: 9.9px verdana; color: #000000;">&raquo;&nbsp;<a href="javascript:void" onClick="showDetail(); return false;"><?=$lang['view_img_detail']?></a><br>
						</font>
					</td>
				</tr>
				<tr>
					<td colspan=2 bgcolor="#F5F5F5"><nobr><font style="font: 9.9px verdana; color: #000000;">&nbsp;<?=$lang['upload_image']?>: <input type="file" name="img" size="16" style="font: 9.9px verdana; color: #000000; width: 230px; background-color: #FFFDEC;"> <input type="submit" name="upload" value="<?=$lang['upload']?>" style="font: 9.9px verdana; color: #000000; width:70px;"></font></nobr></td>
					<td bgcolor="#EEEEEE" align=center><input type="button" name="upload" value="<?=$lang['close']?>" onClick="window.close();" style="width:120px; font: 9.9px verdana; text-align: center; color: #ffffff; background-color: #CE0000;">
					<input type="hidden" name="formname" value="<?=$formname?>"><input type="hidden" name="inputname" value="<?=$inputname?>"><input type="hidden" name="path" value="<?=$path?>"><input type="hidden" name="activedir" value="<?=$activedir_show?>">
					</td>
				</tr>
			</table>
		</form>
	</body>

</html>