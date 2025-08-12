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
		<TITLE><?= $SiteDescription ?> Upgrade Version 1.41 to Version 1.50</TITLE>
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
				Welcome to the Version 1.41 to 1.50 Upgrade Script for <B><?= $SiteDescription ?></B>.<BR>
				<BR>
				This script will update your database to make it compliant with Version 1.50 of <?= $SiteDescription ?>.
				<BR>
				The installer will upgrade database <B>"<?= $db ?>"</B>. Remember to upgrade every database you have.		</P>
			<P class="plaintext">It is assumed that you are already running version 1.41 or 1.42<BR>
		    	<BR>
		    	The MySql User in your Config.php file must have the following permissions: CREATE, ALTER.<BR>
		    	Once the database has been installed you can safely revoke CREATE and ALTER.<BR>
				<BR>
		    	Step 1 of the upgrade will add new fields and index to the database.<BR>
				<BR>
				<A href="<?=$UpgradeScript?>?step=1">Perform Upgrade Step 1>></A>
				<?php
			}

			// =============================================================================================================

			elseif ($Step == '1')
			{
				// Set up the tables...
				echo ('<br>Creating table: news_postcategories...');
				$sql_query = mysql_query("CREATE TABLE news_postcategories (
					ArticleID MEDIUMINT NOT NULL,
					CatID MEDIUMINT NOT NULL)");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Creating index: news_postcategories->ArticleCat ');
				$sql_query = mysql_query("ALTER TABLE news_postcategories ADD INDEX ArticleCat (ArticleID, CatID) ");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Creating index: news_postcategories->CatArticle ');
				$sql_query = mysql_query("ALTER TABLE news_postcategories ADD INDEX CatArticle (CatID, ArticleID) ");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Altering table: news_posts ');
				$sql_query = mysql_query("ALTER TABLE news_posts
						ADD PostDateTime DATETIME NOT NULL AFTER PostTime,
						ADD OriginalPostDateTime DATETIME NOT NULL AFTER OriginalPostTime");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Altering table: news_audit ');
				$sql_query = mysql_query("ALTER TABLE news_audit
						ADD EventDateTime DATETIME NOT NULL AFTER EventTime");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Altering table: news_comments ');
				$sql_query = mysql_query("ALTER TABLE news_comments
						ADD CommentDateTime DATETIME NOT NULL AFTER CommentTime");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Altering table: news_ratings ');
				$sql_query = mysql_query("ALTER TABLE news_ratings
						ADD RatingDateTime DATETIME NOT NULL AFTER RatingTime");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Creating fulltext index: news_posts (ShortPost)...');
				$sql_query = @mysql_query("ALTER TABLE news_posts DROP INDEX ShortPost");
				$sql_query = mysql_query("ALTER TABLE news_posts ADD FULLTEXT(ShortPost)");
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
				$sql_query = @mysql_query("ALTER TABLE news_posts DROP INDEX LongPost");
				$sql_query = mysql_query("ALTER TABLE news_posts ADD FULLTEXT(LongPost)");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					echo '<BR><B>It was not possible to create the full-text index for Long Posts. The "Search for News" facility might be slower. This does <I><U>not</U></I> prevent you from continuing.</B><BR>';
				}

				// ===============================================================================================

				?>
				<BR>
				<BR>
				If there were no errors generated then the Database tables were created successfully.<BR><BR>
				<BR>
		    	Step 2 of the upgrade will try to migrate your data to the new structure.<BR>
				Click 'Next Step' when ready.
				<BR>
				<BR>
				<A href="<?=$UpgradeScript?>?step=2">Next Step >></A>
				<?php
			}

			// =============================================================================================================

			elseif ($Step == '2')
			{

				// Set up the tables...
				echo ('<br>Populating table: news_postcategories...');
				$sql_query = mysql_query("INSERT INTO news_postcategories
					SELECT ID, CatID FROM news_posts WHERE CatID <> 0");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Migrating News Posts information...');
				$sql_query1 = mysql_query("UPDATE news_posts SET PostDateTime = CONCAT(CONVERT(PostDate, CHAR), ' ', CONVERT(PostTime, CHAR))");
				$sql_query2 = mysql_query("UPDATE news_posts SET OriginalPostDateTime = CONCAT(CONVERT(OriginalPostDate, CHAR), ' ', CONVERT(OriginalPostTime, CHAR))");

				if ( ($sql_query1) && ($sql_query2) )
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Migrating News Audit information...');
				$sql_query = mysql_query("UPDATE news_audit SET EventDateTime = CONCAT(CONVERT(EventDate, CHAR), ' ', CONVERT(EventTime, CHAR))");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Migrating News Rating information...');
				$sql_query = mysql_query("UPDATE news_ratings SET RatingDateTime = CONCAT(CONVERT(RatingDate, CHAR), ' ', CONVERT(RatingTime, CHAR))");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Migrating News Comments information...');
				$sql_query = mysql_query("UPDATE news_comments SET CommentDateTime = CONCAT(CONVERT(CommentDate, CHAR), ' ', CONVERT(CommentTime, CHAR))");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Deleting index: news_posts->CatArticle ');
				$sql_query = mysql_query("ALTER TABLE news_posts DROP INDEX CatArticle");
				echo ('<b> Done!</b>');


				echo ('<br>Creating index: news_posts->ArticleDT ');
				$sql_query = mysql_query("ALTER TABLE news_posts ADD INDEX ArticleDT (PostDateTime)");
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
				If there were no errors generated then the data was migrated successfully.<BR><BR>
				Next, the install script will try to remove redundant database fields.<BR>
				IMPORTANT: Only proceed if you have encountered no errors up to this point.<BR>
				Click 'Next Step' when ready.
				<BR>
				<BR>
				<A href="<?=$UpgradeScript?>?step=3">Next Step >></A>
				<?php
			}

			// =============================================================================================================

			elseif ($Step == '3')
			{
				echo ('<br>Removing old column from news_posts...');
				$sql_query = mysql_query("ALTER TABLE news_posts
					DROP COLUMN CatID,
					DROP COLUMN PostDate,
					DROP COLUMN PostTime,
					DROP COLUMN OriginalPostDate,
					DROP COLUMN OriginalPostTime");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Removing old columns from news_audit...');
				$sql_query = mysql_query("ALTER TABLE news_audit
					DROP COLUMN EventDate,
					DROP COLUMN EventTime");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}


				echo ('<br>Removing old columns from news_ratings...');
				$sql_query = mysql_query("ALTER TABLE news_ratings
					DROP COLUMN RatingDate,
					DROP COLUMN RatingTime");
				if ($sql_query)
				{
					echo ('<b> Done!</b>');
				}
				else
				{
					echo mysql_error();
					exit();
				}

				echo ('<br>Removing old columns from news_comments...');
				$sql_query = mysql_query("ALTER TABLE news_comments
					DROP COLUMN CommentDate,
					DROP COLUMN CommentTime");
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
				Please now delete this Upgrade script (<?= $_SERVER['PHP_SELF'] ?>) or make it inaccessible.<BR>
				Ideally, you should delete the entire Upgrade folder.
				<BR>
				<?php
			}
			?>
	    	</P>
		</BODY>
</HTML>
