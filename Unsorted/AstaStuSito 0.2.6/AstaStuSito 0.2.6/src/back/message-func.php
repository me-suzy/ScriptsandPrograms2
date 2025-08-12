<?php

/*
AstaStuSito ver 0.2.6
Copyright (C) 2001-2002 isazi <isazi@olografix.org>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

# Function for making response or advertisement

function MakeXhtmlMessage($Title, $Message) {
	
	# Style
	
	$CSS = "../default.css";
	
	# Make the page
	
	$XhtmlMess = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\"http://www.w3.org/TR/xhtml1/DTD/transitional.dtd\">
<html>
<head>
<title>$Title</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"$CSS\"></link>
</head>
<body>
<br />
<h1 align=\"center\">$Title</h1>
<br />
<br />
<h2 align=\"center\">$Message</h2>
<br />
</body>
</html>";
	
	# Return it
	
	return $XhtmlMess;

}

?>
