<?
include ("../includes/global.php");

links();

if($email != "" && $ps != "")
{
    $member_links=menu($email,$ps);
    print "$member_links";
}

//DB connectivity
$link=dbconnect();

$sql = "SELECT mem_id, password FROM member_details where email_id='$email' ";
if($res = mysql_query($sql))
{
      $res = mysql_fetch_array($res);
      if(md5($res[1]) == $ps)
      {
             $content = file_reader("$site_html_path/referral_links.html");
             $content=str_replace("[site_url]",$site_url,$content);
             $content=str_replace("[siteadds]",$siteadds,$content);
             $content=str_replace("[refid]",$res[0],$content);
             $content=str_replace("[banner_url]",$banner_url,$content);
             print $content;
       }
       else
       {
             $out_message= <<< HTM
<b>Error in Login. Either the User Id or Password
entered is Wrong. <br> <br><br><br>Go back and provide a valid Login Details</b>
<META HTTP-EQUIV=REFRESH CONTENT="3; URL=$site_url/index.php">
HTM;
             //function to display message
             out_message($out_message,$color_feedback_bad);
       }
}

//to close the link to DB
dbclose($link);

print "$html_footer";
?>