<?
include ('../includes/global.php');
  
//connecting DB
$link=dbconnect();

function configure($query)
{
    global $usertotal,$emailadds, $allint, $finterests, $noid ,$from, $to;
    $count=$noid;
    if($res = mysql_query ($query))
    {
        while($res1 = mysql_fetch_array($res))
	{
            $query1 = "select mem_id, interests from additional_info where mem_id = $res1[1]"; 
	    if($result = mysql_query($query1))   {
	       $result = mysql_fetch_array($result);
	       $sinterests = split("::",$result[1]);
	       $flag="false";
               for($i=0;$i<count($sinterests); $i++){
                  if ($finterests) {
                     if(in_array($sinterests[$i], $finterests))  {
                       $flag="true";
                       break;
                     }
                  }
               }
               if($noid) {
               if($flag == "true")  {
               $query2 = "select email_id from member_details where mem_id = $result[0] limit $count";

               if($result1 = mysql_query($query2))  {
	       $num=mysql_num_rows($result1);
               $count-=$num;
               while ($result3=mysql_fetch_array($result1))  {
 	          $emailadds.=$result3[0].",";
                  $usertotal++;
               }
               }
               }
               else if(($flag == "false")&&($finterests=="" ))  
               {
               $query2 = "select email_id from member_details where mem_id = $result[0] limit $count";
               if($result1 = mysql_query($query2))  {
               $num=mysql_num_rows($result1);
               $count-=$num;
               while ($result3=mysql_fetch_array($result1))  {
 	          $emailadds.=$result3[0].",";
                  $usertotal++;
               }
               }
               }
               }
               if($from && $to) { 
               if($flag == "true")  {
               if(($result[0]  >= $from) &&($result[0] <= $to))  {
               $query2 = "select email_id from member_details where mem_id = $result[0]";
               if($result1 = mysql_query($query2))  {
	       while ($result3=mysql_fetch_array($result1))  {
 	          $emailadds.=$result3[0].",";
                  $usertotal++;
               }
               }
               }
               }
               else if(($flag == "false")&&($finterests=="" ))  
               {
               if(($result[0]  >= $from) &&($result[0] <= $to))  {
               $query2 = "select email_id from member_details where mem_id = $result[0]";
               if($result1 = mysql_query($query2))  {
               while ($result3=mysql_fetch_array($result1))  {
 	          $emailadds.=$result3[0].",";
                  $usertotal++;
               }
               }
               }
               }
               }
            }
	}
    }
}

if($B2=="SendMail")
{

    $usertotal=0;
    if($country) $allcount= join("','",$country);
    if($finterests) $allint= join("','",$finterests);

    for($i=0;$i<count($finterests);$i++)
    {
	$int.=$finterests[$i]."::";
    }
    if($from && $to)
    {   

    	if($allcount != "None")
            $query1="select email_id, mem_id from member_details where country in ('$allcount') and mem_id >= $from and mem_id <= $to ";
	
        else
            $query1="select email_id, mem_id from member_details where mem_id >= $from and mem_id <= $to ";
    }
    elseif($noid)
    {
        if($allcount != "None")
    	    $query1="select email_id, mem_id from member_details where country in ('$allcount')";
	else
            $query1="select email_id, mem_id from member_details";
	
    }
    $query2="select min(mem_id),max(mem_id),count(*) from member_details";
   
    $emailadds="";
    $usertotal=0;
    $empty="";
    file_writer("$admin_path/temp_file.txt",$empty);
    
    configure($query1);
    
    $file=fopen("$admin_path/temp_file.txt",'a') or die(" $admin_path FFFFFile Does'nt Exists");
    if(fwrite($file,$emailadds))
    {
        fclose($file);
    } 
   
    $mail_date=$month."/".$day."/".$year;

    //check for country & interest

    $emailadds=file_reader("$admin_path/temp_file.txt");

    if(isset($emailadds))
    {
	$emails=explode(",",$emailadds);
	$usertotal=count($emails);
	$usertotal--;
    }	

    if($content_type == "NO")
       $type="text/plain";
    else if($content_type == "YES")    
       $type="text/html";

    print "<meta Content-type: text/html>\n\n<h1>Sending E-Mail</h1><i>Please wait for the WHOLE page to load.
    DO NOT REFRESH THIS PAGE, OR ELSE THE MAILING WILL BE SENT OUT AGAIN.</i>";
    $headers  = "MIME-Version: 1.0\r\n"; 



if($content_type == "NO")
  {

       $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
       $brtype = "\n\n";
  }
    else if($content_type == "OFF")    
      {
         $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	        $brtype = "<br><br>";
      }
    
    /* additional headers */
    $headers .= "From: $GLOBALS[admin_mail_id]\r\n";
    $headers .= "Reply-To: $GLOBALS[admin_mail_id]\r\n";

$emessage = str_replace("\r\n","\n\n",$emessage);
//   $emessage=nl2br($emessage);
    $emessage=$GLOBALS[email_header].$brtype.$emessage.$brtype.$GLOBALS[email_footer];
    $url="$site_url/petrack.php?adid=";
    $cnt=substr_count($emessage, $url);

    if($cnt==0)
    {
	for($i=0;$i<$usertotal;$i++)
	{
//echo $headers;
            $emessage = stripslashes($emessage);
//echo $emessage;
	    mail($emails[$i], $subject, $emessage,$headers);
	}
    }
    elseif($cnt>0)
    {
	$str="_EMAIL_";
	for($i=0;$i<$usertotal;$i++)
        {
            $emessage = stripslashes($emessage);
	    $msg=str_replace($str,$emails[$i],$emessage);
	    mail($emails[$i], $subject, $msg,$headers);
        }
    }
    print "<br>Mailing Complete.<br><br>The E-mail was sent to $usertotal valid e-mail addresses.";
     
}
dbclose($link);

//country list
for ($i=0;$i<count($Country);$i++)
{
    $coun.="<option>$Country[$i]</option>\n";
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

print <<<EOL
<form action="massemailer.php" method="POST">
<font face="arial" size="3"><b><center>
Mass E-Mail Manager</b></font><br><br>
<font face="arial" size="2">Mail to be send to the Member ID :</font>
<input type="text" name="from">
&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;
<input type="text" name="to">
<br><br>
<b>(or)</b>
<br><br>
<font face="arial" size="2">Number of Mails to be send : </font>
<input type="text" name="noid">
<br>
<br>
EOL;
$count = 0;
while ($count < $usertotal)
{
    $min = $count;
    if (($count + 2000) >= $usertotal)
    {
        $max = $usertotal;
	if ($min == 0)
        {
	    $p_min = 1;
	}
        else
        {
	    $p_min = $min;
	}
	$mail_count_str.="<option value=\"$min-$max\">Users #$p_min to $max</option>\n";
	$count = $usertotal;
    }
    else
    {
	$max = $count + 2000;
	if ($min == 0)
        {
	    $p_min = 1;
	}
        else
        {
	    $p_min = $min;
	}
	$count = $count + 2000;
	$p_max = $count - 1;
	$mail_count_str.="<option value=\"$min-$p_max\">Users #$p_min to $p_max</option>\n";
    }
}

$monthstr;
for ($i = 1; $i <= 12; $i++)
{
    $str = $i;
    if ($i <=  9)
    {
        $str = "0$i";
    }
    $monthstr .= "<option value='$str'>$str</option>\n";
}

for ($i = 1; $i <= 31; $i++)
{
    $str = $i;
    if ($i <= 9)
    {
        $str = "0$i";
    }
    $daystr .= "<option value='$str'>$str</option>\n";
}

for ($i = 2000; $i <= 2010 ; $i++)
{
    $str = $i;
    $yearstr .= "<option value='$str'>$str</option>\n";
}

print <<<HTML
$mail_count_str
</select>
<font face="arial" size="2">Select the Country :</font><br>
<select name="country[]" MULTIPLE>
$coun
</select>
<br><br><font face="arial" size="2"><b>Select the Interest :</b></font><br>
<TABLE BORDER=0 WIDTH=98% ALIGN=CENTER>
<tr>
<td width=33%>
<font color="blue">
$interests1
</td>
<td width=33%>
<font color="blue">
$interests2
</td>
<td width=34%>
<font color="blue">
$interests3
</td>
</tr>
</table>
<br>
</select>
</center>
<input type="hidden" name="date" value="$mail_date">
<center>
<!--<br><br>

<select name="month">
<option value="" selected>MM</option>
$monthstr
</select>/
<select name="day">
<option value="" selected>DD</option>
$daystr
</select>/
<select name="year">
<option value="" selected>YYYY</option>
$yearstr
</select>
-->
HTML;
?>
<br><center><font face="arial" size="2">Today's Date : <?=date("m/d/Y")?></font></center>
<br>
<!--<input type="submit" value="SendMail" name="B2"><br>
NOTICE: You must have the e-mail written for today, make sure you don't
submit this form more than once, or else it may send the mailing twice.
<br><br>
-->
<font face="arial" size="2">Subject:</font><br>
<input type="text" name="subject" ><br><br>

<font face="arial" size="2">
Type:&nbsp;&nbsp;
<input type="radio" name="content_type" value="ON">Text&nbsp;&nbsp;

<input type="radio" name="content_type" value="OFF">HTML<br><br>
</font>

<font face="arial" size="2">Message</font><br>
<textarea name="emessage" cols="60" rows="10"></textarea><br>
</td></tr></table></center>
<br><center><input type="submit" value="SendMail" name="B2"></center>
</form>
</body>

<?
print  <<<HTM
<br><br><pre>Click the link. <a href="$admin_url/admin.php">Go to Admin Index Page</a></pre>
HTM;
?>
