<script language="javascript">
function Check_Form()
{

        if (document.cmt.name.value == "" )
        {
                alert("Invalid Value: $evoLANG[name]");
                return false;
        }

        if (document.cmt.email.value == "" )
        {
                alert("Invalid Value: $evoLANG[email]");
                return false;
        }

        if (document.cmt.comments.value == "" )
        {
                alert("Invalid Value: $evoLANG[comments]");
                return false;
        }
}
</script>

<form action="$_SERVER[PHP_SELF]?do=comment" method="post" name="cmt" onSubmit="return Check_Form();" />
<input type="hidden" value="$userip" name="ip" />
<input type="hidden" value="$id" name="artid" />
<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
<tr>
<td class="tblborder">
<table align="center" cellpadding="4" cellspacing="0" border="0" width="100%">
<tr>
<td class="tblhead" colspan="2" align="left">&nbsp;$evoLANG[addcomment]</td>
</tr>
<tr>
<td class="firstalt" valign="top" width="">
<b>$evoLANG[name]</b></td>
<td class=firstalt align="left">
<input type="text" name="name" value="" size='30' />
</td>
</tr>
<tr>
<td class="secondalt" valign="top" width=""><b>$evoLANG[email]</b></td>
<td class=secondalt align="left"> <input type="text" name="email" value="" size='30' />
</td>
</tr>
<tr>
<td class=firstalt valign="top" width=""><b>$evoLANG[comments]</b></td>
<td class=firstalt align="left"> <textarea name="comments" cols="40" rows="8"></textarea>
</td>
</tr>
<tr>
<td colspan="2" align="center" class="tblhead">
<input type="submit" name="addcomment" value="$evoLANG[addcomment]" />
<input type="reset" value="reset" />
</td>
</tr>
</table>
</td>
</tr>
</table>
</form>


