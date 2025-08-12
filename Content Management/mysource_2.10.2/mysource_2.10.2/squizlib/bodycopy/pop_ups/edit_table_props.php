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
## $Source: /home/cvsroot/squizlib/bodycopy/pop_ups/edit_table_props.php,v $
## $Revision: 2.20.2.2 $
## $Author: tbarrett $
## $Date: 2004/05/18 00:43:04 $
#######################################################################
# because this page gets the list of files each time is shouldn't be cached
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Expires: ". gmdate("D, d M Y H:i:s",time()-3600) . " GMT");

include(dirname(__FILE__)."/header.php");
global $_BODYCOPY;
?>
<script language="JavaScript">

	var available_types		 = new Object();
	var available_conditions = new Object();
	function popup_init() {

		var data = owner.bodycopy_current_edit["data"]["attributes"];
		available_types = owner.bodycopy_current_edit["data"]["available_types"];
		available_conditions = owner.bodycopy_current_edit["data"]["available_conditions"];

		var f = document.main_form;

		// remove the existing values
		for(var i = f.type.options.length - 1; i >= 0; i--) {
			f.type.options[i] = null;
		}

		var i = 1;
		f.type.options[0] = new Option(' ', ' ');
		for(var key in available_types) {
			if (available_types[key] == null) continue;
			if(available_types[key]["name"] != null) {
				f.type.options[i] = new Option(available_types[key]["name"], key);
				i++;
			}
		}

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
		
		f.width.value		= (data['width']	  == null) ? "" : data['width'];
		f.height.value		= (data['height']	  == null) ? "" : data['height'];
		f.bgcolor.value		= (data['bgcolor']	  == null) ? "" : (data['bgcolor']);
		var bgimage			= (data['background'] == null) ? "" : data['background'];
		bgfileid = bgimage.substr(5,bgimage.length);
		f.background.value = bgimage;
		owner.highlightComboElement(f.fileid,      bgfileid);
		owner.highlightComboElement(f.align,	   data['align']);
		owner.highlightComboElement(f.border,	   data['border']);
		owner.highlightComboElement(f.cellspacing, data['cellspacing']);
		owner.highlightComboElement(f.cellpadding, data['cellpadding']);
		owner.highlightComboElement(f.showif, data['showif']);
		f.showif_conds.value = (typeof data['showif_conds'] == 'undefined') ? '' : data['showif_conds'];
		owner.highlightComboElement(f.rawhtml, data['rawhtml']);

		f.tableid.value = owner.bodycopy_current_edit["data"]["tableid"];
		f.bodycopy_name.value = owner.bodycopy_current_edit["bodycopy_name"];

		// remove focus because Netscape seems to scroll to the top of the page
		// when the focus happens (probably because the element is not visisble
		// when the focus is run
		//f.width.focus();

	}// end popup_init()

	function popup_save(f) {

		var data = new Object();
		data["width"]			= owner.elementValue(f.width);
		data["height"]			= owner.elementValue(f.height);
		data["bgcolor"]			= owner.elementValue(f.bgcolor);
		data["background"]		= owner.elementValue(f.background);
		data["align"]			= owner.elementValue(f.align);
		data["border"]			= owner.elementValue(f.border);
		data["cellspacing"]		= owner.elementValue(f.cellspacing);
		data["cellpadding"]		= owner.elementValue(f.cellpadding);
		data["change_type"]		= owner.elementValue(f.type);
		data["showif"]			= owner.elementValue(f.showif);
		data["showif_conds"]	= owner.elementValue(f.showif_conds);
		data["rawhtml"]			= owner.elementValue(f.rawhtml);
		owner.bodycopy_save_table_properties(data);
	}

	function set_file_url(f, changed) {

		var fileid = "";
		if (changed == "fileid") {
			fileid = f.fileid.options[f.fileid.selectedIndex].value;
		} else if (changed == "other_fileid") {
			fileid = f.other_fileid.value;
			f.fileid.options[0].selected = true;
		} else {
		}

		if (fileid != "") {
			f.background.value = "./?f=" + fileid;
		} else {
			f.background.value = "";
		}

	}// end set_file_url()

</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<form name="main_form">
	<input type="hidden" name="bodycopy_name" value="">
	<input type="hidden" name="tableid" value="">
	<tr>
		<td nowrap align="left"><a href="javascript: owner.bodycopy_copy_table(document.main_form.bodycopy_name.value, document.main_form.tableid.value);" onmouseover="javascript: window.status='Copy Table'; return true;" onmouseout="javascript: window.status=''; return true;"><img src="<?=$_BODYCOPY['file_prefix']?>images/icons/copy.gif" width="20" height="20" border="0"></a>&nbsp;<a href="javascript: owner.bodycopy_delete_table(document.main_form.bodycopy_name.value, document.main_form.tableid.value);" onmouseover="javascript: window.status='Delete Table'; return true;" onmouseout="javascript: window.status=''; return true;"><img src="<?=$_BODYCOPY['file_prefix']?>images/icons/delete.gif" width="20" height="20" border="0"></a></td>
		<td nowrap class="bodycopy-popup-heading">Edit Table Properties&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="0" cellpadding="0" cellspacing="3">
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Width :</td>
					<td valign="middle">
						<input type="text" name="width" value="" size="5">
					</td>
				</tr>
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
					<td class="bodycopy-popup-heading">Background Image :</td>
					<td valign="middle">
						<input type="hidden" name="background" value="">
						<?
							# let's see if the passed URL can help us with a fileid
							if (ereg("^\\./\\?f=([0-9]+)", $url, $regs)){
								$fileid = $regs[1];
							}#end if

							$web  = &get_web_system();
							$page = &$web->get_page($pageid);

							if ($page->id) $fileids = &$page->file_index;
							else $fileids = Array();

							$files = Array("" => "");

							if ($fileids) {
								foreach($fileids as $id) {
									$file = &$page->get_file($id);
									if (is_image($file->filename)) {
										$files[$id] = $file->filename;
									}#end if
								}#end foreach
							}#end if
							echo combo_box("fileid", $files, $fileid, "onChange=\"javascript: set_file_url(this.form, 'fileid');\"",20);
						?>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Alignment :</td>
					<td valign="middle">
						<select name="align">
							<option value=""      >
							<option value="left"  >Left
							<option value="center">Centre
							<option value="right" >Right
						</select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Border :</td>
					<td valign="middle">
						<select name="border">
							<option value="" >
							<option value="0">0
							<option value="1">1
							<option value="2">2
							<option value="3">3
							<option value="4">4
							<option value="5">5
							<option value="6">6
							<option value="7">7
							<option value="8">8
							<option value="9">9
							<option value="10">10
						</select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Cell Spacing :</td>
					<td valign="middle">
						<select name="cellspacing">
							<option value="" >
							<option value="0">0
							<option value="1">1
							<option value="2">2
							<option value="3">3
							<option value="4">4
							<option value="5">5
							<option value="6">6
							<option value="7">7
							<option value="8">8
							<option value="9">9
							<option value="10">10
						</select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Cell Padding :</td>
					<td valign="middle">
						<select name="cellpadding">
							<option value="" >
							<option value="0">0
							<option value="1">1
							<option value="2">2
							<option value="3">3
							<option value="4">4
							<option value="5">5
							<option value="6">6
							<option value="7">7
							<option value="8">8
							<option value="9">9
							<option value="10">10
						</select>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">Change All Cell Types :</td>
					<td>
						<select name="type">
							<option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
							<option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
							<option value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						</select>
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
					# Further up we work out the page, we can just use the siteid from the current page. The conditions might need to know the page and site id's so we'll pass them along.
					$siteid = $page->siteid;
					$pageid = $page->id;
					?>
						<script type="text/javascript" language="javascript">
							function edit_cond() {
								var f = document.main_form;
								link = "<?= $_BODYCOPY['pop_up_prefix']; ?>/edit_conditions.php&siteid=<?=$siteid?>&pageid=<?=$pageid?>&condition=" + owner.elementValue(f.showif) + '&page_width=100%&page_height=100%';
								window.open(link, 'conditions', 'width=200,height=200,resizable');
						}
						</script>
						<input type="button" onclick="javascript: edit_cond();" value="Customise">
						<input type="hidden" name="showif_conds" value="<val_type>string</val_type> <val></val>">
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading">
						<table cellpadding=0 cellspacing=0><tr><td class="bodycopy-popup-heading" noWrap>No BodyCopy </td></tr></table>HTML:
					</td>
					<td>
						<select name="rawhtml">
							<option value="" >
							<option value="1">Yes
						</select>
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
			<input type="button" value="Save"   onClick="javascript: popup_save(this.form)">
			<input type="button" value="Cancel" onClick="javascript: popup_close();">
		</td>
	</tr>
</form>
</table>
<? include(dirname(__FILE__)."/footer.php"); ?> 