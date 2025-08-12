<?  ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: include/web.inc
## Desc: An object that represents the web portion of the core
## $Source: /home/cvsroot/mysource/include/web.inc,v $
## $Revision: 2.71.2.1 $
## $Author: achadszinow $
## $Date: 2004/05/25 01:53:19 $
#######################################################################
global $INCLUDE_PATH;
require_once("$INCLUDE_PATH/webobject.inc");
require_once("$INCLUDE_PATH/site.inc");
require_once("$INCLUDE_PATH/page.inc");
require_once("$INCLUDE_PATH/file.inc");
require_once("$INCLUDE_PATH/access_group.inc");
#---------------------------------------------------------------------#

 ####################################################
# This is what you talk to to do various webby things
class Web extends Object {

	var $db;
	var $master_email = 'root';

	var $current_siteid = '';
	var $current_pageid = '';
	var $current_fileid = '';

	var $site_factory = array(); # Where sites are stored if being used
	var $page_factory = array(); # Where pages are stored if being used
	var $file_factory = array(); # Where files are stored if being used

	var $site_urls    = array(); # Where site urls are stored when get_site_url is called
	var $page_urls    = array(); # Where page urls are stored when get_page_url is called
	var $file_urls    = array(); # Where file urls are stored when get_file_url is called

	var $editable_sites;

	var $frontend;
	var $backend;

	var $extensions; # An array of extension objects.. created as needed

	var $dest_protocol; # URLs generated to sites, pages etc will use this protocol. Can be overridden to force a switch back to http from https for example.

	var $_backend_setup = false;  # a boolean specifiying whether the backend has been setup or not

	 ##############################
	# Constructor
	function Web () {
		$system_config = &get_system_config();

		 ##########################################
		# Set the webmaster's email address
		if (!$this->master_email = (($system_config->webmaster_email)?$system_config->webmaster_email:$_SERVER['SERVER_ADMIN'])) {
			$this->_set_error("Could not determine webmaster email address.",__FILE__,__LINE__);
		}

		$this->dest_protocol = url_protocol();

		# HELLO OPTIMISERS!!! This should only be called when things actually want to use it!
		$this->get_db();

		$this->determine_current_objects();

		 ############
		# Ponging
		$MYSOURCE_PING = $_REQUEST['MYSOURCE_PING'];
		if ($MYSOURCE_PING == "PING") {echo("PONG!"); exit(); }
		# and backwards compatiblilty
		if (!function_exists("ping_local_url")) {
			function ping_local_url($url) {return ping_mysource_url($url);}
		}

	}

	 #############################################################
	# Determine the current site, page and files ids based on
	# the global environment
	function determine_current_objects() {
		$siteid = $_REQUEST['siteid'];
		$s = $_REQUEST['s'];
		$pageid = $_REQUEST['pageid'];
		$p = $_REQUEST['p'];
		$fileid = $_REQUEST['fileid'];
		$f = $_REQUEST['f'];

		 #######
		# File?
		if(!$this->current_fileid) {
			if ($fileid) {
				$this->current_fileid = $fileid;
			} elseif ($f) { # A quick link to a file
				$this->current_fileid = $f;
			}
		}

		 #######
		# Page?
		if(!$this->current_pageid) {
			if ((int) $pageid) {
				$this->current_pageid = (int) $pageid;
			} elseif ((int) $p) { # A quick link to a page
				$this->current_pageid = (int) $p;
			} elseif ($this->current_fileid) {
				if(!$this->current_pageid = $this->db->single_element("SELECT pageid FROM file WHERE fileid='$this->current_fileid'")) {
					$this->current_fileid = "";
					$this->current_pageid = "";
				}
			}
		}


		 #######
		# Site?
		if(!$this->current_siteid) {
			if ((int) $siteid) {
				$this->current_siteid = (int) $siteid;
			} elseif ((int) $s) { # A quick link to a site
				$this->current_siteid = (int) $s;
			} elseif ($this->current_pageid) {
				if(!$this->current_siteid = $this->db->single_element("SELECT siteid FROM page WHERE pageid='$this->current_pageid'")) {
					$this->current_fileid = "";
					$this->current_pageid = "";
					$this->current_siteid = "";
				}
			}
		}
	}

	 ##################################################
	# Simply returns a reference to the web db
	function &get_db() {
		if(get_class($this->db) != "db") {
			 #############################################################
			# Open the connection to the database - using configuration
			$system_config = &get_system_config();
			$this->db = new Db(
				$system_config->web_db_details['name'],
				$system_config->web_db_details['login'],
				$system_config->web_db_details['password'],
				$system_config->web_db_details['host'],
				$system_config->web_db_details['querylog']
			);
			$this->db->set_error_call("report_error");

			# Validate all data coming out of the database to ensure it hasn't
			# been corrupted - if the data is in a multi-byte character set
			if(in_array("web",$system_config->check_db_mbstrings)) {
				$this->db->multibyte_string_protection_charset($system_config->default_charset);
			}

			$version = $this->db->server_version();
			if(version_no_compare($version, MYSOURCE_REQUIRED_MYSQL_VERSION) < 0) {
				if (!empty($version)) {
					report_error(__FILE__,__LINE__,MYSOURCE_LONG_NAME." requires MySQL Version ".MYSOURCE_REQUIRED_MYSQL_VERSION.". You may need to upgrade. Your web database's current version is ".$this->db->server_version().".");
					exit();
				} else {
					report_error(__FILE__,__LINE__,MYSOURCE_LONG_NAME." requires a MySQL connection. We are unable to connect to the web database. Please check the connection details in the mysource.conf file.");
				}
				exit();
			}
		}
		return $this->db;
	}


	 ##################################################
	# Simply returns a reference to the web db
	function &get_frontend() {
		global $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/frontend.inc");
		if(get_class($this->frontend) != "frontend") {
			$this->frontend = new Frontend($this);
		}
		return $this->frontend;
	}


	 ##################################################
	# Simply returns a reference to the web db
	function &get_backend() {
		if(get_class($this->backend) != "backend") {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/backend.inc");
			global $EDIT_DIR, $EDIT_PATH;
			$this->backend = new Backend($EDIT_DIR,$EDIT_PATH);
		}
		return $this->backend;
	}


	 ###################################################
	# Returns a reference to a particular site
	function &get_site($siteid=0) {
		if(!$siteid) $siteid = $this->current_siteid;
		if(!$siteid) return;
		if(!isset($this->site_factory[$siteid]) || get_class($this->site_factory[$siteid]) != "site") {
			$this->site_factory[$siteid] = new Site($siteid);
		}
		return $this->site_factory[$siteid];
	}


	 ###################################################
	# Returns a reference to a particular page
	function &get_page($pageid=0) {
		if(!$pageid) $pageid = $this->current_pageid;
		if(!$pageid) return;
		if(!isset($this->page_factory[$pageid]) || get_class($this->page_factory[$pageid]) != "page") {
			$this->page_factory[$pageid] = new Page($pageid);
		}
		return $this->page_factory[$pageid];
	}

	 ################################################
	# Returns a reference to a particular file
	function &get_file($fileid=0) {
		if(!$fileid) $fileid = $this->current_fileid;
		if(!$fileid) return;
		if(!isset($this->file_factory[$fileid]) || get_class($this->file_factory[$fileid]) != "file") {
			$this->file_factory[$fileid] = new File($fileid);
		}
		return $this->file_factory[$fileid];
	}


	 ######################################################
	# Clears up memory. Other code can call this if it
	# feels guilty but know its wont stuff any other code up
	# e.g. site duplication can load thousands of pages
	function forget_site($siteid=0) {
		if (empty($this->site_factory[$siteid])) return;
		unset($this->site_factory[$siteid]->web_system); # remove circular references before unsetting
		unset($this->site_factory[$siteid]);
	}
	function forget_page($pageid=0) {
		if (empty($this->page_factory[$pageid])) return;
		unset($this->page_factory[$pageid]->web_system); # remove circular references before unsetting
		unset($this->page_factory[$pageid]);
	}
	function forget_file($fileid=0) {
		if (empty($this->file_factory[$fileid])) return;
		unset($this->file_factory[$fileid]->web_system); # remove circular references before unsetting
		unset($this->file_factory[$fileid]);
	}

	 #####################################################
	# Someone should let us know if they're creating stuff
	function register_new_site($siteid=0,&$ref) {
		$this->site_factory[$siteid] = &$ref;
	}
	function register_new_page($pageid=0,&$ref) {
		$this->page_factory[$pageid] = &$ref;
	}
	function register_new_file($fileid=0,&$ref) {
		$this->file_factory[$fileid] = &$ref;
	}

	 #########################################################
	# Returns an id => name list of the general access groups
	function &get_general_access_group_list() {
		$session = &get_mysource_session();
		$general_access_group_list = $session->get_var('general_access_group_list');
		if(!isset($general_access_group_list)) {
			$db = &$this->get_db();
			$general_access_group_list = $db->associative_array("SELECT groupid, name FROM access_group WHERE siteid=0 ORDER BY name");
			$session->set_var('general_access_group_list',$general_access_group_list);
		}
		return $general_access_group_list;
	}

	/**
	* Returns an array of site access groups in the form siteid=>[sitename] groupname
	*
	* @access public
	* @returns array
	*/

	function &get_site_access_groups() {
		$sites = $this->get_editable_sites();
		$options = array();
		if (! empty($sites)) {
			foreach($sites as $id=>$name) {
				$site = $this->get_site($id);
				if (! empty($site->access_groups)) {
					foreach($site->access_groups as $groupid=>$gname) {
						$options+= array("$groupid"=>"[$name] $gname");
					}
				}
			}
		}
		return $options;
	}

	 #########################################################
	# Returns an id => name list of the site designs available
	function &get_site_design_list() {
		$session = &get_mysource_session();
		$site_design_list = $session->get_var('site_design_list');
		if(!isset($site_design_list)) {
			$db = &$this->get_db();
			$site_design_list = $db->associative_array("SELECT designid, name FROM site_design ORDER BY name");
			$session->set_var('site_design_list',$site_design_list);
		}
		return $site_design_list;
	}

	   #################################################################
	  # Handles a special ping request by other functions.
	 # This allows PHP to confirm that a certain URL includes this file.
	##################################################################
	# Pinging
	function ping_mysource_url($url) {
		if (!$f = fopen($url . "?MYSOURCE_PING=PING","r")) return 0;
		if (fgets($f,5) == "PONG!") return 1;
		else return "";
	}


	  ###################################################################
	 # Prints a html image tag
	function image_tag($src='', $rel='', $alt='', $width='', $height='', $extra='', $link='', $target='') {
		global $WEB_PATH;
		if (file_exists($src) && !is_dir($src)) {
			$no_extension = true;
		} else {
			$no_extension = false;
		}

		if(!file_exists($src.".".($ext="gif")) && !file_exists($src.".".($ext="jpg")) && !file_exists($src.".".($ext="png")) && !$no_extension) {
			$rel    = lib_href("images");
			$src    = "$WEB_PATH/__lib/images/blank";
			$width  = 1;
			$height = 1;
			$ext    = "gif";
		}
		$dir      = dirname($src);
		$filename = substr(strrchr($src,"/"), 1);

		if ($no_extension) {
			list($w,$h) = getimagesize("$src");
		} else {
			list($w,$h) = getimagesize("$src.$ext");
		}
		if (!$width)  $width  = $w;
		if (!$height) $height = $h;
		$alt     = ereg_replace("\"","&quot;",ereg_replace("[\n\r]+"," ",$alt));

		if ($no_extension) {
			$result = "<img src=\"$rel/$filename\" alt=\"$alt\"";
			if ($width > 0) $result .= " width=$width";
			if ($height > 0) $result .= " height=$height";
			$result .= " $extra>";
		} else {
			$result = "<img src=\"$rel/$filename.$ext\" alt=\"$alt\"";
			if ($width > 0) $result .= " width=$width";
			if ($height > 0) $result .= " height=$height";
			$result .= " $extra>";
		}

		if($link) {
			$result = "<a href=\"$link\"".(($target)?"target=\"$target\"":"").">$result</a>";
		}

		if ($caption = file_to_string("$src.cap")) {
			$result = "<table cellpadding=0 cellspacing=0 border=0><tr><td>$result</td><tr><td class=fineprint>$caption</td></tr></table>";
		}
		return $result;
	}

	 ######################################################################
	# Prints a multiple-select box all all the pages in the current site
	function page_multiple_combo_box($name='',$selected='',$extras='',$maxlength='',$height=20, $skip_safeedit=false) {
		return multiple_combo_box(
				$name,
				$this->page_array_with_sticks(0, 0, '', '', $skip_safeedit),
				$selected, "$extras class=data", $maxlength, $height
			);
	}

	function page_array_with_sticks($siteid=0,$pageid=0,$max_depth='',$stix='', $skip_safeedit=false,$spacing_string='&nbsp;') {
		if(!$pageid) $pageid = "0";
		if(!$siteid) $siteid = $this->current_siteid;
		if(!$siteid) return Array();

		$site = &$this->get_site($siteid);

		$page_index = &$site->get_page_index($siteid);

		$childids = $page_index[$pageid]['childids'];

		$result = array();
		if (!empty($childids)) {
			$countdown = count($childids);
			foreach($childids as $childid) {
				if (!($skip_safeedit && $page_index[$childid]['replaceid'])) {
					$countdown--;
					$result[$childid] = $stix.(($countdown)?"|-":"`-").$page_index[$childid]['name'];
				}
				if(!empty($page_index[$childid]['childids']) && ($max_depth === '' || $max_depth > 0)) {
					$newstix = $stix.(($countdown)?'|'.$spacing_string:$spacing_string.$spacing_string);
					$result = $result + $this->page_array_with_sticks($siteid,$childid,(($max_depth!='')?$max_depth--:''),$newstix, $skip_safeedit,$spacing_string);
				}
			}
		}
		return $result;
	}

	 #############################################################
	# Returns an array of pageids of templates of this type
	# Can restrict it to a site or sub-pages of a certain pageid
	function get_template_pages($template='',$siteid='',$pageid='') {
		if(!$template) return;

		if($pageid) {
			$page = &$this->get_page($pageid);
			$pageids = $page->get_all_subpageids($pageid);
		}

		$sql = "SELECT pageid, name
				FROM page
				WHERE 1=1 ".(($siteid)?"AND siteid=".$siteid:"")."
				".(($pageid)?"AND pageid IN ('".implode("','", $pageids)."')":"")."
				AND template = '$template'";

		return  $this->db->associative_array($sql);
	}

	 ###################################################################
	# Given an array of siteids, returns an array of site names for them
	function get_site_names($siteids='') {

		if (!$siteids) return Array();

		if (!is_array($siteids)) $siteids = Array($siteids);

		$sql = "SELECT DISTINCT siteid, name
				FROM site
				WHERE siteid IN ('".implode("','", $siteids)."')
				ORDER BY name";

		return $this->db->associative_array($sql);
	}


	 ###########################################################################################
	# Loads the bare information about all the organisations in the database into an tree array
	function &get_editable_sites($id=0) {
		$session = &get_mysource_session();
		if(!$id) $id = $session->user->id;
		if($id && !isset($this->editable_sites[$id])) {
			$this->load_editable_sites($id);
		}
		return $this->editable_sites[$id];
	}


	 ###################################################################
	# Given a userid, returns an array of all sites this person can edit
	function &load_editable_sites($id=0) {
		if(!$id) {
			$this->editable_sites[$id] = array();
		} elseif (superuser('web')) {
			$this->editable_sites[$id] = $this->db->associative_array("SELECT DISTINCT siteid, name FROM site  ORDER BY name");
		} else {
			$this->editable_sites[$id] = $this->db->associative_array("SELECT DISTINCT site.siteid, site.name FROM site_admin, site WHERE site_admin.siteid=site.siteid AND site_admin.userid='$id' ORDER BY site.name");
			$this->editable_sites[$id] = $this->editable_sites[$id] + $this->db->associative_array("SELECT DISTINCT site.siteid, site.name FROM site_editor, site WHERE site_editor.siteid=site.siteid AND site_editor.userid='$id' ORDER BY site.name");
			$this->editable_sites[$id] = $this->editable_sites[$id] + $this->db->associative_array("SELECT DISTINCT page.siteid, site.name FROM page_editor, page, site WHERE page_editor.pageid=page.pageid AND page.siteid=site.siteid AND page_editor.userid='$id' ORDER BY site.name");
			$this->editable_sites[$id] = $this->editable_sites[$id] + $this->db->associative_array("SELECT DISTINCT page.siteid, site.name FROM page_admin, page, site WHERE page_admin.pageid=page.pageid AND page.siteid=site.siteid AND page_admin.userid='$id' ORDER BY site.name");
		}
		reset($this->editable_sites[$id]);
		return $this->editable_sites[$id];
	}


	 ############################################################
	# Returns the current URL that the browser happens to be at
	function current_url() {
		return $_SERVER['HTTP_HOST'].str_replace("index.php", "", $_SERVER['PHP_SELF']);
	}

	 ############################################################
	# Returns the current URL that the browser happens to be at
	function set_dest_protocol($protocol='') {
		$this->dest_protocol = $protocol;
	}

	 ###############################################################################
	# Gets the URL of a particular site
	# Force secure is a flag, 0 - use default, 1 - use secure, 2 - use insecure
	function &get_site_url($siteid=0,$force_secure=0) {

		global $IN_BACKEND;
		$system_config = &get_system_config();
		$site = &$this->get_site($siteid);

		# Get the protocol
		if($force_secure == 1) $protocol = 'https';
		elseif($force_secure == 2) $protocol = 'http';
		else $protocol = $this->dest_protocol;

		# Have we already loaded a URL for this site and protocol
		if(!empty($this->site_urls[$siteid][$protocol])) return $this->site_urls[$siteid][$protocol];

		# Get the url we are going to work with, either the current url
		# of the current url minus the backend_suffix
		if ($IN_BACKEND) {
			$parts = explode($system_config->backend_suffix, $this->current_url());
			$start_url = $parts[0];
		} else {
			$start_url = $this->current_url();
		}

		# Find a site url that is the closest match to the current url
		$parts = explode('/', $start_url);
		while (!empty($parts)) {
			$candidate = implode('/', $parts);
			if (isset($site->urls[$candidate]) && ($site->urls[$candidate] == '' || $site->urls[$candidate] == $protocol)) {
				$url = $candidate;
				break;
			}
			$parts = array_slice($parts, 0, -1);
		}

		# Find a url with the correct protocol
		if(!$url) {
			foreach($site->urls as $u => $p) {
				if ($p == '' && $protocol == $p) {
					$url = $u;
					break;
				}
			}
		}

		# Return the first url
		if(!$url) {
			reset($site->urls);
			$url = key($site->urls);
			$protocol = current($site->urls);
			// if the protocol is blank for first url, default to http
			if ($protocol == '') $protocol = 'http';
		}

		return $this->site_urls[$siteid][$protocol] = $protocol.'://'.$url.'/';
	}

	 ###############################################################
	# Gets a relative link to a site based on the current Location
	function get_site_href($siteid=0) {
		return relative_href(url_protocol()."://".$this->current_url(),$this->get_site_url($siteid));
	}

	 ###################################
	# Gets the URL of a particular page
	# Force secure is a flag, 0 - use default, 1 - use secure, 2 - use insecure
	function &get_page_url($siteid=0,$pageid=0,$force_secure=0) {

		$system_config = &get_system_config();
		
		# Have we already loaded a URL for this page
		if($this->page_urls[$pageid] && !$force_secure) return $this->page_urls[$pageid];

		$site = &$this->get_site($siteid);
		$index = &$site->get_page_index();
		
		# If force secure not set then set it appropriately
		if(!$force_secure) {
			if($index[$pageid]['usessl']) $force_secure = 1;
			else $force_secure = 2;
		}

		# Get the site urls....with the correct protocol
		$url = $this->get_site_url($siteid,$force_secure);

		if(!$url || strpos($url, "?") !== FALSE) {
			$this->page_urls[$pageid] = $this->dest_protocol.'://'.$this->current_url()."?p=$pageid";

		} elseif(!$system_config->virtual_paths) {
			$this->page_urls[$pageid] = $url."?p=$pageid";

		} else {
			$site_index = &$site->get_page_index();
			$lineage = array($pageid);
			if (!empty($site_index[reset($lineage)]['parentid'])) {
				while($parentid = $site_index[reset($lineage)]['parentid']) array_unshift($lineage,$parentid);
			}
			$ext = "";
			if (!empty($lineage)) {
				foreach($lineage as $parentid) {
					$dir = "";

					if (!empty($site_index[$parentid]['dirs'])) {
						reset($site_index[$parentid]['dirs']);
						$dir = current($site_index[$parentid]['dirs']);
					}

					if($dir) {
						$ext .= (($ext) ? '/' : '').$dir;
					} else {
						$ext .= "?p=$pageid";
						break;
					}
				}
			}
			$this->page_urls[$pageid] = $url.$ext;
		}
		return $this->page_urls[$pageid];
	}

	 ###############################################################
	# Gets a relative link to a site based on the current Location
	function get_page_href($siteid=0,$pageid=0) {
		return relative_href(url_protocol()."://".$this->current_url(),$this->get_page_url($siteid,$pageid));
	}

	 ################################################
	# Make sure we're at the right url for this page
	function verify_page_url($pageid=0,$url='',&$leftovers) {
		# $leftovers is a reference to somewhere this function can
		# store the "leftovers" of the URL.
		# Say the current URL was http://www.blah.com/page/articles/5
		# but the page's URL is http://www.blah.com/page
		# then "articles/5" will be stored in the leftovers
		# The page template might want to use the leftovers to, say,
		# display a particular article.

		$page = &$this->get_page($pageid);
		$site = &$this->get_site($page->siteid);
		if(empty($site->urls)) return true; # Awww :( no choice I guess but to grudgingly accept it

		$full_url = ereg_replace("index\.php$","",$url); # Not everyone has virual paths you know
		$url = ereg_replace("/+$","",$full_url);

		# if this page is the site's index page, then there is special stuff we need to check
		if($page->id == $site->index_pageid) {
			# if the current url is in the sites url list don't redirect
			if (is_array($site->urls) && in_array($url,array_keys($site->urls))) {
				# backwards compatability ;), for the './[spf]=' things
				# add trailing slash to site roots that are sub-dirs of domains
				if (!ereg("/$", $full_url) && strstr($url, "/")) {
					return false;
				} else {
					return true;
				}#end if
			}#end if
		}#end if
		unset($full_url);
		$system_config = &get_system_config();
		if(!$system_config->virtual_paths) {
			return in_array($url,array_keys($site->urls));
		}
		$page = &$this->get_page($pageid);
		$segments = split("/+",$url);
		$site_index = &$site->get_page_index();
		$id = $pageid;

		//if($site_index[$id]['usessl'] && url_protocol() != 'https') return false;

		# if this page doesn't have any virtual paths, we just have to accept that
		if(empty($site_index[$id]['dirs'])) return true;

		for($seg = end($segments); $seg; $seg = prev($segments)) {
			if($id) {
				if(!in_array($seg,$site_index[$id]['dirs'])) {
					# Uh oh! Okay don't panic, maybe we're in the leftovers
					if($leftovers) $leftovers = "$seg/$leftovers";
					else           $leftovers = $seg;
				} else {
					$id = $site_index[$id]['parentid'];
				}
			} else {
				if(in_array($seg,array_keys($site->urls))) {
					return true;
				} else {
					while($prev_seg = prev($segments)) {
						if (in_array($seg = "$prev_seg/$seg",array_keys($site->urls))) {
							return true;
						}
					}
				}
			}
		}

		# Things aren't looking good today - we weren't in the leftovers
		$leftovers = "";
		# Maybe its are parental problem.
		while($id = $site_index[$id]['parentid']) {
			# One of the parents has no VP.. forgive all
			if(empty($site_index[$id]['dirs'])) return true;
		}
		return false;
	}

	 ###################################
	# Gets the URL of a particular file
	# Force secure is a flag, 0 - use default, 1 - use secure, 2 - use insecure
	function &get_file_url($siteid=0,$pageid=0,$fileid=0,$force_secure=0) {
		# Have we already loaded a URL for this file and protocol
		if($this->file_urls[$fileid] && !$force_secure) return $this->file_urls[$fileid];

		$site = &$this->get_site($siteid);
		$index = &$site->get_page_index();
		$file = &$this->get_file($fileid);
		$page = &$this->get_page($pageid);

		# If the page is encrypted then we should also encrypt the files
		if($index[$pageid]['usessl'] || url_protocol() == 'https') $force_secure = 1;

		# Yeh, a public file that doesn't need to be piped
		if ($page->effective_unrestricted() && $file->filename && $file->log_hits != 'Y') {
			$url = data_href("page/$page->id/$file->filename", $this->get_site_url($siteid,$force_secure));
		} else {
			$system_config = &get_system_config();
			if(!$system_config->virtual_paths) {
				$url = $this->get_site_url($siteid,$force_secure).'/?f='.$fileid;
			} else {
				$url  = $this->get_page_url($siteid,$pageid,$force_secure);
				#Hmm, no url well let's just
				if(!$url) $url = ((strstr($this->current_url(), ".php")) ? dirname($this->current_url()) : $this->current_url())."/?f=$fileid";
				else if (strstr($url, "?"))   $url .= "&f=$fileid";
				elseif ($file->filename) $url .= "/$file->filename";
				else $url .= "/?f=$fileid";
			}
		}
		$this->file_urls[$fileid] = $url;

		return $this->file_urls[$fileid];
	}

	 ###############################################################
	# Gets a relative link to a file based on the current Location
	function get_file_href($siteid=0,$pageid=0,$fileid=0) {
		# note we do not use the dest_protocol here because file hrefs
		# are most likely to be embedded in the current page, which
		# should be consistant
		return relative_href(url_protocol()."://".$this->current_url(),$this->get_file_url($siteid,$pageid,$fileid));
	}


	 ##########################################
	# Returns a URL that points to the backend
	function get_backend_url() {

		$siteid = $this->current_siteid;
		if(!$siteid) {
			$siteid = $this->db->single_element("SELECT siteid FROM site LIMIT 1");
		}
		$system_config = &get_system_config();
		return ereg_replace("\?.*$","",$this->get_site_url($siteid))."$system_config->backend_suffix";
	}

	 ################################################
	# Make sure we're at the right url for this file
	function verify_file_url($fileid=0,$url='') {
		$url = ereg_replace("/$","",$url);
		$segments = split("/+",$url);
		$file = &$this->get_file($fileid);
		$page = &$this->get_page($file->pageid);
		$site = &$this->get_site($page->siteid);
		$site_index = &$site->get_page_index();
		$filename = substr(strrchr($url,"/"),1);
		return($filename == $file->filename && $this->verify_page_url($page->id,substr($url,0,-strlen($filename)-1)));
	}

	 #######################################################################
	# URL registration. Sites, pages and files can register themselves
	function register_url($url='',$info='') {
		if($this->db->single_element("SELECT url FROM url_lookup WHERE url='".addslashes($url)."'")) {
			return false;
		}
		return $this->db->insert("INSERT INTO url_lookup (url,info) VALUES ('".addslashes($url)."','".addslashes(serialize($info))."')");
	}

	 #######################################################################
	# URL registration. Sites, pages and files can register themselves
	function unregister_url($url='') {
		if(!$this->db->single_element("SELECT url FROM url_lookup WHERE url='".addslashes($url)."'")) {
			return false;
		}
		return $this->db->delete("DELETE FROM url_lookup WHERE url='".addslashes($url)."'");
	}


	 #####################################################
	# Grabs the information, if any, for a particular URL
	# Returns an empty array if URL is registered but blank
	# returns null if the URL is not registered
	function get_url_info($url='') {
		return unserialize($this->db->single_element('SELECT info FROM url_lookup WHERE url=\''.addslashes($url).'\''));
	}

	 ####################################################################
	# Takes a new URL for a site and sets up all the URLS for its pages and
	# files etc
	function register_site_url($siteid=0,$new_url='') {
		$site = &$this->get_site($siteid);
		if(!$this->register_url($new_url,array("s"=>(int)$siteid))) return false;
		# Let the pages take care of 'emselves
		$top_pageids = $site->get_top_pageids();
		if (!is_array($top_pageids)) $top_pageids = array();
		$paths = array($new_url);
		if (!empty($top_pageids)) {
			foreach($top_pageids as $top_pageid) {
				$this->new_url_paths_to_page($siteid,$top_pageid,$paths);
			}
		}
		return true;
	}

	 ####################################################################
	# Takes a old URL for a site and removes up all the URLS for its pages and
	# files etc
	function unregister_site_url($siteid=0,$lost_url='') {
		$site = &$this->get_site($siteid);
		$this->unregister_url($lost_url);
		# Let the pages take care of 'emselves
		$top_pageids = $site->get_top_pageids();
		if (!is_array($top_pageids)) $top_pageids = array();
		$paths = array($lost_url);
		if (!empty($top_pageids)) {
			foreach($top_pageids as $top_pageid) {
				$this->lost_url_paths_to_page($siteid,$top_pageid,$paths);
			}
		}
		return true;
	}

	 #####################################################################################
	# This takes a pageid and ensures that all its deserved URLs are set up for a new dir
	function register_page_dir($siteid=0,$pageid=0,$new_dir='') {
		$site = &$this->get_site($siteid);
		$site_index = &$site->get_page_index();
		if(!$site_index[$pageid]) return;
		$paths = $this->get_url_paths_to_page($siteid,$pageid);
		if (!is_array($paths)) $paths = array();
		# Paths now contains all the URLs theoretically pointing to the page's parent page
		# Register our page with the new dir
		if (!empty($paths)) {
			foreach($paths as $i => $path) {
				$paths[$i] = "$path/$new_dir";
				if (!$this->register_url("$path/$new_dir",array("p"=>(int)$pageid,"s"=>(int)$siteid))) {
					foreach($paths as $j => $path) {
						$this->unregister_url("$path/$new_dir");
						if($i == $j) break;
					}
					return false;
				}
			}
		}
		# And now do all the children
		if (!empty($site_index[$pageid]['childids'])) {
			foreach($site_index[$pageid]['childids'] as $childid) {
				$this->new_url_paths_to_page($siteid,$childid,&$paths);
			}
		}
		# And files
		$db = &$this->get_db();
		$files = $db->associative_array("SELECT fileid, filename FROM file WHERE pageid='$pageid' ORDER BY orderno");
		if (!empty($files) && !empty($paths)) {
			foreach($files as $fileid => $filename) {
				foreach($paths as $path) {
					$this->register_url("$path/$filename",array("f"=>(int)$fileid,"p"=>(int)$pageid,"s"=>(int)$siteid));
				}
			}
		}
		return true;
	}

	 #########################################################################
	# This takes a pageid and ensures that all its URLs are removed for a dir
	function unregister_page_dir($siteid=0,$pageid=0,$lost_dir='') {
		$site = &$this->get_site($siteid);
		$site_index = &$site->get_page_index();
		if(!$site_index[$pageid]) return;
		$paths = $this->get_url_paths_to_page($siteid,$pageid);
		if (!is_array($paths)) $paths = array();
		# Paths now contains all the URLs theoretically pointing to the page's parent page
		# Unregister our dir
		if (!empty($paths)) {
			foreach($paths as $i => $path) {
				$paths[$i] = "$path/$lost_dir";
				$this->unregister_url("$path/$lost_dir");
			}
		}
		# And now do all the children
		if (!empty($site_index[$pageid]['childids'])) {
			foreach($site_index[$pageid]['childids'] as $childid) {
				$this->lost_url_paths_to_page($siteid,$childid,&$paths);
			}
		}
		# And files
		$db = &$this->get_db();
		$files = $db->associative_array("SELECT fileid, filename FROM file WHERE pageid='$pageid' ORDER BY orderno");
		if (!empty($files) && !empty($paths)) {
			foreach($files as $fileid => $filename) {
				foreach($paths as $path) {
					$this->unregister_url("$path/$filename",array("f"=>$fileid,"p"=>$pageid,"s"=>$site->id));
				}
			}
		}
		return true;
	}


	 #####################################################
	# Registers a filename as a url for a particular file
	function register_file_name($siteid=0,$pageid=0,$fileid=0,$new_filename='') {
		$paths = $this->get_url_paths_of_page($siteid,$pageid);
		if (!is_array($paths)) $paths = array();
		if (!empty($paths)) {
			foreach($paths as $path) {
				$this->register_url("$path/$new_filename",array("s"=>$siteid,"p"=>$pageid,"f"=>$fileid));
			}
		}
		return true;
	}

	 #####################################################
	# Unregisters a filename as a url for a particular file
	function unregister_file_name($siteid=0,$pageid=0,$fileid=0,$lost_filename='') {
		$paths = $this->get_url_paths_of_page($siteid,$pageid);
		if (!is_array($paths)) $paths = array();
		if (!empty($paths)) {
			foreach($paths as $path) {
				$this->unregister_url("$path/$lost_filename");
			}
		}
		return true;
	}

	 ##############################################################
	# Returns an array paths that point to the parent of this page
	function get_url_paths_to_page($siteid=0,$pageid=0) {
		$site = &$this->get_site($siteid);
		$site_index = &$site->get_page_index();
		if(!$site_index[$pageid]) return;
		# Start with the site URLs
		$arr = array();
		$arr[] = array_keys($site->urls);
		# Go through the page lineage
		$lineage = array($site_index[$pageid]['parentid']);
		while($parentid = $site_index[reset($lineage)]['parentid']) array_unshift($lineage,$parentid);
		if (!empty($lineage)) {
			foreach($lineage as $id) {
				if(!$id) break;
				$arr[] = &$site_index[$id]['dirs'];
			}
		}
		$paths = array(""); # Start off with a blank string
		if (!empty($arr)) {
			foreach($arr as $i => $dirs) {
				$old_paths = $paths;
				$paths = array();
				foreach($dirs as $dir) {
					foreach($old_paths as $old_path) {
						$paths[] = (($old_path)?"$old_path/$dir":$dir);
					}
				}
			}
		}
		return $paths;
	}

	 #########################################
	# Like above but includes the page itself.
	function get_url_paths_of_page($siteid=0,$pageid=0) {
		$site = &$this->get_site($siteid);
		$site_index = &$site->get_page_index();
		if(!$site_index[$pageid]) return;
		$parent_paths = $this->get_url_paths_to_page($siteid,$pageid);
		if (!is_array($parent_paths)) $parent_paths = array();
		$paths = array();

		if (!empty($site_index[$pageid]['dirs']) && !empty($parent_paths)) {
			foreach($site_index[$pageid]['dirs'] as $dir) {
				foreach($parent_paths as $path) {
					$paths[] = "$path/$dir";
				}
			}
		}

		return $paths;
	}

	 #######################################################################
	# Tells a page that a set of new URLs points to its parent, and that it too could
	# make use of these wonderful new URLs to be seen in
	function new_url_paths_to_page($siteid=0,$pageid=0,&$paths) {
		$site = &$this->get_site($siteid);
		$site_index = &$site->get_page_index();
		if(!$site_index[$pageid]) return;
		$db = &$this->get_db();
		# Alrighty then, lets do all the dirs for this page
		$new_paths = array();
		if (!empty($site_index[$pageid]['dirs']) && !empty($paths)) {
			foreach($site_index[$pageid]['dirs'] as $dir) {
				foreach($paths as $path) {
					$this->register_url($new_path = "$path/$dir",array("p"=>(int)$pageid,"s"=>(int)$siteid));
					$new_paths[] = $new_path;
				}
			}
		}
		# Pass the news onto the kids
		if (!empty($site_index[$pageid]['childids'])) {
			foreach($site_index[$pageid]['childids'] as $childid) {
				$this->new_url_paths_to_page($siteid,$childid,&$new_paths);
			}
		}
		# And the file attachments
		$files = $db->associative_array("SELECT fileid, filename FROM file WHERE pageid='$pageid' ORDER BY orderno");
		if (!empty($files) && !empty($new_paths)) {
			foreach($files as $fileid => $filename) {
				foreach($new_paths as $new_path) {
					$this->register_url("$new_path/$filename",array("f"=>(int)$fileid,"p"=>(int)$pageid,"s"=>(int)$siteid));
				}
			}
		}
	}


	 #######################################################################
	# Tells a page that a set of new URLs no longer point to its parent
	# and that it can no longers make use of them
	function lost_url_paths_to_page($siteid=0,$pageid=0,&$paths) {
		$site = &$this->get_site($siteid);
		$site_index = &$site->get_page_index();
		if(!$site_index[$pageid]) return;
		$db = &$this->get_db();
		# Alrighty then, lets do all the dirs for this page
		$new_paths = array();
		if (!empty($site_index[$pageid]['dirs']) && !empty($paths)) {
			foreach($site_index[$pageid]['dirs'] as $dir) {
				foreach($paths as $path) {
					$this->unregister_url($new_path = "$path/$dir");
					$new_paths[] = $new_path;
				}
			}
		}
		# Pass the news onto the kids
		if (!empty($site_index[$pageid]['childids'])) {
			foreach($site_index[$pageid]['childids'] as $childid) {
				$this->lost_url_paths_to_page($siteid,$childid,&$new_paths);
			}
		}
		# And the file attachments
		$files = $db->associative_array("SELECT fileid, filename FROM file WHERE pageid='$pageid' ORDER BY orderno");
		if (!empty($files) && !empty($new_paths)) {
			foreach($files as $fileid => $filename) {
				foreach($new_paths as $new_path) {
					$this->unregister_url("$new_path/$filename",array("f"=>$fileid,"p"=>$pageid,"s"=>$siteid));
				}
			}
		}
	}

	 ###############################################
	# Returns a reference to a web extension object
	function &get_extension($extension='') {
		if(!$extension) return;
		$class_name = "web_extension_$extension";
		if(!isset($this->extensions[$extension])) {
			global $XTRAS_PATH;
			include_once("$XTRAS_PATH/web/extensions/$extension/$extension.inc");
			$this->extensions[$extension] = new $class_name();
		}
		return $this->extensions[$extension];
	}


	 #####################################################
	# Looks up the URL lookup table and sets environment
	# variables accordingly
	function extract_url_info_to_environment() {
		$system_config = &get_system_config();
		$url = preg_replace("/\/+$/",'',$this->current_url());
		if($system_config->strict_url_matching) {
			$info = $this->get_url_info($url);
		} else {
			while(!$info && strlen(trim($url)) > 1) {
				$info = $this->get_url_info($url);
				$url = dirname($url);
			}
		}
		if (is_array($info)) {
			foreach($info as $k => $v) { # Set these globals. Remember, globals already set (GET, POST, COOKIE) have preference
				$_REQUEST[$k] = ($_REQUEST[$k]) ? $_REQUEST[$k] : $v;
			}
		}
		return $url;
	}


	 ##########################################################
	# Print just gets the frontend class to do thwork for now
	function print_frontend() {
		$system_config = &get_system_config();
		$db = &$this->get_db();

		$this->determine_current_objects();

		 #####################################################################
		# Let's try to determine some information from the current URL
		if(!$this->current_siteid && !$this->current_pageid) {
			$url = $this->extract_url_info_to_environment();
		}
		$this->determine_current_objects();

		if(!$this->current_siteid) {
			# Page not found...lets load the site so we can print its 404 page when appropriate
			while(!$this->current_siteid && strlen(trim($url)) > 1) {
				# We do the strlen in the above while condtion
				# because, unfortunately strlen's result isn't 
				# consistant. Sometimes it ends up as a . sometimes a /
				# and others a blank string
				$this->current_siteid = $db->single_element("SELECT siteid FROM site_url WHERE url='".addslashes($url)."'");
				$url = dirname($url);
			}
		} elseif(!$this->current_pageid) $this->current_pageid = $db->single_element("SELECT index_pageid FROM site WHERE siteid='".addslashes($this->current_siteid)."'");

		$frontend = &$this->get_frontend();
		$frontend->paint();
	}


	 ##################################################
	# Returns the HREF to the backend for this object
	function get_backend_href() {
		global $EDIT_DIR;
		return "$EDIT_DIR/web.php?";
	}

	 ################################################################################
	# Sets up a backend object for webby objects to use while doing their backends !
	function &setup_backend() {
		$backend = &$this->get_backend();
		if (!$this->_backend_setup) {
			$backend->set_active_menu_item("web");
			$backend->set_hidden_field("extension_edit","");
			$this->_backend_setup = true;
		}
		return $backend;
	}



	 ##########################################################
	# Prints the backend for the web system
	function print_backend() {
		global $XTRAS, $INCLUDE_PATH;

		$system_config = &get_system_config();
		$session = &get_mysource_session();

		 ###################################
		# Setup the backend a little
		$backend = &$this->setup_backend(); # Backend refrence
		$backend->set_title("$system_config->system_name - Web Backend");

		 ####################################################################
		# SECUIRTY - Only let in those who have permission to work on a site
		if (!$session->logged_in()) {
			$session->login_screen($backend->title,"You must be logged in and have permission to edit <i>$system_config->system_name</i> sites in order to proceed.");
		}

		$editable_sites = &$this->get_editable_sites();

		if (empty($editable_sites) && !superuser("web")) {
			$session->login_screen($backend->title,"You must have permission to edit <i>$system_config->system_name</i> sites in order to proceed.",$session->user->login);
		}

		 ##########################################################
		# Display the edit interface for the extension if asked for
		$extension_edit = $_GET['extension_edit'];
		$web_section = $_REQUEST['web_section'];

		if($extension_edit) {
			$extension = &$this->get_extension($extension_edit);
			$extension->print_backend();
			return;
		}

		$backend->set_heading("Web System","web");
		$backend->set_subheading($system_config->system_name);
		$backend->set_hidden_field("siteid");
		$backend->set_hidden_field("action");
		$backend->set_hidden_field("web_section",$web_section);

		 ##########################################
		# Set up the tabs for the main interface
		$backend->set_tab("sites","web.php","Sites","View the list of available sites","site");

		$general_groups = &$this->get_general_access_group_list();
		if(superuser("web") || !empty($general_groups)) {
			$backend->set_tab("web_security","web.php?web_section=web_security","Security","Manage global web access groups","security");
		}

		if (superuser("web")) {
			$backend->set_tab("web_designs","web.php?web_section=web_designs","Designs","View the list of available site designs","page");
			$backend->set_tab("wizards","web.php?web_section=wizards","Wizards","View the list of available web system wizards","graph");
		}

		$backend->set_active_tab(($web_section)?$web_section:"sites");

		$action = $_POST['action'];
		switch($web_section) {
		  ############
		 # SECURITY #
		############
		case "web_security":
			if($action == "New General Group") {
				if (superuser("web")) {
					$new_general_access_group_name = gpc_stripslashes($_POST['new_general_access_group_name']);
					$group = new Access_Group(0, $this);
					$backend->add_message($group->create($new_general_access_group_name));
					$session->unset_var('general_access_group_list');
					if($group->id) {
						$backend->set_relocation("access_group.php?groupid=$group->id");
					}
				} else {
					$backend->add_message("Permission denied.");
				}
			}

			$backend->print_header();

			 #######################
			# List general groups
			$general_groups = &$this->get_general_access_group_list();

			if(superuser('web') || !empty($general_groups)) {

				 ########################################
				# Section where we manage access groups!
				$backend->open_section('Read Access');

				$backend->open_field('Gen. Access Groups:','top');

				if (is_array($general_groups) && !empty($general_groups)) {
					?>
					<table cellspacing=2 cellpadding=2 border=0>
						<tr>
							<td class=data><b>ID</td>
							<td class=data><b>Access Group</td>
						</tr>
					<?
					foreach($general_groups as $groupid => $name) {
						?>
						<tr bgcolor=#d0d0d0>
							<td class=data align=right><?=$groupid?></td>
							<td class=data><a href="access_group.php?groupid=<?=$groupid?>" class=data><?=$name?></a></td>
						</tr>
						<?
					}
					?></table><?
				} else {
			}

				 ######################################
				# Allow the creation of a new group
				if (superuser('web')) {
					$backend->open_field('New General Group:');
					echo text_box('new_general_access_group_name','',20,255,'class=data');
					$backend->print_commit_button("Commit","if (document.edit.new_general_access_group_name.value.length > 0 && confirm('Make a new access group called '+document.edit.new_general_access_group_name.value+'?')) {document.edit.action.value = 'New General Group';document.edit.submit();}");
				}
			}
			$backend->print_footer();
			break;

		  ###########
		 # WIZARDS #
		###########
		case "wizards":
			# Don't wanna be commitin' nothin'!
			$backend->set_hidden_field('action','');

			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/wizard.inc");
			$wizard = new Wizard($this);
			$type_set = $wizard->type_set();
			if (!$type_set) $backend->print_header();
			$wizard->print_backend();
			if (!$type_set) $backend->print_footer();

			return;


		  ############
		 # DESIGNS  #
		############
		case "web_designs":
			if($action == 'New Site Design') {
				if (superuser('web')) {
					include_once("$INCLUDE_PATH/site_design.inc");
					$site_design =& new Site_Design(0);
					$backend->add_message($site_design->create(gpc_stripslashes($_REQUEST['new_site_design_name'])));
					if($site_design->id > 0) {
						$backend->set_relocation("site_design.php?designid=$site_design->id");
					}
				} else {
					$backend->add_message('Permission denied.');
				}
			}

			$backend->print_header();

			 ########################################
			# Section where we manage site designs!
			if (superuser('web')) {

				$backend->open_section('Site Designs');

				 #######################
				# List general groups
				$backend->open_field('Existing Designs:','top');

				$sql = 'SELECT designid, name, UNIX_TIMESTAMP(last_modified) as last_modified, modifierid FROM site_design ORDER BY name';
				$site_designs = $this->db->associative_array($sql);
				if (empty($site_designs)) {
					echo 'None';
				} else {
					?>
					<table cellspacing=2 cellpadding=2 border=0>
						<tr>
							<td class=data><b>ID</td>
							<td class=data><b>Design</td>
							<td class=data><b>Last Modified</td>
							<td class=data><b>By</td>
						</tr>
					<?

					$ids = Array();
					foreach($site_designs as $data) {
						if (!in_array($data['modifierid'], $ids)) $ids[] = $data['modifierid'];
					}
					$sql = "SELECT userid, concat(firstname,' ',surname) AS name, email
							FROM user
							WHERE userid IN ('".implode("','", $ids)."')";
					$users_system = &get_users_system();
					$modifier_info = $users_system->db->associative_array($sql);

					foreach($site_designs as $designid => $data) {
						?>
						<tr bgcolor=#d0d0d0>
							<td class=data>
								<a href="site_design.php?designid=<?=$designid?>"><?=$designid?></a>
							</td>
							<td class=data>
								<a href="site_design.php?designid=<?=$designid?>"><?=$data['name']?></a>
							</td>
							<td class=data>
								<?=readable_datetime($data['last_modified'])?>
							</td>
							<td class=data>
								<a href="mailto:<?=$modifier_info[$data['modifierid']]['email']?>"><?=$modifier_info[$data['modifierid']]['name']?></a>
							</td>
						</tr>
						<?
					}

					?></table><?
				}#end if

				 ######################################
				# Allow the creation of a new design
				$backend->open_field('Create New Design:');
				echo text_box('new_site_design_name','',20,255,'class=data');

				$backend->print_commit_button("Commit","if (document.edit.new_site_design_name.value.length > 0 && confirm('Make a new site design called '+document.edit.new_site_design_name.value+'?')) {document.edit.action.value = 'New Site Design';document.edit.submit();}");
			}

			$backend->print_footer();
			break;

		  ############
		 # SITELIST #
		############
		default:
			if($action == 'Create Site') {
				if (superuser('web')) {
					$name = gpc_stripslashes($_REQUEST['name']);
					$designid = $_REQUEST['designid'];
					$new_site = new Site(0,$this);
					$backend->add_message($new_site->create($name,$designid));
					if ($new_site->id) {
						$this->current_siteid = $new_site->id;
						$this->editable_sites[$this->current_siteid] = $name;
						$backend->set_relocation("site.php?s=$new_site->id");
					}
				} else {
					$backend->add_message('Permission denied.');
				}
			}

			$backend->print_header();

			 ########################################
			# Section where we manage our sites
			$backend->open_section('Sites');

			 ########################################
			# Field for creating new sites
			if (superuser('web')) {
				$backend->open_field('Create New Site:', 'one_liner');
				$designs = &$this->get_site_design_list();

				# if there are designs then let them create a site
				if ($designs) {
					echo text_box('name','',25,128,'class=data');
					echo combo_box('designid',$designs,'','class=data');
					echo '&nbsp;';
					$backend->print_icon_button("javascript: document.edit.action.value='Create Site';document.edit.submit();","Create New Site","create");

				# else no designs how the hell are we supposed to create a site
				} else {
					echo "Unable to create a new site without any site designs";
				}#end if
			}

			 ########################################
			# The list of current sites
			$backend->open_field('Sites:','top');

			$siteids     = array_keys($editable_sites);
			$sites       = $this->db->associative_array("SELECT siteid, name, public, description FROM site WHERE siteid IN ('".implode("','",$siteids)."') ORDER BY name");
			$page_counts = $this->db->associative_array("SELECT siteid, count(*) from page WHERE siteid IN ('".implode("','",$siteids)."') GROUP BY siteid");
			?>
			<table cellspacing=2 cellpadding=2 border=0>
				<tr>
					<td class=data>&nbsp;</td>
					<td class=data><b>ID</td>
					<td class=data><b>Site</td>
					<td class=data><b>Pages</td>
					<td class=data>&nbsp;</td>
				</tr>
			<?
				
			if (is_array($sites) && !empty($sites)) {
				foreach($sites as $siteid => $site) {
					$_site = &$this->get_site($siteid);
					?>
					<tr bgcolor=#d0d0d0>
						<td class=data>
							<img src="images/<? echo (($site['public'])?"tick":"cross") ?>.gif" alt="<? echo (($site['public'])?"Public":"Private") ?>" width=15 height=15>
						</td>
						<td class=data align=right><?=$siteid?></td>
						<td class=data>
							<?
							$desc = ereg_replace("'","\\'", htmlspecialchars("$site[name] - $site[description]"));
							$desc = ereg_replace("[\n\r]+"," ",$desc);
							echo "<a href=\"site.php?siteid=$siteid\" onmouseover=\"window.status='Edit site? $desc';return true;\" onmouseout=\"window.status=''; return true;\">$site[name]</a>";
							?>
						</td>
						<td class=data align=right><?=((int)$page_counts[$siteid])?></td>
						<?
							$protocol = key($_site->urls);
							if(!$protocol = current($_site->urls)) $protocol = url_protocol(); #default
						?>
						<td class=data align=center>&nbsp;<?=(key($_site->urls)) ? "<a href='".$protocol.'://'.key($_site->urls)."' target='_blank'>[preview]</a>" : '' ?>&nbsp;</td>
					</tr>
					<?
				}
			} else {
				?>
					<tr bgcolor=#d0d0d0>
						<td class=data colspan="5">
							&nbsp;None.
						</td>
					</tr>
				<?
			}
			?></table><?
			$backend->print_footer();
			break;
		}


	}


	 ###############################################################
	# Set the current pageid so everything knows what it is
	function set_current_pageid($pageid=0) {

		$this->current_pageid = $pageid;
		# if there isn't a siteid then set it from the page
		if (!$this->current_siteid) {
			$page = &$this->get_page();
			if ($page->id) {
				$this->current_siteid = $page->siteid;
			}#end if
		}#end if

	}#end set_current_pageid()

	 ##############################################################
	# Prints out the necessary files and html to use the tooltips
	function initialise_tooltips() {
		if ($this->tooltips_initialised) return;
		?>
		<script language=javascript src="<?=squizlib_href('js','detect.js');?>"></script>
		<script language=javascript src="<?=squizlib_href('js','layer_handler.js');?>"></script>
		<script language=javascript src="<?=squizlib_href('js','tooltips.js');?>"></script>
		<!-- Begin Tooltip -->
		<style type="text/css">
			#ToolTipDiv {
				position:absolute;
				width:  1px;
				height: 1px;
				visibility: hidden;
			}
		</style>
		<div id="ToolTipDiv">
		</div>
		<!-- End Tooltip -->
		<?
		$this->tooltips_initialised = true;

	}#end _initialise_tooltips()

}#end class Web

?>
