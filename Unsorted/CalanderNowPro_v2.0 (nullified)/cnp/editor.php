<?
//////////////////////////////////////////////////////////////////////////////                      
//                                                                          //
//  Program Name         : Calander Now Pro                                 //
//  Program version      : 2.0                                              //
//  Program Author       : Jason VandeBoom                                  //
//  Supplied by          : drew010                                          //
//  Nullified by         : CyKuH [WTN]                                      //
//  Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////                      
//       SCRIPT CONFIGURATION
// Width in pixels of Text Editor Box
$width = "550";

# Height in pixels of Text Editor Box
$height = "250";

# Use pre-existing content?
# If you use pre-existing content, you must enter that 
# content or insert a query on the editor_saved.php page.
# 0 = Yes    1 = No
$precontent = "0";

?>
<html>
<head>
<title>PhpEditor</title>
<script language="JavaScript">
var viewMode = 1;
  function Init()
  {
    iView.document.designMode = 'On';
  }
  function selOn(ctrl)
  {
	ctrl.style.borderColor = '#CCCCCC';
		ctrl.style.backgroundColor = '#FFFFFF';
	ctrl.style.cursor = 'hand';	
  }
  function selOff(ctrl)
  {
	ctrl.style.borderColor = '#D6D3CE';  
	ctrl.style.backgroundColor = '#D6D3CE';
  }
  function selDown(ctrl)
  {
	ctrl.style.backgroundColor = '#CCCCCC';
  }
  function selUp(ctrl)
  {
  }
  function doBold()
  {
	iView.document.execCommand('bold', false, null);
  }
  function doCopy()
  {
	iView.document.execCommand('copy', false, null);
  }
  function doSall()
  {
	iView.document.execCommand('selectall', false, null);
  }
   function doPaste()
  {
	iView.document.execCommand('paste', false, null);
  }
  function doItalic()
  {
	iView.document.execCommand('italic', false, null);
  }
  function doUnderline()
  {
	iView.document.execCommand('underline', false, null);
  }
  function doLeft()
  {
    iView.document.execCommand('justifyleft', false, null);
  }
  function doCenter()
  {
    iView.document.execCommand('justifycenter', false, null);
  }
  function doRight()
  {
    iView.document.execCommand('justifyright', false, null);
  }
  function doOrdList()
  {
    iView.document.execCommand('insertorderedlist', false, null);
  }
  function doCUT()
  {
    iView.document.execCommand('cut', false, null);
  }
  function doPrint()
  {
    iView.document.execCommand('print', false, null);
  }
  function doRef()
  {
    iView.document.execCommand('refresh', false, null);
  }
  function doIndent()
  {
    iView.document.execCommand('indent', false, null);
  }
  function doOutdent()
  {
    iView.document.execCommand('outdent', false, null);
  }
  function doBulList()
  {
    iView.document.execCommand('insertunorderedlist', false, null);
  }
  function doForeCol()
  {
    var fCol = prompt('Enter a color for your text (EX: Blue)', '');
    if(fCol != null)
      iView.document.execCommand('forecolor', false, fCol);
  }
  function doBackCol()
  {
    var bCol = prompt('Enter background color', '');
    if(bCol != null)
      iView.document.execCommand('backcolor', false, bCol);
  }
  function doLink()
  {
    iView.document.execCommand('createlink');
  }
  function doImage()
  {
    var imgSrc = prompt('Enter image location', '');
    if(imgSrc != null)    
     iView.document.execCommand('insertimage', false, imgSrc);
  }
  function doRule()
  {
    iView.document.execCommand('inserthorizontalrule', false, null);
  }
  function doFont(fName)
  {
    if(fName != '')
      iView.document.execCommand('fontname', false, fName);
  }
  function doSize(fSize)
  {
    if(fSize != '')
      iView.document.execCommand('fontsize', false, fSize);
  }
  function doHead(hType)
  {
    if(hType != '')
    {
      iView.document.execCommand('formatblock', false, hType);  
      doFont(selFont.options[selFont.selectedIndex].value);
    }
  }
  function doToggleView()
  {  
    if(viewMode == 1)
    {
      iHTML = iView.document.body.innerHTML;
      iView.document.body.innerText = iHTML;
      tblCtrls.style.display = 'none';
	  //tblCtrls2.style.display = 'none';
      iView.focus();
      viewMode = 2; // Code
    }
    else
    {
      iText = iView.document.body.innerText;
      iView.document.body.innerHTML = iText;
      tblCtrls.style.display = 'inline';
	  //tblCtrls2.style.display = 'inline';
      iView.focus();
      viewMode = 1;
    }
  }
</script>
<style>
  .butClass
  {    
    border: 1px solid;
    border-color: #D6D3CE;
  }
  .tdClass
  {
    padding-left: 3px;
    padding-top:3px;
  }
</style>
<body onLoad="Init()">
<table width="<? print $width; ?>" border="0" cellpadding="0" cellspacing="0" bgcolor="#D6D3CE" id="tblCtrls">
  <tr> 
    <td> <div align="left"><img alt="Bold" class="butClass" src="media/bold.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBold()" width="23" height="22"> 
        <img alt="Italic" class="butClass" src="media/italic.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doItalic()"> 
        <img alt="Underline" class="butClass" src="media/underline.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doUnderline()"> 
        <img alt="Left" class="butClass" src="media/left.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doLeft()"> 
        <img alt="Center" class="butClass" src="media/center.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doCenter()"> 
        <img alt="Right" class="butClass" src="media/right.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doRight()"> 
        <img alt="Outdent" class="butClass" src="media/outdent.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doOutdent()"> 
        <img alt="Indent" class="butClass" src="media/indent.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doIndent()"> 
        <img alt="Text Color" class="butClass" src="media/forecol.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doForeCol()"> 
        <img alt="Background Color" class="butClass" src="media/bgcol.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBackCol()"> 
        <img alt="Ordered List" class="butClass" src="media/ordlist.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doOrdList()"> 
        <img alt="Bulleted List" class="butClass" src="media/bullist.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBulList()"> 
        <img alt="Hyperlink" class="butClass" src="media/link.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doLink()"> 
        <img alt="Image" class="butClass" src="media/image.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="window.open('editor_images.php?id=<?php print $row["id"]; ?>&db=Pages','photoupload','width=400,height=250,scrollbars,resizable');"> 
        <img alt="Horizontal Rule" class="butClass" src="media/rule.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doRule()"> 
        <img alt="Cut" class="butClass" src="media/cut.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doCUT()"> 
        <img alt="Copy" class="butClass" src="media/copy.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doCopy()"> 
        <img alt="Paste" class="butClass" src="media/paste.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doPaste()"> 
        <img alt="Clear All Contents" class="butClass" src="media/sall.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doRef()" width="23" height="22"><br>
        <select name="select" onChange="doFont(this.options[this.selectedIndex].value)">
          <option value="">-- Font --</option>
          <option value="Arial, Helvetica">Arial, Helvetica</option>
          <option value="Times New Roman, Times">Times New Roman, Times</option>
          <option value="Courier New, Courier">Courier New, Courier</option>
          <option value="Verdana">Verdana</option>
          <option value="Wingdings">Wingdings</option>
        </select>
        &nbsp; 
        <select name="select2" onChange="doSize(this.options[this.selectedIndex].value)">
          <option value="" selected>-- Size --</option>
          <option value="1">Very Small</option>
          <option value="2">Small</option>
          <option value="3">Medium</option>
          <option value="4">Large</option>
          <option value="5">Larger</option>
          <option value="6">Very Large</option>
          <option value="7">Extremely Large</option>
        </select>
      </div></td>
  </tr>
</table>
<table width="<? print $width; ?>" border="0" cellspacing="0" cellpadding="0" bgcolor="#D6D3CE">
  <tr> 
    <td> <iframe src="tempinsert.php?&srcloc=<? print $srcloc; ?>" id="iView" style="width: <? print $width; ?>px; height:<? print $height; ?>px"></iframe> 
      <textarea style="display: none" name=Content></textarea> <script>

  function copyValue(f) {

    f.elements.Content.value = "" + iView.document.body.innerHTML + "";

  }

</script> </td>
  </tr>
  <tr> </tr>
</table>
<table width="<? print $width; ?>" border="0" cellspacing="0" cellpadding="0" bgcolor="#D6D3CE">
  <tr> 
    <td height="30"> <table width="230" border="0" cellspacing="0" cellpadding="1" bgcolor="#666666" align="center">
        <tr> 
          <td> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td bgcolor="#F0F0F0"> <div align="right"><font size="1" face="Arial, Helvetica, sans-serif">Switch 
                    between Visual and Code Views &gt; &nbsp;</font></div></td>
                <td width="35" bgcolor="#D6D3CE"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><img alt="HTML VIEW" class="butClass" src="media/mode.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doToggleView()"></font></div></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>

</html>



