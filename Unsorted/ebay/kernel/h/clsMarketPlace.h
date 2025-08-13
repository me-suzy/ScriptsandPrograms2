/*	$Id: clsMarketPlace.h,v 1.15.2.3.10.3 1999/08/10 01:19:49 nsacco Exp $	*/
//
//	File:		clsMarketPlace.h
//
// Class:	clsMarketPlace
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Representation of a marketplace
//
// Modifications:
//				- 02/07/97 michael	- Created
//				- 18/07/97 tini		- add hot item count
//				- 06/18/98 inna     - added CCardEmail
//				- 08/22/98 mila		- added methods for getting pics/ directory
//									  path, relative path, etc...
//				- 05/25/99 nsacco	- removed mpPartners and added mpSites. Removed
//									  GetPartners. Added GetSites.
//				- 07/19/99 nsacco	- Added GetCurrentPartnerName()
//				- 08/04/99 nsacco	- Added mDynLoginPrompt, mDynPasswordPrompt.
//

#ifndef CLSMARKETPLACE_INCLUDED
#define CLSMARKETPLACE_INCLUDED 1

#include "eBayTypes.h"
#include "eBayPageTypes.h"
#include <vector.h>
#include "clsSynchronize.h"
#include <string>

#include <clsLogging.h>
#include <clsTimeScale.h>

// nsacco 05/25/99
#include "clsSites.h"

// Interesting defines
#define	MARKETPLACE_UNKNOWN 0
#define NUM_SPECIAL_PASS 2
#define MAIL_CLASSES 3

#define MAIL_CONTROL_TESTING 1

#ifndef MAIL_CONTROL_TESTING
typedef enum
{
	bidNoticesChinese = 0,
	bidNoticesDutch = 1,
	outBidNoticesChinese = 2
} MailBidNoticeTypeEnum;

typedef enum
{
	all_pools = -1,
	pool_general = 1,
	pool_registration = 2,
	pool_help = 3
} MailPoolTypeEnum;

typedef struct
{
	char *machine;
	int	weighting;
} MailMachine;
typedef vector<MailMachine *> MailMachineVector;

typedef struct
{
	int poolType;
	MailMachineVector *machines;
} MailPool;
typedef vector<MailPool *> MailPoolVector;
#endif

// Class forward
class	clsItem;
class	clsItems;
class	clsUsers;
class	clsUser;
class	clsCategories;
class	clsStatistics;
class	clsAnnouncements;
class	clsBulletinBoards;
class	clsAdWidget;
class	clsAdRelated;
class	ostream;
// nsacco 05/25/99 added clsSite
class	clsSite;
class	clsNotes;
class	clsLocations;
class	clsUserVerificationServices;
class   clsCountries;
// only on NT FOR THE MOMENT
#ifdef _MSC_VER
class   clsMailControl;
#endif
class   clsCurrencies;
class	clsRegions;
class	clsFilters;
class	clsMessages;

// 
// These little classes describe
// bid increments
//
class clsMarketPlaceBidIncrement
{
	public:
		double	mMinValue;
		double	mMaxValue;
		double	mIncrement;
};

// 
// And Insertion fees
//
class clsMarketPlaceInsertionFees
{
	public:
		double	mMaxValue;
		double	mFee;
};


// 
// This class describes the various 
// criteria which a user must meet to
// perform certain operations like
// listing items, bidding, etc.
//
class clsMarketPlaceUserCriteria
{
	public:
		// Does user state count?
		bool	mUserStateCriteria;

		// (State) Do they have to be registered?
		bool	mMustBeRegistered;

		// (State) Do they have to NOT be Suspended?
		bool	mMustNotBeSuspended;

		// Does feedback count?
		bool	mFeedbackCriteria;

		// (Feedback) What is the minimum feedback allowd?
		int		mMinimumFeedbackScore;

		// Do credit requirements count?
		bool	mCreditStateCriteria;

		// (Credit) Do they have to have a credit card
		// on file?
		bool	mMustHaveCCOnFile;

		// (Credit) Do they have to have good credit?
		bool	mMustHaveGoodCredit;

		// Does Account status count?
		bool	mAccountBalanceCriteria;

		// (Account) What is the maximum outstanding
		// balance allowed?
		double	mMaximumBalance;

		// Past Due concerns?
		bool	mAccountPastDueCriteria;

		// (Account) What is the maximum outstanding past
		// due balance allowed?
		double	mMaximumPastDueBalance;

		// Does having good credit override any balance
		// concerns?
		bool	mCreditOverridesBalance;

};

class clsMarketPlace
{

	public:
		//
		// Vanilla CTOR/DTOR
		//
		clsMarketPlace();

		//
		// with feeling...
		//
		clsMarketPlace(MarketPlaceId id, 
							const char *pName,
							const char *pHeader,
							const char *pAboutMeHeader,							
							const char *pSecureHeader,
							const char *pFooter,
							const char *pSecureFooter,
							const char *pRelativeHeader,
							const char *pRelativeFooter,
							const char *pHTMLPath,
							const char *pHTMLRelativePath,
							const char *pImagePath,
							const char *pSecureHTMLPath,
							const char *pSecureHTMLRelativePath,
							const char *pCGIPath,
							const char *pCGIRelativePath,
							const char *pPicsPath,
							const char *pPicsRelativePath,
					// kakiyama 07/20/99
							const char *pSearchPath,
							const char *pGalleryListingPath,

							const char *pSSLCGIPath,
							const char *pSSLHTMLPath,
							const char *pSSLImagePath,
							const char *pAdminPath,
							const char *pListingPath,
							const char *pListingRelativePath,
							const char *pMembersPath,
							const char *pLoginPrompt,
							const char *pPasswordPrompt,
							const char *pHomeURL,
							const char *pThankYouText,
							const char *pConfirmEmail,
							const char *pAdminEmail,
							const char *pSupportEmail,
							const char *pBillingEmail,
							const char *pRegistrationEmail,
							const char *pBillingPolicyText,
							int bidIncrementCount,
							clsMarketPlaceBidIncrement *pIncrements,
							int	insertionFeeCount,
							clsMarketPlaceInsertionFees *pFees,
							double featuredFee,
							double categoryFeaturedFee,
							double boldFee,
							double giftIconFee,
							double galleryFee,
							double galleryFeaturedFee,
							double itemMoveFee,
							int	hotItemCount,
							const char *pSpecialPassword0,
							const char *pSpecialPassword1,
							const char *pAdminSpecialPassword,
							const clsMarketPlaceUserCriteria *pListCriteria,
							const clsMarketPlaceUserCriteria *pFeatureCriteria,
							const clsMarketPlaceUserCriteria *pBidCriteria,
							const char *pCCardEmail,
							const char *pReportInfringingEmail
					  );
		// inna added CCardEmail

		// DTOR
		~clsMarketPlace();

		//
		// GetName
		//		Returns the name of the marketplace
		//
		const char *GetName();

		//
		// GetId
		//		Returns the marketplace id
		//
		MarketPlaceId GetId();

		//
		// GetParentMarketPlace
		//		Returns our "parent" marketplace
		//
		clsMarketPlace *GetParentMarketPlace();

		//
		// GetChildMarketplaces
		//		Returns a STL (thing) containing a list
		//		of marketplace subordinate to this one.
		//		If the recurse parameter is true, it 
		//		returns ALL subordinate marketplaces.
		//
		// GetChildMarketplaces(bool recurse=false);

		//
		// GetCategories
		//		Returns the clsCategories object for this
		//		marketplace
		clsCategories	*GetCategories();

		//
		// GetItems
		//		Returns the clsItems object for all items
		//		in the marketplace.
		//
		clsItems *GetItems();

		//
		// GetUsers
		//		Returns the clsUsers object for all users
		//		in a marketplace
		//
		clsUsers *GetUsers();

		//
		// GetStatistics
		//		Returns the clsStatistics for all statistics
		//		in the marketplace
		clsStatistics*	GetStatistics();

		//
		// GetAnnouncements
		//		Returns the clsAnnouncements for all announcements
		//		in the marketplace
		clsAnnouncements *GetAnnouncements();

		//
		// GetBulletinBoards
		//		Returns the clsBulletinBoards Object
		//
		clsBulletinBoards *GetBulletinBoards();

		//
		// ResetBulletinBoards
		//		Clears the clsBulletinBoards Object so it will be
		//		reloaded
		//
		void ResetBulletinBoards();

		//
		// GetNotes
		//		Gets the clsNotes object. If the object already
		//		exists, reset it
		//
		clsNotes		*GetNotes();


		//
		// GetLocations
		//		Returns the clsLocations object for location services
		clsLocations *GetLocations();

		
		//
		// GetUserVerificationServices
		//		Returns the clsUserVerificationServices object for user-verification services
		clsUserVerificationServices *GetUserVerificationServices();

		// **********
		// The following functions will probably be relegated
		// to resources
		// **********

		//
		// GetHeader
		//		Returns a pointer to the Marketplace's
		//		HTML Header
		//
		const char *GetHeader(bool withAnnouncements=true);
		const char *GetFooter(bool getAds = false);
		const char *GetRelativeHeader();
		const char *GetRelativeFooter();
		const char *GetAboutMeHeader();

		// Get*Path
		const char *GetHTMLPath(PageEnum page = PageUnknown);
		const char *GetHTMLNoCobrandPath(PageEnum page = PageUnknown);
		const char *GetHTMLRelativePath();
		const char *GetImagePath();
		const char *GetHTMLRelativeNoCobrandPath();
		const char *GetSecureHTMLPath();
		const char *GetSecureHTMLRelativePath();
		const char *GetSecureHeader();
		const char *GetSecureFooter();
		const char *GetCGIPath(PageEnum page = PageUnknown);
		const char *GetCGINoCobrandPath(PageEnum page = PageUnknown);
		const char *GetCGIRelativePath();
		const char *GetCGIRelativeNoCobrandPath();
		const char *GetPicsPath(PageEnum page = PageUnknown);
		const char *GetPicsNoCobrandPath(PageEnum page = PageUnknown);
		const char *GetPicsRelativePath();
		const char *GetPicsRelativeNoCobrandPath();
		const char *GetSSLCGIPath(PageEnum page = PageUnknown);
		const char *GetSSLHTMLPath(PageEnum page = PageUnknown);
		const char *GetSSLImagePath(PageEnum page = PageUnknown);
		const char *GetAdminPath();
		const char *GetListingPath();
		const char *GetListingNoCobrandPath();
		const char *GetListingRelativePath();
		const char *GetListingRelativeNoCobrandPath();
		const char *GetMembersPath();
		const char *GetLoginPrompt();
		const char *GetPasswordPrompt();
		const char *GetHomeURL();
		const char *GetThankYouText();
		const char *GetConfirmEmail();
		const char *GetAdminEmail();
		const char *GetSupportEmail();
		const char *GetBillingEmail();
		const char *GetRegistrationEmail();
		const char *GetBillingPolicyText();
		//inna
		const char *GetCCardEmail();
		const char *GetReportInfringingEmail();

		// kakiyama 07/20/99
		const char *GetSearchPath(PageEnum page = PageUnknown);
		const char *GetGalleryListingPath();
		const char *GetGalleryListingNoCobrandPath();
		// nsacco 08/09/99
		const char *GetAdPicsPath();

		//
		// GetHotItemCount
		//
		int GetHotItemCount();

		//
		// GetXCriteria
		//	Returns the proper criteria for the requested
		//	action
		//
		const clsMarketPlaceUserCriteria *GetListCriteria();
		const clsMarketPlaceUserCriteria *GetFeaturedCriteria();
		const clsMarketPlaceUserCriteria *GetBidCriteria();

		//
		// That special passwords
		//
		const char *GetSpecialPassword(int i=0);
		const char *GetAdminSpecialPassword();

		//
		// UserCanList
		//		Determines if the passed user is allowed to
		//		list items in the marketplace
		bool UserCanList(clsUser *pUser, 
						 ostream *pStream = NULL,
						 bool	 CheckBalance = true);

		//
		// UserCanFeature
		//		Determines if the passed user is allowed to
		//		list _featured_ items in the marketplace
		bool UserCanFeature(clsUser *pUser, ostream *pStream = NULL);

		//
		// UserCanBid
		//		Determines if the passed user is allowed to 
		//		bid in this marketplace
		bool UserCanBid(clsUser *pUser, ostream *pStream = NULL);


		// In order for a user to list dutch auctions, they must:
		// 1. Have a feedback rating of at least 10 -and-
		// 2. Must have been registered on eBay for at least 60 days.
		//   -or-
		// 1. Have a credit card on file
		// Note: Didn't use EvaluateUser() here, because it would have taken
		//  too long to figure out how to make it do an -or- comparison.
		bool UserCanListDutchAuction(clsUser *pUser);

		// Returns whether or not a user id is considered recently changed
		bool UserIdRecentlyChanged(time_t userId_last_modified);

		// Returns whether or not user can change user id
		bool CanUserChangeUserId(time_t userId_last_modified);

		//GiftIcon info
		const char *GetGiftIconImage(int iconType);

		//
		// Get AdWidget
		//
		clsAdWidget*	GetAdWidget();

		clsAdRelated*	GetAdRelated();

		PageEnum GetCurrentPage() { return mePage; }
		void SetCurrentPage(PageEnum ePage) {mePage = ePage;}

		int GetMailMachineIndex(int mailClass) const { if (mailClass < 0 || mailClass >= MAIL_CLASSES) mailClass = 0; return mMailIndex[mailClass]; }
		void SetMailMachineIndex(int mailClass, int index) { if (mailClass < 0 || mailClass > MAIL_CLASSES) mailClass = 0; mMailIndex[mailClass] = index; }

		clsCountries* GetCountries();
		clsCurrencies* GetCurrencies();

		//gurinder - for time scale logging
		clsLogging*		mpLogging;
		clsLogging*		GetLogging();

// only on NT FOR THE MOMENT
#ifdef _MSC_VER
		clsMailControl* GetMailControl();
#endif
		// new for Feb 2, 1999 dutch auction policy
		static const int mMinFeedbackForDutch;
		static const int mMinUserAgeForDutch;

		double GetMaxAmount(int currencyId);
		int    GetMaxAmountSize(int currencyId);
// petra		TimeZoneEnum    GetCurrentTimeZone();
// petra		void   SetCurrentTimeZone(TimeZoneEnum tz);
		//
		// Regional Codes
		//
		clsRegions*	GetRegions();


		clsFilters *	GetFilters();

		clsMessages *	GetMessages();

		// nsacco 05/25/99
		clsSites* GetSites();

		// wen 6/9/99
		int GetCurrentSiteId();
		int GetCurrentPartnerId();

		// nsacco 07/19/99
		const char *GetCurrentPartnerName();

private:
		bool EvaluateUser(const clsMarketPlaceUserCriteria *pCriteria,
						  char *pAttemptedAction,
						  clsUser *pUser,
						  ostream *pStream,
						  bool	CheckBalance = true);

		MarketPlaceId				mId;
		clsCategories				*mpCategories;
		clsItems					*mpItems;
		clsUsers					*mpUsers;
		clsStatistics				*mpStatistics;
		clsAnnouncements			*mpAnnouncements;
		clsBulletinBoards			*mpBulletinBoards;
		clsNotes					*mpNotes;
		clsLocations				*mpLocations;
		clsUserVerificationServices	*mpUserVerificationServices;
		clsCountries				*mpCountries;
		clsCurrencies				*mpCurrencies;
// only on NT FOR THE MOMENT
#ifdef _MSC_VER
		clsMailControl				*mpMailControl;
#endif
		clsFilters					*mpFilters;
		clsMessages					*mpMessages;

		const char					*mpName;
		const char					*mpHeader;
		const char					*mpAboutMeHeader;
		const char					*mpFooter;
		const char					*mpSecureHeader;
		const char					*mpSecureFooter;
		const char					*mpRelativeFooter;
		const char					*mpRelativeHeader;
		const char					*mpHTMLPath;
		const char					*mpHTMLRelativePath;
		const char					*mpImagePath;
		const char					*mpSecureHTMLPath;
		const char					*mpSecureHTMLRelativePath;
		const char					*mpCGIPath;
		const char					*mpCGIRelativePath;
		const char					*mpPicsPath;
// kakiyama 07/20/99
		const char					*mpSearchPath;
		const char					*mpGalleryListingPath;

		const char					*mpPicsRelativePath;
		const char					*mpSSLCGIPath;
		const char					*mpSSLHTMLPath;
		const char					*mpSSLImagePath;
		const char					*mpAdminPath;
		const char					*mpListingPath;
		const char					*mpListingRelativePath;
		const char					*mpMembersPath;
		const char					*mpLoginPrompt;
		const char					*mpPasswordPrompt;
		const char					*mpHomeURL;
		const char					*mpThankYouText;
		const char					*mpConfirmEmail;
		const char					*mpAdminEmail;
		const char					*mpSupportEmail;
		const char					*mpBillingEmail;
		const char					*mpRegistrationEmail;
		const char					*mpBillingPolicyText;


		int							mBidIncrementCount;
		clsMarketPlaceBidIncrement	*mpBidIncrements;

		int							mInsertionFeeCount;
		clsMarketPlaceInsertionFees	*mpInsertionFees;

		double						mFeaturedFee;
		double						mCategoryFeaturedFee;
		double						mBoldFee;
		double						mGiftIconFee;
		double						mGalleryFee;
		double						mGalleryFeaturedFee;
		double						mItemMoveFee;

		int							mHotItemCount;

		const char					*mpSpecialPassword[NUM_SPECIAL_PASS];
		const char					*mpAdminSpecialPassword;

		const clsMarketPlaceUserCriteria	*mpListCriteria;
		const clsMarketPlaceUserCriteria	*mpFeatureCriteria;
		const clsMarketPlaceUserCriteria	*mpBidCriteria;

		clsAdWidget*	mpAdWidget;
		clsAdRelated*	mpAdRelated;

		int				mPartnersVersion;
		PageEnum		mePage;
		
		//inna - holds ccard email address
		const char					*mpCCardEmail;
		const char					*mpReportInfringingEmail;

		// Which fees and credits to use
		int				mCurrentCurrency;
// petra		TimeZoneEnum	mCurrentTimeZone;

		int mMailIndex[MAIL_CLASSES]; // Index of the current mailserver.

		// Regions
		//
		clsRegions*	mpRegions;

		// Sites
		// nsacco 05/25/99 for cobranding
		clsSites* mpSites;

		// nsacco 08/04/99
		char mDynLoginPrompt[256];
		char mDynPasswordPrompt[256];
};


#endif CLSMARKETPLACE_INCLUDED
