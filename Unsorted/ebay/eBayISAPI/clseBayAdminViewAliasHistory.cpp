/*	$Id: clseBayAdminViewAliasHistory.cpp,v 1.3.700.1 1999/08/01 02:51:32 barry Exp $	*/
//
//	File:		clseBayAdminViewAliasHistory.cc
//
//	Class:		clseBayApp
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Display pages shows alias change history
//
//	Modifications:
//				- 01/23/98 Wen	- Created
//		07/19/99	nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
//

#include "ebihdr.h"
#include "clsAliasHistoryWidget.h"

// Used to reference functions in our caller.
// It's probably more "portable" to handle
// this stuff through clsEnvironment.


void clseBayApp::ViewAliasHistory(CEBayISAPIExtension *pServer, 
							 char *pUserId,
							 char *pPass)
{
	clsAliasHistoryWidget*  pUserIdHistoryWidget;

	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" User Id and E-mail History"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// header
	*mpStream	<<	"<h2>User Id and E-mail History for User: "
				<<	pUserId
				<<	"</h2>\n";

	// validate the requestor
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);

	// If we didn't get the user, we're done
	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		CleanUp();
		return;
	}

	// legal, rule

	// display user id history
	pUserIdHistoryWidget = new clsAliasHistoryWidget(mpMarketPlace, this, UserIdAlias);
	pUserIdHistoryWidget->SetUser(mpUser);
	pUserIdHistoryWidget->EmitHTML(mpStream);
	delete pUserIdHistoryWidget;

	*mpStream	<< "<p>";

	pUserIdHistoryWidget = new clsAliasHistoryWidget(mpMarketPlace, this, EMailAlias);
	pUserIdHistoryWidget->SetUser(mpUser);
	pUserIdHistoryWidget->EmitHTML(mpStream);
	delete pUserIdHistoryWidget;

	*mpStream	<< "</p>";

	// the footer
	*mpStream	<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();
	return;
}
