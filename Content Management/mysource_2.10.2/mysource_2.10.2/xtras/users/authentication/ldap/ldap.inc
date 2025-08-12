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
## $Source: /home/cvsroot/xtras/users/authentication/ldap/ldap.inc,v $
## $Revision: 1.3 $
## $Author: nking $
## $Date: 2003/06/19 05:22:44 $
#######################################################################
global $INCLUDE_PATH, $SQUIZLIB_PATH;
include_once("$INCLUDE_PATH/users_authentication.inc");
include_once("$SQUIZLIB_PATH/ldap/ldap.inc");
#---------------------------------------------------------------------#

class Users_Authentication_LDAP extends Users_Authentication {


	 #######################################
	# Returns a reference to an LDAP object
	function &get_ldap() {
		if(!isset($this->temp['ldap'])) {
			$this->temp['ldap'] = new Ldap(
				$this->parameters['host'],
				$this->parameters['password'],
				$this->parameters['binddn'],
				$this->parameters['rootdn']
			);
		}
		return $this->temp['ldap'];
	}

	 ####################################################
	# Given a login/password, returns the corresponding
	# userid in the mysource user database. Returns null
	# or 0 or anything that doesn't resolve to a int
	# greater than 0 in authentication falied.
	function get_userid($login,$password) {
		# Check LDAP for an existing user
		$ldap = &$this->get_ldap();
		# We do the search instead of binding directly as the user because
		# ldap->connect_as_user requires the full dn as the first arg and 
		# doing it this way doesn't limit you to having all the users in the 
		# same organisational unit in the ldap directory
		$result = $ldap->search($this->parameters['rootdn'],'('.$this->parameters['idfield'].'='.$login.')');
		if (!$result) return;
		$entry = $ldap->get_entries($result);
		if (!$entry || ($entry['count'] == 0)) return;
		# Verify password
		if ($entry) $userok = $ldap->connect_as_user($entry[($entry['count']-1)]['dn'],$password);
		if ($userok) {
			$db = &$this->get_db();
			$userid = $db->single_element("SELECT userid 
				FROM user 
				WHERE login='".addslashes($login)."'");
			if(!empty($userid)) {
				return $userid;
			} else {
				# Create a user automatically!
				$user = new User();
				if ($this->parameters['autocreate_associate'] == 1) {
					$user->create($login, $this->parameters['autocreate_organisationid'], $this->parameters['autocreate_title'], true);
				} else {
					$user->create($login);
				}
				return $user->id;
			}
		}
		return;
	}

	 ###################################################
	# Generate random passwords when creating new users
	# in the MySource database?
	function generate_password() {
		return false;
	}

	 ##########################################
	# Returns a dropdown list of organisations
	function get_organisation_list(){
		return $this->users_system->org_array_with_sticks();
	}

}


?>
