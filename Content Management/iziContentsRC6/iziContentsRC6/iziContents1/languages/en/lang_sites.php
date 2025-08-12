<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'maintain sites';

//  List Headings
$GLOBALS["tSiteCode"] = 'Site Code';
$GLOBALS["tSiteName"] = 'Site Name';
$GLOBALS["tSiteDescription"] = 'Site Description';
$GLOBALS["tSiteEnabled"] = 'Enabled';

//  List Functions
$GLOBALS["tAddNewSite"] = 'Add new site';
$GLOBALS["tViewSite"] = 'View site details';
$GLOBALS["tEditSite"] = 'Edit site details';
$GLOBALS["tDeleteSite"] = 'Delete site';
$GLOBALS["tReleaseSite"] = 'Enable/Disable this site';
$GLOBALS["tSelectSite"] = 'Select this site for maintenance';

//  Form Block Titles
$GLOBALS["thSiteGeneral"] = 'Site Details';

//  Form Detail Comments and Help Texts
$GLOBALS["tDetails"] = 'This form lets you define subsites within your ezContents site.';
$GLOBALS["hSiteCode"] = 'This is the unique code that identifies this sub-site.<br />It must not contain any spaces or special characters.<br /><br />If your Apache server is correctly configured to use the .htaccess file in your ezContents directory, then viewers can directly access this site with a url in the format: http://www.yourserver.com/ezc_directory/<sitecode>.';
$GLOBALS["hSiteName"] = 'A name used in list displays for this site (used by the [sitelist] tag).';
$GLOBALS["hSiteDescription"] = 'A description used in list displays for this site (used by the [sitelist] tag).';
$GLOBALS["hSiteEnabled"] = 'Whether this site is enabled or not.';

//  Error Messages
$GLOBALS["eNoCode"] = 'You must give this site an identifier code.';
$GLOBALS["eInvalidCode"] = 'Site code contains invalid characters';
$GLOBALS["eMasterCode"] = 'This site identifier code is already in use for the master site.';
$GLOBALS["eCodeInUse"] = 'This identifier code is already in use for another site or theme.';
$GLOBALS["eNoName"] = 'Site name cannot be left empty.';
$GLOBALS["eNoDescription"] = 'Site description cannot be left empty.';

?>
