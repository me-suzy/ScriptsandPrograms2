<?
include ("../includes/global.php");

print "<center>";
links();
print "</center><br><br>";

//DB connectivity
$link=dbconnect();

if($email != "" && $ps != "")
{
      print "<center>";
      menu($email,$ps);
      print "</center>";
}
if($p=="save")
{
    if ($name == "")
    {
         message("Your Name", "You left it empty");
         $res="false";
    }
    if ($email == "")
    {
         message("E-Mail Address","You left it empty");
         $res="false";
    }
    if ($type == "")
    {
  	 message("Question Type","You didn't choose one.");
         $res="false";
    }
    if ($bquestion == "")
    {
	 message("Brief Question","You left it empty.");
         $res="false";
    }
    if ($question == "")
    {
	 message("Complete Question","You left it empty.");
         $res="false";
    }
    else
    {
         if($res="true")
         {
 	     $msg=<<<MSG
<font color=green><b>The email Id of theUser Contacted    :</b></font>
$email<br>
  
<font color=green><b>Name of that User                    :</b></font>
$name<br>
  
<font color=green><b>The Question Type                    :</b></font>
$type<br>
  
<font color=green><b>Brief of the Question                :</b></font>
$bquestion<br>
  
<font color=green><b>The actual Query                     :</b></font>
$question<br>
   
Thanks,
<br>
$name<br>
MSG;
             $query1="insert contact_us values('$email','$name','$type','$bquestion','$question')";
             mysql_query ($query1);
             if(send_mail($admin_mail_id,$email,$email_contactus_subject,$msg))
             {
                  $out_message= <<< HTM
 Thank you &nbsp;<b>$name<br><br>&nbsp;
<br>&nbsp;&nbsp;We will reply to you as soon as possible at : $email
HTM;
                  //function to display message
                   out_message($out_message,$color_feedback_good);
             }
         }
    }
}
else
{
   $contents=file_reader("$site_html_path/contact_us.html");
   $contents=str_replace("[site_url]",$site_url,$contents);
   $contents=str_replace("[refid]",$refid,$contents);
   $contents=str_replace("[ps]",$ps,$contents);
   $contents=str_replace("[email]",$email,$contents);
   print $contents;
}
//DB dicconnection
dbclose($link);
print "<br><br><br><br>$html_footer";

?>

