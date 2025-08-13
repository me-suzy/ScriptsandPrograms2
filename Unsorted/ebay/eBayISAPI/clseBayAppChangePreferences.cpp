/*	$Id: clseBayAppChangePreferences.cpp,v 1.7.166.3.74.2 1999/08/05 18:58:53 nsacco Exp $	*/
//
//	File:	clseBayAppChangeRegistration.cpp
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Update a user's registration information. 
//		
//		** NOTE **
//		Uses ValidateRegistrationInfo in clseBayAppRegister.cpp
//		** NOTE **
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 07/02/99 nsacco - removed use of mpMarketPlace->GetName()
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

// Error Messages
// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgNotConfirmed =
"<h2>Unconfirmed Registration</h2>"
"Sorry, you have not yet confirmed your registration."
"You should have received an e-mail with instructions for "
"confirming your registration. "
"If you did not receive this e-mail, or if you have lost it, "
"please return to "
"<a href=\"http://pages.ebay.com/services/registration/register-by-country.html\">Registration</a>"
" and re-register "
"(with the same User ID and e-mail address) to have it sent to "
"you again.";
*/

static const char *ErrorMsgSuspended =
"<h2>Registration Blocked</h2>"
"Sorry, Registration is blocked for this account. ";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgUnknownState =
"<h2>Internal Error</h2>"
"Sorry, there was a problem confirming your registration. "
"Please report this to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.";
*/

void clseBayApp::ChangePreferences(CEBayISAPIExtension *pServer,
									char * pUserId,
									char * pPass,
									int interest_1,
									int interest_2,
									int interest_3,
									int interest_4
									)
{
	bool	error		= false;
	char NullStr = '\0';

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change Preferences"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	//
	// Now let's try the encripted password
	// The last parameter allows the method to check if the password 
	// is the encrypted one stored in the database
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream, true, NULL,
													false, false, false, true);
	if (!mpUser)
	{
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// We got the user. Let's ensure they're in the right
	// state.
	if (!mpUser->IsConfirmed())
	{
	//	*mpStream <<	ErrorMsgNotConfirmed

	// kakiyama 07/07/99

		*mpStream << clsIntlResource::GetFResString(-1,
							"<h2>Unconfirmed Registration</h2>"
							"Sorry, you have not yet confirmed your registration."
							"You should have received an e-mail with instructions for "
							"confirming your registration. "
							"If you did not receive this e-mail, or if you have lost it, "
							"please return to "
							"<a href=\"%{1:GetHTMLPath}services/registration/register-by-country.html\">Registration</a>"
							" and re-register "
							"(with the same User ID and e-mail address) to have it sent to "
							"you again.",
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL)
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (mpUser->IsSuspended())
	{
		*mpStream <<	ErrorMsgSuspended
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (!mpUser->IsConfirmed())
	{
	//	*mpStream  <<	ErrorMsgUnknownState

	// kakiyama 07/09/99

		*mpStream  << clsIntlResource::GetFResString(-1,
							"<h2>Internal Error</h2>"
							"Sorry, there was a problem confirming your registration. "
							"Please report this to "
							"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.",
							clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
							NULL)
				   <<	"<br>"
				   <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}


	// Now, let's touch everything. We don't know what's changed,
	// so, we'll change  it all!
	mpUser->SetInterests_1(interest_1);
	mpUser->SetInterests_2(interest_2);
	mpUser->SetInterests_3(interest_3);
	mpUser->SetInterests_4(interest_4);

	mpUser->UpdateUser();


	// Now, we can finally tell the user how wonderful they are
	*mpStream <<	"<h2>Your preferences have been updated</h2>"
					"Thank you for taking the time to let us know about "
					"changes to your preferences.";


	// Now, we delete the user object and re-fetch it. The primary
	// reason for this is to make sure the "last modified" date
	// if correct.
	delete mpUser;
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream, true, NULL,
													false, false, false, true);
	if (!mpUser)
	{
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;
}

