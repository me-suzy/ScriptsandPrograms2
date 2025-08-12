<?php //FICHIER D'AIDE

require("presentation.inc.php");

HAUTPAGEWEB('Discoman - Help');
LAYERS2();
?>

	<table class="Mtable" border="0" width="100%" cellpadding="0" cellspacing="0">
    	<th>Aide</th>
    </table>
    <table class="Stable" border="1" style="border-color:#000000;" width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<td>
            <a name="0"></a><b>Sommaire :</b><br><br>

            <a href="#1">Première utilisation</a><br>
            <a href="#2">Gestion des artistes</a><br>
            <a href="#3">Gestion des formats</a><br>
            <a href="#4">Gestion des pays</a><br>
            <a href="#5">Gestion des enregistrements</a><br>
            <a href="#6">Gestion des utilisateurs</a><br>
            <a href="#7">Gestion des infos</a><br><br>

            <a name="1"></a><b>Première utilisation :</b><br><br>

			Créer votre profil d'administrateur : Pour celà, cliquez sur Connexion => Admin. Pour la 1ère connexion, le programme vous demande de saisir un nom et un mot de passe. Conservez les précieusement, ils seront nécessaires pour chaque ajout, modification, suppression d'élèments de la base de données. Une fois le profil admin créé, vous devez vous relogger, ce qui vous permet d'accéder au panneau de contrôle. Pour permettre à la base de données d'accepter des enregistrements, il faut obligatoirement paramétrer :<br>
            - les artistes<br>
            - les différents formats<br>
            - les différents pays<br>
            et éventuellement les différents utilisateurs à qui vous donnerez des droits d'accès.<br><br>

        	<div align="right"><a href="#0">Retour au sommaire</a></div><br><br>

            <a name="2"></a><b>Gestion des artistes :</b><br><br>

            Connectez-vous en tant qu'admin ou utilisateur niveau 2.<br>
            Création : Cliquer sur "Ajouter". Remplir le champ nom et valider. Le nouvel artiste est ajouté à la liste.<br>
            Modification : Cliquer sur "Modifier". Choisir l'artiste à modifier. Modifiez le nom et validez.<br>
            Suppression : Cliquer sur "Supprimer". Choisir l'artiste à supprimer et cliquez sur "Supprimer". Si des enregistrements liés à l'artiste existent, vous obtiendrez un message d'avertissement. Il convient alors d'effacer tous ces enregistrements pour pouvoir effacer l'artiste. Voir la section <a href="#5">Gestion des enregistrements</a>.<br><br>

        	<div align="right"><a href="#0">Retour au sommaire</a></div><br><br>

            <a name="3"></a><b>Gestion des formats :</b><br><br>

            Connectez-vous en tant qu'admin ou utilisateur niveau 2.<br>
            Création : Cliquer sur "Ajouter". Remplir le champ format (abréviation, par ex : LP ou 12"), ainsi que la description du champ (ex : 33 tours, maxi 45 tours) et valider. Le nouveau format est ajouté à la liste existante.<br>
            Modification : Cliquer sur "Modifier". Choisir le format à modifier. Modifiez le nom ou/et la désignation et validez.<br>
            Suppression : Cliquer sur "Supprimer". Choisir le format à supprimer et cliquer sur "Supprimer". Si des enregistrements liés au format existent, vous obtiendrez un message d'avertissement. Il convient alors d'effacer ou modifier tous ces enregistrements pour pouvoir effacer le format. Voir la section <a href="#5">Gestion des enregistrements</a>.<br><br>

        	<div align="right"><a href="#0">Retour au sommaire</a></div><br><br>

            <a name="4"></a><b>Gestion des pays :</b><br><br>

            Connectez-vous en tant qu'admin ou utilisateur niveau 2.<br>
            Création : Cliquer sur "Ajouter". Remplir le nom du pays et son abréviation (par ex : F pour France), Puis valider. Le nouveau pays est ajouté à la liste.<br>
            Modification : Cliquer sur "Modifier". Choisir le pays à modifier. Modifiez le nom ou/et la désignation et validez.<br>
            Suppression : Cliquer sur "Supprimer". Choisir le pays à supprimer et cliquer sur "Supprimer". Si des enregistrements liés au pays existent, vous obtiendrez un message d'avertissement. Il convient alors d'effacer ou modifier tous ces enregistrements pour pouvoir effacer le pays. Voir la section <a href="#5">Gestion des enregistrements</a>.<br><br>

        	<div align="right"><a href="#0">Retour au sommaire</a></div><br><br>

            <a name="5"></a><b>Gestion des enregistrements :</b><br><br>

            Connectez-vous en tant qu'admin ou utilisateur.<br>
            Création : Cliquer sur "Ajouter". Remplir :<br>
            - le nom de l'artiste en le sélectionnant dans le menu déroulant<br>
            - télécharger de une à 3 images en cliquant sur le bouton parcourir. Les formats acceptés sont jpg et gif, inférieurs à 300 ko. Recommandation : Une image de 400x400 pixels avec une résolution assez faible pour un poids total d'environ 100 ko semble un bon compromis.<br>
            - Sélectionner le format<br>
            - Saisir l'année de sortie<br>
            - Sélectionner le pays d'origine<br>
            - Saisir la référence<br>
            - Ajouter un éventuel commentaire<br>
            - Saisir le ou les titres contenus sur le disque.<br>
            Puis valider.<br>
            Modification : Cliquer sur "Modifier". Choisir dans la liste déroulante, l'artiste pour lequel vous voulez modifier des enregistrements. Sélectionnez ensuite l'enregistrement souhaité dans la liste. Modifier les élèments voulus et valider. Si vous ne souhaitez modifier qu'une ou plusieurs images (ajout, modification, suppression), il suffit de cliquer sur le bouton correspondant à l'image. TRES IMPORTANT : Si plusieurs images sont concernées, il convient de raffraîchir la page entre chaque modification. Si seules les images sont modifiées, il est inutile de valider car la modification est directement prise en compte dans la base de données.<br>
            Suppression : Cliquer sur "Supprimer". Choisir dans la liste déroulante, l'artiste pour lequel vous voulez supprimer un enregistrement. Sélectionnez ensuite l'enregistrement souhaité dans la liste et valider.<br><br>

        	<div align="right"><a href="#0">Retour au sommaire</a></div><br><br>

            <a name="6"></a><b>Gestion des utilisateurs :</b><br><br>

            Connectez-vous en tant qu'admin.<br>
            Création : Cliquer sur "Ajouter". Remplir le nom et sélectionner son niveau :<br>
            - 1 : l'utilisateur peut ajouter, modifier et supprimer des enregistrements,<br>
            - 2 : l'utilisateur peut ajouter, modifier et supprimer des enregistrements, artistes, formats et pays.<br> Puis valider. Le nouvel utilisateur est ajouté à la liste.<br>
            Modification : Cliquer sur "Modifier". Choisir l'utilisateur à modifier. Modifiez son niveau et validez.<br>
            Suppression : Cliquer sur "Supprimer". Choisir l'utilisateur à supprimer et cliquer sur "Supprimer".<br><br>

        	<div align="right"><a href="#0">Retour au sommaire</a></div><br><br>

            <a name="7"></a><b>Gestion des infos :</b><br><br>

            Se connecter en tant qu'admin.<br>
            Création : Cliquer sur "Ajouter". Saisir le sujet et le texte, éventuellement ajouter une image (max 300 ko). Vous pouvez ajouter des balises html. Puis valider. La nouvelle info est ajoutée à la liste.<br>
            Modification : Cliquer sur "Modifier". Choisir l'info à modifier. Modifier le sujet et/ou le texte. Valider.<br>
            Suppression : Cliquer sur "Supprimer". Choisir l'info à supprimer et cliquer sur "Supprimer".<br>
            Recommandations : les images font par défaut 200x200 pixels en affichage sur la page d'accueil. Donc ne pas sélectionner d'images trop lourdes (100 ko devrait être le maximum), surtout s'il y a une image par info, afin que la page ne mette pas trop de temps à s'afficher. Ensuite, vu la taille d'affichage, une résolution importante ne sert à rien. Afin de limiter le poids de la page, seules les 10 dernières infos s'affichent sur la page infos. Les autres restent néanmoins accessibles depuis le panel admin.<br><br>

        	<div align="right"><a href="#0">Retour au sommaire</a></div><br><br>
            </td>
		</tr>
    </table>
<?
BASPAGEWEB();
?>