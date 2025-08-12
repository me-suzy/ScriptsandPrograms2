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
## $Source: /home/cvsroot/squizlib/wysiwyg/pop_ups/insert_image.php,v $
## $Revision: 1.14 $
## $Author: dofford $
## $Date: 2004/03/08 01:09:42 $
#######################################################################
require_once(dirname(__FILE__).'/../../../web/init.php');
require_once(dirname(__FILE__).'/../../html_form/html_form.inc');
# because this page gets the list of files each time is shouldn't be cached
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
header('Expires: '. gmdate('D, d M Y H:i:s',time()-3600) . ' GMT');

$url      = gpc_stripslashes($_GET['url']);
$align    = gpc_stripslashes($_GET['align']);
$alt      = gpc_stripslashes($_GET['alt']);
$vspace   = gpc_stripslashes($_GET['vspace']);
$hspace   = gpc_stripslashes($_GET['hspace']);
$border   = gpc_stripslashes($_GET['border']);
$enable_file_upload   = gpc_stripslashes($_GET['enable_file_upload']);
$enable_file_delete   = gpc_stripslashes($_GET['enable_file_delete']);

?>
<html>
<head>
<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js', 'form_functions.js');?>"></script>
<style type="text/css">
	body { 
		background-color: #c0c0c0; 
	}
	td, input { 
		font-family: "MS Sans Serif"; font-size: xx-small; vertical-align: middle; 
	}
	select { 
		font-family: "Courier, monospace"; 
		font-size: xx-small; 
		vertical-align: middle; 
	}
	table.dlg { 
		border:0; 
	}
	.dlg td { 
		align: left; height: 20; 
	}
	.dlg input { 
		border-size: 2px; 
	}
	input.button { 
		border-top: 1px solid white; 
		border-left: 1px solid white;
		border-bottom: 1px solid black; 
		border-right: 1px solid black;
		font-size: x-small; 
		width: 60; 
	}
	select { 
		height: 75%; 
	}
</style>
<title>Insert Image</title>
<script language="JavaScript">

	function set_file_url(f, changed) {
		var fileid = "";
		if (changed == "fileid") {
			fileid = f.fileid.options[f.fileid.selectedIndex].value;
			f.other_fileid.value = "";
		} else if (changed == "other_fileid") {
			fileid = f.other_fileid.value;
			f.fileid.options[0].selected = true;
		} else {
			f.fileid.options[0].selected = true;
			f.other_fileid.value = "";
			f.url.value = '';
		}
		
		if (fileid != "") {
			f.url.value = "./?f=" + fileid;
		}// end if

	}// end set_file_url()

	function save(f) {
		var retVal = new Object();
		retVal["url"]    = f.url.value;
		retVal["alt"]    = f.alt.value;
		retVal["hspace"] = f.hspace.options[f.hspace.selectedIndex].value;
		retVal["vspace"] = f.vspace.options[f.vspace.selectedIndex].value;
		retVal["border"] = f.vspace.options[f.border.selectedIndex].value;
		retVal["align"]  = f.align.options[f.align.selectedIndex].value;
		retVal["del_fileids"]  = del_fileids;

		window.returnValue = retVal;
		window.close();
	}

	function save_fileid(fileid, filename) {
		var f = document.edit
		if (fileid != "") {
			var option_num = 0;
			option_num = f.fileid.options.length;
			f.fileid.options[option_num] = new Option(filename, fileid);
			f.fileid.selectedIndex = option_num;

			set_file_url(f, 'fileid');
		
		}// end if

	}// end save_fileid()

	function del_fileid(fileid) {
		var f = document.edit
		if (fileid != '') {
			for(var i = 0; i < f.fileid.options.length; i++) {
				if (f.fileid.options[i].value == fileid) {
					f.fileid.options[i] = null;
					set_file_url(f, '');
					del_fileids.push(fileid);
				}
			}
		
		}// end if

	}// end save_fileid()

	var del_fileids = new Array();
	save_popup_fileid = save_fileid;

	function cancel() {
		var retVal = new Object();
		retVal["del_fileids"]  = del_fileids;
		window.returnValue = retVal;
		window.close();
	}


	var file_upload_progress_interval_id = 0;
	var file_upload_progress_counter     = 0;
	var file_upload_progress_counter_str = '';
	function start_new_file_upload_progress() {
		file_upload_progress_counter     = 0;
		file_upload_progress_interval_id = setInterval(increment_new_file_upload_progress, 250);
	}// end start_new_file_upload_progress()

	function increment_new_file_upload_progress() {

		if (file_upload_progress_counter == 0) {
			file_upload_progress_counter_str = 'Uploading ';
			set_new_file_upload_progress(file_upload_progress_counter_str);
			file_upload_progress_counter++;
			return;

		} else if (file_upload_progress_counter > 30) {
			file_upload_progress_counter = -1;
		} 

		file_upload_progress_counter_str += '-';
		set_new_file_upload_progress(file_upload_progress_counter_str + '>');
		file_upload_progress_counter++;

	}// end stop_new_file_upload_progress()

	function stop_new_file_upload_progress() {
		if (file_upload_progress_interval_id) clearInterval(file_upload_progress_interval_id);
		file_upload_progress_counter_str  = ' ';
		set_new_file_upload_progress(file_upload_progress_counter_str);
	}// end stop_new_file_upload_progress()

	function set_new_file_upload_progress(text) {
		document.getElementById('new_file_uploader_status').innerText = (text != '') ? text : ' ';
	}// end set_new_file_upload_progress(text)


</script>
</head>
<body topmargin="0" leftmargin="0" style="border: 0; margin: 0;" scroll="no">
<table class="dlg" cellpadding="0" cellspacing="2" border="0" width="100%" height="100%">
	<tr>
		<td colspan="3">
			<table width="100%">
				<tr>
					<td nowrap>Images&nbsp;</td>
					<td valign="middle" width="100%"><hr width="100%"></td>
				</tr>
			</table>
		</td>
	</tr>
<? 
if ($enable_file_upload) {
?>
	<tr>
		<td>&nbsp;</td>
		<td>New Image :<br><br></td>
		<td>
			<iframe id="new_file_uploader" src="new_file.php?siteid=<?=$_REQUEST['siteid']?>&pageid=<?=$_REQUEST['pageid']?>" scrolling="auto" width="100%" height="30" marginwidth="0" marginheight="0" frameborder="no"></iframe>
			<div id="new_file_uploader_status" style="color: red; font-size:8px;">&nbsp;</div>
		</td>
	</tr>
<?
}#end if
?>
<form name="edit">
	<tr>
		<td>&nbsp;</td>
		<td>
			Images Attached <br>
			to this page :
		</td>
		<td>
			<table>
				<tr>
					<td>
						<?
							# let's see if the passed URL can help us with a fileid
							if (ereg("^\\./\\?f=([0-9]+)", $url, $regs)){
								$fileid = $regs[1];
							}#end if

							$web  = &get_web_system();
							$page = &$web->get_page($pageid);

							if ($page->id) $fileids = &$page->file_index;
							else $fileids = Array();

							$files = Array('' => '');

							if ($fileids) {
								foreach($fileids as $id) {
									$file = &$page->get_file($id);
									if (is_image($file->filename)) {
										$files[$id] = $file->filename;
									}#end if
								}#end foreach
							}#end if
							echo combo_box('fileid', $files, $fileid, 'onChange="javascript: set_file_url(this.form, \'fileid\');"');
						?>
					</td>
<? 
if ($enable_file_delete) {
?> 
					<td>
						<iframe id="del_file_iframe" src="del_file.php" scrolling="auto" width="100%" height="30" marginwidth="0" marginheight="0" frameborder="no"></iframe>
					</td>
<? 
}
?> 
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><b>OR</b></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			Other File ID :
		</td>
		<td>
			<input type="text" name="other_fileid" value="" size="5" onKeyUp="javascript: set_file_url(this.form, 'other_fileid');">
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<hr width="100%">
		</td>
	</tr>
	<tr>
		<td width="10">&nbsp;</td>
		<td>Align :</td>
		<td valign="middle">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top">
					<? 
						$alignment_options =  Array(''       => '',
													'left'   => 'Left',
													'right'  => 'Right');

						echo combo_box('align', $alignment_options, strtolower($align));
					?>
					</td>
					<td>&nbsp;&nbsp;</td>
					<td valign="top">
						(relative to the text, not page - allows text wrapping around images)
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
		<tr>
		<td width="10">&nbsp;</td>
		<td>Border :</td>
		<td valign="middle">
			<? 
			$border_options =  Array(	''    => '',
										'0'   => '0',
										'1'   => '1',
										'2'   => '2',
										'3'   => '3',
										'4'   => '4',
										'5'   => '5',
										'6'   => '6',
										'7'   => '7',
										'8'   => '8',
										'9'   => '9',
										'10'  => '10');

			echo combo_box('border', $border_options, $border);
			?>
		</td>
	</tr>
	<tr>
		<td width="10">&nbsp;</td>
		<td>Hspace :</td>
		<td valign="middle">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top">
					<? 
						$hspace_options =  Array(	''    => '',
													'0'   => '0',
													'1'   => '1',
													'2'   => '2',
													'3'   => '3',
													'4'   => '4',
													'5'   => '5',
													'6'   => '6',
													'7'   => '7',
													'8'   => '8',
													'9'   => '9',
													'10'  => '10');

						echo combo_box('hspace', $hspace_options, $hspace);
					?>
					</td>
					<td>&nbsp;&nbsp;</td>
					<td valign="top">
						(horizontal spacing)
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="10">&nbsp;</td>
		<td>Vspace :</td>
		<td valign="middle">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top">
					<? 
						$vspace_options =  Array(	''    => '',
													'0'   => '0',
													'1'   => '1',
													'2'   => '2',
													'3'   => '3',
													'4'   => '4',
													'5'   => '5',
													'6'   => '6',
													'7'   => '7',
													'8'   => '8',
													'9'   => '9',
													'10'  => '10');

						echo combo_box('vspace', $vspace_options, $vspace);
					?>
					</td>
					<td>&nbsp;&nbsp;</td>
					<td valign="top">
						(vertical spacing)
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="10">&nbsp;</td>
		<td>
			Image ALT text :
		</td>
		<td valign="middle">
			<?= text_box('alt', $alt, 40, '')?>
		</td>
	</tr>
	<tr>
		<td width="10">&nbsp;</td>
		<td>
			Image URL :
		</td>
		<td valign="middle">
			<?= text_box('url', $url, 40, '', 'onKeyUp="javascript: set_file_url(this.form, \'\');"'); ?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<hr width="100%">
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2" align="center">
			<input class="button" type="button" value="Insert" onclick="javascript: save(this.form);">
			<input class="button" type="button" value="Cancel" onclick="javascript: cancel();">
		</td>
	</tr>
</table>
</form>
</body>
</html>