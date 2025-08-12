<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
{
	//
	// Redirect unauthorized user to the admin login page.
	
	echo "<Script language=\"javascript\">window.location=\"login.php\"</script>";
}
else
{
	include ("config.php");
	include( "settings.inc.php");
	include_once ("header.inc.php");
	echo "<h2>".$lng[259]."</h2>";

	$phpv = phpversion();
	$mysql_version = mysql_get_server_info();

	$mysql_size = $dbsize; 
	$estore_size = abcDirectorySize("$site_dir");
	$images_size = abcDirectorySize("$site_dir/images");
	$total_size = $estore_size + $mysql_size;
	
	$estore_size = abcFormatSize($estore_size);
	$images_size = abcFormatSize($images_size);
	$total_size = abcFormatSize($total_size);

	$rs = mysql_query ("SHOW TABLE STATUS FROM $dbname");
	$mysql_size = 0;
	while ($r = mysql_fetch_array($rs))
		$mysql_size += $r[Data_length] + $r[Index_length];

	$mysql_size = abcFormatSize($mysql_size);

	$sql_count = "select * from ".$prefix."store_order_sum where sec_order_id <>''";
	$result_count = mysql_query ($sql_count);
	$total_orders = mysql_num_rows($result_count);
	
	$sql_count = "select * from ".$prefix."store_order_sum where sec_order_id <>'' and status='0'";
	$result_count = mysql_query ($sql_count);
	$total_queued = mysql_num_rows($result_count);
	
	$sql_count = "select * from ".$prefix."store_order_sum where sec_order_id <>'' and status='2'";
	$result_count = mysql_query ($sql_count);
	$total_complete = mysql_num_rows($result_count);
	
	$sql_count = "select * from ".$prefix."store_order_sum where sec_order_id <>'' and status='1'";
	$result_count = mysql_query ($sql_count);
	$total_incomplete = mysql_num_rows($result_count);
	
	$sql_count = "select * from ".$prefix."store_order_sum where sec_order_id <>'' and status='3'";
	$result_count = mysql_query ($sql_count);
	$total_declined = mysql_num_rows($result_count);
	
	$total_orders = number_format($total_orders);
	$total_complete = number_format($total_complete);
	$total_queued = number_format($total_queued);
	$total_incomplete = number_format($total_incomplete);
	$total_declined = number_format($total_declined);

echo "<table border=\"1\" bordercolor=\"#e6e6e6\" cellspacing=\"0\" cellpadding=\"4\" width=\"100%\">
<tr>
<td valign=\"top\"><b>".$lng[260]."</b></td>
<td valign=\"top\"><b>".$lng[261]."</b></td>
<td valign=\"top\"><b>".$lng[262]."</b></td>
<td valign=\"top\"><b>".$lng[263]."</b></td>
<td valign=\"top\"><b>".$lng[264]."</b></td>
</tr>
<tr>
<td valign=\"top\">$total_orders</td>
<td valign=\"top\">$total_complete</td>
<td valign=\"top\">$total_queued</td>
<td valign=\"top\">$total_incomplete</td>
<td valign=\"top\">$total_declined</td>
</tr>
</table>
";


echo "
<form action=\"orders.php\" method=\"post\">
<b>".$lng[265].":</b><br>
<input type=\"text\" name=\"searchStr\" class=\"textbox\">
&nbsp;<input type=\"submit\" class=\"submit\" value=\"".$lng[266]."\">
</form>
";
	
include_once ("footer.inc.php");
	
}  // end if session is registered


////////////////////////////////////////////////////////////////
// abcDirectorySize
// 	Calculates the size of given directory and all its
//	subdirectories.
//
////////////////////////////////////////////////////////////////

function abcDirectorySize( $directory )
{
	if( !is_dir( $directory ) )
		return -1;
	
	$size = 0;
	
	if ($DIR = opendir($directory))
	{
		while( ( $dirfile = readdir($DIR)) !== false )
		{
			if( is_link($directory . '/' . $dirfile) ||
				$dirfile == '.' ||
				$dirfile == '..' )
			{
				continue;
			}
			
			if (is_file($directory . '/' . $dirfile))
				$size += filesize($directory . '/' . $dirfile);
			elseif( is_dir($directory . '/' . $dirfile ) )
			{
				$dirSize = abcDirectorySize($directory . '/' . $dirfile);
				if( $dirSize >= 0 )
					$size += $dirSize;
				else
					return -1;
			}
		}
		
		closedir($DIR);
	}

	return $size;
}

?>
