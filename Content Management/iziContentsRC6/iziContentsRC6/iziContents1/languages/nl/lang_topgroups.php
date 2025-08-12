<?

// Form Title
$GLOBALS["tFormTitle"]		= 'Beheer topmenu\'s';

// List Headings
$GLOBALS["tMenuTitle"]		= 'Menu titel';
$GLOBALS["tHomepage"]		= 'Startpagina';

// List Functions
$GLOBALS["tAddNewMenu"]		= 'Nieuwe topmenu link toevoegen';
$GLOBALS["tEditMenu"]		= 'Menu link bewerken';
$GLOBALS["tDeleteMenu"]		= 'Verwijder menu link';
$GLOBALS["tMakeHomepage"]	= 'Maak startpagina';
$GLOBALS["tHomepageSet"]	= 'Huidige startpagina';

//  Form Block Titles
$GLOBALS["thGeneral"] = 'Algemene instellingen';
$GLOBALS["thGraphics"] = 'Grafische instellingen';
$GLOBALS["thLinks"] = 'Modules en links';
$GLOBALS["thSequence"] = 'Menu volgorde';
$GLOBALS["thAccess"] = 'Toegangsbeveiliging';

// Form Field Headings
$GLOBALS["tMenuRef"] 		= 'Menu naam';
$GLOBALS["tMenuTitle"]		= 'Menu titel';
$GLOBALS["tParentMenu"]		= 'Oorsprong menu';
$GLOBALS["tMenuLink"]		= 'Menu link';
$GLOBALS["tOpenMenuLink"]	= 'Open menu link binnen pagina';
$GLOBALS["tMenuImage1"]		= 'Menu afbeelding';
$GLOBALS["tMenuImage2"]		= 'Menu afbeelding - muis er over';
$GLOBALS["tMenuImage3"] 	= 'Menu afbeelding (geselecteerd)';
$GLOBALS["tMenuImage4"] 	= 'Menu afbeelding (geselecteerd) - muis erover';
$GLOBALS["tMenuHover"]		= 'Menu zweeftekst';
$GLOBALS["tShowMenu"]		= 'Toon menu';
$GLOBALS["tOrderID"]		= 'Volgorde ID';
$GLOBALS["tOrderBy"]		= 'Volgorde menu op';
$GLOBALS["tOrderDir"]		= 'Volgorde richting';
$GLOBALS["tMLoginReq"]		= 'Login verplicht';
$GLOBALS["tUsergroups"]		= 'Gebruikersgroep';
$GLOBALS["tAuthor"] 		= 'Topmenu eigenaar';

// Form Text Description
$GLOBALS["tDetails"]		 = 'Op deze pagina is het mogelijk om de topmenu links te maken en te beheren.';
$GLOBALS["hMenuTitle"]		 = 'De naam van de menu link. Dit zal een link zijn naar een contentpagina.';
$GLOBALS["hMenuLink"]		 = 'Als dit veld ingevuld is, zal een directe link worden aangemaakt naar deze locatie.<br />bv. http://www.google.com zal de Google site in het menu tonen.<br />Het is niet mogelijk om ook eigen content te koppelen aan deze menulink.<br />Op deze manier kunnen persoonlijke links gemaakt worden.<br /><br />Hier kunnen ook de iziContents plug-in modules gebruik worden. Bv.", "what\'s new" of andere toevoegingen.<br /><ul><li>Voor een "zoek" pagina specificeer: modules/search/search.php<br /><li>Voor "wat is nieuw" pagina specificeer: modules/whatsnew/whatsnew.php</ul>';
$GLOBALS["hOpenMenuLink"]	 = 'Menu links openen in een nieuw venster in non-frames mode.<br />Deze setting zal om een externe link laten zien in de eigen site.';
$GLOBALS["hOpenMenuLink"]	.= 'Het zal alleen correct werken indien de externe pagina specifiek voor iziContents is gemaakt.';
$GLOBALS["hMenuImage1"]		 = 'De afbeelding die te zien is als menu titel.';
$GLOBALS["hMenuImage2"]		 = 'De afbeelding die te zien is als men met de muis over de link gaat.';
$GLOBALS["hMenuImage3"] 		= 'De afbeelding die te zien is als het menuitem geselecteerd is';
$GLOBALS["hMenuImage4"] 		= 'De afbeelding die te zien is als het menuitem geselecteerd is en als men met de muis over de link gaat.';
$GLOBALS["hMenuHover"]		 = 'Tekst die getoond wordt als men met de muis over de menu link gaat.';
$GLOBALS["hShowMenu"]		 = 'Moet het menu zichtbaar zijn of niet?';
$GLOBALS["hOrderID"]		 = 'De volgorde ID in welke de menu`s getoond worden als "'.$GLOBALS["tOrderBy"].'" gezet is op Volgorde ID.';
$GLOBALS["hOrderBy"]		 = 'Kies de manier op de inhoud te sorteren die bij dit menu hoort.';
$GLOBALS["hOrderDir"]		 = 'Kies uit volgorde richting: ('.$GLOBALS["tAscending"].' of '.$GLOBALS["tDescending"].').';
$GLOBALS["hMLoginReq"]		 = 'Als deze optie aan staat, dan is het nodig om in te loggen om in het menu te komen.';
$GLOBALS["hUsergroups"]		 = 'Login is alleen mogelijk door gebruikers van deze gebruikersgroep.';
$GLOBALS["hAuthor"] 		 = 'Dit is de \'eigenaar\' van het topmenu. Als Onderdeel-level beveiliging is ingeschakeld, kan alleen de eigenaar van het topmenu artikelen toevoegen aan dit menu.';

// Error Messages
$GLOBALS["eMenuExists"] = 'Een menu met deze naam bestaat al.';
$GLOBALS["eInvalidName"] = 'Menunaam bevat verkeerde karakters.';
$GLOBALS["eTitleEmpty"]		= 'Menu titel mag niet leeg zijn.';

?>