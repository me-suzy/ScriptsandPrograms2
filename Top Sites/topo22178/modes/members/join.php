<?php
//
//  modes/members/join.php
//  rev003
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
// CONTENIDO
//----------------------------------------------------------------
if($paso==1 OR $paso=='') {
	$ID=ej3Time();
	$HTML='<form action="index.php" method="post" onSubmit="submitOnce(this);">'."\n";
	$HTML.='<table align="center" class="0" border="0" cellspacing="1" cellpadding="5">'."\n";
	$HTML.='<tr><td align="center" class="0" colspan="2"><span class="title">'.$_NewMember_.'</span></td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_Email_.'</span></td>'."\n";
	$HTML.='<td class="2"><INPUT TYPE="TEXT" class="text" id="eEmai" name="email" maxlength="'.$gMaxURL.'" size="60" onKeyUp="Contar(\'eEmai\',\'sEmai\',\''.$_CharLeft_.'\','.$gMaxURL.');" onBlur="validate(this,\'email\',\'your@email.com\');"><br><span id="sEmai" class="minitext"></span></td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_WebTitle_.'</span></td>'."\n";
	$HTML.='<td class="2"><INPUT TYPE="TEXT" class="text" id="eWebT" name="web" maxlength="60" size="60" onKeyUp="Contar(\'eWebT\',\'sWebT\',\''.$_CharLeft_.'\',60);"><br><span id="sWebT" class="minitext"></span></td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_WebURL_.'</span></td>'."\n";
	$HTML.='<td class="2"><INPUT TYPE="TEXT" class="text" id="eWebU" name="webURL" value="http://" maxlength="'.$gMaxURL.'" size="60" onKeyUp="Contar(\'eWebU\',\'sWebU\',\''.$_CharLeft_.'\','.$gMaxURL.');" onBlur="validate(this,\'url\',\'http://\');"><br><span id="sWebU" class="minitext"></span></td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_BannerURL_.'</span></td>'."\n";
	$HTML.='<td class="2"><INPUT TYPE="TEXT" class="text" id="eBann" name="bannerURL" value="http://" maxlength="100" size="60" onBlur="validate(this,\'url\',\'http://\'); Cargar(this.value);" onKeyUp="Contar(\'eBann\',\'sBann\',\''.$_CharLeft_.'\','.$gMaxURL.');"><br><span id="sBann" class="minitext">'.str_replace('{CHAR}',$gMaxURL,$_CharLeft_).'</span><br><img id="bannerIMG" src="images/no_banner.gif"><br><span id="bannerINF" class="minitext"></span></td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_Description_.'</span></td>'."\n";
	$HTML.='<td class="2"><INPUT TYPE="TEXT" class="text" id="eDesc" name="descripcion" maxlength="'.$maxDescripcion.'" size="60" onKeyUp="Contar(\'eDesc\',\'sDesc\',\''.$_CharLeft_.'\','.$gMaxDescripcion.');"><br><span id="sDesc" class="minitext"></span></td></tr>'."\n";
	if($gCategorias) {
		if($categorias->numRegistros) {
			$HTML.='<tr><td align="right" class="1"><span class="options">'.$_Category_.'</span></td>'."\n";
			$HTML.='<td class="2"><SELECT name="categoria">'.$categorias->Select(1).'</SELECT>'."\n";
		}
	}
	if($gBanderas) {
		asort($_Country_);
		$HTML.='<tr><td align="right" class="1"><span class="options">'.$_CountryText_.'</span></td>'."\n";
		$HTML.='<td class="2"><SELECT name="country" onChange="oBan=getObject(\'bandera\'); oBan.src=\'images/flags/\'+ this.options[this.selectedIndex].value +\'.gif\';">'."\n";
		$HTML.='<OPTION value="unknow">'.$_SelectCountry_.'</OPTION>';
		foreach($_Country_ as $key => $value) {
			if(strlen($key)==2) $HTML.='<OPTION value="'.$key.'">'.$value.'</OPTION>'."\n";
		}
		$HTML.='</SELECT>';
		$HTML.=' <img id="bandera" align="absmiddle" src="images/flags/unknow.gif" border="0"></td></tr>'."\n";
	}
	$HTML.='<tr><td align="center" class="0" colspan="2"><input type="submit" class="button">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="m" value="members">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="s" value="html">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="t" value="join">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="paso" value="2">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="ID" value="'.$ID.'"></td></tr>'."\n";
	$HTML.='</table></form>'."\n";
	$HTML.='<SCRIPT>'."\n";
	$HTML.='var imagen;'."\n";
	$HTML.='var cont;'."\n";
	$HTML.='var imgObj=getObject(\'bannerIMG\');'."\n";
	$HTML.='var infObj=getObject(\'bannerINF\');'."\n";
	$HTML.='function Cargar(url) {'."\n";
	$HTML.='	url=url.toLowerCase();'."\n";
	$HTML.='	imgObj.src=\'images/no_banner.gif\';'."\n";
	$HTML.='	infObj.innerHTML=\'\';'."\n";
	$HTML.='	if(url.slice(-4)==\'.swf\') {'."\n";
	$HTML.='		infObj.innerHTML=\'<embed src="\'+ url +\'" quality="medium" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">\';'."\n";
	$HTML.='		return 1;'."\n";
	$HTML.='	}'."\n";
	$HTML.='	cont=0;'."\n";
	$HTML.='    imagen=new Image();'."\n";
	$HTML.='    imagen.src=url;'."\n";
	$HTML.='    Mostrar();'."\n";
	$HTML.='}'."\n";
	$HTML.='function Mostrar() {'."\n";
	$HTML.='    if(imagen.complete) {'."\n";
	$HTML.='        imgObj.src=imagen.src;'."\n";
	$HTML.='        infObj.innerHTML=\'<b>\' + imagen.nameProp + \'</b> :: <b>\' + imagen.width + \' x \' + imagen.height + \'</b> :: <b>\' + Math.floor(imagen.fileSize/1024) + \'</b> KB\';'."\n";
	$HTML.='    } else {'."\n";
	$HTML.='        cont=cont+1;'."\n";
	$HTML.='        if(cont <= 20) {'."\n";
	$HTML.='            setTimeout(\'Mostrar()\',1000);'."\n";
	$HTML.='        } else {'."\n";
	$HTML.='            imgObj.src=\'images/no_banner.gif\''."\n";
	$HTML.='            infObj.innerHTML=\'<span class="disable">&nbsp;Banner no válido&nbsp;</span>\';'."\n";
	$HTML.='        };'."\n";
	$HTML.='    }'."\n";
	$HTML.='}'."\n";
	$HTML.='</SCRIPT>'."\n";
}
if($paso==2) {
	//Adaptacion de variables para PHP v4.2+
	$email=$_POST['email'];
	$web=$_POST['web'];
	$webURL=$_POST['webURL'];
	$bannerURL=$_POST['bannerURL'];
	$descripcion=$_POST['descripcion'];
	$categoria=$_POST['categoria'];
	$country=$_POST['country'];
	//--------------------------------------
	$nuevoSitio=new SitioWebAvanzado($ID);
	
	$viejo=array('\"',"\'",'|','$','<','{');
	$nuevo=array('&#'.ord('"'),'&#'.ord("'"),'&#'.ord('|'),'&#'.ord('$'),'&#'.ord('<'),'&#'.ord('{'));
	$nuevoSitio->clave=str_replace($viejo,$nuevo,intval(substr($ID,-4)*(1+substr($ID,-6,1))));
	$nuevoSitio->email=str_replace($viejo,$nuevo,$email);
	$nuevoSitio->web=str_replace($viejo,$nuevo,$web);
	$nuevoSitio->webURL=str_replace($viejo,$nuevo,$webURL);
	$nuevoSitio->bannerURL[0]=str_replace($viejo,$nuevo,$bannerURL);
	$nuevoSitio->descripcion=str_replace($viejo,$nuevo,$descripcion);
	$nuevoSitio->pais=str_replace($viejo,$nuevo,$country);
	$nuevoSitio->Crear();
	
	$data[0]=$ID;
	$data[1]='0000000000.0000';
	if($gCategorias) {
		if(isset($categoria)) $data[1]=$categoria;
	}
	$data[2]=1-$gVistoBueno;
	$data[3]=0;
	$data[4]=$nuevoSitio->web;
	$data[5]=$nuevoSitio->webURL;
	$indice->Escribir($ID,$data);
	
	//Generación del código de votación
	$_Mailed_=str_replace('{EMAIL}',$nuevoSitio->email,$_Mailed_);
	if($gEnviarCorreo) {
		//Mandamos los emails necesarios.
		$to=$nuevoSitio->email;
		$subject=$gTopNombre." :: ".$_NewMember_;
		$accountInfo=$_AccountInfo_."\n";
		$accountInfo.=$_WebTitle_.": ".$nuevoSitio->web."\n";
		$accountInfo.=$_Pass_.": ".$nuevoSitio->clave."\n";
		$accountInfo.=$_WebURL_.": ".$nuevoSitio->webURL."\n";
		$accountInfo.=$_ID_.": ".$ID."\n";
		$accountInfo.=$_ControlPanel_.": ".$gTopURL."index.php?m=members&s=html&t=edit\n";
		$accountInfo.=$_CodeHTML_.":\n";
		if($gBuscadorAmigable) {
			$accountInfo.='<a href="'.$gTopURL.'data/'.$ID.'.php" target="_blank"><IMG src="'.$gVoteImagenSimple.'" border="0" alt="'.$gTopURL.'"></a>'."\n";
		} else {
			$accountInfo.='<a href="'.$gTopURL.'in.php?ID='.$ID.'" target="_blank"><IMG src="'.$gVoteImagenSimple.'" border="0"></a>'."\n";
		}
		$linea=file("data/welcome.htm");
		foreach($linea as $value) $text.=$value;
		$text=str_replace('<BR>',"\n",$text);
		$text=str_replace('{ACCOUNT_INFO}',"\n\n".$accountInfo,$text);
		$text.="----------------------------------------------------\n";
		$text.=$gTopNombre."\n";
		$text.=$gAdminEmail."\n";
		$text.=$gTopURL."\n";
		$text.="----------------------------------------------------\n";

		$email=new Email();
		$email->set_from($gAdminEmail,$gTopNombre);
		$email->set_to($to);
		$email->add_to($gAdminEmail);
		$email->set_subject($subject);
		$email->set_text($text);
		$email->send();
	}
	$HTML='<table align="center" class="0" border="0" cellspacing="1" cellpadding="5">'."\n";
	$HTML.='<tr><td align="center" class="0" colspan="2"><span class="title">'.$_NewMember_.'</span></td></tr>'."\n";
	$HTML.='<tr><td align="center" class="1" colspan="2"><span class="minititle">'.$_NewMemberInfo_.'</span></td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_WebTitle_.'</span></td>'."\n";
	$HTML.='<td class="2">'.$nuevoSitio->web.'</td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_Pass_.'</span></td>'."\n";
	$HTML.='<td class="2">'.$nuevoSitio->clave.'</td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_ControlPanel_.'</span></td>'."\n";
	$HTML.='<td class="2"><a href="index.php?m=members&s=html&t=edit">'.$gTopURL.'index.php?m=members&s=html&t=edit</a></td></tr>'."\n";
	$HTML.='<tr><td align="center" class="0" colspan="2"><input type="button" class="minibutton" value="'.$_Back_.'" onClick="history.back();">'."\n";
	$HTML.='&nbsp;<input type="button" class="minibutton" value="'.$_GoTop_.'" onClick="location.href=\'index.php\'">&nbsp;<input type="button" class="minibutton" value="'.$_ControlPanel_.'" onClick="location.href=\'index.php?m=members&s=html&t=edit&paso=1&ID='.$ID.'&password='.$nuevoSitio->clave.'\';"></td></tr>'."\n";
	$HTML.='</table>'."\n";
	if($gEnviarCorreo) $HTML.='<br><table align="center" border="0" cellspacing="1" cellpadding="5"><tr class="2"><td align="center" class="minititle">'.$_Mailed_.'</td></tr></table>';
	$HTML.='<br><table align="center" border="0" cellspacing="1" cellpadding="5">'."\n";
	$HTML.='<tr><td align="center" class="0"><span class="minititle">'.$_CodeHTML_.'</span></td></tr>'."\n";
	if($gBuscadorAmigable) {
		$HTML.='<tr><td align="center" class="1"><a href="'.$gTopURL.'data/'.$ID.'.php" target="_blank"><IMG src="'.$gVoteImagenSimple.'" border="0" alt="'.$gTopURL.'"></a>'."\n";
		$HTML.='<br><span class="text">&lt;a href="'.$gTopURL.'data/'.$ID.'.php" target="_blank"&gt;<br>&lt;IMG src="'.$gVoteImagenSimple.'" border="0" alt="'.$gTopURL.'"&gt;&lt;/a&gt;</span></td></tr>'."\n";
	} else {
		$HTML.='<tr><td align="center" class="1"><a href="'.$gTopURL.'in.php?ID='.$ID.'" target="_blank"><IMG src="'.$gVoteImagenSimple.'" border="0"></a>'."\n";
		$HTML.='<br><span class="text">&lt;a href="'.$gTopURL.'in.php?ID='.$ID.'" target="_blank"&gt;<br>&lt;IMG src="'.$gVoteImagenSimple.'" border="0"&gt;&lt;/a&gt;</span></td></tr>'."\n";
	}
	$HTML.='</table>'."\n";
}

?>