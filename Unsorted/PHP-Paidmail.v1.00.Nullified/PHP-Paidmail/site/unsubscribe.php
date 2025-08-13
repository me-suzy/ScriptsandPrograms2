<html>
<?
include ("../includes/global.php");

print "<center>";
links();
print "</center><br><br>";

//DB connection
$link=dbconnect();

$query1="select mem_id,password from member_details where email_id='$email' ";
$res=mysql_query($query1);
if($res=mysql_fetch_array($res))
{
     if(md5($res[1]) == $pwd)
     {

         $member_links=menu($email,$pwd);
         print "$member_links <br><br>";

         if(!$action && !$B1)
         {
	     $out_message= <<< HTM
<H3>Unsubscribe from $sitename.com</H3>
If you really want to Unsubscribe your membership from our site,<br> then Click on the button below
<CENTER><FORM METHOD=POST ACTION="">
<INPUT TYPE="hidden" NAME="action" VALUE="Unsubscribe">
<INPUT TYPE="hidden" NAME="mem_id" VALUE="$res[0]">
<INPUT TYPE="hidden" NAME="pwd" VALUE="$pwd">
<FONT FACE="COURIER" color="black">
Email Address: <INPUT TYPE="text" NAME="email" VALUE="$email" SIZE="40"  READONLY>
<P></FONT>
<INPUT TYPE="submit" NAME="B1" VALUE="Unsubscribe">
</FORM></CENTER><P>
HTM;

	     //function to display message
	     out_message($out_message,$color_feedback_good);
         }
         elseif($action == "Unsubscribe" && $B1 == "Unsubscribe")
         {
               $query2="delete from member_details where email_id='$email' and password='$res[1]' ";

               if(mysql_query($query2))
               {
		     //to move the tiers on level above
		     move_Tier($mem_id);

		     if(file_exists("../gold_membership/gold_funs.php")) { include ("../gold_membership/gold_funs.php"); }

		     $userid[0]=$email;
		     //to delete this user id from the gold membership
		     $out=remove_GoldMember($userid,"$gold_path/random.txt");

		     $out_message= <<< HTM
<br><br><br><br><center>You are successfully unsubscribed from $siteadds.
<br><br>You will be automatically redirected to our home page.
<META HTTP-EQUIV=REFRESH CONTENT="4; URL=$site_url/index.php">
HTM;

		     //function to display message
		     out_message($out_message,$color_feedback_good);
               }
               else
               {
		      $out_message= <<< HTM
<br><br><br><br><center>You are not unsubscribed from $siteadds.
<br><br>Please Try again.<br>
HTM;
		      //function to display message
		      out_message($out_message,$color_feedback_bad);

               }

         }

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

//to close DB connection
dbclose($link);

print "<br><br><br><br>$html_footer";
?>
</html>