<?php
// Read the data.dat file for countdown info
include("js.php");
include("filereader.php");
include("functions.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" 
href="default.css" />
<title>Tizag Graphic Countdown</title>
</head>
<body style="text-align: center;">
<p>
<a href="index.php">Create/Edit Countdown</a> <a href="htmlcode.php">View Current Countdown & HTML Code</a>
</p>
<p>Before using this application you must read the readme file "readme.txt"</p>
<?php
echo '<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>';
?>
<h2>Tizag Countdown Creator</h2>
<div id="table">
<form name="countdown" enctype="multipart/form-data" action="process.php" method="post"> 

<?php
echoIntro();
echoTextInfo();
echoBorder();
echoDate();
echoPosition();
echoSubmit();
?>

</form>
</div>
</body>
</html>