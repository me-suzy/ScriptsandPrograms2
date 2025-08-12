<?php
/*******************************************************************************
 * Copyright (C) 2002 CLAIRE Cédric cedric.claire@safari-msi.com
 * http://www.portailphp.com/
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

// Ajout d'anti-slashes selon "Magic Quotes"
function AuAddSlashes($chaine)
{
    return get_magic_quotes_gpc() == 1 ? $chaine : AddSlashes($chaine) ;
}
 
function sql_connect($host, $db, $login, $pass)
{
    $sql = @mysql_connect($host, $login, $pass) or die("Impossible to connect the server") ;
    @mysql_select_db($db) or die("Impossible to connect the data-base") ;
    return $sql ;
}

function sql_query($query, $sql, $erreur_die = 1)
{
    if ($res = @mysql_query($query, $sql))
    {
        return $res ;
    }
    else
    {
        if ($erreur_die)
        {
            die("Query: " . $query . " Error: " . mysql_error()) ;
        }
        else
        {
            return 0 ;
        }
    }
}

function is_Windows()
{
    if (strpos(getenv('OS'), 'Win') !== false)
    {
        return true ;
    }

    return false ;
}

/* Vérifie que la librairie GD est chargée */
if (!@extension_loaded("gd"))
{
    if (is_Windows())
    {
        $suffix = '.dll';
    }
    else
    {
        $suffix = '.so';
    }

    @dl("gd" . $suffix);

    if (!@extension_loaded("gd"))
    {
        /* On n'a pas réussi à charger la librairie GD */
        $is_gd = false ;
    }
    else
    {
        $is_gd = true ;
    }

}
else
{
    $is_gd = true ;
}
?>
