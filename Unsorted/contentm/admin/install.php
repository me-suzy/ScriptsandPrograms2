<?php
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	
	/*////////////////////////////////////////////////////////////
	
	iWare Professional 4.0.0
	Copyright (C) 2002,2003 David N. Simmons 
	http://www.dsiware.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	A COPY OF THE GPL LICENSE FOR THIS PROGRAM CAN BE FOUND WITHIN THE
	docs/ DIRECTORY OF THE INSTALLATION PACKAGE.

	/////////////////////////////////////////////////////////////*/

	@import_request_variables('gpcs');
	include "gui.php";
	$GUI=new GUI ();
?>
<html>
<head>
<title> iWare Professional INSTALLER</title>
<script language=JavaScript>window.status='Powered By iWare Professional';</script>
<link rel="stylesheet" href="iware.css"></link>
</head>
<center>
<?php $GUI->PageBody (); ?>
<?php

	if(isset($install)&&$install==1)
	{

		// set admin/ directory to writable permission level
		@chmod(".",0777);

		// set files/ directory to safer permission level
		@chmod(".",0777);

		// Connect to Database
		$link = mysql_connect ($dbhost,$dbuser,$dbpwd)or die ("INSTALL ERROR ".mysql_error());
		mysql_select_db($dbname,$link)or die ("INSTALL ERROR ".mysql_error());
		
		// Get Existing Tables //
		$tb_names=Array();
		$result = mysql_list_tables ($dbname);
		$i = 0;
		while ($i < mysql_num_rows ($result)) 
			{
			$tb_names[$i] = mysql_tablename ($result, $i);
			$i++;
			}
		
		// Prepend table prefix if present
		$tb_useraccounts = $tb_prefix.$tb_useraccounts;
		$tb_usergroups = $tb_prefix.$tb_usergroups;
		$tb_header = $tb_prefix.$tb_header;
		$tb_docs = $tb_prefix.$tb_docs;
		$tb_footer = $tb_prefix.$tb_footer;
		$tb_config = $tb_prefix.$tb_config;

		// check for upgrade option
		if(!isset($upgrade)){$upgrade=0;}
		if($upgrade==0)
			{
			// Drop Tables If Needed //
			if(in_array("$tb_useraccounts",$tb_names))
			{@mysql_query("drop table ".$tb_useraccounts." ",$link)or die ("INSTALL ERROR ".mysql_error());}
			if(in_array("$tb_usergroups",$tb_names))			
			{@mysql_query("drop table ".$tb_usergroups." ",$link)or die ("INSTALL ERROR ".mysql_error());}
			if(in_array("$tb_header",$tb_names))
			{@mysql_query("drop table ".$tb_header." ",$link)or die ("INSTALL ERROR ".mysql_error());}
			if(in_array("$tb_docs",$tb_names))
			{@mysql_query("drop table ".$tb_docs." ",$link)or die ("INSTALL ERROR ".mysql_error());}
			if(in_array("$tb_footer",$tb_names))
			{@mysql_query("drop table ".$tb_footer." ",$link)or die ("INSTALL ERROR ".mysql_error());}
			if(in_array("$tb_config",$tb_names))
			{@mysql_query("drop table ".$tb_config." ",$link)or die ("INSTALL ERROR ".mysql_error());}
			
			// Create System Database Tables //
			mysql_query("create table $tb_useraccounts (id varchar (50), realname varchar (50), username varchar (50), password varchar (50), is_admin int (2), group_id varchar (50))",$link)or die ("INSTALL ERROR ".mysql_error());
			
			mysql_query("create table $tb_usergroups (id varchar (50), groupname varchar (50), allow_users int (2), allow_groups int (2), allow_header int (2), allow_footer int (2), allow_skin int (2), allow_docs int (2), allow_nav int (2), allow_order int (2), allow_files int (2), allow_mods int (2))",$link)or die ("INSTALL ERROR ".mysql_error());
			
			mysql_query("create table $tb_header (is_enabled int (2), display_mode int (2), image_name varchar (50), image_alt text, image_border int (2), title_text text, title_font varchar (50), title_color varchar (50), title_size int (2))",$link)or die ("INSTALL ERROR ".mysql_error());
			
			mysql_query("create table $tb_docs (id varchar (50), nav_order int (2), parent_id varchar (50), is_hidden int (2), link_text varchar (50), doc_content text,module varchar (50),meta_title text, meta_keywords text, meta_description text)",$link)or die ("INSTALL ERROR ".mysql_error());
			
			mysql_query("create table $tb_footer (is_enabled int (2), footer_text text)",$link)or die ("INSTALL ERROR ".mysql_error());
			
			mysql_query("create table $tb_config (license_key varchar (50), active_skin varchar (50), navbar_style varchar (50))",$link)or die ("INSTALL ERROR ".mysql_error());

			// Insert Base Data //
			mysql_query("insert into $tb_useraccounts (id,group_id,is_admin,realname,username,password) values ('1','1','1','System Administrator','admin','seNYhC60H6.5s')",$link)or die ("INSTALL ERROR ".mysql_error());
			
			mysql_query("insert into $tb_usergroups (id, groupname, allow_users, allow_groups,  allow_header, allow_footer, allow_skin, allow_docs, allow_nav, allow_order, allow_files, allow_mods) values ('1','Administrators','1','1','1','1','1','1','1','1','1','1')",$link)or die ("INSTALL ERROR ".mysql_error());
			
			mysql_query("insert into $tb_config (license_key,active_skin,navbar_style) values ('IW','Default','Default')",$link)or die ("INSTALL ERROR ".mysql_error());
			
			mysql_query("insert into $tb_docs (id,nav_order,parent_id,is_hidden,link_text) values ('1','1','0','0','home')",$link)or die ("INSTALL ERROR ".mysql_error());
			
			mysql_query("insert into $tb_header (is_enabled) values ('0')",$link)or die ("INSTALL ERROR ".mysql_error());

			mysql_query("insert into $tb_footer (is_enabled) values ('0')",$link)or die ("INSTALL ERROR ".mysql_error());
			}

		// Verify Directory Permissions //
		if(!is_writable("../files/")){die("INSTALL ERROR - files/ directory must have write permissions");}
		if(!is_writable(".")){die("INSTALL ERROR - admin/ directory must have write permissions");}

		// Build Configuration File //
		$basefile="<"."?php \n /* Automatically Generated by the iWare Installation Script */ \n define(\"IWARE_VERSION\",\"4.0.0\"); \n define(\"IWARE_LANG\",\"".$lang."\");  \n define(\"IWARE_HOSTNAME\",\"".$dbhost."\"); \n define(\"IWARE_USERNAME\",\"".$dbuser."\"); \n define(\"IWARE_PASSWORD\",\"".$dbpwd."\"); \n define(\"IWARE_DATABASE\",\"".$dbname."\"); \n define(\"IWARE_USERS\",\"".$tb_useraccounts."\"); \n define(\"IWARE_GROUPS\",\"".$tb_usergroups."\"); \n define(\"IWARE_DOCS\",\"".$tb_docs."\"); \n  define(\"IWARE_HEADER\",\"".$tb_header."\"); \n define(\"IWARE_FOOTER\",\"".$tb_footer."\"); \n define(\"IWARE_CONFIG\",\"".$tb_config."\"); \n ?".">";
		
		// Write Configuration File //
		$fp=@fopen("iware_config.php","w") or die("INSTALL ERROR - CANNOT CREATE CONFIGURATION FILE");
		@fwrite($fp,$basefile) or die("INSTALL ERROR - CANNOT WRITE TO CONFIGURATION FILE");
		@fclose($fp);

		// set admin/ directory to safer permission level
		@chmod(".",0755);

		// Output Summary
		?>
		<form method=post name=installForm action="index.php" >
		<table border=1 bordercolor=#000000 cellpadding=3 cellspacing=0 bgcolor=#f5f5f5 width=550>
		<tr>
		<td>
		<p>Installation is complete, you can now login to the admin control panel using 
		<?php
			if($upgrade==0){echo " the default username and password \"<b>admin</b>\" and \"<b>setup</b>\".";}
			else{echo " your existing username and password. ";}
		?>
		</p>
		<center><input type="submit" value="login"></center>
		</td>
		</tr>
		</table>
		</form>
		<?php

	}
else
	{
	?>
	<form method=post name=installForm action="install.php?install=1">
	<table border=1 bordercolor=#000000 cellpadding=3 cellspacing=0 bgcolor=#f5f5f5 width=550>
	<tr><td colspan=2 bgcolor=#c0c0c0><b>iWare Professional Installer</b></td></tr>
	<tr><td colspan=2 bgcolor=#e4e4e4><b>Language Settings</b></td></tr>
	<tr><td colspan=2 bgcolor=#f5f5f5>
	<p>
	Select the language used to display within the control panel interface. * Note that multi-language support has not yet been extended to the modules. All modules are currently in US_ENGLISH .
	</p>
	</td></tr>	
	<tr><td>Language</td><td>
	<select name="lang">
	<?php
		if ($handle = opendir('lang/')) 
		{
		while (false !== ($file = readdir($handle)))
			{
			if ($file != "." && $file != ".."){echo "<option value=\"$file\">$file</option>\n";}
			}
		closedir($handle); 
		}
	?>
	</select>
	</td></tr>	
	<tr><td colspan=2 bgcolor=#e4e4e4><b>Host Settings</b></td></tr>	
	<tr><td colspan=2 bgcolor=#f5f5f5>
	<p>
	Enter the needed database connection information below to allow the installation script to create tables in the specified database.
	</p>
	</td></tr>	
	<tr><td>Hostname or IP</td><td><input type="text" name="dbhost" value="localhost"></td></tr>
	<tr><td>Username</td><td><input type="text" name="dbuser" value=""></td></tr>
	<tr><td>Password</td><td><input type="password" name="dbpwd" value=""></td></tr>
	<tr><td>Database Name</td><td><input type="text" name="dbname" value="iware"></td></tr>
	<tr><td colspan=2 bgcolor=#e4e4e4><b>System Tables</b></td></tr>	
	<tr><td colspan=2 bgcolor=#f5f5f5>
	<p>
	The installation program will create / use the following tables in the specified database. If you need to change any of these table names do so now, otherwise simply use the defaults. 
	</td></tr>
	<tr><td>config temp</td><td><input type="text" name="tb_config" value="config"></td></tr>
	<tr><td>user accounts</td><td><input type="text" name="tb_useraccounts" value="users"></td></tr>
	<tr><td>user groups</td><td><input type="text" name="tb_usergroups" value="groups"></td></tr>
	<tr><td>header display</td><td><input type="text" name="tb_header" value="header"></td></tr>
	<tr><td>docs</td><td><input type="text" name="tb_docs" value="docs"></td></tr>
	<tr><td>footer display</td><td><input type="text" name="tb_footer" value="footer"></td></tr>
	<tr><td colspan=2 bgcolor=#e4e4e4><b>System Table Prefix</b></td></tr>	
	<tr><td colspan=2 bgcolor=#f5f5f5>
	<p>
	The installation program can prefix the above created database tables with some text to allow you to install multiple copies of this software in a single database. If you do not wish to prefix the tables then do not enter anything below.
	</p>
	</td></tr>
	<tr><td>Prefix</td><td><input type="text" name="tb_prefix" value=""></td></tr>
	<tr><td colspan=2 bgcolor=#e4e4e4><b>Upgrade Option</b></td></tr>	
	<tr><td colspan=2 bgcolor=#f5f5f5>
	<p>
	If you are performing an UPGRADE and wish to only re-write your configuration check the box below to avoid removing your existing tables and data. If you do not check the option below your tables and their data will be overwritten.
	</p>
	</td></tr>
	<tr><td colspan=2>
	<input type="checkbox" name="upgrade" value=1> UPGRADE ONLY ( DO NOT Overwrite My Database Tables )
	</td></tr>
	<tr><td colspan=2 align=center bgcolor=#c0c0c0><input type="submit" value="Install"></td></tr>	
	</table>
	</form>
	<?php
	}
?>
</center>
</body>
</html>