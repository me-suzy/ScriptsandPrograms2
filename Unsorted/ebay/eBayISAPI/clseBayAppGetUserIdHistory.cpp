/*	$Id: clseBayAppGetUserIdHistory.cpp,v 1.4.430.1.14.1 1999/08/01 03:01:15 barry Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Display pages that user can request other's emails
//
//	Modifications:
//				- 12/19/97 Wen	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

// Used to reference functions in our caller.
// It's probably more "portable" to handle
// this stuff through clsEnvironment.

void clseBayApp::GetUserIdHistory(CEBayISAPIExtension *pServer, char *pUserId)
{
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Request User Id History"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// header
	*mpStream	<<	"<h2>This Person Has A New Look</h2>\n"
				<<	"The &quot;shades&quot icon "
				<<	"<img border=0 height=15 width=21 alt=\"mask\""
				<<	"src=\""
				<<	mpMarketPlace->GetImagePath()
				<<	"mask.gif\">"
				<<	" behind a User ID means that this "
				<<	"person has changed his/her User ID within the last 30 days."
				<<	"<p>To view the User ID History and e-mail address of this "
				<<	"person, please submit the following request:<p>";

	// legal, rule

	// form
	*mpStream	<<	"<form method=\"POST\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageReturnUserIdHistory)
				<<	"eBayISAPI.dll\">\n"
				<<	"<input TYPE=HIDDEN NAME=\"MfcISAPICommand\" "
				<<	"VALUE=\"ReturnUserIdHistory\">\n"
				<<	"<table border=0 cellspacing=3 cellpadding=2>\n"
				<<	"<tr><td>Requested "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	":</td>\n"
				<<	"<td><input type=\"text\" name=\"requested\" size=40 ";
	
	// determine whether there is requested user id
	if (pUserId && stricmp(pUserId, "default") != 0)
	{
		 *mpStream	<<	"value=\""
					<<	pUserId
					<<	"\"";
	}

	*mpStream	<<	"</td></tr>\n"
				<<	"<tr><td>Your "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	":</td>\n"
				<<	"<td><input type=\"text\" name=\"userid\" size=40></td></tr>\n"
					"<tr><td>Your "
				<<	mpMarketPlace->GetPasswordPrompt()
				<<	":</td>\n"
				<<	"<td><input type=\"password\" name=\"pass\" size=40></td></tr>\n"
				<<	"</table>\n"
				<<	"<p><input type=\"submit\" value=\"Submit\"></p>\n"
				<<	"</form>\n";

	// the footer
	*mpStream	<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();
	return;

}

