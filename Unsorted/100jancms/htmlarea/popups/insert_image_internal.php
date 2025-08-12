<?php 
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
// do not cache
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    	        // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate");  	        // HTTP/1.1
header ("Pragma: no-cache");                          	        // HTTP/1.0


// Restrict acces to this page

//this page clearance
$arr = array (  
  '0' => 'ADMIN',
  '1' => 'ARTICLES_MASTER',
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include '../../restrict_access.php';

//configuration file
include '../../config_inc.php'; 

//load config
$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='articles_editor_image_preview'";
$result=mysql_query($query);
$articles_editor_image_preview=mysql_result($result,0,"config_value");

$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='app_url'";
$result=mysql_query($query);
$app_url=mysql_result($result,0,"config_value");



//user_privileges

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD W3 HTML 3.2//EN">
<html id="dlgImage" STYLE="width: 607px; height: 445px;">
<head>
<meta http-equiv="MSThemeCompatible" content="Yes">
<title>Insert Internal Image</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">


<SCRIPT>

function Get_URL() {
if (this.txtFileName.selectedIndex>-1)
  imageFrame.location="image_selected.php?image="+this.txtFileName[this.txtFileName.selectedIndex].value;
}

</SCRIPT>



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
  + "Error line: " + line + "\n" + message;
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

//loading image name ======================================================================

//application path
var app_url="<?php echo $app_url."images/articles/depot/";?>";
//length of application path string
var app_url_length = app_url.length;
//is the image internal?
var is_contained = elmSelectedImage.src.indexOf(app_url, 0);
//alert (is_contained);
if (is_contained!==-1) {

	//get image name
	var image_name = elmSelectedImage.src.substr(app_url_length, elmSelectedImage.src.length);

for (var i = 0; i < txtFileName.length; i++) {

if (txtFileName[i].value==image_name) {
	//selects image
	txtFileName.focus();
	txtFileName.selectedIndex=i;
	
	Get_URL();
	}

}

//=========================================================================================

          txtFileName.value          = elmSelectedImage.src.replace(/^[^*]*(\*\*\*)/, "$1");  // fix placeholder src values that editor converted to abs paths
          txtFileName.intImageHeight = elmSelectedImage.height;
          txtFileName.intImageWidth  = elmSelectedImage.width;
          txtVertical.value          = elmSelectedImage.vspace;
          txtHorizontal.value        = elmSelectedImage.hspace;
          txtBorder.value            = elmSelectedImage.border;
          txtAltText.value           = elmSelectedImage.alt;
          selAlignment.value         = elmSelectedImage.align;
	} //internal image	  
        }
      }
    }
  }
  txtFileName.value = txtFileName.value || "http://";
  txtFileName.focus();



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
  

  // error checking

  if (!txtFileName.value) { 
    alert("Image must be selected.");
    txtFileName.focus();
    return;
  }
  if (txtHorizontal.value && !_isValidNumber(txtHorizontal.value)) {
    alert("Horizontal spacing must be a number between 0 and 999.");
    txtHorizontal.focus();
    return;
  }
  if (txtBorder.value && !_isValidNumber(txtBorder.value)) {
    alert("Border thickness must be a number between 0 and 999.");
    txtBorder.focus();
    return;
  }
  if (txtVertical.value && !_isValidNumber(txtVertical.value)) {
    alert("Vertical spacing must be a number between 0 and 999.");
    txtVertical.focus();
    return;
  }

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

  //include absolute URL here
  elmImage.src = "<?php echo $app_url."images/articles/depot/";?>"+txtFileName.value;
  
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

<link href="../../cms_style.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0"  rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" scroll="no" onLoad="Init()" id="bdy">
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="200"><div id="filelist">
        <select name="txtFileName" id="txtFileName" size="20" class="formfields" style="width:200" onClick="Get_URL()" onChange="Get_URL()" >
          <?php 
//load all image file names
if ($handle = opendir('../../images/articles/depot')) {
    while (false !== ($file = readdir($handle))) { 
        if ($file != "." AND $file != ".." AND $file != "preview") { 
			$filelist[] = $file;  // insert each filename as an array element
		}
    }
    closedir($handle); 
}

	//echo results
	 natcasesort($filelist);  // sort the array
		reset ($filelist);
		while (list ($key, $val) = each ($filelist)) // give it out sorted
			{
				echo "<option value=\"".$val."\">".$val."</option>";				
			}
?>
        </select>
      </div></td>
    <td width="5">&nbsp;</td>
    <td width="395"><table width="395" height="286" border="0" cellpadding="0" cellspacing="0" class="okvir">
        <tr> 
          <td> <iframe id="imageFrame" name="imageFrame" src="image_selected.php" width="395" height="284" scrolling="Yes" frameborder="0"><span class="maintext"><strong>imageFrame</strong> 
            iFrame is here</span></iframe></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="90"> <table width="199" height="100%" border="0" cellpadding="0" cellspacing="0" class="okvir3">
        <tr> 
          <td align="left" valign="top"> <iframe id="manageFrame" name="manageFrame" src="image_manage.php" width="198" height="90" scrolling="no" frameborder="0"><span class="maintext"><strong>manageFrame</strong> 
            iFrame is here</span></iframe> </td>
        </tr>
      </table></td>
    <td>&nbsp;</td>
    <td height="90"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" class="okvir2">
        <tr> 
          <td align="left" valign="top"><table width="394" height="5" border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td></td>
              </tr>
            </table>
            <table width="394" border="0" cellspacing="0" cellpadding="0">
              <tr align="left" valign="middle" class="maintext"> 
                <td width="26%">&nbsp;&nbsp;File size:</td>
                <td width="25%"> <div class="maintext" id="filesize"></div></td>
                <td width="22%">Alternate text:</td>
                <td width="27%"><input name="txtAltText" type=text class="formfields" id="txtAltText" style="width: 90px; " onfocus="select()"></td>
              </tr>
              <tr align="left" valign="middle" class="maintext"> 
                <td width="26%">&nbsp;&nbsp;Alignment:</td>
                <td width="25%"> <select class="formfields" name="selAlignment" size=1 id="selAlignment" style="width: 6.72em; ">
                    <option value="" selected id=optNotSet> Not set </option>
                    <option id=optLeft value=left> Left </option>
                    <option id=optRight value=right> Right </option>
                    <option id=optTexttop value=textTop> Texttop </option>
                    <option value=absMiddle id=optAbsMiddle> Absmiddle </option>
                    <option id=optBaseline value=baseline> Baseline </option>
                    <option id=optAbsBottom value=absBottom> Absbottom </option>
                    <option id=optBottom value=bottom> Bottom </option>
                    <option id=optMiddle value=middle> Middle </option>
                    <option id=optTop value=top> Top </option>
                  </select></td>
                <td width="22%">H space:</td>
                <td width="27%"><input name="hspace" type=text class="formfields" id="txtHorizontal" style="width: 4.2em; ime-mode: disabled;" value="0" size=3 maxlength=3></td>
              </tr>
              <tr align="left" valign="middle" class="maintext"> 
                <td width="26%">&nbsp;&nbsp;Border Thickness: </td>
                <td width="25%"> <input name="txtBorder" id="txtBorder" class="formfields" type="text" style="width: 6.72em; ime-mode: disabled;" value="0" size=3 maxlength=3 ></td>
                <td width="22%">V space: </td>
                <td width="27%"><input name="txtVertical" type=text class="formfields" id="txtVertical" style="width: 4.2em; ime-mode: disabled;" value="0" size=3 maxlength=3></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="38" colspan="3"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" class="okvir4">
        <tr>
          <td align="right"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" class="okvir2">
              <tr> 
                <td align="right"> 
                  <input name="btnOK" type="submit" class="formfields2" id="btnOK" style="width: 73px; height: 23px;" value="OK"  onClick="btnOKClick()"> 
                  <input name="btnCancel" type="reset" class="formfields2" id="btnCancel" style="width: 73px; height: 23px;" onClick="window.close();" value="Cancel">
                  &nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table> </td>
  </tr>
</table>

</body>
</html>

