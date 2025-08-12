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
## $Source: /home/cvsroot/squizlib/bodycopy/pop_ups/edit_table_cell_type_file_new_file.php,v $
## $Revision: 2.3 $
## $Author: gsherwood $
## $Date: 2003/02/27 03:34:22 $
#######################################################################

# because this page gets the list of files each time is shouldn't be cached
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Expires: ". gmdate("D, d M Y H:i:s",time()-3600) . " GMT");
if ($_GET['upload_only']) $bgcolor = "#C0C0C0";
include(dirname(__FILE__)."/header.php"); 
$file = new File(0);
$file->visible = "N"; # *cough* can't call set_visible because the file ain't created yet
$file->print_backend(true, $_GET['upload_only']);

# if the file object has a fileid, 
# then it has been submitted, so we can save and close
if ($file->id) {
?>
	<script language="JavaScript">
		window.opener.save_popup_fileid(<?=$file->id?>, '<?=$file->filename?>');
		window.close();
	</script>
<?

}#end if

include(dirname(__FILE__)."/footer.php"); 
 
?>