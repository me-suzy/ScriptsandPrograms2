<?php
/*-----------------------------------------------------------------------------+
|                            OG CMS v1.02                                      |
+------------------------------------------------------------------------------+
| This file is component of OG CMS :: web management system                    |
|                                                                              |
|                    Please, send any comments, suggestions and bug reports to |
|                                                      olegu@soemme.no         |
| Original Author: Vidar Løvbrekke Sømme                                       |
| Author email   : olegu@soemme.no                                             |
| Project website: http://www.soemme.no/                                       |
| Licence Type   : FREE                                                        |
+------------------------------------------------------------------------------+ */
//simple setup script
require_once 'connect.php';

function create_tables ($password) {

         //queries_first:
         //zones:
         $query_zones = "CREATE TABLE og_zones (
                      id smallint(6) NOT NULL auto_increment,
                      zone mediumtext NOT NULL,
                        PRIMARY KEY  (id)
                        ) TYPE=MyISAM;";
         //----------------------------------------

         //system
         $query_system = "CREATE TABLE `og_system` (
                       `id` int(11) NOT NULL auto_increment,
                       `password` text NOT NULL,
                       `title` varchar(255) NOT NULL default '',
                       `mail` varchar(255) NOT NULL default '',
                       `footer` varchar(255) NOT NULL default '',
                       `top_image` varchar(255) NOT NULL default '',
                       `admin_top_image` varchar(255) NOT NULL default '',
                       `small_image_width` mediumint(9) NOT NULL default '0',
                       `small_image_height` mediumint(9) NOT NULL default '0',
                       `image_width` mediumint(9) NOT NULL default '0',
                       `image_height` mediumint(9) NOT NULL default '0',
                       `post_count` mediumint(9) NOT NULL default '0',
                       `rh_enable` varchar(10) NOT NULL default '',
                       `top_five_dl` varchar(10) NOT NULL default '',
                       `last_five_comm` varchar(10) NOT NULL default '',
                       PRIMARY KEY  (`id`)
                     ) TYPE=MyISAM;";
         //----------------------------------------

         //static articles
         $query_static = "CREATE TABLE og_static (
                       id int(11) NOT NULL auto_increment,
                       name varchar(255) NOT NULL default '',
                       file_name varchar(255) NOT NULL default '',
                       PRIMARY KEY  (id)
                     ) TYPE=MyISAM;";
         //-----------------------------------------

         //post
         $query_post = "CREATE TABLE og_post (
                     id int(11) NOT NULL auto_increment,
                     title tinytext NOT NULL,
                     ingress mediumtext NOT NULL,
                     main_text longtext,
                     file_name mediumtext,
                     downloads int(11) NOT NULL default '0',
                     last_dl varchar(255) default NULL,
                     image_name mediumtext,
                     date int(11) default NULL,
                     zone int(11) NOT NULL default '0',
                     zone_old tinytext NOT NULL,
                     comment tinyint(4) NOT NULL default '0',
                     PRIMARY KEY  (id)
                   ) TYPE=MyISAM;";
         //-------------------------------------------

         //comments
         $query_comments = "CREATE TABLE og_comments (
                         cid int(11) NOT NULL auto_increment,
                         id int(11) NOT NULL default '0',
                         time varchar(255) NOT NULL default '',
                         name varchar(255) NOT NULL default '',
                         mail varchar(255) NOT NULL default '',
                         comment varchar(255) NOT NULL default '',
                         PRIMARY KEY  (cid)
                       ) TYPE=MyISAM;";
         //--------------------------------------------
         //end of table creation querys
         //------------------------------

         //------------------------------
         //execute table creation queries
         //-------------------------------
         mysql_query($query_zones) or die (mysql_error());
         print "zones table created successfully...<br>";
         mysql_query($query_system) or die (mysql_error());
         print "static table created successfully...<br>";
         mysql_query($query_static) or die (mysql_error());
         print "static articles table created successfully...<br>";
         mysql_query($query_post) or die (mysql_error());
         print "post table created successfully...<br>";
         mysql_query($query_comments) or die (mysql_error());
         print "comments table created successfull...<br>";
         //----------------------------------
         //end of table creation
         //----------------------------------
         //make values
         $md5password = md5($password);
         $time = strtotime("now");

         //queries to populate tables:
         $pop_zones = "INSERT INTO og_zones VALUES (1, 'welcome')";
         $pop_post = "INSERT INTO og_post(title, ingress, main_text, file_name,
                       image_name, date, zone, comment) VALUES ('Welcome to OG CMS v1.02', 'This is the first version
                       of og cms, somethings are still missing, but it is fully usable.', 'Main features:<br />
                       - Easy posting of new updates.<br />
                       - Easy to attach file for download<br />
                       - Easy to upload files and Images (not recommended for big files)<br />
                       - Easy to administrate posts, comments and settings.<br />
                       - Not quite so easy to style your website, but with a copy of Top Style light it can be done quire easily.<br />
                       - Customizeable Language.<br />
                       - Perfect for someone who want to publish their music / other files on the internet, easily.',
                       '', '', '$time', '1', '0')";
         $pop_system = "INSERT INTO og_system (password, title, mail, footer, top_image, admin_top_image,
                       small_image_width, small_image_height, image_width, image_height, post_count, rh_enable,
                       top_five_dl, last_five_comm)
                       VALUES ('$md5password', 'OG CMS V1.0', 'webmaster@yoursite.com', 'Copyright (c) 2004 yoursite.com',
                       'default.jpg', 'default.jpg', '75', '75', '150', '150' ,'10', 'on', 'on', 'on')";
         //----------------
         //end of population queries
         //-------------------

         //execute queries:
         mysql_query($pop_zones) or die (mysql_error());
         print "zones table populated successfully...<br>";
         mysql_query($pop_post) or die (mysql_error());
         print "post table populated successfully...<br>";
         mysql_query($pop_system) or die (mysql_error());
         print "system table populated successfully...<br>";
         //---------------------
         //end of table population
         //---------------------
}

function setup_form() {
         //print setup form
         ?>
         <form action="<? echo $PHP_SELF; ?>" name="setup" method="post">
         Welcome to the setup script for OG_CMS v1.02<br>
         This script will take care of creating and populating the tables
         needed for the script to run correctly.<br><br>
         To perform this setup process you are required to type in the
         administrator password you want to use.  This password can be
         <BR><BR>
         -----------------<BR>
         CAUTION          <BR>
         -----------------<BR>
         DO NOT RUN THIS SCRIPT IF YOU ARE UPDATING FROM A PREVIOUS VERSION,<BR>
         RUNNING THIS SCRIPT WILL CAUSE A MESS!!<BR>
         ===================================================================<BR>

         changed later.<br><br>
         Admin password: <input type="password" name="password" size="40" />
         <br><br>
         <input type="submit" name="start" value="Start Setup" />
         <input type="reset" name="reset" value="reset" />
         </form>
         <?php
}
         
//control structure:
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
<title>OG installer</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="generator" content="HAPedit 3.1">
<link rel="StyleSheet" href="inst_style.css" type="text/css">
</head>
<div class="heading">
OG installer V1.0
</div>
<br><br><br>
<?

?>
</body>

</html>
<?php
if ($_POST['start'] == "Start Setup") {
   create_tables ($_POST['password']);
   print "setup complete <a href=\"admin.php\">start using og cms</a>";
}
else {
     setup_form();
     }

