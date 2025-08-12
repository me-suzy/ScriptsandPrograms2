<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

require('Config/Config.php');
require('Inc/Functions.php');

$Step = isset($_GET['step']) ? $_GET['step'] : '';

if (!$AllowInstall)
	die('The installation script has been disabled!');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<TITLE><?= $SiteDescription ?> Installation</TITLE>
			<META name="Author" content="Jim Willsher">
			<META name="Keywords" content="PHP, News, Headlines, PHPFreeNews">
			<META name="Description" content="PHP Free News">
			<LINK rel="stylesheet" href="Inc/Styles.css" type="text/css" />
		</HEAD>
		<BODY>
			<P class="plaintext"><IMG src="Inc/Images/<?= $AdminSiteLogo ?>" width="266" height="61"></P>
			<P class="plaintext">

			<?php
			// =============================================================================================================

			if ($Step == '')
			{
				// First Stage
				?>
				Welcome to the installer for <B>PHP News</B>.<BR>
				<BR>
				Don't forget to update the configuration file (or remove Install.php from your server)<BR>
				once the application has been installed, to prevent mis-configuration.<BR>
				<BR>
				If you have configured your database information in the configuration file<BR>
				then click next to create the tables. The installer will try to use database <B>"<?= $db ?>"</B>
				<BR><BR>
				The MySql User in your Config.php file must have the following permissions: SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX.<BR>
				Once the database has been installed you can safely revoke CREATE, ALTER and INDEX.
				<BR>

				<?php
				if (!MySql4())
				{
					?>
					You would appear to be running a version of MySql which is earlier than 4.0.0. Whilst PHPFreeNews *should* run happily on this version, there are no guarantees. If you do run it on MySql 3 but find a few "funnies" then let me know I'l try to iron them out!
					<BR>
					<?php
				}
				?>
				<BR>
				<A href="?step=1">Next >></A>
				<?php
			}

			// =============================================================================================================

			elseif ($Step == '1')
			{
				// Set up the tables...
				echo ('<br>Creating table: news_posts...');
				$sql_query = mysql_query("CREATE TABLE news_posts (
					ID MEDIUMINT NOT NULL AUTO_INCREMENT,
					AuthorID MEDIUMINT NOT NULL DEFAULT '0',
					PostDateTime DATETIME NOT NULL,
					OriginalPostDateTime DATETIME DEFAULT '0' NOT NULL,
					Headline VARCHAR(255) NOT NULL,
					ShortPost TEXT NOT NULL,
					LongPost TEXT,
					ImageID MEDIUMINT NOT NULL DEFAULT '0',
					TemplateID MEDIUMINT NOT NULL DEFAULT '0',
					Sticky TINYINT NOT NULL DEFAULT '0',
					Locked TINYINT NOT NULL DEFAULT '0',
					Priority TINYINT NOT NULL DEFAULT '1',
					Visible TINYINT NOT NULL DEFAULT '1',
					Approved TINYINT NOT NULL DEFAULT '1',
					AllowComments TINYINT NOT NULL DEFAULT '1',
					TimesRead INT NOT NULL DEFAULT '0',
					Archived TINYINT NOT NULL DEFAULT '0',
					PRIMARY KEY (ID))");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();
	
				echo ('<br>Creating table: news_images...');
				$sql_query = mysql_query("CREATE TABLE news_images (
						ID MEDIUMINT NOT NULL AUTO_INCREMENT,
						ImageName VARCHAR(255) NOT NULL,
						ImageFilename  VARCHAR(255) NOT NULL,
						PRIMARY KEY (ID))");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Creating table: news_categories...');
				$sql_query = mysql_query("CREATE TABLE news_categories (
						ID MEDIUMINT NOT NULL AUTO_INCREMENT,
						CatDesc VARCHAR(255) NOT NULL,
						PRIMARY KEY (ID))");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();
	
				echo ('<br>Creating table: news_users...');
				$sql_query = mysql_query("CREATE TABLE news_users (
						ID MEDIUMINT NOT NULL AUTO_INCREMENT,
						Username VARCHAR(30) NOT NULL,
						Password VARCHAR(32) NOT NULL,
						FullName VARCHAR(255) NOT NULL,
						AccessLevel TINYINT NOT NULL DEFAULT '0',
						EditAnyPost TINYINT NOT NULL DEFAULT '0',
						CanApprovePosts TINYINT NOT NULL DEFAULT '0',
						CanChangeLock TINYINT NOT NULL DEFAULT '0',
						MustChangePassword TINYINT NOT NULL DEFAULT '0',
						PRIMARY KEY (ID))");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Creating table: news_templates...');
				$sql_query = mysql_query("CREATE TABLE news_templates (
					ID MEDIUMINT NOT NULL AUTO_INCREMENT,
					TemplateName VARCHAR(30) NOT NULL,
					Headline TEXT NOT NULL,
					ShortPost TEXT NOT NULL,
					LongPost TEXT NOT NULL,
					Comments TEXT NOT NULL,
					PRIMARY KEY (ID) ) ");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Creating table: news_usercodes...');
				$sql_query = mysql_query("CREATE TABLE news_usercodes (
					ID TINYINT NOT NULL AUTO_INCREMENT,
					UserCode VARCHAR(30) NOT NULL,
					ReplacementText TEXT,
					PRIMARY KEY (ID) ) ");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Creating table: news_ratings...');
				$sql_query = mysql_query("CREATE TABLE news_ratings (
					ID MEDIUMINT NOT NULL AUTO_INCREMENT,
					ArticleID MEDIUMINT NOT NULL,
					IPAddress VARCHAR(15) NOT NULL,
					RatingDateTime DATETIME NOT NULL,
					Rating TINYINT NOT NULL,
					PRIMARY KEY (ID) ) ");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Creating table: news_comments...');
				$sql_query = mysql_query("CREATE TABLE news_comments (
					ID MEDIUMINT NOT NULL AUTO_INCREMENT,
					ArticleID MEDIUMINT NOT NULL,
					IPAddress VARCHAR(15) NOT NULL,
					Name VARCHAR(200) NOT NULL,
					EmailAddress VARCHAR(200) NOT NULL,
					CommentDateTime DATETIME NOT NULL,
					VerificationCode VARCHAR(30) NOT NULL,
					Approved TINYINT NOT NULL,
					Comment TEXT,
					PRIMARY KEY (ID) ) ");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Creating table: news_audit...');
				$sql_query = mysql_query("CREATE TABLE news_audit (
					ID MEDIUMINT NOT NULL AUTO_INCREMENT,
					EventDateTime DATETIME NOT NULL,
					EventUserID MEDIUMINT NOT NULL,
					EventCatID SMALLINT NOT NULL,
					EventType VARCHAR(1) NOT NULL,
					LinkedID MEDIUMINT NULL,
					Description TEXT NOT NULL,
					PRIMARY KEY (ID) ) ");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Creating table: news_audit_categories...');
				$sql_query = mysql_query("CREATE TABLE news_audit_categories (
					ID MEDIUMINT NOT NULL,
					CatDesc VARCHAR(255) NOT NULL,
					PRIMARY KEY (ID) ) ");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Creating table: news_postcategories...');
				$sql_query = mysql_query("CREATE TABLE news_postcategories (
					ArticleID MEDIUMINT NOT NULL,
					CatID MEDIUMINT NOT NULL)");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				// ===============================================================================================

				?>
				<BR>
				<BR>
				If there were no errors generated then the Database tables were created successfully.<BR><BR>
				Next, the install script will try to create indexes on the tables.<BR>
				Click 'Next' when ready.
				<BR>
				<BR>
				<A href="?step=2">Next >></A>
				<?php
			}

			// =============================================================================================================

			elseif ($Step == '2')
			{
				echo ('<br>Creating fulltext index: news_posts (ShortPost)...');
				$sql_query = @mysql_query("ALTER TABLE news_posts ADD FULLTEXT(ShortPost)");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					echo '<BR><B>It was not possible to create the full-text index for Short Posts. The "Search for News" facility might be slower. This does <I><U>not</U></I> prevent you from continuing.</B><BR>';
				}

				echo ('<br>Creating fulltext index: news_posts (LongPost)...');
				$sql_query = @mysql_query("ALTER TABLE news_posts ADD FULLTEXT(LongPost)");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					echo '<BR><B>It was not possible to create the full-text index for Long Posts. The "Search for News" facility might be slower. This does <I><U>not</U></I> prevent you from continuing.</B><BR>';
				}

				echo ('<br>Creating index: news_comments->ArticleIP ');
				$sql_query = mysql_query("ALTER TABLE news_comments ADD INDEX ArticleIP (ArticleID, IPAddress) ");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Creating index: news_comments->Verification ');
				$sql_query = mysql_query("ALTER TABLE news_comments ADD INDEX ArticleVerification (ArticleID, VerificationCode) ");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Creating index: news_ratings->ArticleIP ');
				$sql_query = mysql_query("ALTER TABLE news_ratings ADD INDEX ArticleIP (ArticleID, IPAddress) ");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Creating index: news_audit->EventCatTypeDT ');
				$sql_query = mysql_query("ALTER TABLE news_audit ADD INDEX EventCatTypeDT (EventCatID, EventType, EventDateTime) ");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Creating index: news_postcategories->ArticleCat ');
				$sql_query = mysql_query("ALTER TABLE news_postcategories ADD INDEX ArticleCat (ArticleID, CatID) ");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Creating index: news_postcategories->CatArticle ');
				$sql_query = mysql_query("ALTER TABLE news_postcategories ADD INDEX CatArticle (CatID, ArticleID) ");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Creating index: news_posts->ArticleDT ');
				$sql_query = mysql_query("ALTER TABLE news_posts ADD INDEX ArticleDT (PostDateTime)");
				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				// ===============================================================================================

				?>
				<BR>
				<BR>
				If there were no errors generated (<I>other than fulltext index-related errors</I>) then the Database indexes were created successfully.<BR><BR>
				Next, the install script will insert the default admin account and sample data into the<BR>
				database.  Click 'Next' when ready.
				<BR>
				<BR>
				<A href="?step=3">Next >></A>
				<?php
			}

			// =============================================================================================================

			elseif ($Step == '3')
			{
				echo ('Inserting default Admin account...');
				$sql_query = mysql_query("INSERT INTO news_users SET ID=1, Username='Admin', Password=MD5('Admin'), FullName='Administrator', AccessLevel='2', EditAnyPost='1', CanApprovePosts='1', CanChangeLock='1', MustChangePassword='1'");

				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

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
				$sql12 = mysql_query("INSERT INTO `news_audit_categories` SET ID=12, CatDesc='Locked status'");

				if ($sql1 && $sql2 && $sql3 && $sql4 && $sql5 && $sql6 && $sql7 && $sql8 && $sql9 && $sql10 && $sql11 && $sql12)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Inserting sample image...');
				$sql_query = mysql_query("INSERT INTO `news_images` SET ID=1, ImageName='PHPFreeNews', ImageFilename='PHPFreeNews.gif'");

				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Inserting sample categories...');
				$sql_query1 = mysql_query("INSERT INTO `news_categories` SET ID=1, CatDesc='World News'");
				$sql_query2 = mysql_query("INSERT INTO `news_categories` SET ID=2, CatDesc='Local News'");

				if ($sql_query1 AND $sql_query2)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Inserting sample template...');
				$sql_query1 = mysql_query("INSERT INTO `news_templates` SET ID=1, TemplateName='Default', Headline='<table class=\"NewsHeading\" width=\"100%\"><tr><td>{headline}</td></tr></table>', ShortPost='<center><i>Posted by {author} on {newsdate} at {newstime}<i></center><BR>{image}{news}{readmore}', LongPost='<center><i>Posted by {author} on {newsdate} at {newstime}<i></center><BR>{image}{news}<br>{rating}<br>{comments}', Comments='<TR><TD><B>{name} on {commentdate} at {commenttime}</B></TD></TR><TR><TD>{comment}<HR></TD></TR>'");

				if ($sql_query1 AND $sql_query2)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();
				
				echo ('<br>Inserting sample user-defined codes...');
				$sql_query1 = mysql_query("INSERT INTO `news_usercodes` SET ID=1, UserCode='{beta}', ReplacementText='Download this program at your own risk.  It is still in beta stage!'");
				$sql_query1 = mysql_query("INSERT INTO `news_usercodes` SET ID=2, UserCode='{source}', ReplacementText='Source Code'");

				if ($sql_query1 AND $sql_query2)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Inserting sample news post...');
				$sql_query = mysql_query("INSERT INTO `news_posts` SET
					ID=1, AuthorID=1, PostDateTime='2005-03-01 16:30:00', OriginalPostDateTime='2005-03-01 16:30:00',
					Headline='Sample News Post',
					ShortPost='Thanks for installing PHPFreeNews.  This is your first news post!.\r\n\r\nPlease report any errors or bugs.',
					LongPost='This is the long post...',
					ImageID='1', TemplateID='1', Sticky='0', Locked='0', Priority='5', Visible='1', Approved='1',
					AllowComments='1', TimesRead=0, Archived='0'");

				if ($sql_query)
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				echo ('<br>Assigning sample news article to categories...');
				$sql_query1 = mysql_query("INSERT INTO `news_postcategories` SET ArticleID=1, CatID=1");
				$sql_query2 = mysql_query("INSERT INTO `news_postcategories` SET ArticleID=1, CatID=2");
				if ( ($sql_query1) && ($sql_query2) )
					echo ('<b> Done!</b>');
				else
					echo mysql_error();

				?>
				<BR>
				<BR>
				The install script has now finished setting up your database. You can now log into the script by browsing to <BR> 
				<B><A href="<?=$WWW?>/Admin.php"><?=$WWW?>/Admin.php</A></B>
				.<BR>
				<BR>
				The default account name is 'Admin' and the default password for this account is 'Admin'.<BR />
				<BR>
				<B>You must now edit your Config/Config.php file and change $AllowInstall=true to $AllowInstall=false</B>. This is the first configuration line in the file.
				<?php
			}
			?>
		</P>
	</BODY>
</HTML>
