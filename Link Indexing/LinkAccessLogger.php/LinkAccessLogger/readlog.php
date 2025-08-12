<?php
# set logfile to read from
$logfile = 'logfile.txt';

# load class
require('./classes/LinkAccessLogger.class.php');
?>
<html>
<body>


<pre>
<?php echo LinkAccessLogger::readLog($logfile); ?>
</pre>


</body>
</html>
