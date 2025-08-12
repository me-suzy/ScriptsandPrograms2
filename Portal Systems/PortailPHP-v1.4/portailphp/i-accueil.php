<?php
/*******************************************************************************
 * Copyright (C) 2002 CLAIRE Cédric cedric.claire@safari-msi.com
 * http://www.portailphp.com/
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

echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$App_Me_Titre</strong> $App_version<br /><br />" ;

if (file_exists("$chemin/licence.php"))
{
    include("include/edito/" . $_SESSION["App_Langue"]) ;

    if ($App_Affiche_News)
    {
        $affiche = "News-plusrecents" ;
        $_SESSION["News_Ordre"] = "DO_date DESC,DO_suj ASC" ;

        include("$chemin/mod_news/index.php") ;
        
        echo "<br />" ;
    }
    
    if ($App_Affiche_File)
    {
        $affiche = "File-plusrecents" ;
        $_SESSION["File_Ordre"] = "FI_date DESC,FI_titre ASC" ;

        include("$chemin/mod_file/index.php") ;
        
        echo "<br />" ;
    }
    
    if ($App_Affiche_Liens)
    {
        $affiche = "Liens-plusrecents" ;
        $_SESSION["Liens_Ordre"] = "LI_date DESC,LI_suj ASC" ;

        include("$chemin/mod_liens/index.php") ;
        
        echo "<br />" ;
    }
     
    if ($App_Affiche_Search)
    {
        include("$chemin/mod_search/index.php") ;
        echo "<br />" ;
    }
    
    if ($App_Affiche_publicite)
    {
        include("$chemin/mod_publicite/index.php") ;
    }
}
?>
