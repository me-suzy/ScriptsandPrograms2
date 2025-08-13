<?
    include ("../includes/global.php");

    print "<center>";
    links();
    print "</center><br><br>";

    //DB connectivity
    $link=dbconnect();

    if($login=="now")
    {
      $sql="SELECT  count(*) from advt_email where ad_id=$adid and email_id='$id' " ;
      if($res = mysql_query($sql) )
      {
        $res=mysql_fetch_array($res);

         if($res[0]>0)
         {
	    $query="SELECT  * from advt_email where email_id='$id' " ;
	    $res1 = mysql_query($query);
	    print "<br><br><table border=1><tr><td><b>Campaign Name</b></td><td><b>Action</b></td></tr>";
	    while($res2=mysql_fetch_array($res1))
	    {
	        print <<<HTML
		 <tr><td>$res2[3]</td><td><a href="$site_url/advertisers_area.php?view=stats&adid=$res2[0]">View Stats</a></td></tr>
HTML;
	    }

	    print <<<HTM
	    </table><br><br>
	    Want to place a new campaign? <a href="$site_url/getad.php?email=$id&ps=$ps">CLICK HERE </a><br><br>
HTM;

	    if($mail_by_advertiser == "ON")
	    {
	       $out_message= <<<HTM
<br><br><center><b><font color=green>
Want to mail your messages to directly all the <br> registered members of this site? If yes
<a href="$site_url/advertiser_mailer.php?adid=$adid&id=$id&action=mailer">Click Here</a></font></b></center>
HTM;
               //function to display message
	       out_message($out_message,$color_feedback_good);
	    }

	    if($ps != "")
	    {
		$out_message= <<<HTM
<br><br><center><b><font color=green>
Click here to return to <a href="$site_url/member_area.php?email=$id&ps=$ps">Menbers Area</a></font></b></center>
HTM;
		//function to display message
		out_message($out_message,$color_feedback_good);
	    }
         }
         else
         {
             $out_message= <<<HTM
<br><br><center><b><font color=red>You are not a valid user.</font></b></center>
<META HTTP-EQUIV=REFRESH CONTENT="3; URL=$site_url/index.php">
HTM;

             //function to display message
             out_message($out_message,$color_feedback_bad);
	     exit;
         }
     }
     else
     {
         $out_message= <<<HTM
<br><br><center><b><font color=red>You are not a valid user.</font></b></center>
<META HTTP-EQUIV=REFRESH CONTENT="3; URL=$site_url/index.php">
HTM;

         //function to display message
         out_message($out_message,$color_feedback_bad);
         exit;
     }
  }
  elseif($view == "stats" && $adid != "")
  {
       $query="select * from email_clicks where ad_id='$adid'";
       $res1 = mysql_query($query);

       $out_message= <<< HTM
        <br><br><h4>Stats for the Campaign : $res2[3] </h4><br><br>
        <table border=1><tr><td><b>Date</b></td><td><b>Clicks Received</b></td></tr>
HTM;

        while($res2=mysql_fetch_array($res1))
        {
            $out_message.= <<<HTML
             <tr><td>$res2[date]</td><td>$res2[clicks]</td></tr>
HTML;
        }

        $out_message.= <<< HTML
        </table><br><br><br><b>Clink the link to go <a href="javascript:history.back()">BACK</a>
HTML;

        //function to display message
        out_message($out_message,$color_feedback_good);

    }

    //DB disconnection
    dbclose($link);

    print "<br><br><br><br>$html_footer";

?>

