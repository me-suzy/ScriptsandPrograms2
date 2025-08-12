<?
// preliminary session tasks
unset ($logOn);

session_start(); 

session_register ("logOn");
	
include_once "sys/Conf.inc";


if (isset ($FormGroup) && $FormGroup == 'Login' && 
	isset ($UserName) && isset ($Password) && !isset ($logOn))
{
	$db = new DB ();
	$db->execute ("select ID, FirstName, LastName from User where UserName = '$UserName' and Password = '$Password'");
	
	if ($db->getNumRows () > 0)
	{
		$logOn = true;
		$uid    = $db->recordSet [0][0]*1;

		$fname = htmlspecialchars (stripslashes ($db->recordSet [0][1]));
		$lname = htmlspecialchars (stripslashes ($db->recordSet [0][2]));
		
		session_register ("uid");
		session_register ("fname");
		session_register ("lname");
	}
	else
	{
		$logOn = false;
		session_destroy ();
	}
}
else if (isset ($FormGroup) && $FormGroup == 'Logout' && isset ($logOn))
{
	$logOn = false;	
	session_destroy ();
}
else if (!isset ($logOn))
{
	$logOn = false;
	session_destroy ();
}
?>