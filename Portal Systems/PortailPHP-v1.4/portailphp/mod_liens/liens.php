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

$chemin = "." ;

if ($action == "Liens-Add")
{
    $res_faq = sql_query("INSERT INTO $BD_Tab_liens (LI_date,LI_aut,LI_mail,LI_rub,LI_suj,LI_lien,LI_cont ) VALUES" .
               " (NOW(),'$auteur','$mail','" . AuAddSlashes($rub) . "','" . AuAddSlashes($titre) . "','" . AuAddSlashes($lien) . "','" .
               AuAddSlashes($contenu) . "')", $sql_id) or die("$Err_Insert") ;
    echo "<strong>$Mod_Membres_Liens_Add</strong><br /><br />" ;
}

$res_liens = sql_query("SELECT * FROM $BD_Tab_liens_cat ORDER BY LIC_nom ASC", $sql_id) ;

echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' /> <strong>$Rub_Liens ($SsRub_Liens_Proposer)</strong><br /><br />" ;

?>
<script language='JavaScript'>
function valider_formulaire(thisForm)
{
  if (thisForm.auteur.value == '')
  {
    alert('<?php echo $Mod_Liens_JS_Auteur ; ?>') ;
    thisForm.auteur.focus() ;
    return false;
  }
  
  if (thisForm.mail.value == '')
  {
    alert('<?php echo $Mod_Liens_JS_mail ; ?>') ;
    thisForm.mail.focus() ;
    return false;
  }
  
  if (thisForm.rub.value == '')
  {
    alert('<?php echo $Mod_Liens_JS_Rubrique ; ?>') ;
    thisForm.rub.focus() ;
    return false;
  }
  
  if (thisForm.titre.value == '')
  {
    alert('<?php echo $Mod_Liens_JS_Titre ; ?>') ;
    thisForm.titre.focus() ;
    return false;
  }
  
  if (thisForm.lien.value == '')
  {
    alert('<?php echo $Mod_Liens_JS_Lien ; ?>') ;
    thisForm.lien.focus() ;
    return false;
  }
  
  if (thisForm.contenu.value == '')
  {
      alert('<?php echo $Mod_Liens_JS_Desc ; ?>') ;
      thisForm.contenu.focus() ;
      return false;
  }
  
  return true;
}
</script>


<form name='formlienadd' method='post' onSubmit='return valider_formulaire(this)'
      action='./index.php?<?php echo $sid ; ?>affiche=Liens-Proposer&action=Liens-Add&App_Theme=<?php echo $_SESSION["App_Theme"] ; ?>'>
  <div align='left'>
    <table border='0' cellpadding='0' cellspacing='0' width='600'>
      <tr>
        <td width='200'><?php echo $Mod_Liens_form_Rubrique ; ?> : (50)</td>
        <td width='400'><select size='1' name='rub'>
        <?php
            while ($row = mysql_fetch_object($res_liens))
            {
                echo "          <option value='" . $row->LIC_nom . "'>" . $row->LIC_nom . "</option>" ;
            }
        ?>    
        </select>
      </td>
   </tr>
   <tr>
     <td width='200'><?php echo $Mod_Liens_form_Auteur ; ?> : (50)</td>
     <td width='400'><input type='text' name='auteur' size='50'></td>
   </tr>
   <tr>
     <td width='200'><?php echo $Mod_Liens_form_Mail ; ?> : (30)</td>
     <td width='400'><input type='text' name='mail' size='30'></td>
   </tr>
   <tr>
     <td width='200'><?php echo $Mod_Liens_form_Titre ; ?> : (50)</td>
     <td width='400'><input type='text' name='titre' size='30'></td>
   </tr>
   <tr>
     <td width='200'><?php echo $Mod_Liens_form_Lien ; ?> : (50)</td>
     <td width='400'><input type='text' name='lien' size='30'></td>
   </tr>
   <tr>
     <td width='200'><?php echo $Mod_Liens_form_Desc ; ?> : (200)</td>
     <td width='400'><input type='text' name='contenu' size='50'></td>
   </tr>
   <tr>
     <td width='200'>&nbsp;</td>
     <td width='400'><input type='submit' value='OK'></td>
   </tr>
 </table>
</div>
</form>