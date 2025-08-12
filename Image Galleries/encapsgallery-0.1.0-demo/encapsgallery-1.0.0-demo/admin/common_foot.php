</div><br><table width="100%"><tr><td colspan="2" bgcolor="Green" height="10"></td></tr></table>

</body></html>
<?
//var_dump($config);
if($config["debug"]>0){
	echo '<pre><table width=100% bgcolor=silver><tr><td></td></tr></table>';	
	echo '$_POST:';var_dump($_POST);
	echo '<hr>$_GET:';var_dump($_GET);
	echo '<hr>$html:';var_dump($html);
	echo '<hr>$_SESSION:';var_dump($_SESSION);
	echo '</pre><table width=100% bgcolor=silver><tr><td></td></tr></table>';
}
?>
