<?
// ---------------------------------------------------------------------------- //
// MyNewsGroups :) 'Share your knowledge'
// Copyright (C) 2002 Carlos Sánchez Valle (yosoyde@bilbao.com)

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------------------------- //

//------------------------------------------------------------------//
// include.php
// Author: Carlos Sánchez
// Created: 29/06/01
//
// Description: We include all the required files, classes and 
//  			libraries from this script.
//
//
//------------------------------------------------------------------//



// File and directories

$myng_dir['images']	= "./images"; // Necesario para la aplicacion
$myng_dir['styles']	= "./styles";
$myng_dir['lib']	= $myng_root."/lib";
$myng_dir['class']	= $myng_root."/class";
$myng_dir['include'] = $myng_root."/include";
//$myng_dir['themes']	= $myng_dir['prefix']."themes";
//$myng_dir['lang']	= $myng_dir['prefix']."lang";

$myng_file['nntp_lib']		= "nntp.lib.php";
$myng_file['standard_lib']	= "standard.lib.php";
$myng_file['stats_lib']	= "stats.lib.php";
$myng_file['action_lib']	= "action.lib.php";
$myng_file['login_class']	= "login.class.php";
$myng_file['extended_class']	= "extended.class.php";
$myng_file['templates']		= "template.inc.php";
$myng_file['db']			= "db_mysql.inc.php";
$myng_file['style']			= "style.css";
$myng_file['calendar_class'] = "calendar.class.php";

//--------------- Files to include in ALL the scripts -------------------------//
//
//

include($myng_dir['include']."/".$myng_file['db']);                  // Database abstraction class
include($myng_dir['include']."/".$myng_file['templates']);           // Template class
include($myng_dir['class']."/".$myng_file['login_class']);           // Login class
include($myng_dir['lib']."/".$myng_file['nntp_lib']);                // NNTP class
include($myng_dir['lib']."/".$myng_file['standard_lib']);            // Standard library
include($myng_dir['lib']."/".$myng_file['stats_lib']);               // Stats library
include($myng_dir['lib']."/".$myng_file['action_lib']);              // Action library
include($myng_dir['class']."/".$myng_file['calendar_class']);           // Login class


include($myng_dir['class']."/".$myng_file['extended_class']);        // Extended classes
//-----------------------------------------------------------------------------//


?>