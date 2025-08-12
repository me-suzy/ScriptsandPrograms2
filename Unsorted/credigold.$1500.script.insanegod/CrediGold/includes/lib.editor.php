<?

function extractEditor($act = "index.php")

   {

      global $_PHPLIB, $PHP_SELF;

?>

<table id="tblCoolbar" width=500 cellpadding="0" cellspacing="0" align=center>

<tr>

   <td><div class="cbtn" onClick="cmdExec('cut')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="1" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/cut.gif" alt="Cut">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('copy')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="1" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/copy.gif" alt="Copy">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('paste')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="1" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/paste.gif" alt="Paste">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('selectall')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/newdoc.gif"  width=22 height=23 alt="Select All">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('bold')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="1" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/bold.gif" alt="Bold">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('italic')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="1" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/italic.gif" alt="Italic">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('underline')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="1" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/under.gif" alt="Underline">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('justifyleft')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="1" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/left.gif" alt="Justify Left">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('justifycenter')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="1" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/center.gif" alt="Center">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('justifyright')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="1" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/right.gif" alt="Justify Right">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('insertorderedlist')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/numlist.gif" alt="Ordered List">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('insertunorderedlist')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/bullist.gif" alt="Unordered List">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('outdent')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/deindent.gif" alt="Decrease Indent">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('indent')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/inindent.gif" alt="Increase Indent">

   </div></td>



</tr>

<tr>



   <td><div class="cbtn" onClick="cmdExec('InsertHorizontalRule')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/hr.gif"  width=23 height=22 alt="New HR">

   </div></td>



   <td><div class="cbtn" onClick="foreColor()" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/fgcolor.gif" alt="Forecolor">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('createLink')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/link.gif" alt="Link">

   </div></td>



   <td><div class="cbtn" onClick="newBookmark()" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/anchor.gif" width=23 height=22 alt="New Bookmark">

   </div></td>



   <td><div class="cbtn" onClick="insertImage()" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/image.gif" alt="Image">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('InsertParagraph')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/paragraph.gif"  width=23 height=22 alt="New Paragraph">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('InsertInputSubmit')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/submit.gif"  width=23 height=22 alt="New Submit Button">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('InsertInputButton')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/button.gif"  width=23 height=22 alt="New Button">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('InsertInputRadio')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/radio.gif"  width=23 height=22 alt="New Radio Button">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('InsertInputCheckbox')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/checkbox.gif"  width=23 height=22 alt="New Checkbox">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('InsertSelectDropdown')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/select.gif"  width=23 height=22 alt="New Select Field">

   </div></td>



   <td><div class="cbtn" onClick="cmdExec('InsertTextArea')" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onmouseup="button_up(this);">

   <img hspace="2" vspace=1 align=absmiddle src="<?=$_PHPLIB["http_path"]?>/images/editor/textarea.gif"  width=23 height=22 alt="New Text Area">

   </div></td>

</tr>

<tr>



</tr>

<tr valign="middle">

   <td colspan=16>

      <select onchange="cmdExec('formatBlock',this[this.selectedIndex].value);this.selectedIndex=0">

         <option selected>Style</option>

         <option value="Normal">Normal</option>

         <option value="Heading 1">Heading 1</option>

         <option value="Heading 2">Heading 2</option>

         <option value="Heading 3">Heading 3</option>

         <option value="Heading 4">Heading 4</option>

         <option value="Heading 5">Heading 5</option>

         <option value="Address">Address</option>

         <option value="Formatted">Formatted</option>

         <option value="Definition Term">Definition Term</option>

      </select>

      <select onchange="cmdExec('fontname',this[this.selectedIndex].value);">

         <option selected>Font</option>

         <option value="Arial">Arial</option>

         <option value="Arial Black">Arial Black</option>

         <option value="Arial Narrow">Arial Narrow</option>

         <option value="Comic Sans MS">Comic Sans MS</option>

         <option value="Courier New">Courier New</option>

         <option value="System">System</option>

         <option value="Tahoma">Tahoma</option>

         <option value="Times New Roman">Times New Roman</option>

         <option value="Verdana">Verdana</option>

         <option value="Wingdings">Wingdings</option>

      </select>

      <select onchange="cmdExec('fontsize',this[this.selectedIndex].value);">

         <option selected>Size</option>

         <option value="1">1</option>

         <option value="2">2</option>

         <option value="3">3</option>

         <option value="4">4</option>

         <option value="5">5</option>

         <option value="6">6</option>

         <option value="7">7</option>

         <option value="8">8</option>

         <option value="10">10</option>

         <option value="12">12</option>

         <option value="14">14</option>

      </select>



   </td>



</tr>

</table>

<script language="JavaScript">

<!--

function insertImage()

   {

      if (isHTMLMode)

         {

            alert("Please uncheck 'Edit HTML'");return;

         }

      var sImgSrc=prompt("Enter the URL of the image you want to insert.\nFor images you uploaded on the server just type the name of the image: ", "");

      if(sImgSrc!=null) cmdExec("InsertImage",sImgSrc);

   }

//-->

</script>

<?

   }

function loadCSS()

   {

?>

<STYLE TYPE="text/css">

TABLE#tblCoolbar

   {

   background-color:threedface; padding:1px; color:menutext;

   border-width:1px; border-style:solid;

   border-color:threedhighlight threedshadow threedshadow threedhighlight;

   }

.cbtn

   {

   height:18;

   BORDER-LEFT: threedface 1px solid;

   BORDER-RIGHT: threedface 1px solid;

   BORDER-TOP: threedface 1px solid;

   BORDER-BOTTOM: threedface 1px solid;

   }

.txtbtn {font-family:tahoma; font-size:70%; color:menutext;}

</STYLE>

<?

   }

function endEditor($formName = "form0", $targetURL = "index.php", $buttonText = "Submit", $ads = "")

   {

      global $dc;

      global $_PHPLIB, $PHP_SELF;

?>

<center><iframe width="500" id="idContent" height="350" src="<?=$targetURL?>"></iframe></center>

<form name=butts action=#>

<table width=500 align=center>

<tr><td align=center class=text>

<input type="checkbox" onclick="setMode(this.checked)"> Switch to HTML Mode

<br><br>

<input type=button value="<?=$buttonText?>" name=pageMe onCLick=setup()>



</font>

</td></tr>

</table>

</form>

<script language="JavaScript" src=<?=$_PHPLIB["http_path"]?>/modules/engine.js></script>

<script>

function setup()

   {

      f = document.<?=$formName?>;

      f.cont.value = idContent.document.body.innerHTML;

      <?=$ads?>

      f.submit();

   }

</script>

<?

   }

function loadTemplates()

   {

      global $dc, $_Config;

      $dc->query("SELECT * FROM ".$_Config["database_pages"].";");

      for ($i=0;$i<$dc->num_rows();$i++)

         {

            $dc->next_record();

            $rules = ($dc->get("id") == get_param("tempID"))?"selected":"";

?>

<option value="<?=$dc->get("id")?>" <?=$rules?>><?=$dc->get("explain")?></option>

<?

         }

   }



function loadEmails()

   {

      global $dc, $_Config;

      $dc->query("SELECT * FROM ".$_Config["database_emails"].";");

      for ($i=0;$i<$dc->num_rows();$i++)

         {

            $dc->next_record();

            $rules = ($dc->get("id") == get_param("tempID"))?"selected":"";

?>

<option value="<?=$dc->get("id")?>" <?=$rules?>><?=$dc->get("name")?></option>

<?

         }

   }

?>