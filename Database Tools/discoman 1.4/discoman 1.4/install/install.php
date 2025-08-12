<?

//modifiez les variables ci-dessous pour qu'elles correspondent au script à installer.

$nom_script="Discoman 1.4"; //Le nom de votre script pour le titre de la page
$file="discoman.sql"; //le nom du fichier sql qui permet de créer vos tables
$fin="L'installation est terminée. Vous pouvez supprimer le répertoire install."; //Le message de fin d'installation
$lancer="../index.php"; //La page à lancer à la fin de l'installation. Indiquez le sous-répertoire si nécessaire.


//Ne rien modifier ci-dessous, sauf si vous savez ce que vous faites.

function install_script($nom_script,$file,$fin,$lancer)
//$nom_script = nom du script pour lequel créer un fichier d'install
//$file = nom du fichier sql
//$fin = texte de fin d'installation
//$lancer = url de la page de démarrage du script, à lancer à la fin de l'installation
{
global $install;
global $action;
global $serveur;
global $database;
global $username;
global $password;
$install = "oui";
$action=(isset($_GET['action'])) ? $_GET['action'] : "";
?>
<html>
<head>
<title>Installation de <? echo $nom_script ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="titre-page">Installation de <? echo $nom_script ?></p>
<? switch ($action)
	{
	case 'etape1':

    $balises=$_POST['balises'];//écriture du fichier meta.php
	$fn=fopen("../meta.php","w+");
	$texte2="<?\necho\"\n";
    $texte2.=$balises;
    $texte2.="\";\n?>";
	fwrite($fn,$texte2);
	fclose($fn);

    $langa=$_POST['lang'];//écriture du fichier config.inc.php
	$fo=fopen("../config.inc.php","w+");
	$texte3="<?\n\$lang=\"".$langa."\";\n?>";
	fwrite($fo,$texte3);
	fclose($fo);

	$serveur=$_POST['serveur'];
	$database=$_POST['database'];
	$username=$_POST['username'];
	$password=$_POST['password'];
	$fp=fopen("../link.inc.php","w+");
	$texte="<?\n";
	$texte.="\$serveur = \"".$serveur."\";\n\$database = \"".$database."\";\n\$username = \"".$username."\";\n\$password = \"".$password."\";\n";
	$texte.="\$link = mysql_connect(\$serveur,\$username,\$password) or die(mysql_error());\n";
	$texte.="if (!isset(\$install)){\$install=\"\";}";
	$texte.="if (\$install!=\"oui\")\n";
	$texte.="{mysql_select_db(\$database,\$link) or die(mysql_error());}\n";
	$texte.="?>";
	fwrite($fp,$texte);
	fclose($fp);
	echo "Le fichier link.inc.php a été généré.\n";
	include("../link.inc.php");
	$req=mysql_list_dbs($link);
	$liste_base=mysql_fetch_array($req);
	do
	  	{
		if ($liste_base[0]==$database)
			{
			$detecte="oui";
			}
		else {
			$detecte="non";
			}
		}
	while ($liste_base=mysql_fetch_array($req));
	if ($detecte=="oui")
		{
		echo "La base $database a été correctement détectée.";
		}
	else {
		mysql_query("CREATE DATABASE IF NOT EXISTS $database") or die (mysql_error());
		echo "La base $database a été correctement créée.";
		}
	echo "\n<p class=\"titre-lien\">Etape 2 : importer les tables :</p>";
	mysql_select_db($database,$link);
	$fp = @fopen($file,"r");
    if ($fp!=FALSE)
		{
		$import_sql = fread($fp,filesize($file));
		fclose($fp);
		$import_sql=explode(";",$import_sql);
		foreach($import_sql as $value)
			{
			if ($value!="")
				{
				@mysql_query($value);
				}
			}
		echo "\n Les tables suivantes ont été correctement créées :<ul>";
		$liste_table=mysql_list_tables($database);
		$affiche_liste_table=mysql_fetch_array($liste_table);
		$total_table=mysql_num_rows($liste_table);
		if ($liste_table=="")
		{echo"Problème : aucune table créée !";}
		else
			{
			do  {
				echo "<li>".$affiche_liste_table[0]."</li>";
				}
			while ($affiche_liste_table=mysql_fetch_array($liste_table));
			}
		echo "</ul>".$fin;
		echo "<p>";
		afficher_url($lancer,"Lancer l'application");
		echo "</p>";
		}
		else
			{ echo "Je n'ai pas trouvé le fichier ".$file;}
	break;
	default :
    echo"<div id='Layer6' style='position:absolute; width:780px; height:410px; z-index:1; left: 15; top:80; border: 1px none #000000; overflow: auto'>";
	echo"<p class=\"titre-lien\">Etape 1 : Configurer le fichier link.inc.php avec les données de
  votre serveur mysql :</p>";
  echo "Les valeurs par défaut ci-dessous conviennent si vous faites une installation en local avec EasyPHP.<BR>";
  ?>

<form name="form2" method="post" action="install.php?action=etape1">
  <table width="780" border="0" cellpadding="0" cellspacing="0" class="text">
    <tr>
      <td width="200">Nom du serveur :</td>
      <td width="580"><input name="serveur" type="text" id="serveur" value="localhost"></td>
    </tr>
    <tr>
      <td>Nom de la base de donn&eacute;e :</td>
      <td><input name="database" type="text" id="database"></td>
    </tr>
    <tr>
      <td>Nom d'utilisateur :</td>
      <td><input name="username" type="text" id="username" value="root"></td>
    </tr>
    <tr>
      <td>Mot de passe :</td>
      <td><input name="password" type="text" id="password">
        (en g&eacute;n&eacute;ral vide si vous &ecirc;tes en local)</td>
    </tr>
    <tr>
      <td>Langue :</td>
      <td><select size="1" name="lang">
  		<option value="fr">Français</option>
    	<option value="en">Anglais</option>
		</select></td>
    </tr>
    <tr>
      <td>Balises meta :</td>
      <td><textarea name="balises" rows=8 cols=60 wrap="off">
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
<meta name='description' content='A COMPLETER'>
<meta name='Keywords' content='A COMPLETER'>
<meta http-equiv='expires' content='0'>
<META NAME='robots' CONTENT='index, follow'>
<meta name='revisit-after' content='7 days'></textarea>
	  </td>
    </tr>
  </table>
  <p>
    <div align="center"><input type="submit" name="Submit2" value="Envoyer" id="style1"></div>
  </p>
</form>
</div>
<? } ?>
</body>
</html>
<? }

function afficher_url($lien,$texte)
{
echo "<a href=\"".$lien."\">".$texte."</a>";
}
//affiche un lien hypertexte
//$lien = lien de l'url
//$texte = texte à afficher pour le lien



install_script($nom_script,$file,$fin,$lancer);//execution de la fonction d'installation
?>