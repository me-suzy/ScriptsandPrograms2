<?
    include ("../includes/global.php");

    print "<center>";
    links();
    print "</center><br><br>";

    //DB connectivity
    $link=dbconnect();

    if($email != "" && $ps != "")
    {
        //function to put link to navigate to all pages
        print "<center>";
        $member_links=menu($email,$ps);
        print $member_links;

        $out_message= <<<MSG
</center><br><br><b>Use this URL in ur referral site or in your mail, to receive incentives</b><br>
<a href="$site_url/index.php?refid=$email">$siteadds</a><br><br>
MSG;
        //function to display message
        out_message($out_message,$color_feedback_good);
    }
    if($action == "regad")
    {
        if($name == "" || $title == "" )
            print "<b>Enter all the fields correctly</b>";
        else
        {
            if($amtpclick == "")
            {
                $amtpclick=0;
            }
            $adid=date(dmy).time();
            $query1="insert into advt_email (ad_id,email_id,name,title,url,amt_perclick,type,info)
values($adid,'$emailid','$name','$title','$url','$amtpclick','$adtype','$info')";
             mysql_query($query1);

             if($refid != "")
             {
                  $query3="insert into member_referrals values ('*',$refid)";
                  mysql_query ($query3);

                  //function to credit referral bonus to referrer
                  bonus_credit($refid,"ref");
             }
             $msg = file_reader("$site_html_path/email/email_advt_regn_msg.html");
             $msg=str_replace("[site_url]",$site_url,$msg);
             $msg=str_replace("[site_name]",$sitename,$msg);
             $msg=str_replace("[title]",$title,$msg);
             $msg=str_replace("[ad_id]",$adid,$msg);
             $msg=str_replace("[email_id]",$emailid,$msg);

             //mail sent to advertiser
             send_mail($emailid,$advt_regn_email_subject,$msg);

             $content = file_reader("$site_html_path/advt_regn_msg.html");
             $content=str_replace("[title]",$title,$content);
             $content=str_replace("[email_id]",$emailid,$content);
             print $content;
         }
   }
   elseif($temp != "" && $email != "" && $action != "regad" )
   {
       $file = fopen("$sitepath/site/advt_temp.data", 'r');
       $content=fread($file, filesize("$sitepath/site/advt_temp.data"));
       fclose($file);
       $content=split("\n",$content);
       for($i=0;$i<count($content)-1;$i++)
       {
           $data=split("&&",$content[$i]);
           if($temp == $data[0])
                break;
       }
       $mode="Paid Email";
       if($data[2] == "nonpaid")
       {
            $mode="Non-Paid Email";
            $field= <<< amt
      Enter the amount to be given per email click (in Cents):<br><input name="amtpclick" size='20'><br><br>
amt;
        }

        $file_content = file_reader("$site_html_path/advertise_with_us.html");

        $file_content = str_replace("[site_url]",$site_url,$file_content);
        $file_content = str_replace("[currency_symbol]",$currency_symbol,$file_content);
        $file_content = str_replace("[emailid]",$data[1],$file_content);
        $file_content = str_replace("[cost]",$data[3],$file_content);
        $file_content = str_replace("[adtype]",$data[2],$file_content);
        $file_content = str_replace("[refid]",$data[5],$file_content);
        $file_content = str_replace("[mode]",$mode,$file_content);
        $file_content = str_replace("[field]",$field,$file_content);
        $file_content = str_replace("[info]",$data[4],$file_content);
        print $file_content;
    }
    else
    {
      $out_message= <<<MSG
You are unathorised to view this page.
<META HTTP-EQUIV=REFRESH CONTENT="2; URL=$site_url/index.php"></center>
MSG;
      //function to display message
      out_message($out_message,$color_feedback_bad);
    }

    //DB disconnection
    dbclose($link);

    print "$html_footer";
?>