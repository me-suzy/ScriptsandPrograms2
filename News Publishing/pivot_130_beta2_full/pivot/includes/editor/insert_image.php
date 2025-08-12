<?php

chdir('../../');
include ('pv_core.php');
CheckLogin();
LoadUserlanguage();


if (isset($HTTP_POST_FILES) && count($HTTP_POST_FILES)>0 ) {
	$path = '../'.$Cfg['upload_path'];
	require_once('includes/fileupload-class.php');
	$my_uploader = new uploader;

	// OPTIONAL: set the max filesize of uploadable files in bytes
	$my_uploader->max_filesize($Cfg['max_filesize']);

	// UPLOAD the file
	if ($my_uploader->upload('userfile', $Cfg['upload_accept'], $Cfg['upload_extension'])) {
		$success = $my_uploader->save_file($path, $Cfg['upload_save_mode'], 1);
	}
}

if (isset($_GET['f_target'])) {
	$target= $_GET['f_target'];
} else {
	$target= $_POST['f_target'];
}

if (isset($_GET['f_text'])) {
	$text= urldecode($_GET['f_text']);
} else {
	$text= $_POST['f_text'];
}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CurrentEncoding; ?>\"" />
	<title>Pivot &#187; <?php echo lang('upload', 'insert_image'); ?></title>
	<link href="../../<?php echo $theme['css']; ?>" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
    //<![CDATA[

    //We need to submit this to the opener, that is to the editor
    function do_submit_f_image(image,image_alt,image_align, border){
    	window.opener.doImage(image,image_alt,image_align, border, '<?php echo $target; ?>');
    	window.close();
    }

    function openPickWindow() {

    	var my_url = '../pick.php?session=<?php echo $Pivot_Vars['session']; ?>';
    	window.open(my_url,'pick','location=no,status=yes,scrollbars=yes,resizable=yes,width=500,height=400');

    }


    //]]>
    </script>
</head>
  <body style="margin: 6px 6px 6px 6px; background-image: none;" onload="document.pick_f_image.f_image_alt.select();">
    <table border="0" cellspacing="0" cellpadding="2">

    <tr>
		  <td colspan="2"><b><?php echo lang('upload', 'insert_image'); ?>:</b> <br />
		    <br />
		    <?php echo lang('upload', 'insert_image_desc'); ?> </td>
    </tr>
			<tr>
			  <td><b>- <?php echo lang('upload', 'choose_upload'); ?>:</b></td>
    <td>

<form action="insert_image.php" method="post" enctype="multipart/form-data" name="form1" class="nopadding" id="form">
<input type='hidden' name='f_target' size='25' value='<?php echo $target; ?>' class='input' />
<input type='hidden' name='session' size='25' value='<?php echo $Pivot_Vars['session'] ?>' class='input' />
<input name="userfile" type="file"  class="input">
<input type="submit" value="<?php echo lang('upload', 'button'); ?>" class="button" />
</form>  </td>
    </tr>
		
<?php 

if ($success) {
	$msg = sprintf(lang('upload', 'uploaded_as'),  $my_uploader->file['name']);
	printf("<tr><td colspan=2>%s</td></tr>", $msg);

} else if($my_uploader->errors) {

	$msg = lang('upload', 'not_uploaded')."<br />\n";
	while(list($key, $var) = each($my_uploader->errors)){
		$msg .= $var . "<br />\n";
	}
	printf("<tr><td colspan=2>%s</td></tr>", $msg);

}

?>
		<tr>
		  <td><b>- <?php echo lang('upload', 'choose_select'); ?>:</b></td>
    <td> <input type='hidden' name='f_target' size='35' value='<? echo $target; ?>' class='input' /> 
      <input name="Submit2" type="button" class="button" value="<?php echo lang('upload', 'select_image'); ?>" onClick="openPickWindow();">
    </td>
	</tr></table><hr size="1" noshade="noshade"><form name="pick_f_image" action="" method="post">
	<table>
</td></tr>
    </tr>
		<tr><td><b><?php echo lang('upload', 'imagename'); ?>: </b></td>
    <td> <input type='text' name='f_image' size='35' value='<?php 

    $imagename= "";

    if (isset($_GET['f_image'])) {
    	$imagename = $_GET['f_image'];
    } else if ($success) {
    	$imagename = $my_uploader->file['name'];
    }

    echo $imagename;

		?>' class='input' />
    </td>
    </tr>
    <tr> 
      <td><b><?php echo lang('upload', 'alt_text'); ?>:</b> </td>
      <td> <input type='text' name='f_image_alt' size='35' value='<?php echo $text; ?>' class='input' />
      </td>
    </tr>
    <tr> 
      <td><b><?php echo lang('upload', 'align'); ?>:</b></td>
      <td> <select name="f_image_align" class='input'>
          <option value="center" selected='selected'><?php echo lang('upload', 'center'); ?></option>
          <option value="left"><?php echo lang('upload', 'left'); ?></option>
          <option value="right"><?php echo lang('upload', 'right'); ?></option>
          <option value="inline"><?php echo lang('upload', 'inline'); ?></option>
        </select> </td>
    </tr>
    <tr> 
      <td><b><?php echo lang('upload', 'border'); ?>:</b></td>
      <td><input type='text' name='f_border' size='10' value='0' class='input' />
        <?php echo lang('upload', 'pixels'); ?></td>
    </tr>
    <tr> 
      <td colspan=2><br /><input type='button' name='Submit' value='<?php echo lang('go'); ?>' class='button' onClick="do_submit_f_image(document.pick_f_image.f_image.value , document.pick_f_image.f_image_alt.value , document.pick_f_image.f_image_align.value, document.pick_f_image.f_border.value);">
      &nbsp;&nbsp;
      <input name="cancel" type="button" class="button" id="cancel" value="<?php echo lang('cancel'); ?>" onClick="self.close();" /></td>
    </tr>
  </table>	
</form>
</html></body>
