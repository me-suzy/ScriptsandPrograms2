<?php
include ("../includes/global.php");

links();

if($email != "" && $ps != "")
{
   $member_links=menu($email,$ps);
   print "$member_links";
}

//DB connectivity
$link=dbconnect();

$query1 = "SELECT mem_id,password FROM member_details where email_id='$email'";
if($res = mysql_query ($query1))
{
    $res = mysql_fetch_array($res);
    if(md5($res[1]) == $ps)
    {
	$query2 = "SELECT * FROM member_earnings where mem_id=$res[0]";
        $query3 = "SELECT count(*) FROM member_referrals where parent_id=$res[0]";
        $query4="select title,imp_purchased,imp_used from advt_banner where email_id='$email' ";
        $query5="select ad_id,title,clicks_recd from advt_email where email_id='$email' ";
        $pid=$res[0];
        if($res = mysql_query ($query2))
        {
	   $res = mysql_fetch_array($res);
//this loop is to find the tiers of members
           for($i=0;$i<$Total_Tiers;$i++)
           {
	       $pid=sql($pid,$i);
	       if (count($pid) >0){
               $pid=join(",",$pid);
	       $qry="select email_id from member_details where mem_id in ($pid)";
	       $rss=mysql_query($qry);
	       while($rs1=mysql_fetch_array($rss)) {
		   $par_id[$i].=$rs1[0]." , ";
	       }
	       $len=strlen($par_id[$i]);
	       $len-=2;
	       $par_id[$i]=Substr($par_id[$i],0,$len);
	   }
        }
	$qry="SELECT * FROM member_credit where mem_id=$res[0]";
	$qry1="SELECT * FROM member_debit where mem_id=$res[0]";
		
	if($data=mysql_query($qry))
	{
	    $c_total=0;

	    $str=<<<HTM
            <tr>
	    <td align="right"><font size="2" face="Arial">
	    Reason for credit&nbsp;&nbsp;Amount credited&nbsp;&nbsp;Date of credit
	    </font></td>
	    </tr>
HTM;
	    while($data1=mysql_fetch_array($data))
	    {
		$credit=$data1[credits]/100;
		$str.=<<<HTM
                
		<tr>
		<td align="right"><font size="2" face="Arial">
		$data1[r_credit]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$currency_symbol$credit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$data1[c_date]
		</font></td>
		</tr>
HTM;
		$c_total=$c_total+$data1[credits];
	    }
	}

	if($data=mysql_query($qry1))
	{
	    $d_total=0;

	    $str1=<<<HTM
	    <tr>
	    <td align="right"><font  size="2" face="Arial">
	    Reason for debit&nbsp;&nbsp;Amount debited&nbsp;&nbsp;Date of debit
	    </b>
	    </font></td>
	    </tr>
HTM;
	    while($data1=mysql_fetch_array($data))
	    {
		$debit=$data1[debits]/100;
		$str1.=<<<HTM
		<tr>
		<td align="right"><font size="2" face="Arial">
		$data1[r_debit]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$currency_symbol$debit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$data1[d_date]
		</font></td>
		</tr>
HTM;
		$d_total=$d_total+$data1[debits];
	    }
	}
        //Code to show the updated referral bonus for both click & referrals.           
	global $include_path;

	if(file_exists("$include_path/vars_commission.php")) { include ("$include_path/vars_commission.php");}

	$pid=$res[0];
	$clk_earnings=0;
	for($i=0;$i<$Total_Tiers;$i++)
	{
	    $pid=sql($pid,$i);
	    if (count($pid) >0){
		$pid=join(",",$pid);
		$qry="select * from member_earnings where mem_id in ($pid)";
		$rss=mysql_query($qry);

		while($rs1=mysql_fetch_array($rss)) {
			$clk_earnings+=$rs1[pd_clickthro]*($PerClickRate[$i]/100);
		}
	    }
	}
	$ref_earnings=0;
	for($i=0;$i<$Total_Tiers;$i++)
	{
	    $ref_earnings+=$tier[$i]*$PerReferralRate[$i];
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title><?=$sitename?>.com - Your Current Earnings</title>
</head>
<body>
<br>
<br>
<table cellSpacing="3" cellPadding="3" width="100%" border="0">
  <tbody>
    <tr>
      <td align="right"><font face="Arial" color="blue" size="+1">Paid Email
        Clickthrus :&nbsp <?=$currency_symbol.(($res[1]+$res[pd_clickban])/100)?></font></td>
    </tr>
    <tr>
      <td align="right"><font face="Arial" color="blue" size="+1">Credits :
        &nbsp<?="$currency_symbol".(($c_total/100))?></font></td>
    </tr>
    <?=$str;?>
    <tr>
      <td align="right"><font face="Arial" color="blue" size="+1">Sign Up Bonus :&nbsp<?="$currency_symbol$signup_bonus"?><br></font>
      </td>
    </tr>
    <tr>
      <td align="right"><font face="Arial" color="blue" size="+1">Referral Bonus :&nbsp<?="$currency_symbol".($ref_earnings/100)?><br></font>
      </td>
    </tr>

    <tr>
      <td align="right"><font face="Arial" color="blue" size="+1">Debits :
        &nbsp<?="$currency_symbol".(($d_total/100))?></font></td>
    </tr>
    <?=$str1;?>
    <tr>
      <td align="right"></td>
    </tr>
    <tr>
      <td align="left"><font face="Arial" color="blue" size="+1">Your Referrals :&nbsp</font></td>
    </tr>
    

<?
   for($i=0;$i<$Total_Tiers;$i++)
   {
        $n=$i+1;
        print <<< EOL
           <tr><td align="left"><font face="arial" size="2" color="green"><b>Tier $n:</b>($tier[$i] Total Referrals)</font></td></tr>
EOL;
   }
?>

        <!--<p>(This msg should be displayed as and when the banner ads is clicked
        by somebody provided they should provide their emailid to this site)<br>This total is not including any
referrals or referral bonuses, only credits, debits, and click-throughs.</p>-->

    <tr>
      <td><br>
      </td>
    </tr>
    <tr>
      <td align="right"><font face="Arial" color="blue" size="+1">

<?
	$mytotearn = ((($res[1]+$res[pd_clickban])/100)+($c_total/100)+$signup_bonus+(($ref_earnings + $clk_earnings)/100)-($d_total/100));
?>
Total Earnings :&nbsp <?=$currency_symbol.((($res[1]+$res[pd_clickban])/100)+($c_total/100)+$signup_bonus+(($ref_earnings+$clk_earnings)/100)-($d_total/100))?></font></td>
    </tr>
    <tr>
      <td>

        <?
        
          }
          else
           print ("<center><b>There is some Technical problem occoured in retrieval of records</b></center>");

        ?>

        <hr>
      </td>
    </tr>
    <tr>
      <td align="left"><font face="Arial" color="green" size="+1"><center><u>Advertising
        </u></center></font><i><font color="black"</font></i></td>
    </tr>
    <tr>
      <td align="left">
        <center><font face="Arial" size="+1">Paid-Email Advertising : &nbsp</font></center><br>
        <table width="100%" align="center">
          <tbody>
            <tr>
                  <td><font face="Arial" color="blue"><b>Ad Name</b></font></td>
                  <td><font face="Arial" color="blue"><b>Clicks Received</b></font></td>
             </tr>

<?
          if($res1 = mysql_query ($query5))
          {
            while($res2 = mysql_fetch_array($res1))
            {
                  print <<< HTML
                  <tr><td><a href=advertisers_area.php?login=now&adid=$res2[0]&id=$email&ps=$ps><font face="Arial" size="2" color="black">$res2[1]</font></a></td>
                  <td><font face="Arial" size="2" color ="black">$res2[2]</font></td></tr>
HTML;
            }
          }

?>
        </tbody>
      </table>
   </tr>
    </td>
	
    
    <tr>
      <td align="left">
        <center><font face="Arial" size="+1">Rotating Banner Advertising :&nbsp</font></b></center><br>
        <table width="100%" align="center">
          <tbody>
            <tr>
                  <td><font  face="Arial" color="blue"><b>Title</b></font></td>
                  <td><font  face="Arial" color="blue"><b>Impression Purchased</b></font></td>
		 <td><font face="Arial"  color="blue"><b>Impression Used</b></font></td>
             </tr>

<?
	$qry="select * from banner where user='$email'";
        if($res1 = mysql_query ($qry))
        {
            while($res2 = mysql_fetch_array($res1))
	    {
                $used=0;
	        $qry1="select * from banner_imp where banner_id=$res2[banner_id]";
	        if ($result=mysql_query($qry1))
	        {
		     while($data=mysql_fetch_array($result))
			$used+=$data[count];
	        }
                else
		   $used=0;	
	        print <<< HTML
		<tr><td><font face="Arial"  size="2" color ="black">$res2[title]</font></td>
                <td><font face="Arial" size="2"  color ="black">$res2[clicks]</font></td>
		<td><font face="Arial" size="2" color ="black">$used</font></td></tr>
HTML;
	    }
        }
?>
         </tbody>
        </table>
      </td></tr>
    <tr>
      <td align="left">
        <center><font face="Arial" size="+1">Paid-Per-Click Advertising : &nbsp</center><br>
        <table width="100%" align="center">
          <tbody>
            <tr>
                  <td><font face="Arial" color="blue"><b>Title</b></font></td>
		  <td><font face="Arial" color="blue"><b>Allowed # of Clicks</b></font></td>
                  <td><font face="Arial" color="blue"><b># of Clicks Remaining</b></font></td>
              </tr>

<?
	$qry="select * from pay_banners where user_id='$email'";

          if($res1 = mysql_query ($qry))
          {

	    while($res2 = mysql_fetch_array($res1))
	    {
          print <<< HTML
		<tr>
			<td><font face="Arial" size="2" color ="black">$res2[banner_name]</font></td>
			<td><font face="Arial" size="2" color ="black">$res2[total_clicks]</font></td>
                	<td><font face="Arial" size="2" color ="black">$res2[clicks_remaining]</font></td>
		</tr>
HTML;
	    }
          }

?>
         </tbody>
        </table>
      </td></tr>
      <tr>
	<td>
	  <hr>
	</td>
      </tr>
     <tr>
	<td>	
          <font face="Arial" color="green" size="+1"><center><u>Redemption Center
        </u></center></font><br><br>
<?               
                  $query1="select * from redempt";
		  $res1=mysql_query($query1);
		  while ($data=mysql_fetch_array($res1))
                  {

                        $amt=$data[amt]/100;
			$output=<<<HTM
			<form action="redeem.php?action=redeem" method="post">
			<table>
		       <tr><td><center><font face="Arial" size="2" color ="black">$data[item]>/font></center></td></tr>

		       <tr><td><center><font face="Arial" size="2" color ="black">$data[r_desc]</font></center></td></tr>
                       <tr><td><center><font face="Arial" size="2" color ="black">$currency_symbol$amt</font></center><br></td></tr>
HTM;
		       print $output;
                       if ($mytotearn >= $amt)
		       {
                                
			    $output=<<<HTM
			    <tr><td><center><input type="submit" name="View" value="Redeem"></center></td></tr>
			    <input type='hidden' name='mailid' value=$email>
			    <input type='hidden' name='tamt' value=$amt>
			    <input type='hidden' name='paidamt' value=$mytotearn>
			    <input type='hidden' name='type' value=$data[item]>

HTM;
			    print $output;			
			}
	            print  "</table>";
		    print "</form>";    
               }
?>
       </td>
     </tr>
    </tbody>
</table>
</body>
</html>
<?
   }
        else
           print  "<br><br><br><br><center><b>Hi&nbsp;<font
color=green>".$email."</font>&nbsp;&nbsp;You have entered a invalid user id or password</b></center></html>";
    }
      else
           print  "<br><br><br><br><center><b>Hi&nbsp;<font
color=green>".$email."</font>&nbsp;&nbsp;You have entered a invalid user id or password</b></center></html>";

dbclose($link);

print "<br><br><br><br>$html_footer";
?>
