<?php
/***************************************************************************
*	Very Simple News System
*	Version: 3.0.1
*	Filename: vsns_3.0.0_to_3.0.1.php
*	Description: Upgrades VSNS 3.0.0 to 3.0.1
****************************************************************************
*	Build Date: March 4, 2005
*	Author: Tachyon
*	Website: http://tachyondecay.net
****************************************************************************
*	Copyright © 2005 by Tachyon
*
*	This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.  A copy of the GPL version 2 is
*	included with this package in the file "COPYING.TXT"
*
*   This program is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with this program; if not, write to the Free Software
*   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
****************************************************************************/

include "../settings.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<title>VSNS 3.0 Upgrade</title>

	<style type="text/css">
		@import "../templates/styles.css";
	</style>

</head>
<body>

<div id="top" style="text-align: center;">
	<img src="../logo.png" alt="VSNS v3.0" id="logo" />
</div>

<div id="main">

<h1 style="text-align: center;">VSNS v3.0.1 Upgrade</h1>

<?php
mysql_query("INSERT INTO vsns_config VALUES ('navdisplay', 1)");
mysql_query("INSERT INTO vsns_config VALUES ('hlevel', 2)");
mysql_query("UPDATE vsns_config SET config_value = '3.0.1' WHERE config_name = 'version'");
?>

<p class="response">Upgrade complete.  Delete this file.</p>

<div id="footer">
	<p>Powered by VSNS Lemon 3.0 &copy; 2005 by <a href="http://tachyondecay.net">Tachyon</a>.  All rights reserved.</p>
</div>

</body>
</html>