<html>
<title>...:: Guestbook ::...</title>
<head>
<link rel="stylesheet" href="admin/style.css" type="text/css">
<script language="JavaScript" src="admin/inc/functions.js"></script>
</head>
<body>

<?
// Guestbook v1.0 
// Copyright 2005 Armand Niculescu
// Website: www.armandniculescu.com

include_once("admin/inc/connect.php");
$query = mysql_query("SELECT * FROM guestbook ORDER BY id DESC") or die(mysql_error());

$content = "";
$content .= "<div class=\"content\">";
$paginatie = "";

if ($_SERVER['REQUEST_METHOD']=='POST'){
			if (!$_POST['name'] OR !$_POST['comments']) {
						$content .= "<script>alert('Please fill all the fields marked with *')</script>";
			}	else { 
			   $date = time();
						$name = stripslashes($_POST['name']);
						$email = stripslashes($_POST['email']);
						$url = stripslashes($_POST['url']);
						$comments = stripslashes($_POST['comments']);																		
						$query = mysql_query("INSERT 
																																			INTO 
																												guestbook 
																																			VALUES(
																																										'NULL',
																																										'".$date."',
																																										'".$name."',
																																										'".$email."',
																																										'".$url."',																																										
																																										'".$comments."'																																																																																			
																																			)						
																											") or die(mysql_error());
							echo "<script>document.location.replace('index.php')</script>";																											
			}																
}

if (mysql_num_rows($query) > 0 ){

//create pages

$pg = empty($_GET['pg']) ? 1 : $_GET['pg']; 
$catePePag = "10"; // no of items per page
$nrIntrari = mysql_num_rows($query); 
$rest = ($nrIntrari % $catePePag); 
$nrPag = IntVal($nrIntrari / $catePePag);

$paginatie = "Page: ";
if ($rest <> 0)
	$nrPag = $nrPag + 1; 
for ($i=1; $i<=$nrPag; $i++) {
	if ($i == $pg)
		$paginatie .=" <b>".$i."</b> ";
	else
		$paginatie .= "<a href=\"index.php?&amp;pg=".$i."\">".$i."</a> ";
}

if ((!intval($pg)) or ($pg > $nrPag))
	echo "<script>location.href('index.php?&amp;pg=1');</script>";

$content .= "\n<h1>Guestbook items</h1>";

$content .= "<div class=\"paginatie\">";
$content .= $paginatie;	
$content .= "</div>";

if ($pg == 1) 
	$inf = 0;
else 
	$inf = $catePePag * ($pg-1);
	
$intrariPag = mysql_query("SELECT * FROM guestbook ORDER BY id DESC LIMIT " . $inf . ",". $catePePag. "") or die(mysql_error());

//end of pagination 


			for($i=0; $i<mysql_num_rows($intrariPag); $i++) {
      $content .= "\n<dl class=\"formular\">";
      $content .= "<dt>Name</dt><dd>".mysql_result($intrariPag,$i,'name')."</dd>";
						if (mysql_result($intrariPag,$i,'email'))
      			$content .= "<dt>E-mail</dt><dd><a href=\"mailto:".mysql_result($intrariPag,$i,'email')."\">".mysql_result($intrariPag,$i,'email')."</a></dd>";
						if (mysql_result($intrariPag,$i,'url') != "http://")
      			$content .= "<dt>URL</dt><dd><a href=\"".mysql_result($intrariPag,$i,'url')."\" target=\"_blank\">".mysql_result($intrariPag,$i,'url')."</a></dd>";
      $content .= "<dt>Comments</dt><dd>".mysql_result($intrariPag,$i,'comments')."</dd>";												
      $content .= "\n</dl>";
						$content .= "<div class=\"sterge\">&nbsp;</div>";
						if ($i != mysql_num_rows($intrariPag)-1)
									$content .= "<hr />";
						else 
									$content .= "<p style=\"margin: 4px 0; padding: 0;\">&nbsp;</p>";																		
			}
}

else {
					 $content .= "\n<h1>Guestbook items</h1>";
					 $content .= "\n<p>Nobody signed the guestbook yet!</p>";						
}

$content .= "<div class=\"paginatie\">";
$content .= $paginatie;	
$content .= "</div>";

$content .= "<div class=\"sterge\">&nbsp;</div>";
$content .= "\n<h1>Sign the guestbook</h1>";
$content .= "\n<form action=\"index.php?page=guestbook&amp;act=add\" method=\"post\">";
$content .= "\n<dl class=\"formular\">";
$content .= "<dt>Name</dt><dd><input type=\"text\" name=\"name\" value=\"".(!empty($_POST['name']) ? $_POST['name']: "")."\" /> *</dd>";
$content .= "<dt>E-mail</dt><dd><input type=\"text\" name=\"email\" value=\"".(!empty($_POST['email']) ? $_POST['email']: "")."\" /></dd>";
$content .= "<dt>URL</dt><dd><input type=\"text\" name=\"url\" value=\"".(!empty($_POST['url']) ? $_POST['url']: "http://")."\" /></dd>";
$content .= "<dt>Comments</dt><dd><textarea name=\"comments\" rows=\"5\" cols=\"40\">".(!empty($_POST['comments']) ? $_POST['comments']: "")."</textarea> *</dd>";												
$content .= "<dt>&nbsp;</dt><dd><input type=\"submit\" value=\"Submit\" /></dd>";
$content .= "\n</dl></form>";			


//Please do not erase the line below. Leave the copyright intact. Thank you
$content .= "<p style=\"font-size: 10px;\">Guestbook v1.0 &middot; &copy; 2005 <a href=\"http://www.armandniculescu.com\" target=\"_blank\">Armand Niculescu</a></p>";
$content .= "</div>";
print $content;
?>

</body>
</html>