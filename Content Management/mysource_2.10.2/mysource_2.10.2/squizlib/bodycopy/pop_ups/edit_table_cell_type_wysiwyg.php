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
## $Source: /home/cvsroot/squizlib/bodycopy/pop_ups/edit_table_cell_type_wysiwyg.php,v $
## $Revision: 2.16 $
## $Author: dofford $
## $Date: 2004/03/08 01:10:14 $
#######################################################################

$body_extra = 'scroll="no" UNSELECTABLE="on"';
$show_stylesheet = true;
include(dirname(__FILE__)."/header.php"); 
include_once(dirname(__FILE__).'/../../wysiwyg/wysiwyg.inc');

?> 
<script language="JavaScript" type="text/javascript">

	// returns the data for initialising the pop-up
	function get_init_data() {
		var data = new Object();
		data["html"]   = owner.bodycopy_current_edit["data"]["html"];
		data["styles"] = owner.get_bodycopy_special_styles();
		data["keywords"] = owner.get_bodycopy_special_keywords();
		return data;
	}

</script>
<?

$wysiwyg = new wysiwyg();

$wysiwyg->paint_interface($_GET['siteid'],
						$_GET['pageid'],
						$page_width, 
						'get_init_data()', 
						'owner.bodycopy_save_table_cell_type_wysiwyg(html)',
						'popup_close()',
						'owner.get_bodycopy_popup_visibilty()',
						true,
						true,
						true,
						'owner.get_bodycopy_anchors(owner.bodycopy_current_edit["bodycopy_name"])',
						$_GET['enable_keywords'],
						true,
						0,
						'full',
						true,
						$stylesheet,
						true,
						true,
						true
						);

include(dirname(__FILE__)."/footer.php");
?>
