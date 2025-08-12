<?php
/***************************************************************************

 broxx_template.class.php
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


class broxx_template
  {
  var $templ_file; // datei mit vorlage
  var $templ_content; // aktueller inhalt der vorlage
  var $loop_code; // Wiederholungscodes
  var $loop_tags; // Tags im Wiederholungstags
  var $loop_content; // gerenderter Wiederholungscode
  var $loop_pos_start;
  var $loop_pos_end;

  // ## start
  function broxx_template ($templ_file="")
    {
    $this->templ_file = $templ_file;
    // auslesen
    if (file_exists ($this->templ_file)) {
      $this->templ_content = "";
      $handle = fopen ($templ_file, "r");
      while (!feof($handle)) {
        $this->templ_content .= fgets($handle, 4096);
        }
      fclose ($handle);
      }
    }

  // ## ersetzt tag gegen inhalt
  function replace_tag ($tag, $content)
    {
    $this->templ_content = str_replace ($tag, $content, $this->templ_content);
    }

  // ## schleifen code ermitteln
  function loop_new ($name, $tag_start, $tag_end)
    {
    $return = 0;
    $pos_start = strpos ($this->templ_content, $tag_start);
    $pos_end = strpos ($this->templ_content, $tag_end) + strlen ($tag_end);
    if ($pos_start !== false && $pos_end !== false) {
      if ($pos_end > $pos_start) {
        $this -> loop_code[$name] = substr ($this->templ_content, $pos_start, $pos_end-$pos_start);
        $this -> loop_code[$name] = str_replace ($tag_start,"",$this -> loop_code[$name]);
        $this -> loop_code[$name] = str_replace ($tag_end,"",$this -> loop_code[$name]);
        $this -> loop_content[$name] = "";
        $this -> loop_pos_start[$name] = $pos_start;
        $this -> loop_pos_end[$name] = $pos_end;
        $return = 1;
        }
      }
    return $return;
    }

  // ## tags im wiederholungscode bekanntgeben
  function loop_register_tags ($name, $tags=array())
    {
    $return = 0;
    if (isset ($this -> loop_code[$name]) && is_array ($tags)) {
      $this -> loop_tags[$name] = $tags;
      $return = 1;
      }
    return $return;
    }

  // ## neue schleife hinzufuegen
  function loop_make ($name, $tagscontent=array())
    {
    $return = 0;
    if (isset ($this -> loop_code[$name]) && isset ($this -> loop_tags[$name]) && is_array ($tagscontent)) {
      if (count ($this -> loop_tags[$name]) == count ($tagscontent)) {
        $newloop = $this -> loop_code[$name];
        for ($n=0;$n<count($tagscontent);$n++) {
          $newloop = str_replace ($this->loop_tags[$name][$n], $tagscontent[$n], $newloop);
          }
        $this -> loop_content[$name] .= $newloop;
        $return = 1;
        }
      }
    return $return;
    }

  // ## gesamten schleifencode ausgeben
  function replace_loop ($name)
    {
    $return = 0;
    if (isset ($this->loop_content[$name]) && isset ($this->loop_pos_start[$name]) && isset ($this->loop_pos_end[$name])) {
      $this->templ_content = substr_replace ($this->templ_content, $this->loop_content[$name], $this->loop_pos_start[$name], $this->loop_pos_end[$name]-$this->loop_pos_start[$name]);
      $return = 1;
      }
    return $return;
    }

  }

?>