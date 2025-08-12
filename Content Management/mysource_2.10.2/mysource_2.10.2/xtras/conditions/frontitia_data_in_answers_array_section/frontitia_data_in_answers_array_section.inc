<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
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
## $Source: /home/cvsroot/xtras/conditions/frontitia_data_in_answers_array_section/frontitia_data_in_answers_array_section.inc,v $
## $Revision: 1.3 $
## $Author: ramato $
## $Date: 2003/07/15 01:04:35 $
#######################################################################
global $INCLUDE_PATH;
require_once("$INCLUDE_PATH/condition.inc");
#---------------------------------------------------------------------#

class Frontitia_Data_In_Answers_Array_Section extends Condition {
	var $width = 400;
	var $height = 185;

	function evaluate($attributeid) {
		$session = &get_mysource_session();
		$answers_array = $session->get_var('form_answers');
		if (is_array($answers_array)) {
			$data = $answers_array[$attributeid];
			if ($data) {
				return true;
			}
		}
		return false;
	}#end evaluate()

	function print_backend() {
		?>
		<form onsubmit="return save_conditions(this)" name="cond_form">
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<th valign="top">Edit conditions</th>
		</tr>
		</table>
		<br />
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td valign="top" align="left" nowrap>Attribute ID to monitor in answers array</td>
			<td valign="top" align="left"><input type="text" name="foreign_keyid"></td>
		</tr>
		</table>
		<br />
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td valign="top" align="center">
				<input type="submit" value="Save">
				<input type="button" value="Cancel" onclick="javascript: window.close()">
			</td>
		</tr>
		</table>
		</form>
		<script type="text/javascript" language="javascript">
			// Function to save the value
			function save_conditions(formObj) {
				window.opener.document.main_form.showif_conds.value = formObj.foreign_keyid.value;
				window.close();
			}
			
			// Command to load the value
			document.cond_form.foreign_keyid.value = window.opener.document.main_form.showif_conds.value;
		</script>

		<?php
	}

}#end class Logged_In

?>