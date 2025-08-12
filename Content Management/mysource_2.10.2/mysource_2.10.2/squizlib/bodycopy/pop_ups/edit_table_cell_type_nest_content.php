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
## $Source: /home/cvsroot/squizlib/bodycopy/pop_ups/edit_table_cell_type_nest_content.php,v $
## $Revision: 2.9 $
## $Author: dofford $
## $Date: 2004/03/04 23:07:29 $
#######################################################################
# because this page gets the list of sites and pages each time is shouldn't be cached
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("Expires: ". gmdate("D, d M Y H:i:s",time()-3600) . " GMT");
include(dirname(__FILE__)."/header.php"); 

global $browser;
global $SQUIZLIB_PATH;

?>
<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('var_serialise', 'var_serialise.js');?>"></script>
<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js', 'form_functions.js');?>"></script>
<script language="JavaScript">
	
	function popup_init() {

		var data = owner.bodycopy_current_edit["data"];
		var f = document.main_form;

		// Lets create these page arrays
		window.all_sites_and_pages = new Array();
		<?
		$web = &get_web_system();
		# Need to preload all pages and sites then have the list limited by siteid
		$sites = $web->get_editable_sites();
		$pages = Array("" => "");
		foreach($sites as $siteid => $name) {
			$site_pages = $web->page_array_with_sticks($siteid,0,'','',false,' ');
			?>
			window.all_sites_and_pages[<?=$siteid?>] = var_unserialise('<?=var_serialise($site_pages, true);?>');
			<?
		}
		$current_site = &$web->get_site();
		?>

		if (data['siteid'] == null || data['siteid'] == 0) {
			<?
			$nest_pageid = $_GET['new_pageid'];
			?>
			var have_nest_pageid = <?= ((empty($nest_pageid)) ? 'false' : 'true') ?>;
			if (data['pageid'] != 0 && !have_nest_pageid) {
				// Refresh and put into our nest pageid into GET
				document.location = "<?=$_SERVER['PHP_SELF']?>?<?=$_SERVER['QUERY_STRING']?>&new_pageid=" + data['pageid'];
			} else if (have_nest_pageid) {
				<?
				$page = &$web->get_page($nest_pageid);
				if ($page->id) {
					$site2 = &$page->get_site();
					?>
					data['siteid'] = "<?=$site2->id?>";
					<?
				} else {
					// Page no longer exists so we'll have to reset the site cause without the page we are not going to be able to work out the site
					?>
					data['siteid'] = "<?=$current_site->id?>";
					<?
				}
				?>
			} else {
				// There is no site id or pageid to get the site id so lets set current site as default
				data['siteid'] = "<?=$current_site->id?>";
			}
		} else {
			// Check if the set site still exists
			if (!window.all_sites_and_pages[data['siteid']]) {
				// Site doesn't exist anymore so I guess neither does the page so reset site to current site
				data['siteid'] = "<?=$current_site->id?>";
			}
		}
		// Populate our pageid list
		populate_page_list(data['siteid']);
		owner.highlightComboElement(f.siteid, data['siteid']);
		owner.highlightComboElement(f.pageid, data['pageid']);
		owner.highlightComboElement(f.submit_type, data['submit_type']);
		owner.highlightComboElement(f.restrict_links, data['restrict_links']);
		f.variables.value = (data['variables'] == null) ? '' : data['variables'];
	}// end popup_init()

	function popup_save(f) {
		owner.bodycopy_save_table_cell_type_nest_content(
			owner.elementValue(f.siteid),
			owner.elementValue(f.pageid),
			owner.elementValue(f.variables),
			owner.elementValue(f.submit_type),
			owner.elementValue(f.restrict_links)
		);
	}

</script>
<table width="100%" border="0">
<form name="main_form">
	<tr>
		<td nowrap colspan="3" class="bodycopy-popup-heading">Nest Contents Properties&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4"><hr></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			Site :
		</td>
		<td valign="middle" colspan="2">
			<?
			$web = &get_web_system();
			$site = &$web->get_site();
			$sites[$site->id] = '[THIS SITE] '.$sites[$site->id];
			echo combo_box('siteid', $sites, "", 'onChange="javascript: populate_page_list(elementValue(this));"', 75);
			?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			Nest Page :
		</td>
		<td valign="middle" colspan="2">
		<?
			$pages = Array("" => "");
			echo combo_box("pageid", $pages, "", "style=\"font-family: courier new;\"", 75);

		?>
			<SCRIPT language="JavaScript">
				function populate_page_list(siteid) {
					var valid_pages = window.all_sites_and_pages[siteid];
					var form = document.main_form;
					// Wipe any previous values
					for(var i = form.pageid.options.length - 1; i >= 0; i--) {
						form.pageid.options[i] = null;
					}
					var i = 0;
					for(var pid in valid_pages) {
						var valid_pagename = valid_pages[pid];
						if (valid_pagename == null) {
							continue;
						}
						form.pageid.options[i] = new Option(valid_pagename, pid);
						i++;
					}
				}
			</SCRIPT>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td valign="middle" colspan="2">
			<SCRIPT language="JavaScript">
				function edit_page_contents() {
					var form = document.main_form;
					var selectedIndex = form.pageid.selectedIndex;
					var pid = form.pageid.options[selectedIndex].value;
					if (pid != undefined && pid > 0) {
						window.open("page.php?p="+pid+"&template_edit=1",'nestContent_'+pid,'toolbar=1, menubar=1, location=1, status=1, scrollbars=1, resizable=1');
					}
				}
			</SCRIPT>
			<a href="javascript: edit_page_contents();">Edit Contents of the Nested Page</a>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="top">
			Submit Variables :
		</td>
		<td valign="middle">
		<?
			echo text_area("variables", $variables, 30, 3);
		?>
		</td>
		<td width="100%" valign="top">
			<?=combo_box('submit_type', Array('global' => 'Global', 'get-post' => 'GET/POST', 'get' => 'GET', 'post' => 'POST', 'session' => 'Session'));?>
		</td>
	</tr>
	<tr>
		<td colspan="4" style="font-size:10px">Enter the variables to submit as KEY:VALUE pairs.<br>Then select the method you want these variables passed to the Nest Content page.
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td valign="middle" colspan="3">
		Keep nested pages internal links in this page? :
		<?
			echo combo_box('restrict_links', array('0'=>'No','1'=>'Yes'));
		?>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<hr>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="3">
			<input type="button" value="Save" onclick="javascript: popup_save(this.form)">
			<input type="button" value="Cancel" onclick="javascript: popup_close();">
		</td>
		<td>&nbsp;</td>
	</tr>
</form>
</table>
<? include(dirname(__FILE__)."/footer.php"); ?>