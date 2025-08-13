/*	$Id: clsDeadbeat.cpp,v 1.2 1999/03/07 08:16:52 josh Exp $	*/
//
//	File:	clsDeadbeat.cc
//
//	Class:	clsDeadbeat
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Represents the deadbeat info for a user
//
// Modifications:
//				- 12/01/98 mila		- Created
//				- 12/15/98 mila		- Added new method AddDeadbeatItem;
//									  fixed bugs in SetCreditRequestCount and
//									  SetWarningCount
//

// This pragma avoids annoying warning messages
// about overlength names generated for STL
#pragma warning( disable : 4786 )
#include "eBayKernel.h"

#include <stdio.h>

//
// Destructor
//
clsDeadbeat::~clsDeadbeat()
{
	ReleaseSellerItems();
	ReleaseBidderItems();

	return;
}

//
// GetDeadbeatScore
//
int clsDeadbeat::GetDeadbeatScore()
{
	int				score;

	if (IsValidDeadbeatScore())
		score = mDeadbeatScore;
	else
	{
		// Get the score from the database.
		score = gApp->GetDatabase()->GetDeadbeatScore(mId);
		ValidateDeadbeatScore();
	}

	return score;
}

//
// GetCreditRequestCount
//
int clsDeadbeat::GetCreditRequestCount()
{
	int				count;

	if (IsValidCreditRequestCount())
		count = mCreditRequestCount;
	else
	{
		// Get the count from the database.
		count = gApp->GetDatabase()->GetCreditRequestCount(mId);
		ValidateCreditRequestCount();
	}

	return count;
}

//
// GetWarningCount
//
int clsDeadbeat::GetWarningCount()
{
	int				count;

	if (IsValidWarningCount())
		count = mWarningCount;
	else
	{
		count = gApp->GetDatabase()->GetWarningCount(mId);
		ValidateWarningCount();
	}

	return count;
}

//
// ValidateDeadbeatScore
//		Set the flag for valid deadbeat score.
//
void clsDeadbeat::ValidateDeadbeatScore()
{
	mValidDeadbeatScore = true;
	gApp->GetDatabase()->ValidateDeadbeatScore(mId);
}

//
// InvalidateDeadbeatScore
//		Clear the flag for valid deadbeat score.
//
void clsDeadbeat::InvalidateDeadbeatScore()
{
	mValidDeadbeatScore = false;
	gApp->GetDatabase()->InvalidateDeadbeatScore(mId);
}

//
// ValidateCreditRequestCount
//		Set the flag for valid credit request count.
//
void clsDeadbeat::ValidateCreditRequestCount()
{
	mValidCreditRequestCount = true;
	gApp->GetDatabase()->ValidateCreditRequestCount(mId);
}

//
// InvalidateCreditRequestCount
//		Clear the flag for valid credit request count.
//
void clsDeadbeat::InvalidateCreditRequestCount()
{
	mValidCreditRequestCount = false;
	gApp->GetDatabase()->InvalidateCreditRequestCount(mId);
}

//
// ValidateWarningCount
//		Set the flag for valid warning count.
//
void clsDeadbeat::ValidateWarningCount()
{
	mValidWarningCount = true;
	gApp->GetDatabase()->ValidateWarningCount(mId);
}

//
// InvalidateWarningCount
//		Clear the flag for valid warning count.
//
void clsDeadbeat::InvalidateWarningCount()
{
	mValidWarningCount = false;
	gApp->GetDatabase()->InvalidateWarningCount(mId);
}

//
// GetSellerItems
//	Gets a list of deadbeat items with this user as seller
//
DeadbeatItemVector *clsDeadbeat::GetSellerItems()
{
	if (!mGotSellerItems)
	{
		gApp->GetDatabase()->GetDeadbeatItemsBySellerId(mId,
											  &mvSellerItems);
		mGotSellerItems = true;
	}

	return &mvSellerItems; 
}

//
// GetBidderItems
//	Gets a list of deadbeat items with this user as high bidder
//
DeadbeatItemVector *clsDeadbeat::GetBidderItems()
{
	if (!mGotBidderItems)
	{
		gApp->GetDatabase()->GetDeadbeatItemsByBidderId(mId,
											  &mvBidderItems);
		mGotBidderItems = true;
	}

	return &mvBidderItems; 
}


//
// ReleaseSellerItems
//	Releases the vector of seller items
//
void clsDeadbeat::ReleaseSellerItems()
{
	DeadbeatItemVector::iterator	i;

	if (mGotSellerItems)
	{
		for (i = mvSellerItems.begin();
			 i != mvSellerItems.end();
			 i++)
		{
			delete (*i);
		}

		mvSellerItems.erase(mvSellerItems.begin(),
							mvSellerItems.end());

		// no more items in vector
		mGotSellerItems = false;
	}
}

//
// ReleaseBidderItems
//	Releases the vector of bidder items
//
void clsDeadbeat::ReleaseBidderItems()
{
	DeadbeatItemVector::iterator	i;

	if (mGotBidderItems)
	{
		for (i = mvBidderItems.begin();
			 i != mvBidderItems.end();
			 i++)
		{
			delete (*i);
		}

		mvBidderItems.erase(mvBidderItems.begin(),
							mvBidderItems.end());

		// no more items in vector
		mGotBidderItems = false;
	}
}

//
// Get a specified deadbeat item
//
clsDeadbeatItem* clsDeadbeat::GetItem(int id, int seller, int bidder)
{
	clsDeadbeatItem *pItem;

	pItem = new clsDeadbeatItem;
	gApp->GetDatabase()->GetDeadbeatItem(id, seller, bidder, pItem, NULL, 0);

	return pItem;
}

//
// AddDeadbeatItem
//
void clsDeadbeat::AddDeadbeatItem(clsDeadbeatItem *pItem)
{
	if (pItem != NULL)
	{
		// Add the deadbeat transaction info to the database
		gApp->GetDatabase()->AddDeadbeatItem(pItem);

		// The seller's credit request count and the bidder's
		// deadbeat score must be invalidated.  The calling
		// method should call clsDeadbeat::InvalidateDeadbeatScore()
		// and clsDeadbeat::InvalidateCreditRequestCount() after
		// this method returns.  (mila 2/19/99)
	}
}

//
// DeleteDeadbeatItem
//
void clsDeadbeat::DeleteDeadbeatItem(int item, int seller, int bidder)
{
	clsMarketPlace *pMarketPlace = NULL;
	MarketPlaceId	marketPlaceId;

	if (item != 0)
	{
		pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
		if (pMarketPlace == NULL)
			return;

		marketPlaceId = pMarketPlace->GetId();

		// Delete the deadbeat transaction info from the database
		gApp->GetDatabase()->DeleteDeadbeatItem(marketPlaceId,
												item,
												seller,
												bidder);

		// The seller's credit request count and the bidder's
		// deadbeat score must be invalidated.  The calling
		// method should call clsDeadbeat::InvalidateDeadbeatScore()
		// and clsDeadbeat::InvalidateCreditRequestCount() after
		// this method returns.  (mila 2/19/99)
	}
}

