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

$res_res1 = sql_query("SELECT * FROM $BD_Tab_docs_cat ORDER BY DOC_nom ASC", $sql_id) ;

// BUGFIX : n'affiche plus le message si pas IE
if (eregi('msie', $HTTP_USER_AGENT) && !eregi('opera', $HTTP_USER_AGENT))
{
    echo "<script language=\"JavaScript\">\n<!--" ;

    include("./editeur/editor.js.php") ;

    echo "\n-->\n</script>" ;
}

if ($action == "News-Add")
{
    $res_res2 = sql_query("INSERT INTO $BD_Tab_docs (DO_date,DO_aut,Do_mail,DO_rub,DO_suj,DO_cont ) VALUES (NOW(),'" .
                $_SESSION["Admin_Pseudo"] .  "','" . $_SESSION["Admin_Mail"] . "','" . AuAddSlashes($categorie) .
                "','" . AuAddSlashes($sujet) . "','" . AuAddSlashes($EditorValue)."')", $sql_id) or die("$Err_Insert");
    echo "<strong>$Mod_Membres_News_Add</strong><br /><br />" ;
}


?>
<img border='0' src='<?php echo "$chemin/themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' />&nbsp;
<strong><?php echo $Mod_Membres_Rub_News_Add ; ?></strong><br /><br />

<script language='JavaScript'>
  function valider_formulaire(thisForm)
  {
    copyValue();
    
    if (thisForm.EditorValue.value == '')
    {
      alert('<?php echo $Mod_News_JS_Contenu ; ?>');
      thisForm.EditorValue.focus();
      return false ;
    }
    
    if (thisForm.categorie.value == '')
    {
      alert('<?php echo $Mod_News_JS_Categorie ; ?>') ;
      thisForm.categorie.focus() ;
      return false ;
    }
    
    if (thisForm.sujet.value == '')
    {
      alert('<?php echo $Mod_News_JS_Sujet ; ?>');
      thisForm.sujet.focus();
      return false;
    }
    
    return true;
  }
</script>

<form name='fHtmlEditor' method='post' onSubmit='return valider_formulaire(this)'
      action='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Admin&admin=News-Add&action=News-Add'>
<div align='left'>
  <table border='0' cellpadding='0' cellspacing='0' width='800'>
    <tr>
      <td width='200'><?php echo $Mod_News_Form_Categorie ; ?> :</td>
      <td width='600'><select size='1' name='categorie'>");
        <?php
        while($row = mysql_fetch_object($res_res1))
        {
            echo "          <option value='" . $row->DOC_nom . "'>" . $row->DOC_nom . "</option>\n" ;
        }
        ?>
       </select>
     </td>
   </tr>
   <tr>
     <td width='200'><?php echo $Mod_News_Form_Sujet ; ?> : (50)</td>
     <td width='600'><input type='text' name='sujet' size='50'></td>
   </tr>
   <tr>
     <td width='200'><?php echo $Mod_News_Form_Corps ; ?> :</td>
     <td width='600'>
       <?php
       if (eregi('msie', $HTTP_USER_AGENT) && !eregi('opera', $HTTP_USER_AGENT))
       {
           // Internet Explorer
           include "$chemin/editeur/index.inc.php";
           echo "</td></tr>" ;
       }
       else
       {
           ?>
               <textarea rows='15' name='EditorValue' cols='50'></textarea><br />
             </td>
           </tr>
           <?php
       }
       ?>
    <tr>
      <td width='200'>&nbsp;</td>
      <td width='600'><input type='submit' value='OK'></td>
    </tr>
  </table>
</div>
</form>

<?php

?>