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
//  | index.php                                                                 |
//  +---------------------------------------------------------------------------+
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Pragma: no-cache');// HTTP/1.0

require_once('include/globalize.inc.php');

$menu	= get('menu');
$get	= get();

if (!$menu) $menu = 'browse';
$main_url = $menu . '.php?';

foreach($get as $key => $value)
	{
	if ($key != 'menu')	$main_url .= $key . '=' . rawurlencode($value) . '&amp;';
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
	<title>netjukebox - live @ <?php echo $_SERVER['HTTP_HOST'];?></title>
	<meta name="robots" content="index, follow">	
	<meta name="description" content="netjukebox - live @ <?php echo $_SERVER['HTTP_HOST'];?>">
	<meta name="keywords" content="netjukebox, the flexible media share, live @ <?php echo $_SERVER['HTTP_HOST'];?>">
	<meta name="author" content="Willem Bartels">
	<link href="favicon.ico" rel="shortcut icon">
	<link href="favicon.ico" rel="icon">
	<script type="text/javascript">
		<!--
		if (screen.width < 1)	document.cookie="netjukebox_width=1024";
		else					document.cookie="netjukebox_width=" + screen.width;
		//-->
	</script>
</head>

<frameset rows="56,*" border="0">
	<frame src="menu.php?menu=<?php echo $menu; ?>" name="menu" id="menu" frameborder="0" scrolling="No" noresize>
	<frame src="<?php echo $main_url; ?>" name="main" id="main" frameborder="0" scrolling="Auto" noresize>
	<noframes>
		<body>
		netjukebox - live @ <?php echo $_SERVER['HTTP_HOST'];?><br>
		<a href="http://www.netjukebox.nl">netjukebox - the flexible media share</a>
		</body>
	</noframes>
</frameset>
</html>