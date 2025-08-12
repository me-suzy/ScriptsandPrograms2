<?php
# choose a banner

include_once('includes/db_connect.php');
include_once('banner.inc.php');
include('Templates/maintemplate.header.inc.php');
?>
<style type="text/css">
<!--
.style1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #333333;
	font-weight: bold;
}
-->
</style>


        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td width="100%" height="30">
              <h1>Your heading here </h1>
              </td>
            <td width="50%" height="30" align="right">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" valign="top" align="left">
              <p>&nbsp;</p>
              <p align="center" class="style1">Use this page to add content specific to you site such as your main affiliate sites or information regarding the subject of the directory.</p>
            </td>
          </tr>
          <tr>
            <td width="100%" height="30" colspan="2" valign="top" align="left">
            </td>
          </tr>
          <tr>
            <td width="100%" height="49" colspan="2">
       <p align="center"><a class="menu" href="<?php echo $CONST_LINK_ROOT ?>/index2.php">
       </a></p>
          </td>
          </tr>
          <tr>
            <td height="49" colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td height="49" colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td height="49" colspan="2">&nbsp;</td>
          </tr>
        </table>

<?include('Templates/maintemplate.footer.inc.php');?>
