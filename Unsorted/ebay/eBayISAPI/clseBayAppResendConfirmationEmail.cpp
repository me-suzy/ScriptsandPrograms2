/*	$Id: clseBayAppResendConfirmationEmail.cpp,v 1.6.166.4.40.2 1999/08/05 18:59:03 nsacco Exp $	*/
//
//	File:	clseBayAppResendConfirmationEmail.cpp
//
//	Class:	clseBayApp
//
//	Author:	Vicki Shu (vicki@ebay.com)
//
//	Function:
//
//		re-send confirmation email with the same password
//
// Modifications:
//				- 08/18/98 Vicki	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

// Error Messages
static const char *ErrorMsgSuspended =
"<h2>Registration confirmed, but blocked</h2>"
"There is no need to register again, because your registration has "
"already been confirmed. However, your status has currently "
"been blocked due to the existence of an outstanding issue regarding "
"your account. Typically, this is because of a past due balance on "
"your account, or another issue that you should have already been "
"made aware of. "
"<br>";

static const char *ErrorMsgUnconfirmed =
"<h2>Confirmation Instructions have been resent!</h2>"
"An e-mail message containing instructions on confirming your "
" registration has sent to you. You\'ll need to follow those instructions before "
"your registration is enabled. This confirmation message will be "
"sent to you again now based on your request, so please wait for it to arrive and follow "
"the directions it contains."
"\n";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgNeverRegistered =
"<h2>You never registered </h2>"
"You never registered on eBay! Please go to our ";
//"<a href=\"http://pages.ebay.com/services/registration/register-by-country.html\">registration page</a>. ";


// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgUnknown =
"<h2>Unknown Registration Error</h2>"
"There has been an unknown error validating your registration. Please "
"report this error, along with all pertinent information (your selected "
"userid, name, address, etc.) to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">"
"Customer Support</a>. ";
*/

static const char *ErrorMsgNameTooLong =
"<h2>Error in Name</h2>"
"Sorry, the name you entered was too long. Please go back "
"and try again.";

static const char *ErrorMsgMail =
"<h2>Error Sending Confirmation Notice</h2>"
"Sorry, we could not send your registration confirmation "
"notice via electronic mail. This is probably because your e-mail "
"address was invalid. Please go back and check it again. ";

static const char *ErrorMsgOmittedEmail =
"<h2>The e-mail address is omitted or invalid</h2>"
"Sorry, the e-mail address is omitted or invalid. "
"Please go back and try again.";

void clseBayApp::ResendConfirmationEmail(CEBayISAPIExtension *pServer,
							char * pEmail)
{
	bool	error		= false;

	char*	cSalt;
	int		mailRc;
//	char*	pUserId;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Registration"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	// Is the field specified ???
	// Remove the space in pEmail and convert it to lower case
	if( FIELD_OMITTED(pEmail) || !ValidateEmail(pEmail) )
	{
		*mpStream	<<	ErrorMsgOmittedEmail
					<<	"<BR><BR>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

#ifdef _MSC_VER
		strlwr(pEmail);
#endif
/*
	// Check if it is from hotmail.com , mailcity.com or usa.net
	if (strstr(pEmail, "@hotmail.com")  != NULL ||
		strstr(pEmail, "@mailcity.com") != NULL ||
		strstr(pEmail, "@usa.net")      != NULL) 
	{
		// inform user not supporting hotmail
		*mpStream <<	"<h2>Unauthenticated Mail Service</h2>"
						"We\'re sorry, due to potential lack of "
						"authentication, we are no longer accepting "
						"registrations from your mail service for "
						"registered users. Please register using a "
						"different e-mail address."
				  <<	"<p><BR>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}
*/

	// Let's get user
	mpUser	=	mpUsers->GetUser(pEmail);
	if (mpUser)
	{
		// E-mail already seems to exist. Let's
		// see if they're suspended, or they just
		// exist already
		if (mpUser->IsSuspended())
		{
			*mpStream	<< "<BR>\n"	<<	ErrorMsgSuspended;
		}
		else if (mpUser->IsUnconfirmed() || mpUser->IsCCVerify())
		{
			// we do not want generate a new password, so we get it by SALT
			// because at the first time pwd alway same as SALT.
			cSalt=mpUser->GetSalt();

			// And mail the notice. We do this here because if we
			// have a problem, the user will never be able to 
			// confirm. 
			// UserId alway is email because we let user to chose UserID in 
			// confirmation process
			mailRc = MailUserRegistrationNotice(pEmail, pEmail, cSalt, mpUser->IsCCVerify());

			if (!mailRc)
			{
				*mpStream	<<	"<BR>\n"	<<	ErrorMsgMail;
			}
			else
			{
				*mpStream	<<	"<BR>\n"	<<	ErrorMsgUnconfirmed;
			} 
		} 
		else if (mpUser->IsConfirmed())
		{
			*mpStream	<<	"<BR>\n"
						<<	"<h2>Registration already confirmed!</h2>" 
							"There is no need to  register again, because your registration has already "
							"been confirmed. "
							"<br> "
							"Click here to: "
							"<ul> <li> <a href=\""
						<<	mpMarketPlace->GetHTMLPath()
						<<	"services/buyandsell/reqpass.html\">Request a new password</a>"
							"<li> <a href=\""
							<< mpMarketPlace->GetCGIPath(PageChangeUserId)
							<< "eBayISAPI.dll?ChangeUserid\">Change your User ID</a>"
							"<li> <a href=\""
						<<	mpMarketPlace->GetHTMLPath()
						<<	"/services/myebay/change-registration.html\">Change your registration information</a>"
							"</ul>";

		}
		else
		{
		//	*mpStream	<<	"<BR>\n"	<<	ErrorMsgUnknown;

		// kakiyama 07/07/99

			*mpStream   << "<BR>\n"		
				        << clsIntlResource::GetFResString(-1,
									"<h2>Unknown Registration Error</h2>"
									"There has been an unknown error validating your registration. Please "
									"report this error, along with all pertinent information (your selected "
									"userid, name, address, etc.) to "
									"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">"
									"Customer Support</a>. ",
									clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
									NULL);


		}
	}
	else
	{
	//	*mpStream	<<	ErrorMsgNeverRegistered;

	// kakiyama 07/07/99

		*mpStream   << clsIntlResource::GetFResString(-1,
							"<h2>You never registered </h2>"
							"You never registered on eBay! Please go to our "
							"<a href=\"%{1:GetHTMLPath}services/registration/register-by-country.html\">registration page</a>. ",
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL);

	}


	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();
	
	CleanUp();
	return;
}
