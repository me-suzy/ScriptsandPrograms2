<?php
/*******************************************************************************
* data/include/class_imaxes.php
*
* Procesa y devuelve tratamientos sobre las imágenes
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
* class PFN_Imaxes
*
* clase para tratamiento de imágenes e iconos para los
* archivos e imágenes
*/
class PFN_Imaxes {
	var $conf;
	var $vars;
	var $paths;
	var $niveles;
	var $arquivos;
	var $orixinal;
	var $destino;
	var $iconos = array();

	/**
	* function PFN_Imaxes (object $conf)
	*
	* recibe el objecto $conf con los parametros de configuración,
	* el objeto $vars por global para obtener variables
	* y el array $paths para las rutas
	*/
	function PFN_Imaxes (&$conf) {
		global $vars, $paths, $relativo;

		$this->vars = &$vars;
		$this->conf = &$conf;
		$this->paths = &$paths;
		$this->path_icos = $conf->g('estilo').'ico/';

		$this->carga_iconos();
	}


	/**
	* function carga_iconos (void)
	*
	* Carga el array de iconos para se usado en el listado
	*/
	function carga_iconos () {
		$this->iconos = include($this->paths['include'].'mime.php');
	}

	/**
	* function niveles (object $niveles)
	*
	* recibe el objecto con las acciones concretas para niveles
	*/
	function niveles (&$niveles) {
		$this->niveles = &$niveles;
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
	* function e_imaxe (string $cal)
	*
	* comprueba si el path dado es una imágen que se puede tratar
	* y que está validada en el fichero de configuración
	* $this->conf->g('imaxes','validas')
	*
	* return boolean
	*/
	function e_imaxe ($cal) {
		$datos = @GetImageSize($cal);

		if ($datos && in_array($datos[2],$this->conf->g('imaxes','validas'))) {
			return $datos;
		} else {
			return false;
		}
	}

	/**
	* function nome_pequena (string $cal)
	*
	* devuelve el posible nombre de la copia reducida
	* de una imágen dada
	*
	* return string
	*/
	function nome_pequena ($cal) {
		return dirname($cal)."/.".basename($cal);
	}

	/**
	* function icono (string $cal)
	*
	* devuelve la imagen relacionada con la extensión
	* del fichero dado, existe una imágen genérica
	* para aquellas extensiones que no tengan icono
	*
	* return string
	*/
	function icono ($cal) {
		$ext = explode(".",$cal);
		$ext = strtolower(end($ext));

		if (isset($this->iconos[$ext])) {
			return $this->path_icos.$ext.'.png';
		} else {
			return $this->path_icos.'0.png';
		}
	}

	/**
	* function sello (string $cal, boolean $auto, boolean $normal)
	*
	* devuelve la imagen reducida de de $cal en caso de existir
	* si no existe devuelve el icono devuelto por $this->icono()
	* la opción de $auto nos permite enviar además el código html
	* para limitar el tamaño de la imágen con los parametros de
	* configuración $this->conf->g('imaxes','sello')
	* Si $normal esta a true, como dirección URL de la imágen,
	* colocará la real, mientras que con false generará una
	* imágen reducida en el momento.
	*
	* return string
	*/
	function sello ($cal, $auto=true, $normal=true) {
		$peq = $this->nome_pequena($cal);

		if ($this->e_imaxe($this->conf->g('raiz','path').$peq)) {
			$dir = dirname($peq);
			$base_imx = basename($peq);
			$mais = ($auto?('" width="'.$this->conf->g('imaxes','sello')
				.'" height="'.$this->conf->g('imaxes','sello')):'');

			if ($normal) {
				return PFN_cambia_outra_url(
					$this->conf->g('protocolo')
					.$this->conf->g('raiz','host')
					.$this->conf->g('raiz','web')
					.$peq, '', '').$mais;
			} else {
				return 'crea_imaxe.php?dir='.urlencode($dir)
					.'&amp;cal='.urlencode($base_imx)
					.'&amp;'.session_name().'='.session_id()
					.$mais;
			}
		} else {
			return $this->icono($cal);
		}
	}

	/**
	* function eliminar (string $arq)
	*
	* elimina una imagen reducida
	* se usa siempre que se elimine la imagen original asociada
	*
	* return boolean
	*/
	function eliminar ($arq) {
		if (is_file($this->nome_pequena($arq))) {
			return @unlink($this->nome_pequena($arq));
		}
	}

	/**
	* function copiar (string $orixinal, string $destino)
	*
	* copia una imagen reducida $orixinal en $destino
	* se usa siempre que se copia la imagen original asociada
	*
	* return boolean
	*/
	function copiar ($orixinal,$destino) {
		if (is_file($this->nome_pequena($orixinal))) {
			return @copy($this->nome_pequena($orixinal),$this->nome_pequena($destino));
		}
	}

	/**
	* function mover (string $orixinal, string $destino)
	*
	* mueve una imagen reducida $orixinal en $destino
	* se usa siempre que se mueve la imagen original asociada
	*
	* return boolean
	*/
	function mover ($orixinal,$destino) {
		if (is_file($this->nome_pequena($orixinal))) {
			$ok = @copy($this->nome_pequena($orixinal),$this->nome_pequena($destino));
			@unlink($this->nome_pequena($orixinal));
			return $ok;
		}
	}

	/**
	* function renomear (string $orixinal, string $destino)
	*
	* renombra una imagen reducida $orixinal en $destino
	* se usa siempre que se renombra la imagen original asociada
	*
	* return boolean
	*/
	function renomear ($orixinal,$destino) {
		if (is_file($this->nome_pequena($orixinal))) {
			return @rename($this->nome_pequena($orixinal),$this->nome_pequena($destino));
		}
	}

	/**
	* function reducir (string $orixinal)
	*
	* Crea una copia exacta reducida de una imágen original
	*
	* return boolean
	*/
	function reducir ($orixinal) {
		if ($this->conf->g('imaxes','pequena') && $datos = $this->e_imaxe($orixinal)) {
			$this->orixinal = $orixinal;
			$this->destino = $this->nome_pequena($orixinal);

			return $this->_reducir($datos);
		} else {
			return false;
		}
	}

	/**
	* function recortar (string $orixinal, intval $posX, intval $posY, intval $ancho, intval $alto)
	*
	* Crea una copia recortada por la zona escogida por el usuario
	*
	* return boolean
	*/
	function recortar ($orixinal, $posX, $posY, $ancho, $alto) {
		if ($this->conf->g('imaxes','pequena') && $datos = $this->e_imaxe($orixinal)) {
			$this->orixinal = $orixinal;
			$this->destino = $this->nome_pequena($orixinal);
			$posX = intval($posX);
			$posY = intval($posY);
			$ancho = intval($ancho);
			$alto = intval($alto);

			return $this->_reducir($datos, $posX, $posY, $ancho, $alto, false);
		} else {
			return false;
		}
	}
	/**
	* function temporal (array $datos, integer $posX, integer $posY, integer $ancho, integer $alto, boolean $manter)
	* $datos: array de valores devueltos por GetImageSize
	* $posX: coordenada X donde se pulsa sobre la imagen $tmp
	* $posY: coordenada Y donde se pulsa sobre la imagen $tmp
	* $ancho: ancho en pixels que se deben coger de la imagen $tmp
	* $ancho: alto en pixels que se deben coger de la imagen $tmp
	* $mantener: mantiene o no las proporciones originales (copia, recorte)
	*
	* crea una imágen temporal a partir de los valores ($datos) de otra
	* la imágen será creada con inicio en $posX y $posY respecto a la
	* imágen original $tmp y cogiendo la zona marcada por $ancho y $alto
	* para reducirla.
	* la reducción sera proporcional a la original con limites de configuración
	* de $this->conf->g('imaxes','ancho') y $this->conf->g('imaxes','alto') en caso
	* de que $mater sea true, en caso contrario se creará una imagen cuadrada
	* con tamaño exacto $this->conf->g('imaxes','ancho') y $this->conf->g('imaxes','alto').
	*
	* devuelve la ruta para poder ser visualizada por web
	*
	* return string
	*/
	function _reducir ($datos, $posX=0, $posY=0, $ancho=0, $alto=0, $manter=true) {
		switch ($datos[2]) {
			case 1:
				$imaxe = @imageCreateFromGIF($this->orixinal);
				break;
			case 2:
				$imaxe = @imageCreateFromJPEG($this->orixinal);
				break;
			case 3:
				$imaxe = @imageCreateFromPNG($this->orixinal);
				break;
			default:
				return false;
				break;
		};

		if (!$imaxe) {
			return 0;
		}

		if ($manter) {
			list($dX, $dY) = $this->proporcions($datos[0], $datos[1]);
			list($ancho, $alto) = array($datos[0], $datos[1]);
		} else {
			$dX = $this->conf->g('imaxes','ancho');
			$dY = $this->conf->g('imaxes','alto');
		}

		list($posX, $posY) = $this->maximos($datos, $posX, $posY, $ancho, $alto);

		// Si da problemas usar imagecreate
		if ($this->conf->g("gd2") == true) {
			$pequena = imageCreateTrueColor($dX, $dY);
		} else {
			$pequena = imageCreate($dX, $dY);
		}

		if (!$this->conf->g('gd2')
		|| ($dX == $datos[0] && $dY = $datos[1])
		|| ($ancho < $dX || $alto < $dY)) {
			// imageCopyResampled no funciona correctamente ya que omite las coordenadas
			// de la imagen original y siempre recorta desde la esquina superior izquierda
			// pero mantengo el código en caso de que se solucione en la próximas versiones
			if (function_exists('imageCopyResampled')) {
				// ImageCopyResized || ImageCopyResampled:
				// $pequena: imagen a reducida a crear
				// $imaxe: imagen original
				// 0, 0: coordenadas de inicio para la nueva imagen
				// $posX: coordenada X donde se pulsa sobre la imagen original
				// $posY: coordenada Y donde se pulsa sobre la imagen original
				//				asi conseguimos que la imagen reducida empiece en las
				//				coordenadas escogidas en la imagen original
				// $dX: ancho en pixels que debe medir la imagen reducida
				// $dY: alto en pixels que debe medir la imagen reducida
				// $ancho: ancho en pixels de la imagen original que debe coger en la imagen reducida
				// $alto: alto en pixels de la imagen original que debe coger en la imagen reducida
				// echo "ImageCopyResized($pequena, $imaxe, 0, 0, $posX, $posY, $dX, $dY, $ancho, $alto)";
				// exit;
	
				//if (!ImageCopyResampled($pequena, $imaxe, 0, 0, $posX, $posY, $dX, $dY, $ancho, $alto)) {
				if (!@ImageCopyResized($pequena, $imaxe, 0, 0, $posX, $posY, $dX, $dY, $ancho, $alto)) {
					@ImageCopyResized($pequena, $imaxe, 0, 0, $posX, $posY, $dX, $dY, $ancho, $alto);
				}
			} else {
				@ImageCopyResized($pequena, $imaxe, 0, 0, $posX, $posY, $dX, $dY, $ancho, $alto);
			}
		} else {
			$this->imageCopyResampleBicubic($pequena, $imaxe, 0, 0, $posX, $posY, $dX, $dY, $ancho, $alto);
		}

		if ($datos[2] == 1 && in_array(1, $this->conf->g('imaxes','validas'))) {
				imagecolortransparent($pequena, imagecolorallocate($imaxe, 0, 0, 0));
				$ok = @imageGIF($pequena, $this->destino, $this->conf->g('imaxes','calidade'));
		} elseif ($datos[2] == 3 && in_array(3, $this->conf->g('imaxes','validas'))) {
				imagecolortransparent($pequena, imagecolorallocate($imaxe, 0, 0, 0));
				$ok = @imagePNG($pequena, $this->destino, $this->conf->g('imaxes','calidade'));
		} else {
				$ok = @imageJPEG($pequena, $this->destino, $this->conf->g('imaxes','calidade'));
		}

		if ($ok) {
			@imageDestroy($pequena);
			return $this->web;
		}
	}

	/**
	* function imageCopyResampleBicubic (
	*		pointer &$dst_img,
	*		pointer &$src_img,
	*		intval $dst_x,
	*		intval $dst_y,
	*		intval $src_x,
	*		intval $src_y,
	*		intval $dst_w,
	*		intval $dst_h,
	*		intval $src_w,
	*		intval $src_h)
	*
	* Realiza un remuestreo bicúbico cuando se crea una copia reducida a partir
	* de otra imagen para mejorar visiblemente la calidad
	*/
	function ImageCopyResampleBicubic (&$dst_img, &$src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) {
		/**
		* port to PHP by John Jensen July 10 2001 (updated June 13, 2002 by tim@smoothdeity.com) --
		* original code (in C, for the PHP GD Module) by jernberg@fairytale.se
		* Taken out of http://www.php.net/manual/en/function.imagecopyresized.php
		*/
		$scaleX = ($src_w - 1) / $dst_w;
		$scaleY = ($src_h - 1) / $dst_h;

		$scaleX2 = (int) ($scaleX / 2);
		$scaleY2 = (int) ($scaleY / 2);

		$dstSizeX = imagesx( $dst_img );
		$dstSizeY = imagesy( $dst_img );
		$srcSizeX = imagesx( $src_img );
		$srcSizeY = imagesy( $src_img );

		for ($j = 0; $j < ($dst_h - $dst_y); $j++) {
			$sY = (int) ($j * $scaleY) + $src_y;
			$y13 = $sY + $scaleY2;
			$dY = $j + $dst_y;

			if (($sY > $srcSizeY) or ($dY > $dstSizeY)) {
				break 1;
			}

			for ($i = 0; $i < ($dst_w - $dst_x); $i++) {
				$sX = (int) ($i * $scaleX) + $src_x;
				$x34 = $sX + $scaleX2;
				$dX = $i + $dst_x;

				if (($sX > $srcSizeX) or ($dX > $dstSizeX)) {
					break 1;
				}

				$color1 = ImageColorsForIndex ($src_img, ImageColorAt ($src_img, $sX, $y13));
				$color2 = ImageColorsForIndex ($src_img, ImageColorAt ($src_img, $sX, $sY));
				$color3 = ImageColorsForIndex ($src_img, ImageColorAt ($src_img, $x34, $y13));
				$color4 = ImageColorsForIndex ($src_img, ImageColorAt ($src_img, $x34, $sY));

				$red = ($color1['red'] + $color2['red'] + $color3['red'] + $color4['red']) / 4;
				$green = ($color1['green'] + $color2['green'] + $color3['green'] + $color4['green']) / 4;
				$blue = ($color1['blue'] + $color2['blue'] + $color3['blue'] + $color4['blue']) / 4;

				ImageSetPixel ($dst_img, $dX, $dY, ImageColorClosest ($dst_img, $red, $green, $blue));
			}
		}
	}

	/**
	* function proporcions (integer $ancho, integer $alto)
	*
	* calula el tamaño proporcional máximo para una imágen con datos
	* $ancho y $alto
	*
	* return array
	*/
	function proporcions ($ancho, $alto) {
		if ($ancho > $alto) {
			$n_ancho = $this->conf->g('imaxes','ancho');
			$n_alto = ($n_ancho*$alto)/$ancho;
			settype($n_alto,'integer');
		} elseif ($alto > $ancho) {
			$n_alto = $this->conf->g('imaxes','alto');
			$n_ancho = ($n_alto*$ancho)/$alto;
			settype($n_ancho,'integer');
		} else {
			$n_alto = $this->conf->g('imaxes','ancho');
			$n_ancho = $this->conf->g('imaxes','alto');
		};
		
		return array($n_ancho,$n_alto);
	}

	/**
	* function maximos (array $datos, integer $posX, integer $posY, integer $ancho, integer $alto)
	*
	* en caso de que los valores de posición $posX o $posY + el $ancho o $alto
	* superen el tamaño real de la imagen, busca la posición correcta para no
	* sobrepasar los límites de la imagen
	*
	* return array
	*/
	function maximos ($datos, $posX, $posY, $ancho, $alto) {
		$posX = ($posX+$ancho)>$datos[0]?$datos[0]-$ancho:$posX;
		$posX = $posX<0?0:$posX;
		$posY = ($posY+$alto)>$datos[1]?$datos[1]-$alto:$posY;
		$posY = $posY<0?0:$posY;
		
		return array($posX,$posY);
	}

	/**
	* function volcar_imaxe (string $cal)
	*
	* crea una copia de una imágen con las medidas recibidas
	*
	* En caso de no recibir medidas o que las medidas origen y destino
	* sean iguales, vuelca directamente la imágen recibida
	*
	* return string
	*/
	function volcar_imaxe ($cal, $ancho, $alto) {
		$datos = @getImageSize($cal);

		if (empty($ancho) || empty($alto)
		|| ($datos[0] == $ancho && $datos[1] == $alto)) {
			return $this->arquivos->get_contido($cal);
		} elseif (is_array($datos)) {
			$this->orixinal = $cal;
			$this->destino = $this->paths['tmp'].time().rand(1,3600);
			
			$ancho_orix = $this->conf->g('imaxes','ancho');
			$alto_orix = $this->conf->g('imaxes','alto');

			$this->conf->p(intval($ancho), 'imaxes','ancho');
			$this->conf->p(intval($alto), 'imaxes','alto');

			$this->_reducir($datos);

			$contido = &$this->arquivos->get_contido($this->destino);

			@unlink($this->destino);

			$this->conf->p(array('imaxes','ancho'), $ancho_orix);
			$this->conf->p(array('imaxes','alto'), $alto_orix);

			return $contido;
		} else {
			return '';
		}
	}
}
?>
