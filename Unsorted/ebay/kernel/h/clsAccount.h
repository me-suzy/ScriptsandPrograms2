/*	$Id: clsAccount.h,v 1.10 1999/02/21 02:46:22 josh Exp $	*/
//
//	File:	clsAccount.h
//
//	Class:	clsAccount
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Represents a user's account
//
// Modifications:
//				- 04/02/97 michael	- Created
//
#ifndef CLSACCOUNT_INCLUDED

#include "eBayTypes.h"
#include "clsAccountDetail.h"
#include <time.h>

// Class forward
class	clsUser;
class	clsItem;

//
// clsInterimBalance
//
//	Date and amount of an interim balance
//
class clsInterimBalance
{
	public:
		clsInterimBalance() : 
			mId(0),
			mWhen(0),
			mBalance(0)
		{ ; };

		clsInterimBalance(time_t when, double balance) :
			mId(0),
			mWhen(when),
			mBalance(balance)
		{ ; };
		clsInterimBalance(int id, time_t when, double balance) :
			mId(id),
			mWhen(when),
			mBalance(balance)
		{ ; }

		~clsInterimBalance()
		{ ; };
		int			mId;		
		time_t		mWhen;
		double		mBalance;
};

typedef list<clsInterimBalance *> InterimBalanceList;

class clsAccount
{
	public:
		//
		// Default CTOR
		//	This is actually a "good" default CTOR, 
		//	since it reflects what an initial account
		//	would look like.
		//

		clsAccount() :
					mpUser(NULL),
					mBalance(0),
					mLastUpdated((time_t)0),
					mPastDueBase((time_t)0),
					mPastDue30Days(0),
					mPastDue60Days(0),
					mPastDue90Days(0),
					mPastDue120Days(0),
					mPastDueMoreThan120Days(0),
					mCCId(0),
					mCCExpiryDate((time_t)0),
					mCCLastUpdated((time_t)0),
// Lena					mCCLastCCNoticeSent((time_t)0)
					mCCLastCCNoticeSent((time_t)0),
					mTableIndicator(10)

		{
			return;
		};

		//
		// Fancy CTOR
		//
		clsAccount(double balance,
				   time_t lastUpdated,
				   time_t pastDueBase,
				   double pastDue30Days,
				   double pastDue60Days,
				   double pastDue90Days,
				   double pastDue120Days,
				   double pastDueMoreThan120Days,
				   short  ccId,
				   time_t ccExpiryDate,
				   time_t lastCCUpdateTime,
				   time_t lastCCNoticeSent
				   ) :
					mpUser(NULL),
					mBalance(balance),
					mLastUpdated(lastUpdated),
					mPastDueBase(pastDueBase),
					mPastDue30Days(pastDue30Days),
					mPastDue60Days(pastDue60Days),
					mPastDue90Days(pastDue90Days),
					mPastDue120Days(pastDue120Days),
					mPastDueMoreThan120Days(pastDueMoreThan120Days),
					mCCId(ccId),
					mCCExpiryDate(ccExpiryDate),
					mCCLastUpdated(lastCCUpdateTime),
					mCCLastCCNoticeSent(lastCCNoticeSent)


		{
// Lena
			mTableIndicator =10;
			return;
		}
// Lena
		clsAccount(double balance,
				   time_t lastUpdated,
				   time_t pastDueBase,
				   double pastDue30Days,
				   double pastDue60Days,
				   double pastDue90Days,
				   double pastDue120Days,
				   double pastDueMoreThan120Days,
				   short  ccId,
				   time_t ccExpiryDate,
				   time_t lastCCUpdateTime,
				   time_t lastCCNoticeSent,
				   int	  tableIndicator	
				   ) :
					mpUser(NULL),
					mBalance(balance),
					mLastUpdated(lastUpdated),
					mPastDueBase(pastDueBase),
					mPastDue30Days(pastDue30Days),
					mPastDue60Days(pastDue60Days),
					mPastDue90Days(pastDue90Days),
					mPastDue120Days(pastDue120Days),
					mPastDueMoreThan120Days(pastDueMoreThan120Days),
					mCCId(ccId),
					mCCExpiryDate(ccExpiryDate),
					mCCLastUpdated(lastCCUpdateTime),
					mCCLastCCNoticeSent(lastCCNoticeSent),
					mTableIndicator( tableIndicator )


		{
			return;
		}

		// DTOR
		~clsAccount()
		{
		};

		//
		// SetUser
		//	Sets our parent user
		//
		void SetUser(clsUser *pUser);

		//
		// GetBalance, SetBalance
		//
		double GetBalance();
		void SetBalance(double balance);

		//
		// GetLastUpdate
		//
		time_t	GetLastUpdate();

		//
		// Exists
		//
		//	Indicates whether or not the user's account exists
		//	yet.
		//
		bool Exists();
// Lena
		int GetTableIndicator();

		//
		// Charge for Insertion fee
		//
		void ChargeInsertionFee(clsItem *pItem, char* pOldItemNum = NULL);

		//
		// Charge for Bolded item
		//
		void ChargeBoldFee(clsItem *pItem);

		// 
		// Charge for featured listing
		//
		void ChargeFeaturedFee(clsItem *pItem);


		// 
		// Charge for category featured listing
		//
		void ChargeCategoryFeaturedFee(clsItem *pItem);

		// gift icon stuff

		void ChargeGiftIconFee(clsItem *pItem);
		void CreditGiftIconFee(clsItem *pItem);

		void ChargeGalleryFee(clsItem *pItem);
		void ChargeFeaturedGalleryFee(clsItem *pItem);
		void CreditGalleryFee(clsItem *pItem);
		void CreditFeaturedGalleryFee(clsItem *pItem);
		//
		// Charge for FinalValueFee
		//
		void ChargeListingFee(clsItem *pItem);
		void ChargeListingFee(clsItem *pItem, int qtySold);

		void ChargePartialSaleFee(clsItem *pItem,
								  double theFee);
		void ChargePartialSaleFee(char *pItemId,
								  double theFee);

		//
		// Apply a Credit
		//
		void ApplyCourtesyCredit(double creditAmount,
								 char *pMemo);
		void ApplyNoSaleCredit(clsItem *pItem,
								   double creditAmount);
		void ApplyNoSaleCredit(char *pItemId,
							   double creditAmount);
		void ApplyPartialSaleCredit(clsItem *pItem,
									double creditAmount);
		void ApplyPartialSaleCredit(char *pItemId,
									double creditAmount);
		void ApplyInsertionFeeCredit(clsItem* pItem,
									 const char* pMemo);
	
		//
		// AddRawAccountDetail
		//
// Lena
//		void AddRawAccountDetail(clsAccountDetail *pDetail,
//								 int migrationBatchId = 0);
// Lena
		void AddRawAccountDetail(clsAccountDetail *pDetail,
								 int migrationBatchId = 0, int batchId = 0);

		// AddInterimBalance during invoicing

		void AddInterimBalance( int id, time_t theTime, double amount );
		bool GetInterimBalance( int id, time_t &theTime, double &amount, bool first );
		bool GetInterimBalanceForMonth( int id, time_t the_time );

		//
		// GetInterimBalances gets ALL the interim balances for a user
		//
		void GetInterimBalances(InterimBalanceList *pInterimBalances);

		void GetUsersWithAccountsNotInvoiced( vector<unsigned int> *pvIds,
                                                        time_t tInvoiceDate,
														int idStart, int idEnd = 0);
		
		void CombineInterimBalanceForUsers( int oldId, int newId );
		bool UpdateIndicator( int id, int indicator );

		//
		// UpdateCCDetails
		//
		void UpdateCCDetails(int id, int CCId, time_t ccExpDate, time_t ccUpdateTime);

		//
		// Get all the account detail information
		//
		void GetAccountDetail(AccountDetailVector *pvDetail);
		// Lena
		void GetAccountDetail( AccountDetailVector *pvDetail, time_t since );
		void GetAccountDetailUntil( AccountDetailVector *pvDetail, time_t until );
		void GetAccountDetail( AccountDetailVector *pvDetail, 
						time_t since, time_t until );
		void GetAccountDetail(AccountDetailList *plDetail);

		//
		// Get all the account detail for a given item
		//
		void GetAccountDetailForItem(int itemId,
									 AccountDetailVector *pvDetail);

		void GetAccountDetailForItem(char *pItemId,
									 AccountDetailVector *pvDetail);

		//
		// Get all the account detail of a particular type
		//
		void GetAccountDetailByType(AccountDetailTypeEnum type,
									AccountDetailVector *pvDetail);

		//
		// Get a description of an account detail record
		//
		static const char *GetAccountDetailDescriptor(AccountDetailTypeEnum it);
		static const int GetAccountDetailDescriptorMaxLength();

		//
		// Delete all account detail records added at a given time
		//
		void DeleteAccountDetailByTime(time_t theTime);

		//
		// Delete account balance for the user  - used when renaming user
		//
		void DeleteAccountBalance();

		//
		// Rebalance the account
		//
		void Rebalance();
		void RebalanceAccount();
		void SetAccountBalance(float balance);

		//
		// GetAWAccountId
		//
		int GetAWAccountId();

		//
		// SetPastDue
		//
		void SetPastDue(time_t pastDueBase,
						double pastDue30Days,
						double pastDue60Days,
						double pastDue90Days,
						double pastDue120Days,
						double pastDueMoreThan120Days);

		//inna - Get all payments/crdeits by a user from a given date
		void GetPaymentsSince(time_t tSinceDate, double &amount);
		//inna - Get all payments/crdeits by a user from a given date range
		void GetPaymentsByDate(time_t tSinceDate, time_t tEndDate, double &amount);
		//inna - calculate past due amount for a given period
		double CalculateXPastDue(int period, int id, 
									 InterimBalanceList *plBalances, 
									 time_t tStartDate, time_t tEndDate);

		//
		// AdjustBalance
		//
		//
		void AdjustBalance(double delta);

		//
		// GetCCIdForUser
		//
		short GetCCIdForUser();

		//
		// SetCCIdForUser
		//
		void SetCCIdForUser(short CCId);

		//
		// GetCCExpiryDate
		//
		time_t GetCCExpiryDate();

		//
		// SetCCExpiryDate
		//
		void SetCCExpiryDate(time_t CCExpiryDate);

		//
		// GetLastCCUpdate
		//
		time_t GetLastCCUpdate();

		//
		// SetLastCCUpdate
		//
		void SetLastCCUpdate(time_t ccUpdateTime);

		// GetLastCCUpdate
		//
		time_t GetLastCCNoticeSent();
		
		// SetLastCCUpdate
		//
		void SetLastCCNoticeSent(time_t CCLastNoticeSent);

		// for fixing relist problem
		void GetBadAccountDetail(AccountDetailVector *pvDetail);
		//
		//
		//
		time_t	GetPastDueBase();
		float	GetPastDue30Days();
		float	GetPastDue60Days();
		float	GetPastDue90Days();
		float	GetPastDue120Days();
		float	GetPastDueOver120Days();


	private:


		//
		// Our user
		//
		clsUser		*mpUser;

		double		mBalance;
		time_t		mLastUpdated;
		time_t		mPastDueBase;
		double		mPastDue30Days;
		double		mPastDue60Days;
		double		mPastDue90Days;
		double		mPastDue120Days;
		double		mPastDueMoreThan120Days;
		short		mCCId;
		time_t		mCCExpiryDate;
		time_t		mCCLastUpdated;
		time_t		mCCLastCCNoticeSent;
// Lena
		int			mTableIndicator;


};

#define CLSACCOUNT_INCLUDED
#endif /* CLSACCOUNT_INCLUDED */



