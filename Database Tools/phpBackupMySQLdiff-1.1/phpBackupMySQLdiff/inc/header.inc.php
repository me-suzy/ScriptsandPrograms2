<?
/**universal
 * Fichier contenant l'en-tête des fichiers
 * @author Thomas Pequet
 * @version 1.0 
 */
?>
<HTML>
<HEAD>
<TITLE><?=$nomSite." ".$versionSite." > ".${"titre".$page};?></TITLE>
<?=$metas;?>
<LINK REL="stylesheet" HREF="<?=$rep_par_rapport_racine.$ficStyle;?>" TYPE="text/css">
<SCRIPT LANGUAGE="JavaScript" SRC="<?=$rep_par_rapport_racine.$ficScript;?>"></SCRIPT>
</HEAD>
<BODY>
<?
if ($page=="1") {
?>
<CENTER>
<?=$imageLogo;?>
</CENTER>
<?
} else {
?>
<B><?=$nomSite." > ".${"titre".$page};?></B>
<?
}
?>
<BR>
