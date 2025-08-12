<?
class Session
{
	function Session ()
	{
		global $UserName,
			   $UserPswd,
			   $uid,
			   $ActionGroup;
			   
		// preliminary session tasks
		$uid = 0;
		$fname = $lname = "";
		
		session_start(); 
		
		include_once "sys/Conf.inc";

		if (isset ($ActionGroup) && $ActionGroup == 'Login' && isset ($UserName) && isset ($UserPswd))
		{
			$db = new DB ();
			$db->execute ("select ID, FirstName, Name from User where UserName = '$UserName' and Password = '$UserPswd'");
			
			if ($db->getNumRows () > 0)
			{
				$logOn = true;
				$uid    = $db->recordSet [0][0]*1;

				$fname = htmlspecialchars (stripslashes ($db->recordSet [0][1]));
				$lname = htmlspecialchars (stripslashes ($db->recordSet [0][2]));
				session_register ("uid");
				session_register ("fname");
				session_register ("lname");
				$ttt = 666;
				session_register ("ttt");
			}
			else
			{
				session_destroy ();
			}
		}
		else if (isset ($ActionGroup) && $ActionGroup == 'Logout' && isset ($uid))
		{
			$uid = 0;	
			$fname = $lname = "";
			session_destroy ();
		}
		else if (!isset ($uid))
		{
			$uid = 0;
			$fname = $lname = "";
			session_destroy ();
		}
		
	}
}
?>