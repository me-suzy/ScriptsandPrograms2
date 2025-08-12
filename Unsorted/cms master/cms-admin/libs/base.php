<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/libs/base.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     

class Base {

    var $msg;

    function redirect($url) {
    	header("Location: $url");
    }
    
    function msg_js_show($msg) {
	    echo "<script>alert(\"$msg\");</script>";
    }
    
    function go_back() {
	    echo "<script>history.go(-1)</script>";
    }
    
    function msg_set($msg) {
	    $this->msg = $msg;
    }
    
    function msg_show($template) {
	    echo preg_replace("/MSG/", $this->msg, $template);
    }
    
    function convert_post_get($var_name) {
      global $ServerVars;
      $var_data = $ServerVars->POST["$var_name"];
      if (empty($var_data)) { $var_data = $ServerVars->GET["$var_name"]; }
      return $var_data;
    }

}

?>