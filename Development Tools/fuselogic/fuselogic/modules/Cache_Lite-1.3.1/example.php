<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Cache Example</title>
</head>
<body>
Hi, I am cache example page.
I should be run faster becouse I am cached!
<?
if(include_once('class.stopwatch.php')){
    $time = &new stopwatch(FL_MICROTIME_START);
		echo 'Produced in '.$time->time().' sec';
}
?>
</body>
</html>
