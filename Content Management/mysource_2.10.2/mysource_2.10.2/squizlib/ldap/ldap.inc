<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Generic Include Files -- PHP4 ----------##
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
## File: ldap/ldap.inc
## Desc: Thin LDAP server abstracion layer object
## $Source: /home/cvsroot/squizlib/ldap/ldap.inc,v $
## $Revision: 1.8 $
## $Author: brobertson $
## $Date: 2004/03/16 12:14:16 $
#######################################################################

#---------------------------------------------------------------------#

class Ldap extends Object {

	var $host='';     # Abbr: h
	var $password=''; # Abbr: p
	var $rootdn='';
	var $bind='';	   
	
	var $ptr=''; # The current ldap server pointer
	 
	 ##################################
	# Constructor
	function Ldap ($h='',$p='',$bdn='',$rdn='') {
		$this->print_errors = 1;
		if ($h) { # If a host has been specified, connect
			$this->connect($h,$p,$bdn,$rdn);
		}
	}

	 ###############################
	# Connect to a certain LDAP server & bind to it
	function connect($h='',$p='',$bdn='',$rdn='',$errors='0') {
		if ($h && $h != $this->host) {
			if ($this->ptr) { # Close old ptr
				ldap_close($this->ptr);
			}
			if (!($this->ptr = ldap_connect($h))) {
				if ($errors) {
					$this->_set_error("Unable to connect to LDAP server: $h. ",__FILE__,__LINE__);
				}
				return false;
			}
			$this->host = $h;
		}
		# Bind to the server
		if (!$p || $p == '') {	// means anonymous bind
			if (!($r=ldap_bind($this->ptr))) {
				if ($errors > 0) {
					$this->_set_error("Unable to Anonomously bind to LDAP server: $h.");	
					unset($this->ptr);
					return false;
				}
			}
		} else {
			$bindto = ($rdn) ? $bdn.",".$rdn : $bdn;
			if (!($r=ldap_bind($this->ptr,$bindto,$p))) {
				if ($errors > 0) {
					$this->_set_error("Unable to bind to LDAP server: $h. BDN: ".$bindto.". Password: ".$p.":".(($p) ? "YES" : "NO")."");
				}
				unset($this->ptr);
				return false;
			}
		}
		$this->host = $h;
		$this->bind = $r;
		$this->rootdn = $bindto;
		return $r;
	}

	 #####################################
	# Disconnect from the database server
	function disconnect() {
		return ldap_close($this->ptr);
	}

	function connect_as_user($bdn='',$password='') {
		$conn_result = $this->connect($this->host,$password,$bdn);
		return $conn_result;
	}

	  #############################################
	 # search the server starting at $startdn, using $filter which can be an array.
	 # returns a search result identifier
	function search($startdn='',$filter='') {
		if (!($sr = ldap_search($this->ptr,$startdn,$filter))) {
			$this->_set_error("Unable to SEARCH [$startdn] with Filter [$filter]: ".ldap_error($this->ptr),__FILE__,__LINE__);
			return 0;
		} else {
			return $sr;
		}
	}

	  ##################################################################################
	 # returns the entries in a given search result in a multi-dim array...
	function get_entries($result='') {
		if (!($info = ldap_get_entries($this->ptr,$result)))	{
			$this->_set_error("Unable to get entries for LDAP search result [$result] :".ldap_error($this->ptr),__FILE__,__LINE__);
			return 0;
		} else {
			return $info;
		}
	}


	  ##################################################################################
	 # returns the number of entries in a given search result...
	function get_num_entries($result='') {
		if (!$result)
			return 0;
		return ldap_count_entries($this->ptr,$result);
	}


	  ##################################################################################
	 # insert an entry into ldap. The details are deifined in $details, and its added at 
	 # level specified by $dn
	function add_entry($dn='',$details='') {
		if (!ldap_add($this->ptr,$dn,$details)) {
			$this->_set_error("Unable to INSERT entry into LDAP at DN: [$dn] ".ldap_error($this->ptr),__FILE__,__LINE__);
		} else {
			return 1;
		}
	}

	  ##################################################################################
	 # modify an entry into ldap. The details are deifined in $details, and its added at 
	 # level specified by $dn
	function modify_entry($dn='',$details='') {
		if (!ldap_modify($this->ptr,$dn,$details)) {
			$this->_set_error("Unable to MODIFY entry into LDAP at DN: [$dn] ".ldap_error($this->ptr),__FILE__,__LINE__);
		} else {
			return 1;
		}
	}

	  ##################################################################################
	 # deletes an entry from ldap. The dn path details are deifined in $dn
	function delete_entry($dn='') {
		if (!ldap_delete($this->ptr,$dn)) {
			$this->_set_error("Unable to DELETE entry from LDAP at DN: [$dn] ".ldap_error($this->ptr),__FILE__,__LINE__);
		} else {
			return 1;
		}
	}

}

?>
