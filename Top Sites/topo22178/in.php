<?php
//
//  modes/top/in.php
//  rev007
//  PHP v4.2+
//

//----------------------------------------------------------------
// PARAMETROS DE ENTRADA
//----------------------------------------------------------------
if(isset($_POST['ID'])) $ID=$_POST['ID']; else $ID=$_GET['ID'];
if(isset($_POST['Id'])) $Id=$_POST['Id']; else $Id=$_GET['Id'];
if(isset($_POST['id'])) $id=$_POST['id']; else $id=$_GET['id'];

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
if(!isset($ID)) {
	if(isset($id)) $ID=$id;
	if(isset($Id)) $ID=$Id;
}

//Comprobamos que la ID tiene tiene el formato 10.4
if(strlen($ID)<15) $ID.='.0000';

//----------------------------------------------------------------
// CONTENIDO
//----------------------------------------------------------------
$cookies=new Cookies;
$web=new SitioWebAvanzado($ID);
$index=new Index;
$ip=capturarIP();

//Si no existe la ID pasada mostramos el top
if(!$index->Existe($ID)) {
	echo '<script>window.location.href="index.php";</script>';
	exit();
}

$irIndex=0;
if($_SERVER['HTTP_REFERER']==$gTopURL.'in.php?ID='.$ID) $irIndex=1;
if($_SERVER['HTTP_REFERER']==$gTopURL.'in.php?Id='.$ID) $irIndex=1;
if($_SERVER['HTTP_REFERER']==$gTopURL.'in.php?id='.$ID) $irIndex=1;

$ilegal1=$ilegal2=0;
if($gAntitrampaCookies) {
	if($tiempo=$cookies->webUltIN($ID)) {
		if($tiempo > abs(time()-$gTiempoVoto)) $ilegal1=1;
	}
}
if($gAntitrampaIPs) {
	if($tiempo=$web->ipsExiste($ip[0])) {
		if($tiempo > abs(time()-$gTiempoVoto)) $ilegal2=1;
	}
}

if($irIndex) { //Llamada desde el propio script
    if($ilegal1==0 AND $ilegal2==0) {
		$web->IN($ip[0],time()-$gTiempoVoto);
		$cookies->webActualizar($ID.'','IN');
	}
    $HTML.='<table align="center" border="0" class="0" cellspacing="1" cellpadding="5">';
    $HTML.='<tr><td colspan="0" align="center" class="title">'.$gTopNombre.'</td></tr>';
    $HTML.='<tr class="1"><td align="center" valign="middle" class="text">'.$_VoteCount_.'</td></tr>';
    $HTML.='<tr class="2"><td align="center" valign="middle" class="minititle">'.$_Entering_.'</td></tr>';
    $HTML.='</table>';
	//Mostramos todas o sólo una categoria.
	if($gTipoTop) {
		$HTML.='<script>window.location.href="index.php?c='.$index->Leer($ID,1).'";</script>';
	} else {
		$HTML.='<script>window.location.href="index.php";</script>';
	}
} else {    //Llamada desde un servidor externo
    if($ilegal1 OR $ilegal2) {
        //Voto NO válido
        $HTML.='<table align="center" border="0" class="0" cellspacing="1" cellpadding="5">';
        $HTML.='<tr class="0"><td colspan="2" align="center" class="title">'.$gTopNombre.'</td></tr>';
        $HTML.='<tr class="1"><td align="center" valign="middle" class="minititle">'.$_InvalidVote_.'</td></tr>';
        $HTML.='<tr class="2"><td align="center" valign="middle" class="text"><br><a href="'.$web->webURL.'" target="_blank">'.$web->web.'</a><br><div id="cont"> </div><br></td></tr>';
        $HTML.='<tr class="1"><td align="center" valign="middle" class="text"><a href="index.php">'.$_WithoutVote_.'</a></td></tr>';
        $HTML.='</table>';
        $HTML.='<script>reloj=new Reloj('.abs($tiempo-time()+$gTiempoVoto).',\'cont\',\''.$_WhenVote_.'\',1); setInterval(\'reloj.Atras()\',1000);</script>';
    } else {
        //Voto válido
        $HTML.='<form action="in.php" method="post">';
        $HTML.='<table align="center" border="0" class="0" cellspacing="1" cellpadding="5">';
		$HTML.='<tr class="0"><td colspan="2" align="center" class="title">'.$gTopNombre.'</td></tr>';
        $HTML.='<tr class="1"><td align="center" valign="middle" class="text">'.$_ComeFrom_.'<a href="'.$web->webURL.'" target="_blank">'.$web->web.'</a></td></tr>';
        $HTML.='<tr class="2"><td align="center" valign="middle" class="text"><br>'.$_IfYouWant_.'<br><br><INPUT TYPE="SUBMIT" class="button" value="'.$_VoteFor_.$web->web.'"><br>&nbsp;</td></tr>';
        $HTML.='<tr class="1"><td align="center" valign="middle" class="text"><a href="index.php">'.$_WithoutVote_.'</a></td></tr>';
        $HTML.='<INPUT TYPE="HIDDEN" name="ID" value="'.$ID.'">';
        $HTML.='</table></form>';
    }
}

//----------------------------------------------------------------
// SALIDA (propia)
//----------------------------------------------------------------
include('code/inc_header.php');
echo $HTML;
$gRendimiento=0;
include('code/inc_footer.php');
exit();

?>