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
## $Source: /home/cvsroot/squizlib/wysiwyg/pop_ups/insert_url_page_selector.php,v $
## $Revision: 1.9 $
## $Author: dofford $
## $Date: 2004/03/08 01:09:42 $
#######################################################################
include_once(dirname(__FILE__).'/../../../web/init.php');

include_once(dirname(__FILE__).'/../../html_form/html_form.inc');
# because this page gets the list of files each time is shouldn't be cached
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
header('Expires: '. gmdate('D, d M Y H:i:s',time()-3600) . ' GMT');

$web   = &get_web_system();

# retrieve the current url link
$link = (isset($_GET['link'])) ? trim(gpc_stripslashes($_GET['link'])) : '';

# get the site, page and file id's of the page that we are editing
$current_siteid = (isset($_REQUEST['siteid'])) ? (int) gpc_stripslashes($_REQUEST['siteid']) : 0;
$current_pageid = (isset($_REQUEST['pageid'])) ? (int) gpc_stripslashes($_REQUEST['pageid']) : 0;

$action = (isset($_REQUEST['action'])) ? (int) gpc_stripslashes($_REQUEST['action']) : '';

# these are the id's for what the link actually points to, initially set to zero but when we start 
# refreshing ourselves we'll pass these through
$link_siteid = (isset($_REQUEST['link_siteid'])) ? (int) gpc_stripslashes($_REQUEST['link_siteid']) : 0;
$link_pageid = (isset($_REQUEST['link_pageid'])) ? (int) gpc_stripslashes($_REQUEST['link_pageid']) : 0;
$link_fileid = (isset($_REQUEST['link_fileid'])) ? (int) gpc_stripslashes($_REQUEST['link_fileid']) : 0;

$enable_file_upload   = gpc_stripslashes($_REQUEST['enable_file_upload']);

# if there is a link passed through check if it is in 
# a form we can understand, if so reset the site, page or file id's
if ($link != '') {
	if (preg_match('/^.\/\?([spf])=([0-9]+)$/', $link, $matches)) {
		# depending on what type of link it is get the id's from different places
		switch($matches[1]) {
			# a file link
			case 'f' :
				$link_fileid = (int) $matches[2];
			break;
			# a page link
			case 'p' :
				$link_pageid = (int) $matches[2];
			break;
			# a site link
			case 's' :
				$link_siteid = (int) $matches[2];
			break;
		}#end switch

		if ($link_fileid) {
			$file = &$web->get_file($link_fileid);
			if ($file->id) {
				$link_pageid = $file->pageid;
			} else {
				$link_fileid = 0;
			}#end if
		}#end if
				
		if ($link_pageid) {
			$page = &$web->get_page($link_pageid);
			if ($page->id) {
				$link_siteid = $page->siteid;
			} else {
				$link_pageid = 0;
				$link_fileid = 0;
			}#end if
		}#end if
				
		if ($link_siteid) {
			$site = &$web->get_site($link_siteid);
			if (!$site->id) {
				$link_siteid = 0;
				$link_pageid = 0;
				$link_fileid = 0;
			}#end if
		}#end if

	}#end if preg match

# else set the site and page to the current site and current page if they aren't set to anything
} else {
	if (!$link_siteid) {
		$link_siteid = $current_siteid;
		$link_pageid = $current_pageid;
	} elseif ($link_siteid != $current_siteid) {
		$link_pageid = null;
	}
}#end if 

$can_upload_file = ($link_pageid == $current_pageid);

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

	input, select { 
		font-family: Arial, Verdana, Sans-Serif; 
		font-size: 10px; 
	}

</style>
<title>Insert URL Page Selector</title>
<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js','form_functions.js')?>"></script>
<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js','general.js')?>"></script>
<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js','debug.js')?>"></script>
<script language="JavaScript">

	function init() {

		var f = document.edit
		self.parent.set_page_selector_status('');
		// if a page has been selected then give that combo the focus, 
		// otherwise give the site combo focus
		//if (elementValue(f.link_pageid) != '') f.link_pageid.focus();
		//else f.link_siteid.focus();


	}// end set_page_url()

	function set_site_url(f) {

		var link_siteid = elementValue(f.link_siteid);
		if (link_siteid != "") {
			self.parent.set_url("", "./?s=" + link_siteid, null, false);
			// now just refresh so that we get that sites page list appears correctly
			self.parent.set_page_selector_status('Refreshing, please wait...');
			f.submit();
		}// end if

	}// end set_page_url()

	function set_page_url(f, refresh) {

		var link_pageid = elementValue(f.link_pageid);
		if (link_pageid != "") {

			self.parent.set_url("", "./?p=" + link_pageid, null, false);
			if (refresh) {
				// now just refresh so that we get that pages file list appearing
				self.parent.set_page_selector_status('Refreshing, please wait...');
				f.submit();
			}
		// if they have selected the blank, then set the url to the site link
		} else {
			set_site_url(f);

		}// end if

	}// end set_page_url()

	function set_file_url(f) {

		var link_fileid   = elementValue(f.link_fileid);
		if (link_fileid != "") {
			self.parent.set_url("", "./?f=" + link_fileid, "", false);
		// if they have selected the blank, then set the url to the page link
		} else {
			set_page_url(f, false);
		}// end if

	}// end set_file_url()

	function save_fileid(fileid, filename) {
		var f = document.edit
		if (fileid != "") {
			var option_num = 0;
			option_num = f.link_fileid.options.length;
			f.link_fileid.options[option_num] = new Option(filename, fileid);
			f.link_fileid.selectedIndex = option_num;

			set_file_url(f);
		
		}// end if

	}// end save_fileid()

	save_popup_fileid = save_fileid;

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
		self.parent.set_page_selector_status(text);
	}// end set_new_file_upload_progress(text)

</script>
</head>

<body topmargin="0" leftmargin="0" onLoad="javascript: init();">
<form name="edit" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<input type="hidden" name="siteid" value="<?=$current_siteid?>">
<input type="hidden" name="pageid" value="<?=$current_pageid?>">
<input type="hidden" name="enable_file_upload" value="<?=$enable_file_upload?>">
<table class="dlg" cellpadding="0" cellspacing="2" border="0" width="100%">
	<tr>
		<td>&nbsp;</td>
		<td valign="top">
			Sites :
		</td>
		<td valign="top">
		<?
			$session = &get_mysource_session();
			$sites = $web->get_editable_sites($session->user->id);
			asort($sites);
			$sites[$current_siteid] = "[THIS SITE] ".$sites[$current_siteid];
			$sites = Array('' => '') + $sites;
			echo combo_box("link_siteid", $sites, $link_siteid, "onchange=\"set_site_url(this.form);\"");
		?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top">
			Pages :
		</td>
		<td valign="top">
		<?
			if (!$link_siteid) {
				echo 'No Site Selected';
			} else {
				$pages = $web->page_array_with_sticks($link_siteid, 0, '', '', true);
				$pages = Array('' => '') + $pages;
				echo combo_box('link_pageid', $pages, $link_pageid, 'onChange="javascript: set_page_url(this.form, true);"');
			}
		?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top">
			Files :
		</td>
		<td valign="top">
		<?
			if (!$link_pageid) {
				echo 'No Page Selected';
			} else {
				$page = &$web->get_page($link_pageid);
				if ($page->id) $fileids = &$page->file_index;
				else $fileids = Array();

				# if there are no files and the page that we are displaying isn't the current page
				# (which means that we can't add files to it, therefore don't need the blank combo)
				if (empty($fileids) && !$can_upload_file) {
					echo 'No Files Available';

				} else {
					$files = Array('' => '');
					if ($fileids) {
						foreach($fileids as $id) {
							$file = &$page->get_file($id);
							$files[$id] = $file->filename;
						}
					}
					echo combo_box('link_fileid', $files, $link_fileid, 'onChange="javascript: set_file_url(this.form);"');

				}#end if
			}#end if no link_pageid
		?>
		</td>
	</tr>
<?
# only show the new button if the page displayed is the current page we are on
if ($can_upload_file && $enable_file_upload) {
?> 
	<tr>
		<td>&nbsp;</td>
		<td valign="top">
			New File :
		</td>
		<td valign="top">
				<iframe id="new_file_uploader" src="new_file.php?siteid=<?=$current_siteid?>&pageid=<?=$current_pageid?>" scrolling="auto" width="100%" height="30" marginwidth="0" marginheight="0" frameborder="no"></iframe>
				<div id="new_file_uploader_status" style="color: red; font-size:8px;">&nbsp;</div>
		</td>
	</tr>
<?
}#end if
?>
</table>
</form>
</body>
</html>
