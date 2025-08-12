<?php
include ("config.php");

if ($ipbancheck3 == "0") {if ($numv == "0"){
	if ($warn == $naum) {
	echo "You are banned from the Admin CP...now go away!";
} else {

if ($_GET['view'] == "new") {
echo "<b>Gallery Module</b><br>This module replaces the need for the albums manager or even the media category. Simply add a gallery, click on the name of the gallery (when viewing the list), either upload or mass upload a folder and presto, your done. Just link to gallery.php or use the php tag &lt;?php echo gallery('limit', 'templatename'); &#62; to list the latest galleries.<br><br>Also if you link to have content/media linked together you can use the {gallery}text here{endgallery} code or the {gallery;text here;gallery} to link to a gallery related to the content. Just place that in the template. (ie. gallery related to a review, put code in reviews template)<br><br><b>Extensive mod_rewrite</b><br>An important factor in your site growing is search engine stats and easily the best way to achieve higher stats is mod_rewrite. In version 2.2 of OneCMS there is mod_rewrite for content, companies and games but new mod_rewrite includes the forums (forum, topics and posts), the game list, forum home, gallery and pages (includes the page name into it). The mod_rewrite can now be turned on and off with just editing the setting via <a href='a_settings.php?type=general'><u>this page</u></a>.<br><br><b>User Reviews</b><br>One of the most wanted modules was user reviews and here it is. Although somewhat limited the user reviews is a nifty feature. Users can submit user reviews, they can enter the name, game, system, overall rating and of course the review. If your are staff/admin level then you can easily delete user reviews and also visitors can rate the game 1-5. Lastly you can customize the look of a user review by editing the template via <a href='a_templates.php'><u>this page</u></a>.<br><br><br>These are only the most important new features, for a full list of features and bug fixes check <a href='WHATSNEW'><u>this page</u></a>";
}

if ($_GET['view'] == "") {
echo "<iframe src='http://www.insanevisions.com/onecms-updates.php?version=".$version."&siteurl=".$_SERVER['HTTP_HOST']."&sitename=".$sitename."' width='100%' height='100%' frameborder='0'></iframe>";
}

}
}
}
include ("a_footer.inc");
?>