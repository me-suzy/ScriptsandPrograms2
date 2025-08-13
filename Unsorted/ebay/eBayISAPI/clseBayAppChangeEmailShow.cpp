/*	$Id: clseBayAppChangeEmailShow.cpp,v 1.10.66.3.62.2 1999/08/05 18:58:53 nsacco Exp $	*/
//
//	File:	clseBayAppChangeEmailShow.cpp
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Handle a change email request request
//
// Modifications:
//				- 09/11/97 tini	- Created
//				- 07/02/99 nsacco - removed use of mpMarketPlace->GetName()
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

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgEmailUsed =
"<h2>Email Already Taken.</h2>"
"Sorry, we already have a record of your new e-mail address. "
"If you would like to merge your new e-mail account and your old e-mail account,"
" please go to our <a href=\"http://pages.ebay.com/help/myinfo/index.html\">My Information</a> page "
"for more information.";
*/

static const char *ErrorMsgUnknown =
"<h2>Unknown Registration Error</h2>"
"There has been an unknown error validating your registration. Please "
"report this error, along with all pertinent information (your selected "
"User ID, name, address, etc.) to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.";

static const char *ErrorMsgMail =
"<h2>Error Sending Confirmation Notice</h2>"
"Sorry, we could not send you your registration confirmation "
"notice via electronic mail. This is probably because your e-mail "
"address was invalid. Please go back and check it again. ";


static const char *ErrorMsgOmittedEmail =
"<h2>The e-mail is omitted or invalid</h2>"
"Sorry, the e-mail is omitted or invalid. "
"Please go back and try again.";

// inform user not supporting hotmail
static const char *ErrorMsgUnauthenticatedService =
"<h2>Unauthenticated Mail Service</h2>"
"We\'re sorry, due to potential lack of "
"authentication, we are no longer accepting "
"registrations from your mail service for "
"registered users. Please register using a "
"different e-mail address.";

// bool clseBayApp::ValidateEmail(char* pUserId)
//bool clseBayApp::ValidateChangeEmailInfo(char *pFromUserId,
//											char *pToUserId)
//{
	// check if FromUserId is already in the database

//}

// 
// MailUserRegistrationNotice
//
int clseBayApp::MailUserChangeEmailNotice(char *pUserId,
											char *pEmail,
											char *pPassword)
{
	clsMail		*pMail;
	ostream		*pMStream;
	char		subject[256];
	int			mailRc;
	clsAnnouncement			*pAnnouncement;
	char*		pTemp;

	// We need a mail object
	pMail		= new clsMail;
	pMStream	= pMail->OpenStream();

	// Emit
	*pMStream 
		<<	"Dear "
		<<	pUserId
		<<	",\n\n";

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

	// emit change email announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(ChgEmail,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMStream << pTemp;
		*pMStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	*pMStream	<<	"PLEASE READ THIS MESSAGE OR YOUR E-MAIL CHANGE WILL NOT BE ACTIVATED!\n"
			"\n"
			"YOU MUST ENTER THE CONFIRMATION CODE CONTAINED IN THIS MESSAGE IN\n"
			"OUR CONFIRMATION FORM IN ORDER TO ACTIVATE YOUR CHANGE OF E-MAIL.\n"
			"\n"
			"Please access the following form to confirm your change of e-mail:\n"
			"\n"
			"  "
		<<	mpMarketPlace->GetCGIPath(PageChangeEmailConfirm)
		<<	"ebayISAPI.dll?ChangeEmailConfirm"
			"\n"
			"You can also access this from our Registered User Services menu.\n"
			"\n"
			"You will be asked for the following information, which you\n"
			"must type EXACTLY as it appears below:\n"
			"\n"
			"  User Id:            "
		<<  pUserId
		<<  "\n"
			"  New E-mail address: "
		<<	pEmail
		<<	"\n"
		<<	"  Confirmation code:  "
		<<	pPassword
		<<	"\n"
			"\n";

	// emit general footer announcements
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

	// emit change email footer announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(ChgEmail,Footer,
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
	// nsacco 07/02/99 removed mpMarketPlace-GetName()
	sprintf(subject, "eBay Change E-mail");

	mailRc =	pMail->Send(pEmail, 
							(char *)mpMarketPlace->GetConfirmEmail(),
							subject);

	// All done!
	delete	pMail;

	return mailRc;
}

void clseBayApp::ChangeEmailShow(CEBayISAPIExtension *pServer,
							char *pUserId, char *pPass, char *pNewEmail)
{
	bool	error		= false;


	int		password;
	char	cPassword[5];
	char	cSalt[5];
	char	*pCryptedPassword;

	int		mailRc;

	clsUser*	pNewUser;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change E-mail"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	// Remove the space in pNewEmail and convert it to lower case
	if( !ValidateEmail(pNewEmail) )
	{
		*mpStream	<<	ErrorMsgOmittedEmail
					<<	"<BR>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}
/*
	// Check if it is from hotmail.com , mailcity.com or usa.net
	if (strstr(pNewEmail, "@hotmail.com")  != NULL ||
		strstr(pNewEmail, "@mailcity.com") != NULL ||
		strstr(pNewEmail, "@usa.net")      != NULL) 
	{
		// inform user not supporting hotmail
		*mpStream <<	ErrorMsgUnauthenticatedService
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}
*/
	// get and check user and password
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream); 
	if (!mpUser)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	//check the new email is same as old one, if so, we are done

	if(strcmp (mpUser->GetEmail(), pNewEmail) == 0) {
		    
        *mpStream << "<h2> This is your current e-mail address </h2>"
			      << "\"<I>"
			      << pNewEmail
		          << "\" </I>is your current e-mail address. "
			      << "Please go back and check again!"
				  << "<br>"
				  << mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// We got the user. Let's ensure they're in the right
	// state.
	if (!mpUser->IsConfirmed())
	{
	//	*mpStream <<	ErrorMsgNotConfirmed

	// kakiyama 07/07/99

		*mpStream <<    clsIntlResource::GetFResString(-1,
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

	// Let's see if the userid is already taken. We 
	// can't use GetAndCheckUser, since it emits
	// error messages.
	pNewUser	=	mpUsers->GetUserByEmail(pNewEmail);
	if (pNewUser && stricmp(pNewUser->GetUserId(), pUserId) != 0)
	{
		// UserId already seems to exist. Cannot rename.
		
	//	*mpStream <<	ErrorMsgEmailUsed

	// kakiyama 07/09/99

	    *mpStream << clsIntlResource::GetFResString(-1,
							"<h2>Email Already Taken.</h2>"
							"Sorry, we already have a record of your new e-mail address. "
							"If you would like to merge your new e-mail account and your old e-mail account,"
							" please go to our <a href=\"%{1:GetHTMLPath}help/myinfo/index.html\">My Information</a> page "
							"for more information.",
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL)
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		delete pNewUser;
		CleanUp();
		return;
	}
	delete pNewUser;

	// validate in rename_pending table.
	// right now, if user enters request multiple times, it will ignore

	// Now, for the special password. 
	// 
	// *** IMPORTANT ***
	// Since we might have to re-mail the user their 
	// password, we need to have a way to extract the
	// "first" password in cleartext. Soooo, the first
	// password is the same as the salt. Isn't that
	// just oogy?
	// *** IMPORTANT ***
	//
	srand( (unsigned)time( NULL ) );
	password		= ((int)rand());
	sprintf(cPassword, "%d", password);
	sprintf(cSalt, "%d", password);

	// Let's encrypt it
	pCryptedPassword	= crypt(cPassword, cSalt);

	// put in rename_pending table
	mpUser->SetRenamePending(pNewEmail,pCryptedPassword,cSalt);

	// And mail the notice. We do this here because if we
	// have a problem, the user will never be able to 
	// confirm.
	mailRc = MailUserChangeEmailNotice(pUserId, pNewEmail, cPassword);

	if (!mailRc)
	{
		*mpStream <<	ErrorMsgMail
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}


	// Now, we can finally tell the user what happened
	*mpStream <<	"<h2>Change e-mail process begun!</h2>"
					"\n"
					"Thank you for taking the first step to change your e-mail "
					"with eBay! "
			  <<	"You will receive an e-mail message confirming "
					"this change of e-mail and including a special code."
					"<p>"
					"\n"
					"Once you receive this message, you will be instructed "
					"to return to this site to enter the confirmation code in a "
					"special form. This will confirm and finalize your "
					"registration immediately. Generally, your confirmation "
					"e-mail and code should arrive within 24 hours, "
					"depending on your service provider."
					"<p>"
					"\n"
			  <<	mpMarketPlace->GetFooter();

	free	(pCryptedPassword);

	CleanUp();
	return;
}

