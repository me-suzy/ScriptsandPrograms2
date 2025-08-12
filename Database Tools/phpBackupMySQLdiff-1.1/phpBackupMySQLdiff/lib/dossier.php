<?
/**universal
 * Cette classe permet de gérer les fichiers et les dossiers d'un dossier donné
 *  - collecter les infos du dossier (ex: Taille, Date de modification, Permissions, ...) 
 *  - créer un dossier dans le dossier
 *  - avoir la liste des dossier et sous-dossiers
 *  - avoir la liste des fichiers
 *  - rechercher des dossiers et fichiers par rapport à certains critères
 *  - renommer le dossier
 *  - créer le dossier
 *  - supprimer récursivement le dossier
 *  - télécharger le dossier dans un format compressé (zip, tar, tgz)
 *  - déplacer le dossier dans un autre répertoire
 * 
 * @author Thomas Pequet
 * @version 1.3
 */

class dossier {

	/**universal
	 * String: Chemin du dossier
	 */
	var $chemin = "";
	
	/**universal
	 * Array: Tableau des fichiers du dossier
	 */
	var $liste_fichiers = array();	

	/**universal
	 * Array: Tableau des dossiers du dossier
	 */
	var $liste_dossiers = array();

	/**universal
	 * Array: Infos du dossier
	 */
	var $infos = array();	
	
	/**universal
	 * Array: Indique si les fichier et les dossiers du dossier ont été listés
	 */
	var $lister = false;		

	/**universal
	 * Constructeur
	 * @param chemin:String Chemin du dossier
	 * @param creer:Boolean Créer le fichier si il n'existe pas lors de la création de l'objet
	 */
	function dossier($chemin, $creer = false) {
		// Initaliser la variable chemin
		$this->chemin = str_replace("\\", "/", $chemin);	
		// Enlever les caractères spéciaux
		if (!ereg("^[\.]{1,}", $chemin))
			$this->chemin = dirname($this->chemin)."/".$this->puger_caracteres_speciaux(basename($this->chemin));

		// Si le dossier n'existe pas alors on tente de le créer
		if ($creer && !$this->existe() && isset($chemin) && $chemin!="") { 
			$this->creer();		
		}			
	}	

	/**universal
	 * Retourne le chemin 	 
	 * @return String Chemin du dossier	 
	 */
	function retour_chemin() {		
		return $this->chemin;
	}

	/**universal
	 * Retourne le nom 	 
	 * @return String Nom du dossier	 
	 */
	function retour_nom() {		
		return basename($this->chemin);
	}	
	
	/**universal
	 * Retourne l'extension 	 
	 * @return String Extension du dossier	 
	 */
	function retour_extension() {		
		return $this->infos["extension"];
	}	
	
	/**universal
	 * Retourne le type 	 
	 * @return String Type du dossier	 
	 */
	function retour_type() {		
		return $this->infos["type"];
	}	
	
	/**universal
	 * Retourne la taille des fichiers et des dossiers du dossier 	 
	 * @return Int Taille du dossier en octets	 
	 */
	function retour_taille() {		
		$taille = 0;	

		// Vérification que le dossier a été listé
		if (!$this->retour_lister())
			$this->lister_collecter_dossiers_fichiers();
		
		// Taille des fichiers du dossier
		for ($i=0; $i<sizeof($this->liste_fichiers); $i++) {
			$taille = $taille + $this->liste_fichiers[$i]["taille"];
		}
		
		// Parcours des répertoires du dossier
		for ($i=0; $i<sizeof($this->liste_dossiers); $i++) {
			$obj_dossier_tmp = new dossier($this->retour_chemin()."/".$this->liste_dossiers[$i]["nom"]);
			if (!$obj_dossier_tmp->retour_lister())
				$obj_dossier_tmp->lister_collecter_dossiers_fichiers();
			$taille = $taille + $obj_dossier_tmp->retour_taille();
		}
		
		unset($obj_dossier_tmp);
		
		return $taille;
	}	
	
	/**universal
	 * Retourne true si le dossier a déja été listé
	 * @return Boolean True	si le dossier a déja été listé
	 */
	function retour_lister() {		
		return $this->lister;
	}		

	/**universal
	 * Indique si le dossier existe
	 * @return Boolean qui indique si le dossier existe
	 */		
	function existe() {
		return is_dir($this->retour_chemin());
	}	
	
	/**universal
	 * Purger le nom du dossier de ses caractères spéciaux
	 * @param $chaine:String Chaine à purger
	 * @return Chaine purgée
	 */
	function puger_caracteres_speciaux($chaine) {	
		return str_replace(array("\\","/",":","*","?","\"","<",">","|"), array("","","","","","","","","",""), $chaine);
	}				
	
	/**universal
	 * Collecter les infos du dossier
	 * @return Array Infos du dossier	 
	 */
	function collecter_infos() {
		$this->infos["nom"] = $this->retour_nom();
		$this->infos["chemin"] = $this->chemin;
		$this->infos["type"] = "Dossier";
		
		if ($this->existe()) { 
			// Effacer les variables en cache
			clearstatcache();		
		
			if (($taille_tmp = filesize($this->chemin)) == 0)
				$this->infos["taille"] = "-1";
			else
				$this->infos["taille"] = $taille_tmp;
			//$this->infos["atime"] = fileatime($this->chemin);
			$this->infos["mtime"] = filemtime($this->chemin);
			//$this->infos["ctime"] = filectime($this->chemin);
			$this->infos["permissions"] = fileperms($this->chemin);
			//$this->infos["gid"] = filegroup($this->chemin);					
			//$this->infos["uid"] = fileowner($this->chemin);
		}
			
		return $this->infos;
	}

	/**universal
	 * Lister et collecter les dossiers et les fichiers du chemin
	 * @param typeFichiers:Array Tableau contenant les extensions des fichiers à sélectionner	 
	 */
	 function lister_collecter_dossiers_fichiers($typeFichiers = array()) {		
		// Ouverture du répertoire
		$handle = @opendir($this->chemin);
		
		$this->liste_fichiers = array();	
		$this->liste_dossiers = array();
		$indice_fichier = 0;
		$indice_dossier = 0;
		
		// Parcours du répertoire
		while ($fichier = @readdir($handle)){		
			if($fichier!="." && $fichier!="..") {
				if(is_file($this->chemin."/".$fichier)){
					if (is_array($typeFichiers) && sizeof($typeFichiers)>0) {
						for ($i=0;$i<sizeof($typeFichiers);$i++) {
							if (eregi("\.".strtolower($typeFichiers[$i])."$", $fichier)) {
								$obj_fichier_tmp = new fichier($this->chemin, $fichier);	
								$this->liste_fichiers[$indice_fichier] = $obj_fichier_tmp->collecter_infos();
								$indice_fichier++;
								unset($obj_fichier_tmp);	
								break;
							}
						}
					} else {
						$obj_fichier_tmp = new fichier($this->chemin, $fichier);	
						$this->liste_fichiers[$indice_fichier] = $obj_fichier_tmp->collecter_infos();
						$indice_fichier++;
						unset($obj_fichier_tmp);				
					}				
				
				} else if (is_dir($this->chemin."/".$fichier)) {
					$obj_dossier_tmp = new dossier($this->chemin."/".$fichier);					
					$this->liste_dossiers[$indice_dossier] = $obj_dossier_tmp->collecter_infos();					
					$indice_dossier++;
					unset($obj_dossier_tmp);	
				}					
			}	
		}		
		
		// Fermeture du pointeur
		@closedir($handle);

		$this->lister = true;
	}
	
	/**universal
	 * Recherche les fichiers et les dossiers
	 * @param $nomType:String Nom et/ou Type de fichier à chercher dans le nom du fichier ou du dossier (ex: *.php, nom*.php)
	 * @param $mot:String Mot à chercher dans les fichiers
	 * @param $dossier:String Dossier de recherche
	 * @param $dansSousDossier:Boolean Indique si il faut chercher dans les sous-dossiers
	 */
	 function rechercher_dossiers_fichiers($nomType = "", $mot = "", $dossier = "", $dansSousDossier = true) {		
	 	 
	 	// Chemin du dossier dans lequel on éffectue la recherche
		if ($dossier!="")
			$chemin = $this->chemin."/".$dossier;
		else
			$chemin = $this->chemin;
	 
		// Parser le nom /type du fichier 
	 	if (is_string($nomType) && $nomType!="") {
			$listeTmp = split(";", $nomType);
			
			// nomType devient un tableau
			$nomType = array();
			
			for ($i=0;$i<sizeof($listeTmp);$i++) {
				if (ereg("\*", $listeTmp[$i]))
					$nomType[sizeof($nomType)] = "^(".ereg_replace("\*","([^]]+)",str_replace(array("."),array("\."),$listeTmp[$i])).")$";
				else
					$nomType[sizeof($nomType)] = $listeTmp[$i];
			}				
		}

		// Ouverture du répertoire
		$handle = @opendir($chemin);
		
		$indice_fichier = 0;
		$indice_dossier = 0;
		
		// Parcours du répertoire
		while ($fichier = @readdir($handle)){		
			if($fichier!="." && $fichier!="..") {
				if(is_file($chemin."/".$fichier)){
					if (is_array($nomType) && sizeof($nomType)>0) {
						for ($i=0;$i<sizeof($nomType);$i++) {
							if (eregi($nomType[$i], $fichier)) {
								$obj_fichier_tmp = new fichier($chemin, $fichier);	
								if ($mot!="") {
									if (eregi($mot,$obj_fichier_tmp->lire("r")))
										$this->liste_fichiers[sizeof($this->liste_fichiers)] = $obj_fichier_tmp->collecter_infos();
								} else {
									$this->liste_fichiers[sizeof($this->liste_fichiers)] = $obj_fichier_tmp->collecter_infos();
								}
								unset($obj_fichier_tmp);	
								break;
							}
						}
					} else {
						$obj_fichier_tmp = new fichier($chemin, $fichier);	
						if ($mot!="") {
							if (eregi($mot,$obj_fichier_tmp->lire("r")))
								$this->liste_fichiers[sizeof($this->liste_fichiers)] = $obj_fichier_tmp->collecter_infos();
						} else {
							$this->liste_fichiers[sizeof($this->liste_fichiers)] = $obj_fichier_tmp->collecter_infos();
						}
						unset($obj_fichier_tmp);				
					}				
				
				} else if (is_dir($chemin."/".$fichier)) {
					if (is_array($nomType) && sizeof($nomType)>0) {
						for ($i=0;$i<sizeof($nomType);$i++) {
							if (eregi($nomType[$i], $fichier)) {				
								$obj_dossier_tmp = new dossier($chemin."/".$fichier);		
								if ($mot=="") {
									$this->liste_dossiers[sizeof($this->liste_dossiers)] = $obj_dossier_tmp->collecter_infos();					
								}
								unset($obj_dossier_tmp);	
							}
						}
					} else {
						$obj_dossier_tmp = new dossier($chemin."/".$fichier);		
						if ($mot=="") {
							$this->liste_dossiers[sizeof($this->liste_dossiers)] = $obj_dossier_tmp->collecter_infos();		
						}			
						unset($obj_dossier_tmp);						
					}
					
					// Recherche dans les sous-dossiers
					if ($dansSousDossier || $dansSousDossier=="1") {
						if ($dossier=="")
							$this->rechercher_dossiers_fichiers($nomType, $mot, $fichier);
						else
							$this->rechercher_dossiers_fichiers($nomType, $mot, $dossier."/".$fichier);
					}
				}					
			}	
		}		
		
		// Fermeture du pointeur
		@closedir($handle);
		
		$this->lister = true;
	}	
	
	/**universal
	 * Retourne le tableau de tous les fichiers des dossiers et des sous-dossiers
	 * @param typeFichiers:Array Tableau contenant les extensions des fichiers à sélectionner
	 * @return Array Liste des fichiers	 
	 */
	 function retour_liste_tous_fichiers($typeFichiers = array()) {
	 	// Tableau contenant les fichiers
	 	$liste = array();
	 	
		$indice_fichier = 0;
			
		// Ouverture du répertoire
		$handle = @opendir($this->chemin);
				
		// Parcours du répertoire
		while ($fichier = @readdir($handle)) {		
			if($fichier!="." && $fichier!="..") {
				if(is_file($this->chemin."/".$fichier)) {
					if (is_array($typeFichiers) && sizeof($typeFichiers)>0) {
						for ($i=0;$i<sizeof($typeFichiers);$i++) {
							if (eregi("\.".strtolower($typeFichiers[$i])."$", $fichier)) {
								$liste[$indice_fichier]["chemin"] = $this->chemin."/".$fichier;
								$indice_fichier++;
								break;
							}
						}
					} else {
						$liste[$indice_fichier]["chemin"] = $this->chemin."/".$fichier;
						$indice_fichier++;
					}

				} else if (is_dir($this->chemin."/".$fichier)) {
					$obj_dossier_tmp = new dossier($this->chemin."/".$fichier);		
					$listeTmp = $obj_dossier_tmp->retour_liste_tous_fichiers($typeFichiers);
					for ($i=0;$i<sizeof($listeTmp);$i++) {
						$liste[$indice_fichier]["chemin"] = $listeTmp[$i]["chemin"];
						$indice_fichier++;
					}
					unset($obj_dossier_tmp);		
				}					
			}	
		}	
		
		// Fermeture du pointeur
		@closedir($handle);
		
		// Trier le tableau par rapport au chemin
		$liste = $this->retour_liste_fichiers("chemin","",$liste);
		
		return $liste;
	}	
	
	/**universal
	 * Retourne le tableau de tous les dossiers et les sous-dossiers
	 * @return Array Liste des fichiers	 
	 */
	 function retour_liste_tous_dossiers() {
	 	// Tableau contenant les dossiers
		$liste = array();
	 	
		$indice_dossier = 0;
			
		// Ouverture du répertoire
		$handle = @opendir($this->chemin);
			
		// Parcours du répertoire
		while ($dossier = @readdir($handle)){		
			if($dossier!="." && $dossier!="..") {
				if (is_dir($this->chemin."/".$dossier)) {				
					$liste[$indice_dossier]["chemin"] = $this->chemin."/".$dossier;
					$indice_dossier++;
					// Sélection des sous-dossiers
					$obj_dossier_tmp = new dossier($this->chemin."/".$dossier);
					$listeTmp = $obj_dossier_tmp->retour_liste_tous_dossiers();
					for ($i=0;$i<sizeof($listeTmp);$i++) {
						$liste[$indice_dossier]["chemin"] = $listeTmp[$i]["chemin"];
						$indice_dossier++;
					}					
					unset($obj_dossier_tmp);					
				}					
			}	
		}				
		
		// Fermeture du pointeur
		@closedir($handle);

		// Trier le tableau par rapport au chemin
		$liste = $this->retour_liste_dossiers("chemin","",$liste);
		
		return $liste;
	}	
	
	/**universal
	 * Retourne le tableau des fichiers du dossier trié par $tri
	 * @param $tri:String Nom du tri du tableau
	 * @param $ordre:String Ordre de tri du tableau	 	
	 * @param $listeFichiers:Array Liste des fichiers 
	 * @return Array Tableau des fichiers	 
	 */
	function retour_liste_fichiers($tri = "nom", $ordre = "", $listeFichiers = null) {		
		
		if (!isset($listeFichiers))
			$listeFichiers = $this->liste_fichiers;
		
		if ($tri=="nom" || $tri=="taille" || $tri=="atime" || $tri=="mtime" || $tri=="ctime" || $tri=="type" || $tri=="permissions" || $tri=="gid" || $tri=="uid" || $tri=="extension" || $tri=="inode" || $tri=="chemin") {					
			if ($ordre=="desc") {
				$fonction = "tri_".$tri."_desc";
			} else {
				$fonction = "tri_".$tri;
			}
			
			if (method_exists($this, $fonction))
				usort($listeFichiers, array($this, $fonction));
		}	
		
		return $listeFichiers;
	}			

	/**universal
	 * Retourne le tableau des dossiers du dossier trié par $tri
	 * @param $tri:String Nom du tri du tableau
	 * @param $ordre:String Ordre de tri du tableau	 
	 * @param $listeDossiers:Array Liste des dossiers	 
	 * @return Array Tableau des dossiers	 
	 */
	function retour_liste_dossiers($tri = "nom", $ordre = "", $listeDossiers = null) {

		if (!isset($listeDossiers))
			$listeDossiers = $this->liste_dossiers;
		
		if ($tri=="nom" || $tri=="taille" || $tri=="atime" || $tri=="mtime" || $tri=="ctime" || $tri=="type" || $tri=="permissions" || $tri=="gid" || $tri=="uid" || $tri=="extension" || $tri=="chemin") {			
			if ($ordre=="desc") {
				$fonction = "tri_".$tri."_desc";
			} else {
				$fonction = "tri_".$tri;
			}

			if (method_exists($this, $fonction))
				usort($listeDossiers, array($this, $fonction));
		}	
		
		return $listeDossiers;	
	}	

	/**universal
	 * Tri sur le nom
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt1 est inférieur à $elt2, >0 si $elt2 est inférieur à $elt1, et 0 si egaux	 
	 */	
	function tri_nom($elt1,$elt2) {
		return strcmp(strtolower($elt1["nom"]),strtolower($elt2["nom"]));
	}
	
	/**universal
	 * Tri sur le nom décroissant
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt2 est inférieur à $elt1, >0 si $elt1 est inférieur à $elt2, et 0 si egaux
	 */	
	function tri_nom_desc($elt1,$elt2) {
		return strcmp(strtolower($elt2["nom"]),strtolower($elt1["nom"]));
	}	

	/**universal
	 * Tri sur le type
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt1 est inférieur à $elt2, >0 si $elt2 est inférieur à $elt1, et 0 si egaux	 
	 */	
	function tri_type($elt1,$elt2) {
		return strcmp(strtolower($elt1["type"]),strtolower($elt2["type"]));
	}
	
	/**universal
	 * Tri sur le type décroissant
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt2 est inférieur à $elt1, >0 si $elt1 est inférieur à $elt2, et 0 si egaux
	 */	
	function tri_type_desc($elt1,$elt2) {
		return strcmp(strtolower($elt2["type"]),strtolower($elt1["type"]));
	}
	
	/**universal
	 * Tri sur la taille
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt1 est inférieur à $elt2, >0 si $elt2 est inférieur à $elt1, et 0 si egaux	 
	 */	
	function tri_taille($elt1,$elt2) {
		return $elt1["taille"]>$elt2["taille"];
	}
	
	/**universal
	 * Tri sur la taille décroissante
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt2 est inférieur à $elt1, >0 si $elt1 est inférieur à $elt2, et 0 si egaux
	 */	
	function tri_taille_desc($elt1,$elt2) {
		return $elt2["taille"]>$elt1["taille"];
	}

	/**universal
	 * Tri sur la atime
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt1 est inférieur à $elt2, >0 si $elt2 est inférieur à $elt1, et 0 si egaux	 
	 */	
	function tri_atime($elt1,$elt2) {
		return $elt1["atime"]>$elt2["atime"];
	}
	
	/**universal
	 * Tri sur la atime décroissante
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt2 est inférieur à $elt1, >0 si $elt1 est inférieur à $elt2, et 0 si egaux
	 */	
	function tri_atime_desc($elt1,$elt2) {
		return $elt2["atime"]>$elt1["atime"];
	}
	
	/**universal
	 * Tri sur la mtime
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt1 est inférieur à $elt2, >0 si $elt2 est inférieur à $elt1, et 0 si egaux	 
	 */	
	function tri_mtime($elt1,$elt2) {
		return $elt1["mtime"]>$elt2["mtime"];
	}
	
	/**universal
	 * Tri sur la mtime décroissante
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt2 est inférieur à $elt1, >0 si $elt1 est inférieur à $elt2, et 0 si egaux
	 */	
	function tri_mtime_desc($elt1,$elt2) {
		return $elt2["mtime"]>$elt1["mtime"];
	}	
	
	/**universal
	 * Tri sur la ctime
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt1 est inférieur à $elt2, >0 si $elt2 est inférieur à $elt1, et 0 si egaux	 
	 */	
	function tri_ctime($elt1,$elt2) {
		return $elt1["ctime"]>$elt2["ctime"];
	}
	
	/**universal
	 * Tri sur la ctime décroissante
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt2 est inférieur à $elt1, >0 si $elt1 est inférieur à $elt2, et 0 si egaux
	 */	
	function tri_ctime_desc($elt1,$elt2) {
		return $elt2["ctime"]>$elt1["ctime"];
	}	
	
	/**universal
	 * Tri sur les permissions
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt1 est inférieur à $elt2, >0 si $elt2 est inférieur à $elt1, et 0 si egaux	 
	 */	
	function tri_permissions($elt1,$elt2) {
		return $elt1["permissions"]>$elt2["permissions"];
	}
	
	/**universal
	 * Tri sur les permissions décroissantes
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt2 est inférieur à $elt1, >0 si $elt1 est inférieur à $elt2, et 0 si egaux
	 */	
	function tri_permissions_desc($elt1,$elt2) {
		return $elt2["permissions"]>$elt1["permissions"];
	}
	
	/**universal
	 * Tri sur le gid
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt1 est inférieur à $elt2, >0 si $elt2 est inférieur à $elt1, et 0 si egaux	 
	 */	
	function tri_gid($elt1,$elt2) {
		return $elt1["gid"]>$elt2["gid"];
	}
	
	/**universal
	 * Tri sur le gid décroissant
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt2 est inférieur à $elt1, >0 si $elt1 est inférieur à $elt2, et 0 si egaux
	 */	
	function tri_gid_desc($elt1,$elt2) {
		return $elt2["gid"]>$elt1["gid"];
	}
	
	/**universal
	 * Tri sur le uid
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt1 est inférieur à $elt2, >0 si $elt2 est inférieur à $elt1, et 0 si egaux	 
	 */	
	function tri_uid($elt1,$elt2) {
		return $elt1["uid"]>$elt2["uid"];
	}
	
	/**universal
	 * Tri sur le gid décroissant
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt2 est inférieur à $elt1, >0 si $elt1 est inférieur à $elt2, et 0 si egaux
	 */	
	function   tri_uid_desc($elt1,$elt2) {
		return $elt2["uid"]>$elt1["uid"];
	}	
	
	/**universal
	 * Tri sur l'extension
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt1 est inférieur à $elt2, >0 si $elt2 est inférieur à $elt1, et 0 si egaux	 
	 */	
	function tri_extension($elt1,$elt2) {
		return strcmp(strtolower($elt1["extension"]),strtolower($elt2["extension"]));
	}
	
	/**universal
	 * Tri sur l'extension décroissante
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt2 est inférieur à $elt1, >0 si $elt1 est inférieur à $elt2, et 0 si egaux
	 */	
	function tri_extension_desc($elt1,$elt2) {
		return strcmp(strtolower($elt2["extension"]),strtolower($elt1["extension"]));
	}	
	
	/**universal
	 * Tri sur l'inode
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt1 est inférieur à $elt2, >0 si $elt2 est inférieur à $elt1, et 0 si egaux	 
	 */	
	function tri_inode($elt1,$elt2) {
		return $elt1["inode"]>$elt2["inode"];
	}
	
	/**universal
	 * Tri sur l'inode décroissante
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt2 est inférieur à $elt1, >0 si $elt1 est inférieur à $elt2, et 0 si egaux
	 */	
	function tri_inode_desc($elt1,$elt2) {
		return $elt2["inode"]>$elt1["inode"];
	}		
	
	/**universal
	 * Tri sur le chemin
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt1 est inférieur à $elt2, >0 si $elt2 est inférieur à $elt1, et 0 si egaux	 
	 */	
	function tri_chemin($elt1,$elt2) {
		return strcmp(strtolower($elt1["chemin"]),strtolower($elt2["chemin"]));
	}
	
	/**universal
	 * Tri sur le chemin décroissant
	 * @param $elt1:String Element 1 du tableau 
	 * @param $elt2:String Element 2 du tableau 	 
	 * @return Int <0 si $elt2 est inférieur à $elt1, >0 si $elt1 est inférieur à $elt2, et 0 si egaux
	 */	
	function tri_chemin_desc($elt1,$elt2) {
		return strcmp(strtolower($elt2["chemin"]),strtolower($elt1["chemin"]));
	}					

	/**universal
	 * Renommmer le dossier
	 * @param $nouveau:String Nouveau nom 
	 * @return Boolean True si supprimé	 
	 */
	function renommer($nouveau) {
		$nouveau = $this->puger_caracteres_speciaux($nouveau);
		if ($this->existe()) {
			if (!is_dir(dirname($this->chemin)."/".$nouveau)) {
				if (@rename($this->retour_chemin(), dirname($this->chemin)."/".$nouveau)) {
					$this->chemin = dirname($this->chemin)."/".$nouveau;
					return true;
				} else {
					return false;
				}
			}
		}	
	}	
	
	/**universal
	 * Copier le dossier
	 * @param $nouveau_chemin:String Nouveau chemin 
	 * @param $nouveau_nom:String Nouveau nom 
	 * @return Boolean True si copié	 
	 */
	function copier($nouveau_chemin, $nouveau_nom) {
		// Boolean pour savoir si ça c'est bien passé
		$ok = false;		
	
		if ($this->existe() && !is_dir($nouveau_chemin."/".$nouveau_nom))
		{
			// Création du dossier distant
			$dossier_new = new dossier($nouveau_chemin."/".$nouveau_nom, true);

			// Ouverture du répertoire
			$handle = @opendir($this->chemin);	
			
			// Copie de tous les fichiers et dossiers du répertoire
			while ($fic = @readdir($handle)){		
				if($fic!="." && $fic!="..") {
					if(is_file($this->chemin."/".$fic)) {
						// Copie du fichier
						$fichier_tmp = new fichier($this->chemin, $fic);					
						$fichier_tmp->copier($nouveau_chemin."/".$nouveau_nom, $fic);
					} else if (is_dir($this->chemin."/".$fic)) {
						// Copie du dossier
						$dossier_tmp = new dossier($this->chemin."/".$fic);
						$dossier_tmp->copier($nouveau_chemin."/".$nouveau_nom, $fic);	
					}
				}	
			}	
		    	
		    // Fermetuire du pointeur
		    @closedir($handle);
			
			// Suppression du dossier vide
			@rmdir($this->chemin);
						
			$ok = true;
		}
		
		return $ok;
	}			
	
	/**universal
	 * Créer le dossier
	 * @param $droits:String Droits du dossier
	 * @return Boolean True si créé	 
	 */
	function creer($droits = 0777) {
		if(!$this->existe()) {
			if (mkdir($this->retour_chemin(), $droits)) {
				chmod($this->retour_chemin(), $droits);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}	
	
	/**universal
	 * Supprimer un dossier recursivement 
	 * @return Boolean True si supprimé	 
	 */
	function supprimer() {
		// Boolean pour savoir si ça c'est bien passé
		$ok = false;	
	
		if ($this->existe()) {			
			// Ouverture du répertoire
			$handle = @opendir($this->chemin);	
			
			// Suppression de tous les fichiers et dossiers du répertoire
			while ($fic = @readdir($handle)){		
				if($fic!="." && $fic!="..") {
					if(is_file($this->chemin."/".$fic)) {
						// Suppression du fichier
						$fichier_tmp = new fichier($this->chemin, $fic);					
						$fichier_tmp->supprimer();
					} else if (is_dir($this->chemin."/".$fic)) {
						// Suppression du dossier
						$dossier_tmp = new dossier($this->chemin."/".$fic);
						$dossier_tmp->supprimer();	
					}
				}	
			}	
		    	
		    // Fermetuire du pointeur
		    @closedir($handle);
			
			// Suppression du dossier vide
			@rmdir($this->chemin);
						
			$ok = true;
		}
		
		return $ok;
	}	
	
	/**universal
	 * Télécharger le contenu du dossier compressé dans une archive (zip, tar, tgz)
	 * @param $rep_tmp:String Répertoire où va être crée l'archive temporairement
	 * @param $format:String Format de l'archive (zip, tar, tgz)
	 * @param $die:Boolean Après le téléchargement faut-il arrêter 	
	 */
	function telecharger($rep_tmp, $format, $die = true) {
		// Boolean pour savoir si ça c'est bien passé
		$ok = false;	
	
		// Vérification du format
		if ($format=="zip" || $format=="tar" || $format=="tgz") {
								
			// Création du répertoire tmp si il n'existe pas
			if (!is_dir($rep_tmp)) {
				mkdir($rep_tmp, 0777);
				chmod($rep_tmp, 0777);
			}		
	
			if ($format=="zip") {
	
				// Inclure la librairie de compression
				include("pclzip.lib.php");
		
				// Nom de l'archive temporaire
				$archive_tmp = ".".rand(0,999999)."archiveTmp.zip";
		
				// Nom de l'archive à renvoyer
				$archive = $this->retour_nom().".zip";
				
				// Création de l'objet Zip
				$obj_zip_tmp = new PclZip($rep_tmp."/".$archive_tmp);
				$obj_zip_tmp->create("", "", $this->retour_chemin());
				if ($obj_zip_tmp->add($this->retour_liste_tous_fichiers(), "", $this->retour_chemin()))
					$ok = true;
				else
					die($obj_zip_tmp->errorInfo());
						
				// Destruction des objets temporaires
				unset($obj_zip_tmp);
			
			} else if ($format=="tgz" || $format=="tar") {
			
				// Inclure la librairie de compression
				include("pcltar.lib.php");		
				
				if ($format=="tgz") {					
					// Nom de l'archive temporaire
					$archive_tmp = ".".rand(0,999999)."archiveTmp.tar.gz";
					
					// Nom de l'archive à renvoyer
					$archive = $this->retour_nom().".tar.gz";
					
				} else {					
					// Nom de l'archive temporaire
					$archive_tmp = "archivetmp.tar";
					
					// Nom de l'archive à renvoyer
					$archive = $this->retour_nom().".tar";					
				}
				
				// Création de l'archive
				if (PclTarCreate($rep_tmp."/".$archive_tmp, $this->retour_liste_tous_fichiers(),$format, "", $this->retour_chemin()))
					$ok = true;
				else
					die(PclErrorString(PclTarCreate($rep_tmp."/".$archive_tmp, $this->retour_liste_tous_fichiers(),$format, "", $this->retour_chemin())));						
			}	
			
			// Envoie des en-têtes
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");	
			header("Content-Length: ".filesize($rep_tmp."/".$archive_tmp));
			header("Content-Disposition: attachment; filename=".$archive);
			
			$obj_fichier_tmp = new fichier($rep_tmp, $archive_tmp);

			// Lit le fichier et l'affiche sur la sortie standard
			echo $obj_fichier_tmp->lire("rb");
				
			// Suppression du fichier
			$obj_fichier_tmp->supprimer();				
			
			// Destruction des objets temporaires
			unset($obj_fichier_tmp);			
			unset($archive_tmp);				
			unset($archive);	
			
			// Fin de l'envoie
			die();	
		} 
		
		return $ok;
	}
	
	/**universal
	 * Déplacer le dossier dans un autre répertoire	 
	 * @param $chemin:String Nouveau dossier où sera le fichier
	 */
	function deplacer($dossier) {
		if($this->existe() && isset($dossier) && $dossier!="") {
			$dossierTmp = new dossier($dossier);
			if ($dossierTmp->existe()) {
				// Copier le fichier dans le nouveau répertoire
				if ($this->copier($dossier, basename($this->chemin))) {
					// Supprimer le fichier
					$this->supprimer();
					
					return true;
				}
			}
			unset($dossierTmp);
		}
		 	
		return false;
	}		
}
?>