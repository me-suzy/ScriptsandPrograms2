<script language="javascript">
function Check_Form()
{

        if (document.addart.pid.value == "" )
        {
                alert("Invalid Value: $evoLANG[category]");
                return false;
        }

        if (document.addart.subject.value == "" )
        {
                alert("Invalid Value: $evoLANG[subject]");
                return false;
        }

        if (document.addart.summary.value == "" )
        {
                alert("Invalid Value: $evoLANG[summary]");
                return false;
        }

        $more_validation

        $check_article

        return true;
}

function pop_form(formname)
{
        formname.target = "_blank";
}

</script>

<form action='$_SERVER[PHP_SELF]?do=submit' method='post' name='$art[formname]' onsubmit="return Check_Form();" enctype="multipart/form-data">
<table border="0" cellpadding="0" cellspacing="0" align="center" width="95%">
  <tr>
    <td class="tblborder">
      <table border="0" cellpadding="4" cellspacing="1" align="center" width="100%">
        <tr>
          <td class="tblhead" colspan="2">
          Add Article
          <input type="hidden" name="uniqueid" value="$art[uniqid]" />
          </td>
        </tr>

        <!-- author -->
        <tr class="firstalt">
          <td valign="top">
          <b> Author </b>
          </td>
          <td width="75%" valign="top">
            $art[author]
          </td>
        </tr>

        <!-- category -->
        <tr class="firstalt">
          <td valign="top">
          <b> Category </b>
          </td>
          <td valign="top">
            $art[category]
          </td>
        </tr>

        <!-- date -->
        <tr class="secondalt">
          <td valign="top">
          <b> $evoLANG[datepublished] </b>
          </td>
          <td valign="top">
            $art[date]
          </td>
        </tr>

        <!-- subject -->
        <tr class="secondalt">
          <td valign="top">
          <b> $evoLANG[subject] </b>
          </td>
          <td valign="top">
            <input type="text" name="subject" size="25" />
          </td>
        </tr>

        <!-- summary -->
        <tr class="firstalt">
          <td valign="top">
          <b> $evoLANG[summary] </b> <br /> $evoLANG[d_summary]
          </td>
          <td valign="top">
            <textarea name="summary" cols="40" rows="5"></textarea>
          </td>
        </tr>

        <!-- conditionals: do not remove -->
        [IF:isadmin]
        <!-- is featured? -->
        <tr class="secondalt">
          <td valign="top">
          <b> $evoLANG[featured] </b>
          </td>
          <td valign="top">
            $art[featured]
          </td>
        </tr>
        [/IF]
        <!-- end conditionals -->

        <!-- enable Auto-BR -->
        <tr class="firstalt">
          <td valign="top">
          <b> $evoLANG[autobr] </b><br />$evoLANG[d_autobr]
          </td>
          <td valign="top">
            $art[autobr]
          </td>
        </tr>

        <!-- assign style -->
        [IF:isadmin]
        <tr class="firstalt">
          <td valign="top">
          <b> $evoLANG[assignstyle] </b><br />$evoLANG[d_assignstyle]
          </td>
          <td valign="top">
            $art[assignstyle]
          </td>
        </tr>
        [/IF]

        <!-- custom meta tag rows -->
        $custom_meta
        <!-- /custom meta tag rows -->

        <!-- buttons -->
        <tr class="tblhead">
          <td colspan="2" align="center">
            $art[buttons]
          </td>
        </tr>

        <!-- article -->
        <tr class="secondalt">
          <td valign="top">
          <b> $evoLANG[article] </b>
          </td>
          <td valign="top">
            $art[article]
          </td>
        </tr>

        <!-- support file -->
        <tr class="secondalt">
          <td valign="top">
          <b> $evoLANG[supportfile] </b><br />
          $evoLANG[d_supportfile]

          </td>
          <td valign="top">
            <input type="file" name="support" /><br /> $uploadinfo
          </td>
        </tr>

        <!-- article image -->
        $art[articleimage]

        <!-- use comment -->
        $art[usecomment]

        <!-- use rating -->
        $art[userating]

        <!-- custom  -->
        $custom_fields
        <!-- /custom -->

        <!-- related articles -->
        $art[relatedart]

        <!-- submit row -->
        <tr class="tblhead">
          <td colspan="2" align="center">
            <input type="submit" name="addarticle" value="submit" />
            <input type="submit" onClick="pop_form($art[formname])" name="preview" value="preview"/>

            <input type="reset" />
          </td>
        </tr>


      </table>
    </td>
  </tr>
</table>


<br />

<div align="center">
  <div style="width:65%;padding:4px">
    <div class="tblhead" style="padding:4px;border-collapse:collapse;border:1px solid #000"> $evoLANG[uploadedimages]</div>
    <div id="uploadedimages"></div>
</div>



