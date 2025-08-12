<?php

/******************************************
* File      :   Defines the 'styleeditor' related functions
* Project   :   Contenido
* Descr     :
*
* Author    :   Olaf Niemann
* Created   :   2003
* Modified  :   22.04.2003
*
* © four for business AG
******************************************/


function styleEdit($filename,$somecontent) {
    global $client, $cfg, $cfgClient;

    $path = $cfgClient[$client]["css"]["path"]; 
    if (is_writable($path.$filename)) {

        if (!$handle = fopen($path.$filename, "w+")) {
             print "Kann die Datei $filename nicht öffnen";
             exit;
        }

        if (!fwrite($handle, stripslashes($somecontent) )) {
            print "Kann in die Datei $filename nicht schreiben";
            exit;
        }

        fclose($handle);

    } else {
        print "Die Datei $filename ist nicht schreibbar";
    }


}

?>
