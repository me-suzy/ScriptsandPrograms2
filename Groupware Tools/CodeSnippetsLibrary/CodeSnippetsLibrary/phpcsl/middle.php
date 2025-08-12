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

ini_set("highlight.bg",      "#FFFFFF"); // white
ini_set("highlight.comment", "#FF8000"); // orange
ini_set("highlight.default", "#0000BB"); // blue
ini_set("highlight.html",    "#000000"); // black
ini_set("highlight.keyword", "#007700"); // green
ini_set("highlight.string",  "#DD0000"); // red


// if page being called using iframe we need to include the config files!!
if(!function_exists('file_exist')) {
	include 'config.php';
	include 'functions.php';
	echo '<link rel="stylesheet" href="'.$snippet_theme.'/phpcsl.css" type="text/css">';
}

if(isset($_GET['show'])) {

$file = htmlentities($_GET['cat_select']).".".htmlentities($_GET['show']).$snippet_ext_s;
$file = $snippet_root.$snippet_library.$file;

$code = "unknown";

$fp = @fopen($file, "r");
$contents = fread($fp, 80000);
fclose($fp);

$src = $contents;
// is the code php or other?
if(substr_count($src,"<?") && substr_count($src,"?>")) { 
	$code = "php";
}

if($code == "php") {
ob_start();
highlight_file($file);
// $src = ereg_replace('<font color="([^"]*)">', '<span style="color: \1">', ob_get_contents());
// $src = str_replace(array('</font>', "\r", "\n"), array('</span>', '', ''), $src);
$src = ob_get_contents();
ob_end_clean();
} else {
	$src = 	htmlentities($src);

	$patterns = array("/\&quot;(.*?)\&quot;/", "/\\[(.*?)\]/", "/\&lt;(.*?)\&gt;/");
    $replacements = array("<span style=\"color:\#007700\">&quot;\\1&quot;</span>","[<span style=\"color:\#DD0000\">\\1</span>]", "<span style=\"color:#0000BB\">&lt;\\1&gt;</span>");
    $src = preg_replace($patterns,$replacements, $src);
    
    // just a sample of some keywords - will add to and improve
    $keywords = array(
    			"function ",
    			" if",
    			" else",
    			" elseif",
    			" while",
    			" for",
    			" switch",
    			" case",
    			" include",
    			" require",
    			" return",
    			" true",
    			" false",
    			" var",
    			" private");
    			
    foreach($keywords as $k){
    	$src = str_replace($k, " <span style=\"color:#0000BB\">$k</span>", $src);	
    }
    
}
?>
<table>
<tr>
<td class="num" valign="top" align="right"><pre><code>
<?
for($i=1; $i<=(substr_count($src,"<br />")+1); $i++) {
	echo "$i:\n";	
}
?>
</code></pre></td>
<td>
<pre><?=$src?></pre>
</td>
</tr>
</table>
	
	
	
<?	
} else { 

	switch ($_GET['op']) {
	
		case "cats":
		
			if(isset($_GET['act'])) {
			 switch ($_GET['act']) {
				
				case "add":
				 	require_once 'cats_add.php';
				break;
				
				case "edit":
					require_once 'cats_edit.php';
				break;
				
				case "delete":
				 require_once 'cats_delete.php';
				break;
			 }
			} else {
			 echo "no \$act variable was passed on the query string !!";
			}
			
		break; // end cats
		
		case "snips":
		
			if(isset($_GET['act'])) {
			 switch ($_GET['act']) {
				
				case "add":
				 	require_once 'snips_add.php';
				break;
				
				case "edit":
					require_once 'snips_edit.php';
				break;
				
				case "delete":
				 	require_once 'snips_delete.php';
				break;
			 }
			} else {
			  echo "no \$act variable was passed on the query string !!";
			}
		
		break; // end snips
		
		
		case "lib":
		
			if(isset($_GET['act'])) {
			 switch ($_GET['act']) {
				
				case "add":
				 	require_once 'lib_add.php';
				break;
				
				case "edit":
					require_once 'lib_edit.php';
				break;
				
				case "delete":
				 	require_once 'lib_delete.php';
				break;
				
				case "change":
				 	require_once 'lib_change.php';
				break;
				
				case "session":
				 	require_once 'lib_session.php';
				break;
			 }
			} else {
			  echo "no \$act variable was passed on the query string !!";
			}
		
		break; // end lib
		
		
		case "credits":

			require_once 'credits.php';
		
		break; // end credits
	
	} // end switch
} // end if/else
?>
