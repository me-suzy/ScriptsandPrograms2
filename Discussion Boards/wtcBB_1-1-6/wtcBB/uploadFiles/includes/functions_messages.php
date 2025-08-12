<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //FUNCTIONS - MESSAGES\\ ################ \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// this file should be included whenever you are
// using the postreply.php postthread.php and 

// this function builds an array containing all
// attachment extensions that are enabled AND in the db...
function buildAttachExt() {
	// get all extensions with proper exceptions
	$allExt = query("SELECT * FROM attachment_storage WHERE enabled = 1 ORDER BY ext");

	// yikes..
	if(!mysql_num_rows($allExt)) {
		return false;
	}

	// make array
	$attachext = Array();

	while($extinfo = mysql_fetch_array($allExt)) {
		// build array...
		$attachext[$extinfo['storageid']] = $extinfo;
	}

	// return array
	return $attachext;
}

// build extensions
$attachtext = buildAttachExt();

// this function will build the post icons
function buildPostIcons2() {
	// get all
	$allPostIcons = query("SELECT * FROM post_icons ORDER BY display_order, title");

	// intiate array
	$postIconInfo = Array();

	// loop
	if(mysql_num_rows($allPostIcons)) {
		while($posticon = mysql_fetch_array($allPostIcons)) {
			// form array
			$postIconInfo[$posticon['post_iconid']] = $posticon;
		}
	}

	// no post icons in db.. return false
	else {
		return false;
	}

	// return array created
	return $postIconInfo;
}

// build postIconInfo array
$postIconInfo = buildPostIcons2();

// constructs the post icons
function buildPostIcons($isThread = false) {
	global $postIconInfo, $_POST, $postinfo, $threadinfo;

	// loop through them all
	if(is_array($postIconInfo)) {
		foreach($postIconInfo as $id => $arr) {
			if(($isThread AND $threadinfo['post_icon_thread'] == '<img src="'.$arr['filepath'].'" alt="'.$arr['title'].'" />') OR ($_POST['postIcon'] == $id OR $postinfo['post_icon'] == '<img src="'.$arr['filepath'].'" alt="'.$arr['title'].'" />')) {
				$checked = " checked=\"checked\"";
			} else {
				$checked = "";
			}
	
			// construct the bit...
			eval("\$postIcons .= \"".getTemplate("message_posticons")."\";");
		}
	}
	
	else {
		$postIcons = '';
	}

	// return
	return $postIcons; // returns templates
}

// this function will get all the smilies...
// and build it for the messages
function buildClickableSmilies() {
	global $allSmilies, $bboptions;

	// loop through.. but first intiate counter
	$x = 1;

	while($smileyinfo = mysql_fetch_array($allSmilies)) {
		// get out if we're over total limit...
		if($x > $bboptions['clickable_smilies_total']) {
			break;
		}

		// get smiley template
		eval("\$smilies .= \"".getTemplate("smileybox_smiliesbit")."\";");

		// if x modded by per row is 0... use separator
		if(!($x % $bboptions['clickable_smilies_row']) OR $x == $bboptions['clickable_smilies_total']) {
			eval("\$smilies .= \"".getTemplate("smileybox_separator")."\";");
		}

		$x++;
	}

	// return
	return $smilies; // returns all smilies...
}

// this will build the colors...
function buildToolbarColors() {
	// make array... total: 25
	// yea you can add, take away, whatever you want from this array
	// key = color name (or HEX if it doesn't have one)
	// value = HEX (or even color name if you wanted..)
	// i found this place useful when constructing this array: http://www.w3schools.com/html/html_colornames.asp

	$colors = Array(
		"Red" => "#ff0000",
		"Blue" => "#0000ff",
		"Green" => "#008000",
		"Purple" => "#800080",
		"Pink" => "#ffc0cb",
		"Black" => "#000000",
		"White" => "#ffffff",
		"Yellow" => "#ffff00",
		"Brown" => "#a52a2a",
		"Cyan" => "#00ffff",
		"Magenta" => "#ff00ff",
		"Steel Blue" => "#4682b4",
		"Turquoise" => "#40e0d0",
		"Orange" => "#ffa500",
		"Orange Red" => "#ff4500",
		"Navy" => "#000080",
		"Lime Green" => "#32cd32",
		"Light Coral" => "#f08080",
		"Fire Brick" => "#b22222",
		"Gold" => "#ffd700",
		"Silver" => "#c0c0c0",
		"Orchid" => "#da70d6",
		"Indian Red" => "#cd5c5c",
		"Lime" => "#00ff00",
		"Indigo" => "#4b0082"
	);

	// sort it before looping through it...
	ksort($colors);

	// loop through and create the <option>'s
	foreach($colors as $colorName => $hex) {
		$allColors .= "<option value=\"".$hex."\" style=\"background: ".$hex."; color: #000000;\">".$colorName."</option>\n";
	}

	// return all the colors formed...
	return $allColors;
}

// this function will build the fonts...
// add what you want to the array.. doesn't matter
// there are 10 fonts.. you can take away or add to the list...
function buildToolbarFonts() {
	// the key is used to display it
	// the value is used to format it in the <select>
	$fonts = Array(
		"Verdana" => "verdana",
		"Arial" => "arial",
		"Tahoma" => "tahoma",
		"Century" => "century",
		"Comic Sans MS" => "comic sans ms",
		"Jester" => "jester",
		"Trebuchet MS" => "trebuchet ms",
		"Times New Roman" => "times new roman",
		"Lucida Sans" => "lucida sans",
		"Teletype" => "teletype"
	);

	// sort
	ksort($fonts);

	// loop through and create the <option>'s
	foreach($fonts as $fontName => $fontFormat) {
		$allFonts .= "<option value=\"".$fontFormat."\" style=\"font-family: ".$fontFormat.";\">".$fontName."</option>\n";
	}

	// returns all the fonts formed...
	return $allFonts;
}

// this function will cache attachments
// for specified thread... or for PM
function buildAttachments($threadid,$pmHash = "") {
	// pm?
	if($pmHash) {
		$getAttachments = query("SELECT * FROM attachments WHERE attachmenthash = '".$pmHash."' ORDER BY attachmentname");
	} else {
		$getAttachments = query("SELECT * FROM attachments WHERE attachmentthread = '".$threadid."' ORDER BY attachmentname");
	}

	// uh oh...
	if(!mysql_num_rows($getAttachments)) {
		return false;
	}

	if($pmHash) {
		while($PM_attachinfo2 = mysql_fetch_array($getAttachments)) {
			$attachinfo[$PM_attachinfo2['attachmentid']] = $PM_attachinfo2;
		}
	}
	
	else {
		while($attachinfo2 = mysql_fetch_array($getAttachments)) {
			$attachinfo[$attachinfo2['attachmentpost']][$attachinfo2['attachmentid']] = $attachinfo2;
		}
	}

	// return array
	return $attachinfo;
}

?>