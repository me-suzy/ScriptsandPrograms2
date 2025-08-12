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
## $Source: /home/cvsroot/squizlib/wysiwyg/pop_ups/new_file.php,v $
## $Revision: 1.10 $
## $Author: ramato $
## $Date: 2003/06/13 06:14:31 $
#######################################################################
require_once(dirname(__FILE__).'/../../../web/squizlib_init.php');

$web = &get_web_system();
require_once($INCLUDE_PATH.'/file.inc');
$image_prefix  = squizlib_href('wysiwyg', 'images');

$onload_extra = '';

# process the form
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'Commit') {

	$file = new File(0);
	$file->visible = 'N'; # *cough* can't call set_visible because the file ain't created yet
	$file->print_backend(true, true, true, true);

	# if the file object has a fileid, 
	# then it has been submitted, so we can save and close
	if ($file->id) {
		$onload_extra .= "self.parent.stop_new_file_upload_progress();\n";
		$onload_extra .= "self.parent.save_popup_fileid($file->id, '".addslashes($file->filename)."');\n";
		$onload_extra .= "self.parent.set_new_file_upload_progress('Upload Complete');\n";
	} else {
		$onload_extra .= "self.parent.stop_new_file_upload_progress();\n";
		$onload_extra .= "self.parent.set_new_file_upload_progress('Upload Failed');\n";
	}

}#end if

# because this page gets the list of files each time is shouldn't be cached
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Expires: ". gmdate("D, d M Y H:i:s",time()-3600) . " GMT");
?>
<html>
<head>
<style type="text/css">
	body { 
		background-color: #c0c0c0;
	}
	td { 
		font-family: Arial, Verdana, Sans-Serif; 
		font-size: 12px; 
	}
	input, select { 
		font-family: Arial, Verdana, Sans-Serif; 
		font-size: 10px; 
	}
</style>
<script language="JavaScript" src="<?=squizlib_href('js','detect.js');?>"></script>
<script language="JavaScript">

	if (is_ie4up || is_dom) {
		var owner = parent;
	} else {
		var owner = window;
	}// end if

	function init() {

		var f = document.edit
		<?=$onload_extra?>

	}// end init()

	function upload(f) {

		if(f.thefile.value.length > 5){ 
			self.parent.start_new_file_upload_progress();
			f.submit();
		}// end if

	}// end upload()


</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onLoad="javascript: init();">
<?
/*
	I don't really like how this is done by hard-coding the form values
	but changing print_backend() in file to allow essentially this very basic form
	to be printed would mess that function up quite nastilly
*/
$page = &$web->get_page($_REQUEST['pageid']);
?>
	<form name="new_image" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="siteid"       value="<?=$_REQUEST['siteid']?>">
		<input type="hidden" name="pageid"       value="<?=$_REQUEST['pageid']?>">
		<input type="hidden" name="new_pageid"   value="<?=$_REQUEST['pageid']?>">
		<input type="hidden" name="action"       value="Commit">
		<input type="hidden" name="file_edit"    value="true">	
		<input type="hidden" name="process_only" value="1">	
		<input type="hidden" name="visible"      value="N">
		<input type="hidden" name="description"  value="">
		<input type="hidden" name="keywords"     value="">
		<?=file_upload('thefile', $page->data_path, 20000000);?>
		<input type="button" value="Upload!" onClick="javascript: upload(this.form);">
	</form>
</body>
</html>