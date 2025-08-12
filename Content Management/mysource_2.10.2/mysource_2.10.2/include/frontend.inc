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
## File: include/frontend.inc
## Desc: A class that handles printing the frontend out.. a breakaway part of the web class.
## $Source: /home/cvsroot/mysource/include/frontend.inc,v $
## $Revision: 2.10 $
## $Author: csmith $
## $Date: 2003/08/18 22:33:30 $
#######################################################################
include_once("$INCLUDE_PATH/html_general.inc");
include_once("$INCLUDE_PATH/html_image.inc");
include_once("$INCLUDE_PATH/html_form.inc");
#---------------------------------------------------------------------#


 ##############################################################
# This is what you talk to to do various frontend webby things
class Frontend extends WebObject {
	
	 ##############################
	# Constructor
	function Frontend () {
		WebObject::WebObject();
	}


	 ################################
	# Handy little wrappers
	function &get_site($siteid=0) {
		return $this->web_system->get_site($siteid);
	}
	function &get_page($pageid=0) {
		return $this->web_system->get_page($pageid);
	}



	 #########################################################
	# Print this if for some reason we don't know what to do
	function page_not_found() {
		$system_config = &get_system_config();
		$session = &get_mysource_session();
		# log the broken link if we have logging turned
		if ($system_config->log_visitors) {
			$db = &$this->get_db();
			$pageid = 0;
			if ($this->web_system->current_pageid > 0) $pageid = $this->web_system->current_pageid;
			$db->insert("INSERT INTO log_page_not_found (pageid,sessionid,hit_time,userid,referer) VALUES('$pageid', '".addslashes(session_id())."', now(), '".$session->user->id."','".addslashes($_SERVER['HTTP_REFERER'])."')");
		}

		$site = &$this->get_site();
		if($site->id && $site->not_found_pageid) {
			$page = &$this->get_page($site->not_found_pageid);
			if($page->id) {
				$this->web_system->set_current_pageid($page->id);
				$page->print_frontend();
				return;
			}
		}

		if (!PHP_CGI) {
			header("HTTP/1.0 404 Not Found");
		}

		?>
		<html>
			<head>
				<title>Page Not Found</title>
				<style type="text/css">
					.one {
						font-family: arial, verdana, sans-serif; 
						font-size: 60px; 
						font-weight: bold; 
						color: #dddddd;
					}
					.two {
						font-family: arial, verdana, sans-serif; 
						font-size: 30px; 
						font-weight: bold; 
						color: #eeeeee;
					}
				</style>
			</head>
			<body bgcolor="#ffffff">
			<table width="100%" height="100%">
				<tr>
					<td align="center" class="one">Page Not Found</td>
				</tr>
				<tr>
					<td align="center" class="two">MySource</td>
				</tr>
			</table>
			<!-- 
				Add some padding text so that IE actually prints out our page not found page
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, 
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, 
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, 
			-->
			</body>
		</html>
		<?
	}



	 ######################################################################
	# Sends an image or a CSS file etc from various places in the system
	function send_file($type='', $file='') {

		 ######################################################
		# Has this one already been approved for this session?

		 #############################
		# No funny business!
		$file = preg_replace("/\.*\//",'/',$file);

		global $XTRAS_PATH, $WEB_PATH, $SQUIZLIB_PATH;

		$server_protocol = $_SERVER['SERVER_PROTOCOL'];
		$query_string = $_SERVER['QUERY_STRING'];

		$session = &get_mysource_session();

		if(!$files_sent = &$session->get_var('files_sent')) {
			$files_sent = array();
			$session->set_var('files_sent',$files_sent);
		}
		if($session->logged_in()) {
			$files_sent_user = &$files_sent[$session->user->id];
		} else {
			$files_sent_user = &$files_sent[0];
		}

		 ##################################
		# Check the browser cache
		if(!$cached_file = &$files_sent_user[$query_string]) {

			switch ($type) {
				case 'lib':
					$file = "$WEB_PATH/__lib/$file";
					break;
				case 'squizlib':
					$file = "$SQUIZLIB_PATH/$file";
					break;
				case 'system' :
				case 'web'    :
				case 'users'  :
					# in general if this is getting called then the file is restricted
					$file = get_data_path(false, "$type/$file");
					break;
				case 'user':
					$userid = $_GET['userid'];
					$file = get_data_path(false, "$type/$userid/$file");
					break;
				case 'site':
					$site = &$this->get_site();
					$file = "$site->data_path/$file";
					break;
				case 'site_design':
					$site = &$this->get_site();
					$designid = $_REQUEST['designid'];
					# if there ain't a designid or it isn't an allowed design for this site
					# use the default design
					if (!$designid || !in_array($designid, $site->allowed_designids)) {
						$designid = $site->designid;
					}
					# in general if this is getting called then the file is restricted
					$file = get_data_path(false, "site/design/$designid/images/$file");
					break;
				case 'site_design_edit':
					$designid = $_REQUEST['designid'];
					# in general if this is getting called then the file is restricted
					$file = get_data_path(false, "site/design/$designid/images/$file");
					break;
				case 'page':
					$page = &$this->get_page();
					$file = "$page->data_path/$file";
					break;
				case 'page_template':
					$page = &$this->get_page();
					$file = "$XTRAS_PATH/page/templates/$page->template/images/$file";
					break;
				default:
					header("$server_protocol 401 Type Unknown");
					report_error(__FILE__,__LINE__,"File type unknown: $type");
					exit();
					break;
			}

		} else $file = &$cached_file;

		if (!($mtime = filemtime($file))) {
			if (!PHP_CGI) {
				header("$server_protocol 404 File not found");
			}
			$this->_set_error("File not found: $file",__FILE__,__LINE__);
			exit();
			break;
		}
		$since_time = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
		$since_time = (($since_time)?strtotime($since_time):0);
		if($since_time >= $mtime) {
			if (!PHP_CGI) {
				header("$server_protocol 304 Not Modified");
			}
			header('Expires: '. gmdate('D, d M Y H:i:s',time()+3600*3) . ' GMT');
			exit();
		}

		 ###################################################
		# Act depending on what the file is associated with
		switch ($type) {
			case 'lib': case 'squizlib': 
				$files_sent_user[$query_string] = $file;
				send_cacheable_file($file);
				break;
			case 'system' :
			case 'web'    :
			case 'users'  :
				$prefix_len = strlen(get_data_path(false, "$type/"));
				# if the file we want belongs to an extension, get the extension to check the security
				if (strpos(($original_file = substr($file, $prefix_len)), "extensions") == 0) {
					$bits = split("/+", $original_file, 3);
					$ext_name = $bits[1];
					$ext_file = $bits[2];
					unset($bits);
					switch ($type) {
						case 'system' :
							$extension = null;
						break;
						case 'web'    :
							$web = &get_web_system();
							$extension = &$web->get_extension($ext_name);
						break;
						case 'users'  :
							$users = &get_users_system();
							$extension = &$users->get_extension($ext_name);
						break;
					}
					if (!$extension->file_view_access($ext_file)) {
						# Fraid not Sonny Jim
						$access_reject_message = &$session->get_var('access_reject_message');
						$session->login_screen($extension->name,"Access to this file is restricted.</b><br>$ACCESS_REJECT_MESSAGE");
						break;
					}					
				}#end if
				$files_sent_user[$query_string] = $file;
				send_cacheable_file($file);

				break;
			case 'user':
				$userid = $_REQUEST['userid'];
				$users = &get_users_system();
				if(in_array($userid,$users->managed_userids())) {
					send_cacheable_file($file);
					$files_sent_user[$query_string] = $file;
				} else {
					$access_reject_message = &$session->get_var('access_reject_message');
					$session->login_screen($extension->name,"Access to this file is restricted.</b><br>$access_reject_message");
				}
				break;
			case 'site': case 'site_design' :
				$site = &$this->get_site();
				if ($site->read_access()) {
					send_cacheable_file($file);
					$files_sent_user[$query_string] = $file;
				} else {
					# Fraid not Sonny Jim
					$access_reject_message = &$session->get_var('access_reject_message');
					$session->login_screen($site->name,"Access to this file is restricted.</b><br>$access_reject_message");
				}
				break;
			case 'site_design_edit' :
				# only superusers can access site design files without a site
				if (superuser('web')) {
					$files_sent_user[$query_string] = $file;
					send_cacheable_file($file);
				} else {
					# Fraid not Sonny Jim
					$access_reject_message = &$session->get_var('access_reject_message');
					$session->login_screen("ACCESS DENIED","Access to this file is restricted.</b><br>$access_reject_message");
				}

				break;
			case 'page': case 'page_template':
				$page = &$this->get_page();
				if ($page->read_access()) {
					$files_sent_user[$query_string] = $file;
					send_cacheable_file($file);
				} else {
					# Fraid not Sonny Jim
					$access_reject_message = &$session->get_var('access_reject_message');
					$session->login_screen($page->name,"Access to this file is restricted.</b><br>$access_reject_message");
				}
				break;
			default:
				break;
		}
	}

	 ######################################################
	# Print the frontend as it should appear at the moment - this is becoming a bit unescessary
	function paint() {

		# Set the browser's language and character set.
		$system_config = &get_system_config();
		if($system_config->default_charset)
			header("Content-type: text/html; charset=$system_config->default_charset");
		if($system_config->default_language)
			header("Content-language: $system_config->default_language");

		# Not doing anythign special, lets just let the site handle it
		$site = &$this->get_site();
		if(!$site->id) {
			$this->page_not_found();
			return;
		}
		$site->print_frontend();
	}

}

?>
