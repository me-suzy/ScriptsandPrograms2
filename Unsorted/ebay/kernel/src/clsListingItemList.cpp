/*	$Id: clsListingItemList.cpp,v 1.6 1999/04/28 05:35:21 josh Exp $	*/
//
//	File:		clsListingItemList.cpp
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
#include "eBayKernel.h"
#include "clsListingItemList.h"

//
// constructor
//
clsListingItemList::clsListingItemList(MarketPlaceId MarketplaceId, 
									   clsDatabase* pDB,
									   int			ListType, // 1 for active; 0 for completed
									   time_t EndTime)
{
	mMarketplaceId	= MarketplaceId;
	mpDatabase		= pDB;
	mListType		= ListType;
	mEndTime		= EndTime;

	mpCategoryIds	= NULL;
	mpCategoryChildList = NULL;
	mpItemListPointers = NULL;
	mpCategoryItemNumber = NULL;
	mpNewTodayCount = NULL;
	mpEndingTodayCount = NULL;
	mpGoingItemCount = NULL;
}

//
//	destructor
//
clsListingItemList::~clsListingItemList()
{
	CleanUp();
}

//
// A sort function used by vector to sort item in the order of category id
//
bool clsListingItemList::ItemsInCategoryIdOrder(clsListingItem* pItem1, clsListingItem* pItem2)
{
	return pItem1->GetCategoryId() < pItem2->GetCategoryId();
}

//
// Init --  Retrieve all active items from database, 
//			sort the Items in the order of their category id, 
//			Set the indexes to the first and the last items of each category,
//			Prepare the array for keeping the number of items in each category,
//			retrieve a child category ids for each category
//
void clsListingItemList::Initialize()
{
	int							CatId;
	int							i;
	int*						pCategoryIds;
	ListingItemVector::iterator	iItem;
	int		HotItemLimit = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetHotItemCount();
	time_t	NewToday = mEndTime - ONE_DAY;
	time_t	EndingToday = mEndTime + ONE_DAY;
	time_t  ThreeHours  = mEndTime + 3 * 60 * 60;

	// get the max category id
	mMaxCategoryId = mpDatabase->GetMaxCategoryId(mMarketplaceId);

	// build the mpItemListPointers (leaf only)
	mpItemListPointers = new ItemListPointer[mMaxCategoryId + 1];
	mpNewTodayCount    = new int[mMaxCategoryId + 1];
	mpEndingTodayCount = new int[mMaxCategoryId + 1];
	mpGoingItemCount   = new int[mMaxCategoryId + 1];

	memset(mpItemListPointers, 0, (mMaxCategoryId + 1)*sizeof(ItemListPointer));
	memset(mpNewTodayCount,    0, (mMaxCategoryId + 1)*sizeof(mpNewTodayCount[0]));
	memset(mpEndingTodayCount, 0, (mMaxCategoryId + 1)*sizeof(mpEndingTodayCount[0]));
	memset(mpGoingItemCount, 0, (mMaxCategoryId + 1)*sizeof(mpGoingItemCount[0]));

	// create vector array for items
	// mpItemList = new ListingItemVector [mMaxCategoryId + 1];

	//
	// set the number of items for each category to -1
	//
	mpCategoryItemNumber = new int [mMaxCategoryId + 1];
	for (i = 1; i <= mMaxCategoryId; i++)
	{
		mpCategoryItemNumber[i] = -1;
	}

	//
	// retrieve for the child list for each category
	// the child list should be ended with -1
	//
	mpCategoryChildList = new int*[mMaxCategoryId + 1];
	for (i = 1; i <= mMaxCategoryId; i++)
	{
		mpDatabase->GetChildLeafCategoryIds(mMarketplaceId, i, &pCategoryIds);
		mpCategoryChildList[i] = pCategoryIds;
	}

	//
	// Retrieve the data
	//
	mpDatabase->Begin();
	mpDatabase->SetReadOnly();
	mpDatabase->GetListingItems(mMarketplaceId, mEndTime, mListType, mpItemList);
	mpDatabase->End();

	// done for completed listing
	if (mListType == 0)
		return;

	//
	// Scan the item list to build super featured item list,
	// hot item list, number of new today, ending today and going items in each
	// category
	//
	for (CatId = 1; CatId <= mMaxCategoryId; CatId++)
	{
		for (iItem = mpItemList[CatId].begin(); iItem != mpItemList[CatId].end(); iItem++)
		{
			// build super featured item list
			if ((*iItem)->IsSuperFeatured())
			{
				mSuperFeaturedItemList.push_back(*iItem);
			}

			// build hot item list
			if ((*iItem)->GetBidCount() > HotItemLimit 
				&& !(*iItem)->IsReserved())
			{
				mHotItemList.push_back(*iItem);
			}

			// Get number of items new today
			if ((*iItem)->GetStartTime() >= NewToday)
			{
				mpNewTodayCount[CatId]++;
			}

			// get number of items ending today
			if ((*iItem)->GetEndTime() <= EndingToday)
			{
				mpEndingTodayCount[CatId]++;
			}

			// Get number of item ending in three hours
			if ((*iItem)->GetEndTime() <= ThreeHours)
			{
				mpGoingItemCount[CatId]++;
				mGoingItemList.push_back(*iItem);
			}
		}
	}
}

// The difference here is that we don't sort it into categories, count it,
// twist, fold, mutilate, or spindle. We just get the list of all the items.
// You're responsible for your own cleanup if you use this.
void clsListingItemList::GetAllListingItemsNoInitialize(ListingItemVector *pItemList)
{
	mpDatabase->GetAllActiveItems(pItemList);
//	mpDatabase->GetAllActiveItemsAllTable(pItemList);
	return;
}

//
// CleanUp
//
void clsListingItemList::CleanUp()
{
	int	i;
	ListingItemVector::iterator	iItem;

	// If we don't have this, we didn't initialize, so don't clean up.
	if (!mpItemListPointers)
		return;

	for (i = 1; i <= mMaxCategoryId; i++)
	{
		for (iItem = mpItemList[i].begin(); iItem != mpItemList[i].end(); iItem++)
		{
			delete *iItem;
		}
		mpItemList[i].erase(mpItemList[i].begin(), mpItemList[i].end());
	}
	mSuperFeaturedItemList.erase(mSuperFeaturedItemList.begin(), mSuperFeaturedItemList.end());
	mHotItemList.erase(mHotItemList.begin(), mHotItemList.end());
	mGoingItemList.erase(mGoingItemList.begin(), mGoingItemList.end());

	delete [] mpCategoryIds;
	delete [] mpItemListPointers;
	delete [] mpCategoryItemNumber;
	delete [] mpNewTodayCount;
	delete [] mpEndingTodayCount;
	delete [] mpGoingItemCount;

	if (mpCategoryChildList)
	{
		for (i = 1; i <= mMaxCategoryId; i++)
		{
			delete [] mpCategoryChildList[i];
		}
		delete [] mpCategoryChildList;
	}
}

//
// Return a vector containing all items in the specified category
// (including the items in the child categories)
//
void clsListingItemList::GetItemsInCategory(int CatId, ListingItemVector* pItemVector)
{
	int*	pChildList;
	int		i = 0;
	int		ChildCatId;
	ListingItemVector::iterator	iItem;

	// prepare the vector
	pItemVector->erase(pItemVector->begin(), pItemVector->end());
	pItemVector->reserve(GetNumberOfItemsInCategory(CatId));

	pChildList = mpCategoryChildList[CatId];
	while (pChildList && (ChildCatId = pChildList[i]) != -1)
	{
		for (iItem = mpItemList[ChildCatId].begin(); iItem != mpItemList[ChildCatId].end(); iItem++)
		{
			pItemVector->push_back(*iItem);
		}
		i++;
	}
}

//
// Return number of items in the specified category
// (including the items in the child categories)
//
int clsListingItemList::GetNumberOfItemsInCategory(int CatId)
{
	int*	pChildList;
	int		i = 0;
	int		ChildCatId;

	if (mpCategoryItemNumber[CatId] == -1)
	{
		// init
		mpCategoryItemNumber[CatId] = 0;

		// Get child categories
		pChildList = mpCategoryChildList[CatId];
		while (pChildList && (ChildCatId = pChildList[i]) != -1)
		{
			mpCategoryItemNumber[CatId] += mpItemList[ChildCatId].size();
			i++;
		}
	}

	return mpCategoryItemNumber[CatId];
}

// Retrieve super featured listing items
void clsListingItemList::GetSuperFeaturedListingItems(ListingItemVector* pItemVector)
{
	// prepare the vector
	pItemVector->erase(pItemVector->begin(), pItemVector->end());
	pItemVector->reserve(mSuperFeaturedItemList.size());

	*pItemVector = mSuperFeaturedItemList;
}

// Retrieve super featured listing items
void clsListingItemList::GetHotListingItems(ListingItemVector* pItemVector)
{
	// prepare the vector
	pItemVector->erase(pItemVector->begin(), pItemVector->end());
	pItemVector->reserve(mHotItemList.size());

	*pItemVector = mHotItemList;
}

//
// Return number of new today items in the specified category
// (including the items in the child categories)
//
int clsListingItemList::GetNumberOfNewTodayItemsInCategory(int CatId)
{
	int*	pChildList;
	int		i = 0;
	int		ChildCatId;
	int		NumberOfItems = 0;

	// Get child categories
	pChildList = mpCategoryChildList[CatId];
	while (pChildList && (ChildCatId = pChildList[i]) != -1)
	{
		NumberOfItems +=  mpNewTodayCount[ChildCatId];
		i++;
	}

	return NumberOfItems;
}

//
// Return number of ending today items in the specified category
// (including the items in the child categories)
//
int clsListingItemList::GetNumberOfEndingTodayItemsInCategory(int CatId)
{
	int*	pChildList;
	int		i = 0;
	int		ChildCatId;
	int		NumberOfItems = 0;

	// Get child categories
	pChildList = mpCategoryChildList[CatId];
	while (pChildList && (ChildCatId = pChildList[i]) != -1)
	{
		NumberOfItems +=  mpEndingTodayCount[ChildCatId];
		i++;
	}

	return NumberOfItems;
}


//
// Return number of items ending in three hours in the specified category
// (including the items in the child categories)
//
int clsListingItemList::GetGoingItemCountInCategory(int CatId)
{
	int*	pChildList;
	int		i = 0;
	int		ChildCatId;
	int		NumberOfItems = 0;

	// Get child categories
	pChildList = mpCategoryChildList[CatId];
	while (pChildList && (ChildCatId = pChildList[i]) != -1)
	{
		NumberOfItems +=  mpGoingItemCount[ChildCatId];
		i++;
	}

	return NumberOfItems;
}

//
// Get All going items
//
ListingItemVector* clsListingItemList::GetAllGoingItemList()
{
	return &mGoingItemList;
}

#if 0
// return everything
ListingItemVector* clsListingItemList::GetAllItemsList()
{ 
	return mpItemList;
}
#endif
