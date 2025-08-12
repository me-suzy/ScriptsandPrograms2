<?php 
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// COMMENTS.PHP > 03-11-2005

$c_query = "SELECT * FROM ".$prefix."articles WHERE aid = '$row->aid'"; 
$c_result = mysql_query($c_query); 
while($c_row = mysql_fetch_object($c_result)) { 
	$settings_comments = $c_row->show_comments; 
} 
if ($settings[comments] == 1 && $settings_comments == 1) { 
 
	if ($c == 1) { 
	include("admin/cookies.php"); 
	$author = read_cookie("mobscommenter"); 
	$email = read_cookie("mobscommentemail"); 
	$url = read_cookie("mobscommenturl"); 
	$c_query = "SELECT * FROM ".$prefix."comments WHERE parentid = '$row->aid' ORDER BY coid"; 
	$c_result = mysql_query($c_query); 
	echo "<a name='comments'></a>"; 
		while($c_row = mysql_fetch_object($c_result)) { 
?> 
 
 
<b><?php getcomment("author");?> (<a href="mailto:<?php getcomment("email");?>">mail</a>/<a href="<?php getcomment("url");?>">url</a>)</b> @ <?php getcomment("date");?> - <?php getcomment("time");?><br> 
<?php comment();?><br><br> 
 
<?php	 
		} 
?> 
 
<form method="post" action="<?php echo $PHP_SELF;?>"> 
<input type="hidden" name="comments" value="post"> 
<input type="hidden" name="p" value="<?php echo $p;?>"> 
<input type="text" value="<?php echo $author; ?>" name="author"> name<br> 
<input type="text" value="<?php echo $email; ?>" name="email"> email<br> 
<input type="text" value="<?php echo $url; ?>" name="url"> website<br> 
<textarea name="comment" cols=35 rows=8></textarea><br> 
<input type="submit" value="post comment"> 
</form> 
<a href="<?php echo $PHP_SELF;?>">back</a> 
<?php 
	} 
} 
?> 
 
