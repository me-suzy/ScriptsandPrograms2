<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
$value=$value*100000;
if ($vtype=='cash'){
$value=$value*$admin_cash_factor;}
if ($save==1){
@mysql_query("delete from ".$mysql_prefix."paid_clicks where id=$ptcid");
@mysql_query("delete from ".$mysql_prefix."ptc_ads where description='#PAID-START-PAGE#'");
@mysql_query("insert into ".$mysql_prefix."ptc_ads set html='$html',hrlock='$hrlock',description='#PAID-START-PAGE#',site_url='$site_url',run_type='ongoing',value='$value',vtype='$vtype'");}
echo "<html><title>Paid Start Page Manager</title><script>window.focus()</script><body><center><h2>Paid Start Page Manager</h2></center><hr> 
Place this code: <b>&lt;? show_start_page_url(); ?&gt;</b> on the page where you wish to display the start page url information for the users<br><br>";
$savemode=1;
$row=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."ptc_ads where description='#PAID-START-PAGE#'"));
$row[value]=$row[value]/100000;
if ($row[vtype]=='cash'){
$row[value]=$row[value]/$admin_cash_factor;}
?>
<a name="adform"></a><form action="startpage.php" method="POST" name="form">
<input type="hidden" name="save" value="1">
<? if (!$row[html]){$row[html]="$domain Paid Start Page";}?>
        <input type=hidden name=ptcid value=<? echo $row[ptcid]?>>
	<table border=0 width=400><tr><th colspan=2><?= $mode;?> Start Page</th></tr>
        <tr><td>Description</td><td><input type=text name=html value='<? echo $row[html];?>'></td></tr>
        <tr><td>Value:</td><td><input type=text name=value value=<?= number_format($row[value],5,".","");?>>
        </td></tr><tr><td>Value Type:</td><td><select name=vtype><option <? if ($row[vtype]=='points'){echo "selected";}?> value=points>Points<option <? if ($row[vtype]=='cash'){echo "selected";}?> value=cash>Cash</select>
        </td></tr><tr><td>Hours between each credit</td><td><input type=text name=hrlock value=<?=$row[hrlock];?>>
	</td></tr><tr><td>Site URL:</td><td><input type=text name=site_url size=40 value='<?=stripslashes($row[site_url]);?>'>
</td></tr><tr><td colspan=2>	<input type="submit" name="add" value="Save">
</form>
<? 
echo "</td></tr></table></body></html>";
