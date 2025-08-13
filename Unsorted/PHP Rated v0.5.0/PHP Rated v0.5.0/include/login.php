<?

/*
 * $Id: login.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

if(isset($login)){
	$login_sql = "select * from $tb_users where username = '$UN' and password = password('$PW')";
	$login_query = sql_query($login_sql);
	if(sql_num_rows($login_query) == 1){
		if($login_array = sql_fetch_array($login_query)){
			$username = $login_array["username"];
			session_register("username");
			$userid = $login_array["id"];
			session_register("userid");
		}
	}
	?><script language="Javascript">window.location='<?=$base_url?>/index.php?<?=$sn?>=<?=$sid?>'</script><?
}
if(isset($logout)){
	session_destroy();
	?><script language="Javascript">window.location='<?=$base_url?>/index.php'</script><?
}

if(session_is_registered("userid")){

$content = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td width="100%" class="regular" align="right">Welcome $username<br /><a href="$base_url/index.php?$sn=$sid&amp;logout=1" target="_top">Logout</a></td>
</tr>
</table>
EOF;

} else {

$content = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><form method="post" action="$base_url/index.php?$sn=$sid">
<td width="100%" bgcolor="white" class="regular" align="right">
username:
<br />
<input type="text" name="UN" size="12" value="" />
<br />
password:
<br />
<input type="password" name="PW" size="12" value="" />
<br />
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td valign="bottom">&nbsp;<a href="$base_url/index.php?$sn=$sid&amp;show=lost" target="_top" class="small">Lost Password?</a>
</td>
<td align="right"><input class="button" type="submit" name="login" value="Go ->" /></td>
</tr>
</table>
</td>
</form></tr>
</table>
EOF;
}

$title = "Log";

if(session_is_registered("userid")) $title .= "ged In";
else $title .= "in";

$final_output .= table($title, $content);

/*
 * $Id: login.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>