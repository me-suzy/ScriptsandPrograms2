<?
include ('../includes/global.php');

//DB connectivity
$link=dbconnect();

$query6="select ad_id,title,clicks_recd from advt_email where email_id='$u'";
$res11 = mysql_query($query6);

if($t=="")
{
    $query1="select * from member_details where email_id='$u' ";
  
    if($res =mysql_query ($query1))
    {
        $res = mysql_fetch_array($res);
        $mypassword = md5($res[9]);
        $myemail = $res[1];
      
        $query2="select * from member_earnings where mem_id=$res[0]";
        $query3="select parent_id from member_referrals where mem_id=$res[0]";
        $query5="select count(*),mem_id from member_referrals where parent_id=$res[0] group by mem_id";
        if($earn =mysql_query ($query2))
            $earn = mysql_fetch_array($earn);
	  	   
        if($par =mysql_query ($query3))
        {
            $par1 = mysql_fetch_array($par);
            $query4="select email_id from member_details where mem_id=$par1[0]";
            if($parent_id =mysql_query ($query4))
                $parent_id = mysql_fetch_array($parent_id);
        }
        $pid=$res[0];
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
	    Reason for credit&nbsp;&nbsp;Amount Credited&nbsp;&nbsp;Date of credit
	    </b>
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
	    <td align="right"><font size="2" face="Arial">
	    Reason for debit&nbsp;&nbsp;Amount Debited&nbsp;&nbsp;Date of debit
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
    }
}
elseif($t=="done" && $edit=="Save")
{
    $query ="update member_details set  email_id='$email',f_name='$f_name',l_name='$l_name',address='$adds',
 city='$city', state='$state',zip='$zip',country='$country',password='$pass',last_login='$time_stamp' where
mem_id=$memid ";

    print "<b><font color=green>Datas successfully updated </font><br><br>";
    mysql_query($query) or die("update".mysql_error());
}
elseif($t=="done" && $edit=="Delete the User")
{
    $sql="SELECT mem_id from member_details where email_id='$email' ";
    $res=mysql_query($sql);
    $mem_id=mysql_fetch_array($res);
    $query11="select ad_id from advt_email where email_id='$email'";
    $result=mysql_query($query11);
    if ($ad_id=mysql_fetch_array($result)) {
	$sql4="delete from email_clicks where ad_id='$ad_id[0]'";
	mysql_query($sql4);
    }
    $sql="delete from member_details where email_id='$email'";
    $sql1="delete from additional_info where mem_id='$mem_id[0]'";
    $sql2="delete from advt_email where email_id='$email'";
    $sql3="delete from advt_banner where email_id='$email'";

    $sql5="delete from member_credit where mem_id='$mem_id[0]'";
    $sql6="delete from member_debit where mem_id='$mem_id[0]'";
    $sql7="delete from paid_mail where mem_id='$mem_id[0]'";
    $sql8="delete from member_earnings where mem_id=$mem_id[0]";
    $sql9="delete from member_referrals where mem_id=$mem_id[0]";

    if ((mysql_query($sql))&&(mysql_query($sql1))&&(mysql_query($sql2))&&(mysql_query($sql3))&&(mysql_query($sql5))&&(mysql_query($sql6))&&(mysql_query($sql7))&&(mysql_query($sql8))&&(mysql_query($sql9)))
    {
	if(file_exists("../gold_membership/gold_funs.php")) { include ("../gold_membership/gold_funs.php"); }
	//to move the tiers on level above
	move_Tier($mem_id[0]);
	$userid[0]=$email;
	//to delete this user id from the gold membership
	$out=remove_GoldMember($userid,"$gold_path/random.txt");
	print "Record's of $u are Successfuly Deleted";
    }
}

?>
<body>
 <font face="arial" size="3"><b><center>Account Manager</center></b></font>
<font face="Arial" size="2" color="blue"><b>
<?=$res[1]?>'s Account:
</b></font><br> <br>

<table>
<tr>
<td valign="top" width="50%">
<b><font face="Arial" size="2">Editable Information:</font></b><br><br>
<form action="userinfo_admin.php?t=done&&u=<?=$res[1]?>" method="POST">
<table>
<tr><td>
<font face="Arial" size="2">Member Id:</font>
</td><td>
<b><font face="Arial" size="2"><?=$res[0]?></font></b>
<input type=hidden value="<?=$res[0]?>" name=memid>
<input type=hidden value="<?=$res[13]?>" name="time_stamp">
</td></tr>
<tr><td>
<font face="Arial" size="2">E-Mail:</font>
</td><td>
 <input type="text" name="email" value="<?=$res[1]?>">
</td></tr>
<tr><td>
<font face="Arial" size="2">Password:</font>
</td><td>
 <input type="text" name="pass" value="<?=$res[9]?>">
</td></tr>
<tr><td>
<font face="Arial" size="2">First Name:</font> 
</td><td><input type="text" name="f_name" value="<?=$res[2]?>"><br>
</td></tr>
<tr><td>
<font face="Arial" size="2">Last Name: </font>
</td><td><input type="text" name="l_name" value="<?=$res[3]?>"><br>
</td></tr>
<tr><td>
<font face="Arial" size="2">Address: </font>
</td><td>
<input type="text" name="adds" value="<?=$res[4]?>"><br>
</td></tr>
<tr><td>
<font face="Arial" size="2">City: </font>
</td><td>
<input type="text" name="city" value="<?=$res[5]?>"><br>
</td></tr>
<tr><td>
<font face="Arial" size="2">State:</font>
</td><td>
 <input type="text" name="state" value="<?=$res[6]?>"><br>
</td></tr>
<tr><td>
<font face="Arial" size="2">Zip Code:</font>
</td><td>
 <input type="text" name="zip" value="<?=$res[7]?>"><br>
</td></tr>
<tr><td>
<font face="Arial" size="2">Country:</font> 
</td><td>
<input type="text" name="country" value="<?=$res[8]?>"><br>
</td></tr>
</table>
<input type="submit" name="edit" value="Save">&nbsp;&nbsp;
<input type="submit" name="edit" value="Delete the User">

</form>

<form action="infoadmin.php?t=done&&mem_id=<?=$res[0]?>" method="post">
<font face="Arial" size="2"><b>Add A Referral</b></font>
<br>
<font face="Arial" size="2">Name of Referral:</font><input type="text" name="ref_id">
<input type="submit" name="add" value="Add Referral">
</form>

</td>


<td valign="top" width="50%">
<font face="Arial" size="2"><b>Non-Editable Information:</b><br><br>
Up-Line(The person who reffered): <b><?=$parent_id[0]?></b><br>
Account Status: <b><?=$res[12]?></b><br>
IP Address: <b><?=$res[11]?></b><br>
</font>
</td></tr></table>

<hr>
<font face="Arial" size="3" color="#000000"><b>
Current Earnings for: <?=$res[1]?>
</b></font>

<font face="" size="">
<table width="100%" cellspacing="3" cellpadding="3" border="0">
<tr><td align="right">
<font face="Arial" size="+1" color="blue">
<?=$res[1]?>'s Paid Email Clickthrus: <?=$currency_symbol.(($earn[1]+$earn[pd_clickban])/100)?>
</font>
</td></tr>
<tr><td  align="right">
<font face="Arial" size="+1" color="blue">
Credits:<?="$currency_symbol".(($c_total/100))?></font></td>
</tr> 
<?=$str;?>
<tr><td align="right">
<font face="Arial" size="+1" color="blue">Sign Up Bonus :<?=$currency_symbol.$signup_bonus?><br> Referral Bonus :<?=$currency_symbol.($ref_earnings/100)?><br></font></td></tr>
<tr><td  align="right">
<font face="Arial" size="+1" color="blue">
Debits: <?="$currency_symbol".(($d_total/100))?>
</font>
</td></tr>
<?=$str1;?>
<tr><td  align="left">
<font face="Arial" size="3" color="#000000"><b>
<?=$res[1]?>'s Referrals:
</b></font>
</td></tr>
<?
   for($i=0;$i<$Total_Tiers;$i++)
   {
          $n=$i+1;
           print "<tr><td  align='left'><font face='arial' size='2' color='#000000'><b>Tier $n:</b> ($tier[$i] Total
Referrals)</font></td></tr>";
   }
?>
<tr>
      <td align="right"><font face="Arial" color="blue" size="+1"><b>Total
        Earnings: <?=$currency_symbol.((($earn[1]+$earn[pd_clickban])/100)+($c_total/100)+$signup_bonus+(($ref_earnings+$clk_earnings)/100)-($d_total/100))?></b></font></td>
    </tr>
<tr><td>
<hr>
</td></tr>
      
<tr>
      <td align="left"><font face="Arial" color="green" size="+1"><center><u>Advertising
        </u></center></font><i><font color="black"</font></i></td>
</tr>
     <tr>
      <td align="left">
        <center><font face="Arial" size="+1">Paid-Email Advertising:</font></center><br>
        <table width="100%" align="center">
          <tbody>
            <tr>
                  <td><font face="arial" color="blue"><b>Ad Name</b></font></td>
                  <td><font face="arial" color="blue"><b>Clicks Received</b></font></td>
             </tr>

<?         
            while($res12 = mysql_fetch_array($res11))
            {
                  print <<<HTML
                  <tr><td><a href="../site/advertisers_area.php?login=now&adid=$res12[0]&id=$myemail&ps=$mypassword">$res12[1]</a></td>
                  <td>$res12[2]</td></tr>
HTML;
}
?>
          </tbody>
        </table>
     </td>
</tr>
 
    <tr>
      <td align="left">
        <center><font face="Arial" size="+1">Rotating Banner Advertising:</font></center><br>
        <table width="100%" align="center">
          <tbody>
            <tr>
                  <td><font face="arial" color="blue"><b>Title</b></font></td>
                  <td><font face="arial" color="blue"><b>Impression Purchased</b></font></td>
		 <td><font face="arial" color="blue"><b>Impression Used</b></font></td>
             </tr>
<?
	$qry="select * from banner where user='$myemail'";
          if($res1 = mysql_query($qry))
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
		<tr><td>$res2[title]</td>
                <td>$res2[clicks]</td>
		<td>$used</td></tr>
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
	$qry="select * from pay_banners where user_id='$u'";

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
    
</table>
</font>
</td>
<td rowspan="2" width="28"></td>
</tr>
<tr align="center">
<center><td colspan="2" class="list"><br><hr><br>&copy; 2001 <?=$siteadds?>. All Rights Reserved</td></center>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
<?

// DB disconnection
dbclose($link);

print  <<<HTM
<br><br><center><pre>Click the link. <a href="$admin_url/admin.php">Go to Admin Index Page</a></pre></center>
HTM;
?>
