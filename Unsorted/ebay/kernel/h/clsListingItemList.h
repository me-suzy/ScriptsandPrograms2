/*	$Id: clsListingItemList.h,v 1.5 1999/03/07 08:16:46 josh Exp $	*/
//
//	File:		clsListingItemList.h
//
//	Class:		clsListingItemList
//
//	Author:		Wen Wen (wen@ebay.com)
//
//	Function:
//				class for managing listing items
//
//	Modifications:
//				- 08/31/97 Wen - Created
//
#ifndef CLSLISTINGITEMLIST_INCLUDE
#define CLSLISTINGITEMLIST_INCLUDE

class clsDatabase;
#include "eBayTypes.h"
#include "clsListingItem.h"

//#include <fstream.h>
#include <stdio.h>
#include <string.h>
#include <time.h>

typedef struct
{
	ListingItemVector::iterator	First;
	ListingItemVector::iterator	Last;
} ItemListPointer;

class clsListingItemList
{
public:
	clsListingItemList(MarketPlaceId MarketplaceId, 
						clsDatabase* pDB, 
						int ListType,  // 0 - Active, 1 - Completed
						time_t EndTime);
	~clsListingItemList();

	// Initialize
	void Initialize();

	// Clean up
	void CleanUp();

	// The difference here is that we don't sort it into categories, count it,
	// twist, fold, mutilate, or spindle. We just get the list of all the items.
	// You're responsible for your own cleanup if you use this.
	void GetAllListingItemsNoInitialize(ListingItemVector *pItemList);

	// Retrieve items for the sepecified category
	void GetItemsInCategory(int CatId, ListingItemVector* pItemVector);

	// number of items in the specified category
	int GetNumberOfItemsInCategory(int CatId);
	int GetNumberOfNewTodayItemsInCategory(int CatId);
	int GetNumberOfEndingTodayItemsInCategory(int CatId);

	// Retrieve super featured listing items
	void GetSuperFeaturedListingItems(ListingItemVector* pItemVector);

	// Retrieve super featured listing items
	void GetHotListingItems(ListingItemVector* pItemVector);

	// Get Going item count
	int GetGoingItemCountInCategory(int CatId);

	// Get All Going Item
	ListingItemVector* GetAllGoingItemList();

	// sort function for vector sorting the item in the order of category
	static bool ItemsInCategoryIdOrder(clsListingItem* pItem1, clsListingItem* pItem2);

	// Get All Going Item
//	ListingItemVector* GetAllItemsList();

protected:
	ListingItemVector	mpItemList[2500];
	ListingItemVector	mSuperFeaturedItemList;
	ListingItemVector	mHotItemList;
	ListingItemVector	mGoingItemList;
	ItemListPointer*	mpItemListPointers;
	int**				mpCategoryChildList;
	int*				mpCategoryItemNumber;
	int*				mpCategoryIds;
	int*				mpNewTodayCount;
	int*				mpEndingTodayCount;
	int*				mpGoingItemCount;
	int					mMaxCategoryId;

	clsDatabase*		mpDatabase;
	MarketPlaceId		mMarketplaceId;
	int					mListType;
	time_t				mEndTime;
};

#endif // CLSLISTINGITEMLIST_INCLUDE
