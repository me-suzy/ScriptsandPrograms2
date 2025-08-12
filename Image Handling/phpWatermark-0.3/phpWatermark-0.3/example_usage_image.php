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

// Add a watermark containing the string
// "phpWatermark" to the image specified above
$wm->addWatermark("/tmp/logo.png", "IMAGE");

// Fetch the marked image
$im = $wm->getMarkedImage();

// Output
header("Content-type: image/png");
imagepng($im);

?>
