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
## $Source: /home/cvsroot/xtras/conditions/required_data_in_post_var_save_session/required_data_in_post_var_save_session.inc,v $
## $Revision: 1.3 $
## $Author: achadszinow $
## $Date: 2003/06/10 02:43:47 $
#######################################################################
global $INCLUDE_PATH;
require_once("$INCLUDE_PATH/condition.inc");
#---------------------------------------------------------------------#

class Required_Data_In_Post_Var_Save_Session extends Condition {
	var $width = 400;
	var $height = 270;

	function evaluate($settings) {
		$session = &get_mysource_session();
		list($post_var_name, $data, $hidden_name) = explode('|', $settings);
		if ($_POST[$post_var_name] == $data) {
			$session->set_var($post_var_name,$data);
			return true;
		} else {
			$session_data = $session->get_var($post_var_name);
			if ($_POST[$hidden_name]) {
				$null = NULL;
				$session->set_var($post_var_name,$null);
				return false;
			} else {
				return ($session_data == $data);
			}
		}
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
			<td valign="top" align="left" nowrap>name|data|hidden_name</td>
			<td valign="top" align="left"><input type="text" name="categoryid"></td>
		</tr>
			<tr>
			<td valign="top" align="left" colspan="2">This showif requires three bits of information to work. The first is the name of the post var data. The second is the value representing true that the post var will hold if it is true. The third is the name of a post var that will be true when a new post has been performed. Each of these elements should be entered into the input box seperated by a pipe symbol as shown in a rough example above.</td>
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