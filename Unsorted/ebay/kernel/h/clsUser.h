/*	$Id: clsUser.h,v 1.14.2.1.100.2 1999/08/06 02:26:59 nsacco Exp $	*/
//
//	File:		clsUser.h
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
//				- 06/09/97 tini     - modified to handle 2 user tables
//				- 06/17/97 tini     - changed lastModifed to be set in the
//									  database call to system date/time.
//				- 06/20/97 tini		- added functions to handle renaming users.
//				- 02/23/99 anoop    - added IsValidUVRating method to get user's UV rating.
//				- 09/25/98 mila		- added functions GetDeadbeatScore() and
//									  IsDeadbeat()
//				- 10/01/98 mila		- added GetDeadbeatItems()
//				- 12/09/98 mila		- deleted GetDeadbeatItems(); deadbeat items
//									  are now obtained via clsDeadbeat; added new
//									  instance variable mpDeadbeat.
//				- 04/07/99 kaz		- Support for Police Badge T&C
//				- 05/06/99 kaz		- Comments for user flags
//				- 05/11/99 kaz		- Merge user flags from other builds
//				- 06/29/99 petra	- add GetCountry (to give it something else than the default behavior)
//				- 07/02/99 nsacco	- Added site id and co partner id
//				- 07/02/99 nsacco	- Added site id and co partner id
//				- 07/06/99 nsacco	- Added site id and co partner id to constructor
//

#ifndef CLSUSER_INCLUDED

#include "eBayTypes.h"
#include "clsFeedback.h"
#include "time.h"

#include "clsItem.h"			// For ItemVector
#include "clsItems.h"			// for sorting items
#include "clsDeadbeat.h"		// for DeadbeatVector
#include "clsDeadbeatItem.h"	// for DeadbeatItemVector

// Defines
#define	EBAY_USERID_CHANGE_DAYS				30
#define EBAY_USERID_EMBARGO_OLD_USERID_DAYS	30
#define EBAY_USERID_EMBARGO_OLD_EMAIL_DAYS	30
#define EBAY_EMAILS_REQUEST_PER_DAY			5000
#define DAYS_ACTIVE							2 

#define SECS_PER_DAY 86400



#define UserFlagSignedAgreement		0x00000001
#define UserFlagChangesToAgreement	0x00000002	// e-mail changes to the user agreement
#define UserFlagChangesToPrivacy	0x00000004	// e-mail changes to Privacy policy
#define UserFlagTakePartInSurveys	0x00000008

#define UserFlagRegisteredSoho      0x00000010
#define UserFlagEventPromotion      0x00000020	// e-mail me marketing spam
#define UserFlagNewsletter			0x00000040	// e-mail me the newsletter
#define UserFlagIsVerifiedAsAdult	0x00000080

#define UserFlagIsVerifiedAnon		0x00000100
#define UserFlagHasAboutMePage		0x00000200
#define UserFlasHasANote			0x00000400
#define	UserFlagPersonalShopper		0x00000800

#define	UserFlagBid					0x00001000	// do NOT e-mail me Bid Notices
#define	UserFlagOutBid				0x00002000	// do NOT e-mail me Out bid notices
#define	UserFlagList				0x00004000	// do NOT e-mail me Listing an Item notices
#define	UserFlagDailyStatus			0x00008000	// do NOT e-mail me daily status

#define UserFlagSignedPBAgreement	0x00010000	// kaz: 4/7/99: Support for Police Badge T&C
#define	UserFlagHasABlockedItem		0x00020000	// user has tried to list item(s) for
												// sale that were subsequently blocked
												// as part of legal buddies project
#define	UserFlagEndofAuction		0x00040000	// do NOT e-mail me EOA: someone please rename this
#define UserFlagSpecialOffer        0x00080000	// e-mail me special offers

/*	
typedef enum
{
	UserFlagSignedAgreement		= 0x0001,
	UserFlagChangesToAgreement	= 0x0002,
	UserFlagChangesToPrivacy	= 0x0004,
	UserFlagTakePartInSurveys	= 0x0008,
	UserFlagIsVerifiedAsAdult	= 0x0080, // Many flags are already used.
	UserFlagIsVerifiedAnon		= 0x0100,
	UserFlagHasAboutMePage		= 0x0200,
	UserFlasHasANote			= 0x0400,  // At least one eNote filed
	UserFlagPersonalShopper		= 0x0800	// User has used PS
} UserFlag; 
*/


// TopSeller (ne PowerSeller) stuff
typedef enum
{
	TopSellerLevel_1 = 1,
	TopSellerLevel_2 = 2,
	TopSellerLevel_3 = 3,
	TopSellerLevel_1_with_Agreement = 11,
	TopSellerLevel_2_with_Agreement = 22,
	TopSellerLevel_3_with_Agreement = 33
	
} TopSellerLevelEnum;

// bit fields for User Verification stuff
typedef enum
{
	UVDetailPhoneNumberLength		= 0x0001,	// bit 1
	UVDetailValidAreaCode			= 0x0002,	// bit 2
	UVDetailValidZipCode			= 0x0004,	// bit 3
	UVDetailValidCity				= 0x0008,	// bit 4
	UVDetailZipMatchesState			= 0x0010,	// bit 5
	UVDetailAreaCodeMatchesState	= 0x0020,	// bit 6
	UVDetailZipCloseToAreaCode		= 0x0040,	// bit 7
	UVDetailZipMatchesCity			= 0x0080,	// bit 8
	UVDetailAreaCodeMatchesCity		= 0x0100,	// bit 9
	UVDetailCityMatchesState		= 0x0200,	// bit 10
	UVDetailPhonePrefixNot555		= 0x0400	// bit 11
} UVDetail; 


// Some convienent macros
#define STRING_VARIABLE(name)		\
private:							\
	char	*mp##name;				\
public:								\
	char	*Get##name();			\
	void	Set##name(char *pNew);	

#define INT_VARIABLE(name)			\
private:							\
	int		m##name;				\
public:								\
	int		Get##name();			\
	void	Set##name(int new_value);

#define BOOL_VARIABLE(name)			\
private:							\
	bool	m##name;				\
public:								\
	bool	Get##name();			\
	void	Set##name(bool new_value);

#define LONG_VARIABLE(name)			\
private:							\
	long	m##name;				\
public:								\
	long	Get##name();			\
	void	Set##name(long new_value);


// Class forward
class clsFeedback;
class clsAccount;

// to store user's password and salt for renames
class clsUserRenamePendingCode
{
public:
	char *mpTempSalt;
	char *mpTempPass;

	clsUserRenamePendingCode(char *pSalt,
							char *pPass);

	~clsUserRenamePendingCode();
		
};

//
// Alias Type
//
typedef enum
{
	UserIdAlias	= 1,
	EMailAlias	= 2
} UserAliasTypeEnum;

//
// Alias history
//
//	Contains information about when an user's UserId or Email
//	was changed FROM the mAlias value. Even though userid are
//	limited to 32 bytes, emails can be longs, so, we have a 
//	long field.
//
class clsUserAliasHistory
{
	public:
		UserAliasTypeEnum		mType;
		char					mAlias[256];
		time_t					mModified;
		char					mHost[256];

		clsUserAliasHistory(UserAliasTypeEnum type,
							char *pAlias,
							time_t modified,
							char *pHost)
		{
			mType		= type;
			strcpy(mAlias, pAlias);
			mModified	= modified;
			strcpy(mHost, pHost);
			return;
		}

		~clsUserAliasHistory()
		{
			;
		}
};

typedef vector<clsUserAliasHistory *>	UserAliasHistoryVector;

//
//UserIdAliasHistory 
//
class clsUserIdAliasHistory 
{
	public:
		int					mId;
		time_t				mModified;
		
		clsUserIdAliasHistory(int id,
							time_t modified)
							
		{
		
			mId = id;
			mModified	= modified;
			
		}

		~clsUserIdAliasHistory()
		{
			;
		}
};
typedef vector<clsUserIdAliasHistory *>	UserIdAliasHistoryVector;


class clsUser
{
	public:
		// Vanilla CTOR and DTOR, as required
		clsUser();

		// changed to include ebay_user_info table also
		~clsUser();


		//
		// Stupid Constructor
		//
		clsUser(int id);

		// Short constructor - from ebay_users table only
		// nsacco 07/06/99 added site id and co partner id
		clsUser(int	 marketplace,
				int  id,
				char *pUserId,
				char *pEmail,
				UserStateEnum state,
				char *pPassword,
				char *pSalt,
				long lastModified,
				long useridLastChanged,
				int user_flags,
				int country_id,
				int UVRating,
				int UVDetail,
				int siteId = SITE_EBAY_MAIN,
				int coPartnerId = PARTNER_EBAY
				);

		// 
		// Fancy Constructor - with ebay_users and ebay_user_info data
		//
		// *** NOTE ***
		// I've had problems with the STL and putting
		// pointers into objects, which I didn't resolve
		// at the time. But, allocating storage for the
		// potential maximum size of all these strings
		// would suck big time, so I guess I'll just 
		// have to fix it..
		// *** NOTE ***
		clsUser(int	 marketplace,
				int  id,
				char *pUserId,
				UserStateEnum state,
				char *pPassword,
				char *pSalt,
				long lastModified,
				long useridLastChanged,
				int  user_flags,
				int  country_id,
				int UVRating,
				int UVDetail,
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
				int	 count,
				bool creditCardOnFile,
				bool goodCredit,
				char *pGender,
				int  Interests_1,
				int  Interests_2,
				int  Interests_3,
				int  Interests_4,
				int  partnerId = 0,
				// nsacco 07/02/99
				int	 siteId = 0,
				int	 coPartnerId = 0,
				int	 reqEmaiCount = 0,
				long topSellerInitiatedDate = 0,
				int  topSellerLevel = 0
				);

		// HasDetail
		bool	HasDetail();

		// Dirty
		bool	IsDirty();
		void	SetDirty(bool dirty);

		void	AddUserInfo(
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
				int	 count,
				bool creditCardOnFile,
				bool goodCredit,
				char *pGender,
				int  interests_1,
				int  interests_2,
				int  interests_3,
				int  interests_4,
				int  partnerId = 0,
				// nsacco 07/02/99
				int  siteId = 0,
				int  coPartnerId = 0,
				int	 reqEmaiCount = 0,
				long topSellerInitiatedDate = 0,
				int  topSellerLevel = 0
				);

		void	UpdateUser();

		void	EarliestCreationDate(long createDate);

		//
		// State Helpers
		//	Silly routines to test and set the user's state.
		//	Their main utility is that they "hide" the various
		//	state values from most (if not all users). 
		//
		bool	IsConfirmed();
		bool	IsSuspended();
		bool	IsCCVerify();
		bool	IsUnconfirmed();
		bool	IsRenamePending(char *pNewUserId);

		void	SetConfirmed();
		void	SetSuspended();
		void	SetUnconfirmed();
		void	SetCCVerify();
		bool	IsInMaintenance();
		bool	IsDeleted();
		void	SetInMaintenance();
		void	SetDeleted();

		//
		// TestPass
		//	Determines if the password entered is valid
		bool	IsGoodPassword(char *pPass, char *mpPassword, char *mpSalt);
		bool	TestPass(char *pPassword);
		bool	TestPendingPass(char *pNewUserId, char *pPassword);

		// Check the access level that pPass grants this user
		// Return values: 0=no access, 1=level1 access, 2=level2 access
		int		GetAccessLevel(char *pPass);

		// Returns the encrypted password with the salt stripped out.
		//  Caller should NOT free the returned char*, as it just points
		//  to the member variable, mpPassword
		char*	GetPasswordNoSalt();

		//
		// Getters + Setters
		//	(which aren't handled by macros, that is)
		//
		UserStateEnum	GetUserState();
		void			SetUserState(UserStateEnum state);


		//
		// Determines if the UV (User Verification) rating is acceptable or not.
		//
		bool	IsValidUVRating();

		
		//
		// Credit Information
		//
		bool	HasGoodCredit();
		void	SetHasGoodCredit(bool doThey);

		bool	HasCreditCardOnFile();
		void	SetHasCreditCardOnFile(bool doThey);

		bool	IsInfoDirty();
		void	SetInfoDirty(bool dirty);

		//
		// Admin information
		//
		bool HasAdmin(AdminFunctionEnum adcode);
		void SetHasAdmin(AdminFunctionEnum adcode, bool doThey);

		// 
		// Daily Item Counts
		// 
		int		GetDailyItemCount();
		void	SetDailyItemCount(int count);
		void	AdjustDailyItemCount(int delta);

		//
		// GetFeedback
		//		Gets the user's feedback object.
		//
		clsFeedback	*GetFeedback();

		//
		// SetFeedback
		//		This is a *special* method to indicate
		//		we've prefetched the feedback, and here
		//		it is!
		//
		void SetFeedback(clsFeedback *pFeedback);

		//
		// GetAccount
		//		Gets the user's account object.
		//
		clsAccount	*GetAccount();

		//
		// GetListedItems
		//		Gets a list of items that this user
		//		has listed, and stuffs them into
		//		a vector; also will sort too if asked nicely.
		//      Set getMoreStuff to true, and the item will fill
		//		with more stuff
		void GetListedItems(ItemList *pItemVector, int daysSince, 
			bool getMoreStuff = false,
			ItemListSortEnum SortCode = SortItemsByUnknown);

		//
		// GetListedItemsCount
		//
		int GetListedItemsCount();

		//
		// GetBidItems
		//		Gets a list of items which this user
		//		has bid on.
		//
		void GetBidItems(ItemList *pItemList,
						 int daysSince,
						 bool getMoreStuff = false,
						 ItemListSortEnum SortCode = SortItemsByUnknown,
						 bool withPrivate = false);		// Charles added withPrivate

		//
		// GetBidItemsCount
		//
		int GetBidItemsCount();

		//
		// GetHighBidItems
		//		Gets a list of items which this user
		//		is the high bidder for
		//
		void GetHighBidItems(ItemList *pItemList,
							 bool completed,
							 ItemListSortEnum SortCode = SortItemsByUnknown);

		// copies pCopyUser's data to this user 
		void CopyUserData(clsUser *pCopyUser, long changeTime);

		void SetRenamePending(char *pNewUserId,
							char *pPassword,
							char *pSalt);

//		void Rename(char *pNewEmail);

		// get and set attribute values for user, not actually stored in
		// user; we get and set it from the database directly.
		void GetAttributeValue(int attribute_id,
							  bool *pGotBoolResponse,
							  bool *pBoolResponse,
							  bool *pGotNumberResponse,
							  float *pNumberResponse,
							  bool *pGotTextResponse,
							  char **ppTextResponse);

		void SetAttributeValue(int attribute_id,
								   bool value);

		void SetAttributeValue(int attribute_id,
								   float value);

		void SetAttributeValue(int attribute_id,
								   int value);

		void SetAttributeValue(int attribute_id,
								   char *pValue);

		//
		// ChangeUserId
		//
		//	This method is intended to be ATOMIC. Either it works,
		//	and the user gets the new userid, or it doesn't, and 
		//	they don't. 
		//
		bool ChangeUserId(char *pNewUserId);

		//
		// ChangeEmail
		//
		void ChangeEmail(char *pNewEmail);

		//
		//	GetAliasHistory
		//
		void GetAliasHistory(UserAliasHistoryVector *pVHistory);

/*
		//
		// UserIdChangedInInterval
		//
		bool UserIdChangedInInterval(int interval);
*/
		bool UserIdRecentlyChanged();
		bool CanUserChangeUserId();

		int CanReceiveInfo(const char *pHost);
		// resetting user settings
		void AddReqEmailCount(int Delta);
		void ResetReqUserCount();
		void ResetReqEmailCount();
		void ResetSellerList();
		void ResetBidderList();
		bool UserHasActivities();
		bool TestCryptedPassword(char *pEncryptedPass);
		void ChangePassword(char *pNewPass);

		// The functions to set the user flags always
		// return the old flags.
		int GetAllUserFlags();
		int SetAllUserFlags(int flags);

		//bool GetOneUserFlag(UserFlag bit);
		bool GetOneUserFlag(unsigned int bit); 
		int SetSomeUserFlags(bool on, int mask);

		// User agreement helper.
		bool	AcceptedUserAgreement();
		bool SendChangesToAgreement(); 
		bool SendChangesToPrivacy() ;

		// kaz: 4/15/99 Police Badge T&C support
		bool AcceptedPoliceBadgeAgreement();

		bool SendTakePartInSurveys() ;
		bool SendSpecialOffer();
		bool SendEventPromotion() ;
		bool SendNewsletter() ;

		bool SendEndofAuction() ;
		bool SendBid() ;
		bool SendOutBid(); 
		bool SendList() ;
		bool SendDailyStatus(); 

		// They're verified as an adult.
		bool	IsVerifiedAsAdult();
		void	VerifyAsAdult(bool on = true);

		bool	IsVerifiedAsAnon();
		void	VerifyAsAnon(bool on = true);

		bool	HasAboutMePage();
        void	SetAboutMePage(bool on = true);

		bool	HasANote();
		void	SetHasANote(bool does);

		void SetTopSellerLevel(TopSellerLevelEnum level);
		int  GetTopSellerLevel();
		bool IsTopSeller();

		// Some international-related functions.
		bool OkayToViewAdult();

		// Deadbeat info
		int			GetDeadbeatScore();
		bool		IsDeadbeat();
		clsDeadbeat *GetDeadbeat();

		bool	HasABlockedItem();
		void	SetHasABlockedItem(bool does);


		//survey 
		bool	IsParticipatedSurvey(int survey_id);
		void	AddUserToSurveyRecord(int survey_id);

		char *GetCountry ();				// petra

private:

		INT_VARIABLE(MarketPlace);			// User's marketplace
		INT_VARIABLE(Id);					// Numeric Id
		STRING_VARIABLE(UserId);
		UserStateEnum	mUserState;			// User's state
		STRING_VARIABLE(Password);			// Password
		STRING_VARIABLE(Salt);				// Salt
		LONG_VARIABLE(LastModified);        // last update time
		INT_VARIABLE(UserFlags);			// Flags for user choices
		INT_VARIABLE(UVRating);				// numerical user verification rating
		INT_VARIABLE(UVDetail);				// flags for user verification detail record
		INT_VARIABLE(CountryId);			// Id of the user's home country

		STRING_VARIABLE(Host);				// Host 
		STRING_VARIABLE(Name);				// Name
		STRING_VARIABLE(Company);			// Company
		STRING_VARIABLE(Address);			// Address
		STRING_VARIABLE(City);				// City
		STRING_VARIABLE(State);				// State
		STRING_VARIABLE(Zip);				// Zip
// petra		STRING_VARIABLE(Country);			// Country
		char *mpCountry;					// petra
		STRING_VARIABLE(DayPhone);			// Phone
		STRING_VARIABLE(NightPhone);		// Phone
		STRING_VARIABLE(FaxPhone);			// Phone
		LONG_VARIABLE(Created);				// Registration date

		// this should be custom, setting both flags to dirty!!
		STRING_VARIABLE(Email);				// Email address

		INT_VARIABLE(Count);				// items listed
		BOOL_VARIABLE(CreditCardOnFile);	// user credit info
		BOOL_VARIABLE(GoodCredit);			// ditto
		STRING_VARIABLE(Gender);			// gender
		INT_VARIABLE(Interests_1);			// interests
		INT_VARIABLE(Interests_2);
		INT_VARIABLE(Interests_3);
		INT_VARIABLE(Interests_4);
		INT_VARIABLE(PartnerId);			// Partner Id
		// nsacco 07/02/99
		INT_VARIABLE(SiteId);				// site id 
		INT_VARIABLE(CoPartnerId);			// branding partner id
		LONG_VARIABLE(UserIdLastModified);
		// note, this is not updated in UpdateUserInfo field;
		// update only via database call: AddReqEmailCount(clsUser, delta);
		INT_VARIABLE(ReqEmailCount);		// # email request count

		// TopSeller
//		INT_VARIABLE(TopSellerLevel);			// TS level
		LONG_VARIABLE(TopSellerInitiatedDate);	// date user was made a TS

		void	ClearAll();
		void    ClearUserInfo();

		// merge user state during renames

		void MergeUserState(UserStateEnum fromstate, UserStateEnum tostate);

		//
		// mDirty tells us if we've been modified
		//
		bool			mDirty;

		//
		// mHasDetail tells us if we have user detail info
		// mDirtyDetail tells us if we've modified info
		bool			mHasDetail;
		bool			mDirtyDetail;
		

		// Item counts is seprate from everything else,
		// so we keep it that way
		int				mDailyItemCount;
		int				mDailyItemCountDelta;

		// Other interesting objects
		clsFeedback		*mpFeedback;
		clsAccount		*mpAccount;
		clsDeadbeat		*mpDeadbeat;

		int mTopSellerLevel;
};

//
// This thing is used for STL
//
class clsUserPtr
{
	public:
		//
		// CTOR
		//
		clsUserPtr()
		{
			mpUser	=	NULL;
		}

		clsUserPtr(clsUser *pUser)
		{
			mpUser	=	pUser;
		}

		~clsUserPtr()
		{
			return;
		}

		clsUser		*mpUser;
};

typedef list<clsUserPtr> UserList;



#undef STRING_VARIABLE
#define CLSUSER_INCLUDED 1
#endif /* CLSUSER_INCLUDED */



