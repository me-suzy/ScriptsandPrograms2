<?

// Form Title
$GLOBALS["tFormTitle"]		= 'Beheer submenu\'s';
$GLOBALS["tFormTitle2"] = 'Beheer submenu vertalingen';

// List Headings
$GLOBALS["tSubmenuTitle"]	= 'Submenu titel';
$GLOBALS["tMenuTitle"]		= 'Menu titel';

// List Functions
$GLOBALS["tMenuFilter"]				= 'Menu filter';
$GLOBALS["tViewSubmenu"] 			= 'Bekijk submenu';
$GLOBALS["tAddNewSubmenu"]		= 'Nieuwe submenu link toevoegen';
$GLOBALS["tEditSubmenu"]			= 'Wijzigen submenu link';
$GLOBALS["tDeleteSubmenu"]		= 'Verwijderen submenu link';
$GLOBALS["tTranslate"] 				= 'Vertaal menu item';
$GLOBALS["tViewTranslation"] 	= 'Bekijk vertaling';
$GLOBALS["tEditTranslation"] 	= 'Bewerk vertaling';

//  Form Block Titles
$GLOBALS["thGeneral"] = 'Algemene referentie';
$GLOBALS["thGraphics"] = 'Plaatjes';
$GLOBALS["thLinks"] = 'Modules en links';
$GLOBALS["thSequence"] = 'Menu volgorde';
$GLOBALS["thAccess"] = 'Toegangs beveiliging';

// Form Field Headings
$GLOBALS["tMenuRef"] 			= 'Submenu naam';
$GLOBALS["tSubmenuTitle"]	= 'Submenu naam';
$GLOBALS["tParentMenu"]		= 'Valt onder:';
$GLOBALS["tMenuLink"]			= 'Menu link';
$GLOBALS["tOpenMenuLink"]	= 'Open menulink binnen pagina';
$GLOBALS["tMenuImage1"]		= 'Submenu plaatje';
$GLOBALS["tMenuImage2"]		= 'Submenu plaatje - muis over';
$GLOBALS["tMenuImage3"] 	= 'Submenu image (geselecteerd)';
$GLOBALS["tMenuImage4"] 	= 'Submenu image (geselecteerd) - muis over';
$GLOBALS["tMenuHover"]		= 'Submenu zweeftekst';
$GLOBALS["tShowMenu"]			= 'Toon submenu';
$GLOBALS["tOrderBy"]			= 'Volgorde menu op';
$GLOBALS["tOrderDir"]			= 'Volgorde richting';
$GLOBALS["tMLoginReq"]		= 'Login verplicht';
$GLOBALS["tUsergroups"]		= 'Gebruikersgroep';
$GLOBALS["tAuthor"] 			= 'Submenu eigenaar';

// Form Text Description
$GLOBALS["tDetails"]		 	= 'Met dit formulier kunt u nieuwe submenulinks toevoegen en wijzigen in het zijmenu.';
$GLOBALS["hMenuRef"] 			= 'This is the internal identifier used by iziContents to refer to this sub-menu item.<br />Als u dit veld leeg laat, wordt er automatisch een nummer aan toegekent als referentie.';
$GLOBALS["hSubmenuTitle"]	= 'De naam van de menu link. Dit wordt een menulink naar de inhoud die bij deze submenulink hoort.';
$GLOBALS["hParentMenu"] 	= 'Het bijbehorende menuitem van dit submenu.';
$GLOBALS["hMenuLink"]		 	= 'In dit veld kunt u een externe link naar een andere website opgegeven.</ br>Zo zal bij http://www.google.com de Google pagina in het inhoudsframe getoond worden.</ br>Er kan geen inhoud achter deze submenulink gekoppeld kunnen worden!.<br>Op deze manier kun je eigen links maken.<br>iziContents heeft ook module die u kunt gebruiken voor "search", "what\'s new" en andere add-ins.</ br>Voor de "zoek" pagina geef je op: modules/search/search.php</ br><li>Voor de "what\'s new" pagina: modules/whatsnew/whatsnew.php</ul>';
$GLOBALS["hOpenMenuLink"]	 = 'De link naar een externe website moet geopend worden in een nieuw venster als u de site hebt ingesteld op non-frames mode.<br>Met deze instelling zorgt u ervoor dat een externe link geopend wordt in de iziContents site.</ br>Dit zal alleen werken als de externe pagina zo is ontworpen dat deze dat toelaat.';
$GLOBALS["hMenuImage1"]		 = 'Het plaatje die de menutitel zal vervangen.</ br>(Het plaatje zal minder breed moeten zijn dan de breedte van uw zijmenu.)';
$GLOBALS["hMenuImage2"]		 = 'Het plaatje die getoond zal worden al de muis over het plaatje gaat.</ br>(Het plaatje zal minder breed moeten zijn dan de breedte van uw zijmenu.)';
$GLOBALS["hMenuImage3"] 		= 'De afbeelding die te zien is als het submenuitem geselecteerd is';
$GLOBALS["hMenuImage4"] 		= 'De afbeelding die te zien is als het submenuitem geselecteerd is en als men met de muis over de link gaat.';
$GLOBALS["hMenuHover"]		 = 'De tekst die getoond moet worden zodra u met de muis over een link gaat.';
$GLOBALS["hShowMenu"]		 = 'Moet het menu weergeven worden of niet?';
$GLOBALS["hOrderBy"]		 = 'Selecteer de sorteervolgorde type waarme de content of dit menu gesorteerd zal worden.';
$GLOBALS["hOrderDir"]		 = 'Selecteer de richting van de sortering ('.$GLOBALS["tAscending"].' of '.$GLOBALS["tDescending"].').';
$GLOBALS["hMLoginReq"]		 = 'Als deze insteling is ingesteld moet de bezoeker inloggen om de menuinhoud te kunnen bekijken.';
$GLOBALS["hUsergroups"]		 = 'Login is alleen mogelijk door gebruikers van deze gebruikersgroep, of hoger.';
$GLOBALS["hAuthor"] 		 = 'Dit is de \'eigenaar\' van het submenu. Als Onderdeel-level beveiliging is ingeschakeld, kan alleen de eigenaar van het submenu artikelen toevoegen aan dit submenu.';

// Error Messages
$GLOBALS["eMenuExists"] = 'Een submenu met deze naam bestaat al.';
$GLOBALS["eInvalidName"] = 'Submenunaam bevat verkeerde karakters.';
$GLOBALS["eTitleEmpty"]		= 'Submenu titel mag niet leeg zijn.';

?>
