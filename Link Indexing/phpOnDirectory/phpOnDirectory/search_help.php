<?php

# choose a banner

include_once('includes/db_connect.php');
include('Templates/maintemplate.header.inc.php');

?>
<div align="left">
  <table border="0" cellpadding="0" cellspacing="0" width="375" height="375">
    <tr>
      <td height="20" colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td width="20" height="20"></td>
      <td bgcolor="#FFFFFF" height="335" valign="top" align="left">
        <div align="center">
          <center>
          <table border="0" cellpadding="10" cellspacing="0" width="100%">
            <tr>
              <td width="100%"><font color="#333399" class="cattitle"><b>Simple Site Search</b></font>
                <p>This simple search tool is provided to
                help you find the sites that you are most interested in across
                the whole set of categories.</p>
                <p>Type in the keywords or phrases that
                you are interested in seperated by spaces then click the search
                button.</p>
                <p>Sites are displayed in order of relevance.</p>
                <p>A maximum of 25 sites are returned from each search. The more
                keywords you use the more likely you are to find the site that
                meets you requirements.</p>
                <p>&nbsp;</p>
                <p align="center" ><font color="#333399"><a href="javascript:null(0)" class="nav" onClick="window.close();">Close Window</a></font></td>
            </tr>
          </table>
          </center>
        </div>
      </td>
      <td width="20" height="20"> </td>
    </tr>
    <tr>
      <td height="20" colspan="3">&nbsp;</td>
    </tr>
  </table>
</div>
<?include('Templates/maintemplate.footer.inc.php');?>
