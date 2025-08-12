<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'maintain menus';
$GLOBALS["tFormTitle2"] = 'maintain menu translations';

//  List Headings
$GLOBALS["tMenuTitle"] = 'Menu title';
$GLOBALS["tHomepage"] = 'Homepage menu';

//  List Functions
$GLOBALS["tMenuFilter"] = 'Menu filter';
$GLOBALS["tEditMenu"] = 'Edit menu item';
$GLOBALS["tAddNewMenu"] = 'Add new menu item';
$GLOBALS["tViewMenu"] = 'View menu item';
$GLOBALS["tDeleteMenu"] = 'Delete menu item';
$GLOBALS["tMakeHomepage"] = 'make home';
$GLOBALS["tHomepageSet"] = 'Current homepage menu';
$GLOBALS["tTranslate"] = 'Translate menu item';
$GLOBALS["tViewTranslation"] = 'View translation';
$GLOBALS["tEditTranslation"] = 'Edit translation';

//  Form Block Titles
$GLOBALS["thGeneral"] = 'General Reference';
$GLOBALS["thGraphics"] = 'Graphics';
$GLOBALS["thLinks"] = 'Modules and Links';
$GLOBALS["thSequence"] = 'Menu Sequencing';
$GLOBALS["thAccess"] = 'Access Security';

//  Form Field Headings
$GLOBALS["tMenuRef"] = 'Menu name';
$GLOBALS["tMenuTitle"] = 'Menu title';
$GLOBALS["tParentMenu"] = 'Top-level parent menu';
$GLOBALS["tMenuLink"] = 'Menu link';
$GLOBALS["tOpenMenuLink"] = 'Open menu link within page';
$GLOBALS["tMenuImage1"] = 'Menu image';
$GLOBALS["tMenuImage2"] = 'Menu image - mouse over';
$GLOBALS["tMenuImage3"] = 'Menu image (selected)';
$GLOBALS["tMenuImage4"] = 'Menu image (selected) - mouse over';
$GLOBALS["tMenuHover"] = 'Menu hover text';
$GLOBALS["tShowMenu"] = 'Show menu';
$GLOBALS["tOrderBy"] = 'Order contents by';
$GLOBALS["tOrderDir"] = 'Order direction';
$GLOBALS["tMLoginReq"] = 'Login required';
$GLOBALS["tUsergroups"] = 'User groups';
$GLOBALS["tAuthor"] = 'Menu Owner';

//  Form Detail Comments and Help Texts
$GLOBALS["tDetails"] = 'This form lets you edit or create new menu groups to navigate the contents.';
$GLOBALS["hMenuRef"] = 'This is the internal identifier used by ezContents to refer to this menu item.<br />If you leave the field blank, it will automatically be given a sequence number as a reference.';
$GLOBALS["hMenuTitle"] = 'The name of the menu group. This will be a link to the contents in this menu.';
$GLOBALS["hParentMenu"] = 'The top-level parent menu of this menu.';
$GLOBALS["hMenuLink"] = 'If this field is filled, a hard link will be created to the specified link by the menu. e.g. http:{groups';
$GLOBALS["hOpenMenuLink"] = 'Menu links open within a new window when in non-frames mode.<br />Setting this flag will force an external link to open within the contents segment of the EzContents window. It will only work correctly when the external page script is designed to work with EzContents.';
$GLOBALS["hMenuImage1"] = 'The image that will replace the menu title. (It should be less than the width of your left column.)';
$GLOBALS["hMenuImage2"] = 'The image that will be shown when the mouse cursor passes over the image.<br />(It should be less than the width of your left column.)';
$GLOBALS["hMenuImage3"] = 'The image that will be shown when this menu item is the currently selected menu.<br />(It should be less than the width of your left column.)';
$GLOBALS["hMenuImage4"] = 'The image that will be shown when this menu item is the currently selected menu and the mouse cursor passes over the image.<br />(It should be less than the width of your left column.)';
$GLOBALS["hMenuHover"] = 'Text to be displayed when the cursor hovers over a menu item, if you have enabled this feature.';
$GLOBALS["hShowMenu"] = 'Whether the menu is displayed or not.';
$GLOBALS["hOrderBy"] = 'Select the order type by which the contents on this menu will be ordered.';
$GLOBALS["hOrderDir"] = 'Select the direction for the sort (Ascending or Descending).';
$GLOBALS["hMLoginReq"] = 'If this flag is set, then a login is required to access this menu option.';
$GLOBALS["hUsergroups"] = 'Login is restricted to members of these selected usergroups.';
$GLOBALS["hAuthor"] = 'This is the \'owner\' of the menu. If section-level security is enabled, only the owner can \'attach\' items to this menu.';

//  Error Messages
$GLOBALS["eMenuExists"] = 'A menu with this name already exists.';
$GLOBALS["eInvalidName"] = 'Menu name contains invalid characters.';
$GLOBALS["eTitleEmpty"] = 'Menu title can not be left empty.';

?>
