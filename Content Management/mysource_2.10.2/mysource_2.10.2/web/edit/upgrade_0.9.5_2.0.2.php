<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/upgrade_0.9.5_2.0.2.php,v $
## $Revision: 2.5 $
## $Author: csmith $
## $Date: 2003/12/01 02:39:22 $
#######################################################################
# Initialise
include_once("../init.php");
#---------------------------------------------------------------------#

 ####################################################################
# tell anyone who isn't a superuser to politely go away
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Upgrade to 2.0.2", "You must be logged in.");
} else if (!user_root()) {
	$SESSION->login_screen("Upgrade to 2.0.2", "You must be a <b>root</b> to upgrade the system.",$SESSION->user->login);
}

?>
Upgrading MySource 0.9.5 BETA to 2.0.2 BETA...<br><br>
<?

if (!$system_backed_up) {
?>
<div align="center">
<form onSubmit="javascript: return confirm('Since this upgrade script is upgrading from one BETA release to another, we cannot guarantee it wil function perfectly. It has been fairly well tested, however. Good luck.');">
<b>Are you sure that you have backed up your system ?</b><br>
<input type="hidden" name="time" value="<?=microtime()?>">
<input type="submit" name="system_backed_up" value="&nbsp;&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;">
<input type="button" value="&nbsp;&nbsp;&nbsp;No&nbsp;&nbsp;&nbsp;" onClick="javascript: alert('Well get on with it :)');">
</form>
</div>
<?
exit;
}

error_reporting(5);
$web = &get_web_system();
$webdb = &$web->get_db();
$users = &get_users_system();
$usersdb = &$users->get_db();

 ##############################################################
# clear the cache
function clear_mysource_cache() {
	global $CACHE_PATH;
	$cache_ptr = @opendir($CACHE_PATH) or die("Unable to open cache dir - '$CACHE_PATH'");
	while (false !== ($cache_file = readdir($cache_ptr))) { 
		if ($cache_file == "." || $cache_file == "..") continue;
		# only delete files with an extension
		if (strstr($cache_file, ".")) { 
			unlink("$CACHE_PATH/$cache_file") or die("Unable to remove '$CACHE_PATH/$cache_file'");
		} #end if

	}#end while
	closedir($cache_ptr); 
}

clear_mysource_cache();

 #########################################################
# First we'll just upgrade the templates..
$sql = array();
$sql[] = "ALTER TABLE xtra_page_template_form ADD column log_form_submission TINYINT DEFAULT 0;";

$sql[] = "CREATE TABLE xtra_page_template_form_log (
	logid            MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	pageid           MEDIUMINT(9) UNSIGNED,
	submission_time  DATETIME,
	answers          LONGTEXT,
	userid           MEDIUMINT(8) UNSIGNED,
	sessionid        CHAR(32)
);";

$sql[] = "ALTER TABLE xtra_page_template_redirect ADD column window_options TEXT;";

$sql[] = "CREATE TABLE xtra_page_template_pullcontent (
	pageid		MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY,
	content_pageid	MEDIUMINT(9) UNSIGNED
);";

$sql[] = "CREATE TABLE xtra_site_extension_frames (
  siteid         MEDIUMINT(9) UNSIGNED NOT NULL,
  frameset_text  TEXT,      # the html that forms the frameset
  index_pageid   MEDIUMINT(9) UNSIGNED NOT NULL, # the page to use as the index page, 
                                                 # because the index page is going to be taken by this template
  index_frameid  MEDIUMINT(9) UNSIGNED NOT NULL, # the frame that will contain the main content for the page
  PRIMARY KEY(siteid)
);";

$sql[] = "CREATE TABLE xtra_site_extension_frames_frame (
  siteid    MEDIUMINT(7) UNSIGNED NOT NULL,
  frameid   SMALLINT     NOT NULL DEFAULT 0,
  name      VARCHAR(255) NOT NULL,
  designid  MEDIUMINT    UNSIGNED NOT NULL,
  PRIMARY KEY (siteid, frameid),
  UNIQUE (siteid, name)
);";

foreach($sql as $run) $webdb->select($run);

# User database
$sql = array('ALTER TABLE affiliation ADD column answers LONGTEXT','ALTER TABLE organisation ADD column form LONGTEXT');

foreach($sql as $run) $usersdb->select($run);

 ###########################################
# OK Let's upgrade the site designs
$sql =  "
		CREATE TABLE site_design_customisation (
		  customisationid  varchar(255) NOT NULL,
		  designid         MEDIUMINT(7) UNSIGNED NOT NULL,
		  design           LONGTEXT     NOT NULL,
		  PRIMARY KEY   (customisationid)
		)
	";
$webdb->select($sql);

$sql = "SELECT siteid, designid, design
		FROM site";
$designs = $webdb->associative_array($sql);
foreach($designs as $data) {
	$sql = "INSERT INTO site_design_customisation 
			(customisationid, designid, design)
			VALUES
			('".$data['siteid'].".site', '".$data['designid']."', '".addslashes($data['design'])."')";
	$webdb->insert($sql) or die("Unable to update the site designs");
}#end foreach
# OK, we can now drop the design column from the site table
$sql =  "ALTER TABLE site DROP COLUMN design";
$webdb->select($sql);

clear_mysource_cache();

 ##############################################################
# Okay...bo taking over now after Blair's upgrade stuff
# This is going to update the way page_statuses work

$page_statuses = $webdb->associative_array("SELECT pageid,status FROM page");
$sql="";
$sql[] = "ALTER TABLE site ADD COLUMN not_found_pageid MEDIUMINT UNSIGNED NOT NULL;";
$sql[] = "ALTER TABLE page ADD COLUMN visible TINYINT(3) UNSIGNED NOT NULL;";

$sql[] = "ALTER TABLE page DROP COLUMN status;";
$sql[] = "ALTER TABLE page ADD COLUMN next_status_change datetime NOT NULL;";
$sql[] = "ALTER TABLE page ADD KEY (next_status_change);";

$sql[] = "CREATE TABLE page_status (
		pageid         MEDIUMINT     UNSIGNED NOT NULL,
		date           DATETIME      NOT NULL,
		status         CHAR(1)       NOT NULL,
		userid         MEDIUMINT(8)  UNSIGNED NOT NULL,
		log            VARCHAR(255)  NOT NULL,
		PRIMARY KEY(pageid,date),
		KEY(userid),
		KEY(status)
	);";
foreach($sql as $run) $webdb->select($run);

# Lets add all the page statuses to the new table
foreach($page_statuses as $pageid => $status) {
	$webdb->insert("INSERT INTO page_status (pageid,status,date) VALUES ('$pageid','$status',now())");
}

# Set all page visibilities to on
$webdb->update("UPDATE page SET visible=1");

clear_mysource_cache();

 #############################################################
# create the new data directories

$RESTRICTED_PATH   = "$DATA_PATH/restricted";
$UNRESTRICTED_PATH = "$DATA_PATH/unrestricted";

create_directory($RESTRICTED_PATH)   or die("Unable to create new restricted dir");
create_directory($UNRESTRICTED_PATH) or die("Unable to create new unrestricted dir");

$dirs = Array("web", "site", "site/design", "page", "users", "user");
foreach($dirs as $dir) {
	create_directory("$RESTRICTED_PATH/$dir")   or die("Unable to create '$RESTRICTED_PATH/$dir'");
	create_directory("$UNRESTRICTED_PATH/$dir") or die("Unable to create '$UNRESTRICTED_PATH/$dir'");
}

 #############################
# move the site dirs

$siteids = $webdb->single_column("SELECT siteid FROM site");

foreach($siteids as $siteid) {
	$site = &$web->get_site($siteid);
	$site_dir = "$DATA_PATH/web/site/s$siteid";
	# if this is a dir and of the right format
    if (is_dir($site_dir)) { 

		$page_index = &$site->get_page_index();

		 #########################
		# move the pages
		foreach($page_index as $pageid => $data) {
			if (!$pageid) continue; 
			$page_dir = "$site_dir/p$pageid";
			if (!is_dir($page_dir)) continue;
			create_directory("$page_dir/thumbs") or die("Unable to create dir - '$page_dir/thumbs'");
			create_directory("$page_dir/template") or die("Unable to create dir - '$page_dir/template'");
			if (is_dir("$page_dir/images")) {
				$page_ptr = @opendir("$page_dir/images") or die("Unable to open dir - '$page_dir/images'");
				while (false !== ($file = readdir($page_ptr))) { 
					if ($file == "." || $file == "..") continue;
					# if this is the page thumb or a file thumb nail 
					if (ereg('^thumb', $file) || ereg('^file_thumb', $file)) { 
						rename("$page_dir/images/$file", "$page_dir/thumbs/$file") or die("Unable to move '$page_dir/images/$file' to '$page_dir/thumbs/$file'");
					# oh well, assume it's something to do with the template
					} else {
						rename("$page_dir/images/$file", "$page_dir/template/$file") or die("Unable to move '$page_dir/images/$file' to '$page_dir/template/$file'");
					} #end if

				}#end while
				closedir($page_ptr);

				rmdir("$page_dir/images") or pre_echo("WARNING : Unable to delete dir - '$page_dir/images'");

			}#end if
			
			$path = ($data[effective_public] && in_array($data[effective_status],array('L','E','R'))) ? $UNRESTRICTED_PATH : $RESTRICTED_PATH;
			rename($page_dir, "$path/page/$pageid") or die("Unable to move '$page_dir' to '$path/page/$pageid'");

		}#end foreach


		 ################################################################
		# move all the stuff in the old images directory back a level
		if (is_dir("$site_dir/images")) {
			$images_ptr = @opendir("$site_dir/images") or die("Unable to open old dir - '$site_dir/images'");
			while (false !== ($images_file = readdir($images_ptr))) { 
				if ($images_file != "." && $images_file != "..") {
					rename("$site_dir/images/$images_file", "$site_dir/$images_file") or die("Unable to move '$site_dir/images/$images_file' to '$site_dir/$images_file'");
				}#end if
			}#end while
			closedir($images_ptr);
			rmdir("$site_dir/images") or pre_echo("WARNING : Unable to remove '$site_dir/images'");
		}

		$path = ($site->public) ? $UNRESTRICTED_PATH : $RESTRICTED_PATH;
		rename($site_dir, "$path/site/$siteid") or die("Unable to move '$site_dir' to '$path/site/$siteid'");

    } #end if
	$web->forget_site($siteid);

}#end foreach

rmdir("$DATA_PATH/web/site") or pre_echo("Unable to remove '$DATA_PATH/web/site' you will need to do this manually");

 ########################################################################
# move the design dirs, let's make them all unrestricted for the moment
$sql = "ALTER TABLE site_design ADD COLUMN public TINYINT UNSIGNED NOT NULL DEFAULT 1;";
$webdb->select($sql);
$sql = "UPDATE site_design SET public = 1";
$webdb->update($sql);
$designs = &$web->get_site_design_list();
foreach($designs as $designid => $name) {
	# if this is a dir
    if (is_dir("$DATA_PATH/web/site_design/d$designid")) { 
		rename("$DATA_PATH/web/site_design/d$designid", "$UNRESTRICTED_PATH/site/design/$designid") or die("Unable to move '$DATA_PATH/web/site_design/d$designid' to '$DATA_PATH/site/design/$designid'");
    } #end if

}#end foreach
rmdir("$DATA_PATH/web/site_design") or pre_echo("Unable to remove '$DATA_PATH/web/site_design' you will need to do this manually");


 ################################################################
# move all the extensions in the web/images directory back a level, restricted
if (is_dir("$DATA_PATH/web/images/extensions")) {
	rename("$DATA_PATH/web/images/extensions", "$RESTRICTED_PATH/web/extensions") or die("Unable to move '$DATA_PATH/web/images/extensions' to '$RESTRICTED_PATH/web/extensions'");
}
 
 ###################################################################################
# move all the stuff in the web/images to /web directory on the unrestricted side
if (is_dir("$DATA_PATH/web/images")) {
	$images_ptr = @opendir("$DATA_PATH/web/images") or die("Unable to open old dir - '$DATA_PATH/web/images'");
	while (false !== ($images_file = readdir($images_ptr))) { 
		if ($images_file != "." && $images_file != "..") {
			rename("$DATA_PATH/web/images/$images_file", "$UNRESTRICTED_PATH/web/$images_file") or die("Unable to move '$DATA_PATH/web/images/$images_file' to '$UNRESTRICTED_PATH/web/$images_file'");
		}#end if
	}#end while
	closedir($images_ptr);
	rmdir("$DATA_PATH/web/images") or pre_echo("Unable to remove '$DATA_PATH/web/images' you will need to do this manually");
}

rmdir("$DATA_PATH/web") or pre_echo("Unable to remove '$DATA_PATH/web' you will need to do this manually");


 #################################################
# move the user dirs, all unrestricted
if (is_dir("$DATA_PATH/users")) {
	$users_ptr = @opendir("$DATA_PATH/users") or die("Unable to open old dir - '$DATA_PATH/site/design'");
	while (false !== ($users_file = readdir($users_ptr))) { 
		if ($users_file == "." || $users_file == "..") continue;
		# if this is a dir and of the right format
		if (is_dir("$DATA_PATH/users/$users_file") && ereg('^u([0-9]+)', $users_file, $matches)) { 
			$usersid = $matches[1];
			rename("$DATA_PATH/users/$users_file", "$UNRESTRICTED_PATH/user/$usersid") or die("Unable to move '$DATA_PATH/users/$users_file' to '$UNRESTRICTED_PATH/user/$usersid'");
		} #end if

	}#end while
	closedir($users_ptr); 

	 ################################################################
	# move all the extensions in the users/images directory back a level, restricted
	if (is_dir("$DATA_PATH/users/images/extensions")) {
		rename("$DATA_PATH/users/images/extensions", "$RESTRICTED_PATH/users/extensions") or die("Unable to move '$DATA_PATH/users/images/extensions' to '$RESTRICTED_PATH/users/extensions'");
	}

	 ################################################################
	# move everything else in the users/images directory back a level, unresctricted
	if (is_dir("$DATA_PATH/users/images")) {
		$images_ptr = @opendir("$DATA_PATH/users/images") or die("Unable to open old dir - '$DATA_PATH/users/images'");
		while (false !== ($images_file = readdir($images_ptr))) { 
			if ($images_file != "." && $images_file != "..") {
				rename("$DATA_PATH/users/images/$images_file", "$UNRESTRICTED_PATH/users/$images_file") or die("Unable to move '$DATA_PATH/users/images/$images_file' to '$UNRESTRICTED_PATH/users/$images_file'");
			}#end if
		}#end while
		closedir($images_ptr);
		rmdir("$DATA_PATH/users/images") or pre_echo("Unable to remove '$DATA_PATH/users/images' you will need to do this manually");
	}

	rmdir("$DATA_PATH/users") or pre_echo("Unable to remove '$DATA_PATH/users' you will need to do this manually");
}#end if

clear_mysource_cache();

?>
<br>
<a href="./">...upgrade complete.</a>
