<?php
/*******************************************************************************
* data/include/class_inc.php
*
* Procesa y devuelve los datos de los ficheros de información adicional
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
* class PFN_INC
*
* clase para tratamiento de información adicional
* para ficheros y directorios
*/
class PFN_INC {
	var $conf;
	var $datos = array();
	var $mais_datos = array();
	var $arquivos;
	var $resultado = array();
	var $multiple;

	/**
	* function PFN_INC (object $conf)
	*
	* recibe el objecto $conf con los parametros de configuración
	* y el objeto $vars por global para obtener variables
	*/
	function PFN_INC (&$conf) {
		global $vars;

		$this->conf = &$conf;
		$this->vars = &$vars;
	}

	/**
	* function arquivos (object $arquivos)
	*
	* recibe el objecto con las acciones concretas para archivos
	*/
	function arquivos (&$arquivos) {
		$this->arquivos = &$arquivos;
	}

	/**
	* function nome_inc (string $cal)
	*
	* devuelve el posible nombre del archivo de información adicional
	*
	* return string
	*/
	function nome_inc ($cal) {
		if ((substr($cal, -1) == '/') || @is_dir($cal)) {
			return $cal.'/.'.$this->conf->g('inc','suf_ext');
		} else {
			return dirname($cal).'/'.$this->conf->g('inc','pre_ext').basename($cal).'.'.$this->conf->g('inc','suf_ext');
		}
	}

	/**
	* function carga_datos (string $cal)
	*
	* carga los datos adicionales del fichero o directorio $cal
	*/
	function carga_datos ($cal) {
		$this->datos = array();

		if ($this->conf->g('inc','estado')) {
			$cal = $this->nome_inc($cal);

			if (@is_file($cal)) {
				$this->datos = include ($cal);
			}
		}

		return $cal;
	}

	/**
	* function get_datos (void)
	*
	* Devuelve los datos cargados de un fichero de información adicional
	*
	* return array
	*/
	function get_datos () {
		return $this->datos;
	}

	/**
	* function mais_datos (string $campo, string $valor, string $k2)
	*
	* añade mas campos o varía los ya cargados con carga_datos()
	*/
	function mais_datos ($campo, $valor, $k2='') {
		if (empty($k2)) {
			$this->mais_datos[$campo] = $valor;
		} else {
			$this->mais_datos[$campo][$k2] = $valor;
		}
	}

	/**
	* function valor (string $campo, string $k)
	*
	* devuelve el valor de un campo de información adicional
	*
	* return string
	*/
	function valor ($campo, $k='') {
		if (empty($k)) {
			return $this->datos[$campo];
		} else {
			return $this->datos[$campo][$k];
		}
	}

	/**
	* function multiple (string $opc)
	*
	* configura la recepción de datos desde el formulario como
	* múltiple (subida múltiple de ficheros) y añade una extensión
	* al nombre del campo que se recibe
	*/
	function multiple ($opc) {
		$this->multiple = $opc;
	}

	/**
	* function crea_listado (string tipo, integet $limite)
	*
	* crea el texto para enseñar en la pantalla de navegación
	* debajo del nombre del archivo a partir del estado (true/false)
	* de la variable de $this->conf->g('inc',$cal,'listado')
	*
	* return string
	*/
	function crea_listado ($tipo, $limite=100) {
		foreach (array('comun',$tipo) as $lista) {
			foreach ($this->conf->g('inc',$lista) as $v) {
				if ($v['listado'] && $this->datos[$v['nome']]) {
					$txt .= substr($this->datos[$v['nome']],0,$limite).' : ';
				}
			}
		}

		return substr($txt,0,-3);
	}

	/**
	* function crea_formulario (string $tipo)
	*
	* carga y devuelve un array con cada campo y valor
	* del archivo de información adicional para crear
	* un formulario con los campos necesarios.
	* simpre cargará los campos datos por $this->conf->g('inc','comun')
	* y despues los necesarios según el parametro
	* $tipo ('dir','arq') para completar el formulario
	*
	* cuando se usan varios ficheros de configuración, solo se mostrarán
	* aquellos campos que estean relacionados con el actual
	*
	* return array
	*/
	function crea_formulario ($tipo) {
		$this->resultado = array();

		if ($this->conf->g('inc','estado')) {
			$i = 0;

			foreach (array('comun',$tipo) as $lista) {
				foreach ($this->conf->g('inc',$lista) as $v) {
					$this->resultado[$i]['campo'] = '<label for="'.$v['nome']
						.(strlen($this->multiple)?('_'.$this->multiple):'')
						.'">'.$this->conf->t($v['nome']).'</label>';
					// Llamada a la función que genera el html para textarea o text
					$this->$v['tipo']($i,$v);
					$i++;
				}
			}
		}

		return $this->resultado;
	}

	/**
	* function text (string $k, array $v)
	* $k: indice del elemento
	* $v: array con los valores para cubrir el html
	*
	* monta un campo <input type='text' con los parametros recibidos
	*/
	function text ($k, $v) {
		$this->resultado[$k]['valor'] = '<input type="text"'
			.(strlen($this->multiple)?
				(' id="'.$v['nome'].'_'.$this->multiple.'" name="'.$v['nome'].'['.$this->multiple.']"')
				:(' id="'.$v['nome'].'" name="'.$v['nome'].'"'))
			.' value="'.PFN_textoInterno2Form($this->valor($v['nome'])).'"'
			.' class="text" />';
	}

	/**
	* function textarea (string $k, array $v)
	* $k: indice del elemento
	* $v: array con los valores para cubrir el html
	*
	* monta un campo <input type='text' con los parametros recibidos
	*/
	function textarea ($k, $v) {
		$this->resultado[$k]['valor'] = '<textarea'
			.(strlen($this->multiple)?
				(' id="'.$v['nome'].'_'.$this->multiple.'" name="'.$v['nome'].'['.$this->multiple.']"')
				:(' id="'.$v['nome'].'" name="'.$v['nome'].'"')).'>'
			.PFN_textoInterno2Form($this->valor($v['nome']))
			.'</textarea>';
	}

	/**
	* function resultado ()
	*
	* devuelve el resultado del montaje del formulario o la descricion
	*
	* return array
	*/
	function resultado () {
		return $this->resultado;
	}

	/**
	* function crea_descricion (string $tipo)
	*
	* crea una lista con los [campos] = valor obtenidos
	* del fichero de información adicional, el parametro
	* $dir es usado para definir si se deben coger los datos
	* de $this->conf->g('inc','dir') o $this->conf->g('inc','arq')
	*
	* return array
	*/
	function crea_descricion ($tipo) {
		$this->resultado = array();

		if ($this->conf->g('inc','estado')) {
			$i = 0;

			foreach (array('comun',$tipo) as $lista) {
				foreach ($this->conf->g('inc',$lista) as $v) {
					if ($this->valor($v['nome'])) {
						$this->resultado[$i]['campo'] = $this->conf->t($v['nome']);
						$this->resultado[$i]['valor'] = $this->valor($v['nome']);
						$i++;
					}
				}
			}

			$this->resultado[$i]['campo'] = $this->conf->t('descargado');
			$this->resultado[$i]['valor'] = $this->valor('descargado');
		}

		return $this->resultado;
	}

	/**
	* function crea_inc (string $cal, string $tipo)
	* $cal: fichero original (no de datos adicionales)
	* $tipo: formato de datos ('dir','inc')
	*
	* crea o modifica un fichero de información adicional pero manteniendo
	* los valores de otros campos ya existentes en caso de existir
	* solo modificará los valores definidos por $this->conf->g('inc','comun')
	* y $this->conf->g('inc',$tipo)
	* los valores necesarios del formulario los obtiene por POST
	*
	* return string
	*/
	function crea_inc ($cal, $tipo) {
		$campos = array();
		$this->carga_datos($cal);

		foreach ((array)$this->datos as $k => $v) {
			if (is_array($v)) {
				foreach ($v as $k2 => $v2) {
					$campos[$k][$k2] = $v2;
				}
			} else {
				$campos[$k] = $v;
			}
		}

		if ($this->vars->post('')) {
			foreach (array('comun',$tipo) as $lista) {
				foreach ($this->conf->g('inc',$lista) as $v) {
					if (strlen($this->multiple)) {
						$este = $this->vars->post($v['nome']);
						$campos[$v['nome']] = PFN_textoForm2interno($este[$this->multiple]);
					} else {
						$campos[$v['nome']] = PFN_textoForm2interno($this->vars->post($v['nome'].$this->multiple));
					}
				}
			}
		}

		foreach ((array)$this->mais_datos as $k => $v) {
			if (is_array($v)) {
				foreach ($v as $k2 => $v2) {
					$campos[$k][$k2] = $v2;
				}
			} else {
				$campos[$k] = $v;
			}
		}

		return $this->escribe_inc($cal, $campos);
	}

	/**
	* function escribe_inc (string $cal, array $campos)
	* $cal: fichero original (no de datos adicionales)
	* $campos: array con los datos a escribir
	*
	* escribe el fichero de datos adicionales a partir de los
	* datos recibidos por $campos
	*
	* devuelve el nombre del fichero de datos adicionales escrito
	*
	* return string
	*/
	function escribe_inc ($cal, $campos) {
		$txt_arq = '';
		$txt_tmp = '';

		foreach ($campos as $k => $v) {
			if (is_array($v) && trim($k) != '') {
				$txt_v2 = '';

				foreach ($v as $k2 => $v2) {
					$v2 = trim($v2);
					$k2 = trim($k2);

					$txt_v2 .= (strlen($k2) && strlen($v2))?"'".$k2."' => '".$v2."',":'';
				}

				if (!empty($txt_v2)) {
					$txt_tmp .= "\n\t'".$k."' => array(".$txt_v2;
					$txt_tmp = substr($txt_tmp,0,-1).'),';
				}
			} else {
				if (trim($k) != '' && trim($v) != '') {
					$txt_tmp .= "\n\t'".$k."' => '".$v."',";
				}
			} 
		}

		if (!empty($txt_tmp)) {
			$txt_arq = "<?php\nreturn array(".substr($txt_tmp,0,-1)."\n);\n?>";
		}

		if ($this->arquivos->abre_escribe($this->nome_inc($cal),$txt_arq)) {
			return $this->nome_inc($cal);
		}
	}

	/**
	* function eliminar (string $arq)
	*
	* elimina un fichero de información adicional
	* se usa siempre que se elimine el fichero original original
	*
	* return boolean
	*/
	function eliminar ($arq) {
		if (@is_file($this->nome_inc($arq))) {
			return @unlink($this->nome_inc($arq));
		}
	}

	/**
	* function copiar (string $orixinal, string $destino)
	*
	* copia un fichero de información adicional
	* se usa siempre que se copie el fichero original asociado
	*
	* return boolean
	*/
	function copiar ($orixinal,$destino) {
		if (@is_file($this->nome_inc($orixinal))) {
			return @copy($this->nome_inc($orixinal),$this->nome_inc($destino));
		}
	}

	/**
	* function mover (string $orixinal, string $destino)
	*
	* mueve un fichero de información adicional
	* se usa siempre que se mueva el fichero original asociado
	*
	* return boolean
	*/
	function mover ($orixinal,$destino) {
		if (@is_file($this->nome_inc($orixinal))) {
			$ok = @copy($this->nome_inc($orixinal),$this->nome_inc($destino));
			@unlink($this->nome_inc($orixinal));
			return $ok;
		}
	}

	/**
	* function renomear (string $orixinal, string $destino)
	*
	* renombra un fichero de información adicional
	* se usa siempre que se renombre el fichero original asociado
	*
	* return boolean
	*/
	function renomear ($orixinal,$destino) {
		if (@is_file($this->nome_inc($orixinal))) {
			return @rename($this->nome_inc($orixinal),$this->nome_inc($destino));
		}
	}

	/**
	* function vacia_path (string $path, boolean $borrar_inc, boolean $borrar_imx)
	*
	* Elimina todos los ficheros de información adicional de una raíz y sus
	* subdirectorios
	*/
	function vacia_path ($path, $borrar_inc=true, $borrar_imx=true) {
		if (!@is_dir($path)) {
			return false;
		}

		if ($this->conf->g('inc','pre_ext') == '.') {
			$expr = '^\..*'.$this->conf->g('inc','suf_ext').'$';
		} else {
			$expr = '^'.$this->conf->g('inc','pre_ext').'.*'.$this->conf->g('inc','suf_ext').'$';
		}

		$od = @opendir($path);

		while ($cada = @readdir($od)) {
			if ($cada == '.' || $cada == '..') {
				continue;
			}

			if (is_dir($path.$cada)) {
				$this->vacia_path($path.$cada.'/', $borrar_inc, $borrar_imx);
			} else {
				if ((ereg($expr, $cada) && $borrar_inc)
				|| (@GetImageSize($path.$cada) && ereg('^\.', $cada) && $borrar_imx)) {
					@unlink ($path.$cada);
				}
			}
		}

		@closedir($od);
	}
}
?>
