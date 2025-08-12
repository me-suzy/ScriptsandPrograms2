<?php
/****************************************************************************
* data/include/class_vars.php
*
* Procesa y devuelve los datos de las variables
* GET, POST, SESSION, FILES, SERVER
*

PHPfileNavigator versión 2.0.0

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
* class PFN_Vars
*
* clase carga, modifica y obtiene los valores de las variables de
* GET, POST, SESSION, FILES, SERVER
*/
class PFN_Vars {
	var $get = array();
	var $post = array();
	var $session = array();
	var $files = array();
	var $server = array();

	/**
	* function PFN_Vars (void)
	*
	* carga las variables predefinidas de PHP necesarias
	* realiza la comprobacion para _SERVER y HTTP_SERVER_VARS
	*/
	function PFN_Vars () {
		if (is_array($_SERVER)) {
			$this->get = &$_GET;
			$this->post = &$_POST;
			$this->session = &$_SESSION;
			$this->files = &$_FILES;
			$this->server = &$_SERVER;
		} else {
			$this->get = &$GLOBALS['HTTP_GET_VARS'];
			$this->post = &$GLOBALS['HTTP_POST_VARS'];
			$this->session = &$GLOBALS['HTTP_SESSION_VARS'];
			$this->files = &$GLOBALS['HTTP_POST_FILES'];
			$this->server = &$GLOBALS['HTTP_SERVER_VARS'];
		}
	}

	/**
	* function get (string $k, string $v)
	*
	* en caso de que $v no tenga valor, devuelve el
	* valor de la variable $k de $_GET,
	* si $v tiene valor se asociará su valor con la
	* variable de $_GET[$k]
	*
	* return mixed
	*/
	function get ($k, $v='-*-') {
		if (empty($k)) {
			return $this->get;
		}

		if ($v == '-*-') {
			if (empty($this->get[$k])) {
				return false;
			} else {
				return urldecode($this->get[$k]);
			}
		} else {
			$this->get[$k] = urlencode($v);
		}
	}

	/**
	* function post (string $k, string $v)
	*
	* en caso de que $v no tenga valor, devuelve el
	* valor de la variable $k de $_POST,
	* si $v tiene valor se asociará su valor con la
	* variable de $_POST[$k]
	*
	* return mixed
	*/
	function post ($k, $v='-*-') {
		if (empty($k)) {
			return $this->post;
		}

		if ($v == '-*-') {
			if (empty($this->post[$k])) {
				return false;
			} else {
				return $this->post[$k];
			}
		} else {
			$this->post[$k] = $v;
		}
	}

	/**
	* function session (mixed $k, string $v)
	*
	* en caso de que al pedir el primer valor de sesion
	* la variable $_SESSION este vacía, volverá a ser
	* cargada.
	* en caso de que $v no tenga valor, devuelve el
	* valor de la variable $k de $_SESSION,
	* si $v tiene valor se asociará su valor con la
	* variable de $_SESSION[$k]
	*
	* return mixed
	*/
	function session ($k, $v='-*-') {
		if (empty($this->session) || !count($this->session)) {
			if (is_array($_SESSION)) {
				$this->session = &$_SESSION;
			} else {
				$this->session = &$GLOBALS['HTTP_SESSION_VARS'];
			}
		}

		if (empty($k)) {
			return $this->session;
		}

		if ($v == '-*-') {
			if (is_array($k)) {
				return $this->session[$k[0]][$k[1]];
			} else {
				return $this->session[$k];
			}
		} else {
			if (is_array($k)) {
				$this->session[$k[0]][$k[1]] = $v;
			} else {
				$this->session[$k] = $v;
			}
		}
	}

	/**
	* function files (string $k, string $v)
	*
	* en caso de que $v no tenga valor, devuelve el
	* valor de la variable $k de $_FILES,
	* si $v tiene valor se asociará su valor con la
	* variable de $_FILES[$k]
	*
	* return mixed
	*/
	function files ($k, $v='-*-') {
		if (empty($k)) {
			return $this->files;
		}

		if ($v == '-*-') {
			return $this->files[$k];
		} else {
			$this->files[$k] = $v;
		}
	}

	/**
	* function server (string $k, string $v)
	*
	* en caso de que $v no tenga valor, devuelve el
	* valor de la variable $k de $_SERVER,
	* si $v tiene valor se asociará su valor con la
	* variable de $_SERVER[$k]
	*
	* return mixed
	*/
	function server ($k, $v='-*-') {
		if (empty($k)) {
			return $this->server;
		}

		if ($v == '-*-') {
			return $this->server[$k];
		} else {
			$this->server[$k] = $v;
		}
	}
}

$vars = new PFN_Vars();
?>
