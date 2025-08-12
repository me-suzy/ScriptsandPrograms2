<?

// Form Title
$GLOBALS["tFormTitle"]		= 'Gestion des bannières';

// List Headings
$GLOBALS["tURL"]		= 'URL';
$GLOBALS["tImpressions"]	= 'Visualisations';
$GLOBALS["tClicks"]		= 'Clics';

// List Functions
$GLOBALS["tAddNewBanner"]	= 'Ajouter nouvelle bannière';
$GLOBALS["tViewBanner"] = 'Afficher bannière';
$GLOBALS["tEditBanner"]		= 'Editer bannière';
$GLOBALS["tDeleteBanner"]	= 'Effacer bannière';

//  Form Block Titles
$GLOBALS["thDetails"] = 'Afficher Détails Bannière';
$GLOBALS["thStatus"] = 'Statut';
$GLOBALS["thLog"] = 'Logging';

// Form Field Headings
$GLOBALS["tTarget"]		= 'URL de la cible';
$GLOBALS["tAltText"]		= 'Texte du tag alt de l\'Image';
$GLOBALS["tPublishDate"]	= 'Date de Publication';
$GLOBALS["tExpireDate"]		= 'Date D\'Expiration';
$GLOBALS["tActive"]		= 'Activée';
$GLOBALS["tBannerImage"]	= 'Image de la Bannière';
$GLOBALS["tBannerHTML"]		= 'HTML de la Bannière';

// Other Form Headings
$GLOBALS["tShowBanner"]		= 'Bannière Visible';

// Form Detail Comments and Help Texts
$GLOBALS["tDetails"] 		 = 'Ce formulaire vous permet de gérer les bannières qui apparaissent sur votre Site.';
$GLOBALS["hTarget"]		 = 'Url de la cible après avoir cliqué sur la bannière. .<br>';
$GLOBALS["hTarget"]		.= '(Ajouter toujours http:// au début du lien.)';
$GLOBALS["hAltText"]		 = 'Texte du tag Alt apparaissant lors du survol de la souris sur l\'image.';
$GLOBALS["hPublishDate"]	 = 'Date de Publication de la Bannière. Ne sera plus visible si la date est postérieure à la date actuelle.';
$GLOBALS["hExpireDate"]		 = 'Date à laquelle la publication de la Bannière expire. Ne sera plus visible si la date est passée.';
$GLOBALS["hEnabled"] = 'If this is set to \"No\" the banner will never be selected.';
$GLOBALS["hActive"]		 = 'Si vous réglez sur "'.$GLOBALS["tNo"].'" la bannière ne sera jamais visible.';
$GLOBALS["hImpressions"]	 = 'Nombre de fois où la bannière est vue. Utilisez ce champ pour réinitialiser le compteur.';
$GLOBALS["hClicks"]		 = 'Nombre de fois où un visiteur clique sur la bannière. Utilisez ce champ pour réinitialiser le compteur.';
$GLOBALS["hBannerImage"]	 = 'Si vous spécifiez une image ici, ceci remplacera l\'url de l\'image".<br>';
$GLOBALS["hBannerImage"]	 = 'Une altenative est de pouvoir spécifier du code HTML dans le champ "'.$GLOBALS["tBannerHTML"].'" qui publie la bannière; mais pas les deux.';
$GLOBALS["hBannerHTML"]		 = ' Ici vous pouvez spécifier le code html d\'une image existant sur le web à la place de télécharger une image. ex:<br>';
$GLOBALS["hBannerHTML"]		.= '&lt;A HREF=&quot;http://www.domain.com&quot;&gt;<br>';
$GLOBALS["hBannerHTML"]		.= '&lt;IMG SRC=&quot;http://www.domain.com/images/banner.gif&quot;&gt;&lt;/A&gt;<br>';
$GLOBALS["hBannerHTML"]		.= 'Ceci pointe vers une image sur un serveur distant, ou une applet java qui génére la bannière.<br>';
$GLOBALS["hBannerHTML"]		.= 'Une alternative, par exemple, est d\'appeler une application qui génére des citations aléatoires dans le footer de votre page.';

// Error Messages
$GLOBALS["eNoURL"]		= 'URL de la cible ne peut pas être vide.';
$GLOBALS["eImpressionsNum"]	= 'La valeur des visualisations doit être numérique.';
$GLOBALS["eClicksNum"]		= 'La valeur des clics doit être numérique.';
$GLOBALS["eNoImage"]		= 'Une image ou son URL doit être spécifiée.';
$GLOBALS["eBothImageHTML"]	= 'Spécifiez seulement une image ou une bannière html, pas les deux.';

?>
