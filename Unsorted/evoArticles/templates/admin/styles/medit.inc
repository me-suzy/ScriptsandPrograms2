<table border="0" cellpadding="4" cellspacing="1" align="center" width="100%">
<tr>
<td valign="top">
<small>
<b>Note :</b><br />
Hold down CTRL to choose multiple templates<br /><br />
<form method='post' action='$PHP_SELF?do=multiple&dir=$dir'>
$tpllist
<br /><br />
<div align="center"><input type="submit" value="Select" name="mselect" /></div>
</form>
</td>
<td width="75%">

<form method='post' action='$PHP_SELF?do=multiple&dir=$dir'>
<input type="hidden" name="back" value="$_SERVER[REQUEST_URI]" />
  <table border=0 cellpadding=0 cellspacing=0 align=center width=100%>
    <tr>
      <td class=tblborder>
      <table border=0 width=100% cellpadding=4 cellspacing=1 align=center>
        <tr>
	  <td class=tblhead colspan=2>
	  $evoLANG[styles_edittpl]
	  </td>
	</tr>
	$tplloop
	<tr>
	  <td colspan=2 align=center class=tblhead> 
	    <input type=submit value="$evoLANG[button_go]" name="edittpl" /> </td>
	</tr>
      </table>

      </td>
    </tr>
  </table>
</form>
</td>
</tr>
</table>