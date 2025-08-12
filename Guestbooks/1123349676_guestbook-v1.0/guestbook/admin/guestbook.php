<?
// Guestbook v1.0 
// Copyright 2005 Armand Niculescu
// Website: www.armandniculescu.com

$content = "<div class=\"guestbook\">";
$paginatie = "";

$query = mysql_query("SELECT * FROM guestbook ORDER BY date DESC") or die(mysql_error());
if (mysql_num_rows($query) == 0) {
			$content .= "<p>There are no items yet!</p>";
}
else {
$_GET['act'] = (!isset($_GET['act'])) ? "" : $_GET['act'];

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
		$paginatie .= "<a href=\"index.php?page=guestbook&amp;pg=".$i."\">".$i."</a> ";
}

if ((!intval($pg)) or ($pg > $nrPag))
	echo "<script>location.href('index.php?page=guestbook&amp;pg=1');</script>";

$content .= "<div class=\"paginatie\">";
$content .= $paginatie;	
$content .= "</div>";

if ($pg == 1) 
	$inf = 0;
else 
	$inf = $catePePag * ($pg-1);
	
$intrariPag = mysql_query("SELECT * FROM guestbook ORDER BY id DESC LIMIT " . $inf . ",". $catePePag. "") or die(mysql_error());

//end of pagination 

			$content .= "\n<table cellpadding=\"4\" cellspacing=\"1\" class=\"tabel\">";
			$content .= "\n<thead>";
			$content .= "\n<th>Date</th>";
			$content .= "\n<th>Name</th>";
			$content .= "\n<th>Comments</th>";
			$content .= "\n<th>Options</th>";			
			$content .= "\n</thead>";																		
			for($j=0; $j<mysql_num_rows($intrariPag); $j++) {
								$row = (($j % 2) == 0) ? "row1" : "row2";
								$id = mysql_result($intrariPag,$j,'id');
								$date = mysql_result($intrariPag,$j,'date');
								$date = date("d/m/Y - h:i", $date);
								$content .= "\n<tr class=\"$row\">";
								$content .= "\n<td width=\"80\">".$date."</td>";
								$content .= "\n<td width=\"80\">".mysql_result($intrariPag,$j,'name')."</td>";
								$content .= "\n<td width=\"430\" style=\"text-align: left;\">".mysql_result($intrariPag,$j,'comments')."</td>";
								$content .= "\n<td><a href=\"index.php?page=guestbook&amp;act=modify&amp;id=".$id."#modify\">Modify</a> | <a href=\"javascript:sterge(".$id.")\">Erase</a></td>";																																																
								$content .= "\n</tr>";											
			}
}

$content .= "</table>";
$content .= "<div class=\"paginatie\">";
$content .= $paginatie;	
$content .= "</div>";

if (isset($_GET['act']) AND $_GET['act'] == 'modify') {
			$content .= "<div class=\"sterge\">&nbsp;</div>";
			$content .= "\n<h1><a name=\"modify\"></a>Modify comment</h1>";
			$content .= "\n<form action=\"index.php?page=guestbook&amp;act=update&amp;id=".$_GET['id']."\" method=\"post\">";
			$content .= "\n<dl class=\"formular\">";

			$query = mysql_query("SELECT * FROM guestbook WHERE id=".$_GET['id']."") or die(mysql_error());
			$date = mysql_result($query,0,'date');
			$date = date("d/m/Y - h:i", $date);
			
			$content .= "<dt>Date</dt><dd>".$date."</dd>";
			$content .= "<dt>Name</dt><dd><input type=\"text\" name=\"name\" value=\"".mysql_result($query,0,'name')."\" /></dd>";
			$content .= "<dt>E-mail</dt><dd><input type=\"text\" name=\"email\" value=\"".mysql_result($query,0,'email')."\" /></dd>";
			$content .= "<dt>URL</dt><dd><input type=\"text\" name=\"url\" value=\"".mysql_result($query,0,'url')."\" /></dd>";
			$content .= "<dt>Comments</dt><dd><textarea name=\"comments\" rows=\"5\" cols=\"40\">".mysql_result($query,0,'comments')."</textarea></dd>";												
			$content .= "<dt>&nbsp;</dt><dd><input type=\"submit\" value=\"Modify\" /></dd>";
			$content .= "\n</dl></form>";			
}

if (isset($_GET['act']) AND $_GET['act'] == 'update') {
			$query = mysql_query("UPDATE guestbook SET 
																														name='".$_POST['name']."',
																														email='".$_POST['email']."',
																														url='".$_POST['url']."',
																														comments='".$_POST['comments']."'
																										WHERE id='".$_GET['id']."'																																																																																								
																								") or die(mysql_error());
			echo "<script>document.location.replace('index.php?page=guestbook')</script>";
}

if (isset($_GET['act']) AND $_GET['act'] == 'delete') {
			$query = mysql_query("DELETE FROM guestbook	WHERE id='".$_GET['id']."'") or die(mysql_error());
			echo "<script>document.location.replace('index.php?page=guestbook')</script>";
}

$content .= "</div>";
print $content;
?>