<?php

// HERE ARE SOME VARIABLES TO CONFIGURE
// THE FIRST SET IS FOR THE MYSQL DATABASE CONNECTION

$hostname = "localhost"; #database hostname (usually localhost)
$database = "joe_images"; #database name
$db_user = "user";  #database username (make sure this username has permissions for the db)
$db_pass = "pass";  #database password for the username

$image_config[large_w] = "500"; #width of the largest image
$image_config[thumb_w] = "150"; #width of thumbnails
$image_config[quality] = "70";  #quality of compression for images (0-100) 70 default
$image_config[path] = "images/"; #path to images under joe_images folder

$admin[user] = "admin"; #username to access the photo album page.
$admin[pass] = "pass";  #password for the admin area to access photo album

// DO NOT EDIT BELOW THIS LINE

//connect script

$db = mysql_connect($hostname, $db_user, $db_pass) or die("Couldn't Connect to Database");
$connection = mysql_select_db($database, $db) or die("Couldn't Connect to Database");

///////////////// START OF FUNCTIONS ///////////////

session_start();

function auth(){
   global $admin;
   
  if($_POST[userLOG] AND $_POST[passLOG]){
      $_SESSION[userLOG] = $_POST[userLOG];
      $_SESSION[passLOG] = $_POST[passLOG];
   }
   
   if($_SESSION[userLOG] AND $_SESSION[passLOG]){
      if($_SESSION[userLOG] != $admin[user] OR $_SESSION[passLOG] != $admin[pass]){
         logout("Invalid username of password!");
      }
   }else{
      logout("Joe Images Administration");
   }
   
}

function logout($msg){

   $_SESSION = array();
   print "
   <html>
   <head>
   <title>Joe Images Administration Login</title>
   <style>
   body {
      background: #F05E2A;
      margin: 50px;
   }
   body, td, div {
      font-family: tahoma;
      color: #333333;
      font-size: 10px;
   }
   #loginbox, #errorbox {
      width: 300px;
      border: 3px solid #FCC1A0;
      padding: 5px;
      margin: 3px;
      background: #ffffff;
   }
   
   #errorbox {
      font-weight: bold;
      font-size: 11px;
      color: #104A6C;
   }
   </style>
   </head>
   <body>
   <center>
   ";
   
   if($msg){
      print "<div id='errorbox'>$msg</div>";
   }
   
   print "
   <div id='loginbox' align='left'>
   <b>LOGIN AREA</b><br>
   <form method='post'>
   <table cellpadding='3' cellspacing='0' border='0'>
   <tr><td align='right'>Username</td><td><input type='text' name='userLOG' value='' size='15'></td></tr>
   <tr><td align='right'>Password</td><td><input type='password' name='passLOG' value='' size='15'></td></tr>
   <tr><td></td><td><input type='submit' value='Login'></td></tr>
   </table>
   </form>
   </div>
   
   </center>   
   </body>
   </html>
   ";
   exit;

}


function headerPrint(){

   print "
   <html>
   <head>
   <title>Untitled</title>
   <meta name='generator' content='BBEdit 6.5.3'>
   
   <style>
   body {
	  background: #F05E2A;
   }
   
   td {
	  font-family: arial;
	  font-size: 10px;
	  color: #333333;
	  line-height: 170%;
	  font-weight: bold;
   }
   body, td, div {
      font-family: tahoma;
      color: #333333;
      font-size: 10px;
   }
   b {
      font-size: 12px;
   }
   
   a {
      font-weight: bold;
      color: #FA1C18;
      text-decoration: none;
   }

   a:hover {
      font-weight: bold;
      color: #000000;
      text-decoration: none;
   }
   
   .row1 {
      padding-top: 2px;
      padding-bottom: 2px;      
      padding-left: 5px;
      padding-right: 5px;
      background: #ffffff;
   }
   .row2 {
      padding-top: 2px;
      padding-bottom: 2px;      
      padding-left: 5px;
      padding-right: 5px;
      background: #fdfdfd;
   }
   .rowspacer {
      background: #e3e3e3;
   }
   
   .titlebar {
      background: #95BA23;
      border-top: 1px solid #e3e3e3;
      border-bottom: 1px solid #e3e3e3;
      padding: 5px;
      font-weight: bold;
      color: #ffffff;
      text-decoration: none;
   }
   
   .button, a.button {
      background: #ffffff;
      border: 1px solid #e3e3e3;
      padding: 5px;
      font-weight: bold;
      color: #FA1C18;
      text-decoration: none;
   }

   a.button:hover {
      background: #ffffff;
      border: 1px solid #e3e3e3;
      padding: 5px;
      font-weight: bold;
      color: #000000;
      text-decoration: none;
   }
   
   #titlebox, #siteborder {
      width: 680px;
      border: 3px solid #FCC1A0;
      padding: 5px;
      margin: 3px;
      background: #ffffff;
      text-align: left;      
   }
   #titlebox {
      font-family: tahoma, arial;
      font-size: 12px;
      font-weight: bold;   
      color: #104A6C;
   }
   </style>
   
   </head>
   <body>
   <center>
   <div id='titlebox'>JOE IMAGES by joedesigns.com</div>
   
   <div id='siteborder'>
   ";
}



function footerPrint(){
   print "
   </div>
   </center>
   </body>
   </html>
   ";
}


function listTable(){
   
   print "<table width='100%' cellpadding='0' cellspacing='0' border='0'>";  

   print "<tr><td colspan='2'><a href='index.php?fuse=add' class='button'>ADD NEW IMAGE</a> <a href='view.php' class='button'>VIEW IMAGES</a><br><br></td></tr>";

   $sql = mysql_query("SELECT * FROM joe_images ORDER by id DESC") or die("died");
      
   print "<tr><td colspan='2' class='titlebar'>IMAGES - ".mysql_num_rows($sql)." Records Found</td></tr>";
   
   while($row = mysql_fetch_array($sql)){
   
      if($z == 1){$class = "row1";$z=2;}else{$class = "row2";$z=1;}

      print "<tr><td class='$class'>$row[name]</td><td class='$class' align='right'><a href='index.php?fuse=mod&id=$row[id]'>MOD</a> / <a href='index.php?fuse=del&id=$row[id]'>DEL</a></td></tr>";
      print "<tr><td height='1' colspan='2' class='rowspacer'></td></tr>";
      
   }
   
   print "</table>";
   
}


function form($id, $image_config){

   if($id){
      $sql = mysql_query("SELECT * FROM joe_images WHERE id = '$id'") or die("died");
      $r = mysql_fetch_array($sql);
   }

   
   print "<form method='post' enctype='multipart/form-data'>";
   print "<table cellpadding='4' cellspacing='0' border='0'>";
   
   if(file_exists("".$image_config[path]."$id.jpg") AND $id){
      print "<tr><td></td><td><img src='".$image_config[path]."$id.jpg'></td></tr>";
   }else{
      print "<tr><td></td><td>Image must be at least $image_config[large_w] pixels wide.<br><br></td></tr>";
      print "<tr><td>Image File</td><td><input type='file' name='image'></td></tr>";
   }
   
   print "<tr><td align='right'>Status</td><td><select name='r[status]'>";

   if($r[status_id] == 2){
      print "

      <option value='1'>Enabled</option>
      <option value='2' selected>Disabled</option>
      ";
   }else{
      print "
      <option value='1' selected>Enabled</option>
      <option value='2'>Disabled</option>
      "; 
   }  
   
   print "</select></td></tr>";
   
   print "<tr><td align='right'>Name</td><td><input type='text' size='35' name='r[name]' value='$r[name]'></td></tr>";   
   print "<tr><td align='right'>Description</td><td><textarea cols='40' rows='5' name='r[info]'>$r[info]</textarea></td></tr>";      

   print "<tr><td></td><td><input type='submit' value='Add/Modify Record'></td></tr>";

   print "</table>";
   print "</form>";
   
}

function errorCheck($r, $image){
   
   if(!$image){$error .= "- Image File<br>";}
   
   list($width, $height, $type, $attr) = getimagesize($image);
   
   if($width < $image_config[large_w]){
   	  $error .= "- The image's width is $width pixels, the image width must be at least $image_config[large]!<br>";
   }
   
   if($error){
      print "<b>Please go back and complete the required fields.</b><br>";
      print "<div style='padding-left: 20px;'>$error</div>";
      print "<br><input type='button' value='Go Back' onclick='javascript:history.back()'>";
      footerPrint();
      exit;
   }   
}

function successMsg($msg){
   print "<b>$msg</b><br><br><input type='button' value='Back to List' onclick=\"window.location='index.php'\">";
}

function createThumb($name,$dest,$new_w,$quality){

   system("convert -quality $quality -resize '$new_w' '$name' '$dest'", $retval);

   if ($retval){
	  echo"Sorry the Image wasn't created.<br><br>";
   }
}

function viewPhotos($cols, $sort, $order,$path){
   global $image_config;
   if($sort == 1){
      $sortby = "timestamp";
   }
   if($sort == 2){
      $sortby = "name";
   }
   if($order == 1){
      $orderby = "ASC";
   }
   if($order == 2){
      $orderby = "DESC";
   }
   print "<table cellpadding='3' cellspacing='0' border='0'>";
   
   $img = mysql_query("SELECT * FROM joe_images WHERE status = '1' ORDER by $sortby $orderby") or die("died");
   
   $z = 1;
   while($imgR = mysql_fetch_array($img)){
   
	  if($z == 1){ print "<tr>"; }
	
	  list($w, $h) = getimagesize($path.$image_config[path].$imgR[id].".jpg");
	  print "<td valign='top' align='center'><a href='$path$image_config[path]$imgR[id]LG.jpg' target='new'><img src='$path$image_config[path]$imgR[id].jpg' width='$w' height='$h' border='0' style='border: 1px solid #333333;'></a><br>$imgR[name]</td>";
	  
	  if($z == $cols){
		 print "</tr>";
		 $z = 0;
	  }
	  
	  $z++;
   
   }
   
   print "</table>";

}


///////////////// END OF FUNCTIONS ////////

?>