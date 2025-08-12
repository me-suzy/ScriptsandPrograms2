<?php

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Disco search');
LAYERS2();

include ("form.inc.php");//fichier d'affichage des différents éléments du formulaire tel que liste déroulante
require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_trad.inc.php";
require($lang_filename);
?>
<table class="Mtable" border="0" width="100%" cellpadding="0" cellspacing="0">
       <tr>
      	<th><?php echo "$txt_recherche_disco" ?></th>
       </tr>
</table>
<table class="Stable" border="0" style="border-collapse: collapse" bordercolor="#111111" width="100%">
<form name="FormName" action="queries.php" method="GET">
            <tr>
            	<td><?php echo "$txt_artiste" ?> : </td>
                <td><input type="text" name="form_artiste" maxlength="30"></td>
                <td></td>
            </tr>
        	<tr>
				<td><?php echo "$txt_formats" ?> :</td>
				<td>
				<? affichformat(1,0); ?>
    			</td>
            	<td></td>
   			</tr>
			<tr>
    			<td><?php echo "$txt_annee" ?> :</td>
   				<td><select name="form_annee1"><option value="1" selected><?php echo "$txt_exact" ?></option><option value="2">></option><option value="3"><</option></select>&nbsp;
        		<input type="text" name="form_annee2" maxlength="100"></td>
                <td></td>
    		</tr>
            <tr>
                <td><?php echo "$txt_payss" ?> : </td>
                <td>
				<? affichpays(1,0); ?>
				</td>
                <td></td>
   			</tr>
			<tr>
            	<td><?php echo "$txt_ref" ?> : </td>
                <td><input type="text" name="form_ref" maxlength="40"></td>
                <td></td>
            </tr>
            <tr>
            	<td><?php echo "$txt_com" ?> : </td>
                <td><input type="text" name="form_com" maxlength="50"></td>
                <td></td>
            </tr>
            <tr>
            	<td><?php echo "$txt_titre" ?> : </td>
                <td><input type="text" name="form_titres" maxlength="50"></td>
                <td></td>
            </tr>
            <tr>
            	<td align="center" colspan="3"><input type="submit" id="style1" value="<?php echo "$txt_chercher" ?>"></td>
            </tr>
</form>
</table>

<?
BASPAGEWEB();
?>