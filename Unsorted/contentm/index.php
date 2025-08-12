<?php
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");	
	
	/*////////////////////////////////////////////////////////////
	
	iWare Professional 4.0.0
	Copyright (C) 2002,2003 David N. Simmons 
	http://www.dsiware.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	A COPY OF THE GPL LICENSE FOR THIS PROGRAM CAN BE FOUND WITHIN THE
	docs/ DIRECTORY OF THE INSTALLATION PACKAGE.

	/////////////////////////////////////////////////////////////*/
	
	@import_request_variables('cgps');
	$PHP_SELF=$_SERVER['PHP_SELF'];
	if(!isset($D)){$D=1;}
	if(!isset($V)){$V="";}
	$ClientLoader=1;
	include "admin/iware.php";
	$IW = new IWARE ();	
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo $IW->Client_GetMETATitle ($D); ?></title>
<meta name="Keywords" content="<?php echo $IW->Client_GetMETAKeywords ($D); ?>">
<meta name="Description" content="<?php echo $IW->Client_GetMETADescription ($D); ?>">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" type="text/css" href="skins/<?php echo $IW->Client_GetSkin (); ?>/styles.css"></link>
<script src="skins/<?php echo $IW->Client_GetSkin (); ?>/scripts.js"></script>
<?php include "navbar/". $IW->Client_GetNav () . "/scripts.inc"; ?>
</head>
<?php include "skins/".$IW->Client_GetSkin ()."/layout.php";?>
</html>