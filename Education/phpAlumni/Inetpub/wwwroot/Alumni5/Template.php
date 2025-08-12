<?php

//Template class @0-4F23B295

define("tpParse", false);
define("tpParseSafe", true);

class clsTemplate
{
  var $globals        = array();  // initial data:files and blocks
  var $blocks         = array();  // resulted data and variables
  var $templates_path = "./";     // path to templates
  var $parse_array    = array();  // array ready for parsing
  var $position       = 0;        // position in parse string
  var $length         = 0;        // length of parse string 
  var $block_path     = "";

  var  $delimiter      = "";       // delimit blocks, tags, and html's - 27
  var  $tag_sign       = "";       // tag sign - ¤
  var  $begin_block    = "";       // begin block sign - 16
  var  $end_block      = "";       // end block sign - 17

  function clsTemplate()
  {
    $this->templates_path = ".";
    $this->delimiter      = chr(27);   
    $this->tag_sign       = chr(15);  
    $this->begin_block    = chr(16);  
    $this->end_block      = chr(17);  
  }

  function LoadTemplate($filename, $block_name)
  {
    $file_path = $this->templates_path . "/" . $filename;
    if (file_exists($file_path))
    {
      $file_content = join("", file($file_path));

      $delimiter = $this->delimiter;
      $tag_sign = $this->tag_sign;
      $begin_block = $this->begin_block;
      $end_block = $this->end_block;
    
      // preparing file content for parsing
      $file_content = preg_replace("/(<!\-\-\s*begin\s*([\w\s]*\w+)\s*\-\->)/is",  $delimiter . $begin_block . $delimiter . "\\2" . $delimiter, $file_content);
      $file_content = preg_replace("/(<!\-\-\s*end\s*([\w\s]*\w+)\s*\-\->)/is",  $delimiter . $end_block . $delimiter . "\\2" . $delimiter, $file_content);
      $file_content = preg_replace("/(\{(\w+)\})/is", $delimiter . $tag_sign . $delimiter . "\\2" . $delimiter, $file_content);
      $this->parse_array = explode($delimiter, $file_content);
      $this->position = 0;
      $this->length = sizeof($this->parse_array);

      // begin parse
      $block_names[0] = $block_name;
      $this->set_block($block_names, false);
    }
  }

  function set_block($block_names, $is_subblock = true)
  {
    $block_level = sizeof($block_names);
    $block_name = "/" . join("/", $block_names);
    $block_array  = array();
    $block_number = 0; // begin from first block and go on
    $block_array[0] = 0;

    $tag_sign = $this->tag_sign;
    $begin_block = $this->begin_block;
    $end_block = $this->end_block;

    while ($this->position < $this->length) 
    {
      $element_array = $this->parse_array[$this->position];
      if($element_array == $tag_sign)
      {
        $block_number++;
        $block_array[$block_number] = $this->parse_array[$this->position + 1];
        $this->position += 2;
      }
      else if($element_array == $begin_block)
      {
        $block_number++; // increase block number by one
        $block_array[$block_number] = $block_name . "/" . $this->parse_array[$this->position + 1];
        $this->position += 2;
        $block_names[$block_level] = $this->parse_array[$this->position - 1];
        $this->set_block($block_names, true);
      }
      else if($element_array == $end_block && $is_subblock)
      {
        if($this->parse_array[$this->position + 1] == $block_names[$block_level - 1])
        {
          $block_array[0] = $block_number;
          $this->position += 2;
          $this->blocks[$block_name] = $block_array;
          return;
        }
        else
        {
          echo "Error in block: $block_name";
          exit;
        }
      }
      else
      {
        $block_number++;
        $block_array[$block_number] = $block_name . "#" . $block_number;
        $this->globals[$block_name . "#" . $block_number] = $element_array;
        $this->position++;
      }
    }
    $block_array[0] = $block_number;
    $this->blocks[$block_name] = $block_array;
  }

  function blockexists($block_name)
  {
    $block_name = $this->getname($block_name, true);
    return isset($this->blocks[$block_name]);
  }

  function setvar($key, $value)
  {
    $this->globals[$key] = $value;
  }

  function setblockvar($key, $value)
  {
    $key = $this->getname($key, true);
    $this->globals[$key] = $value;
  }

  function getvar($key)
  {
    $key = $this->getname($key, false);
    return $this->globals[$key];
  }

  function parse($block_name, $accumulate = true)
  {
    $this->globalparse($block_name, $accumulate, "");
  }

  function parseto($block_name, $accumulate, $parse_to)
  {
    $this->globalparse($block_name, $accumulate, $parse_to);
  }

  function globalparse($block_name, $accumulate = true, $parse_to = "", $output = false)
  {
    $block_name = $this->getname($block_name, true);

    if($parse_to == "") $parse_to = $block_name;
    else $parse_to = $this->getname($parse_to, true);
    $block_value = "";

    $block_array = $this->blocks[$block_name];
    $globals = $this->globals;
    $array_size = $block_array[0];
    for($i = 1; $i <= $array_size; $i++)
      $block_value .= isset($globals[$block_array[$i]]) ? $globals[$block_array[$i]] : "";
    $this->globals[$parse_to] = ($accumulate && isset($this->globals[$parse_to])) ? $this->globals[$parse_to] . $block_value : $block_value;
    if($output)
      echo $this->globals[$block_name];
  }

  function getname($array_key, $is_block)
  {

    if($is_block && strlen($array_key) && substr($array_key, 0, 1) != "/")
      $array_key = "/" . $array_key;
    if($is_block)
      $searching_array = $this->blocks;
    else
      $searching_array = $this->globals;

    if($is_block && strlen($this->block_path))
    {
      if(substr($this->block_path, 0, 1) != "/")
        $this->block_path = "/" . $this->block_path;
      if(substr($this->block_path, strlen($this->block_path) - 1, 1) == "/")
        $this->block_path = substr($this->block_path, 1, strlen($this->block_path) - 1);
      if(strlen($array_key))
        $array_key = $this->block_path . $array_key;
      else
        $array_key = $this->block_path;
    }
      
    if(!isset($searching_array[$array_key]))
    {
      reset($searching_array);
      while (list($key,) = each($searching_array)) 
      {
        $key_len = strlen($key);
        $array_key_len = strlen($array_key);
        if($key_len >= $array_key_len && substr($key, $key_len - $array_key_len, $array_key_len) == $array_key) 
        {
          $array_key = $key;
          break;
        }
      }
    }
    return $array_key;
  }

  function pparse($block_name, $accumulate = true)
  {
    $this->globalparse($block_name, $accumulate, "", true);
  }

  function print_block($block_name)
  {
    $block_name = $this->getname($block_name, true);
    reset($this->blocks[$block_name]);
    echo "<table border=\"1\">";
    while(list($key, $value) = each($this->blocks[$block_name])) 
    {
      $block_value = isset($this->globals[$value]) ? $this->globals[$value] : "";
      echo "<tr><th valign=top>$value</th><td>" . nl2br(htmlspecialchars($block_value)) . "</td></tr>";
    }
    echo "</table>";
  }

  function print_globals()
  {
    reset($this->globals);
    echo "<table border=\"1\">";
    while(list($key, $value) = each($this->globals)) 
      echo "<tr><th valign=top>$key</th><td>" . nl2br(htmlspecialchars($value)) . "</td></tr>";
    echo "</table>";
  }

}

/*//*/


//End Template class


?>
