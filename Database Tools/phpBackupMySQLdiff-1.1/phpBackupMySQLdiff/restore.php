<?php
/**universal
 * Restauration des données contenu dans les fichies XML
 * 
 * @author Thomas Pequet
 * @version 1.0 
 */

// Repertoire dans lequel on est par rapport à la racine du site
$rep_par_rapport_racine = "";

// Identifant de la page (0, ou 1, ...)
$page = "3";

// Temps limite d'execution du script en secondes
@set_time_limit(300);

// Fichiers à inclure dans la page
@session_start();
include_once($rep_par_rapport_racine."inc/fonctions.inc.php");
include($rep_par_rapport_racine."inc/config.inc.php");
//include($rep_par_rapport_racine.$ficConnBase);
include($rep_par_rapport_racine."lang/".$langue.".inc.php");
include($rep_par_rapport_racine."inc/img.inc.php");
include_once($rep_par_rapport_racine."lib/archive.php");
include_once($rep_par_rapport_racine."lib/fichier.php");

// Constantes de la page (en majuscules)

// Variables de la page
$infos = "";
$erreur = "";
$data = "";
$structure = "";
if (!isset($jourDeb))
	$jourDeb = 1;
if (!isset($moisDeb))
	$moisDeb = date("n");
if (!isset($anneeDeb))
	$anneeDeb = date("Y");
if (!isset($jourFin))
	$jourFin = date("t");
if (!isset($moisFin))
	$moisFin = date("n");
if (!isset($anneeFin))
	$anneeFin = date("Y");	
// Tableau conprenant les dates (Ymd) à restaurer
$tabDateYmd = array();
for ($i=mktime(12,0,0,$moisFin,$jourFin,$anneeFin);$i>=mktime(12,0,0,$moisDeb,$jourDeb,$anneeDeb);$i=$i-24*60*60) {
	if (!isset($tabDateYmd[date("Ym", $i)]))
		$tabDateYmd[date("Ym", $i)] = array();
	$tabDateYmd[date("Ym", $i)][sizeof($tabDateYmd[date("Ym", $i)])] = date("d", $i);
}
// Définition des tableau qui contiendra le code Xml et Dtd des fichiers à restaurer
$structureXml = array();
//$structureDtd = array();
$dataXml = array();
$dataDtd = array();
$tempsPageDeb = time();
// Chargement des paramètres de restauration contenu dans le fichier XML
$options = chargerConfig($rep_par_rapport_racine.$ficConfigRestauration);

// Fonctions de la page

// Actions de la page
if (isset($nomconfig) && isset($options[$nomconfig])) {
	if (is_dir($options[$nomconfig]["dossier"])) {
		// Définition des bases à restaurer
		if (is_array($options[$nomconfig]["tables"]))
			$bases_keys = array_keys($options[$nomconfig]["tables"]);	
		else
			$bases_keys = array();	
		// Définition des dates (année mois) à parcourir
		if (is_array($tabDateYmd))
			$Ym_keys = array_keys($tabDateYmd);	
		else
			$Ym_keys = array();	
		// Parcours des bases
		for ($i=0;$i<sizeof($bases_keys);$i++) {
			// Vérification que le dossier de la base existe
			if (is_dir($options[$nomconfig]["dossier"]."/".$bases_keys[$i])) {		
				// Selection des tables de la base de données
				$tables_keys = array_keys($options[$nomconfig]["tables"][$bases_keys[$i]]);		
				// Parcours de la table des dates à analyser
				for ($j=0;$j<sizeof($Ym_keys);$j++) {
					// Vérification que le dossier de la Ym existe
					if (is_dir($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$Ym_keys[$j])) {		
						// Parcours de la liste des tables de la base
						for ($k=0;$k<sizeof($tables_keys);$k++) {
							// Vérification que le dossier de la table existe
							if (is_dir($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$Ym_keys[$j]."/".$tables_keys[$k])) {
								// Recupération de la liste des fichiers dossier
								$listeFichiers = array();
								$handle = @opendir($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$Ym_keys[$j]."/".$tables_keys[$k]);
								while ($fichierTmp = @readdir($handle)){		
									if(is_file($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$Ym_keys[$j]."/".$tables_keys[$k]."/".$fichierTmp) && ereg("(zip|tar.gz|tar|tgz)$", $fichierTmp)) {
										$listeFichiers[sizeof($listeFichiers)] = $fichierTmp;
									}
								}
								@closedir($handle);
								// Effacer le cache
								clearstatcache();
								// Tri du tableau des fichiers en ordre inverse par rapport au nom des fichiers
								arsort($listeFichiers);
								//print_r($listeFichiers);
								// Parcours de la liste des fichiers
								$fichiers_keys = array_keys($listeFichiers);
								for ($l=0;$l<sizeof($fichiers_keys);$l++) {
									// Vérification que ce fichier est dans la période à restaurer
									if (in_array(substr($listeFichiers[$fichiers_keys[$l]],0,2),$tabDateYmd[$Ym_keys[$j]])) {
										// Fichier contenant la structure de la table
										if (($options[$nomconfig]["infos"]=="structure" || $options[$nomconfig]["infos"]=="data") && ereg("structure",$listeFichiers[$fichiers_keys[$l]])) {
											// Vérification que cette structure n'a pas déjà été restaurée
											if (!isset($structureXml[$bases_keys[$i]][$tables_keys[$k]])) {
												// Ouverture du fichier
												$archiveTmp = new archive($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$Ym_keys[$j]."/".$tables_keys[$k], $listeFichiers[$fichiers_keys[$l]]);
												// Extraire des fichiers
												$archiveTmp->extraire_fichier($rep_tmp, "", $Ym_keys[$j].ereg_replace("(zip|tar.gz|tar|tgz)$", "xml", $listeFichiers[$fichiers_keys[$l]]));
												//$archiveTmp->extraire_fichier($rep_tmp, "", $Ym_keys[$j].ereg_replace("(zip|tar.gz|tar|tgz)$", "dtd", $listeFichiers[$fichiers_keys[$l]]));
												$archiveTmp->extraire_fichier($rep_tmp, "", $Ym_keys[$j].ereg_replace("structure.(zip|tar.gz|tar|tgz)$", "data.dtd", $listeFichiers[$fichiers_keys[$l]]));
												// Création d'un objet Fichier
												$fichierTmp = new fichier($rep_tmp, $Ym_keys[$j].ereg_replace("(zip|tar.gz|tar|tgz)$", "xml", $listeFichiers[$fichiers_keys[$l]]));
												// Récupération du contenu
												$structureXml[$bases_keys[$i]][$tables_keys[$k]] = $fichierTmp->lire("r");
												// Supprimer le fichier
												$fichierTmp->supprimer();
												// Création d'un objet Fichier
												//$fichierTmp = new fichier($rep_tmp, $Ym_keys[$j].ereg_replace("(zip|tar.gz|tar|tgz)$", "dtd", $listeFichiers[$fichiers_keys[$l]]));
												// Récupération du contenu
												//$structureDtd[$bases_keys[$i]][$tables_keys[$k]] = $fichierTmp->lire("r");
												// Supprimer le fichier
												//$fichierTmp->supprimer();
												// Création d'un objet Fichier
												$fichierTmp = new fichier($rep_tmp, $Ym_keys[$j].ereg_replace("structure.(zip|tar.gz|tar|tgz)$", "data.dtd", $listeFichiers[$fichiers_keys[$l]]));
												// Récupération du contenu
												$dataDtd[$bases_keys[$i]][$tables_keys[$k]] = $fichierTmp->lire("r");
												// Supprimer le fichier
												$fichierTmp->supprimer();
												
												unset($archiveTmp);
												unset($fichierTmp);
											}
											
										// Fichier contenant les données de la table
										} else if (($options[$nomconfig]["infos"]=="data" || $options[$nomconfig]["infos"]=="dataonly") && ereg("data",$listeFichiers[$fichiers_keys[$l]])) {
											// Ouverture du fichier
											$archiveTmp = new archive($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$Ym_keys[$j]."/".$tables_keys[$k], $listeFichiers[$fichiers_keys[$l]]);
											// Extraire le fichier XML
											$archiveTmp->extraire_fichier($rep_tmp, "", $Ym_keys[$j].ereg_replace("(zip|tar.gz|tar|tgz)$", "xml", $listeFichiers[$fichiers_keys[$l]]));
											// Création d'un objet Fichier
											$fichierTmp = new fichier($rep_tmp, $Ym_keys[$j].ereg_replace("(zip|tar.gz|tar|tgz)$", "xml", $listeFichiers[$fichiers_keys[$l]]));
											// Récupération du contenu
											$dataXml[$bases_keys[$i]][$tables_keys[$k]][sizeof($dataXml[$bases_keys[$i]][$tables_keys[$k]])] = $fichierTmp->lire("r");
											// Supprimer le fichier
											$fichierTmp->supprimer();
											
											unset($archiveTmp);
											unset($fichierTmp);
										}											
									}									
								}
								unset($listeFichiers);
								unset($fichiers_keys);
							} else {
								$erreur .= str_replace(array("##base##","##table##","##mois##","##annee##"), array($bases_keys[$i],$tables_keys[$k],$nomMois[substr($Ym_keys[$j],4,2)],substr($Ym_keys[$j],0,4)), $message5)."<BR>";
							}
							// Effacer le cache
							clearstatcache();
						}
					} else {
						$erreur .= str_replace(array("##base##","##mois##","##annee##"), array($bases_keys[$i],$nomMois[substr($Ym_keys[$j],4,2)],substr($Ym_keys[$j],0,4)), $message4)."<BR>";
					}
					// Effacer le cache
					clearstatcache();				
				}
				unset($tables_keys);
			} else {
				$infos .= str_replace("##base##", $bases_keys[$i], $message3)."<BR>";
			}
			// Effacer le cache
			clearstatcache();
		}
		unset($bases_keys);
		unset($Ym_keys);
	} else {
		$erreur .= str_replace("##rep##", $options[$nomconfig]["dossier"], $message2)."<BR>";
	}
	// Effacer le cache
	clearstatcache();

} else {
	$erreur .= str_replace("##fic##", $ficConfigRestauration, $message1)."<BR>";
}
unset($tabDateYmd);

// Vérification que des informations sont à restaurer
if (sizeof($structureXml)>0) {
	//echo "<PRE>";print_r($structureXml);echo "</PRE>";
	// Parcours des bases
	$bases_keys = array_keys($structureXml);	
	for ($i=0;$i<sizeof($bases_keys);$i++) {
		// Parcours des tables
		$tables_keys = array_keys($structureXml[$bases_keys[$i]]);
		for ($j=0;$j<sizeof($tables_keys);$j++) {
			// Parcours des données
			for ($k=0;$k<sizeof($structureXml[$bases_keys[$i]][$tables_keys[$j]]);$k++) {
				$structure .= xml2sqlCreate($bases_keys[$i], $structureXml[$bases_keys[$i]][$tables_keys[$j]], $droptable, $proteger, $ajouternombase);
			}
			$infos .= str_replace(array("##base##","##table##"), array($bases_keys[$i],$tables_keys[$j]), $message6)."<BR>";
		}
		unset($tables_keys);
	}	
	unset($bases_keys);
} else {
	$structure = "# ".$message8;
}
unset($structureXml);

if (sizeof($dataXml)>0) {
	//echo "<PRE>";print_r($dataXml);echo "</PRE>";
	// Parcours des bases
	$bases_keys = array_keys($dataXml);	
	for ($i=0;$i<sizeof($bases_keys);$i++) {
		// Parcours des tables
		$tables_keys = array_keys($dataXml[$bases_keys[$i]]);
		for ($j=0;$j<sizeof($tables_keys);$j++) {
			$nb = 0;
			// Parcours des données
			for ($k=0;$k<sizeof($dataXml[$bases_keys[$i]][$tables_keys[$j]]);$k++) {
				$dataTmp = xml2sqlInsert($bases_keys[$i], $dataXml[$bases_keys[$i]][$tables_keys[$j]][$k], $dataDtd[$bases_keys[$i]][$tables_keys[$j]], $proteger, $ajouternombase, $options[$nomconfig]["requete"]);
 				$data .= $dataTmp["sql"];
				$nb = $nb + $dataTmp["nb"];
			}			
			$infos .= str_replace(array("##nb##","##base##","##table##"), array($nb,$bases_keys[$i],$tables_keys[$j]), $message7)."<BR>";			
		}
		unset($tables_keys);	
	}
	unset($bases_keys);
} else {
	$data = "# ".$message9;
}
unset($dataXml);
unset($dataDtd);

// Affichage en mode texte
if ($affichage=="texte") {

	// Envoie des en-têtes
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");		
	//header("Content-Type: text/plain");
	header("Content-Length: ".strlen($structure."\n\n".$data));				
	header("Content-Disposition: attachment; filename=restore.txt");

	echo $structure."\n\n".$data;

	die();

// Affichage en mode Html
} else {

	// Affichage des en-têtes (normalement avant ce include, rien n'est affiché)
	include($rep_par_rapport_racine."inc/header.inc.php"); 
	
	echo "<HR>";
	echo "<U>".$texteInfos.":</U><BR><A CLASS=\"vert\">".$infos."</A>";
	echo "<HR SIZE=\"1\">";
	echo "<U>".$texteErreurs.":</U><BR><A CLASS=\"rouge\">".$erreur."</A>";
	echo "<HR>";
?>
<FORM NAME="<?=$PHP_SELF;?>?action=executer" METHOD="post" ACTION="">
<B><?=$texte1;?></B>
<TEXTAREA NAME="structure"<?=$taille_textarea1;?> ROWS="20" WRAP="off">
<?=$structure;?>
</TEXTAREA>
<BR>
<BR>
<B><?=$texte2;?></B>
<TEXTAREA NAME="structure"<?=$taille_textarea1;?> ROWS="40" WRAP="off">
<?=$data;?>
</TEXTAREA>
</FORM>
<?
	// Affichage du temps d'éxécution de la page
	echo $texteTmeps.": ".date("i:s", (time()-$tempsPageDeb))."\n";

	// Affichage du pied de page
	include($rep_par_rapport_racine."inc/footer.inc.php"); 	
}
?>