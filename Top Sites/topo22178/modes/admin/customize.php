<?php
//
//  modes/admin/customize.php
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
	$directorio=opendir('css');
    if($file=readdir($directorio)) {
        do {
            $ext=strrchr($file,'.');
            //$aux=explode('.',$file);
            if ($file != "." AND $file != ".." AND ($ext==".css" OR $ext==".CSS")) {
                $css[$i++]=$file;
            }
        } while ($file = readdir($directorio));
        closedir($directorio);
    }
	$i=0;
	$directorio=opendir('themes');
    if($file=readdir($directorio)) {
        do {
            if($file != "." AND $file != ".." AND is_dir('themes/'.$file)) $temas[$i++]=$file;
        } while ($file = readdir($directorio));
        closedir($directorio);
    }
	$HTML='<FORM action="index.php" method="post" onSubmit="submitOnce(this);">';
	$HTML.='<TABLE align="center" class="0" border="0" cellpadding="2" cellspacing="1">';
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_Layout_.'</span></TD></TR>'."\n";
	if(is_array($temas)) {
		$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_Theme_.'</span></TD><TD class="2"><SELECT name="gTemaNEW">'."\n";
		foreach($temas as $value) {
			$HTML.='<OPTION value="'.$value.'"';
			if($gTema==$value) $HTML.=' selected';
			$HTML.='>'.$value.'</OPTION>';
		}
		$HTML.='</SELECT></TD></TR>'."\n";
		$linea=file('themes/'.$gTema.'/templates.dat');
		foreach($linea as $value) $data.=$value;
		$aux=explode('||*|||*|||||*',$data);
		$HTML.='<TR><TD class="2" colspan="2">'."\n";
		$HTML.='<table align="center" width="90%" class="0" border="0" cellpadding="2" cellspacing="2"><tr class="1"><td class="minitext">'.nl2br($aux[0]).'</td></tr></table>';
		$HTML.='</TD></TR>'."\n";
	}
	if(is_array($css)) {
		$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_Style_.'</span></TD><TD class="2"><SELECT name="gEstiloNEW">';
		$HTML.='<OPTION value="">'.$_ThemeStyle_.'</OPTION>';
		foreach($css as $value) {
			$HTML.='<OPTION value="'.$value.'"';
			if($gEstilo==$value) $HTML.=' selected';
			$HTML.='>'.$value.'</OPTION>';
		}
		$HTML.='</SELECT></TD></TR>'."\n";
	}
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_Highlight_.'</span></TD><TD class="2"><SELECT name="gIluminarNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gIluminar) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_BannersBlocks_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_WithBanner_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gConBannerNEW" value="'.$gConBanner.'" onBlur="validate(this,\'numberMin\',5,0);" maxlength="2" size="3"> <span class="minitext">0 .. 99</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_NumberOfBlocks_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gNumBloquesNEW" value="'.$gNumBloques.'" onBlur="validate(this,\'numberMin\',2,1);" maxlength="2" size="3"> <span class="minitext">1 .. 99</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_BannersPerBlock_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gWebsPorBloqueNEW" value="'.$gWebsPorBloque.'" onBlur="validate(this,\'numberMin\',10,1);" maxlength="2" size="3"> <span class="minitext">1 .. 99</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_TopCriteria_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_SortBy_.'</span></TD><TD class="2"><SELECT name="gCriterioOrdenNEW">';
	foreach($_SortCriterion_ as $key => $value) {
		$HTML.='<OPTION value="'.$key.'"';
		if($gCriterioOrden==$key) $HTML.=' selected';
		$HTML.='>'.$value.'</OPTION>';
	}
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_MinHits_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gMinimoHitsNEW" value="'.$gMinimoHits.'" onBlur="validate(this,\'numberMin\',0,0);" maxlength="2" size="3"> <span class="minitext">0 .. 99</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_ConfigTimes_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_UpdateTime_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gTiempoActualizarNEW" value="'.$gTiempoActualizar.'" onBlur="validate(this,\'numberMin\',600,10);" maxlength="10" size="12"> <span class="minitext">10 .. 9999999999</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_ResetTime_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gTiempoResetearNEW" value="'.$gTiempoResetear.'" onBlur="validate(this,\'numberMin\',604800,60);" maxlength="10" size="12"> <span class="minitext">60 .. 9999999999</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_VoteTime_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gTiempoVotoNEW" value="'.$gTiempoVoto.'" onBlur="validate(this,\'numberMin\',86400,30);" maxlength="10" size="12"> <span class="minitext">30 .. 9999999999</span></TD></TR>'."\n";
	//$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_DisableTime_.'</span></TD><TD class="2"> <span class="minitext">Feature not implemented</TD></TR>'."\n";
	$HTML.='<TR><TD class="0" align="center" colspan="2">';
	$HTML.='<INPUT type="hidden" name="m" value="admin">';
	$HTML.='<INPUT type="hidden" name="s" value="html">';	
	$HTML.='<INPUT type="hidden" name="t" value="custom">';
	$HTML.='<INPUT type="hidden" name="paso" value="1">';	
	$HTML.='<INPUT type="reset" class="button">&nbsp;<INPUT type="submit" class="button"></TD></TR>'."\n";	
	$HTML.='</TABLE></FORM>';
}

if($paso==1) {
	//Adaptacion de variables para PHP v4.2+
	$gTemaNEW=$_POST['gTemaNEW'];
	$gEstiloNEW=$_POST['gEstiloNEW'];
	$gIluminarNEW=$_POST['gIluminarNEW'];
	$gConBannerNEW=$_POST['gConBannerNEW'];
	$gNumBloquesNEW=$_POST['gNumBloquesNEW'];
	$gWebsPorBloqueNEW=$_POST['gWebsPorBloqueNEW'];
	$gCriterioOrdenNEW=$_POST['gCriterioOrdenNEW'];
	$gMinimoHitsNEW=$_POST['gMinimoHitsNEW'];
	$gTiempoActualizarNEW=$_POST['gTiempoActualizarNEW'];
	$gTiempoResetearNEW=$_POST['gTiempoResetearNEW'];
	$gTiempoVotoNEW=$_POST['gTiempoVotoNEW'];
	//--------------------------------------
	$old[0]="\$gTema='".$gTema."';";
	$new[0]="\$gTema='".$gTemaNEW."';";
	$old[1]="\$gEstilo='".$gEstilo."';";
	$new[1]="\$gEstilo='".$gEstiloNEW."';";
	$old[2]="\$gIluminar=".$gIluminar.";";
	$new[2]="\$gIluminar=".$gIluminarNEW.";";
	$old[3]="\$gConBanner=".$gConBanner.";";
	$new[3]="\$gConBanner=".$gConBannerNEW.";";
	$old[4]="\$gNumBloques=".$gNumBloques.";";
	$new[4]="\$gNumBloques=".$gNumBloquesNEW.";";
	$old[5]="\$gWebsPorBloque=".$gWebsPorBloque.";";
	$new[5]="\$gWebsPorBloque=".$gWebsPorBloqueNEW.";";
	$old[6]="\$gCriterioOrden=".$gCriterioOrden.";";
	$new[6]="\$gCriterioOrden=".$gCriterioOrdenNEW.";";
	$old[7]="\$gMinimoHits=".$gMinimoHits.";";
	$new[7]="\$gMinimoHits=".$gMinimoHitsNEW.";";
	$old[8]="\$gTiempoActualizar=".$gTiempoActualizar.";";
	$new[8]="\$gTiempoActualizar=".$gTiempoActualizarNEW.";";
	$old[9]="\$gTiempoResetear=".$gTiempoResetear.";";
	$new[9]="\$gTiempoResetear=".$gTiempoResetearNEW.";";
	$old[10]="\$gTiempoVoto=".$gTiempoVoto.";";
	$new[10]="\$gTiempoVoto=".$gTiempoVotoNEW.";";
	
	config($old,$new,'data/');
	//Recargamos.	
	$HTML='<br><br><br><br><center><span class="title">'.$_UpdatingData_.'</span></center>';
	$HTML.='<script>parent.location.href="index.php?m=admin&s=html&t=custom";</script>';
}

?>