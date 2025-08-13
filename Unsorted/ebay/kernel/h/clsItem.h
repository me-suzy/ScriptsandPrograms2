/*	$Id: clsItem.h,v 1.17.2.4.14.2 1999/08/03 23:41:24 nsacco Exp $	*/
//
//	File:		clsItem.h
//
// Class:	clsItem
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Represents an item
//
//	***** NOTE *****
//	See the "SetNewHighBidder" and "SetNewDescription"
//	methods. These (essentially) set some member variables,
//	and then force them to be written out to the database.
//	It might be nice to handle these more elegantly, for
//	example with a "dirty flag" which forces the object
//	out to the database (at a later time), but this is
//	how it's done now.
//	***** NOTE ******
//
// Modifications:
//				- 02/10/97 michael	- Created
//				- 06/13/97 tini     - removed itemXref, added last Modified date
//				- 07/29/97 tini		- added host and visit count to items
//				- 07/05/97 tini		- added get dutch high bidders;
//				- 09/29/97 wen		- added password + modified Set() +
//									- defined ItemFetureTypeEnum
//				- 05/24/99 jennifer - added functions for Gallery Admin Tool
//				- 06/10/99 bill		- modified Fancy CTOR clsItem constructor
//				- 07/15/99 samuel	- added one new member variable, mBillingCurrencyId
//									  and associated changes in constructors and 
//									  add/retrieve database functions
//				- 07/15/99 nsacco	- added a siteid
//				- 07/27/99 nsacco	- Added new params to Set() and new member variables
//										Added SetShipToRegion() and some other new functions
//
#ifndef CLSITEM_INCLUDED

#include "eBayTypes.h"
#include "clsBid.h"
#include "clsBidEngine.h"
#include "vector.h"
#include "list.h"
#include "time.h"
#include "clsGalleryChangedItem.h"
#include "clsFees.h"

// Class forward
class clsBidResult;
class clsItems;


// Auction Type Enums
typedef enum
{
	AuctionUnknown	= 0,
	AuctionChinese	= 1,
	AuctionDutch	= 2,
	AuctionReverse	= 3,
	AuctionYankee	= 4
} AuctionTypeEnum;

// Item Feature Type Enum (used in the password field of the item)
// ItemFeatureTypeEnum is moved to "eBayTypes.h" as it is used in clsListingItem now
// ----- Steve, 3/29/99


// Sam, 01/20/99, auto credits
// reasons for credit request
// credit request marked deadbeat if type==1 or 2 or 3 or 4 or 8
// **Important** this order must be maintained in credit-request.html
typedef enum
{
	CreditTypeUnknown								= 0,
	CreditTypeFullNoResponseFromBidder				= 1,
	CreditTypeFullBidderNoLongerWantsItem			= 2,
	CreditTypeFullPaymentNotReceivedFromBidder		= 3,
	CreditTypeFullBouncedCheckOrStoppedPayment		= 4,
	CreditTypeFullBidderReturnedItem				= 5,
	CreditTypeFullBidderFamilyEmergency				= 6,
	CreditTypeFullBidderClaimedUnacceptableTerms	= 7,
	CreditTypePartialItemSoldToLowerBidder			= 8,
	CreditTypePartialItemDamagedLowerPrice			= 9,
	CreditTypePartialItemPriceConfusion				= 10,
	CreditTypePartialItemBidderFamilyEmergency		= 11,
	// put all new credit types about this line!!!
	CreditTypeLast									= 12
} CreditTypeEnum;


struct ItemCredits	{ 
		  			int					item_id;
					int					bidder_id;
					float				amt;
					time_t				last_modified;
					CreditTypeEnum		reason_code;
					char				credit_type[3];
					int					quantity;
					int					batch_id;
					};
typedef struct ItemCredits sItemCredits;
typedef vector<sItemCredits *> CreditsVector;


//
//	SaleStatus flags. These are #defined so we can play
//	with bitmasks. An Enum could have also been used, 
//	but I wasn't sure what it would accomplish
//
#define	ITEM_SALE_STATUS_NO_FINAL_VALUE_FEE	0x01
// seller bid for item before
#define ITEM_BID_BY_SELLER_FLAG 0x02
#define ITEM_BIDS_FINALIZED_FLAG 0x04
//inna - mark wacko items
#define ITEM_WACKO_FLAG 0x08


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



class clsItem
{
	friend class	clsItemPtr;

	public:
		// Vanilla CTOR and DTOR, as required
		clsItem() :
			mMarketPlaceId((MarketPlaceId)0),
			mId(0),
			mAuctionType(AuctionUnknown),
			mpTitle((char *)0),
			mpDescription((char *)0),
			mpLocation((char *)0),
			mSeller(0),
			mOwner(0),
			mPassword(0),
			mCategory(0),
			mQuantity(0),
			mBidCount(0),
			mStartTime(0),
			mEndTime(0),
			mStatus(0),
			mPrice(0),
			mStartPrice(0),
			mReservePrice(0),
			mHighBidder(0),
			mFeatured(false),
			mSuperFeatured(false),
			mBoldTitle(false),
			mPrivate(false),
			mRegisteredOnly(false),
			mpHost((char *)0),
			mVisitCount(0),
			mpPictureURL(NULL),
			mpCategoryName(NULL),
			mpSellerUserId(NULL),
			mSellerUserState(UserUnknown),
			mSellerUserFlags(0),
			mpHighBidderUserId(NULL),
			mHighBidderUserState(UserUnknown),
			mHighBidderUserFlags(0),
			mSellerFeedbackScore(INT_MIN),
			mHighBidderFeedbackScore(INT_MIN),
			mSellerIdLastModified(0),
			mHighBidderIdLastModified(0),
			mLastModified(0),
			mpSellerEmail(NULL),
			mpHighBidderEmail(NULL),
			mpRowId(NULL),
			mDelta(0),
			mpIconFlags(NULL),
			mpGalleryURL(NULL),
			mGalleryType(NoneGallery),
			mGalleryState(kGalleryNotProcessed),
			mCountryId(Country_None),
			mCurrencyId(Currency_USD),
			mEnded(0),
			mpZip(NULL),
			//samuel, 7/15/99
			mBillingCurrencyId(Currency_USD),
			mShippingOption(SiteOnly),	// nsacco 07/27/99
			mShipRegionFlags(ShipRegion_None),	// nsacco 07/27/99
			mDescLang(English),			// nsacco 07/27/99
			mSiteId(SITE_EBAY_MAIN)		// nsacco 07/15/99

		{
			mpBidEngine	= NULL;
			mHaveInfo	= false;
			mNoticeTime	= false;
			mBillTime	= false;
			mDirty		= false;

			mpItems		= NULL;
			mAdult = 'A';
			//vicki
			mpIconFlags = NULL;
			mpFees      = NULL;
			return;
		}

		virtual ~clsItem()
		{
			// throw exception if mDirty, but for right now, do nothing
			delete mpBidEngine;
			mpBidEngine			= NULL;
			delete [] mpTitle;
			mpTitle				= NULL;
			delete [] mpDescription;
			mpDescription		= NULL;
			delete [] mpLocation;
			mpLocation			= NULL;
			delete [] mpPictureURL;
			mpPictureURL		= NULL;
			delete [] mpCategoryName;
			mpCategoryName		= NULL;
			delete [] mpSellerUserId;
			mpSellerUserId		= NULL;
			delete [] mpHighBidderUserId;
			mpHighBidderUserId	= NULL;
			delete [] mpHost;
			mpHost				= NULL;
			delete [] mpSellerEmail;
			delete [] mpHighBidderEmail;
			delete [] mpRowId;
			mpRowId				= NULL;
			delete [] mpIconFlags;
			mpIconFlags			= NULL;
			delete [] mpGalleryURL;
			mpGalleryURL		= NULL;
			delete mpFees;
			mpFees				= NULL;
			delete mpZip;
			mpZip				= NULL;
		}

		// 
		// This constructor is used to create new items,
		// and thus has a lot of information defaulted
		//
		// nsacco 07/27/99 added new params
		clsItem(int marketPlaceid,
				int id,
				AuctionTypeEnum auctionType,
				char *pTitle,
				char *pDescription,				// Populated
				char *pLocation,
				int seller,
				int owner,
				int category,
				int quantity, 
				long startTime,
				long endTime,
				long status, 
				double startPrice,
				double reservePrice,
				bool featured,
				bool superFeatured,
				bool boldTitle,
				bool privateAuction,
				bool registeredOnly,
				char *pHost,
				char *pPictureURL,
				long lastModified,
				int password=0,
				char *pRowId=NULL,
				long delta=0,
				char *pIconFlags=NULL,
				char *pGalleryURL=NULL,
				GalleryTypeEnum galleryType = NoneGallery,
				GalleryResultCode galleryState = kGalleryNotProcessed,
				int  countryId = Country_None,
				int  currencyId = Currency_USD, 
				bool ended = false,
				char *pZip=NULL,
				//samuel, 7/15/99
				int  billingCurrencyId = Currency_USD,
				int  shippingOption = SiteOnly,
				long shipRegionFlags = ShipRegion_None,
				int	 descLang = English,
				int  siteId = SITE_EBAY_MAIN) :	
			mMarketPlaceId(marketPlaceid),
			mId(id),
			mAuctionType(auctionType),
			mpTitle(pTitle),
			mpDescription(pDescription),
			mpLocation(pLocation),
			mSeller(seller),
			mOwner(owner),
			mPassword(password),
			mCategory(category),
			mQuantity(quantity),
			mBidCount(0),
			mStartTime(startTime),
			mEndTime(endTime),
			mStatus(status),
			mPrice(0),
			mStartPrice(startPrice),
			mReservePrice(reservePrice),
			mHighBidder(0),
			mFeatured(featured),
			mSuperFeatured(superFeatured),
			mBoldTitle(boldTitle),
			mPrivate(privateAuction),
			mRegisteredOnly(registeredOnly),
			mpHost(pHost),
			mVisitCount(0),
			mpPictureURL(pPictureURL),
			mpCategoryName(NULL),
			mpSellerUserId(NULL),
			mSellerUserState(UserUnknown),
			mSellerUserFlags(0),
			mpHighBidderUserId(NULL),
			mHighBidderUserState(UserUnknown),
			mHighBidderUserFlags(0),
			mSellerFeedbackScore(INT_MIN),
			mHighBidderFeedbackScore(INT_MIN),
			mSellerIdLastModified(0),
			mHighBidderIdLastModified(0),
			mLastModified(lastModified),
			mpSellerEmail(NULL),
			mpHighBidderEmail(NULL),
			mpRowId(pRowId),
			mDelta(delta),
			mGalleryType(galleryType),
			mGalleryState(galleryState),
			mCountryId(countryId),
			mCurrencyId(currencyId),
			mEnded(ended),
			//samuel, 7/15/99
			mBillingCurrencyId(billingCurrencyId),
			mShippingOption(shippingOption), // nsacco 07/27/99
			mShipRegionFlags(shipRegionFlags), // nsacco 07/27/99
			mDescLang(descLang),		// nsacco 07/27/99
			mSiteId(siteId)				// nsacco 07/15/99

		{
			if (pTitle)
			{
				mpTitle		= new char[strlen(pTitle) + 1];
				strcpy(mpTitle, pTitle);
			}
			else
				mpTitle		= NULL;

			if (pDescription)
			{
				mpDescription	= new char[strlen(pDescription) + 1];
				strcpy(mpDescription, pDescription);
			}
			else
				mpDescription	= NULL;

			if (pLocation)
			{
				mpLocation		= new char[strlen(pLocation) + 1];
				strcpy(mpLocation, pLocation);
			}
			else
				mpLocation		= NULL;

			if (pPictureURL)
			{
				mpPictureURL	= new char[strlen(pPictureURL) + 1];
				strcpy(mpPictureURL, pPictureURL);
			}
			else
				mpPictureURL		= NULL;

			if (pGalleryURL)
			{
				mpGalleryURL	= new char[strlen(pGalleryURL) + 1];
				strcpy(mpGalleryURL, pGalleryURL);
			}
			else
				mpGalleryURL		= NULL;

			if (pRowId)
			{
				mpRowId	= new char[strlen(pRowId) + 1];
				strcpy(mpRowId, pRowId);
			}
			else
				mpRowId		= NULL;

			mpBidEngine	= NULL;

			mHaveInfo	= false;
			mNoticeTime	= 0;
			mBillTime	= 0;

			mDirty = false;

			mpItems		= NULL;
			mAdult = 'A';
			if (pIconFlags)
			{
				mpIconFlags	= new char[strlen(pIconFlags) + 1];
				strcpy(mpIconFlags, pIconFlags);
			}
			else
				mpIconFlags		= NULL;
			
			mpFees = new clsFees(this);

			if (pZip)
			{
				mpZip = new char[strlen(pZip) + 1];
				strcpy(mpZip, pZip);
			}

			return;
		}


		// Fancy CTOR
		// nsacco 07/27/99 added new params
		clsItem(int marketPlaceid,
				int id,
				AuctionTypeEnum auctionType,
				char *pTitle,
				char *pDescription,				// Populated
				char *pLocation,
				int seller,
				int owner,
				int pass, 
				int category,
				int bidCount, 
				int quantity, 
				long startTime,
				long endTime,
				long status, 
				double price, 
				double startPrice,
				double reservePrice,
				int highBidder,
				bool featured,
				bool superFeatured,
				bool boldTitle,
				bool privateAuction,
				bool registeredOnly,
				char *pHost,
				int visitCount,
				char *pPictureURL,
				char *pCategoryName,			// Populated
				char *pSellerUserId,			// Populated
				int sellerUserState,			// Populated
				int	sellerUserFlags,			// Populated
				char *pHighBidderUserId,		// Populated
				int highBidderUserState,		// Populated
				int highBidderUserFlags,		// Populated
				int sellerFeedbackScore,		// Populated
				int highBidderFeedbackScore,	// Populated
				long sellerIdLastChange,		// Populated
				long highBidderIdLastChange,	// Populated
				long lastModified,
				const char *pSellerEmail,		// Populated
				const char *pHighBidderEmail,	// Populated
				char *pRowId,
				long delta,
				char *pIconFlags,
				char *pGalleryURL,
				GalleryTypeEnum	galleryType,
				GalleryResultCode galleryState,
				int countryId = Country_None,
				int currencyId = Currency_USD,
				bool ended = false,
				char *pZip = NULL,
				//samuel, 7/15/99
				int billingCurrencyId = Currency_USD,
				int shippingOption = SiteOnly,	// nsacco 07/27/99
				long shipRegionFlags = ShipRegion_None, // nsacco 07/27/99
				int descLang = English,			// nsacco 07/27/99
				int siteId = SITE_EBAY_MAIN		// nsacco 07/15/99
				) :	
			mMarketPlaceId(marketPlaceid),
			mId(id),
			mAuctionType(auctionType),
			mpTitle(pTitle),
			mpDescription(pDescription),
			mpLocation(pLocation),
			mSeller(seller),
			mOwner(owner),
			mPassword(pass),
			mCategory(category),
			mQuantity(quantity),
			mBidCount(bidCount),
			mStartTime(startTime),
			mEndTime(endTime),
			mStatus(status),
			mPrice(price),
			mStartPrice(startPrice),
			mReservePrice(reservePrice),
			mHighBidder(highBidder),
			mFeatured(featured),
			mSuperFeatured(superFeatured),
			mBoldTitle(boldTitle),
			mPrivate(privateAuction),
			mRegisteredOnly(registeredOnly),
			mpHost(pHost),
			mVisitCount(visitCount),
			mpPictureURL(pPictureURL),
			mpCategoryName(pCategoryName),
			mpSellerUserId(pSellerUserId),
			mSellerUserState(sellerUserState),
			mSellerUserFlags(sellerUserFlags),
			mpHighBidderUserId(pHighBidderUserId),
			mHighBidderUserState(highBidderUserState),
			mHighBidderUserFlags(highBidderUserFlags),
			mSellerFeedbackScore(sellerFeedbackScore),
			mHighBidderFeedbackScore(highBidderFeedbackScore),
			mSellerIdLastModified(sellerIdLastChange),
			mHighBidderIdLastModified(highBidderIdLastChange),
			mLastModified(lastModified),
			mpRowId(pRowId),
			mDelta(delta),
			mCountryId(countryId),
			mCurrencyId(currencyId),
			mEnded(ended),
			//samuel, 7/15/99
			mBillingCurrencyId(billingCurrencyId),
			mShippingOption(shippingOption), // nsacco 07/27/99
			mShipRegionFlags(shipRegionFlags), // nsacco 07/27/99
			mDescLang(descLang),		// nsacco 07/27/99
			mSiteId(siteId)				// nsacco 07/15/99
		{
			if (pTitle)
			{
				mpTitle		= new char[strlen(pTitle) + 1];
				strcpy(mpTitle, pTitle);
			}
			else
				mpTitle		= NULL;

			if (pDescription)
			{
				mpDescription	= new char[strlen(pDescription) + 1];
				strcpy(mpDescription, pDescription);
			}
			else
				mpDescription	= NULL;

			if (pLocation)
			{
				mpLocation		= new char[strlen(pLocation) + 1];
				strcpy(mpLocation, pLocation);
			}
			else
				mpLocation		= NULL;

			if (pPictureURL)
			{
				mpPictureURL	= new char[strlen(pPictureURL) + 1];
				strcpy(mpPictureURL, pPictureURL);
			}
			else
				mpPictureURL		= NULL;

			if (pCategoryName)
			{
				mpCategoryName	= new char[strlen(pCategoryName) + 1];
				strcpy(mpCategoryName, pCategoryName);
			}
			else
				mpCategoryName		= NULL;

			if (pSellerUserId)
			{
				mpSellerUserId	= new char[strlen(pSellerUserId) + 1];
				strcpy(mpSellerUserId, pSellerUserId);
			}
			else
				mpSellerUserId		= NULL;

			if (pHighBidderUserId)
			{
				mpHighBidderUserId	= new char[strlen(pHighBidderUserId) + 1];
				strcpy(mpHighBidderUserId, pHighBidderUserId);
			}
			else
				mpHighBidderUserId		= NULL;

			if (pHost)
			{
				mpHost	= new char[strlen(pHost) + 1];
				strcpy(mpHost, pHost);
			}
			else
				mpHost		= NULL;

			if (pRowId)
			{
				mpRowId	= new char[strlen(pRowId) + 1];
				strcpy(mpRowId, pRowId);
			}
			else
				mpRowId		= NULL;
			
			mpBidEngine	= NULL;

			mHaveInfo	= false;
			mNoticeTime	= 0;
			mBillTime	= 0;

			mDirty		= false;

			mpItems		= NULL;

			if (pSellerEmail)
			{
				mpSellerEmail = new char [strlen(pSellerEmail) + 1];
				strcpy(mpSellerEmail, pSellerEmail);
			}
			else
				mpSellerEmail = NULL;

			if (pHighBidderEmail)
			{
				mpHighBidderEmail = new char [strlen(pHighBidderEmail) + 1];
				strcpy(mpHighBidderEmail, pHighBidderEmail);
			}
			else
				mpHighBidderEmail = NULL;

			mAdult = 'A';

			if (pIconFlags)
			{
				mpIconFlags = new char[strlen(pIconFlags) + 1];
				strcpy(mpIconFlags, pIconFlags);
			}
			else
				mpIconFlags = NULL;

			if (pGalleryURL)
			{
				mpGalleryURL	= new char[strlen(pGalleryURL) + 1];
				strcpy(mpGalleryURL, pGalleryURL);
			}
			else
				mpGalleryURL		= NULL;

			mpFees = new clsFees(this);

			if (pZip)
			{
				mpZip = new char[strlen(pZip) + 1];
				strcpy(mpZip, pZip);
			}

			return;
		};


		void UpdateItem ();

		//
		// Getters / Setters (not handled by macros)
		//

		// GetMarketPlaceId
		CategoryId		GetCategory();
		void			SetCategory(CategoryId id);
		AuctionTypeEnum	GetAuctionType();
		void			SetAuctionType(AuctionTypeEnum type);

		//Gallery
		GalleryTypeEnum	GetGalleryType();
		void			SetGalleryType(GalleryTypeEnum type);

		//Gallery
		GalleryResultCode	GetGalleryState()
		{
			return mGalleryState;
		}

		void SetGalleryState(GalleryResultCode state)
		{
			mGalleryState = state;
		}

		//
		// GetBids
		//		Gets all the bids for an item
		//
		void GetBids(BidVector *pBids);

		//
		//GetAllBidCount
		//
		int GetAllBidCount();
		//
		// GetFinalBids
		//		Retrieves bids for an item sorted in descending amount
		//
		void GetFinalBids(BidVector *pBids, BidVector *pFinalBids);

		//
		// GetHighestBidForUser
		//		Fills in a vector with all the bids 
		//		for a given user
		//
		clsBid *GetHighBidForUser(int user_id);

		//
		// GetHighestBidsForItem
		//	Returns the two highest bids for an item 
		//
		void GetHighBidsForItem(bool lock,
								clsBid **pHighestBid,
								clsBid **pNextHighestBid);

		void GetHighBidsForItem(bool lock,
								BidVector *pvHighBids);

		void GetBidsForItemSorted(BidVector *pvBids);

		void GetDutchHighBidders(BidVector *pvBids, bool ArchiveVersion=false);

		//
		// Return true if user with given ID is a high bidder on this item;
		// return false otherwise
		//
		bool IsDutchHighBidder(int id);

		void RecomputeDutchBids();

		//
		// GetBidders
		//
		void GetBidders(list<int> *plBidders);

		//
		// Bid
		//
		void Bid(int user, int quantity, double maxBid, time_t bidTime);
		void Bid(clsBid *pBid);

		//
		// RetractBids
		//	Retracts all bids for a user
		//
		void RetractBids(int user);

		//
		// CancelBids
		//	Cancels (which is the same as retracts, except
		//	that the seller does it) all bids for a user
		//
		void CancelBids(int user);

		//
		// BlockBid
		//	Blocks the given bid (i.e., moves it to the
		//  blocked bids table)
		//
		void BlockBid(clsBid *pBid);


		// Dirty flag
		bool	IsDirty();
		void	SetDirty(bool dirty);

		//
		// SetTitle
		//
		void SetNewTitle(char *pTitle);

		//
		// SetNewHighBidder
		//
		void SetNewHighBidder(int user, double price);
		void SetNewHighBidderAndBidCount(int user, double price, int bidcount);

		//
		// SetNewBidCount
		//
		void SetNewBidCount(int count);

		//
		// SetNewDescription
		//
		void SetNewDescription(char *pDescription);

		//
		// SetNewEndTime
		//
		void SetNewEndTime(long newTime);

		//
		// SetNewFeatured
		//
		void SetNewFeatured(bool isIt);

		//
		// SetNewSuperFeatured
		//
		void SetNewSuperFeatured(bool isIt);

		//
		// SetNewCategory
		//
		void SetNewCategory(CategoryId category);

		//
		// Set
		//		Set is used to reuse an existing clsItem
		//		object. It's intended for clients that 
		//		access a lot of items and want to avoid
		//		the overhead of creating and destroying
		//		the object.
		// 
		// nsacco 07/27/99 new params
		void Set(MarketPlaceId marketPlaceid, 
				 int id,
				 AuctionTypeEnum auctionType,
				 char *pTitle,
				 char *pDescription,
				 char *pLocation,
				 int seller,
				 int owner,
				 CategoryId category,
				 int bidCount,
				 int quantity, 
				 long startTime,
				 long endTime,
				 long status, 
				 double price,
				 double startPrice,
				 double reservePrice,
				 int highBidder,
				 bool featured,
				 bool superFeatured,
				 bool boldTitle,
				 bool privateAuction,
				 bool registeredOnly,
				 char *pHost,
				 int visitCount,
				 char *pPictureURL,
				 char *pCategoryName,
				 char *pSellerUserId,
				 int sellerUserState,
				 int sellerUserFlags,
				 char *pHighBidderUserId,
				 int highBidderUserState,
				 int highBidderUserFlags,
				 int sellerFeedbackScore,
				 int highBidderFeedbackScore,
				 long sellerIdLastChange,
				 long highBidderIdLastChange,
				 long lastModified,
				 const char *pSellerEmail = NULL,
				 const char *pHighBidderEmail = NULL,
				 int password = 0,
				 char *pRowId = 0,
				 long delta = 0,
				 char *pIconFlags = NULL,
				 char *pGalleryURL =NULL,
				 GalleryTypeEnum galleryType = NoneGallery,
				 GalleryResultCode galleryState	= kGalleryNotProcessed,
				 int countryId = Country_None,
				 int currencyId = Currency_USD,
				 bool ended = false,
				 char *pZip=NULL,
				 //samuel, 7/15/99
				 int billingCurrencyId = Currency_USD,
				 int shippingOption = SiteOnly,
				 long shipRegionFlags = ShipRegion_None,
				 int descLang = English,
				 int siteId = SITE_EBAY_MAIN		// nsacco 07/27/99
				 );

		// sets description without setting dirty flag

		void SetDesc(MarketPlaceId marketPlaceid, 
				 int id,
				 char *pDescription);

		// add new high bidder for dutch auction without setting dirty flag
		void SetDutchHighBidder(clsBid *hibidder);

		// deletes all high bidders associated with an item
		void DeleteDutchHighBidder();

		// nsacco 07/27/99
		// new functions
		long SetShipToRegion(long mask, bool on);
		bool IsShippingToRegion(unsigned long bit);
		long GetShipRegionFlags();
		int GetShippingOption();
		void SetShippingOption(int theNewShippingOption);
		bool IsShippingToSiteOnly();
		bool IsShippingToSiteAndRegions();

		CategoryId		mCategory;
		AuctionTypeEnum	mAuctionType;

		IINT_VARIABLE(MarketPlaceId);
		IINT_VARIABLE(Id);
		ISTRING_VARIABLE(Title);
		ISTRING_VARIABLE(Description);
		ISTRING_VARIABLE(Location);
		IINT_VARIABLE(Seller);
		IINT_VARIABLE(Owner);
		IINT_VARIABLE(Password);
		IINT_VARIABLE(Quantity);
		IINT_VARIABLE(BidCount);
		ILONG_VARIABLE(StartTime);
		ILONG_VARIABLE(EndTime);
		ILONG_VARIABLE(Status);
		IDOUBLE_VARIABLE(Price);
		IDOUBLE_VARIABLE(StartPrice);
		IDOUBLE_VARIABLE(ReservePrice);
		IINT_VARIABLE(HighBidder);
		IBOOL_VARIABLE(Featured);
		IBOOL_VARIABLE(SuperFeatured);
		IBOOL_VARIABLE(BoldTitle);
		IBOOL_VARIABLE(Private);
		IBOOL_VARIABLE(RegisteredOnly);
		ISTRING_VARIABLE(Host);
		IINT_VARIABLE(VisitCount);
		ISTRING_VARIABLE(PictureURL);
		ILONG_VARIABLE(LastModified);  // setter value not used
		ICHAR_VARIABLE(Adult);
		ISTRING_VARIABLE(IconFlags);

		GalleryTypeEnum	mGalleryType;
		GalleryResultCode mGalleryState;
		ISTRING_VARIABLE(GalleryURL);


		//  The following variables are only filled in
		//	if the Item is "populated"
		ISTRING_VARIABLE(CategoryName);
		ISTRING_VARIABLE(SellerUserId);
		IINT_VARIABLE(SellerUserState);
		IINT_VARIABLE(SellerFeedbackScore);
		IINT_VARIABLE(SellerUserFlags);
		ILONG_VARIABLE(SellerIdLastModified);
		ISTRING_VARIABLE(HighBidderUserId);
		IINT_VARIABLE(HighBidderUserState);
		IINT_VARIABLE(HighBidderFeedbackScore);
		IINT_VARIABLE(HighBidderUserFlags);
		ILONG_VARIABLE(HighBidderIdLastModified);

		ISTRING_VARIABLE(SellerEmail);
		ISTRING_VARIABLE(HighBidderEmail);

		ILONG_VARIABLE(Delta);
		ISTRING_VARIABLE(RowId);
		IINT_VARIABLE(CountryId);
		IINT_VARIABLE(CurrencyId);
		IBOOL_VARIABLE(Ended);

		ISTRING_VARIABLE(Zip);

		//samuel, 7/15/99
		IINT_VARIABLE(BillingCurrencyId);

		// nsacco 07/27/99
		// Note: the Get/Set methods for ShippingOptions and ShipRegionFlags must
		// be manually coded.
		IINT_VARIABLE(DescLang);

		// nsacco 07/15/99
		IINT_VARIABLE(SiteId);

		// Bidding Proxies
		clsBidEngine	*GetBidEngine();
		clsBidResult	*ProposeBid(
							clsBid *pBid,
							ostream *pStream = NULL,
							clsUser *pUser = NULL);

		clsBidResult	*AcceptBid(
							clsBid *pBid,
							ostream *pStream = NULL,
							clsUser *pUser = NULL);

		// vector is for dutch high bidders;
		void AdjustPrice(BidVector *pvHighBids = NULL);

		// Noticed
		long GetNoticeTime();
		void SetNoticeTime(long when);
		long GetDBNoticeTime();

		// Item Billed
		long GetBillTime();
		long GetDBBillTime();
		void SetBillTime(long when);
		void SetDBDutchGMS(float sold_price);

		// Sale Status manipulators
		void SetNoFinalValueFee(bool value);
		bool ChargeNoFinalValueFee();

		// seller bid once flag manipulator
		void SetItemBidBySellerFlag(bool value);
		bool GetItemBidBySellerFlag();
		
		//// returns true if auction is Gallery or FeaturedGallery
		bool IsGallery();
		bool IsFeaturedGallery();

		// item finalized flag
		void SetItemBidsFinalizedFlag(bool value);
		bool GetItemBidsFinalizedFlag();
		void Finalize();

		//wacko flag manipulator
		void SetItemWackoFlag(bool value);
		bool IsItemWacko();

		// Credit Stuff
		bool HasInsertionCredit();
		void SetHasInsertionCredit(bool doesIt);

		bool HasCategoryFeaturedCredit();
		void SetHasCategoryFeaturedCredit(bool doesIt);

		bool HasGiftIconCredit();
		void SetHasGiftIconCredit(bool doesIt);

		bool HasFeaturedCredit();
		void SetHasFeaturedCredit(bool doesIt);

		bool HasBoldCredit();
		void SetHasBoldCredit(bool doesIt);

		bool HasFVFCredit();
		void SetHasFVFCredit(bool doesIt);

		bool HasNoSaleCredit();
		void SetHasNoSaleCredit(bool doesIt);

		// adult stuff
		bool IsAdult();					// adult items are really strict. no bidding, listing, or viewing
		bool NoBidAndListForMinor();	// not adult per se, but still can't bid or list (e.g. firearms)


		// gift stuff
		int GetGiftIconType();
		//Gallery credit
		bool HasGalleryCredit();
		void SetHasGalleryCredit(bool doesIt);
		//Featured gallery credit
		bool HasFeaturedGalleryCredit();
		void SetHasFeaturedGalleryCredit(bool doesIt);

		const char *GetGalleryStateMessage();

		//
		// GetBidIncrement
		//		Returns the automatic bid increment amount
		//		for the item's price.
		double GetBidIncrement(double price = 0);

		//
		// GetInsertionFee
		//		Returns the insertion fee for an item
		//
		double GetInsertionFee(double price = 0);
		//
		// GetBoldFee
		//		Returns the bolding fee for an item
		//
		double GetBoldFee(double dPrice = 0);

		//
		// GetFeaturedFee
		//
		double GetFeaturedFee(time_t when = 0);
		double GetCategoryFeaturedFee(time_t when = 0);
		double GetGiftIconFee(int icon = 0);
		double GetGalleryFee();
		double GetFeaturedGalleryFee();
		double GetItemMoveFee();
		//
		// GetListingFee (commission)
		//
		double GetListingFee(double price = 0);
		double GetListingFee(double price, int category);

// Lena T's and C's
		void SetPaymentMOCashiers(bool accepts);
		void SetPaymentPersonalCheck(bool accepts);
		void SetPaymentAmEx(bool accepts);
		void SetPaymentVisaMaster(bool accepts);
		void SetPaymentDiscover(bool accepts);
		void SetPaymentOther(bool accepts);
		void SetPaymentEscrow(bool accepts);
		void SetPaymentCOD(bool accepts);
		void SetPaymentSeeDescription(bool checked);
		void SetSellerPaysShipping(bool does);
		void SetBuyerPaysShippingFixed(bool does);
		void SetBuyerPaysShippingActual(bool does);
		void SetShippingSeeDescription(bool checked);
		// nsacco 07/27/99 removed SetShippingToCanada()
		void SetShippingInternationally(bool checked);
		bool AcceptsPaymentMOCashiers();
		bool AcceptsPaymentPersonalCheck();
		bool AcceptsPaymentVisaMaster();
		bool AcceptsPaymentDiscover();
		bool AcceptsPaymentAmEx();
		bool AcceptsPaymentOther();
		bool AcceptsPaymentEscrow();
		bool AcceptsPaymentCOD();
		bool SellerPaysForShipping();
		bool BuyerPaysForShippingActual();
		bool BuyerPaysForShippingFixed();
		bool MorePaymentSeeDescription();
		bool MoreShippingSeeDescription();
		// nsacco 07/27/99 removed IsShippingToCanada()
		bool IsShippingInternationally();
		void TsAndCsSet();

// Sam, 01/20/99, Auto Credit Stuff
		bool SetItemCredit(CreditsVector *pvCredits);
		void GetAllItemCredits(int item_id, CreditsVector *pvCredits);
		bool isDeadbeatCreditReq(CreditTypeEnum reason);

		bool  CheckForAutomotiveListing(int nCategory=0);
		bool CheckForRealEstateListing(int nCategory=0);
		//
		// regional stuff
		//
		int	GetRegionID();

		double GetFVFConversionRate();


		//
		// HACK
		//
		clsItems		*mpItems;
	private:


		clsBidEngine	*mpBidEngine;

		bool			mHaveInfo;
		long			mNoticeTime;
		long			mBillTime;

		bool			mDirty;

		// See eBayTypes for enum to index into these arrays.

		double eBayFeesUS[FeeEnumSize];
		double eBayFeesUK[FeeEnumSize];

		clsFees		   *mpFees;

		// nsacco 07/27/99
		int				mShippingOption;
		long			mShipRegionFlags;
};

//
// This thing is used for STL
//
class clsItemPtr
{
	public:
		//
		// CTOR
		//
		clsItemPtr()
		{
			mpItem	=	NULL;
		}

		clsItemPtr(clsItem *pItem)
		{
			mpItem	=	pItem;
		}

		~clsItemPtr()
		{
			return;
		}

		//
		//	"<" operator
		//
		int operator<(clsItemPtr &pOther);

		clsItem		*mpItem;

};


// Convienent Typedefs
typedef vector<clsItem *> ItemVector;

typedef list<clsItemPtr> ItemList;

//inna - like clsItemRowId but with id as well
class clsItemIdRowId
{
	public:

		// Constructors
		clsItemIdRowId() {;}

		clsItemIdRowId(char* pRowId,int ItemId) 
		{ 
			mRowId	= new char[20];
			strcpy(mRowId,pRowId);
			mItemId=ItemId;
		}

		// destructor
		~clsItemIdRowId()
		{
				delete	[] mRowId;
		}


		// Gets
		char*	GetRowId(){return mRowId;}
		int		GetItemId(){return mItemId;}

		// Sets
		void	SetRowId(char* pRowId)
		{	
			strcpy(mRowId, pRowId); 
		}
		
		void	SetItemId(int ItemId)
		{
			mItemId=ItemId;
		}
		

	private:
		int			mItemId;
		char		*mRowId;
};

#define CLSITEM_INCLUDED
#endif /* CLSITEM_INCLUDED */



