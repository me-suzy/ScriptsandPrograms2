/*	$Id: clseBayAppReturnUserEmail.cpp,v 1.6.166.2.86.2 1999/08/05 20:42:19 nsacco Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Display pages that returns reuested email address of an user.
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


const char ErrorMsgTooManyRequest[] = "<h2>Too Many E-mail Addresses Requested</h2>"
"You have requested too many e-mail addresses today. ";


void clseBayApp::ReturnUserEmail(CEBayISAPIExtension *pServer, 
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
		sprintf(Action, "%seBayISAPI.dll", mpMarketPlace->GetCGIPath(PageReturnUserEmail));

		// create the name value pairs
		theNameValuePairs[0].SetName("MfcISAPICommand");
		theNameValuePairs[0].SetValue("ReturnUserEmail");
		theNameValuePairs[1].SetName("requested");
		theNameValuePairs[1].SetValue(pRequestedUserId);

		// show login page
		LoginDialog(Action, 2, theNameValuePairs);

		CleanUp();

		return;
	}

	// Heading, etc
	*mpStream <<	"<html><head><title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" User E-mail Information"
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
	*mpStream	<<	"<h2>User ID History and E-mail Information for "
				<<	pRequestedUserId
				<<	"</h2>\n";

	// legal, rule

	// display user email
	*mpStream	<<	"<p>"
				<<	pRequestedUserId
				<<	" can be contacted via e-mail: ";
	pUserEmailWidget = new clsUserEmailWidget;
	pUserEmailWidget->SetUser(pRequestedUser);
	pUserEmailWidget->EmitHTML(mpStream);
	delete pUserEmailWidget;
		*mpStream	<<	"</p>";

	// display user id history
	*mpStream	<<	"<p>";
	pUserIdHistoryWidget = new clsAliasHistoryWidget(mpMarketPlace, this, UserIdAlias);
	pUserIdHistoryWidget->SetUser(pRequestedUser);
	pUserIdHistoryWidget->EmitHTML(mpStream);
	delete pUserIdHistoryWidget;

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
