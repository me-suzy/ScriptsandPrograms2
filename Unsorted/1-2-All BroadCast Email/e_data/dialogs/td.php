<?PHP 
// ================================================
// Table cell properties dialog
// ================================================

// include wysiwyg config
include '../config/visEdit_control.config.php';
include '../class/lang.class.php';

$theme = empty($HTTP_GET_VARS['theme'])?$visEdit_default_theme:$HTTP_GET_VARS['theme'];
$theme_path = '../lib/themes/'.$theme.'/';

$l = new visEdit_Lang($HTTP_GET_VARS['lang']);
$l->setBlock('table_cell_prop');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<meta http-equiv="Pragma" content="no-cache">
  <title><?PHP echo $l->m('title')?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=<?PHP echo $l->getCharset()?>">
  <link rel="stylesheet" type="text/css" href="<?PHP echo $theme_path.'css/'?>dialog.css">
  <script language="javascript" src="utils.js"></script>
  <script language="javascript">
  <!--  
  function showColorPicker(curcolor) {
    var newcol = showModalDialog('colorpicker.php?theme=<?PHP echo $theme?>&lang=<?PHP echo $l->lang?>', curcolor, 
      'dialogHeight:250px; dialogWidth:366px; resizable:no; status:no');  
    try {
      td_prop.cbgcolor.value = newcol;
      td_prop.color_sample.style.backgroundColor = td_prop.cbgcolor.value;
    }
    catch (excp) {}
  }

  function Init() {
    var cProps = window.dialogArguments;
    if (cProps)
    {
      // set attribute values
      td_prop.cbgcolor.value = cProps.bgColor;
      td_prop.color_sample.style.backgroundColor = td_prop.cbgcolor.value;
      if (cProps.width) {
        if (!isNaN(cProps.width) || (cProps.width.substr(cProps.width.length-2,2).toLowerCase() == "px"))
        {
          // pixels
          if (!isNaN(cProps.width))
            td_prop.cwidth.value = cProps.width;
          else
            td_prop.cwidth.value = cProps.width.substr(0,cProps.width.length-2);
          td_prop.cwunits.options[0].selected = false;
          td_prop.cwunits.options[1].selected = true;
        }
        else
        {
          // percents
          td_prop.cwidth.value = cProps.width.substr(0,cProps.width.length-1);
          td_prop.cwunits.options[0].selected = true;
          td_prop.cwunits.options[1].selected = false;
        }
      }
      if (cProps.width) {
        if (!isNaN(cProps.height) || (cProps.height.substr(cProps.height.length-2,2).toLowerCase() == "px"))
        {
          // pixels
          if (!isNaN(cProps.height))
            td_prop.cheight.value = cProps.height;
          else
            td_prop.cheight.value = cProps.height.substr(0,cProps.height.length-2);
          td_prop.chunits.options[0].selected = false;
          td_prop.chunits.options[1].selected = true;
        }
        else
        {
          // percents
          td_prop.cheight.value = cProps.height.substr(0,cProps.height.length-1);
          td_prop.chunits.options[0].selected = true;
          td_prop.chunits.options[1].selected = false;
        }
      }
      
      setHAlign(cProps.align);
      setVAlign(cProps.vAlign);
      
      if (cProps.noWrap)
        td_prop.cnowrap.checked = true;
      
      
      if (cProps.styleOptions) {
        for (i=1; i<cProps.styleOptions.length; i++)
        {
          var oOption = document.createElement("OPTION");
          td_prop.ccssclass.add(oOption);
          oOption.innerText = cProps.styleOptions[i].innerText;
          oOption.value = cProps.styleOptions[i].value;
  
          if (cProps.className) {
            td_prop.ccssclass.value = cProps.className;
          }
        }
      }
    }
    resizeDialogToContent();
  }
  
  function validateParams()
  {
    // check width and height
    if (isNaN(parseInt(td_prop.cwidth.value)) && td_prop.cwidth.value != '')
    {
      alert('<?PHP echo $l->m('error').': '.$l->m('error_width_nan')?>');
      td_prop.cwidth.focus();
      return false;
    }
    if (isNaN(parseInt(td_prop.cheight.value)) && td_prop.cheight.value != '')
    {
      alert('<?PHP echo $l->m('error').': '.$l->m('error_height_nan')?>');
      td_prop.cheight.focus();
      return false;
    }
    
    return true;
  }
  
  function okClick() {
    // validate paramters
    if (validateParams())    
    {
      var cprops = {};
      cprops.align = (td_prop.chalign.value)?(td_prop.chalign.value):'';
      cprops.vAlign = (td_prop.cvalign.value)?(td_prop.cvalign.value):'';
      cprops.width = (td_prop.cwidth.value)?(td_prop.cwidth.value + td_prop.cwunits.value):'';
      cprops.height = (td_prop.cheight.value)?(td_prop.cheight.value + td_prop.chunits.value):'';
      cprops.bgColor = td_prop.cbgcolor.value;
      cprops.className = (td_prop.ccssclass.value != 'default')?td_prop.ccssclass.value:'';
      cprops.noWrap = (td_prop.cnowrap.checked)?true:false;

      window.returnValue = cprops;
      window.close();
    }
  }

  function cancelClick() {
    window.close();
  }
  
  function setSample()
  {
    try {
      td_prop.color_sample.style.backgroundColor = td_prop.cbgcolor.value;
    }
    catch (excp) {}
  }
  
  function setHAlign(alignment)
  {
    switch (alignment) {
      case "left":
        td_prop.ha_left.className = "align_on";
        td_prop.ha_center.className = "align_off";
        td_prop.ha_right.className = "align_off";
        break;
      case "center":
        td_prop.ha_left.className = "align_off";
        td_prop.ha_center.className = "align_on";
        td_prop.ha_right.className = "align_off";
        break;
      case "right":
        td_prop.ha_left.className = "align_off";
        td_prop.ha_center.className = "align_off";
        td_prop.ha_right.className = "align_on";
        break;
    }
    td_prop.chalign.value = alignment;
  }

  function setVAlign(alignment)
  {
    switch (alignment) {
      case "middle":
        td_prop.ha_middle.className = "align_on";
        td_prop.ha_baseline.className = "align_off";
        td_prop.ha_bottom.className = "align_off";
        td_prop.ha_top.className = "align_off";
        break;
      case "baseline":
        td_prop.ha_middle.className = "align_off";
        td_prop.ha_baseline.className = "align_on";
        td_prop.ha_bottom.className = "align_off";
        td_prop.ha_top.className = "align_off";
        break;
      case "bottom":
        td_prop.ha_middle.className = "align_off";
        td_prop.ha_baseline.className = "align_off";
        td_prop.ha_bottom.className = "align_on";
        td_prop.ha_top.className = "align_off";
        break;
      case "top":
        td_prop.ha_middle.className = "align_off";
        td_prop.ha_baseline.className = "align_off";
        td_prop.ha_bottom.className = "align_off";
        td_prop.ha_top.className = "align_on";
        break;
    }
    td_prop.cvalign.value = alignment;
  }
  //-->
  </script>
</head>

<body onLoad="Init()" dir="<?PHP echo $l->getDir();?>">
<table border="0" cellspacing="0" cellpadding="2" width="336">
<form name="td_prop">
<tr>
  <td colspan="2"><?PHP echo $l->m('horizontal_align')?>:</td>
  <td colspan="2" align="right"><input type="hidden" name="chalign">
  <img id="ha_left" src="<?PHP echo $theme_path.'img/'?>tb_left.gif" class="align_off" onClick="setHAlign('left');" alt="<?PHP echo $l->m('left')?>">
  <img id="ha_center" src="<?PHP echo $theme_path.'img/'?>tb_center.gif" class="align_off" onClick="setHAlign('center');" alt="<?PHP echo $l->m('center')?>">
  <img id="ha_right" src="<?PHP echo $theme_path.'img/'?>tb_right.gif" class="align_off" onClick="setHAlign('right');" alt="<?PHP echo $l->m('right')?>">
  </td>
</tr>
<tr>
  <td colspan="2"><?PHP echo $l->m('vertical_align')?>:</td>
  <td colspan="2" align="right"><input type="hidden" name="cvalign">
  <img id="ha_top" src="<?PHP echo $theme_path.'img/'?>tb_top.gif" class="align_off" onClick="setVAlign('top');" alt="<?PHP echo $l->m('top')?>">
  <img id="ha_middle" src="<?PHP echo $theme_path.'img/'?>tb_middle.gif" class="align_off" onClick="setVAlign('middle');" alt="<?PHP echo $l->m('middle')?>">
  <img id="ha_bottom" src="<?PHP echo $theme_path.'img/'?>tb_bottom.gif" class="align_off" onClick="setVAlign('bottom');" alt="<?PHP echo $l->m('bottom')?>">
  <img id="ha_baseline" src="<?PHP echo $theme_path.'img/'?>tb_baseline.gif" class="align_off" onClick="setVAlign('baseline');" alt="<?PHP echo $l->m('baseline')?>">
  </td>
</tr>
<tr>
  <td><?PHP echo $l->m('width')?>:</td>
  <td nowrap>
    <input type="text" name="cwidth" size="3" maxlenght="3" class="input_small">
    <select size="1" name="cwunits" class="input">
      <option value="%">%</option>
      <option value="px">px</option>
    </select>
  </td>
  <td><?PHP echo $l->m('height')?>:</td>
  <td nowrap>
    <input type="text" name="cheight" size="3" maxlenght="3" class="input_small">
    <select size="1" name="chunits" class="input">
      <option value="%">%</option>
      <option value="px">px</option>
    </select>
  </td>
</tr>
<tr>
  <td nowrap><?PHP echo $l->m('css_class')?>:</td>
  <td nowrap colspan="3">
    <select id="ccssclass" name="ccssclass" size="1" class="input">
    </select>
  </td>
</tr>
<tr>
  <td nowrap><?PHP echo $l->m('no_wrap')?>:</td>
  <td nowrap>
    <input type="checkbox" name="cnowrap">
  </td>
  <td colspan="2">&nbsp;</td>
</tr>
<tr>
  <td colspan="4"><?PHP echo $l->m('bg_color')?>: <img src="spacer.gif" id="color_sample" border="1" width="30" height="18" align="absbottom">&nbsp;<input type="text" name="cbgcolor" size="7" maxlenght="7" class="input_color" onKeyUp="setSample()">&nbsp;
  <img src="<?PHP echo $theme_path.'img/'?>tb_colorpicker.gif" border="0" onClick="showColorPicker(cbgcolor.value)" align="absbottom">
  </td>
</tr>
<tr>
<td colspan="4" nowrap>
<hr width="100%">
</td>
</tr>
<tr>
<td colspan="4" align="right" valign="bottom" nowrap>
<input type="button" value="<?PHP echo $l->m('ok')?>" onClick="okClick()" class="bt">
<input type="button" value="<?PHP echo $l->m('cancel')?>" onClick="cancelClick()" class="bt">
</td>
</tr>
</form>
</table>

</body>
</html>
