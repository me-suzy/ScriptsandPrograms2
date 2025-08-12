<?if(sizeof($files[name]) > 0){?>
<!-- FILE MENU -->
<div id="FM~menuFile" class="menu" >
<div style="border-top:1px solid white;border-left:1px solid white;border-bottom:1px solid gray;border-right:1px solid gray;background-color:whitesmoke;padding:4px;padding-left:6px;">
<img src="images/interface/x.gif" title="close menu" alt="x" width="5" height="5" border="0" alt="" style="border:1px solid silver;position:absolute;top:2px;right:2px;cursor:hand;" onClick="javascript:iconListSelOff(getCurSelIconIndex(),true)"><strong><a href="javascript:down(escape(icons[getCurSelIconIndex()]['obj'].getAttribute('title')))" class="item">download</a></strong>
<div class="separator">&nbsp;</div>
<a href="javascript:document.filemanform.act.value='multiarch';document.filemanform.submit();" class="item">add to zip</a>
<span id="FM~menuExtra">
</span>
<br>
<a href="javascript:launchEdit('0');" class="item">edit as text</a>
<br>
<a href="javascript:sendByEmail();" class="item">send by email</a>
<?if (!eregi("windows", php_uname())) {?>
<br>
<a href="javascript:chmod('1');" class="item">edit permissions</a>
<?}?>
<div class="separator">&nbsp;</div>
<a href="javascript:document.filemanform.act.value='copy';document.filemanform.submit();" class="item">copy</a>
<br>
<a href="javascript:document.filemanform.act.value='cut';document.filemanform.submit();" class="item">cut</a>
<div class="separator">&nbsp;</div>
<a href="javascript:rename_prompt();" class="item">rename</a>
<br>
<a href="javascript:delete_prompt();" class="item">delete</a>
</div>
</div>

<!-- DIR MENU -->

<div id="FM~menuDir" class="menu" >
<div style="border-top:1px solid white;border-left:1px solid white;border-bottom:1px solid gray;border-right:1px solid gray;background-color:whitesmoke;padding:4px;padding-left:6px;">
<img src="images/interface/x.gif" title="close menu" alt="x" width="5" height="5" border="0" alt="" style="border:1px solid silver;position:absolute;top:2px;right:2px;cursor:hand;" onClick="javascript:iconListSelOff(getCurSelIconIndex(),true)">
<strong><a href="javascript:browse(icons[getCurSelIconIndex()]['obj'].getAttribute('title'))" class="item">browse</a></strong>
<div class="separator">&nbsp;</div>
<a href="javascript:document.filemanform.act.value='multiarch';document.filemanform.submit();" class="item">add to zip</a>
<?if (!eregi("windows", php_uname())) {?>
<br>
<a href="javascript:chmod('1');" class="item">edit permissions</a>
<?}?>
<div class="separator">&nbsp;</div>
<a href="javascript:document.filemanform.act.value='copy';document.filemanform.submit();" class="item">copy</a>
<br>
<a href="javascript:document.filemanform.act.value='cut';document.filemanform.submit();" class="item">cut</a>
<div class="separator">&nbsp;</div>
<a href="javascript:rename_prompt();" class="item">rename</a>
<br>
<a href="javascript:delete_prompt();" class="item">delete</a>
</div>
</div>
<!-- MULTIPLE SELECTION DIR -->

<div id="FM~multiple" class="menu" >
<div style="border-top:1px solid white;border-left:1px solid white;border-bottom:1px solid gray;border-right:1px solid gray;background-color:whitesmoke;padding:4px;padding-left:6px;">
<img src="images/interface/x.gif" title="close menu" alt="x" width="5" height="5" border="0" alt="" style="border:1px solid silver;position:absolute;top:2px;right:2px;cursor:hand;" onClick="javascript:iconListSelect('none')">
<a href="javascript:document.filemanform.act.value='multiarch';document.filemanform.submit();" class="item">add to zip</a>
<?if (!eregi("windows", php_uname())) {?>
<br>
<a href="javascript:chmod('2');" class="item">edit permissions</a>
<?}?>
<div class="separator">&nbsp;</div>
<a href="javascript:document.filemanform.act.value='copy';document.filemanform.submit();" class="item">copy</a>
<br>
<a href="javascript:document.filemanform.act.value='cut';document.filemanform.submit();" class="item">cut</a>
<div class="separator">&nbsp;</div>
<a href="javascript:delete_prompt();" class="item">delete</a>
</div>
</div>
<?}?>