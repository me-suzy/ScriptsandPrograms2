<?php
//
//  index.php
//	rev010
//  PHP v4.2+
//

//----------------------------------------------------------------
// ¿INSTALACION COMPLETA?
//----------------------------------------------------------------
if(file_exists('install.php')) {
	echo '<SCRIPT>window.location.href="install.php";</SCRIPT>';
	exit();
}

//----------------------------------------------------------------
// PARAMETROS DE ENTRADA
//----------------------------------------------------------------
if(isset($_POST['m'])) $modo=$_POST['m']; else $modo=$_GET['m'];
if(isset($_POST['s'])) $submodo=$_POST['s']; else $submodo=$_GET['s'];

//----------------------------------------------------------------
// CODIGO NECESARIO
//----------------------------------------------------------------
include('data/inc_config.php');
include('code/inc_functions.php');
include('lang/'.$gIdioma.'.php');
include('code/class_db_'.$gTipoDB.'.php');
include('code/class_topo.php');
include('code/class_misc.php');

//----------------------------------------------------------------
// DATOS NECESARIOS
//----------------------------------------------------------------
$tiempo_usado=ej3Time();
if(!isset($modo)) $modo='top';
if(!isset($submodo)) $submodo='html';

//----------------------------------------------------------------
// CONTENIDO SEGUN $modo y $submodo (por defecto 'inicio')
//----------------------------------------------------------------
include('modes/'.$modo.'/'.$submodo.'.php');

//----------------------------------------------------------------
// SALIDA (general)
//----------------------------------------------------------------
echo 'ERROR: TOPo can\'t render the page.';

?>