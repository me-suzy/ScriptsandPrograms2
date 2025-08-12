<?php

/*
	Copyright (C) 2003-2005 UseBB Team
	http://www.usebb.net
	
	$Header: /cvsroot/usebb/UseBB/sources/session.php,v 1.54 2005/09/15 19:07:09 pc_freak Exp $
	
	This file is part of UseBB.
	
	UseBB is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	UseBB is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with UseBB; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 * Session management
 *
 * Contains the session class to do session management.
 *
 * @author	UseBB Team
 * @link	http://www.usebb.net
 * @license	GPL-2
 * @version	$Revision: 1.54 $
 * @copyright	Copyright (C) 2003-2005 UseBB Team
 * @package	UseBB
 * @subpackage	Core
 */

//
// Die when called directly in browser
//
if ( !defined('INCLUDED') )
	exit();

/**
 * Session management
 *
 * Does all kinds of session management in addition to PHP's functions.
 *
 * @author	UseBB Team
 * @link	http://www.usebb.net
 * @license	GPL-2
 * @version	$Revision: 1.54 $
 * @copyright	Copyright (C) 2003-2005 UseBB Team
 * @package	UseBB
 * @subpackage	Core
 */
class session {
	
	/**
	 * @var array Array containing all session info, as well as user info. update() must be called before this contains information.
	 */
	var $sess_info = array();

	/**
	 * Start or continue a session
	 */
	function start() {
		
		global $functions;
		
		//
		// Set the session save path
		//
		$proposed_save_path = $functions->get_config('session_save_path');
		if ( !empty($proposed_save_path) )
			session_save_path($proposed_save_path);
		
		//
		// Set some PHP session cookie configuration options
		//
		session_set_cookie_params($functions->get_config('session_max_lifetime')*60, $functions->get_config('cookie_path'), $functions->get_config('cookie_domain'), $functions->get_config('cookie_secure'));
		
		//
		// Set the session name
		//
		session_name($functions->get_config('session_name').'_sid');
		
		//
		// Start the session
		//
		if ( !@ini_get('session.auto_start') )
			session_start();
		
		//
		// Several session info we maintain
		//
		$_SESSION['previous_visit'] = ( isset($_SESSION['previous_visit']) && valid_int($_SESSION['previous_visit']) ) ? $_SESSION['previous_visit'] : 0;
		$_SESSION['viewed_topics'] = ( isset($_SESSION['viewed_topics']) && is_array($_SESSION['viewed_topics']) ) ? $_SESSION['viewed_topics'] : array();
		$_SESSION['latest_post'] = ( isset($_SESSION['latest_post']) && valid_int($_SESSION['latest_post']) ) ? $_SESSION['latest_post'] : 0;
		
	}
	
	/**
	 * Update the session table for this session
	 *
	 * This method also checks for banned IP-addresses or user accounts and checks for auto-login cookies. update() must be called before $sess_info contains usable information.
	 *
	 * @param string $location Current forum location (current location when missing)
	 * @param int $user_id New user ID (current ID when missing)
	 */
	function update($location=NULL, $user_id=NULL) {
		
		global $functions, $db;
		
		//
		// Some required workarounds...
		//
		$location = addslashes($location);
		$current_time = time();
		
		//
		// First, get the user's IP address
		//
		$ip_addr = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : getenv('REMOTE_ADDR');
		
		//
		// Get banned IP addresses
		//
		$result = $db->query("SELECT ip_addr FROM ".TABLE_PREFIX."bans WHERE ip_addr <> ''");
		$ip_banned = false;
		$banned_ips_sql = array();
		while ( $out = $db->fetch_result($result) ) {
			
			$banned_ip = preg_quote($out['ip_addr'], '#');
			$banned_ip = preg_replace(array('#\\\\\*#', '#\\\\\?#'), array('[0-9]*', '[0-9]'), $banned_ip);
			if ( preg_match('#^'.$banned_ip.'$#', $ip_addr) )
				$ip_banned = true;
			$banned_ip = $out['ip_addr'];
			$banned_ip = preg_replace(array('#\*#', '#\?#'), array('%', '_'), $banned_ip);
			$banned_ips_sql[] = "ip_addr LIKE '".$banned_ip."'";
			
		}
		
		//
		// Remove older clone sessions if needed
		//
		$add_to_remove_query = array();
		if ( !$functions->get_config('allow_multi_sess') ) {
			
			$add_to_remove_query[] = "( ip_addr = '".$ip_addr."' AND sess_id <> '".session_id()."' )";
			
		}
		
		//
		// Remove outdated sessions and searches if needed
		//
		if ( $functions->get_config('session_max_lifetime') ) {
			
			$min_updated = $current_time - ( $functions->get_config('session_max_lifetime') * 60 );
			$add_to_remove_query[] = "updated < ".$min_updated;
			$db->query("DELETE FROM ".TABLE_PREFIX."searches WHERE time < ".$min_updated);
			
		}
		
		//
		// Remove sessions with banned IP addresses
		//
		if ( count($banned_ips_sql) ) {
			
			$add_to_remove_query[] = join(' OR ', $banned_ips_sql);
			
		}
		
		//
		// Now run the cleanup query
		//
		if ( count($add_to_remove_query) ) {
			
			$add_to_remove_query = join(' OR ', $add_to_remove_query);
			$db->query("DELETE FROM ".TABLE_PREFIX."sessions WHERE ".$add_to_remove_query);
			
		}
		
		if ( $ip_banned ) {
			
			//
			// Save session information with the banned key and
			// IP address if this IP address is banned
			//
			$this->sess_info = array(
				'sess_id' => session_id(),
				'user_id' => 0,
				'ip_addr' => $ip_addr,
				'updated' => $current_time,
				'ip_banned' => true
			);
			
		} else {
			
			$user_info_set = false;
			
			//
			// Get information about the current session
			//
			$result = $db->query("SELECT user_id, started, location, pages, ip_addr FROM ".TABLE_PREFIX."sessions WHERE sess_id = '".session_id()."'");
			$current_sess_info = $db->fetch_result($result);
			
			//
			// If this session ID exists in database and if it doesn't belong to this IP address
			//
			if ( is_array($current_sess_info) && $current_sess_info['ip_addr'] != $ip_addr ) {
				
				//
				// Reload the page, stripping the wrong session ID
				// in the URL (if present) and unsetting the cookie
				//
				$SID = SID;
				$goto = str_replace($SID, '', $_SERVER['REQUEST_URI']);
				setcookie($functions->get_config('session_name').'_sid', '', time()-31536000, $functions->get_config('cookie_path'), $functions->get_config('cookie_domain'), $functions->get_config('cookie_secure'));
				$functions->raw_redirect($goto);
				exit();
				
			}
			
			//
			// Auto login
			//
			if ( $functions->isset_al() && !$current_sess_info['user_id'] ) {
				
				//
				// If there is a remember cookie
				// and the user is not logged in...
				//
				$cookie_data = $functions->get_al();
				
				if ( !valid_int($cookie_data[0]) || !intval($cookie_data[0]) ) {
					
					//
					// There's something wrong with the user id
					//
					$user_id = 0;
					$functions->unset_al();
					
				} else {
					
					$cookie_data[0] = intval($cookie_data[0]);
					
					$result = $db->query("SELECT * FROM ".TABLE_PREFIX."members WHERE id = ".$cookie_data[0]);
					$user_info = $db->fetch_result($result);
					
					if ( $user_info['id'] ) {
						
						//
						// If the encrypted password in the cookie equals to the password in the database
						// the user is active and not banned and [ the board is not closed or the user is an admin ]
						//
						if ( $cookie_data[1] === $user_info['passwd'] && $user_info['active'] && !$user_info['banned'] && ( !$functions->get_config('board_closed') || $user_info['level'] == LEVEL_ADMIN ) ) {
							
							//
							// Change the user id that will be entered in the DB below
							// and renew the cookie (or it will not work anymore after a year)
							//
							$user_id = $cookie_data[0];
							$functions->set_al($user_info['id'], $user_info['passwd']);
							$_SESSION['previous_visit'] = $user_info['last_pageview'];
							$_SESSION['viewed_topics'] = array();
							
							//
							// So we have the user info, no need to find it later
							//
							$user_info_set = true;
							
						} else {
							
							$user_id = 0;
							$functions->unset_al();
							
						}
						
					} else {
						
						//
						// The user ID does not exist at all
						//
						$user_id = 0;
						$functions->unset_al();
						
					}
					
				}
				
			}
			
			//
			// Insert the new session info or update the existing session info
			//
			if ( $current_sess_info['started'] ) {
				
				//
				// The user ID did not change
				//
				if ( empty($user_id) && $user_id !== LEVEL_GUEST )
					$user_id = $current_sess_info['user_id'];
				
				//
				// Update the location and page count if a page has been passed
				//
				if ( empty($location) ) {
					
					$location = $current_sess_info['location'];
					$pages = $current_sess_info['pages'];
					
				} else {
					
					$pages = $current_sess_info['pages']+1;
					
				}
				
			} else {
				
				//
				// The session did not start yet, so this must be a guest
				//
				if ( empty($user_id) )
					$user_id = 0;
				$pages = 1;
				
			}
			
			if ( $user_id > LEVEL_GUEST && !$user_info_set ) {
				
				//
				// We don't already have the user info
				// manual login (no autologin cookie) probably
				//
				$result = $db->query("SELECT * FROM ".TABLE_PREFIX."members WHERE id = ".$user_id);
				$user_info = $db->fetch_result($result);
				
				if ( $user_info['id'] ) {
					
					//
					// If the user is active and not banned and
					// [ the board is not closed or the user is an admin ]
					//
					if ( !$user_info['active'] || $user_info['banned'] || ( $functions->get_config('board_closed') && $user_info['level'] != LEVEL_ADMIN ) ) {
						
						$user_id = 0;
						
					} else {
						
						if ( $_SESSION['previous_visit'] == 0 )
							$_SESSION['previous_visit'] = $user_info['last_pageview'];
						
					}
					
				} else {
					
					//
					// No such user ID exists
					//
					$user_id = 0;
					
				}
				
			}
			
			//
			// Now we either insert or update the session info
			//
			$update_query = ( $current_sess_info['started'] ) ? "UPDATE ".TABLE_PREFIX."sessions SET user_id = ".$user_id.", ip_addr = '".$ip_addr."', updated = ".$current_time.", location = '".$location."', pages = ".$pages." WHERE sess_id = '".session_id()."'" : "INSERT INTO ".TABLE_PREFIX."sessions VALUES ( '".session_id()."', ".$user_id.", '".$ip_addr."', ".$current_time.", ".$current_time.", '".$location."', ".$pages." )";
			$db->query($update_query);
			
			//
			// Update the last login and last pageview timestamp of the user
			//
			if ( $user_id ) {
				
				$add_to_update_query = ( $current_sess_info['user_id'] != $user_id ) ? ', last_login = '.$current_time : '';
				$db->query("UPDATE ".TABLE_PREFIX."members SET last_pageview = ".$current_time.$add_to_update_query." WHERE id = ".$user_id);
				
			}
			
			//
			// Now save the session information
			//
			$this->sess_info = array(
				'sess_id' => session_id(),
				'user_id' => $user_id,
				'ip_addr' => $ip_addr,
				'started' => ( valid_int($current_sess_info['started']) ) ? $current_sess_info['started'] : $current_time,
				'updated' => $current_time,
				'location' => $location,
				'pages' => $pages,
				'ip_banned' => false,
				'user_info' => ( $user_id ) ? $user_info : array()
			);
			
		}
		
	}
	
	/**
	 * Destroy a running session
	 */
	function destroy() {
		
		global $functions, $db;
		
		$functions->unset_al();
		$db->query("DELETE FROM ".TABLE_PREFIX."sessions WHERE sess_id = '".session_id()."'");
		$db->query("DELETE FROM ".TABLE_PREFIX."searches WHERE sess_id = '".session_id()."'");
		$_SESSION = array();
		session_destroy();
		
	}
	
}

?>
