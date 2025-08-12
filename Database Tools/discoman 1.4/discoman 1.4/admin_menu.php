<?php

$nom_utilisateur="".@$_GET[name]."";

function affiche_menu($curlevel) {

require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_admin.inc.php";
require($lang_filename);

	echo "<table class=\"Stable\" border=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\">
		<tr>
            <td></td>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
        </tr>
		<tr>
    		<td>".$txt_disques." :</td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_ajouter."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','add.php?curlevel=$curlevel');return document.MM_returnValue\"></td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_modifier."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','record_update.php?curlevel=$curlevel');return document.MM_returnValue\"></td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_effacer."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','record_delete.php?curlevel=$curlevel');return document.MM_returnValue\"></td>
        </tr>";

	if ($curlevel==2 || $curlevel==3) echo "
   		<tr>
    		<td>".$txt_artistes." :</td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_ajouter."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_adds.php?choix=1&curlevel=$curlevel');return document.MM_returnValue\"></td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_modifier."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_update.php?choix=1&curlevel=$curlevel');return document.MM_returnValue\"></td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_effacer."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_delete.php?choix=1&curlevel=$curlevel');return document.MM_returnValue\"></td>
        </tr>
        <tr>
    		<td>".$txt_formats." :</td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_ajouter."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_adds.php?choix=2&curlevel=$curlevel');return document.MM_returnValue\"></td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_modifier."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_update.php?choix=2&curlevel=$curlevel');return document.MM_returnValue\"></td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_effacer."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_delete.php?choix=2&curlevel=$curlevel');return document.MM_returnValue\"></td>
        </tr>
        <tr>
    		<td>".$txt_payss." :</td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_ajouter."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_adds.php?choix=3&curlevel=$curlevel');return document.MM_returnValue\"></td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_modifier."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_update.php?choix=3&curlevel=$curlevel');return document.MM_returnValue\"></td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_effacer."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_delete.php?choix=3&curlevel=$curlevel');return document.MM_returnValue\"></td>
        </tr>";

	if ($curlevel==3) echo "
		<tr>
        	<td>".$txt_utils." :</td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_ajouter."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_adds.php?choix=4&curlevel=$curlevel');return document.MM_returnValue\"></td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_modifier."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_update.php?choix=4&curlevel=$curlevel');return document.MM_returnValue\"></td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_effacer."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_delete.php?choix=4&curlevel=$curlevel');return document.MM_returnValue\"></td>
        </tr>
		<tr>
        	<td>".$txt_infos." :</td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_ajouter."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_adds.php?choix=7&curlevel=$curlevel');return document.MM_returnValue\"></td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_modifier."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_update.php?choix=7&curlevel=$curlevel');return document.MM_returnValue\"></td>
            <td align=center><input type=\"button\" id=\"style1\" value=\"".$txt_effacer."\" onClick=\"MM_goToURL('parent.frames[\'middle\']','admin_delete.php?choix=7&curlevel=$curlevel');return document.MM_returnValue\"></td>
        </tr>";
echo "
		<tr>
            <td></td>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
        </tr>
</table>";

if ($nom_utilisateur != "") echo "Bienvenue $nom_utilisateur !";
}
?>