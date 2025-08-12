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
## $Source: /home/cvsroot/squizlib/bodycopy/pop_ups/edit_table_cell_type_richtext.php,v $
## $Revision: 2.6 $
## $Author: ramato $
## $Date: 2003/07/15 01:04:33 $
#######################################################################
# because this page gets the list of files each time is shouldn't be cached
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Expires: ". gmdate("D, d M Y H:i:s",time()-3600) . " GMT");
include(dirname(__FILE__)."/header.php"); 

global $browser;

$pageid = $_REQUEST['pageid'];

?> 
<script language="JavaScript">
	
	function popup_init() {

		var data = owner.bodycopy_current_edit["data"]["text"];
		var f = document.main_form;
		f.text.value = (data == null)  ? '' : data;
		// remove focus because Netscape seems to scroll to the top of the page
		// when the focus happens (probably because the element is not visisble
		// when the focus is run
		//f.text.focus();

	}// end popup_init()

	function popup_save(f) {
		owner.bodycopy_save_table_cell_type_richtext(owner.elementValue(f.text));
	}

	function set_copy_box(f, value, field) {

		// set all the field that the value doesn't belong to to blank
		if (field.indexOf('image_fileid') < 0 && f.image_fileid != null && f.image_fileid.options.length > 0)
			f.image_fileid.options[0].selected = true;

		if (field.indexOf('pageid') < 0 && f.pageid != null && f.pageid.options.length > 0) 
			f.pageid.options[0].selected = true;

		if (field.indexOf('file_fileid') < 0 && f.file_fileid != null && f.file_fileid.options.length > 0) 
			f.file_fileid.options[0].selected = true;

		if (field.indexOf('url') < 0)
			f.url.value = 'http://';
		
		f.copy_box.value = value;

	}// end set_copy_box()
	
	function create_img_tag(f, changed) {

		var tag = get_img_tag(f, changed);
		if (tag != '') {
			set_copy_box(f, tag, changed);
		} else {
			set_copy_box(f, '', '');
		}// end if

	}// end create_img_tag()

	function get_img_tag(f, changed) {

		var fileid = '';
		if (changed == 'image_fileid') {
			fileid = owner.elementValue(f.elements[changed]);
		}
		
		if (fileid != '') {
			return '<img src="./?f=' + fileid + '" alt="[ALT text here]" border="0">';
		} else {
			return '';
		}// end if

	}// end get_img_tag()

	function add_new_file(fileid, filename) {
		var f = document.main_form
		var option_num = 0;

		option_num = f.file_fileid.options.length;
		f.file_fileid.options[option_num] = new Option(filename, fileid);
		f.file_fileid.selectedIndex = option_num;

		var lc_filename = filename.toLowerCase();
		// if it's an image then add to the image box
		if (lc_filename.indexOf('.jpg') > -1 || lc_filename.indexOf('.gif') > -1 || lc_filename.indexOf('.png') > -1) {
			option_num = f.image_fileid.options.length;
			f.image_fileid.options[option_num] = new Option(filename, fileid);
			f.image_fileid.selectedIndex = option_num;
			create_img_tag(f, 'image_fileid');
		} else {
			create_url(f, 'file_fileid');
		}// end if

	}// add_new_file()

	if (is_ie4up || is_dom) {
		save_popup_fileid = add_new_file;
	} else {
		owner.save_popup_fileid = add_new_file;
	}

	var new_file_window = null;
	function popup_new_file() {
		if (new_file_window && !new_file_window.closed) new_file_window.close();
		new_file_window = window.open('<?=$_BODYCOPY["pop_up_prefix"]?>edit_table_cell_type_file_new_file.php&upload_only=1&page_width=380&page_height=230&pageid=<?=$pageid?>', 'new_file_window_popup', 'toolbar=no,width=400,height=250,nominimize,nomaximize,norestore,scrollbars=yes,resizable=yes');

	}

	function create_url(f, changed) {

		var url = '';
		switch (changed) {

			case 'pageid' :
				var pageid = owner.elementValue(f.pageid);
				if (pageid != '') { 
					url = './?p=' + pageid;
				}
			break;
			
			case 'file_fileid' :
				var fileid = owner.elementValue(f.file_fileid);
				if (fileid != '') { 
					url = './?f=' + fileid;
				}
			break;

			case 'url' :		
				url = owner.elementValue(f.url);
			break;

		}// end if

		if (url != '') {
			var href = '<a href="' + url + '">'
			var contents = '';
			if (f.image_link.checked) {
				contents = get_img_tag(f, 'image_fileid');
			}
			if (contents != '') {
				href += contents;
				changed += ',image_fileid';
			} else {
				href += '[ LINKED TEXT HERE ]';
			}
			href += '</a>';
			set_copy_box(f, href, changed);
		} else {
			set_copy_box(f, '', '');
		}// end if

	}// end create_url()


</script>
<table width="100%" border="0">
<form name="main_form">
	<tr>
		<td nowrap colspan="3" class="bodycopy-popup-heading">Rich Text Edit of Cell Contents&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3"><hr></td>
	</tr>
	<tr>
		<td class="bodycopy-popup-heading" valign="top">Text :</td>
		<td valign="middle" align="center" colspan="2">
			<div style="font-family: courier new, monospace; font-size: 9pt;">
				<textarea name="text" wrap="virtual" style="font-family: courier new, monospace; font-size: 9pt;" rows="10" cols="<?=(($browser == "ie") ? "80" : "60") ?>"></textarea>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<hr>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			Copy :
		</td>
		<td>
			<input type="text" name="copy_box" value="" size="<?=(($browser == "ns") ? "40" : "60") ?>" onFocus="javascript: this.select(); return true;">
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<hr>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top">New Image/File :</td>
		<td>
			<input type="button" value="New" onclick="javascript: popup_new_file();">
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			Attached Images :
		</td>
		<td> 
		<?
			$web  = &get_web_system();
			$page = &$web->get_page($pageid);
			if ($page->id) $fileids = &$page->file_index;
			else $fileids = Array();

			if ($fileids) {
				$files = Array("" => "");
				foreach($fileids as $id) {
					$file = &$page->get_file($id);
					if (is_image($file->filename)) {
						$files[$id] = $file->filename;
					}
				}
			}
			echo combo_box("image_fileid", $files, "", "onChange=\"javascript: create_img_tag(this.form, 'image_fileid');\"");
		?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<hr width="80%">
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			&nbsp;
		</td>
		<td valign="middle">
			<input type="checkbox" name="image_link" checked value="1">&nbsp;Use currently selected image when creating links
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			Site Pages :
		</td>
		<td valign="middle">
		<?
			$pages = $web->page_array_with_sticks($siteid);
			$pages = Array("" => "") + $pages;
			echo combo_box("pageid", $pages, "", "onChange=\"javascript: create_url(this.form, 'pageid');\" style=\"font-family: courier new;\"", 75);
		?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			Attached Files :
		</td>
		<td> 
		<?
			$files = Array("" => "");
			if ($fileids) {
				foreach($fileids as $id) {
					$file = &$page->get_file($id);
					$files[$id] = $file->filename;
				}
			}
			echo combo_box("file_fileid", $files, "", "onChange=\"javascript: create_url(this.form, 'file_fileid');\"");
		?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			Other URL :
		</td>
		<td>
			<input type="text" name="url" value="http://" size="<?=(($browser == "ie") ? "60" : "40") ?>" onKeyUp="javascript: create_url(this.form, 'url');">
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<hr>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="3">
			<input type="button" value="Save" onclick="javascript: popup_save(this.form)">
			<input type="button" value="Cancel" onclick="javascript: popup_close();">
		</td>
	</tr>
</form>
</table>
<? include(dirname(__FILE__)."/footer.php"); ?> 