<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############## //ADMIN PANEL STYLE SYSTEM\\ ############### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Styles";
$permissions = "styles";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");


function buildTemplateArr2($styleid) {
	// get all default templates
	$default = query("SELECT * FROM templates_default ORDER BY title ASC");

	// get all customized templates
	$cus = query("SELECT * FROM templates WHERE styleid = '".$styleid."' AND is_global = 0 ORDER BY title ASC");
	
	// create array
	if(!is_array($templateinfo)) {
		$templateinfo = Array();
	}

	// put custom into array...
	if(mysql_num_rows($cus) > 0) {
		while($custom = mysql_fetch_array($cus)) {
			if(empty($custom['defaultid']) OR $custom['defaultid'] == null) {
				$templateinfo[$custom['templategroupid']][$custom['title']] = $custom;
				$templateinfo[$custom['templategroupid']][$custom['title']]['is_custom'] = 1;
			} else {
				$customTemplates[$custom['defaultid']] = $custom;
			}
		}
	}

	// loop through each template
	while($defaultTemplates = mysql_fetch_array($default)) {
		if(is_array($customTemplates[$defaultTemplates['defaultid']])) {
			// put in templateinfo array
			$templateinfo[$customTemplates[$defaultTemplates['defaultid']]['templategroupid']][$customTemplates[$defaultTemplates['defaultid']]['title']] = $customTemplates[$defaultTemplates['defaultid']];
			$templateinfo[$customTemplates[$defaultTemplates['defaultid']]['templategroupid']][$customTemplates[$defaultTemplates['defaultid']]['title']]['is_custom'] = 1;
		}

		// if we didn't get a custom template...
		else {
			$templateinfo[$defaultTemplates['templategroupid']][$defaultTemplates['title']] = $defaultTemplates;
			$templateinfo[$defaultTemplates['templategroupid']][$defaultTemplates['title']]['is_custom'] = 0;
		}
	}

	// returns all templates for current style...
	return $templateinfo;
}

function buildTemplateArr3($styleid) {
	// get all customized templates
	$cus = query("SELECT * FROM templates WHERE styleid = '".$styleid."' AND is_global = 0 ORDER BY title ASC");
	
	// put custom into array...
	if(mysql_num_rows($cus) > 0) {
		while($custom = mysql_fetch_array($cus)) {
			if($custom['is_custom'] == 1) {
				$templateinfo[-1][$custom['title']] = $custom;
			} else {
				$templateinfo[$custom['templategroupid']][$custom['title']] = $custom;
			}
		}
	}

	// returns all templates for current style...
	return $templateinfo;
}

// ##### DO IMPORT/EXPORT ##### \\
if($_GET['do'] == "importExport") {
	if($_POST['import']['set_form']) {
		$theContent = "";

		// set some vars
		$name = $_FILES['fupload']['name'];
		$tmp_name = $_FILES['fupload']['tmp_name'];
		$mime = $_FILES['fupload']['type'];
		$size = $_FILES['fupload']['size'];

		if($_POST['import']['filepath']) {
			// get extension
			if(!eregi(".xml",$_POST['import']['filepath'])) {
				construct_error("You must enter a path to an XML file.");
				exit;
			}
			
			// bad uri
			else if(!($theContent = @file_get_contents($_POST['import']['filepath']))) {
				construct_error("You have entered a bad URL.");
				exit;
			}
		}

		else {
			// upload?
			if(!is_uploaded_file($tmp_name)) {
				construct_error("The file failed to upload.");
				exit;
			}

			else if(strpos($mime,"xml") === false) {
				construct_error("You must upload an XML file.");
				exit;
			}

			else if(!($theContent = @file_get_contents($tmp_name))) {
				construct_error("The message board failed to retrieve the contents of the uploaded file.");
				exit;
			}
		}

		// import!
		include("./../includes/functions_xml.php");
		xml_import($theContent,false);

		// redirect to thankyou page...
		redirect("thankyou.php?message=You have imported the style successfully.&uri=style.php?do=importExport");
		exit;
	}

	if($_POST['export']['set_form']) {
		// start to form query
		$FIND_style = $_POST['export']['styleid'];

		foreach($_POST['groups'] as $key => $groupid) {
			if($groupid == -1) {
				$FIND_groups = "";
				break;
			}

			$FIND_groups .= "OR templategroupid = '".$groupid."' ";
		}

		$conditions = "styleid = '".$FIND_style."' ".$FIND_groups;
		if($FIND_groups) {
			$FIND_groups = preg_replace("|^OR|","",$FIND_groups);
			$FIND_groups = "WHERE ".$FIND_groups;
		}

		$total_colors = "SELECT * FROM styles_colors WHERE styleid = '".$FIND_style."' LIMIT 1";
		$total_groups = "SELECT * FROM templategroups ".$FIND_groups." ORDER BY title";

		$theGroups = query($total_groups);
		$styleinfo = query("SELECT * FROM styles WHERE styleid = '".$FIND_style."' LIMIT 1",1);
		if($_POST['export']['colors2'] == 1) $color_q = query($total_colors);

		$styleinfo['title'] = trim($styleinfo['title']);
		$title_noSpaces = preg_replace("|\s|","",$styleinfo['title']);

		$templateinfo2 = buildTemplateArr3($FIND_style);

		// create file.. if we want to dump
		if(!$_POST['export']['download']) {
			$handle = fopen("../export/wtcBB_style_".$title_noSpaces.".xml","wb");
			fwrite($handle,"<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n\n");
			fwrite($handle,"<style title=\"".$styleinfo['title']."\" display_order=\"".$styleinfo['display_order']."\" user_selection=\"".$styleinfo['user_selection']."\">\n");

			// loop through templates
			while($groupinfo = mysql_fetch_array($theGroups)) {
				if(is_array($templateinfo2[$groupinfo['templategroupid']])) {
					fwrite($handle,"\t<group templategroupid=\"".$groupinfo['templategroupid']."\" title=\"".$groupinfo['title']."\">\n");

					$templateinfo['template'] = str_replace("\n\n","\n",$templateinfo['template']);

					foreach($templateinfo2[$groupinfo['templategroupid']] as $theTitle => $templateinfo) {
						fwrite($handle,"\t\t<template templateid=\"".$templateinfo['templateid']."\" type=\"".$templateinfo['type']."\" templategroupid=\"".$templateinfo['templategroupid']."\" title=\"".$templateinfo['title']."\" defaultid=\"".$templateinfo['defaultid']."\" styleid=\"".$templateinfo['styleid']."\" last_edit=\"".$templateinfo['last_edit']."\" username=\"".$templateinfo['username']."\" version=\"".$templateinfo['version']."\" is_global=\"".$templateinfo['is_global']."\" is_custom=\"".$templateinfo['is_custom']."\"><![CDATA[".$templateinfo['template']."]]></template>\n");
					}

					fwrite($handle,"\t</group>\n\n");
				}
			}

			// custom templates?
			if(is_array($templateinfo2[-1])) {
				fwrite($handle,"\t<group templategroupid=\"-1\" title=\"Custom\">\n");

				foreach($templateinfo2[-1] as $theTitle => $templateinfo) {
					fwrite($handle,"\t\t<template templateid=\"".$templateinfo['templateid']."\" type=\"".$templateinfo['type']."\" templategroupid=\"".$templateinfo['templategroupid']."\" title=\"".$templateinfo['title']."\" defaultid=\"".$templateinfo['defaultid']."\" styleid=\"".$templateinfo['styleid']."\" last_edit=\"".$templateinfo['last_edit']."\" username=\"".$templateinfo['username']."\" version=\"".$templateinfo['version']."\" is_global=\"".$templateinfo['is_global']."\" is_custom=\"".$templateinfo['is_custom']."\"><![CDATA[".$templateinfo['template']."]]></template>\n");
				}

				fwrite($handle,"\t</group>\n\n");
			}

			if($_POST['export']['colors2'] AND mysql_num_rows($color_q)) {
				// fetch arr
				$colorinfo = mysql_fetch_array($color_q);
				$attribs = "";

				foreach($colorinfo as $fieldName => $value) {
					if(strlen($fieldName) <= 3 OR $fieldName == "styleid" OR $fieldName == "colorid") {
						continue;
					}

					$attribs .= $fieldName."=\"".htmlspecialchars($value)."\" ";
				}

				fwrite($handle,"\t<colors ".$attribs."/>\n");
			}

			fwrite($handle,"</style>");

			fclose($handle);
		}

		// we're downloading it
		else {
			header('Content-type: text/xml');
			header('Content-Disposition: attachment; filename="wtcBB_style_'.$title_noSpaces.'.xml"');

			print("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n\n");
			print("<style title=\"".$styleinfo['title']."\" display_order=\"".$styleinfo['display_order']."\" user_selection=\"".$styleinfo['user_selection']."\">\n");

			// loop through templates
			while($groupinfo = mysql_fetch_array($theGroups)) {
				if(is_array($templateinfo2[$groupinfo['templategroupid']])) {
					print("\t<group templategroupid=\"".$groupinfo['templategroupid']."\" title=\"".$groupinfo['title']."\">\n");

					foreach($templateinfo2[$groupinfo['templategroupid']] as $theTitle => $templateinfo) {
						print("\t\t<template templateid=\"".$templateinfo['templateid']."\" type=\"".$templateinfo['type']."\" templategroupid=\"".$templateinfo['templategroupid']."\" title=\"".$templateinfo['title']."\" defaultid=\"".$templateinfo['defaultid']."\" styleid=\"".$templateinfo['styleid']."\" last_edit=\"".$templateinfo['last_edit']."\" username=\"".$templateinfo['username']."\" version=\"".$templateinfo['version']."\" is_global=\"".$templateinfo['is_global']."\" is_custom=\"".$templateinfo['is_custom']."\"><![CDATA[".$templateinfo['template']."]]></template>\n");
					}

					print("\t</group>\n\n");
				}
			}

			// custom templates?
			if(is_array($templateinfo2[-1])) {
				print("\t<group templategroupid=\"-1\" title=\"Custom\">\n");

				$templateinfo['template'] = str_replace("\r\n","\n",$templateinfo['template']);

				foreach($templateinfo2[-1] as $theTitle => $templateinfo) {
					print("\t\t<template templateid=\"".$templateinfo['templateid']."\" type=\"".$templateinfo['type']."\" templategroupid=\"".$templateinfo['templategroupid']."\" title=\"".$templateinfo['title']."\" defaultid=\"".$templateinfo['defaultid']."\" styleid=\"".$templateinfo['styleid']."\" last_edit=\"".$templateinfo['last_edit']."\" username=\"".$templateinfo['username']."\" version=\"".$templateinfo['version']."\" is_global=\"".$templateinfo['is_global']."\" is_custom=\"".$templateinfo['is_custom']."\"><![CDATA[".$templateinfo['template']."]]></template>\n");
				}

				print("\t</group>\n\n");
			}

			if($_POST['export']['colors2'] AND mysql_num_rows($color_q)) {
				// fetch arr
				$colorinfo = mysql_fetch_array($color_q);
				$attribs = "";

				foreach($colorinfo as $fieldName => $value) {
					if(strlen($fieldName) <= 3 OR $fieldName == "styleid" OR $fieldName == "colorid") {
						continue;
					}

					$attribs .= $fieldName."=\"".htmlspecialchars($value)."\" ";
				}

				print("\t<colors ".$attribs."/>\n");
			}

			print("</style>");

			exit;
		}

		// redirect to thankyou page...
		redirect("thankyou.php?message=You have exported the style successfully.&uri=style.php?do=importExport");
	}

	// do header
	admin_header("wtcBB Admin Panel - Import/Export");

	construct_title("Import/Export Styles");

	construct_table("options","export","export_submit",1);

	construct_header("Export Style",2);


	construct_select_begin(1,"Select Style:","","export","styleid");

		// get styles
		$allStyles = query("SELECT * FROM styles ORDER BY display_order");

		while($theStyle = mysql_fetch_array($allStyles)) {
			print('<option value="'.$theStyle['styleid'].'">'.$theStyle['title'].'</option>');
		}

	construct_select_end(1);


	construct_select_begin(2,"Template Group(s)","","export","groups",0,1);

		print("<select name=\"groups[]\" multiple=\"multiple\" size=\"15\">\n");
			$group_q = query("SELECT * FROM templategroups ORDER BY title");
			print('<option value="-1" selected="selected">All Template Groups</option>');
			while($theGroup = mysql_fetch_array($group_q)) {
				print('<option value="'.$theGroup['templategroupid'].'">'.$theGroup['title'].'</option>');
			}
		print("</select>\n\n");

	construct_select_end(2,1);

	construct_input(1,"Export Style Colors?","","export","colors2",0,1);

	construct_input(2,"Download it?","If you want the file to be dumped into your file system, keep this choice as No. If you want to download it to your computer, select Yes.","export","download",1,2);

	construct_footer(2,"export_submit");

	construct_table_END(1);


	print("\n\n<br /><br />\n\n");


	?>
		<form method="post" action="" enctype="multipart/form-data" name="import" style="margin: 0px;">
		<br /><input type="hidden" name="import[set_form]" value="1" />

		<table border="0" cellspacing="0" cellpadding="4" class="options">
	<?php

	construct_header("Import Style",2);

	construct_text(1,"Location of File:","You can either enter a file path in which is on the internet, or you may upload the file using the option below.","import","filepath");

	?>
	<tr>
		<td class="desc2">
			<strong>Location of File:</strong> <br /> <span class="small">You can upload the file here, or you can specify a URL on the internet in the option above.</span>
		</td>

		<td class="input2">
			<input type="file" class="text" name="fupload" />
		</td>
	</tr>
	<?php

	construct_select_begin(1,"Overwrite existing style?","","import","styleid");

		// get styles
		$allStyles = query("SELECT * FROM styles ORDER BY display_order");

		print('<option value="-1">Create New Style</option>');

		while($theStyle = mysql_fetch_array($allStyles)) {
			print('<option value="'.$theStyle['styleid'].'">'.$theStyle['title'].'</option>');
		}

	construct_select_end(1);


	construct_input(2,"Import Style Colors?","","import","colors",1,1);

	construct_header("New Style Options <span class=\"small\">(Only if you are creating a new style)</span>",2);

	construct_text(1,"Title","","import","title");

	construct_input(2,"Allow User Selection","Enabling this option will allow registered users to select this style in their user control panel.","import","user_selection",0,2);

	construct_text(1,"Display Order","","import","display_order","",1);

	construct_footer(2,"import_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}

// ##### DO STYLE COLORS ##### \\
else if($_GET['do'] == "colors") {
	// get style id and check it
	$getStyleId = query("SELECT * FROM styles WHERE styleid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($getStyleId)) {
		construct_error("Sorry, no style exists with the given ID.");
		exit;
	}

	// get styleinfo
	$styleinfo = mysql_fetch_array($getStyleId);

	// get all default color templates
	$defaultColors = query("SELECT * FROM styles_colors_default LIMIT 1",1);
	$defaultColorsCopy = query("SELECT * FROM styles_colors_default LIMIT 1",1);
	$defaultColorsCopyCopy = query("SELECT * FROM styles_colors_default LIMIT 1",1);

	// do we have customized?
	$customizedColors = query("SELECT * FROM styles_colors WHERE defaultid = '".$defaultColors['defaultid']."' AND styleid = '".$styleinfo['styleid']."' LIMIT 1");

	// if so... we should replace
	if(mysql_num_rows($customizedColors)) {
		while($custColorInfo = mysql_fetch_array($customizedColors)) {
			// loop through default colors to see if we can differentiate..
			$x = 0;
			foreach($defaultColors as $option => $value) {
				if(strlen($option) > 3) {
					if($value != $custColorInfo[$option]) {
						// no match.. set the defaultColor variabe to this value now
						$defaultColors[$option] = $custColorInfo[$option];
						$defaultColorsCopy[$option] = "scyth543216789";
					}
				}
			}
		}
	}

	// custom color array
	$customizedColors2 = query("SELECT * FROM styles_colors WHERE defaultid = '".$defaultColors['defaultid']."' AND styleid = '".$styleinfo['styleid']."' LIMIT 1");
	$custColor = mysql_fetch_array($customizedColors2);

	// update/insert time!
	if($_POST['edit_colors']['set_form']) {
		// insert or update? if no customized.. then insert!
		if(mysql_num_rows($customizedColors) == 0 AND $_POST['edit_colors']['revert_all'] == 0) {
			// long query...
			$query = "INSERT INTO styles_colors (styleid,defaultid,";

			// intiate counter
			$x = 0;

			// loop through array to form query
			foreach($_POST['edit_colors'] as $key => $value) {
				// no set form!
				if($key != "set_form" AND $key != "revert_all") {
					// get comma
					if($x == 0) {
						$comma = "";
					} else {
						$comma = ",";
					}

					// form query
					$query .= $comma.$key;
					
					// increment
					$x++;
				}
			}

			// form more of query
			$query .= ") VALUES ('".$styleinfo['styleid']."','".$defaultColors['defaultid']."',";

			// reset counter
			$x = 0;

			// loop through array again to get values...
			foreach($_POST['edit_colors'] as $key => $value) {
				// no set form!
				if($key != "set_form" AND $key != "revert_all") {
					// get comma
					if($x == 0) {
						$comma = "";
					} else {
						$comma = " , ";
					}

					// form query
					$query .= $comma."'".addslashes($value)."'";
					
					// increment
					$x++;
				}
			}

			// form rest of query
			$query .= ")";
		}

		// otherwise, we are updating
		else {
			// wait! revert_all!!!!!
			if($_POST['edit_colors']['revert_all'] == 1) {
				// delete this style and get out!
				query("DELETE FROM styles_colors WHERE colorid = '".$custColor['colorid']."' LIMIT 1");
				
				// redirect to thankyou page...
				redirect("thankyou.php?message=Style colors reverted successfully. You will now be redirected back.&uri=style.php?do=colorsSTEVEid=".$styleinfo['styleid']);
			}

			// long query..
			$query = "UPDATE styles_colors SET ";

			// intiate counter
			$x = 0;

			// loop through array to form query
			foreach($_POST['edit_colors'] as $key => $value) {
				// no set form!
				if($key != "set_form" AND $key != "revert_all") {
					// get comma
					if($x == 0) {
						$comma = "";
					} else {
						$comma = " , ";
					}

					// form query
					$query .= $comma.$key." = '".addslashes($value)."'";

					// increment
					$x++;
				}
			}

			// form rest of query
			$query .= " WHERE defaultid = '".$defaultColors['defaultid']."' AND styleid = '".$styleinfo['styleid']."'";
		}

		//print($query);

		// run query
		query($query);

		// what if revert!?!?!
		if(is_array($_POST['revert5'])) {
			foreach($_POST['revert5'] as $key => $value) {
				if($value == 1) {
					// already have all defaults.. no need to select them.. just run an update query!
					query("UPDATE styles_colors SET ".$key." = '".$defaultColorsCopyCopy[$key]."' WHERE colorid = '".$custColor['colorid']."'");
				}
			}
		}

		// redirect to thankyou page...
		redirect("thankyou.php?message=Style colors changed successfully. You will now be redirected back.&uri=style.php?do=colorsSTEVEid=".$styleinfo['styleid']);
	}

	// do header
	admin_header("wtcBB Admin Panel - Styles - Edit Style Colors");

	construct_title("Edit Style Colors for Style <em>".$styleinfo['title']."</em> <span class=\"small\">(id: ".$styleinfo['styleid'].")</span>");

	
	?>

	<table border="0" cellspacing="0" cellpadding="4" class="options">
		<tr>
			<td class="header">Navigation</td>
		</tr>

		<tr>
			<td class="desc1_bottom" style="text-align: center; padding: 7px;">
				<form style="margin: 0px; padding: 0px;" method="post" action="" name="myForm">
					<select name="navi" onChange="location.href=(myForm.navi.options[myForm.navi.selectedIndex].value)">
						<option value="#" selected="selected">---NAVIGATION---</option>
						<option value="style.php?do=templates&id=<?php print($styleinfo['styleid']); ?>">Edit Style Templates</option>
						<option value="style.php?do=colors&id=<?php print($styleinfo['styleid']); ?>">Edit Style Colors</option>
						<option value="style.php?do=edit_style&id=<?php print($styleinfo['styleid']); ?>">Edit Style Options</option>
						<option value="style.php?do=add_template&styleid=<?php print($styleinfo['styleid']); ?>">Add Template</option>
						<option value="style.php?do=add_replacement&styleid=<?php print($styleinfo['styleid']); ?>">Add Replacement</option>
					</select>

					<button type="button" onClick="location.href=(myForm.navi.options[myForm.navi.selectedIndex].value)" style="margin: 2px; margin-bottom: 0px;" <?php print($submitbg); ?>>Go</button>
				</form>
			</td>
		</tr>

		<tr>
			<td class="footer" colspan="0">&nbsp;</td>
		</tr>

	</table>

	<br />

	<?php


	// BASIC OPTIONS \\
	construct_table("options","edit_colors","color_submit",1);

	construct_header("Basic Color Options",2);

	constructColorText(1,"Main Division Width","","edit_colors","main_table_width",$defaultColors['main_table_width'],0,$defaultColorsCopy['main_table_width']);

	constructColorText(2,"Inner Division Width","","edit_colors","inner_table_width",$defaultColors['inner_table_width'],0,$defaultColorsCopy['inner_table_width']);

	constructColorText(1,"Border Color","","edit_colors","border_color",$defaultColors['border_color'],0,$defaultColorsCopy['border_color'],1);

	constructColorText(2,"Border Width","","edit_colors","border_width",$defaultColors['border_width'],0,$defaultColorsCopy['border_width']);

	constructColorText(1,"Border Style","","edit_colors","border_style",$defaultColors['border_style'],0,$defaultColorsCopy['border_style']);

	constructColorText(2,"Images Folder","","edit_colors","images_folder",$defaultColors['images_folder'],0,$defaultColorsCopy['images_folder']);

	constructColorText(1,"Table/Division Padding","","edit_colors","cell_padding",$defaultColors['cell_padding'],0,$defaultColorsCopy['cell_padding']);

	constructColorText(2,"Title Image","","edit_colors","title_image",$defaultColors['title_image'],0,$defaultColorsCopy['title_image']);

	constructColorText(1,"Doc Type","It is <strong>strongly</strong> recommended that you keep this as an XHTML Document Type Definition. As a lot of the layout structure relies on an XHTML DTD.","edit_colors","doctype",htmlspecialchars($defaultColors['doctype']),0,$defaultColorsCopy['doctype']);

	construct_input(2,"Revert whole style?","All customized <strong>colors</strong> for this style will be lost!","edit_colors","revert_all",1,2);

	construct_footer(2,"color_submit");
	construct_table_END();

	
	print("\n\n<br /><br />\n\n");

	
	// BACKGROUND \\
	construct_table("options","edit_colors","color_submit");

	construct_header("Body Style Options",2);

	constructColorText(1,"Background Color","","edit_colors","body_background",$defaultColors['body_background'],0,$defaultColorsCopy['body_background'],1);

	constructColorText(2,"Font Color","","edit_colors","body_color",$defaultColors['body_color'],0,$defaultColorsCopy['body_color'],1);

	constructColorText(1,"Font Family","","edit_colors","body_family",$defaultColors['body_family'],0,$defaultColorsCopy['body_family']);

	constructColorText(2,"Font Size","","edit_colors","body_size",$defaultColors['body_size'],0,$defaultColorsCopy['body_size']);

	constructColorText(1,"Font Style","","edit_colors","body_style",$defaultColors['body_style'],0,$defaultColorsCopy['body_style']);

	constructColorText(2,"Font Weight","","edit_colors","body_weight",$defaultColors['body_weight'],1,$defaultColorsCopy['body_weight']);

	// regular link
	construct_header("Regular Link",2);

	constructColorText(1,"Link Background","","edit_colors","body_a_link_bg",$defaultColors['body_a_link_bg'],0,$defaultColorsCopy['body_a_link_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","body_a_link_color",$defaultColors['body_a_link_color'],0,$defaultColorsCopy['body_a_link_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","body_a_link_decoration",$defaultColors['body_a_link_decoration'],0,$defaultColorsCopy['body_a_link_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","body_a_link_weight",$defaultColors['body_a_link_weight'],0,$defaultColorsCopy['body_a_link_weight']);

	constructColorText(1,"Link Style","","edit_colors","body_a_link_style",$defaultColors['body_a_link_style'],1,$defaultColorsCopy['body_a_link_style']);

	// visited link
	construct_header("Visited Link",2);

	constructColorText(1,"Link Background","","edit_colors","body_a_visited_bg",$defaultColors['body_a_visited_bg'],0,$defaultColorsCopy['body_a_visited_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","body_a_visited_color",$defaultColors['body_a_visited_color'],0,$defaultColorsCopy['body_a_visited_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","body_a_visited_decoration",$defaultColors['body_a_visited_decoration'],0,$defaultColorsCopy['body_a_visited_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","body_a_visited_weight",$defaultColors['body_a_visited_weight'],0,$defaultColorsCopy['body_a_visited_weight']);

	constructColorText(1,"Link Style","","edit_colors","body_a_visited_style",$defaultColors['body_a_visited_style'],1,$defaultColorsCopy['body_a_visited_style']);

	// hover/active link
	construct_header("Hover/Active Link",2);

	constructColorText(1,"Link Background","","edit_colors","body_a_hover_bg",$defaultColors['body_a_hover_bg'],0,$defaultColorsCopy['body_a_hover_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","body_a_hover_color",$defaultColors['body_a_hover_color'],0,$defaultColorsCopy['body_a_hover_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","body_a_hover_decoration",$defaultColors['body_a_hover_decoration'],0,$defaultColorsCopy['body_a_hover_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","body_a_hover_weight",$defaultColors['body_a_hover_weight'],0,$defaultColorsCopy['body_a_hover_weight']);

	constructColorText(1,"Link Style","","edit_colors","body_a_hover_style",$defaultColors['body_a_hover_style'],1,$defaultColorsCopy['body_a_hover_style']);

	// extra css
	construct_header("Extra CSS Properties",2);

	// get custmoized...
	if($defaultColorsCopy['body_extra'] == "scyth543216789") {
		$customized = " style=\"color: #BB0000; font-weight: bold;\"";
	} else {
		$customized = "";
	}

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc1_bottom\" colspan=\"2\" style=\"text-align: center;\"><textarea name=\"edit_colors[body_extra]\" cols=\"60\" rows=\"10\"".$customized.">".htmlspecialchars($defaultColors['body_extra'])."</textarea></td>\n");
	print("\t\t</tr>\n");

	construct_footer(2,"color_submit");
	construct_table_END();


	print("\n\n<br /><br />\n\n");

	
	// PAGE BACKGROUND \\
	construct_table("options","edit_colors","color_submit");

	construct_header("Page Style Options",2);

	constructColorText(1,"Background Color","","edit_colors","page_background",$defaultColors['page_background'],0,$defaultColorsCopy['page_background'],1);

	constructColorText(2,"Font Color","","edit_colors","page_color",$defaultColors['page_color'],0,$defaultColorsCopy['page_color'],1);

	constructColorText(1,"Font Family","","edit_colors","page_family",$defaultColors['page_family'],0,$defaultColorsCopy['page_family']);

	constructColorText(2,"Font Size","","edit_colors","page_size",$defaultColors['page_size'],0,$defaultColorsCopy['page_size']);

	constructColorText(1,"Font Style","","edit_colors","page_style",$defaultColors['page_style'],0,$defaultColorsCopy['page_style']);

	constructColorText(2,"Font Weight","","edit_colors","page_weight",$defaultColors['page_weight'],1,$defaultColorsCopy['page_weight']);

	// regular link
	construct_header("Regular Link",2);

	constructColorText(1,"Link Background","","edit_colors","page_a_link_bg",$defaultColors['page_a_link_bg'],0,$defaultColorsCopy['page_a_link_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","page_a_link_color",$defaultColors['page_a_link_color'],0,$defaultColorsCopy['page_a_link_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","page_a_link_decoration",$defaultColors['page_a_link_decoration'],0,$defaultColorsCopy['page_a_link_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","page_a_link_weight",$defaultColors['page_a_link_weight'],0,$defaultColorsCopy['page_a_link_weight']);

	constructColorText(1,"Link Style","","edit_colors","page_a_link_style",$defaultColors['page_a_link_style'],1,$defaultColorsCopy['page_a_link_style']);

	// visited link
	construct_header("Visited Link",2);

	constructColorText(1,"Link Background","","edit_colors","page_a_visited_bg",$defaultColors['page_a_visited_bg'],0,$defaultColorsCopy['page_a_visited_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","page_a_visited_color",$defaultColors['page_a_visited_color'],0,$defaultColorsCopy['page_a_visited_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","page_a_visited_decoration",$defaultColors['page_a_visited_decoration'],0,$defaultColorsCopy['page_a_visited_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","page_a_visited_weight",$defaultColors['page_a_visited_weight'],0,$defaultColorsCopy['page_a_visited_weight']);

	constructColorText(1,"Link Style","","edit_colors","page_a_visited_style",$defaultColors['page_a_visited_style'],1,$defaultColorsCopy['page_a_visited_style']);

	// hover/active link
	construct_header("Hover/Active Link",2);

	constructColorText(1,"Link Background","","edit_colors","page_a_hover_bg",$defaultColors['page_a_hover_bg'],0,$defaultColorsCopy['page_a_hover_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","page_a_hover_color",$defaultColors['page_a_hover_color'],0,$defaultColorsCopy['page_a_hover_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","page_a_hover_decoration",$defaultColors['page_a_hover_decoration'],0,$defaultColorsCopy['page_a_hover_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","page_a_hover_weight",$defaultColors['page_a_hover_weight'],0,$defaultColorsCopy['page_a_hover_weight']);

	constructColorText(1,"Link Style","","edit_colors","page_a_hover_style",$defaultColors['page_a_hover_style'],1,$defaultColorsCopy['page_a_hover_style']);

	// extra css
	construct_header("Extra CSS Properties",2);

	// get custmoized...
	if($defaultColorsCopy['page_extra'] == "scyth543216789") {
		$customized = " style=\"color: #BB0000; font-weight: bold;\"";
	} else {
		$customized = "";
	}

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc1_bottom\" colspan=\"2\" style=\"text-align: center;\"><textarea name=\"edit_colors[page_extra]\" cols=\"60\" rows=\"10\"".$customized.">".htmlspecialchars($defaultColors['page_extra'])."</textarea></td>\n");
	print("\t\t</tr>\n");

	construct_footer(2,"color_submit");
	construct_table_END();


	print("\n\n<br /><br />\n\n");


	// MISCELLANEOUS BACKGROUND \\
	// not a clue what i'm using this for yet...
	// a few months later....
	// i still don't know what i'm using it for
	// *COMMENTS IT OUT* (for later use? meh)
	/*construct_table("options","edit_colors","color_submit");

	construct_header("Miscellaneous Style Options",2);

	constructColorText(1,"Background Color","","edit_colors","misc_background",$defaultColors['misc_background'],0,$defaultColorsCopy['misc_background'],1);

	constructColorText(2,"Font Color","","edit_colors","misc_color",$defaultColors['misc_color'],0,$defaultColorsCopy['misc_color'],1);

	constructColorText(1,"Font Family","","edit_colors","misc_family",$defaultColors['misc_family'],0,$defaultColorsCopy['misc_family']);

	constructColorText(2,"Font Size","","edit_colors","misc_size",$defaultColors['misc_size'],0,$defaultColorsCopy['misc_size']);

	constructColorText(1,"Font Style","","edit_colors","misc_style",$defaultColors['misc_style'],0,$defaultColorsCopy['misc_style']);

	constructColorText(2,"Font Weight","","edit_colors","misc_weight",$defaultColors['misc_weight'],1,$defaultColorsCopy['misc_weight']);

	// extra css
	construct_header("Extra CSS Properties",2);

	// get custmoized...
	if($defaultColorsCopy['misc_extra'] == "scyth543216789") {
		$customized = " style=\"color: #BB0000; font-weight: bold;\"";
	} else {
		$customized = "";
	}

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc1_bottom\" colspan=\"2\" style=\"text-align: center;\"><textarea name=\"edit_colors[misc_extra]\" cols=\"60\" rows=\"10\"".$customized.">".htmlspecialchars($defaultColors['misc_extra'])."</textarea></td>\n");
	print("\t\t</tr>\n");

	construct_footer(2,"color_submit");
	construct_table_END();


	print("\n\n<br /><br />\n\n");*/


	// CATEGORY \\
	construct_table("options","edit_colors","color_submit");

	construct_header("Category Style Options",2);

	constructColorText(1,"Background Color","","edit_colors","cat_background",$defaultColors['cat_background'],0,$defaultColorsCopy['cat_background'],1);

	constructColorText(2,"Font Color","","edit_colors","cat_color",$defaultColors['cat_color'],0,$defaultColorsCopy['cat_color'],1);

	constructColorText(1,"Font Family","","edit_colors","cat_family",$defaultColors['cat_family'],0,$defaultColorsCopy['cat_family']);

	constructColorText(2,"Font Size","","edit_colors","cat_size",$defaultColors['cat_size'],0,$defaultColorsCopy['cat_size']);

	constructColorText(1,"Font Style","","edit_colors","cat_style",$defaultColors['cat_style'],0,$defaultColorsCopy['cat_style']);

	constructColorText(2,"Font Weight","","edit_colors","cat_weight",$defaultColors['cat_weight'],1,$defaultColorsCopy['cat_weight']);

	// regular link
	construct_header("Regular Link",2);

	constructColorText(1,"Link Background","","edit_colors","cat_a_link_bg",$defaultColors['cat_a_link_bg'],0,$defaultColorsCopy['cat_a_link_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","cat_a_link_color",$defaultColors['cat_a_link_color'],0,$defaultColorsCopy['cat_a_link_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","cat_a_link_decoration",$defaultColors['cat_a_link_decoration'],0,$defaultColorsCopy['cat_a_link_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","cat_a_link_weight",$defaultColors['cat_a_link_weight'],0,$defaultColorsCopy['cat_a_link_weight']);

	constructColorText(1,"Link Style","","edit_colors","cat_a_link_style",$defaultColors['cat_a_link_style'],1,$defaultColorsCopy['cat_a_link_style']);

	// visited link
	construct_header("Visited Link",2);

	constructColorText(1,"Link Background","","edit_colors","cat_a_visited_bg",$defaultColors['cat_a_visited_bg'],0,$defaultColorsCopy['cat_a_visited_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","cat_a_visited_color",$defaultColors['cat_a_visited_color'],0,$defaultColorsCopy['cat_a_visited_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","cat_a_visited_decoration",$defaultColors['cat_a_visited_decoration'],0,$defaultColorsCopy['cat_a_visited_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","cat_a_visited_weight",$defaultColors['cat_a_visited_weight'],0,$defaultColorsCopy['cat_a_visited_weight']);

	constructColorText(1,"Link Style","","edit_colors","cat_a_visited_style",$defaultColors['cat_a_visited_style'],1,$defaultColorsCopy['cat_a_visited_style']);

	// hover/active link
	construct_header("Hover/Active Link",2);

	constructColorText(1,"Link Background","","edit_colors","cat_a_hover_bg",$defaultColors['cat_a_hover_bg'],0,$defaultColorsCopy['cat_a_hover_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","cat_a_hover_color",$defaultColors['cat_a_hover_color'],0,$defaultColorsCopy['cat_a_hover_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","cat_a_hover_decoration",$defaultColors['cat_a_hover_decoration'],0,$defaultColorsCopy['cat_a_hover_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","cat_a_hover_weight",$defaultColors['cat_a_hover_weight'],0,$defaultColorsCopy['cat_a_hover_weight']);

	constructColorText(1,"Link Style","","edit_colors","cat_a_hover_style",$defaultColors['cat_a_hover_style'],1,$defaultColorsCopy['cat_a_hover_style']);

	// extra css
	construct_header("Extra CSS Properties",2);

	// get custmoized...
	if($defaultColorsCopy['cat_extra'] == "scyth543216789") {
		$customized = " style=\"color: #BB0000; font-weight: bold;\"";
	} else {
		$customized = "";
	}

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc1_bottom\" colspan=\"2\" style=\"text-align: center;\"><textarea name=\"edit_colors[cat_extra]\" cols=\"60\" rows=\"10\"".$customized.">".htmlspecialchars($defaultColors['cat_extra'])."</textarea></td>\n");
	print("\t\t</tr>\n");

	construct_footer(2,"color_submit");
	construct_table_END();


	print("\n\n<br /><br />\n\n");


	// HEADER/FOOTER \\
	construct_table("options","edit_colors","color_submit");

	construct_header("Header/Footer Style Options",2);

	constructColorText(1,"Background Color","","edit_colors","headFoot_background",$defaultColors['headFoot_background'],0,$defaultColorsCopy['headFoot_background'],1);

	constructColorText(2,"Font Color","","edit_colors","headFoot_color",$defaultColors['headFoot_color'],0,$defaultColorsCopy['headFoot_color'],1);

	constructColorText(1,"Font Family","","edit_colors","headFoot_family",$defaultColors['headFoot_family'],0,$defaultColorsCopy['headFoot_family']);

	constructColorText(2,"Font Size","","edit_colors","headFoot_size",$defaultColors['headFoot_size'],0,$defaultColorsCopy['headFoot_size']);

	constructColorText(1,"Font Style","","edit_colors","headFoot_style",$defaultColors['headFoot_style'],0,$defaultColorsCopy['headFoot_style']);

	constructColorText(2,"Font Weight","","edit_colors","headFoot_weight",$defaultColors['headFoot_weight'],1,$defaultColorsCopy['headFoot_weight']);

	// regular link
	construct_header("Regular Link",2);

	constructColorText(1,"Link Background","","edit_colors","headFoot_a_link_bg",$defaultColors['headFoot_a_link_bg'],0,$defaultColorsCopy['headFoot_a_link_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","headFoot_a_link_color",$defaultColors['headFoot_a_link_color'],0,$defaultColorsCopy['headFoot_a_link_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","headFoot_a_link_decoration",$defaultColors['headFoot_a_link_decoration'],0,$defaultColorsCopy['headFoot_a_link_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","headFoot_a_link_weight",$defaultColors['headFoot_a_link_weight'],0,$defaultColorsCopy['headFoot_a_link_weight']);

	constructColorText(1,"Link Style","","edit_colors","headFoot_a_link_style",$defaultColors['headFoot_a_link_style'],1,$defaultColorsCopy['headFoot_a_link_style']);

	// visited link
	construct_header("Visited Link",2);

	constructColorText(1,"Link Background","","edit_colors","headFoot_a_visited_bg",$defaultColors['headFoot_a_visited_bg'],0,$defaultColorsCopy['headFoot_a_visited_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","headFoot_a_visited_color",$defaultColors['headFoot_a_visited_color'],0,$defaultColorsCopy['headFoot_a_visited_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","headFoot_a_visited_decoration",$defaultColors['headFoot_a_visited_decoration'],0,$defaultColorsCopy['headFoot_a_visited_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","headFoot_a_visited_weight",$defaultColors['headFoot_a_visited_weight'],0,$defaultColorsCopy['headFoot_a_visited_weight']);

	constructColorText(1,"Link Style","","edit_colors","headFoot_a_visited_style",$defaultColors['headFoot_a_visited_style'],1,$defaultColorsCopy['headFoot_a_visited_style']);

	// hover/active link
	construct_header("Hover/Active Link",2);

	constructColorText(1,"Link Background","","edit_colors","headFoot_a_hover_bg",$defaultColors['headFoot_a_hover_bg'],0,$defaultColorsCopy['headFoot_a_hover_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","headFoot_a_hover_color",$defaultColors['headFoot_a_hover_color'],0,$defaultColorsCopy['headFoot_a_hover_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","headFoot_a_hover_decoration",$defaultColors['headFoot_a_hover_decoration'],0,$defaultColorsCopy['headFoot_a_hover_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","headFoot_a_hover_weight",$defaultColors['headFoot_a_hover_weight'],0,$defaultColorsCopy['headFoot_a_hover_weight']);

	constructColorText(1,"Link Style","","edit_colors","headFoot_a_hover_style",$defaultColors['headFoot_a_hover_style'],1,$defaultColorsCopy['headFoot_a_hover_style']);

	// extra css
	construct_header("Extra CSS Properties",2);

	// get custmoized...
	if($defaultColorsCopy['headFoot_extra'] == "scyth543216789") {
		$customized = " style=\"color: #BB0000; font-weight: bold;\"";
	} else {
		$customized = "";
	}

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc1_bottom\" colspan=\"2\" style=\"text-align: center;\"><textarea name=\"edit_colors[headFoot_extra]\" cols=\"60\" rows=\"10\"".$customized.">".htmlspecialchars($defaultColors['headFoot_extra'])."</textarea></td>\n");
	print("\t\t</tr>\n");

	construct_footer(2,"color_submit");
	construct_table_END();


	print("\n\n<br /><br />\n\n");


	// FIRST ALTERNATING \\
	construct_table("options","edit_colors","color_submit");

	construct_header("First Alternating Color Style Options",2);

	constructColorText(1,"Background Color","","edit_colors","first_background",$defaultColors['first_background'],0,$defaultColorsCopy['first_background'],1);

	constructColorText(2,"Font Color","","edit_colors","first_color",$defaultColors['first_color'],0,$defaultColorsCopy['first_color'],1);

	constructColorText(1,"Font Family","","edit_colors","first_family",$defaultColors['first_family'],0,$defaultColorsCopy['first_family']);

	constructColorText(2,"Font Size","","edit_colors","first_size",$defaultColors['first_size'],0,$defaultColorsCopy['first_size']);

	constructColorText(1,"Font Style","","edit_colors","first_style",$defaultColors['first_style'],0,$defaultColorsCopy['first_style']);

	constructColorText(2,"Font Weight","","edit_colors","first_weight",$defaultColors['first_weight'],1,$defaultColorsCopy['first_weight']);

	// regular link
	construct_header("Regular Link",2);

	constructColorText(1,"Link Background","","edit_colors","first_a_link_bg",$defaultColors['first_a_link_bg'],0,$defaultColorsCopy['first_a_link_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","first_a_link_color",$defaultColors['first_a_link_color'],0,$defaultColorsCopy['first_a_link_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","first_a_link_decoration",$defaultColors['first_a_link_decoration'],0,$defaultColorsCopy['first_a_link_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","first_a_link_weight",$defaultColors['first_a_link_weight'],0,$defaultColorsCopy['first_a_link_weight']);

	constructColorText(1,"Link Style","","edit_colors","first_a_link_style",$defaultColors['first_a_link_style'],1,$defaultColorsCopy['first_a_link_style']);

	// visited link
	construct_header("Visited Link",2);

	constructColorText(1,"Link Background","","edit_colors","first_a_visited_bg",$defaultColors['first_a_visited_bg'],0,$defaultColorsCopy['first_a_visited_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","first_a_visited_color",$defaultColors['first_a_visited_color'],0,$defaultColorsCopy['first_a_visited_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","first_a_visited_decoration",$defaultColors['first_a_visited_decoration'],0,$defaultColorsCopy['first_a_visited_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","first_a_visited_weight",$defaultColors['first_a_visited_weight'],0,$defaultColorsCopy['first_a_visited_weight']);

	constructColorText(1,"Link Style","","edit_colors","first_a_visited_style",$defaultColors['first_a_visited_style'],1,$defaultColorsCopy['first_a_visited_style']);

	// hover/active link
	construct_header("Hover/Active Link",2);

	constructColorText(1,"Link Background","","edit_colors","first_a_hover_bg",$defaultColors['first_a_hover_bg'],0,$defaultColorsCopy['first_a_hover_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","first_a_hover_color",$defaultColors['first_a_hover_color'],0,$defaultColorsCopy['first_a_hover_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","first_a_hover_decoration",$defaultColors['first_a_hover_decoration'],0,$defaultColorsCopy['first_a_hover_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","first_a_hover_weight",$defaultColors['first_a_hover_weight'],0,$defaultColorsCopy['first_a_hover_weight']);

	constructColorText(1,"Link Style","","edit_colors","first_a_hover_style",$defaultColors['first_a_hover_style'],1,$defaultColorsCopy['first_a_hover_style']);

	// extra css
	construct_header("Extra CSS Properties",2);

	// get custmoized...
	if($defaultColorsCopy['first_extra'] == "scyth543216789") {
		$customized = " style=\"color: #BB0000; font-weight: bold;\"";
	} else {
		$customized = "";
	}

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc1_bottom\" colspan=\"2\" style=\"text-align: center;\"><textarea name=\"edit_colors[first_extra]\" cols=\"60\" rows=\"10\"".$customized.">".htmlspecialchars($defaultColors['first_extra'])."</textarea></td>\n");
	print("\t\t</tr>\n");

	construct_footer(2,"color_submit");
	construct_table_END();


	print("\n\n<br /><br />\n\n");


	// SECOND ALTERNATING \\
	construct_table("options","edit_colors","color_submit");

	construct_header("Second Alternating Color Style Options",2);

	constructColorText(1,"Background Color","","edit_colors","second_background",$defaultColors['second_background'],0,$defaultColorsCopy['second_background'],1);

	constructColorText(2,"Font Color","","edit_colors","second_color",$defaultColors['second_color'],0,$defaultColorsCopy['second_color'],1);

	constructColorText(1,"Font Family","","edit_colors","second_family",$defaultColors['second_family'],0,$defaultColorsCopy['second_family']);

	constructColorText(2,"Font Size","","edit_colors","second_size",$defaultColors['second_size'],0,$defaultColorsCopy['second_size']);

	constructColorText(1,"Font Style","","edit_colors","second_style",$defaultColors['second_style'],0,$defaultColorsCopy['second_style']);

	constructColorText(2,"Font Weight","","edit_colors","second_weight",$defaultColors['second_weight'],1,$defaultColorsCopy['second_weight']);

	// regular link
	construct_header("Regular Link",2);

	constructColorText(1,"Link Background","","edit_colors","second_a_link_bg",$defaultColors['second_a_link_bg'],0,$defaultColorsCopy['second_a_link_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","second_a_link_color",$defaultColors['second_a_link_color'],0,$defaultColorsCopy['second_a_link_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","second_a_link_decoration",$defaultColors['second_a_link_decoration'],1,$defaultColorsCopy['second_a_link_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","second_a_link_weight",$defaultColors['second_a_link_weight'],0,$defaultColorsCopy['second_a_link_weight']);

	constructColorText(1,"Link Style","","edit_colors","second_a_link_style",$defaultColors['second_a_link_style'],1,$defaultColorsCopy['second_a_link_style']);

	// visited link
	construct_header("Visited Link",2);

	constructColorText(1,"Link Background","","edit_colors","second_a_visited_bg",$defaultColors['second_a_visited_bg'],0,$defaultColorsCopy['second_a_visited_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","second_a_visited_color",$defaultColors['second_a_visited_color'],0,$defaultColorsCopy['second_a_visited_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","second_a_visited_decoration",$defaultColors['second_a_visited_decoration'],0,$defaultColorsCopy['second_a_visited_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","second_a_visited_weight",$defaultColors['second_a_visited_weight'],0,$defaultColorsCopy['second_a_visited_weight']);

	constructColorText(1,"Link Style","","edit_colors","second_a_visited_style",$defaultColors['second_a_visited_style'],1,$defaultColorsCopy['second_a_visited_style']);

	// hover/active link
	construct_header("Hover/Active Link",2);

	constructColorText(1,"Link Background","","edit_colors","second_a_hover_bg",$defaultColors['second_a_hover_bg'],0,$defaultColorsCopy['second_a_hover_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","second_a_hover_color",$defaultColors['second_a_hover_color'],0,$defaultColorsCopy['second_a_hover_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","second_a_hover_decoration",$defaultColors['second_a_hover_decoration'],0,$defaultColorsCopy['second_a_hover_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","second_a_hover_weight",$defaultColors['second_a_hover_weight'],0,$defaultColorsCopy['second_a_hover_weight']);

	constructColorText(1,"Link Style","","edit_colors","second_a_hover_style",$defaultColors['second_a_hover_style'],1,$defaultColorsCopy['second_a_hover_style']);

	// extra css
	construct_header("Extra CSS Properties",2);

	// get custmoized...
	if($defaultColorsCopy['second_extra'] == "scyth543216789") {
		$customized = " style=\"color: #BB0000; font-weight: bold;\"";
	} else {
		$customized = "";
	}

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc1_bottom\" colspan=\"2\" style=\"text-align: center;\"><textarea name=\"edit_colors[second_extra]\" cols=\"60\" rows=\"10\"".$customized.">".htmlspecialchars($defaultColors['second_extra'])."</textarea></td>\n");
	print("\t\t</tr>\n");

	construct_footer(2,"color_submit");
	construct_table_END();


	print("\n\n<br /><br />\n\n");


	// NAV TOP \\
	construct_table("options","edit_colors","color_submit");

	construct_header("Top Navigation Style Options",2);

	constructColorText(1,"Background Color","","edit_colors","nav_background",$defaultColors['nav_background'],0,$defaultColorsCopy['nav_background'],1);

	constructColorText(2,"Font Color","","edit_colors","nav_color",$defaultColors['nav_color'],0,$defaultColorsCopy['nav_color'],1);

	constructColorText(1,"Font Family","","edit_colors","nav_family",$defaultColors['nav_family'],0,$defaultColorsCopy['nav_family']);

	constructColorText(2,"Font Size","","edit_colors","nav_size",$defaultColors['nav_size'],0,$defaultColorsCopy['nav_size']);

	constructColorText(1,"Font Style","","edit_colors","nav_style",$defaultColors['nav_style'],0,$defaultColorsCopy['nav_style']);

	constructColorText(2,"Font Weight","","edit_colors","nav_weight",$defaultColors['nav_weight'],1,$defaultColorsCopy['nav_weight']);

	// regular link
	construct_header("Regular Link",2);

	constructColorText(1,"Link Background","","edit_colors","nav_a_link_bg",$defaultColors['nav_a_link_bg'],0,$defaultColorsCopy['nav_a_link_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","nav_a_link_color",$defaultColors['nav_a_link_color'],0,$defaultColorsCopy['nav_a_link_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","nav_a_link_decoration",$defaultColors['nav_a_link_decoration'],0,$defaultColorsCopy['nav_a_link_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","nav_a_link_weight",$defaultColors['nav_a_link_weight'],0,$defaultColorsCopy['nav_a_link_weight']);

	constructColorText(1,"Link Style","","edit_colors","nav_a_link_style",$defaultColors['nav_a_link_style'],1,$defaultColorsCopy['nav_a_link_style']);

	// visited link
	construct_header("Visited Link",2);

	constructColorText(1,"Link Background","","edit_colors","nav_a_visited_bg",$defaultColors['nav_a_visited_bg'],0,$defaultColorsCopy['nav_a_visited_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","nav_a_visited_color",$defaultColors['nav_a_visited_color'],0,$defaultColorsCopy['nav_a_visited_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","nav_a_visited_decoration",$defaultColors['nav_a_visited_decoration'],0,$defaultColorsCopy['nav_a_visited_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","nav_a_visited_weight",$defaultColors['nav_a_visited_weight'],0,$defaultColorsCopy['nav_a_visited_weight']);

	constructColorText(1,"Link Style","","edit_colors","nav_a_visited_style",$defaultColors['nav_a_visited_style'],1,$defaultColorsCopy['nav_a_visited_style']);

	// hover/active link
	construct_header("Hover/Active Link",2);

	constructColorText(1,"Link Background","","edit_colors","nav_a_hover_bg",$defaultColors['nav_a_hover_bg'],0,$defaultColorsCopy['nav_a_hover_bg'],1);

	constructColorText(2,"Link Color","","edit_colors","nav_a_hover_color",$defaultColors['nav_a_hover_color'],0,$defaultColorsCopy['nav_a_hover_color'],1);

	constructColorText(1,"Link Decoration","","edit_colors","nav_a_hover_decoration",$defaultColors['nav_a_hover_decoration'],0,$defaultColorsCopy['nav_a_hover_decoration']);

	constructColorText(2,"Link Weight","","edit_colors","nav_a_hover_weight",$defaultColors['nav_a_hover_weight'],0,$defaultColorsCopy['nav_a_hover_weight']);

	constructColorText(1,"Link Style","","edit_colors","nav_a_hover_style",$defaultColors['nav_a_hover_style'],1,$defaultColorsCopy['nav_a_hover_style']);

	// extra css
	construct_header("Extra CSS Properties",2);

	// get custmoized...
	if($defaultColorsCopy['nav_extra'] == "scyth543216789") {
		$customized = " style=\"color: #BB0000; font-weight: bold;\"";
	} else {
		$customized = "";
	}

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc1_bottom\" colspan=\"2\" style=\"text-align: center;\"><textarea name=\"edit_colors[nav_extra]\" cols=\"60\" rows=\"10\"".$customized.">".htmlspecialchars($defaultColors['nav_extra'])."</textarea></td>\n");
	print("\t\t</tr>\n");

	construct_footer(2,"color_submit");
	construct_table_END();


	print("\n\n<br /><br />\n\n");


	// INPUT \\
	construct_table("options","edit_colors","color_submit");

	construct_header("Input Style Options",2);

	constructColorText(1,"Background Color","","edit_colors","input_background",$defaultColors['input_background'],0,$defaultColorsCopy['input_background'],1);

	constructColorText(2,"Font Color","","edit_colors","input_color",$defaultColors['input_color'],0,$defaultColorsCopy['input_color'],1);

	constructColorText(1,"Font Family","","edit_colors","input_family",$defaultColors['input_family'],0,$defaultColorsCopy['input_family']);

	constructColorText(2,"Font Size","","edit_colors","input_size",$defaultColors['input_size'],0,$defaultColorsCopy['input_size']);

	constructColorText(1,"Font Style","","edit_colors","input_style",$defaultColors['input_style'],0,$defaultColorsCopy['input_style']);

	constructColorText(2,"Font Weight","","edit_colors","input_weight",$defaultColors['input_weight'],1,$defaultColorsCopy['input_weight']);

	// extra css
	construct_header("Extra CSS Properties",2);

	// get custmoized...
	if($defaultColorsCopy['input_extra'] == "scyth543216789") {
		$customized = " style=\"color: #BB0000; font-weight: bold;\"";
	} else {
		$customized = "";
	}

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc1_bottom\" colspan=\"2\" style=\"text-align: center;\"><textarea name=\"edit_colors[input_extra]\" cols=\"60\" rows=\"10\"".$customized.">".htmlspecialchars($defaultColors['input_extra'])."</textarea></td>\n");
	print("\t\t</tr>\n");

	construct_footer(2,"color_submit");
	construct_table_END();


	print("\n\n<br /><br />\n\n");


	// BUTTON \\
	construct_table("options","edit_colors","color_submit");

	construct_header("Button Style Options",2);

	constructColorText(1,"Background Color","","edit_colors","button_background",$defaultColors['button_background'],0,$defaultColorsCopy['button_background'],1);

	constructColorText(2,"Font Color","","edit_colors","button_color",$defaultColors['button_color'],0,$defaultColorsCopy['button_color'],1);

	constructColorText(1,"Font Family","","edit_colors","button_family",$defaultColors['button_family'],0,$defaultColorsCopy['button_family']);

	constructColorText(2,"Font Size","","edit_colors","button_size",$defaultColors['button_size'],0,$defaultColorsCopy['button_size']);

	constructColorText(1,"Font Style","","edit_colors","button_style",$defaultColors['button_style'],0,$defaultColorsCopy['button_style']);

	constructColorText(2,"Font Weight","","edit_colors","button_weight",$defaultColors['button_weight'],1,$defaultColorsCopy['button_weight']);

	// extra css
	construct_header("Extra CSS Properties",2);

	// get custmoized...
	if($defaultColorsCopy['button_extra'] == "scyth543216789") {
		$customized = " style=\"color: #BB0000; font-weight: bold;\"";
	} else {
		$customized = "";
	}

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc1_bottom\" colspan=\"2\" style=\"text-align: center;\"><textarea name=\"edit_colors[button_extra]\" cols=\"60\" rows=\"10\"".$customized.">".htmlspecialchars($defaultColors['button_extra'])."</textarea></td>\n");
	print("\t\t</tr>\n");

	construct_footer(2,"color_submit");
	construct_table_END();


	print("\n\n<br /><br />\n\n");


	// SELECT \\
	construct_table("options","edit_colors","color_submit");

	construct_header("Select Menus Style Options",2);

	constructColorText(1,"Background Color","","edit_colors","select_background",$defaultColors['select_background'],0,$defaultColorsCopy['select_background'],1);

	constructColorText(2,"Font Color","","edit_colors","select_color",$defaultColors['select_color'],0,$defaultColorsCopy['select_color'],1);

	constructColorText(1,"Font Family","","edit_colors","select_family",$defaultColors['select_family'],0,$defaultColorsCopy['select_family']);

	constructColorText(2,"Font Size","","edit_colors","select_size",$defaultColors['select_size'],0,$defaultColorsCopy['select_size']);

	constructColorText(1,"Font Style","","edit_colors","select_style",$defaultColors['select_style'],0,$defaultColorsCopy['select_style']);

	constructColorText(2,"Font Weight","","edit_colors","select_weight",$defaultColors['select_weight'],1,$defaultColorsCopy['select_weight']);

	// extra css
	construct_header("Extra CSS Properties",2);

	// get custmoized...
	if($defaultColorsCopy['select_extra'] == "scyth543216789") {
		$customized = " style=\"color: #BB0000; font-weight: bold;\"";
	} else {
		$customized = "";
	}

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc1_bottom\" colspan=\"2\" style=\"text-align: center;\"><textarea name=\"edit_colors[select_extra]\" cols=\"60\" rows=\"10\"".$customized.">".htmlspecialchars($defaultColors['select_extra'])."</textarea></td>\n");
	print("\t\t</tr>\n");

	construct_footer(2,"color_submit");
	construct_table_END();


	print("\n\n<br /><br />\n\n");


	// SMALL FONT \\
	construct_table("options","edit_colors","color_submit");

	construct_header("Small Font Style Options",2);

	constructColorText(1,"Background Color","","edit_colors","small_background",$defaultColors['small_background'],0,$defaultColorsCopy['small_background'],1);

	constructColorText(2,"Font Color","","edit_colors","small_color",$defaultColors['small_color'],0,$defaultColorsCopy['small_color'],1);

	constructColorText(1,"Font Family","","edit_colors","small_family",$defaultColors['small_family'],0,$defaultColorsCopy['small_family']);

	constructColorText(2,"Font Size","","edit_colors","small_size",$defaultColors['small_size'],0,$defaultColorsCopy['small_size']);

	constructColorText(1,"Font Style","","edit_colors","small_style",$defaultColors['small_style'],0,$defaultColorsCopy['small_style']);

	constructColorText(2,"Font Weight","","edit_colors","small_weight",$defaultColors['small_weight'],1,$defaultColorsCopy['small_weight']);

	// extra css
	construct_header("Extra CSS Properties",2);

	// get custmoized...
	if($defaultColorsCopy['small_extra'] == "scyth543216789") {
		$customized = " style=\"color: #BB0000; font-weight: bold;\"";
	} else {
		$customized = "";
	}

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc1_bottom\" colspan=\"2\" style=\"text-align: center;\"><textarea name=\"edit_colors[small_extra]\" cols=\"60\" rows=\"10\"".$customized.">".htmlspecialchars($defaultColors['small_extra'])."</textarea></td>\n");
	print("\t\t</tr>\n");

	construct_footer(2,"color_submit");
	construct_table_END();


	print("\n\n<br /><br />\n\n");


	// TIME FONT \\
	construct_table("options","edit_colors","color_submit");

	construct_header("Time Font Style Options",2);

	constructColorText(1,"Background Color","","edit_colors","time_background",$defaultColors['time_background'],0,$defaultColorsCopy['time_background'],1);

	constructColorText(2,"Font Color","","edit_colors","time_color",$defaultColors['time_color'],0,$defaultColorsCopy['time_color'],1);

	constructColorText(1,"Font Family","","edit_colors","time_family",$defaultColors['time_family'],0,$defaultColorsCopy['time_family']);

	constructColorText(2,"Font Size","","edit_colors","time_size",$defaultColors['time_size'],0,$defaultColorsCopy['time_size']);

	constructColorText(1,"Font Style","","edit_colors","time_style",$defaultColors['time_style'],0,$defaultColorsCopy['time_style']);

	constructColorText(2,"Font Weight","","edit_colors","time_weight",$defaultColors['time_weight'],1,$defaultColorsCopy['time_weight']);

	// extra css
	construct_header("Extra CSS Properties",2);

	// get custmoized...
	if($defaultColorsCopy['time_extra'] == "scyth543216789") {
		$customized = " style=\"color: #BB0000; font-weight: bold;\"";
	} else {
		$customized = "";
	}

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc1_bottom\" colspan=\"2\" style=\"text-align: center;\"><textarea name=\"edit_colors[time_extra]\" cols=\"60\" rows=\"10\"".$customized.">".htmlspecialchars($defaultColors['time_extra'])."</textarea></td>\n");
	print("\t\t</tr>\n");

	construct_footer(2,"color_submit");
	construct_table_END();


	print("\n\n<br /><br />\n\n");


	// FORUM JUMP \\
	construct_table("options","edit_colors","color_submit");

	construct_header("Forum Jump Style Options",2);

	constructColorText(1,"Level 1 Text Color","","edit_colors","jump_1_color",$defaultColors['jump_1_color'],0,$defaultColorsCopy['jump_1_color'],1);

	constructColorText(2,"Level 1 Background Color","","edit_colors","jump_1_bg",$defaultColors['jump_1_bg'],0,$defaultColorsCopy['jump_1_bg'],1);

	constructColorText(1,"Level 2 Text Color","","edit_colors","jump_2_color",$defaultColors['jump_2_color'],0,$defaultColorsCopy['jump_2_color'],1);

	constructColorText(2,"Level 2 Background Color","","edit_colors","jump_2_bg",$defaultColors['jump_2_bg'],0,$defaultColorsCopy['jump_2_bg'],1);

	constructColorText(1,"Level 3 Text Color","","edit_colors","jump_3_color",$defaultColors['jump_3_color'],0,$defaultColorsCopy['jump_3_color'],1);

	constructColorText(2,"Level 3 Background Color","","edit_colors","jump_3_bg",$defaultColors['jump_3_bg'],0,$defaultColorsCopy['jump_3_bg'],1);

	constructColorText(1,"Level 4 Text Color","","edit_colors","jump_4_color",$defaultColors['jump_4_color'],0,$defaultColorsCopy['jump_4_color'],1);

	constructColorText(2,"Level 4 Background Color","","edit_colors","jump_4_bg",$defaultColors['jump_4_bg'],1,$defaultColorsCopy['jump_4_bg'],1);

	construct_footer(2,"color_submit");
	construct_table_END();


	print("\n\n<br /><br />\n\n");


	// EXTRA CSS \\
	construct_table("options","edit_colors","color_submit");

	construct_header("Advanced User: Extra CSS",2);

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc1_bottom\" colspan=\"2\" style=\"text-align: center;\"><textarea name=\"edit_colors[extra_css]\" cols=\"60\" rows=\"10\" style=\"color: #BB0000; font-weight: bold;\">".htmlspecialchars($custColor['extra_css'])."</textarea></td>\n");
	print("\t\t</tr>\n");

	construct_footer(2,"color_submit");
	construct_table_END(1);

	// do footer
	admin_footer();
}



// ############################################# \\
// ############################################# \\
// ##############SHOUT OUT TO scyth!############ \\
// ################ scyth.net ################## \\
// ########scyth loves the replacements####### \\
// ###that's why i'm giving him this shoutout### \\
// ############################################# \\
// ############################################# \\


// ##### DO DELETE REPLACEMENT ##### \\
else if($_GET['do'] == "delete_replacement") {
	// get replaceid
	$getReplaceId = query("SELECT * FROM replacement_variables WHERE replaceid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($getReplaceId)) {
		construct_error("Sorry, a replacement variable with the given ID does not exist. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// array
	$replaceinfo = mysql_fetch_array($getReplaceId);

	// construct confirm!!!
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// delete replacement
			query("DELETE FROM replacement_variables WHERE replaceid = '".$replaceinfo['replaceid']."'");

			// redirect to thankyou page...
			redirect("thankyou.php?message=Replacement deleted successfully. You will now be redirected to the Replacements Manager.&uri=style.php?do=manager_replace");
		}

		// no...
		else {
			// redirect to thankyou page...
			redirect("style.php?do=manager_replace");
		}
	}
	
	// do a confirm page...
	construct_confirm("Are you sure you want to permanently delete the replacement <em>".$replaceinfo['find']."</em>? This cannot be undone!");
}

// ##### DO EDIT REPLACEMENT ##### \\
else if($_GET['do'] == "edit_replacement") {
	// get replaceid
	$getReplaceId = query("SELECT * FROM replacement_variables WHERE replaceid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($getReplaceId)) {
		construct_error("Sorry, a replacement variable with the given ID does not exist. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// array
	$replaceinfo = mysql_fetch_array($getReplaceId);

	if($_POST['edit_replacement']['set_form']) {
		// get styleid.. AND getIsGlobal
		if($_POST['edit_replacement']['styleid'] != "global") {
			$getStyleId = $_POST['edit_replacement']['styleid'];
			$getIsGlobal = 0;
		} else {
			$getStyleId = 0;
			$getIsGlobal = 1;
		}

		// form query by hand.. easier...
		$query = "UPDATE replacement_variables SET styleid = '".$getStyleId."' , is_global = '".$getIsGlobal."' , find = '".addslashes($_POST['edit_replacement']['find'])."' , replacement = '".addslashes($_POST['edit_replacement']['replacement'])."' WHERE replaceid = '".$replaceinfo['replaceid']."'";

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Replacement added successfully. You will now be redirected to the Replacements Manager.&uri=style.php?do=manager_replace");
	}
		
	// do header
	admin_header("wtcBB Admin Panel - Styles - Edit Replacement");

	construct_title("Edit Replacement");

	construct_table("options","edit_replacement","replace_submit",1);

	construct_header("Edit Replacement <em>".$replaceinfo['find']."</em> <span class=\"small\">(id: ".$replaceinfo['replaceid'].")",2);

	construct_select_begin(1,"Style","Select a style for this replacement to be specific to. Select \"Global\" for this replacement to take effect in all styles.","edit_replacement","styleid");
			if($replaceinfo['is_global'] == 1) {
				print("<option value=\"global\" selected=\"selected\">Global</option>\n");
			} else {
				print("<option value=\"global\">Global</option>\n");
			}

			// get styles...
			$checkStyles = query("SELECT * FROM styles ORDER BY display_order, title");

			// loop through styles
			while($styleinfo = mysql_fetch_array($checkStyles)) {
				// get selected...
				if($replaceinfo['styleid'] == $styleinfo['styleid']) {
					$selected = " selected=\"selected\"";
				} else {
					$selected = "";
				}

				print("<option value=\"".$styleinfo['styleid']."\"".$selected.">".$styleinfo['title']."</option>\n");
			}

	construct_select_end(1);

	construct_text(2,"Text to Find","Input here the text you want to find.","edit_replacement","find",$replaceinfo['find']);

	construct_textarea(1,"Text to Replace With","Input here the text you want to replace with.","edit_replacement","replacement",$replaceinfo['replacement']);

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"2\"><button type=\"submit\" ".$submitbg.">Save</button> &nbsp;&nbsp;&nbsp; <button type=\"reset\" ".$submitbg.">Reset</button></td></tr>\n");
	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO ADD REPLACEMENT ##### \\
else if($_GET['do'] == "add_replacement") {
	if($_POST['add_replacement']['set_form']) {
		// get styleid.. AND getIsGlobal
		if($_POST['add_replacement']['styleid'] != "global") {
			$getStyleId = $_POST['add_replacement']['styleid'];
			$getIsGlobal = 0;
		} else {
			$getStyleId = 0;
			$getIsGlobal = 1;
		}

		// form query by hand.. easier...
		$query = "INSERT INTO replacement_variables (styleid,is_global,find,replacement) VALUES ('".$getStyleId."','".$getIsGlobal."','".addslashes($_POST['add_replacement']['find'])."','".addslashes($_POST['add_replacement']['replacement'])."')";

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Replacement added successfully. You will now be redirected to the Replacements Manager.&uri=style.php?do=manager_replace");
	}
		
	// do header
	admin_header("wtcBB Admin Panel - Styles - Add a Replacement");

	construct_title("Add a Replacement");

	construct_table("options","add_replacement","replace_submit",1);

	construct_header("Add a Replacement",2);

	construct_select_begin(1,"Style","Select a style for this replacement to be specific to. Select \"Global\" for this replacement to take effect in all styles.","add_replacement","styleid");
		if(!isset($_GET['styleid'])) {
			print("<option value=\"global\" selected=\"selected\">Global</option>\n");
		} else {
			print("<option value=\"global\">Global</option>\n");
		}

		// get styles...
		$checkStyles = query("SELECT * FROM styles ORDER BY display_order, title");

		// loop through styles
		while($styleinfo = mysql_fetch_array($checkStyles)) {
			// get selected...
			if(isset($_GET['styleid']) AND $_GET['styleid'] == $styleinfo['styleid']) {
				$selected = " selected=\"selected\"";
			} else {
				$selected = "";
			}

			print("<option value=\"".$styleinfo['styleid']."\"".$selected.">".$styleinfo['title']."</option>\n");
		}

	construct_select_end(1);

	construct_text(2,"Text to Find","Input here the text you want to find.","add_replacement","find");

	construct_textarea(1,"Text to Replace With","Input here the text you want to replace with.","add_replacement","replacement");

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"2\"><button type=\"submit\" ".$submitbg.">Save</button> &nbsp;&nbsp;&nbsp; <button type=\"reset\" ".$submitbg.">Reset</button></td></tr>\n");
	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO REPLACEMENT MANAGER ##### \\
else if($_GET['do'] == "manager_replace") {
	// get styles...
	$styleinfo_query = query("SELECT * FROM styles ORDER BY display_order, title");

	// get globals...
	$getGlobalReplacements = query("SELECT * FROM replacement_variables WHERE is_global = '1' ORDER BY find");

	// do header
	admin_header("wtcBB Admin Panel - Style System - Replacement Variables");

	construct_title("Replacement Variables");

	// show global replacements.. if there are some!
	if(mysql_num_rows($getGlobalReplacements)) {
		construct_table("options","replacement","replace_submit");
		construct_header("Global Replacements",1);

		print("\n\n\t<tr>\n");

			print("\t\t<td class=\"cat\">\n");
			print("\t\t\tReplacements\n");
			print("\t\t</td>\n\n");

		print("\t</tr>\n\n");

		print("\t<tr>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: left; font-size: 8pt; white-space: nowrap; width: 15%; padding: 5px;\">\n");
				// no need to check if there are replacements... if there weren't, we wouldn't be showing this!
				print("<ul style=\"margin: 0;\">\n");
				while($replaceinfo = mysql_fetch_array($getGlobalReplacements)) {
					print("\t<li style=\"margin: 0px;\"><strong>".htmlspecialchars($replaceinfo['find'])."</strong> : ".htmlspecialchars($replaceinfo['replacement'])." || <a href=\"style.php?do=edit_replacement&id=".$replaceinfo['replaceid']."\">Edit</a> - <a href=\"style.php?do=delete_replacement&id=".$replaceinfo['replaceid']."\">Delete</a></li>");
				}
				print("</ul>\n");
			print("\t\t</td>\n");

		print("\t</tr>\n\n");

		print("\t<tr><td class=\"footer\" style=\"border-top: none;\"><button type=\"button\" onclick=\"location.href='style.php?do=add_replacement';\" ".$submitbg.">Add Global Replacement</button></td></tr>\n");
		construct_table_END();

		print("\n\n<br /><br />\n\n");
	}
		

	construct_table("options","replacement","replace_submit");
	construct_header("Style Specific Replacements",2);

	print("\n\n\t<tr>\n");

		print("\t\t<td class=\"cat\">\n");
		print("\t\t\tStyle\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tReplacements\n");
		print("\t\t</td>\n\n");

	print("\t</tr>\n\n");

	while($styleinfo = mysql_fetch_array($styleinfo_query)) {
		print("\t<tr>\n");

			print("\t\t<td class=\"desc1\" style=\"text-align: left; white-space: nowrap; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<a href=\"style.php?do=edit_style&id=".$styleinfo['styleid']."\">".$styleinfo['title']."</a> <br /> <button type=\"button\" onclick=\"location.href='style.php?do=add_replacement&styleid=".$styleinfo['styleid']."';\" ".$submitbg.">Add Replacement</button>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: left; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				// get replacements...
				$getReplacements = query("SELECT * FROM replacement_variables WHERE styleid = '".$styleinfo['styleid']."' AND is_global = '0'");

				if(!mysql_num_rows($getReplacements)) {
					print("<strong>No Replacements exist specific to this style.</strong>\n");
				}

				// replacements!
				else {
					print("<ul style=\"margin: 0px;\">\n");
					while($replaceinfo = mysql_fetch_array($getReplacements)) {
						print("\t<li style=\"margin: 0px;\"><strong>".htmlspecialchars($replaceinfo['find'])."</strong> : ".htmlspecialchars($replaceinfo['replacement'])." || <a href=\"style.php?do=edit_replacement&id=".$replaceinfo['replaceid']."\">Edit</a> - <a href=\"style.php?do=delete_replacement&id=".$replaceinfo['replaceid']."\">Delete</a></li>");
					}
					print("</ul>\n");
				}
			print("\t\t</td>\n");

		print("\t</tr>\n\n");
	}

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"2\">&nbsp;</td></tr>\n");
	construct_table_END();

	// do footer
	admin_footer();
}


// ##### DO DELETE STYLE ##### \\
else if($_GET['do'] == "delete_style") {
	// make sure we have a valid id...
	$getStyleId = query("SELECT * FROM styles WHERE styleid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($getStyleId)) {
		construct_error("Sorry, no style with the given ID exists. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// if there is only one style left.. do not delete!
	if($_GET['id'] == 1) {
		construct_error("You are trying to delete the first default style, this action is disallowed, as this style may be needed for message board operation.");
		exit;
	}

	// array
	$styleinfo = mysql_fetch_array($getStyleId);

	// construct confirm!!!
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// delete all templates and replacements and colors belonging to this style...
			query("DELETE FROM replacement_variables WHERE styleid = '".$styleinfo['styleid']."'");
			query("DELETE FROM templates WHERE styleid = '".$styleinfo['styleid']."'");
			query("DELETE FROM styles_colors WHERE styleid = '".$styleinfo['styleid']."'");

			// update forums to default
			query("UPDATE forums SET default_style = '0' WHERE default_style = '".$styleinfo['styleid']."'");

			// update user info
			query("UPDATE user_info SET style_id = '".$bboptions['general_style']."' WHERE style_id = '".$styleinfo['styleid']."'");

			// delete style...
			query("DELETE FROM styles WHERE styleid = '".$styleinfo['styleid']."' LIMIT 1");

			// redirect to thankyou page...
			redirect("thankyou.php?message=Template deleted successfully. You will now be redirected to the Style Manager.&uri=style.php?do=manager");
		}

		// no...
		else {
			// redirect to thankyou page...
			redirect("style.php?do=manager");
		}
	}
	
	// do a confirm page...
	construct_confirm("Are you sure you want to permanently delete the style <em>".$styleinfo['title']."</em>? All custom templates belonging to this style will be deleteted!");
}


// ##### DO VIEW DEFAULT ##### \\
else if($_GET['do'] == "view_default") {
	// make sure we have a valid id...
	$getDefaultTemplate = query("SELECT * FROM templates_default WHERE defaultid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($getDefaultTemplate)) {
		construct_error("Sorry, no default template with the given ID exists. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// we're good to go! array...
	$templateinfo = mysql_fetch_array($getDefaultTemplate);

	// do header
	admin_header("wtcBB Admin Panel - Styles - Default Template");

	construct_title("Default Template Template");

	construct_table("options","edit_template","template_submit",1);

	construct_header("Default Template <em>".$templateinfo['title']."</em> <span class=\"small\">(id: ".$templateinfo['defaultid'].")</span>",2);

	construct_text(1,"Title","","edit_template","title",$templateinfo['title']);

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc2\" colspan=\"2\">\n");
			print("\t\t\t\t<strong>Template:</strong> <br /><br />\n\n");
			
			print("\t\t\t\t<div style=\"text-align: center;\">\n");
				print("\t\t\t\t\t<textarea cols=\"60\" rows=\"24\" style=\"font-family: courier new; font-size: 10pt;\">".htmlspecialchars($templateinfo['template'])."</textarea>\n");
			print("\t\t\t\t</div>\n");

		print("\t\t\t</td>\n");
	print("\t\t</tr>\n");

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"2\">&nbsp;</td></tr>\n");
	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO FIND UPDATED TEMPLATES ##### \\
else if($_GET['do'] == "find_updated") {
	// look for reg. templates with version number other than current!
	$findUpdatedTemplates = query("SELECT * FROM templates WHERE version != '".$bboptions['version_num']."' AND is_global != 1 AND is_custom != 1 AND defaultid > 0 ORDER BY templategroupid, title");

	// all set!
	if(!mysql_num_rows($findUpdatedTemplates)) {
		construct_error("There are no templates that need updating.");
		exit;
	}

	// construct style array...
	$styles_q = query('SELECT styleid,title FROM styles');

	while($style = mysql_fetch_array($styles_q)) {
		$styles[$style['styleid']] = $style['title'];
	}

	// do header
	admin_header("wtcBB Admin Panel - Style System - Find Updated Templates");

	construct_title("Find Updated Templates");

	print("\n\n<br />\n\n<div align=\"center\"><div style=\"text-align: left; width: 90%;\">\n");
		print("All templates listed below have had it's default template updated because of an upgrade or downgrade you made to your message board. You can chose to revert, edit, or view the newly updated default template for each template.");
	print("\n\n</div>\n</div>\n<br />\n\n");

	construct_table("options","template_form","template_submit",1);
	construct_header("Search Results",3);

	print("\n\n\t<tr>\n");

		print("\t\t<td class=\"cat\">\n");
		print("\t\t\tName\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tInformation\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tOptions\n");
		print("\t\t</td>\n");

	print("\t</tr>\n\n");

	// loop
	while($customTemplateInfo = mysql_fetch_array($findUpdatedTemplates)) {
		print("\t<tr>\n");

			print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
				print("\t\t\t-- -- <a href=\"style.php?do=edit_templates&templateid=".$customTemplateInfo['templateid']."&styleid=".$customTemplateInfo['styleid']."&groupid=".$customTemplateInfo['templategroupid']."\" style=\"color: #bb0000;\">".$customTemplateInfo['title']."</a> <br />Style: ".$styles[$customTemplateInfo['styleid']]."\n");
			print("\t\t</td>\n");

			// form date...
			$formattedDate = date("F j, Y \a\\t g:i A",$customTemplateInfo['last_edit']);
			$userinfo = query("SELECT * FROM user_info WHERE username = '".$customTemplateInfo['username']."' LIMIT 1",1);

			print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<strong>Type:</strong> Customized Template<br />\n");
				print("\t\t\t<strong>Version:</strong> ".$customTemplateInfo['version']."<br />\n");
				print("\t\t\t<strong>Last Edited:</strong> ".$formattedDate."<br />\n");
				print("\t\t\t<strong>Last Edited By:</strong> <a href=\"user.php?do=edit&id=".$userinfo['userid']."\">".$userinfo['username']."</a>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<select style=\"margin-bottom: 3px;\" name=\"control".$customTemplateInfo['title']."\" onChange=\"location.href=(document.template_form.control".$customTemplateInfo['title'].".options[document.template_form.control".$customTemplateInfo['title'].".selectedIndex].value)\">\n");
					print("\t\t\t\t<option value=\"style.php?do=edit_templates&templateid=".$customTemplateInfo['templateid']."&styleid=".$customTemplateInfo['styleid']."&groupid=".$customTemplateInfo['templategroupid']."\" selected=\"selected\">Edit Template</option>\n");
					print("\t\t\t\t<option value=\"style.php?do=view_default&id=".$customTemplateInfo['defaultid']."\">View Default</option>\n");
					print("\t\t\t\t<option value=\"style.php?do=revert&templateid=".$customTemplateInfo['templateid']."&styleid=".$customTemplateInfo['styleid']."&groupid=".$customTemplateInfo['templategroupid']."\">Revert</option>\n");
				print("\t\t\t</select>\n");
				print("\t\t\t<button type=\"button\" onClick=\"location.href=(document.template_form.control".$customTemplateInfo['title'].".options[document.template_form.control".$customTemplateInfo['title'].".selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px; margin-top: 0px;\" ".$submitbg.">Go</button>\n");
			print("\t\t</td>\n");

		print("\t</tr>\n\n");
	}

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"3\">&nbsp;</td></tr>\n");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO SEARCH TEMPLATES ##### \\
else if($_GET['do'] == "search_templates") {
	// display search results...
	if($_POST['search_template']['set_form']) {
		// get styleinfo..
		$styleinfo = query("SELECT * FROM styles WHERE styleid = '".$_POST['search_template']['styleid']."' LIMIT 1",1);

		// get template groups...
		$getTemplateGroups = query("SELECT * FROM templategroups ORDER BY title");

		// do header
		admin_header("wtcBB Admin Panel - Style System - Search In Templates");

		construct_title("Search In Templates");

		construct_table("options","template_form","template_submit",1);
		construct_header("Search Results",3);

		print("\n\n\t<tr>\n");

			print("\t\t<td class=\"cat\">\n");
			print("\t\t\tName\n");
			print("\t\t</td>\n\n");

			print("\t\t<td class=\"cat2\">\n");
			print("\t\t\tInformation\n");
			print("\t\t</td>\n\n");

			print("\t\t<td class=\"cat2\">\n");
			print("\t\t\tOptions\n");
			print("\t\t</td>\n");

		print("\t</tr>\n\n");

		// get global templates...
		$getGlobal = query("SELECT * FROM templates WHERE is_global = '1' AND template LIKE '%".$_POST['search_template']['search_query']."%' ORDER BY title");

		// if there are rows.. display them...
		if(mysql_num_rows($getGlobal)) {

			// expand or contract?
			if($_GET['action'] AND $_GET['action'] == "global") {
				$expandContract = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."';\" ".$submitbg.">Contract</button>";
				$expandContract2 = "";
			} else {
				$expandContract = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."&action=global';\" ".$submitbg.">Expand</button>";
				$expandContract2 = "&action=global";
			}

			print("\t<tr>\n");

				print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
					print("\t\t\t<a href=\"style.php?do=templates&id=".$styleinfo['styleid'].$expandContract2."\" style=\"color: #BB0000;\">Global Templates</a>\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t<strong>Type:</strong> Global Template Group\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t\t".$expandContract."\n");
				print("\t\t</td>\n");

			print("\t</tr>\n\n");

			// loop through globals...
			while($globalinfo = mysql_fetch_array($getGlobal)) {
				print("\t<tr>\n");

					print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
						print("\t\t\t-- -- <a href=\"style.php?do=edit_templates&styleid=".$styleinfo['styleid']."&templateid=".$globalinfo['templateid']."\" style=\"color: #bb0000;\" class=\"small\">".$globalinfo['title']."</a>\n");
					print("\t\t</td>\n");

					// form date...
					$formattedDate = date("F j, Y \a\\t g:i A",$globalinfo['last_edit']);
					print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<strong>Type:</strong> Global Template<br />\n");
						print("\t\t\t<strong>Version:</strong> ".$globalinfo['version']."<br />\n");
						print("\t\t\t<strong>Last Edited:</strong> ".$formattedDate."<br />\n");
						print("\t\t\t<strong>Last Edited By: </strong> ".$globalinfo['username']."</a><br />\n");
					print("\t\t</td>\n");

					print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<select style=\"margin-bottom: 3px;\" name=\"control".$globalinfo['title']."\" onChange=\"location.href=(document.template_form.control".$globalinfo['title'].".options[document.template_form.control".$globalinfo['title'].".selectedIndex].value)\">\n");
							print("\t\t\t\t<option value=\"style.php?do=edit_templates&templateid=".$globalinfo['templateid']."\" selected=\"selected\">Edit Template</option>\n");
							print("\t\t\t\t<option value=\"style.php?do=revert&templateid=".$globalinfo['templateid']."\">Delete</option>\n");
						print("\t\t\t</select>\n");
						print("\t\t\t<button type=\"button\" onClick=\"location.href=(document.template_form.control".$globalinfo['title'].".options[document.template_form.control".$globalinfo['title'].".selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px; margin-top: 0px;\" ".$submitbg.">Go</button>\n");
					print("\t\t</td>\n");

				print("\t</tr>\n\n");
			}
		}

		// get custom templates...
		$getCustom = query("SELECT * FROM templates WHERE is_custom = '1' AND styleid = '".$styleinfo['styleid']."' AND template LIKE '%".$_POST['search_template']['search_query']."%' ORDER BY title");

		// if there are rows.. display them...
		if(mysql_num_rows($getCustom)) {
			// expand or contract?
			if($_GET['action'] AND $_GET['action'] == "custom") {
				$expandContract = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."';\" ".$submitbg.">Contract</button>";
				$expandContract2 = "";
			} else {
				$expandContract = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."&action=custom';\" ".$submitbg.">Expand</button>";
				$expandContract2 = "&action=custom";
			}

			print("\t<tr>\n");

				print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
					print("\t\t\t<a href=\"style.php?do=templates&id=".$styleinfo['styleid'].$expandContract2."\" style=\"color: #BB0000;\">Custom Templates</a>\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t<strong>Type:</strong> Custom Template Group\n");
				print("\t\t</td>\n");

				print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t\t".$expandContract."\n");
				print("\t\t</td>\n");

			print("\t</tr>\n\n");

			// loop through customs...
			while($custominfo = mysql_fetch_array($getCustom)) {
				print("\t<tr>\n");

					print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
						print("\t\t\t-- -- <a href=\"style.php?do=edit_templates&templateid=".$custominfo['templateid']."&styleid=".$styleinfo['styleid']."\" style=\"color: #bb0000;\" class=\"small\">".$custominfo['title']."</a>\n");
					print("\t\t</td>\n");

					// form date...
					$formattedDate = date("F j, Y \a\\t g:i A",$custominfo['last_edit']);
					print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<strong>Type:</strong> Custom Template<br />\n");
						print("\t\t\t<strong>Version:</strong> ".$custominfo['version']."<br />\n");
						print("\t\t\t<strong>Last Edited:</strong> ".$formattedDate."<br />\n");
						print("\t\t\t<strong>Last Edited By: </strong> ".$custominfo['username']."<br />\n");
					print("\t\t</td>\n");

					print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<select style=\"margin-bottom: 3px;\" name=\"control".$custominfo['title']."\" onChange=\"location.href=(document.template_form.control".$custominfo['title'].".options[document.template_form.control".$custominfo['title'].".selectedIndex].value)\">\n");
							print("\t\t\t\t<option value=\"style.php?do=edit_templates&templateid=".$custominfo['templateid']."&styleid=".$styleinfo['styleid']."\" selected=\"selected\">Edit Template</option>\n");
							print("\t\t\t\t<option value=\"style.php?do=revert&templateid=".$custominfo['templateid']."&styleid=".$styleinfo['styleid']."\">Delete</option>\n");
						print("\t\t\t</select>\n");
						print("\t\t\t<button type=\"button\" onClick=\"location.href=(document.template_form.control".$custominfo['title'].".options[document.template_form.control".$custominfo['title'].".selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px; margin-top: 0px;\" ".$submitbg.">Go</button>\n");
					print("\t\t</td>\n");

				print("\t</tr>\n\n");
			}
		}

		while($templategroupinfo = mysql_fetch_array($getTemplateGroups)) {

			// expand or contract?
			if(isset($_GET['groupid']) AND $templategroupinfo['templategroupid'] == $_GET['groupid']) {
				$expandContract = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."';\" ".$submitbg.">Contract</button>";
				$expandContract2 = "";
			} else {
				$expandContract = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."';\" ".$submitbg.">Expand</button>";
				$expandContract2 = "&groupid=".$templategroupinfo['templategroupid'];
			}

			// DEFAULT templates for this template group...
			$getDefaultTemplates = query("SELECT * FROM templates_default WHERE templategroupid = '".$templategroupinfo['templategroupid']."' AND template LIKE '%".$_POST['search_template']['search_query']."%' ORDER BY title");

			$getDefaultTemplates2 = query("SELECT * FROM templates_default WHERE templategroupid = '".$templategroupinfo['templategroupid']."' ORDER BY title");

			// get the reg templates.. JUST to count... and maybe to use ^_^
			$getRegularTemplates = query("SELECT * FROM templates WHERE styleid = '".$styleinfo['styleid']."' AND templategroupid = '".$templategroupinfo['templategroupid']."' AND template LIKE '%".$_POST['search_template']['search_query']."%' ORDER BY title");

			// only if we have at least ONE result from either of the two queries above...
			if(mysql_num_rows($getDefaultTemplates) OR mysql_num_rows($getRegularTemplates)) {
				print("\t<tr>\n");

					print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<a href=\"style.php?do=templates&id=".$styleinfo['styleid']."&groupid=".$expandContract2."\" style=\"color: #000000;\">".$templategroupinfo['title']."</a>\n");
					print("\t\t</td>\n");

					print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<strong>Type:</strong> Template Group\n");
					print("\t\t</td>\n");

					print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
							print("\t\t\t\t".$expandContract."\n");
					print("\t\t</td>\n");

				print("\t</tr>\n\n");

				// if we ONLY get customized templates.. we have to do it this way because.. well.. ummm.. i couldn't think of any other logical way!
				if(!mysql_num_rows($getDefaultTemplates)) {					
					// loop
					while($customTemplateInfo = mysql_fetch_array($getRegularTemplates)) {
						print("\t<tr>\n");

							print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
								print("\t\t\t-- -- <a href=\"style.php?do=edit_templates&templateid=".$customTemplateInfo['templateid']."&styleid=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."\" style=\"color: #bb0000;\" class=\"small\">".$customTemplateInfo['title']."</a>\n");
							print("\t\t</td>\n");

							// form date...
							$formattedDate = date("F j, Y \a\\t g:i A",$customTemplateInfo['last_edit']);

							print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
								print("\t\t\t<strong>Type:</strong> Customized Template<br />\n");
								print("\t\t\t<strong>Version:</strong> ".$customTemplateInfo['version']."<br />\n");
								print("\t\t\t<strong>Last Edited:</strong> ".$formattedDate."<br />\n");
								print("\t\t\t<strong>Last Edited By:</strong> ".$customTemplateInfo['username']."\n");
							print("\t\t</td>\n");

							print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
								print("\t\t\t<select style=\"margin-bottom: 3px;\" name=\"control".$customTemplateInfo['title']."\" onChange=\"location.href=(document.template_form.control".$customTemplateInfo['title'].".options[document.template_form.control".$customTemplateInfo['title'].".selectedIndex].value)\">\n");
									print("\t\t\t\t<option value=\"style.php?do=edit_templates&templateid=".$customTemplateInfo['templateid']."&styleid=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."\" selected=\"selected\">Edit Template</option>\n");
									print("\t\t\t\t<option value=\"style.php?do=view_default&id=".$customTemplateInfo['defaultid']."\">View Default</option>\n");
									print("\t\t\t\t<option value=\"style.php?do=revert&templateid=".$customTemplateInfo['templateid']."&styleid=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."\">Revert</option>\n");
								print("\t\t\t</select>\n");
								print("\t\t\t<button type=\"button\" onClick=\"location.href=(document.template_form.control".$customTemplateInfo['title'].".options[document.template_form.control".$customTemplateInfo['title'].".selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px; margin-top: 0px;\" ".$submitbg.">Go</button>\n");
							print("\t\t</td>\n");

						print("\t</tr>\n\n");
					}
				}

				// customized and/or default
				else {
					while($templateinfo = mysql_fetch_array($getDefaultTemplates2)) {
						// default or customized?
						$getCustomizedTemplate = query("SELECT * FROM templates WHERE defaultid = '".$templateinfo['defaultid']."' AND styleid = '".$styleinfo['styleid']."' AND templategroupid = '".$templategroupinfo['templategroupid']."' AND template LIKE '%".$_POST['search_template']['search_query']."%' LIMIT 1");
						
						if(!mysql_num_rows($getCustomizedTemplate) AND eregi($_POST['search_template']['search_query'],$templateinfo['template'])) {
							print("\t<tr>\n");

								print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
									print("\t\t\t-- -- <a href=\"style.php?do=edit_templates&styleid=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."&templateid=".$templateinfo['defaultid']."&d=yes\" class=\"small\">".$templateinfo['title']."</a>\n");
								print("\t\t</td>\n");

								print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
									print("\t\t\t<strong>Type:</strong> Default Template<br />\n");
									print("\t\t\t<strong>Version:</strong> ".$templateinfo['version']."\n");
								print("\t\t</td>\n");

								print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
									print("\t\t\t<select style=\"margin-bottom: 3px;\" name=\"control".$templateinfo['title']."\" onChange=\"location.href=(document.template_form.control".$templateinfo['title'].".options[document.template_form.control".$templateinfo['title'].".selectedIndex].value)\">\n");
										print("\t\t\t\t<option value=\"style.php?do=edit_templates&styleid=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."&templateid=".$templateinfo['defaultid']."&d=yes\" selected=\"selected\">Edit Template</option>\n");
									print("\t\t\t</select>\n");
									print("\t\t\t<button type=\"button\" onClick=\"location.href=(document.template_form.control".$templateinfo['title'].".options[document.template_form.control".$templateinfo['title'].".selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px; margin-top: 0px;\" ".$submitbg.">Go</button>\n");
								print("\t\t</td>\n");

							print("\t</tr>\n\n");
						}

						else if(mysql_num_rows($getCustomizedTemplate)) {														
							// array
							$customTemplateInfo = mysql_fetch_array($getCustomizedTemplate);

							print("\t<tr>\n");

								print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
									print("\t\t\t-- -- <a href=\"style.php?do=edit_templates&templateid=".$customTemplateInfo['templateid']."&styleid=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."\" style=\"color: #bb0000;\" class=\"small\">".$customTemplateInfo['title']."</a>\n");
								print("\t\t</td>\n");

								// form date...
								$formattedDate = date("F j, Y \a\\t g:i A",$customTemplateInfo['last_edit']);

								print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
									print("\t\t\t<strong>Type:</strong> Customized Template<br />\n");
									print("\t\t\t<strong>Version:</strong> ".$customTemplateInfo['version']."<br />\n");
									print("\t\t\t<strong>Last Edited:</strong> ".$formattedDate."<br />\n");
									print("\t\t\t<strong>Last Edited By:</strong> ".$customTemplateInfo['username']."\n");
								print("\t\t</td>\n");

								print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
									print("\t\t\t<select style=\"margin-bottom: 3px;\" name=\"control".$customTemplateInfo['title']."\" onChange=\"location.href=(document.template_form.control".$customTemplateInfo['title'].".options[document.template_form.control".$customTemplateInfo['title'].".selectedIndex].value)\">\n");
										print("\t\t\t\t<option value=\"style.php?do=edit_templates&templateid=".$customTemplateInfo['templateid']."&styleid=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."\" selected=\"selected\">Edit Template</option>\n");
										print("\t\t\t\t<option value=\"style.php?do=view_default&id=".$customTemplateInfo['defaultid']."\">View Default</option>\n");
										print("\t\t\t\t<option value=\"style.php?do=revert&templateid=".$customTemplateInfo['templateid']."&styleid=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."\">Revert</option>\n");
									print("\t\t\t</select>\n");
									print("\t\t\t<button type=\"button\" onClick=\"location.href=(document.template_form.control".$customTemplateInfo['title'].".options[document.template_form.control".$customTemplateInfo['title'].".selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px; margin-top: 0px;\" ".$submitbg.">Go</button>\n");
								print("\t\t</td>\n");

							print("\t</tr>\n\n");
						}
					}
				}
			}
		}

		// get expandContractALL
		if($_GET['action'] AND $_GET['action'] == "expand_all") {
			$expandContractALL = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."';\" ".$submitbg.">Contract All</button>";
		} else {
			$expandContractALL = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."&action=expand_all';\" ".$submitbg.">Expand All</button>";
		}

		print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"3\"><button type=\"button\" onclick=\"location.href='style.php?do=add_template&styleid=".$styleinfo['styleid']."';\" ".$submitbg.">Add Template</button> &nbsp;&nbsp;&nbsp; ".$expandContractALL."</td></tr>\n");
		construct_table_END(1);

		// do footer
		admin_footer();

		exit;
	}

	// do header
	admin_header("wtcBB Admin Panel - Styles - Search in Templates");

	construct_title("Search in Templates");

	construct_table("options","search_template","template_submit",1);

	construct_header("Search In Templates",2);

	construct_textarea(1,"Search Query","Enter the string you wish to search for.","search_template","search_query");


	construct_select_begin(2,"Style","Select the style that you want to search in.","search_template","styleid");
			// get styles...
			$checkStyles = query("SELECT * FROM styles ORDER BY display_order");

			// loop through styles
			while($styleinfo = mysql_fetch_array($checkStyles)) {
				print("<option value=\"".$styleinfo['styleid']."\">".$styleinfo['title']."</option>\n");
			}

	construct_select_end(2);

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"2\"><button type=\"submit\" ".$submitbg.">Search</button>\n");
	// so we expand all...
	print("\t\t<input type=\"hidden\" name=\"action\" value=\"expand_all\" />\n");
	print("\n</td></tr>\n");

	construct_table_END(1);

	// do footer
	admin_footer();
}

// ##### DO REVERT TEMPLATES ##### \\
else if($_GET['do'] == "revert") {
	// get template
	$getTemplate = query("SELECT * FROM templates WHERE templateid = '".$_GET['templateid']."' AND templategroupid = '".$_GET['groupid']."' AND styleid = '".$_GET['styleid']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($getTemplate)) {
		construct_error("Sorry, no template exists with the given ID's. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// array
	$templateinfo = mysql_fetch_array($getTemplate);

	// construct confirm!!!
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// delete template...
			query("DELETE FROM templates WHERE templateid = '".$_GET['templateid']."' LIMIT 1");

			if($templateinfo['is_global'] == 0) {
				// redirect to thankyou page...
				redirect("thankyou.php?message=Template reverted successfully. You will now be redirected to the Style Manager.&uri=style.php?do=templatesSTEVEid=".$_GET['styleid']."STEVEgroupid=".$_GET['groupid']);
			} else {
				// redirect to thankyou page...
				redirect("thankyou.php?message=Template edited successfully. You will now be redirected to the Style Manager.&uri=style.php?do=manager");
			}
		}

		// no...
		else {
			if(!$templateinfo['is_global']) {
				// redirect to thankyou page...
				redirect("style.php?do=templatesSTEVEid=".$_GET['styleid']."STEVEgroupid=".$_GET['groupid']);
			} else {
				// redirect to thankyou page...
				redirect("style.php?do=manager");
			}
		}
	}
	
	// do a confirm page...
	construct_confirm("Are you sure you want to permanently revert the template <em>".$templateinfo['title']."</em>? This cannot be undone!");
}

// ##### DO TEMPLATES ##### \\
else if($_GET['do'] == "templates") {
	// make sure we have a valid styleid...
	$getStyle = query("SELECT * FROM styles WHERE styleid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($getStyle)) {
		construct_error("Sorry, no style was found with the given ID. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// styleinfo array
	$styleinfo = mysql_fetch_array($getStyle);

	// get template groups...
	$getTemplateGroups = query("SELECT * FROM templategroups ORDER BY title");

	// do header
	admin_header("wtcBB Admin Panel - Style System - Templates");

	construct_title("Templates");

	?>

	<table border="0" cellspacing="0" cellpadding="4" class="options">
		<tr>
			<td class="header">Navigation</td>
		</tr>

		<tr>
			<td class="desc1_bottom" style="text-align: center; padding: 7px;">
				<form style="margin: 0px; padding: 0px;" method="post" action="" name="myForm">
					<select name="navi" onChange="location.href=(myForm.navi.options[myForm.navi.selectedIndex].value)">
						<option value="#" selected="selected">---NAVIGATION---</option>
						<option value="style.php?do=templates&id=<?php print($styleinfo['styleid']); ?>">Edit Style Templates</option>
						<option value="style.php?do=colors&id=<?php print($styleinfo['styleid']); ?>">Edit Style Colors</option>
						<option value="style.php?do=edit_style&id=<?php print($styleinfo['styleid']); ?>">Edit Style Options</option>
						<option value="style.php?do=add_template&styleid=<?php print($styleinfo['styleid']); ?>">Add Template</option>
						<option value="style.php?do=add_replacement&styleid=<?php print($styleinfo['styleid']); ?>">Add Replacement</option>
					</select>

					<button type="button" onClick="location.href=(myForm.navi.options[myForm.navi.selectedIndex].value)" style="margin: 2px; margin-bottom: 0px;" <?php print($submitbg); ?>>Go</button>
				</form>
			</td>
		</tr>

		<tr>
			<td class="footer" colspan="0">&nbsp;</td>
		</tr>

	</table>

	<br />

	<?php

	construct_table("options","template_form","template_submit",1);
	construct_header("Templates for style <em>".$styleinfo['title']."</em>",3);

	print("\n\n\t<tr>\n");

		print("\t\t<td class=\"cat\">\n");
		print("\t\t\tName\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tInformation\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tOptions\n");
		print("\t\t</td>\n");

	print("\t</tr>\n\n");

	// get global templates...
	$getGlobal = query("SELECT * FROM templates WHERE is_global = '1' ORDER BY title");

	// if there are rows.. display them...
	if(mysql_num_rows($getGlobal)) {
		// expand or contract?
		if($_GET['action'] AND $_GET['action'] == "global") {
			$expandContract = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."';\" ".$submitbg.">Contract</button>";
			$expandContract2 = "";
		} else {
			$expandContract = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."&action=global';\" ".$submitbg.">Expand</button>";
			$expandContract2 = "&action=global";
		}

		print("\t<tr>\n");

			print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<a href=\"style.php?do=templates&id=".$styleinfo['styleid'].$expandContract2."\" style=\"color: #BB0000;\">Global Templates</a>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<strong>Type:</strong> Global Template Group\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: left; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t\t".$expandContract."\n");
			print("\t\t</td>\n");

		print("\t</tr>\n\n");

		// expand...
		if($_GET['action'] AND ($_GET['action'] == "global" OR $_GET['action'] == "expand_all")) {
			// loop through globals...
			while($globalinfo = mysql_fetch_array($getGlobal)) {
				print("\t<tr>\n");

					print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
						print("\t\t\t-- -- <a href=\"style.php?do=edit_templates&styleid=".$styleinfo['styleid']."&templateid=".$globalinfo['templateid']."\" style=\"color: #bb0000;\" class=\"small\">".$globalinfo['title']."</a>\n");
					print("\t\t</td>\n");

					// form date...
					$formattedDate = date("F j, Y \a\\t g:i A",$globalinfo['last_edit']);
					print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<strong>Type:</strong> Global Template<br />\n");
						print("\t\t\t<strong>Version:</strong> ".$globalinfo['version']."<br />\n");
						print("\t\t\t<strong>Last Edited:</strong> ".$formattedDate."<br />\n");
						print("\t\t\t<strong>Last Edited By: </strong> ".$globalinfo['username']."<br />\n");
					print("\t\t</td>\n");

					print("\t\t<td class=\"desc2\" style=\"text-align: left; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<select style=\"margin-bottom: 3px;\" name=\"control".$globalinfo['title']."\" onChange=\"location.href=(document.template_form.control".$globalinfo['title'].".options[document.template_form.control".$globalinfo['title'].".selectedIndex].value)\">\n");
							print("\t\t\t\t<option value=\"style.php?do=edit_templates&templateid=".$globalinfo['templateid']."\" selected=\"selected\">Edit Template</option>\n");
							print("\t\t\t\t<option value=\"style.php?do=revert&templateid=".$globalinfo['templateid']."\">Delete</option>\n");
						print("\t\t\t</select>\n");
						print("\t\t\t<button type=\"button\" onClick=\"location.href=(document.template_form.control".$globalinfo['title'].".options[document.template_form.control".$globalinfo['title'].".selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px; margin-top: 0px;\" ".$submitbg.">Go</button>\n");
					print("\t\t</td>\n");

				print("\t</tr>\n\n");
			}
		}
	}

	// get custom templates...
	$getCustom = query("SELECT * FROM templates WHERE is_custom = '1' AND styleid = '".$styleinfo['styleid']."' ORDER BY title");

	// if there are rows.. display them...
	if(mysql_num_rows($getCustom)) {
		// expand or contract?
		if($_GET['action'] AND $_GET['action'] == "custom") {
			$expandContract = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."';\" ".$submitbg.">Contract</button>";
			$expandContract2 = "";
		} else {
			$expandContract = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."&action=custom';\" ".$submitbg.">Expand</button>";
			$expandContract2 = "&action=custom";
		}

		print("\t<tr>\n");

			print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<a href=\"style.php?do=templates&id=".$styleinfo['styleid'].$expandContract2."\" style=\"color: #BB0000;\">Custom Templates</a>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<strong>Type:</strong> Custom Template Group\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: left; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t\t".$expandContract."\n");
			print("\t\t</td>\n");

		print("\t</tr>\n\n");

		// expand...
		if($_GET['action'] AND ($_GET['action'] == "custom" OR $_GET['action'] == "expand_all")) {
			// loop through customs...
			while($custominfo = mysql_fetch_array($getCustom)) {
				print("\t<tr>\n");

					print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
						print("\t\t\t-- -- <a href=\"style.php?do=edit_templates&templateid=".$custominfo['templateid']."&styleid=".$styleinfo['styleid']."\" style=\"color: #bb0000;\" class=\"small\">".$custominfo['title']."</a>\n");
					print("\t\t</td>\n");

					// form date...
					$formattedDate = date("F j, Y \a\\t g:i A",$custominfo['last_edit']);
					print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<strong>Type:</strong> Custom Template<br />\n");
						print("\t\t\t<strong>Version:</strong> ".$custominfo['version']."<br />\n");
						print("\t\t\t<strong>Last Edited:</strong> ".$formattedDate."<br />\n");
						print("\t\t\t<strong>Last Edited By: </strong> ".$custominfo['username']."<br />\n");
					print("\t\t</td>\n");

					print("\t\t<td class=\"desc2\" style=\"text-align: left; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<select style=\"margin-bottom: 3px;\" name=\"control".$custominfo['title']."\" onChange=\"location.href=(document.template_form.control".$custominfo['title'].".options[document.template_form.control".$custominfo['title'].".selectedIndex].value)\">\n");
							print("\t\t\t\t<option value=\"style.php?do=edit_templates&templateid=".$custominfo['templateid']."&styleid=".$styleinfo['styleid']."\" selected=\"selected\">Edit Template</option>\n");
							print("\t\t\t\t<option value=\"style.php?do=revert&templateid=".$custominfo['templateid']."&styleid=".$styleinfo['styleid']."\">Delete</option>\n");
						print("\t\t\t</select>\n");
						print("\t\t\t<button type=\"button\" onClick=\"location.href=(document.template_form.control".$custominfo['title'].".options[document.template_form.control".$custominfo['title'].".selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px; margin-top: 0px;\" ".$submitbg.">Go</button>\n");
					print("\t\t</td>\n");

				print("\t</tr>\n\n");
			}
		}
	}

	$templateinfo2 = buildTemplateArr2($styleinfo['styleid']);

	while($templategroupinfo = mysql_fetch_array($getTemplateGroups)) {
		// expand or contract?
		if(isset($_GET['groupid']) AND $templategroupinfo['templategroupid'] == $_GET['groupid']) {
			$expandContract = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."';\" ".$submitbg.">Contract</button>";
			$expandContract2 = "";
		} else {
			$expandContract = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."';\" ".$submitbg.">Expand</button>";
			$expandContract2 = "&groupid=".$templategroupinfo['templategroupid'];
		}

		print("\t<tr>\n");

			print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<a href=\"style.php?do=templates&id=".$styleinfo['styleid']."&groupid=".$expandContract2."\" style=\"color: #000000;\">".$templategroupinfo['title']."</a>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<strong>Type:</strong> Template Group\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: left; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t\t".$expandContract."\n");
			print("\t\t</td>\n");

		print("\t</tr>\n\n");

		// do templates
		if(($_GET['groupid'] AND $templategroupinfo['templategroupid'] == $_GET['groupid']) OR ($_GET['action'] AND $_GET['action'] == "expand_all")) {
			// if we have rows...
			if(is_array($templateinfo2[$templategroupinfo['templategroupid']])) {
				foreach($templateinfo2[$templategroupinfo['templategroupid']] as $title => $templateinfo) {
					// customized!
					if($templateinfo['is_custom'] == 1) {
						print("\t<tr>\n");

							print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
								print("\t\t\t-- -- <a href=\"style.php?do=edit_templates&templateid=".$templateinfo['templateid']."&styleid=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."\" style=\"color: #bb0000;\" class=\"small\">".$templateinfo['title']."</a>\n");
							print("\t\t</td>\n");

							// form date...
							$formattedDate = date("F j, Y \a\\t g:i A",$templateinfo['last_edit']);

							print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
								print("\t\t\t<strong>Type:</strong> Customized Template<br />\n");
								print("\t\t\t<strong>Version:</strong> ".$templateinfo['version']."<br />\n");
								print("\t\t\t<strong>Last Edited:</strong> ".$formattedDate."<br />\n");
								print("\t\t\t<strong>Last Edited By:</strong> ".$templateinfo['username']."\n");
							print("\t\t</td>\n");

							print("\t\t<td class=\"desc2\" style=\"text-align: left; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
								print("\t\t\t<select style=\"margin-bottom: 3px;\" name=\"control".$templateinfo['title']."\" onChange=\"location.href=(document.template_form.control".$templateinfo['title'].".options[document.template_form.control".$templateinfo['title'].".selectedIndex].value)\">\n");
									print("\t\t\t\t<option value=\"style.php?do=edit_templates&templateid=".$templateinfo['templateid']."&styleid=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."\" selected=\"selected\">Edit Template</option>\n");
									print("\t\t\t\t<option value=\"style.php?do=view_default&id=".$templateinfo['defaultid']."\">View Default</option>\n");
									print("\t\t\t\t<option value=\"style.php?do=revert&templateid=".$templateinfo['templateid']."&styleid=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."\">Revert</option>\n");
								print("\t\t\t</select>\n");
								print("\t\t\t<button type=\"button\" onClick=\"location.href=(document.template_form.control".$templateinfo['title'].".options[document.template_form.control".$templateinfo['title'].".selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px; margin-top: 0px;\" ".$submitbg.">Go</button>\n");
							print("\t\t</td>\n");

						print("\t</tr>\n\n");
					}

					// default template...
					else {
						print("\t<tr>\n");

							print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
								print("\t\t\t-- -- <a href=\"style.php?do=edit_templates&styleid=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."&templateid=".$templateinfo['defaultid']."&d=yes\" class=\"small\">".$templateinfo['title']."</a>\n");
							print("\t\t</td>\n");

							print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; border-left: none; width: 15%; padding: 5px;\">\n");
								print("\t\t\t<strong>Type:</strong> Default Template<br />\n");
								print("\t\t\t<strong>Version:</strong> ".$templateinfo['version']."\n");
							print("\t\t</td>\n");

							print("\t\t<td class=\"desc2\" style=\"text-align: left; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
								print("\t\t\t<select style=\"margin-bottom: 3px;\" name=\"control".$templateinfo['title']."\" onChange=\"location.href=(document.template_form.control".$templateinfo['title'].".options[document.template_form.control".$templateinfo['title'].".selectedIndex].value)\">\n");
									print("\t\t\t\t<option value=\"style.php?do=edit_templates&styleid=".$styleinfo['styleid']."&groupid=".$templategroupinfo['templategroupid']."&templateid=".$templateinfo['defaultid']."&d=yes\" selected=\"selected\">Edit Template</option>\n");
								print("\t\t\t</select>\n");
								print("\t\t\t<button type=\"button\" onClick=\"location.href=(document.template_form.control".$templateinfo['title'].".options[document.template_form.control".$templateinfo['title'].".selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px; margin-top: 0px;\" ".$submitbg.">Go</button>\n");
							print("\t\t</td>\n");

						print("\t</tr>\n\n");
					}
				}
			}
		}
	}

	// get expandContractALL
	if($_GET['action'] AND $_GET['action'] == "expand_all") {
		$expandContractALL = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."';\" ".$submitbg.">Contract All</button>";
	} else {
		$expandContractALL = "<button type=\"button\" onclick=\"location.href='style.php?do=templates&id=".$styleinfo['styleid']."&action=expand_all';\" ".$submitbg.">Expand All</button>";
	}

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"3\"><button type=\"button\" onclick=\"location.href='style.php?do=add_template&styleid=".$styleinfo['styleid']."';\" ".$submitbg.">Add Template</button> &nbsp;&nbsp;&nbsp; ".$expandContractALL."</td></tr>\n");
	construct_table_END(1);

	// do footer
	admin_footer();
}

// ##### DO STYLE MANAGER ##### \\
else if($_GET['do'] == "manager") {
	// run query to check and find styles...
	$styleinfo_query = query("SELECT * FROM styles ORDER BY display_order");

	// uh oh...
	if(!mysql_num_rows($styleinfo_query)) {
		construct_error("No styles exist. Please use the <a href=\"style.php?do=add_style\">Add Style</a> page to make a new one.");
		exit;
	}

	// update user selectableness and display order...
	if($_POST['style_order']['set_form']) {
		// loop through the user_selectable array
		foreach($_POST['user_selectable'] as $key => $value) {
			// update
			$update_query = query("UPDATE styles SET user_selection = '".addslashes($value)."' , display_order = '".addslashes($_POST['display_order'][$key])."' , enabled = '".$_POST['enabled'][$key]."' WHERE styleid = '".$key."'");
		}

		// refresh...
		redirect("style.php?do=manager");
	}

	// do header
	admin_header("wtcBB Admin Panel - Style System - Select a Style to Edit");

	construct_title("Select a Style to Edit");

	construct_table("options","style_order","style_submit",1);
	construct_header("Select a Style to Edit",5);

	print("\n\n\t<tr>\n");

		print("\t\t<td class=\"cat\">\n");
		print("\t\t\tStyle\n");
		print("\t\t</td>\n\n");
		
		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tEnabled\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tUser Selectable\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tDisplay Order\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tOptions\n");
		print("\t\t</td>\n");

	print("\t</tr>\n\n");

	while($styleinfo = mysql_fetch_array($styleinfo_query)) {
		print("\t<tr>\n");
			$getChecked1 = ''; $getChecked2 = '';
			$getChecked3 = ''; $getChecked4 = '';

			// get user_selectable
			if($styleinfo['user_selection']) {
				$getChecked1 = " checked=\"checked\"";
			} else { 
				$getChecked2 = " checked=\"checked\"";
			}
			
			// get enabled
			if($styleinfo['enabled']) {
				$getChecked3 = ' checked="checked"';
			}
			
			else {
				$getChecked4 = ' checked="checked"';
			}

			print("\t\t<td class=\"desc1\" style=\"text-align: center; white-space: nowrap; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<a href=\"style.php?do=edit_style&id=".$styleinfo['styleid']."\">".$styleinfo['title']."</a>\n");
			print("\t\t</td>\n");
			
			print("\t\t<td class=\"desc2\" style=\"text-align: center; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<label for=\"enabled".$styleinfo['styleid']."\"><input type=\"radio\" name=\"enabled[".$styleinfo['styleid']."]\" id=\"enabled".$styleinfo['styleid']."\" value=\"1\"".$getChecked3." /> Yes</label>			<label for=\"enabled".$styleinfo['styleid']."2\"><input type=\"radio\" name=\"enabled[".$styleinfo['styleid']."]\" id=\"enabled".$styleinfo['styleid']."2\" value=\"0\"".$getChecked4." /> No</label>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc1\" style=\"text-align: center; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<label for=\"active".$styleinfo['styleid']."\"><input type=\"radio\" name=\"user_selectable[".$styleinfo['styleid']."]\" id=\"active".$styleinfo['styleid']."\" value=\"1\"".$getChecked1." /> Yes</label>			<label for=\"active".$styleinfo['styleid']."2\"><input type=\"radio\" name=\"user_selectable[".$styleinfo['styleid']."]\" id=\"active".$styleinfo['styleid']."2\" value=\"0\"".$getChecked2." /> No</label>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<input type=\"text\" class=\"text\" value=\"".$styleinfo['display_order']."\" style=\"width: 20px;\" name=\"display_order[".$styleinfo['styleid']."]\" />\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
					print("\t\t\t\t<select style=\"margin-bottom: 3px;\" name=\"control".$styleinfo['styleid']."\" onChange=\"location.href=(document.style_order.control".$styleinfo['styleid'].".options[document.style_order.control".$styleinfo['styleid'].".selectedIndex].value)\">\n");
						print("\t\t\t\t\t<option value=\"style.php?do=templates&id=".$styleinfo['styleid']."\" selected=\"selected\">Edit Style Templates</option>\n");
						print("\t\t\t\t\t<option value=\"style.php?do=colors&id=".$styleinfo['styleid']."\">Edit Style Colors</option>\n");
						print("\t\t\t\t\t<option value=\"style.php?do=edit_style&id=".$styleinfo['styleid']."\">Edit Style Options</option>\n");
						print("\t\t\t\t\t<option value=\"style.php?do=delete_style&id=".$styleinfo['styleid']."\">Delete Style</option>\n");
						print("\t\t\t\t\t<option value=\"style.php?do=add_template&styleid=".$styleinfo['styleid']."\">Add Template</option>\n");
						print("\t\t\t\t\t<option value=\"style.php?do=add_replacement&styleid=".$styleinfo['styleid']."\">Add Replacement</option>\n");
					print("\t\t\t\t</select>\n");
					print("\t\t\t\t <button type=\"button\" onClick=\"location.href=(document.style_order.control".$styleinfo['styleid'].".options[document.style_order.control".$styleinfo['styleid'].".selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px; margin-top: 0px;\" ".$submitbg.">Go</button>\n");
			print("\t\t</td>\n");

		print("\t</tr>\n\n");
	}

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"5\"><button type=\"submit\" name=\"style_order[submitid]\" ".$submitbg.">Save</button> &nbsp;&nbsp;&nbsp; <button type=\"button\" onclick=\"location.href='style.php?do=add_style';\" ".$submitbg.">Add Style</button></td></tr>\n");
	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO EDIT TEMPLATES ##### \\
else if($_GET['do'] == "edit_templates") {
	// make sure we have a valid template...
	if($_GET['d'] == "yes") {
		$getTemplate = query("SELECT * FROM templates_default WHERE defaultid = '".$_GET['templateid']."' AND templategroupid = '".$_GET['groupid']."' LIMIT 1");
	}

	// otherwise we already have an edited template...
	else {
		$getTemplate = query("SELECT * FROM templates WHERE templateid = '".$_GET['templateid']."' LIMIT 1");
	}

	// uh oh...
	if(!mysql_num_rows($getTemplate)) {
		construct_error("Sorry, no template with the given ID exists. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// array
	$templateinfo = mysql_fetch_array($getTemplate);
	$styleinfo_q = query("SELECT * FROM styles WHERE styleid = '".$_GET['styleid']."' LIMIT 1");

	if(!mysql_num_rows($styleinfo_q)) {
		construct_error("Sorry, no style exists with the given ID. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	$styleinfo = mysql_fetch_array($styleinfo_q);

	// get temp id
	if($_GET['d'] == "yes") {
		$getTemplateId = $templateinfo['defaultid'];
	} else {
		$getTemplateId = $templateinfo['templateid'];
	}

	if($_POST['edit_template']['set_form']) {
		if(!$templateinfo['is_global']) {
			// get the template group id...
			if($_POST['edit_template']['templategroupid'] == "add_templategroup") {
				// add a new template group.. than get the ID
				$addTemplateGroup = query("INSERT INTO templategroups (title) VALUES ('".addslashes($_POST['edit_template']['new_templategroup'])."')");

				$getTemplategroupID = mysql_insert_id();
			}
			
			else if($_POST['edit_template']['templategroupid'] != "custom" AND $_POST['edit_template']['templategroupid'] != "add_templategroup") {
				$getTemplategroupID = $_POST['edit_template']['templategroupid'];
			}

			else if($_POST['edit_template']['templategroupid'] == "custom") {
				$getTemplategroupID = 0;
			}

			// get is custom...
			if($_POST['edit_template']['templategroupid'] == "custom") {
				$getIsCustom = 1;
				$getTemplategroupID = 0;
			} else {
				$getIsCustom = 0;
			}

			$theStyleID = $_GET['styleid'];
		}

		else {
			$getIsCustom = 0;
			$getTemplategroupID = 0;
			$theStyleID = 0;
		}

		// parse the conditionals in the template...
		$phpTemplate = parseConditionals($_POST['edit_template']['template']);

		// time to.... insert? or maybe... UPDATE?
		//if($_GET['d'] == "yes" AND !$_POST['edit_template']['keep_default']) {
		if($_GET['d'] == "yes") {
			// insert...
			$query = "INSERT INTO templates (type,templategroupid,title,template,defaultid,styleid,last_edit,username,version,is_custom,template_php) VALUES ('2','".$getTemplategroupID."','".addslashes($_POST['edit_template']['title'])."','".addslashes($_POST['edit_template']['template'])."','".$getTemplateId."','".$theStyleID."','".time()."','".$_COOKIE['wtcBB_adminUsername']."','".$bboptions['version_num']."','".$getIsCustom."','".$phpTemplate."')";

			// run query
			query($query);

			$getTemplateId = mysql_insert_id();
		}

		else {
			// DEVELOPMENT PURPOSES... keep as default?
			/*if($_POST['edit_template']['keep_default']) {
				// update DEFAULT
				$query = "UPDATE templates_default SET title = '".addslashes($_POST['edit_template']['title'])."' , template = '".addslashes($_POST['edit_template']['template'])."' , templategroupid = '".$getTemplategroupID."' , template_php = '".$phpTemplate."' WHERE defaultid = '".$templateinfo['defaultid']."'";			
			}

			else {*/
				// update
				$query = "UPDATE templates SET title = '".addslashes($_POST['edit_template']['title'])."' , template = '".addslashes($_POST['edit_template']['template'])."' , last_edit = '".time()."' , username = '".$_COOKIE['wtcBB_adminUsername']."' , templategroupid = '".$getTemplategroupID."' , is_custom = '".$getIsCustom."' , template_php = '".$phpTemplate."' WHERE templateid = '".$getTemplateId."'";

				if($templateinfo['defaultid'] > 0 AND $templateinfo['defaultid'] != null) {
					query("UPDATE templates_default SET templategroupid = '".$getTemplategroupID."' WHERE defaultid = '".$templateinfo['defaultid']."'");
				}
			//}

			// run query
			query($query);

			$getTemplateId = $_GET['templateid'];
		}

		if(!$templateinfo['is_global']) {
			if($_POST['reload']) {
				/*if($_POST['edit_template']['keep_default']) {					
					redirect("style.php?do=edit_templates&d=yes&templateid=".$getTemplateId."&styleid=".$_GET['styleid']."&groupid=".$_GET['groupid']);
				}

				else {*/
					redirect("style.php?do=edit_templates&templateid=".$getTemplateId."&styleid=".$_GET['styleid']."&groupid=".$_GET['groupid']);
				//}
			} else {
				// redirect to thankyou page...
				redirect("thankyou.php?message=Template edited successfully. You will now be redirected to the Style Manager.&uri=style.php?do=templatesSTEVEid=".$_GET['styleid']."STEVEgroupid=".$_GET['groupid']);
			}
		} else {
			if($_POST['reload']) {
				redirect("style.php?do=edit_templates&templateid=".$getTemplateId."&styleid=".$_GET['styleid']."&groupid=".$_GET['groupid']);
			} else {
				// redirect to thankyou page...
				redirect("thankyou.php?message=Template edited successfully. You will now be redirected to the Style Manager.&uri=style.php?do=templatesSTEVEid=".$_GET['styleid']."STEVEaction=global");
			}
		}
	}

	// do header
	admin_header("wtcBB Admin Panel - Styles - Edit Template");

	construct_title("Edit Template");

	?>

	<table border="0" cellspacing="0" cellpadding="4" class="options">
		<tr>
			<td class="header">Navigation</td>
		</tr>

		<tr>
			<td class="desc1_bottom" style="text-align: center; padding: 7px;">
				<form style="margin: 0px; padding: 0px;" method="post" action="" name="myForm">
					<select name="navi" onChange="location.href=(myForm.navi.options[myForm.navi.selectedIndex].value)">
						<option value="#" selected="selected">---NAVIGATION---</option>
						<option value="style.php?do=templates&id=<?php print($styleinfo['styleid']); ?>">Edit Style Templates</option>
						<option value="style.php?do=colors&id=<?php print($styleinfo['styleid']); ?>">Edit Style Colors</option>
						<option value="style.php?do=edit_style&id=<?php print($styleinfo['styleid']); ?>">Edit Style Options</option>
						<option value="style.php?do=add_template&styleid=<?php print($styleinfo['styleid']); ?>">Add Template</option>
						<option value="style.php?do=add_replacement&styleid=<?php print($styleinfo['styleid']); ?>">Add Replacement</option>
					</select>

					<button type="button" onClick="location.href=(myForm.navi.options[myForm.navi.selectedIndex].value)" style="margin: 2px; margin-bottom: 0px;" <?php print($submitbg); ?>>Go</button>
				</form>
			</td>
		</tr>

		<tr>
			<td class="footer" colspan="0">&nbsp;</td>
		</tr>

	</table>

	<br />

	<?php

	construct_table("options","edit_template","template_submit",1);

	construct_header("Edit Template <em>".$templateinfo['title']."</em> <span class=\"small\">(id: ".$getTemplateId.")</span>",2);

	//construct_input(1,"Keep as default?","---DEVELOPMENT PURPOSES ONLY (TEMPORARY)---","edit_template","keep_default",0,1);

	construct_text(1,"Title","","edit_template","title",$templateinfo['title']);

	if($templateinfo['is_global'] == 0) {
		construct_select_begin(2,"Template Group","Select the template group that you want this template to belong to.","edit_template","templategroupid");
				// get templategroups
				$templategroups = query("SELECT * FROM templategroups ORDER BY title");

				if($templateinfo['is_custom'] == 1) {
					$selectedCustom = ' selected="selected"';
				} else {
					$selectedCustom = '';
				}

				print("<option value=\"custom\"".$selectedCustom.">Custom Templates</option>\n");
				print("<option value=\"add_templategroup\">Other (See field below)</option>\n");

				if(mysql_num_rows($templategroups) > 0) {
					while($templategroupinfo = mysql_fetch_array($templategroups)) {
						if($templateinfo['templategroupid'] == $templategroupinfo['templategroupid']) {
							$isSelected = ' selected="selected"';
						} else {
							$isSelected = '';
						}

						print("<option value=\"".$templategroupinfo['templategroupid']."\"".$isSelected.">".$templategroupinfo['title']."</option>\n");
					}
				}

		construct_select_end(2);

		construct_text(1,"Create New Template Group","Enter the name of the new template group here, if you have made the proper selection above.","edit_template","new_templategroup");
	}

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc2\" colspan=\"2\">\n");
			print("\t\t\t\t<strong>Template:</strong> <br /><br />\n\n");
			
			print("\t\t\t\t<div style=\"text-align: center;\">\n");
				print("\t\t\t\t\t<textarea cols=\"60\" rows=\"24\" style=\"font-family: courier new; font-size: 10pt;\" name=\"edit_template[template]\">".htmlspecialchars($templateinfo['template'])."</textarea>\n");
			print("\t\t\t\t</div>\n");

		print("\t\t\t</td>\n");
	print("\t\t</tr>\n");

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"2\"><input type=\"submit\" value=\"Save & Reload\" name=\"reload\" class=\"button\" ".$submitbg." /> &nbsp;&nbsp;&nbsp; <input type=\"submit\" ".$submitbg." value=\"Save\" class=\"button\" /> &nbsp;&nbsp;&nbsp; <button type=\"reset\" ".$submitbg.">Reset</button> &nbsp;&nbsp;&nbsp; <button type=\"button\" onclick=\"window.open('style.php?do=view_default&id=".$templateinfo['defaultid']."', 'newpop', 'height=575, width=675, resizable=yes, scrollbars=yes');\" ".$submitbg.">View Default</button></td></tr>\n");
	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO ADD TEMPLATE ##### \\

else if($_GET['do'] == "add_template") {
	// make sure styles exist...
	$checkStyles = query("SELECT * FROM styles ORDER BY title");

	// uh oh...
	if(!mysql_num_rows($checkStyles)) {
		construct_error("Sorry, no styles exist for you to add templates to. Please add one on the <a href=\"style.php?do=add_style\">Add Style</a> page.");
		exit;
	}

	// add template
	if($_POST['add_template']['set_form']) {
		// get the template group id...
		if($_POST['add_template']['styleid'] == "global") {
			$getTemplategroupID = 0;
		}
		
		else if($_POST['add_template']['templategroupid'] == "add_templategroup") {
			// add a new template group.. than get the ID
			$addTemplateGroup = query("INSERT INTO templategroups (title) VALUES ('".addslashes($_POST['add_template']['new_templategroup'])."')");

			$getTemplategroupID = mysql_insert_id();
		}
		
		/*else if(($_POST['add_template']['is_default']) OR ($_POST['add_template']['templategroupid'] != "custom" AND $_POST['add_template']['templategroupid'] != "add_templategroup")) {
			$getTemplategroupID = $_POST['add_template']['templategroupid'];
		}*/

		else if($_POST['add_template']['templategroupid'] != "custom" AND $_POST['add_template']['templategroupid'] != "add_templategroup") {
			$getTemplategroupID = $_POST['add_template']['templategroupid'];
		}		

		else if($_POST['add_template']['templategroupid'] == "custom") {
			$getTemplategroupID = 0;
		}

		// get is global..
		if($_POST['add_template']['styleid'] == "global") {
			$getIsGlobal = 1;
			$getStyleID = 0;
		} else {
			$getIsGlobal = 0;
			$getStyleID = $_POST['add_template']['styleid'];
		}

		// get is custom...
		if($_POST['add_template']['templategroupid'] == "custom" AND $getIsGlobal == 0) {
			$getIsCustom = 1;
		} else {
			$getIsCustom = 0;
		}

		$phpTemplate = parseConditionals($_POST['add_template']['template']);
			
		// insert query...
		/*if($_POST['add_template']['is_default']) {
			// insert into default templates...
			$query = query("INSERT INTO templates_default (title,templategroupid,template,type,version,template_php) VALUES ('".addslashes($_POST['add_template']['title'])."','".$getTemplategroupID."','".addslashes($_POST['add_template']['template'])."','1','".$bboptions['version_num']."','".$phpTemplate."')");
		} else {*/
			// get article date?
			if(strpos($_POST['add_template']['title'],"wtc_article") !== false) {
				$artDate = time();
			} else {
				$artDate = null;
			}

			// insert into reg. templates
			if(strpos($_POST['add_template']['title'],"wtc_article") !== false) {
				$query = query("INSERT INTO templates (title,templategroupid,template,type,version,styleid,last_edit,username,is_global,is_custom,template_php,article_made) VALUES ('".addslashes($_POST['add_template']['title'])."','".$getTemplategroupID."','".addslashes($_POST['add_template']['template'])."','2','".$bboptions['version_num']."','".$getStyleID."','".time()."','".$_COOKIE['wtcBB_adminUsername']."','".$getIsGlobal."','".$getIsCustom."','".$phpTemplate."','".$artDate."')");
			} else {
				$query = query("INSERT INTO templates (title,templategroupid,template,type,version,styleid,last_edit,username,is_global,is_custom,template_php) VALUES ('".addslashes($_POST['add_template']['title'])."','".$getTemplategroupID."','".addslashes($_POST['add_template']['template'])."','2','".$bboptions['version_num']."','".$getStyleID."','".time()."','".$_COOKIE['wtcBB_adminUsername']."','".$getIsGlobal."','".$getIsCustom."','".$phpTemplate."')");
			}
		//}

		if(!$getStyleID) {
			$getStyleID = 1;
		}

		// redirect to thankyou page...
		redirect("thankyou.php?message=Template added successfully. You will now be redirected to the Style Manager.&uri=style.php?do=templatesSTEVEid=".$getStyleID."STEVEgroupid=".$getTemplategroupID);
	}

	// do header
	admin_header("wtcBB Admin Panel - Styles - Add Template Style");

	construct_title("Add Template");

	construct_table("options","add_template","template_submit",1);

	construct_header("Add Template",2);

	construct_text(2,"Title","","add_template","title");


	construct_select_begin(1,"Style","Select the style that you want this template to belong to. You can select \"Global\" to have this template blong to all styles.","add_template","styleid");
			if(!$_GET['styleid']) {
				print("<option value=\"global\" selected=\"selected\">Global Template</option>\n");
			} else {
				print("<option value=\"global\">Global Template</option>\n");
			}

			// loop through styles
			while($styleinfo = mysql_fetch_array($checkStyles)) {
				// get selected...
				if($_GET['styleid']) {
					if($_GET['styleid'] == $styleinfo['styleid']) {
						$selected = " selected=\"selected\"";
					}

					else {
						$selected = "";
					}
				} else {
					$selected = "";
				}

				print("<option value=\"".$styleinfo['styleid']."\"".$selected.">".$styleinfo['title']."</option>\n");
			}

	construct_select_end(1);


	construct_select_begin(2,"Template Group","Select the template group that you want this template to belong to.","add_template","templategroupid");
			// get templategroups
			$templategroups = query("SELECT * FROM templategroups ORDER BY title");

			print("<option value=\"custom\" selected=\"selected\">Custom Templates</option>\n");
			print("<option value=\"add_templategroup\">Other (See field below)</option>\n");

			if(mysql_num_rows($templategroups)) {
				while($templategroupinfo = mysql_fetch_array($templategroups)) {
					print("<option value=\"".$templategroupinfo['templategroupid']."\">".$templategroupinfo['title']."</option>\n");
				}
			}

	construct_select_end(2);


	construct_text(1,"Create New Template Group","If you selected \"Other\" in the field above, enter the name of the template group here.","add_template","new_templategroup");

	print("\t\t<tr>\n");
		print("\t\t\t<td class=\"desc2_bottom\" colspan=\"2\">\n");
			print("\t\t\t\t<strong>Template:</strong> <br /><br />\n\n");
			
			print("\t\t\t\t<div style=\"text-align: center;\">\n");
				print("\t\t\t\t\t<textarea cols=\"60\" rows=\"24\" style=\"font-family: courier new; font-size: 10pt;\" name=\"add_template[template]\"></textarea>\n");
			print("\t\t\t\t</div>\n");

		print("\t\t\t</td>\n");
	print("\t\t</tr>\n");

	//construct_input(1,"Is Default? (temporary option)","---DEVELOPMENT PURPOSES (TEMPORARY)---","add_template","is_default",1,1);

	construct_footer(2,"style_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO EDIT STYLE ##### \\
else if($_GET['do'] == "edit_style") {
	// make sure we have a valid styleid
	$checkStyle = query("SELECT * FROM styles WHERE styleid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkStyle)) {
		construct_error("Sorry, no style exists for the given ID.");
		exit;
	}

	// array
	$styleinfo = mysql_fetch_array($checkStyle);

	// update
	if($_POST['edit_style']['set_form']) {
		// form query by hand
		$query = "UPDATE styles SET title = '".addslashes($_POST['edit_style']['title'])."' , user_selection = '".$_POST['edit_style']['user_selection']."' , display_order = '".addslashes($_POST['edit_style']['display_order'])."' WHERE styleid = '".$_GET['id']."'";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Style <em>".$_POST['edit_style']['title']."</em> edited successfully. You will now be redirected to the Style Manager.&uri=style.php?do=manager");
	}

	// do header
	admin_header("wtcBB Admin Panel - Styles - Edit Style");

	construct_title("Edit Style");

	?>

	<table border="0" cellspacing="0" cellpadding="4" class="options">
		<tr>
			<td class="header">Navigation</td>
		</tr>

		<tr>
			<td class="desc1_bottom" style="text-align: center; padding: 7px;">
				<form style="margin: 0px; padding: 0px;" method="post" action="" name="myForm">
					<select name="navi" onChange="location.href=(myForm.navi.options[myForm.navi.selectedIndex].value)">
						<option value="#" selected="selected">---NAVIGATION---</option>
						<option value="style.php?do=templates&id=<?php print($styleinfo['styleid']); ?>">Edit Style Templates</option>
						<option value="style.php?do=colors&id=<?php print($styleinfo['styleid']); ?>">Edit Style Colors</option>
						<option value="style.php?do=edit_style&id=<?php print($styleinfo['styleid']); ?>">Edit Style Options</option>
						<option value="style.php?do=add_template&styleid=<?php print($styleinfo['styleid']); ?>">Add Template</option>
						<option value="style.php?do=add_replacement&styleid=<?php print($styleinfo['styleid']); ?>">Add Replacement</option>
					</select>

					<button type="button" onClick="location.href=(myForm.navi.options[myForm.navi.selectedIndex].value)" style="margin: 2px; margin-bottom: 0px;" <?php print($submitbg); ?>>Go</button>
				</form>
			</td>
		</tr>

		<tr>
			<td class="footer" colspan="0">&nbsp;</td>
		</tr>

	</table>

	<br />

	<?php

	construct_table("options","edit_style","style_submit",1);

	construct_header("Edit Style <em>".$styleinfo['title']."</em> <span class=\"small\">(id: ".$styleinfo['styleid'].")</span>",2);

	construct_text(1,"Title","","edit_style","title",$styleinfo['title']);

	construct_input(2,"Allow User Selection","Enabling this option will allow registered users to select this style in their user control panel.","edit_style","user_selection",0,0,$styleinfo);

	construct_text(1,"Display Order","","edit_style","display_order",$styleinfo['display_order'],1);

	construct_footer(2,"style_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO ADD STYLE ##### \\
else if($_GET['do'] == "add_style") {
	if($_POST['add_style']['set_form']) {
		// form query by hand
		$query = "INSERT INTO styles (title,user_selection,display_order) VAlUES ('".$_POST['add_style']['title']."','".$_POST['add_style']['user_selection']."','".$_POST['add_style']['display_order']."')";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Style added successfully. You will now be redirected to the Style Manager.&uri=style.php?do=manager");
	}

	// do header
	admin_header("wtcBB Admin Panel - Styles - Add Style");

	construct_title("Add Style");

	construct_table("options","add_style","style_submit",1);

	construct_header("Add Style",2);

	construct_text(1,"Title","","add_style","title");

	construct_input(2,"Allow User Selection","Enabling this option will allow registered users to select this style in their user control panel.","add_style","user_selection",0,2);

	construct_text(1,"Display Order","","add_style","display_order","1",1);

	construct_footer(2,"style_submit");

	construct_table_END(1);

	// do footer
	admin_footer();
}

// otherwise we have an error on our hands.. invalid page...
else {
	construct_error("Invalid page");
}


?>