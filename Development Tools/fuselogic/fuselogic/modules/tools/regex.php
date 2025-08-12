<?php
$result = '<big>Result:</big><hr>';
if(!empty($_REQUEST['regex']) and !empty($_REQUEST['string'])){
    preg_match("/".(string)addslashes($_REQUEST['regex'])."/i",$_REQUEST['string'],$a_result);
    $count = count($a_result);
		$result2 = $_REQUEST['string'];
		if($count > 0){
		    for($i=0;$i<$count;$i++){
		        $result .= htmlspecialchars($a_result[$i]).'<hr>';
						$result2 = str_replace($a_result[$i],'<start_macth>'.htmlspecialchars($a_result[$i]).'</end_macth>',$result2);
		    }		
		}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Regular Expression</title>
</head>
<body>
<form action="<?php echo index().module().'/'.sub_module(); ?>" method="POST">
<input name="regex" value="<?php echo $_REQUEST['regex']; ?>"/>
<br>
<textarea rows="15" cols="90" name="string">
<?php echo $_REQUEST['string']; ?>
</textarea>
<br>
<input type="submit" value="match"/>
<input type="reset" value="reset"/>
</form>
<table>
<tr><td>
<?php echo $result; ?>
</td></tr>
<tr><td>
<?php //echo $result2; ?>
</td></tr>
</table>
</body>
</html>
