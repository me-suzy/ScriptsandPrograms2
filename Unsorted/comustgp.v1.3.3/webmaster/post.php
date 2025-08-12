<?
#######################################
###         ComusTGP version 1.3.3  ###
###         nibbi@nibbi.net         ###
###         Copyright 2002          ###
#######################################
?>
<?
include($DOCUMENT_ROOT . "/includes/config.inc.php");

$description = strtolower($description);
$description = ucwords($description);
$popup = "window.open";

if($useconfirm == 'Yes'){
   $confirm = "waiting";
      }else{
   $confirm = "yes";
}

if($useblacklist == 'Yes'){
   $result = mysql_query("SELECT * from tblBlacklist where email = '$email'"); 
      $num = @mysql_num_rows($result); 
      If ($num) { 
      echo "<center><h1><font color=red>You are blacklisted</font>";
      die();
      }
   }
if($usedupe == 'Yes'){
   $result = mysql_query("SELECT * from tblTgp where url = '$url'");
      $num = @mysql_num_rows($result); 
      If ($num) { 
      echo "<center><h1><font color=red>This is a duplicate URL.</font><br>
            <br>Please only post a gallery once.</h1></center>";
      die();
      }
   }
/* Check for and report reciprical links -- always on */
$open = @fopen("$url", "r");
      if(!$open){ 
         echo "Submitted Page not found.";
            die();
      }else{
      $read = fread($open, 15000);
      fclose($open);
      $recipcheck= substr_count($read, "$recip");
   if(!$recipcheck){
      $recreport = "No";
         }else{
      $recreport = "Yes";
   }
}     
if($badwordcheck == 'Yes'){
      $ckbad = explode(",", "$badword");
      while(list($v) = each($ckbad)){  
      $ckbad[$v] = trim($ckbad[$v]);
   $badcheck= substr_count($read, "$ckbad[$v]");
      if($badcheck){
      echo "<center><h1>You are using the banned word \"$ckbad[$v]\" someplace on your post</h1></center>"; 
      die();
      }
   }
}
if($popcheck == 'No'){
      $badpop = substr_count($read, "$popup");
         if($badpop){
         echo "<center><h1>Pop-up windows are not allowed on your galleries.</h1></center>"; 
         die();
         }
}
if($reqrecip == 'Yes'){
      if(!$recipcheck){
      echo "<center><h1><font color=red>A reciprical link is required.</h1></font><p><h3>Please be sure that you have a link back to me and it is sent to $recip.</h3></p>";
      die();

   }
}
if($usepreferred == 'Yes'){
   $result = mysql_query("SELECT * from tblPreferred where pass = '$pass'"); 
      $num = @mysql_num_rows($result); 
      If ($num) { 
         mysql_query("INSERT into tblTgp (nickname, email, url, category, description, date, newpost, accept, recip, sessionid) VALUES ('$nickname', '$email', '$url', '$category', '$description', '$dnow', 'no', 'yes', '$recreport', '$session')");

   Echo "<center><table width=600 border=0 cellspacing=3 cellpadding=3>
   <tr>
   <td><h3>Thank you for your submission to $sitename.</h3><b>This is what we show you submitted:</b><br><br><b>Email:</b> $email<br><b>URL:</b> $url<br><b>Category:</b> $category<br><b>Description:</b> $description<br><br>As a preferred poster, your gallery has already been listed.<br><br>If you have any questions, please feel free to email me at $tgpemail<br><br>Regards,<br>$siteowner<br><br></td>
  </tr>
</table></center><br>";   
   die();
   }
}
if($useconfirm == 'Yes'){

   $recipient = "$email";
   $subject = "Submission to $sitename";
   $message = "Thank you for your submission to $sitename.<br><br><b>This is what we show you submitted:</b><br>Email: $email<br>URL: $url<br>Category: $category<br>Description: $description<br><br>Before your link can be reviewed and posted, you will need to click on the link below...<br><br>
<a href=\"http://www.$sitename/accept.php?accept=yes&seid=$session\"><font color red>
<h1><font color=red>Click Here To Complete Your Post</font></h1>
</font></a><br>If that link doesnt work, please copy and paste the link below into your browser.<p>
http://www.$sitename/accept.php?accept=yes&seid=$session<br><br>
If you have any questions, please feel free to email me at $tgpemail</p><p>Regards,<br>$siteowner<br>
$tgpemail</p>";

   $extra = "From: $tgpemail\r\nReply-To: $tgpemail\r\nContent-type:text/html\r\n";
   
   mail ($recipient, $subject, $message, $extra);
}


mysql_query("INSERT into tblTgp (nickname, email, url, category, description, date, newpost, recip, sessionid) VALUES ('$nickname', '$email', '$url', '$category', '$description', '$dnow', '$confirm', '$recreport', '$session')");

Echo "<center><table width=600 border=0 cellspacing=3 cellpadding=3>
  <tr>
      <td><h3>Thank you for your submission to $sitename.</h3><b>This is what we show you submitted:</b>
      <br>
      <b>Email:</b> $email<br>
      <b>URL:</b> $url<br>
      <b>Category:</b> $category<br>
      <b>Description:</b> $description
   </td>
  </tr>
</table></center>";
?>
