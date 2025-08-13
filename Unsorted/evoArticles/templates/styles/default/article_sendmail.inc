<script language="javascript">
function Check_Form()
{
        if (document.forms_1.name.value == "" )
        {
                alert("Invalid Value: $evoLANG[from_to]"); return false;
        }

        if (document.forms_1.email.value == "" )
        {
                alert("Invalid Value: $evoLANG[from_email]"); return false;
        }

        if (document.forms_1.to_name.value == "" )
        {
                alert("Invalid Value: $evoLANG[send_to]"); return false;
        }

        if (document.forms_1.to_email.value == "" )
        {
                alert("Invalid Value: $evoLANG[send_email]"); return false;
        }

        if (document.forms_1.subject.value == "" )
        {
                alert("Invalid Value: Subject"); return false;
        }

        if (document.forms_1.message.value == "" )
        {
                alert("Invalid Value: Message"); return false;
        }
        return true;
 }
</script>

<form action="$_SERVER[PHP_SELF]?email/id:$id" method="post" name="forms_1" onsubmit="return Check_Form();">
<table border="0" cellpadding="0" cellspacing="0" align="center" width="95%">
  <tr>
    <td class="tblborder">

      <table align="center" cellpadding="4" cellspacing="1" border="0" width="100%">
        <tr>
          <td class="tblhead" colspan="2" align="left">
          $evoLANG[sendfriend]
          </td>
        </tr>
        <tr>
          <td class="firstalt" valign="top" width="30%">
            <b>$evoLANG[from_to]</b>
          </td>
          <td class="firstalt" align="left">
            <input type="text" name="name" value="" size="30" />
          </td>
        </tr>
        <tr>
          <td class="firstalt" valign="top" width="30%">
            <b>$evoLANG[from_email]</b>
          </td>
          <td class="firstalt" align="left">
            <input type="text" name="email" value="" size="30" />
          </td>
        </tr>
        <tr>
          <td class="firstalt" valign="top" width="30%">
            <b>$evoLANG[send_to]</b>
          </td>
          <td class="firstalt" align="left">
            <input type="text" name="to_name" value="" size="30" />
          </td>
        </tr>
        <tr>
          <td class="firstalt" valign="top" width="30%">
            <b>$evoLANG[send_email]</b>
          </td>
          <td class="firstalt" align="left">
            <input type="text" name="to_email" value="" size="30" />
          </td>
        </tr>
        <tr>
          <td class="firstalt" valign="top" width="30%">
            <b>$evoLANG[subject]</b>
          </td>
          <td class="firstalt" align="left">
            <input type="text" name="subject" value="$evoLANG[article] : $article_name " size="30" />
          </td>
        </tr>
        <tr>
          <td class="secondalt" valign="top" width="30%">
            <b>$evoLANG[message]</b>
          </td>
          <td class="secondalt" align="left">
            <textarea name="message" cols="60" rows="12">
            $evoLANG[sendfriend_email]
            $article_url

            </textarea>
          </td>
        </tr>
        <tr>
          <td class="firstalt" valign="top" width="30%">
            <b>$evoLANG[sendhtmlformat]</b>
          </td>
          <td class="firstalt" align="left">
          $evoLANG[word_yes] <input type="radio" name="usehtml" value="1" />
          $evoLANG[word_no] <input type="radio" name="usehtml" value="0" checked />
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center" class="tblhead">
            <input type="submit" name="sendfriend" value="$evoLANG[button_go]" /> <input type="reset" value="reset" />
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>



