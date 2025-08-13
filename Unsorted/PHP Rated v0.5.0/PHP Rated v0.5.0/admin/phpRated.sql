
#
#	$Id: phpRated.sql,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
#

drop table if exists admin;
drop table if exists comments;
drop table if exists pms;
drop table if exists ratings;
drop table if exists sessions;
drop table if exists settings;
drop table if exists templates;
drop table if exists users;
drop table if exists image_types;

create table admin (
	id tinyint(4) not null auto_increment,
	username char(16) not null default '',
	password char(32) not null default '',
	primary key  (id)
);

insert into admin (
	id,
	username,
	password
) values (
	'1',
	'admin',
	'5f4dcc3b5aa765d61d8327deb882cf99'
);

create table comments (
	id int(7) unsigned not null auto_increment,
	user_id smallint(5) unsigned not null default '0',
	comment text not null,
	author_id smallint(5) not null default '0',
	author_ip varchar(15) not null default '',
	status varchar(16) not null default 'waiting',
	timestamp timestamp(14) not null,
	primary key  (id),
	key timestamp (timestamp),
	key user_id (user_id)
);

create table pms (
	id int(7) unsigned not null auto_increment,
	user_id smallint(5) unsigned not null default '0',
	subject varchar(50) not null default '',
	message text not null,
	author_id smallint(5) not null default '0',
	author_ip varchar(15) not null default '',
	pm_status varchar(16) not null default 'unread',
	timestamp timestamp(14) not null,
	primary key  (id),
	key timestamp (timestamp),
	key user_id (user_id)
);

create table ratings (
	id int(7) unsigned not null auto_increment,
	user_id smallint(5) unsigned not null default '0',
	rating tinyint(1) unsigned not null default '0',
	rater_id smallint(5) not null default '0',
	rater_ip char(15) not null default '',
	timestamp timestamp(14) not null,
	primary key  (id),
	key timestamp (timestamp),
	key user_id (user_id)
);

create table sessions (
	id varchar(32) not null default '',
	data text not null,
	expire int(11) unsigned not null default '0',
	primary key (id)
);

create table settings (
	orderby tinyint(3) unsigned not null default '0',
	name varchar(24) not null default '',
	setting varchar(255) not null default '',
	descr varchar(255) not null default '',
	primary key (name)
);

insert into settings (orderby, name, setting, descr) values (105,'max_image_size','102400','This is the maximum size an image file can be, this applies to locally stored images only.');
insert into settings (orderby, name, setting, descr) values (160,'np','3','The number of navigation links you want dispayed on each side of the current member page.  3 or 4 is probably best.');
insert into settings (orderby, name, setting, descr) values (80,'table_border_color','yellow','This affects the outer table border.  See include/tables.php for more info.');
insert into settings (orderby, name, setting, descr) values (65,'table_title_color','black','This affects the table title bar background color.  See include/tables.php for more info.');
insert into settings (orderby, name, setting, descr) values (75,'table_content_color','white','This affects the color of the table body.  See include/tables.php for more info.');
insert into settings (orderby, name, setting, descr) values (85,'page_bg_color','#333366','Overall page background color.');
insert into settings (orderby, name, setting, descr) values (50,'title_img','images/title.png','This is the relative path to the main title image that appears on the main page.');
insert into settings (orderby, name, setting, descr) values (55,'tb_header_img','images/bar.gif','This is the image used for the table header background.  See also \'table_title_color\'.');
insert into settings (orderby, name, setting, descr) values (15,'base_font','Tahoma, Verdana, sans-serif','General font face.');
insert into settings (orderby, name, setting, descr) values (20,'base_font_size','13','General font size in pixels, 11-15 is probably best.');
insert into settings (orderby, name, setting, descr) values (25,'base_font_color','black','General site font color.');
insert into settings (orderby, name, setting, descr) values (30,'base_link_color','black','General link color.');
insert into settings (orderby, name, setting, descr) values (35,'hover_link_color','yellow','Hover link color.');
insert into settings (orderby, name, setting, descr) values (40,'hover_link_bg_color','#333366','Hover link background color.');
insert into settings (orderby, name, setting, descr) values (70,'table_title_text_color','#333366','This is the font color for the table headers.');
insert into settings (orderby, name, setting, descr) values (45,'error_font_color','red','General error font color.');
insert into settings (orderby, name, setting, descr) values (10,'site_title','Rated Destiney','The name of your pRated based site.');
insert into settings (orderby, name, setting, descr) values (165,'speed_rate','1','If set to yes, this adds a 2 second <meta> refresh to the rating thank you page.  You probably want this.');
insert into settings (orderby, name, setting, descr) values (150,'girl_count','10','How many girls to list in the mini-list.');
insert into settings (orderby, name, setting, descr) values (155,'guy_count','10','How many guys to list in the mini-list.');
insert into settings (orderby, name, setting, descr) values (135,'show_site_stats','1','If set to yes, this causes the site stats to be shown. You probably want this.  Site stats can be further customized by editing the proper template.');
insert into settings (orderby, name, setting, descr) values (140,'girl_t','1','If set to yes, this causes the girl\'s mini-list to be displayed.');
insert into settings (orderby, name, setting, descr) values (145,'guy_t','1','If set to yes, this causes the guy\'s mini-list to be displayed.');
insert into settings (orderby, name, setting, descr) values (60,'table_file','tables.gray2.php','What table theme you want to use.');
insert into settings (orderby, name, setting, descr) values (90,'left_col_width','156','This is the width of the left column in pixels.');
insert into settings (orderby, name, setting, descr) values (95,'right_col_width','156','This is the width of the right column in pixels.');
insert into settings (orderby, name, setting, descr) values (100,'main_col_width','658','This is the width of the main column in pixels.');
insert into settings (orderby, name, setting, descr) values (120,'allow_local_image','1','This setting controls whether or not you allow users to store their images locally on your server.');
insert into settings (orderby, name, setting, descr) values (125,'allow_remote_image','1','This setting controls whether or not you allow users to store their images on their own remote server.');
insert into settings (orderby, name, setting, descr) values (170,'max_un_length','14','This setting is for chopping the username\'s length down so it doesn\'t overrun the table width in the mini toplists at the top-right.');
insert into settings (orderby, name, setting, descr) values (130,'comments_per_page','10','This setting is for setting a maximum number of comments per page on your view comments page.');
insert into settings (orderby, name, setting, descr) values (110,'max_image_width','640','This is the maximum width an image file can be, this applies to locally stored images only.');
insert into settings (orderby, name, setting, descr) values (115,'max_image_height','640','This is the maximum height an image file can be, this applies to locally stored images only.');

create table users (
	id smallint(5) unsigned not null auto_increment,
	username varchar(16) not null default '',
	password varchar(16) not null default '',
	hint varchar(100) not null default '',
	realname varchar(48) not null default '',
	description text not null,
	age tinyint(2) unsigned not null default '0',
	sex enum('m','f') not null default 'm',
	state varchar(32) not null default '',
	country varchar(32) not null default 'United_States.gif',
	email varchar(48) not null default '',
	url varchar(255) not null default '',
	quote varchar(255) not null default '',
	image enum('here','there') not null default 'there',
	image_url varchar(144) not null default '',
	image_ext varchar(4) NOT NULL default '',
	image_status enum('-1','0','1') not null default '-1',
	total_ratings smallint(5) unsigned not null default '0',
	total_points mediumint(9) unsigned not null default '0',
	average_rating decimal(6,4) not null default '0.0000',
	total_comments int unsigned not null,
	signup varchar(14) not null default '',
	timestamp timestamp(14) not null,
	primary key  (id),
	key sex (sex),
	key timestamp (timestamp),
	key signup (signup),
	key username (username),
	key email (email)
);

create table image_types (
	id tinyint(3) unsigned not null auto_increment,
	ext char(4) not null default '',
	primary key  (id)
);

insert into image_types (id, ext) values (1,'bmp');
insert into image_types (id, ext) values (2,'gif');
insert into image_types (id, ext) values (3,'jpg');
insert into image_types (id, ext) values (4,'jpeg');
insert into image_types (id, ext) values (5,'png');

create table templates (
	id tinyint(4) unsigned not null auto_increment,
	name varchar(16) not null default '',
	template text not null,
	primary key  (id),
	unique key name (name)
);

insert into templates (id, name, template) values (1,'main_text','<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\">\r\n<tr>\r\n<td width=\"100%\" class=\"regular\"><img src=\"\$base_url/\$title_img\" border=\"0\" alt=\"\$title\" title=\"\$title\" /></td>\r\n</tr>\r\n<tr>\r\n<td width=\"100%\" class=\"regular\">Welcome to <a href=\"\$base_url/index.php?\$sn=\$sid\">\$site_title</a>.  Here you can upload your picture and have others view it and rate it.  It\'s a great way to meet new people too.  Not only can you rate other member\'s pictures, you can also leave private messages and comments too!  Best of all, membership is totally Free!  <a href=\"\$base_url/index.php?\$sn=\$sid&show=signup\">Signup now</a> and join the fun!\r\n</td>\r\n</tr>\r\n<tr>\r\n<td width=\"100%\" class=\"regular\"><span class=\"bold\">How it works:</span>  You <a href=\"\$base_url/index.php?\$sn=\$sid&show=signup\">signup</a> and become a member.  Once signed up you can upload your image.  You may also link to your image if you already have it on another server.  Once your image is approved by us, it will begin to appear on the site.  Visitors will view your image and rate it depending on their own personal opinion.  Visitors may also leave public comments about you, and some may even decide to leave you a private message.</td>\r\n</tr>\r\n<tr>\r\n<td width=\"100%\" class=\"regular\"><span class=\"bold\">Rules:</span>  No prØn allowed!  For the clueless this means no \"naked pictures\" allowed.  All images are approved by us before being shown on the site.  If you upload porn we will delete it.  If you link to porn we will delete it.  This is an \"all ages\" site so keep it clean!  To keep the site as fast as possible, all images must be smaller than \$max_image_size bytes in filesize.<br /><br /><a href=\"\$base_url/index.php?\$sn=\$sid&show=signup\"><b>CLICK HERE TO SIGNUP NOW!</b></a><br><br></td>\r\n</tr>\r\n<tr>\r\n<td width=\"100%\" class=\"regular\"></td>\r\n</tr>\r\n<tr>\r\n<td width=\"100%\" class=\"smallregular\" align=\"right\">Designed for 1024x768 resolution, 24bit color or better</td>\r\n</tr>\r\n</table>');
insert into templates (id, name, template) values (2,'image_rules','<ul>\r\n<li class=\"regular\">No prØn allowed!  For the clueless this means no \"naked pictures\".  All images are approved by us before being shown on the site.  If you upload or link to porn we will delete it.</li>\r\n<li class=\"regular\">To keep the site as fast as possible, all images must be smaller than \$max_image_size bytes in filesize.</li>\r\n<li class=\"regular\">Both new image uploads and new image URLs cause image statistics to be reset.  Make sure you are ready to start over if you decide to update an already highly rated image.  We can not recover accidentally overwritten images.</li>\r\n</ul>');
insert into templates (id, name, template) values (3,'site_stats','<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n<tr bgcolor=\"#eeeeee\">\n	<td class=\"smallregular\" nowrap=\"nowrap\">Total Points:</td>\n	<td align=\"right\" class=\"smallregular\">\$pts_total_count</td>\n</tr>\n<tr>\n	<td class=\"smallregular\" nowrap=\"nowrap\">Points Today:</td>\n	<td align=\"right\" class=\"smallregular\">\$pts_pd_count</td>\n</tr>\n<tr>\n	<td class=\"smallregular\" nowrap=\"nowrap\">Points this Week:</td>\n	<td align=\"right\" class=\"smallregular\">\$pts_pw_count</td>\n</tr>\n<tr>\n	<td class=\"smallregular\" nowrap=\"nowrap\">Points this Month:</td>\n	<td align=\"right\" class=\"smallregular\">\$pts_pm_count</td>\n</tr>\n<tr bgcolor=\"#eeeeee\">\n	<td class=\"smallregular\" nowrap=\"nowrap\">Total Ratings:</td>\n	<td align=\"right\" class=\"smallregular\">\$ra_total_count</td>\n</tr>\n<tr>\n	<td class=\"smallregular\" nowrap=\"nowrap\">Ratings Today:</td>\n	<td align=\"right\" class=\"smallregular\">\$ra_pd_count</td>\n</tr>\n<tr>\n	<td class=\"smallregular\" nowrap=\"nowrap\">Ratings this Week:</td>\n	<td align=\"right\" class=\"smallregular\">\$ra_pw_count</td>\n</tr>\n<tr>\n	<td class=\"smallregular\" nowrap=\"nowrap\">Ratings this Month:</td>\n	<td align=\"right\" class=\"smallregular\">\$ra_pm_count</td>\n</tr>\n<tr bgcolor=\"#eeeeee\">\n	<td class=\"smallregular\" nowrap=\"nowrap\">Total Comments:</td>\n	<td align=\"right\" class=\"smallregular\">\$cm_total_count</td>\n</tr>\n<tr>\n	<td class=\"smallregular\" nowrap=\"nowrap\">Comments Today:</td>\n	<td align=\"right\" class=\"smallregular\">\$cm_pd_count</td>\n</tr>\n<tr>\n	<td class=\"smallregular\" nowrap=\"nowrap\">Comments this Week:</td>\n	<td align=\"right\" class=\"smallregular\">\$cm_pw_count</td>\n</tr>\n<tr>\n	<td class=\"smallregular\" nowrap=\"nowrap\">Comments this Month:</td>\n	<td align=\"right\" class=\"smallregular\">\$cm_pm_count</td>\n</tr>\n<tr bgcolor=\"#eeeeee\">\n	<td class=\"smallregular\" nowrap=\"nowrap\">Total Members:</td>\n	<td align=\"right\" class=\"smallregular\">\$su_total_count</td>\n</tr>\n<tr>\n	<td class=\"smallregular\" nowrap=\"nowrap\">Signups Today:</td>\n	<td align=\"right\" class=\"smallregular\">\$su_pd_count</td>\n</tr>\n<tr>\n	<td class=\"smallregular\" nowrap=\"nowrap\">Signups this Week:</td>\n	<td align=\"right\" class=\"smallregular\">\$su_pw_count</td>\n</tr>\n<tr>\n	<td class=\"smallregular\" nowrap=\"nowrap\">Signups this Month:</td>\n	<td align=\"right\" class=\"smallregular\">\$su_pm_count</td>\n</tr>\n</table>');
insert into templates (id, name, template) values (4,'visitors_online','<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n<tr>\n	<td class=\"regular\">&nbsp;Visitors Online:</td>\n	<td align=\"right\" class=\"regular\">\$uo_total&nbsp;</td>\n</tr>\n</table>');
insert into templates (id, name, template) values (5,'logged_in','<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\r\n<tr>\r\n	<td width=\"100%\" class=\"regular\" align=\"right\">Welcome \$username<br /><a href=\"\$base_url/logout.php?\$sn=\$sid\" target=\"_top\">Logout</a></td>\r\n</tr>\r\n</table>');
insert into templates (id, name, template) values (6,'logged_out','<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\r\n<tr><form method=\"post\" action=\"\$base_url/login.php?\$sn=\$sid\">\r\n	<td width=\"100%\" class=\"regular\" align=\"right\">username:<br /><input type=\"text\" name=\"UN\" size=\"12\" value=\"\" /><br />password:<br /><input type=\"password\" name=\"PW\" size=\"12\" value=\"\" /><br />\r\n	<table cellpadding=\"0\" cellspacing=\"1\" border=\"0\" width=\"100%\">\r\n	<tr>\r\n		<td valign=\"bottom\" nowrap=\"nowrap\"><a href=\"\$base_url/index.php?\$sn=\$sid&show=lost\" target=\"_top\" class=\"small\">Lost<br />Password</a></td>\r\n		<td align=\"right\" valign=\"bottom\"><input class=\"button\" type=\"submit\" name=\"login\" value=\"Go ->\" /></td>\r\n	</tr>\r\n	</table>\r\n	</td>\r\n</form></tr>\r\n</table>');
insert into templates (id, name, template) values (7,'about_member','<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n<tr>\n	<td class=\"regular\"><a name=\"profile\" class=\"bold\">Average Rating: </a>\$array[average_rating]</td>\n	<td class=\"regular\"><span class=\"bold\">Total Points: </span>\$array[total_points]</td>\n	<td class=\"regular\" align=\"right\"><span class=\"bold\">Times Rated: </span>\$array[total_ratings]</td>\n</tr>\n</table>\n<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n<tr>\n	<td class=\"regular\" width=\"50%\"><span class=\"bold\">Name: </span>\$array[user_name]</td>\n	<td class=\"regular\" width=\"50%\"><span class=\"bold\">Age: </span>\$array[age]</td>\n</tr>\n<tr>\n	<td class=\"regular\" width=\"100%\" colspan=\"2\"><span class=\"bold\">Description: </span>\$array[description]</td>\n</tr>\n<tr>\n	<td class=\"regular\" width=\"50%\"><span class=\"bold\">State/Province: </span>\$array[state]</td>\n	<td class=\"regular\" class=\"50%\"><span class=\"bold\">Country: </span><img align=\"top\" border=\"1\" src=\"\$base_url/images/flags/\$array[country]\" hspace=\"5\" alt=\"\$country title=\"\$country\" />\$country</td>\n</tr>\n<tr>\n	<td class=\"regular\" width=(%\"><span class=\"bold\">URL: </span><a href=\"\$array[url]\" target=\"_blank\">\$array[url]</a></td>\n	<td class=\"regular\" width=\"50%\"><span class=\"bold\">Quote: </span><i>&quot;\$array[quote]&quot;</i></td>\n</tr>\n</table>');
insert into templates (id, name, template) values (8,'img_src','<table cellpadding=\"1\" cellspacing=\"0\" border=\"0\" bgcolor=\"black\">\r\n<tr>\r\n	<td bgcolor=\"black\"><img src=\"\$image_src\"></td>\r\n</tr>\r\n</table>');
insert into templates (id, name, template) values (9,'comment','<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n<tr>\n	<td class=\"regular\" width=\"100%\" colspan=\"2\">On \$d, \$user_link wrote:</td>\n</tr>\n<tr>\n	<td width=\"1%\">&nbsp;</td>\n	<td colspan=\"2\" class=\"regular\" width=\"99%\">\$gc_array[comment]</td>\n</tr>\n</table>');
insert into templates (id, name, template) values (10,'copyright','Copyright © 2001 <a class=\"small\" href=\"\$base_url/index.php?\$sn=\$sid\" target=\"_top\">\$site_title</a> - All Rights Reserved');
insert into templates (id, name, template) values (11,'styles','<style type=\"text/css\">\nBODY {\n	font-family: \$base_font;\n	font-size: \$medium_font;\n	font-weight: normal;\n	color: \$base_font_color;\n}\na {\n	font-family: \$base_font;\n	font-size: \$medium_font;\n	font-weight: normal;\n	color: \$base_link_color;\n}\na:active {\n	font-family: \$base_font;\n	font-size: \$medium_font;\n	font-weight: normal;\n	color: \$base_link_color;\n}\na:visited {\n	font-family: \$base_font;\n	font-size: \$medium_font;\n	font-weight: normal;\n	color: \$base_link_color;\n}\na:hover {\n	font-family: \$base_font;\n	font-size: \$medium_font;\n	font-weight: normal;\n	color: \$hover_link_color;\n	background-color: \$hover_link_bg_color;\n}\na.small {\n	font-family: \$base_font;\n	font-size: \$small_font;\n	font-weight: normal;\n	color: \$base_font_color;\n}\na:active.small {\n	font-family: \$base_font;\n	font-size: \$small_font;\n	font-weight: normal;\n	color: \$base_font_color;\n}\na:visited.small {\n	font-family: \$base_font;\n	font-size: \$small_font;\n	font-weight: normal;\n	color: \$base_font_color;\n}\na:hover.small {\n	font-family: \$base_font;\n	font-size: \$small_font;\n	font-weight: normal;\n	color: \$hover_link_color;\n	background-color: \$hover_link_bg_color;\n}\na:il.hover {\n	background-color: transparent;\n}\n.title {\n	font-family: \$base_font;\n	font-size: \$large_font;\n	font-weight: bold;\n	color: \$table_title_text_color;\n}\n.regular {\n	font-family: \$base_font;\n	font-size: \$medium_font;\n	font-weight: normal;\n	color: \$base_font_color;\n}\n.selectedNav {\n	font-family: \$base_font_color;\n	font-size: \$medium_font;\n	font-weight: normal;\n	color: \$hover_link_color;\n	background: \$hover_link_bg_color;\n}\n.bold {\n	font-family: \$base_font;\n	font-size: \$medium_font;\n	font-weight: bold;\n	color: \$base_font_color;\n}\n.smallregular {\n	font-family: \$base_font;\n	font-size: \$small_font;\n	font-weight: normal;\n	color: \$base_font_color;\n}\n.error {\n	font-family: \$base_font;\n	font-size: \$medium_font;\n	font-weight: normal;\n	color: \$error_font_color;\n}\n.tb_header {\n	color: \$table_title_text_color;\n	background-color: \$table_title_color;\n	background: url(\"\$base_url/\$tb_header_img\");\n	background-repeat: repeat-x;\n}\ninput, textarea, select {\n	font-family: \$base_font;\n	font-size: \$medium_font;\n	font-weight: normal;\n	color: \$base_font_color;\n}\n\n.gray_top_middle {\nbackground: url(\"\$base_url/images/gray_top_middle.gif\") repeat-x;\n	height: 18px;\n}\n.gray_middle_fill {\n	background: url(\"\$base_url/images/gray_middle_fill.gif\") repeat-x;\n	height: 1px;\n}\n.gray_title_left {\n	background: url(\"\$base_url/images/gray_left_title.gif\") repeat-y;\n}\n.gray_title_right {\n	background: url(\"\$base_url/images/gray_right_title.gif\") repeat-y;\n}\n.gray_left_body {\n	background: url(\"\$base_url/images/gray_left_body.gif\") repeat-y;\n}\n.gray_right_body {\n	background: url(\"\$base_url/images/gray_right_body.gif\") repeat-y;\n}\n.gray_bottom_middle {\n	background: url(\"\$base_url/images/gray_bottom_middle.gif\") repeat-x;\n}\n</style>');
insert into templates (id, name, template) values (12,'doc_head','<!DOCTYPE html \r\nPUBLIC \"-//W3C//DTD XHTML 1.0\n	Transitional//EN\"\r\n\"DTD/xhtml1-transitional.dtd\">\n<html>\n<head>\n<title> .: \$site_title :.</title>\n<meta name=\"keywords\" content=\"prated,rate,amatuer,pictures,xxx,rated,mysql,porn,members,jpg,bmp,gif\" />\n<meta name=\"description\" content=\"pRated is a commercial PHP Script which allows you to enhance your site by adding an upload and rate me section.\" />\n<meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-1\" />');
insert into templates (id, name, template) values (13,'body_tag','<body bgcolor=\"\$page_bg_color\">\r\n<center><a href=\"http://destiney.com/prated/\" target=\"_blank\"><img src=\"http://destiney.com/2002/images/banner_prated.gif\" width=\"468\" height=\"60\" border=\"0\" alt=\"pRated\" title=\"pRated\" vspace=\"2\"></a></center>');
insert into templates (id, name, template) values (14,'lost_pass_form','<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\">\n<tr>\n	<td width=\"100%\" class=\"regular\" align=\"center\"><br />Enter your Username or Email Address<br />and we will send you your Password Hint.<br /><br /></td>\n</tr>\n<form method=\"post\" action=\"\$base_url/index.php?\$sn=\$sid&amp;show=lost&amp;search=1\">\n<tr>\n	<td width=\"100%\" class=\"regular\" align=\"center\">Username: <input type=\"text\" name=\"search_username\" size=\"16\" value=\"\" />&nbsp;<input class=\"button\" type=\"submit\" name=\"lost_username\" value=\"Search ->\" /><br /></form><form method=\"post\" action=\"\$base_url/index.php?\$sn=\$sid&amp;show=lost&amp;search=1\">Email Address: <input type=\"lost_email\" name=\"search_email\" size=\"24\" value=\"\" />&nbsp;<input class=\"button\" type=\"submit\" name=\"lost_email\" value=\"Search ->\" /><br /><br /></td>\n</tr>\n</form>\n</table>');
insert into templates (id, name, template) values (15,'comment_list','<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" width=\"100%\">\r\n<tr>\r\n	<td class=\"regular\" width=\"100%\" colspan=\"2\">On \$d, \$user_link left a comment for \$member_link:</td>\r\n</tr>\r\n<tr>\r\n	<td width=\"1%\"> </td>\r\n	<td colspan=\"2\" class=\"regular\" width=\"99%\">\$gc_array[comment]</td>\r\n</tr>\r\n</table>');
insert into templates (id, name, template) values (16,'direct_link','<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\r\n<tr><form>\r\n<td class=\"regular\">Link to this page: </td>\r\n<td class=\"regular\"><input type=\"text\" value=\"$base_url/?i=$array[id]\" size=\"42\" readonly onFocus=\"select()\" onSelect=\"select()\"></td>\r\n</form></tr>\r\n</table>');

#
#	$Id: phpRated.sql,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
#
