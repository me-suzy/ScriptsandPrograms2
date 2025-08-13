/*	$Id: clsDatabase.cpp,v 1.5 1999/03/07 08:16:50 josh Exp $	*/
//
//	File:		clsDatabase.cc
//
// Class:	clsDatabase
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 02/09/97 michael	- Created
//				- 06/20/97 tini		- added functions to handle renaming users.
//				- 09/02/97 wen		- added functions for listing items
//

#include "eBayKernel.h"
#include "clsListingItemList.h"

clsDatabase::clsDatabase(char *pHost)
{

	// Let's stash our own copy of the host name
	if (pHost)
	{
		mpHost	= new char[strlen(pHost) + 1];
		strcpy(mpHost, pHost);
	}
	else
		mpHost	= (char *)0;
	
	mpListingItemList= NULL;

	return;
}

clsDatabase::~clsDatabase()
{
	delete []mpHost;

	RemoveListingItems();

	return;
}


// ******* Start of RebuildList functions *****
//
// The following functions are designed for RebuildList
// Please use them as described:
//
// 1) call PrepareActiveListingItems() or 
//		   PrepareCompletedListingItems()
// 2) Call any other functions as needed
// 3) Call RemoveListingItems() to cleanup
//
// Please note that it takes a few minutes to Prepare
// the Listing Items (that means it is too slow for 
// other application) and it needs a lot of memory.
// Also, the returned clsListingItem contains subset of
// information of clsItems.

void clsDatabase::PrepareListingItems(MarketPlaceId MarketplaceId, 
									  int	 ListType,
									  time_t EndTime)
{
	// Clean up before create a new one
	delete mpListingItemList;
	
	mpListingItemList = new clsListingItemList(MarketplaceId, 
								this, 
								ListType, 
								EndTime);

	mpListingItemList->Initialize();
}

void clsDatabase::RemoveListingItems()
{
	delete mpListingItemList;
	mpListingItemList = NULL;
}

void clsDatabase::GetListingItemsInCategory(int CatId, ListingItemVector* pItemVector)
{
	mpListingItemList->GetItemsInCategory(CatId, pItemVector);
}

int clsDatabase::GetNumberOfListingItemsInCategory(int CatId)
{
	return mpListingItemList->GetNumberOfItemsInCategory(CatId);
}

int clsDatabase::GetNumberOfNewTodayItemsInCategory(int CatId)
{
	return mpListingItemList->GetNumberOfNewTodayItemsInCategory(CatId);
}

int  clsDatabase::GetNumberOfEndingTodayItemsInCategory(int CatId)
{
	return mpListingItemList->GetNumberOfEndingTodayItemsInCategory(CatId);
}


// Retrieve super featured listing items
void clsDatabase::GetSuperFeaturedListingItems(ListingItemVector* pItemVector)
{
	mpListingItemList->GetSuperFeaturedListingItems(pItemVector);
}

// Retrieve super featured listing items
void clsDatabase::GetHotListingItems(ListingItemVector* pItemVector)
{
	mpListingItemList->GetHotListingItems(pItemVector);
}

// Get Going item count
int clsDatabase::GetGoingItemCountInCategory(int CatId)
{
	return mpListingItemList->GetGoingItemCountInCategory(CatId);
}

// GetAllGoingItemList()
ListingItemVector* clsDatabase::GetAllGoingItemList()
{
	return mpListingItemList->GetAllGoingItemList();
}

#if 0
// returns everything
ListingItemVector* clsDatabase::GetAllItemsList()
{
	return mpListingItemList->GetAllItemsList();
}
#endif

//
// *********** End of RebuildList functions *************
//
