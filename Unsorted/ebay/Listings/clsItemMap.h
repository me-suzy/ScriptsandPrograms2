/* $Id: clsItemMap.h,v 1.11.64.1.80.1 1999/08/01 02:51:13 barry Exp $ */
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
// The ListingsProduce project also depends on this file,
// and will use CLSITEMMAP_WANT_STRUCTURES_ONLY when included there.

#ifndef CLSITEMMAP_INCLUDE
#define CLSITEMMAP_INCLUDE
#ifndef CLSITEMMAP_WANT_STRUCTURES_ONLY

#ifndef _EBAY_H
#include "ebay.h"
#endif

#ifdef _MSC_VER
#include <windows.h>
#endif

#ifndef _INC_TIME
#include <time.h>
#endif

// The following structure fields are all assumed to
// be packed, with no padding inbetween.  They are also
// assumed to have been written in the byte order of
// the _target_ host, not of the generating host.

#endif 

#ifdef E114_prod
// One record per category, immediately follows
// the header block
//
							//  x bytes
#endif
/*
This contains the definition of headerEntry, categoryEntry and itemEntry
 */
#include "entries.h"

struct userEntry {
    int32_t userNumber;
    int32_t pageNumber;
    int32_t titleTextOffset;
};

// Here go the item offset lists. These are
// no structure, just int32_t's.

#ifndef CLSITEMMAP_WANT_STRUCTURES_ONLY

// The 'entry types' we can have.
typedef enum
{
	normalEntry	= 0,
	hotEntry,
	featuredEntry,
	bigticketEntry
} entryTypeEnum;

// The 'listing types' we can have.
// We use UnknownListingType to make
// iteration safe and logical.
typedef enum
{
	CurrentListingType,
	NewListingType,
	EndingListingType,
	CompletedListingType,
	GoingListingType,
	GalleryListingType,
	UnknownListingType
} listingTypeEnum;

class clsMappedFile;

class clsItemMap {

private:
	// Cast pointers to the various parts of the file.
	char			*mpTextBase;
	itemEntry		*mpItemsBase;
	int32_t			*mpListsBase;
	headerEntry		*mpHeader;
	categoryEntry	*mpCategories;
//    userEntry       *mpUsers;
	clsMappedFile	*mpMap;

	time_t			mTimeGenerated;

	// Common routine to get a list of items given a number,
	// an offset, and a place to store them.
	void GetListOfItems(int32_t listOffset, int num, itemEntry **pEntriesTable) const;

	bool GetNumAndOffsets(listingTypeEnum listingType, 
						  entryTypeEnum entryType, 
						  int numCategory, 
						  int *numAvailable, 
						  int *startingOffset) const;

public:
	explicit clsItemMap(LPCSTR lpFileName);
	Defaults(clsItemMap);

	~clsItemMap();

	// Get the list of items.
	// Returns the total available items.
	// If last is less than 0, returns to the end,
	// if last is less than first (but not less than zero),
	// doesn't fill in anything but still returns total available.
	int GetItems(itemEntry **ppEntriesTable,
		int *numItems, int numCategory, entryTypeEnum entryType,
		listingTypeEnum listingType,
		int firstItem = 0, int lastItem = -1) const;

	int GetRandomItems(listingTypeEnum listingType, 
					   entryTypeEnum entryType,
					   int category, 
					   int numWanted, 
					   itemEntry **ppEntriesTable) const;

    // Returns the item 'itemNumber' -- or NULL if it does not exist
    // in the file.
    itemEntry *GetItem(int itemIndex) const
    { if (itemIndex < 0) return NULL; return mpItemsBase + itemIndex; }

	// Get a category object. We don't count 0 in numCategories, so the > check is correct.
	categoryEntry *GetCategory(int num) const
	{ if (num < 0 || num > mpHeader->numCategories || (mpCategories[num].categoryNumber < 0)) 
	  return NULL; if (mpCategories[num].categoryNumber != num) return NULL; return mpCategories + num; }

	// We use this to tell the users how out of date we are.
	time_t GetTimeGenerated() const { return mTimeGenerated; }

	// Get the titles from the text pool.
	const char *GetTitle(itemEntry *pItem) const { return mpTextBase + pItem->titleTextOffset; }
	const char *GetTitle(categoryEntry *pCategory) const { return mpTextBase + pCategory->titleTextOffset; }

	//    const char *GetTitle(userEntry *pUser) const { return mpTextBase + pUser->titleTextOffset; }

	// This is CountSelfAndDescendants for the top level --
	// we'd like to use numCategories, but there are gaps, so we can't.
	int GetNumberOfCategories() const { return CountSelfAndDescendants(mpCategories + 0, 10); }

	// Tells us how many categories we have beneath us and down [levels], including ourselves.
	// It's written recursively.
	int CountSelfAndDescendants(categoryEntry *pCategory, int levels) const;

//    userEntry *GetUsers(int *numUsers,
//        int *maxUsers,
//        int numCategory,
//        int firstUser = 0,
//        int lastUser = -1) const;

	void DoReplace(LPCSTR lpName);

	// Tells us what position within the category the item holds in the given listing type.
	// Use the item index -- don't use the actual item number.
	// Returns -1 if the item is not found in the specified category.
	long FindItemInCategory(categoryEntry *pCategory, int listingType, long itemIndex) const;

	// Returns true if the item is listed within the 'hot' section for the category,
	// and false if it isn't.
	bool ItemIsHotInCategory(categoryEntry *pCategory,
		int listingType,
		long itemOffset) const;

	// Returns true if the item is listed within the 'featured' section for the category,
	// and false if it isn't.
	bool ItemIsFeaturedInCategory(categoryEntry *pCategory,
		int listingType,
		long itemOffset) const;

    // Tells us what the index number is for a particular item number -- this allows us to find
	// occurrences of it in the category lists.
	// Returns -1 if it could not be found.
	long FindItemIndex(int32_t itemNumber) const;
};

#endif /* CLSITEMMAP_WANT_STRUCTURES_ONLY */
#endif /* CLSITEMMAP_INCLUDE */
