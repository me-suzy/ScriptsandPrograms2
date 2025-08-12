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
## $Source: /home/cvsroot/xtras/conditions/required_data_in_request_var/required_data_in_request_var.inc,v $
## $Revision: 1.2 $
## $Author: dofford $
## $Date: 2004/01/14 23:00:13 $
#######################################################################
global $INCLUDE_PATH;
require_once("$INCLUDE_PATH/condition.inc");
#---------------------------------------------------------------------#

class Required_Data_In_Request_Var extends Condition {
	var $width = 400;
	var $height = 300;

	function evaluate($settings) {
		$session = &get_mysource_session();
		list($name, $data, $request) = explode(',', $settings);

		$result =false;
		if (isset($name) && isset($data) && isset($request)) {
			switch ($request) {
				case 'request':
					$result = $this->compare_data_vals($data, $_REQUEST[$name]);
					break;
				case 'get':
					$result = $this->compare_data_vals($data, $_GET[$name]);
					break;
				case 'post':
					$result = $this->compare_data_vals($data, $_POST[$name]);
					break;
			}
		}
		return $result;
	}#end evaluate()

	function compare_data_vals($data, $compare) {
		if ($data == '%set%')
			return isset($compare);
		else
			return ($data == $compare);
	}

	function print_backend() {
		?>
		<form name="comp_form" onsubmit="return save_conditions()">
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<th valign="top">Edit conditions</th>
		</tr>
		</table>
		<br />
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
		<tr>
			<td valign="top" align="left" nowrap>Name</td>
			<td valign="top" align="left"><input type="text" name="name"></td>
		</tr>
		<tr>
			<td valign="top" align="left" nowrap>Data</td>
			<td valign="top" align="left"><input type="text" name="data"></td>
		</tr>
		<tr>
			<td valign="top" align="left" nowrap>Data</td>
			<td valign="top" align="left">
				<? echo combo_box('request', array('request'=>'GET/POST','get'=>'GET','post'=>'POST'), 'request'); ?>
			</td>
		</tr>
			<tr>
			<td valign="top" align="left" colspan="2">First enter the name of the request data to look for, then enter the data. Use keyword %set% for data to check if the value is set. Select the type of request you are looking for</td>
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

			get_previous_options();
			
			function save_conditions() {
				var conditions = new Array();
				
				conditions[0] = this.comp_form.name.value;
				conditions[1] = this.comp_form.data.value;
				conditions[2] = this.comp_form.request.value;

				window.opener.document.main_form.showif_conds.value = conditions;
				window.close();
			}

			function get_previous_options(){
				vals = new String();
				vals = window.opener.document.main_form.showif_conds.value;

				val = new Array();
				val = vals.split(",");

				if(val[0] != "" && val[0] != "undefined"){
					this.comp_form.name.value = val[0];
				}
				if(val[1] != "" && val[1] != "undefined"){
					this.comp_form.data.value = val[1];
				}
				if(val[2] != "" && val[2] != "undefined"){
					this.comp_form.request.value = val[2];
				}
			}

		</script>

		<?php
	}

}#end class Logged_In

?>