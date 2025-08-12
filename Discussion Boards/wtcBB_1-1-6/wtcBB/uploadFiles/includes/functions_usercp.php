<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################### //USERCP FUNCTIONS\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// cache avatars
function buildAvatars() {
	// get all avatars
	$avatars = query("SELECT * FROM avatars ORDER BY display_order,title");

	// if rows, proceed
	if(mysql_num_rows($avatars)) {
		// loop and form array
		while($avatar = mysql_fetch_array($avatars)) {
			$avatarinfo[$avatar['avatarid']] = $avatar;
		}
	}

	// return array
	return $avatarinfo;
}

// build it
$avatarinfo = buildAvatars();

?>