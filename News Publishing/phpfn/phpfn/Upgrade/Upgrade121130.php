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
		<TITLE><?= $SiteDescription ?> Upgrade Version 1.21 to Version 1.30</TITLE>
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
				Welcome to the Version 1.21 to 1.30 Upgrade Script for <B><?= $SiteDescription ?></B>.<BR>
				<BR>
				This script will update your database to make it compliant with Version 1.30 of <?= $SiteDescription ?>.
				<BR>
				The installer will upgrade database <B>"<?= $db ?>"</B>. Remember to upgrade every database you have.		</P>
			<P class="plaintext">It is assumed that you are already running version 1.21 or 1.22. <BR>
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
				echo ('<br>Creating table: news_ratings...');
				$sql_query = mysql_query("CREATE TABLE news_ratings (
					ID MEDIUMINT NOT NULL AUTO_INCREMENT,
					ArticleID MEDIUMINT NOT NULL,
					IPAddress VARCHAR(15) NOT NULL,
					RatingDate DATE NOT NULL,
					RatingTime TIME NOT NULL,
					Rating TINYINT NOT NULL,
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

				echo ('<br>Creating table: news_comments...');
				$sql_query = mysql_query("CREATE TABLE news_comments (
					ID MEDIUMINT NOT NULL AUTO_INCREMENT,
					ArticleID MEDIUMINT NOT NULL,
					IPAddress VARCHAR(15) NOT NULL,
					Name VARCHAR(200) NOT NULL,
					EmailAddress VARCHAR(200) NOT NULL,
					CommentDate DATE NOT NULL,
					CommentTime TIME NOT NULL,
					VerificationCode VARCHAR(30) NOT NULL,
					Approved TINYINT NOT NULL,
					Comment TEXT,
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
						ADD AllowComments TINYINT NOT NULL DEFAULT '1' AFTER Visible,
						ADD Approved TINYINT NOT NULL DEFAULT '1' AFTER Visible");
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
						ADD CanApprovePosts TINYINT NOT NULL DEFAULT '0' AFTER EditAnyPost");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Creating index: news_comments->ArticleIP ');
				$sql_query = mysql_query("ALTER TABLE news_comments ADD INDEX ArticleIP (ArticleID, IPAddress) ");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Creating index: news_comments->Verification ');
				$sql_query = mysql_query("ALTER TABLE news_comments ADD INDEX ArticleVerification (ArticleID, VerificationCode) ");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Creating index: news_ratings->ArticleIP ');
				$sql_query = mysql_query("ALTER TABLE news_ratings ADD INDEX ArticleIP (ArticleID, IPAddress) ");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Creating index: news_posts->CatArticle ');
				$sql_query = mysql_query("ALTER TABLE news_posts ADD INDEX CatArticle (CatID, ID) ");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Updating data: news_users...');
				$sql_query = mysql_query("UPDATE news_users SET CanApprovePosts = '1' WHERE AccessLevel='2'");
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
