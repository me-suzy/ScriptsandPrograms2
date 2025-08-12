<?php
/*
cavemonkey50's Webcam Update Script
Author: Ronald Heft, Jr. (cavemonkey50.com)
Script Webpage: http://cavemonkey50.com/code/webcam_update_script
Last Updated: 11/03/05
Version 1.5

Purpose: To check if the webcam image was updated and display the appropriate information.
*/

// Configure the following variables:

$file = "cavecam.jpg"; // The name of the image
$dir = "/home/cavemon/public_html/cavecam/images/"; // The directory the image resides (using full server path)
$time = 60; // How long the webcam should be displayed before considered offline (in seconds)

// End variable configuration. Do not edit any PHP from beyond this point. Only edit HTML.

$filename = $dir . $file; // Assembles the filename

if (file_exists($filename)) { // Checks if the image exists
if (filemtime($filename) + $time >= time()) { // See if the webcam was updated
?>

<!-- Insert the HTML for when the webcam is active. -->

<?php } else { ?>

<!-- Insert the HTML for when the webcam is inactive. -->

<?php } } else { // If the webcam does not exist ?>

<!-- Insert the HTML for when the webcam image does not exist. -->

<?php } ?>