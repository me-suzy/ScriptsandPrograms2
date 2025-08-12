<?php
if($terms == "ON" )
{
    include ("../includes/global.php");

    print "<left>";
    links();
    print "</left><br><br>";

    if($password1 == $password2)
    {
        //DB connectivity
        $link=dbconnect();

        //To get the system date used to store in DB as Prog joined date
        $doj = date("y/m/d");

        //To retrieve the IP Address of the client machine
        $ip = getenv(REMOTE_ADDR);

        //insertion of datas into "member_details" table
        $query1= <<<SQL
        insert into member_details (email_id,f_name,l_name,address,city,state,zip,country,password, joined_date,ipadds,status)
        values('$email','$first_name','$last_name','$address1,$address2','$city','$State[$state]','$zip','$Country[$country]',
        '$password1',now(),'$ip','active' )
SQL;

        $query2="select mem_id from member_details where email_id='$email' ";

        if(mysql_query ($query1))
        {
	    if($res=mysql_query ($query2))
	    {
		$res=mysql_fetch_array($res);

		for($i=0;$i<count($finterests);$i++)
		{

		     $int.=$finterests[$i]."::";
		}

		//insertion of datas into "additional_info" table
		$sql= <<<SQL
		insert additional_info set mem_id=$res[0],age=$fage,gender='$gender',
		marital='$fmarital',household='$fhousehold',childrens='$fchildren',income='$fincome',
		housestatus='$fhousestatus',learning='$flearning',occupation='$foccupation',
		vehicles='$fvehicles',creditcard='$creditcard',spentonline='$fspentonline',
		interests='$int',html_email='$html_email'
SQL;
		//insert query execution for additional demographic info
		mysql_query ($sql);

		//crediting 200 cents is $2 for this member as a sign up bonus
		$query4="insert into member_earnings (mem_id,credits) values($res[0],$signup_bonus)";
		mysql_query($query4);
		if($refid != "")
		{
		       $query5="insert into member_referrals values ($res[0],$refid)";

		       //updation of member referral table  $res contains child member id and $refid
		       //contains parent member id
		       mysql_query ($query5);

		       //function to credit referral bonus to referrer
		       bonus_credit($refid,"ref");
		}
	    }
	    $msg = file_reader("$site_html_path/email/email_signup.html");
	    $msg=str_replace("[site_url]",$site_url,$msg);
	    $msg=str_replace("[sitename]",$sitename,$msg);
	    $msg=str_replace("[username]",$email,$msg);
	    $msg=str_replace("[password]",$password1,$msg);


	    //confirmation msg given, after function to send a mail to the subscriber as an acknoledgememt for regn
	    if(send_mail($email,$admin_mail_id,$email_regn_subject,$msg))
	    {
		$out_message= <<< HTM
Thank you  <b>$first_name</b><br><br>
<center>Your information has been recorded successfully & your Login Id and Password <br> are sent
to    <i><u><font color=blue>$email</i></u><br><br><br><a
href="$site_url/index.php"><h3>HOME</h3></a></center>
HTM;
		//function to display message
		out_message($out_message,$color_feedback_good);
	    }
	    // to close the link to DB
	    dbclose($link);
        }
    }
    else
    {
        $out_message= <<< HTM
<b>The values entered as Password does'nt match with Confirm Password<br><br>Go back and
retype it correctly<br><br>
HTM;
        //function to display message
        out_message($out_message,$color_feedback_bad);
    }
}
else
{
    $out_message= <<< HTM
<b>You did'nt agree to our Terms And Conditions. If you ready to agree go back and tick the
ckeckbox</b>
HTM;

    //function to display message
     out_message($out_message,$color_feedback_bad);
}
?>


