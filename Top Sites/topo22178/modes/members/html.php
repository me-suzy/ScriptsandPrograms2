<?php
//
//  modes/members/html.php
//  rev010
//  PHP v4.2+
//

//----------------------------------------------------------------
// COMPROBACION DEL CONTEXTO
//----------------------------------------------------------------
if(!stristr($_SERVER['PHP_SELF'],'index.php')) {
	echo $_SERVER['PHP_SELF'];
	echo '<SCRIPT>window.location.href="index.php";</SCRIPT>';
	exit();
}

//----------------------------------------------------------------
// PARAMETROS DE ENTRADA
//----------------------------------------------------------------
if(isset($_POST['t'])) $tipo=$_POST['t']; else $tipo=$_GET['t'];
if(isset($_POST['paso'])) $paso=$_POST['paso']; else $paso=$_GET['paso'];
if(isset($_POST['ID'])) $ID=$_POST['ID']; else $ID=$_GET['ID'];

//----------------------------------------------------------------
// CONTENIDO SEGUN $tipo
//----------------------------------------------------------------
//Clases para acceder a la BD.
$indice=new Index('');
if($gCategorias) {
	$categorias=new Categorias;
}

$HTML='<SCRIPT>window.location.href = "index.php";</SCRIPT>';

include('modes/members/'.$tipo.'.php');

//----------------------------------------------------------------
// SALIDA (propia)
//----------------------------------------------------------------
if($gVerBannerSup==2 OR $gVerBannerMed==2 OR $gVerBannerInf==2) $autoBanner=AutoBanner(2+$gNumBloques-1);

include('code/inc_header.php');
if($gVerBannerSup==1) include("data/bannertop.htm");
if($gVerBannerSup==2) echo $autoBanner[0]."<br>";
echo '<p align="center" class="title">'.$gTopNombre.'</p>';
if($gReglas AND $tipo=='join') include('data/rules.htm');
echo '<br>'.$HTML.'<br>';
if($gVerBannerInf==1) include("data/bannerbottom.htm");
if($gVerBannerInf==2) echo "<br>".$autoBanner[$gNumBloques];
$gRendimiento=0;
include('code/inc_footer.php');
exit();

?>