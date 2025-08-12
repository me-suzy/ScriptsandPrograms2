<?PHP 
// ================================================
// Confirmation dialog
// ================================================

// include wysiwyg config
include '../config/visEdit_control.config.php';
include '../class/lang.class.php';

$theme = empty($HTTP_GET_VARS['theme'])?$visEdit_default_theme:$HTTP_GET_VARS['theme'];
$theme_path = '../lib/themes/'.$theme.'/';

$block = $HTTP_GET_VARS['block'];
$message = $HTTP_GET_VARS['message'];

$l = new visEdit_Lang($HTTP_GET_VARS['lang']);
$l->setBlock($block);
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
  function Init() {
    cur_color = window.dialogArguments;
    resizeDialogToContent();
  }
  
  function okClick() {
    window.returnValue = true;
    window.close();
  }

  function cancelClick() {
    window.returnValue = false;
    window.close();
  }
  //-->
  </script>
</head>

<body onLoad="Init()" dir="<?PHP echo $l->getDir();?>">

<p align="center">
<br>
<?PHP echo $l->m($message)?>
<br><br>
<form name="colorpicker">
<input type="button" value="<?PHP echo $l->m('ok')?>" onClick="okClick()" class="bt">
<input type="button" value="<?PHP echo $l->m('cancel')?>" onClick="cancelClick()" class="bt"><br><br>
</form>
</p>

</body>
</html>
