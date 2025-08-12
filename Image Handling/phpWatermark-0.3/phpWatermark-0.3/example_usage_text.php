<?php

// Load function
require("./phpWatermark.inc.php");

// Instantiate phpWatermark
// The only parameter currently required is the name
// of the image, which should get marked
$wm = new watermark("/path/to/image.png");

// Optionally specify the position of
// the watermark on the image
$wm->setPosition("RND");

// Optionally specify a fixed color for the text
$wm->setFixedColor("#FF00FF");
// or
$wm->setFixedColor(array(255, 0, 255));

// Add a watermark containing the string
// "phpWatermark" to the image specified above
$wm->addWatermark("phpWatermark", "TEXT");

// Fetch the marked image
$im = $wm->getMarkedImage();

// Output
header("Content-type: image/png");
imagepng($im);

?>
