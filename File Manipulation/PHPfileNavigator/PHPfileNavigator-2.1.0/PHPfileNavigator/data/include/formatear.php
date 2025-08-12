<?php
/*******************************************************************************
* data/include/formatear.php
*
* Funciones para diversos tratamientos de datos y texto
*

PHPfileNavigator versión 2.1.0

Copyright (C) 2004-2005 Lito <lito@eordes.com>

http://phpfilenavigator.litoweb.net/

Este programa es software libre. Puede redistribuirlo y/o modificarlo bajo los
términos de la Licencia Pública General de GNU según es publicada por la Free
Software Foundation, bien de la versión 2 de dicha Licencia o bien (según su
elección) de cualquier versión posterior. 

Este programa se distribuye con la esperanza de que sea útil, pero SIN NINGUNA
GARANTÍA, incluso sin la garantía MERCANTIL implícita o sin garantizar la
CONVENIENCIA PARA UN PROPÓSITO PARTICULAR. Véase la Licencia Pública General de
GNU para más detalles. 

Debería haber recibido una copia de la Licencia Pública General junto con este
programa. Si no ha sido así, escriba a la Free Software Foundation, Inc., en
675 Mass Ave, Cambridge, MA 02139, EEUU. 
*******************************************************************************/

defined('OK') or die();

/**
* function PFN_query_array (void)
*
* devuelve la cadena de QUERY_STRING convertida
* en un array multidimensional
*
* return array
*/
function PFN_query_array () {
	global $vars;
	parse_str($vars->server('QUERY_STRING'),$a);
	return $a;
}

/**
* function PFN_query_str (array $params, string $sep)
*
* recibe un array multidimensional y lo transforma en
* una cadena para pasar como QUERY_STRING, con los elementos
* separados por $sep
*
* return string
*/
function PFN_query_str ($params, $sep="&amp;") {
	 foreach ($params as $key => $value) {
		$str .= (strlen($str) < 1) ? '' : $sep;
		$str .= $key.'='.rawurlencode($value);
	}

	return $str;
}

/**
* function PFN_get_url (boolean $php, boolean header)
*
* devuelve la url actual.
* si el parámetro $php es true, la cadena incluye
* al principio el valor de PHP_SELF
* para llamadas desde la funcion Header('Location: ...'); se debe
* pasar a true el parametro $header
*
* return string
*/
function PFN_get_url ($php=true, $header=false) {
	global $vars;
	$cad = $php?($vars->server('PHP_SELF').'?'):'';
	$q = PFN_query_array();
	return $cad.PFN_query_str($q,$header?'&':'&amp;');
}

/**
* function PFN_cambia_url (mixed $orixen, mixed $destino, boolean $php, boolean header)
*
* realiza un cambio en alguna de las variables pasadas por
* QUERY_STRING, en caso de no existir la variable a cambiar,
* la crea.
* el cambio no será permanente ya que la próxima vez que se pida
* el mismo campo, devolve su valor inicial.
* el cambio puede ser multiple si los valores de $orixen y $destino
* son un array
* si el parámetro $php es true, la cadena incluye
* al principio el valor de PHP_SELF
* para llamadas desde la funcion Header('Location: ...'); se debe
* pasar a true el parametro $header
*
* return string
*/
function PFN_cambia_url ($orixen, $destino, $php=true, $header=false) {
	global $vars;
	$cad = $php?($vars->server('PHP_SELF').'?'):'';
	$url = PFN_query_array();

	if (is_array($orixen)) {
		foreach ($orixen as $k => $v) {
			if (empty($destino[$k])) {
				unset($url[$v]);
			} else {
				$url[$v] = $destino[$k];
			} 
		}
	} else {
		if (empty($destino)) {
			unset($url[$orixen]);
		} else {
			$url[$orixen] = $destino;
		} 
	}

	return $cad.PFN_query_str($url,$header?'&':'&amp;');
}

/**
* function PFN_cambia_outra_url (string $url, mixed $orixen, mixed $destino, boolean $header=false)
*
* realiza un cambio en una URL distinta a la actual
*
* return string
*/
function PFN_cambia_outra_url ($url, $orixen, $destino, $header=false) {
	$q = array();
	list($php, $query) = explode('?', $url);
	strlen($query)?parse_str($query, $q):'';

	if (is_array($orixen)) {
		foreach ($orixen as $k => $v) {
			if (empty($destino[$k])) {
				unset($q[$v]);
			} else {
				$q[$v] = $destino[$k];
			}
		}
	} else {
		if (empty($destino)) {
			unset($q[$orixen]);
		} else {
			$q[$orixen] = $destino;
		} 
	}

	$acum = '';
	$partes = explode('/', $php);

	if (eregi('^[a-z]{3,5}:$', $partes[0], $http)) {
		$http = $http[0].'/';
		array_shift($partes);
	} else {
		$http = '';
	}

	foreach ($partes as $v) {
		if (!empty($v) && $v != '.') {
			$acum .= '/'.rawurlencode($v);
		}
	}

	$q = PFN_query_str($q, ($header?'&':'&amp;'));

	return $http.$acum.(empty($q)?'':('?'.$q));
}

/**
* function PFN_quita_url (mixed $orixen, boolean $php, boolean header)
*
* elimina una variable y su valor de QUERY_STRING,
* que no será permanente ya que la próxima vez que se pida
* el mismo valor, existirá.
* si el $orixen es un array, eliminará todos los elementos
* incluidos en el mismo.
* si el parámetro $php es true, la cadena incluye
* al principio el valor de PHP_SELF
* para llamadas desde la funcion Header('Location: ...'); se debe
* pasar a true el parametro $header
*
* return string
*/
function PFN_quita_url ($orixen, $php=true, $header=false) {
	global $vars;

	$cad = $php?($vars->server('PHP_SELF').'?'):'';
	$url = PFN_query_array();

	if (is_array($orixen)) {
		foreach ($orixen as $k => $v) {
			unset($url[$v]);
		}
	} else {
		unset($url[$orixen]);
	}

	return $cad.PFN_query_str($url, $header?'&':'&amp;');
}

/**
* function PFN_quita_url_SERVER (mixed $orixen, boolean $php)
*
* elimina una variable y su valor de QUERY_STRING permanentemente.
* si el $orixen es un array, eliminará todos los elementos
* incluidos en el mismo.
* si el parametro $php es true, la cadena incluye
* al principio el valor de PHP_SELF
*
* return string
*/
function PFN_quita_url_SERVER ($orixen, $php=true) {
	global $vars;
	$cad = $php?($vars->server('PHP_SELF').'?'):'';
	$url = PFN_query_array();

	if (is_array($orixen)) {
		foreach ($orixen as $k => $v) {
			unset($url[$v]);
		}
	} else {
		unset($url[$orixen]);
	}

	$vars->server('QUERY_STRING',PFN_query_str($url,'&'));
	return $cad.$vars->server('QUERY_STRING');
}

/**
* function PFN_get_valor_url (string $cal)
*
* devuelve el valor de la variable $cal
* almacenada en QUERY_STRING
*
* return string
*/
function PFN_get_valor_url ($cal) {
	$q = PFN_query_array();
	return $q[$cal];
}

/**
* function PFN_peso (integer $peso)
*
* formatea el tamaño de un fichero que recibe
* en bytes, para devolverlo en formato legible
*
* return string
*/
function PFN_peso ($peso) {
	if($peso == 0) $formato = '0 B';
	else if ($peso <= 1024) $formato = $peso.' B';
	else if ($peso <= (1024*1024)) $formato = sprintf ('%d KB',(int)($peso/1024));
	else if ($peso <= (1024*1024*1024)) $formato = sprintf ('%.2f MB',($peso/(1024*1024)));
	else $formato = sprintf ('%.2f Gb',($peso/(1024*1024*1024)));

	return $formato;
}

/**
* function PFN_permisos (integer $perms)
*
* Formatea los permisos de un fichero o directorio
* para visualizalos en formato legible
*
* return string
*/
function PFN_permisos ($perms) {
	global $conf;

	if ($conf->g('permisos_num')) {
		return substr(sprintf('%o', $perms), -3);
	}

	if (($perms & 0xC000) == 0xC000) {
		 $info = 's'; // Socket
	} elseif (($perms & 0xA000) == 0xA000) {
		 $info = 'l'; // Symbolic Link
	} elseif (($perms & 0x8000) == 0x8000) {
		 $info = '-'; // Regular
	} elseif (($perms & 0x6000) == 0x6000) {
		 $info = 'b'; // Block special
	} elseif (($perms & 0x4000) == 0x4000) {
		 $info = 'd'; // Directory
	} elseif (($perms & 0x2000) == 0x2000) {
		 $info = 'c'; // Character special
	} elseif (($perms & 0x1000) == 0x1000) {
		 $info = 'p'; // FIFO pipe
	}

	// Owner
	$info .= (($perms & 0x0100) ? 'r' : '-');
	$info .= (($perms & 0x0080) ? 'w' : '-');
	$info .= (($perms & 0x0040) ?
		(($perms & 0x0800) ? 's' : 'x' ) :
		(($perms & 0x0800) ? 'S' : '-'));
	
	// Group
	$info .= (($perms & 0x0020) ? 'r' : '-');
	$info .= (($perms & 0x0010) ? 'w' : '-');
	$info .= (($perms & 0x0008) ?
		(($perms & 0x0400) ? 's' : 'x' ) :
		(($perms & 0x0400) ? 'S' : '-'));
	
	// World
	$info .= (($perms & 0x0004) ? 'r' : '-');
	$info .= (($perms & 0x0002) ? 'w' : '-');
	$info .= (($perms & 0x0001) ?
		(($perms & 0x0200) ? 't' : 'x' ) :
		(($perms & 0x0200) ? 'T' : '-'));
	
	return $info;
}

/**
* function PFN_check_nome (string $nome)
*
* formatea el nombre de un fichero o directorio
* antes de realizar un upload o de crear un directorio
*
* return string
*/
function PFN_check_nome ($nome) {
	global $conf;

	if ($conf->g('nome_riguroso')) {
		$busca = array (
			'á','é','í','ó','ú','à','è','ì','ò','ù',
			'â','ê','î','ô','û','ä','ë','ï','ö','ü',
			'Á','É','Í','Ó','Ú','À','È','Ì','Ò','Ù',
			'Â','Ê','Î','Ô','Û','Ä','Ë','Ï','Ö','Ü',
			'ñ','Ñ','ç','Ç',' ','(',')','?','¿','/',
			'#','º','ª','!','·','#','%','¬','=','¡',
			'^',';','"',"'",'+','[',']','{','}',';',
			'~','¤','¶','ø','þ','æ','ß','ð','«','»',
			'¢','µ','','\\',':','*','<','>','|','$',
			'&','@','`');
		$cambia = array (
			'a','e','i','o','u','a','e','i','o','u',
			'a','e','i','o','u','a','e','i','o','u',
			'A','E','I','O','U','A','E','I','O','U',
			'A','E','I','O','U','A','E','I','O','U',
			'n','N','c','C','_','_','_','_','_','_',
			'_','o','a','_','_','_','_','_','_','_',
			'_','_','_','_','_','_','_','_','_','_',
			'_','_','_','_','_','_','_','_','_','_',
			'_','_','_','_','_','_','_','_','_','_',
			'_','_','_');
	} else {
		$busca = array (
			'?','/','\\',':','*','|','<','>','$','"',
			'%',"'",'+');
		$cambia = array (
			'_','_','_','_','_','_','_','_','_','_',
			'_','_','_');
	}

	return str_replace($busca, $cambia, trim($nome));
}

/**
* function PFN_cambia_intros (string $cadena)
*
* cambia los intros de un texto por <br /> para guardarlo
* como información adicional
*
* return string
*/
function PFN_cambia_intros ($cadena) {
	return ereg_replace("(\r\n|\n|\r)",'<br />', $cadena);
}

/**
* function PFN_intro_normal (string $cadena)
*
* cambia los saltos de variados por saltos con solo \n
*
* return string
*/
function PFN_intro_normal ($cadena) {
	return ereg_replace("(\r\n|\r)","\n", $cadena);
}

/**
* function PFN_iniEtiquetas (string $d)
*
* cambia los < > de las etiquetas html por [[ ]] para evitar
* problemas con htmlentities en la conversión
*
* return string
*/
function PFN_iniEtiquetas ($d) {
	return @preg_replace('/\<(.*)[^>]\>/sU','[[\\1]]',$d);
}

/**
* function PFN_finEtiquetas (string $d)
*
* cambia los [[ ]] despues de procesar un texto html
* por los correctos < >
*
* return string
*/
function PFN_finEtiquetas ($d) {
	@preg_match_all('/\[\[(.*)[^\]\]]\]\]/U',$d,$lista);

	for($i=0; $i < count($lista[1]); $i++) {
		$v = $lista[1][$i];
		$v2 = preg_quote ($lista[0][$i]);
		$tmp = '<'.preg_replace('/&quot;/', '"', $v).'>';
		$d = preg_replace('|'.$v2.'|', $tmp, $d);
	}

	return $d;
}

/**
* function PFN_textoForm2interno (string $d)
*
* formatea un texto de un formulario para
* guardarlo como interno
*
* return string
*/
function PFN_textoForm2interno ($d) {
	if (get_magic_quotes_gpc() == 1) {
		$d = stripslashes($d);
	}

	$d = ereg_replace('[\\]+$', '', trim($d));
	$d = str_replace("'","\\'",$d);
	$d = ereg_replace('[\\]+', '\\', $d);
	$d = PFN_cambia_intros($d);

	return $d;
}

/**
* function PFN_textoInterno2Form (string $d)
*
* formatea un texto interno para colocarlo
* en un formulario
*
* return string
*/
function PFN_textoInterno2Form ($d) {
	$trans = get_html_translation_table(HTML_ENTITIES);
	$trans = array_flip($trans);
	$d = strtr($d, $trans);
	$d = eregi_replace('<br>',"\n", trim($d));
	return eregi_replace('<br \/>',"\n", $d);
}

/**
* function PFN_textoArquivo2pantalla (string $d, boolean $php)
*
* formatea el contenido de un fichero para
* mostrarlo legible por pantalla, coloreado si es un fichero php
*
* return string
*/
function PFN_textoArquivo2pantalla ($d, $php=false) {
	$ini = '<span class="numera">';
	$fin  = '&nbsp;</span>';
	$i = 1;

	if ($php) {
		ob_start();
		@highlight_string($d);
		$d = ob_get_contents();
		ob_end_clean();

		$d = split('(<br \/>|<br>)', $d);
		$d[0] = str_replace('<code>', '', $d[0]);
		$texto = '<code>';
		$total = strlen(count($d));

		foreach ($d as $v) {
			$d = str_replace('font color="','span style="color: ', $d);
			$d = str_replace('font"','span', $d);

			$texto .= $ini.str_repeat('&nbsp;', $total-strlen($i)).$i.':'.$fin.str_replace("\n", '', $v)."\n";
			$i++;
		}

		return $texto;
	} else {
		$d = htmlentities($d);
		$d = split("(\r\n|\n|\r)", $d);
		$texto = '';
		$total = strlen(count($d));

		foreach ($d as $v) {
			$texto .= $ini.str_repeat('&nbsp;', $total-strlen($i)).$i.':'.$fin.str_replace("\n", '', $v)."\n";
			$i++;
		}

		return $texto;
	}
}

/**
* function PFN_listado_select (integer $total, integer $actual, integer $paxinar)
* $total: numero de elementos en un directorio
* $actual: posición actual en la paginación de un directorio
* $paxinar: numero de elementos a mostrar por página en el listado de un directorio
*
* crea un select para la paginación de los elementos contenidos en
* un directorio
*
* return string
*/
function PFN_listado_select ($total, $actual, $paxinar) {
	$cad = '<select class="formulario" id="lista"'
		.' onchange="enlace(\''.PFN_quita_url('lista').'&amp;lista=\'+this.value);"'
		.(($total > $paxinar)?' style="background: #D00; color: #FFF;"':'').'>';

	if ($total == 0) {
		$cad .= '<option value=""> --- </option>';
	} else {
		for ($i = 0; $i < $total; $i += $paxinar) {
			$select = ($i==$actual)?'selected="selected"':'';
			$fin = ( $i+$paxinar >= $total )?$total:($i+$paxinar);
			$cad .= "\n".'<option value="'.$i.'" '.$select.'>'.($i+1).' - '.$fin.'</option>';
		}
	}

	return $cad."\n</select>";
}

/**
* function PFN_espacio_disco (string $arq, boolean $real)
*
* Devuelve el peso real de un fichero en disco, teniendo en cuenta que el tamaño
* mínimo de bloque son 4 kb, si $real es true devolvera el valor de filesize
*
* return integer
*/
function PFN_espacio_disco ($arq, $real=false) {
	global $conf;

	if (is_int($arq)) {
		$s = $arq;
	} else {
		$s = @filesize($arq);
	}

	if ($conf->g('peso_real') || $real) {
		return $s;
	}

	if ($s < 1) {
		return 0;
	} elseif ($s < 4096) {
		return 4096;
	}

	$r = ($s/4096);

	if (is_integer($r)) {
		return $s;
	} else {
		return ((intval($r)+1)*4096);
	}
}
?>
