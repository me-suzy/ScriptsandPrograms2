<?
/*
* Project:      PHP-CSL (PHP Code Snippet Library)
* Version:      0.9
* Date:         2005/02/09 (y/m/d)
* Author:       Stuart Cochrane
* URL:          http://www.php-csl.com
* Sourceforge:  http://www.sourceforge.net/projects/php-csl/
*
* Read: reame.txt, install.txt, license.txt
*
*
*
*
*
*/

function file_open($filename) {
	global $snippet_root, $snippet_library;
	if($fp = @fopen($snippet_root.$snippet_library.$filename, "r")) {
		$fp = @fopen($snippet_root.$snippet_library.$filename, "r");
		$contents = fread($fp, 80000);
		fclose($fp);
		return $contents;
	} else {
		return false;
	}
}


function file_write($filename, $filecontent) {
	global $snippet_root, $snippet_library;
	if($fp = @fopen( $snippet_root.$snippet_library.$filename,"w+")) {
		$contents = fwrite($fp, $filecontent, 80000);
		fclose($fp);
		return true;
	} else {
		return false;
	}
}


function file_delete($cat, $snip) {
	global $snippet_root, $snippet_library, $snippet_ext_d, $snippet_ext_s;
	$file_s = $snippet_root.$snippet_library.$cat.".".$snip.$snippet_ext_s;
	$file_d = $snippet_root.$snippet_library.$cat.".".$snip.$snippet_ext_d;
    chmod($file_s, 0777);
    chmod($file_d, 0777);
        if(unlink($file_s) && unlink($file_d)) {
        // continue
        } else {
        	echo "ERROR TYPE: Library files may be locked !!!<br><Br>";
        	return false;
        }
    return true;
}

// ok - this could be taken to be a stupid function
// but under certain configs it could be useful
function file_exist($filename) {
	global $snippet_root, $snippet_library;
	if(file_exists($snippet_root.$snippet_library.$filename)) {
		return false;
	} else {
		return true;
	}
}


function dec_string($string) {
	return $string;
	// no longer using this poor conversion
	// return strrev(urldecode($string));
}


function enc_string($string) {
	return $string;
	// no longer using this poor conversion
	//return urlencode(strrev($string));
}

function default_lib_get() {
	global $snippet_root, $default_lib_file, $current_lib;
	$fp = fopen($snippet_root.$default_lib_file,"r"); 
	$data = trim(fread($fp, 80000)); 
	fclose( $fp );
	
	if(isset($_SESSION['current_lib'])) {
		$data = $_SESSION['current_lib'];
	} elseif(isset($current_lib)) {
		$data = $current_lib;
	}
	
	return $data;
}

function new_default() {
	global $snippet_root, $snippet_libs;
	// read the current libs file
		$fp = fopen($snippet_root.$snippet_libs,"r"); 
		$old_lib = fread($fp, 80000); 
		fclose( $fp ); 
		// build the new libs
		$old_lib = $new_lib.",".$old_lib;
		// split , pattern as libs divider
		$d_array = split (",", $old_lib);
		// remove duplicate entrys
		$data_array = array_unique($d_array);
		// order the libs in alphnumerical order asc
		sort($data_array);
		foreach ($data_array as $tmp) {
			$new_default = $tmp;
		}
		if(!libs_change($new_default)) {
			return false;
		}
		return true;
}

function libs_change($default_lib) {
	global $default_lib_file;
	// write the new default libs to file
	$fp = fopen($default_lib_file,"w+"); 
	if(fwrite($fp, $default_lib, 80000)) {
		fclose( $fp );
		return true;
	} else {
		return false;
	}
}

function lib_add($new_lib) {
	global $snippet_root, $snippet_libs, $snippet_cats_default;
	// remove invalid characters
	$reg = new regex;
	$reg->set_string($new_lib);
	$new_lib = $reg->get_string();
	$copy_lib = $new_lib;
	
	// create the physical folder
	if(!is_dir($new_lib)) {
		// ok, the directory doesnt already exist
		// create it!!
		if(mkdir($new_lib, 0777)) {
			// cool, folder made!!
			$cont = true;
		}
	}	
	if($cont) {
		// read the current libs file
		$fp = fopen($snippet_libs,"r"); 
		$old_lib = fread($fp, 80000); 
		fclose( $fp ); 
		// build the new libs
		$old_lib = $new_lib.",".$old_lib;
		// split , pattern as libs divider
		$d_array = split (",", $old_lib);
		// remove duplicate entrys
		$data_array = array_unique($d_array);
		// order the libs in alphnumerical order asc
		sort($data_array);
		$new_lib = "";
		foreach ($data_array as $tmp) {
			$new_lib .= $tmp.",";
		}
		// write the new cats to file
		$fp = fopen($snippet_libs,"w+"); 
		fwrite($fp, $new_lib, 80000); 
		fclose( $fp );
	}
	
	copy($snippet_cats_default, $copy_lib."/".$snippet_cats_default);
	
}

function libs_get() {
	global $snippet_libs;
	$fp = fopen($snippet_libs,"r"); 
	$data = fread($fp, 80000); 
	fclose( $fp );
	// split , pattern as libs divider
	$data_array = split (",", $data);
	$data_array = array_unique($data_array);
	return $data_array;
}

function libs_edit($old_lib, $new_lib) {
	global $snippet_libs;

	$reg = new regex;
	$reg->set_string($new_lib);
	$new_lib = $reg->get_string();
	
	// rename the physical file
	if(@rename($old_lib, $new_lib)) {
		$cont = true;
	} else {
		$cont = false;
	}
	
	if($cont) {
	// rename the library in the 'libs' text file
	$all_libs = libs_get();
	$final_lib = ",$new_lib,";
	foreach($all_libs as $tmp_libs) { 
		if(strlen($tmp_libs) >1) {
			if($tmp_libs == $old_lib) {
			// ok
			} else {
				$final_lib .= $tmp_libs.",";
			}
		}
	}
	// write the new libs to file
	$fp = fopen($snippet_libs,"w+"); 
	if(fwrite($fp, $final_lib, 80000)) {
		fclose( $fp );
		return true;
	} else {
		return false;
	}
	
	} // if
	
}

function libs_delete($lib) {
	global $snippet_cats_default, $snippet_libs, $cat, $snippet_ext_s, $snippet_ext_d;
	$all_libs = libs_get();
	$new_lib = ",";
	foreach($all_libs as $tmp_libs) { 
		if(strlen($tmp_libs) >1) {
			if($tmp_libs == $lib) {
			// do nowt
			} else {
				$new_lib .= $tmp_libs.",";
			}
		}
	}
	// write the new cats to file
	$fp = fopen($snippet_libs,"w+"); 
	fwrite($fp, $new_lib, 80000); 
	fclose( $fp );
	
	// remove directory and files
	deldir($lib);
}

function deldir($dir){
  $current_dir = opendir($dir);
  while($entryname = readdir($current_dir)){
     if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")){
       deldir("${dir}/${entryname}");
     }elseif($entryname != "." and $entryname!=".."){
       unlink("${dir}/${entryname}");
     }
  }
  closedir($current_dir);
  rmdir(${dir});
} 

function cats_add($new_cat) {
	global $snippet_root, $snippet_cats;
	// remove invalid characters
	$reg = new regex;
	$reg->set_string($new_cat);
	$new_cat = $reg->get_string();
	// read the current cats file
	$fp = fopen($snippet_root.$snippet_cats,"r"); 
	$old_cat = fread($fp, 80000); 
	fclose( $fp ); 
	// build the new cats
	$old_cat = $new_cat.",".$old_cat;
	// split , pattern as cats divider
	$d_array = split (",", $old_cat);
	// remove duplicate entrys
	$data_array = array_unique($d_array);
	// order the cats in alphnumerical order asc
	sort($data_array);
	$new_cat = "";
	foreach ($data_array as $tmp) {
		$new_cat .= $tmp.",";
	}
	// write the new cats to file
	$fp = fopen($snippet_root.$snippet_cats,"w+"); 
	fwrite($fp, $new_cat, 80000); 
	fclose( $fp );
	return true;
}


function cats_get() {
	global $snippet_root,$snippet_cats;
	$fp = fopen($snippet_root.$snippet_cats,"r"); 
	$data = fread($fp, 80000); 
	fclose( $fp );
	// split , pattern as cats divider
	$data_array = split (",", $data);
	$data_array = array_unique($data_array);
	return $data_array;
}


function cats_delete($cat) {
	global $snippet_cats, $snippet_root, $snippet_library;
	global $snippet_ext_s, $snippet_ext_d;
	$all_cats = cats_get();
	$new_cat = ",";
	foreach($all_cats as $tmp_cats) { 
	  
	  echo "$tmp_cats <br>";
	  
		if(strlen($tmp_cats) >1) {
			if($tmp_cats == $cat) {
			// do nowt
			} else {
				$new_cat .= $tmp_cats.",";
			}
		}
	}
		// write the new cats to file
		$fp = fopen($snippet_root.$snippet_cats,"w+"); 
		fwrite($fp, $new_cat, 80000); 
		fclose( $fp );

	$list_ob = new snips;
	$list_ob->snips_list_set($cat);
	$list = $list_ob->snips_list_get();

	foreach ($list as $f) {
		echo "file = ".$file_s."<br>";
		$file_s = $snippet_root.$snippet_library.$cat.".".$f.$snippet_ext_s;
		$file_d = $snippet_root.$snippet_library.$cat.".".$f.$snippet_ext_d;
		echo "file = ".$file_s."<br>";
		chmod($file_s, 0777);
		chmod($file_d, 0777);
		if(unlink($file_s) && unlink($file_d)) {
			// continue
		} else {
			echo "ERROR TYPE: Library files may be locked !!!<br><Br>";
			return false;
		}
	}
	return true;
}


function cats_edit($old_cat, $new_cat) {
	global $snippet_cats, $snippet_root, $snippet_library;
	global $snippet_ext_s, $snippet_ext_d;
	$reg = new regex;
	$reg->set_string($new_cat);
	$new_cat = $reg->get_string();
	// get a list of all snippets in this category
	$list_ob = new snips;
	$list_ob->snips_list_set($old_cat);
	$list = $list_ob->snips_list_get();
	// rename each one
	foreach ($list as $f) {
		$file_s_old = $snippet_root.$snippet_library.$old_cat.".".$f.$snippet_ext_s;
		$file_s_new = $snippet_root.$snippet_library.$new_cat.".".$f.$snippet_ext_s;
		$file_d_old = $snippet_root.$snippet_library.$old_cat.".".$f.$snippet_ext_d;
		$file_d_new = $snippet_root.$snippet_library.$new_cat.".".$f.$snippet_ext_d;
	
		@chmod($file_s_old, 0777);
		@chmod($file_d_old, 0777);
		@rename($file_s_old, $file_s_new);
		@rename($file_d_old, $file_d_new);
	}

		
	// rename the category in the 'cats' text file
	$all_cats = cats_get();
	$final_cat = ",$new_cat,";
	foreach($all_cats as $tmp_cats) { 
		if(strlen($tmp_cats) >1) {
			if($tmp_cats == $old_cat) {
			// ok
			} else {
				$final_cat .= $tmp_cats.",";
			}
		}
	}
		// write the new cats to file
		$fp = @fopen($snippet_root.$snippet_cats,"w+"); 
		if(fwrite($fp, $final_cat, 80000)) {
			fclose( $fp );
			return true;
		} else {
			return false;
		}
}

class REGEX {
	// var $out_string = "";

	function regex($out_string = "") {
		$this->out_string = "";
	}

	function set_string($string) {
		$string = ereg_replace("[^A-Za-z0-9\-\_] ", "", $string);
		$string = str_replace(" ", "_", $string);
		$string = str_replace(".", "", $string);
		$string = str_replace("/", "", $string);
		$string = stripslashes($string);
		$string = substr($string, 0, 60);
		$this->out_string = $string;
	}

	function get_string() {
		return $this->out_string;
	}
}


class SNIPS extends REGEX {

	function snips($list="") {
		$this->list = $list;
	}

	function snips_create($cats, $title, $syntax, $description) {
		global $snippet_library, $snippet_ext_s, $snippet_ext_d;
		global $snippet_s, $snippet_d, $snippet_root;
		$reg = new regex;
		$reg->set_string($title);
		$new_title = $reg->get_string();

		$new_syntax = $snippet_root.$snippet_library.$cats.".".$new_title.$snippet_ext_s;
		$new_desc   = $snippet_root.$snippet_library.$cats.".".$new_title.$snippet_ext_d;
		if(!file_exists($new_syntax) && !file_exists($new_desc)) {

			// create syntax file
			@copy($snippet_root.$snippet_s, $new_syntax);
			chmod($new_syntax, 0777);
			$this->file_write($cats.".".$new_title.$snippet_ext_s, stripslashes($syntax));

			// create description file
			@copy($snippet_root.$snippet_d, $new_desc);
			chmod($new_desc, 0777);
			$this->file_write($cats.".".$new_title.$snippet_ext_d, stripslashes($description));

			return true;
		} else {
			return false;
		}
	}

	function snips_list_set($cat) {
		global $snippet_library, $snippet_ext_s, $snippet_root;
		$dir = opendir($snippet_root.$snippet_library);
		$i=0;
		$expr = "(^$cat\.{1})+([a-z0-9\-\_])+($snippet_ext_s{1}$)";
		$exp2 = "(^$cat\.{1})";
		while ($lib = readdir($dir)) {
			 if (eregi($expr, $lib)) {
			 	$this->list[$i] = ereg_replace($exp2, "", $lib);
				$this->list[$i] = str_replace($snippet_ext_s, "", $this->list[$i]);
				$i++;
			 }
		}
		closedir($dir);
	}

	function snips_list_get() {
		return $this->list;
	}

	function file_write($filename, $filecontent) {
	global $snippet_root, $snippet_library;
	if($fp = @fopen( $snippet_root.$snippet_library.$filename,"w+")) {
		$filecontent = dec_string($filecontent);
		$contents = fwrite($fp, $filecontent, 80000);
		fclose($fp);
		return true;
	} else {
		return false;
	}
}

}

?>
