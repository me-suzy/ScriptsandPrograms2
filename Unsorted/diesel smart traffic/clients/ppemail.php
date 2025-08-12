 <?php 
include "../tpl/clients_top.ihtml";
include "../conf/sys.conf";
include "../lib/emails.lib";

$db = c();
    $r = q("select cm.user_id as userid, cm.title as title, cm.url as url, mc.credits_num as credits, pr.prev_number as ccredits from campaigns cm, members_credits mc, prev pr where cm.id='$cid' and mc.user_id=cm.user_id and pr.cid='$cid'");
    $sender=f($r);


  $a= f(q("select count(me.id) as n from members me, members_policy mp where me.status='1' and me.id=mp.user_id and 1"));	
  $t= f(q("select count(me.id) as n from members me, members_policy mp where me.status='1' and me.id=mp.user_id and mp.trusted='1'"));	
  $ad= f(q("select count(me.id) as n from members me, members_policy mp where me.status='1' and me.id=mp.user_id and mp.free='0'"));
  $fr= f(q("select count(me.id) as n from members me, members_policy mp where me.status='1' and me.id=mp.user_id and mp.free='1'"));

 if ($subject1&&$textmail1&&$htmlmail1&&$cid)
 {
	$cred=$credits1+$emailcphit+$emailcpmem;
	
	$sent=0;
    
    if ($target1=="test") $cond="me.id='$sender[userid]'";   
    if ($target1=="all") {$cond="1";$sent=$mkadd["members"];}
    if ($target1=="trusted") $cond="mp.trusted='1'";
    if ($target1=="advertisers") {$cond="mp.free='0'";$sent=$mkadd["advertisers"];}
    if ($target1=="free") {$cond="mp.free='1'";$sent=$mkadd["free"];}

  $et= f(q("select count(me.id) as n from members me, members_policy mp where me.status='1' and me.id=mp.user_id and $cond"));


  if (($et[n]+$sent)*$cred<=$sender[ccredits])
	{
      echo "<blockquote> Sending emails. Please wait ...  </blockquote><br>";
	  set_time_limit(480);

  	  q("update prev set prev_number=prev_number-($cred*$sent) where cid='$cid'");	
	  $unsent=0;
	  
	  $r=q("select me.id as id, me.email as email, me.fname as fname, me.lname as lname, me.login as login from members me, members_policy mp where me.status='1' and me.id=mp.user_id and $cond group by me.id");
	  while ($mem= f($r))
	    {

 $param=array(
            first=>$mem[fname],
            last=>$mem[lname],
            mail=>$mem[email],
            username=>$mem[login],
            link=>$ROOT_HOST."emailclick.php?mid=$mem[id]&cid=$cid"
             ); 

		 if (e(q("select id from unsubscribers where member_id='$mem[id]'")))
			{
            $unslnk="If you wish to unsubscribe, go to this link: ".$ROOT_HOST."index.php?action=unsubscribe&user=$mem[email]";
			send_mail($PAID_MAIL, $mem[email], "Paid email for #first# #last# : ". $subject1, $txtmail1."\n\r Get $cred credits at : #link# \n\r $unslnk", $htmlmail1 . "<br> <a href='#link#' target=_ntm3kpaidmailwin> Get $cred credits for reading this email ! </a> <br><br> $unslnk", $param);
		
			q("update prev set prev_number=prev_number-$cred where cid='$cid'");

			q("INSERT INTO event (`id`, `sender`, `title`, `contents`, `type`, `user_id`, `credits`, `status`, `rdate`) VALUES ('', '$cid', '".(parse_mail($subject1, $param))."', 'Member credits for email received : <br><br> ".(parse_mail($htmlmail1."<br><br><a href=#link# target=_ntm3kpaidmailwin> Get $cred credits for reading this email ! </a>  <br><br> $unslnk", $param))."', 'ppemail', '$mem[id]', '".($cred-$emailcpmem)."', '1','".strtotime(date("d M Y H:i:s"))."')");

			$sent++;
			} else $unsent++;
	    };

	echo "<blockquote><br><b>$sent</b> emails sent";
	echo "<br><b>$unsent</b> selected members are not subscribed to this service";
	echo "<br><b>".($cred*$sent)."</b> credits spent </blockquote>";
	
	} else echo "<blockquote> Your campaign does not have enough credits for sending this email to all selected members. No emails were sent.<blockquote> ";

 };
 
 if (!$sent)
 {?>
<blockquote>
  <p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b><font color="#003366"> 
    SEND PAID EMAILS</font></b></font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><br>
   <b> [ <?php echo $sender[title]; ?> ] </b> <font size=1>(<?php echo $sender[url]; ?>)</font><br>
    Available credits in campaign : <?php echo $sender[ccredits]; ?> <br><br>
    Sending cost per member : <?php echo $emailcpmem; ?> <br>
    <br>
    </font><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">
	Minimum click cost per member : <?php echo $emailcphit; ?> (Minimum amount to be paid to each member.)<br>
	Minimum total click cost per member : <?php echo ($emailcphit+$emailcpmem); ?> <br>
    <br>
    These variables will be submited for each email :<br>
    #first# = receiver first name<br>
    #last# = receiver last name<br>
    #username# = receiver username<br>
    #link# = url for receiver to earn credits<br>
    </font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Users 
    that can't read html emails will be able to read the text email.</font></p>
</blockquote>
<form name="form1" method="post" action="ppemail.php">
  <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Subject</font></td>
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
        Paid email for #first# #last# : 
        <input type="text" name="subject1" size="50" maxlength="50" value="Great site !">
        </font></td>
    </tr>
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">To</font></td>
      <td> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
        <select name="target1">
          <option value="test">Yourself - test (1)</option>
          <option value="all" selected>All members (<?php echo ($a[n]+$mkadd["members"]); ?>)</option>
          <option value="trusted">Trusted members (<?php echo $t[n]; ?>)</option>
          <option value="advertisers">Advertisers (<?php echo $ad[n]+($mkadd["advertisers"]); ?>)</option>
          <option value="free">Free members (<?php echo ($fr[n]+$mkadd["free"]); ?>)</option>
        </select>
        </font></td>
    </tr>
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Extra 
        pay per hit</font></td>
      <td> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
        <input type="text" name="credits1" size="4" value="0">
        credits. </font></td>
    </tr>
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Text 
        Email</font></td>
      <td> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><br>
        <textarea name="textmail1" cols="80" rows="5">Dear #first# #last#,

By visiting  SITE, you earn credits and also see great content.

Regards,
The SITE admin


</textarea>
        <br>
        Get credits at : #link#</font></td>
    </tr>
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">HTML 
        Email</font></td>
      <td> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><br>
        <textarea name="htmlmail1" cols="80" rows="5"><br>Dear #first# #last#,
<br>
<br>By visiting  <b>SITE</b>, you earn credits and also see great content.
<br>
<br>Regards,
<br><I>The SITE admin</I>

</textarea>
        <br>
        <a href="#link#">Get credits for reading this email !</a></font></td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <div align="center"> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
          <input type="hidden" name="sent" value="1">
          <input type="hidden" name="cid" value="<?php echo $cid ?>">
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
