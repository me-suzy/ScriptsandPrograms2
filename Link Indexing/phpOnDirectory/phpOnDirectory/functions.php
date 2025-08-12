<?php
# choose a banner

function unhtmlentities ($string)
{
   $trans_tbl = get_html_translation_table (HTML_ENTITIES);
   $trans_tbl = array_flip ($trans_tbl);
   $ret = strtr ($string, $trans_tbl);
   return preg_replace('/&#(\d+);/me',"chr('\\1')",$ret);    
}

function getTemplateByName($Name,$vars=array())
{
    $res= mysql_query("
                            SELECT * 
                            FROM dir_template 
                            WHERE template_name ='$Name'");
    echo mysql_error();                            
    $oRow = mysql_fetch_object($res);                                
    
    $allowed_vars = split(';',$oRow->template_variables );
    $body = unhtmlentities($oRow->template_value );

    foreach ($allowed_vars as $name) {
        if (!array_key_exists($name,$vars)) {
            $vars[$name] = '';
        }

        if (!empty($name)) $body = preg_replace("/\{$name\}/",$vars[$name],$body,-1);
    }
    return array ($oRow->template_type,$body);
}
function form_get($value){
    global $_POST,$_GET;
    if (isset($_POST[$value])) {
        $get_value=$_POST[$value];
    }
    elseif (isset($_GET[$value])) {
        $get_value=$_GET[$value];
    }
    else {
        $get_value="";
    }
    $get_value = (get_magic_quotes_gpc()==0 && set_magic_quotes_runtime()==0) ? addslashes($get_value) : $get_value;
    return $get_value;
}


