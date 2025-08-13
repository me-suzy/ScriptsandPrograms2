/* $Id: clsItemMap.cpp,v 1.5.204.1.80.1 1999/08/01 02:51:13 barry Exp $ */
// File: clsItemMap
//
// Author: Chad Musick (chad@ebay.com)
//
// Description: This is one of the two mapped files for dynamic
// listings. This one contains information that will change
// every time we update the listings, such as the items,
// the number of items in categories, and the lists of items
// in categories (in various sort and filter orders)
//
#include "clsItemMap.h"
#include "clsMappedFile.h"

#include <stdio.h>
#include <stdlib.h>

// Constructor -- we just mostly do casts here
// after we have our mapped file.
clsItemMap::clsItemMap(LPCSTR lpFileName)
{
	mpMap = new clsMappedFile(lpFileName);

	mpHeader = (headerEntry *) mpMap->GetBaseAddress();

	mpCategories = (categoryEntry *) ((char *) mpMap->GetBaseAddress() + mpHeader->categoryOffset);
	mpTextBase = (char *) ((char *) mpMap->GetBaseAddress() + mpHeader->textOffset);
	mpListsBase = (int32_t *) ((char *) mpMap->GetBaseAddress() + mpHeader->listsOffset);
	mpItemsBase = (itemEntry *) ((char *) mpMap->GetBaseAddress() + mpHeader->itemOffset);
//    mpUsers = (userEntry *) ((char *) mpMap->GetBaseAddress() + mpHeader->usersOffset);

	mTimeGenerated = (time_t) mpHeader->timeGenerated;
}

// We don't delete anything else, since it's just part of the mapped file.
clsItemMap::~clsItemMap()
{
	delete mpMap;
}   //lint !e1740 We know lotsa stuff doesn't get freed; they're just pointers to other hooey

// Static because RefreshMap doesn't know how to handle a member function.
static void ReplaceFile(LPCSTR lpOldFile, LPCSTR lpNewFile)
{
	remove(lpOldFile);
	rename(lpNewFile, lpOldFile);
}

// This is our constructor, except that we refresh the
// map file, rather than making a new one.
void clsItemMap::DoReplace(LPCSTR lpNewFile)
{
	mpMap->RefreshMap(ReplaceFile, lpNewFile);

	mpHeader = (headerEntry *) mpMap->GetBaseAddress();

	mpCategories = (categoryEntry *) ((char *) mpMap->GetBaseAddress() + mpHeader->categoryOffset);
	mpTextBase = (char *) ((char *) mpMap->GetBaseAddress() + mpHeader->textOffset);
	mpListsBase = (int32_t *) ((char *) mpMap->GetBaseAddress() + mpHeader->listsOffset);
	mpItemsBase = (itemEntry *) ((char *) mpMap->GetBaseAddress() + mpHeader->itemOffset);
//    mpUsers = (userEntry *) ((char *) mpMap->GetBaseAddress() + mpHeader->usersOffset);

	mTimeGenerated = (time_t) mpHeader->timeGenerated;
}

// Common (simple) routine to fill a list given enough information.
void clsItemMap::GetListOfItems(int32_t listOffset, int num, itemEntry **pEntriesTable) const
{
	int i;

	i = 0;

	while (num > 0)
	{
		pEntriesTable[i] = mpItemsBase + mpListsBase[listOffset + i];
		++i;
		--num;
	}

	return;
}

bool clsItemMap::GetNumAndOffsets(listingTypeEnum listingType, 
								  entryTypeEnum entryType, 
								  int numCategory, 
								  int *numAvailable, 
								  int *startingOffset) const
{

    categoryEntry *pEntry = &mpCategories[numCategory];

	switch (listingType)
	{
	case GoingListingType:
		switch (entryType)
		{
		case hotEntry:
			*numAvailable = pEntry->g_numHot;
			*startingOffset = pEntry->g_hotOffset;
			break;
		case featuredEntry:
			*numAvailable = pEntry->g_numFeatured;
			*startingOffset = pEntry->g_featuredOffset;
			break;
   		case normalEntry: 
			*numAvailable = pEntry->numGoing;
			*startingOffset = pEntry->goingOffset;
			break;

		}
		break;

	case NewListingType:
		switch (entryType)
		{
		case normalEntry: 
			*numAvailable = pEntry->numNew;
			*startingOffset = pEntry->newOffset;
			break;
		case hotEntry:
			*numAvailable = pEntry->n_numHot;
			*startingOffset = pEntry->n_hotOffset;
			break;
		case featuredEntry:
			*numAvailable = pEntry->n_numFeatured;
			*startingOffset = pEntry->n_featuredOffset;
			break;
		}
		break;

	case EndingListingType:
		switch (entryType)
		{
		case normalEntry: 
			*numAvailable = pEntry->numEnding;
			*startingOffset = pEntry->endingOffset;
			break;
		case hotEntry:
			*numAvailable = pEntry->e_numHot;
			*startingOffset = pEntry->e_hotOffset;
			break;
		case featuredEntry:
			*numAvailable = pEntry->e_numFeatured;
			*startingOffset = pEntry->e_featuredOffset;
			break;
		}
		break;

	case CurrentListingType:
		switch (entryType)
		{
		case hotEntry:
			*numAvailable = pEntry->numHot;
			*startingOffset = pEntry->hotOffset;
			break;
		case featuredEntry:
			*numAvailable = pEntry->numFeatured;
			*startingOffset = pEntry->featuredOffset;
			break;
        case normalEntry: 
        default:
			*numAvailable = pEntry->numCurrent;
			*startingOffset = pEntry->currentOffset;
			break;

		}
		break;

	case GalleryListingType:
		switch (entryType)
		{
		case featuredEntry:
			*numAvailable = pEntry->numGalleryFeatured;
			*startingOffset = pEntry->galleryFeaturedOffset;
			break;
        case normalEntry: 
        default:
			*numAvailable = pEntry->numGalleryNormal;
			*startingOffset = pEntry->galleryNormalOffset;
			break;

		}
		break;

	case CompletedListingType:
    case UnknownListingType:
    default:
		return false; // Shouldn't be here!
	}

	return true;	

}

// Fills a list with the request category, range, entry type, and listing type
// with items. Just figures out how many you actually get and where
// they start and then calls GetListOfItems.
//
// Returns the number of available items in the selected category, entry type
// and listing type (union of). It returns this whether or not it fills in
// any items.
//
// numItems is set to the number of items actually filled.
//
// This function will never attempt to fill more than last - first + 1
// items, except that if lastItem is < 0, lastItem will be set to the
// last available item.
int clsItemMap::GetItems(itemEntry **ppEntriesTable,
						 int *numItems, 
						 int numCategory, 
						 entryTypeEnum entryType,
						 listingTypeEnum listingType,
						 int firstItem /* = 0 */, int lastItem /* = -1 */) const
{
	int maxAvailable;
	int numAvailable;
	int numWanted;
	int startingOffset;

	// numCategories is the maximum category number, so the > is correct.
	if (numCategory < 0 || numCategory > mpHeader->numCategories || firstItem < 0 ||
		(mpCategories[numCategory].categoryNumber < 0))
	{
		*numItems = 0;
		return 0;
	}

	if (!GetNumAndOffsets(listingType, entryType, numCategory, &numAvailable, &startingOffset))
	{
        // Should never reach here. Ooops oops oops.
        *numItems = 0;
        return 0;
	}

    maxAvailable = numAvailable;

	// We chop off the head of the list.
	numAvailable -= firstItem;

	// And if we no longer have any, return.
	if (numAvailable <= 0)
	{
		*numItems = 0;
		return maxAvailable;
	}

	// Set the last item if it's < 0
	if (lastItem < 0)
		lastItem = maxAvailable - 1;

	// How many do we want?
	numWanted = lastItem - firstItem + 1;

	// Well, if we don't want any, don't give us any.
	if (numWanted <= 0)
	{
		*numItems = 0;
		return maxAvailable;
	}

	// Now, how many can we _have_?
	// This won't ever be more than numWanted.
	numAvailable = (numAvailable > numWanted ? numWanted : numAvailable);

	// We can't start below the list.
	if (startingOffset < 0)
	{
		*numItems = 0;
		return maxAvailable;
	}

	*numItems = numAvailable;
	GetListOfItems(startingOffset + firstItem, numAvailable, ppEntriesTable);

	return maxAvailable;
}


// Fills the entries table passed to this function with num random
// items of the given type and in the given category.
// Returns the number of random items actually filled in.
int clsItemMap::GetRandomItems(listingTypeEnum listingType, 
							   entryTypeEnum entryType,
							   int category, 
							   int numWantedTemp, 
							   itemEntry **ppEntriesTable) const
{
	int interval;
	int numAvailable;
	int numInInterval;
	int randomOffset;
	int startingOffset;
	int startOfInterval;
	int numWanted;

	numWanted = numWantedTemp;

	// Find the right offset into the items.map file to start at.
	if (!GetNumAndOffsets(listingType, entryType, category, &numAvailable, &startingOffset))
	{
        // Should never reach here. Ooops oops oops.        
        return 0;
	}

	if (numAvailable == 0)
		return 0;

	if (numWanted > numAvailable)
		numWanted = numAvailable;

	// Randomly select three items from this section of the file.
	// Do this by subdividing this section into num intervals and 
	// pulling a random item from each iterval. (That way, we don't
	// have to worry about repeating items in the random ones we've
	// already selected, with the added benefit of getting a more even
	// distribution across the listings.)
	
	numInInterval = numAvailable / numWanted;

	startOfInterval = 0;
	for (interval = 0; interval < numWanted; interval++)
	{
			
		// Quick bit of code to make sure all items are really considered,
		// by expanding the range of the final interval if necessary.
		if (interval == (numWanted - 1))
		{
			numInInterval = numAvailable - startOfInterval;
		}

		// Random number, please.
		randomOffset = rand() % numInInterval;

		// Pick a random item out of the number of items in this interval.
		ppEntriesTable[interval] = mpItemsBase + 
			mpListsBase[startingOffset + startOfInterval + randomOffset];
		
		startOfInterval += numInInterval;
	}

	return interval; 
}

// Recursively count our descendants, and add 1 for us.
int clsItemMap::CountSelfAndDescendants(categoryEntry *pCategory, 
									    int levels) const
{
	int ret;

	// We don't exist.
	if (levels < 0)
		return 0;

	// No children, but we still exist.
	if (levels == 0 || !pCategory->firstChild)
		return 1;

	pCategory = GetCategory(pCategory->firstChild);

	ret = 1; // 1 for ourselves.

	// We walk the siblings here, starting with firstChild.
	// All children of a parent are siblings, so this
	// has the effect of getting all the children.
	while (pCategory)
	{
		ret += CountSelfAndDescendants(pCategory, levels - 1);

		if (pCategory->rightSibling)
			pCategory = GetCategory(pCategory->rightSibling);
		else
			pCategory = NULL;
	}

	return ret;
}
#if 0
userEntry *clsItemMap::GetUsers(int *numUsers,
                                int *maxUsers,
                                int numCategory,
                                int firstUser,
                                int lastUser) const
{
    int32_t startingOffset;
	int numAvailable;
	int numWanted;

	// numCategories is the maximum category number, so the > is correct.
	if (numCategory < 0 || numCategory > mpHeader->numCategories || firstUser < 0 ||
		(mpCategories[numCategory].categoryNumber < 0))
	{
		*numUsers = 0;
        *maxUsers = 0;
		return NULL;
	}

    categoryEntry *pEntry = &mpCategories[numCategory];
    *maxUsers = numAvailable = pEntry->numUsers;
    startingOffset = pEntry->usersOffset;

	// We chop off the head of the list.
	numAvailable -= firstUser;

	// And if we no longer have any, return.
	if (numAvailable <= 0)
	{
		*numUsers = 0;
        return NULL;
	}

	// Set the last item if it's < 0
	if (lastUser < 0)
		lastUser = *maxUsers - 1;

	// How many do we want?
	numWanted = lastUser - firstUser + 1;

	// Well, if we don't want any, don't give us any.
	if (numWanted <= 0)
	{
		*numUsers = 0;
		return NULL;
	}

	// Now, how many can we _have_?
	// This won't ever be more than numWanted.
	numAvailable = (numAvailable > numWanted ? numWanted : numAvailable);

	// We can't start below the list.
	if (startingOffset < 0)
	{
		*numUsers = 0;
		return NULL;
	}

	*numUsers = numAvailable;
    return mpUsers + startingOffset;
}
#endif

// An item location routine.
// Returns -1, 0, and 1 for item is too big, just right, and too little respectively.
static int find_item(const void *pItemNumber, const void *pItem)
{
	return *((int32_t *) pItemNumber) - ((itemEntry *) pItem)->itemNumber;
}

// Tells us what the index number is for a particular item number -- this allows us to find
// occurrences of it in the category lists.
// Returns -1 if it could not be found.
long clsItemMap::FindItemIndex(int32_t itemNumber) const
{
	unsigned long maxItems;

	itemEntry *pItem;

	maxItems = (mpHeader->listsOffset - mpHeader->itemOffset) / sizeof (itemEntry);

	pItem = (itemEntry *) bsearch(&itemNumber, mpItemsBase, maxItems, sizeof (itemEntry),
		find_item);

	if (!pItem)
		return -1;

	return pItem - mpItemsBase;
}

struct itemAndData
{
	long mItemOffset;
	const clsItemMap *mpData;
};

static int compare_by_startdate_descending(const void *keyval, const void *itemOffset2)
{
	itemEntry *i1, *i2;
	const clsItemMap *pData = ((itemAndData *) keyval)->mpData;

	i1 = pData->GetItem(((itemAndData *) keyval)->mItemOffset);
	i2 = pData->GetItem(*((long *)itemOffset2));

	return i2->startTime - i1->startTime;
}

static int compare_by_enddate_ascending(const void *keyval, const void *itemOffset2)
{
	itemEntry *i1, *i2;
	const clsItemMap *pData = ((itemAndData *) keyval)->mpData;

	i1 = pData->GetItem(((itemAndData *) keyval)->mItemOffset);
	i2 = pData->GetItem(*((long *)itemOffset2));

	return i1->endTime - i2->endTime;
}

typedef int (*FindFunction)(const void *, const void *);

// Tells us whether or not the item is listed in the 'hot' section.
bool clsItemMap::ItemIsHotInCategory(categoryEntry *pCategory,
									 int listingType,
									 long itemOffset) const
{
	// We use a find helper to store the context for the search, since we
	// only have one of these objects, so we certainly can't store
	// it _here_ in a multithreaded environment.
	int numAvailable;
	int startingOffset;
	long *pOffset;
	FindFunction f;
	itemAndData findHelper;

	if (itemOffset < 0)
		return false;

	switch (listingType)
	{
	case GoingListingType:
		numAvailable = pCategory->g_numHot;
		startingOffset = pCategory->g_hotOffset;
		f = compare_by_enddate_ascending;
		break;

	case NewListingType:
		numAvailable = pCategory->n_numHot;
		startingOffset = pCategory->n_hotOffset;
		f = compare_by_startdate_descending;
		break;

	case EndingListingType:
		numAvailable = pCategory->e_numHot;
		startingOffset = pCategory->e_hotOffset;
		f = compare_by_startdate_descending;
		break;

	case CurrentListingType:
		numAvailable = pCategory->numHot;
		startingOffset = pCategory->hotOffset;
		f = compare_by_startdate_descending;
		break;

    case CompletedListingType:
    case UnknownListingType:
    default:
        // Should never reach here. Ooops oops oops.
		numAvailable = 0;
		break;
	}

	if (!numAvailable)
		return false;

	findHelper.mItemOffset = itemOffset;
	findHelper.mpData = this;

	// We do a bsearch now.
	pOffset = (long *) bsearch(&findHelper, mpListsBase + startingOffset, numAvailable,
		sizeof (long), f);

	if (!pOffset)
		return false;

	return true;
}

// Tells us whether or not the item is listed in the 'featured' section.
bool clsItemMap::ItemIsFeaturedInCategory(categoryEntry *pCategory,
									 int listingType,
									 long itemOffset) const
{
	// We use a find helper to store the context for the search, since we
	// only have one of these objects, so we certainly can't store
	// it _here_ in a multithreaded environment.
	int numAvailable;
	int startingOffset;
	long *pOffset;
	FindFunction f;
	itemAndData findHelper;

	if (itemOffset < 0)
		return false;

	switch (listingType)
	{
	case GoingListingType:
		numAvailable = pCategory->g_numFeatured;
		startingOffset = pCategory->g_featuredOffset;
		f = compare_by_enddate_ascending;
		break;

	case NewListingType:
		numAvailable = pCategory->n_numFeatured;
		startingOffset = pCategory->n_featuredOffset;
		f = compare_by_startdate_descending;
		break;

	case EndingListingType:
		numAvailable = pCategory->e_numFeatured;
		startingOffset = pCategory->e_featuredOffset;
		f = compare_by_startdate_descending;
		break;

	case CurrentListingType:
		numAvailable = pCategory->numFeatured;
		startingOffset = pCategory->featuredOffset;
		f = compare_by_startdate_descending;
		break;

    case CompletedListingType:
    case UnknownListingType:
    default:
        // Should never reach here. Ooops oops oops.
		numAvailable = 0;
		break;
	}

	if (!numAvailable)
		return false;

	findHelper.mItemOffset = itemOffset;
	findHelper.mpData = this;

	// We do a bsearch now.
	pOffset = (long *) bsearch(&findHelper, mpListsBase + startingOffset, numAvailable,
		sizeof (long), f);

	if (!pOffset)
		return false;

	return true;
}

// Tells us what position within the category the item holds in the given listing type.
// Use the actual item number -- don't use the index.
// Returns -1 if the item is not found in the specified category.
long clsItemMap::FindItemInCategory(categoryEntry *pCategory, 
									int listingType, 
									long itemOffset) const
{
	// We use a find helper to store the context for the search, since we
	// only have one of these objects, so we certainly can't store
	// it _here_ in a multithreaded environment.
	int numAvailable;
	int startingOffset;
	long *pOffset;
	FindFunction f;
	itemAndData findHelper;

	if (itemOffset < 0)
		return -1;

	switch (listingType)
	{
	case GoingListingType:
		numAvailable = pCategory->numGoing;
		startingOffset = pCategory->goingOffset;
		f = compare_by_enddate_ascending;
		break;

	case NewListingType:
		numAvailable = pCategory->numNew;
		startingOffset = pCategory->newOffset;
		f = compare_by_startdate_descending;
		break;

	case EndingListingType:
		numAvailable = pCategory->numEnding;
		startingOffset = pCategory->endingOffset;
		f = compare_by_enddate_ascending;
		break;

	case CurrentListingType:
		numAvailable = pCategory->numCurrent;
		startingOffset = pCategory->currentOffset;
		f = compare_by_startdate_descending;
		break;

	case GalleryListingType:
		numAvailable = pCategory->numGalleryNormal;
		startingOffset = pCategory->galleryNormalOffset;
		f = compare_by_startdate_descending;
		break;

    case CompletedListingType:
    case UnknownListingType:
    default:
        // Should never reach here. Ooops oops oops.
		numAvailable = 0;
		break;
	}

	if (!numAvailable)
		return -1;

	findHelper.mItemOffset = itemOffset;
	findHelper.mpData = this;

	// We do a bsearch now.
	pOffset = (long *) bsearch(&findHelper, mpListsBase + startingOffset, numAvailable,
		sizeof (long), f);

	if (!pOffset)
		return -1;

	return (pOffset - (mpListsBase + startingOffset));
}
