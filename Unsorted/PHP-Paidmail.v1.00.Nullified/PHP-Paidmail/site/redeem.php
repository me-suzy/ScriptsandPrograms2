<?php

include ("../includes/global.php");

print "<center>";
links();
print "</center><br><br>";

if($email != "" && $ps != "")
{
    $member_links=menu($email,$ps);
    print "$member_links";
}

//DB connectivity
$link=dbconnect();

if ($action=="redeem")
{
   $msg=<<<MSG
<font color=green><b>$mailid</b></font> is redeeming a $currency_symbol $tamt $type
<br>
Thanks,
<br>
MSG;
$qry="select * from redeem_contact where email='$mailid'";
$res=mysql_query($qry);
$num=mysql_num_rows($res);
$res=mysql_fetch_array($res);
if ($num==0)
{
    $query1="insert redeem_contact values('$mailid',$tamt)";
    mysql_query ($query1);
}
else
{
    $amt=$res[amt]+$tamt;
    $flag=0;
    $query1="update redeem_contact set amt=$amt where email='$mailid'";
    mysql_query($query1);
    $flag=1;
}
$subject="Member Payment Request";
if((send_mail($admin_mail_id,$mailid,$subject,$msg)) && (($flag==1) || ($num==0)))
{
    $query1="select password from member_details  where email_id='$mailid'";
    $res=mysql_query($query1);
    $pass=mysql_fetch_array($res);
    $pass=md5($pass[0]); 

    $out_message= <<< HTM
<center><h2>Redemption Complete</h2></center><br>Here are the steps to the redemption process:<br>
1. The Webmaster will review your account for any mistakes or errors.<br> 
2. After that he will debit your account for the amount redeemed. <br>
3. Then he will contact you and send you your redemption.<br>
This whole process may take a couple days. <br>
So please be patient.
HTM;
    //function to display message
    out_message($out_message,$color_feedback_good);
}
//DB dicconnection
dbclose($link);
}
print "<br><br><br><br>$html_footer";
?>








