<?
include ("../includes/global.php");

print "";
links();
print "<br><br>";

//connecting DB
$link=dbconnect();

print "<html><body>";

//This query will give the count of email id which is duplicated ie if it occours more than once
$sql = "SELECT count(*) FROM member_details where email_id='$email' ";

if($res = mysql_query ($sql))
{
    $mail = mysql_fetch_array($res);
    //This condition will check for wheather the email id given already present in the DB or not.
    if($mail[0] > 0)
    {
         $out_message= "
<br><center><b>The email address that you entered is already in use. 
<br><br>Please login with your email address and password.</font>";
         //function to display message
         out_message($out_message,$color_feedback_bad);
    }
    else
    {
         //If not present this part will execute
         $query = "SELECT count(*),max(mem_id) FROM member_details";
         if($result = mysql_query ($query))
         {
              $id = mysql_fetch_array($result);
              if($id[0] == 0) { $memid = 1; }
              else { $memid = $id[1] +1; }
         }
        $msg= "
<p>This is your sign up confirmation email!</p>
<table><tr><th>Click the link below to continue with the registration</th></tr><tr><th>
<a href=\"$site_url/member_regn.php?memid=$memid&email=$email&refid=$refid\" target=\"new\">$site_url/member_regn.php?memid=$memid&email=$email&refid=$refid</a>
</th></tr><tr><td></td></tr>
</table>";

         //function to send a mail to the subscriber as a confirmation for sign up
         if(send_mail($email,$admin_mail_id,$email_signup_subject,$msg))
         {
             $out_message= "
<h4>A confirmation email has been sent to your email address.<br> 
You must follow the instructions in that confirmation email<br>
in order to finish signing up.</h4>   
Your Email Address is :  $email<BR><BR>";
              //function to display message
              out_message($out_message,$color_feedback_good);
          }
          else
          {
               $out_message= "
<b>Error in your mail id or wrong mail id /n <br><br>
If you want to continue further provide a valid email id.</b>";
                //function to display message
                out_message($out_message,$color_feedback_bad);
          }
     }
}
dbclose($link);
print "<br><br><br><br>$html_footer";
?>

