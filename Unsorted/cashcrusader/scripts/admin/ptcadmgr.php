<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
$description=addslashes($description);
$html=addslashes($html);
$id=addslashes($id);
$category=addslashes($category);
$alt_text=addslashes($alt_text);
$value=$value*100000;
if ($vtype=='cash'){
$value=$value*$admin_cash_factor;}
if ($save==2 and $oldid){
@mysql_query("update ".$mysql_prefix."ptc_ads set hrlock='$hrlock',id='$id',description='$description',image_url='$image_url',img_width='$img_width',img_height='$img_height',site_url='$site_url',html='$html',category='$category',run_quantity='$run_quantity',run_type='$run_type',alt_text='$alt_text',value='$value',vtype='$vtype',timer='$timer' where ptcid=$oldid");}
if ($save==1){
$searchphrase='';
@mysql_query("insert into ".$mysql_prefix."ptc_ads set  hrlock='$hrlock',id='$id',description='$description',image_url='$image_url',site_url='$site_url',html='$html',category='$category',run_quantity='$run_quantity',run_type='$run_type',alt_text='$alt_text',value='$value',vtype='$vtype',timer='$timer',img_width='$img_width',img_height='$img_height'");}
if ($mode=='Delete'){
@mysql_query("delete from ".$mysql_prefix."paid_clicks where id='$ptcid' and type='ptc'");
@mysql_query("delete from ".$mysql_prefix."ptc_ads where ptcid='$ptcid'");
@mysql_query("optimize table ".$mysql_prefix."paid_clicks");
}
echo "<html><title>Paid to click Ad Manager</title><script>window.focus()</script><body><center><h2>Paid to click Ad Manager</h2></center><hr> 
<a href=http://myecom.net/main/thegetpaidsite/ptc_instructions.htm target=_instructions><font size=4>CLICK HERE FOR INSTRUCTIONS</font></a><br><br>To place ads on your page use the following code. REPLACE: PUT_GROUP_HERE with the ad group name of the PTC ads you wish to display on that page<br>&lt;? get_ptc_ad('PUT_GROUP_HERE');?&gt;<br><form action=ptcadmgr.php method=post>Search PTC Ads Database: (leave blank to list all ads) <input type=text name=searchphrase><input type=hidden name=get value=search><input type=submit value='Search'><br><a href=ptcadmgr.php#adform target=_top>Create a new ad campaign</a></form><br>";
if ($get=='search'){$searchphrase="%".$searchphrase."%";}
echo "<table border=1><tr><th>Ad ID</th><th>Client ID</th><th>Ad Description</th><th>Ad Group</th><th>Type</th><th>Expire at</th><th>Views</td><th>Clicks</th><th>CTR</th><th>Value</th><th>Type</th><th>Timer</th><th>Lock</th><th>Last Shown</th><td></td></tr>";
if (!$searchphrase){$searchphrase='*****************************';}
$getads=@mysql_query("select * from ".$mysql_prefix."ptc_ads where description!='#PAID-START-PAGE#' and (ptcid like '$searchphrase' or id like '$searchphrase' or category like '$searchphrase' or description like '$searchphrase' or ptcid=LAST_INSERT_ID()) order by category,id,description");
while($row=@mysql_fetch_array($getads)){
$row[value]=$row[value]/100000;
if ($row[vtype]=='cash'){
$row[value]=$row[value]/$admin_cash_factor;}
if($row[views]){
$ctr=number_format($row[clicks]/$row[views],3)." to 1";}
$row[time]=substr($row[time],4,2)."/".substr($row[time],6,2)."/".substr($row[time],0,4)." ".substr($row[time],8,2).":".substr($row[time],10,2);
echo "<form action=ptcadmgr.php#adform method=post><input type=hidden name=searchphrase value='$searchphrase'><input type=hidden name=ptcid value='$row[ptcid]'><tr><td>$row[ptcid]</td><td>$row[id]</td><td>$row[description]</td><td>$row[category]</td><td>$row[run_type]</td><td>$row[run_quantity]</td><td>$row[views]</td><td>$row[clicks]</td><td>$ctr</td><td>$row[value]</td><td>$row[vtype]</td><td>$row[timer]</td><td>$row[hrlock]</td><td>$row[time]</td><td><input type=submit name=mode value='Delete'><input type=submit name=mode value='Edit'><input type=submit name=mode value='Copy'></td></tr></form>";
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
$row=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."ptc_ads where ptcid='$ptcid'"));
}
if (!$mode){$mode='Create New';}
$row[value]=$row[value]/100000;
if ($row[vtype]=='cash'){
$row[value]=$row[value]/$admin_cash_factor;}
?>
<a name="adform"></a><form action="ptcadmgr.php" method="POST" name="form">
<input type=hidden name=searchphrase value='<?= $searchphrase;?>'>
<input type="hidden" name="save" value="<?=$savemode;?>">
<? if ($savemode==2){?><input type=hidden name=oldid value='<?=$row[ptcid];?>'><? } ?>
	<table border=0 width=400><tr><th colspan=2><?= $mode;?> Ad</th></tr><tr><td>Client ID:</td><td><input type="text" name="id" value="<?=stripslashes($row[id]);?>">
        </td></tr><tr><td>Ad Description:</td><td><input type="text" name="description" value="<?=stripslashes($row[description]);?>">
	</td></tr><tr><td>Ad Group:</td><td><input type="text" name="category" value="<?=stripslashes($row[category]);?>">
        </td></tr><tr><td>Duration Type:</td><td><select name=run_type><option <? if ($row[run_type]=='ongoing'){ echo "selected";}?> value=ongoing>Never Expire<option <? if ($row[run_type]=='date'){ echo "selected";}?> value=date>Expire by certain date<option <? if ($row[run_type]=='clicks'){ echo "selected";}?> value=clicks>Expire after so many clicks<option <? if ($row[run_type]=='views'){ echo "selected";}?> value=views>Expire after so many exposures</select>
	</td></tr><tr><td>Duration:</td><td><input type="text" name="run_quantity" value=<?= $row[run_quantity];?>>
        </td></tr><tr><td colspan=2>(if using date to expire use the format YYYYMMDDHHMMSS)
        </td></tr><tr><td>Value:</td><td><input type=text name=value value=<?= number_format($row[value],5,".","");?>>
        </td></tr><tr><td>Value Type:</td><td><select name=vtype><option <? if ($row[vtype]=='points'){echo "selected";}?> value=points>Points<option <? if ($row[vtype]=='cash'){echo "selected";}?> value=cash>Cash</select>
        </td></tr><tr><td>Timer:</td><td><input type=text name=timer value=<?=$row[timer];?>>
        </td></tr><tr><td>Hours to lock ad after it is clicked</td><td><input type=text name=hrlock value=<?=$row[hrlock];?>>
	</td></tr><tr><td colspan=2><hr>BANNER AD</td></tr><tr><td>Banner image URL:</td><td><input type=text size=40 name=image_url value=<?=stripslashes($row[image_url]);?>>
	</td></tr><tr><td>Image width:</td><td><input type=text name=img_width value=<?= $row[img_width];?>></td></tr><tr><td>Image Height</td><td><input type=text name=img_height value=<?= $row[img_height];?>>
	</td></tr><tr><td>Site URL:</td><td><input type=text name=site_url size=40 value='<?=stripslashes($row[site_url]);?>'>
	</td></tr><tr><td>Alt Text:</td><td><input type=text name=alt_text size=40 value='<?=stripslashes($row[alt_text]);?>'>
	</td></tr><tr><td colspan=2><hr>
	HTML AD (HTML Ads can not be tracked as easly and require the use of a javascript popup to track the clicks not to mention the use of a timer is pointless as they could still close the advertisers page and get the points anyway)<br><textarea name="html" rows=10 cols=50><?=htmlentities(stripslashes($row[html]));?></textarea><br>
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
}
echo "</td></tr></table></body></html>";
