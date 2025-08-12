<?php

/***************************************************************************

 settings.php
 -------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

$EzAdmin_Style["settingAuthor"]			= 'Lenny';
$EzAdmin_Style["styleName"]				= 'Pixel';

$EzAdmin_Style["adminmenuwidth"]		= 180;				// Width for the admin menu frame
$EzAdmin_Style["adminsubmenuoffset"]	= 5;				// Horizontal offset for sub-menu entries

$EzAdmin_Style["menubutton"]			= '';		// Menu Button image template file name (without the file extension)
$EzAdmin_Style["menubuttonMethod"]		= '';																// This file must exist in the style subdirectory as either gif, jpg or png
$EzAdmin_Style["menubuttonFont"]		= 'Arial';			// Menu button font face
$EzAdmin_Style["menubuttonFontSize"]		= 14;			// Menu button font size
$EzAdmin_Style["menubuttonAlign"]		= 'left';			// Menu button text alignment ('left', 'right' or 'center')
$EzAdmin_Style["menubuttonValign"]		= 'middle';			// Menu button text vertical alignment ('top', 'bottom' or 'middle')
$EzAdmin_Style["menubuttonTextColour"]		= '#FFFFFF';	// Must be an RGB numeric value, prefixed with '#'
$EzAdmin_Style["menubuttonColour"]		= '#2975DE';		// menubuttoncolour, menubuttonborder and menubuttonbordercolour
$EzAdmin_Style["menubuttonBorder"]		= 0;			//    are used only if the script is unable to generate or even
$EzAdmin_Style["menubuttonBorderColour"]	= '#2975DE';		//    simulate a graphic button and forced to resort to text

$EzAdmin_Style["addbutton"]			= 'pixel/images/add_button';	// Add Button image template file name (without the file extension)
									// This file must exist in the style subdirectory as either gif, jpg or png
$EzAdmin_Style["addbuttonFont"]		= 'Arial';	// Add button font face
$EzAdmin_Style["addbuttonFontSize"]		= 12;		// Add button font size
$EzAdmin_Style["addbuttonAlign"]		= 'left';	// Add button text alignment ('left', 'right' or 'center')
$EzAdmin_Style["addbuttonValign"]		= 'middle';	// Add button text vertical alignment ('top', 'bottom' or 'middle')
$EzAdmin_Style["addbuttonTextColour"]		= '#666666';	// Must be an RGB numeric value, prefixed with '#'
//$EzAdmin_Style["addbuttonColour"]		= '#F2F2F2';	// addbuttoncolour, addbuttonborder and addbuttonbordercolour
$EzAdmin_Style["addbuttonBorder"]		= 0;		//    are used only if the script is unable to generate or even
								//    simulate a graphic button and forced to resort to text

//  Path for button icons is relative to the style subdirectory
$EzAdmin_Style["FirstIcon"]			= 'images/first_button.gif';
$EzAdmin_Style["PrevIcon"]			= 'images/prev_button.gif';
$EzAdmin_Style["NextIcon"]			= 'images/next_button.gif';
$EzAdmin_Style["LastIcon"]			= 'images/last_button.gif';
$EzAdmin_Style["FolderIcon"]			= 'images/folder_button.gif';
$EzAdmin_Style["EditIcon"]			= 'images/edit_button.gif';
$EzAdmin_Style["DeleteIcon"]			= 'images/del_button.gif';
$EzAdmin_Style["ViewIcon"]			= 'images/view_button.gif';
$EzAdmin_Style["ToggleIcon"]			= 'images/rel_button.gif';
$EzAdmin_Style["CatIcon"]			= 'images/cat_button.gif';
$EzAdmin_Style["UpIcon"]			= 'images/up.gif';
$EzAdmin_Style["DownIcon"]			= 'images/down.gif';
$EzAdmin_Style["ShutDown"]			= 'images/shutdown.gif';
?>
