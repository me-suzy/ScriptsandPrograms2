<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage handler
 */
require_once('ow.php');
require_once('core/basicclass.php'); #we need some defines from basicclass
include_once('core/ua/phpsniff.class.php');

class Basic_user {
	var $_unlimitedaccess = false;
	var $_systemaccountid = NULL;
	var $_anonymousgroupid = NULL;
	var $_client = NULL;
	var $_objectidstack = array();
	var $_headercache = array();
	var $_stylecache = array();
	var $_cleardump = false;
	var $_tempapp = null;
	
	function Basic_user() {
	}
	
	// Do not remember $_unlimitedaccess in sessions
	// Adds additional security. 
	function __wakeup() {
		$this->_unlimitedaccess = false;
	}
	
	function addHeaderCache($id, $content) {
		if ($content) $this->_headercache[$id] = $content;
	}
	
	function addStyleCache($id, $content) {
		if ($content) $this->_stylecache[$id] = $content;
	}

	function getHeaderCache() {
		return $this->_headercache;
	}

	function getStyleCache() {
		return $this->_stylecache;
	}
	
	function setClearDump($bool) {
		$this->_cleardump = $bool;
	}

	function getClearDump() {
		return $this->_cleardump;
	}
		
	function setObjectIdStack($oid) {
		if (is_array($oid)) {
			$this->_objectidstack = $oid;
		} else {
			$this->_objectidstack[] = $oid;
		}
	}
	
	function getObjectIdStack() {
		return $this->_objectidstack;
	}
	
	function clearObjectIdStack() {
		$this->_objectidstack = array();
	}
	
	/**
	 * Only for dealing with very special cases, where the current user needs
	 * to be granted unlimited access to all objects
	 * At the moment only used in eventhandler->fireEvent, to allow for retrieval
	 * of data on other users and usergroups.
	 * The value is (of course) not persistent between page impressions
	 * but remember to set the value to false again as soon as possible.
	 * Use with care!
	 */
	function setUnlimitedAccess($value) {
		$this->_unlimitedaccess = $value;
	}
	
	function getUnlimitedAccess() {
		return $this->_unlimitedaccess;
	}
	
	function loggedIn() {
		if ($this->getWebuser()) {
			if ($this->getLevel() >= ACCESS_USER ) { 
				return true;
			} else {
				return false;
			}
		} else {
			if ($this->getLevel() >= ACCESS_EDITOR ) { 
				return true;
			} else {
				return false;
			}
		}
	}
	
	function getRevisionControl() {
		global $CONFIG;
		$app = $this->GetAppName();
		if (isset($CONFIG['revisioncontrol']) && is_array($CONFIG['revisioncontrol'])) {
			return (in_array($app, $CONFIG['revisioncontrol']));
		} else if ($app == 'edocument') {
			return (isset($CONFIG['revisioncontrol'])) ? $CONFIG['revisioncontrol'] : true;
		}
		return false;
	}
	
	function getName() {
		assert('!empty($_SESSION[\'usr\'][\'validusername\']); //* $_SESSION[\'usr\'][\'validusername\'] must be non-empty');
		return $_SESSION['usr']['validusername'];
	}
	
	function getObjectId() {
		assert('!empty($_SESSION[\'usr\'][\'validuserid\']) && is_numeric($_SESSION[\'usr\'][\'validuserid\']); //* $_SESSION[\'usr\'][\'validuserid\'] must be set and be an integer');
		return $_SESSION['usr']['validuserid'];
	}
	
	function getLevel() {
		return (isset($_SESSION['usr']['validuserlevel'])) ? 
			$_SESSION['usr']['validuserlevel'] : ACCESS_ANONYMOUS;
	}
	
	function getGroups() {
		assert('$this->getLevel() >= ACCESS_ANONYMOUS; //* $this->getLevel must be at least ACCESS_ANONYMOUS');
		if ($this->getLevel() == ACCESS_ANONYMOUS) 
			return array($this->getAnonymousGroupId());
		return $_SESSION['usr']['validusergroups'];
	}
	
	function getProfile() {
		return $_SESSION['usr']['validprofile'];
	}
	
	function getSite() {
		global $site;
		if (!isset($_SESSION['site'])) $_SESSION['site'] = $site;
		assert('!empty($_SESSION[\'site\']); //* $_SESSION[\'site\'] must be non-empty');
		return (int)$_SESSION['site'];
	}
	
	function getWebuser() {
		if (isset($_SESSION['usr']['webuser'])) return $_SESSION['usr']['webuser'];
		return false;
	}
	
	function setWebuser($value) {
		$_SESSION['usr']['webuser'] = $value;
	}
	
	function getSystemUrl() {
		global $system_url;
		/**
		 * $system_url is always set in config.php
		 */
		assert('!empty($system_url); //* $system_url must be non-empty');
		return $system_url;
	}
	
	function getSystemPath() {
		/**
		 * $system_path is always set in config.php
		 */
		global $system_path;
		assert('!empty($system_path); //* $system_path must be non-empty');
		return $system_path;
	}
		
	function getViewerUrl() {
		global $viewer_url;
		/**
		 * $viewer_url is only set in site.php (and is only accessible in web-mode)
		 */
		if (!isset($_SESSION['usr']['viewerurl'])) {
			if (!empty($viewer_url)) {
				$_SESSION['usr']['viewerurl'] = $viewer_url;
			}
		}
		assert('!empty($_SESSION[\'usr\'][\'viewerurl\']); //* $viewer_url must be non-empty');
		return $_SESSION['usr']['viewerurl'];
	}
	
	function setViewerUrl($url) {
		$_SESSION['usr']['viewerurl'] = $url;
	}

	function getViewerPath() {
		global $viewer_path;
		if (!isset($_SESSION['usr']['viewerpath'])) {
			if (!empty($viewer_path)) {
				$_SESSION['usr']['viewerpath'] = $viewer_path;
			}
		}
		assert('!empty($_SESSION[\'usr\'][\'viewerpath\']); //* $viewer_path must be non-empty');
		return $_SESSION['usr']['viewerpath'];
	}
	
	function setViewerPath($path) {
		$_SESSION['usr']['viewerpath'] = $path;
	}
	
	function getProfileView($type,$view) {
		if ($_SESSION['usr']['validprofile'][$type][$view] == 1) return true;
		return false;
	}

	function getProfileEditor($field) {
		if ($_SESSION['usr']['validprofile'][$field] == 1 || $this->getLevel() == ACCESS_ADMINISTRATOR) return true;
		return false;
	}
	
	function getCreatedBy() {
		$obj = owRead($this->getObjectId());
		return $obj->elements[0]['object']['createdby'];
	}
	

	function logOut() {
		unset($_SESSION['usr']);
		unset($_SESSION['site']);
		unset($_SESSION['guitemp']);
	}

	function logOutWebsite() {
		unset($_SESSION['usr']);
	}

	function loadUserCfg() {
		global $system_path;
		$_SESSION['gui'] = array();
		$filename = $system_path."sites/".$this->getSite()."/usercfg/".$this->getName().".php";
		if (file_exists($filename)) {
			include($filename);
			$_SESSION['gui'] = $USERCFG;
		}
	}

	function correctlogIn($lsite, $username, $password) {
		global $CONFIG;
		
		$db =& getDbConn();
		
		if ($CONFIG['ntlm'] && !empty($_SERVER['AUTH_USER'])) {
			// AUTH_USER is in the form domain\username. Remove domain
			$username = substr(strrchr($_SERVER["AUTH_USER"], "\\"), 1);
			if (!empty($username)) {
				return $db->getOne("select count(*) from user, object where user.objectid = object.objectid and site= '" . $lsite . "' and name='$username' and deleted=0 and active=1");
			}
		}

		
		$lsite = (int)$lsite;
		$usernamedb = $db->qstr($username);
		$passworddb = $db->qstr($password);
		
		if ($db->getone("select object.objectid from user, object where user.objectid = object.objectid and site=$lsite and name=$usernamedb and password = MD5($passworddb) and deleted=0 and active=1")) {
			return true;
		} else if ($db->getone("select object.objectid from user, object where user.objectid = object.objectid and site=$lsite and name=$usernamedb and password = OLD_PASSWORD($passworddb) and deleted=0 and active=1")) {
			return true;
		} else if ($db->getone("select object.objectid from user, object where user.objectid = object.objectid and site=$lsite and name=$usernamedb and password = PASSWORD($passworddb) and deleted=0 and active=1")) {
			return true;
		} else {
			return false;
		}
	}
	
	function logIn($lsite, $username, $password, $website = 0) {
		global $CONFIG;
		$db =& getDbConn();
		/** 
		 * @todo Escape the username and password parameters to avoid sql injection
		 */
		if ($this->correctlogIn($lsite, $username, $password)) {
#			$this->logOut(); #reset all sessionvariables
			if ($CONFIG['ntlm'] && !empty($_SERVER['AUTH_USER'])) {
				// AUTH_USER is in the form domain\username. Remove domain
				$tmpusername = substr(strrchr($_SERVER["AUTH_USER"], "\\"), 1);
				if (!empty($tmpusername)) {
					$username = $tmpusername;
				}
			}

			$uid = $db->getone("select user.objectid as res from user, object where user.objectid = object.objectid and site= '$lsite' and name = '$username' and deleted=0 and active=1");
			$_SESSION['site'] = $lsite;
			#######
			$_SESSION['usr']['validusername'] = $username;
			$_SESSION['usr']['validuserid'] = $uid;
			$_SESSION['usr']['validuserlevel'] = $db->getone("select max(ug.level) as max from usergroupmember ugm inner join usergroup ug on ugm.groupid = ug.objectid where userid='$uid'");
			$_SESSION['usr']['validusergroups'] = $db->getcol("select groupid from usergroupmember where userid='$uid'");
			$row = $db->getrow("select * from site where site = '".$_SESSION['site']."'");
			$this->setViewerUrl($row['website_url']);
			$this->setViewerPath($row['website_path']);

			$_SESSION['usr']['saveduserid'] = $uid;
			setcookie("saveduserid",$_SESSION['usr']['saveduserid'],time()+31622400);

			$u = owNew('user');
			$u->setlistaccess(true);
			$u->readobject($uid);
			$_SESSION['usr']['restrictlanguage'] = $u->elements[0]['restrictlanguage'];
			if (!empty($u->elements[0]['objectlanguage'])) $_SESSION['usr']['objectlanguage'] = $u->elements[0]['objectlanguage'];
			#if ('' == $_SESSION['usr']['objectlanguage']) $_SESSION['usr']['objectlanguage'] = 'EN';
			if (!empty($u->elements[0]['guilistlanguage'])) $_SESSION['usr']['guilistlanguage'] = $u->elements[0]['guilistlanguage'];
			if (!empty($u->elements[0]['locale'])) $_SESSION['usr']['locale'] = $u->elements[0]['locale'];
			$_SESSION['usr']['validrootdir'] = $u->elements[0]['rootdir'];
			$_SESSION['usr']['realname'] = $u->elements[0]['realname'];
			$_SESSION['app'] = $u->elements[0]['app'];
			$_SESSION['usr']['appavail'] = $u->elements[0]['appavail'];
			$_SESSION['usr']['guilanguage'] = strtolower($u->elements[0]['guilanguage']);
			if ('' == $_SESSION['usr']['guilanguage']) $_SESSION['usr']['guilanguage'] = 'en';
			$_SESSION['usr']['oldeditor'] = $u->elements[0]['oldeditor'];

			$pobj = owNew('profile');
			$pobj->setlistaccess(true);
			if ($u->elements[0]['profileid'] != 0) {
				$pobj->readobject($u->elements[0]['profileid']);
			} else {
				if ($pobj->locatedefault()) $pobj->readobject($pobj->locatedefault());
			}
			$_SESSION['usr']['validprofile'] = $pobj->elements[0];
			
			$this->loadUserCfg();
			$db->execute("insert into statistics_login (sessionid, userid, timestamp, site, username, ip, website, failed) values ('".session_id()."','".$this->getObjectId()."',NOW(),'".$lsite."','".$username."','".$this->getIp()."','".$website."',0)");
			return true;
		} else {
			$db->execute("insert into statistics_login (sessionid, userid, timestamp, site, username, ip, website, failed) values ('".session_id()."','0',NOW(),'".$lsite."','".$username."','".$this->getIp()."','".$website."',1)");
			return false;
		}
	}

	/**
	 * Only for use from showpage.php
	 */
	function recognizeUser($force = false) {
		global $CONFIG;

		if ($CONFIG['ntlm'] && !empty($_SERVER['AUTH_USER'])) {
			$this->login($this->getsite(), '', '');
		}

		if (!isset($_SESSION['usr']['saveduserid']) || $force) {
			# if not, try to recognize the user from cookie
			$notfound = false;
			if ($_COOKIE['saveduserid'] <> '') {
				$_SESSION['usr']['saveduserid'] = $_COOKIE['saveduserid'];
				$_SESSION['usr']['validuserid'] = $_COOKIE['saveduserid'];
				$_SESSION['usr']['validusername'] = 'AutoUser';
				$eh =& getErrorHandler();
				$eh->disable();
				$u = owRead($_SESSION['usr']['validuserid']);
				$eh->enable();
				if ($u) {
					$_SESSION['usr']['validusername'] = $u->elements[0]['name'];
				} else {
					$notfound = true;
				}
			} else {
				$notfound = true;
			}
			
			if ($notfound) {
				# Create temporary settings for usr-array, because we cannot
				# instantiate a user-object if these variables are not set
				$_SESSION['usr']['validuserid'] = $this->getSystemAccountId();
				$_SESSION['usr']['validusername'] = 'AutoUser';
				
				$u = owNew('user');
				$u->createObject(array("name" => "AutoUser (¤_¤) ".$this->GetIp()." on ".date('Y-m-d H:i:s'), "objectlanguage" => $this->getPrimaryLanguage()),0);
				$u->setSysHidden(true);
				$u->setWebHidden(true);
				# remove line below, when we have fixed, that the elements-array
				# is properly set after a createobject call
				$u->readObject($u->getobjectid());
				$_SESSION['usr']['validuserid'] = $u->getobjectid();
				$_SESSION['usr']['validusername'] = $u->getName();
				$_SESSION['usr']['saveduserid'] = $_SESSION['usr']['validuserid'];
				setcookie("saveduserid",$_SESSION['usr']['saveduserid'],time()+31622400);
				unset($u);
			}
		}
	}
	
	function getLanguage() {
		return (isset($_SESSION['lang'])) ? $_SESSION['lang'] : $this->getPrimaryLanguage();
	}
	
	function setLanguage($lang) {
		$_SESSION['lang'] = $lang;
		$this->setGuiLanguage($lang);
	}
	
	function getGuiLanguage() {
		return $_SESSION['usr']['guilanguage'];
	}
	
	function setGuiLanguage($lang) {
		$_SESSION['usr']['guilanguage'] = strtolower($lang);
	}
	
	function getPrimaryLanguage() {
		global $CONFIG;
		return (isset($CONFIG['primary_language'])) ? $CONFIG['primary_language'] : 'EN';
	}
	
	function getObjectLanguage() {
		return (!empty($_SESSION['usr']['objectlanguage'])) ? $_SESSION['usr']['objectlanguage'] : $this->getPrimaryLanguage();
	}
	
	function getGuiListLanguage() {
		return (isset($_SESSION['usr']['guilistlanguage'])) ? $_SESSION['usr']['guilistlanguage'] : $this->getPrimaryLanguage();
	}

	function getLocale() {
		return (isset($_SESSION['usr']['locale'])) ? $_SESSION['usr']['locale'] : $this->getPrimaryLanguage();
	}
	
	function getLastVariantLanguage() {
		return (isset($_SESSION['usr']['variantlanguage'])) ? $_SESSION['usr']['variantlanguage'] : $this->getPrimaryLanguage();
	}

	function setLastVariantLanguage($lang) {
		$_SESSION['usr']['variantlanguage'] = $lang;
	}
		
	function getRestrictLanguage() {
		return (!empty($_SESSION['usr']['restrictlanguage'])) ? $_SESSION['usr']['restrictlanguage'] : false;
	}

	function getOldEditor() {
		return $_SESSION['usr']['oldeditor'];
	}
	
	function getUserAgent() {
		if ($this->_client == NULL) {
			$this->_client = new phpSniff($UA,0);
			$this->_client->init();
		}
	}
	
	function getIp() {
		$this->getUserAgent();
		return ('' != $this->_client->_browser_info['ua']) ? 
			$this->_client->property('ip') : '';
	}
	
	function getSmartyVars() {
		$user['name']		= $this->getName();
		$user['publicname']	= ($_SESSION['usr']['realname'] != "") ? $_SESSION['usr']['realname'] : $this->getName();
		$user['objectid']	= $this->getObjectId();
		$user['level']		= $this->getLevel();
		$user['language']		= $this->getLanguage();
		$this->getUserAgent();
		if ('' != $this->_client->_browser_info['ua']) {
			$user['useragent']		= $this->_client->property('ua');
			$user['browser']		= $this->_client->property('browser');
			$user['browserlongname']	= $this->_client->property('browserlongname');
			$user['browserversion']		= $this->_client->property('version');
			$user['browsermajversion']	= $this->_client->property('maj_ver');
			$user['browserminversion']	= $this->_client->property('min_ver');
			$user['browserletterversion']	= $this->_client->property('letter_ver');
			$user['javascript']		= $this->_client->property('javascript');
			$user['platform']		= $this->_client->property('platform');
			$user['os']			= $this->_client->property('os');
			$user['ip']			= $this->_client->property('ip');
			$user['browserlanguage']	= strtoupper($this->_client->property('language'));
		}
		return $user;
	}

	function &getSmarty() {
		define("SMARTY_DIR" , $this->getSystemPath() . 'core/template/');
		require_once($this->getSystemPath() . 'core/template/Smarty.class.php');
		$smarty = new Smarty;
		$smarty->template_dir = $this->getSystemPath()."sites/".$this->getSite();
		$smarty->compile_dir = $this->getSystemPath()."sites/".$this->getSite()."/compile";
		$smarty->config_dir = $this->getDirTplCfg();
		$smarty->compile_check = true;
		$smarty->debugging = false;
		$smarty->plugins_dir = array(SMARTY_DIR.'metajour', SMARTY_DIR.'/plugins/');
		$smarty->assign("user",$this->getSmartyVars());
		$system['site'] = $this->getSite();
		$MeUrl = $this->getViewerUrl().'showpage.php?pageid='.$_REQUEST['pageid'];
		$smarty->assign("me",$MeUrl);
		$smarty->assign('engine','showpage.php?pageid=');
		$db =& getDbConn();		
		$system['title'] = $db->getOne('select name from site where site = '.$this->getSite());
		$system['viewer_url'] = $this->getViewerUrl();
		$system['viewer_path'] = $this->getViewerPath();
		$system['system_url'] = $this->getSystemUrl();
		$system['system_path'] = $this->getSystemPath();
		$smarty->assign("system",$system);
		$smarty->assign("server",$_SERVER);
		$smarty->assign("get",$_GET);
		$smarty->assign("post",$_POST);
		$smarty->assign("request",$_REQUEST);
		return $smarty;
	}
	
	function getViewCfg($otype, $name) {
		if (isset($_SESSION['gui'][$otype][$name])) return $_SESSION['gui'][$otype][$name];
		return false;
	}

	function isFieldSecret($otype, $fieldname) {
		if ($_SESSION['gui'][$otype][$fieldname]['_fieldsecret_']) return true;
		return false;
	}
	
	function isFieldHidden($otype, $fieldname) {
		if ($_SESSION['gui'][$otype][$fieldname]['_fieldhidden_']) return true;
		return false;
	}
	
	function fieldDefault($otype, $fieldname) {
		if (isset($_SESSION['gui'][$otype][$fieldname]['_fielddefault_'])) return $_SESSION['gui'][$otype][$fieldname]['_fielddefault_'];
	}
	
	function getPrgName() {
		if ($this->getAppName() != '') {
			$namefile = $this->getSystemPath().'app/'.$this->getAppName().'/lang/appname.da.php';
			if (file_exists($namefile)) {
				include($namefile);
				return $LANG['appname'];
			}
		} else {
			return 'IPW METAjour';
		}
	}
	
	function getAppName() {
		if (!is_null($this->_tempapp)) return $this->_tempapp;
		if ($_SESSION['app'] == 'metajour') return '';
		if (isset($_SESSION['app'])) return $_SESSION['app'];
		return '';
	}

	function setAppName($app) {
		$_SESSION['app'] = $app;
	}

	function setTempAppName($app) {
		$this->_tempapp = $app;
	}
	
	function isAppAvail($value) {
		if ($this->getLevel() == ACCESS_ADMINISTRATOR) return true;
		if (in_array($value,$_SESSION['usr']['appavail'])) return true;
	}
	
	function getAppAvail() {
		return $_SESSION['usr']['appavail'];
	}
	
	function getVendor() {
		return "IPW Systems a&middot;s";
	}
	
	function getVersion() {
		return "2.1";
	}
	
	function getSystemAccountId() {
		$db =& getDbConn();
		if ($this->_systemaccountid == NULL) {
			$tmp = $db->getone("select user.objectid as res from user, object where user.objectid = object.objectid and site = '".$this->getSite()."' and name = 'SYSTEM' and object.deleted = 0 and object.active = 1");
			if ($tmp) $this->_systemaccountid = $tmp;
		}
		assert('!empty($this->_systemaccountid) && is_numeric($this->_systemaccountid); //* $this->_systemaccountid must be set and be an integer');
		return $this->_systemaccountid;
	}
	
	function getAnonymousGroupId() {
		$db =& getDbConn();
		if ($this->_anonymousgroupid == NULL) {
			$tmp = $db->getone("select usergroup.objectid as res from usergroup, object where usergroup.objectid = object.objectid and site = '".$this->getSite()."' and level = '".ACCESS_ANONYMOUS."' and object.deleted = 0 and object.active = 1");
			if ($tmp) $this->_anonymousgroupid = $tmp;
		}
		assert('!empty($this->_anonymousgroupid) && is_numeric($this->_anonymousgroupid); //* $this->_anonymousgroupid must be set and be an integer');
		return $this->_anonymousgroupid;
	}
	
	function getDirFilter() {
		return $this->getSystemPath()."sites/".$this->getSite()."/filter/";
	}

	function getDirFilterUpload() {
		return $this->getSystemPath()."sites/".$this->getSite()."/filterupload/";
	}

	function getDirBinfile() {
		return $this->getSystemPath()."sites/".$this->getSite()."/binfile/";
	}
	
	function getDirBinfileCache() {
		return $this->getSystemPath()."sites/".$this->getSite()."/binfilecache/";
	}
	
	function getDirStaticbinfile() {
		return $this->getSystemPath()."sites/".$this->getSite()."/staticbinfile/";
	}
	
	function getDirStimgbinfile() {
		return $this->getViewerPath()."img/";
	}
	
	function getDirStfilebinfile() {
		return $this->getViewerPath()."files/";
	}

	function getDirTplCfg() {
		return $this->getSystemPath()."sites/".$this->getSite()."/tplcfg/";
	}
	
	function getUrlFile() {
		return $this->getViewerUrl()."img/";
	}
	
	function getUrlImg() {
		return $this->getViewerUrl()."files/";
	}
	
	function getStatExclIp() {
		global $CONFIG;
		return $CONFIG['statexclip'];
	}
}

function &getUserHandler() {
	static $_userhandler = null;
	if (null === $_userhandler) {
		$_userhandler = new basic_user;
	}
	return $_userhandler;
}

assert_options( ASSERT_CALLBACK, 'assert_callback');
function assert_callback( $script, $line, $message ) {
	echo 'ASSERT CHECK FAILED: <b>', $script,'</b> on line <b>', $line,'</b> :<br />';
	echo '<b>Description: ', ereg_replace( '^.*//\*', '', $message ), '</b><br />';
	exit;
}

?>