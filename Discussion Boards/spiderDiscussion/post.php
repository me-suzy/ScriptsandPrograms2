<?

////////////////////////////////////////////////////////////
//
// spiderDiscussion v1.3 - a simple threaded discussion board
//
////////////////////////////////////////////////////////////
//
// This script adds posts to a discussion board and creates
// post files from a template.
//
// See readme.txt for more information.
//
// Author: Jon Thomas <http://www.fromthedesk.com/code>
// Last Modified: 07/18/2003
//
// You may freely use, modify, and distribute this script.
//
////////////////////////////////////////////////////////////

// define the variables
$postIndex = "board.html";	// Web page that indexes all posts
$templateFile = "template.txt";	// text file that stores the template for posts
$numberFile = "noposts.txt";	// text file that stores the number of posts
$postingOrder = 0; 		// 0 for most to least recent; 1 for least to most recent

// DO NOT EDIT BELOW THIS POINT UNLESS YOU KNOW PHP! //

// if necessary info is not provided, print an error message
if ($name == "" || $subject == "" || $message == "") {
	echo "<html>\n";
	echo "<head>\n";
	echo "<title>Error</title>\n";
	echo "<link rel=\"stylesheet\" href=\"style.css\">\n";
	echo "</head>\n\n";
	echo "<body>\n";
	echo "<h2>Error</h2>\n\n";
	echo "<p>You must fill in in your name and the subject and message of your post.  Please go back and fill in the other information.</p>\n";
	echo "</body>\n";
	echo "</html>";
	exit;
}

// get rid of those "magic quotes"
$name = stripslashes($name);
$subject = stripslashes($subject);
$message = stripslashes($message);

// get the currrent post number
$fp_postno = fopen($numberFile, "r");
$number = fread($fp_postno, filesize($numberFile));
fclose($fp_postno);

// increment the number
$number++;

// save the new post number
$fp_postno = fopen($numberFile, "w");
fputs($fp_postno, $number);
fclose($fp_postno);

// get the date and time
$datetime = date('l, F j \a\t g:i A T');

// if an e-mail address is provided, include it in the "stamp"
if ($email != "") {
	$stamp = "<a href=\"mailto:$email\"><i>$name</i></a> - $datetime<br>\n";
}

// otherwise, just include the name and date and time
else {
	$stamp = "<i>$name</i> - $datetime\n";
}

// strip HTML and PHP tags from the subject
$subject = strip_tags($subject);
$message = strip_tags($message, "<a><b><i><u>");

// create the post entry for the index
if (isset($reply)) {
	if ($postingOrder) {
		$post = "<ul>\n";
		$post .= "<li><a href=\"messages/$number.html\"><b>$subject</b></a><br>\n";
		$post .= $stamp;
		$post .= "<!--POST REPLY TO $number-->\n";
		$post .= "</ul>";
		$post .= "<!--POST REPLY TO $reply-->\n";
	}
	else {
		$post = "<!--POST REPLY TO $reply-->\n";
		$post .= "<ul>\n";
		$post .= "<li><a href=\"messages/$number.html\"><b>$subject</b></a><br>\n";
		$post .= $stamp;
		$post .= "<!--POST REPLY TO $number-->\n";
		$post .= "</ul>";
	}
}
else {
	if ($postingOrder) {
		$post = "<li><a href=\"messages/$number.html\"><b>$subject</b></a><br>\n";
		$post .= $stamp;
		$post .= "<!--POST REPLY TO $number-->";
		$post .= "<!--POST NEW HERE-->\n";
	}
	else {
		$post = "<!--POST NEW HERE-->\n";
		$post .= "<li><a href=\"messages/$number.html\"><b>$subject</b></a><br>\n";
		$post .= $stamp;
		$post .= "<!--POST REPLY TO $number-->";
	}
}

// get the lines of the post index
$lines = file($postIndex);

// replace the appropriate post tag with the post entry
if (isset($reply)) {
	$lines = preg_replace("/<!--POST REPLY TO $reply-->/", $post, $lines);
}
else {
	$lines = preg_replace("/<!--POST NEW HERE-->/", $post, $lines);
}

// put all the lines back into a file
$file = join("", $lines);

// add the new post entry to the index
$fp_index = fopen($postIndex, "w");
fputs($fp_index, $file);
fclose($fp_index);

// get the post template
$template = file($templateFile);

// replace newline characters with the HTML break tag
$message = preg_replace("/\n/", "<br>", $message);

// replace the tags with the actual data
$template = preg_replace("/<!--SUBJECT-->/", $subject, $template);
$template = preg_replace("/<!--MESSAGE-->/", "$message<br>", $template);
$template = preg_replace("/<!--NAME AND DATE-->/", $stamp, $template);
$template = preg_replace("/<!--MESSAGE NUMBER-->/", $number, $template);

// put all the lines back into a file
$file = join("", $template);

// create the new post page
$fp_post = fopen("messages/$number.html", "w");
fputs($fp_post, $file);
fclose($fp_post);

// send the user back to the post index
header("Location: $postIndex");

?>