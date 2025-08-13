<script language="JavaScript">
 <!--
  function changecolor(name,color) {
   name.style.backgroundColor = color;
  }
 //-->
</script>

$done
<form method='post' name="editstyle" action='$PHP_SELF?do=edit&id=$id'>
  <table border=0 cellpadding=0 cellspacing=0 align=center width=100%>
    <tr>
      <td class=tblborder>
      <table border=0 width=100% cellpadding=4 cellspacing=1 align=center>
        <tr>
	  <td class=tblhead colspan=2>
	  $evoLANG[styles_edit]
	  <input type="hidden" name="id" value="$id" />
	  </td>
	</tr>
	<tr>
	  <td width=30% class=firstalt><b> Name </b> </td>
	  <td class=secondalt>
	    <input type=text name="name" size=40 value="$row[name]" />
	  </td>
	</tr>
	<tr>
	  <td width=30% class=firstalt><b> Folder Name </b> </td>
	  <td class=secondalt>
	    <input type=text name=tplfolder size=40 value="$row[tplfolder]" />
	  </td>
	</tr>

	<!--<tr>
	  <td colspan=2 align=center class=tblhead> 
	    <input type=submit value="Edit Style Name" name="edit" /> </td>
	</tr>-->
      </table>

      </td>
    </tr>
  </table>
 <br />

  <table border=0 cellpadding=0 cellspacing=0 align=center width=100%>
    <tr>
      <td class=tblborder>
      <table border=0 width=100% cellpadding=4 cellspacing=1 align=center>
        <tr>
	  <td class=tblhead colspan=2>
	  $evoLANG[styles_global] 
	  <input type="hidden" name="id" value="$id" />
	  </td>
	</tr>
	$tplloop
	<!-- <tr>
	  <td colspan=2 align=center class=tblhead> 
	    <input type=submit value="Edit Main Templates" name="edittpl" /> </td>
	</tr> -->
      </table>

      </td>
    </tr>
  </table>

<br />
$replacements

  <table border=0 cellpadding=0 cellspacing=0 align=center width=100%>
    <tr>
      <td class=tblborder>
      <table border=0 width=100% cellpadding=4 cellspacing=1 align=center>
        <tr>
	  <td colspan=2 align=center class=tblhead> 
	    <input type=submit value="Edit Style" name="edit" /> </td>
	</tr>
      </table>

      </td>
    </tr>
  </table>
</form>