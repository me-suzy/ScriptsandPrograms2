<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'maintien sites';

//  List Headings
$GLOBALS["tSiteCode"] = 'Code du Site';
$GLOBALS["tSiteName"] = 'Nom du Site';
$GLOBALS["tSiteDescription"] = 'Description du Site';
$GLOBALS["tSiteEnabled"] = 'Activer';

//  List Functions
$GLOBALS["tAddNewSite"] = 'Ajouter un nouveau site';
$GLOBALS["tViewSite"] = 'Affichage detail du site';
$GLOBALS["tEditSite"] = 'Editer detail du site';
$GLOBALS["tDeleteSite"] = 'Effacer site';
$GLOBALS["tReleaseSite"] = 'Activer/Desactiver ce site';
$GLOBALS["tSelectSite"] = 'Selecter ce site pour maintenance';

//  Form Block Titles
$GLOBALS["thSiteGeneral"] = 'Details du Site';

//  Form Detail Comments and Help Texts
$GLOBALS["tDetails"] = 'Cette forme vous permettez de définit subsites dans votre site de ezContents.';
$GLOBALS["hSiteCode"] = 'Ceci est le code unique qui ceci identifie le sous-site.<br />Il ne doit pas contenir d\'espaces ou des caractères spéciaux.<br /><br />Si votre serveur Apache est correctement configuré pour utiliser le. le fichier de htaccess dans votre annuaire de ezContents, alors les téléspectateurs directement peuvent accéder à ce site avec un url dans le format: http://www.yourserver.com/ezc_directory/<sitecode>.';
$GLOBALS["hSiteName"] = 'Un nom utilisé dans la liste montre pour ce site (utilisé par le [sitelist] tag).';
$GLOBALS["hSiteDescription"] = 'Une description utilisée dans la liste montre pour ce site (utilisé par le [sitelist] tag).';
$GLOBALS["hSiteEnabled"] = 'Si ce site est activé ou pas.';

//  Error Messages
$GLOBALS["eNoCode"] = 'Vous devez donner un code d\'identificateur à ce site.';
$GLOBALS["eInvalidCode"] = 'Le code de site contient des caractères nuls';
$GLOBALS["eMasterCode"] = 'Ce code d\'identificateur de site est déjà dans l\'usage pour le site de maître.';
$GLOBALS["eCodeInUse"] = 'Ce code d\'identificateur est déjà dans l\'usage pour un autre site ou un autre thème.';
$GLOBALS["eNoName"] = 'Le nom de site ne peut pas être gauche vide.';
$GLOBALS["eNoDescription"] = 'La description de site ne peut pas être gauche vide.';

?>
