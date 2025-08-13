/*	$Id: clsListingItem.h,v 1.8.248.1 1999/08/01 03:02:09 barry Exp $	*/
//
//	File:		clsListingItem.h
//
//	Class:		clsListingItem
//
//	Author:		Wen Wen (wen@ebay.com)
//
//	Function:
//				class for listing items
//
//	Modifications:
//				- 08/31/97 Wen - Created
//				- 07/27/99 nsacco - updated for new shipping conditions and params
//									Modified constructors and added new methods.
//
#ifndef CLSLISTINGITEM_INCLUDE
#define CLSLISTINGITEM_INCLUDE

#include "eBayTypes.h"
#include "time.h"
#include "vector.h"


class clsListingItem
{
public:
	clsListingItem() {
						mpTitle = NULL;
						mpZip = NULL;
					}

	// nsacco 07/27/99 added new params
	clsListingItem(	int		Id, 
					char*	pTitle,
					const char *pRowId,
					int		CatId,
					int		BidCount,
					time_t	StartTime,
					time_t	EndTime,
					double	Price,
					bool	IsReserved,
					bool	IsFeatured,
					bool	IsSuperFeatured,
					bool	IsBoldTitle,
					bool	HasPic,
					int     GiftIconType,
					bool	isGallery,
					bool	isFeaturedGallery,
					int     countryId = Country_None, // = 0
					int     currencyId = Currency_USD, // =1
					int		shippingOption = SiteOnly,
					long	shipRegionFlags = ShipRegion_None,
					int		descLang = English,
					int		siteId = SITE_EBAY_MAIN,
					int     password = 0,
					char *	zip = NULL);


	~clsListingItem() {
						delete [] mpTitle;
						delete [] mpZip;
					}

	// nsacco 07/27/99 added new params
	void Set	(	int		Id, 
					char*	pTitle,
					const char *pRowId,
					int		CatId,
					int		BidCount,
					time_t	StartTime,
					time_t	EndTime,
					double	Price,
					bool	IsReserved,
					bool	IsFeatured,
					bool	IsSuperFeatured,
					bool	IsBoldTitle,
					bool	HasPic,
					int 	GiftIconType,
					bool	isGallery,
					bool	isFeaturedGallery,
					GalleryResultCode galleryState,
					int		countryId = Country_None,
					int     currencyId = Currency_USD,
					int		shippingOption = SiteOnly,
					long	shipRegionFlags = ShipRegion_None,
					int		descLang = English,
					int		siteId = SITE_EBAY_MAIN,
					int		password = 0,
					char *	zip = NULL);

	int		GetId()				{ return mId; }
	const char*	GetTitle()		{ return mpTitle; }
	const char *GetRowId()		{ return mRowId; }
	int		GetPassword()		{ return mPassword; }
	int		GetCategoryId()		{ return mCategoryId; }
	int		GetBidCount()		{ return mBidCount; }
	time_t	GetStartTime()		{ return mStartTime; }
	time_t	GetEndTime()		{ return mEndTime; }
	double	GetPrice()			{ return mPrice; }
	bool	IsReserved()		{ return mReserved;}
	bool	IsFeatured()		{ return mFeatured;}
	bool	IsSuperFeatured ()	{ return mSuperFeatured;}
	bool	IsBoldTitle()		{ return mBoldTitle;}
	bool	HasPic()			{ return mHasPic;}
	int 	GiftIconType()		{ return mGiftIconType;}
	bool	isGallery()			{ return mGallery;}
	bool	isFeaturedGallery()	{ return mFeaturedGallery;}	
	GalleryResultCode	GetGalleryState()	{ return mGalleryState;}	
	int		CountryId()			{ return mCountryId;}
	int		CurrencyId()		{ return mCurrencyId;}
	const char * GetZip()		{ return mpZip;}
	bool IsShippingInternationally();

	// nsacco 07/27/99
	// new functions
	bool IsShippingToRegion(unsigned long bit);
	bool IsShippingToSiteOnly();
	bool IsShippingToSiteAndRegions();
	long GetShipRegionFlags();
	int	 GetShippingOption();
	int	 GetSiteId();
	int  GetDescLang();

	// regional stuff
	//
	int	GetRegionID();
	bool IsLAItem();

	// list sorting support function and variables
	int		operator<(clsListingItem &pOther);
	static	ItemListSortEnum CurrentSortMode;


protected:
	int		mId;
	char	mRowId[20];
	char*	mpTitle;
	int		mPassword;
	int		mCategoryId;
	int		mBidCount;
	time_t	mStartTime;
	time_t	mEndTime;
	double	mPrice;
	bool	mReserved;
	bool	mFeatured;
	bool	mSuperFeatured;
	bool	mBoldTitle;
	bool	mHasPic;
	int		mGiftIconType;
	bool    mGallery;
	bool	mFeaturedGallery;
	GalleryResultCode mGalleryState;
	int     mCountryId;
	int     mCurrencyId;
	char *	mpZip;
	// nsacco 07/27/99
	int		mShippingOption;	// the shipping options for the item
	long	mShipRegionFlags;	// the regions the seller will ship to
	int		mDescLang;			// the lang of item description
	int		mSiteId;			// the site the item was listed on
};

typedef vector<clsListingItem*> ListingItemVector;

#endif // CLSLISTINGITEM_INCLUDE
