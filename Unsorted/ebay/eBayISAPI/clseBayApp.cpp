/*	$Id: clseBayApp.cpp,v 1.17.2.4.4.3 1999/08/10 01:19:49 nsacco Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 09/03/98	mila	- Made changes for new feedback stuff.
//				- 05/25/99	nsacco	- MarketPlace now uses clsSite
//				- 07/19/99	nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//				- 07/27/99	nsacco	- Fixed EmitHeader()
//				- 08/05/99	nsacco	- Added html and head tags as necessary.
//
//
#include "ebihdr.h"
#include "clsBase64.h"
#include "clsPSSearches.h"

// Used to reference functions in our caller.
// It's probably more "portable" to handle
// this stuff through clsEnvironment.


clseBayApp::clseBayApp()
{
	mpDatabase		= NULL;			// yp
	mpMarketPlaces	= NULL;
	mpMarketPlace	= NULL;
	mpItems			= NULL;
	mpUsers			= NULL;
	mpCategories	= NULL;
	mpLocations		= NULL;
	mpUserVerificationServices = NULL;
	mpStatistics	= NULL;
	mpAnnouncements	= NULL;

	mpItem			= NULL;
	mpUser			= NULL;
	mpFeedback		= NULL;

	mpvPartners		= NULL;

	mpAuthorizationQueue	= NULL;

	mpCookie		= NULL;

	mpPSSearches	= NULL;

	return;
}

//
// Destructor
//
//	Note that clsApp will take care of mpMarketPlaces and
//	mpDatabase
//

clseBayApp::~clseBayApp()
{
	vector<const char *>::iterator i;
//	EDEBUG('*', "~clseBayApp() Begin");

	delete			mpUser;
	delete			mpItem;
	delete			mpFeedback;
	delete			mpPSSearches;

	if (mpvPartners)
	{
		for (i = mpvPartners->begin(); i != mpvPartners->end(); ++i)
		{
			delete (void *) (*i);
		}
		delete mpvPartners;
	}

//	EDEBUG('*', "~clseBayApp() End");

	return;
}



//
// Common Setup routine
//
void clseBayApp::SetUp()
{
	// Obtain the objects we need. We don't do 
	// this in the CTOR since some of these objects
	// may depend on the particular Marketplace the
	// user is requesting.
	
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (IIS_Server_is_down()) return; //new outage code

	// *** NOTE ***
	// This is where we'd do marketplace stuff
	// *** NOTE ***
	if (!mpMarketPlaces)
		mpMarketPlaces	= gApp->GetMarketPlaces();
	
	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (mpMarketPlace)	
		mpMarketPlace->SetCurrentPage(GetCurrentPage());

	if (!mpItems)
		mpItems			= mpMarketPlace->GetItems();

	if (!mpUsers)
		mpUsers			= mpMarketPlace->GetUsers();

	if (!mpCategories)
		mpCategories	= mpMarketPlace->GetCategories();

	if (!mpLocations)
		mpLocations	= mpMarketPlace->GetLocations();

	if (!mpUserVerificationServices)
		mpUserVerificationServices	= mpMarketPlace->GetUserVerificationServices();

	if (!mpStatistics)
		mpStatistics	= mpMarketPlace->GetStatistics();

	if (!mpAnnouncements)
		mpAnnouncements	= mpMarketPlace->GetAnnouncements();

	if (!mpAuthorizationQueue)
		mpAuthorizationQueue	= new clsAuthorizationQueue;

	mpItem			= NULL;
	mpUser			= NULL;
	mpFeedback		= NULL;
	mpCookie		= NULL;
    mHasAdultCookie = -1;
	return;
}

void clseBayApp::SetCookie(COOKIE_ID id,
						   const char *pValue,
						   bool needCrypt,
						   time_t expires /*= NULL*/)
{
	if (!mpCookie)
    {
		mpCookie = new clseBayCookie;

	    // Make sure we take our current cookies into account.
	    mpCookie->SetCookiesFromClient(GetEnvironment()->GetCookie());
    }
	mpCookie->SetCookie(id, pValue, needCrypt, expires);
}

void clseBayApp::RemoveCookie(COOKIE_ID id)
{
	if (!mpCookie)
    {
		mpCookie = new clseBayCookie;

	    // Make sure we take our current cookies into account.
	    mpCookie->SetCookiesFromClient(GetEnvironment()->GetCookie());
    }
	mpCookie->RemoveCookie(id);
}

clseBayCookie *clseBayApp::GetCookie()
{
	return mpCookie;
}

bool clseBayApp::HasAdultCookie()
{
//#define _NO_ADULT_CHECK
#ifdef _NO_ADULT_CHECK
    char *pHost = GetEnvironment()->GetRemoteAddr();

    if (strcmp(pHost, "209.1.128.147") &&
        strcmp(pHost, "209.1.128.189") &&
        strcmp(pHost, "209.1.128.182") &&
        strcmp(pHost, "209.1.128.149") &&
        strcmp(pHost, "209.1.128.229"))
        return true;
#endif // _NO_ADULT_CHECK
#undef _NO_ADULT_CHECK

    const char *pValue;
    const char *pValueCheck;
    unsigned char key[16];

    if (mHasAdultCookie != -1)
        return mHasAdultCookie != 0;

    if (!mpCookie)
    {
		mpCookie = new clseBayCookie;
		mpCookie->SetCookiesFromClient(GetEnvironment()->GetCookie());
    }

    pValue = mpCookie->GetCookie(CookieAdult);
    if (!pValue)
    {
        mHasAdultCookie = 0;
        return false;
    }

    clsBase64 theBase;
	clseBayCookie::BuildAdultCookie(key, gApp->GetEnvironment()->GetBrowser());
    pValueCheck = theBase.Encode((const char *) key, sizeof (key));

    if (!strcmp(pValue, pValueCheck))
    {
        mHasAdultCookie = 1;
        return true;
    }

    mHasAdultCookie = 0;
    return false;
}

void clseBayApp::SendString(const char *pString)
{
	*mpStream << pString;
}

//
// Common Cleanup routine
//
void clseBayApp::CleanUp()
{
	if (mpItem)
	{
		delete	mpItem;
		mpItem	= NULL;
	}

	if (mpUser)
	{
		delete	mpUser;
		mpUser	= NULL;
	}

	if (mpFeedback)
	{
		delete	mpFeedback;
		mpFeedback	= NULL;
	}

	if (mpAuthorizationQueue)
	{
		delete mpAuthorizationQueue;
		mpAuthorizationQueue	= NULL;
	}

	delete mpCookie;
	mpCookie = NULL;
    mHasAdultCookie = -1;

	return;
}



//
// Parse Error
//
void clseBayApp::ParseError(int cause)
{
	SetUp();

	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Input Error"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader();

	*mpStream <<	"<H2>Input Error</H2>";

	switch (cause)
	{
		case CHttpServer::callParamRequired:
		case CHttpServer::callBadParamCount:
		case CHttpServer::callMissingParams:
			*mpStream <<	"We did not successfully receive all the "
							"required information from the page. "
							"Please go back and ensure that all fields "
							"are filled in.";
			break;

		case CHttpServer::callBadParam:
			*mpStream <<	"One of the parameters received was invalid "
							"for this function. This probably means "
							"that your browser had problems with the form "
							"or you invoked the function incorrectly. Please "
							"go back and try again. If you're using an old "
							"bookmark, you may need to rebookmark it due to "
							"recent changes to protect your privacy. If the "
							"problem persists, "
							"please report the problem to "
					  <<	mpMarketPlace->GetCurrentPartnerName()
					  <<	" "
					  <<	mpMarketPlace->GetSupportEmail()
					  <<	".";
			break;

		default:
			*mpStream <<	"There was an error in your input. Please go back "
							"and ensure that all fields are properly filled in.";
			break;
	}

	*mpStream <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}


//
// Drop a cookie to client
//
bool clseBayApp::DropUserIdCookie(char* pUserId, char* pPassword, CHttpServerContext* pCtxt)
{
	clseBayCookie	Cookie;
	const char*		TheHeader;
	clsUsers* pUsers = GetMarketPlaces()->GetCurrentMarketPlace()->GetUsers();
	clsUser*  pUser  = pUsers->GetAndCheckUserAndPassword(pUserId, pPassword, NULL);

	if (pUser == NULL)
	{
		return false;
	}
	delete pUser;

	// Get cookie from client before adding the new one (VERY IMPORTANT,
	// otherwise the existing cookies with be removed)
	Cookie.SetCookiesFromClient(gApp->GetEnvironment()->GetCookie());
	Cookie.SetCookie(COOKIE_USERID, pUserId, true);
	TheHeader = Cookie.GetCookieHeader();

	if (!TheHeader)
		return false;

	return pCtxt->ServerSupportFunction(
					HSE_REQ_SEND_RESPONSE_HEADER, 
					NULL,
					NULL,
					(unsigned long *) TheHeader) ? true : false;
}

//
// RemoveACookie
//
bool clseBayApp::RemoveACookie(CHttpServerContext* pCtxt, int Id)
{
	clseBayCookie	Cookie;
	const char*		TheHeader;

	// Get cookie from client 
	Cookie.SetCookiesFromClient(gApp->GetEnvironment()->GetCookie());
	Cookie.RemoveCookie((COOKIE_ID) Id);
	TheHeader = Cookie.GetCookieHeader();

	if (!TheHeader)
		return false;

	return pCtxt->ServerSupportFunction(HSE_REQ_SEND_RESPONSE_HEADER,
								 NULL,
								 NULL,
								 (unsigned long *) TheHeader) ? true : false;
}

//
// Remove (logout) cookie
//
void clseBayApp::RemoveUserIdCookie(CHttpServerContext* pCtxt, bool Success)
{
	SetUp();

	// Heading, etc
	*mpStream <<	"<html><head><title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Logout"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// header
	*mpStream	<<	"<h2>Logout</h2>\n";

	*mpStream << "Logout!";

	// the footer
	*mpStream << mpMarketPlace->GetFooter()
				 << flush;

	CleanUp();
	return;

}


void clseBayApp::EmitHeader(const char *title)
{
	// nsacco 07/27/99 added missing <html> and <head> tags
	*mpStream << "<html>"
		<< "<head>"
		<< "<title>"
		<< mpMarketPlace->GetCurrentPartnerName()
		<< " " 
		<< title
		<< "</title>"
		<< "</head>"
		<< mpMarketPlace->GetHeader()
		<< "\n"
		<< flush;
}

// CheckAuthorization: check it the user is authorized sufficiently, and
// if not, complain. In debug mode, prints a very noisy message on the relevant
// page, and also generates a warning to make it even more obvious.
//
// TODO - change this back to 1 !!!
#define AUTHORIZE_CHECK 0

#if AUTHORIZE_CHECK==0
#pragma message("Warning: " __FILE__ "                  AUTHORIZATION CHECK DISABLED")
bool authorizationEnabled = false;
#else
bool authorizationEnabled = true;
#endif

bool clseBayApp::CheckAuthorization(eBayISAPIAuthEnum authLevel)
{
	if (authLevel == eBayISAPIAuthSupport ||
		authLevel == eBayISAPIAuthAdmin)
		return true;
#if AUTHORIZE_CHECK==1	// If in production
	*mpStream <<    "<h2>Not Authorized</h2>"
		"You are not authorized to use this "
		<<    mpMarketPlace->GetCurrentPartnerName()
		<<    " function. "
		<<    "<p>"
		<<    mpMarketPlace->GetFooter();
	return false;
#else	// not in production, only testing
	*mpStream << "<h2>Warning -- Authorization check disabled</h2><p>";
	return true;
#endif
}

//
// Maps an eNote type to the email template
//
const char *clseBayApp::GetEmailTemplateForNoteType(unsigned int type)
{
	clsMapNoteTypeToMailTemplate	*pTemplate;

	for (pTemplate = clseBayApp::mMapNoteTypeToMailTemplate;
		 pTemplate->mNoteType != 0;
		 pTemplate++)
	{
		if (pTemplate->mNoteType == type)
			return	pTemplate->mpEmailTemplate;
	}

	// If we're here, not found. Just use the last entry, which is the
	// default.
	return pTemplate->mpEmailTemplate;
}

//
// Maps an eNote type to the email subject
//
const char *clseBayApp::GetEmailSubjectForNoteType(unsigned int type)
{
	clsMapNoteTypeToMailTemplate	*pTemplate;

	for (pTemplate = clseBayApp::mMapNoteTypeToMailTemplate;
		 pTemplate->mNoteType != 0;
		 pTemplate++)
	{
		if (pTemplate->mNoteType == type)
			return	pTemplate->mpEmailSubject;
	}

	// If we're here, not found. Just use the last entry, which is the
	// default.
	return pTemplate->mpEmailSubject;
}

//
// Maps an eNote type to the bidder email template
//
const char *clseBayApp::GetBidderEmailTemplateForNoteType(unsigned int type)
{
	clsMapNoteTypeToMailTemplate	*pTemplate;

	for (pTemplate = clseBayApp::mMapNoteTypeToMailTemplate;
		 pTemplate->mNoteType != 0;
		 pTemplate++)
	{
		if (pTemplate->mNoteType == type)
			return	pTemplate->mpBidderEmailTemplate;
	}

	// If we're here, not found. Just use the last entry, which is the
	// default.
	return pTemplate->mpBidderEmailTemplate;
}

//
// Maps an eNote type to the bidder email template when the seller is suspened
//
const char *clseBayApp::GetBidderEmailSellerSuspendedTemplateForNoteType(unsigned int type)
{
	clsMapNoteTypeToMailTemplate	*pTemplate;

	for (pTemplate = clseBayApp::mMapNoteTypeToMailTemplate;
		 pTemplate->mNoteType != 0;
		 pTemplate++)
	{
		if (pTemplate->mNoteType == type)
			return	pTemplate->mpBidderEmailSellerSuspendedTemplate;
	}

	// If we're here, not found. Just use the last entry, which is the
	// default.
	return pTemplate->mpBidderEmailSellerSuspendedTemplate;
}

//
// Maps an eNote type to the bidder email subject
//
const char *clseBayApp::GetBidderEmailSubjectForNoteType(unsigned int type)
{
	clsMapNoteTypeToMailTemplate	*pTemplate;

	for (pTemplate = clseBayApp::mMapNoteTypeToMailTemplate;
		 pTemplate->mNoteType != 0;
		 pTemplate++)
	{
		if (pTemplate->mNoteType == type)
			return	pTemplate->mpBidderEmailSubject;
	}

	// If we're here, not found. Just use the last entry, which is the
	// default.
	return pTemplate->mpBidderEmailSubject;
}


//
// Maps an eNote type to the buddy email template
//
const char *clseBayApp::GetBuddyEmailTemplateForNoteType(unsigned int type)
{
	clsMapNoteTypeToMailTemplate	*pTemplate;

	for (pTemplate = clseBayApp::mMapNoteTypeToMailTemplate;
		 pTemplate->mNoteType != 0;
		 pTemplate++)
	{
		if (pTemplate->mNoteType == type)
			return	pTemplate->mpBuddyEmailTemplate;
	}

	// If we're here, not found. Just use the last entry, which is the
	// default.
	return pTemplate->mpBuddyEmailTemplate;
}

//
// Maps an eNote type to the buddy email subject
//
const char *clseBayApp::GetBuddyEmailSubjectForNoteType(unsigned int type)
{
	clsMapNoteTypeToMailTemplate	*pTemplate;

	for (pTemplate = clseBayApp::mMapNoteTypeToMailTemplate;
		 pTemplate->mNoteType != 0;
		 pTemplate++)
	{
		if (pTemplate->mNoteType == type)
			return	pTemplate->mpBuddyEmailSubject;
	}

	// If we're here, not found. Just use the last entry, which is the
	// default.
	return pTemplate->mpBuddyEmailSubject;
}
//
// Given a buddy id, finds the member describing the buddy
//
const clsCopyrightBuddyInfo *clseBayApp::GetBuddyInfo(unsigned int buddyId)
{
	clsCopyrightBuddyInfo	*pBuddy;

	for (pBuddy = clseBayApp::mCopyrightBuddyInfo;
		 pBuddy->mBuddyId != 0;
		 pBuddy++)
	{
		if (pBuddy->mBuddyId == buddyId)
			return	pBuddy;
	}

	// If we're here, not found. Just use the last entry, which is the
	// default.
	return pBuddy;
}

//
// Emits a list of buddies as a drop-down list. 
//
//
// This routine emits a nice list of all the
// note types as HTML Option tags
//
void clseBayApp::EmitBuddyInfoAsHTMLOptions(unsigned int currentBuddyId)
{
	clsCopyrightBuddyInfo	*pBuddy;

	for (pBuddy = clseBayApp::mCopyrightBuddyInfo;
		 ;
		 pBuddy++)
	{
		*mpStream <<	"<OPTION VALUE=\""
				 <<	pBuddy->mBuddyId
				 <<	"\"";

		if (pBuddy->mBuddyId == currentBuddyId)
			*mpStream <<	" SELECTED";

		*mpStream <<	">"
				 <<	pBuddy->mpBuddyDropDownName
				 << "</OPTION>";

		if (pBuddy->mBuddyId == 0)
			break;
	}

	return;
}

void clseBayApp::TurnOnBidNoticesChinese()
{
//	mpMarketPlace->SetBidNoticesChinese(true);

	return;
}

void clseBayApp::TurnOffBidNoticesChinese()
{
//	mpMarketPlace->SetBidNoticesChinese(false);

	return;
}

void clseBayApp::TurnOnBidNoticesDutch()
{
//	mpMarketPlace->SetBidNoticesDutch(true);

	return;
}

void clseBayApp::TurnOffBidNoticesDutch()
{
//	mpMarketPlace->SetBidNoticesDutch(false);

	return;
}

void clseBayApp::TurnOnOutBidNoticesChinese()
{
//	mpMarketPlace->SetOutBidNoticesChinese(true);

	return;
}

void clseBayApp::TurnOffOutBidNoticesChinese()
{
//	mpMarketPlace->SetOutBidNoticesChinese(false);

	return;
}

//
// return the pointer for Personal Searches
clsPSSearches* clseBayApp::GetPSSearches()
{
	if (!mpPSSearches)
	{
		// if there isn't one, create one.
		mpPSSearches = new clsPSSearches;
		mpPSSearches->SetProps();
	}

	return mpPSSearches;
}

void clseBayApp::InitISAPI(unsigned char *pCtx)
{
	clsApp::InitISAPI(pCtx);

	GetMarketPlaces()->GetCurrentMarketPlace()->GetUsers()->GetUserValidation()->ClearValidation();

	// wen 06/06/99 make sure the new site is set
	GetMarketPlaces()->GetCurrentMarketPlace()->GetSites()->ResetCurrentSite();
}
