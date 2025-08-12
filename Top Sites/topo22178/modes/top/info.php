<?php
//
//  modes/top/info.php
//  rev006
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
if(isset($_POST['t'])) $tipo=$_POST['t']; else $tipo=$_GET['t'];
if(isset($_POST['f'])) $frame=$_POST['f']; else $frame=$_GET['f'];
if(isset($_POST['paso'])) $paso=$_POST['paso']; else $paso=$_GET['paso'];
if(isset($_POST['ID'])) $ID=$_POST['ID']; else $ID=$_GET['ID'];

//----------------------------------------------------------------
// CONTENIDO
//----------------------------------------------------------------
$web=new SitioWebAvanzado($ID);
$cookies=new Cookies;

if($tipo=='') { //Generamos los frames que hagan falta.
	if($gComentarios) {
	    $HTML.='<html><head>';
		$HTML.='<title>'.$web->web.'</title>';
	    $HTML.='<frameset rows="*" cols="310,*" frameborder="NO" border="0" framespacing="0">';
	    $HTML.='<frame name="stats" noresize scrolling="NO" src="index.php?m=top&s=info&t=stats&ID='.$ID.'" marginheight="0">';
		$HTML.='<frame name="comments" noresize src="index.php?m=top&s=info&t=comments&ID='.$ID.'" marginheight="0">';
	    $HTML.='</frameset>';
	    $HTML.='</head></html>';
	    echo $HTML;
	    exit();
	} else {
		$modo='stats';
	}
}

if($tipo=='stats') {
	//Distribución de las notas.
	$notaMedia=0;
	if($web->dat[5]) {
		$notaMedia=number_format($web->dat[6]/$web->dat[5],1,'.','');
		foreach($web->not as $key => $value) $notas[$web->notLeer($key,4)]++;
		$maximo=max($notas);
	}
	$infoHTML_0='<table width="100%" class="0" align="center" border="0" cellspacing="1" cellpadding="2">';
	$infoHTML_0.='<tr class="0"><td align="center" colspan="2" class="minititle">'.$_Rates_.'</td></tr>';
	$infoHTML_0.='<tr class="text"><td align="right" class="1">'.$_Average_.'</td><td align="left" class="2"><img src="themes/'.$gTema.'/rate'.number_format($notaMedia,0,'.','').'.gif" border="0">&nbsp;'.$notaMedia.'&nbsp;<span class="minitext">('.$web->dat[5].' '.$_Rates_.')</span></td></tr>';
	$infoHTML_0.='<tr class="text"><td align="right" class="1">';
	for($i=10;$i>=0;$i--) {
		$infoHTML_0.='<img src="themes/'.$gTema.'/rate'.$i.'.gif" border="0"> ('.$i.')';
	    if($i<>0) $infoHTML_0.='</br>';
	}
	$infoHTML_0.='</td><td align="left" class="2">';
	for($i=10;$i>=0;$i--) {
		$infoHTML_0.=barra("simple",125,$notas[$i],$maximo).'&nbsp;'.$notas[$i];
		if($i<>0) $infoHTML_0.='</br>';
	}
	$infoHTML_0.='</td></tr></table>';

	//Notas por paises
	$infoHTML_1='<br><center><span class="minititle">'.$_NoData_.'</span></center>';
	if(is_array($web->not)) {
		foreach($web->not as $key => $value) {
			$aux=capturarPais($web->notLeer($key,2));
			$paisNUM[$aux]++;
			$paisSUM[$aux]+=$web->notLeer($key,4);
		}
	}			
	if(is_array($web->not)) {
		foreach($paisNUM as $key => $value) $paisMED[$key]=number_format($paisSUM[$key]/$value,1,'.','');
		$maxNUM=max($paisNUM);
		$maxMED=max($paisMED);
		arsort($paisNUM,SORT_NUMERIC);
		$infoHTML_1='<table width="100%" class="0" align="center" border="0" cellspacing="1" cellpadding="2">';
	    $infoHTML_1.='<tr class="0"><td align="center" colspan="3" class="minititle">'.$_RatesByCountry_.'</td></tr>';
		$infoHTML_1.='<tr class="'.(1+$i%2).'">';
		$infoHTML_1.='<td align="center" valign="middle"><span class="text"><IMG src="themes/'.$gTema.'/icon_world.gif"></span></td>';
		$infoHTML_1.='<td align="center" valign="middle"><span class="text"><i>'.$web->datLeer(5).' '.$_Rates_.'</i></span></td>';
		$infoHTML_1.='<td valign="middle"><IMG src="themes/'.$gTema.'/rate'.number_format($web->dat[6]/$web->dat[5],0,'.','').'.gif" border="0"> <span class="text">'.number_format($web->dat[6]/$web->dat[5],1,'.','').'</span></td>';
		$infoHTML_1.='</tr>';
	    foreach($paisNUM as $key => $value) {
			$i++;
	        $infoHTML_1.='<tr class="'.(1+$i%2).'">';
			$infoHTML_1.='<td align="center" valign="middle"><IMG src="images/flags/'.$key.'.gif" title="'.$_Country_[$key].'" border="0"></td>';
			$infoHTML_1.='<td align="left" valign="middle">'.barra("simple",70,$value,$maxNUM).' <span class="text"><i>'.$value.'</i></span></td>';
			$infoHTML_1.='<td valign="middle"><IMG src="themes/'.$gTema.'/rate'.number_format($paisMED[$key],0,'.','').'.gif" border="0"> <span class="text">'.$paisMED[$key].'</span></td>';
			$infoHTML_1.='</tr>';
	    }
		$infoHTML_1.='</table>';
	}

	//Ultimos IN
	$infoHTML_2='<br><center><span class="minititle">'.$_NoData_.'</span></center>';
	if(is_array($web->ips)) {
		$infoHTML_2='<table width="100%" class="0" align="center" border="0" cellspacing="1" cellpadding="2">';
	    $infoHTML_2.='<tr class="0"><td align="center" colspan="4" class="minititle">'.$_LastestIN_.'</td></tr>';
	    foreach($web->ips as $key => $value) {
			$i++;
	        $infoHTML_2.='<tr class="'.(1+$i%2).'">';
			$infoHTML_2.='<td align="center" valign="middle"><span class="minitext">'.ej3Date('fechaCorta',$web->ipsLeer($key,3)).'<br><b>'.ej3Date('horaLarga',$web->ipsLeer($key,3)).'</b></span></td>';
			$infoHTML_2.='<td align="center" valign="middle"><IMG src="images/flags/'.capturarPais($web->ipsLeer($key,2)).'.gif" title="'.$_Country_[capturarPais($web->ipsLeer($key,2))].'" border="0"></td>';
			$infoHTML_2.='<td align="center" valign="middle">'.capturarHost($web->ipsLeer($key,2));
			if(isset($web->not[$key])) $infoHTML_2.='<br><IMG src="themes/'.$gTema.'/rate'.$web->notLeer($key,4).'.gif" border="0">';
			if($cookies->esAdmin()) $infoHTML_2.='<br>'.$web->ipsLeer($key,1);
			$infoHTML_2.='</td>';
			$infoHTML_2.='</tr>';
	    }
		$infoHTML_2.='</table>';
	}

	if($frame=='iframe') {
		//Notas por paises
		if($paso==1) $HTML=$infoHTML_1;
		//Desglose de los últimos IN
		if($paso==2) $HTML=$infoHTML_2;
	} else {
		//Notas por paises
		if(!is_array($web->not) OR count($paisNUM)>5) {
			$infoHTML_1='<IFRAME frameborder="0" width="100%" height="210" marginheight="1" marginwidth="5" src="index.php?m=top&s=info&t=stats&ID='.$ID.'&f=iframe&paso=1"></IFRAME>';
		}
		//Ultimos IN
		if(!is_array($web->ips) OR count($web->ips)>5) {
			$infoHTML_2='<IFRAME frameborder="0" width="100%" height="210" marginheight="1" marginwidth="5" src="index.php?m=top&s=info&t=stats&ID='.$ID.'&f=iframe&paso=2"></IFRAME>';
		}
		$HTML.='<SCRIPT>'."\n";
		$HTML.='var info0=\''.$infoHTML_0.'\';'."\n";
		$HTML.='var info1=\''.$infoHTML_1.'\';'."\n";
		$HTML.='var info2=\''.$infoHTML_2.'\';'."\n";
		$HTML.='</SCRIPT>'."\n";
		$dias=0;
		if(time()-$ID>=86400) $dias=number_format((time()-$ID)/86400,0,'.','');
		$HTML.='<table width="99%" align="center" border="0" cellspacing="1" cellpadding="2">'."\n";
		$HTML.='<caption><span class="title">'.$_Stats_.'</span></caption>'."\n";
		$HTML.='<tr class="0"><td align="center" colspan="2" class="minititle">'.$_InOut_.'</td></tr>'."\n";
		$HTML.='<tr valign="top"><td align="right" class="1"><span class="options">'.$_DateJoin_.'</span></td><td align="left" class="2"><span class="text">'.ej3Date('fechaCorta',$ID).'</span><br><span class="minitext">'.str_replace('{DAYS}',$dias,$_DaysInTop_).'</span></td></tr>'."\n";
		$HTML.='<tr valign="top"><td align="right" class="1"><span class="options">'.$_In_.'</span><br><span class="minitext"><b>'.$_LastIN_.'</b></span></td><td align="left" class="2"><span class="text"><span class="in">'.$web->datLeer(1).'</span> / <span class="in">'.$web->datLeer(3).'</span></span><br><span class="minitext">'.ej3Date('fechaCorta',$web->datLeer(8)).' ('.ej3Date('horaCorta',$web->datLeer(8)).')</span></td></tr>'."\n";
		$HTML.='<tr valign="top"><td align="right" class="1"><span class="options">'.$_Out_.'</span><br><span class="minitext"><b>'.$_LastOUT_.'</b></span></td><td align="left" class="2"><span class="text"><span class="out">'.$web->datLeer(2).'</span> / <span class="out">'.$web->datLeer(4).'</span></span><br><span class="minitext">'.ej3Date('fechaCorta',$web->datLeer(9)).' ('.ej3Date('horaCorta',$web->datLeer(9)).')</span></td></tr>'."\n";
		$HTML.='<tr valign="top"><td align="right" class="1"><span class="options">'.$_RatioParcial_.'<br>'.$_RatioTotal_.'</span></td><td class="2">'.barra("ratio",150,$web->datLeer(1),$web->datLeer(2)).barra("ratio",150,$web->datLeer(3),$web->datLeer(4)).'</td></tr>'."\n";
		$HTML.='<tr class="1"><td align="center" colspan="2">'."\n";
		if($gPuntuacion) {
			$HTML.='<INPUT type="button" class="minibutton" value="'.$_Rates_.'" onClick="oObj=getObject(\'td_frame\'); oObj.innerHTML=info0;">'."\n";
			if($web->datLeer(5)>0) $HTML.=' <INPUT type="button" class="minibutton" value="'.$_RatesByCountry_.'" onClick="oObj=getObject(\'td_frame\'); oObj.innerHTML=info1;">'."\n";
		}
		$HTML.=' <INPUT type="button" class="minibutton" value="'.$_LastestIN_.'" onClick="oObj=getObject(\'td_frame\'); oObj.innerHTML=info2;">'."\n";		
		$HTML.='</td></tr>'."\n";
		$HTML.='<tr><td id="td_frame" align="center" colspan="2">'.$infoHTML_2.'</td></tr>'."\n";
		$HTML.='</table>'."\n";
	}		
}

if($tipo=='comments') {
    if($paso=="") {
        $HTML.='<table width="99%" class="0" align="center" border="0" cellspacing="1" cellpadding="2">';
		$HTML.="<caption><span class=\"title\">".$_Comments_."</span><BR><INPUT type=\"button\" class=\"button\" value=\"".$_AddComment_."\" onClick=\"window.open('".$gTopURL."index.php?m=top&s=info&t=comments&paso=1&ID=".$ID."','comments')\"></caption>";
        if($web->datLeer(7)==0) {
            $HTML.='<tr class="2"><td align="center" class="minititle">'.$_NoComment_.'</td></tr>';
        } else {
            foreach($web->com as $raw) {
                $i++;
                $aux=explode("||",$raw);
                $HTML.='<tr class="'.(1+$i%2).'"><td class="text">'.$aux[4];
                if(strlen($aux[6])>6) $HTML.='&nbsp;<a href="mailto:'.$aux[6].'" target="_blank"><img src="themes/'.$gTema.'/icon_email.gif" border="0"></a>';
                if(strlen($aux[5])>10) $HTML.='&nbsp;<a href="'.$aux[5].'" target="_blank"><img src="themes/'.$gTema.'/icon_www.gif" border="0"></a>';
                $HTML.='</td></tr>';
                $HTML.='<tr class="'.(1+$i%2).'"><td class="minitext"><i>'.$aux[7].'</i></td></tr>';
                $HTML.='<tr class="'.(1+$i%2).'"><td class="minitext" align="right"><img src="themes/'.$gTema.'/rate'.$web->notLeer($aux[1],4).'.gif" border="0">&nbsp;'.date($_DateFormat_,$aux[3]);
				if($cookies->esAdmin()) $HTML.="<br><b>IP:</b> ".$aux[1]." ~ ".$aux[2]." <INPUT type=\"button\" class=\"minibutton\" value=\"".$_Delete_."\" onClick=\"window.open('index.php?m=top&s=info&t=comments&paso=3&deleteID=".$aux[1]."&ID=".$ID."','comments')\">";
				$HTML.='</td></tr>';				
            }
        }
        $HTML.='</table>';
    }
    if($paso==1) {
        $HTML='<form action="index.php" method="post" onSubmit="submitOnce(this);">';
        $HTML.='<table class="0" width="95%" align="center" border="0" cellspacing="1" cellpadding="2">';
        $HTML.='<caption><span class="title">'.$_Comments_.'</span></caption>';
		$HTML.='<tr class="0"><td align="center" class="minititle">'.$_AddComment_.'</td></tr>';
        $HTML.='<tr class="1"><td align="left" class="options">'.$_YourName_.'<BR>';
        $HTML.='<INPUT TYPE="TEXT" class="text" name="yourname" maxlength="50" size="40"></td></tr>';
        $HTML.='<tr class="2"><td align="left" class="options">'.$_YourWeb_.'&nbsp;<img src="themes/'.$gTema.'/icon_www.gif" border="0" width="32" height="16" align="absmiddle"><BR>';
        $HTML.='<INPUT TYPE="TEXT" class="text" name="yourweb" value="http://" maxlength="50" size="40"></td></tr>';
        $HTML.='<tr class="1"><td align="left" class="options">'.$_YourEmail_.'&nbsp;<img src="themes/'.$gTema.'/icon_email.gif" border="0" width="32" height="16" align="absmiddle"><BR>';
        $HTML.='<INPUT TYPE="TEXT" class="text" name="youremail" maxlength="50" size="40"></td></tr>';
        $HTML.='<tr class="2"><td align="left" class="options">'.$_YourComment_.'<BR>';
        $HTML.='<TEXTAREA name="comment" value="comment" rows="8" cols="38" wrap="VIRTUAL"></TEXTAREA></td></tr>';
        $HTML.='<tr class="1"><td align="left" class="options">'.$_YourRate_.'&nbsp;';
        $HTML.='<SELECT name="rate">';
        for($i=0;$i<=10;$i++) $HTML.='<OPTION value="'.$i.'">'.$i.'</OPTION>';
        $HTML.='</SELECT></td></tr>';
        $HTML.='<INPUT TYPE="HIDDEN" name="m" value="top">';
        $HTML.='<INPUT TYPE="HIDDEN" name="s" value="info">';
        $HTML.='<INPUT TYPE="HIDDEN" name="t" value="comments">';
        $HTML.='<INPUT TYPE="HIDDEN" name="paso" value="2">';
        $HTML.='<INPUT TYPE="HIDDEN" name="ID" value="'.$ID.'">';
        $HTML.='<tr class="0"><td align="center"><input type="button" class="button" value="'.$_Return_.'" onClick="history.go(-1)">&nbsp;<input type="submit" class="button"></td></tr>';
        $HTML.='</table></form>';
    }
    if($paso==2) {
		//Adaptacion de variables para PHP v4.2+
		$yourname=$_POST['yourname'];
		$yourweb=$_POST['yourweb'];
		$youremail=$_POST['youremail'];
		$comment=$_POST['comment'];
		$rate=$_POST['rate'];
		//--------------------------------------
		$ip=capturarIP();
		$data[0]=$yourname;
        $data[1]=$yourweb;
        $data[2]=$youremail;
		$data[3]=str_replace(array('\"',"\'",'|','$','<'),array('&#'.ord('"'),'&#'.ord("'"),'&#'.ord('|'),'&#'.ord('$'),'&#'.ord('<')),$comment);
        $data[3]=ereg_replace("(\r\n|\n|\r)","<br>",$data[3]);
        if($data[0]=="" OR $data[3]=="") {
            $HTML='<form action="index.php" method="post">';
            $HTML.='<table align="center" border="0" cellspacing="1" cellpadding="5">';
            $HTML.='<caption><span class="title">'.$_Comments_.'</span></caption>';
            $HTML.='<tr class="0"><td align="center" valign="middle" class="minititle">'.$_AddComment_.'</td></tr>';
            $HTML.='<tr class="1"><td align="center" valign="middle" class="text">'.$_CommentInvalid_.'</td></tr>';
            $HTML.='<tr class="2"><td align="center" valign="middle"><INPUT TYPE="SUBMIT" class="button" value="'.$_Return_.'"></td></tr>';
	        $HTML.='<INPUT TYPE="HIDDEN" name="m" value="top">';
	        $HTML.='<INPUT TYPE="HIDDEN" name="s" value="info">';
            $HTML.='<INPUT TYPE="HIDDEN" name="t" value="comments">';
            $HTML.='<INPUT TYPE="HIDDEN" name="paso" value="">';
            $HTML.='<INPUT TYPE="HIDDEN" name="ID" value="'.$ID.'">';
            $HTML.='</table></form>';
            include('code/inc_header.php');
            echo $HTML;
            echo '</body></html>';
            exit();
        }
        if(isset($web->com[$ip[0]])) {
            $web->comEscribir($ip[0],$data);
            $HTML='<form action="index.php" method="post">';
            $HTML.='<table class="0" width="95%" align="center" border="0" cellspacing="1" cellpadding="5">';
            $HTML.='<caption><span class="title">'.$_Comments_.'</span></caption>';
            $HTML.='<tr class="0"><td align="center" valign="middle" class="minititle">'.$_AddComment_.'</td></tr>';
            $HTML.='<tr class="1"><td align="center" valign="middle" class="text">'.$_CommentUpdate_.'</td></tr>';
            $HTML.='<tr class="2"><td align="center" valign="middle"><INPUT TYPE="SUBMIT" class="button" value="'.$_Return_.'"></td></tr>';
	        $HTML.='<INPUT TYPE="HIDDEN" name="m" value="top">';
	        $HTML.='<INPUT TYPE="HIDDEN" name="s" value="info">';
            $HTML.='<INPUT TYPE="HIDDEN" name="t" value="comments">';
            $HTML.='<INPUT TYPE="HIDDEN" name="paso" value="">';
            $HTML.='<INPUT TYPE="HIDDEN" name="ID" value="'.$ID.'">';
            $HTML.='</table></form>';
        } else {
			$web->comEscribir($ip[0],$data);
            $HTML='<form action="index.php" method="post">';
            $HTML.='<table class="0" width="95%" align="center" border="0" cellspacing="1" cellpadding="5">';
            $HTML.='<caption><span class="title">'.$_Comments_.'</span></caption>';
            $HTML.='<tr class="0"><td align="center" valign="middle" class="minititle">'.$_AddComment_.'</td></tr>';
            $HTML.='<tr class="1"><td align="center" valign="middle" class="text">'.$_CommentAdded_.'</td></tr>';
            $HTML.='<tr class="2"><td align="center" valign="middle"><INPUT TYPE="SUBMIT" class="button" value="'.$_Return_.'"></td></tr>';
	        $HTML.='<INPUT TYPE="HIDDEN" name="m" value="top">';
	        $HTML.='<INPUT TYPE="HIDDEN" name="s" value="info">';
            $HTML.='<INPUT TYPE="HIDDEN" name="t" value="comments">';
            $HTML.='<INPUT TYPE="HIDDEN" name="paso" value="">';
            $HTML.='<INPUT TYPE="HIDDEN" name="ID" value="'.$ID.'">';
            $HTML.='</table></form>';
        }
		$web->notEscribir($ip[0],$rate);
    }
    if($paso==3 AND $cookies->esAdmin()) {
		//Adaptacion de variables para PHP v4.2+
		if(isset($_POST['deleteID'])) $deleteID=$_POST['deleteID']; else $deleteID=$_GET['deleteID'];
		//--------------------------------------
		$web->comBorrar($deleteID);
        $HTML.='<form action="index.php" method="post">';		
        $HTML.='<table class="0" width="95%" align="center" border="0" cellspacing="1" cellpadding="5">';
        $HTML.='<caption><span class="title">'.$_Comments_.'</span></caption>';
        $HTML.='<tr class="0"><td align="center" valign="middle" class="minititle">'.$_Comments_.'</td></tr>';
        $HTML.='<tr class="1"><td align="center" valign="middle" class="text">'.$_CommentDeleted_.'</td></tr>';
        $HTML.='<tr class="2"><td align="center" valign="middle"><INPUT TYPE="SUBMIT" class="button" value="'.$_Return_.'"></td></tr>';
        $HTML.='<INPUT TYPE="HIDDEN" name="m" value="top">';
        $HTML.='<INPUT TYPE="HIDDEN" name="s" value="info">';
        $HTML.='<INPUT TYPE="HIDDEN" name="t" value="comments">';
        $HTML.='<INPUT TYPE="HIDDEN" name="paso" value="">';
        $HTML.='<INPUT TYPE="HIDDEN" name="ID" value="'.$ID.'">';
        $HTML.='</table></form>';
    }
}

if($tipo=='puntuar') {
    if($paso==1 OR $paso=='') {
        $HTML='<form action="index.php" method="post" onSubmit="submitOnce(this);">';
        $HTML.='<table width="90%" align="center" border="0" cellspacing="1" cellpadding="5">';
        $HTML.='<tr class="0"><td align="center"><span class="title">'.$gTopNombre.'</span></td></tr>';
        $HTML.='<tr class="1"><td align="center"><span class="text">'.$_Rate_.' '.$web->web.'</span></td></tr>';
        $HTML.='<tr class="2"><td align="center"><SELECT name="nota">';
        for($i=0;$i<=10;$i++) $HTML.='<OPTION value="'.$i.'">'.$i.'</OPTION>';
        $HTML.='</SELECT></td></tr>';
		$HTML.='<tr class="0"><td align="center">';
        $HTML.='<INPUT TYPE="HIDDEN" name="m" value="top">';
        $HTML.='<INPUT TYPE="HIDDEN" name="s" value="info">';
        $HTML.='<INPUT TYPE="HIDDEN" name="t" value="puntuar">';
        $HTML.='<INPUT TYPE="HIDDEN" name="paso" value="2">';
        $HTML.='<INPUT TYPE="HIDDEN" name="ID" value="'.$ID.'">';
        $HTML.='<input type="submit" class="button"></td></tr>';
        $HTML.='</table></form>';
    }
    if($paso==2) {
		//Adaptacion de variables para PHP v4.2+
		$nota=$_POST['nota'];
		//--------------------------------------
		$ip=capturarIP();
        $anterior=$web->notEscribir($ip[0],$nota);
		$cookies->webActualizar($ID,'NOTA',$nota);
        if($anterior=='') {
            $HTML.='<table width="90%" align="center" border="0" cellspacing="1" cellpadding="5">';
            $HTML.='<tr class="0"><td align="center"><span class="title">'.$gTopNombre.'</span></td></tr>';
            $HTML.='<tr class="1"><td align="center" valign="middle"><span class="text">'.$_RateCount_.'</span></td></tr>';
            $HTML.='<tr class="2"><td align="center" valign="middle"><span class="text">'.$web->web.'<br>'.$_RateShown_.'<br><img src="themes/'.$gTema.'/rate'.$nota.'.gif" border="0"></span></td></tr>';
            $HTML.='</table>';
        } else {
            $HTML.='<table width="90%" align="center" border="0" cellspacing="1" cellpadding="5">';
			$HTML.='<tr class="0"><td align="center"><span class="title">'.$gTopNombre.'</span></td></tr>';
            $HTML.='<tr class="1"><td align="center" valign="middle"><span class="text">'.$_RateUpdate_.'</span></td></tr>';
            $HTML.='<tr class="2"><td align="center" valign="middle"><span class="text">'.$web->web.'<br>'.$_RateShown_.'<br><img src="themes/'.$gTema.'/rate'.$nota.'.gif" border="0"><br>'.$_RateShownBefore_.'<br><img src="themes/'.$gTema.'/rate'.$anterior.'.gif" border="0"></span></td></tr>';
            $HTML.='</table>';
        }
    }
}

if($tipo=='email') {
	if($paso==1 AND $cookies->esAdmin()) {
		//Adaptacion de variables para PHP v4.2+
		if(isset($_POST['to'])) $to=$_POST['to']; else $to=$_GET['to'];
		//--------------------------------------
		$web=new SitioWeb($ID);
		$accountInfo="\n\n\n----------------------------------------------------\n";
		$accountInfo.=$_AccountInfo_."\n";
		$accountInfo.=$_WebTitle_.": ".$web->web."\n";
		$accountInfo.=$_Pass_.": ".$web->clave."\n";
		$accountInfo.=$_WebURL_.": ".$web->webURL."\n";
		$accountInfo.=$_ID_.": ".$ID."\n";
		$accountInfo.=$_ControlPanel_.": ".$gTopURL."members.php?modo=edit\n";
		$accountInfo.="----------------------------------------------------\n";
		$accountInfo.=$gTopNombre."\n";
		$accountInfo.=$gAdminEmail."\n";
		$accountInfo.=$gTopURL."\n";
		$accountInfo.="----------------------------------------------------\n";
		$HTML.='<form action="index.php" method="post" onSubmit="submitOnce(this);">';
		$HTML.='<table width="95%" align="center" border="0" cellpadding="2" cellspacing="1">';
		$HTML.='<tr><td class="0" colspan="2" align="center"><span class="title">'.$_Email_.'</span></td></tr>';
		$HTML.='<tr><td class="1" align="right">'.$_To_.':</td>';
		$HTML.='<td class="2">'.$web->web.' &lt;'.$web->email.'&gt;</td>';
		$HTML.='</tr>';
		$HTML.='<tr><td class="1" align="right">'.$_From_.':</td>';
		$HTML.='<td class="2">'.$gTopNombre.' &lt;'.$gAdminEmail.'&gt;</td>';
		$HTML.='</tr>';
		$HTML.='<tr><td class="1" align="right">'.$_Subject_.':</td>';
		$HTML.='<td class="2"><INPUT TYPE="TEXT" class="text" name="subject" size="70"></td>';
		$HTML.='</tr>';
		$HTML.='<tr><td class="1" align="right" valign="top">'.$_Text_.':</td>';
		$HTML.='<td class="2"><TEXTAREA name="text" rows="15" cols="70" wrap="hard">'.$accountInfo.'</TEXTAREA></td>';
		$HTML.='</tr>';
		$HTML.='<tr><td class="0" colspan="2" align="center">';
        $HTML.='<INPUT TYPE="HIDDEN" name="m" value="top">';
        $HTML.='<INPUT TYPE="HIDDEN" name="s" value="info">';
		$HTML.='<INPUT type="hidden" name="t" value="email">';
		$HTML.='<INPUT type="hidden" name="paso" value="2">';
		$HTML.='<INPUT type="reset" class="button">&nbsp;<INPUT type="submit" class="button"></TD></TR>'."\n";
		$HTML.='</table></form>';
	}
	if($paso==2 AND $cookies->esAdmin()) {
		//Adaptacion de variables para PHP v4.2+
		$to=$_POST['to'];
		$subject=$_POST['subject'];
		$text=$_POST['text'];
		//--------------------------------------
		$email=new Email();
		$email->set_from($gAdminEmail,$gTopNombre);
		$email->set_to($to);
		$email->set_subject($subject);
		$email->set_text($text);
		$email->send();
	}
}

//----------------------------------------------------------------
// SALIDA (propia)
//----------------------------------------------------------------
include('code/inc_header.php');	
echo $HTML;
echo '</body></html>';
exit();
?>