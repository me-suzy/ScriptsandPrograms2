/*	$Id: clseBayAppSendQueryEmail.cpp,v 1.5.66.6.16.1 1999/08/01 03:01:29 barry Exp $	*/
//
//	File:	clseBayAppSendQueryEmail.cpp
//
//	Class:	clseBayApp
//
//	Author:	Vicki Shu (vicki@ebay.com)
//
//	Function:
//
//		send email to support
//
// Modifications:
//				- 02/26/99 Vicki	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

static const char *ErrorMsgOmittedEmail =
"<h2>The e-mail address is omitted or invalid</h2>"
"Sorry, the e-mail address  is omitted or invalid. "
"Please remove any spaces "
"in your email address. "
"<p>AOL and WebTV Users:  Please remove any spaces from your username and add the domain suffix  "
"(<b>@aol.com</b> or <b>@webtv.net</b> to your username). "
"For example, if your username is <b>joecool</b>, your e-mail address would be <b>joecool@aol.com</b>. </p>";

void clseBayApp::SendQueryEmail(CEBayISAPIExtension *pServer,
							char * pUserId,
							char * pPass,
							char * pSubject,
							char * pMessage,
							int  MailDestination)
{
	int		mailRc;

	clsMail			Mail;
	ostream			*pMStream;
	char			*pEmail;


	// Setup
	SetUp();


	// Usual Title and Header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" E-mail Question to Support Confirmation"
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();


	if (FIELD_OMITTED(pSubject))
	{
		// subject cannot be NULL
		*mpStream	<< "<h2>Subject not selected</h2>"
					<< "Please select a subject."
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (FIELD_OMITTED(pMessage))
	{
		// msg cannot be NULL
		*mpStream	<< "<h2>Message cannot be empty</h2>"
					<< "Please go back and try again."
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	//for new user, we cannot check their userid/password
	if (stricmp(pSubject, "registering") ==0 || stricmp(pSubject, "npb appeals") ==0)
	{
		if( FIELD_OMITTED(pUserId) || !ValidateEmail(pUserId) )
		{
			*mpStream	<<	ErrorMsgOmittedEmail
						<<	"<BR><BR>";	
			*mpStream	<<	mpMarketPlace->GetSecureFooter();

			CleanUp();
			return;
		}

		pEmail = pUserId;

	}
	else
	{
		// Before we do anything, check the user again
		mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);

		if (!mpUser)
		{
			*mpStream  <<	"<p>"
					   <<	mpMarketPlace->GetFooter();
			CleanUp();
			return;
		}
		pEmail = mpUser->GetEmail();
	}

	// We need a mail object
	pMStream	= Mail.OpenStream();
		
	*pMStream <<	pMessage;
	// Sign the email with the sender's name
	*pMStream << "\n\n"
				 << "-- "
				 << pUserId
				 << "\n";

	//which email address?? for Potentially illegal item, need mail to support 
	if (MailDestination == 1 /*report infringing item */)
		mailRc =	Mail.Send((char *)mpMarketPlace->GetReportInfringingEmail(),
							pEmail,   
							pSubject,
							NULL,
							NULL,
							HELP_POOL);
	else
		// send the query to support
		mailRc =	Mail.Send((char *)mpMarketPlace->GetSupportEmail(),
								pEmail,   
								pSubject,
								NULL,
								NULL,
								HELP_POOL); 
	// handle send errors
	if (!mailRc)
	{
		*mpStream <<	"<h2>Unable to send email</h2>"
						"Sorry, we could not send the email now!"
						"<br>"
						"\n";
	}
	else
	{
		*mpStream <<	"<h2>Your email has been sent to eBay!</h2>"
						"In most cases, eBay will send you an email response within 24 hours. " 
						"<p>"
						"Return to <a href = \""
				<<		mpMarketPlace->GetHTMLPath()
				<<		"help/index.html\">"
						"Help Overview</a>.";


		*mpStream << "<br>"	
				  << "\n";
	}

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>"
					"\n";

	CleanUp();

	return;
}
