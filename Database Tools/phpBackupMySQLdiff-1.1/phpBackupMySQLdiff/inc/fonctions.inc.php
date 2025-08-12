<?
/**universal
 * Fonctions utilisées dans le site
 * @author Thomas Pequet
 * @version 1.0 
 */

/**universal
 * Rediriger vers une page
 * @param page:String Adresse de la nouvelle page pour la redirection
 */
function rediriger($page) {
	header("Request-URI: ".$page);
	header("Content-Location: ".$page);
	header("Location: ".$page);
}

/**universal
 * Fonction pour enlever les accents d'une chaine
 * @param chaine:String Chaine à purger
 * @return String Chaine purgée
 */
function enlever_accents($chaine) {
	return strtr($chaine, "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ", "aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");
}

/**universal
 * Fonction qui indique si le navigateur est compatible avec les styles pour les INPUT, OPTION et TEXTEAREA
 * @return Boolean True si le navigateur est compatible avec les styles
 */
function compatible_style() {
	global $HTTP_USER_AGENT;
	return eregi("msie",$HTTP_USER_AGENT) || eregi("mozilla/5",$HTTP_USER_AGENT);
}

/**universal
 * Fonction qui charge les paramètres de configuration du logiciel provenant d'un fichier XML
 * @param fichier:String Fichier contenant les paramètres de configuration
 * @return Boolean Tableau des paramètres de configuration
 */
function chargerConfig($fichier) {
	global $options;
	
	// Tableau des options
	$options = array();
	
	if (!function_exists("startElement")) {
		function startElement($parser, $name, $attrs) {
			global $etage, $config;
			$etage++;
			if ($etage==2) {
				$config = $attrs["NAME"];
			}
		}
	}
	
	if (!function_exists("characterData")) {	
		function characterData($parser, $data) {
			global $etage, $donnees;	
			$donnees = trim($data);
		}	
	}
	
	if (!function_exists("endElement")) {	
		function endElement($parser, $name) {
			global $etage, $donnees, $options, $config;
			if ($etage>1) {
				switch($name) {
		   			case "CONFIG":						
		   				break;	
		   			case "TABLE":
						list($base, $table) = split("\.", $donnees);
						$options[$config]["tables"][$base][$table] = 1;								
		   				break;	
					default:
						$options[$config][strtolower($name)] = $donnees;
						break;
				}			
			}
			$etage--;
		}
	}
	
	// Création de l'analyseur XML
	$xml_parser = xml_parser_create();
	// Affecte les gestionnaires de début et de fin
	xml_set_element_handler($xml_parser, "startElement", "endElement");
	// Affecte les gestionnaires de caractère bruts
	xml_set_character_data_handler($xml_parser, "characterData");
	
	// Ouverture du fichier
	if ($fp = @fopen($fichier, "r")) {
		while ($data = fread($fp, 4096)) {
		    if (!xml_parse($xml_parser, $data, feof($fp))) {
	    	    die(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)));
	    	}
		}
		// Fermeture du fichier
		fclose($fp);
	}
	
	// Détruire l'analyseur XML
	xml_parser_free($xml_parser);	

	return $options;
}

/**universal
 * Fonction qui transforme du XML en requêtes SQL d'insertion
 * @param nombase:String Nom de la base
 * @param contenuXml:String Fichier Xml à transformer
 * @param contenuDtd:String Fichier Dtd contenant la structure de la table
 * @param proteger:Boolean Indique si il faut ajouter des ` devant les noms des tables
 * @param ajouternombase:Boolean Indique Indique si il faut ajouter le nom de la base devant les noms des tables
 * @param requete:String Requete à éffectuer pour la sélection des Insert
 * @return Liste des requêtes SQL
 */
function xml2sqlInsert($nombase, $contenuXml, $contenuDtd, $proteger = false, $ajouternombase = false, $requete = "") {
	global $sql, $fields, $values, $table, $base, $protegerchamps, $ajouterbase, $listeFields, $fieldTrouve, $tabRequete, $insertOk, $fieldTrouveRequete;
	
	// Chaine contenant les requête SQL
	$sql = "";
	// Chaine contenant les champs d'un requête
	$fields = "";
	// Chaine contenant les valeurs d'un requête
	$values = "";
	// Nom de la table
	$table = "";
	// Nombre d'INSERT
	$nb = 0;
	// Savoir si l'INSERT doit être écrit
	$insertOk = true;
	
	// Ré-affectation des variables passées en paramètres
	$base = $nombase;
	$protegerchamps = $proteger;
	$ajouterbase = $ajouternombase;
	$tabRequete = array();
	if (ereg("^([0-9a-zA-Z_-]{1,})(=|!=|like|notlike|>|>=|<|<=)(.*)$", $requete))
	{
		$tabRequete["champ"] 		= ereg_replace("^([0-9a-zA-Z_-]{1,})(=|!=|like|notlike|>|>=|<|<=)(.*)$", "\\1", $requete);
		$tabRequete["operateur"] 	= ereg_replace("^([0-9a-zA-Z_-]{1,})(=|!=|like|notlike|>|>=|<|<=)(.*)$", "\\2", $requete);
		$tabRequete["valeur"] 		= ereg_replace("^([0-9a-zA-Z_-]{1,})(=|!=|like|notlike|>|>=|<|<=)(.*)$", "\\3", $requete);
	}
	
	// Récupération des champ de la table par apport à DTD
	if ($contenuDtd!="") {
		$listeFieldsTmp = split('[|]', ereg_replace("(^.*<!ATTLIST FIELD NAME \()(.*)(\) \"\" >.*$)", "\\2", $contenuDtd));
		for ($i=0;$i<sizeof($listeFields);$i++) {
			$listeFields[$listeFieldsTmp[$i]] = 1;
		}
		unset($listeFieldsTmp);
	} else
		$listeFields = "";
	
	if (!function_exists("startElement1")) {
		function startElement1($parser, $name, $attrs) {
			global $sql, $fields, $values, $table, $base, $protegerchamps, $ajouterbase, $listeFields, $fieldTrouve, $tabRequete, $insertOk, $fieldTrouveRequete;
			switch($name) {
		   		case "DATA":	
					$fields = "";
					$values = "";	
					$insertOk = true;		
		   			break;	
		   		case "TABLE":	
					if ($ajouterbase)					
						$table = $base.".".$attrs["NAME"];		
					else
						$table = $attrs["NAME"];
					$sql .= "#\n";	
					$sql .= "# Données de la table `".$table."`\n";
					$sql .= "# Sauvegardé le ".substr($attrs["SAUVEGARDE"],6,2)."-".substr($attrs["SAUVEGARDE"],4,2)."-".substr($attrs["SAUVEGARDE"],0,4)." à ".substr($attrs["SAUVEGARDE"],8,2).":".substr($attrs["SAUVEGARDE"],10,2).":".substr($attrs["SAUVEGARDE"],12,2)."\n";	
					$sql .= "#\n";	
		   			break;
				case "FIELD":	
					$fieldTrouve = false;
					// Vérifier que ce champ est bien dans cette table
					if (is_array($listeFields) && in_array($attrs["NAME"], $listeFields)) {
						$fieldTrouve = true;
					} else {
						$fieldTrouve = true;
					}
					
					if ($fieldTrouve) {
						$fieldTmp = $attrs["NAME"];
						
						// Vérification de cette variable avec la requete
						if (sizeof($tabRequete)==3 && $tabRequete["champ"]==$fieldTmp)
							$fieldTrouveRequete = true;					
						else if (sizeof($tabRequete)==3)
							$fieldTrouveRequete = false;
						else
							$fieldTrouveRequete = true;
					
						if ($insertOk) {
							if ($fields!="")			
								$fields .= ", ";
							if ($protegerchamps)
								$fields .= "`".$fieldTmp."`";
							else
								$fields .= "".$fieldTmp."";
						}
					}
		   			break;						
				default:						
					break;
			} 
		}
	}
	
	if (!function_exists("characterData1")) {	
		function characterData1($parser, $data) {
			global $values, $fieldTrouve, $tabRequete, $insertOk, $fieldTrouveRequete;
			if ($fieldTrouve && $insertOk) {
				if (trim($data)!="") {
					$valuesTmp = urldecode(str_replace(array("%0A","%0D","%09"),array("\\n","\\r","\\t"),$data));

					// Vérification de cette valeur avec la requete
					if ($fieldTrouveRequete && sizeof($tabRequete)==3) {
						if ($tabRequete["operateur"]=="=" && $tabRequete["valeur"]==$valuesTmp)
							$insertOk = true;
						else if ($tabRequete["operateur"]=="!=" && $tabRequete["valeur"]!=$valuesTmp)
							$insertOk = true;
						else if ($tabRequete["operateur"]==">" && $tabRequete["valeur"]<$valuesTmp)
							$insertOk = true;
						else if ($tabRequete["operateur"]==">=" && $tabRequete["valeur"]<=$valuesTmp)
							$insertOk = true;
						else if ($tabRequete["operateur"]=="<" && $tabRequete["valeur"]>$valuesTmp)
							$insertOk = true;
						else if ($tabRequete["operateur"]=="<=" && $tabRequete["valeur"]>=$valuesTmp)
							$insertOk = true;
						else if ($tabRequete["operateur"]=="like" && eregi($tabRequete["valeur"], $valuesTmp))
							$insertOk = true;
						else if ($tabRequete["operateur"]=="notlike" && !eregi($tabRequete["valeur"], $valuesTmp))
							$insertOk = true;
						else
							$insertOk = false;
					}

					if ($insertOk) {
						if ($values!="")			
							$values .= ", ";
						$values .= "'".$valuesTmp."'";
					}
				}
			}
		}	
	}
	
	if (!function_exists("endElement1")) {	
		function endElement1($parser, $name) {
			global $sql, $fields, $values, $table, $protegerchamps, $nb, $insertOk;
			switch($name) {
				case "TABLE":	
					$sql .= "\n";		
		   			break;
		   		case "DATA":	
		   			if ($insertOk) {
						if ($protegerchamps)
							$sql .= "INSERT INTO `".$table."` (".$fields.") VALUES (".$values.");\n";		
						else
							$sql .= "INSERT INTO ".$table." (".$fields.") VALUES (".$values.");\n";		
						$nb++;
					}
		   			break;	
				default:					
					break;
			}
		}
	}
	
	// Création de l'analyseur XML
	$xml_parser = xml_parser_create();
	// Affecte les gestionnaires de début et de fin
	xml_set_element_handler($xml_parser, "startElement1", "endElement1");
	// Affecte les gestionnaires de caractère bruts
	xml_set_character_data_handler($xml_parser, "characterData1");
		
	if (!xml_parse($xml_parser, $contenuXml)) {
	   die(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)));
	}
	
	// Détruire l'analyseur XML
	xml_parser_free($xml_parser);		
	
	return array("sql" => $sql, "nb" => $nb);
}

/**universal
 * Fonction qui transforme du XML en requêtes SQL de création de table
 * @param nombase:String Nom de la base
 * @param contenuXml:String Fichier Xml à transformer
 * @param drop:Boolean Indique si il faut ajouter des DROP TABLE avant les requetes de creation de tables
 * @param proteger:Boolean Indique si il faut ajouter des ` devant les noms des tables
 * @param ajouternombase:Boolean Indique Indique si il faut ajouter le nom de la base devant les noms des tables
 * @return Liste des requêtes SQL
 */
function xml2sqlCreate($nombase, $contenuXml, $drop = false, $proteger = false, $ajouternombase = false) {
	global $sql, $values, $table, $dropTable, $base, $protegerchamps, $ajouterbase;

	// Chaine contenant la requête SQL
	$sql = "";
	// Chaine contenant la fin de la requête SQL
	$finsql = "";
	// Valeurs d'un requête
	$values = "";
	// Nom de la table
	$table = "";

	// Ré-affectation des variables passées en paramètres
	$base = $nombase;
	$dropTable = $drop;
	$protegerchamps = $proteger;
	$ajouterbase = $ajouternombase;	
	
	if (!function_exists("startElement2")) {
		function startElement2($parser, $name, $attrs) {
			global $sql, $finsql, $field, $key, $table, $dropTable, $base, $protegerchamps, $ajouterbase;
			switch($name) {
		   		case "TABLE":	
					$table = $attrs["NAME"];	
					
					$sql .= "#\n";
					if ($ajouterbase)		
						$sql .= "# Structure de la table `".$base.".".$table."`\n";
					else
						$sql .= "# Structure de la table `".$table."`\n";
					$sql .= "# Sauvegardé le ".substr($attrs["SAUVEGARDE"],6,2)."-".substr($attrs["SAUVEGARDE"],4,2)."-".substr($attrs["SAUVEGARDE"],0,4)." à ".substr($attrs["SAUVEGARDE"],8,2).":".substr($attrs["SAUVEGARDE"],10,2).":".substr($attrs["SAUVEGARDE"],12,2)."\n";	
					$sql .= "#\n";	
					if ($ajouterbase) {
						if ($protegerchamps)
							$sql .= "USE `".$base."`;\n";
						else
							$sql .= "USE ".$base.";\n";
					}
					if ($dropTable)	{
						if ($protegerchamps)
							$sql .= "DROP TABLE IF EXISTS `".$table."`;\n";	
						else
							$sql .= "DROP TABLE IF EXISTS ".$table.";\n";	
					}
					if ($protegerchamps)
						$sql .= "CREATE TABLE `".$table."` (\n";
					else
						$sql .= "CREATE TABLE ".$table." (\n";
		   			break;
				case "FIELD":	
					$sql .= "   ";
					if ($protegerchamps)
						$sql .= "`".$attrs["NAME"]."`";
					else
						$sql .= "".$attrs["NAME"]."";
					if (isset($attrs["TYPE"]) && $attrs["TYPE"]!="")
						$sql .= " ".$attrs["TYPE"];
					if (isset($attrs["NULL"]) && $attrs["NULL"]=="YES")
						$sql .= " NULL";
					else
						$sql .= " NOT NULL";
					if (isset($attrs["DEFAULT"]) && $attrs["DEFAULT"]!="")
						$sql .= " default '".$attrs["DEFAULT"]."'";
					if (isset($attrs["EXTRA"]) && $attrs["EXTRA"]!="")
						$sql .= " ".$attrs["EXTRA"];
					$sql .= ",\n";
		   			break;		
				case "KEY":
					if ($attrs["SEQ_IN_INDEX"]=="1") {
						$sql .= "   ";
						if ($attrs["NAME"]=="PRIMARY") {
							if ($protegerchamps)
								$sql .= "PRIMARY KEY (`".$attrs["COLUMN_NAME"]."`)";
							else
								$sql .= "PRIMARY KEY (".$attrs["COLUMN_NAME"].")";
						} else {
							if ($attrs["COMMENT"]=="FULLTEXT") 
								$sql .= "FULLTEXT ";
							else if ($attrs["CARDINALITY"]=="62") 
								$sql .= "UNIQUE ";					
							if ($protegerchamps)
								$sql .= "KEY `".$attrs["NAME"]."` (`".$attrs["COLUMN_NAME"]."`)";
							else
								$sql .= "KEY ".$attrs["NAME"]." (".$attrs["COLUMN_NAME"].")";
						}
					} else {
						// Supprimer la virgule de fin de ligne
						if ($protegerchamps) {
							if (substr($sql,strlen($sql)-4,3)=="`),")
								$sql = substr($sql,0,strlen($sql)-4).", `".$attrs["COLUMN_NAME"]."`)";							
						} else {
							if (substr($sql,strlen($sql)-3,2)=="),")
								$sql = substr($sql,0,strlen($sql)-3).", ".$attrs["COLUMN_NAME"].")";	
						}
					}	
					$sql .= ",\n";
					break;	
				case "STATUS":
					if (isset($attrs["TYPE"]))
						$finsql = ") TYPE=".$attrs["TYPE"].";\n\n";
					else
						$finsql = ");\n\n";
					break;						
				default:						
					break;
			} 
		}
	}
	
	if (!function_exists("characterData2")) {	
		function characterData2($parser, $data) {
			global $values;
			if (trim($data)!="") {
				if ($values!="")			
					$values .= ", ";
				$values .= "'".addslashes(urldecode($data))."'";
			}
		}	
	}
	
	if (!function_exists("endElement2")) {	
		function endElement2($parser, $name) {
			global $sql, $finsql, $values, $table;
			switch($name) {
				case "TABLE":
					// Supprimer la virgule de fin de ligne
					if (substr($sql,strlen($sql)-2,1)==",")
						$sql = substr($sql,0,strlen($sql)-2)."\n";
					$sql .= $finsql;		
		   			break;
				default:					
					break;
			}
		}
	}
	
	// Création de l'analyseur XML
	$xml_parser = xml_parser_create();
	// Affecte les gestionnaires de début et de fin
	xml_set_element_handler($xml_parser, "startElement2", "endElement2");
	// Affecte les gestionnaires de caractère bruts
	xml_set_character_data_handler($xml_parser, "characterData2");
		
	if (!xml_parse($xml_parser, $contenuXml)) {
	   die(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)));
	}
	
	// Détruire l'analyseur XML
	xml_parser_free($xml_parser);		
	
	return $sql;
}
?>