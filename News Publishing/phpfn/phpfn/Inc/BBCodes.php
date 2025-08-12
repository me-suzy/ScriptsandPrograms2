<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/
require_once('../Config/Config.php');
?>
<HTML>
	<HEAD>
		<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<TITLE>PHPFreeNews</TITLE>
		<LINK rel="stylesheet" href="Styles.css" type="text/css" />
	</HEAD>
	<BODY>
	<P class="Welcome">Listed below are the "BB Codes" supported by PHPFreeNews. Include these in your posting to get consistent news throughout your site.</P>
		<TABLE class="Admin">
			<HR>
			<TR>
				<TD valign="top" nowrap>
					<TABLE class="Admin">
						<TR class="CentreTable">
							<TD>
								Make text bold
							</TD>
							<TD>
								[b]text[/b]
							</TD>
						</TR>
						<TR class="CentreTable">
							<TD>
								Make text italic
							</TD>
							<TD>
								[i]italic text[/i]
							</TD>
						</TR>
						<TR class="CentreTable">
							<TD>
								Underline text
							</TD>
							<TD>
								[u]underlined text[/u]
							</TD>
						</TR>
						<TR class="CentreTable">
							<TD>
								Centre text
							</TD>
							<TD>
								[c]centred text[/c]
							</TD>
						</TR>
						<TR class="CentreTable">
							<TD>
								New paragraph
							</TD>
							<TD>
								[p]text[/p]
							</TD>
						</TR>
						<TR class="CentreTable">
							<TD>
								Sample program code
							</TD>
							<TD>
								[code]for i = 1 to 10[/code]
							</TD>
						</TR>
						<TR class="CentreTable">
							<TD>
								Quote someone
							</TD>
							<TD>
								[quote]And that's magic![/quote]
							</TD>
						</TR>
						<TR class="CentreTable">
							<TD>
								Embed an image
							</TD>
							<TD>
								[img]http://www.bitmaps.com/x.bmp[/img]
							</TD>
						</TR>

						<TR class="CentreTable">
							<TD>
								Hyperlink with URL, in new window </TD>
							<TD>
								[url]www.domain.com[/url]
							</TD>
						</TR>
						<TR class="CentreTable">
							<TD>
								Hyperlink with URL and description, in new window
							</TD>
							<TD>
								[url=www.domain.com]website[/url]
							</TD>
						</TR>

						<TR class="CentreTable">
							<TD>
								Hyperlink with URL, in same window
							</TD>
							<TD>
								[url2]www.domain.com[/url2]
							</TD>
						</TR>
						<TR class="CentreTable">
							<TD>
								Hyperlink with URL and description, in same window
							</TD>
							<TD>
								[ur2l=www.domain.com]website[/url2]
							</TD>
						</TR>


						<TR class="CentreTable">
							<TD>
								Email address with visible address
							</TD>
							<TD>
								[email]bill@microsoft.com[/email]
							</TD>
						</TR>
						<TR class="CentreTable">
							<TD>
								Email address</TD>
							<TD>
								[email=bill@microsoft.com]email me[/email]
							</TD>
						</TR>
					</TABLE>				
				</TD>
			</TR>

			<TR>
				<TD height="40" valign="top">
					<FORM method="post" action="">
						<CENTER>
							<INPUT class="but" type="button" name="Button" value="Close" onClick="window.close()">
						</CENTER>
					</FORM>
				</TD>
			</TR>

		</TABLE>
	</BODY>
</HTML>