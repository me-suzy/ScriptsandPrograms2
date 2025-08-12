<?php
	@import_request_variables('cgps');
	$PHP_SELF=$_SERVER['PHP_SELF'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD W3 HTML 3.2//EN">
<HTML  id=dlgImage STYLE="width: 432px;scrolling:yes;">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="MSThemeCompatible" content="Yes">
<TITLE>Media Library</TITLE>
<style>
  html, body, button, div, input, select, fieldset { font-family: MS Shell Dlg; font-size: 8pt; position: absolute; scroll :true;};
</style>
<SCRIPT defer>

function _CloseOnEsc() {
  if (event.keyCode == 27) { window.close(); return; }
}

function _getTextRange(elm) {
  var r = elm.parentTextEdit.createTextRange();
  r.moveToElementText(elm);
  return r;
}

window.onerror = HandleError

function HandleError(message, url, line) {
  var str = "An error has occurred in this dialog." + "\n\n"
  + "Error: " + line + "\n" + message;
  alert(str);
  window.close();
  return true;
}

function Init() {
  var elmSelectedImage;
  var htmlSelectionControl = "Control";
  var globalDoc = window.dialogArguments;
  var grngMaster = globalDoc.selection.createRange();
  
  // event handlers  
  document.body.onkeypress = _CloseOnEsc;
  btnOK.onclick = new Function("btnOKClick()");

  txtFileName.fImageLoaded = false;
  txtFileName.intImageWidth = 0;
  txtFileName.intImageHeight = 0;

  if (globalDoc.selection.type == htmlSelectionControl) {
    if (grngMaster.length == 1) {
      elmSelectedImage = grngMaster.item(0);
      if (elmSelectedImage.tagName == "IMG") {
        txtFileName.fImageLoaded = true;
        if (elmSelectedImage.src) {
          txtFileName.value          = elmSelectedImage.src.replace(/^[^*]*(\*\*\*)/, "$1");  // fix placeholder src values that editor converted to abs paths
          txtFileName.intImageHeight = elmSelectedImage.height;
          txtFileName.intImageWidth  = elmSelectedImage.width;
          txtVertical.value          = elmSelectedImage.vspace;
          txtHorizontal.value        = elmSelectedImage.hspace;
          txtBorder.value            = elmSelectedImage.border;
          txtAltText.value           = elmSelectedImage.alt;
          selAlignment.value         = elmSelectedImage.align;
        }
      }
    }
  }
  txtFileName.value = txtFileName.value || "http://";
}

function _isValidNumber(txtBox) {
  var val = parseInt(txtBox);
  if (isNaN(val) || val < 0 || val > 999) { return false; }
  return true;
}

function btnOKClick() {
  var elmImage;
  var intAlignment;
  var htmlSelectionControl = "Control";
  var globalDoc = window.dialogArguments;
  var grngMaster = globalDoc.selection.createRange();

  // delete selected content and replace with image
  if (globalDoc.selection.type == htmlSelectionControl && !txtFileName.fImageLoaded) {
    grngMaster.execCommand('Delete');
    grngMaster = globalDoc.selection.createRange();
  }
    
  idstr = "\" id=\"556e697175657e537472696e67";     // new image creation ID
  if (!txtFileName.fImageLoaded) {
    grngMaster.execCommand("InsertImage", false, idstr);
    elmImage = globalDoc.all['556e697175657e537472696e67'];
    elmImage.removeAttribute("id");
    elmImage.removeAttribute("src");
    grngMaster.moveStart("character", -1);
  } else {
    elmImage = grngMaster.item(0);
    if (elmImage.src != txtFileName.value) {
      grngMaster.execCommand('Delete');
      grngMaster = globalDoc.selection.createRange();
      grngMaster.execCommand("InsertImage", false, idstr);
      elmImage = globalDoc.all['556e697175657e537472696e67'];
      elmImage.removeAttribute("id");
      elmImage.removeAttribute("src");
      grngMaster.moveStart("character", -1);
      txtFileName.fImageLoaded = false;
    }
    grngMaster = _getTextRange(elmImage);
  }

  if (txtFileName.fImageLoaded) {
    elmImage.style.width = txtFileName.intImageWidth;
    elmImage.style.height = txtFileName.intImageHeight;
  }

  if (txtFileName.value.length > 2040) {
    txtFileName.value = txtFileName.value.substring(0,2040);
  }
  
  elmImage.src = txtFileName.value;
  
  if (txtHorizontal.value != "") { elmImage.hspace = parseInt(txtHorizontal.value); }
  else                           { elmImage.hspace = 0; }

  if (txtVertical.value != "") { elmImage.vspace = parseInt(txtVertical.value); }
  else                         { elmImage.vspace = 0; }
  
  elmImage.alt = txtAltText.value;

  if (txtBorder.value != "") { elmImage.border = parseInt(txtBorder.value); }
  else                       { elmImage.border = 0; }

  elmImage.align = selAlignment.value;
  grngMaster.collapse(false);
  grngMaster.select();
  window.close();
}
</SCRIPT>
</HEAD>
<BODY id=bdy onload="Init()" style="background: threedface; color: windowtext;scroll:Yes" >
<SCRIPT LANGUAGE=JavaScript>
	function SetFileName (file)
	{
	<?php $path=str_replace("admin/popups/insert_media.php","files/",$_SERVER['SCRIPT_NAME']);?>
	var webHost ='http://<?php echo $_SERVER['HTTP_HOST'].$path; ?>';
	txtFileName.value=(webHost+file);
	btnOKClick ();
	}
</SCRIPT>
<INPUT type=hidden ID=txtFileName value="http://">
<INPUT type=hidden ID=txtAltText value="">
<INPUT type=hidden ID=selAlignment value="">
<INPUT type=hidden ID=txtHorizontal value="0">
<INPUT type=hidden ID=txtBorder value="0">
<INPUT type=hidden ID=txtVertical value="0">
<center>
<?php
if ($handle = @opendir('../../files/')) 
	{
	while (false !== ($file = readdir($handle)))
		{
		if ($file != "." && $file != ".." )
			{
			$ext=substr($file,strpos($file,"."),strlen($file));
			if($ext==".jpg" || $ext==".gif" || $ext==".png")
				{
				$size=@getImageSize('../../files/'.$file);
				echo "<p><img src=\"../../files/$file\"  width=\"".($size[0]/2)."\" height=\"".($size[1]/2)."\" onClick=\"SetFileName ('$file')\"><br />$file<br />".$size[0]." X ".$size[1]."</p>\n";
				}
			}
		}
	closedir($handle); 
	}
?>
</center>
<input type=hidden ID=btnOK>
<BUTTON ID=btnCancel style="left: 31.36em; top: 3.6504em; width: 7em; height: 2.2em; " type=reset tabIndex=45 onClick="window.close();">Cancel</BUTTON>
</BODY>
</HTML>