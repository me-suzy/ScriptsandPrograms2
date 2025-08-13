/*	$Id: clsDeadbeat.h,v 1.2 1999/03/07 08:16:45 josh Exp $	*/
//
//	File:		clsDeadbeat.h
//
// Class:	clsDeadbeat
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Represents the deadbeat info for a user
//
// Modifications:
//				- 12/02/98 mila		- Created
//				- 12/15/98 mila		- Added new method AddDeadbeatItem
//

#ifndef clsDeadbeat_INCLUDED

#include "eBayTypes.h"
#include "time.h"
#include "vector.h"
#include "iterator.h"

#include "clsDeadbeatItem.h"

// Class Foward
class clsUser;
class clsDatabase;
 
//
// clsDeadbeat
//
class clsDeadbeat
{
	public:
		//
		// Constructor, Destructor
		//
		clsDeadbeat() :
			mId(0),
			mCreated(time(0)),
			mLastModified(time(0)),
			mUserIsDeadbeat(false),
			mDeadbeatScore(0),
			mUserHasCreditRequests(false),
			mCreditRequestCount(0),
			mUserHasWarnings(false),
			mWarningCount(0),
			mGotSellerItems(false),
			mGotBidderItems(false),
			mValidDeadbeatScore(false),
			mValidCreditRequestCount(false),
			mValidWarningCount(false)
		{
		}

		clsDeadbeat(int id,
					time_t created = time(0),
					time_t lastModified = time(0),
					int deadbeatScore = 0,
					int creditRequestCount = 0,
					int warningCount = 0,
					bool isValidDeadbeatScore = false,
					bool isValidCreditRequestCount = false,
					bool isValidWarningCount = false) :
			mId(id),
			mCreated(created),
			mLastModified(lastModified),
			mDeadbeatScore(deadbeatScore),
			mUserIsDeadbeat(deadbeatScore < 0),
			mCreditRequestCount(creditRequestCount),
			mUserHasCreditRequests(creditRequestCount > 0),
			mWarningCount(warningCount),
			mUserHasWarnings(warningCount > 0),
			mGotSellerItems(false),
			mGotBidderItems(false),
			mValidDeadbeatScore(isValidDeadbeatScore),
			mValidCreditRequestCount(isValidCreditRequestCount),
			mValidWarningCount(isValidWarningCount)
		{
		}

		//
		// Destructor
		//
		~clsDeadbeat();

		//
		// UserIsDeadbeat
		//
		bool UserIsDeadbeat() { return mUserIsDeadbeat; }

		//
		// UserHasCreditRequests
		//
		bool UserHasCreditRequests() { return mUserHasCreditRequests; }

		//
		// SetId, GetId
		//
		void SetId(int id) { mId = id; }
		int GetId() { return mId; }

		//
		// GetDeadbeatScore
		//
		int	GetDeadbeatScore();

		//
		// GetCreditRequestCount
		//
		int	GetCreditRequestCount();

		//
		// GetWarningCount
		//
		int	GetWarningCount();

		//
		// ValidateDeadbeatScore
		//		Set the flag for valid deadbeat score.
		//
		void ValidateDeadbeatScore();

		//
		// InvalidateDeadbeatScore
		//		Clear the flag for valid deadbeat score.
		//
		void InvalidateDeadbeatScore();

		//
		// IsValidDeadbeatScore
		//		Return true if deadbeat score is valid, or false otherwise.
		//
		bool IsValidDeadbeatScore() { return mValidDeadbeatScore; }

		//
		// ValidateCreditRequestCount
		//		Set the flag for valid credit request count.
		//
		void ValidateCreditRequestCount();

		//
		// InvalidateCreditRequestCount
		//		Clear the flag for valid credit request count.
		//
		void InvalidateCreditRequestCount();

		//
		// IsValidCreditRequestCount
		//		Return true if credit request count is valid, or false otherwise.
		//
		bool IsValidCreditRequestCount() { return mValidCreditRequestCount; }

		//
		// ValidateWarningCount
		//		Set the flag for valid warning count.
		//
		void ValidateWarningCount();

		//
		// InvalidateWarningCount
		//		Clear the flag for valid warning count.
		//
		void InvalidateWarningCount();

		//
		// IsValidWarningCount
		//		Return true if warning count is valid, or false otherwise.
		//
		bool IsValidWarningCount() { return mValidWarningCount; }

		//
		// ValidateScores
		//		Set the flags for deadbeat score, credit request count, and warning count.
		//
		void ValidateScores() { ValidateDeadbeatScore();
								ValidateCreditRequestCount();
								ValidateWarningCount(); }

		//
		// InvalidateScores
		//		Clear the flags for deadbeat score, credit request count, and warning count.
		//
		void InvalidateScores() { InvalidateDeadbeatScore();
								  InvalidateCreditRequestCount();
								  InvalidateWarningCount(); }

		//
		// AreValidScores
		//		Return true if deadbeat score, credit request count, and warning count are 
		//		all valid, or false otherwise.
		//
		bool AreValidScores() { return IsValidDeadbeatScore()
										&& IsValidCreditRequestCount()
										&& IsValidWarningCount(); }

		//
		// GetSellerItems
		//
		DeadbeatItemVector * GetSellerItems();

		//
		// GetBidderItems
		//
		DeadbeatItemVector * GetBidderItems();

		//
		// ReleaseSellerItems
		//
		void ReleaseSellerItems();

		//
		// ReleaseBidderItems
		//
		void ReleaseBidderItems();

		//
		// GetItem
		//
		clsDeadbeatItem* GetItem(int id, int seller, int bidder);

		//
		// AddDeadbeatItem
		//
		void AddDeadbeatItem(clsDeadbeatItem *pItem);
		
		//
		// DeleteDeadbeatItem
		//
		void DeleteDeadbeatItem(int item, int seller, int bidder);
		
		//
		// TransferDeadbeatInfo
		//
		void TransferDeadbeatInfo(clsUser *pFromUser, clsUser *pToUser);

	protected:

	private:
		//
		// mGotSellerItems and mGotBidderItems indicates whether or not this
		// deadbeat object has been populated from the database.
		// There's no "refresh" so delete the deadbeat object and repopulate 
		// to get the updated deadbeat info.
		//
		bool				mUserIsDeadbeat;		// true if mDeadbeatScore < 0
		bool				mUserHasCreditRequests;	// true if mCreditRequestCount > 0
		bool				mUserHasWarnings;		// true if mWarningCount > 0

		bool				mGotSellerItems;		// true if mvSellerItems populated
		bool				mGotBidderItems;		// true if mvBidderItems populated

		int					mId;					// user's numeric id
		int					mDeadbeatScore;			// user's deadbeat score
		int					mCreditRequestCount;	// user's credit request count
		int					mWarningCount;			// warnings issued to user

		bool				mValidDeadbeatScore;		// true if mDeadbeatScore is up-to-date
		bool				mValidCreditRequestCount;	// true if mCreditRequestCount is up-to-date
		bool				mValidWarningCount;			// true if mWarningCount is up-to-date

		time_t				mCreated;				// creation date/time
		time_t				mLastModified;			// last modified date/time

		//
		// Vectors of deadbeat items
		//
		DeadbeatItemVector	mvSellerItems;
		DeadbeatItemVector	mvBidderItems;
};
		
//
// A vector of deadbeats
//
typedef vector<clsDeadbeat *>	DeadbeatVector;

#define clsDeadbeat_INCLUDED 1
#endif /* clsDeadbeat_INCLUDED */


	

	
