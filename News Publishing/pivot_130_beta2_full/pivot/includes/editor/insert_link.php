<?php

chdir('../../');
include ('pv_core.php');
CheckLogin();
LoadUserlanguage();


if (isset($_GET['f_target'])) {
	$target= $_GET['f_target'];
} else {
	$target= $_POST['f_target'];
}

$url = $_GET['url'];
if ($url=="") {
	$url="http://";
}

$text = urldecode($_GET['text']);
if ($text=="") {
	$text="link";
}

if ($text != trim($text)) {
	$text = trim($text);
	$padding=" ";
} else {
	$padding="";
}

$title = $_GET['title'];

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CurrentEncoding; ?>\"" />
	<title>Pivot &#187; <?php echo lang('link', 'insert_link'); ?></title>
	<link href="../../<?php echo $theme['css']; ?>" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
    //<![CDATA[


    //We need to submit this to the opener, that is to the editor
    function do_submit_f_link(url, text, title){
    	window.opener.doLink(url, text, title, '<?php echo $target; ?>', '<?php echo $padding; ?>');
    	window.close();
    }

    function do_submit_f_note(image,image_alt,image_align, border){
    	window.opener.doImage(image,image_alt,image_align, border, '<?php echo $target; ?>');
    	window.close();
    }


    function openPickWindow() {

    	var my_url = '../pick.php';
    	window.open(my_url,'pick','location=no,status=yes,scrollbars=yes,resizable=yes,width=500,height=400');

    }


    //]]>
    </script>
</head>
<body style="margin: 6px 6px 6px 6px; background-image: none;" onload="document.pick_f_image.f_url.select();";>
<form name="pick_f_image" action="" Method="POST" onsubmit="do_submit_f_link(document.pick_f_image.f_url.value ,document.pick_f_image.f_text.value , document.pick_f_image.f_title.value);">
  <table width="100%">
		<tr>
		  <td colspan="2"><b><?php echo lang('link', 'insert_link'); ?>:</b> <br />
		    <br />
		    <?php echo lang('link', 'insert_link_desc'); ?> </td>
    </tr>
			<tr>
			  <td><b><?php echo lang('link', 'url'); ?>:</b></td>
    <td>


<input name="f_url" type="text"  class="input" style="width:98%" value="<?php echo $url; ?>">
  </td>
    </tr>
		
		<tr>
		  <td><b><?php echo lang('link', 'text'); ?>:</b></td>
    <td><input name="f_text" type="text"  class="input" style="width:98%" value="<?php echo stripslashes($text); ?>"> </td>
</tr>
		<tr>
		  <td><b><?php echo lang('link', 'title'); ?>:</b></td>
    <td><input name="f_title" type="text"  class="input" style="width:98%" value="<?php echo $title; ?>"> </td>
</tr></table><hr size="1" noshade>
  <table>
    <tr> 
      <td><input type='button' name='Submit' value='<?php echo lang('go'); ?>' class='button' onClick="do_submit_f_link(document.pick_f_image.f_url.value ,document.pick_f_image.f_text.value , document.pick_f_image.f_title.value);" /> 
      &nbsp;&nbsp;
      <input name="cancel" type="button" class="button" id="cancel" value="<?php echo lang('cancel'); ?>" onClick="self.close();" /></td>
    </tr>
  </table>	
</form>
</html>
</body>
