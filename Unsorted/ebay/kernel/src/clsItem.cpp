/*	$Id: clsItem.cpp,v 1.17.2.3.4.4 1999/08/04 16:51:34 nsacco Exp $	*/
//
//	File:	clsItem.cpp
//
//	Class:	clsItem
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Represents an item
//
// Modifications:
//				- 02/10/97 michael	- Created
//				- 06/13/97 tini     - added dirty flag; removed itemXref;
//				- 07/05/97 tini		- added get dutch high bidders;
//				- 09/29/97 wen		- added password + modified Set()
//				- 07/15/99 nsacco	- added siteId
//				- 07/27/99 nsacco	- new params for Set() and member variables and
//										constructor. SetShipToRegion().
//
#pragma warning( disable : 4786 )
#include "eBayKernel.h"

#include "clsBidEngineChinese.h"
#include "clsBidEngineDutch.h"
#include "clsCurrencyWidget.h"
#include "clsExchangeRates.h"
#include "hash_map.h"
#include "clsRegions.h"

// A nice little macro
// setImmed##variable assumes the item already exists in the database 
// with the correct marketplace and id and immediately updates the item
// in the database
//
#define ISTRING_METHODS(variable)				\
char *clsItem::Get##variable()					\
{												\
	return mp##variable;						\
}												\
void clsItem::Set##variable(char *pNew)			\
{												\
	if (mp##variable)							\
		delete mp##variable;					\
	mp##variable = new char[strlen(pNew) + 1];	\
	strcpy(mp##variable, pNew);					\
	mDirty = true;								\
	return;										\
}												\
void clsItem::SetImmed##variable(char *pNew)	\
{												\
	if (mp##variable)							\
		delete mp##variable;					\
	mp##variable = new char[strlen(pNew) + 1];	\
	strcpy(mp##variable, pNew);					\
	gApp->GetDatabase()->UpdateItem(this);		\
	mDirty = false;								\
	return;										\
}

#define IINT_METHODS(variable)					\
int clsItem::Get##variable()					\
{												\
	return m##variable;							\
}												\
void clsItem::Set##variable(int newval)			\
{												\
	m##variable	= newval;						\
	mDirty = true;								\
	return;										\
}												\
void clsItem::SetImmed##variable(int newval)	\
{												\
	m##variable	= newval;						\
	gApp->GetDatabase()->UpdateItem(this);		\
	mDirty = true;								\
	return;										\
} 

#define IDOUBLE_METHODS(variable)				\
double clsItem::Get##variable()					\
{												\
	return m##variable;							\
}												\
void clsItem::Set##variable(double newval)		\
{												\
	m##variable	= newval;						\
	mDirty = true;								\
	return;										\
}												\
void clsItem::SetImmed##variable(double newval)	\
{												\
	m##variable	= newval;						\
	gApp->GetDatabase()->UpdateItem(this);		\
	mDirty = true;								\
	return;										\
} 

#define ILONG_METHODS(variable)					\
long clsItem::Get##variable()					\
{												\
	return m##variable;							\
}												\
void clsItem::Set##variable(long newval)		\
{												\
	m##variable	= newval;						\
	mDirty = true;								\
	return;										\
} 												\
void clsItem::SetImmed##variable(long newval)	\
{												\
	m##variable	= newval;						\
	gApp->GetDatabase()->UpdateItem(this);		\
	mDirty = true;								\
	return;										\
} 

#define IBOOL_METHODS(variable)					\
bool clsItem::Get##variable()					\
{												\
	return m##variable;							\
}												\
void clsItem::Set##variable(bool newval)		\
{												\
	m##variable	= newval;						\
	mDirty = true;								\
	return;										\
} 												\
void clsItem::SetImmed##variable(bool newval)	\
{												\
	m##variable	= newval;						\
	gApp->GetDatabase()->UpdateItem(this);		\
	mDirty = true;								\
	return;										\
} 

static const char *sGalleryResultCodeMessages[] =
{
	"Your image has not yet been added to the Gallery.",
	"Your image has successfully been added to the Gallery.",
	"The URL for your image does not appear to be valid.",
	"The URL for your image does not appear to start with http://",
	"There seems to be a problem with the file containing your image.",
	"We could not find the server containing your image.",
	"The server containing your image was unavailable when we tried to retrieve it.",
	"We could not find your Gallery image when we went to retrieve it.",
	"The image failed to come across the Internet when we tried to retrieve it.",
	"The file format containing your image appears to not be jpeg, bmp, or tif.",
	"We were not able to process your image.",
	"We were not able to process your image.",
	"Please contact customer support at support@ebay.com for more information about your Gallery image." /* generic msg */
};

char clsItem::GetAdult()
{
	if (mAdult == 'A')
	{
		mAdult = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCategories()->GetCategory(mCategory, true)->GetAdult();
	}

	return mAdult;
}
void clsItem::SetAdult(char newval)	
{
	mAdult	= newval;
	mDirty = true;
	return;
}

// update
void clsItem::UpdateItem()
{
	if (mDirty)
	{
		gApp->GetDatabase()->UpdateItem(this);
		// check if item has description!
		gApp->GetDatabase()->UpdateItemDesc(this);
		mDirty = false;
	};
};


//
// GetCategory
//
CategoryId clsItem::GetCategory()
{
	return mCategory;
}

//
// SetCategory
void clsItem::SetCategory(CategoryId id)
{
	mCategory	= id;
	mDirty = true;
	return;
}

// GetAuctionType
AuctionTypeEnum clsItem::GetAuctionType()
{
	return mAuctionType;
}


// SetAuctionType
void clsItem::SetAuctionType(AuctionTypeEnum type)
{
	mAuctionType	= type;
	mDirty = true;
}

// GetGalleryType
GalleryTypeEnum clsItem::GetGalleryType()
{
	return mGalleryType;
}

// SetGalleryType
void clsItem::SetGalleryType(GalleryTypeEnum type)
{
	mGalleryType	= type;
	mDirty = true;
}
//which gift icon ?
int clsItem::GetGiftIconType()
{
	if (mpIconFlags == NULL )
	   return GiftIconUnknown;
	
	if (*mpIconFlags == 'g')
		return Father;

  return atoi(mpIconFlags);
}
/*
bool clsItem::IsAGift()
{
   char *pdest;
   if (mpIconFlags == NULL )
	   return false;
   pdest = strrchr( mpIconFlags, 'g' );
   if (pdest != NULL)
	   return true;
   return false;
} */

//
// Set
//		Set is used to reused an existing clsItem
//		object. It's intended for clients that 
//		access a lot of items and want to avoid
//		the overhead of creating and destroying
//		the object.
// 
// nsacco 07/27/99 new params
void clsItem::Set(MarketPlaceId marketPlaceId, 
				  int id, 
				  AuctionTypeEnum(auctionType),
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
				  long lastUpdate,
				  const char *pSellerEmail,
				  const char *pHighBidderEmail,
				  int password /*=0*/,
				  char *pRowId,
				  long delta,
				  char *pIconFlags,
				  char *pGalleryURL,
				  GalleryTypeEnum(galleryType),
				  GalleryResultCode galleryState,
				  int  countryId,
				  int  currencyId, 
				  bool ended,
				  char *pZip,
				  int  billingCurrencyId,	// samuel
				  int shippingOption,
				  long shipRegionFlags,
				  int descLang,
				  int siteId
				  )
{
	char						*pCategory;

	mMarketPlaceId				= marketPlaceId;
	mId							= id;
	mAuctionType				= auctionType;
	mpTitle						= pTitle;
	mpDescription				= pDescription;
	mpLocation					= pLocation;
	mSeller						= seller;
	mOwner						= owner;
	mCategory					= category;
	mBidCount					= bidCount;
	mQuantity					= quantity;
	mStartTime					= startTime;
	mEndTime					= endTime;
	mStatus						= status;
	mPrice						= price;
	mStartPrice					= startPrice;
	mHighBidder					= highBidder;
	mReservePrice				= reservePrice;
	mFeatured					= featured;
	mSuperFeatured				= superFeatured;
	mBoldTitle					= boldTitle;
	mPrivate					= privateAuction;
	mRegisteredOnly				= registeredOnly;
	mpHost						= pHost;
	mVisitCount					= visitCount;
	mpPictureURL				= pPictureURL;
	mpSellerUserId				= pSellerUserId;
	mSellerUserState			= sellerUserState;
	mSellerUserFlags			= sellerUserFlags;
	mpHighBidderUserId			= pHighBidderUserId;
	mHighBidderUserState		= highBidderUserState;
	mHighBidderUserFlags		= highBidderUserFlags;
	mSellerFeedbackScore		= sellerFeedbackScore;
	mHighBidderFeedbackScore	= highBidderFeedbackScore;
	mSellerIdLastModified		= sellerIdLastChange;
	mHighBidderIdLastModified	= highBidderIdLastChange;
	mLastModified				= lastUpdate;
	mPassword					= password;
	mpRowId						= pRowId;
	mDelta						= delta;
	mpGalleryURL				= pGalleryURL;
	mGalleryType				= galleryType;
	mGalleryState				= galleryState;
	mpIconFlags					= pIconFlags;
	mCountryId					= countryId;
	mCurrencyId				    = currencyId;
	mEnded						= ended;
	mpZip						= pZip;
	//samuel, 7/15/99
	mBillingCurrencyId			= billingCurrencyId;
	// nsacco 07/27/99
	mShippingOption				= shippingOption;
	mShipRegionFlags			= shipRegionFlags;
	mDescLang					= descLang;
	mSiteId						= siteId;

	
	if (pSellerEmail)
	{
		delete [] mpSellerEmail;
		mpSellerEmail = new char [strlen(pSellerEmail) + 1];
		strcpy(mpSellerEmail, pSellerEmail);
	}

	if (pHighBidderEmail)
	{
		delete [] mpHighBidderEmail;
		mpHighBidderEmail = new char [strlen(pHighBidderEmail) + 1];
		strcpy(mpHighBidderEmail, pHighBidderEmail);
	}

	// Strip leading colons (':') from category name
	pCategory	= pCategoryName;

	if (pCategory)
	{
		for (;
			 *pCategory == ':';
			 pCategory++)
		{
			;
		}
	}
	else
		pCategory	= "";

	mpCategoryName	= new char[strlen(pCategory) + 1];
	strcpy(mpCategoryName, pCategory);

	if (pCategoryName)
		delete [] pCategoryName;

	delete mpFees; // Delete any previous one.
	mpFees = new clsFees(this);

	return;
};





void clsItem::SetDesc(MarketPlaceId marketPlaceId, 
				  int id, 
				  char *pDescription)
{
	mMarketPlaceId				= marketPlaceId;
	mId							= id;
	mpDescription				= pDescription;

	return;
};

void clsItem::SetDutchHighBidder(clsBid *hibidder)
{
	gApp->GetDatabase()->SetDutchHighBidder(this, hibidder);
};

void clsItem::DeleteDutchHighBidder()
{
	gApp->GetDatabase()->DeleteDutchHighBidder(this);
};


IINT_METHODS(MarketPlaceId);
IINT_METHODS(Id);
ISTRING_METHODS(Title);
// should description be special methods to go to ebay_item_desc directly?
// or have indicator to decide if its dirty or if its even loaded?
ISTRING_METHODS(Description);
ISTRING_METHODS(Location);
IINT_METHODS(Seller);
IINT_METHODS(Owner);
IINT_METHODS(Password);
IINT_METHODS(Quantity);
IINT_METHODS(BidCount);
ILONG_METHODS(StartTime);
ILONG_METHODS(EndTime);
ILONG_METHODS(Status);
IDOUBLE_METHODS(Price);
IDOUBLE_METHODS(StartPrice);
IDOUBLE_METHODS(ReservePrice);
IINT_METHODS(HighBidder);
IBOOL_METHODS(Featured);
IBOOL_METHODS(SuperFeatured);
IBOOL_METHODS(BoldTitle);
IBOOL_METHODS(Private);
IBOOL_METHODS(RegisteredOnly);
ISTRING_METHODS(Host);
IINT_METHODS(VisitCount);
ISTRING_METHODS(PictureURL);
ISTRING_METHODS(IconFlags);
ISTRING_METHODS(GalleryURL);
IINT_METHODS(CountryId);
IINT_METHODS(CurrencyId);
IBOOL_METHODS(Ended);
ISTRING_METHODS(Zip);
//samuel, 7/19/99
IINT_METHODS(BillingCurrencyId);
// nsacco 07/27/99
// NOTE: methods for ShippingOption and ShipRegionFlags are coded manually
IINT_METHODS(DescLang);
// nsacco 07/15/99
IINT_METHODS(SiteId);


//  The following variables are only filled in
//	if the Item is "populated"
ISTRING_METHODS(CategoryName);
ISTRING_METHODS(SellerUserId);
ISTRING_METHODS(SellerEmail);
IINT_METHODS(SellerUserState);
IINT_METHODS(SellerFeedbackScore);
ISTRING_METHODS(HighBidderUserId);
ISTRING_METHODS(HighBidderEmail);
IINT_METHODS(HighBidderUserState);
IINT_METHODS(HighBidderFeedbackScore);
ILONG_METHODS(HighBidderIdLastModified);
ILONG_METHODS(SellerIdLastModified);
ISTRING_METHODS(RowId);
ILONG_METHODS(Delta);

IINT_METHODS(SellerUserFlags);
IINT_METHODS(HighBidderUserFlags);


//
// IsDirty
//
bool clsItem::IsDirty()
{
	return mDirty;
}

//
// SetDirty
//
void clsItem::SetDirty(bool dirty)
{
	mDirty	= dirty;
	return;
}

//
// GetBids
//		Retrieves bids for an item
//
void clsItem::GetBids(BidVector *pBids)
{
	gApp->GetDatabase()->GetBids(
							mMarketPlaceId,
							mId,
							pBids,
							mEnded	);
	return;
};

//
// GetHighBidForUser
//		Returns the highest bid for a user
//
clsBid *clsItem::GetHighBidForUser(int user_id)
{
	return gApp->GetDatabase()->GetHighestBidForUser(
										mMarketPlaceId,
										mId,
										user_id
													);
}

//
// GetHighestBidsForItem - return highest 2 bids
//
void clsItem::GetHighBidsForItem(
					  bool lock,
					  clsBid **ppHighestBid,
					  clsBid **ppNextHighestBid)
{
	gApp->GetDatabase()->GetHighestBidsForItem(
										lock,
										mMarketPlaceId,
										mId,
										ppHighestBid,
										ppNextHighestBid,
										mEnded
											 );
	return;
}

//
// GetHighestBidsForItem - return vector of high bidders
//
void clsItem::GetHighBidsForItem(
					  bool lock,
					  BidVector *pvHighBids)
{
	gApp->GetDatabase()->GetHighestBidsForItem(
										lock,
										mMarketPlaceId,
										mId,
										mQuantity,
										pvHighBids,
										mEnded);
	return;
}
//get item bidcount include the canceled and retracted bids
int clsItem::GetAllBidCount()
{

	return gApp->GetDatabase()->GetItemBids(mMarketPlaceId,mId, mEnded);
}

//
// GetBidsForItemSorted 
//
//	Fills in the passed vector with a list of clsBids.
//	To save time on the database side, we do the "work"
//	of sorting here.
//
//
// Sort method
//
//
// sort_bid_user
//
//	A private sort routine to group all bids
//	from a user together, ordered by time.
//
static bool sort_bid_user_value(clsBid *pA, clsBid *pB)
{
	if (pA->mUser < pB->mUser)
	{
		return true;
	}
	if (pA->mUser == pB->mUser)
	{
		if (pA->mValue > pB->mValue)
			return true;

		if (pA->mValue == pB->mValue)
		{
			if (pA->mTime < pB->mTime)
				return true;
		}
	}

	return false;
}

//	Sorts the bids value decending, time ascending,
//	so the higest, soonest bid is first
//
static bool sort_bid_amount(clsBid *pA, clsBid *pB)
{
	if (pA->mAmount > pB->mAmount)
		return true;

	if (pA->mAmount == pB->mAmount)
	{
		if (pA->mTime < pB->mTime)
			return true;
	}
	return false;
}

// sort by bid amount and total value
static bool sort_bid_amount_qty(clsBid *pA, clsBid *pB)
{
	if (pA->mAmount > pB->mAmount)
		return true;

	if (pA->mAmount == pB->mAmount)
	{
		if (pA->mQuantity > pB->mQuantity)
			return true;

		if (pA->mQuantity == pB->mQuantity)
		{
			if (pA->mTime < pB->mTime)
				return true;
		}
	}
	return false;
}

// sorted by bid amount and bid date
void clsItem::GetBidsForItemSorted(BidVector *pvHighBids)
{
	BidVector				vAllBids;
	BidVector::iterator		vI;


	gApp->GetDatabase()->GetBids(mMarketPlaceId,
								 mId,
								 &vAllBids, mEnded);

	// Eliminate irrelevant entries
	for (vI	= vAllBids.begin();
		 vI != vAllBids.end();
		 vI++)
	{
		if ((*vI)->mAction == BID_BID ||
			(*vI)->mAction == BID_DUTCH_BID)
		{
			pvHighBids->push_back(*vI);
		}
		else
			delete (*vI);
	}

	// Erase so later desctruction of vector doesn't
	// destroy bids
	vAllBids.erase(vAllBids.begin(), vAllBids.end());

	// Sort remainin
	if (pvHighBids->size() > 0)
		sort(pvHighBids->begin(), pvHighBids->end(), sort_bid_amount);

	return;
}


// get all the high bidders into the vector
// in dutch auction, all bidders get the item at the lowest bid price!
//
void clsItem::GetDutchHighBidders(BidVector *pvBids, bool ArchiveVersion)
{

// use of table is commented out because of problems with transaction logic
//	gApp->GetDatabase()->GetDutchHighBidders(mMarketPlaceId, mId, pvBids);

	double						proposedNewPrice;
	BidVector::iterator			vI;
	clsBid						*pHighBid;
	clsBid						*pBid;
	int							total_qty = 0;
	int							item_qty = GetQuantity();

	BidVector					*pvOwnBids;
	BidVector				vAllBids;

	// the hash map of people who have high bids for this item
	hash_map<const int, int, hash<int>, eqint>
		bidTracks;

	// hasherator
	hash_map<const int, int, hash<int>, eqint>::
		const_iterator			ii;

	if (ArchiveVersion)
		gApp->GetDatabase()->GetBidsArc(mMarketPlaceId,
								 mId,
								 &vAllBids);
	else
		gApp->GetDatabase()->GetBids(mMarketPlaceId,
								 mId,
								 &vAllBids, mEnded);

	// initialize pvOwnBids containing highest winning bids per user
	pvOwnBids = new BidVector;

	// sort by user and value of bid to get highest per user
	if (vAllBids.begin() != vAllBids.end())
		sort(vAllBids.begin(), vAllBids.end(), sort_bid_user_value);

	// Eliminate irrelevant entries
	for (vI	= vAllBids.begin();
		 vI != vAllBids.end();
		 vI++)
	{
		// only valid bids
		if ((*vI)->mAction == BID_BID ||
			(*vI)->mAction == BID_DUTCH_BID)
		{
			// push only the first one of this user
			 pBid = (*vI);

			 // let's see if we've seen this user before
			 ii = bidTracks.find((const int)(pBid->mUser));

			 // if not, make a tracker 
			 // and use the record as high bidder, since its sorted
			 // adjust the total_qty by the amount
			 if (ii == bidTracks.end())
			 {
				 bidTracks[pBid->mUser] = 0;
				 pvOwnBids->push_back(pBid);
			 }

			 else
				 // found user before; delete
				 delete pBid;
		}
		else
			// not bids, only retractions/cancellations. Delete
			delete (*vI);
	 }

	// Erase so later desctruction of vector doesn't
	// destroy bids
	vAllBids.erase(vAllBids.begin(), vAllBids.end());

	// clean up
	bidTracks.erase(bidTracks.begin(),
						 bidTracks.end());

	// if there aren't any non-retracted bids, there isn't any high bidder
	if (pvOwnBids->size() == 0)
		return;

	// Sort remaining if any
	if (pvOwnBids->begin() != pvOwnBids->end())
		sort(pvOwnBids->begin(), pvOwnBids->end(), sort_bid_amount);

	// prep the high bidder's table by deleting all entries for the item
//	mpItem->DeleteDutchHighBidder();

	// iterate through pVBids for the winning bids
	// till quantity is exhausted
	for (vI = pvOwnBids->begin();
		 vI != pvOwnBids->end();
		 vI++)
	{
			pBid = (*vI);

			if (total_qty < item_qty)
			{
				// insert into result vector, erase from original vector
				pvBids->push_back(pBid);
				
				// increment quantity - accounted for in the bid
				total_qty = total_qty + pBid->mQuantity;				 
			}
			else
				delete pBid;
			 
	 };

	// current price = lowest winning bid or start price if qty is not exhausted
	if (vI != pvOwnBids->end() && (total_qty >= item_qty))
	{
		proposedNewPrice = (*vI)->mAmount;
		// Round
		proposedNewPrice	= RoundToCents(proposedNewPrice);

		pHighBid = (*vI);
		// Set new high bidder and price in item
//		mpItem->SetNewHighBidder(pHighBid->mUser,
//								 proposedNewPrice);
	}
	else 
		proposedNewPrice	=
			GetStartPrice();

	// set proposed new price to item
	// because of the nature of dutch auctions and bids, we don't do this
	// high bidders will be recalculated each time


	// clean up our own vector, don't delete the bids because
	// its used by another.
	pvOwnBids->erase(pvOwnBids->begin(), pvOwnBids->end());

	delete	pvOwnBids;

	return;	
};

//
// Returns true if user with given ID is one of the item's high bidders;
// returns false otherwise.
//
bool clsItem::IsDutchHighBidder(int id)
{
	BidVector 					vBidders;
	BidVector::const_iterator	i;
	bool						found = false;

	// Let's get the bidders
	GetDutchHighBidders(&vBidders);

	if (vBidders.size() > 0)
	{
		for (i = vBidders.begin();
			 i != vBidders.end();
			 ++i)
		{
			if (id == (*i)->mUser)
			{
				found = true;
				break;
			}
		}
	}
	
	vBidders.erase(vBidders.begin(), vBidders.end());

	return found;
}

//
// Recalculates the item's bidcount, current price and high bidder
// from actual tables
//
void clsItem::RecomputeDutchBids()
{
	double						proposedNewPrice;
	BidVector::iterator			vI;
	clsBid						*pHighBid;
	clsBid						*pBid;
	int							total_qty = 0;
	int							item_qty = GetQuantity();

	// calculated bid count
	int							bcount; 

	// highest winning bid per user
	BidVector					*pvOwnBids;

	// all valid bids
	BidVector					*pvAllBids;

	int							max_qty = 100000;  // kludge

	int							*pBidderIds;
	int							i;

	// the hash map of people who have high bids for this item
	hash_map<const int, int, hash<int>, eqint>
		bidTracks;

	// hasherator
	hash_map<const int, int, hash<int>, eqint>::
		const_iterator			ii;

	if (mAuctionType != AuctionDutch)
		return;

	pvAllBids = new BidVector;

	// get all valid bids for item
	gApp->GetDatabase()->GetHighestBidsForItem(
										false,
										mMarketPlaceId,
										mId,
										max_qty,
										pvAllBids, mEnded
											 );

	// size of the vector = number of bids for the item
	bcount = pvAllBids->size();

	// initialize pvOwnBids containing highest winning bids per user
	pvOwnBids = new BidVector;

	// sort by user and value of bid to get highest per user
	if (pvAllBids->begin() != pvAllBids->end())
		sort(pvAllBids->begin(), pvAllBids->end(), sort_bid_user_value);

	// Get highest bid per user sorted
	for (vI	= pvAllBids->begin();
		 vI != pvAllBids->end();
		 vI++)
	{
			// push only the first one of this user
			 pBid = (*vI);

			 // let's see if we've seen this user before
			 ii = bidTracks.find((const int)(pBid->mUser));

			 // if not, make a tracker 
			 // and use the record as high bidder, since its sorted
			 // adjust the total_qty by the amount
			 if (ii == bidTracks.end())
			 {
				 bidTracks[pBid->mUser] = 0;
				 pvOwnBids->push_back(pBid);
			 }

			 else
				 // found user before; delete
				 delete pBid;
	 }

	// Erase so later desctruction of vector doesn't
	// destroy bids
	pvAllBids->erase(pvAllBids->begin(), pvAllBids->end());
	delete pvAllBids;

	// clean up
	bidTracks.erase(bidTracks.begin(),
						 bidTracks.end());

	// if there aren't any non-retracted bids, there isn't any high bidder
	if (pvOwnBids->size() == 0)
	{
		// no high bidder, no current price, bidcount = 0
		SetHighBidder(0);
		SetPrice(0);
		SetImmedBidCount(0);

		// cleanup
		delete pvOwnBids;
		return;
	}
	else
	{
		// Sort remaining if any
		if (pvOwnBids->begin() != pvOwnBids->end())
			sort(pvOwnBids->begin(), pvOwnBids->end(), sort_bid_amount);

		// allocate memory to bidder ids
		pBidderIds = new int [pvOwnBids->size()];

		// iterate through pVBids for the winning bids
		// till quantity is exhausted
		for (vI = pvOwnBids->begin(), i = 0;
			 vI != pvOwnBids->end();
			 vI++, i++)
		{
			pBid = (*vI);

			if (total_qty < item_qty)
			{
				// to track lowest winning bidder
				pHighBid = pBid;
				
				// increment quantity - accounted for in the bid
				total_qty = total_qty + pBid->mQuantity;
				
				// Save the bidder ids in the array
				pBidderIds[i] = pBid->mUser;
			}
			else
			{
				break;
			}
		}

		// insert transaction records for Bidders
		gApp->GetDatabase()->AddTransactionRecord(mId, mSeller, pBidderIds, i, mEndTime);

		// release the array
		delete [] pBidderIds;

		// current price = lowest winning bid or start price if qty is not exhausted
		if (total_qty >= item_qty)
		{
			proposedNewPrice = pHighBid->mAmount;
			// Round
			proposedNewPrice	= RoundToCents(proposedNewPrice);

		}
		else 
			proposedNewPrice	=
				GetStartPrice();


		// Set new high bidder and price and bidcount in item
		SetHighBidder(pHighBid->mUser);
		SetPrice(proposedNewPrice);
		SetImmedBidCount(bcount);

	}

	// clean up our own vector, delete the bids 
	for (vI = pvOwnBids->begin();
		 vI != pvOwnBids->end();
		 vI++)
	{
		delete	(*vI);
	}

	pvOwnBids->erase(pvOwnBids->begin(), pvOwnBids->end());

	delete	pvOwnBids;

	return;	
};


//
// Bid
//
void clsItem::Bid(int user, int quantity, double maxBid, time_t bidTime)
{
	clsBid	*pBid;

	// Make a bid object to represent this
	pBid	= new clsBid(bidTime,
						 BID_BID,
						 user,
						 maxBid,
						 quantity,
						 (char *)0); 

	gApp->GetDatabase()->AddBid(mMarketPlaceId,
								mId,
								pBid);

	delete	pBid;
	return;
}

void clsItem::Bid(clsBid *pBid)
{
	gApp->GetDatabase()->AddBid(mMarketPlaceId,
								mId,
								pBid);
}

//
// RetractBids
//	Retracts all bids for a user
//
void clsItem::RetractBids(int user)
{
	gApp->GetDatabase()->RetractBids(mMarketPlaceId,
									 mId,
									 user,
									 BID_AUTORETRACT);

	if (GetOwner() == user)
		SetItemBidBySellerFlag(false);

	AdjustPrice();
	return;
}

//
// CancelBids
//	Cancels all bids for a user
//
void clsItem::CancelBids(int user)
{
	gApp->GetDatabase()->RetractBids(mMarketPlaceId,
									 mId,
									 user,
									 BID_AUTOCANCEL);
	AdjustPrice();
	return;
}

//
// BlockBid
//	Blocks the given bid (i.e., moves it to the
//  blocked bids table, then deletes it from the bids table)
//
void clsItem::BlockBid(clsBid *pBid)
{
	gApp->GetDatabase()->AddBid(mMarketPlaceId, mId, pBid, true);
	gApp->GetDatabase()->DeleteBid(mId, pBid->mUser);
}

//
// GetBidders
//	Gets a list of all active bidders 
//
void clsItem::GetBidders(list<int> *plBidders)
{
	gApp->GetDatabase()->GetBiddersForItem(mMarketPlaceId,
										   mId,
										   plBidders, mEnded);

	return;
}

//
// SetNewTitle
//
void clsItem::SetNewTitle(char *pNewTitle)
{
	SetTitle(pNewTitle);
	gApp->GetDatabase()->SetNewTitle(this);
	return;
}

//
// SetNewHighBidder
//
void clsItem::SetNewHighBidder(int user, double price)
{
	clsUser *pTempUser = NULL;

	SetPrice(price);
	SetHighBidder(user);	// set the high bidder

	// set the userid for the high bidder
	pTempUser = gApp->GetDatabase()->GetUserById(user);
	if (pTempUser) SetHighBidderUserId(pTempUser->GetUserId());
	if (pTempUser) delete pTempUser;


	gApp->GetDatabase()->SetNewHighBidder(this);

	return;
}

/* this is added so we update the database once instead of with setNewHighBidder
and SetNewBidcount */
void clsItem::SetNewHighBidderAndBidCount(int user, double price, int bidcount)
{
//	clsUser *pTempUser = NULL;
	bool toupdate = false;

/* checks if we need to update the database */

	if (GetPrice() != price)
	{
		SetPrice(price);
		toupdate = true;
	};
	if (GetHighBidder() != user)
	{
		SetHighBidder(user);	// set the high bidder
		toupdate = true;
	}
	if (GetBidCount() != bidcount)
	{
		SetBidCount(bidcount);
		toupdate = true;
	};

	// set the userid for the high bidder
//	pTempUser = gApp->GetDatabase()->GetUserById(user);
//	if (pTempUser) SetHighBidderUserId(pTempUser->GetUserId());
//	if (pTempUser) delete pTempUser;

	if (toupdate)
		gApp->GetDatabase()->SetNewHighBidderAndBidCount(this);

	return;
}

void clsItem::SetNewBidCount(int count)
{
	SetBidCount(count);

	gApp->GetDatabase()->SetNewBidCount(this);

	return;
}

//
// SetNewDescription
//
void clsItem::SetNewDescription(char *pNewDescription)
{
	SetDescription(pNewDescription);
	gApp->GetDatabase()->SetNewDescription(this);
	return;
}

//
// SetNewEndTime
//
void clsItem::SetNewEndTime(long newTime)
{
	SetEndTime(newTime);
	gApp->GetDatabase()->SetNewEndTime(this);
	return;
}

//
// SetNewFeatured
//
void clsItem::SetNewFeatured(bool isIt)
{
	SetFeatured(isIt);
	gApp->GetDatabase()->SetNewFeatured(this);
	return;
}

//
// SetNewSuperFeatured
//
void clsItem::SetNewSuperFeatured(bool isIt)
{
	SetSuperFeatured(isIt);
	gApp->GetDatabase()->SetNewSuperFeatured(this);
	return;
}

//
// SetNewCategory
//
void clsItem::SetNewCategory(CategoryId category)
{
	mCategory	= category;
	gApp->GetDatabase()->SetNewCategory(this);
	return;
}


//
// GetBidEngine
//
clsBidEngine *clsItem::GetBidEngine()
{
	if (!mpBidEngine)
	{
		switch (mAuctionType)
		{
			case AuctionChinese:
				mpBidEngine	= new clsBidEngineChinese(this);
				break;

			case AuctionDutch:
				mpBidEngine = new clsBidEngineDutch(this);
				break;

			default:
				mpBidEngine	= NULL;
				break;
		}
	}
	return mpBidEngine;
}

//
// ProposeBid
//	Just forwards it to our bid engine
//
clsBidResult *clsItem::ProposeBid(clsBid *pBid,
								  ostream *pStream,
								  clsUser *pUser)
{
	clsBidEngine	*pEngine;

	pEngine	= GetBidEngine();

	return pEngine->ProposeBid(pBid, pStream, pUser);
}

//
// AcceptBid
//	Just forwards it to our bid engine
//
clsBidResult *clsItem::AcceptBid(clsBid *pBid,
								  ostream *pStream,
								  clsUser *pUser)
{
	clsBidEngine	*pEngine;
	clsBidResult *pBidResult;

	pEngine	= GetBidEngine();

	// begin transaction
//	gApp->GetDatabase()->Begin();

	pBidResult = pEngine->AcceptBid(pBid, pStream, pUser);
	// invalidate bidder list here
	gApp->GetDatabase()->InvalidateBidderList(pUser->GetMarketPlace(), 
		pUser->GetId(), this->GetId(), this->GetEndTime());

	// finish transaction
//	gApp->GetDatabase()->End();

	return pBidResult;
}

//
// AdjustPrice
//
void clsItem::AdjustPrice(BidVector *pvHighBids)
{
	clsBidEngine	*pEngine;
//	bool			noTrans;

//	noTrans = gApp->GetDatabase()->InTransaction();

	pEngine	= GetBidEngine();

	// put transaction wrapper to check if its already in a transaction
	// if not, do this in a transaction
//	if (noTrans)
//		gApp->GetDatabase()->Begin();

	pEngine->AdjustPrice(pvHighBids);

	// commit if we start transaction here.
//	if (noTrans)
//		gApp->GetDatabase()->End();

	return;
}


//
// GetNoticeTime
//
long clsItem::GetNoticeTime()
{
	// not implemented yet
//	mNoticeTime = gApp->GetDatabase()->GetNoticeTime(this);
	return mNoticeTime;
}

long clsItem::GetDBNoticeTime()
{
	mNoticeTime = gApp->GetDatabase()->GetNoticeTime(this);
	return mNoticeTime;
}

//
// SetNoticeTime
//
void clsItem::SetNoticeTime(long when)
{
	mNoticeTime	= when;

	gApp->GetDatabase()->AddItemNoticed(this);

	return;
}

//
// GetBillTime
//
long clsItem::GetBillTime()
{
//	mBillTime = gApp->GetDatabase()->GetBillTime(this);
	return mBillTime;
}

long clsItem::GetDBBillTime()
{
	mBillTime = gApp->GetDatabase()->GetBillTime(this);
	return mBillTime;
}

//
// SetBillTime
//
void clsItem::SetBillTime(long when)
{
	mBillTime	= when;
	gApp->GetDatabase()->AddItemBilled(this);
	return;
}

void clsItem::SetDBDutchGMS(float sold_price)
{
	gApp->GetDatabase()->SetDBDutchGMS(this, sold_price);
	return;
} 

void clsItem::SetNoFinalValueFee(bool value)
{
	if (value)
	{
		mStatus	= mStatus | ITEM_SALE_STATUS_NO_FINAL_VALUE_FEE;
	}
	else
	{
		mStatus	= mStatus & ~ITEM_SALE_STATUS_NO_FINAL_VALUE_FEE;
	}
//	mDirty	= true;

	gApp->GetDatabase()->UpdateItemStatus(this);
	
//	mDirty	= false;
}

bool clsItem::ChargeNoFinalValueFee()
{
	return	(mStatus & ITEM_SALE_STATUS_NO_FINAL_VALUE_FEE) ? true : false ;
}

//inna start
void clsItem::SetItemWackoFlag(bool value)
{
// just for my note:
// if item is wacko,true is sent to this method and
// mStatus | 1000, sets on the wacko bit
	if (value)
	{
		mStatus	= mStatus | ITEM_WACKO_FLAG;
	}
	else
	{
		mStatus	= mStatus & ~ITEM_WACKO_FLAG;
	}
//	mDirty	= true;

	gApp->GetDatabase()->UpdateItemStatus(this);
	
//	mDirty	= false;
}

bool clsItem::IsItemWacko()
{
	return	(mStatus & ITEM_WACKO_FLAG) ? true : false ;
}
//inna end 

// seller bid once flag manipulator
void clsItem::SetItemBidBySellerFlag(bool value)
{
	if (value)
	{
		mStatus	= mStatus | ITEM_BID_BY_SELLER_FLAG;
	}
	else
	{
		mStatus	= mStatus & ~ITEM_BID_BY_SELLER_FLAG;
	}
//	mDirty	= true;

	gApp->GetDatabase()->UpdateItemStatus(this);
	
//	mDirty	= false;
}

bool clsItem::GetItemBidBySellerFlag()
{
	return (mStatus & ITEM_BID_BY_SELLER_FLAG) ? true : false;
}

//added for gallery
bool clsItem::IsGallery()
{ 
	if (mGalleryType != Gallery)
		return false;
	return true;
}

bool clsItem::IsFeaturedGallery()
{
	if (mGalleryType != FeaturedGallery)
		return false;
	return true;
}


// item finalized flag
void clsItem::SetItemBidsFinalizedFlag(bool value)
{
	if (value)
	{
		mStatus	= mStatus | ITEM_BIDS_FINALIZED_FLAG;
	}
	else
	{
		mStatus	= mStatus & ~ITEM_BIDS_FINALIZED_FLAG;
	}
//	mDirty	= true;

	gApp->GetDatabase()->UpdateItemStatus(this);
	
//	mDirty	= false;
};

bool clsItem::GetItemBidsFinalizedFlag()
{
	return (mStatus & ITEM_BIDS_FINALIZED_FLAG) ? true : false;

};

void clsItem::Finalize()
{
	time_t			curtime;

	// checks for sale end vs. sysdate
	if (GetItemBidsFinalizedFlag())
		return;
	else
	{	// not finalized yet; is sale ended?
		curtime = time(0);
		if (curtime > GetEndTime())
		{

			// if bids > 1, then possible race condition to resolve
			if (GetBidCount() > 1)
			{
				// call the respective recomputes
				if (GetAuctionType() == AuctionDutch)
					RecomputeDutchBids();
				else
					if (GetAuctionType() == AuctionChinese)
						AdjustPrice();

			}

			if (GetBidCount() > 0)
			{
					// insert a transaction record for the Bidder
					gApp->GetDatabase()->AddTransactionRecord(mId, 
						mSeller, &mHighBidder, 1, mEndTime);
			}

			// done, set flag
			SetItemBidsFinalizedFlag(true);
		}
	}
}


//
//	"<" operator
//
int clsItemPtr::operator<(clsItemPtr &pOther)
{
	if (!mpItem->mpItems)
		mpItem->mpItems	= 
			gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetItems();

	switch(mpItem->mpItems->mCurrentSortMode)
	{
		case SortItemsById:
			if (mpItem->mId < pOther.mpItem->mId)
				return	1;
			else
				return	0;

		case SortItemsByIdReverse:
			if (mpItem->mId > pOther.mpItem->mId)
				return	1;
			else
				return	0;
			break;

		case SortItemsByStartTime:
			if (mpItem->mStartTime < pOther.mpItem->mStartTime)
				return 1;
			else
				return 0;
			break;

		case SortItemsByStartTimeReverse:
			if (mpItem->mStartTime > pOther.mpItem->mStartTime)
				return 1;
			else
				return 0;
			break;

		case SortItemsByEndTime:
			if (mpItem->mEndTime < pOther.mpItem->mEndTime)
				return 1;
			else
				return 0;
			break;

		case SortItemsByEndTimeReverse:
			if (mpItem->mEndTime > pOther.mpItem->mEndTime)
				return 1;
			else
				return 0;
			break;

		case SortItemsByStartPrice:
			if (mpItem->mStartPrice < pOther.mpItem->mStartPrice)
				return 1;
			else
				return 0;

		case SortItemsByStartPriceReverse:
			if (mpItem->mStartPrice > pOther.mpItem->mStartPrice)
				return 1;
			else
				return 0;

		case SortItemsByPrice:
		{
			double	aPrice;
			double	bPrice;

			aPrice	= mpItem->mPrice;
			if (aPrice == 0)
				aPrice	= mpItem->mStartPrice;

			bPrice	= pOther.mpItem->mPrice;
			if (bPrice == 0)
				bPrice	= pOther.mpItem->mStartPrice;

			return (aPrice < bPrice);
			break;
		}

		case SortItemsByPriceReverse:
		{
			double	aPrice;
			double	bPrice;

			aPrice	= mpItem->mPrice;
			if (aPrice == 0)
				aPrice	= mpItem->mStartPrice;

			bPrice	= pOther.mpItem->mPrice;
			if (bPrice == 0)
				bPrice	= pOther.mpItem->mStartPrice;

			return (aPrice < bPrice);
			break;
		}

		case SortItemsByReservePrice:
			if (mpItem->mReservePrice < pOther.mpItem->mReservePrice)
				return 1;
			else
				return 0;

		case SortItemsByReservePriceReverse:
			if (mpItem->mReservePrice > pOther.mpItem->mReservePrice)
				return 1;
			else
				return 0;

		case SortItemsByBidCount:
			if (mpItem->mBidCount < pOther.mpItem->mBidCount)
				return 1;
			else
				return 0;

		case SortItemsByBidCountReverse:
			if (mpItem->mBidCount > pOther.mpItem->mBidCount)
				return 1;
			else
				return 0;

		case SortItemsByQuantity:
			if (mpItem->mQuantity < pOther.mpItem->mQuantity)
				return 1;
			else
				return 0;

		case SortItemsByQuantityReverse:
			if (mpItem->mQuantity > pOther.mpItem->mQuantity)
				return 1;
			else
				return 0;

		case SortItemsByTitle:
			if (strcmp(mpItem->mpTitle, pOther.mpItem->mpTitle) < 0)
				return	1;
			else
				return	0;
			break;

		case SortItemsByTitleReverse:
			if (strcmp(mpItem->mpTitle, pOther.mpItem->mpTitle) >= 0)
				return	1;
			else
				return	0;
			break;

		case SortItemsByUnknown:
		default:
			return 0;
			break;
	}
};

// 
// Helper functions for credits
//

//
// A "No Sale" Credit is actually "like" a Final Value Fee credit,
// but the difference is that there's no "blame" on the high bidder.
//
bool clsItem::HasNoSaleCredit()
{
	return	(mPassword & ItemCreditNoSale) ? true : false;
}

bool clsItem::HasFVFCredit()
{
	return	(mPassword & ItemCreditFVF) ? true : false;
}

bool clsItem::HasInsertionCredit()
{
	return	(mPassword & ItemCreditInsertion) ? true : false;
}

bool clsItem::HasFeaturedCredit()
{
	return	(mPassword & ItemCreditFeatured) ? true : false;
}

bool clsItem::HasCategoryFeaturedCredit()
{
	return	(mPassword & ItemCreditCategoryFeatured) ? true : false;
}

bool clsItem::HasBoldCredit()
{
	return	(mPassword & ItemCreditBold) ? true : false;
}

bool clsItem::HasGalleryCredit()
{
	return	(mPassword & ItemCreditGallery) ? true : false;
}

bool clsItem::HasFeaturedGalleryCredit()
{
	return	(mPassword & ItemCreditFeaturedGallery) ? true : false;
}

bool clsItem::HasGiftIconCredit()
{
	return	(mPassword & ItemCreditGiftIcon) ? true : false;
}

void clsItem::SetHasInsertionCredit(bool doesIt)
{
	if (doesIt)
		mPassword		= mPassword | ItemCreditInsertion;
	else
		mPassword		= mPassword & ~ItemCreditInsertion;

	gApp->GetDatabase()->UpdateItemPassword(this);
}

void clsItem::SetHasFeaturedCredit(bool doesIt)
{
	if (doesIt)
		mPassword		= mPassword | ItemCreditFeatured;
	else
		mPassword		= mPassword & ~ItemCreditFeatured;

	gApp->GetDatabase()->UpdateItemPassword(this);
}

void clsItem::SetHasCategoryFeaturedCredit(bool doesIt)
{
	if (doesIt)
		mPassword		= mPassword | ItemCreditCategoryFeatured;
	else
		mPassword		= mPassword & ~ItemCreditCategoryFeatured;

	gApp->GetDatabase()->UpdateItemPassword(this);
}

void clsItem::SetHasFVFCredit(bool doesIt)
{
	if (doesIt)
		mPassword		= mPassword | ItemCreditFVF;
	else
		mPassword		= mPassword & ~ItemCreditFVF;

	gApp->GetDatabase()->UpdateItemPassword(this);
}

void clsItem::SetHasGiftIconCredit(bool doesIt)
{
	if (doesIt)
		mPassword		= mPassword | ItemCreditGiftIcon;
	else
		mPassword		= mPassword & ~ItemCreditGiftIcon;

	gApp->GetDatabase()->UpdateItemPassword(this);
}

void clsItem::SetHasNoSaleCredit(bool doesIt)
{
	if (doesIt)
		mPassword		= mPassword | ItemCreditNoSale;
	else
		mPassword		= mPassword & ~ItemCreditNoSale;

	gApp->GetDatabase()->UpdateItemPassword(this);
}

void clsItem::SetHasBoldCredit(bool doesIt)
{
	if (doesIt)
		mPassword		= mPassword | ItemCreditBold;
	else
		mPassword		= mPassword & ~ItemCreditBold;

	gApp->GetDatabase()->UpdateItemPassword(this);
}

// Sam, 01/20/99, Helper functions for auto credits
bool clsItem::SetItemCredit(CreditsVector *pvCredits)
{
	return (gApp->GetDatabase()->InsertItemCredit(pvCredits));
}

void clsItem::GetAllItemCredits(int item_id, CreditsVector *pvCredits)
{
	gApp->GetDatabase()->GetCreditsForItem(item_id, pvCredits);
}

bool clsItem::isDeadbeatCreditReq(CreditTypeEnum reason)
{
	switch (reason)
	{
		case CreditTypeFullNoResponseFromBidder:
		case CreditTypeFullBidderNoLongerWantsItem:
		case CreditTypeFullPaymentNotReceivedFromBidder:
		case CreditTypeFullBouncedCheckOrStoppedPayment:
		case CreditTypePartialItemSoldToLowerBidder:
			return true;
		default:
			return false;
	}
}


// adult items are really strict. no bidding, listing, or viewing
bool clsItem::IsAdult()
{
	if (mAdult == 'A')
	{
		mAdult = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCategories()->GetCategory(mCategory, true)->GetAdult();
	}

	return (mAdult == '1');
}

// not adult per se, but still can't bid or list (e.g. firearms)
bool clsItem::NoBidAndListForMinor()
{
	if (mAdult == 'A')
	{
		mAdult = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCategories()->GetCategory(mCategory, true)->GetAdult();
	}

	return (mAdult == '2');
}

void clsItem::SetPaymentMOCashiers(bool accepts)
{
	if (accepts)
		mPassword = mPassword | PaymentMOCashiers;
	else
		mPassword = mPassword & ~PaymentMOCashiers;
}

void clsItem::SetPaymentPersonalCheck(bool accepts)
{
	if (accepts)
		mPassword = mPassword | PaymentPersonalCheck;
	else
		mPassword = mPassword & ~PaymentPersonalCheck;
}

void clsItem::SetPaymentAmEx(bool accepts)
{
	if (accepts)
		mPassword = mPassword | PaymentAmEx;
	else
		mPassword = mPassword & ~PaymentAmEx;
}

void clsItem::SetPaymentVisaMaster(bool accepts)
{
	if (accepts)
		mPassword = mPassword | PaymentVisaMaster;
	else
		mPassword = mPassword & ~PaymentVisaMaster;
}

void clsItem::SetPaymentDiscover(bool accepts)
{
	if (accepts)
		mPassword = mPassword | PaymentDiscover;
	else
		mPassword = mPassword & ~PaymentDiscover;
}

void clsItem::SetPaymentOther(bool accepts)
{
	if (accepts)
		mPassword = mPassword | PaymentOther;
	else
		mPassword = mPassword & ~PaymentOther;
}

void clsItem::SetPaymentEscrow(bool accepts)
{ 
	if (accepts)
		mPassword = mPassword | PaymentEscrow;
	else
		mPassword = mPassword & ~PaymentEscrow;
}

void clsItem::SetPaymentCOD(bool accepts)
{ 
	if (accepts)
		mPassword = mPassword | PaymentCOD;
	else
		mPassword = mPassword & ~PaymentCOD;
}

void clsItem::SetPaymentSeeDescription(bool checked)
{ 
	if (checked)
		mPassword = mPassword | PaymentSeeDescription;
	else
		mPassword = mPassword & ~PaymentSeeDescription;
}

void clsItem::SetSellerPaysShipping(bool does)
{
	if (does)
		mPassword = mPassword | SellerPaysShipping;
	else
		mPassword = mPassword & ~SellerPaysShipping;
}

void clsItem::SetBuyerPaysShippingFixed(bool does)
{
	if (does)
		mPassword = mPassword | BuyerPaysShippingFixed;
	else
		mPassword = mPassword & ~BuyerPaysShippingFixed;
}

void clsItem::SetBuyerPaysShippingActual(bool does)
{
	if (does)
		mPassword = mPassword | BuyerPaysShippingActual;
	else
		mPassword = mPassword & ~BuyerPaysShippingActual;
}

void clsItem::SetShippingSeeDescription(bool checked)
{
	if (checked)
		mPassword = mPassword | ShippingSeeDescription;
	else
		mPassword = mPassword & ~ShippingSeeDescription;
}

// nsacco 07/27/99 removed SetShippingToCanada

// nsacco 07/27/99 modified for new method
// TODO - remove after this code has been in core site for several weeks
void clsItem::SetShippingInternationally(bool checked)
{
	if (mPassword & ShippingInternationally)
	{
		// we have an old style item
		// so lets make it new style
		mPassword = mPassword & ~ShippingSeeDescription;
	}

	if (checked)
	{
		SetShippingOption(Worldwide);
	}
	else
	{
		SetShippingOption(SiteOnly);
	}
}

bool clsItem::AcceptsPaymentMOCashiers()
{
	return	(mPassword & PaymentMOCashiers) ? true : false;
}

bool clsItem::AcceptsPaymentPersonalCheck()
{
	return	(mPassword & PaymentPersonalCheck) ? true : false;
}

bool clsItem::AcceptsPaymentVisaMaster()
{
	return	(mPassword & PaymentVisaMaster) ? true : false;
}

bool clsItem::AcceptsPaymentAmEx()
{
	return	(mPassword & PaymentAmEx) ? true : false;
}

bool clsItem::AcceptsPaymentDiscover()
{
	return	(mPassword & PaymentDiscover) ? true : false;
}

bool clsItem::AcceptsPaymentOther()
{
	return	(mPassword & PaymentOther) ? true : false;
}

bool clsItem::AcceptsPaymentEscrow()
{
	return	(mPassword & PaymentEscrow) ? true : false;
}

bool clsItem::AcceptsPaymentCOD()
{
	return	(mPassword & PaymentCOD) ? true : false;
}

bool clsItem::MorePaymentSeeDescription()
{
	return	(mPassword & PaymentSeeDescription) ? true : false;
}

bool clsItem::SellerPaysForShipping()
{
	return	(mPassword & SellerPaysShipping) ? true : false;
}

bool clsItem::BuyerPaysForShippingFixed()
{
	return	(mPassword & BuyerPaysShippingFixed) ? true : false;
}

bool clsItem::BuyerPaysForShippingActual()
{
	return	(mPassword & BuyerPaysShippingActual) ? true : false;
}

bool clsItem::MoreShippingSeeDescription()
{
	return	(mPassword & ShippingSeeDescription) ? true : false;
}

// nsacco 07/27/99 removed IsShippingToCanada

// nsacco 07/27/99 modified
bool clsItem::IsShippingInternationally()
{
	if (GetShippingOption() == Worldwide)
	{
		return true;
	}
	else
	{
		return false;
	}
}

void clsItem::TsAndCsSet()
{
	if (!AcceptsPaymentMOCashiers() && !AcceptsPaymentEscrow() && 
		!MorePaymentSeeDescription() && !AcceptsPaymentOther() &&
		!AcceptsPaymentAmEx() && !AcceptsPaymentVisaMaster() &&
		!AcceptsPaymentPersonalCheck() && !AcceptsPaymentCOD() &&
		!AcceptsPaymentDiscover())
		SetPaymentSeeDescription(true);
	if (!MoreShippingSeeDescription() && !BuyerPaysForShippingActual() &&
		!BuyerPaysForShippingFixed() && !SellerPaysForShipping())
		SetShippingSeeDescription(true);
	return;
}

void clsItem::SetHasGalleryCredit(bool doesIt)
{
	if (doesIt)
		mPassword		= mPassword | ItemCreditGallery;
	else
		mPassword		= mPassword & ~ItemCreditGallery;

	gApp->GetDatabase()->UpdateItemPassword(this);
}

void clsItem::SetHasFeaturedGalleryCredit(bool doesIt)
{
	if (doesIt)
		mPassword		= mPassword | ItemCreditFeaturedGallery;
	else
		mPassword		= mPassword & ~ItemCreditFeaturedGallery;

	gApp->GetDatabase()->UpdateItemPassword(this);
}

const char *clsItem::GetGalleryStateMessage()
{
	GalleryResultCode code = GetGalleryState();
	
	if (code < 0 ||
		code > kGalleryMaxResultCode)

	   code = kGalleryMaxResultCode; // Generic msg

	return sGalleryResultCodeMessages[code];
}

//
// GetBidIncrement
//
//	*** NOTE ***
//	This function _should_ be based on some
//	marketplace based parameters. Right now,
//	it's not.
//	*** NOTE ***
//
double clsItem::GetBidIncrement(double price)
{
	return mpFees->GetBidIncrement(price);
}


//
// GetInsertionFee
//
//	*** NOTE ***
//	This function _should_ be based on some
//	marketplace based parameters. Right now,
//	it's not.
//	*** NOTE ***
//
double clsItem::GetInsertionFee(double price)
{
	return mpFees->GetInsertionFee(price);
}

//
// GetFeaturedFee
//
double clsItem::GetFeaturedFee(time_t when)
{
	FeeEnum fee;

	if (when == 0)
		when = time(0);
	if (clsUtilities::CompareTimeToGivenDate(when, 02, 15, 99, 0, 0, 0) < 0) 
		fee = FeaturedFee;
	else
		fee = NewFeaturedFee;

	return mpFees->GetFee(fee);
}

//
// GetFeaturedFee
//
double clsItem::GetCategoryFeaturedFee(time_t when)
{
	FeeEnum fee;

	if (when == 0)
		when = time(0);
	if (clsUtilities::CompareTimeToGivenDate(when, 02, 15, 99, 0, 0, 0) < 0) 
		fee = CategoryFeaturedFee;
	else
		fee = NewCategoryFeaturedFee;

	return mpFees->GetFee(fee);
}

//
// GetBoldFee
//
double clsItem::GetBoldFee(double price)
{
	return mpFees->GetFee(BoldFee, price);
}
// 
// GetGiftIconFee
//
double clsItem::GetGiftIconFee(int icon)
{
	if (icon == GiftIconUnknown || icon == RosieIcon) // for roies or not gift icon
		return 0.0;
	else
		return mpFees->GetFee(GiftIconFee);
}


//
// GetGalleryFee
//
double clsItem::GetGalleryFee()
{
	return mpFees->GetFee(GalleryFee);
}


//
// GetFeaturedGalleryFee
//
double clsItem::GetFeaturedGalleryFee()
{
	return mpFees->GetFee(GalleryFeaturedFee);
}

//
// GetItemMoveFee
//
double clsItem::GetItemMoveFee()
{
	return mpFees->GetFee(ItemMoveFee);
}

//
// GetListingFee
//
//	*** NOTE ***
//	This function _should_ be based on some
//	marketplace based parameters. Right now,
//	it's not.
//	*** NOTE ***
//
double clsItem::GetListingFee(double price)
{
	return mpFees->GetListingFee(price);
}

// check for automotive listing
bool  clsItem::CheckForAutomotiveListing(int nCategory)
{
	//if item listed before change date, just return false
	if (clsUtilities::CompareTimeToGivenDate(mStartTime, 4, 24, 99, 0, 0, 0) < 0)
		return false;

	if (nCategory==0)
		return mpFees->CheckForAutomotiveListing(mCategory);
	else
	  return mpFees->CheckForAutomotiveListing(nCategory);
}
// check for real estate listing
bool clsItem::CheckForRealEstateListing(int nCategory)
{
	//if item listed before change date, just return false
	if (clsUtilities::CompareTimeToGivenDate(mStartTime, 4, 24, 99, 0, 0, 0) < 0)
	 return false;

	if (nCategory==0)
		return mpFees->CheckForRealEstateListing(mCategory);
	else
		return mpFees->CheckForRealEstateListing(nCategory);
} 

int clsItem::GetRegionID()
{
	if (!mpZip)
		return 0;

	return gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetRegions()->GetRegionID(mpZip);
}

double clsItem::GetFVFConversionRate()
{
	clsMarketPlace *pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
	clsExchangeRates *pExchangeRates = pMarketPlace->GetCurrencies()->GetExchangeRates();
	return pExchangeRates->GetExchangeRate(GetEndTime(), GetCurrencyId(), Currency_USD);
}


// nsacco 07/27/99
void clsItem::SetShippingOption(int theNewShippingOption)
{
	// if changing from SitePlusRegions, then deselect all the regions
	if (mShippingOption == SitePlusRegions)
	{
		if (mShippingOption != theNewShippingOption)
		{
			mShippingOption = theNewShippingOption;
			mShipRegionFlags = ShipRegion_None;	
		}
	}
	else
	{
		mShippingOption = theNewShippingOption;
	}
}

// nsacco 07/27/99
//
// check if the item is shipping to site country only
//
bool clsItem::IsShippingToSiteOnly() 
{
	if (GetShippingOption() == SiteOnly)
		return true;
	else
		return false;
}

// nsacco 07/27/99
//
// check if the item is shipping to a site plus regions
//
bool clsItem::IsShippingToSiteAndRegions() 
{
	if (GetShippingOption() == SitePlusRegions)
		return true;
	else
		return false;
}

// nsacco 07/27/99
//
// Retrieve a single ship to region flag.
//
bool clsItem::IsShippingToRegion(unsigned long bit) 
{
	long flags = GetShipRegionFlags();
	return ( (flags & bit) > 0);
}

// nsacco 07/27/99
//
// Retrieve all the ship to region flags.
//
long clsItem::GetShipRegionFlags() 
{
	return (mShipRegionFlags);
}

// nsacco 07/27/99
//
// Retrieve the shipping option.
//
int clsItem::GetShippingOption() 
{
	return (mShippingOption);
}

// nsacco 07/27/99
//
// Set one or more user flags, and indicate whether to toggle them
// on or off (the other flags are untouched). 
// You can logical-or the bit masks together in mask.
//
long clsItem::SetShipToRegion(long mask, bool on)
{
	long flags;
	long oldFlags = GetShipRegionFlags();

	if (on)
	{
		// regions can only be turned on if the shipping option
		// is SitePlusRegions.
		if (mask != ShipRegion_None)
		{
			mShippingOption = SitePlusRegions;
			flags = oldFlags | mask;
		}
		else
		{
			// the mask is equal to ShipRegion_None and we
			// can only clear the regions if the shipping option is NOT
			// SitePlusRegion
			mShippingOption = SiteOnly;
			flags = ShipRegion_None;
		}
		
	}
	else 
	{
		// ignore ShipRegion_None and on = false
		if (mask != ShipRegion_None)
		{
			flags = oldFlags & ~mask;
		}
	}

	// make the change
	mShipRegionFlags = flags;

	return oldFlags;
}

