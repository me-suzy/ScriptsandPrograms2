 <?php 
include "../tpl/clients_top.ihtml";
include "../conf/sys.conf";
include "../lib/emails.lib";

$db = c();

if (!$uid){
   if (!e(q("select id from campaigns where user_id='$auth'"))) $cm=f(q("select id from campaigns where user_id='$auth' ORDER BY RAND()"));
  $uid=$cm["id"];
}

$r = q("select * from campaigns where id='$uid' and status='1' and user_id='$auth'");
if (e($r)) {echo "Campaign not found or disabled/deleted. Go <a href=index.php>home</a> !"; exit;};
$camp=f($r);

$cred=f(q("select prev_number as n from prev where cid='$camp[id]'"));

$c1="(1";
$r = q("select * from campaigns where status='1' and user_id='$auth'");
while ($cp=f($r)) $c1.=" AND uid<>'$cp[id]'";
$c1.=")";

$a= f(q("select count(id) as n from safelistdata where status='1' and uid='$uid'"));	
$oth= f(q("select count(id) as n from safelistdata where status='1' and $c1"));	
$mtt= f(q("select count(id) as n from safelistdata where uid='$uid'"));
$mov= f(q("select count(id) as n from safelistdata where status='3' and uid='$uid'"));
$mnc= f(q("select count(id) as n from safelistdata where status='0' and uid='$uid'"));

if (!$adv_cost_per_email) $adv_cost_per_email=50;
$adv_cost=$oth[n]*$adv_cost_per_email;

$dt1=strtotime(date("d M Y H:i:s"));

 if ($subject1&&$textmail1&&$htmlmail1&&$uid)
 {
	
	$sent=0;
    
	$mem_can_send=0;
	if ($cred[n]>=$adv_cost) $mem_can_send=1;

    if ($target1=="all") {$cond="me.uid='$uid'";$mem_can_send=1;}
	if ($target1=="other") {$cond="$c1";}
    
  if ($mem_can_send)
	{
      
	  echo "<blockquote> Sending emails. Please wait ...  </blockquote><br>";
	  set_time_limit(480);

  	  $unsent=0;
	  
	   $r=q("select me.id as id, me.email as email, me.password as password, me.safemail as safemail, me.fname as fname, me.lname as lname from safelistdata me where me.status='1' and $cond");

	  while ($mem= f($r))
	    {

 $param=array(
            first=>$mem[fname],
            last=>$mem[lname]          
             ); 

	
			if (!$SAFE_MAIL) $SAFE_MAIL="do.not.reply@easypromoter.com";
            $unslnk="If you wish to stop receiving emails like this, go to this link: ".$ROOT_HOST."safelist_login.php?email=$mem[email]&password=$mem[password]";
			send_mail("$camp[title] <$SAFE_MAIL>", $mem[safemail], $subject1." [ $camp[title] ]", $textmail1." \n\r $unslnk", $htmlmail1 . " <br><br> $unslnk", $param);
	
	
			$sent++;
	        q("update prev set prev_number=prev_number-$adv_cost_per_email where cid='$camp[id]'");
	    };

	echo "<blockquote><br><b>$sent</b> emails sent";
	echo "<br></blockquote>";
	
	} else echo "<blockquote> Not enough credits in you campaign. <blockquote> ";

 };
 
 $sslnk=$ROOT_HOST."safelist_register.php?uid=$uid";
 $sllnk=$ROOT_HOST."safelist_login.php?uid=$uid";

 if (!$sent)
 {?>
<blockquote>
  <p><font face="Arial, Helvetica, sans-serif" size="3" color="#333333"><b><font color="#003399"> 
   <b> [ <?php echo $camp[title]; ?> ] </b><br>
	  Send email to safelist members &gt;</font></b></font></p>
  <p>

<?php
echo "<table border=0 cellspacing=1 cellpadding=2 bgcolor=AAAAAA align=center>";
echo "<tr><td colspan=2 bgcolor='$color_head'>$camp[title] Safelist Members</td></tr>";
echo "<tr bgcolor=FFFFFF><td>Available</td><td>$a[n]</td></tr>";
echo "<tr bgcolor=FFFFFF><td>On Vacation</td><td>$mov[n]</td></tr>";
echo "<tr bgcolor=FFFFFF><td>Not Confirmed</td><td>$mnc[n]</td></tr>";
echo "<tr bgcolor=F0F0F0><td>Total</td><td>$mtt[n]</td></tr>";
echo "<tr><td colspan=2 bgcolor='FFFFFF' align=center><a href=safelistmembers.php?uid=$uid target=_ntm3k_safelistmembers>View Members</a></td></tr>";
echo "<tr><td colspan=2 bgcolor='$color_head'>Other Members</td></tr>";
echo "<tr bgcolor=FFFFFF><td>Available</td><td>$oth[n]</td></tr>";
echo "</table>";
?>
  <font face="Arial, Helvetica, sans-serif" size="2" color="#333333">
     Available credits in campaign :  <?php echo $cred[n]; ?> <br>
	 Cost to send an email to all other safelists : <?php echo $adv_cost; ?><br>
	 You can send unlimited emails to your own safelists for free.<br>
	<br>
     Register link :<br> <I><?php echo $sslnk; ?></I>
	<br>
	 Login link :<br> <I><?php echo $sllnk; ?></I>
    <br><br>
    These variables will be submited for each email :<br>
    #first# = receiver first name<br>
    #last# = receiver last name<br>
    </font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Users 
    that can't read html emails will be able to read the text email.</font></p>
</blockquote>
<form name="form1" method="post" action="safelistemail.php">
  <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Subject</font></td>
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
      <input type="text" name="subject1" size="50" maxlength="50" value="Great site !">
        </font></td>
    </tr>
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">To</font></td>
      <td> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
        <select name="target1">
          <option value="all" selected>Your safelist (<?php echo $a[n]; ?>)</option>
		  <?php if (!$policy[free]) echo "<option value=other>All other safelists ($oth[n])</option>"; ?>
		  </select>
        </font></td>
    </tr>
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Text 
        Email</font></td>
      <td> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><br>
        <textarea name="textmail1" cols="80" rows="5">Dear #first# #last#,

Your message...

Thanks,
Safelist Owner


</textarea></td>
    </tr>
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">HTML 
        Email</font></td>
      <td> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><br>
        <textarea name="htmlmail1" cols="80" rows="5"><br>Dear #first# #last#,
<br>
<br>Your message...
<br>
<br>Thanks,
<br><I>Safelist Owner</I>

</textarea>
</font></td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <div align="center"> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
          <input type="hidden" name="sent" value="1">
          <input type="hidden" name="uid" value="<?php echo $uid ?>">
          <input type="reset" name="Submit2" value="Reset">
          <input type="submit" name="Submit" value="Send">
          </font></div>
      </td>
    </tr>
  </table>
</form>
<?php };

d($db);
include "../tpl/clients_bottom.ihtml";

?>
