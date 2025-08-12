<html>
<head>
<title>Simp Counter Example</title>
</head>
<body>

<?php

error_reporting(E_ALL); 

$syspath = $_SERVER["DOCUMENT_ROOT"] . $_SERVER["PHP_SELF"];
$syspath = str_replace("example.php", "simpcounter.php", $syspath);

echo "Your system path to simpcounter.php is:<br><br> 
$syspath<br><br>";

$path = "<?php include '$syspath'; ?>";
?>
 
Add the following code to all your pages where you want the counter to show up:<br><br> 

<? echo htmlspecialchars($path); ?><br><br>

With your current setting the ouptut will be:<br><br>

<?php include $syspath; ?>

<br><br>

If you want to use the graphical counter, then you'll need to change the $imgdir and $usetextcounter settings in the simpcounter.php file.

If you have the settings in counter.php set to count all hits then try pressing refresh to see the counter increment.

<br><br>

You can visit the following link for help with Simp Counter 1.1<br>
<a href=http://speedycode.com/archives/17-Simp-Counter-1.1-Released.html>Simp Counter 1.1</a><br><br>

Visit Speedycode.com for more code and scripts:<br>
<a href=http://speedycode.com>SpeedyCode.Com</a>

</body>
</html>
