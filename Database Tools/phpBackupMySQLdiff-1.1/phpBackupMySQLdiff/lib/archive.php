<?

/**universal

 * Cette classe permet de gérer les fichiers et les dossiers d'une archive donnée (zip, tar, ou tgz):

 * (cette classe représente à la fois un dossier et un fichier)

 *  - collecter les infos de l'archive (ex: Taille, Date de modification, Permissions, ...) 

 *  - ajouter un fichier

 *  - supprimer un fichier

 *  - extraire un fichier

 *  - avoir la liste de tous les fichiers

 *  - avoir la liste des dossiers et des fichiers par rapport à un dossier donné

 * 

 * @author Thomas Pequet

 * @version 1.2

 */



include_once("dossier.php");

include_once("fichier.php");

 

class archive extends dossier {



	/**universal

	 * String: Nom de l'archive

	 */

	var $archive = "";	

	

	/**universal

	 * String: Type de l'archive

	 */

	var $type = "";	

	

	/**universal

	 * Constructeur

	 * @param chemin:String Chemin de l'archive

	 * @param archive:String Nom de l'archive

	 */

	function archive($chemin, $archive) {

		// Initaliser la variable chemin

		$this->chemin = $chemin;

		// Initaliser la variable archive

		$this->archive = $archive;

		// Initaliser la variable type

		if ($this->retour_extension()=="zip")

			$this->type = "zip";

		else if ($this->retour_extension()=="tar")

			$this->type = "tar";

		else if (($this->retour_extension()=="gz" && eregi("\.tar\.gz$",$this->archive)) || $this->retour_extension()=="tgz")

			$this->type = "tgz";		

		else

			die("Ce format d'archive n'est pas supporté. Seul sont supportées les archives au format Zip, Tar, Tgz et Tar.gz");

		

		// Inclure les librairies

		if ($this->type=="tgz" || $this->type=="tar")

			include_once("pcltar.lib.php");

		else if ($this->type=="zip") 

			include_once("pclzip.lib.php");		

			

		// Vérification de l'extistence de l'archive

		if (!$this->existe()) {

			if ($this->type=="tgz" || $this->type=="tar")

				PclTarCreate($this->retour_chemin(), array(), $this->type);

			else if ($this->type=="zip") {

				$obj_zip_tmp = new PclZip($this->retour_chemin());

				$obj_zip_tmp->create(array());

				unset($obj_fichier_tmp);

			}

		}				

	}	

	

	/**universal

	 * Retourne le chemin 	 

	 * @return String Chemin de l'archive	 

	 */

	function retour_chemin() {		

		return $this->chemin."/".$this->archive;

	}	

	

	/**universal

	 * Retourne le nom 	 

	 * @return String Nom de l'archive	 

	 */

	function retour_nom() {		

		return $this->archive;

	}

	

	/**universal

	 * Retourne l'extension de l'archive 

	 * @return String Extension de l'archive	 

	 */

	function retour_extension() {		

		if (isset($this->infos["extension"]))

			return strtolower($this->infos["extension"]);		

		else

			return strtolower(eregi_replace("\.","",substr($this->archive,strrpos($this->archive,"."))));

	}		

	

	/**universal

	 * Retourne le type (zip, tar, ou tgz)

	 * @return String Type de l'archive	 

	 */

	function retour_type() {		

		return $this->type;

	}	

	

	/**universal

	 * Retourne la taille des fichiers de l'archive 	 

	 * @return Int Taille du dossier en octets	 

	 */

	function retour_taille() {		

		$taille = 0;



		// Vérification que l'archive a été listée

		if (!$this->retour_lister())

			$this->lister_collecter_dossiers_fichiers();

		

		for ($i=0; $i<sizeof($this->liste_fichiers); $i++) {

			 $taille = $taille + $this->liste_fichiers[$i]["taille"];

		}

		

		return $taille;

	}	

	

	/**universal

	 * Indique si l'archive existe

	 * @return Boolean qui indique si l'archive existe

	 */		

	function existe() {

		return is_file($this->retour_chemin());

	}		

	

	/**universal

	 * Collecter les infos de l'archive

	 * @return Array Infos de l'archive	 

	 */

	function collecter_infos() {

		$this->infos["nom"] = $this->retour_nom();

		$this->infos["chemin"] = $this->chemin;

		$this->infos["type"] = "Archive";

		

		if ($this->type=="zip")

			$this->infos["extension"] = "zip";

		else if ($this->type=="tar")

			$this->infos["extension"] = "tar";

		else if ($this->type=="tgz")

			$this->infos["extension"] = "tar.gz";

		

		if ($this->existe()) { 

			//$this->infos["taille"] = filesize($this->retour_chemin());

			//$this->infos["atime"] = fileatime($this->retour_chemin());

			//$this->infos["mtime"] = filemtime($this->retour_chemin());

			//$this->infos["ctime"] = filectime($this->retour_chemin());

			//$this->infos["permissions"] = fileperms($this->retour_chemin());					

			//$this->infos["gid"] = filegroup($this->retour_chemin());					

			//$this->infos["uid"] = fileowner($this->retour_chemin());					

			//$this->infos["inode"] = fileinode($this->retour_chemin());			

		} 	

		

		// Effacer les variables en cache

		clearstatcache();

		

		return $this->infos;

	}	

	

	/**universal

	 * Lister les dossiers et les fichiers de l'archive par rapport à un dossier donné

	 * @param dossier:String Dossier à récupérer le contenu 

	 */

	function lister_collecter_dossiers_fichiers($dossier = "") {				

		global $liste_dossier_tmp, $indice_liste_dossier_tmp;

				

		$indice_fichier = 0;

		$indice_dossier = 0;	

		$this->liste_fichiers = array();		

				

		if (!function_exists("ajouter_dans_liste_dossier_tmp")) {

			function ajouter_dans_liste_dossier_tmp($dossier_tmp, $dossier_chemin_tmp) {

				global $liste_dossier_tmp, $indice_liste_dossier_tmp;

				

				$trouve = false;

	

				if ($dossier_tmp==".")

					$dossier_tmp = "";

	

				if ($dossier_chemin_tmp==".")

					$dossier_chemin_tmp = "";

				

				for ($i=0; $i<sizeof($liste_dossier_tmp); $i++) {

					if ($liste_dossier_tmp[$i]["nom"]==$dossier_tmp && $liste_dossier_tmp[$i]["pere"]==$dossier_chemin_tmp) {

						$trouve = true;

						break;	

					}

				}							

				

				if (!$trouve && $dossier_tmp!="") {

					$liste_dossier_tmp[$indice_liste_dossier_tmp]["nom"] = $dossier_tmp;

					$liste_dossier_tmp[$indice_liste_dossier_tmp]["pere"] = $dossier_chemin_tmp;

					if ($dossier_chemin_tmp!="")

						$liste_dossier_tmp[$indice_liste_dossier_tmp]["etage"] = sizeof(split("/",$dossier_chemin_tmp));

					else

						$liste_dossier_tmp[$indice_liste_dossier_tmp]["etage"] = 0;

					$indice_liste_dossier_tmp++;				

					

					$dossier_tmp = basename($dossier_chemin_tmp);

					$dossier_chemin_tmp = dirname($dossier_chemin_tmp);	

					ajouter_dans_liste_dossier_tmp($dossier_tmp, $dossier_chemin_tmp);

					

					return true;

				} else {

					return false;

				}

			}	

		}

	

		// Récupération de la liste des fichiers de l'archive

		$archive_liste = $this->retour_liste_tous_fichiers();			

	

		$liste_dossier_tmp = array();	

		$indice_liste_dossier_tmp = 0;

			

		for ($i=0; $i<sizeof($archive_liste); $i++) {

		

			// Détection pour savoir si c'est un fichier ou un dossier

			if (substr($archive_liste[$i]["filename"],strlen($archive_liste[$i]["filename"])-1,strlen($archive_liste[$i]["filename"]))=="/") { // dossier

			

			} else { // fichier (ex: tutu/toto/titi/toto.txt)

				// Nom du fichier (ex: toto.txt)

				$fichier_tmp = basename($archive_liste[$i]["filename"]);

				// Chemin du fichier dans l'archive (ex: tutu/toto/titi)

				$fichier_chemin_tmp = dirname($archive_liste[$i]["filename"]);

				if ($fichier_chemin_tmp==".")

					$fichier_chemin_tmp = "";

					

				if ($fichier_chemin_tmp==$dossier && $fichier_tmp!="") {

					$this->liste_fichiers[$indice_fichier]["nom"] = $fichier_tmp;

					

					$obj_fichier_tmp = new fichier("", $fichier_tmp);					

					$obj_fichier_tmp->collecter_infos();

					

					$this->liste_fichiers[$indice_fichier]["extension"] = $obj_fichier_tmp->retour_extension();

					$this->liste_fichiers[$indice_fichier]["type"] = $obj_fichier_tmp->retour_type();

					$this->liste_fichiers[$indice_fichier]["taille"] = $archive_liste[$i]["size"];

					//$this->liste_fichiers[$indice_fichier]["atime"] = -1;

					$this->liste_fichiers[$indice_fichier]["mtime"] = $archive_liste[$i]["mtime"];

					//$this->liste_fichiers[$indice_fichier]["ctime"] = -1;

					//$this->liste_fichiers[$indice_fichier]["gid"] = $archive_liste[$i]["gid"];

					//$this->liste_fichiers[$indice_fichier]["uid"] = $archive_liste[$i]["uid"];

					//$this->liste_fichiers[$indice_fichier]["permissions"] = -1;

					//$this->liste_fichiers[$indice_fichier]["inode"] = -1;

					$this->liste_fichiers[$indice_fichier]["index"] = $archive_liste[$i]["index"];

					$indice_fichier++;

					

					unset($obj_fichier_tmp);					

				} 

				

				// Nom du dossier dans lequel est le fichier (ex: titi)

				$dossier_tmp = basename($fichier_chemin_tmp);

				

				// Dossier dans lequel est le dossier (ex: tutu/toto)

				$dossier_chemin_tmp = dirname($fichier_chemin_tmp);			



				// Ajout de ce dossier dans la liste total des dossiers de l'archive

				ajouter_dans_liste_dossier_tmp($dossier_tmp, $dossier_chemin_tmp);		

			}

		}		

		

		// Sélection des dossiers de ce dossier parmi tous les dossier de l'archive

		for ($i=0; $i<sizeof($liste_dossier_tmp); $i++) {

			if ($liste_dossier_tmp[$i]["pere"]==$dossier) {

				$this->liste_dossiers[$indice_dossier]["nom"] = $liste_dossier_tmp[$i]["nom"];

						

				$this->liste_dossiers[$indice_dossier]["extension"] = "";

				$this->liste_dossiers[$indice_dossier]["type"] = "Dossier";

				//$this->liste_dossiers[$indice_dossier]["taille"] = -1;

				//$this->liste_dossiers[$indice_dossier]["atime"] = -1;

				//$this->liste_dossiers[$indice_dossier]["mtime"] = -1;

				//$this->liste_dossiers[$indice_dossier]["ctime"] = -1;

				//$this->liste_dossiers[$indice_dossier]["gid"] = -1;

				//$this->liste_dossiers[$indice_dossier]["uid"] = -1;

				//$this->liste_dossiers[$indice_dossier]["permissions"] = -1;

				//$this->collecte_infos_dossier();

				$indice_dossier++;

			}		

		}	

		

		$this->lister = true;

	}

	

	/**universal

	 * Retourne le tableau de tous les fichiers de l'archive

	 * @return Array Tableau de tous les fichiers de l'archive

	 */

	function retour_liste_tous_fichiers() {		

		

		if ($this->type=="tgz" || $this->type=="tar") {

			// Récupération de la liste des fichier de l'archive

			$archive_liste = PclTarList($this->retour_chemin(), $this->type);			

				

		} else if ($this->type=="zip") {

			// Création de l'objet

			$obj_zip_tmp = new PclZip($this->retour_chemin());

			// Récupération de la liste des fichier de l'archive

			$archive_liste = $obj_zip_tmp->listContent();			

			// Desctruction de l'objet		

			unset($obj_zip_tmp);

		}

		

		return $archive_liste;

	}	

	

	/**universal

	 * Extraire un fichier

	 * @param $rep_tmp:String Répertoire temporaire pour l'extraction 

	 * @param $dossier:String Dossier ou se trouve le fichier dans l'archive

	 * @param $fichier:String Fichier à extraire

	 * @return Boolean qui indique si ça c'est bien passé

	 */	

	function extraire_fichier($rep_tmp, $dossier, $fichier) {

		// Boolean pour savoir si ça c'est bien passé

		$ok = false;	

	

		// Enlever le "/" ou le "\" devant le dossier du fichier

		if (substr($dossier,0,1)=="/" || substr($dossier,0,1)=="\\") 

			 $dossier = substr($dossier,1,strlen($dossier));		

	

		// Chemin du fichier à extraire

		if ($dossier=="")

			$fichier_a_extraire = $fichier;

		else

			$fichier_a_extraire = $dossier."/".$fichier;

		

		if ($this->type=="tgz" || $this->type=="tar") {

			// Extraction du fichier dans le répertoire temporaire

			if (PclTarExtractList($this->retour_chemin(), array($fichier_a_extraire), $rep_tmp, $dossier, $this->type))

				$ok = true;

			else

				die(PclErrorString(PclTarExtractList($this->retour_chemin(), array($fichier_a_extraire), $rep_tmp, $dossier, $this->type)));						

											

		} else if ($this->type=="zip") {

			// Création de l'objet

			$obj_zip_tmp = new PclZip($this->retour_chemin());

			// Index du fichier dans le tableau de fichier

			$index = -1;

			

			if (!$this->retour_lister())

				$this->lister_collecter_dossiers_fichiers($dossier);

			

			// Parcours du tableau de fichiers

			for ($i=0; $i<sizeof($this->liste_fichiers); $i++) {

				if ($this->liste_fichiers[$i]["nom"] == $fichier) {

					$index = $this->liste_fichiers[$i]["index"];

					break;

				}

			}



			if ($index!=-1) {

				// Extraction du fichier dans le répertoire temporaire

				$obj_zip_tmp->extractByIndex($index, $rep_tmp, $dossier);	

				$ok = true;

			} else {

				die($obj_zip_tmp->errorInfo());

			}

						

			// Desctruction de l'objet		

			unset($obj_zip_tmp);

		}		

		

		return $ok;

	}	

	

	/**universal

	 * Supprimer un fichier de l'archive

	 * @param $dossier:String Dossier ou se trouve le fichier dans l'archive

	 * @param $fichier:String Fichier à telecharger 

	 * @return Boolean True si supprimé

	 */	

	function supprimer_fichier($dossier, $fichier) {

		// Boolean pour savoir si ça c'est bien passé

		$ok = false;



		// Enlever le "/" ou le "\" devant le dossier du fichier

		if (substr($dossier,0,1)=="/" || substr($dossier,0,1)=="\\") 

			$dossier = substr($dossier,1,strlen($dossier));			

		

		// Chemin du fichier à effacé

		if ($dossier=="")

			$fichier_a_supprimer = $fichier;

		else

			$fichier_a_supprimer = $dossier."/".$fichier;



		if ($this->type=="tgz" || $this->type=="tar") {

			// Suppression du fichier dans l'archive

			if (PclTarDelete($this->retour_chemin(), array($fichier_a_supprimer), $this->type))

				$ok = true;

			else

				die(PclErrorString(PclTarDelete($this->retour_chemin(), array($fichier_a_supprimer), $this->type)));

							

		} else if ($this->type=="zip") {

			// Création de l'objet

			$obj_zip_tmp = new PclZip($this->retour_chemin());

			// Index du fichier dans le tableau de fichier

			$index = -1;

			

			if (!$this->retour_lister())

				$this->lister_collecter_dossiers_fichiers($dossier);

			

			// Parcours du tableau de fichiers

			for ($i=0; $i<sizeof($this->liste_fichiers); $i++) {

				if ($this->liste_fichiers[$i]["nom"]==$fichier) {

					$index = $this->liste_fichiers[$i]["index"];

					break;

				}

			}



			if ($index!=-1) {

				// Suppression du fichier dans l'archive

				if ($obj_zip_tmp->deleteByIndex($index))

					$ok = true;

				else

					die($obj_zip_tmp->errorInfo());

			}

		

			// Desctruction de l'objet		

			unset($obj_zip_tmp);

		}	

		

		return $ok;	

	}	

	

	/**universal

	 * Ajouter un fichier dans l'archive

	 * @param $rep_tmp:String Répertoire temporaire où le fichier a été uploader

	 * @param $dossier:String Dossier ou va se trouver le fichier dans l'archive

	 * @param $fichier:String Nom du fichier

	 * @param $supprimerFichier:Boolean Supprimer le fichier après l'avoir ajouter à l'archive

	 * @return Boolean True si ajouté 

	 */	

	function ajouter_fichier($rep_tmp, $dossier, $fichier, $supprimerFichier = true) {

		// Boolean pour savoir si ça c'est bien passé

		$ok = false;

	

		// Enlever le "/" ou le "\" devant le dossier du fichier

		if (substr($dossier,0,1)=="/" || substr($dossier,0,1)=="\\") 

			$dossier = substr($dossier,1,strlen($dossier));					

	

		if ($this->type=="tgz" || $this->type=="tar") {

			// Ajout du fichier dans l'archive

			if (PclTarAddList($this->retour_chemin(), array($rep_tmp."/".$fichier), $dossier, $rep_tmp, $this->type))

				$ok = true;

			else

				die(PclErrorString(PclTarAddList($this->retour_chemin(), array($rep_tmp."/".$fichier), $dossier, $rep_tmp, $this->type)));

				

		} else if ($this->type=="zip") {

			// Création de l'objet

			$obj_zip_tmp = new PclZip($this->retour_chemin());

			// Ajout du fichier dans l'archive

			if ($obj_zip_tmp->add(array($rep_tmp."/".$fichier), $dossier, $rep_tmp))							

				$ok = true;

			else

				die($obj_zip_tmp->errorInfo());

			// Desctruction de l'objet		

			unset($obj_zip_tmp);

		}		

		

		// Suppression du fichier

		if ($supprimerFichier) {

			$obj_fichier_tmp = new fichier($rep_tmp, $fichier);

			$obj_fichier_tmp->supprimer();		

			unset($obj_fichier_tmp);

		}

	

		return $ok;

	}					

}

?>