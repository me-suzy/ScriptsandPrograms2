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

$xmlData = $output;


$xh = xslt_create();

    $arguments = array(
        '/_xml' => trim($xmlData),
        '/_xsl' => trim($xslData)
    ); 

	@xslt_set_encoding($xh,$GLOBALS["default_charset"]);
    $result = @xslt_process($xh, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments);
    

if ($result) {

	if(preg_match("/\.xsl/", $custom_template)) 	{
					$result = str_replace('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">','', $result);
					$result = preg_replace('/<bcwb .*?form=\"start\"><\/bcwb>/','', $result);
					$result = preg_replace('/<bcwb .*?form=\"finish\"><\/bcwb>/','', $result);
					$result = '<?xml version="1.0" encoding="UTF-8"?>'.$result;
	}


    print ($result);
    }
else 
{
	$url = preg_replace(array("/^\//","/\/$/"),array("",""), $GLOBALS["SCRIPT_URI"]);
	$url = preg_replace("/^(.*?)\?/is", "\\1", $url);
	
	
	print '
	
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=WINDOWS-1251">
    <title>XSLT-error</title>
  </head>
  <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0">
    <base href="'.$http_path.'">
    <style>
BODY {	padding : 0px 0px 0px 0px; margin : 0px 0px 0px 0px; }
TD.adminarea, A.adminarea, A.adminarea:visited, A.adminarea:active, A.adminarea:link {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #F5DBDB; padding-bottom : 5px; text-decoration : none; }
A.adminarea:hover { text-decoration : none; color: #FFFFFF; }
INPUT.adminarea, SELECT.adminarea {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: black; background-color: #F5D6D5; }

INPUT.adminarea_btn { BACKGROUND-COLOR: #761715; 
BORDER-BOTTOM: #DE6867 1px solid; 
BORDER-LEFT: #DE6867 1px solid; 
BORDER-RIGHT: #DE6867 1px solid; 
BORDER-TOP: #DE6867 1px solid; FONT-SIZE: 10px; color: #F5DBDB; font-weight : bold;
PADDING-LEFT: 5px; PADDING-RIGHT: 5px; TEXT-ALIGN: center	}
DIV.cont_btn { font-family: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 10px;
background-color:menu;overflow:auto; border-width:1px; border-style:solid;border-color:threeddarkshadow white white threeddarkshadow;
}
SELECT.cont_btn, INPUT.cont_btn { font-family: Verdana, Arial, Helvetica, sans-serif; FONT-SIZE: 10px; }

H1.sys {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 18px; color: black;  }
TD.sys {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: black; text-decoration : none; }
A.sys, A.sys:visited, A.sys:active, A.sys:link {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #731410; text-decoration : none; }
A.sys:hover { color: #CE2C31; }
DL { margin-left: 15px; }

</style>	
<script>
function helpdesk(pointer)
{
	window.open(\'http://bcwb.sheiko.rg/scripts/helpdesk.xml.php?pointer=\'+pointer, \'displayWindow\',\'width=250,height=200,status=no,toolbar=no,menubar=no, scrollbars=auto, resizable=yes\'); 
	return false;
}
</script>
	
	
<LINK REL="stylesheet" TYPE="text/css" HREF="'.$GLOBALS["http_path"].'system/default.css.php" TITLE="Style" />
<title>XSLT-error</title>


<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#D9D9D9">
	<tr>	
		<td onclick="return helpdesk(\'Info\')" style="CURSOR: hand"><IMG SRC="'.$GLOBALS["http_path"].'system/install_logo2.gif" WIDTH="117" HEIGHT="51" ALT="BCWB v2.0." /></td>
		<td width="100%" valign="middle" background="'.$GLOBALS["http_path"].'system/install_bg.gif"> 
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td class="panel_title">&#xA0;XSLT-ERROR</td>
				
				</tr>
			</table>
		</td>
	</tr>
</table>	
	
	
<LINK REL="stylesheet" TYPE="text/css" HREF="'.$GLOBALS["http_path"].'system/default.css.php" TITLE="Style" />	
	
      <table width="100%" border="0" cellspacing="0" cellpadding="10" >
        <tr>
          <td class="install">	
	';
	
	if(defined("TEMPLATECHANGED"))
	print "<br /><h1>BCWB Report</h1>Changed XSL-template containing errors <br />
    Your actions:<br/><ul>
    	<li><a  href=\"".$GLOBALS["http_path"].$url."/?action=edittemplate\">Repair template</a></li>
    	</ul>";
	else 
    print "<br /><h1>BCWB Report</h1>Convertor XHTML could not process the page contents: (<span class=\"alert\">/dcontent/".$this->filename."</span>) <br />
    Your actions:<br/><ul>
        <li><a  style=\"text-decoration: underline; cursor: hand;\" onclick=\"return history.back(1)\">Return to editing the current page</a></li>
    	<li><a  href=\"".$GLOBALS["http_path"].$url."/?action=editxml\">Edit page code</a></li>
    	<li><a  href=\"".$GLOBALS["http_path"].$url."/?action=repair\">Repair (clean) page</a></li>
        <li><a  href=\"".$GLOBALS["http_path"].$url."/?action=clean\">Delete page</a></li>
    	</ul>";
    
    print "<br /><h1>SABLOTRON Report</h1>\nXHTML Parser<br />\n";
    print ("\tError number: " . xslt_errno($xh) . "<br />\n");
    print ("\tError string: " . xslt_error($xh) . "<br />\n");
    print '</td></tr></table>';
    exit;
}
?>