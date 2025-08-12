<?
include "./config.php";

if ($create){
	$sql = "CREATE TABLE $tablescripts (
	  id int(11) NOT NULL auto_increment,
	  subcat int(11) NOT NULL default '0',
	  email varchar(255) NOT NULL default '',
	  password varchar(255) NOT NULL default '',
	  title varchar(255) NOT NULL default '',
	  homeurl varchar(255) NOT NULL default '',
	  dlurl varchar(255) NOT NULL default '',
	  demourl varchar(255) NOT NULL default '',
	  descr text NOT NULL,
	  price varchar(50) NOT NULL default '',
	  version varchar(10) NOT NULL default '',
	  hitsin int(11) NOT NULL default '0',
	  hitsout int(11) NOT NULL default '0',
	  added datetime NOT NULL default '0000-00-00 00:00:00',
	  PRIMARY KEY  (id),
	  UNIQUE KEY title (title)
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());

	$sql = "CREATE TABLE $tablecats (
	  id int(11) NOT NULL auto_increment,
	  cat varchar(255) NOT NULL default '',
	  ct int(11) NOT NULL default '0',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());

	

	$catsblob = "INSERT INTO $tablecats VALUES (1, 'Ad Management', 0);
	INSERT INTO $tablecats VALUES (2, 'Affiliate Programs', 0);
	INSERT INTO $tablecats VALUES (3, 'Auctions', 0);
	INSERT INTO $tablecats VALUES (4, 'Banner Exchange', 0);
	INSERT INTO $tablecats VALUES (5, 'Bookmark Management', 0);
	INSERT INTO $tablecats VALUES (6, 'Calculators', 0);
	INSERT INTO $tablecats VALUES (7, 'Calendars', 0);
	INSERT INTO $tablecats VALUES (8, 'Chat Scripts', 0);
	INSERT INTO $tablecats VALUES (9, 'Classified Ads', 0);
	INSERT INTO $tablecats VALUES (10, 'Click Tracking', 0);
	INSERT INTO $tablecats VALUES (11, 'Communication Tools', 0);
	INSERT INTO $tablecats VALUES (12, 'Content Management', 0);
	INSERT INTO $tablecats VALUES (13, 'Contests and Awards', 0);
	INSERT INTO $tablecats VALUES (14, 'Countdowns', 0);
	INSERT INTO $tablecats VALUES (15, 'Counters', 0);
	INSERT INTO $tablecats VALUES (16, 'Customer Support', 0);
	INSERT INTO $tablecats VALUES (17, 'Database Tools', 0);
	INSERT INTO $tablecats VALUES (18, 'Date and Time', 0);
	INSERT INTO $tablecats VALUES (19, 'Development Tools', 0);
	INSERT INTO $tablecats VALUES (20, 'Discussion Boards', 0);
	INSERT INTO $tablecats VALUES (21, 'E-Commerce', 0);
	INSERT INTO $tablecats VALUES (22, 'Education', 0);
	INSERT INTO $tablecats VALUES (23, 'Email Systems', 0);
	INSERT INTO $tablecats VALUES (24, 'Error Handling', 0);
	INSERT INTO $tablecats VALUES (25, 'FAQ and Knowledgebase', 0);
	INSERT INTO $tablecats VALUES (26, 'File Manipulation', 0);
	INSERT INTO $tablecats VALUES (27, 'Financial Tools', 0);
	INSERT INTO $tablecats VALUES (28, 'Flash', 0);
	INSERT INTO $tablecats VALUES (29, 'Form Processors', 0);
	INSERT INTO $tablecats VALUES (30, 'Games and Entertainment', 0);
	INSERT INTO $tablecats VALUES (31, 'Graphs and Charts', 0);
	INSERT INTO $tablecats VALUES (32, 'Groupware Tools', 0);
	INSERT INTO $tablecats VALUES (33, 'Guestbooks', 0);
	INSERT INTO $tablecats VALUES (34, 'Image Galleries', 0);
	INSERT INTO $tablecats VALUES (35, 'Image Manipulation', 0);
	INSERT INTO $tablecats VALUES (36, 'Interactive Stories', 0);
	INSERT INTO $tablecats VALUES (37, 'Link Checking', 0);
	INSERT INTO $tablecats VALUES (38, 'Link Indexing', 0);
	INSERT INTO $tablecats VALUES (39, 'Mailing List Managers', 0);
	INSERT INTO $tablecats VALUES (40, 'Miscellaneous', 0);
	INSERT INTO $tablecats VALUES (41, 'Multi-Level Marketing', 0);
	INSERT INTO $tablecats VALUES (42, 'Multimedia', 0);
	INSERT INTO $tablecats VALUES (43, 'Music Libraries', 0);
	INSERT INTO $tablecats VALUES (44, 'Networking Tools', 0);
	INSERT INTO $tablecats VALUES (45, 'News Publishing', 0);
	INSERT INTO $tablecats VALUES (46, 'Open Directory Project', 0);
	INSERT INTO $tablecats VALUES (47, 'Organizers', 0);
	INSERT INTO $tablecats VALUES (48, 'Polls and Voting', 0);
	INSERT INTO $tablecats VALUES (49, 'Portal Systems', 0);
	INSERT INTO $tablecats VALUES (50, 'Postcards', 0);
	INSERT INTO $tablecats VALUES (51, 'Quote Display', 0);
	INSERT INTO $tablecats VALUES (52, 'Randomizing', 0);
	INSERT INTO $tablecats VALUES (53, 'Redirection', 0);
	INSERT INTO $tablecats VALUES (54, 'Reviews and Ratings', 0);
	INSERT INTO $tablecats VALUES (55, 'Search Engines', 0);
	INSERT INTO $tablecats VALUES (56, 'Security Systems', 0);
	INSERT INTO $tablecats VALUES (57, 'Server Management', 0);
	INSERT INTO $tablecats VALUES (58, 'Site Mapping', 0);
	INSERT INTO $tablecats VALUES (59, 'Site Navigation', 0);
	INSERT INTO $tablecats VALUES (60, 'Site Recommendation', 0);
	INSERT INTO $tablecats VALUES (61, 'Software Repository', 0);
	INSERT INTO $tablecats VALUES (62, 'Tests and Quizzes', 0);
	INSERT INTO $tablecats VALUES (63, 'Top Sites', 0);
	INSERT INTO $tablecats VALUES (64, 'URL Submitters', 0);
	INSERT INTO $tablecats VALUES (65, 'Usenet Gateway', 0);
	INSERT INTO $tablecats VALUES (66, 'User Authentication', 0);
	INSERT INTO $tablecats VALUES (67, 'User Management', 0);
	INSERT INTO $tablecats VALUES (68, 'Vertical Markets', 0);
	INSERT INTO $tablecats VALUES (69, 'Virtual Communities', 0);
	INSERT INTO $tablecats VALUES (70, 'WAP and WML', 0);
	INSERT INTO $tablecats VALUES (71, 'Web Fetching', 0);
	INSERT INTO $tablecats VALUES (72, 'Web Hosting Tools', 0);
	INSERT INTO $tablecats VALUES (73, 'Web Rings', 0);
	INSERT INTO $tablecats VALUES (74, 'Web Search', 0);
	INSERT INTO $tablecats VALUES (75, 'Web Traffic Analysis', 0);
	INSERT INTO $tablecats VALUES (76, 'XML', 0);";
	$arsql = explode("\n", $catsblob);
	$ctcnt = count($arsql);
	for($x=0;$x<$ctcnt;$x++){
		$sql = $arsql[$x];
		$sql = str_replace("\n", "", $sql);
		$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());
	}
	print "The MySQL tables <b>$tablecats</b> and <b>$tablescripts</b> have been created and are ready for use! I highly suggest that you now delete <b>create_tables.php</b> before you continue.";
	exit;
}
?>

<p>This script will automatically create the MySQL table for PHP Script Index. 
  You MUST edit config.php before this script can be run. The 'Create Table' button 
  will appear below if you've successfully given all the info needed to do this. 
  Please review the info below just to be sure:</p>
<p>Database Host: <? print $sqlhost; ?><br>
  Database Login: <? print $sqllogin; ?><br>
  Database Password: <? print $sqlpass; ?><br>
  Database Name: <? print $sqldb; ?><br>
  Scripts Table Name: <? print $tablescripts; ?><br>
  Categories Table Name: <? print $tablecats; ?><br>
  <br>
  <br>
  If this is all correct, click the 'Create Table' button below. If the button 
  does not show below, it's because one of these fields are empty.</p>
<?
if ($sqlhost && $sqllogin && $sqldb && $tablescripts && $tablecats) print "<form name='form1' method='post' action='create_tables.php'>
  <div align='center'>
    <input type='hidden' name='create' value='1'>
    <input type='submit' value='Create Table!'>
  </div><br><br><b>Note: This script will not create the \"$sqldb\" database. It only creates the tables in the database. You may need to create the database yourself if \"$sqldb\" does not exist.</b>
</form>";
?>
