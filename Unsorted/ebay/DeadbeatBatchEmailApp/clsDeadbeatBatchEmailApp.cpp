/*	$Id: clsDeadbeatBatchEmailApp.cpp,v 1.3.22.1.94.3 1999/08/09 17:23:52 nsacco Exp $	*/
/*	$Id	*/
//
//	File:	clsDeadbeatBatchEmailApp.cpp
//
//	Class:	clsDeadbeatBatchEmailApp
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 03/04/99 mila		- created
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsDeadbeatBatchEmailApp.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsItems.h"
#include "clsDeadbeatItem.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsUtilities.h"
#include "clsMail.h"

#include "vector.h"
#include "iterator.h"

#include <stdio.h>
#include <stdlib.h>
#include <errno.h>
#include <time.h>

#ifdef _MSC_VER
#include <strstrea.h>
#else
#include <strstream.h>
#endif


// SendEmail_Debug: We don't want to send email messages via 
// BCC: to notify@ebay.com if we are just testing. 
//
#define SENDEMAIL_DEBUG 0 

#if SENDEMAIL_DEBUG==1
#pragma message("Warning: SEND DEADBEAT EMAIL USING DEBUG MODE - THIS NEEDS TO BE CHANGED FOR RELEASE")
bool bSendEmail_Debug = true;
#else
bool bSendEmail_Debug = false;
#endif

static const char *EmailWarningSubject[] =
{
	"Warning #1: Bidding infraction - ",
	"Warning #2: Bidding infraction - ",
	"NOTICE: eBay 30 Day Suspension - ",
	"NOTICE: eBay Registration Suspension - Deadbeat bidder - ",
	NULL
};

static const char *EmailWarningPart1_1 =
"You were a winning bidder for item number ";

static const char *EmailWarningPart1_2 =
"  The seller for this item has requested a credit for the auction."
"  We require sellers to provide a reason for requesting a credit."
"  The reason provided indicates that you did not complete this transaction.";

static const char *EmailWarningPart2_1[] =
{

"This is your first warning for this type of offense."
"  Bidders receiving four warnings will be suspended indefinitely from eBay.\n\n",
	
	
"This is your second warning for this type of offense."
"  Customers receiving a third warning are temporarily suspended from eBay for 30 days."
"  Bidders receiving four warnings will be suspended indefinitely from eBay.\n\n",
	
"This is your third warning for this type of offense.  You have been temporarily suspended from eBay."
"  You will be reinstated automatically after 30 days."
"  Bidders receiving four warnings will be suspended indefinitely from eBay.\n\n",

"This is your fourth warning for this type of offense.  You have been indefinitely suspended from eBay.\n\n",

NULL
};

static const char *EmailWarningPart2_2[] =
{

"This message has not been publicly announced on eBay and has been sent"
" only to the email address you provided in your registration.\n\n",
	
"This message has not been publicly announced on eBay and has been sent"
" only to the email address you provided in your registration.\n\n",
	
"",

"",

NULL
};

static const char *EmailWarningPart3 =
"The eBay User Agreement explains that if you bid on an item and your bid is accepted by the seller,"
" you are obligated to complete the transaction."
"  As of March 1, 1999 eBay has implemented a policy to warn and (in the case of repeated offenses)"
" suspend bidders who repeatedly fail to honor their bids."
"  For details please refer to our Deadbeat Bidder Policy page at:\n";

static const char *EmailWarningPart4 =
"We realize that there may be extenuating circumstances in which a bidder is"
" unable to honor their winning bid, and that sometimes sellers will make a mistake"
" when they request credits.  If you believe this report is an error, and you can"
" provide proof of payment or other evidence to show that it is inaccurate,"
" please review the Deadbeat Bidder appeals instructions within our online Help desk at:\n";

static const char *AdminUserId = "support";

static const char *ReturnAddress = "aw-confirm@ebay.com";

static const char *TempSuspendNoteText = "Deadbeat 30 day Suspension";
static const char *SuspendNoteText = "Deadbeat Full Suspension";


// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *DeadbeatPolicyPage = "http://pages.ebay.com/safeharbor-dbeat.html";
static const char *DeadbeatAppealPage = "http://pages.ebay.com/help/appeal-deadbeat.html";
*/

const char *AutomatedSupportEmailBccList[] = 
{
	"notify@ebay.com",
	NULL
};

static const int kSuspendLevel = 4;
static const int kTempSuspendLevel = 3;

static const int kAuthLevel = 3;	// eBayISAPIAuthAdmin


clsDeadbeatBatchEmailApp::clsDeadbeatBatchEmailApp()
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	return;
}


clsDeadbeatBatchEmailApp::~clsDeadbeatBatchEmailApp()
{
	return;
};

/* emits end of auction notice for a given item */
bool clsDeadbeatBatchEmailApp::EmitDeadbeatEmail(clsDeadbeatItem *pItem,
												 FILE *pDeadbeatEmailLog)
{
	// dates/times
	time_t		itemEndTime;
	struct tm	*pItemEndTimeTM;
	char		cItemEndTime[32];
// petra - unused	char		cItemEndDateRFC802[128];

	// email
	clsMail		*pMail;
	ostrstream	*pMailStream;
	int			mailRc = 0;

	// eNotes
	int			type;
	char		*pNoteText;
	char		cSubjectText[512] = "";
	char		*pEmailText;
	int			strLen;

	//deadbeat info
	clsUser		*pBidder;
	clsDeadbeat	*pDeadbeat;
//	int			deadbeatScore;
	int			warningCount;
	char		*pUserId;

	// item info
	int			itemId;
	int			sellerId;
	int			bidderId;
	char		*pTitle;


	if (pItem == NULL || pDeadbeatEmailLog == NULL)
		return false;

	// let's cache these...
	itemId = pItem->GetId();
	sellerId = pItem->GetSeller();
	bidderId = pItem->GetBidder();
	pTitle = pItem->GetTitle();

	// get user object for deadbeat bidder
	pBidder = mpUsers->GetUser(bidderId);
	if (pBidder == NULL)
		return false;

	// check validity of bidder email
	if (pBidder->GetEmail() == '\0')
	{
		fprintf(pDeadbeatEmailLog, "** Error ** Could NOT get email for bidder %d of item %d\n",
				pItem->GetSeller(), itemId);
		delete pBidder;
		return false;
	}

	//Get deadbeat 
	pDeadbeat = pBidder->GetDeadbeat();
	if (pDeadbeat == NULL)
		return false;

	warningCount = pDeadbeat->GetWarningCount();
//	deadbeatScore = abs(pDeadbeat->GetDeadbeatScore());

	if (warningCount >= kSuspendLevel)
		return false;

	pUserId = pBidder->GetUserId();
	if (pUserId == NULL)
		return false;

	// Open the mail stream
	pMail = new clsMail();
	if (pMail == NULL)
		return false;
	pMailStream	= pMail->OpenStream();

	// prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	// TODO - use time/date widgets
	//
	// Reformat dates
	//
	itemEndTime		= pItem->GetEndTime();
	pItemEndTimeTM	= localtime(&itemEndTime);
	clsUtilities::GetDateTime(itemEndTime, &cItemEndTime[0]);
// petra - unused	strftime(cItemEndDateRFC802, sizeof(cItemEndDateRFC802),
// petra			 "%a, %d %b %Y %H:%M:%S %z",
// petra			 pItemEndTimeTM);

	// Start email message
	*pMailStream << "Dear "
				 << pUserId
				 << ":\n\n";
	
	// Email text
	*pMailStream << EmailWarningPart1_1
				 << itemId
				 << ", "
				 << pTitle
				 << "."
				 << EmailWarningPart1_2
				 << "\n\n"
				 << EmailWarningPart2_1[warningCount]
				 << EmailWarningPart2_2[warningCount]
				 << EmailWarningPart3
				 << mpMarketPlace->GetHTMLPath()
	// kakiyama 07/16/99
	//			 <<	DeadbeatPolicyPage
				 << mpMarketPlace->GetHTMLPath()
				 << "safeharbor-dbeat.html"
				 << "\n\n"
				 << EmailWarningPart4
				 << mpMarketPlace->GetHTMLPath()	
				 << "help/"
	// kakiyama 07/16/99
	//			 <<	DeadbeatAppealPage
				 << mpMarketPlace->GetHTMLPath()
				 << "help/appeal-deadbeat.html"
				 << "\n\n"
				 << "Regards,\n\n"
				 << "eBay SafeHarbor Team"
				 << "\n\n";

	// We only send email if not suspending the user
	if (warningCount < kTempSuspendLevel - 1)
	{
		//Send Email debug style or release style
		if (bSendEmail_Debug)
		{
			//Debug - do not BCC notify@ebay.com
			mailRc = pMail->Send(pBidder->GetEmail(), (char *)ReturnAddress, (char *)EmailWarningSubject[warningCount]);
		}
		else
		{
			//Release - send BCC to notify@ebay.com
			mailRc = pMail->Send(pBidder->GetEmail(), (char *)ReturnAddress, (char *)EmailWarningSubject[warningCount], NULL, (char **)AutomatedSupportEmailBccList);
		}
	}
	else
	{
		// Suspend user and send email through Admin call
		// Set suspension values
		if (warningCount == kTempSuspendLevel - 1)
		{
			type = eNoteTypeSuspension30Days;
			pNoteText = (char *)TempSuspendNoteText;
		}
		else
		{
			type = eNoteTypeSuspension;
			pNoteText = (char *)SuspendNoteText;
		}

		//Build subject text line and append users email address
		strcpy(cSubjectText, EmailWarningSubject[warningCount]);
		strcat(cSubjectText, pBidder->GetEmail());

		//Get text from stream to send to eNotes
		strLen = pMailStream->pcount();
		pEmailText = new char[strLen + 1];
		memcpy(pEmailText, pMailStream->str(), strLen);
		pEmailText[strLen] = '\0';

		// Suspend user and send email through Admin call
		SuspendUserViaENotes((char *)AdminUserId,
							 (char *)mpMarketPlace->GetAdminSpecialPassword(),
							 pUserId, type, pNoteText,
							 cSubjectText,
							 pEmailText);
	}

	// Clean up
	delete pMail;
	delete pBidder;
	delete pEmailText;

	return true;
}

void clsDeadbeatBatchEmailApp::SuspendUserViaENotes(char *pUserId,
											        char *pPass,
											        char *pSuspendee,
											        int type,
											        char *pText,
											        char *pEmailSubject,
											        char *pEmailText)
{
	clsUser		*pSuspendeeUser	= NULL;
	clsUser		*pUser = NULL;

	// For mailing the suspendee
	clsMail		*pMail;
	ostrstream	*pMailStream;


	char		*pUserInfoText;
	char		*pTextWithUserInfo;
	time_t		nowTime;
	clsNotes	*pNotes;
	clsNote		*pNote;

	// Check the input
	if (pUserId == NULL || pPass == NULL || pSuspendee == NULL || pText == NULL || type < 0)
		return;

	// All is well! Let's file an enote!
	pSuspendeeUser = mpUsers->GetAndCheckUser(pSuspendee, NULL);
	if (pSuspendeeUser == NULL)
		return;

	pUser = mpUsers->GetAndCheckUser(pUserId, NULL);
	if (pUser == NULL)
		return;

	nowTime	= time(0);

	pUserInfoText		= clsNote::GetUserInfo(0, pSuspendeeUser);
	pTextWithUserInfo	= new char[strlen(pUserInfoText) + 8 + strlen(pText) + 1];
	strcpy(pTextWithUserInfo, pUserInfoText);
	strcat(pTextWithUserInfo, "<br><br>");
	strcat(pTextWithUserInfo, pText);

	pNotes	= mpMarketPlace->GetNotes();

	pNote	= new clsNote(pNotes->GetSupportUser()->GetId(),
						  pUser->GetId(),
						  0,
						  pSuspendeeUser->GetId(),
						  eClsNoteFromTypeAutoAdminPost,
						  type,
						  eClsNoteVisibleSupportOnly,
						  nowTime,
						  (time_t)0,
						  pEmailSubject,
						  pTextWithUserInfo);

	pNotes->AddNote(pNote);

	delete pTextWithUserInfo;

	// Indicate there's a note about this user!
	pSuspendeeUser->SetHasANote(true);

	// Do them!
	pSuspendeeUser->SetSuspended();

	// update them!
	pSuspendeeUser->UpdateUser();

	// Now, mail them!
	pMail	= new clsMail();

	pMailStream	= pMail->OpenStream();

	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	*pMailStream <<	pEmailText;

	pMail->Send(pSuspendeeUser->GetEmail(),
				(char *)pUser->GetEmail(),
				pEmailSubject,
				NULL,
				(char **)AutomatedSupportEmailBccList);

	delete	pMail;
	delete	pSuspendeeUser;
	delete  pUser;

	return;
}

void clsDeadbeatBatchEmailApp::Run()
{
	// This a vector of "Stub" items which haven't 
	// gotten their end-of-auction notices yet
	DeadbeatItemVector		vItems;

	// And an iterator
	DeadbeatItemVector::iterator	i;

	clsUser	*				pBidder;
	clsDeadbeat *			pDeadbeat;

	time_t					noticeTime;
	struct tm *				pTime;

	// used for timing stats; 
	// currentTime and endTime figures out time for mail
	// mailTime add them up for the whole session.
    time_t                  nowTime;
	time_t					endTime;
	time_t					mailTime = 0;
	struct tm *				pLocalTime;

	// File stuff
	FILE *					pDeadbeatEmailLog;
	char					fname[25];

	bool					mailSent = false;


	noticeTime = time(0);
	pTime = localtime(&noticeTime);

	sprintf(fname, "deadbeatemail%04d%02d%02d.txt", 
			pTime->tm_year + 1900,
			pTime->tm_mon+1, 
			pTime->tm_mday);

	// File shenanigans
	pDeadbeatEmailLog = fopen(fname, "a");

	if (pDeadbeatEmailLog == NULL)
	{
		fprintf(stderr,"%s:%d Unable to open deadbeat email log. \n",
			  __FILE__, __LINE__);
	}

	// The things we need
	if (!mpDatabase)
		mpDatabase		= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces	= gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpUsers)
		mpUsers			= mpMarketPlace->GetUsers();

	// First, let's get the items
	mpDatabase->GetDeadbeatItemsNotWarned(&vItems);

	nowTime = time(0);
	pLocalTime = localtime(&nowTime);

	fprintf(pDeadbeatEmailLog,
		"%2d/%2d/%2d %2d:%2d:%2d\t Done getting all deadbeat items not warned.\n",
		pLocalTime->tm_mon+1, pLocalTime->tm_mday, pLocalTime->tm_year,
		pLocalTime->tm_hour, pLocalTime->tm_min, pLocalTime->tm_sec);

	// Show how many there are
	fprintf(pDeadbeatEmailLog, "%d Items to warn\n", vItems.size());

	//inna-test int inna=1;
	// Now, we loop through them
	for (i = vItems.begin();
		 i != vItems.end();
		 i++)
	{
		if (*i == NULL)
			continue;

		nowTime = time(0);
		pLocalTime = localtime(&nowTime);

		// Log start time
		fprintf(pDeadbeatEmailLog,
		"%2d/%2d/%2d %2d:%2d:%2d start: ",
		pLocalTime->tm_mon+1, pLocalTime->tm_mday, pLocalTime->tm_year,
		pLocalTime->tm_hour, pLocalTime->tm_min, pLocalTime->tm_sec);

		mailSent = EmitDeadbeatEmail((*i), pDeadbeatEmailLog);

		endTime = time(0);
		pLocalTime = localtime(&endTime);
		
		// Log end time
		fprintf(pDeadbeatEmailLog,
		"%2d/%2d/%2d %2d:%2d:%2d end: ",
		pLocalTime->tm_mon+1, pLocalTime->tm_mday, pLocalTime->tm_year,
		pLocalTime->tm_hour, pLocalTime->tm_min, pLocalTime->tm_sec);

		mailTime = mailTime + (endTime - nowTime);

		if (mailSent)
		{
			(*i)->SetNotified(true, true);

			if ((*i)->GetBidder() > 0)
			{
				pBidder = mpUsers->GetUser((*i)->GetBidder());
				if (pBidder != NULL)
				{
					pDeadbeat = pBidder->GetDeadbeat();
					if (pDeadbeat != NULL)
						pDeadbeat->InvalidateWarningCount();
				}
				delete pBidder;
			}
		}

		delete (*i);
	}

	fprintf(pDeadbeatEmailLog, "%d total mail time\n ", mailTime);
	fclose (pDeadbeatEmailLog);

	vItems.erase(vItems.begin(), vItems.end());
}

static clsDeadbeatBatchEmailApp *pTestApp = NULL;

int main()
{
#ifdef _MSC_VER
	g_tlsindex = 0;
#endif

	if (!pTestApp)
	{
		pTestApp	= new clsDeadbeatBatchEmailApp();
	}

	pTestApp->InitShell();
	pTestApp->Run();

	return 0;
}
