<?php
//
//  modes/members/forget.php
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
	$HTML='<table align="center" border="0" cellspacing="1" cellpadding="5"><tr class="1"><td align="center" class="minititle">'.$_EmptyIndex_.'</td></tr></table>';
	$paso=-1;
}

if($paso==0 OR $paso=='') {
	$HTML='<form action="index.php" method="post">';
	$HTML.='<br><table align="center" class="0" border="0" cellspacing="1" cellpadding="5">';
	$HTML.='<caption><span class="title">'.$_ForgetScreen_.'</span></caption>';
	$HTML.='<tr class="0"><td align="center"><span class="minititle">'.$_SelectSite_.'</span></td></tr>';
	$HTML.='<tr class="1"><td align="center" class="texto0"><SELECT name="ID">'.$indice->Select(1).'</SELECT></td></tr>';
	$HTML.='<INPUT TYPE="HIDDEN" name="m" value="members">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="s" value="html">'."\n";
	$HTML.='<INPUT TYPE="HIDDEN" name="t" value="forget">';
	$HTML.='<INPUT TYPE="HIDDEN" name="paso" value="1">';
	$HTML.='<tr class="0"><td align="center"><input type="submit" class="button" value="'.$_SendPass_.'"></td></tr>';
	$HTML.='</table></form>';
}
if($paso==1) {
	$nuevoSitio=new SitioWebAvanzado($ID);
	//Generación del código de votación
	$_Mailed_=str_replace('{EMAIL}',$nuevoSitio->email,$_Mailed_);
	if($gEnviarCorreo) {
		//Mandamos los emails necesarios.
		$to=$nuevoSitio->email;
		$subject=$gTopNombre;
		$text=$_AccountInfo_."\n\n";
		$text.=$_WebTitle_.": ".$nuevoSitio->web."\n";
		$text.=$_Pass_.": ".$nuevoSitio->clave."\n";
		$text.=$_WebURL_.": ".$nuevoSitio->webURL."\n";
		$text.=$_ID_.": ".$ID."\n";
		$text.=$_ControlPanel_.": ".$gTopURL."index.php?m=members&s=html&t=edit\n";
		$text.="----------------------------------------------------\n";
		$text.=$gTopNombre."\n";
		$text.=$gAdminEmail."\n";
		$text.=$gTopURL."\n";
		$text.="----------------------------------------------------\n";
		
		$email=new Email();
		$email->set_from($gAdminEmail,$gTopNombre);
		$email->set_to($to);
		$email->set_subject($subject);
		$email->set_text($text);
		$email->send();
	}
	
	$HTML='<br><table align="center" border="0" cellspacing="1" cellpadding="5">';
	$HTML.='<caption><span class="title">'.$_ForgetScreen_.'</span></caption>';
	$HTML.='<tr class="0"><td align="center"><span class="minititle">'.$_PassMailedTo_.'</span></td></tr>';
	$HTML.='<tr class="1"><td align="center"><span class="text">'.$nuevoSitio->web.'<br>'.$nuevoSitio->email.'</span></td></tr>';
	$HTML.='<tr class="2"><td align="center"><input type="button" class="button" value="'.$_Close_.'" onClick="window.close(this);"></td></tr>';
	$HTML.='</table>';
}

?>