<!-- CLIPBOARD -->
<div id="FM~clipboard" class="popup" style="height:40px;" >
<div style="border-top:1px solid white;border-left:1px solid white;border-bottom:1px solid gray;border-right:1px solid gray;background-color:whitesmoke;padding:10px;">
<form action="?dir=<?echo prepUrl($dir)?>&act=paste" name="FM_clipform" method="post">
<?
$clipboardPath = "$config[root_dir]/clipboard/";
$d = dir($clipboardPath);
$i=0;
$clipboardOptions = "";
while ($clipfile = $d->read()) {
	if ($clipfile != "." && $clipfile != "..") {
		if (is_dir("$clipboardPath/$clipfile")) {
			$clipboardOptions .= "<br><input type=\"checkbox\" name=\"clipfiles[]\" value=\"$clipfile\" checked> <label for=\"$clipfile\" style=\"font-weight:bold\">$clipfile</label>";
$i++;
		} else {
			$clipboardOptions .= "<br><input type=\"checkbox\" name=\"clipfiles[]\" style=\"color:navy\" value=\"$clipfile\" checked id=\"CLIP~$clipfile\"> <label for=\"CLIP~$clipfile\">$clipfile (".getFileSize (filesize("$clipboardPath/$clipfile")).")</label>";
$i++;
		}
	}
}
$d->close();
echo $clipboardOptions;
?>
<div align="center">
<?if ($i > 0){
$clipStatus = "$i objects in clipboard.";
?>
<br><br>
<input type="submit" class="button" name="submit" value="paste"> 
<input type="submit" class="button" name="submit" value="discard">
<input type="button" onClick="javascript:hideMenu('FM~clipboard')" class="button" value="close">
<?} else {
$clipStatus = "Clipboard empty.";
?>
Clipboard empty.
<br><br>
<input type="button" onClick="javascript:hideMenu('FM~clipboard')" class="button" value="close">
<?}?>
</div>
</form>
</div>
</div>


<?/*?>
<!-- trash -->
<div id="FM~trash" class="popup" style="height:40px;" >
<div style="border-top:1px solid white;border-left:1px solid white;border-bottom:1px solid gray;border-right:1px solid gray;background-color:whitesmoke;padding:10px;">
<form action="?dir=<?echo prepUrl($dir)?>&act=trash" name="FM_trashform" method="post">
<?
$i=0;
$fd = fopen ("$config[root_dir]/trashundo.txt", "r");
$trashOptions = "";
if ($fd) {
while (!feof ($fd)) {
	$line = fgets($fd, 4096);
	if (strlen($line) > 3) {
		$i++;
		$splited = split(" @#@ ",$line);
?>
<input type="checkbox" name="trashfiles[]" value="<?echo $splited[2]?>" checked><span title="Deleted from: <?echo $splited[0]?>" <?if(is_dir($config[root_dir]."/trash/".trim($splited[2]))){?>style="font-weight:bold;"<?}?>><?echo $splited[1] ?></span><br>
<?
	}
}

fclose ($fd);
}
?>
<div align="center">
<?if ($i > 0){
$trashStatus = "$i objects in trash.";
?>
<br><br>
<input type="hidden" name="action" value="">
<input type="button" class="button" value="restore" onClick="javascript:this.form.action.value='restore';this.form.submit()"> 
<input type="button" class="button" value="move here" onClick="javascript:this.form.action.value='move';this.form.submit()"> 
<input type="button" class="button" value="delete" onClick="javascript:this.form.action.value='delete';this.form.submit()">
<input type="button" onClick="javascript:hideMenu('FM~trash')" class="button" value="close">
<?} else {
$trashStatus = "Trash empty.";
?>
Trash empty.
<br><br>
<input type="button" onClick="javascript:hideMenu('FM~trash')" class="button" value="close">
<?}?>
</div>
</form>
</div>
</div>
<?*/?>



<!-- UPLOAD FORM -->

<div class="popup" id="FM~upload" style="width:215px;" >
<div style="border-top:1px solid white;border-left:1px solid white;border-bottom:1px solid gray;border-right:1px solid gray;background-color:whitesmoke;padding:10px;">
<input class="button" type="button" 
onClick="javascript:addUploadFields(this.value)" value="add field...">
<br>
<br>
<form action="?act=upload&dir=<?echo prepUrl($dir)?>" method="post" enctype="multipart/form-data">
<input type="file" name="uploadfiles[]" class="inputfile" onFocus="javascript:writing=true;" onBlur="javascript:writing=false;">
<span id="FM~cust"></span>
<br><br>
<div align="center">
<input type="submit" name="submit" value="upload" class="button"> 
<input type="button" value="cancel" class="button" onClick="javascript:hideMenu('FM~upload');document.getElementById('FM~cust').innerHTML=''">
<br><br>
<span class="comment">Max total upload size: <?echo ini_get("upload_max_filesize")?></span>
</div>
</form>
</div>
</div>
<!-- MKDIR FORM -->

<div id="FM~mkdir" class="popup" style="width:200px;" >
<div style="border-top:1px solid white;border-left:1px solid white;border-bottom:1px solid gray;border-right:1px solid gray;background-color:whitesmoke;padding:10px;">
<form action="?act=mkdir&dir=<?echo prepUrl($dir)?>" name="mkd" method="post">
<div align="center">
<br>
<input type="text" name="dirName" value="new folder" onFocus="javascript:writing=true;" onBlur="javascript:writing=false;">
<br><br>
<input type="submit" class="button" value="create">
<input type="button" value="cancel" class="button" onClick="javascript:hideMenu('FM~mkdir')"></div>
</form>
</div>
</div>
<!-- RENAME FORM -->

<div id="FM~renDiv" class="popup" style="width:200px;">
<div style="border-top:1px solid white;border-left:1px solid white;border-bottom:1px solid gray;border-right:1px solid gray;background-color:whitesmoke;padding:10px;">
<form action="?act=rename&dir=<?echo prepUrl($dir)?>" name="renameForm" method="post">
<div align="center">
<br>
<input type="text" name="newName" value="" onFocus="javascript:writing=true;" onBlur="javascript:writing=false;">
<br><br>
<input type="hidden" class="button" value="" name="oldName">
<input type="submit" class="button" value="rename">
<input type="button" value="cancel" class="button" onClick="javascript:hideMenu('FM~renDiv')"></div>
</form>
</div>
</div>

<!-- INNER POPUP -->
<img height="0" width="0" src="images/interface/loading.gif" border="0" style="display:none;visibility:hidden;height:0px;width:0px;">
<div id="FM~popupDIV" class="popup">
	<div style="border-top:1px solid white;border-left:1px solid white;border-bottom:1px solid gray;border-right:1px solid gray;background-color:whitesmoke;padding:10px;"><div align="right" style="margin-bottom:10px;position:relative;right:-25px;"><input type="button" class="button" onClick="javascript:closePopup()" value="close"></div>
		<div id="FM~iframeDIV" style="border:1px solid silver; background-image: url(images/interface/loading.gif); background-repeat: no-repeat; background-position: center;">
		</div>
	</div>
</div>