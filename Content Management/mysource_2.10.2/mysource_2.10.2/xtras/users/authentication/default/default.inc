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
## $Source: /home/cvsroot/xtras/users/authentication/default/default.inc,v $
## $Revision: 1.2 $
## $Author: csmith $
## $Date: 2003/06/26 07:24:24 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/users_authentication.inc");
#---------------------------------------------------------------------#

class Users_Authentication_Default extends Users_Authentication {
	
	 ####################################################
	# Given a login/password, returns the corresponding
	# userid in the mysource user database. Returns null
	# or 0 or anything that doesn't resolve to a int
	# greater than 0 in authentication falied.
	function get_userid($login,$password) {
			$v = explode('.', $this->users_system->db->server_version());
			if ($v[0] == '4') {
				$qry = "SELECT userid FROM user WHERE login='".addslashes($login)."' AND password=OLD_PASSWORD('".addslashes($password)."')";
			} else {
				$qry = "SELECT userid, password FROM user WHERE login='".addslashes($login)."' AND password=PASSWORD('".addslashes($password)."')";
			}
		return $this->users_system->db->single_element($qry);
	}

	 ###################################################
	# Generate random passwords when creating new users
	# in the MySource database?
	function generate_password() {
		return true;
	}


}


?>