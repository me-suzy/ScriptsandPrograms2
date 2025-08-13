/*	$Id: clsFillHeader.h,v 1.8.2.1.52.1 1999/08/01 02:51:16 barry Exp $	*/

// Modified to support UK map file
// Steve Yan  3/25/99
// modifications
//		- 06/09/99	nsacco	Modified MapFileTypeEnum for Australia site


#ifndef CLSFILLHEADER_INCLUDE
#define CLSFILLHEADER_INCLUDE

#include "clsPackedStructures.h"

// This is the top of our descending chain --
// we fill this 'first', but it ends up finishing
// last because it calls on categories to fill
// themselves, which in turn call on the items to
// fill themselves.
//
// We'll do a cursory fill of things here first,
// though, and then the details will be filled
// in by the lower order classes.

// Class forward references.
class clsTextPool;
class ofstream;
class clsCategory;
class clsCategories;
class clsListingItem;

#include "vector.h"
#include "list.h"

#include <time.h>


typedef enum
{
	mapFileTypeNotSpecified = 0,
	mapFileAllItems		= 1,
	mapFileUKItems		= 2,
	mapShippingInternationally	= 64,	// DO NOT CHANGE THIS!!!
	mapFileOnlyUKItems	= 4,			// LISTINGSPRODUCE will break if we get too many map files
	mapFileLA	= 5,					
	mapFileAUItems = 6,
	mapFileOnlyAUItems = 7
} MapFileTypeEnum;


class clsFillHeader
{
private:

	// These 2 members are new for generating multiple map files
	MapFileTypeEnum	*	mpMapFileType;
	int					mNumOfMapFiles;


	// This holds the title for each item
	char ** mppItemTitle;

	headerEntry		*	mpHeader;
	categoryEntry ** mppCategories;

	itemEntry * mpItems;
	itemEntry ** mppItems;

    // How many items we have.

    int mNumItems;
    int	* mpNumItems;

	// Pointer to the vector of items -- this is actually
	// all of the various lists, and we count on the categories
	// to keep track of what it is that they want.
	// We use a vector rather than a list because much of
	// the logic requires a RandomAccessIterator for
	// efficiency.

	vector<int32_t> ** mppLists;

	// Pointer to a vector which contains one item offset for
	// each item -- we use this as an indirect reference vector
	// to partition, sort and mutilate the item list without
	// affecting the real one.
	// It can't be a list because the list class doesn't have
	// most of the functions we need.

	// This is only used to partition.
	vector<int32_t> ** mppReferenceList;

	// The 'work' is done here -- adding together child category
	// vectors to sort them together, mostly.
	vector<int32_t> ** mppWorkingList;

	// The common text block.
	clsTextPool ** mppText;

	// The time we started generating stuff.
	time_t mTime;

	// These are the static variables necessary to support the
	// static partition functions.

	// We need a static member so that the static functions can reference it.
	static time_t mStaticTime;
	static int32_t mStaticCategory;
	static itemEntry *mpStaticItems;

	enum
	{
		e_currentItems,
		e_currentItemsFeatured,
		e_currentItemsHot,
		e_newItems,
		e_newItemsFeatured,
		e_newItemsHot,
		e_endingItems,
		e_endingItemsFeatured,
		e_endingItemsHot,
		e_goingItems,
		e_goingItemsFeatured,
		e_goingItemsHot,
		e_gallery,
		e_galleryFeatured
	};

	// Check if an item belongs to the map file type
	bool isThisMapFileType(int mapFileIndex, int itemIndex);

	// void FillWithEachChild(categoryEntry *pEntry, int do_what);
	void FillWithEachChild(categoryEntry *pEntry, int do_what, int mapFileIndex);

	void FillCategoryItemListLeaf(categoryEntry *pEntry, 
		vector<int32_t>::iterator begin,
		vector<int32_t>::iterator end,
		int mapFileIndex);

	void FillOutIntVectorsForTheSpecificMapFileTypes();


public:

	// constructor which fill out the 2 new member variables
	clsFillHeader(MapFileTypeEnum * arrayOfMapFileType, int numberOfArrayElements);

	~clsFillHeader();

    // Calling this fills in all of the structures necessary
    // to write the binary file, but does not write it.
    // (Use WriteBinaryToStream for that.)
	void Run();

	// Fill in the category entry structures, without item lists.
	// A special version for the top level category.


	void FillTopCategoryNoItems(categoryEntry *pEntry,
							    clsCategories *pCategories,
								int mapFileIndex);

	void FillCategoryNoItems(clsCategory *pCategory,
							 categoryEntry *pEntry,
							 clsCategories *pCategories,
							 int mapFileIndex); 

	void FillItem(itemEntry *pEntry,
				  clsListingItem *pItem,
				  int itemIndex);

	// overloaded function, 2nd param is the index to the item in mpItems
	void FillItem(itemEntry *pEntry,
				  int itemIndex,
				  int mapFileIndex);

	// And to finish off the category after the rest has been done.
	void FillCategoryItemLists(categoryEntry *pCategory, int mapFileIndex);

    // Writes a packed file onto pStream. The reader from
    // the 'listings' project knows how to use this file.
	void WriteBinaryToStream(ofstream * pStream, int mapFileIndex);

	bool isThisMapFileType(MapFileTypeEnum mapFileType, int itemIndex);

	// These are all static functions to partition or sort vectors
	// of items to make up the item offset lists.
	static bool isInCategory(int32_t item);
	static bool isHot(int32_t item);
	static bool isEnding(int32_t item);
	static bool isNew(int32_t item);
	static bool isGoing(int32_t item);
	static bool isFeatured(int32_t item);
	static bool isAdult(int32_t item);
	static bool isGallery(int32_t item);
	static bool isGalleryFeatured(int32_t item);
	static bool sortByStartDateDescending(int32_t item, int32_t item2);
	static bool sortByEndDateAscending(int32_t item, int32_t item2);
	static bool sortByCategory(int32_t item, int32_t item2);

};

#endif /* CLSFILLHEADER_INCLUDE */
