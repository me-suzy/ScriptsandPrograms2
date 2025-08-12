<?php
/****************************************************************************
* data/include/class_sesion.php
*
* Procesa los datos de las sesiones mediante session_set_save_handler para
* permitir encriptación
*

PHPfileNavigator versión 2.0.1

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
* class PFN_Sesion
*
* Clase que maneja los ficheros de sesion para permitir encriptado de datos
*/
class PFN_Sesion {
	var $activa = true;
	var $clave;
	var $sesion;
	var $fp = false;
	var $encriptar = array('r' => true, 'w' => true);
	var $tempo = 1800; // Duración de la sesión, por defecto 1/2 hora

	/**
	* function PFN_Sesion (object &$conf)
	*
	* Inicializa la clase de sesión, carga la clave privada y el tiempo máximo
	* de sesión, cargando la clave privada del fichero de configuración
	*/
	function PFN_Sesion (&$conf) {
		if ($this->activa) {
			$this->clave = $conf->g('clave');

			ini_set('session.use_trans_sid', '0');
			ini_set('session.cache_limiter', 'nocache');
			ini_set('session.cache_expire', '180');
			ini_set('session.use_cookies', '1');
			ini_set('session.use_only_cookies', '0');
			ini_set('session.gc_maxlifetime', $this->tempo);
			ini_set('session.save_handler', 'user');
		}
	}

	/**
	* function inicia (void)
	* 
	* Inicializa la función manejadora de sesiones
	*/
	function inicia () {
		if ($this->activa) {
			session_set_save_handler(
				array(&$this, 'abrir'),
				array(&$this, 'pechar'),
				array(&$this, 'ler'),
				array(&$this, 'escribir'),
				array(&$this, 'destruir'),
				array(&$this, 'caducar')
			);
		}
	}

	/**
	* function encriptar (boolean $r, boolean $w)
	*
	* Permite indicar si se va a encriptar o no los datos de sesion
	*/
	function encriptar ($r, $w) {
		$this->encriptar = array('r' => $r, 'w' => $w);
	}

	/**
	* function abrir (string $save_path, string $session_name)
	*
	* Carga el nombre de fichero de sesión
	*
	* return boolean
	*/
	function abrir ($save_path, $session_name) {
		$session_id = session_id();

		if (empty($session_id)) {
			list($sec, $usec) = explode(' ', microtime());
			mt_srand((float) $sec + ((float) $usec * 100000));
			$session_id = md5(uniqid(mt_rand(), true));

			session_id($session_id);
		}

		$this->sesion = $save_path.'/sess_'.$session_id;

		return true;
	}

	/**
	* function pechar (void)
	*
	* Desbloquea y cierra el fichero de sesión abierto
	*
	* return boolean
	*/
	function pechar () {
		@fclose($this->fp);
		unset($this->fp);

		return true;
	}

	/**
	* function ler (void)
	*
	* Abre para lectura y bloquea el fichero de sesion, desencriptando su
	* contenido y devolviendo la cadena resultante
	*
	* return string
	*/
	// Original: function ler ($id) {
	function ler () {
		if ($this->fp) {
			@fclose($this->fp);
		}

		if ($this->fp = @fopen($this->sesion, 'r')) {
			@flock($this->fp, LOCK_SH);
			$datos = @fread($this->fp, @filesize($this->sesion));
			@flock($this->fp, LOCK_UN);

			if ($this->encriptar['r']) {
				return $this->desencripta($datos);
			} else {
				return $datos;
			}
		} else {
			return '';
		}
	}

	/**
	* function escribir (string $id, string $sess_data)
	*
	* Abre en modo escritura y bloquea el fichero de sesión, encriptando
	* y escribiendo la cadena resultante
	*
	* return boolean
	*/
	function escribir ($id, $sess_data) {
		if ($this->fp) {
			@fclose($this->fp);
		}

		$ok = false;

		if ($this->fp = @fopen($this->sesion, 'w')) {
			@flock($this->fp, LOCK_EX);

			if ($this->encriptar['w']) {
				$sess_data = $this->encripta($sess_data);
			}

			$ok = @fwrite($this->fp, $sess_data);
			@flock($this->fp, LOCK_UN);
		}
		
		return $ok;
	}

	/**
	* function destruir (void)
	*
	* Elimina el fichero de sesión
	*
	* return boolean
	*/
	// Original: function destruir ($id) {
	function destruir () {
		$this->pechar();
		$ok = @unlink($this->sesion);
		unset($this->sesion);

		return $ok;
	}

	/**
	* function caducar (void)
	*
	* Elimina todas las sesiones con un tiempo de acceso mayor al definido
	* en la varibale $this->tempo
	*
	* return boolean
	*/
	// Original: function caducar ($maxlifetime) {
	function caducar () {
		if ((time() - @fileatime($this->sesion)) > $this->tempo) {
			$this->destruir();
		}

		return true;
	}

	/**
	* function keyED (string $cad)
	*
	* Genera una clave para la cadena recibida 
	*
	* return string
	*/
	function keyED ($cad) {
		$lonx_clave = strlen($this->clave);
		$lonx_cad = strlen($cad);
		$cnt = 0;
		$resultado = '';

		for ($i=0; $i < $lonx_cad; $i++) {
			$cnt = ($cnt == $lonx_clave)?0:$cnt;
			$resultado .= substr($cad, $i, 1) ^ substr($this->clave, $cnt, 1);
			$cnt++;
		} 

		return $resultado;
	} 

	/**
	* function encripta (string $cad)
	*
	* Encripta una cadena de texto $cad y devuelve el resultado
	*
	* return string
	*/
	function encripta ($cad) {
		srand((double)microtime()*1000000);
		$aleatorio = md5(rand(0,32000));
		$lonx_clave = strlen($this->clave);
		$lonx_cad = strlen($cad);
		$cnt = 0;
		$resultado = '';

		for ($i=0; $i < $lonx_cad; $i++){
			$cnt = ($cnt == $lonx_clave)?0:$cnt;
			$resultado .= substr($aleatorio, $cnt, 1).(substr($cad, $i, 1) ^ substr($aleatorio, $cnt, 1));
			$cnt++;
		} 

		return base64_encode($this->keyED($resultado));
	} 
	
	/**
	* function desencripta (string $cad)
	*
	* Desencripta una cadena de texto $cad y devuelve el resultado
	*
	* return string
	*/
	function desencripta ($cad) {
		$cad = $this->keyED(base64_decode($cad));
		$lonx_cad = strlen($cad);
		$resultado = '';

		for ($i=0; $i < $lonx_cad; $i++){
			$md5 = substr($cad ,$i, 1);
			$i++;
			$resultado .= (substr($cad, $i, 1) ^ $md5);
		}

		return $resultado;
	}
}

$sesion = &new PFN_Sesion($conf);
$sesion->inicia();
?>
