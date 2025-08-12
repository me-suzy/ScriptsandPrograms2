<?php
singletonQueue();
$html = getLayout();

$i = '</head>';
$o = '   <link href="'.WebPath().'/smart_css.php" type="text/css" rel="stylesheet" rel="StyleSheet">';
$o .= "\n</head>";
$html = str_replace($i,$o,$html);

echo $html;

?>