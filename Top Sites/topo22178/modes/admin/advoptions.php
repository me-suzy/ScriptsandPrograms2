<?php
//
//  modes/admin/advoptions.php
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
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_AdvOptions_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_OnlineUsers_.'</span></TD><TD class="2"><SELECT name="gUsuariosOnlineNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gUsuariosOnline) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_gZIPcompression_.'</span></TD><TD class="2"><SELECT name="gCompresionNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	for($i=1;$i<10;$i++) {
		$HTML.='<OPTION class="enable" value="'.$i.'"';
		if($gCompresion==$i) $HTML.=' selected';
		$HTML.='>'.$i.'</OPTION>';
	}
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_PersonalStats_.'</span></TD><TD class="2"><SELECT name="gEstaPersonalesNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gEstaPersonales) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_BannersPreload_.'</span></TD><TD class="2"><SELECT name="gPrecargarBannerNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="10"';
	if($gPrecargarBanner) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	/*
	//Esto se queda aqui para cuando este implementada la opción de prohibir los banners flash
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_BannersFlash_.'</span></TD><TD class="2"><SELECT name="gFlashBannerNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="10"';
	if($gFlashBanner) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	*/
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_Multibanner_.'</span></TD><TD class="2"><SELECT name="gMultiBannerNEW">';
	$HTML.='<OPTION class="disable" value="1">'.$_Disable_.'</OPTION>';	
	for($i=2;$i<11;$i++) {
		$HTML.='<OPTION class="enable" value="'.$i.'"';
		if($gMultiBanner==$i) $HTML.=' selected';
		$HTML.='>'.$i.' '.$_BannersPerSite_.'</OPTION>';
	}
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_AdvCodeToGetVotes_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_SearchFriendly_.'</span></TD><TD class="2"><SELECT name="gBuscadorAmigableNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="10"';
	if($gBuscadorAmigable) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	//$HTML.='<TR><TD class="1" align="center" colspan="2"><span class="options">These features are not implemented</span></TD></TR>'."\n";	
	$HTML.='<TR><TD class="0" align="center" colspan="2">';
	$HTML.='<INPUT type="hidden" name="m" value="admin">';
	$HTML.='<INPUT type="hidden" name="s" value="html">';	
	$HTML.='<INPUT type="hidden" name="t" value="advoptions">';
	$HTML.='<INPUT type="hidden" name="paso" value="1">';	
	$HTML.='<INPUT type="reset" class="button">&nbsp;<INPUT type="submit" class="button"></TD></TR>'."\n";	
	$HTML.='</TABLE></FORM>';
}

if($paso==1) {
	//Adaptacion de variables para PHP v4.2+
	$gUsuariosOnlineNEW=$_POST['gUsuariosOnlineNEW'];
	$gCompresionNEW=$_POST['gCompresionNEW'];
	$gEstaPersonalesNEW=$_POST['gEstaPersonalesNEW'];
	$gPrecargarBannerNEW=$_POST['gPrecargarBannerNEW'];
	$gFlashBannerNEW=$_POST['gFlashBannerNEW'];
	$gMultiBannerNEW=$_POST['gMultiBannerNEW'];
	$gBuscadorAmigableNEW=$_POST['gBuscadorAmigableNEW'];
	//--------------------------------------
	$old[0]="\$gUsuariosOnline=".$gUsuariosOnline.";";
	$new[0]="\$gUsuariosOnline=".$gUsuariosOnlineNEW.";";
	$old[1]="\$gCompresion=".$gCompresion.";";
	$new[1]="\$gCompresion=".$gCompresionNEW.";";
	$old[2]="\$gEstaPersonales=".$gEstaPersonales.";";
	$new[2]="\$gEstaPersonales=".$gEstaPersonalesNEW.";";
	$old[3]="\$gPrecargarBanner=".$gPrecargarBanner.";";
	$new[3]="\$gPrecargarBanner=".$gPrecargarBannerNEW.";";
	//$old[4]="\$gFlashBanner=".$gFlashBanner.";";
	//$new[4]="\$gFlashBanner=".$gFlashBannerNEW.";";
	$old[5]="\$gMultiBanner=".$gMultiBanner.";";
	$new[5]="\$gMultiBanner=".$gMultiBannerNEW.";";
	$old[6]="\$gBuscadorAmigable=".$gBuscadorAmigable.";";
	$new[6]="\$gBuscadorAmigable=".$gBuscadorAmigableNEW.";";

	config($old,$new,'data/');
	//Recargamos.	
	$HTML='<br><br><br><br><center><span class="title">'.$_UpdatingData_.'</span></center>';
	$HTML.='<script>location.href="index.php?m=admin&s=html&t=advoptions";</script>';
}

?>