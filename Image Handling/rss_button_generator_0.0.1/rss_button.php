<?
##################################################################################################
#                          APPLICATION DETAILS AND LICENSING INFORMATION                         #
##################################################################################################
# SOFTWARE NAME: RSS Button Generator
# VERSION: 0.0.1
# FILENAME: rss_button.php
# AUTHOR: Nugen Software Inc.
# DETAILS: RSS Button Creation Functions
# URL: http://www.nugensoftware.com
##################################################################################################
#                THIS FILE IS OPEN SOURCE - PLEASE CREDIT THE ORIGINAL AUTHORS                   #
##################################################################################################

  //
 // CONFIGURATIONS
//
################################################################################################################
//Font Path - Server Level Path with trailing /
$FONT_PATH='/home/my_username/public_html/fonts/'; //(case sensitive)

//Font File - the True Type Font's filename
$FONT_FILE='my_font.ttf'; //(case sensitive)
################################################################################################################

  //
 // FUNCTIONS
//
################################################################################################################
function HexColor2RGB($HEX_CODE) {

//split the hex values
$T_R=substr($HEX_CODE, 0, 2);
$T_G=substr($HEX_CODE, 2, 2);
$T_B=substr($HEX_CODE, 4, 2);

//convert to values
$T_R=hexdec($T_R);
$T_G=hexdec($T_G);
$T_B=hexdec($T_B);

//create RGB array
$T_RGB['RED']=$T_R;
$T_RGB['GREEN']=$T_G;
$T_RGB['BLUE']=$T_B;

return $T_RGB;

}
################################################################################################################

################################################################################################################
function MakeRSSGraphic($LS_TEXT, $LS_FONTCOLOR, $LS_BACKGROUND, $RS_TEXT, $RS_FONTCOLOR, $RS_BACKGROUND, $VBAR_POS) {
	global $T_RSS_IMG, $FONT_PATH, $FONT_FILE;
	
	//set the image base background
	$RSS_COLORS['BASE_BACKGROUND']= imagecolorallocate($T_RSS_IMG,255,255,255); //white
	
	//border color
	$RSS_COLORS['BORDER']= imagecolorallocate($T_RSS_IMG,0,0,0); //black
	
	//create left side font color
	$T_RGB=HexColor2RGB($LS_FONTCOLOR);
	$RSS_COLORS['LS_FONTCOLOR']= imagecolorallocate($T_RSS_IMG,$T_RGB['RED'],$T_RGB['GREEN'],$T_RGB['BLUE']);
	
	//create left side background color
	$T_RGB=HexColor2RGB($LS_BACKGROUND);
	$RSS_COLORS['LS_BACKGROUND']= imagecolorallocate($T_RSS_IMG,$T_RGB['RED'],$T_RGB['GREEN'],$T_RGB['BLUE']);

	//create right side font color
	$T_RGB=HexColor2RGB($RS_FONTCOLOR);
	$RSS_COLORS['RS_FONTCOLOR']= imagecolorallocate($T_RSS_IMG,$T_RGB['RED'],$T_RGB['GREEN'],$T_RGB['BLUE']);
	
	//create right side background color
	$T_RGB=HexColor2RGB($RS_BACKGROUND);
	$RSS_COLORS['RS_BACKGROUND']= imagecolorallocate($T_RSS_IMG,$T_RGB['RED'],$T_RGB['GREEN'],$T_RGB['BLUE']);
	
	//make border
	imagerectangle ($T_RSS_IMG, 0, 0, 79, 14, $RSS_COLORS['BORDER']);
	
	//make left side background
	imagefilledrectangle ($T_RSS_IMG, 2, 2, ($VBAR_POS-4), 12, $RSS_COLORS['LS_BACKGROUND']);
	
	//make right side background
	imagefilledrectangle ($T_RSS_IMG, ($VBAR_POS-2), 2, 77, 12, $RSS_COLORS['RS_BACKGROUND']);
	
	//make left side text
	imagettftext($T_RSS_IMG, 10, 0, 4, 10, $RSS_COLORS['LS_FONTCOLOR'], $FONT_PATH . $FONT_FILE, $LS_TEXT);	
	
	//make right side text
	imagettftext($T_RSS_IMG, 10, 0, $VBAR_POS, 10, $RSS_COLORS['RS_FONTCOLOR'], $FONT_PATH . $FONT_FILE, $RS_TEXT);	
	
	//return image
	return $T_RSS_IMG;

}
################################################################################################################

  //
 // CODE
//
################################################################################################################
if ($_GET['MAKE_GRAPHIC']==true) {
	header ("Content-type: image/png"); //set the meta content type
	$T_RSS_IMG= imagecreatefrompng('base_image.png'); //open the base image
	
	//render image
	MakeRSSGraphic($_GET['LS_FONT_TEXT'], $_GET['LS_FONTCOLOR'], $_GET['LS_BACKGROUND'], $_GET['RS_FONT_TEXT'], $_GET['RS_FONTCOLOR'], $_GET['RS_BACKGROUND'], $_GET['VBAR']);
	
	imagepng($T_RSS_IMG); //write out png
} else {
	//do nothing
	echo 'error';
}
################################################################################################################
?>