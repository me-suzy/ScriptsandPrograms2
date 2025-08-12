<?php

include_once("header.php");
include_once("left.php");

// Processing templates

$tmpl = new Template ( "html/privacy.html" );

$lng['message'] = $lng[859];

$tmpl -> param ( 'lng', array ( $lng ) );
$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();


// Footer

include_once("right.php");
include_once("footer.php");

?>