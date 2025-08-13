/*	$Id: clseBayAppGetBidderEmails.cpp,v 1.7.204.1.108.1 1999/08/01 03:01:14 barry Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Display pages that ask user user id and password
//
//	Modifications:
//				- 12/19/97 Wen	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

#include "clsUserValidation.h"

// Used to reference functions in our caller.
// It's probably more "portable" to handle
// this stuff through clsEnvironment.


void clseBayApp::GetBidderEmails(CEBayISAPIExtension *pServer, int Item, int PageType)
{
	clsUserValidation *pValidation;


	pValidation = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetUsers()->GetUserValidation();
	if (pValidation->IsSoftValidated())
	{
		if (PageType == PageViewBidDutchHighBidderEmails)
			ViewBidDutchHighBidderEmails(NULL, Item, NULL, NULL);
		else
			ViewBidderWithEmails(NULL, Item, NULL, NULL);
		return;
	}

	SetUp();
	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Request User Password"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// header
	*mpStream	<<	"<h2>Please Provide Your User ID and Password</h2>\n";

	// legal, rule
	*mpStream	<< "<font size=-1>" 
		        << "eBay kindly requests that you submit your User ID and password to "
                << "view the e-mail address of another user. <I>Thank you!</I></font>";
              
	// form
	*mpStream	<<	"<form method=\"POST\" action=\""
				<<	mpMarketPlace->GetCGIPath((PageEnum)PageType)
				<<	"eBayISAPI.dll\">\n"
					"<input TYPE=HIDDEN NAME=\"MfcISAPICommand\" ";

	if (PageType == PageViewBidDutchHighBidderEmails)
	{
		*mpStream	<<	"VALUE=\"ViewBidDutchHighBidderEmails\">\n";
	}
	else if (PageType == PageViewBidderWithEmails)
	{
		*mpStream	<<	"VALUE=\"ViewBidderWithEmails\">\n";
	}

	*mpStream	<<	"<input TYPE=HIDDEN NAME=\"item\" "
					"VALUE=\""
				<<	Item
				<<	"\">\n"
					"<table border=0 cellspacing=3 cellpadding=2>\n";

	*mpStream	<<	"<tr><td>Your "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	":</td>\n"
					"<td><input type=\"text\" name=\"userid\" size=40></td></tr>\n"
					"<tr><td>"
				<<	mpMarketPlace->GetPasswordPrompt()
				<<	":</td>\n"
					"<td><input type=\"password\" name=\"pass\" size=40></td></tr>\n"
					"</table>\n"
					"<p><input type=\"submit\" value=\"Submit\"></p>\n"
					"</form>\n";
	*mpStream   <<  "<p>"
		        <<  "You must be a registered eBay user to view the bidding "
				<<  "history of this item. If you are not a registered eBay "
				<<  "user, you can "
				    "<a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/registration/register-by-country.html"
					"\""
					">"
				<<  "register</A> now "
				<<  "for <strong> free </strong>.";

	// the footer
	*mpStream << mpMarketPlace->GetFooter()
				 << flush;

	CleanUp();
	return;
}

