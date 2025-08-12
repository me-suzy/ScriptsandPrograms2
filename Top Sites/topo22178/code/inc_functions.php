<?php
//
//  inc_funciones.php
//	rev006
//  PHP v4.2+
//

function ej3Rendimiento($nivel,$info,$lugar) {
	//Tipo de compresión soportado
	$ENCODING=CheckCanGzip();
	//Buffer de salida
	$html=ob_get_contents();
	ob_end_clean();
	//¿Rendimiento?
	if($info) {
		$info='<table><tr><td class="description">';
		if($GLOBALS['gUsuariosOnline']) $info.='<a href="javascript;" onClick="return false;" onMouseOver="return overlib(\''.$GLOBALS['online']->overLib().'\',HAUTO,FULLHTML,OFFSETX,5,OFFSETY,'.(-90-21*count($GLOBALS['online']->paises)).',STATUS,\''.$GLOBALS['_OnlineUsers_'].'\')" onMouseOut="nd();"><img src=themes/'.$GLOBALS['gTema'].'/online.gif border=0 align=absmiddle><b>'.$GLOBALS['_Online_'].':</b></a> '.$GLOBALS['online']->online_ahora.'/'.$GLOBALS['online']->max_hoy.'/'.$GLOBALS['online']->max_total.'&nbsp;&nbsp;||&nbsp;&nbsp;';
		if(isset($GLOBALS['tiempo_usado'])) $info.='<b>CPU:</b> '.number_format(ej3Time()-$GLOBALS['tiempo_usado'],3,'.','').'seg.&nbsp;&nbsp;||&nbsp;&nbsp;';
		$info.='<b>DataBase(</b>'.$GLOBALS['db_numConsultas'].'<b>):</b> '.number_format($GLOBALS['db_segConsultas'],3,'.','').'seg.&nbsp;&nbsp;||&nbsp;&nbsp;';
		if($nivel AND $ENCODING) {
			$info.='<b>'.$ENCODING.'(</b>'.$nivel.'<b>):</b> ';
			$info.=number_format(strlen($html)/1024,1,'','')."KB..";
			$info.="(".number_format(100-(100*strlen(gzcompress($html,$nivel))/strlen($html)),0)."%)";
			$info.="..".number_format(strlen(gzcompress($html,$nivel))/1024,1,'','').'KB.';
		} else {
			$info.='<b>'.$GLOBALS['_Size_'].':</b> ';
			$info.=number_format(strlen($html)/1024,1,'','')."KB.";
		}
		$info.='</td></tr></table>';
		$html=str_replace($lugar,$info,$html); 
	}
	//¿Comprimir?
	if($nivel AND $ENCODING) {
		header("Content-Encoding: $ENCODING");
		print "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		$Size=strlen($html);
		$Crc=crc32($html);
		$html=gzcompress($html,$nivel);
		$html=substr($html,0,strlen($html)-4);
		print $html;
		print pack('V',$Crc);
		print pack('V',$Size);
	} else {
		print $html;
	}
}

function CheckCanGzip(){ 
    if(headers_sent() || connection_aborted()) return 0; 
    if(strpos($_SERVER['HTTP_ACCEPT_ENCODING'],'x-gzip')!==false) return "x-gzip"; 
    if(strpos($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip')!==false) return "gzip"; 
    return 0; 
} 
/*
function ej3gzip($nivel,$info,$lugar) {
	$ENCODING=CheckCanGzip();
    if($ENCODING){ 
        print "\n<!-- Usando compresión: $ENCODING -->\n"; 
        $html=ob_get_contents(); 
        ob_end_clean(); 
        if($info) {
			$info='<table><tr><td class="description">';
			$info.='<a href=""><img src="themes/'.$GLOBALS['gTema'].'/loading.gif" align="absmiddle" border="0" alt="Online"><b>Online:</b></a> xx/yy/zz&nbsp;&nbsp;||&nbsp;&nbsp;';
			if(isset($GLOBALS['tiempo_usado'])) $info.='<b>CPU:</b> '.number_format(ej3Time()-$GLOBALS['tiempo_usado'],3,'.','').'seg.&nbsp;&nbsp;||&nbsp;&nbsp;';
			$info.='<b>DataBase(</b>'.$GLOBALS['db_numConsultas'].'<b>):</b> '.number_format($GLOBALS['db_segConsultas'],3,'.','').'seg.&nbsp;&nbsp;||&nbsp;&nbsp;';
			$info.='<b>'.$ENCODING.'(</b>'.$nivel.'<b>):</b> ';
            $info.=number_format(strlen($html)/1000,1,'','')."KB..";
			$info.="(".number_format(100-(100*strlen(gzcompress($html,$nivel))/strlen($html)),0)."%)";
            $info.="..".number_format(strlen(gzcompress($html,$nivel))/1000,1,'','').'KB.';
			$info.='</td></tr></table>';
			$html=str_replace($lugar,$info,$html); 
        }
        header("Content-Encoding: $ENCODING");  	
        print "\x1f\x8b\x08\x00\x00\x00\x00\x00"; 
        $Size=strlen($html); 
        $Crc=crc32($html); 
        $html=gzcompress($html,$nivel); 
        $html=substr($html,0,strlen($html)-4); 
        print $html; 
        print pack('V',$Crc); 
        print pack('V',$Size); 
        exit; 
    } else {
        ob_end_flush(); 
        exit; 
    } 
}
*/

function ej3Time() {
	list($usec, $sec) = explode(" ",microtime());
	return number_format((float)$usec + (float)$sec,4,'.','');
}

function ej3Date($formato,$tiempo='') {
	//global $gTiempoOffSet, $_Day_, $_Month_;
	if($tiempo=='') $tiempo=time();
	$fecha=getdate($tiempo+$GLOBALS['gTiempoOffSet']);
	if($fecha['hours']<10) $fecha['hours']='0'.$fecha['hours'];
	if($fecha['minutes']<10) $fecha['minutes']='0'.$fecha['minutes'];
	if($fecha['seconds']<10) $fecha['seconds']='0'.$fecha['seconds'];
	if($formato=='fechaLarga') {
		return $GLOBALS['_Day_'][$fecha['wday']].'.'.$fecha['mday'].'-'.$GLOBALS['_Month_'][$fecha['mon']].'-'.$fecha['year'];
	} elseif($formato=='fechaCorta') {
		return substr($GLOBALS['_Day_'][$fecha['wday']],0,3).'.'.substr($fecha['mday'],0,3).'-'.substr($GLOBALS['_Month_'][$fecha['mon']],0,3).'-'.$fecha['year'];
	} elseif($formato=='horaLarga') {
		return $fecha['hours'].':'.$fecha['minutes'].'&#39;'.$fecha['seconds'].'&#34;';
	} elseif($formato=='horaCorta') {
		return $fecha['hours'].':'.$fecha['minutes'];
	} elseif($formato=='hora12') {
	
	} else {
		return date($formato,$tiempo+$gTiempoOffSet);
	}
}

function ej3Mail($to,$from,$subject,$text) {
	$headers='From: '.$from;
	@mail($to,$subject,$text,$headers);
}

/**
 * capturarIP()
 * Captura las IPs del usuario actual.
 * 
 * @return array Array (0=IP primaria, 1=IP secundaria)
 **/
function capturarIP() {
	if(!isset($_SERVER)) $_SERVER=$HTTP_SERVER_VARS;
	$IPs[0]=$_SERVER['REMOTE_ADDR'];
	$IPs[1]=$_SERVER['REMOTE_ADDR'];
	if(isset($_SERVER['HTTP_X_FOWARD'])) $IPs[0]=$_SERVER['HTTP_X_FOWARD'];
	return $IPs;
}

/**
 * capturarPais()
 * Captura el pais a partir del hostname.
 * El código se verifica a partir del array asociativo $_Country_
 * 
 * @param string Hostname
 * @return string Codigo de pais con dos letras ó 'unknow'
 **/
function capturarPais($hostname) {
	$pais='unknow';
	$aux=explode('.',$hostname);
	$num=count($aux);
	$aux[$num-1]=chop($aux[$num-1]);
	for($i=$num-1;$i>=0;$i--) {
		if(strlen($aux[$i])==2 AND isset($GLOBALS['_Country_'][$aux[$i]])) $pais=$aux[$i];
	}
	if(isset($GLOBALS['_Country_'][$aux[$num-1]])) $pais=$aux[$num-1];
	if($pais=='uk') $pais='en';
	return $pais;
}

function capturarHost($hostname) {
	$aux=explode('.',$hostname);
	$num=count($aux);
	$aux[$num-1]=chop($aux[$num-1]);
	$salida=$aux[$num-1];
	for($i=$num-2;$i>=0;$i--) {
		$salida=$aux[$i].'.'.$salida;
		if(strlen($aux[$i])>3 and $aux[$i]!='info') {
			return $salida;
		}
	}
	return 'unknow';
}

function barra($modo,$para1,$para2,$para3) {
	//global $gTema;
    if($modo=="simple") {
        if($para3==0) {
            $cociente=0;
        } else {
            $cociente=number_format(($para1*$para2)/$para3,0,'.','');
        }
        $HTML='<img src="themes/'.$GLOBALS['gTema'].'/bar_1.jpg" valign="middle" border="0" height="10" width="'.$cociente.'">';
        return $HTML;
    }

    if($modo=="ratio") {
        if($para2+$para3==0) {
            $cociente1=0;
            $cociente2=0;
        } else {
            $cociente1=number_format($para1*$para2/($para2+$para3),0,'.','');
            $cociente2=number_format($para1*$para3/($para2+$para3),0,'.','');
        }
        if($para2<=$para3) {
            if($para2) {
                $para3=number_format($para3/$para2,0,'.','');
                $para2=1;
            } else {
                $para2='';
            }
        } else {
            if($para3) {
                $para2=number_format($para2/$para3,0,'.','');
                $para3=1;
            } else {
                $para3='';
            }
        }
        $HTML='<table border="0" cellspacing="0" cellpadding="0"><tr>';
        $HTML.='<td class="bar1" width="'.$cociente1.'" align="center"><span class="textbar">'.$para2.'</span></td>';
        $HTML.='<td class="bar2" width="'.$cociente2.'" align="center"><span class="textbar">'.$para3.'</span></td>';
        $HTML.='</tr></table>';
        return $HTML;
    }
}

function EstaPersonal($ID) {
	$galletas=new Cookies;
	if($codigoHTML=$galletas->webHTML(str_replace('.','',$ID))) {
		return ' onMouseOver="return overlib(\''.$codigoHTML.'\',FULLHTML,STATUS,\'Per.Stats\')" onMouseOut="nd();"';
	} else {
		return '';
	}
}

function config($old,$new,$ruta='') {
    $fp=fopen($ruta.'inc_config.php','r');
    $raw=fread($fp,filesize($ruta.'inc_config.php'));
    fclose($fp);
	$data=str_replace($old,$new,$raw);
    $fp=fopen($ruta.'inc_config.php','w');
    flock($fp,2);
    $ok=fwrite($fp,$data);
    fclose($fp);
    return $ok;
}

function AutoBanner($para) {	//rev003
	//Cargamos los modelos.
	$fp=fopen('themes/'.$GLOBALS['gTema'].'/templates.dat','r');
	$data=fread($fp,filesize('themes/'.$GLOBALS['gTema'].'/templates.dat'));
	fclose($fp);
	$modelo=explode('||*|||*|||||*',$data);
	$variables=explode('||',$modelo[1]);

	for($i=0;$i<$para;$i++)	$codigo[$i]='';
	$indice=new Index();
	for($i=0;$i<$para;$i++) {
		do {
			if($indice->numRegistros==0) return $codigo;
			$key=array_rand($indice->registros);
			$sitio=new SitioWeb($key);
			if(!is_array($sitio->bannerURL)) $indice->numRegistros=$indice->numRegistros-1;
		} while(!isset($sitio->bannerURL[0]));
		$codigo[$i]='<CENTER><span id="infSdM_AutoBanner'.$i.'" class="description"></span>';
		$codigo[$i].='<a href="index.php?m=top&s=out&ID='.$key.'" target="_blank">';
		$codigo[$i].='<img id="banSdM_AutoBanner'.$i.'" src="themes/'.$GLOBALS['gTema'].'/loading.gif" border="0" onMouseMove="window.status=\''.str_replace('&#'.ord("'"),"\'",$sitio->web).'\';" onMouseOut="window.status=\' \';"></a></CENTER>';
		$codigo[$i].='<SCRIPT>bannerSdM_AutoBanner'.$i.'=new PrecargarBanner(\''.$sitio->bannerURL[array_rand($sitio->bannerURL)].'\',\'banSdM_AutoBanner'.$i.'\',\'infSdM_AutoBanner'.$i.'\',20,'.$variables[1].','.$variables[2].');</SCRIPT>';
	}
	return $codigo;
}

?>