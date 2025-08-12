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
## $Source: /home/cvsroot/xtras/conditions/value_in_url/value_in_url.inc,v $
## $Revision: 1.2 $
## $Author: achadszinow $
## $Date: 2003/12/05 00:19:31 $
#######################################################################
global $INCLUDE_PATH;
require_once("$INCLUDE_PATH/condition.inc");
#---------------------------------------------------------------------#

class Value_In_Url extends Condition {
	var $width = 515;
	var $height = 210;

	function evaluate($val) {
		list($label, $value) = explode(',', $val);
		if ($value == 'any_value') {
			return isset($_GET[$label]);
		} else {
			$values = explode('|', $value);
			if (!empty($values)) {
				foreach($values as $v) {
					if ($_GET[$label] == $v) {
						return true;
					}
				}
			}
		}
		return false;
	}#end evaluate()

	function print_backend() {
		?>
		<form name="cond_form" onsubmit="save_conditions()">
			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td valign="top" align="left" nowrap>URL Label</td>
				<td valign="top" align="left"><input type="text" name="url_label"></td>
			</tr>
			<tr>
				<td valign="top" align="left" colspan="2">What label in the URL should we be looking at?</td>
			</tr>
			<tr>
				<td valign="top" align="left" nowrap>URL Value</td>
				<td valign="top" align="left"><input type="text" name="url_value"></td>
			</tr>
			<tr>
				<td valign="top" align="left" colspan="2">What value in the area of the URL we are looking at should the value equal? Type any_value if you wish to return true on if the url element is not empty. If you would like to enter more than one value enter your values separated by a |</td>
			</tr>
			<tr>
				<td valign="top" align="center" colspan="2"><input type="submit" value="Save"></td>
			</tr>
			</table>
		</form>

		<script language="Javascript">
			get_previous_options();

			function save_conditions() {
				var conditions = new Array();
				
				conditions[0] = document.cond_form.url_label.value;
				conditions[1] = document.cond_form.url_value.value;

				window.opener.document.main_form.showif_conds.value = conditions;
				window.close();
			}

			function get_previous_options(){
				vals = new String();
				vals = window.opener.document.main_form.showif_conds.value;

				val = new Array();
				val = vals.split(",");

				if(val[0] != "" && val[0] != "undefined"){
					document.cond_form.url_label.value = val[0];
				}
				if(val[1] != "" && val[1] != "undefined"){
					document.cond_form.url_value.value = val[1];
				}
			}
		</script>
		<?php
	}
}#end class Logged_In
?>