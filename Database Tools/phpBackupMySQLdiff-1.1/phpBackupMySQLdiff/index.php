<?php
/**universal
 * Paramètrer le logiciel - Lancer des sauvegardes et des restaurations
 * 
 * @author Thomas Pequet
 * @version 1.0 
 */

// Repertoire dans lequel on est par rapport à la racine du site
$rep_par_rapport_racine = "";

// Identifant de la page (0, ou 1, ...)
$page = "1";

// Fichiers à inclure dans la page
@session_start();
include_once($rep_par_rapport_racine."inc/fonctions.inc.php");
include($rep_par_rapport_racine."inc/config.inc.php");
include($rep_par_rapport_racine.$ficConnBase);
include($rep_par_rapport_racine."lang/".$langue.".inc.php");
include($rep_par_rapport_racine."inc/img.inc.php");

// Constantes de la page (en majuscules)

// Variables de la page
$infos = "";
$erreur = "";
// Nom par default de la configuration  de Sauvegarde
if (!isset($nomconfigsauvegarde) && $action!="supprimerConfigurationSauvegarde")
	$nomconfigsauvegarde = "default";	
// Nom par default de la configuration  de Restauration
if (!isset($nomconfigrestauration) && $action!="supprimerConfigurationRestauration")
	$nomconfigrestauration = "default";	
// Définir la rubrique par default: "", "save", "restore" ou "install"
if (!isset($rub))
	$rub = "";
// Chargement des paramètres de sauvegarde contenu dans le fichier XML
if ($rub=="save")
	$optionsSauvegarde = chargerConfig($ficConfigSauvegarde);
// Chargement des paramètres de restauration contenu dans le fichier XML
if ($rub=="restore")
	$optionsRestauration = chargerConfig($ficConfigRestauration);

// Fonctions de la page
	
// Actions de la page
if ($action=="installerTimestamp" && $rub=="install") {

	// Parcours des tables
	for ($i=0;$i<sizeof($tables);$i++) {
		if ($tables[$i]!="") {
			list($base, $table) = split("\.", $tables[$i]);
			// Sélection de la base
			$querySql = "USE `".$base."`";
			if ($bd->sql_query($querySql)) {
				// Modification de la structure de la table
				$querySql = "ALTER TABLE `".$table."` ADD `TIMESTAMP` timestamp(14) NOT NULL;";
				//echo $querySql."<BR>";
				if ($bd->sql_query($querySql))
					$infos .= str_replace(array("##base##","##table##"), array($base,$table), $message1)."<BR>";
				else
					$erreur .= str_replace(array("##base##","##table##"), array($base,$table), $message2)."<BR>";
			} else {
				$erreur .= str_replace("##base##", $base, $message3)."<BR>";
			}
		}
	}

} else if ($action=="sauvegarderConfigurationSauvegarde" && $rub=="save") {

	if (trim($nomconfignew)=="" || $nomconfignew=="default")
		$nomconfig = "default";
	else
		$nomconfig = $nomconfignew;
	unset($nomconfignew);
	
	// Ajouter au tableau des options la nouvelle configuration
	$optionsSauvegarde[$nomconfig]["dossier"] = stripslashes($dossier);
	$optionsSauvegarde[$nomconfig]["infos"] = $infosSauvegarder;
	$optionsSauvegarde[$nomconfig]["formatarchive"] = $formatarchive;
	$optionsSauvegarde[$nomconfig]["tables"] = null;
	for ($i=0;$i<sizeof($tables);$i++) {
		if ($tables[$i]!="") {
			list($base, $table) = split("\.", $tables[$i]);
			$optionsSauvegarde[$nomconfig]["tables"][$base][$table] = 1;	
		}
	}
	
	// Mise en forme des options pour la sauvegarde dans le fichier de config 
	$texte = 	"<BACKUPHPMYSQL>\n";
	
	$optionsSauvegarde_keys = array_keys($optionsSauvegarde);
	
	for ($j=0;$j<sizeof($optionsSauvegarde_keys);$j++) {
	 
		$texte .=	"\t<CONFIG NAME=\"".$optionsSauvegarde_keys[$j]."\">\n".
				"\t\t<DOSSIER>".$optionsSauvegarde[$optionsSauvegarde_keys[$j]]["dossier"]."</DOSSIER>\n".
				"\t\t<INFOS>".$optionsSauvegarde[$optionsSauvegarde_keys[$j]]["infos"]."</INFOS>\n";
		
		if (is_array($optionsSauvegarde[$optionsSauvegarde_keys[$j]]["tables"])) {
			$bases_keys = array_keys($optionsSauvegarde[$optionsSauvegarde_keys[$j]]["tables"]);		
			for ($i=0;$i<sizeof($bases_keys);$i++) {
				$tables_keys = array_keys($optionsSauvegarde[$optionsSauvegarde_keys[$j]]["tables"][$bases_keys[$i]]);		
				for ($k=0;$k<sizeof($tables_keys);$k++) {
					$texte .= "\t\t<TABLE>".$bases_keys[$i].".".$tables_keys[$k]."</TABLE>\n";
				}
			}
		}				
	
		$texte .= 	"\t\t<FORMATARCHIVE>".$optionsSauvegarde[$optionsSauvegarde_keys[$j]]["formatarchive"]."</FORMATARCHIVE>\n".
				"\t</CONFIG>\n";
	}

	$texte .=	"</BACKUPHPMYSQL>";	
		
	if (is_writable($ficConfigSauvegarde)) {
		// Ouverture du fichier
		$fp = @fopen($ficConfigSauvegarde, "w+");
		// Ecriture dans le fichier
		fwrite($fp, $texte, strlen($texte));
		// Fermeture du fichier
		fclose($fp);
		
		$nomconfigsauvegarde = $nomconfig;
		
		$infos .= str_replace("##config##", $nomconfig, $message4)."<BR>";
	} else {
		$erreur .= str_replace("##fic##", $ficConfigSauvegarde, $message3)."<BR>";
	}

} else if ($action=="supprimerConfigurationSauvegarde" && $rub=="save") {
	
	if (trim($nomconfigsauvegarde)!="") {
	
		// Mise en forme des options pour la sauvegarde dans le fichier de config 
		$texte = 	"<BACKUPHPMYSQL>\n";
	
		$optionsSauvegarde_keys = array_keys($optionsSauvegarde);
	
		for ($j=0;$j<sizeof($optionsSauvegarde_keys);$j++) {
	 
			if ($optionsSauvegarde_keys[$j]!=$nomconfigsauvegarde) {
			
				$texte .=	"\t<CONFIG NAME=\"".$optionsSauvegarde_keys[$j]."\">\n".
						"\t\t<DOSSIER>".$optionsSauvegarde[$optionsSauvegarde_keys[$j]]["dossier"]."</DOSSIER>\n".
						"\t\t<INFOS>".$optionsSauvegarde[$optionsSauvegarde_keys[$j]]["infos"]."</INFOS>\n";
		
				if (is_array($optionsSauvegarde[$optionsSauvegarde_keys[$j]]["tables"])) {
					$bases_keys = array_keys($optionsSauvegarde[$optionsSauvegarde_keys[$j]]["tables"]);		
					for ($i=0;$i<sizeof($bases_keys);$i++) {
						$tables_keys = array_keys($optionsSauvegarde[$optionsSauvegarde_keys[$j]]["tables"][$bases_keys[$i]]);		
						for ($k=0;$k<sizeof($tables_keys);$k++) {
							$texte .= "\t\t<TABLE>".$bases_keys[$i].".".$tables_keys[$k]."</TABLE>\n";
						}
					}
				}				
	
				$texte .= 	"\t</CONFIG>\n";
			}
		}

		$texte .=	"</BACKUPHPMYSQL>";	
		
		if (is_writable($ficConfigSauvegarde)) {
			// Ouverture du fichier
			$fp = @fopen($ficConfigSauvegarde, "w+");
			// Ecriture dans le fichier
			fwrite($fp, $texte, strlen($texte));
			// Fermeture du fichier
			fclose($fp);
		
			$infos .= str_replace("##config##", $nomconfigsauvegarde, $message6)."<BR>";
			
			$nomconfigsauvegarde = "default";
			
			// Chargement des paramètres de sauvegarde contenu dans le fichier XML
			$optionsSauvegarde = chargerConfig($ficConfigSauvegarde);
			
		} else {
			$erreur .= str_replace("##fic##", $ficConfigSauvegarde, $message3)."<BR>";
		}	
	}
	
} else if ($action=="sauvegarderConfigurationRestauration" && $rub=="restore") {

	if (trim($nomconfignew)=="" || $nomconfignew=="default")
		$nomconfig = "default";
	else
		$nomconfig = $nomconfignew;
	unset($nomconfignew);
	
	// Ajouter au tableau des options la nouvelle configuration
	$optionsRestauration[$nomconfig]["dossier"] = stripslashes($dossier);
	$optionsRestauration[$nomconfig]["infos"] = $infosRestaurer;
	$optionsRestauration[$nomconfig]["requete"] = $requetechamp.$requeteoperateur.$requetevaleur;	
	$optionsRestauration[$nomconfig]["tables"] = null;
	for ($i=0;$i<sizeof($tables);$i++) {
		if ($tables[$i]!="") {
			list($base, $table) = split("\.", $tables[$i]);
			$optionsRestauration[$nomconfig]["tables"][$base][$table] = 1;	
		}
	}
	
	// Mise en forme des options pour la sauvegarde dans le fichier de config 
	$texte = 	"<BACKUPHPMYSQL>\n";
	
	$optionsRestauration_keys = array_keys($optionsRestauration);
	
	for ($j=0;$j<sizeof($optionsRestauration_keys);$j++) {
	 
		$texte .=	"\t<CONFIG NAME=\"".$optionsRestauration_keys[$j]."\">\n".
				"\t\t<DOSSIER>".$optionsRestauration[$optionsRestauration_keys[$j]]["dossier"]."</DOSSIER>\n".
				"\t\t<INFOS>".$optionsRestauration[$optionsRestauration_keys[$j]]["infos"]."</INFOS>\n".
				"\t\t<REQUETE>".$optionsRestauration[$optionsRestauration_keys[$j]]["requete"]."</REQUETE>\n";
		
		if (is_array($optionsRestauration[$optionsRestauration_keys[$j]]["tables"])) {
			$bases_keys = array_keys($optionsRestauration[$optionsRestauration_keys[$j]]["tables"]);		
			for ($i=0;$i<sizeof($bases_keys);$i++) {
				$tables_keys = array_keys($optionsRestauration[$optionsRestauration_keys[$j]]["tables"][$bases_keys[$i]]);		
				for ($k=0;$k<sizeof($tables_keys);$k++) {
					$texte .= "\t\t<TABLE>".$bases_keys[$i].".".$tables_keys[$k]."</TABLE>\n";
				}
			}
		}				
	
		$texte .= 	"\t</CONFIG>\n";
	}

	$texte .=	"</BACKUPHPMYSQL>";	
		
	if (is_writable($ficConfigRestauration)) {
		// Ouverture du fichier
		$fp = @fopen($ficConfigRestauration, "w+");
		// Ecriture dans le fichier
		fwrite($fp, $texte, strlen($texte));
		// Fermeture du fichier
		fclose($fp);
		
		$nomconfigrestauration = $nomconfig;
		
		$infos .= str_replace("##config##", $nomconfig, $message4)."<BR>";
	} else {
		$erreur .= str_replace("##fic##", $ficConfigRestauration, $message3)."<BR>";
	}
	
} else if ($action=="supprimerConfigurationRestauration" && $rub=="restore") {
	
	if (trim($nomconfigrestauration)!="") {
	
		// Mise en forme des options pour la sauvegarde dans le fichier de config 
		$texte = 	"<BACKUPHPMYSQL>\n";
	
		$optionsRestauration_keys = array_keys($optionsRestauration);
	
		for ($j=0;$j<sizeof($optionsRestauration_keys);$j++) {
	 
			if ($optionsRestauration_keys[$j]!=$nomconfigrestauration) {
			
				$texte .=	"\t<CONFIG NAME=\"".$optionsRestauration_keys[$j]."\">\n".
						"\t\t<DOSSIER>".$optionsRestauration[$optionsRestauration_keys[$j]]["dossier"]."</DOSSIER>\n".
						"\t\t<INFOS>".$optionsRestauration[$optionsRestauration_keys[$j]]["infos"]."</INFOS>\n".
						"\t\t<REQUETE>".$optionsRestauration[$optionsRestauration_keys[$j]]["requete"]."</REQUETE>\n";
		
				if (is_array($optionsRestauration[$optionsRestauration_keys[$j]]["tables"])) {
					$bases_keys = array_keys($optionsRestauration[$optionsRestauration_keys[$j]]["tables"]);		
					for ($i=0;$i<sizeof($bases_keys);$i++) {
						$tables_keys = array_keys($optionsRestauration[$optionsRestauration_keys[$j]]["tables"][$bases_keys[$i]]);		
						for ($k=0;$k<sizeof($tables_keys);$k++) {
							$texte .= "\t\t<TABLE>".$bases_keys[$i].".".$tables_keys[$k]."</TABLE>\n";
						}
					}
				}				
	
				$texte .= 	"\t</CONFIG>\n";
			}
		}

		$texte .=	"</BACKUPHPMYSQL>";	
		
		if (is_writable($ficConfigRestauration)) {
			// Ouverture du fichier
			$fp = @fopen($ficConfigRestauration, "w+");
			// Ecriture dans le fichier
			fwrite($fp, $texte, strlen($texte));
			// Fermeture du fichier
			fclose($fp);
		
			$infos .= str_replace("##config##", $nomconfigrestauration, $message6)."<BR>";
			
			$nomconfigrestauration = "default";
			
			// Chargement des paramètres de restauration contenu dans le fichier XML
			$optionsRestauration = chargerConfig($ficConfigRestauration);
			
		} else {
			$erreur .= str_replace("##fic##", $ficConfigRestauration, $message3)."<BR>";
		}	
	}
}

// Affichage des en-têtes (normalement avant ce include, rien n'est affiché)
include($rep_par_rapport_racine."inc/header.inc.php"); 
?>
<CENTER>
<B>[ <? if ($rub!="") { ?><A HREF="<?=$PHP_SELF;?>"><? } ?><?=$texte4;?></A> | <? if ($rub!="install") { ?><A HREF="<?=$PHP_SELF;?>?rub=install"><? } ?><?=$texte1;?></A> | <? if ($rub!="save") { ?><A HREF="<?=$PHP_SELF;?>?rub=save"><? } ?><?=$texte2;?></A> | <? if ($rub!="restore") { ?><A HREF="<?=$PHP_SELF;?>?rub=restore"><? } ?><?=$texte3;?></A> ]</B>
</CENTER>
<BR>
<TABLE BORDER="0" CELLSPACING="1" CELLPADDING="0" WIDTH="900" ALIGN="center">
  <TR>
   <TD>
<? 
if ($infos!="" || $erreur!="") {
	echo "<HR>";
	echo "<U>".$texteInfos.":</U><BR><A CLASS=\"vert\">".$infos."</A>";
	echo "<HR SIZE=\"1\">";
	echo "<U>".$texteErreurs.":</U><BR><A CLASS=\"rouge\">".$erreur."</A>";
	echo "<HR>";
	echo "<BR>";
}
?>
   </TD>
  </TR>
</TABLE>
<?
if ($rub=="install") {
?>
<TABLE BORDER="0" CELLSPACING="1" CELLPADDING="0" WIDTH="900" ALIGN="center">
  <TR VALIGN="top">
   <TD><FIELDSET><LEGEND><B><U><?=$texte5;?></U></B></LEGEND>
   <TABLE BORDER="0" CELLSPACING="1" CELLPADDING="6" WIDTH="100%">
     <TR>
      <TD WIDTH="50%">
     	<?=str_replace("##fic##", $ficConfigSauvegarde, $texte6);?>
      </TD>
      <TD WIDTH="50%">	
<?
	if (is_writable($ficConfigSauvegarde)) {
?>
	<B CLASS="vert">OK</B>
<?	
	} else {
?>
	<B CLASS="rouge">Erreur:
<?
		if (is_file($ficConfigSauvegarde)) {
?>
	<?=$texte7;?>
<?	
		} else {
?>	
	<?=$texte8;?>
<?
		}
?>	
	</B>
<?
	}
?>
      </TD>
     </TR>
     <TR>
      <TD>
     	<?=str_replace("##fic##", $ficConfigRestauration, $texte6);?>
      </TD>
      <TD>	
<?
	if (is_writable($ficConfigRestauration)) {
?>
	<B CLASS="vert">OK</B>
<?	
	} else {
?>
	<B CLASS="rouge">Erreur:
<?
		if (is_file($ficConfigRestauration)) {
?>
	<?=$texte7;?>
<?	
		} else {
?>	
	<?=$texte8;?>
<?
		}
?>	
	</B>
<?	
	}
?>
      </TD>
     </TR> 
     <TR>
      <TD>
     	<?=$texte9;?>
      </TD>
      <TD>	
<?
	if (function_exists("xml_parse")) {
?>
	<B CLASS="vert">OK</B>
<?	
	} else {
?>
	<B CLASS="rouge">Erreur: <?=$texte10;?></B>
<?	
	}
?>
      </TD>
     </TR> 
     <TR>
      <TD>
     	<?=$texte11;?>
      </TD>
      <TD>	
<?
	if (function_exists("gzopen")) {
?>
	<B CLASS="vert">OK</B>
<?	
	} else {
?>
	<B CLASS="rouge">Erreur: <?=$texte12;?></B>
<?	
	}
?>
      </TD>
     </TR>               
   </TABLE>
   </FIELDSET></TD>
  </TR>
  <TR>
   <TD>&nbsp;</TD>
  </TR>  
  <FORM NAME="formInstallerTimestamp" ACTION="<?=$PHP_SELF;?>?rub=<?=$rub;?>" METHOD="post">  
  <INPUT TYPE="hidden" NAME="action" VALUE="">  
  <TR VALIGN="top">
   <TD><FIELDSET><LEGEND><B><U><?=$texte13;?></U></B></LEGEND>
   <TABLE BORDER="0" CELLSPACING="1" CELLPADDING="6" WIDTH="100%">
     <TR>
      <TD WIDTH="50%" VALIGN="top">
     	<DIV ALIGN="justify"><?=$texte14;?></DIV>
      </TD>
      <TD WIDTH="50%">	
      	<?=str_replace("##serveur##", $bdServeur, $texte15);?>
	<SELECT NAME="tables[]" size="25" multiple="multiple"<?=$taille_select1;?>>
<?
	// Sélection de tous les bases
	$querySql = "SHOW DATABASES";
	//echo $querySql."<BR>";
	$result = $bd->sql_query($querySql);
	if ($bd->sql_numrows($result)>0) {
		while($row = $bd->sql_fetchrow($result)) {
?>
	 <OPTION VALUE="">[<?=$texte45;?>: `<?=$row["Database"];?>`]</OPTION>
<?	
			// Sélection de tous les bases
			$querySql = "SHOW TABLES FROM ".$row["Database"];
			//echo $querySql."<BR>";
			$result1 = $bd->sql_query($querySql);
			if ($bd->sql_numrows($result1)>0) {
				while($row1 = $bd->sql_fetchrow($result1)) {
?>
	 <OPTION VALUE="<?=$row["Database"].".".$row1["Tables_in_".$row["Database"]];?>">&nbsp;&nbsp;&nbsp;`<?=$row1["Tables_in_".$row["Database"]];?>`</OPTION>
<?				
				}
			}
		}
	}
	else
	{
?>
	 <OPTION VALUE="">[<?=$texte45;?>: `<?=$bdNomBase;?>`]</OPTION>
<?	
		// Sélection de tous les bases
		$querySql = "SHOW TABLES FROM ".$bdNomBase;
		//echo $querySql."<BR>";
		$result1 = $bd->sql_query($querySql);
		if ($bd->sql_numrows($result1)>0) {
			while($row1 = $bd->sql_fetchrow($result1)) {
?>
	 <OPTION VALUE="<?=$bdNomBase.".".$row1["Tables_in_".$bdNomBase];?>">&nbsp;&nbsp;&nbsp;`<?=$row1["Tables_in_".$bdNomBase];?>`</OPTION>
<?				
			}
		}
	}
?>			
	 </SELECT>
      </TD>
     </TR>
     <TR>
      <TD COLSPAN="2"><INPUT TYPE="button" NAME="" VALUE="<?=$texte16;?>"<?=$taille_input1bis;?> onClick="modifierAction('formInstallerTimestamp','installerTimestamp'); submit();"></TD>
     </TR>
   </TABLE>
   </FIELDSET></TD>
  </TR>  
  </FORM>
</TABLE>
<?
} else if ($rub=="save") {
?> 
<TABLE BORDER="0" CELLSPACING="1" CELLPADDING="0" WIDTH="900" ALIGN="center">
  <FORM NAME="formSauvegarder" ACTION="save.<?=$extension;?>" METHOD="get" TARGET="_blank">  
  <TR VALIGN="top">
   <TD><FIELDSET><LEGEND><B><U><?=$texte17;?></U></B></LEGEND>
   <TABLE BORDER="0" CELLSPACING="1" CELLPADDING="6" WIDTH="100%">
     <TR>
      <TD WIDTH="50%">
	<?=$texte18;?>:
      </TD>
      <TD WIDTH="50%">
	<SELECT NAME="nomconfig">
<?
	$optionsSauvegarde_keys = array_keys($optionsSauvegarde);
	for ($i=0;$i<sizeof($optionsSauvegarde_keys);$i++) {
?>
	 <OPTION VALUE="<?=$optionsSauvegarde_keys[$i];?>"<? if ($nomconfigsauvegarde==$optionsSauvegarde_keys[$i]) echo " SELECTED"; ?>><?=$optionsSauvegarde_keys[$i];?></OPTION>
<?
	}
?>	   
	</SELECT>
      </TD>
     </TR>
     <TR>
      <TD COLSPAN="2"><INPUT TYPE="submit" NAME="" VALUE="<?=$texte21;?>"<?=$taille_input1bis;?>></TD>
     </TR>
     <TR>
      <TD COLSPAN="2"><U><?=$texte19;?>:</U>
      <BR>
      <?=$texte20;?>
      </TD>
     </TR>          
   </TABLE>
   </FIELDSET></TD>
  </TR>
  </FORM>
  <TR>
   <TD>&nbsp;</TD>
  </TR>
  <TR>
   <TD>&nbsp;</TD>
  </TR>  
  <FORM NAME="formConfigurationSauvegarde" ACTION="<?=$PHP_SELF;?>?rub=<?=$rub;?>" METHOD="post">  
  <INPUT TYPE="hidden" NAME="action" VALUE="">
  <INPUT TYPE="hidden" NAME="nomconfignew" VALUE="">
  <TR VALIGN="top">
   <TD><FIELDSET><LEGEND><B><U><?=$texte22;?></U></B></LEGEND>
   <TABLE BORDER="0" CELLSPACING="1" CELLPADDING="6" WIDTH="100%">
     <TR>
      <TD WIDTH="50%">  	 
	<?=$texte23;?>: 
      </TD>
      <TD>
	<SELECT NAME="nomconfigsauvegarde">
	 <OPTION></OPTION>
<?
	$optionsSauvegarde_keys = array_keys($optionsSauvegarde);
	for ($i=0;$i<sizeof($optionsSauvegarde_keys);$i++) {
?>
	 <OPTION VALUE="<?=$optionsSauvegarde_keys[$i];?>"<? if ($nomconfigsauvegarde==$optionsSauvegarde_keys[$i]) echo " SELECTED"; ?>><?=$optionsSauvegarde_keys[$i];?></OPTION>
<?
	}
?>	   
	</SELECT>
	<INPUT TYPE="button" NAME="ok1" VALUE="<?=$texteCharger;?>"<?=$taille_input2;?> onClick="modifierAction('formConfigurationSauvegarde','chargerConfigurationSauvegarde'); submitForm('formConfigurationSauvegarde');">
	<INPUT TYPE="button" NAME="ok2" VALUE="<?=$texteSupprimer;?>"<?=$taille_input2;?> onClick="modifierAction('formConfigurationSauvegarde','supprimerConfigurationSauvegarde'); submitForm('formConfigurationSauvegarde');">
      </TD>
     </TR>
     <TR>
      <TD COLSPAN="2">
	<HR SIZE="1">
      </TD>
     </TR>
     <TR>
      <TD VALIGN="top">
	<?=$texte24;?>: 
      </TD>
      <TD CLASS="option">
	<INPUT TYPE="radio" NAME="infosSauvegarder" VALUE="data"<? if ($optionsSauvegarde[$nomconfigsauvegarde]["infos"]=="data") echo " CHECKED"; ?> ID="data"> <LABEL FOR="data"><?=$texte25;?></LABEL>
	<INPUT TYPE="radio" NAME="infosSauvegarder" VALUE="dataonly"<? if ($optionsSauvegarde[$nomconfigsauvegarde]["infos"]=="dataonly") echo " CHECKED"; ?> ID="dataonly"> <LABEL FOR="dataonly"><?=$texte26;?></LABEL>
	<INPUT TYPE="radio" NAME="infosSauvegarder" VALUE="structure"<? if ($optionsSauvegarde[$nomconfigsauvegarde]["infos"]=="structure") echo " CHECKED"; ?> ID="structure"> <LABEL FOR="structure"><?=$texte27;?></LABEL>
      </TD>
     </TR>
     <TR>
      <TD VALIGN="top">
	<?=$texte28;?>: 
      </TD>
      <TD>
	<INPUT TYPE="text" NAME="dossier" VALUE="<?=$optionsSauvegarde[$nomconfigsauvegarde]["dossier"];?>"<?=$taille_input1;?>>  
      </TD>
     </TR>
     <TR>
      <TD VALIGN="top">
	<?=$texte29;?>:
      </TD>
      <TD>
      	<?=str_replace("##serveur##", $bdServeur, $texte15);?>
	<SELECT NAME="tables[]" size="20" multiple="multiple"<?=$taille_select1;?>>
<?
	// Sélection de tous les bases
	$querySql = "SHOW DATABASES";
	//echo $querySql."<BR>";
	$result = $bd->sql_query($querySql);
	if ($bd->sql_numrows($result)>0) {
		while($row = $bd->sql_fetchrow($result)) {
?>
	 <OPTION VALUE="">[<?=$texte45;?>: `<?=$row["Database"];?>`]</OPTION>
<?	
			// Sélection de tous les bases
			$querySql = "SHOW TABLES FROM ".$row["Database"];
			//echo $querySql."<BR>";
			$result1 = $bd->sql_query($querySql);
			if ($bd->sql_numrows($result1)>0) {
				while($row1 = $bd->sql_fetchrow($result1)) {
?>
	 <OPTION VALUE="<?=$row["Database"].".".$row1["Tables_in_".$row["Database"]];?>"<? if (isset($optionsSauvegarde[$nomconfigsauvegarde]["tables"][$row["Database"]][$row1["Tables_in_".$row["Database"]]])) echo " SELECTED"; ?>>&nbsp;&nbsp;&nbsp;`<?=$row1["Tables_in_".$row["Database"]];?>`</OPTION>
<?				
				}
			}
		}
	}
	else
	{
?>
	 <OPTION VALUE="">[<?=$texte45;?>: `<?=$bdNomBase;?>`]</OPTION>
<?	
		// Sélection de tous les bases
		$querySql = "SHOW TABLES FROM ".$bdNomBase;
		//echo $querySql."<BR>";
		$result1 = $bd->sql_query($querySql);
		if ($bd->sql_numrows($result1)>0) {
			while($row1 = $bd->sql_fetchrow($result1)) {
?>
	 <OPTION VALUE="<?=$bdNomBase.".".$row1["Tables_in_".$bdNomBase];?>"<? if (isset($optionsSauvegarde[$nomconfigsauvegarde]["tables"][$bdNomBase][$row1["Tables_in_".$bdNomBase]])) echo " SELECTED"; ?>>&nbsp;&nbsp;&nbsp;`<?=$row1["Tables_in_".$bdNomBase];?>`</OPTION>
<?				
			}
		}
	}	
?>			
	</SELECT>
      </TD>
     </TR>
     <TR>
      <TD VALIGN="top">
	<?=$texte30;?>:
      </TD>
      <TD CLASS="option">
	<INPUT TYPE="radio" NAME="formatarchive" VALUE="zip"<? if ($optionsSauvegarde[$nomconfigsauvegarde]["formatarchive"]=="zip") echo " CHECKED"; ?> ID="zip"> <LABEL FOR="zip">zip</LABEL>
	<INPUT TYPE="radio" NAME="formatarchive" VALUE="tar"<? if ($optionsSauvegarde[$nomconfigsauvegarde]["formatarchive"]=="tar") echo " CHECKED"; ?> ID="tar"> <LABEL FOR="tar">tar</LABEL>
	<INPUT TYPE="radio" NAME="formatarchive" VALUE="tgz"<? if ($optionsSauvegarde[$nomconfigsauvegarde]["formatarchive"]=="tgz") echo " CHECKED"; ?> ID="tgz"> <LABEL FOR="tgz">tar.gz</LABEL>  
      </TD>
     </TR>
     <TR>
      <TD COLSPAN="2">
	<INPUT TYPE="button" NAME="" VALUE="<?=$texte31;?>"<?=$taille_input1bis;?> onClick="modifierAction('formConfigurationSauvegarde','sauvegarderConfigurationSauvegarde'); promptNomConfiguration('formConfigurationSauvegarde','<?=$nomconfigsauvegarde;?>');">
      </TD>
     </TR>
   </TABLE>
   </FIELDSET></TD>
  </FORM>
</TABLE>  
<?

} else if ($rub=="restore") {  
?> 
<TABLE BORDER="0" CELLSPACING="1" CELLPADDING="0" WIDTH="900" ALIGN="center">
  <FORM NAME="formRestaurer" ACTION="restore.<?=$extension;?>" METHOD="get">  
  <INPUT TYPE="hidden" NAME="affichage" VALUE="">
  <TR VALIGN="top">
   <TD><FIELDSET><LEGEND><B><U><?=$texte32;?></U></B></LEGEND>
   <TABLE BORDER="0" CELLSPACING="1" CELLPADDING="6" WIDTH="100%">
     <TR>
      <TD WIDTH="50%">
	<?=$texte33;?>: 
      </TD>
      <TD WIDTH="50%">
	<SELECT NAME="nomconfig">
<?
	$optionsRestauration_keys = array_keys($optionsRestauration);
	for ($i=0;$i<sizeof($optionsRestauration_keys);$i++) {
?>
	 <OPTION VALUE="<?=$optionsRestauration_keys[$i];?>"<? if ($nomconfigrestauration==$optionsRestauration_keys[$i]) echo " SELECTED"; ?>><?=$optionsRestauration_keys[$i];?></OPTION>
<?
	}
?>	   
	</SELECT>
       </TD>
      </TR>
      <TR>
       <TD>
	<?=$texte34;?>:
       </TD>
       <TD>
	<SELECT NAME="jourDeb">
<? 
	for($i=1;$i<=31;$i++) {
		if ($i<=date("t")) {
?>
	 <OPTION VALUE="<?=$i;?>"<? if ($i==1) echo " SELECTED"; ?>><?=$i;?></OPTION>
<?
		} else {
?>
	 <OPTION VALUE="1"></OPTION>
<?	
		}
	}
?>
	</SELECT>
	<SELECT NAME="moisDeb" onChange="updateJour('formRestaurer','jourDeb','moisDeb','anneeDeb')">
<? 
	for($i=1;$i<=12;$i++) {
?>
	 <OPTION VALUE="<?=$i;?>"<? if ($i==date("n")) echo " SELECTED"; ?>><?=$nomMois[sprintf("%02d",$i)];?></OPTION>
<?
	}
?>
	</SELECT>
	<SELECT NAME="anneeDeb" onChange="updateJour('formRestaurer','jourDeb','moisDeb','anneeDeb')">
<? 
	for($i=2002;$i<=2010;$i++) {
?>
	 <OPTION VALUE="<?=$i;?>"<? if ($i==date("Y")) echo " SELECTED"; ?>><?=$i;?></OPTION>
<?
	}
?>
	</SELECT>
	<?=$texte35;?>
	<SELECT NAME="jourFin">
<? 
	for($i=1;$i<=31;$i++) {
		if ($i<=date("t")) {
?>
	 <OPTION VALUE="<?=$i;?>"<? if ($i==date("t")) echo " SELECTED"; ?>><?=$i;?></OPTION>
<?
		} else {
?>
	 <OPTION VALUE=""></OPTION>
<?	
		}
	}
?>
	</SELECT>
	<SELECT NAME="moisFin" onChange="updateJour('formRestaurer','jourFin','moisFin','anneeFin')">
<? 
	for($i=1;$i<=12;$i++) {
?>
	 <OPTION VALUE="<?=$i;?>"<? if ($i==date("n")) echo " SELECTED"; ?>><?=$nomMois[sprintf("%02d",$i)];?></OPTION>
<?
	}
?>
	</SELECT>
	<SELECT NAME="anneeFin" onChange="updateJour('formRestaurer','jourFin','moisFin','anneeFin')">
<? 
	for($i=2002;$i<=2010;$i++) {
?>
	 <OPTION VALUE="<?=$i;?>"<? if ($i==date("Y")) echo " SELECTED"; ?>><?=$i;?></OPTION>
<?
	}
?>
	</SELECT>
       </TD>
      </TR>
      <TR>
       <TD>
	<LABEL FOR="droptable"><?=$texte36;?></LABEL>
       </TD>
       <TD>
	<INPUT TYPE="checkbox" NAME="droptable" VALUE="1" ID="droptable">
       </TD>
      </TR>
      <TR>
       <TD>
	<LABEL FOR="proteger"><?=$texte37;?></LABEL>
       </TD>
       <TD>
	<INPUT TYPE="checkbox" NAME="proteger" VALUE="1" ID="proteger">
       </TD>
      </TR>
      <TR>
       <TD>
       	<LABEL FOR="ajouternombase"><?=$texte38;?></LABEL>
       </TD>
       <TD>
	<INPUT TYPE="checkbox" NAME="ajouternombase" VALUE="1" ID="ajouternombase">
      </TD>
     </TR>
     <TR>						
      <TD COLSPAN="2"><INPUT TYPE="button" NAME="" VALUE="<?=$texte39;?>"<?=$taille_input1bis;?> onClick="javascript:document.formRestaurer.affichage.value='';document.formRestaurer.target='_blank';document.formRestaurer.submit();"></TD>
     </TR>
     <TR>						
      <TD COLSPAN="2"><INPUT TYPE="button" NAME="" VALUE="<?=$texte40;?>"<?=$taille_input1bis;?> onClick="javascript:document.formRestaurer.affichage.value='texte';document.formRestaurer.target='_self';document.formRestaurer.submit();"></TD>
     </TR>
   </TABLE>
   </FIELDSET></TD>
  </TR>  
  </FORM> 
  <TR>
   <TD>&nbsp;</TD>
  </TR>
  <TR>
   <TD>&nbsp;</TD>
  </TR>   
  <FORM NAME="formConfigurationRestauration" ACTION="<?=$PHP_SLEF;?>?rub=<?=$rub;?>" METHOD="post">
  <INPUT TYPE="hidden" NAME="action" VALUE="">
  <INPUT TYPE="hidden" NAME="nomconfignew" VALUE="">
  <TR>
   <TD><FIELDSET><LEGEND><B><U><?=$texte41;?></U></B></LEGEND>
   <TABLE BORDER="0" CELLSPACING="1" CELLPADDING="6" WIDTH="100%">
     <TR>
      <TD WIDTH="50%">
	<?=$texte42;?>: 
      </TD>
      <TD WIDTH="50%">
	<SELECT NAME="nomconfigrestauration">
	 <OPTION></OPTION>
<?
	$optionsRestauration_keys = array_keys($optionsRestauration);
	for ($i=0;$i<sizeof($optionsRestauration_keys);$i++) {
?>
	 <OPTION VALUE="<?=$optionsRestauration_keys[$i];?>"<? if ($nomconfigrestauration==$optionsRestauration_keys[$i]) echo " SELECTED"; ?>><?=$optionsRestauration_keys[$i];?></OPTION>
<?
	}
?>	   
	</SELECT>
	<INPUT TYPE="button" NAME="ok1" VALUE="<?=$texteCharger;?>"<?=$taille_input2;?> onClick="modifierAction('formConfigurationRestauration','chargerConfigurationRestauration'); submitForm('formConfigurationRestauration');">
	<INPUT TYPE="button" NAME="ok2" VALUE="<?=$texteSupprimer;?>"<?=$taille_input2;?> onClick="modifierAction('formConfigurationRestauration','supprimerConfigurationRestauration'); submitForm('formConfigurationRestauration');">	
      </TD>
     </TR>
     <TR>
      <TD COLSPAN="2"><HR SIZE="1"></TD>
     </TR>
     <TR>
      <TD VALIGN="top">	      
	<?=$texte43;?>: 
      </TD>
      <TD CLASS="option">
	<INPUT TYPE="radio" NAME="infosRestaurer" VALUE="data"<? if ($optionsRestauration[$nomconfigrestauration]["infos"]=="data") echo " CHECKED"; ?> ID="data"> <LABEL FOR="data"><?=$texte25;?></LABEL>
	<INPUT TYPE="radio" NAME="infosRestaurer" VALUE="dataonly"<? if ($optionsRestauration[$nomconfigrestauration]["infos"]=="dataonly") echo " CHECKED"; ?> ID="dataonly"> <LABEL FOR="dataonly"><?=$texte26;?></LABEL>
	<INPUT TYPE="radio" NAME="infosRestaurer" VALUE="structure"<? if ($optionsRestauration[$nomconfigrestauration]["infos"]=="structure") echo " CHECKED"; ?> ID="structure"> <LABEL FOR="structure"><?=$texte27;?></LABEL>
      </TD>
     </TR>
     <TR>
      <TD VALIGN="top">
	<?=$texte28;?>:
      </TD>
      <TD>
      	<INPUT TYPE="text" NAME="dossier" VALUE="<?=$optionsRestauration[$nomconfigrestauration]["dossier"];?>"<?=$taille_input1;?>>
      </TD>
     </TR>
     <TR>
      <TD VALIGN="top">
     	<?=$texte44;?>:
      </TD>
      <TD>	
      	<?=str_replace("##serveur##", $bdServeur, $texte15);?>
	<SELECT NAME="tables[]" size="20" multiple="multiple"<?=$taille_select1;?>>
<?
	// Sélection de tous les bases
	$querySql = "SHOW DATABASES";
	//echo $querySql."<BR>";
	$result = $bd->sql_query($querySql);
	if ($bd->sql_numrows($result)>0) {
		while($row = $bd->sql_fetchrow($result)) {
?>
	 <OPTION VALUE="">[<?=$texte45;?>: `<?=$row["Database"];?>`]</OPTION>
<?	
			// Sélection de tous les bases
			$querySql = "SHOW TABLES FROM ".$row["Database"];
			//echo $querySql."<BR>";
			$result1 = $bd->sql_query($querySql);
			if ($bd->sql_numrows($result1)>0) {
				while($row1 = $bd->sql_fetchrow($result1)) {
?>
	 <OPTION VALUE="<?=$row["Database"].".".$row1["Tables_in_".$row["Database"]];?>"<? if (isset($optionsRestauration[$nomconfigrestauration]["tables"][$row["Database"]][$row1["Tables_in_".$row["Database"]]])) echo " SELECTED"; ?>>&nbsp;&nbsp;&nbsp;`<?=$row1["Tables_in_".$row["Database"]];?>`</OPTION>
<?				
				}
			}
		}
	}
	else
	{
?>
	 <OPTION VALUE="">[<?=$texte45;?>: `<?=$bdNomBase;?>`]</OPTION>
<?	
		// Sélection de tous les bases
		$querySql = "SHOW TABLES FROM ".$bdNomBase;
		//echo $querySql."<BR>";
		$result1 = $bd->sql_query($querySql);
		if ($bd->sql_numrows($result1)>0) {
			while($row1 = $bd->sql_fetchrow($result1)) {
?>
	 <OPTION VALUE="<?=$bdNomBase.".".$row1["Tables_in_".$bdNomBase];?>"<? if (isset($optionsRestauration[$nomconfigrestauration]["tables"][$bdNomBase][$row1["Tables_in_".$bdNomBase]])) echo " SELECTED"; ?>>&nbsp;&nbsp;&nbsp;`<?=$row1["Tables_in_".$bdNomBase];?>`</OPTION>
<?				
			}
		}
	}	
?>			
	 </SELECT>
      </TD>
     </TR>
	 <TR>
      <TD VALIGN="top">
     	<?=$texte50;?>:
      </TD>
<?
	// Découpage de la requête 
	if ($optionsRestauration[$nomconfigrestauration]["requete"]!="")
	{
		$requetechamp 		= ereg_replace("^([0-9a-zA-Z_-]{1,})(=|!=|like|notlike|>|>=|<|<=)(.*)$", "\\1", $optionsRestauration[$nomconfigrestauration]["requete"]);
		$requeteoperateur 	= ereg_replace("^([0-9a-zA-Z_-]{1,})(=|!=|like|notlike|>|>=|<|<=)(.*)$", "\\2", $optionsRestauration[$nomconfigrestauration]["requete"]);
		$requetevaleur 		= ereg_replace("^([0-9a-zA-Z_-]{1,})(=|!=|like|notlike|>|>=|<|<=)(.*)$", "\\3", $optionsRestauration[$nomconfigrestauration]["requete"]);
	}
?>	  
      <TD><TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" WIDTH="100%">
	    <TR>
		 <TD WIDTH="33%"><INPUT TYPE="text" NAME="requetechamp" VALUE="<?=$requetechamp;?>"<?=$taille_input1;?>></TD>
		 <TD WIDTH="33%"><SELECT NAME="requeteoperateur"<?=$taille_input1;?>>
		  <OPTION VALUE=""></OPTION>
		  <OPTION VALUE="="<? if ($requeteoperateur=="=") echo " SELECTED"; ?>>=</OPTION>
		  <OPTION VALUE="!="<? if ($requeteoperateur=="!=") echo " SELECTED"; ?>>!=</OPTION>
		  <OPTION VALUE=">"<? if ($requeteoperateur==">") echo " SELECTED"; ?>>></OPTION>
		  <OPTION VALUE=">="<? if ($requeteoperateur==">=") echo " SELECTED"; ?>>>=</OPTION>
		  <OPTION VALUE="<"<? if ($requeteoperateur=="<") echo " SELECTED"; ?>><</OPTION>
		  <OPTION VALUE="<="<? if ($requeteoperateur=="<=") echo " SELECTED"; ?>><=</OPTION>		  
		  <OPTION VALUE="like"<? if ($requeteoperateur=="like") echo " SELECTED"; ?>>LIKE</OPTION>
		  <OPTION VALUE="notlike"<? if ($requeteoperateur=="notlike") echo " SELECTED"; ?>>NOT LIKE</OPTION>
		 </SELECT></TD>
		 <TD WIDTH="33%"><INPUT TYPE="text" NAME="requetevaleur" VALUE="<?=$requetevaleur;?>"<?=$taille_input1;?>></TD>		 		 
		</TR>
	  </TABLE>
	  (ex: ID=1, ID>0, NOM LIKE TOTO)	  
	  </TD>
	 </TR>
     <TR>
      <TD COLSPAN="2"><INPUT TYPE="button" NAME="" VALUE="<?=$texte31;?>"<?=$taille_input1bis;?> onClick="modifierAction('formConfigurationRestauration','sauvegarderConfigurationRestauration'); promptNomConfiguration('formConfigurationRestauration','<?=$nomconfigrestauration;?>');"></TD>
     </TR>
   </TABLE>
   </FIELDSET></TD>
  </TR>  
  </FORM>
</TABLE>
<?
} else {
?>
<CENTER>
<?=$imagePx?> WIDTH="600" HEIGHT="1">
</CENTER>
<BR>
<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" WIDTH="600" ALIGN="center">
  <TR>
   <TD><DIV ALIGN="justify"><?=$texte46;?></DIV></TD>
  </TR>
  <TR>
   <TD>&nbsp;</TD>
  </TR>  
  <TR>
   <TD><DIV ALIGN="justify"><?=$texte47;?></DIV></TD>
  </TR>  
  <TR>
   <TD>&nbsp;</TD>
  </TR>  
  <TR>
   <TD><DIV ALIGN="justify"><?=$texte48;?></DIV></TD>
  </TR>  
  <TR>
   <TD>&nbsp;</TD>
  </TR>  
  <TR>
   <TD><DIV ALIGN="justify"><?=$texte49;?></DIV></TD>
  </TR>
</TABLE>
<BR>
<?	
}

// Affichage du pied de page
include($rep_par_rapport_racine."inc/footer.inc.php"); 
?>