/*	$Id: clsItems.cpp,v 1.13.2.2 1999/07/17 02:16:27 inna Exp $	*/
//
//	File:		clsItems.cc
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
//				- 05/20/99 jennifer - added functions for Gallery Admin Tool
//
#include "eBayKernel.h"
#include "eBayTypes.h"
#include "clsGalleryChangedItem.h"
#include "clsItemsCache.h"

#include <time.h>

//
// Constructor
//
clsItems::clsItems(clsMarketPlace *pMarketPlace) : mCurrentSortMode(SortItemsByUnknown)
{
	// Choose your database folks
	mpMarketPlace	= pMarketPlace;
	return;
}

//
// Destructor
//
clsItems::~clsItems()
{
	return;
}

//
// GetItem
//
clsItem *clsItems::GetItem(int id,
						   bool withDescription,
						   char *pRowId,
						   time_t delta,
						   bool blocked)	// get from blocked items table
{
	bool		gotItem;

	clsItem		*pItem;

	pItem	= new clsItem;

	// Let's plug it!
	if (!withDescription)
	{
		gotItem = 
			gApp->GetDatabase()->GetItem(mpMarketPlace->GetId(),
								id, pItem, pRowId, delta, false, blocked);
	}
	else
	{		
		gotItem = 
			gApp->GetDatabase()->GetItemWithDescription(
								mpMarketPlace->GetId(),
								id,
								pItem, pRowId, delta, false, blocked);
	}
	if (!gotItem)
	{
		delete pItem;
		return NULL;
	}
	else
		return pItem;
}

clsItem *clsItems::GetItemEnded(int id,
						   bool withDescription,
						   char *pRowId,
						   time_t delta)
{
	bool		gotItem;

	clsItem		*pItem;

	pItem	= new clsItem;

	// Let's plug it!
	if (!withDescription)
	{
		gotItem = 
			gApp->GetDatabase()->GetItem(mpMarketPlace->GetId(),
								id, pItem, pRowId, delta, true);
	}
	else
	{		
		gotItem = 
			gApp->GetDatabase()->GetItemWithDescription(
								mpMarketPlace->GetId(),
								id,
								pItem, pRowId, delta, true);
	}
	if (!gotItem)
	{
		delete pItem;
		return NULL;
	}
	else
		return pItem;
}

// gets abbreviated item from archive
clsItem *clsItems::GetItemArc(int id)
{
	bool		gotItem;

	clsItem		*pItem;

	pItem	= new clsItem;

	gotItem = 
		gApp->GetDatabase()->GetItemArc(id, pItem);

	if (!gotItem)
	{
		delete pItem;
		return NULL;
	}
	else
		return pItem;
}			

// gets full item detail, no description from archive
clsItem *clsItems::GetItemArcDet(int id)
{
	bool		gotItem;

	clsItem		*pItem;

	pItem	= new clsItem;

	gotItem = 
		gApp->GetDatabase()->GetItemArc(mpMarketPlace->GetId(), id, pItem);

	if (!gotItem)
	{
		delete pItem;
		return NULL;
	}
	else
		return pItem;
}			

//
// GetOldItem
//
clsItem *clsItems::GetOldItem(int id)
{
	bool		gotItem;

	clsItem		*pItem;

	pItem	= new clsItem;

	gotItem = 
			gApp->GetDatabase()->GetItemWithDescArc(
								mpMarketPlace->GetId(),
								id,
								pItem
											  );
	if (!gotItem)
	{
		delete pItem;
		return NULL;
	}
	else
		return pItem;
}

//
// AddItem
//
// Just adds an item to the database. If the item
// id is 0 (which it usually is!), we get the next
// item id first.  If blocked is true, add the item
// to the blocked items table instead of the active
// items table.
//
void clsItems::AddItem(clsItem *pItem, bool blocked)
{
	if (pItem->GetId() == 0)
	{
		pItem->SetId(gApp->GetDatabase()->GetNextItemId());
	}
	pItem->TsAndCsSet();

	gApp->GetDatabase()->AddItem(pItem, blocked);
	gApp->GetDatabase()->AddItemDesc(pItem, blocked);

	if (!blocked)
		gApp->GetDatabase()->AdjustMarketPlaceItemCount(mpMarketPlace->GetId(),
										   +1);

	return;
}

//
// DeleteItem
//
void clsItems::DeleteItem(int id, bool blocked /* = false */)
{
	gApp->GetDatabase()->DeleteItem(mpMarketPlace->GetId(), id, false, blocked);
	return;
}

//
// DeleteAllItems
//
void clsItems::DeleteAllItems()
{
	gApp->GetDatabase()->ClearAllItems();
	return;
}

//
// GetNextItemId
//
int clsItems::GetNextItemId()
{
	return gApp->GetDatabase()->GetNextItemId();
}


//
// yet another generic database calls: getItemsVector with 1=active, 2=featured, 3=hot
//
void clsItems::GetActiveItems(ItemVector *pvItems,
						   ItemListSortEnum SortCode /* = SortItemsByUnknown */)
{
	time_t curtime;
	curtime	= time(0);
	gApp->GetDatabase()->GetItemsVector(
										(MarketPlaceId)mpMarketPlace->GetId(),
										curtime,
										0,
										1,
										pvItems,
										SortCode
															);
	return;
}

// 
// GetItemsNotNoticed
//
void clsItems::GetItemsNotNoticed(vector<int> *pvItems,
//						   int daySince,
							time_t fromdate,
							time_t todate
//						   ItemListSortEnum SortCode /* = SortItemsByUnknown */
)
{
	gApp->GetDatabase()->GetItemsNotNoticed(
							(MarketPlaceId)mpMarketPlace->GetId(),
							pvItems,
//							daySince,
							fromdate,
							todate
//							SortCode
										   );
	return;
}

// 
// GetItemsNotNoticed - Get vector of Row Ids
//
void clsItems::GetItemsNotNoticedRowId(vector<clsItemIdRowId*> *pvRows,
							time_t fromdate,
							time_t todate)
{
	gApp->GetDatabase()->GetItemsNotNoticedRowId(
							(MarketPlaceId)mpMarketPlace->GetId(),
							pvRows,
							fromdate,
							todate
										   );
	return;
}
//
// GetItemsNotBilled
//
void clsItems::GetItemsNotBilled(vector<int> *pvItems,
						   ItemListSortEnum SortCode /* = SortItemsByUnknown */)
{
   gApp->GetDatabase()->GetItemsNotBilled(
                     (MarketPlaceId)mpMarketPlace->GetId(),
                     pvItems
                                 );
   return;
}

// GetSuperFeaturedItems
// This method fills in a vector with all super featured
// items.
void clsItems::GetFeaturedItems(ItemVector *pvItems, time_t endDate,
						   ItemListSortEnum SortCode /* = SortItemsByUnknown */)
{
	gApp->GetDatabase()->GetItemsVector(
										(MarketPlaceId)mpMarketPlace->GetId(),
										endDate,
										0,
										2,
										pvItems,
										SortCode
										);
	return;
};

// GetHotItems
void clsItems::GetHotItems(ItemVector *pvItems, time_t endDate,
						   ItemListSortEnum SortCode /* = SortItemsByUnknown */)
{
	gApp->GetDatabase()->GetItemsVector(
										(MarketPlaceId)mpMarketPlace->GetId(),
										endDate,
										mpMarketPlace->GetHotItemCount(),
										3,
										pvItems,
										SortCode
										);
	return;
};

void clsItems::GetItemsCompleted(ItemVector *pvItems, time_t endDate,
						   ItemListSortEnum SortCode /* = SortItemsByUnknown */)
{
	gApp->GetDatabase()->GetItemsVector(
									(MarketPlaceId)mpMarketPlace->GetId(),
									endDate,
									0,
									4,
									pvItems,
									SortCode);
};

// Get item ending
void clsItems::GetItemsEnding(ItemVector *pvItems, time_t endDate1, time_t endDate2,
						   ItemListSortEnum SortCode /* = SortItemsByUnknown */)
{
	gApp->GetDatabase()->GetItemsVector(
									(MarketPlaceId)mpMarketPlace->GetId(),
									endDate1,
									0,
									5,
									pvItems,
									endDate2,
									SortCode);
}

//
// Prepare active listing items
//
void clsItems::PrepareActiveListingItems(time_t EndTime)
{
	gApp->GetDatabase()->PrepareListingItems(mpMarketPlace->GetId(), 
											1,
											EndTime);
}

//
// Prepare completed listing items
//
void clsItems::PrepareCompletedListingItems(time_t EndTime)
{
	gApp->GetDatabase()->PrepareListingItems(mpMarketPlace->GetId(), 
											0,
											EndTime);
}

//
// Remove listing items (cleanup)
//
void clsItems::RemoveListingItems()
{
	gApp->GetDatabase()->RemoveListingItems();
}

//
// Retrieve listing items in the specified category (including those
// in the child categories)
//
void clsItems::GetListingItemsInCategory(int CatId,
										 ListingItemVector* pItemVector)
{
	gApp->GetDatabase()->GetListingItemsInCategory(CatId, pItemVector);
}

//
// Number of listing items in the specified category (including those
// in the child categories)
//
int clsItems::GetNumberOfListingItemsInCategory(int CatId)
{
	return gApp->GetDatabase()->GetNumberOfListingItemsInCategory(CatId);
}

int clsItems::GetNumberOfNewTodayItemsInCategory(int CatId)
{
	return gApp->GetDatabase()->GetNumberOfNewTodayItemsInCategory(CatId);
}

int clsItems::GetNumberOfEndingTodayItemsInCategory(int CatId)
{
	return gApp->GetDatabase()->GetNumberOfEndingTodayItemsInCategory(CatId);
}

//
// Retrieve super featured listing items
//
void clsItems::GetSuperFeaturedListingItems(ListingItemVector* pItemVector)
{
	gApp->GetDatabase()->GetSuperFeaturedListingItems(pItemVector);
}

//
// Retrieve super featured listing items
//
void clsItems::GetHotListingItems(ListingItemVector* pItemVector)
{
	gApp->GetDatabase()->GetHotListingItems(pItemVector);
}

// Get Going item count
int clsItems::GetGoingItemCountInCategory(int CatId)
{
	return gApp->GetDatabase()->GetGoingItemCountInCategory(CatId);
}

// Get Going Items
ListingItemVector* clsItems::GetAllGoingItemList()
{
	return gApp->GetDatabase()->GetAllGoingItemList();
}

// Retrieve all ids of all items that are super-featured and
//  have sale_end's after endDate and that are in a descendant of category catId
void clsItems::GetSuperFeaturedItemIds(vector<int> *pvItemIds, time_t endDate, int catId /* = 0 */)
{
   gApp->GetDatabase()->GetItemIdsVector(mpMarketPlace, pvItemIds, endDate, eGetSuperFeatured, catId);
   return;
}

// Retrieve all ids of all items that are either featured OR super-featured and
//  have sale_end's after endDate and that are in a descendant of category catId
void clsItems::GetAllFeaturedItemIds(vector<int> *pvItemIds, time_t endDate, int catId /* = 0 */)
{
   gApp->GetDatabase()->GetItemIdsVector(mpMarketPlace, pvItemIds, endDate, eGetAllFeatured, catId);
   return;
}

// Retrieve all ids of all items that are hot and
//  have sale_end's after endDate and that are in a descendant of category catId
void clsItems::GetHotItemIds(vector<int> *pvItemIds, time_t endDate, int catId /* = 0 */)
{
   gApp->GetDatabase()->GetItemIdsVector(mpMarketPlace, pvItemIds, endDate, eGetHot, catId);
   return;
}

// Retrieve all ids of all items that are hot and aren't Dutch
//  have sale_end's after endDate and that are in a descendant of category catId
void clsItems::GetHotNonDutchItemIds(vector<int> *pvItemIds, time_t endDate, int catId /* = 0 */)
{
   gApp->GetDatabase()->GetItemIdsVector(mpMarketPlace, pvItemIds, endDate, eGetHotNonDutch, catId);
   return;
}



// Retrieve all ids of all items that are staff picks and
//  have sale_end's after endDate and that are in a descendant of category catId
void clsItems::GetStaffPicksItemIds(vector<int> *pvItemIds, time_t endDate, int catId /* = 0 */)
{
   gApp->GetDatabase()->GetItemIdsVector(mpMarketPlace, pvItemIds, endDate, eGetStaffPicks, catId);
   return;
}

// Retrieve all ids of all items that are black-listed and
//  have sale_end's after endDate and that are in a descendant of category catId
void clsItems::GetBlackListItemIds(vector<int> *pvItemIds, time_t endDate, int catId /* = 0 */)
{
   gApp->GetDatabase()->GetItemIdsVector(mpMarketPlace, pvItemIds, endDate, eGetBlackList, catId);
   return;
}

// Retrieve all ids of all items that are in gallery pool and
//  have sale_end's after endDate and that are in a descendant of category catId
void clsItems::GetGalleryListItemIds(vector<int> *pvItemIds, time_t endDate, int catId /* = 0 */)
{
   gApp->GetDatabase()->GetItemIdsVector(mpMarketPlace, pvItemIds, endDate, eGetGalleryList, catId);
   return;
}

// Retrieve all ids of all items that
//  have sale_end's after endDate and that are in a descendant of category catId
void clsItems::GetActiveItemIds(vector<int> *pvItemIds, time_t endDate, int catId /* = 0 */)
{
   gApp->GetDatabase()->GetItemIdsVector(mpMarketPlace, pvItemIds, endDate, eGetActive, catId);
   return;
}

//
// GetRandomItemIds
//
// Retrieve all ids of all items that
//  have sale_end's after endDate and that are in a descendant of category catId
// currently, this mimics getactiveitems and gets EVERYTHING. we may want to limit
// the number of items returned in the future.
//
// howMany -1: ALL, 0: none, 0+: count <-CURRENTLY DISABLED
//
void clsItems::GetRandomItemIds(vector<int> *pvItemIds, time_t endDate, int catId /* = 0 */, int howMany)
{
   gApp->GetDatabase()->GetItemIdsVector(mpMarketPlace, pvItemIds, endDate, eGetActiveRandom, catId);
   return;
}

//
//
// this one goes through the standard vector to take advantage of caching
void clsItems::GetHighTicketIds(vector<int> *pvItemIds, time_t endDate, float price)
{
   gApp->GetDatabase()->GetItemIdsVector(mpMarketPlace, pvItemIds, endDate, eGetHighTicket, 0 /* cat id */, 0 /* end limit */, price);
   return;
}


// Retrieve all ids of high ticket price items that
// have sale_end after endDate
void clsItems::GetHighTicketItems(vector<int> *pvItemIds, time_t endDate, float Price)
{
	gApp->GetDatabase()->GetHighTicketItems(pvItemIds, endDate, Price);

	return;
}




//
// Item sorting comparison functions
//

// by item id
bool sort_items_id(clsItem *pA, clsItem *pB)
{
	return (pA->GetId() < pB->GetId());
}
bool sort_items_reverse_id(clsItem *pA, clsItem *pB)
{
	return (pA->GetId() > pB->GetId());
}

// by start time
bool sort_items_start_time(clsItem *pA, clsItem *pB)
{
	return (pA->GetStartTime() < pB->GetStartTime());
}
bool sort_items_reverse_start_time(clsItem *pA, clsItem *pB)
{
	return (pA->GetStartTime() > pB->GetStartTime());
}

// by end time
bool sort_items_end_time(clsItem *pA, clsItem *pB)
{
	return (pA->GetEndTime() < pB->GetEndTime());
}
bool sort_items_reverse_end_time(clsItem *pA, clsItem *pB)
{
	return (pA->GetEndTime() > pB->GetEndTime());
}

// by current price
bool sort_items_price(clsItem *pA, clsItem *pB)
{
	double	aPrice;
	double	bPrice;

	aPrice	= pA->GetPrice();
	if (aPrice == 0)
		aPrice	= pA->GetStartPrice();

	bPrice	= pB->GetPrice();
	if (bPrice == 0)
		bPrice	= pB->GetStartPrice();

	return (aPrice < bPrice);
}
bool sort_items_reverse_price(clsItem *pA, clsItem *pB)
{
	double	aPrice;
	double	bPrice;

	aPrice	= pA->GetPrice();
	if (aPrice == 0)
		aPrice	= pA->GetStartPrice();

	bPrice	= pB->GetPrice();
	if (bPrice == 0)
		bPrice	= pB->GetStartPrice();

	return (aPrice > bPrice);
}

// by start price
bool sort_items_startprice(clsItem *pA, clsItem *pB)
{
	return (pA->GetStartPrice() < pB->GetStartPrice());
}
bool sort_items_reverse_startprice(clsItem *pA, clsItem *pB)
{
	return (pA->GetStartPrice() > pB->GetStartPrice());
}

// by reserve price
bool sort_items_reserveprice(clsItem *pA, clsItem *pB)
{
	return (pA->GetReservePrice() < pB->GetReservePrice());
}
bool sort_items_reverse_reserveprice(clsItem *pA, clsItem *pB)
{
	return (pA->GetReservePrice() > pB->GetReservePrice());
}

// by bid count
bool sort_items_bidcount(clsItem *pA, clsItem *pB)
{
	return (pA->GetBidCount() < pB->GetBidCount());
}
bool sort_items_reverse_bidcount(clsItem *pA, clsItem *pB)
{
	return (pA->GetBidCount() > pB->GetBidCount());
}

// by quantity
bool sort_items_quantity(clsItem *pA, clsItem *pB)
{
	return (pA->GetQuantity() < pB->GetQuantity());
}
bool sort_items_reverse_quantity(clsItem *pA, clsItem *pB)
{
	return (pA->GetQuantity() > pB->GetQuantity());
}

// by title
bool sort_items_title(clsItem *pA, clsItem *pB)
{
	return (strcmp(pA->GetTitle(), pB->GetTitle()) < 0);
}
bool sort_items_reverse_title(clsItem *pA, clsItem *pB)
{
	return (strcmp(pA->GetTitle(), pB->GetTitle()) > 0);
}

//
// Tweaked method to get all the items for a credit batch
//
//
//	** NOTE **
//	This method may be giving me a headache. It knows, 
//	internally, to look in the active items database and
//	in the archive database. I'm not sure if we should
//	expose that here, or in the database layer.
//	** NOTE **
//
void clsItems::GetManyItemsForCreditBatch(
						list<unsigned int> *pItemIdList,
						ItemList *pItems,
						list<unsigned int> *pMissingItemIdList)
{
	// Itch me!
	list<unsigned int>::iterator	iItemId;
	list<unsigned int>::iterator	iMissingItemId;

	ItemList::iterator				iItem;

	// An Item
	clsItem							*pItem;


	// First, copy the list to the missing item list.
	//
	// ** NOTE **
	// I had some weird problems compiling an insert_iterator on VC++,
	// so I did it this way
	// ** NOTE **
	for (iItemId = pItemIdList->begin();
		 iItemId != pItemIdList->end();
		 iItemId++)
	{
		pMissingItemIdList->push_back((*iItemId));
	}

	int i = pMissingItemIdList->size();

	// Now, get things from the normal database
	gApp->GetDatabase()->GetManyItemsForCreditBatch(mpMarketPlace->GetId(),
													pItemIdList,
													pItems);

	// Now, remove the items we got from the missing item list
	for (iItem = pItems->begin();
		 iItem != pItems->end();
		 iItem++)
	{
		pItem	= (*iItem).mpItem;

		// Note that we CAN'T break once we find the item in 
		// the list, since an item can appear more than once
		// in a batch, and if we find it once, we've found
		// it for all of them ;-)
		for (iMissingItemId = pMissingItemIdList->begin();
			 iMissingItemId != pMissingItemIdList->end();
			 iMissingItemId++)
		{
			if ((*iMissingItemId) == pItem->GetId())
			{
				pMissingItemIdList->erase(iMissingItemId);
				break;
			}
		}
	}


	// Now, anything still missing comes from the ended database
	gApp->GetDatabase()->GetManyEndedItemsForCreditBatch(mpMarketPlace->GetId(),
													   pMissingItemIdList,
													   pItems);
	for (iItem = pItems->begin();
		 iItem != pItems->end();
		 iItem++)
	{
		pItem	= (*iItem).mpItem;

		for (iMissingItemId = pMissingItemIdList->begin();
			 iMissingItemId != pMissingItemIdList->end();
			 iMissingItemId++)
		{
			if ((*iMissingItemId) == pItem->GetId())
			{
				pMissingItemIdList->erase(iMissingItemId);
				break;
			}
		}
	}

	// Now, anything still missing comes from the Arc database
	gApp->GetDatabase()->GetManyArcItemsForCreditBatch(mpMarketPlace->GetId(),
													   pMissingItemIdList,
													   pItems);

	// Now, remove the items we got from the missing item list. Now,
	// this is a little redundant, since we're also scanning the items
	// we already got, but we can live with that.
	for (iItem = pItems->begin();
		 iItem != pItems->end();
		 iItem++)
	{
		pItem	= (*iItem).mpItem;

		for (iMissingItemId = pMissingItemIdList->begin();
			 iMissingItemId != pMissingItemIdList->end();
			 iMissingItemId++)
		{
			if ((*iMissingItemId) == pItem->GetId())
			{
				pMissingItemIdList->erase(iMissingItemId);
				break;
			}
		}
	}

	// All done!
	return;
}

//
// Tweaked method to get all the items for auction ending
//
void clsItems::GetManyItemsForAuctionEnd(
						list<unsigned int> *pItemIdList,
						vector<clsItemPtr> *pItems,
						list<unsigned int> *pMissingItemIdList,
						bool bGetCompleteItem)
{
	// Itch me!
	list<unsigned int>::iterator	iItemId;
	list<unsigned int>::iterator	iMissingItemId;

	vector<clsItemPtr>::iterator	iItem;

	// An Item
	clsItem							*pItem;


	// First, copy the list to the missing item list.
	//
	// ** NOTE **
	// I had some weird problems compiling an insert_iterator on VC++,
	// so I did it this way
	// ** NOTE **
	for (iItemId = pItemIdList->begin();
		 iItemId != pItemIdList->end();
		 iItemId++)
	{
		pMissingItemIdList->push_back((*iItemId));
	}

	int i = pMissingItemIdList->size();

	// Now, get things from the normal database
	gApp->GetDatabase()->GetManyItemsForAuctionEnd(mpMarketPlace->GetId(),
												   pItemIdList,
												   pItems,
												   bGetCompleteItem);

	// Now, remove the items we got from the missing item list
	for (iItem = pItems->begin();
		 iItem != pItems->end();
		 iItem++)
	{
		pItem	= (*iItem).mpItem;

		// Note that we CAN'T break once we find the item in 
		// the list, since an item can appear more than once
		// in a batch, and if we find it once, we've found
		// it for all of them ;-)
		for (iMissingItemId = pMissingItemIdList->begin();
			 iMissingItemId != pMissingItemIdList->end();
			 iMissingItemId++)
		{
			if ((*iMissingItemId) == pItem->GetId())
			{
				pMissingItemIdList->erase(iMissingItemId);
				break;
			}
		}
	}
	// Now, anything still missing comes from the ended database
	gApp->GetDatabase()->GetManyEndedItemsForAuctionEnd(mpMarketPlace->GetId(),
													   pItemIdList,
													   pItems);
	for (iItem = pItems->begin();
		 iItem != pItems->end();
		 iItem++)
	{
		pItem	= (*iItem).mpItem;

		for (iMissingItemId = pMissingItemIdList->begin();
			 iMissingItemId != pMissingItemIdList->end();
			 iMissingItemId++)
		{
			if ((*iMissingItemId) == pItem->GetId())
			{
				pMissingItemIdList->erase(iMissingItemId);
				break;
			}
		}
	}

	// All done!
	return;
}

//
// Check whether the transacton can be used for transaction based feedback
//
bool clsItems::IsValidTransactionFeedback(int Item, int SellerId, int BidderId, char Used)
{
	return gApp->GetDatabase()->IsValidTransaction(Item, SellerId, BidderId, Used);
}

//
// Set a flag to indicate the transaction has been used
//
void clsItems::SetTransactionUsed(int Item, int SellerId, int BidderId, char Used)
{
	gApp->GetDatabase()->SetTransactionUsed(Item, SellerId, BidderId, Used);
}

void clsItems::ArchiveItem(clsItem *pItem)
{
	gApp->GetDatabase()->RemoveItem(pItem);
}

void clsItems::EndItem(clsItem *pItem)
{
	gApp->GetDatabase()->EndItem(pItem);
}


// Sam, 02/01/99, Auto Credits
int clsItems::GetNextCreditBatchId()
{
	return (gApp->GetDatabase()->GetNextBatchIdForCredits());

}


void clsItems::GetAllUnProcessedCredits(int batch_id, CreditsVector *pvCredits)
{
	gApp->GetDatabase()->GetAllNewCredits(batch_id, pvCredits);
}


// AddSpecialItem adds an item into the ebay_special_items
void clsItems::AddSpecialItem(int id, int kind, time_t endDate)
{
	gApp->GetDatabase()->AddSpecialItem(id, kind, endDate);
}

// DeleteSpecialItem deletes an item from the ebay_special_items
void clsItems::DeleteSpecialItem(int id)
{
	gApp->GetDatabase()->DeleteSpecialItem(id);
}

// FlushEndedSpecialItem removes items that auction has ended from ebay_special_items
void clsItems::FlushSpecialItem()
{
	gApp->GetDatabase()->FlushSpecialItem();
}
