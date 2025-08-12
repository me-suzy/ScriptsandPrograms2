<?PHP 
// ================================================
// Image properties dialog
// ================================================

// include wysiwyg config
include '../config/visEdit_control.config.php';
include '../class/lang.class.php';

$theme = empty($HTTP_GET_VARS['theme'])?$visEdit_default_theme:$HTTP_GET_VARS['theme'];
$theme_path = '../lib/themes/'.$theme.'/';

$l = new visEdit_Lang($HTTP_GET_VARS['lang']);
$l->setBlock('image_prop');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
  <title><?PHP echo $l->m('title')?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=<?PHP echo $l->getCharset()?>">
  <link rel="stylesheet" type="text/css" href="<?PHP echo $theme_path.'css/'?>dialog.css">
  <script language="javascript" src="utils.js"></script>
  
  <script language="javascript">
  <!--  
  function Init() {
    var iProps = window.dialogArguments;
    if (iProps)
    {
      // set attribute values
      if (iProps.width) {
        img_prop.cwidth.value = iProps.width;
      }
      if (iProps.height) {
        img_prop.cheight.value = iProps.height;
      }
      
      setAlign(iProps.align);
      
      if (iProps.src) {
        img_prop.csrc.value = iProps.src;
      }
      if (iProps.alt) {
        img_prop.calt.value = iProps.alt;
      }
      if (iProps.border) {
        img_prop.cborder.value = iProps.border;
      }
      if (iProps.hspace) {
        img_prop.chspace.value = iProps.hspace;
      }
      if (iProps.vspace) {
        img_prop.cvspace.value = iProps.vspace;
      }
    }
    resizeDialogToContent();
  }
  
  function validateParams()
  {
    // check width and height
    if (isNaN(parseInt(img_prop.cwidth.value)) && img_prop.cwidth.value != '')
    {
      alert('<?PHP echo $l->m('error').': '.$l->m('error_width_nan')?>');
      img_prop.cwidth.focus();
      return false;
    }
    if (isNaN(parseInt(img_prop.cheight.value)) && img_prop.cheight.value != '')
    {
      alert('<?PHP echo $l->m('error').': '.$l->m('error_height_nan')?>');
      img_prop.cheight.focus();
      return false;
    }
    if (isNaN(parseInt(img_prop.cborder.value)) && img_prop.cborder.value != '')
    {
      alert('<?PHP echo $l->m('error').': '.$l->m('error_border_nan')?>');
      img_prop.cborder.focus();
      return false;
    }
    if (isNaN(parseInt(img_prop.chspace.value)) && img_prop.chspace.value != '')
    {
      alert('<?PHP echo $l->m('error').': '.$l->m('error_hspace_nan')?>');
      img_prop.chspace.focus();
      return false;
    }
    if (isNaN(parseInt(img_prop.cvspace.value)) && img_prop.cvspace.value != '')
    {
      alert('<?PHP echo $l->m('error').': '.$l->m('error_vspace_nan')?>');
      img_prop.cvspace.focus();
      return false;
    }
    
    return true;
  }
  
  function okClick() {
    // validate paramters
    if (validateParams())    
    {
      var iProps = {};
      iProps.align = (img_prop.calign.value)?(img_prop.calign.value):'';
      iProps.width = (img_prop.cwidth.value)?(img_prop.cwidth.value):'';
      iProps.height = (img_prop.cheight.value)?(img_prop.cheight.value):'';
      iProps.border = (img_prop.cborder.value)?(img_prop.cborder.value):'';
      iProps.src = (img_prop.csrc.value)?(img_prop.csrc.value):'';
      iProps.alt = (img_prop.calt.value)?(img_prop.calt.value):'';
      iProps.hspace = (img_prop.chspace.value)?(img_prop.chspace.value):'';
      iProps.vspace = (img_prop.cvspace.value)?(img_prop.cvspace.value):'';

      window.returnValue = iProps;
      window.close();
    }
  }

  function cancelClick() {
    window.close();
  }
  
  
  function setAlign(alignment)
  {
    for (i=0; i<img_prop.calign.options.length; i++)  
    {
      al = img_prop.calign.options.item(i);
      if (al.value == alignment.toLowerCase()) {
        img_prop.calign.selectedIndex = al.index;
      }
    }
  }

  //-->
  </script>
</head>

<body onLoad="Init()" dir="<?PHP echo $l->getDir();?>">
<table border="0" cellspacing="0" cellpadding="2" width="336">
<form name="img_prop">
<tr>
  <td><?PHP echo $l->m('source')?>:</td>
  <td colspan="3"><input type="text" name="csrc" class="input" size="32"></td>
</tr>
<tr>
  <td><?PHP echo $l->m('alt')?>:</td>
  <td colspan="3"><input type="text" name="calt" class="input" size="32"></td>
</tr>
<tr>
  <td><?PHP echo $l->m('align')?>:</td>
  <td align="left">
  <select name="calign" size="1" class="input">
    <option value=""></option>
    <option value="left"><?PHP echo $l->m('left')?></option>
    <option value="right"><?PHP echo $l->m('right')?></option>
    <option value="top"><?PHP echo $l->m('top')?></option>
    <option value="middle"><?PHP echo $l->m('middle')?></option>
    <option value="bottom"><?PHP echo $l->m('bottom')?></option>
    <option value="absmiddle"><?PHP echo $l->m('absmiddle')?></option>
    <option value="texttop"><?PHP echo $l->m('texttop')?></option>
    <option value="baseline"><?PHP echo $l->m('baseline')?></option>
  </select>
  </td>
  <td><?PHP echo $l->m('border')?>:</td>
  <td align="left"><input type="text" name="cborder" class="input_small"></td>
</tr>
<tr>
  <td><?PHP echo $l->m('width')?>:</td>
  <td nowrap>
    <input type="text" name="cwidth" size="3" maxlenght="3" class="input_small">
  </td>
  <td><?PHP echo $l->m('height')?>:</td>
  <td nowrap>
    <input type="text" name="cheight" size="3" maxlenght="3" class="input_small">
  </td>
</tr>
<tr>
  <td><?PHP echo $l->m('hspace')?>:</td>
  <td nowrap>
    <input type="text" name="chspace" size="3" maxlenght="3" class="input_small">
  </td>
  <td><?PHP echo $l->m('vspace')?>:</td>
  <td nowrap>
    <input type="text" name="cvspace" size="3" maxlenght="3" class="input_small">
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
