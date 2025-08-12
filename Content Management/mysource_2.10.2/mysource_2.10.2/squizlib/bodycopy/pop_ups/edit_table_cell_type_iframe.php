<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Bodycopy Editor ---- PHP4 --------------##
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
## $Source: /home/cvsroot/squizlib/bodycopy/pop_ups/edit_table_cell_type_iframe.php,v $
## $Revision: 2.0 $
## $Author: agland $
## $Date: 2001/12/18 06:03:02 $
#######################################################################

include(dirname(__FILE__)."/header.php"); 
?> 
<script language="JavaScript">

	function popup_init() {

		var f = document.main_form;
		var url = owner.bodycopy_current_edit["data"]["url"];
		var width = owner.bodycopy_current_edit["data"]["width"];
		var height = owner.bodycopy_current_edit["data"]["height"];
		var scroll = owner.bodycopy_current_edit["data"]["scroll"];
		f.url.value = (url == null)  ? "" : url;
		f.width.value = (width == null)  ? "" : width;
		f.height.value = (height == null)  ? "" : height;
		owner.highlightComboElement(f.scroll, scroll);
		// remove focus because Netscape seems to scroll to the top of the page
		// when the focus happens (probably because the element is not visisble
		// when the focus is run
		//f.url.focus();

	}// end popup_init()

	function popup_save(f) {
		owner.bodycopy_save_table_cell_type_iframe(owner.elementValue(f.url),owner.elementValue(f.width),owner.elementValue(f.height),owner.elementValue(f.scroll));
	}

</script>
<table width="100%" border="0">
<form name="main_form">
	<tr>
		<td nowrap class="bodycopy-popup-heading">iFrame Properties&nbsp;</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="4">
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Url :</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td valign="middle">
						<div style="font-family: courier new, monospace; font-size: 9pt;">
							<input type="text" name="url" size="50">
						</div>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Width :</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td valign="middle">
						<div style="font-family: courier new, monospace; font-size: 9pt;">
							<input type="text" name="width" size="50">
						</div>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Height :</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td valign="middle">
						<div style="font-family: courier new, monospace; font-size: 9pt;">
							<input type="text" name="height" size="50">
						</div>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Enable Scrollbars :</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td valign="middle">
						<div style="font-family: courier new, monospace; font-size: 9pt;">
							<select name="scroll">
								<option value="Auto">Auto</option>
								<option value="Yes">Yes</option>
								<option value="No">No</option>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<hr>
		</td>
	</tr>
	<tr>
		<td align="center">
			<input type="button" value="Save" onclick="javascript: popup_save(this.form)">
			<input type="button" value="Cancel" onclick="javascript: popup_close();">
		</td>
	</tr>
</form>
</table>
<? include(dirname(__FILE__)."/footer.php"); ?> 