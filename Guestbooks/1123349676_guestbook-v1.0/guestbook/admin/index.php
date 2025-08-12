<?
// Guestbook v1.0 
// Copyright 2005 Armand Niculescu
// Website: www.armandniculescu.com

include_once("inc/connect.php");
session_start();
$eLogat=false;

if (!empty($_POST['user']) AND !empty($_POST['parola'])){
	$logare=mysql_query("SELECT * FROM users WHERE user='".$_POST['user']."' AND pass='".md5($_POST['parola'])."'");
	if (mysql_num_rows($logare)>0){
		$_SESSION['user']=$_POST['user'];
		$_SESSION['parola']=md5($_POST['parola']);
		$eLogat=true;
	}
}else if (!empty($_SESSION['user']) AND !empty($_SESSION['parola'])){
					 $logare=mysql_query("SELECT * FROM users WHERE user='".$_SESSION['user']."' AND pass='".$_SESSION['parola']."'") or die ("ERROR: ".mysql_error());
					 if (mysql_num_rows($logare)>0){
					 		$eLogat=true;
					 }
				}		
				
if (!empty($_GET['logout'])){
	$eLogat=false;
	session_destroy();
}

if ($eLogat==true) {

echo "
<html>
<title>...:: Guestbook admin ::...</title>
<head>
<link rel=\"stylesheet\" href=\"style.css\" type=\"text/css\">
<script language=\"JavaScript\" src=\"inc/functions.js\"></script>
</head>
<body>
<div class=\"title\">NAVIGATION</div>
<div class=\"meniu\"> 
	<ul>
		<li><a href=\"index.php?page=schimbapass\">Change password</a></li>
		<li><a href=\"index.php?page=guestbook\">Guestbook items</a></li>
		<li><a href=\"index.php?logout=t\">Logout</a></li>
		<li><a href=\"http://www.armandniculescu.com\" target=\"_blank\">&copy 2005 by miRRor</a></li>		
	</ul>  
</div>
<div class=\"sterge\">&nbsp;</div>
<div class=\"main\">

";

if (isset($_GET['page']) AND file_exists($_GET['page'] . ".php")) 
	include $_GET['page'] . ".php";

echo "
<div class=\"sterge\">&nbsp;</div>
</div>
<p style=\"font-size: 10px;\">Guestbook v1.0 &middot; &copy; 2005 <a href=\"http://www.armandniculescu.com\" target=\"_blank\">Armand Niculescu</a></p>
</body>
</html>
";
}
else {

    echo "				
    <html>
    <head>
          <link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />
    </head>
    <body>
            <div id=\"login\" style=\"margin-top: 200px;\">
             	 	<form action=\"index.php\" method=\"post\" name=\"login\">
           		  			<h3>Guestbook admin</h3>
               					<p>Username: <input type=\"text\" name=\"user\" /></p>
               					<p>Password: <input type=\"password\" name=\"parola\" /></p>
               					<p><input type=\"submit\" name=\"submit\" value=\"Login\" /></p>
             		</form>
															<p style=\"font-size: 10px;\">Guestbook v1.0 &middot; &copy; 2005 <a href=\"http://www.armandniculescu.com\" target=\"_blank\">Armand Niculescu</a></p>
        	 </div>
   </body>
   </html>";
   }
?>