<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Pivot &raquo; New Entry</title>
</head>

<body>
<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------

include "pv_core.php";

if (count($_POST)>2) {

	// There's input, so we're going to post.
	$entry['user'] = $_POST['username'];
	$entry['pass'] = $_POST['password'];
	$entry['title'] = stripslashes($_POST['title']);
	$entry['introduction'] = stripslashes($_POST['introduction']);
	$entry['category'] = $_POST['f_catsing'];
	$entry['allow_comments']=1;

	// check if the user and pass are valid
	if ($Users[$entry['user']]['pass'] == md5($entry['pass']))  {

		if (strlen($entry['introduction'])>3) {

			// if so, save the new entry and generate files (if necesary)
			$db = new db();
			$entry['code']=">";
			$entry['date']= date('Y-m-d-H-i');

			//fix the category
			$entry['category'] = array ($entry['category']);

			$entry = $db->set_entry($entry);
			$db->save_entry();

			// remove it from cache, to make sure the latest one is used.
			$db->unread_entry($entry['code']);

			make_filename($Pivot_Vars['piv_code'], $Pivot_Vars['piv_weblog'], 'message', $message);

			// regenerate entry, frontpage and archive..
			generate_pages($Pivot_Vars['piv_code'], TRUE, TRUE, TRUE);

			echo "<b>Your entry has been posted!</b><br />";

			$msg_title = "[moblog] Succes!";

		} else {
			echo "<b>Not posted: For some reason</b><br />";
		}

	} else {
		echo "<b>Not posted: Wrong User and or Password</b><br />";
	}

}

?>
<form name="form1" method="post" action="minilog.php">
<p>Username:<br />
<input type="text" name="username" />
</p>
<p>Password:<br />
<input type="text" name="password" />
</p>
<p>Title:<br />
<input type="text" name="title" style="width:95%" />
</p>
<p>Entry:<br />
<textarea name="introduction" cols="60" rows="5" style="width:95%"></textarea>
</p>
<p>Category:<br />
<?php echo get_categories_select("single"); ?>
</p>
<p>
<input type="submit" name="Submit" value="Post entry!" />
</p>
</form>
</body>
</html>
