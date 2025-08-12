<?php
/***************************************************************************
*	Vanilla Guestbook
*	Version: 0.1
*	Filename: emotes.php
*	Description: Guide to the emoticon shortcuts
****************************************************************************
*	Build Date: October 11, 2005
*	Author: Tachyon
*	Website: http://tachyondecay.net/
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

include "functions/emoticon_functions.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
   "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Emoticon Guide</title>

	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<style type="text/css">
		@import "templates/styles.css";

		body { background-color: #FFFFF0; text-align: center; }
	</style>

</head>
<body>

<table class="bk_emoticons">
<tr>
	<th>Shortcut</th>
	<th>Image</th>
</tr>
<?php
emote_guide();
?>
</table>

<p><a href="javascript:self.close();">Close Window</a></p>

</body>
</html>
