<?php

//  Form Title
$GLOBALS["tFormTitle"] 		= 'Beheer inhoud';
$GLOBALS["tFormTitle2"] 	= 'Beheer inhoud vertalingen';

//  List Headings
$GLOBALS["tMenu"] 		= 'Menu';
$GLOBALS["tPageRef"] 		= 'Artikel naam';
$GLOBALS["tSubmenu"] 		= 'Submenu';
$GLOBALS["tTitle"] 		= 'Titel';
$GLOBALS["tRSSen"] 		= 'RSS';
$GLOBALS["tSearchen"] 		= 'Zoeken';

//  List Functions
$GLOBALS["tMenuFilter"] 	= 'Menu filter';
$GLOBALS["tAllMenus"] 		= 'Alle menu\'s';
$GLOBALS["tAddNewContent"] 	= 'Nieuw artikel toevoegen';
$GLOBALS["tEditContent"] 	= 'Bewerk artikel';
$GLOBALS["tDeleteContent"] 	= 'Verwijder artikel';
$GLOBALS["tTranslate"] 		= 'Vertaal artikel';
$GLOBALS["tViewTranslation"] 	= 'Laat vertaling zien';
$GLOBALS["tEditTranslation"] 	= 'Bewerk vertaling';

//  Form Block Titles
$GLOBALS["thContentLinks"] 	= 'Artikel link details';
$GLOBALS["thDates"] 		= 'Datums voor weergave';
$GLOBALS["thHeader"] 		= 'Kop';
$GLOBALS["thTeaser"] 		= 'Intro';
$GLOBALS["thBodyContent"] 	= 'Artikel inhoud';
$GLOBALS["thFooter"] 		= 'Voet';
$GLOBALS["thPosition"] 		= 'Positie op pagina';
$GLOBALS["thRatings"] 		= 'Waardering opties';
$GLOBALS["thRSS"]			 		= 'RSS / Zoeken';

//  Form Field Headings
$GLOBALS["tPublishDate"] 	= 'Publicatie datum';
$GLOBALS["tExpiryDate"] 	= 'Verloop datum';
$GLOBALS["tHeader"] 		= 'Kop titel';
$GLOBALS["tHeaderImage"] 	= 'Kop afbeelding';
$GLOBALS["tTeaser"] 		= 'Intro';
$GLOBALS["tBodyText"] 		= 'Artikel tekst';
$GLOBALS["tShowHeader"] 	= 'Laat kop zien';
$GLOBALS["tShowAuthor"] 	= 'Laat auteur zien';
$GLOBALS["tShowUpdate"] 	= 'Laat update datum zien';
$GLOBALS["tTeaserImage"] 	= 'Intro afbeelding';
$GLOBALS["tTeaserImageAlign"] = 'Uitlijnen intro afbeelding';
$GLOBALS["tDetailImage"] 	= 'Afbeelding in artikel';
$GLOBALS["tDetailImageAlign"] = 'Uitlijnen afbeelding in artikel';
$GLOBALS["tLeftRight"] 		= 'Linker/Rechter kolom';
$GLOBALS["tCanRate"] 		= 'Toestaan artikel beoordeling';
$GLOBALS["tCanComment"] 	= 'Toestaan artikel commentaar';
$GLOBALS["tPrinterFriendly"] 	= 'Toestaan "print vriendelijk" scherm';
$GLOBALS["tAuthor"] 		= 'Artikel auteur';
$GLOBALS["tRSS"] 		= 'Laat artikel meedoen met RSS';
$GLOBALS["tSearch"]	= 'Laat artikel meedoen met zoekopdracht';

//  Form Detail Comments and Help Texts
$GLOBALS["tDetails"] 		= 'Met dit formulier kunt u nieuwe artikelen aanmaken of wijzigen.<br /><br />1. Als de intro tekst leeg gelaten wordt, dan wordt de inhoud van het artikel op de pagina getoond.<br />2. Als de intro tekst gevuld is en de artikel tekst is leeg, dan wordt de tekst "Lees verder.... niet getoond.<br />3. Als de intro tekst gevuld is en de artikel tekst is gevuld, dan wordt de intro tekst op de inhoudspagina getoond,<br />en dan wordt "Lees verder..." wordt als link getoond naar de artikeltekst in een nieuwe pagina.<br /><br />Indien u gebruik maakt van HTML code wordt dit "encoded" gemaakt, zodat u de inhoud ziet zoals u die ingeeft.';

$GLOBALS["hMenu"]			= 'Dit is de menulink waaronder deze pagina wordt getoond.';
$GLOBALS["hSubmenu"]		= 'Dit is de submenu link waaronder deze pagina wordt getoond.';
$GLOBALS["hPublishDate"]	= 'De publicatiedatum van de pagina. Hier kunt u ook een datum opgegeven vanaf wanneer de pagina op de site te zien zal zijn.';
$GLOBALS["hExpiryDate"]		= 'De verloopdatum van de pagina. Na deze datum is de pagina niet meer op de website te zien.';
$GLOBALS["hHeader"]		= 'De koptitel die zal worden getoond op de 1e (intro) pagina als "'.$GLOBALS["tShowHeader"].'" is aangezet.';
$GLOBALS["hHeaderImage"]	 = 'Een afbeelding die zal worden getoond op de plaats van de koptitel, als'.$GLOBALS["tShowHeader"].' is aangezet.';
$GLOBALS["hTeaser"]		 = 'Dit is de vervolg pagina waar bezoekers op komen als ze hebben geklikt op: "'.$GLOBALS["tReadMore"].'" Als u de een korte pakkendende tekst heeft bij Intro pagina, dan is dit de hoofdpagina.';
$GLOBALS["hBodyText"]		 = 'De tekst voor de eerste pagina. U kunt dit zien als een korte pakkende tekst of als een complete pagina.<br>Als de intro tekst gevuld is en hoofdtekst leeg is zal de link "'.$GLOBALS["tReadMore"].'" niet verschijnen.';
$GLOBALS["hShowHeader"]		 = 'Als dit is aangezet zal de koptitel of de kopafbeelding worden getoond bovenaan de pagina.';
$GLOBALS["hShowAuthor"]		 = 'Als dit is aangezet, zal onderaan de pagina de naam van de auteur van de pagina (met een e-mail link).';
$GLOBALS["hShowUpdate"]		 = 'Als dit is aangezet, zal onderaan de pagina de datum van de laatste bewerking te zien zijn. Dit is voornamelijk bij nieuwsachtige pagina\'s van belang.';
$GLOBALS["hTeaserImage"]	 = 'U kunt hier een plaatje selecteren dat op de 1e pagina (of: intro tekst) komt te staan.';
$GLOBALS["hTeaserImageAlign"]	 = 'Dit is de uitlijning: Hier geeft u aan of het plaatje '.$GLOBALS["tLeft"].' of '.$GLOBALS["tRight"].' komt te staan ten opzichte van de introtekst.';
$GLOBALS["hDetailImage"]	 = 'U kunt hier een plaatje selecteren dat op de pagina met hoofdtekst komt te staan.';
$GLOBALS["hDetailImageAlign"]	 = 'Dit is de uitlijning: Hier geeft u aan of het plaatje: '.$GLOBALS["tLeft"].' of '.$GLOBALS["tRight"].' komt te staan ten opzichte van de hoofdtekst.';
$GLOBALS["hLeftRight"]		 = 'Bij meerdere \"intro\'s\" op een pagina is het mogelijk om aan te geven of de intro\'s naast of onder elkaar moeten komen te staan. De optie '.$GLOBALS["tRight"].' zet een intro naast een eerder gepubliceerde intro. De optie '.$GLOBALS["tLeft"].' zet altijd alle intro\'s onder elkaar.';

$GLOBALS["hLeftRight"] 		= 'Hiermee geeft u aan of de inhoud van het artikel in de linker of rechter kolom van de pagina komt.';
$GLOBALS["hCanRate"] 		= 'Als dit is aangezet, kan een artikel worden beoordeeld.';
$GLOBALS["hCanComment"] 	= 'Als dit is aangezet, kunnen bezoekers een artikel van commentaar voorzien.';
$GLOBALS["hPrinterFriendly"] 	= 'Als dit is aangezet, komt er een Printer vriendelijk icoon bij het artikel.';
$GLOBALS["hAuthor"] 		= 'De auteur van dit artikel.';
$GLOBALS["hRSS"] 					= 'Als dit is aangezet, wordt een artikel meegenomen met RSS.';
$GLOBALS["hSearch"] 			= 'Als dit is aangezet, wordt een artikel meegenomen met de zoekfunctie.';

//  Error Messages
$GLOBALS["eArticleExists"] 	= 'Een artikel met deze naam bestaat al, kies een ander naam aub.';
$GLOBALS["eInvalidName"] 	= 'In het artikelnaam staan ongeldige tekens.';

?>
