<?php
//
//  modes/admin/banners.php
//	rev006
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
	$linea=file("data/bannertop.htm");
	foreach($linea as $value) $BannerSupTEXTO.=str_replace(array("\n","\r","\r\n"),array('','',''),$value);
	$BannerSupTEXTO='<br><TEXTAREA name="BannerSupTEXTO" rows="4" cols="80" wrap="VIRTUAL" onBlur="show_textarea(this,1);">'.$BannerSupTEXTO.'</TEXTAREA><br><span id="uno">'.$BannerSupTEXTO.'</span>';	
	$linea=file("data/bannermiddle.htm");
	foreach($linea as $value) $BannerMedTEXTO.=str_replace(array("\n","\r","\r\n"),array('','',''),$value);
	$BannerMedTEXTO='<br><TEXTAREA name="BannerMedTEXTO" rows="4" cols="80" wrap="VIRTUAL" onBlur="show_textarea(this,2);">'.$BannerMedTEXTO.'</TEXTAREA><br><span id="dos">'.$BannerMedTEXTO.'</span>';
	$linea=file("data/bannerbottom.htm");
	foreach($linea as $value) $BannerInfTEXTO.=str_replace(array("\n","\r","\r\n"),array('','',''),$value);
	$BannerInfTEXTO='<br><TEXTAREA name="BannerInfTEXTO" rows="4" cols="80" wrap="VIRTUAL" onBlur="show_textarea(this,3);">'.$BannerInfTEXTO.'</TEXTAREA><br><span id="tres">'.$BannerInfTEXTO.'</span>';
	$linea=file("data/rules.htm");
	foreach($linea as $value) $ReglasTEXTO.=str_replace(array("\n","\r","\r\n"),array('','',''),$value);
	$ReglasTEXTO='<br><TEXTAREA name="ReglasTEXTO" rows="4" cols="80" wrap="VIRTUAL">'.$ReglasTEXTO.'</TEXTAREA>';
	$linea=file("data/notice.htm");
	foreach($linea as $value) $AvisoTEXTO.=str_replace(array("\n","\r","\r\n"),array('','',''),$value);
	$AvisoTEXTO='<br><TEXTAREA name="AvisoTEXTO" rows="4" cols="80" wrap="VIRTUAL">'.$AvisoTEXTO.'</TEXTAREA>';
	$linea=file("data/welcome.htm");
	foreach($linea as $value) $EmailTEXTO.=$value;
	
	$HTML.='<SCRIPT>'."\n";
	$HTML.='function textarea(othis,spanID) {'."\n";
	$HTML.='	oSpan=getObject(spanID);'."\n";
	$HTML.='	if(othis.options[othis.selectedIndex].value==1) {'."\n";
	$HTML.='		switch(spanID) {'."\n";
	$HTML.='			case "BannerSup":'."\n";
	$HTML.='				oSpan.innerHTML=\''.$BannerSupTEXTO.'\';'."\n";
	$HTML.='				break;'."\n";
	$HTML.='			case "BannerMed":'."\n";
	$HTML.='				oSpan.innerHTML=\''.$BannerMedTEXTO.'\';'."\n";
	$HTML.='				break;'."\n";
	$HTML.='			case "BannerInf":'."\n";
	$HTML.='				oSpan.innerHTML=\''.$BannerInfTEXTO.'\';'."\n";
	$HTML.='				break;'."\n";
	$HTML.='			case "Reglas":'."\n";
	$HTML.='				oSpan.innerHTML=\''.$ReglasTEXTO.'\';'."\n";
	$HTML.='				break;'."\n";
	$HTML.='			case "Aviso":'."\n";
	$HTML.='				oSpan.innerHTML=\''.$AvisoTEXTO.'\';'."\n";
	$HTML.='				break;'."\n";
	$HTML.='		}';
	$HTML.='	} else {'."\n";
	$HTML.='		oSpan.innerHTML=\'\';'."\n";
	$HTML.='	}'."\n";
	$HTML.='}'."\n";
	$HTML.='function show_textarea(othis,numSpan) {'."\n";
	$HTML.='		switch(numSpan) {'."\n";
	$HTML.='			case 1:'."\n";
	$HTML.='				oSpan=getObject("uno");'."\n";
	$HTML.='				break;'."\n";
	$HTML.='			case 2:'."\n";
	$HTML.='				oSpan=getObject("dos");'."\n";
	$HTML.='				break;'."\n";
	$HTML.='			case 3:'."\n";
	$HTML.='				oSpan=getObject("tres");'."\n";
	$HTML.='				break;'."\n";
	$HTML.='		}';
	$HTML.='		oSpan.innerHTML=othis.value;'."\n";	
	$HTML.='}'."\n";
	$HTML.='</SCRIPT>'."\n";
	$HTML.='<FORM action="index.php" method="post" onSubmit="submitOnce(this);">';
	$HTML.='<TABLE align="center" class="0" border="0" cellpadding="2" cellspacing="1">';
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_AdBanners_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right" valign="top"><span class="options">'.$_BannerTop_.'</span></TD><TD class="2"><SELECT name="gVerBannerSupNEW" onChange="textarea(this,\'BannerSup\');">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gVerBannerSup==1) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="2"';
	if($gVerBannerSup==2) $HTML.=' selected';
	$HTML.='>AutoBanner</OPTION>';
	$HTML.='</SELECT>';
	$HTML.='<span id="BannerSup">';
	if($gVerBannerSup==1) $HTML.=$BannerSupTEXTO;
	$HTML.='</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right" valign="top"><span class="options">'.$_BannerMiddle_.'</span></TD><TD class="2"><SELECT name="gVerBannerMedNEW" onChange="textarea(this,\'BannerMed\');">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gVerBannerMed==1) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="2"';
	if($gVerBannerMed==2) $HTML.=' selected';
	$HTML.='>AutoBanner</OPTION>';
	$HTML.='</SELECT>';
	$HTML.='<span id="BannerMed">';
	if($gVerBannerMed==1) $HTML.=$BannerMedTEXTO;
	$HTML.='</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right" valign="top"><span class="options">'.$_BannerBottom_.'</span></TD><TD class="2"><SELECT name="gVerBannerInfNEW" onChange="textarea(this,\'BannerInf\');">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gVerBannerInf==1) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="2"';
	if($gVerBannerInf==2) $HTML.=' selected';
	$HTML.='>AutoBanner</OPTION>';
	$HTML.='</SELECT>';
	$HTML.='<span id="BannerInf">';
	if($gVerBannerInf==1) $HTML.=$BannerInfTEXTO;
	$HTML.='</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_Rules_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right" valign="top"><span class="options">'.$_Rules_.'</span></TD><TD class="2"><SELECT name="gReglasNEW" onChange="textarea(this,\'Reglas\');">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gReglas==1) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT>';
	$HTML.='<span id="Reglas">';
	if($gReglas==1) $HTML.=$ReglasTEXTO;
	$HTML.='</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_Notice_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right" valign="top"><span class="options">'.$_Notice_.'</span></TD><TD class="2"><SELECT name="gAvisoNEW" onChange="textarea(this,\'Aviso\');">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gAviso==1) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT>';
	$HTML.='<span id="Aviso">';
	if($gAviso==1) $HTML.=$AvisoTEXTO;
	$HTML.='</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_WelcomeEmail_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="center" colspan="2"><TEXTAREA name="EmailTEXTO" rows="6" cols="100" wrap="VIRTUAL">'.$EmailTEXTO.'</TEXTAREA><br><span class="minitext">'.$_WelcomeEmailInfo_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="0" align="center" colspan="2">';
	$HTML.='<INPUT type="hidden" name="m" value="admin">';
	$HTML.='<INPUT type="hidden" name="s" value="html">';	
	$HTML.='<INPUT type="hidden" name="t" value="banners">';
	$HTML.='<INPUT type="hidden" name="paso" value="1">';	
	$HTML.='<INPUT type="reset" class="button">&nbsp;<INPUT type="submit" class="button"></TD></TR>'."\n";	
	$HTML.='</TABLE></FORM>';
}

if($paso==1) {
	//Adaptacion de variables para PHP v4.2+
	$gVerBannerSupNEW=$_POST['gVerBannerSupNEW'];
	$gVerBannerMedNEW=$_POST['gVerBannerMedNEW'];
	$gVerBannerInfNEW=$_POST['gVerBannerInfNEW'];
	$gReglasNEW=$_POST['gReglasNEW'];
	$gAvisoNEW=$_POST['gAvisoNEW'];
	$BannerSupTEXTO=$_POST['BannerSupTEXTO'];
	$BannerMedTEXTO=$_POST['BannerMedTEXTO'];
	$BannerInfTEXTO=$_POST['BannerInfTEXTO'];
	$ReglasTEXTO=$_POST['ReglasTEXTO'];
	$AvisoTEXTO=$_POST['AvisoTEXTO'];
	$EmailTEXTO=$_POST['EmailTEXTO'];
	//--------------------------------------
	$old[0]="\$gVerBannerSup=".$gVerBannerSup.";";
	$new[0]="\$gVerBannerSup=".$gVerBannerSupNEW.";";
	$old[1]="\$gVerBannerMed=".$gVerBannerMed.";";
	$new[1]="\$gVerBannerMed=".$gVerBannerMedNEW.";";
	$old[2]="\$gVerBannerInf=".$gVerBannerInf.";";
	$new[2]="\$gVerBannerInf=".$gVerBannerInfNEW.";";
	$old[3]="\$gReglas=".$gReglas.";";
	$new[3]="\$gReglas=".$gReglasNEW.";";
	$old[4]="\$gAviso=".$gAviso.";";
	$new[4]="\$gAviso=".$gAvisoNEW.";";
	
	if($gVerBannerSupNEW) {
		$fp=fopen('data/bannertop.htm','w');
		flock($fp,2);
		fwrite($fp,stripslashes($BannerSupTEXTO));
		fclose($fp);
	}
	if($gVerBannerMedNEW) {
		$fp=fopen('data/bannermiddle.htm','w');
		flock($fp,2);
		fwrite($fp,stripslashes($BannerMedTEXTO));
		fclose($fp);
	}
	if($gVerBannerInfNEW) {
		$fp=fopen('data/bannerbottom.htm','w');
		flock($fp,2);
		fwrite($fp,stripslashes($BannerInfTEXTO));
		fclose($fp);
	}
	if($gReglasNEW) {
		$fp=fopen('data/rules.htm','w');
		flock($fp,2);
		fwrite($fp,stripslashes($ReglasTEXTO));
		fclose($fp);
	}
	if($gAvisoNEW) {
		$fp=fopen('data/notice.htm','w');
		flock($fp,2);
		fwrite($fp,stripslashes($AvisoTEXTO));
		fclose($fp);
	}
	$fp=fopen('data/welcome.htm','w');
	flock($fp,2);
	fwrite($fp,stripslashes($EmailTEXTO));
	fclose($fp);
	
	config($old,$new,'data/');
	//Recargamos.	
	$HTML='<br><br><br><br><center><span class="title">'.$_UpdatingData_.'</span></center>';
	$HTML.='<script>location.href="index.php?m=admin&s=html&t=banners";</script>';
}

?>