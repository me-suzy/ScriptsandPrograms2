<?

/**universal

 * Cette classe permet de travailler sur les fichiers

 *  - collecter les infos du fichier (ex: Taille, Date de modification, Permissions, ...)

 *  - copier le fichier

 *  - créer le fichier

 *  - supprimer le fichier

 *  - renommer le fichier

 *  - vérifier que le fichier existe

 *  - télécharger le fichier

 *  - afficher le fichier par le navigateur

 *  - déplacer le fichier dans un autre répertoire

 * 

 * @author Thomas Pequet

 * @version 1.3

 */



include_once("dossier.php");

 

class fichier {



	/**universal

	 * String: Chemin du fichier

	 */

	var $chemin = "";

	

	/**universal

	 * String: Nom du fichier

	 */

	var $fichier = "";	

	

	/**universal

	 * Array: Infos du fichier

	 */

	var $infos = array();	



	/**universal

	 * Constructeur

	 * @param chemin:String Chemin du fichier

	 * @param fichier:String Nom du fichier	 

	 * @param creer:Boolean Créer le fichier si il n'existe pas lors de la création de l'objet

	 */

	function fichier($chemin, $fichier, $creer = true) {

		// Initaliser la variable chemin

		$this->chemin = str_replace("\\", "/", $chemin);

		// Enlever les caractères spéciaux

		$this->fichier = $this->puger_caracteres_speciaux($fichier);



		// Si le fichier n'existe pas alors on tente de le créer

		if ($creer && !$this->existe() && isset($chemin) && $chemin!="") { 

			$this->creer();		

		}

	}

	

	/**universal

	 * Retourne le chemin 	 

	 * @return String Chemin du fichier	 

	 */

	function retour_chemin() {		

		return $this->chemin."/".$this->fichier;

	}

	

	/**universal

	 * Retourne le nom 	 

	 * @return String Nom du fichier	 

	 */

	function retour_nom() {		

		return $this->fichier;	

	}	

	

	/**universal

	 * Retourne l'extension 	 

	 * @return String Extension du fichier	 

	 */

	function retour_extension() {		

		if (ereg("\.", $this->fichier)) {

			if (isset($this->infos["extension"]))

				return strtolower($this->infos["extension"]);		

			else

				return strtolower(eregi_replace("\.", "", substr($this->fichier, strrpos($this->fichier, "."))));

		} else {

			return "???";

		}

	}	

	

	/**universal

	 * Retourne le type 	 

	 * @return String Type du fichier	 

	 */

	function retour_type() {		

		return $this->infos["type"];

	}	

	

	/**universal

	 * Retourne la taille en octet	 

	 * @return Int Taille du fichier	 

	 */

	function retour_taille() {		

		if (isset($this->infos["taille"]))

			return $this->infos["taille"];		

		else

			return filesize($this->retour_chemin());	

	}				

	

	/**universal

	 * Indique si le fichier existe

	 * @return Boolean qui indique si le fichier existe

	 */		

	function existe() {

		return is_file($this->retour_chemin());

	}		

	

	/**universal

	 * Purger le nom du fichier de ses caractères spéciaux

	 * @param $chaine:String Chaine à purger

	 * @return Chaine purgée

	 */

	function puger_caracteres_speciaux($chaine) {	

		return str_replace(array(":","*","?","\"","<",">","|"), array("","","","","","",""), $chaine);

	}		

	

	/**universal

	 * Indique si le fichier est une archive

	 * @return Boolean qui indique si le fichier est une archive 	 

	 */

	function is_archive() {

		if (($this->retour_extension()=="gz" && eregi("\.tar\.gz$",$this->fichier)) || $this->retour_extension()=="tar" || $this->retour_extension()=="tgz" || $this->retour_extension()=="zip")

			return true;

		else

			return false;

	}			

	

	/**universal

	 * Collecter les infos du fichier

	 * @return Array Infos du fichier	 

	 */

	function collecter_infos() {

		$this->infos["nom"] = $this->fichier;

		$this->infos["chemin"] = $this->chemin;

				

		if (eregi("\.jpeg$", $this->fichier) || eregi("\.jpg$", $this->fichier)) {

			$this->infos["extension"] = "jpg";

			$this->infos["type"] = "Image JPEG";

		} else if (eregi("\.gif$", $this->fichier)) {

			$this->infos["extension"] = "gif";

			$this->infos["type"] = "Image GIF";

		} else if (eregi("\.png$", $this->fichier)) {

			$this->infos["extension"] = "png";	

			$this->infos["type"] = "Image PNG";

		} else if (eregi("\.bmp$", $this->fichier)) {

			$this->infos["extension"] = "bmp";	

			$this->infos["type"] = "Image Bitmap";

		} else if (eregi("\.tif$", $this->fichier)) {

			$this->infos["extension"] = "tif";	

			$this->infos["type"] = "Document image TIF";

		} else if (eregi("\.css$", $this->fichier)) {

			$this->infos["extension"] = "css";	

			$this->infos["type"] = "Document Feuille de style";

		} else if (eregi("\.js$", $this->fichier)) {

			$this->infos["extension"] = "js";	

			$this->infos["type"] = "Fichier JavaScript";

		} else if (eregi("\.htm$", $this->fichier) || eregi("\.html$", $this->fichier) || eregi("\.dhtml$", $this->fichier) || eregi("\.shtml$", $this->fichier)) {

			$this->infos["extension"] = "html";	

			$this->infos["type"] = "Document HTML";

		} else if (eregi("\.php$", $this->fichier) || eregi("\.php2$", $this->fichier) || eregi("\.php3$", $this->fichier) || eregi("\.php4$", $this->fichier) || eregi("\.phps$", $this->fichier)) {

			$this->infos["extension"] = "php";	

			$this->infos["type"] = "Fichier PHP";

		} else if (eregi("\.inc$", $this->fichier)) {

			$this->infos["extension"] = "inc";	

			$this->infos["type"] = "Fichier Include";			

		} else if (eregi("\.asp$", $this->fichier) || eregi("\.asa$", $this->fichier)) {

			$this->infos["extension"] = "asp";	

			$this->infos["type"] = "Fichier ASP";

		} else if (eregi("\.jsp$", $this->fichier)) {

			$this->infos["extension"] = "jsp";	

			$this->infos["type"] = "Fichier JSP";

		} else if (eregi("\.java$", $this->fichier)) {

			$this->infos["extension"] = "java";	

			$this->infos["type"] = "Fichier JAVA";		

		} else if (eregi("\.class$", $this->fichier)) {

			$this->infos["extension"] = "class";	

			$this->infos["type"] = "Fichier CLASS";			

		} else if (eregi("\.jar", $this->fichier)) {

			$this->infos["extension"] = "jar";	

			$this->infos["type"] = "Archive JAR";				

		} else if (eregi("\.pl$", $this->fichier)) {

			$this->infos["extension"] = "pl";	

			$this->infos["type"] = "Fichier PERL";			

		} else if (eregi("\.txt$", $this->fichier)) {

			$this->infos["extension"] = "txt";	

			$this->infos["type"] = "Document texte";

		} else if (eregi("\.hlp$", $this->fichier)) {

			$this->infos["extension"] = "hlp";	

			$this->infos["type"] = "Fichier d'aide";

		} else if (eregi("\.chm$", $this->fichier)) {

			$this->infos["extension"] = "chm";	

			$this->infos["type"] = "Fichier HTML compilé";

		} else if (eregi("\.bat$", $this->fichier)) {

			$this->infos["extension"] = "bat";	

			$this->infos["type"] = "Fichier de comande MS-DOS";

		} else if (eregi("\.exe$", $this->fichier)) {

			$this->infos["extension"] = "exe";	

			$this->infos["type"] = "Application";

		} else if (eregi("\.rar$", $this->fichier)) {

			$this->infos["extension"] = "rar";	

			$this->infos["type"] = "Archive RAR";

		} else if (eregi("\.zip$", $this->fichier)) {

			$this->infos["extension"] = "zip";	

			$this->infos["type"] = "Archive ZIP";

		} else if (eregi("\.tar\.gz$", $this->fichier)) {

			$this->infos["extension"] = "tgz";	

			$this->infos["type"] = "Archive TGZ";

		} else if (eregi("\.gz$", $this->fichier)) {

			$this->infos["extension"] = "gz";	

			$this->infos["type"] = "Archive GZIP";

		} else if (eregi("\.tgz$", $this->fichier)) {

			$this->infos["extension"] = "tgz";	

			$this->infos["type"] = "Archive TGZ";

		} else if (eregi("\.tar$", $this->fichier)) {

			$this->infos["extension"] = "tar";	

			$this->infos["type"] = "Archive TAR";

		} else if (eregi("\.mid$", $this->fichier)) {

			$this->infos["extension"] = "mid";	

			$this->infos["type"] = "Son Midi";

		} else if (eregi("\.mp3$", $this->fichier)) {

			$this->infos["extension"] = "mp3";	

			$this->infos["type"] = "Son Mp3";

		} else if (eregi("\.wav$", $this->fichier)) {

			$this->infos["extension"] = "wav";	

			$this->infos["type"] = "Son Wave";

		} else if (eregi("\.ra$", $this->fichier)) {

			$this->infos["extension"] = "ra";	

			$this->infos["type"] = "RealAudio";			

		} else if (eregi("\.rm$", $this->fichier)) {

			$this->infos["extension"] = "rm";	

			$this->infos["type"] = "RealVidéo";	

		} else if (eregi("\.ram$", $this->fichier)) {

			$this->infos["extension"] = "ram";	

			$this->infos["type"] = "Lien vers fichier RealMedia";

		} else if (eregi("\.avi$", $this->fichier)) {

			$this->infos["extension"] = "avi";	

			$this->infos["type"] = "Clip vidéo";

		} else if (eregi("\.mov$", $this->fichier)) {

			$this->infos["extension"] = "mov";	

			$this->infos["type"] = "Clip vidéo QuickTime";					

		} else if (eregi("\.mpg$", $this->fichier) || eregi("\.mpeg", $this->fichier)) {

			$this->infos["extension"] = "mpg";	

			$this->infos["type"] = "Clip vidéo";										

		} else if (eregi("\.doc$", $this->fichier)) {

			$this->infos["extension"] = "doc";	

			$this->infos["type"] = "Document Microsoft Word";

		} else if (eregi("\.xls$", $this->fichier)) {

			$this->infos["extension"] = "xls";	

			$this->infos["type"] = "Document Microsoft Excel";			

		} else if (eregi("\.csv$", $this->fichier)) {

			$this->infos["extension"] = "xls";	

			$this->infos["type"] = "Document texte séparé par des ';'";			

		} else if (eregi("\.ppt", $this->fichier)) {

			$this->infos["extension"] = "ppt";	

			$this->infos["type"] = "Document Microsoft PowerPoint";																

		} else if (eregi("\.pdf$", $this->fichier)) {

			$this->infos["extension"] = "pdf";	

			$this->infos["type"] = "Document Adobe Acrobat";

		} else if (eregi("\.ps$", $this->fichier)) {

			$this->infos["extension"] = "ps";	

			$this->infos["type"] = "Document PostScript";	

		} else if (eregi("\.fla$", $this->fichier)) {

			$this->infos["extension"] = "fla";	

			$this->infos["type"] = "Flash Movie";	

		} else if (eregi("\.swf$", $this->fichier)) {

			$this->infos["extension"] = "swf";	

			$this->infos["type"] = "Flash Player Movie";															

		} else if (eregi("\.ini$", $this->fichier)) {

			$this->infos["extension"] = "ini";	

			$this->infos["type"] = "Paramètres de configuration";															

		} else if (eregi("\.ico$", $this->fichier)) {

			$this->infos["extension"] = "ico";	

			$this->infos["type"] = "Fichier Icône";

		} else if (eregi("\.xml$", $this->fichier)) {

			$this->infos["extension"] = "xml";	

			$this->infos["type"] = "Document XML";	

		} else if (eregi("\.dtd$", $this->fichier)) {

			$this->infos["extension"] = "dtd";	

			$this->infos["type"] = "Document DTD";	

		} else if (eregi("\.xsl$", $this->fichier)) {

			$this->infos["extension"] = "xsl";	

			$this->infos["type"] = "Feuille de style XSL";														

		} else if (eregi("\.sql$", $this->fichier)) {

			$this->infos["extension"] = "sql";	

			$this->infos["type"] = "Script SQL";	

		} else if (eregi("\.lnk$", $this->fichier)) {

			$this->infos["extension"] = "lnk";	

			$this->infos["type"] = "Raccourci";	

		} else if (eregi("\.url$", $this->fichier)) {

			$this->infos["extension"] = "lnk";	

			$this->infos["type"] = "Lien Internet";	

		} else if (eregi("\.reg$", $this->fichier)) {

			$this->infos["extension"] = "reg";	

			$this->infos["type"] = "Fichier Base de Registre";	

		} else if (eregi("\.log$", $this->fichier)) {

			$this->infos["extension"] = "log";	

			$this->infos["type"] = "Fichier de Logs";	

		} else if (eregi("\.htaccess$", $this->fichier)) {

			$this->infos["extension"] = "htaccess";	

			$this->infos["type"] = "Fichier de Droits d'Accès";	

		} else {		

			if (!ereg("\.", $this->fichier)) {

				$this->infos["extension"] = "???";

				$this->infos["type"] = "Fichier ???";			

			} else {

				$this->infos["extension"] = strtolower(eregi_replace("\.", "", substr($this->fichier, strrpos($this->fichier, "."))));

				$this->infos["type"] = "Fichier ".strtoupper($this->infos["extension"]);

			}			

		}		

		

		if ($this->existe()) { 

			// Effacer les variables en cache

			clearstatcache();		

		

			$this->infos["taille"] = filesize($this->retour_chemin());

			//$this->infos["atime"] = fileatime($this->retour_chemin());

			$this->infos["mtime"] = filemtime($this->retour_chemin());

			//$this->infos["ctime"] = filectime($this->retour_chemin());

			//$this->infos["permissions"] = fileperms($this->retour_chemin());					

			//$this->infos["gid"] = filegroup($this->retour_chemin());					

			//$this->infos["uid"] = fileowner($this->retour_chemin());					

			//$this->infos["inode"] = fileinode($this->retour_chemin());			

		} 		

		

		return $this->infos;	

	}	

	

	/**universal

	 * Renommmer le fichier

	 * @param $nouveau:String Nouveau nom 

	 * @return Boolean True si supprimé	 

	 */

	function renommer($nouveau) {

		if ($this->existe() && $nouveau!=$this->fichier) {

			if (!is_file($this->chemin."/".$nouveau)) {

				if (rename($this->retour_chemin(), $this->chemin."/".$nouveau)) {

					$this->fichier = $nouveau;

					return true;

				} else {

					return false;

				}

			} else {

				die("Impossible de renommer le fichier car un fichier existe déjà avec le nouveau nom.<BR>".$this->retour_chemin()." -> ".$this->chemin."/".$nouveau);

			}

		}

	}	

	

	/**universal

	 * Copier le fichier

	 * @param $nouveau_chemin:String Nouveau chemin 

	 * @param $nouveau_nom:String Nouveau nom 

	 * @return Boolean True si copié	 

	 */

	function copier($nouveau_chemin, $nouveau_nom) {

		if ($this->existe() && !(is_file($nouveau_chemin."/".$nouveau_nom) || is_link($nouveau_chemin."/".$nouveau_nom)))

			return copy($this->retour_chemin(), $nouveau_chemin."/".$nouveau_nom);

		else

			return false;

	}		

	

	/**universal

	 * Créer le fichier

	 * @param $droits:String Droits du fichier

	 * @return Boolean True si créé	 

	 */

	function creer($droits = 0777) {

		if(!$this->existe()) {

			if (touch($this->retour_chemin())) {

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

	 * Supprimer le fichier

	 * @return Boolean True si supprimé	 

	 */

	function supprimer() {

		if($this->existe())

			return unlink($this->retour_chemin());

		else 	

			return false;

	}	

	

	/**universal

	 * Télécharger le fichier

	 * @param $die:Boolean Après le téléchargement faut-il arrêter 	 

	 */

	function telecharger($die = true) {

		if ($this->existe()) { 		

			// Envoie des en-têtes

			header("Content-Type: application/force-download");

			header("Content-Type: application/octet-stream");			

			header("Content-Length: ".filesize($this->retour_chemin()));

			header("Content-Disposition: attachment; filename=".$this->fichier);

			

			// Lit le fichier et l'affiche sur la sortie standard

			echo $this->lire("rb");		

			

			// Arrêt du script

			if ($die)

				die();		

		}

	}	

	

	/**universal

	 * Afficher le fichier dans le navigateur

	 * @param $die:Boolean Après l'affichage faut-il arrêter 	 

	 */

	function afficher($die = true) {

		if ($this->existe()) { 		

			// Envoie des en-têtes

			header("Content-Type: ".$this->get_mime_type($this->retour_extension()));

			header("Content-Length: ".filesize($this->retour_chemin()));				

			header("Content-Disposition: filename=".$this->fichier);

			

			// Lit le fichier et l'affiche

			echo $this->lire("rb");	

			

			// Arrêt du script

			if ($die)

				die();		

		}

	}		

	

	/**universal

	 * Déplacer le fichier dans un autre répertoire	 

	 * @param $chemin:String Nouveau dossier où sera le fichier

	 */

	function deplacer($dossier) {

		if($this->existe() && isset($dossier) && $dossier!="") {

			$dossierTmp = new dossier($dossier);

			if ($dossierTmp->existe()) {

				// Copier le fichier dans le nouveau répertoire

				if ($this->copier($dossier, $this->fichier)) {

					// Supprimer le fichier

					$this->supprimer();

					

					return true;

				}

			}

			unset($dossierTmp);

		}

		 	

		return false;

	}		

	

	/**universal

	 * Ecrire dans le fichier

	 * @param $texte:String Texte à écrire dans le fichier

	 * @param $mode:String Mode d'ouverture du fichier (r, rb, r+, rb+, w, wb, w+, wb+, a, ab, a+, ab+)

	 */

	function ecrire($texte, $mode) {	

		$fp = fopen($this->retour_chemin(), $mode);

		fwrite($fp, $texte, strlen($texte));

		fclose($fp);	

	}

	

	/**universal

	 * Lire le fichier

	 * @param $mode:String Mode d'ouverture du fichier (r, rb)

	 * @return le contenu du fichier

	 */

	function lire($mode = "r") {	

		// Effacer le cache pour filesize

		clearstatcache();

	

		$fp = fopen($this->retour_chemin(), $mode);

		$contenu = fread($fp, filesize($this->retour_chemin()));

		fclose($fp);	

		

		return $contenu;

	}	

	

	/**universal

	 * Titre : Type Mime d'un fichier 

	 * Auteur : Damien Seguy 

	 * Email : damien.seguy@nexen.net

	 * Url : www.nexen.net/

	 * Description : Retourne le type MIME d'un fichier. Cette fonction est très pratique pour générer des emails avec pièces attachées, ou bien avec la fonction header().

	 */

	function get_mime_type($extension) {

		$mimetypes= array(

			"ez" => "application/andrew-inset",

			"hqx" => "application/mac-binhex40",

			"cpt" => "application/mac-compactpro",

			"doc" => "application/msword",

			"bin" => "application/octet-stream",

			"dms" => "application/octet-stream",

			"lha" => "application/octet-stream",

			"lzh" => "application/octet-stream",

			"exe" => "application/octet-stream",

			"class" => "application/octet-stream",

			"so" => "application/octet-stream",

			"dll" => "application/octet-stream",

			"oda" => "application/oda",

			"pdf" => "application/pdf",

			"ai" => "application/postscript",

			"eps" => "application/postscript",

			"ps" => "application/postscript",

			"smi" => "application/smil",

			"smil" => "application/smil",

			"mif" => "application/vnd.mif",

			"xls" => "application/vnd.ms-excel",

			"ppt" => "application/vnd.ms-powerpoint",

			"wbxml" => "application/vnd.wap.wbxml",

			"wmlc" => "application/vnd.wap.wmlc",

			"wmlsc" => "application/vnd.wap.wmlscriptc",

			"bcpio" => "application/x-bcpio",

			"vcd" => "application/x-cdlink",

			"pgn" => "application/x-chess-pgn",

			"cpio" => "application/x-cpio",

			"csh" => "application/x-csh",

			"dcr" => "application/x-director",

			"dir" => "application/x-director",

			"dxr" => "application/x-director",

			"dvi" => "application/x-dvi",

			"spl" => "application/x-futuresplash",

			"gtar" => "application/x-gtar",

			"hdf" => "application/x-hdf",

			"js" => "application/x-javascript",

			"skp" => "application/x-koan",

			"skd" => "application/x-koan",

			"skt" => "application/x-koan",

			"skm" => "application/x-koan",

			"latex" => "application/x-latex",

			"nc" => "application/x-netcdf",

			"cdf" => "application/x-netcdf",

			"sh" => "application/x-sh",

			"shar" => "application/x-shar",

			"swf" => "application/x-shockwave-flash",

			"sit" => "application/x-stuffit",

			"sv4cpio" => "application/x-sv4cpio",

			"sv4crc" => "application/x-sv4crc",

			"tar" => "application/x-tar",

			"tcl" => "application/x-tcl",

			"tex" => "application/x-tex",

			"texinfo" => "application/x-texinfo",

			"texi" => "application/x-texinfo",

			"t" => "application/x-troff",

			"tr" => "application/x-troff",

			"roff" => "application/x-troff",

			"man" => "application/x-troff-man",

			"me" => "application/x-troff-me",

			"ms" => "application/x-troff-ms",

			"ustar" => "application/x-ustar",

			"src" => "application/x-wais-source",

			"xhtml" => "application/xhtml+xml",

			"xht" => "application/xhtml+xml",

			"zip" => "application/zip",

			"au" => "audio/basic",

			"snd" => "audio/basic",

			"mid" => "audio/midi",

			"midi" => "audio/midi",

			"kar" => "audio/midi",

			"mpga" => "audio/mpeg",

			"mp2" => "audio/mpeg",

			"mp3" => "audio/mpeg",

			"aif" => "audio/x-aiff",

			"aiff" => "audio/x-aiff",

			"aifc" => "audio/x-aiff",

			"m3u" => "audio/x-mpegurl",

			"ram" => "audio/x-pn-realaudio",

			"rm" => "audio/x-pn-realaudio",

			"rpm" => "audio/x-pn-realaudio-plugin",

			"ra" => "audio/x-realaudio",

			"wav" => "audio/x-wav",

			"pdb" => "chemical/x-pdb",

			"xyz" => "chemical/x-xyz",

			"bmp" => "image/bmp",

			"gif" => "image/gif",

			"ief" => "image/ief",

			"jpeg" => "image/jpeg",

			"jpg" => "image/jpeg",

			"jpe" => "image/jpeg",

			"png" => "image/png",

			"tiff" => "image/tiff",

			"tif" => "image/tiff",

			"djvu" => "image/vnd.djvu",

			"djv" => "image/vnd.djvu",

			"wbmp" => "image/vnd.wap.wbmp",

			"ras" => "image/x-cmu-raster",

			"pnm" => "image/x-portable-anymap",

			"pbm" => "image/x-portable-bitmap",

			"pgm" => "image/x-portable-graymap",

			"ppm" => "image/x-portable-pixmap",

			"rgb" => "image/x-rgb",

			"xbm" => "image/x-xbitmap",

			"xpm" => "image/x-xpixmap",

			"xwd" => "image/x-xwindowdump",

			"igs" => "model/iges",

			"iges" => "model/iges",

			"msh" => "model/mesh",

			"mesh" => "model/mesh",

			"silo" => "model/mesh",

			"wrl" => "model/vrml",

			"vrml" => "model/vrml",

			"css" => "text/css",

			"html" => "text/html",

			"htm" => "text/html",

			"asc" => "text/plain",

			"txt" => "text/plain",

			"rtx" => "text/richtext",

			"rtf" => "text/rtf",

			"sgml" => "text/sgml",

			"sgm" => "text/sgml",

			"tsv" => "text/tab-separated-values",

			"wml" => "text/vnd.wap.wml",

			"wmls" => "text/vnd.wap.wmlscript",

			"etx" => "text/x-setext",

			"xsl" => "text/xml",

			"xml" => "text/xml",

			"mpeg" => "video/mpeg",

			"mpg" => "video/mpeg",

			"mpe" => "video/mpeg",

			"qt" => "video/quicktime",

			"mov" => "video/quicktime",

			"mxu" => "video/vnd.mpegurl",

			"avi" => "video/x-msvideo",

			"movie" => "video/x-sgi-movie",

			"ice" => "x-conference/x-cooltalk"

		);

	

	 	$extension = strtolower($extension);

	 	

	 	if (!isset($mimetypes[$extension]))

	   		return "application/octet-stream";

	 	else

	 		return $mimetypes[$extension];	 	

	}	

	

	/**universal

	 * Titre : Informations MP3 

	 * Auteur : Marcel 

	 * Email : marcel@gtn.com

	 * Url : hichac.de/mp3/

	 * Description : Lit les informations contenues dans un fichier MP3

	 */

	function mp3info($filename){

	

	    // MH: MPEG Audio Tag ID3v1 stuff 

	    $genre[0]="Blues"; 

	    $genre[1]="Classic Rock"; 

	    $genre[2]="Country"; 

	    $genre[3]="Dance"; 

	    $genre[4]="Disco"; 

	    $genre[5]="Funk"; 

	    $genre[6]="Grunge"; 

	    $genre[7]="Hip-Hop"; 

	    $genre[8]="Jazz"; 

	    $genre[9]="Metal"; 

	    $genre[10]="New Age"; 

	    $genre[11]="Oldies"; 

	    $genre[12]="Other"; 

	    $genre[13]="Pop"; 

	    $genre[14]="R&amp;B"; 

	    $genre[15]="Rap"; 

	    $genre[16]="Reggae"; 

	    $genre[17]="Rock"; 

	    $genre[18]="Techno"; 

	    $genre[19]="Industrial"; 

	    $genre[20]="Alternative"; 

	    $genre[21]="Ska"; 

	    $genre[22]="Death Metal"; 

	    $genre[23]="Pranks"; 

	    $genre[24]="Soundtrack"; 

	    $genre[25]="Euro-Techno"; 

	    $genre[26]="Ambient"; 

	    $genre[27]="Trip-Hop"; 

	    $genre[28]="Vocal"; 

	    $genre[29]="Jazz+Funk"; 

	    $genre[30]="Fusion"; 

	    $genre[31]="Trance"; 

	    $genre[32]="Classical"; 

	    $genre[33]="Instrumental"; 

	    $genre[34]="Acid"; 

	    $genre[35]="House"; 

	    $genre[36]="Game"; 

	    $genre[37]="Sound Clip"; 

	    $genre[38]="Gospel"; 

	    $genre[39]="Noise"; 

	    $genre[40]="AlternRock"; 

	    $genre[41]="Bass"; 

	    $genre[42]="Soul"; 

	    $genre[43]="Punk"; 

	    $genre[44]="Space"; 

	    $genre[45]="Meditative"; 

	    $genre[46]="Instrumental Pop"; 

	    $genre[47]="Instrumental Rock"; 

	    $genre[48]="Ethnic"; 

	    $genre[49]="Gothic"; 

	    $genre[50]="Darkwave"; 

	    $genre[51]="Techno-Industrial"; 

	    $genre[52]="Electronic"; 

	    $genre[53]="Pop-Folk"; 

	    $genre[54]="Eurodance"; 

	    $genre[55]="Dream"; 

	    $genre[56]="Southern Rock"; 

	    $genre[57]="Comedy"; 

	    $genre[58]="Cult"; 

	    $genre[59]="Gangsta"; 

	    $genre[60]="Top 40"; 

	    $genre[61]="Christian Rap"; 

	    $genre[62]="Pop/Funk"; 

	    $genre[63]="Jungle"; 

	    $genre[64]="Native American"; 

	    $genre[65]="Cabaret"; 

	    $genre[66]="New Wave"; 

	    $genre[67]="Psychadelic"; 

	    $genre[68]="Rave"; 

	    $genre[69]="Showtunes"; 

	    $genre[70]="Trailer"; 

	    $genre[71]="Lo-Fi"; 

	    $genre[72]="Tribal"; 

	    $genre[73]="Acid Punk"; 

	    $genre[74]="Acid Jazz"; 

	    $genre[75]="Polka"; 

	    $genre[76]="Retro"; 

	    $genre[77]="Musical"; 

	    $genre[78]="Rock &amp; Roll"; 

	    $genre[79]="Hard Rock"; 

	    # WinAmp expanded the above with the following: 

	    $genre[80]="Folk"; 

	    $genre[81]="Folk-Rock"; 

	    $genre[82]="National Folk"; 

	    $genre[83]="Swing"; 

	    $genre[84]="Fast Fusion"; 

	    $genre[85]="Bebob"; 

	    $genre[86]="Latin"; 

	    $genre[87]="Revival"; 

	    $genre[88]="Celtic"; 

	    $genre[89]="Bluegrass"; 

	    $genre[90]="Avantgarde"; 

	    $genre[91]="Gothic Rock"; 

	    $genre[92]="Progressive Rock"; 

	    $genre[93]="Psychedelic Rock"; 

	    $genre[94]="Symphonic Rock"; 

	    $genre[95]="Slow Rock"; 

	    $genre[96]="Big Band"; 

	    $genre[97]="Chorus"; 

	    $genre[98]="Easy Listening"; 

	    $genre[99]="Acoustic"; 

	    $genre[100]="Humour"; 

	    $genre[101]="Speech"; 

	    $genre[102]="Chanson"; 

	    $genre[103]="Opera"; 

	    $genre[104]="Chamber Music"; 

	    $genre[105]="Sonata"; 

	    $genre[106]="Symphony"; 

	    $genre[107]="Booty Brass"; 

	    $genre[108]="Primus"; 

	    $genre[109]="Porn Groove"; 

	    $genre[110]="Satire"; 

	    $genre[111]="Slow Jam"; 

	    $genre[112]="Club"; 

	    $genre[113]="Tango"; 

	    $genre[114]="Samba"; 

	    $genre[115]="Folklore"; 

	    $genre[116]="Ballad"; 

	    $genre[117]="Poweer Ballad"; 

	    $genre[118]="Rhytmic Soul"; 

	    $genre[119]="Freestyle"; 

	    $genre[120]="Duet"; 

	    $genre[121]="Punk Rock"; 

	    $genre[122]="Drum Solo"; 

	    $genre[123]="A Capela"; 

	    $genre[124]="Euro-House"; 

	    $genre[125]="Dance Hall"; 	

	

	    // Ensure file exists! 

	    if (!$fp = @fopen($filename,"rb")) { 

	        return (1); 

	    } 

	

	    // Checking to make sure I can find Frame Sync 

	    while (!feof($fp)) { 

	            $tmp=fgetc($fp); 

	            if (ord($tmp)==255) { 

	                $tmp=fgetc($fp); 

	                if (substr((decbin(ord($tmp))),0,3)=="111") { 

	                    break; 

	                } 

	            } 

	    } // eo while 

	

	    // If end of file is reached before Frame Sync is found then bail... 

	    if (feof($fp)) { 

	        fclose($fp); 

	        return (2); 

	    } 

	

	    // We have declared all engines go. 

	

	    // Assign filesize 

	    $fred['filesize']=filesize($filename); 

	

	    // Assign all important information to $bitstream variable. 

	    $inf=decbin(ord($tmp)); 

	    $inf=sprintf("%08d",$inf); 

	    $bitstream = $inf; 

	    $tmp=fgetc($fp); 

	    $inf=decbin(ord($tmp)); 

	    $inf=sprintf("%08d",$inf); 

	    $bitstream = $bitstream.$inf; 

	    $tmp=fgetc($fp); 

	    $inf=decbin(ord($tmp)); 

	    $inf=sprintf("%08d",$inf); 

	    $bitstream = $bitstream.$inf; 

	

	    // $bitstream now totals the 3 important bytes of the header of this frame. 

	

	    // Determine Version of Mpeg. 

	    switch (substr($bitstream,3,2)) { 

	            case "00": 

	                $fred['version']="2.5"; 

	                break; 

	            case "01": 

	                $fred['version']="0"; 

	                break; 

	            case "10": 

	                $fred['version']="2"; 

	                break; 

	            case "11": 

	                $fred['version']="1"; 

	                break; 

	    } // eo switch 

	

	    // Determine Layer. 

	    switch (substr($bitstream,5,2)) { 

	            case "00": 

	                $fred['layer']="0"; 

	                break; 

	            case "01": 

	                $fred['layer']="3"; 

	                break; 

	            case "10": 

	                $fred['layer']="2"; 

	                break; 

	            case "11": 

	                $fred['layer']="1"; 

	                break; 

	    } // eo switch 

	

	    // Determine CRC checking enabled / disabled 1==disabled 

	    $fred['crc'] = substr($bitstream,7,1); 

	

	    // Determine Bitrate 

	    // Setting an index variable ... trust me in this 

	    // state tis the only way I can think of doing it... 

	    if (($fred['version']=="1")&($fred['layer']=="1")) { 

	            $index="1"; 

	    } elseif (($fred['version']=="1")&($fred['layer']=="2")) { 

	            $index="2"; 

	    } 

	    elseif ($fred['version']=="1") { 

	            $index="3"; 

	    } 

	    elseif ($fred['layer']=="1") { 

	            $index="4"; 

	    } 

	    else    { 

	            $index="5"; 

	    } 

	

	    switch (substr($bitstream,8,4)) { 

	            case "0000": 

	                $fred['bitrate']="free"; 

	                break; 

	            case "0001": 

	                if (($fred['layer']>1)and($fred['version']>1)) 

	                    { 

	                        $fred['bitrate']="8000"; 

	                    } 

	                else 

	                    { 

	                        $fred['bitrate']="32000"; 

	                    } 

	                break; 

	            case "0010": 

	                switch ($index) 

	                    { 

	                        case "1": 

	                            $fred['bitrate']="64000"; 

	                            break; 

	                        case "2": 

	                            $fred['bitrate']="48000"; 

	                            break; 

	                        case "3": 

	                            $fred['bitrate']="40000"; 

	                            break; 

	                        case "4": 

	                            $fred['bitrate']="48000"; 

	                            break; 

	                        case "5": 

	                            $fred['bitrate']="16000"; 

	                            break; 

	                    } 

	                break; 

	            case "0011": 

	                switch ($index) 

	                    { 

	                        case "1": 

	                            $fred['bitrate']="96000"; 

	                            break; 

	                        case "2": 

	                            $fred['bitrate']="56000"; 

	                            break; 

	                        case "3": 

	                            $fred['bitrate']="48000"; 

	                            break; 

	                        case "4": 

	                            $fred['bitrate']="56000"; 

	                            break; 

	                        case "5": 

	                            $fred['bitrate']="24000"; 

	                            break; 

	                    } 

	                break; 

	            case "0100": 

	                switch ($index) 

	                    { 

	                        case "1": 

	                            $fred['bitrate']="128000"; 

	                            break; 

	                        case "2": 

	                            $fred['bitrate']="64000"; 

	                            break; 

	                        case "3": 

	                            $fred['bitrate']="56000"; 

	                            break; 

	                        case "4": 

	                            $fred['bitrate']="64000"; 

	                            break; 

	                        case "5": 

	                            $fred['bitrate']="32000"; 

	                            break; 

	                    } 

	                break; 

	            case "0101": 

	                switch ($index) 

	                    { 

	                        case "1": 

	                            $fred['bitrate']="160000"; 

	                            break; 

	                        case "2": 

	                            $fred['bitrate']="80000"; 

	                            break; 

	                        case "3": 

	                            $fred['bitrate']="64000"; 

	                            break; 

	                        case "4": 

	                            $fred['bitrate']="80000"; 

	                            break; 

	                        case "5": 

	                            $fred['bitrate']="40000"; 

	                            break; 

	                    } 

	                break; 

	            case "0110": 

	                switch ($index) 

	                    { 

	                        case "1": 

	                            $fred['bitrate']="192000"; 

	                            break; 

	                        case "2": 

	                            $fred['bitrate']="96000"; 

	                            break; 

	                        case "3": 

	                            $fred['bitrate']="80000"; 

	                            break; 

	                        case "4": 

	                            $fred['bitrate']="96000"; 

	                            break; 

	                        case "5": 

	                            $fred['bitrate']="48000"; 

	                            break; 

	                    } 

	                break; 

	            case "0111": 

	                switch ($index) 

	                    { 

	                        case "1": 

	                            $fred['bitrate']="224000"; 

	                            break; 

	                        case "2": 

	                            $fred['bitrate']="112000"; 

	                            break; 

	                        case "3": 

	                            $fred['bitrate']="96000"; 

	                            break; 

	                        case "4": 

	                            $fred['bitrate']="112000"; 

	                            break; 

	                        case "5": 

	                            $fred['bitrate']="56000"; 

	                            break; 

	                    } 

	                break; 

	            case "1000": 

	                switch ($index) 

	                    { 

	                        case "1": 

	                            $fred['bitrate']="256000"; 

	                            break; 

	                        case "2": 

	                            $fred['bitrate']="128000"; 

	                            break; 

	                        case "3": 

	                            $fred['bitrate']="112000"; 

	                            break; 

	                        case "4": 

	                            $fred['bitrate']="128000"; 

	                            break; 

	                        case "5": 

	                            $fred['bitrate']="64000"; 

	                            break; 

	                    } 

	                break; 

	            case "1001": 

	                switch ($index) 

	                    { 

	                        case "1": 

	                            $fred['bitrate']="288000"; 

	                            break; 

	                        case "2": 

	                            $fred['bitrate']="160000"; 

	                            break; 

	                        case "3": 

	                            $fred['bitrate']="128000"; 

	                            break; 

	                        case "4": 

	                            $fred['bitrate']="144000"; 

	                            break; 

	                        case "5": 

	                            $fred['bitrate']="80000"; 

	                            break; 

	                    } 

	                break; 

	            case "1010": 

	                switch ($index) 

	                    { 

	                        case "1": 

	                            $fred['bitrate']="320000"; 

	                            break; 

	                        case "2": 

	                            $fred['bitrate']="192000"; 

	                            break; 

	                        case "3": 

	                            $fred['bitrate']="160000"; 

	                            break; 

	                        case "4": 

	                            $fred['bitrate']="160000"; 

	                            break; 

	                        case "5": 

	                            $fred['bitrate']="96000"; 

	                            break; 

	                    } 

	                break; 

	            case "1011": 

	                switch ($index) 

	                    { 

	                        case "1": 

	                            $fred['bitrate']="352000"; 

	                            break; 

	                        case "2": 

	                            $fred['bitrate']="224000"; 

	                            break; 

	                        case "3": 

	                            $fred['bitrate']="192000"; 

	                            break; 

	                        case "4": 

	                            $fred['bitrate']="176000"; 

	                            break; 

	                        case "5": 

	                            $fred['bitrate']="112000"; 

	                            break; 

	                    } 

	                break; 

	            case "1100": 

	                switch ($index) 

	                    { 

	                        case "1": 

	                            $fred['bitrate']="384000"; 

	                            break; 

	                        case "2": 

	                            $fred['bitrate']="256000"; 

	                            break; 

	                        case "3": 

	                            $fred['bitrate']="224000"; 

	                            break; 

	                        case "4": 

	                            $fred['bitrate']="192000"; 

	                            break; 

	                        case "5": 

	                            $fred['bitrate']="128000"; 

	                            break; 

	                    } 

	                break; 

	            case "1101": 

	                switch ($index) 

	                    { 

	                        case "1": 

	                            $fred['bitrate']="416000"; 

	                            break; 

	                        case "2": 

	                            $fred['bitrate']="320000"; 

	                            break; 

	                        case "3": 

	                            $fred['bitrate']="256000"; 

	                            break; 

	                        case "4": 

	                            $fred['bitrate']="224000"; 

	                            break; 

	                        case "5": 

	                            $fred['bitrate']="144000"; 

	                            break; 

	                    } 

	                break; 

	            case "1110": 

	                switch ($index) 

	                    { 

	                        case "1": 

	                            $fred['bitrate']="448000"; 

	                            break; 

	                        case "2": 

	                            $fred['bitrate']="384000"; 

	                            break; 

	                        case "3": 

	                            $fred['bitrate']="320000"; 

	                            break; 

	                        case "4": 

	                            $fred['bitrate']="256000"; 

	                            break; 

	                        case "5": 

	                            $fred['bitrate']="160000"; 

	                            break; 

	                    } 

	                break; 

	            case "1111": 

	                $fred['bitrate']="bad"; 

	                break; 

	    } // eo switch 

	

	    // Determine Sample Rate 

	    switch ($fred['version']) { 

	            case "1": 

	                switch (substr($bitstream,12,2)) { 

	                        case "00": 

	                            $fred['samplerate']="44100"; 

	                            break; 

	                        case "01": 

	                            $fred['samplerate']="48000"; 

	                            break; 

	                        case "10": 

	                            $fred['samplerate']="32000"; 

	                            break; 

	                        case "11": 

	                            $fred['samplerate']="reserved"; 

	                            break; 

	                } // eo switch 

	                break; 

	            case "2": 

	                switch (substr($bitstream,12,2)) { 

	                        case "00": 

	                            $fred['samplerate']="22050"; 

	                            break; 

	                        case "01": 

	                            $fred['samplerate']="24000"; 

	                            break; 

	                        case "10": 

	                            $fred['samplerate']="16000"; 

	                            break; 

	                        case "11": 

	                            $fred['samplerate']="reserved"; 

	                            break; 

	                } // eo switch 

	                break; 

	            case "2.5": 

	                switch (substr($bitstream,12,2)) { 

	                        case "00": 

	                            $fred['samplerate']="11025"; 

	                            break; 

	                        case "01": 

	                            $fred['samplerate']="12000"; 

	                            break; 

	                        case "10": 

	                            $fred['samplerate']="8000"; 

	                            break; 

	                        case "11": 

	                            $fred['samplerate']="reserved"; 

	                            break; 

	                } // eo switch 

	                break; 

	    } // eo switch 

	

	    // Determine whether padding is set on. 0 == no & 1 == yes 

	    $padding = substr($bitstream,14,1); 

	

	    // Determine the private bit's value. Dont know what for though? 

	    $private = substr($bitstream,15,1); 

	

	    // Determine Channel mode 

	    switch (substr($bitstream,16,2)) { 

	            case "00": 

	                $fred['cmode']="Stereo"; 

	                break; 

	            case "01": 

	                $fred['cmode']="Joint Stereo"; 

	                break; 

	            case "10": 

	                $fred['cmode']="Dual Channel"; 

	                break; 

	            case "11": 

	                $fred['cmode']="Mono"; 

	                break; 

	    } // eo switch 

	         

	    // Determine Copyright 0 == no & 1 == yes 

	    $fred['copyright'] = substr($bitstream,20,1); 

	

	    // Determine Original 0 == Copy & 1 == Original 

	    $fred['original'] = substr($bitstream,21,1); 

	

	    // Determine Emphasis 

	    switch (substr($bitstream,22,2)) { 

	            case "00": 

	                $fred['emphasis']="none"; 

	                break; 

	            case "01": 

	                $fred['emphasis']="50/15 ms"; 

	                break; 

	            case "10": 

	                $fred['emphasis']="reserved"; 

	                break; 

	            case "11": 

	                $fred['emphasis']="CCIT J.17"; 

	                break; 

	    } // eo switch 

	

	    // Determine number of frames. 

	    if ((isset($fred['samplerate'])) and (isset($fred['bitrate']))) { 

	        if ($fred['layer']=="1") { 

	            $fred['frames']=floor($fred['filesize']/(floor(((12*$fred['bitrate'])/($fred['samplerate']+$padding))*4)));     

	        } else { 

	            $fred['frames']=floor($fred['filesize']/(floor((144*$fred['bitrate'])/($fred['samplerate'])))); 

	        } // eo if 

	         

	        // Determine number of seconds in song. 

	        if ($fred['layer']=="1") { 

	            $fred['time']=floor((384/$fred['samplerate'])*$fred['frames']); 

	        } else { 

	            $fred['time']=floor((1152/$fred['samplerate'])*$fred['frames']); 

	        } // eo if 

	    } // eo if 

	

	    // MH: Get MPEG Audio Tag info 

	

	    fseek($fp,$fred['filesize']-128); 

	    $tag=fread($fp,128); 

	    if (substr($tag,0,3) == "TAG") { 

	        $fred['tagtitle']=substr($tag,3,30); 

	        $fred['tagartist']=substr($tag,33,30); 

	        $fred['tagalbum']=substr($tag,63,30); 

	        $fred['tagyear']=substr($tag,93,4); 

	        $fred['tagcomment']=substr($tag,97,30); 

	        $fred['taggenreid']=ord(substr($tag,127,1)); 

	        $fred['taggenrename']= ( $fred['taggenreid'] >= 0 && $fred['taggenreid'] <= 125) ? $genre[$fred['taggenreid']] : "(unkown)"; 

	    } // has audio tag ? 

	

	    fclose($fp); 

	

	    $fred['filename']=$filename; 

	    return($fred); 

	

	}

}

?>