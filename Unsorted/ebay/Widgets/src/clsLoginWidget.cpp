/*	$Id: clsLoginWidget.cpp,v 1.2.2.4.78.1 1999/08/01 02:51:23 barry Exp $	*/
//
//	File:	clsLoginWidget.h
//
//	Class:	clsLoginWidget
//
//	Author:	Wen Wen
//
//	Function:
//
// Modifications:
//				- 2/2/99	Wen - Created
//				- 06/29/99	nsacco - rewritten to not use static strings for prompt and cookie
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "widgets.h"
#include "clsLoginWidget.h"

// constructor
//
clsLoginWidget::clsLoginWidget(clsMarketPlace* pMarketPlace)
{
	mpMarketPlace = pMarketPlace;
	mShowRememberMe = true;

	// nsacco 06/29/99
	char thePrompt[2048] = "";
	char theCookie[2048] = "";;

	switch (mpMarketPlace->GetCurrentSiteId())
	{
	case SITE_EBAY_AU:
	case SITE_EBAY_UK:
	case SITE_EBAY_CA:
	case SITE_EBAY_US:
	case SITE_EBAY_MAIN:
	default:
		// build the prompt
		strcpy(thePrompt, 
					"<h2>eBay Login</h2>\n"
					"<font size=\"3\">" 
					"eBay kindly requests that you submit your User ID and password."
					"</font>");

		// build the cookie
		strcpy(theCookie,
					"By choosing this option, you\'ll get a temporary \"cookie\" that enables you "
					"to skip this step during this browser session. "
					"When you turn your browser off, the cookie will "
					"disappear. For more information about cookies, click "
					"<A HREF=\"");
		strcat(theCookie, mpMarketPlace->GetHTMLPath());
		strcpy(theCookie,
					"help/myinfo/cookies.html\">here</A>.");

		mpPrompt = thePrompt;
		mpCookie = theCookie;
	}
}

// destructor
//
clsLoginWidget::~clsLoginWidget()
{

}

// Set parameters
//
void clsLoginWidget::SetParams(char* pAction,
				  int Pairs, 
				  clsNameValuePair* pNameValue,
				  bool FieldOnly/*=false*/)
{
	mpAction		= pAction;
	mPairs			= Pairs;
	mpNameValues	= pNameValue;
	mFieldOnly		= FieldOnly;
}

// Emit HTML page
//
void clsLoginWidget::EmitHTML(ostream* pStream)
{

	int i;

	if (!mFieldOnly)
	{
		// Heading, etc
		*pStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
				 <<	mpMarketPlace->GetCurrentPartnerName()
				 <<	" Login"
					"</title>"
					"<meta http-equiv=\"Expires\" "
					"content=\"Mon, 05 Oct 1998, 00:00:00 GMT\">"
					"</head>"
				 << mpMarketPlace->GetHeader()
				 << "\n"
				 << flush;

		// emit prompt
		*pStream <<	mpPrompt;

		// form
		*pStream	<<	"<form method=\"POST\" action=\""
					<<	mpAction
					<<	"\">\n";

		// print out the hidden name value pairs
		for (i = 0; i < mPairs; i++)
		{
			*pStream << "<input type=\"hidden\" name=\""
					  << mpNameValues[i].GetName()
					  << "\" value=\""
					  << mpNameValues[i].GetValue()
					  << "\">\n";
		}
	}

	// print out text input boxes for user id and password
	*pStream	<<	"<table><tr><td>Your "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	":</td>\n"
					"<td><input type=\"text\" name=\"userid\" size=40></td></tr>\n"
					"<tr><td>Your "
				<<	mpMarketPlace->GetPasswordPrompt()
				<<	":</td>\n"
					"<td><input type=\"password\" name=\"pass\" size=40></td></tr>\n"
					"</table>\n";

	if (mShowRememberMe)
	{
		*pStream	<<	"<P><input type=\"checkbox\" name=acceptcookie value=1>Remember me\n<br>"
					<<	mpCookie
					<<	"</p>";
	}
	
	*pStream	<<	"<input type=\"submit\" value=\"Submit\">\n";

	if (!mFieldOnly)
	{
		// close the form
		*pStream	<< "</form>\n";

		// And the footer
		*pStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter()
					<<	flush;
	}

	return;
}

