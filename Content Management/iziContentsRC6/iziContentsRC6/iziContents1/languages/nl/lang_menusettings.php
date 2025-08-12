<?

// Form Title
$GLOBALS["tFormTitle"]		= 'Beheer menu instellingen';

// Form Field Headings
$GLOBALS["tTopMenuBorder"]	= 'Kopmenu - dikte kantlijn ';
$GLOBALS["tTopMenuAlign"]	= 'Kopmenu - uitlijning links';
$GLOBALS["tTopMenuRows"] 	= 'Kopmenu kolomen';
$GLOBALS["tTopMenuSeparator"]	= 'Kopmenu - scheidslijn menulinks';
$GLOBALS["tMenuBorder"]		= 'Menu - dikte kantlijn';
$GLOBALS["tTopDistance"]	= 'Menu - afstand vanaf bovenkant';
$GLOBALS["tBetweenDistance1"]	= 'Menu - afstand menu en submenu`s';
$GLOBALS["tBetweenDistance2"]	= 'Menu - afstand tussen submenu`s';
$GLOBALS["tIndent"]		= 'Menu - submenu inspringen';
$GLOBALS["tPrivateMenus"]	= 'Menu - privé menu`s';

$GLOBALS["tLocked"]		= 'Op slot (moet inloggen)';
$GLOBALS["tHidden"]		= 'Verborgen';

//  Form Block Titles
$GLOBALS["thTopMenu"] 		= 'Kopmenu instellingen';
$GLOBALS["thSideMenu"] 		= 'Menu instellingen';
$GLOBALS["thAccess"] 		= 'Privé menu instellingen';

// Form Detail Comments and Help Texts
$GLOBALS["tiMenuTitle"]		= 'Menu titel';
$tiMenu				= 'menu';
$tiSubmenu			= 'submenu';

$GLOBALS["tDetails"]		 = '|<br />';
$GLOBALS["tDetails"]		.= '|- '.$GLOBALS["tTopDistance"].'<br />';
$GLOBALS["tDetails"]		.= '|<br />';
$GLOBALS["tDetails"]		.= '['.$tiMenu.']<br />';
$GLOBALS["tDetails"]		.= '|<br />';
$GLOBALS["tDetails"]		.= '|- '.$GLOBALS["tBetweenDistance1"].'<br />';
$GLOBALS["tDetails"]		.= '|<br />';
$GLOBALS["tDetails"]		.= '<table><tr><td width=10></td>';
$GLOBALS["tDetails"]		.= '<td>';
$GLOBALS["tDetails"]		.= '['.$tiSubmenu.']<br />';
$GLOBALS["tDetails"]		.= '|<br />';
$GLOBALS["tDetails"]		.= '|- '.$GLOBALS["tBetweenDistance2"].'<br />';
$GLOBALS["tDetails"]		.= '|<br />';
$GLOBALS["tDetails"]		.= '['.$tiSubmenu.']<br />';
$GLOBALS["tDetails"]		.= '['.$tiSubmenu.']<br />';
$GLOBALS["tDetails"]		.= '</td></tr></table>';
$GLOBALS["tDetails"]		.= '['.$tiMenu.']<br />';
$GLOBALS["tDetails"]		.= '['.$tiMenu.']<br />';
$GLOBALS["tDetails"]		.= '['.$tiMenu.']<br />';

$GLOBALS["hTopMenuBorder"]	 = 'De randdikte van de kantlijn tussen de items op het kop menu.<br />';
$GLOBALS["hTopMenuBorder"]	.= 'Indien gewenst wordt om geen kantlijn te hebben, moet de waarde op 0 staan.';
$GLOBALS["hTopMenuAlign"]	 = 'Moeten kop menulinks worden uitgelijnd '.$GLOBALS["tLeft"].', '.$GLOBALS["tRight"].' of worden getoond langs de gehele breedte van het menu balk ('.$GLOBALS["tCentre"].')?';
$GLOBALS["hTopMenuSeparator"]	 = 'Kies de stijl van de scheidslijnen die gebruikt moet worden tussen menulinks.';
$GLOBALS["hMenuBorder"]		 = 'De randdikte van de kantlijn tussen menulinks in het menu.<br />';
$GLOBALS["hMenuBorder"]		.= 'Zet deze op 0 (nul) indien geen kantlijn gewenst is.';
$GLOBALS["hTopDistance"]	 = 'De afstand vanaf de eerste menulink tot aan de bovenkant van het frame.';
$GLOBALS["hBetweenDistance1"]	 = 'De afstand tussen een menulink item en een submenu link.';
$GLOBALS["hBetweenDistance2"]	 = 'De afstand tussen de submenu`s.';
$GLOBALS["hIndent"]		 = 'Inspring afstand tussen de linker kantlijn van het menu en de subgroep menulinks.';
$GLOBALS["hPrivateMenus"]	 = 'Moeten privé menu`s getoond worden als '.$GLOBALS["tLocked"].' of '.$GLOBALS["tHidden"].'?';

// Error Messages
$GLOBALS["eBorder1"]		= 'Dikte kantlijn van kopmenu is geen getal.';
$GLOBALS["eBorder2"]		= 'Menu dikte kantlijn is geen getal.';
$GLOBALS["eRows1"] 		= 'Kopmenu rijen is geen getal.';
$GLOBALS["eDistance1"]		= 'Afstand vanaf de bovenkant is geen getal.';
$GLOBALS["eDistance2"]		= 'Afstand tunnsen menu en submenu\`s is geen getal.';
$GLOBALS["eDistance3"]		= 'Afstand tussen submenu\`s is geen getal.';
$GLOBALS["eDistance4"]		= 'Submenu inspringen is geen nummer.';

?>
