<?php

  /**
    * $Id: standard_inclusions.inc.php,v 1.3 2004/12/28 16:58:19 carsten Exp $
    *
    * standard inclusions for all pages
    * 
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package common
    */

	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");               // Date from past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");   // HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");                                     // HTTP/1.0

    include ("../../config/config.inc.php");
    include ("../../connect_database.php");

?>