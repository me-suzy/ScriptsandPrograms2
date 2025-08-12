<?php
//
//  install.php
//	rev006
//  PHP v4.2+
//

//Adaptacion de variables para PHP v4.2+
if(isset($_POST['paso'])) $paso=$_POST['paso']; else $paso=$_GET['paso'];
if(isset($_POST['gAdminLogin'])) $gAdminLogin=$_POST['gAdminLogin']; else $gAdminLogin=$_GET['gAdminLogin'];
if(isset($_POST['gAdminPass'])) $gAdminPass=$_POST['gAdminPass']; else $gAdminPass=$_GET['gAdminPass'];
//--------------------------------------

//----------------------------------------------------------------
// DATOS NECESARIOS
//----------------------------------------------------------------
$gVer='2.2';
$gRev='178';
if(!isset($paso)) $paso=0;

//----------------------------------------------------------------
// CONTENIDO
//----------------------------------------------------------------
if($paso==0) {	//$paso==0 (por defecto)
	//----------------------------------------------------------------
	// MENSAJE DE BIENVENIDA
	//----------------------------------------------------------------
	$HTML.='<table width="300" align="center" cellpadding="10" cellspacing="1" border="0">';
	$HTML.='<form action="install.php" method="post" onSubmit="submitOnce(this);">';
	$HTML.='<tr class="header1"><td class="title" align="center">Welcome to EJ3 TOPo</td></tr>';
	$HTML.='<tr class="1"><td align="center">';
	$HTML.='<br>Hello, welcome to the installation program of <b><nobr>EJ3 TOPo v'.$gVer.'</nobr></b>, a avanced PHP script to create and manage a complete top-list.';
	$HTML.='<br><br>Follow the next simple steps to install the script in your web server.<br>&nbsp;';
	$HTML.='</td></tr>';
	$HTML.='<tr class="header0"><td align="center">';
	$HTML.='<input type="hidden" name="paso" value="1">';
	$HTML.='<input type="submit" class="button" value="Continue">';
	$HTML.='</td></tr>';
	$HTML.='</form></table>';
}
if($paso==1) {
	//----------------------------------------------------------------
	// CREACION DE LA ESTRUCTURA DE DIRECTORIOS
	//----------------------------------------------------------------
	if(ini_get('safe_mode')) {
		if(is_dir('cache') AND is_writable('cache') AND is_dir('data') AND is_writable('data')) {
			$auxHTML.='<tr class="1"><td align="center">';
			$auxHTML.='<br><span class="title">Structure of Directories Successfully Created</span>';
			$auxHTML.='<br><br>Click in <b>Next</b> button to continue with the installation.<br>&nbsp;';
			$auxHTML.='</td></tr>';
			$auxHTML.='<tr class="header0"><td colspan="2" align="right">';
			$auxHTML.='<input type="hidden" name="paso" value="2">';
			$auxHTML.='<input type="submit" class="button" value="Next >>">';
			$auxHTML.='</td></tr>';
		} else {
			$auxHTML.='<tr class="1"><td align="center">';
			$auxHTML.='<br><span class="title">Your server is running in <i>Safe Mode</i></span>';
			$auxHTML.='<br><br>Before continue you must create <b>data</b> and <b>cache</b> directories manually in your server, set file permissions of <b>data</b> and <b>cache</b> directories to <b>777</b> manually and click <b>Try Again</b> button.<br>&nbsp;';
			$auxHTML.='</td></tr>';
			$auxHTML.='<tr class="header0"><td colspan="2" align="right">';
			$auxHTML.='<input type="hidden" name="paso" value="1">';
			$auxHTML.='<input type="submit" class="button" value="Try Again">&nbsp;&nbsp;';
			$auxHTML.='</td></tr>';
		}
	} else {
		@umask(0);
		@mkdir('cache',0777);
		@mkdir('data',0777);
		@chmod("cache",0777);
		@chmod("data",0777);
		
		if(!is_dir('cache')) {
			$auxHTML.='<tr class="1"><td align="center">';
			$auxHTML.='<br><span class="title">Can&#39;t Create <b>data</b> and <b>cache</b> directories</span>';
			$auxHTML.='<br><br>Before continue create <b>data</b> and <b>cache</b> directories manually in your server and click <b>Try Again</b> button.<br>&nbsp;';
			$auxHTML.='</td></tr>';
			$auxHTML.='<tr class="header0"><td colspan="2" align="right">';
			$auxHTML.='<input type="hidden" name="paso" value="1">';
			$auxHTML.='<input type="submit" class="button" value="Try Again">&nbsp;&nbsp;';
			$auxHTML.='</td></tr>';
		} elseif(!is_writable('cache')) {
			$auxHTML.='<tr class="1"><td align="center">';
			$auxHTML.='<br><span class="title">Can&#39;t Change directory Permissions to 777</span>';
			$auxHTML.='<br><br>Before continue set file permissions of <b>data</b> and <b>cache</b> directories to <b>777</b> manually and click <b>Try Again</b> button.<br>&nbsp;';
			$auxHTML.='</td></tr>';
			$auxHTML.='<tr class="header0"><td colspan="2" align="right">';
			$auxHTML.='<input type="hidden" name="paso" value="1">';
			$auxHTML.='<input type="submit" class="button" value="Try Again">&nbsp;&nbsp;';
			$auxHTML.='</td></tr>';
		} else {
			$auxHTML.='<tr class="1"><td align="center">';
			$auxHTML.='<br><span class="title">Structure of Directories Successfully Created</span>';
			$auxHTML.='<br><br>Click in <b>Next</b> button to continue with the installation.<br>&nbsp;';
			$auxHTML.='</td></tr>';
			$auxHTML.='<tr class="header0"><td colspan="2" align="right">';
			$auxHTML.='<input type="hidden" name="paso" value="2">';
			$auxHTML.='<input type="submit" class="button" value="Next >>">';
			$auxHTML.='</td></tr>';
		}
	}
	
	$HTML.='<table width="400" align="center" cellpadding="10" cellspacing="1" border="0">';
	$HTML.='<form action="install.php" method="post" onSubmit="submitOnce(this);">';
	$HTML.='<tr class="header1"><td class="title" align="center">EJ3 TOPo Installation&nbsp;&nbsp;|&nbsp;&nbsp;Step <sup>1</sup><big>/</big><small>5</small></td></tr>';
	$HTML.='<tr class="header0"><td class="minititle" align="center">Creating Structure of Directories...</td></tr>';
	$HTML.=$auxHTML;
	$HTML.='</form></table>';
}
if($paso==2) {
	//----------------------------------------------------------------
	// CREACION DE LOS ARCHIVOS NECESARIOS
	//----------------------------------------------------------------
	$fp=@fopen('data/index.dat','w');
    $ok=@fwrite($fp,'');
    @fclose($fp);
	@chmod('data/index.dat',0666);
	
	$fp=@fopen('data/categories.dat','w');
    $ok=@fwrite($fp,'');
    @fclose($fp);
	@chmod('data/categories.dat',0666);

	$fp=@fopen('data/online.dat','w');
    $ok=@fwrite($fp,'');
    @fclose($fp);
	@chmod('data/online.dat',0666);
	
	$fp=@fopen('data/notice.htm','w');
    $ok=@fwrite($fp,'<table align=center border=0 bgcolor=Black cellspacing=1 cellpadding=5><tr class=1><td class=text align=center><span class=title>Notice</span><br>Here you can write your notices.</td></tr></table>');
    @fclose($fp);
	@chmod('data/notice.htm',0666);

	$fp=@fopen('data/rules.htm','w');
    $ok=@fwrite($fp,'<table align=center border=0 bgcolor=Black  cellspacing=1 cellpadding=5><tr class=1><td class=text align=center><span class=title>Rules</span><li>Rule one</li><li>Rule two</li><li>Rule three</li><li>...</li></td></tr></table>');
    @fclose($fp);
	@chmod('data/rules.htm',0666);

	$fp=@fopen('data/welcome.htm','w');
    $ok=@fwrite($fp,"Welcome to TOPo TopList\n{ACCOUNT_INFO}");
    @fclose($fp);
	@chmod('data/welcome.htm',0666);

	$fp=@fopen('data/bannertop.htm','w');
    $ok=@fwrite($fp,'<CENTER>Here you can write your HTML code for top banner</CENTER>');
    @fclose($fp);
	@chmod('data/bannertop.htm',0666);

	$fp=@fopen('data/bannermiddle.htm','w');
    $ok=@fwrite($fp,'<CENTER>Here you can write your HTML code for middle banner</CENTER>');
    @fclose($fp);
	@chmod('data/bannermiddle.htm',0666);
	
	$fp=@fopen('data/bannerbottom.htm','w');
    $ok=@fwrite($fp,'<CENTER>Here you can write your HTML code for bottom banner</CENTER>');
    @fclose($fp);
	@chmod('data/bannerbottom.htm',0666);
	
	if($ok) {
		$auxHTML.='<tr class="1"><td align="center">';
		$auxHTML.='<br><span class="title">Data Files Successfully Created</span>';
		$auxHTML.='<br><br>Click in <b>Next</b> button to continue with the installation.<br>&nbsp;';
		$auxHTML.='</td></tr>';
		$auxHTML.='<tr class="header0"><td colspan="2" align="right">';
		$auxHTML.='<input type="hidden" name="paso" value="3">';
		$auxHTML.='<input type="submit" class="button" value="Next >>">';
		$auxHTML.='</td></tr>';
	} else {
		$auxHTML.='<tr class="1"><td align="center">';
		$auxHTML.='<br><span class="title">Can&#39;t Create Data Files</span>';
		$auxHTML.='<br><br>Change manually file permissions of the directory <b>/data</b> to <b>777</b> and click <b>Try Again</b> button.<br>&nbsp;';
		$auxHTML.='</td></tr>';
		$auxHTML.='<tr class="header0"><td colspan="2" align="right">';
		$auxHTML.='<input type="hidden" name="paso" value="2">';
		$auxHTML.='<input type="submit" class="button" value="Try Again">&nbsp;&nbsp;';
		$auxHTML.='</td></tr>';
	}
	$HTML.='<table width="400" align="center" cellpadding="10" cellspacing="1" border="0">';
	$HTML.='<form action="install.php" method="post" onSubmit="submitOnce(this);">';
	$HTML.='<tr class="header1"><td class="title" align="center">EJ3 TOPo Installation&nbsp;&nbsp;|&nbsp;&nbsp;Step <sup>2</sup><big>/</big><small>5</small></td></tr>';
	$HTML.='<tr class="header0"><td class="minititle" align="center">Creating data files...</td></tr>';
	$HTML.=$auxHTML;
	$HTML.='</form></table>';
}
if($paso==3) {
	//----------------------------------------------------------------
	// DATOS DE ACCESO AL PANEL DE CONTROL.
	//----------------------------------------------------------------
	$HTML.='<table width="400" align="center" cellpadding="5" cellspacing="1" border="0">';
	$HTML.='<form action="install.php" method="post" onSubmit="submitOnce(this);">';
	$HTML.='<tr class="header1"><td colspan="2" class="title" align="center">EJ3 TOPo Installation&nbsp;&nbsp;|&nbsp;&nbsp;Step <sup>3</sup><big>/</big><small>5</small></td></tr>';
	$HTML.='<tr class="header0"><td colspan="2" class="minititle" align="center">Enter the data to access to TOPo Control Panel.</td></tr>';
	$HTML.='<tr class="1"><td align="right" valign="top">';
	$HTML.='<b>Admin Login</b>:&nbsp;';
	$HTML.='</td><td valign="top">';
	$HTML.='<input type="text" class="text" name="gAdminLogin" value="admin"><br>Enter a name to login in the TOPo Control Panel.';
	$HTML.='</td></tr>';
	$HTML.='<tr class="2"><td align="right" valign="top">';
	$HTML.='<b>Admin Pass</b>:&nbsp;';
	$HTML.='</td><td valign="top">';
	$HTML.='<input type="text" class="text" name="gAdminPass" value="pass"><br>The password to access to TOPo Control Panel.';
	$HTML.='</td></tr>';
	$HTML.='<tr class="header0"><td colspan="2" align="right">';
	$HTML.='<input type="hidden" name="paso" value="4">';
	$HTML.='<input type="submit" class="button" value="Next >>">';
	$HTML.='</td></tr>';
	$HTML.='</form></table>';
}
if($paso==4) {
	//----------------------------------------------------------------
	// CREACION DE data/inc_config.php
	//----------------------------------------------------------------
	$config="<?php\n";
	$config.="//\n";
	$config.="//  inc_config.php\n";
	$config.="//  rev004\n";
	$config.="//\n";
	$config.="\n";
	$config.="//You can modify this file manually but take care to use ' instead of \"\n";
	$config.="\n";
	$config.="//Variables generales.\n";
	$config.="\$gVer='".$gVer."';\n";
	$config.="\$gRev='".$gRev."';\n";
	$config.="\$gDebug=0;\n";
	$config.="\$gDemo=0;\n";
	$config.="\$gTipoDB='text';\n";
	$config.="\n";
	$config.="//Configuración del Top\n";
	$config.="\$gAdminCookie=30;\n";
	$config.="\$gAdminLogin='".$gAdminLogin."';\n";
	$config.="\$gAdminPass='".$gAdminPass."';\n";
	$config.="\$gAdminEmail='admin@email.com';\n";
	$config.="\$gTopNombre='TOPo v".$gVer." Demo';\n";
	$config.="\$gTopMetaTags='topsite';\n";
	$config.="\$gTopCopyright='2002-2005 (c) EJ3 Soft';\n";
	$config.="\$gIdioma='english';\n";
	$path=pathinfo($_SERVER['PHP_SELF']);
	$url='http://'.$_SERVER['SERVER_NAME'].''.$path['dirname'].'/';
	$config.="\$gTopURL='".$url."';\n";
	$config.="\$gTopURLhost='".gethostbyaddr($_SERVER['SERVER_ADDR'])."';\n";
	$config.="\$gTopURLip='".$_SERVER['SERVER_ADDR']."';\n";
	$config.="\$gVoteImagenSimple='".$url."images/vote.gif';\n";
	$config.="\$gEnviarCorreo=1;\n";
	$config.="\n";
	$config.="//Personalizar Top\n";
	$config.="\$gTema='topo';\n";
	$config.="\$gEstilo='';\n";
	$config.="\$gIluminar=0;\n";
	$config.="\$gConBanner=5;\n";
	$config.="\$gNumBloques=2;\n";
	$config.="\$gWebsPorBloque=10;\n";
	$config.="\$gCriterioOrden=1;\n";
	$config.="\$gMinimoHits=0;\n";
	$config.="\$gTiempoActualizar=300;\n";
	$config.="\$gTiempoResetear=604800;\n";
	$config.="\$gTiempoVoto=86400;\n";
	$config.="\n";
	$config.="//Opciones\n";
	$config.="\$gComentarios=1;\n";
	$config.="\$gPuntuacion=1;\n";
	$config.="\$gEstadisticas=1;\n";
	$config.="\$gSitioDelMomento=1;\n";
	$config.="\$gPodium=0;\n";
	$config.="\$gRendimiento=1;\n";
	$config.="\$gVistoBueno=0;\n";
	$config.="\$gBanderas=1;\n";
	$config.="\$gFrame=1;\n";
	$config.="\$gAntitrampaCookies=1;\n";
	$config.="\$gAntitrampaIPs=1;\n";
	$config.="\$gMaxDescripcion=200;\n";
	$config.="\$gMaxURL=100;\n";
	$config.="\n";
	$config.="//Opciones Avanzadas\n";
	$config.="\$gRendimiento=1;\n";
	$config.="\$gUsuariosOnline=1;\n";
	if(function_exists('gzcompress')) $config.="\$gCompresion=5;\n"; else $config.="\$gCompresion=0;\n";	
	$config.="\$gEstaPersonales=1;\n";
	$config.="\$gPrecargarBanner=10;\n";
	$config.="\$gFlashBanner=1;\n";
	$config.="\$gMultiBanner=3;\n";
	$config.="\$gBuscadorAmigable=10;\n";
	$config.="\n";
	$config.="//Banners/Reglas/Avisos\n";
	$config.="\$gVerBannerSup=0;\n";
	$config.="\$gVerBannerMed=0;\n";
	$config.="\$gVerBannerInf=0;\n";
	$config.="\$gReglas=0;\n";
	$config.="\$gAviso=0;\n";
	$config.="\n";
	$config.="//Páginas Inscritas\n";
	$config.="\$gCategorias=0;\n";
	$config.="\$gTipoTop=0;\n";
	$config.="//No modificables por el panel de control.\n";
	$config.="\$gTiempoOffset=0;\n";
	$config.="\$gAutoActualizar=0;\n";
	$config.="\$gWebsPorPagina=20;\n";
	$config.="\n";
	$config.="?>";
	
	$fp=@fopen('data/inc_config.php','w');
    $ok=@fwrite($fp,$config);
    @fclose($fp);
	@chmod('data/inc_config.php',0666);
	if($ok) {
		$auxHTML.='<tr class="1"><td align="center">';
		$auxHTML.='<br><span class="title">Config File Successfully Created</span>';
		$auxHTML.='<br><br>Click in <b>Next</b> button to continue with the installation.<br>&nbsp;';
		$auxHTML.='</td></tr>';
		$auxHTML.='<tr class="header0"><td colspan="2" align="right">';
		$auxHTML.='<input type="hidden" name="paso" value="5">';
		$auxHTML.='<input type="submit" class="button" value="Next >>">';
		$auxHTML.='</td></tr>';
	} else {
		$auxHTML.='<tr class="1"><td align="center">';
		$auxHTML.='<br><span class="title">Can&#39;t Write Config File</span>';
		$auxHTML.='<br><br>Change manually file permissions of the directory <b>/data</b> to <b>777</b> and click <b>Try Again</b> button.<br>&nbsp;';
		$auxHTML.='</td></tr>';
		$auxHTML.='<tr class="header0"><td colspan="2" align="right">';
		$auxHTML.='<input type="hidden" name="paso" value="4">';
		$auxHTML.='<input type="submit" class="button" value="Try Again">&nbsp;&nbsp;';
		$auxHTML.='</td></tr>';
	}
	$HTML.='<table width="400" align="center" cellpadding="10" cellspacing="1" border="0">';
	$HTML.='<form action="install.php" method="post" onSubmit="submitOnce(this);">';
	$HTML.='<tr class="header1"><td class="title" align="center">EJ3 TOPo Installation&nbsp;&nbsp;|&nbsp;&nbsp;Step <sup>4</sup><big>/</big><small>5</small></td></tr>';
	$HTML.='<tr class="header0"><td class="minititle" align="center">Creating config file...</td></tr>';
	$HTML.=$auxHTML;
	$HTML.='</form></table>';
}
if($paso==5) {
	//----------------------------------------------------------------
	// BORRANDO install.php
	//----------------------------------------------------------------
	$ok=TRUE;
	if(file_exists('install.php')) $ok=@unlink('install.php');
	if($ok) {
		$auxHTML.='<tr class="1"><td align="center">';
		$auxHTML.='<br><span class="title">Install File Successfully Deleted</span>';
		$auxHTML.='<br><br><b><nobr>EJ3 TOPo v'.$gVer.'</nobr></b> is successfully instaled in your server.';
		$auxHTML.='<br><br>Now you can see the top-list (that is empty) or enter in the Control Panel to configure your top-list.<br>&nbsp;';
		$auxHTML.='</td></tr>';
		$auxHTML.='<tr class="header0"><td align="center">';
		$auxHTML.='<input type="button" class="button" value="Top-List" onClick="location.href=\'index.php\'">&nbsp;&nbsp;';
		$auxHTML.='<input type="button" class="button" value="Control Panel" onClick="location.href=\'admin.php\'">';
		$auxHTML.='</td></tr>';
	} else {
		$auxHTML.='<tr class="1"><td align="center">';
		$auxHTML.='<br><span class="title">Can&#39;t Delete Install File</span>';
		$auxHTML.='<br><br>Try to delete <b>install.php</b> file manually and click <b>Try Again</b> button.<br>&nbsp;';
		$auxHTML.='</td></tr>';
		$auxHTML.='<tr class="header0"><td colspan="2" align="right">';
		$auxHTML.='<input type="hidden" name="paso" value="5">';
		$auxHTML.='<input type="submit" class="button" value="Try Again">&nbsp;&nbsp;';
		$auxHTML.='</td></tr>';
	}
	$HTML.='<table width="400" align="center" cellpadding="10" cellspacing="1" border="0">';
	$HTML.='<form action="install.php" method="post" onSubmit="submitOnce(this);">';
	$HTML.='<tr class="header1"><td class="title" align="center">EJ3 TOPo Installation&nbsp;&nbsp;|&nbsp;&nbsp;Step <sup>5</sup><big>/</big><small>5</small></td></tr>';
	$HTML.='<tr class="header0"><td class="minititle" align="center">Deleting install file...</td></tr>';
	$HTML.=$auxHTML;
	$HTML.='</form></table>';
}

//----------------------------------------------------------------
// SALIDA
//----------------------------------------------------------------
echo '<html>';
echo '<head>';
echo '<title>EJ3 TOPo</title>';
echo '<script>';
echo 'function submitOnce(theform) {
	if (document.all || document.getElementById) {
		for(i=0 ; i < theform.length ; i++) {
			var tempobj = theform.elements[i];
			if(tempobj.type.toLowerCase()=="submit"||tempobj.type.toLowerCase()=="reset") {
				tempobj.disabled=true;
			}
		}
	}
}
';
echo '</script>';
echo '<style>';
echo 'BODY {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; 
	font-size: 10px; 
	font-weight: normal;
	color : Black;
	margin-left : 0px;
	margin-right : 0px;
}
TABLE {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; 
	font-size: 10px; 
	font-weight: normal;
	color : Black;
}
SELECT {
	background-color: #FFCC66;
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; 
	font-size: 10px; 
	font-weight: bold;
	color : Black;
}
INPUT.text {
    background-color : #FFFFFF;
    color : Black;
    border : 1px ridge Black;
    font-size : 10px;
    font-family : Verdana, Arial;
    font-weight : normal;
}
INPUT.button {
	background-color: #FFCC66;
	color : Black;
	border: 1px ridge #FF9900;
	height: auto;
	font-size: 10px;
	font-weight: bold;
	height: auto;
	font: Verdana, Arial;
}
.resumen {
	background-color: #FAFAD2;
	border-color: Yellow;
}

.arial10 {
	font-family: Arial, Helvetica, sans-serif; 
	font-size: 10px; 
	font-weight: normal;
	color : Black;
}
.arial11 {
	font-family: Arial, Helvetica, sans-serif; 
	font-size: 11px; 
	font-weight: normal;
	color : Black;
}

A:LINK, A:ACTIVE, A:FOCUS {
	color : #990033;
	font-weight : bold;
}
A:VISITED {
	color : #990033;
	font-weight : bold;
}
A:HOVER {
	font-weight : bold;
	color: White;
	background-color: #990033;
	/*color : #FF6600;*/
	text-decoration : none;
}
A.blue:LINK, A.blue:ACTIVE, A.blue:FOCUS {
	color : #333399;
    font-weight : bold;
}
A.blue:VISITED {
    color : #333399;
    font-weight : bold;
}
A.blue:HOVER {
    font-weight : bold;
    color : #3366FF;
    text-decoration : underline;
}
A.green:LINK, A.blue:ACTIVE, A.blue:FOCUS {
	color : #336600;
    font-weight : bold;
}
A.green:VISITED {
    color : #336600;
    font-weight : bold;
}
A.green:HOVER {
    font-weight : bold;
    color : #00CC33;
    text-decoration : underline;
}
.title {
	font-family : Arial, Helvetica, sans-serif;
	font-size : 14px;
	font-weight : bold;
	color : #990033;
}
.minititle {
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 10px;
	font-weight : bold;
	color : #990033;
}
.red0 {
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 10px;
	font-weight : bold;
	color : Red;
}
.green0 {
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 10px;
	font-weight : bold;
	color : #00CC33;
}
.blue0 {
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 10px;
	font-weight : bold;
	color : #00BFFF;
}
.red1 {
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 10px;
	font-weight : bold;
	color : #990033;
}
.green1 {
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 10px;
	font-weight : bold;
	color : #339933;
}
.blue1 {
	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size : 10px;
	font-weight : bold;
	color : #3366FF;
}

.header0 {
	background-color: #FFCC66;
	font-weight : bold;
}

.header1 {
	background-color: #FF9900;
}

.header1r {
	background-color: #33CC33;
}
.header1g {
	background-color: #33CC33;
}
.header1b {
	background-color: #00CCFF;
}
.header1y {
	background-color: #33CC33;
}



.0 {
	background-color: #EEEEEE;
}

.1 {
	background-color: #DDDDDD;
}

.2 {
	background-color: #CCCCCC;
}
.3 {
	background-color: #BBBBBB;
}
.4 {
	background-color: #AAAAAA;
}
.5 {
	background-color: #999999;
}
.6 {
	background-color: #888888;
}
.7 {
	background-color: #777777;
}
.8 {
	background-color: #666666;
}
.9 {
	background-color: #555555;
}';
echo '</style>';
echo '</head>';
echo '<body>';
echo '<TABLE bgcolor="#FFFFFF" width="800" height="580" align="center" cellpadding="0" cellspacing="0" border="0">';
echo '<TR><TD width="100%" height="100%">';
echo '<table bgcolor="#FF9900" align="center" cellpadding="2" cellspacing="2" border="0"><tr><td bgcolor="#FFFFFF">';
echo $HTML;
echo '</td></tr></table></TD></TR>';
echo '</TABLE>';
echo '</body></html>';
?>