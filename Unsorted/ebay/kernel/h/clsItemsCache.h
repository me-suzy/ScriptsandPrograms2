#ifndef clsItemsCache_h
#define clsItemsCache_h

#include "clsItems.h"
#include "clsItem.h"
#include "clsListingItem.h"
#include "clsCategories.h"


// list of items we currently cache. items taken from eBayTypes.h
static int CachedItems[] = 
{
	eGetSuperFeatured, eGetActiveRandom, eGetAllFeatured, eGetHighTicket, eGetHot
};

class clsItemsCache
{
public:
    clsItemsCache();
    ~clsItemsCache();

	// this enum matches the Kind column in ebay_item_list_cache
    enum
    {
		itemCacheRandom = 0,
		itemCacheHighTicket,
		itemCacheHot,
		itemCacheFeatured,
		itemCacheSuperFeatured
    };
    static double mHighTicketLimit;
    static long mRandomPickLimit;	// max # of item ids to store in a single row in the cache table
    static int mHotLimit;

	// get item ids from a cache row
	bool GetItemIdsVectorFromCache(vector<unsigned long> *pStore, 
		time_t endDate, 
		int queryCode, 
		int catId,
		int itemState, // 1 = active, 0 = completed
		int country = 0);

	// store item ids into a cache row
	void StoreItemIdsVectorInCache(vector<unsigned long> *pStore, 
		char *scope,
		int queryCode, 
		int catId,
		int itemState, // 1 = active, 0 = completed
		int country = 0);

	// given a huge vector of ListingItems (e.g, from ListingsProduce), refresh the caches
	// nCountry tells FillAllCaches what country of items is in the given vector, so that the
	// right value can be put in the country column of ebay_item_list_cache
	void FillAllCaches(ListingItemVector *pvItems, int nCountry);

	// figures out from the existing cache in the DB which categories are represented in the cache
    void PickCategories();

 	bool IsCachedItemQuery(int queryCode); 
	

private:
    list<int> *mplCategoriesToDo;
};

#endif /* clsItemsCache_h */