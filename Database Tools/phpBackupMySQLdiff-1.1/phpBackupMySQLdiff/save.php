<?php
/**universal
 * Sauvegarde de tables sous forme de fichies XML
 * 
 * @author Thomas Pequet
 * @version 1.0 
 */

// Repertoire dans lequel on est par rapport à la racine du site
$rep_par_rapport_racine = "";

// Identifant de la page (0, ou 1, ...)
$page = "2";

// Temps limite d'execution du script en secondes
@set_time_limit(300);

// Fichiers à inclure dans la page
@session_start();
include_once($rep_par_rapport_racine."inc/fonctions.inc.php");
include($rep_par_rapport_racine."inc/config.inc.php");
include($rep_par_rapport_racine.$ficConnBase);
include($rep_par_rapport_racine."lang/".$langue.".inc.php");
include($rep_par_rapport_racine."inc/img.inc.php");
include_once($rep_par_rapport_racine."lib/archive.php");
include_once($rep_par_rapport_racine."lib/fichier.php");

// Constantes de la page (en majuscules)

// Variables de la page
$infos = "";
$erreur = "";
$dateFic = date("YmdHis");
$dateDos = date("Ym");
$dateFicArchive = date("dHis");
$tempsPageDeb = time();
// Chargement des paramètres de sauvegarde contenu dans le fichier XML
$options = chargerConfig($rep_par_rapport_racine.$ficConfigSauvegarde);

// Fonctions de la page

// Actions de la page
if (isset($nomconfig) && isset($options[$nomconfig])) {
	
	// Vérification que le dossier racine existe sinon on tente de le créer
	if (!is_dir($options[$nomconfig]["dossier"])) {
		if (!@mkdir($options[$nomconfig]["dossier"], 0755))
			$erreur .= str_replace("##rep##", $options[$nomconfig]["dossier"], $message1)."<BR>";
	}
	// Effacer le cache
	clearstatcache();
	
	// Vérification qu'il n'y pas eu d'erreur avant la sauvegarde
	if ($erreur=="") {
		// Parcours des bases ayant des tables à sauvegarder
		if (is_array($options[$nomconfig]["tables"]))
			$bases_keys = array_keys($options[$nomconfig]["tables"]);	
		else
			$bases_keys = array();
		for ($i=0;$i<sizeof($bases_keys);$i++) {
			// Selection de la base de données
			$querySql = "USE `".$bases_keys[$i]."`";
			if (!$bd->sql_query($querySql)) {
				$erreur .= str_replace("##base##", $bases_keys[$i], $message3)."<BR>";;
			}
				
			if ($erreur=="") {		
				// Vérification que le dossier de cette base existe sinon on tente de le créer
				if (!is_dir($options[$nomconfig]["dossier"]."/".$bases_keys[$i])) {
					if (!@mkdir($options[$nomconfig]["dossier"]."/".$bases_keys[$i], 0755))
						$erreur .= str_replace("##rep##", $options[$nomconfig]["dossier"]."/".$bases_keys[$i], $message1)."<BR>";
				}
				// Effacer le cache
				clearstatcache();
					
				if ($erreur=="") {
				
					// Récupération de la date du dernier backup: AnnéeMois
					$handle = @opendir($options[$nomconfig]["dossier"]."/".$bases_keys[$i]);
					$listeDossiers = array();
					while ($element = @readdir($handle)){		
						if($element!="." && $element!=".." && is_dir($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$element)) {
							$listeDossiers[sizeof($listeDossiers)] = $element;
						}
					}
					@closedir($handle);
					// Effacer le cache
					clearstatcache();					
					
					if (sizeof($listeDossiers)>0) {
						sort($listeDossiers);
						$anneeMoisDernierBackup = $listeDossiers[sizeof($listeDossiers)-1];
					} else {
						$anneeMoisDernierBackup = null;
					}
					unset($listeDossiers);
				
					// Selection des tables de la base de données
					$tables_keys = array_keys($options[$nomconfig]["tables"][$bases_keys[$i]]);		
					for ($j=0;$j<sizeof($tables_keys);$j++) {
				
						// Variables locales
						$infosTmp = "";
						$erreurTmp = "";
						$contenuFicXmlStructure = "";
						$contenuFicDtdStructure = "";
						$contenuFicXmlData = "";
						$contenuFicDtdData = "";
						$timestampDernierBackupStructure = "19700101000000";
						$timestampDernierBackupData = "19700101000000";
						
						// Vérification que le dossier de la date du mois existe
						if (is_dir($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$dateDos)) {
							$repDateExiste = true;
							// Vérification que le dossier de la table existe
							if (is_dir($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$dateDos."/".$tables_keys[$j])) {
								$repTableExiste = true;						
							} else {
								$repTableExiste = false;
							}
						} else {
							$repDateExiste = false;
							$repTableExiste = false;
						}
						// Effacer le cache
						clearstatcache();
						
						// Récupération de la date du dernier backup
						if (isset($anneeMoisDernierBackup)) {
							$listeFichiersData = array();
							$listeFichiersStructure = array();
							$handle = @opendir($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$anneeMoisDernierBackup."/".$tables_keys[$j]);
							while ($element = @readdir($handle)){		
								if($element!="." && $element!=".." && is_file($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$anneeMoisDernierBackup."/".$tables_keys[$j]."/".$element)) {
									if (ereg("data", $element)) {
										list($timestampTmp, $reste) = split('-', $element);
										$listeFichiersData[sizeof($listeFichiersData)] = $anneeMoisDernierBackup.$timestampTmp;
									} else if (ereg("structure", $element)) {
										list($timestampTmp, $reste) = split('-', $element);
										$listeFichiersStructure[sizeof($listeFichiersStructure)] = $anneeMoisDernierBackup.$timestampTmp;
									}
								}
							}
							@closedir($handle);
							// Effacer le cache
							clearstatcache();
							if (sizeof($listeFichiersData)>0) {
								sort($listeFichiersData);
								$timestampDernierBackupData = $listeFichiersData[sizeof($listeFichiersData)-1];
							}
							if (sizeof($listeFichiersStructure)>0) {
								sort($listeFichiersStructure);
								$timestampDernierBackupStructure = $listeFichiersStructure[sizeof($listeFichiersStructure)-1];
							}		
							unset($listeFichiersData);				
							unset($listeFichiersStructure);
						}
													
						// Sauvegarde de la structure de la table
						$fields = array();
						$querySql = "SHOW FIELDS FROM `".$tables_keys[$j]."`";
						$result = $bd->sql_query($querySql);			
						if ($bd->sql_numrows($result)>0) {
							$contenuFicXmlStructure .= "<TABLE NAME=\"".$tables_keys[$j]."\" SAUVEGARDE=\"".$dateFic."\">\n";
							while($row = $bd->sql_fetchrow($result)) {
								$fields[sizeof($fields)] = $row["Field"];
								$contenuFicXmlStructure .= "\t<FIELD NAME=\"".$row["Field"]."\"";
								if ($row["Type"]!="")
									$contenuFicXmlStructure .= " TYPE=\"".$row["Type"]."\"";
								if ($row["Null"]!="")
									$contenuFicXmlStructure .= " NULL=\"".$row["Null"]."\"";
								if ($row["Key"]!="")
									$contenuFicXmlStructure .= " KEY=\"".$row["Key"]."\"";
								if ($row["Default"]!="")
									$contenuFicXmlStructure .= " DEFAULT=\"".$row["Default"]."\"";
								if ($row["Extra"]!="")
									$contenuFicXmlStructure .= " EXTRA=\"".$row["Extra"]."\"";
								$contenuFicXmlStructure .= " />\n";
							}
							$bd->sql_freeresult($result);
							
							$querySql = "SHOW KEYS FROM `".$tables_keys[$j]."`";
							$result = $bd->sql_query($querySql);
							if ($bd->sql_numrows($result)>0) {
								while($row = $bd->sql_fetchrow($result)) {
									$contenuFicXmlStructure .= "\t<KEY NAME=\"".$row["Key_name"]."\"";
									if ($row["Table"]!="")
										$contenuFicXmlStructure .= " TABLE=\"".$row["Table"]."\"";								
									if ($row["Nom_unique"]!="")
										$contenuFicXmlStructure .= " NOM_UNIQUE=\"".$row["Nom_unique"]."\"";
									if ($row["Seq_in_index"]!="")
										$contenuFicXmlStructure .= " SEQ_IN_INDEX=\"".$row["Seq_in_index"]."\"";
									if ($row["Column_name"]!="")
										$contenuFicXmlStructure .= " COLUMN_NAME=\"".$row["Column_name"]."\"";
									if ($row["Collation"]!="")
										$contenuFicXmlStructure .= " COLLATION=\"".$row["Collation"]."\"";
/* Données non sauvegardées car génantes pour la restauration	

									if ($row["Cardinality"]!="")
										$contenuFicXmlStructure .= " CARDINALITY=\"".$row["Cardinality"]."\"";
*/
 									if ($row["Sub_part"]!="")
										$contenuFicXmlStructure .= " SUB_PART=\"".$row["Sub_part"]."\"";
									if ($row["Packed"]!="")
										$contenuFicXmlStructure .= " PACKED=\"".$row["Packed"]."\"";
									if ($row["Comment"]!="")
										$contenuFicXmlStructure .= " COMMENT=\"".$row["Comment"]."\"";
									$contenuFicXmlStructure .= " />\n";
								}
							}
							$bd->sql_freeresult($result);
							
							// Récupération des infos des tables
							$querySql = "SHOW TABLE STATUS LIKE '".$tables_keys[$j]."'";
							$result = $bd->sql_query($querySql);
							if ($bd->sql_numrows($result)>0) {
								while($row = $bd->sql_fetchrow($result)) {
									$contenuFicXmlStructure .= "\t<STATUS";
									if ($row["Type"]!="")
										$contenuFicXmlStructure .= " TYPE=\"".$row["Type"]."\"";								
/* Données non sauvegardées car génantes pour la restauration	

									if ($row["Row_format"]!="")
										$contenuFicXmlStructure .= " ROW_FORMAT=\"".$row["Row_format"]."\"";
									if ($row["Rows"]!="")
										$contenuFicXmlStructure .= " ROWS=\"".$row["Rows"]."\"";
									if ($row["Avg_row_length"]!="")
										$contenuFicXmlStructure .= " AVG_ROW_LENGTH=\"".$row["Avg_row_length"]."\"";
									if ($row["Data_length"]!="")
										$contenuFicXmlStructure .= " DATA_LENGTH=\"".$row["Data_length"]."\"";
									if ($row["Max_data_length"]!="")
										$contenuFicXmlStructure .= " MAX_DATA_LENGTH=\"".$row["Max_data_length"]."\"";
									if ($row["Index_length"]!="")
										$contenuFicXmlStructure .= " INDEX_LENGTH=\"".$row["Index_length"]."\"";
									if ($row["Data_free"]!="")
										$contenuFicXmlStructure .= " DATA_FREE=\"".$row["Data_free"]."\"";
									if ($row["Auto_increment"]!="")
										$contenuFicXmlStructure .= " AUTO_INCREMENT=\"".$row["Auto_increment"]."\"";
									if ($row["Create_time"]!="")
										$contenuFicXmlStructure .= " CREATE_TIME=\"".$row["Create_time"]."\"";
									if ($row["Update_time"]!="")
										$contenuFicXmlStructure .= " UPDATE_TIME=\"".$row["Update_time"]."\"";
									if ($row["Ckeck_time"]!="")
										$contenuFicXmlStructure .= " CKECK_TIME=\"".$row["Ckeck_time"]."\"";
									if ($row["Create_options"]!="")
										$contenuFicXmlStructure .= " CREATE_OPTIONS=\"".$row["Create_options"]."\"";
									if ($row["Comment"]!="")
										$contenuFicXmlStructure .= " COMMENT=\"".$row["Comment"]."\"";
*/
									$contenuFicXmlStructure .= " />\n";
								}
							}	
							
							$contenuFicXmlStructure .= "</TABLE>";
							$contenuFicDtdStructure .= "<!ELEMENT TABLE (FIELD*,KEY*) >\n";	
							$contenuFicDtdStructure .= "<!ATTLIST TABLE NAME CDATA \"".$tables_keys[$j]."\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST TABLE SAUVEGARDE CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ELEMENT FIELD (#PCDATA) >\n";	
							$contenuFicDtdStructure .= "<!ATTLIST FIELD NAME CDATA \"\" >\n";					
							$contenuFicDtdStructure .= "<!ATTLIST FIELD TYPE CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST FIELD NULL CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST FIELD KEY CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST FIELD DEFAULT CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST FIELD EXTRA CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ELEMENT KEY (#PCDATA) >\n";						
							$contenuFicDtdStructure .= "<!ATTLIST KEY NAME CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST KEY TABLE CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST KEY NOM_UNIQUE CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST KEY SEQ_IN_INDEX CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST KEY COLUMN_NAMEE CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST KEY COLLATION CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST KEY CARDINALITY CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST KEY SUB_PART CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST KEY PACKED CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST KEY COMMENT CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ELEMENT STATUS (#PCDATA) >\n";						
							$contenuFicDtdStructure .= "<!ATTLIST STATUS TYPE CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST STATUS ROW_FORMAT CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST STATUS ROWS CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST STATUS AVG_ROW_LENGTH CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST STATUS DATA_LENGTH CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST STATUS MAX_DATA_LENGTH CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST STATUS INDEX_LENGTH CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST STATUS DATA_FREE CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST STATUS AUTO_INCREMENT CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST STATUS CREATE_TIME CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST STATUS UPDATE_TIME CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST STATUS CKECK_TIME CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST STATUS CREATE_OPTIONS CDATA \"\" >\n";
							$contenuFicDtdStructure .= "<!ATTLIST STATUS COMMENT CDATA \"\" >\n";
							$contenuFicDtdData .= "<!ELEMENT TABLE (DATA*) >\n";	
							$contenuFicDtdData .= "<!ATTLIST TABLE NAME CDATA \"".$tables_keys[$j]."\" >\n";
							$contenuFicDtdData .= "<!ATTLIST TABLE SAUVEGARDE CDATA \"\" >\n";
							$contenuFicDtdData .= "<!ELEMENT DATA (FIELD?) >\n";
							$contenuFicDtdData .= "<!ELEMENT FIELD (#PCDATA) >\n";
							$fieldsListe = "";							
							for ($k=0;$k<sizeof($fields);$k++) {
								if ($fieldsListe!="")
									$fieldsListe .= "|";
								$fieldsListe .= $fields[$k];
							}				
							$contenuFicDtdData .= "<!ATTLIST FIELD NAME (".$fieldsListe.") \"\" >\n";																					
							unset($fieldsListe);
						}
						$bd->sql_freeresult($result);				
						
						// Vérification que la structre n'a pas changée
						if ($options[$nomconfig]["infos"]=="structure" || $options[$nomconfig]["infos"]=="data") {
							$dateTmp = substr($timestampDernierBackupStructure,6);
							// Vérification que l'archive existe
							if (is_file($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$anneeMoisDernierBackup."/".$tables_keys[$j]."/".$dateTmp."-structure.zip")) {
								$archiveTmp = new archive($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$anneeMoisDernierBackup."/".$tables_keys[$j], $dateTmp."-structure.zip");
							} else if (is_file($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$anneeMoisDernierBackup."/".$tables_keys[$j]."/".$dateTmp."-structure.tar")) {
								$archiveTmp = new archive($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$anneeMoisDernierBackup."/".$tables_keys[$j], $dateTmp."-structure.tar");
							} else if (is_file($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$anneeMoisDernierBackup."/".$tables_keys[$j]."/".$dateTmp."-structure.tgz")) {
								$archiveTmp = new archive($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$anneeMoisDernierBackup."/".$tables_keys[$j], $dateTmp."-structure.tgz");
							} else if (is_file($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$anneeMoisDernierBackup."/".$tables_keys[$j]."/".$dateTmp."-structure.tar.gz")) {
								$archiveTmp = new archive($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$anneeMoisDernierBackup."/".$tables_keys[$j], $dateTmp."-structure.tar.gz");
							} else {
								$archiveTmp = null;
							}
							// Effacer le cache
							clearstatcache();
							// Extraire le fichier Xml de la strucutre et comparer son contenu
							if (isset($archiveTmp)) {
								$contenuFicXmlStructureTmp = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" standalone=\"no\"?>\n".$contenuFicXmlStructure;
								// Extraire le fichier XML
								$archiveTmp->extraire_fichier($rep_tmp, "", $timestampDernierBackupStructure."-structure.xml");
								// Création d'un objet Fichier
								$fichierTmp = new fichier($rep_tmp, $timestampDernierBackupStructure."-structure.xml");
								// Récupération du contenu
								$contenuTmp = $fichierTmp->lire("r");
								// Suppression des balises génantes
								$contenuTmp = eregi_replace("<!([^>\n]*)>\n", "", $contenuTmp);
								$contenuTmp = eregi_replace("<TABLE([^>\n]*)>\n", "<TABLE>\n", $contenuTmp);
								$contenuFicXmlStructureTmp = eregi_replace("<TABLE([^>\n]*)>\n", "<TABLE>\n", $contenuFicXmlStructureTmp);
								// Supprimer le fichier
								$fichierTmp->supprimer();							
								// Verifier que la structure de la table a été modifié depuis la dernière sauvegarde
								if ($contenuTmp!=$contenuFicXmlStructureTmp)
									$structureModifier = true;
								else
									$structureModifier = false;
									
								unset($contenuTmp);
								unset($archiveTmp);
								unset($fichierTmp);
							} else {
								$structureModifier = true;
							}
							if ($structureModifier)
								$infosTmp .= str_replace("##table##", $bases_keys[$i].".".$tables_keys[$j], $message5)."<BR>";
						}					
					
						// Récupération des enregistrements modifiés
						if ($options[$nomconfig]["infos"]=="dataonly" || $options[$nomconfig]["infos"]=="data") {					
							$querySql = "SELECT * FROM `".$tables_keys[$j]."` WHERE TIMESTAMP>='".$timestampDernierBackupData."'";
							$result = $bd->sql_query($querySql) or $erreurTmp .= str_replace("##table##", $bases_keys[$i].".".$tables_keys[$j], $message4)."<BR>";
							if ($erreurTmp=="" && $bd->sql_numrows($result)>0) {
								$contenuFicXmlData .= "<TABLE NAME=\"".$tables_keys[$j]."\" SAUVEGARDE=\"".$dateFic."\">\n";
								while($row = $bd->sql_fetchrow($result)) {
									$contenuFicXmlData .= "\t<DATA>\n";
									for ($k=0;$k<sizeof($fields);$k++) {
										if ($row["$fields[$k]"]!="")
											$contenuFicXmlData .= "\t\t<FIELD NAME=\"".$fields[$k]."\">".urlencode($row["$fields[$k]"])."</FIELD>\n";
									}
									$contenuFicXmlData .= "\t</DATA>\n";
								}
								$contenuFicXmlData .= "</TABLE>";
								$infosTmp .= str_replace(array("##nb##", "##table##"), array($bd->sql_numrows($result), $bases_keys[$i].".".$tables_keys[$j]), $message6)."<BR>";
								$dataModifier = true;
							} else {									
								$dataModifier = false;
							}	
							$bd->sql_freeresult($result);						
						}	
						unset($fields);
					
						// Sauvegarde des données si elles ont été modifiée ou Sauvegarde de la structure si elle a été modifiée
						if ($dataModifier || $structureModifier) {
							
							// Création des répertoires
							if ($erreurTmp=="" && !$repDateExiste && !@mkdir($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$dateDos, 0755))
								$erreurTmp .= str_replace("##rep##", $options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$dateDos, $message1)."<BR>";
							if ($erreurTmp=="" && !$repTableExiste && !@mkdir($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$dateDos."/".$tables_keys[$j], 0755))
								$erreurTmp .= str_replace("##rep##", $options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$dateDos."/".$tables_keys[$j], $message1)."<BR>";
								
							if ($erreurTmp=="") {						
								if ($dataModifier) {
									// Ajout les en-têtes aux fichiers
									$contenuFicXmlData = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" standalone=\"no\"?>\n<!DOCTYPE ".$tables_keys[$j]." SYSTEM \"".$dateFic."-data.dtd\">\n".$contenuFicXmlData;
									// Création de l'archive								
									$archiveTmp = new archive($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$dateDos."/".$tables_keys[$j], $dateFicArchive."-data.".$options[$nomconfig]["formatarchive"]);
									// Création du fichier Data XML
									$fichierTmp = new fichier($rep_tmp, $dateFic."-data.xml");
									$fichierTmp->ecrire($contenuFicXmlData,"w+");
									// Ajouter le fichier à l'archive
									$archiveTmp->ajouter_fichier($rep_tmp, "", $dateFic."-data.xml", false);
									// Suppression du fichier
									$fichierTmp->supprimer();
									
									unset($archiveTmp);
									unset($fichierTmp);
								}
								if ($structureModifier) {
									// Ajout les en-têtes aux fichiers
									$contenuFicXmlStructure = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" standalone=\"no\"?>\n<!DOCTYPE ".$tables_keys[$j]." SYSTEM \"".$dateFic."-structure.dtd\">\n".$contenuFicXmlStructure;
									$contenuFicDtdStructure = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n".$contenuFicDtdStructure;				
									$contenuFicDtdData = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n".$contenuFicDtdData;
									// Création de l'archive	
									$archiveTmp = new archive($options[$nomconfig]["dossier"]."/".$bases_keys[$i]."/".$dateDos."/".$tables_keys[$j], $dateFicArchive."-structure.".$options[$nomconfig]["formatarchive"]);
									// Création du fichier Structure XML
									$fichierTmp = new fichier($rep_tmp, $dateFic."-structure.xml");
									$fichierTmp->ecrire($contenuFicXmlStructure,"w+");
									// Ajouter le fichier à l'archive
									$archiveTmp->ajouter_fichier($rep_tmp, "", $dateFic."-structure.xml", false);
									// Suppression du fichier
									$fichierTmp->supprimer();
									// Création du fichier Structure DTD
									$fichierTmp = new fichier($rep_tmp, $dateFic."-structure.dtd");
									$fichierTmp->ecrire($contenuFicDtdStructure,"w+");
									// Ajouter le fichier à l'archive
									$archiveTmp->ajouter_fichier($rep_tmp, "", $dateFic."-structure.dtd", false);
									// Suppression du fichier
									$fichierTmp->supprimer();
									// Création du fichier Data DTD
									$fichierTmp = new fichier($rep_tmp, $dateFic."-data.dtd");
									$fichierTmp->ecrire($contenuFicDtdData,"w+");
									// Ajouter le fichier à l'archive
									$archiveTmp->ajouter_fichier($rep_tmp, "", $dateFic."-data.dtd", false);
									// Suppression du fichier
									$fichierTmp->supprimer();
									
									unset($archiveTmp);
									unset($fichierTmp);
								}
							} else {
								$infosTmp = "";	
							}							
						}					
						$infos .= $infosTmp;
						$erreur .= $erreurTmp;						
					}
					unset($tables_keys);
				}
			}			
		}
		unset($bases_keys);
	}	
} else {
	$erreur .= str_replace("##fic##", $ficConfigSauvegarde, $message2)."<BR>";
}

// Affichage des en-têtes (normalement avant ce include, rien n'est affiché)
include($rep_par_rapport_racine."inc/header.inc.php"); 

echo "<HR>";
echo "<U>".$texteInfos.":</U><BR><A CLASS=\"vert\">"; if ($infos=="") echo $message7; else echo $infos; echo "</A>";
echo "<HR SIZE=\"1\">";
echo "<U>".$texteErreurs.":</U><BR><A CLASS=\"rouge\">".$erreur."</A>";
echo "<HR>";

// Affichage du temps d'éxécution de la page
echo $texteTmeps.": ".date("i:s", (time()-$tempsPageDeb))."\n";

// Affichage du pied de page
include($rep_par_rapport_racine."inc/footer.inc.php"); 
?>