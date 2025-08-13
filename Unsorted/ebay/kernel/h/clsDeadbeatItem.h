/*	$Id: clsDeadbeatItem.h,v 1.3 1999/03/22 00:09:40 josh Exp $	*/
//
//	File:		clsDeadbeatItem.h
//
// Class:	clsDeadbeatItem
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Represents an item involved in a transaction
//				that the high bidder backed out of.
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
#ifndef CLSDEADBEATITEM_INCLUDED

#include "eBayTypes.h"
#include "vector.h"
#include "list.h"
#include "time.h"

//#include "clsItem.h"
class clsItem;

// Some convienent macros
#define ISTRING_VARIABLE(name)				\
private:									\
	char	*mp##name;						\
public:										\
	char	*Get##name();					\
	void	Set##name(char *pNew);			\
	void	SetImmed##name(char *pNew);	

#define IINT_VARIABLE(name)					\
private:									\
	int		m##name;						\
public:										\
	int		Get##name();					\
	void	Set##name(int new_value);		\
	void	SetImmed##name(int new_value);

#define  IDOUBLE_VARIABLE(name)				\
private:									\
	double	m##name;						\
public:										\
	double	Get##name();					\
	void	Set##name(double new_value);	\
	void	SetImmed##name(double new_value);

#define  ILONG_VARIABLE(name)				\
private:									\
	long	m##name;						\
public:										\
	long	Get##name();					\
	void	Set##name(long new_value);		\
	void	SetImmed##name(long new_value);

#define  IBOOL_VARIABLE(name)				\
private:									\
	bool	m##name;						\
public:										\
	bool	Get##name();					\
	void	Set##name(bool new_value);		\
	void	SetImmed##name(bool new_value);

#define  ICHAR_VARIABLE(name)				\
private:									\
	char	m##name;						\
public:										\
	char	Get##name();					\
	void	Set##name(char new_value);


class clsDeadbeatItem
{
	friend class	clsDeadbeatItemPtr;

	public:
		// Vanilla CTOR, as required
		clsDeadbeatItem()
		{
			return;
		}

		// Vanilla DTOR, as required
		~clsDeadbeatItem()
		{
			return;
		}

		// CTOR to initialize just the seller/bidder ids and the item number
		clsDeadbeatItem(int sellerId, int bidderId, int itemNum)
		{
			SetSeller(sellerId);
			SetBidder(bidderId);
			SetId(itemNum);

			return;
		};

		// CTOR to initialize fields from an existing clsItem
		clsDeadbeatItem(clsItem *pItem)
		{
			char *pString;

			char *pTitle;
			char *pRowId;

			if (pItem == NULL)
				return;

			pString = pItem->GetTitle();
			if (pString != NULL)
			{
				pTitle = new char[strlen(pString+1)];
				strcpy(pTitle, pString);
			}
			else
				pTitle = NULL;

			pString = pItem->GetRowId();
			if (pString != NULL)
			{
				pRowId = new char[strlen(pString+1)];
				strcpy(pRowId, pString);
			}
			else
				pRowId = NULL;

			Set(pItem->GetMarketPlaceId(), 
				pItem->GetId(),
				pItem->GetSeller(),
				pItem->GetHighBidder(),
				pItem->GetStartTime(),
				pItem->GetEndTime(),
				pTitle,
				pItem->GetPrice(),
				pItem->GetQuantity(),
				"*",
				0,
				false,
				time(0),
				time(0),
				pRowId,
				pItem->GetDelta());

			return;
		}

		//
		// Set
		//		Set the members of the object.
		//
		void Set(MarketPlaceId marketplace,
				 int id,
				 int seller,
				 int bidder,
				 time_t startTime,
				 time_t endTime,
				 char *pTitle,
				 float price,
				 int quantity,
				 char *reasonCode,
				 int transactionId,
				 bool notified,
				 time_t created,
				 time_t lastModified,
				 char *pRowId,
				 long delta);

		//
		// AddDeadbeatItem
		//		Add this deadbeat item to the database.
		//
		void AddDeadbeatItem();

		//
		// DeleteDeadbeatItem
		//		Delete this deadbeat item from the database.
		//
		void DeleteDeadbeatItem();

		//
		// GetDeadbeatItem
		//		Get this deadbeat item from the database.
		//
		void GetDeadbeatItem();

		//
		// IsDeadbeat
		//		Return true if this item is in the deadbeat items table,
		//		or false otherwise.
		//
		bool IsDeadbeat();

		//
		// SetNotified
		//		Set the notified flag for the item.  If propagate is true,
		//		propagate the change to the database.
		//
		void SetNotified(bool notified, bool propagate);

		//
		// SetReasonCode
		//		Set the reason code for the item's deadbeat status.
		//
		void SetReasonCode(const char *code);
#if 0 // not yet
		void SetReasonCode(int code);
#endif

		//
		// GetReasonCode
		//		Return the reason code for the item's deadbeat status.
		//
		const char *GetReasonCode() { return mReasonCode; }

		//
		// SetTransactionId
		//		Set the transaction ID for the corresponding user account entry.
		//
		void SetTransactionId(unsigned int xactionId) { mTransactionId = xactionId; }

		//
		// GetTransactionId
		//		Return the transaction ID for the corresponding user account entry.
		//
		unsigned int GetTransactionId() { return mTransactionId; }

	protected:

	private:

		IINT_VARIABLE(MarketPlaceId);
		IINT_VARIABLE(Id);
		IINT_VARIABLE(Seller);
		IINT_VARIABLE(Bidder);
		ILONG_VARIABLE(StartTime);
		ILONG_VARIABLE(EndTime);
		ISTRING_VARIABLE(Title);
		IDOUBLE_VARIABLE(Price);
		IINT_VARIABLE(Quantity);
		IBOOL_VARIABLE(Notified);
		ILONG_VARIABLE(CreationTime);		// setter value not used
		ILONG_VARIABLE(LastModifiedTime);	// setter value not used
		ILONG_VARIABLE(Delta);
		ISTRING_VARIABLE(RowId);

		char			mReasonCode[3];		// reason for item's deadbeat status
		unsigned int	mTransactionId;		// transaction id number
};

//
// This thing is used for STL
//
class clsDeadbeatItemPtr
{
	public:
		//
		// CTOR
		//
		clsDeadbeatItemPtr()
		{
			mpItem	=	NULL;
		}

		clsDeadbeatItemPtr(clsDeadbeatItem *pItem)
		{
			mpItem	=	pItem;
		}

		~clsDeadbeatItemPtr()
		{
			delete mpItem;
		}

#if 0	// we're not ready for this yet
		//
		//	"<" operator
		//
		int operator<(clsDeadbeatItemPtr &pOther);
#endif

		clsDeadbeatItem		*mpItem;
};


// Convienent Typedefs
typedef vector<clsDeadbeatItem *> DeadbeatItemVector;

typedef list<clsDeadbeatItemPtr> DeadbeatItemList;

#define CLSDEADBEATITEM_INCLUDED
#endif /* CLSDEADBEATITEM_INCLUDED */



