/*	$Id: clsListingItem.cpp,v 1.9.250.1 1999/08/01 03:02:29 barry Exp $	*/
//
//      File:           clsListingItem.cpp
//
//      Class:          clsListingItem
//
//      Author:         Wen Wen (wen@ebay.com)
//
//      Function:
//                              class for listing items
//
//      Modifications:
//                  - 08/31/97 Wen - Created
//					- 07/27/99 nsacco - new params and added new functions.
//										Rewrote IsShippingInternationally.			
//
#include "eBayKernel.h"
#include "clsRegions.h"

ItemListSortEnum clsListingItem::CurrentSortMode = SortItemsByUnknown;

// nsacco 07/27/99 new params
clsListingItem::clsListingItem(int		Id,
                                char*   pTitle,
								const char *pRowId,
                                int     CatId,
                                int     BidCount,
                                time_t  StartTime,
                                time_t  EndTime,
                                double  Price,
                                bool    IsReserved,
                                bool    IsFeatured,
                                bool    IsSuperFeatured,
                                bool    IsBoldTitle,
                                bool    HasPic,
								int 	GiftIconType,
								bool	IsGalllery,
								bool	IsFeaturedGallery,
								int		countryId,
								int     currencyId,
								int		shippingOption,
								long	shipRegionFlags,
								int		descLang,
								int		siteId,
								int		password,
								char *	zip)

{
	mId = Id;
	mpTitle = new char[strlen(pTitle)+1];
	strcpy(mpTitle, pTitle);
	strcpy(mRowId, pRowId);
	mCategoryId = CatId;
	mBidCount = BidCount;
	mStartTime = StartTime;
	mEndTime = EndTime;
	mPrice = Price;
	mReserved = IsReserved;
	mFeatured = IsFeatured;
	mSuperFeatured = IsSuperFeatured;
	mBoldTitle = IsBoldTitle;
	mHasPic = HasPic;
	mGiftIconType = GiftIconType;
	mGallery = IsGalllery;
	mFeaturedGallery = IsFeaturedGallery;
	mPassword = password;
	mCountryId = countryId;
	mCurrencyId = currencyId;
	mpZip = new char[strlen(zip)+1];
	strcpy(mpZip, zip);
	// nsacco 07/27/99 
	mShippingOption = shippingOption;
	mShipRegionFlags = shipRegionFlags;
	mDescLang = descLang;
	mSiteId = siteId;

}

// nsacco 07/27/99 new params
void clsListingItem::Set        (       int     Id,
                                        char*   pTitle,
										const char *pRowId,
                                        int     CatId,
                                        int     BidCount,
                                        time_t  StartTime,
                                        time_t  EndTime,
                                        double  Price,
                                        bool    IsReserved,
                                        bool    IsFeatured,
                                        bool    IsSuperFeatured,
                                        bool    IsBoldTitle,
                                        bool    HasPic,
										int     GiftIconType,
										bool	IsGallery,
										bool	IsFeaturedGallery,
										GalleryResultCode galleryState,
										int     countryId,
										int     currencyId,
										int		shippingOption,
										long	shipRegionFlags,
										int		descLang,
										int		siteId,
										int		password,
										char *	zip)
{
	mId = Id;
	delete [] mpTitle;
	mpTitle = new char[strlen(pTitle)+1];
	strcpy(mpTitle, pTitle);
	strcpy(mRowId, pRowId);
	mCategoryId = CatId;
	mBidCount = BidCount;
	mStartTime = StartTime;
	mEndTime = EndTime;
	mPrice = Price;
	mReserved = IsReserved;
	mFeatured = IsFeatured;
	mSuperFeatured = IsSuperFeatured;
	mBoldTitle = IsBoldTitle;
	mHasPic = HasPic;
	mGiftIconType = GiftIconType;
	mGallery = IsGallery;
	mFeaturedGallery = IsFeaturedGallery;
	mGalleryState = galleryState;
	mPassword = password;
	mCountryId = countryId;
	mCurrencyId = currencyId;
	delete [] mpZip;
	mpZip = new char[strlen(zip)+1];
	strcpy(mpZip, zip);
	// nsacco 07/27/99 new params
	mShippingOption = shippingOption;
	mShipRegionFlags = shipRegionFlags;
	mDescLang = descLang;
	mSiteId = siteId;
}


bool clsListingItem::IsShippingInternationally()
{
	// nsacco 07/27/99
	// do new check
	if (GetShippingOption() == Worldwide)
	{
		return true;
	}
	else
	{
		// try old method
		// TODO - is this needed?
		return	(mPassword & ShippingInternationally) ? true : false;
	}
}

int clsListingItem::GetRegionID()
{
	if (!mpZip)
		return 0;

	return gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetRegions()->GetRegionID(mpZip);
}

bool clsListingItem::IsLAItem()
{
	int region = GetRegionID();
	return ((int)Region_LA == region);
}

// nsacco 07/27/99
bool clsListingItem::IsShippingToSiteOnly() 
{
	if (GetShippingOption() == SiteOnly)
		return true;
	else
		return false;
}

// nsacco 07/27/99
bool clsListingItem::IsShippingToSiteAndRegions() 
{
	if (GetShippingOption() == SitePlusRegions)
		return true;
	else
		return false;
}

// nsacco 07/27/99
bool clsListingItem::IsShippingToRegion(unsigned long bit) 
{
	long flags = GetShipRegionFlags();
	return ( (flags & bit) > 0);
}

// nsacco 07/27/99
long clsListingItem::GetShipRegionFlags() 
{
	return (mShipRegionFlags);
}

// nsacco 07/27/99
int clsListingItem::GetShippingOption() 
{
	return (mShippingOption);
}

// nsacco 07/27/99
int clsListingItem::GetSiteId() 
{
	return (mSiteId);
}

// nsacco 07/27/99
int clsListingItem::GetDescLang() 
{
	return (mDescLang);
}

//
//	"<" operator (for list sorting purpose)
//
int clsListingItem::operator<(clsListingItem &pOther)
{

	switch(CurrentSortMode)
	{
		case SortItemsById:
			if (mId < pOther.mId)
				return	1;
			else
				return	0;

		case SortItemsByIdReverse:
			if (mId > pOther.mId)
				return	1;
			else
				return	0;
			break;

		case SortItemsByStartTime:
			if (mStartTime < pOther.mStartTime)
				return 1;
			else
				return 0;
			break;

		case SortItemsByStartTimeReverse:
			if (mStartTime > pOther.mStartTime)
				return 1;
			else
				return 0;
			break;

		case SortItemsByEndTime:
			if (mEndTime < pOther.mEndTime)
				return 1;
			else
				return 0;
			break;

		case SortItemsByEndTimeReverse:
			if (mEndTime > pOther.mEndTime)
				return 1;
			else
				return 0;
			break;

		case SortItemsByBidCount:
			if (mBidCount < pOther.mBidCount)
				return 1;
			else
				return 0;

		case SortItemsByBidCountReverse:
			if (mBidCount > pOther.mBidCount)
				return 1;
			else
				return 0;

		case SortItemsByTitle:
			if (strcmp(mpTitle, pOther.mpTitle) < 0)
				return	1;
			else
				return	0;
			break;

		case SortItemsByTitleReverse:
			if (strcmp(mpTitle, pOther.mpTitle) >= 0)
				return	1;
			else
				return	0;
			break;

		case SortItemsByUnknown:
		default:
			return 0;
			break;
	}
}
