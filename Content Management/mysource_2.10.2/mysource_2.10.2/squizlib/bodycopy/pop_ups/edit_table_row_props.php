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
## $Source: /home/cvsroot/squizlib/bodycopy/pop_ups/edit_table_row_props.php,v $
## $Revision: 2.8.2.2 $
## $Author: tbarrett $
## $Date: 2004/05/18 00:43:04 $
#######################################################################
include(dirname(__FILE__)."/header.php"); 
?> 
<script language="JavaScript">
	
	var available_conditions = new Object();
	function popup_init() {

		var data = owner.bodycopy_current_edit["data"]["attributes"];
		available_conditions = owner.bodycopy_current_edit["data"]["available_conditions"];

		var f = document.main_form;
		f.height.value  = (data['height'] == null)  ? "" : data['height'];
		f.bgcolor.value = (data['bgcolor'] == null) ? "" : data['bgcolor'];

		var i = 2;
		f.showif.options[0] = new Option('[ Always Show ]', '');
		f.showif.options[1] = new Option('[ Do Not Show ]', 'Do Not Show');
		for(var key in available_conditions) {
			if (available_conditions[key] == null) continue;
			if(available_conditions[key]["name"] != null) {
				f.showif.options[i] = new Option(available_conditions[key]["name"], key);
				i++;
			}
		}

		owner.highlightComboElement(f.showif, data['showif']);
		f.showif_conds.value = (typeof data['showif_conds'] == 'undefined') ? '' : data['showif_conds'];

		// remove focus because Netscape seems to scroll to the top of the page
		// when the focus happens (probably because the element is not visisble
		// when the focus is run
		//f.height.focus();

	}// end popup_init()

	function save_props(f) {

		var data = new Object();
		data["height"]  = owner.elementValue(f.height);
		data["bgcolor"] = owner.elementValue(f.bgcolor);
		data["showif"] = owner.elementValue(f.showif);
		data["showif_conds"]	= owner.elementValue(f.showif_conds);
		owner.bodycopy_save_table_row_properties(data);
	}
</script>
<table width="100%" border="0" class="bodycopy-popup-table">
<form name="main_form">
	<tr>
		<td nowrap class="bodycopy-popup-heading">Edit Table Row Properties&nbsp;</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="4">
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Height :</td>
					<td valign="middle">
						<input type="text" name="height" value="" size="5">
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Background Colour :</td>
					<td valign="middle">
						<?=colour_box('bgcolor', '', true, 'owner', '*','text', false, false);?>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Show If :</td>
					<td>
						<select name="showif">
							<option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Show If Conditions:</td>
					<td>
						<?
						# The conditions might need to know the page and site id's so lets work them out and pass them along.
						$web  = &get_web_system();
						$page = &$web->get_page();
						$pageid = $page->id;
						$siteid = $page->siteid;
						?>
						<script type="text/javascript" language="javascript">
							function edit_cond() {
								var f = document.main_form;
								link = "<?= $_BODYCOPY['pop_up_prefix']; ?>/edit_conditions.php&siteid=<?=$siteid?>&pageid=<?=$pageid?>&condition=" + owner.elementValue(f.showif) + '&page_width=100%&page_height=100%';
								window.open(link, 'conditions', 'width=200,height=200');
						}
						</script>
						<input type="button" onclick="javascript: edit_cond();" value="Customise">
						<input type="hidden" name="showif_conds" value="<val_type>string</val_type> <val></val>">
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
			<input type="button" value="Save" onclick="javascript: save_props(this.form)">
			<input type="button" value="Cancel" onclick="javascript: popup_close();">
		</td>
	</tr>
</form>
</table>
<? include(dirname(__FILE__)."/footer.php"); ?> 
