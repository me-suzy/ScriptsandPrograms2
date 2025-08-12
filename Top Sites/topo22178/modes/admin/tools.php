<?php
//
//  modes/admin/tools.php
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
if($frame=='') {
	$HTML='<TABLE width="95%" align="center" border="0" cellpadding="2" cellspacing="1">';
	$HTML.='<TR><TD class="0" align="center"><span class="title">'.$_Tools_.'</span></TD></TR>';
	$HTML.='<TR><TD class="1" align="center">';
	$HTML.='<INPUT type="button" class="button" value="Mass Email" onClick="window.frames[\'frame_tool\'].document.location.href=\'index.php?m=admin&s=html&t=tools&f=mass_email\'">';
	$HTML.='&nbsp;&nbsp;&nbsp;<INPUT type="button" class="button" value="DataBase v1.x &#187 v2.x" onClick="window.frames[\'frame_tool\'].document.location.href=\'index.php?m=admin&s=html&t=tools&f=conversor_v1x_a_v2x\'">';
	$HTML.='</TD></TR>';
	$HTML.='<TR><TD></TD></TR>';
	$HTML.='<TR><TD class="2"><IFRAME name="frame_tool" src="index.php?m=admin&s=html&t=tools&f=ayuda" frameborder="0" height="500" width="100%"></IFRAME></TD></TR>';
	$HTML.='</TABLE>';
}

if($frame=='ayuda') {
	$HTML='<CENTER>Select one tool from above.</CENTER>';
}

if($frame=='mass_email') {
	if($paso==0 OR $paso=='') {
		$HTML='<TABLE width="95%" align="center" border="0" cellpadding="2" cellspacing="1">';
		$HTML.='<TR><TD class="0" align="center"><span class="title">Mass Email</span></TD></TR>';
		$HTML.='<TR><TD class="1">';
		$HTML.='With this tool you can send massive emails to the sites member of the toplist. This tool need PHP v4.1 to work fine.';
		$HTML.='</TD></TR>';
		$HTML.='<TR><TD>';
		$HTML.='<hr><br><form action="index.php" method="post" onSubmit="submitOnce(this);">';
		$HTML.='<table align="center" border="0" cellpadding="2" cellspacing="1">';
		$HTML.='<tr><td class="0" colspan="2" align="center"><span class="title">Message</span></td></tr>';
		$HTML.='<tr><td class="1" align="right">To:</td>';
		$HTML.='<td class="2"><SELECT name="to"><OPTION value="0">All sites</OPTION><OPTION value="1">Enable sites</OPTION><OPTION value="2">Disable sites</OPTION></SELECT></td>';
		$HTML.='</tr>';
		$HTML.='<tr><td class="1" align="right">From:</td>';
		$HTML.='<td class="2">'.$gTopNombre.' &lt;'.$gAdminEmail.'&gt;</td>';
		$HTML.='</tr>';
		$HTML.='<tr><td class="1" align="right">Subject:</td>';
		$HTML.='<td class="2"><INPUT TYPE="TEXT" class="text" name="subject" size="65"></td>';
		$HTML.='</tr>';
		$HTML.='<tr><td class="1" align="right" valign="top">Text:</td>';
		$HTML.='<td class="2"><TEXTAREA name="text" rows="9" cols="65" wrap="hard"></TEXTAREA></td>';
		$HTML.='</tr>';
		$HTML.='<tr><td class="0" colspan="2" align="center">';
		$HTML.='<INPUT type="hidden" name="m" value="admin">';
		$HTML.='<INPUT type="hidden" name="s" value="html">';
		$HTML.='<INPUT type="hidden" name="t" value="tools">';
		$HTML.='<INPUT type="hidden" name="f" value="mass_email">';
		$HTML.='<INPUT type="hidden" name="paso" value="1">';
		$HTML.='<INPUT type="reset" class="button">&nbsp;<INPUT type="submit" class="button"></TD></TR>'."\n";
		$HTML.='</table></form>';
		$HTML.='</TD></TR>';
		$HTML.='</TABLE>';
	}
	
	if($paso==1) {
		//Adaptacion de variables para PHP v4.2+
		$to=$_POST['to'];
		$subject=$_POST['subject'];
		$text=$_POST['text'];
		//--------------------------------------
		$indice=new Index();
		$lista=$indice->EmailTo($to);
		$email=new Email();
		$email->set_from($gAdminEmail,$gTopNombre);
		$email->set_to($gAdminEmail);
		if(is_array($lista)) {
			foreach($lista as $value) {
				$sitio=new SitioWeb($value);
				$email->add_to($sitio->email);
			}
			$email->set_subject($subject);
			$email->set_text($text);
			$email->send();
			$HTML='<TABLE width="95%" align="center" border="0" cellpadding="2" cellspacing="1">';
			$HTML.='<TR><TD class="0" align="center"><span class="title">Mass Email</span></TD></TR>';
			$HTML.='<TR><TD class="1">';
			$HTML.='With this tool you can send massive emails to the sites member of the toplist. This tool need PHP v4.1 to work fine.';
			$HTML.='</TD></TR>';
			$HTML.='<TR><TD>';
			$HTML.='<hr><br><table align="center" border="0" cellpadding="2" cellspacing="1">';
			$HTML.='<tr><td class="1" align="center"><span class="title">>>> EMAILS SEND <<<</span><hr>'.count($lista).' mails was emailed.</td></tr>';
			$HTML.='</table>';
			$HTML.='</TD></TR>';
			$HTML.='</TABLE>';
		} else {
			$HTML='<TABLE width="95%" align="center" border="0" cellpadding="2" cellspacing="1">';
			$HTML.='<TR><TD class="0" align="center"><span class="title">Mass Email</span></TD></TR>';
			$HTML.='<TR><TD class="1">';
			$HTML.='With this tool you can send massive emails to the sites member of the toplist. This tool need PHP v4.1 (or superior) to work properly.';
			$HTML.='</TD></TR>';
			$HTML.='<TR><TD>';
			$HTML.='<hr><br><table align="center" border="0" cellpadding="2" cellspacing="1">';
			$HTML.='<tr><td class="1" align="center"><span class="title">>>> NO SITES SELECTED <<<</span></td></tr>';
			$HTML.='</table>';
			$HTML.='</TD></TR>';
			$HTML.='</TABLE>';
		}
	}
}


if($frame=='conversor_v1x_a_v2x') {
	if($paso==0 OR $paso=='') {
		//Adaptacion de variables para PHP v4.2+
		if(isset($_POST['dir'])) $dir=$_POST['dir']; else $dir=$_GET['dir'];
		//--------------------------------------
		$HTML='<TABLE width="95%" align="center" border="0" cellpadding="2" cellspacing="1">';
		$HTML.='<TR><TD class="0" align="center"><span class="title">DataBase v1.x &#187 v2.x</span></TD></TR>';
		$HTML.='<TR><TD class="1">';
		$HTML.='With this tool you can convert TOPo v1.x DB format to the new TOPo v2.x DB format and import the sites registered in your older toplist to the new TOPo v2.x top.';
		$HTML.='<br><span class="minititle">WARNING:</span> Before use it, I recommend you make a backup of the <span class="minititle">/data/</span> directory of your TOPo installation.';
		$HTML.='</TD></TR>';
		$HTML.='<TR><TD>';
		$HTML.='<hr><br><table align="center" border="0" cellpadding="2" cellspacing="1">';
		$HTML.='<tr><td class="0" align="center"><span class="title">Step 1</span></td></tr>';
		$HTML.='<tr><td class="1">Browser and select the <span class="minititle">index.dat</span> file that is placed<br>in the <span class="minititle">/data/</span> directory of your old toplist.<hr></td></tr>';
		$dirInicial=getcwd();
		if(!isset($dir)) $dir='.';
		chdir($dir);
		$dirActual=getcwd();
		$directorio=opendir($dirActual);
		while($file = readdir($directorio)) {
			if($file=='.' OR $file=='..' OR $file[0]=='0' OR $file[0]=='1' OR $file[0]=='2' OR $file[0]=='3') continue;
			if(is_dir($file)) {
				$directorios[]=$file;
			} else {
				$ficheros[]=$file;
			}
		}
		closedir($directorio);
		chdir($dirInicial);
		if(is_array($directorios)) sort($directorios);
		if(is_array($ficheros)) sort($ficheros);
		
		$tree=explode('/',$dirActual);
		if(!is_array($tree) OR count($tree)==1) $tree=explode("\\",$dirActual);
		$num_tree=count($tree);
		$tabulacion='';

		$HTML.='<tr><td><img src="themes/'.$gTema.'/icon_folder.gif" border="0" align="absmiddle"> ';
		$HTML.=$dirActual;
		$HTML.='</td></tr>';
		
		for($i=0;$i<$num_tree;$i++) {
			$sub='';
			for($j=$num_tree-1-$i;$j>0;$j--) $sub.='/..';
			$dir=$dirActual.$sub;
			$HTML.='<tr><td>'.$tabulacion.'<img src="themes/'.$gTema.'/icon_folder.gif" border="0" align="absmiddle"> ';
			if($sub=='') {
				$HTML.=$tree[$i].'/';
			} else {
				$HTML.='<a href="index.php?m=admin&s=html&t=tools&f=conversor_v1x_a_v2x&dir='.$dir.'">'.$tree[$i].'/</a>';
			}
			$HTML.='</td></tr>';
			$tabulacion.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		if(is_array($directorios)) {
			foreach($directorios as $value) {
				$dir=$dirActual.'/'.$value;
				$HTML.='<tr><td>'.$tabulacion.'<img src="themes/'.$gTema.'/icon_folder.gif" border="0" align="absmiddle"> ';
				$HTML.='<a href="index.php?m=admin&s=html&t=tools&f=conversor_v1x_a_v2x&dir='.$dir.'">'.$value.'/</a>';
				$HTML.='</td></tr>';
			}
		}
		if(is_array($ficheros)) {
			foreach($ficheros as $value) {
				if($value=="index.dat") {
					$HTML.='<tr><td>'.$tabulacion.'<img src="themes/'.$gTema.'/icon_file.gif" border="0" align="absmiddle"> <img src="themes/'.$gTema.'/arrow_next.gif" border="0" align="absmiddle">';
					$HTML.='<a href="index.php?m=admin&s=html&t=tools&f=conversor_v1x_a_v2x&paso=1&dir='.$dir.'/index.dat">'.$value.'</a> <img src="themes/'.$gTema.'/arrow_prev.gif" border="0" align="absmiddle">';
					$HTML.='</td></tr>';
				} else {
					$HTML.='<tr><td>'.$tabulacion.'<img src="themes/'.$gTema.'/icon_file.gif" border="0" align="absmiddle"> ';
					$HTML.=$value;
					$HTML.='</td></tr>';
				}
			}
		}
		$HTML.='</table>';
		$HTML.='</TD></TR>';
		$HTML.='</TABLE>';
	}
	
	if($paso==1) {
		//Adaptacion de variables para PHP v4.2+
		if(isset($_POST['dir'])) $dir=$_POST['dir']; else $dir=$_GET['dir'];
		//--------------------------------------
		$dir=str_replace("\\\\",'/',$dir);	//Quitamos los delimitadores de windows.
		//Comprobamos si el index.dat tiene el formato válido.
		$linea=file($dir);
		if(!is_array($linea)) {									//index.dat no válido ó vacio
			$HTML='<TABLE width="95%" align="center" border="0" cellpadding="2" cellspacing="1">';
			$HTML.='<TR><TD class="0" align="center"><span class="title">DataBase v1.x &#187 v2.x</span></TD></TR>';
			$HTML.='<TR><TD class="1">';
			$HTML.='With this tool you can convert TOPo v1.x DB format to the new TOPo v2.x DB format and import the sites registered in your older toplist to the new TOPo v2.x top.';
			$HTML.='<br><span class="minititle">WARNING:</span> Before use it, I recommend you make a backup of the <span class="minititle">/data/</span> directory of your TOPo installation.';
			$HTML.='</TD></TR>';
			$HTML.='<TR><TD>';
			$HTML.='<hr><br><table align="center" border="0" cellpadding="2" cellspacing="1">';
			$HTML.='<tr><td class="0" align="center"><span class="title">Step 2</span></td></tr>';
			$HTML.='<tr><td class="1" align="center"><span class="title">>>> ERROR <<<</span><BR><span class="minititle">index.dat</span> is empty.<hr></td></tr>';
			$HTML.='<tr><td class="1" align="center"><input type="button" class="button" value="'.$_Back_.'" onClick="history.go(-1)"></td></tr>';
			$HTML.='</table>';
			$HTML.='</TD></TR>';
			$HTML.='</TABLE>';
		} elseif(count($aux=explode('||',$linea[0]))!=4) {		//index.dat no válido
			$HTML='<TABLE width="95%" align="center" border="0" cellpadding="2" cellspacing="1">';
			$HTML.='<TR><TD class="0" align="center"><span class="title">DataBase v1.x &#187 v2.x</span></TD></TR>';
			$HTML.='<TR><TD class="1">';
			$HTML.='With this tool you can convert TOPo v1.x DB format to the new TOPo v2.x DB format and import the sites registered in your older toplist to the new TOPo v2.x top.';
			$HTML.='<br><span class="minititle">WARNING:</span> Before use it, I recommend you make a backup of the <span class="minititle">/data/</span> directory of your TOPo installation.';
			$HTML.='</TD></TR>';
			$HTML.='<TR><TD>';
			$HTML.='<hr><br><table align="center" border="0" cellpadding="2" cellspacing="1">';
			$HTML.='<tr><td class="0" align="center"><span class="title">Step 2</span></td></tr>';
			$HTML.='<tr><td class="1" align="center"><span class="title">>>> ERROR <<<</span><BR><span class="minititle">index.dat</span> invalid format.';
			$HTML.='<hr></td></tr>';
			$HTML.='<tr><td class="1" align="center"><input type="button" class="button" value="'.$_Back_.'" onClick="history.go(-1)"></td></tr>';
			$HTML.='</table>';
			$HTML.='</TD></TR>';
			$HTML.='</TABLE>';
		} else {											//index.dat válido
			$HTML='<TABLE width="95%" align="center" border="0" cellpadding="2" cellspacing="1">';
			$HTML.='<TR><TD class="0" align="center"><span class="title">DataBase v1.x &#187 v2.x</span></TD></TR>';
			$HTML.='<TR><TD class="1">';
			$HTML.='With this tool you can convert TOPo v1.x DB format to the new TOPo v2.x DB format and import the sites registered in your older toplist to the new TOPo v2.x top.';
			$HTML.='<br><span class="minititle">WARNING:</span> Before use it, I recommend you make a backup of the <span class="minititle">/data/</span> directory of your TOPo installation.';
			$HTML.='</TD></TR>';
			$HTML.='<TR><TD>';
			$HTML.='<hr><br><table align="center" border="0" cellpadding="2" cellspacing="1">';
			$HTML.='<tr><td class="0" align="center"><span class="title">Step 2</span></td></tr>';
			$HTML.='<tr><td class="1" align="center"><span class="title">>>> '.$_UpdatingData_.'<<<</span><br>'.count($linea).' sites found in index.dat<hr></td></tr>';
			foreach($linea as $value) {
				$aux=explode('||',$value);
				$HTML.='<tr><td class="1" align="center">'.$aux[1].'</td></tr>';
			}
			$HTML.='</table>';
			$HTML.='</TD></TR>';
			$HTML.='</TABLE>';
			$HTML.='<SCRIPT>document.location.href="index.php?m=admin&s=html&t=tools&f=conversor_v1x_a_v2x&paso=2&dir='.$dir.'";</SCRIPT>';
		}
	}

 	if($paso==2) {
		//Adaptacion de variables para PHP v4.2+
		if(isset($_POST['dir'])) $dir=$_POST['dir']; else $dir=$_GET['dir'];
		//--------------------------------------
		//Realizamos la conversión
		$conversor=new Conversor_v1x_a_v2x($dir);
		$conversor->Convertir();
		$HTML='<TABLE width="95%" align="center" border="0" cellpadding="2" cellspacing="1">';
		$HTML.='<TR><TD class="0" align="center"><span class="title">DataBase v1.x &#187 v2.x</span></TD></TR>';
		$HTML.='<TR><TD class="1">';
		$HTML.='With this tool you can convert TOPo v1.x DB format to the new TOPo v2.x DB format and import the sites registered in your older toplist to the new TOPo v2.x top.';
		$HTML.='<br><span class="minititle">WARNING:</span> Before use it, I recommend you make a backup of the <span class="minititle">/data/</span> directory of your TOPo installation.';
		$HTML.='</TD></TR>';
		$HTML.='<TR><TD>';
		$HTML.='<hr><br><table align="center" border="0" cellpadding="2" cellspacing="1">';
		$HTML.='<tr><td class="0" align="center"><span class="title">Step 3</span></td></tr>';
		$HTML.='<tr><td class="1" align="center"><span class="title">>>> CONVERSION COMPLETED <<<</span><hr>Added '.count($conversor->lista).' sites to the toplist.</td></tr>';
		$HTML.='</table>';
		$HTML.='</TD></TR>';
		$HTML.='</TABLE>';
	}
}

?>