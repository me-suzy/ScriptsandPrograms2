<?

    include ("../includes/global.php");



    print "<center>";

    links();

    print "</center><br><br>";



    $member_links=menu($email,$ps);

    print "$member_links <br><br>";



if($gold_membership == "ON")

{

      if(file_exists("../gold_membership/gold_funs.php")) { include ("../gold_membership/gold_funs.php"); }

      else { include ("gold_funs.php"); }



      if(!$t && $email && $ps)

      {

                //DB Connection

                $link=dbconnect();



                //to fetch the member id corresponding to the email id selected randomly

                $qry="Select mem_id,password from member_details where email_id='$email' ";

                $res=mysql_query($qry) or die(mysql_error());

                $mem_det=mysql_fetch_array($res) or die(mysql_error());



                mysql_close($link);



                if(md5($mem_det[1]) != $ps)

                {

                     $err_msg=<<< EOL

                       <b><font color=red size=4>Error in sign up. Either the User Id or Password

                        entered is Wrong. <br> <br><br><br>Go back and provide a valid Login Details</font></b>

                       <META HTTP-EQUIV=REFRESH CONTENT="3; URL=$site_url/index.php">

EOL;



                     try_again($err_msg);

                }

                $file_content = file_reader("$gold_html_path/getgold.html");



                $file_content = str_replace("[gold_url]",$gold_url,$file_content);

                $file_content = str_replace("[currency_symbol]",$currency_symbol,$file_content);

                $file_content = str_replace("[gold_amount]",$gold_membership_amount,$file_content);

                $file_content = str_replace("[email]",$email,$file_content);

                $file_content = str_replace("[ps]",$ps,$file_content);

                print $file_content;



      }

      elseif($t == "getgold" && $payment == "notdone")

      {

         if ($agree="ON") $agr="Yes";

         else $agr="No";

$msg=<<<MSG

<font color=green><b>From                                             :</b></font>

$email<br>

  

<font color=green><b>Type of Payment                                  :</b></font>

$pay_type<br>

  

<font color=green><b>Information to be displayed with Banner          :</b></font>

$info<br>

  

<font color=green><b>Agree for payment for the Gold Membership        :</b></font>

$agr<br>

   

Thanks,

<br>

MSG;

			//DB connectivity

                        $link=dbconnect();

                        $query1="insert purchase_gold values ('$email','$pay_type','$info','$agr')";

                        mysql_query ($query1);

$subject="Gold Membership Order";



                        if(send_mail($admin_mail_id,$email,$subject,$msg))



                        {



                                $out_message= <<< HTM

                       Thank you for purchasing Gold Membership from Ahhoy.

                       The Order has been sent to the Webmaster. The webmaster will contact you and let

                       you know where all payments may be directed to. Thank You.

HTM;



                                //function to display message



                                out_message($out_message,$color_feedback_good);

                       }



      }

      elseif($t == "getgold" && $payment == "done" && $temp_id)

      {

                $file_content=file_reader("$gold_path/gold_temp.data");

                $file_content=split("\n",$file_content);

                $line_count=count($file_content);



                for($i=0;$i<($line_count-1);$i++)

                {

                      $data=split("&&",$file_content[$i]);

                      if($temp_id == $data[0])

                      {

                              $line_data=$data;

                              break;

                      }

                }

                if(!$line_data)

                {

                        $err_msg= <<<HTM

<br><br><br><br><center><b>There is some error in processing.</b><br>

</center>

HTM;

                        //function to display message

                        try_again($err_msg);



                }



            $bool=check_GoldMember($data[1]);



            if($bool == "notexist")

            {



                $gold_det="$data[1]&&$data[2]&&$data[3]\n";



                $file=fopen("$gold_path/random.txt",'a');

                fwrite($file,$gold_det) or die("Could Not Able To Write The File");

                fclose($file);



                $tempid[0]=$data[0];



                /*

                this function will delete the temp data of this user from gold_temp.data file.

                This takes input of Temp id provided the var should be an array. Multiple id's can also be passed as an array.

                */

                $out=remove_GoldMember($tempid,"$gold_path/gold_temp.data");

                if($out == "false")

                {

                      print "Temp Records Not Deleted";

                }



                $file_content = file_reader("$gold_html_path/thanku.html");

                $file_content = str_replace("[sitename]",$sitename,$file_content);

                $file_content = str_replace("[email]",$data[1],$file_content);



                print <<<HTM

                $file_content

                <META HTTP-EQUIV=REFRESH CONTENT="6; URL=$site_url/member_area.php?email=$data[1]&ps=$data[4]">

HTM;

            }

            elseif($bool == "exist")

            {

                     $out_message= <<<HTM

<br><br><br><br><center><b>You are already registered as a Gold Member</b><br>

<META HTTP-EQUIV=REFRESH CONTENT="5; URL=$site_url/member_area.php?email=$data[1]&ps=$data[4]">

</center>

HTM;

            }



             //function to display message

              out_message($out_message,$color_feedback_good);



      }

      elseif(!$email && !$ps)

      {

             $err_msg= <<< EOL

                <b><font color=red size=4>Unexpected Error. Either the User Id or Password

                entered is Wrong. <br> <br><br><br></font></b>

                <META HTTP-EQUIV=REFRESH CONTENT="1; URL=$site_url/index.php">

EOL;

             try_again($err_msg);

      }



      print "$html_footer";

}



  function try_again($err_msg)

  {

       global $gold_html_path,$html_footer,$color_feedback_bad;

          $err_msg= <<<MSG

                <H1>Missing Data!</H1>

                <P><B>$err_msg</B>

MSG;



        $file_content = file_reader("$gold_html_path/outfile.html");

        $file_content = str_replace("[MESSAGE]",$err_msg,$file_content);

        $file_content = str_replace("[font_color]",$color_feedback_bad,$file_content);



        print $file_content;



        print "$html_footer";

        exit;

  }



?>

