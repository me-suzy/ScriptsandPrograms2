<?
include ("../includes/global.php");

print "<center>";
links();
print "</center><br><br>";

//connecting DB
$link=dbconnect();

function configure($query)
{
   global $emailadds,$usertotal;
   if($res = mysql_query ($query));
   {
       while ($res1 = mysql_fetch_array($res))
       {
           $emailadds.=$res1[0].",";
           $usertotal++;
       }
   }
   return $emailadds;
   mysql_free_result($res);
}

if($action=="mailer")
{
    $sql="SELECT  count(*) from advt_email where ad_id=$adid and email_id='$id' " ;
    $query1="select count(*) from advt_banner where email_id='$email'";
    $query2="select count(*) from advt_email where email_id='$email'";

    $res = mysql_query($sql);
    $res=mysql_fetch_array($res);

    if($res[0]>0)
    {
        for ($i = 1; $i <= 12; $i++)
        {
            $str = $i;
	    if ($i <=  9)
            {
                $str = "0$i";
            }
            $monthstr .= "<option value='$str'>$str</option>\n";
        }

        for ($i = 1; $i <= 31; $i++)
        {
            $str = $i;
            if ($i <= 9)
            {
                $str = "0$i";
            }
            $daystr .= "<option value='$str'>$str</option>\n";
        }

       for ($i = 2000; $i <= 2010 ; $i++)
       {
           $str = $i;
           $yearstr .= "<option value='$str'>$str</option>\n";
       }

        $today=date("m/d/Y");
	$content = file_reader("$site_html_path/advertiser_mailer.html");
	$content=str_replace("[site_url]",$site_url,$content);
	$content=str_replace("[mail_date]",$mail_date,$content);
	$content=str_replace("[monthstr]",$monthstr,$content);
	$content=str_replace("[daystr]",$daystr,$content);
	$content=str_replace("[yearstr]",$yearstr,$content);
	$content=str_replace("[today]",$today,$content);
	$content=str_replace("[adid]",$adid,$content);
	$content=str_replace("[id]",$id,$content);
	print $content;
    }
    else
    {
        $out_message= "<b>You are not a valid advertiser</b>";
        //function to display message
        out_message($out_message,$color_feedback_bad);
    }
}
elseif($B1=="SendMail" && $action != "mailer")
{
    $mail_date=$month."/".$day."/".$year;
    $usertotal=0;
    $emailadds="";
    $query1="select email_id from member_details";

    //function to collect all email ids
    $emailadds=configure($query1);

    $out_message= <<< HTM
<meta Content-type: text/html>\n\n<h1>Sending E-Mail</h1><i>Please wait for the WHOLE page to load.
DO NOT REFRESH THIS PAGE, OR ELSE THE MAILING WILL BE SENT OUT AGAIN.</i>
HTM;

    //function to display message
    out_message($out_message,$color_feedback_good);

    if(send_mail($emailadds,$id,$subject,$emessage))
    {
        $out_message= <<< HTM
<br><br><b>Mailing Complete</b>.<br><br>The E-mail was sent to $usertotal valid e-mail addresses.
HTM;
        //function to display message
        out_message($out_message,$color_feedback_good);
    }
    else
    {
        $out_message= "Error sending email to subscribers";
        //function to display message
        out_message($out_message,$color_feedback_bad);
    }
}

$out_message=<<<HTM
<br><br>
Click here to return back to
<b><a href="advertisers_area.php?login=now&adid=$adid&id=$id">Advertiser Area</a></b>.
HTM;

//function to display message
out_message($out_message,$color_feedback_good);

dbclose($link);

print "<br><br><br><br>$html_footer";
?>


