<?  ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
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
## File: include/html_form.inc
## Desc: Functions for the quick and easy creation of form elements in HTML
## $Source: /home/cvsroot/mysource/include/html_form.inc,v $
## $Revision: 2.9 $
## $Author: csmith $
## $Date: 2003/05/16 05:17:58 $
#######################################################################
# include the more generic functions
global $SQUIZLIB_PATH;
include_once("$SQUIZLIB_PATH/html_form/html_form.inc");
#---------------------------------------------------------------------#

   ######################################################################################
  # Given a name, an array of keys and values, and a default value, prints a combo box #
 #  This is specifically for the date_box function
######################################################################################
# LEGACY!! Someone get rid of this

function combo_box_date($name='', $contents='', $default='', $extras='') {
	$string =" <SELECT NAME=\"$name\" $extras>";
	while(list($key, $value) = each($contents)) {
		if($value[0] == $default) $string .="<OPTION value=\"".$value[0]."\" SELECTED>". $value[1];
		else			          $string .="<OPTION value=\"".$value[0]."\">" . $value[1];
	}
	$string .= "</SELECT>";
	return $string;
}

  ##########################################################################################
 # Prints an entry box for a colour picker, requires javascript and colour_picker script  #
##########################################################################################

function colour_box($name='', $default='', $allow_blanks=false, $path_to_js_functions='', $button_text='*', $field_type='text', $show_current_colour=true, $show_change_colour=true) {
	static $initialised = false;
	$default = strtolower($default);

	$str = '';

	global $EDIT_DIR;

	if (!$initialised) {
		$str = "<script language=\"JavaScript\" src=\"$EDIT_DIR/colour_picker.js\"></script>";
		$str .= "<script language=\"JavaScript\">var colour_change_image_dir='$EDIT_DIR/images/'</script>";
		$initialised = true;
	}#end if

	$allow_blanks_str = ($allow_blanks) ? "true" : "false";
	$path_to_js_functions .= ($path_to_js_functions) ? "." : "";
	if ($show_current_colour) $str.= '<span id="colour_span_'.$name.'" style="background-color:'.$default.';">'.str_repeat('&nbsp;', 10).'</span>&nbsp;';
	$str .= "<input type=$field_type name=\"$name\" id=\"colour_box_$name\" value=\"$default\" onchange=\"Javascript: value = ".$path_to_js_functions."check_colour(value, $allow_blanks_str);\" ";
	if ($show_current_colour) $str.= "show_colour_change('$name');\" ";
	$str.= "size=7 maxlength=6 style=\"font-family=courier\"><input type=button value=\"&nbsp;$button_text&nbsp;\" onclick=\"".$path_to_js_functions."load_colour_picker(form.elements['".addslashes($name)."'], '$EDIT_DIR')\" style=\"font-family=courier\">";
	if ($show_change_colour) $str .= '&nbsp;<img id="colour_change_'.$name.'" name="colour_change_'.$name.'" src="'.$EDIT_DIR.'/images/cross.gif" width="15" height="15" border="0" align="bottom">';
	return $str;
}


   #########################################################################################################
  # Takes a date in MySQL string format or DDMMYYYY and draws a set of selection boxes using it as default#
 # This requires certain javascript functions to be printed in the header of the HTML, see statistics.act#
#########################################################################################################

function date_box($name='', $default='', $form_name='', $start_year='', $show_time=false, $onchange='') {
	static $initialised = false;

	if (!$initialised) {
		?><script language="JavaScript" src="<?=lib_href("js/check_date.js")?>"></script><?
		$initialised = true;
	}#end if

	#calc the start and end years
	if (intval($start_year) < 1900) $start_year = 1998;
	$dateinfo = getdate(time());
	$end_year = $dateinfo['year'] + 10;
	if ($end_year < $start_year)
		$end_year = $start_year + 10;

	$default_day     = substr($default, 8,2);
	$default_month   = substr($default, 5, 2);
	$default_year    = substr($default, 0, 4);
	#$default_century = substr($default, 0, 2);

	if($show_time) {
		$default_hour	= substr($default,11,2);
		$default_min	= substr($default,14,2);
	}

	$replacements = Array("day_name" => "day_$name", 
						"month_name" => "month_$name", 
						"year_name" => "year_$name",
						"hour_name" => "hour_$name", 
						"min_name" => "min_$name");
	$onchange = replace_keywords($onchange, $replacements);

	$check_html = "ONCHANGE=\"javascript:check_date('$form_name', '$name', ".(($show_time)?"true":"false").",true);$onchange\"";
	$string  = "<INPUT TYPE=hidden NAME=\"$name\" VALUE=\"$default\">";

	$days[] = array("00","-");
	for ($i = 1; $i <= 31; $i++)
		$days[] = array(sprintf("%02d", $i), $i);

	$string .= combo_box_date("day_$name", $days, $default_day, $check_html);

	$months[] = array("00","-");
	for ($i = 1; $i <= 12; $i++)
		$months[] = array(sprintf("%02d", $i), date("M",mktime(5,5,5,$i,5,2000)));
	$string .= combo_box_date("month_$name", $months, $default_month, $check_html);

	$years[] = array("0000","-");
	for ($i = $start_year; $i <= $end_year; $i++)
		$years[] = array(sprintf("%02d", $i), $i);
	$string .= combo_box_date("year_$name", $years, $default_year, $check_html);

	if($show_time) {
		for($i = 0; $i <= 23; $i++)
			$hours[] = array(sprintf("%02d", $i), sprintf("%02d", $i));
		$string .= combo_box_date("hour_$name",$hours, $default_hour, $check_html)."<span class=fineprint>hour</span>";

		for($i = 0; $i <= 59; $i++)
			$mins[] = array(sprintf("%02d", $i), sprintf("%02d", $i));
		$string .= combo_box_date("min_$name",$mins, $default_min, $check_html)."<span class=fineprint>min</span>";
	}

	#$string .= "<INPUT TYPE=text SIZE=2 MAXLENGTH=2 NAME=\"year_$name\" VALUE=$default_year ONCHANGE=\"check_date('$form_name', '$name')\">";
	return $string;
}

?>