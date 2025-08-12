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


// ==============================================================================================================================

function TemporaryReadTemplate($templatemode)
{
	global $NewsDir, $TemplateDir;

	if ($templatemode == "StickyShort") {
		$filename = "$_SERVER[DOCUMENT_ROOT]$NewsDir" . "$TemplateDir/StickyShort.txt";
	} elseif ($templatemode == "NonStickyShort") {
		$filename = "$_SERVER[DOCUMENT_ROOT]$NewsDir" . "$TemplateDir/NonStickyShort.txt";
	} elseif ($templatemode == "StickyLong") {
		$filename = "$_SERVER[DOCUMENT_ROOT]$NewsDir" . "$TemplateDir/StickyLong.txt";
	} elseif ($templatemode == "NonStickyLong") {
		$filename = "$_SERVER[DOCUMENT_ROOT]$NewsDir" . "$TemplateDir/NonStickyLong.txt";
	} elseif ($templatemode == "Codes") {
		$filename = "$_SERVER[DOCUMENT_ROOT]$NewsDir" . "$TemplateDir/CustomCodes.txt";
	} else {
		echo ("Your paths in the config file are not correct or the template files were not uploaded correctly.<br><br>Exiting out gracefully.");
		exit();
	}

	if (file_exists($filename)) {
		$fd = fopen ($filename, "r"); 
		$contents_temp = fread ($fd, filesize ($filename)); 
		fclose ($fd);
		return $contents_temp;
	} else {
		echo ("Your paths in the config file are not correct or the template files were not uploaded correctly.<br><br>Exiting out gracefully.");
		exit();
	}
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<TITLE><?= $SiteDescription ?> Upgrade Version 1.9 to Version 1.21</TITLE>
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
				Welcome to the Version 1.9 to 1.21 Upgrade Script for <B><?= $SiteDescription ?></B>.<BR>
				<BR>
				This script will update your database to make it compliant with Version 1.21 of <?= $SiteDescription ?>.
				<BR>
				The installer will upgrade database <B>"<?= $db ?>"</B>. Remember to upgrade every database you have.		</P>
			<P class="plaintext">It is assumed that you are already running version 1.9 or 1.20. <BR>
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
				echo ('<br>Creating table: news_templates...');
				$sql_query = mysql_query("CREATE TABLE news_templates (
					ID MEDIUMINT NOT NULL AUTO_INCREMENT,
					TemplateName VARCHAR(30) NOT NULL,
					ShortPost TEXT NOT NULL,
					LongPost TEXT NOT NULL,
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

				echo ('<br>Creating table: news_usercodes...');
				$sql_query = mysql_query("CREATE TABLE news_usercodes (
					ID TINYINT NOT NULL AUTO_INCREMENT,
					UserCode VARCHAR(30) NOT NULL,
					ReplacementText TEXT,
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


				echo ('<br>Updating table: news_posts...');
				$sql_query = mysql_query("ALTER TABLE news_posts
						ADD TemplateID MEDIUMINT NOT NULL DEFAULT '0' AFTER CatID");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Creating default template');
				$Short = TemporaryReadTemplate("StickyShort");
				$Long = TemporaryReadTemplate("StickyLong");
				$sql_query = mysql_query("INSERT INTO news_templates (TemplateName, ShortPost, LongPost) VALUES('Default', '$Short','$Long')");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Creating initial Sticky template');
				$Short = TemporaryReadTemplate("StickyShort");
				$Long = TemporaryReadTemplate("StickyLong");
				$sql_query = mysql_query("INSERT INTO news_templates (TemplateName, ShortPost, LongPost) VALUES('Template 1', '$Short','$Long')");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Creating initial Non-Sticky template');
				$Short = TemporaryReadTemplate("NonStickyShort");
				$Long = TemporaryReadTemplate("NonStickyLong");
				$sql_query = mysql_query("INSERT INTO news_templates (TemplateName, ShortPost, LongPost) VALUES('Template 2', '$Short','$Long')");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Assigning templates to existing sticky posts');
				$sql_query = mysql_query("UPDATE news_posts SET TemplateID = 2 WHERE Sticky = 1");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}
				
				echo ('<br>Assigning templates to existing non-sticky posts');
				$sql_query = mysql_query("UPDATE news_posts SET TemplateID = 3 WHERE Sticky = 0");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Importing user-defined codes');
				$UserCodes = explode("\n", TemporaryReadTemplate("Codes"));
				for ($i=0; $i < count($UserCodes); $i++)
				{
					$Data = explode("::", $UserCodes[$i]);
					$Data[1]= str_replace("::", "", $Data[1]);
					$sql_query = mysql_query("INSERT INTO news_usercodes (UserCode, ReplacementText) VALUES('$Data[0]', '$Data[1]')");
				}

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
				You may also now remove your Templates folder as it is no longer required.
				<BR>
				<?php
			}
			?>
	    	</P>
		</BODY>
</HTML>
