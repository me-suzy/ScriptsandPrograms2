/*	$Id: clseBayAppAdminRequestPassword.cpp,v 1.8.66.4.62.2 1999/08/05 18:58:51 nsacco Exp $	*/
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
//
extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

// Error Messages
// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString

/*
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
"(with the same User ID and e-mail address) to have it sent to "
"you again."
"<br>"
"Please contact <a href=\"mailto:support@ebay.com\">Customer Support</a> if you have any questions.";
*/

static const char *ErrorMsgSuspended =
"<h2>Registration Blocked</h2>"
"Sorry, registration is blocked for this account. "
"Please contact <a href=\"mailto:support@ebay.com\">Customer Support</a> if you have any questions.";

static const char *ErrorMsgUnknownState =
"<h2>Internal Error</h2>"
"Sorry, there was a problem confirming your registration. "
"Please report this to <a href=\"mailto:support@ebay.com\">Customer Support</a>.";

void clseBayApp::AdminRequestPassword(CEBayISAPIExtension *pServer,
								 char *pUserId,
								eBayISAPIAuthEnum authLevel)
{
	int		password;
	int		salt;
	char	cPassword[16];
	char	cSalt[16];
	char	*pCryptedPassword;
	clsAnnouncement			*pAnnouncement;

	clsMail	*pMail;
	ostream	*pMStream;
	char	subject[256];
	int		mailRc;

	char*	pTemp;

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

	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	// Let's get the user
	mpUser = mpUsers->GetAndCheckUser(pUserId, mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<p>";
			
		CleanUp();

		return;
	}

	// We got the user. Let's ensure they're in the right
	// state.
	if (mpUser->IsSuspended())
	{
		*mpStream <<	ErrorMsgSuspended
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (!mpUser->IsConfirmed() && 
		mpUser->GetUserState() != UserGhost)
	{
	//	*mpStream <<	ErrorMsgNotConfirmed

	// kakiyama 07/09/99

		*mpStream << clsIntlResource::GetFResString(-1,
							"<h2>Unconfirmed Registration</h2>"
							"Sorry, you have not yet confirmed your registration."
							"You should have received an e-mail with instructions for "
							"confirming your registration. "
							"<br>"
							"If you did not receive this e-mail, or if you have lost it, "
							"please return to "
							"<a href=\"%{1:GetHTMLPath}services/registration/register-by-country.html\">Registration</a>"
							" and re-register "
							"(with the same User ID and e-mail address) to have it sent to "
							"you again."
							"<br>"
							"Please contact <a href=\"mailto:support@ebay.com\">Customer Support</a> if you have any questions.",
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL)
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}



	// Ok, let's generate new password and salt
	srand( (unsigned)time( NULL ) );
	password		= ((int)rand());
	salt			= ((int)rand());
	sprintf(cPassword, "%d", password);
	sprintf(cSalt, "%d", salt);

	// We need some mail now!
	pMail		= new clsMail;
	pMStream	= pMail->OpenStream();


	// Compose the mail
	strlwr(pUserId);
	*pMStream <<	"Dear "
			  <<	pUserId
			  <<	",\n"
			  <<	"\n";

	// emit general announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMStream << pTemp;
		*pMStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	// emit password request announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(PasswordReq,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMStream << pTemp;
		*pMStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	*pMStream <<	"You requested a new temporary password. Your old password\n"
			  <<	"has been disabled, and your new password is enclosed in\n"
			  <<	"this e-mail message.\n"
			  <<	"\n"
			  <<	"You can change your password to one of your own choosing\n"
			  <<	"by visiting "
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	"\'s Registered User Services\n"
			  <<	"section.\n"
			  <<	"\n"
			  <<    "To change your password, "
			  <<	"you will need your ";

	*pMStream <<	"User ID"
			  <<	" or e-mail address and temporary password"
			  <<	"\n";

	*pMStream <<	"User ID"
			  <<	":   "
			  <<	pUserId
			  <<	"\n"
			  <<	"Temporary password: "
			  <<	cPassword
			  <<	"\n"
			  <<	"\n"
			  <<	"IMPORTANT: YOU MUST CHANGE YOUR PASSWORD WITH THIS INFORMATION\n"
			  <<	"\n"
			  <<	"The URL for choosing a new password is:"
			  <<	"\n"
//			  <<	mpMarketPlace->GetHTMLPath()
//			  <<	"http://pages.ebay.com/"
// kakiyama 07/16/99
			  <<    mpMarketPlace->GetHTMLPath()
			  <<	"services/myebay/selectpass.html"
			  <<	"\n"
			  <<	"This new password request originated from IP address "
			  <<	gApp->GetEnvironment()->GetRemoteAddr()
			  <<	" . If you did not make this request, please forward this email "
			  <<	"to support@ebay.com immediately and we will investigate.\n"
					"\n";



	// emit general announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMStream << pTemp;
		*pMStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	// emit password request announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(PasswordReq,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMStream << pTemp;
		*pMStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	*pMStream	<<	mpMarketPlace->GetThankYouText()
				<<	"\n"
				<<	mpMarketPlace->GetHomeURL()
				<<	"\n";

	// Send
	sprintf(subject, "%s New password request",
			mpMarketPlace->GetCurrentPartnerName());

	mailRc =	pMail->Send(mpUser->GetEmail(), 
							(char *)mpMarketPlace->GetConfirmEmail(),
							subject);

	// We don't need no mail now
	delete	pMail;

	if (!mailRc)
	{
		*mpStream <<	"<h2>Unable to send new password</h2>"
				  <<	"Sorry, we could not send you your new password "
				  <<	"notice via electronic mail. This is probably because your E-Mail "
				  <<	"address was invalid. Please go back and try again. "
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}


	// Let's crypt the password
	pCryptedPassword	= crypt(cPassword, cSalt);

	mpUser->SetSalt(cSalt);
	mpUser->SetPassword(pCryptedPassword);
	mpUser->UpdateUser();

	// Now, we can finally tell the user how wonderful they are
	*mpStream <<	"<h2>New password generated</h2>"
			  <<	"Your new temporary password has been generated and sent "
			  <<	"to you via e-mail. Once you receive that message, you "
			  <<	"can come back to the Registered User Services password "
			  <<	"<a href="
//			  <<	mpMarketPlace->GetHTMLPath()
//			  <<	"http://pages.ebay.com/"
// kakiyama 07/16/99
			  <<    mpMarketPlace->GetHTMLPath()
			  <<	"services/myebay/selectpass.html"
			  <<	">"
			  <<	"selection page"
			  <<	"</a>"
			  <<	" to select a password of your own choosing."
			  <<	"<p>"
			  <<	"<br>"
			  <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	free(pCryptedPassword);
	CleanUp();
	return;

}

