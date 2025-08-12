<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
##
##
## $Source: /home/cvsroot/mysource/web/edit/upgrade_0.9.1_0.9.2.php,v $
## $Revision: 2.0 $
## $Author: agland $
## $Date: 2001/12/18 06:03:08 $
#######################################################################
# Initialise
include_once("../init.php");
#---------------------------------------------------------------------#

echo("Upgrading MySource 0.9.1 BETA to 0.9.2 BETA...<br><br>");

 ####################################################################
# Apply changes to the database structure (see db/web_ChangeLog.sql)
$web = &get_web_system();

$web_ChangeLog_sql = array(
	"ALTER TABLE log_session ADD referer TEXT NOT NULL",
	"CREATE TABLE url_lookup (
		url       VARCHAR(255) NOT NULL,
		info      TEXT,
		PRIMARY KEY(url)
	)",
	"CREATE TABLE page_dir (
	  pageid       MEDIUMINT    UNSIGNED NOT NULL,
	  dir          VARCHAR(255) NOT NULL,
	  orderno      TINYINT      UNSIGNED NOT NULL,
	  PRIMARY KEY (pageid,dir),
	  KEY         (dir),
	  KEY         (orderno)
	)"
);

foreach($web_ChangeLog_sql as $sql) {
	$web->db->select($sql);
}

$site_urls = $web->db->associative_array("SELECT url, siteid FROM site_url");

# Okay, register all of these
foreach($site_urls as $url => $siteid) {
	echo "Registering URL '$url' for site $siteid.<br>";
	$web->register_site_url($siteid,$url);
}

$page_names = $web->db->associative_array("SELECT pageid, short_name FROM page");

foreach($page_names as $id => $name) {
	$page = &$web->get_page($id);
	if(!$page->dirs) $page->dirs = array();
	echo $page->update_dirs($page->dirs + array($name)) ."<br>";
	$web->forget_page($id);
}

echo("<br><a href=\"./\">...done!</a>");

?>