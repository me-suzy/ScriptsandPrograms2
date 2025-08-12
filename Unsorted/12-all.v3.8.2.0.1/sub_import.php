<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_374; ?> </strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"> 
  <?PHP
if ($cval == ""){
?>
  <strong><?PHP print $lang_375; ?></strong>: </font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_376; ?><br>
  </font><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_389; ?>:</font></p>
<blockquote> 
  <p><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_5; ?>;<?PHP print $lang_4; ?>;field1;field2;field3;field4;field5;field6;field7;field8;field9;field10,signup 
    date </font></p>
</blockquote>
<p><font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_377; ?></font></p>
<table width="570" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="175"><div align="center">
        <table width="100%" height="29" border="0" cellpadding="3" cellspacing="0" background="media/h_n1.gif">
          <tr>
            <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=sub_import&nl=<?PHP print $nl; ?>&im_type=form"><?PHP print $lang_521; ?></a></font></div></td>
          </tr>
        </table>
      </div></td>
    <td width="20" bgcolor="#FFFFFF"><div align="center"><font size="2"><font face="Arial, Helvetica, sans-serif"></font></font></div></td>
    <td width="175"><div align="center"> 
        <table width="100%" height="29" border="0" cellpadding="3" cellspacing="0" background="media/h_n1.gif">
          <tr> 
            <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php?page=sub_import&nl=<?PHP print $nl; ?>&im_type=file"><?PHP print $lang_522; ?></a></font></div></td>
          </tr>
        </table>
        
      </div></td>
    <td width="200" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr valign="top" bgcolor="#FFFFFF"> 
    <td colspan="4"><img src="media/h_b.gif" width="560" height="1"></td>
  </tr>
</table>
<p><font size="1" face="Arial, Helvetica, sans-serif"></font></p>
<form action="main.php" method="post" enctype="multipart/form-data" name="" id="">
  <p> 
    <?PHP
  	if ($im_type == "" or $im_type == "form"){
  ?>
    <textarea name="words" cols="65" rows="12"></textarea>
    <?PHP
  	}
  	else {
  ?>
    <input name="file" type="file" id="file">
    <?PHP
  }
  ?>
  </p>
  <p><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_378; ?></b></font></p>
  <table width="400" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="50"><b><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="sep1" type="text" id="sep12" value=";" size="3" maxlength="5">
        </font></b></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"><font size="1"><?PHP print $lang_379; ?> 
        = ; (semicolon)</font></font></td>
    </tr>
  </table>
  <p><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_380; ?>:</b></font></p>
  <p> <font size="2" face="Arial, Helvetica, sans-serif"> 
    <input type="radio" name="type" value="par" checked>
    <?PHP print $lang_381; ?><br>
    </font><font size="2" face="Arial, Helvetica, sans-serif"> 
    <input type="radio" name="type" value="cust">
    <?PHP print $lang_382; ?><br>
    <i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?PHP print $lang_383; ?>: 
    </i> 
    <input type="text" name="cust">
    </font></p>
  <p><font size="2" face="Arial, Helvetica, sans-serif"> 
    <input name="ropt" type="checkbox" id="ropt" value="yes">
    <font color="#990000"><?PHP print $lang_384; ?></font></font></p>
  <blockquote> 
    <p><font size="2" face="Arial, Helvetica, sans-serif"> <?PHP print $lang_385; ?></font></p>
  </blockquote>
  <p><font size="2" face="Arial, Helvetica, sans-serif"> 
    <input name="subcheck" type="checkbox" id="subcheck" value="yes">
    <font color="#990000"><?PHP print $lang_386; ?></font> </font></p>
  <blockquote> 
    <p><font size="2" face="Arial, Helvetica, sans-serif"> <?PHP print $lang_387; ?></font></p>
  </blockquote>
  <p> <font size="2" face="Arial, Helvetica, sans-serif">
    <input name="whichtable" type="radio" value="ListMembers" checked>
    <?PHP print $lang_534; ?><br>
    <font color="#666666">
    <input name="whichtable" type="radio" value="ListMembersU">
    <?PHP print $lang_535; ?></font></font></p>
  <p> 
    <input type="submit" name="Submit" value="<?PHP print $lang_21; ?>">
    <input type="hidden" name="page" value="sub_import">
    <input type="hidden" name="nl" value="<?PHP print $nl; ?>">
    <input type="hidden" name="cval" value="TRUE">
    <input name="im_type" type="hidden" id="im_type" value="<?PHP print $im_type; ?>">
    <input type="hidden" name="max_file_size" value="10000000">
  </p>
</form>
<?PHP }
else {
?>
<div align="center"> 
  <p align="left"><font size="2" face="Arial, Helvetica, sans-serif"> 
    <?PHP
  set_time_limit(0); 
  $num = 0;
  $num2 = 0;
  if ($file != "none" AND $file != "") {
    $tem_f = fopen ($file, "r");
    $words = fread($tem_f, filesize ($file));
    fclose($tem_f);
    unlink($file);
  }
  if ($im_type == "file"){
  //print $file;
  //$words = file("$file");
  }
  if ($type == par){
  $words=explode("\n",$words);
  }
  if ($type == cust){
  $words=explode("$cust",$words);
  }
  
  				$check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
				$chk = mysql_fetch_array($check);
				if ($chk["a_mx"] != ""){
				$ctnow = count($words);
				$findcount = mysql_query ("SELECT COUNT(nl) FROM ListMembers
                         WHERE nl = '$nl'
						 AND email != ''
                       ");
				$num_email = mysql_result($findcount, 0, 0); 
				$num_email2 = $num_email;
				$num_email = $num_email + $ctnow;
				if ($num_email >= $chk["a_mx"]){
						$box_status = "1";
						print $lang_469;
						print "<p>Currently contains $num_email2 subscribers";
						$ltdt = $chk["a_mx"];
						print "<br>Limit of $ltdt subscribers";
						die();
						
				}
				}

  while($words[$num] != "") {
ignore_user_abort();
  $words2=explode("$sep1",$words[$num]);
   $email = $words2[0];
  $name = $words2[1];
  $field1 = $words2[2];
    $field2 = $words2[3];
    $field3 = $words2[4];
    $field4 = $words2[5];
    $field5 = $words2[6];
    $field6 = $words2[7];
    $field7 = $words2[8];
    $field8 = $words2[9];
    $field9 = $words2[10];
    $field10 = $words2[11];
	$sdate = $words2[12];
	if ($sdate == ""){
	$today = date("Ymd");
	}
	else {
	$today = $sdate;
	}
	$email = ereg_replace ("[\r]", "", $email);
	$email = ereg_replace (" ", "", $email);
	@$email = ereg_replace ("&nbsp\;", "", $email);
	$email = ereg_replace ("  ", "", $email);
	if ($subcheck == "yes"){
	$countcheck = mysql_query ("SELECT * FROM ListMembersU
						 WHERE em LIKE '$email'
						 AND nl LIKE '$nl'
	");
	$countcheckd = mysql_num_rows($countcheck);
	}
	else {
	$countcheckd = "0";
	}
		
	if ($countcheckd == "0"){
	
	if($ropt == "yes" AND $whichtable == "ListMembers"){
	$result = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
	$thanks = mysql_fetch_array($result);
	mysql_query ("INSERT INTO ListMembers (email, name, nl, sdate, sip, active, field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, comp) VALUES ('$email' ,'$name' ,'$nl' ,'$today' ,'$REMOTE_ADDR' ,'1' ,'$field1' ,'$field2' ,'$field3' ,'$field4' ,'$field5' ,'$field6' ,'$field7' ,'$field8' ,'$field9' ,'$field10','$HTTP_USER_AGENT')");  
								require("sender.inc.php");
								if ($name == ""){
									$name = "Subscriber";
								}
								$message = stripslashes($thanks["icontent"]);
								$message = ereg_replace ("subscribername", "$name", $message);
								$message = ereg_replace ("subscriberemail", "$email", $message);
								$subject = stripslashes($thanks["isubject"]);
								$subject = ereg_replace ("subscribername", "$name", $subject);
								$subject = ereg_replace ("subscriberemail", "$email", $subject);
								$from = $thanks["email"];
								$mtype = $thanks["confirmoptt"];
								$urlfinder = mysql_query ("SELECT * FROM Backend
															 WHERE valid LIKE '1'
															 limit 1
														 ");
								$findurl = mysql_fetch_array($urlfinder);
								$murl = $findurl["murl"];
								$cemail = base64_encode($email);
								$message = ereg_replace ("%CONFIRMLINK%", "$murl/box.php?p=$p&e=$cemail&funcml=csub&nl=$nl", $message);
								$mail = new htmlMimeMail();
								if ($mtype == 'html') {
									$mail->setHtml($message);
								} elseif ($mtype == 'text') {
									$message = ereg_replace ("\r", "", $message);
									$mail->setText($message);
								} 
								$mail->setFrom($from);
								$mail->setSubject($subject);
								//$mail->setReturnPath($from);
								$sendResult = $mail->send(array($email));
	}
	else {
		if($whichtable == "ListMembers"){
			mysql_query ("INSERT INTO ListMembers (email, name, nl, sdate, sip, active, field1, field2, field3, field4, field5, field6, field7, field8, field9, field10, comp) VALUES ('$email' ,'$name' ,'$nl' ,'$today' ,'imported' ,'0' ,'$field1' ,'$field2' ,'$field3' ,'$field4' ,'$field5' ,'$field6' ,'$field7' ,'$field8' ,'$field9' ,'$field10','imported')");  
 		}
		if ($whichtable == "ListMembersU"){
			mysql_query ("INSERT INTO ListMembersU (em, nl) VALUES ('$email','$nl' )");  
		}
 }
 }
$num = $num + 1;

$num2 = 0;
flush();
}
?>
    <font color="#FF0000"><?PHP print $lang_388; ?></font>.</font> 
    <?PHP } ?>
  </p>
</div>
<div align="center"></div>
