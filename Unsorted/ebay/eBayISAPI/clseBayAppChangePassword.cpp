/*	$Id: clseBayAppChangePassword.cpp,v 1.8.22.4.14.1 1999/08/01 03:01:09 barry Exp $	*/
//
//	File:	clseBayAppChangePassword.cpp
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Handle a registration request
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 07/02/99 nsacco - removed use of mpMarketPlace->GetName()
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsPSSearches.h"

//
// Support for our own personal crypt
extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

// Error Messages
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

static const char *ErrorMsgSuspended =
"<h2>Registration Blocked</h2>"
"Sorry, registration is blocked for this account. ";

static const char *ErrorMsgUnknownState =
"<h2>Internal Error</h2>"
"Sorry, there was a problem confirming your registration. "
"Please report this to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.";

static const char *ErrorMsgNoPass =
"<h2>Password not entered</h2>"
"Sorry, you must enter your current password. "
"Please go back and try again.";

static const char *ErrorMsgBadUserOrPassword =
"<h2>Invalid User ID or password</h2>"
"Sorry, the User ID and/or the special password "
"is invalid. "
"Please go back and check it again. If you have forgotten your User ID, "
"you may use your e-mail address. ";

static const char *ErrorMsgNoNewPass =
"<h2>No new password</h2>"
"Sorry, you <b>must</b> supply a new password. "
"Please go back and try again.";

static const char *ErrorMsgNewPassDifferent =
"<h2>New passwords differ</h2>"
"Sorry, the two new passwords you entered are different. "
"Please go back and try again.";

static const char *ErrorMsgPasswordsTheSame =
"<h2>Old and new passwords the same</h2>"
"Sorry, your new password appears to be the same as your "
"old password. Please go back and choose a different new password.";



void clseBayApp::ChangePassword(CEBayISAPIExtension *pServer,
								char * pUserId,
								char * pPass,
								char * pNewPass,
								char * pNewPass2)
{
	bool	error		= false;
	int		salt;
	char	cSalt[16];
	char	*pCryptedPassword;

	clsPSSearches*	pPSSearches;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change Password"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";


	// Let's get the user. 
	mpUser	=	mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);

	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}


	// Ok, the user's ok. Let's check that we've got a 
	// password, and two NEW passwords.
	if (FIELD_OMITTED(pPass))
	{
		*mpStream <<	ErrorMsgNoPass
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Let's see if the password is correct
	strlwr(pPass);
	if (!mpUser->TestPass(pPass))
	{
		*mpStream <<	ErrorMsgBadUserOrPassword
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}


	// Check te new passwords
	if (FIELD_OMITTED(pNewPass) ||
		FIELD_OMITTED(pNewPass2))
	{
		*mpStream <<	ErrorMsgNoNewPass
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	strlwr(pNewPass);
	strlwr(pNewPass2);

	if (strcmp(pNewPass, pNewPass2) != 0)
	{
		*mpStream <<	ErrorMsgNewPassDifferent
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Make sure the new one isn't the same as the old
	// one
	if (strcmp(pPass, pNewPass) == 0)
	{
		*mpStream <<	ErrorMsgPasswordsTheSame
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	//
	// Now, new salt
	// 
	salt				= ((int)rand());
	sprintf(cSalt, "%d", salt);
	pCryptedPassword	= crypt(pNewPass, cSalt);

	// Tell PSSearches that the user has change password if the user uses personal shopper
	if (mpUser->GetOneUserFlag(UserFlagPersonalShopper))
	{
		pPSSearches = GetPSSearches();
		pPSSearches->ChangeEmailPassword(mpUser->GetEmail(), 
									mpUser->GetPassword(), 
									mpUser->GetEmail(), 
									pCryptedPassword);
	}


	// 
	// Set them!
	//
	mpUser->SetPassword(pCryptedPassword);
	mpUser->SetSalt(cSalt);

	// And update
	mpUser->UpdateUser();

	// Now, we can finally tell the user how wonderful they are
	*mpStream <<	"<h2>Password changed</h2>"
					"Your new password has been encrypted and recorded. "
					"If you forget your password in the future, you can "
					"go to the forgot password page from our site map.\n "
					"<p>"
			  <<	mpMarketPlace->GetFooter();

	free(pCryptedPassword);

	CleanUp();
	return;
}

void clseBayApp::ChangePasswordCrypted(CEBayISAPIExtension *pServer,
								char * pUserId,
								char * pPass,
								char * pNewPass,
								char * pNewPass2)
{
	bool	error		= false;

	clsPSSearches*	pPSSearches;
	char	OldPass[65];
	

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change Password"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";


	// Let's get the user. 
	mpUser	=	mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream, true, NULL, false, false, false, true);

	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}


	// Ok, the user's ok. Let's check that we've got a 
	// password, and two NEW passwords.
	if (FIELD_OMITTED(pPass))
	{
		*mpStream <<	ErrorMsgNoPass
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Let's see if the password is correct	
	if (!mpUser->TestCryptedPassword(pPass))
	{
		*mpStream <<	ErrorMsgBadUserOrPassword
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}


	// Check the new passwords
	if (FIELD_OMITTED(pNewPass) ||
		FIELD_OMITTED(pNewPass2))
	{
		*mpStream <<	ErrorMsgNoNewPass
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	strlwr(pNewPass);
	strlwr(pNewPass2);

	if (strcmp(pNewPass, pNewPass2) != 0)
	{
		*mpStream <<	ErrorMsgNewPassDifferent
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Make sure the new one isn't the same as the old
	// one
	if (strcmp(pPass, pNewPass) == 0)
	{
		*mpStream <<	ErrorMsgPasswordsTheSame
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// make a copy of the old password
	strcpy(OldPass, mpUser->GetPassword());

	mpUser->ChangePassword(pNewPass);

	// Tell PSSearches that the user has change password if the user uses personal shopper
	if (mpUser->GetOneUserFlag(UserFlagPersonalShopper))
	{
		pPSSearches = GetPSSearches();
		pPSSearches->ChangeEmailPassword(mpUser->GetEmail(), 
									OldPass, 
									mpUser->GetEmail(), 
									mpUser->GetPassword());
	}

	// Now, we can finally tell the user how wonderful they are
	*mpStream <<	"<h2>Password changed</h2>"
					"Your new password has been encrypted and recorded. "
					"If you forget your password in the future, you can "
					"go to the forgot password page from our site map.\n "
					"<p>"
			  <<	mpMarketPlace->GetFooter();	

	CleanUp();
	return;
}