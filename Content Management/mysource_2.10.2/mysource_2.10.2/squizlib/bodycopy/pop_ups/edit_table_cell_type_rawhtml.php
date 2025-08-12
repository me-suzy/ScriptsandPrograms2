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
## $Source: /home/cvsroot/squizlib/bodycopy/pop_ups/edit_table_cell_type_rawhtml.php,v $
## $Revision: 2.3 $
## $Author: gsherwood $
## $Date: 2003/02/02 23:09:42 $
#######################################################################

include(dirname(__FILE__)."/header.php"); 
?> 
<script language="JavaScript">

	function popup_init() {

		var data = owner.bodycopy_current_edit["data"]["html"];
		var f = document.main_form;
		f.html.value = (data == null)  ? "" : data;
		// remove focus because Netscape seems to scroll to the top of the page
		// when the focus happens (probably because the element is not visisble
		// when the focus is run
		//f.html.focus();

	}// end popup_init()

	function popup_save(f) {
		owner.bodycopy_save_table_cell_type_rawhtml(owner.elementValue(f.html));
	}

</script>
<table width="100%" border="0">
<form name="main_form">
	<tr>
		<td nowrap class="bodycopy-popup-heading">Raw HTML Edit of Cell Contents&nbsp;</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="4">
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">HTML :</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td valign="middle">
						<div style="font-family: courier new, monospace; font-size: 9pt;">
							<textarea name="html" wrap="off" style="font-family: courier new, monospace; font-size: 9pt;" rows="14" cols="<?=(($browser == "ie") ? "85" : "95") ?>"></textarea>
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