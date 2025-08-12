<table onmousemove="return false;" style="width: 650px;margin-bottom: 2px;" cellspacing="0" cellpadding="0" border="0">
<tr style="text-align:left;">
    <td width="10" height="19">
	<img height=19 alt="" src="images/interface/corner-tl.gif" width="10" border="0"></td>
    <td style="background-color:#DCDCD3">
	<span class="menuitem" onMouseOver="mover(this,'red','white')" onMouseOut="mover(this,'#DCDCD3','black')" onClick="javascript:showMenu('FM~upload');hideMenu('FM~clipboard');hideMenu('FM~mkdir');">upload</span><span class="menuitem" onMouseOver="mover(this,'orange','white');window.status='<?echo $clipStatus?>'" onMouseOut="mover(this,'#DCDCD3','black')" onClick="javascript:showMenu('FM~clipboard');hideMenu('FM~upload');hideMenu('FM~mkdir');">clipboard</span><span class="menuitem" onMouseOver="mover(this,'#FF3E96','white');window.status='<?
$fd = fopen ("$config[root_dir]/trashundo.txt", "r");
$i=0;
if ($fd) {
	while (!feof ($fd)) {
	$line = fgets($fd, 4096);
		if (strlen($line) > 3) {
			$i++;
		}
	}
fclose ($fd);
}
if ($i > 0){
	echo "$i objects in trash.";
}else {
	echo "Trash empty.";
}
?>'" onMouseOut="mover(this,'#DCDCD3','black');" onClick="javascript:popup('trash.php?dir='+currentDir+'', '320', '250', '20', '200');hideMenu('FM~clipboard');hideMenu('FM~upload');hideMenu('FM~mkdir');">trash</span><span class="menuitem" onMouseOver="mover(this,'#2062AF','white')" onMouseOut="mover(this,'#DCDCD3','black')" onClick="javascript:showMenu('FM~mkdir');hideMenu('FM~clipboard');hideMenu('FM~upload');document.mkd.dirName.select()">new folder</span><span class="menuitem" onMouseOver="mover(this,'#8A2BE2','white')" onMouseOut="mover(this,'#DCDCD3','black')" onClick="javascript:launchEdit('1')">new file</span><span class="menuitem" onMouseOver="mover(this,'black','white')" onMouseOut="mover(this,'#DCDCD3','black')" onClick="javascript:iconListSelect('all')">select all</span><span class="menuitem" onMouseOver="mover(this,'brown','white')" onMouseOut="mover(this,'#DCDCD3','black')" onClick="javascript:iconListSelect('none')">deselect all</span><span class="menuitem" onClick="javascript:document.location.href='?dir='+currentDir+'';" onMouseOver="javascript:mover(this,'green','white')" onMouseOut="javascript:mover(this,'#DCDCD3','black')">refresh</span><?if($view != "list"){?><span style="width:41px;position:">&nbsp;</span><span class="menuitem" onClick="javascript:document.location.href='?dir='+currentDir+'&view=list';" onMouseOver="javascript:mover(this,'#1e90ff','white')" onMouseOut="javascript:mover(this,'#DCDCD3','black')">view details</span><?} else {?><span style="width:49px;position:">&nbsp;</span><span class="menuitem" onClick="javascript:document.location.href='?dir='+currentDir+'&view=icons';" onMouseOver="javascript:mover(this,'#1e90ff','white')" onMouseOut="javascript:mover(this,'#DCDCD3','black')">view icons</span><?}?></td>
	<td width="10" height="19"><img width="10" height="19" alt="" src="images/interface/corner-tr.gif" border="0"></td>
</tr>
</table>