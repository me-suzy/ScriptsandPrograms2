<?php
//
//  modes/top/out.php
//  rev005
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
if(isset($_POST['ID'])) $ID=$_POST['ID']; else $ID=$_GET['ID'];
if(isset($_POST['t'])) $tipo=$_POST['t']; else $tipo=$_GET['t'];

//----------------------------------------------------------------
// CONTENIDO
//----------------------------------------------------------------
$indice=new Index;
if($indice->Existe($ID)) {
	$web=new SitioWebAvanzado($ID);
	$cookies=new Cookies;
} else {
	echo '<script>window.location.href="index.php";</script>';
	exit();
}

//Actualizamos los datos.
if($tipo=='') { 
	$web->OUT();
	$cookies->webActualizar($ID.'','OUT');
    if($gFrame) {    //Frame
		$img_info=@GetImageSize($gVoteImagenSimple);
		if($img_info[1]<27) $img_info[1]=27;
        $HTML.='<html><head>';
		$HTML.='<title>'.$gTopNombre.'</title>';
        $HTML.='<frameset rows="'.($img_info[1]+3).',*" cols="*" frameborder="NO" border="0" framespacing="0">';
        $HTML.='<frame ID="topo" noresize scrolling="NO" src="index.php?m=top&s=out&t=frame&ID='.$ID.'" marginheight="0">';
        $HTML.='<frame ID="web" noresize src="'.$web->webURL.'" marginheight="0">';
        $HTML.='</frameset>';
        $HTML.='</head></html>';
        echo $HTML;
        exit();
    } else {
		$HTML.='<p align="center" class="titulo">'.$gTopNombre.'</p>';
        $HTML.='<table align="center" border="0" cellspacing="1" cellpadding="5">';
        $HTML.='<tr class="0"><td align="center" valign="middle" class="minititle">'.$_UpdateData_.'</td></tr>';
        $HTML.='<tr class="1"><td align="center" valign="middle">'.$_EnteringWeb_.$web->web.'</td></tr>';
        $HTML.='</table>';
        $HTML.='<script>window.location.href=\''.$web->webURL.'\'</script>';
    }
}

//Frame superior
if($tipo=='frame') {
	$nota=0;
	if($web->datLeer(6)) $nota=number_format($web->datLeer(6)/$web->datLeer(5),1,'.','');
    $HTML.='<table align="center" border="0" cellspacing="1" width="100%">';
    $HTML.='<tr>';
    $HTML.='<td align="center" width="90" valign="middle" class="1"><a href="'.$gTopURL.'" target="_blank"><img src="'.$gVoteImagenSimple.'" border="0"></a></td>';
    $HTML.='<td align="center" valign="middle" class="2"><IMG src="'.$gTopURL.'/images/flags/'.$web->pais.'.gif" width="24" height="15" border="0" align="absmiddle"> <b><a href="'.$web->webURL.'" target="_top">'.$web->web.'</a></b>';
	if($gPuntuacion) $HTML.='&nbsp;<img src="themes/'.$gTema.'/rate'.number_format($nota,0,'.','').'.gif" border="0">&nbsp;<span class="minitext"><b>'.$nota.'</b> <i>('.$web->datLeer(5).' '.$_Votes_.')</span></i>&nbsp;<INPUT TYPE="BUTTON" class="minibutton" value="'.$_RateIt_.'" onClick="ventana(\'index.php?m=top&s=info&ID='.$ID.'&t=puntuar\',\'_blank\',300,150)">';
	$HTML.='&nbsp;<INPUT TYPE="BUTTON" class="minibutton" value="'.$_Info_.'" onClick="ventana(\'index.php?m=top&s=info&ID='.$ID.'\',\'_blank\','.(200*$gComentarios+360).','.(200*$gPuntuacion+225).')"></td>';
	$HTML.='<td align="center" valign="middle" class="1"><INPUT type="button" class="minibutton" onClick="top.location.href=\''.$web->webURL.'\';" value="'.$_Close_.'"></td>';
    $HTML.='</tr>';
    $HTML.='</table>';
	include('code/inc_header.php');	
	echo $HTML;
	echo '</body></html>';
	exit();
}

//----------------------------------------------------------------
// SALIDA (propia)
//----------------------------------------------------------------
include('code/inc_header.php');
echo $HTML;
include('code/inc_footer.php');
exit();

?>