<?
include ("../includes/global.php");

if($email != "" && $ps != "" && $action != "clickbanner")
{
    links();
    $member_links=menu($email,$ps);
    print "$member_links<br><br>";
}

//DB connectivity
$link=dbconnect();

$query1 = "SELECT mem_id,password FROM member_details where email_id='$email'";
if($res = mysql_query ($query1))
{
    $res = mysql_fetch_array($res);
    if(md5($res[1]) == $ps)
    {
        if ($action == "showbanners") 
	{
	    print "<p><font face='arial' size='2'>Welcome to the Paid-to-Click & u will be paid for clicks on the banners below and view the advertiser's web site. Each banner displays the amount you will earn for clicking on it.</font></p>";

	    $query = "SELECT COUNT(*) FROM pay_banners WHERE clicks_remaining > 0";
	    $result = mysql_query($query);
	    $row = mysql_fetch_row($result);
		    $count = $row[0];
	    mysql_free_result($result);

	    print "<p align='center'><table width='100%'>";

	    if (!$index)
		    $index = 0;
	    if (!$rows)
		    $rows = 10;

	    $query = "SELECT banner_id, type, banner_name, image_url, image_width, image_height,link_url, text_link, click_amount, date_added  FROM pay_banners WHERE clicks_remaining > 0  ORDER BY click_amount DESC, banner_id DESC LIMIT $index, $rows";
            $query23 ="select * from pay_banners WHERE clicks_remaining > 0";
            if($result23 = mysql_query($query23))
                 $cnt=mysql_num_rows($result23);
	    $result = mysql_query($query);
	    
	    while ($row = mysql_fetch_array($result)) 
	    {
		$query1 = "SELECT * FROM banner_clicks WHERE (member_id = '$email') AND (banner_id = $row[banner_id]) AND (click_date = now())";
		$result1 = mysql_query($query1); 
		$num = mysql_num_rows($result1);
		if($num==0)
		{
		    print "<tr><td align='center'><b>" . $row["banner_name"] . "</b><b> " . $row["click_amount"] ."  (in cents) </b></td></tr>";

		    if ($row["type"] == "banner") 
		    {
			print "<tr><td align='center'><a href='$row[link_url]' onClick=\"javascript:window.open('waiting.php?email=$email&banner_id=$row[banner_id]&action=open','loading','toolbar=no,left=50,top=50,scrollbars=no,resizable=no,width=400,height=100,alwaysRaised=yes')\"><img src='" . $row["image_url"] . "' width='" . $row["image_width"] . "' image_height = '" . $row["image_height"] . "' border='0'></a></td></tr>";

		    }
		    else 
		    {
				print "<tr><td align='center'><a href='$row[link_url]' onClick=\"javascript:window.open('waiting.php?email=$email&banner_id=$row[banner_id]&action=open','loading','toolbar=no,location=no,scrollbars=no,resizable=no,width=400,height=100')\"><font face='verdana' size='2'><b>" . $row["text_link"] . "</b></font></a></td></tr>";

		     }
		     print "<tr><td align='center'>&nbsp;</td></tr>";
		 }
	    }
	    mysql_free_result($result);
	    if(($cnt>$rows) &&(($index+$rows)<=$cnt ))
	    {
		print "<tr><td>&nbsp;</td></tr>";
		print "<tr><td align='right'><font face='verdana' size='2'><b><a href='$PHP_SELF?email=$email&ps=$ps&action=showbanners&index=" . ($index + $rows) . "'>Next</a></b></font></td></tr>";
	    }
	    print "</table></p>";
        }
    }
    else
        print  "<br><br><br><br><center><b>Hi&nbsp;<font
color=green>".$email."</font>&nbsp;&nbsp;You have entered a invalid user id or password</b></center></html>";
}
else
    print  "<br><br><br><br><center><b>Hi&nbsp;<font
color=green>".$email."</font>&nbsp;&nbsp;You have entered a invalid user id or password</b></center></html>";

dbclose($link);

print "$html_footer";
?>

