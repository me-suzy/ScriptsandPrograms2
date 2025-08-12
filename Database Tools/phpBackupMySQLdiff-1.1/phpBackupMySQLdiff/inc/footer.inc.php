<?
/**universal
 * Fichier contenant la fin des fichiers
 * @author Thomas Pequet
 * @version 1.1
 */

// Fermeture de la connexion à la base
if (isset($bd)) {
	$bd->sql_close();
	unset($bd);
}

if ($page=="1") {
?>
<BR>
<CENTER><? echo $imageFleur.'<BR>'.$imagePx.'WIDTH="600" HEIGHT="2">'; ?></CENTER>
<?
}
?>
</BODY>
</HTML>
