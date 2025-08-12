	
	<table border="0" align="center" id="searchandlogin">
  		<tr>
		
    		<td align="left" width="250"><a href="<?php echo CMS_WWW; ?>"><strong><span class="normalText" ><?php echo SITE_NAME; ?></span></strong></a>&nbsp;
    		</td>
			
			
			<td align="left" ><img src="<?php echo CMS_WWW; ?>/images/common/spacer.gif" width="2" >&nbsp;</td>
<?php					
			//search form
			if ($config['search'] == "off" ) {
			    echo "<td valign=\"middle\" width=\"20\">&nbsp;</td>";
				echo "<td valign=\"bottom\" width=\"42\">&nbsp;</td>";
			}
			else {
			    echo "<form name=\"search\" method=\"get\" action=\"" . CMS_WWW . "/templates/forms/search_results.php" . "\">"; 				
    			echo "<td align=\"right\" valign=\"middle\" width=\"24\">";
				echo "<input type=\"text\" name=\"term\" size=\"20\" />&nbsp;</td>";
				echo "<td align=\"left\" valign=\"middle\" width=\"42\"><input type=\"image\" name=\"term\" src=\"" . CMS_WWW . "/images/common/search.gif\" style=\"border:none\" align=\"bottom\" width=\"42\" height=\"14\" /></td>";
			}
?>			

<!-- contact us -->
			    <td width="10"><img src="<?php echo CMS_WWW; ?>/images/common/spacer.gif" width="10" ></td>
			    <td class="smallText" align="right" valign="middle" width="16"> 
					<a href="<?php echo CMS_WWW; ?>/templates/forms/contact_form.php?id=2"><img src="<?php echo CMS_WWW; ?>/images/common/email.gif" border="0" height="16" width="16">&nbsp;</a></td>
				
				<td class="smallText" align="left" valign="middle" width="50"> 
					<a href="<?php echo CMS_WWW; ?>/templates/forms/contact_form.php?id=2">Contact Us</a>
				</td>
				
<!-- sitemap -->
			    <td width="5"><img src="<?php echo CMS_WWW; ?>/images/spacer.gif" width="5" ></td>
			    <td class="smallText" align="right" valign="middle" width="50">
				  <a href="<?php echo CMS_WWW; ?>/templates/sitemap/sitemap.php?id=3">Sitemap</a></td>
<?php
			//register
			    if (!user_isloggedin()) {
					if ($config['register'] == "on" ) {
			    		echo "<td width=\"15\"><img src=\"" . CMS_WWW . "/images/common/spacer.gif\" width=\"15\" ></td>";
						echo "<td class=\"smallText\" align=\"right\" valign=\"middle\" width=\"30\">";  
							echo "<a href=\"" . CMS_WWW . "/templates/forms/register_form.php?id=4\">". REGISTER ."</a>";
						echo "</td>";
						}
					else {
			    		echo "<td>&nbsp;"; 
						echo "</td>";
					} //if the register button is set to be visible
				} //if user is not logged in
			
			
	    	//login
			if ($config['login'] == "on" ) {
			    echo "<td width=\"10\"><img src=\"" . CMS_WWW . "/images/common/spacer.gif\" width=\"10\" ></td>";
				echo "<td class=\"smallText\" align=\"right\" valign=\"middle\" width=\"20\">"; 
					$id = $_GET['id'];
					if (user_isloggedin()) {
	     			echo "<a href=\"" . CMS_WWW . "/templates/forms/logout.php\">". LOGOUT ."</a>";
    				}
					else {
					echo "<img src=\"" . CMS_WWW . "/images/common/spacer.gif\" width=\"10\" ><a href=\"" . CMS_WWW . "/templates/forms/login.php?id=5\">". LOGIN ."</a>";
					}
				echo "</td>";
				}
			else {
			    echo "<td>"; 
				echo "</td>";
			}
			
			//admin
			if (is_memberof(2)) {
			    echo "<td width=\"10\"><img src=\"" . CMS_WWW . "/images/common/spacer.gif\" width=\"10\" ></td>";
				echo "<td class=\"smallText\" align=\"right\" valign=\"middle\" width=\"20\">"; 
					echo "<a href=\"" . CMS_WWW . "/admin.php\">". ADMIN ."</a>";
				echo "</td>";
				}
			else {
			    
			    echo "<td>"; 
				echo "</td>";
			}
?>			
  	<td></form></td></tr>
  </table>
  