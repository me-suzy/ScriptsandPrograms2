<html>
<body>
<?php

require "URLHelper.php";
    
$url = 'http://www.phpclasses.org';

echo "HTTP Status for: &quot;$url&quot;<br>";
echo "The title is: &quot;" . URLHelper::getTitle($url) . "&quot;<br>";
echo "<br>";
echo nl2br(URLHelper::getHTTPHeader($url));
?>
</body>
</html>
