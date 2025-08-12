<?php
//  +---------------------------------------------------------------------------+
//  | netjukebox, Copyright © 2001-2005  Willem Bartels                         |
//  |                                                                           |
//  | info@netjukebox.nl                                                        |
//  | http://www.netjukebox.nl                                                  |
//  |                                                                           |
//  | This file is part of netjukebox.                                          |
//  | netjukebox is free software; you can redistribute it and/or modify        |
//  | it under the terms of the GNU General Public License as published by      |
//  | the Free Software Foundation; either version 2 of the License, or         |
//  | (at your option) any later version.                                       |
//  |                                                                           |
//  | netjukebox is distributed in the hope that it will be useful,             |
//  | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
//  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
//  | GNU General Public License for more details.                              |
//  |                                                                           |
//  | You should have received a copy of the GNU General Public License         |
//  | along with this program; if not, write to the Free Software               |
//  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
//  +---------------------------------------------------------------------------+



//  +---------------------------------------------------------------------------+
//  | header.inc.php                                                            |
//  +---------------------------------------------------------------------------+
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>netjukebox - the flexible media share</title>
	<meta name="robots" content="noindex, follow">
	<meta name="author" content="Willem Bartels">
	<link href="<?php echo $cfg['css']; ?>/styles.css" rel="stylesheet" type="text/css">
	<link href="favicon.ico" rel="shortcut icon">
	<link href="favicon.ico" rel="icon">
</head>
<body>
<script src="javascript/overlib.js" type="text/javascript"></script>
<script src="javascript/overlib_cssstyle.js" type="text/javascript"></script>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<table class="fullscreen">
<tr <?php if (isset($cfg['header']) && $cfg['header'] == 'align') echo 'align="center" valign="middle"'; else echo 'valign="top"'; ?>>
	<td><iframe name="dummy" id="dummy" width="0" height="0" frameborder="0"></iframe>
<!-- ---- end header ---- -->
