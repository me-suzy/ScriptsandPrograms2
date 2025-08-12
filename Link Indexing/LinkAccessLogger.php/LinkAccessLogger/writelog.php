<?php
# set logfile to write to
$logfile = 'logfile.txt';

# load class
require('./classes/LinkAccessLogger.class.php');

# write to logfile
LinkAccessLogger::writeLog($logfile);
?>
<html>
<body>


The requested URL is outdated. The current one is:<br />
<a href="http://www.example.com/">http://www.example.com/</a><br />
Please update your references.


</body>
</html>
