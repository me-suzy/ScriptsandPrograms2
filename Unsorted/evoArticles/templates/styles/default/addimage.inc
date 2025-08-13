<!-- $external_js -->
<script language="javascript">

function insert(what,wysiwyg)
{
   if (wysiwyg != 1)
   {
        window.opener.uploadedimages.innerHTML += "<div style='border:1px solid black;padding:4px;width:100%;border-collapse:collapse' align='left' class='firstalt'><b> $evoLANG[image] </b> : $imagename <br /> <b>$evoLANG[imageplaceholder]</b> : "+what+"</div>";
        window.opener.document.$_GET[for].value +=what;
   }
   else
   {
        return what;
   }
}

</script>
<br />
$form
<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%" align="left">
  <tr>
    <td class="tblborder">
      <table border="0" cellpadding="4" cellspacing="1" width="100%">
        $info
      </table>
    </td>
  </tr>
</table>




