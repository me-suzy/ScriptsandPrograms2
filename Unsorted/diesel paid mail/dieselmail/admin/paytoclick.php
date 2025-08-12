<?php
    include ('../includes/global.php');	

    //DB connectivity
    $link=dbconnect();

    print "<font face='Arial' size='3'>";
	    print "<center><b>Pay-To-click</b></center><br>";

    print "</font>";

    print "<p><font face='arial' size='2'>If you are adding a text link, leave the Image URL field blank. If you are adding a banner link, leave the Link Text field blank.</font></p>";

    print "<p align='center'><table width='80%'>";
    print "<form action='paytoclick.php?action=add' method='POST'>";
    print "<input type='hidden' name='action' value='add'>";

    print "<tr><td><font face='arial' size='2'>User Id:</font></td>";
	    print "<td><input type='text' name='user_id' size='30'></td></tr>";
    print "<tr><td><font face='Arial' size='2'>Type:</font></td>";
	    print "<td><select name='type'><option value='banner'>Banner</option><option value='text'>Text</option></select>";
    print "<tr><td><font face='Arial' size='2'>Banner Name:</font></td>";
	    print "<td><input type='text' name='banner_name'></td></tr>";
    print "<tr><td><font face='Arial' size='2'>Image URL:</font></td>";
	    print "<td><input type='text' name='image_url' size='50'></td></tr>";
    print "<tr><td><font face='Arial' size='2'>Link URL:</font></td>";
	    print "<td><input type='text' name='link_url' size='50'></td></tr>";
    print "<tr><td><font face='Arial' size='2'>Image Width:</font></td>";
	    print "<td><input type='text' name='image_width' size='3' value='468'></td></tr>";
    print "<tr><td><font face='Arial' size='2'>Image Height:</font></td>";
	    print "<td><input type='text' name='image_height' size='3' value='60'></td></tr>";
    print "<tr><td><font face='Arial' size='2'>Text Link (no quotes)</font></td>";
	    print "<td><textarea name='text_link' wrap='virtual' rows='5' cols='25'></textarea></td></tr>";
    print "<tr><td><font face='Arial' size='2'>Click Amount:(in cents)</font></td>";
	    print "<td><input type='text' name='click_amount'size='20'></td></tr>";
    print "<tr><td><font face='Arial' size='2'># of Clicks Remaining:</font></td>";
	    print "<td><input type='text' name='clicks_remaining' value='2000'></td></tr>";
    print "<tr><td colspan='2'>&nbsp;</td></tr>";
    print "<tr><td colspan='2' align='center'><input type='submit' value='Add Banner'></td></tr>";	
    print "</form>";
    print "</table></p>";
	
if($action=="add")
{
    if ($type == "banner") {
	$query = "INSERT INTO pay_banners SET user_id = '$user_id', type = 'banner', banner_name = '$banner_name', image_url = '$image_url', link_url = '$link_url', image_width = $image_width, image_height = $image_height, click_amount = $click_amount, clicks_remaining = $clicks_remaining ,total_clicks=$clicks_remaining, date_added = now()";
    } 
    else {
	$query = "INSERT INTO pay_banners SET user_id = '$user_id', type = 'text', banner_name = '$banner_name', image_url = NULL, link_url = '$link_url', image_width = 0, image_height = 0, text_link = '$text_link', click_amount = $click_amount, clicks_remaining = $clicks_remaining, total_clicks=$clicks_remaining, date_added = now()";
    }
    if(mysql_query($query))
    echo "<br> Operation Completed";
}
elseif($action=="done")
{
    if($edit=="Edit Banner")
    {
	if ($type == "banner") {
	    $query = "UPDATE pay_banners SET user_id = '$user_id', type = 'banner', banner_name = '$banner_name', image_url = '$image_url', link_url = '$link_url', image_width = $image_width, image_height = $image_height, text_link = NULL, click_amount = $click_amount, clicks_remaining = $clicks_remaining ,total_clicks=$clicks_remaining WHERE banner_id = $banner_id";
	} 
        else {
	    $query = "UPDATE pay_banners SET user_id = '$user_id', type = 'text', banner_name = '$banner_name', image_url = NULL, link_url = '$link_url', image_width = 0, image_height = 0, text_link = '$text_link', click_amount = $click_amount, clicks_remaining = $clicks_remaining ,total_clicks=$clicks_remaining WHERE banner_id = $banner_id";
	}
	mysql_query($query);
    }
    elseif($delete=="Delete Banner")
    {
        $query = "DELETE FROM pay_banners WHERE banner_id = $banner_id";
        mysql_query($query);
    }
}
$contents=<<<HTM
     <hr>
     <br><font face="arial" size="3"><b><center>Existing Banners</center></b></font>
HTM;
    print $contents;
$query1 = "select * from pay_banners";

if($rs1 = mysql_query($query1))
{
    while ($data=mysql_fetch_array($rs1))
    {
	print "<p><font face='verdana' size='2'>If you are editing a text link, leave the Image URL field blank. If you are adding a banner link, leave the Link Text field blank.</font></p>";
	
	print "<p align='center'><table width='80%'>";
	print "<form action='paytoclick.php?action=done' method='POST'>";
	print "<input type='hidden' name='banner_id' value='$data[0]'>";
	print "<tr><td><font face='Arial' size='2'>User Id:</font></td>";
		print "<td><input type='text' name='user_id'  value='$data[user_id]'></td></tr>";
	print "<tr><td><font face='Arial' size='2'>Type:</font></td>";
	$ban="";
	$txt="";
	if($data[type]=="banner")
		$ban="selected";
	else
		$txt="selected";
		print "<td><select name='type'><option value='banner' $ban>Banner</option><option value='text' $txt>Text</option></select>";
	print "<tr><td><font face='Arial' size='2'>Banner Name:</font></td>";
		print "<td><input type='text' name='banner_name' value='$data[banner_name]'></td></tr>";
	print "<tr><td><font face='Arial' size='2'>Image URL:</font></td>";
		print "<td><input type='text' name='image_url' size='50' value='$data[image_url]'></td></tr>";
	print "<tr><td><font face='Arial' size='2'>Link URL:</font></td>";
		print "<td><input type='text' name='link_url' size='50' value='$data[link_url]'></td></tr>";
	print "<tr><td><font face='Arial' size='2'>Image Width:</font></td>";
		print "<td><input type='text' name='image_width' size='3' value='$data[image_width]'></td></tr>";
	print "<tr><td><font face='Arial' size='2'>Image Height:</font></td>";
		print "<td><input type='text' name='image_height' size='3' value='$data[image_height]'></td></tr>";
	print "<tr><td><font face='Arial' size='2'>Text Link (no quotes)</font></td>";
		print "<td><textarea name='text_link' wrap='virtual' rows='5' cols='25'>$data[text_link]</textarea></td></tr>";
	print "<tr><td><font face='Arial' size='2'>Click Amount:<br>(in cents)</font></td>";
		print "<td><input type='text' name='click_amount'size='20' value='$data[click_amount]'></td></tr>";
	print "<tr><td><font face='Arial' size='2'># of Clicks Remaining:</font></td>";
		print "<td><input type='text' name='clicks_remaining' value='$data[clicks_remaining]'</td></tr>";
	print "<tr><td colspan='2'>&nbsp;</td></tr>";
	print "<tr><td colspan='2' align='center'><input type='submit' name='edit' value='Edit Banner'>";	
	print "<input type='submit' name='delete' value='Delete Banner'></td></tr>";
	print "</form>";
	print "</table><hr></p>";
    }
}

$output=<<<HTM
     <br><br><pre> Click the link.<a href="$admin_url/admin.php">Go to Admin Index Page</a></pre>
HTM;

print $output;
dbclose($link);

?>