<?php

chdir('../../');
include ('pv_core.php');
CheckLogin();
LoadUserlanguage();

if (isset($_FILES) && count($_FILES)>0 ) {
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
    function do_submit_f_image(f_image, f_image_alt, f_image_align, f_popup, f_text, f_border){

    	//First we gonna check which descr they want for their pop up
    	var i;
    	for( i=0; i<f_popup.length; ++i) {
    		if( f_popup[i].checked) {
    			selected_popup =  f_popup[i].value;
    		}
    	}


    	//First we gonna check which descr they want for their pop up
    	if(selected_popup == 'text') {
    		window.opener.doPopupImage(f_image,f_image_alt,f_image_align,f_text, f_border, '<?php echo $target; ?>');
    	} else {
    		window.opener.doPopupImage(f_image,f_image_alt,f_image_align, '(thumbnail)' , f_border, '<?php echo $target; ?>');
    	}
    	window.close();
    }

    function openPickWindow() {

    	var my_url = '../pick.php?session=<?php echo $Pivot_Vars['session']; ?>';
    	window.open(my_url, 'pick', 'location=no,status=yes,scrollbars=yes,resizable=yes,width=500,height=400');

    }

    function pop() {

    	imagename = document.pick_f_image.f_image.value;

    	if (imagename== '') {
    		alert("<?php echo lang('upload', 'notice_upload_first'); ?>");
    		return;
    	} else {
    		//alert('pop '+ imagename);
    		window.open("../../modules/module_image.php?session=<?php echo $Pivot_Vars['session']; ?>&refresh=0&image="+imagename, 'thumbnail', "toolbar=no,resizable=yes,scrollbars=yes,width=520,height=550");
    	}
    }



    //]]>
    </script>
</head>
  <body style="margin: 6px 6px 6px 6px; background-image: none;" onload="document.pick_f_image.f_image_alt.select();">
    <table border="0" cellspacing="0" cellpadding="2">

		<tr>
		  <td colspan="2"><b><?php echo lang('upload', 'insert_image_popup'); ?>:</b> <br />
		    <br />
		    <?php echo lang('upload', 'insert_image_popup_desc'); ?></td>
    </tr>
			<tr>
			  <td><b>-  <?php echo lang('upload', 'choose_upload'); ?>:</b></td>
    <td>


<form action="insert_popup.php" method="post" enctype="multipart/form-data" name="form" class="nopadding" id="form">
<input type='hidden' name='f_target' size='25' value='<? echo $target; ?>' class='input' />
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
	</tr></table><hr size="1" noshade><form name="pick_f_image" action="" Method="POST">
<table>
	<td><b><?php echo lang('upload', 'imagename'); ?>:</b> </td><td>
			<input type='text' name='f_image' size='25' value='<?php 
			$imagename= "";

			if (isset($_GET['f_image'])) {
				$imagename = $_GET['f_image'];
			} else if ($success) {
				$imagename = $my_uploader->file['name'];
			}

			echo $imagename;



?>' class='input' />
	</td></tr><tr><td><b><?php echo lang('upload', 'alt_text'); ?>:</b> </td><td>		
			<input type='text' name='f_image_alt' size='25' value='' class='input' />
			
	</td>
	</tr>
<?php 
if ( $_GET['f_hasthumb'] == "1") {
	$thumb = "CHECKED";
	$notthumb = "";
} else {
	$thumb = "";
	$notthumb = "CHECKED";
}

?>
	
	<tr>
	<td valign="top"><b><?php echo lang('upload', 'for_popup'); ?>:</b></td>
	<td>
	<input name="f_popup" id="f_popup1" type="radio" value="thumb" <?php echo $thumb; ?>><label for="f_popup1">
	<?php echo lang('upload', 'use_thumbnail'); ?> <?php

	printf('(<a href="javascript:pop();">'.lang('upload', 'edit_thumbnail').'</a>)');

	?></label><br />
	<input name="f_popup" id="f_popup2"  type="radio" value="text" <?php echo $notthumb; ?>><label for="f_popup2"> 
	<?php echo lang('upload', 'use_text'); ?> : </label>&nbsp;<input type=text name="f_text" class="input" value="<?php echo $text; ?>"><br />
	</td>
	</tr>	
	
	<tr>
	<td><b><?php echo lang('upload', 'align'); ?>:</b></td>
	<td>
	<select name="f_image_align" class='input' />
		<option value="center" selected='selected'><?php echo lang('upload', 'center'); ?></option>
		<option value="left"><?php echo lang('upload', 'left'); ?></option>
		<option value="right"><?php echo lang('upload', 'right'); ?></option>
		<option value="inline"><?php echo lang('upload', 'inline'); ?></option>
	</select>
	</td>
	</tr>	
	<tr> 
      <td><b><?php echo lang('upload', 'border'); ?>:</b></td>
      <td><input type='text' name='f_border' size='10' value='1' class='input' />
        <?php echo lang('upload', 'pixels'); ?></td>
    </tr>
	<tr>
	<td colspan=2><input type='button' name='Submit' value='<?php echo lang('go'); ?>' class='button' onClick="do_submit_f_image(document.pick_f_image.f_image.value, document.pick_f_image.f_image_alt.value, document.pick_f_image.f_image_align.value, f_popup, document.pick_f_image.f_text.value, document.pick_f_image.f_border.value);">
	&nbsp;&nbsp;
	<input name="cancel" type="button" class="button" id="cancel" value="<?php echo lang('cancel'); ?>" onClick="self.close();">
	</td></tr>
</table>	
</form>	

</html>
</body>
