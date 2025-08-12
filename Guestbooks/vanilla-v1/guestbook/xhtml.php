<?php
/***************************************************************************
*	Vanilla Guestbook
*	Version: 0.1
*	Filename: xhtml.php
*	Description: Guide to the allowed (X)HTML codes
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
   "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Allowed XHTML Guide</title>

	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<style type="text/css">
		@import "templates/styles.css";

		body { background-color: #FFFFF0; text-align: center; }
	</style>

</head>
<body>

<p>Basic <abbr title="eXtensible HyperText Markup Language">(X)HTML</abbr> code is allowed to format your entries.  Note that styles are stripped regardless of the tag you use.</p>

<ul class="xhtmlcodes">
	<li><span class="thecode">&lt;em&gt;</span> - emphasis</li>
	<li><span class="thecode">&lt;strong&gt;</span> - strong</li>
	<li><span class="thecode">&lt;a href=&quot;<em>http://google.com/</em>&quot;&gt;Google&lt;/a&gt;</span> - Hyperlink</li>
	<li><span class="thecode">&lt;abbr title=&quot;Ontario Archaeological Society&quot;&gt;OAS&lt;/abbr&gt;</span> - Abbreviation</li>
	<li><span class="thecode">&lt;acronym title=&quot;National Aeronautics and Space Administration&quot;&gt;NASA&lt;acronym&gt;</span> - Acronym</li>
	<li><span class="thecode">&lt;code&gt;<em>code here</em>&lt;/code&gt;</span> - Code</li>
	<li><span class="thecode">&lt;blockquote&gt;<em>quote here</em>&lt;/blockquote&gt;</span> - Block Quotation</li>
</ul>

<p>Use <em>/me</em> to create an action you perform:</p>

<ul class="xhtmlcodes">
	<li>/me codes a guestbook.</li>
	<li><span style="font-weight: bold; font-style: italic;">Tachyon</span> codes a guestbook.</li>
</ul>

<p><a href="javascript:self.close();">Close Window</a></p>

</body>
</html>
