/*	$Id: clseBayAppReturnUserIdHistory.cpp,v 1.7.166.2.86.1 1999/08/01 03:01:28 barry Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Display pages that returns requested email address of an user.
//
//	Modifications:
//				- 12/19/97 Wen	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "clsAliasHistoryWidget.h"
#include "clsUserEmailWidget.h"
#include "clsUserValidation.h"
#include "clsNameValue.h"


const char ErrorMsgTooManyRequest[] = "You have requested too many e-mail addresses today. ";


void clseBayApp::ReturnUserIdHistory(CEBayISAPIExtension *pServer, 
							 char *pRequestedUserId,
							 char *pRequestorUserId,
							 char *pRequestorPass)
{
	clsUser*	pRequestedUser;
	clsAliasHistoryWidget*  pUserIdHistoryWidget;
	clsUserEmailWidget*		pUserEmailWidget;
	clsUserValidation*		pUserValidation;

	SetUp();

	// check cookie
	pUserValidation = mpUsers->GetUserValidation();
	if (pUserValidation->IsSoftValidated() == false && 
		strcmp(pRequestorUserId, "default") == 0)
	{
		char Action[255];
		clsNameValuePair theNameValuePairs[2];

		// Create the actions tring
		sprintf(Action, "%seBayISAPI.dll", mpMarketPlace->GetCGIPath(PageReturnUserIdHistory));

		// create the name value pairs
		theNameValuePairs[0].SetName("MfcISAPICommand");
		theNameValuePairs[0].SetValue("ReturnUserIdHistory");
		theNameValuePairs[1].SetName("requested");
		theNameValuePairs[1].SetValue(pRequestedUserId);

		// show login page
		LoginDialog(Action, 2, theNameValuePairs);
		
		CleanUp();

		return;
	}

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" User ID History"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// validate the requestor
	if (pUserValidation->IsSoftValidated())
	{
		mpUser = mpUsers->GetAndCheckUser((char*)pUserValidation->GetValidatedUserId(), mpStream);
	}
	else
	{
		mpUser	= mpUsers->GetAndCheckUserAndPassword(pRequestorUserId, pRequestorPass, mpStream);
	}

	// If we didn't get the user, we're done
	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		CleanUp();
		return;
	}

	// Check whether over the limit
	if (mpUser->GetReqEmailCount() >= EBAY_EMAILS_REQUEST_PER_DAY)
	{
		*mpStream <<	ErrorMsgTooManyRequest
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		CleanUp();
		return;
	}

	// get the requested user info
	pRequestedUser = mpUsers->GetAndCheckUser(pRequestedUserId, NULL);

	if (!pRequestedUser || 
		(pRequestedUser->GetUserState() != UserConfirmed && 
		 pRequestedUser->GetUserState() != UserSuspended) )
	{
		*mpStream	<<	"<h2>Requested user is not a register user</h2>"
					<<	pRequestedUserId
					<<	" is "
					<<	"<a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"help/myinfo/user-not-registered.html\">"
					<<	"not a registered user</a>. "
					<<	"Please check the User ID."
					<<	"<p>"
					<<	mpMarketPlace->GetFooter()
					<<	flush;
		CleanUp();
		return;
	}


	// header
	*mpStream	<<	"<h2>User ID History for "
				<<	pRequestedUserId
				<<	"</h2>\n";

	// legal, rule

	// display user id history
	pUserIdHistoryWidget = new clsAliasHistoryWidget(mpMarketPlace, this, UserIdAlias);
	pUserIdHistoryWidget->SetUser(pRequestedUser);
	pUserIdHistoryWidget->EmitHTML(mpStream);
	delete pUserIdHistoryWidget;

	// display user email
	*mpStream	<<	"<p>"
				<<	pRequestedUserId
				<<	" can be contacted via e-mail: ";
	pUserEmailWidget = new clsUserEmailWidget;
	pUserEmailWidget->SetUser(pRequestedUser);
	pUserEmailWidget->EmitHTML(mpStream);
	delete pUserEmailWidget;

	*mpStream	<<	"</p>";

	if (pRequestedUser->GetUserState() == UserSuspended)
	{
		*mpStream	<<	"This customer is "
					<<	"<a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"help/myinfo/user-not-registered.html\">"
						"not a registered user"
						"</a>.";
	}

	// the footer
	*mpStream	<<	mpMarketPlace->GetFooter()
				<<	flush;

	mpUser->AddReqEmailCount(1);

	delete pRequestedUser;

	CleanUp();
	return;
}
