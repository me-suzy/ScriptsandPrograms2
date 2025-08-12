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
	if ($my_uploader->upload('userfile', $Cfg['upload_accept'], '')) {
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
	<title>Pivot &#187; <?php echo lang('upload', 'insert_download'); ?></title>
	<link href="../../<?php echo $theme['css']; ?>" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
    //<![CDATA[
    

//We need to submit this to the opener, that is to the editor
function do_submit_f_download(f_image, f_popup, f_text, f_title){
	
	//First we gonna check which descr they want for their pop up	
	var i;
	for( i=0; i<f_popup.length; ++i) {
		if( f_popup[i].checked) {
			selected_popup =  f_popup[i].value;
		}
	}

	window.opener.doDownload(f_image, selected_popup, f_text, f_title, '<?php echo $target; ?>');

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


</script>
<body style="margin: 6px 6px 6px 6px; background-image: none;">

  <table>
		<tr>
		  <td colspan="2"><b><?php echo lang('upload', 'insert_download'); ?>:</b> <br />
		    <br />
		    <?php echo lang('upload', 'insert_download_desc'); ?></td>
    </tr>
			<tr>
			  <td><b>- <?php echo lang('upload', 'choose_upload'); ?>:</b></td>
    <td>


<form action="insert_download.php" method="post" enctype="multipart/form-data" name="form" class="nopadding" id="form">
<input type='hidden' name='f_target' size='25' value='<? echo $target; ?>' class='input'>
<input type='hidden' name='session' size='25' value='<?php echo $Pivot_Vars['session'] ?>' class='input'>
<input name="userfile" type="file"  class="input">
<input type="submit" value="<?php echo lang('upload', 'button'); ?>" class="button">
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
    <td> <input type='hidden' name='f_target' size='35' value='<? echo $target; ?>' class='input'> 
      <input name="Submit2" type="button" class="button" value="<?php echo lang('upload', 'select_file'); ?>" onClick="openPickWindow();">
    </td>
	</tr></table><hr size="1" noshade><form name="pick_f_image" action="" Method="POST">
<table>
	<td><b><?php echo lang('upload', 'filename'); ?>:</b> </td><td>
			<input type='text' name='f_image' style='width:98%' value='<?php 
		$imagename= "";

		if (isset($_GET['f_image'])) {
			$imagename = $_GET['f_image']; 
		} else if ($success) { 
			$imagename = $my_uploader->file['name'];
		}
		
		echo $imagename;
		
		

?>' class='input'>
	</td></tr>

<?php
	if ($text == '') {
	  $useicon = ' CHECKED';
	  $usetext = '';
	} else {
	  $useicon = '';
	  $usetext = ' CHECKED';
	}

?>


	<tr>
	<td valign="top"><b><?php echo lang('upload', 'for_popup'); ?>:</b></td>
	<td>
	<input name="f_popup" id="f_popup1" type="radio" value="icon" <?php echo $useicon; ?>><label for="f_popup1"><?php echo lang('upload', 'use_icon'); ?> </label>
	<br />
	<input name="f_popup" id="f_popup2" type="radio" value="text" <?php echo $usetext; ?>><label for="f_popup2"><?php echo lang('upload', 'use_text'); ?> : </label>&nbsp;<input type=text name="f_text" class="input" value="<?php echo $text; ?>"><br />
	</td>
	</tr>	



		<tr>
		  <td><b><?php echo lang('link', 'title'); ?>:</b></td>
    <td><input name="f_title" type="text"  class="input" style="width:98%" value="<?php echo $title; ?>"> </td>
</tr>	


	<tr>
	<td colspan=2><input type='button' name='Submit' value='<?php echo lang('go'); ?>' class='button' onClick="do_submit_f_download(document.pick_f_image.f_image.value, document.pick_f_image.f_popup, document.pick_f_image.f_text.value , document.pick_f_image.f_title.value);">
	&nbsp;&nbsp;
	<input name="cancel" type="button" class="button" id="cancel" value="<?php echo lang('cancel'); ?>" onClick="self.close();">
	</td></tr>
</table>	
</form>	

</html></body>
