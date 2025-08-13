/*	$Id: clseBayAppAdminEndAllAuctions.cpp,v 1.7.390.1 1999/08/01 02:51:44 barry Exp $	*/
//
//	File:		clseBayAppAdminEndAllAuctions.cpp
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

#include <time.h>

void clseBayApp::AdminEndAllAuctions(char *pUserId,
									 char *pPass,
									 char *pTargetUser,
									 int suspended,
									 int creditFees,
									 int emailbidders,
									 int type,
									 int buddy,
									 char *pText,
									 char *pSellerEmailSubjectTemplate,
									 char *pSellerEmailTemplate,
									 char *pBidderEmailSubjectTemplate,
									 char *pBidderEmailTemplate,
									 char *pBuddyEmailAddress,
									 char *pBuddyEmailSubjectTemplate,
									 char *pBuddyEmailTemplate,
									 eBayISAPIAuthEnum authLevel)
{
	bool						error	= false;

	// The "Target" user
	clsUser						*pSeller = NULL;

	// This is a vector of the items and it's iterator
	ItemList					itemList;
	ItemList::iterator			i;

	list<unsigned int>			lItemIds;

	time_t						nowTime;

	// Setup
	SetUp();

	// Title
	*mpStream <<	"<head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative End All Auctions for "
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

	//
	// Let's make sure this user can do this!
	//
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);

	if (!mpUser)
	{
		error	= true;
	}

	if (mpUser && strstr(mpUser->GetEmail(), "@ebay.com") == 0)
	{
		*mpStream <<	"<font color=red size=+2>Not Authorized</font>"
						"You are not authorized to use this "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" function. ";
		error	= true;
	}


	// Get the user
	pSeller	= mpUsers->GetAndCheckUser(pTargetUser, mpStream);

	if (!pSeller)
	{
		*mpStream <<	"<font color=red size=+2>Invalid Userid</font>"
						"The user whose auctions you\'re trying to end, "
				  <<	pTargetUser
				  <<	" does not exist.";
		error	= true;

		return;
	}

	if (clsNote::IsTextRequired(type))
	{
		if (pText == NULL						||
			strcmp(pText, "default") == 0			)
		{
			*mpStream <<	"<font color=red size=+2>"
							"No long explanation!"
							"</font>"
							"Sorry, but you must provide a complete explanation for "
							"ending this auction."
							"<p>";

			error	= true;
		}
	}


	// Let's get the items listed by this user
	if (pTargetUser)
	{
		pSeller->GetListedItems(&itemList, -1, true);

		if (itemList.size() < 1)
		{
			*mpStream <<	"<font color=red size=+2>"
							"User has no Auctions"
							"</font>"
							"<br>"
					  <<	"User "
					  <<	pTargetUser
					  <<	" has no auctions in progress. No action taken.";

			error	= true;
		}
	}

	// If we got an error, reshow.
	if (error)
	{
		delete	pSeller;

		EndAllAuctionsShow(pUserId,
						   pPass,
						   pTargetUser,
						   suspended,
						   creditFees,
						   emailbidders,
						   type,
						   buddy,
						   pText);

		*mpStream <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	//
	// Ok, we can re-use the code in clseBayAppAdminEndAuction.cpp. We'll
	// make the list of items into a list of item ids, and pass it 
	// along. 
	//
	// ** NOTE **
	// This is a bit inefficent, since we make a brand new list of item
	// ids from a perfectly good set of items, One day I'll fix it
	// ** NOTE **
	//
	for (i = itemList.begin();
		 i != itemList.end();
		 i++)
	{
		// See if the auction of over yet.
		nowTime	= time(0);
		if ((*i).mpItem->GetEndTime() < nowTime)
			continue;

		lItemIds.push_back((*i).mpItem->GetId());
	}

	EndAuctions(&lItemIds, 
			mpUser,
			(eNoteTypeEnum)type,
			buddy,
			pText, 
			pSellerEmailSubjectTemplate,
			pSellerEmailTemplate,
			pBidderEmailSubjectTemplate,
			pBidderEmailTemplate,
			pBuddyEmailAddress,
			pBuddyEmailSubjectTemplate,
			pBuddyEmailTemplate,
			creditFees == 0 ? false : true, 
			suspended == 0 ? false : true,
			emailbidders == 0 ? false : true);


	// Tell them it worked!
	*mpStream <<	"<p>"
					"<font color=red size=+1>All Auctions by User "
			  <<	pTargetUser
			  <<	" have been ended! </font>"
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();


		// Clean up
	for (i = itemList.begin();
		 i != itemList.end();
		 i++)
	{
		delete	(*i).mpItem;
	}

	itemList.erase(itemList.begin(), 
				   itemList.end());

	delete	pSeller;

	CleanUp();

	return;
}

	