<?php

$nl = "\n";
echo '<p>Output of embedded script / Ausgabe eines eingebetteten Scripts:</p>';
echo '<p>';
echo 'Browser: '.$HTTP_USER_AGENT.'<br />'.$nl;
echo 'IP-Nummer: '.$REMOTE_ADDR.'<br />'.$nl;
if ( isset ($REMOTE_HOST) )
   echo 'Rechner-Name: '.$REMOTE_HOST.'<br />'.$nl;
echo 'SCRIPT_FILENAME: '.$SCRIPT_FILENAME.'<br />'.$nl;
echo 'SCRIPT_NAME: '.$SCRIPT_NAME.'<br />'.$nl;
echo 'QUERY_STRING: '.$QUERY_STRING.'<br />'.$nl;
echo 'PATH_INFO: '.$PATH_INFO.'<br />'.$nl;
echo 'PATH_TRANSLATED: '.$PATH_TRANSLATED.'<br />'.$nl;
echo 'Testvariable: '.$test . $nl;
echo '</p>'.$nl;
?>
