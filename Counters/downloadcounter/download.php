<?php
/*
Simple Download Counter 1.0
by Drew Phillips (http://www.drew-phillips.com)

Setup:
Setting up this script is very easy and requires little experience.
First, setup a mysql database or use an existing database.  This 
program only requires one small table.
Once you have setup the database, issue the following sql query to
create the table. *NOTE: if you are unsure of how to do this setup,
visit http://phpmyadmin.sourceforge.net and download phpMyAdmin, a 
web-based SQL database administration tool.  If you are unsure of
whether or not you have MySQL installed or setup for you, contact
your system administrator or visit www.mysql.com for more details.

CREATE TABLE `dl_count` (
`file` VARCHAR( 128 ) NOT NULL ,
`count` INT NOT NULL
);

The above will have you two small steps away from being ready.
Next upload the download.php script (this file) to a location
on your website.  Create a web accessable folder in which you
will use to store files.  Place all your download files in the
folder.  Next alter the variables below to match your database
and folder location.

Usage:
There is no need to setup any filenames in your database, the 
script will do it for you upon the first time a file gets downloaded.
Simply create a link to download.php like so:
http://www.yoursite.com/download.php?file=testfile.zip
The first time someone access testfile, an entry will be added to the
database and the count will be initialized.  They will be transparently
redirected and prompted to download the file.
To display the count on a page:
First you must include the download script on your page by inserting the
following code prior to any calls to the count function.
<?php include("/full/path/to/download.php"); ?>
This is an absolute path, not a URI, on windows it will be similar to C:\inetpub\wwwroot\download.php
and unix /home/username/public_html/download.php
If you try to include using http:// it will not work properly.

If you are unsure of your path, you can use this:
<?php include($_SERVER['DOCUMENT_ROOT'] . "/folder/download.php"); ?>
where /folder/download.php is the web path to your script.
i.e. http://www.yoursite.com/folder/download.php

Then to get a count, simply do the following where you want the number of downloads displayed.
<?php echo showCount("filename.ext"); ?>  That will show the count.
Make sure the page with this on it has a .php extension, not .html or anything else.
If that is the case it will not work.
Thats it...if you followed that you will be up and running in no time.
If you require additional help, email drew@drew-phillips.com or get in contact with
me from my website, www.drew-phillips.com


**USAGE IN A NUTSHELL FOR EXPERIENCED PHP USERS**
<?php include("/path/to/download.php"); ?>
<a href="download.php?file=testfile.zip">Download Now</a><br>
This file has been downloaded <?php echo showCount("testfile.zip"); ?> times.


*/

//CONFIGURATION SECTION

$FILES_DIR = "/downloads/";
//URI to files
//Include beginning and trailing slash
//This is the web path to your files, not a server path
//Example:  www.yoursite.com/folder/files/ will be /folder/files/
//If you wish to serve offsite files, you can use http://www.site.com/downloads/

$MYSQL_USER = "drew";             //The username used to connect to MySQL
$MYSQL_PASS = "password";         //The MySQL Password for the user
$MYSQL_HOST = "localhost";        //The host to connect to
$MYSQL_DB   = "drew";             //The database in which the dl_count table is in


##############################################################
# Thats IT!!  No more configuration required.
##############################################################


$cnt_sql = @mysql_connect($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS);
@mysql_select_db($MYSQL_DB, $cnt_sql);

if(isset($_GET['file'])) {
	$file = urlencode($_GET['file']);

	if(empty($file)) {
		echo "No File Specified";
		exit;
	}
	if(strpos($file, "..") !== FALSE) {
		echo "HACK ATTEMPT!";
		exit;
	}
	if(strpos($file, "://") !== FALSE) {
		echo "Invalid File";
		exit;
	}

	$cookie = urlencode(str_replace(".", "_", $file));  //cookie fix

	$query = "SELECT * FROM dl_count WHERE file = '$file'";
	$result = mysql_query($query, $cnt_sql);
	if(!$result) {
		echo mysql_error();
		exit;
	}
	if(mysql_num_rows($result) == 0) {
		//first use of this file
		$query = "INSERT INTO dl_count VALUES('$file', 1)";
		$result = mysql_query($query, $cnt_sql);
		setcookie("dl_" . $cookie, "set", time() + 60*60*24*365);
	} else {
		if(!isset($_COOKIE['dl_' . $cookie])) {
			$query = "UPDATE dl_count SET count = count + 1 WHERE file = '$file'";
			$result = mysql_query($query);
			setcookie("dl_". $cookie, "set", time() + 60*60*24*365);
		}
	}

	header("Location: " . $FILES_DIR . $file);
}

function showCount($fileID)
{
	global $cnt_sql;
	$query = "SELECT count FROM dl_count WHERE file = '$fileID'";
	$result = mysql_query($query, $cnt_sql);
	if(mysql_num_rows($result) == 0) {
		return 0;
	} else {
		$count = mysql_fetch_row($result);
		return $count[0];
	}
}

?>
