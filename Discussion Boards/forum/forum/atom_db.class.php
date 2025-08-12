<?php

session_start(); //for the login.

/*

Functions List
==============
getClientStats();
selectDB($file, $user, $pass);
reBuild();
addRow($db, array($recs));
editRow($db, $row, array($recs));
deleteRow($db, $row);
createDB($dbname, $user, $pass, $name, $mail);
createTable($name, array($records));
deleteTable($dbname);
query($dbname, $tableid, $string);
export($db, $saveas, $delimitor [, $usetable ]);
import($db, $dbname, $delimitor);

Login Info
==============
Note: You must start your own form method so it will post the info.
When loggin in, the username and password are stored in sesstions.
USERNAME: $_SESSION["_LOGUN"];
PASSWORD: $_SESSION["_LOGPW"];

login_db("users database name", "username field", "md5 password field");
login_user(); //username text field
login_pass(); //password text field
login_submit();  //submit button for login form
login_verify($valid logins, $invalid logins);  //verify the users input
is_logged_in($user, $pass); if is logged in
login_profile(); //retrieves the users info in an array.


Notice: inputting anything into the database requires a reBuild(); to take affect
        outputting anything like export will save automatically. without prior notice.
        import will overwrite any existing db's without warning.

compress(array($file));
readDir($dir, array($ignore));
getFileSize($file[, $suffix]);
getEditTime($file[, date]);

*/

class sdb {
  var $version = "1.1.4+";
  var $debug = false;
  var $constatus = true;
  var $data = array();
  var $logged_db = null;
  var $buildlock = "true";

  var $badinput = array("<" => "&lt;",
                       ">" => "&gt;",
                       "\r\n" => "<br>",
                       "DBNAME=" => "dbname=");

  
  function error($msg){
    if ($this->debug == true){
    echo ("<font size=\"2\" face=\"Verdana\"><b>Atom_DB Service Message:</b> $msg</font><br /><br />\n");
    };
  } //end function error
  
  function is_connected(){
    if ($this->logged_db == true){
      return true;
    } else {
    $this->error("You are not properly connected to the database!");
    }; //end if
  } //end is_connected


  function conn_stats($msg){
    if ($this->constatus == true){
    echo ("<font size=\"2\" face=\"Verdana\"><b>Atom_DB Connection Status:</b> $msg</font><br /><br />\n");
    };
  } //end function error
  
  function getClientStats(){
$ip = $_SERVER["REMOTE_ADDR"];
if (eregi("MSIE", $_SERVER["HTTP_USER_AGENT"])){
  $os = "Windows Internet Explorer";
} elseif (eregi("Firefox", $_SERVER["HTTP_USER_AGENT"])){
  $os = "Firefox";
} else {
  $os = "Unknown Browser";
};
$url = $_SERVER["REQUEST_URI"];
if (isset($_SERVER["HTTP_REFERER"])){
$refer = $_SERVER["HTTP_REFERER"];
} else {
  $refer = "false";
};

$this->data["_CLIENT"] = array("IP" => "$ip",
                                      "BROWSER" => "$os",
                                      "URL" => "$url",
                                      "REFERER" => "$refer");
} //end function getClientStats
  
function selectDB($dbl, $user, $pass){
$db = "$dbl";
$this->dbfile = "$dbl";

$file = fopen("$db", "r");
$file = fread($file, filesize("$db"));
$file = explode("-----ADMIN_CFG-----", $file);

//admin config
$admin = $file["0"];
$admin = explode("\"", $admin);
$this->admin_un = $admin["1"];  //username
$this->admin_pw = $admin["3"];  //password
$this->admin_na = $admin["5"];  //name
$this->admin_ma = $admin["7"];  //email

if ($user != $this->admin_un or $pass != $this->admin_pw){
  $this->conn_stats("Failed to connect to the database ($dbl) with username: '$user' and pass '$pass'");
  $this->logged_db = false;
} else {

  $this->logged_db = true;

//db config
$db = $file["1"];

$db = explode("DBNAME=", $db);
unset($db["0"]);



#print_r($db); //un altered DB by not exploding \r\n

for ($i = 1; $i <= count($db); $i++){

  $id[$i] = explode("\r\n", trim($db["$i"]));
  $name = $id[$i][0];

  for ($a = 0; $a < count($id[$i]); $a++){
  $dbi[$name][] = explode("<~>", $id[$i][$a]);
  };

}; //end $i


$this->data["_DB"] = @$dbi;

#print_r($dbi);
}; //end else


#print_r($this->data["db"]);  //un altered DB by not exploding the delimitor
} //end function selectDB

function reBuild($final="false"){

if ($this->buildlock == "true" && $final == "true" or $this->buildlock == "false" && $final == "false"){
if ($this->is_connected()){
if (!file_exists($this->dbfile.".tmp")){
$file = trim($this->dbfile.".tmp");
$old = trim($this->dbfile);

$new = fopen("$file", "w");
fwrite($new, "ADMIN_UN = \"$this->admin_un\"\r\nADMIN_PW = \"$this->admin_pw\"\r\nADMIN_NA = \"$this->admin_na\"\r\nADMIN_MA = \"$this->admin_ma\"\r\n-----ADMIN_CFG-----\r\n");

foreach ($this->data["_DB"] as $dbname){

$selected = trim($dbname[0][0]);

#print_r($dbname);

fwrite($new, "DBNAME=$selected\r\n");

foreach ($this->data["_DB"]["$selected"] as $row){

  $id = implode("<~>", $row);
  if ($id != $dbname[0][0]){
  fwrite($new, "$id\r\n");
  };

};

fwrite($new, "\r\n");

};


fclose($new);
unlink($old);
rename($file, $old);

} else {

  $this->conn_stats("Cannot add information into db. DBLoad too high!");

}; //end if

} else {
  $this->conn_stats("Cannot reBuild DB, You are not connected to one, Sorry!");
  }; //end is_connected

}; //end if.

} //end function reBuild

function addRow($db, $recs=array(), $filter="true"){
  if (!$this->is_connected()){
    $this->conn_stats("Unable to append records due to no database selected!");
  } else {
  

  $id = count($this->data["_DB"]["$db"]);

    foreach ($recs as $new_value){

    if ($filter == "true"){
        foreach ($this->badinput as $oinput => $ninput){
           $new_value = str_replace("$oinput", "$ninput", $new_value);
        }; //end foreach
    }; //end if $filter
  
    $new_value = stripslashes("$new_value");
    $this->data["_DB"]["$db"][$id][] = $new_value;
    };

  }; //end else
  
} //end function addRow




function editRow($db, $id, $recs=array(), $filter="true"){
  if (!$this->is_connected()){
    $this->conn_stats("Unable to permitate records due to no database selected!");
  } else {


    foreach ($recs as $key => $new_value){

    if ($filter == "true"){
        foreach ($this->badinput as $oinput => $ninput){
           $new_value = str_replace("$oinput", "$ninput", $new_value);
        }; //end foreach
    }; //end if $filter

    $new_value = stripslashes("$new_value");
    $this->data["_DB"]["$db"][$id][$key] = $new_value;
    };

  }; //end else

} //end function editRow


function deleteRow($db, $row){
if (!$this->is_connected()){
  $this->conn_stats("Unable to remove a row due to no database selected!");
} else {

unset($this->data["_DB"]["$db"]["$row"]);

}; //end else



} //end function deleteRow


function createDB($dbname, $user, $pass, $name, $mail){
  if (!file_exists("$dbname")){
    $new = fopen("$dbname", "w");
    fwrite ($new, "ADMIN_UN = \"$user\"\r\nADMIN_PW = \"$pass\"\r\nADMIN_NA = \"$name\"\r\nADMIN_MA = \"$mail\"\r\n-----ADMIN_CFG-----\r\n");
    fclose($new);
    $this->error("Created database sucessfuly!");
  } else {
    $this->error("Database already exists! failed creation!");
  }; //end else
} //end function createDB

function createTable($name, $tables=array()){
  if (!$this->is_connected()){
    $this->conn_stats("Unable to create table due to no database selected!");
  } else {

  $this->data["_DB"]["$name"][0][0] = "$name";
  
  if ($tables != null){
    $this->addRow("$name", $tables);
  };
  
  $this->error("Created the table sucesfuly!");

  }; //end if
  
} //end function createTable
  
function deleteTable($dbname){

if (isset($this->data["_DB"]["$dbname"])){
  unset($this->data["_DB"]["$dbname"]);
} else {
  $this->error("Table doesnt exist or has already been removed, Recommend: reBuild");
}; //end isset

} //end function deleteTable

function query($dbname, $tableid, $string){

if (isset($this->data["_DB"]["$dbname"])){

for ($i = 0; $i < count($this->data["_DB"]["$dbname"]); $i++){
  $row[] = @$this->data["_DB"]["$dbname"]["$i"]["$tableid"];
}; //end for $i

$id = array_search($string, $row);
return $id;

} else {
$this->error("Specified Table doesnt exist! failed to query field...");
}; //end else

$this->querys++; //add a query

} //end function query


function export($db, $saveas, $delim, $start="1"){
  if ($start != "1"){
    $start = "2";
  }; //end if
  
  if (!$this->is_connected()){
    $this->conn_stats("Unable to export table due to no database selected!");
  } else {
  

  $new = fopen($saveas, "w");
  
  $sdb = $this->data["_DB"]["$db"];
  for ($i = $start; $i < count($sdb); $i++){

  $ndb = implode("$delim", $sdb[$i]);
  fwrite($new, "$ndb\r\n");
  

  }; //end $i

  }; //end if


  fclose($new);


} //end function export;


function import($dbfile, $dbname, $delim){

if (isset($this->data["_DB"]["$dbname"])){
unset($this->data["_DB"]["$dbname"]);
}; //end isset

$this->createTable("$dbname");


$file = file($dbfile);
for ($i = 0; $i < count($file); $i++){
  $newrow = str_replace("\r\n", "", $file["$i"]);
  $this->data["_DB"]["$dbname"][] = explode("$delim", $newrow);
}; //end $i


} //end function import

###########LOGIN SCRIPT##########
function login_db($dbname, $userfield, $passfield){
  if (isset($this->data["_DB"]["$dbname"])){

    $this->login_dbname = $dbname;
    $this->login_unf = $userfield;
    $this->login_pwf = $passfield;

  } else {
    $this->error("The database <b>$dbname</b> doesnt exist!");
  }; //end if
} //end function login_db

function login_user($v=""){
if (!$this->is_logged_in(@$_SESSION["_LOGUN"], @$_SESSION["_LOGPW"])){
  echo ("<input type=\"text\" name=\"_LOGUN\" value=\"$v\" />\r\n");
}; //end not logged in
} //end function login_user

function login_pass($v=""){
if (!$this->is_logged_in(@$_SESSION["_LOGUN"], @$_SESSION["_LOGPW"])){
  echo ("<input type=\"password\" name=\"_LOGPW\" value=\"$v\" />\r\n");
}; //end not logged in
} //end function login_user

function login_submit($v="Login Now"){
if (!$this->is_logged_in(@$_SESSION["_LOGUN"], @$_SESSION["_LOGPW"])){
  echo ("<input type=\"submit\" name=\"_LOGPO\" value=\"$v\" />\r\n");
}; //end not logged in
} //end function login_user

function login_verify($valid="Successfuly logged in!", $invalid="The username and password dont match a record in the DB."){
if (isset($_POST["_LOGPO"])){
if (!$this->is_logged_in(@$_SESSION["_LOGUN"], @$_SESSION["_LOGPW"])){
   $db = $this->login_dbname;
   $un = $this->login_unf;
   $pw = $this->login_pwf;

   $num = @$this->query("$db", $un, $_POST["_LOGUN"]);
   $data = @$this->data["_DB"]["$db"]["$num"];

   if ($_POST["_LOGUN"] != "" and $_POST["_LOGPW"] != "" and $_POST["_LOGUN"] == $data[$un] and md5($_POST["_LOGPW"]) == $data[$pw]){

   $_SESSION["_LOGUN"] = $_POST["_LOGUN"];
   $_SESSION["_LOGPW"] = md5($_POST["_LOGPW"]);
   echo ("$valid");

   } else {

   //user wasnt found in the database with the provided un and pw
   echo ("$invalid");

   }; //end if
}; //end not logged in
}; //end isset
} //end function login_verify


function is_logged_in($user, $pass){
  if (isset($user) and isset ($pass)){

  $db = $this->login_dbname;
  $un = $this->login_unf;
  $pw = $this->login_pwf;

  $id = $this->query($this->login_dbname, $this->login_unf, $user);
  if ($this->data["_DB"]["$db"]["$id"]["$un"] == $user and $this->data["_DB"]["$db"]["$id"]["$pw"] == $pass){


  return true;
  
  } else {

  return false;
  
  } //end else

  } else {

  return false;
  
  }; //end else

} //end function is_logged_in

function login_profile(){
 if ($this->is_logged_in(@$_SESSION["_LOGUN"], @$_SESSION["_LOGPW"])){

  $db = $this->login_dbname;

  $id = $this->query($this->login_dbname, $this->login_unf, $_SESSION["_LOGUN"]);
  $id = $this->data["_DB"]["$db"][$id];
  return $id;

 }; //end not logged in
} //end login_profile.
###########LOGIN SCRIPT###########

// -------------------------------------------- \\
//functions not interacting with the selected DB\\
// -------------------------------------------- \\


function compress( $srcFileName=array()) {
// getting file content
foreach ($srcFileName as $srcFileName){
$fp = fopen( $srcFileName, "r" );
$data = fread ( $fp, filesize( $srcFileName ) );
fclose( $fp );

// writing compressed file

$zp = gzopen( $srcFileName.".gz", "w" );
gzwrite( $zp, $data );
gzclose( $zp );
  }
} //end function compress

function readDir($dir, $ignore=array()){
 $d = opendir("$dir");
 $file = "";
while(($f = readdir($d))) {
      if($f != '..' && $f !='.' && $f != '' && !in_array($f, $ignore)){

      $file .="$f<~>";
} //end if
} //end while
$id = explode("<~>", $file);
return $id;
} //end function read_dir

function getFileSize($file, $sfx="false") {
$bytes = filesize("$file");
   if ($bytes >= 1099511627776) {
       $return = round($bytes / 1024 / 1024 / 1024 / 1024, 2);
       $suffix = "TB";
   } elseif ($bytes >= 1073741824) {
       $return = round($bytes / 1024 / 1024 / 1024, 2);
       $suffix = "GB";
   } elseif ($bytes >= 1048576) {
       $return = round($bytes / 1024 / 1024, 2);
       $suffix = "MB";
   } elseif ($bytes >= 1024) {
       $return = round($bytes / 1024, 2);
       $suffix = "KB";
   } else {
       $return = $bytes;
       $suffix = "Byte";
   }
   if ($sfx == "true"){
   if ($return == 1) {
       $return .= " " . $suffix;
   } else {
       $return .= " " . $suffix . "s";
   }
   };
   return $return;
} //end function getFileSize


function getEditTime($file, $date="F d Y H:i:s."){
if (file_exists($file)) {
   return date ("$date", filemtime($file));
}

} //end function getEditTime



} //end class sdb();



?>
