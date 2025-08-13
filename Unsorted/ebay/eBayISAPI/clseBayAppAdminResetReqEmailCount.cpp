/*	$Id: clseBayAppAdminResetReqEmailCount.cpp,v 1.6.396.2 1999/08/05 20:42:04 nsacco Exp $	*/
//
//	File:		clseBayAppAdminResetReqEmailCount.cpp
//
//	Class:		clseBayApp
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 02/10/98 wen	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

void clseBayApp::AdminResetReqEmailCount(CEBayISAPIExtension *pServer,
							 char *pUserId,
							 eBayISAPIAuthEnum authLevel)
{
	// Setup
	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Reset Request E-mail Count for "
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
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// Sanity
	if (mpUser->IsSuspended())
	{
		*mpStream <<	"<h2> User "
				  <<	pUserId
				  <<	" already suspended!</h2>"
				  <<	"The user is already suspended. No action was taken."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	if (mpUser->IsUnconfirmed())
	{
		*mpStream <<	"<h2> User "
				  <<	pUserId
				  <<	" is not confirmed</h2>"
				  <<	"This user has not confirmed their registration, and "
						"no action was taken."
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// Suspend them!
	mpUser->ResetReqEmailCount();

	// Tell them it worked!
	*mpStream <<	"<h2>User "
			  <<	pUserId
			  <<	" e-mail request count is reset to zero!</h2>"
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

void clseBayApp::AdminResetReqUserCount(CEBayISAPIExtension *pServer,
							 char *pUserId,
							 eBayISAPIAuthEnum authLevel)
{
	// Setup
	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Reset Request E-mail Count for "
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
	if (mpUser->IsSuspended())
	{
		*mpStream <<	"<h2> User "
				  <<	pUserId
				  <<	" already suspended!</h2>"
				  <<	"The user is already suspended. No action was taken."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	if (mpUser->IsUnconfirmed())
	{
		*mpStream <<	"<h2> User "
				  <<	pUserId
				  <<	" is not confirmed</h2>"
				  <<	"This user has not confirmed their registration, and "
						"no action was taken."
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// Suspend them!
	mpUser->ResetReqUserCount();

	// Tell them it worked!
	*mpStream <<	"<h2>User "
			  <<	pUserId
			  <<	" e-mail request count is reset to zero!</h2>"
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}
