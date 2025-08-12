<?
   include ("../includes/global.php");

   links();

   if($email && $ps)
   {
        $out_message= <<<MSG
<b>You are already signed up.</b>
<META HTTP-EQUIV=REFRESH CONTENT="1; URL=$site_url/member_area.php?email=$email&ps=$ps">
MSG;
        //function to display message
        out_message($out_message,$color_feedback_bad);
   }

   $contents=file_reader("$site_html_path/email_req.html");
   $contents=str_replace("[site_url]",$site_url,$contents);
   $contents=str_replace("[refid]",$refid,$contents);
   print $contents;

   print "$html_footer";
?>








