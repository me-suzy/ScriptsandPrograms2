<?php
//
//  modes/members/edit.php
//  rev002
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
if(filesize('data/index.dat')<6) {
	$HTML.='<table align="center" border="0" cellspacing="1" cellpadding="5"><tr class="1"><td align="center" class="minititle">'.$_EmptyIndex_.'</td></tr></table>';
	$paso=-1;
}
if($paso==0 OR $paso=='') {
	$HTML='<form action="index.php" method="post">'."\n";
	$HTML.='<br><table align="center" border="0" class="0" cellspacing="1" cellpadding="5">'."\n";
	$HTML.='<tr><td align="center" class="0" colspan="2"><span class="title">'.$_LoginScreen_.'</span></td></tr>'."\n";
	$HTML.='<tr class="1"><td align="right"><span class="options">'.$_WebTitle_.'</span></td><td class="2"><SELECT name="ID">'.$indice->Select(2).'</SELECT></td></tr>'."\n";
	$HTML.='<tr class="1"><td align="right" valign="top"><span class="options">'.$_Password_.'</span></td><td class="2"><INPUT TYPE="password" class="text" name="password" maxlength="50" size="40">';
	if($gEnviarCorreo) $HTML.='<br><span class="minitext">'.$_Forget_.'</span>'."\n";
	$HTML.='</td></tr>'."\n";
	$HTML.='<tr><td align="center" class="0" colspan="2"><input type="submit" class="button">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="m" value="members">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="s" value="html">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="t" value="edit">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="paso" value="1">'."\n";
	$HTML.='</td></tr>'."\n";
	$HTML.='</table></form>'."\n";
}
if($paso==1) {
	//Adaptacion de variables para PHP v4.2+
	if(isset($_POST['password'])) $password=$_POST['password']; else $password=$_GET['password'];
	//--------------------------------------
	$web=new SitioWeb($ID);
	$HTML='<form action="index.php" method="post" onSubmit="submitOnce(this);">'."\n";
	$HTML.='<table align="center" class="0" border="0" bgcolor="Black" cellspacing="1" cellpadding="5">'."\n";
	$HTML.='<tr><td align="center" class="0" colspan="2"><span class="title">'.$_ControlPanel_.'</span></td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_WebTitle_.'</span></td>'."\n";
	$HTML.='<td class="2"><span id="sWebT" class="minititle">'.$web->web.'</span></td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_Pass_.'</span></td>'."\n";
	$HTML.='<td class="2"><INPUT TYPE="PASSWORD" value="'.$web->clave.'" class="text" id="eClave" name="passwordNEW" size="30" onMouseOver="oObj=getObject(\'sClave\'); oObj.innerHTML=this.value;" onMouseOut="oObj=getObject(\'sClave\'); oObj.innerHTML=\'\';"> <span id="sClave" class="text"></span></td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_Email_.'</span></td>'."\n";
	$HTML.='<td class="2"><INPUT TYPE="TEXT" value="'.$web->email.'" class="text" id="eEmai" name="email" maxlength="'.$gMaxURL.'" size="60" onKeyUp="Contar(\'eEmai\',\'sEmai\',\''.$_CharLeft_.'\','.$gMaxURL.');" onBlur="validate(this,\'email\',\'your@email.com\');"><br><span id="sEmai" class="minitext"></span></td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_WebURL_.'</span></td>'."\n";
	$HTML.='<td class="2"><INPUT TYPE="TEXT" value="'.$web->webURL.'" class="text" id="eWebU" name="webURL" value="http://" maxlength="'.$gMaxURL.'" size="60" onKeyUp="Contar(\'eWebU\',\'sWebU\',\''.$_CharLeft_.'\','.$gMaxURL.');" onBlur="validate(this,\'url\',\'http://\');"><br><span id="sWebU" class="minitext"></span></td></tr>'."\n";
	if(count($web->bannerURL)<$gMultiBanner) {
		$HTML.='<tr><td align="right" class="1" valign="top"><INPUT type="submit" class="minibutton" value="'.$_AddBannerURL_.'"></td>';
		$HTML.=''."\n";
		$HTML.='<td class="2"><INPUT TYPE="TEXT" class="text" id="eBann" name="bannerURL" value="http://" maxlength="100" size="60" onBlur="validate(this,\'url\',\'http://\');" onKeyUp="Contar(\'eBann\',\'sBann\',\''.$_CharLeft_.'\','.$gMaxURL.');"><br><span id="sBann" class="minitext">'.str_replace('{CHAR}',$gMaxURL,$_CharLeft_).'</span></td></tr>'."\n";
	}
	if(is_array($web->bannerURL)) {
		foreach($web->bannerURL as $key=>$value) {
			$HTML.='<tr><td colspan="2" class="2" align="center">';
			$HTML.='<INPUT type="button" class="minibutton" value="'.$_DeleteBannerURL_.' '.($key+1).'" onClick="location.href=\'index.php?m=members&s=html&t=edit&paso=3&DelBannerURL='.$value.'&ID='.$ID.'&password='.$web->clave.'\'">'."\n";
			$HTML.='<br>'.$value;
			if(substr($value,-4)=='.swf') {	//Banner tipo flash
				$HTML.='<br><embed src="'.$value.'" quality="medium" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">';
			} else {
				$HTML.='<br><img src="'.$value.'" border=0>';
			}
			$HTML.='</td></tr>';
		}
	}
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_Description_.'</span></td>'."\n";
	$HTML.='<td class="2"><INPUT TYPE="TEXT" value="'.$web->descripcion.'" class="text" id="eDesc" name="descripcion" maxlength="'.$maxDescripcion.'" size="60" onKeyUp="Contar(\'eDesc\',\'sDesc\',\''.$_CharLeft_.'\','.$gMaxDescripcion.');"><br><span id="sDesc" class="minitext"></span></td></tr>'."\n";
	if($gCategorias AND !$indice->Leer($ID,3)) {
		$HTML.='<tr><td align="right" class="1"><span class="options">'.$_Category_.'</span></td>'."\n";
		$HTML.='<td class="2"><SELECT name="categoria"><OPTION value="0000000000.0000">'.$_Null_.'</OPTION>'.$categorias->Select(1,$indice->Leer($ID,1)).'</SELECT>'."\n";
	}
	if($gBanderas) {
		asort($_Country_);
		$HTML.='<tr><td align="right" class="1"><span class="options">'.$_CountryText_.' / '.$_Language_.'</span></td>'."\n";
		$HTML.='<td class="2"><SELECT name="country" onChange="oBan=getObject(\'bandera\'); oBan.src=\'images/flags/\'+ this.options[this.selectedIndex].value +\'.gif\';">'."\n";
		$HTML.='<OPTION value="unknow">'.$_SelectCountry_.'</OPTION>';
		foreach($_Country_ as $key => $value) {
			if(strlen($key)==2) {
				$HTML.='<OPTION value="'.$key.'"';
				if($web->pais==$key) $HTML.=' selected';
				$HTML.='>'.$value.'</OPTION>'."\n";
			}
		}
		$HTML.='</SELECT>';
		$HTML.=' <img id="bandera" align="absmiddle" src="images/flags/'.$web->pais.'.gif" border="0"></td></tr>'."\n";
	}
	$HTML.='<tr><td align="center" class="0" colspan="2"><input type="submit" class="button">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="m" value="members">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="s" value="html">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="t" value="edit">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="paso" value="2">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="password" value="'.$web->clave.'">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="ID" value="'.$ID.'"></td></tr>'."\n";
	$HTML.='</table></form>'."\n";
	$HTML.='<SCRIPT>'."\n";
	$HTML.='var imagen;'."\n";
	$HTML.='var cont;'."\n";
	$HTML.='var imgObjAdd=getObject(\'AddBannerIMG\');'."\n";
	$HTML.='var infObjAdd=getObject(\'AddbannerINF\');'."\n";
	$HTML.='var imgObjDel=getObject(\'DelBannerIMG\');'."\n";
	$HTML.='var infObjDel=getObject(\'DelbannerINF\');'."\n";
	$HTML.='function Cargar(url,modo) {'."\n";
	$HTML.='	url=url.toLowerCase();'."\n";
	$HTML.='	if(modo==\'Add\') {'."\n";
	$HTML.='	    imgObj=imgObjAdd;'."\n";
	$HTML.='	    infObj=infObjAdd;'."\n";
	$HTML.='	} else {'."\n";
	$HTML.='	    imgObj=imgObjDel;'."\n";
	$HTML.='	    infObj=infObjDel;'."\n";
	$HTML.='	}'."\n";
	$HTML.='    imgObj.src=\'images/no_banner.gif\';'."\n";
	$HTML.='    infObj.innerHTML=\'\';'."\n";
	$HTML.='	if(url.slice(-4)==\'.swf\') {'."\n";
	$HTML.="    	Boton='<INPUT type=\"button\" class=\"minibutton\" value=\"$_Delete_\" onClick=\"location.href=\'index.php?m=members&s=html&t=edit&paso=3&DelBannerURL=' + imagen.src + '&ID=$ID&password=$laclave\'\">&nbsp;'"."\n";
	$HTML.='		infObj.innerHTML=\'<embed src="\'+ url +\'" quality="medium" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"><br>\' + Boton;'."\n";
	$HTML.='		return 1;'."\n";
	$HTML.='	}'."\n";
	$HTML.='	cont=0;'."\n";
	$HTML.='    imagen=new Image();'."\n";
	$HTML.='    imagen.src=url;'."\n";
	$HTML.='    Mostrar(modo);'."\n";
	$HTML.='}'."\n";
	$HTML.='function Mostrar(modo) {'."\n";
	$HTML.='	if(modo==\'Add\') {'."\n";
	$HTML.='	    imgObj=imgObjAdd;'."\n";
	$HTML.='	    infObj=infObjAdd;'."\n";
	$HTML.="    	Boton='<INPUT type=\"submit\" class=\"minibutton\" value=\"$_Add_\">&nbsp;'"."\n";
	$HTML.='	} else {'."\n";
	$HTML.='	    imgObj=imgObjDel;'."\n";
	$HTML.='	    infObj=infObjDel;'."\n";
	$laclave=$web->clave;
	$HTML.="    	Boton='<INPUT type=\"button\" class=\"minibutton\" value=\"$_Delete_\" onClick=\"location.href=\'index.php?m=members&s=html&t=edit&paso=3&DelBannerURL=' + imagen.src + '&ID=$ID&password=$laclave\'\">&nbsp;'"."\n";
	$HTML.='	}'."\n";
	$HTML.='    if(imagen.complete) {'."\n";
	$HTML.='        imgObj.src=imagen.src;'."\n";
	$HTML.='        infObj.innerHTML=Boton + \'<b>\' + imagen.nameProp + \'</b> :: <b>\' + imagen.width + \' x \' + imagen.height + \'</b> :: <b>\' + Math.floor(imagen.fileSize/1024) + \'</b> KB\';'."\n";
	$HTML.='    } else {'."\n";
	$HTML.='        cont=cont+1;'."\n";
	$HTML.='        if(cont <= 20) {'."\n";
	$HTML.='            setTimeout(\'Mostrar("\' + modo + \'")\',1000);'."\n";
	$HTML.='        } else {'."\n";
	$HTML.='            imgObj.src=\'images/no_banner.gif\''."\n";
	$HTML.='            infObj.innerHTML=Boton + \'<span class="disable">&nbsp;Banner no válido&nbsp;</span>\';'."\n";
	$HTML.='        };'."\n";
	$HTML.='    }'."\n";
	$HTML.='}'."\n";
	if(is_array($web->bannerURL)) $HTML.='Cargar(\''.$web->bannerURL[0].'\',\'Del\');';
	$HTML.='</SCRIPT>'."\n";
	$HTML.='<table align="center" border="0" cellspacing="1" cellpadding="5">'."\n";
	$HTML.='<tr><td align="center" class="0"><span class="minititle">'.$_CodeHTML_.'</span></td></tr>'."\n";
	if($gBuscadorAmigable) {
		$HTML.='<tr><td align="center" class="1"><a href="'.$gTopURL.'data/'.$ID.'.php" target="_blank"><IMG src="'.$gVoteImagenSimple.'" border="0" alt="'.$gTopURL.'"></a>'."\n";
		$HTML.='<br><span class="text">&lt;a href="'.$gTopURL.'data/'.$ID.'.php" target="_blank"&gt;<br>&lt;IMG src="'.$gVoteImagenSimple.'" border="0" alt="'.$gTopURL.'"&gt;&lt;/a&gt;</span></td></tr>'."\n";
	} else {
		$HTML.='<tr><td align="center" class="1"><a href="'.$gTopURL.'in.php?ID='.$ID.'" target="_blank"><IMG src="'.$gVoteImagenSimple.'" border="0"></a>'."\n";
		$HTML.='<br><span class="text">&lt;a href="'.$gTopURL.'in.php?ID='.$ID.'" target="_blank"&gt;<br>&lt;IMG src="'.$gVoteImagenSimple.'" border="0"&gt;&lt;/a&gt;</span></td></tr>'."\n";
	}
	$HTML.='</table>'."\n";
	if($web->clave!=$password) {
		$HTML='<br><table align="center" border="0" bgcolor="Black" cellspacing="1" cellpadding="5"><tr class="0"><td align="center" class="titulo">'.$_ErrorLogin_.'</td></tr></table>';
		$paso=-1;
	}
}
if($paso==2) {	//Guardamos los datos al hacer un submit
	//Adaptacion de variables para PHP v4.2+
	$passwordNEW=$_POST['passwordNEW'];
	$email=$_POST['email'];
	$webURL=$_POST['webURL'];
	$bannerURL=$_POST['bannerURL'];
	$DelBannerURL=$_POST['DelBannerURL'];
	$descripcion=$_POST['descripcion'];
	$categoria=$_POST['categoria'];
	$country=$_POST['country'];
	$password=$_POST['password'];
	//--------------------------------------
	$nuevoSitio=new SitioWebAvanzado($ID);
	if($nuevoSitio->clave!=$password) {
		$HTML='<SCRIPT>window.location.href = "index.php";</SCRIPT>';
		exit();
	}
	if($nuevoSitio->webURL!=$webURL) $cambioURL=1;
	
	$viejo=array('\"',"\'",'|','$','<','{');
	$nuevo=array('&#'.ord('"'),'&#'.ord("'"),'&#'.ord('|'),'&#'.ord('$'),'&#'.ord('<'),'&#'.ord('{'));
	$nuevoSitio->clave=str_replace($viejo,$nuevo,$passwordNEW);
	$nuevoSitio->email=str_replace($viejo,$nuevo,$email);
	$nuevoSitio->webURL=str_replace($viejo,$nuevo,$webURL);
	$nuevoSitio->bannerURL[]=str_replace($viejo,$nuevo,$bannerURL);
	$nuevoSitio->descripcion=str_replace($viejo,$nuevo,$descripcion);
	$nuevoSitio->pais=str_replace($viejo,$nuevo,$country);
	$nuevoSitio->_Guardar();
		
	if($gCategorias OR $cambioURL) {
		if($indice->Leer($ID,1)!=$categoria OR $cambioURL) {	//Cambiamos de categoria y/o URL.
			$data[0]=$ID;
			$data[1]=$categoria;
			$data[2]=$indice->Leer($ID,2);
			$data[3]=$indice->Leer($ID,3);
			$data[4]=$indice->Leer($ID,4);
			$data[5]=$nuevoSitio->webURL;
			$indice->Escribir($ID,$data);	//Lo guarda automaticamente.
		}
	}
		
	//Recargamos.	
	$HTML='<br><br><br><br><center><span class="title">'.$_UpdatingData_.'</span></center>';
	$HTML.='<script>window.location.href="index.php?m=members&s=html&t=edit&paso=1&ID='.$ID.'&password='.$nuevoSitio->clave.'";</script>';
}
if($paso==3) {	//Borramos un banner
	//Adaptacion de variables para PHP v4.2+
	if(isset($_POST['DelBannerURL'])) $DelBannerURL=$_POST['DelBannerURL']; else $DelBannerURL=$_GET['DelBannerURL'];
	if(isset($_POST['password'])) $password=$_POST['password']; else $password=$_GET['password'];
	//--------------------------------------
	$nuevoSitio=new SitioWebAvanzado($ID);
	if($nuevoSitio->clave!=$password) {
		$HTML='<SCRIPT>window.location.href = "index.php";</SCRIPT>';
		exit();
	}
	//Sólo borramos la url que nos han pasado.
	if(is_array($nuevoSitio->bannerURL)) {
		foreach($nuevoSitio->bannerURL as $value) {
			if($value!==$DelBannerURL) $banners[]=$value;
		}
	}
	$nuevoSitio->bannerURL=$banners;
	$nuevoSitio->_Guardar();
	
	//Recargamos
	$HTML='<br><br><br><br><center><span class="title">'.$_UpdatingData_.'</span></center>';
	$HTML.='<script>window.location.href="index.php?m=members&s=html&t=edit&paso=1&ID='.$ID.'&password='.$nuevoSitio->clave.'";</script>';
}

?>