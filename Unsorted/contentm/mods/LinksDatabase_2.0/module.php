<?php

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
	
	if(!isset($category))
		{
		// show link categories
		$result=$IW->Query("select * from mod_linksportal_category order by category_title");
		for($i=0;$i<$IW->CountResult($result);$i++)
			{
			echo "<p><b><a href=\"?D=$D&V=category&category=".$IW->Result($result,$i,"id")."\">".$IW->Result($result,$i,"category_title")."</a></b><br />";
			echo "<font size=1>".$IW->Result($result,$i,"category_desc")."</font></p>";
			}
		$IW->FreeResult($result);
		}
	else
		{
		// show links
		$result=$IW->Query("select * from mod_linksportal where category='$category' order by title");
		for($i=0;$i<$IW->CountResult($result);$i++)
			{
			echo "<p><b><a href=\"".$IW->Result($result,$i,"url")."\" target=_blank>".$IW->Result($result,$i,"title")."</a></b><br />";
			echo "<font size=1>".$IW->Result($result,$i,"description")."</font></p>";
			}
		$IW->FreeResult($result);
		}

	
?>