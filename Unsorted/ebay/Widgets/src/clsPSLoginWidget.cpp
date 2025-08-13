/*	$Id: clsPSLoginWidget.cpp,v 1.2.2.4.78.1 1999/08/01 02:51:23 barry Exp $	*/
//
//	File:	clsPSLoginWidget.h
//
//	Class:	clsPSLoginWidget
//
//	Author:	Wen Wen
//
//	Function:
//		This class displays a login page for Personal Shopper.
//		The class is derived from clsLoginWidget
//							
// Modifications:
//				- 2/2/99	Wen - Created
//				- 06/29/99	nsacco - rewritten to not use static strings
//
#include "widgets.h"
#include "clsPSLoginWidget.h"

// constructor
//
clsPSLoginWidget::clsPSLoginWidget(clsMarketPlace* pMarketPlace)
	: clsLoginWidget(pMarketPlace)
{
	// nsacco 06/29/99
	char thePrompt[4096] = "";
	char theCookie[2048] = "";

	switch (pMarketPlace->GetCurrentSiteId())
	{
	case SITE_EBAY_AU:
	case SITE_EBAY_UK:
	case SITE_EBAY_CA:
	case SITE_EBAY_US:
	case SITE_EBAY_MAIN:
	default:
		// build the prompt
		strcpy(thePrompt,
			"<table border=\"0\" width=\"100%\">\n"
			"<tr><td valign=\"middle\" width=\"10%\"><img src=\"");
		// TODO - is this needed for this image or is it a fixed image that will not 
		// be customized for each site?
		strcat(thePrompt, pMarketPlace->GetPicsPath());
		strcat(thePrompt,
			"pslogo.gif\""
			" alt=\"Personal Shopper\" border=\"0\" align=\"middle\"></td>\n"
			"<td valign=\"bottom\">&nbsp;<b><font face=\"Helvetica, Arial\" size=+2>Login</font></b></td>\n"
			"<td align=\"right\"><a href=\"http://www.netmind.com\"><img src=\"");
		// TODO - is this needed for this image or is it a fixed image that will not 
		// be customized for each site?
		strcat(thePrompt, pMarketPlace->GetPicsPath());
		strcat(thePrompt, 
			"poweredby.gif\" "
			"alt=\"Powered by NetMind\" align=\"middle\" border=0></a></td></tr></table>\n"
			"<p><font size=\"3\">Use Personal Shopper to help you with your buying! With Personal Shopper, you can" 
			"<ul>"
			"<li>Save and run your favorite searches (up to 3 searches).</li>"
			"<li>Get email notification when new items appear on eBay that match what you're looking for.</li>"
			"</ul>"
			"<p>Please submit your User ID and password to access Personal Shopper. "
			"If you do not have a User ID or password, "
			"please follow this link to <b>"
			"<a href=\"");
		strcat(thePrompt, pMarketPlace->GetHTMLPath());
		strcat(thePrompt, 
			"services/registration/register.html\">become a registered user</a>.</b>"
			"</p>"
			"</font>");

		// build the cookie
		strcpy(theCookie, 
			"By choosing this option, you\'ll get a temporary \"cookie\" that enables you "
			"to skip this step when you use other Personal Shopper functions during this "
			"browser session.  When you turn your browser off, the cookie will "
			"disappear. For more information about cookies, click "
			"<A HREF=\"");
		strcat(theCookie, pMarketPlace->GetHTMLPath());
		strcat(theCookie,
			"help/myinfo/cookies.html\">here</A>.");

		mpPrompt = thePrompt;
		mpCookie = theCookie;
	}
}

clsPSLoginWidget::~clsPSLoginWidget()
{
}
