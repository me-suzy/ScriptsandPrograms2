/*	$Id: clsDatabase.h,v 1.21.2.11.2.3 1999/08/10 17:21:48 phofer Exp $	*/
//
//	File:		clsDatabase.h
//
// Class:	clsDatabase.h
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Abstract base class
//
// Modifications:
//				- 02/09/97 michael	- Created
//				- 06/09/97 tini     - added user virtual functions for Oracle
//				- 06/20/97 tini		- added functions to handle renaming users.
//				- 07/30/97 wen		- added function to retrieve item count
//									  for a marketplace; Added functions
//									  to adjust and retrieve bid count for
//									  a marketplace.
//				- 09/02/97 wen		- added functions for listing items
//				- 09/20/97 chad		- added functions for voiding feedback value
//				- 10/13/97 poon		- added GetItemIdsVector
//				- 11/19/97 charles	- added GetItemsCountByCategory()
//				- 12/06/97 michael	- added RecentFeedbackFromUser, RecentFeedbackFromHost
//				- 09/08/98 wen		- added GetHighTicketItems()
//				- 09/25/98 mila		- added functions for deadbeat items and users
//				- 10/02/98 mila		- added GetDeadbeatItemsByBidderId
//				- 10/13/98 mila		- added GetAllDeadbeatItems
//				- 12/01/98 mila		- added GetDeadbeatItemsBySellerId, AddDeadbeat,
//									  GetDeadbeat, et al.
//				- 12/08/98 mila		- added GetAllDeadbeats
//				- 12/15/98 mila		- added GetDeadbeatItemCountBySellerId and
//									  GetDeadbeatItemCountByBidderId
//				- 04/12/99 mila		- added lots of new methods for Legal Buddy project
//									  (most named *Filter*() or *Message*())
//				- 04/19/99 samuel   - added functions to handle currency exchange rates
//
//				- 05/03/99 Gurinder - added an admin function to ReInstate Item
//				- 05/11/99 jnace	- added region ID parameter to GetCategoryCountsFromOpenItems
//				- 05/20/99 jennifer - added functions for Gallery Admin Tool
//				- 07/29/99 Sonya	- added functions for Invoice and Balance Aging State checking
//
//				- 05/25/99 nsacco	- added siteId to LoadPartnerHeaderAndFooter
//									  and new LoadSites and LoadSite
//				- 06/04/99 petra	- added siteId to categories
//				- 06/21/99 nsacco	- added siteId and pParsedString to CreateCobrandPartner
//				- 07/02/99 petra	- added GetLocaleInfo
//				- 07/06/99 nsacco	- added siteid and co_partnerid
//				- 08/09/99 petra	- added GetNumberOfSites


#ifndef CLSDATABASE_INCLUDED
#define CLSDATABASE_INCLUDED 1
#include "eBayTypes.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsBid.h"
#include "clsFeedback.h"
#include "clsAccount.h"
#include "clsAccountDetail.h"
#include "clsBulletinBoard.h"
#include "clsCategories.h"
#include "clsListingItem.h"
#include "clsStatisticsTransaction.h"
#include "clsDailyStatistics.h"
#include "clsDailyFinance.h"
#include "clsAnnouncement.h"
#include "clsItems.h"	// for sorting items
#include "clsUserCode.h"
#include "clsAuthorizationQueue.h"
#include "clsEndOfMonthBalance.h"
#include "clsNoteAddressList.h"
#include "clsNotes.h"
#include "clsNote.h"
#include "clsGiftOccasion.h"
#include "clsLocations.h"
#include "clsCountries.h" // for CountryVector
#include "clsCurrencies.h" // for CurrencyVector
#include <hash_map.h>
#include "clsDeadbeat.h"
#include "clsDeadbeatItem.h"
#include "clsEOAState.h"
#include "clsExchangeRates.h"
#include "clsCategoryFilter.h"
#include "clsCategoryMessage.h"
#include "clsFilter.h"
#include "clsFilters.h"
#include "clsFilterMessage.h"
#include "clsMessage.h"
#include "clsMessages.h"
#include "clsInvAndBalAgingState.h"
#include "clsPartnerAd.h"
#include "clsAd.h"
#include "clsIntlLocale.h"		// petra

// Typedefs

// Class forward
class clsItem;
class clsCategory;
class clsListingItemList;
class clsAnnouncement;
class clsPartner;
class clsUserPage;
class clsNeighbor;
class clsGiftOccasion;
class clsFeedbackRowItem; 
class clsGalleryChangedItem;
class clsItemGalleryInfo;
class clsRegion;
class clsRegions;
// nsacco 05/25/99
class clsSite;

class clsBidderOrSellerItem
{
public:
	clsBidderOrSellerItem() : mId(0), mSaleEnd((time_t)0) {}
	virtual ~clsBidderOrSellerItem() {}
    clsBidderOrSellerItem(int id, time_t saleEnd) : mId(id), mSaleEnd(saleEnd) {}
	int				mId;
	time_t			mSaleEnd;
};
typedef list<clsBidderOrSellerItem> BidderOrSellerItemList;

class clsSellerItem : public clsBidderOrSellerItem
{
public:
	clsSellerItem() {}
	clsSellerItem(int id, time_t saleEnd) : clsBidderOrSellerItem(id, saleEnd) {}
};


typedef list<clsSellerItem> SellerItemList;

class clsBidderItem : public clsBidderOrSellerItem
{
public:
	clsBidderItem() {}
	clsBidderItem(int id, time_t saleEnd) : clsBidderOrSellerItem(id, saleEnd) {}
};


typedef list<clsBidderItem> BidderItemList;

class clsHeader;
class clsFooter;

class clsDatabase
{
	public:

		// Constructor, Destructor
		clsDatabase(char *pHost);
		virtual	~clsDatabase();

		//
		// Begin and End are specialized calls to 
		// bound complex transactions (and hopefully
		// speed up performance)
		//
		virtual void Begin() = 0;
		virtual void End() = 0;
		virtual void Cancel() = 0;
		virtual bool InTransaction() = 0;

		//
		// Universal Cancel
		//
		virtual void CancelQuery() = 0;

		//
		// rollback all pending transactions
		//
		virtual void CancelPendingTransactions() = 0;

		//
		// Various modes for transactions
		//
		virtual void SetReadCommitted() = 0;
		virtual void SetSerializable() = 0;
		virtual void SetReadOnly() = 0;

		//
		// Cehck whether the rowid is in the right format
		//
		virtual bool IsValidRowIdFormat(const char *pRowId) = 0;


		// *****
		// Items
		// *****

		//
		// ClearAllItems
		//
		virtual void ClearAllItems() = 0;

		//
		// Get an item
		//
		virtual bool GetItem(int id,
							 clsItem *pItem,
							 char *pRowNo,
							 time_t delta,
							 bool ended = false,
							 bool blocked = false) = 0;
		virtual bool GetItem(int marketplace,
							 int id,
							 clsItem *pItem,
							 char *pRowNo,
							 time_t delta,
							 bool ended = false,
							 bool blocked = false) = 0;
		virtual bool GetItemDescription(int marketplace,
										int id,
										clsItem *pItem,
										bool blocked = false) = 0;
		virtual bool GetItemWithDescription(
								int marketplace,
								int id,
								clsItem *pItem,
								char *pRowNo,
								time_t delta,
								bool ended = false,
								bool blocked = false) = 0;
		//
		// Getting various lists of items
		//
		virtual void GetItemsListedByUser(MarketPlaceId marketplace,
										  int id,
										  int daysSince,
										  ItemList *pItems,
										  bool getMoreStuff = false,
										  ItemListSortEnum SortCode = SortItemsByUnknown) = 0;

		virtual void GetItemsListedByUserActiveOrEnded(
								MarketPlaceId marketplace,
								int id,
								int	daysSince,
								SellerItemList *lItems,
								ItemList *pItems,
								bool getMoreStuff /* = false */,
								ItemListSortEnum SortCode /* = SortItemsByUnknown */,
								bool ended) = 0;

		virtual int GetItemsListedByUserCount(MarketPlaceId marketplace,
											  int id) = 0;

		virtual void GetItemsBidByUser(MarketPlaceId marketplace,
							 		   int id,
									   int daysSince,
									   ItemList *pItems,
									   bool getMoreStuff= false,
									   ItemListSortEnum SortCode = SortItemsByUnknown,
									   bool withPrivate = false) = 0; // Added by charles

		virtual void GetItemsBidByUserActiveOrEnded(
								MarketPlaceId marketplace,
								int id,
								int	daysSince,
								BidderItemList *lItems,
								ItemList *pItems,
								bool getMoreStuff /* = false */,
								ItemListSortEnum SortCode /* = SortItemsByUnknown */,
								bool withPrivate,
								bool ended) = 0;

		virtual int GetItemsBidByUserCount(MarketPlaceId marketplace,
										   int id) = 0;

		virtual void GetItemsHighBidByUser(MarketPlaceId marketplace,
							 			   int id,
										   bool completed,
										   ItemList *pItems,
										   ItemListSortEnum SortCode = SortItemsByUnknown,
										   bool ended = false) = 0;

		virtual void GetItemsVector(MarketPlaceId marketplace,
									time_t enddate,
									int hotcount,
									int QueryCode,
									ItemVector *pvItems,
									time_t endlimitdate = 0,
									ItemListSortEnum SortCode = SortItemsByUnknown,
									bool ended = false) = 0;

		virtual void GetItemsNotNoticed(MarketPlaceId marketplace,
										vector<int> *pvItems,
										time_t fromdate,
										time_t todate
										) = 0;

		virtual void GetItemsNotNoticedRowId(MarketPlaceId marketplace,
										vector<clsItemIdRowId*> *pvRows,
										time_t fromdate,
										time_t todate) = 0;

		virtual void GetItemsNotBilled(MarketPlaceId marketplace,
									   vector<int> *pvItems) = 0;

//		virtual void GetItemIdsToEnd(vector<int> *pvItems, time_t startDate, time_t endDate) = 0;
		// Retrieve item ids in a vector.
		// queryCode: 1=active, 2=superfeatured, 3=hot, 4=completed, 5=endlimit (g,g,g),
		//				6=staff picks.
		virtual void GetItemIdsVector(clsMarketPlace *pMarketPlace, 
										vector<int> *pvItemIds,
										time_t endDate,
										GetItemIdsEnum queryCode  = eGetActive, 
										int catId = 0,
										time_t endLimitDate = 0,
										float price = 0,
										bool OKToUseCache=true, bool ended = false) = 0;

		// Retrieve Active High Price Items
		// 
		virtual void GetHighTicketItems(vector<int> *pvItemIds, 
										time_t endDate, 
										float Price) = 0;


		// finds all items that are modified after a certain date
		virtual void GetItemsModifiedAfter(MarketPlaceId marketplace,
							 		   time_t modDate,
									   ItemVector *pItems,
										ItemListSortEnum SortCode = SortItemsByUnknown) = 0;
		// Lena - merge!
		virtual void GetItemsModifiedAfterMinimal(MarketPlaceId,
				time_t, time_t, ItemVector *, bool started = 0) = 0;

		virtual void GetManyItemDescriptions(MarketPlaceId marketplace,
											 ItemVector::iterator iStart,
											 ItemVector::iterator iEnd, bool ended = false) = 0;
		//
		// Indicates that an item's end-of-auction notice had
		// been sent out
		//
		virtual void AddItemNoticed(clsItem *pItem) = 0;
		virtual long GetNoticeTime(clsItem *pItem) = 0;	
		
		//
		// Indicates that an items billing notice has been sent
		//	out
		//
		virtual void AddItemBilled(clsItem *pItem) = 0;

		//  
		// store GMS number for dutch auctions
		// to help reports
		//
		virtual void SetDBDutchGMS(clsItem *pItem, float price)=0;
		
		virtual long GetBillTime(clsItem *pItem) = 0;
		//
		// Gets the next availible item id
		//
		virtual int GetNextItemId() = 0;

		//
		// Add an item description, add an item
		//
		virtual void AddItemDesc(clsItem *pI, bool blocked = false) = 0;

		virtual void AddItem(clsItem *pItem, bool blocked = false) = 0;
		//
		// Update an item description, update an item
		//
		virtual void UpdateItemDesc(clsItem *pItem, bool blocked = false) = 0;
		virtual void UpdateItem(clsItem *pItem, bool blocked = false) = 0;
		virtual void UpdateItemStatus(clsItem *pItem) = 0;

		//
		// Archive functions
		//
		// used only to archive item descriptions
		virtual void ClearItemsToBad() = 0;
		virtual void SetItemsToBad(int id, int btype) = 0; 
		virtual void GetItemsToArchive(MarketPlaceId  marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate) = 0;
		virtual void GetItemsToEnded(MarketPlaceId marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate) = 0;
		virtual void UpdateItemDescArc(clsItem *pItem) = 0;		
		virtual void AddItemDescArc(clsItem *pItem) = 0;

		virtual void GetItemsByEndDateArc(MarketPlaceId marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate) = 0;
		virtual bool GetItemDescArc(int id,
										clsItem *pItem) = 0;
		virtual void AddItemDescEnded(clsItem *pItem) = 0;
		virtual void GetItemsFromTemp(vector<int> *vItems) = 0;
		virtual int GetItemDescArc(int id, unsigned char **description) = 0;
		virtual bool GetItemWithDescArc(
								int marketplace,
								int id,
								clsItem *pItem) = 0;

		virtual void GetAllActiveItems(ListingItemVector * pvItems) = 0;
		virtual void GetAllActiveItemsAllTable(ListingItemVector * pvItems) = 0;
		/* this is abbreviated item for admin batch only! */
		virtual bool GetItemArc(int id,
							 clsItem *pItem) = 0;
		
		/* this gets the full item for summary report of archived items */
		virtual bool GetItemArc(int marketplace,
							 int id,
							 clsItem *pItem) = 0;

		virtual void GetBidsArc(MarketPlaceId marketplace,
							 int item_id,
							 BidVector *pBids) = 0;

		//
		// Update a item Password
		//
		virtual void UpdateItemPassword(clsItem *pItem) = 0;

		
		//
		// Delete an item
		//
		virtual void DeleteItem(int marketplace,
								int id,
								bool ended = false,
								bool blocked = false) = 0;

		//
		// Set a new Title
		//
		virtual void SetNewTitle(clsItem *pItem) = 0;

		//
		// Set a new high bidder
		//
		virtual void SetNewHighBidder(clsItem *pItem) = 0;
		virtual void SetNewHighBidderAndBidCount(clsItem *pItem) = 0;

		//
		// Set a new bidcount
		//
		virtual void SetNewBidCount(clsItem *pItem) = 0;

		//
		// Set a new description
		//
		virtual void SetNewDescription(clsItem *pItem) = 0;

		//
		// Sets a new dutch high bidder
		//
		virtual void SetDutchHighBidder(clsItem *pItem, clsBid *pBid) = 0;

		//
		// Deletes all dutch high bidders associated with this item
		//
		virtual void DeleteDutchHighBidder(clsItem *pItem) = 0;

		//
		// Set a new ending time
		//
		virtual void SetNewEndTime(clsItem *pItem) = 0;

		//
		// Set a new featured
		//
		virtual void SetNewFeatured(clsItem *pItem) = 0;

		//
		// Set a new super featured
		//
		virtual void SetNewSuperFeatured(clsItem *pItem) = 0;

		//
		// Set a new category
		//
		virtual void SetNewCategory(clsItem *pItem) = 0;

		//
		// adjusts visitcount by delta
		//
		virtual void SetItemVisitCount(clsItem *pItem, int delta) = 0;
		
		//
		// Adjust the item count
		// 
		virtual void AdjustMarketPlaceItemCount(int marketPlaceId,
												int delta) = 0;

		//
		// Retrieve the item count since inception
		//
		virtual int GetItemCountSinceInception(MarketPlaceId marketplaceId) = 0;
		virtual int GetDailyItemCount(MarketPlaceId marketplaceId) = 0;


		virtual int GetItemsCountOn(MarketPlaceId marketplace, 
			time_t enddate) = 0;

		//
		// SPECIAL Get many items for a credit batch
		//
		virtual void GetManyItemsForCreditBatch(MarketPlaceId marketplace,
												list<unsigned int> *pItemIdList,
												ItemList *pItems) = 0;
		virtual void GetManyEndedItemsForCreditBatch(
								MarketPlaceId marketplace,
								list<unsigned int> *pItemIdList,
								ItemList *pItems) = 0;
		virtual void GetManyArcItemsForCreditBatch(MarketPlaceId marketplace,
												list<unsigned int> *pItemIdList,
												ItemList *pItems) = 0;
		virtual void RemoveItem(clsItem *pItem) = 0;
		virtual void RemoveItemFromEnded(clsItem *pItem) = 0;
		virtual void EndItem(clsItem *pItem) = 0;
//		virtual void ArchiveItem(clsItem *pItem, char *month, char *year) = 0;
		virtual void ArchiveItem(clsItem *pItem) = 0;
//		virtual void DeleteFromItemInfo(clsItem *pItem) = 0;
		virtual void GetMonthYear(time_t saleStart, char *month, char *year) = 0;

		virtual void GetManyItemsForAuctionEnd(
								MarketPlaceId marketplace,
								list<unsigned int> *pItemIdList,
								vector<clsItemPtr> *pvItems,
								bool bGetCompleteItem = false) = 0;

		virtual void GetManyEndedItemsForAuctionEnd(
								MarketPlaceId marketplace,
								list<unsigned int> *pItemIdList,
								vector<clsItemPtr> *pItems) = 0;

		virtual void AddItemDescArcByMonth(clsItem *pItem) = 0;
		virtual bool SetItemGalleryInfo(int itemID, clsItemGalleryInfo& info) = 0;
		virtual bool GetItemGalleryInfo(int itemID, clsItemGalleryInfo& info) = 0;

		///////////////
		//Admin options
		virtual bool ReInstateItem(clsItem *pItem) = 0;


		// **********
		// Bids 
		// **********

		//
		// Get all the bids on an item
		//
		virtual void GetBids(MarketPlaceId marketplace,
							 int item_id,
							 BidVector *pBids, bool ended = false) = 0;

		//
		// Get a user's highest bid on an item
		//
		virtual clsBid *GetHighestBidForUser(MarketPlaceId marketplace,
											 int item_id,
											 int user_id, bool ended = false) = 0;
		//
		// Get the highest bids on an item
		//
		virtual void GetHighestBidsForItem(bool lock,
								   MarketPlaceId marketplace,
								   int item_id,
								   clsBid **pHighestBid,
								   clsBid **pNextHighestBid, bool ended = false) = 0;

		virtual void GetHighestBidsForItem(bool lock,
								   MarketPlaceId marketplace,
								   int item_id,
								   int item_qty,
								   BidVector *pvHighBids, bool ended = false) = 0;

		virtual void GetDutchHighBidders(MarketPlaceId marketplace,
									int itemId,
									BidVector *pvBids) = 0;

		// Get all the bids count for an item
		//
		virtual int GetItemBids(MarketPlaceId marketplace,
							 int item_id, bool ended = false) = 0;

		//
		// Get all the bids for an item, sorted by date within amount
		//
		virtual void GetBidsForItemSorted(
									MarketPlaceId marketplace,
									int item_id,
									BidVector *pvHighBids, bool ended = false) = 0;

		
		
		// 
		// Add a bid
		//
		virtual void AddBid(MarketPlaceId marketplace,
							int item,
							clsBid *pBid,
							bool blocked = false) = 0;

		//
		// Retract all of a user's bids on an item
		//
		virtual void RetractBids(MarketPlaceId marketplace,
								 int item,
								 int user,
								 BidActionEnum type) = 0;

		//
		// Delete a user's bid on an item
		//
		virtual void DeleteBid(int item,
							   int user) = 0;

		//
		// Delete a user's bid on an item
		//
		virtual void DeleteBids(int marketplace,
							    int item) = 0;

		//
		// Get the bidders on an item
		//
		virtual void GetBiddersForItem(MarketPlaceId marketplace,
										  int id,
										  list<int> *plUsers, bool ended = false) = 0;
		//
		// Adjust the bid count for a marketplace
		// 
		virtual void AdjustMarketPlaceBidCount(int marketPlaceId,
									   int delta) = 0;

		//
		// Retrieve bid count since inception
		//
		virtual int GetBidCountSinceInception(MarketPlaceId marketplaceId) = 0;


		//
		// Routines made public to invalidate seller and bidder
		// lists
		//
		virtual void InvalidateSellerList(MarketPlaceId marketplace,
										  int sellerId, int itemId = 0, time_t saleEnd = 0) = 0;

		virtual void InvalidateBidderList(MarketPlaceId marketplace,
										  int BidderId, int itemId = 0, time_t saleEnd = 0) = 0;

		// delete seller and bidder lists
		virtual void DeleteSellerList(MarketPlaceId marketplace,
										  int sellerId) = 0;

		virtual void DeleteBidderList(MarketPlaceId marketplace,
										  int BidderId) = 0;

		virtual void GetUsersWithBidderLists(vector <unsigned int> &vUserList) = 0;
		virtual void GetUsersWithSellerLists(vector <unsigned int> &vUserList) = 0;

		// **********
		// Users
		// **********

		// Getting a user in different flavors
		virtual clsUser *GetUserById(int id) = 0;
		virtual void GetUserAndFeedbackById(int id,
									clsUser **ppUserId,
									clsFeedback **ppFeedback) = 0;

		// Get a user by email address
		virtual clsUser *GetUserByEmail(int id, char *pUserId) = 0;
		virtual void GetUserAndFeedbackByEmail(int marketplace,
									   char *pEmail,
									   clsUser **ppUserId,
									   clsFeedback **ppFeedback) = 0;



		// Get full blown user and info by Id
		virtual clsUser *GetUserAndInfoById(int id)=0;
	
		virtual clsUser *GetUserByUserId(int marketplace,
										 char *pUserId) = 0;
		virtual void GetUserAndFeedbackByUserId(int marketplace,
										char *pUserId,
										clsUser **ppUserId,
										clsFeedback **ppFeedback) = 0;


		virtual clsUser *GetUserInfo(clsUser *pUser) = 0;

		// updates creation date to earlier time
		virtual void UpdateUserCreation(clsUser *pUser) = 0;

		//
		// ChangeUserId
		//
		virtual bool ChangeUserId(int marketplace,
								  int id,
								  char *pNewUserId) = 0;

/*
		//
		// UserIdChangedInInterval
		//
		virtual bool UserIdChangedInInterval(int marketplace,
											 int id,
											 int interval) = 0;

		//
		// EMailChangedInInterval
		//
		virtual bool EMailChangedInInterval(int marketplace,
											int id,
											int interval) = 0;

*/

		//
		// AddUserAlais
		//
		virtual void AddUserAlias(int marketplace,
									 int id,
									 char *pAlias,
									 char *pHost,
									 time_t changeTime) = 0;

		//
		// AddEmailAlais
		//
		virtual void AddEmailAlias(int marketplace,
									 int id,
									 char *pAlias,
									 char *pHost,
									 time_t changeTime) = 0;

		//
		// GetAliasHistory
		//
		virtual void GetAliasHistory(int marketplace,
									 int id,
									 UserAliasHistoryVector *pVAlias) = 0;
        //GetUserIdAliasHistory
		virtual void GetIdByAlias(int marketplace,
									 char *alias,
									 UserIdAliasHistoryVector *pvUsers)= 0;
		// admin info per user
		virtual bool GetUserAdminInfo(clsUser *pUser, int code) = 0;
		virtual void SetUserAdminInfo(clsUser *pUser, bool doThey, int code) = 0;

		// Get all the ids of users with a minimum feedback level
		virtual void GetUserIdsByFeedback(int minVal,
								  vector<int> *pvUsers) = 0;

		virtual void GetUserIdsAndFeedbackByFeedback(
								  int minVal,
								  vector<clsUserPtr> *pvUsers) = 0;


		// inna Get the ids of users with unsplit feedback in range
		virtual void GetUserIdsUnsplit(int start_id, int end_id,
								  vector<int> *pvUsers) = 0;

		// Next availible id for a user. Sorry about the
		// name!
		virtual int GetNextUserId() = 0;

		// Adding a user
		virtual void AddUser(clsUser *pUser) = 0;

		// And their Info
		virtual void AddUserInfo(clsUser *pUser) = 0;

		// Updating a user
		virtual void UpdateUser(clsUser *pUser) = 0;

		// Update User info
		virtual void UpdateUserInfo(clsUser *pUser) = 0;

		// checks if a user already has an info record
		virtual bool HasUserInfo(clsUser *pUser) = 0;

		// Delete
		virtual void DeleteUserLists(clsUser *pUser) = 0;
		virtual void DeleteUserInfo(clsUser *pUser) = 0;
		virtual void DeleteUser(clsUser *pUser) = 0;

		// Rename
		virtual void RenameUser(MarketPlaceId marketPlace,
								char *pOldUserId,
								char *pNewUserId) = 0;


		// adds a record to ebay_renamed_users table
		virtual void AddRenamedUser(char *pOldUserId,
									char *pNewUserId) = 0;

		// deletes a record to ebay_renamed_users table
		virtual void DeleteRenamedUser(char *pOldUserId) = 0;

		// renames renamed user
		virtual void RenameRenamedUser(char *pOldUserId,
								char *pNewUserId) = 0;

		// Adding many users
		// nsacco 07/06/99 added siteid and co_partnerid
		virtual void AddManyUsers(
						int count,
						int	*pMarketplaces,
						int *pIds,
						char *pUserIds,
						int userIdLen,
						char *pEmails,
						int emailLen,
						UserStateEnum *pUserStates,
						char *pPasswords,
						int passwordLen,
						char *pSalts,
						int saltLen,
						char *pLastUpdates,
						int lastUpdateLen,
						int *pSiteIds,
						int *pCoPartnerIds
						) = 0;

		virtual void AddManyUsersInfo(
						int count,
						int *pIds,
						char *pHosts,
						int hostLen,
						char *pNames,
						int nameLen,
						char *pCompanies,
						int companyLen,
						char *pAddresses,
						int addressLen,
						char *pCitys,
						int cityLen,
						char *pStates,
						int stateLen,
						char *pZips,
						int zipLen,
						char *pCountrys,
						int countryLen,
						char *pPhones,
						int phoneLen,
						char *pCreations,
						int creationLen,
						int *pcounts,
						char *pCredit_cards,
						char *pGood_credits,
						char *pGenders,
						int GenderLen,
						int *pinterests_1,
						int *pinterests_2,
						int *pinterests_3,
						int *pinterests_4) = 0;

		virtual void RenameIdInUserAssets(int oldid,
									int newid) = 0;

		//check whether user participated the servey
		virtual bool IsParticipatedSurvey(int survey_idd, int useri) = 0;

		//insert a record into ebay_user_survey_record 
		virtual void AddUserToSurveyRecord(int survey_id, int userid) = 0;

		// kludge fix for ebay_account_xref constraint go around
		virtual int IsUserAccountXref(int id) = 0;
		virtual void DeleteUserAccountXref(int id) = 0;

		virtual bool IsUserSpecial(char *pUserId) = 0;

		virtual bool IsUserRenamePending(clsUser *pUser, char *pNewUserId) = 0;

		// Another kludge. Get the user's AW credit-card-on-file and
		// good_credit status.
		virtual void AddAWCreditCardOnFile(char *pUserId) = 0;
		virtual void AddAWGoodCredit(char *pUserId) = 0;
		virtual void GetAWCreditStatus(char *pUserId, 
									   bool *pCreditCardOnFile,
									   bool *pGoodCredit) = 0;


		virtual void GetUsersBySubstring(MarketPlaceId marketplace,
										 UserSearchTypeEnum how,
										 char *pString,
										 vector<clsUser *> *pvUsers) = 0;

		virtual bool GetUserRenamePendingCode(clsUser *pUser, 
										char *pNewId,
										char *pSalt) = 0;

		virtual void SetUserRenamePending(clsUser *pUser, 
										char *pNewId, 
										char *pPass,
										char *pSalt) = 0;

		virtual void DeleteUserRenamePending(int Id) = 0;
		virtual void ExpireUserRenamePending(time_t endTime) = 0;

		virtual void SetUserDateAndHost(int id,
										time_t whem,
										char *pHost) = 0;

		virtual void AddReqEmailCount(int id,
										int delta) = 0;

		virtual void ResetReqEmailCount(int id) = 0;

		// This returns true if we are allowed to send (id) information
		// about another user, and false if we aren't -- it also updates
		// our recors -- incrementing their 'requested count' by 1, and
		// storing their host (and possibly alerting us if someone is
		// requesting too much).

		// Returns 0 for 'okay', 1 for 'not okay',
		// and '-1 for NOTIFY SOMEONE!'
		virtual int CanReceiveInfo(int id, const char *pHost) = 0;

		// Just reset it to 0.
		virtual void ResetCanReceiveInfo(int id) = 0;

		//
		// Tweaked method to get users for Credit, Accounting batch
		//
		virtual void GetManyUsersForCreditBatch(MarketPlaceId marketplace,
										list<unsigned int> *pUserIdList,
										UserList *pUsers) = 0;

		//
		// A more general one, used by the user cache
		//
		virtual void GetManyUsers(MarketPlaceId marketplace,
								  list<UserId> *pUserIdList,
								  list<UserId> *pMissingUserIdList,
								  UserList *pUsers) = 0;



		//
		// GetActiveUser
		//
		virtual void GetActiveUsers(MarketPlaceId marketplace,
										 vector<int> *pvIds) = 0;

		// Get all 
		virtual void GetAllUsers( MarketPlaceId marketplace, vector<unsigned int> *pvIds,
								int minId = 0, int maxId = 0) = 0;

		// **********
		// User Attributes
		// **********

		virtual void GetUserAttribute(int user_id,
									  int attribute_id,
									  bool *pGotBoolResponse,
									  bool *pBoolResponse,
									  bool *pGotNumberResponse,
									  float *pNumberResponse,
									  bool *pGotTextResponse,
									  char **ppTextResponse) = 0;


		virtual void SetUserAttributeValue(int user_id,
										   int attribute_id,
										   bool value) = 0;

		virtual void SetUserAttributeValue(int user_id,
										   int attribute_id,
										   float value) = 0;

		virtual void SetUserAttributeValue(int user_id,
										   int attribute_id,
										   int value) = 0;

		virtual void SetUserAttributeValue(int user_id,
										   int attribute_id,
										   char *pValue) = 0;

		// **********
		// Demographic
		// **********
		virtual void GetUserCodeVector(UserCodeVector *pvUserCodeVector) = 0;




		// **********
		// Feedback
		// **********

		//
		// Private routine to add summary feedback
		//
		virtual void AddFeedback(int id, int score,
						 int flags, bool split=false) = 0;


		// Get a user's feedback object
		virtual clsFeedback *GetFeedback(int id) = 0;

		// Determine if a user has left feedback for a given
		// user
		virtual bool UserHasFeedbackFromUser(int id,
											 int commentingId,
											 bool Split) = 0;

		// recompute score server version
		virtual int GetRecomputedFeedbackScore(int id, 
			bool split) = 0;

		// Various Feedback updates
		virtual void SetFeedbackScore(int id, int score) = 0;
		virtual void UpdateFeedbackFlags(int id, int score) = 0;
		//inna
		virtual void UpdateFeedbackSplitFlag(int id) = 0;

		// Get a user's feedback detail
		virtual void GetFeedbackDetail(
						int id,
						FeedbackItemVector *pvDetails,
						bool Split
									  ) = 0;

		// inna Get a user's feedback detail to split into 10 tables
		virtual void GetFeedbackDetailToSplit(
						int id, int Split,
						FeedbackItemVector *pvDetails
						) = 0;
		// inna insert feedback detail into appropriate X10 table
		virtual void SplitFeedbackDetail(clsFeedbackItem *pFeedbackItem
										)= 0;
									
		// Get the minimal version of a user's feedback detail
		virtual void GetMinimalFeedbackDetail(
						int id,
						MinimalFeedbackItemVector *pvDetails,
						bool Split
											 ) = 0;

		//
		// Get Feedback left by a user
		//
		virtual void GetFeedbackDetailLeftByUser(
						int id,
						FeedbackItemVector *pvFeedbackDetail
							) = 0;

		virtual void VoidFeedbackLeftByUser(
						int commenting_id
							) = 0;

		virtual void RestoreFeedbackLeftByUser(
						int commenting_id
							) = 0;
		
		virtual void InvalidateExtendedFeedback(int id) = 0;

		virtual void UpdateExtendedFeedback(clsFeedback *pFeedback) = 0;

		virtual void GetSuspendedUsers(
						vector<int> *pVUsers) = 0;

		// Add a feedback detail record
		virtual void AddFeedbackDetail(
							   int id,
							   int commentingId,
							   char *pCommentingHost,
							   FeedbackTypeEnum type,
							   int score,
							   char *pText,
							   bool Split,
							   int item=0) = 0;

		virtual void UpdateResponse(int Commentor, 
									time_t CommentDate, 
									int Commentee, 
									const char* pResponse,
									bool Split) = 0;

		virtual void UpdateFollowUp(int Commetor, 
									time_t CommentDate, 
									int Commentee, 
									const char* pFollowUp,
									bool Split) = 0;

		virtual bool HasResponse(int Commentor, 
								time_t CommentDate, 
								int Commentee,
								bool Split) = 0;

		virtual bool HasFollowUp(int Commentor, 
								time_t CommentDate, 
								int Commentee,
								bool Split) = 0;

		virtual clsFeedbackItem* GetFeedbackItem(
					  int Commentor,
					  time_t CommentingDate,
					  int Id,
					  bool Split) = 0;

		virtual int GetFeedbackDetailCount(int id, bool Split) = 0;

		// Transfer Feedback for a user
		virtual void TransferFeedback(clsUser *pFromUser,
									  clsUser *pToUser) = 0;

		// Transfer feedback LEFT by a user
		virtual void TransferFeedbackLeft(clsUser *pFromUser,
										  clsUser *pToUser) = 0;


		// Delete's feedback record
		virtual void DeleteFeedback(int id)=0;

		// 
		// Recent feedback from users, hosts
		//
		virtual clsFeedbackItem *RecentFeedbackFromUser(
												int id,
												int commentingId,
												time_t timeLimit,
												bool negativeFeedbackOnly
													   ) = 0;

		virtual clsFeedbackItem *RecentFeedbackFromHost(
												int id,
												char *pCommentingHost,
												time_t timeLimit,
												bool negativeFeedbackOnly
													   ) = 0;


		// Migration - Add LOTS of feedback ;-)
		virtual void AddManyFeedbackDetail(
								int	count,
								int *pIds,
								char *pTimes,
								int timeLen,
								int *pCommentingIds,
								char *pCommentingHosts,
								int phoneLen,
								FeedbackTypeEnum *pTypes,
								int *pScores,
								char *pTexts,
								int textLen) = 0;

		// transaction table for feedback
		virtual void AddTransactionRecord(int Item, 
								int SellerId, 
								int* pBidders, 
								int BidderCount, 
								time_t Date) = 0;

		virtual bool IsValidTransaction(int Item, int SellerId, int Bidder, char Flag) = 0;
		virtual void SetTransactionUsed(int Item, int SellerId, int BidderId, char Flag) = 0;

		virtual bool GetFeedbackListFromFeedbackList(int id,
					vector<clsFeedbackRowItem *> *pvFeedback) = 0;

		virtual void UpdateFeedbackList(int Id,
					vector<clsFeedbackItem *> *pvFeedback) = 0;

		virtual void InvalidateFeedbackList(int Id) = 0;

		virtual void DeleteFeedbackList(int Id) = 0;

		// get feedback detail from feedback detail cache list
		virtual void GetFeedbackDetailFromList(
						int id,
						FeedbackItemVector *pvDetails,
						bool Split,
						int Offset,
						int Length,
					    int *pTotalItems) = 0;

		virtual void GetFeedbackDetailFromListMinimal(
						int id,
						MinimalFeedbackItemVector *pvMinDetails,
						bool Split,
						int Offset,
						int Length
									  ) = 0;

		//  **********
		//	Accounts
		//  **********

		// Get a user's account "balance" (summary)
		virtual clsAccount *GetAccount(int id) = 0;

		virtual void UpdateCCDetails(	int id, 
				 	int cc_First4Digits, 
				 	time_t cc_Expirydate,
				 	time_t cc_Updatetime) = 0;
		virtual bool UpdateIndicator( int id, int indicator ) = 0;
		//inna 
		virtual void SetTableIndicator( int id, int &indicator ) = 0;

        virtual void GetUserAccounts(AccountsVector *pvAccounts) = 0;

        //
        // Update last email notice sent
        //
        virtual void UpdateExpiredNoticeSent(int id,
											 time_t cc_NoticeSentDate)=0;

		// Add a detail record
/* Lena		virtual void AddAccountDetail(
						int id,
						clsAccountDetail *pDetail
									 ) = 0;
*/
/*		virtual void AddAccountDetail(
						int id, int tableIndicator,
						clsAccountDetail *pDetail, int itemId, int batchId = 0
									 ) = 0;  */
		virtual void AddAccountDetail(
						int id, int tableIndicator,
						clsAccountDetail *pDetail ) = 0;
									 
		virtual bool LoadAccountDetail(
						int id,
						int tableIndicator,
						clsAccountDetail *pDetail ) = 0;

/*	Lena	virtual void AddRawAccountDetail(
						int id,
						clsAccountDetail *pDetail,
						int migrationBatchId
								) = 0;
*/
/*		virtual void AddRawAccountDetail(
						int id,
						int tableIndicator,
						clsAccountDetail *pDetail,
						int migrationBatchId,
						int batchId = 0
								) = 0;  */
		virtual void AddRawAccountDetail(
						int id,
						int tableIndicator,
						clsAccountDetail *pDetail,
						int migrationBatchId ) = 0;
/* Lena		virtual void AddRawAccountDetail(
						int count,
						int *pId,
						char *pWhen,
						int *pAction,
						float *pAmount,
						char *pMemo,
						int *pSeq,
						int *pMigrationBatchId) = 0;
*/
		virtual void AddRawAccountDetail(
						int count,
						int *pId,
						int tableIndicator,
						char *pWhen,
						int *pAction,
						float *pAmount,
						char *pMemo,
						int *pSeq,
						int *pMigrationBatchId,
						int *batchId, int itemId = 0) = 0;

		virtual void AddInterimBalance(
						int id,
						time_t theTime,
		       			double amount ) = 0;
		virtual bool GetInterimBalance( 
						int id, 
						time_t &theTime, 
						double &amount, bool first = 0 ) = 0;
		virtual bool GetInterimBalanceForMonth( int id, time_t the_time ) = 0;

		virtual bool CombineInterimBalanceForUsers( int oldId, int newId ) = 0;

		virtual bool CombineInterimBalance( int oldId, int newId, 
											  time_t the_time ) = 0;
		virtual void CalculateDate( time_t &theDate ) = 0;
		virtual bool GetMonthRangeForUsers( int id, time_t &timeStart, 
											   time_t &timeEnd ) = 0;
		virtual void GetInterimBalances(int id,
										InterimBalanceList *plBalances) = 0;




		//
		// Hack Method
		//
		virtual int XAddRawAccountDetail(
						int count,
						int *pId,
						char *pWhen,
						int *pAction,
						float *pAmount,
						char *pMemo,
						int *pSeq,
						int *pMigrationBatchId) = 0;


		virtual void AddAccountAWItemXref(int count,
										  unsigned int *pSeq,
										  char *pItem) = 0;

		//
		// HACK method
		//
		virtual void XAddAccountAWItemXref(int count,
										  unsigned int *pSeq,
										  char *pItem) = 0;


		virtual void AddAccountItemXref(TransactionId id,
										int itemId) = 0;


		// Create an account balance record
		virtual void CreateAccount(int id,
								   double balance = 0) = 0;

		// Adjust account balance
		virtual void AdjustAccountBalance(int id,
										  double delta) = 0;

		// Rebalance account
// Lena		virtual void RebalanceAccount(int id) = 0;
		virtual void RebalanceAccount(int id, int tableIndicator) = 0;

		// Get Next transacton id
		virtual void GetNextTransactionId(TransactionId *pId) = 0;

		// Get a user's account detail
/*	Lena	virtual void GetAccountDetail(int id,
									  AccountDetailVector *pvDetail) = 0;
		virtual void GetAccountDetail(int id,
							AccountDetailVector *pvDetail, time_t since) = 0;
		virtual void GetAccountDetail(int id,
									  AccountDetailList *plDetail) = 0;

		virtual void GetAccountDetailUntil(int id,
							AccountDetailVector *pvDetail, time_t until) = 0;
		virtual void GetAccountDetail(int id,
					AccountDetailVector *pvDetail, time_t since, time_t until) = 0;


		// Get a user's account detail for an item
		virtual void GetAccountDetailForItem(int id,
											 int itemId,
											 AccountDetailVector *pvDetail) = 0;
*/
		virtual void GetAccountDetail(int id, int tableIndicator,
									  AccountDetailVector *pvDetail) = 0;
		virtual void GetAccountDetail(int id, int tableIndicator,
							AccountDetailVector *pvDetail, time_t since) = 0;
		virtual void GetAccountDetail(int id, int tableIndicator,
									  AccountDetailList *plDetail) = 0;

		virtual void GetAccountDetailUntil(int id, int tableIndicator,
							AccountDetailVector *pvDetail, time_t until) = 0;
		virtual void GetAccountDetail(int id, int tableIndicator,
					AccountDetailVector *pvDetail, time_t since, time_t until) = 0;


		// Get a user's account detail for an item
		virtual void GetAccountDetailForItem(int id,
												int tableIndicator,
											 int itemId,
											 AccountDetailVector *pvDetail) = 0;

		virtual void GetAccountDetailForAWItem(int id,
											   char *pItem,
											   AccountDetailVector *pvDetail) = 0;

/* Lena		virtual void GetAccountDetailByType(int id,
											AccountDetailTypeEnum type,
											AccountDetailVector *pvDeatil) = 0;

		// Delete detail based on time
		virtual void DeleteAccountDetailByTime(int id,
											   time_t theTime) = 0;
*/
		virtual void GetAccountDetailByType(int id, int tbaleIndicator,
											AccountDetailTypeEnum type,
											AccountDetailVector *pvDeatil) = 0;

		// Delete detail based on time
		virtual void DeleteAccountDetailByTime(int id, int tableIndicator,
											   time_t theTime) = 0;

		// Delete account balances - used in rename
		virtual void DeleteAccountBalance(int id) = 0;

		// Add a cross-reference to their old AW account
		virtual void AddAWAccountCrossReference(int id,
						         				int awAccountId) = 0;

		virtual void GetAWAccountCrossReference(int id,
												int *pAWId) = 0;

		virtual void GeteBayAccountCrossReference(int id,
												  int *peBayId) = 0;

		// Get bad account detail records for a user
		virtual void GetBadAccountDetail(int id,
									  AccountDetailVector *pvDetail) = 0;

		// Update past due
		virtual void UpdateAccountPastDue(int id,
										  time_t pastDueBase,
										  double pastDue30Days,
										  double pastDue60Days,
										  double pastDue90Days,
										  double pastDue120Days,
										  double pastDueOver120Days) = 0;
		// Get last AW update time
		virtual time_t GetLastUpdateFromAWTime(int id) = 0;
		virtual void SetLastUpdateFromAWTime(int id,
											 time_t when) = 0;

		//
		// Get a list of all users with accounts
		//
		virtual void GetUsersWithAccounts(vector<unsigned int> *pvIds) = 0;
		virtual void GetUsersWithUnsplitAccountsRange(
									vector<unsigned int> *pvIds,
									int idStart, int idEnd ) = 0;
		//inna - needed for balance aging
		virtual void GetAllUsersWithAccountsRange(
									vector<unsigned int> *pvIds,
									int idStart, int idEnd) = 0;

		// Get a list of users with bad account detail;
		// used only to fix free relisting problem
		virtual void GetUsersWithBadAccounts(vector<unsigned int> *pvIds) = 0;
		
		//
		// Update account Detail
		//
// Lena		virtual void UpdateAccountDetailTime(int userId,
//											 clsAccountDetail *pDetail) = 0;
		virtual void UpdateAccountDetailTime(int userId, int tableIndicator,
											 clsAccountDetail *pDetail) = 0;
		//inna
		virtual void GetPaymentsSince(int id, time_t tSinceDate, double &amount, 
										int tableIndicator) = 0;
		//inna
		virtual void GetPaymentsByDate(int id, time_t tSinceDate, time_t tEndDate, 
										double &amount, int tableIndicator) = 0;
		//inna
		virtual void AddEndOfMonthBalanceDelayed(clsEndOfMonthBalance *pEndOfMonthBalance)=0;
		virtual void AddEndOfMonthBalance(clsEndOfMonthBalance *pEndOfMonthBalance)=0;
		virtual clsEndOfMonthBalance *GetEndOfMonthBalanceById(int id, time_t tInvoiceDate) = 0;
		virtual void GetUsersForThisMonth(vector<unsigned int> *pvIds,
                                                        time_t tInvoiceDate, int idStart, int idEnd)=0;

		//inna - make summary report into a table output
		virtual void AddRawSummaryReportData(char *pCatName,
int allItem,
			int rItem,
			int rItemSold,
			int rItemNot,
			int nRItemSold,	
			int nRItemNot, 
			int dItemSold,
			int dItemNot,
			int allItemSold, 
			float sumAllItemSoldPrice,
			float sumRItemSoldPrice,
			float sumNRItemSoldPrice,
			float sumDItemSoldPrice,
			float sumBoldFee,
			float sumFeaturedFee,
			float sumSuperFeaturedFee,
			float sumListFee,
			float sumFVFee,
			float sumGalleryFee,
			float sumFeatureGalleryFee,
			float sumGiftIconFee,
			float rItemSoldFees,
			float rItemNotFees,
			float nRItemSoldFees,
			float nRItemNotFees,
			float dItemSoldFees,
			float dItemNotFees,
			float dAllItemFees,
			char * fromdate,
			int category_id) = 0;
		//
		// Get last invoice date
		//
		// ** NOTE **
		//	This code assumes we uniformly record invoice time as of
		//	a certain date. If it's in a range, then this won't work
		//	as it is intended.
		//
		virtual time_t GetLastInvoiceDate() = 0;

		//
		// Get invoices in a speficified date range
		//
//		virtual void GetInvoices(time_t startInvoiceTime,
//								 time_t endInvoiceTime,
//							 	 AccountDetailVector *pvDetail) = 0;

		virtual void GetInvoices(time_t startInvoiceTime,
								 time_t endInvoiceTime,
//							 	 AccountDetailVector *pvDetail) = 0;
								 InterimBalanceList *pInterimBalance,
								 int requestedId = 0, bool all = false) = 0;
		// get a list of users with accounts not invoiced on a certain date
		virtual void GetUsersWithAccountsNotInvoiced( 
									vector<unsigned int> *pvIds,
                                    time_t tInvoiceDate, 
									int idStart, int idEnd = 0 ) = 0;
		virtual void GetUsersWithPastDueNotCalculated(
						vector<unsigned int> *pvIds,
                        time_t tPastDueDate) = 0;

		virtual void SetAccountBalance(float balance, int id) = 0;

		virtual void InvoiceTime(tm &thisTime, int month) = 0;
		virtual int LastDayOfMonth(int month, int year) = 0;
		virtual bool LeapYear(int year) = 0;
		virtual bool GetEOAStateInfo(clsEOAState *pEOAState) = 0;
		virtual bool UpdateEOAStateInfo(clsEOAState *pEOAState) = 0;
		virtual bool CreateNextEOAStateInfo(clsEOAState *pEOAState) = 0;
		virtual void MakeInstanceComplete(clsEOAState *pEOAState) = 0;



		//	**********
		//	Categories
		//	**********
		virtual clsCategory *GetCategoryById(
							MarketPlaceId marketplace,
							CategoryId category,
							int siteId
											) = 0;

//		virtual clsCategory *GetCategoryByName(
//							MarketPlaceId marketplace,
//							char *pName
//											  ) = 0;

		virtual clsCategory *GetCategoryFirst(
							MarketPlaceId marketplace,
							CategoryId category,
							int QueryCode,
							int siteId
											  ) = 0;

		// getting category vectors use the same set of
		// declarations, etc; only the sql statement differ.
		// all = 1; topLevel = 2
		// children = 3, descendants = 4, siblings = 5
		virtual void GetCategoryVector(
							MarketPlaceId marketplace,
							CategoryId pId,
							int queryCode,
							CategoryVector *pCategoryVector,
							int siteId
							) = 0;

//		// sets order_no of a category
//		virtual void UpdateCategoryCounter(
//							clsCategory *pCategory,
//							int count) = 0;


		// Get the next available category id
		virtual int GetNextCategoryId() = 0;

		virtual void GetCategoryItems(MarketPlaceId marketplace,
									CategoryId category,
									int QueryCode,
									time_t enddate,
									ItemVector *pvItems,
									ItemListSortEnum SortCode = SortItemsByUnknown,
									bool ended = false) = 0;

		virtual int GetCategoryItemsCount(MarketPlaceId marketplace,
									CategoryId category,
									int QueryCode,
									time_t startdate,
									time_t enddate, bool ended = false) = 0;

		virtual int GetItemsCountByCategory(CategoryId category,
									int QueryCode,
									time_t startdate,
									time_t enddate) = 0;


//		// moves items from one category to another
//		virtual void SetCategoryItems(MarketPlaceId marketplace,
//									CategoryId oldCategory,
//									CategoryId newCategory) = 0;
//
//		// moves a user's interest from one category to another
//		// used when replacing a category with another
//		virtual void SetCategoryUsersInterests(CategoryId oldCategory,
//									CategoryId newCategory) = 0;

		// get total category count
		virtual int GetCategoryCount(MarketPlaceId marketplace)=0;

		// get category count under a sepcified category
		virtual int GetCategoryCountInCategory(MarketPlaceId marketplace, CategoryId id)=0;

		// Get the open item counts in each category, stored in
		// *ppvCounts[category_id]
		virtual void GetCategoryCountsFromOpenItems(vector<int> **ppvCounts, int iRegionID = 0) = 0;

		virtual void LockCategoryTable() = 0;

		// Get first two level category count
		virtual int GetFirstTwoLevelCategoryCount(MarketPlaceId marketplace) = 0;

		//	**********
		//	Bulletin Board
		//	**********
		virtual void GetBulletinBoardControlEntries(BulletinBoardVector *pvBoards) = 0;
		virtual void AddBulletinBoardControlEntry(clsBulletinBoard *pBoard) = 0;
		virtual void UpdateBulletinBoardControlEntry(clsBulletinBoard *pBoard) = 0;

		virtual void AddBulletinBoardEntry(BulletinBoardId board_id,
										   int id,
										   char *pEntry) = 0;

		virtual void TrimBulletinBoard(BulletinBoardId board_id,
									   int size) = 0;

		virtual void GetAllBulletinBoardEntries(
								BulletinBoardId board_id,
								BulletinBoardEntryList *plEntries,
								int maxPostAgeSeconds) = 0;


		// *********************
		// Listing items
		// *********************

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

		// Prepare items for retrieving
		void PrepareListingItems(MarketPlaceId MarketplaceId, 
									int ListType,
									time_t Endtime);

		// clean up the items
		void RemoveListingItems();

		// retrieve items from a specified category
		void GetListingItemsInCategory(int CatId, ListingItemVector* pItemVector);

		// get number of items under a specified category
		int  GetNumberOfListingItemsInCategory(int CatId);
		int  GetNumberOfNewTodayItemsInCategory(int CatId);
		int  GetNumberOfEndingTodayItemsInCategory(int CatId);

		// Retrieve super featured listing items
		void GetSuperFeaturedListingItems(ListingItemVector* pItemVector);

		// Retrieve super featured listing items
		void GetHotListingItems(ListingItemVector* pItemVector);

		// Get Going item count
		int GetGoingItemCountInCategory(int CatId);

		// Get all going items
		ListingItemVector* GetAllGoingItemList();

		// get all items
//		ListingItemVector* GetAllItemsList();

		//
		// *********** End of RebuildList functions *************
		//


		//
		// Retrieve active listing items
		//
		virtual void GetListingItems(MarketPlaceId marketplace, 
							time_t enddate, 
							int	   QueryType,
							ListingItemVector* pItemVector,
							bool splitIntoCategtories = true, bool ended = false) = 0;

		// GetChildLeafCategoryIds
		virtual void GetChildLeafCategoryIds( MarketPlaceId marketplaceid,
									  int	CatId,
									  int**  pCategoryIds) = 0;

		// Get Not leaf category Ids
		virtual void GetNotLeafCategoryIds( MarketPlaceId marketplaceid, vector<int> *pvCatIds) = 0;

		// GetNumberOfChildLeafCategories
		virtual int  GetNumberOfChildLeafCategories(MarketPlaceId marketplace, 
											int CatId) = 0;

		// GetMaxCategoryId
		virtual int  GetMaxCategoryId(MarketPlaceId marketplace) = 0;


		virtual void GetItemsByEndDate(MarketPlaceId marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate, bool ended = false) = 0;

		virtual void GetItemsByEndDateSortedByCat(MarketPlaceId marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate) = 0;
										  
		// ************
		//
		//	Statistics
		//
		// ************

		// Update daily statistics
		virtual void UpdateDailyStatisticsOnLeafCategories(
						const char* pQuery,
						int		Marketplace,
						int		TransactionId,
						time_t	Today) = 0;

		virtual void UpdateDailyStatisticsOnNotLeafCategory(
                                MarketPlaceId Marketplace,
                                time_t  Today,
                                int     XactionId,
                                int     CatId,
                                char*   pCatList) = 0;

		virtual void GetDailyStatistics(MarketPlaceId marketplace,
								time_t StartTime, 
								time_t EndTime,
								int	XactionId,
								CategoryId CatId,
								DailyStatsVector* pDailyStats) = 0;

		// get statistics transcation type
		virtual void GetStatisticsTransaction(
						StatisticsEnum StatisticType,
						StatsTransactionVector* pTransVector) = 0;

		virtual void GetTransactionQuery(
				int XactionId,
				StatisticsEnum StatisticType,
				char* pQuery,
				int	  Size) = 0;

		// Get daily bids
		virtual int	GetDailyBids(MarketPlaceId Marketplace,
				time_t	StartTime) = 0;

		virtual void UpdateDailyBids(
				MarketPlaceId Marketplace,
				time_t	Today,
				int	Bids) = 0;


		// ********************
		//
		// Finance
		//
		// ********************

		// summarize the data from ebay_accounts to ebay_finance
		virtual void UpdateDailyFinance(time_t StartTime) = 0;

		// retrieve data from ebay_finance
		virtual void GetDailyFinance(
				time_t StartTime, 
				time_t EndTime,
				DailyFinanceRawVector* pvDailyFinance,
				int *pMaxAction) = 0;

		// ********************
		//
		//	Ads
		//
		// ********************
		virtual void GetPageViews(int Marketplace, 
									 int Pagetype,
									 int* pPageViews) = 0;

		virtual void GetAds(int PageType,
							   time_t Start,
							   time_t End,
							   void** pAdVectorArray) = 0;

		//
		// Historical Data
		//
		virtual void AddHistoricalInfo(int count,
									   int *pIds,
									   char *pPeriodBegins,
									   int periodBeginSize,
									   int *pCategoryIds,
									   int *pItemsSold,
									   float *pDollarsSold,
									   int *pItemsBought,
									   float *pDollarsBought,
									   int *pItemsNotSold,
									   float *pDollarsNotSold) = 0;



		virtual clsAnnouncement *GetAnnouncement(
			int marketplace,
			int announceid, 
			int where,
			int partnerId=0,
			int siteId=0) = 0;

		virtual bool UpdateAnnouncement(
			clsAnnouncement *pAnnounce) = 0;

		virtual bool AddAnnouncement(
			int marketplace,
			int announceid, 
			int where, 
			char *pCode,
			char *pAnnounce,
			int partnerId=0,
			int siteId=0) = 0;

		virtual void GetAllAnnouncementsBySiteAndPartner(
			int marketplace,
			AnnouncementVector *pvAnnouncements,
			int SiteId,
			int PartnerId) = 0;

		virtual void GetAllAnnouncements(
			int marketplace,
			AnnouncementVector *pvAnnouncements) = 0;

		virtual void GetOnePartnerData(int id,
							   time_t forWhen,
							   int *hitcount,
							   int *new_users,
							   int *new_users_total,
							   int *new_users_ever) = 0;

		// Store a partner tally.
		virtual void AddPartnerData(int id,
								   int views,
								   time_t coversDay,
								   int newUserRegistrations,
								   int newUserRegistrationsEver) = 0;

		// Get the list of partner names, with their number as the index into
		// the vector.
		virtual void GetPartnerIds(vector<const char *> *pvPartners) = 0;

		//
		// Junk
		//
		virtual void GetBetaCustomers(vector<int> *pVUsers) = 0;

		//
		// User Surveys
		//
		virtual void GetUserSurveyResponse(MarketPlaceId marketplace,
										   int user_id,
										   int survey_id,
										   int question_id,
										   bool *pGotBoolResponse,
										   bool *pBoolResponse,
										   bool *pGotNumberResponse,
										   float *pNumberResponse,
										   bool *pGotTextResponse,
										   int *pTextResponseLength,
										   char **ppTestResponse) = 0;

		virtual void SetUserSurveyResponse(MarketPlaceId marketplace,
										   int user_id,
										   int survey_id,
										   int question_id,
										   bool response) = 0;

		virtual void SetUserSurveyResponse(MarketPlaceId marketplace,
										   int user_id,
										   int survey_id,
										   int question_id,
										   float response) = 0;

		virtual void SetUserSurveyResponse(MarketPlaceId marketplace,
										   int user_id,
										   int survey_id,
										   int question_id,
										   char *pResponse) = 0;

		// Cobranding
		// nsacco 05/25/99 added siteId
		virtual void LoadPartners(vector<clsPartner *> *pvPartners, int siteId) = 0;

		// kakiyama 06/23/99
		virtual void GetForeignSites(vector<clsSite *> *pvSites) = 0;

		// nsacco 05/25/99
		virtual void LoadSites(vector<clsSite *> *pvSites) = 0;
		virtual void LoadSite(int id, clsSite **pSite) = 0;
		virtual void LoadSite(const char *pName, clsSite **pSite) = 0;
		virtual void GetAllMinimalSites(vector<clsSite *> *pvSites) = 0;
		// petra 08/09/99
		virtual int GetNumberOfSites() = 0;

		// nsacco 05/25/99 added siteId
		virtual void LoadPartnerHeaderAndFooter(int partnerId,
			vector<char *> *pvHeaders, vector<char *> *pvFooters,
			vector<char *> *pvDeletes,
			int siteId) = 0;

		virtual int GetNumberOfHeaderReferences(int unique_id) = 0;

		virtual void ChangeCobrandHeaderText(int unique_id,
									const char *pNewText) = 0;

		virtual int GetCobrandHeaderTextId(int partnerId,
											  int isHeader,
											  PageTypeEnum page_type,
											  PageTypeEnum secondary_page_type,
											  int siteid) = 0;

		virtual int NewCobrandHeaderReference(const char *pDescription,
												 const char *pNewText) = 0;

		virtual int GetCobrandNextHeaderId() = 0;

		virtual void RemoveCobrandHeaderReference(int uniqueId) = 0;

		virtual void SetCobrandHeader(int partnerId, int isHeader,
										PageTypeEnum pageType,
										PageTypeEnum secondaryPageType,
										const char *pDescription,
										const char *pText,
										int site_id) = 0;

		virtual void UpdateCobrandHeaderReference(int partnerId,
													 int isHeader,
													 PageTypeEnum pageType,
													 PageTypeEnum secondaryPageType,
													 int uniqueId,
													 int site_id) = 0;

		virtual void CopyCobrandHeaderReference(int partnerIdOriginal,
												   int isHeaderOriginal,
												   PageTypeEnum pageTypeOriginal,
												   PageTypeEnum secondaryPageTypeOriginal,
												   int siteIdOriginal,
												   int partnerIdNew,
												   int isHeaderNew,
												   PageTypeEnum pageTypeNew,
												   PageTypeEnum secondaryPageTypeNew,
												   int siteIdNew) = 0;

		virtual void ChangeCobrandHeaderDesc(int uniqueId,
												const char *pNewDesc) = 0;

		virtual int GetCobrandNextPartnerId() = 0;
		// nsacco 06/21/99 added siteId and pParsedString
		virtual int CreateCobrandPartner(const char *pName,
											const char *pDescription,
											int siteId,
											const char *pParsedString) = 0;

		virtual void GetSiteHeadersAndFooters(int siteId,
											  vector<clsHeader*>* pvHeaders, 
											  vector<clsFooter*>* pvFooters) = 0;

		virtual void GetSitePartnerHeadersAndFooters(int siteId,
													 int partnerId,
													 vector<clsHeader*>* pvHeaders, 
													 vector<clsFooter*>* pvFooters) = 0;

		virtual int AddLinkButton(clsUser *pUser, RecipLinkEnum pWhichPic, const char *pUrls) = 0;

		//
		// Cobrand ads
		//
		virtual bool AddCobrandAdDesc(clsAd *pAd) = 0;

		virtual int GetCobrandAdDescTextLen(int id) = 0;

		virtual int GetCobrandAdDescTextLen(const char *pName) = 0;

		virtual char *GetCobrandAdDescText(int id) = 0;

		virtual clsAd *GetCobrandAdDesc(int id) = 0;

		virtual clsAd *GetCobrandAdDesc(const char *pName) = 0;

		virtual void LoadAllCobrandAdDescs(AdVector *pvAds) = 0;

		virtual bool UpdateCobrandAdDesc(int id, clsAd *pAd) = 0;

		virtual bool UpdateCobrandAdDesc(const char *pName, clsAd *pAd) = 0;

		virtual bool DeleteCobrandAdDesc(int id) = 0;

		virtual bool DeleteCobrandAdDesc(const char *pName) = 0;

		virtual int GetNextCobrandAdDescId() = 0;

		virtual void AddCobrandAd(clsPartnerAd *pAd) = 0;

		virtual void LoadAllCobrandAds(PartnerAdVector *pvAds) = 0;

		virtual void GetCobrandAdsById(int adId, vector<clsPartnerAd *> *pvAds) = 0;

		virtual void GetCobrandAdsBySite(int siteId, vector<clsPartnerAd *> *pvAds) = 0;

		virtual void GetCobrandAdsBySiteAndPartner(int siteId, 
												   int partnerId,
												   vector<clsPartnerAd *> *pvAds) = 0;

		virtual void GetCobrandAdsByPage(PartnerAdVector *pvAds,
										 int siteId, 
										 int partnerId,
										 PageTypeEnum pageType1,
										 PageTypeEnum pageType2) = 0;

		virtual bool UpdateCobrandAd(clsPartnerAd *pAd) = 0;

		virtual bool DeleteCobrandAd(clsPartnerAd *pAd) = 0;

		//
		// User flags
		//
		virtual int GetUserFlags(int userId) = 0;
		virtual void SetUserFlags(int userId, int choices) = 0;

		// Referral counting.
		virtual void IncrementPartnerCount(int id) = 0;
		virtual void CountPartnerRegistrations(time_t forWhen) = 0;
		virtual void CreateEmptyPartnerCountRecord(int id,
												   const char *pTime) = 0;

		//
		// Check whethe an email is an anonymous email
		//
		virtual bool IsAnonymousEmail(char* pEmail) = 0;

		// Get a list of adult category ids
		virtual void GetAdultCategoryIds(clsMarketPlace *pMarketPlace, 
										vector<CategoryId> *pvAdultCategories) = 0;

		//	*************************
		//	Credit Card Authorization
		//	*************************
		virtual clsAuthorizationQueue *AddAuthorizationEntry(int	id,
															char   *pCCNumber, 
															time_t	CCExpiryDate,
															int		priority,
															float	Amount,
															int		transaction_type,
															char	*accholdername, 
															char	*street_addr, 
															char	*city_addr, 
															char	*stateprov_addr,
															char	*zipcode_addr, 
															char	*country_addr,
															char	*billingaccounttype
															)=0;

		virtual void RemoveAuthorizationEntry(int refID) = 0;
		virtual clsAuthorizationQueue *GetAuthorizationItemWithID(int refID) = 0;
		virtual int GetAuthorizationTableSize() = 0;
		virtual void GetAuthorizationItems(	int					 trans_type,
											int					 priority,
											int					 status,
											bool				 bChangeState,
											int					 newStatus,								
											AuthorizationVector	*pvAuthorizationItems) = 0;

		virtual int  GetAuthorizationStatusForID(int refID) = 0;
		virtual void SetAuthorizationStatusForRefID(int refID, aTransResp aResult) = 0;
		virtual void CommitCCBillingData(int id, int refID) = 0;
		virtual void SetAuthorizationAttemptCount(int id, int count, bool isResetRequired) = 0;
		virtual int  GetAuthorizationAttemptCount(int id) = 0;
		virtual int  GetNextSettlementFileId() = 0;
		virtual int VerifyCCChargeAmount(char *cc, float amount, time_t maxwhen, 
								 int UserId)=0;


		//
		// User pages.
		//
		virtual void CreateUserPage(clsUserPage *pPage) = 0;
		virtual void UpdateUserPage(clsUserPage *pPage) = 0;
		virtual void GetUserPage(clsUserPage *pPage,
								bool withDictionary) = 0;
		virtual void AddViewToUserPage(long userId,
									  int pageNumber) = 0;
		virtual void RemoveUserPage(long userId,
								   int pageNumber) = 0;

        virtual void CreateUserPageCategoryListing(clsUserPage *pPage) = 0;
        virtual void UpdateUserPageCategoryListing(clsUserPage *pPage) = 0;
        virtual void RemoveUserPageCategoryListing(long userId,
                                                   long category) = 0;
        virtual void RemoveAllUserPageCategoryListing(long userId) = 0;

        virtual void GetUserPagesByUser(long userId,
            vector<clsUserPage *> *pvPages) = 0;

        virtual void GetAllUserCategoryPages(vector<clsUserPage *> *pvPages) = 0;

		virtual void CreateNeighbor(long userId,
								   long targetUserId,
								   const char *pComment) = 0;
		virtual void ApproveNeighbor(long userId,
									long targetUserId,
									bool approve) = 0;
		virtual void GetNeighbors(long userId, vector<clsNeighbor *> *pvNeighbors) = 0;

		// Top Seller
		virtual void GetTopSellers(MarketPlaceId marketplace, int level,
			vector<int> *pvIds) = 0;

		//
		// Gift occasions
		//
		virtual void GetActiveGiftOccasions(MarketPlaceId marketplace,
											vector<clsGiftOccasion *> *pvOccasions) = 0;

		virtual bool GetGiftOccasion(MarketPlaceId marketplace,
									 int id,
									 clsGiftOccasion *pOccasion) = 0;

		virtual void AddGiftOccasions(vector<clsGiftOccasion *> *pvOccasions) = 0;

		virtual void AddGiftOccasion(clsGiftOccasion *pOccasion) = 0;

		virtual void DeleteAllGiftOccasions(MarketPlaceId marketplace) = 0;

		virtual void DeleteGiftOccasion(MarketPlaceId marketplace,
										int id) = 0;

		virtual void UpdateGiftOccasion(clsGiftOccasion *pOccasion) = 0;

		virtual int GetNextGiftOccasionId() = 0;

		virtual int GetGiftOccasionFlags(MarketPlaceId marketplace,
										 int id) = 0;

		virtual void SetGiftOccasionFlags(MarketPlaceId marketplace,
										  int id,
										  int flags) = 0;

		// **********
		// Notes
		// **********
		virtual void LoadNotes(unsigned int addressFilter, 
							   unsigned int aboutFilter,
							   unsigned int categoryFilter,
							   clsNoteAddressList *pFrom,
							   clsNoteAddressList *pTo,
							   clsNoteAddressList *pCC,
							   clsNoteAddressList *pAbout,
							   clsNoteList *plNotes) = 0;

		virtual int GetNextNoteSequence() = 0;

		virtual void AddNote(clsNote *pNote) = 0;



		// Trust/Safety things
		virtual void GetNewBigSellers(MarketPlaceId marketplace,
			int maximumAge,
			int minimumSales,
			vector<int> &userVector) = 0;

		virtual void GetAuctionIds(const vector<int>&vSellers,
			vector<int>&vCandidateSellers,
			vector<int>&vCandidateAuctionIds,
			int limit) = 0;

		virtual void GetAuctionsBidOn(const vector<int>&vBidders,
			vector<int>&vAllBidders,
			vector<int>&vAllAuctionsBidOn,
			int limit) = 0;

		virtual void GetSellersOfAuctions(const vector<int>&vAuctions,
			hash_map<int, int, hash<int>, eqint>&mSellersAndAuctions,
			int limit) = 0;

		virtual void GetGenericPairsFromSingleVectorWithLimit(const char *SQL,
			const vector<int> &vInputs,
			vector<int> &vOutput1,
			vector<int> &vOutput2,	
			int limit,
			unsigned char*& pCursor) = 0;

		virtual void GetAuctionsWon(const vector<int>&vBidders,
			vector<int>&vItemNumbers,
			vector<int>&vReturnedBidders,
			int limit) = 0;

		virtual void GetOurAuctionsBidOnByUs(const vector<int>&vItems,
			const vector<int>&vBidderIds,
			vector<int>&vReturnedIds,
			int limit) = 0;

		virtual void GetShillInformationForOurAuctions(
			const vector<int>&vInputs,		// item ids
			hash_map<int, int, hash<int>, eqint>& mapItemsToCounts,
			hash_map<int, time_t, hash<int>, eqint>& mapItemsToDurations,
			hash_map<int, float, hash<int>, eqint>& mapItemsToReserves) = 0;


		virtual void GetBidsFromTheseUsers(
			const vector<int>&vAuctionsByUsBidOnUs, 
			const vector<int>&vBidderIds,
			vector<int>&vBidderIdsOfBids,
			vector<int>&vOurBidItemNumbers,
			vector<int>&vOurBidTypes) = 0;

		virtual void GetAuctionsWithRetractions(
			const vector<int>&vBidderIds,
			vector<int>&vReturnedBidders,
			vector<int>&vReturnedRetractions,
			int limit) = 0;

#ifdef SLURPING_FEEDBACK

		virtual void GetFeedbackScores(const vector<int>&vIds,
			hash_map<int, int, hash<int>, eqint>&mIdsAndScores) = 0;

#endif /* SLURPING_FEEDBACK */

		//
		// Locations.
		//
		virtual bool LocationsIsValidZip(const char *targetZip) = 0;
		virtual bool LocationsIsValidAC(int targetAC) = 0;
		virtual bool LocationsIsValidCity(const char *targetCity) = 0;

		virtual bool LocationsDoesACMatchZip(int ac, const char *zip) = 0;
		virtual bool LocationsDoesACMatchState(int ac, const char *state) = 0;
		virtual bool LocationsDoesZipMatchState(const char *zip, const char *state) = 0;
		virtual bool LocationsDoesACMatchCity(int ac, const char *city) = 0;
		virtual bool LocationsDoesZipMatchCity(const char *zip, const char *city) = 0;
		virtual bool LocationsDoesCityMatchState(const char *city, const char *state) = 0;

		virtual void LocationsGetLLForZip(const char *zip, double *lat, double *lon) = 0;
		virtual void LocationsGetLLForAC(int ac, double *lat, double *lon) = 0;

		//
		// International.
		//
		virtual void GetAllCountries(CountryVector *pvCountries) = 0;
		virtual int  DetermineNumCountries() = 0;

		virtual void GetAllCurrencies(CurrencyVector *pvCurrencies) = 0;
		virtual int  DetermineNumCurrencies() = 0;

		// petra 07/02/99
		virtual void GetLocaleInfo (int localeId, int timezoneId, clsIntlLocale * pLocale) = 0;

		// Exchange rates
		virtual bool GetRatesForCurrency(ExchangeRateVector *pvRates, int fromCurrency) = 0;

		// *******
		// Gallery
		// *******

		virtual bool GetGalleryChangedItem(int sequenceID, clsGalleryChangedItem& item) = 0;
		virtual bool AppendGalleryChangedItem(clsGalleryChangedItem& item) = 0;
		virtual bool SetGalleryChangedItemState(int sequenceID, int newSequenceID, int attempts, int state) = 0;
		virtual bool DeleteGalleryChangedItem(int sequenceID) = 0;
		virtual bool GetGallerySequenceRange(int& minSequence, int& maxSequence) = 0;

		virtual int GetCurrentGallerySequence() = 0;
		virtual int GetNextGallerySequence() = 0;

		virtual int GetCurrentGalleryReadSequence() = 0;
		virtual int GetNextGalleryReadSequence() = 0;

		//  
		//	 i-Escrow
		//
		// 
		// summarize the data from ebay_accounts to ebay_finance
		virtual bool IsABidderForThisItem (int Item, int UserId) = 0;

		//
		// Deadbeats
		//
		virtual void AddDeadbeat(int id,
								 int deadscore,
								 int creditRequests,
								 int warnings,
								 bool isValidDeadbeatScore,
								 bool isValidCreditRequestCount,
								 bool isValidWarningCount) = 0;

		virtual clsDeadbeat *GetDeadbeat(int id) = 0;

		virtual bool GetAllDeadbeats(DeadbeatVector *pvDeadbeats) = 0;

		virtual void SetDeadbeatScore(int id, int score) = 0;

		virtual void SetCreditRequestCount(int id, int count) = 0;

		virtual void SetWarningCount(int id, int count) = 0;

		virtual int GetDeadbeatScore(int bidder) = 0;

		virtual bool IsDeadbeatUser(int bidder) = 0;

		virtual int GetCreditRequestCount(int seller) = 0;

		virtual bool UserHasCreditRequests(int seller) = 0;

		virtual int GetWarningCount(int bidder) = 0;

		virtual bool UserHasWarnings(int bidder) = 0;

		virtual bool InvalidateDeadbeatScore(int id) = 0;

		virtual bool ValidateDeadbeatScore(int id) = 0;

		virtual bool InvalidateCreditRequestCount(int id) = 0;

		virtual bool ValidateCreditRequestCount(int id) = 0;

		virtual bool InvalidateWarningCount(int id) = 0;

		virtual bool ValidateWarningCount(int id) = 0;

		//
		// Deadbeat Items
		//
		virtual void ClearAllDeadbeatItems() = 0;

		virtual bool GetDeadbeatItem(MarketPlaceId marketplace,
									 int id,
									 int seller,
									 int bidder,
									 clsDeadbeatItem *pDeadbeatItem,
									 char *pRowId,
									 time_t delta) = 0;

		virtual bool GetDeadbeatItem(int id,
									 int seller,
									 int bidder,
									 clsDeadbeatItem *pDeadbeatItem,
									 char *pRowId,
									 time_t delta) = 0;

		virtual bool GetDeadbeatItemsByBidderId(MarketPlaceId marketplace,
												int id,
												DeadbeatItemVector *pvItems) = 0;
										
		virtual bool GetDeadbeatItemsByBidderId(int id,
												DeadbeatItemVector *pvItems) = 0;
										
		virtual bool GetDeadbeatItemsBySellerId(MarketPlaceId marketplace,
												int id,
												DeadbeatItemVector *pvItems) = 0;
										
		virtual bool GetDeadbeatItemsBySellerId(int id,
												DeadbeatItemVector *pvItems) = 0;
										
		virtual bool GetAllDeadbeatItems(MarketPlaceId marketplace,
										 DeadbeatItemVector *pvItems) = 0;
										
		virtual bool GetAllDeadbeatItems(DeadbeatItemVector *pItems) = 0;
										
		virtual void AddDeadbeatItem(clsDeadbeatItem *pDeadbeatItem) = 0;

		virtual void DeleteDeadbeatItem(MarketPlaceId marketplace,
										int id,
										int seller,
										int bidder) = 0;

		virtual bool IsDeadbeatItem(MarketPlaceId marketplaceId, 
									int id,
									int seller,
									int bidder) = 0;

		virtual int GetDeadbeatItemCountByBidderId(MarketPlaceId marketplace,
												   int bidder) = 0;
										
		virtual int GetDeadbeatItemCountByBidderId(int bidder) = 0;
										
		virtual int GetDeadbeatItemCountBySellerId(MarketPlaceId marketplace,
												   int seller) = 0;
										
		virtual int GetDeadbeatItemCountBySellerId(int seller) = 0;

		virtual int GetDeadbeatItemsWarnedCountByBidderId(MarketPlaceId marketplace,
														  int bidder) = 0;
										
		virtual int GetDeadbeatItemsWarnedCountByBidderId(int bidder) = 0;
										
		virtual bool GetDeadbeatItemsNotWarned(MarketPlaceId marketplace,
											   DeadbeatItemVector *pvItems) = 0;
										
		virtual bool GetDeadbeatItemsNotWarned(DeadbeatItemVector *pvItems) = 0;

		virtual bool UpdateDeadbeatItem(clsDeadbeatItem *pItem) = 0;

		virtual bool SetDeadbeatItemWarned(int id, int seller, int bidder) = 0;

		// Auto Credits
		virtual bool InsertItemCredit(CreditsVector *pvCredits) = 0;
		virtual void GetCreditsForItem(int item_id, CreditsVector *pvCredits) = 0;
		virtual int  GetNextBatchIdForCredits() = 0;
		virtual void GetAllNewCredits(int batch_id, CreditsVector *pvCredits) = 0;
										

		//
		// Widget Caching
		//
		virtual void StoreItemList(int active, 
			int kind, CategoryId category, char* scope, int country, vector<unsigned long> *pStore) = 0;
		virtual void RetrieveItemList(int active, 
			int kind, CategoryId category, int country, vector<unsigned long> *pStore) = 0;
		virtual void GetCachedCategoryIds(list<int> *pStore) = 0;

		//
		// Regions
		//
		virtual void GetAllRegionInfo(vector<clsRegion*>* pvRegions) = 0;

		virtual void GetAllRegionsAndZips(clsRegions* pRegions) = 0;

		//
		// Categories
		//
		virtual bool		MaskCategory(MarketPlaceId marketplace,
										 CategoryId categoryId,
										 bool on) = 0;

		virtual bool		FlagCategory(MarketPlaceId marketplace,
										 CategoryId categoryId,
										 bool on) = 0;

#if 0
		virtual void		GetMaskedCategories(MarketPlaceId marketplace,
												CategoryVector *pvCategories) = 0;

		virtual void		GetFlaggedCategories(MarketPlaceId marketplace,
												 CategoryVector *pvCategories) = 0;
#endif

		//
		// Category filters
		//
		virtual bool			AddCategoryFilter(clsCategoryFilter *pFilter) = 0;

		virtual void			DeleteCategoryFilter(CategoryId categoryId,
													 FilterId filterId) = 0;

		virtual bool			UpdateCategoryFilter(CategoryId categoryId,
													 FilterId filterId,
													 FilterId newFilterId) = 0;

		virtual clsCategoryFilter *
								GetCategoryFilter(CategoryId categoryId,
												  FilterId filterId) = 0;

		virtual bool			GetCategoryFiltersByCategoryId(CategoryId categoryId,
															   vector<FilterId> *pvFilterIds) = 0;

		virtual unsigned int	GetCategoryFilterCountByCategoryId(CategoryId categoryId) = 0;

		virtual bool			GetCategoryFiltersByFilterId(FilterId filterId,
															 vector<CategoryId> *pvCategoryIds) = 0;

		virtual unsigned int	GetCategoryFilterCountByFilterId(FilterId filterId) = 0;

		virtual void			GetAllCategoryFilters(CategoryFilterVector *pvCategoryFilters) = 0;


		//
		// Filters
		//
		virtual bool		AddFilter(clsFilter *pFilter) = 0;

		virtual void		DeleteFilter(FilterId id) = 0;

		virtual void		DeleteFilter(const char *pName) = 0;

		virtual bool		UpdateFilter(FilterId id,
										 clsFilter *pFilter) = 0;

		virtual bool		UpdateFilter(const char *pName,
										 clsFilter *pFilter) = 0;

		virtual clsFilter *	GetFilter(FilterId id) = 0;

		virtual clsFilter *	GetFilter(const char *pName) = 0;

		virtual void		GetFilters(CategoryId categoryId,
									   FilterVector *pvFilters) = 0;

		virtual void		GetAllFilters(FilterVector *pvFilters) = 0;

		virtual FilterId	GetNextFilterId() = 0;

		virtual FilterId	GetMaxFilterId() = 0;


		//
		// Currency exchange rates
		//
		virtual bool InsertExchangeRateRecord(time_t indate, 
							int fromcurrency, double rate) = 0;

		virtual int DetermineNumExchangeRates() = 0;


		//
		// Category messages
		//
		virtual bool			AddCategoryMessage(clsCategoryMessage *pCategoryMessage) = 0;

		virtual void			DeleteCategoryMessage(CategoryId categoryId,
													  MessageId messageId) = 0;

		virtual bool			UpdateCategoryMessage(CategoryId categoryId,
													  MessageId messageId,
													  clsCategoryMessage *pCategoryMessage) = 0;
		virtual clsCategoryMessage *
								GetCategoryMessage(CategoryId categoryId,
												   MessageId messageId) = 0;

		virtual bool			GetCategoryMessagesByCategoryId(CategoryId categoryId,
																CategoryMessageVector *pvCategoryMessages) = 0;

		virtual unsigned int	GetCategoryMessageCountByCategoryId(CategoryId categoryId) = 0;

		virtual bool			GetCategoryMessagesByMessageId(MessageId messageId,
															   CategoryMessageVector *pvCategoryMessages) = 0;

		virtual unsigned int	GetCategoryMessageCountByMessageId(MessageId messageId) = 0;

		virtual bool			GetAllCategoryMessages(CategoryMessageVector *pvCategoryMessages) = 0;

		//
		// Filter messages
		//
		virtual bool			AddMinFilterMessage(clsMinFilterMessage *pMinFilterMessage) = 0;

		virtual void			DeleteMinFilterMessage(FilterId filterId,
													   MessageId messageId,
													   MessageType messageType) = 0;

		virtual bool			UpdateMinFilterMessage(FilterId filterId,
													   MessageId messageId,
													   MessageType messageType,
													   clsMinFilterMessage *pMinFilterMessage) = 0;

		virtual clsMinFilterMessage *
								GetMinFilterMessage(FilterId filterId,
													MessageId messageId,
													MessageType messageType) = 0;

		virtual bool			GetMinFilterMessagesByFilterId(FilterId filterId,
															   MinFilterMessageVector *pvMinFilterMessages) = 0;

		virtual unsigned int	GetMinFilterMessageCountByFilterId(FilterId filterId) = 0;

		virtual bool			GetMinFilterMessagesByMessageId(MessageId messageId,
																MinFilterMessageVector *pvMinFilterMessages) = 0;

		virtual unsigned int	GetMinFilterMessageCountByMessageId(MessageId messageId) = 0;


		//
		// Messages
		//
		virtual bool		AddMessage(clsMessage *pMessage) = 0;

		virtual void		DeleteMessage(MessageId id) = 0;

		virtual void		DeleteMessage(const char *pName) = 0;

		virtual bool		UpdateMessage(MessageId id,
										  clsMessage *pMessage) = 0;

		virtual bool		UpdateMessage(const char *pName,
										  clsMessage *pMessage) = 0;

		virtual clsMessage *GetMessage(MessageId id) = 0;

		virtual clsMessage *GetMessage(const char *pName) = 0;

		virtual clsMessage *GetMessage(CategoryId categoryId,
									   MessageType messageType) = 0;

		virtual bool		GetMessages(MessageQueryType queryType,
										MessageVector *pvMessages,
										unsigned int value = 0) = 0;

		virtual MessageId	GetNextMessageId() = 0;

		virtual MessageId	GetMaxMessageId() = 0;


		//
		// Blocked/flagged items
		//
		virtual void			AddBlockedItem(clsItem *pItem) = 0;

		virtual void			DeleteBlockedItem(MarketPlaceId marketplace,
											  ItemId id) = 0;

		virtual void			DeleteBlockedItem(ItemId id) = 0;

		virtual void			UpdateBlockedItem(clsItem *pItem) = 0;

		virtual bool			GetBlockedItem(MarketPlaceId marketplace,
											   ItemId id,
											   clsItem *pItem) = 0;

		virtual bool			GetBlockedItem(ItemId id, clsItem *pItem) = 0;
			
		virtual unsigned int	GetBlockedItemCountById(MarketPlaceId marketplace,
														ItemId id) = 0;

		virtual unsigned int	GetBlockedItemCountById(ItemId id) = 0;

		virtual unsigned int	GetBlockedItemCount(MarketPlaceId marketplace) = 0;

		virtual unsigned int	GetBlockedItemCount() = 0;

		virtual void			GetUnconfirmedUsers(MarketPlaceId m, 
									vector<int> &vIds, int age) = 0;

		//inna = lydia's methods
		virtual bool TempAcctsByDateExists() = 0;
		virtual bool TempAcctsNotSplitIdExists() = 0;

		virtual void DropAcctsByDate() = 0;
		virtual void DropAcctsNotSplitId() = 0;

		virtual void CTASAcctsNotSplitId() = 0;
		virtual void CTASAccountsByDate(char *cStartTime, char *cEndTime) = 0; 

		virtual void AddSpecialItem(int item_id, 
								    int kind, 
									time_t endDate) = 0;
		virtual void DeleteSpecialItem(int item_id) = 0;
		virtual void FlushSpecialItem() = 0;

		/********
		Invoice and BalanceAging State
		*********/
		virtual bool CreateInvAndBalAgingStateInfo(clsInvAndBalAgingState *pInvAndBalAgingState) =0;
		virtual bool GetInvAndBalAgingStateInfo(clsInvAndBalAgingState *pInvAndBalAgingState) =0;
		virtual bool UpdateInvAndBalAgingStateInfo(clsInvAndBalAgingState *pInvAndBalAgingState) =0;
		virtual void MakeInstanceComplete(clsInvAndBalAgingState *pInvAndBalAgingState)  =0;
		virtual bool IsRangeOverlap(clsInvAndBalAgingState *pInvAndBalAgingState)	=0;
		virtual	void CleanUpOverlappedRecord(clsInvAndBalAgingState *pInvAndBalAgingState)	=0;

protected:		
		char				*mpHost;
		clsListingItemList	*mpListingItemList;

};


//bool gIIS_Server_is_down_flag=false; //new Outage-code

#endif /* CLSDATABASE_INCLUDED */
