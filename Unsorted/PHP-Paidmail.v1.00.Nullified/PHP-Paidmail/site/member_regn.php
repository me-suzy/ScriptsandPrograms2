<?
   include ("../includes/global.php");

   print "<center>";
   links();
   print "</center><br><br>";


   if(!$memid || !$email)
   {
       $out_message= <<< HTM
<b>You are not eligible to enter this page without confirmation. <br>You should
enter this page only by clicking the link present <br>in your email while you sign up.</b>
<META HTTP-EQUIV=REFRESH CONTENT="3; URL=$site_url/index.php">
HTM;

       //function to display message
       out_message($out_message,$color_feedback_bad);
       print "<br><br><br><br>$html_footer";
       exit;
   }

   $member_regn = file_reader("$site_html_path/registeration.html");
   $member_regn=str_replace("[site_url]",$site_url,$member_regn);
   $member_regn=str_replace("[mem_id]",$memid,$member_regn);
   $member_regn=str_replace("[email_id]",$email,$member_regn);
   $member_regn=str_replace("[ref_id]",$refid,$member_regn);

   //state list
   for ($i=0;$i<count($State);$i++)
   {
       $stat.="<option value=\"$i\">$State[$i]</option>\n";
   }
   //country list
   for ($i=0;$i<count($Country);$i++)
   {
       $coun.="<option value=\"$i\">$Country[$i]</option>\n";
   }
   //age
   foreach($age as $key => $value)
   {
       $ag.="<option value=$key>$age[$key]</option>\n";
   }
   //marital status
   foreach($marital as $key => $value)
   {
       $mari.="<option value=$key>$marital[$key]</option>\n";
   }
   //household members
   foreach($household as $key => $value)
   {
       $hhold.="<option value=$key>$household[$key]</option>\n";
   }
   //childrens
   foreach($children as $key => $value)
   {
       $ch.="<option value=$key>$children[$key]</option>\n";
   }
   //annual income
   foreach($income as $key => $value)
   {
       $inc.="<option value=$key>$income[$key]</option>\n";
   }
   //rented or owned
   foreach($housestatus as $key => $value)
   {
       $hs.="<option value=$key>$housestatus[$key]</option>\n";
   }
   //education level
   foreach($learning as $key => $value)
   {
       $lear.="<option value=$key>$learning[$key]</option>\n";
   }
   //occupation
   foreach($occupation as $key => $value)
   {
       $occ.="<option value=$key>$occupation[$key]</option>\n";
   }
   //vehicle details
   foreach($vehicles as $key => $value)
   {
       $veh.="<option value=$key>$vehicles[$key]</option>\n";
   }
   //spending online
   foreach($spentonline as $key => $value)
   {
       $sol.="<option value=$key>$spentonline[$key]</option>\n";
   }
   //areas of interests
   $i = 0;
   foreach($interests as $key => $value)
   {
       if($i<15)
       { 
	   $interests1.=<<<HTM
<input type="checkbox" name="finterests[]" value="$key"><font face="arial" size="2">$interests[$key]</font><br>\n
HTM;
       }
       elseif($i>=15 && $i<30)
       {
	   $interests2.=<<<HTM
<input type="checkbox" name="finterests[]" value="$key"><font face="arial" size="2">$interests[$key]</font><br>\n
HTM;
       }
       elseif($i>=30) 
       {
	   $interests3.=<<<HTM
<input type="checkbox" name="finterests[]" value="$key"><font face="arial" size="2">$interests[$key]</font><br>\n
HTM;
       }
       $i++;
    }

    $find_str=Array("[state]","[country]","[interests1]","[interests2]","[interests3]","[age]","[marital]","[household]","[children]","[income]","[housestatus]","[learning]","[occupation]","[vehicles]","[spentonline]","[max_login_days]");

    $repl_str=Array($stat,$coun,$interests1,$interests2,$interests3,$ag,$mari,$hhold,$ch,$inc,$hs,$lear,$occ,$veh,$sol,$max_days_for_login);

   for($i=0;$i<count($find_str);$i++)
   {
      $member_regn=str_replace($find_str[$i],$repl_str[$i],$member_regn);
   }
   print "$member_regn<br>$html_footer";
?>

