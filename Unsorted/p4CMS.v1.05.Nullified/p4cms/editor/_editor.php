<?
error_reporting (0);

$settings = Array();
$settings['app_dir'] = "_i3"; // application files directory (no trailing slash)

global $HTTP_GET_VARS,$HTTP_POST_VARS,$HTTP_SERVER_VARS,$PHP_SELF;

@include($settings[app_dir].'/inc/config.php');
@include($settings[app_dir].'/lang/lang_'.$settings['language'].'.php');
if($settings['security'] == "1") {
	include($settings[app_dir].'/inc/security.php');
}

// get protocol type to enable even https:// secure servers
$protocol = strtolower(substr($HTTP_SERVER_VARS['SERVER_PROTOCOL'], 0, strpos($HTTP_SERVER_VARS['SERVER_PROTOCOL'],"/")));

// get absolute path for replacing in relative links (IIS friendly)
$full_path = $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
if(!empty($_SERVER['QUERY_STRING'])) {
   $full_path .= '?'.$HTTP_SERVER_VARS['QUERY_STRING'];
}
$path_chunks = parse_url($full_path);
$work_path = $path_chunks['scheme']."://".$path_chunks['host'].$path_chunks['path'];
$abs_path = substr($work_path,0,strrpos($work_path,"/"));

$abs_path = $protocol . "://" . $_SERVER['HTTP_HOST'] . "/";

// get options from initial tag
if(isset($HTTP_GET_VARS["options"])) {
	$optpack = $HTTP_GET_VARS["options"];
}

// new in v.2.3 - multiple input methods
	// original form/textarea method
	if((isset($HTTP_GET_VARS["formname"])) && (isset($HTTP_GET_VARS["inputname"]))) {
		$formname = $HTTP_GET_VARS["formname"];
		$inputname = $HTTP_GET_VARS["inputname"];
		$input_method = 1;
		$title_entry = $inputname;

	// new DOM GetElementById method
	} elseif(isset($HTTP_GET_VARS["id"])) {
		$id = $HTTP_GET_VARS["id"];
		$input_method = 2;
		$title_entry = $id;

	// direct database access method from link (GET)
	} elseif((isset($HTTP_GET_VARS["dbname"])) && (isset($HTTP_GET_VARS["dbtable"])) && (isset($HTTP_GET_VARS["dbfield"]))) {

		$input_method = 3;
		include($settings[app_dir].'/inc/db_bridge.php');
		$title_entry = $dbfield;

	// direct database access method from form (POST)
	} elseif((isset($HTTP_POST_VARS["dbname"])) && (isset($HTTP_POST_VARS["dbtable"])) && (isset($HTTP_POST_VARS["dbfield"]))) {

		$input_method = 4;
		include($settings[app_dir].'/inc/db_bridge.php');
		$title_entry = $HTTP_POST_VARS["dbfield"];
	
	// static file editing
	} elseif(isset($HTTP_GET_VARS["filename"])) {

		$input_method = 5;
		include($settings['app_dir'].'/inc/file_bridge.php');
		$title_entry = $HTTP_GET_VARS["filename"];
	}

// on the fly settings - new from v 1 build 9 - see docs
if($optpack == "") {
	// keep settings from config file
} else {
	// use settings provided in init link
	// order of settings
	// 1. Local image selector  0 - 0ff / 1 - On
	// 2. Table functions  0 - Off / 1 - On
	// 3. File Functions  0 - Off / 1 - On
	// 4. Color Picker  0 - Off / 1 - On
	// 5. Font Settings  0 - Off / 1 - On
	// 6. Relative Paths 0 - Off / 1 - On
	// 7. Cascade Style Sheet - with path
	$optionsArray = split(',' , $optpack);
	$nn = 1;
	foreach($optionsArray as $value) {
		$option[$nn] = $value;
		$nn++;
	}
}

if(isset($HTTP_GET_VARS['op'])) {
	$op = $HTTP_GET_VARS['op'];
} elseif(isset($HTTP_POST_VARS['op'])) {
	$op = $HTTP_POST_VARS['op'];
} else {
	$op = "";
}

switch($op) {
##############################################################################
# default editor case                                                        #
##############################################################################
	
	default:
echo  <<<content
		<html>
			<head>
				<meta http-equiv="content-type" content="text/html;charset=".$lang[charset]."">
				<title>$settings[product_name] $settings[version] - $lang[editing_content] "$title_entry" $lang[field]</title>

				<style type="text/css">
					<!--	
						body { background-color: #FFFFFF; color: #000000; }
						.h {  cursor: hand; }
						.framed { border: 1px solid $settings[border_color]; background-color: $settings[bgcolor];	}

						.o {    
						BORDER-BOTTOM: gray 1px solid;
						BORDER-LEFT: white 1px solid;
						BORDER-RIGHT: gray 1px solid;
						BORDER-TOP: white 1px solid;
						MARGIN-LEFT: 0px;
						MARGIN-RIGHT: 0px;
						MARGIN-TOP: 0px;
						MARGIN-BOTTOM: 0px;
						cursor: hand;
						}
		
						.c {
						BORDER-BOTTOM: white 1px solid;
						BORDER-LEFT: gray 1px solid;
						BORDER-RIGHT: white 1px solid;
						BORDER-TOP: gray 1px solid;
						MARGIN-LEFT: 0px;
						MARGIN-RIGHT: 0px;
						MARGIN-TOP: 0px;
						MARGIN-BOTTOM: 0px;
						cursor: hand;
						}
		
						.n {
						MARGIN-LEFT: 1px;
						MARGIN-RIGHT: 1px;
						MARGIN-TOP: 1px;
						MARGIN-BOTTOM: 1px;
						VISIBILITY: visible;
						}

						.select_update {
						background-color: #E7EDF8;
						color: #002D80;
						}

					-->
				</style>
			</head>

			<body  leftmargin="2" marginwidth="2" topmargin="2" marginheight="2" onResize="blockDefault();">

content;
				include($settings['app_dir'].'/js/core_js.php');

echo  <<<content
				<script LANGUAGE="JavaScript">
					window.onload = initEditor
				</script>
				<table border="0" cellpadding="5" cellspacing="0" width="100%" height="100%" class="framed">
					<tr>
						<td align="top" height=30>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="top">
content;
								if(($input_method == 1) || ($input_method == 2)) {
									echo "<img unselectable=\"on\" class='n' src=\"".$settings[app_dir]."/img/new.gif\" width=\"30\" height=\"30\" border=\"0\" align=\"absmiddle\" alt=\"".$lang[start_over]."\" onClick=\"newDoc();className='c';\" onMouseOver=\"className='o'\" onMouseOut=\"className='n'\">";
								}
echo  <<<content
										<img unselectable="on" class='n' src="$settings[app_dir]/img/save.gif" width="30" height="30" border="0" align="absmiddle" alt="$lang[save_doc]" onClick="OnFormSubmit();className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/spacer.gif" width="8" height="30" border="0" align="absmiddle"><img unselectable="on" class='n' src="$settings[app_dir]/img/cut.gif" width="30" height="30" border="0" align="absmiddle" alt="$lang[cut]" onClick="doFormat('Cut');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/copy.gif" width="30" height="30" border="0" align="absmiddle" alt="$lang[copy]" onClick="doFormat('Copy');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/paste.gif" width="30" height="30" border="0" align="absmiddle" alt="$lang[paste]" onClick="doFormat('Paste');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/undo.gif" width="30" height="30" border="0" align="absmiddle" alt="$lang[undo]" onClick="execCommand('Undo');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/redo.gif" width="30" height="30" border="0" align="absmiddle" alt="$lang[redo]" onClick="execCommand('Redo');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/spacer.gif" width="8" height="30" border="0" align="absmiddle"><img unselectable="on" class='n' src="$settings[app_dir]/img/ul.gif" width="30" height="30" border="0" align="absmiddle" alt="$lang[list_bullets]" onClick="doFormat('InsertUnorderedList');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/ol.gif" width="30" height="30" border="0" align="absmiddle" alt="$lang[list_numbers]" onClick="doFormat('InsertOrderedList');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/tab_.gif" width="30" height="30" border="0" align="absmiddle" alt="$lang[indent_decr]" onClick="doFormat('Outdent');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/tab+.gif" width="30" height="30" border="0" align="absmiddle" alt="$lang[indent_incr]" onClick="doFormat('Indent');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/hr.gif" width="30" height="30" border="0" align="absmiddle" alt="$lang[ruler]" onClick="doFormat('InsertHorizontalRule');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/spacer.gif" width="8" height="30" border="0" align="absmiddle">
content;
										if($option[1]==1) { 
										echo "<img unselectable=\"on\" class='n' src=\"".$settings['app_dir']."/img/img_loc.gif\" width=\"30\" height=\"30\" border=\"0\" align=\"absmiddle\" alt=\"".$lang['insert_edit_image']."\" onClick=\"blockLocIMG();className='c';\" onMouseOver=\"className='o'\" onMouseOut=\"className='n'\">";
										echo "<img class='n' src=\"".$settings['app_dir']."/img/spacer.gif\" width=\"8\" height=\"30\" border=\"0\" align=\"absmiddle\">";
										}
										if($option[2]==1) { 
										echo "<img unselectable=\"on\" class='n' src=\"".$settings['app_dir']."/img/table.gif\" width=\"30\" height=\"30\" border=\"0\" align=\"absmiddle\" alt=\"".$lang['insert_edit_table']."\" onClick=\"blockTable();className='c';\" onMouseOver=\"className='o'\" onMouseOut=\"className='n'\">";
										echo "<img unselectable=\"on\" class='n' src=\"".$settings['app_dir']."/img/td.gif\" width=\"30\" height=\"30\" border=\"0\" align=\"absmiddle\" alt=\"".$lang['edit_table_cell']."\" onClick=\"editTable();className='c';\" onMouseOver=\"className='o'\" onMouseOut=\"className='n'\"><img class='n' src=\"".$settings['app_dir']."/img/spacer.gif\" width=\"8\" height=\"30\" border=\"0\" align=\"absmiddle\">";
										}
										if($option[4]==1) { 
										echo "<img unselectable=\"on\" class='n' src=\"".$settings['app_dir']."/img/fg.gif\" width=\"30\" height=\"30\" border=\"0\" align=\"absmiddle\" alt=\"".$lang['color_picker']." (".$lang['foreground_color'].")\" onClick=\"blockPicker('fg');className='c';\" onMouseOver=\"className='o'\" onMouseOut=\"className='n'\"><img class='n' src=\"".$settings['app_dir']."/img/bg.gif\" width=\"30\" height=\"30\" border=\"0\" align=\"absmiddle\" alt=\"".$lang['color_picker']." (".$lang['background_color'].")\" onClick=\"blockPicker('bg');className='c';\" onMouseOver=\"className='o'\" onMouseOut=\"className='n'\">";
										}

										if($settings['disable_html_view'] == 0) {
											echo "&nbsp&nbsp;<input type=\"button\" name=\"swapButton\" value=\"".$lang['switch_html']."\" onClick=\"Swap_it();\" style=\"width:110px; font: 9.9px verdana; text-align: center; color: #ffffff; background-color: #CE0000;\">";
										}
echo  <<<content
								</td>
								</tr>
								<tr>
									<td background="$settings[app_dir]/img/dashline.gif"><img unselectable="on" src="$settings[app_dir]/img/pix.gif" width="100" height="6" border="0"></td>
								</tr>
								<tr height=20>
									<td valign="bottom" nowrap>
content;
if($option[7] != "") {
	echo "<select name=\"css_style\" onChange=\"applyStyle(this)\" style=\"font: 11.9px verdana;\">\n";
	echo "\t\t\t\t\t<OPTION>CSS Style</OPTION>\n";
	echo "\t\t\t\t</SELECT>&nbsp;";
}
if($option[5] == 1) {
echo  <<<content
									<select name="format" onChange="doFormat('formatBlock','&lt;'+document.all.format.value+'&gt;');" style="font: 11.9px verdana; width: 80px;">
										<option value="1" selected>$lang[format] ...</option>
										<option value="p">$lang[normal]</option>
										<option value="H1">$lang[heading] 1</option>
										<option value="H2">$lang[heading] 2</option>
										<option value="H3">$lang[heading] 3</option>
										<option value="H4">$lang[heading] 4</option>
										<option value="H5">$lang[heading] 5</option>
										<option value="H6">$lang[heading] 6</option>
										<option value="PRE">$lang[preformated]</option>
									</select>&nbsp;
							
									<select name="font" onChange="doFormat('FontName',document.all.font.value);" style="font: 11.9px verdana; width: 170px;">
										<option value="1" selected>$lang[select_font]...</option>
										<option value="Arial, Helvetica, sans-serif">Arial, Helvetica, sans-serif</option>
										<option value="Comic Sans MS">Comic Sans MS</option>
										<option value="Courier New, Courier, mono">Courier New, Courier, mono</option>
										<option value="Georgia, Times New Roman">Georgia, Times New Roman</option>
										<option value="System">System</option>
										<option value="Times New Roman, Times, serif">Times New Roman, Times</option>
										<option value="Verdana, Arial, Helvetica">Verdana, Arial, Helvetica</option>
										<option value="Windings">Wingdings</option>

									</select>&nbsp;
content;
	if($settings['font_size_type'] == "pt") {
		echo "<select name=\"size\" onChange=\"setFontSize(document.all.size.value);\" style=\"font: 11.9px verdana; width: 35px;\">";
		echo "<option value=\"None\" selected>$lang[size]</option>";
		foreach($settings[font_size_pt] as $value) {
			echo "<option value=\"".$value."\">".$value."</option>";
		}
		echo "</select>";
	} else {
		echo "<select name=\"size\" onChange=\"doFormat('FontSize',document.all.size.value);\" style=\"font: 11.9px verdana; width: 35px;\">";
		echo "<option value=\"None\" selected>$lang[size]</option>";
		foreach($settings[font_size_num] as $value) {
			echo "<option value=\"".$value."\">".$value."</option>";
		}
		echo "</select>";
	}
}
echo  <<<content
									<img unselectable="on" class='n' src="$settings[app_dir]/img/b.gif" width="30" height="20" border="0"  align="absmiddle" alt="$lang[bold_text]" onClick="doFormat('Bold');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/i.gif" width="30" height="20" border="0" align="absmiddle" alt="$lang[italic_text]" onClick="doFormat('Italic');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/u.gif" width="30" height="20" border="0" align="absmiddle" alt="$lang[underline_text]" onClick="doFormat('Underline');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/left.gif" width="30" height="20" border="0" alt="$lang[align_left]" align="absmiddle" onClick="doFormat('JustifyLeft');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/center.gif" width="30" height="20" border="0" alt="$lang[align_center]" align="absmiddle" onClick="doFormat('JustifyCenter');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/right.gif" width="30" height="20" border="0" alt="$lang[align_right]" align="absmiddle" onClick="doFormat('JustifyRight');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/full.gif" width="30" height="20" border="0" alt="$lang[justify_full]" align="absmiddle" onClick="doFormat('JustifyFull');className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/link.gif" width="30" height="20" border="0" align="absmiddle" alt="$lang[add_link]" onClick="blockCreateLink();className='c';" onMouseOver="className='o'" onMouseOut="className='n'"><img unselectable="on" class='n' src="$settings[app_dir]/img/unlink.gif" width="30" height="20" border="0" align="absmiddle" alt="$lang[remove_link]" onClick="doFormat('Unlink');className='c';" onMouseOver="className='o'" onMouseOut="className='n'">
									<img unselectable="on" class='n' src="$settings[app_dir]/img/frame.gif" width="30" height="20" border="0" align="absmiddle" alt="$lang[framing_on_off]" onClick="switchFraming();className='c';" onMouseOver="className='o'" onMouseOut="className='n'">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td valign="top">
				
							<!-- start of iframe area -->
							<IFRAME ID="textEdit" width="100%" height="95%"></IFRAME>
							<script LANGUAGE="JavaScript">
							textEdit.focus()
							</script>
							<!-- end of iframe area -->
				
							<img src="$settings[app_dir]/img/pix.gif" width="100" height="5"><br>
				
							<!-- start of submenu area -->
							<IFRAME ID="subMenu" width="100%" height="3%" style="border: solid 1px black;" frameborder="0" noresize scrolling="no"></IFRAME>
							<!-- end of submenu area -->

							<!-- start of pool area -->
content;
							if(($input_method == 3) || ($input_method == 4)) {
								echo "<IFRAME ID=\"pool\" width=\"0\" height=\"0\" style=\"display: none;\" frameborder=\"0\" noresize scrolling=\"no\" src=\"".$settings[app_dir]."/inc/db_bridge.php?dbworks=init&dbname=".$dbname."&dbtable=".$dbtable."&dbfield=".$dbfield."&dbai=".$dbai."&dbrecord=".$dbrecord."&dbsafe=".$dbsafe."&dbreturn=".$dbreturn."\">";
							} elseif($input_method == 5) {
								echo "<IFRAME ID=\"pool\" width=\"0\" height=\"0\" style=\"display: none;\" frameborder=\"0\" noresize scrolling=\"no\" src=\"".$settings[app_dir]."/inc/file_bridge.php?fileworks=init&filename=".$filename."&filereturn=".$filereturn."\">";
							} else {
								echo "<IFRAME ID=\"pool\" width=\"0\" height=\"0\" style=\"display: none;\" frameborder=\"0\" noresize scrolling=\"no\">";
							}
echo  <<<content
							</IFRAME>
							<!-- end of pool area -->

						</td>
					</tr>
				</table>
			</body>
		</html>
content;

	break;


##############################################################################
# image editor case                                                          #
##############################################################################

	case "image_edit":

	$activedir = $HTTP_GET_VARS[activedir];

	if(empty($activedir)) { 
		$activedir = $settings[images_root]; 
	} 

	// generate clickable path
	$show_path = "";
	$activepath = explode('/', $activedir);
	for($i=0; $i<count($activepath); $i++) {
		if(empty($activepath[$i])) {
			continue;
		}
		$show_path .= "<a href='_editor.php?op=image_edit&activedir=";
		for($j=0; $j<=$i; $j++) {
			if(empty($activepath[$j])) {
				continue;
			}
			$show_path .= $activepath[$j].'/';
		}
		$show_path .= "'>".$activepath[$i]."/</a>";
	}

	
	// generate subfolders select box
	$subfolders = "";
	@chdir($activedir);
	$handle=opendir('.');
		while ($folder = readdir($handle)) {
			if(is_dir($folder)) { 
				$dirlist[] = $folder;
			} 
		}
		closedir($handle);
		
		asort($dirlist);
		while (list ($key, $folder) = each ($dirlist)) {
			if (($folder != "..") && ($folder != ".") && ($folder != "_vti_cnf")) {
			$subfolders .= "<option value=\"_editor.php?op=image_edit&activedir=".$activedir."/".$folder."\">".$folder."</option>";
			}
		}


	// generate files selectbox
	$z = 0; // checking if any files were found
	$subfiles = "";
	$handle=opendir('.');
	while ($file = readdir($handle)) {
		if(is_file($file)) {
			$filelist[] = $file; 
			$z++;
		}
	}
	closedir($handle);

	if($z>0) {
		asort($filelist);
		while (list ($key, $file) = each($filelist)) {
			ereg(".gif | .jpg",$file); 
				if ($file != "." && $file != ".." && (!is_dir($file))) {
					$subfiles .= "<option value=\"".$file."\">".$file."</option>";
				}
		}
	}


	
	if($HTTP_GET_VARS[w]=="edit") {
		$onload = " preData();";
		$topflag = $lang[edit_image];
		$buttons = "<input type=\"button\" name=\"apply\" value=\"".$lang[apply]."\" onClick=\"EditImage();\" style=\"width:100px; text-align: center; color: #CE0000; background-color: #F5F5F5;\">&nbsp;<input type=\"button\" name=\"insert\" value=\"".$lang[ok]."\" onClick=\"EditImage(); window.close();\" style=\"width:100px; text-align: center; color: #CE0000; background-color: #F5F5F5;\">&nbsp;<input type=\"button\" name=\"cancel\" value=\"".$lang[close]."\" onClick=\"window.close();\" style=\"width:100px; text-align: center; color: #CE0000; background-color: #F5F5F5;\">";
	} else {
		$onload =  "";
		$topflag = $lang[insert_image];
		$buttons = "<input type=\"button\" name=\"insert\" value=\"".$lang[insert_image]."\" onClick=\"insertImage();\" style=\"width:120px; font: 11px verdana; text-align: center; color: #CE0000; background-color: #F5F5F5;\">&nbsp;&nbsp;&nbsp;<input type=\"button\" name=\"cancel\" value=\"".$lang[close]."\" onClick=\"window.close();\" style=\"width:100px; text-align: center; color: #CE0000; background-color: #F5F5F5;\">";
	}
	
echo  <<<content
		<html>
			<head>
				<meta http-equiv="content-type" content="text/html;charset=$lang[charset]">
				<META http-equiv="Pragma" content="no-cache">
				<title>Bild bearbeiten</title>

				<style type="text/css">
				<!--	
				table, input, select, textarea, button { font: 11px verdana; color: $settings[textcolor1]; }
				.h {  cursor: hand; }
				a { text-decoration: none; color: $settings[textcolor3]; }
				-->
				</style>

				<SCRIPT type="text/javascript">
				<!--				
				var aspectRatioPos;
				var aspectRatioNeg;
				
				function showimage() {
					//document.images.preview.src = '$activedir/' + document.select.files.options[document.select.files.selectedIndex].value
					document.select.filename.value = document.images.preview.src
				}

				function changeFolder(form) {
					var myindex = form.subfolders.selectedIndex;
						if (form.subfolders.options[myindex].value != 0) {
							location = form.subfolders.options[myindex].value;
						}
				}

				function showDetail() {
	
					var w = open ('', 'detail', 'width=380,height=350,titlebar=0,resizable=yes,status=yes');
					var detailImage = document.images.preview.src

					var detailHTML = "";
						detailHTML += "<HTML>"
						detailHTML += "<HEAD><TITLE>Bilddetail</TITLE>"
						detailHTML += "<style>"
						detailHTML += "table { font: 11px verdana; color: #FFFFFF }"
						detailHTML += "input,button { font: 11px verdana; color: #222222; border: 0px solid #FFFFFF; }"
						detailHTML += "</style>"
						detailHTML += "<SCRIPT language=\"Javascript\">"
						detailHTML += "function fillPage() {"
						detailHTML += "document.mydetail.filewidth.value = document.images.detail.width;"
						detailHTML += "document.mydetail.fileheight.value = document.images.detail.height;"
						detailHTML += "document.mydetail.filesize.value = document.images.detail.fileSize;"
						detailHTML += "parent.window.resizeTo((document.images.detail.width+12),(document.images.detail.height+50));"
						detailHTML += "}"
						detailHTML += "<\/SCRIPT>"
						detailHTML += "</HEAD>"
						detailHTML += "<BODY onLoad=\"fillPage();\" style=\"background-image: url(" + detailImage + ");\">"
						detailHTML += "<IMG name=\"detail\" style=\"position: absolute; left: -1000; top: -1000;\">"
						detailHTML += "<FORM name=\"mydetail\" onSubmit=\"return false\">"
						detailHTML += "<TABLE name=\"show\" CELLSPACING=1 CELLPADDING=2 BORDER=0 WIDTH=\"180\" STYLE=\"border: 1px solid #FFFFFF;\">"
						detailHTML += "<TR><TD COLSPAN=2><b>$lang[detail_preview]</b></TD></TR>"
						detailHTML += "<TR><TD ALIGN=right>$lang[size]:</TD>"
						detailHTML += "<TD><input type=\"text\" name=\"filesize\" style=\"width: 48px; text-align: right;\"> $lang[bytes]</font></TD></TR>"
						detailHTML += "<TR><TD align=right>$lang[measures]:</TD>"
						detailHTML += "<TD><input type=\"text\" name=\"filewidth\" style=\"width: 32px; text-align: right\"> x <input type=\"text\" name=\"fileheight\" style=\"width: 32px;\"> px</TD></TR>"
						detailHTML += "<TR><TD colspan=2 align=center><br></TD></TR>"
						detailHTML += "<TR><TD colspan=2 align=center><input type=\"button\" name=\"close\" value=\"$lang[close_this_window]\" onClick=\"window.close();\" style=\"width:150px; text-align: center; color: #CE0000; background-color: #FFFFFF;\"></TD></TR>"
						detailHTML += "</TABLE>"
						detailHTML += "</HTML></FORM>"

						w.document.open();
						w.document.write(detailHTML);

						w.document.images.detail.src = detailImage;
		
						w.document.close();

				}
				
				function preData() {

					// aspect ratio module
					aspect.innerHTML = "<input type=\"text\" name=\"width\" size=\"2\" border=\"0\" style=\"text-align: right;\" onChange=\"watchAspect('pos');\">px / <input type=\"text\" name=\"height\" size=\"2\" border=\"0\" style=\"text-align: right;\" onChange=\"watchAspect('neg');\">px&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"aspect\" value=\"yes\" border=\"0\" onclick=\"countAspect();\"> $lang[aspect_ratio]";

					var EditSel = window.opener.textEdit.document.selection;
					if (EditSel.type == "Control") {
						var myRange = EditSel.createRange();
							if(myRange(0).tagName.toUpperCase() == "IMG") {
								var mySel = myRange(0).parentNode;
							}

					} else { 
						var mySel = EditSel.createRange().parentElement();
					}

					document.select.align.value = myRange(0).align;
					document.select.border.value = myRange(0).border;
					document.select.alt_text.value = myRange(0).alt;
					document.select.filename.value = myRange(0).src;
					document.select.width.value = myRange(0).width;
					document.select.height.value = myRange(0).height;

					if(!myRange(0).vspace) {
						document.select.vspace.value = 0;
					} else {
						document.select.vspace.value = myRange(0).vspace;
					}

					if(!myRange(0).hspace) {
					document.select.hspace.value = 0;
					} else {
						document.select.hspace.value = myRange(0).hspace;
					}
					//document.images.preview.src = myRange(0).src;
				}

				function countAspect() {
					if(document.select.aspect.checked) {
						Awidth = document.select.width.value;
						Aheight = document.select.height.value;
						aspectRatioPos = Awidth/Aheight;
						aspectRatioNeg = Aheight/Awidth;
					}
				}
				
				function watchAspect(how) {
					if(document.select.aspect.checked) {
						if(how == 'pos') {
							Awidth = document.select.width.value;
							Aheight = Math.round(Awidth/aspectRatioPos);
							document.select.height.value = Aheight;
						}
						if(how == 'neg') {
							Aheight = document.select.height.value;
							Awidth = Math.round(Aheight/aspectRatioNeg);
							document.select.width.value = Awidth;
						}
					}
				}
				
				
				function EditImage() {
					var EditDoc = window.opener.textEdit;
					var ImageSrc = document.select.filename.value;
					var ImageAlt = document.select.alt_text.value;
					var ImageAlign = document.select.align.value;
					var ImageBorder = document.select.border.value;
					var ImageHspace = document.select.hspace.value;
					var ImageVspace = document.select.vspace.value;
					var ImageWidth = document.select.width.value;
					var ImageHeight = document.select.height.value;
					
					var claimImage = EditDoc.document.selection.createRange()(0)

							if (ImageAlign != "")	{
								claimImage.align = ImageAlign;
							} else {
								claimImage.removeAttribute('align');
							}

							if (ImageBorder != "")	{
								claimImage.border = ImageBorder;
							} else {
								claimImage.removeAttribute('border');
							}

							if (ImageAlt != "")	{
								claimImage.alt = ImageAlt;
							} else {
								claimImage.removeAttribute('alt');
							}

							if (ImageHspace != "" || ImageHspace != 0)	{
								claimImage.hspace = ImageHspace;
							} else {
								claimImage.removeAttribute('hspace');
							}

							if (ImageVspace != "" || ImageVspace != 0)	{
								claimImage.vspace = ImageVspace;
							} else {
								claimImage.removeAttribute('vspace');
							}

							if (ImageWidth != "")	{
								claimImage.width = ImageWidth;
							} else {
								claimImage.removeAttribute('width');
							}

							if (ImageHeight != "")	{
								claimImage.height = ImageHeight;
							} else {
								claimImage.removeAttribute('height');
							}

				}

				function insertImage() {
					var EditDoc = window.opener.textEdit.document;
					window.opener.textEdit.focus()
					
					var subM = document.select;
					if (window.opener.format=="HTML") {
						var imgTag = '<img src="' + subM.filename.value + '" border="' + subM.border.value + '" align="' + subM.align.value + '" '

						if(subM.alt_text.value != "") {
							imgTag += 'alt= "' + subM.alt_text.value + '" '
						}

						if(subM.vspace.value != "") {
							imgTag += 'vspace = "' + subM.vspace.value + '" '
						}

						if(subM.hspace.value != "") {
							imgTag += 'hspace= "' + subM.hspace.value + '" '
						}

						if(subM.width.value != "") {
							imgTag += 'width= "' + subM.width.value + '" '
						}

						if(subM.height.value != "") {
							imgTag += 'height= "' + subM.height.value + '" '
						}

						imgTag += '>'
						EditDoc.selection.createRange().pasteHTML(imgTag)
					} else alert("$lang[alert_wysiwyg_image]")
				}


				function newImage() {
					location = '_editor.php?op=image_edit';
				}
		
				function editImage() {
					location = '_editor.php?op=image_edit&w=edit';
				}

				function uploadImage() {
					location = '_editor.php?op=image_upload';
				}
				
				function setHeight() {
					parent.window.resizeTo(520,444);
				}
				
				function mediapool() {
  var winWidth = 700;
  var winHeight = 440;
  var w = (screen.width - winWidth)/2;
  var h = (screen.height - winHeight)/2 - 60;
  var url = '../mediapool.php?typ=bild&target=filename&d4sess=';
  var name = 'mpool';
  var features = 'scrollbars=no,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
  window.open(url,name,features);
}
			-->
			</SCRIPT>	
		</head>
		<body bgcolor="#ffffff" topmargin=3 leftmargin=3 onLoad="setHeight(); $onload">
		<form name="select" onSubmit="return false" style="margin: 0px;">
			<table width="100%" border="0" cellspacing="0" cellpadding="5" style="border: 1px solid $settings[border_color];">
				<tr>
					<td align="center" style="border-right: 1px solid $settings[border_color]; font-weight: bold; color: $settings[textcolor3]; ">$topflag</td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-bottom: 1px solid $settings[border_color];"><a href="javascript:window.close();" style="color: $settings[textcolor2];font-weight: bold;">$lang[close]</a></td>
				</tr>
				<tr>
					<td colspan="3">
						<table width="100%" border="0" cellspacing="1" cellpadding="3">
							<tr>
								<td width="23%">$lang[filename]</td>
								<td><input type="text" name="filename" style="width: 280px;" size="64"> <input type="button" value="Media-Pool" onClick="mediapool();" style="width:110px; font: 11px verdana; text-align: center; color: #CE0000; background-color: #F5F5F5;">
								<input type="hidden" name="path" value="$activedir"></td>
							</tr>
							<tr>
								<td width="23%">$lang[image_alt]</td>
								<td><input type="text" name="alt_text" style="width: 400px;" size="64"></td>
							</tr>
							<tr>
								<td width="23%">$lang[align]</td>
								<td>	<input type="text" name="align" style="width: 100px;">
										<select type="text" name="sel_align" style="width: 100px;" onChange="align.value = sel_align[sel_align.selectedIndex].value; sel_align.value = '';">
										<option value=""></option>
										<option value="">$lang[default]</option>
										<option value="top">$lang[top]</option>
										<option value="middle">$lang[middle]</option>
										<option value="bottom">$lang[bottom]</option>
										<option value="left">$lang[left]</option>
										<option value="right">$lang[right]</option>
										<option value="texttop">$lang[texttop]</option>
										<option value="absmiddle">$lang[absmiddle]</option>
										<option value="baseline">$lang[baseline]</option>
										<option value="absbottom">$lang[absbottom]</option>
										</select></td>
							</tr>
							<tr>
								<td width="23%">$lang[border]</td>
								<td><input type="text" name="border" value="0" size="2" border="0" style="text-align: right;">px</td>
							</tr>
							<tr>
								<td width="23%">$lang[vspace] / $lang[hspace]</td>
								<td><input type="text" name="vspace" size="2" border="0" style="text-align: right;">px / <input type="text" name="hspace" size="2" border="0" style="text-align: right;">px</td>
							</tr>
							<tr id="dimensions">
								<td width="23%">$lang[width] / $lang[height]</td>
								<td>
									<div id="aspect"><input type="text" name="width" size="2" border="0" style="text-align: right;">px / <input type="text" name="height" size="2" border="0" style="text-align: right;">px</div>
								</td>
							</tr>
							<tr>
								<td width="23%">&nbsp;</td>
								<td>$buttons</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
content;

	break;



##############################################################################
# image uploader case                                                        #
##############################################################################

	case "image_upload":
	global $HTTP_POST_VARS, $HTTP_POST_FILES;	
	
	$activedir = $settings[images_root];

		if(!$HTTP_POST_VARS[submit]) {
			$showform = 1;
		}

		if($HTTP_POST_VARS[submit]) {

			if($HTTP_POST_VARS[subfolder] == "") {
				$newimg = "".$activedir."/".$HTTP_POST_FILES[img][name]."";
			} else {
				$newimg = "".$activedir."/".$HTTP_POST_VARS[subfolder]."/".$HTTP_POST_FILES[img][name]."";
			}
	
			//image selection check
			if ($HTTP_POST_FILES[img][name] == "") {
				$error = 1;
				$errormes .= "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\"><b>".$lang['error'].":</b> ".$lang['alert_no_image']."&nbsp;</font>";
			}
	
			// image size check
			if ($HTTP_POST_FILES[img][size] > $settings[max_img_size]) {
				$error = 1;
				$errormes .= "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\"><b>".$lang['error'].":</b> ".$lang['alert_file_oversize']."&nbsp;</font>";
			}

			// image file type check against image file type array from config file
			$z = "stop";
			foreach($settings[img_file_types] as $filetype) {
				if($HTTP_POST_FILES[img][type] == $filetype) {
					$z = "go";
				}
			}

			if ($z == "go") {

				//checks if file exists
				if (file_exists($newimg) != FALSE ) {
					$error = 1;
					$errormes .= "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\"><b>".$lang['error'].":</b> ".$lang['alert_file_exists']."&nbsp;</font>";
				}

				if (!copy($HTTP_POST_FILES[img][tmp_name], $newimg) != FALSE )  {
					$error = 1;
					$errormes .= "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\"><b>".$lang['error'].":</b> ".$lang['alert_no_dir']."&nbsp;</font>";
				}
			} else {
				$error = 1;
				$errormes .= "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\"><b>".$lang['error'].":</b> ".$lang['alert_file_unaproved']." [<a href=\"javascript:void\" title=\"".$lang['sent_info']." (".$HTTP_POST_FILES[img][type].") format!\">?</a>]&nbsp;</font>";
			}

		}

		if($HTTP_GET_VARS[show] == "newfolder") {
			$showform = 0;
			$error = 0;
			$folder = 1;
		}

		if(isset($HTTP_POST_VARS[addfolder])) {
			if($HTTP_POST_VARS[foldername] != "") {
				@chdir($activedir);
				$dirName = $HTTP_POST_VARS[foldername];

				if(file_exists($dirName) == FALSE ) {
					mkdir($dirName, 0777);
					$status = "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\">".$lang['folder']." <b>".$dirName."</b> ".$lang['created_success'].".<br></font>";
					$folder = 0;
				} else {
					$folder = 1;
					$status = "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\"><b>".$lang['error'].":</b> ".$lang['alert_no_folder']."<br></font>";
				}
			} else {
				$folder = 1;
				$status = "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\"><b>".$lang['error'].":</b> ".$lang['no_folder']." <b>".$dirName."</b> ".$lang['no_folder_desc'].".<br></font>";
			}
		
		}


echo  <<<content
		<html>
			<head>
			<META http-equiv="Pragma" content="no-cache"> 
			<title>$lang[upload_new_image]</title>
			<style type="text/css">
			<!--	
				table, input, select, textarea, button { font: 11px verdana; color: $settings[textcolor1]; }
				.h {  cursor: hand; }
				a { text-decoration: none; color: $settings[textcolor3]; }
			-->
			</style>
			<script language="Javascript">
				function newImage() {
					location = '_editor.php?op=image_upload';
				}
		
				function editImage() {
					location = '_editor.php?op=image_edit';
				}

				function insertImage() {
					location = '_editor.php?op=image_edit';
				}				
			
				function setHeight() {
					parent.window.resizeTo(520,180);
				}
			</script>
			</head>

			<body leftmargin=3 topmargin=3 bgcolor="#ffffff" OnLoad="setHeight();">
				<center>
					<form name="uploader" method="POST" action="_editor.php?op=image_upload" enctype="multipart/form-data">
					<table width="100%" border="0" cellspacing="0" cellpadding="5" style="border: 1px solid $settings[border_color];">
						<tr>
							<td align="center" bgcolor="$settings[bgcolor]" style="border-right: 1px solid $settings[border_color]; border-bottom: 1px solid $settings[border_color];"><a href="javascript:insertImage()" style="color: $settings[textcolor2]; font-weight: bold;">$lang[insert_edit_image]</a></td>
							<td align="center" style="border-right: 1px solid $settings[border_color];"><font style="font-weight: bold; color: $settings[textcolor3];">$lang[upload_new_image]</font></td>
							<td align="center" bgcolor="$settings[bgcolor]" style="border-bottom: 1px solid $settings[border_color];"><a href="javascript:window.close();" style="color: $settings[textcolor2]; font-weight: bold;">$lang[close]</a></td>
				</tr>
				<tr>
					<td colspan="4">
content;


	if(isset($HTTP_POST_VARS[submit])) {
		if($error == 1) {
			echo "<br>".$errormes;
		} else {
			echo "<br><p align=center><font style=\"color: ".$settings[textcolor1].";\">".$lang['file']." <b>".$HTTP_POST_FILES[img][name]."</b> ".$lang['upload_success'].".<br><br></font><input type=\"submit\" name=\"continue\" value=\"".$lang['continue']."\" style=\"width:90px; text-align: center; color: ".$settings[textcolor3]."; background-color: #FFFFFF;\" onClick=\"insertImage()\"><br></p>";
		}
	} 

	if(($error == 1) || ($showform == 1)) {
		echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">";
		echo "<tr><td width=\"23%\">".$lang['upload_to_folder']."</td>";
		echo "<td><select name=\"subfolder\" size=\"1\" style=\"width=200px;\">";
		echo "<option value=\"\">./</option>";

		@chdir($activedir);
		$handle=opendir('.');
			while ($folder = readdir($handle)) {
				if(is_dir($folder)) { 
					$dirlist[] = $folder;
				} 
			}
		closedir($handle);
		
		asort($dirlist);
			while (list ($key, $folder) = each ($dirlist)) {
				if ($folder != ".." && $folder != ".") {
					echo "<option value=\"".$folder."\">".$folder."</option>";
				}
			}

		echo "</select> <input type=\"button\" name=\"new_folder\" value=\"".$lang['create_new_dir']."\" style=\"width:130px; text-align: center; background-color: #F5F5F5;\" onClick=\"location = '_editor.php?op=image_upload&show=newfolder';\"></td>";
		echo "</tr><tr><td width=\"23%\">".$lang['image']."</td>";
		echo "<td><input style=\"width:268px;\" type=\"file\" name=\"img\" title=\"".$lang['images_only']."\"></td>";
		echo "</tr><tr><td width=\"23%\">&nbsp;</td>";
		echo "<td><input type=\"submit\" name=\"submit\" value=\"".$lang['submit_file']."\" style=\"width:120px; text-align: center; color: #ce0000; background-color: #f5f5f5;\">&nbsp;&nbsp;&nbsp;<input type=\"button\" name=\"cancel\" value=\"".$lang['cancel']."\" style=\"width:80px; text-align: center; background-color: #F5F5F5;\" onClick=\"location = '_editor.php?op=image_edit';\"></td>";
		echo "</tr></table>";
	}

	if(isset($HTTP_POST_VARS[addfolder])) {
		echo $status;
	}
	
	if($folder == 1) {
		echo "<table cellspacing=2 cellpadding=0 border=0 width=\"100%\">";
		echo "<tr><td align=right><font style=\"font: 11.9px verdana; color: ".$settings[textcolor1].";\">".$lang['new_folder_name'].":</font></td>";
		echo "<td><input style=\"width:200px;\" type=\"text\" name=\"foldername\" title=\"".$lang['note_aut_chmod']."\"></td></tr>";
		echo "<tr><td>&nbsp;</td><td>";
		echo "<button style=\"width:100px; text-align: center; color: #CE0000; background-color: #F5F5F5;\" name=\"addfolder\" type=\"submit\">".$lang['create_folder']."</button>&nbsp;&nbsp;&nbsp;";
		echo "<input type=\"submit\" name=\"cancel\" value=\"".$lang['cancel']."\" style=\"width:80px; text-align: center; background-color: #F5F5F5;\" onClick=\"insertImage()\">&nbsp;</td>";
		echo "</tr></table>";
	}

	echo  <<<content
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>
content;
	
	break;

##############################################################################
# link select case                                                           #
##############################################################################

	case "link_select":
echo  <<<content
		<html>
			<head>
				<meta http-equiv="content-type" content="text/html;charset=$lang[charset]">
				<META http-equiv="Pragma" content="no-cache">
				<title>$lang[create_edit_link]</title>

		<style type="text/css">
			<!--	
			.h {  cursor: hand; }
			INPUT,SELECT,TEXTAREA,BUTTON,TD { color: $settings[textcolor1]; font-size: 11.9px; font-family: Verdana }
			.tab_button { width:160px; text-align: center; color: $settings[textcolor1]; background-color: $settings[bgcolor2] }
			a { color: $settings[textcolor2]; text-decoration: none; text-weight: bold; }
			-->
		</style>

		<SCRIPT type="text/javascript">
		<!--
		var editorUrl = new String (document.location.href);
		var lastpos = editorUrl.lastIndexOf("/");
		var abs_url = editorUrl.substring(0,lastpos);
		
		function getFilename() {
			document.select.link_url.value = '$activedir/' + document.select.files.options[document.select.files.selectedIndex].value
		}

		function changeFolder(form) {
			var myindex = form.subfolders.selectedIndex;
			if (form.subfolders.options[myindex].value != 0) {
				location = form.subfolders.options[myindex].value;
			}
		}

		function preselectLink(method) {
			var LinkDoc = window.opener.textEdit.document
			if (LinkDoc.selection.type == "Control") {
				var cRange = LinkDoc.selection.createRange();
					if (cRange(0).tagName.toUpperCase() == "IMG") {
						var preSel = cRange(0).parentNode;
					}
			} else {
				var preSel = LinkDoc.selection.createRange().parentElement();
			}

			if (preSel.tagName.toUpperCase() == "A") {
				var linkURLtemp = preSel.href;
					if(method == '1') linkURLtemp = linkURLtemp.replace(new RegExp(abs_url,'g'),'.');
				document.select.link_url.value = linkURLtemp;
				document.select.upload.value = "$lang[apply]";
				
				if(preSel.target != "") {
				document.select.link_target.value = preSel.target
				}
				
				if(preSel.title != "") {
				document.select.link_alt.value = preSel.title
				}
			}
		}


		function CreateLink() {
			var LinkDoc = window.opener.textEdit.document
			var LinkTarget = document.select.link_target.value;
			var LinkUrl = document.select.link_url.value;
			var LinkAlt = document.select.link_alt.value;
			var FullUrl;

			if (LinkUrl != "") {
			
				var NewLink = LinkDoc.createElement('<A>')
				var teSel = LinkDoc.selection.createRange()
		
				if(LinkDoc.selection.createRange().text != "") {
					teSel.execCommand("CreateLink",false,LinkUrl);
				
					if (LinkDoc.selection.type == "Control") {
						claimLink = teSel(0).parentNode;
					} else {
						claimLink = teSel.parentElement();
					}
				
					if (LinkTarget != "")	{
						claimLink.target = LinkTarget;
					} else {
						claimLink.removeAttribute('target');
					}

					if (LinkAlt != "")	{
						claimLink.title = LinkAlt;
					} else {
						claimLink.removeAttribute('title');
					}
				} else {
					alert("$lang[alert_selection]")
				}
			} else {
				alert("$lang[alert_url]")
				document.select.link_url.focus()
			}
		}

		function newFile() {
			location = '_editor.php?op=file_upload&method=$HTTP_GET_VARS[method]';
		}

		function setHeight() {
			parent.window.resizeTo(500,210);
		}
		</script>	
		
	</head>

	<body bgcolor="#ffffff" topmargin=3 leftmargin=3 onLoad="preselectLink('$HTTP_GET_VARS[method]'); setHeight(); focus();">
		<form name="select" onSubmit="return false" style="margin: 0px;">
			<table width="100%" border="0" cellspacing="0" cellpadding="5"style="border: 1px solid $settings[border_color];">
				<tr>
					<td align="center" bgcolor="#FFFFFF" style="border-right: 1px solid $settings[border_color];"><font style="font-weight: bold; color: $settings[textcolor3];">$lang[normal_link]</td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-right: 1px solid $settings[border_color]; border-bottom: 1px solid $settings[border_color];"><a href="_editor.php?op=link_file&method=$HTTP_GET_VARS[method]" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[link_to_file]</a></td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-right: 1px solid $settings[border_color]; border-bottom: 1px solid $settings[border_color];"><a href="_editor.php?op=file_upload&method=$HTTP_GET_VARS[method]" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[upload_file]</a></td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-bottom: 1px solid $settings[border_color];"><a href="javascript:window.close();" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[close]</a></td>
				</tr>
				<tr>
					<td colspan="4">
						<table width="100%" border="0" cellspacing="1" cellpadding="3">
							<tr>
								<td width="23%">$lang[link_url]</td>
								<td><input type="text" name="link_url" style="width: 270px; text-align: left;" size="40"></td>
							</tr>
							<tr>
								<td width="23%">$lang[link_alt]</td>
								<td><input type="text" name="link_alt" style="width: 270px; text-align: left;"></td>
							</tr>
							<tr>
								<td width="23%">$lang[link_target]</td>
								<td><input type="text" name="link_target" style="width: 80px; text-align: left;">
								<select name="targets" size="1" style="width=70px;" onChange="link_target.value = targets[targets.selectedIndex].value; targets.value = '';">
										<option value=""></option>
										<option value="">None</option>
										<option value="_blank">_blank</option>
										<option value="_parent">_parent</option>
										<option value="_self">_self</option>
										<option value="_top">_top</option>
									</select></td>
							</tr>
							<tr>
								<td width="23%"></td>
								<td><input type="button" name="upload" value="$lang[create_link]" style="width:100px; font: 11.9px verdana; text-align: center; color: #ce0000; background-color: #f5f5f5;" OnClick="CreateLink(); window.close();"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
		</body>
	</html>
content;

break;	


##############################################################################
# link to file case                                                          #
##############################################################################

	case "link_file":
	global $HTTP_POST_VARS, $HTTP_POST_FILES;	

$activedir = $HTTP_GET_VARS[activedir];
	
	if (!IsSet($activedir)) { 
		$activedir_show = $activedir;
		$activedir = $settings[files_root];
		} else {
		$activedir_show = $activedir;
		$activedir = $settings[files_root]."/".$activedir."";
		
	}

echo  <<<content
		<html>
			<head>
				<meta http-equiv="content-type" content="text/html;charset=$lang[charset]">
				<META http-equiv="Pragma" content="no-cache">
				<title>$lang[create_edit_link]</title>

		<style type="text/css">
			<!--	
			.h {  cursor: hand; }
			INPUT,SELECT,TEXTAREA,BUTTON,TD { color: $settings[textcolor1]; font-size: 11.9px; font-family: Verdana }
			.tab_button { width:160px; text-align: center; color: $settings[textcolor1]; background-color: $settings[bgcolor2] }
			a { color: $settings[textcolor3]; text-decoration: none; text-weight: bold; }
			-->
		</style>

		<SCRIPT type="text/javascript">
		<!--
		var editorUrl = new String (document.location.href);
		var lastpos = editorUrl.lastIndexOf("/");
		var abs_url = editorUrl.substring(0,lastpos);
		
		function getFilename() {
			document.select.link_url.value = '$activedir/' + document.select.files.options[document.select.files.selectedIndex].value
		}

		function changeFolder(form) {
			var myindex = form.subfolders.selectedIndex;
			if (form.subfolders.options[myindex].value != 0) {
				location = form.subfolders.options[myindex].value;
			}
		}

		function preselectLink(method) {
			var LinkDoc = window.opener.textEdit.document
			if (LinkDoc.selection.type == "Control") {
				var cRange = LinkDoc.selection.createRange();
					if (cRange(0).tagName.toUpperCase() == "IMG") {
						var preSel = cRange(0).parentNode;
					}
			} else {
				preSel = LinkDoc.selection.createRange().parentElement();
			}

			if (preSel.tagName.toUpperCase() == "A") {
				var linkURLtemp = preSel.href;
					if(method == '1') linkURLtemp = linkURLtemp.replace(new RegExp(abs_url,'g'),'.');
				document.select.link_url.value = linkURLtemp;
				document.select.upload.value = "$lang[apply]";
				
				if(preSel.target != "") {
				document.select.link_target.value = preSel.target
				}
				
				if(preSel.title != "") {
				document.select.link_alt.value = preSel.title
				}
			}
		}


		function CreateLink() {
			var LinkDoc = window.opener.textEdit.document
			var LinkTarget = document.select.link_target.value;
			var LinkUrl = document.select.link_url.value;
			var LinkAlt = document.select.link_alt.value;
			var FullUrl;

			if (LinkUrl != "") {
			
				var NewLink = LinkDoc.createElement('<A>')
				var teSel = LinkDoc.selection.createRange()
		
				if(LinkDoc.selection.createRange().text != "") {
					teSel.execCommand("CreateLink",false,LinkUrl);
				
					if (LinkDoc.selection.type == "Control") {
						claimLink = teSel(0).parentNode;
					} else {
						claimLink = teSel.parentElement();
					}
				
					if (LinkTarget != "")	{
						claimLink.target = LinkTarget;
					} else {
						claimLink.removeAttribute('target');
					}

					if (LinkAlt != "")	{
						claimLink.title = LinkAlt;
					} else {
						claimLink.removeAttribute('title');
					}
				} else {
					alert("$lang[alert_selection]")
				}
			} else {
				alert("$lang[alert_url]")
				document.select.link_url.focus()
			}
		}

		function newFile() {
			location = '_editor.php?op=file_upload&method=$HTTP_GET_VARS[method]';
		}
		
		function setHeight() {
			parent.window.resizeTo(500,350);
		}
		</script>	
	</head>

	<body bgcolor="#ffffff" topmargin=3 leftmargin=3 onLoad="preselectLink('$HTTP_GET_VARS[method]'); setHeight(); focus();">
		<form name="select" onSubmit="return false" style="margin: 0px;">
			<table width="100%" border="0" cellspacing="0" cellpadding="5" style="border: 1px solid $settings[border_color];">
				<tr>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-right: 1px solid $settings[border_color]; border-bottom: 1px solid $settings[border_color];"><a href="_editor.php?op=link_select&method=$HTTP_GET_VARS[method]" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[normal_link]</font></a></td>
					<td align="center" bgcolor="#FFFFFF" style="border-right: 1px solid $settings[border_color]"><font style="font-weight: bold; color: $settings[textcolor3];">$lang[link_to_file]</font></td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-right: 1px solid $settings[border_color]; border-bottom: 1px solid $settings[border_color];"><a href="_editor.php?op=file_upload&method=$HTTP_GET_VARS[method]" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[upload_file]</font></a></td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-bottom: 1px solid $settings[border_color];"><a href="javascript:window.close();" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[close]</font></a></td>
				</tr>
				<tr>
					<td colspan="4">
						<table width="100%" border="0" cellspacing="1" cellpadding="3">
							<tr>
								<td colspan="2" bgcolor="#FFF3BD"><font style="padding: 3px;">$lang[current_folder]: <a href="_editor.php?op=link_file">$settings[files_root]/</a><a href="_editor.php?op=link_file&activedir=$activedir_show">$activedir_show</a></font></td>
							</tr>
							<tr>
								<td width="23%">$lang[locate_file]</td>
								<td> 
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td>$lang[subfolders]<br>
												<select name="subfolders" size="7" style="width=120px;" onChange="changeFolder(this.form)">
content;

	@chdir($activedir);
	$handle=opendir('.');
		while ($folder = readdir($handle)) {
			if(is_dir($folder)) { 
				$dirlist[] = $folder;
			} 
		}
		closedir($handle);
		
		asort($dirlist);
		while (list ($key, $folder) = each ($dirlist)) {
			if ($folder != ".." && $folder != ".") {
				echo "<option value=\"_editor.php?op=link_file&activedir=".$folder."\">".$folder."</option>";
			}
		}


echo  <<<content
												</select></td>
											<td width="20"></td>
											<td>$lang[files]:<br>
												<select name="files" size="7" style="width=120px;" onChange="getFilename();">
content;

	$handle=opendir('.');
		while ($file = readdir($handle)) {
			if(is_file($file)) {
				$filelist[] = $file; 
			}
		}
		closedir($handle);

		// get ereg condition from config file
		$build_ereg = "\"";
		$nn = 0;
		$n = count($settings[mime_file_ext]);
		foreach($settings[mime_file_ext] as $exttype) {
			$nn++;
			$build_ereg .= "($img_ext == ".$exttype.")";
				if($nn < $n) {
					$build_ereg .= " | ";
				}
		$build_ereg = "\"";
		}

		asort($filelist);
		while (list ($key, $file) = each($filelist)) {
			ereg($build_ereg,$file); 
				if ($file != "." && $file != ".." && (!is_dir($file))) {
					echo "<option value=\"".$file."\">".$file."</option>";
				}
		}

echo  <<<content
												</select></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="23%">$lang[link_url]</td>
								<td><input type="text" name="link_url" style="width: 270px; text-align: left;"></td>
							</tr>
							<tr>
								<td width="23%">$lang[link_alt]</td>
								<td><input type="text" name="link_alt" style="width: 270px; text-align: left;"></td>
							</tr>
							<tr>
								<td width="23%">$lang[link_target]</td>
								<td><input type="text" name="link_target" style="width: 80px; text-align: left;"> <select name="targets" size="1" style="width=70px;" onchange="link_target.value = targets[targets.selectedIndex].value; targets.value = '';">
										<option value=""></option>
										<option value="">None</option>
										<option value="_blank">_blank</option>
										<option value="_parent">_parent</option>
										<option value="_self">_self</option>
										<option value="_top">_top</option>
									</select></td>
							</tr>
							<tr>
								<td width="23%"></td>
								<td><input type="button" name="upload" value="$lang[create_link]" style="width:100px; font: 11.9px verdana; text-align: center; color: #ce0000; background-color: #f5f5f5;" onclick="CreateLink(); window.close();"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>

content;

break;	


##############################################################################
# file upload case                                                           #
##############################################################################

	case "file_upload":
		global $HTTP_POST_VARS,$HTTP_POST_FILES;

		$factivedir = $settings[files_root];

		if(!isset($HTTP_POST_VARS[submit])) {
			$showform = 1;
		}

		if(isset($HTTP_POST_VARS[submit])) {

			if($subfolder=="") {
				$newimg = "".$factivedir."/".$HTTP_POST_FILES[filename][name]."";
			} else {
				$newimg = "".$factivedir."/".$subfolder."/".$HTTP_POST_FILES[filename][name]."";
			}

			//file selection check
			if ($HTTP_POST_FILES[filename][name] == "") {
				$error = 1;
				$errormes .= "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\"><b>".$lang['error'].":</b> ".$lang['alert_no_image']."&nbsp;</font>";
			}
	
			// file size check
			if ($HTTP_POST_FILES[filename][size] > $settings[max_file_size]) {
				$error = 1;
				$errormes .= "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\"><b>".$lang['error'].":</b> ".$lang['alert_file_oversize']."&nbsp;</font>";
			}

			// image file type check against image file type array from config file
			$build_condition = "(";
			$filename2check = $HTTP_POST_FILES[filename][name];
			$filename_ext = strrchr($filename2check,".");
			
			$z = 0;
			$n = (count($settings[mime_file_ext])-1);
			foreach($settings[mime_file_ext] as $mimeext) {
				if($mimeext != $filename_ext) {
					$z++;
				}
			}

			if ($z == $n) {
				//checks if file exists
				if (file_exists("$newimg") != FALSE ) {
					$error = 1;
					$errormes .= "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\"><b>".$lang['error'].":</b> ".$lang['alert_file_exists']."&nbsp;</font>";
				}

				if (!copy($HTTP_POST_FILES[filename][tmp_name], $newimg) != FALSE )  {
					$error = 1;
					$errormes .= "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\"><b>".$lang['error'].":</b> ".$lang['alert_no_dir']."&nbsp;</font>";
				}
			} else {
				$error = 1;
				$errormes .= "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\"><b>".$lang['error'].":</b> ".$lang['alert_file_unaproved']." [<a href=\"javascript:void\" title=\"".$lang['sent_info']." (".$filename_ext.") format!\">?</a>]&nbsp;</font>";
			}

		}

		if($HTTP_GET_VARS[show] == "newfolder") {
			$showform = 0;
			$error = 0;
			$folder = 1;
		}

		if(isset($HTTP_POST_VARS[addfolder])) {
			if($HTTP_POST_VARS[foldername] != "") {
				@chdir($factivedir);
				$dirName = $HTTP_POST_VARS[foldername];

				if(file_exists($dirName) == FALSE ) {
					mkdir($dirName, 0777);
					$status = "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\">".$lang['folder']." <b>".$dirName."</b> ".$lang['created_success'].".<br></font>";
					$folder = 0;
				} else {
					$folder = 1;
					$status = "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\"><b>".$lang['error'].":</b> ".$lang['alert_no_folder']."<br></font>";
				}
			} else {
				$folder = 1;
				$status = "<font style=\"font: 11.9px verdana; color: ".$settings[textcolor3].";\"><b>".$lang['error'].":</b> ".$lang['no_folder']." <b>".$dirName."</b> ".$lang['no_folder_desc'].".<br></font>";
			}
		
		}


echo  <<<content
	<html>
		<head>
			<meta http-equiv="content-type" content="text/html;charset=$lang[charset]">
			<META http-equiv="Pragma" content="no-cache">
			<title>$lang[upload_file]</title>
	<style type="text/css">
			<!--	
			.h {  cursor: hand; }
			INPUT,SELECT,TEXTAREA,BUTTON,TD { color: $settings[textcolor1]; font-size: 11.9px; font-family: Verdana }
			.tab_button { width:160px; text-align: center; color: $settings[textcolor1]; background-color: $settings[bgcolor2] }
			a { color: $settings[textcolor3]; text-decoration: none; text-weight: bold; }
			-->
		</style>
		<script language="Javascript">	
			function setHeight() {
				parent.window.resizeTo(500,210);
			}
		</script>	
		</head>

		<body topmargin=3 leftmargin=3 bgcolor="#ffffff" OnLoad="setHeight();">
			<center>
				<form name="uploader" method="POST" action="_editor.php?op=file_upload" enctype="multipart/form-data">
			<table width="100%" border="0" cellspacing="0" cellpadding="5" style="border: 1px solid $settings[border_color];">
				<tr>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-right: 1px solid $settings[border_color]; border-bottom: 1px solid $settings[border_color];"><a href="_editor.php?op=link_select&method=$HTTP_GET_VARS[method]" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[normal_link]</font></a></td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-right: 1px solid $settings[border_color]; border-bottom: 1px solid $settings[border_color];"><a href="_editor.php?op=link_file&method=$HTTP_GET_VARS[method]" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[link_to_file]</font></a></td>
					<td align="center" bgcolor="#FFFFFF" style="border-right: 1px solid $settings[border_color];"><font style="font-weight: bold; color: $settings[textcolor3];">$lang[upload_file]</font></td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-bottom: 1px solid $settings[border_color];"><a href="javascript:window.close();" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[close]</font></a></td>
				</tr>
				<tr>
					<td colspan="4">
content;

	if(isset($HTTP_POST_VARS[submit])) {
		if($error == 1) {
		echo $errormes;
		} else {
		echo "<p align=center><br><font style=\"font: 11.9px verdana; color: ".$settings[textcolor1].";\">".$lang['file']." <b><font color=\"CE0000\">".$HTTP_POST_FILES[filename][name]."</font></b> ".$lang['upload_success'].".<br><br></font><input type=\"button\" name=\"continue\" value=\"".$lang['continue']."\" style=\"width:100px; font: 11.9px verdana; text-align: center; color: #CE0000; background-color: #F5F5F5;\" onClick=\"location = '_editor.php?op=link_select';\"><br></p>";
		}
	} 

	if(($error == 1) || ($showform == 1)) {
		echo "<table cellspacing=1 cellpadding=4 border=0 width=\"100%\">";
		echo "<tr><td colspan=2 bgcolor=#FFFFFF><b>".$lang['upload_file']."</b>";
		
		if(isset($HTTP_POST_VARS[addfolder])) {
			echo "&nbsp;-&nbsp;".$status;
		}
		
		echo"</td></tr>";
		echo "<tr><td>".$lang['folder'].":&nbsp;</a></td>";
		echo "<td><select name=\"subfolder\" size=\"1\" style=\"width=120px;\">";
		echo "<option value=\"\">./</option>";

		@chdir($factivedir);
		$handle=opendir('.');
			while ($folder = readdir($handle)) {
				if(is_dir($folder)) { 
					$dirlist[] = $folder;
				} 
			}
		closedir($handle);
		
		asort($dirlist);
			while (list ($key, $folder) = each ($dirlist)) {
				if ($folder != ".." && $folder != ".") {
					echo "<option value=\"".$folder."\">".$folder."</option>";
				}
			}

		echo "</select>&nbsp;&nbsp;<input type=\"button\" name=\"new_folder\" value=\"".$lang['create_new_folder']."\" style=\"width:130px; font: 11px verdana; text-align: center; color: #000000; background-color: #F5F5F5;\" onClick=\"location = '_editor.php?op=file_upload&show=newfolder';\"></td></tr>";		
		echo "<tr><td>".$lang['file'].":&nbsp</td>";
		echo "<td><input style=\"width:240px;\" type=\"file\" name=\"filename\">";
		echo "</td>";
		echo "</tr>";
		echo "<tr><td>&nbsp;</td>";
		echo "<td><input type=\"submit\" name=\"submit\" value=\"".$lang['submit_file']."\" style=\"width:100px; font: 11px verdana; text-align: center; color: #CE0000; background-color: #F5F5F5;\">&nbsp;&nbsp;";
		echo "<input type=\"button\" name=\"cancel\" value=\"".$lang['cancel']."\" style=\"width:80px; font: 11px verdana; text-align: center; color: #222222; background-color: #F5F5F5;\" onClick=\"location = '_editor.php?op=link_select';\">";
		echo "</td></tr>";
		echo "</table>";
	}

	
	if($folder == 1) {
		echo "<table cellspacing=1 cellpadding=3 border=0 width=\"100%\">";
		echo "<tr><td colspan=2 bgcolor=#FFFFFF><b>".$lang['create_folder']."</b></td></tr>";
		echo "<tr><td align=right>".$lang['new_folder_name'].":</td>";
		echo "<td><input style=\"width:200px;\" type=\"text\" name=\"foldername\" title=\"".$lang['note_aut_chmod']."\"></td></tr>";
		echo "<tr><td>&nbsp;</td><td><input type=\"submit\" style=\"width:120px; font: 11px verdana; text-align: center; color: #CE0000; background-color: #F5F5F5;\" name=\"addfolder\" value=\"".$lang['create_folder']."\">&nbsp;&nbsp;";
		echo "<input type=\"button\" name=\"cancel\" value=\"".$lang['cancel']."\" style=\"width:80px; font: 11px verdana; text-align: center; color: #222222; background-color: #F5F5F5;\" onClick=\"location = '_editor.php?op=link_select';\">&nbsp;</td>";
		echo "</tr></table>";
	}

echo  <<<content
						</td>
					</tr>
				</table>
			</form>
		</body>
	</html>
content;
break;	

##############################################################################
# color picker case                                                          #
##############################################################################

	case "color_picker":

		if(!isset($which)) { $which = $HTTP_GET_VARS[$which]; }
		if($which=="fg") { 
			$display = $lang['foreground_color'];
			$function = "ChangeCol('Forecolor')";
			$order = "Forecolor";
		} else {
			$display =  $lang['background_color'];
			$function = "ChangeCol('Backcolor')";
			$order = "Backcolor";
		}

echo  <<<content
	<html>
		<head>
			<META http-equiv="Pragma" content="no-cache">
			<style type="text/css">
			<!--	
				body { background-color: #FFFFFF; color: #000000; }
				.h {  cursor: hand; }
				.framed { border: 1px solid black; background-color: #FFF3BD;	}
				.framed2 { border: 1px solid #444444; cursor: hand;	}
			-->
			</style>
		</head>
		<body bgcolor="#ffffff" topmargin=3 leftmargin=3 OnUnload="FavCookie();" OnLoad="FavGetCookie()">

			<script LANGUAGE="JavaScript">
			<!--
				function PickCol(colorString){
					picker.bgColor= colorString;
					document.all.colview.value = colorString;
					ChangeCol('$order');
				}

				function ChangeCol(whichColor) {
					var fgString = document.all.colview.value 
					parent.doFormat(whichColor,fgString);
				}
	
				function CustCol() {
					var CustString = document.all.colview.value
					picker.bgColor = CustString;
				}
	
				function ColToFav() {
					var CustString = document.all.colview.value
					picker.bgColor = CustString
					fav5.bgColor = fav4.bgColor
					fav4.bgColor = fav3.bgColor
					fav3.bgColor = fav2.bgColor
					fav2.bgColor = fav1.bgColor
					fav1.bgColor = CustString
				}	
	
				function FavChange(which) {
					var SwitchToFav = eval("fav"+which+".bgColor");
					SwitchToFav = SwitchToFav.substring(1,7)
					document.all.colview.value = SwitchToFav;
					picker.bgColor = SwitchToFav;
					parent.doFormat('$order',"#" + SwitchToFav);
				}
	
				function FavCookie() {
					var favorite1 = fav1.bgColor
					var favorite2 = fav2.bgColor
					var favorite3 = fav3.bgColor
					var favorite4 = fav4.bgColor
					var favorite5 = fav5.bgColor
					var fcookie = favorite1 + favorite2 + favorite3 + favorite4 + favorite5
					var expireDate = new Date
					expireDate.setMonth(expireDate.getMonth()+6)
					document.cookie = "fcookie=" + fcookie + "; expires=" + expireDate.toGMTString()
				}

				function FavGetCookie() {
					if (document.cookie != "") {
						var fcolors = document.cookie.split("=")[1]
						fav1.bgColor = fcolors.substring(0,6)
						fav2.bgColor = fcolors.substring(7,13)
						fav3.bgColor = fcolors.substring(14,20)
						fav4.bgColor = fcolors.substring(21,27)
						fav5.bgColor = fcolors.substring(28,34)
					}
				}
			-->
			</script>
			<table border=0 cellspacing="0" cellpadding="0" width=100%>
				<tr>
					<td align="absmiddle"><span style="font: 11.9px verdana; color: #CE0000;"><b>&nbsp;$display:</b></span></td>
					<td align="absmiddle">
						<table bgcolor="#000000" width="74" border=0 cellspacing="0" cellpadding="0" align="center" id="picker">
							<tr>
								<td><img class="framed2" src="$settings[app_dir]/img/pix.gif" width=100 height=20 onClick="$function; return false" title="$lang[repick_color]"></td>
							</tr>
						</table>
					</td>

					<td align="absmiddle">
						<span style="font: 11.9px verdana;">#</span><input type="text" name="colview" size="6" value="000000" style="width:55px; font: 11.9px verdana; border: 1px solid black;" title="$lang[insert_custom_color]" onChange="CustCol();"><input type="button" name="add" value="&raquo;" class="framed2" style="background-color:#ffffff; color:CE0000; font: 9.9px verdana; margin-left: 2px;" title="$lang[add_to_fav_colors]" OnClick="ColToFav();">
					</td>

					<td align="absmiddle">
						<table width="70" border="0" cellspacing="2" cellpadding="1" align="left" name="favorites">
							<tr valign="top">
								<td id='fav1' bgcolor="#ffffff" class="framed2"><img src="$settings[app_dir]/img/pix.gif" border=0 height=10 width=10 name="favcolor1" OnClick="FavChange('1')"></td>
								<td id='fav2' bgcolor="#ffffff" class="framed2"><img src="$settings[app_dir]/img/pix.gif" border=0 height=10 width=10 name="favcolor2" OnClick="FavChange('2')"></td>
								<td id='fav3' bgcolor="#ffffff" class="framed2"><img src="$settings[app_dir]/img/pix.gif" border=0 height=10 width=10 name="favcolor3" OnClick="FavChange('3')"></td>
								<td id='fav4' bgcolor="#ffffff" class="framed2"><img src="$settings[app_dir]/img/pix.gif" border=0 height=10 width=10 name="favcolor4" OnClick="FavChange('4')"></td>
								<td id='fav5' bgcolor="#ffffff" class="framed2"><img src="$settings[app_dir]/img/pix.gif" border=0 height=10 width=10 name="favcolor5" OnClick="FavChange('5')"></td>
								<td><span style="font: 11px verdana; color: #000000;"><nobr>&nbsp;&lt;&nbsp;$lang[fav_colors]</nobr></span></td>
							</tr>
						</table>
					</td>
					<td align=right>
					<input type="button" name="cancel" value="$lang[close]" style="width:60px; font: 11.9px verdana; text-align: center; color: #CE0000; background-color: #F5F5F5;" onClick="parent.blockDefault()">&nbsp;
					</td>
				</tr>
			</table>
			<table height="48" width="100%" border="0" bordercolor="#000000" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
				<tbody>
					<tr>
						<script language="JavaScript">
							var c = new Array();
							c[1] = "FF";
							c[2] = "CC";
							c[3] = "99";
							c[4] = "66";
							c[5] = "33";
							c[6] = "00";
							var d = 0;
							for(i=1; i <=6; i++) {
								if(i > 0) {
									document.write("</tr><tr>"); 
								}
								for(m=1;m <=6;m++) {

									for(n=1;n <=6;n++) {
										d++;
										color = c[i] + c[m] + c[n];
										document.write("<td bgcolor=\"#"+color+"\" class=\"framed2\"><img src=\"$settings[app_dir]/img/pix.gif\" width=100% height=6 onClick=\"PickCol('"+color+"')\" border=0 class=\"h\" alt=\"#"+color+"\"></td>");
									}
								}
							}
						</script>
					</tr>
				</tbody>
			</table>
		</body>
	</html>
content;
	
	break;	


##############################################################################
# table edit -                                                               #
##############################################################################

	case "table_edit":

echo  <<<content
	<html>
	<head>
	<META http-equiv="Pragma" content="no-cache"> 
	<meta http-equiv="content-type" content="text/html;charset=".$lang[charset]."">
	<title>HTMLeditbox - Table Management</title>
		<style type="text/css" media="screen"><!--
			INPUT,SELECT,TEXTAREA,BUTTON,TD { color: $settings[textcolor1]; font-size: 11.9px; font-family: Verdana }
			.tab_button { width:160px; text-align: center; color: $settings[textcolor1]; background-color: $settings[bgcolor2] }
			a { color: $settings[textcolor2]; text-decoration: none; text-weight: bold; }
		--></style>
	<script language="Javascript"><!--

			function setBorders() {
				var allborders;
				allborders = document.table.border_all_color.value
				document.table.border_top_color.value = allborders
				document.table.border_right_color.value = allborders
				document.table.border_bottom_color.value = allborders
				document.table.border_left_color.value = allborders
			}

			function setWidths() {
				var allwidths;
				allwidths = document.table.border_all_width.value
				document.table.border_top_width.value = allwidths
				document.table.border_right_width.value = allwidths
				document.table.border_bottom_width.value = allwidths
				document.table.border_left_width.value = allwidths
			}

			function setStyles(x) {
				var dt = document.table
				dt.border_top_style.selectedIndex = x
				dt.border_right_style.selectedIndex = x
				dt.border_bottom_style.selectedIndex = x
				dt.border_left_style.selectedIndex = x
			}			

			function previewSettings() {
				var dt = document.table;
				var preTable = document.getElementById('preview');
				preTable.bgColor = '' + document.table.bgcolor.value + '';
				preTable.style.borderLeftStyle = '' + document.table.border_left_style.options[document.table.border_left_style.selectedIndex].value + '';
				preTable.style.borderLeftWidth = '' + document.table.border_left_width.value + '';
				preTable.style.borderLeftColor = '' + document.table.border_left_color.value + '';
				preTable.style.borderTopStyle = '' + document.table.border_top_style.options[document.table.border_top_style.selectedIndex].value + '';
				preTable.style.borderTopWidth = '' + document.table.border_top_width.value + '';
				preTable.style.borderTopColor = '' + document.table.border_top_color.value + '';
				preTable.style.borderRightStyle = '' + document.table.border_right_style.options[document.table.border_right_style.selectedIndex].value + '';
				preTable.style.borderRightWidth = '' + document.table.border_right_width.value + '';
				preTable.style.borderRightColor = '' + document.table.border_right_color.value + '';
				preTable.style.borderBottomStyle = '' + document.table.border_bottom_style.options[document.table.border_bottom_style.selectedIndex].value + '';
				preTable.style.borderBottomWidth = '' + document.table.border_bottom_width.value + '';
				preTable.style.borderBottomColor = '' + document.table.border_bottom_color.value + '';
			}


			function prePopulateTable() {
				var parWin = window.opener.textEdit.document
				if (parWin.selection.type == "Control") {
					var getRange = parWin.selection.createRange();
					var getTable = getRange(0).parentElement.getElementsByTagName('table');
					
					var dt = document.table;
					
					// check table width and height for % sign
					var tempWidth = getTable(0).width;
					if(tempWidth.indexOf("%") > -1) {
						dt.width.value = tempWidth.replace('%','');
						dt.wunits.selectedIndex = 1;
					} else {
						dt.width.value = tempWidth;
					}

					 var tempHeight = getTable(0).height;
					if(tempHeight.indexOf("%") > -1) {
						dt.height.value = tempHeight.replace('%','');
						dt.hunits.selectedIndex = 1;
					} else {
						dt.height.value = tempHeight;
					}
					
					dt.bgcolor.value = getTable(0).bgColor;
					dt.cellpad.value = getTable(0).cellPadding;
					dt.cellspace.value = getTable(0).cellSpacing;
					dt.rows.value = getTable(0).getElementsByTagName('tr').length;
					dt.columns.value = (getTable(0).getElementsByTagName('td').length) / dt.rows.value;

					var ta = getTable(0).align;
					spinWheel('1',ta);

					var blstmp = getTable(0).style.borderLeftStyle;
					spinWheel('2',blstmp);
					var blwtmp = getTable(0).style.borderLeftWidth;
					dt.border_left_width.value = blwtmp.replace('px','');
					dt.border_left_color.value = getTable(0).style.borderLeftColor;

					var btstmp = getTable(0).style.borderTopStyle;
					spinWheel('3',blstmp);
					var btwtmp = getTable(0).style.borderTopWidth;
					dt.border_top_width.value = btwtmp.replace('px','');
					dt.border_top_color.value = getTable(0).style.borderTopColor;
					
					var brstmp = getTable(0).style.borderRightStyle;
					spinWheel('4',brstmp);
					var brwtmp = getTable(0).style.borderRightWidth;
					dt.border_right_width.value = brwtmp.replace('px','');
					dt.border_right_color.value = getTable(0).style.borderRightColor;
					
					var bbstmp = getTable(0).style.borderBottomStyle;
					spinWheel('5',brstmp);
					var bbwtmp = getTable(0).style.borderBottomWidth;
					dt.border_bottom_width.value = bbwtmp.replace('px','');
					dt.border_bottom_color.value = getTable(0).style.borderBottomColor;

					previewSettings();
					document.focus();

				} else alert('$lang[no_table_selected]');
			}


			function spinWheel(selbox,selvalue) {
				if(selbox == '1') var dt = document.table.table_align;
				if(selbox == '2') var dt = document.table.border_left_style;
				if(selbox == '3') var dt = document.table.border_top_style;
				if(selbox == '4') var dt = document.table.border_right_style;
				if(selbox == '5') var dt = document.table.border_bottom_style; 

				for (var i = 1; i < dt.options.length; i++) {
					if(dt.options[i].value == selvalue) {
						dt.selectedIndex = i;
					}
				} 
			}


			function createTable() {
				var parWin = window.opener.textEdit.document;

				if (window.opener.format == "HTML") {
					var subM = document.table
					var r = subM.rows.value
					var c = subM.columns.value

					if ((r=="") || (r==0) || (c=="") || (c==0)) {
						alert('$lang[alert_rows_columns]');
					} else {
						var tableTag = ""
						tableTag += '<table style="'
						tableTag += 'border-right:' + subM.border_right_color.value + ' '
						tableTag += '' + subM.border_right_width.value + 'px '
						tableTag += '' + subM.border_right_style.options[subM.border_right_style.selectedIndex].value +'; '
						tableTag += 'border-top:' + subM.border_top_color.value + ' '
						tableTag += '' + subM.border_top_width.value + 'px '
						tableTag += '' + subM.border_top_style.options[subM.border_top_style.selectedIndex].value +'; '
						tableTag += 'border-left:' + subM.border_left_color.value + ' '
						tableTag += '' + subM.border_left_width.value + 'px '
						tableTag += '' + subM.border_left_style.options[subM.border_left_style.selectedIndex].value +'; '
						tableTag += 'border-bottom:' + subM.border_bottom_color.value + ' '
						tableTag += '' + subM.border_bottom_width.value + 'px '
						tableTag += '' + subM.border_bottom_style.options[subM.border_bottom_style.selectedIndex].value +';"'

						tableTag += 'bgcolor="' + subM.bgcolor.value + '" '
						tableTag += 'width="' + subM.width.value + subM.wunits.options[subM.wunits.selectedIndex].value + '" '

						if(subM.height.value != "") {
							tableTag += 'height="' + subM.height.value + subM.wunits.options[subM.hunits.selectedIndex].value + '" '
						}

						if(subM.table_align.selectedIndex > 0) {
							tableTag += 'align="' + subM.table_align.options[subM.table_align.selectedIndex].value + '" '
						}

						tableTag += 'cellspacing="' + subM.cellspace.value + '" '
						tableTag += 'cellpadding="' + subM.cellpad.value + '">'
						tableTag += '<tr>'

						for (ri=1;ri <=r;ri++) {
							if (ri > 1) {
								tableTag += '</tr>'
								tableTag += '<tr>'
							}
							
							for (ci=1;ci <=c;ci++) {
								tableTag += '<td>&nbsp;</td>'
							}
						}

						tableTag += '</tr>'
						tableTag += '</table>'

						window.opener.CssFramingOn();
						parWin.selection.createRange().pasteHTML(tableTag)
					}
			
				} else alert('$lang[alert_wysiwyg_table]');
			}


			function editTable() {
				var parWin = window.opener.textEdit.document
				if (parWin.selection.type == "Control") {
					var getRange = parWin.selection.createRange();
					var getTable = getRange(0).parentElement.getElementsByTagName('table');
					
					var dt = document.table;
					if(dt.wunits.options[dt.wunits.selectedIndex].value == "%") {
						getTable(0).width = dt.width.value+'%';
					} else {
						getTable(0).width = dt.width.value;
					}

					if(dt.hunits.options[dt.hunits.selectedIndex].value == "%") {
						getTable(0).height = dt.height.value+'%';
					} else {
						getTable(0).height = dt.height.value;
					}
					getTable(0).height = dt.height.value;
					getTable(0).bgColor = dt.bgcolor.value;
					getTable(0).cellPadding = dt.cellpad.value;
					getTable(0).cellSpacing = dt.cellspace.value;

					if(dt.table_align.selectedIndex > 0) {
						getTable(0).align = dt.table_align.options[dt.table_align.selectedIndex].value
					}

					getTable(0).style.borderLeftStyle = dt.border_left_style.options[dt.border_left_style.selectedIndex].value;
					getTable(0).style.borderLeftWidth = dt.border_left_width.value + 'px';
					getTable(0).style.borderLeftColor = dt.border_left_color.value;

					getTable(0).style.borderTopStyle = dt.border_top_style.options[dt.border_top_style.selectedIndex].value;
					getTable(0).style.borderTopWidth = dt.border_top_width.value + 'px';
					getTable(0).style.borderTopColor = dt.border_top_color.value;
					
					getTable(0).style.borderRightStyle = dt.border_right_style.options[dt.border_right_style.selectedIndex].value;
					getTable(0).style.borderRightWidth = dt.border_right_width.value + 'px';
					getTable(0).style.borderRightColor = dt.border_right_color.value;
					
					getTable(0).style.borderBottomStyle = dt.border_bottom_style.options[dt.border_bottom_style.selectedIndex].value;
					getTable(0).style.borderBottomWidth = dt.border_bottom_width.value + 'px';
					getTable(0).style.borderBottomColor = dt.border_bottom_color.value;
				
				}
			}

			function setHeight() {
				parent.window.resizeTo(580,400);
			}

	--></script>

		
		</head>
	<body bgcolor="#ffffff" topmargin=3 leftmargin=3
content;
		if($HTTP_GET_VARS[tab] == "preload") {
			echo " OnLoad=\"prePopulateTable(); setHeight();\">";
			$headflag = $lang[edit_table];
		} else {
			echo " OnLoad=\"setHeight();\">";
			$headflag = $lang[create_table];
		}
echo  <<<content
		<form name="table" onSubmit="return false" style="margin: 0px;">
			<table width="100%" border="0" cellspacing="0" cellpadding="5" style="border: 1px solid $settings[border_color];">
				<tr>
					<td align="center" style="border-right: 1px solid $settings[border_color];"><font style="font-weight: bold; color: $settings[textcolor3];">$headflag</font></td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-right: 1px solid $settings[border_color]; border-bottom: 1px solid $settings[border_color];"><a href="_editor.php?op=table_cell" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[edit_cell]</font></a></td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-right: 1px solid $settings[border_color]; border-bottom: 1px solid $settings[border_color];"><a href="_editor.php?op=table_func" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[rows_columns]</font></a></td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-bottom: 1px solid $settings[border_color];"><a href="javascript:window.close()" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[close]</font></a></td>
				</tr>
				<tr>
					<td colspan="4">
						<table width="100%" border="0" cellspacing="1" cellpadding="3">
							<tr>
								<td align="right" bgcolor="#F4F4F4" width="23%"><font style="color: #ce0000;">$lang[num_of_cols]:</font></td>
								<td bgcolor="#F4F4F4"><input type="text" name="columns" style="width: 30px;" value="1" size="2" tabindex=1></td>
								<td align="right" bgcolor="#F4F4F4"><font style="color: #ce0000;">$lang[num_of_rows]:</font></td>
								<td bgcolor="#F4F4F4"><input type="text" name="rows" value="1" size="2" style="width: 30px;" tabindex=2></td>
								<td align="right" bgcolor="#F4F4F4">$lang[table_width]:</td>
								<td bgcolor="#F4F4F4"><input type="text" name="width" size="4" style="width: 36px;" tabindex=3><select name="wunits" size="1" style="width: 42px;">
										<option value="px"> $lang[px]</option>
										<option value="%">%</option>
									</select></td>
							</tr>
							<tr>
								<td align="right" width="23%">$lang[cellspacing]:</td>
								<td><input type="text" name="cellspace" style="width: 20px;" value="0" size="2" tabindex=4> $lang[px]</td>
								<td align="right">$lang[cellpadding]:</td>
								<td><input type="text" name="cellpad" size="2" style="width: 20px;" value="0" tabindex=5> $lang[px]</td>
								<td align="right">$lang[table_height]:</td>
								<td><input type="text" name="height" size="4" style="width: 36px;" tabindex=6><select name="hunits" size="1" style="width: 42px;">
										<option value="px"> $lang[px]</option>
										<option value="%">%</option>
									</select></td>
							</tr>
							<tr>
								<td colspan="6" align="right" id="preview">&nbsp;</td>
							</tr>
							<tr>
								<td align="right" width="23%">$lang[back_color]:</td>
								<td colspan="3">$lang[color] <input type="text" name="bgcolor" style="width: 66px;" value="#FFFFFF" size="10" onchange="preview.bgColor = document.all.bgcolor.value; previewSettings();" tabindex=7> pick&amp;see &gt;</td>
								<td colspan="2">
									<script src="$settings[app_dir]/js/picker.js" type="text/javascript"></script>
								</td>
							</tr>
							<tr>
								<td align="right" bgcolor="#f5f5f5" width="23%">$lang[borders_all]:</td>
								<td colspan="5" bgcolor="#f5f5f5">$lang[color] <input type="text" name="border_all_color" style="width: 66px;" value="#000000" size="10" onchange="setBorders(); previewSettings();" tabindex=8>&nbsp;&nbsp;- $lang[width]: <input type="text" name="border_all_width" size="2" border="0" value="0" style="width: 20px; text-align: right;" onchange="setWidths(); previewSettings();" tabindex=9> $lang[px]&nbsp;&nbsp;- $lang[style]: <select name="border_all_style" size="1" onchange="setStyles(selectedIndex); previewSettings();" tabindex=10>
										<option value="">$lang[none]</option>
										<option value="solid">$lang[solid]</option>
										<option value="dashed">$lang[dashed]</option>
										<option value="dotted">$lang[dotted]</option>
										<option value="double">$lang[double]</option>
										<option value="groove">$lang[groove]</option>
										<option value="ridge">$lang[ridge]</option>
										<option value="inset">$lang[inset]</option>
										<option value="outset">$lang[outset]</option>
									</select> </td>
							</tr>
							<div id="more">
							<tr>
								<td align="right" width="23%">$lang[border] - $lang[top]:</td>
								<td colspan="5">$lang[color] <input type="text" name="border_top_color" style="width: 66px;" value="#000000" size="10" onchange="previewSettings();">&nbsp;&nbsp;- $lang[width]: <input type="text" name="border_top_width" size="2" border="0" value="0" style="width: 20px; text-align: right;" onchange="previewSettings();"> $lang[px]&nbsp;&nbsp;- $lang[style]: <select name="border_top_style" size="1" onchange="previewSettings();">
										<option value="">$lang[none]</option>
										<option value="solid">$lang[solid]</option>
										<option value="dashed">$lang[dashed]</option>
										<option value="dotted">$lang[dotted]</option>
										<option value="double">$lang[double]</option>
										<option value="groove">$lang[groove]</option>
										<option value="ridge">$lang[ridge]</option>
										<option value="inset">$lang[inset]</option>
										<option value="outset">$lang[outset]</option>
									</select></td>
							</tr>
							<tr>
								<td align="right" width="23%">$lang[border] - $lang[right]:</td>
								<td colspan="5">$lang[color] <input type="text" name="border_right_color" style="width: 66px;" value="#000000" size="10" onchange="previewSettings();">&nbsp;&nbsp;- $lang[width]: <input type="text" name="border_right_width" size="2" border="0" value="0" style="width: 20px; text-align: right;" onchange="previewSettings();"> $lang[px]&nbsp;&nbsp;- $lang[style]: <select name="border_right_style" size="1" onchange="previewSettings();">
										<option value="">$lang[none]</option>
										<option value="solid">$lang[solid]</option>
										<option value="dashed">$lang[dashed]</option>
										<option value="dotted">$lang[dotted]</option>
										<option value="double">$lang[double]</option>
										<option value="groove">$lang[groove]</option>
										<option value="ridge">$lang[ridge]</option>
										<option value="inset">$lang[inset]</option>
										<option value="outset">$lang[outset]</option>
									</select></td>
							</tr>
							<tr>
								<td align="right" width="23%">$lang[border] - $lang[bottom]:</td>
								<td colspan="5">$lang[color] <input type="text" name="border_bottom_color" style="width: 66px;" value="#000000" size="10" onchange="previewSettings();">&nbsp;&nbsp;- $lang[width]: <input type="text" name="border_bottom_width" size="2" border="0" value="0" style="width: 20px; text-align: right;" onchange="previewSettings();"> $lang[px]&nbsp;&nbsp;- $lang[style]: <select name="border_bottom_style" size="1" onchange="previewSettings();">
										<option value="">$lang[none]</option>
										<option value="solid">$lang[solid]</option>
										<option value="dashed">$lang[dashed]</option>
										<option value="dotted">$lang[dotted]</option>
										<option value="double">$lang[double]</option>
										<option value="groove">$lang[groove]</option>
										<option value="ridge">$lang[ridge]</option>
										<option value="inset">$lang[inset]</option>
										<option value="outset">$lang[outset]</option>
									</select></td>
							</tr>
							<tr>
								<td align="right" width="23%">$lang[border] - $lang[left]:</td>
								<td colspan="5">$lang[color] <input type="text" name="border_left_color" style="width: 66px;" value="#000000" size="10" onchange="previewSettings();">&nbsp;&nbsp;- $lang[width]: <input type="text" name="border_left_width" size="2" border="0" value="0" style="width: 20px; text-align: right;" onchange="previewSettings();"> $lang[px]&nbsp;&nbsp;- $lang[style]: <select name="border_left_style" size="1" onchange="previewSettings();">
										<option value="">$lang[none]</option>
										<option value="solid">$lang[solid]</option>
										<option value="dashed">$lang[dashed]</option>
										<option value="dotted">$lang[dotted]</option>
										<option value="double">$lang[double]</option>
										<option value="groove">$lang[groove]</option>
										<option value="ridge">$lang[ridge]</option>
										<option value="inset">$lang[inset]</option>
										<option value="outset">$lang[outset]</option>
									</select></td>
							</tr>
							</div>
							<tr>
								<td align="right" width="23%">$lang[table_align]:</td>
								<td colspan="5"><select name="table_align" size="1">
										<option>$lang[none]</option>
										<option value="left">$lang[left]</option>
										<option value="center">$lang[center]</option>
										<option value="right">$lang[right]</option>
									</select></td>
							</tr>
							<tr>
								<td width="23%"></td>
								<td colspan="5">
content;

if($HTTP_GET_VARS[tab] == "preload") {
	echo "<input type=\"button\" name=\"act\" value=\"$lang[edit_table]\" style=\"width:160px; text-align: center; color: #ce0000; background-color: #f5f5f5;\" onclick=\"editTable();\">";
} else {
	echo "<input type=\"button\" name=\"act\" value=\"$lang[create_table]\" style=\"width:160px; text-align: center; color: #ce0000; background-color: #f5f5f5;\" onclick=\"createTable();\">";
}

echo  <<<content
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>
content;

break;	


######################################################################
# table - edit cell                                                  #
######################################################################
			
	case "table_cell":
echo  <<<content
	<html>
	<head>
	<META http-equiv="Pragma" content="no-cache"> 
	<meta http-equiv="content-type" content="text/html;charset=".$lang[charset]."">
	<title>HTMLeditbox - Table Management</title>
		<style type="text/css" media="screen"><!--
			INPUT,SELECT,TEXTAREA,BUTTON,TD { color: $settings[textcolor1]; font-size: 11.9px; font-family: Verdana }
			.tab_button { width:160px; text-align: center; color: $settings[textcolor1]; background-color: $settings[bgcolor2] }
			a { color: $settings[textcolor2]; text-decoration: none; text-weight: bold; }
		--></style>
	<script language="Javascript"><!--
			


			function areWeInCell() {
				if (document.selection.type != "Control") {
					var oRange = document.selection.createRange().parentElement()
						if(oRange.tagName.toUpperCase() == "TD" || oRange.tagName.toUpperCase() == "TH") {
							return true;
						} else return false;
				}
			}

			function setBorders() {
				var allborders;
				allborders = document.table.border_all_color.value
				document.table.border_top_color.value = allborders
				document.table.border_right_color.value = allborders
				document.table.border_bottom_color.value = allborders
				document.table.border_left_color.value = allborders
			}

			function setWidths() {
				var allwidths;
				allwidths = document.table.border_all_width.value
				document.table.border_top_width.value = allwidths
				document.table.border_right_width.value = allwidths
				document.table.border_bottom_width.value = allwidths
				document.table.border_left_width.value = allwidths
			}

			function setStyles(x) {
				var dt = document.table
				dt.border_top_style.selectedIndex = x
				dt.border_right_style.selectedIndex = x
				dt.border_bottom_style.selectedIndex = x
				dt.border_left_style.selectedIndex = x
			}

			function previewSettings() {
				var dt = document.table;
				var preTable = document.getElementById('preview');
				preTable.bgColor = '' + document.table.bgcolor.value + '';
				preTable.style.borderLeftStyle = '' + document.table.border_left_style.options[document.table.border_left_style.selectedIndex].value + '';
				preTable.style.borderLeftWidth = '' + document.table.border_left_width.value + '';
				preTable.style.borderLeftColor = '' + document.table.border_left_color.value + '';
				preTable.style.borderTopStyle = '' + document.table.border_top_style.options[document.table.border_top_style.selectedIndex].value + '';
				preTable.style.borderTopWidth = '' + document.table.border_top_width.value + '';
				preTable.style.borderTopColor = '' + document.table.border_top_color.value + '';
				preTable.style.borderRightStyle = '' + document.table.border_right_style.options[document.table.border_right_style.selectedIndex].value + '';
				preTable.style.borderRightWidth = '' + document.table.border_right_width.value + '';
				preTable.style.borderRightColor = '' + document.table.border_right_color.value + '';
				preTable.style.borderBottomStyle = '' + document.table.border_bottom_style.options[document.table.border_bottom_style.selectedIndex].value + '';
				preTable.style.borderBottomWidth = '' + document.table.border_bottom_width.value + '';
				preTable.style.borderBottomColor = '' + document.table.border_bottom_color.value + '';
			}

			function spinWheel(selbox,selvalue) {
				if(selbox == '2') var dt = document.table.border_left_style;
				if(selbox == '3') var dt = document.table.border_top_style;
				if(selbox == '4') var dt = document.table.border_right_style;
				if(selbox == '5') var dt = document.table.border_bottom_style;
				if(selbox == '6') var dt = document.table.hor_align;
				if(selbox == '7') var dt = document.table.vert_align;


				for (var i = 1; i < dt.options.length; i++) {
					if(dt.options[i].value == selvalue) {
						dt.selectedIndex = i;
					}
				} 
			}

			function ClimbUpLookFor(startElement,whatTag) {
				while (startElement != null && startElement.tagName != whatTag) {
					startElement = startElement.parentElement;
					}
				return startElement;
			}

			
			function editCell() {
					var parWin = window.opener.textEdit.document
					var getRange = parWin.selection.createRange();
						if(getRange.parentElement != null) {
							var getTable = ClimbUpLookFor(getRange.parentElement(),"TD");
							if(getTable == null) return;
					
							var dt = document.table;
							if(dt.wunits.options[dt.wunits.selectedIndex].value == "%") {
								getTable.width = dt.width.value+'%';
							} else {
								getTable.width = dt.width.value;
							}

							if(dt.hunits.options[dt.hunits.selectedIndex].value == "%") {
								getTable.height = dt.height.value+'%';
							} else {
								getTable.height = dt.height.value;
							}
							getTable.bgColor = dt.bgcolor.value;
					
							if(dt.col_span.value > 1) { 
								getTable.colSpan = dt.col_span.value;
							} else getTable.removeAttribute('colSpan');


							if(dt.row_span.value > 1) {
								getTable.rowSpan = dt.row_span.value;
							} else getTable.removeAttribute('rowSpan');
	
							getTable.align = dt.hor_align.options[dt.hor_align.selectedIndex].value;
							getTable.vAlign = dt.vert_align.options[dt.vert_align.selectedIndex].value;

							if(dt.no_wrap.checked == true) {
								getTable.noWrap = true;
							} else {
								getTable.noWrap = false;
							}

							getTable.style.borderLeftStyle = dt.border_left_style.options[dt.border_left_style.selectedIndex].value;

							if(dt.border_left_width.value != '') {
								getTable.style.borderLeftWidth = dt.border_left_width.value + 'px';
							}
							getTable.style.borderLeftColor = dt.border_left_color.value;

							getTable.style.borderTopStyle = dt.border_top_style.options[dt.border_top_style.selectedIndex].value;
						
							if(dt.border_top_width.value != '') {
								getTable.style.borderTopWidth = dt.border_top_width.value + 'px';
							}
							getTable.style.borderTopColor = dt.border_top_color.value;
					
							getTable.style.borderRightStyle = dt.border_right_style.options[dt.border_right_style.selectedIndex].value;
	
							if(dt.border_right_width.value != '') {
								getTable.style.borderRightWidth = dt.border_right_width.value + 'px';
							}
							getTable.style.borderRightColor = dt.border_right_color.value;
					
							getTable.style.borderBottomStyle = dt.border_bottom_style.options[dt.border_bottom_style.selectedIndex].value;
						
							if(dt.border_bottom_width.value != '') {
								getTable.style.borderBottomWidth = dt.border_bottom_width.value + 'px';
							}
							getTable.style.borderBottomColor = dt.border_bottom_color.value;
					}
			}

			function prePopulateCell() {
				var parWin = window.opener.textEdit.document;
				var getRange = parWin.selection.createRange();
					if(getRange.parentElement != null) {
						var getTable = ClimbUpLookFor(getRange.parentElement(),"TD");
						if(getTable == null) {
							alert('$lang[no_cell_selected]');
							return;
						}
					
						var dt = document.table;
					
						// check table width and height for % sign
						var tempWidth = getTable.width;
						if(tempWidth.indexOf("%") > -1) {
							dt.width.value = tempWidth.replace('%','');
							dt.wunits.selectedIndex = 1;
						} else {
							dt.width.value = tempWidth;
						}


						var tempHeight = getTable.height;
						if(tempHeight.indexOf("%") > -1) {
							dt.height.value = tempHeight.replace('%','');
							dt.hunits.selectedIndex = 1;
						} else {
							dt.height.value = tempHeight;
						}

						dt.bgcolor.value = getTable.bgColor;
						
						dt.col_span.value = getTable.colSpan;
						dt.row_span.value = getTable.rowSpan;
						
						var halignTmp = getTable.align;
						spinWheel('6',halignTmp);

						var valignTmp = getTable.vAlign;
						spinWheel('7',valignTmp);

						if(getTable.noWrap == true) {
							dt.no_wrap.checked = true;
						}

						var blstmp = getTable.style.borderLeftStyle;
						spinWheel('2',blstmp);
						var blwtmp = getTable.style.borderLeftWidth;
						dt.border_left_width.value = blwtmp.replace('px','');
						dt.border_left_color.value = getTable.style.borderLeftColor;

						var btstmp = getTable.style.borderTopStyle;
						spinWheel('3',blstmp);
						var btwtmp = getTable.style.borderTopWidth;
						dt.border_top_width.value = btwtmp.replace('px','');
						dt.border_top_color.value = getTable.style.borderTopColor;
					
						var brstmp = getTable.style.borderRightStyle;
						spinWheel('4',brstmp);
						var brwtmp = getTable.style.borderRightWidth;
						dt.border_right_width.value = brwtmp.replace('px','');
						dt.border_right_color.value = getTable.style.borderRightColor;
					
						var bbstmp = getTable.style.borderBottomStyle;
						spinWheel('5',brstmp);
						var bbwtmp = getTable.style.borderBottomWidth;
						dt.border_bottom_width.value = bbwtmp.replace('px','');
						dt.border_bottom_color.value = getTable.style.borderBottomColor;

						previewSettings();
						document.focus();
					}
			}

			function setHeight() {
				parent.window.resizeTo(580,426);
			}

	--></script>

		
		</head>
	<body bgcolor="#ffffff" topmargin=3 leftmargin=3 OnLoad="prePopulateCell(); setHeight();">
		<form name="table" onSubmit="return false" style="margin: 0px;">
			<table width="100%" border="0" cellspacing="0" cellpadding="5" style="border: 1px solid $settings[border_color];">
				<tr>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-right: 1px solid $settings[border_color]; border-bottom: 1px solid $settings[border_color];"><font style="font-weight: bold; color: $settings[textcolor3];"><a href="_editor.php?op=table_edit" style="color: $settings[textcolor2];">$lang[create_table]</a></font></td>
					<td align="center" bgcolor="#FFFFFF" style="border-right: 1px solid $settings[border_color];"><font style="font-weight: bold; color: $settings[textcolor3];">$lang[edit_cell]</font></td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-right: 1px solid $settings[border_color]; border-bottom: 1px solid $settings[border_color];"><a href="_editor.php?op=table_func" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[rows_columns]</font></a></td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-bottom: 1px solid $settings[border_color];"><a href="javascript:window.close()" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[close]</font></a></td>
				</tr>
				<tr>
					<td colspan="4">
						<table width="100%" border="0" cellspacing="1" cellpadding="3">
							<tr>
								<td align="right" bgcolor="#fff3bd" width="23%">$lang[cell_width]:</td>
								<td colspan="2" bgcolor="#fff3bd"><input type="text" name="width" size="4" style="width: 36px;" tabindex=3><select name="wunits" size="1" style="width: 42px;" tabindex=4>
										<option value="px">px</option>
										<option value="%">%</option>
									</select></td>
								<td align="right" bgcolor="#fff3bd">$lang[cell_height]:</td>
								<td colspan="2" bgcolor="#fff3bd"><input type="text" name="height" size="4" style="width: 36px;" tabindex=3><select name="hunits" size="1" style="width: 42px;" tabindex=4>
										<option value="px">px</option>
										<option value="%">%</option>
									</select></td>
							</tr>
							<tr>
								<td align="right" width="23%">$lang[vertical_alignment]:</td>
								<td colspan="2"><select name="vert_align" size="1">
										<option>$lang[default]</option>
										<option value="top">$lang[top]</option>
										<option value="middle">$lang[middle]</option>
										<option value="bottom">$lang[bottom]</option>
									</select></td>
								<td align="right">$lang[horizontal_alignment]:</td>
								<td colspan="2"><select name="hor_align" size="1">
										<option>$lang[default]</option>
										<option value="left">$lang[left]</option>
										<option value="center">$lang[center]</option>
										<option value="right">$lang[right]</option>
									</select></td>
							</tr>
							<tr>
								<td align="right" width="23%">$lang[row_span]:</td>
								<td colspan="2"><input type="text" name="row_span" value="1" size="2" style="width: 20px;" tabindex=2></td>
								<td align="right">$lang[col_span]:</td>
								<td colspan="2"><input type="text" name="col_span" size="2" style="width: 20px;" value="0" tabindex=6></td>
							</tr>
							<tr>
								<td colspan="6" align="right" id="preview">&nbsp;</td>
							</tr>
							<tr>
								<td align="right" width="23%">$lang[back_color]:</td>
								<td colspan="3">$lang[color] <input type="text" name="bgcolor" style="width: 66px;" value="#FFFFFF" size="10" onchange="preview.bgColor = document.all.bgcolor.value; previewSettings();" tabindex=7> pick&amp;see &gt;</td>
								<td colspan="2">
									<script src="$settings[app_dir]/js/picker.js" type="text/javascript"></script>
								</td>
							</tr>
							<tr>
								<td align="right" bgcolor="#f5f5f5" width="23%">$lang[borders_all]:</td>
								<td colspan="5" bgcolor="#f5f5f5">$lang[color] <input type="text" name="border_all_color" style="width: 66px;" value="#000000" size="10" onchange="setBorders(); previewSettings();" tabindex=8>&nbsp;&nbsp;- $lang[width]: <input type="text" name="border_all_width" size="2" border="0" value="0" style="width: 20px; text-align: right;" onchange="setWidths(); previewSettings();" tabindex=9> $lang[px]&nbsp;&nbsp;- $lang[style]: <select name="border_all_style" size="1" onchange="setStyles(selectedIndex); previewSettings();" tabindex=10>
										<option value="">$lang[none]</option>
										<option value="solid">$lang[solid]</option>
										<option value="dashed">$lang[dashed]</option>
										<option value="dotted">$lang[dotted]</option>
										<option value="double">$lang[double]</option>
										<option value="groove">$lang[groove]</option>
										<option value="ridge">$lang[ridge]</option>
										<option value="inset">$lang[inset]</option>
										<option value="outset">$lang[outset]</option>
									</select> </td>
							</tr>
							<div id="more">
							<tr>
								<td align="right" width="23%">$lang[border] - $lang[top]:</td>
								<td colspan="5">$lang[color] <input type="text" name="border_top_color" style="width: 66px;" value="#000000" size="10" onchange="previewSettings();">&nbsp;&nbsp;- $lang[width]: <input type="text" name="border_top_width" size="2" border="0" value="0" style="width: 20px; text-align: right;" onchange="previewSettings();"> $lang[px]&nbsp;&nbsp;- $lang[style]: <select name="border_top_style" size="1" onchange="previewSettings();">
										<option value="">$lang[none]</option>
										<option value="solid">$lang[solid]</option>
										<option value="dashed">$lang[dashed]</option>
										<option value="dotted">$lang[dotted]</option>
										<option value="double">$lang[double]</option>
										<option value="groove">$lang[groove]</option>
										<option value="ridge">$lang[ridge]</option>
										<option value="inset">$lang[inset]</option>
										<option value="outset">$lang[outset]</option>
									</select></td>
							</tr>
							<tr>
								<td align="right" width="23%">$lang[border] - $lang[right]:</td>
								<td colspan="5">$lang[color] <input type="text" name="border_right_color" style="width: 66px;" value="#000000" size="10" onchange="previewSettings();">&nbsp;&nbsp;- $lang[width]: <input type="text" name="border_right_width" size="2" border="0" value="0" style="width: 20px; text-align: right;" onchange="previewSettings();"> $lang[px]&nbsp;&nbsp;- $lang[style]: <select name="border_right_style" size="1" onchange="previewSettings();">
										<option value="">$lang[none]</option>
										<option value="solid">$lang[solid]</option>
										<option value="dashed">$lang[dashed]</option>
										<option value="dotted">$lang[dotted]</option>
										<option value="double">$lang[double]</option>
										<option value="groove">$lang[groove]</option>
										<option value="ridge">$lang[ridge]</option>
										<option value="inset">$lang[inset]</option>
										<option value="outset">$lang[outset]</option>
									</select></td>
							</tr>
							<tr>
								<td align="right" width="23%">$lang[border] - $lang[bottom]:</td>
								<td colspan="5">$lang[color] <input type="text" name="border_bottom_color" style="width: 66px;" value="#000000" size="10" onchange="previewSettings();">&nbsp;&nbsp;- $lang[width]: <input type="text" name="border_bottom_width" size="2" border="0" value="0" style="width: 20px; text-align: right;" onchange="previewSettings();"> $lang[px]&nbsp;&nbsp;- $lang[style]: <select name="border_bottom_style" size="1" onchange="previewSettings();">
										<option value="">$lang[none]</option>
										<option value="solid">$lang[solid]</option>
										<option value="dashed">$lang[dashed]</option>
										<option value="dotted">$lang[dotted]</option>
										<option value="double">$lang[double]</option>
										<option value="groove">$lang[groove]</option>
										<option value="ridge">$lang[ridge]</option>
										<option value="inset">$lang[inset]</option>
										<option value="outset">$lang[outset]</option>
									</select></td>
							</tr>
							<tr>
								<td align="right" width="23%">$lang[border] - $lang[left]:</td>
								<td colspan="5">$lang[color] <input type="text" name="border_left_color" style="width: 66px;" value="#000000" size="10" onchange="previewSettings();">&nbsp;&nbsp;- $lang[width]: <input type="text" name="border_left_width" size="2" border="0" value="0" style="width: 20px; text-align: right;" onchange="previewSettings();"> $lang[px]&nbsp;&nbsp;- $lang[style]: <select name="border_left_style" size="1" onchange="previewSettings();">
										<option value="">$lang[none]</option>
										<option value="solid">$lang[solid]</option>
										<option value="dashed">$lang[dashed]</option>
										<option value="dotted">$lang[dotted]</option>
										<option value="double">$lang[double]</option>
										<option value="groove">$lang[groove]</option>
										<option value="ridge">$lang[ridge]</option>
										<option value="inset">$lang[inset]</option>
										<option value="outset">$lang[outset]</option>
									</select></td>
							</tr>
							</div>
							<tr>
								<td align="right" width="23%"><input type="checkbox" name="no_wrap" value="1" border="0"></td>
								<td colspan="5">$lang[no_wrap]</td>
							</tr>
							<tr>
								<td width="23%"></td>
								<td colspan="5">
									<input type="button" name="act" value="$lang[edit_cell]" style="width:160px; text-align: center; color: #ce0000; background-color: #f5f5f5;" onclick="editCell();">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>

content;
	break;


######################################################################
# table - table functions                                            #
######################################################################
	
	case "table_func":

echo  <<<content
	<html>
	<head>
	<META http-equiv="Pragma" content="no-cache"> 
	<meta http-equiv="content-type" content="text/html;charset=".$lang[charset]."">
	<title>HTMLeditbox - Table Management</title>
		<style type="text/css" media="screen"><!--
			INPUT,SELECT,TEXTAREA,BUTTON,TD { color: $settings[textcolor1]; font-size: 11.9px; font-family: Verdana }
			.tab_button { width:160px; text-align: center; color: $settings[textcolor1]; background-color: $settings[bgcolor2] }
			a { color: $settings[textcolor2]; text-decoration: none; text-weight: bold; }
		--></style>
	<script language="Javascript"><!--

		var parWin = window.opener.textEdit.document;
		var getRange = parWin.selection.createRange();

		function whatCell() {
			var getRange = parWin.selection.createRange();

			if(getRange.parentElement != null) {
				var getCell = ClimbUpLookFor(getRange.parentElement(),"TD");
				if(getCell == null) return;
			}
			return getCell;
		}

		function whatRow() {
			var getRange = parWin.selection.createRange();

			if(getRange.parentElement != null) {
				var getRow = ClimbUpLookFor(getRange.parentElement(),"TR");
				if(getRow == null) return;
			}
			return getRow;
		}

		function whatTable() {
			var getRange = parWin.selection.createRange();

			if(getRange.parentElement != null) {
				var getTable = ClimbUpLookFor(getRange.parentElement(),"TABLE");
				if(getTable == null) return;
			}
			return getTable;
		}

		function colsInTable(getTable) {
			var numCols = getTable.getElementsByTagName("td").length / getTable.getElementsByTagName("tr").length;
			return numCols;
		}

		function getRowIndex (cell) {
			return parWin.all ? cell.parentElement.rowIndex : cell.parentNode.rowIndex;
		}

		function showMeCoord(cell) {
			coordX = cell.parentElement.rowIndex;
			coordY = cell.cellIndex;
			alert(coordX + ',' + coordY);
		}


		function ClimbUpLookFor(startElement,whatTag) {
			while (startElement != null && startElement.tagName != whatTag) {
				startElement = startElement.parentElement;
				}
			return startElement;
		}

		function InsertRowAbove() {
			var getCell = whatCell();
			var getTable = whatTable();

			rowAbove = getTable.insertRow(getCell.parentElement.rowIndex);
			numCols = colsInTable(getTable);
			for(i=0; i < numCols; i++) {
				addCell = rowAbove.insertCell(rowAbove)
			}
		}

		function InsertRowBelow() {
			var getCell = whatCell();
			var getTable = whatTable();

			rowAbove = getTable.insertRow(getCell.parentElement.rowIndex+1);
			numCols = colsInTable(getTable);
			for(i=0; i < numCols; i++) {
				addCell = rowAbove.insertCell(rowAbove)
			}
		}


		function InsertColLeft() {
			var getCell = whatCell();
 			var getTable = whatTable();
			var totalRows = getTable.rows.length;
			var whichCol = getCell.cellIndex;

			for(i=0; i < totalRows; i++) {
				thisRow = getTable.rows[i].insertCell(whichCol);
			}
		}

		function InsertColRight() {
			var getCell = whatCell();
 			var getTable = whatTable();
			var totalRows = getTable.rows.length;
			var whichCol = getCell.cellIndex + 1;

			for(i=0; i < totalRows; i++) {
				thisRow = getTable.rows[i].insertCell(whichCol);
			}
		}


		function MoveRowUp() {
			var getCell = whatCell();
			var getTable = whatTable();
			var getRow = getCell.parentElement.rowIndex;

			if(getRow > 0) {
			swapUp = getTable.rows(getRow).swapNode(getTable.rows(getRow-1));
			}
		}

		function MoveRowDown() {
			var getCell = whatCell();
			var getTable = whatTable();
			var totalRows = getTable.rows.length;
			var getRow = getCell.parentElement.rowIndex;

			if(getRow < totalRows) {
			swapDown = getTable.rows(getRow).swapNode(getTable.rows(getRow+1));
			}
		}

		function MoveColLeft() {
			var getCell = whatCell();
			var getTable = whatTable();
			var totalRows = getTable.rows.length;
			var getRow = getCell.parentElement.rowIndex;
			var whichCol = getCell.cellIndex

			if(whichCol > 0) {
				for(i=0; i < totalRows; i++) {
					swapLeft = getTable.rows(i).cells(whichCol).swapNode(getTable.rows(i).cells(whichCol-1));
				}
			}
		}

		function MoveColRight() {
			var getCell = whatCell();
			var getTable = whatTable();
			var totalRows = getTable.rows.length;
			var totalCols = getTable.cells.length / totalRows - 1;
			var getRow = getCell.parentElement.rowIndex;
			var whichCol = getCell.cellIndex

			if(whichCol < totalCols) {
				for(i=0; i < totalRows; i++) {
					swapLeft = getTable.rows(i).cells(whichCol).swapNode(getTable.rows(i).cells(whichCol+1));
				}
			}
		}

		function InsertCell() {
			var getCell = whatCell();
			var getRow = getCell.parentElement.rowIndex;
			addCell = getCell.parentElement.insertCell(getCell)
		}

		function DeleteRow() {
			var getCell = whatCell();
			var getTable = whatTable();

			delRow = getTable.deleteRow(getCell.parentElement.rowIndex);
		}

		function DeleteCell() {
			var getCell = whatCell();
			var getTable = whatTable();

			delRow = getCell.parentElement.deleteCell();
		}

		function DeleteCol() {
			var getCell = whatCell();
 			var getTable = whatTable();
			var totalRows = getTable.rows.length;
			var whichCol = getCell.cellIndex;

			for(i=0; i < totalRows; i++) {
				thisRow = getTable.rows[i].deleteCell(whichCol);
			}
		}

		function setHeight() {
			parent.window.resizeTo(580,222);
		}

		--></script>
		</head>
	<body bgcolor="#ffffff" topmargin=3 leftmargin=3 OnLoad="setHeight();">
		<form name="table" onSubmit="return false" style="margin: 0px;">
			<table width="100%" border="0" cellspacing="0" cellpadding="5" style="border: 1px solid $settings[border_color];">
				<tr>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-right: 1px solid $settings[border_color]; border-bottom: 1px solid $settings[border_color];"><font style="font-weight: bold; color: $settings[textcolor3];"><a href="_editor.php?op=table_edit" style="color: $settings[textcolor2];">$lang[create_table]</a></font></td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-right: 1px solid $settings[border_color]; border-bottom: 1px solid $settings[border_color];"><a href="_editor.php?op=table_cell" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[edit_cell]</font></a></td>
					<td align="center" bgcolor="#FFFFFF" style="border-right: 1px solid $settings[border_color];"><font style="font-weight: bold; color: $settings[textcolor3];">$lang[rows_columns]</font></a></td>
					<td align="center" bgcolor="$settings[bgcolor]" style="border-bottom: 1px solid $settings[border_color];"><a href="javascript:window.close()" style="color: $settings[textcolor2];"><font style="font-weight: bold; color: $settings[textcolor2];">$lang[close]</font></a></td>
				</tr>
				<tr>
					<td colspan="4"><br>
						<table width="100%" border="0" cellspacing="1" cellpadding="3">
							<tr>
								<td align="center"><input type="button" name="insert_row_above" value="$lang[insert_row_above]" class="tab_button" onclick="InsertRowAbove();"></td>
								<td align="center"><input type="button" name="move_row_up" value="$lang[move_row_up]" class="tab_button" onclick="MoveRowUp();"></td>
								<td align="center"><input type="button" name="merge_cells" value="$lang[insert_cell]" class="tab_button" onclick="InsertCell();"></td>
							</tr>
							<tr>
								<td align="center"><input type="button" name="insert_row_below" value="$lang[insert_row_below]" class="tab_button" onclick="InsertRowBelow();"></td>
								<td align="center"><input type="button" name="move_row_down" value="$lang[move_row_down]" class="tab_button" onclick="MoveRowDown();"></td>
								<td align="center"><input type="button" name="delete_cell" value="$lang[delete_cell]" class="tab_button" onclick="DeleteCell();"></td>
							</tr>
							<tr>
								<td align="center"><input type="button" name="insert_col_left" value="$lang[insert_col_left]" class="tab_button" onclick="InsertColLeft();"></td>
								<td align="center"><input type="button" name="move_col_left" value="$lang[move_col_left]" class="tab_button" onclick="MoveColLeft();"></td>
								<td align="center"><input type="button" name="delete_row" value="$lang[delete_row]" class="tab_button" onclick="DeleteRow();"></td>
							</tr>
							<tr>
								<td align="center"><input type="button" name="insert_col_right" value="$lang[insert_col_right]" class="tab_button" onclick="InsertColRight();"></td>
								<td align="center"><input type="button" name="move_col_right" value="$lang[move_col_right]" class="tab_button" onclick="MoveColRight();"></td>
								<td align="center"><input type="button" name="delete_col" value="$lang[delete_col]" class="tab_button" onclick="DeleteCol();"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>

content;

	break;

	case "save2db":
		$dbworks = "save";
		include($settings['app_dir'].'/inc/db_bridge.php');
	break;


	case "save2file":
		$fileworks = "save";
		include($settings['app_dir'].'/inc/file_bridge.php');
	break;
}
?>