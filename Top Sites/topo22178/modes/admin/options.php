<?php
//
//  modes/admin/options.php
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

//----------------------------------------------------------------
// CONTENIDO
//----------------------------------------------------------------
if($paso=='' OR $paso==0) {
	$HTML='<FORM action="index.php" method="post" onSubmit="submitOnce(this);">';
	$HTML.='<TABLE align="center" class="0" border="0" cellpadding="2" cellspacing="1">';
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_Options_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_CommentSystem_.'</span></TD><TD class="2"><SELECT name="gComentariosNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gComentarios) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_RateSystem_.'</span></TD><TD class="2"><SELECT name="gPuntuacionNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gPuntuacion) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_StatsSystem_.'</span></TD><TD class="2"><SELECT name="gEstadisticasNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gEstadisticas) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_SiteOfTheMoment_.'</span></TD><TD class="2"><SELECT name="gSitioDelMomentoNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gSitioDelMomento) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_Podium_.'</span></TD><TD class="2"><SELECT name="gPodiumNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	for($i=1;$i<10;$i++) {
		$HTML.='<OPTION class="enable" value="'.$i.'"';
		if($gPodium==$i) $HTML.=' selected';
		$HTML.='>'.$i.'</OPTION>';
	}
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_Performance_.'</span></TD><TD class="2"><SELECT name="gRendimientoNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gRendimiento) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_Validation_.'</span></TD><TD class="2"><SELECT name="gVistoBuenoNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gVistoBueno) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_ShowCountry_.'</span></TD><TD class="2"><SELECT name="gBanderasNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gBanderas) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_ShowFrame_.'</span></TD><TD class="2"><SELECT name="gFrameNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gFrame) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_AntiCheating_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_AnticheatingCookies_.'</span></TD><TD class="2"><SELECT name="gAntitrampaCookiesNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gAntitrampaCookies) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_AnticheatingIPs_.'</span></TD><TD class="2"><SELECT name="gAntitrampaIPsNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gAntitrampaIPs) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_ConfigMax_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_MaxDescription_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gMaxDescripcionNEW" value="'.$gMaxDescripcion.'" onBlur="validate(this,\'numberMin\',200,50);" maxlength="4" size="5"> <span class="minitext">50 .. 9999</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_MaxURLs_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gMaxURLNEW" value="'.$gMaxURL.'" onBlur="validate(this,\'numberMin\',50,20);" maxlength="4" size="5"> <span class="minitext">20 .. 9999</span></TD></TR>'."\n";	
	$HTML.='<TR><TD class="0" align="center" colspan="2">';
	$HTML.='<INPUT type="hidden" name="m" value="admin">';
	$HTML.='<INPUT type="hidden" name="s" value="html">';	
	$HTML.='<INPUT type="hidden" name="t" value="options">';
	$HTML.='<INPUT type="hidden" name="paso" value="1">';	
	$HTML.='<INPUT type="reset" class="button">&nbsp;<INPUT type="submit" class="button"></TD></TR>'."\n";	
	$HTML.='</TABLE></FORM>';
}

if($paso==1) {
	//Adaptacion de variables para PHP v4.2+
	$gComentariosNEW=$_POST['gComentariosNEW'];
	$gPuntuacionNEW=$_POST['gPuntuacionNEW'];
	$gEstadisticasNEW=$_POST['gEstadisticasNEW'];
	$gSitioDelMomentoNEW=$_POST['gSitioDelMomentoNEW'];
	$gPodiumNEW=$_POST['gPodiumNEW'];
	$gRendimientoNEW=$_POST['gRendimientoNEW'];
	$gVistoBuenoNEW=$_POST['gVistoBuenoNEW'];
	$gBanderasNEW=$_POST['gBanderasNEW'];
	$gFrameNEW=$_POST['gFrameNEW'];
	$gAntitrampaCookiesNEW=$_POST['gAntitrampaCookiesNEW'];
	$gAntitrampaIPsNEW=$_POST['gAntitrampaIPsNEW'];
	$gMaxDescripcionNEW=$_POST['gMaxDescripcionNEW'];
	$gMaxURLNEW=$_POST['gMaxURLNEW'];
	//--------------------------------------
	$old[0]="\$gComentarios=".$gComentarios.";";
	$new[0]="\$gComentarios=".$gComentariosNEW.";";
	$old[1]="\$gPuntuacion=".$gPuntuacion.";";
	$new[1]="\$gPuntuacion=".$gPuntuacionNEW.";";
	$old[2]="\$gEstadisticas=".$gEstadisticas.";";
	$new[2]="\$gEstadisticas=".$gEstadisticasNEW.";";
	$old[3]="\$gSitioDelMomento=".$gSitioDelMomento.";";
	$new[3]="\$gSitioDelMomento=".$gSitioDelMomentoNEW.";";
	$old[4]="\$gPodium=".$gPodium.";";
	$new[4]="\$gPodium=".$gPodiumNEW.";";
	$old[5]="\$gRendimiento=".$gRendimiento.";";
	$new[5]="\$gRendimiento=".$gRendimientoNEW.";";
	$old[6]="\$gVistoBueno=".$gVistoBueno.";";
	$new[6]="\$gVistoBueno=".$gVistoBuenoNEW.";";
	$old[7]="\$gBanderas=".$gBanderas.";";
	$new[7]="\$gBanderas=".$gBanderasNEW.";";
	$old[8]="\$gFrame=".$gFrame.";";
	$new[8]="\$gFrame=".$gFrameNEW.";";
	$old[9]="\$gAntitrampaCookies=".$gAntitrampaCookies.";";
	$new[9]="\$gAntitrampaCookies=".$gAntitrampaCookiesNEW.";";
	$old[10]="\$gAntitrampaIPs=".$gAntitrampaIPs.";";
	$new[10]="\$gAntitrampaIPs=".$gAntitrampaIPsNEW.";";
	$old[11]="\$gMaxDescripcion=".$gMaxDescripcion.";";
	$new[11]="\$gMaxDescripcion=".$gMaxDescripcionNEW.";";
	$old[12]="\$gMaxURL=".$gMaxURL.";";
	$new[12]="\$gMaxURL=".$gMaxURLNEW.";";
	
	config($old,$new,'data/');
	//Recargamos.	
	$HTML='<br><br><br><br><center><span class="title">'.$_UpdatingData_.'</span></center>';
	$HTML.='<script>location.href="index.php?m=admin&s=html&t=options";</script>';
}

?>