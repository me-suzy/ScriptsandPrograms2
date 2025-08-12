<?
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               classes/upload.class.php         #
# File purpose            Class for image uploads          #
# File created by         AzDG <support@azdg.com>          #
############################################################

class Upload {
    var $up_file;
	var $f;
    var $p;
	var $name;
	var $size;
	var $widht;
	var $height;
	var $type;
	var $max_size;
	var $path;
	var $directory;
	var $errors;
	var $move;
	
	function Upload($input_name,$size,$width,$height,$dir) {
	    $this->f = $input_name;
		$this->max_size = $size;
		$this->directory = $dir;
		$this->max_width = $width;
		$this->max_height = $height;
	}
	
  function do_upload() {
  //global $_FILES;
	    $this->up_file = $_FILES[$this->f]['tmp_name'];
		$this->name = $_FILES[$this->f]['name'];
		$this->size = $_FILES[$this->f]['size']/1000;
   	    $this->type = $_FILES[$this->f]['type'];
        if    (ereg(".gif$", $this->type)) $this->type="gif";
        elseif(ereg(".png$", $this->type)) $this->type="png";
        elseif(ereg(".jpg$", $this->type)||
               ereg(".jpeg$", $this->type)) $this->type="jpg";
        else $this->type="";
		$this->path = $this->directory;
        $this->width = $this->height = '';
        if(!empty($this->up_file)&&(C_HACK3)) {
        $this->p = getimagesize($this->up_file);
        $this->width = $this->p[0];  
        $this->height = $this->p[1];
        }  
		$this->errors = '';
		
	  if($this->move_file()) return true;
      else return false;
	}
	
	function getName() {
	  return $this->name;
	}
	
	function getMaxSize() {
	  return $this->max_size;
	}
	
	function getSize() {
	  return $this->size;
	}

	function getType() {
	  return $this->type;
	}
	
	function move_file() {
	  if($this->check_size() && $this->check_extension() && $this->check_uploaded() && $this->check_wh()) {
			$this->move = move_uploaded_file($this->up_file, $this->path.$this->type);
            chmod($this->path.$this->type, 0644);    

			return true;
		} else {
		  return false;
		}
	}
	
	function check_size() {
    global $w; 
	  if(($this->up_file != "")&&($this->size <= $this->max_size)) {
      return true;
		} else {
		  if($this->errors == '') {
              $tm=array($this->max_size,$this->size);
			  $this->errors = $this->errors.template($w[220],$tm);
			}
		  return false;
		}
	}
	
	function check_wh() { // Check width and height
    global $w; 
	  if(($this->up_file != "")&&($this->width <= $this->max_width)&&($this->height <= $this->max_height)) {
      return true;
		} else {
		  if($this->errors == '') {
              $tm=array($this->max_width,$this->max_height);
			  $this->errors = $this->errors.template($w[2201],$tm);
			}
		  return false;
		}
	}
	
	function check_uploaded() {
    global $w; 
	  if(is_uploaded_file($this->up_file)) {
			return true;
		} else {
		  if($this->errors == '') {
			  $this->errors = $this->errors.$w[1].'4';
			}
		  return false;
		}
	}
	
	function check_extension() {
    global $w; 
  		if ($this->type == "") {
  		    if($this->errors == '') {
  			   $this->errors = $this->errors.$w[222].$this->type;
  			}
  		  return false;
  		} else {
        return true;
		}
	}
	
	function getErrors() {
	  return $this->errors;
	}
	
}
?>