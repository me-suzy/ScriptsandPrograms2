<?php
ini_set("max_execution_time", "30000");


# choose a banner

include_once('../includes/db_connect.php');
?>
<?php
ini_set("max_execution_time", "30000");
/*****************************************************
* &copy; copyright 1999 - 2003 Interactive Arts Ltd.
*
* All materials and software are copyrighted by Interactive Arts Ltd.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
session_start();

# remove members from the mailing list when they click from within the newsletter
check_admin();


if (isset($HTTP_GET_VARS['mode']) && $HTTP_GET_VARS['mode']=='delete') {
	$id=$HTTP_GET_VARS['id'];
	$query="DELETE FROM dir_site_list WHERE site_id = $id";
	$return=mysql_query($query,$link);
}

$query="SELECT * FROM dir_site_list WHERE site_sponsor='N' AND site_live='Y'ORDER BY site_id LIMIT $start,$limit";
$return=mysql_query($query,$link);
$TOTAL=mysql_num_rows($return);
$sess_id="PHPSESSID=".session_id();
include("$CONST_INCLUDE_ROOT/Templates/maintemplate.header.inc.php");
?>


<?php include('../includes/admin_header.php'); ?>
<?php
while ($sql_array=mysql_fetch_object($return)) {

	$linkback_url="http://$sql_array->site_linkback";
	$linkback_url=trim($linkback_url);

	print("Checking:$sql_array->site_id> <a href='$linkback_url' target='blank'>$linkback_url</a>");
	flush();
	$contents="";

	if (@$fp=fopen($linkback_url,'r')) {
	$chktime=time();
		while(!feof($fp))
		{
		  $contents.= fread($fp,1024);
		  if (time() >= $chktime+3) break;
		}
		fclose($fp);

		if (strstr($contents,$CONST_LINK_ROOT)) {
			$test_result="<font color='green'>Passed</font>";
		} else {
			$test_result="<font color='red'>Failed</font> -> <a href='linkchecker.php?mode=delete&id=$sql_array->site_id&start=$start&limit=$limit'><font color='red'>[Delete]</font></a>";
		}

		print(" -> $test_result<br>");
	} else {
		print(" -> <a href='linkchecker.php?mode=delete&id=$sql_array->site_id&start=$start&limit=$limit'><font color='red'>Failed to open</font></a><br>");
	}

	flush();

}


print("<br>Link Checking Complete");
mysql_close($link);
?>
<p><input type="button" onClick="location.href='linkchecker.php?start=<?php echo $start ?>&limit=<?php echo $limit ?>&<?php echo $sess_id ?>'" value="Refresh" name="btnRefresh">&nbsp;
<input type="button" onClick="location.href='linkchecker.php?start=<?php echo $start+30 ?>&limit=<?php echo $limit ?>&<?php echo $sess_id ?>'" value="Next" name="btnNext"></p>
<p>&nbsp;</p>

<?include("$CONST_INCLUDE_ROOT/Templates/maintemplate.footer.inc.php");?>
