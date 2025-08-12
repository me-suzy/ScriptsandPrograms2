<?php
//
//  modes/admin/config.php
//	rev004
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
	$directorio=opendir('lang');
    if($file=readdir($directorio)) {
        do {
            $ext=strrchr($file,'.');
            //$aux=explode('.',$file);
            if ($file!="." AND $file!=".." AND ($ext==".php" OR $ext==".PHP")) {
                $lang_O[]=str_replace('.php','',$file);
            }
        } while ($file = readdir($directorio));
        closedir($directorio);
	}
	$HTML='<FORM action="index.php" method="post" onSubmit="submitOnce(this);">';
	$HTML.='<TABLE align="center" class="0" border="0" cellpadding="2" cellspacing="1">';
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_AdminInfo_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_AdminLogin_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gAdminLoginNEW" value="'.$gAdminLogin.'" onBlur="validate(this,\'text\',\''.$gAdminLogin.'\',0);" maxlength="30" size="31"></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_AdminPass_.'</span></TD>';
	$HTML.='<TD class="2"><INPUT type="password" class="text" name="gAdminPassNEW" value="'.$gAdminPass.'" onBlur="validate(this,\'text\',\''.$gAdminPass.'\',0);" maxlength="30" size="31" onMouseOver="oObj=getObject(\'sClave\'); oObj.innerHTML=this.value;" onMouseOut="oObj=getObject(\'sClave\'); oObj.innerHTML=\'\';"> <span id="sClave"></span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_AdminEmail_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gAdminEmailNEW" value="'.$gAdminEmail.'" onBlur="validate(this,\'email\',\''.$gAdminEmail.'\',0);" size="31"></TD></TR>'."\n";
	$HTML.='<TR><TD class="0" align="center" colspan="2"><span class="minititle">'.$_TopInfo_.'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_TopName_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gTopNombreNEW" value="'.$gTopNombre.'" onBlur="validate(this,\'text\',\''.$gTopNombre.'\',0);" maxlength="50" size="40"></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_TopMetaTags_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gTopMetaTagsNEW" value="'.$gTopMetaTags.'" onBlur="validate(this,\'text\',\''.$gTopMetaTags.'\',0);" maxlength="100" size="40"></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_TopCopyright_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gTopCopyrightNEW" value="'.$gTopCopyright.'" onBlur="validate(this,\'text\',\''.$gTopCopyright.'\',0);" maxlength="100" size="40"></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right" valign="top"><span class="options">'.$_Language_.'</span></TD><TD class="2"><SELECT name="gIdiomaNEW">';
	foreach($lang_O as $value) {
		$HTML.='<OPTION value="'.$value.'"';
		if($gIdioma==$value) $HTML.=' selected';
		$HTML.='>'.$value.'</OPTION>';
	}
	$HTML.='</SELECT>';
	$HTML.='<br><span class="minitext"><b>File:</b> '.$gIdioma.'.php for <b>TOPo v</b>'.htmlentities($_LangForVersion_).'</span>'."\n";
	$HTML.='<br><span class="minitext"><b>Author:</b> '.htmlentities($_LangAuthor_).'</span></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right" valign="top"><span class="options">'.$_TopURL_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gTopURLNEW" value="'.$gTopURL.'" onBlur="validate(this,\'url\',0,0);" maxlength="100" size="50">';
	$HTML.='<br><span class="minitext"><b>HostMask:</b> '.$gTopURLhost.'</span>';
	$HTML.='<br><span class="minitext"><b>Server IP:</b> '.$gTopURLip.'</span>';
	$HTML.='</TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_VoteURL_.'</span></TD><TD class="2"><INPUT type="text" class="text" name="gVoteImagenSimpleNEW" value="'.$gVoteImagenSimple.'" onBlur="validate(this,\'url\',0,0);" maxlength="100" size="50"></TD></TR>'."\n";
	$HTML.='<TR><TD class="1" align="right"><span class="options">'.$_SendMail_.'</span></TD><TD class="2"><SELECT name="gEnviarCorreoNEW">';
	$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
	$HTML.='<OPTION class="enable" value="1"';
	if($gEnviarCorreo) $HTML.=' selected';
	$HTML.='>'.$_Enable_.'</OPTION>';
	$HTML.='</SELECT></TD></TR>'."\n";
	$HTML.='<TR><TD class="0" align="center" colspan="2">';
	$HTML.='<INPUT type="hidden" name="m" value="admin">';
	$HTML.='<INPUT type="hidden" name="s" value="html">';	
	$HTML.='<INPUT type="hidden" name="t" value="config">';
	$HTML.='<INPUT type="hidden" name="paso" value="1">';	
	$HTML.='<INPUT type="reset" class="button">&nbsp;<INPUT type="submit" class="button"></TD></TR>'."\n";	
	$HTML.='</TABLE></FORM>';
}

if($paso==1) {
	//Adaptacion de variables para PHP v4.2+
	$gAdminLoginNEW=$_POST['gAdminLoginNEW'];
	$gAdminPassNEW=$_POST['gAdminPassNEW'];
	$gAdminEmailNEW=$_POST['gAdminEmailNEW'];
	$gTopNombreNEW=$_POST['gTopNombreNEW'];
	$gTopMetaTagsNEW=$_POST['gTopMetaTagsNEW'];
	$gTopCopyrightNEW=$_POST['gTopCopyrightNEW'];
	$gTopURLNEW=$_POST['gTopURLNEW'];
	$gVoteImagenSimpleNEW=$_POST['gVoteImagenSimpleNEW'];
	$gEnviarCorreoNEW=$_POST['gEnviarCorreoNEW'];
	$gIdiomaNEW=$_POST['gIdiomaNEW'];
	//--------------------------------------
	$old[0]="\$gAdminLogin='".$gAdminLogin."';";
	$new[0]="\$gAdminLogin='".$gAdminLoginNEW."';";
	$old[1]="\$gAdminPass='".$gAdminPass."';";
	$new[1]="\$gAdminPass='".$gAdminPassNEW."';";
	$old[2]="\$gAdminEmail='".$gAdminEmail."';";
	$new[2]="\$gAdminEmail='".$gAdminEmailNEW."';";
	$old[3]="\$gTopNombre='".$gTopNombre."';";
	$new[3]="\$gTopNombre='".$gTopNombreNEW."';";
	$old[4]="\$gTopMetaTags='".$gTopMetaTags."';";
	$new[4]="\$gTopMetaTags='".$gTopMetaTagsNEW."';";
	$old[5]="\$gTopCopyright='".$gTopCopyright."';";
	$new[5]="\$gTopCopyright='".$gTopCopyrightNEW."';";
	$old[6]="\$gTopURL='".$gTopURL."';";
	$new[6]="\$gTopURL='".$gTopURLNEW."';";
	$old[7]="\$gTopURLhost='".$gTopURLhost."';";
	$new[7]="\$gTopURLhost='".gethostbyaddr($_SERVER['SERVER_ADDR'])."';";
	$old[8]="\$gTopURLip='".$gTopURLip."';";
	$new[8]="\$gTopURLip='".$_SERVER['SERVER_ADDR']."';";
	$old[9]="\$gVoteImagenSimple='".$gVoteImagenSimple."';";
	$new[9]="\$gVoteImagenSimple='".$gVoteImagenSimpleNEW."';";
	$old[10]="\$gEnviarCorreo=".$gEnviarCorreo.";";
	$new[10]="\$gEnviarCorreo=".$gEnviarCorreoNEW.";";
	$old[11]="\$gIdioma='".$gIdioma."';";
	$new[11]="\$gIdioma='".$gIdiomaNEW."';";
	
	config($old,$new,'data/');
	//Recargamos.	
	$HTML='<br><br><br><br><center><span class="title">'.$_UpdatingData_.'</span></center>';
	$HTML.='<script>parent.location.href="index.php?m=admin&s=html&t=config";</script>';
}

?>