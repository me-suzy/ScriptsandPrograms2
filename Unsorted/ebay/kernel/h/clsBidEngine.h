/*	$Id: clsBidEngine.h,v 1.3 1999/04/07 05:42:35 josh Exp $	*/
//
//	File:	clsBidEngine.h
//
//	Class:	clsBidEngine
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	clsBidEngine is an abstract base class describing how
//	an item's bidding is handled
//
// Modifications:
//				- 02/10/97 michael	- Created
//
#ifndef CLSBIDENGINE_INCLUDED

#include "eBayTypes.h"
#include "clsBid.h"
#include <math.h>

//
// Class forward
//
class clsItem;
class clsMarketPlace;
class clsUsers;
class clsUser;
class clsBid;
class clsBidResult;
class ostream;
class clsAnnouncements;
class clsAnnouncement;

// 
// Rounding routine
//
/*inline double RoundToCents(double it)
{
	double		itCents;
	int			itInt;

	itCents	= it * 100;

	if (itCents >= 0)
		itCents	= itCents + .5;
	else
		itCents	= itCents - .5;

	itInt	= (int)itCents;
	it		= (float)itInt / 100;

	return it;
}*/
//new code - to deal with bigger nubmers 
inline double RoundToCents(double it)
{
    bool bWasNegative = false;
    if (it < 0)
    {
        bWasNegative = true;
        it = -it;
    }
    double  itCents = floor(it * 100.0 + .5) / 100.0;
    return (bWasNegative) ? -itCents : itCents;
}

/*
inline int RoundToCentsTimes100(double it)
{
	double		itCents;

	itCents	= it * 100;

	if (itCents >= 0)
		itCents	= itCents + .5;
	else
		itCents	= itCents - .5;

	return (int)itCents;
}
*/

inline int RoundToCentsTimes100(double it)
{
    bool bWasNegative = false;
    if (it < 0)
    {
        bWasNegative = true;
        it = -it;
    }
    int itCents = (int)floor(it * 100.0 + .5);
    return (bWasNegative) ? -itCents : itCents;
}

class clsBidEngine
{
	public:
		// 
		// CTOR, DTOR
		//
		//	CTOR is passed the parent item
		//
		clsBidEngine(clsItem *pItem);
		~clsBidEngine();


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
		virtual clsBidResult	*ProposeBid(
									clsBid *pBid,
									ostream *pStream = NULL,
									clsUser *pUser = NULL) = 0;

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
		virtual clsBidResult	*AcceptBid(
									clsBid *pBid,
									ostream *pStream = NULL,
									clsUser *pUser = NULL) = 0;

		//
		// AdjustPrice
		//
		//	Considers which ever bids are appropriate for the
		//	item, and recomputes it's new price.
		//
		//	The caller can optionally provide a "helper", which
		//	is the previous high bid for the item.
		//
		virtual void AdjustPrice(BidVector *pvBids) = 0;

	protected:
		//
		// The "parent" item for this Engine
		//
		clsItem			*mpItem;

		//
		// The item's marketplace
		//
		clsMarketPlace	*mpMarketPlace;

		//
		// The marketplace's users object
		//
		clsUsers		*mpUsers;

		//
		// A (potentially) cached user object
		//
		clsUser			*mpUser;

		clsAnnouncements *mpAnnouncements;

};


#define CLSBIDENGINE_INCLUDED 1
#endif /* CLSBIDENGINE_INCLUDED */

