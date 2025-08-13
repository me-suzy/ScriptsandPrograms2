<?php

function newuser()
{
	global $form_input_registration_user_name, $form_input_registration_user_pass, $form_input_registration_email, $form_input_registration_first, $form_input_registration_last,  $form_input_registration_cust1, $form_input_registration_cust2, $form_input_registration_cust3, $form_input_registration_cust4, $form_input_registration_cust5, $form_input_registration_cust6, $pass, $conn;

	if(check_perm(-1, "user")==1)
		$pend=1;
	elseif(check_perm(-1, "user")==2 || check_perm(-1, "user")==3 ) 
		$pend=0;
	else
		return 0;

	$pass=$form_input_registration_user_pass;

	$form_input_registration_user_name=inl_escape($form_input_registration_user_name);
	$form_input_registration_user_pass=md5($form_input_registration_user_pass);
	$form_input_registration_email=inl_escape($form_input_registration_email);
	$form_input_registration_first=inl_escape($form_input_registration_first);
	$form_input_registration_last=inl_escape($form_input_registration_last);
	$form_input_registration_cust1=inl_escape($form_input_registration_cust1);
	$form_input_registration_cust2=inl_escape($form_input_registration_cust2);
	$form_input_registration_cust3=inl_escape($form_input_registration_cust3);
	$form_input_registration_cust4=inl_escape($form_input_registration_cust4);
	$form_input_registration_cust5=inl_escape($form_input_registration_cust5);
	$form_input_registration_cust6=inl_escape($form_input_registration_cust6);
	if(strlen($form_input_registration_cust1)>0 || strlen($form_input_registration_cust2)>0 || strlen($form_input_registration_cust3)>0 || strlen($form_input_registration_cust4)>0 || strlen($form_input_registration_cust5)>0 || strlen($form_input_registration_cust5)>0){
		
		$query="insert into inl_custom (cust1, cust2, cust3, cust4, cust5, cust6) values ('$form_input_registration_cust1', '$form_input_registration_cust2', '$form_input_registration_cust3', '$form_input_registration_cust4', '$form_input_registration_cust5', '$form_input_registration_cust6')";
		$conn->Execute($query);
		$id=$conn->Insert_ID("inl_custom","cust_id");
	}
	else
		$id=0;
	$user_date=time();
	
	$query="insert into inl_users (user_name, user_pass, first, last, email, user_perm, user_date, user_status, user_pend, user_cust) values ('$form_input_registration_user_name', '$form_input_registration_user_pass', '$form_input_registration_first', '$form_input_registration_last', '$form_input_registration_email', '3', '$user_date', '1', '$pend', $id)";
	$conn->Execute($query);
	$id=$conn->Insert_ID("inl_users","user_id");
	$result=1;

	global $email_perm, $subject, $from, $reply;
	$query="select * from inl_users where user_id=$id";
	$rs = &$conn->Execute($query);
	global $user_data;
	if ($rs && !$rs->EOF)
		$user_data=$rs->fields;
	include("includes/admin_email_lib.php");
	if($email_perm[0]==1){
		$body=email_parse("mail_admin_new_user");
		if($u=get_admin_emails())
			@mail($u, $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
	}
	if($email_perm[5]==1){
		if(check_perm(-1, "user")==3)
		{
			$body=email_parse("mail_user_registration_confirmation");
			if($user_data["email"])
				@mail($user_data["email"], $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
		}
		else
		{
			$body=email_parse("mail_user_new_user");
			if($user_data["email"])
				@mail($user_data["email"], $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
		}
	}
	return $result;
}
function vote($id)
{	global $REMOTE_ADDR, $votelink, $form_rate_radio_vote, $rating_expiration, $conn; 
	$ip=$REMOTE_ADDR;
	$voted=0;
	
	//clear old stamps
	$expired=time()-86400*$rating_expiration;
	$query="delete from inl_votes where stamp<$expired and rev=0";
	$conn->Execute($query);

	if(!isset($votelink[$id]))
	{	$query="Select stamp from inl_votes where vote_ip='$ip' and vote_link='$id' and rev=0";
		$rs = &$conn->Execute($query);
		if ($rs && !$rs->EOF)
		{	if($rs->fields[0]>0)
				$voted=1;
		}
	}
	else
		$voted=1;
	
	if($voted==0)
	{	$rs = &$conn->Execute("select link_rating, link_votes from inl_links where link_id=$id");
		if ($rs && !$rs->EOF)
		{	$rating=$rs->fields[0]; 
			$votes=$rs->fields[1];		
			$newrating=($rating*$votes+$form_rate_radio_vote)/($votes+1);
			$query="update inl_links set link_votes=link_votes+1, link_rating='$newrating' where link_id=$id";
			if ($conn->Execute($query) == true) 
			{	$lifetime=time()+86400*$rating_expiration;
				setcookie("votelink[$id]","1",$lifetime);
				$now=time();
				$query="insert into inl_votes (stamp, vote_ip, vote_link, rev) values ('$now', '$ip', '$id', 0)";
				$conn->Execute($query);
			}
		}
	}

	return $voted;
}

function get_users_dropdown($user){
	global $conn;
	$query="select user_name, user_perm, user_id from inl_users order by user_perm, user_name";
	$rs = &$conn->Execute($query);
	$optio="";
	while ($rs && !$rs->EOF)
	{
		$optio=$optio."<option value=".$rs->fields[2];
		if($user==$rs->fields[2]){$optio=$optio."  selected";}
		$optio=$optio.">".$rs->fields[0]."</option>\n";
		$rs->MoveNext();
	}
	return $optio;
}
function get_user(){
	global $form_input_registration_user_name, $form_input_registration_user_pass, $form_input_registration_email, $form_input_registration_first, $form_input_registration_last,  $form_input_registration_cust1, $form_input_registration_cust2, $form_input_registration_cust3, $form_input_registration_cust4, $form_input_registration_cust5, $form_input_registration_cust6,
		$ses, $attach, $conn;
	
	$query="Select * from inl_users where user_id=".$ses["user_id"];
	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF)
	{
		$form_input_registration_user_name=$rs->fields[1];
		$form_input_registration_first=$rs->fields[3];
		$form_input_registration_last=$rs->fields[4];
		$form_input_registration_email=$rs->fields[5];
		$form_input_registration_user_pass=$rs->fields[2];
		$user_cust=$rs->fields[8];
		if($user_cust!=0){
			$query="Select * from inl_custom where cust_id=$user_cust";
			$rs = &$conn->Execute($query);
			if ($rs && !$rs->EOF)
			{
				$form_input_registration_cust1=$rs->fields[1];
				$form_input_registration_cust2=$rs->fields[2];
				$form_input_registration_cust3=$rs->fields[3];
				$form_input_registration_cust4=$rs->fields[4];
				$form_input_registration_cust5=$rs->fields[5];
				$form_input_registration_cust6=$rs->fields[6];
			}
		}
	}else
		inl_header("index.php?t=login&attach=$attach");

}
function updateuser()
{
	global $form_input_registration_user_pass, $form_input_registration_email, $form_input_registration_first, $form_input_registration_last,  $form_input_registration_cust1, $form_input_registration_cust2, $form_input_registration_cust3, $form_input_registration_cust4, $form_input_registration_cust5, $form_input_registration_cust6, $ses, $attach, $keeppass, $conn;
	
	$form_input_registration_user_pass=md5($form_input_registration_user_pass);
	$form_input_registration_email=inl_escape($form_input_registration_email);
	$form_input_registration_first=inl_escape($form_input_registration_first);
	$form_input_registration_last=inl_escape($form_input_registration_last);
	$form_input_registration_cust1=inl_escape($form_input_registration_cust1);
	$form_input_registration_cust2=inl_escape($form_input_registration_cust2);
	$form_input_registration_cust3=inl_escape($form_input_registration_cust3);
	$form_input_registration_cust4=inl_escape($form_input_registration_cust4);
	$form_input_registration_cust5=inl_escape($form_input_registration_cust5);
	$form_input_registration_cust6=inl_escape($form_input_registration_cust6);

	if ($keeppass == 1)
		$query="update inl_users set first='$form_input_registration_first', last='$form_input_registration_last', email='$form_input_registration_email' where user_id=".$ses["user_id"];
	else
		$query="update inl_users set  user_pass='$form_input_registration_user_pass', first='$form_input_registration_first', last='$form_input_registration_last', email='$form_input_registration_email' where user_id=".$ses["user_id"];
	
	if ($conn->Execute($query) == false) {return ;} 
	$query="Select user_cust from inl_users where user_id=".$ses["user_id"];

	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF)
	{
		if($rs->fields[0]!=0){		
			$query="update inl_custom set cust1='$form_input_registration_cust1', cust2='$form_input_registration_cust2', cust3='$form_input_registration_cust3', cust4='$form_input_registration_cust4', cust5='$form_input_registration_cust5', cust6='$form_input_registration_cust6' where cust_id=".$rs->fields[0];

			if( $conn->Execute($query))
				return 1;
			else
				return 0;
		}else
		{	$query="insert into inl_custom (cust1, cust2, cust3, cust4, cust5, cust6) values ('$form_input_registration_cust1', '$form_input_registration_cust2', '$form_input_registration_cust3', '$form_input_registration_cust4', '$form_input_registration_cust5', '$form_input_registration_cust6')";
			$conn->Execute($query);
			$user_cust=$conn->Insert_ID("inl_custom","cust_id");

			$query="update inl_users set user_cust='$user_cust' where user_id='".$ses["user_id"]."'";
			if( $conn->Execute($query))
				return 1;
			else
				return 0;
		}
	}
	else
		return 0;
	
}

function random_password()
{
	mt_srand((double)microtime()*1000000);
	$charset="qwertyuiopasdfghjklzxcvbnm1234567890";

	for ( $i = 0; $i < 6; $i++ )
		$ret.=($charset[mt_rand(0,35)]);

	return $ret;
}
function get_user_id($user_name)
{
	global $conn;
	$query="SELECT user_id from inl_users where user_name='".addslashes($user_name)."'";
	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF)
	{	if($rs->fields[0]>0)
			return $rs->fields[0];
		else
			return 0;
	}
	else
		return 0;
}
function get_user_name($user_id)
{
	global $conn;
	$query="SELECT user_name from inl_users where user_id='$user_id'";
	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF)
		return $rs->fields[0];
	else
		return "";
}
?>