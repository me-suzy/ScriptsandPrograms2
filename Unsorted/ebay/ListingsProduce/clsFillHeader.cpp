/* $Id: clsFillHeader.cpp,v 1.11.2.1.52.1 1999/08/01 02:51:16 barry Exp $ */

// Modified to support generating UK map file. 
// 3/25/99  Steve Yan
// modifications:
//	06/09/99	nsacco	Modified to support Australia site

#include "clsDatabase.h"
#include "clsFillHeader.h"
#include "clsTextPool.h"

#include "clsCategory.h"
#include "clsCategories.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsListingItem.h"
#include "clsListingItemList.h"
#include "clsItemsCache.h"
#include "clsUtilities.h"
#include "clsApp.h"

#include <iostream.h>
#include <fstream.h>

// #include "algo.h"

// Undef this to record the times various things took
// in clock time (processor time) and real time.
#define RECORD_TIME

typedef int (*pVoidFunc)(const void *, const void *);

// We must declare our static member variables to make space for them.
time_t clsFillHeader::mStaticTime;

int32_t clsFillHeader::mStaticCategory;
itemEntry * clsFillHeader::mpStaticItems;


clsFillHeader::clsFillHeader(MapFileTypeEnum * arrayOfMapFileType, int numberOfArrayElements)
{	
	int	i;

	assert(arrayOfMapFileType);
	assert(numberOfArrayElements > 0);

	mpMapFileType	= arrayOfMapFileType;
	mNumOfMapFiles	= numberOfArrayElements;

	mpHeader	= new headerEntry[mNumOfMapFiles];
	assert(mpHeader);
	memset(mpHeader, '\0', sizeof (headerEntry) * mNumOfMapFiles);

	mppCategories	= new categoryEntry * [mNumOfMapFiles];
	assert(mppCategories);
	for ( i= 0; i<mNumOfMapFiles; ++i)
	{
		mppCategories[i] = NULL;
	}

	mppItems = new itemEntry * [mNumOfMapFiles];
	assert(mppItems);
	for ( i= 0; i<mNumOfMapFiles; ++i)
	{
		mppItems[i] = NULL;
	}

	mppLists = new vector<int32_t> * [mNumOfMapFiles];
	assert(mppLists);
	for ( i= 0; i<mNumOfMapFiles; ++i)
	{
		mppLists[i] = new vector<int32_t>;
	}

	mppText = new clsTextPool * [mNumOfMapFiles];
	assert(mppText);
	for ( i= 0; i<mNumOfMapFiles; ++i)
	{
		mppText[i] = new clsTextPool;
	}

	mppReferenceList = new vector<int32_t> * [mNumOfMapFiles];
	assert(mppReferenceList);
	for ( i= 0; i<mNumOfMapFiles; ++i)
	{
		mppReferenceList[i] = NULL;
	}	

	mppWorkingList = new vector<int32_t> * [mNumOfMapFiles];
	assert(mppWorkingList);
	for ( i= 0; i<mNumOfMapFiles; ++i)
	{
		mppWorkingList[i] = NULL;
	}	

	mpNumItems = new int[mNumOfMapFiles];
	for ( i= 0; i<mNumOfMapFiles; ++i)
	{
		mpNumItems[i] = 0;
	}	

	// We still need to keep these 2 members which holds all items
	mpItems = NULL;
	mNumItems = 0;

	mppItemTitle = NULL;

}

clsFillHeader::~clsFillHeader()
{
	int i;

	if(mppCategories) 
	{
		for (i = 0; i<mNumOfMapFiles; ++i)
			if(mppCategories[i]) 
				delete [] mppCategories[i];

		delete [] mppCategories;
	}

	if(mppItems) 
	{
		if (mpItems)
		{
			// make sure we do not free mpItems twice
			for (i = 0; i<mNumOfMapFiles; ++i)
				if(mppItems[i] && (mppItems[i] != mpItems)) 
					delete [] mppItems[i];
		}

		delete [] mppItems;
	}

	if(mppLists) 
	{
		for (i = 0; i<mNumOfMapFiles; ++i)
			if(mppLists[i]) 
				delete mppLists[i];

		delete [] mppLists;
	}

	if(mppText) 
	{
		for (i = 0; i<mNumOfMapFiles; ++i)
			if(mppText[i]) 
				delete mppText[i];

		delete [] mppText;
	}

	if(mppReferenceList) 
	{
		for (i = 0; i<mNumOfMapFiles; ++i)
			if(mppReferenceList[i]) 
				delete mppReferenceList[i];

		delete [] mppReferenceList;
	}

	if(mppWorkingList) 
	{
		for (i = 0; i<mNumOfMapFiles; ++i)
			if(mppWorkingList[i]) 
				delete mppWorkingList[i];

		delete [] mppWorkingList;
	}

	if (mpNumItems)
		delete [] mpNumItems;

	if (mpItems) 
		delete [] mpItems;

}

// A sort helper function.
// Returns negative if the first argument is less than the second,
// 0 if they are equal, and positive if the first is bigger. 
static int
compare_items(const itemEntry *pEntry1, const itemEntry *pEntry2)
{
	return pEntry1->itemNumber - pEntry2->itemNumber;
}

// This does:
//
// Fill in the known header pieces
// Make the categories and fill them in, except for items.
// Make all the items and fill them in, sorted by category.
// Finish off filling in the header.
// Make the item lists and fill the categories in more.
// We're done.
//
void clsFillHeader::Run()
{
	int numCategories;
	int numItems;
	int lastCategory;
	vector<int32_t>::iterator m;
	vector<int32_t>::iterator n;

	int i, p;
	clsCategories *pCategories;
	CategoryVector vCategories;
	CategoryVector::iterator j;
	ListingItemVector vItems;
	ListingItemVector::iterator k;
	clsListingItemList *pItemList;
#ifdef RECORD_TIME
	clock_t lastClock;
	time_t lastTime;
	clock_t clockDiff;
	time_t timeDiff;

	lastClock = clock();
	lastTime = time(NULL);

	cout << "Beginning run at " << lastTime << " with clock " << lastClock << ".\n";
	cout << "Clock cycles per second: " << CLOCKS_PER_SEC << ".\n";
	cout.flush();
#endif

	// We'll set this now and just use it everywhere
	// to delimit.
	mTime = time(NULL);
	mStaticTime = mTime;

	pCategories = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCategories();
	numCategories = pCategories->GetMaxCategoryId();

	// Get all of the categories.
	pCategories->All(&vCategories);

	// loop through for each requested map file
	for (p = 0; p<mNumOfMapFiles; ++p)
	{
		// We use a magic number for sanity checking -- we fill it in just before writing.
		mpHeader[p].magicNumber = 0;

		mpHeader[p].timeGenerated = (int32_t) mTime;

		// We know the categories will start at the
		// size of us, since we start at 0, and
		// they come exactly next.
		mpHeader[p].categoryOffset = sizeof (headerEntry);

		// Set some counts.
		mpHeader[p].numCategories = numCategories;

		// We set the text offset here -- it's the size of the number of
		// categories we have past the header entry -- the +1 is for the 0 category.
		mpHeader[p].textOffset = mpHeader[p].categoryOffset + sizeof (categoryEntry) * (numCategories + 1);

		// Add one for category 0.
		mppCategories[p] = new categoryEntry [numCategories + 1];

		// Fill in the category structs, sans item lists.

		// First, we make all the category objects bad, so that we don't
		// rely on having every number existant.
		// This _will_ leave them full of junk, and we'll just have to
		// trust that we never read junk.

		for (i = 0; i <= numCategories; ++i)
		{
			mppCategories[p][i].categoryNumber = -1;
		}

		// Iterate through and fill them, sans items.
		for (j = vCategories.begin(); j != vCategories.end(); ++j)
		{
			// Paranoia -- if they changed while we weren't looking and
			// got more categories.
			if ((*j)->GetId() > numCategories)
				continue;

			// Rather than sorting them (oh pain!), we just
			// pass in the right structure, so the results
			// will end up magically sorted.

			FillCategoryNoItems(*j, mppCategories[p] + (*j)->GetId(), pCategories, p);

			// We'll clean up at the same time -- we really can do this.
			// but we only delete it for the last map file, otherwise, we are accessing
			// memory location being freed --- access violation.
			if (mNumOfMapFiles - 1 == p) 
				delete *j;
		}

		// We have to handle the top level category slightly differently.
		FillTopCategoryNoItems(mppCategories[p] + 0, pCategories, p);

		#ifdef RECORD_TIME
			clockDiff = clock() - lastClock;
			timeDiff = time(NULL) - lastTime;
			lastClock = clock();
			lastTime = time(NULL);

			cout << "Elapsed time for Map File #" << p << " " << numCategories << " categories: "
				 << timeDiff << " real seconds, " << clockDiff << " clock cycles.\n";
			cout.flush();
		#endif

	}

	vCategories.clear();

	// Now we get all the active items, so we can count them.
	pItemList = new clsListingItemList(gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
		gApp->GetDatabase(), 1 /* Active */, mTime);

	// Unsorted.
	pItemList->GetAllListingItemsNoInitialize(&vItems);
	// And clean up.
	delete pItemList;

#ifdef RECORD_TIME
	clockDiff = clock() - lastClock;
	timeDiff = time(NULL) - lastTime;
	lastClock = clock();
	lastTime = time(NULL);

	cout << "Elapsed time for retrieving " << vItems.size() << " items: "
		 << timeDiff << " real seconds, " << clockDiff << " clock cycles.\n";
	cout.flush();
#endif

	// Set the sort order for the list to sort by category.
	//clsListingItem::CurrentSortMode = SortItemsByCategory;
	if (vItems.size())
		sort(vItems.begin(), vItems.end());

#ifdef RECORD_TIME
	clockDiff = clock() - lastClock;
	timeDiff = time(NULL) - lastTime;
	lastClock = clock();
	lastTime = time(NULL);

	cout << "Elapsed time for sorting " << vItems.size() << " items: "
		 << timeDiff << " real seconds, " << clockDiff << " clock cycles.\n";
	cout.flush();
#endif

	// Get the size, so that we know how many items to allocate.
	numItems = vItems.size();

	if (numItems > 0)
		mppItemTitle = new char * [numItems];

	// Allocate our items.
	mpItems = new itemEntry[numItems];
    mNumItems = numItems;

	// Loop through and fill the items.
	ListingItemVector::iterator end = vItems.end();

	for (i = 0, k = vItems.begin(); k != end; ++k, ++i)
	{
		FillItem(mpItems + i, *k, i);

		// And clean up at the same time.
//		delete *k;
	}
//	vItems.clear();

#ifdef RECORD_TIME
	clockDiff = clock() - lastClock;
	timeDiff = time(NULL) - lastTime;
	lastClock = clock();
	lastTime = time(NULL);

	cout << "Elapsed time for fill out mpItems: " 
		 << timeDiff << " real seconds, " << clockDiff << " clock cycles.\n";
	cout.flush();
#endif

	// This fills out mppItems for each map file type
	FillOutIntVectorsForTheSpecificMapFileTypes();

	// clean the items title strings
	for (i = 0; i < numItems; ++i)
		if (mppItemTitle[i])
			delete [] mppItemTitle[i];

	if (numItems > 0)
		delete [] mppItemTitle;

	for (p = 0; p<mNumOfMapFiles; ++p)
	{
		if (mpNumItems[p] > 0)
		{
			qsort(mppItems[p], mpNumItems[p], sizeof (itemEntry), (pVoidFunc)compare_items);
			mpStaticItems = mppItems[p];


			// Once we get here we actually have enough information to finish the
			// header, since we have done all the text we will, have done all
			// the categories we will, and all the items we will.
			// The item lists just end up after the rest.

			// We've already filled in the textOffset. The itemOffset becomes that
			// plus the alignment-safe size of the text.
			mpHeader[p].itemOffset = (int32_t) (mpHeader[p].textOffset + mppText[p]->GetSafeWriteSize());

			// We know how many items we have, so now we can add items * sizeof the items
			// to the item offset to get the lists offset.
			mpHeader[p].listsOffset = (int32_t) (mpHeader[p].itemOffset + (mpNumItems[p] * sizeof (itemEntry)));

			// And that's everything in the header structure.

			// Now we should fill in our item lists in the category structures.

			// First we make a vector with an entry for each item, so that we
			// can sort and partition.
			mppReferenceList[p] = new vector<int32_t>;
			// Also make somewhere that we can work.
			mppWorkingList[p] = new vector<int32_t>;

			// We know how many to reserve, so we'll do so.
			mppReferenceList[p]->reserve(mpNumItems[p]);
			mppWorkingList[p]->reserve(mpNumItems[p]);

			// One for each item.
			for (i = 0; i < mpNumItems[p]; ++i)
				mppReferenceList[p]->push_back(i);

			if (mppReferenceList[p]->size())
				sort(mppReferenceList[p]->begin(), mppReferenceList[p]->end(), sortByCategory);

			lastCategory = (mppItems[p] + *(mppReferenceList[p]->begin()))->categoryNumber;
			for (m = mppReferenceList[p]->begin(), n = mppReferenceList[p]->begin(); /* Nothing */; ++n)
			{
				if (n == mppReferenceList[p]->end() || (mppItems[p] + *n)->categoryNumber != lastCategory)
				{
					FillCategoryItemListLeaf(mppCategories[p] + lastCategory,
						m, n, p);
					if (n != mppReferenceList[p]->end())
					{
						m = n;
						lastCategory = (mppItems[p] + *n)->categoryNumber;
					}
				}
				if (n == mppReferenceList[p]->end())
					break;
			}

			// Now we fill the category lists recursively, so we'll just call it for the top level.
			FillCategoryItemLists(mppCategories[p] + 0, p);

			#ifdef RECORD_TIME
				clockDiff = clock() - lastClock;
				timeDiff = time(NULL) - lastTime;
				lastClock = clock();
				lastTime = time(NULL);

				cout << "Elapsed time for filling lists for map file [" << p << "]: "
					 << timeDiff << " real seconds, " << clockDiff << " clock cycles.\n";
				cout.flush();
			#endif
		}
		else
			cout << "No items for map file [" << p << "]." << endl;
	}

	// These are for the widgetsCache

		clsItemsCache widgetsCache;
		widgetsCache.FillAllCaches(&vItems, 0);
		for (i = 0, k = vItems.begin(); k != end; ++k, ++i)
		{
			delete *k;
		}
		vItems.clear();

#ifdef RECORD_TIME
	clockDiff = clock() - lastClock;
	timeDiff = time(NULL) - lastTime;
	lastClock = clock();
	lastTime = time(NULL);

	cout << "Elapsed time for filling Widgets caches: "
		 << timeDiff << " real seconds, " << clockDiff << " clock cycles.\n";
	cout.flush();
#endif

	return;
}


// Checks the map file type
bool clsFillHeader::isThisMapFileType(MapFileTypeEnum mapFileType, int itemIndex)
{
	bool bThisType = false;

	// TODO - add Canada
	switch(mapFileType)
	{
	case mapFileAllItems:
		bThisType = true;
		break;
	case mapFileUKItems:
		if ((Country_UK == (CountryCodes)mpItems[itemIndex].countryID) ||
		  ((int8_t)mapShippingInternationally <= (int8_t)mpItems[itemIndex].mapFileType))
			bThisType = true;
		break;
	case mapFileOnlyUKItems:
		if (Country_UK == (CountryCodes)mpItems[itemIndex].countryID)
			bThisType = true;
		break;
	// nsacco 06/09/99
	case mapFileAUItems:
		if ((Country_AU == (CountryCodes)mpItems[itemIndex].countryID) ||
		  ((int8_t)mapShippingInternationally <= (int8_t)mpItems[itemIndex].mapFileType))
			bThisType = true;
		break;
	case mapFileOnlyAUItems:
		if (Country_AU == (CountryCodes)mpItems[itemIndex].countryID)
			bThisType = true;
		break;
	//case mapFileCAItems:
	//	if ((Country_CA == (CountryCodes)mpItems[itemIndex].countryID) ||
	//	  ((int8_t)mapShippingInternationally <= (int8_t)mpItems[itemIndex].mapFileType))
	//		bThisType = true;
	//	break;
	//case mapFileOnlyCAItems:
	//	if (Country_CA == (CountryCodes)mpItems[itemIndex].countryID)
	//		bThisType = true;
	//	break;
	case mapFileLA:
		if (((int8_t)mapFileLA == (int8_t)mpItems[itemIndex].mapFileType) ||
			(((int8_t)mapShippingInternationally <= (int8_t)mpItems[itemIndex].mapFileType)) && 
			((int8_t)mapFileLA == (int8_t)((int8_t)mpItems[itemIndex].mapFileType - (int8_t)mapShippingInternationally)))
			bThisType = true;
		break;
	default:
		break;
	}
	
	return bThisType;
}

// This fills out mppItems, mpNumItems, mppReferenceList, mppWorkingList
void clsFillHeader::FillOutIntVectorsForTheSpecificMapFileTypes()
{
	int i, j, p;


	#ifdef RECORD_TIME
		clock_t lastClock;
		time_t lastTime;
		clock_t clockDiff;
		time_t timeDiff;

		lastClock = clock();
		lastTime = time(NULL);
	#endif

	// These are temporary vectors to retrieve the map file specific items
	vector<int32_t> ** mppTempList;
	vector<int32_t>::iterator m;

	mppTempList = new vector<int32_t> * [mNumOfMapFiles];
	assert(mppTempList);

	for (p=0; p<mNumOfMapFiles; ++p)
	{
		if (mapFileAllItems == (MapFileTypeEnum)mpMapFileType[p])
		{
			// If creating map file for all items, no need to create mppItems[p] again.
			mppItems[p] = mpItems;
			mpNumItems[p] = mNumItems;
			continue;
		}

		mppTempList[p] = new vector<int32_t>;
		mppTempList[p]->reserve(mNumItems);
		mpNumItems[p] = 0;	
		
		// One for each item.
		for (i = 0; i < mNumItems; ++i)
			if(isThisMapFileType(mpMapFileType[p], i))
			{
				mppTempList[p]->push_back(i);
			}
	
		mpNumItems[p] = mppTempList[p]->size();
		
		if (mpNumItems[p])
		{
			mppItems[p] = new itemEntry[mpNumItems[p]];
			for (j = 0, m = mppTempList[p]->begin(); m != mppTempList[p]->end(); ++j, ++m)
			{
				FillItem(mppItems[p] + j, *m, p);
			}
		}

		if(mppTempList[p]) 
			delete mppTempList[p];


		#ifdef RECORD_TIME
			clockDiff = clock() - lastClock;
			timeDiff = time(NULL) - lastTime;
			lastClock = clock();
			lastTime = time(NULL);

			cout << "Elapsed time for fill out mppItems[" << p << "]: "
				 << timeDiff << " real seconds, " << clockDiff << " clock cycles.\n";
			cout.flush();
		#endif

	}

	// Delete these temporaries when we are done
	if(mppTempList) 
		delete [] mppTempList;

}


// This fills in the top category entry structure, except for
// the item lists (which it invalidates).
// We'll fill the item lists later.
void clsFillHeader::FillTopCategoryNoItems(categoryEntry *pEntry,
										   clsCategories *pCategories, int p)
{
	clsCategory *pPseudoCategory;
	clsCategory *pChildCategory;

	// Set the title to Top
	pEntry->titleTextOffset = mppText[p]->AddString("Top");

	pEntry->categoryNumber = (int16_t)0;
	pEntry->isAdult = (int8_t) 0;
	pEntry->isLeaf = (int8_t) 0;
	pEntry->categoryLevel = (int8_t) 0;

	// We have no parent.
	// We have no brothers.
	// We have no sisters.
	// We have no family. 
	// (We are alone, oh woe is us!)
	pEntry->parentCategory = pEntry->leftSibling = pEntry->rightSibling = (int16_t) 0;

	// Oh wait, we do have children!
	// Kaloo Kalay!
	// We use a constructed pseudo-category to get this thing.
	pPseudoCategory = new clsCategory(0);
	pChildCategory = pCategories->GetCategoryFirstChild(pPseudoCategory);
	delete pPseudoCategory; // Go away.

	// If we have no first child, we're in deep trouble anyway. We might
	// as well exit rather than build a data file we know is corrupt.
	if (!pChildCategory)
	{
		fprintf(stdout, "Uh oh. Top category has no child categories? Aborting.\n");
		exit(1);
	}

	pEntry->firstChild = (int16_t)pChildCategory->GetId();

	// And the child needs to go away now.
	delete pChildCategory;

	// Now make the rest of the category safe for human consumption.
	// Setting the offsets to -1 tells the reader not to look, since
	// 0 is a valid offset.

	pEntry->currentOffset = -1;
	pEntry->numCurrent = 0;

	pEntry->endingOffset = -1;
	pEntry->numEnding = 0;

	pEntry->newOffset = -1;
	pEntry->numNew = 0;

	pEntry->goingOffset = -1;
	pEntry->numGoing = 0;

	pEntry->featuredOffset = -1;
	pEntry->numFeatured = 0;

	pEntry->hotOffset = -1;
	pEntry->numHot = 0;

	pEntry->galleryNormalOffset = -1;
	pEntry->numGalleryNormal = 0;

	pEntry->galleryFeaturedOffset = -1;
	pEntry->numGalleryFeatured = 0;

	pEntry->e_featuredOffset = -1;
	pEntry->e_numFeatured = 0;

	pEntry->e_hotOffset = -1;
	pEntry->e_numHot = 0;

	pEntry->n_featuredOffset = -1;
	pEntry->n_numFeatured = 0;

	pEntry->n_hotOffset = -1;
	pEntry->n_numHot = 0;

	pEntry->g_featuredOffset = -1;
	pEntry->g_numFeatured = 0;

	pEntry->g_hotOffset = -1;
	pEntry->g_numHot = 0;

	// And zero the things we use to make alignment correct.
	pEntry->forFutureUse = (int16_t)0;
	pEntry->forFutureUse2 = (int8_t)0;

	// And we're done -- we'll be back later for the lists!
	return;
}

// This fills in a category entry structure, except for
// the item lists (which it invalidates).
// We'll fill the item lists later.
void clsFillHeader::FillCategoryNoItems(clsCategory *pCategory,
										categoryEntry *pEntry,
										clsCategories *pCategories,
										int mapFileIndex)
{
	// Set the title.
	pEntry->titleTextOffset = mppText[mapFileIndex]->AddString(pCategory->GetName());

	// Fill in the obvious stuff.
	pEntry->categoryNumber = (int16_t) pCategory->GetId();
	pEntry->isAdult = (int8_t) pCategory->isAdult();
	pEntry->isLeaf = (int8_t) pCategory->isLeaf();
	pEntry->categoryLevel = (int8_t) pCategory->catLevel();

	// This is the parent.
	pEntry->parentCategory = (int16_t) pCategory->GetLevel1();

	// The left or previous sibling.
	pEntry->leftSibling = (int16_t) pCategory->GetPrevSibling();

	// The right or next sibling.
	pEntry->rightSibling = (int16_t) pCategory->GetNextSibling();

	// If we're a leaf, we have no children.
	if (pCategory->isLeaf())
	{
		pEntry->firstChild = (int16_t)0;
	}
	else // Otherwise, figure out what our _first_ child is.
	{
		// Now we can throw away the original pCategory, since we're done with it.
		pCategory = pCategories->GetCategoryFirstChild(pCategory);
		
		// And set the first child.
		if (!pCategory)
		{
			// Sanity.
			pEntry->isLeaf = (int8_t) true;
			pEntry->firstChild = (int16_t)0;
		}
		else
			pEntry->firstChild = (int16_t) pCategory->GetId();

		// And delete the child category.
		delete pCategory;
	}

	// Now make the rest of the category safe for human consumption.
	// Setting the offsets to -1 tells the reader not to look, since
	// 0 is a valid offset.

	pEntry->currentOffset = -1;
	pEntry->numCurrent = 0;

	pEntry->endingOffset = -1;
	pEntry->numEnding = 0;

	pEntry->newOffset = -1;
	pEntry->numNew = 0;

	pEntry->goingOffset = -1;
	pEntry->numGoing = 0;

	pEntry->featuredOffset = -1;
	pEntry->numFeatured = 0;

	pEntry->hotOffset = -1;
	pEntry->numHot = 0;

	pEntry->galleryNormalOffset = -1;
	pEntry->numGalleryNormal = 0;

	pEntry->galleryFeaturedOffset = -1;
	pEntry->numGalleryFeatured = 0;

	pEntry->e_featuredOffset = -1;
	pEntry->e_numFeatured = 0;

	pEntry->e_hotOffset = -1;
	pEntry->e_numHot = 0;

	pEntry->n_featuredOffset = -1;
	pEntry->n_numFeatured = 0;

	pEntry->n_hotOffset = -1;
	pEntry->n_numHot = 0;

	pEntry->g_featuredOffset = -1;
	pEntry->g_numFeatured = 0;

	pEntry->g_hotOffset = -1;
	pEntry->g_numHot = 0;

	// And zero the things we use to make alignment correct.
	pEntry->forFutureUse = (int16_t)0;
	pEntry->forFutureUse2 = (int8_t)0;

	// And we're done -- we'll be back later for the lists!
	return;
}

// Just transfer from one format to another.
void clsFillHeader::FillItem(itemEntry *pEntry,
							 clsListingItem *pItem,
							 int itemIndex)
{
	mppItemTitle[itemIndex] = clsUtilities::StripHTML((char *) pItem->GetTitle());

	// need to double check here if mppText[0] is ok to use !!!!!!!!!!!!!!!!!!
	// right now we assume the 1st map file is always for all items
	pEntry->titleTextOffset = mppText[0]->AddString(mppItemTitle[itemIndex]);
//	delete [] pTitle;

	pEntry->itemNumber = pItem->GetId();
	pEntry->startTime = (int32_t) pItem->GetStartTime();
	pEntry->endTime = (int32_t) pItem->GetEndTime();

	// We convert from a double to an int32_t -- this then, is the
	// number of cents, we add .5 to fix a rounding problem in Oracle.
	pEntry->highBid = (int32_t) (pItem->GetPrice() * 100.0 + 0.5);

	pEntry->numBids = (int16_t) pItem->GetBidCount();
	pEntry->hasPicture = (int8_t) pItem->HasPic();
	pEntry->isBold = (int8_t) pItem->IsBoldTitle();
	pEntry->isReserved = (int8_t) pItem->IsReserved();
	pEntry->isFeatured = (int8_t) pItem->IsFeatured();
	pEntry->isSuperFeatured = (int8_t) pItem->IsSuperFeatured();
	//in order to support multiple  icons, isGift returns int instead of bool
	pEntry->whichGift = (int8_t) pItem->GiftIconType();
	
	pEntry->categoryNumber = (int16_t) pItem->GetCategoryId();
	pEntry->isAdult = (int8_t)(mppCategories[0][pEntry->categoryNumber].isAdult);

	pEntry->galleryType = (int8_t)NoneGallery;

	// Internationalization
	pEntry->countryID = (int8_t)pItem->CountryId();
	pEntry->currencyID = (int8_t)pItem->CurrencyId();

	if (pItem->GetGalleryState() == kGallerySuccess)
	{
		if (!pItem->isGallery() && !pItem->isFeaturedGallery())
		{
			if (pEntry->isFeatured)
				pEntry->galleryType = (int8_t)FeaturedGallery;
			else
				pEntry->galleryType = (int8_t)Gallery;
		}

		else if (pItem->isFeaturedGallery())
			pEntry->galleryType = (int8_t)FeaturedGallery;
		else if (pItem->isGallery())
			pEntry->galleryType = (int8_t)Gallery;
	}

	pEntry->mapFileType = (int8_t)mapFileTypeNotSpecified;

	if(pItem->IsShippingInternationally())
		pEntry->mapFileType = (int8_t)((int8_t)pEntry->mapFileType + (int8_t)mapShippingInternationally);

	if(pItem->IsLAItem())
		pEntry->mapFileType = (int8_t)((int8_t)pEntry->mapFileType + (int8_t)mapFileLA);

	// That's all of the members.
	return;
}


// overloaded function
void clsFillHeader::FillItem(itemEntry *pEntry,
							 int itemIndex, int mapFileIndex)
{
	// need to set mppText[mapFileIndex] and title offset. here

	pEntry->titleTextOffset = mppText[mapFileIndex]->AddString(mppItemTitle[itemIndex]);
	pEntry->itemNumber = mpItems[itemIndex].itemNumber;
	pEntry->startTime = mpItems[itemIndex].startTime;
	pEntry->endTime = mpItems[itemIndex].endTime;
	pEntry->highBid = mpItems[itemIndex].highBid;
	pEntry->numBids = (int16_t)mpItems[itemIndex].numBids;
	pEntry->hasPicture = (int8_t)mpItems[itemIndex].hasPicture;
	pEntry->isBold = (int8_t)mpItems[itemIndex].isBold;
	pEntry->isReserved = (int8_t)mpItems[itemIndex].isReserved;
	pEntry->isFeatured = (int8_t)mpItems[itemIndex].isFeatured;
	pEntry->isSuperFeatured = (int8_t)mpItems[itemIndex].isSuperFeatured;
	pEntry->isGift = (int8_t)mpItems[itemIndex].isGift;
	pEntry->whichGift = (int8_t)mpItems[itemIndex].whichGift;
	pEntry->categoryNumber = (int16_t)mpItems[itemIndex].categoryNumber;
	pEntry->isAdult = (int8_t)mpItems[itemIndex].isAdult;
	pEntry->galleryType = (int8_t)mpItems[itemIndex].galleryType;
	pEntry->countryID = (int8_t)mpItems[itemIndex].countryID;
	pEntry->currencyID = (int8_t)mpItems[itemIndex].currencyID;
	pEntry->mapFileType = (int8_t)mpItems[itemIndex].mapFileType;

	return;
}

// Fill one leaf category with items, given the begin and end pointers to the items
// in that category...
// It MUST be true that:
// A category with items has no children.
// (Thus also a category with children has no items)
void clsFillHeader::FillCategoryItemListLeaf(categoryEntry *pCategory,
											 vector<int32_t>::iterator beginList,
											 vector<int32_t>::iterator endList,
											 int mapFileIndex)
{
	vector<int32_t>::iterator endOfList;
	vector<int32_t>::iterator endOfList2;

	// Set the static time. Probably redundant after the first time.
	mStaticTime = mTime;
	mpStaticItems = mppItems[mapFileIndex];

	// Definitely not redundant -- set the current category for partitioning.

	mStaticCategory = pCategory->categoryNumber;

//	assert(pCategory->isLeaf);
//  Change this one so that it will not crash if there are some items leaks into non-leaf categories --- Stevey
	if (!pCategory->isLeaf)
	{
		cout << "\n In function clsFillHeader::FillCategoryItemListLeaf, assert(pCategory->isLeaf) fails for category " << pCategory->categoryNumber << '\n\n';
	}


	// Clear out anything we were doing before.
	mppWorkingList[mapFileIndex]->clear();

	mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(), beginList, endList);

	// Oh look -- it just so happens that the partitioned list is the
	// current items -- isn't that convenient?

	// Now we sort the current ones. We'll sort by insertion (starting) date.
	if (mppWorkingList[mapFileIndex]->size())
		sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByStartDateDescending);

	// Set the offset in the entry. This will be the size of the vector before
	// we add to it.
	pCategory->currentOffset = mppLists[mapFileIndex]->size();
	// And set the number in the list.
	pCategory->numCurrent = mppWorkingList[mapFileIndex]->size();
	// Now do the insert.
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());

	// Rinse and repeat for the various types.

	// Same sort for 'new' items, so we'll use a stable partition -- it will partition
	// without otherwise affecting sort order.

	// Reuse endOfList
	if (!mppWorkingList[mapFileIndex]->size())
		return;

	endOfList = stable_partition(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(),
		isNew);

	// Set the header and insert into the lists as before.
	pCategory->newOffset = mppLists[mapFileIndex]->size();
	// And set the number in the list.
	pCategory->numNew = endOfList - mppWorkingList[mapFileIndex]->begin();
	// Now do the insert.
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());

	// Now do the new and 'Featured' list.
	// Use 'endOfList2' so that 'endOfList' is still the new items, though now
	// out of order.
	if (mppWorkingList[mapFileIndex]->begin() != endOfList)
		endOfList2 = stable_partition(mppWorkingList[mapFileIndex]->begin(), endOfList,
			isFeatured);
	else
		endOfList2 = mppWorkingList[mapFileIndex]->begin();

	// Set the header and insert into the lists.
	pCategory->n_featuredOffset = mppLists[mapFileIndex]->size();
	pCategory->n_numFeatured = endOfList2 - mppWorkingList[mapFileIndex]->begin();
	// Do the insert
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), endOfList2);

	// Partition the 'new' items for hots.
	// We use 'endOfList' to not re-partition for new as well.
	if (mppWorkingList[mapFileIndex]->begin() != endOfList)
		endOfList2 = partition(mppWorkingList[mapFileIndex]->begin(), endOfList, isHot);
	else
		endOfList2 = mppWorkingList[mapFileIndex]->begin();

	// And resort the new hots.
	if (mppWorkingList[mapFileIndex]->begin() != endOfList2)
		sort(mppWorkingList[mapFileIndex]->begin(), endOfList2, sortByStartDateDescending);

	// And take care of new hot.
	pCategory->n_hotOffset = mppLists[mapFileIndex]->size();
	pCategory->n_numHot = endOfList2 - mppWorkingList[mapFileIndex]->begin();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), endOfList2);

	//
	// Now we re-partition the whole list to get all of the 'ending' items.
	//
	if (!mppWorkingList[mapFileIndex]->size())
		return;

	endOfList = partition(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), isEnding);

	// And we sort that by ascending ending date.
	if (mppWorkingList[mapFileIndex]->begin() != endOfList)
		sort(mppWorkingList[mapFileIndex]->begin(), endOfList, sortByEndDateAscending);

	// Now we do the 'ending' list.
	pCategory->endingOffset = mppLists[mapFileIndex]->size();
	pCategory->numEnding = endOfList - mppWorkingList[mapFileIndex]->begin();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), endOfList);

	// Now do the ending and 'Featured' list.
	// Use 'endOfList2' so that 'endOfList' is still the ending items, though
	// now out of order.
	if (endOfList != mppWorkingList[mapFileIndex]->begin())
		endOfList2 = stable_partition(mppWorkingList[mapFileIndex]->begin(), endOfList,
			isFeatured);
	else
		endOfList2 = endOfList;

	// Set the header and insert into the lists.
	pCategory->e_featuredOffset = mppLists[mapFileIndex]->size();
	pCategory->e_numFeatured = endOfList2 - mppWorkingList[mapFileIndex]->begin();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), endOfList2);

	// And partition again for hot. Use 'endOfList' to do it only
	// among the 'ending' items.
	if (endOfList != mppWorkingList[mapFileIndex]->begin())
		endOfList2 = partition(mppWorkingList[mapFileIndex]->begin(), endOfList, isHot);
	else
		endOfList2 = endOfList;

	// And sort the ending hot items.
	if (mppWorkingList[mapFileIndex]->begin() != endOfList2)
		sort(mppWorkingList[mapFileIndex]->begin(), endOfList2, sortByEndDateAscending);

	// And take care of ending hot.
	pCategory->e_hotOffset = mppLists[mapFileIndex]->size();
	pCategory->e_numHot = endOfList2 - mppWorkingList[mapFileIndex]->begin();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), endOfList2);


	// Now we repartition the ending list to get all of the 'going' items, since
	// they're guaranteed to be a subset of ending.
	if (endOfList != mppWorkingList[mapFileIndex]->begin())
		endOfList = partition(mppWorkingList[mapFileIndex]->begin(), endOfList, isGoing);

	// And we sort that be ascending ending date.
	if (mppWorkingList[mapFileIndex]->begin() != endOfList)
		sort(mppWorkingList[mapFileIndex]->begin(), endOfList, sortByEndDateAscending);

	// Now we do the 'going' list.
	pCategory->goingOffset = mppLists[mapFileIndex]->size();
	pCategory->numGoing = endOfList - mppWorkingList[mapFileIndex]->begin();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), endOfList);

	// Now do the going and 'Featured' list.
	// Use 'endOfList2' so that 'endOfList' is still the going items, though
	// now out of order.
	if (endOfList != mppWorkingList[mapFileIndex]->begin())
		endOfList2 = stable_partition(mppWorkingList[mapFileIndex]->begin(), endOfList,
			isFeatured);
	else
		endOfList2 = endOfList;

	pCategory->g_featuredOffset = mppLists[mapFileIndex]->size();
	pCategory->g_numFeatured = endOfList2 - mppWorkingList[mapFileIndex]->begin();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), endOfList2);

	// And partition again for hot.
	// We use 'endOfList' to only partition in 'going'.
	if (endOfList != mppWorkingList[mapFileIndex]->begin())
		endOfList2 = stable_partition(mppWorkingList[mapFileIndex]->begin(), endOfList,
			isHot);
	else
		endOfList2 = endOfList;

	// Resort the hot going items.
	if (mppWorkingList[mapFileIndex]->begin() != endOfList2)
		sort(mppWorkingList[mapFileIndex]->begin(), endOfList2, sortByEndDateAscending);


	// And take care of going hot.
	pCategory->g_hotOffset = mppLists[mapFileIndex]->size();
	pCategory->g_numHot = endOfList2 - mppWorkingList[mapFileIndex]->begin();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), endOfList2);

	//
	// Repartition the whole thing for normal gallery items
	//
	if (!mppWorkingList[mapFileIndex]->size())
		return;

	endOfList = partition(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(),
		isGallery);

	// Now sort them, by start date descending.
	if (mppWorkingList[mapFileIndex]->begin() != endOfList)
		sort(mppWorkingList[mapFileIndex]->begin(), endOfList, sortByStartDateDescending);

	// And take care of normal featured.
	pCategory->galleryNormalOffset = mppLists[mapFileIndex]->size();
	pCategory->numGalleryNormal = endOfList - mppWorkingList[mapFileIndex]->begin();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), endOfList);

	if (!mppWorkingList[mapFileIndex]->size())
		return;

	// Now do the gallery featured list.
	// Use endOfList2 so that 'endOfList' is still the gallery items, though now
	// out of order.
	if (mppWorkingList[mapFileIndex]->begin() != endOfList)
		endOfList2 = stable_partition(mppWorkingList[mapFileIndex]->begin(), endOfList,
			isGalleryFeatured); 
	else
		endOfList2 = mppWorkingList[mapFileIndex]->begin();

	// Set the header and insert into the lists.
	pCategory->galleryFeaturedOffset = mppLists[mapFileIndex]->size();
	pCategory->numGalleryFeatured = endOfList2 - mppWorkingList[mapFileIndex]->begin();
	// Do the insert
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), endOfList2);


	//
	// Now we repartition the whole thing for normal featured items.
	//
	if (!mppWorkingList[mapFileIndex]->size())
		return;

	endOfList = partition(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(),
		isFeatured);

	// Now sort them, by start date descending.
	if (mppWorkingList[mapFileIndex]->begin() != endOfList)
		sort(mppWorkingList[mapFileIndex]->begin(), endOfList, sortByStartDateDescending);

	// And take care of normal featured.
	pCategory->featuredOffset = mppLists[mapFileIndex]->size();
	pCategory->numFeatured = endOfList - mppWorkingList[mapFileIndex]->begin();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), endOfList);

	if (!mppWorkingList[mapFileIndex]->size())
		return;	

	// Now we repartition the whole thing for normal hot items.
	endOfList = partition(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(),
		isHot);

	// Now sort them, by start date descending.
	if (mppWorkingList[mapFileIndex]->begin() != endOfList)
		sort(mppWorkingList[mapFileIndex]->begin(), endOfList, sortByStartDateDescending);

	// And take care of normal hot.
	pCategory->hotOffset = mppLists[mapFileIndex]->size();
	pCategory->numHot = endOfList - mppWorkingList[mapFileIndex]->begin();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), endOfList);

	// And we're done. Whew.
	return;
}

void clsFillHeader::FillWithEachChild(categoryEntry *pCategory, int do_what, int mapFileIndex)
{
	categoryEntry *pChild;

	pChild = mppCategories[mapFileIndex] + pCategory->firstChild;

	if (!pCategory->categoryNumber)
	{
		vector<int32_t>::iterator i;
		mStaticCategory = 0;
		i = partition(mppReferenceList[mapFileIndex]->begin(), mppReferenceList[mapFileIndex]->end(), not1(ptr_fun(isAdult)));

		switch (do_what)
		{
		case e_currentItems:
		case e_currentItemsHot:
		case e_currentItemsFeatured:
			break;
		case e_newItems:
		case e_newItemsFeatured:
		case e_newItemsHot:
			i = partition(mppReferenceList[mapFileIndex]->begin(), i, isNew);
			break;
		case e_endingItems:
		case e_endingItemsFeatured:
		case e_endingItemsHot:
			i = partition(mppReferenceList[mapFileIndex]->begin(), i, isEnding);
			break;
		case e_goingItems:
		case e_goingItemsHot:
		case e_goingItemsFeatured:
			i = partition(mppReferenceList[mapFileIndex]->begin(), i, isGoing);
			break;
		case e_gallery:
		case e_galleryFeatured:
			i = partition(mppReferenceList[mapFileIndex]->begin(), i, isGallery);
			break;
		default:
			break;
		}

		switch (do_what)
		{
		case e_currentItems:
		case e_newItems:
		case e_endingItems:
		case e_goingItems:
			break;
		case e_currentItemsFeatured:
		case e_newItemsFeatured:
		case e_endingItemsFeatured:
		case e_goingItemsFeatured:
			i = partition(mppReferenceList[mapFileIndex]->begin(), i, isFeatured);
			break;
		case e_currentItemsHot:
		case e_newItemsHot:
		case e_endingItemsHot:
		case e_goingItemsHot:
			i = partition(mppReferenceList[mapFileIndex]->begin(), i, isHot);
			break;
		case e_galleryFeatured:
			i = partition(mppReferenceList[mapFileIndex]->begin(), i, isGalleryFeatured);
			break;
		default:
			break;
		}

		mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
			mppReferenceList[mapFileIndex]->begin(), i);

		return;
	}

	while (pChild)
	{
		switch (do_what)
		{
		case e_currentItems:
			if (!(pChild->isAdult && !pCategory->isAdult) && (pChild->currentOffset != -1 && pChild->numCurrent))
			{
				mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
					(mppLists[mapFileIndex]->begin() + pChild->currentOffset),
					(mppLists[mapFileIndex]->begin() + pChild->currentOffset + pChild->numCurrent));
			}
			break;
		case e_currentItemsFeatured:
			if (!(pChild->isAdult && !pCategory->isAdult) && (pChild->featuredOffset != -1) && pChild->numFeatured)
			{
				mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
					(mppLists[mapFileIndex]->begin() + pChild->featuredOffset),
					(mppLists[mapFileIndex]->begin() + pChild->featuredOffset + pChild->numFeatured));
			}
			break;
		case e_currentItemsHot:
			if (!(pChild->isAdult && !pCategory->isAdult) && (pChild->hotOffset != -1) && pChild->numHot)
			{
				mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
					(mppLists[mapFileIndex]->begin() + pChild->hotOffset),
					(mppLists[mapFileIndex]->begin() + pChild->hotOffset + pChild->numHot));
			}
			break;
		case e_newItems:
			if (!(pChild->isAdult && !pCategory->isAdult) && (pChild->newOffset != -1) && pChild->numNew)
			{
				mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
					(mppLists[mapFileIndex]->begin() + pChild->newOffset),
					(mppLists[mapFileIndex]->begin() + pChild->newOffset + pChild->numNew));
			}
			break;
		case e_newItemsFeatured:
			if (!(pChild->isAdult && !pCategory->isAdult) && (pChild->n_featuredOffset != -1) && pChild->n_numFeatured)
			{
				mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
					(mppLists[mapFileIndex]->begin() + pChild->n_featuredOffset),
					(mppLists[mapFileIndex]->begin() + pChild->n_featuredOffset + pChild->n_numFeatured));
			}
			break;
		case e_newItemsHot:
			if (!(pChild->isAdult && !pCategory->isAdult) && (pChild->n_hotOffset != -1) && pChild->n_numHot)
			{
				mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
					(mppLists[mapFileIndex]->begin() + pChild->n_hotOffset),
					(mppLists[mapFileIndex]->begin() + pChild->n_hotOffset + pChild->n_numHot));
			}
			break;
		case e_endingItems:
			if (!(pChild->isAdult && !pCategory->isAdult) && (pChild->endingOffset != -1) && pChild->numEnding)
			{
				mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
					(mppLists[mapFileIndex]->begin() + pChild->endingOffset),
					(mppLists[mapFileIndex]->begin() + pChild->endingOffset + pChild->numEnding));
			}
			break;
		case e_endingItemsFeatured:
			if (!(pChild->isAdult && !pCategory->isAdult) && (pChild->e_featuredOffset != -1) && pChild->e_numFeatured)
			{
				mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
					(mppLists[mapFileIndex]->begin() + pChild->e_featuredOffset),
					(mppLists[mapFileIndex]->begin() + pChild->e_featuredOffset + pChild->e_numFeatured));
			}
			break;
		case e_endingItemsHot:
			if (!(pChild->isAdult && !pCategory->isAdult) && (pChild->e_hotOffset != -1) && pChild->e_numHot)
			{
				mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
					(mppLists[mapFileIndex]->begin() + pChild->e_hotOffset),
					(mppLists[mapFileIndex]->begin() + pChild->e_hotOffset + pChild->e_numHot));
			}
			break;
		case e_goingItems:
			if (!(pChild->isAdult && !pCategory->isAdult) && (pChild->goingOffset != -1) && pChild->numGoing)
			{
				mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
					(mppLists[mapFileIndex]->begin() + pChild->goingOffset),
					(mppLists[mapFileIndex]->begin() + pChild->goingOffset + pChild->numGoing));
			}
			break;
		case e_goingItemsFeatured:
			if (!(pChild->isAdult && !pCategory->isAdult) && (pChild->g_featuredOffset != -1) && pChild->g_numFeatured)
			{
				mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
					(mppLists[mapFileIndex]->begin() + pChild->g_featuredOffset),
					(mppLists[mapFileIndex]->begin() + pChild->g_featuredOffset + pChild->g_numFeatured));
			}
			break;
		case e_goingItemsHot:
			if (!(pChild->isAdult && !pCategory->isAdult) && (pChild->g_hotOffset != -1) && pChild->g_numHot)
			{
				mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
					(mppLists[mapFileIndex]->begin() + pChild->g_hotOffset),
					(mppLists[mapFileIndex]->begin() + pChild->g_hotOffset + pChild->g_numHot));
			}
			break;

		case e_gallery:
			if (!(pChild->isAdult && !pCategory->isAdult) && (pChild->galleryNormalOffset != -1) && pChild->numGalleryNormal)
			{
				mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
					(mppLists[mapFileIndex]->begin() + pChild->galleryNormalOffset),
					(mppLists[mapFileIndex]->begin() + pChild->galleryNormalOffset + pChild->numGalleryNormal));
			}
			break;
		case e_galleryFeatured:
			if (!(pChild->isAdult && !pCategory->isAdult) && (pChild->galleryFeaturedOffset != -1) && pChild->numGalleryFeatured)
			{
				mppWorkingList[mapFileIndex]->insert(mppWorkingList[mapFileIndex]->end(),
					(mppLists[mapFileIndex]->begin() + pChild->galleryFeaturedOffset),
					(mppLists[mapFileIndex]->begin() + pChild->galleryFeaturedOffset + pChild->numGalleryFeatured));
			}
			break;
		default:
			break;
		}

		if (pChild->rightSibling)
			pChild = mppCategories[mapFileIndex] + pChild->rightSibling;
		else
			pChild = NULL;
	}

	return;
}

// And to finish off the category after the rest has been done.
// This is done recursively.
void clsFillHeader::FillCategoryItemLists(categoryEntry *pCategory, int mapFileIndex)
{
	categoryEntry *pChild;
//	vector<int32_t>::iterator endOfList;
//	vector<int32_t>::iterator endOfList2;

//	assert(!pCategory->isLeaf);
//  Change this one so that it will not crash if there are some items leaks into non-leaf categories --- Stevey
	if (pCategory->isLeaf)
	{
		cout << "\n In function clsFillHeader::FillCategoryItemLists, assert(!pCategory->isLeaf) fails for category " << pCategory->categoryNumber << '\n\n';
	}

	pChild = mppCategories[mapFileIndex] + pCategory->firstChild;

	while (pChild)
	{
		if (!pChild->isLeaf)
			FillCategoryItemLists(pChild, mapFileIndex);

		if (pChild->rightSibling)
			pChild = mppCategories[mapFileIndex] + pChild->rightSibling;
		else
			pChild = NULL;
	}

	// Clear out anything we were doing before.
	mppWorkingList[mapFileIndex]->clear();

	// Now that we have that, we continue, and add in our child categories.
	FillWithEachChild(pCategory, e_currentItems, mapFileIndex);

	// However, we don't do a 'current' list for levels 0 or 1.
	if (pCategory->categoryLevel > 1)
	{
		// Now we sort the current ones. We'll sort by insertion (starting) date.
		if (mppWorkingList[mapFileIndex]->size())
			sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByStartDateDescending);

		// Set the offset in the entry. This will be the size of the vector before
		// we add to it.
		pCategory->currentOffset = mppLists[mapFileIndex]->size();
		// And set the number in the list.
		pCategory->numCurrent = mppWorkingList[mapFileIndex]->size();
		// Now do the insert.
		mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());
	}
	else // We do, however, set the count.
	{
		pCategory->numCurrent = mppWorkingList[mapFileIndex]->size();
		pCategory->currentOffset = -1;
	}

	// Rinse and repeat for the various types.
	mppWorkingList[mapFileIndex]->clear();

	FillWithEachChild(pCategory, e_newItems, mapFileIndex);

	// We don't do a 'new' list for level 0 either.
	if (pCategory->categoryLevel > 0)
	{
		if (mppWorkingList[mapFileIndex]->size())
			sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByStartDateDescending);

		// Set the header and insert into the lists as before.
		pCategory->newOffset = mppLists[mapFileIndex]->size();
		// And set the number in the list.
		pCategory->numNew = mppWorkingList[mapFileIndex]->size();
		// Now do the insert.
		mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());
	}
	else // We do, however, set the count.
	{
		pCategory->numNew = mppWorkingList[mapFileIndex]->size();
		pCategory->newOffset = -1;
	}

	// New + Hot
	mppWorkingList[mapFileIndex]->clear();
	FillWithEachChild(pCategory, e_newItemsHot, mapFileIndex);

	// Set the header and insert into the lists.
	if (mppWorkingList[mapFileIndex]->size())
		sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByStartDateDescending);

	pCategory->n_hotOffset = mppLists[mapFileIndex]->size();
	pCategory->n_numHot = mppWorkingList[mapFileIndex]->size();
	// Do the insert
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());

	// New + Featured
	mppWorkingList[mapFileIndex]->clear();
	FillWithEachChild(pCategory, e_newItemsFeatured, mapFileIndex);

	// Set the header and insert into the lists.
	if (mppWorkingList[mapFileIndex]->size())
		sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByStartDateDescending);

	pCategory->n_featuredOffset = mppLists[mapFileIndex]->size();
	pCategory->n_numFeatured = mppWorkingList[mapFileIndex]->size();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());

	// Ending
	mppWorkingList[mapFileIndex]->clear();
	FillWithEachChild(pCategory, e_endingItems, mapFileIndex);

	// And we sort that by ascending ending date.
	if (!mppWorkingList[mapFileIndex]->empty())
		sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByEndDateAscending);

	// Now we do the 'ending' list.
	// We don't do an 'ending' list for level 0 either.

	if (pCategory->categoryLevel > 0)
	{
		pCategory->endingOffset = mppLists[mapFileIndex]->size();
		pCategory->numEnding = mppWorkingList[mapFileIndex]->size();
		mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());
	}
	else // Set the count, though.
	{
		pCategory->endingOffset = -1;
		pCategory->numEnding = mppWorkingList[mapFileIndex]->size();
	}

	// Ending + Hot
	mppWorkingList[mapFileIndex]->clear();
	FillWithEachChild(pCategory, e_endingItemsHot, mapFileIndex);

	if (!mppWorkingList[mapFileIndex]->empty())
		sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByEndDateAscending);

	pCategory->e_hotOffset = mppLists[mapFileIndex]->size();
	pCategory->e_numHot = mppWorkingList[mapFileIndex]->size();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());

	// Ending + featured
	mppWorkingList[mapFileIndex]->clear();
	FillWithEachChild(pCategory, e_endingItemsFeatured, mapFileIndex);

	if (!mppWorkingList[mapFileIndex]->empty())
		sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByEndDateAscending);

	pCategory->e_featuredOffset = mppLists[mapFileIndex]->size();
	pCategory->e_numFeatured = mppWorkingList[mapFileIndex]->size();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());

	// Going
	mppWorkingList[mapFileIndex]->clear();
	FillWithEachChild(pCategory, e_goingItems, mapFileIndex);

	if (!mppWorkingList[mapFileIndex]->empty())
		sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByEndDateAscending);

	pCategory->goingOffset = mppLists[mapFileIndex]->size();
	pCategory->numGoing = mppWorkingList[mapFileIndex]->size();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());

	// Going + Hot
	mppWorkingList[mapFileIndex]->clear();
	FillWithEachChild(pCategory, e_goingItemsHot, mapFileIndex);

	if (!mppWorkingList[mapFileIndex]->empty())
		sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByEndDateAscending);

	pCategory->g_hotOffset = mppLists[mapFileIndex]->size();
	pCategory->g_numHot = mppWorkingList[mapFileIndex]->size();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());

	// Going + Featured
	mppWorkingList[mapFileIndex]->clear();
	FillWithEachChild(pCategory, e_goingItemsFeatured, mapFileIndex);

	if (!mppWorkingList[mapFileIndex]->empty())
	{
		sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByEndDateAscending);
	}

	pCategory->g_featuredOffset = mppLists[mapFileIndex]->size();
	pCategory->g_numFeatured = mppWorkingList[mapFileIndex]->size();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());

	// Current + Hot
	mppWorkingList[mapFileIndex]->clear();
	FillWithEachChild(pCategory, e_currentItemsHot, mapFileIndex);

	if (!mppWorkingList[mapFileIndex]->empty())
		sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByStartDateDescending);

	pCategory->hotOffset = mppLists[mapFileIndex]->size();
	pCategory->numHot = mppWorkingList[mapFileIndex]->size();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());

	// Current + Featured
	mppWorkingList[mapFileIndex]->clear();
	FillWithEachChild(pCategory, e_currentItemsFeatured, mapFileIndex);

	if (!mppWorkingList[mapFileIndex]->empty())
		sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByStartDateDescending);

	pCategory->featuredOffset = mppLists[mapFileIndex]->size();
	pCategory->numFeatured = mppWorkingList[mapFileIndex]->size();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());

	mppWorkingList[mapFileIndex]->clear();
	FillWithEachChild(pCategory, e_gallery, mapFileIndex);

	if (!mppWorkingList[mapFileIndex]->empty())
		sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByStartDateDescending);

	pCategory->galleryNormalOffset = mppLists[mapFileIndex]->size();
	pCategory->numGalleryNormal = mppWorkingList[mapFileIndex]->size();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());


	mppWorkingList[mapFileIndex]->clear();
	FillWithEachChild(pCategory, e_galleryFeatured, mapFileIndex);

	if (!mppWorkingList[mapFileIndex]->empty())
		sort(mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end(), sortByStartDateDescending);

	pCategory->galleryFeaturedOffset = mppLists[mapFileIndex]->size();
	pCategory->numGalleryFeatured = mppWorkingList[mapFileIndex]->size();
	mppLists[mapFileIndex]->insert(mppLists[mapFileIndex]->end(), mppWorkingList[mapFileIndex]->begin(), mppWorkingList[mapFileIndex]->end());


	// And we're done. Whew.

	return;
}

// Adult
bool clsFillHeader::isAdult(int32_t item)
{
	return mpStaticItems[item].isAdult != 0;
}

// In the current category.
bool clsFillHeader::isInCategory(int32_t item)
{
	return mpStaticItems[item].categoryNumber == mStaticCategory;
}

// Number of bids over 30 and not reserve item.
bool clsFillHeader::isHot(int32_t item)
{
	return (mpStaticItems[item].numBids > 30 &&
			!mpStaticItems[item].isReserved);
}

// Ending within 24 hours.
bool clsFillHeader::isEnding(int32_t item)
{
	return (mpStaticItems[item].endTime - mStaticTime) <= 86400;
}

// Started within the last 24 hours.
bool clsFillHeader::isNew(int32_t item)
{
	return (mStaticTime - mpStaticItems[item].startTime) <= 86400;
}

// Ending within 5 hours.
bool clsFillHeader::isGoing(int32_t item)
{
	return (mpStaticItems[item].endTime - mStaticTime) <= 18000;
}

// Hot -- it 'knows' when we have the top level category
// and returns super Featured only in that case.
bool clsFillHeader::isFeatured(int32_t item)
{
	// Use !! to avoid performance warning.
	if (mStaticCategory == 0)
		return !!mpStaticItems[item].isSuperFeatured;

	// Use !! to avoid performance warning.
	return !!mpStaticItems[item].isFeatured;
}

// Gallery
bool clsFillHeader::isGallery(int32_t item)
{
	return (mpStaticItems[item].galleryType == Gallery ||
		    mpStaticItems[item].galleryType == FeaturedGallery);
}

// Gallery featured
bool clsFillHeader::isGalleryFeatured(int32_t item)
{
	return mpStaticItems[item].galleryType == FeaturedGallery;
}

// Comparison routines for sorting.
bool clsFillHeader::sortByStartDateDescending(int32_t item, int32_t item2)
{
	return mpStaticItems[item].startTime > mpStaticItems[item2].startTime;
}

bool clsFillHeader::sortByEndDateAscending(int32_t item, int32_t item2)
{
	return mpStaticItems[item].endTime < mpStaticItems[item2].endTime;
}

bool clsFillHeader::sortByCategory(int32_t item, int32_t item2)
{
	return mpStaticItems[item].categoryNumber < mpStaticItems[item2].categoryNumber;
}

// A macro to reverse the byte order. Define this to be semantically null
// if the byte order of the producing machine and the byte
// order of the target machine are the same.


#ifdef _MSC_VER
#define FIX_BYTE_ORDER32(x)
#define FIX_BYTE_ORDER16(x)
#else
// long
#define FIX_BYTE_ORDER32(x)	(x) = ((((x) >> 24) & 0xFF) | \
				       (((x) >> 16) & 0xFF) << 8 | \
				       (((x) >> 8) & 0xFF) << 16 | \
					((x) & 0xFF) << 24)

// short
#define FIX_BYTE_ORDER16(x)	(x) = ((((x) >> 8) & 0xFF) | \
					((x) & 0xFF) << 8)
#endif

// Here we write the binary file necessary for the 'listings'
// project.
// First we fix up the structures before writing them out,
// by calling the FIX_BYTE_ORDER macro.

void clsFillHeader::WriteBinaryToStream(ofstream * pStream, int p)
{
	// only write to the map file if there are some items.
	if (mpNumItems[p] > 0)
	{
		int i, j;
		categoryEntry *pCategory;
		itemEntry *pItem;
		vector<int32_t>::iterator k;
		int32_t listVal;
		int32_t numCategories;
	#ifdef RECORD_TIME
		clock_t lastClock;
		time_t lastTime;
		clock_t clockDiff;
		time_t timeDiff;

		lastClock = clock();
		lastTime = time(NULL);

		cout << "Beginning write for map file [" << p << "] at " << lastTime 
			<< " with clock " << lastClock << ".\n";
		cout << "Clock cycles per second: " << CLOCKS_PER_SEC << ".\n";
		cout.flush();
	#endif

		numCategories = mpHeader[p].numCategories;
		// Fix the header.
		// Fill in the magic number (our size)
		mpHeader[p].magicNumber = mpHeader[p].listsOffset + sizeof (int32_t) * mppLists[p]->size();

		FIX_BYTE_ORDER32(mpHeader[p].magicNumber);
		FIX_BYTE_ORDER32(mpHeader[p].categoryOffset);
		FIX_BYTE_ORDER32(mpHeader[p].numCategories);
		FIX_BYTE_ORDER32(mpHeader[p].itemOffset);
		FIX_BYTE_ORDER32(mpHeader[p].textOffset);
		FIX_BYTE_ORDER32(mpHeader[p].listsOffset);
		FIX_BYTE_ORDER32(mpHeader[p].timeGenerated);

		// Write out the header.
			pStream->write((const char *) &(mpHeader[p]), sizeof(mpHeader[p]));

		// Loop and fix the categories.
		for (i = 0, j = numCategories + 1; i < j; ++i)
		{
			pCategory = mppCategories[p] + i;

			FIX_BYTE_ORDER32(pCategory->titleTextOffset);
			FIX_BYTE_ORDER32(pCategory->currentOffset);
			FIX_BYTE_ORDER32(pCategory->numCurrent);
			FIX_BYTE_ORDER32(pCategory->endingOffset);
			FIX_BYTE_ORDER32(pCategory->numEnding);
			FIX_BYTE_ORDER32(pCategory->newOffset);
			FIX_BYTE_ORDER32(pCategory->numNew);
			FIX_BYTE_ORDER32(pCategory->goingOffset);
			FIX_BYTE_ORDER32(pCategory->numGoing);
			FIX_BYTE_ORDER32(pCategory->featuredOffset);
			FIX_BYTE_ORDER32(pCategory->numFeatured);
			FIX_BYTE_ORDER32(pCategory->hotOffset);
			FIX_BYTE_ORDER32(pCategory->numHot);
			FIX_BYTE_ORDER32(pCategory->galleryNormalOffset);
			FIX_BYTE_ORDER32(pCategory->numGalleryNormal);
			FIX_BYTE_ORDER32(pCategory->galleryFeaturedOffset);
			FIX_BYTE_ORDER32(pCategory->numGalleryFeatured);
			FIX_BYTE_ORDER32(pCategory->e_featuredOffset);
			FIX_BYTE_ORDER32(pCategory->e_numFeatured);
			FIX_BYTE_ORDER32(pCategory->e_hotOffset);
			FIX_BYTE_ORDER32(pCategory->e_numHot);
			FIX_BYTE_ORDER32(pCategory->n_featuredOffset);
			FIX_BYTE_ORDER32(pCategory->n_numFeatured);
			FIX_BYTE_ORDER32(pCategory->n_hotOffset);
			FIX_BYTE_ORDER32(pCategory->n_numHot);
			FIX_BYTE_ORDER32(pCategory->g_featuredOffset);
			FIX_BYTE_ORDER32(pCategory->g_numFeatured);
			FIX_BYTE_ORDER32(pCategory->g_hotOffset);
			FIX_BYTE_ORDER32(pCategory->g_numHot);


			FIX_BYTE_ORDER16(pCategory->categoryNumber);
			FIX_BYTE_ORDER16(pCategory->parentCategory);
			FIX_BYTE_ORDER16(pCategory->leftSibling);
			FIX_BYTE_ORDER16(pCategory->rightSibling);
			FIX_BYTE_ORDER16(pCategory->firstChild);
			FIX_BYTE_ORDER16(pCategory->forFutureUse);

			// It's not necessary to fix the 1 byte fields.
		}

	#ifdef RECORD_TIME
		clockDiff = clock() - lastClock;
		timeDiff = time(NULL) - lastTime;
		lastClock = clock();
		lastTime = time(NULL);

		cout << "Elapsed time for writing header for map file [" << p << "]: "
			 << timeDiff << " real seconds, " << clockDiff << " clock cycles.\n";
		cout.flush();
	#endif

		// And write out the categories.
		// numCategories + 1 is how many we have. (0 category counts.)
		pStream->write((const char *) mppCategories[p], 
			sizeof (categoryEntry) * (numCategories + 1));

	#ifdef RECORD_TIME
		clockDiff = clock() - lastClock;
		timeDiff = time(NULL) - lastTime;
		lastClock = clock();
		lastTime = time(NULL);

		cout << "Elapsed time for writing categories for map file [" << p << "]: "
			 << timeDiff << " real seconds, " << clockDiff << " clock cycles.\n";
		cout.flush();
	#endif

		// Take care of the text block -- byte order doesn't need
		// to change on this one.
		pStream->write(mppText[p]->GetBuffer(), mppText[p]->GetSafeWriteSize());

	#ifdef RECORD_TIME
		clockDiff = clock() - lastClock;
		timeDiff = time(NULL) - lastTime;
		lastClock = clock();
		lastTime = time(NULL);

		cout << "Elapsed time for writing text block for map file [" << p << "]: "
			 << timeDiff << " real seconds, " << clockDiff << " clock cycles.\n";
		cout.flush();
	#endif

		// Loop and fix the items.
		for (i = 0; i < mpNumItems[p]; ++i)
		{
			pItem = mppItems[p] + i;

			FIX_BYTE_ORDER32(pItem->titleTextOffset);
			FIX_BYTE_ORDER32(pItem->itemNumber);
			FIX_BYTE_ORDER32(pItem->startTime);
			FIX_BYTE_ORDER32(pItem->endTime);
			FIX_BYTE_ORDER32(pItem->highBid);

			FIX_BYTE_ORDER16(pItem->numBids);
			FIX_BYTE_ORDER16(pItem->categoryNumber);

			// It's not necessary to fix the 1 byte fields.
		}

		// And write out the items.
		pStream->write((const char *) mppItems[p],
			sizeof (itemEntry) * mpNumItems[p]);

	#ifdef RECORD_TIME
		clockDiff = clock() - lastClock;
		timeDiff = time(NULL) - lastTime;
		lastClock = clock();
		lastTime = time(NULL);

		cout << "Elapsed time for writing " << mpNumItems[p] << " items for map file [" << p << "]: "
			 << timeDiff << " real seconds, " << clockDiff << " clock cycles.\n";
		cout.flush();
	#endif

		// Loop and fix the list items and write them at the
		// same time (since they're not guaranteed to be writable
		// as a block, and probably aren't).

		for (k = mppLists[p]->begin(); k != mppLists[p]->end(); ++k)
		{
			listVal = *k;

			FIX_BYTE_ORDER32(listVal);
			pStream->write((const char *) &listVal,
				sizeof (int32_t));
		}

	#ifdef RECORD_TIME
		clockDiff = clock() - lastClock;
		timeDiff = time(NULL) - lastTime;
		lastClock = clock();
		lastTime = time(NULL);

		cout << "Elapsed time for writing lists for map file [" << p << "]: "
			 << timeDiff << " real seconds, " << clockDiff << " clock cycles.\n";
		cout.flush();
	#endif

		// If we had problems, exit _now_
		if (!pStream->good() && !pStream->eof())
			exit(1);

		// And we're done.
	}
	else
		cout << "No items for map file [" << p << "]" << endl;
    return;
}
