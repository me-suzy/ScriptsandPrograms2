<?php

// ***************************************************************************
// * Copyright © 2003 - Thomas Egtvedt - www.evaria.com - thomas@evaria.com  *
// *                                                                         *
// *      ECMS - Content Management System - Support: forum.evaria.com       *
// *                                                                         *
// * This program is commercial software; you can not redistribute/reproduce *
// * it and/or sell it without the prior written consent of www.evaria.com   *
// *                                                                         *
// * This program is distributed in the hope that it will be useful,         *
// * but WITHOUT ANY WARRANTY; without even the implied warranty of          *
// * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                    *
// ***************************************************************************
//
// This is the Email Client head script for the ECMS, 
// please review readme.txt before installing it on your server.
//

//-------------------- Verifying the fields ---
if(isset($_REQUEST["submit"]))
{
$Field_bug = "";
$Name = $_REQUEST['Name'];
$Email = $_REQUEST['Email']; 
$Message  = $_REQUEST['Message'];

	if(empty($Name))
	{                 
    	$Field_bug.="<li>Please enter your name or nickname !</li>";
	}
	if(empty($Message))
	{
    	$Field_bug.="<li>Please enter your message !</li>";
	}
	if(empty($Email))
	{                 
    	$Field_bug.="<li>Please enter your Email address !</li>";
	}
	if(!empty($Email))
	{
	$email_ok = eregi("^[_\.0-9a-z-]+@([0-9a-z-]+\.)+[a-z]{2,4}$",$Email);		
		if(TEST_MAIL)
		{		
			list($user, $domaine) = split("@", $Email, 2);
			$domain_ok = @checkdnsrr($domaine, "MX");
		}
		else
		{
		    $domain_ok = true;
		}
	}
	if (!$email_ok || !$domain_ok)
	{
		$Field_bug.="<li><b>$Email</b> is not a valid Email address !</li>";

	}
	
	$No_mail = "$Field_bug";
	
	if(empty($Field_bug) && 
	eregi("^[a-z0-9]+([_.-][a-z0-9]+)*@([a-z0-9]+([.-][a-z0-9]+)*)+\\.[a-z]{2,4}$",$Email))
	{
		$ok = 1;
	}
	elseif(!empty($Field_bug) && 
	eregi("^[a-z0-9]+([_.-][a-z0-9]+)*@([a-z0-9]+([.-][a-z0-9]+)*)+\\.[a-z]{2,4}$",$Email))
	{
		$ok = false;
	}
	else
	{
		$Fake_mail = "<li>Please return to verify your Email address !</li>";
		$ok = false;
	}

//-------------------- Upload file(s) if any ---
$status = "";
$new_name = "";
$uploaded = "";
$get_files = "";
$all_names = "";
$No_upload = "";
	
for($i=0;$i<count($_FILES["fileup"]["tmp_name"]);$i++)
{
	$name=$_FILES["fileup"]["name"][$i];
	$temp=$_FILES["fileup"]["tmp_name"][$i];
	$size=$_FILES["fileup"]["size"][$i];
	$type=$_FILES["fileup"]["type"][$i];
    $ext = strrchr($name,'.');
	if (!empty($name) && ($limit_size == "yes") && ($min_size > $size))
	{
        $No_upload.= "Error: Your file, $name ($size bytes) is to small (min: $min_size bytes).<br>\n";
	}
	if (!empty($name) && ($limit_size == "yes") && ($max_size < $size))
	{
        $No_upload.= "Error: Your file, $name ($size bytes) is to big (max: $max_size bytes).<br>\n";
	}
	if (!empty($name) && ($limit_ext == "yes") && (!in_array($ext,$extensions)))
	{
        $No_upload.= "Error: We do not allow this file-type ($ext) on our server.<br>\n";
	}
	if (empty($No_upload) && $size > 0)
	{
	global $up_dir, $all_names;
	$new_name = time() . "-" . $name;
	$new_name = str_replace(" ","_",$new_name);
	@move_uploaded_file($temp, $up_dir . $new_name);
	$all_names.= "$new_name ($size bytes)\n";
    }
}


//-------------------- File upload message ---
if(empty($all_names))
{
	$status = "No file sent.<br><br>$No_upload";
	$uploaded = "Not this time...";
}
else
{
	$status = "Your file(s) was successfully uploaded.<br><br>$No_upload";
	$uploaded = "Yes !!!";
	$get_files = "File Info:\n$all_names\n";
}

//-------------------- Building the Email ---
if($ok=="1")
{
 $sentat = date("M j G:i:s O");
 if (getenv(HTTP_X_FORWARDED_FOR)){
 $ip=getenv(HTTP_X_FORWARDED_FOR);
 } else { 
 $ip=getenv(REMOTE_ADDR);
 } 
 $host = isset($REMOTE_HOST) ? $REMOTE_HOST : @gethostbyaddr($ip);
        if($host == $ip){
            $host = getenv('REMOTE_ADDR');
        }
 if (!empty($QUERY_STRING)){
 $referer = $baseurl . "?" . $QUERY_STRING;
 } else { 
 $referer = $_SERVER["HTTP_REFERER"];
 }
 $subject = "Evaria Mail Client";
 $body ="\nSent from: $referer";
 $body.="\n------------------------------------------------------------\n\n";
 $body.="From: $Email\n";
 $body.="Name: $Name\n";
 $body.="Site: $Website\n\n";
 $body.="Message:\n$Message\n\n";
 $body.="Received: $sentat GMT\n";
 $body.="Host (IP): $host ($ip)"; 
 $body.="\n------------------------------------------------------------\n\n";
 $body.="File Upload: $uploaded\n\n";
 $body.="$get_files\n";
 $body.="$No_upload";
 $body.="\n------------------------------------------------------------\n\n";
 $body.="$author";
  
 if($online_isp == "1")
 {
	if(!email("feedback",$adminmail,$subject,$body))
	{
	print "An error occurred during mail delivery <br>";
	}
 }
 elseif ($online_isp == "2")
 {
    include "mail.inc";
    if(!email($adminmail,$subject,$body))
	{
    print "An error occurred during mail delivery <br>";
	}
 }
 else{
	if(!mail($adminmail,$subject,$body,"From: $Name<$Email>\r\nReply-To: $Email\r\nReturn-Path: $adminmail"))
	{
	print "An error occurred during mail delivery <br>";
	}
 }

//-------------------- Create success message ---
?>

<br><br>
<TABLE align="center" width="<? echo $table_width; ?>" border="0" cellspacing="0" cellpadding="0">
  <TR>
    <TD class="m_head" align="left">Thank you <? echo $Name; ?>!</TD>
  </TR>
  <TR>
	<TD class="m_text"><br>Your message has been successfully sent to the webmaster<br>
	at <?=$sitename?> and should receive an answer shortly.<br><br>
    <? echo $status; ?>
	</TD>
  </TR>
</TABLE>

<?
}
else
{
//-------------------- Create error message ---
?>

<br><br>
<TABLE align="center" width="<? echo $table_width; ?>" border="0" cellspacing="0" cellpadding="0">
  <TR>
    <TD class="m_head" align="left">Houston we have a problem...</TD>
  </TR>
  <TR>
	<TD class="m_text"><br>
	<ul type="square">
	<? 
	echo $No_mail;
	echo $Fake_mail;
	?>
	</ul><br>
	<?
	echo $No_upload;
	?>
	<br><br>
	Your message could not be processed properly.<br> 
	Please note the error messages above and try again.
    </TD>
  </TR>
  <TR>
    <TD align="center">
	<br><br><br><br><br><br>
	<input class="email_btn" type="button" value=" Return & Fix " onclick="javascript:history.go(-1);">
    </TD>
  </TR>
</TABLE>

<?
//-------------------- Building the Email form ---
} 
}
else
{
  $num = 0;
  $html = '';
  while($num < $UploadNum)
  {
	$num++;
	$html.= "  <tr><td align=\"left\"><b><img src=\"img/arrow_g.gif\" width=\"11\" height=\"11\" alt=\"optional\" border=\"0\" align=\"absmiddle\"> Attach File #$num:</b><br><input class=\"email_input\" size=\"40\" type=\"file\" name=\"fileup[]\" value=\"\"></td></tr>\n";
  }
?>

<TABLE align="center" width="<? echo $table_width; ?>" border="0" cellspacing="0" cellpadding="0">
  <TR>
    <TD class="m_head" align="left">Contact us...</TD>
  </TR>
  <TR>
	<TD class="m_text"><br>This is a live demo, but please try it out anyway...</TD>
  </TR>
</TABLE>
<br>
<form action="?view=contact" method="post" enctype="multipart/form-data">
<TABLE class="email_table" align="center" width="90%" cellspacing="10" cellpadding="0" border="0">
  <TR>
    <TD align="left"><b><img src="img/arrow.gif" width="11" height="11" 
	alt="required" border="0" align="absmiddle"> Your Name:</b><br>
    <input class="email_input" type="text" size="40" maxlength="50" name="Name" value=""></TD>
	<TD align="left"><b><img src="img/arrow.gif" width="11" height="11" 
	alt="required" border="0" align="absmiddle"> Your Email:</b><br>
    <input class="email_input" type="text" size="40" maxlength="80" name="Email" value="@"></TD>
  </TR>
  <TR>
    <TD align="left"><b><img src="img/arrow_g.gif" width="11" height="11" 
	alt="optional" border="0" align="absmiddle"> Your Website:</b><br>
    <input class="email_input" type="text" size="40" maxlength="120" name="Website" value="http://"></TD>
	<TD align="left">
<TABLE width="100%" cellspacing="0" cellpadding="0" border="0">
<?php echo $html; ?>
</TABLE>
    </TD>
  </TR>
  <TR>
    <TD align="left" colspan="2"><b><img src="img/arrow.gif" width="11" height="11" 
	alt="required" border="0" align="absmiddle"> Your Message:</b><br>
    <textarea class="email_area" name="Message" cols="52" rows="8"></textarea></TD>
  </TR>
  <TR>
    <TD colspan="2"><img src="img/pix.gif" width="1" height="10" alt="pix" border="0"></TD>
  </TR>
  <TR>
    <TD align="center"><input class="email_btn_clear" type="reset" name="reset" value=" Clear Form "></TD>
	<TD align="center"><input class="email_btn_send" type="submit" name="submit" value=" Send Email "></TD>
  </TR>
</TABLE>
</form>
<br><br>
<TABLE align="center" width="90%" cellspacing="0" cellpadding="0" border="0">
  <TR>
    <TD class="m_text" align="left"><b>When including file(s), the following restrictions apply:</b><br><br>
	File ext.:<?php
	if (($extensions == "") or ($extensions == " ") or ($ext_count == "0") or ($ext_count == "") or ($limit_ext != "yes") or ($limit_ext == "")) { 
	echo "all files allowed..."; 
	} else { 
	  $ext_count2 = $ext_count+1;
        for($counter=0; $counter<$ext_count; $counter++) {
            echo " " . $extensions[$counter];
        }
    }
	?><br>
	File size: between <?php
	if (($min_size == "") || ($limit_size != "yes")) {
            $min_size = "other size";
        } else {
            $min_size = round($min_size / 1024,2);
        } 
	if (($max_size == "") || ($limit_size != "yes")) {
            $max_size = "any size";
        } else {
            $max_size = round($max_size / 1024,2);
        }
        
		echo $min_size . " KB & " . $max_size . " KB";
	?><br>
	No spaces or illegal characters (/,*,\...).</TD>
  </TR>	
</TABLE>
	  
<? 
} 
?>
