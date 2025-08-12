<?php 
/**
* frame_form.php
*
* This file contains the code for the gallery frame form
* 
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*
*/
?>
<script language="javaScript">
function validateForm() {
  var okSoFar=true //-- Changes to false when bad field found.
	var wm = "The following fields are required to \n\rcontinue this action:\n\r\n";
	var noerror = 1;
  //-- Check the field, reject if blank.


  if (document.myForm.form_frame_name.value=="") {
    okSoFar=false
	wm += "* Frame set name\r\n";
    document.myForm.form_frame_name.focus()
  }


	if (okSoFar==false) {
	alert(wm);
	return (false);
	}
  
}
</script>

<form method="post" action="frame.php" name="myForm" onSubmit="return validateForm()" enctype="multipart/form-data">

<center>
<table border="0" cellspacing="1" cellpadding="3" bgcolor="#999999">

<tr>
	<td class="resultline-alt"><b>Frame Set Name: </b></td>
	<td class="resultline-alt"><input type="text" name="form_frame_name"<?php if ((isset($frame_name)) && (!empty($frame_name))) { echo " value=\"".stripslashes($frame_name)."\""; }?> maxlength="60" size="25"></td>
</tr>
<tr>
	<td colspan="2" class="resultline"><b>Thumbnail Frame Images</b></td>
</tr>
<tr>
	<td class="resultline-alt">Top Left:</td>
	<td class="resultline-alt"><input name="form_frame_img[0]" type="file"></td>
</tr>
<tr>
	<td class="resultline-alt">Top Background:</td>
	<td class="resultline-alt"><input name="form_frame_img[1]" type="file"></td>
</tr>
<tr>
	<td class="resultline-alt">Top Right:</td>
	<td class="resultline-alt"><input name="form_frame_img[2]" type="file"></td>
</tr>
<tr>
	<td class="resultline-alt">Left Background:</td>
	<td class="resultline-alt"><input name="form_frame_img[3]" type="file"></td>
</tr>
<tr>
	<td class="resultline-alt">Right Background:</td>
	<td class="resultline-alt"><input name="form_frame_img[4]" type="file"></td>
</tr>
<tr>
	<td class="resultline-alt">Bottom Left:</td>
	<td class="resultline-alt"><input name="form_frame_img[5]" type="file"></td>
</tr>
<tr>
	<td class="resultline-alt">Bottom Background:</td>
	<td class="resultline-alt"><input name="form_frame_img[6]" type="file"></td>
</tr>
<tr>
	<td class="resultline-alt">Bottom Right:</td>
	<td class="resultline-alt"><input name="form_frame_img[7]" type="file"></td>
</tr>
<tr>
	<td colspan="2" class="resultline"><b>Fullsize Frame Images</b></td>
</tr>
<tr>
	<td class="resultline-alt">Top Left:</td>
	<td class="resultline-alt"><input name="form_frame_img[8]" type="file"></td>
</tr>
<tr>
	<td class="resultline-alt">Top Background:</td>
	<td class="resultline-alt"><input name="form_frame_img[9]" type="file"></td>
</tr>
<tr>
	<td class="resultline-alt">Top Right:</td>
	<td class="resultline-alt"><input name="form_frame_img[10]" type="file"></td>
</tr>
<tr>
	<td class="resultline-alt">Left Background:</td>
	<td class="resultline-alt"><input name="form_frame_img[11]" type="file"></td>
</tr>
<tr>
	<td class="resultline-alt">Right Background:</td>
	<td class="resultline-alt"><input name="form_frame_img[12]" type="file"></td>
</tr>
<tr>
	<td class="resultline-alt">Bottom Left:</td>
	<td class="resultline-alt"><input name="form_frame_img[13]" type="file"></td>
</tr>
<tr>
	<td class="resultline-alt">Bottom Background:</td>
	<td class="resultline-alt"><input name="form_frame_img[14]" type="file"></td>
</tr>
<tr>
	<td class="resultline-alt">Bottom Right:</td>
	<td class="resultline-alt"><input name="form_frame_img[15]" type="file"></td>
</tr>
<tr>
	<td colspan="2" class="resultline" align="right"><div align="right">
	<?php if (isset($_REQUEST['frame_id'])) { ?>
	<input type="submit" value="Save Edits" class="formbutton">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="frame_id" value="<?php echo $_REQUEST['frame_id']; ?>">
	<?php } else { ?>
	<input type="submit" value="Save New" class="formbutton">
	<input type="hidden" name="action" value="new">
	<?php } ?>
	
	</div></td>
</tr>
</table>
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
</center>
</form>