<?

if(isset($_GET['step'])) { $step = $_GET['step']; } elseif(isset($_POST['step'])) { $step = $_POST['step']; } else { $step = 0; }

$head = "
<html>
<head>
<title>wFAQ Installer</title>
<style type='text/css'>

body {
font-family: \"Palatino Linotype\", georgia, verdana, sans-serif;
font-size: 11pt;
line-height: 16pt;
color: #333333;
}

h2 {
margin-bottom: 5px;
}

input.button {
background: #336699;
color: #FFFFFF;
padding: 3px;
font-weight: bold;
font-family: arial, verdana, tahoma, sans-serif;
font-size: 10pt;
}

input.text {
font-family: arial, verdana, sans-serif;
font-size: 9pt;
}

form {
margin: 0px;
}

a:link { color: #336699; }
a:visited { color: #336699; }
a:hover { color: #3399FF; }

</style>
</head>
<body>
";


$foot = "
</body>
</html>
";



echo $head;








if($step == 0) {
echo "
<h2>wFAQ Installer</h2>
Thank you for choosing wFAQ. Installation is very simple - you will follow these steps:
<ol>
<li>Submit your MySQL database information.</li>
<li>Choose a username and password.</li>
<li>Delete this file (faq_install.php).</li>
</ol>
Before you begin, be sure that you have an available MySQL database on your server.
If you are unsure about your MySQL information, contact your hosting provider. Also,
be sure that you have followed the uploading instructions provided in readme.txt and that
you have read your license agreement in license.txt. Installation of this script signifies
your acceptance and understanding of the terms given in this agreement.
<br><br>
<form action='faq_install.php' method='POST'>
<input type='submit' class='button' value='Continue...'>
<input type='hidden' name='step' value='1'>
</form>
";
}





if($step == 1) {
echo "
<h2>MySQL Information</h2>
Please provide your MySQL database information.
<br><br>
<table cellpadding='3' cellspacing='0'>
<form action='faq_install.php' method='POST'>
<tr>
<td align='right'>Hostname:</td>
<td><input type='text' class='text' size='25' value='localhost' name='mysql_hostname' maxlength='100'></td>
</tr>
<tr>
<td align='right'>Database Name:</td>
<td><input type='text' class='text' size='25' name='mysql_database' maxlength='100'></td>
</tr>
<tr>
<td align='right'>Username:</td>
<td><input type='text' class='text' size='25' name='mysql_username' maxlength='100'></td>
</tr>
<tr>
<td align='right'>Password:</td>
<td><input type='password' class='text' size='25' name='mysql_password' maxlength='100'></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>
<input type='submit' class='button' value='Continue...'>
<input type='hidden' name='step' value='2'>
</td>
</table>
";
}






if($step == 2) { 
$mysql_hostname = $_POST['mysql_hostname'];
$mysql_database = $_POST['mysql_database'];
$mysql_username = $_POST['mysql_username'];
$mysql_password = $_POST['mysql_password'];

if($mysql_hostname == "" OR $mysql_database == "" OR $mysql_username == "" OR $mysql_password == "") {
echo "
<h2>Error:</h2>
You must provide a hostname, database name, username, and password.
<br><br>
<form action='faq_install.php' method='POST'>
<input type='submit' class='button' value='Try Again'>
<input type='hidden' name='step' value='1'>
</form>
";
echo $foot;
exit();
}

// ATTEMPT CONNECTION
$link = @mysql_connect($mysql_hostname, $mysql_username, $mysql_password);
if(!$link) { 
echo "
<h2>Error:</h2>Could not connect to MySQL server. 
Please check your login information and <a href='#' onClick='history.go(-1)'>try again</a>. 
MySQL returned this error:<br><br>";
$error = mysql_error();
echo "<i>$error</i> $foot";
exit();
}

// ATTEMPT DATBASE SELECTION
$db_selected = @mysql_select_db($mysql_database, $link);
if(!$db_selected) { 
echo "
<h2>Error:</h2>
Connected to MySQL server, but cannot select database. 
Check your login information, be sure that this MySQL user has been added to this database, and <a href='#' onClick='history.go(-1)'>try again</a>.
MySQL returned this error:<br><br>";
$error = mysql_error();
echo "<i>$error</i> $foot";
exit();
}



// DROP TABLES IF THEY ALREADY EXIST
mysql_query("DROP TABLE IF EXISTS `faq_admin`");
mysql_query("DROP TABLE IF EXISTS `faq_categories`");
mysql_query("DROP TABLE IF EXISTS `faq_questions`");


// CREATE faq_admin
mysql_query("
CREATE TABLE `faq_admin` (
  `username` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `showcats` int(1) NOT NULL default '1',
  `shownumbers` int(1) NOT NULL default '1',
  `header` text NOT NULL,
  `footer` text NOT NULL
)
");



// CREATE faq_categories
mysql_query("
CREATE TABLE `faq_categories` (
  `c_id` int(8) NOT NULL auto_increment,
  `c_order` int(8) NOT NULL default '0',
  `category` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`c_id`)
)
");


//INSERT DEFAULT INTO faq_categories
mysql_query("INSERT INTO `faq_categories` VALUES (1, 1, 'First Category')");


// CREATE faq_questions
mysql_query("
CREATE TABLE `faq_questions` (
  `q_id` int(8) NOT NULL auto_increment,
  `c_id` int(8) NOT NULL default '0',
  `q_order` int(8) NOT NULL default '0',
  `question` varchar(255) NOT NULL default '',
  `answer` text NOT NULL,
  PRIMARY KEY  (`q_id`)
)
");


//INSERT DEFAULT INTO faq_questions
mysql_query("INSERT INTO `faq_questions` VALUES (1, 1, 1, 'Question 1', 'Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. ')");
mysql_query("INSERT INTO `faq_questions` VALUES (2, 1, 2, 'Question 2', 'Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. ')");
mysql_query("INSERT INTO `faq_questions` VALUES (3, 1, 3, 'Question 3', 'Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. Replace this with your text. Here is the answer to this question. ')");



// PREPARE PRESET HTML HEADER AND FOOTER
$header_html = "
<html>
<head>
<title>Frequently Asked Questions</title>
<style type=\'text/css\'>
body {
font-family: verdana, arial, sans-serif;
font-size: 9pt;
line-height: 13pt;
color: #333333;
}

h1 {
font-size: 13pt;
}

h2 {
font-size: 10pt;
margin-bottom: 2px;
}
</style>
</head>
<body>

<h1>Frequently Asked Questions</h1>
";

$footer_html = "
</body>
</html>
";




// INSERT ADMIN ROW
mysql_query("INSERT INTO faq_admin (username, password, showcats, shownumbers, header, footer) VALUES ('', '', 1, 1, '$header_html', '$footer_html')") or die(mysql_error());




// CREATE CONFIG FILE
$filename = "faq_config.php";
$somecontent = "<?\n// SET MYSQL INFORMATION\n\$mysql_host = \"$mysql_hostname\";\n\$mysql_database = \"$mysql_database\";\n\$mysql_username = \"$mysql_username\";\n\$mysql_password = \"$mysql_password\";\n\n// CONNECT TO MYSQL DATABASE\n\$mysql_connect = mysql_connect(\"\$mysql_host\", \"\$mysql_username\", \"\$mysql_password\");\nmysql_select_db(\"\$mysql_database\");\n\n// SELECT ADMIN INFO\n\$admin_info = mysql_fetch_assoc(mysql_query(\"SELECT * FROM faq_admin\"));?>";
$handle = fopen($filename, 'w+');
fwrite($handle, $somecontent);
fclose($handle);




$step = 3;
}



if($step == 3) {

echo "
<h2>Username and Password</h2>
You will need a username and password to access your FAQ manager. Please specify the
username and password you desire in the fields below.
<br><br>
<table cellpadding='3' cellspacing='0'>
<form action='faq_install.php' method='POST'>
<tr>
<td align='right'>Username:</td>
<td><input type='text' class='text' size='25' name='username' maxlength='100'></td>
</tr>
<tr>
<td align='right'>Password:</td>
<td><input type='password' class='text' size='25' name='password' maxlength='100'></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>
<input type='submit' class='button' value='Continue...'>
<input type='hidden' name='step' value='4'>
</td>
</table>
";
}


if($step == 4) {
$username = $_POST['username'];
$password = $_POST['password'];

if(str_replace(" ", "", $username) == "" OR str_replace(" ", "", $password) == "") {
echo "
<h2>Error:</h2>
You must provide a username and password.
<br><br>
<form action='faq_install.php' method='POST'>
<input type='submit' class='button' value='Try Again'>
<input type='hidden' name='step' value='3'>
</form>
";
echo $foot;
exit();
}

include "faq_config.php";
mysql_query("UPDATE faq_admin SET username='$username', password='$password'");


echo "
<h2>Installation Complete</h2>
Congratulations! Your installation has completed successfully. For your security, you
should now delete this file (faq_install.php) from your server.
<br><br>
You can now login to your <b><a href='faq_admin.php'>FAQ manager</a></b>.


";

}






echo $foot;
?>








