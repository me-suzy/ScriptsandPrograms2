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
## File: include/squiz_server_manager.inc
## Desc: manage squiz server jobs
## $Source: /home/cvsroot/mysource/include/squiz_server_client_mysource2.inc,v $
## $Revision: 2.1 $
## $Author: bvial $
## $Date: 2004/02/25 23:01:23 $
#######################################################################
$system_config = &get_system_config();
include_once($system_config->squiz_server_details['path'].'/code/squiz_server_client.inc');
#---------------------------------------------------------------------#

/**
* Server_Client
*
* Wrapper for the Squiz_Server_Client, main purpose is to supply the domain, port and systemid from the conf file
* so that individual users don't need to worry about that
*/
class Squiz_Server_Client_MySource2 extends Squiz_Server_Client {

	/**
	* Create a new client
	*
	* @param string $systemid the id that the server knows this system by
	* @param string $domain domain to connect to
	* @param integer $port port to connect to
	*
	* @access public
	*/
	function Squiz_Server_Client_MySource2() {
		$system_config = &get_system_config();
		$this->Squiz_Server_Client($system_config->squiz_server_details['systemid'],$system_config->squiz_server_details['domain'],$system_config->squiz_server_details['port']);
	}

}