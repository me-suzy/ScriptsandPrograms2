/*	$Id: clsUser.cpp,v 1.17.22.3.50.2 1999/08/05 18:59:18 nsacco Exp $	*/
//
//	File:		clsUser.cc
//
// Class:	clsUser
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Represents a user
//
// Modifications:
//				- 02/10/97 michael	- Created
//				- 06/09/97 tini     - changed class to reference 2 user tables
//				- 06/16/97 tini     - added UpdateUser to check if dirty
//				- 06/17/97 tini     - changed LastModifed to not be set in the
//                                    class, but is done in the database call
//									  using system date and time.
//				- 06/20/97 tini		- added functions to handle renaming users.
//				- 09/20/97 chad		- added call to VoidFeedbackLeftByUser
//									  when suspending users.
//				- 02/23/99 anoop    - added IsValidUVRating method to get user's UV rating.
//				- 09/25/98 mila		- added GetDeadbeatScore() and IsDeadbeat()
//				- 10/01/98 mila		- added GetDeadbeatItems()
//				- 12/09/98 mila		- deleted GetDeadbeatItems(); deadbeat items
//									  are now obtained via clsDeadbeat; added new
//									  instance variable mpDeadbeat.
//				- 04/15/99 kaz		- Added support for Police Bage T&C
//				- 06/09/99 nsacco	- Added Australia to OkToViewAdult()
//				- 07/02/99 nsacco	- Added a site id and co partner id
//				- 07/06/99 nsacco	- Added site id and co partner id to constructor
//

#include "eBayKernel.h"

extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

// A nice little macro
#define STRING_METHODS(variable)				\
char *clsUser::Get##variable()					\
{												\
	return mp##variable;						\
}												\
void clsUser::Set##variable(char *pNew)			\
{												\
	if (mp##variable)							\
		delete mp##variable;					\
	mp##variable = new char[strlen(pNew) + 1];	\
	strcpy(mp##variable, pNew);					\
	mDirty	= true;								\
	return;										\
}

#define INT_METHODS(variable)					\
int clsUser::Get##variable()					\
{												\
	return m##variable;							\
}												\
void clsUser::Set##variable(int newval)			\
{												\
	m##variable	= newval;						\
	mDirty	= true;								\
	return;										\
} 

#define INT_METHODS_WITH_UPDATE(variable)		\
int clsUser::Get##variable()					\
{												\
	return m##variable;							\
}												\
void clsUser::Set##variable(int newval)			\
{												\
	m##variable	= newval;						\
	mDirty	= true;								\
	UpdateUser();								\
	return;										\
} 

#define LONG_METHODS(variable)					\
long clsUser::Get##variable()					\
{												\
	return m##variable;							\
}												\
void clsUser::Set##variable(long newval)		\
{												\
	m##variable	= newval;						\
	mDirty	= true;								\
	return;										\
} 

// macro to get stuff from database if mHasDetail = False

#define DSTRING_METHODS(variable)					\
char *clsUser::Get##variable()						\
{													\
	if (!mHasDetail && mUserState != UserGhost)		\
	{												\
		if (gApp->GetDatabase()->GetUserInfo(this))	\
		{											\
			mDirtyDetail = false;					\
			mHasDetail = true;						\
		}											\
	}												\
	return mp##variable;							\
}													\
void clsUser::Set##variable(char *pNew)				\
{													\
	if  (!mHasDetail)								\
	{												\
		gApp->GetDatabase()->GetUserInfo(this);		\
		mHasDetail	= true;							\
	}												\
	if (mp##variable)								\
		delete mp##variable;						\
	if (pNew != NULL)								\
	{												\
		mp##variable = new char[strlen(pNew) + 1];	\
		strcpy(mp##variable, pNew);					\
	}												\
	else											\
		mp##variable = NULL;						\
	mDirty			= true;							\
	mDirtyDetail	= true;							\
	return;											\
}

#define DINT_METHODS(variable)						\
int clsUser::Get##variable()						\
{													\
	if (!mHasDetail)								\
	{												\
		if (gApp->GetDatabase()->GetUserInfo(this))	\
		{											\
			mDirtyDetail = false;					\
			mHasDetail = true;						\
		}											\
	}												\
	return m##variable;								\
}													\
void clsUser::Set##variable(int newval)				\
{													\
	if  (!mHasDetail)							\
	{											\
		gApp->GetDatabase()->GetUserInfo(this);	\
		mHasDetail	= true;						\
	}											\
												\
	m##variable	= newval;						\
	mDirty			= true;						\
	mDirtyDetail	= true;						\
	return;										\
} 

#define DLONG_METHODS(variable)						\
long clsUser::Get##variable()						\
{													\
	if (!mHasDetail)								\
	{												\
		if (gApp->GetDatabase()->GetUserInfo(this))	\
		{											\
			mDirtyDetail = false;					\
			mHasDetail = true;						\
		}											\
	}												\
	return m##variable;								\
}													\
void clsUser::Set##variable(long newval)			\
{													\
	if  (!mHasDetail)								\
	{												\
		gApp->GetDatabase()->GetUserInfo(this);		\
		mHasDetail	= true;							\
	}												\
	m##variable	= newval;							\
	mDirty			= true;							\
	mDirtyDetail	= true;							\
	return;											\
} 

#define DBOOL_METHODS(variable)						\
bool clsUser::Get##variable()						\
{													\
	if (!mHasDetail)								\
	{												\
		if (gApp->GetDatabase()->GetUserInfo(this))	\
		{											\
			mDirtyDetail = false;					\
			mHasDetail = true;						\
		}											\
	}												\
	return m##variable;								\
}													\
void clsUser::Set##variable(bool newval)			\
{													\
	m##variable	= newval;							\
	mDirtyDetail	= true;							\
	return;											\
} 

clsUserRenamePendingCode::clsUserRenamePendingCode(char *pSalt,
												   char *pPass)
{
	if (mpTempSalt)
	{
		mpTempSalt 
			= new char[strlen(pSalt) + 1];
		strcpy(mpTempSalt, pSalt);
	}
	else
	{
		mpTempSalt	= new char;
		*(mpTempSalt)	= 0x00;
	}

	if (mpTempPass)
	{
		mpTempPass 
			= new char[strlen(pPass) + 1];
		strcpy(mpTempPass, pPass);
	}
};

//
// Destructor
//
clsUserRenamePendingCode::~clsUserRenamePendingCode()
{
	delete mpTempSalt;
	delete mpTempPass;
	return;
}


void clsUser::ClearUserInfo()
{
	mpHost			= (char *)0;
	mpName			= (char *)0;
	mpCompany		= (char *)0;
	mpAddress		= (char *)0;
	mpCity			= (char *)0;
	mpState			= (char *)0;
	mpCountry		= (char *)0;
	mpZip			= (char *)0;
	mpDayPhone		= (char *)0;
	mpNightPhone	= (char *)0;
	mpFaxPhone		= (char *)0;
	mCreated		= (long)0;

	mCount			= (long)0;
	mCreditCardOnFile	= false;
	mGoodCredit		= false;

	mpGender		= (char *)0;
	mInterests_1	= (long)0;
	mInterests_2	= (long)0;
	mInterests_3	= (long)0;
	mInterests_4	= (long)0;
	mReqEmailCount	= (long)0;

	mDirty				= false;
	mHasDetail			= false;    // instance has detail info
	mDirtyDetail		= false;

	mDailyItemCount			= 0;
	mDailyItemCountDelta	= 0;
	mPartnerId				= 0;
	mSiteId					= SITE_EBAY_MAIN;	// nsacco 07/02/99
	mCoPartnerId			= PARTNER_NONE;

	mpAccount				= NULL;
	mpFeedback				= NULL;
	mpDeadbeat				= NULL;

	mTopSellerInitiatedDate		= (long)0;
	mTopSellerLevel				= 0;

	return;
}

void clsUser::ClearAll()
{
	mId					= 0;
	mpUserId			= (char *)0;
	mpEmail				= NULL;
	mUserState			= UserUnknown;
	mpPassword			= (char *)0;
	mpSalt				= (char *)0;
	mLastModified		= (long)0;
	mUserIdLastModified	= (long)0;
	mUserFlags			= (long)0;
	mUVRating			= (int)0;
	mUVDetail			= (int)0;
	mUserFlags			= (int)0;
	mCountryId          = (int)0;
	// nsacco 07/02/99
	mSiteId				= SITE_EBAY_MAIN;
	mCoPartnerId		= PARTNER_NONE;
	ClearUserInfo();
}


clsUser::clsUser()
{
	ClearAll();
	return;
}

clsUser::clsUser(int id)
{
	ClearAll();
	mId	= id;
	return;
}

clsUser::~clsUser()
{
	// force an update or error if mDirty or (mHasDetail and mDirtyDetail)?
	// throw exception if dirty
			delete	[] mpUserId;
			delete	[] mpPassword;
			delete	[] mpSalt;
			delete	[] mpHost;
			delete	[] mpName;
			delete	[] mpCompany;
			delete	[] mpAddress;
			delete	[] mpCity;
			delete	[] mpState;
			delete	[] mpZip;
			delete	[] mpCountry;
			delete	[] mpDayPhone;
			delete	[] mpNightPhone;
			delete	[] mpFaxPhone;
			delete	[] mpEmail;
			delete  [] mpGender;
			delete	mpFeedback;
			delete	mpDeadbeat;

			return;
}

// short constructor
// nsacco 07/06/99 added siteid and co partner id
clsUser::clsUser(int marketPlace,
				 int  id,
				 char *pUserId,
				 char *pEmail,
				 UserStateEnum state,
				 char *pPassword,
				 char *pSalt,
				 long lastModified,
				 long userIdLastModified,
				 int userFlags,
				 int countryId,
				 int UVRating,
				 int UVDetail,
				 int siteId,
				 int coPartnerId
				 )
{
	ClearAll();

	mMarketPlace		= marketPlace;
	mId					= id;
	mUserState			= state;

	mpUserId			= new char[strlen(pUserId) + 1];
	strcpy(mpUserId, pUserId);

	mpEmail				= new char[strlen(pEmail) + 1];
	strcpy(mpEmail, pEmail);

	mpPassword			= new char[strlen(pPassword) + 1];
	strcpy(mpPassword, pPassword);

	mpSalt				= new char[strlen(pSalt) + 1];
	strcpy(mpSalt, pSalt);

	mLastModified		= lastModified;
	mUserIdLastModified	= userIdLastModified;

	mUserFlags          = userFlags;

	mUVRating			= UVRating;
	mUVDetail			= UVDetail;
	mCountryId          = countryId;

	// nsacco 07/06/99
	mSiteId				= siteId;
	mCoPartnerId		= coPartnerId;

	mDirty	= false;
	mHasDetail = false;
	return;
}

// fancy constructor

clsUser::clsUser(int marketPlace,
				 int  id,
				 char *pUserId,
				 UserStateEnum state,
				 char *pPassword,
				 char *pSalt,
				 long lastModified,
				 long userIdLastModified,
				 int  userFlags,
				 int  countryId,
				 int  UVRating,
				 int  UVDetail,
				 char *pHost,
				 char *pName,
				 char *pCompany,
				 char *pAddress,
				 char *pCity,
				 char *pState,
				 char *pZip,
				 char *pCountry,
				 char *pDayPhone,
				 char *pNightPhone,
				 char *pFaxPhone,
				 long creation,
				 char *pEmail,
				 int  count,
				 bool credit_card_on_file,
				 bool good_credit,
				 char *pGender,
				 int  interests_1,
				 int  interests_2,
				 int  interests_3,
				 int  interests_4,
				 int  partnerId,
				 int  siteId,		// nsacco 07/02/99
				 int  coPartnerId,
				 int  reqEmailCount,
				 long topSellerInitiatedDate,
				 int  topSellerLevel
				 )
{
	ClearAll();

	mMarketPlace		= marketPlace;
	mId					= id;
	mUserState			= state;

	mPartnerId			= partnerId;
	mSiteId				= siteId;	// nsacco 07/02/99
	mCoPartnerId		= coPartnerId;

	mpUserId			= new char[strlen(pUserId) + 1];
	strcpy(mpUserId, pUserId);

	mpEmail		= new char[strlen(pEmail) + 1];
	strcpy(mpEmail, pEmail);

	mpPassword		= new char[strlen(pPassword) + 1];
	strcpy(mpPassword, pPassword);

	mpSalt				= new char[strlen(pSalt) + 1];
	strcpy(mpSalt, pSalt);

	mLastModified		= lastModified;
	mUserIdLastModified	= userIdLastModified;

	mUserFlags          = userFlags;

	mUVRating			= UVRating;
	mUVDetail			= UVDetail;
	mCountryId          = countryId;

	AddUserInfo(pHost, 
				pName,
				pCompany,
				pAddress,
				pCity,
				pState,
				pZip,
				pCountry,
				pDayPhone,
				pNightPhone,
				pFaxPhone,
				creation,
				count,
				credit_card_on_file,
				good_credit,
				pGender,
				interests_1,
				interests_2,
				interests_3,
				interests_4,
				partnerId,
				siteId,			// nsacco 07/02/99
				coPartnerId,
				reqEmailCount,
				topSellerInitiatedDate,
				topSellerLevel
				);
	mDirty	= false;
	mHasDetail = true;
	return;
}

// add user info

void clsUser::AddUserInfo(
				 char *pHost,
				 char *pName,
				 char *pCompany,
				 char *pAddress,
				 char *pCity,
				 char *pState,
				 char *pZip,
				 char *pCountry,
				 char *pDayPhone,
				 char *pNightPhone,
				 char *pFaxPhone,
				 long creation,
				 int  count,
				 bool credit_card_on_file,
				 bool good_credit,
				 char *pGender,
				 int  interests_1,
				 int  interests_2,
				 int  interests_3,
				 int  interests_4,
				 int  partnerId,
				 int  siteId,		// nsacco 07/02/99
				 int  coPartnerId,
				 int  reqEmailCount,
				 long topSellerInitiatedDate,
				 int  topSellerLevel
				 )
{
	int		companyLen		= 0;
	int		dayPhoneLen		= 0;
	int		nightPhoneLen	= 0;
	int		faxPhoneLen		= 0;

	mpHost			= new char[strlen(pHost) + 1];
	strcpy(mpHost, pHost);

	mpName			= new char[strlen(pName) + 1];
	strcpy(mpName, pName);

	if (pCompany && (companyLen = strlen(pCompany))!= 0)
	{
		mpCompany		= new char[companyLen + 1];
		strcpy(mpCompany, pCompany);
	}
	else
		mpCompany	= NULL;

	mpAddress		= new char[strlen(pAddress) + 1];
	strcpy(mpAddress, pAddress);

	mpCity			= new char[strlen(pCity) + 1];
	strcpy(mpCity, pCity);

	mpState			= new char[strlen(pState) + 1];
	strcpy(mpState, pState);

	mpZip			= new char[strlen(pZip) + 1];
	strcpy(mpZip, pZip);

// petra	mpCountry		= new char[strlen(pCountry) + 1];
	mpCountry = new char[EBAY_MAX_COUNTRY_SIZE];	// petra
	strcpy(mpCountry, pCountry);

	if (pDayPhone && (dayPhoneLen = strlen(pDayPhone)) != 0)
	{
		mpDayPhone			= new char[dayPhoneLen + 1];
		strcpy(mpDayPhone, pDayPhone);
	}
	else
		mpDayPhone			= NULL;

	if (pNightPhone && (nightPhoneLen = strlen(pNightPhone)) != 0)
	{
		mpNightPhone		= new char[nightPhoneLen + 1];
		strcpy(mpNightPhone, pNightPhone);
	}
	else
		mpNightPhone		= NULL;

	if (pFaxPhone && (faxPhoneLen = strlen(pFaxPhone))!= 0)
	{
		mpFaxPhone			= new char[faxPhoneLen + 1];
		strcpy(mpFaxPhone, pFaxPhone);
	}
	else
		mpFaxPhone			= NULL;
	mCreated		= creation;

	mCount			= count;

	mCreditCardOnFile = credit_card_on_file;
	mGoodCredit = good_credit;

	mpGender = new char[strlen(pGender) + 1];
	strcpy(mpGender, pGender);

	mInterests_1	= interests_1;
	mInterests_2	= interests_2;
	mInterests_3	= interests_3;
	mInterests_4	= interests_4;

	mPartnerId = partnerId;
	mSiteId = siteId;	// nsacco 07/02/99
	mCoPartnerId = coPartnerId;
	mReqEmailCount = reqEmailCount;

	mTopSellerInitiatedDate = topSellerInitiatedDate;
	mTopSellerLevel = topSellerLevel;

	mDirty	= false;
	mHasDetail = true;
	return;

}

void clsUser::UpdateUser ()
{
	if (mHasDetail && mDirtyDetail)
		gApp->GetDatabase()->UpdateUserInfo(this);
	if (mDirty)
		gApp->GetDatabase()->UpdateUser(this);
	mDirtyDetail = false;
	mDirty = false;

}

void clsUser::EarliestCreationDate(long createDate)
{
	// only modify if its earlier than what's in the record
	if ((createDate > 0) && (mCreated > createDate))
	{
		mCreated = createDate;
		gApp->GetDatabase()->UpdateUserCreation(this);
	};
};

//
// HasDetail
//
bool clsUser::HasDetail()
{
	if (!mHasDetail)
	{
		if (gApp->GetDatabase()->GetUserInfo(this))
		{											
			mDirtyDetail = false;					
			mHasDetail = true;						
		}
		else
		{
			mDirtyDetail	= false;
			mHasDetail		= false;
		}
	}

	return mHasDetail;
}


//
// IsDirty
//
bool clsUser::IsDirty()
{
	return mDirty;
}

//
// SetDirty
//
void clsUser::SetDirty(bool dirty)
{
	mDirty	= dirty;
	return;
}

//
// IsDetailDirty
//
bool clsUser::IsInfoDirty()
{
	return mDirtyDetail;
}

//
// SetInfoDirty
//
void clsUser::SetInfoDirty(bool dirty)
{
	mDirtyDetail	= dirty;
	return;
}

//
// GetFeedback
//
clsFeedback *clsUser::GetFeedback()
{
	if (mpFeedback == NULL)
		mpFeedback = gApp->GetDatabase()->GetFeedback(mId);

	return mpFeedback;
}

void clsUser::SetFeedback(clsFeedback *pFeedback)
{
	if (mpFeedback)
		delete mpFeedback;

	mpFeedback	= pFeedback;
	return;
}


//
// GetAccount
//
clsAccount *clsUser::GetAccount()
{
	if (!mpAccount)
	{
		mpAccount	= 
			gApp->GetDatabase()->GetAccount(mId);

		// If the user doesn't have an account, then 
		// make one
		if (!mpAccount)
		{
			mpAccount	= new clsAccount;
		}
		mpAccount->SetUser(this);
	}
	return mpAccount;
}

//
// GetListedItems
//
void clsUser::GetListedItems(ItemList *pItemList, int daysSince,
							 bool getMoreStuff /* = false */,
							 ItemListSortEnum SortCode /* = SortItemsByUnknown */)
{
	gApp->GetDatabase()->GetItemsListedByUser(
		gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
		mId,
		daysSince,
		pItemList,
		getMoreStuff,
		SortCode
											 );
	return;
}

//
// GetListedItemsCount
//
int clsUser::GetListedItemsCount()
{
	return gApp->GetDatabase()->GetItemsListedByUserCount(
			gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
			mId
												  );
}


//
// GetBidItems
//
void clsUser::GetBidItems(ItemList *pItemList,
							 int daysSince,
							 bool getMoreStuff /* = false */,
						   ItemListSortEnum SortCode /* = SortItemsByUnknown */,
						   bool withPrivate)
{
	gApp->GetDatabase()->GetItemsBidByUser(
		gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
		mId,
		daysSince,
		pItemList,
		getMoreStuff,
		SortCode,
		withPrivate);
	return;
}


//
// GetBidItemsCount
//
int clsUser::GetBidItemsCount()
{
	return gApp->GetDatabase()->GetItemsBidByUserCount(
			gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
			mId
											 );
}

//
// GetHighBidItems
//
void clsUser::GetHighBidItems(ItemList *pItemList,
							 bool completed,
						   ItemListSortEnum SortCode /* = SortItemsByUnknown */)
{
	gApp->GetDatabase()->GetItemsHighBidByUser(
		gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
		mId,
		completed,
		pItemList,
		SortCode
											 );
	return;
}

void clsUser::MergeUserState(UserStateEnum fromState, UserStateEnum toState)
{
	// only change the state if toState is confirmed and fromstate is not
	// but not suspended
	if ((fromState == UserSuspended) || (toState == UserSuspended))
		SetUserState(UserSuspended);
	else
	{
		// user gets whatever new state is?
		SetUserState(toState);
	}
};


// CopyUserData: currently only used in combining 2 users;
// actually only copies the user's statuses, not user detail
// copies one user's data to this user
// also copies user info.
// to change 
void clsUser::CopyUserData(clsUser *pCopyUser, long changeTime)
{
    clsAccount *pOldAccount, *pNewAccount;
	// uses the earliest creation date
	EarliestCreationDate(pCopyUser->GetCreated());

        // sam, 3/19/98
        // Now that ebay_user, ebay_user_info tables have been updated, update
        // ebay_account_balances table with the old user CC information. Following
        // criterion will be used
        // 1. if credit_card_on_file is set then don't do anything, since user provided
        //    cc details which will eventually end up in DB via updatecc program.
        // 2. if credit_card_on_file is NOT set for this record, then if old CC info. is
        //        available then copy over and update credit_card_on_file flag.
        // if new users credit_card_on_file is not set and old users credit_card_on file is
        // set then update.

        if (!GetCreditCardOnFile() && pCopyUser->GetCreditCardOnFile())
        {
                pOldAccount = pCopyUser->GetAccount();
                pNewAccount = GetAccount();
                pNewAccount->UpdateCCDetails(this->GetId(),
                                             pOldAccount->GetCCIdForUser(),
                                             pOldAccount->GetCCExpiryDate(),
                                             pOldAccount->GetLastCCUpdate());
                if (pOldAccount)
                        delete pOldAccount;
                if (pNewAccount)
                        delete pNewAccount;

        }

	// copying int and bool based values
	// copy ccof or gc from either one that is on
	if (! GetCreditCardOnFile())
		SetCreditCardOnFile(pCopyUser->GetCreditCardOnFile());

	if (! GetGoodCredit())
		SetGoodCredit(pCopyUser->GetGoodCredit());

	// item counts are added
	SetCount(mCount + pCopyUser->GetCount());
	SetUserIdLastModified(changeTime);

	// SetReqEmailCount(mReqEmailCount + pCopyUser->GetCount());
	// req email count is not updated in UpdateUserInfo; only in
	// the following call
	gApp->GetDatabase()->AddReqEmailCount(mId, pCopyUser->GetCount());

	gApp->GetDatabase()->UpdateUserInfo(this);
	gApp->GetDatabase()->UpdateUser(this);

}


// IsConfirmed
bool clsUser::IsConfirmed()
{
	return mUserState == UserConfirmed;
}

// IsSuspended
bool clsUser::IsSuspended()
{
	return mUserState == UserSuspended;
}

// IsUnconfirmed
bool clsUser::IsUnconfirmed()
{
	return mUserState == UserUnconfirmed;
}

// Check whether the user needs to provide credit card for registration
bool clsUser::IsCCVerify()
{
	return mUserState == UserCCVerify;
}

bool clsUser::IsRenamePending(char *pNewUserId)
{
	return gApp->GetDatabase()->IsUserRenamePending(this, pNewUserId);
};

// SetConfirmed
void clsUser::SetConfirmed()
{
	// WARNING BAD!!!
	/*
	if (mUserState == UserSuspended)
		GetFeedback()->RestoreFeedbackLeft();
		*/

	mUserState	= UserConfirmed;
	mDirty		= true;
	return;
}

// SetSuspended
void clsUser::SetSuspended()
{
	// Don't revoid if they're already suspended
	// WARNING BAD!!!
	/*
	if (mUserState != UserSuspended)
		GetFeedback()->VoidFeedbackLeft();
		*/

	mUserState	= UserSuspended;
	mDirty		= true;
	return;
}

// SetUnconfirmed
void clsUser::SetUnconfirmed()
{
	mUserState	= UserUnconfirmed;
	mDirty		= true;
	return;
}

// SetCCVerify
void clsUser::SetCCVerify()
{
	mUserState	= UserCCVerify;
	mDirty		= true;
	return;
}

bool clsUser::IsInMaintenance()
{
	return mUserState == UserInMaintenance;
}

void clsUser::SetInMaintenance()
{
	mUserState	= UserInMaintenance;
	mDirty		= true;
	return;
}

bool clsUser::IsDeleted()
{
	return mUserState == UserDeleted;
}

void clsUser::SetDeleted()
{
	mUserState	= UserDeleted;
	mDirty		= true;
	return;
}

// GetUserState, SetUserState
UserStateEnum clsUser::GetUserState()
{
	return mUserState;
}

void clsUser::SetUserState(UserStateEnum state)
{
		// WARNING - THIS CAUSES PROBLEMS!!!
	/*
	if (state == UserSuspended &&
		mUserState != UserSuspended)
		GetFeedback()->VoidFeedbackLeft();
	else if (state != UserSuspended &&
		mUserState == UserSuspended)
		GetFeedback()->RestoreFeedbackLeft();
*/
	mUserState	= state;
	mDirty		= true;
	return;
}

// petra get name from country id, not user info
char *clsUser::GetCountry ()
{
	gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCountries()->GetCountryName (mCountryId, mpCountry);	// petra
	return mpCountry;
}


INT_METHODS(MarketPlace);			// User's marketplace
INT_METHODS(Id);						// Numeric Id
STRING_METHODS(UserId);				// User Id
STRING_METHODS(Password);			// Password
STRING_METHODS(Salt);				// Salt
LONG_METHODS(LastModified);			// Last Modified, set value is not used
INT_METHODS_WITH_UPDATE(UserFlags); // User choices
INT_METHODS(UVRating);				// numerical user verification rating
INT_METHODS(UVDetail);				// flags for user verification detail record
INT_METHODS(CountryId);				// Country id
DSTRING_METHODS(Host);				// Host 
DSTRING_METHODS(Name);				// Name
DSTRING_METHODS(Company);			// Company
DSTRING_METHODS(Address);			// Address
DSTRING_METHODS(City);				// City
DSTRING_METHODS(State);				// State
DSTRING_METHODS(Zip);				// Zip
// petra DSTRING_METHODS(Country);			// Country
DSTRING_METHODS(DayPhone);			// Phone
DSTRING_METHODS(NightPhone);		// Phone
DSTRING_METHODS(FaxPhone);			// Phone
DLONG_METHODS(Created);				// Creation/Registration
DINT_METHODS(Count);
DBOOL_METHODS(CreditCardOnFile);
DBOOL_METHODS(GoodCredit);
DSTRING_METHODS(Gender);
DINT_METHODS(Interests_1);
DINT_METHODS(Interests_2);
DINT_METHODS(Interests_3);
DINT_METHODS(Interests_4);
DINT_METHODS(PartnerId);
// nsacco 07/02/99
DINT_METHODS(SiteId);
DINT_METHODS(CoPartnerId);
LONG_METHODS(UserIdLastModified);
DINT_METHODS(ReqEmailCount);
DLONG_METHODS(TopSellerInitiatedDate);
// DINT_METHODS(TopSellerLevel);

int clsUser::GetDailyItemCount()
{
//	if (!mDailyItemCountRetrieved)
//	{
//		gApp->GetDatabase()->GetUserItemCount(this);
//		mDailyItemCountDelta		= 0;
//		mDailyItemCountDirty		= false;
//		mDailyItemCountRetrieved	= true;
//	}
	return GetCount();
}

void clsUser::SetDailyItemCount(int count)
{
//	mDailyItemCount	= count;
	SetCount(count);
}


void clsUser::AdjustDailyItemCount(int delta)
{
//	mDailyItemCountDelta	+= delta;
//	mDailyItemCountDirty	= true;
	int count = GetCount();
	count = count + delta;
	SetInfoDirty(true);
	return;
}


//
// Determines if the UV (User Verification) rating is acceptable or not.
//
bool clsUser::IsValidUVRating()
{
	bool retVal;
	
	// Before 3/15/99, UV Rating is valid for all users.
	if (clsUtilities::CompareTimeToGivenDate(time(0),3, 15, 99, 0, 0, 0) < 0)
	{
		return true;
	}

	// We are beyond 3/15/99
	if ((mUVRating >= 0) || 
		(mUVRating == clsUserVerificationServices::UV_RATING_NOT_CALCULATED) || 
		(mUVRating == clsUserVerificationServices::UV_RATING_FOR_COUNTRY_NOT_AVAILABLE)) 
	{

		retVal = true;
	}
	else
	{
		retVal = false;
	}

	return retVal;
}  /* IsValidUVRating */


bool clsUser::HasGoodCredit()
{
//	if (!mCreditInfoRetrieved)
//	{
//		gApp->GetDatabase()->GetUserCreditInfo(this);
//		mCreditInfoRetrieved	= true;
//	}
	return GetGoodCredit();
}

bool clsUser::HasCreditCardOnFile()
{
//	if (!mCreditInfoRetrieved)
//	{
//		gApp->GetDatabase()->GetUserCreditInfo(this);
//		mCreditInfoRetrieved	= true;
//	}
	return GetCreditCardOnFile();
}

// goes to the database directly!
bool clsUser::HasAdmin(AdminFunctionEnum adcode)
{
	// 1 is for Category admin
	return gApp->GetDatabase()->GetUserAdminInfo(this,adcode);
}

void clsUser::SetHasAdmin(AdminFunctionEnum adcode, bool doThey)
{
	gApp->GetDatabase()->SetUserAdminInfo(this, doThey,adcode);
	return;
}

void clsUser::SetHasGoodCredit(bool doThey)
{
//	mHasGoodCredit			= doThey;
//	mCreditInfoRetrieved	= true;
//	mCreditInfoDirty		= true;
	mGoodCredit = doThey;
	SetInfoDirty(true);
	return;
}

void clsUser::SetHasCreditCardOnFile(bool doThey)
{
//	mHasCreditCardOnFile	= doThey;
//	mCreditInfoRetrieved	= true;
//	mCreditInfoDirty		= true;
	mCreditCardOnFile = doThey;
	SetInfoDirty(true);
	return;
}

// 
// TestPass checks to see if the passed password
// matches the user's. Assumes that, of course,
// the user's password and salt have been loaded
bool clsUser::TestPass(char *pPass)
{
//	char	*pEncryptedPass;
//	int		saltLen;
//	char	*pTerminatedSalt;
//	bool	isGood;

//	if (strcmp(pPass, 
//			   gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSpecialPassword()) == 0)
//		return true;

//	saltLen	= strlen(mpSalt);

//	pTerminatedSalt	= new char[saltLen + 2];
//	strcpy(pTerminatedSalt, mpSalt);
//	if (*(pTerminatedSalt + saltLen - 1) == '\n')
//		*(pTerminatedSalt + saltLen - 1) = '\0';

//	pEncryptedPass	= crypt(pPass, pTerminatedSalt);

//	if (strcmp(mpPassword, pEncryptedPass) == 0)
//		isGood	= true;
//	else
//		isGood	= false;

//	free(pEncryptedPass);
//	delete pTerminatedSalt;

//	return isGood;
//	if(!strcmp(pPass,mpPassword)) {return true;}
	return IsGoodPassword(pPass, mpPassword, mpSalt);
}

// abstracted password check; checks if password pPass is actually same
// as pUserPass.
// pPass is what user entered; pUserPass is actual user password from the db,
// pSalt is actual user's salt from the db.
bool clsUser::IsGoodPassword(char *pPass, char *pUserPass, char *pSalt)
{
	char	*pEncryptedPass;
	int		saltLen;
	char	*pTerminatedSalt;
	bool	isGood = false;
	int i;

	for (i=0; i<NUM_SPECIAL_PASS; i++)
	{
		if (strcmp(pPass, 
				   gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSpecialPassword(i)) == 0)
			return true;
	}

	saltLen = strlen(pSalt);
	pTerminatedSalt = new char[saltLen + 2];
	strcpy(pTerminatedSalt, pSalt);
	if (*(pTerminatedSalt + saltLen - 1) == '\n')
		*(pTerminatedSalt + saltLen - 1) = '\0';

	pEncryptedPass	= crypt(pPass, pTerminatedSalt);

	if (strcmp(pUserPass, pEncryptedPass) == 0)
		isGood	= true;
	else
		isGood	= false;

	free(pEncryptedPass);
	delete pTerminatedSalt;

	return isGood;
};

// check what access level (1-n) user is granted using the password pPass.
// pPass can be either what the user entered or the user's encrypted password
int clsUser::GetAccessLevel(char *pPass)
{
	int		i;
	char	cSalt[65];
	char	*pEncryptedPass;
	char	lowerCasePass[256];

	// make the pass case-insensitive
	strcpy(lowerCasePass, pPass);
	clsUtilities::StringLower(lowerCasePass);

	// check against the special passwords, starting most ambitiously with the highest level access
	for (i=NUM_SPECIAL_PASS; i>=1; i--)
	{
		if (strcmp(lowerCasePass, gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSpecialPassword(i-1)) == 0)
			return i;
	}

	// none of the special passwords worked, so now let's check the user password


	// 1st let's try assuming that the pPass is already encrypted
	if (strcmp(mpPassword, pPass) == 0) return 2;					// with salt
	if (strcmp(this->GetPasswordNoSalt(), pPass) == 0) return 2;	// without salt

	// 2nd let's try assuming that the pPass is not already encrypted

	// make the encrypted one
	strcpy(cSalt, this->GetSalt());
	pEncryptedPass = crypt(lowerCasePass,cSalt);

	if (strcmp(mpPassword, pEncryptedPass) == 0)
	{
		free(pEncryptedPass);
		return 2;
	}

	// ok, nothing worked so return 0 (no access)
	free(pEncryptedPass);
	return 0;
}

// Returns the encrypted password with the salt stripped out.
//  Caller should NOT free the returned char*, as it just points
//  to the member variable, mpPassword
char* clsUser::GetPasswordNoSalt()
{
	char* cEncryptedPasswordNoSalt;
	char* cTemp;
	int j;

	// get the encrypted password, including the salt
	cEncryptedPasswordNoSalt = this->GetPassword();

	// skip over the first 3 $'s (encrypted passwords in the database are
	//  of the form "$1$14293$lsJzYvguOcUNhFl8XcWNH." if for some reason
	//  there are less than 3 $'s, it will skip over just those that exist.
	for (j=0; j<3; j++)
	{
		cTemp = strchr(cEncryptedPasswordNoSalt, '$');
		if (cTemp) cEncryptedPasswordNoSalt = cTemp+1;
	}

	return cEncryptedPasswordNoSalt;

}

// test pending code against database entry 
bool clsUser::TestPendingPass(char *pNewUserId, char *pPass)
{
	// kludge to fix multiple entries by same user
	// check if the record exists with the correct salt/pPass...

//	clsUserRenamePendingCode *pCode;
	bool isGood;

	if (strcmp(pPass, 
			   gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSpecialPassword()) == 0)
		return true;

	// Get user's salt and password from rename pending table
	isGood = gApp->GetDatabase()->GetUserRenamePendingCode(this, pNewUserId, pPass);
	
	return isGood;
}


// inserts rename data into database
void clsUser::SetRenamePending(char *pNewUserId,
							char *pPass,
							char *pSalt)
{
	gApp->GetDatabase()->SetUserRenamePending(this, pNewUserId, pPass, pSalt);
	return;
};

/* should not be used
void clsUser::Rename(char *pNewEmail)
{
	// need to put this in pl/sql
	char		comment[256];
	char		*pHostAddr;
	clsFeedback *pFeedback;

	// begin transaction
	gApp->GetDatabase()->Begin();
	
//	sprintf(comment, "Feedback for user %s has been transferred to %s", 
//			mpUserId, pNewEmail);
//	pHostAddr = "internal";

			// if old user doesn't have no feedback items, it will return 
			// a feedback object anyway

//	pFeedback = GetFeedback();
//	pFeedback->AddNeutralFeedback(GetId(),
//								  pHostAddr,
//								  comment);

	// ensure no cycles
//	gApp->GetDatabase()->DeleteRenamedUser(mpUserId);

//	gApp->GetDatabase()->RenameRenamedUser(mpUserId, pNewUserId);

	// add to ebay_renamed_users table OLD userid and OLD id
//	gApp->GetDatabase()->AddRenamedUser(mpUserId, pNewUserId);

	ChangeEmail(pNewEmail);
	
	// delete from rename_pending table
	gApp->GetDatabase()->DeleteUserRenamePending(mpUserId);

	// changes userid in ebay_user and puts a record in rename_user;
	// need to handle multiple renames? or renaming back to original?
	// rename user doesn't handle circular renames.
//	SetUserId(pNewUserId);
//	SetEmail(pNewUserId);
//	UpdateUser();
//	delete pFeedback;

	gApp->GetDatabase()->End();

};
*/
		// get and set attribute values for user, not actually stored in
		// user; we get and set it from the database directly.
void clsUser::GetAttributeValue(int attribute_id,
							  bool *pGotBoolResponse,
							  bool *pBoolResponse,
							  bool *pGotNumberResponse,
							  float *pNumberResponse,
							  bool *pGotTextResponse,
							  char **ppTextResponse)
{
	gApp->GetDatabase()->GetUserAttribute(mId, attribute_id,
		pGotBoolResponse, pBoolResponse, pGotNumberResponse, pNumberResponse,
		pGotTextResponse, ppTextResponse);
	return;	
};

void clsUser::SetAttributeValue(int attribute_id,
								   bool value)
{
	gApp->GetDatabase()->SetUserAttributeValue(mId, attribute_id, value);
	return;	
};

void clsUser::SetAttributeValue(int attribute_id,
								   float value)
{
	gApp->GetDatabase()->SetUserAttributeValue(mId, attribute_id, value);
	return;	
};

void clsUser::SetAttributeValue(int attribute_id,
								   int value)
{
	gApp->GetDatabase()->SetUserAttributeValue(mId, attribute_id, value);
	return;	
};

void clsUser::SetAttributeValue(int attribute_id,
								   char *pValue)
{
	gApp->GetDatabase()->SetUserAttributeValue(mId, attribute_id, pValue);
	return;	
};


//
// ChangeUserId
//	
//	Does all the chores associated with changing a userid
//
bool clsUser::ChangeUserId(char *pNewUserId)
{
	char		oldUserId[256];
	bool		userIdChanged;
	time_t		changeTime;

	// check if we NEED to change userid at all
	if (strcmp(GetUserId(), pNewUserId) == 0)
		return true;
	else
	{
		changeTime	= time(0);

		// begin transaction
		gApp->GetDatabase()->Begin();

		// Remember the old UserId
		strcpy(oldUserId, GetUserId());

		// First, let's get the database to change the userid,
		// if it can
		userIdChanged = gApp->GetDatabase()->ChangeUserId(GetMarketPlace(),
				  										  GetId(),
														  pNewUserId);

		if (!userIdChanged)
		{
			gApp->GetDatabase()->End();
			return false;
		};

		SetUserId(pNewUserId);
		SetUserIdLastModified(changeTime);
		UpdateUser();

		// The "old" useerid is now an alias, so let's make it so
		gApp->GetDatabase()->AddUserAlias(GetMarketPlace(),
				  						  GetId(),
										  oldUserId,
										  "host",
										  changeTime);

		gApp->GetDatabase()->End();
	}
	return true;
}


/* NOT THIS WAY - USE USERID_LAST_MODIFIED
//
// UserIdChangedInInterval
//	
//	Does all the chores associated with changing a userid
//
bool clsUser::UserIdChangedInInterval(int interval)
{
	bool	userIdChanged = false;


	// First, let's get the database to change the userid,
	// if it can

	userIdChanged = gApp->GetDatabase()->UserIdChangedInInterval(GetMarketPlace(),
				  												 GetId(),
																 interval);

	return	userIdChanged;

}
	*/
	
bool clsUser::UserIdRecentlyChanged()
{
	return gApp->GetMarketPlaces()->GetCurrentMarketPlace()->UserIdRecentlyChanged(GetUserIdLastModified());
}


bool clsUser::CanUserChangeUserId()
{
	return gApp->GetMarketPlaces()->GetCurrentMarketPlace()->CanUserChangeUserId(GetUserIdLastModified());
}



//
// ChangeEmail
//	
//	Does all the chores associated with changing an email address
//  
//	*** NOTE ***
//	Should this just be in SetEmail?
//  Not in transaction; make sure calling function enclose it in a transaction!
//	*** NOTE ***
//
void clsUser::ChangeEmail(char *pNewEmail)
{
	char		oldEmail[256];
	time_t		changeTime;

	// check if we NEED to change email at all
	if (strcmp(GetEmail(), pNewEmail) == 0)
		return;
	else
	{
		// start transaction
		gApp->GetDatabase()->Begin();

		changeTime	= time(0);

		// Remember the old UserId
		strcpy(oldEmail, GetEmail());

		// if user id is the same as the email, change the user id as well.
		if (strcmp(GetUserId(), GetEmail()) == 0)
		{
            SetUserId(pNewEmail);
			SetUserIdLastModified(changeTime);
		}

		// change email
		SetEmail(pNewEmail);
		gApp->GetDatabase()->UpdateUser(this);
		gApp->GetDatabase()->UpdateUserInfo(this);

		// The "old" email is now an alias, so let's make it so
		gApp->GetDatabase()->AddEmailAlias(GetMarketPlace(),
				  						   GetId(),
										   oldEmail,
										   "host",
										   changeTime);

		// delete from rename_pending table
		gApp->GetDatabase()->DeleteUserRenamePending(mId);

		// commit transaction
		gApp->GetDatabase()->End();
	}
	return;
}

void clsUser::GetAliasHistory(UserAliasHistoryVector *pVHistory)
{
	gApp->GetDatabase()->GetAliasHistory(GetMarketPlace(),
										 GetId(),
										 pVHistory);
	return;
}

char *clsUser::GetEmail()
{
	if (mpEmail == NULL)
	{
		if (strchr(mpUserId, '@'))
		{
			mpEmail = new char[strlen(mpUserId)+1];
			strcpy(mpEmail, mpUserId);
		}
		else
		{
			mpEmail = new char [1];
			*mpEmail = 0;
		}
	}

	return mpEmail;
}

void clsUser::SetEmail(char *pNew)
{
	if  (!mHasDetail && mUserState != UserGhost)
	{
		gApp->GetDatabase()->GetUserInfo(this);
		mHasDetail	= true;
	}

	if (mpEmail)
		delete mpEmail;

	if (pNew != NULL)
	{
		mpEmail = new char[strlen(pNew) + 1];
		strcpy(mpEmail, pNew);
	}
	else
		mpEmail = NULL;

	mDirty			= true;

	return;
}

void clsUser::AddReqEmailCount(int Delta)
{
	gApp->GetDatabase()->AddReqEmailCount(mId, Delta);
	return;
}

void clsUser::ResetReqEmailCount()
{
	gApp->GetDatabase()->ResetReqEmailCount(mId);
	return;
}

void clsUser::ResetSellerList()
{
	gApp->GetDatabase()->InvalidateSellerList(GetMarketPlace(),mId);
	return;
}

void clsUser::ResetBidderList()
{
	gApp->GetDatabase()->InvalidateBidderList(GetMarketPlace(),mId);
	return;
}

bool clsUser::UserHasActivities()
{
	// This is a vector of the items
	ItemList					itemList;
	ItemList::iterator i;
	bool didSomething = false;
	
	// see if user has bid on anything in the past
	GetBidItems(&itemList, DAYS_ACTIVE);	

	if (itemList.size() > 0)
		didSomething = true;
	else
	{
		// ? do I need to clean up itemList even though size is 0?

		// see if user has anything listed in the past
		GetListedItems(&itemList, DAYS_ACTIVE);

		if (itemList.size() > 0)
			didSomething = true;
	}

	// Clean up
	if (itemList.size() > 0)
	{
		for (i = itemList.begin();
			 i != itemList.end();
			 i++)
		{
			delete	(*i).mpItem;
		}

		itemList.erase(itemList.begin(), 
					   itemList.end());
	}
	return didSomething;
}

// abstracted password check; checks if password pPass is actually same
// as pUserPass.
// pPass is what user entered; pUserPass is actual user password from the db,
// pSalt is actual user's salt from the db.
bool clsUser::TestCryptedPassword(char *pEncryptedPass)
{	
	bool	isGood = false;
	int i;
	for (i=0; i<NUM_SPECIAL_PASS; i++)
	{
		if (strcmp(pEncryptedPass, 
				   gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSpecialPassword(i)) == 0)
			return true;
	}	
	if (strcmp(mpPassword, pEncryptedPass) == 0)
		isGood	= true;
	else
		isGood	= false;
	return isGood;
};

void clsUser::ChangePassword(char *pNewPass)
{
	int		salt;
	char	cSalt[16];
	char	*pCryptedPassword;

	salt				= ((int)rand());
	sprintf(cSalt, "%d", salt);
	pCryptedPassword	= crypt(pNewPass, cSalt);
	// 
	// Set them!
	//
	SetPassword(pCryptedPassword);
	SetSalt(cSalt);
	// And update
	UpdateUser();
	free(pCryptedPassword);
}

//
// Functions to access the bit flags for the user.
//

// 
// Retrieve a single user flag.
//
bool clsUser::GetOneUserFlag(unsigned int bit) 
{
	int flags = GetUserFlags();
	return ( (flags & bit) > 0);
}

//
// Set one or more user flags, and indicate whether to toggle them
// on or off (the other flags are untouched). 
// You can logical-or the bit masks together in mask.
//
int clsUser::SetSomeUserFlags(bool on, int mask)
{
	long flags;
	long oldFlags = GetUserFlags();

	if (on)
		flags = oldFlags | mask;
	else 
		flags = oldFlags & ~mask;

	SetUserFlags(flags);

	return oldFlags;
}

bool clsUser::SendChangesToAgreement() 
{
	return GetOneUserFlag(UserFlagChangesToAgreement);
}

bool clsUser::SendChangesToPrivacy() 
{
	return GetOneUserFlag(UserFlagChangesToPrivacy);
}

bool clsUser::SendTakePartInSurveys() 
{
	return GetOneUserFlag(UserFlagTakePartInSurveys);
}


bool clsUser::SendSpecialOffer()
{
	return GetOneUserFlag(UserFlagSpecialOffer);
}

bool clsUser::SendEventPromotion()
{
	return GetOneUserFlag(UserFlagEventPromotion);
}
bool clsUser::SendNewsletter() 
{
	return GetOneUserFlag(UserFlagNewsletter);
}

bool clsUser::SendEndofAuction() 
{
	return !GetOneUserFlag(UserFlagEndofAuction);
}

bool clsUser::SendBid() 
{
	return !GetOneUserFlag(UserFlagBid);
}
bool clsUser::SendOutBid() 
{
	return !GetOneUserFlag(UserFlagOutBid);
}
bool clsUser::SendList() 
{
	return !GetOneUserFlag(UserFlagList);
}
bool clsUser::SendDailyStatus() 
{
	return !GetOneUserFlag(UserFlagDailyStatus);
}

//
// Easy way for application code to quickly check whether the user
// has accepted the new user agreement.
//
bool clsUser::AcceptedUserAgreement() 
{
	return GetOneUserFlag(UserFlagSignedAgreement);
}

bool clsUser::AcceptedPoliceBadgeAgreement() 
{
	return GetOneUserFlag(UserFlagSignedPBAgreement);	// kaz: 4/7/99: added support for Police Badge T&C
}

int clsUser::CanReceiveInfo(const char *pHost)
{
	return gApp->GetDatabase()->CanReceiveInfo(GetId(),
		pHost);
}

void clsUser::ResetReqUserCount()
{
	gApp->GetDatabase()->ResetCanReceiveInfo(GetId());
}

bool clsUser::IsVerifiedAsAdult()
{
	return GetOneUserFlag(UserFlagIsVerifiedAsAdult) || IsVerifiedAsAnon() || HasCreditCardOnFile() || strstr(GetEmail(), "@ebay.com");
}

void clsUser::VerifyAsAdult(bool on)
{
	if (GetOneUserFlag(UserFlagIsVerifiedAsAdult) != on)
	{
		SetSomeUserFlags(on, UserFlagIsVerifiedAsAdult);
	}
}

bool clsUser::IsVerifiedAsAnon()
{
	return GetOneUserFlag(UserFlagIsVerifiedAnon);
}

void clsUser::VerifyAsAnon(bool on)
{
	if (GetOneUserFlag(UserFlagIsVerifiedAnon) != on)
	{
		SetSomeUserFlags(on, UserFlagIsVerifiedAnon);
	}
}


bool clsUser::HasANote()
{
	return GetOneUserFlag(UserFlasHasANote);
}


void clsUser::SetHasANote(bool on)
{
	if (GetOneUserFlag(UserFlasHasANote) != on)
	{
		SetSomeUserFlags(on, UserFlasHasANote);
	}
}

//
// Functions to access the bit flags for the user.
//

// 
// Get all of the user flags at once.
//
int clsUser::GetAllUserFlags()
{
	return gApp->GetDatabase()->GetUserFlags(GetId());
}

//
// Set all of the user flags at once.
//
int clsUser::SetAllUserFlags(int flags)
{
	int oldFlags = GetAllUserFlags();
	gApp->GetDatabase()->SetUserFlags(GetId(), flags);
	return oldFlags;
}

//
// About Me flags.
//
bool clsUser::HasAboutMePage()
{
	return GetOneUserFlag(UserFlagHasAboutMePage);
}

void clsUser::SetAboutMePage(bool on /* = true */)
{
    // Only set the flag if it's not already equal --
    // avoids an unecessary database call when nothing
    // has changed.
    if (HasAboutMePage() != on)
    {
        SetSomeUserFlags(on, UserFlagHasAboutMePage);
        UpdateUser();
    }
}

bool clsUser::OkayToViewAdult()
{
	switch (mCountryId)
	{
	case Country_UK:
		return false;

	case Country_None:
	case Country_US:
	case Country_CA:
	// nsacco 06/09/99
	// TODO is this correct?
	case Country_AU:
	default:
		return true;
	}
}

int clsUser::GetDeadbeatScore()
{
	return GetDeadbeat()->GetDeadbeatScore();
}

bool clsUser::IsDeadbeat()
{
	return GetDeadbeat()->UserIsDeadbeat();
}

clsDeadbeat *clsUser::GetDeadbeat()
{
	if (mpDeadbeat == NULL)
	{
		mpDeadbeat = gApp->GetDatabase()->GetDeadbeat(mId);
	}

	return mpDeadbeat;
}

//
//check whether user participated a survey
//
bool clsUser::IsParticipatedSurvey(int survey_id)
{
	return gApp->GetDatabase()->IsParticipatedSurvey(survey_id, mId);
}

void clsUser::AddUserToSurveyRecord(int survey_id)
{
	gApp->GetDatabase()->AddUserToSurveyRecord(survey_id, mId);
}


//
// Top Seller
//

//
// SetTopSellerLevel - sets the level of the top seller, also set the initial time/date of their induction if they are new
//
void clsUser::SetTopSellerLevel(TopSellerLevelEnum level)
{
	// refresh the user object
	if  (!mHasDetail)								
	{												
		gApp->GetDatabase()->GetUserInfo(this);		
		mHasDetail	= true;							
	}												

	// set the new level
	mTopSellerLevel = level;

	// check to see if date has been set, if not, this user is a newbie and we'll set the date now
	if(GetTopSellerInitiatedDate() == 0) {
		// they're new, so set the time
		SetTopSellerInitiatedDate(time(0));
	}

	// mark it dirty
	mDirty = true;
	mDirtyDetail = true;				
}

int clsUser::GetTopSellerLevel()
{ 
	if (!mHasDetail)								
	{												
		if (gApp->GetDatabase()->GetUserInfo(this))	
		{											
			mDirtyDetail = false;					
			mHasDetail = true;						
		}											
	}												

	return mTopSellerLevel;
}

bool clsUser::IsTopSeller()
{
	return (GetTopSellerLevel() > 0) ? true : false;
}

//
// (end of) Top Seller
//


bool clsUser::HasABlockedItem()
{
	return GetOneUserFlag(UserFlagHasABlockedItem);
}


void clsUser::SetHasABlockedItem(bool on)
{
	if (GetOneUserFlag(UserFlagHasABlockedItem) != on)
	{
		SetSomeUserFlags(on, UserFlagHasABlockedItem);
	}
}

