<?php // MISE A JOUR DE LA TABLE INFOS
require("config.inc.php");
require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Infos update');
LAYERS2();
include("link.inc.php");

$id_infos="".@$_GET[id_infos]."";
$texte="".@$_GET[form_texte]."";
$sujet="".@$_GET[form_sujet]."";
$curlevel="".@$_GET[curlevel]."";
$choix="".@$_GET[choix]."";

if ($texte !="") {

	$update = mysql_query("
		UPDATE
			disco_infos
		SET
        	sujet='$sujet',
        	texte='$texte'
		WHERE
			id_infos LIKE '$id_infos'");

	if ($update) {

	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>Résultat</th>
       		</tr>
         </table>
         <table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
            	<td>Fiche infos <b>$id_infos</b> mise à jour.</td>
            </tr>
        </table></div>\n";

LAYERPAGEDEB2();

	echo "
        <table width='100%'>
			<tr>
        		<td align='left'><a href=\"admin_update.php?curlevel=$curlevel&choix=$choix\">[<< back to admin update page] </a></td>
    		</tr>
		</table>";

LAYERPAGEFIN();
	   	}
   }

else {

     $query = "SELECT
              	id_infos,
                date,
                sujet,
                texte,
                image
              FROM
              	disco_infos
              WHERE
              	id_infos LIKE '$id_infos'";

	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_assoc($result);
    $str = stripslashes($row['sujet']);

	echo "
		<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
    			<th>Infos Result</th>
    		</tr>
  		</table>";

    LAYERS4();

    echo "<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
		<FORM METHOD=\"GET\" ACTION=\"$PHP_SELF\">
    		<tr>
    			<td width='20%'>Id infos :</td>
        		<td>".$row['id_infos']."</td>
        	</tr>
    		<tr>
    			<td width='20%'>Date :</td>";
	if ($lang=="fr") {
   		list($year, $month, $day) = explode("-", $row['date']);
   		$row['date'] = "$day/$month/$year";
        }
      echo "	<td>".$row['date']."</td>
    		</tr>
    		<tr>
    			<td width='20%'>Sujet :</td>
        		<td><input type=\"text\" name=\"form_sujet\" value=`$str`></td>
    		</tr>
			<tr>
            	<td width='20%'>Image :</td>";
                if ($row['image']!="")	echo "
                <td><div align=\"center\"><img src=\"images_infos/".$row['id_infos'].$row["image"]."\" width=\"200\" alt=\"\" border=0 style=\"cursor:move;\" onClick='location=\"title2.php?valeur=".$row["id_infos"].$row["image"]."&upload_dir=images_infos&mode=1\"'></div></td>
            <tr>
            	<td><div align=\"center\"><input type=\"button\" id=\"style1\" value=\"Raffraîchir\" onClick='javascript:window.location.reload()'></div></td>
                <td><div align=\"center\"><input type=\"button\" id=\"style1\" value=\"Changer\" onClick='location=\"title2.php?valeur=".$row['id_infos'].$row["image"]."&id_infos=".$row['id_infos']."&upload_dir=images_infos&mode=6\"'></div></td>
            </tr>
            <tr>
            	<td></td>
            	<td><div align=\"center\"><input type=\"button\" id=\"style1\" value=\"Supprimer\" onClick='location=\"title2.php?valeur=".$row['id_infos'].$row["image"]."&id_infos=".$row['id_infos']."&upload_dir=images_infos&mode=8\"'></div></td>";
                else echo "
                <td></td>
            </tr>
            <tr>
            	<td><div align=\"center\"><input type=\"button\" id=\"style1\" value=\"Raffraîchir\" onClick='javascript:window.location.reload()'></div></td>
                <td><div align=\"center\"><input type=\"button\" id=\"style1\" value=\"Ajouter\" onClick='location=\"title2.php?id_infos=$id_infos&upload_dir=images_infos&mode=7\"'></div></td>";
		echo "
            </tr>
    		<tr>
    			<td>Infos :</td>";
                $row['texte']=eregi_replace("\n","\n<br>",$row['texte']);
        echo "	<td><textarea name=\"form_texte\" cols=\"40\" rows=\"8\">".$row['texte']."</textarea></td>
    		</tr>
			<tr>
				<td colspan=2><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"Update\" name=\"Add\"></div></td>
    		</tr>
            <input name=\"id_infos\" type=\"hidden\" value=".$row['id_infos'].">
        	<input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
            <input name=\"choix\" type=\"hidden\" value=\"$choix\">
		</FORM>
		</table></div></div>";

LAYERPAGEDEB3(-1);
}
mysql_close($link);
BASPAGEWEB2();
?>