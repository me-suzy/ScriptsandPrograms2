<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------

// don't access directly..
if(!defined('INPIVOT')){ exit('not in pivot'); }

function PageHeader($title='New Hotness', $menu='1') {
	global $build, $CurrentEncoding, $theme;
	
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset='.$CurrentEncoding.'" />
		<title>Pivot &#187; '.$title.'</title>

		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" type="text/css" href="'. $theme['css'] .'" />
		<meta http-equiv="Cache-Control" content="no-cache" />
		<meta http-equiv="Expires" content="0" />
		<meta http-equiv="Pragma" content="no-cache" /> 
		<script language="JavaScript" src="includes/js/switch.js" type="text/javascript"></script>
		

	</head>
	<body>
	<!-- menu starts here -->
	<div id="links">
		
	<a href="http://www.pivotlog.net/?ver='.urlencode($build).'" target="_blank" title="'.$build.'"><img src="'.$theme['logo'].'" width="120" height="129" border="0" alt="'.$build.'" /></a>
		
			';
	if($menu == 1){
		NavLinks();
	}
	echo '
	
	</div>
	<!-- menu ends here -->
	
	';

}

function PageAnkeiler($title='New Hotness', $toggletext="", $togglelink="javascript:toggle();") {
	global $Pivot_Vars, $Cfg;

	// create a little bit of space.
	// Some oft-used shortcuts. We'll make these customisable sometime.

	echo "<div class='showlog'>".lang('general', 'shortcuts').":&nbsp;";

	$link = "index.php?session=".$Pivot_Vars['session'] ."&amp;menu=entries&amp;func=new_entry";
	echo "<a href=\"$link\">".lang('userbar', 'submit')."</a>&nbsp;";

	$link = "index.php?session=".$Pivot_Vars['session'] ."&amp;menu=main&amp;func=buildfrontpage";
	echo "-&nbsp;<a href=\"$link\">".lang('adminbar', 'buildfrontpage')."</a>&nbsp;";

	$link = "index.php?session=".$Pivot_Vars['session'] ."&amp;menu=main&amp;func=send_pings";
	echo "-&nbsp;<a href=\"$link\">".lang('adminbar', 'sendping')."</a>&nbsp;";

	$link = "index.php?session=".$Pivot_Vars['session'] ."&amp;menu=main&amp;func=build_index";
	echo "-&nbsp;<a href=\"$link\">".lang('adminbar', 'buildindex')."</a>&nbsp;";

	if ($Cfg['debug'] == 1) {
		//void(debugwin.location.reload());
		$link = "javascript:void(debugwin = window.open('modules/module_debug.php#bottom', 'debugwin', 'status=yes, scrollbars=yes, resizable=yes, width=600, height=300'))";
		echo "-&nbsp;<a href=\"$link\">".lang('adminbar', 'debug')."</a>&nbsp;";
	}
		
	echo "</div><div class='main'>";


	// print out the Ankeiler itself
	echo "\n<div class='chooseview'>";
	if ($toggletext!="") {
		printf("<a href='%s' title='toggle between Basic and Extended View'><span id='extended_label'>%s</span></a>",
		$togglelink, $toggletext);
	}
	echo "</div>\n<h1>$title</h1>\n";
	echo "</div>\n<div class='main'>\n";

}


function NavLinks() {
	global $Pivot_Vars, $mainFunctions, $adminFunctions, $Cfg, $Weblogs;
	if(!isset($Pivot_Vars['menu'])) {
		$Pivot_Vars['menu'] = 'overview';
	}

	echo "<ul>";

	foreach($mainFunctions as $func => $disp) {
		//just getting the toplevel 'key' to the subcategory items.
		if($Pivot_Vars['menu']!=$func){
			echo '<li class="topmenu"><a href="index.php?session='.$Pivot_Vars['session'].'&amp;menu='.$func.'">'.$disp[0].'</a></li>'."\n";
		}else{
			echo '<li class="topmenu"><a href="index.php?session='.$Pivot_Vars['session'].'&amp;menu='.$func.'"><b>&#187;'.$disp[0].'</b></a></li>'."\n";
			if($func != 'admin'){
				for($i=1; $i<count($mainFunctions[$func]); $i++){
					echo '<li class="submenu"><a href="index.php?session='.$Pivot_Vars['session'].'&amp;menu='.$func.'&amp;func='.$mainFunctions[$func][$i][0].'">'.$mainFunctions[$func][$i][1].'</a></li>'."\n";
				}
			}else{
				foreach($adminFunctions as $do => $adisp) {
					echo '<li class="submenu"><a href="index.php?session='.$Pivot_Vars['session'].'&amp;menu='.$func.'&amp;func=admin&amp;do='.$do.'">'.$adisp.'</a></li>'."\n";
				}
			}
		}
	}
	echo '<li class="topmenu"><a>&nbsp;</a></li>';
	echo '<li class="topmenu" style="font-size: 0.8em;"><a href="index.php?session='.$Pivot_Vars['session'].'&amp;func=login&amp;do=logout"><b>logout '. $Pivot_Vars['user'] .'</b></a></li>'."\n";
	foreach ($Weblogs as $Weblog) {
		$sitename = ($Weblog['name'] != "") ? $Weblog['name'] : "(..)";
		$siteurl = $Weblog['front_path'].$Weblog['front_filename'];
		echo '<li class="topmenu"><a href="'.$siteurl.'" target="_blank">'.$sitename.'</a></li>';
	}

	echo "</ul>";

}


function PageFooter() {

	echo "</div>\n";

	if(function_exists('memory_get_usage')) {
		echo "<div style='color:#FFF;' class='main'>".timetaken()." - ".memory_get_usage()."</div>\n";
	} else {
		echo("<div style='color:#FFF;' class='main'>".timetaken()."</div>\n");
	}

	echo("</body>\n</html>\n");

	//exit;
}

function MakeLink($params='', $linkfun=''){
	global $Pivot_Vars;
	
	// if it's a javascript: link, just return it..
	if (substr($params['func'], 0, 11) == "javascript:") {
		return "<a href=\"".$params['func']."\">";
	} 
	
	$url = '<a href="index.php?session='.$Pivot_Vars['session'].'&amp;menu='.$Pivot_Vars['menu'];
	if(is_array($params)){
		foreach($params as $var => $val) {
			$url .= '&amp;'.$var.'='.$val;
		}
	}
	$url .= '" '.$linkfun.'>';
	return $url;
}

//used before forms.
function StartForm($whereto='', $admin='0', $special='') {
	global $Pivot_Vars;
	if($admin==1 || ( isset($Pivot_Vars['func']) && $Pivot_Vars['func']=='admin')){
		$whereto = 'admin&amp;do='.$whereto;
	}
	echo '<form method="post" '.$special.' action="index.php?menu='.$Pivot_Vars['menu'].'&amp;func='.$whereto.'" class="nopadding" />'."\n";
	if(strlen($Pivot_Vars['session']) == 12) {
		echo '<input type="hidden" name="session" value="'.$Pivot_Vars['session'].'" />'."\n";
	}
}


function StartTable($tableheader='', $rows='2'){

	echo '<table cellspacing="0" cellpadding="2" border="0" width="480" class="sTable">'."\n";

	echo "<tr class=\"trheader\">\n<td colspan=\"".$rows."\" align=\"center\">".$tableheader."</td>\n</tr>\n";
}



function ConfirmPage($title, $params, $message) {
	global $Pivot_Vars;
	PageHeader($title, 1);
	if($Pivot_Vars['func'] == 'admin') {
		StartForm($Pivot_Vars['do']);
	}else{
		StartForm($Pivot_Vars['func']);
	}
	if(is_array($params)){
		$acount = (count($params)/2);
		for($i = 0; $i < $acount; $i++){
			$j = 1+(2*$i);
			echo '<input type="hidden" name="'.$params[$j-1].'" value=\''.$params[$j].'\' />'."\n";
			//echo '<br />(input type="hidden" name="'.$params[$j-1].'" value=\''.$params[$j].'\')'."\n";
		}
	}
	echo '<div style="padding:20px 10px"><input type="hidden" name="confirmed" value="1" />'."\n";
	Paragraph( wordwrap($message,80,"<br />").'<br /><br /><br /><input type="submit" value="Continue" class="button" />');
	echo '</div></form>'."\n";
	PageFooter();
	exit;
}



function DispPage($toshow = '', $screenname=''){
	global $Pivot_Vars, $theme_icon;

	if(is_array($toshow)){

		echo "<table  width='95%'  border='0' cellpadding='5' cellspacing='0'>\n";

		$cnt = count($toshow);
		for($i = 0; $i < $cnt; $i++){

			// Make the link..
			if( (isset($Pivot_Vars['func']) && $Pivot_Vars['func']=='admin') ||
			(isset($Pivot_Vars['menu']) && $Pivot_Vars['menu']=='admin') ) {
				$links = array('func' => 'admin', 'do' => $toshow[$i][1]);
			}else{
				$links = array('func' => $toshow[$i][1]);
			}

			if(isset($toshow[$i][3])) {
				$links = array('menu' => $toshow[$i][3]);
			}

			$link = MakeLink($links);

			// Print the icon..
			echo "<tr><td width='48' valign='top' align='center'>\n";
			print_icon($screenname, $toshow[$i][1], $link);
			echo "</td><td><h3>";


			echo $link;


			echo $toshow[$i][0];
			echo "</a></h3>\n<p class='dim'>";
			// Edit the comments that were posted to this entry.
			echo $toshow[$i][2];
			echo "</p></td>\n";
			echo "</tr>\n\n";
		}
		echo "</table>\n\n";

	}
}

function print_icon($cat, $icon, $link='') {

	global $theme_icon;


	// get the icon image..
	$replace_from= array("ufield_edit&amp;field=", "cat_edit&amp;cat=", "blog_edit1&amp;blog=", "templates&amp;create=true");
	$replace_to= array("", "", "", "d");

	$iconname = strtolower(str_replace($replace_from, $replace_to, $icon));

	//debug($cat ."+".$iconname."+");

	if ( (isset($theme_icon[$cat][$iconname])) &&
	(file_exists($theme_icon[$cat][$iconname])))  {
		$icon = $theme_icon[$cat][$iconname];
	} else {

		if ($iconname == "") {
			// if empty
			$icon = "pics/nix.gif";
		}	else if ($cat == "weblogs")  {
			// default category icon..
			$icon = $theme_icon['weblogs']['d'];
		}	else if ($cat == "category")  {
			// default linkdump icon..
			$icon = $theme_icon['category']['d'];
		}	else if ($cat == "del_blog")  {
			// delete weblog icon..
			$icon = $theme_icon['del_blog']['d'];

		} else {
			$icon = $theme_icon['default'];
		}

	}

	list ($width, $height) = getimagesize($icon);
	printf("%s", $link);
	printf("<img src='%s' width='%s' height='%s' vspace='3' "
	.	" alt='%s - %s' style='border: 0px;' /></a>\n", $icon, $width, $height, $cat, $iconname);

}


function EndForm($button='Submit', $special='0', $text='') {
	
	if ($text!='') {
		$add = "<tr>\n<td colspan='2'>$text</td>\n</tr>\n\n";
	} else {
		$add = "";
	}
	
	if($special == 1) {
		echo "<tr>\n<td>&nbsp;</td><td align='right'><input type='submit' value='".$button."' class='button' /></td>\n</tr>\n</table>\n\n</form>";
		echo $add;
	} else if($special == 2) {
		echo "<tr>\n<td>&nbsp;</td><td colspan='2' align='right'><input type='submit' value='".$button."' class='button' /></td>\n</tr>\n";
		echo $add;		
	} else {
		echo '<input type="submit" value="'.$button.'" class="button" /></form>';
	}
}




function Paragraph($text=''){
	echo '<div class="info">'.$text.'</div>';
}


function MidHeading($title='') {
	echo '<h2>'.$title.'</h2>';
}


function DisplaySettings($settingsArray, $cfgVar='Cfg', $pertytable=0) {
	if(is_array($settingsArray)){
		$arraycount = count($settingsArray);
		for($i = 0; $i < $arraycount; $i++){
			/* echo '<b>' . $settingsArray[$i][2] . '</b>'; */
			GenSetting($settingsArray[$i][0], $settingsArray[$i][1], $settingsArray[$i][2], $settingsArray[$i][3], $settingsArray[$i][4], $settingsArray[$i][5], $settingsArray[$i][6], $cfgVar);
		}
	}else{
		//my god you are an idiot for not sending an array.
	}
}

/*
GenSetting() was updated by Mark on 02/10/2003, though you shouldn't notice it at all. Basically
I moved each display function to its own function, so that you can create fields without having
to use the exact format of a normal settings table (useful for submit, modify, etc.)

This is so that we can use an array to display forms.

function prototype:
	GenSetting(
		$fieldname='none',      // fieldname: the internal variable, *** must follow variable naming conventions.
		$dispname='none given', // dispname is what is shown.
		$description='',        // description: ignored if a value isn't set for it.
		$type='0',              // SEE BELOW
		$defvalue='',           // defvalue: only used when no variable currently exists for it.
		$length='30',
		$additional='',         // additional: setting things like max length, height, disabled, or whatever else may be in the opening tag.
		$cfgvar='Cfg'
		)
// colons (:) and question marks (?) are added appropriately to display names. ??? JM

// TYPE
0 - texfield
GenSetting('time_offset', 'Time Offset', 'This setting will determine how blah blah..', 0, '0', '30', 'maxlength=\"2\"');
1 - password
GenSetting('pass1', 'Password (Verify)', 'This setting will determine how blah blah..', 1, '0', '20', 'maxlength=\"10\"');
2 - checkbox
*we can leave length and additional blank because it leaves them out anyways.
GenSetting('disable', 'Disable This?', 'This setting will determine how blah blah..', 2, '0', '', '');
3 - dropdown
a) yes/no [enable/disable]
	GenSetting( 'wysiwyg','Use WYSIWYG?','This setting will determine how blah blah..',3,'yn','20','' );
b) anything goes (first/current is selected)
	$archive = array( 'Day','day','Week','week','Bi-Weekly','biweekly','Month','month' );
	// First is display, second is value for variable (archive in below example)
	GenSetting( 'archive','Archive by..','This setting will determine how blah blah..','3',$archive,'30','' );
4 - selectbox - (really a dropdown with height)
	* the multiple attribute it there by default, as otherwise it's assumed you'd use 'dropdown' [3]
	$allowed = array( 'User1','user1','User2','user2','User3','user3','User4','user4' );
	// First is display, second is variable. Seeing if it's selected is done in this function.
	// length = height for this.
	GenSetting( 'usersallowed','Users allowed to post:','The people chosen here will be able to post in this category.','4',$allowed,'30','' );
5 - textarea
	* additional should have 'height=\"30\"' or something.
	GenSetting( 'profile','Your Public Profile',,'What you put here will be shown to anyone who can view your profile.',5,'I have nothing to say to you.','60','height=\"35\"' );
6 - radios
	// First one is the name, rest are values.
	$archive = array( 'Day','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday' );
	GenSetting( 'archive','Archive each','This setting will determine how blah blah..','6',$archive,'30','' );
7 - hidden
	* done exactly like textfield.
	// any description and length will not be used for obvious reasons.
	GenSetting( 'time_offset','Time Offset','This setting will determine how blah blah..',0,'0','30','maxlength=\"2\"' );
8 - header - a header in the middle of a table
	//the '2' is colspan
	GenSetting( '','Header','',,'8','2' );
	
*/


function GenSetting($fieldname='none', $dispname='none given', $description='', $type='0', $defvalue='', $length='', $additional='', $cfgvar='Cfg') {
	global $$cfgvar, $lang;

	// set the default for length..
	if ($length=="") { $length = 40; }
	
	if($type < 7) {
		$settingdisp = "<tr>\n<td width='25%'  valign='top'><b class='tabular' style='padding-right: 7px;'>".$dispname.":</b></td>\n
		<td width='75%'>";
	}	else {
		$settingdisp = "";
	}
	// we do other stuff in the case's themselves because it's just easier.
	switch($type) {
		case 0:
		$settingdisp .= GenTextfield($fieldname, $length, $additional, $defvalue, $cfgvar);
		break;
		case 1:
		$settingdisp .= GenPassfield($fieldname, $length, $additional, $cfgvar);
		break;
		case 2:
		$settingdisp .= GenCheckbox($fieldname, $additional, $defvalue, $cfgvar);
		break;
		case 3:
		$settingdisp .= GenDropdown($fieldname, $additional, $defvalue, $cfgvar);
		break;
		case 4:
		$settingdisp .= GenSelectbox($fieldname, $length, $defvalue, $cfgvar);
		break;
		case 5:
		$settingdisp .= GenTextarea($fieldname, $length, $additional, $defvalue, $cfgvar);
		break;
		case 6:
		$settingdisp .= GenRadios($fieldname, $defvalue, $cfgvar);
		break;
		case 7:
		$settingdisp .= GenHidden($fieldname, $additional, $defvalue, $cfgvar);
		break;
		case 8:
		$settingdisp = "<tr>\n<td class='sHeading' colspan='".$length."'><hr size='1' noshade='noshade' />\n<h2 style='margin-left:0px;'>".$dispname."</h2>\n</td>\n</tr>\n";
		break;

	}
	// JM - just for checkboxes...
	if( 2==$type ) {
		$settingdisp .= "</td>\n</tr>\n";
		if( ''!=$additional ) {
			$settingdisp = str_replace( '<tr>','<tr '.$additional.'>',$settingdisp );
		}
		// perhaps add the line with the description.
		if(strlen($description) > strlen($dispname)){
			$settingdisp .= "<tr>\n<td width='85%' colspan='2' style='padding-left: 18%; padding-bottom: 7px;'>".str_replace("&nbsp;", " ", $description) ."</td>\n</tr>\n";
		}
		
	} else if( $type < 7 ) {
		$settingdisp .= "</td>\n</tr>\n";

		// perhaps add the line with the description.
		if(strlen($description) > strlen($dispname)){
			$settingdisp .= "<tr>\n<td width='85%' colspan='2' style='padding-left: 18%; padding-bottom: 7px;'>".str_replace("&nbsp;", " ", $description) ."</td>\n</tr>\n";
		}
	}
	echo $settingdisp;
}

function GetDefValue($fieldname, $defvalue, $cfgvar){
	global $$cfgvar;
	if(isset(${$cfgvar}[$fieldname])){
		return ${$cfgvar}[$fieldname];
	}else{
		return $defvalue;
	}
}

function GenTextfield($fieldname, $length, $additional, $defvalue, $cfgvar){

	$defvalue = GetDefValue($fieldname, $defvalue, $cfgvar);

	return '<input type="text" name="'.$fieldname.'" value="'.Stripslashes(Htmlspecialchars($defvalue)).'" size="'.$length.'"'. $additional.' class="input" />';
}

function GenPassfield($fieldname, $length, $additional, $cfgvar){
	$defvalue = GetDefValue($fieldname, $defvalue, $cfgvar);
	return '<input type="password" name="'.$fieldname.'" value="'.$defvalue.'" size="'.$length.'"'. $additional.' class="input" />';
}

function GenCheckbox($fieldname, $additional, $defvalue="", $cfgvar) {
	if ($defvalue=="") {
		$defvalue = GetDefValue($fieldname, $defvalue, $cfgvar);
	}
	return '<input type="checkbox" name="'.$fieldname.'"'.(($defvalue == 1) ? ' checked="checked"' : '').' '.$additional.' />';
}

function GenDropdown($fieldname, $additional, $defvalue, $cfgvar) {
	global $lang, $$cfgvar;

	if($defvalue=='yn'){
		$disp = '<select name="'.$fieldname.'" '.$additional.' class="input">';
		if(isset(${$cfgvar}[$fieldname])){
			$temp['yes'] = ((${$cfgvar}[$fieldname] == 1) ? " selected='selected'" : '');
			$temp['no'] = ((${$cfgvar}[$fieldname] == 0) ? " selected='selected'" : '');
		}elseif((!isset(${$cfgvar}[$fieldname])) && (isset($additional))){
			$temp['yes'] = (($additional == 1) ? " selected='selected'" : '');
			$temp['no'] = (($additional == 0) ? " selected='selected'" : '');
		}
		$disp .= '<option value="1" '.$temp['yes'].'>'.lang('yes').'</option><option value="0" '.$temp['no'].'>'.lang('no').'</option>';

	}elseif(is_array($defvalue)){
		$acount = (count($defvalue)/2);
		if(is_array($additional)){
			$checkagainst = $additional[0];
			$realadditional = $additional[1];
		}else{
			$checkagainst = $additional;
			//debug("checkagainst1: ". $checkagainst ." - ". $fieldname );
		}
		$disp = '<select name="'.$fieldname.'" '.$realadditional.'>';

		if(isset(${$cfgvar}[$fieldname])) {

			$checkagainst = ${$cfgvar}[$fieldname];
			//debug("checkagainst2: ". $checkagainst  );
		}

		// and now, a nasty hack, to allow for proper editing of subweblogs
		if (strpos($fieldname, '[template]') > 1 ) {
			//debug("checkagainst3: $cfgvar ". $checkagainst  );
			$checkagainst = ${$cfgvar}['sub_weblog'][$checkagainst]['template'];
			//debug("checkagainst3: $cfgvar ". $checkagainst  );
		}

		for($i = 0; $i < $acount; $i++){
			$j = 1+(2*$i);

			$sel = ($checkagainst == $defvalue[$j]) ? " selected='selected'" : '';
			//debug("is: $checkagainst == ".$defvalue[$j]." : $sel");
			$disp .= '<option value="'.$defvalue[$j].'" '.$sel.'>'.$defvalue[$j-1].'</option>'."\n";
		}
	}

	$disp .= '</select>'."\n";
	return $disp;
}


function GenSelectbox($fieldname, $length, $defvalue, $cfgvar) {
	$selected = GetDefValue($fieldname, $defvalue, $cfgvar);
	foreach(explode('|', $selected) as $i => $value){
		$craparray[$value] = TRUE;
	}
	$disp = '<select name="'.$fieldname.'[]" size="'.$length.'" multiple="multiple" >';
	for($i = 0; $i < (count($defvalue)/2); $i++){
		$j = 1+(2*$i);
		$disp .= '<option value="'.$defvalue[$j].'"'.(($craparray[$defvalue[$j]] == TRUE) ? " selected='selected'" : '').'>'.$defvalue[$j-1].'</option>';
	}
	return $disp .= '</select>'."\n";
}


function GenTextarea($fieldname, $length, $additional, $defvalue, $cfgvar){
	$defvalue = GetDefValue($fieldname, $defvalue, $cfgvar);
	return '<textarea name="'.$fieldname.'" id="'.$fieldname.'" cols="'.$length.'" '.$additional.' class="resizable" style="height: 120px;">'.$defvalue.'</textarea>';
}

function GenRadios($fieldname, $defvalue, $cfgvar){
	$selected = GetDefValue($fieldname, '', $cfgvar);
	$acount = count($defvalue);
	for($i = 1; $i < $acount; $i++){
		$disp .= '<input type="radio" name="'.$defvalue[0].'" value="'.$defvalue[$i].'"'.(($selected == $defvalue[$i]) ? ' selected' : '').'>'.$defvalue[$i].'<br />';
	}
	return $disp;
}

function GenHidden($fieldname, $additional, $defvalue, $cfgvar){
	$defvalue = GetDefValue($fieldname, $defvalue, $cfgvar);
	return '<input type="hidden" name="'.$fieldname.'" value="'.$defvalue.'" '. $additional.' />';
}
?>
