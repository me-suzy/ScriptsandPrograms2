<?
/**
 * Source.php : Show source of a php script
 * 
 * You can use your own source.php file, param is for file to view is $_REQUEST['script']
 * /!\ Be carreful you must secure this page !!!! /!\
 * 
 * Don't forget to secure this script !
 * With something like that or whatever you want ! :)
 * 
 * if (!Rights::is_Admin())
 * {
 *  	redirect_login();
 * 		exit;
 * }
 * 
 * Now uses external view source script :
 * --> http://aidan.dotgeek.org/lib/?file=PHP_Highlight.php
 * 
 * @filesource
 * @package PHP_Debug
 * @see Debug
 * @since 10 Dec 2003
 */
 
require_once('../includes/setup.php');
 
/**
 * Lang Var
 */ 
$txtScriptNameNeeded = "Script Name needed";
$txtError = "ERROR";
$txtViewSource = "View Source of";
$txtWrongExt = "Only PHP or include script are allowed";
 
/**
 * Other Var
 */  

// Start HTML
print('
<html>
<head>
<style type="text/css" id="viewsourceStyleSheet">
    li { color:#7B68EE; font-family:courier new,arial; }
    .lineNumber { color:#7B68EE; font-family:courier new,arial; }
    .sourcecode {
        margin-top: 10px;
        background-color: #F5F5F5;
        border: 1px solid #BBB;
        padding: 6px;
        width: 100% 
    }
</style>  
</head>
<body bgcolor="#FFFFFF">');

/**
 * Output Source
 */ 
if (!$_REQUEST['script'])
{ 
   echo "<br /><b>== $txtError : $txtScriptNameNeeded</b><br />";
} 
else 
{ 
	$script = $_REQUEST['script'];
	print("<h3>== $txtViewSource : ". $_REQUEST['script'] ."</h3>");

   if (ereg("(\.php|\.inc|\.tpl|\.txt)$", $script))
   {
        print("<div class=\"sourcecode\">");

        $h = new PHP_Highlight;
        $h->loadFile($script);
        $h->toHtml(false, true, null, true);

        print('</div>');
   }
   else 
	   print("<b>== $txtError : $txtWrongExt</b>");
}

// Close HTML
print('</body></html>');
?>