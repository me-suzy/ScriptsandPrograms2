<?PHP
/************************************************************************/
/* BCWB: Business Card Web Builder                                      */
/* ============================================                         */
/*                                                                      */
/* 	The author of this program code:                                    */
/*  Dmitry Sheiko (sheiko@cmsdevelopment.com)	                    	*/
/* 	Copyright by Dmitry Sheiko											*/
/* 	http://bcwb.cmsdevelopment.com     			                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if( !defined("SABLOTRON") )
{
	include_once("../config.inc.php");
	header( 'Content-type: text/xml' );
	
	if($language=="ru" or $language=="en") $default_language=$language;
	if(!$default_language) $default_language="ru";
	include_once('../lang/'.$default_language.'.inc.php');
	$root_path = preg_replace("/scripts\/xslt\.php$/is", "", $GLOBALS["SCRIPT_FILENAME"]);
	
	include($root_path.'include/startup/debug.inc.php');
	include($root_path.'include/startup/auth.inc.php');
	include($root_path.'include/startup/common_functions.inc.php');
	include($root_path.'include/lib/admin.lib.php');
	
	list($bcwb_admin->script_uri, $bcwb_admin->xslt_filename, $bcwb_admin->action) = split(";", $_GET["script_uri"]);
	$GLOBALS["action"] = $bcwb_admin->action;

}
else
{
	list($bcwb_admin->script_uri, $bcwb_admin->xslt_filename, $bcwb_admin->action) = split(";", preg_replace("/^xslt\.php\?script_uri=/", "", $xslt_file_name) );

}


$bcwb_admin->query_uri = eregi_replace("^/", "", $bcwb_admin->script_uri);
if(!$bcwb_admin->xslt_filename) $bcwb_admin->xslt_filename = "index.xsl";

$bcwb_admin->url_parse();
$bcwb_admin->get_xslt_list();

if( !$GLOBALS["authorized"] )
	$xslData = ( $bcwb_admin->admin_parse_content( $bcwb_admin->get_file_content($root_path."dcontent/".$bcwb_admin->xslt_filename) ) ) ;
elseif( $bcwb_admin->action=="tree" )
	$xslData = $bcwb_admin->admin_header_parse( $bcwb_admin->get_file_content ( $http_path."scripts/tree.xslt.php") ) ; 
else 
	$xslData = ( $bcwb_admin->admin_header_parse( $bcwb_admin->admin_parse_content( $bcwb_admin->get_file_content($root_path."dcontent/".$bcwb_admin->xslt_filename) ) ) );


if( !$GLOBALS["authorized"] ) $xslData = preg_replace( array("/<bcwb_header \/>/is", "/<bcwb_footer \/>/is"), array("", ""), $xslData );


			if(preg_match("/\<\?PHP/", $xslData))
				{
					$tags_array=array();
					preg_match_all("/\<\?PHP(.*?)\?\>/is", $xslData, $tags_array);
					if($tags_array[1])
					{
						foreach($tags_array[1] as $val)
						{ 
							ob_start();
							@eval($val);
							$cont_value = ob_get_contents();
							ob_end_clean();
							$xslData = str_replace("<?PHP".$val."?>", $cont_value, $xslData);
						}
					}
				} 


if( !defined("SABLOTRON") )  print $xslData;
?>