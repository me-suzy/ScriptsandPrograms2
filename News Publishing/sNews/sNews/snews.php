<?php
// ----------------------------------------------------------------------
// sNews v1.3
// Copyright(c) 2005, Solucija - All rights reserved
// http://www.solucija.com/
// ----------------------------------------------------------------------
// For information how to set up the MySQL database, see readme.txt.
// Enter your settings below 
// ----------------------------------------------------------------------

error_reporting (E_ALL ^ E_NOTICE);

function s($variable) {
$s = Array();

//******************
// GLOBAL SETTINGS
//******************

$s['username'] = md5("test"); // Enter your administration username
$s['password'] = md5("test"); // Enter your administration password

$s['dbhost'] = "localhost"; // MySQL host
$s['dbname'] = "dbname"; // Database name
$s['dbuname'] = "dbusername"; // Database Username
$s['dbpass'] = "dbpass"; // Database password
$s['dberror'] = "Error while connecting to the database!"; // Database error message

$s['prefix'] = ""; // Table prefix for multiple sNews systems on one database (if you don't need it just leave it blank)

$s['website'] = "www.yoursite.com"; // Website url
$s['website_title'] = "Yoursite"; // Website title
$s['website_email'] = "info@yoursite.com"; // Contact email (info@yoursite.com)
$s['contact_subject'] = "Contact Form"; // Subject of the contact form message
$s['image_folder'] = "img"; // Folder to save images

$s['charset'] = "iso-8859-1"; // Default charset
$s['articles_limit'] = 5; // Number of articles displayed on the front page
$s['display_num_categories'] = "YES"; // Display number of articles next to a category name (YES or NO)
$s['display_comment_time'] = "YES"; // display date and time on comments (YES or NO)
$s['date_format'] = "d.m.Y."; // Date format
$s['fp_date_format'] = "d.m."; // Date format for front page articles
$s['comment_dt_format'] = "d.m."; // Date and time format for comments
$s['results_per_page'] = 5; // Number of comments to display per page

return $s[$variable];
}

//********************
// LANGUAGE VARIABLES
//********************
function l($variable) {
$l = Array();

$l['home'] = "Home";
$l['archives'] = "Archive";
$l['rss_feed'] = "RSS Feed";
$l['contact'] = "Contact";
$l['search_button'] = "Search";
$l['search_results'] = "Search results";
$l['charerror'] = "At least 4 characters are needed to preform the search.";
$l['noresults'] = "There are no results for query ";
$l['resultsfound'] = "results were found for query";
$l['recent_articles'] = "Recent articles";
$l['addcomment'] = "Leave a comment";
$l['comments'] = "Comments";
$l['comment'] = "Comment";
$l['first_page'] = "First";
$l['last_page'] = "Last";
$l['previous_page'] = "Previous";
$l['next_page'] = "Next";
$l['name'] = "Name";
$l['sendcomment'] = "Submit";
$l['comment_sent'] = "Your comment has been submitted";
$l['comment_error'] = "Your comment was not sent";
$l['ce_reasons'] = "Possible reasons: You left blank column or the comment is too short.";
$l['email'] = "Email";
$l['url'] = "Website URL";
$l['message'] = "Message";
$l['send_message'] = "Submit";
$l['contact_sent'] = "Your message has been sent";
$l['contact_not_sent'] = "Your message was not sent";
$l['message_error'] = "Possible reasons: You left name or message field blank or email address does not exist";
$l['backhome'] = "Back home";
$l['backarticle'] = "Back to article";
$l['read_more'] = "Read more";
$l['article_not_exist'] = "Article does not exist";
	
		
//***********************************
// ADMINISTRATION LANGUAGE VARIABLES
//***********************************
$l['username'] = "Username";
$l['password'] = "Password";
$l['login'] = "Login";
$l['already_logged'] = "Already logged in";
$l['logout'] = "Logout";
$l['settings'] = "Settings";
$l['save_settings'] = "Save settings";
$l['articles'] = "Articles";
$l['category'] = "Category";
$l['categories'] = "Categories";
$l['add_category'] = "Add category";
$l['edit_category'] = "Edit category";
$l['delete_category'] = "Delete category";
$l['description'] = "Description";
$l['publish_category'] = "Publish category";
$l['published'] = "Published";
$l['unpublished'] = "Unpublished";
$l['new_article'] = "New article";
$l['limit_article'] = "Limit front page article to a number of characters (0 = no limit)";
$l['article_date'] = "Article date (enter a higher date for future posting)";
$l['auto_html'] = "Auto paragraph";
$l['display_title'] = "Display title";
$l['display_info'] = "Display info line";
$l['enable_commenting'] = "Enable commenting";
$l['edit_comment'] = "Edit comment";
$l['freeze_comments'] = "Freeze comments";
$l['unfreeze_comments'] = "Unfreeze comments";
$l['publish_article'] = "Publish article";
$l['unpublished_articles'] = "Unpublished articles";
$l['submit_new_article'] = "Submit";
$l['operation_completed'] = "Operation completed succesfully";
$l['deleted_success'] = "Succesfuly deleted";
$l['edit_article'] = "Edit article";
$l['simple'] = "Simple";
$l['advanced'] = "Advanced";
$l['images'] = "Images";
$l['attach_image'] = "Attach image";
$l['upload_image'] = "Upload Image";
$l['upload'] = "Upload";
$l['no_image'] = "No image";
$l['edit'] = "Edit";
$l['delete_article'] = "Delete article";
$l['admin_error'] = "Error";
$l['title_error'] = "You must enter title";
$l['text_error'] = "You must enter some text";
$l['back'] = "Back";
$l['comments'] = "Comments";
$l['enabled'] = "Enabled";
$l['disabled'] = "Disabled";
$l['posted_by'] = "Posted by";
$l['delete_comment'] = "Delete comment";
$l['title'] = "Title";
$l['text'] = "Text";
$l['position'] = "Position";
$l['display_menu_item'] = "Display as menu item";
$l['left'] = "Left";
$l['center'] = "Center";
$l['right'] = "Right";
$l['saved_images'] = "Saved images";
$l['image_error'] = "Image could not be copied";
$l['login_faliure'] = "Access denied";

return $l[$variable];
}

//*******************************************************************************
//            END OF SETTINGS & VARIABLES, DON'T EDIT BELOW THIS LINE!
//*******************************************************************************

//*******************************************************************************
//                               WEBSITE FUNCTIONS
//*******************************************************************************


// TITLE
function title() {
	if ($_POST['Submitted'] == "True") {
    	if (md5($_POST['Username']) == s('username') && md5($_POST['Password']) == s('password')) {
			$_SESSION['Logged_In'] = "True";
    		$_SESSION['Username'] = s('username');
    }	}
	$id = $_GET['id'];
	if ($id == "") { $title = s('website_title'); }
	else { 
		$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
  		mysql_select_db(s('dbname')) or die(s('dberror'));
  		$query = "SELECT * FROM " .s('prefix'). "articles WHERE id = $id"; 
		$result = mysql_query($query);
  		while ($r = mysql_fetch_array($result)) { $title = s('website_title'). " &raquo; ". $r['title']; } }
	echo "<title>" .$title. "</title>";
}


// DISPLAY CATEGORIES
function categories() { 
	echo "<a href=\"index.php\" title=\"" .s('website_title'). "\">". l('home') ."</a>";
	$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
	mysql_select_db(s('dbname')) or die(s('dberror'));
	$query = "SELECT * FROM " .s('prefix'). "categories WHERE published = 'YES' ORDER BY id"; 
	$result = mysql_query($query);
	while ($r = mysql_fetch_array($result)) {
		if (s('display_num_categories') == "YES") {
			$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
			mysql_select_db(s('dbname')) or die(s('dberror'));
			$calc_num_query = "SELECT * FROM " .s('prefix'). "articles WHERE position = 1 AND category = $r[id]"; 
			$cm_result = mysql_query($calc_num_query);
			$num_rows = mysql_num_rows($cm_result);
			echo "<a href=\"index.php?category=" .$r['id']. "\" title=\"". $r['description'] ."\">" .$r['name']. " (" .$num_rows. ") </a>"; }
		else { echo "<a href=\"index.php?category=" .$r['id']. "\" title=\"". $r['description'] ."\">" .$r['name']. "</a>"; }}
	echo "<a href=\"index.php?action=archives\">". l('archives') ."</a>";
	echo "<a href=\"index.php?action=contact\">". l('contact') ."</a>";
}


// DISPLAY MENU ITEMS
function menu_items() { 
	echo "<a href='index.php?action=archives'>" .l('archives'). "</a>";
    $db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
	mysql_select_db(s('dbname')) or die(s('dberror'));
	$query = "SELECT * FROM " .s('prefix'). "articles WHERE position = 3 ORDER BY id ASC"; 
	$result = mysql_query($query);
	while ($r = mysql_fetch_array($result)) {
	echo "<a href=\"index.php?id=" .$r['id']. "\">" .$r['title']. "</a>"; }
	if (isset($_SESSION['Username'])) { echo "<a href='index.php?action=categories'>". l('categories') ."</a>"; }
	if (isset($_SESSION['Username'])) { echo "<a href='index.php?action=new'>". l('new_article') ."</a>"; }
	if (isset($_SESSION['Username'])) { echo "<a href='index.php?action=unpublished'>". l('unpublished_articles') ."</a>"; }
	if (isset($_SESSION['Username'])) { echo "<a href='index.php?action=images'>". l('images') ."</a>"; }
	if (isset($_SESSION['Username'])) { echo "<a href='index.php?action=logout'>". l('logout') ."</a>"; }
	echo "<a href='index.php?action=contact'>". l('contact') ."</a>";
}

// LEFT
function left() {	
	$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
  	mysql_select_db(s('dbname')) or die(s('dberror'));
  	$query = "SELECT * FROM " .s('prefix'). "articles WHERE position = 5 ORDER BY id DESC"; 
	$result = mysql_query($query);
  	while ($r = mysql_fetch_array($result)) {
    	if (isset($_SESSION['Username'])) { echo "<a href=\"index.php?action=edit&id=$r[id]\">". l('edit_article') ."</a> <img src=\"images/arrow.gif\" alt=\"|\" /> <a href=\"index.php?action=process&task=delete&amp;id=". $id ."\">". l('delete_article') ."</a>"; }
		if ($r['displaytitle'] == "YES") { echo "<h2>". $r['title'] ."</h2>"; }
    	echo $r['text'];	  	  	  	
	} 
} 

// CENTER			
function center($article_limit) {
	$id = $_GET['id'];
	$display_further = "YES";
	if ($_POST['submit_text']) { processing(); $display_further="NO"; }
	if ($_POST['contactform']) { contact(); $display_further="NO"; }
			
	switch ($_GET['action']) {	
	
	case "archives": 
		archives();
	break;
	case "contact": 
		contact();
	break;
	case "rss": 
		rss();
	break;
	case "login": 
		login();
	break;
	case "settings": 
		if (isset($_SESSION['Username'])) { settings(); }
	break;
	case "categories": 
		if (isset($_SESSION['Username'])) { view_categories(); }
	break;
	case "editcategory":
		if (isset($_SESSION['Username'])) { edit_category(); }
	break;
	case "new": 
		if (isset($_SESSION['Username'])) { new_article(); }
	break;
	case "unpublished": 
		if (isset($_SESSION['Username'])) { unpublished_articles(); }
	break;
	case "simpleedit":
		if (isset($_SESSION['Username'])) { edit_article(simple); }
	break;
	case "advancededit":
		if (isset($_SESSION['Username'])) { edit_article(advanced); }
	break;
	case "editcomment":
		if (isset($_SESSION['Username'])) { edit_comment(); }
	break;
	case "images":
		if (isset($_SESSION['Username'])) { images(); }
	break;
	case "process": 
		if (isset($_SESSION['Username']) AND $display_further <> "NO") { processing(); }
	break;	
	case "logout":
    	session_start();
	    $_SESSION = array();
	    session_destroy();
        echo "<META HTTP-EQUIV=\"refresh\" content=\"1; URL=" . $_SERVER['PHP_SELF'] . "\">";
    break; 
	default: 
	if (isset($_POST['search'])) { search(); } 
	else if (isset($_POST['comment'])) { comment("comment_posted");} 
	else if ($display_further <> "NO") {		
		$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
  		mysql_select_db(s('dbname')) or die(s('dberror'));
   		if ($id <> "") {
	  		$query = "SELECT * FROM " .s('prefix'). "articles WHERE id = $id";
	  		$shorten = 99990000;
		} else {
			$category = $_GET['category'];
			if ($category == "") { $category = 0; }
			$query = "SELECT * FROM " .s('prefix'). "articles WHERE position = 1 AND category = $category ORDER BY id DESC LIMIT $article_limit"; 
			$shorten = $r['textlimit'];
			echo $r['textlimit'];
			if ($shorten == 0) { $shorten = 9999000; }
		}
		$result = mysql_query($query);
		while ($r = mysql_fetch_array($result)) {
	  		if ($id == "") { 
		  		$shorten = $r['textlimit'];
		  		if ($shorten == 0) { $shorten = 99990000; }}
		  	$comments_num = 0;
	  		$comment_query = "SELECT * FROM " .s('prefix'). "comments WHERE articleid = $r[id]"; 
	  		$comment_result = mysql_query($comment_query);
  			while ($comment_r = mysql_fetch_array($comment_result)) { $comments_num++; }
      		$date = date(s('date_format'), strtotime($r['date']));
      		$fp_date_format = date(s('fp_date_format'), strtotime($r['date']));
      		      		
      		if ($id <> "") {
	      		$article_title = $r['title'];	
	  			if ($r['displaytitle'] == "YES") { echo "<h2>". $r['title'] ."</h2>"; }
	  			$position = $r['position'];
	  			$id = $r[id];
				}  else if ($r['displaytitle'] == "YES") { echo "<h2><a href=\"index.php?id=" .$r['id']. "\">" .$r['title']. "</a></h2>"; }
				if ($r['image'] <> "" AND $show <> "archives") { ?>
					<div class="image">
						<img src="<? echo s('image_folder'); ?>/<? echo $r['image']; ?>" alt="<? echo $r['title']; ?>" />
					</div><? }
      			
				echo substr(stripslashes($r['text']), 0, $shorten);
				$numrows++;
      			if ($id == "" AND strlen($r['text']) > $shorten) { echo "...</p>"; }
      			$commentable = $r['commentable'];
      	   		if ($r['position'] <> 3 OR isset($_SESSION['Username'])) {
      				if ($id == "") {
	      				if ($r['displayinfo'] == "YES") {
		      			echo "<p class=\"date\">";
		      		if (strlen($r['text']) > $shorten) {		      			
		      			echo "<img src='images/more.gif' alt='' /> <a href=\"index.php?id=" .$r['id']. "\">". l('read_more') ."</a> ";
	      			}
	      				if ($commentable == "YES" or $commentable == "FREEZ") {
		      				echo "<img src='images/comment.gif' alt='' /> <a href='index.php?id=$r[id]'>". l('comments') ."(". $comments_num .")</a> ";
	      				}	      				
	      				echo "<img src='images/timeicon.gif' alt='' /> " .$fp_date_format. "</p>"; 
      					}} else { 
	      				echo "<p class=\"date\">";
	      				if (isset($_SESSION['Username']))  {
	      					echo l('edit_article'). "[ <a href=\"index.php?action=simpleedit&id=$r[id]\">". l('simple') ."</a> <img src=\"images/arrow.gif\" alt=\"|\" /> <a href=\"index.php?action=advancededit&id=$r[id]\">". l('advanced') ." </a> ] <img src=\"images/arrow.gif\" alt=\"|\" /> <a href=\"index.php?action=process&task=delete&amp;id=". $id ."\">". l('delete_article') ."</a> <img src=\"images/arrow.gif\" alt=\"|\" /> ";
	      					
	      					if ($r['commentable'] == "FREEZ") { echo "<a href=\"index.php?action=process&task=unfreezecomments&id=$r[id]\">". l('unfreeze_comments') ."</a> <img src=\"images/arrow.gif\" alt=\"|\" /> ";
	      					} else if ($r['commentable'] <> "NO") { echo "<a href=\"index.php?action=process&task=freezecomments&id=$r[id]\">". l('freeze_comments') ."</a> <img src=\"images/arrow.gif\" alt=\"|\" /> "; }
	      				}
      					echo "<a href=\"index.php?category=". $r['category'] ."\">". l('back') ."</a>&nbsp;&nbsp;<img src='images/timeicon.gif' alt='' /> ". $date ."</p>"; 
      					echo $commentnum;
      		}}}}
			if ($id <> "" AND $commentable == "YES") { comment("unfreezed"); } 
      		else if ($id <> "" AND $commentable == "FREEZ") { comment("freezed"); } 
			if ($numrows == 0 AND $id <> "") { echo "<h2>". l('article_not_exist') ."</h2>"; }
}}

// COMMENTS
function comment($freeze_status) {
	$articleid = $_POST['id'];
	$commentspage = $_POST['commentspage'];
	if ($commentspage == 0) { $commentspage = 1; }
	if (isset($_POST['comment']) AND strlen($_POST['name']) > 2 AND strlen($_POST['comment']) > 5) {
		echo "<h2>". l('comment_sent') ."</h2>";
		echo "<p><a href=\"index.php?id=" .$articleid. "&commentspage=" .$commentspage. "\">". l('backarticle'). "</a></p>";
		$name = $_POST['name'];
		$comment = $_POST['text'];
		$time = date('Y-m-d H:i:s');
		$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
        mysql_select_db(s('dbname')) or die(s('dberror'));
        mysql_query("INSERT INTO ". s('prefix')."comments(articleid,name,comment,time) VALUES('$articleid', '$name', '$comment', '$time')"); 
        mysql_close($db);
	} else if (isset($_POST['comment'])) {
		echo "<h2>". l('comment_error') ."</h2>";
		echo "<p>". l('ce_reasons') ."</p>";
		echo "<p><a href=\"index.php?id=" .$articleid. "&commentspage=" .$commentspage. "\">". l('back'). "</a></p>";
    } else { 
	    $articleid = $_GET['id'];
	$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
  	mysql_select_db(s('dbname')) or die(s('dberror'));
  	$results_per_page = s('results_per_page');
	$pageNum = 1;
	if(isset($_GET['commentspage'])) { $pageNum = $_GET['commentspage']; }
	$offset = ($pageNum - 1) * $results_per_page;
	$totalrows  = "SELECT * FROM " .s('prefix'). "comments WHERE articleid = $articleid";
	$rowsresult = mysql_query($totalrows, $db) or die(s('dberror'));
	$rowu = mysql_fetch_assoc($rowsresult);
	$numrows = mysql_num_rows($rowsresult);
	$query  = "SELECT * FROM " .s('prefix'). "comments WHERE articleid = $articleid ORDER by id ASC LIMIT $offset, $results_per_page";
	$result = mysql_query($query, $db) or die(s('dberror'));
	while ($r = mysql_fetch_array($result)) {
			echo "<div class=\"comments\"><p>" .$r['comment']. "</p>";
			$date = date(s('comment_dt_format'), strtotime($r['time']));
			echo "<p><img src=\"images/commentname.gif\" alt=\">\" /> <b>" .$r['name']. "</b>";
		if  (date("Y", strtotime($r['time'])) == 1999 OR s('display_comment_time') == "NO") { 
			$date = ""; 
		} else {
			echo " <img src=\"images/arrow.gif\" alt=\"|\" /> ";
			echo $date;				
		}
		if (isset($_SESSION['Username'])) { echo " <img src=\"images/arrow.gif\" alt=\"|\" /> "; ?>
			<a href="index.php?action=process&action=editcomment&commentid=<?php echo $r['id']; ?>"><? echo l('edit'); ?></a> <img src="images/arrow.gif" alt="|" /> <a href="index.php?action=process&task=deletecomment&articleid=<? echo $articleid; ?>&commentid=<? echo $r['id']; ?>"><? echo l('delete_comment'); ?></a> <? 
		} echo "</p></div>"; }
			$maxPage = ceil($numrows/$results_per_page);
			$back_to_page = ceil(($numrows + 1)/$results_per_page);
			$self = $_SERVER['PHP_SELF'];
			if ($pageNum > 1) {
    			$page = $pageNum - 1;
    			$prev = " <a href=\"index.php?id=". $articleid ."&commentspage=$page\">< " .l('previous_page'). " </a> ";
    			$first = " <a href=\"index.php?id=". $articleid ."&commentspage=1\"><< " .l('first_page')." </a> "; 
    		} else { $prev  = "< " .l('previous_page'); $first = "<< " .l('first_page'); }
				if ($pageNum < $maxPage) {
    				$page = $pageNum + 1;
    				$next = " <a href=\"index.php?id=". $articleid ."&commentspage=$page\">" .l('next_page'). " ></a> ";
        			$last = " <a href=\"index.php?id=". $articleid ."&commentspage=$maxPage\">" .l('last_page'). " >></a> ";
				} else { $next = l('next_page'). " > "; $last = l('last_page'). " >>"; }
					if ($maxPage > 1) { echo "<div class=\"date\">" .$first ." ". $prev . " <strong>  [$pageNum</strong> / <strong>$maxPage]  </strong> " . $next ." ". $last ."</div>"; }
						
					if ($freeze_status <> "freezed") { ?>
					<div class="commentsbox">
						<h2><? echo l('addcomment') ?></h2>	
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  							<p><? echo l('comment'); ?></p>
  							<p><textarea name="text" class="text" rows="5" cols="5"></textarea></p>
							<p><? echo l('name'); ?></p>
							<p><input name="name" type="text" class="field" id="name" /></p>
  							<p><input name="id" type="hidden" id="id" value="<? echo $_GET['id']; ?>" /></p>
  							<p><input name="commentspage" type="hidden" id="commentspage" value="<? echo $back_to_page; ?>" /></p>
  							<p><input name="comment" type="submit" value="<? echo l('sendcomment'); ?>" /></p>
  	    				</form>
						</div><?
}}}

// RIGHT			
function right() {	
	$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
  	mysql_select_db(s('dbname')) or die(s('dberror'));
  	$query = "SELECT * FROM " .s('prefix'). "articles WHERE position = 2 ORDER BY id DESC"; 
	$result = mysql_query($query);
  	while ($r = mysql_fetch_array($result)) {
    	if (isset($_SESSION['Username'])) { echo "<a href=\"index.php?action=edit&id=$r[id]\">". l(edit_article) ."</a> <img src=\"images/arrow.gif\" alt=\"|\" /> <a href=\"index.php?action=process&task=delete&amp;id=". $id ."\">". l(delete_article) ."</a>"; }
		if ($r['displaytitle'] == "YES") { echo "<h2>". $r['title'] ."</h2>"; }
    	echo $r['text'];
	} 
} 

// ARCHIVES
function archives() {
	echo "<h2>". l('archives') ."</h2>";
	echo "<br /><p><b>". l('home') ."</b></p>";
	$query = "SELECT * FROM " .s('prefix'). "articles WHERE position = 1 AND category = 0 ORDER BY id DESC"; 
	$result = mysql_query($query);
	while ($r = mysql_fetch_array($result)) {
		$date = date(s('date_format'), strtotime($r['date']));
		echo "<p><img src='images/arrow.gif' alt='' /> <a href=\"index.php?id=" . $r['id'] ."\" title=\"". $c['description'] ."\">". $r['title'] ."</a> <img src='images/arrow.gif' alt='' /> ". $date ."</p>";
	}	
	$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
	mysql_select_db(s('dbname')) or die(s('dberror'));
	$cat_query = "SELECT * FROM " .s('prefix'). "categories"; 
	$cat_result = mysql_query($cat_query);
	while ($c = mysql_fetch_array($cat_result)) {
		echo "<br /><p><b>". $c['name'] ."</b> <img src='images/arrow.gif' alt='' /> ". $c['description'] ."</p>";
		echo "<p><a href=\"index.php?id=" .$r['id']. "\">" .$r['title']. "</a></p> ";
		$catid = $c['id'];
		$query = "SELECT * FROM " .s('prefix'). "articles WHERE category = $catid"; 
		$result = mysql_query($query);
			while ($r = mysql_fetch_array($result)) {
				$date = date(s(date), strtotime($r['date']));
				echo "<p><img src='images/arrow.gif' alt='' /> <a href=\"index.php?id=" . $r['id'] ."\">". $r['title'] ."</a> <img src='images/arrow.gif' alt='' /> ". $date ."</p>";
}}} 


// CONTACT
function contact() { 
if ($_POST['show'] = "contact" AND $_POST['contactform'] == "") {?>
	<h2><? echo l('contact'); ?></h2>
	<form method="post" action="index.php?action=contact">
		<p><? echo l('name'); ?>:<br /></p>
		<p><input name="name" type="text" id="name" class="field" /></p>
		<p><br /><? echo l('email'); ?>:</p>
		<p><input name="email" type="text" id="email" class="field" /></p>
		<p><br /><? echo l('url'); ?>:</p>
		<p><input name="weblink" type="text" id="weblink" class="field" /></p>
		<p><br /><? echo l('message'); ?>:</p>
  		<p><textarea name="message" rows="4" cols="5" class="text"></textarea></p>
     	<p><br /><input name="contactform" type="submit" value="<? echo l('send_message'); ?>" /></p>
	</form>
  	<? } 
if (isset($_POST['contactform'])) {
	$to = s('website_email');
	$subject = s(contact_subject);
	$body = l('name') .": ". $_POST['name'] ."\n";
	$body .= l('email') .": ". $_POST['email'] ."\n";
	$body .= l('url') .": ". $_POST['weblink'] ."\n\n";
	$body .= l('message') .": ". $_POST['message'] ."\n";
if (strlen($_POST['name']) > 1 AND strlen($_POST['message']) > 1) {
	mail($to, $subject, $body);
	echo "<h2>". l('contact_sent') ."</h2>";
  	echo "<p><a href='index.php'>". l('backhome') ."</a></p>";
} else { 
	echo "<h2>". l('contact_not_sent') ."</h2>";
	echo "<p>". l('message_error') ."</p>";
	echo "<p><a href='index.php'>". l('backhome') ."</a></p>";
}}}
	
// NEW ARTICLES
function new_articles($number) {
	$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
	mysql_select_db(s('dbname')) or die(s('dberror'));
	$tl = s('show_articles');
	$query = "SELECT * FROM " .s('prefix'). "articles WHERE position = 1 ORDER BY id DESC LIMIT 0, $number"; 
	$result = mysql_query($query);
	while ($r = mysql_fetch_array($result)) {
		echo "<p><a href=\"index.php?id=$r[id]\">" .$r['title']. "</a></p> ";
		if ($r['category'] == 0 OR $r['category'] == "") {
			echo "<p>". l(category) ." <img src='images/arrow.gif' alt='' /> <a href=\"index.php\" title=\"Home\">". l('home') ."</a></p>";$output = "<p>". l(category) ." <img src='images/arrow.gif' alt='' /> <a href=\"index.php\" title=\"Home\">". l('home') ."</a></p>";
		} else {
			$category = $r['category'];
			$cat_query = "SELECT * FROM " .s('prefix'). "categories WHERE id = $category"; 
			$cat_result = mysql_query($cat_query);
			while ($c = mysql_fetch_array($cat_result)) {
				echo "<p>". l(category) ." <img src='images/arrow.gif' alt='' /> <a href=\"index.php?category=" . $c['id'] ."\" title=\"". $c['description'] ."\">". $c['name'] ."</a></p>";
	}}}	
}

// PAST ENTRIES
function past_entries($ts, $tl) {
	$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
	mysql_select_db(s('dbname')) or die(s('dberror'));
	if ($_GET['category'] <> 0) { $category = $_GET['category']; } else { $category = 0; };
	$query = "SELECT * FROM " .s('prefix'). "articles WHERE position = 1 AND category = $category ORDER BY id DESC LIMIT $ts, $tl"; 
	$result = mysql_query($query);
	while ($r = mysql_fetch_array($result)) {
		$date = date(s('date_format'), strtotime($r['date']));
		echo "<p><a href='index.php?id=$r[id]'>$r[title]</a> <img src='images/arrow.gif' alt='' /> ";
		echo $date;
		echo "</p>"; }
}
	
// SEARCH FORM
function searchform() { ?>
	<form method="post" class="search" action="<?php echo $_SERVER['PHP_SELF']; ?>" />
		<input name="pojam" type="text" class="text" id="pojam" />
  	<input type="submit" name="search" class="button" value="<? echo l(search_button); ?>" />
<?  					
}

// SEARCH ENGINE
function search() {
	$search_query = $_POST['pojam'];
	$pojam = "%".$search_query."%";
	echo "<h2>". l(search_results) ."</h2>";
	if (strlen($search_query) < 4) {
		echo "<p>". l(charerror) ."</p>";
	} else {
		$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
		mysql_select_db(s('dbname')) or die(s('dberror'));
		$query = "SELECT * FROM " .s('prefix'). "articles WHERE title LIKE '$pojam' || text LIKE '$pojam' AND position <> 2 ORDER BY id DESC";
		$result = mysql_query($query);
		while ($r = mysql_fetch_array($result)) {
			$broj++;
			echo "<p><a href='index.php?id=$r[id]'>$r[title]</a> > ";
			echo $r[date];
			echo "</p>";}
	if ($broj == "") { echo "<p>". l(noresults) ." <b> " . $search_query . "</b>.</p>";
		$broj = "0";
	} else { echo "<br /><p><b>" . $broj . "</b> ". l(resultsfound) ."<b> " . $search_query . "</b>.</p>"; }}
	echo "<p><br /><a href='index.php'>". l('backhome') ."</a></p>";
}


// RSS FEED
function rss() {
	$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
	mysql_select_db(s('dbname')) or die(s('dberror'));
	$query = "SELECT * FROM " .s('prefix'). "articles WHERE position = 1 ORDER BY id DESC"; 
	$result = mysql_query($query);
	$filename = "rss.xml";
	$header = "<?xml version=\"1.0\" ?>";
	$header .= "<rss version=\"2.0\">";
	$header .= "<channel>";
	$footer = "</channel>";
	$footer .= "</rss>";
	$fh = fopen($filename, "w+");
	fwrite($fh, $header);
	while ($r = mysql_fetch_assoc($result)){
   		$pattern="'<[\/\!]*?[^<>]*?>'si";
   		$replace="";
   		$description = preg_replace($pattern, $replace,	stripslashes($r['text']));
   		$item  ="<item>";
   		$item .= "<title>". $r['title'] ."</title>";
   		$item .= "<description>". $description ."</description>";
   		$item .= "<link>http://". s(website) ."/index.php?id=". $r['id'] ."</link>";
   		$item .= "</item>";
   		fwrite($fh, $item);
  }
	fwrite($fh, $footer);
	fclose($fh);
	echo "<script>self.location='rss.xml';</script>";
}

//*******************************************************************************
//                             ADMINISTRATIVE FUNCTIONS
//*******************************************************************************

//********
// LOGIN 
//********
function login() {
	if ($_SESSION['Logged_In'] != "True") {
    	echo "<h2>Login</h2>";
		echo "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">
    	<p><br />". l(username) .": <input type=\"textbox\" class=\"text\" name=\"Username\" /></p>
    	<p>". l(password) .": <input type=\"password\" class=\"text\" name=\"Password\" /></p>
    	<p><input type=\"hidden\" name=\"Submitted\" value=\"True\"></p>
    	<p><input type=\"Submit\" name=\"Submit\" value=\"". l(login) ."\"></p>
    	</form>";
	} else {
		echo "<h2>" .l('already_logged'). "</h2><br />";
		echo "<p><a href=\"index.php?action=logout\">". l('logout') ."</a></p>";
}}

//************************
// CATEGORIES (ADD, VIEW)
//************************
function view_categories() { ?>
	<h2><? echo l(categories); ?></h2> 
	<p>Home</p> <?
	$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
  	mysql_select_db(s('dbname')) or die(s('dberror'));
  	$query = "SELECT * FROM " .s('prefix'). "categories ORDER BY id"; 
	$result = mysql_query($query);
  	while ($r = mysql_fetch_array($result)) {
    	if (isset($_SESSION['Username'])) { echo "<p>". $r['name'] ." <img src=\"images/arrow.gif\" alt=\"|\" /> <a title=\"". $r['description'] ."\" href=\"index.php?action=editcategory&id=$r[id]\">". l(edit_category) ."</a> <img src=\"images/arrow.gif\" alt=\"|\" /> <a href=\"index.php?action=process&task=deletecategory&id=$r[id]\">". l(delete_category) ."</a> <img src=\"images/arrow.gif\" alt=\"|\" /> ";
    		if ($r['published'] == "YES") { echo l('published'); } else { echo l('unpublished'); }}
				echo "</p>";
    		} 
			echo "<br /><br />";
	    	echo "<h2>". l(add_category) ."</h2>"; ?>
			<form name="post-text" method="post" action="index.php?action=process&task=add_category"> 
    			<p><? echo l('name'); ?>:</p>
      			<p><input type="text" class="field" name="name" /></p>
      			<p><? echo l('description'); ?>:</p>
      			<p><input type="text" class="field" name="description" /></p>
				<p><input type="checkbox" value="YES" name="publish" checked> <? echo l('publish_category'); ?></p>
      			<p><input type="hidden" name="task" value="add_category" /></p>
    			<p><input type="submit" name="submit_text" value="<? echo l(add_category); ?>" /></p>
    		</form><?
}

//***************			
// EDIT CATEGORY 
//***************
function edit_category() { ?>
	<h2><? echo l('edit_category') ?></h2><?
	$categoryid = $_GET['id'];
	$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
  	mysql_select_db(s('dbname')) or die(s('dberror'));
  	$query = "SELECT * FROM " .s('prefix'). "categories WHERE id = $categoryid"; 
	$result = mysql_query($query);
  	while ($r = mysql_fetch_array($result)) {
    	if (isset($_SESSION['Username'])) { 
	    	echo "<p>". $r['name'] ." <img src=\"images/arrow.gif\" alt=\"|\" /> <a href=\"index.php?action=process&task=deletecategory&id=$r[id]\">". l(delete_category) ."</a> <img src=\"images/arrow.gif\" alt=\"|\" /> ";
    		if ($r['published'] == "YES") { echo l('published'); } else { echo l('unpublished'); }
				echo "</p>"; }
    		echo "<br /><br />"; ?>
	    	<form name="post-text" method="post" action="index.php?action=process&task=edit_category"> 
    			<p>    			    			
    			<? echo l('name'); ?>:<br /><input type="text" class="field" value="<?php echo $r['name']; ?>" name="name" /><br /><br />
    			<? echo l('description'); ?>:<br /><input type="text" class="field" value="<?php echo $r['description']; ?>" name="description" /><br /><br /><?
      			if ($r['published'] == "YES") { ?>
	      			<input type="checkbox" value="YES" name="publish" checked> <? echo l('publish_category');
      			} else {	?>
	      			<input type="checkbox" value="YES" name="publish"> <? echo l('publish_category'); 
	      			 } ?><br /><br />
	      		<input type="hidden" name="id" value="<?php echo $categoryid; ?>">
	      		<input type="hidden" name="task" value="edit_category">
	      		<input type="submit" name="submit_text" value="<? echo l('edit_category'); ?>">
    			</p>
      		</form>
      		<? }
}


//*************
// NEW ARTICLE 
//*************
function new_article() { ?>
	<h2><? echo l('new_article'); ?></h2>
  	<form name="post-text" method="post" action="index.php?action=process&task=new"> 
    	<p><br /><? echo l('title'); ?>:</p>
    	<p><input type="text" name="title" class="field" /></p>
      	<p><? echo l('text'); ?>:</p>
      	<p><textarea name="text" class="text"></textarea></p>
      	<p><? echo l('limit_article'); ?>:</p>
      	<p><input type="text" name="text_limit" value="500" class="field" /></p>
      	<p><br /><input type="checkbox" value="ON" name="auto_html" checked> <? echo l('auto_html'); ?></p>
      	<p><br /><? echo l('category'); ?>:
      	<select name="category" class="text">
	    	<option value="0"><? echo l('home'); ?></option> <?
			$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
			mysql_select_db(s('dbname')) or die(s('dberror'));
			$query = "SELECT * FROM " .s('prefix'). "categories ORDER BY id"; 
			$result = mysql_query($query);
			while ($r = mysql_fetch_array($result)) { echo "<option value=\"". $r['id'] ."\">". $r['name'] ."</option>"; } ?>
		</select></p>
    	<p><br /><? echo l('position'); ?>:</p>
		<p><input type="radio" value="3" name="position"> <? echo l('display_menu_item'); ?></p>
      	<p><input type="radio" value="5" name="position"> <? echo l('left'); ?></p>
      	<p><input type="radio" value="1" checked name="position"> <? echo l('center'); ?></p>
      	<p><input type="radio" value="2" name="position"> <? echo l('right'); ?></p>
      	<p><br /><input type="checkbox" value="YES" name="display_title" checked> <? echo l('display_title'); ?></p>
      	<p><input type="checkbox" value="YES" name="display_info" checked> <? echo l('display_info'); ?></p>
      	<p><input type="checkbox" value="YES" name="commentable"> <? echo l('enable_commenting'); ?></p>
      	<p><input type="checkbox" value="ON" name="publish" checked> <? echo l('publish_article'); ?></p>
      	<p><br /><? echo l('attach_image'); ?>:
      	<select name="image" class="text">
	    	<option value=""><? echo l('no_image'); ?></option> <?
				$upload_dir = s('image_folder') ."/";	
				$rep=opendir($upload_dir);
				while ($file = readdir($rep)) {
					if($file != '..' && $file !='.' && $file !=''){
						if (!is_dir($file)){
    		    			$folder=substr($file, 0, -4);
							echo "<option value='$file'>$folder</option>";
        				}
					}
				}
				closedir($rep);
				clearstatcache(); ?>
		</select></p>				    				
    	<p><input type="hidden" name="task" value="new"></p>
    	<p><input type="submit" name="submit_text" value="<? echo l('submit_new_article'); ?>"></p>
    	</p>
	</form> <?
}
    
    
//**********************			
// UNPUBLISHED ARTICLES
//**********************
function unpublished_articles() {	
	echo "<h2>". l('unpublished_articles') ."</h2>";
	$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
  	mysql_select_db(s('dbname')) or die(s('dberror'));
  	$query = "SELECT * FROM " .s('prefix'). "articles WHERE position = 4 ORDER BY id DESC"; 
	$result = mysql_query($query);
  	while ($r = mysql_fetch_array($result)) {
	  	$date = date(s('date_format'), strtotime($r['date']));
    	if (isset($_SESSION['Username'])) { echo "<p><a title=\"". l('edit_article') ."\" href=\"index.php?action=edit&id=$r[id]\">". $r['title'] ."</a> <img src=\"images/arrow.gif\" alt=\"|\" /> ". $date ."</p>"; 
    	}
	echo "</p>";
	} 
} 	
    
//**************			
// EDIT ARTICLE 
//**************
function edit_article($mode) { ?>
          	<h2><? echo l('edit_article') ?></h2><?
			$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
  			mysql_select_db(s('dbname')) or die(s('dberror'));
			$id = $_REQUEST['id'];
  			$query = mysql_query("SELECT * FROM " .s('prefix'). "articles WHERE id='$id'");
  			$r = mysql_fetch_array($query);
  			$article_category = $r['category']; ?>
  			<form name="post-text" method="post" action="index.php?action=process&task=edit&amp;id=<?php echo $id; ?>"> 
    		<p><br /><? echo l('title'); ?>:</p>
    		<p><input type="text" name="title" value="<?php echo $r[title]; ?>" class="field" /></p>
      		<p><? echo l('text'); ?>:</p>
      		<? if ($mode == "advanced") {	$text = $r['text']; } 
      		   if ($mode == "simple") { $text = str_replace(array("<br />", "<p>", "</p>"), "" , $r[text]); } ?>
      		<p><textarea name="text" class="text"><? echo stripslashes($text); ?></textarea></p>
      		<p><? echo l('limit_article'); ?>:</p>
      		<p><input type="text" name="text_limit" value="<? echo $r['textlimit']; ?>" class="field" /></p>
      		<p><br /><? echo l('category'); ?>:
      		<select name="category" class="text">
      	   		<option value="category" <? if ($article_category == 0) { echo "selected"; } ?>><? echo l('home'); ?></option> <?
					$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
					mysql_select_db(s('dbname')) or die(s('dberror'));
					$category_query = "SELECT * FROM " .s('prefix'). "categories ORDER BY id"; 
					$category_result = mysql_query($category_query);
					while ($cat = mysql_fetch_array($category_result)) { 
						echo "<option value='". $cat['id'] ."'";
						if ($article_category == $cat['id']) { echo "selected"; }						
						echo ">". $cat['name'] ."</option>"; } ?>
					</select></p>
      		<p><br /><? echo l('position'); ?>:<br /></p>
      		<? if ($r['position'] == 3) { ?>
	      		<p><input type="radio" value="3" name="position" checked> <? echo l('display_menu_item'); ?></p>
    		<? } else { ?>
    			<p><input type="radio" value="3" name="position"> <? echo l('display_menu_item'); ?></p>
    		<? }
    		if ($r['position'] == 5) { ?>
	      		<p><input type="radio" value="5" name="position" checked> <? echo l('left'); ?></p>
    		<? } else { ?>
    			<p><input type="radio" value="5" name="position"> <? echo l('left'); ?></p>
    		<? }
    		if ($r['position'] == 1) { ?>
	      		<p><input type="radio" value="1" name="position" checked> <? echo l('center'); ?></p>
    		<? } else { ?>
    			<p><input type="radio" value="1" name="position"> <? echo l('center'); ?></p>
    		<? }
    		if ($r['position'] == 2) { ?>
	      		<p><input type="radio" value="2" name="position" checked> <? echo l('right'); ?></p>
    		<? } else { ?>
    			<p><input type="radio" value="2" name="position"> <? echo l('right'); ?></p>
    		<? }
    		if ($r['displaytitle'] == "YES") { ?>
	      		<p><br /><input type="checkbox" value="YES" name="display_title" checked> <? echo l('display_title'); ?></p>
    		<? } else { ?>
    			<p><input type="checkbox" value="YES" name="display_title"> <? echo l('display_title'); ?></p>
    		<? }
    		if ($r['displayinfo'] == "YES") { ?>
	      		<p><input type="checkbox" value="YES" name="display_info" checked> <? echo l('display_info'); ?></p>
    		<? } else { ?>
    			<p><input type="checkbox" value="YES" name="display_info"> <? echo l('display_info'); ?></p>
    		<? }    		
    		if ($r['commentable'] == "YES") { ?>
	      		<p><input type="checkbox" value="YES" name="commentable" checked> <? echo l('enable_commenting'); ?></p>
    		<? } else { ?>
    			<p><input type="checkbox" value="YES" name="commentable"> <? echo l('enable_commenting'); ?></p>
    		<? }
    		if ($r['position'] == 4) { ?>
	      		<p><input type="checkbox" value="ON" name="publish"> <? echo l('publish_article'); ?></p>
    		<? } else { ?>
    			<p><input type="checkbox" value="ON" name="publish" checked> <? echo l('publish_article'); ?></p>
    		<? } 
    		echo "<p><br />" .l('attach_image'); ?>:
      		<select name="image" class="text"><?
    		if (stripslashes($r['image']) == "") { echo "<option value='' selected>". l('no_image') ."</option>"; } 
    		else { echo "<option value=''>". l('no_image') ."</option>"; }
    				$upload_dir = s('image_folder') ."/";
					$rep=opendir($upload_dir);
					while ($file = readdir($rep)) {
						if($file != '..' && $file !='.' && $file !=''){
							if (!is_dir($file)){
    		    				$folder=substr($file, 0, -4);
					    	    		    			
    		    				if ($file == stripslashes($r['image'])) {		    				
	    		    		  		echo "<option value='$file' selected>$folder</option>";
    		    				} else if ($folder <> "Thumb") {
	    		       				echo "<option value='$file'>$folder</option>";
    		       			}}}} ?>
				</select></p>
    		<p><input type="hidden" name="id" value="<?php echo $id; ?>"></p> <?
    		if ($mode == "simple") { ?><p><input type="hidden" name="task" value="simpleedit"></p> <? }
    		if ($mode == "advanced") { ?><p><input type="hidden" name="task" value="advancededit"></p> <? } ?>
    		<p><input type="submit" name="submit_text" value="<? echo l('edit'); ?>"></p>
  			</form><? 
}


//**************			
// EDIT COMMENT 
//**************
function edit_comment() { ?>
          	<h2><? echo l('edit_comment') ?></h2><?
			$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
  			mysql_select_db(s('dbname')) or die(s('dberror'));
			$commentid = $_REQUEST['commentid'];
			$query = mysql_query("SELECT * FROM " .s('prefix'). "comments WHERE id='$commentid'");
  			$r = mysql_fetch_array($query);
  			$text = $r['comment']; ?>
  			<form name="post-text" method="post" action="index.php?action=process&task=editcomment&amp;id=<?php echo $id; ?>"> 
    			<p><br /><? echo l('comment'); ?>:</p>
      			<p><textarea name="editedcomment" class="text"><? echo stripslashes($text); ?></textarea></p>
    			<p><? echo l('name'); ?>:</p>
    			<p><input type="text" name="name" value="<?php echo $r['name']; ?>" class="field" /></p>
    			<p><input type="hidden" name="id" value="<?php echo $r['articleid']; ?>"></p>
    			<p><input type="hidden" name="task" value="editcomment"></p>
				<p><input type="submit" name="submit_text" value="<? echo l('edit'); ?>"></p>
			</form>
    		
<?}

//*********************************************
// PROCESSING (CATEGORIES, ARTICLES, COMMENTS)
//*********************************************
function processing() {
	$action = $_REQUEST['action'];
  	$id = $_REQUEST['id'];
  	$name = $_POST['name'];
  	$category = $_POST['category'];
  	$description = $_POST['description'];
  	$title = $_POST['title'];
  	$comment = $_POST['editedcomment'];
  	$text = $_POST['text'];
  	$text_limit = $_POST['text_limit'];
  	$auto_html = $_POST['auto_html'];
  	$date = date('Y-m-d H:i:s'); 
  	$display_title = $_POST['display_title'];
  	$display_info = $_POST['display_info'];
  	$commentable = $_POST['commentable'];
  	$publish = $_POST['publish'];
  	$publish_category = $_POST['publish'];
  	$position = $_POST['position'];
  	$display = $_POST['display'];
  	$image = $_POST['image'];
  	  	 	
  	if ($text_limit == "") { $text_limit = 0; } 
  	if ($position == "") { $position = 1; } 
  	if ($commentable == "") { $commentable = "NO"; }
  	if ($publish <> "ON") { $position = 4; }	
  	if ($display_title == "") { $display_title = "NO"; }
  	if ($display_info == "") { $display_info = "NO"; }	  										
  				
   	if ($_POST['task'] == "add_category") {
    	if ($_POST['submit_text']) {
      		if ($name == "")  { echo "<h2>". l('error') ."</h2><p>". l('error') ."</p><p><a href=\"index.php?action=categories\">". l('back') ."</a></p>"; }
      		else { 
        		$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
        		mysql_select_db(s('dbname')) or die(s('dberror'));
        		mysql_query("INSERT INTO ". s('prefix'). "categories(name,description,published) VALUES('$name', '$description', '$publish_category')"); 
        		echo "<h2>". l('operation_completed') ."</h2><p><a href=\"index.php?action=categories\">". l('back') ."</a></p>";
        		mysql_close($db);
	}}} 
      			
    if ($_POST['task'] == "edit_category") {
    	if ($_POST['submit_text']) {
    		$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
      		mysql_select_db(s('dbname')) or die(s('dberror'));
      		mysql_query("UPDATE ". s('prefix'). "categories SET name='$name' WHERE id='$id'");
      		mysql_query("UPDATE ". s('prefix'). "categories SET description='$description' WHERE id='$id'");
      		mysql_query("UPDATE ". s('prefix'). "categories SET published='$publish_category' WHERE id='$id'");
      		echo "<h2>". l('operation_completed') ."</h2><p><a href=\"index.php?action=categories\">". l('back') ."</a></p>";
      		mysql_close($db);
    }}
    			 					
  	else if ($_POST['task'] == "new") {
    	if ($_POST['submit_text']) {
      		if ($title == "")  { echo "<h2>". l('admin_error') ."</h2><p>" .l('title_error'). "</p><p><a href=\"index.php?action=new\">". l('back') ."</a></p>"; }
      		else if ($text == "")  { echo "<h2>. l('admin_error') .</h2><p>". l('text_error') ."</p><p><a href=\"index.php?action=new\">". l('back') ."</a></p>"; }
      		else { 
        		if ($auto_html == "ON") { 
	        		
	        	$text = str_replace('<p></p>', '', '<p>' . preg_replace('#\n|\r#', '</p>$0<p>', $text) . '</p>'); }
	      		$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
        		mysql_select_db(s('dbname')) or die(s('dberror'));
        		mysql_query("INSERT INTO ". s('prefix'). "articles(title,text,textlimit,date,category,position,displaytitle,displayinfo,commentable,image) VALUES('$title', '$text', '$text_limit', '$date', '$category', '$position', '$display_title', '$display_info', '$commentable', '$image')"); 
        		echo "<h2>". l('operation_completed') ."</h2><p><a href=\"index.php\">". l('back') ."</a></p>";
        		mysql_close($db);
	}}}
	
	else if ($_POST['task'] == "simpleedit") {
    	$text = str_replace('<p></p>', '', '<p>' . preg_replace('#\n|\r#', '</p>$0<p>', $text) . '</p>');
		$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
      	mysql_select_db(s('dbname')) or die(s('dberror'));
      	mysql_query("UPDATE ". s('prefix'). "articles SET title='$title' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET text='$text' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET textlimit='$text_limit' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET category='$category' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET position='$position' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET displaytitle='$display_title' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET displayinfo='$display_info' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET commentable='$commentable' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET image='$image' WHERE id='$id'");
      	echo "<h2>". l('operation_completed') ."</h2><p><a href=\"index.php?id=" .$id. "\">". l('back') ."</a></p>";
      	mysql_close($db);
    }
    else if ($_POST['task'] == "advancededit") {
    	
		$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
      	mysql_select_db(s('dbname')) or die(s('dberror'));
      	mysql_query("UPDATE ". s('prefix'). "articles SET title='$title' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET text='$text' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET textlimit='$text_limit' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET category='$category' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET position='$position' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET displaytitle='$display_title' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET displayinfo='$display_info' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET commentable='$commentable' WHERE id='$id'");
      	mysql_query("UPDATE ". s('prefix'). "articles SET image='$image' WHERE id='$id'");
      	echo "<h2>". l('operation_completed') ."</h2><p><a href=\"index.php?id=" .$id. "\">". l('back') ."</a></p>";
      	mysql_close($db);
    }
  	
    else if ($_GET['task'] == "delete") { 
    	$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
    	mysql_select_db(s('dbname')) or die(s('dberror'));
    	mysql_query("DELETE FROM " .s('prefix'). "articles WHERE id='$id'");
    	echo "<h2>". l('deleted_success') ."</h2><p><a href=\"index.php\">". l('backhome') ."</a></p>";
    	mysql_close($db);
  	}
  	
  	else if ($_POST['task'] == "editcomment") {
		$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
    	mysql_select_db(s('dbname')) or die(s('dberror'));
    	mysql_query("UPDATE ". s('prefix'). "comments SET name='$name' WHERE articleid='$id'");
    	mysql_query("UPDATE ". s('prefix'). "comments SET comment='$comment' WHERE articleid='$id'");
    	echo "<h2>". l('operation_completed') ."</h2><p><a href=\"index.php?id=" .$id. "\">". l('back') ."</a></p>";
    	mysql_close($db);
	}
  	
	else if ($_GET['task'] == "freezecomments") {
		$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
      	mysql_select_db(s('dbname')) or die(s('dberror'));
      	mysql_query("UPDATE ". s('prefix'). "articles SET commentable='FREEZ' WHERE id='$id'");
      	echo "<h2>". l('operation_completed') ."</h2><p><a href=\"index.php?id=". $id ."\">". l('back') ."</a></p>";
      	mysql_close($db);
    }
    
    else if ($_GET['task'] == "unfreezecomments") {
		$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
    	mysql_select_db(s('dbname')) or die(s('dberror'));
    	mysql_query("UPDATE ". s('prefix'). "articles SET commentable='YES' WHERE id='$id'");
    	echo "<h2>". l('operation_completed') ."</h2><p><a href=\"index.php?id=". $id ."\">". l('back') ."</a></p>";
    	mysql_close($db);
    }
  	
    else if ($_GET['task'] == "deletecomment") { 
    	$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
    	mysql_select_db(s('dbname')) or die(s('dberror'));
    	$commentid = $_GET['commentid'];
    	$articleid = $_GET['articleid'];
    	
    	mysql_query("DELETE FROM " .s('prefix'). "comments WHERE id='$commentid'");
    	echo "<h2>". l('deleted_success') ."</h2><p><a href=\"index.php?id=" .$articleid. "\">". l('back') ."</a></p>";
    	mysql_close($db);
  	}
  	
  	else if ($_GET['task'] == "deletecategory") { 
		$db = mysql_connect(s('dbhost'),s('dbuname'),s('dbpass')); 
    	mysql_select_db(s('dbname')) or die(s('dberror'));
    	$categoryid = $_GET['categoryid'];
    	mysql_query("DELETE FROM " .s('prefix'). "categories WHERE id='$id'");
    	echo "<h2>". l('deleted_success') ."</h2><p><a href=\"index.php?action=categories\">". l('back') ."</a></p>";
    	mysql_close($db);
  	}
}


//********
// IMAGES 
//********
function images() { ?>
	<h2><? echo l('images'); ?></h2>
	<form name="imageformauthenticate" method="post" action="" enctype="multipart/form-data"></form>
	<form name="imageform" method="post" action="" enctype="multipart/form-data">
		<p><br /><? echo l('upload_image'); ?>:</p>
		<p><input type="file" name="imagefile" />
		<input type="submit" name="upload" value="<? echo l('upload'); ?>" /><br /></p>
	</form> <?
	if(isset( $_POST['upload'] )) {
		if ($_FILES['imagefile']['type']){ 
			$upload_dir = s('image_folder') ."/";	 
			copy ($_FILES['imagefile']['tmp_name'], $upload_dir .$_FILES['imagefile']['name']) or die ("Could not copy"); 
        	echo "<h2>" .l('operation_completed'). "</h2>";
			$kb_size = round(($_FILES['imagefile']['size'] / 1024), 1);
        	echo "<p><b>".$_FILES['imagefile']['name']. "</b>  [ " .$kb_size. " KB ] [ " .$_FILES['imagefile']['type']." ]";
    	} else {
            echo "<h2>" .l('admin_error'). "</h2>";
            echo "<p>" .l('image_error'). "</p>";
    	}
	} else {
		$upload_dir = s('image_folder') ."/";
    	$handle= opendir($upload_dir);
		$filelist = "";
		while ($file = readdir($handle)) {
   		if(!is_dir($file) && !is_link($file)) {
	    	$filelist .= "<a href='$upload_dir$file'>".$file."</a><br />";
    }}
	echo "<h2>". l('saved_images') .":</h2>";
	echo "<p>" .$filelist. "</p>";
}}
php?>