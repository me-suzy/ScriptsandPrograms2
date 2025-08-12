<?php
 
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 
/**
  * Adds Slashes to sent value.
  *
  * @param string $buffer the text to add slashes to.
  *
  * @return string $buffer the converted string.
  */
function my_addslashes($what){
  
  if(is_array($what)){
     while (list($key, $val) = each($what)) {
       $what[$key] = my_addslashes($val);
     }
     return $what;
  } else {   	
   if (!(get_magic_quotes_gpc()))
    return addslashes($what);
   else
    return $what;
  }
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 
/**
  * Removes Slashes from sent value.
  *
  * @param string $buffer the text to remove slashes from.
  *
  * @return string $buffer the converted string.
  */
function my_stripslashes($what){
	if(is_array($what)){
     while (list($key, $val) = each($what)) {
       $what[$key] = my_stripslashes($val);
     }
     return $what;
  } else {    
    if (!(get_magic_quotes_gpc())) 
       return $what;
    else
       return stripslashes($what);
  }
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 
/**
  * Reads all values from the Request Object either adding slashes or 
  * Removing them based on preference.
  *
  * @param string $buffer the text to remove slashes from.
  *
  * @return string $buffer the converted string.
  */
function parse_incoming($addslashes=false){
   global $_REQUEST;

   if($addslashes)
      return my_addslashes($_REQUEST);               
   else 
      return my_stripslashes($_REQUEST);     
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
/**
  * Makes a SQL insert value "safe" by escaping quotes and optionally  
  * casting as a integer if needed.
  *
  * @param string $buffer the text to make sql safe.
  * @param bool $addslashes add slashes to string if not already done.
  * @param bool $numeric  cast value as int.
  * @param int $maxsize  max size of string 0 is unlimited.
  *
  * @return string $buffer the converted string.
  */
function filter_sql($what,$addslashes=true,$numeric=false,$maxsize=0){	 
	 
	 if($addslashes)
	   $what = addslashes($what);
	 else
	   $what = addslashes(stripslashes($what));
	   
	 if($numeric)
	   $what = intval($what);
	   
	 if($maxsize!=0)
	   $what = substr($what,0,$maxsize);
   
	 $what = str_replace("`","",$what);
	 
   // un-comment the following lines for compatability with Microsoft SQL server:
   // may cause problems with txt-db-api if uncommented...
	  //$what = str_replace("\'", "''", $what);
	  //$what = str_replace("\"", "\"\"", $what);
	 
	 return $what;	   
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
/**
  * Makes a string insert value "safe" by escaping HTML chars 
  *
  * @param string $buffer the text to make sql safe.
  *
  * @return string $buffer the converted string.
  */
function filter_html($what){	 
 
/*
Quite often, on HTML pages that are not encoded as UTF-8, and people 
write in not native encoding, some browser (for sure IExplorer) will 
send the different charset characters using HTML Entities, such as 
&#1073; for small russian 'b'.
htmlspecialchars() will convert this character to the entity, since 
it changes all & to &amp; 
What is being done here is all "&#" are being translated to (^)-(^)
and then translated back to "&#" after:
*/	
	 $what = str_replace( "&#"           , "(^)-(^)"        , $what ); 
	 $what = str_replace( "&"            , "&amp;"         , $what );
	 $what = str_replace( ">"            , "&gt;"          , $what );
	 $what = str_replace( "<"            , "&lt;"          , $what );
	 $what = str_replace( "\""           , "&quot;"        , $what );
	 $what = str_replace( "!"            , "&#33;"         , $what );
	 $what = str_replace( "'"            , "&#39;"         , $what ); 
	 $what = str_replace( "(^)-(^)"       , "&#"            , $what ); 
	 $what = str_replace("`"             ,""               , $what );
	 $what = ereg_replace("\n"        , "<br>"          , $what ); 
	 $what = ereg_replace("\r"        , ""              , $what ); 	 
	 
 $what = ereg_replace("à"            , "&agrave;"      , $what ); 
   $what = ereg_replace("á"            , "&aacute;"      , $what ); 
   $what = ereg_replace("è"            , "&egrave;"      , $what ); 
   $what = ereg_replace("é"            , "&eacute;"      , $what ); 
   $what = ereg_replace("ì"            , "&igrave;"      , $what ); 
   $what = ereg_replace("í"            , "&iacute;"      , $what ); 
   $what = ereg_replace("ò"            , "&ograve;"      , $what ); 
   $what = ereg_replace("ó"            , "&oacute;"      , $what ); 
   $what = ereg_replace("ù"            , "&ugrave;"      , $what ); 
   $what = ereg_replace("ú"            , "&uacute;"      , $what ); 
   $what = ereg_replace("ç"            , "&ccedil;"      , $what ); 
   $what = ereg_replace("ã"            , "&atilde;"      , $what ); 
   $what = ereg_replace("õ"            , "&otilde;"      , $what ); 
   $what = ereg_replace("ê"            , "&ecirc;"      , $what ); 
   $what = ereg_replace("â"            , "&acirc;"      , $what ); 
   $what = ereg_replace("ô"            , "&ocirc;"      , $what ); 

  
	 
	 return $what;	 	 
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
/**
  * Makes sure string is only alpha numeric. 
  *
  * @param string $buffer the text to make sql safe.
  *
  * @return string $buffer the converted string.
  */
function alphanumeric($string){
  $string =  ereg_Replace("([^a-zA-Z0-9])*", "", $string);
  return $string;
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
/**
  * Makes sure string is filterd before sending to system command 
  * no piping, passing possible environment variables ($),
  * seperate commands, nested execution, file redirection,
  * background processing, special commands (backspace, etc.), quotes
  * newlines, or some other special characters
  *
  * @param string $buffer the text to make cmd safe
  *
  * @return string $buffer the converted string.
  */
function filter_cmd($string){
	return escapeshellcmd($string);
  //$pattern = '/(;|\||`|>|<|&|^|"|'."\n|\r|'".'|{|}|[|]|\)|\()/i'; 
  //$string = preg_replace($pattern, '', $string);
  //$string = '"'.preg_replace('/\$/', '\\\$', $string).'"'; //make sure this is only interpretted as ONE argument
  //return $string;
  
}
?>