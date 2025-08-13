<?php
	include ("../vars.inc.php");
	
	$q=new Cdb;
	
	if (!isset($action)) $action="settings";
	
	
	switch ($action)
	{
		case "settings":
			FFileRead("template.settings.htm",$content);
			$title=$sitename." - Settings";
			$content=str_replace("{paypal_email}",get_setting("pay_pal_email"),$content);
			$content=str_replace("{lost_password_email}",get_setting("forgot_pass_email"),$content);
			$content=str_replace("{lost_password_email_subject}",get_setting("forgot_pass_subject"),$content);
			$content=str_replace("{welcome_email_subject}",get_setting("welcome_subject"),$content);
			$content=str_replace("{welcome_email}",get_setting("welcome_email"),$content);
			$content=str_replace("{notification_email_subject}",get_setting("notification_subject"),$content);
			$content=str_replace("{notification_email}",get_setting("notification_body"),$content);
			break;
		case "save_settings":
			save_setting("pay_pal_email",$paypal_email);
			save_setting("forgot_pass_email",$lost_password_email);
			save_setting("forgot_pass_subject",$lost_password_email_subject);
			save_setting("welcome_subject",$welcome_email_subject);
			save_setting("welcome_email",$welcome_email);
			save_setting("notification_subject",$notification_email_subject);
			save_setting("notification_body",$notification_email);
			header("Location:index.php");
			break;
		case "members":
			FFileRead("template.members.htm",$content);
			$title=$sitename." - Members";
			$query="select * from members";
			$q->query($query);
			while($q->next_record())
			{
				$rows.="<tr>
							<td>".$q->f("id")."</td>
							<td>".$q->f("email")."</td>
							<td><input type=checkbox name=check[".$q->f("id")."]></td>
						</tr>";
							
			}
			$content=str_replace("{rows}",$rows,$content);
			break;
		case "delete_members":
			foreach ($check as $x => $value)
			{	
				$query="delete from monitor where member_id='$x'";
				$q->query($query);
				$query="delete from members where id='$x'";
				$q->query($query);
			}
			header("Location:index.php?action=members");
			break;
		case "add_member":
			FFileRead("template.add.member.htm",$content);
			break;
		case "do_add_member":
			$query="insert into members(id, email, password) values(NULL, '$email', '$password')";
			$q->query($query);
			header("Location:index.php?action=members");
			break;

	}

	FFileRead("template.main.htm",$main);
	$main=str_replace("{content}",$content,$main);
	$main=str_replace("{title}",$title,$main);
	$main=str_replace("{sitename}",$sitename,$main);
	$main=str_replace("{webmasteremail}",$webmasteremail,$main);
	echo $main;
?>