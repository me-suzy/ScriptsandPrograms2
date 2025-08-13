/*	$Id: clseBayAppAdminConfirmUser.cpp,v 1.5.396.1 1999/08/01 02:51:43 barry Exp $	*/
//
//	File:		clseBayAppAdminConfirmUser.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 06/14/97 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

void clseBayApp::ConfirmUser(CEBayISAPIExtension *pServer,
							 char *pUserId,
							 eBayISAPIAuthEnum authLevel)
{
	// Setup
	SetUp();

	// Title
	*mpStream <<	"<head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Confirmation for "
			  <<	pUserId
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<p>";

	// Let's see if we're allowed to do this
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
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// Sanity
	if (!mpUser->IsUnconfirmed())
	{
		*mpStream <<	"<h2> User "
				  <<	pUserId
				  <<	" is not unconfirmed!</h2>"
				  <<	"This user is not is a confirmation pending status. No action was taken."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}



	// Suspend them!
	mpUser->SetConfirmed();

	// update them!
	mpUser->UpdateUser();

	// Tell them it worked!
	*mpStream <<	"<h2>User "
			  <<	pUserId
			  <<	" Confirmed! </h2>"
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

	