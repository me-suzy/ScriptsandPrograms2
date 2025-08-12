<?PHP 
// ================================================
// Language class
// ================================================

class visEdit_Lang
{
  // current language
  var $lang;
  // accessors
  function setLang($value)
  {
    $this->lang = $value;
  }
  function getLang()
  {
    $this->lang = $value;
  }

  // variable to hold current language block
  var $block;
  // accessors
  function setBlock($value)
  {
    $this->block = $value;
  }
  function getBlock()
  {
    return $this->block;
  }
  
  // charset for the current language
  var $charset;
  // accessors
  function getCharset()
  {
    return $this->charset;
  }

  // text direction for the current language
  var $dir = 'ltr';
  // accessors
  function getDir()
  {
    return $this->dir;
  }
  
  // language data
  var $lang_data;
  // default language data
  var $default_lang_data;
  
  // constructor
  function visEdit_Lang($lang = '')
  {
    global $visEdit_default_lang;
    if ($lang == '')
    {
      $this->lang = $visEdit_default_lang;
    }
    else
    {
      $this->lang = $lang;
    }
    $this->loadData();
  }

  // load language data
  function loadData()
  {
    global $visEdit_dir;
    global $visEdit_root;
    global $visEdit_default_lang;
	$visEdit_root = __FILE__ ;
	$visEdit_root = str_replace('\\', '/', $visEdit_root);
	$visEdit_root = str_replace('class/lang.class.php', '', $visEdit_root);

    @include($visEdit_root.'/lib/lang/'.$this->lang.'/'.$this->lang.'_lang_data.inc.php');
    $this->charset = $visEdit_lang_charset;
    if (!empty($visEdit_lang_direction)) $this->dir = $visEdit_lang_direction;
    $this->lang_data = $visEdit_lang_data;
    unset($visEdit_lang_data);
    @include($visEdit_root.'/lib/lang/'.$visEdit_default_lang.'/'.$visEdit_default_lang.'_lang_data.inc.php');
    $this->default_lang_data = $visEdit_lang_data;
  }

  // return message
  function showMessage($message, $block='')
  {
    $_block = ($block == '')?$this->block:$block;
    if (!empty($this->lang_data[$_block][$message]))
    {
      // return message
      return $this->lang_data[$_block][$message];
    }
    else
    {
      // if message is not present in current language data 
      // return message from default language
      return $this->default_lang_data[$_block][$message];
    }
  }
  
  // shortcut for showMessage
  function m($message, $block='')
  {
    return $this->showMessage($message, $block);
  }
  
  // sets the root point for the data
  function setRoot($block = '')
  {
    // if no block passed -> reload data
    if ($block == '')
    {
      $this->loadData();
    }
    else
    {
      // "move pointer"
      $this->lang_data = $this->lang_data[$block];
      $this->default_lang_data = $this->default_lang_data[$block];
    }
  }
} // visEdit_Lang
?>

