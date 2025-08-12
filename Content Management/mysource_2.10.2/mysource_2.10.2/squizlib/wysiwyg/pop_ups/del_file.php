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
## $Source: /home/cvsroot/squizlib/wysiwyg/pop_ups/del_file.php,v $
## $Revision: 1.1 $
## $Author: dofford $
## $Date: 2004/03/04 01:30:02 $
#######################################################################
require_once(dirname(__FILE__).'/../../../web/squizlib_init.php');

$web = &get_web_system();
require_once($INCLUDE_PATH.'/file.inc');

$onload_extra='';

# process the form
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'Delete' && ($fileid = $_REQUEST['fileid']) >= 0) {
	$file = new File($fileid);

	# if we have a file object then delete it
	if ($file->id) {
		$file->print_backend(true, true, true, true);
		$onload_extra .= "self.parent.set_new_file_upload_progress('File $file->filename deleted');\n";
		$onload_extra .= "self.parent.del_fileid($file->id);\n";

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
<script language="JavaScript" src="<?=squizlib_href('js','form_functions.js');?>"></script>
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

	function delete_attachment(form) {

		var fileid = elementValue(self.parent.edit.fileid);
		if (fileid != '' && fileid != undefined) {
			form.fileid.value = fileid;
			form.submit();
		}
	}//end delete_attachment

</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onLoad="javascript: init();">
<? 
	//Copied same style as new_file.php 
?>
	<form name="del_image" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="action"       value="Delete">
		<input type="hidden" name="file_edit"    value="true">
		<input type="hidden" name="process_only" value="1">
		<input type="hidden" name="visible"      value="N">
		<input type="hidden" name="description"  value="">
		<input type="hidden" name="keywords"     value="">
		<input type="hidden" name="fileid"     value="">
		<input type="button" value="Delete Attachment!" onClick="javascript: delete_attachment(this.form);">
	</form>
</body>
</html>