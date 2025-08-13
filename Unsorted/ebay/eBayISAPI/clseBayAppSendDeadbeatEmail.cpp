/*	$Id: clseBayAppSendDeadbeatEmail.cpp,v 1.4.22.8.66.1 1999/08/05 18:59:04 nsacco Exp $	*/
//
//	File:		clseBayAppSendDeadbeatEmail.cpp
//
//	Class:		clseBayAppSendDeadbeatEmail
//
//	Author:		Lou Leonardo (lou@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 02/22/99	lou		- Created
//

#include "ebihdr.h"

#include "clsDeadbeat.h"
#include "string.h"
#include "clsNote.h"			

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
	"NOTICE: eBay Registration Suspension - Non-paying bidder - "
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

"This is your fourth warning for this type of offense.  You have been indefinitely suspended from eBay.\n\n"
};

static const char *EmailWarningPart2_2[] =
{

"This message has not been publicly announced on eBay and has been sent"
" only to the email address you provided in your registration.\n\n",

"This message has not been publicly announced on eBay and has been sent"
" only to the email address you provided in your registration.\n\n",

"",

"",
};

static const char *EmailWarningPart3 =
"The eBay User Agreement explains that if you bid on an item and your bid is accepted by the seller,"
" you are obligated to complete the transaction."
"  As of March 1, 1999 eBay has implemented a policy to warn and (in the case of repeated offenses)"
" suspend bidders who repeatedly fail to honor their bids."
"  For details please refer to an explanation of this policy at:\n";

static const char *EmailWarningPart4 =
"We realize that there may be extenuating circumstances in which a bidder is"
" unable to honor their winning bid, and that sometimes sellers will make a mistake"
" when they request credits.  If you believe this report is an error, and you can"
" provide proof of payment or other evidence to show that it is inaccurate,"
" please review the appeal instructions within our online Help desk at:\n";

static const char *AdminUserId = "support";

static const char *ReturnAddress = "aw-confirm@ebay.com";

static const char *TempSuspendNoteText = "Deadbeat 30 day Suspension";
static const char *SuspendNoteText = "Deadbeat Full Suspension";

static const int nSuspendLevel = 3;
static const int nTempSuspendLevel = 2;


// kakiyama 07/09/99 - commented out
// resourced 
//static const char *DeadbeatPolicyPage = "http://pages.ebay.com/services/safeharbor/safeharbor-dbeat.html";
//static const char *DeadbeatAppealPage = "http://pages.ebay.com/help/community/appeal-deadbeat.html";

//
// SendDeadbeatEmail
//
int clseBayApp::SendDeadbeatEmail(int nDeadbeatId,
								  int nItemId, 
								  char *pTitle,
								  char *pPassword,
								  eBayISAPIAuthEnum authLevel)
{
	// email
	clsMail		*pMail;
	ostrstream	*pMailStream;
	int			mailRc = 0;

	//deadbeat info
	clsUser		*pDeadbeatUser;
	clsDeadbeat	*pDeadbeat;
	int			nDeadbeatScore;
	char		*pUserId;

	clsItem		*pItem;

	int			nType;
	char		*pNoteText;
	char		SubjectText[512]="";
	char		*pEmailText;
	int			nStrLen;

	clsFeedback			*pFeedback;
	int					feedbackScore = 0;


	SetUp();

	// Get user 
	pDeadbeatUser = mpUsers->GetUser(nDeadbeatId);

	//Make sure we have a valid user
	if (!pDeadbeatUser)
	{
		CleanUp();
		return 0;
	}

	//Get deadbeat 
	pDeadbeat = pDeadbeatUser->GetDeadbeat();

	//Make sure valid Deadbeat
	if (!pDeadbeat)
	{
		CleanUp();
		return 0;
	}

	// Let's try and get the item
	pItem = mpItems->GetItem(nItemId);
	if (pItem == NULL)
	{
		pItem = mpItems->GetItemArc(nItemId);
		if (pItem == NULL)
		{
			CleanUp();
			return 0;
		}
	}

	// Make sure we have Item Text
	if (!pTitle)  
	{
		CleanUp();
		return 0;
	}

	// Get deadbeats feedback score
	pFeedback = pDeadbeatUser->GetFeedback();
	if (pFeedback != NULL)
		feedbackScore = pFeedback->GetScore();

	// Get current deadbeat level and make it positive
	nDeadbeatScore = abs(pDeadbeat->GetDeadbeatScore());

	//Since the DeadbeatItem has not been added yet, when we recalc the deadbeat 
	// score it is 1 less than it's actual number

	// If warning level is 0 or more than 4 then we do not send e-mail
	if ((nDeadbeatScore < 0) || (nDeadbeatScore > nSuspendLevel))
	{
		if (pDeadbeatUser->IsSuspended())
		{
			CleanUp();
			return 0;
		}
		else
		{
			nDeadbeatScore = nSuspendLevel;
		}
	}

	pUserId = pDeadbeatUser->GetUserId();

	// Make sure we have a UserId
	if (!pUserId) 
	{
		CleanUp();
		return 0;
	}


	// For email stuff
	pMail	= new clsMail();
	pMailStream	= pMail->OpenStream();

	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	// Start email message
	*pMailStream << "Dear "
				 << pUserId
				 << ":\n\n";
	
	// Email text
	*pMailStream << EmailWarningPart1_1
				 << nItemId
				 << ", "
				 << pTitle
				 << "."
				 << EmailWarningPart1_2
				 << "\n\n"
				 << EmailWarningPart2_1[nDeadbeatScore]
				 << EmailWarningPart2_2[nDeadbeatScore]
				 << EmailWarningPart3
//				 << mpMarketPlace->GetHTMLPath()
//				 <<	DeadbeatPolicyPage
//	kakiyama 07/08/99
				 << mpMarketPlace->GetHTMLPath()
				 << "services/safeharbor/safeharbor-dbeat.html"
				 << "\n\n"
				 << EmailWarningPart4
//				 << mpMarketPlace->GetHTMLPath()
//				 << "help/"
//				 <<	DeadbeatAppealPage
// kakiyama 07/08/99
				 << mpMarketPlace->GetHTMLPath()
				 << "help/community/appeal-deadbeat.html"
				 << "\n\n"
				 << "Regards,\n\n"
				 << "eBay SafeHarbor Team"
				 << "\n\n";

	// We only send email if not suspending the user
	if (nDeadbeatScore < nTempSuspendLevel)
	{
		//Send Email debug style or release style
		if (bSendEmail_Debug)
		{
			//Debug - do not BCC notify@ebay.com
			mailRc = pMail->Send(pDeadbeatUser->GetEmail(), (char *)ReturnAddress, (char *)EmailWarningSubject[nDeadbeatScore]);
		}
		else
		{
			//Release - send BCC to notify@ebay.com
			mailRc = pMail->Send(pDeadbeatUser->GetEmail(), (char *)ReturnAddress, (char *)EmailWarningSubject[nDeadbeatScore], NULL, (char **)AutomatedSupportEmailBccList);
		}
	}
	else
	{
		if ( feedbackScore >= 100 )
		{
			// We don't want to automatically suspend users with a feedback > 100
			//Send Email debug style or release style
			if (bSendEmail_Debug)
			{
				//Debug - do not send to notify@ebay.com
				mailRc = pMail->Send("testuser1@altavista.net", (char *)ReturnAddress, "Deadbeat plus 100 Feedback");
			}
			else
			{
				//Release - send notify@ebay.com
				mailRc = pMail->Send("queue@phoenix.ebay.com", (char *)ReturnAddress, "Deadbeat plus 100 Feedback");
			}
		}
		else
		{
			// Set suspension values
			if (nDeadbeatScore == nTempSuspendLevel)
			{
				nType = eNoteTypeSuspension30Days;
				pNoteText = (char *)TempSuspendNoteText;
			}
			else
			{
				nType = eNoteTypeSuspension;
				pNoteText = (char *)SuspendNoteText;
			}

			//Build subject text line and append users email address
			strcpy(SubjectText, EmailWarningSubject[nDeadbeatScore]);
			strcat(SubjectText, pDeadbeatUser->GetEmail());

			//Get text from stream to send to eNotes
			nStrLen = pMailStream->pcount();
			pEmailText = new char[nStrLen + 1];
			memcpy(pEmailText, pMailStream->str(), nStrLen);
			pEmailText[nStrLen] = '\0';

			// Suspend user and send email through Admin call
			mailRc = (int)AdminSuspendUserNoShow((char *)AdminUserId, pPassword,
									pUserId, nType, pNoteText,
									SubjectText,
									pEmailText, authLevel);
		
			// Clean up 
			delete pEmailText;
		}
	}

	// We don't need mail now
	delete	pMail;

	delete pDeadbeatUser;
	delete pItem;

	CleanUp();

	return mailRc;
}

