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

echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Mod_Membres_Rub_Del_Photo</strong><br /><br />" ;

if (isset($id))
{
    $req = " AND PO_uid='$id'" ;
}
else
{    
    $req = "" ;
}

$res_res1 = sql_query("SELECT * FROM $BD_Tab_photos WHERE 1 $req ORDER BY PO_titre ASC", $sql_id) ;

while($row = mysql_fetch_object($res_res1))
{
    if (!isset($id))
    {
        ?>
        <table>
        <tr>
          <td>
            <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=Photo-Del&action=Photo-Del&id=<?php echo $row->PO_uid ; ?>'><img border='0' src='<?php echo $chemin ; ?>/images/ico_trash.gif' alt='<?php echo $Mod_Membres_Rub_News_Del2 ; ?>' /></a>&nbsp;<?php echo $row->PO_titre ; ?></td>
        </tr>
        </table>
        <?php
    }
    
    if ($action == "Photo-Del")
    {
        $res_res2 = sql_query("DELETE FROM $BD_Tab_photos WHERE PO_uid ='$id'", $sql_id) or die("$Err_Supp") ;
        $delete = @unlink("$chemin/mod_photos/photos/$row->PO_code") ;
        $delete = @unlink("$chemin/mod_photos/vignettes/$row->PO_code") ;
        echo "<strong>OK</strong>" ;
    }
}
?>