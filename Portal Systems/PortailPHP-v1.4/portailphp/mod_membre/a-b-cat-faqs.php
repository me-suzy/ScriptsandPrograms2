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

echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Mod_Membres_Rub_Faq_Cat</strong><br /><br />" ;

if ($action == "Cat-Faqs-Add")
{
    $res_catlien2 = sql_query("INSERT INTO $BD_Tab_faq_cat (FAC_nom ) VALUES ('" . AuAddSlashes($categorie) . "')", $sql_id) or
                    die("$Err_Insert") ;
    echo "<strong>$Mod_Membres_Rub_Faq_Cat_OK</strong><br /><br />" ;
}

$res_catlien1 = sql_query("SELECT * FROM `$BD_Tab_faq_cat` ORDER BY FAC_nom ASC", $sql_id) ;

?>
<script language='JavaScript'>
  function valider_formulaire(thisForm)
  {
    if(thisForm.categorie.value == '')
    {
      alert('<?php echo $Mod_Faq_JS_Nom ; ?>') ;
      thisForm.categorie.focus() ;
      return false ;
    }

    return true ;
  }
</script>

<?php
while($row = mysql_fetch_object($res_catlien1))
{
     echo "<img border='0' src='themes/" . $_SESSION["App_Theme"]. "/ico-cat01.gif' /> " . $row->FAC_nom . "<br />" ;
}
?>

<form name='formcatlien' method='post' onSubmit='return valider_formulaire(this)'
      action='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Admin&admin=Cat-Faqs&action=Cat-Faqs-Add'>
<div align='left'>
  <table border='0' cellpadding='0' cellspacing='0' width='600'>
    <tr>
      <td width='200'><?php echo $Mod_Membres_Rub_Faq_Form_Categorie ; ?> : (30)</td>
      <td width='400'><input type='text' name='categorie' size='50'></td>
    </tr>
    <tr>
      <td width='200'></td>
      <td width='400'><input type='submit' value='OK'></td>
    </tr>
  </table>
</div>
</form>