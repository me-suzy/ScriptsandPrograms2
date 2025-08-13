<link rel="stylesheet" type="text/css" href="css/style.css" title="default" />
<?php
// Get file and display
@ $license = readfile('http://www.olate.com/legal/license_text.php');
	
// Error handling
if (!$license)
{
	echo '<p><b>Error:</b></p>Unable to open license file. The Olate server may be unavailable so please try again later.';
}
?>
