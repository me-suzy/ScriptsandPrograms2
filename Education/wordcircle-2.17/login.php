<?php

include "s_classes.php";

$visuals = new visuals();
$god = new god_obj();
$god->checkSecurity();
$visuals->showheader(false);

				echo('<script language="JavaScript" type="text/javascript">
					window.setTimeout("window.location.href=\'index.php\'",3000);
					</script>
					<table   align="center" width="400">
					<tr><td align="center"><br>
					<br>
					<strong>Please wait while we log you in.</strong>
					<br>
					<br>
					
					<img src="icon_circle.gif" width="200" height="40" alt=""><br>
					<br>
					<br>
					<br></td></tr>
					</table><br>
					<br>
					
					
					
					');
					
$visuals->showfooter();

