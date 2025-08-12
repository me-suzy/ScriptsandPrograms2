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
## $Source: /home/cvsroot/squizlib/wysiwyg/pop_ups/insert_url.php,v $
## $Revision: 1.9.2.1 $
## $Author: brobertson $
## $Date: 2004/05/19 13:43:03 $
#######################################################################
include_once(dirname(__FILE__).'/../../../web/init.php');
include_once(dirname(__FILE__).'/../../html_form/html_form.inc');
# because this page gets the list of files each time is shouldn't be cached
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
header('Expires: '. gmdate('D, d M Y H:i:s',time()-3600) . ' GMT');

$siteid = gpc_stripslashes($_GET['siteid']);
$pageid = gpc_stripslashes($_GET['pageid']);

$url_protocol_options = Array(
							''  => '',
							'http://'  => 'http://',
							'https://' => 'https://',
							'mailto:'  => 'mailto:',
							'ftp://'   => 'ftp://'
						);

$new_window_bool_options = Array(
								'toolbar'    => 'Tool Bar',
								'menubar'    => 'Menu Bars',
								'location'   => 'Location Bar',
								'status'     => 'Status Bar',
								'scrollbars' => 'Scroll Bars',
								'resizable'  => 'Resizable'
							);

$enable_file_upload   = gpc_stripslashes($_GET['enable_file_upload']);

?>
<html>
<head>
<style type="text/css">
	body { 
		background-color: #c0c0c0; 
	}
	td, input { 
		font-family: "MS Sans Serif"; font-size: xx-small; 
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
<title>Insert URL</title>
<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js','form_functions.js')?>"></script>
<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js','general.js')?>"></script>
<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js','debug.js')?>"></script>
<script language="JavaScript">

	var new_window_bool_options = new Array('<?=implode("','", array_keys($new_window_bool_options))?>');

	function init(f) {

		var e = '^(.+:(\/\/)?)?([^#]*)(#(.*))?$';
		var re = new RegExp(e, '');
		var results = re.exec(window.dialogArguments["url"]);

		set_url(results[1], results[3], results[5]);

		f.status_text.value	= window.dialogArguments["status_text"];

		enable_new_window(f, window.dialogArguments["new_window"]);
		f.width.value  = window.dialogArguments["new_window_options"]["width"];
		f.height.value = window.dialogArguments["new_window_options"]["height"];
		for(var i=0; i < new_window_bool_options.length; i++) {
			f.elements[new_window_bool_options[i]].checked = (window.dialogArguments["new_window_options"][new_window_bool_options[i]] == 1) ? true : false;
		}

		// add the anchors to the list
		for(var i = 0; i < window.dialogArguments["anchor_list"].length; i++) {
			f.anchor.options[f.anchor.options.length] = new Option(window.dialogArguments["anchor_list"][i], window.dialogArguments["anchor_list"][i]);
		}

	}// end init();

	function set_url(protocol, link, anchor, reset_the_iframe) {

		var f = document.edit;

		if (reset_the_iframe == null) reset_the_iframe = true;

		if (protocol != null) highlightComboElement(f.url_protocol, protocol);
		if (link     != null) f.url_link.value   = link;
		if (anchor   != null) {
			f.url_anchor.value = anchor;
			// if the anchor is blank, blank out the anchor drop down
			if (anchor == "") highlightComboElement(f.anchor, '');
		}

		if (reset_the_iframe) reset_iframe();

	}// end set_url()

	function reset_iframe() {

		var f = document.edit;
		var src = './insert_url_page_selector.php?link=' + escape(elementValue(f.url_link)) 
					+ '&siteid=<?=$siteid?>&pageid=<?=$pageid?>&enable_file_upload=<?=$enable_file_upload?>';

		set_page_selector_status('Loading, please wait...');
		document.getElementById('page_selector').src = src;

	}// end reset_iframe()

	function set_page_selector_status(text) {

		document.getElementById('page_selector_status').innerText = (text != '') ? text : ' ';

	}// end set_page_selector_status()

	function set_anchor(f) {

		var anchor = elementValue(f.anchor);
		if (anchor != "") {
			set_url('', '', anchor);
		}// end if

	}// end set_anchor()

	function enable_new_window(f, enable) {

		var bg_colour = '#' + ((enable == 1) ? 'ffffff' : 'c0c0c0');
		var disable = (enable != 1);

		// make sure that the new window box says what it's supposed to
		highlightComboElement(f.new_window, enable);

		f.width.disabled  = disable;
		f.height.disabled = disable;
		f.width.style.backgroundColor  = bg_colour;
		f.height.style.backgroundColor = bg_colour;
		for(var i=0; i < new_window_bool_options.length; i++) {
			f.elements[new_window_bool_options[i]].disabled = disable;
		}

	}// end enable_new_window()

	function save(f) {
		var retVal = new Object();

		retVal["url"]			= elementValue(f.url_protocol) 
									+ elementValue(f.url_link) 
									+ ((elementValue(f.url_anchor) != "") ? '#' + elementValue(f.url_anchor) : '');

		retVal["status_text"]	= elementValue(f.status_text);

		retVal["new_window"]   = elementValue(f.new_window);
		retVal["new_window_options"] = new Object();
		retVal["new_window_options"]["width"]  = elementValue(f.width);
		retVal["new_window_options"]["height"] = elementValue(f.height);
		for(var i=0; i < new_window_bool_options.length; i++) {
			retVal["new_window_options"][new_window_bool_options[i]] = (f.elements[new_window_bool_options[i]].checked) ? 1 : 0;
		}

		window.returnValue = retVal;
		window.close();
	}

	function cancel() {
		window.returnValue = null;
		window.close();
	}
</script>
</head>

<body topmargin="0" leftmargin="0" style="border: 0; margin: 0;" scroll="no" onLoad="javascript: init(document.edit);">
<form name="edit">
<table class="dlg" cellpadding="0" cellspacing="2" border="0" width="100%" height="100%">
	<tr>
		<td colspan="3">
			<table width="100%">
				<tr>
					<td nowrap>URL Info&nbsp;</td>
					<td valign="middle" width="100%"><hr width="100%"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="10">&nbsp;</td>
		<td colspan="2" valign="top" align="center">
			<table border="0" cellspacing="3" cellpadding="0">
				<tr>
					<td valign="top">Protocol<br><?= combo_box('url_protocol', $url_protocol_options, '', 'style="font-family: courier new; font-size: 11px;"'); ?></td>
					<td valign="top">Link<br><?= text_box('url_link',   '', 40, 0, 'onChange="javascript: reset_iframe();"'); ?></td>
					<td valign="top">&nbsp;<br>#</td>
					<td valign="top">Anchor<br><?= text_box('url_anchor', '', 20, 0, 'onChange="javascript: highlightComboElement(this.form.anchor, \'\'); if (elementValue(this.form.url_link) == \'\') { highlightComboElement(this.form.url_protocol, \'\'); }"'); ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table width="100%">
				<tr>
					<td nowrap>Pages and Files&nbsp;</td>
					<td valign="middle" width="100%"><hr width="100%"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<iframe id="page_selector" scrolling="auto" width="100%" height="100" marginwidth="0" marginheight="0" frameborder="no"></iframe>
			<div id="page_selector_status" style="color: red; font-size:8px;">&nbsp;</div>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table width="100%">
				<tr>
					<td nowrap>Anchors on Current Page&nbsp;</td>
					<td valign="middle" width="100%"><hr width="100%"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top">
			Anchors :
		</td>
		<td valign="top">
		<?
			echo combo_box('anchor', Array('' => ''), '', 'onChange="javascript: set_anchor(this.form);"');
		?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table width="100%">
				<tr>
					<td nowrap>Options :&nbsp;</td>
					<td valign="middle" width="100%"><hr width="100%"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="10">&nbsp;</td>
		<td valign="top">
			Status Bar Text :
		</td>
		<td valign="middle">
			<?= text_box('status_text', $status_text, 50); ?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top">
			New Window :
		</td>
		<td valign="middle">
			<?= combo_box('new_window', Array('0' => 'No', '1' => 'Yes'), $new_window, 'onChange="javascript: enable_new_window(this.form, elementValue(this));"'); ?><br>
			<br>
			New Window Options :
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
		<?
			$count = 0;
			foreach($new_window_bool_options as $var => $name) {
				$count++;
			?> 
					<td width="33%">
						<input type="checkbox" value="1" name="<?=$var?>" <?=($_GET[$var]) ? 'checked' : '';?>>
						<?=$name?>
					</td>
			<?
				if ($count % 3 == 0) {
					echo '</tr><tr>';
				}
			}#end foreach
		?>
				</tr>
				<tr>
					<td colspan="3">
						Size : <input type="text" value="<?=$_GET['width']?>" size="3" name="width"> (w) x <input type="text" value="<?=$_GET['height']?>" size="3" name="height"> (h)
					</td>
				</tr>
			</table>
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
