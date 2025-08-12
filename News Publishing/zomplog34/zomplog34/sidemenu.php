	<?
	function displayPages($spacer){
	global $table_pages, $link, $page;
	$query = "SELECT * FROM $table_pages ORDER BY id DESC";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	$pages = arrayMaker($result,MYSQL_ASSOC);
	foreach($pages as $page){
	echo "<a href='page.php?id=$page[id]'>$page[title]</a>$spacer";
	}
	}
	
	function displaylatestPosts($spacer){
	global $table, $link, $entry;
	$query = "SELECT * FROM $table ORDER BY id DESC LIMIT 10";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	$entries = arrayMaker($result,MYSQL_ASSOC);
	foreach($entries as $entry){
	echo "<a href='detail.php?id=$entry[id]'>$entry[title]</a>$spacer";
	}
	}
	
	function displayCategories($spacer){
	global $table_cat, $table, $link, $cat, $numcat;
	$query = "SELECT * FROM $table_cat ORDER BY id DESC";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	$categories = arrayMaker($result,MYSQL_ASSOC);
	foreach($categories as $cat){
	$query = "SELECT * FROM $table WHERE catid = $cat[id]";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	$numcat = mysql_num_rows ($result);
	echo "<a href='get.php?catid=$cat[id]'>$cat[name]</a> ($numcat)$spacer";
	}
	}
	
	function displayAuthors($spacer){
	global $table_users, $link, $user;
	$query = "SELECT * FROM $table_users ORDER BY id DESC";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	$users = arrayMaker($result,MYSQL_ASSOC);
	foreach($users as $user){
	echo "<a href='get.php?username=$user[login]'>$user[login]</a>$spacer";
	}
	}
	
	function aboutAuthor(){
	global $table_users, $link, $user;
	$query = "SELECT * FROM $table_users WHERE login = '$_GET[username]' LIMIT 1";
	$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
	$user = mysql_fetch_array($result);
	return $user;
	}
	
	$author = aboutAuthor();
	
	include("skins/$settings[skin]/sidemenu.php");
	
	?>



