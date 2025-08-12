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
## $Source: /home/cvsroot/xtras/conditions/session_var_exists/session_var_exists.inc,v $
## $Revision: 1.1 $
## $Author: dofford $
## $Date: 2003/12/10 23:47:34 $
#######################################################################
global $INCLUDE_PATH;
require_once("$INCLUDE_PATH/condition.inc");
#---------------------------------------------------------------------#

class Session_Var_Exists extends Condition {
	var $width = 400;
	var $height = 270;

	function evaluate($val) {
		$session = &get_mysource_session();
		list($svar_name, $svar_val) = explode(',', $val);

		$session_val = $session->get_var($svar_name);
		if ($session_val == $svar_val) {
			return true;
		}

		return false;
	}#end evaluate()

	function print_backend() {
		?>
		<form onsubmit="return save_conditions(this)" name="cond_form">
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<th valign="top">Session Variable Conditions</th>
		</tr>
		</table>
		<br />
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td valign="top" align="left" nowrap>Session Variable Name</td>
			<td valign="top" align="left"><input type="text" name="svar_name"></td>
		</tr>
		<tr>
			<td valign="top" align="left" nowrap>Session Variable Value</td>
			<td valign="top" align="left"><input type="text" name="svar_val"></td>
		</tr>
			<tr>
			<td valign="top" align="left" colspan="2">This show if condition returns true if the session var with the given name contains the given value</td>
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

			//function to load the value
			get_previous_options();

			// Function to save the value
			function save_conditions() {
				var conditions = new Array();
				
				conditions[0] = this.cond_form.svar_name.value;
				conditions[1] = this.cond_form.svar_val.value;

				window.opener.document.main_form.showif_conds.value = conditions;
				window.close();
			}

			function get_previous_options(){
				vals = new String();
				vals = window.opener.document.main_form.showif_conds.value;

				val = new Array();
				val = vals.split(",");

				if(val[0] != "" && val[0] != "undefined"){
					this.cond_form.svar_name.value = val[0];
				}
				if(val[1] != "" && val[1] != "undefined"){
					this.cond_form.svar_val.value = val[1];
				}
			}
		</script>

		<?php
	}

}#end class Logged_In

?>