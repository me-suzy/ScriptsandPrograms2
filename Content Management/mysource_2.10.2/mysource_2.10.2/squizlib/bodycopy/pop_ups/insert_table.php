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
## $Source: /home/cvsroot/squizlib/bodycopy/pop_ups/insert_table.php,v $
## $Revision: 2.5 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:18 $
#######################################################################
include(dirname(__FILE__)."/header.php"); 
?> 
<script language="JavaScript">

	var available_types = new Object();
	function popup_init() {

		var data = owner.bodycopy_current_edit["data"];
		var f = document.main_form;
		available_types = owner.get_bodycopy_available_cell_types();

		// remove the existing values
		for(var i = f.type.options.length - 1; i >= 0; i--) {
			f.type.options[i] = null;
		}

		var i = 0;
		for(var key in available_types) {
			if (available_types[key] == null) continue;
			if(available_types[key]["name"] != null) {
				f.type.options[i] = new Option(available_types[key]["name"], key);
				i++;
			}
		}
		owner.highlightComboElement(f.type, data["type"]);
		show_desc(f);

		// remove focus because Netscape seems to scroll to the top of the page
		// when the focus happens (probably because the element is not visisble
		// when the focus is run
		//f.type.focus();

	}// end popup_init()

	function popup_save(f) {
		var data = new Object();
		data["width"]   = owner.elementValue(f.width);
		data["bgcolor"] = owner.elementValue(f.bgcolor);
		owner.bodycopy_save_insert_table(owner.elementValue(f.cols), owner.elementValue(f.rows), owner.elementValue(f.type), data);
	}

	function set_pos_int(field, input_default) {

		var num = parseInt(owner.elementValue(field));
		if (isNaN(num) || num < 0) {
			alert("Please enter a positive number\n");
			field.value = input_default;
			field.focus();
		} else {
			field.value = num;
		}// end if

	}// end set_pos_int()

	function show_desc(f) {
		if (available_types[owner.elementValue(f.type)] != null) {
			f.type_description.value = available_types[owner.elementValue(f.type)]["description"];
		}
	}// end show_desc()


</script>
<table border="0" width="100%">
<form name="main_form">
	<tr>
		<td nowrap class="bodycopy-popup-heading">Insert Table&nbsp;</td>
	</tr>
	<tr>
		<td>
			<hr>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="4">
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading"># of Columns:</td>
					<td>
						<input type="text" name="cols" value="1" size="3" onChange="javascript: set_pos_int(this, 1);">
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading"># of Rows:</td>
					<td>
						<input type="text" name="rows" value="1" size="3" onChange="javascript: set_pos_int(this, 1);">
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Width:</td>
					<td>
						<input type="text" name="width" value="" size="4">
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Background Colour:</td>
					<td>
						<?=colour_box('bgcolor', '', true, 'owner','*','text', false, false);?>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Default Cell Type :</td>
					<td>
						<select name="type" onChange="javascript: show_desc(this.form);">
							<option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
							<option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
							<option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td valign="middle">
						<textarea name="type_description"  onFocus="javascript: this.blur();" style="font-family: courier, monospace;" rows="6" cols="<?=(($browser == "ie") ? "25" : "20")?>" wrap="virtual"></textarea>
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
			<input type="button" name="save_button" value="Save" onclick="javascript: popup_save(this.form)">
			<input type="button" value="Cancel" onclick="javascript: popup_close();">
		</td>
	</tr>
</form>
</table>
<? include(dirname(__FILE__)."/footer.php"); ?>