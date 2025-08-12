<?

// Form Title
$GLOBALS["tFormTitle"]		= 'Gestion des sous-menus';

// List Headings
$GLOBALS["tSubmenuTitle"]	= 'Titre du Sous-menu';
$GLOBALS["tMenuTitle"]		= 'Titre du Menu';

// List Functions
$GLOBALS["tMenuFilter"]		= 'Filtre du Menu';
$GLOBALS["tAddNewSubmenu"]	= 'Ajouter nouveau sous-menu';
$GLOBALS["tEditSubmenu"]	= 'Editer sous-menu';
$GLOBALS["tDeleteSubmenu"]	= 'Effacer sub-menu';

// Form Field Headings
$GLOBALS["tSubmenuTitle"]	= 'Titre du Sous-menu';
$GLOBALS["tParentMenu"]		= 'Menu Parent du Sous-menu';
$GLOBALS["tMenuLink"]		= 'Lien du Sous-menu';
$GLOBALS["tOpenMenuLink"]	= 'Ouvrir lien du sous-menu dans la page';
$GLOBALS["tMenuImage1"]		= 'Image du Sous-menu';
$GLOBALS["tMenuImage2"]		= 'Image du Sous-menu - survol de la souris';
$GLOBALS["tMenuHover"]		= 'Texte du survol du sous-menu';
$GLOBALS["tShowMenu"]		= 'Sous-menu Visible';
$GLOBALS["tOrderID"]		= 'Ordre ID';
$GLOBALS["tOrderBy"]		= 'Trier le contenu par';
$GLOBALS["tOrderDir"]		= 'Sens du Tri';
$GLOBALS["tMLoginReq"]		= 'Login requis';
$GLOBALS["tUsergroup"]		= 'Groupe Utilisateur';

// Form Text Description
$GLOBALS["tDetails"]		 = 'Ce formulaire vous permet d\'éditer ou de créer de nouveaux groupes de sous-menu pour naviguer dans le contenu.';
$GLOBALS["hSubmenuTitle"]	.= 'Nom du groupe de menu. This will be a link to the contents in this menu.';
$GLOBALS["hParentMenu"]		.= 'Le menu parent de ce sous-menu.';
$GLOBALS["hMenuLink"]		 = 'Si ce champ est rempli, un lien réel sera crée avec le lien spécifié par le menu. ';
$GLOBALS["hMenuLink"]		.= 'ex: http://www.altavista.com montrera la page altavista dans la frame du contenu. ';
$GLOBALS["hMenuLink"]		.= 'Il ne sera pas possible de lier tout autre contenu à ce groupe.<br>';
$GLOBALS["hMenuLink"]		.= 'De cette facon vous pouvez créer des liens personnalisés.<br><br>';
$GLOBALS["hMenuLink"]		.= 'ezContents inclus des modules de plug-in que vous pouvez utiliser pour "recherche", "quoi de neuf" ou autres add-ons.';
$GLOBALS["hMenuLink"]		.= '<ul><li>Pour une page "recherche" spécifiez: modules/search/search.php';
$GLOBALS["hMenuLink"]		.= '<li>Pour la page "quoi de neuf" spécifiez: modules/whatsnew/whatsnew.php</ul>';
$GLOBALS["hOpenMenuLink"]	 = 'Les liens du Menu s\'ouvrent dans une nouvelle fenêtre quand vous êtes en mode sans-frames.<br>';
$GLOBALS["hOpenMenuLink"]	.= 'Cocher ce bouton force un lien externe à s\'ouvrir dans le contenu de la fenêtre de EzContents. ';
$GLOBALS["hOpenMenuLink"]	.= 'Ne fonctionnera correctement que si le script de la page externe est compatible avec EzContents.';
$GLOBALS["hMenuImage1"]		 = 'L\'image qui remplacera le titre du menu. ';
$GLOBALS["hMenuImage1"]		.= '(Doit être inférieure à la largeur de votre de votre menu, comprenant l\'indentation définie dans les réglages du menu.)';
$GLOBALS["hMenuImage2"]		 = 'L\'image qui apparaitra lorsque la souris survolera l\'image. ';
$GLOBALS["hMenuImage2"]		.= '(Doit être inférieure à la largeur de votre de votre menu, comprenant l\'indentation définie dans les réglages du menu.)';
$GLOBALS["hMenuHover"]		 = 'Texte qui apparaitra lors du survol de la souris sur un titre du menu, si vous avez validé cette option.';
$GLOBALS["hShowMenu"]		 = 'Si le menu est visible ou non.';
$GLOBALS["hOrderID"]		 = 'L\'ordre de tri du ID dans lequel les titres du menu apparaitront quand "'.$GLOBALS["tOrderBy"].'" est réglé sur Ordre ID.';
$GLOBALS["hOrderBy"]		 = 'Sélectionne le type de tri par lequel le contenu de ce menu sera trié.';
$GLOBALS["hOrderDir"]		 = 'Sélectionne le sens du Tri ('.$GLOBALS["tAscending"].' ou '.$GLOBALS["tDescending"].').';
$GLOBALS["hMLoginReq"]		 = 'Si ce réglage est validé, un login est requis pour accéder à cette option du menu.';
$GLOBALS["hUsergroup"]		 = 'L\'accès est réservé aux membres de ce groupe ou du groupe supérieur.';

// Error Messages
$GLOBALS["eMenuExists"]		= 'A menu with this name already exists.';
$GLOBALS["eTitleEmpty"]		= 'Le Titre du Sous Menu ne peut pas être vide.';

?>
