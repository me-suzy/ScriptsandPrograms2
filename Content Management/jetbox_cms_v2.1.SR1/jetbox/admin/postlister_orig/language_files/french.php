<?php
$s1 = "Aide";
$s2 = "Composer";
$s3 = "Ajout/suppression d'abonn&eacute;s";
$s4 = "Editer une liste";
$s5 = "Cr&eacute;er/supprimer des listes";
$s6 = "La table principale de Postlister va maintenant &ecirc;tre cr&eacute;&eacute;e. Cette op&eacute;ration n'est &agrave; effectuer qu'une seule fois. Vous avez choisi de nommer la table principale <i>$mainTable</i>. Si vous voulez lui donner un autre nom, vous devez ouvrir le fichier <i>settings.php</i> et modifier la variable <i>\$mainTable</i>. Si vous ne souhaitez pas la modifier, cliquez sur le bouton ci-dessous pour cr&eacute;er la table.";
$s7 = "Cr&eacute;er la table";
$s8 = "Une erreur est survenue";
$s9 = "Retour";
$s10 = "Le nom de la table n'est pas valide. Ce nom ne doit contenir que des lettres et des chiffres, &agrave; l'exclusion des espaces et des caract&egrave;res sp&eacute;ciaux.";
$s11 = "La table principale <i>$mainTable</i> de Postlister a &eacute;t&eacute; cr&eacute;&eacute;e. Vous pouvez maintenant <a href=lists.php>cr&eacute;er des listes de diffusion</a>.";
$s12 = "Choisir une liste de diffusion :";
$s13 = "OK";
$s14 = "Il n'y a pas encore de liste de diffusion.";
$s15 = "Cr&eacute;er la liste de diffusion";
$s16 = "Créer une liste de diffusion";
$s17 = "Nom de la liste de diffusion :";
$s18 = "Choisissez un nom pour la nouvelle liste de diffusion. Le nom ne doit pas d&eacute;passer 20 caract&egrave;res. Il ne doit contenir aucun espace ni caract&egrave;res sp&eacute;ciaux. Seules les lettres non accentu&eacute;es et les chiffres sont admis.";
$s19 = "Supprimer une liste de diffusion";
$s20 = "Quelle liste voulez-vous supprimer ?";
$s21 = "Supprimer";
$s22 = "La liste de diffusion <i>$listeOpret</i> a été créée. Vous pouvez maintenant <a href=edit.php?liste=$listeOpret>éditer la liste</a>.";
$s23 = "Etes-vous sûr de vouloir supprimer la liste de diffusion nommée <i>$listeSlet</i> ? En poursuivant, vous perdrez toutes les adresses qu'elle contient.";
$s24 = "Annuler";
$s25 = "Supprimer la liste";
$s26 = "La liste de diffusion nommée <i>$listeSletBekraeft</i> a été supprimée.";
$s27 = "Adresse de l'expéditeur, ex. : <i>Votre nom &lt;votre.nom@$SERVER_NAME&gt;</i>:";
$s28 = "Signature à insérer à la fin des messages envoyés à la liste :";
$s29 = "Message de bienvenue -- envoyé aux nouveaux abonnés à la liste.";
$s30 = "Enregistrer les changements";
$s31 = "Le message de bienvenue <b>doit</b> contenir le mot <i>[SUBSCRIBE_URL]</i>.";

# The following variable will go into an email body. Therfore, you need to break all lines after 72 characters.
$s32 = "You have received this email because you or somebody else
has subscribed you to the mailing list $listeOpret at
http://$HTTP_HOST.
Before we can add your email address to our mailing list we need to
make sure that the email address exists and is working, and that you
actually want to subscribe to our mailing list. Therefore, we ask you
to confirm your subscription by visiting the following URL:

<[SUBSCRIBE_URL]>

Thank you.";

$s33 = "Changements enregistrés pour <i>$liste</i>.";
$s34 = "Ajouter des adresses de messagerie";
$s35 = "Supprimer des adresses de messagerie";
$s36 = "Ajouter";
$s37 = "Nouvelle adresse de messagerie à ajouter à la liste -- ex. : <i>jean.martin@exemple.com</i>:";
$s38 = "<i>$epostadresseTilfoej</i> n'est pas une adresse de messagerie valide.";
$s39 = "L'adresse de messagerie <i>$epostadresseTilfoej</i> a été ajoutée à la liste <i>$liste</i>.";
$s40 = "L'adresse de messagerie <i>$epostadresseTilfoej</i> existe déjà dans la liste.";
$s41 = "Afficher";
$s42 = "tous les abonnés";
$s43 = "admis";
$s44 = "non-admis";
$s45 = "commentçant par";
$s46 = "contenant";
$s47 = "Résultat vide.";
$s48 = "admis";
$s49 = "non-admis";
$s50 = "L'adresse de messagerie <i>$sletDenne</i> a été supprimée de la liste de diffusion <i>$liste</i>.";
$s51 = "Rédiger un message pour la liste de diffusion <i>$liste</i>";
$s52 = "De:";
$s53 = "Objet:";
$s54 = "Contenu:";
$s55 = "Retour à la ligne après 72 caractères";
$s56 = "Aperçu";
$s57 = "Imprimer";
$s58 = "Compter mots";
$s59 = "Fonctions";
$s60 = "Nombre de caractères:";
$s61 = "Nombre de mots:";
$s62 = "Accès à cette page impossible sans authentification.";
$s63 = "Vous pouvez utiliser les variables suivantes dans le corps du message :";
$s64 = "L'adresse de messagerie du destinataire.";
$s65 = "L'adresse URL de désinscription -- l'URL à partir de laquelle le destinataire peut se désabonner de la liste.";
$s66 = "A :";
$s67 = "Envoyer";
$s68 = "Retour -- je veux modifier le message";
$s69 = "Listes de diffusion";
$s70 = "S'abonner à notre/nos liste(s) de diffusion :";
$s71 = "Votre adresse de messagerie :";
$s72 = "Choisir une liste de diffusion :";
$s73 = "M'abonner";
$s74 = "Me désabonner";
$s75 = "<i>$email</i> n'est pas une adresse de messagerie valide.";
$s76 = "Vous n'avez pas indiqué si vous voulez vous abonner ou vous désabonner à une liste de diffusion. Le problème peut être dû à une erreur dans le formulaire que vous avez envoyé. Veuillez contacter l'administrateur du site web.";
$s77 = "Abonnement à la liste de diffusion $list";
$s78 = "Désabonnement de la liste de diffusion $list";
$s79 = "Merci de votre abonnement à la liste de diffusion <i>$list</i>. D'ici quelques minutes, vous recevrez un message e-mail contenant une URL à laquelle vous devez vous rendre pour confirmer votre demande d'abonnement.";
$s80 = "Pour pouvoir vous désabonner de la liste de diffusion <i>$list</i>, nous vous demandons de confirmer votre demande de désabonnement. D'ici quelques minutes, vous recevrez un message e-mail contenant une URL à laquelle vous devez vous rendre pour confirmer votre demande de désbonnement.";

# The following variable will go into an email body. Therfore, you need to break all lines after 72 characters.
$s81 = "You have received this email because you or somebody else
has unsubscribed you from the mailing list $listeOpret at
http://$HTTP_HOST.
Before we can remove your email address from our mailing list we need
to make sure that you, the owner of the email address, actually want to
be removed from the list. Therefore, we ask you to visit the following
URL in order to confirm your unsubscription request:

<[UNSUBSCRIBE_URL]>

Thank you.";

$s82 = "Le message de désabonnement <b>doit</b> contenir le mot <i>[UNSUBSCRIBE_URL]</i>.";
$s83 = "Le message de désabonnement -- message à envoyer aux abonnés souhaitant se désabonner de la liste.";
$s84 = "L'adresse <i>$email</i> existe déjà dans la liste.";
$s85 = "Terminé ! Le message a été envoyé à toutes les adresses inscrites sur la liste.";
$s86 = "Envoi en cours du message numéro";
$s87 = "par";
$s88 = "Ne fermez PAS cette fenêtre de navigateur ! Ne touchez rien pendant que Postlister envoie les messages restants.";
$s89 = "L'adresse de messagerie <i>$email</i> n'existe pas dans la liste. Par conséquent il n'est pas possible de la désinscrire.";
$s90 = "Vous n'avez pas indiqué d'adresse de messagerie.";
$s91 = "Vous n'avez pas précisé si vous demandez votre abonnement ou votre désabonnement.";
$s92 = "Vous n'avez pas spécifié l'ID d'adresse de messagerie.";
$s93 = "Vous n'avez pas spécifié de liste de diffusion.";
$s94 = "Vous n'avez pas donné l'ID correct pour l'adresse de messagerie <i>$epost</i>.";
$s95 = "Terminé ! Vous êtes maintenant inscrit à la liste de diffusion <i>$liste</i>.";
$s96 = "Vous êtes maintenant désabonné de la liste de diffusion <i>$liste</i>. Vous ne recevrez plus de messages de cette liste.";
$s97 = "sur";
$s98 = "Importer des adresses de messagerie";
$s99 = "Ouvrir et importer";
$s100 = "Fichier <i>$importfil</i> introuvable.";
$s101 = "Opération terminée ! Toutes les adresses de messagerie du fichier <i>$importfil</i> ont été importées dans la liste de diffusion <i>$liste</i>.";
$s102 = "Si vous avez un fichier contenant une liste d'adresses de messageries, vous pouvez importer ces adresses dans la liste de diffusion <i>$liste</i>. Cependant, le fichier doit se composer d'une adresse de messagerie par ligne, à l'exclusion de toute autre information. Autrement dit, le format du fichier doit ressembler à :<p><i>jean.dupont@exemple.fr<br>François Martin &lt;francois.martin@exemple.com&gt;<br>php@php.net</i>";
$s103 = "Fichier:";
$s104 = "Retour à la page principale";
$s105 = "Importer/exporter";
$s106 = "Exporter des adresses de messagerie";
$s107 = "Exporter";
$s108 = "Cette fonction vous permet d'exporter les adresses de messagerie de la liste <i>$liste</i>. C'est à dire que toutes les adresses de messagerie seront copiées dans un fichier, à raison d'une adresse par ligne. Ce fichier sera nommé <i>postlister-$liste.txt</i> et déposé dans le répertoire indiqué ci-dessous.  <b>Le répertoire dans lequel le fichier sera déposé doit impérativement être pourvu des droits d'écriture adéquats. Par conséquent, vous devez faire un chmod 777 sur ce répertoire à l'aide d'un client FTP ou SSH/telnet.</b>";
$s109 = "Le répertoire où vous souhaitez déposer le fichier :";
$s110 = "<i>$eksport</i> n'est pas un répertoire. Vous devez indiquer le répertoire dans lequel vous voulez déposer le fichier contenant les adresses de messagerie.";
$s111 = "Opération terminée ! Toutes les adresses de messagerie de la liste de diffustion <i>$liste</i> mailing list ont été copiées dans le fichier <i>$eksport/postliste-$liste.txt</i>.";
?>
