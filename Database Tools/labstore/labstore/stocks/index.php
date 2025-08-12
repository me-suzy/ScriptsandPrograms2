<?php
// top of page - the header - has mysql info too
include ("header.php");
 // finish header
/////////////////////////////////////////////////////////////////////////
echo ('<span style="color:#dcdcdc;">'.$log_status.'<a>'. $date); ?></a> || <a href="help/help.htm#what" onclick="return popitup('help/help.htm#what')">What is this</a> || <a href="help/help.htm#how" onclick="return popitup('help/help.htm#how')">How do I use it</a> || <a href="help/help.htm#get">Get software</a> || <a href="
<?php
echo ($mainsite_url.'">'.$mainsite_name);
?>
</a></span></p></div>
<div style="padding-left: 5px; padding-top: 5px;">
<table summary="front" width="600" border="0" cellpadding="0" cellspacing="3" style="border:0;  background-image:url('images/front_image.jpg'); background-position: center right; background-repeat: x-repeat;">
<colgroup><col valign="middle" align="right" /><col valign="middle" align="center" /><col valign="middle" align="left" /></colgroup>
<?php
// the textboxes
$to_show = '';
foreach ($modules_array as $key=>$value)
{
$to_show .= '<tr>
<td style="text-align:right; align:right; valign:middle;"><a href="modules/'.$value[3].'" title="browse all entries for - '.$value[2].'">'.$value[1].' &rarr;</a></td>
<td style="text-align:center; align:center; valign:middle; vertical-align:middle;"><form method="get" action="modules/'.$value[3].'">
<input type="text" name="sterm_1" id="sterm_1" size="10" maxlength="15"  /><input type="hidden" name="smenu_1" id="smenu_1" value="name" />
</form></td>
<td style="text-align:left; align:left; valign:middle; color:#dcdcdc;"><span style="background-color:#ffffff;">&larr; '.$value[2].'</span></td>
</tr>';
}
echo ($to_show);
// showing number of items in db
// depends on modules
$to_show = '';
foreach ($modules_array as $key=>$value)
{
$query = 'SELECT COUNT(*) FROM `'.$value[4].'`';
$num_of_entries = mysql_fetch_row(mysql_query($query));
$to_show .= $num_of_entries[0].' '.strtolower($value[1]).', ';
}
$to_show = substr($to_show, 0, -2); // remove last ', '
echo ("</table><p style=\"color: #dcdcdc;\">".$to_show." in database</p>");
/////////////////////////////////////////////////////////////////////////
include ("footer.php");
?>

