<?php
/*******************************************************************************
 * Copyright (C) 2004 Martineau Emeric
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
 * La présente Licence Publique Générale n'autorise pas le concessionnaire à
 * incorporer son programme dans des programmes propriétaires. Si votre programme
 * est une bibliothèque de sous-programmes, vous pouvez considérer comme plus
 * intéressant d'autoriser une édition de liens des applications propriétaires
 * avec la bibliothèque. Si c'est ce que vous souhaitez, vous devrez utiliser non
 * pas la présente licence, mais la Licence Publique Générale pour Bibliothèques GNU.
 ***********************************************************************************/
if (!$_SESSION["Admin"]) die("<strong>INTERDIT</strong>") ;

require("./mod_forum/config.inc.php");

echo "<img border='0' src='themes/" . $_SESSION["App_Theme"]. "/ico-puce01.gif' />&nbsp;<strong>$Rub_Forum</strong><br /><br />" ;

if (isset($_POST["delete"]))
{
    if (isset($_GET["id"]) && is_numeric($_GET["id"]))
    {
        $id = $_GET["id"] ;
    }
    else
    {
        $id = -1 ;
    }

    // Regarde s'il s'agit d"un message ou d'une réponse
    $q = mysql_query("SELECT reponse_a_id FROM $BD_Tab_forum WHERE id='$id'") ;
    $rep = mysql_fetch_array($q) ;

    $q = mysql_query("DELETE FROM $BD_Tab_forum WHERE id='$id'") ;

    if ($q)
    {
        echo "<strong>$Mod_Forum_DeleteOK</strong><br />" ;

        if ($rep[0] == 0)
        {
            // Suppression des commentaire
            $q = mysql_query("DELETE FROM $BD_Tab_forum WHERE reponse_a_id='$id'") ;

            if ($q)
            {
                echo "<strong>$Mod_Forum_Delete_com_OK</strong>" ;

            }
            else
            {
                echo "<strong>$Mod_Forum_Delete_com_NOK" . $id . "</strong>" ;
            }
        }
    }
    else
    {
        echo "<strong>$Mod_Forum_DeleteNOK</strong>" ;
    }

}
else
{
    echo $Mod_Forum_Query ;
    ?>
    <form action="index.php?<?php echo $sid ; ?>affiche=Del-Msg-Forum&id=<?php echo $_GET["id"]?>" method="post">
      <input type="submit" value="<?php echo $Mod_Forum_valider ; ?>" name="delete">
    </form>
    <?php
}

?>