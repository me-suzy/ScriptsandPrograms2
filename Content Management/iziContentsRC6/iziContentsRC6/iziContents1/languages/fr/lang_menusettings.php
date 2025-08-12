<?

// Form Title
$GLOBALS["tFormTitle"]		= 'Réglages du Menu';

// Form Field Headings
$GLOBALS["tTopMenuBorder"]	= 'Bordure du menu Horizontal';
$GLOBALS["tTopMenuAlign"]	= 'Alignement du menu Horizontal';
$GLOBALS["tTopMenuSeparator"]	= 'Séparateurs du menu Horizontal';
$GLOBALS["tMenuBorder"]		= 'Bordure du Menu';
$GLOBALS["tTopDistance"]	= 'Distance du haut';
$GLOBALS["tBetweenDistance1"]	= 'Distance entre le menu et le sous-menu/menu';
$GLOBALS["tBetweenDistance2"]	= 'Distance entre les sous-menus';
$GLOBALS["tIndent"]		= 'Indentation du Sous menu';
$GLOBALS["tPrivateMenus"]	= 'Menu Privé';

$GLOBALS["tLocked"]		= 'Verrouillé';
$GLOBALS["tHidden"]		= 'Caché';

// Form Block Titles
$GLOBALS["thTopMenu"]		= 'Header Bar Menu Settings';
$GLOBALS["thSideMenu"]		= 'Sidebar Menu Settings';
$GLOBALS["thAccess"]		= 'Private Menu Settings';

// Form Detail Comments and Help Texts
$tiMenu				= 'menu';
$tiSubmenu			= 'sous-menu';

$GLOBALS["tDetails"]		 = '|<br>';
$GLOBALS["tDetails"]		.= '|- '.$GLOBALS["tTopDistance"].'<br>';
$GLOBALS["tDetails"]		.= '|<br>';
$GLOBALS["tDetails"]		.= '['.$tiMenu.']<br>';
$GLOBALS["tDetails"]		.= '|<br>';
$GLOBALS["tDetails"]		.= '|- '.$GLOBALS["tBetweenDistance1"].'<br>';
$GLOBALS["tDetails"]		.= '|<br>';
$GLOBALS["tDetails"]		.= '<table><tr><td width=10></td>';
$GLOBALS["tDetails"]		.= '<td>';
$GLOBALS["tDetails"]		.= '['.$tiSubmenu.']<br>';
$GLOBALS["tDetails"]		.= '|<br>';
$GLOBALS["tDetails"]		.= '|- '.$GLOBALS["tBetweenDistance2"].'<br>';
$GLOBALS["tDetails"]		.= '|<br>';
$GLOBALS["tDetails"]		.= '['.$tiSubmenu.']<br>';
$GLOBALS["tDetails"]		.= '['.$tiSubmenu.']<br>';
$GLOBALS["tDetails"]		.= '</td></tr></table>';
$GLOBALS["tDetails"]		.= '['.$tiMenu.']<br>';
$GLOBALS["tDetails"]		.= '['.$tiMenu.']<br>';
$GLOBALS["tDetails"]		.= '['.$tiMenu.']<br>';

$GLOBALS["hTopMenuBorder"]	 = 'Epaisseur de la bordure entre les titres dans le menu horizontal.<br>';
$GLOBALS["hTopMenuBorder"]	.= 'Réglez cette valeur sur 0 si vous ne voulez pas de bordure.';
$GLOBALS["hTopMenuAlign"]	 = 'Doit on aligner les données du menu à '.$GLOBALS["tLeft"].', '.$GLOBALS["tRight"].' ou remplir l\'espace total du menu horizontal ('.$GLOBALS["tCentre"].')?';
$GLOBALS["hTopMenuSeparator"]	 = 'Sélectionnez le style des séparateurs à utiliser entre les données du menu horizontal.';
$GLOBALS["hMenuBorder"]		 = 'Epaisseur de la bordure entre les groupes de menu dans le menu latéral.<br>';
$GLOBALS["hMenuBorder"]		.= 'Réglez cette valeur sur 0 si vous ne voulez pas de bordure.';
$GLOBALS["hTopDistance"]	 = 'Distance entre le haut de la frame et le premier titre dans le menu latéral.';
$GLOBALS["hBetweenDistance1"]	 = 'La distance entre un groupe de menu et n\'importe lequel des sous groupes de menu.';
$GLOBALS["hBetweenDistance2"]	 = 'La distance entre les sous-menus.';
$GLOBALS["hIndent"]		 = 'Indentation entre la bord gauche du menu et le sous-groupe du menu.';
$GLOBALS["hPrivateMenus"]	 = 'Les Menus Privés apparaissent soit '.$GLOBALS["tLocked"].' ou '.$GLOBALS["tHidden"].'?';

// Error Messages
$GLOBALS["eBorder1"]		= 'La Bordure du menu n\'est pas un nombre.';
$GLOBALS["eBorder2"]		= 'La Bordure du menu n\'est pas un nombre.';
$GLOBALS["eDistance1"]		= 'La Distance entre le haut du premier menu n\'est pas un nombre.';
$GLOBALS["eDistance2"]		= 'La Distance entre le menu et le menu/sous-menu n\'est pas un nombre.';
$GLOBALS["eDistance3"]		= 'La Distance entre les sous-menus n\'est pas un nombre.';
$GLOBALS["eDistance4"]		='L\'indentation des Sous menus n\'est pas un nombre.';

?>
