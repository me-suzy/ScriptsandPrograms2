<?php
/***************************************************************************

 broxx_db_mysql.class.php
 -------------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/
  
class broxx_db_mysql
  {
  var $Host;
  var $User;
  var $Pass;
  var $Database;
  var $Connection;
  var $errormsg;

  // ## start
  // mit uebergebenen variablen wird
  // die verbindung hergestellt - schlaegt verbindung fehl wird 0
  // zuruekgegeben
  function connect ($Host="",$User="",$Pass="",$Database="")
    {
    $ok = 0;
    $this->Host = $Host;
    $this->User = $User;
    $this->Pass = $Pass;
    $this->Database = $Database;
    if ($Database != "") {
      // connecting
      $this->Connection = @mysql_connect($Host, $User, $Pass);
      if (@mysql_select_db($Database, $this->Connection)) $ok = 1;
      else $this = "";
      }
    return $ok;
    }
  
  // ## verbindung trennen
  // mit funktionsaufruf wird verbindung getrennt und objekt
  // geloescht
  // rueckgabe: true/false
  function kill ()
    {
    $ok = 0;
    if (@mysql_close ($this->Connection)) {
      $ok = 1; $this = "";
      }
    return $ok;
    }
    
  // ## abfrage
  // funktion zum ausfuehrung einer query
  // rueckgabe: array mit den 2 werten: result und resultanzahl
  // bei abfragefehler wird die mysql meldung in ->errormsg gespeichert
  function read ($query)
    {
    $RESULT = 0; $RESULTnum = 0; $this->errormsg = "";
    $RESULT = @mysql_query ($query,$this->Connection);
    $RESULTnum = @mysql_num_rows ($RESULT);
    $this->errormsg = mysql_error ($this->Connection);
    return array ($RESULT, $RESULTnum);
    }
    
  // ## bearbeiten
  // funktion die anhand einer query eine aktion durchfuehrt (insert, update, delete)
  // rueckgabe: anzahl von der aktion betroffenen datensaetze
  // bei abfragefehler wird die mysql meldung in ->errormsg gespeichert
  function edit ($query)
    {
    $num = 0; $this->errormsg = "";
    $dbUPDATE = @mysql_query ($query);
    $num = mysql_affected_rows ();
    $this->errormsg = mysql_error ($this->Connection);
    return $num;
    }

  }

?>