<?

// Form Title
$GLOBALS["tFormTitle"]		= 'Gestion du contenu';

// List Headings
$GLOBALS["tMenu"]		= 'Menu';
$GLOBALS["tSubmenu"]		= 'Sous-menu';
$GLOBALS["tTitle"]		= 'Titre';
$GLOBALS["tPageNumber"]		= 'Page';

// List Functions
$GLOBALS["tMenuFilter"]		= 'Filtre du Menu';
$GLOBALS["tAddNewContent"]	= 'Ajouter nouvelle page de contenu';
$GLOBALS["tEditContent"]	= 'Editer contenu';
$GLOBALS["tDeleteContent"]	= 'Effacer contenu';

// Form Field Headings
$GLOBALS["tPageID"]		= 'ID de la Page';
$GLOBALS["tMenu"]		= 'Menu';
$GLOBALS["tSubmenu"]		= 'Sous-menu';
$GLOBALS["tPublishDate"]	= 'Date de Publication';
$GLOBALS["tExpiryDate"]		= 'Date D\'Expiration';
$GLOBALS["tHeader"]		= 'Titre du Header';
$GLOBALS["tHeaderImage"]	= 'Image du Header';
$GLOBALS["tTeaser"]		= 'Résumé';
$GLOBALS["tBodyText"]		= 'Corps du texte';
$GLOBALS["tOrderID"]		= 'Ordre ID';
$GLOBALS["tShowHeader"]		= 'Header Visible';
$GLOBALS["tShowAuthor"]		= 'Auteur Visible';
$GLOBALS["tShowUpdate"]		= 'Date update Visible';
$GLOBALS["tTeaserImage"]	= 'Image du Résumé';
$GLOBALS["tTeaserImageAlign"]	= 'Alignement image du résumé';
$GLOBALS["tDetailImage"]	= 'Image page Détails';
$GLOBALS["tDetailImageAlign"]	= 'Alignement image page Détails';
$GLOBALS["tLeftRight"]		= 'Colonne Gauche/Droite';

// Form Detail Comments and Help Texts
$GLOBALS["tDetails"]		 = 'Ce formulaire vous permet d\'éditer ou de créer de nouveaux articles.<br><br>';
$GLOBALS["tDetails"]		.= '1. Si le texte du résumé est vide, alors le texte complet apparaitra à la place du résumé.<br>';
$GLOBALS["tDetails"]		.= '2. Si le texte du résumé est rempli et que le corps du texte complet est vide, "Lire la suite..." n\'apparaitra pas.<br>';
$GLOBALS["tDetails"]		.= '3. Si le texte du résumé et le texte complet sont remplis, le résumé apparaitra en premier,<br>';
$GLOBALS["tDetails"]		.= 'et "Lire la suite..." servira de lien vers la page du texte complet.<br><br>';
$GLOBALS["tDetails"]		.= 'Si vous utilisez du code HTML il sera encodé et le contenu sera visible. <br>';
$GLOBALS["tDetails"]		.= 'Vous pouvez utiliser des tags personnalisés. Sont inclus:<br>';
$GLOBALS["tDetails"]		.= '1. [link]http://www.ezcontents.org, Visual Shapers[/link] - Apparaitra ainsi: <a href="http://www.ezcontents.org/" target="_blank">Visual Shapers</a><br>';
$GLOBALS["tDetails"]		.= '2. [email]ezcontents_info@visualshapers.com[/email] - Apparaitra ainsi: <a href="mailto:ezcontents_info@visualshapers.com">ezcontents_info@visualshapers.com</a><br>';
$GLOBALS["tDetails"]		.= '3. [code]&lt;? phpfunction(); ?&gt;[/code] - Apparaitra en petite taille de fonte (toujours encodé en html). Ex:pour montrer du code source PHP.<br>';
$GLOBALS["tDetails"]		.= '4. [html]&lt;a href="link.html"&gt;Link&lt;/a&gt;[/html] - Apparaitra sans être encodé en html. Par ce fait, vous pourrez ajouter du code HTML personnalisé.<br>';
$GLOBALS["tDetails"]		.= '5. [pagelink]23, Lien vers page 23[/pagelink] - Lien direct vers le contenu de la page.';

$GLOBALS["hMenu"]		 = 'Ceci est le menu vertical à partir duquel sera sera généré le contenu.';
$GLOBALS["hSubmenu"]		 = 'Ceci est le sous groupe de menu vertical à partir duquel sera sera généré le contenu.';
$GLOBALS["hPublishDate"]	 = 'La date de publication du contenu, avant laquelle la page ne sera pas visible.';
$GLOBALS["hExpiryDate"]		 = 'La date d\'expiration du contenu, après laquelle la page ne sera plus visible.';
$GLOBALS["hHeader"]		 = 'Le titre qui apparaitra dans la barre de titre pour cette page si  '.$GLOBALS["tShowHeader"].' est validé.';
$GLOBALS["hHeaderImage"]	 = 'Une image qui apparaitra en tant qu\'alternative au titre du texte pour cette page si '.$GLOBALS["tShowHeader"].' est validé.';
$GLOBALS["hTeaser"]		 = 'Texte du Résumé.<br>';
$GLOBALS["hTeaser"]		.= 'Si vous entrez du texte dans ce champ, il apparaitra sur la page à la place du texte complet, avec un "'.$GLOBALS["tReadMore"].'" comme lien vers le texte principal.';
$GLOBALS["hBodyText"]		 = 'Ceci est le texte principal de votre contenu.';
$GLOBALS["hOrderID"]		 = 'Vous pouvez spécifier un '.$GLOBALS["tOrderID"].' pour controler le tri de vos articles dans la page.';
$GLOBALS["hShowHeader"]		 = 'Si cette option est validée, le texte du header ou une image apparaitra dans une barre de titre au dessus du résumé ou du texte complet de votre contenu.';
$GLOBALS["hShowAuthor"]		 = 'Si cette option est validée, dans le bas de votre contenu apparaitra le nom (avec un lien e-mail) de l\'auteur qui à posté ce contenu.';
$GLOBALS["hShowUpdate"]		 = 'Si cette option est validée, dans le bas de votre contenu apparaitra la date de modification, la date originelle de publication, ou sa dernière édition.';
$GLOBALS["hTeaserImage"]	 = 'Ici, vous pouvez spécifier une image à placer dans le haut du résumé.';
$GLOBALS["hTeaserImageAlign"]	 = 'Si vous avez sélectionné une image pour le résumé, vous pouvez la disposer à '.$GLOBALS["tLeft"].' ou à '.$GLOBALS["tRight"].' dans n\'importe quel texte de résumé.';
$GLOBALS["hDetailImage"]	 = 'Ici, vous pouvez spécifier une image à placer dans le haut du texte principal.';
$GLOBALS["hDetailImageAlign"]	 = 'Si vous avez sélectionné une image pour le texte principal, vous pouvez la disposer à '.$GLOBALS["tLeft"].' ou à '.$GLOBALS["tRight"].' du texte principal.';;
$GLOBALS["hLeftRight"]		 = 'Ceci détermine si le contenu de cette page apparait dans la colonne '.$GLOBALS["tLeft"].' ou  '.$GLOBALS["tRight"].' du contenu.';

?>
