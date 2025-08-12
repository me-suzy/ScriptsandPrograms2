<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'Beheer sites';

//  List Headings
$GLOBALS["tSiteCode"] = 'Site code';
$GLOBALS["tSiteName"] = 'Site naam';
$GLOBALS["tSiteDescription"] = 'Site omschrijving';
$GLOBALS["tSiteEnabled"] = 'Aan';

//  List Functions
$GLOBALS["tAddNewSite"] = 'Nieuwe site toevoegen';
$GLOBALS["tViewSite"] = 'Bekijk site bijzonderheden';
$GLOBALS["tEditSite"] = 'Bewerk site bijzonderheden';
$GLOBALS["tDeleteSite"] = 'Verwijder site';
$GLOBALS["tReleaseSite"] = 'Enable/Disable deze site';
$GLOBALS["tSelectSite"] = 'Selecteer deze site voor beheer';

//  Form Block Titles
$GLOBALS["thSiteGeneral"] = 'Site bijzonderheden';

//  Form Detail Comments and Help Texts
$GLOBALS["tDetails"]		= 'Op deze pagina is het mogelijk om  sub-sites te definiëren in de iziContents site.';
$GLOBALS["hSiteCode"] 		= 'Dit is de unieke site code voor deze sub-site.<br />Deze code mag geen spaties of speciale tekens bevatten.<br /><br />Als de Apache server geconfigureerd is voor het gebruik van de .htaccess file in de iziContents directory, kunnen de bezoekers direct toegang krijgen tot deze site met een url in dit formaat: http://www.mijnserver.nl/ezc_directorie/<sitecode>.';
$GLOBALS["hSiteName"] 		= 'Een naam die wordt gebruikt voor deze site (wordt gebruikt voor de [sitelist] tag).';
$GLOBALS["hSiteDescription"] 	= 'Een omschrijving die wordt gebruikt voor deze site (wordt gebruikt voor de [sitelist] tag).';
$GLOBALS["hSiteEnabled"] 	= 'Geeft aan of deze site is enabled of niet.';

//  Error Messages
$GLOBALS["eNoCode"] 		= 'Deze site moet een identificatie code hebben.';
$GLOBALS["eInvalidCode"] 	= 'Site code bevat onjuiste tekens.';
$GLOBALS["eMasterCode"] 	= 'Deze identificatie code is al in gebruik door de master site.';
$GLOBALS["eCodeInUse"] 		= 'Deze identificatie code is al in bebruik door een andere site of thema.';
$GLOBALS["eNoName"] 		= 'De naam van de site mag niet leeg zijn.';
$GLOBALS["eNoDescription"] 	= 'Site omschrijving mag niet leeg zijn.';

?>
