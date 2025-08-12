<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
 * classes related to miscellenious string formatting and rendering
 *
 * included from: render_page.inc
 *
 *
 * @author: Thomas Mann
 * @uses:
 * @usedby: throughout everywhere
 *
 */






/**
* get theme-directory (without slashes) from current user-definition
*
* if undefined, return default-theme
*/
function getCurTheme() 
{
    global $auth;
    global $PH;
    global $g_theme_names;

    ### make sure theme is define ###
    if(isset($auth)
     && isset($auth->cur_user)
     && ($theme= $g_theme_names[$auth->cur_user->theme])) {
        return $theme;
    }
    
    ### if login-page use special theme ###
    if($PH->cur_page_id == 'loginForm') {
        return $g_theme_names[confGet('THEME_LOGIN')]; 
    }
    return $g_theme_names[confGet('THEME_DEFAULT')]; 
};



/**
 * Exception thrown related to rendering
 *
 * Prints message and some debug-output.
*/
class RenderException extends Exception
{
  public $backtrace=NULL;

  function __construct($message=false, $code=false)
  {
	$this->message="";
    if($message) {
        $this->message="<pre>$message";
    }
    $this->backtrace = debug_backtrace();
  }
}

/**
 * convert url to external link-tag (remove http:/ and reduced to reasonable length)
 *
 * add http:/ if missing
 */
function url2linkExtern($url, $show=NULL, $maxlen=20) {
    if(!preg_match("/^http:\/\//",$url)) {
        if(!$show) {
            $show= $url;
        }
        $url="http://$url";
    }
    else {
        if(!$show) {
            $show= preg_replace("/^https?:\/\//","",$url);
        }
    }
    if(strlen($show) > $maxlen) {
        $show=substr($show,0,$maxlen)."...";
    }
    return "<a class='extern' href='$url'>$show</a>";
}

/**
 * convert url to mail link-tag (remove mail:// and reduced to reasonable length)
*/
function url2linkMail($url,$show=false, $maxlen=32) {

    if(!preg_match("/^mailto:/",$url)) {
        if(!$show) {
            $show= $url;
        }
        $url="mailto:$url";
    }
    else {
        if(!$show) {
            $show= preg_replace("/^mailto?:/","",$url);
        }
    }
    if(strlen($show) > $maxlen) {
        $show=substr($show,0,$maxlen)."...";
    }
    return "<a class='mail' href='$url'>$show</a>";
}


#--- convert a string int months ---------
# NOTE this check is very loose to also fit for german
function string2month(&$string) {
    $mon=1;
    foreach(array('Jan','Feb','Ma?.r','Apr','Ma','Jun','Jul','Aug','Sep','O','Nov','Dec') as $m) {
        if(preg_match("/^$m/i",$string,$matches)) {
            return "$mon";      # TODO-printf-formated layout for 2 digits
        }
        ++$mon;
    }
    return false;
}

function mysqlDatetime2utc($datetime) {
    $out=array();
    if(preg_match("/\b(\d\d\d\d)[^\d](\d?\d)[^\d](\d?\d)\s+(\d\d)[^\d](\d\d)[^\d](\d\d)\b/",$datetime,$matches)) {
        if(count($matches)==7) {
            $out['year']=$matches[1];
            $out['mon']=$matches[2];
            $out['day']=$matches[3];
            $out['hour']=$matches[4];
            $out['min']=$matches[5];
            $out['sec']=$matches[6];
            return $out;
        }
    }
     return false;
}

#--------------------------------------------------------------
# get html-passed ids
#--------------------------------------------------------------
function getPassedIds($name=false,$wild=false)
{
    #if(!$name) {
    #    throw new myException ("getPassedIds requires at least a name");
    #}

    $ids=NULL;
    #--- first try original id ---
    if($name) {
        $id=get($name);
        $ids=array();
        if(isset($id)) {
            $ids[]=$id;
        }
    }
    #--- then use wildcards --
    if(!$wild) {
        $wild= strtolower($name)."s_*";     # eg: 'objectS_*'
    }
    $selected_items= get($wild);
    if($selected_items) {
        $keys=array_keys($selected_items);
        foreach($keys as $key) {
            if(preg_match("/_(\d+)_/",$key,$matches)) {
                $ids[]=$matches[1];
            }
        }
    }
    return $ids;
}

#--------------------------------------------------------------
# get exactly one html-passed id
# -  set PH->message if multiple selected
#--------------------------------------------------------------
function getOnePassedId($name=false,$wild=false, $abort_on_failure=true,$message=NULL)
{
    global $PH;

    if(!$message) {
        $message="ERROR: could not find id";
    }
    $ids= getPassedIds($name,$wild);
    if(!$ids) {
        if($abort_on_failure) {
            $PH->abortWarning($message,ERROR_NOTE);
            exit("aborting");
        }
        return;
    }
    else if(count($ids)>1) {
        $PH->message= 'NOTE: only one item expected. Took the first one...';
    }
    return $ids[0];
}

/**
* convert text to html-format (add line-breaks)
*/
function text2html ($text) {
    $text= preg_replace("/\n\s/","\n&nbsp;",$text);
    $text= preg_replace("/\n/","<br>",$text);
    return $text;
}

?>