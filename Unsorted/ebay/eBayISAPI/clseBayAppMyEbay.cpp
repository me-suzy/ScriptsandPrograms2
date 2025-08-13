/*	$Id: clseBayAppMyEbay.cpp,v 1.7.204.2 1999/07/19 18:47:43 poon Exp $	*/
//
//	File:	clseBayAppMyEbay.cpp
//
//	Class:	clseBayAppMyEbay
//
//	Author:	Alex Poon (poon@ebay.com)
//
//	Function:
//
//		Displays the my ebay page
//
// Modifications:
//				- 11/14/97 poon	- Created
//				- 12/03/97 charles added the password encryption
//

//#define PURIFY_H_VERSION 1
#ifdef PURIFY_H_VERSION
#include "pure.h"
#endif

#include "ebihdr.h"
#include "clseBayUserSellingWidget.h"
#include "clseBayUserBiddingWidget.h"
#include "clseBayMyFavoritesWidget.h"
#include "clseBayMyFeedbackWidget.h"
#include "clseBayMyProfileWidget.h"
#include "clseBayMyBalanceWidget.h"
#include "hash_map.h"


/*  These definitions take care of the time zone specification

// 
// This Enum tells us witch time zone
// the user want to display
//
typedef enum
{
	PacificTime			= 0,
	CentralTime			= 1,
	EasternTime			= 2,
	MountainTime		= 3,
	AlaskaTime			= 4,
	AmericanSamoaTime	= 5,
	AtlanticTime		= 6,
	ArizonaTime			= 7,
	GreenwichMeanTime	= 8,
	GuamTime			= 9,
	HawaiiTime			= 10,
	IndianaTime			= 11,
	NewFoundLandTime	= 12,
	SaskatchewanTime	= 13
} myTimeZonePreference; 

//
// The definition of the time zone
// label and number
//
typedef struct
{
	char					*pMyTimeZoneLabel;
	myTimeZonePreference	myZone;
	int						gmtDelay;
	int						pstDelay;
} myTimeZone;

#define ONE_HOUR 3600
//
// List of defined time zone at eBay
//
myTimeZone eBayTimeZone[] =
{
	{"PST"			,	PacificTime			,	((-8)  * ONE_HOUR)	,	((0)   * ONE_HOUR)	},
	{"CST"			,	CentralTime			,	((-6)  * ONE_HOUR)	,	((2)   * ONE_HOUR)	},
	{"EST"			,	EasternTime			,	((-5)  * ONE_HOUR)	,	((3)   * ONE_HOUR)	},
	{"MNT"			,	MountainTime		,	((-7)  * ONE_HOUR)	,	((1)   * ONE_HOUR)	},
	{"ALT"			,	AlaskaTime			,	((-9)  * ONE_HOUR)	,	((-1)  * ONE_HOUR)	},
	{"AST"			,	AmericanSamoaTime	,	((-11) * ONE_HOUR)	,	((-3)  * ONE_HOUR)	},
	{"ATT"			,	AtlanticTime		,	((-4)  * ONE_HOUR)	,	((4)   * ONE_HOUR)	},
	{"AZT"			,	ArizonaTime			,	((-7)  * ONE_HOUR)	,	((1)   * ONE_HOUR)	},
	{"GMT"			,	GreenwichMeanTime	,	((0)   * ONE_HOUR)	,	((8)   * ONE_HOUR)	},
	{"GUT"			,	GuamTime			,	((10)  * ONE_HOUR)	,	((18)  * ONE_HOUR)	},
	{"HIT"			,	HawaiiTime			,	((-10) * ONE_HOUR)	,	((-2)  * ONE_HOUR)	},
	{"INT"			,	IndianaTime			,	((-5)  * ONE_HOUR)	,	((3)   * ONE_HOUR)	},
	{"Newfoundland"	,	NewFoundLandTime	,	((-3)  * ONE_HOUR)	,	((5)   * ONE_HOUR)	},
	{"Saskatchewan"	,	SaskatchewanTime	,	((-6)  * ONE_HOUR)	,	((2)   * ONE_HOUR)	}
};

*/

//
// MyEbayRedirect
//
// returns whether or not the cleartext password succeeded and the redirect was done
bool clseBayApp::MyEbayRedirect(CEBayISAPIExtension *pThis,
						   CHttpServerContext* pCtxt,
							 char *pUserId,
							 char *pPass,
							 char *pFirst,
							 /*char *pZone,*/
							 int sellerSort,
							 int bidderSort,
							 int daysSince,
							 int prefFavo,
							 int prefFeed,
							 int prefBala,
							 int prefSell,
							 int prefBidd)
{
	char currentURL[512];

	char  passToDisplay[65];
	int   iThePassLen = 0;
//	unsigned long lLength;

	int  accessLevel;

	char	cgipath[128];
	char	lowerCasePass[128];

	char	*pCleanUserId = NULL;
/*
	// josh's logging code for tracking down the monster bug
	char tbuf[128];
	sprintf(tbuf, "MyEbayRedirect: %20.20s %20.20s %20.20s", 
		pUserId, pPass, pFirst);
	LogEvent(tbuf);
*/


	SetUp();

	// user is logging in for the first time so let's verify his clear-text password.
	// Note: didn't use GetAndCheckUserAndPassword() because it writes to the stream, which
	//  we can't do since we haven't called StartContent
	mpUser = mpUsers->GetUser(pUserId);

	// manually check the cleartext password
	strcpy(lowerCasePass, pPass);
	clsUtilities::StringLower(lowerCasePass);
	if ((!mpUser) || (!mpUser->TestPass(lowerCasePass)))
	{
		// since we haven't called it yet, let's do it now
		pThis->StartContent(pCtxt);

		delete mpUser;

		// emit the "password error" text
		mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream,
													 false, NULL, true);

		*mpStream	<<	"<br><br>"
					<<	mpMarketPlace->GetFooter()
					<<	flush;

		CleanUp();

		pThis->EndContent(pCtxt);
		return false;
	}

	// ok, so the user just logged in and has a valid cleartext password.
	// For safety, let's redirect them to a new URL that shows the encrypted password.

	// will be used for the redirection below
	strcpy(cgipath, mpMarketPlace->GetCGIPath(PageMyEbay));

	// Get the encrypted password from the database (salt stripped)
	strcpy(passToDisplay, mpUser->GetPasswordNoSalt());

	// check the access level granted to the user.
	//  then we will hide this fact in the URL by padding the
	//  encrypted password with a '1' for level1 access, and '2' for level2 access
	accessLevel = mpUser->GetAccessLevel(pPass);

	// clean up the userid/email first (which also lowercases it)
	if (pUserId) pCleanUserId = clsUtilities::CleanUpUserId(pUserId);

	// safety
	if (!pCleanUserId)
	{
		CleanUp();
		return false;
	}

	// make sure that GetCGIPath doesn't return localhost, because IE4.0 won't redirect
	//  to localhost. why, i don't know. it WILL redirect to 127.0.0.1, however. Netscape
	//  doesn't have this problem. took me a 1/2 day to figure this out. damn.

	// set the redirect URL while padding the password with the access level
	sprintf(currentURL,
			"%sebayISAPI.dll?MyEbay&userid=%s&pass=%s%d&first=%s&sellerSort=%d&bidderSort=%d&dayssince=%d&p1=%d&p2=%d&p3=%d&p4=%d&p5=%d",
			cgipath,pCleanUserId, passToDisplay, accessLevel, "N",
			/*pZone,*/
			sellerSort, bidderSort,
			daysSince,prefFavo,prefFeed,prefBala,prefSell,prefBidd);

	if (pCleanUserId) delete [] pCleanUserId;

	CleanUp();

//	lLength = strlen(currentURL);
//	int success = pCtxt->ServerSupportFunction(HSE_REQ_SEND_URL_REDIRECT_RESP, currentURL, &lLength, NULL);

	// ok, try yet another way to do the redirect
	pThis->EbayRedirect(pCtxt, currentURL);

	/*
	*mpStream	<<	"HTTP/1.0 302 Object Moved\r\n"
//				<<	"Status: 302 Moved Temporarily\r"
				<<	"Location: " << currentURL << "\r\n"
				<<	"Content-type: text/html\r\n\r\n"
				<<	flush;
	*/

	/*
	// ok this code is here in case the redirect fails and the user gets a blank page.
	//  it does 2 things: 1) uses a meta tag redirect to force the redirect
	//                    2) in case 1 doesn't work, give the user a link
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	"My eBay redirect page"
			  <<	"</TITLE>"
					"<META HTTP-EQUIV=\"refresh\" content=\"0; URL=\""
			  <<	currentURL
			  <<	"\">"
					"</HEAD>"
			  <<	"<body>"
			  <<	"Loading...<p>Click "
			  <<	"<a href=\""
			  <<	currentURL
			  <<	"\">here</a> if your My eBay page doesn't load on its own in the next few seconds."
			  <<	flush;

	pThis->EndContent(pCtxt);
	*/

	return true;


}


//
// MyEbay
//
void clseBayApp::MyEbay(CEBayISAPIExtension *pThis,
						   CHttpServerContext* pCtxt,
							 char *pUserId,
							 char *pPass,
							 char *pFirst,
							 /*char *pZone,*/
							 int sellerSort,
							 int bidderSort,
							 int daysSince,
							 int prefFavo,
							 int prefFeed,
							 int prefBala,
							 int prefSell,
							 int prefBidd)
{
//	clsTimeScale timeScale("MyEbay", mpMarketPlace->GetLogging());

	clseBayMyFavoritesWidget *mfw;
	clseBayMyFeedbackWidget *mfbw;
	clseBayUserSellingWidget *usw;
	clseBayUserBiddingWidget *ubw;
	clseBayMyProfileWidget *mpw;
	clseBayMyBalanceWidget *mbw;
	char currentURL[512];

	char  passToDisplay[65];
	int   iThePassLen = 0;

	int  accessLevel;
	char unpaddedPass[65];

	char	cgipath[128];

	char	*pCleanUserId = NULL;


	// josh's logging code for tracking down the monster bug
//	char tbuf[128];
//	sprintf(tbuf, "MyEbay: %20.20s %20.20s %20.20s", pUserId, pPass, pFirst);
//	LogEvent(tbuf);


	SetUp();

	strcpy(cgipath, mpMarketPlace->GetCGIPath(PageMyEbay));

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Title
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	"My eBay Page for "
			  <<	pUserId
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	flush;

	/*
	// TEMPORARILY DISABLE EBAY
	*mpStream <<	"<h1>Sorry!</h1>"
					"My eBay, which is in testing, is currently not available!"
					"<br>"
			  <<	mpMarketPlace->GetFooter();
	return;
	*/

	// The user has already logged in, meaning that the password in the URL
	//  is already encrypted. Need to confirm that the correct encrypted password
	//  has been given.
	mpUser = mpUsers->GetUser(pUserId, false, false);

	// safety
	if (!mpUser)
	{
		*mpStream <<	"No such user."
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Get the encrypted password from the database (salt stripped)
	strcpy(passToDisplay, mpUser->GetPasswordNoSalt());

	// separate the encrypted pass from the access level.
	// (we know that the encrypted password is padded because pFirst=N)
	strcpy(unpaddedPass, pPass);
	unpaddedPass[strlen(unpaddedPass)-1]='\0';

	// compare them
	if(strcmp(passToDisplay,unpaddedPass))
	{
		*mpStream <<	"Incorrect password.<br><br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// ok, the encrypted password worked, now let's determine the access level
	accessLevel = atoi(pPass+strlen(pPass)-1);


	// Ok, so user is authenticated
	// Is 0 <= daysSince <= 30 ???
	//
	if(daysSince > 30 || daysSince < 0)
	{
		*mpStream <<	"<br>The days worth of completed auctions must between 0-30.<br><br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

/*	Taking care of the time zone specification.
	//
	// Is the time zone eligible ??
	//
	int numTimeZone = 0;
	numTimeZone = sizeof(eBayTimeZone)/sizeof(myTimeZone);
	for(int j = 0 ; j < numTimeZone ; j++ )
	{
		if(strncmp(pZone,eBayTimeZone[j].pMyTimeZoneLabel,strlen(eBayTimeZone[j].pMyTimeZoneLabel)) == 0)
		{
			break;
		}
	}

	if( j >= numTimeZone )
	{
		*mpStream <<	"<br>The time zone is not properly defined, correct it and try again.<br><br>"
				  <<	"<b>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}
*/


	// Ok, at this point, we are authenticated, so let's continue

	// Create current URL to pass to the widgets that need it. Also pad
	// the password
	sprintf(currentURL,
			"%sebayISAPI.dll?MyEbay&userid=%s&pass=%s%d&first=%s&sellerSort=%d&bidderSort=%d&dayssince=%d&p1=%d&p2=%d&p3=%d&p4=%d&p5=%d",
			cgipath,pUserId, passToDisplay, accessLevel, "N",
			/*pZone,*/
			sellerSort, bidderSort,
			daysSince,prefFavo,prefFeed,prefBala,prefSell,prefBidd);
/*
	// link to uifeedback board
	*mpStream	<<	"<p><a href=\""
				<<	mpMarketPlace->GetCGIPath(PageViewBoard)
				<<	"eBayISAPI.dll?ViewBoard&amp;name=uifeedback\">"
				<<	"Tell us what you think of My eBay"
				<<	"</a></p>";*/

	///////////////////////////////////////////////////
	// Here let's deal about the users' preferences  //
	///////////////////////////////////////////////////
	// The profile is always required 
	// Output the user's name
	//

// sprintf(tbuf, "MyEbay Begin MyProfileWidget: %20.20s %20.20s %20.20s", pUserId, pPass, pFirst);
// LogEvent(tbuf);

	mpw = new clseBayMyProfileWidget(mpMarketPlace);
	mpw->SetUser(mpUser);
	mpw->SetTitleColor("#CCCCCC");
	mpw->SetBorder(1);
	mpw->EmitHTML(mpStream);
	delete mpw;

// sprintf(tbuf, "MyEbay End MyProfileWidget: %20.20s %20.20s %20.20s", pUserId, pPass, pFirst);
// LogEvent(tbuf);
	
	if(prefFavo == 1)
	{

// sprintf(tbuf, "MyEbay Begin MyFavoritesWidget: %20.20s %20.20s %20.20s", pUserId, pPass, pFirst);
// LogEvent(tbuf);

		// Output my favorites
		mfw = new clseBayMyFavoritesWidget(mpMarketPlace);
		mfw->SetUser(mpUser);
		mfw->SetPassword(passToDisplay);
		mfw->SetTitleColor("#99CCCC");
		mfw->EmitHTML(mpStream);
		delete mfw;

// sprintf(tbuf, "MyEbay End MyFavoritesWidget: %20.20s %20.20s %20.20s", pUserId, pPass, pFirst);
// LogEvent(tbuf);

	}
	
	if(prefFeed == 1)
	{

// sprintf(tbuf, "MyEbay Begin MyFeedbackWidget: %20.20s %20.20s %20.20s", pUserId, pPass, pFirst);
// LogEvent(tbuf);

		// Output my feedback
		mfbw = new clseBayMyFeedbackWidget(mpMarketPlace);
		mfbw->SetUser(mpUser);
		mfbw->SetTitleColor("#FFFFCC");
		mfbw->SetCellPadding(0);
		mfbw->EmitHTML(mpStream);
		delete mfbw;

// sprintf(tbuf, "MyEbay End MyFeedbackWidget: %20.20s %20.20s %20.20s", pUserId, pPass, pFirst);
// LogEvent(tbuf);

	}

	if(prefBala == 1)
	{

// sprintf(tbuf, "MyEbay Begin MyBalanceWidget: %20.20s %20.20s %20.20s", pUserId, pPass, pFirst);
// LogEvent(tbuf);

		// Output my balance
		mbw = new clseBayMyBalanceWidget(mpMarketPlace);
		mbw->SetUser(mpUser);
		mbw->SetTitleColor("#CCCCCC");
		mbw->SetPassword(passToDisplay);
		mbw->EmitHTML(mpStream);
		delete mbw;

// sprintf(tbuf, "MyEbay End MyBalanceWidget: %20.20s %20.20s %20.20s", pUserId, pPass, pFirst);
// LogEvent(tbuf);

	}

	if(prefSell == 1)
	{

// sprintf(tbuf, "MyEbay Begin UserSellingWidget: %20.20s %20.20s %20.20s", pUserId, pPass, pFirst);
// LogEvent(tbuf);

		// Output seller list
		usw = new clseBayUserSellingWidget(mpMarketPlace);
		usw->SetUser(mpUser);
		usw->SetUserPassword(passToDisplay);
		usw->SetDaysSince(daysSince);
		usw->SetCurrentURL(currentURL);
		usw->SetSortCode((ItemListSortEnum)sellerSort);
		usw->SetCellPadding(1);
		usw->SetColor("#FFCC99");
		if (accessLevel < 2) usw->SetRestrictedAccess(true);
		usw->SetIncremental(true);
		usw->EmitHTML(mpStream);
		delete usw;

// sprintf(tbuf, "MyEbay End UserSellingWidget: %20.20s %20.20s %20.20s", pUserId, pPass, pFirst);
// LogEvent(tbuf);

	}

	if(prefBidd == 1)
	{

// sprintf(tbuf, "MyEbay Begin UserBiddingWidget: %20.20s %20.20s %20.20s", pUserId, pPass, pFirst);
// LogEvent(tbuf);

		*mpStream <<	"<P>&nbsp;</P>";

		// Output bidder list
		ubw = new clseBayUserBiddingWidget(mpMarketPlace);
		ubw->SetUser(mpUser);
		ubw->SetUserPassword(passToDisplay);
		ubw->SetDaysSince(daysSince);
		ubw->SetCurrentURL(currentURL);
		ubw->SetSortCode((ItemListSortEnum)bidderSort);
		ubw->SetCellPadding(1);
		ubw->SetColor("#CCCCFF");
		if (accessLevel < 2) ubw->SetRestrictedAccess(true);
		ubw->SetIncremental(true);
		ubw->EmitHTML(mpStream);
		delete ubw;

// sprintf(tbuf, "MyEbay End UserBiddingWidget: %20.20s %20.20s %20.20s", pUserId, pPass, pFirst);
// LogEvent(tbuf);

	}

	*mpStream	<<	"<br><br>"
				<<	mpMarketPlace->GetFooter();
	CleanUp();

	return;
}

