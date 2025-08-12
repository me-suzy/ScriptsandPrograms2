<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'maintenir les paramétre du serveur';

//  Form Field Headings
$GLOBALS["tGzipSetting"] = 'Utiliser la compression de gzip';
$GLOBALS["tSecureServer"] = 'Serveur sécuritaire';
$GLOBALS["tGzipTest"] = 'Teste de compression';
$GLOBALS["tGzipSupported"] = 'Votre serveur soutient la compression de GZIP.';
$GLOBALS["tGzipUnsupported"] = 'Votre serveur ne supporte pas la compression GZIP.';
$GLOBALS["tMultiSite"] = 'Multi-Site activer';
$GLOBALS["tMultiSiteAuthors"] = 'Le Multi-Site avec les utilisateurs communs';
$GLOBALS["tMultiLanguage"] = 'Multi-Language activer';
$GLOBALS["tMultiTheme"] = 'Multi-Theme activer';
$GLOBALS["tPageTimer"] = 'Afficher le temps de génération de page';
$GLOBALS["tDefaultLanguage"] = 'Langue par default';
$GLOBALS["tDateFormat"] = 'Date Format Mask';
$GLOBALS["tTimezone"] = 'Timezone du serveur ';
$GLOBALS["tFrameSetting"] = 'Frames/Pas de Frames';
$GLOBALS["tVisitorStats"] = 'Activer statistique d\'accès';

//  Form Block Titles
$GLOBALS["thServerOptions"] = 'Options Serveur';
$GLOBALS["thezContentsOptions"] = 'Options ezContents';

//  Form Field Options
$GLOBALS["tGzipCompression"] = 'Compresser texte';
$GLOBALS["tGzipNoCompression"] = 'Pas de compression';
$GLOBALS["tTimerDisplay"] = 'Afficher minuteur';
$GLOBALS["tTimerNoDisplay"] = 'Ne pas afficher minuteur';
$GLOBALS["tFrames"] = 'Frames';
$GLOBALS["tNoFrames"] = 'Pas de frames';

//  Form Text Description
$GLOBALS["tDetails"] = 'Cette forme vous permet de régler quelques valeurs globales pour la façon que votre site apparaît.';
$GLOBALS["hGzipSetting"] = 'Régler ce drapeau pour rendre capable la compression de gzip si votre serveur le soutient.';
$GLOBALS["hSecureServer"] = 'Si votre serveur de toile soutient https, vérifier ceci pour login assuré.<br /><br /><B>Note:-</B> La function n\'a pas été implenmenté.';
$GLOBALS["hMultiSite"] = 'Régler ce drapeau à \'Oui\' si vous projetez de courir plus qu\'un site de ezContents sur ce serveur.';
$GLOBALS["hMultiSiteAuthors"] = 'Régler ce drapeau à \'Oui\' si vous souhaitez permettre de l\'accès aux utilisateurs à tous sites sur ce serveur dans le mode de Multi-Site.';
$GLOBALS["hMultiLanguage"] = 'Régler ce drapeau à \'Oui\' si vous projetez de gérer votre site dans plus qu\'une langue.';
$GLOBALS["hMultiTheme"] = 'Régler ce drapeau à \'Oui\' si vous voulez rendre capable des thèmes alternatifs pour votre site.';
$GLOBALS["hPageTimer"] = 'Activer le minuteur pour montrer les temps de génération de page.<br />Ceci est principalement un outil diagnostique pour les entrepreneurs de ezContents dans l\'essai de l\'efficacité du code.';
$GLOBALS["hDefaultLanguage"] = 'Régler la langue par default pour votre site.';
$GLOBALS["hDateFormat"] = 'Formatter mask pour l\'affichage de la date.<br /><br />Les codes que peut être utilisé sont basé sur la norme de Groupe Ouverte, et inclure:<br /><ul><li><b>%a</b> = le jour de semaine (3 lettres)<br />eg. Fri<li><b>%A</b> = le jour de semaine (long)<br />eg. Vendredi<li><b>%d</b> = le jour de mois (2 chiffre)<br />eg. 01<li><b>%e</b> = le jour de mois (1 or 2 chiffre)<br />eg. 1<br />(Il ne semble pas qu\'a définir comme la partie de la norme du Groupe Ouverte.)<li><b>%b</b> = mois (textual, 3 lettres)<br />eg. Mar<li><b>%B</b> = mois (textual, long)<br />eg. Mars<li><b>%m</b> = mois (2 chiffre)<br />eg. 03<li><b>%Y</b> = année (4 chiffre)<br />eg. 2002<li><b>%y</b> = année (2 chiffre)<br />eg. 02<br /><br /><li><b>%x</b> Le format standard pour locale<br />(Dépend du serveur.)</ul>';
$GLOBALS["hTimezone"] = 'La zone date/heure à afficher.<br />e.g. \"UST\", \"UST+1\"<br />Si votre <i>format date masquée</i> contient l\'heure, régler ceci pour donner de l\'une indication du temps par rapport à leur propre timezone aux lecteurs vers le monde; autrement le laisser vide.';
$GLOBALS["hFrameSetting"] = 'Afficher le site dans les frames ou utiliser une version de frameless.';
$GLOBALS["hVisitorStats"] = 'Faire vous souhaitez maintenir la statistique d\'accès pour votre site?';

?>
