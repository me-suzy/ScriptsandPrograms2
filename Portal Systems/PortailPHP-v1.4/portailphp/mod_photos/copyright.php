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
$ID = $_GET["ID"] ;

// Paramètres :
$AdresseImage = "./photos/" . $ID . ".jpg";
$Copyright = "Copyright PortailPHP";

// Ouverture de l'image
$Taille = GetImageSize($AdresseImage);
$Image = imagecreatefromjpeg($AdresseImage);

header ("content-Type: image/jpeg") ; 


// Définition de la couleur    
$Noir = imagecolorallocate($Image,0,0,0) ;
// les parametres sont : nom de l'image, indice rouge, indice vert, indice bleu
/*
//Position du texte (en bas à droite)
$ImgBox=ImageTTFBBox($TaillePolice, 0, 1, $Copyright);
*/    
// Tableau contenant la taille du texte
$HPos = ($Taille[0] - 150) ;
// Position du coin gauche
$VPos = $Taille[1] - 20 ;
// Position du coin bas
// Ajout du texte
imagestring($Image, 3, $HPos, $VPos, $Copyright, 0) ;

// Envoi de l'image au navigateur
ImageJPEG($Image) ;

// Destruction de l'espace mémoire des images
imagedestroy($Image) ;
?>