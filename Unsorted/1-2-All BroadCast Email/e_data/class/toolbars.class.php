<?PHP 
// ================================================
// Toolbars class
// ================================================

// toolbar item type constants
define("visEdit_TBI_IMAGE", "image");
define("visEdit_TBI_BUTTON", "button");
define("visEdit_TBI_DROPDOWN", "dropdown");

// toolbar item
class visEdit_TB_Item
{
  // name
  var $name;
  // language object
  var $lang;
  // editor name
  var $editor;
  // additional item data
  var $data;
  // toolbar theme
  var $theme;
  
  // get items html
  function get()
  {
    return $this->lang->m('title',$this->name);
  }
  
  // show item
  function show()
  {
    echo $this->get();
  }
  
  // constructor
  function visEdit_TB_Item($name, &$lang, $editor, $theme, $attributes='', $data='')
  {
    $this->name = $name;
    $this->lang = $lang;
    $this->editor = $editor;
    $this->theme = $theme;
    if (!is_array($data))
    {
      $this->data = array();
    }
    else
    {
      $this->data = $data;
    }
  }
} // visEdit_TB_Item

// toolbar image
class visEdit_TB_Image extends visEdit_TB_Item
{
  // override get
  function get()
  {
    global $visEdit_dir;
    
    if (!empty($this->name))
    {
      $buf = '<img id="visEdit_'.$this->editor.'_tb_'.$this->name.'" alt="'.$this->lang->m('title',$this->name).'" src="'.$visEdit_dir.'lib/themes/'.$this->theme.'/img/tb_'.$this->name.'.gif" '.$this->attributes.' unselectable="on">';
      return $buf;
    }
  }
} // visEdit_TB_Image

// toolbar button
class visEdit_TB_Button extends visEdit_TB_Item
{
  // override get
  function get()
  {
    global $visEdit_dir;
    $visEdit_dir = "e_data/";
    if (!empty($this->name))
    {
      $buf = '<img id="visEdit_'.$this->editor.'_tb_'.$this->name.'" alt="'.$this->lang->m('title',$this->name).'" src="'.$visEdit_dir.'lib/themes/'.$this->theme.'/img/tb_'.$this->name.'.gif" onClick="visEdit_'.$this->name.'_click(\''.$this->editor.'\',this)" class="visEdit_'.$this->theme.'_tb_out" onMouseOver="visEdit_'.$this->theme.'_bt_over(this)" onMouseOut="visEdit_'.$this->theme.'_bt_out(this)" onMouseDown="visEdit_'.$this->theme.'_bt_down(this)" onMouseUp="visEdit_'.$this->theme.'_bt_up(this)"  '.$this->attributes.' unselectable="on">';
      return $buf;
    }
  }
} // visEdit_TB_Button

// toolbar dropdown
class visEdit_TB_Dropdown extends visEdit_TB_Item
{
  // override get
  function get()
  {
    global $visEdit_dir;
    global $visEdit_theme;
    
    if (!empty($this->name))
    {
      $buf = '<select size="1" id="visEdit_'.$this->editor.'_tb_'.$this->name.'" name="visEdit_'.$this->editor.'_tb_'.$this->name.'" align="absmiddle" class="visEdit_'.$this->theme.'_tb_input" onchange="visEdit_'.$this->name.'_change(\''.$this->editor.'\',this)" '.$this->attributes.'>';
      $buf.='<option>'.$this->lang->m('title',$this->name).'</option>';
      while(list($value,$text) = each($this->data))
      {
        $buf.='<option value="'.$value.'">'.$text.'</option>';
      }
      $buf.= '</select>';
      return $buf;
    }
  }
} // visEdit_TB_Button

// toolbars
class visEdit_Toolbars
{
  // array of toolbar data
  var $toolbars;

  // toolbar mode (scheme)
  var $mode;
  
  // dropdown data
  var $dropdown_data;
  
  // accessors
  function setMode($value)
  {
    global $visEdit_dir;
    global $visEdit_root;
    global $visEdit_default_toolbars;
    
    if ($value == '')
    {
      $this->mode = $visEdit_default_toolbars;
    }
    else
    {
      $this->mode = $value;
    }
    if (!@include('e_data/lib/toolbars/'.$this->mode.'/'.$this->mode.'_toolbar_data.inc.php'))
    {
      // load default toolbar data
      @include('e_data/lib/toolbars/'.$visEdit_default_toolbars.'/'.$visEdit_default_toolbars.'_toolbar_data.inc.php');
    }
    $this->toolbars = $visEdit_toolbar_data;
  }
  
  // language object
  var $lang;
  
  // editor name
  var $editor;
  
  // toolbar theme
  var $theme;
  
  // constructor
  function visEdit_Toolbars(&$lang, $editor, $mode='', $theme='', $dropdown_data='')
  {
    global $visEdit_dropdown_data;
    
    $this->lang = $lang;
    $this->editor = $editor;
    $this->setMode($mode);
    $this->theme = $theme;
    if ($dropdown_data != '')
    {
      $this->dropdown_data = $dropdown_data;
    }
    else
    {
      $this->dropdown_data = $visEdit_dropdown_data;
    }
  }
  
  // get toolbar html for the specified position (top, left, right, bottom)
  function get($pos, $mode='design')
  {
    if (!empty($this->toolbars[$pos.'_'.$mode]))
    {
      if ($pos == 'top' || $pos == 'bottom')
      {
        // horizontal toolbar
        $tb_pos_start = '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
        $tb_pos_end = '</table>';
        $tb_item_sep = '';
      }
      else
      {
        // vertical toolbar
        $tb_pos_start = '<table border="0" cellpadding="0" cellspacing="0"><tr>';
        $tb_pos_end = '</tr></table>';
        $tb_item_sep = '<br>';
      }
      $buf = $tb_pos_start;
      while (list(,$tb) = each($this->toolbars[$pos.'_'.$mode]))
      {
        if ($pos == 'top' || $pos == 'bottom')
        {
          // horizontal toolbar
          $tb_start = '<tr><td align="'.$tb['settings']['align'].'" valign="'.$tb['settings']['valign'].'" class="visEdit_'.$this->theme.'_toolbar_'.$pos.'">';
          $tb_end = '</td></tr>';
        }
        else
        {
          // vertical toolbar
          $tb_start = '<td align="'.$tb['settings']['align'].'" valign="'.$tb['settings']['valign'].'" class="visEdit_'.$this->theme.'_toolbar_'.$pos.'">';
          $tb_end = '</td>';
        }
      
        $buf .= $tb_start;
        while (list(,$tbitem) = each($tb['data']))
        {
          $buf .= $this->getTbItem($tbitem['name'],$tbitem['type'],$tbitem['attributes'], $tbitem['data']) . $tb_item_sep;
        }
        $buf .= $tb_end;
      }
      $buf .= $tb_pos_end;
    }
    return $buf;
  } // get
  
  // returns toolbar item html based on name and type
  function getTbItem($name, $type, $attributes, $data)
  {
    switch($type)
    {
      case visEdit_TBI_IMAGE:
        $tbi = new visEdit_TB_Image($name, $this->lang, $this->editor, $this->theme, $attributes);
        $buf = $tbi->get();
        break;
      case visEdit_TBI_BUTTON:
        $tbi = new visEdit_TB_Button($name, $this->lang, $this->editor, $this->theme, $attributes);
        $buf = $tbi->get();
        break;
      case visEdit_TBI_DROPDOWN:
        if (!empty($this->dropdown_data[$name]))
        {
          $d_data = $this->dropdown_data[$name];
        }
        else
        {
          $d_data = $data;
        }
        $tbi = new visEdit_TB_Dropdown($name, $this->lang, $this->editor, $this->theme, $attributes, $d_data);
        $buf = $tbi->get();
        break;
      default:
        $tbi = new visEdit_TB_Item($name, $this->lang, $this->editor, $this->theme, $attributes);
        $buf = $tbi->get();
        break;
    }
    return $buf;
  } // getTbItem
  
  // output toolbar html for the specified position (top, left, right, bottom)
  function show($pos)
  {
    echo $this->get($pos);
  } // show
} // class visEdit_Toolbars
?>
