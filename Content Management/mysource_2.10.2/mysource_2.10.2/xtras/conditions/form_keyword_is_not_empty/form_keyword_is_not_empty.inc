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
## $Source: /home/cvsroot/xtras/conditions/form_keyword_is_not_empty/form_keyword_is_not_empty.inc,v $
## $Revision: 1.1 $
## $Author: ramato $
## $Date: 2003/03/17 05:26:01 $
#######################################################################
global $INCLUDE_PATH;
require_once("$INCLUDE_PATH/condition.inc");
#---------------------------------------------------------------------#

class Form_Keyword_Is_Not_Empty extends Condition {
	var $width = 390;
	var $height = 200;
	
	function evaluate($val) {
		$session = &get_mysource_session();
		$web_system = &$this->get_web_system();
		$site = &$web_system->get_site();
		$page = &$site->get_page();
		$template = &$page->get_template();
		$form = &$session->get_var("form_$page->id");
		if (!$form) return false;
		foreach ($form->sections as $sectionid => $section) {
			foreach ($section['questions'] as $questionid => $question) {
				if ($question->title == $val && trim($question->value) == '') return false;
				if ($question->title == $val && trim($question->value) != '') return true;
			}
		}
		return true;
	}#end evaluate()

	function print_backend() {
		?>
		<form onsubmit="return save_conditions(this)" name="cond_form">
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<th valign="top">Edit <i>keyword empty</i> conditions</th>
		</tr>
		</table>
		<br />
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td valign="top" align="left" nowrap>Which keyword do you want to check ? <br />(dont include the %'s)</td>
			<td valign="top" align="left"><input type="text" name="condition"></td>
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
				window.opener.document.main_form.showif_conds.value = formObj.condition.value;
				window.close();
			}
			
			// Command to load the value
			document.cond_form.condition.value = window.opener.document.main_form.showif_conds.value;
		</script>

		<?php
	}

}#end class Logged_In

?>