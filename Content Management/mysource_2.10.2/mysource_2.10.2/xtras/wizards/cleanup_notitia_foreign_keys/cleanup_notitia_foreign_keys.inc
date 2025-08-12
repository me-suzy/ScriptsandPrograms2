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
## File: include/report.inc
## Desc: A wizard that updates all the virtual paths
## $Source: /home/cvsroot/xtras/wizards/cleanup_notitia_foreign_keys/cleanup_notitia_foreign_keys.inc,v $
## $Revision: 1.3 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:12 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Update Virtual Paths Wizard
* A wizard that updates all the virtual paths to be either the page short name
* or the page name. This wizard works on a web system, site, or page
* level.
*
* @access public
* @package Wizards
*/
class Cleanup_Notitia_Foreign_Keys extends Wizard {
	
	/**
	* The description of the wizard
	* @var string
	*/
	var $report_desc = 'Cleanup Notitia Foreign keys (Possibly needed to be run if you used Foreign Keys Prior to Notitia v1.3.25 Stable or v1.4.15 Dev';

	/**
	* An array of levels that the wizard is compatible with
	* @var array
	*/
	var $compatible_with = array('web');

	/**
	* An array of default parameter set options for the wizard
	* @var array
	*/
	var $parameters = array();

	
	/**
	* Constructor
	* @param	object &$asset This is the current asset
	* @returns	object Update_virtual_paths
	* @access	public
	*/
	function Update_virtual_paths (&$asset) {	
		Wizard::Wizard($asset);
	}

	/**
	* This function does all the processing
	* @param  object &$backend A reference to the wizard backend object
	* @access public
	*/
	function process_wizard_web(&$backend) {
		$web = &$this->get_web_system();
		$webdb = &$web->get_db();

		$valid_recordids = $webdb->single_column("select recordid from xtra_web_extension_notitia_record");
		# Create a record and then delete it so we can get the biggest record id 
		$biggest_recordid = $webdb->insert("INSERT INTO xtra_web_extension_notitia_record () VALUES ()");
		$webdb->delete("DELETE FROM xtra_web_extension_notitia_record WHERE recordid = $biggest_recordid");

		$foreign_key_attributeids = $webdb->single_column("SELECT attributeid FROM xtra_web_extension_notitia_attribute WHERE type ='foreign_key'");
		$foreign_key_attributeids_string = implode(',', $foreign_key_attributeids);

		# We do biggest recordid + 100 because it's possible that 
		for($i=1; $i < $biggest_recordid; $i++) {
			if (in_array($i, $valid_recordids)) continue; # We only want to make sure invalid record ids are not in the notitia
			$value_changes = $webdb->associative_array2("SELECT recordid, attributeid, REPLACE(value, '|$i|', '|') as value FROM xtra_web_extension_notitia_attribute_value WHERE attributeid IN ($foreign_key_attributeids_string) AND value LIKE '%|$i|%'");
			foreach($value_changes as $recordid => $changes) {
				foreach($changes as $attributeid => $value) {
					if (strlen($value) == 1) {
						# Must be just a | char and that's invalid
						$insert_value = '';
					} else {
						$insert_value = $value;
					}
					$webdb->replace("REPLACE INTO xtra_web_extension_notitia_attribute_value (value,attributeid,recordid) VALUES ('$insert_value','$attributeid','$recordid')");
					echo "<p>Attributeid: $attributid Recordid: $recordid value has been cleaned</p>";
				}
			}
		}

		global $CACHE;
		$CACHE->wipe();
		echo "<p>Cleaning complete. If you did not get any messages above this one you did not need to run this wizard.</p>";
	}
}

?>
