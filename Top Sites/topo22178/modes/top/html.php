<?php
//
//  modes/top/html.php
//  rev009
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
if(isset($_POST['p'])) $p=$_POST['p']; else $p=$_GET['p'];
if(isset($_POST['c'])) $c=$_POST['c']; else $c=$_GET['c'];

//----------------------------------------------------------------
// DATOS NECESARIOS
//----------------------------------------------------------------
$noTop=0;

//Determinación de la página a mostrar
if(!isset($p)) $p=1;

//Determinación de la categoría a mostrar
$prefijo='';
if($gCategorias) {
	$categorias=new Categorias;
	if($categorias->Existe($c)) {
		$prefijo=$c;	//Usamos prefijos.
	} else {
		$prefijo='0000000000.0000';	//Usamos prefijos.
	}
}

//----------------------------------------------------------------
// CONTENIDO
//----------------------------------------------------------------
//Carga de los datos de data/info.dat
$infoDAT=new Info($prefijo);

//Comprobamos si hay que generar un nuevo top o no
if($infoDAT->ultActualizacion < abs(time()-$gTiempoActualizar)) {
	$indice=new Index();
	$lista=$indice->Listar($prefijo);
	if(is_array($lista)) {
		$top=new Constructor($lista,$gCriterioOrden,abs(time()-$gTiempoResetear),$prefijo);
		$top->GenerarPaginas($gTema,$gTopNombre,$gConBanner,$gNumBloques,$gWebsPorBloque,$gPrecargarBanner);
		//Recargamos la informacion de info.dat puesto que ha sido modificada.
		$infoDAT=new Info($prefijo);
		if($gDebug) {
			echo "<hr>";
			echo "<br>\$infoDAT->ultActualizacion < abs(time()-\$gTiempoActualizar)";
			echo "<br>time() - \$gTiempoActualizar = ".time()." - ".$gTiempoActualizar." = ".(time()-$gTiempoActualizar);
			echo "<br>\$infoDAT->paginas = ".$infoDAT->paginas;
			echo "<br>\$infoDAT->webs = ".$infoDAT->webs;
			echo "<br>\$infoDAT->ultActualizacion = ".$infoDAT->ultActualizacion;
			echo "<br>\$infoDAT->ultReset = ".$infoDAT->ultReset;
			echo "<hr>";
		}		
	} else {
		$noTop=1;
	}
}

//Nos aseguramos de que estan generadas las páginas (antes de mostrarlas)
if(!file_exists('cache/'.$prefijo.'page1.php')) {
	$indice=new Index();
	$lista=$indice->Listar($prefijo);
	if(is_array($lista)) {
		$top=new Constructor($lista,$gCriterioOrden,abs(time()-$gTiempoResetear),$prefijo);
		$top->GenerarPaginas($gTema,$gTopNombre,$gConBanner,$gNumBloques,$gWebsPorBloque,$gPrecargarBanner);
		if($gDebug) {
			echo "<hr>";
			echo "<br>!file_exists('cache/'.$prefijo.'page1.php')";
			echo "<br>time() - \$gTiempoActualizar = ".time()." - ".$gTiempoActualizar." = ".(time()-$gTiempoActualizar);
			echo "<br>\$infoDAT->paginas = ".$infoDAT->paginas;
			echo "<br>\$infoDAT->webs = ".$infoDAT->webs;
			echo "<br>\$infoDAT->ultActualizacion = ".$infoDAT->ultActualizacion;
			echo "<br>\$infoDAT->ultReset = ".$infoDAT->ultReset;
			echo "<hr>";
		}		
	} else {
		$noTop=1;
	}
}

//En caso de que no exista el top o este vacio
if($noTop) {
	$HTML.='<p align="center" class="title">'.$gTopNombre.'</p>';
	$HTML.='<table align="center" border="0" class="0" cellspacing="1" cellpadding="10">';
	$HTML.='<tr class="1"><td align="center" valign="middle"><span class="text">';
	$HTML.='<a href="index.php?m=members&s=html&t=join&paso=1" target="_blank">'.$_Join_.'</a>';
	$HTML.='<hr><a href="index.php?m=admin&s=html" target="_blank">'.$_Webmaster_.'</a>';
    $HTML.='</span></td></tr>';
    $HTML.='</table>';
	$HTML.='<p align="center" class="title">'.$_EmptyIndex_.'</p>';
	include('code/inc_header.php');
    echo $HTML;
	$gRendimiento=0;
    include('code/inc_footer.php');
    exit();
}

//Recargamos datos antes de mostrar las páginas generadas.
//$infoDAT actualizado para construir bien las etiquetas en caso de actualización.
unset($infoDAT);
$infoDAT=new Info($prefijo);
if($infoDAT->paginas < $p) $p=1;
if($gVerBannerSup==2 OR $gVerBannerMed==2 OR $gVerBannerInf==2) $autoBanner=AutoBanner(2+$gNumBloques-1);

//----------------------------------------------------------------
// CONTADOR ONLINE
//----------------------------------------------------------------
if($gUsuariosOnline) {
	//Captura de datos
	$ip=capturarIP();
	$onlineData['ip']=$ip[0];
	$onlineData['timestamp']=time();
	$onlineData['pais']=capturarPais(gethostbyaddr($ip[0]));
	if($onlineData['pais']=='unknow') $onlineData['pais']=capturarPais(gethostbyaddr($ip[1])); 
	//Almacenamiento de datos
	$online=new Online('data/online.dat');
	$online->Insert($onlineData);
	$online->Refresh(666);
	$online->Save();
}

//----------------------------------------------------------------
// SALIDA (propia)
//----------------------------------------------------------------
ob_start();
ob_implicit_flush(0);
include('code/inc_header.php');
if($gVerBannerSup==1) include("data/bannertop.htm");
if($gVerBannerSup==2) echo $autoBanner[0]."<br>";
include('cache/'.$prefijo.'page'.$p.'.php');
if($gVerBannerInf==1) include("data/bannerbottom.htm");
if($gVerBannerInf==2) echo "<br>".$autoBanner[$gNumBloques];
include('code/inc_footer.php');
ej3Rendimiento($gCompresion,$gRendimiento,'{PERFORMANCE}');
exit();

?>