<? //***** Français ******//
/**universal
 * Fichier contenant les variables pour la traduction française du site
 *
 * @author Thomas Pequet
 * @version 1.0  
 */

// Texte commun à toutes les pages
$titre1 	= "Sauvegarde différentielle des bases de données MySQL en PHP";
$titre2 	= "Sauvegarde";
$titre3 	= "Restauration";

// Nom des mois
$nomMois = array(
	  	"01" => "Janvier",
	  	"02" => "Février",
	  	"03" => "Mars",
	  	"04" => "Avril",
	  	"05" => "Mai",
	  	"06" => "Juin",
	  	"07" => "Juillet",																				
		"08" => "Août",
		"09" => "Septembre",
		"10" => "Octobre",
		"11" => "Novembre",
		"12" => "Décembre"
	  );
	  
$texteInfos 		= "Infos";
$texteErreurs 		= "Erreurs";
$texteTmeps 		= "Temps d'éxécution de la page";
$texteCharger 		= "Charger";
$texteSupprimer 	= "Supprimer";

// Le texte de "index"
if ($page=="1") {
	$texte1 	= "Installer ".$nomSite;
	$texte2 	= "Sauvegarder des données";
	$texte3 	= "Restaurer des données";
	$texte4 	= "Présentation";
	$texte5 	= "Vérification de la configuration";
	$texte6 	= "Fichier <B>##fic##</B> modifiable ?";
	$texte7 	= "Le fichier est en lecture seule";
	$texte8 	= "Le fichier n'existe pas";
	$texte9 	= "Module <B>XML</B> installé ?";
	$texte10 	= "Le module <B>XML</B> n'est pas installé dans PHP";
	$texte11 	= "Module <B>Zlib</B> installé ?";
	$texte12 	= "Le module <B>Zlib</B> n'est pas installé dans PHP";
	$texte13 	= "Installation de <B>".$nomSite."</B> dans les tables";
	$texte14 	= "Sélectionnez les tables qui seront modifiées pour être compatibles avec <B>".$nomSite."</B> -> un champ <B>`TIMESTAMP`</B> sera ajouté à la fin de chaque tables sélectionnées pour permettre les sauvegardes différentielles:";
	$texte15 	= "Tables du serveur `<B>##serveur##</B>`";
	$texte16 	= "Modifier les tables";
	$texte17 	= "Sauvegarder";
	$texte18 	= "Nom de la configuration de sauvegarde";
	$texte19 	= "Automatiser les sauvegardes";
	$texte20 	= "Il est possible d'automatiser les sauvegardes en éxécutant la page <B>save.php</B> et en lui passant en paramètres le nom de la configuration à sauvegarder.<BR><I>Exemple:</I> php -q -f save.php \"&nomconfig=default\"<BR>Cette commande peut être appelée par un <B>cron</B> ou par des services comme <B><A HREF=\"http://www.webcron.org\" TARGET=\"_blank\">webcron.org</A></B>.";
	$texte21 	= "Lancer une sauvegarde manuelle";
	$texte22 	= "Configuration de sauvegarde";
	$texte23 	= "Configuration de sauvegarde";
	$texte24 	= "Infos à sauvegarder";
	$texte25 	= "Structure et données";
	$texte26 	= "Données";
	$texte27 	= "Structure";
	$texte28 	= "Répertoire de stockage";
	$texte29 	= "Sélectionnez les tables à sauvegarder parmi les différentes bases";
	$texte30 	= "Format d'archivage";
	$texte31 	= "Enregistrer la configuration";
	$texte32 	= "Restaurer";
	$texte33 	= "Nom de la configuration de restauration";
	$texte34 	= "Restaurer les données sauvegardées entre le";
	$texte35 	= "au";
	$texte36 	= "Ajouter des énoncés \"drop table\"";
	$texte37 	= "Protéger les noms des tables et des champs par des \"`\"";
	$texte38 	= "Ajouter le nom de la base dans les requêtes";
	$texte39 	= "Afficher les requêtes SQL";
	$texte40 	= "Télécharger un fichier contenant les requêtes SQL";
	$texte41 	= "Configuration de restauration";
	$texte42 	= "Configuration de restauration";
	$texte43 	= "Infos à restaurer";
	$texte44 	= "Sélectionnez les tables à restaurer parmi les différentes bases";
	$texte45 	= "Base de données";
	$texte46 	= "<B>".$nomSite."</B> est un logiciel PHP qui permet d'effectuer des sauvegardes différentielles de vos bases de données <B>MySQL</B> en ne modifiant que très légèrement vos tables.";
	$texte47 	= "Grâce à <B>".$nomSite."</B>, vous pourrez ne sauvegarder que les données qui ont été modifiées depuis la dernière sauvegarde et ainsi gagner en place si vous sauvegardez régulièrement vos données.";
	$texte48 	= "Les données sont sauvegardées sous forme de fichiers XML compréssés dans des archives au format zip, tar, ou tar.gz.";
	$texte49 	= "Une fois vos données sauvegardées, vous pourrez restaurer vos données sous forme de requête SQL.";
	$texte50 	= "Requête";
	$message1 	= "La table <B>`##base##.##table##`</B> a été modifiée avec succès";
	$message2 	= "Le champ <B>`TIMESTAMP`</B> n'a pas pu être ajouté dans la table <B>`##base##.##table##`</B>. Ce champ existe peut-être déjà.";
	$message3 	= "Impossible de trouver la base <B>`##base##`</B>";
	$message4 	= "La configuration <B>`##config##`</B> a été sauvegardée avec succès";
	$message5 	= "Le fichier <B>`##fic##`</B> n'a pas été modifié car ses permissions ne le permettent pas.";
	$message6 	= "La configuration <B>`##config##`</B> a été supprimée avec succès";
}

// Le texte de "save"
if ($page=="2") {
	$message1 	= "Impossible de créer le répertoire: <B>##rep##</B>";
	$message2 	= "Impossible de trouver les paramètres de configuration dans le fichier: <B>##fic##</B>";
	$message3 	= "Impossible de se connecter à la base: <B>`##base##`</B>";
	$message4 	= "Impossible de trouver de champ <B>`TIMESTAMP`</B> dans la table: <B>`##table##`</B> (<A HREF=\"index.".$extension."?rub=install\" TARGET=\"_blank\">Voir procédure d'installation</A>)";
	$message5 	= "Sauvegarde de la structure de la table <B>`##table##`</B>";
	$message6 	= "Sauvegarde de <B>##nb##</B> enregistrement(s) de la table <B>`##table##`</B>";
	$message7 	= "Aucune donnée sauvegardée";
}

// Le texte de "restore"
if ($page=="3") {
	$texte1 	= "Structures des tables";
	$texte2 	= "Données des tables";
	$message1 	= "Impossible de trouver les paramètres de configuration dans le fichier: <B>##fic##</B>";
	$message2 	= "Impossible de trouver le répertoire: <B>##rep##</B>";
	$message3 	= "Aucune sauvegarde de la base <B>`##base##`</B> n'a été trouvée";
	$message4 	= "Aucune sauvegarde de la base <B>`##base##`</B> n'a été trouvée du mois de <B>##mois## ##annee##</B>.";
	$message5 	= "Aucune sauvegarde de la table <B>`##base##.##table##`</B> n'a été trouvée du mois de <B>##mois## ##annee##</B>.";
	$message6 	= "Restauration de la structure de la table <B>`##base##.##table##`</B>";
	$message7 	= "Restauration de <B>##nb##</B> enregistrements(s) de la table <B>`##base##.##table##`</B>";
	$message8 	= "Aucune structure restaurée";
	$message9 	= "Aucune donnée restaurée";
}
?>