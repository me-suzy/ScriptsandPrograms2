<?
include ("../includes/global.php");

if($m=="login")
{
   switch ($todo)
   {
        case "login":
                //Login to My Account
                     $p=md5($p);
                     header("Location:member_area.php?email=$u&ps=$p&refid=$refid ");
                     break;
        case "cancel":
                //Unsubscribe
                     $p=md5($p);
                     header("Location:unsubscribe.php?email=$u&pwd=$p ");
                     break;
        case "sendpass":
		    print "<center>";
		    links();
		    print "</center><br><br>";

               //Send Password
                    //connection DB
                    $link=dbconnect();
                    $query="select count(*),password from member_details where email_id='$u' group by email_id";
                    if($res=mysql_query($query))
                    {
                        $res=mysql_fetch_array($res);
                        if($res[0] > 0)
                        {
                            $sub="Login details for $siteadds";
                            $msg= <<<MSG
Login details for $siteadds<br><br>
<b>User id :</b>$u <br><b>Password:</b>$res[1]<br><br>
<a href="$site_url/index.php?email=$u"></a>
MSG;
                            if(send_mail($u,$admin_mail_id,$sub,$msg))
                            {
		                $out_message= <<< HTM
<b>Your User ID and Password have been sent to your email address.</b>
HTM;
     			       //function to display message
		               out_message($out_message,$color_feedback_good);

                            }
                        }
                        else
                        {

		                $out_message= <<< HTM
<center>Error !!! \n\nYou are not a valid user!!!!!!\n\nYour email address is not in our Database</center>
HTM;
     			       //function to display message
		               out_message($out_message,$color_feedback_bad);

                        }
                    }
                    else
                    {

	                $out_message= <<< HTM
<center>There is some technical problem in site to validate your Login. 
Please try after some time</center>
HTM;

		       //function to display message
	               out_message($out_message,$color_feedback_bad);

                    }

                     //disconnecting from DB
                     dbclose($link);
		     print "<br><br><br><br>$html_footer";
                     break;
       }
}
elseif($goto=="ad_stats")
{
   if($adid == "" || $id == "")
   {

	$out_message= <<< HTM
<br><br><center><b>You left a column empty go back and enter it correctly.</b></center>
<META HTTP-EQUIV=REFRESH CONTENT="3; URL=index.php"></center>
HTM;
	//function to display message
	out_message($out_message,$color_feedback_bad);
	exit;
   }
   else
   {
       header("Location:advertisers_area.php?login=now&adid=$adid&id=$id");
   }
}
?>




