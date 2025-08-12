<?php
	$dirpath = "$Config_rootdir";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();

      if ( $ucook->LoggedIn() )
	{ Header("Location: $Config_mainurl/user/index.php"); }

$usr->HeaderOut($Config_SiteTitle .' :: '.$Config_buyline);

?>

<p class="tn" align="center">Welcome to the realm <?php echo $Config_systemname ?>, the mystical land of visualizing 
                    experience... through photos...</p>
			<br>
                  <table width="98%" border="0" cellspacing="0" cellpadding="4" align="center">
                    <tr> 
                      <td class="tn"> 
			      <div align="justify"><?php echo $Config_systemname ?>, place where you create your 
			        own personal little world, decorating it with the light of those times 
			        of life, which don't last forever... in life you have to collect all the 
			        goods of it and keep it for those hard times which everyone has... those 
			        times will become lighter if you have the support and glimpse of the times 
			        you cherish the most... herein <?php echo $Config_systemname ?> we let you do just 
				  that... giving you all you want to make the treasure trove of your's... giving you the power to
				  create your own ePhoto Albums. <a href="register.php">signup 
                       now</a> ~ <a href=http://www.albinator.com/showalbum.php?uuid=albinator&aid=38>demo album</a></div>
                      </td>
                    </tr>
                    <tr> 
                      <td class="tn">&nbsp;</td>
                    </tr>
                  </table>
                  <table width="98%" border="0" cellspacing="0" cellpadding="4" align="center">
                    <tr>
                      <td height="143"> <span class="tn">with <?php echo $Config_systemname ?> you can...</span><br>
                        <br>
                        <table width="80%" border="0" cellspacing="0" cellpadding="2" align="center">
                          <tr> 
                            <td class="tn"><font color="#003366">&gt; create and 
                              personlize your photo albums </font></td>
                          </tr>
                          <tr> 
                            <td height="19" class="tn"><font color="#003366">&gt; 
                              create private albums </font></td>
                          </tr>
                          <tr> 
                            <td height="2" class="tn"><font color="#003366">&gt; 
                              add pictures with simple steps </font></td>
                          </tr>
                          <tr> 
                            <td height="2" class="tn"><font color="#003366">&gt; 
                              arrange your photos as you like</font></td>
                          </tr>
                          <tr> 
                            <td height="2" class="tn"><font color="#003366">&gt; 
                              tell family &amp; friends about particular albums</font></td>
                          </tr>
                          <tr> 
                            <td height="2" class="tn"><font color="#003366">&gt; 
                              make your pictures a personalised photo eCards</font></td>
                          </tr>
                          <tr> 
                            <td height="2" class="tn"><font color="#003366">&gt; 
                              never forget those special dates with personal reminders</font></td>
                          </tr>
                          <tr> 
                            <td height="12" class="tn"><font color="#003366">&gt; 
                              manipulate / edit your photos to look better</font></td>
                          </tr>
                          <tr> 
                            <td height="12" class="tn">&nbsp;&nbsp; <font color="#003366"> 
                              and lots lots more...</font></td>
                          </tr>
                          <tr> 
                            <td height="12" class="tn">&nbsp;</td>
                          </tr>
                          <tr> 
                            <td height="12" class="tn"> 
                              <div align="left">and the best thing about it... 
                                its <b>FREE</b>...<br><a href="register.php">signup 
                          now</a>
					</div>
                            </td>
                          </tr>
                        </table>
                      </td>
                      <td width="100" height="143"> 
                        <div align="right"><img src="<?php echo $dirpath.$Config_imgdir ?>/main/logo3.gif" width="75" height="300"></div>
                      </td>
                    </tr>
                  </table>
<?php

$usr->FooterOut();

?>