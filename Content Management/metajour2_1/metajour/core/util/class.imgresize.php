<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage util
 */

class imgresize {
	var $img;

	function imgresize($imgfile, $format) {
		$this->img['ok'] = true;
		//detect image format
#		$this->img["format"]=ereg_replace(".*\.(.*)$","\\1",$imgfile);
#		$this->img["format"]=strtoupper($this->img["format"]);
		if ($format == "image/pjpeg" || $format == "image/jpeg" ) {
			//JPEG
			$this->img["format"]="JPEG";
			$this->img["src"] = ImageCreateFromJPEG ($imgfile);
		} elseif ($format == "image/png" || $format == "image/x-png") {
			//PNG
			$this->img["format"]="PNG";
			$this->img["src"] = ImageCreateFromPNG ($imgfile);
# GIF IS UNFORTUNATELY NOT SUPPORTED BY ALL VERSIONS OF GD BECAUS OF PATENT ISSUES
		} elseif (function_exists("imagegif") && $format == "image/gif" ) {
			//GIF
			$this->img["format"]="GIF";
			$this->img["src"] = ImageCreateFromGIF ($imgfile);
		} elseif ($format == "image/wbmp" ) {
			//WBMP
			$this->img["format"]="WBMP";
			$this->img["src"] = ImageCreateFromWBMP ($imgfile);
		} else {
			//DEFAULT
			$this->img['ok'] = false;
		}
		if ($this->img['ok']) {
			@$this->img["width"] = imagesx($this->img["src"]);
			@$this->img["height"] = imagesy($this->img["src"]);
			//default quality jpeg
			$this->img["quality"]=75;
		}
	}

	function size_height($size=100)
	{
		//height
    	$this->img["height_thumb"]=$size;
    	@$this->img["width_thumb"] = ($this->img["height_thumb"]/$this->img["height"])*$this->img["width"];
	}

	function size_width($size=100)
	{
		//width
		$this->img["width_thumb"]=$size;
    	@$this->img["height_thumb"] = ($this->img["width_thumb"]/$this->img["width"])*$this->img["height"];
	}

	function size_auto($size=100)
	{
		//size
		if ($this->img["width"]>=$this->img["height"]) {
    		$this->img["width_thumb"]=$size;
    		@$this->img["height_thumb"] = ($this->img["width_thumb"]/$this->img["width"])*$this->img["height"];
		} else {
	    	$this->img["height_thumb"]=$size;
    		@$this->img["width_thumb"] = ($this->img["height_thumb"]/$this->img["height"])*$this->img["width"];
 		}
	}

	function jpeg_quality($quality=75)
	{
		//jpeg quality
		$this->img["quality"]=$quality;
	}

	function show()
	{
		//show thumb
		@Header("Content-Type: image/".$this->img["format"]);

		/* change ImageCreateTrueColor to ImageCreate if your GD not supported ImageCreateTrueColor function*/
		if (function_exists('imagecreatetruecolor')) {
			$this->img["des"] = ImageCreateTrueColor($this->img["width_thumb"],$this->img["height_thumb"]);
		} else {
			$this->img["des"] = ImageCreate($this->img["width_thumb"],$this->img["height_thumb"]);
		}
    	@imagecopyresized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["width_thumb"], $this->img["height_thumb"], $this->img["width"], $this->img["height"]);

		if ($this->img["format"]=="JPG" || $this->img["format"]=="JPEG") {
			//JPEG
			imageJPEG($this->img["des"],"",$this->img["quality"]);
		} elseif ($this->img["format"]=="PNG") {
			//PNG
			imagePNG($this->img["des"]);
		} elseif ($this->img["format"]=="GIF") {
			//GIF
			imageGIF($this->img["des"]);
		} elseif ($this->img["format"]=="WBMP") {
			//WBMP
			imageWBMP($this->img["des"]);
		}
	}

	function save($save="")
	{
		//save thumb
		if (empty($save)) $save=strtolower("./thumb.".$this->img["format"]);
		/* change ImageCreateTrueColor to ImageCreate if your GD not supported ImageCreateTrueColor function*/
		if (function_exists('imagecreatetruecolor')) {
			$this->img["des"] = ImageCreateTrueColor($this->img["width_thumb"],$this->img["height_thumb"]);
		} else {
			$this->img["des"] = ImageCreate($this->img["width_thumb"],$this->img["height_thumb"]);
		}
    	@imagecopyresized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["width_thumb"], $this->img["height_thumb"], $this->img["width"], $this->img["height"]);
		
		if ($this->img["format"]=="JPG" || $this->img["format"]=="JPEG") {
			//JPEG
			imageJPEG($this->img["des"],"$save",$this->img["quality"]);
		} elseif ($this->img["format"]=="PNG") {
			//PNG
			imagePNG($this->img["des"],"$save");
		} elseif ($this->img["format"]=="GIF") {
			//GIF
			imageGIF($this->img["des"],"$save");
		} elseif ($this->img["format"]=="WBMP") {
			//WBMP
			imageWBMP($this->img["des"],"$save");
		}
	}
}
?>
