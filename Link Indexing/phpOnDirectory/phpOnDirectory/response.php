<?php
# choose a banner
include_once('includes/db_connect.php');
include_once('banner.inc.php');
include('Templates/maintemplate.header.inc.php');
?>
        <table border="0" cellpadding="0" cellspacing="0" width="95%">
          <tr>
            <td width="100%" height="30" colspan="3">
              <h1>Add Your Site</h1></td>
          </tr>
          <tr>
            <td width="100%" valign="top" align="left" colspan="3">
            </td>
          </tr>
          <tr>
            <td width="100%" colspan="3">
  <p align="left">Thank you for applying to be
  listed on our directory.</p>
  <p align="left">You're request has been sent and
  will be reviewed in the next few days. Please now cut-and-paste the code below
  into am html page on your site. This page should be no further than one click
  away from the home page and should be accessible to the search engines (i.e.
  indexed)</p>
  <p><img border="0" src="images/ondating_1.gif" width="120" height="60"><br>
      <textarea rows="5" cols="46" name="txtLinkCode" tabindex="7"><font face="Verdana" size="1">
<a href="<?php echo $CONST_LINK_ROOT ?>"><img ALT='Click here' border="0" src="<?php echo $CONST_LINK_ROOT ?>/images/ondating_1.gif" width="120" height="60"><br>
<?=$CONST_LINK_SITE?></a></font></textarea>
  </p>
  <p>        <img border="0" src="images/ondating_2.gif" width="100" height="30"><br>
        <textarea rows="5" cols="46" name="txtLinkCode" tabindex="7"><font face="Verdana" size="1">
<a href="<?php echo $CONST_LINK_ROOT ?>"><img ALT='Click here' border="0" src="<?php echo $CONST_LINK_ROOT ?>/images/ondating_2.gif" width="100" height="30"><br>
<?=$CONST_LINK_SITE?></a></font></textarea>
  </p>
  <p>          <img border="0" src="images/ondating_3.gif" width="88" height="31"><br>
          <textarea rows="5" cols="46" name="txtLinkCode" tabindex="7"><font face="Verdana" size="1">
<a href="<?php echo $CONST_LINK_ROOT ?>"><img ALT='Click here' border="0" src="<?php echo $CONST_LINK_ROOT ?>/images/ondating_3.gif" width="88" height="31"><br>
<?=$CONST_LINK_SITE?></a></font></textarea>
          <br>
          <br>
  </p>
  <hr>
  <p align="left"><b>Webmasters: </b>If you want to earn <u> extra income</u> from your
  dating related website, check out the <a class="menu" href="<?php echo $CONST_LINK_ROOT ?>/affiliate_program.php">affiliate
  programs</a>. </p>
  <p align="left">You can <u>list your own affiliate program</u> for free. Click
  'ADD URL' and select the affiliate schemes category for your submission.
  Remember to include details of the commission levels and the link to the
  affiliate scheme details at your site.</p>
  <p align="left">To drive additional high quality traffic for your website, you
  may wish to consider our <a class="menu" href="<?php echo $CONST_LINK_ROOT ?>/advertise.php">advertising
  options</a>.</p>
  <p align="left">Thank you for listing your site.</p>
  <p align="left"><i><?php echo $CONST_LINK_SITE ?></i></p>
  <p align="left">
            </td>
          </tr>
        </table>
<?include('Templates/maintemplate.footer.inc.php');?>
