<?php
/***************************************************************************
					_config.php
					------------
	product			: HTMLeditbox
	version			: 2.2
	released		: Tue Oct 2 2003
	copyright		: Copyright © 2001-3 Labs4.com
	email			: support@labs4.com
	website			: http://www.labs4.com

***************************************************************************/
/* This is main configuration file, 
   settings can be overiden from init link ... for detail see docs ********
***************************************************************************/

/**************************************************************************
Script version and copyright information
***************************************************************************/
$settings[version] = "*********";
$settings[build] = 1;
$settings[copyright] = "&copy;&nbsp;2001-3 ********";
$settings[product_name] = "*********";

/**************************************************************************
Language settings
***************************************************************************/
$settings[language] = "english";

/**************************************************************************
Security settings - to switch on, set to one
***************************************************************************/
$settings[security] = "0";
$settings[password] = "password";

/**************************************************************************
Editor appearance settings
***************************************************************************/
$settings[bgcolor] = "#FFF3BD";
$settings[bgcolor2] = "#F5F5F5";
$settings[border_color] = "#000000";

$settings[textcolor1] = "#222222";
$settings[textcolor2] = "#999999";
$settings[textcolor3] = "#CE0000";


/**************************************************************************
Default editor options, can be overriden from initial link (use wizard)
***************************************************************************/
$option[1] = 1;		// 1. Local image selector  0 - 0ff / 1 - On
$option[2] = 1;		// 2. Table functions  0 - Off / 1 - On
$option[3] = 1;		// 3. File Functions  0 - Off / 1 - On
$option[4] = 1;		// 4. Color Picker  0 - Off / 1 - On
$option[5] = 1;		// 5. Font Settings  0 - Off / 1 - On
$option[6] = 0;		// 6. Relative Paths 0 - Off / 1 - On
$option[7] = "";	// 7. Cascade Style Sheet - filename with path

/**************************************************************************
to disable HTML/WYSIWYG button set this variable to 1
***************************************************************************/
$settings[disable_html_view] = 0;


/**************************************************************************
Path to root image directory (no trailing slash)
***************************************************************************/
$settings[images_root] = "./images/articles";

/**************************************************************************
Maximum image size in bytes - example: 100000 = 100kB
***************************************************************************/
$settings[max_img_size] = "2500000";

/**************************************************************************
Approved image file types ... to add another file type,
create another line with $img_file_types[x] where x is next number in a row
***************************************************************************/
$settings[img_file_types][1] = "image/jpeg";
$settings[img_file_types][2] = "image/pjpeg";
$settings[img_file_types][3] = "image/gif";
$settings[img_file_types][4] = "image/png";


/**************************************************************************
Path to root file directory - if used (no trailing slash)
- if you want to list files from root add dot "." but remember that script 
  supports only one level of subdirectories!
***************************************************************************/
$settings[files_root] = "./files";

/**************************************************************************
Maximum file size in bytes - example: 100000 = 100kB
***************************************************************************/
$settings[max_file_size] = "2500000";

/**************************************************************************
Approved file extensions for file listing ... to add another extension,
create another line with $mime_file_ext[x] where x is next number in row
if you want narrow file type selection just uncomment unwanted extensions
***************************************************************************/
$settings[mime_file_ext][1] = ".html";
$settings[mime_file_ext][2] = ".htm";
$settings[mime_file_ext][3] = ".asc";
$settings[mime_file_ext][4] = ".txt";
$settings[mime_file_ext][5] = ".jpeg";
$settings[mime_file_ext][6] = ".asc";
$settings[mime_file_ext][7] = ".jpeg";
$settings[mime_file_ext][8] = ".jpg";
$settings[mime_file_ext][9] = ".gif";
$settings[mime_file_ext][10] = ".png";
$settings[mime_file_ext][11] = ".js";
$settings[mime_file_ext][12] = ".pdf";
$settings[mime_file_ext][13] = ".ai";
$settings[mime_file_ext][14] = ".eps";
$settings[mime_file_ext][15] = ".ps";
$settings[mime_file_ext][16] = ".doc";
$settings[mime_file_ext][17] = ".hqx";
$settings[mime_file_ext][18] = ".tar";
$settings[mime_file_ext][19] = ".bin";
$settings[mime_file_ext][20] = ".uu";
$settings[mime_file_ext][21] = ".exe";
$settings[mime_file_ext][22] = ".rtf";
$settings[mime_file_ext][23] = ".rar";
$settings[mime_file_ext][24] = ".zip";
$settings[mime_file_ext][25] = ".wav";
$settings[mime_file_ext][26] = ".au";
$settings[mime_file_ext][27] = ".snd";
$settings[mime_file_ext][28] = ".mpeg";
$settings[mime_file_ext][29] = ".mpg";
$settings[mime_file_ext][30] = ".mp3";
$settings[mime_file_ext][31] = ".qt";
$settings[mime_file_ext][32] = ".mov";
$settings[mime_file_ext][33] = ".avi";

/**************************************************************************
Font sizes setting - is size in points?
***************************************************************************/
$settings[font_size_type] = "num";		// pt or num

/**************************************************************************
Font sizes (numeric)
***************************************************************************/
$settings[font_size_num][1] = 1;
$settings[font_size_num][2] = 2;
$settings[font_size_num][3] = 3;
$settings[font_size_num][4] = 4;
$settings[font_size_num][5] = 5;
$settings[font_size_num][6] = 6;
$settings[font_size_num][7] = 7;

/**************************************************************************
Font sizes (points) - due to ActiveX limitation size cannot be higher than 16
***************************************************************************/
$settings[font_size_pt][1] = 8;
$settings[font_size_pt][2] = 9;
$settings[font_size_pt][3] = 10;
$settings[font_size_pt][4] = 11;
$settings[font_size_pt][5] = 12;
$settings[font_size_pt][6] = 13;
$settings[font_size_pt][7] = 14;
$settings[font_size_pt][8] = 15;
$settings[font_size_pt][9] = 16;

/**************************************************************************
End of configuration file
***************************************************************************/
?>
