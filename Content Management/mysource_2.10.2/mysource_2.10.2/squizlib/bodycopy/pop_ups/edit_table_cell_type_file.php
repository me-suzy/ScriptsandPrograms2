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
## $Source: /home/cvsroot/squizlib/bodycopy/pop_ups/edit_table_cell_type_file.php,v $
## $Revision: 2.9 $
## $Author: dofford $
## $Date: 2004/02/23 02:29:05 $
#######################################################################

# because this page gets the list of files each time is shouldn't be cached
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
header('Expires: '. gmdate('D, d M Y H:i:s',time()-3600) . ' GMT');
include(dirname(__FILE__).'/header.php'); 
$pageid = $_GET['pageid'];
?> 
<script language="JavaScript">

	function popup_init() {

		var data = owner.bodycopy_current_edit['data'];
		var f = document.main_form;
		owner.highlightComboElement(f.fileid, data['fileid']);
		// if the file isn't in the list it must be and "Other FILE ID"
		if (owner.elementValue(f.fileid) == '') {
			f.other_fileid.value = data['fileid'];
		}
		owner.highlightComboElement(f.embed, data['embed']);
		f.embed_width.value  = data['embed_options']['width'];
		f.embed_height.value = data['embed_options']['height'];
		owner.highlightComboElement(f.embed_loop,          data['embed_options']['loop']);
		owner.highlightComboElement(f.embed_auto_start,    data['embed_options']['auto_start']);
		owner.highlightComboElement(f.embed_show_controls, data['embed_options']['show_controls']);

		// remove focus because Netscape seems to scroll to the top of the page
		// when the focus happens (probably because the element is not visisble
		// when the focus is run
		//f.width.focus();

	}// end popup_init()

	function save_props(f) {

		var fileid = f.other_fileid.value;
		if (fileid == '') {
			fileid = owner.elementValue(f.fileid);
		}
		var embed = owner.elementValue(f.embed);
		var embed_options = new Object();
		embed_options['loop']          = owner.elementValue(f.embed_loop);
		embed_options['width']         = owner.elementValue(f.embed_width);
		embed_options['height']        = owner.elementValue(f.embed_height);
		embed_options['auto_start']    = owner.elementValue(f.embed_auto_start);
		embed_options['show_controls'] = owner.elementValue(f.embed_show_controls);

		owner.bodycopy_save_table_cell_type_file(fileid, embed, embed_options);

	}

 	function add_new_file(fileid, filename) {
		var f = document.main_form
		var option_num = f.fileid.options.length;
		f.fileid.options[option_num] = new Option(filename, fileid);
		f.fileid.selectedIndex = option_num;
	}

	if (is_ie4up || is_dom) {
		save_popup_fileid = add_new_file;
	} else {
		owner.save_popup_fileid = add_new_file;
	}

	var new_file_window = null;
	function popup_new_file() {
		if (new_file_window && !new_file_window.closed) new_file_window.close();

		new_file_window = window.open('<?=$_BODYCOPY['pop_up_prefix']?>edit_table_cell_type_file_new_file.php&page_width=580&page_height=580&pageid=<?=$pageid?>', 'new_file_window_popup', 'toolbar=no,width=600,height=600,nominimize,nomaximize,norestore,scrollbars=yes,resizable=yes');
		
	}
</script>
<table width="100%" border="0">
<form name="main_form">
	<tr>
		<td nowrap class="bodycopy-popup-heading">Edit Table Cell Properties&nbsp;</td>
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
					<td class="bodycopy-popup-heading" valign="top">New File/Image :</td>
					<td>
						<input type="button" value="New" onclick="javascript: popup_new_file();">
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bodycopy-popup-heading" valign="top">Existing File/Image :</td>
					<td valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						Files Attached to this page :
					</td>
					<td>
					<?
						$web  = &get_web_system($pageid);
						$page = &$web->get_page();
						if ($page->id) {
							$fileids = &$page->file_index;
							$files = Array('blankfileid' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
							foreach($fileids as $id) {
								$file = &$page->get_file($id);
								$files[$id] = $file->filename;
							}
							echo combo_box('fileid', $files, '', ' onChange="javascript: this.form.other_fileid.value = \'\'"');
						}#end if pageid

					?>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						Other File ID :
					</td>
					<td>
						<input type="text" name="other_fileid" value="" size="5" onChange="javascript: owner.highlightComboElement(this.form.fileid, '');">
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td valign="top">
						<b>Embed Object :</b>
					</td>
					<td>
						<?=combo_box('embed', Array('1' => 'Yes, if the file is embeddable', '0' => 'No'));?>
						<div class="warning">
							NOTE: wmv, asf and asx files will not work on pages that are restricted
						</div>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td valign="top">
						Embed Options :
					</td>
					<td>
						<table border="0" cellspacing="2" cellpadding="0" width="100%">
							<tr>
								<td>&nbsp;</td>
								<td>
									Size :
								</td>
								<td>
									<?=text_box('embed_width', '', 5)?><span class="smallprint"> (w) x </span><?=text_box('embed_height', '', 5)?><span class="smallprint"> (h)</span>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									Auto Start :
								</td>
								<td>
									<?=combo_box('embed_auto_start', Array('1' => 'Yes', '0' => 'No'));?>
									<span class="smallprint">( wmv, asf and asx files only )</span>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									Show Controls:
								</td>
								<td>
									<?=combo_box('embed_show_controls', Array('1' => 'Yes', '0' => 'No'));?>
									<span class="smallprint">( mov, wmv, asf and asx files only )</span>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									Loop:
								</td>
								<td>
									<?=combo_box('embed_loop', Array('1' => 'Yes', '0' => 'No'));?>
								</td>
							</tr>
						</table>
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
<? include(dirname(__FILE__).'/footer.php'); ?> 