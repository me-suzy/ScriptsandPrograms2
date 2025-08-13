/*	$Id: clseBayAppRequestPassword.cpp,v 1.7.166.3.76.1 1999/08/01 03:01:26 barry Exp $	*/
//
//	File:	clseBayAppRequestPassword.cpp
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Generate a new password for the user and
//		mail it to them.
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

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
"<br>"
"If you did not receive this e-mail, or if you have lost it, "
"please return to "
"<a href=\"http://pages.ebay.com/services/registration/register-by-country.html\">Registration</a>"
" and re-register "
"(with the same e-mail address) to have it sent to "
"you again.";

static const char *ErrorMsgSuspended =
"<h2>Registration Blocked</h2>"
"Sorry, registration is blocked for this account. ";

static const char *ErrorMsgUnknownState =
"<h2>Internal Error</h2>"
"Sorry, there was a problem confirming your registration. "
"Please contact <a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">"
"Customer Support</a> "
"if you have any questions about this problem. ";

void clseBayApp::RequestPassword(CEBayISAPIExtension *pServer,
								 char *pUserId)
{
/*	int		password;
	int		salt;
	char	cPassword[16];
	char	cSalt[16];
	char	*pCryptedPassword;
	clsAnnouncement			*pAnnouncement;

	clsMail	*pMail;
	ostream	*pMStream;
	char	subject[256];
	int		mailRc;

	char*	pTemp;*/

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Requesting a new Password"
			  <<	"</TITLE>"
			  <<	"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	*mpStream <<	"<h2>This function is not available</h2>"
					"Click "
			  <<	"<a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/buyandsell/reqpass.html"
			  <<	"\""
					">"
					"here"
					"</a>"
					" instead."
			  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;

/* function is removed; the rest of the functionality is a copy 
in AdminRequestPassword */

}

