<?php
include ("../includes/global.php");

if($action=="open")
{
    print"<html>";
    print"<title>Please Wait</title>";
    print"<head>";
    print"<META HTTP-EQUIV='REFRESH' CONTENT='20; URL=$site_url/waiting.php?email=$email&banner_id=$banner_id&action=done'>";
    print"</head>";
    print"<body>You must wait 20 seconds.</body>";
    print"</html>";
}
elseif($action=="done")
{
    //DB connectivity
    $link=dbconnect();
    $query = "SELECT link_url, click_amount FROM pay_banners WHERE banner_id = $banner_id";

    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $link_url = $row["link_url"];
    $click_amount = $row["click_amount"];
    mysql_free_result($result);

    //check wheter 
    $query = "SELECT count(*) FROM banner_clicks WHERE (member_id = '$email') AND (banner_id = $banner_id) AND (click_date = now())";
    $result = mysql_query($query);
    $row = mysql_fetch_row($result);
    $count = $row[0];
    mysql_free_result($result);
    $mem_id = get_mem_id($email);
    if ($count > 0) {
    } else 
    {
	$qry="select * from member_earnings where mem_id=$mem_id";
	if($out=mysql_query($qry))   {
	    $no=mysql_num_rows($out);
	    $out=mysql_fetch_array($out);
            if($no == 0)   {
		$query = "INSERT INTO member_earnings SET pd_clickban = $click_amount , mem_id=$mem_id";
 		mysql_query($query);
	    }
	    else    {
	        $ban_amt=$out[pd_clickban];
$amt1=$ban_amt + $click_amount;
	        $query = "UPDATE member_earnings SET pd_clickban = $amt1 WHERE mem_id = $mem_id";
 	        mysql_query($query);
//echo $query;
            } 
         }
	 $query = "INSERT INTO banner_clicks SET member_id = '$email', banner_id = $banner_id, click_date = now()";
	 mysql_query($query);
         $query = "UPDATE pay_banners SET clicks_remaining = clicks_remaining - 1 WHERE banner_id = $banner_id";
	 mysql_query($query);
    }

//db close funtion.
dbclose($link);

print <<<EOF
<html>
<title>Thanks...</title>
<body onLoad="window.close()">
</body>
</html>
EOF;

}
?>