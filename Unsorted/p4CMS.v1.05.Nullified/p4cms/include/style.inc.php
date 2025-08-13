<?

$d4style = array();

$style_file = file("style/style.ini");
for ($i=0; $i<=count($style_file)-1; $i++) {
	$style_split = explode("=", $style_file[$i]);
	$d4style[$style_split[0]] = $style_split[1];
}

function StyleSheet() {
?>
<link rel="stylesheet" href="style/style.css">
 <script language="javascript" src="include/common.js"></script>
<?
}

?>