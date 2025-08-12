<?
class Explorer 
{
var $parms=array();
var $mess=array();
var $rep;
var $root; // physical path
var $url_root; // how to get with navigator
var $action_priv=1;
var $auth_mod_topdir=0;
var $filter=array(".","..");
var $file_image_path = "i/";
var $insidebordercolor="#009999";
var $filelinelink="";
var $folderlinelink="";
var $display_size = 1;
var $display_type=1;
var $display_date=1;
	
function Explorer($root,$url_root)
	{
	$this->root = $root;
	$this->url_root = $url_root;
	
	
	$this->parms["head_color"]="efefef";
	$this->parms["line_color1"]="white";
	$this->parms["line_color2"]="white";
	$this->parms["higlight_color2"]="#fee3bc";
	$this->parms["error_message"]="990000";
	$this->parms["img_dir"]="i/ContextMenu/";
	$this->parms["img_sup"]=$this->parms["img_dir"] . "del.gif";
	$this->parms["img_mod"]=$this->parms["img_dir"] . "edit.gif";
	$this->parms["maxchars"] = 30;
	$this->parms["class"]="explorer_entry";
	$this->parms["basedir"]="/modules/thirdparty/explorer/";


$this->mess=array(
"0" => "Dernière version",
"1" => "Fichier",
"2" => "Taille",
"3" => "Type",
"4" => "Modifi&eacute; le",
"5" => "Actions",
"6" => "Renommer",
"7" => "Supprimer",
"8" => "Dossier",
"9" => "Fichier MIDI",
"10" => "Document texte",
"11" => "Javascript",
"12" => "Image GIF",
"13" => "Image JPG",
"14" => "Page HTML",
"15" => "Page HTML",
"16" => "Fichier REAL",
"17" => "Fichier REAL",
"18" => "Script PERL",
"19" => "Fichier ZIP",
"20" => "Son WAV",
"21" => "Script PHP",
"22" => "Script PHP",
"23" => "Fichier",
"24" => "Dossier parent",
"25" => "Transf&eacute;rer un fichier (taille maxi 500ko) dans : ",
"26" => "Cr&eacute;er un nouveau r&eacute;pertoire dans : ",
"27" => "Transf&eacute;rer",
"28" => "Cr&eacute;er un nouveau fichier dans : ",
"29" => "Cr&eacute;er",
"30" => "Saisissez un nom de r&eacute;pertoire et cliquez sur &quot;Cr&eacute;er&quot;",
"31" => "Vous n'avez pas s&eacute;lectionn&eacute; de fichier",
"32" => "Retour",
"33" => "Erreur de transfert !",
"34" => "Le Fichier",
"35" => "a &eacute;t&eacute; enregistr&eacute; dans le r&eacute;pertoire",
"36" => "Sa taille est de",
"37" => "Veuillez saisir un nom de fichier valide",
"38" => "Le dossier",
"39" => "a &eacute;t&eacute; cr&eacute;&eacute; dans le r&eacute;pertoire",
"40" => "Ce dossier existe d&eacute;jà",
"41" => "a &eacute;t&eacute; renomm&eacute; en",
"42" => "en",
"43" => "existe d&eacute;jà",
"44" => "a &eacute;t&eacute; effac&eacute;",
"45" => "r&eacute;pertoire",
"46" => "fichier",
"47" => "Voulez-vous supprimer d&eacute;finitivement le",
"48" => "OUI",
"49" => "NON",
"50" => "Fichier EXE",
"51" => "Editer",
"52" => "Edition du fichier",
"53" => "Enregistrer",
"54" => "Annuler",
"55" => "a &eacute;t&eacute; modifi&eacute;",
"56" => "Image BMP",
"57" => "Image PNG",
"58" => "Fichier CSS",
"59" => "Fichier MP3",
"60" => "Fichier RAR",
"61" => "Fichier GZ",
"62" => "root du site",
"63" => "D&eacute;connexion",
"64" => "Fichier Excel",
"65" => "Fichier Word",
"66" => "Copier",
"67" => "Fichier s&eacute;lectionn&eacute;",
"68" => "Coller dans",
"69" => "Ou choisissez un autre r&eacute;pertoire",
"70" => "D&eacute;placer",
"71" => "Ce fichier existe d&eacute;jà",
"72" => "La root du r&eacute;pertoire est incorrecte. V&eacute;rifier la variable dans le fichier telech.php3",
"73" => "a &eacute;t&eacute; copi&eacute; dans le r&eacute;pertoire",
"74" => "a &eacute;t&eacute; d&eacute;plac&eacute; dans le r&eacute;pertoire",
"75" => "Le fichier users.txt n'est pas dans le r&eacute;pertoire prive",
"76" => "Ce fichier a &eacute;t&eacute; supprim&eacute;",
"77" => "Erreur de création répertoire :",
"visu"=>"Visualiser"
);	
	$this->input_action();
	}
function input_action()
{
global $_POST,$_GET;
$arr_data=array();

/*if ($_POST["a"]!="" || $_POST["rep"]!="")
 	{$arr_data=$_POST;}
if ($_GET["a"]!="" || $_GET["rep"]!="")
	{$arr_data=$_GET;}*/
$arr_data =array_merge($_GET,$_POST);
$this->rep = $arr_data["rep"];

if ($arr_data["a"]!="") $this->action($arr_data);
}
function slash()
	{
	if(ereg("\\\\",$this->root)){$slash="\\";}
	else {$slash="/";}
	return $slash;
	}
	
function listing($nom_rep)
	{
	global $sens,$ordre,$font;
	$handle=opendir($nom_rep);
	while ($fichier = readdir($handle))
		{
		if(!in_array($fichier, $this->filter)) 
			{		
			$liste_nom[$fichier]=$this->mimetype($nom_rep.$this->slash().$fichier,"image");
			$liste_taille[$fichier]=filesize($nom_rep.$this->slash().$fichier);
			$liste_mod[$fichier]=filemtime($nom_rep.$this->slash().$fichier);
			$liste_type[$fichier]=$this->mimetype($nom_rep.$this->slash().$fichier,"type");
			}
		}
	closedir($handle);
	
	if($ordre=="nom")
		{
		if (is_array($liste_nom))
			{if($sens==0){ksort($liste_nom);}else{krsort($liste_nom);}
			}
		$liste=$liste_nom;
		}
	else if($ordre=="taille")
		{
		if($sens==0){asort($liste_taille);}else{arsort($liste_taille);}
		$liste=$liste_taille;
		}
	else if($ordre=="mod")
		{
		if($sens==0){asort($liste_mod);}else{arsort($liste_mod);}
		$liste=$liste_mod;
		}
	else if($ordre=="type")
		{
		if($sens==0){asort($liste_type);}else{arsort($liste_type);}
		$liste=$liste_type;
		}
	else 
		{
		// ORDRE PAR DEFAUT (type)
		if(is_array($liste_type))
			{
			if($sens==0){asort($liste_type);}else{arsort($liste_type);}
			}
		$liste=$liste_type;
		}
	return $liste;	
	}
function mimetype($fichier,$quoi)
	{

	if(is_dir($fichier)){$image="repertoire-ferme.gif";$nom_type=$this->mess[8];}
	else if(eregi("\.mid",$fichier)){$image="mid.gif";$nom_type=$this->mess[9];}
	else if(eregi("\.txt",$fichier)){$image="txt.gif";$nom_type=$this->mess[10];}
	else if(eregi("\.js",$fichier)){$image="js.gif";$nom_type=$this->mess[11];}
	else if(eregi("\.gif",$fichier)){$image="gif.gif";$nom_type=$this->mess[12];}
	else if(eregi("\.jpg",$fichier)){$image="jpg.gif";$nom_type=$this->mess[13];}
	else if(eregi("\.html",$fichier)){$image="html.gif";$nom_type=$this->mess[14];}
	else if(eregi("\.htm",$fichier)){$image="html.gif";$nom_type=$this->mess[15];}
	else if(eregi("\.rar",$fichier)){$image="rar.gif";$nom_type=$this->mess[60];}
	else if(eregi("\.gz",$fichier)){$image="zip.gif";$nom_type=$this->mess[61];}
	else if(eregi("\.ra",$fichier)){$image="ram.gif";$nom_type=$this->mess[16];}
	else if(eregi("\.ram",$fichier)){$image="ram.gif";$nom_type=$this->mess[17];}
	else if(eregi("\.rm",$fichier)){$image="ram.gif";$nom_type=$this->mess[17];}
	else if(eregi("\.pl",$fichier)){$image="pl.gif";$nom_type=$this->mess[18];}
	else if(eregi("\.zip",$fichier)){$image="zip.gif";$nom_type=$this->mess[19];}
	else if(eregi("\.wav",$fichier)){$image="wav.gif";$nom_type=$this->mess[20];}
	else if(eregi("\.php",$fichier)){$image="php.gif";$nom_type=$this->mess[21];}
	else if(eregi("\.php3",$fichier)){$image="php.gif";$nom_type=$this->mess[22];}
	else if(eregi("\.exe",$fichier)){$image="exe.gif";$nom_type=$this->mess[50];}
	else if(eregi("\.bmp",$fichier)){$image="bmp.gif";$nom_type=$this->mess[56];}
	else if(eregi("\.png",$fichier)){$image="gif.gif";$nom_type=$this->mess[57];}
	else if(eregi("\.css",$fichier)){$image="css.gif";$nom_type=$this->mess[58];}
	else if(eregi("\.mp3",$fichier)){$image="mp3.gif";$nom_type=$this->mess[59];}
	else if(eregi("\.xls",$fichier)){$image="xls.gif";$nom_type=$this->mess[64];}
	else if(eregi("\.doc",$fichier)){$image="doc.gif";$nom_type=$this->mess[65];}
	else {$image="defaut.gif";$nom_type=$this->mess[23];}
	if($quoi=="image"){return $image;} else {return $nom_type;}
	}
function fsize($fichier)
	{
	global $size_unit;
	$taille=filesize($fichier);
	if ($taille >= 1073741824) {$taille = round($taille / 1073741824 * 100) / 100 . " G".$size_unit;}
	elseif ($taille >= 1048576) {$taille = round($taille / 1048576 * 100) / 100 . " M".$size_unit;}
	elseif ($taille >= 1024) {$taille = round($taille / 1024 * 100) / 100 . " K".$size_unit;}
	else {$taille = $taille . " ".$size_unit;} 
	if($taille==0 || is_dir($fichier)) {$taille="&nbsp;";}
	return $taille;
	}

function date_modif($fichier)
	{
	$tmp = filemtime($fichier);
	return date("d/m/Y",$tmp);
	}

function init()
	{
	global $sens,$mess,$font;
	if($this->rep==""){$nom_rep=$this->root;}
	if($sens==""){$sens=0;}
	else
		{
		if($sens==1){$sens=0;}else{$sens=1;}	
		}	
	if($this->rep!=""){$this->rep=stripslashes($this->rep);$nom_rep=$this->root.$this->slash().$this->rep;}
	if(!file_exists($this->root)) {echo "<font face=\"$font\" size=\"2\">".$this->mess[72]."</font>\n";exit;}
	if(!is_dir($nom_rep)) {echo "<font face=\"$font\" size=\"2\">".$this->mess[76]."<br><br><a href=\"javascript:window.history.back()\">".$this->mess[32]."</a></font>\n";exit;}
	return $nom_rep;
	}
function deldir($location) 
	{ 
	if(is_dir($location))
		{
		$all=opendir($location); 
		while ($file=readdir($all)) 
			{ 
			if (is_dir($location.$this->slash().$file) && $file <> ".." && $file <> ".") 
				{ 
				$this->deldir($location.$this->slash().$file); 
				if(file_exists($location.$this->slash().$file)){rmdir($location.$this->slash().$file); }
				unset($file); 
				}
			elseif (!is_dir($location.$this->slash().$file))
				{ 
				unlink($location.$this->slash().$file); 
				unset($file); 
				} 
			} 
			
		closedir($all); 
		unset($all); 
		rmdir($location);
		}
	else 
		{
		closedir($all); 
		unset($all); 
		unlink($location);
		}
	}
function toHTML()
	{
	// $rep,$ordre,$sens passes dans l'url
	global $ordre,$sens,$id,$font;
	$str=array();
	echo $this->js();
	if(eregi("\.\.",$this->rep) || $this->rep==".") {$this->rep="";}
	$nom_rep=$this->init();
	$base_nom_rep=str_replace($this->root,"",$nom_rep);
	if($base_nom_rep==""){$base_nom_rep=$this->slash();}
	$this->rep=stripslashes($this->rep);
	
	$titre_root = "Index des documents";
	// AFFICHAGE DU REPERTOIRE COURANT ET root
	//$str[] =  "<table bgcolor=white width=100% cellpadding=0 cellspacing=0 border=0><tr><td><img src=\"i/repertoire-ferme.gif\" width=\"20\" height=\"20\" align=\"ABSMIDDLE\"><font face=\"$font\" size=\"2\"><a href=\"" . $sess->url("telech.php") . "\"><b>$titre_root</b></a> $base_nom_rep</font></td>";
	//$str[] =  "<td align=\"right\"><font face=\"$font\" size=\"2\">";
	//if($users==1) {$str[] =  "<a href=\"telech.php?action=deconnexion&id=$id\">$this->mess[63]</a>";}
	//$str[] =  "</font></td></tr></table><br>";
	
	$str[] =  "<div id=filelist><TABLE width=100% cellpadding=0 cellspacing=0 border=0 style='border:solid 1px $this->insidebordercolor'  bgcolor=white>";
	
	
	// PREMIERE LIGNE DU TABLEAU : Nom du fichier / Taille / Type / Modifié le / Actions
	if($this->rep!="" ){$lien="&rep=".$this->rep;}
	
	$str[] =  "<tr bgcolor=" . $this->parms["head_color"] . " >";
	
		$str[] =  "<td align=center height=20 style='border-bottom:solid black 1px'><b><a href=\"" . $this->get_url(  "&sens=$sens&ordre=nom".$lien ) ."\" class=explorer_entry>" . $this->mess[1] . "</a></b></td>\n";
		if ($this->display_size) $str[] = "<td  style='border-bottom:solid black 1px'><b><a href=\"" .$this->get_url(  "&sens=$sens&ordre=taille".$lien) ."\"  class=explorer_entry>" .$this->mess[2]. "</a></b></td>\n";
		if ($this->display_type) $str[] =  "<td  style='border-bottom:solid black 1px'><b><a href=\"" .$this->get_url(  "&sens=$sens&ordre=type".$lien )."\"  class=explorer_entry>" .$this->mess[3]. "</a></b></td>\n";	
		if ($this->display_date) $str[] =  "<td  style='border-bottom:solid black 1px'><b><a href=\"" .$this->get_url(  "&sens=$sens&ordre=mod".$lien) ."\"  class=explorer_entry>" .$this->mess[4]. "</a></b></td>\n";
		/*if ($this->rep!="")	$str[] =  "<td align=center><b>" .$this->mess[5]. "</b></td>\n";	*/

	
	// LIEN DOSSIER PARENT
	if($this->rep!="")
		{
		$nom=dirname($this->rep);
		$str[] =  "<tr bgcolor=" . $this->parms["head_color"] . "><td nowrap colspan=4><a href=\"" . $this->get_url( $this->rep!=$nom?"&rep=$nom":"");
		$str[] =  "\"  class=explorer_entry><img src=\"" . $this->file_image_path . "parent.gif\" width=\"20\" align=\"absmiddle\" border=\"0\" alt=\"".$this->mess[24]."\"  class=explorer_entry><b>$base_nom_rep</b></font></a></td>";
		//if ( $this->rep!="") $str[] =  "<td>&nbsp;</td></tr>\n";
		}
		
	// LECTURE DU REPERTOIRE ET CLASSEMENT DES FICHIERS
	$liste=$this->listing($nom_rep);
		
	// AFFICHAGE
	$lineno=0;
	if(is_array($liste))
		{
		while (list($fichier,$mime) = each($liste))
		
			{
			// DEFINITION DU LIEN SUR LE FICHIER
			if(is_dir($nom_rep.$this->slash().$fichier))
				{
				$lien= $this->get_url ( "&rep=" . ($this->rep!=""?$this->rep.$this->slash():"") . $fichier);
				$affiche_copier="non";
				}
			else 
				{
				$lien=$this->url_root . "/";
				if($this->rep!=""){$lien.=$this->rep ."/";}
				$lien.="$fichier";
				$affiche_copier="non";
				}
				
			if (!is_dir($nom_rep.$this->slash().$fichier))
				{
				if ($this->filelinelink!="" )
				$lien = str_replace("--", $this->rep . "/" . $fichier, $this->filelinelink);
				}
			else
				{
				if ($this->folderlinelink!=""  )
				$lien = str_replace("--", $lien, $this->filelinelink);
				}
		
			// AFFICHAGE DE LA LIGNE
			if ($color==$this->parms["line_color1"] ) 
				$color= $this->parms["line_color2"] ; 
			else $color=$this->parms["line_color1"];
			
			//NAME OF FILE IN THIS LINE FOR URL
			$this->linefilename =  $this->rep!=""?$this->rep."&fic=".$this->rep.$this->slash().$fichier:"&fic="  .$fichier;
			$lineno++;
			$str[] =  "<tr bgcolor=$color ondblclick='select_line(this,$lineno,\"". str_replace("&fic=","",$this->linefilename) ."\")'>";
			
			$str[] =  "<td nowrap  class=explorer_entry>";
			
			$str[] =  "<a href=\"$lien\"";
			if (!is_dir($nom_rep.$this->slash().$fichier) && $this->filelinelink=="") $str[] =  " target=_blank";
			$str[] =  "><img src=\"" . $this->file_image_path .  $this->mimetype($nom_rep.$this->slash().$fichier,"image")."\" width=\"20\" height=\"20\" align=\"ABSMIDDLE\" border=\"0\"> ";
			$str[] =  "$fichier</a>\n";
			$str[] =  "</td>\n";
			
			
			
			if ($this->display_size)
				{
				$str[] =  "<td class=explorer_entry>";
				$str[] =  $this->fsize($nom_rep.$this->slash().$fichier);
				$str[] =  "</td>";
				}
			if ($this->display_type)
				{
				$str[] =  "<td class=explorer_entry>";
				$str[] =  $this->mimetype($nom_rep.$this->slash().$fichier,"type");
				$str[] =  "</td>";			
				}
			if ($this->display_date)
				{
				$str[] =  "<td class=explorer_entry>";
				$str[] =  $this->date_modif($nom_rep.$this->slash().$fichier);
				$str[] =  "</td>";
				}
			
			$str[] =  "<td nowrap align=center >";
			// IMAGE COPIER
			if($affiche_copier=="oui")
				{
				$str[] =  "<a href=\"" . $this->get_url(  "&action=copier&rep=" . ($rep!=""?"$rep&fic=$rep".$this->slash():"&fic=") );
				$str[] =  "$fichier\"><img src=\"" . $this->file_image_path . "copier.gif\" alt=\"". $this->mess[66]."\" width=\"20\" height=\"20\" border=\"0\"></a>\n";
				}
			

			// IMAGE DEPLACER
			if(!is_dir($this->root.$this->slash().$fichier))
				{
				//$str[] =  "<a href=\"" . $sess->url($PHP_SELF) . "&action=deplacer&rep=";if($rep!=""){$str[] =  "$rep&fic=$rep".slash();}else{$str[] =  "&fic=";}
				//$str[] =  "$fichier\"><img src=\"i/deplacer.gif\" alt=\"$this->mess[70]\" width=\"20\" height=\"20\" border=\"0\"></a>\n";
				 }
		/*
if (($this->rep!="" || $this->auth_mod_topdir) && $this->action_priv)	
{
			// IMAGE RENOMMER
			if (!is_dir($nom_rep.$this->slash().$fichier)) 
				$this_item = "ce fichier";
			else
				$this_item = "ce répertoire";
				
			
			
			$str[] =  "&nbsp;<a href=\"javascript:precise_url('" . $this->get_url(   "&a=rename&rep=" .$this->linefilename);
			$str[] =  "','fic_new','Renommer $this_item','$fichier')\"><img src=\"" . $this->parms["img_mod"] . "\" alt=\"".$this->mess[6]."\"  border=\"0\" align=absmiddle> ". $this->mess[6]."</a>\n";
			
			// IMAGE SUPPRIMER
			$str[] =  "&nbsp;<a href=\"javascript:confirm_url('" . $this->get_url(   "&a=sup&rep=" .($this->linefilename));
			$str[] =  "',' $this_item')\"><img src=\"" . $this->parms["img_sup"] . "\" alt=\"".$this->mess[7]."\" border=\"0\" align=absmiddle> ".$this->mess[7]."</a>\n";
}*/
			// IMAGE EDITER
			//if(eregi("\.txt|\.php|\.php3|\.htm|\.html|\.cgi|\.pl|\.js",$fichier) && !is_dir($this->root.slash().$fichier))
			//	{
			//	$str[] =  "<a href=\"" . $sess->url($PHP_SELF) . "&action=editer&rep=";if($rep!=""){$str[] =  "$rep&fic=$rep".slash();}else{$str[] =  "&fic=";}
			//	$str[] =  "$fichier\"><img src=\"i/editer.gif\" alt=\"$this->mess[51]\" width=\"20\" height=\"20\" border=\"0\"></a>\n";
			//	}
			
			$str[] =  "</td>\n";

			}	
		}
	else $str[] =  "<tr><th colspan=6 bgcolor=" . $this->parms["line_color2"] . " height=60  class=explorer_entry>Ce répertoire est vide</th></tr>";
	$str[] =  "</table></div>" . $this->horizontal_space();		
	return join('',$str);
	}
	
function horizontal_space()
{
return "<table border=0 cellpadding=1 cellspacing=1><tr><td></td></tr></table>";
}
function getfilename($nom)
	{
	$nom=stripslashes($nom);
	$nom=str_replace("'","",$nom);
	$nom=str_replace("\"","",$nom);
	$nom=str_replace("\"","",$nom);
	$nom=str_replace("&","",$nom);
	$nom=str_replace(",","",$nom);
	$nom=str_replace(";","",$nom);
	$nom=str_replace("/","",$nom);
	$nom=str_replace("\\","",$nom);
	$nom=str_replace("`","",$nom);
	$nom=str_replace("<","",$nom);
	$nom=str_replace(">","",$nom);
	$nom=str_replace(" ","_",$nom);
	$nom=str_replace(":","",$nom);
	$nom=str_replace("*","",$nom);
	$nom=str_replace("|","",$nom);
	$nom=str_replace("?","",$nom);
	$nom=str_replace("é","e",$nom);
	$nom=str_replace("è","e",$nom);
	$nom=str_replace("ç","c",$nom);
	$nom=str_replace("@","",$nom);
	$nom=str_replace("â","a",$nom);
	$nom=str_replace("ê","e",$nom);
	$nom=str_replace("î","i",$nom);
	$nom=str_replace("ô","o",$nom);
	$nom=str_replace("û","u",$nom);
	$nom=str_replace("ù","u",$nom);
	$nom=str_replace("à","a",$nom);
	$nom=str_replace("!","",$nom);
	$nom=str_replace("§","",$nom);
	$nom=str_replace("+","",$nom);
	$nom=str_replace("^","",$nom);
	$nom=str_replace("(","",$nom);
	$nom=str_replace(")","",$nom);
	$nom=str_replace("#","",$nom);
	$nom=str_replace("=","",$nom);
	$nom=str_replace("$","",$nom);	
	$nom=str_replace("%","",$nom);
	//$nom = strtolower(substr ($nom,0,$this->parms["maxchars"]));
	$nom = strtolower($nom);
 	return $nom;
 	}
function enlever_controlM($fichier)
	{
	$fic=file($fichier);
	$fp=fopen($fichier,"w");
	while (list ($cle, $val) = each ($fic)) 
		{
		$val=str_replace(CHR(10),"",$val);
		$val=str_replace(CHR(13),"",$val);
		fputs($fp,"$val\n");
		}
	fclose($fp);
	}
function action($arr_data)
{
$action=$arr_data["a"];

switch($action) {
case "sup"; $this->action_delete($arr_data);break;
case "upload";$this->action_upload($arr_data);break;
case "mkdir"; $this->action_mkdir($arr_data);break;		
case "rename"; $this->action_rename($arr_data); break;
}
}//end of function act
function action_rename($arr_data)
{
$err=0;
//$this->rep=stripslashes($arr_data["rep"]);
$fic=stripslashes($arr_data["fic"]);
$nom_fic=basename($arr_data["fic"]);
$messtmp="<font color=red>";
$newdirname=$this->getfilename($arr_data["fic_new"]);
$old=$this->root.$this->slash().$fic;
$new=dirname($old).$this->slash().$newdirname;

if($newdirname=="")
	{
	$messtmp.="$fic_new :". $this->mess[37] ; $err=1;
	}
//else if(file_exists($new))
//	{
//	$messtmp.="<b>$fic_new</b> $this->mess[43]"; $err=1;
//	}
else
	{
	rename($old,$new);
	$messtmp.="<b>$fic</b>". $this->mess[41] ."<b>$newdirname</b>";
	}
$messtmp.="<br><br>";	
$messtmp.="</font>";
		if ($err)
		{echo "<center>\n";
		echo "$messtmp";}
}
function create_dir($dir)
{
	umask(000);
	$rep = $this->root.$this->slash().$this->rep.$this->slash().$dir;
	if (!mkdir($rep,0777))
		echo $this->mess[77] . " $rep - $dir";
}
function createIfNotExists($dir)
{
$full_rep=$this->root.$this->slash().$this->rep.$this->slash().$dir;
if (!file_exists($full_rep)) 
	$this->create_dir($dir);
}
function action_mkdir($arr_data)
{
$err="";
$messtmp="<font color=" . $this->parms["error_message"] . ">";
$this->rep=stripslashes($arr_data["rep"]);
$nomdir=$this->getfilename($arr_data["newdirname"]);

if($nomdir=="")
	{
	$messtmp.=$this->mess[37] ." $nomdir"; $err=1;
	}
else if(file_exists($this->root.$this->slash().$this->rep.$this->slash().$nomdir))
	{
	$messtmp.=$this->mess[40]; $err=1;
	}
else
	{
	$this->create_dir($nomdir);
	//umask(000);
	//mkdir($this->root.$this->slash().$this->rep.$this->slash().$nomdir,0777);
	$messtmp.=$this->mess[38] ."<b>$nomdir</b>". $this->mess[39] ." <b>";
	if($this->rep=="") {$messtmp.="/";} else {$messtmp.="$rep";}
	$messtmp.="</b>";
	}
$messtmp.="</font>";
if ($err)
	{echo "<center>\n";
	echo "$messtmp";}
}
function action_delete($arr_data)
{
//$this->rep=stripslashes($arr_data["rep"]);
$fic=stripslashes($arr_data["fic"]);
if ($fic!="")
	{
	$messtmp="<font face=\"$font\" size=\"2\">";
	$a_effacer=$this->root.$this->slash().$fic;
		
		if(file_exists($a_effacer))
			{
			if(is_dir($a_effacer)){$this->deldir($a_effacer);$messtmp.="$this->mess[38] <b>$fic</b> $this->mess[44].";}
			else {unlink($a_effacer); $messtmp.="$this->mess[34] <b>$fic</b> $this->mess[44].";}
			}
		else {$messtmp.=$this->mess[76];}
	}
else echo "erreur (file delete) : aucun fichier spécifié ";
			
			//$messtmp.="</font>";
			//echo "<center>\n";
			//echo "$messtmp";
}
function action_upload($arr_data)
{
global $_FILES;
$InputFile = "userfile";
$this->rep=stripslashes($arr_data["rep"]);
		
$userfile = $_FILES[$InputFile]['tmp_name'];
$userfile_size = $_FILES[$InputFile]['size'];
$userfile_name = $_FILES[$InputFile]['name'];
$FileType = $_FILES[$InputFile]['type'];

$messtmp="<font color=" . $this->parms["error_message"] . ">";
if($this->rep!=""){$rep_source=$this->slash().$this->rep;}
$destination=$this->root.$rep_source;
if ($userfile_size!=0) {$taille_ko=$userfile_size/1024;} else {$taille_ko=0;}
if ($userfile=="none") {$message=$this->mess[31];}
if ($userfile!="none" && $userfile_size!=0)
	{
	$userfile=stripslashes($userfile);
	$userfile_name=$this->getfilename($userfile_name);
	if (!copy($userfile, "$destination/$userfile_name"))
		{
        		$message="<br>$this->mess[33]<br>$userfile_name";
	        	}
       	else
		{
        		if(eregi("\.txt","$userfile_name")
        		||eregi("\.html","$userfile_name")
        		||eregi("\.htm","$userfile_name")
        		||eregi("\.php","$userfile_name")
        		||eregi("\.php3","$userfile_name")
        		||eregi("\.htaccess","$userfile_name")
        		||eregi("\.htpasswd","$userfile_name")
        		||eregi("\.pl","$userfile_name")
        		||eregi("\.cgi","$userfile_name")
        		||eregi("\.js","$userfile_name")
        		)
        			{
        			$this->enlever_controlM("$destination/$userfile_name");
        			}		
		$message="$this->mess[34] <b>$userfile_name</b> $this->mess[35] <b>$rep</b>";
		}
	}
$messtmp.="$message<br>";		
$messtmp.="</font>";
	if ($err)
		{echo "<center>\n";
		echo "$messtmp";}
}
function frm_new()
	{
	global $self;
	$str=array();
	$str[] = "<table width=100%  bgcolor=white style='border:solid 1px $this->insidebordercolor'> 	   <form enctype=\"multipart/form-data\" action=\"$self\" method=\"post\"><tr > 
	    <td colspan=\"2\"  class=explorer_entry>" . $this->mess[25];
	if($this->rep==""){$str[] = "/";}else{$str[] = $this->rep;}
	$str[] = "</b><br>
	        <input type=\"file\" name=\"userfile\" size=\"30\"   class=explorer_entry>
	        <INPUT TYPE=\"hidden\" name=\"a\" value=\"upload\">
	        <INPUT TYPE=\"hidden\" name=\"id\" value=\"$id\">
	        <input type=\"hidden\" name=\"rep\" value=\"" . $this->rep ."\">
	        <input type=\"submit\" name=\"Submit\" value=\"" . $this->mess[27]."\"   class=explorer_entry>
	      
	    </td>
	  </tr></form>
	   <form method=\"post\" action=\"$self\"><tr bgcolor=white> 
	    <td colspan=\"2\"  class=explorer_entry> " . $this->mess[26] ."";
	if($this->rep==""){$str[] = "/";}else{$str[] = $this->rep;}
	$str[] = "</font><br>
	        <input type=\"text\" name=\"newdirname\" size=\"30\"  class=explorer_entry>
	        <input type=\"hidden\" name=\"rep\" value=\"" . $this->rep ."\">
	        <input type=\"hidden\" name=\"a\" value=\"mkdir\">
	        <INPUT TYPE=\"hidden\" name=\"id\" value=\"$id\">
	        <input   class=explorer_entry type=\"submit\" name=\"Submit\" value=\"" . $this->mess[29]."\" >
	     
	    </td>
	  </tr> </form></table>";
	 return join("",$str);
	}
function HTML_buttons()
{
global $libdir;
$str=array();
//See 
$button_see=$this->mess["visu"];
$href_see = "javascript:openPopWin('" .  $libdir  . $this->parms["basedir"]. "file.php?f=" . 	$this->url_root . "/' + get_current_id()   )";

$str[] = "<a href=\"" . $href_see ."\" class=explorer_entry >$button_see</a>";

//Rename
$button_rename =$this->mess[6];
$href_rename="javascript:precise_url('" . $this->get_url('&a=rename&rep=' . $this->rep ) . "&fic=' + get_current_id()  ,'fic_new','Renommer ', get_current_id() )";
$str[] = "<a href=\"" . $href_rename ."\"   class=explorer_entry>$button_rename</a>";

//Delete
$button_delete =$this->mess[7];
$href_delete="javascript:confirm_url('" . $this->get_url('&a=sup&rep=' . $this->rep ) . "&fic=' + get_current_id()  , 'ce fichier')";
$str[] = "<a href=\"" . $href_delete."\"   class=explorer_entry>$button_delete</a>";
$return_string = "<table width=100% style='border:solid 1px $this->insidebordercolor' bgcolor=white><tr><td align=right  class=explorer_entry>" . join(' | ', $str) . "</td></tr></table>" . $this->horizontal_space();
return $return_string;
}

	
function js()
{
return  "<script>
var selected = '';
var dest=''
function select_line(obj,num,id){
    if (selected!='' ){  selected.bgColor = '#ffffff';}
        obj.bgColor = '#ff9900';
        selected = obj;
		dest=id;
  document.selection.empty();
    }
function get_current_id()
{
return dest;
}	
</script>";
}
function get_url($parms)
{
global $self;
return $self . "?$parms";
}
}
?>