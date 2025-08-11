<?php
require('./config.php');

// Set page header to XML
header('Content-Type: text/xml');

// Instantiate the trackback class
$trackback = pslNew('Trackback');

$ary = getRequestValue();

$error = '';
if($trackback->store($ary['section'], clean($_POST['title']), clean($_POST['excerpt']), clean($_POST['url']), clean($_POST['blog_name']), &$error)) {
   echo '<?xml version="1.0" encoding="iso-8859-1"?>
<response>
<error>0</error>
</response>';
} else {
   Header('HTTP/1.1 400 Bad Syntax');
   echo '<?xml version="1.0" encoding="iso-8859-1"?>
<response>
<error>1</error>
<message>'.$error.'</message>
</response>';
}
?>
