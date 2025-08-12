<?
/*commadmin.php - version 1.2c - This is the administration module for
	'comments.php', and doesn't do much by itself. The complete 'Comments'
		guestbook distribution is available at http://bry.kicks-ass.org or
			http://www.bryancentral.com - bryhal@rogers.com - July 24 2005 */
error_reporting(0);	
session_start();
if (!isset ($_SESSION['auth'])) {
	die('<p>You are not authorized to view this page.</p>'); }
$dbuser = $_SESSION['dbuser'];
$dbpassword = $_SESSION['dbpassword'];
$dbserver = $_SESSION['dbserver'];
$admin_name = $_SESSION['admin_name'];
$dbname = $_SESSION['dbname'];
$tablename = $_REQUEST['table'];
$name = $_SESSION['name'];
if (strcmp(trim($name),trim($admin_name)) != 0) {
	die('<p>You are not authorized to view this page.</p>'); }

//FUNCTIONS*********************************************************************

function dbconnect() {
	global $dbserver, $dbuser, $dbpassword, $dbname;
	$dbcnx = @mysql_connect("$dbserver","$dbuser","$dbpassword");
	if (!$dbcnx) {
	die( '<p>Unable to connect to the database server.</p><p>This page can only be entered through the main script.</p>' );
	}
	if (! @mysql_select_db($dbname) ) {
	die( '<p>Unable to locate the comments database.</p>'.var_dump($_GET, $_SESSION, $GLOBALS));
	}
}

function getcomments() {
	global $tablename, $row, $result;
	$result = @mysql_query("SELECT name, comment, date, ip, id FROM $tablename order by date desc"); 
	if (!$result) {
	die('<p>Error performing query: ' . mysql_error() . $result.'</p>');
	}
}

function geteditcomment() {
	global $tablename, $row, $result, $id, $comment;
	$result = @mysql_query("SELECT comment, id FROM $tablename where id = $id "); 
	if (!$result) {
	die('<p>Error performing query: ' . mysql_error() .'</p>');
	}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<style type="text/css">
<!--
body {
	margin: 5px;
	background-color: #EFEFEF;
}
h1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #BB2222;
	font-size: 12px;
	font-weight: bold;
	text-align: center;
	margin: 0px;
}
table {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	background-color: #DDDDDD;
	margin-top: 1px;
	width: 100%;
	padding-left: 8px;
}
p {
	font-family: Arial, Helvetica, sans-serif;
	color: #550000;
	font-size: 12px;
	font-weight: bold;
	text-align: center;
}	

a	{
	text-decoration:none
}
textarea {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #333333;
	margin: 2px;
	padding: 3px;
	height: 80px;
	width: 90%;
	border: thin solid #999999;
}
a:link {
	font-family: Verdana, Trebuchet, sans-serif;
	color: #BB2222;
	font-weight: bold;
}
a:active {
	font-family: Verdana, Trebuchet, sans-serif;
	color: #BB2222;
	font-weight: bold;
}
a:hover {
	font-family: Verdana, Trebuchet, sans-serif;
	color: #00AA00;
	font-weight: bold;
}

a:visited {
	font-family: Verdana, Trebuchet, sans-serif;
	color: #00AA00;
	font-weight: bold;
}

form {
	text-align: center;
}
.submita {
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	background-color: #CCCCCC;
	vertical-align: top;
	width: 80%;
	margin: 0;
	float: none;
	height: 24px;
}
-->
</style>
<!-- This code found at www.mediacollege.com - Textarea character counter -->
<script language="javascript" type="text/javascript">
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}
</script>
</head>
<body>

<?
if (isset ($_POST['newcomment'])) {
		dbconnect();
		$newcomment = addslashes($_POST['newcomment']);
		$newcomment = wordwrap($newcomment, 40,' ', 1);
		$id = $_REQUEST['id'];
		$tablename = trim($_POST['tablepost']);
		$sql = ("UPDATE $tablename SET comment = '$newcomment' where id = $id ");        
		if (mysql_query($sql)) {
		echo '<p>Comment modified.</p>';
		} else {
		exit('<p>Error updating comment: ' . mysql_error() .$tablename. '</p>');
		unset ($_POST['newcomment']);
    	} 

}
$id = trim($_REQUEST['id']);
$action = trim($_REQUEST['action']);
//$tablename = trim($_REQUEST['table']);

if ($action == "edit") {
	dbconnect();
	geteditcomment();
	$row = mysql_fetch_array($result);
	$comment = htmlspecialchars(stripslashes($row['comment']));
	$id = $row['id'];
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<p>Edit the comment:<br />
<textarea name="newcomment" id="newcomment" rows="7" cols="40" onKeyDown="limitText(this.form.newcomment,this.form.countdown,250);" 
onKeyUp="limitText(this.form.newcomment,this.form.countdown,250);">
<?php echo $comment; ?> </textarea><br />
<font size="1">(Maximum characters: 250)<br>
You have <input readonly type="text" name="countdown" size="3" value="250"> characters left.</font>
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="tablepost" value="<?php echo $tablename;?>">
<input type="submit" class="submita" value="Save Changes">
</form> 
<?

exit;
}
//}

if (isset ($_REQUEST['id']) && ($_GET['action'] == "delete")) {
	dbconnect();
	$todelete = @mysql_query("Delete from $tablename where id = $id");
	if (!$todelete) {
	die('<p>Error performing query: ' . mysql_error() . '</p>');
	}
	unset ($_REQUEST['id']);
}

echo ('<h1>Admin for table '.$tablename.'<br> - X to Delete - <font size="3px">&bull;</font> to Edit -</h1>'); 
 

?>

<form>
<input type="button" class="submita" onClick="opener.location.reload(); window.close();" value="Close Window and Refresh List">
</form>
<?

//}
dbconnect();
getcomments();

// Display the text of each comment in a table
while ( $row = mysql_fetch_array($result) ) {
  	$lname = htmlspecialchars(stripslashes($row['name']));
  	$comment = htmlspecialchars(stripslashes($row['comment']));
 	echo('<table><tr><td width="12px" title="Delete"><a href="'.$_SERVER['PHP_SELF'].'?id='.$row['id'].'&action=delete&table='.$tablename.'" onclick="return confirm(\'Delete this message?\');">' .'X '.'</a></td><td width="12px" title="Edit"><a href="'.$_SERVER['PHP_SELF'].'?id='.$row['id'].'&action=edit&table='.$tablename.'">'.' <font size="3px">&bull;</font>'.'</a></td><td width="60px">'. $lname . '</td><td>' . $comment . '</td></tr></table>') ;
	//. $row['date']  . ' ' . $row['ip'] . ' ' --- removed from display line for now
 	}

?>

</body>
</html>	
