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

echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Mod_Membres_Rub_News_Edit</strong><br /><br />" ;

if (isset($_GET["id"]))
{
    // BUGFIX : n'affiche plus le message si pas IE    
    if (eregi('msie', $HTTP_USER_AGENT) && !eregi('opera', $HTTP_USER_AGENT))
    {
        echo "<script language=\"JavaScript\">\n<!--" ;

        include("./editeur/editor.js.php") ;

        echo "\n-->\n</script>" ;
    }
    
    $req =" AND DO_uid='" . $_GET["id"] . "' ";

    $res_res1 = sql_query("SELECT * FROM $BD_Tab_docs WHERE 1 $req ORDER BY DO_date DESC,DO_suj ASC", $sql_id);

    while($row = mysql_fetch_object($res_res1))
    {
        if (isset($_GET["id"]))
        {
        ?>
        <form name='fHtmlEditor' method='post' onsubmit='copyValue();'
              action='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Admin&admin=News-Edit&id=<?php echo $row->DO_uid ; ?>&action=News-Edit'>
          <div align='left'>
            <table border='0' cellpadding='0' cellspacing='0' width='600'>
              <tr>
                <td width='100%'>
                  <img border='0' src='<?php echo $chemin ; ?>/images/ico_zip.gif' />&nbsp;
                  <strong><?php echo $row->DO_rub ; ?></strong> :
                  <input type='text' name='sujet' value ='<?php echo $row->DO_suj ; ?>' size='50'>
                </td>
              </tr>
              <tr>
                <td width='100%'><br />
                 <img border='0' src='<?php echo $chemin ; ?>/images/ico_zip.gif' />&nbsp;
                 <i>( <?php
                      echo $Mod_News_PostPar . " " . $row->DO_aut . ", " . $Mod_News_Le . " " . $row->DO_date .
                           ", " . $row->DO_lect . " " . $Mod_News_Arch_Lectures ;
                      ?>      
                 )</i>
                 <br /><br /><br /><br />
               </td>
             </tr>
             <tr>
               <td width='100%'>
                 <?php
                 $valeur = $row->DO_cont ;

                 if (eregi('msie', $HTTP_USER_AGENT) && !eregi('opera', $HTTP_USER_AGENT))
                 {                 
                     // Internet Explorer
                     include "$chemin/editeur/index.inc.php" ;
                     echo "</td></tr>" ;
                 }
                 else
                 {
                     // BUGFIX : pas d'affichage de valeur
                     //echo "<textarea rows='15' name='EditorValue' valur='$valeur' cols='50'></textarea><br />"
                     echo "<textarea rows='15' name='EditorValue' cols='50'>$valeur</textarea><br />" .
                          "</td>\n</tr>" ;
                 }
                 
                 echo "<tr>\n" .
                      "  <td align='left'><input type='submit' value='OK'></td>\n" .
                      "</tr>" ;
    
                if ($action == "News-Edit")
                {
                    $res_res2 = sql_query("UPDATE $BD_Tab_docs SET DO_suj='" . AuAddSlashes($sujet) . "',DO_cont='" .
                                AuAddSlashes($EditorValue) . "' WHERE DO_uid ='" . $_GET["id"] . "'", $sql_id) or die("$Err_Modif") ;
                    echo "<strong>OK</strong>" ;
                }    
        }
    }
        
    echo "</table>" ;
    echo "</div>" ;
}
else
{
    echo $Mod_Membres_Rub_News ;
}
?>