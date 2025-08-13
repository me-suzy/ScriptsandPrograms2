/*	$Id: clsBid.h,v 1.3 1999/02/21 02:46:25 josh Exp $	*/
//
//	File:		clsBid.h
//
// Class:	clsBid
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Represents a bid
//
// Modifications:
//				- 02/10/97 michael	- Created
//
#ifndef CLSBID_INCLUDED

#include "eBayTypes.h"

#include <string.h>
#include <time.h>
#include <vector.h>

typedef enum
{
	BID_UNKNOWN		= 0,
	BID_BID			= 1,
	BID_DUTCH_BID	= 2,
	BID_RETRACTION	= 3,
	BID_AUTORETRACT	= 4,
	BID_CANCELLED	= 5,
	BID_AUTOCANCEL	= 6
} BidActionEnum;

class clsBid
{
	public:
		// Vanilla CTOR and DTOR, as required
		clsBid() :
			mTime(0),
			mAction(BID_UNKNOWN),
			mUser(0),
			mAmount(0),
			mQuantity(0),
			mValue(0)
		{
			mReason[0]	= '\0';
			return;
		}

		~clsBid()
		{
		}

		// Fancy CTOR
		clsBid(time_t time,
			   BidActionEnum action,
			   int user,
			   double amount,
			   int quantity,
			   char *pReason) :
			mTime(time),
			mAction(action),
			mUser(user),
			mAmount(amount),
			mQuantity(quantity)
		{
			mValue	= amount * quantity;

			if (mAction == BID_RETRACTION ||
				mAction	== BID_CANCELLED) 
			{
				mReason[sizeof (mReason) - 1] = 0;
				strncpy(mReason, pReason, sizeof(mReason) - 1);
			}
			else
				mReason[0] = '\0';
			return;
		};

		// Fancy CTOR
		clsBid(time_t time,
			   BidActionEnum action,
			   int user,
			   double amount,
			   int quantity,
			   double value,
			   char *pReason) :
			mTime(time),
			mAction(action),
			mUser(user),
			mAmount(amount),
			mQuantity(quantity),
			mValue(value)
		{
			if (mAction == BID_RETRACTION ||
				mAction == BID_CANCELLED) 
			{
				mReason[sizeof (mReason) - 1] = 0;
				strncpy(mReason, pReason, sizeof(mReason) - 1);
			}
			else
				mReason[0] = '\0';
			return;
		};

		bool operator==(const clsBid& a) const
		{
			if (a.mUser == mUser &&
				 a.mTime == mTime)
			{
				return true;
			}
			else
			{
				return false;
			}
		}


		time_t			mTime;			// Bid Time
		BidActionEnum	mAction;		// Bid Action
		int				mUser;			// Bid User
		double			mAmount;		// Bid amount
		int				mQuantity;		// Bid quantity
		char			mReason[128];	// Retraction reason 
		double			mValue;

	private:
};

// Convienent Typedef
typedef vector<clsBid *> BidVector;

#define CLSBID_INCLUDED
#endif /* CLSBID_INCLUDED */



