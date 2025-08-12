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
		<TITLE>
		<?= $SiteDescription ?>
		Upgrade Version 1.9 Database to Version 1.15 Database</TITLE>
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
			Welcome to the Version 1.9 to 1.15 Upgrade Script for <B>
			<?= $SiteDescription ?>
			</B>.<BR>
			<BR>
			This script will update your database to make it compliant with Version 1.15 of<?= $SiteDescription ?>. <BR>
			The installer will upgrade database <B>"<?= $db ?>"</B>. Remember to upgrade every database you have.</P>
			<P class="plaintext">It is assumed that you are already running version 1.9 or later. <BR>
			<BR>
			The MySql User in your Config.php file must have the following permissions: ALTER.<BR>
			Once the database has been installed you can safely revoke ALTER. <BR>
			<BR>
			<A href="<?=$UpgradeScript?>?step=1">Perform Upgrade >></A>
			<?php
			}

			// =============================================================================================================

			elseif ($Step == '1')
			{
				// Set up the tables...
				echo ('<br>Updating Table: news_posts...');
				$sql_query = mysql_query("ALTER TABLE news_posts
						ADD `OriginalPostDate` DATE DEFAULT '0' NOT NULL AFTER `PostTime` ,
						ADD `OriginalPostTime` TIME DEFAULT '0' NOT NULL AFTER `OriginalPostDate");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Migrating Data: news_posts...');
				$sql_query = mysql_query("UPDATE news_posts
						SET `OriginalPostDate` = `PostDate` ,
							`OriginalPostTime`  = `PostTime`");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}


				// ===============================================================================================

				?>
				<BR>
				<BR>
				If there were no errors generated then the Database was updated successfully.<BR>
				Please now delete this installation file (<?= $_SERVER['PHP_SELF'] ?>) or make it inaccessible. <BR>
				<BR>
				<?php
			}
			?>
		</P>
	</BODY>
</HTML>
