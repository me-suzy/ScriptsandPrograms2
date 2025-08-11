<?
include($config['path']."idx_foot.html");
if($config["debug"]>0){
	echo '<pre><table width=100% bgcolor=silver><tr><td></td></tr></table>';	
	echo '$_POST:';var_dump($_POST);
	echo '<hr>$_GET:';var_dump($_GET);
	echo '<hr>$html:';var_dump($html);
	echo '<hr>$HTTP_POST_FILES:';var_dump($HTTP_POST_FILES);
	echo '</pre><table width=100% bgcolor=silver><tr><td></td></tr></table>';
}
?>