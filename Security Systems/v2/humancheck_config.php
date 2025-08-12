<?PHP
#############################################
#	Project:	HumanCheck 2.1
#	file:		humancheck_config.php
#	company:	SmiledSoft.com (SmiledSoft.com)
#	author:		Yuriy Horobey (yuriy@horobey.com)
#	purpose: 	this is different configuration settings
#	date: 24.08.2004
#
#############################################


	$config_max_digits	=	5;	//how many digits should be in secretcode?
	$config_back_image	=	"backgroundimage.jpg"; // background image over which secret code will be shown
	//it must be jpeg
	$config_font		=	100; //size of the font to print the secret code. Must fit the image. 
	//this is not implemented yet, however from 1 to 5? it makes some slight changes to the font
	$config_code_color	=	"FF7700"; //real color will not be exactly as this code, but closest existing in image palete
?>