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
		<TITLE><?= $SiteDescription ?> Upgrade Version 1.8 to Version 1.9</TITLE>
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
				Welcome to the Version 1.8 to 1.9 Upgrade Script for <B><?= $SiteDescription ?></B>.<BR>
				<BR>
				This script will update your database to make it compliant with Version 1.9 of <?= $SiteDescription ?>.
				<BR>
				The installer will upgrade database <B>"<?= $db ?>"</B>. Remember to upgrade every database you have.		</P>
			<P class="plaintext">It is assumed that you are already running version 1.8. <BR>
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
				echo ('<br>Updating table: news_categories...');
				$sql_query = mysql_query("ALTER TABLE news_categories
						CHANGE ID ID MEDIUMINT NOT NULL AUTO_INCREMENT,
						CHANGE CatDesc CatDesc VARCHAR(255) NOT NULL");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Updating table: news_images...');
				$sql_query = mysql_query("ALTER TABLE news_images
						CHANGE ID ID MEDIUMINT NOT NULL AUTO_INCREMENT,
						CHANGE ImageName ImageName VARCHAR(255) NOT NULL,
						CHANGE ImageFilename ImageFilename  VARCHAR(255) NOT NULL");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}


				echo ('<br>Updating table: news_users...');
				$sql_query = mysql_query("ALTER TABLE news_users
						CHANGE ID ID MEDIUMINT NOT NULL AUTO_INCREMENT,
						CHANGE Username Username VARCHAR(30) NOT NULL,
						CHANGE Password Password VARCHAR(30) NOT NULL,
						CHANGE FullName FullName VARCHAR(255) NOT NULL,
						CHANGE AccessLevel AccessLevel TINYINT NOT NULL DEFAULT '0',
						CHANGE EditAnyPost EditAnyPost TINYINT NOT NULL DEFAULT '0'");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Updating table: news_posts...');
				$sql_query = mysql_query("ALTER TABLE news_posts
						CHANGE ID ID MEDIUMINT NOT NULL AUTO_INCREMENT,
						CHANGE PostDate PostDate DATE NOT NULL,
						CHANGE PostTime PostTime TIME NOT NULL,
						CHANGE ShortPost ShortPost TEXT NOT NULL,
						CHANGE Sticky Sticky TINYINT NOT NULL DEFAULT '0',
						CHANGE Priority Priority TINYINT NOT NULL DEFAULT '1',
						CHANGE Archived Archived TINYINT NOT NULL DEFAULT '0',
						CHANGE Visible Visible TINYINT NOT NULL DEFAULT '1',
						CHANGE AuthorID AuthorID MEDIUMINT NOT NULL DEFAULT '0',
						CHANGE ImageID ImageID MEDIUMINT NOT NULL DEFAULT '0',
						CHANGE CatID CatID MEDIUMINT NOT NULL DEFAULT '0',
						CHANGE TimesRead TimesRead INT NOT NULL DEFAULT '0'");
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
