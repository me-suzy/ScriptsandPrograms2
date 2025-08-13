/*	$Id: clseBayAppAdminRebalanceUserAccount.cpp,v 1.4.396.2 1999/08/05 20:42:01 nsacco Exp $	*/
//
//	File:		clseBayAppAdminRebalanceUserAccount.cpp
//
//	Class:		clseBayApp
//
//	Author:		Inna Markov (inna@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 07/31/98 inna 	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

void clseBayApp::AdminRebalanceUserAccount(CEBayISAPIExtension *pServer,
							 char *pUserId,
							 eBayISAPIAuthEnum authLevel)
{
	clsAccount* pAccount;

	// Setup
	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Rebalance User Account for "
			  <<	pUserId
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<p>";

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}

	// Get the user
	mpUser	= mpUsers->GetAndCheckUser(pUserId, mpStream);

	if (!mpUser)
	{
		CleanUp();

		return;
	}

	pAccount=NULL;
	pAccount	= mpUser->GetAccount();

	if (!pAccount->Exists())
	{
		*mpStream <<	"<h2>User "
				  <<	pUserId
				  <<	" does not have "				  
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" account. Can not rebalance "
				  <<    "non existing account.</h2>"
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}	
	
	//call rebalance function 
	pAccount->Rebalance();


	delete pAccount;

	// Tell them it worked!
	*mpStream <<	"<h2>Account for User "
			  <<	pUserId
			  <<	" has been rebalanced!</h2>"
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

