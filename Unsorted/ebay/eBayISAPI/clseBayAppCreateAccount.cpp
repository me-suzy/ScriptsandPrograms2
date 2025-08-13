/*	$Id: clseBayAppCreateAccount.cpp,v 1.4.362.1.102.1 1999/08/01 03:01:12 barry Exp $	*/
//
//	File:		clseBayAppCreateAccount.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)/yp
//
//	Function:
//
//
//	Modifications:
//				- 06/14/97 michael/yp	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()

#include "ebihdr.h"

#define CHECKED(x)	(!strcmp(x,"on"))


void clseBayApp::CreateAccount(CEBayISAPIExtension *pServer,
							   char *pUserId,
						       char *pPassword
							)
{
	// Setup
	SetUp();


	// Usual Title and Header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Create Account"
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// Before we do anything, check the user again
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPassword, mpStream);

	if (!mpUser)
	{
		*mpStream  <<	"<p>"
				   <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}


	// That was wasy!

	*mpStream <<	"<h2>Registered user status verified</h2>"
					"Now, you will proceed to our secure online account creation form. If this "
					"link doesn\'t work for you, you may be using a browser that does not "
					"support secure communication. In that case, follow this link to our <a "
					"href="
			  <<	mpMarketPlace->GetSecureHTMLPath()
			  <<	"cc-update.html"
					">"
					"account order form"
					"</a>"
					" for further instructions."
					"<p>"
					"<form method=post action="
					"https://business.best.com/ebay/s-create-account-2.cgi"
					">"
					"<input type=hidden name=email value="
					"\""
//			  <<	mpUser->GetUserId()
			  <<	mpUser->GetEmail()
			  <<	"\""
					">"
					"<strong>Press this button to proceed to our secure form:</strong>"
					"<p>"
					"<blockquote>"
					"<input type=submit value=\"proceed\">"
					"</blockquote>"
					"<p>"
					"</form>\n";

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

}
