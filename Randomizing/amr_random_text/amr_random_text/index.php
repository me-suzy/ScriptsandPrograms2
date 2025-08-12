<?php
	
//------------------------------------------------------------------------------------------------------//
//																										//
//--->			Randomized Text Beta Version														<---//
//--->			Created By AMR Graphics Apr. 2005													<---//
//--->			All Rights Reserved By AMR Graphics® 1998 - 2005©									<---//
//--->			This is a freeware script and may be redistributed and/or edited as needed			<---//
//--->			Please remember to give credit where credit is due. Thanks for using AMR.			<---//
//																										//
//------------------------------------------------------------------------------------------------------//

//--->	Randomizer, this simple code will call up a random page of any text everytime the page is refreshed.
//--->	Please adjust all the variables below to your liking, do not edit below thos unless you know your stuff. ;)

$title = "Testimonials";					//--->	This is the head "TITLE" of your area, change as needed.
$fonties = "misc/fonts.css";				//--->	Direct server to you cascading stylesheet, name it whatever you like or not.
$tab_wid = "600";							//--->	Set the width of your tabl here. Leave blank if you want it to adjust on it's own.
$tab_heg = "100%";							//--->	Set the height of your table here. Leave blank if you want it to adjust on it's own.
$page_amount = 4;							//--->	Change "NUMBER" to amount of pages and remember to minus one from total.

//--->	Nice if you want to have a tip or quote always randomly changing.

$random_page = rand(0, $page_amount);		//--->	This is the randomizer code which works with PHP 4.3xx and above.

print "<html><head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<meta name=\"robots\" content=\"noindex, nofollow\">
<title>$title</title>
<link rel=\"stylesheet\" href=\"$fonties\" type=\"text/css\">
</head><body class=\"bod\"><div align=\"center\">";

print "<table class=\"tabs\" width=\"$tab_wid\" height=\"$tab_heg\"><tr class=\"tar\"><td class=\"tad\">";
print "<p class=\"hab\">$title</p>";

print "<p class=\"insert\">";
include("insert.inc");						//--->	 This code includes the page description for tips and/or quotes.
print "<br><br></p>";

print "<p class=\"pages\">";
include("$random_page.txt");				//--->	 This code includes the page of tips and/or quotes.
print "<br><br></p>";

print "</td></tr></table></div></body></html>";	
?>