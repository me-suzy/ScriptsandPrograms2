<?php
/*
Copyright (C) 2002 CLAIRE Cédric claced@m6net.fr http://www.yoopla.net/portailphp/
Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou le modifier conformément aux dispositions de la Licence Publique Générale GNU, telle que publiée par la Free Software Foundation ; version 2 de la licence, ou encore (à votre choix) toute version ultérieure.
Ce programme est distribué dans l'espoir qu'il sera utile, mais SANS AUCUNE GARANTIE ; sans même la garantie implicite de COMMERCIALISATION ou D'ADAPTATION A UN OBJET PARTICULIER. Pour plus de détail, voir la Licence Publique Générale GNU .
Vous devez avoir reçu un exemplaire de la Licence Publique Générale GNU en même temps que ce programme ; si ce n'est pas le cas, écrivez à la Free Software Foundation Inc., 675 Mass Ave, Cambridge, MA 02139, Etats-Unis.
Portail PHP
La présente Licence Publique Générale n'autorise pas le concessionnaire à incorporer son programme dans des programmes propriétaires. Si votre programme est une bibliothèque de sous-programmes, vous pouvez considérer comme plus intéressant d'autoriser une édition de liens des applications propriétaires avec la bibliothèque. Si c'est ce que vous souhaitez, vous devrez utiliser non pas la présente licence, mais la Licence Publique Générale pour Bibliothèques GNU.
*/

// MODULE MEMBRES
$Mod_Membres_Accueil_OFF = "Se d&eacute;connecter";
$Mod_Membres_News_Add="Votre News a &eacute;t&eacute; correctement transf&eacute;r&eacute;";
$Mod_Membres_Liens_Add="Votre Liens a &eacute;t&eacute; correctement transf&eacute;r&eacute;";
$Mod_Membres_News_Edit="Votre News a &eacute;t&eacute; correctement modifi&eacute;e";
$Mod_Membres_Info="Editer le fichier <strong>/include/config.php</strong> pour modifier les valeurs";
$Mod_Membres_InfoUser="Editer la table <strong>$BD_Tab_user</strong> pour modifier les valeurs";
$Mod_Membres_Info_Theme="Thème";
$Mod_Membres_Info_Langue="Langue";
$Mod_Membres_Info_Titre="Titre";
$Mod_Membres_Info_Version="Version";
$Mod_Membres_Info_Couleur="Couleur Logo";
$Mod_Membres_Info_URL="URL";
$Mod_Membres_Info_UploadLimite="Limite Upload (octets)";
$Mod_Membres_Serveur_Info="Informations du serveur PHP";
$Mod_Membres_Nom="Nom";
$Mod_Membres_Pseudo="Pseudo";
$Mod_Membres_Mail="E-mail";
$Mod_Membres_Date="Actif depuis le ";
$Mod_Membres_Droit="Droit";
$Mod_Membres_Web="Site web";
$Mod_Membres_Password="Mot de passe";
$Mod_Membres_Err_Field="Vous avez oubli&eacute; de remplir un champ.";
$Mod_Membres_Err_Login="Pseudo et/ou Mot de Passe Incorrect";
$Mod_Membres_Rub_Accueil="Accueil Admin";
$Mod_Membres_Rub_Info="Info";
$Mod_Membres_Rub_News_Cat="Ajouter une Cat&eacute;gorie au module News";
$Mod_Membres_Rub_News_Add="Ajouter un article";
$Mod_Membres_Rub_News_Edit="Modifier un article";
$Mod_Membres_Rub_News_Del="Supprimer un article";
$Mod_Membres_Rub_News_Del2="Supprimer";
$Mod_Membres_Rub_News = "A pr&eacute;sent, pour supprimer ou &eacute;diter un article, un t&eacute;l&eacute;chargement, passez par l'interface du site. Lorsque vous &ecirc;tes connect&eacute; en administrateur, les liens <strong>Modifier</strong> et <strong>Supprimer</strong> apparaîtront." ;
$Mod_Membres_Rub_News_question = "Êtes-vous sûr de vouloir supprimer l'article : " ;
$Mod_Membres_Rub_News_Form_Categorie="Nom de la Cat&eacute;gorie";
$Mod_Membres_Rub_News_JS_Cat="Le champ Cat&eacute;gorie doit être rempli";
$Mod_Membres_Rub_News_Cat_OK="Votre cat&eacute;gorie a &eacute;t&eacute; correctement transf&eacute;r&eacute;";
$Mod_Membres_Rub_File_Cat="Ajouter une Cat&eacute;gorie au module File";
$Mod_Membres_Rub_File_DelDataBase = "Fichier supprim&eacute; de la base mais inexistant sur le serveur." ;
$Mod_Membres_Rub_File_Del="Supprimer un fichier";
$Mod_Membres_Rub_File_Form_Categorie="Nom de la Cat&eacute;gorie";
$Mod_Membres_Rub_File_JS_Cat="Le champ Cat&eacute;gorie doit être rempli";
$Mod_Membres_Rub_File_Cat_OK="Votre cat&eacute;gorie a &eacute;t&eacute; correctement transf&eacute;r&eacute;";
$Mod_Membres_Rub_Liens_Cat="Ajouter une Cat&eacute;gorie au module Liens";
$Mod_Membres_Rub_Liens_Cat_OK="Votre cat&eacute;gorie a &eacute;t&eacute; correctement transf&eacute;r&eacute;";
$Mod_Membres_Rub_Faq_Form_Categorie="Nom de la Cat&eacute;gorie";
$Mod_Membres_Rub_Faq_Cat="Ajouter une Cat&eacute;gorie au module FAQ";
$Mod_Membres_Rub_Faq_Add="Ajouter une FAQ";
$Mod_Membres_Rub_Faq_Edit="Modifier une FAQ";
$Mod_Membres_Rub_Faq_Del="Supprimer une FAQ";
$Mod_Membres_Rub_Faq_Add_OK="Votre FAQ a &eacute;t&eacute; correctement transf&eacute;r&eacute;";
$Mod_Membres_Rub_Faq_Cat_OK="Votre cat&eacute;gorie a &eacute;t&eacute; correctement transf&eacute;r&eacute;";
$Mod_Membres_Rub_Upload_Photo="Ajouter une photo";
$Mod_Membres_Rub_Del_Photo="Supprimer une photo";
$Mod_Membres_Rub_Upload_File = "Ajouter un fichier";
$Mod_Membres_Rub_Liens_Edit="Ajouter un lien";
$Mod_Membres_Nom="Nom";
$Mod_Membres_Regdate="Date Enregistrement";
$Mod_Membres_Droit="Droit";
$Mod_Membres_Web="Web";
$Mod_Membres_OK_Login="Vous êtes connect&eacute;s";
$Mod_Membres_OFF_Login="Vous êtes deconnect&eacute;s";
$Mod_Membres_Photos_Form_Photo="Photo(JPG)";
$Mod_Membres_Photos_Form_Vign="Vignette(JPG)";
$Mod_Membres_Photos_Form_Titre="Titre (50)";
$Mod_Membres_Photos_Form_Date="Date (aaaa-mm-jj)";
$Mod_Membres_Photos_Form_Texte="Texte";
// Ajouter à la version 1.2.1
$Mod_Membres_Menu_General = "Menu g&eacute;n&eacute;ral" ; 
$Mod_Membres_Menu_News = "Menu News" ;
$Mod_Membres_Menu_Fichiers = "Menu fichiers" ;
$Mod_Membres_Menu_Liens = "Menu liens" ; 
$Mod_Membres_Menu_Faq = "Menu FAQ" ;
$Mod_Membres_Menu_Photos = "Menu Photos" ;
// Ajouter à la version 1.2.2
$Mod_Membres_SC = "Code de sécurit&eacute;" ;
$Mod_Membres_SC1 = "Taper le code de s&eacute;curit&eacute; ci-dessous dans le champs ci-dessous" ;
$Mod_Membre_Mdp_dif = "Les mots de passe sont diff&eacute;rents, veuillez recommanc&eacute;." ;
$Mod_Membre_MyProfil_OK = "Mise &agrave; jour r&eacute;ussi." ;
?>