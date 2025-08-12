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
//  | ridirect.php                                                              |
//  +---------------------------------------------------------------------------+
require_once('include/initialize.inc.php');
authenticate('access_browse');
require_once('include/globalize.inc.php');
?>
<html>
<head>
<meta name="robots" content="noindex, nofollow">
<title>netjukebox - ridirect</title>
</head>
<body bgcolor="#FFFFFF" text="#C0C0C0">
<font face="Trebuchet MS,Arial,Helvetica,sans-serif" size="6">loading</font>

<form action="<?php echo get('url'); ?>" method="post" name="AutoPost" id="AutoPost">
<?php
$get = get();
foreach($get as $key => $value)
	{
	if ($key != 'url')
		{
		echo '	<input type="hidden" name="'. htmlentities($key) .'" value="' . htmlentities($value) . '">'. "\n";
		}
	}
?>
	<noscript>
	Javascript is required for netjukebox.<hr>
	<input type="submit" value="Continue">
	</noscript>
</form>

<script type="text/javascript">
	<!--
	window.onload=function(){document.AutoPost.submit()};
	document.AutoPost.submit();
	//-->
</script>

</body>
</html>
