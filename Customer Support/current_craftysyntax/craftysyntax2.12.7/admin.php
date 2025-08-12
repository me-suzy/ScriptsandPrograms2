<?php
//===========================================================================
//* --    ~~                Crafty Syntax Live Help                ~~    -- *
//===========================================================================
//           URL:   http://www.craftysyntax.com/    EMAIL: ericg@craftysyntax.com
//         Copyright (C) 2003-2005 Eric Gerdes   (http://www.craftysyntax.com )
// --------------------------------------------------------------------------
// $              CVS will be released with version 3.1.0                   $
// $    Please check http://www.craftysyntax.com/ or REGISTER your program for updates  $
// --------------------------------------------------------------------------
// NOTICE: Do NOT remove the copyright and/or license information any files. 
//         doing so will automatically terminate your rights to use program.
//         If you change the program you MUST clause your changes and note
//         that the original program is Crafty Syntax Live help or you will 
//         also be terminating your rights to use program and any segment 
//         of it.        
// --------------------------------------------------------------------------
// LICENSE:
//     This program is free software; you can redistribute it and/or
//     modify it under the terms of the GNU General Public License
//     as published by the Free Software Foundation; 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program in a file named LICENSE.txt .
// --------------------------------------------------------------------------  
// BIG NOTE:
//     At the time of the release of this version of CSLH, Version 3.1.0 
//     which is a more modular, extendable , skinable version of CSLH
//     was being developed.. please visit http://www.craftysyntax.com to see if it was released! 
//===========================================================================
require_once("admin_common.php");
validate_session($identity);

$mydatabase->close_connect();

if(!(isset($UNTRUSTED['help']))){ $UNTRUSTED['help'] = ""; }
if(!(isset($UNTRUSTED['page']))){ $UNTRUSTED['page'] = "scratch.php"; }
if(!(isset($UNTRUSTED['tab']))){ $UNTRUSTED['tab'] = ""; }
?>
<title>CSLH ADMIN</title>
<frameset rows="58,*" border="0" frameborder="0" framespacing="0" spacing="0" NORESIZE=NORESIZE>
 <frame src="admin_options.php?sound=off&tab=<?php echo $UNTRUSTED['tab']; ?>" name="topofit" scrolling="no" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
 <frameset cols="220,*" border="0" frameborder="0" framespacing="0" spacing="0" NORESIZE=NORESIZE>
 <frame src="navigation.php" name="navigation" scrolling="AUTO" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
 <frame src="<?php echo $UNTRUSTED['page']; ?>?help=<?php echo $UNTRUSTED['help']; ?>" name="contents" scrolling="AUTO" border="0" marginheight="0" marginwidth="0" NORESIZE>
</frameset>