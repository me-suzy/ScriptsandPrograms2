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
echo "<br />" ;

if($erreur==1)
{
    echo "<div align='center'><strong>$Mod_Membres_Err_Field</strong><br /><br /></div>" ;
}

// Si la bibliothèque GD est disponible, on génère un Security Code
if ($is_gd)
{
    // Génére le code de sécurité
    srand((double)microtime() * 1000000) ;
    $tab = "0123456789" ;
    $_SESSION["sc_code"] = "" ;

    for ($i = 0; $i < 6; $i++)
    {
        $_SESSION["sc_code"] .= $tab[rand(0, 9)] ;
    }

    $tab = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN" ;
    $_SESSION["sc_field_name"] = "" ;

    for ($i = 0; $i < 6; $i++)
    {
        $_SESSION["sc_field_name"] .= $tab[rand(0, strlen($tab))] ;
    }

    $_SESSION["sc_time"] = time() ;
}

echo "
<form action='$chemin/mod_membre/script-verif.php?" . $sid . "' method='post'>
<table align='center' border='0'>
  <tr>
    <td><img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;$Mod_Membres_Pseudo :</td>
    <td><input type='text' name='login' maxlength='250'></td>
  </tr>
  <tr>
    <td><img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;$Mod_Membres_Password :</td>
    <td><input type='password' name='pass' maxlength='10'>
    <input type='hidden' name='App_Theme' value='" . $_SESSION["App_Theme"] . "'>
    </td>
  </tr>" ;

if ($is_gd)
{
    echo "  <tr>
    <td><img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;$Mod_Membres_SC :</td>
    <td><img src='sc.php?$sid' /></td>
  </tr>
  <tr>
    <td colspan='2'>
    $Mod_Membres_SC1 :<br />
    <input type='text' name='" . $_SESSION["sc_field_name"] . "' value='' />
    </td>
  </tr>" ;
}

echo "  <tr>
    <td colspan='2' align='center'><input type='submit' value='login'></td>
  </tr>
</table>
</form>" ;
?>

