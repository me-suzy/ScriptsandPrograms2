<?PHP 
// ================================================
// Image library dialog
// ================================================

// include wysiwyg config
include '../config/visEdit_control.config.php';
include '../class/lang.class.php';


$theme = empty($HTTP_POST_VARS['theme'])?(empty($HTTP_GET_VARS['theme'])?$visEdit_default_theme:$HTTP_GET_VARS['theme']):$HTTP_POST_VARS['theme'];
$theme_path = '../lib/themes/'.$theme.'/';

$l = new visEdit_Lang(empty($HTTP_POST_VARS['lang'])?$HTTP_GET_VARS['lang']:$HTTP_POST_VARS['lang']);
$l->setBlock('image_insert');
?>

<?PHP 
$imglib = $HTTP_POST_VARS['lib'];
if (empty($imglib)) $imglib = $HTTP_GET_VARS['lib'];

$value_found = false;
// callback function for preventing listing of non-library directory
function is_array_value($value, $key, $_imglib)
{
  global $value_found;
  // echo $value.'-'.$_imglib.'<br>';
  if (is_array($value)) array_walk($value, 'is_array_value',$_imglib);
  if ($value == $_imglib){
    $value_found=true;
  }
}
array_walk($visEdit_imglibs, 'is_array_value',$imglib);

if (!$value_found || empty($imglib))
{
  $imglib = $visEdit_imglibs[0]['value'];
}
$lib_options = liboptions($visEdit_imglibs,'',$imglib);


$img = $HTTP_POST_VARS['imglist'];

$preview = '';

$errors = array();
if ($HTTP_POST_FILES['img_file']['size']>0)
{
  if ($img = uploadImg('img_file'))
  {
    $preview = $visEdit_base_url.$imglib.$img;
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
  <title><?PHP echo $l->m('title')?></title>
	<meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Content-Type" content="text/html; charset=<?PHP echo $l->getCharset()?>">
  <link rel="stylesheet" type="text/css" href="<?PHP echo $theme_path.'css/'?>dialog.css">
  <script language="javascript" src="utils.js"></script>
  
  <script language="javascript">
  <!--
    function selectClick()
    {
      if (document.libbrowser.lib.selectedIndex>=0 && document.libbrowser.imglist.selectedIndex>=0)
      {
        window.returnValue = '<?PHP echo $visEdit_base_url?>'+document.libbrowser.lib.options[document.libbrowser.lib.selectedIndex].value + document.libbrowser.imglist.options[document.libbrowser.imglist.selectedIndex].value;
        window.close();
      }
      else
      {
        alert('<?PHP echo $l->m('error').': '.$l->m('error_no_image')?>');
      }
    }
    
    function Init()
    {
      resizeDialogToContent();
    }
  //-->
  </script>
</head>

<body onLoad="Init()" dir="<?PHP echo $l->getDir();?>">
  <script language="javascript">
  <!--
    window.name = 'imglibrary';
  //-->
  </script>

<form name="libbrowser" method="post" action="img_library.php" enctype="multipart/form-data" target="imglibrary">
<input type="hidden" name="theme" value="<?PHP echo $theme?>">
<input type="hidden" name="lang" value="<?PHP echo $l->lang?>">
<div style="border: 1 solid Black; padding: 5 5 5 5;">
<table border="0" cellpadding="2" cellspacing="0">
<tr>
  <td valign="top" align="left"><b><?PHP echo $l->m('library')?>:</b></td>
  <td valign="top" align="left">&nbsp;</td>
  <td valign="top" align="left"><b><?PHP echo $l->m('preview')?>:</b></td>
</tr>
<tr>
  <td valign="top" align="left">
  <select name="lib" size="1" class="input" style="width: 150px;" onChange="libbrowser.submit();">
    <?PHP echo $lib_options?>
  </select>
  </td>
  <td valign="top" align="left" rowspan="3">&nbsp;</td>
  <td valign="top" align="left" rowspan="3">
  <iframe name="imgpreview" src="<?PHP echo $preview?>" style="width: 200px; height: 100%;" scrolling="Auto" marginheight="0" marginwidth="0" frameborder="0"></iframe>
  </td>
</tr>
<tr>
  <td valign="top" align="left"><b><?PHP echo $l->m('images')?>:</b></td>
</tr>
<tr>
  <td valign="top" align="left">
  <?PHP 
	      $_root = __FILE__ ;
	$_root = str_replace('\\', '/', $_root);
	$_root = str_replace('e_data/dialogs/img_library.php', '', $_root);
    $d = @dir($_root.$imglib);
  ?>
  <select name="imglist" size="15" class="input" style="width: 150px;" 
    onchange="if (this.selectedIndex &gt;=0) imgpreview.location.href = '<?PHP echo $visEdit_base_url.$imglib?>' + this.options[this.selectedIndex].value;" ondblclick="selectClick();">
  <?PHP 
    if ($d) 
    {
      while (false !== ($entry = $d->read())) {
        if (is_file($_root.$imglib.$entry))
        {
          ?>
          <option value="<?PHP echo $entry?>" <?PHP echo ($entry == $img)?'selected':''?>><?PHP echo $entry?></option>
          <?PHP 
        }
      }
      $d->close();
    }
    else
    {
      $errors[] = $l->m('error_no_dir');
    }
  ?>


  </select>
  </td>
</tr>
<tr>
  <td valign="top" align="left" colspan="3">
  <input type="button" value="<?PHP echo $l->m('select')?>" class="bt" onclick="selectClick();">&nbsp;<input type="button" value="<?PHP echo $l->m('cancel')?>" class="bt" onclick="window.close();">
  </td>
</tr>
</table>
</div>

<?PHP  if ($visEdit_upload_allowed) { ?>
<div style="border: 1 solid Black; padding: 5 5 5 5;">
<table border="0" cellpadding="2" cellspacing="0">
<tr>
  <td valign="top" align="left">
    <?PHP  
    if (!empty($errors))
    {
      echo '<span class="error">';
      foreach ($errors as $err)
      {
        echo $err.'<br>';
      }
      echo '</span>';
    }
    ?>

  <?PHP 
  if ($d) {
  ?>
    <b><?PHP echo $l->m('upload')?>:</b> <input type="file" name="img_file" class="input"><br>
    <input type="submit" name="btnupload" class="bt" value="<?PHP echo $l->m('upload_button')?>">
  <?PHP 
  }
  ?>
  </td>
</tr>
</table>
</div>
<?PHP  } ?>
</form>
</body>
</html>

<?PHP 
function liboptions($arr, $prefix = '', $sel = '')
{
  $buf = '';
  foreach($arr as $lib) {
    $buf .= '<option value="'.$lib['value'].'"'.(($lib['value'] == $sel)?' selected':'').'>'.$prefix.$lib['text'].'</option>'."\n";
  }
  return $buf;
}

function uploadImg($img) {

  global $HTTP_POST_FILES;
  global $HTTP_SERVER_VARS;
  global $visEdit_valid_imgs;
  global $imglib;
  global $errors;
  global $l;
  global $visEdit_upload_allowed;
  
  if (!$visEdit_upload_allowed) return false;

	      $_root = __FILE__ ;
	$_root = str_replace('\\', '/', $_root);
	$_root = str_replace('e_data/dialogs/img_library.php', '', $_root);
  
  if ($HTTP_POST_FILES[$img]['size']>0) {
    $data['type'] = $HTTP_POST_FILES[$img]['type'];
    $data['name'] = $HTTP_POST_FILES[$img]['name'];
    $data['size'] = $HTTP_POST_FILES[$img]['size'];
    $data['tmp_name'] = $HTTP_POST_FILES[$img]['tmp_name'];

    // get file extension
    $ext = strtolower(substr(strrchr($data['name'],'.'), 1));
    if (in_array($ext,$visEdit_valid_imgs)) {
      $dir_name = $_root.$imglib;

      $img_name = $data['name'];
      $i = 1;
      while (file_exists($dir_name.$img_name)) {
        $img_name = ereg_replace('(.*)(\.[a-zA-Z]+)$', '\1_'.$i.'\2', $data['name']);
        $i++;
      }
      if (!move_uploaded_file($data['tmp_name'], $dir_name.$img_name)) {
        $errors[] = $l->m('error_uploading');
        return false;
      }

      return $img_name;
    }
    else
    {
      $errors[] = $l->m('error_wrong_type');
    }
  }
  return false;
}
?>