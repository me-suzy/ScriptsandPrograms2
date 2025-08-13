<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Member Area</title>
</head>
<body>

<?php
    include ("../includes/global.php");

    links();
   
    //DB connectivity
    $link=dbconnect();

    $query1 = "SELECT count(*),TO_DAYS(NOW())-TO_DAYS(last_login),last_login,password,status FROM member_details where email_id='$email'  group by email_id";
    if($res = mysql_query ($query1))
    {
        $res = mysql_fetch_array($res);
        //This condition will check for whethere the login details is valid or not ie if count value is 1 then the login details is valid, else invalid and
         //checking for condition if previous login is greater than maximum days or less.If less that $max_days_for_login  days,login will  proceed, else locked
         //encryption done for user password
         $pwd = md5($res[3]);
         if(($res[0] > 0) && ($pwd==$ps) && ($res[1] < $max_days_for_login) && ($res[4] == "active") )
         {
             $member_links=menu($email,$pwd);
             $query2 = "update member_details set email_id='$email',last_login=now() where email_id='$email' ";
             mysql_query ($query2);
             //function to display message
             $members_area=file_reader("$site_html_path/member_area.html");
             $members_area=str_replace("[sitename]",$sitename,$members_area);
             $members_area=str_replace("[LINKS]",$member_links,$members_area);
             /*This part will be executed when the Gold Membership option is 
               Enabled by the admin*/ 
             if($gold_membership == "ON")
             {
                 if(file_exists("../gold_membership/gold_funs.php")) { include ("../gold_membership/gold_funs.php"); }
                 $bool=check_GoldMember($email);
                 if($bool == "notexist")
                 {
                     $out_message= <<<HTM
Want to become a Gold Member? <br>If yes Click the Link
<b><a href="$gold_url/getgold.php?email=$email&ps=$ps">Gold Membership</a></b>
HTM;
                 }
                 elseif($bool == "exist")
                 {
                    $out_message= <<<HTM
<b>Gold Member</b><br>
<img src="/PaidMail/images/star.gif" ALT="Gold Member">
HTM;
                 }
                 $members_area=str_replace("[Gold_Member]",$out_message,$members_area);
             }
             elseif($gold_membership == "OFF")
             {
                 $members_area=str_replace("[Gold_Member]","",$members_area);
             }
             print $members_area;
	}
        else
        {
            //checking for condition if previous login is greater than maximum days or less.
            //If less than max days, login will  proceed, else locked
            if($res[4] == "inactive")
            {
                $out_message= <<< HTM
<br><br><b>You did'nt login to this site for more than $max_days_for_login
Days. So your account was freezed.<br><br> Contact site administrator to Unlock your account
if needed.</b>
HTM;
                //function to display message
                out_message($out_message,$color_feedback_bad);
             }
             elseif($pwd!=$ps)
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
     }
     else
     {
          $out_message= <<< HTM
<b>There is some Technical Problem, which stops proceeding further.
<br><br>Try again later.</b>
HTM;
          //function to display message
          out_message($out_message,$color_feedback_bad);
     }
    //to close to DB link
    dbclose($link);

    print "<br><br><br><br>$html_footer";
?>

