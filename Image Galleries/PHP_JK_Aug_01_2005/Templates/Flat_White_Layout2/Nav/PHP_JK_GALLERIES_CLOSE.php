	<table cellpadding=0 cellspacing=0 border=0 width=<?=$iTableWidth?> class='HeadFoot'>
		<tr>
			<td width=15><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=15 Height=26></td>
			<td width=100%>
				<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td style='font-size=9px;'>
				<b>
				<?php
				If ( $bHasAccount ) {
					If ( isset($_REQUEST["GAL1"]) ){
						$sLoginName = Trim($_REQUEST["GAL1"]);
						Echo "User: <a href='" . $sSiteURL . "/UserArea/UserData/index.php' class='SmallNav3'>" . $sLoginName . "</a>";
					}
				}
				?>
				</td><td align=right><font size=-2 color='<?=$TextColor3?>'>Copyright &copy; 2004 - <?=date("Y")?> PHPJK.com &nbsp;&nbsp;
				</td></tr></table>
			</td>
		</tr>
	</table>
	<table cellpadding=0 cellspacing=0 border=0 width=<?=$iTableWidth?>>
		<tr>
			<td valign=top>
				<script type="text/javascript"><!--
				google_ad_client = "pub-3125950574976235";
				google_ad_width = 468;
				google_ad_height = 60;
				google_ad_format = "468x60_as";
				google_ad_type = "text_image";
				google_ad_channel ="";
				google_color_border = "CCCCCC";
				google_color_bg = "FFFFFF";
				google_color_link = "000000";
				google_color_url = "666666";
				google_color_text = "333333";
				//--></script>
				<script type="text/javascript"
				  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script>
			</td>
			<td align=right>
				<a href = 'http://www.phpjk.com/' class='MediumNavPage' target='_blank'><img src='<?=G_STRUCTURE_DI("PoweredByPHPJK_100x30.gif", $GLOBALS["COLORBASED"])?>' alt='Site powered by PHP JackKnife' border=0></a>
			</td>
		</tr>
	</table>
	</center>
</BODY></HTML>
<?php
DB_CloseDomains();
?>