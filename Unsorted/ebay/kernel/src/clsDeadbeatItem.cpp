/*	$Id: clsDeadbeatItem.cpp,v 1.3 1999/03/22 00:09:42 josh Exp $	*/
//
//	File:	clsDeadbeatItem.cpp
//
//	Class:	clsDeadbeatItem
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//			Represents an item in the deadbeat items table.
//
// Modifications:
//				- 09/22/98 mila		- Created
//				- 12/15/98 mila		- Changed mReasonCode member to char[3] to
//									  support current one-character reason code
//									  and upcoming two-character reason code.
//									  Tweaked SetReasonCode() method accordingly.
//				- 02/23/99 mila		- Changed class to contain a subset of information
//									  contained in clsItem, rather than being derived
//									  from it; this supports changes to ebay_deadbeat_items
//
#pragma warning( disable : 4786 )
#include "eBayKernel.h"
#include "clsDeadbeatItem.h"


// A nice little macro
// setImmed##variable assumes the item already exists in the database 
// with the correct marketplace and id and immediately updates the item
// in the database
//
#define ISTRING_METHODS(variable)					\
char *clsDeadbeatItem::Get##variable()				\
{													\
	return mp##variable;							\
}													\
void clsDeadbeatItem::Set##variable(char *pNew)		\
{													\
	if (mp##variable)								\
		delete mp##variable;						\
	mp##variable = new char[strlen(pNew) + 1];		\
	strcpy(mp##variable, pNew);						\
	return;											\
}													\
void clsDeadbeatItem::SetImmed##variable(char *pNew)	\
{													\
	if (mp##variable)								\
		delete mp##variable;						\
	mp##variable = new char[strlen(pNew) + 1];		\
	strcpy(mp##variable, pNew);						\
	gApp->GetDatabase()->UpdateDeadbeatItem(this);	\
	return;											\
}

#define IINT_METHODS(variable)						\
int clsDeadbeatItem::Get##variable()				\
{													\
	return m##variable;								\
}													\
void clsDeadbeatItem::Set##variable(int newval)		\
{													\
	m##variable	= newval;							\
	return;											\
}													\
void clsDeadbeatItem::SetImmed##variable(int newval)	\
{													\
	m##variable	= newval;							\
	gApp->GetDatabase()->UpdateDeadbeatItem(this);	\
	return;											\
}

#define IDOUBLE_METHODS(variable)					\
double clsDeadbeatItem::Get##variable()				\
{													\
	return m##variable;								\
}													\
void clsDeadbeatItem::Set##variable(double newval)	\
{													\
	m##variable	= newval;							\
	return;											\
}													\
void clsDeadbeatItem::SetImmed##variable(double newval)	\
{													\
	m##variable	= newval;							\
	gApp->GetDatabase()->UpdateDeadbeatItem(this);	\
	return;											\
}

#define ILONG_METHODS(variable)						\
long clsDeadbeatItem::Get##variable()				\
{													\
	return m##variable;								\
}													\
void clsDeadbeatItem::Set##variable(long newval)	\
{													\
	m##variable	= newval;							\
	return;											\
} 													\
void clsDeadbeatItem::SetImmed##variable(long newval)	\
{													\
	m##variable	= newval;							\
	gApp->GetDatabase()->UpdateDeadbeatItem(this);	\
	return;											\
}

#define IBOOL_METHODS(variable)						\
bool clsDeadbeatItem::Get##variable()				\
{													\
	return m##variable;								\
}													\
void clsDeadbeatItem::Set##variable(bool newval)	\
{													\
	m##variable	= newval;							\
	return;											\
} 													\
void clsDeadbeatItem::SetImmed##variable(bool newval)	\
{													\
	m##variable	= newval;							\
	gApp->GetDatabase()->UpdateDeadbeatItem(this);	\
	return;											\
} 													\


//
// AddDeadbeatItem
//
void clsDeadbeatItem::AddDeadbeatItem()
{
	gApp->GetDatabase()->AddDeadbeatItem(this);

	return;
};

//
// DeleteDeadbeatItem
//
void clsDeadbeatItem::DeleteDeadbeatItem()
{
	gApp->GetDatabase()->DeleteDeadbeatItem(gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
											GetId(),
											GetSeller(),
	                                        GetBidder());

	return;
};

//
// GetDeadbeatItem
//
void clsDeadbeatItem::GetDeadbeatItem()
{
	gApp->GetDatabase()->GetDeadbeatItem(GetId(), GetSeller(), GetBidder(), this, NULL, 0);

	return;
};

//
// IsDeadbeat
//
bool clsDeadbeatItem::IsDeadbeat()
{
	return gApp->GetDatabase()->IsDeadbeatItem(gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
											   GetId(),
											   GetSeller(),
											   GetBidder());
}

void clsDeadbeatItem::Set(MarketPlaceId marketplace,
						  int id,
						  int seller,
						  int bidder,
						  time_t startTime,
						  time_t endTime,
						  char *pTitle,
						  float price,
						  int quantity,
						  char *pReasonCode,
						  int transactionId,
						  bool notified,
						  time_t created,
						  time_t lastModified,
						  char *pRowId,
						  long delta)
{
			mMarketPlaceId = marketplace;
			mId = id;
			mSeller = seller;
			mBidder = bidder;
			mStartTime = startTime;
			mEndTime = endTime;
			mPrice = price;
			mQuantity = quantity;
			mTransactionId = transactionId;
			mNotified = notified;
			mCreationTime = created;
			mLastModifiedTime = lastModified;
			mDelta = delta;

			if (pTitle != NULL)
			{
				mpTitle	= new char[strlen(pTitle) + 1];
				strcpy(mpTitle, pTitle);
			}
			else
				mpTitle	= NULL;

			if (pReasonCode != NULL)
			{
				strncpy(mReasonCode, pReasonCode, sizeof(mReasonCode));
			}
			else
				strcpy(mReasonCode, "*");

			if (pRowId != NULL)
			{
				mpRowId	= new char[strlen(pRowId) + 1];
				strcpy(mpRowId, pRowId);
			}
			else
				mpRowId	= NULL;
}

IINT_METHODS(MarketPlaceId);
IINT_METHODS(Id);
IINT_METHODS(Seller);
IINT_METHODS(Bidder);
ILONG_METHODS(StartTime);
ILONG_METHODS(EndTime);
ISTRING_METHODS(Title);
IDOUBLE_METHODS(Price);
IINT_METHODS(Quantity);
IBOOL_METHODS(Notified);
ILONG_METHODS(CreationTime);
ILONG_METHODS(LastModifiedTime);
ILONG_METHODS(Delta);
ISTRING_METHODS(RowId);

//
// SetNotified
//
void clsDeadbeatItem::SetNotified(bool notified, bool propagate)
{
	mNotified = notified;
	if (propagate)
		gApp->GetDatabase()->SetDeadbeatItemWarned(mId, mSeller, mBidder);

	return;
}

//
// SetReasonCode
//
void clsDeadbeatItem::SetReasonCode(const char *pCode)
{
#if 0	// XXX not yet
	int code;

	if (pCode != NULL)
	{
		code = atoi(pCode);
		if (code > CreditTypeUnknown && code < CreditTypeLast)
			strcpy(mReasonCode, pCode);
		else
			sprintf(mReasonCode, "%02d", CreditTypeUnknown);
	}
	else
		sprintf(mReasonCode, "%02d", CreditTypeUnknown);
#else
	strcpy(mReasonCode, pCode);
#endif
}

#if 0 // XXX not yet
void clsDeadbeatItem::SetReasonCode(int code)
{
	char pCode[3];

	if (code > CreditTypeUnknown && code < CreditTypeLast)
		sprintf(pCode, "%02d", code);
	else
		sprintf(pCode, "%02d", CreditTypeUnknown);
		
	strcpy(mReasonCode, pCode);
}
#endif

