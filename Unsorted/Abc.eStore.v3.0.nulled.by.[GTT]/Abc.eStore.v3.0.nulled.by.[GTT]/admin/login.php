<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

include ("config.php");
include ("settings.inc.php");
include_once ("loginheader.inc.php");

$user = $_POST['user'];
$pass = $_POST['pass'];

if( $user && $pass )
{
	//
	// Demo login
	
	if ( $allowdemo == 1 ) {
		
		if ($user == $demologin && $pass == $demopass)	
			$_SESSION["demo"] = "1";
		
	}
	
	
	//
	// If the user has just tried to log in
	
	$passwd = md5($pass);
	$query = "select * from ".$prefix."store_config where user='$user' and pass=('$passwd')";
	$result = mysql_query($query);
	
	if( mysql_num_rows( $result ) > 0 )
	{
		// if they are in the database register the user for the session
		$_SESSION["admin"] = $user;
	}
}

if( isset( $_SESSION["admin"] ) || isset( $_SESSION["demo"] ) )
{
//
// Redirect user to request page on successful authentication
echo "<Script language=\"javascript\">window.location=\"index.php\"</script>";
}
else
{
	//
  	// If not display error messages
  	
    if( $_SERVER["REQUEST_METHOD"] == "POST" )
    {
		// if they've tried and failed to log in
		$error = $lng[253]."<br>";
    }
    else 
    {
		// they have not tried to log in yet or have logged out
		$not_logged_in = $lng[252]."<br>";
    }

    // Provide form for login 

echo "

<table width=\"100%\" height=\"99%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td align=\"center\">

<form method=\"post\" action=\"login.php\">

<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\" width=\"400\" align=\"center\">
<tr bgcolor=\"#cccccc\">
<td width=\"400\" colspan=\"2\"><img src=\"images/admin_logo_250.gif\" width=\"250\" height=\"50\" border=\"0\" hspace=\"0\" vspace=\"0\"></td>
</tr>

<tr bgcolor=\"#f6f6f6\">
<td colspan=\"2\"><b>".$lng[254]."</b> / <b>$not_logged_in$error</b></td>
</tr>

<tr bgcolor=\"#f6f6f6\">
<td width=\"200\">".$lng[255].":</td>
<td width=\"200\"> <input type=\"text\" class=\"textbox\" name=\"user\"></td>
</tr>

<tr bgcolor=\"#f6f6f6\"> 
<td width=\"200\">".$lng[256].":</td>
<td width=\"200\"> <input type=\"password\" class=\"textbox\" name=\"pass\"></td>
</tr>

<tr bgcolor=\"#f6f6f6\"> 
<td width=\"200\">&nbsp;</td>
<td width=\"200\"><a href=\"forgot_pass.php\" target=\"_self\">".$lng[257]."</a></td>
</tr>

<tr bgcolor=\"#f6f6f6\"> 
<td width=\"200\">&nbsp;</td>
<td width=\"200\">
<input type=\"submit\" class=\"submit\" value=\"".$lng[258]."\" name=\"submit\">
</td>
</tr>

</table>

</form>

</td></tr></table>
";
}

include_once ("loginfooter.inc.php");

?>