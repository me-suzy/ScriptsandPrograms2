<?php

$doorway = "files/" . $page . ".html";
$url = sprintf("%s%s%s","http://",$HTTP_HOST,$REQUEST_URI);
$dirname = dirname($url);
$doorway_dir = "pages";
$doorway_url = $dirname . "/" . $doorway_dir;

if ($makedoorway) {
		if (@!is_dir("pages")) { 
				mkdir("pages", 0777);
		}
		if (@!is_file("$doorway_dir/$file")) {
				$page = stripslashes($page);
				$fp = fopen("$doorway_dir/$file", "a+");
				fwrite ($fp, "$page");
				echo "Your new doorway page has been created! Below is the full url of this new page (this is the URL you will need to submit to search engines:<br>";
				echo "<b>$doorway_url/$file</b> <br><br><a href=\"$doorway_url/$file\" target=\"_new\">Click Here to view the page you just created</a> (if you added the javascript redirect, you will transferred to your Link URL before you are even able to see what the doorway page looks like)<br><br>";
				echo "<a href=\"javascript: var popUp=open('help.html#searchengines','help','width=400,height=200,scrollbars=yes,resizable=no');\">I've created my doorway page, now what?</a><br><br>";
				echo "<a href=\"index.php\">Click Here</a> to return to the main page.";
		} else {
				echo "The filename you chose already exists! Please use your browser's back button and enter a different filename for your doorway page.";
		}
} 
elseif ($title && $keywords && $desc && $body && $link && $home && $logourl && $coname && $file) {
		include("doorwaycode.inc");
} else {
		echo "You did not fill out all required fields! Please go back button and correct this problem.";
}

?>
