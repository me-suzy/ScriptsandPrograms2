<?php
$la = "a";
$z = "b";
include ("config.php");

if ($ipbancheck3 == "0") {if ($numv == "0"){
	if ($warn == $naum) {
	echo "You are banned from the Admin CP...now go away!";
} else {

echo '<link rel="stylesheet" type="text/css" href="ta3.css">';

if ($_GET['id'] == "") {
include ("a_header.inc");
echo "<a href='a_help.php?id=basic'><b>Basic Functions*</b></a><br><a href='a_help.php?id=list'><b>List*</b></a><br><a href='a_help.php?id=list2'><b>List 2*</b></a><br><a href='a_help.php?id=userlevels'><b>User Levels*</b></a><br><a href='a_help.php?id=fields'>Custom Fields</a><br><a href='a_help.php?id=images'>File/Album Manager</a><br><a href='a_help.php?id=ad'>Ad Manager</a><br><a href='a_help.php?id=perm'>Permissions</a><br><a href='a_help.php?id=newsletter'>Newsletter Manager</a><br><a href='a_help.php?id=templates'>Templates</a><br><a href='a_help.php?id=elitefields'>Elite Fields</a><br><br><a href='README.txt'><b>Readme File*</b></a><br><a href='BUGS.php'><b>Bugs List*</b></a><br><br><a href='GNU.txt'>GNU License</a><br><a href='GPL.txt'>GPL License</a><br><br><br>*=Very important reads";
include ("a_footer.inc");
} else {

if (!is_numeric($_GET['id'])) {
include ("a_header.inc");
    if ($_GET['id'] == "userlevels") {
	echo "In this help file I will explain the difference between each and every user level, 1-6<br><br>";
	echo "<b>Level 1:</b> The highest level, anyone granted this has access to <b>everything</b>, excluding permissions.<br><br>";
	echo "<b>Level 2:</b> Think of this as a Super Staff level. Aside from users, permissions and such, this level lets the person do everything.<br><br>";
	echo "<b>Level 3-5:</b> Level 3-5 are practically the same. It lets the user add, edit and delete there own content and that's it.<br><br>";
	echo "<b>Level 6:</b> Level 6 is the level for normal users and they have no access to the control panel.";
	}
    if ($_GET['id'] == "basic") {
	echo "In this help file I will explain some basic functions.<br><br>";
	echo '&lt;?php echo showtemplate("templatename"); ?&#62;<br><br>';
	echo "If you want to just simply show a template then use this function. Just replace templatename with the name of the template<br><br>";
	echo '&lt;?php echo copyright(); ?&#62;<br><br>';
	echo "This function just shows the copyright tag. Although it is not required, in order for OneCMS to get more people interested we need the word to get out and this is a great way.<br><br>";
	echo '&lt;?php echo ad("name", "type"); ?&#62;<br><br>';
	echo "To show an ad from the ad manager, use this tag. Replace name with the name of the group or ad and then replace type with either group (if you put in a group, it will rotate between ads in this group) or if you enter in image (if the ad is an image), coding (like google adsense) or flash (flash ad) it will just show this ad only.<br><br>";
	echo '&lt;?php echo af("limit"); ?&#62;<br><br>';
	echo "If you want to display affiliates from the AF Manager use this. Replace limit with the amount of affiliates you want to display or if you want to display all of them, leave it blank.<br><br>";
	echo '&lt;?php echo systemslist("templatename", "limit"); ?&#62;<br><br>';
	echo "If you want to just list systems use this function. Replace templatename with the name of the template you are using and limit with the amount of systems to list. Leave it blank if you want all systems to be listed.<br><br>";
	echo '&lt;?php echo online(); ?&#62;<br><br>';
	echo "This shows the amount of users online<br><br>";
	echo '&lt;?php echo modules(); ?&#62;<br><br>';
	echo "This lists the modules<br><br>";
	echo '&lt;?php echo usersonline(); ?&#62;<br><br>';
	echo "This function lists the users that are online<br><br>";
	echo '&lt;?php echo totalpms(); ?&#62;<br><br>';
	echo "This function shows the amount of PM's you have<br><br>";
	echo '&lt;?php echo newpms(); ?&#62;<br><br>';
	echo "Same as above except this only shows the amount of new PM's that you have<br><br>";
	echo '&lt;?php echo pms("limit"); ?&#62;<br><br>';
	echo "This function lists your pm's. (subject only, no message) Replace limit with the amount you want to show or if for some reason you want to list all pm's, don't put in anything<br><br>";
	echo '&lt;?php echo welcome(); ?&#62;<br><br>';
	echo "Just a simple function that says something like the below:<br><br>";
	echo welcome();
	}
	if ($_GET['id'] == "images") {
	echo "Unfortunately managing files/albums and basically just posting media and screen shots in CMS's of the past is a hassle. Knowing this I made a very easy to use, yet very effecient way of managing files and albums.<br><br>First of all, let me start with the file manager. When uploading files (after choosing how many) you have several things to fill in.<br><br><b>File</b> - If you want to you can upload a file.<br><b>...or link to a file</b> - As the name says, if you want to just link to an image you can do that.<br><b>File Type</b> - The purpose of this is so that for example, when adding a game instead of having a ton of images to go through it simply lists the boxart images...options are screen shot, smiley, boxart, image, file and movie.<br><b>Watermark Image?</b> - Pretty self explanatory, file has to be a jpeg however.<br><b>Album</b> - If you want this to be in an album, choose the album here.<br><b>Caption</b> - If this is a screen shot you can choose to enter in a caption to appear under the screen. (or however you have it in your template)<br><br>The purpose of albums is to organize screen shots. If you have 10 screen shots for say, Halo 2, then instead of manually posting screen shots in straight up HTML (admit it, most of you used to do that) you can simply assign an album to screen shots and then add media and choose the Halo 2 album to display (for example...assigning the album would then show all screen shots assigned to the album)";
	}

	if ($_GET['id'] == "ad") {
	echo "Before you even look for it let me just get it out of the way now...'groups' in this module has the same basic purpose as the album manager. Basically you can assign ads to a group which then rotate randomly. If you don't assign a group and display the ad in a template, it will only show that ad. Now that we have that out of the way, let's continue.<br><br>This is a very short help file because most of the fields when adding an ad are self-explanatory. Name, Group (just explained that), URL and Type (type of ad) are all easy comprehinsible so let me explain the others.<br><br><b>Dimensions</b> - If your adding an ad that's an image or flash ad then enter in the width/height here.<br><b>Coding</b> - Enter in the coding here. (if you selected 'coding'...use this for like burst, google, etc.)<br><b>If visitor is logged in, should they still see the ad?</b> - I think you know what this means. But just a note, people not logged in see the ad no matter what you choose for this.";
	}

	if ($_GET['id'] == "newsletter") {
	echo "Yet another simple help file, but I felt both this and the Ad manager help files were too long to be in the FAQ. Basically when going in to manage newsletters the most confusing this is going to be the difference between <u>categories</u> and <u>editions</u>. Think of categories as say 'Playboy Magazine' and an edition being 'May 2004' for example. Also another thing people are getting confused about is when you do add an edition it is <b>NOT sent out</b>. It is just created, but when you want it sent out go 'Newsletter Manager' and click the '[ Send out Newsletter(s) ]' link, click the editions you want sent out, click submit and the editions selected will be sent out to the subcribers.";
	}

	if ($_GET['id'] == "") {
	echo "";
	}

    if ($_GET['id'] == "list3") {
	echo "More than likely you came from the 'List' help tutorial and want to find out the id of a system. Below all systems, along with there ID, are listed.<br><br><table cellspacing='0' cellpadding='2' border='0' align='center'><tr><td><b>System Name</b></td><td><b>ID</b></td></tr>";

	$query="SELECT * FROM onecms_systems ORDER BY `id` DESC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

		echo "<tr><td>".$row[name]."</td><i>".$row[id]."</i></td></tr>";
	}
	echo "</table>";
	}

	if ($_GET['id'] == "list2") {
	echo "<title>Help :: List Part 2</title>";
    echo "Your probably surprised to see another page dedicated to explaining the list feature and you should be. Just a few hours ago I decided to expand even more on the list feature and I might even need <b>2 pages</b> to explain the frontend aspect, it's a complex thing to understand. Basically I made as many possiblities as possible...with the frontend you can set a limit, set a field to order by, ascending or descending, list publishers, list developers, list games, list games on a certain system and list content by game id and/or system id. That may sound like too much, but it's not. The nice thing is there are set defaults, meaning you could have a huge url setting 5 things...but you don't have to. So let's tackle the rest of this feature right here and right now.<br><br><b>List Publishers</b> - Here is the base URL - <a href='index.php?list=publisher'>index.php?list=publisher</a><br><br><b>List Developers</b> - Here is the base URL - <a href='index.php?list=developer'>index.php?list=developer</a><br><br><b>List Content</b> - Here is the base URL - <a href='index.php?list=content'>index.php?list=content</a><br><br><b>List Category Content</b> - Here is the base URL, example (replace <u>categoryname</u> with the name of the category) - <a href='index.php?list=categoryname'>index.php?list=categoryname</a><br><br><b>List Games</b> - Here is the base URL - <a href='index.php?list=games'>index.php?list=games</a><br><br><b>List Systems</b> - Here is the base URL - <a href='index.php?list=systems'>index.php?list=systems</a><br><br>Although you can addon the following things onto those URL's - <br><br><b>&t=</b> Here you can choose a template instead of the default, just put the name of the template after the equals sign.<br><b>&limit=</b> Here you can set the limit, say for example, the latest 10 things.<br><b>&by=</b> You can order the list by say, stats, date, etc.<br><b>&type=</b> Basically you can choose to either list this stuff from the highest numeral to lowest (DESC) or the opposite (ASC)<br><br>The above stuff can be used for list content, games, everything. But here is where we get down to the exclusive stuff.<br><br><b>List Games for content</b> - Here is the base URL, example - <a href='index.php?list=content&g=1'>index.php?list=content&g=1</a><br><b>g</b> holds the id of the game your filtering. Now to filter it for categories, say reviews, just replace 'content' with the name of the category name.<br><br>Now we move onto filtering Systems for content...a fancy word for 'showing content for a certain system'. Basically, it's the same as the above, except <b>s</b>. It would look something like <a href='index.php?list=content&s=1'>this</a>. You can also apply this to, say, reviews...just like above, replace 'content' with the category name.<br><br>See the system and game filtering? That can be done with the games category part as well, except you can't game filter a game. ;) Just do the above and replace 'content' with 'games'...it's as easy as that!";
	}
	if ($_GET['id'] == "elitefields") {
	echo "<title>Help :: Elite Fields</title>";
	echo "Have you had the chance to check out an 'Elite' profile? See those buttons such as this? <img src='a_images/elite_f.jpg' alt='Favorites' title='Favorites'> With OneCMS v2 you can use these in game pages AND also in reviews, previews and all content. Simply do the following:<br><br><b>{game-favorites}</b> - The tag that will display the icon for the favorites part of 'Elite'. When clicking this, it will add the game to your favorites.<br><br><b>{game-playing}</b> - The tag that will display the icon for the playing part of 'Elite'. When clicking this, it will add the game to your playing.<br><br><b>{game-collection}</b> - The tag that will display the icon for the collection part of 'Elite'. When clicking this, it will add the game to your collection.<br><br><b>{game-tracked}</b> - The tag that will display the icon for the tracked part of 'Elite'. When clicking this, it will add the game to your tracked.<br><br><b>{game-wishlist}</b> - The tag that will display the icon for the wishlist part of 'Elite'. When clicking this, it will add the game to your wishlist.<br><br><b>{game-systems}</b> - The tag that will display the icon for the systems part of 'Elite'. When clicking this, it will add the game to your systems.<br><br>As easy as cake!";
	}
	if ($_GET['id'] == "fields") {
    echo "<title>Help :: Custom Fields</title>";
	echo "I'll admit it, Custom Fields was the hardest thing to do in OneCMS Version 2.0. Not only for the frontend, but for the backend as well. The good thing about this is if you use custom fields, you can make Version 2.0 fit your site amazingly well. First, let's start out with the backend. When you add or edit a custom field, you need to enter at least the Name, Category and Type...Help Info being optional. But let's break it down.<br><br><b>Name</b> - This is an obvious one...this is what you want your custom field to be called.<br><br><b>Category</b> - What category do you want this field to be for? If you select 'Global', it will be able to be applied to all categories.<br><i>NOTE: If you choose the 'Global' type, it will NOT be applied to games or user profiles, only categories. (a.k.a. reviews, previews, etc.)</i><br><br><b>Type</b> - This is an easy one, do you want it to be a textfield or textbox (a.k.a. textarea)<br><br><b>Help Info</b> - Want to explain the field better, without staff having to PM asking you what the heck it means? Just write up a explanation for staff to view if they dont know what it means. (<b>NOTE:</b> Help info for a field an be accessed by clicking the <b>?</b> by the field when adding content (or adding a game, etc.))<br><br>Ok, that basically covers it for the backend aspect of custom fields. Pretty simple so far, right? Well, the frontend aspect is insanely simple. Say you create a field called 'Graphics', when editing a template for the category such as 'reviews', simply have somewhere in there {Graphics} and when viewing the content, it will show the data for that field.";
	}
	if ($_GET['id'] == "list") {
	echo "<title>Help :: List</title>";
	echo "Aah, the most useful, yet most complex feature in OneCMS Version 2.0, the list feature. Basically, when I coded 2.0, I thought of every useful feature and every feature that me and friends would even just 'want' in a CMS and jammed it together. The biggest thing was <b>easily</b> showing the latest reviews, previews, news, etc. or just linking to a list of it. Thankfully I've made the dream come true and that's what this help file is all about, showing you how to use the list feature, so here we go.<br><br><center><b>Step 1</b></center><br><br>The first step is to learn the different commands and there uses.<br><br><table cellspacing=\"5\" cellpadding=\"5\" border=\"0\" align=\"left\"><tr><td><textarea cols='45' rows='3'>";
	
	echo '&lt;?php echo content("category", "limit", "templatename"); ?&#62;';
	
	echo "</textarea></td><td>This command grabs content. The first item to edit is 'category'...replace that with the category name your grabbing content links to. The 2nd item is the limit, ie. 5. And the last thing is the template name. Yeah, I forgot to mention, you must create a template to use for this. Say you made a template like something below:<br><br>- {name} : {date}<br><br>That would output something like below (example) and lets also say you set the limit to 2 -<br><br>- Halo 2 announced : May 28th<br>- test test : June 5th<br><br>I think you get the picture. Then all you do after making the template is putting that above tag (with the above changes) into your header template, footer template, system template or whatever and wuvala.</td></tr><tr><td><textarea cols='45' rows='3'>";
	
	echo '&lt;?php echo systems("category", "systemid", "limit", "templatename"); ?&#62;';
	
	echo "</textarea></td><td>Ok, to make it easier on you let me just do an example to hopefully make you understand all the commands easier. Say I want to get the latest 5 reviews from the xbox category. Then I would do something like this - <br><br>";

	echo '&lt;?php echo systems("reviews", "2", "5", "templatename"); ?&#62;';

	echo "<br><br>The 2 you see is the id number for the xbox system, you can either find out the ID by <a href='a_help.php?id=list3' target='popup'><u><b>clicking here</b></u></a> or if your including this in the systems template, simply put {id}. And lastly is 'templatename', simply replace that with the name of a template that you want used for this. Then all you do after making the template is putting that above tag (with the above changes into your header template, footer template, system template or whatever and wuvala.</td></tr><tr><td><textarea cols='45' rows='3'>";
	
	echo '&lt;?php echo games("limit", "templatename"); ?&#62;';
	
	echo "</textarea></td><td>Aah, the easiest one! Thank goodness, eh? Anyways, this will be short and sweet if you've read the other 2 paragraphs. Replace 'limit' with, well, the limit you want it to be (ie. 5 latest games, the limit would be 5) and then replace 'templatename' with the name of the template.</td></tr><tr><td><textarea cols='45' rows='3'>";
	
	echo '&lt;?php echo posts("forumid", "limit", "template"); ?&#62;';
	
	echo "</textarea></td><td>Here comes part 1 of 2 of the forum list feature. Here we want to link to the latest posts. Simple replace 'forumid' with the ID of the forum you want filtered or leave it blank if you don't want to filter to a certain forum. And of course then set the limit and template.</td></tr><tr><td><textarea cols='45' rows='3'>";
	
	echo '&lt;?php echo topics("forumid", "limit", "template"); ?&#62;';
	
	echo "</textarea></td><td>Same directions as the above one, except this is for the latest topics.</td></tr></table>";
}

include ("a_footer.inc");
} else {

	$query="SELECT * FROM onecms_fields WHERE id = '".$_GET['id']."'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
        $des = "$row[des]";

		echo "<title>Help :: $row[name] field</title>";
		echo "<b><center><font size='1' face='verdana'>Help for field: </b><i>$row[name]</i><br><br>$des<br><br><a href=\"javascript:self.close()\">Close Window</a>";
	}
}
}

}
}
}
?>
