<?php
/******************************************************************************
* IPG: Instant Photo Gallery                                                  *
* =========================================================================== *
* Software Version:             IPG 1.0                                       *
* Copyright 2005 by:            Verosky Media - Edward Verosky                *
* Support, News, Updates at:    http://www.instantphotogallery.com            *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the GNU General Public License as published by the Free  * 
* Software Foundation; either version 2 of the License, or (at your option)   *
* any later version.                                                          *                                                                             *
* This program is distributed WITHOUT ANY WARRANTIES; without even any        *
* implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    *
*                                                                             *
* See www.gnu.org  for details of the GPL license.                            *
******************************************************************************/

require_once("../includes/config.php");
require_once("../includes/functions/fns_std.php");
require_once("../includes/functions/fns_db.php");

$DOC_TITLE = "Installation";

if($_POST['action'] == 'install'){ 
	if(strlen(trim($_POST['username'])) < 5 || strlen(trim($_POST['password'])) < 8) { $msg = "You must enter an administrator username of 5 or more characters, and password of 8 or more characters to continue."; }
	else {
	if(create_tables($_POST)) {
	$msg = "Tables successfully created!";
	$success = true;
	 } else {
	 $msg = "There was a problem with the installation.  Tables were not created.  Please check that your 
		configuration file has the correct database connection settings and try again.";
	 $success = false;
	 }
	 }//end if username/password
}
?>
<HTML>
<HEAD>
<title><?php print $DOC_TITLE ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link rel="stylesheet" href="../CSS/main.css" type="text/css">

</head>
<body bgcolor="#CCCCCC" text="#000000">
<p>&nbsp;</p>
<div align="center">
  <table width="75%" border="3" cellspacing="0" cellpadding="20">
    <tr> 
      <td bgcolor="#FFFFFF"> 
  <p><img src="logo.gif" width="273" height="75"></p>
        <h3>Installation</h3>
		<p><font color="red">
          <?php print $msg; ?>
          </font></p>
		
<?php

if(isset($success) && $success){
?>

<form name="form1" method="post" action="../admin/" enctype="multipart/form-data">
        <input type="hidden" name="action" value="install_successful">
        <input type="submit" name="submit" value="Login To Your Administration Panel">

      </form>

<?php }else { ?>

<p>This script will create your new database tables and your administrator 
          account.<br>
          <br>
          <b>WARNING</b>: Make sure you write down your administrator username 
          and password in case you forget it at some point. You will not be able 
          to manage your Instant Photo Gallery without your login.<br></p>

<form name="form1" method="post" action="<?php print $PHP_SELF ?>" enctype="multipart/form-data">
        
          <table width="72%" class="admin_form_box">
            <tr> 
      <td colspan="3" nowrap> 
        <p class="admin_form_header">Create Admin Login</p>
      </td>
    </tr>
    <tr valign="middle"> 
              <td align="left" class="admin_form_label" nowrap width="24%">Username: </td>
              <td align="left" width="76%"> 
                <input type="text" name="username" value="admin">
                <font face="Arial, Helvetica, sans-serif" size="-2">(Min. 5 Characters)</font></td>
    </tr>
    <tr valign="middle"> 
              <td align="left" class="admin_form_label" nowrap width="24%">Password: </td>
              <td align="left" width="76%"> 
                <input type="text" name="password" value="<?php print $_POST['password']; ?>">
                <font face="Arial, Helvetica, sans-serif" size="-2">(Min. 8 Characters)</font></td>
    </tr>
    <tr> 
              <td align="left" valign="middle" width="24%"> 
                <input type="hidden" name="action" value="install">
        <input type="submit" name="submit" value="Submit">
      </td>
	  <td>&nbsp;</td>
    </tr>
  </table>
        <br>
      </form>

<?php
}//end if success
?>
</td></tr></table></div></body></html>
<?php

	function create_tables($frm) {
		// Create the tables
		db_connect();
		db_query("
		DROP TABLE IF EXISTS " . PDB_PREFIX . "auth
		");
		
		db_query("
		CREATE TABLE " . PDB_PREFIX . "auth (
		  id int(11) NOT NULL auto_increment,
		  username varchar(32) NOT NULL default '',
		  password varchar(32) NOT NULL default '',
		  name varchar(64) NOT NULL default '',
		  cat_id int(11) NOT NULL default '0',
		  active tinyint(4) NOT NULL default '1',
		  PRIMARY KEY  (id),
		  UNIQUE KEY username (username,password)
		) TYPE=MyISAM AUTO_INCREMENT=1 
		");
		
		db_query("
		INSERT INTO " . PDB_PREFIX . "auth VALUES (1, '" . $frm['username'] . "', '" . md5($frm['password']) . "', 'Admin', 0, 1)
		");
		
		db_query("
		DROP TABLE IF EXISTS " . PDB_PREFIX . "categories
		");
		
		db_query("
		CREATE TABLE " . PDB_PREFIX . "categories (
		  id int(11) NOT NULL auto_increment,
		  cat_name varchar(32) NOT NULL default '',
		  private tinyint(4) NOT NULL default '0',
		  PRIMARY KEY  (id)
		) TYPE=MyISAM
		");
		
		db_query("
		DROP TABLE IF EXISTS " . PDB_PREFIX . "configuration
		");
		
		db_query("
		CREATE TABLE " . PDB_PREFIX . "configuration (
		  id int(11) NOT NULL auto_increment,
		  config_key varchar(96) NOT NULL default '',
		  config_value varchar(96) NOT NULL default '',
		  config_name varchar(32) NOT NULL default '',
		  config_description varchar(96) NOT NULL default '',
		  PRIMARY KEY  (id)
		) TYPE=MyISAM
		");
		
		db_query("
		INSERT INTO " . PDB_PREFIX . "configuration VALUES 
		(1, 'SITE_NAME', 'My Galleries', 'Site Name', ''),
		(2, 'HEADER_TEXT', '', 'Header Text', ''),
		(3, 'MAX_PORTFOLIO_THUMBNAIL_COLUMNS', '3', 'Max Portfolio Thumbnail Columns', ''),
		(4, 'MAX_CATEGORY_THUMBNAIL_COLUMNS', '3', 'Max Category Thumbnail Columns', ''),		
		(5, 'MAX_FILESIZE', '2097152', 'Max Upload Filesize', 'Bytes allowed for a single upload. Server limits may vary.'),
		(6, 'MAX_THUMBNAIL_WIDTH', '200', 'Max Thumbnail Width', ''),
		(7, 'MAX_THUMBNAIL_HEIGHT', '200', 'Max Thumbnail Height', ''),
		(8, 'MAX_IMAGE_WIDTH', '600', 'Max Image Width', ''),
		(9, 'MAX_IMAGE_HEIGHT', '1000', 'Max Image Height', ''),
		(10, 'PORTFOLIO_LABEL', 'Gallery', 'Portfolio Label', 'What you call your portfolio (Gallery, Showcase, etc.)'),
		(11, 'THUMBNAIL_INFO_POS', '2', 'Thumbnail Info Position', 'Hide=0; Right=1; Bottom=2'),
		(12, 'MENU_POS', '0', 'Menu Position', 'Top=0; Left=1; Right=2'),
		(13, 'SHOW_TIMES_VIEWED', '0', 'Show Times Viewed', ''),
		(14, 'IMAGE_RESAMPLE_QUALITY', '75', 'Image Resample Quality', 'Lowest to highest: 0 - 100'),
		(15, 'THUMBNAIL_RESAMPLE_QUALITY', '75', 'Thumbnail Resample Quality', 'Lowest to highest: 0 - 100')
		");
		
		db_query("
		DROP TABLE IF EXISTS " . PDB_PREFIX . "content
		");
		
		db_query("
		CREATE TABLE " . PDB_PREFIX . "content (
		  id int(11) NOT NULL auto_increment,
		  title varchar(64) NOT NULL default '',
		  body text NOT NULL,
		  menu_label varchar(64) NOT NULL default '',
		  PRIMARY KEY  (id)
		) TYPE=MyISAM
		");

		db_query("
		INSERT INTO " . PDB_PREFIX . "content VALUES 
		(1, 'Index', '', 'Home')");
		
		db_query("
		DROP TABLE IF EXISTS " . PDB_PREFIX . "image_comments
		");
		
		db_query("
		CREATE TABLE " . PDB_PREFIX . "image_comments (
		  id int(11) NOT NULL auto_increment,
		  image_id int(11) NOT NULL default '0',
		  comment text NOT NULL,
		  comment_uid int(11) NOT NULL default '0',
		  comment_date date NOT NULL default '0000-00-00',
		  PRIMARY KEY  (id)
		) TYPE=MyISAM
		");
		
		db_query("
		DROP TABLE IF EXISTS " . PDB_PREFIX . "image_ratings
		");
		
		db_query("
		CREATE TABLE " . PDB_PREFIX . "image_ratings (
		  id int(11) NOT NULL default '0',
		  times_clicked int(11) NOT NULL default '0',
		  rating_total int(11) NOT NULL default '0',
		  times_rated int(11) NOT NULL default '0',
		  avg_rating decimal(3,2) NOT NULL default '0.00',
		  last_rated date NOT NULL default '0000-00-00',
		  PRIMARY KEY  (id)
		) TYPE=MyISAM
		");
		
		db_query("
		DROP TABLE IF EXISTS " . PDB_PREFIX . "images
		");
		
		db_query("
		CREATE TABLE " . PDB_PREFIX . "images (
		  id int(11) NOT NULL auto_increment,
		  image varchar(32) NOT NULL default '',
		  title varchar(32) NOT NULL default '',
		  caption varchar(64) NOT NULL default '',
		  comment_requested tinyint(1) NOT NULL default '0',
		  copyright varchar(64) NOT NULL default '',
		  view_counter int(11) NOT NULL default '0',
		  filesize int(11) NOT NULL default '0',
		  public_view tinyint(1) NOT NULL default '1',
		  PRIMARY KEY  (id)
		) TYPE=MyISAM
		");
		
		db_query("
		DROP TABLE IF EXISTS " . PDB_PREFIX . "images_to_categories
		");
		
		db_query("
		CREATE TABLE " . PDB_PREFIX . "images_to_categories (
		  image_id int(11) NOT NULL default '0',
		  cat_id int(11) NOT NULL default '0',
		  display_order int(11) NOT NULL default '0',
		  PRIMARY KEY  (image_id,cat_id)
		) TYPE=MyISAM
		");
		
		db_query("
		DROP TABLE IF EXISTS " . PDB_PREFIX . "user_text
		");
		
		db_query("
		CREATE TABLE " . PDB_PREFIX . "user_text (
  id int(11) NOT NULL auto_increment,
  title varchar(64) NOT NULL default '',
  text_content text NOT NULL,
  content_cat int(11) NOT NULL default '0',
  display_area int(11) NOT NULL default '0',
  display_order int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM
		");
		
		return true;
	}//end create_tables






?>


