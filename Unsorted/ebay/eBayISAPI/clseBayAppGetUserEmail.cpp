/*	$Id: clseBayAppGetUserEmail.cpp,v 1.6.22.3.76.2 1999/08/05 18:58:56 nsacco Exp $	*/
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
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clseBayApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsUserValidation.h"

// Used to reference functions in our caller.
// It's probably more "portable" to handle
// this stuff through clsEnvironment.

#include "stdafx.h"
#include <AFXISAPI.H>

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
const char MsgCookie[] = 
"By choosing this option, you\'ll get a temporary \"cookie\" that enables you "
"to skip this step if you request another user's email address during this "
"browser session.  When you turn your browser off, the cookie will "
"disappear. For more information about cookies, click "
"<A HREF=\"http://pages.ebay.com/help/myinfo/cookies.html\">here</A>.";
*/

void clseBayApp::GetUserEmail(CEBayISAPIExtension *pServer, char *pUserId)
{
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Request User E-mail"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// header
	*mpStream	<<	"<h2>User ID History and Email Address Request Form </h2>\n";

	if (mpUsers->GetUserValidation()->IsSoftValidated() == false)
	{
		// legal, rule
		*mpStream	<< "<font size=\"3\">" 
					<< "eBay kindly requests that you submit your User ID and password to "
					<< "view the User ID History or email address of another user.\n"
					<< "<P>Note: When the shades icon<img border=0 height=15 width=21 alt=\"mask\""
					<<	"src=\""
					<<	mpMarketPlace->GetImagePath()
					<<	"mask.gif\"> appears next to a User ID, it "
					<< "signifies that the user has changed his/her User ID within the "
					<< "last 30 days.  The shades icon will disappear after the user has "
					<< "maintained the same User ID for a 30-day period.";
	}

	// link to multiple emails
	*mpStream	<<	"<p>If you would like to look up the e-mail addresses of <b>multiple users</b> at once, click <a href=\""
				<<	mpMarketPlace->GetCGIPath(PageMultipleEmails)
				<<	"eBayISAPI.dll?GetMultipleEmails\">"
				<<	"here</a>.</font>\n";

	// form
	*mpStream	<<	"<form method=\"POST\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageReturnUserEmail)
				<<	"eBayISAPI.dll\">\n"
					"<input TYPE=HIDDEN NAME=\"MfcISAPICommand\" "
					"VALUE=\"ReturnUserEmail\">\n"
					"<table><tr><td>\n"
					"Requested "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	": </td>"
				<<  "<td><input type=\"text\" name=\"requested\" size=40 ";
	
	// determine whether there is requested user id
	if (pUserId && stricmp(pUserId, "default") != 0)
	{
		 *mpStream	<<	"value=\""
					<<	pUserId
					<<	"\"\n";
	}

	*mpStream	<<	"></td>\n";

	if (mpUsers->GetUserValidation()->IsSoftValidated() == false)
	{
		// print out the userid and password form
		*mpStream	<<	"<tr><td>Your "
					<<	mpMarketPlace->GetLoginPrompt()
					<<	":</td>\n"
						"<td><input type=\"text\" name=\"userid\" size=40></td></tr>\n"
						"<tr><td>Your "
					<<	mpMarketPlace->GetPasswordPrompt()
					<<	":</td>\n"
						"<td><input type=\"password\" name=\"pass\" size=40></td></tr>\n"
						"</table>\n";

		// kakiyama 07/09/99
		*mpStream	<<	"<P><input type=\"checkbox\" name=acceptcookie value=1>Remember me\n<br>"
					<<	clsIntlResource::GetFResString(-1,
									"By choosing this option, you\'ll get a temporary \"cookie\" that enables you "
									"to skip this step if you request another user's email address during this "
									"browser session.  When you turn your browser off, the cookie will "
									"disappear. For more information about cookies, click "
									"<A HREF=\"%{1:GetHTMLPath}help/myinfo/cookies.html\">here</A>.",
									clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
									NULL);
	}
	else
	{
		// close the table
		*mpStream	<<	"</table>\n";
	}

	// add submit button and finish the form
	*mpStream	<<	"<p><input type=\"submit\" value=\"Submit\"></p>\n"
					"</form>\n";


	// the footer
	*mpStream << mpMarketPlace->GetFooter()
				 << flush;

	CleanUp();
	return;
}

