#include "eBayKernel.h"
#include "clsItemsCache.h"

double clsItemsCache::mHighTicketLimit = 5000.00;
long clsItemsCache::mRandomPickLimit = 1000;
int clsItemsCache::mHotLimit = 30;

static clsCategories *spCategories = NULL;

// Right now, this picks the top level categories (plus 0)--
// add additional categories however desired, but only
// categories which are being used should really be in here.
// (Growth of time required is linear in the number of categories)
void clsItemsCache::PickCategories()
{
    if (!mplCategoriesToDo)
		mplCategoriesToDo = new list<int>;

	gApp->GetDatabase()->GetCachedCategoryIds(mplCategoriesToDo);

}

clsItemsCache::clsItemsCache()
{
    mplCategoriesToDo = NULL;
}

clsItemsCache::~clsItemsCache()
{
    delete mplCategoriesToDo;
}

static bool
itemIsNotAdult(clsListingItem *pItem)
{
    return !spCategories->IsAdultCategory(pItem->GetCategoryId());
}

static bool
sortItemsByCategory(clsListingItem *pItem1, clsListingItem *pItem2)
{
    return pItem1->GetCategoryId() < pItem2->GetCategoryId();
}

static bool
itemIsHighTicket(clsListingItem *pItem)
{
    return pItem->GetPrice() >= clsItemsCache::mHighTicketLimit;
}

static bool
itemIsHot(clsListingItem *pItem)
{
    return pItem->GetBidCount() > clsItemsCache::mHotLimit && !pItem->IsReserved();
}

static bool
itemIsFeatured(clsListingItem *pItem)
{
    return (pItem->IsFeatured() || pItem->IsSuperFeatured());
}

static bool
itemIsSuperFeatured(clsListingItem *pItem)
{
    return pItem->IsSuperFeatured();
}

void clsItemsCache::FillAllCaches(ListingItemVector *pvItems, int nCountry)
{
    ListingItemVector::iterator j;
    list<int>::iterator k;

    spCategories = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCategories();

 //   i = partition(pvItems->begin(), pvItems->end(), itemIsNotAdult);
    sort(pvItems->begin(), pvItems->end(), sortItemsByCategory);

	// figure out from the existing cache, which categories need to be filled
    PickCategories();

	// for each category that needs to be done, do it
    for (k = mplCategoriesToDo->begin(); k != mplCategoriesToDo->end(); ++k)
    {
		ListingItemVector vInCategory;
		vector<unsigned long> vMatches;
		int lastCategory;
		bool insertOn = false;
		int count = 0;

		j = pvItems->begin();

		lastCategory = (*j)->GetCategoryId() + 1;
		while (j != pvItems->end())
		{
			if ((*j)->GetCategoryId() != lastCategory)
			{
				lastCategory = (*j)->GetCategoryId();
				insertOn = !(*k) || spCategories->IsDescendant(*k, lastCategory);
			}
			if (insertOn)
				vInCategory.push_back(*j);
			++j;
		}

		// Randomize all the items for this category
		random_shuffle(vInCategory.begin(), vInCategory.end());

		// First, let's store away the items for the mystery widget
		for (j = vInCategory.begin(), count = 0; (j != vInCategory.end()) && (count < mRandomPickLimit); ++j)
		{
			count++;
			vMatches.push_back((*j)->GetId());
		}
		gApp->GetDatabase()->StoreItemList(1, itemCacheRandom, *k, ((count == mRandomPickLimit) ? "R" : "F") , nCountry, &vMatches);
		vMatches.clear();

		// Now, copy over all high ticket items...
		for (j = vInCategory.begin(), count = 0; (j != vInCategory.end()) && (count < mRandomPickLimit); ++j)
		{
			if (itemIsHighTicket(*j))
			{
				count++;
				vMatches.push_back((*j)->GetId());
			}
		}
		gApp->GetDatabase()->StoreItemList(1, itemCacheHighTicket, *k, ((count == mRandomPickLimit) ? "R" : "F"), nCountry, &vMatches);
		vMatches.clear();

		// Now, the hot items.
		for (j = vInCategory.begin(), count = 0; (j != vInCategory.end()) && (count < mRandomPickLimit); ++j)
		{
			if (itemIsHot(*j))
			{
				count++;
				vMatches.push_back((*j)->GetId());
			}
		}
		gApp->GetDatabase()->StoreItemList(1, itemCacheHot,	*k, ((count == mRandomPickLimit) ? "R" : "F"), nCountry, &vMatches);
		vMatches.clear();

		// Now, featured.
		for (j = vInCategory.begin(), count = 0; (j != vInCategory.end()) && (count < mRandomPickLimit); ++j)
		{
			if (itemIsFeatured(*j))
			{
				count++;
				vMatches.push_back((*j)->GetId());
			}
		}
		gApp->GetDatabase()->StoreItemList(1, itemCacheFeatured, *k, ((count == mRandomPickLimit) ? "R" : "F"), nCountry, &vMatches);
		vMatches.clear();

		// And lastly, super featured.
		for (j = vInCategory.begin(), count = 0; (j != vInCategory.end()) && (count < mRandomPickLimit); ++j)
		{
			if (itemIsSuperFeatured(*j))
			{
				count++;
				vMatches.push_back((*j)->GetId());
			}
		}
		gApp->GetDatabase()->StoreItemList(1, itemCacheSuperFeatured, *k, ((count == mRandomPickLimit) ? "R" : "F"), nCountry, &vMatches);
		vMatches.clear();

		// Pointless, but let's do it just for our peace of mind:
		vInCategory.clear();
	}
}


bool clsItemsCache::GetItemIdsVectorFromCache(vector<unsigned long> *pStore, 
											 time_t endDate, 
											 int queryCode, 
											 int catId,
											 int itemState, // 1 = active, 0 = completed
											 int country /* = 0 */)
{
	int cacheType;
    int z;
	
	// check for the current kinds of items we cache
	// maybe we should simply use the item types from eBayTypes huh???
	switch (queryCode) {
		case eGetSuperFeatured: 
			cacheType = itemCacheSuperFeatured;
			break;
		case eGetAllFeatured: 
			cacheType = itemCacheFeatured;
			break;
		case eGetActiveRandom: 
			cacheType = itemCacheRandom;
			break;
		case eGetHighTicket: 
			cacheType = itemCacheHighTicket;
			break;
		case eGetHot: 
			cacheType = itemCacheHot;
			break;
	}
	
    // check the current size of the vector
	z = pStore->size();
    // check the database for the cache
	gApp->GetDatabase()->RetrieveItemList(itemState, cacheType, 
		(CategoryId) catId,
		country, (vector<unsigned long> *) pStore);

    // did the vector size change?
	if (pStore->size() == z)
		return false;
  
	return true;
}

void clsItemsCache::StoreItemIdsVectorInCache(vector<unsigned long> *pStore, 
											  char *scope,
											 int queryCode, 
											 int catId,
											 int itemState, // 1 = active, 0 = completed
											 int country /* = 0 */)
{
	int cacheType;
	
	// check for the current kinds of items we cache
	// maybe we should simply use the item types from eBayTypes huh???
	switch (queryCode) {
		case eGetSuperFeatured: 
			cacheType = itemCacheSuperFeatured;
			break;
		case eGetAllFeatured: 
			cacheType = itemCacheFeatured;
			break;
		case eGetActiveRandom: 
			cacheType = itemCacheRandom;
			break;
		case eGetHighTicket: 
			cacheType = itemCacheHighTicket;
			break;
		case eGetHot: 
			cacheType = itemCacheHot;
			break;
	}
	
    // store it
    gApp->GetDatabase()->StoreItemList(itemState, cacheType, 
		(CategoryId) catId, scope,
		country, pStore);
}

//
// IsCachedItemQuery
//
// checks list of item types we cache
//
bool clsItemsCache::IsCachedItemQuery(int queryCode)
{ 
	int q = 0;

	while(q < sizeof(CachedItems) / sizeof(int)) {
		if(queryCode == CachedItems[q])
			return true;
		q++;
	}
	return false;
}

 