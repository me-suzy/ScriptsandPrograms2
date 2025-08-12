MySQL-Dumper v2.6 by Matthijs Draijer

MySQL-Dumper is a script for automatically backuping your tables in a MySQL-database.
The two basic pages are a page for frequently use (backup.php) and an index-page
(index). The page for frequently use checks if there are tables in the database that have
been changed after the last back-up. If they are, there will be made a back-up of these tables,
and this backup will be saved as ZIP-file which saves space. 

INSTALL
1.	Unzip the ZIP-file
2.	Change the variables in 'inc_config.php'
3.	Move the files (9 in total) to your webserver or to a map on your webserver (ie
	http://www.yourdomain.com/dump/)
	
Now the script is ready for running
4a.	Open a PHP-file which you use every day and include :
	<? include (../dump/backup.php"); ?>
	Now, every time you use the upper mentioned file, if necessary a backup of the selected
	tables in your MySQL-database will be made.
4b.	Open CRONTAB and include :
	00 00 * * * wget -O/dev/null -q http://www.yourdomain.com/dump/backup.php
	Now every day at 00:00 the script will check for changed tables.

5.	When you want to backup all tables or an unusual selection of the tables, go to
	'/dump/admin.php' and select the tables you want to backup.
6.	When you want to delete one or more generated files, go to
	'http://www.yourdomain.com/dump/', and make your choise of one of the options.


Matthijs Draijer
ICQ# : 46739124
MSN  : matthijsdraijer@hotmail.com