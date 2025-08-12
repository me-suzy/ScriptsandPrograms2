<?
//include($config['path']."idx_foot.html");

$saveRoot = $root;
$root = "../../../";
if(file_exists($file))include($root."index_footer.php");
//$file = $root."html/banner.html";
//if(file_exists($file))include($file);
$root = $saveRoot;


if($config["debug"]>0){
	echo '<pre><table width=100% bgcolor=silver><tr><td></td></tr></table>';	
	echo '$_POST:';var_dump($_POST);
	echo '<hr>$_GET:';var_dump($_GET);
	echo '<hr>$html:';var_dump($html);
	echo '<hr>$HTTP_POST_FILES:';var_dump($HTTP_POST_FILES);
	echo '<hr>$_SESSION:';var_dump($_SESSION);
	echo '</pre><table width=100% bgcolor=silver><tr><td></td></tr></table>';
}
?>