<?php



/* Written by Gerben Schmidt, http://scripts.zomp.nl */

function connectToDB() {
	global $link, $dbhost, $dbuser, $dbpass, $dbname;
	
	($link = mysql_connect("$dbhost", "$dbuser", "$dbpass")) || die("Couldn't connect to MySQL");

	mysql_select_db("$dbname", $link) || die("Couldn't open db: $dbname. Error if any was: ".mysql_error() );
}

// load multiple rows into array
function arrayMaker($result)
{
   $res_array = array();

   for ($count=0; $row = @mysql_fetch_array($result); $count++)
     $res_array[$count] = $row;

   return $res_array;
}

// this is a security fix for anyone not using magic quotes
if (!ini_get('magic_quotes_gpc'))
{
$_GET = array_map('addslashes', $_GET);
$_POST = array_map('addslashes', $_POST);
}


function tableExists($table_name)
         {
         $Table = mysql_query("show tables like '" . $table_name . "'");
         
         if(mysql_fetch_row($table) === false)
            return(false);
         
         return(true);
         }

// load system settings
function loadSettings($link,$table_settings){
$query = "SELECT * FROM $table_settings";
$result = mysql_query($query,$link) or die("Go to the <a href='install.php'>installer page</a> to install Zomplog.");
return mysql_fetch_array($result,MYSQL_ASSOC);
}

// load moblog settings
function loadMoblogSettings($link,$table_moblog){
$query = "SELECT * FROM $table_moblog";
$result = mysql_query($query,$link) or die("Could not load system settings.");
return mysql_fetch_array($result,MYSQL_ASSOC);
}


function firstwords($str,$wordcount) {
$words=preg_split('/([\s.,;]+)/',$str,$wordcount+1,PREG_SPLIT_DELIM_CAPTURE);
array_pop($words);
return(implode('',$words));
}



/* ------------------------ users -------------------------- */

// create new user
function newUser() {

	global $link, $table_users;

	$query="INSERT INTO $table_users (login, password, admin) VALUES('$_POST[login]', '$_POST[password]', '$_POST[admin]')";
	$result=mysql_query($query, $link) or die("Died inserting login info into db.  Error returned if any: ".mysql_error());

	return true;

} 

// load user information
function loadUser($username,$link,$table_users){
$query = "SELECT * FROM $table_users WHERE login = '$username'";
$result = mysql_query($query,$link) or die("Could not load userdata.");
return mysql_fetch_array($result,MYSQL_ASSOC);
}

// update user
function updateUser($userid,$link,$table_users) {
	$query="UPDATE $table_users SET password = '$_POST[password]', name = '$_POST[name]', email = '$_POST[email]', about = '$_POST[about]' WHERE id = '$userid'";
	$result=mysql_query($query, $link) or die("Died inserting data into db.  Error returned if any: ".mysql_error());
	return true;
} 


/* ------------------------ entries -------------------------- */

// create new entry
function newEntry($username,$image,$imagewidth,$imageheight,$date) {

	global $link, $table, $date;

	$query="INSERT INTO $table (title, text, extended, image, imagewidth, imageheight, fullwidth, mediafile, mediatype, catid, username, date) VALUES ('$_POST[title]', '$_POST[text]', '$_POST[extended]', '$image', '$imagewidth', '$imageheight', '$_POST[fullwidth]', '$_POST[mediafile]', '$_POST[mediatype]', '$_POST[catid]', '$username', '$date')";
	$result=mysql_query($query, $link) or die("Died inserting login info into db.  Error returned if any: ".mysql_error());

	return true;
} 

// update entry
function editEntry($link,$table,$image,$imagewidth,$imageheight,$thedate) {
	$query="UPDATE $table SET date = '$thedate', title = '$_POST[title]', text = '$_POST[text]', extended = '$_POST[extended]', image = '$image', imagewidth = '$imagewidth', imageheight = '$imageheight', fullwidth = '$_POST[fullwidth]', mediafile = '$_POST[mediafile]', mediatype = '$_POST[mediatype]', catid = '$_POST[catid]' WHERE id = '$_POST[id]'";
	$result=mysql_query($query, $link) or die("Died inserting data into db.  Error returned if any: ".mysql_error());
	return true;
} 

function loadEntries($link,$table) {

	$query = "SELECT * FROM $table ORDER BY id DESC";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	return arrayMaker($result,MYSQL_ASSOC);
}

function loadEntry($link,$table) {
$theid = 1; // if the parameter is not numeric (possible hacking attempt), the script defaults to 1
if(is_numeric($_GET['id'])){ 
$theid =  $_GET['id'];
}
	$query = "SELECT * FROM $table WHERE id = '$theid'";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	return mysql_fetch_array($result,MYSQL_ASSOC);
}

/* ------------------------ categories -------------------------- */

// create new category
function newCat($link,$table_cat) {

	$query="INSERT INTO $table_cat (name) VALUES ('$_POST[catname]')";
	$result=mysql_query($query, $link) or die("Died inserting category info into db.  Error returned if any: ".mysql_error());

	return true;
} 

// update categories
function changeCat($link,$table_cat) {
	$query="UPDATE $table_cat SET name = '$_POST[catname]' WHERE id = '$_POST[id]'";
	$result=mysql_query($query, $link) or die("Died inserting data into db.  Error returned if any: ".mysql_error());
	return true;
} 

function loadCat($link,$table_cat) {

	$query = "SELECT * FROM $table_cat";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	return arrayMaker($result,MYSQL_ASSOC);
}

// list single category
function loadOnecat($link,$table_cat) {
	$query = "SELECT * FROM $table_cat WHERE id = $_GET[catid]";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	return mysql_fetch_array($result,MYSQL_ASSOC);
}

/* ------------------------ comments -------------------------- */

// create new comment
function newComment($link,$table_comments,$date) {

// filter out html or javascript
$thename = htmlspecialchars($_POST[name], ENT_QUOTES);
$thecomment = htmlspecialchars($_POST[comment], ENT_QUOTES);
$theip = htmlspecialchars($_POST[ip], ENT_QUOTES);

	$query="INSERT INTO $table_comments (entry_id, name, comment, date, ip) VALUES ('$_GET[id]', '$thename', '$thecomment', '$date', '$theip')";
	$result=mysql_query($query, $link) or die("Died inserting category info into db.  Error returned if any: ".mysql_error());

	return true;
}

// update categories
function changeComment($id,$link,$table_comments) {
	$query="UPDATE $table_comments SET name = '$_POST[name]', comment = '$_POST[comment]' WHERE id = '$id'";
	$result=mysql_query($query, $link) or die("Died inserting data into db.  Error returned if any: ".mysql_error());
	return true;
} 

function loadComments($entryid,$link,$table_comments) {
	$query = "SELECT * FROM $table_comments WHERE entry_id = $entryid ORDER by id DESC";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	return arrayMaker($result,MYSQL_ASSOC);
}

function loadOneComment($id,$link,$table_comments) {
	$query = "SELECT * FROM $table_comments WHERE id = $id";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	return mysql_fetch_assoc($result);
}

function loadAllComments($link,$table_comments) {
	$query = "SELECT * FROM $table_comments ORDER by id DESC";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	return arrayMaker($result,MYSQL_ASSOC);
}


/* ------------------------ other -------------------------- */

/* errors */

function displayErrors($messages) {
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style='border: #CC0000 dotted; border-width: 1px 1px 1px 1px; padding: 2px 2px 2px 2px;' class="text">
  <tr>
    <td>There were problems with the previous action.  Following is a list of the error messages generated:<ul>
	<?
		foreach($messages as $msg){
		print("<li>$msg</li>\n");
	}
	print("</ul>\n");
	?>
	
	</td>
  </tr>
  </table>
  <br />
  <?
}

function displayMessage($message) {
global $lang_error1, $lang_error2, $lang_error3, $lang_error4, $lang_error5, $lang_error6, $lang_error7, $lang_error8, $lang_error9, $lang_error10, $lang_error11, $lang_error12, $lang_error13;
switch($message){
	
	case 1:
	$message = "$lang_error1";
	break;
	
	case 2:
	$message = "$lang_error2";
	break;

	case 3:
	$message = "$lang_error3";
	break;

	case 4:
	$message = "$lang_error4";
	break;
	
	case 5:
	$message = "$lang_error5";
	break;
	
	case 6:
	$message = "$lang_error6";
	break;
	
	case 7:
	$message = "$lang_error7";
	break;
	
	case 8:
	$message = "$lang_error8";
	break;
	
	case 9:
	$message = "$lang_error9";
	break;
	
	case 10:
	$message = "$lang_error10";
	break;
	
	case 11:
	$message = "$lang_error11";
	break;
	
		case 12:
	$message = "$lang_error12";
	break;
	
			case 13:
	$message = "$lang_error13";
	break;
	}
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style='border: #009900 dotted; border-width: 1px 1px 1px 1px; padding: 2px 2px 2px 2px;' class="text">
  <tr>
    <td>
	<?
echo "$message";		
	?>
	</td>
  </tr>
  </table>
  <br />
  <?
}



// generate random id
function randomId(){
//set the random id length
$random_id_length = 6;

//generate a random id encrypt it and store it in $rnd_id
$rnd_id = crypt(uniqid(rand(),1));

//to remove any slashes that might have come
$rnd_id = strip_tags(stripslashes($rnd_id));

//Removing any . or / and reversing the string
$rnd_id = str_replace(".","",$rnd_id);
$rnd_id = strrev(str_replace("/","",$rnd_id));

//finally I take the first 6 characters from the $rnd_id
$rnd_id = substr($rnd_id,0,$random_id_length);
return $rnd_id;
}

/* ------------------------ sessions -------------------------- */

function checkLoggedIn($status){
	
	switch($status){
	
		case "yes":
			if(!$_SESSION["loggedIn"]){
				header("Location: ../login.php");
				exit;
			}
			break;
			
		case "no":
			if($_SESSION["loggedIn"]){
				header("Location: admin/members.php?".session_name()."=".session_id());
			}
			break;			
	}	
	
	return true;
} 

function checkPass($login, $password) {
	
	global $link, $table_users;
	
	$query="SELECT login, password FROM $table_users WHERE login='$login' and password='$password'";
	$result=mysql_query($query, $link)
		or die("checkPass fatal error: ".mysql_error());
	
	if(mysql_num_rows($result)==1) {
		$row=mysql_fetch_array($result);
		return $row;
	}
	
	return false;
} 

function cleanMemberSession($login, $password) {
	
	$_SESSION["login"]=$login;
	$_SESSION["password"]=$password;
	$_SESSION["loggedIn"]=true;
} 

function flushMemberSession() {
	
	unset($_SESSION["login"]);
	unset($_SESSION["password"]);
	unset($_SESSION["loggedIn"]);

	session_destroy();

	return true;
} 

function doCSS() {
	
	?>
<style type="text/css">
body{font-family: Arial, Helvetica; font-size: 10pt}
h1{font-size: 12pt}
</style>
	<?php
} 

function field_validator($field_descr, $field_data, 
  $field_type, $min_length="", $max_length="", 
  $field_required=1) {
	
	global $messages;
	
	
	if(!$field_data && !$field_required){ return; }

	$field_ok=false;

	$email_regexp="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|";
	$email_regexp.="(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$";

	$data_types=array(
		"email"=>$email_regexp,
		"digit"=>"^[0-9]$",
		"number"=>"^[0-9]+$",
		"alpha"=>"^[a-zA-Z]+$",
		"alpha_space"=>"^[a-zA-Z ]+$",
		"alphanumeric"=>"^[a-zA-Z0-9]+$",
		"alphanumeric_space"=>"^[a-zA-Z0-9 ]+$",
		"string"=>""
	);
	
	if ($field_required && empty($field_data)) {
		$messages[] = "$field_descr is a required field.";
		return;
	}
	
	if ($field_type == "string") {
		$field_ok = true;
	} else {
		
		$field_ok = ereg($data_types[$field_type], $field_data);		
	}
	
	
	if (!$field_ok) {
		$messages[] = "Please enter a valid $field_descr.";
		return;
	}
	
	
	if ($field_ok && $min_length) {
		if (strlen($field_data) < $min_length) {
			$messages[] = "$field_descr is invalid, it should be at least $min_length character(s).";
			return;
		}
	}
	
	
	if ($field_ok && $max_length) {
		if (strlen($field_data) > $max_length) {
			$messages[] = "$field_descr is invalid, it should be less than $max_length characters.";
			return;
		}
	}
}

