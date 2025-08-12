<?php
/*******************************************************************************
 * Copyright (C) 2002 CLAIRE Cédric claced@m6net.fr
 * http://www.yoopla.net/portailphp/
 *
 * Modifié par Martineau Emeric Copyright (C) 2004
 *
 * Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou le
 * modifier conformément aux dispositions de la Licence Publique Générale GNU,
 * telle que publiée par la Free Software Foundation ; version 2 de la licence,
 * ou encore (à votre choix) toute version ultérieure.
 *
 * Ce programme est distribué dans l'espoir qu'il sera utile, mais SANS AUCUNE
 * GARANTIE ; sans même la garantie implicite de COMMERCIALISATION ou
 * D'ADAPTATION A UN OBJET PARTICULIER. Pour plus de détail, voir la Licence
 * Publique Générale GNU .
 *
 * Vous devez avoir reçu un exemplaire de la Licence Publique Générale GNU en
 * même temps que ce programme ; si ce n'est pas le cas, écrivez à la Free
 * Software Foundation Inc., 675 Mass Ave, Cambridge, MA 02139, Etats-Unis.
 *
 * Portail PHP
 * La présente Licence Publique Générale n'autorise pas le concessionnaire à
 * incorporer son programme dans des programmes propriétaires. Si votre programme
 * est une bibliothèque de sous-programmes, vous pouvez considérer comme plus
 * intéressant d'autoriser une édition de liens des applications propriétaires
 * avec la bibliothèque. Si c'est ce que vous souhaitez, vous devrez utiliser non
 * pas la présente licence, mais la Licence Publique Générale pour Bibliothèques GNU.
 ***********************************************************************************/
if (!$_SESSION["Admin"]) die("<strong>INTERDIT</strong>") ;

$res_file1 = sql_query("SELECT * FROM `$BD_Tab_file_cat` ORDER BY FIC_nom ASC", $sql_id) ;

echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Mod_Membres_Rub_Upload_File</strong><br /><br />" ;
?>

<script language='JavaScript'>
  function valider_formulaire(thisForm)
  {
    if (thisForm.nom.value == '')
    {
      alert('<?php echo $Mod_File_JS_Nom ; ?>') ;
      thisForm.nom.focus() ;
      return false ;
    }
    
    if (thisForm.email.value == '')
    {
      alert('<?php echo $Mod_File_JS_Email ; ?>') ;
      thisForm.email.focus() ;
      return false ;
    }
    
    if (thisForm.monfichier.value == '')
    {
      alert('<?php echo $Mod_File_JS_Monfichier ; ?>') ;
      thisForm.monfichier.focus() ;
      return false ;
    }
    
    if (thisForm.titre.value == '')
    {
      alert('<?php echo $Mod_File_JS_Titre ; ?>') ;
      thisForm.titre.focus() ;
      return false ;
    }
    
    return true ;
  }
</script>

<form enctype='multipart/form-data' onSubmit='return valider_formulaire(this)' 
      action='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Admin&admin=File-Upload&action=upload' method='post'>
  <div align='left'>
    <table border='0' cellpadding='0' cellspacing='0' width='600'>
      <tr>
        <td width='155'><?php echo $Mod_File_Etiquette4 ; ?> : (100)</td>
        <td width='445'><input type='text' name='nom' size='50'></td>
      </tr>
      <tr>
        <td width='155'><?php echo $Mod_File_Etiquette5 ; ?> : (25)</td>
        <td width='445'><input type='text' name='email' size='50'></td>
      </tr>
      <tr>
        <td width='155'>
          <input type='hidden' name='MAX_FILE_SIZE' value='<?php echo $Mod_File_Taille ; ?>'>
          <?php echo $Mod_File_Etiquette ; ?> :</td>
        <td width='445'><input type='file' name='monfichier' ></td>
     </tr>
     <tr>
       <td width='155'><?php echo $Mod_File_Etiquette2 ; ?> : (50)</td>
       <td width='445'>
         <select size='1' name='categorie'>

<?php
while ($row = mysql_fetch_object($res_file1))
{
    echo "          <option value='" . $row->FIC_nom . "' selected>" . $row->FIC_nom . "</option>" ;
}
?>

         </select>
       </td>
     </tr>
     <tr>
       <td width='155'><?php echo $Mod_File_Etiquette3 ; ?> : (100)</td>
       <td width='445'><input type='text' name='titre' size='25'></td>
     </tr>
     <tr>
       <td width='155'>&nbsp;</td>
       <td width='445'><input type='submit' value='OK'></td>
     </tr>
   </table>
 </div>
</form>

<?php
if ($action == "upload")
{
    if (move_uploaded_file($_FILES["monfichier"]["tmp_name"], "$chemin/mod_file/upload/" . $_FILES["monfichier"]["name"]))
    {    
        $res_file1 = sql_query("INSERT INTO $BD_Tab_file (FI_date, FI_cat, FI_nom, FI_titre,FI_aut,FI_mail ) VALUES (NOW(), '" .
                 AuAddSlashes($categorie) . "', '" . AuAddSlashes($_FILES["monfichier"]["name"]) . "', '" . AuAddSlashes($titre) . 
                 "', '" . AuAddSlashes($nom) . "', '" . AuAddSlashes($email) . "')", $sql_id) ;
        echo "<strong>$Mod_File_Upload_OK</strong>" ;
    }
    else
    {
        echo "<strong>$Mod_File_Upload_NOK</strong>" ;
    }        
}
?>