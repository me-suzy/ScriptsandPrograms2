--------------------------------------------------------------------------------
CJG MENU v1.0 - Html Tree Menu Structure - Copyright (C) 2002 CARLOS GUERLLOY  
cjgmenu@guerlloy.com
guerlloy@hotmail.com
carlos@weinstein.com.ar
Buenos Aires, Argentina
--------------------------------------------------------------------------------
This program is free software; you can  redistribute it and/or  modify it  under
the terms   of the   GNU General   Public License   as published   by the   Free
Software Foundation; either  version 2   of the  License, or  (at  your  option)
any  later version. This program  is  distributed in  the hope that  it  will be
useful,  but  WITHOUT  ANY  WARRANTY;  without  even  the   implied  warranty of
MERCHANTABILITY  or FITNESS  FOR A  PARTICULAR  PURPOSE.  See the  GNU   General
Public License for   more details. You  should have received  a copy of  the GNU
General Public License along  with this  program; if   not, write  to the   Free
Software  Foundation, Inc.,  59 Temple Place,  Suite 330, Boston,  MA 02111-1307
USA
--------------------------------------------------------------------------------

Any suggestions, comments or questions are welcome.
Feel free to write me to cjgmenu@guerlloy.com
-Carlos


Instructions to use
-------------------
1) Unpack and copy the files (preserving directories) to the desired folder.
2) Alter cjgmenu.def according to your needs (there's a sample in the file).
3) Include the menu in your page (there is a sample in sample.php:
	In the <HEAD> section, the line 
			<?php include("cjgmenu.php"); ?>
	In the <BODY> section (where you want the menu), the line 
			<?php menuhere(); ?>
4) Modify (if needed) the cjgmenu.css file to alter the menu style.	
   (styles are explained there).	
5) Modify (if needed) the cjgmenuconfig.php file to alter the menu behavior 
   (options are explained there).	
6) Ok!

	
Avalilable functions from Javascript
------------------------------------

------------------------------------------------------------------------------------------------------------------------
menuexpand(optionmenu,option)

	Opens programatically the submenu indicated by "optionmenu" argument and "option" argument.
	"optionmenu" starts from "root", and the submenus are int the form root[VMENU][n1], root[VMENU][n1][VMENU][n2], ...
	"option" can be the option number (starting from zero) or the option label.
	Additionally, the function returns the value of the new opened menu, so you can open menus recursively through
	the menus tree.
	Examples:
		menuexpand(root,2);		// Opens the main's menu third (zero-based) option 
		menuexpand(root,"Software");	// Opens the main's menu option labeled "Software"

		menuxexpand(root,3);		// Opens the main's menu fourth option, and then
		menuexpand(root[VMENU][3],0);	// 	opens the first option of the fourth main's menu submenu

		menuexpand(menuexpand(root,3),0)	// A better way to do the same

------------------------------------------------------------------------------------------------------------------------
menucollapse(optionmenu,option)

	Closes programatically the submenu indicated by "optionmenu" argument and "option" argument.
	"optionmenu" starts from "root", and the submenus are int the form root[VMENU][n1], root[VMENU][n1][VMENU][n2], ...
	"option" can be the option number (starting from zero) or the option label.

	Examples:
		menucollapse(root,1);		// Opens the main's menu second (zero-based) option 
		menucollapse(root,"Download");	// Opens the main's menu option labeled "Download"


------------------------------------------------------------------------------------------------------------------------
menuexpandall()

	Opens all submenus at once. It can be a little slow, depending on the pc performance.

------------------------------------------------------------------------------------------------------------------------
menucollapseall()

	Closes all submenus at once.

------------------------------------------------------------------------------------------------------------------------
menuhere()

	Draws the menu.
------------------------------------------------------------------------------------------------------------------------


Version history
---------------
1.0a: 
Original Version

1.0b:
Better Demo
Added menuexpand() and menucollapse() functions
Internal menu structure reorganization
Fixed bug with back join lines
Fixed root node

1.0c:
Fixed bug when closing menu with opened child