<?php

// file action for admin log
$fileAction = "Navigation";
$modArea = true;

// include files...
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title> Navigation </title>
<link rel="stylesheet" href="nav_style.css" type="text/css" />

<script type="text/javascript">
<!--

// cookie?
check = document.cookie.indexOf('wtcBB_prefs=');
stop = document.cookie.indexOf(";",(check + 12));

if(stop == -1) {
	stop = document.cookie.length;
}

cookiePrefs = document.cookie.substring((check + 12),stop);

if(check == -1) {
	cookiePrefs = false;
}

function doPreferences() {
	if(!cookiePrefs || cookiePrefs == 'false') {
		return;
	}

	cookieArray = cookiePrefs.split(", ");

	for(x = 0; x < cookieArray.length; x++) {
		wtcBB_expandCollapse(cookieArray[x]);
	}
}

function wtcBB_expandCollapse(objID) {
	if(!document.getElementById) {
		return;
	}

	divObj = document.getElementById(objID);
	imgObj = document.getElementById(objID + "_img");

	if(divObj.style.display == 'block') {
		divObj.style.display = 'none';
		imgObj.setAttribute('src','./../images/expand.gif');
		imgObj.setAttribute('alt','Expand');
	}

	else {
		divObj.style.display = 'block';
		imgObj.setAttribute('src','./../images/collapse.gif');
		imgObj.setAttribute('alt','Collapse');
	}
}

function wtcBB_expandAll() {
	if(!document.getElementById) {
		return;
	}

	objs = document.getElementsByTagName("div");

	for(x = 0; x < objs.length; x++) {
		imgObjName = objs[x].getAttribute('id');
		imgObj = document.getElementById(imgObjName + '_img');
		
		imgObj.setAttribute('src','./../images/collapse.gif');
		imgObj.setAttribute('alt','Collapse');
		objs[x].style.display = 'block';
	}
}

function wtcBB_collapseAll() {
	if(!document.getElementById) {
		return;
	}

	objs = document.getElementsByTagName("div");

	for(x = 0; x < objs.length; x++) {
		imgObjName = objs[x].getAttribute('id');
		imgObj = document.getElementById(imgObjName + '_img');
		
		imgObj.setAttribute('src','./../images/expand.gif');
		imgObj.setAttribute('alt','Expand');
		objs[x].style.display = 'none';
	}
}

function savePrefs() {
	if(!document.getElementById) {
		return;
	}

	objs = document.getElementsByTagName("div");
	prefs = false;

	for(x = 0; x < objs.length; x++) {
		if(objs[x].style.display == 'block') {
			if(prefs == false) {
				prefs = objs[x].getAttribute('id');
			} else {
				prefs = prefs + ", " + objs[x].getAttribute('id');
			}
		}
	}

	cookiePath = '<?php print($bboptions[cookie_path]); ?>';
	cookieDomain = '<?php print($bboptions[cookie_domain]); ?>';
	temp = new Date();
	expireDate = new Date(temp.getTime() + (60*60*24*365*1000));

	document.cookie = 'wtcBB_prefs=' + prefs + ';expires=' + expireDate + ';path=' + cookiePath + ';domain=' + cookieDomain;
}

-->
</script>

</head>
<body>

<h1><a href="http://www.webtrickscentral.com" target="_top"><img src="./../images/wtcBB_header_ADMIN.jpg" border="0" alt="WebTricksCentral Home" /></a></h1>

<?php
// get admin permissions
$adminPermissions = query("SELECT * FROM admin_permissions WHERE userid = '".$_COOKIE['wtcBB_adminUserid']."' LIMIT 1",1);

print("<p style=\"text-align: center; margin-right: 10px;\"><a href=\"javascript:wtcBB_expandAll();\" style=\"display: inline; margin: 0;\">Expand All</a> - <a href=\"javascript:wtcBB_collapseAll();\" style=\"display: inline; margin: 0;\">Collapse All</a><br /><a href=\"javascript:savePrefs();\">Save Preferences</a></p>");

if($_COOKIE['wtcBB_adminIsMod']) {
	construct_nav_link("moderator.php?do=index","Home","content");

	construct_nav_header("Announcements");
	construct_nav_link("moderator.php?do=edit_announcement","Announcement Manager","content");
	print("</div>\n");

	construct_nav_header("User Options");
	construct_nav_link("moderator.php?do=search","Search","content");
	construct_nav_link("moderator.php?do=ban","Ban User","content");
	construct_nav_link("moderator.php?do=view_banned","View Banned Users","content");
	print("</div>\n");

	construct_nav_header("Threads &amp; Posts");
	construct_nav_link("moderator.php?do=threads","Move/Prune Threads","content");
	construct_nav_link("moderator.php?do=posts","Prune Posts","content");
	print("</div>\n");
}

else {
	construct_nav_header("wtcBB Main");
	construct_nav_link("content.php","Admin Panel Home","content");

	if($adminPermissions['wtcBBoptions']) {
		construct_nav_link("options.php?do=options","wtcBB Options","content");
	}

	construct_nav_link("../index.php","Message Board","content");
	construct_nav_link("content.php?do=phpinfo","PHP Info","content");
	print("</div>\n");
}

if($adminPermissions['styles']) {
	construct_nav_header("Style System");
	construct_nav_link("style.php?do=manager","Style Manager","content");
	construct_nav_link("style.php?do=search_templates","Search in Templates","content");
	construct_nav_link("style.php?do=add_style","Add Style","content");
	construct_nav_link("style.php?do=add_template","Add Template","content");
	construct_nav_link("style.php?do=importExport","Import/Export Styles","content");
	construct_nav_link("style.php?do=manager_replace","Replacements Manager","content");
	construct_nav_link("style.php?do=add_replacement","Add Replacement","content");
	construct_nav_link("style.php?do=find_updated","Find Updated Templates","content");
	print("</div>\n");
}

if($adminPermissions['forums_moderators']) {
	construct_nav_header("Forums &amp; Moderators");
	construct_nav_link("forum.php?do=add","Add Forum","content");
	construct_nav_link("forum.php?do=edit","Forum Manager","content");
	construct_nav_link("forum.php?do=permission","Forum Permissions","content");
	construct_nav_link("forum.php?do=add_moderator","Add Moderator","content");
	construct_nav_link("forum.php?do=show_mods","Show All Moderators","content");
	print("</div>\n");
}


if($adminPermissions['users']) {
	construct_nav_header("Users");
	construct_nav_link("user.php?do=search","Search","content");
	construct_nav_link("user.php?do=add","Add User","content");
	construct_nav_link("user.php?do=search_ip","Search IP Address","content");
	construct_nav_link("user.php?do=prune","Prune/Move Users","content");
	construct_nav_link("user.php?do=email","Send E-mail","content");
	construct_nav_link("user.php?do=ban","Ban User","content");
	construct_nav_link("user.php?do=view_banned","View Banned Users","content");
	print("</div>\n");
}

if($adminPermissions['usergroups'] OR isSuperAdmin()) {
	construct_nav_header("Usergroups");

	if($adminPermissions['usergroups']) {
		construct_nav_link("usergroup.php?do=manager","Usergroup Manager","content");
		construct_nav_link("usergroup.php?do=add","Add Usergroup","content");
	}

	construct_nav_link("usergroup.php?do=admin_permissions","Admin Permissions","content");
	print("</div>\n");
}

if($adminPermissions['warn']) {
	construct_nav_header("Warning System");
	construct_nav_link("warn.php?do=edit","Warn Type Manager","content");
	construct_nav_link("warn.php?do=add","Add Warn Type","content");
	construct_nav_link("warn.php?do=view","View Warnings","content");
	print("</div>\n");
}

if($adminPermissions['logs_stats']) {
	construct_nav_header("Logs &amp; Maintenance");
	construct_nav_link("updateinfo.php","Update Information","content");
	construct_nav_link("log.php?do=admin","Administrator Log","content");
	construct_nav_link("log.php?do=mod","Moderator Log","content");
	print("</div>\n");
}

if($adminPermissions['announcements']) {
	construct_nav_header("Announcements");
	construct_nav_link("announcement.php?do=add","Add Announcement","content");
	construct_nav_link("announcement.php?do=edit","Announcement Manager","content");
	print("</div>\n");
}

if($adminPermissions['threads_posts']) {
	construct_nav_header("Threads &amp; Posts");
	construct_nav_link("massprune.php?do=threads","Move/Prune Threads","content");
	construct_nav_link("massprune.php?do=posts","Prune Posts","content");
	print("</div>\n");
}

if($adminPermissions['attachments']) {
	construct_nav_header("Attachments");
	construct_nav_link("attachments.php?do=ext","Attachment Extensions","content");
	construct_nav_link("attachments.php?do=add_ext","Add Extension","content");
	print("</div>\n");
}

if($adminPermissions['usertitles']) {
	construct_nav_header("Usertitles");
	construct_nav_link("usertitle.php?do=manager","Usertitle Manager","content");
	construct_nav_link("usertitle.php?do=add_usertitle","Add Usertitle","content");
	print("</div>\n");
}

if($adminPermissions['faq']) {
	construct_nav_header("FAQ");
	construct_nav_link("faq.php?do=manager","FAQ Manager","content");
	construct_nav_link("faq.php?do=add_faq_category","Add FAQ Category","content");
	construct_nav_link("faq.php?do=add_faq_item","Add FAQ Item","content");
	print("</div>\n");
}

if($adminPermissions['bbcode']) {
	construct_nav_header("Custom BB Codes");
	construct_nav_link("bbcode.php?do=manager","BB Code Manager","content");
	construct_nav_link("bbcode.php?do=add_bbcode","Add Custom BB Code","content");
	print("</div>\n");
}

if($adminPermissions['avatars']) {
	construct_nav_header("Avatars");
	construct_nav_link("avatar.php?do=manager","Avatar Manager","content");
	construct_nav_link("avatar.php?do=add_avatar","Add Avatar","content");
	construct_nav_link("avatar.php?do=upload_avatar","Upload Avatar","content");
	print("</div>\n");
}

if($adminPermissions['smilies']) {
	construct_nav_header("Smilies");
	construct_nav_link("smilies.php?do=manager","Smiley Manager","content");
	construct_nav_link("smilies.php?do=add_smiley","Add Smiley","content");
	construct_nav_link("smilies.php?do=upload_smilie","Upload Smiley","content");
	print("</div>\n");
}

if($adminPermissions['post_icons']) {
	construct_nav_header("Post Icons");
	construct_nav_link("post_icons.php?do=manager","Post Icon Manager","content");
	construct_nav_link("post_icons.php?do=add_post_icon","Add Post Icon","content");
	construct_nav_link("post_icons.php?do=upload_post_icon","Upload Post Icon","content");
	print("</div>\n");
}

?>

<script type="text/javascript">
<!--

// if javascript is disabled, the menus are never collapsed
wtcBB_collapseAll();
doPreferences();

-->
</script>

</body>
</html>