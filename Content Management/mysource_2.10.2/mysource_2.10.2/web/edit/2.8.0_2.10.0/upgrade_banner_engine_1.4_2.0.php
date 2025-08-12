<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/mysource/web/edit/2.8.0_2.10.0/upgrade_banner_engine_1.4_2.0.php,v $
## $Revision: 1.1.2.1 $
## $Author: brobertson $
## $Date: 2004/05/11 11:29:27 $
#######################################################################
# Initialise
include_once("../../init.php");
#---------------------------------------------------------------------#

include_once('./upgrade_functions.php');

$session = &get_mysource_session();

 ######################################
# tell anyone who isn't root .... sorry
if (!$session->logged_in()) {
	$session->login_screen("Upgrade Banner Engine", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$session->login_screen("Upgrade Banner Engine", "You must be <b>root</b> to upgrade the system.",$session->user->login);
	exit();
}

if (!is_file($SYSTEM_ROOT."/xtras/site/extensions/banner_engine/banner_engine.inc")) {
	report_ignore($_SERVER['SCRIPT_FILENAME']);
	exit();
}

if (is_file($_SERVER['SCRIPT_FILENAME'] . '.success')) {
	echo 'This upgrade has already run. Aborting.<br />';
	exit();
}

if (is_file($_SERVER['SCRIPT_FILENAME'] . '.failure')) {
	unlink($_SERVER['SCRIPT_FILENAME'] . '.failure');
}

?>
Upgrading MySource Banner Engine.<br><br>
<?
error_reporting(5);
$web = &get_web_system();
$webdb = &$web->get_db();
global $CACHE;

if(!$_REQUEST['step']) {

	$queries[] = "CREATE TABLE xtra_site_extension_banner_engine_backup (
	siteid		MEDIUMINT(7) UNSIGNED NOT NULL PRIMARY KEY
	);";

	$queries[] = "CREATE TABLE xtra_site_extension_banner_engine_banner_backup (
		bannerid        MEDIUMINT(7) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		siteid	        MEDIUMINT(7) UNSIGNED,
		to_pageid	MEDIUMINT(9) UNSIGNED,
		to_url		VARCHAR(255),
		new_window	enum('Y','N'),
		type    enum('full', 'half', 'tile','other'),
		width   int(5),
		height  int(5),
		name		varchar(255)
	);";

	$insert_queries[] = "INSERT INTO xtra_site_extension_banner_engine_backup SELECT * FROM xtra_site_extension_banner_engine";
	$insert_queries[] = "INSERT INTO xtra_site_extension_banner_engine_banner_backup SELECT * FROM xtra_site_extension_banner_engine_banner";

	foreach($queries as $query) {
		if(!$webdb->select($query)) {
			echo "Error - There was a problem creating a backup banner egnine table. The rest of the script can't run until this problem is fixed.<p>";
			report_failure($_SERVER['SCRIPT_FILENAME']);
			exit;
		}
	}
	
	foreach($insert_queries as $query) {
		$webdb->insert($query);
	}

	$sql[] = "DROP TABLE xtra_site_extension_banner_engine;";
	$sql[] = "DROP TABLE xtra_site_extension_banner_engine_banner;";
	$sql[] = "DROP TABLE xtra_site_extension_banner_engine_banner_page_link;";
	$sql[] = "DROP TABLE xtra_site_extension_banner_engine_log_click;";
	$sql[] = "DROP TABLE xtra_site_extension_banner_engine_log_impression;";

	$sql[] = "CREATE TABLE xtra_site_extension_banner_engine (
	  siteid          MEDIUMINT(7) UNSIGNED NOT NULL PRIMARY KEY,
	  parameters      LONGTEXT
	);";

	$sql[] = "CREATE TABLE xtra_site_extension_banner_engine_banner (
	  bannerid        MEDIUMINT(7) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  siteid          MEDIUMINT(7) UNSIGNED,
	  parameters      LONGTEXT,
	  start_date      DATE NOT NULL,
	  end_date        DATE NOT NULL,
	  KEY             siteid (siteid),
	  KEY             start_date (start_date),
	  KEY             end_date (end_date)
	);";

	$sql[] = "CREATE TABLE xtra_site_extension_banner_engine_log_click (
	  bannerid        MEDIUMINT(7) UNSIGNED DEFAULT '0' NOT NULL,
	  pageid          MEDIUMINT(9) UNSIGNED DEFAULT '0' NOT NULL,
	  hit_time        DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
	  sessionid       CHAR(32) DEFAULT '' NOT NULL,
	  userid          MEDIUMINT(9) UNSIGNED DEFAULT '0' NOT NULL,
	  KEY             bannerid(bannerid),
	  KEY             pageid (pageid),
	  KEY             hit_time (hit_time),
	  KEY             sessionid (sessionid),
	  KEY             userid (userid)
	);";

	$sql[] = "CREATE TABLE xtra_site_extension_banner_engine_log_impression (
	  bannerid      MEDIUMINT(7) UNSIGNED DEFAULT '0' NOT NULL,
	  pageid        MEDIUMINT(9) UNSIGNED DEFAULT '0' NOT NULL,
	  hit_time      DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
	  sessionid     CHAR(32) DEFAULT '' NOT NULL,
	  userid        MEDIUMINT(9) DEFAULT '' NOT NULL,
	  KEY           bannerid(bannerid),
	  KEY           pageid(pageid),
	  KEY           hit_time(hit_time),
	  KEY           sessionid(sessionid),
	  KEY           userid(userid)
	);";
	

	

	foreach($sql as $run) $webdb->select($run);

		?> 
	<p>The database changes for the bmail upgrade are done.
	<br>Do you have any serious <b>database errors</b> (e.g. Not including trying to drop a column that doesn't exist)?
	<p>
	<form action="<?= $_SERVER[PHP_SELF]; ?>" name='upgrade' method="POST"> 
		<input type=hidden name=step value="">
		<input type="button" name="no" value="No" onClick="document.upgrade.step.value=2; document.upgrade.submit()">
		<input type="button" name="yes" value="Yes" onClick="javascript:alert('sorry, you can\'t go on any further with this upgrade - please check the database backup table exists, the data has been copied correctly and columns for the banner engine table have been changed.'); return false;">
	</form>
	<?	

} elseif ($_REQUEST['step'] == 2) {
	$siteids = $webdb->single_column('SELECT siteid FROM xtra_site_extension_banner_engine_backup');

	foreach($siteids as $siteid) {
		$webdb->insert("INSERT INTO xtra_site_extension_banner_engine (siteid) VALUES ($siteid)");
	}

	$banners = $webdb->associative_array('SELECT bannerid,siteid,to_pageid,to_url,new_window,name FROM xtra_site_extension_banner_engine_banner_backup');

	foreach($banners as $bannerid => $data) {
		$page = &$web->get_page($data['to_pageid']);
		$site = &$web->get_site($page->siteid);
		$params['name'] = $data['name'];
		$params['new_window'] = $data['new_window'];
		$params['to_siteid'] = $site->id;
		$params['to_pageid'] = $data['to_pageid'];
		$params['to_url'] = $data['to_url'];
		$webdb->insert('INSERT INTO xtra_site_extension_banner_engine_banner (bannerid,siteid,parameters) VALUES ('.$bannerid.','.$data['siteid'].',"'.addslashes(serialize($params)).'")');
	}

	foreach($siteids as $siteid) {
		$site = &$web->get_site($siteid);
		$banner_engine = &$site->get_extension('banner_engine');
		if(is_dir($banner_engine->data_path)) {
			if($dh = opendir($banner_engine->data_path)) {
				while(($file = readdir($dh)) !== false) {
					if(!preg_match("/^\./",$file)) {
						preg_match("/([0-9]+)\./",$file,$matches);
						create_directory($banner_engine->data_path."/banner/$matches[1]");
						copy($banner_engine->data_path."/$file",$banner_engine->data_path."/banner/$matches[1]/$file");
						unlink($banner_engine->data_path."/$file");
					}
				}
			}
			closedir($dh);
		}
	}


	echo "Banner Engine has been upgraded. Dropping backup table...<p>";
	
	$sql[] = "DROP TABLE xtra_site_extension_banner_engine_backup;";
	$sql[] = "DROP TABLE xtra_site_extension_banner_engine_banner_backup;";

	foreach($sql as $run) {
		$webdb->select($run);
	}

	# Lets copy all the file over

	report_success($_SERVER['SCRIPT_FILENAME']);

	echo "All bmail pages have been converted. Dropping backup tables...<p>";
	
	report_success($_SERVER['SCRIPT_FILENAME']);
}