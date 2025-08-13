/*	$Id: clsDailyStatistics.cpp,v 1.4 1998/08/25 03:20:46 josh Exp $	*/
//
//	File:		clsDailyStatistics.cc
//
// Class:	clsDailyStatistics
//
//	Author:	Wen Wen (wen@ebay.com)
//
//	Function:
//
//				Information for Daily Statistics
//
// Modifications:
//				- 10/06/97 Wen	- Created
//

#include "eBayKernel.h"


clsDailyStatistics::clsDailyStatistics(
								MarketPlaceId Marketplace,
								time_t	TheTime,
								int		XactionId,
								int		CatId,
								int		Items,
								float	Dollar,
								int		BidCount)
{
	mMarketplace	= Marketplace;
	mToday			= TheTime;
	mTransType		= (TransactionEnum) XactionId;
	mCatId			= CatId;
	mNumberOfItems	= Items;
	mDollarAmount	= Dollar;
	mBidCount		= BidCount;
}


