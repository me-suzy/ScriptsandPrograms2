<?php

include("include/config.php") ;
include("include/fonctions.php") ;
 
session_start();


// Taille de l'image
$x = 77 ;
$y = 20 ;

// Couleurs
$bgColor = array(0xff, 0xff, 0xff) ;
$textColor = array(0, 0, 0) ;
$grilleColor = array(0x92, 0x92, 0x92) ;
$encadrementColor = array(0, 0, 0) ;

// Police
$police = 5 ;

// Génération de l'image
$img = ImageCreate($x, $y) ;

// Génération des couleurs
$cBgColor = ImageColorAllocate($img, $bgColor[0],  $bgColor[1], $bgColor[2]) ;
$cTextColor = ImageColorAllocate($img, $textColor[0],  $textColor[1], $textColor[2]) ;
$cGrilleColor = ImageColorAllocate($img, $grilleColor[0],  $grilleColor[1], $grilleColor[2]) ;
$cEncadrementColor = ImageColorAllocate($img, $encadrementColor[0],  $encadrementColor[1], $encadrementColor[2]) ;

// Si le temp n'a pas expiré
//recupération de la langue choisie
if (get_cfg_var("register_globals") == 1)
{
    $is_set_time = session_is_registered('sc_time') ;
}
else
{
    $is_set_time = isset($_SESSION["sc_time"]) ;
}

if ($is_set_time && (($_SESSION["sc_time"] + 300) > time()))
{
    $code = $_SESSION["sc_code"] ;
    
    // Génére la première position des verticales
    srand((double)microtime()*1000000) ;
    $firstPos = rand(0, 4) ;

    for ($i = $firstPos; $i < $x; $i += 5)
    {
        ImageLine($img, $i, 0, $i, $y, $cGrilleColor) ;
    }

    // Génére la première position des horizontales
    $firstPos = rand(0, 4) ;

    for ($i = $firstPos; $i < $y; $i += 5)
    {
        ImageLine($img, 0, $i, $x, $i, $cGrilleColor) ;
    }

    // Dessine le cadre
    ImageRectangle($img, 0, 0, $x - 1, $y - 1, $cEncadrementColor ) ;    
}
else
{
    $code = "TimeOut" ;
}

// Calucle de la position de la police
$startX = ($x - (strlen($code) * ImageFontWidth($police))) / 2  ;
$startY = ($y - ImageFontHeight($police)) / 2 ;

// Ecriture du code
ImageString($img, $police, $startX, $startY, $code, $cTextColor) ;

if ($registerGlobalsOn)
{
    session_register('_SESSION') ;
}

header("Content-type: image/jpeg") ;
ImageJpeg($img, '', 75) ;
ImageDestroy($img) ;
?>