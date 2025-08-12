<? ## 18/09/2003

## v1.1 Stable ##
## Changelog:
##   -Bug fix: string length cannot be overwritten to smaller than default
######
## Verification Image
## pt_br: Imagem de Verificação
######
## This class generate an image with random text
## to be used in form verification. It has visual
## elements design to confuse OCR software preventing
## the use of BOTS.
##
## pt_br:
## Esta classe  gera uma imagem com texto randomico
## para ser usada em validação de formularios. Ela tem 
## elementos visuais desenhados para confundir softwares
## de OCR, prevenindo o uso de BOTS.
##
#######
## Author: Rafael Machado Dohms (DoomsDay)
## Email: dooms@terra.com.br
##
## 18/09/2003
#######
## Usage: See attached files
## Uso: Ver anexos
## 		OR / OU
## http://planeta.terra.com.br/informatica/d2000/vImage/vImage_withexamples.zip
#####


class text_on_image{

	var $numChars = 3; # Tamanho da String: default 3;
	var $w; # Largura da imagem
	var $h = 20; # Altura da Imagem: default 15;
	var $colBG = "188 220 231";
	var $colTxt = "0 0 0";
	var $colBorder = "0 128 192";
	var $charx = 20; # Espaço lateral de cada char
	var $numCirculos = 10; #Numeros de circulos randomicos
	var $post_name;
	var $session_name;
	
	
	function text_on_image(){
		@session_start();
		$this->post_name = 'text_on_image';
		$this->session_name = 'text_on_image';		
		$this->loadCodes();
	}
	
	function getText($num){
		# receber tamanho da string
		if (($num != '')&&($num > $this->numChars)) $this->numChars = $num;		
		# gerar string randmica
		$this->texto = $this->getString();		
		$_SESSION[$this->session_name] = $this->texto;
	}
	
	function loadCodes($code = ''){
	    global $_SESSION;
	    if(!empty($code)){
			    $this->postCode = $code;
			}else{
			    $this->postCode = trim(@$_POST[$this->post_name]);
			}		
		  $this->sessionCode = trim($_SESSION[$this->session_name]);
	}
	
	function checkCode(){
	    if (isset($this->postCode)) $this->loadCodes();
		  if (strtolower($this->postCode) == strtolower($this->sessionCode)){
			    return true;
		  }else{
			    return false;
			}				
	}
	function checkCodeSensitive(){
	    if (isset($this->postCode)) $this->loadCodes();
		  if ($this->postCode == $this->sessionCode){
			    return true;
		  }else{
			    return false;
			}				
	}
    function showCodeBox(){
		    $str = '<input type="text" name="'.$this->post_name.'">';		
			  return $str;
	  }
	
	function showImage($number_of_character = 6){
	    $this->getText($number_of_character);		
	    $this->getImage();		
		  header("Content-type: image/png");
			//try to make sure that this page not be cached, for security reason!
      //start		
      header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");               // Date in the past
      header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  // always modified
      header ("Cache-Control: no-cache, must-revalidate");             // HTTP/1.1
      header ("Pragma: no-cache");                                     // HTTP/1.0		
      //end	 	 
		  ImagePng($this->im);		
	}
	
	function getImage(){
		# Calcular tamanho para caber texto
		$this->w = ($this->numChars*$this->charx) + 40; #5px de cada lado, 4px por char
		# Criar img
		$this->im = imagecreatetruecolor($this->w, $this->h); 
		#desenhar borda e fundo
		imagefill($this->im, 0, 0, $this->getColor($this->colBorder));
		imagefilledrectangle ( $this->im, 1, 1, ($this->w-2), ($this->h-2), $this->getColor($this->colBG) );

		#desenhar circulos
		for ($i=1;$i<=$this->numCirculos;$i++) {
			$randomcolor = imagecolorallocate ($this->im , rand(100,255), rand(100,255),rand(100,255));
			imageellipse($this->im,rand(0,$this->w-10),rand(0,$this->h-3), rand(20,60),rand(20,60),$randomcolor);
		}
		#escrever texto
		$ident = 20;
		for ($i=0;$i<$this->numChars;$i++){
			$char = substr($this->texto, $i, 1);
			$font = rand(4,5);
			$y = round(($this->h-15)/2);
			$col = $this->getColor($this->colTxt);
			if (($i%2) == 0){
				imagechar ( $this->im, $font, $ident, $y, $char, $col );
			}else{
				imagecharup ( $this->im, $font, $ident, $y+10, $char, $col );
			}
			$ident = $ident+$this->charx;
		}

	}
	
	function getColor($var){
		$rgb = explode(" ",$var);
		$col = imagecolorallocate ($this->im, $rgb[0], $rgb[1], $rgb[2]);
		return $col;
	}
	
	function getString(){
		rand(0,time());
		$possible="AGHacefhjkrStVxY124579";
		while(strlen($str)<$this->numChars)
		{
				$str.=substr($possible,(rand()%(strlen($possible))),1);
		}

		$txt = $str;
		
		return $txt;
	}
} 

#dooms@terra.com.br#
?>