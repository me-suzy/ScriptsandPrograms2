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
## $Source: /home/cvsroot/xtras/conditions/required_data_in_post_var/required_data_in_post_var.inc,v $
## $Revision: 1.1 $
## $Author: achadszinow $
## $Date: 2003/06/05 23:33:52 $
#######################################################################
global $INCLUDE_PATH;
require_once("$INCLUDE_PATH/condition.inc");
#---------------------------------------------------------------------#

class Required_Data_In_Post_Var extends Condition {
	var $width = 400;
	var $height = 215;

	function evaluate($settings) {
		$session = &get_mysource_session();
		list($post_var_name, $data) = explode('|', $settings);
		return ($_POST[$post_var_name] == $data);
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
			<td valign="top" align="left" nowrap>Name|data</td>
			<td valign="top" align="left"><input type="text" name="categoryid"></td>
		</tr>
			<tr>
			<td valign="top" align="left" colspan="2">First type the name of the post data to look in then a pipe symbol followed by the data to look for as shown above.</td>
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
				window.opener.document.main_form.showif_conds.value = formObj.categoryid.value;
				window.close();
			}
			
			// Command to load the value
			document.cond_form.categoryid.value = window.opener.document.main_form.showif_conds.value;
		</script>

		<?php
	}

}#end class Logged_In

?>