/*	$Id: clsEmailLoginWidget.cpp,v 1.2.2.4.78.1 1999/08/01 02:51:23 barry Exp $	*/
//
//	File:	clsEmailLoginWidget.h
//
//	Class:	clsEmailLoginWidget
//
//	Author:	Wen Wen
//
//	Function:
//		This class displays a login page for requesting other user email.
//		The class is derived from clsLoginWidget
//							
// Modifications:
//				- 2/2/99	Wen - Created
//				- 06/29/99	nsacco - rewritten to not use static strings
//
#include "widgets.h"
#include "clsEmailLoginWidget.h"

// constructor
//
clsEmailLoginWidget::clsEmailLoginWidget(clsMarketPlace* pMarketPlace)
	: clsLoginWidget(pMarketPlace)
{
	// nsacco 06/29/99 rewritten
	char thePrompt[2048] = "";
	char theCookie[2048] = "";

	switch (pMarketPlace->GetCurrentSiteId())
	{
	case SITE_EBAY_AU:
	case SITE_EBAY_CA:
	case SITE_EBAY_UK:
	case SITE_EBAY_US:
	case SITE_EBAY_MAIN:
	default:
		// build the prompt
		strcpy(thePrompt,"<h2>User ID History and Email Address Request Form</h2>\n"
					"<font size=\"3\">" 
					"eBay kindly requests that you submit your User ID and password to "
					"view the User ID History or email addresses of other users. \n"
					"<P>Note: When the shades icon <img border=0 height=15 width=21 alt=\"mask\""
					"src=\"");
		// TODO - should this really be a site specific path for this image?
		strcat(thePrompt, pMarketPlace->GetPicsPath());
		strcat(thePrompt, "mask.gif\"> appears next to a User ID, it "
					"signifies that the user has changed his/her User ID within the "
					"last 30 days.  The shades icon will disappear after the user has "
					"maintained the same User ID for a 30-day period."
					"</font>");

		// build the cookie
		strcpy(theCookie, "By choosing this option, you\'ll get a temporary \"cookie\" that enables you "
					"to skip this step if you request another user's email address during this "
					"browser session.  When you turn your browser off, the cookie will "
					"disappear. For more information about cookies, click "
					"<A HREF=\"");
		strcat(theCookie, pMarketPlace->GetHTMLPath());
		strcat(theCookie, "help/myinfo/cookies.html\">here</A>.");

		mpPrompt = thePrompt;
		mpCookie = theCookie;
	}
	
}
