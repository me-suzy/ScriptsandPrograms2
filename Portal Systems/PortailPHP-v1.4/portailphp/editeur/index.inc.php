 <input type='hidden' name="EditorValue" value='<?php echo $valeur; ?>' >
   <table border="1" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC" width="60%"  bordercolor="#CCCCCC">
    <tr valign="top"> 
      <td> 
        <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
          <tr valign="top"> 
            <td valign="top"> 
              <div id=editbar > 
                <table width="100%" border="0" cellpadding="0" cellspacing="0" align="left">
                  <tr> 
                    <td> 
                      <table border="0" cellpadding="0" cellspacing="0">
                        <tr> 
                          <td> 
                            <table border="0">
                              <tr valign="baseline"> 
                                <td nowrap> <img class='clsCursor' src="editeur/editor_images/new.gif" width="16" height="16" border="0" alt="Start Over / New File" onClick="newFile();">&nbsp
                                  <img class='clsCursor' src="editeur/editor_images/cut.gif" width="16" height="16" border="0" alt="Cut " onClick="doFormat('Cut')">&nbsp
                                  <img class='clsCursor' src="editeur/editor_images/copy.gif" width="16" height="16" border="0" alt="Copy" onClick="doFormat('Copy')">&nbsp
                                  <img class='clsCursor' src="editeur/editor_images/paste.gif" border="0" alt="Paste" onClick="doFormat('Paste')" width="16" height="16">&nbsp
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td> 
                            <table border="0">
                              <tr valign="baseline"> 
                                <td nowrap> <img class='clsCursor' src="editeur/editor_images/para_bul.gif" width="16" height="16" border="0" alt="Bullet List" onClick="doFormat('InsertUnorderedList');" >&nbsp
                                  <img class='clsCursor' src="editeur/editor_images/para_num.gif" width="16" height="16" border="0" alt="Numbered List" onClick="doFormat('InsertOrderedList');" >&nbsp
                                  <img class='clsCursor' src="editeur/editor_images/indent.gif" width="20" height="16" alt="Indent" onClick="doFormat('Indent')">&nbsp
                                  <img class='clsCursor' src="editeur/editor_images/outdent.gif" width="20" height="16" alt="Outdent" onClick="doFormat('Outdent')">&nbsp
                                  <img class='clsCursor' src="editeur/editor_images/hr.gif" width="16" height="18" alt="HR" onClick="doFormat('InsertHorizontalRule')">&nbsp
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td> 
                            <table border="0">
                              <tr valign="baseline"> 
                                <td nowrap><img src="editeur/editor_images/link.gif" border="0" alt="Link to external site"></td>
                                <td nowrap> 
                                  <select name="what" style="font: 8pt verdana;">
                                    <option value="http://" selected>http://</option>
                                    <option value="mailto:">mailto:</option>
                                    <option value="ftp://">ftp://</option>
                                    <option value="https://">https://</option>
                                  </select>
                                </td>
                                <td> 
                                  <input type="text" name="url" size="35" style="font: 8pt verdana;">
                                </td>
                                <td> 
                                  <input type="button" name="button2" value="Add" onClick="makeUrl();" style="font: 8pt verdana;">
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td><img class='clsCursor' src="editeur/editor_images/help.gif" width="20" height="20" align="middle" alt="Help" onClick="Help_OnClick();">
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr> 
                    <td> 
                      <table border="0">
                        <tr> 
                          <td nowrap valign="baseline"> 
                            <div align="left"> 
                              <select name="font" onChange="doFormat('FontName',document.all.font.value);" style="font: 8pt verdana;">
                                <option value="1" selected >Select Font...</option>
                                <option value="arial">Arial, Helvetica, sans-serif</option>
                                <option value="times" >Times New Roman, Times,serif</option>
                                <option value="courier">Courier New, Courier,mono</option>
                                <option value="georgia">Georgia, Times New Roman</option>
                                <option value="verdana">Verdana, Arial, Helvetica</option>
                              </select>
                              <select name="size" onChange="doFormat('FontSize',document.all.size.value);" style="font: 8pt verdana;">
                                <option value="None" selected>Size</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="+1">+1</option>
                                <option value="+2">+2</option>
                                <option value="+3">+3</option>
                                <option value="+4">+4</option>
                                <option value="+5">+5</option>
                                <option value="+6">+6</option>
                                <option value="+7">+7</option>
                              </select>
                              <img class='clsCursor' src="editeur/editor_images/bold.gif" width="16" height="16" border="0" align="absmiddle" alt="Bold text" onClick="doFormat('Bold')">&nbsp
                              <img class='clsCursor' src="editeur/editor_images/italics.gif" width="16" height="16" border="0" align="absmiddle" alt="Italic text" onClick="doFormat('Italic')">&nbsp
                              <img class='clsCursor' src="editeur/editor_images/underline.gif" width="16" height="16" border="0" align="absmiddle" alt="Underline text" onClick="doFormat('Underline')" >&nbsp
                              <img class='clsCursor' src="editeur/editor_images/left.gif" width="16" height="16" border="0" alt="Align Left" align="absmiddle"  onClick="doFormat('JustifyLeft')">
                              <img class='clsCursor' src="editeur/editor_images/centre.gif" width="16" height="16" border="0" alt="Align Center" align="absmiddle" onClick="doFormat('JustifyCenter')">&nbsp
                              <img class='clsCursor' src="editeur/editor_images/right.gif" width="16" height="16" border="0" alt="Align Right" align="absmiddle"  onClick="doFormat('JustifyRight')">&nbsp
                            </div>
                          </td>
                          <td align="left" nowrap valign="baseline"> 
                            <input type="button" name="btnSwapView" value="View Html" onClick="SwapView_OnClick();" style="width:100px; font: 8pt verdana;">
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </div>
            </td>
          </tr>
          <tr valign="top" align="left"> 
            <td valign="top"> 
              <table width="100%" border="0" height="350">
                <tr valign="top"> 
                  <td width="100%" height="100%"> 
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
                      <tr valign="top"> 
                        <td height="100%" bgcolor="#FFFFFF"><iframe id=myEditor src='<?php echo $chemin;?>/editeur/pd_edit.htm' onFocus="initToolBar(this)" width=100% height=100%></iframe></td>
                      </tr>
                    </table>
                  </td>
                  <td width="9%" align="center"> 
                    <table  bgcolor="#000000" width="74" id="cpick" border="1" cellspacing="0" cellpadding="0" align="center">
                      <tr> 
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                    <input type="text" name="colourp" size="8" value="#000000" style="width:74px; font: 8pt verdana" readonly>
                    <table border=1 bgcolor="#CCCCCC" cellpadding="0" cellspacing="0" width="74" >
                      <tr> 
                        <td bgcolor=white width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('white')"></td>
                        <td bgcolor=gray width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('gray')"></td>
                        <td bgcolor=Scarlet width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Scarlet')"></td>
                        <td bgcolor=brown width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('brown')"></td>
                        <td bgcolor=black width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('black')"></td>
                        <td bgcolor=blue width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('blue')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor=yellow width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('yellow')"></td>
                        <td bgcolor=orange width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('orange')"></td>
                        <td bgcolor=red width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('red')"></td>
                        <td bgcolor=purple width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('purple')"></td>
                        <td bgcolor=Firebrick  width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Firebrick')"></td>
                        <td bgcolor=Maroon width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Maroon')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor=Gold width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Gold')"></td>
                        <td bgcolor=Tan width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Tan')"></td>
                        <td bgcolor=bronze width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('bronze')"></td>
                        <td bgcolor=Magenta width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Magenta')"></td>
                        <td bgcolor=Lavender width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Lavender')"></td>
                        <td bgcolor=Turquoise width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Turquoise')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor=aqua width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('aqua')"></td>
                        <td bgcolor=Aliceblue width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Aliceblue')"></td>
                        <td bgcolor=beige width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('beige')"></td>
                        <td bgcolor=Moccasin width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Moccasin')"></td>
                        <td bgcolor=Linen width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Linen')"></td>
                        <td bgcolor=Orchid width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Orchid')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor=green width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('green')"></td>
                        <td bgcolor=Aquamarine width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Aquamarine')"></td>
                        <td bgcolor=Salmon width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Salmon')"></td>
                        <td bgcolor=Cyan width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Cyan')"></td>
                        <td bgcolor=Khaki width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('Khaki')"></td>
                        <td bgcolor=tomato width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('tomato')"></td>
                      </tr>
                      <tr> 
                        <td bgcolor=darkgreen width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('darkgreen')"></td>
                        <td bgcolor=LightGreen  width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('LightGreen')"></td>
                        <td bgcolor=lightpink width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('lightpink')"></td>
                        <td bgcolor=lightblue width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('lightblue')"></td>
                        <td bgcolor=lightyellow width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('lightyellow')"></td>
                        <td bgcolor=darkblue width="12"><img class="clsCursor" src="blank.gif" height=8 width=10 border=0 onClick="ColorPalette_OnClick('darkblue')"></td>
                      </tr>
                      <tr height=300> 
                      </tr>

                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
 <script language="JavaScript">
  initToolBar("foo");
  window.status  = "Current View: Wysiwyg";
</script>

