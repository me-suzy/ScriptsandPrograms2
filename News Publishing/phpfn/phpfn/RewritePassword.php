<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

require_once('Config/Config.php');

$Updated=false;
$Message="";

// Updated details have been submitted?
if (isset($_POST['submit']))
{
	if ($dbuser == trim($_POST['Username']))
	{
		$ok = mysql_query("UPDATE news_users SET Password=MD5('$dbpass'), MustChangePassword=1 WHERE ID='1'");
		if ($ok)
			$Updated = true;
	}
	else
		$Message="Incorrect Credentials";
}
?>

<HTML>
	<BODY>
		<?php
		if ($Updated)
		{
			?>
			<P>Username updated successfully.</P>
			<?php
		}
		else
		{
			?>
			<P><?=$Message?></P><BR>
			<FORM action="<?=$_SERVER['PHPSELF']?>" method="post">
				Username: <INPUT type="text" name="Username" size="20" maxlength="20">
				<INPUT type="submit" name="submit" value="Update">		
			</FORM>
			<?php
		}
		?>
	</BODY>
</HTML>