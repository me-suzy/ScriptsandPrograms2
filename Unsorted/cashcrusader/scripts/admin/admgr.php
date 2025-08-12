<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
$description=addslashes($description);
$html=addslashes($html);
$id=addslashes($id);
$category=addslashes($category);
$alt_text=addslashes($alt_text);
if ($save==2 and $oldid){
@mysql_query("update ".$mysql_prefix."rotating_ads set id='$id',description='$description',image_url='$image_url',img_width='$img_width',img_height='$img_height',site_url='$site_url',html='$html',category='$category',run_quantity='$run_quantity',run_type='$run_type',alt_text='$alt_text',popupurl='$popupurl',popupwidth='$popupwidth',popupheight='$popupheight',popuptype='$popuptype' where bannerid=$oldid");}
if ($save==1){
$searchphrase='';
@mysql_query("insert into ".$mysql_prefix."rotating_ads set id='$id',description='$description',image_url='$image_url',site_url='$site_url',html='$html',category='$category',run_quantity='$run_quantity',run_type='$run_type',alt_text='$alt_text',popupurl='$popupurl',popupwidth='$popupwidth',popupheight='$popupheight',popuptype='$popuptype',img_width='$img_width',img_height='$img_height'");}
if ($mode=='Delete'){
@mysql_query("delete from ".$mysql_prefix."rotating_ads where bannerid='$bannerid'");
}
echo "<html><body><center><h2>Rotating Ad Manager</h2></center><hr> 
<a href=http://myecom.net/main/thegetpaidsite/rotator_instructions.htm target=_instructions><font size=4>CLICK HERE FOR INSTRUCTIONS</font></a><br><br>To place ads on your page use the following code. REPLACE: PUT_GROUP_HERE with the ad group name of the ads you wish to rotate<br><b>&lt;? getad('PUT_GROUP_HERE');?&gt;</b><br>Or if you would like to display your ads on different sites that are not the Cash Crusader software package you can place this code:
<br><b>&lt;script language=\"JavaScript\" src=\"".$scripts_url."runner.php?GA=PUT_GROUP_HERE\"&gt;&lt;/script&gt;<br></b>
<form action=admgr.php method=post>Search Rotating Ads Database: (leave blank to list all ads) <input type=text name=searchphrase><input type=hidden name=get value=search><input type=submit value='Search'><br><a href=admgr.php#adform target=_top>Create a new ad campaign</a></form><br>";
if ($get=='search'){$searchphrase="%".$searchphrase."%";}
echo "<table border=1><tr><th>Ad ID</th><th>Client ID</th><th>Ad Description</th><th>Ad Group</th><th>Type</th><th>Expire at</th><th>Views</td><th>Clicks</th><th>CTR</th><th>Last Shown</th><td></td></tr>";
if (!$searchphrase){$searchphrase='*****************************';}
$getads=@mysql_query("select * from ".$mysql_prefix."rotating_ads where bannerid like '$searchphrase' or id like '$searchphrase' or category like '$searchphrase' or description like '$searchphrase' or bannerid=LAST_INSERT_ID() order by category,id,description");
while($row=@mysql_fetch_array($getads)){
if($row[views]){
$ctr=number_format($row[clicks]/$row[views],3)." to 1";}
$row[time]=substr($row[time],4,2)."/".substr($row[time],6,2)."/".substr($row[time],0,4)." ".substr($row[time],8,2).":".substr($row[time],10,2);
echo "<form action=admgr.php#adform method=post><input type=hidden name=searchphrase value='$searchphrase'><input type=hidden name=bannerid value='$row[bannerid]'><tr><td>$row[bannerid]</td><td>$row[id]</td><td>$row[description]</td><td>$row[category]</td><td>$row[run_type]</td><td>$row[run_quantity]</td><td>$row[views]</td><td>$row[clicks]</td><td>$ctr</td><td>$row[time]</td><td><input type=submit name=mode value='Delete'><input type=submit name=mode value='Edit'><input type=submit name=mode value='Copy'></td></tr></form>";
}
echo "</table>";
$count=mysql_num_rows($getads);
if ($searchphrase){echo "<b>".$count." ad(s) found</b><br><br>";}
$savemode=1;
$row='';
if ($mode=='Edit' or $mode=='Copy'){
$savemode=2;
if ($mode=='Copy'){
$savemode=1;}
$row=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."rotating_ads where bannerid='$bannerid'"));
}
if (!$mode){$mode='Create New';}
?>
<a name="adform"></a><form action="admgr.php" method="POST" name="form">
<input type=hidden name=searchphrase value='<?= $searchphrase;?>'>
<input type="hidden" name="save" value="<?=$savemode;?>">
<? if ($savemode==2){?><input type=hidden name=oldid value='<?=$row[bannerid];?>'><? } ?>
	<table border=0 width=400><tr><th colspan=2><?= $mode;?> Ad</th></tr><tr><td>Client ID:</td><td><input type="text" name="id" value="<?=stripslashes($row[id]);?>">
        </td></tr><tr><td>Ad Description:</td><td><input type="text" name="description" value="<?=stripslashes($row[description]);?>">
	</td></tr><tr><td>Ad Group:</td><td><input type="text" name="category" value="<?=stripslashes($row[category]);?>">
        </td></tr><tr><td>Duration Type:</td><td><select name=run_type><option <? if ($row[run_type]=='ongoing'){ echo "selected";}?> value=ongoing>Never Expire<option <? if ($row[run_type]=='date'){ echo "selected";}?> value=date>Expire by certain date<option <? if ($row[run_type]=='clicks'){ echo "selected";}?> value=clicks>Expire after so many clicks<option <? if ($row[run_type]=='views'){ echo "selected";}?> value=views>Expire after so many exposures</select>
	</td></tr><tr><td>Duration:</td><td><input type="text" name="run_quantity" value=<?= $row[run_quantity];?>>
        </td></tr><tr><td colspan=2>(if using date to expire use the format YYYYMMDDHHMMSS)
	</td></tr><tr><td colspan=2><hr>BANNER AD</td></tr><tr><td>Banner image URL:</td><td><input type=text size=40 name=image_url value=<?=stripslashes($row[image_url]);?>>
	</td></tr><tr><td>Image width:</td><td><input type=text name=img_width value=<?= $row[img_width];?>></td></tr><tr><td>Image Height</td><td><input type=text name=img_height value=<?= $row[img_height];?>>
	</td></tr><tr><td>Site URL:</td><td><input type=text name=site_url size=40 value='<?=stripslashes($row[site_url]);?>'>
	</td></tr><tr><td>Alt Text:</td><td><input type=text name=alt_text size=40 value='<?=stripslashes($row[alt_text]);?>'>
        </td></tr><tr><td colspan=2><hr>Pop-up/Pop-Under AD (Pop-Up's and Pop-Unders Ads will never expire if the duration type is set to expire after so many clicks)</td></tr>
        <tr><td>Site URL:</td><td><input type=text name=popupurl size=40 value='<?=stripslashes($row[popupurl]);?>'>
        </td></tr><tr><td>Window Width:</td><td><input type=text name=popupwidth value=<?= $row[popupwidth];?>>
	</td></tr><tr><td>Window Height:</td><td><input type=text name=popupheight value=<?= $row[popupheight];?>> 
        </td></tr><tr><td>Window Type:</td><td><select name=popuptype><option <? if ($row[popuptype]=='popunder'){ echo "selected";}?> value=popunder>Pop-Under<option <? if ($row[popuptype]=='popup'){ echo "selected";}?> value=popup>Pop-Up</select>
	</td></tr><tr><td colspan=2><hr>
	HTML AD (HTML Ads will never expire if the duration type is set to expire after so many clicks)<br><textarea name="html" rows=10 cols=50><?=htmlentities(stripslashes($row[html]));?></textarea><br>
	<input type="submit" name="add" value="Save Ad">
</form><hr>
<? 
if ($mode!='Create New'){
$row[html]=stripslashes($row[html]);
$row[alt_text]=stripslashes($row[alt_text]);
if ($row[image_url]){ 
$width='';
$height='';
if ($row[img_width]){
$width="width=$row[img_width]";}
if ($row[img_height]){
$height="height=$row[img_height]";} 
echo "<table border=0 cellpadding=0 cellspacing=0 bgcolor=ffffff><tr><td><a href=$row[site_url] target=_blank><img src=$row[image_url] alt='$alt_text' $width $height border=0></a></td></tr></table>";}
echo "<table border=0 cellpadding=0 cellspacing=0><tr><td>$row[html]</td></tr></table>";
if ($row[popupurl]){
$width='';
$height='';
if ($row[popupwidth]){
$width="width=$row[popupwidth],";}
if ($row[popupheight]){
$height="height=$row[popupheight],";}
$thetime="i".time();
?>
<SCRIPT language='JavaScript'><!--
var iMyWidth = '<?=$width;?>';
var iMyHeight = '<?=$height;?>';
var iMyURL='<?= $row[popupurl];?>';
var iMyPopUp='<?= $row[popuptype];?>'; 
<?= $thetime;?>=window.open(iMyURL,"<?= $thetime;?>",iMyWidth + iMyHeight +"left=0,top=0,toolbars=0, scrollbars=0, location=0, statusbars=0, menubars=0, resizable=0");
if (iMyPopUp=='popunder'){
<?= $thetime;?>.blur()
window.focus()
}
//-->
</SCRIPT>
<?}
}
echo "</td></tr></table></body></html>";
