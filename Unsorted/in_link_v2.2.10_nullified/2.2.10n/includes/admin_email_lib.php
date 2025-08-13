<?php
//Admin E-mail Functions

if(!isset($admin_email_lib_included))
{
	$admin_email_lib_included = true;

//Send E-mail
function send_email($emailim,$emailid){
	global $conn, $la_mailing_list, $la_title_pending_users, $la_admin, $la_all_users, $sid, $session_get, $user_data, $mass_email;
	$query="Select email_body, email_subject, email_from, email_reply, email_to from inl_email where email_id=$emailid";
	$mass_email=true;
	if($sid && $session_get)
		$att_sid="sid=$sid&";

	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF)
	{	
		$e_subject=$rs->fields[1];
		$e_body=$rs->fields[0];
		$e_body=ereg_replace("&lt;","<",$e_body);
		$e_body=ereg_replace("&gt;",">",$e_body);
		$e_body=ereg_replace("&quot;","\"",$e_body);
		$from=$rs->fields[2];
		$replyto=$rs->fields[3];
		$to=$rs->fields[4];
	}

	if($to==$la_mailing_list){$w=" where user_perm=4 ";}
	elseif($to==$la_title_pending_users){$w=" where user_pend=1 ";}
	elseif($to==$la_admin){$w=" where user_perm<3 ";}
	elseif($to==$la_all_users){$w="";}
	$query="Select user_id, user_pend, user_perm from inl_users $w";

	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF)
		$tes=$rs->RecordCount();
	else
		$tes=0;

	$emaillim1=$emailim+10;
	

	#Modified Below to support multiple databases.
	$query="Select * from inl_users LEFT JOIN inl_custom on user_cust=cust_id $w";
	$rs = &$conn->SelectLimit($query, 10, $emailim);
	#Modified Above to support multiple databases.
	while ($rs && !$rs->EOF)
	{	
		$user_data=$rs->fields;
		if($user_data["email"])	
		{
			$e_body_temp=email_parse($e_body);
			@mail($user_data["email"],$e_subject,$e_body_temp,"From:$from<$replyto>\r\nReply-to:$replyto");
		}
		$r++;				
		$rs->MoveNext();
	}	
	
	if($emaillim1>=$tes){
		$query="Delete from inl_email where email_id=".$emailid;
		$conn->Execute("$query");
		$email_done="<p align='center'><font color='#A0A0E0'><b>100%</b></font> 
		  <table border='1' cellspacing='0' width='100%' bordercolor='#C0C0C0'>
       	 <tr>
         		 <td width='100%' bgcolor='#A0A0E0'>&nbsp;</td>
        </tr>
      </table><input type='button' value='Done' name='done' class='button' onClick=location.href=\"users.php?$att_sid\">";	
	
	return $email_done;
	}
	else{
		if($tes!=0){
			$PERCENT=100*$emaillim1/$tes;
			$PERCENT=number_format($PERCENT,1);
		}else{$PERCENT="0";} 
		$t=100-$PERCENT;
		$p=$PERCENT;
		$t=number_format($t,0);
		$p=number_format($p,0);
		$email_processing= "<p align=\"center\"><font color=\"#A0A0E0\"><b>".$PERCENT."%</b></font> 
  			<table border=\"1\" cellspacing=\"0\" width=\"100%\" bordercolor=\"#C0C0C0\">
      	  <tr>
      	    <td width=\"".$PERCENT."%\" bgcolor=\"#A0A0E0\">&nbsp;</td>
      	    <td width=\"".$t."%\">&nbsp;</td>
      	  </tr>
      	</table><input type='button' value='Cancel' name='cancel' class='button' onClick=location.href=\"users_e-mail.php?$att_sid"."back=Back\">";
		global $email_redirect;
		$email_redirect="
			<script language=\"javascript\">
			<!-- 
		
			location.href=\"users_e-mail.php?$att_sid"."submit=Send&e_lim=$emaillim1&e_id=$emailid\"
		
			//-->
			</script>";
		return $email_processing;
	}
}

//Create E-mail
function create_email($e_body,$e_subject, $email_from, $email_reply, $email_to)
{	global $conn;
	$e_body=inl_escape($e_body);
	$e_subject=inl_escape($e_subject);
	
	$query="Insert into inl_email (email_subject, email_body, email_from, email_reply, email_to) values ('$e_subject','$e_body','$email_from', '$email_reply', '$email_to')";
	$conn->Execute("$query");
	return $conn->Insert_ID("inl_email","email_id");
}

//Delete E-mail
function delemail($emid)
{	global $conn;
	$query="Delete from inl_email where email_id=".$emid;
	$conn->Execute("$query");
}


################################################################
//email functions for sending email

function getemail_body($file) 
{	global $filedir, $theme, $admin, $language, $fileh;
	if($admin==1)
		$fileh=$filedir."languages/$language/$file.tpl";
	else
		$fileh=$filedir ."languages/$language/$file.tpl";
	if(file_exists($fileh))
	{	
		$fd = fopen($fileh, "r");
		$ret=fread($fd, filesize($fileh));
		fclose($fd);
		return $ret;
	}
}


function email_parse($t_name)
{	global $conn, $mass_email;
	if(!$mass_email)
		$t=getemail_body($t_name); 
	else
		$t=$t_name;
	$t_len=strlen($t);	
	$o=""; 
	for($i=0;$i<$t_len;$i++)
	{	
		$tagOpen=strpos("~".$t,"<%",$i);		
		if (!$tagOpen) 
		{
			$o.=substr($t,$i);
			break;
		}
		else
		{	
			$tagOpen--;
			$tagClose=strpos($t,"%>",$tagOpen);
			$tag=substr($t,$tagOpen+2,$tagClose-$tagOpen-2);
			$o.=substr($t,$i,$tagOpen-$i);
			if(ereg("subject:",$tag))
			{
				global $subject;
				$subject=substr($tag,8);
			}
			elseif(ereg("from:",$tag))
			{
				global $from;
				$from=substr($tag,5);
				if($from=="root"){
					$rs = &$conn->Execute("Select first, last from inl_users where user_perm=1");
					if($rs && !$rs->EOF)
						$from=$rs->fields[0]." ".$rs->fields[1];
				}
			}
			elseif(ereg("reply:",$tag))
			{
				global $reply;
				$reply=substr($tag,6);
				if($reply=="root")
				{
					$rs = &$conn->Execute("Select email from inl_users where user_perm=1");
					if($rs && !$rs->EOF)
						$reply=$rs->fields[0];
				}
			}
			else
			{
				$o.=email_process($tag);
			}
			$i=$tagClose+1;
		}
	}

	return $o;
}

//tag processing
function email_process($tag_name)
{	global $user_data, $email_link, $email_cat, $old_link, $admin;
	switch($tag_name)
	{	
		case "site_url":
			global $filepath, $server;
			$ret="$server"."$filepath";
			break;
		case "site_name":
			global $sitename;
			$ret="$sitename";
			break;
		case "USER_NAME":
			$ret="".$user_data["user_name"];
			break;
		case "FULL_NAME":
			$ret="".$user_data["first"]." ".$user_data["last"];
			break;
		case "EMAIL":
			$ret="".$user_data["email"];
			break;
		case "FIRST_NAME":
			$ret=$user_data["first"];
			break;
		case "LAST_NAME":
			$ret=$user_data["last"];
			break;
		case "USER_CUST1":
			$ret="".$user_data["cust1"];
			break;
		case "USER_CUST2":
			$ret="".$user_data["cust2"];
			break;
		case "USER_CUST3":
			$ret="".$user_data["cust3"];
			break;
		case "USER_CUST4":
			$ret="".$user_data["cust4"];
			break;
		case "USER_CUST5":
			$ret="".$user_data["cust5"];
			break;
		case "USER_CUST6":
			$ret="".$user_data["cust6"];
			break;
		case "DATE_ADDED":
			global $datefmt;
			$ret="".date($datefmt,$user_data["user_date"]);
			break;
		case "LINK_TITLE":
			$ret="".$email_link["link_name"];
			break;
		case "LINK_URL":
			$ret="".$email_link["link_url"];
			break;
		case "LINK_DESCRIPTION":
			$ret="".$email_link["link_desc"];
			break;
		case "LINK_DATE_ADDED":
			global $datefmt;
			$ret="".date($datefmt,$email_link["link_date"]);
			break;
		case "CAT_TITLE":
			$ret="".$email_cat["cat_name"];
			break;
		case "CAT_DESCRIPTION":
			$ret="".$email_cat["cat_desc"];
			break;
		case "CAT_DATE_ADDED":
			global $datefmt;
			$ret="".date($datefmt,$email_cat["cat_date"]);
			break;
		case "DATE":
			global $datefmt;
			$ret="".date($datefmt,time());
			break;
		case "REVIEW_TEXT":
			global $form_input_add_review_text;
			$ret="".$form_input_add_review_text;
			break;
		case "OLD_TITLE":
			$ret="".$old_link["link_name"];
			break;
		case "OLD_URL":
			$ret="".$old_link["link_url"];
			break;
		case "OLD_DESCRIPTION":
			$ret="".$old_link["link_desc"];
			break;
		case "USER_PASS":
			global $pass;
			$ret="".$pass;
			break;
		case "LINK_PATH":
			global $conn;
			if($admin==1)
				include_once("../includes/links_lib.php");
			else
				include_once("includes/links_lib.php");
			
			$query="Select cat_id from inl_lc where link_id=".$email_link["link_id"];
			$rs = &$conn->Execute($query);
			while ($rs && !$rs->EOF)
			{
				$ret.=linkpath($rs->fields[0])."\n";
				$rs->MoveNext();
			}
			break;

		case "LINK_ID":
			$ret="".$email_link["link_id"];
			break;
	}
	return $ret;
}
function get_admin_emails()
{
	global $conn;
	$query="Select email from inl_users where user_perm<3";
	$rs = &$conn->Execute($query);
	$emails="";
	while(!$rs->EOF)
	{
		$emails.=$rs->fields[0].",";
		$rs->MoveNext();
	}
	$emails=ereg_replace(",$","",$emails);
	return $emails;
} // if(!isset($admin_email_lib_included))
}
?>