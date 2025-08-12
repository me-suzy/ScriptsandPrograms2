
<?php

include ("config.php");

if (!isset($start_news))
	$start_news = 0;

function ch_password($posted_password){

global $db_host, $db_user, $db_password, $db_name;

$db = mysql_connect($db_host, $db_user, $db_password);
if ($db == FALSE)
	die ("Errore Connecting database.");

mysql_select_db($db_name, $db)
	or die ("Error selecting database");
	
$query = "DELETE FROM RTNews_auth WHERE _password != ''";
mysql_query($query, $db);

$enc = md5($posted_password);
$query = "INSERT INTO RTNews_auth (_password) VALUES ('$enc')";

mysql_query($query, $db);

}


function list_news(){

global $db_host, $db_user, $db_password, $db_name;

$db = mysql_connect($db_host, $db_user, $db_password);
if ($db == FALSE)
	die ("Errore Connecting database.");

mysql_select_db($db_name, $db)
	or die ("Error selecting database");
	
$query = "SELECT id,_text FROM RTNews ORDER BY id DESC LIMIT 0,20";

$result = mysql_query($query, $db);

print '<div align=left> <form name="news_list" method="post" action="">
		<select name="news_to_del" size="1">';

// display the news
while ($row = mysql_fetch_array($result)){
	print '<option value="';
    echo $row[id];
	print '">';
	echo stripslashes(substr($row[_text],0,100));
	print '</option>';
	}
	
print'</select> <input type="hidden" name="delete_news" value="true">
      <input type="submit" value="delete"> 
	  </form></div>';
}

function delete_news($id_to_del){

global $db_host, $db_user, $db_password, $db_name;

$db = mysql_connect($db_host, $db_user, $db_password);
if ($db == FALSE)
	die ("Errore Connecting database.");

mysql_select_db($db_name, $db)
	or die ("Error selecting database");

$query = "DELETE FROM RTNews WHERE id = '$id_to_del'";

mysql_query($query, $db);

}


function read_news(){

global $db_host, $db_user, $db_password, $db_name, $news_template, $start_news, $step_news;

$db = mysql_connect($db_host, $db_user, $db_password);
if ($db == FALSE)
	die ("Errore Connecting database.");
	
mysql_select_db($db_name, $db)
		or die ("Error selecting database");
		
$query = "SELECT id,_author,_date,_mail,_title,_text FROM RTNews ORDER BY id DESC LIMIT $start_news,$step_news";

$result = mysql_query($query, $db);
while ($row = mysql_fetch_array($result)){
$temp_array = explode("%%", $news_template);
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
	case "TITLE":
	echo stripslashes($row[_title]);	
	break;
	case "TEXT":
	echo stripslashes($row[_text]);	
	break;
	default:
	echo stripslashes($temp_array[$number]);
	} 
	$number++;
	}
}

$query = "SELECT count(*) AS tot FROM RTNews";

$result = mysql_query($query, $db);
$row = mysql_fetch_array($result);

$pages = intval(($row[tot]-1) / $step_news)+1;
if ($pages > 1)
{
	echo "Page: ";
	for ($i=0; $i<$pages AND $i<20; $i++)
	{ $start_page = $i * $step_news;
	echo "<a href=index.php?start_news=$start_page>" . ($i+1) . "</a> ";
	}
}

mysql_close($db);
}


function news_form(){
print '<form name="sign_news_form" method="post" action="">
<br>
Author:
<input type=text size=40 name=author><br>
<br>
Title:
<input type=text size=40 name=title><br>
<br>
E-mail:
<input type=text size=40 name=mail><br>
<br>
Text:<br>
<textarea cols=60 rows=10 name=text></textarea><br>
<input type="hidden" name="write_news" value="true">
<input name="new_news_form_submit" type="submit">
<input name="reset_news_form" type="reset">
</form>';

global $news_error;

if ($news_error)
	echo "All fields are required!";

}

function new_news(){

global $db_host, $db_user, $db_password, $db_name, $author, $mail, $text, $title;

$author = addslashes(stripslashes($author));
$title = addslashes(stripslashes($title));
$mail = addslashes(stripslashes($mail));
$text = addslashes(stripslashes($text));
$date = date("d-m-y");


$db = mysql_connect($db_host, $db_user, $db_password);
if ($db == FALSE)
	die ("Errore Connecting database.");
	
mysql_select_db($db_name, $db)
		or die ("Error selecting database");

$query = "INSERT INTO RTNews (_author, _mail, _date, _title, _text) VALUES ('$author', '$mail', '$date','$title', '$text')";

if (!mysql_query($query, $db))
	die ("Error inserting entry in db");

mysql_close($db);


}

?>
