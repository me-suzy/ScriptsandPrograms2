<?php
/**************************************
 * phpdbform_image                    *
 **************************************
 * Image control                      *
 * Saves a image into the database    *
 *                                    *
 * Paulo Assis <paulo@phpdbform.com>  *
 * 2002 - 11 - 18                     *
 **************************************/

require_once("phpdbform/phpdbform_field.php");

class phpdbform_image extends phpdbform_field {

    function phpdbform_image( &$form, $field, $title, $size=0 )
    {
		$this->phpdbform_field( $form, $field, $title );
        $this->size = $size;
        $this->maxlength = 0;
		$this->cssclass = "fieldtextbox";
		$this->updatable = false;
		$form->add( $this );
    }

	function get_string()
	{
		if( strlen($this->onblur) ) $javascript = "onblur=\"{$this->onblur}\"";
		else $javascript="";
		if( !empty($this->title) ) $title = $this->title."<br>";
		else $title = "";
		return $title."<input type=\"file\" class=\"{$this->cssclass}\" name=\"{$this->key}\" size=20 $javascript {$this->tag_extra}>\n";
	}

    function process()
    {
		if( isset($_FILES[$this->key]["name"]) && !empty($_FILES[$this->key]["name"]) ) {
			if( $this->size > 0 ) if( $this->size < $_FILES[$this->key]["size"] ) {
//				print "size error";
				return;
			}
			$imsize= getimagesize($_FILES[$this->key]["tmp_name"]);
			// 0 - width; 1 - height
			// 2 - Image Type: 1 = GIF, 2 = JPG, 3 = PNG
			if( $imsize[2] < 1 || $imsize[2] > 3 ) {
//				print "image type not supported";
				return;
			}
			$fp = fopen( $_FILES[$this->key]["tmp_name"],"rb" );
			if($fp) {
				$this->value = fread($fp, $_FILES[$this->key]["size"]);
				fclose($fp);
				$this->updatable = true;
			} else {
//				print "Error opening file";
				return;
			}
        }
    }
}
