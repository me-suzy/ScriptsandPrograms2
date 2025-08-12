<?

// Form Title
$GLOBALS["tFormTitle"]		= 'Gestion des Réglages du Site';

// Form Field Headings
$GLOBALS["tGzipSetting"]	= 'Utiliser la compression gzip';
$GLOBALS["tGzipTest"]		= 'Test compression';
$GLOBALS["tGzipSupported"]	= 'Your server supports GZIP compression.';
$GLOBALS["tGzipUnsupported"]	= 'Your server does not support GZIP compression.';
$GLOBALS["tPageTimer"]		= 'Montre le Temps mis pour générer une page';
$GLOBALS["tDefaultLanguage"]	= 'Langue par défaut';
$GLOBALS["tTimezone"]		= 'Zone Horaires du Serveur';
$GLOBALS["tFrameSetting"]	= 'Avec Frames/Sans frames';
$GLOBALS["tSiteTitle"]		= 'Titre du Site';
$GLOBALS["tSiteDescr"]		= 'Description du Site';
$GLOBALS["tSiteKeywords"]	= 'Mots Clés du Site';
$GLOBALS["tSiteWidth"] = 'Site largeur';
$GLOBALS["tSiteHeight"] = 'Site hauteur';
$GLOBALS["tSectionSecurity"] = 'Section niveau de sécurité';
$GLOBALS["tTopFrameHeight"]	= 'Hauteur de la Frame du Haut';
$GLOBALS["tTopHTML"]		= 'HTML du Haut';
$GLOBALS["tTopMenu"]		= 'Inclure le menu horizontal dans le header';
$GLOBALS["tTopMenuHeight"]	= 'Hauteur du Menu Horizontal';
$GLOBALS["tUserdata"]		= 'Inclure les données utilisateur dans le header';
$GLOBALS["tUserdataWidth"]	= 'Largeur de la frame des données utilisateur';
$GLOBALS["tMenuWidth"]		= 'Largeur de la frame du Menu';
$GLOBALS["tMenuFrameAlign"] = 'L\'alignement du cadre de Menu';
$GLOBALS["tMenuExpand"] = 'Agrandir le menus sur le déclic';
$GLOBALS["tMenuCollapse"]	= 'Déplier les menus par clic';
$GLOBALS["tMenuHover"]		= 'Texte du Tag Alt du menu Visible';
$GLOBALS["tLRContent"]		= 'Diviser frame du contenu en colonnes gauche/droite ';
$GLOBALS["tRightWidth"]		= 'Largeur de la colonne de droite';
$GLOBALS["tColBreak"]		= 'Image de séparation de la colonne gauche/droite du contenu';
$GLOBALS["tBreadcrumb"] = 'Activer \'breadcrumb\' affichage';
$GLOBALS["tBreadcrumbSeparator"] = '\'breadcrumb\' separateur';
$GLOBALS["tBookmark"] = 'Activer favoris';
$GLOBALS["tBanners"]		= 'Bannières Visibles';
$GLOBALS["tFooter"]		= 'Inclure la frame du footer';
$GLOBALS["tFooterHeight"]	= 'Hauteur de la Frame du Footer';
$GLOBALS["tFooterText"]		= 'Texte du Footer';

// Form Field Options
$GLOBALS["tGzipCompression"]	= 'Compresser le texte';
$GLOBALS["tGzipNoCompression"]	= 'Pas de compression';
$GLOBALS["tTimerDisplay"]	= 'Temps du Parser Visible';
$GLOBALS["tTimerNoDisplay"]	= 'Pas de Temps du Parser';
$GLOBALS["tFrames"]		= 'Avec Frames';
$GLOBALS["tNoFrames"]		= 'Sans frames';
$GLOBALS["tNoBanners"]		= 'Ne pas montrer les Bannières';
$GLOBALS["tBannersT"]		= 'Montrer les bannières en Haut';
$GLOBALS["tBannersB"]		= 'Montrer les bannières en Bas';
$GLOBALS["tBannersTB"]		= 'Montrer les bannières en Haut et en Bas';

$GLOBALS["tRefreshMenu"]	= 'Rafraichissez le menu latéral si vous avez modifié ce réglage pour updater les options du menu.';

// Form Text Description
$GLOBALS["tDetails"]		 = 'Ce formulaire vous permet de régler les valeurs globales avec lesquelles votre Site apparaitra.';
$GLOBALS["hGzipSetting"]	 = 'Choisissez ce réglage pour valider la compression gzip si votre serveur le supporte.';
$GLOBALS["hPageTimer"]		 = 'Montrer le Temps que met le parser pour générer les pages.<br>';
$GLOBALS["hPageTimer"]		.= 'Outil de diagnostic pour les développeurs de ezContents pour tester l\'efficacité du code.';
$GLOBALS["hDefaultLanguage"]	 = 'Régler la langue par défaut de votre site.';
$GLOBALS["hTimezone"]		 = 'Zone Horaires pour montrer la date et l\'heure.<br>';
$GLOBALS["hTimezone"]		.= 'ex: "UST", "UST+1"';
$GLOBALS["hFrameSetting"]	 = 'Voir le site en version Avec Frames/Sans Frames.';
$GLOBALS["hSiteTitle"]		.= 'Le titre du Site que vous verrez dans la barre de titre du Navigateur.<br>';
$GLOBALS["hSiteDescr"]		 = 'Spécifie les meta tags HTML de description.<br>';
$GLOBALS["hSiteDescr"]		.= 'Utile pour l\'indexation par les moteurs de recherche.';
$GLOBALS["hSiteKeywords"]	 = 'Spécifie les meta tags HTML des Mots Clés.<br>';
$GLOBALS["hSiteKeywords"]	.= 'Utile pour l\'indexation par les moteurs de recherche. Placez ici tous les mots clés séparés par des virgules.';
$GLOBALS["hTopFrameHeight"]	 = 'Défini la Hauteur de la Frame du Haut.';
$GLOBALS["hTopHTML"]		 = 'Inclus tout code HTML additionnel pour la Frame du Haut.';
$GLOBALS["hTopMenu"]		 = 'Indique si le menu horizontal doit être montré dans le header.<br>';
$GLOBALS["hTopMenu"]		.= 'Si vous validez cette option, vous devrez aussi spécifier la '.$GLOBALS["tTopMenuHeight"].'.<br><br>';
$GLOBALS["hTopMenu"]		.= 'Si vous  modifiez ce réglage, rafraichissez le menu latéral si vous avez modifié ce réglage pour updater les options du menu.';
$GLOBALS["hTopMenuHeight"]	 = 'Détermine la Hauteur du Menu Horizontal s\'il est sélectionné.<br>';
$GLOBALS["hTopMenuHeight"]	.= 'Ceci s\'additionne à la '.$GLOBALS["tTopFrameHeight"].'.';
$GLOBALS["hUserdata"]		 = 'Indique si la frame des ("données utilisateur") apparaitra à droite dans le header.<br>';
$GLOBALS["hUserdata"]		.= 'Si vous validez cette option, vous devrez spécifier la '.$GLOBALS["tUserdataWidth"].'.';
$GLOBALS["hUserdataWidth"]	 = 'Spécifie la largeur de la frame des données utilisateur si vous avez validé cette option.';
$GLOBALS["hMenuWidth"]		 = 'Ajuste la largeur de la frame gauche du menu latéral.';
$GLOBALS["hMenuCollapse"]	 = 'Si vous choisissez de relier les menus un seul menu sera délié avec ses sous-menus.';
$GLOBALS["hMenuHover"]		 = 'Montre le texte du menu dans une petite boite quand la souris survole le titre du menu.';
$GLOBALS["hLRContent"]		 = 'Cela détermine si le contentu des pages peut être diviser en 2 colonnes gauche/droite dans la frame du contenu.<br>';
$GLOBALS["hLRContent"]		.= 'Si vous validez cette option, vous devrez spécifier la '.$GLOBALS["tRightWidth"].'.';
$GLOBALS["hRightWidth"]		 = 'Ceci est le Largeur de la Colonne de Droite dans la frame du contenu si vous avez validé cette option.';
$GLOBALS["hColBreak"]		 = 'Si vous avez validé la séparation de la frame du contenu, vous pouvez aussi spécifier une image de séparartion des colonnes.';
$GLOBALS["hBanners"]		 = 'Indiquez ici si vous voulez que les bannières soint visibles sur votre site.<br>';
$GLOBALS["hBanners"]		.= 'Ce réglage vous permet de monter les bannières soit en haut de la page, soit en bas, ou les deux';
$GLOBALS["hFooter"]		 = 'Inclus un frame de footer en bas de votre page';
$GLOBALS["hFooter"]		.= 'Si vous validez cette option, vous devrez spécifier la '.$GLOBALS["tFooterHeight"].', et le (optionnel) '.$GLOBALS["tFooterText"].'.';
$GLOBALS["hFooterHeight"]	 = 'Spécifie la Hauteur de la frame du footer si vous avez validé cette option.';
$GLOBALS["hFooterText"]		 = 'Si vous avez validé le frame de footer, spécifie le contenu la frame en texte ou en HTML.';

// Error Messages
$GLOBALS["eTopFrame"]		= 'La Hauteur de la Frame du Haut n\'est pas un nombre.';
$GLOBALS["eTopmenuFrame"]	= 'La Hauteur de la Frame du Menu Horizontal n\'est pas un nombre.';
$GLOBALS["eUserdataFrame"]	= 'La Largeur de la Frame des Données Utilisateur n\'est pas un nombre.';
$GLOBALS["eMenuFrame"]		= 'La Largeur de la Frame du Menu n\'est pas un nombre.';
$GLOBALS["eRightFrame"]		= 'La Largeur de la Frame de Droite n\'est pas un nombre.';
$GLOBALS["eBottomFrame"]	= 'La Hauteur de la Frame du Bas n\'est pas un nombre.';

?>
