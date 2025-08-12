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

if ($action == "Faq-Add")
{
     $res_addfaq = sql_query("INSERT INTO $BD_Tab_faq (FA_cat,FA_que,FA_rep) VALUES ('" . AuAddSlashes($categorie) .
                   "','" . AuAddSlashes($question) . "','" . AuAddSlashes($reponse)."')", $sql_id) or die("$Err_Insert") ;
     echo "<strong>$Mod_Membres_Rub_Faq_Add_OK</strong><br /><br />" ;
}

$res_res1 = sql_query("SELECT * FROM $BD_Tab_faq_cat WHERE 1 ORDER BY FAC_nom ASC", $sql_id) ;

echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Mod_Membres_Rub_Faq_Add</strong><br /><br />" ;

?>
<script language='JavaScript'>
  function valider_formulaire(thisForm)
  {
    if (thisForm.question.value == '')
    {
       alert('<?php echo $Mod_Faq_JS_Question ; ?>') ;
       thisForm.question.focus() ;
       return false ;
    }
    
    if (thisForm.reponse.value == '')
    {
       alert('<?php echo $Mod_Faq_JS_Reponse ; ?>') ;
       thisForm.reponse.focus() ;
       return false ;
    }
    
    return true ;
}
</script>

<form name='formfaqadd' method='post' onSubmit='return valider_formulaire(this)'
      action='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Admin&admin=Faq-Add&action=Faq-Add'>     
  <div align='left'> 
    <table border='0' cellpadding='0' cellspacing='0' width='600'>
      <tr>
        <td width='200'><?php echo $Mod_Membres_Rub_Faq_Form_Categorie ; ?> : </td>
        <td width='400'><select size='1' name='categorie'>
<?php     
while ($row = mysql_fetch_object($res_res1))
{
    echo "          <option value='" . $row->FAC_nom . "'>" . $row->FAC_nom . "</option>" ;
}
?>
      </tr> 
      <tr>
        <td width='200'><?php echo $Mod_Faq_form_Question ; ?> : (200)</td> 
        <td width='400'><input type='text' name='question' size='70'></td>
      </tr>
      <tr>
        <td width='200'><?php echo $Mod_Faq_form_Reponse ; ?> : (200)</td>
        <td width='400'><input type='text' name='reponse' size='70'></td>
      </tr>
      <tr>
        <td width='200'>&nbsp;</td>
        <td width='400'><input type='submit' value='OK'></td>
      </tr>
    </table>
  </div>
</form>