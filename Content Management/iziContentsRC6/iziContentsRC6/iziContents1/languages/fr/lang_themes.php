<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'maintenir les thèmes';

//  List Headings
$GLOBALS["tThemeCode"] = 'Code Thème';
$GLOBALS["tThemeName"] = 'Nom Thème';
$GLOBALS["tThemeDescription"] = 'Thème Description';
$GLOBALS["tThemeEnabled"] = 'Activer';

//  List Functions
$GLOBALS["tAddNewTheme"] = 'Ajouter nouveau thème';
$GLOBALS["tViewTheme"] = 'Afficher détails du thème';
$GLOBALS["tEditTheme"] = 'Editer détails du thème';
$GLOBALS["tDeleteTheme"] = 'Effacer le thème';
$GLOBALS["tReleaseTheme"] = 'Activer/Désactiver ce thème';
$GLOBALS["tSelectTheme"] = 'Selecter ce thème pour la maintenance';

//  Form Block Titles
$GLOBALS["thThemeGeneral"] = 'Détails du Thème';

//  Form Detail Comments and Help Texts
$GLOBALS["tDetails"] = 'Cette forme vous permettez de définit des thèmes pour votre site de ezContents.';
$GLOBALS["hThemeCode"] = 'Ceci est le code unique qui identifie ce thème.<br />Il ne doit pas contenir d\'espaces ou des caractères spéciaux.<br /><br />Si votre serveur Apache est correctement configuré pour utiliser le. le fichier de htaccess dans votre annuaire de ezContents, alors les téléspectateurs directement peuvent accéder à ce thème avec un url dans le format: http://www.yourserver.com/ezc_directory/<themecode>.';
$GLOBALS["hThemeName"] = 'Un nom utilisé dans la liste d\'affichage pour ce thème (utilisé par [themelist] tag).';
$GLOBALS["hThemeDescription"] = 'Une description utilisée dans la liste d\'affichage pour ce thème (utilisé par [themelist] tag).';
$GLOBALS["hThemeEnabled"] = 'Si ce thème est activer ou pas.';

//  Error Messages
$GLOBALS["eNoCode"] = 'Vous devez donner un code d\'identificateur à ce thème.';
$GLOBALS["eInvalidCode"] = 'Le code du thème contient des caractères nuls';
$GLOBALS["eMasterCode"] = 'Ce code d\'identificateur de thème est déjà dans l\'usage pour le thème de maître.';
$GLOBALS["eCodeInUse"] = 'Ce code d\'identificateur est déjà dans l\'usage pour un autre thème ou un autre site.';
$GLOBALS["eNoName"] = 'Le nom de thème ne peut pas être gauche vide.';
$GLOBALS["eNoDescription"] = 'La description de thème ne peut pas être gauche vide.';

?>
