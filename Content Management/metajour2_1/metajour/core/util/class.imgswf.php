<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage util
 */

	class Image_base_funktions{
	
		function Cr_jpg($image){
		
			$this->image_array = @GetImageSize($image);
			if(!is_array($this->image_array)){
				$image_handle = $this->Make_error($image);
				}
			else{
				switch($this->image_array[2]){
					case 1:
						$image_handle = $this->Gif2jpg($image);
						break;
					case 2:
						$image_handle = $this->Makejpg($image);
						break;
					case 3:
						$image_handle = $this->Png2jpg($image);
						break;
					default:
						$image_handle = $this->Make_error($image);
						break;
					}
				}
			if($this->Check_prog($image_handle)=== true){
				$this->Jpg_prog2jpg($image_handle);
				}
			return $image_handle;
			}		
			
		function Check_prog($image_handle){
			if (imageinterlace($image_handle) == 1){
				return true;
				}
			else{
				return false;
				}
			}


		function Makejpg($image){
			
			$image_handle = @imagecreatefromjpeg($image);
			if (!$image_handle) {
				$image_handle = $this->Make_error($image);
				}
			return $image_handle;	
			}


		function Png2jpg($image){
		
			$image_handle = @imagecreatefrompng($image);
			if (!$image_handle) {
				$image_handle = $this->Make_error($image);
				}
			return $image_handle;	
			}


		function Jpg_prog2jpg($image_handle){

			imageinterlace($image_handle,0);
			}


		function Gif2jpg($image){

			$image_handle = @imagecreatefromgif($image);
			if (!$image_handle) {
				$image_handle = $this->Make_error($image);
				}
			return $image_handle;
			}


		function Make_error($image){
		
			/* script from an vic@zymsys.com, thanks */
			$image_handle = ImageCreate (150, 30);     
       			$bgc = ImageColorAllocate ($image_handle, 255, 255, 255);
	        	$tc  = ImageColorAllocate ($image_handle, 0, 0, 0);
    	    		ImageFilledRectangle ($image_handle, 0, 0, 150, 30, $bgc); 
        		/* Ausgabe einer Fehlermeldung */
        		ImageString($image_handle, 1, 5, 5, "Fehler beim Öffnen von: $image", $tc);
			$this->image_array[0] = 150;
			$this->image_array[1] = 30;
   			}
		}


	
	class Image2swf extends Image_base_funktions{
		var $image_array;

		
		function Main($image,$swf_name){
			
			$image_handle = $this->Cr_jpg($image);

		
			$this->Make_swf($image_handle,$swf_name);
			return true;
			}


		function Make_swf($image_handle,$swf_name){

			$temp_image_name = uniqid(time()).".jpg";
			imagejpeg ($image_handle, $temp_image_name, 100);
			ImageDestroy($image_handle);
			$s = new SWFShape();
  
  			/*if (!is_object(@new SWFBitmap($temp_image_name))){
  				echo "Konnte Temporäres Bild nicht anlegen / lesen";
  				}
  			else{
  				$b = new SWFBitmap($temp_image_name);
  				}*/

			$fp = fopen($temp_image_name,"r"); 
			$i = fread($fp,9999999); 
			$b = new SWFBitmap($i);
			fclose($fp);			

			$f = $s->addFill($b);
			
			$s->setRightFill($f);
			
			$s->drawLine($this->image_array[0], 0);
			$s->drawLine(0, $this->image_array[1]);
			$s->drawLine(-$this->image_array[0], 0);
			$s->drawLine(0, -$this->image_array[1]);
			
			$m = new SWFMovie();
			$m->setDimension($this->image_array[0], $this->image_array[1]);
			$m->add($s);			
			
			$m->save($swf_name);
			@unlink($temp_image_name);
		}

		}

?>