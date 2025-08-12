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
## $Source: /home/cvsroot/xtras/conditions/categoryid_in_url/categoryid_in_url.inc,v $
## $Revision: 1.2 $
## $Author: achadszinow $
## $Date: 2003/10/16 00:07:18 $
#######################################################################
global $INCLUDE_PATH;
require_once("$INCLUDE_PATH/condition.inc");
#---------------------------------------------------------------------#

class Categoryid_In_Url extends Condition {
	var $width = 515;
	var $height = 210;

	function evaluate($categoryids) {
		if ($categoryids == 'any') {
			return (isset($_GET['cid']));
		} else {
			$categoryid_array = explode('|', $categoryids);
			return (in_array($_GET['cid'], $categoryid_array));
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
			<td valign="top" align="left" nowrap>Category ID(s) look for.</td>
			<td valign="top" align="left"><input type="text" name="categoryid"></td>
		</tr>
			<tr>
			<td valign="top" align="left" colspan="2">If you would like to look out for more than one category id split each category id with a | symbol. Eg. I want to look out for categoryid Six, Seven and Eight then I would enter 6|7|8. Finally you can use the special work "any". This will make it return true if any categoryid is in the url.</td>
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