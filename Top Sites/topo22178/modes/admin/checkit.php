<?php
//
//  modes/admin/checkit.php
//	rev003
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
$HTML='<p class="title" align="center">'.$_CheckingInstall_.'</p>';
$HTML.='<TABLE align="center" class="0" border="0" cellpadding="2" cellspacing="1">';
$HTML.='<TR><TD class="0" align="center" colspan="3"><span class="minititle">'.$_FilePermissions_.'</span></TD></TR>'."\n";

$HTML.='<TR><TD class="1" align="left"><img src="themes/'.$gTema.'/icon_folder.gif" border="0" align="absmiddle"> data/</TD>';
if(file_exists('data')) {
	$HTML.='<TD class="1">&nbsp;<span class="minitext">'.$_Directory_.'</span></TD>';
	$permisos=substr(sprintf("%o",fileperms("data")),2);
	$HTML.='<TD class="1">&nbsp;'.$permisos;
	if($permisos==766 OR $permisos==777) {
		$HTML.=' <span class="minitext">'.$_DirPermissionsOK_.'</span>';
	} else {
		$HTML.=' <span class="minititle">'.$_DirPermissionsChange_.'</span>';
		$error1=1;
	}
	$HTML.='</TD>';
} else {
	$HTML.='<TD class="1" colspan="2"><span class="minititle">'.$_DirMissing_.'</span></TD>';
	$error0=1;
}
$HTML.='</TR>';

$HTML.='<TR><TD class="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="themes/'.$gTema.'/icon_file.gif" border="0" align="absmiddle"> inc_config.php</TD>';
if(file_exists('data/inc_config.php')) {
	$HTML.='<TD class="2">&nbsp;'.number_format(filesize("data/inc_config.php")/1024,1,".","").'KB <span class="minitext">'.ej3Date('fechaCorta',filemtime("data/inc_config.php")).' <b>'.ej3Date('horaCorta',filemtime("data/inc_config.php")).'</b></span></TD>';
	$permisos=substr(sprintf("%o",fileperms("data/inc_config.php")),3);
	$HTML.='<TD class="2">&nbsp;'.$permisos;
	if($permisos==666 OR $permisos==777) {
		$HTML.=' <span class="minitext">'.$_FilePermissionsOK_.'</span>';
	} else {
		$HTML.=' <span class="minititle">'.$_FilePermissionsChange_.'</span>';
		$error1=1;
	}
	$HTML.='</TD>';
} else {
	$HTML.='<TD class="2" colspan="2"><span class="minititle">'.$_FileMissing_.'</span></TD>';
	$error0=1;
}
$HTML.='</TR>';

$HTML.='<TR><TD class="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="themes/'.$gTema.'/icon_file.gif" border="0" align="absmiddle"> index.dat</TD>';
if(file_exists('data/index.dat')) {
	$HTML.='<TD class="2">&nbsp;'.number_format(filesize("data/index.dat")/1024,1,".","").'KB <span class="minitext">'.ej3Date('fechaCorta',filemtime("data/index.dat")).' <b>'.ej3Date('horaCorta',filemtime("data/index.dat")).'</b></span></TD>';
	$permisos=substr(sprintf("%o",fileperms("data/index.dat")),3);
	$HTML.='<TD class="2">&nbsp;'.$permisos;
	if($permisos==666 OR $permisos==777) {
		$HTML.=' <span class="minitext">'.$_FilePermissionsOK_.'</span>';
	} else {
		$HTML.=' <span class="minititle">'.$_FilePermissionsChange_.'</span>';
		$error1=1;
	}
	$HTML.='</TD>';
} else {
	$HTML.='<TD class="2" colspan="2"><span class="minititle">'.$_FileMissing_.'</span></TD>';
	$error0=1;
}
$HTML.='</TR>';

$HTML.='<TR><TD class="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="themes/'.$gTema.'/icon_file.gif" border="0" align="absmiddle"> categories.dat</TD>';
if(file_exists('data/categories.dat')) {
	$HTML.='<TD class="2">&nbsp;'.number_format(filesize("data/categories.dat")/1024,1,".","").'KB <span class="minitext">'.ej3Date('fechaCorta',filemtime("data/categories.dat")).' <b>'.ej3Date('horaCorta',filemtime("data/categories.dat")).'</b></span></TD>';
	$permisos=substr(sprintf("%o",fileperms("data/categories.dat")),3);
	$HTML.='<TD class="2">&nbsp;'.$permisos;
	if($permisos==666 OR $permisos==777) {
		$HTML.=' <span class="minitext">'.$_FilePermissionsOK_.'</span>';
	} else {
		$HTML.=' <span class="minititle">'.$_FilePermissionsChange_.'</span>';
		$error1=1;
	}
	$HTML.='</TD>';
} else {
	$HTML.='<TD class="2" colspan="2"><span class="minititle">'.$_FileMissing_.'</span></TD>';
	$error0=1;
}
$HTML.='</TR>';

$HTML.='<TR><TD class="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="themes/'.$gTema.'/icon_file.gif" border="0" align="absmiddle"> online.dat</TD>';
if(file_exists('data/online.dat')) {
	$HTML.='<TD class="2">&nbsp;'.number_format(filesize("data/online.dat")/1024,1,".","").'KB <span class="minitext">'.ej3Date('fechaCorta',filemtime("data/online.dat")).' <b>'.ej3Date('horaCorta',filemtime("data/online.dat")).'</b></span></TD>';
	$permisos=substr(sprintf("%o",fileperms("data/online.dat")),3);
	$HTML.='<TD class="2">&nbsp;'.$permisos;
	if($permisos==666 OR $permisos==777) {
		$HTML.=' <span class="minitext">'.$_FilePermissionsOK_.'</span>';
	} else {
		$HTML.=' <span class="minititle">'.$_FilePermissionsChange_.'</span>';
		$error1=1;
	}
	$HTML.='</TD>';
} else {
	$HTML.='<TD class="2" colspan="2"><span class="minititle">'.$_FileMissing_.'</span></TD>';
	$error0=1;
}
$HTML.='</TR>';

$HTML.='<TR><TD class="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="themes/'.$gTema.'/icon_file.gif" border="0" align="absmiddle"> notice.htm</TD>';
if(file_exists('data/notice.htm')) {
	$HTML.='<TD class="2">&nbsp;'.number_format(filesize("data/notice.htm")/1024,1,".","").'KB <span class="minitext">'.ej3Date('fechaCorta',filemtime("data/notice.htm")).' <b>'.ej3Date('horaCorta',filemtime("data/notice.htm")).'</b></span></TD>';
	$permisos=substr(sprintf("%o",fileperms("data/notice.htm")),3);
	$HTML.='<TD class="2">&nbsp;'.$permisos;
	if($permisos==666 OR $permisos==777) {
		$HTML.=' <span class="minitext">'.$_FilePermissionsOK_.'</span>';
	} else {
		$HTML.=' <span class="minititle">'.$_FilePermissionsChange_.'</span>';
		$error1=1;
	}
	$HTML.='</TD>';
} else {
	$HTML.='<TD class="2" colspan="2"><span class="minititle">'.$_FileMissing_.'</span></TD>';
	$error0=1;
}
$HTML.='</TR>';

$HTML.='<TR><TD class="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="themes/'.$gTema.'/icon_file.gif" border="0" align="absmiddle"> rules.htm</TD>';
if(file_exists('data/rules.htm')) {
	$HTML.='<TD class="2">&nbsp;'.number_format(filesize("data/rules.htm")/1024,1,".","").'KB <span class="minitext">'.ej3Date('fechaCorta',filemtime("data/rules.htm")).' <b>'.ej3Date('horaCorta',filemtime("data/rules.htm")).'</b></span></TD>';
	$permisos=substr(sprintf("%o",fileperms("data/rules.htm")),3);
	$HTML.='<TD class="2">&nbsp;'.$permisos;
	if($permisos==666 OR $permisos==777) {
		$HTML.=' <span class="minitext">'.$_FilePermissionsOK_.'</span>';
	} else {
		$HTML.=' <span class="minititle">'.$_FilePermissionsChange_.'</span>';
		$error1=1;
	}
	$HTML.='</TD>';
} else {
	$HTML.='<TD class="2" colspan="2"><span class="minititle">'.$_FileMissing_.'</span></TD>';
	$error0=1;
}
$HTML.='</TR>';

$HTML.='<TR><TD class="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="themes/'.$gTema.'/icon_file.gif" border="0" align="absmiddle"> welcome.htm</TD>';
if(file_exists('data/welcome.htm')) {
	$HTML.='<TD class="2">&nbsp;'.number_format(filesize("data/welcome.htm")/1024,1,".","").'KB <span class="minitext">'.ej3Date('fechaCorta',filemtime("data/welcome.htm")).' <b>'.ej3Date('horaCorta',filemtime("data/welcome.htm")).'</b></span></TD>';
	$permisos=substr(sprintf("%o",fileperms("data/welcome.htm")),3);
	$HTML.='<TD class="2">&nbsp;'.$permisos;
	if($permisos==666 OR $permisos==777) {
		$HTML.=' <span class="minitext">'.$_FilePermissionsOK_.'</span>';
	} else {
		$HTML.=' <span class="minititle">'.$_FilePermissionsChange_.'</span>';
		$error1=1;
	}
	$HTML.='</TD>';
} else {
	$HTML.='<TD class="2" colspan="2"><span class="minititle">'.$_FileMissing_.'</span></TD>';
	$error0=1;
}
$HTML.='</TR>';

$HTML.='<TR><TD class="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="themes/'.$gTema.'/icon_file.gif" border="0" align="absmiddle"> bannertop.htm</TD>';
if(file_exists('data/bannertop.htm')) {
	$HTML.='<TD class="2">&nbsp;'.number_format(filesize("data/bannertop.htm")/1024,1,".","").'KB <span class="minitext">'.ej3Date('fechaCorta',filemtime("data/bannertop.htm")).' <b>'.ej3Date('horaCorta',filemtime("data/bannertop.htm")).'</b></span></TD>';
	$permisos=substr(sprintf("%o",fileperms("data/bannertop.htm")),3);
	$HTML.='<TD class="2">&nbsp;'.$permisos;
	if($permisos==666 OR $permisos==777) {
		$HTML.=' <span class="minitext">'.$_FilePermissionsOK_.'</span>';
	} else {
		$HTML.=' <span class="minititle">'.$_FilePermissionsChange_.'</span>';
		$error1=1;
	}
	$HTML.='</TD>';
} else {
	$HTML.='<TD class="2" colspan="2"><span class="minititle">'.$_FileMissing_.'</span></TD>';
	$error0=1;
}
$HTML.='</TR>';

$HTML.='<TR><TD class="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="themes/'.$gTema.'/icon_file.gif" border="0" align="absmiddle"> bannermiddle.htm</TD>';
if(file_exists('data/bannermiddle.htm')) {
	$HTML.='<TD class="2">&nbsp;'.number_format(filesize("data/bannermiddle.htm")/1024,1,".","").'KB <span class="minitext">'.ej3Date('fechaCorta',filemtime("data/bannermiddle.htm")).' <b>'.ej3Date('horaCorta',filemtime("data/bannermiddle.htm")).'</b></span></TD>';
	$permisos=substr(sprintf("%o",fileperms("data/bannermiddle.htm")),3);
	$HTML.='<TD class="2">&nbsp;'.$permisos;
	if($permisos==666 OR $permisos==777) {
		$HTML.=' <span class="minitext">'.$_FilePermissionsOK_.'</span>';
	} else {
		$HTML.=' <span class="minititle">'.$_FilePermissionsChange_.'</span>';
		$error1=1;
	}
	$HTML.='</TD>';
} else {
	$HTML.='<TD class="2" colspan="2"><span class="minititle">'.$_FileMissing_.'</span></TD>';
	$error0=1;
}
$HTML.='</TR>';

$HTML.='<TR><TD class="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="themes/'.$gTema.'/icon_file.gif" border="0" align="absmiddle"> bannerbottom.htm</TD>';
if(file_exists('data/bannerbottom.htm')) {
	$HTML.='<TD class="2">&nbsp;'.number_format(filesize("data/bannerbottom.htm")/1024,1,".","").'KB <span class="minitext">'.ej3Date('fechaCorta',filemtime("data/bannerbottom.htm")).' <b>'.ej3Date('horaCorta',filemtime("data/bannerbottom.htm")).'</b></span></TD>';
	$permisos=substr(sprintf("%o",fileperms("data/bannerbottom.htm")),3);
	$HTML.='<TD class="2">&nbsp;'.$permisos;
	if($permisos==666 OR $permisos==777) {
		$HTML.=' <span class="minitext">'.$_FilePermissionsOK_.'</span>';
	} else {
		$HTML.=' <span class="minititle">'.$_FilePermissionsChange_.'</span>';
		$error1=1;
	}
	$HTML.='</TD>';
} else {
	$HTML.='<TD class="2" colspan="2"><span class="minititle">'.$_FileMissing_.'</span></TD>';
	$error0=1;
}
$HTML.='</TR>';

$HTML.='<TR><TD class="1" align="left"><img src="themes/'.$gTema.'/icon_folder.gif" border="0" align="absmiddle"> cache/</TD>';
if(file_exists('data')) {
	$HTML.='<TD class="1">&nbsp;<span class="minitext">'.$_Directory_.'</span></TD>';
	$permisos=substr(sprintf("%o",fileperms("cache")),2);
	$HTML.='<TD class="1">&nbsp;'.$permisos;
	if($permisos==766 OR $permisos==777) {
		$HTML.=' <span class="minitext">'.$_DirPermissionsOK_.'</span>';
	} else {
		$HTML.=' <span class="minititle">'.$_DirPermissionsChange_.'</span>';
		$error1=1;
	}
	$HTML.='</TD>';
} else {
	$HTML.='<TD class="1" colspan="2"><span class="minititle">'.$_DirMissing_.'</span></TD>';
	$error0=1;
}
$HTML.='</TR>';

$HTML.='<TR><TD class="0" align="center" colspan="3"><span class="minititle">'.$_URLs_.'</span></TD></TR>'."\n";
$HTML.='<TR><TD class="1" align="right">'.$_TopURL_.'</TD>';
if(strlen($gTopURL)<=10) {
	$HTML.='<TD class="1" colspan="2">&nbsp;<span class="minititle">'.str_replace('{MENU}','<a href="admin.php?modo=config" >'.$_TopConfig_.'</a>',$_Error3_).'</span></TD></TR>';
} else {
	$HTML.='<TD class="1" colspan="2">&nbsp;<span class="minitext">'.$gTopURL.'</span></TD></TR>';
}

$HTML.='<TR><TD class="0" align="center" colspan="3"><span class="minititle">'.$_Versions_.'</span></TD></TR>'."\n";
$HTML.='<TR><TD class="1" align="right">PHP v'.phpversion().'</TD>';
$aux=explode(".",phpversion());
if($aux[0]+0.1*$aux[1]>=4.2) {
	$HTML.='<TD class="1" colspan="2">&nbsp;<span class="minitext">'.$_PHPVersionOK_.'</span></TD></TR>';
	$error2=0;
} else {
	$HTML.='<TD class="1" colspan="2">&nbsp;<span class="minititle">'.$_PHPVersionFail_.'</span></TD></TR>';
	$error2=1;
}

$HTML.='</TABLE>'."\n";

if($error0+$error1+$error2==0) {
	$HTML.='<p class="title" align="center">'.$_TOPoOK_.'</p>';
	//Comprobamos que los archivos xxxxxxxxxx.yyyy.php son de la v2.x
	$indice=new Index();
	$listaIDs=$indice->Listar();
	$key=array_rand($listaIDs);
	$fp=fopen('data/'.$listaIDs[$key].'.php','r');
	$aux=fread($fp,filesize('data/'.$listaIDs[$key].'.php'));
	fclose($fp);
	if(stristr($aux,"\$PHP_SELF")) $HTML.='<SCRIPT>ventana(\'update.php?modo=20a2x\',\'_blank\',300,501);</SCRIPT>';
}
if($error0) $HTML.='<p class="title" align="center">'.$_Error0_.'</p>';
if($error1) $HTML.='<p class="title" align="center">'.$_Error1_.'</p>';
if($error2) $HTML.='<p class="title" align="center">'.$_Error2_.'</p>';

?>