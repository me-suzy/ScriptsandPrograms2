<?PHP 
// ================================================
// Configuration file
// ================================================
// directory where visEdit files are located
$visEdit_dir = __FILE__ ;
$visEdit_dir = str_replace('e_data/config/visEdit_control.config.php', '', $visEdit_dir);
$base_url = str_replace($visEdit_dir, '', dirname($HTTP_SERVER_VARS['PHP_SELF']));
$b_ddid = ''.$base_url.'/e_data/';
$b_ddid = str_replace('\\', '', $b_ddid);
$b_ddid = str_replace('//', '/', $b_ddid);
$visEdit_dir = $b_ddid;
// base url for images
$pimgnow = $base_url.'/';
$pimgnow = str_replace('e_data/dialogs/', '', $pimgnow);
$visEdit_base_url = 'http://'.$HTTP_SERVER_VARS['SERVER_NAME'].$pimgnow;
// root finder
$visEdit_root = __FILE__ ;
$visEdit_root = str_replace('\\', '/', $visEdit_root);
$visEdit_root = str_replace('config/visEdit_control.config.php', '', $visEdit_root);
$visEdit_default_toolbars = 'default';

$visEdit_default_theme = 'default';
$visEdit_default_lang = 'en';
$visEdit_default_css_stylesheet = $visEdit_dir.'wysiwyg.css';
// add javascript inline or via separate file
$visEdit_inline_js = false;
// use active toolbar (reflecting current style) or static
$visEdit_active_toolbar = true;
// default dropdown content
$visEdit_dropdown_data['style']['default'] = 'Normal';
$visEdit_dropdown_data['font']['Arial'] = 'Arial';
$visEdit_dropdown_data['font']['Courier'] = 'Courier';
$visEdit_dropdown_data['font']['Tahoma'] = 'Tahoma';
$visEdit_dropdown_data['font']['Times New Roman'] = 'Times';
$visEdit_dropdown_data['font']['Verdana'] = 'Verdana';
$visEdit_dropdown_data['fontsize']['1'] = '1';
$visEdit_dropdown_data['fontsize']['2'] = '2';
$visEdit_dropdown_data['fontsize']['3'] = '3';
$visEdit_dropdown_data['fontsize']['4'] = '4';
$visEdit_dropdown_data['fontsize']['5'] = '5';
$visEdit_dropdown_data['fontsize']['6'] = '6';
$visEdit_dropdown_data['paragraph']['Normal'] = 'Normal';
$visEdit_dropdown_data['paragraph']['Heading 1'] = 'Heading 1';
$visEdit_dropdown_data['paragraph']['Heading 2'] = 'Heading 2';
$visEdit_dropdown_data['paragraph']['Heading 3'] = 'Heading 3';
$visEdit_dropdown_data['paragraph']['Heading 4'] = 'Heading 4';
$visEdit_dropdown_data['paragraph']['Heading 5'] = 'Heading 5';
$visEdit_dropdown_data['paragraph']['Heading 6'] = 'Heading 6';
// image library related config
// allowed extentions for uploaded image files
$visEdit_valid_imgs = array('gif', 'jpg', 'jpeg', 'png');
// allow upload in image library
$visEdit_upload_allowed = true;
// image libraries
$visEdit_imglibs = array(
  array(
    'value'   => 'images/',
    'text'    => 'Image Library',
  ),
);


?>
