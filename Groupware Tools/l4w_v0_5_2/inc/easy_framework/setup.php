<?php
	$setupLock = "SETUPLOCK";
	if (!file_exists($setupLock)) {
		echo "Setup cannot be called as it has been executed already.";
		die ();     		
	}

	$path = dirname($_SERVER['PATH_TRANSLATED']);
	if (MyIsReadable  ($path."/config/config.inc.php", false, 1) == 0)
        include ($path."/easy_framework.inc.php");
	
	// --- Defaults ----------------------------------------------------
	$problems = false;
	
	// --- Everything ok? Then change ini file and go on with index.php
	isset ($_REQUEST['installation']) ? 
		$installation = $_REQUEST['installation'] :
		$installation = "";
		
    if ($installation == "complete") {
    	if (!unlink ($setupLock)) {
    		die ("Sorry, could not delete file ./$setupLock<br>Please delete manually!");
    	};
    	$link = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."/index.php";
    	header("Location: ".$link);
		die ();  
    }
    		
	function MyIsWriteable ($file, $do_echo = true, $rt = 1) {
		if (is_writeable ($file)) {
		    if ($do_echo)
    			echo "<tr><td class='green' align='left'>".$file." is writeable</td><td class='green' width='50' align='right'>ok</td></tr>";
			return 0;
		}
		else {
		    if ($do_echo)
    			echo "<tr><td class='red'>".$file." is not writeable or doesn't exist!</td><td class='red' align='right'> - </td></tr>";		
			return $rt;
		}
	}
	
	function MyIsReadable ($file, $do_echo = true, $rt = 1) {
		if (is_readable ($file)) {
            if ($do_echo)
    			echo "<tr><td class='green' align='left'>".$file." is readable</td><td class='green' width='50' align='right'>ok</td></tr>\n";
			return 0;
		}
		else {
		    if ($do_echo)
    			echo "<tr><td class='red'>".$file." is not readable or doesn't exist!</td><td class='red' align='right'> - </td></tr>\n";		
			return $rt;
		}
	}
?>
<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
       <title>Easy</title>
       <link rel='stylesheet' type='text/css' href='default.css'>
       <style type="text/css">
       		td.green {
       			FONT-FAMILY: Verdana, Geneva, Arial, Helvetica, sans-serif;
				font-size:12px; 
				color:green;
       		}
       		
       		td.red {
       			FONT-FAMILY: Verdana, Geneva, Arial, Helvetica, sans-serif;
				font-size:12px; 
				color:red;
				font-weight:bold;
       		}
       </style>
</head>
<body>
<h3>Easy - Evandor Application System</h3>
<h4>Documentation:</h4>
<a href="http://217.172.179.216/easy_framework/doc/index.html" target='_blank'>Easy Framework Documentation</a>

<h4>Checking directories and files:</h4>
<table>
<?php
	//$path = dirname($_SERVER['PATH_TRANSLATED']);
	clearstatcache ();
	$problems += MyIsReadable  ($path."/config/config.inc.php",true, 1);
	$problems += MyIsWriteable ($path."/SETUPLOCK",true, 2);
	
	if ($problems == 0) {
	    //if (!MyIsReadable  (DOC_ROOT,2)) 
	    //    $problems = true;
    }
?>
</table>

<?php if ($problems > 0) { ?>
<h4>There have been problems:</h4>
<font color='red'>
<?php
    if ($problems % 2 == 1) {
        echo "Copy the config.inc.php.default file to config.inc.php (in folder config) and
              set the required values for your installation.<br>";    
    }    
    $problems = $problems >> 1;

	if ($problems % 2 == 1) {
        echo "SETUPLOCK cannot be removed. Please remove this file manually.<br>";    
    }    
    $problems = $problems >> 1;
    
?>
</font>
<?php }  else { ?>
	<h4>Everything seems ok!</h4>
	Easy seems to be installed correctly.
	<br><br>
	To finish the installation, click <a href='setup.php?installation=complete'>here</a>
<?php } ?>

<br><br>
_____________________________<br>
<font face=Verdana size=1 color='#000066'>
<?php
$s = filectime("setup.php");
$chg = date("F j, Y H:i:s", $s);
echo("Created on " . $chg . "\n");

?>
</font>
</body>
</html>