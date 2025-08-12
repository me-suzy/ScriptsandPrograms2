<?

// Form Title
$GLOBALS["tFormTitle"]		= 'Gestion du Menu Horizontal';

// List Headings
$GLOBALS["tMenuTitle"]		= 'Titre du Menu';
$GLOBALS["tHomepage"]		= 'Page d\'Accueil';

// List Functions
$GLOBALS["tAddNewMenu"]		= 'Ajouter nouveau menu';
$GLOBALS["tEditMenu"]		= 'Editer menu';
$GLOBALS["tDeleteMenu"]		= 'Effacer menu';
$GLOBALS["tMakeHomepage"]	= 'en faire Page d\'accueil';
$GLOBALS["tHomepageSet"]	= 'Page d\'Accueil Actuelle';

// Form Block Titles
$GLOBALS["thGeneral"]		= 'General Reference';
$GLOBALS["thGraphics"]		= 'Graphics';
$GLOBALS["thLinks"]		= 'Modules and Links';
$GLOBALS["thSequence"]		= 'Menu Sequencing';
$GLOBALS["thAccess"]		= 'Access Security';

// Form Field Headings
$GLOBALS["tMenuRef"]		= 'Menu reference';
$GLOBALS["tMenuTitle"]		= 'Titre du Menu';
$GLOBALS["tParentMenu"]		= 'Menu Parent';
$GLOBALS["tMenuLink"]		= 'Lien du Menu';
$GLOBALS["tOpenMenuLink"]	= 'Ouvrir le lien du menu dans une page';
$GLOBALS["tMenuImage1"]		= 'Image du Menu';
$GLOBALS["tMenuImage2"]		= 'Image du Menu - survol de la souris';
$GLOBALS["tMenuImage3"]		= 'Menu image - selected';
$GLOBALS["tMenuHover"]		= 'Texte du survol du Menu';
$GLOBALS["tShowMenu"]		= 'Menu Visible';
$GLOBALS["tOrderID"]		= 'Ordre ID';
$GLOBALS["tOrderBy"]		= 'Trier Contenu par';
$GLOBALS["tOrderDir"]		= 'Sens du Tri';
$GLOBALS["tMLoginReq"]		= 'Login requis';
$GLOBALS["tUsergroup"]		= 'Groupe Utilisateur';

// Form Text Description
$GLOBALS["tDetails"]		 = 'Ce formulaire vous permet d\'éditer ou créer de nouveaux groupes de menu horizontaux pour naviguer dans le contenu.';
$GLOBALS["hMenuRef"]		 = 'This is the internal identifier used by ezContents to refer to this menu item.<br>';
$GLOBALS["hMenuRef"]		.= 'If you leave the field blank, it will automatically be given a sequence number as a reference.';
$GLOBALS["hMenuTitle"]		 = 'Le Nom du groupe de menu. Cela pourra être un lien vers le contenu dans ce menu.';
$GLOBALS["hMenuLink"]		 = 'Si ce champ est rempli, un lien réel sera crée vers le lien spécifié par le menu. ';
$GLOBALS["hMenuLink"]		.= 'ex: http://www.altavista.com montrera la page altavista dans le cadre du contenu. ';
$GLOBALS["hMenuLink"]		.= 'Il ne sera pas possible de lier tout autre contenu à ce groupe.<br>';
$GLOBALS["hMenuLink"]		.= 'De cette facon vous pouvez créer des liens personnalisés.<br><br>';
$GLOBALS["hMenuLink"]		.= 'ezContents inclus des modules de plug-in que vous pouvez utiliser pour "recherche", "quoi de neuf" ou autres add-ons.';
$GLOBALS["hMenuLink"]		.= '<ul><li>Pour une page "recherche" spécifiez: modules/search/search.php';
$GLOBALS["hMenuLink"]		.= '<li>Pour la page "quoi de neuf" spécifiez: modules/whatsnew/whatsnew.php</ul>';
$GLOBALS["hOpenMenuLink"]	 = 'Les liens du Menu s\'ouvrent dans une nouvelle fenêtre quand vous êtes en mode sans-frames.<br>';
$GLOBALS["hOpenMenuLink"]	.= 'Cocher ce bouton force un lien externe à s\'ouvrir dans le contenu de la fenêtre de EzContents. ';
$GLOBALS["hOpenMenuLink"]	.= 'Ne fonctionnera correctement que si le script de la page externe est compatible avec EzContents.';
$GLOBALS["hMenuImage1"]		 = 'L\'image qui remplacera le titre du menu.';
$GLOBALS["hMenuImage2"]		 = 'L\'image qui apparaitra lorsque la souris survolera l\'image. ';
$GLOBALS["hMenuImage3"]		 = 'The image that will be shown when this menu item is the currently selected top menu.';
$GLOBALS["hMenuHover"]		 = 'Texte qui apparaitra lors du survol de la souris sur un titre du menu, si vous avez validé cette option.';
$GLOBALS["hShowMenu"]		 = 'Si le menu est visible ou non.';
$GLOBALS["hOrderID"]		 = 'L\'ordre de tri du ID dans lequel les titres du menu apparaitront quand "'.$GLOBALS["tOrderBy"].'" est réglé sur Ordre ID.';
$GLOBALS["hOrderBy"]		 = 'Sélectionne le type de tri par lequel le contenu de ce menu sera trié.';
$GLOBALS["hOrderDir"]		 = 'Sélectionne le sens du Tri ('.$GLOBALS["tAscending"].' ou '.$GLOBALS["tDescending"].').';
$GLOBALS["hMLoginReq"]		 = 'Si ce réglage est validé, un login est requis pour accéder à cette option du menu.';
$GLOBALS["hUsergroup"]		 = 'L\'accès est réservé aux membres de ce groupe ou du groupe supérieur.';

// Error Messages
$GLOBALS["eMenuExists"]		= 'A top-menu with this name already exists.';
$GLOBALS["eTitleEmpty"]		= 'Le Titre du Menu ne peut pas être vide.';

?>
