<?

// 18-9-2003 22:44

// Form Title
$GLOBALS["tFormTitle"]		= 'Beheer reclameblokken';

// List Headings
$GLOBALS["tURL"]		= 'URL';
$GLOBALS["tImpressions"]	= 'Getoond';
$GLOBALS["tClicks"]		= 'Geklikt';

// List Functions
$GLOBALS["tAddNewBanner"]	= 'Nieuw reclameblok toevoegen';
$GLOBALS["tViewBanner"] 	= 'Toon reclameblok';
$GLOBALS["tEditBanner"]		= 'Reclameblok bewerken';
$GLOBALS["tDeleteBanner"]	= 'Reclameblok verwijderen';

//  Form Block Titles
$GLOBALS["thDetails"] = 'Reclameblok weergave details';
$GLOBALS["thStatus"] = 'Status';
$GLOBALS["thLog"] = 'Logging';

// Form Field Headings
$GLOBALS["tTarget"]		= 'Externe URL';
$GLOBALS["tAltText"]		= 'Mouseover tekst bij een afbeelding';
$GLOBALS["tPublishDate"]	= 'Publicatie datum';
$GLOBALS["tExpireDate"]		= 'Verloop datum';
$GLOBALS["tActive"]		= 'Actief';
$GLOBALS["tBannerImage"]	= 'Reclameblok afbeelding';
$GLOBALS["tBannerHTML"]		= 'Reclameblok HTML';

// Other Form Headings
$GLOBALS["tShowBanner"]		= 'Toon reclameblok';

// Form Detail Comments and Help Texts
$GLOBALS["tDetails"] 		 = 'Met deze pagina kunt u instellen welke reclameblokken (ofwel \'banners\') op uw website getoond worden.';
$GLOBALS["hTarget"]		 = 'De URL van de website nadat er op geklikt wordt.<br>';
$GLOBALS["hTarget"]		.= '(Altijd toevoegen: "http://" aan het begin van het link.)';
$GLOBALS["hAltText"]		 = 'Mouseover tekst voor de reclameblok afbeelding.';
$GLOBALS["hPublishDate"]	 = 'Publicatie datum van het reclameblok. Het zal niet getoond worden voor deze datum.';
$GLOBALS["hExpireDate"]		 = 'Verloop datum van het reclameblok. Het reclameblok wordt niet meer getoond na deze datum.';
$GLOBALS["hEnabled"]		 = 'Als het ingesteld is op "'.$GLOBALS["tNo"].'" zal het reclameblok nooit worden geselecteerd.';
$GLOBALS["hActive"]		 = 'Als het ingesteld is op "'.$GLOBALS["tNo"].'" zal het reclameblok nooit worden geselecteerd.';
$GLOBALS["hImpressions"]	 = 'Aantal keren dat dit reclameblok getoond werd. Gebruik dit veld om de waarde te resetten.';
$GLOBALS["hClicks"]		 = 'Aantal keren dat op het reclameblok geklikt is. Gebruik dit veld om de waarde te resetten.';
$GLOBALS["hBannerImage"]	 = 'Specificeer de bestandsnaam van het reclameblok.<br>';
$GLOBALS["hBannerImage"]	 = 'U kunt ook HTML specificeren in: "'.$GLOBALS["tBannerHTML"].'" om het reclameblok te tonen, maar niet allebei.';
$GLOBALS["hBannerHTML"]		 = 'U kunt hier de HTML specificeren naar de locatie waar het reclameblok staat zodat er het nodig is om een afbeelding te uploaden. bv:<br>';
$GLOBALS["hBannerHTML"]		.= '&lt;A HREF=&quot;http://www.domain.nl&quot;&gt;<br>';
$GLOBALS["hBannerHTML"]		.= '&lt;IMG SRC=&quot;http://www.domain.nl/images/banner.gif&quot;&gt;&lt;/A&gt;<br>';
$GLOBALS["hBannerHTML"]		.= 'Dit kan verwijzen naar een afbeelding op een andere server of een java applet dat het reclameblok genereert.<br>';
$GLOBALS["hBannerHTML"]		.= 'Een alternatief gebruik hiervan is bijvoorbeeld om een "quote-afbeelding" te genereren die steeds anders is onderin het scherm.';

// Error Messages
$GLOBALS["eNoURL"]		= 'Doel URL mag niet leeg zijn.';
$GLOBALS["eImpressionsNum"]	= 'Getoonde waarde moet nummeriek zijn.';
$GLOBALS["eClicksNum"]		= 'Klik waarde moet nummeriek zijn.';
$GLOBALS["eNoImage"]		= 'Een afbeelding of URL moet worden gespecificeerd.';
$GLOBALS["eBothImageHTML"]	= 'Specificeer de afbeelding of HTML, niet allebei.';

?>
