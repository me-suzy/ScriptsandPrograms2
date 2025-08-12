<?php
//
//  modes/admin/webs.php
//	rev014
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
if(isset($_POST['criterio'])) $criterio=$_POST['criterio']; else $criterio=$_GET['criterio'];

//----------------------------------------------------------------
// CONTENIDO
//----------------------------------------------------------------
$indice=new Index;
$categorias=new Categorias;

if($paso=='listado' OR $paso=='') {
	if(!isset($criterio)) $criterio=4;
	$listado=$indice->Tabla($criterio);
	//if(isset($ID)) $webID='&ID='.$ID;
	$num_pag=ceil($indice->numRegistros/$gWebsPorPagina);
	$HTML='<TABLE width="95%" align="center" border="0" cellpading="1" cellspacing="1">';
	$HTML.='<TR class="0">';
	if($num_pag) {
		$HTML.='<TD align="center"><SELECT onChange="window.location.href=this.options[this.selectedIndex].value;">';
		for($i=0;$i<$num_pag;$i++) {
			$HTML.='<OPTION value="index.php?m=admin&s=html&t=webs&paso=listado&criterio='.$criterio.'&pag='.$i.'"';
			if($pag==$i)$HTML.=' selected';
			$HTML.='>'.$_Page_.' '.($i+1).'</OPTION>';
		}
		$HTML.='</SELECT></TD>';
	}
	$HTML.='<TD colspan="5" align="center"><span class="minititle">'.str_replace('{NUMBER}',$indice->numRegistros,$_NumberSites_).'</span></TD></TR>';
	if(is_array($listado)) {	
		$HTML.='<TR class="0"><TD align="center" class="minititle">'.$_Date_.' <a href="index.php?m=admin&s=html&t=webs&paso=listado&criterio=0"><img src="themes/'.$gTema.'/icon_asc.gif" border=0 align="absmiddle"></a> <a href="index.php?m=admin&s=html&t=webs&paso=listado"><img src="themes/'.$gTema.'/icon_desc.gif" border=0 align="absmiddle"></a></TD>';
		$HTML.='<TD align="center" class="minititle"><a href="index.php?m=admin&s=html&t=webs&paso=listado&criterio=4">'.$_WebTitle_.'</a></TD>';
		if($gCategorias) $HTML.='<TD align="center" class="minititle">'.$_Categories_.'</TD>';
		if($gVistoBueno) $HTML.='<TD align="center" class="minititle">'.$_ShowSite_.'</TD>';
		$HTML.='<TD align="center" class="minititle">'.$_Delete_.'</TD>';
		$HTML.='</TR>';
		$i=0;
		foreach($listado as $value) {
			$i++;
			if($i<$pag*$gWebsPorPagina) continue;
			if(($pag+1)*$gWebsPorPagina<$i) break;
			$aux=explode('||',$value);
			$HTML.='<TR class="'.(1+$i%2).'"><TD align="center"><span class="minitext">'.ej3Date('fechaCorta',$aux[0]).'</span></TD>';
			$HTML.='<TD>&nbsp;<INPUT type="button" class="minibutton" value="'.$_Edit_.'" onClick="ventana(\'index.php?m=admin&s=html&t=webs&paso=editarWeb&ID='.$aux[0].'\',\'_blank\',650,501);">';
			$HTML.='&nbsp;<a href="index.php?m=top&s=out&ID='.$aux[0].'" target="_blank"';
			if($gEstaPersonales) $HTML.=EstaPersonal($aux[0]);
			$HTML.='>'.$aux[4].'</a></TD>';
			if($gCategorias) {
				$cat=$_Null_;
				if($aux[1]!='0000000000.0000') $cat=$categorias->Leer($aux[1],2);
				$HTML.='<TD align="center">'.$cat.'</TD>';
			}
			if($gVistoBueno) {
				if($aux[2]) {
					$HTML.='<TD align="center" class="enable">'.$_Enable_.'</TD>';
				} else {
					$HTML.='<TD align="center" class="disable">'.$_Disable_.'</TD>';
				}
			}
			$HTML.='<TD align="center"><INPUT type="button" class="minibutton" value="'.$_Delete_.'" onClick="if(window.confirm(\''.$_DeleteSite_.':\n'.str_replace("&#39","",$aux[4]).'\n'.$_Confirm_.'\')) { document.location.href=\'index.php?m=admin&s=html&t=webs&paso=borrarWeb&ID='.$aux[0].'\'; return true; } else { return false; }"></TD>';
			$HTML.='</TR>';
		}
	}
	$HTML.='</TABLE>';
}

if($paso=='editarWeb') {	//Formulario de edición de un sitio en particular.
	$web=new SitioWeb($ID);
	$HTML='<form action="index.php" method="post" onSubmit="submitOnce(this);">'."\n";
	$HTML.='<table align="center" class="0" border="0" cellspacing="1" cellpadding="5">'."\n";
	$HTML.='<tr><td align="center" class="0" colspan="2"><span class="title">'.$_Edit_.'</span></td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_WebTitle_.'</span></td>'."\n";
	$HTML.='<td class="2"><INPUT TYPE="TEXT" value="'.$web->web.'" class="text" id="eWebT" name="web" maxlength="60" size="60" onKeyUp="Contar(\'eWebT\',\'sWebT\',\''.$_CharLeft_.'\',60);"> <INPUT TYPE="BUTTON" class="minibutton" value="'.$_Info_.'" onClick="ventana(\'index.php?m=top&s=info&ID='.$ID.'\',\'_blank\','.(200*$gComentarios+361).',424)"><br><span id="sWebT" class="minitext"></span></td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_Pass_.'</span></td>'."\n";
	$HTML.='<td class="2"><INPUT TYPE="PASSWORD" value="'.$web->clave.'" class="text" id="eClave" name="clave" size="30" onMouseOver="oObj=getObject(\'sClave\'); oObj.innerHTML=this.value;" onMouseOut="oObj=getObject(\'sClave\'); oObj.innerHTML=\'\';"> <span id="sClave" class="text"></span></td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_Email_.'</span></td>'."\n";
	$HTML.='<td class="2"><INPUT TYPE="TEXT" value="'.$web->email.'" class="text" id="eEmai" name="email" maxlength="'.$gMaxURL.'" size="60" onKeyUp="Contar(\'eEmai\',\'sEmai\',\''.$_CharLeft_.'\','.$gMaxURL.');" onBlur="validate(this,\'email\',\'your@email.com\');">'."\n";
	if($gEnviarCorreo) $HTML.=' <INPUT TYPE="BUTTON" class="minibutton" value="'.$_Email_.'" onClick="ventana(\'index.php?m=top&s=info&t=email&paso=1&to=\'+getObject(\'eEmai\').value+\'&ID='.$ID.'\',\'_blank\',500,350)">';
	$HTML.='<br><span id="sEmai" class="minitext"></span></td></tr>'."\n";
	$HTML.='<tr><td align="right" class="1"><span class="options">'.$_WebURL_.'</span></td>'."\n";
	$HTML.='<td class="2"><INPUT TYPE="TEXT" value="'.$web->webURL.'" class="text" id="eWebU" name="webURL" value="http://" maxlength="'.$gMaxURL.'" size="60" onKeyUp="Contar(\'eWebU\',\'sWebU\',\''.$_CharLeft_.'\','.$gMaxURL.');" onBlur="validate(this,\'url\',\'http://\');"> <INPUT TYPE="BUTTON" class="minibutton" value="'.$_Visit_.'" onClick="window.open(getObject(\'eWebU\').value,\'_blank\');"><br><span id="sWebU" class="minitext"></span></td></tr>'."\n";
	if(count($web->bannerURL)<$gMultiBanner) {
		$HTML.='<tr><td align="right" class="1" valign="top"><INPUT type="submit" class="minibutton" value="'.$_AddBannerURL_.'"></td>';
		$HTML.=''."\n";
		$HTML.='<td class="2"><INPUT TYPE="TEXT" class="text" id="eBann" name="bannerURL" value="http://" maxlength="100" size="60" onBlur="validate(this,\'url\',\'http://\');" onKeyUp="Contar(\'eBann\',\'sBann\',\''.$_CharLeft_.'\','.$gMaxURL.');"><br><span id="sBann" class="minitext">'.str_replace('{CHAR}',$gMaxURL,$_CharLeft_).'</span></td></tr>'."\n";
	}
	if(is_array($web->bannerURL)) {
		foreach($web->bannerURL as $key=>$value) {
			$HTML.='<tr><td colspan="2" class="2" align="center">';
			$HTML.='<INPUT type="button" class="minibutton" value="'.$_DeleteBannerURL_.' '.($key+1).'" onClick="location.href=\'index.php?m=admin&s=html&t=webs&paso=borrarBanner&DelBannerURL='.$value.'&ID='.$ID.'\'">'."\n";
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
	if($gVistoBueno) {
		$HTML.='<tr><td align="right" class="1"><span class="options">'.$_ShowSite_.'</span></td>'."\n";
		$HTML.='<td class="2"><SELECT name="vistoBueno">'."\n";
		$HTML.='<OPTION class="disable" value="0">'.$_Disable_.'</OPTION>';
		$HTML.='<OPTION class="enable" value="1"';
		if($indice->Leer($ID,2)) $HTML.=' selected';
		$HTML.='>'.$_Enable_.'</OPTION>';
		$HTML.='</SELECT></td></tr>'."\n";
	}
	if($gCategorias) {
		$HTML.='<tr><td align="right" class="1"><span class="options">'.$_Category_.'</span></td>'."\n";
		$HTML.='<td class="2"><SELECT name="categoria"><OPTION value="0000000000.0000">'.$_Null_.'</OPTION>'.$categorias->Select(1,$indice->Leer($ID,1)).'</SELECT>'."\n";
		$HTML.='&nbsp;<INPUT TYPE="checkbox" name="bloqueo" value="1"';
		if($indice->Leer($ID,3)) $HTML.=' checked';
		$HTML.='> '.$_CategoryBlock_.'</td></tr>'."\n";
	}
	if($gBanderas) {
		asort($_Country_);
		$HTML.='<tr><td align="right" class="1"><span class="options">'.$_CountryText_.' / '.$_Language_.'</span></td>'."\n";
		$HTML.='<td class="2"><SELECT name="pais" onChange="oBan=getObject(\'bandera\'); oBan.src=\'images/flags/\'+ this.options[this.selectedIndex].value +\'.gif\';">'."\n";
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
	$HTML.='<tr><td align="center" class="0" colspan="2"><input type="reset" class="button"> <input type="submit" class="button">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="m" value="admin">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="s" value="html">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="t" value="webs">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="paso" value="guardarWeb">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="ID" value="'.$ID.'"></td></tr>'."\n";
	$HTML.='</table></form>'."\n";
	/*
	$HTML.='<SCRIPT>'."\n";
	$HTML.='var imagen;'."\n";
	$HTML.='var contador;'."\n";
	$HTML.='var imgObjAdd=getObject(\'AddBannerIMG\');'."\n";
	$HTML.='var infObjAdd=getObject(\'AddbannerINF\');'."\n";
	$HTML.='var imgObjDel=getObject(\'DelBannerIMG\');'."\n";
	$HTML.='var infObjDel=getObject(\'DelbannerINF\');'."\n";
	$HTML.='function Cargar(url,modo) {'."\n";
	$HTML.='	if(modo==\'Add\') {'."\n";
	$HTML.='	    imgObj=imgObjAdd;'."\n";
	$HTML.='	    infObj=infObjAdd;'."\n";
	$HTML.='	} else {'."\n";
	$HTML.='	    imgObj=imgObjDel;'."\n";
	$HTML.='	    infObj=infObjDel;'."\n";
	$HTML.='	}'."\n";
	$HTML.='    imgObj.src=\'images/no_banner.gif\';'."\n";
	$HTML.='    infObj.innerHTML=\'\';'."\n";
	$HTML.='	contador=0;'."\n";
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
	$HTML.="    	Boton='<INPUT type=\"button\" class=\"minibutton\" value=\"$_Delete_\" onClick=\"location.href=\'admin.php?modo=webs&submodo=iframe&paso=3&DelBannerURL=' + imagen.src + '&ID=$ID\'\">&nbsp;'"."\n";
	$HTML.='	}'."\n";
	$HTML.='    if(imagen.complete) {'."\n";
	$HTML.='        imgObj.src=imagen.src;'."\n";
	$HTML.='        infObj.innerHTML=Boton + \'<b>\' + imagen.nameProp + \'</b> :: <b>\' + imagen.width + \' x \' + imagen.height + \'</b> :: <b>\' + Math.floor(imagen.fileSize/1024) + \'</b> KB\';'."\n";
	$HTML.='    } else {'."\n";
	$HTML.='        contador=contador+1;'."\n";
	$HTML.='        if(contador <= 20) {'."\n";
	$HTML.='            setTimeout(\'Mostrar("\' + modo + \'")\',1000);'."\n";
	$HTML.='        } else {'."\n";
	$HTML.='            imgObj.src=\'images/no_banner.gif\''."\n";
	$HTML.='            infObj.innerHTML=Boton + \'<span class="disable">&nbsp;Banner no válido&nbsp;</span>\';'."\n";
	$HTML.='        };'."\n";
	$HTML.='    }'."\n";
	$HTML.='}'."\n";
	if(is_array($web->bannerURL)) $HTML.='Cargar(\''.$web->bannerURL[0].'\',\'Del\');';		
	$HTML.='</SCRIPT>'."\n";
	*/
}

if($paso=='guardarWeb') {	//Guardamos los datos editados de un sitio
	//Adaptacion de variables para PHP v4.2+
	$clave=$_POST['clave'];
	$email=$_POST['email'];
	$web=$_POST['web'];
	$webURL=$_POST['webURL'];
	$bannerURL=$_POST['bannerURL'];
	$descripcion=$_POST['descripcion'];
	$pais=$_POST['pais'];
	//--------------------------------------
	$nuevoSitio=new SitioWebAvanzado($ID);
	
	if($nuevoSitio->web!=$web) $cambioWeb=1;
	if($nuevoSitio->webURL!=$webURL) $cambioURL=1;
	$viejo=array('\"',"\'",'|','$','<','{');
	$nuevo=array('&#'.ord('"'),'&#'.ord("'"),'&#'.ord('|'),'&#'.ord('$'),'&#'.ord('<'),'&#'.ord('{'));
	$nuevoSitio->clave=str_replace($viejo,$nuevo,$clave);
	$nuevoSitio->email=str_replace($viejo,$nuevo,$email);
	$nuevoSitio->web=str_replace($viejo,$nuevo,$web);
	$nuevoSitio->webURL=str_replace($viejo,$nuevo,$webURL);
	$nuevoSitio->bannerURL[]=str_replace($viejo,$nuevo,$bannerURL);
	$nuevoSitio->descripcion=str_replace($viejo,$nuevo,$descripcion);
	if($gBanderas) $nuevoSitio->pais=str_replace($viejo,$nuevo,$pais);
	$nuevoSitio->_Guardar();
	
	//Recargamos.	
	$HTML='<br><br><br><br><center><span class="title">'.$_UpdatingData_.'</span></center>';
	$HTML.='<script>opener.location.href="index.php?m=admin&s=html&t=webs";</script>';
	$HTML.='<script>document.location.href="index.php?m=admin&s=html&t=webs&paso=editarWeb&ID='.$ID.'";</script>';
	
	//Actualización del index.dat
	if($gCategorias OR $gVistoBueno OR $cambioURL OR $cambioWeb) {
		//Adaptacion de variables para PHP v4.2+
		$categoria=$_POST['categoria'];
		$vistoBueno=$_POST['vistoBueno'];
		$bloqueo=$_POST['bloqueo'];
		//--------------------------------------
		$data[0]=$ID;
		if($categoria) { $data[1]=$categoria; } else { $data[1]=$indice->Leer($ID,1); }
		if($vistoBueno==1) { $data[2]=1; } else { $data[2]=0; }
		if($bloqueo==1) { $data[3]=1; } else { $data[3]=0; }
		$data[4]=$nuevoSitio->web;
		$data[5]=$nuevoSitio->webURL;
		$indice->Escribir($ID,$data);	//Guarda automaticamente.
	}
}

if($paso=='borrarBanner') {	//Borramos un banner de un sitio.
	//Adaptacion de variables para PHP v4.2+
	if(isset($_POST['DelBannerURL'])) $DelBannerURL=$_POST['DelBannerURL']; else $DelBannerURL=$_GET['DelBannerURL'];
	//--------------------------------------
	$nuevoSitio=new SitioWebAvanzado($ID);
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
	$HTML.='<script>document.location.href="index.php?m=admin&s=html&t=webs&paso=editarWeb&ID='.$ID.'";</script>';
}
 
if($paso=='borrarWeb') {
	$indice->Borrar($ID);
	//Recargamos.	
	$HTML='<br><br><br><br><center><span class="title">'.$_UpdatingData_.'</span></center>';
	$HTML.='<script>document.location.href="index.php?m=admin&s=html&t=webs";</script>';
}

?>