<?php //A PROPOS

require("presentation.inc.php");

HAUTPAGEWEB('Discoman - about');
LAYERS2();
?>

	<table class="Mtable" border="0" width="100%" cellpadding="0" cellspacing="0">
    	<th>About</th>
    </table>
    <table class="Stable" border="1" style="border-color:#000000;" width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<td><br>
            	<div align="center"><b>DiscoMan</b></div><br><br>

                Version 1.4 du 22/05/05<br><br>

            	<a name="0"></a><b>Sommaire :</b><br><br>

            	<a href="#1">Contact</a><br>
            	<a href="#2">Licence</a><br>
            	<a href="#3">A faire</a><br>
            	<a href="#4">Historique</a><br>
            	<a href="#5">Autres scripts du même auteur</a><br>
                <a href="#6">Remerciements</a><br><br>

                <a name="1"></a><b>Contact :</b><br><br>

                Pour tout bug rencontré, toute demande d'amélioration (dans la limite de mes capacités), vous pouvez me contacter à l'adresse mail suivante :

                <a href="mailto:admin@the-mirror-of-dreams.com">admin@the-mirror-of-dreams.com </a><br><br>
				Un forum est également ouvert à l'adresse : <a href="http://www.the-mirror-of-dreams.com" target="blank">http://www.the-mirror-of-dreams.com</a> rubrique "forums" dans le menu ou "script" sur la home page. La dernière version téléchargeable se trouve dans la section "Spécial".<br><br>

                <div align="right"><a href="#0">Retour au sommaire</a></div><br><br>

                <a name="2"></a><b>Licence :</b><br><br>

                DiscoMan &copy; 2004 - 2005 E.R.<br><br>

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.<br><br>

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.<br><br>

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.<br><br>

				<div align="right"><a href="#0">Retour au sommaire</a></div><br><br>


                <a name="3"></a><b>A faire :</b><br><br>

	                - ajouter la possibilité d'imprimer les résultats;<br>
	                - ajouter un choix de plusieurs présentations possibles;<br>
	                - ajouter une réelle partie admin avec mot de passe;<br>
                    - permettre à l'admin une validation du disque ajouté par un tiers;<br>
                    - pour l'update records, permettre un mode de recherche simple : recherche par artiste + autres possibilités.<br>
	                - lorsque le script sera plus complet, compléter le fichier d'aide et l'agrémenter de copies écrans. Scinder l'aide admin de l'aide utilisateur.<br>
                    - finir de traduire l'interface en anglais;<br>
                    - pouvoir trier les résultats en cliquant sur les colonnes<br>
                    - affiner les autorisations de chaque utilisateur depuis le menu admin<br>
                    - revoir la présentation par frame;<br>
                    - ajouter un module statistiques<br>
                    - ajouter la possibilité de faire des updates / deletes en nombre<br>
                    - scinder le script selon les langages utilisés : php / html / requêtes mysql<br>
                    - étudier en finalité la possibilité de gérer à la fois une discographie et une collection. Pouvoir, par comparaison, établir une liste des disques manquants.<br>
                    - prévoir un champ pour enregistrer le nom de la personne qui saisit le disque;<br>
                    - regrouper les différents fichiers php, notamment les add, update, delete de la partie admin.<br>
                    - insérer la licence sur chaque page.<br>
                    - prévoir des messages d'erreurs, lors de l'ajout d'un disque (champ non renseigné par ex).<br><br>

                <div align="right"><a href="#0">Retour au sommaire</a></div><br><br>

                <a name="4"></a><b>Historique :</b><br><br>

				- version 1.4 du 22/05/2005 : Début de traduction français/anglais avec choix de la langue à l'installation. Script de balises meta créé à l'installation. Possibilité d'uploader 3 images par disque. Visualisation de ces images en miniature et en taille agrandie lorsque l'on clique dessus. Possibilité d'uploader une image par info. Aussi bien pour l'info, que pour l'enregistrement d'un disque, suppression ou modification des images possibles depuis le menu Admin. Affichage des dates en français. Transformation des boutons en menus déroulants. Début de la rédaction de la page Aide. Acceptation des noms contenant des apostrophes et guillemets. Après annulation d'un delete, retour à la page en cours. Correction d'un bug sur la fonction recherche. L'index ne comprend plus que 2 pages : top et middle. Correction d'un bug d'affichage des noms d'artistes sans résultat et de l'affichage des références à modifier. Correction de bugs de mise à jour sur title_update. Correction de l'affichage du tableau présentant les formats au-delà de 16.<br><br>
                - version 1.3 du 07/09/2004 : Implémentation d'une partie news visible depuis la home page et gérée depuis le panel admin. Changement du nom de toutes les tables afin de les préfixer à l'identique. Le nom du dernier artiste selectionné est conservé lors de la saisie de disques. Les formats sont dorènavant affichés dans un tableau. L'affichage des noms de pays composés est corrigé. Le lien sur mon site est corrigé dans la partie about. Quelques modifs d'affichage. Suppression de la page "admin_menu_acces.php".<br><br>
                - version 1.2 du 29/08/2004 : Changement du script d'installation.<br><br>
                - version 1.1 du 06/05/2004 : Ajout de nombreuses fonctionnalités, notamment pour l'admin : add, update et delete dans les différentes tables + protection par mot de passe. Création d'une table utilisateurs. Légére amélioration des layers. Accès à tous les layers depuis le script présentation.php<br><br>
                - version 1.0 du 14/03/2004 : programme initial.<br><br>

                <div align="right"><a href="#0">Retour au sommaire</a></div><br><br>

                <a name="5"></a><b>Autres scripts du même auteur :</b><br><br>

                - Lyly : script de gestion d'une base de données de paroles de chansons<br><br>

                <div align="right"><a href="#0">Retour au sommaire</a></div><br><br>

                <a name="6"></a><b>Remerciements :</b><br><br>

                - Pour le script d'install : <a href="http://www.cdprof.com/index.php">http://www.cdprof.com/index.php </a><br><br>

                <div align="right"><a href="#0">Retour au sommaire</a></div><br><br>
            </td>
		</tr>
    </table>
<?
BASPAGEWEB();
?>