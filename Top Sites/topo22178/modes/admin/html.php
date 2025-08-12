<?php
//
//  modes/admin/admin.php
//	rev005
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
if(isset($_POST['f'])) $frame=$_POST['f']; else $frame=$_GET['f'];
if(isset($_POST['paso'])) $paso=$_POST['paso']; else $paso=$_GET['paso'];
if(isset($_POST['login'])) $login=$_POST['login']; else $login=$_GET['login'];
if(isset($_POST['pass'])) $pass=$_POST['pass']; else $pass=$_GET['pass'];
if(isset($_POST['gAdminLoginNEW'])) $gAdminLoginNEW=$_POST['gAdminLoginNEW']; else $gAdminLoginNEW=$_GET['gAdminLoginNEW'];
if(isset($_POST['gAdminPassNEW'])) $gAdminPassNEW=$_POST['gAdminPassNEW']; else $gAdminPassNEW=$_GET['gAdminPassNEW'];

//----------------------------------------------------------------
// DATOS NECESARIOS
//----------------------------------------------------------------
//Comprobamos que está definida la URL del top.
if(strlen($gTopURL)<10) {
	$path=pathinfo($_SERVER['PHP_SELF']);
	$url='http://'.$_SERVER['SERVER_NAME'].''.$path['dirname'].'/';
	$old[1]="\$gTopURL='".$gTopURL."';";
	$new[1]="\$gTopURL='".$url."';";
	$old[2]="\$gTopURLhost='".$gTopURLhost."';";
	$new[2]="\$gTopURLhost='".gethostbyaddr($_SERVER['SERVER_ADDR'])."';";
	$old[3]="\$gTopURLip='".$gTopURLip."';";
	$new[3]="\$gTopURLip='".$_SERVER['SERVER_ADDR']."';";
	$old[4]="\$gVoteImagenSimple='".$gVoteImagenSimple."';";
	$new[4]="\$gVoteImagenSimple='".$url."images/vote.gif';";
	config($old,$new,'data/');
	$gTopURL=$url;
}

$cookies=new Cookies;

//----------------------------------------------------------------
// CONTROL DE ACCESOS
//----------------------------------------------------------------
if($tipo=="logout") {
	$cookies->MandarCookie('topoAdmin',ej3Time().'||'.ej3Time().'||',-60);
	//Recargamos.
	include('code/inc_header.php');
	$HTML='<br><br><br><br><center><span class="title">'.$_LogOut_.'</span></center>';
	$HTML.='<script>location.href="admin.php";</script>';
	$HTML.='</body></html>';
	echo $HTML;
	exit();
}
if($cookies->esAdmin()) {
	if($modo=='config' AND $paso==1) {
		$cookies->MandarCookie('topoAdmin',$gAdminLoginNEW.'||'.$gAdminPassNEW.'||',60*$gAdminCookie);
	} else {
		$cookies->MandarCookie('topoAdmin',$gAdminLogin.'||'.$gAdminPass.'||',60*$gAdminCookie);
		if(!isset($tipo)) $tipo='checkit';
	}
} elseif($login==$gAdminLogin AND $pass==$gAdminPass) {
	$cookies->MandarCookie('topoAdmin',$gAdminLogin.'||'.$gAdminPass.'||',60*$gAdminCookie);
	if(!isset($tipo)) $tipo='checkit';
} else {
	$HTML.='<br><center><span class="title">'.$gTopNombre.'</span></center>';
    $HTML.='<br><form action="index.php" method="post" onSubmit="submitOnce(this);">';
    $HTML.='<table class="text" align="center" border="0" cellspacing="1" cellpadding="5">';
	$HTML.='<tr><td class="0" align="center" colspan="2"><span class="minititle">'.$_LoginScreen_.'</span></td></tr>';	
    $HTML.='<tr><td class="1" align="right"><span class="options">'.$_Login_.'</span></td><td class="2"><INPUT TYPE="text" class="text" name="login" size="40"></td></tr>';
    $HTML.='<tr><td class="1" align="right"><span class="options">'.$_Pass_.'</span></td><td class="2"><INPUT TYPE="password" class="text" name="pass" maxlength="50" size="40"></tr>';
    $HTML.='<tr><td class="0" align="center" colspan="2"><input type="submit" class="button">';
    $HTML.='<INPUT TYPE="HIDDEN" name="m" value="admin">';
	$HTML.='<INPUT TYPE="HIDDEN" name="s" value="html">';
	$HTML.='<INPUT TYPE="HIDDEN" name="t" value="checkit">';
	$HTML.='</td></tr></table></form>';
	include('code/inc_header.php');
	echo $HTML;
	$gRendimiento=0;
	include('code/inc_footer.php');
	exit();
}

//----------------------------------------------------------------
// MENUS Y CABECERAS
//----------------------------------------------------------------
$INDICE1='<table class="0" width="90%" align="center" border="0" cellspacing="1" cellpadding="5">';
$INDICE1.='<tr><td align="left">{LUGAR}</td>';
$INDICE1.='<td align="right">';
$INDICE1.='<INPUT type="button" id="logout" class="minibutton" value="'.$_LogOut_.'" onClick="window.location.href=\'index.php?m=admin&s=html&t=logout\'"></td></tr>';
$INDICE1.='<script>reloj_logout=new Reloj('.(60*$gAdminCookie).',"logout","'.$_LogOut_.' ({CLOCK})",1); setInterval("reloj_logout.AtrasBoton(\'index.php?m=admin&s=html&t=logout\')",1000);</script>';
$INDICE1.='</table>';

$INDICE2='<table class="0" width="90%" align="center" border="0" cellspacing="1" cellpadding="5">';
$INDICE2.='<tr><td align="left">{LUGAR}</td>';
$INDICE2.='<td align="right">';
$INDICE2.='<INPUT type="button" class="minibutton" value="'.$_LogOut_.'" onClick="window.location.href=\'index.php?m=admin&s=html&t=logout\'"></td></tr>';
$INDICE2.='</table>';

$MENU='<table class="0" align="center" border="0" cellspacing="1" cellpadding="5">';
$MENU.='<tr class="0"><td align="center"><span class="minititle">'.$_Menu_.'</span></td></tr>';
$MENU.='<tr class="2"><td align="center">';
$MENU.='<a href="index.php?m=admin&s=html&t=checkit">'.$_CheckIt_.'</a>';
$MENU.='<br><a href="index.php?m=admin&s=html&t=config">'.$_TopConfig_.'</a>';
$MENU.='<br><a href="index.php?m=admin&s=html&t=custom">'.$_Customize_.'</a>';	
$MENU.='<br><a href="index.php?m=admin&s=html&t=options">'.$_Options_.'</a>';
$MENU.='<br><a href="index.php?m=admin&s=html&t=advoptions">'.$_AdvOptions_.'</a>';
$MENU.='<br><a href="index.php?m=admin&s=html&t=banners">'.$_BannersRulesNotices_.'</a>';
$MENU.='<br><a href="index.php?m=admin&s=html&t=categories">'.$_Categories_.'</a>';
$MENU.='<br><a href="index.php?m=admin&s=html&t=webs">'.$_WebSites_.'</a>';
$MENU.='<hr><a href="index.php?m=admin&s=html&t=tools">'.$_Tools_.'</a>';
$MENU.='<br><br><INPUT type="button" class="minibutton" onClick="window.open(\'index.php\')" value="'.$_ViewTop_.'">';
$MENU.='</td></tr>';
$MENU.='<tr class="0"><td align="center"><span class="minititle">Plugins</span></td></tr>';
$MENU.='<tr class="2"><td align="center">';
$plugins=opendir('plugins');
while($directorio=readdir($plugins)) {
	if($directorio=='.' OR $directorio=='..') continue;
	$MENU.='<a href="plugins/'.$directorio.'/index.php" target="_blank">'.str_replace('_',' ',$directorio).'</a><br>';
}
$MENU.='</td></tr>';
$MENU.='<tr class="0"><td align="center"><span class="minititle">'.$_Versions_.'</span></td></tr>';
$MENU.='<tr class="2"><td align="center">';
$MENU.='<a href="http://www.php.net" target="_blank">PHP</a> v'.phpversion();
$MENU.='<br><a href="http://ej3soft.ej3.net" target="_blank">TOPo</a> v'.$gVer.'.'.$gRev;
$MENU.='</td></tr>';
$MENU.='<tr class="0"><td align="center"><span class="minititle">'.$_Support_.'</span></td></tr>';
$MENU.='<tr class="2"><td align="center">';
$MENU.='<a href="http://ej3soft.ej3.net/ej3_foro/" target="_blank">Help forum</a>';
$MENU.='<form action="https://www.paypal.com/cgi-bin/webscr" method="post">';
$MENU.='<input type="hidden" name="cmd" value="_xclick">';
$MENU.='<input type="hidden" name="business" value="emiliojjj@usuarios.retecal.es">';
$MENU.='<input type="hidden" name="item_name" value="EJ3 TOPo Donation">';
$MENU.='<input type="hidden" name="no_note" value="1">';
$MENU.='<input type="hidden" name="currency_code" value="EUR">';
$MENU.='<input type="hidden" name="tax" value="0">';
$MENU.='<input type="image" src="https://www.paypal.com/images/x-click-butcc-donate.gif" border="0" name="submit" alt="'.$_Support_.'">';
$MENU.='</form>';
$MENU.='</td></tr>';
$MENU.='</table>';
$MENU.='<br>';

//----------------------------------------------------------------
// CONTENIDO SEGUN $tipo
//----------------------------------------------------------------
if($tipo=='checkit') {
	include('modes/admin/checkit.php');
	$INDICE1=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_CheckIt_.'</span>',$INDICE1);
	$INDICE2=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_CheckIt_.'</span>',$INDICE2);
}

if($tipo=='config') {
	include('modes/admin/config.php');
	$INDICE1=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_TopConfig_.'</span>',$INDICE1);
	$INDICE2=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_TopConfig_.'</span>',$INDICE2);
}

if($tipo=='custom') {
	include('modes/admin/customize.php');
	$INDICE1=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_Customize_.'</span>',$INDICE1);
	$INDICE2=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_Customize_.'</span>',$INDICE2);
}

if($tipo=='options') {
	include('modes/admin/options.php');
	$INDICE1=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_Options_.'</span>',$INDICE1);
	$INDICE2=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_Options_.'</span>',$INDICE2);
}

if($tipo=='advoptions') {
	include('modes/admin/advoptions.php');
	$INDICE1=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_AdvOptions_.'</span>',$INDICE1);
	$INDICE2=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_AdvOptions_.'</span>',$INDICE2);
}

if($tipo=='banners') {
	include('modes/admin/banners.php');
	$INDICE1=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_BannersRulesNotices_.'</span>',$INDICE1);
	$INDICE2=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_BannersRulesNotices_.'</span>',$INDICE2);
}

if($tipo=='categories') {
	include('modes/admin/categories.php');
	$INDICE1=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_Categories_.'</span>',$INDICE1);
	$INDICE2=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_Categories_.'</span>',$INDICE2);
}

if($tipo=='webs') {
	include('modes/admin/webs.php');
	$INDICE1=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_WebSites_.'</span>',$INDICE1);
	$INDICE2=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_WebSites_.'</span>',$INDICE2);
}

if($tipo=='tools') {
	include('modes/admin/tools.php');
	$INDICE1=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_Tools_.'</span>',$INDICE1);
	$INDICE2=str_replace('{LUGAR}','<span class="title">TOPo v'.$gVer.' <b>&#187</b> '.$_ControlPanel_.'</span> <b>&#187</b> <span class="title">'.$_Tools_.'</span>',$INDICE2);
}

//----------------------------------------------------------------
// SALIDA (propia)
//----------------------------------------------------------------
include('code/inc_header.php');
if($frame=='iframe' OR $paso=='cambiarTipoTop' OR $paso=='editarWeb' OR $paso=='borrarBanner' OR $paso=='guardarWeb' OR $frame=="ayuda" OR $frame=='conversor_v1x_a_v2x' OR $frame=='conversor_v20_a_v2x' OR $frame=='mass_email') {
	echo $HTML;
	echo '</body></html>';
} else {
	echo $INDICE1;
	echo '<TABLE width="90%" align="center" cellpadding="0" cellspacing="5">';
	echo '<TR><TD class="1" width="20%" valign="top"><br>'.$MENU.'</TD>';
	echo '<TD>';
	echo $HTML;
	echo '</TD></TR></TABLE>';
	echo $INDICE2;
	$gRendimiento=0;
	include('code/inc_footer.php');
}
exit();

?>