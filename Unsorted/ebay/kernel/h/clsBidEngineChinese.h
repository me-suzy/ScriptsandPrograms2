/*	$Id: clsBidEngineChinese.h,v 1.2 1998/06/23 04:27:43 josh Exp $	*/
//
//	File:	clsBidEngineChinese.h
//
//	Class:	clsBidEngineChinese
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	clsBidEngineChinese implements what is historically
//	known as a "Chinese" or "silent" auction. 
//
// Modifications:
//				- 02/10/97 michael	- Created
//
#ifndef CLSBIDENGINECHINESE_INCLUDED

#include "eBayTypes.h"
#include "clsBidEngine.h"

//
// Class forward
//
class clsItem;
class clsUser;
class clsBidResult;
class ostream;

class clsBidEngineChinese : public clsBidEngine
{
	public:
		// 
		// CTOR, DTOR
		//
		//	CTOR is passed the parent item
		//
		clsBidEngineChinese(clsItem *pItem);
		~clsBidEngineChinese();

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
		void AdjustPrice(BidVector *pvBids);

	private:
		void NotifyBidder(clsBid *pBid,
						  clsBidResult *pBidResult);
		void NotifyOutBid(int outBidId);

};


#define CLSBIDENGINECHINESE_INCLUDED 1
#endif /* CLSBIDENGINECHINESE_INCLUDED */
