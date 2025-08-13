<script language="javascript">
function Check_Form()
{

	if (document.addnew.name.value == "" )
	{
		alert("Invalid Value: $evoLANG[word_name]");
		return false;
	}
	return true;
}

function pop_form(formname)
{
	formname.target = "_blank";
}

</script>

<form method="post" name="addnew" action="$_SERVER[PHP_SELF]?do=submit" onsubmit="return Check_Form();">
  <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
    <tr>
      <td class="tblborder">
      <table border="0" width="100%" cellpadding="4" cellspacing="1" align="center">
        <tr>
	  <td class="tblhead" colspan="2">
	  $evoLANG[styles_add]
	  </td>
	</tr>
	<tr>
	  <td width="30%" class="firstalt"><b> Name </b> </td>
	  <td class="secondalt">
	    <input type="text" name="name" size="40" />
	  </td>
	</tr>
	<tr>
	  <td width=30% class=firstalt><b> Folder Name </b> </td>
	  <td class=secondalt>
	    <input type="text" name="tplfolder" size="40" value="$folder" disabled/>
	    <input type="hidden" name="tplfolder" value="$folder" />
	  </td>
	</tr>

	<tr>
	  <td colspan=2 align=center class=tblhead> 
	    <input type="submit" value="$evoLANG[button_go]" name="add" /> </td>
	</tr>
      </table>

      </td>
    </tr>
  </table>
</form>