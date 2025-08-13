<?php
/*	In-link Functions Library Version 2.1.4
	Miscellaneous Functions
	Included in config.php, required for all scripts

	Last updated 10/03/01
*/

function inl_escape($in)
{
	global $html_enable;
	$out=stripslashes($in);
	if($html_enable=="yes"){;}
	else
	{
		$out=ereg_replace("<","&lt;",$out);
		$out=ereg_replace(">","&gt;",$out);
		$out=ereg_replace("\"","&quot;",$out);
	}
	$out=addslashes($out);

	return $out;
}

function form_escape($in)
{	
	global $html_enable;
	$out=stripslashes($in);
	if($html_enable=="yes"){;}
	else
	{
		$out=ereg_replace("<","&lt;",$out);
		$out=ereg_replace(">","&gt;",$out);
		$out=ereg_replace("\"","&quot;",$out);
	}
	return $out;
}

function search_escape($in)
{
	$out = ereg_replace("_","\_",$in);
	$out = ereg_replace("%","\%",$out);
	return $out;
}

function error($str, $type)
{	switch($type)
	{	case 0:
			echo "	<p align=\"center\">&nbsp;</p>
					<p align=\"center\">&nbsp;</p>
					<p align=\"center\">&nbsp;</p>
					<p align=\"center\">&nbsp;</p><p align=\"center\"><b><font face=\"Arial, Verdana, Helvetica\" color=\"#FF0000\">IN-LINK FATAL ERROR:</font></b></p><br>\n<p align=\"center\"><font size=\"2\" face=\"Arial, Verdana, Helvetica\"><b>$str</b></font></font></p>";
			break;
		default:
			echo "	<p align=\"center\">&nbsp;</p>
					<p align=\"center\">&nbsp;</p>
					<p align=\"center\">&nbsp;</p>
					<p align=\"center\">&nbsp;</p><p align=\"center\"><b><font face=\"Arial, Verdana, Helvetica\" color=\"#FF0000\">IN-LINK ERROR:</font></b></p><br>\n<p align=\"center\"><font size=\"2\" face=\"Arial, Verdana, Helvetica\"><b>$str</b></font></font></p>";
	}
}

function inl_header($destin)
{	global $sid, $session_get, $HTTP_SERVER_VARS;
	if($sid && $session_get)
	{	if(substr($destin,-1)=="?" || substr($destin,-1)=="&") //only ? or &
			$addon="sid=$sid";
		elseif(strpos($destin,"?"))
			$addon="&sid=$sid";
		else
			$addon="?sid=$sid";

	}
	
	//$destin="http://".$HTTP_SERVER_VARS['HTTP_HOST']."/".dirname($HTTP_SERVER_VARS['PHP_SELF'])."/".$destin;
	header("Location: $destin$addon");
	die();
}

/*	Login function
	Takes user name and password (plain text)
	returns		1  if success
				-1 if short user name
				-2 if session error
				-3 if database error
				-4 if wrong login (user w/ such name/pwd not found)
*/
function login($username, $password)
{	global $ses, $conn, $sid, $SERVER_NAME, $keya, $keyb;

	$res=check_key();
//CyKuH [WTN]
	if(strlen($username)<3 || strlen($password)<3)
		return -1;
	$username=inl_escape($username);
	$password=md5($password);

	$query="SELECT * FROM inl_users WHERE user_name='$username' and user_pass='$password' and user_pend!=1 and user_status!=0";
	$rs = &$conn->Execute($query);
	if(!$rs)
		return -3;

	if (!$rs->EOF) 
	{	$sid=init_session();

		if($sid)
		{	$ses["user_id"]=$rs->fields["user_id"];
			$ses["user_perm"]=$rs->fields["user_perm"];
			if(!save_session($sid))
				return -3;
		}
		else
			return -2; 
						
	}
	else
		return -4;

	return 1;
}

function logout()
{	global $sid, $ses;

	if($sid)
	{	$ses["user_id"]=0;
		$ses["user_perm"]=0;
		save_session($sid);
	}
	//else - error: if user is logged in, there should be a session
}

function get_keyb($keya)
{	global $SERVER_NAME;

	if(strlen($SERVER_NAME)<1)
		return -1;
	if(strlen($keya)!=32)
		return -2;

	$nh=0;
	settype($nh,"integer");
	for($i=0;$i<strlen($SERVER_NAME);$i++)
		$nh+=ord($SERVER_NAME[$i]);
	$key=md5($SERVER_NAME.$iph.$keya);

	if(strlen($key)!=32)
		return -3;

	$key1=substr($key,0,8);
	$key2=substr($key,8,8);
	$key3=substr($key,16,8);
	$key4=substr($key,24,8);
	
	return $key3.$key2.$key4.$key1;
}

function check_perm($cat_perm=-1,$resource) //returns 0 if no, 1 if pend or 2 if direct
{	global $ses, $root_link_perm, $suggest_cat_perm, $review_perm, $rate_perm, $user_perm;
	switch($resource)
	{	case "link":
			if($cat_perm==-1) //root, or no info avail
				$cat_perm=$root_link_perm;
			break;
		case "cat":
			$cat_perm=$suggest_cat_perm;
			break;
		case "review":
			$cat_perm=$review_perm;
			break;
		case "rate": //special case for ratings - no pending choice
			if(($ses["user_id"] && $rate_perm<2) || (!$ses["user_id"] && $rate_perm%2==0))
				return 0;
			else
				return 2;
			break;
		case "user":
			return $user_perm;
	}
	if(($ses["user_id"]>0 && $cat_perm<3) || (!$ses["user_id"] && $cat_perm%3==0))
		return 0;
	if(($ses["user_id"]>0 && $cat_perm<6 && $cat_perm>2) || (!$ses["user_id"] && $cat_perm%3==1))
		return 1;
	if(($ses["user_id"]>0 && $cat_perm>5) || (!$ses["user_id"] && $cat_perm%3==2))
		return 2;
}

/*	Wrapper for DB insertion, when AutoIncrement ID needs to be overridden
*/
function insert_with_id($query,$table)
{	global $conn, $sql_type;
	
	if($sql_type=="mssql")
		$rs=&$conn->Execute("SET identity_insert $table ON");	

	$rs=&$conn->Execute($query);	
	return $rs;
}


?>