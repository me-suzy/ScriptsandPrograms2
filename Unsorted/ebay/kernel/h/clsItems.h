/*	$Id: clsItems.h,v 1.12.2.2 1999/07/17 02:16:23 inna Exp $	*/
//
//	File:		clsItems.h
//
// Class:	clsItems
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Represents a collection of items
//
// Modifications:
//				- 02/10/97 michael	- Created
//				- 09/02/97 wen		- added functions for retrieving
//									  listing items
//				- 10/13/97 poon		- added GetSuperFeaturedItemIds and
//									        GetHotItemIds
//				- 11/12/97 poon		- moved sorting stuff to here
//				- 05/24/99 jennifer - added admin gallery tool functions
//
#ifndef CLSITEMS_INCLUDED

#include "eBayTypes.h"
#include "clsItem.h"
#include "clsListingItem.h"
#include <vector.h>

// transaction related flags
const char TRANSACT_USED_BY_SELLER = 1;
const char TRANSACT_USED_BY_BIDDER = 2;

//
// A bunch of sort comparison routines used for STL sort
//
bool sort_items_id(clsItem *pA, clsItem *pB);
bool sort_items_reverse_id(clsItem *pA, clsItem *pB);

bool sort_items_start_time(clsItem *pA, clsItem *pB);
bool sort_items_reverse_start_time(clsItem *pA, clsItem *pB);

bool sort_items_end_time(clsItem *pA, clsItem *pB);
bool sort_items_reverse_end_time(clsItem *pA, clsItem *pB);

bool sort_items_price(clsItem *pA, clsItem *pB);
bool sort_items_reverse_price(clsItem *pA, clsItem *pB);

bool sort_items_startprice(clsItem *pA, clsItem *pB);
bool sort_items_reverse_startprice(clsItem *pA, clsItem *pB);

bool sort_items_reserveprice(clsItem *pA, clsItem *pB);
bool sort_items_reverse_reserveprice(clsItem *pA, clsItem *pB);

bool sort_items_bidcount(clsItem *pA, clsItem *pB);
bool sort_items_reverse_bidcount(clsItem *pA, clsItem *pB);

bool sort_items_quantity(clsItem *pA, clsItem *pB);
bool sort_items_reverse_quantity(clsItem *pA, clsItem *pB);

bool sort_items_title(clsItem *pA, clsItem *pB);
bool sort_items_reverse_title(clsItem *pA, clsItem *pB);

// Class forward
class clsMarketPlace;
class clsDatabase;

class clsItems
{
	public:
		clsItems(clsMarketPlace *pMarketPlace);
		~clsItems();

		//
		// GetItem
		//	The "populate" parameter indicates whether 
		//	the item should be populated with derived
		//	fields such as category names, buyer and
		//	seller userids, descriptions, etc. Obviously,
		//	setting "populate" to true involves extra
		//	database actitivity, and is only used in 
		//	certain circumstances.
		//
		clsItem *GetItem(int id, 
						 bool populate = false,
						 char *pRowId = NULL,
						 time_t delta = 0,
						 bool blocked = false);

		clsItem *GetItemEnded(int id,
						   bool withDescription = true,
						   char *pRowId = NULL,
						   time_t delta = 0);
		// abbreviated item arc
		clsItem *GetItemArc(int id);

		// full item detail from arc
		clsItem *GetItemArcDet(int id);
		
		//
		// AddItem adds an item
		//
		void AddItem(clsItem *pItem, bool blocked = false);

		//
		// DeleteItem deletes an item
		//
		void DeleteItem(int id, bool blocked = false);

		//
		// DeleteAllItems removes ALL items
		//
		void DeleteAllItems();

		//
		// GetNextItemId
		//
		int	GetNextItemId();

		// GetOldItem
		// gets an archived item including descriptions
		clsItem *GetOldItem(int id);
		//
		// GetActiveItems
		//	This method fills in a vector with ALL unexpired
		//	items.
		//
		void GetActiveItems(ItemVector *pItemVector,
										ItemListSortEnum SortCode = SortItemsByUnknown);

		// 
		// GetSuperFeaturedItems
		// This method fills in a vector with all super featured
		// items.
		void GetFeaturedItems(ItemVector *pItemVector, long endDate,
										ItemListSortEnum SortCode = SortItemsByUnknown);

		// GetHotItems
		void GetHotItems(ItemVector *pItemVector, long endDate,
										ItemListSortEnum SortCode = SortItemsByUnknown);
		
		// GetItemsNotNoticed
		void GetItemsNotNoticed(vector<int> *pItemVector,
										time_t fromdate,
										time_t todate
										);

		void GetItemsNotNoticedRowId(vector<clsItemIdRowId*> *pRowVector,
										time_t fromdate,
										time_t todate);

		// GetItemsNotBilled
		void GetItemsNotBilled(vector<int> *pItemVector,
										ItemListSortEnum SortCode = SortItemsByUnknown);
		void GetItemsCompleted(ItemVector *pvItems, time_t endDate,
										ItemListSortEnum SortCode = SortItemsByUnknown);

		// GetItemsEnding between endDate1 and endDate2
		void GetItemsEnding(ItemVector *pvItems, time_t endDate1, time_t endDate2,
										ItemListSortEnum SortCode = SortItemsByUnknown);


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
		//
		// Prepare active listing items
		void PrepareActiveListingItems(time_t Endtime);

		// Prepare completed listing items
		void PrepareCompletedListingItems(time_t Endtime);

		// Remove listing items
		void RemoveListingItems();

		// Retrieve listing items in the specified category
		void GetListingItemsInCategory(int CatId, ListingItemVector* pItemVector);

		// Get number of Going items in a category
		int GetGoingItemCountInCategory(int);
		
		// get all going items
		ListingItemVector* GetAllGoingItemList();

	 	// Number of listing items in the specified category
		int  GetNumberOfListingItemsInCategory(int CatId);
		int  GetNumberOfNewTodayItemsInCategory(int CatId);
		int  GetNumberOfEndingTodayItemsInCategory(int CatId);

		// Retrieve super featured listing items
		void GetSuperFeaturedListingItems(ListingItemVector* pItemVector);

		// Retrieve super featured listing items
		void GetHotListingItems(ListingItemVector* pItemVector);

		//
		// *********** End of RebuildList functions *************
		//

		// Retrieve all ids of all items that are super-featured and
		//  have sale_end's after endDate and that are in a descendant of category catId
		void GetAllFeaturedItemIds(vector<int> *pvItemIds, time_t endDate, int catId=0);

		// Retrieve all ids of all items that are featured OR super-featured and
		//  have sale_end's after endDate and that are in a descendant of category catId
		void GetSuperFeaturedItemIds(vector<int> *pvItemIds, time_t endDate, int catId=0);

		// Retrieve all ids of all items that are hot and
		//  have sale_end's after endDate and that are in a descendant of category catId
		void GetHotItemIds(vector<int> *pvItemIds, time_t endDate, int catId=0);

		// Retrieve all ids of all items that are hot and aren't Dutch
		//  have sale_end's after endDate and that are in a descendant of category catId
		void GetHotNonDutchItemIds(vector<int> *pvItemIds, time_t endDate, int catId=0);


		// Retrieve all ids of all items that are staff picks and
		//  have sale_end's after endDate and that are in a descendant of category catId
		void GetStaffPicksItemIds(vector<int> *pvItemIds, time_t endDate, int catId=0);

		// Retrieve all ids of all items that are black-listed and
		//  have sale_end's after endDate and that are in a descendant of category catId
		void GetBlackListItemIds(vector<int> *pvItemIds, time_t endDate, int catId=0);

		// Retrieve all ids of all items that are black-listed and
		//  have sale_end's after endDate and that are in a descendant of category catId
		void GetGalleryListItemIds(vector<int> *pvItemIds, time_t endDate, int catId=0);

		// Retrieve all ids of all items that
		//  have sale_end's after endDate
		void GetActiveItemIds(vector<int> *pvItemIds, time_t endDate);

		//  have sale_end's after endDate and that are in a descendant of category catId
		void GetActiveItemIds(vector<int> *pvItemIds, time_t endDate, int catId=0);

		// Retrieve random ids of all items that
		//  have sale_end's after endDate and that are in a descendant of category catId
		void GetRandomItemIds(vector<int> *pvItemIds, time_t endDate, int catId=0, int howMany=-1);

		// Retrieve all ids of all items that
		//  have sale_end's after endDate and that are in a descendant of category catId
		// this one uses GetItemIdsVector()
		void GetHighTicketIds(vector<int> *pvItemIds, time_t endDate, float price);

		// Retrieve all ids of all items that
		//  have sale_end's after endDate and that are in a descendant of category catId
		void GetHighTicketItems(vector<int> *pvItemIds, time_t endDate, float Price);
		
		//
		// Tweaked method to get all the items for a credit batch
		//
		void GetManyItemsForCreditBatch(
						list<unsigned int> *pItemIdList,
						ItemList *pItems,
						list<unsigned int> *pMissingItemIdList);

		//
		// Tweaked method to get all the items for a credit batch
		//
		void GetManyItemsForAuctionEnd(
						list<unsigned int> *pItemIdList,
						vector<clsItemPtr> *pItems,
						list<unsigned int> *pMissingItemIdList,
						bool bGetCompleteItem = false);


		//
		// Transaction related functions
		//
		// This is to check whether the item is sold from the seller
		// and the bidder. It also checks whether the transaction has
		// been used for leaving a feedback.
		// the parameter: int Used, is the enum to indicate whether the feedback is
		// used by the seller or bidder.
		bool IsValidTransactionFeedback(int Item, int SellerId, int BidderId, char NewFlag);

		// Set the flag after a transaction related feedback is left
		void SetTransactionUsed(int Item, int SellerId, int BidderId, char NewFlag);

		void ArchiveItem(clsItem *pItem);
		void EndItem(clsItem *pItem);

		// Sam, 02/01/99, Auto Credits
		int  GetNextCreditBatchId();

		void GetAllUnProcessedCredits(int batch_id, CreditsVector *pvCredits);

		// AddSpecialItem adds an item into the ebay_special_items
		void AddSpecialItem(int id, int kind, time_t end_date);

		// DeleteSpecialItem deletes an item from the ebay_special_items
		void DeleteSpecialItem(int id);

		// FlushEndedSpecialItem removes items that auction has ended from ebay_special_items
		void FlushSpecialItem();

		//
		// Just a Hack
		//
		ItemListSortEnum	mCurrentSortMode;

	    private:
			clsMarketPlace	*mpMarketPlace;
			ItemVector		*mpItemVector; 
};

#define CLSITEMS_INCLUDED 1
#endif /* CLSITEMS_INCLUDED */
