<?php
#===================================
# trace.php
#===================================
# small function that
#------------------------------------------------




function trace($level,$text,$array1=NULL, $array2=NULL) {
	GLOBAL $trace_levels;
    #if(preg_match("/".$level."/",$trace_levels,$regs)) {
        $trace= debug_backtrace();
        $line=$trace[0]['line'];
        ereg("[^\\]*$",$trace[0]['file'],$matches);
        $file=$matches[0];
        if(is_array($text) || is_object($text)) {
            echo "<span class=notice><b>$level:</b>($file #$line)<pre>";
            echo "</pre></span>";
        }
        else {
		    echo "<span class=notice><b>$level</b></b>($file #$line)" .":". $text."<br>";
            if($array1) {
                echo "<pre>";
                print_r($array1);
                echo "</pre>";
            }
            if($array2) {
                echo "<pre>";
                print_r($array2);
                echo "</pre>";
            }
            echo "</span>";
    	}
    #}
}


function echo_get_vars() {
	global $HTTP_GET_VARS;
	echo "<div class=notice>";
	echo "<b>REFERRER-Vars...</b><br>";
	foreach($HTTP_GET_VARS as $key => $value) {
		echo "<b>$key=</b> '$value'<br>";
	}
	echo "</div>";
}

function echo_post_vars() {
	global $HTTP_POST_VARS;
	echo "<div class=notice>";
	echo "<b>REFERRER-Vars...</b><br>";
	foreach($HTTP_POST_VARS as $key => $value) {
		echo "<b>$key=</b> '$value'<br>";
	}
	echo "</div>";
}


#------------------------------------------------
# filter_vars
# - we don't want to trust any extern data therefore
#   strip weird size & remove weird characters
# - usage:
# 		$vars=array();
# 		filter_vars($HTTP_POST_VARS, $vars);
# 		filter_vars($HTTP_GET_VARS,  $vars);
#------------------------------------------------
function filter_vars(&$referred_vars, &$vars) {
	if(!isset($vars) || !isset($referred_vars) ) {
		trace('ERROR','filter_vars() called without proper parameters');
		return;
	}
	foreach(array_keys($referred_vars) as $key) {
		//--- skip too long variable (probably an hacking-attempt)
		if(strlen($key) > 16000) {
			trace('WARNING','Skipping too long key: "'.$key.'"');
			continue;
		}

		//--- skip variables with invalid name ----
		if(preg_match("/[\\'<>]/",$key)) {
			trace('WARNING','Skipping maleformed key: "'.$key.'"');
			continue;
		}

		$param= $referred_vars[$key];

		//--- strip html-code? ----
        while ($param != strip_tags($param)) {
           $param = strip_tags($param);
    	}

		$param= addslashes($param);             #@@@ this collides with real_escape_string
		$vars[$key]=  substr( $param,0,16000);	# strip length

		#if(preg_match("/[\\']/",$vars[$key])) {
		#	trace('WARNING','Skipping maleformed param: "'.$key.'"');
		#	die;
		#}
	}
}



function renderBacktrace($arr) {
    $buffer='';

    ### ignore empty array ###
    if(!count($arr)) {
        return false;
    }

    $buffer.= "<table class=backtrace>";

    ### write header ###
    $buffer.="<tr>";
    foreach($arr[0] as $key=>$value) {
        $buffer.="<th>$key</th>";
    }

    $buffer.="</tr>";

    ### write lines ###
    foreach($arr as $n) {
        $buffer.="<tr>";
        foreach($n as $key=>$value) {
            if(is_array($value)) {
                $buffer.='<td>';
                foreach($value as $no) {
                    if(is_object($no)) {
                        $buffer.=get_class($no);
                    }
                    else {
                        $buffer.=$no;
                    }
                    $buffer.=".<br>";
                }
                $buffer.="</td>";
            }
            else if(is_object($value)) {
                $buffer.='<td>';
                $buffer.=join("##<br>",$value);
                $buffer.="</td>";
            }
            else {
                $value= str_replace("c:\\programme\\Apache13\\Apache\\htdocs\\nod\\","",$value);
                $buffer.="<td>$value</td>";
            }
        }
        $buffer.="</tr>";
    }
    $buffer.= "</table>";
    return $buffer;
}



#
# access to http-vars
#
function get($key) {
 	global $vars;
    if(isset($vars[$key])) {
        return $vars[$key];
    }
    //--- use wildcards? ---
	else if(ereg("\*",$key)){
        $hash=array();
        foreach($vars as $ikey=>$ivalue) {
            if(ereg($key,$ikey)) {
                $hash[$ikey]=$ivalue;
            }
        }
        return($hash);
    }
    else {
        return;
    }
}

