<?
include ("../includes/global.php");

links();

//DB connectivity
$link=dbconnect();

$member_links="";

if($email != "" && $ps != "")
{
     $member_links.=menu($email,$ps);
     print "$member_links<br><br>";
}
if($action == "getad")
{
    if ($agree="ON") $agr="Yes";
    else $agr="No";
    $msg=<<<MSG
<font color=green><b>From                                          :</b></font>
$email<br>
  
<font color=green><b>Type of Advertising                           :</b></font>
$adtype<br>
  
<font color=green><b>Cost of Advertising                            :</b></font>
$cost<br>
  
<font color=green><b>Information to be displayed with Advertisement :</b></font>
$info<br>
  
<font color=green><b>Agree for payment for the advertising          :</b></font>
$agr<br>
   
Thanks,
<br>
MSG;
	 
	 $query1="insert purchase_contact values ('$email','$adtype',$cost,'$info','$agr')";
         mysql_query ($query1);
$subject="Advertising Order";
         if(send_mail($admin_mail_id,$email,$subject,$msg))
         {
              $out_message= <<< HTM
              Thank you for purchasing advertising from MailBoxCash.
              The Advertising Order has been sent to the Webmaster. The webmaster will 
              contact you and let you know where all payments may be directed to. Thank You.
HTM;
              //function to display message
	      out_message($out_message,$color_feedback_good);
        }
}
elseif($action == "getad" && $payment="done")
{
     $content=file_reader("$site_path/advt_temp.data");
     $content=split("\n",$content);
     for($i=0;$i<count($content)-1;$i++)
     {
          $data=split("&&",$content[$i]);
          if($temp == $data[0])
                break;
     }
     advt_mail($data[1],$data[0]);
     $out_message= <<< HTML
    <br><br><br><center><b><font color=green>Our Web Master will contact you later through $data[1].</font></b></center>
    <META HTTP-EQUIV=REFRESH CONTENT="4; URL=$site_url/index.php"></center>
HTML;

     //function to display message
     out_message($out_message,$color_feedback_good);
}
else
{
        $file_content = file_reader("$site_html_path/getad.html");

        $file_content = str_replace("[site_url]",$site_url,$file_content);
        $file_content = str_replace("[currency_symbol]",$currency_symbol,$file_content);
        $file_content = str_replace("[refid]",$refid,$file_content);
        $file_content = str_replace("[email]",$email,$file_content);
        $file_content = str_replace("[ps]",$ps,$file_content);
        print $file_content;
	
}
//to close to DB link
dbclose($link);

print "$html_footer";
?>