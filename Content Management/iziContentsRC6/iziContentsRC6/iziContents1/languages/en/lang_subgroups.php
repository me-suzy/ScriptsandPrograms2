<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'maintain sub-menus';
$GLOBALS["tFormTitle2"] = 'maintain sub-menu translations';

//  List Headings
$GLOBALS["tSubmenuTitle"] = 'Sub-menu title';
$GLOBALS["tMenuTitle"] = 'Menu title';

//  List Functions
$GLOBALS["tMenuFilter"] = 'Menu filter';
$GLOBALS["tViewSubmenu"] = 'View sub-menu item';
$GLOBALS["tAddNewSubmenu"] = 'Add new sub-menu item';
$GLOBALS["tEditSubmenu"] = 'Edit sub-menu item';
$GLOBALS["tDeleteSubmenu"] = 'Delete sub-menu item';
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
$GLOBALS["tMenuRef"] = 'Sub-menu name';
$GLOBALS["tSubmenuTitle"] = 'Sub-menu title';
$GLOBALS["tParentMenu"] = 'Parent menu';
$GLOBALS["tMenuLink"] = 'Sub-menu link';
$GLOBALS["tOpenMenuLink"] = 'Open sub-menu link within page';
$GLOBALS["tMenuImage1"] = 'Sub-menu image';
$GLOBALS["tMenuImage2"] = 'Sub-menu image - mouse over';
$GLOBALS["tMenuImage3"] = 'Sub-menu image (selected)';
$GLOBALS["tMenuImage4"] = 'Sub-menu image (selected) - mouse over';
$GLOBALS["tMenuHover"] = 'Sub-menu hover text';
$GLOBALS["tShowMenu"] = 'Show sub-menu';
$GLOBALS["tOrderBy"] = 'Order contents by';
$GLOBALS["tOrderDir"] = 'Order direction';
$GLOBALS["tMLoginReq"] = 'Login required';
$GLOBALS["tUsergroups"] = 'User groups';
$GLOBALS["tAuthor"] = 'Sub-menu Owner';

//  Form Text Descriptions and Help Messages
$GLOBALS["tDetails"] = 'This form lets you edit or create new sub-menu groups to navigate the contents.';
$GLOBALS["hMenuRef"] = 'This is the internal identifier used by ezContents to refer to this sub-menu item.<br />If you leave the field blank, it will automatically be given a sequence number as a reference.';
$GLOBALS["hSubmenuTitle"] = 'The name of the menu group. This will be a link to the contents in this menu.';
$GLOBALS["hParentMenu"] = 'The parent menu of this sub menu.';
$GLOBALS["hMenuLink"] = 'If this field is filled, a hard link will be created to the specified link by the menu. e.g. http://www.altavista.com will show the altavista page in the contents frame. It will be not possible to link any other content to this group.<br />This way you can create custom links.<br /><br />ezContents includes plug-in modules that you can use for \"search\", \"what\'s new\" or other add-ins.<ul><li>For a \"search\" page specify: modules/search/search.php<li>For the \"what\'s new\" page specify: modules/whatsnew/whatsnew.php</ul>';
$GLOBALS["hOpenMenuLink"] = 'Menu links open within a new window when in non-frames mode.<br />Setting this flag will force an external link to open within the contents segment of the EzContents window. It will only work correctly when the external page script is designed to work with EzContents.';
$GLOBALS["hMenuImage1"] = 'The image that will replace the menu title. (It should be less than the width of your left column, allowing for any offset that you have defined in the menu settings.)';
$GLOBALS["hMenuImage2"] = 'The image that will be shown when the mouse cursor passes over the image. (It should be less than the width of your left column, allowing for any offset that you have defined in the menu settings.)';
$GLOBALS["hMenuImage3"] = 'The image that will be shown when this sub-menu item is the currently selected menu.<br />(It should be less than the width of your left column, allowing for any offset that you have defined in the menu settings.)';
$GLOBALS["hMenuImage4"] = 'The image that will be shown when this sub-menu item is the currently selected menu and the mouse cursor passes over the image.';
$GLOBALS["hMenuHover"] = 'Text to be displayed when the cursor hovers over a menu item, if you have enabled this feature.';
$GLOBALS["hShowMenu"] = 'Whether the menu is displayed or not.';
$GLOBALS["hOrderBy"] = 'Select the order type by which the contents on this menu will be ordered.';
$GLOBALS["hOrderDir"] = 'Select the direction for the sort (Ascending or Descending).';
$GLOBALS["hMLoginReq"] = 'If this flag is set, then a login is required to access this menu option.';
$GLOBALS["hUsergroups"] = 'Login is restricted to members of these selected usergroups.';
$GLOBALS["hAuthor"] = 'This is the \'owner\' of the sub-menu. If section-level security is enabled, only the owner can \'attach\' articles to this sub-menu.';

//  Error Messages
$GLOBALS["eMenuExists"] = 'A menu with this name already exists.';
$GLOBALS["eInvalidName"] = 'Sub-menu name contains invalid characters.';
$GLOBALS["eTitleEmpty"] = 'Sub-menu title can not be left empty.';

?>
