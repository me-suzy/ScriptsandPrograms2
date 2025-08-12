<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

require('../Config/Config.php');
require('../Inc/Functions.php');

$UpgradeScript = $_SERVER['PHP_SELF'];
$Step = isset($_GET['step']) ? $_GET['step'] : '';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<TITLE><?= $SiteDescription ?> Upgrade Version 1.55 to Version 1.56</TITLE>
			<META name="Author" content="Jim Willsher">
			<META name="Keywords" content="PHP, News, Headlines, PHPFreeNews">
			<META name="Description" content="PHP Free News">
			<LINK rel="stylesheet" href="../Inc/Styles.css" type="text/css" />
		</HEAD>
		<BODY>
			<P class="plaintext"><IMG src="../Inc/Images/<?= $AdminSiteLogo ?>" width="266" height="61"></P>
			<P class="plaintext">


			<?php
			// =============================================================================================================

			if ($Step == '')
			{
				// First Stage
				?>
				Welcome to the Version 1.55 to 1.56 Upgrade Script for <B><?= $SiteDescription ?></B>.<BR>
				<BR>
				This script will update your database to make it compliant with Version 1.55 of <?= $SiteDescription ?>.
				<BR>
				The installer will upgrade database <B>"<?= $db ?>"</B>. Remember to upgrade every database you have.</P>
				<P class="plaintext">It is assumed that you are already running version 1.55<BR>
		    	<BR>
		    	The MySql User in your Config.php file must have the following permissions: ALTER.<BR>
		    	Once the database has been installed you can safely revoke ALTER.<BR>
				<BR>
		    	Step 1 of the upgrade will add new fields to the database.<BR>
				<BR>
				<A href="<?=$UpgradeScript?>?step=1">Perform Upgrade Step 1>></A>
				<?php
			}

			// =============================================================================================================

			elseif ($Step == '1')
			{
				echo ('<br>Altering table: news_templates ');
				$sql_query = mysql_query("ALTER TABLE news_templates
						ADD Comments TEXT NOT NULL DEFAULT '' AFTER LongPost");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				// ===============================================================================================

				?>
				<BR>
				<BR>
				If there were no errors generated then the Database tables were updated successfully.<BR><BR>
				<BR>
		    	Step 2 of the upgrade will update the database.<BR>
				Click 'Next Step' when ready.
				<BR>
				<BR>
				<A href="<?=$UpgradeScript?>?step=2">Next Step >></A>
				<?php
			}

			// =============================================================================================================

			elseif ($Step == '2')
			{
				// Update
				echo ('<br>Updating templates with new values...');
				$sql_query = mysql_query("UPDATE `news_templates` SET Comments = '<TR><TD><B>{name} on {commentdate} at {commenttime}</B></TD></TR><TR><TD>{comment}<HR></TD></TR>'");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				// ===============================================================================================

				?>
				<BR>
				<BR>
				If there were no errors generated then the Database was updated successfully.<BR>
				Please now delete this Upgrade script (<?= $_SERVER['PHP_SELF'] ?>) or make it inaccessible.<BR>
				Ideally, you should delete the entire Upgrade folder.
				<BR>
				<?php
			}
			?>
	    	</P>
		</BODY>
</HTML>
