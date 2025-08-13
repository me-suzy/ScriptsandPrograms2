/*	$Id: clsAccountDetail.h,v 1.10.166.2 1999/07/07 16:25:11 sam Exp $	*/
//
//	File:	clsAccountDetail.h
//
//	Class:	clsAccountDetail
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Represents a details about a user's account
//
// Modifications:
//				- 04/02/97 michael	- Created
//				- 06/19/98 inna		- added CCNotOnFilePerCustRequest transaction
//				- 06/21/99 sam		- added codes for Collectors Universe
//
#ifndef CLSACCOUNTDETAIL_INCLUDED

#include "eBayTypes.h"

#include <time.h>

#include <vector.h>
#include <list.h>

typedef enum
{
	AccountDetailUnknown				= 0,
	AccountDetailFeeInsertion			= 1,
	AccountDetailFeeBold				= 2,
	AccountDetailFeeFeatured			= 3,
	AccountDetailFeeCategoryFeatured	= 4,
	AccountDetailFeeFinalValue			= 5,
	AccountDetailPaymentCheck			= 6,
	AccountDetailPaymentCC				= 7,	
	AccountDetailCreditCourtesy			= 8,
	AccountDetailCreditNoSale			= 9,
	AccountDetailCreditPartialSale		= 10,
	AccountDetailRefundCC				= 11,
	AccountDetailRefundCheck			= 12,
	AccountDetailFinanceCharge			= 13,
	AccountDetailAWDebit				= 14,
	AccountDetailAWCredit				= 15,
	AccountDetailAWMemo					= 16,
	AccountDetailCreditDuplicateListing	= 17,
	AccountDetailFeePartialSale			= 18,
	AccountDetailPaymentCCOnce			= 20,
	AccountDetailFeeReturnedCheck		= 21,
	AccountDetailFeeRedepositCheck		= 22,
	AccountDetailPaymentCash			= 23,
	AccountDetailCreditInsertion		= 24,
	AccountDetailCreditBold				= 25,
	AccountDetailCreditFeatured			= 26,
	AccountDetailCreditCategoryFeatured	= 27,
	AccountDetailCreditFinalValue		= 28,
	AccountDetailFeeNSFCheck			= 29,
	AccountDetailFeeReturnCheckClose	= 30,
	AccountDetailMemo					= 31,
	AccountDetailPaymentMoneyOrder		= 32,
	AccountDetailCreditCardOnFile		= 33,
	AccountDetailCreditCardNotOnFile	= 34,
	AccountDetailInvoiced				= 35,
	AccountDetailInvoicedCreditCard		= 36,
	AccountDetailCreditTransferFrom		= 37,
	AccountDetailDebitTransferTo		= 38,
	AccountDetailInvoiceCreditBalance	= 39,
	AccountDetaileBayDebit				= 40,
	AccountDetaileBayCredit				= 41,
	AccountDetailPromotionalCredit		= 42,
	AccountDetailCCNotOnFilePerCustReq	= 43,   //inna
	AccountDetailCreditInsertionFee		= 44,
	AccountDetailCCPaymentRejected		= 45,
	AccountDetailFeeGiftIcon			= 46,
	AccountDetailCreditGiftIcon			= 47,
	AccountDetailFeeGallery				= 48,
	AccountDetailFeeFeaturedGallery		= 49,
	AccountDetailCreditGallery			= 50,
	AccountDetailCreditFeaturedGallery	= 51,
	AccountDetailItemMoveFee			= 52,
	AccountDetailOutageCredit			= 53,	//inna-outage 6/99
	AccountDetailCreditPSA				= 54,	// Sam, Professional Sports Authenticator
	AccountDetailCreditPCGS				= 55,	// Sam, Professional Coin Grading Service
	AccountDetailFinalEntry				= 56	// this has to be the last
												// entry of this enum type.
												// Any new entry has to be
												// add before it. Please
												// adjust the number accordingly.

} AccountDetailTypeEnum;

class clsAccountDetail
{
	public:
	//
	// Default CTOR
	//
	clsAccountDetail() :
				mId(0),
				mTime((time_t)0),
				mType(AccountDetailUnknown),
				mAmount(0),
				mpMemo(NULL),
				mTransactionId(0),
				mItemId(0),
				mBatchId(0)
	{
		memset(&mOldItemId, 0x00, sizeof(mOldItemId));
		return;
	}

	//
	// Fancy CTOR
	//	This CTOR is used when extracting detail
	//	from the database, and time is included
	//
	clsAccountDetail(time_t theTime,
					 AccountDetailTypeEnum type,
					 double amount,
					 const char *pMemo,
					 TransactionId id,
					 const char *pOldItemId,
					 int itemId,
					 int batchId = 0) :

				mId(0),
				mTime(theTime),
				mType(type),
				mAmount(amount),
				mTransactionId(id),
				mItemId(itemId),
				mBatchId( batchId )

	{
		if (pMemo)
		{
			mpMemo	= new char[strlen(pMemo) + 1];
			strcpy(mpMemo, pMemo);
		}
		else
			mpMemo	= NULL;

		if (pOldItemId)
			strcpy(mOldItemId, pOldItemId);
		else
			memset(&mOldItemId, 0x00, sizeof(mOldItemId));
		return;
	}

				//
	// Fancy CTOR
	//	Sorry. This one is used for the "new" style
	//	AccountDetail records, which include the user id.
	//
	clsAccountDetail(unsigned int id,
					 time_t theTime,
					 AccountDetailTypeEnum type,
					 double amount,
					 const char *pMemo,
					 TransactionId xid,
					 const char *pOldItemId,
					 int itemId,
					 int batchId = 0) :

				mId(id),
				mTime(theTime),
				mType(type),
				mAmount(amount),
				mTransactionId(xid),
				mItemId(itemId),
				mBatchId( batchId )

	{
		if (pMemo)
		{
			mpMemo	= new char[strlen(pMemo) + 1];
			strcpy(mpMemo, pMemo);
		}
		else
			mpMemo	= NULL;

		if (pOldItemId)
			strcpy(mOldItemId, pOldItemId);
		else
			memset(&mOldItemId, 0x00, sizeof(mOldItemId));
		return;
	}

	//
	// Fancy CTOR
	//	This CTOR is used when creating new detail
	//	records, and time is added when they are 
	//	inserted into the DB
	//
	clsAccountDetail(AccountDetailTypeEnum type,
					 double amount,
					 const char *pMemo) :
				mId(0),
				mTime((time_t)0),
				mType(type),
				mAmount(amount),
				mTransactionId(0),
				mItemId(0),
				mBatchId(0)

	{
		if (pMemo)
		{
			mpMemo	= new char[strlen(pMemo) + 1];
			strcpy(mpMemo, pMemo);
		}
		else
			mpMemo	= NULL;
		memset(&mOldItemId, 0x00, sizeof(mOldItemId));
		return;
	}

	clsAccountDetail(AccountDetailTypeEnum type,
					 double amount,
					 const char *pMemo,
					 int itemId, 
					 int batchId = 0) :

				mTime((time_t)0),
				mType(type),
				mAmount(amount),
				mTransactionId(0),
				mItemId(itemId),
				mBatchId(batchId)

	{
		if (pMemo)
		{
			mpMemo	= new char[strlen(pMemo) + 1];
			strcpy(mpMemo, pMemo);
		}
		else
			mpMemo	= NULL;
		memset(&mOldItemId, 0x00, sizeof(mOldItemId));
		return;
	}

	//
	// Used to load old records
	//
	clsAccountDetail(time_t theTime,
					 AccountDetailTypeEnum type,
					 double amount,
					 const char *pMemo,
					 const char *pOldItemId) :
				mId(0),
				mTime((time_t)theTime),
				mType(type),
				mAmount(amount),
				mTransactionId(0),
				mItemId(0),
				mBatchId(0)

	{
		if (pMemo)
		{
			mpMemo	= new char[strlen(pMemo) + 1];
			strcpy(mpMemo, pMemo);
		}
		strcpy(mOldItemId, pOldItemId);
		return;
	}


	clsAccountDetail(time_t theTime,
					 AccountDetailTypeEnum type,
					 double amount,
					 const char *pMemo) :
				mId(0),
				mTime((time_t)theTime),
				mType(type),
				mAmount(amount),
				mTransactionId(0),
				mItemId(0)
	{
		if (pMemo)
		{
			mpMemo	= new char[strlen(pMemo) + 1];
			strcpy(mpMemo, pMemo);
		}
		memset(&mOldItemId, 0x00, sizeof(mOldItemId));
		return;
	}

	// 
	// DTOR
	//
	~clsAccountDetail()
	{
		delete	[] mpMemo;
	}


	public:
		unsigned int			mId;
		time_t					mTime;
		AccountDetailTypeEnum	mType;
		double					mAmount;
		char					*mpMemo;
		TransactionId			mTransactionId;
		int						mItemId;
// Lena
		int						mBatchId;
		char					mOldItemId[16];
};

// Convienent Typedef
typedef vector<clsAccountDetail *> AccountDetailVector;

//
// This thing is used for STL
//
class clsAccountDetailPtr
{
	public:
		//
		// CTOR
		//
		clsAccountDetailPtr()
		{
			mpAccountDetail	=	NULL;
		}

		clsAccountDetailPtr(clsAccountDetail *pDetail)
		{
			mpAccountDetail	= pDetail;
		}

		~clsAccountDetailPtr()
		{
			return;
		}

		//
		//	"<" operator
		//
		int operator<(clsAccountDetailPtr &pOther);

		clsAccountDetail		*mpAccountDetail;
};

typedef list<clsAccountDetailPtr> AccountDetailList;


#define CLSACCOUNTDETAIL_INCLUDED
#endif /* CLSACCOUNTDETAIL_INCLUDED */



