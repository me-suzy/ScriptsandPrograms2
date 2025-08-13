<?php
/*	In-link Sessions Library Version 2.1.4
	Session Functions
	Included in config.php, required for all scripts

	Last updated 10/03/01
*/
function load_session($sid)
{	global $conn, $ses_expiration, $ses;

	clear_old_sessions();

	$rs =&$conn->Execute("SELECT * FROM inl_sessions WHERE ses_id=$sid");	
	if($rs && !$rs->EOF)
	{	//echo "SELECT * FROM inl_sessions WHERE ses_id=$sid <br>";
		if(date("U")>($rs->fields["ses_time"]+$ses_expiration)) //ses expired
			return false;
		//also IP validation available here
		
		$ses=""; //clear old vars
		$ses["user_id"]=$rs->fields["user_id"];
		$ses["user_perm"]=$rs->fields["user_perm"];
		$ses["num_res"]=$rs->fields["num_res"];
		$ses["link_order"]=$rs->fields["link_order"];
		$ses["link_sort"]=$rs->fields["link_sort"];
		$ses["cat_order"]=$rs->fields["cat_order"];
		$ses["cat_sort"]=$rs->fields["cat_sort"];
		$ses["lang"]=$rs->fields["lang"];
		$ses["theme"]=$rs->fields["theme"];
		$ses["destin"]=$rs->fields["destin"];
		
		//echo "In load $sid session ".$rs->fields["user_perm"]." <br>";
	}
	else
	{
		$rs = insert_with_id("INSERT INTO inl_sessions (ses_id,ses_time) VALUES ($sid,".date("U").")","inl_sessions");
		return true;//no session retrieved - actvivating session with the same SID
	}
}

function save_session($sid) // CyKuH [WTN]

{	global $conn, $ses_expiration, $ses, $SERVER_NAME, $keya, $keyb, $session_cookie,$admin;
	$res=check_key();
	if($session_cookie)
		@setcookie("sid",$sid,time()+$ses_expiration,"/","",0);
	$query="UPDATE inl_sessions SET ";
	if($ses["user_id"])
		$query.= "user_id=".$ses["user_id"].", ";
	else
		$query.= "user_id=0, ";
	if($ses["user_perm"])
		$query.= "user_perm=".$ses["user_perm"].", ";
	else
		$query.= "user_perm=0, ";
	$query.= "ses_time=".date("U").", "; //update time
	$query.= "num_res='".$ses["num_res"]."', ";
	$query.= "link_order='".$ses["link_order"]."', ";
	$query.= "link_sort='".$ses["link_sort"]."', ";
	$query.= "cat_order='".$ses["cat_order"]."', ";
	$query.= "cat_sort='".$ses["cat_sort"]."', ";
	$query.= "lang='".$ses["lang"]."', ";
	$query.= "destin='".$ses["destin"]."', ";
	$query.= "theme='".$ses["theme"]."'";
	$query.= " WHERE ses_id=$sid";

	$rs =&$conn->Execute($query);	
	if($rs)
		return true; // CyKuH [WTN]
	else
		return false; //no session 
}

function check_key()
{	global $reg_name, $keya, $keyb, $SERVER_NAME;
	
	if(strlen($reg_name)<1 || strlen($keya)!=32 || strlen($keyb)!=32)
		return 2;

	if(strlen($SERVER_NAME)<1)
		return 6;

	if($SERVER_NAME!=$reg_name)
		return 4;

	$nh=0;
	settype($nh,"integer");
	for($i=0;$i<strlen($SERVER_NAME);$i++)
		$nh+=ord($SERVER_NAME[$i]);
	$key=md5($SERVER_NAME.$iph.$keya);

	if(strlen($key)!=32)
		return 3;

	$key1=substr($key,0,8);
	$key2=substr($key,8,8);
	$key3=substr($key,16,8);
	$key4=substr($key,24,8);
	
	if($key3.$key2.$key4.$key1 != $keyb)
		return 5;

	return 1;
}

function refresh_session($sid)
{	global $conn, $ses_expiration, $session_cookie;
	
	if($session_cookie)
		@setcookie("sid",$sid,time()+$ses_expiration,"/","",0);
	$query="UPDATE inl_sessions SET ";
	$query.= "ses_time=".date("U"); //update time
	$query.= " WHERE ses_id=$sid";

	$rs =&$conn->Execute($query);	
	if($rs)
		return true;
	else
		return false; //no session 
}

function init_session()
{	global $conn, $ses_expiration, $session_cookie;

	clear_old_sessions();

	mt_srand(100000000*(double)microtime());

	$count=0;
	while($count<100)
	{	$sid=mt_rand(100000000,999999999); //9 digit number
		$rs=insert_with_id("INSERT INTO inl_sessions (ses_id,ses_time) VALUES ($sid,".date("U").")","inl_sessions");	
		if($rs)
			break;
		$count++;
	}
	if($count==100)
		return 0;
	else
	{	if($session_cookie)
			@setcookie("sid",$sid,time()+$ses_expiration,"/","",0);
		return $sid;
	}
}

function clear_old_sessions()
{	global $conn, $ses_expiration, $extended_search;

	$exp_time=date("U")-$ses_expiration;
	$rs = &$conn->Execute("SELECT ses_id FROM inl_sessions WHERE ses_time<$exp_time");
	if ($rs)
		while ($rs && !$rs->EOF)
		{
			$t = $rs->fields[0];
			$drop = &$conn->Execute("DROP TABLE IF EXISTS inl_$t");
			$rs->MoveNext();
		}
	$rs = &$conn->Execute("DELETE FROM inl_sessions WHERE ses_time < $exp_time");	
}
?>