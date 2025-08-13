/*	$Id: clseBayAppUserQuery.cpp,v 1.11.66.3.62.1 1999/08/01 03:01:35 barry Exp $	*/
//
//	File:	clseBayAppUserQuery.cpp
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
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clseBayTimeWidget.h"		// petra

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

static char *sAlertMailAddress = "safeharbor@ebay.com";

static void NotifyTooManyRequests(const char *pRemoteAddress,
								  const char *pEmail)
{
	clsMail theMail;
	ostream *pStream = theMail.OpenStream();

	*pStream << "This is an automated message to let you know that\n"
		<< pEmail << " has exceeded the allowed User Info queries.\n"
		"They made their request from IP:\n"
		<< pRemoteAddress
		<< "\n"
		"They are presently being denied user info.\n\n"
		"The WatchDog.\n";
//		"\nTHIS IS ONLY A TEST MESSAGE -- Thanks! -- chad@ebay.com";

	theMail.Send(sAlertMailAddress,
				 sAlertMailAddress,
				 "Automated User Info Abuse Alert");
}

void clseBayApp::UserQuery(CEBayISAPIExtension *pServer,
								char * pUserId,
								char * pPass,
								char * pRequestedUser)
{
	clsUser	*pOtherUser;

	char		*pCompany;
	char		*pOtherCompany;

	char		*pDayPhone;
	char		*pOtherDayPhone;

// petra	time_t		userTime;
// petra	time_t		otherUserTime;
// petra	struct tm	*pLocalTime;
// petra	char		cUserTime[64];
// petra	char		cOtherUserTime[64];

	char		toString[256];
	char		subjectString[256];

	clsMail		*pMail;
	ostream		*pStream;
	clsAnnouncement			*pAnnouncement;

	char*		pTemp;
	int rc;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" User Information Request"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	// Let's validate who we be
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Let's see if they are allowed.
	// The single = _is_ CORRECT here -- we only want to enter this block
	// if the return is not 0. Don't 'fix' it!
	if ((rc = mpUser->CanReceiveInfo(gApp->GetEnvironment()->GetRemoteAddr())))
	{
		// Uh oh. We better notify someone!
		if (rc == -1)
			NotifyTooManyRequests(gApp->GetEnvironment()->GetRemoteAddr(),
				mpUser->GetEmail());

		// And now we tell the user to go away.
		*mpStream	<< "<H2>Allowed Requests Exceeded</H2>"
				"Sorry, you have made too many requests for info today.<br>\n"
				"Please wait a day before you make additional requests.<p>"
				<< mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Let's see if the other user exists 
	strlwr(pRequestedUser);
	pOtherUser	=	mpUsers->GetUser(pRequestedUser);

	// Let's see how they are
	if (!pOtherUser)
	{
		*mpStream <<	"<h2>Requested user not found</h2>"
				  <<	"Sorry, the user your requested, "
				  <<	pRequestedUser
				  <<	", is not a registered "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" user. Please go back and try again. "
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Check if they are unconfirmed
	// Only let through confirmed, CCVerified (see Sam) or suspended users
	/*

	here is a list of all possible states (from ebayTypes.h)

	UserUnknown		= -1,
	UserSuspended	= 0,
	UserConfirmed	= 1,
	UserUnconfirmed	= 2,
	UserGhost		= 3,
	UserInMaintenance = 4,
	UserDeleted		= 5,
	UserCCVerify	= 6

	*/
	if (!pOtherUser->IsConfirmed() && !pOtherUser->IsSuspended() && !pOtherUser->IsCCVerify())
	{
		*mpStream <<	"<h2>Requested user is not confirmed</h2>"
						"Sorry, you can only request information for "
						"confirmed "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" users. If you believe that <b>"
				  <<	pRequestedUser
				  <<	"</b> is a registered user, please go back and try "
				  <<	"again."
						"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

/*
	pvh - removed to allow viewing contact info of suspended users
	
	// Validate their state
	if (!pOtherUser->IsConfirmed())
	{
		*mpStream <<	"<h2>Requested user is not confirmed</h2>"
						"Sorry, you can only request information for "
						"confirmed "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" users. If you believe that "
				  <<	pRequestedUser
				  <<	" is a registered user, please go back and try "
				  <<	"again."
						"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}
*/

	if (!pOtherUser->HasDetail())
	{
		*mpStream <<	"<h2>No Information on User</h2>"
				  <<	"Sorry, our records show no information on "
				  <<	pRequestedUser
				  <<	", although they should. This is an <b>error</b> "
				  <<	"condition, and should be reported to "
				  <<	"<A HREF="
				  <<	"\""
				 <<		mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)
				 <<		"eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue"
				 <<		"\">"
				 <<		"Customer Support"
				 <<		"</A>. "
						"Thank you!"
				  		"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (!mpUser->HasDetail())
	{
		*mpStream <<	"<h2>User Informations</h2>"
				  <<	"Sorry, our records show no information on "
				  <<	pRequestedUser
				  <<	", although they should. This is an <b>error</b> "
				  <<	"condition, and should be reported to "
				  <<	"<A HREF="
				  <<	"\""
				 <<		mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)
				 <<		"eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue"
				 <<		"\">"
				 <<		"Customer Support"
				 <<		"</A>. "
				  <<	"Thank you!"
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}


	// NULL check
	pCompany	= mpUser->GetCompany();
	if (!pCompany)
		pCompany	= "";

	pOtherCompany	= pOtherUser->GetCompany();
	if (!pOtherCompany)
		pOtherCompany	= "";

	pDayPhone	= mpUser->GetDayPhone();
	if (!pDayPhone)
		pDayPhone	= "";

	pOtherDayPhone	= pOtherUser->GetDayPhone();
	if (!pOtherDayPhone)
		pOtherDayPhone	= "";


	// *** NOTE ***
	// Here, we should do the privacy checks
	// *** NOTE ***

	// Date conversions
// petra	userTime	= mpUser->GetCreated();
// petra	pLocalTime	= localtime(&userTime);
// petra	strftime(cUserTime, sizeof(cUserTime),
// petra			 "%m/%d/%y %H:%M:%S %Z",
// petra			  pLocalTime);
// petra
// petra	otherUserTime	= pOtherUser->GetCreated();
// petra	pLocalTime		= localtime(&otherUserTime); //yp
// petra	strftime(cOtherUserTime, sizeof(cOtherUserTime),
// petra			 "%m/%d/%y %H:%M:%S %Z",
// petra			  pLocalTime);



	// Well, we'll be needing mail
	pMail	= new clsMail;
	pStream	= pMail->OpenStream();

	// First, mail to the requestee...
	*pStream <<		"Dear "
			 <<		mpUser->GetUserId()
			 <<		" and "
			 <<		pOtherUser->GetUserId()
			 <<		","
					"\n\n";

	// emit general announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pStream << pTemp;
		*pStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	// emit user info request announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(UserInfoReq,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pStream << pTemp;
		*pStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	*pStream <<		"This message is an automated reply to a request "
					"made by "
					"\n\n"
					"\t"
			 <<		mpUser->GetUserId()
			 <<		"\n\n"
					"This contact information may be used only for "
					"resolving matters related to "
			 <<		mpMarketPlace->GetCurrentPartnerName()
			 <<		". Any other use is strictly prohibited. "
			 <<		"Use of this feature to acquire contact information for the purposes of "
			 <<		"soliciting eBay customers is both illegal and a violation of eBay rules. "
			 <<		"If you believe that the requesting party is making this contact request "
			 <<		"with this intention, or if you have been contacted by someone who you "
			 <<		"believe secured your information via eBay for this purpose, please send a "
			 <<		"copy of this email to eBay's SafeHarbor investigations team at: "
			 <<		"safeharbor@ebay.com immediately."
					"\n\n"
					"The information contained herein has not been "
					"checked for accuracy; users are responsible for "
					"entering their own contact information accurately. "
					"If you receive information which you believe to be "
					"erroneous, please let "
			 <<		mpMarketPlace->GetCurrentPartnerName()
			 <<		" know."
					"\n\n"
					"Contact information for "
			 <<		pOtherUser->GetUserId()
			 <<		":"
					"\n"
			 <<		"\n\tE-mail:      "		<< pOtherUser->GetEmail()
			 <<		"\n\tUser ID:     "		<< pOtherUser->GetUserId()
			 <<		"\n\tName:        "		<< pOtherUser->GetName()	
			 <<		"\n\tCompany:     "		<< pOtherCompany	
//			 <<		"\n\tAddress:     "		<< pOtherUser->GetAddress()	
			 <<		"\n\tCity:        "		<< pOtherUser->GetCity()	
			 <<		"\n\tState:       "		<< pOtherUser->GetState()
			 <<		"\n\tCountry:     "		<< pOtherUser->GetCountry()	
			 <<		"\n\tZip:         "		<< pOtherUser->GetZip()
			 <<		"\n\tPhone:       "		<< pOtherDayPhone
			 <<		"\n\tTime/Date:   ";
	clseBayTimeWidget timeWidget (mpMarketPlace, 1, 2, pOtherUser->GetCreated() );	// petra
	timeWidget.EmitHTML (pStream);		// petra
// petra	<< cOtherUserTime
	*pStream <<		"\n"
			 <<		"\n"
			 <<		"Contact Information for: "
			 <<		mpUser->GetUserId()
			 <<		"\n"
			 <<		"\n\tE-mail:      "		<< mpUser->GetEmail()
			 <<		"\n\tUser ID:     "		<< mpUser->GetUserId()
			 <<		"\n\tName:        "		<< mpUser->GetName()	
			 <<		"\n\tCompany:     "		<< pCompany	
//			 <<		"\n\tAddress:     "		<< mpUser->GetAddress()	
			 <<		"\n\tCity:        "		<< mpUser->GetCity()	
			 <<		"\n\tState:       "		<< mpUser->GetState()
			 <<		"\n\tCountry:     "		<< mpUser->GetCountry()	
			 <<		"\n\tZip:         "		<< mpUser->GetZip()
			 <<		"\n\tPhone:       "		<< pDayPhone
			 <<		"\n\tTime/Date:   ";
	timeWidget.SetTime (mpUser->GetCreated() );		// petra
	timeWidget.EmitHTML (pStream);					// petra
// petra     << cUserTime
	*pStream <<		"\n"
			 <<		"\n"
//			 <<		"This request was made from IP address "
//			 <<		gApp->GetEnvironment()->GetRemoteAddr()
//			 <<		"If you have concerns about this request, please forward "
//			 <<		"this email to support@ebay.com."
//			 <<		"\n"
			 <<		"\n";

	// emit general announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pStream << pTemp;
		*pStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	// emit user info request announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(UserInfoReq,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pStream << pTemp;
		*pStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	*pStream <<		mpMarketPlace->GetThankYouText()
			 <<		"\n"
					"\t"
			 <<		mpMarketPlace->GetHomeURL()
			 <<		"\n";

	// Now, send it
	sprintf(toString, "%s,%s",
			mpUser->GetEmail(),
			pOtherUser->GetEmail());
	sprintf(subjectString, "%s User Information Request",
			mpMarketPlace->GetCurrentPartnerName());

	pMail->Send(mpUser->GetEmail(), 
				(char *)mpMarketPlace->GetConfirmEmail(),
				subjectString);
	pMail->Send(pOtherUser->GetEmail(), 
				(char *)mpMarketPlace->GetConfirmEmail(),
				subjectString);
				
	// Now, we can finally tell the user how wonderful they are
	*mpStream <<	"<h2>Request complete!</h2>"
					"Thank you for your request. Your request has been "
					"processed, and you will receive an e-mail message "
					"containing contact information for"
					"<p>"
					"<samp>"
			  <<	pRequestedUser
			  <<	"</samp>"
					"<p>"
					"Your contact information will also be provided to "
					"the other user."
					"\n"
					"<p>"
			  <<	mpMarketPlace->GetFooter();

	delete	pMail;
	delete	pOtherUser;

	CleanUp();
	return;
}

