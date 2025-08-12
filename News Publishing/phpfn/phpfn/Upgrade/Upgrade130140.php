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
		<TITLE><?= $SiteDescription ?> Upgrade Version 1.30 to Version 1.40</TITLE>
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
				Welcome to the Version 1.30 to 1.40 Upgrade Script for <B><?= $SiteDescription ?></B>.<BR>
				<BR>
				This script will update your database to make it compliant with Version 1.40 of <?= $SiteDescription ?>.
				<BR>
				The installer will upgrade database <B>"<?= $db ?>"</B>. Remember to upgrade every database you have.		</P>
			<P class="plaintext">It is assumed that you are already running version 1.31 or 1.32. <BR>
		    	<BR>
		    	The MySql User in your Config.php file must have the following permissions: CREATE, ALTER.<BR>
		    	Once the database has been installed you can safely revoke CREATE and ALTER. <BR>
				<BR>
				<A href="<?=$UpgradeScript?>?step=1">Perform Upgrade >></A>
				<?php
			}

			// =============================================================================================================

			elseif ($Step == '1')
			{
				// Set up the tables...
				echo ('<br>Creating table: news_audit...');
				$sql_query = mysql_query("CREATE TABLE news_audit (
					ID MEDIUMINT NOT NULL AUTO_INCREMENT,
					EventDate DATE NOT NULL,
					EventTime TIME NOT NULL,
					EventUserID MEDIUMINT NOT NULL,
					EventCatID SMALLINT NOT NULL,
					EventType VARCHAR(1) NOT NULL,
					LinkedID MEDIUMINT NULL,
					Description TEXT NOT NULL,
					PRIMARY KEY (ID) ) ");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Creating table: news_audit_categories...');
				$sql_query = mysql_query("CREATE TABLE news_audit_categories (
					ID MEDIUMINT NOT NULL,
					CatDesc VARCHAR(255) NOT NULL,
					PRIMARY KEY (ID) ) ");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Inserting audit-event categories...');
				$sql1 = mysql_query("INSERT INTO `news_audit_categories` SET ID=1, CatDesc='News Article'");
				$sql2 = mysql_query("INSERT INTO `news_audit_categories` SET ID=2, CatDesc='Sticky status'");
				$sql3 = mysql_query("INSERT INTO `news_audit_categories` SET ID=3, CatDesc='Visible status'");
				$sql4 = mysql_query("INSERT INTO `news_audit_categories` SET ID=4, CatDesc='Category'");
				$sql5 = mysql_query("INSERT INTO `news_audit_categories` SET ID=5, CatDesc='Template'");
				$sql6 = mysql_query("INSERT INTO `news_audit_categories` SET ID=6, CatDesc='Image'");
				$sql7 = mysql_query("INSERT INTO `news_audit_categories` SET ID=7, CatDesc='User'");
				$sql8 = mysql_query("INSERT INTO `news_audit_categories` SET ID=8, CatDesc='Password'");
				$sql9 = mysql_query("INSERT INTO `news_audit_categories` SET ID=9, CatDesc='Login'");
				$sql10 = mysql_query("INSERT INTO `news_audit_categories` SET ID=10, CatDesc='User-Def Code'");
				$sql11 = mysql_query("INSERT INTO `news_audit_categories` SET ID=11, CatDesc='News Article approval'");

				if ($sql1 && $sql2 && $sql3 && $sql4 && $sql5 && $sql6 && $sql7 && $sql8 && $sql9 && $sql10 && $sql11)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo "Error!" . mysql_error();
					exit();
				}

				echo ('<br>Creating index: news_audit->EventCatTypeDT ');
				$sql_query = mysql_query("ALTER TABLE news_audit ADD INDEX EventCatTypeDT (EventCatID, EventType, EventDate, EventTime) ");
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
				Please now delete this installation file (<?= $_SERVER['PHP_SELF'] ?>) or make it inaccessible.
				<BR>
				<?php
			}
			?>
	    	</P>
		</BODY>
</HTML>
