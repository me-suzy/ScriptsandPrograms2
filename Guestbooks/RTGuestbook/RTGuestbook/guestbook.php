
<?php

include ("config.php");

if (!isset($start_guestbook))
	$start_guestbook = 0;
	
if($write_guestbook)
{
	if	($author != "" && $mail != "" && $text != "" && $guestsigned == "")
	{
		setcookie("guestsigned", "Toldo.info/Roberto guestbook", time()+120 , "/", $SERVER_NAME);
		sign_guestbook();
	}
	else if($guestsigned != "")
		echo "Spam Protection: Guestbook already signed, wait two mins...";
	else
		echo "All fields are required!";
}

function ch_password_guestbook($posted_password){

global $db_host, $db_user, $db_password, $db_name;

$db = mysql_connect($db_host, $db_user, $db_password);
if ($db == FALSE)
	die ("Errore Connecting database.");

mysql_select_db($db_name, $db)
	or die ("Error selecting database");
	
$query = "DELETE FROM RTGuestbook_auth WHERE _password != ''";
mysql_query($query, $db);

$enc = md5($posted_password);
$query = "INSERT INTO RTGuestbook_auth (_password) VALUES ('$enc')";

mysql_query($query, $db);

}


function list_guestbook(){

global $db_host, $db_user, $db_password, $db_name;

$db = mysql_connect($db_host, $db_user, $db_password);
if ($db == FALSE)
	die ("Errore Connecting database.");

mysql_select_db($db_name, $db)
	or die ("Error selecting database");
	
$query = "SELECT id,_author,_date,_mail,_text FROM RTGuestbook ORDER BY id DESC LIMIT 0,20";

$result = mysql_query($query, $db);

print '<div align=left> <form name="guestbook_list" method="post" action="admin.php">
		<select name="guestbook_to_del" size="1">';

// display the guestbook
while ($row = mysql_fetch_array($result)){
	print '<option value="';
    echo $row[id];
	print '">';
	echo stripslashes(substr($row[_text],0,100));
	print '</option>';
	}
	
print'</select> <input type="hidden" name="delete_guestbook" value="true">
      <input type="submit" value="delete"> 
	  </form></div>';
}

function delete_guestbook($id_to_del){

global $db_host, $db_user, $db_password, $db_name;

$db = mysql_connect($db_host, $db_user, $db_password);
if ($db == FALSE)
	die ("Errore Connecting database.");

mysql_select_db($db_name, $db)
	or die ("Error selecting database");

$query = "DELETE FROM RTGuestbook WHERE id = '$id_to_del'";

mysql_query($query, $db);

}


function read_guestbook(){

global $db_host, $db_user, $db_password, $db_name, $guestbook_template, $start_guestbook, $step_guestbook;

$db = mysql_connect($db_host, $db_user, $db_password);
if ($db == FALSE)
	die ("Errore Connecting database.");
	
mysql_select_db($db_name, $db)
		or die ("Error selecting database");
		
$query = "SELECT id,_author,_date,_mail,_text FROM RTGuestbook ORDER BY id DESC LIMIT $start_guestbook, $step_guestbook";

$result = mysql_query($query, $db);
while ($row = mysql_fetch_array($result)){
$temp_array = explode("%%", $guestbook_template);
$number = 0;
while( $number < count($temp_array) )
	{	
	switch($temp_array[$number])
	{
	case "DATE":
	echo stripslashes($row[_date]);
	break;
	case "AUTHOR":
	echo stripslashes($row[_author]);	
	break;
	case "MAIL":
	echo stripslashes($row[_mail]);	
	break;
	case "TEXT":
	echo stripslashes($row[_text]);	
	break;
	default:
	print($temp_array[$number]);
	} 
	$number++;
	}
}

$query = "SELECT count(*) AS tot FROM RTGuestbook";

$result = mysql_query($query, $db);
$row = mysql_fetch_array($result);

$pages = intval(($row[tot]-1) / $step_guestbook)+1;
if ($pages > 1)
{
	echo "Page: ";
	for ($i=0; $i<$pages AND $i<20; $i++)
	{ $start_page = $i * $step_guestbook;
	echo "<a href=$page_guestbook?start_guestbook=$start_page>" . ($i+1) . "</a> ";
	}
}

mysql_close($db);
}


function guestbook_form(){
print '<form name="sign_guestbook_form" method="post" action="">
<br>
Author:
<input type=text size=40 name=author><br>
<br>
E-mail:
<input type=text size=40 name=mail><br>
<br>
Text:<br>
<textarea cols=60 rows=10 name=text></textarea><br>
<input type="hidden" name="write_guestbook" value="true">
<input name="sign_guestbook_form_submit" type="submit">
<input name="reset_guestbook_form" type="reset">
</form>';

}

function sign_guestbook(){

global $db_host, $db_user, $db_password, $db_name, $author, $mail, $text;

$author = htmlspecialchars(addslashes(stripslashes($author)));
$mail = htmlspecialchars(addslashes(stripslashes($mail)));
$text = htmlspecialchars(addslashes(stripslashes($text)));
$date = date("d-m-y");


$db = mysql_connect($db_host, $db_user, $db_password);
if ($db == FALSE)
	die ("Errore Connecting database.");
	
mysql_select_db($db_name, $db)
		or die ("Error selecting database");

$query = "INSERT INTO RTGuestbook (_author, _mail, _date, _text) VALUES ('$author', '$mail', '$date', '$text')";

if (!mysql_query($query, $db))
	die ("Error inserting entry in db");

mysql_close($db);


}

?>
