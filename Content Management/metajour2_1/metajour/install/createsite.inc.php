<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jesper Laursen <jl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 */

function createSite($username, $password, $path, $url, $name, $language) {
	global $system_path, $site, $viewer_url, $viewer_path;

	/* save current values */	
	$oldusr = $_SESSION['usr'];
	$oldsite = $site;
	$oldviewer_url = $viewer_url;
	$oldviewer_path = $viewer_path;

	/* set important variables */	
	$viewer_url = $url;
	$viewer_path = $path; 
	$_SESSION['usr']['validuserid'] = 1;
		
	$db =& getDbConn();
	$site = $db->getOne("select max(site) as site from site");
	$site++;
	$_SESSION['site'] = $site;
	$db->query("INSERT INTO site VALUES ($site, '$name', '$viewer_path', '$url', '')");

	$u = owNew('user');
	$u->createObject(array("name" => 'SYSTEM'));

	$u->createObject(array("name" => $username, "password" => $password, "objectlanguage" => $language, "guilanguage" => $language));
	$userid = $u->getObjectId();
	$db->query("update object set createdby = $userid, changedby = $userid, checkedby = $userid where objectid = $userid");

	$g = owNew('usergroup');
	$g->createObject(array("name" => "ANONYMOUS"));
	$id = $g->getObjectId();
	$db->query("update usergroup set level=0 where objectid = $id");

	$g->createObject(array("name" => "USER"));
	$id = $g->getObjectId();
	$db->query("update usergroup set level=10 where objectid = $id");

	$g->createObject(array("name" => "EDITOR"));
	$id = $g->getObjectId();
	$db->query("update usergroup set level=20 where objectid = $id");

	$g->createObject(array("name" => "MANAGER"));
	$id = $g->getObjectId();
	$db->query("update usergroup set level=30 where objectid = $id");

	$g->createObject(array("name" => "ADMINISTRATOR"));
	$id = $g->getObjectId();
	$admingroup = $g->getobjectid();
	$db->query("update usergroup set level=40 where objectid = $id");
	$db->query("insert into usergroupmember (groupid, userid) values ($admingroup, $userid)");

	$obj = owNew('structure');
	$obj->createObject(array("name" => "main"));

	$db->query("update object set createdby = $userid where site = $site");
	$db->query("update object set changedby = $userid where site = $site");
	$db->query("update object set checkedby = $userid where site = $site");

	if (!file_exists($system_path."/sites")) {
		if (!mkdir($system_path."/sites")) {
			"ERROR: Could not create ".$system_path."/sites"."<BR>";
		}
	}
    
    $site_path = $system_path."/sites/".$site;
    
    echo createDirectory($site_path);
    echo createDirectory($site_path.'/binfile');
		echo createDirectory($site_path.'/binfilecache');
    echo createDirectory($site_path.'/filter');
    echo createDirectory($site_path.'/filterupload');
    echo createDirectory($site_path.'/tplcfg');
    echo createDirectory($site_path.'/compile');
    echo createDirectory($site_path.'/cache');
    echo createDirectory($site_path.'/usercfg');

	if (!file_exists($viewer_path)) {
		if (!mkdir($viewer_path)) {
			echo "<b>Warning</b>: Remember to create ".$viewer_path."<BR>";
		}
	}
	if (!file_exists($viewer_path."/img")) {
		if (!mkdir($viewer_path."/img")) {
			echo "<b>Warning</b>: Remember to create ".$viewer_path."/img"."<BR>";
		}
	}
	
	$sitephp = "<?php
# All site specific configuration settings are located in this file
# Common settings shared by all sites are placed in the config.php file
# in your METAjour directory

# Site identification
\$site = '".$site."';

# Absolute path to the directory where the website is located
\$viewer_path = '".$viewer_path."';

# URL to the website
\$viewer_url = '".$viewer_url."';

\$CONFIG['primary_language'] = '".$language."';

# Inclusion of config.php
require('".$system_path."config.php');

\$CONFIG['doctype'] = 'DOCTYPE_401_TRANS_WITH_URL';
?>";
	
	if (!file_exists($viewer_path.'showpage.php')) {
		if (!copy($system_path.'install/root/showpage.php', $viewer_path.'showpage.php')) {
			echo "<b>Warning</b>: Failed to copy showpage.php to $viewer_path <br> \n";
		}
	}
	if (!file_exists($viewer_path.'index.php')) {
		if (!copy($system_path.'install/root/index.php', $viewer_path.'index.php')) {
			echo "<b>Warning</b>: Failed to copy index.php to $viewer_path <br> \n";
		}
	}
	if (!file_exists($viewer_path.'getfile.php')) {
		if (!copy($system_path.'install/root/getfile.php', $viewer_path.'getfile.php')) {
			echo "<b>Warning</b>: Failed to copy getfile.php to $viewer_path <br> \n";
		}
	}
	if (!file_exists($viewer_path.'getstylesheet.php')) {
		if (!copy($system_path.'install/root/getstylesheet.php', $viewer_path.'getstylesheet.php')) {
			echo "<b>Warning</b>: Failed to copy getstylesheet.php to $viewer_path <br> \n";
		}
	}
	if (!file_exists($viewer_path.'site.php')) {
		if (!$handle = fopen($viewer_path.'site.php', 'w')) {
			echo 'Warning: Cannot open file '.$viewer_path.'site.php<br> \n';
		} else {
			if (fwrite($handle, $sitephp) === FALSE) {
				echo 'Warning: Cannot write to file '.$viewer_path.'site.php<br> \n';
			}
			fclose($handle);
		}
	}
	
	$newsite = $site;

	$_SESSION['usr'] = $oldusr;
	$site = $oldsite;
	$_SESSION['site'] = $oldsite;
	$viewer_url = $oldviewer_url;
	$viewer_path = $oldviewer_path;

	return $newsite;
}

function createDirectory($dir) {
    if (is_dir($dir)) {
        return "<b>Warning</b>: Directory <b>".$dir."</b> exists"."<BR>";
	} elseif (!mkdir($dir)) {
        return "<b>Error</b>: Could not create <b>".$dir."</b><BR>";
	}
}

?>