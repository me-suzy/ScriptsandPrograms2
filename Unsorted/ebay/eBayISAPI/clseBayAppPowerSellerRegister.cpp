/*	$Id: clseBayAppPowerSellerRegister.cpp,v 1.2.2.1.86.2 1999/08/05 18:58:59 nsacco Exp $	*/
//
//	File:	clseBayAppPowerSellerRegister.cpp
//
//	Class:	clseBayApp
//
//	Author:	Vicki Shu (vicki@ebay.com)
//
//	Function:

// Modifications:
//				- 05/10/99 vicki	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clseBayUserDemoInfoWidget.h"

// Error Messages
// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgNotConfirmed =
"<h2>Unconfirmed Registration</h2>"
"Sorry. You have not yet confirmed your registration."
"You should have received an E-mail with instructions for "
"confirming your registration. "
"If you did not receive this e-mail, or if you have lost it, "
"please return to "
"<a href=\"http://pages.ebay.com/services/registration/register.html\">Registration</a>"
" and re-register "
"(with the same e-mail address) to have it sent to "
"you again.";
*/

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgSuspended =
"<h2>Registration Blocked</h2>"
"Sorry. Registration is blocked for this account. ";
*/

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgUnknownState =
"<h2>Internal Error</h2>"
"Sorry. There was a problem confirming your registration. "
"Please report this to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.";
*/

void clseBayApp::PowerSellerRegister(CEBayISAPIExtension *pServer,
									char * pUserId,
									char * pPass,
									bool agree)
{
	bool	error		= false;
	char NullStr = '\0';

	int	TopSellerLevel;
	int newLevel;




	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" PowerSeller Register"
					"</TITLE>"
					"</HEAD>";

	*mpStream <<	mpMarketPlace->GetHeader();

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
//		*mpStream <<	ErrorMsgNotConfirmed
//				  <<	"<br>";

// kakiyama 07/06/99

		*mpStream  
			<< clsIntlResource::GetFResString( -1,
				"<h2>Unconfirmed Registration</h2>"
				"Sorry. You have not yet confirmed your registration."
				"You should have received an E-mail with instructions for "
				"confirming your registration. "
				"If you did not receive this e-mail, or if you have lost it, "
				"please return to "
				"<a href=\"%{1:GetHTMLPath}services/registration/register.html\">Registration</a>"
				" and re-register "
				"(with the same e-mail address) to have it sent to "
				"you again.",
				clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
				NULL);

		*mpStream << "<br>";
		*mpStream <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	if (mpUser->IsSuspended())
	{
		//*mpStream <<	ErrorMsgSuspended
		//		  <<	"<br>";
// kakiyama 07/07/99

		*mpStream <<    clsIntlResource::GetFResString(-1,
							"<h2>Registration Blocked</h2>"
							"Sorry. Registration is blocked for this account. ",
							NULL);
		*mpStream << "<br>"
			      << mpMarketPlace->GetFooter();
	
		CleanUp();
		return;
	}

	if (!mpUser->IsConfirmed())
	{
	//	*mpStream  <<	ErrorMsgUnknownState
	//			   <<	"<br>";

	// kakiyama 07/07/99
		
		*mpStream << clsIntlResource::GetFResString(-1,
							"<h2>Internal Error</h2>"
							"Sorry. There was a problem confirming your registration. "
							"Please report this to "
							"<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.",
							clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
							NULL);

		*mpStream << "<br>"
			      << mpMarketPlace->GetFooter();
		
		CleanUp();
		return;
	}

	TopSellerLevel = mpUser->GetTopSellerLevel();
 
	//TopSeller level
	if (TopSellerLevel <= 0)
	{
		*mpStream <<	"<h2>Not Qualified the PowerSellers Program</h2>"
						"We're sorry, but this UserID <strong>"
				  <<	mpUser->GetUserId()
				  <<	"</strong> is not recognized as "
						"qualified for the Powersellers program at this time.  "
						"<p>For details about qualifications, please visit the "
						"<a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"powersellers.html\">PowerSellers Information page</a>"
						" to review eligibility requirements.  <p>If you have "
						"questions about program membership, please contact "
						"<A HREF=\"mailto:powersellersinfo@ebay.com\">powersellersinfo@ebay.com</a>";

		*mpStream <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}
	
	//check whether user already singed PowerSeller agreement
	if (TopSellerLevel >= TopSellerLevel_1_with_Agreement /*11*/)
	{
		*mpStream	<< "<h2>Signed the PowerSellers Program Terms and Conditions before</h2>";
		
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}
	// now, we have all
	if (!agree) 
	{
	// email to ebay, user delined the agreement.
	MailSupportUserDelinePowerSellerAgreement(mpUser);

	*mpStream <<	"<h2>Not interested in participating the PowerSellers program</h2>" 
			  <<	"<br>"
			  <<	mpMarketPlace->GetFooter();
	CleanUp();
	return;
	}
	
	//before insert into DB, let's check again
	//if we are in here, something is really wrong
	if (TopSellerLevel != TopSellerLevel_1 && TopSellerLevel != TopSellerLevel_2 
						&& TopSellerLevel != TopSellerLevel_3)
	{
		*mpStream	<<	"<h2>ERROR</h2>"
					<<	"Sorry, your PowerSeller status is wrong, please contact "
					<<	"<A HREF=\"mailto:powersellersinfo@ebay.com\">powersellersinfo@ebay.com</A>."
					<<	"<br>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;

	}
	// the user has accepted the user agreement, 
	newLevel = 11 * TopSellerLevel; // we already did like this way 1->11 2->22 3

	mpUser->SetTopSellerLevel((TopSellerLevelEnum)newLevel);
	// reocord the date
	mpUser->SetTopSellerInitiatedDate(time(0));
				
	mpUser->UpdateUser();

	//all set, show welcome msg

	*mpStream	<<	"<h2>Welcome to participate eBay PowerSellers program!</h2>"
				<<	"Congratulations!  <p>You are now an eBay PowerSeller! You will soon receive "
					"your PowerSellers membership package in the mail.  If you have any "
					"questions about the program, please contact "
					"<A HREF=\"mailto:powersellersinfo@ebay.com\">powersellersinfo@ebay.com</A>."
				<<	"<br>"
				<<	mpMarketPlace->GetFooter();

	CleanUp();
	return;

}

int clseBayApp::MailSupportUserDelinePowerSellerAgreement(clsUser *pUser)
											  
{
	clsMail			*pMail;
	ostream			*pMStream;
	char			 pSubject[256];
	int				 mailRc;
	char            *pTo = "powersellersinfo@ebay.com"; // email address to notify if user decline the agreement
	
//	char            *pTo = "vicki@ebay.com"; // testing 
	// We need a mail object
	pMail		= new clsMail;
	pMStream	= pMail->OpenStream();

	// Emit
	*pMStream 
		<<	"To Customer Support"
		<<	",\n";


	*pMStream	<<	"The user "
		        <<  pUser->GetUserId()
				<<	" (email address : "
				<<  pUser->GetEmail()
				<<	" )"
				<<  " declined the PowerSeller agreement "
				<<  ".\n\n"
				<<  "(This is an automatic message sent from the "
				<<  "PowerSllers Registration form to help track whether users "
				<<  "are delining PowerSellers Program Terms and Conditions .)"
				<<  "\n\n";

	*pMStream	<<	flush;

	// Send
	sprintf(pSubject, "%s -- Delined the PowerSellers registration ",
			pUser->GetUserId());

	mailRc = pMail->Send(pTo, pTo /* from */, pSubject); 

	// All done!
	delete	pMail;

	return mailRc;
}
