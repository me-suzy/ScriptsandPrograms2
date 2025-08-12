<?php 
  /**
   * Extra config, load config settings from packages.
   * Needed to load classes before session started.
   **/

    $d = dir($cfg_project_directory."includes/");
    while($entry = $d->read()) {
        if (eregi("conf.inc.php$", $entry)) {
            include_once($entry);
        }
    }
    $d->close();


?>