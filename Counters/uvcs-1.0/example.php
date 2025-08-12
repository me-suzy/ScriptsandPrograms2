<?php
include('./counter/counter.inc.php');
echo "<!-- using Urkburk Visitor Counting System from www.urkburk.com -->\n";
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>EXAMPLE</title>
</head>
<body>
<p>
You are visitor number <?= $UVCS['count'] ?>
</p>
</body>
</html>
