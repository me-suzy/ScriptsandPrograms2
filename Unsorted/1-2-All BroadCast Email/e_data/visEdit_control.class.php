<?PHP 

if (preg_match("/http:\/\//i", $visEdit_root)) die ("can't include external file");

include 'e_data/config/visEdit_control.config.php';
include 'e_data/class/toolbars.class.php';
include 'e_data/class/lang.class.php';

// instance counter (static)
$visEdit_wysiwyg_instCount = 0;

class visEdit_Wysiwyg {
  // controls name
  var $control_name;
  // value
  var $value;
  // holds control toolbar mode.
  var $mode; 
  // editor dimensions;
  var $height;
  var $width;
  // language object
  var $lang;
  // theme (skin)
  var $theme;
  // editor stylesheet
  var $css_stylesheet;
  // toolbar dropdown data
  var $dropdown_data;
  // toolbars
  var $toolbars;
  
  // constructor
  function visEdit_Wysiwyg($control_name='richeditor', $value='', $lang='', $mode = '',
              $theme='', $width='100%', $height='300px', $css_stylesheet='', $dropdown_data='')
  {
    global $visEdit_dir;
    global $visEdit_wysiwyg_instCount;
    global $visEdit_default_theme;
    global $visEdit_default_css_stylesheet;
    
    $visEdit_wysiwyg_instCount++;
    
    $this->control_name = $control_name;
    $this->value = $value;
    $this->width = $width;
    $this->height = $height;
    if ($css_stylesheet == '')
    {
      $this->css_stylesheet = $visEdit_default_css_stylesheet;
    }
    else
    {
      $this->css_stylesheet = $css_stylesheet;
    }
    $this->getLang($lang);
    if ($theme=='')
    {
      $this->theme = $visEdit_default_theme;
    }
    else
    {
      $this->theme = $theme;
    }
    $this->mode = $mode;
    $this->dropdown_data = $dropdown_data;
    $this->getToolbar();
  }

  // sets _mode variable and fills toolbar items array
  function setMode($value) {
    $this->mode = $value;
  }
  // returns _mode value
  function getMode() {
    return($this->mode);
  }

  // set value/get value
  function setValue($value) {
    $this->value = $value;
  }
  function getValue() {
    return($this->value);
  }

  // set height/get height
  function setHeight($value) {
    $this->height = $value;
  }
  function getHeight() {
    return($this->height);
  }

  // set/get width
  function setWidth($value) {
    $this->width = $value;
  }
  function getWidth() {
    return($this->width);
  }

  // set/get css_stylesheet
  function setCssStyleSheet($value) {
    $this->css_stylesheet = $value;
  }
  function getCssStyleSheet() {
    return($this->css_stylesheet);
  }
  
  // outputs css and javascript code include
  function getCssScript($inline = false)
  {
    // static method... use only once per page
    global $visEdit_dir;
    global $visEdit_inline_js;
    global $visEdit_root;
    global $visEdit_active_toolbar;

    $buf = '';
    if ($visEdit_inline_js)
    {
      // inline javascript
      echo "<script language='JavaScript'>\n";
      echo "<!--\n";
      echo "var visEdit_active_toolbar = ".($visEdit_active_toolbar?"true":"false").";\n";
      include($visEdit_root.'class/script.js.php');
      echo "//-->\n";
      echo "</script>\n";
    }
    else
    {
      // external javascript
      $buf = "<script language='JavaScript'>\n";
      $buf .= "<!--\n";
      $buf .= "var visEdit_active_toolbar = ".($visEdit_active_toolbar?"true":"false").";\n";
      $buf .= "//-->\n";
      $buf .= "</script>\n";
      $buf .= '<script language="JavaScript" src="'.$visEdit_dir.'visEdit_script.js.php"></script>'."\n\n";
    }
    return $buf;
  }
  
  // checks browser compatibility with the control
  function checkBrowser()
  {
    global $HTTP_SERVER_VARS;
    
    $browser = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
    // check if msie
    if (eregi("MSIE[^;]*",$browser,$msie))
    {
      // get version 
      if (eregi("[0-9]+\.[0-9]+",$msie[0],$version))
      {
        // check version
        if ((float)$version[0]>=5.5)
        {
          // finally check if it's not opera impersonating ie
          if (!eregi("opera",$browser))
          {
            return true;
          }
        }
      }
    }
    return false;
  }
  
  // load language data
  function getLang($lang='')
  {
    $this->lang = new visEdit_Lang($lang);
  }
  // load toolbars
  function getToolbar()
  {
   $this->toolbars = new visEdit_Toolbars($this->lang,$this->control_name,$this->mode,$this->theme,$this->dropdown_data);
  }
  
  // returns html for wysiwyg control
  function getHtml()
  {
    global $visEdit_dir;
    global $visEdit_wysiwyg_instCount;
    global $visEdit_active_toolbar;
    
    
    $n = $this->control_name;
    // todo: make more customizable

    $buf = '';
    if ($this->checkBrowser())
    {
      if ($visEdit_wysiwyg_instCount == 1)
      {
        $buf.= $this->getCssScript();
      }
      // theme based css file and javascript
      $buf.= '<script language="JavaScript" src="'.$visEdit_dir.'lib/themes/'.$this->theme.'/js/toolbar.js.php"></script>';
      $buf.= '<link rel="stylesheet" type="text/css" href="'.$visEdit_dir.'lib/themes/'.$this->theme.'/css/toolbar.css">';

      $buf.= '<table border="0" cellspacing="0" cellpadding="0" width="'.$this->getWidth().'">';
      $buf.= '<tr>';

      $buf .= '<td id="visEdit_'.$n.'_toolbar_top_design" class="visEdit_'.$this->theme.'_toolbar" colspan="3">';
      $buf.= $this->toolbars->get('top');
      $buf .= '</td>';

      $buf .= '<td id="visEdit_'.$n.'_toolbar_top_html" class="visEdit_'.$this->theme.'_toolbar" colspan="3" style="display : none;">';
      $buf.= $this->toolbars->get('top','html');
      $buf .= '</td>';
      
      $buf .= '</tr>';

      $buf.= '<tr>';

      $buf.= '<td id="visEdit_'.$n.'_toolbar_left_design" valign="top" class="visEdit_'.$this->theme.'_toolbar" >';
      $buf.= $this->toolbars->get('left');
      $buf .= '</td>';

      $buf.= '<td id="visEdit_'.$n.'_toolbar_left_html" valign="top" class="visEdit_'.$this->theme.'_toolbar" style="display : none;">';
      $buf.= $this->toolbars->get('left','html');
      $buf .= '</td>';
      
      $buf .= '<td align="left" valign="top" width="100%">';
      
      //$buf.= '<input type="hidden" id="'.$n.'" name="'.$n.'">';
      $buf.= '<textarea id="'.$n.'" name="'.$n.'" style="width:100%; height:'.$this->getHeight().'; display:none;" class="visEdit_'.$this->theme.'_editarea"></textarea>';
      $buf.= '<input type="hidden" id="visEdit_'.$n.'_editor_mode" name="visEdit_'.$n.'_editor_mode" value="design">';
      $buf.= '<input type="hidden" id="visEdit_'.$n.'_lang" value="'.$this->lang->lang.'">';
      $buf.= '<input type="hidden" id="visEdit_'.$n.'_theme" value="'.$this->theme.'">';
      $buf.= '<input type="hidden" id="visEdit_'.$n.'_borders" value="on">';

  	  $buf.= '<iframe id="'.$n.'_rEdit" style="width:100%; height:'.$this->getHeight().'; direction:'.$this->lang->getDir().';" onLoad="visEdit_editorInit(\''.$n.'\',\''.htmlspecialchars($this->getCssStyleSheet()).'\',\''.$this->lang->getDir().'\');" class="visEdit_'.$this->theme.'_editarea" frameborder="no" style="direction : "></iframe><br>';
      
      $buf.= "\n<script language=\"javascript\">\n<!--\n";
      
      $tmpstr = str_replace("\r\n","\n",$this->getValue());
      $tmpstr = str_replace("\r","\n",$tmpstr);
      $content = explode("\n",$tmpstr);
      $plus = "";
      foreach ($content as $line)
      {
        $buf.="setTimeout('document.all.".$n.".value ".$plus."=\"".str_replace('-->','@@END_COMMENT',str_replace('<!--','@@START_COMMENT',str_replace('"','&quot;',str_replace("'","\'",$line))))."\";',0);\n";
        $plus = "+";
      }

      $buf.="setTimeout('document.all.".$n.".value = document.all.".$n.".value.replace(/&quot;/g,\'\"\');',0);"."\n";
      $buf.="setTimeout('document.all.".$n.".value = document.all.".$n.".value.replace(\'@@START_COMMENT\',\'<!--\');',0);"."\n";
      $buf.="setTimeout('document.all.".$n.".value = document.all.".$n.".value.replace(\'@@END_COMMENT\',\'-->\');',0);"."\n";

//      $buf.='setTimeout("alert(document.all.'.$n.'.value);",0);'."\n";

//      $buf.='setTimeout("'.$n.'_rEdit.document.body.innerHTML += document.all.'.$n.'.value;",0);'."\n";
      
//  $buf.='setTimeout("visEdit_toggle_borders(\''.$n.'\',this[\''.$n.'_rEdit\'].document.body,null);",0);'."\n";

      $buf.= '//--></script>';

      $buf.= '</td>';
      
      $buf.= '<td id="visEdit_'.$n.'_toolbar_right_design" valign="top" class="visEdit_'.$this->theme.'_toolbar">';
      $buf.= $this->toolbars->get('right');
      $buf .= '</td>';

      $buf.= '<td id="visEdit_'.$n.'_toolbar_right_html" valign="top" class="visEdit_'.$this->theme.'_toolbar" style="display : none;">';
      $buf.= $this->toolbars->get('right','html');
      $buf .= '</td>';
      
      $buf.= '</tr>';
      $buf.= '<tr><td class="visEdit_'.$this->theme.'_toolbar"></td>';

      $buf .= '<td id="visEdit_'.$n.'_toolbar_bottom_design" class="visEdit_'.$this->theme.'_toolbar" width="100%">';
      $buf.= $this->toolbars->get('bottom');
      $buf .= '</td>';

      $buf .= '<td id="visEdit_'.$n.'_toolbar_bottom_html" class="visEdit_'.$this->theme.'_toolbar" width="100%" style="display : none;">';
      $buf.= $this->toolbars->get('bottom','html');
      $buf .= '</td>';
      
      $buf .= '<td class="visEdit_'.$this->theme.'_toolbar"></td></tr>';
      $buf.= '</table>';
    }
    else
    {
      // show simple text area
  	  $buf = '<textarea cols="20" rows="5" name="'.$n.'" style="width:'.$this->getWidth().'; height:'.$this->getHeight().'">'.htmlspecialchars($this->getValue()).'</textarea>';
    }
    return $buf;
  }

  // outputs wysiwyg control
  function show()
  {
    echo $this->getHtml();
  }

}
?>
