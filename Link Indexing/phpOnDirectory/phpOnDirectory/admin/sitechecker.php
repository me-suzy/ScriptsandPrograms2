<?php
/*****************************************************
* &copy; copyright 1999 - 2003 Interactive Arts Ltd.
*
* All materials and software are copyrighted by Interactive Arts Ltd.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
include('../includes/db_connect.php');
check_admin();

include("../db_sendmail.inc");
include("../functions.php");
include_once('../pagerank.php');

if (isset($HTTP_GET_VARS['mode'])) {

	$mode=$HTTP_GET_VARS['mode'];

	if ($mode == 'save') {

		$txtDescription=$HTTP_POST_VARS['txtDescription'];
		$txtName=$HTTP_POST_VARS['txtName'];
		$txtEmail=$HTTP_POST_VARS['txtEmail'];
		$lstCategory=$HTTP_POST_VARS['lstCategories'];
		$site_id=$HTTP_POST_VARS['site_id'];
		$txtUrl=$HTTP_POST_VARS['txtUrl'];
		$txtLinkUrl=$HTTP_POST_VARS['txtLinkUrl'];
		$rdoAccept=$HTTP_POST_VARS['rdoAccept'];
		$lstReason=$HTTP_POST_VARS['lstReason'];

		switch ($rdoAccept) {
			case 'Accept':
				$txtDescription=mysql_escape_string($txtDescription);
				$txtName=mysql_escape_string($txtName);

				$result=mysql_query("SELECT * FROM dir_categories WHERE cat_id = '$lstCategory'",$link);
				$sql_cat=mysql_fetch_object($result);
				$main_cat=trim($sql_cat->cat_parent);
				$sub_cat=trim($sql_cat->cat_child);

				$combicat=$main_cat." : ".$sub_cat;

				mysql_query("UPDATE dir_site_list SET 
                                site_name='$txtName', 
                                site_category='$combicat', 
                                site_description='$txtDescription', 
                                site_email='$txtEmail', 
                                site_url='$txtUrl', 
                                site_linkback='$txtLinkUrl', 
                                cat_id='$lstCategory', 
                                site_live='Y' 
                              WHERE site_id=$site_id",$link) or die ("Failure to update table ".mysql_error());

                $params = array(
                    "CONST_LINK_SITE" => $CONST_LINK_SITE,
                    "CONST_LINK_ROOT" => $CONST_LINK_ROOT,
                    "txtUrl" => $txtUrl,
                    "main_cat" => $main_cat,
                    "sub_cat" => $sub_cat,
                    "sitename" => $txtName,
                    "category" => $combicat,
                    "description" => $description,
                    "email" => $txtEmail,
                    "url" => $txtUrl,
                    "linkurl" => $txtLinkUrl,
                );
                list($type,$body) = getTemplateByName("Add_Url_Accept_Email",$params);
                
				send_mail("$txtEmail",$CONST_LINK_EMAIL,$CONST_LINK_SITE." Listing",$body,$type,"ON");
				break;

			case 'Update':
				$txtDescription=mysql_escape_string($txtDescription);
				$txtName=mysql_escape_string($txtName);

				$result=mysql_query("SELECT * FROM dir_categories WHERE cat_id = '$lstCategory'",$link);
				$sql_cat=mysql_fetch_object($result);
				$main_cat=trim($sql_cat->cat_parent);
				$sub_cat=trim($sql_cat->cat_child);

				$combicat=$main_cat." : ".$sub_cat;

				mysql_query("UPDATE dir_site_list SET site_name='$txtName', site_category='$combicat', site_description='$txtDescription', site_email='$txtEmail', site_url='$txtUrl', site_linkback='$txtLinkUrl', cat_id='$lstCategory', site_live='N' WHERE site_id=$site_id",$link) or die ("Failure to update table ".mysql_error());
				break;
			case 'Reject':
				mysql_query("DELETE FROM dir_site_list WHERE site_id=$site_id",$link) or die ("Failure to delete rejected record ".mysql_error());

                $params = array(
                    "CONST_LINK_SITE" => $CONST_LINK_SITE,
                    "CONST_LINK_ROOT" => $CONST_LINK_ROOT,
                    "sitename" => $txtName,
                    "category" => $combicat,
                    "description" => $description,
                    "email" => $txtEmail,
                    "url" => $txtUrl,
                    "linkurl" => $txtLinkUrl,
                    "txtReason" => $txtReason,
                    "lstReason" => $lstReason,
                );
                list($type,$body) = getTemplateByName("Add_Url_Reject_Email",$params);
                    
				send_mail("$txtEmail",$CONST_LINK_EMAIL,$CONST_LINK_SITE." Listing",$body,$type,"ON");

				break;

			case 'Delete':
				mysql_query("DELETE FROM dir_site_list WHERE site_id=$site_id",$link)  or die ("Failure to delete record ".mysql_error());
				break;
		}

	}
}

# Display the first/next record

$query="SELECT * FROM dir_site_list WHERE site_live='N' ORDER BY site_id ASC LIMIT 1";
$return=mysql_query($query,$link);

if (mysql_num_rows($return) < 1) {
    include('../Templates/maintemplate.header.inc.php');
	include('../includes/admin_header.php');
	print("There are now no records to process.");
    include('../Templates/maintemplate.footer.inc.php');
	mysql_close($link);
	exit;
}

$sql_array=mysql_fetch_object($return);

$sql_array->site_linkback=str_replace("http://","",$sql_array->site_linkback);
$linkback_url="http://$sql_array->site_linkback";
$linkback_url=trim($linkback_url);

if ($fp=fopen($linkback_url,'r')) {
	$chktime=time();

	while(!feof($fp))
	{
	  $contents.= @fread($fp,1024);
	  if (time() >= $chktime+5) break;
	}
	fclose($fp);

	if (strstr($contents,$CONST_LINK_ROOT)) {
		$test_result="<font color='green'>Passed";
	} else {
		$test_result="<font color='red'>Failed";
	}
}

# duplicate check

$dup_query=mysql_query("SELECT * FROM dir_site_list WHERE site_url = '$sql_array->site_url'",$link);
$no_dup=mysql_num_rows($dup_query);
$no_dup=$no_dup-1;

$sql_array->site_url=str_replace("http://","",$sql_array->site_url);

$write_text="<p><a target='_blank' href='http://$sql_array->site_url'><b>$sql_array->site_name</b></a> - $sql_array->site_description<br><font color='#FF0000'><i>($sql_array->site_url)</i></font></p>";
include('../Templates/maintemplate.header.inc.php');
?>

 <?php  include('../includes/admin_header.php'); ?>
<table border="0" cellspacing="1" width="500" id="AutoNumber1">
  <tr>
    <td width="100%">
    <form method="POST" action="sitechecker.php?PHPSESSID=<?php echo session_id() ?>&mode=save">
    	<input type='hidden' name='site_id' value='<?php print("$sql_array->site_id"); ?>'>
			<table border="0" cellspacing="1" width="100%" id="AutoNumber2" height="566">
              <tr>
                <td width="4%" height="19">&nbsp;</td>
                <td width="20%" height="19">&nbsp;</td>
                <td colspan="3" height="19">&nbsp;</td>
              </tr>
              <tr>
                <td width="4%" height="22">&nbsp;</td>
                <td width="20%" height="22"> Name</td>
                <td colspan="3" height="22">
                  <input type="text" name="txtName" size="50" tabindex="1" value="<?php print("$sql_array->site_name"); ?>">
                </td>
              </tr>
              <tr>
                <td width="4%" height="84">&nbsp;</td>
                <td width="20%" height="84"> Description</td>
                <td colspan="3" height="84">
                  <textarea rows="5" name="txtDescription" cols="50" tabindex="2"><?php print("$sql_array->site_description"); ?></textarea>
                </td>
              </tr>
              <tr>
                <td width="4%" height="22">&nbsp;</td>
                <td width="20%" height="22"> Email</td>
                <td colspan="3" height="22">
                  <input type="text" name="txtEmail" size="50" tabindex="3" value="<?php print("$sql_array->site_email"); ?>">
                </td>
              </tr>
              <tr>
                <td width="4%" height="22">&nbsp;</td>
                <td width="20%" height="22">Url</td>
                <td colspan="3" height="22">
                  <input type="text" name="txtUrl" size="50" tabindex="4" value="<?php print("$sql_array->site_url"); ?>">
                </td>
              </tr>
              <tr>
                <td width="4%" height="22">&nbsp;</td>
                <td width="20%" height="22">Check Url</td>
                <td colspan="3" height="22"> <a href="<?php print("http://$sql_array->site_url"); ?>" target='_blank'><?php print("$sql_array->site_url"); ?></a></td>
              </tr>
              <tr>
                <td width="4%" height="22">&nbsp;</td>
                <td width="20%" height="22">Rating</td>
                <?
                    $votes=(isset($sql_array->votes))?$sql_array->votes:'0';
                    $rating=(isset($sql_array->average))?$sql_array->average:'0.00';
                    $grating = display_rank($sql_array->site_url);
                    $num_rank = get_page_rank("http://".$sql_array->site_url);
                    if ($num_rank == -1) {
                        $num_str = 'No rank';
                    } else {
                        $num_str = $num_rank.'/10';
                    }
                ?>
                <td colspan="3" height="22">
                <?
                print("
                        <p>
                        <i>Hits: $sql_array->clicks_counter, Rating: $rating Votes: $votes Rate It</i>
                        Google rating <img src=\"$grating\" border=0 title=\"$num_str\"></p>
                        <p></p>");
                ?>
                </td>
              </tr>
              <tr>
                <td width="4%" height="22">&nbsp;</td>
                <td width="20%" height="22"> LinkUrl</td>
                <td colspan="3" height="22">
                  <input name="txtLinkUrl" type="text" id="txtLinkUrl2" value="<?php print("$sql_array->site_linkback"); ?>" size="50">
                </td>
              </tr>
              <tr>
                <td height="22">&nbsp;</td>
                <td width="20%" height="22">Check Link Url</td>
                <td colspan="3" height="22"><a href="<?php print("http://$sql_array->site_linkback"); ?>" target='_blank'><?php print("$sql_array->site_linkback"); ?></a> </td>
              </tr>
              <tr>
                <td width="4%" height="22">&nbsp;</td>
                <td width="20%" height="22"> Category</td>
                <td colspan="3" height="22">
                  <select size="1" name="lstCategories" tabindex="6">
                    <option>-- Choose the nearest match --</option>
                    <?php
					$result=mysql_query("SELECT * FROM dir_categories ORDER BY cat_parent",$link);

					while ($cat_array=mysql_fetch_object($result)) {
						if ($cat_array->cat_parent != $saved_cat_parent)
							print("<option>___________________________________</option>");

						if ($cat_array->cat_id == $sql_array->cat_id) $selected="selected"; else  $selected="";
						print("<option value=$cat_array->cat_id $selected>$cat_array->cat_parent : $cat_array->cat_child</option>");

						$saved_cat_parent=$cat_array->cat_parent;
					}
				?>
                  </select>
                </td>
              </tr>
              <tr>
                <td width="4%" height="19">&nbsp;</td>
                <td width="20%" height="19">&nbsp;</td>
                <td colspan="3" height="19">&nbsp;</td>
              </tr>
              <tr>
                <td width="4%" height="18">&nbsp;</td>
                <td width="20%" height="18"> LinkCheck</td>
                <td colspan="3" height="18"><?php print("$test_result"); ?></td>
              </tr>
              <tr>
                <td width="4%" height="19">&nbsp;</td>
                <td width="20%" height="19"> Duplicate</td>
                <td height="19" colspan="3"><?php print("$no_dup"); ?></td>
              </tr>
              <tr>
                <td width="4%" height="19">&nbsp;</td>
                <td colspan="4" height="19">
                  <hr>
                </td>
              </tr>
              <tr>
                <td width="4%" height="20">&nbsp;</td>
                <td width="20%" height="20"> Accept</td>
                <td colspan="3" height="20">
                  <input name="rdoAccept" type="radio" tabindex="9" value="Accept" checked>
                </td>
              </tr>
              <tr>
                <td height="20">&nbsp;</td>
                <td width="20%" height="20">Update</td>
                <td colspan="3" height="20">
                  <input type="radio" value="Update" name="rdoAccept" tabindex="10">
                </td>
              </tr>
              <tr>
                <td width="4%" height="22">&nbsp;</td>
                <td width="20%" height="22"> Reject</td>
                <td width="6%" height="22">
                  <input type="radio" value="Reject" name="rdoAccept" tabindex="11">
                </td>
                <td height="22" colspan="2">
                  <p>&nbsp;                  </p>
                  <p>                </td>
              </tr>
              <tr>
                <td width="4%" height="19">&nbsp;</td>
                <td width="20%" height="19">Delete</td>
                <td colspan="3" height="19">
                  <input type="radio" value="Delete" name="rdoAccept" tabindex="14">
                </td>
              </tr>
              <tr>
                <td height="26">&nbsp;</td>
                <td colspan="4" height="26">
                  <hr>
                </td>
              </tr>
              <tr>
                <td height="26">&nbsp;</td>
                <td colspan="3" rowspan="2">Reject Reason                                 </td>
                <td height="26">
                  <select size="1" name="lstReason" tabindex="12" style="width:350px ">
                    <option value="You must place a link back to us using one of our supplied links on an indexed page on (or no further than 1 click from) your home page.">1. You must place a link back to us ... </option>
                    <option value="Please include the text beneath the graphic used for the link back. Link HTML is provided at <?php echo $CONST_LINK_ROOT ?>/response.php">2. You must include the text under the graphic ... </option>
                    <option value="Text links are not sufficient for entry into the directory/search engine. We can exchange text links via our links page at <?php echo $CONST_LINK_ROOT ?>/dating_links.php if you prefer this option.">3. Text links can be used only for the dating links page ... </option>
                    <option value="You must not be an affiliate site (such that your data comes from another sites' database or you drive users to another site).">4. You must not be an affiliate site ...</option>
                    <option value="Sites must contain a reasonable amount of content (i.e. adverts) to be eligible for listing. We only list dating and related romance sites. Adult contacts sites must be tasteful. No porn sites!">5. Sites must contain a reasonable amount of content...</option>
                    <option value="To be listed under 100% free, you MUST be 100% free for ALL services that you provide in relation to the site you are listing.">6. To be listed under 100% free...</option>
                    <option value="We prefer sites that are listed under their own single domain rather than a) spread across multiple domains or b) a sub-domain of a free hosting service (such as tripod, angelfire, geocities etc.).">7. We prefer sites that are listed under their own domain...</option>
                    <option value="Sites must provide a good user experience and not include multiple pop-ups, music, unasked for installations or pages that cannot be exited.">8. Sites must provide a good user experience...</option>
                    <option value="We are unable to find a site at the URL that was provided in the  submission. If this is a temporary issue, please resubmit when the site is online.">9. No site could be found at the URL posted ... </option>
                    <option value="Submissions from sites that use automated link directories, masking, forwarding or search engine spamming techniques will be exluded.">10. Submissions from sites that use automated link directories, spamming techniques etc.. </option>
                    <option value="Your site description needs to be amended, please describe your site in about one paragraph in descriptive english. Please do not spam the description with lists of keywords.">10. Your site description needs to be amended .. </option>
                    <option value="We cannot authenticate the link on the links page as it is within a frame. Please resubmit giving the URL to the actual content page on which the link resides.">11. We cannot authenticate the link on the links page ..</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td height="26">&nbsp;</td>
                <td height="26">
                  <input name="txtReason" type="text" id="txtReason" size="50" tabindex="13">
                </td>
              </tr>
              <tr>
                <td height="26">&nbsp;</td>
                <td colspan="4" height="26">
                  <hr>
                </td>
              </tr>
              <tr>
                <td width="4%" height="26">&nbsp;</td>
                <td colspan="4" height="26">
                  <input type="submit" value="Submit" name="btnSubmit" tabindex="16" class="button">
                </td>
              </tr>
            </table>
    </form>
    </td>
  </tr>
</table>
<?php include('../Templates/maintemplate.footer.inc.php');?>
