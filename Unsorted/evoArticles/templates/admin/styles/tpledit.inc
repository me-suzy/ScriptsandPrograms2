<script language="javascript">
function preview()
{
	var css = confirm("Do you want it previewed with CSS?");
	if (css != false)
	{
	     var css_file = "<link rel=stylesheet href=$root/$cssfile>\\n";
	}
	else
	{
	     var css_file = "";
	}

	var name = document.editor.name.value;
	var content = document.editor.content.value;
	doc = window.open("",""," width=400, height=450 toolbar=no, statusbar=no, scrollbars=yes");
	doc.document.write ("<title> Preview :- "+name+".htm </title>\\n");
	doc.document.write (css_file+"\\n");
	doc.document.write( content );
}
</script>
<b>[<a href="templates.php?do=makefile&f=../styles/$_GET[dir]">Add New Template </a>]</b><br />
<b>$evoLANG[styles_tplchoose]</b> : $templates_list <b>For :</b> $styleslist <br />
<form method=post action="$PHP_SELF?do=submit" name="editor">
<input type="hidden" name="back" value="$_SERVER[REQUEST_URI]" />
<table border=0 cellpadding=0 cellspacing=0 width=100% align=center>
  <tr>
    <td class=tblborder>
    <table border=0 cellpadding=4 cellspacing=1 width=100% align=center>
      <tr>
        <td class=tblhead colspan=2>
	$evoLANG[templatefileedit]
	</td>
      </tr>
      <tr>
        <td class=firstalt> <b>Filename</b> </td>
	<td class=secondalt width=80%> <b class=title style='font-size:10pt'>$filename</b>
	<input type=hidden name="name" value=$filename />
	<input type=hidden name="url" value="$f_" />
	</td>
      </tr>
      $additional
      <tr>
        <td class=secondalt width=80% colspan="2" align="center">
	<textarea name=content rows="20" cols="75" style='font-size: 9pt;background-color:white;font-family: courier new;color:black'>$filecontent</textarea>
	</td>
      </tr>
      $add_row
      <tr>
        <td class=tblhead colspan=2 align=center>
	  <input type=submit value='$evoLANG[button_go]' name='editfile' accesskey='s' style="font-weight:bold" />
	  <input type=button value="preview" $add onclick="javascript:preview()" style="font-weight:bold" />
	</td>
      </tr>
    </table>
    </td>
  </tr>
</table>
</form>