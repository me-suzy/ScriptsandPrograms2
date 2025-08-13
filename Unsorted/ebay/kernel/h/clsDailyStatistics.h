/*	$Id: clsDailyStatistics.h,v 1.2 1998/06/23 04:27:56 josh Exp $	*/
//
//	File:		clsDailyStatistics.h
//
// Class:	clsDailyStatistics
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Represents dialy statistics
//
// Modifications:
//				- 10/07/97 wen	- Created
//
#ifndef CLSDAILYSTATISTICS_INCLUDED
#define CLSDAILYSTATISTICS_INCLUDED

#include "eBayTypes.h"
#include "time.h"
#include "vector.h"

class clsDailyStatistics
{
	public:

		// Constructors
		clsDailyStatistics(){;}
		clsDailyStatistics( 	MarketPlaceId Marketplace,
								time_t	TheTime,
								int		XactionId,
								int		CatId,
								int		Items,
								float	Dollar,
								int		BidCount);

		// destructor
		~clsDailyStatistics(){;}

		// Gets
		MarketPlaceId GetMarketplace() { return mMarketplace;}
		time_t GetDate()			{ return mToday;}
		CategoryId GetCategoryId()	{ return mCatId;}
		int		GetNumberOfItems()  { return mNumberOfItems;}
		float	GetDollarAmount()	{ return mDollarAmount;}
		int		GetBidCount()		{ return mBidCount;}
		TransactionEnum GetTransType() { return mTransType;}

	protected:
		MarketPlaceId mMarketplace;
		time_t		mToday;
		CategoryId	mCatId;
		TransactionEnum	mTransType;
		int			mNumberOfItems;
		float		mDollarAmount;
		int			mBidCount;
};

typedef vector<clsDailyStatistics *> DailyStatsVector;

#endif // CLSDAILYSTATISTICS_INCLUDED
