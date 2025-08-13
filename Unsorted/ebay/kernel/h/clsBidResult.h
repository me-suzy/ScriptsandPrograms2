/*	$Id: clsBidResult.h,v 1.2 1998/06/23 04:27:46 josh Exp $	*/
//
//	File:	clsBidResult.h
//
//	Class:	clsBidResult
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//		Reflects the result(s) of a proposed or 
//		actual bid.
//
// Modifications:
//				- 02/10/97 michael	- Created
//
#ifndef CLSBIDRESULT_INCLUDED

#include "eBayTypes.h"

class clsBidResult
{
	public:
		//
		// Indicates if the bid was accepted or
		// not
		//
		bool	mBidAccepted;

		//
		// Indicates that there were change(s) 
		// made to the bid (rounding, for example)
		//
		bool	mBidChanged;

		//
		// Indicates that, despite the user's bid,
		// they were somehow outbid
		//
		bool	mOutBid;

		//
		// Indicates the minimum acceptable bid on
		// the item
		//
		double mMinimumAcceptableBid;

		//
		// The Quantity and maximum bid values,
		// adjusted if necessary. If they've been
		// adjusted, then mBidChanged will be true.
		//
		int		mQuantity;
		double	mMaxBid;

		//
		// The actual resulting bid on the item. Depending
		// on the bidding algorithm, this may NOT be the
		// maximum bid, it may be some other computed value.
		//
		double	mBid;

		//
		// The bid increment (if any) used to compute the 
		// adjust maximum bid
		//
		double	mBidIncrement;

};

#define CLSBIDRESULT_INCLUDED 1
#endif /* CLSBIDRESULT_INCLUDED */ 

