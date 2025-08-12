<?php
/*------------------------------------------------------------------------------
CJG EXPLORER PRO v3.2 - WEB FILE MANAGEMENT - Copyright (C) 2003 CARLOS GUERLLOY
CJGSOFT Software
cjgexplorerpro@guerlloy.com
guerlloy@hotmail.com
carlos@weinstein.com.ar
Buenos Aires, Argentina
--------------------------------------------------------------------------------
This program is free software; you can  redistribute it and/or  modify it  under
the terms   of the   GNU General   Public License   as published   by the   Free
Software Foundation; either  version 2   of the  License, or  (at  your  option)
any  later version. This program  is  distributed in  the hope that  it  will be
useful,  but  WITHOUT  ANY  WARRANTY;  without  even  the   implied  warranty of
MERCHANTABILITY  or FITNESS  FOR A  PARTICULAR  PURPOSE.  See the  GNU   General
Public License for   more details. You  should have received  a copy of  the GNU
General Public License along  with this  program; if   not, write  to the   Free
Software  Foundation, Inc.,  59 Temple Place,  Suite 330, Boston,  MA 02111-1307
USA
------------------------------------------------------------------------------*/

include("all.php");

$IX=function_exists("posix_uname")?1:0; // indicates if unix for some defaults

//----------------------------------
// GENERAL CONFIGURATION
//----------------------------------
$usedocroot=1;			// Limit the explorer boundary to web server tree
$root="/";			// Virtual root directory for the explorer
$langdir="english";		// Language (under langs folder)
$show_banner=1;			// Banner (credits) frame
//----------------------------------

//----------------------------------
// TREE PANE
//----------------------------------
$treewidthauto=1;		// Auto resize tree frame
$treewidthmax=300;		// Maximum width for tree frame
$treewidthmin=100;		// Minimum width for tree frame

//----------------------------------
// FILES PANE
//----------------------------------
$alternateback=1;		// Show alternate colored background
$col_name=1;			// Show names column
$col_size=1;			// Show sizes column
$col_date=1;			// Show dates column
$col_type=0;			// Show types column
$col_perm=$IX;			// Show perms column
$col_owner=0;			// Show owner column
$col_group=0;			// Show group column
$datefull=0;			// Show full date (with time) or only day
$permsfull=0;			// Show full permission mask or only current ones
$previewfiles=1;		// Preview file contents (if $allow_view=1)

$frame_resize=1;		// Allow resizing frames
$allow_view=1;			// Allow to see file contents
$allow_exec=1;			// Allow to execute files
$allow_edit=1;			// Allow to edit files
$maxnamelength=30;		// Maximum length (in chars) to show out of file and folder names.
				// (if longer, "..." will be appended). The tooltip shows full name.
//----------------------------------

//----------------------------------
// SHOW/EDIT WINDOW
//----------------------------------
$shed_height=400;		// Window height
$shed_width=400;		// Window width
//----------------------------------

//----------------------------------
// FILESYSTEM FUNCTIONS
//----------------------------------
$allow_delete=1;		// Allow delete
$allow_copy=1;			// Allow copy
$allow_move=1;			// Allow move
$allow_create=1;		// Allow to create files
$allow_mkdir=1;			// Allow make directory
$allow_chmod=$IX;		// Allow change permissions
$allow_download=1;		// Allow download multiple files
$allow_upload=1;		// Allow upload files
$allow_tar=1;			// Allow archive files
$allow_tgz=1;			// Allow archive and compress files
$allow_zip=1;			// Allow zip files
$allow_zid=1;			// Allow zip and download files
$allow_find=1;			// Allow find files
$max_upload=32;			// Maximum number of files in a single upload operation
$max_upload_size=10000000;	// Maximum file size to upload (in bytes)
$max_deep_levels=4;		// Maximum deep level for recursive copy to prevent loops
//----------------------------------

//----------------------------------
// PANELS
//----------------------------------
$open_stats=0;			// Stats panel initially opened
$open_filefuncs=0;		// File functions panel initially opened
$open_transfer=0;		// Transfer panel initially opened
$open_preview=1;		// Preview panel initially opened
$open_board=0;			// Session history panel initially opened
//----------------------------------

//----------------------------------
// STYLE SCHEME
//----------------------------------
$bodyback="#FFFFFF";		// Background color 
$allback="ThreeDFace";		// Background for screen elements
$allfore="ButtonText";		// Foreground for screen elements
$allhigh="ThreeDHighlight";	// Highlight color
$alldark="ThreeDShadow";	// Shadow color
$errorback="#FF0000";		// Error message background color
$errorfore="#FFFFFF";		// Error message foreground color
$rowevenback="#DDDDDD";		// Even rows background color
$rowevenfore="#000000";		// Even rows foreground color
$rowoddback="#CCCCCC";		// Odd rows background color
$rowoddfore="#000000";		// Odd rows foreground color
$bodyfont="MS Sans Serif";	// Font family
$bodyfontsize="8px";		// Default font size
$prefontsize="12px";		// File dump font size
//----------------------------------

?>
