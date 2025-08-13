<?php

      include("/home/php-whiz/public_html/webguy/paidmail/includes/vars.php");

      include("/home/php-whiz/public_html/webguy/paidmail/includes/vars_arrays.php");



      if(file_exists("$include_path/vars_misc.php")) { include ("$include_path/vars_misc.php");}

      if(file_exists("$include_path/vars_commission.php")) { include ("$include_path/vars_commission.php");}



     //function to establish a connection to MySQL and selecting a database to work

      function dbconnect()

      {

         global $siteadds,$dbhost,$dbname,$dbuser,$dbpwd;

	 if($link = mysql_connect($dbhost,$dbuser,$dbpwd))

         {

            $res=mysql_select_db($dbname) or die(mysql_error());

	    if($res)

               return $link;

         }

         else

            print "There is some internal error in retrieving the records. Sorry for the inconvinence.";

      }



      //function  to close the opened link

      function dbclose($link)

      {

         global $link;

         if(mysql_close($link))

         {}

      }



      //function to reader contents of a file

      function file_reader($fileurl)

      {

         $file=fopen($fileurl,'r') or die("File Does'nt Exists");

         if (filesize($fileurl) > 0 )

	 {

            $contents=fread($file,filesize($fileurl));

            fclose($file);

            return $contents;

         }

      }



      //function to write contents in a file

      function file_writer($fileurl,$contents)

      {

        $file=@fopen($fileurl,'w') or die("$fileurl File Does'nt Exists");

        if($contents) 

	{

           if(@fwrite($file,$contents)) 

           {

              fclose($file);

              return true;

           }

	}

      }



      //function to get the member id

      function get_mem_id($email_id)

      {

         $SQL="SELECT mem_id from member_details where email_id='$email_id' ";

         if($res=mysql_query($SQL))

	 {

            $mem_data=mysql_fetch_array($res);

            return $mem_data[0];

	 }

      }



      //function to print a text message

      function out_message($message,$font_color)

      {

         global $sitename,$site_html_path;



         $file_content=file_reader("$site_html_path/outfile.html");

         $file_content = str_replace("[sitename]",$sitename,$file_content);

         $file_content = str_replace("[MESSAGE]",$message,$file_content);

         $file_content = str_replace("[font_color]",$font_color,$file_content);

         print $file_content;

      }



      //function to display the links, banners & header information

      function links()

      {

         global $html_header,$site_html_path,$sitename,$site_url,$refid,$email,$ps;



         //DB connectivity

         $link=dbconnect();



         $query1="select * from banner";

         if($result=mysql_query($query1))

	    $num=mysql_num_rows($result);



         $chk="false";

         $query2="select b.clicks, sum(bi.count) from banner b, banner_imp bi where b.banner_id=bi.banner_id group by b.banner_id ";

         if($res=mysql_query($query2))

         {

	    while($result=mysql_fetch_array($res))

	    {

	       if($result[0]==$result[1])

	       {

	          $chk="true";

		  continue;

	       }

	       else

	       {

	          $chk="false";

		  break;

	       }

	    }

         }

         if (($num > 0 ) && ($chk=="false"))

         {

	    $flag=0;

	    while(!$flag)

	    {

	       $banused=0;

	       $qry="select * from banner order by rand()";

	       if($res=mysql_query($qry))

	       {

		  $res1=mysql_fetch_array($res);

       		  $qry1="select * from banner_imp where banner_id=$res1[banner_id]";

		  if($rs=mysql_query($qry1))

	          {

		     while($rs1=mysql_fetch_array($rs))

			$banused+=$rs1[count];

		     if ($banused<$res1[clicks])	

        	     {

		        $flag=1;

			$output=<<<HTM

			<center>

			$res1[html]

			</center>

HTM;

			$query1="select * from banner_imp where banner_id=$res1[banner_id] and click_date=now()";

			if($result=mysql_query($query1))

			{

		  	   if($result=mysql_fetch_array($result))

			   {

			      $cnt=$result[count]+1;

			      $query="update banner_imp set count=$cnt where banner_id=$res1[banner_id] and click_date=now()";

		              mysql_query($query);

			   }

			   else

			   {

             		      $query="insert into banner_imp set banner_id=$res1[banner_id] , count=1, click_date=now()";

		              mysql_query($query);

			   }

			}

		     }

		  }

	       }

	    }

         }

         //to open the header page

         $html_header=str_replace("[pageheader]",$sitename,$html_header);

         $html_header=str_replace("[site_url]",$site_url,$html_header);

         $html_header=str_replace("[refid]",$refid,$html_header);

         $html_header=str_replace("[email]",$email,$html_header);

         $html_header=str_replace("[ps]",$ps,$html_header);

         $html_header=str_replace("[ban]",$output,$html_header);

         print $html_header;

      }



      //function used for sending mails to subscribers.it accept 3 parameter

      function send_mail($mailid,$fromid,$sub,$msg)

      {

         //$headers  = "MIME-Version: 1.0\r\n";

         $headers = "Content-type: text/html; charset=iso-8859-1\r\n";

         

         /* additional headers */

         $headers .= "From: $fromid\r\n";

         $headers .= "Reply-To: $fromid\r\n";



         $msg="$GLOBALS[email_header]<br><br>$msg<br><br>$GLOBALS[email_footer]";



         //this will send mail to each person individually.

         $mailid=split(",",$mailid);

         for($i=0;$i<count($mailid);$i++)

         {

	    //echo"Message : $msg";

            mail($mailid[$i], $sub, $msg,$headers);

         }

         return 1;

      }



      //function used for sending advertiser emails

      function advt_mail($email,$temp)

      {

         global $site_url,$advt_signup,$advt_signup_email_subject;



         $headers  ="MIME-Version: 1.0\r\n";

         $headers.="Content-type: text/html; charset=iso-8859-1\r\n";



         /* additional headers */

         $headers.="From: $GLOBALS[admin_mail_id]\r\n";

         $headers.="Reply-To: $GLOBALS[admin_mail_id]\r\n";

         $headers.="CC: $GLOBALS[admin_mail_id]\r\n";



         $advt_signup=file_reader("$site_html_path/advertiser_signup.html");

         $advt_signup=str_replace("[site_url]",$site_url,$advt_signup);

         $advt_signup=str_replace("[email]",$email,$advt_signup);

         $advt_signup=str_replace("[temp]",$temp,$advt_signup);



         $message.="$GLOBALS[email_header]<br><br>$advt_signup<br><br>$GLOBALS[email_footer]";



         if(mail($email,$advt_signup_email_subject,$message,$headers))

         {

            return true;

         }

      }



      //function to display links in member area 

      function menu($email,$ps)

      {

         global $site_html_path,$site_url;



         //to open the member_area.html page

         $members_links=file_reader("$site_html_path/members_links.html");

         $members_links=str_replace("[site_url]",$site_url,$members_links);

         $members_links=str_replace("[email]",$email,$members_links);

         $members_links=str_replace("[ps]",$ps,$members_links);

	

         return $members_links;

      }



      //function to display the error message

      function message($field,$msg)

      {

         print "<center><b>There is some error in processing. You did'nt enter\

         <font color=red>".$field."</font><br>".$msg."</b></center>";

      }



      //function to credit the earnings for referrals 

      function refbonus_credit($refid)

      {

         global $referral_bonus;

         //retrieving the previous value of referral bonus for the referrer in his earnings table

         $query4="SELECT referral_bonus FROM member_earnings where mem_id=$refid";

         if($result = mysql_query ($query4))

         {

	    if($cr = mysql_fetch_array($result))

            {

	       if($cr[0] == 0)

	       $cr = $referral_bonus;

	       else

	       $cr = $cr[0]+$referral_bonus;

	    }

            //updating a sum of 200 Cents ie $2 as referral bonus to the referrer

            $query5="update member_earnings set referral_bonus=$cr where mem_id=$refid";

            mysql_query($query5);

         }   

         return true;

      } 



      //function used to find number of members in each tiers.

      function sql($id,$i)

      {

         global $tier,$include_path;



         if(file_exists("$include_path/vars_commission.php")) { include ("$include_path/vars_commission.php");}



         $query="select count(*),mem_id from member_referrals where parent_id in ($id) group by mem_id";

         if($res = mysql_query ($query))

         {

            $tier[$i]=mysql_num_rows($res);

            $res = mysql_fetch_array($res);

            $qry="select mem_id from member_referrals where parent_id in ($id)";

            if($result=mysql_query($qry)) {

               $i=1;

               while ($rs=mysql_fetch_array($result)){

                  $par_id[$i]=$rs[0];

                  $i++;

               }

            }

            return $par_id;

         }

      }



      /*

        this function will move the referral tier one step ahead when a member

        is deleted or he unsubscribes himself from this site.

      */

      function move_Tier($mem_id)

      {

              

         //this will fetch the parent id for the member id passed as param.

         $par_id=member_referral($mem_id);

              

         //this query is to fetch the id's of members who were referred by this $mem_id

         $query="SELECT mem_id from member_referrals where parent_id=$mem_id ";

         $ch_id=Array();

         $i=0;

	 if ($res=mysql_query($query))   

	 {

            while($id=mysql_fetch_array($res))

            {

               $ch_id[$i] = $id[0];

               $i++;

            }

            $query="UPDATE member_referrals set parent_id=$par_id where mem_id=";

            for($i=0;$i<count($ch_id);$i++)

            {

               mysql_query($query.$ch_id[$i]);

            }

         }

         return 1;

      }



      //function to retrieve the parent id of an member

      function member_referral($id)

      {

         $query="select parent_id from member_referrals where mem_id=$id ";

         if($res = mysql_query ($query) )

         {

            if($res1 = mysql_fetch_array($res))

            {

               return $res1[0];

            }

         }

      }



      //function to credit the member earnings

      function credit_member($par_id,$comm_for)

      {

         global $include_path;

    

         if(file_exists("$include_path/vars_commission.php")) { include ("$include_path/vars_commission.php");}



         for($i=0;$i<count($par_id);$i++)

         {

            $query1="SELECT count(*) from member_earnings where mem_id='$par_id[$i]' ";

            $res=mysql_query($query1) or die(mysql_error());

            $rows=mysql_num_rows($res);

            if($rows == 0) { $mode="INSERT";}



            if($comm_for == "click" || $comm_for == "all" )

            {

               $query1="SELECT pd_clickthro from member_earnings where mem_id='$par_id[$i]' ";

               $res=mysql_query($query1);

               $data=mysql_fetch_array($res);



               if($mode == "INSERT")

               {

                  mysql_query("INSERT member_earnings set mem_id='$par_id[$i]',pd_clickthro=$PerClickRate[$i]") or die(mysql_error());

               }

               else

               {

                  $click_comm=($data[0]+$PerClickRate[$i]);

                  mysql_query("UPDATE member_earnings set pd_clickthro=$click_comm where mem_id='$par_id[$i]' ") or die(mysql_error());

               }

            }



           if($comm_for == "ref" || $comm_for == "all" )

           {

              $query1="SELECT referral_bonus from member_earnings where mem_id='$par_id[$i]' ";

              $res=mysql_query($query1);

              $data=mysql_fetch_array($res);



              if($mode == "INSERT")

              {

                 mysql_query("INSERT member_earnings set mem_id='$par_id[$i]',referral_bonus=$PerReferralRate[$i]") or die(mysql_error());

              }

              else

              {

                 $ref_comm=($data[0]+$PerReferralRate[$i]);

                 mysql_query("UPDATE member_earnings set referral_bonus=$ref_comm where mem_id='$par_id[$i]' ") or die(mysql_error());

              }

           }

        }

     }



     //function used to credit the referrals

     function bonus_credit($referrer_id,$comm_for)

     {

        global $Total_Tiers;



        $j=0;

        $par_id[$j]=$referrer_id;

        for($i=0;$i<($Total_Tiers-1);$i++)

        {

           $referrer_id=member_referral($referrer_id);

           if(!$referrer_id || $referrer_id == "0")

              break;



           ++$j;

           $par_id[$j]=$referrer_id;

        }

        //this function will credit all the tier members

        credit_member($par_id,$comm_for);

     }



//##########DO Not Edit Anything Below This##############

       //$admin_header=file_reader("$admin_html_path/header.html");

       //$admin_footer=file_reader("$admin_html_path/footer.html");



       $html_header = file_reader("$site_html_path/header.html");

       $html_footer = file_reader("$site_html_path/footer.html");



       $email_header = file_reader("$site_html_path/email/email_header.html");

       $email_footer = file_reader("$site_html_path/email/email_footer.html");



       $mem_signup = file_reader("$site_html_path/email/email_signup.html");

       $advt_signup = file_reader("$site_html_path/email/advertiser_signup.html");



       $out_messages = file_reader("$site_html_path/outfile.html");

?>