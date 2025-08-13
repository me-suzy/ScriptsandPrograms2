/*	$Id: clseBayAppAdminRetractAllBids.cpp,v 1.5.396.2 1999/08/05 20:42:04 nsacco Exp $	*/
//
//	File:		clseBayAppAdminRetractAllBids.cpp
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

void clseBayApp::RetractAllBids(CEBayISAPIExtension *pServer,
							    char *pUserId,
							    eBayISAPIAuthEnum authLevel,
								bool cautionToTheWind)
{

	// This is a vector of the items
	ItemList					itemList;

	// And an iterator for it
	ItemList::iterator			i;

	// Bid
	clsBid						*pBid;

	// Setup
	SetUp();
	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Retract All Bids for "
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

	// Let's get the items listed by this user
	mpUser->GetBidItems(&itemList, 
						   -1);

	if (itemList.size() < 1)
	{
		*mpStream <<	"<h2>User has no outstanding bids</h2>"
				  <<	"User "
				  <<	pUserId
				  <<	" has no bids on auctions in progress. No action taken."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	if (itemList.size() > 100 && cautionToTheWind)
	{
		*mpStream <<	"<h2>Over 100 bids</h2>"
				  <<	"User "
				  <<	pUserId
				  <<	" appears to have over 100 outstanding bids. No action taken."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}



	// End dem auctions!

	for (i = itemList.begin();
		 i != itemList.end();
		 i++)
	{
		(*i).mpItem->CancelBids(mpUser->GetId());

		pBid	= new clsBid(time(0),
							 BID_CANCELLED,
							 mpUser->GetId(),
							 0,
							 0,
							 "Administrative Cancellation");

		(*i).mpItem->Bid(pBid);
	
		// Recompute the item's price
//		mpItem->AdjustPrice();

		*mpStream <<	"<p>"
				  <<	"Bids retracted for Auction <b>#"
				  <<	(*i).mpItem->GetId()
				  <<	"</b> ("
				  <<	(*i).mpItem->GetTitle()
				  <<	").";
	}


	// Tell them it worked!
	*mpStream <<	"<p>"
					"<font color=red size=+1>All Bids by User "
			  <<	pUserId
			  <<	" have been retracted! </font>"
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

	CleanUp();
	return;
}

	