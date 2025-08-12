 <?php 
require("../conf/sys.conf");
require("../lib/ban.lib");
require("../lib/mysql.lib");
include "../lib/emails.lib";

$db = c();

  $a= f(q("select count(*) as n from members me, members_policy mp where me.status='1' and me.id=mp.user_id"));
  $nc= f(q("select count(*) as n from members me where me.status='0'"));	
  $ds= f(q("select count(*) as n from members me, members_policy mp where me.status='2' and me.id=mp.user_id"));	
  $t= f(q("select count(*) as n from members me, members_policy mp where me.status='1' and me.id=mp.user_id and mp.trusted='1'"));	
  $ad= f(q("select count(*) as n from members me, members_policy mp where me.status='1' and me.id=mp.user_id and mp.free='0'"));
  $fr= f(q("select count(*) as n from members me, members_policy mp where me.status='1' and me.id=mp.user_id and mp.free='1'"));

 if ($subject1&&$textmail1&&$htmlmail1)
 {

    if ($target1=="all") $cond="1";
    if ($target1=="trusted") $cond="mp.trusted='1'";
    if ($target1=="advertisers") $cond="mp.free='0'";
    if ($target1=="free") $cond="mp.free='1'";

      echo "<blockquote> Sending emails. Please wait ...  </blockquote><br>";
	  set_time_limit(480);
	
	  $sent=0;
	  $unsent=0;
	  
if (($target1!="notconfirmed")&&($target1!="disabled"))  $r=q("select me.id as id, me.email as email, me.fname as fname, me.lname as lname, me.login as login, me.pswd as pswd from members me, members_policy mp where me.status='1' and me.id=mp.user_id and $cond");

if ($target1=="notconfirmed")  $r=q("select me.id as id, me.email as email, me.fname as fname, me.lname as lname, me.login as login, me.pswd as pswd from members me where me.status='0'");

if ($target1=="disabled")  $r=q("select me.id as id, me.email as email, me.fname as fname, me.lname as lname, me.login as login, me.pswd as pswd from members me, members_policy mp where me.status='2' and me.id=mp.user_id");

	  while ($mem= f($r))
	    {

 $param=array(
            first=>$mem[fname],
            last=>$mem[lname],
            mail=>$mem[email],
            username=>$mem[login],
            password=>$mem[pswd],
            id=>$mem[id],
            clink=>$ROOT_HOST."confirm.php?mid=".$mem[login],
            loginlink=>$ROOT_HOST."login.php?username=".$mem[login]."&password=".$mem[pswd]
             ); 

		 if (e(q("select id from unsubscribers where member_id='$mem[id]'"))||$sendtoall)
			{
	        if ($sendbyemail) send_mail($ADMIN_MAIL, $mem[email], "Admin message for #first# #last# : ". $subject1, $txtmail1, $htmlmail1, $param);
		
	        if ($sendtoinbox) q("INSERT INTO event (`id`, `sender`, `title`, `contents`, `type`, `user_id`, `credits`, `status`, `rdate`) VALUES ('', 'admin', '".(parse_mail($subject1, $param))."', '".(parse_mail($htmlmail1, $param))."', 'news', '$mem[id]', '0', '1','".strtotime(date("d M Y H:i:s"))."')");

			$sent++;
			} else $unsent++;
	    };

	echo "<blockquote><br><b>$sent</b> members received the message";
                if ($sendbyemail) echo "<br> &nbsp; +  by email.";
                if ($sendtoinbox) echo "<br> &nbsp; + in account inbox.";
	if (!$sendtoall) echo "<br><b>$unsent</b> selected members didn't receive the message because unsubscription";
	echo "<br></blockquote>";
	

 };
 
 if (!$sent)
 {?>
<blockquote>
  <p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b><font color="#003366"> 
    POST ADMIN MESSAGES AND NEWS </font></b></font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">These 
    variables will be submited for each email :<br>
    #first# = receiver first name<br>
    #last# = receiver last name<br>
    #username# = receiver username<br>
    #password# = receiver account password<br>
    #mail# = receiver email<br>
    #clink# = receiver member confirmation link<br>
    #loginlink# = receiver member direct login link<br>
    #id# = receiver member id<br>
    </font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Users 
    that can't read html emails will be able to read the text email.</font></p>
</blockquote>
<form name="form1" method="post" action="post.php">
  <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Subject</font></td>
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
        <input type="text" name="subject1" size="50" maxlength="50" value="Admin message">
        </font></td>
    </tr>
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">To</font></td>
      <td> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
        <select name="target1">
          <option value="all" selected>All active members ( 
          <?php echo $a[n]; ?>
          )</option>
          <option value="trusted">Trusted members ( 
          <?php echo $t[n]; ?>
          )</option>
          <option value="advertisers">Advertisers ( 
          <?php echo $ad[n]; ?>
          )</option>
          <option value="free">Free members ( 
          <?php echo $fr[n]; ?>
          )</option>
          <option value="notconfirmed">Not confirmed ( 
          <?php echo $nc[n]; ?>
          )</option>
          <option value="disabled">Disabled ( 
          <?php echo $ds[n]; ?>
          )</option>
        </select>
        </font></td>
    </tr>
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Text 
        Email</font></td>
      <td> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
        <textarea name="textmail1" cols="80" rows="5">Dear #first# #last#,

Regards,
The SITE admin


</textarea>
        </font></td>
    </tr>
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">HTML 
        Email</font></td>
      <td> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
        <textarea name="htmlmail1" cols="80" rows="5"><br>Dear #first# #last#,
<br>
<br>Regards,
<br><I>The SITE admin</I>

</textarea>
        </font></td>
    </tr>
    <tr>
      <td colspan="2"><br> <font face="Arial, Helvetica, sans-serif"> <font size="1"> 
        <font size="2">  <font color="#333333">
        <input type="checkbox" name="sendtoall" value="1">
        Send also to unsubcribed members. &nbsp;&nbsp;&nbsp;&nbsp; 
        <input type="checkbox" name="sendbyemail" value="1" checked>
        Send by email. &nbsp;&nbsp;&nbsp;&nbsp; 
        <input type="checkbox" name="sendtoinbox" value="1" checked>
        Send in account inbox.</font></font></font></font><br></td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <div align="center"> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
	<br>
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
include "footer.html";
?>
