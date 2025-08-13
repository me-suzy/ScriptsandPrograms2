/*	$Id: clsBidEngineDutch.h,v 1.2 1998/06/23 04:27:45 josh Exp $	*/
//
//	File:	clsBidEngineDutch.h
//
//	Class:	clsBidEngineDutch
//
//	Author:	Tini Widjojo (tini@ebay.com)
//
//	Function:
//
//	clsBidEngineDutch implements what is historically
//	known as a "Dutch" auction. 
//
// Modifications:
//				- 08/05/97 tini	- Created
//
#ifndef CLSBIDENGINEDUTCH_INCLUDED

#include "eBayTypes.h"
#include "clsBidEngine.h"

//
// Class forward
//
class clsItem;
class clsUser;
class clsBidResult;
class ostream;

class clsBidEngineDutch : public clsBidEngine
{
	public:
		// 
		// CTOR, DTOR
		//
		//	CTOR is passed the parent item
		//
		clsBidEngineDutch(clsItem *pItem);
		~clsBidEngineDutch();

		//
		// ProposeBid
		//
		//	This method takes a "proposed" clsBid object
		//	and validates it against the bidding method.
		//	It returns a clsBidResult object to report
		//	on the result
		//
		//	This method assumes the id of the bidding user
		//	represents a valid user for the marketplace
		//
		//	The caller can optionally pass a pointer to
		//	the bidding user's user object, as a "helper"
		//
		clsBidResult	*ProposeBid(clsBid *pBid,
									ostream *pStream = NULL,
									clsUser *pUser = NULL);

		//
		//	AcceptBid
		//
		//	This method takes a clsBid object and applies it
		//	to the item.
		//
		//
		//	This method assumes the id of the bidding user
		//	represents a valid user for the marketplace
		//
		//	The caller can optionally pass a pointer to 
		//	the bidding user's user object, as a "helper"
		clsBidResult	*AcceptBid(clsBid *pBid,
								   ostream *pStream = NULL,
								   clsUser *pUser = NULL);

		//
		// AdjustPrice
		//
		//	Considers which ever bids are appropriate for the
		//	item, and recomputes it's new price.
		//
		void AdjustPrice(BidVector *pvFinalBids);

	private:
		void NotifyBidder(clsBid *pBid,
						  clsBidResult *pBidResult);
};


#define CLSBIDENGINEDUTCH_INCLUDED 1
#endif /* CLSBIDENGINEDUTCH_INCLUDED */
