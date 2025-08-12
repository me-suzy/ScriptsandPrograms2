<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'maintain themes';

//  List Headings
$GLOBALS["tThemeCode"] = 'Theme Code';
$GLOBALS["tThemeName"] = 'Theme Name';
$GLOBALS["tThemeDescription"] = 'Theme Description';
$GLOBALS["tThemeEnabled"] = 'Enabled';

//  List Functions
$GLOBALS["tAddNewTheme"] = 'Add new theme';
$GLOBALS["tViewTheme"] = 'View theme details';
$GLOBALS["tEditTheme"] = 'Edit theme details';
$GLOBALS["tDeleteTheme"] = 'Delete theme';
$GLOBALS["tReleaseTheme"] = 'Enable/Disable this theme';
$GLOBALS["tSelectTheme"] = 'Select this theme for maintenance';

//  Form Block Titles
$GLOBALS["thThemeGeneral"] = 'Theme Details';

//  Form Detail Comments and Help Texts
$GLOBALS["tDetails"] = 'This form lets you define themes for your ezContents site.';
$GLOBALS["hThemeCode"] = 'This is the unique code that identifies this theme.<br />It must not contain any spaces or special characters.<br /><br />If your Apache server is correctly configured to use the .htaccess file in your ezContents directory, then viewers can directly access this theme with a url in the format: http://www.yourserver.com/ezc_directory/<themecode>.';
$GLOBALS["hThemeName"] = 'A name used in list displays for this theme (used by the [themelist] tag).';
$GLOBALS["hThemeDescription"] = 'A description used in list displays for this theme (used by the [themelist] tag).';
$GLOBALS["hThemeEnabled"] = 'Whether this theme is enabled or not.';

//  Error Messages
$GLOBALS["eNoCode"] = 'You must give this theme an identifier code.';
$GLOBALS["eInvalidCode"] = 'Theme code contains invalid characters';
$GLOBALS["eMasterCode"] = 'This theme identifier code is already in use for the master theme.';
$GLOBALS["eCodeInUse"] = 'This identifier code is already in use for another theme or site.';
$GLOBALS["eNoName"] = 'Theme name cannot be left empty.';
$GLOBALS["eNoDescription"] = 'Theme description cannot be left empty.';

?>
