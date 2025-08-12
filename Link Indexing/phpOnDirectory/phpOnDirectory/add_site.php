<?php
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
        <p align="left"><b>Adding your site is
        free, PLUS you get 2 links (one link is in a sub-category which improves
        your PageRank in Google).</b> </p>
        <p align="left"><b>Important</b> - In
        order to be listed on these pages you must meet the following criteria:</p>
        <p align="left">1) You MUST place a link
        on a spidered page on your site such as the home page. All links will be
        checked once a month and if removed, you will be removed from our site
        forever. Our link must be visible no further than 1 click from the
        homepage.</p>
        <p align="left">2) You MUST NOT be an
        affiliate site (such that your data comes from another sites database or
        you drive users to another site).</p>
        <p align="left">3) Our site will only
        promote a limited number of sites in each category. This is to increase
        the click-thru rate for each link. We cannot therefore  guarantee
        that your site will be listed in your preferred category.</p>
        <p align="left">Please see the <a class="menu" href="<?php echo $CONST_LINK_ROOT ?>/rules.php">rules
        page</a> for a full list of our listing policies.</p><br>

        <div align="left">
        <table border="0" cellpadding="0" width="100%">
         <form method="post" action="mailto.php" name="FrmAddURL" onsubmit="return Validate_FrmAddURL();">
          <tr>
            <td width="33%" height="30">Site Name</td>
            <td width="67%" height="30"><input name="txtSiteName" type="text" class="input" tabindex="1" size="28">
            </td>
          </tr>
          <tr>
            <td width="33%" height="30">Site URL</td>
            <td width="67%" height="30"><input name="txtURL" type="text" class="input" tabindex="2" size="28">
            </td>
          </tr>
          <tr>
            <td width="33%" height="30">Category</td>
            <td width="67%" height="30"><select name="lstCategories" size="1" class="input" tabindex="3">
				<option>-- Choose the nearest match --</option>
				<?php
					$result=mysql_query("SELECT * FROM dir_categories ORDER BY cat_parent",$link);

					while ($sql_array=mysql_fetch_object($result)) {
						if ($sql_array->cat_parent != $saved_cat_parent)
							print("<option>___________________________________</option>");

						print("<option value=$sql_array->cat_id>$sql_array->cat_parent : $sql_array->cat_child</option>");

						$saved_cat_parent=$sql_array->cat_parent;
					}
				?>
		  </select></td>
          </tr>
          <tr>
            <td width="33%">Description (Max 250 Chars)</td>
            <td width="67%"><textarea name="txtDescription" cols="46" rows="4" class="input" tabindex="4"></textarea>
            </td>
          </tr>
          <tr>
            <td width="33%" height="30">E-mail</td>
            <td width="67%" height="30"><input name="txtEmail" type="text" class="input" tabindex="5" size="28">
            </td>
          </tr>
          <tr>
            <td width="33%" height="30">Link URL **</td>
            <td width="67%" height="30"><input name="txtLinkURL" type="text" class="input" tabindex="6" size="28">
            </td>
          </tr>
          <tr>
            <td width="30%" valign="middle" align="center" height="40"></td>
            <td width="70%" valign="middle" align="left" height="40"><input class="button" type="submit" value="Submit Now" name="submit" tabindex="7"></td>
          </tr>
          <tr>
            <td width="100%" valign="middle" align="center" height="40" colspan="2">
              <p align="left">** Refers to where on YOUR site have you placed
              OUR link.</p></td>
          </tr>
          </form>
        </table>
        </div>
            </td>
          </tr>
        </table>
    <?include('Templates/maintemplate.footer.inc.php');?>