/*	$Id: clsDatabaseOracle.h,v 1.21.2.16.2.4 1999/08/10 17:21:49 phofer Exp $	*/
//
//	File:	clsDatabaseOracle.h
//
//	Class:	clsDatabaseOracle.h
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 03/29/97 michael	- Created
//				- 06/09/97 tini     - changed user related database accesses
//					to work with 2 user tables
//				- 06/13/97 tini     - removed item-xref methods.
//									- added UpdateItem and UpdateItemDesc
//				- 06/20/97 tini		- added functions to handle renaming users.
//				- 06/23/97 tini		- added mInTransaction variable for complex transactions
//				- 07/30/97 wen		- added function to retrieve item count
//									  for a marketplace; Added functions
//									  to adjust and retrieve bid count for
//									  a marketplace.
//				- 09/02/97 wen		- added functions for listing items
//				- 09/20/97 chad		- added functions for voiding feedback values
//				- 10/13/97 poon		- added GetItemIdsVector
//				- 10/25/97 michael	- added AddHistoricalInfo
//				- 10/31/97 michael	- added SetUserDateAndHost
//				- 11/19/97 charles	- added GetItemsCountByCategory()
//				- 05/12/98 sam		- added CC Authorization routines
//				- 08/25/98 mila		- changed FillFeedbackDetailTableName and
//									  FillFeedbackDetailTableNames to allocate
//									  memory for table name locally; fixed
//									  spelling of 'pagination'
//				- 09/08/98 wen		- added GetHighTicketItems()
//				- 09/25/98 mila		- added functions and cursors to handle
//									  deadbeat items and users
//				- 10/02/98 mila		- added GetDeadbeatItemsByBidderId function
//									  and its cursor
//				- 10/13/98 mila		- added GetAllDeadbeatItems function
//									  and its cursor
//				- 12/01/98 mila		- added GetDeadbeatItemsBySellerId, AddDeadbeat,
//									  GetDeadbeat, et al, and their cursors
//				- 12/08/98 mila		- added GetAllDeadbeats
//				- 12/15/98 mila		- added GetDeadbeatItemCountBySellerId and
//									  GetDeadbeatItemCountByBidderId
//				- 04/12/99 mila		- added lots of new methods and cursors for Legal
//									  Buddy project (most named *Filter* or *Message*)
//				- 05/11/99 jnace	- added region ID parameter to GetCategoryCountsFromOpenItems
//				- 05/24/99 jennifer - added Admin Gallery Tool functions
//				- 07/22/99 sonya	- added 4 functions relate to invoice and balace aging state
//				- 05/25/99 nsacco	- added siteId to LoadPartnerHeaderAndFooter and
//									  new LoadSites
//				- 06/03/99 petra	- added siteId to categories queries; cleaned up unused
//									category functions
//				- 06/21/99 nsacco	- added siteId and pParsedString to CreateCobrandPartner
//				- 07/02/99 petra	- added GetLocaleInfo
//				- 07/06/99 nsacco	- added siteid and co_partnerid
//

#ifndef CLSDATABASEORACLE_INCLUDED
#define CLSDATABASEORACLE_INCLUDED 1
#include "clsDatabase.h"
#include "clsUsers.h"
#include <list.h>
#include <hash_map.h>
#include "clsDeadbeat.h"
#include "clsDeadbeatItem.h"
#include "clsExchangeRates.h"
#include "clsCategoryFilter.h"
#include "clsCategoryMessage.h"
#include "clsFilter.h"
#include "clsFilterMessage.h"
#include "clsMessage.h"
#include "clsMessages.h"

// 
// A Macro to make Check Better
//
#define Check(rc) CheckOracleResult(__FILE__, __LINE__, rc)

// 
// Initial size of the seller list buffer. 16K is big enough for
// 2048 items.
// Feedback list buffer size needs to be tuned...
//
#define	INITIAL_SELLER_LIST_BUFFER_SIZE		16384
#define	INITIAL_BIDDER_LIST_BUFFER_SIZE		16384
#define FEEDBACK_LIST_ROWID_SIZE			18
#define INITIAL_FEEDBACK_LIST_ROWID_COUNT	10

// Lena - to determine if the new users are to be created with -1 table indicator
// or their proper one
#define SPLIT_ACCOUNTS_STARTED true
typedef enum
{
	GetAccountDetailEnum		= 1,
	AddAccountDetailEnum		= 2,
	AddRawAccountDetailEnum     = 3,
	GetAllPaymentsSince			= 4
} CallingTypeEnum;


// forward
void TM_STRUCTToORACLE_DATE(struct tm *pLocalTime,
					        char *pDate);

void TimeToORACLE_DATE(time_t theTime,
					   char *pDate);

void ORACLE_DATEToTime(char *pDate,
					   time_t *pTheTime,
						bool honorDST = true);

void ORACLE_DATEToTM_STRUCT(char *pDate,
					        struct tm *pTheTime);

//
//	This nice little class describes a seller's auction
//	#'s and when the auctions end. It's private to 
//	clsDatabaseOracle, since it's used for an internal
//	optimization.
//
/*
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

//
//	This nice little class describes a bidder's bid
//	item #'s and when the auctions end. It's private to 
//	clsDatabaseOracle, since it's used for an internal
//	optimization.
//
class clsBidderItem : public clsBidderOrSellerItem
{
public:
	clsBidderItem() {}
	clsBidderItem(int id, time_t saleEnd) : clsBidderOrSellerItem(id, saleEnd) {}
};

typedef list<clsBidderItem> BidderItemList;
*/
class clsFeedbackRowItem
{
	public:
		clsFeedbackRowItem()
		{	
			// set array to null
			return;	
		};

		clsFeedbackRowItem(char *pRowId)
		{
			memset(mRowId, 0x00, sizeof(mRowId));
			strncpy(mRowId, pRowId, 18);
		};

		~clsFeedbackRowItem()
		{
			// don't need to delete array.
		};

		char	mRowId[19];
};

typedef vector<clsFeedbackRowItem *>	FeedbackRowItemVector;

class clsDatabaseOracle : public clsDatabase
{
	public:
		friend class clsDBTransaction;
		friend bool IIS_Server_is_down(void); //new outage code

		// Constructor, Destructor
		clsDatabaseOracle(char *pHost);
		virtual ~clsDatabaseOracle();

		//
		// Begin, End
		//
		void Begin();
		void End();
		void Cancel();
		bool InTransaction();

		//
		// CancelQuery
		//
		void CancelQuery();

		void CancelPendingTransactions();

		//
		// Various modes for transactions
		//
		void SetReadCommitted();
		void SetSerializable();
		void SetReadOnly();


		//
		// Cehck whether the rowid is in the right format
		//
		bool IsValidRowIdFormat(const char *pRowId);

		//
		// ClearAllItems
		//
		void ClearAllItems();


		//
		//
		// Get an item
		//
		bool GetItem(int id,
					 clsItem *pItem,
					 char *pRowNo = NULL,
					 time_t delta = 0,
					 bool ended = false,
					 bool blocked = false);
		bool GetItem(int marketplace,
							 int id,
							 clsItem *pItem,
							 char *pRowNo = NULL,
							 time_t delta = 0,
							 bool ended = false,
							 bool blocked = false);
		bool GetItemDescription(int marketplace,
								int id,
								clsItem *pItem,
								bool blocked = false);
		bool GetItemWithDescription(int marketplace,
									int id,
									clsItem *pItem,
									char *pRowNo = NULL,
									time_t delta = 0,
									bool ended = false,
									bool blocked = false);

		//
		// Getting various lists of items
		//
		void GetItemsListedByUser(MarketPlaceId marketplace,
								int id,
								int daysSince,
								ItemList *pItems,
								bool getMoreStuff = false,
								ItemListSortEnum SortCode = SortItemsByUnknown);

		void GetItemsListedByUserActiveOrEnded(
								MarketPlaceId marketplace,
								int id,
								int	daysSince,
								SellerItemList *lItems,
								ItemList *pItems,
								bool getMoreStuff /* = false */,
								ItemListSortEnum SortCode /* = SortItemsByUnknown */,
								bool ended);

		int GetItemsListedByUserCount(MarketPlaceId marketplace,
											  int id);

		void GetItemsBidByUser(MarketPlaceId marketplace,
								int id,
								int daysSince,
								ItemList *pItems,
								bool getMoreStuff = false,
								ItemListSortEnum SortCode = SortItemsByUnknown,
								bool withPrivate = false); // Added by Charles

		void GetItemsBidByUserActiveOrEnded(
								MarketPlaceId marketplace,
								int id,
								int	daysSince,
								BidderItemList *lItems,
								ItemList *pItems,
								bool getMoreStuff /* = false */,
								ItemListSortEnum SortCode /* = SortItemsByUnknown */,
								bool withPrivate,
								bool ended);

		int GetItemsBidByUserCount(MarketPlaceId marketplace,
								   int id);


		void GetItemsHighBidByUser(MarketPlaceId marketplace,
								int id,
								bool completed,
								ItemList *pItems,
								ItemListSortEnum SortCode = SortItemsByUnknown, bool ended = false);

		void GetItemsVector(MarketPlaceId marketplace,
							time_t enddate,
							int hotcount,
							int QueryCode,
							ItemVector *pvItems,
							time_t endlimitdate = 0,
							ItemListSortEnum SortCode = SortItemsByUnknown,
							bool ended = false);

		void GetItemsNotNoticed(MarketPlaceId marketplace,
								vector<int> *pvItems,
								time_t fromdate,
								time_t todate
								);

		void GetItemsNotNoticedRowId(MarketPlaceId marketplace,
								vector<clsItemIdRowId*> *pvRows,
								time_t fromdate,
								time_t todate);

		void GetItemsNotBilled(MarketPlaceId marketplace,
							   vector<int> *pvItems);
//		void GetItemIdsToEnd(vector<int> *pvItems, time_t startDate, time_t endDate);
		long GetNoticeTime(clsItem *pItem);

		long GetBillTime(clsItem *pItem);
		void GetItemsModifiedAfter(MarketPlaceId marketplace,
							 		   time_t modDate,
									   ItemVector *pvItems,
										ItemListSortEnum SortCode = SortItemsByUnknown);
		// Lena merge!
		void GetManyItemDescriptions(MarketPlaceId marketplace,
									 ItemVector::iterator iStart,
									 ItemVector::iterator iEnd, bool ended = false);
		void GetItemsModifiedAfterMinimal(MarketPlaceId marketplace,
										  time_t modDateStart, time_t modDateEnd,
										  ItemVector *pvItems, bool started = 0);
	
		// Retrieve item ids in a vector.
		void GetItemIdsVector(clsMarketPlace *pMarketPlace, 
										vector<int> *pvItemIds,
										time_t endDate,
										GetItemIdsEnum queryCode  = eGetActive, 
										int catId = 0,
										time_t endLimitDate = 0,
										float price = 0,
										bool OKToUseCache=true, bool ended = false);

		// Retrieve Active High Price Items
		// 
		void GetHighTicketItems(vector<int> *pvItemIds, 
								time_t endDate, 
								float Price);

		//
		// Indicates that an item's end-of-auction notice had
		// been sent out
		//
		void AddItemNoticed(clsItem *pItem);
		
		//
		// Indicates that an items billing notice has been sent
		//	out
		//
		void AddItemBilled(clsItem *pItem);

		//  
		// store GMS number for dutch auctions
		// to help reports
		//
		void SetDBDutchGMS(clsItem *pItem, float price);
		
		//
		// Get the next availible item id
		//
		int GetNextItemId();

		//
		// Add item description
		//

		void AddItemDesc(clsItem *pItem,
						 bool blocked = false);

		//
		// Add an item also calls AddItemDesc
		//
		//
		void AddItem(clsItem *pItem,
					 bool blocked = false);

		// Update Item also calls UpdateItemDesc
		void UpdateItemDesc(clsItem *pItem, bool blocked = false);
		void UpdateItem(clsItem *pItem, bool blocked = false);
		void UpdateItemStatus(clsItem *pItem);

		//
		// Functions to get at archived data
		//
		// use for archiving descriptions
		void ClearItemsToBad();
		void SetItemsToBad(int id, int btype); 
		void GetItemsToArchive(MarketPlaceId marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate);
		void GetItemsToEnded(MarketPlaceId marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate);
		void GetItemsFromTemp(vector<int> *vItems);
		int GetItemDescArc(int id, unsigned char **description);
		void UpdateItemDescArc(clsItem *pItem);		
		void AddItemDescArc(clsItem *pItem);
		void AddItemDescEnded(clsItem *pItem);

		// used for item manipulations
		void GetItemsByEndDateArc(MarketPlaceId marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate);
		bool GetItemDescArc(int id,
								clsItem *pItem);
		bool GetItemWithDescArc(int marketplace,
									int id,
									clsItem *pItem);

		/* get abbreviated item from archive for admin batches */
		bool GetItemArc(int id,
					 clsItem *pItem);
				
		/* get all item detail w/o desc from archive for summary reports */
		bool GetItemArc(int marketplace,
							 int id,
							 clsItem *pItem);

		void GetBidsArc(MarketPlaceId marketplace,
				     int item_id,
					 BidVector *pBids);

		//
		// Update a item Password
		//
		void UpdateItemPassword(clsItem *pItem);
		
		//
		// Delete an item
		//
		void DeleteItem(int marketplace,
						int id,
						bool ended = false,
						bool blocked = false);

		//
		// Set a new Title
		//
		void SetNewTitle(clsItem *pItem);

		//
		// Set a new high bidder
		//
		void SetNewHighBidder(clsItem *pItem);
		void SetNewHighBidderAndBidCount(clsItem *pItem);

		//
		// Set a new bidcount
		//
		void SetNewBidCount(clsItem *pItem);

		//
		// Set a new description
		//
		void SetNewDescription(clsItem *pItem);

		//
		// Sets a new dutch high bidder
		//
		void SetDutchHighBidder(clsItem *pItem, clsBid *pBid);

		//
		// Deletes all dutch high bidders associated with this item
		//
		void DeleteDutchHighBidder(clsItem *pItem);
		
		//
		// Set a new ending time
		//
		void SetNewEndTime(clsItem *pItem);

		//
		// Set a new featured
		//
		void SetNewFeatured(clsItem *pItem);

		//
		// Set a new super featured
		//
		void SetNewSuperFeatured(clsItem *pItem);

		//
		// Set a new category
		//
		void SetNewCategory(clsItem *pItem);


		//
		// adjusts visitcount by delta
		//
		void SetItemVisitCount(clsItem *pItem, int delta);
		
		//
		// Adjust the item count for a marketplace
		// 
		void AdjustMarketPlaceItemCount(int marketPlaceId,
										int delta);

		//
		// Retrieve the item count since inception
		//
		int GetItemCountSinceInception(MarketPlaceId marketplaceId);
		int GetDailyItemCount(MarketPlaceId marketplaceId);


		//
		// Get item count previous the end date
		//
		int GetItemsCountOn(MarketPlaceId marketplace, 
							time_t enddate);

		//
		// SPECIAL Get many items for a credit batch
		//
		void GetManyItemsForCreditBatch(MarketPlaceId marketplace,
												list<unsigned int> *pItemIdList,
												ItemList *pItems);
		void clsDatabaseOracle::GetManyEndedItemsForCreditBatch(
								MarketPlaceId marketplace,
								list<unsigned int> *pItemIdList,
								ItemList *pItems);
		void GetManyArcItemsForCreditBatch(MarketPlaceId marketplace,
												list<unsigned int> *pItemIdList,
												ItemList *pItems);
		void EndItem(clsItem *pItem);
//		void ArchiveItem(clsItem *pItem, char *month, char *year);
		void ArchiveItem(clsItem *pItem);
		void GetMonthYear(time_t saleStart, char *month, char *year);
		//inna 
		void RemoveItem(clsItem *pItem);
		void RemoveItemFromEnded(clsItem *pItem);
//		void DeleteFromItemInfo(clsItem *pItem);

		void GetManyItemsForAuctionEnd(
								MarketPlaceId marketplace,
								list<unsigned int> *pItemIdList,
								vector<clsItemPtr> *pItems,
								bool bGetCompleteItem = false);

		void GetManyEndedItemsForAuctionEnd(
								MarketPlaceId marketplace,
								list<unsigned int> *pItemIdList,
								vector<clsItemPtr> *pItems);
		void AddItemDescArcByMonth(clsItem *pItem);
		// Gallery
		bool SetItemGalleryInfo(int itemID, clsItemGalleryInfo& info);
		bool GetItemGalleryInfo(int itemID, clsItemGalleryInfo& info);
		virtual void GetAllActiveItems(ListingItemVector * pvItems);
		void GetAllActiveItemsAllTable(ListingItemVector * pvItems);

		// **********
		// Bids 
		// **********

		//
		// Get all the bids on an item
		//
		void GetBids(MarketPlaceId marketplace,
				     int item_id,
					 BidVector *pBids, bool ended = false);


		//
		// Get a user's highest bid on an item
		//
		clsBid *GetHighestBidForUser(MarketPlaceId marketplace,
									 int item_id,
									 int user_id, bool ended = false);

		//get all bids count for an item
		int GetItemBids(MarketPlaceId marketplace,
				     int item_id, bool ended = false);
		//
		// Get the highest bids on an item
		//
		void GetHighestBidsForItem(bool lock,
								   MarketPlaceId marketplace,
								   int item_id,
								   clsBid **pHighestBid,
								   clsBid **pNextHighestBid, bool ended = false);

		void GetHighestBidsForItem(bool lock,
								   MarketPlaceId marketplace,
								   int item_id,
								   int item_qty,
								   BidVector *pvHighBids, bool ended = false);

		void GetBidsForItemSorted(MarketPlaceId marketplace,
								  int item_id,
								  BidVector *pvHighBids, bool ended = false);

		
		void GetDutchHighBidders(MarketPlaceId marketplace,
									int itemId,
									BidVector *pvBids);
				
		// 
		// Add a bid
		//
		void AddBid(MarketPlaceId marketplace,
					int item,
					clsBid *pBid,
					bool blocked = false);

		//
		// Retract all of a user's bids on an item.
		// (If blocked is true, bids are moved to the
		// blocked bids table instead of to the archived
		// bids table.  -- mila 5/1/99)
		//
		void RetractBids(MarketPlaceId marketplace,
						 int item,
						 int user,
						 BidActionEnum type);

		//
		// Delete a user's bid on an item
		//
		void DeleteBid(int item, int user);

		//
		// Delete all bids on an item
		//
		void DeleteBids(int marketplace, int item);

		//
		// Get the bidders on an item
		//
		void GetBiddersForItem(MarketPlaceId marketplace,
							   int id,
							   list<int> *plUsers, bool ended = false);


		//
		// Adjust the bid count for a marketplace
		// 
		void AdjustMarketPlaceBidCount(int marketPlaceId,
									   int delta);

		//
		// Retrieve bid count since inception
		//
		int GetBidCountSinceInception(MarketPlaceId marketplaceId);

		// **********
		// Users
		// **********

		// Get a user by id. Ids are unique
		// across marketplaces, so we just need the id
		clsUser *GetUserById(int id);
		void GetUserAndFeedbackById(int id,
									clsUser **ppUserId,
									clsFeedback **ppFeedback);

		// Get full blown user and info by Id
		clsUser *GetUserAndInfoById(int id);
	
		// Gets user information by id
		// updates clsUser
		clsUser *GetUserInfo(clsUser *pUser);

		void UpdateUserCreation(clsUser *pUser);

		// Get a user by userid. Userid are ONLY
		// unique in a marketplace, so we need the
		// marketplace id
		clsUser *GetUserByUserId(int id, char *pUserId);
		void GetUserAndFeedbackByUserId(int marketplace,
										char *pUserId,
										clsUser **ppUserId,
										clsFeedback **ppFeedback);
		
		// Get a user by email address
		clsUser *GetUserByEmail(int id, char *pUserId);
		void GetUserAndFeedbackByEmail(int marketplace,
									   char *pEmail,
									   clsUser **ppUserId,
									   clsFeedback **ppFeedback);

		//
		// ChangeUserId
		//
		bool ChangeUserId(int marketplace,
						  int id,
						  char *pNewUserId);

/*
		//
		// UserIdChangedInInterval
		//
		bool UserIdChangedInInterval(int marketplace,
									 int id,
									 int interval);

		//
		// EMailChangedInInterval
		//
		bool EMailChangedInInterval(int marketplace,
									int id,
									int interval);

*/
		//
		// AddUserAlias
		//
		void AddUserAlias(int marketplace,
						  int id,
						  char *pAlias,
						  char *pHost,
						  time_t changeTime);

		//
		// AddEmailAlias
		//
		void AddEmailAlias(int marketplace,
						  int id,
						  char *pAlias,
						  char *pHost,
						  time_t changeTime);

		//
		// GetAliasHistory
		//
		void GetAliasHistory(int marketplace,
							 int id,
							 UserAliasHistoryVector *pVAlias);

		//
		//GetIdByAliasHistory
		//
		void GetIdByAlias(int marketplace,
						  char *alias,
					      UserIdAliasHistoryVector *pvUsers);

		// admin info per user in admin table
		bool GetUserAdminInfo(clsUser *pUser, int code);
		void SetUserAdminInfo(clsUser *pUser, bool doThey, int code);

		// Get all the ids of users with a minimum feedback level
		void GetUserIdsByFeedback(int minVal,
								  vector<int> *pvUsers);

		void GetUserIdsAndFeedbackByFeedback(
							      int minVal,
								  vector<clsUserPtr> *pvUsers);

	     		
		// inna get all unsplit feedback records in user range
		void GetUserIdsUnsplit(int start_id, int end_id,
								  vector<int> *pvUsers);

		// Next availible id for a user. Sorry about the
		// name!
		int GetNextUserId();

		// Adding a user
		void AddUser(clsUser *pUser);

		// And their info
		void AddUserInfo(clsUser *pUser);

		// Updating a user
		void UpdateUser(clsUser *pUser);

		// Updating user info
		void UpdateUserInfo(clsUser *pUser);

		// checks if a user already has an info record
		bool HasUserInfo(clsUser *pUser);

		// Delete user and user info
		void DeleteUserLists(clsUser *pUser);
		void DeleteUserInfo(clsUser *pUser);
		void DeleteUser(clsUser *pUser);

		// RenameUser
		void RenameUser(MarketPlaceId marketPlace,
						char *pOldUserId,
						char *pNewUserId);

		// adds a record to ebay_renamed_users table
		void AddRenamedUser(char *pOldUserId,
							char *pNewUserId);

		// deletes a record to ebay_renamed_users table
		void DeleteRenamedUser(char *pOldUserId);


		// renames renamed user
		void RenameRenamedUser(char *pOldUserId,
								char *pNewUserId);


		// Adding many users
		// nsacco 07/06/99 added siteid and co_partnerid
		void AddManyUsers(
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
						);

		void AddManyUsersInfo(
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
						int *pCounts,
						char *pCredit_cards,
						char *pGood_credits,
						char *pGenders,
						int genderLen,
						int *pInterests_1,
						int *pInterests_2,
						int *pInterests_3,
						int *pInterests_4);

		void RenameIdInUserAssets(int fromid,
						int toid);

		int IsUserAccountXref(int id);
		void DeleteUserAccountXref(int id);

		
		bool IsUserSpecial(char *pUserId);

		bool IsUserRenamePending(clsUser *pUser, char *pNewUserId);

		void GetUsersBySubstring(MarketPlaceId marketplace,
								 UserSearchTypeEnum how,
								 char *pString,
								 vector<clsUser *> *pvUsers);

		bool GetUserRenamePendingCode(clsUser *pUser, 
										char *pNewId, char *pSalt);



		// Another kludge. Get the user's AW credit-card-on-file and
		// good_credit status.
		void AddAWCreditCardOnFile(char *pUserId);
		void AddAWGoodCredit(char *pUserId);
		void GetAWCreditStatus(char *pUserId, bool *pCreditCardOnFile,
							   bool *pGoodCredit);
		void SetUserRenamePending(clsUser *pUser, 
										char *pNewId, 
										char *pPass,
										char *pSalt);

		void DeleteUserRenamePending(int Id);
		void ExpireUserRenamePending(time_t endTime);


		// Migration. Set Date and host
		void SetUserDateAndHost(int id,
							    time_t whem,
							    char *pHost);

		// Add to user's request for email counts
		void AddReqEmailCount(int id,
								int delta);

		void ResetReqEmailCount(int id);

		// This returns true if we are allowed to send (id) information
		// about another user, and false if we aren't -- it also updates
		// our recors -- incrementing their 'requested count' by 1, and
		// storing their host (and possibly alerting us if someone is
		// requesting too much).
		int CanReceiveInfo(int id, const char *pHost);

		// Just reset it to 0.
		void ResetCanReceiveInfo(int id);

		//
		// Tweaked method to get users for Credit, Accounting batch
		//
		void GetManyUsersForCreditBatch(MarketPlaceId marketplace,
										list<unsigned int> *pUserIdList,
										UserList *pUsers);

		//
		// A more general one, used by the user cache
		//
		void GetManyUsers(MarketPlaceId marketplace,
						  list<UserId> *pUserIdList,
						  list<UserId> *pMissingUserIdList,
						  UserList *pUsers);

		//
		// GetActiveUser
		//
		void GetActiveUsers(MarketPlaceId marketplace,
							 vector<int> *pvIds);

		void GetAllUsers(MarketPlaceId marketplace,
							 vector<unsigned int> *pvIds, int minId = 0, int Maxid = 0);

		// **********
		// User Attributes
		// **********

		void GetUserAttribute(int user_id,
							  int attribute_id,
							  bool *pGotBoolResponse,
							  bool *pBoolResponse,
							  bool *pGotNumberResponse,
							  float *pNumberResponse,
							  bool *pGotTextResponse,
							  char **ppTextResponse);

		void SetUserAttributeValue(int user_id,
								   int attribute_id,
								   bool value);

		void SetUserAttributeValue(int user_id,
								   int attribute_id,
								   float value);

		void SetUserAttributeValue(int user_id,
								   int attribute_id,
								   int value);

		void SetUserAttributeValue(int user_id,
								   int attribute_id,
								   char *pValue);


		// **********
		// Demographic
		// **********
		void GetUserCodeVector(UserCodeVector *pvUserCodeVector);

		// **********
		// Feedback
		// **********

		//
		// Private routine to add summary feedback
		//
		void AddFeedback(int id, int score,
						 int flags, bool split=false);

		//
		// Get a user's feedback summary info
		//
		clsFeedback *GetFeedback(int id);

		// recompute score server version
		int GetRecomputedFeedbackScore(int id, bool split=false);
		//
		// Various Feedback updates
		//
		void SetFeedbackScore(int id, int score);
		void UpdateFeedbackFlags(int id, int score);

		//inna		
		void UpdateFeedbackSplitFlag(int id);
		
		// 
		// Determine if a user has left feedback for a given
		// user
		//
		bool UserHasFeedbackFromUser(int id,
									 int commentingId,
									 bool Split);


		//
		// Get a user's feedback details
		//
		void GetFeedbackDetail(
						int id,
						FeedbackItemVector *pvFeedbackDetail,
						bool Split
							  );

		// inna
		// Get a user's feedback details to split into 10 tables
		//
		void GetFeedbackDetailToSplit(
						int id, int Split,
						FeedbackItemVector *pvFeedbackDetail
						);

		// Get the minimal version of a user's feedback detail
		void GetMinimalFeedbackDetail(
						int id,
						MinimalFeedbackItemVector *pvDetails,
						bool Split
											 );

		//
		// Get Feedback left by a user
		//
		void GetFeedbackDetailLeftByUser(
						int id,
						FeedbackItemVector *pvFeedbackDetail
							);

		// 
		// Recent feedback from users, hosts
		//
		clsFeedbackItem *RecentFeedbackFromUser(
												int id,
												int commentingId,
												time_t timeLimit,
												bool negativeFeedbackOnly
													   );

		clsFeedbackItem *RecentFeedbackFromHost(
												int id,
												char *pCommentingHost,
												time_t timeLimit,
												bool negativeFeedbackOnly
													   );


		void VoidFeedbackLeftByUser(
						int commenting_id
							);

		void RestoreFeedbackLeftByUser(
						int commenting_id
							);

		void InvalidateExtendedFeedback(int id);

		void UpdateExtendedFeedback(clsFeedback *pFeedback);

		void GetSuspendedUsers(
						vector<int> *pVUsers);

		//
		// Add a feedback detail record
		// 
		void AddFeedbackDetail(int id,
							   int commentingId,
							   char *pCommentingHost,
							   FeedbackTypeEnum type,
							   int score,
							   char *pText,
							   bool Split,
							   int item = 0
							   );

		// inna
		// Split a feedback detail record into 10 tables
		// 
		void SplitFeedbackDetail(clsFeedbackItem *pFeedbackItem
								);


		void UpdateResponse(int Commentor, 
							time_t CommentDate, 
							int Commentee, 
							const char* pResponse,
							bool Split);

		void UpdateFollowUp(int Commentor, 
							time_t CommentDate, 
							int Commentee, 
							const char* pFollowUp,
							bool Split);

		bool HasFollowUp(int Commentor, 
						time_t CommentDate, 
						int Commentee,
						bool Split);

		bool HasResponse(int Commentor, 
						time_t CommentDate, 
						int Commentee,
						bool Split);

		clsFeedbackItem* GetFeedbackItem(
					  int Commentor,
					  time_t CommentingDate,
					  int Id,
					  bool Split);

		int GetFeedbackDetailCount(int id, bool Split);

		// Transfer Feedback for a user
		void TransferFeedback(clsUser *pFromUser,
							  clsUser *pToUser);

		// Transfer feedback LEFT by a user
		void TransferFeedbackLeft(clsUser *pFromUser,
								  clsUser *pToUser);

		// Delete's feedback record
		void DeleteFeedback(int id);

		void AddManyFeedbackDetail(
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
								int textLen
								  );

		// transaction table for feedback
		void AddTransactionRecord(int Item, 
								int SellerId, 
								int* pBidders, 
								int BidderCount, 
								time_t Date);

		bool IsValidTransaction(int Item, int SellerId, int Bidder, char Used);
		void SetTransactionUsed(int Item, int SellerId, int BidderId, char Used);

		bool GetFeedbackListFromFeedbackList(int id,
					FeedbackRowItemVector *pvFeedback);

		void UpdateFeedbackList(int Id,
					 FeedbackItemVector *pvFeedback);

		void InvalidateFeedbackList(int Id);

		void DeleteFeedbackList(int Id);

		// get feedback detail from feedback detail cache list
		void GetFeedbackDetailFromList(
						int id,
						FeedbackItemVector *pvFeedbackDetail,
						bool Split,
						int Offset,
						int Length,
						int *pTotalItems);

		// get feedback detail from feedback detail cache list
		void GetFeedbackDetailFromListMinimal(
						int id,
						MinimalFeedbackItemVector *pvMinimalFeedbackDetail,
						bool Split,
						int Offset,
						int Length
							  );

		//  **********
		//	Accounts
		//  **********

		// Get a user's account "balance" (summary)
		clsAccount *GetAccount(int id);

		// Update Credit Card information for an account
		void UpdateCCDetails(	int id, 
				 	int cc_First4Digits, 
				 	time_t cc_Expirydate,
				 	time_t cc_Updatetime);

		// Get accounts vector of all user accounts
        void GetUserAccounts(AccountsVector *pvAccounts);

		// Update account entry for email expiration notice sent
		void UpdateExpiredNoticeSent(int id,
									 time_t cc_NoticeSentDate);

		// Create an account balance record
		void CreateAccount(int id,
						   double balance = 0);
// Lena
//		char *GetTableName( int tableIndicator );
		void GetTableName( int tableIndicator, char *tableName );
//		char *CombineSQLStatement( const char *firstPart, 
//											char *table_name,
//											const char *secondPart );
		void CombineSQLStatement( const char *firstPart, 
											char *table_name,
											const char *secondPart,
											char *statement );
		void SetTableIndicator( int id, int &tableIndicator );
		bool UpdateIndicator( int id, int indicator );

		// Adjust account balance
		void AdjustAccountBalance(int id,
								  double delta);

		// Add a detail record
// Lena
//		void AddAccountDetail(
//						int id,
//						clsAccountDetail *pDetail
//									 );
// Lena
/*		void AddAccountDetail(
						int id,
						int tableIndicator,
						clsAccountDetail *pDetail,
						int itemId,
						int batchId = 0
									 ); */
		void AddAccountDetail(
						int id,
						int tableIndicator,
						clsAccountDetail *pDetail );


// Lena		void AddRawAccountDetail(
//						int id,
//						clsAccountDetail *pDetail,
//						int migrationBatchId
//								);
/*		void AddRawAccountDetail(
						int id,
						int tableIndicator,
						clsAccountDetail *pDetail,
						int migrationBatchId,
						int batchId = 0
								);  */
		void AddRawAccountDetail(
						int id,
						int tableIndicator,
						clsAccountDetail *pDetail,
						int migrationBatchId
								);
		bool LoadAccountDetail(
						int id,
						int tableIndicator,
						clsAccountDetail *pDetail );

// Lena		void AddRawAccountDetail(
//						int count,
//						int *pId,
//						char *pWhen,
//						int *pAction,
//						float *pAmount,
//						char *pMemo,
//						int *pSeq,
//						int *pMigrationBatchId);
		void AddRawAccountDetail(
						int count,
						int *pId,
						int tableIndicator,
						char *pWhen,
						int *pAction,
						float *pAmount,
						char *pMemo,
						int *pSeq,
						int *pMigrationBatchId,
						int *pBatchId, int itemId = 0);

// Selecting the users to be invoiced and charged past due
		void GetUsersWithPastDueNotCalculated(
						vector<unsigned int> *pvIds,
                        time_t tPastDueDate);
		void GetUsersWithAccountsNotInvoiced( 
						vector<unsigned int> *pvIds,
                        time_t tInvoiceDate, int idStart, int idEnd = 0 );
		void GetUsersWithUnsplitAccountsRange(
									vector<unsigned int> *pvIds,
									int idStart, int idEnd );
		//inna-needed for balance aging 
		void GetAllUsersWithAccountsRange(
									vector<unsigned int> *pvIds,
									int idStart, int idEnd);

// Adding interim balances during invoices

		void AddInterimBalance(
						int id,
						time_t theTime,
		       			double amount );
		bool CombineInterimBalanceForUsers( int oldId, int newId );

		bool GetInterimBalance( 
						int id, 
						time_t &theTime, 
						double &amount, bool first = 0 );
		bool GetInterimBalanceForMonth( int id, time_t the_time );

		bool CombineInterimBalance( int oldId, int newId, 
											  time_t the_time );
		void CalculateDate( time_t &theDate );
		bool GetMonthRangeForUsers( int id, time_t &timeStart, 
											   time_t &timeEnd );


		void GetInterimBalances(int id,
								InterimBalanceList *plBalances);

		int VerifyCCChargeAmount(char *cc, float amount, time_t maxwhen, int UserId);

		//
		// Special HACK Method
		//
		int XAddRawAccountDetail(
						int count,
						int *pId,
						char *pWhen,
						int *pAction,
						float *pAmount,
						char *pMemo,
						int *pSeq,
						int *pMigrationBatchId);


		void AddAccountAWItemXref(int count,
								  unsigned int *pSeq,
								  char *pItem);

		//
		// Special HACK method
		//
		void XAddAccountAWItemXref(int count,
								  unsigned int *pSeq,
								  char *pItem);


		void AddAccountItemXref(TransactionId id,
							    int itemId);


		// Rebalance an account
// Lena		void RebalanceAccount(int id);
		void RebalanceAccount(int id, int tableIndicator);


		// Get Next transacton id
		void GetNextTransactionId(TransactionId *pId);

		// Get a user's account detail
// Lena		void GetAccountDetail(int id,
//							  AccountDetailVector *pvDetail);
		void GetAccountDetail(int id, 
							  int tableIndicator,	
							  AccountDetailVector *pvDetail);

		// Lena
//		void GetAccountDetail(int id,
//							  AccountDetailVector *pvDetail, time_t since );
//		void GetAccountDetail(int id,
//						AccountDetailVector *pvDetail, time_t since, time_t until);
//		void GetAccountDetailUntil(int id,
//							AccountDetailVector *pvDetail, time_t until);
//
//
//		void GetAccountDetail(int id,
//							  AccountDetailList *plDetail);
//
//		void GetAccountDetailForItem(int id,
//									 int itemId,
//									 AccountDetailVector *pvDetail);
		void GetAccountDetail(int id,
							  int tableIndicator,
							  AccountDetailVector *pvDetail, time_t since );
		void GetAccountDetail(int id,
						int tableIndicator,
						AccountDetailVector *pvDetail, time_t since, time_t until);
		void GetAccountDetailUntil(int id,
							int tableIndicator,
							AccountDetailVector *pvDetail, time_t until);


		void GetAccountDetail(int id,
								int tableIndicator,
							  AccountDetailList *plDetail);


		// Get a user's account detail for an item
		void GetAccountDetailForItem(int id,
										int tableIndicator,
									 int itemId,
									 AccountDetailVector *pvDetail);


		// Get a user's account detail for an item

		void GetAccountDetailForAWItem(int id,
									   char *pItem,
									   AccountDetailVector *pvDetail);

// Lena		void GetAccountDetailByType(int id,
//									AccountDetailTypeEnum type,
//									AccountDetailVector *pvDeatil);
		void GetAccountDetailByType(int id,
									int tableIndicator,
									AccountDetailTypeEnum type,
									AccountDetailVector *pvDeatil);

		// Get Bad account detail record for a given user
		void GetBadAccountDetail(int id,
									  AccountDetailVector *pvDetail);

		void UpdateAccountPastDue(int id,
								  time_t pastDueBase,
								  double	pastDue30Days,
								  double pastDue60Days,
								  double pastDue90Days,
								  double pastDue120Days,
								  double pastDueOver120Days);
		//inna
		void SetAccountBalance(float balance, int id);

		// Delete detail based on time
// Lena		void DeleteAccountDetailByTime(int id,
//									   time_t theTime);
		void DeleteAccountDetailByTime(int id,
										int tableIndicator,
									   time_t theTime);


		// Delete accont balances - used in rename users
		void DeleteAccountBalance(int id);

		// Add a cross-reference to their old AW account
		void AddAWAccountCrossReference(int id,
				         				int awAccountId);

		void GetAWAccountCrossReference(int id,
										int *pAWId);

		void GeteBayAccountCrossReference(int id,
										  int *peBayId);



		// Get last AW update time
		time_t GetLastUpdateFromAWTime(int id);
		void SetLastUpdateFromAWTime(int id, time_t when);


		//
		// Get a list of all users with accounts
		//
		void GetUsersWithAccounts(vector<unsigned int> *pvIds);

		// Get a list of users with bad account details;
		// used to fix relisting problem.
		void GetUsersWithBadAccounts(vector<unsigned int> *pvIds);

		//
		// UpdateAccountDetail
		//
// Lena		void UpdateAccountDetailTime(int userId,
//									 clsAccountDetail *pDetail);
		void UpdateAccountDetailTime(int userId,
										int tableIndicator,
									 clsAccountDetail *pDetail);


		//
		// Get last invoice date
		//
		time_t GetLastInvoiceDate();

		//
		// Get ids of accounts invoiced in a date range
		//
		void GetInvoices(time_t startInvoiceTime,
						 time_t endInvoiceTime,
//						 AccountDetailVector *pvDetail);
						 InterimBalanceList *pInterimBalance,
						 int requestedId = 0, bool all = false);

		
		//inna - get sum of all payments since a given date
		void GetPaymentsSince(int id, time_t tSinceDate, double &amount, int tableIndicator);
		//inna - get sum of all payments for a given date range
		void GetPaymentsByDate(int id, time_t tSinceDate, time_t tEndDate, 
											double &amount, int tableIndicator);
		//inna
		void AddEndOfMonthBalance(clsEndOfMonthBalance *pEndOfMonthBalance);
		void AddEndOfMonthBalanceDelayed(clsEndOfMonthBalance *pEndOfMonthBalance);
		clsEndOfMonthBalance *GetEndOfMonthBalanceById(int id, time_t tInvoiceDate);
		void	GetUsersForThisMonth(vector<unsigned int> *pvIds, time_t tInvoiceDate, int idStart, int idEnd);
		void InvoiceTime(tm &thisTime, int month);
		int LastDayOfMonth(int month, int year);
		bool LeapYear(int year);

		//inna - make summary report into a table output
		void AddRawSummaryReportData(char *pCatName,
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
			int category_id);
		bool GetEOAStateInfo(clsEOAState *pEOAState);
		bool UpdateEOAStateInfo(clsEOAState *pEOAState);
		bool CreateNextEOAStateInfo(clsEOAState *pEOAState);
		void MakeInstanceComplete(clsEOAState *pEOAState);


		// **********
		// Categories
		// **********
		clsCategory *GetCategoryById(
							MarketPlaceId marketplace,
							CategoryId category,
							int siteId = -1
											);

//		clsCategory *GetCategoryByName(
//							MarketPlaceId marketplace,
//							char *pName
//											  );

		clsCategory *GetCategoryFirst(
							MarketPlaceId marketplace,
							CategoryId category,
							int QueryCode,
							int siteId = -1
											  );
		
//		void AddCategory(
//							clsCategory *pCategory);
//
//		void UpdateCategory(
//							clsCategory *pCategory
//							);
//
//		void DeleteCategory(
//							clsCategory *pCategory
//							);
//
//		void UpdateDescendantCategory(
//							clsCategory *pCategory
//							);

		// getting category vectors use the same set of
		// declarations, etc; only the sql statement differ.
		// all = 1; topLevel = 2
		// children = 3, descendants = 4
		void GetCategoryVector(
							MarketPlaceId marketplace,
							CategoryId pId,
							int QueryCode,
							CategoryVector *pCategoryVector,
							int siteId = -1
							);

//		// sets order_no of a category
//		void UpdateCategoryCounter(
//							clsCategory *pCategory,
//							int count);
		
		
		// Get the next available category id
		int GetNextCategoryId();

		void GetCategoryItems(MarketPlaceId marketplace,
									CategoryId category,
									int QueryCode,
									time_t enddate,
									ItemVector *pvItems,
									ItemListSortEnum SortCode = SortItemsByUnknown,
									bool ended = false);


		int GetCategoryItemsCount(MarketPlaceId marketplace,
									CategoryId category,
									int QueryCode,
									time_t startdate,
									time_t enddate, bool ended = false);

		int GetItemsCountByCategory(CategoryId category,
									int QueryCode,
									time_t startdate,
									time_t enddate);

//		void SetCategoryItems(MarketPlaceId marketplace,
//									CategoryId oldCategory,
//									CategoryId newCategory);
//
//		// moves a user's interest from one category to another
//		// used when replacing a category with another
//		void SetCategoryUsersInterests(CategoryId oldCategory,
//									CategoryId newCategory);

		// get total category count
		int GetCategoryCount(MarketPlaceId marketplace);

		// get category count under a sepcified category
		int GetCategoryCountInCategory(MarketPlaceId marketplace, CategoryId id);

		// for category admin, get exclusive lock
		void LockCategoryTable();

		// Get first two level category count
		int GetFirstTwoLevelCategoryCount(MarketPlaceId marketplace);

		// Get the open item counts in each category, stored in
		// *ppvCounts[category_id]
		void GetCategoryCountsFromOpenItems(vector<int> **ppvCounts, int iRegionID = 0);

		//	**********
		//	Bulletin Board
		//	**********

		void GetBulletinBoardControlEntries(BulletinBoardVector *pvBoards);
		void AddBulletinBoardControlEntry(clsBulletinBoard *pBoard);
		void UpdateBulletinBoardControlEntry(clsBulletinBoard *pBoard);


		void AddBulletinBoardEntry(BulletinBoardId board_id,
								   int user_id,
								   char *pEntry);

		void TrimBulletinBoard(BulletinBoardId board_id,
							   int size);

		void GetAllBulletinBoardEntries(
								BulletinBoardId board_id,
								BulletinBoardEntryList *plEntries,
								int maxPostAgeSeconds);

		// ***********
		// Listing items
		// ***********
		//
		// Retrieve active listing items
		//
		void GetListingItems(MarketPlaceId marketplace, 
							time_t enddate, 
							int	   QueryType,
							ListingItemVector* pItemVector,
							bool splitIntoCategories = true, bool ended = false);

		// GetChildLeafCategoryIds
		void GetChildLeafCategoryIds( MarketPlaceId marketplaceid,
									  int	CatId,
									  int**  pCategoryIds);

		// Get Not leaf category Ids
                void GetNotLeafCategoryIds( MarketPlaceId marketplaceid, vector<int> *pvCatIds);

		// GetNumberOfChildLeafCategories
		int  GetNumberOfChildLeafCategories(MarketPlaceId marketplace, 
											int CatId);

		// GetMaxCategoryId
		int  GetMaxCategoryId(MarketPlaceId marketplace);

		void GetItemsByEndDate(MarketPlaceId marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate, bool ended = false);
			
		void GetItemsByEndDateSortedByCat(MarketPlaceId marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate);
		// ************
		//
		//	Statistics
		//
		// ************

		// Update daily statistics
		void UpdateDailyStatisticsOnLeafCategories(
						const char* pQuery,
						int		Marketplace,
						int		TransactionId,
						time_t	Today);

		void UpdateDailyStatisticsOnNotLeafCategory(
                                MarketPlaceId Marketplace,
                                time_t  Today,
                                int     XactionId,
                                int     CatId,
                                char*   pCatList);

		void GetDailyStatistics(MarketPlaceId marketplace,
								time_t StartTime, 
								time_t EndTime,
								int	XactionId,
								CategoryId CatId,
								DailyStatsVector* pDailyStats);

		// get statistics transcation type
		void GetStatisticsTransaction(
						StatisticsEnum StatisticType,
						StatsTransactionVector* pTransVector);


		// Get the statistics trnsaction query statement
		void GetTransactionQuery(
				int XactionId,
				StatisticsEnum StatisticType,
				char* pQuery,
				int	  Size);

		// Get daily bids
		int	GetDailyBids(MarketPlaceId Marketplace,
				time_t	StartTime);

		// Update daily Bids
		void UpdateDailyBids(
				MarketPlaceId Marketplace,
				time_t	Today,
				int	Bids);

		// ********************
		//
		// Finance
		//
		// ********************

		// summarize the data from ebay_accounts to ebay_finance
		void UpdateDailyFinance(time_t StartTime);

		// retrieve data from ebay_finance
		void GetDailyFinance(
				time_t StartTime, 
				time_t EndTime,
				DailyFinanceRawVector* pvDailyFinance,
				int *pMaxAction);
				
		//inna = lydia's methods
		bool TempAcctsByDateExists();
		bool TempAcctsNotSplitIdExists();

		void DropAcctsByDate();
		void DropAcctsNotSplitId();

		void CTASAcctsNotSplitId();
		void CTASAccountsByDate(char *cStartTime, char *cEndTime);


		// ********************
		//
		//	Ads
		//
		// ********************
		void GetPageViews(int Marketplace, 
						 int Pagetype,
						 int* pPageViews);

		void GetAds(int PageType,
				   time_t Start,
				   time_t End,
				   void** pAdVectorArray);

		//
		// Historical Data
		//
		void AddHistoricalInfo(int count,
							   int *pIds,
							   char *pPeriodBegins,
							   int periodBeginSize,
							   int *pCategoryIds,
							   int *pItemsSold,
							   float *pDollarsSold,
							   int *pItemsBought,
							   float *pDollarsBought,
							   int *pItemsNotSold,
							   float *pDollarsNotSold);


		clsAnnouncement *GetAnnouncement(
			int marketplace,
			int announceid, 
			int where,
			int partnerId=0,
			int siteId=0);

		bool UpdateAnnouncement(
			clsAnnouncement *pAnnounce);

		bool AddAnnouncement(
			int marketplace,
			int announceid, 
			int where, 
			char *pCode,
			char *pAnnounce,
			int partnerId=0,
			int siteId=0);

		void GetAllAnnouncementsBySiteAndPartner(
			int marketplace,
			AnnouncementVector *pvAnnouncements,
			int SiteId,
			int PartnerId);

		void GetAllAnnouncements(
			int marketplace,
			AnnouncementVector *pvAnnouncements);

		void GetOnePartnerData(int id,
							   time_t forWhen,
							   int *hitcount,
							   int *new_users,
							   int *new_users_total,
							   int *new_users_ever);

		// Store one partner tally.
		void AddPartnerData(int id,
						   int views,
						   time_t coversDay,
						   int newUserRegistrations,
						   int newUserRegistrationsEver);

		// Get the list of partner names, with their offset into the vector
		// as their index.
		void GetPartnerIds(vector<const char *> *pvPartners);
		//
		// User Surveys
		//
		void GetUserSurveyResponse(MarketPlaceId marketplace,
								   int user_id,
								   int survey_id,
								   int question_id,
								   bool *pGotBoolResponse,
								   bool *pBoolResponse,
								   bool *pGotNumberResponse,
								   float *pNumberResponse,
								   bool *pGotTextResponse,
								   int *pTextResponseLength,
								   char **ppTestResponse);

		void SetUserSurveyResponse(MarketPlaceId marketplace,
								   int user_id,
								   int survey_id,
								   int question_id,
								   bool response);

		void SetUserSurveyResponse(MarketPlaceId marketplace,
								   int user_id,
								   int survey_id,
								   int question_id,
								   float response);

		void SetUserSurveyResponse(MarketPlaceId marketplace,
								   int user_id,
								   int survey_id,
								   int question_id,
								   char *pResponse);


		// Cobranding
		// nsacco 05/25/99 added siteId
		void LoadPartners(vector<clsPartner *> *pvPartners, int siteId);

		// nsacco 05/25/99
		void LoadSites(vector<clsSite *> *pvSites);
		void LoadSite(int id, clsSite **pSite);
		void LoadSite(const char *pName, clsSite **pSite);
		void GetAllMinimalSites(vector<clsSite *> *pvSites);
		// petra 08/09/99
		int GetNumberOfSites();

		// kakiyama 06/18/99
		void GetForeignSites(vector<clsSite *> *pvSites);

		// nsacco 05/25/99 added siteId
		void LoadPartnerHeaderAndFooter(int partnerId,
			vector<char *> *pvHeaders, vector<char *> *pvFooters,
			vector<char *> *pvDeletes,
			int siteId);

		int GetNumberOfHeaderReferences(int unique_id);

		void ChangeCobrandHeaderText(int unique_id,
									const char *pNewText);

		int GetCobrandHeaderTextId(int partnerId,
											  int isHeader,
											  PageTypeEnum page_type,
											  PageTypeEnum secondary_page_type,
											  int siteid);

		int NewCobrandHeaderReference(const char *pDescription,
												 const char *pNewText);

		int GetCobrandNextHeaderId();

		void RemoveCobrandHeaderReference(int uniqueId);

		void SetCobrandHeader(int partnerId, int isHeader,
										PageTypeEnum pageType,
										PageTypeEnum secondaryPageType,
										const char *pDescription,
										const char *pText,
										int site_id);

		void UpdateCobrandHeaderReference(int partnerId,
													 int isHeader,
													 PageTypeEnum pageType,
													 PageTypeEnum secondaryPageType,
													 int uniqueId,
													 int site_id);

		void CopyCobrandHeaderReference(int partnerIdOriginal,
												   int isHeaderOriginal,
												   PageTypeEnum pageTypeOriginal,
												   PageTypeEnum secondaryPageTypeOriginal,
												   int siteIdOriginal,
												   int partnerIdNew,
												   int isHeaderNew,
												   PageTypeEnum pageTypeNew,
												   PageTypeEnum secondaryPageTypeNew,
												   int siteIdNew);

		void ChangeCobrandHeaderDesc(int uniqueId,
												const char *pNewDesc);

		int GetCobrandNextPartnerId();
		// nsacco 06/21/99 added siteId and pParsedString
		int CreateCobrandPartner(const char *pName,
								const char *pDescription,
								int siteId,
								const char *pParsedString);

		void GetSiteHeadersAndFooters(int siteId,
									  vector<clsHeader*>* pvHeaders, 
									  vector<clsFooter*>* pvFooters);

		void GetSitePartnerHeadersAndFooters(int siteId, 
											 int partnerId,
											 vector<clsHeader*>* pvHeaders, 
											 vector<clsFooter*>* pvFooters);

		//
		// Cobrand ads
		//
		bool AddCobrandAdDesc(clsAd *pAd);

		int GetCobrandAdDescTextLen(int id);

		int GetCobrandAdDescTextLen(const char *pName);

		char *GetCobrandAdDescText(int id);

		clsAd *GetCobrandAdDesc(int id);

		clsAd *GetCobrandAdDesc(const char *pName);

		void LoadAllCobrandAdDescs(AdVector *pvAds);

		bool UpdateCobrandAdDesc(int id, clsAd *pAd);

		bool UpdateCobrandAdDesc(const char *pName, clsAd *pAd);

		bool DeleteCobrandAdDesc(int id);

		bool DeleteCobrandAdDesc(const char *pName);

		int GetNextCobrandAdDescId();

		void AddCobrandAd(clsPartnerAd *pAd);

		void LoadAllCobrandAds(PartnerAdVector *pvAds);

		void GetCobrandAdsById(int adId, vector<clsPartnerAd *> *pvAds);

		void GetCobrandAdsBySite(int siteId, vector<clsPartnerAd *> *pvAds);

		void GetCobrandAdsBySiteAndPartner(int siteId, 
										   int partnerId,
										   vector<clsPartnerAd *> *pvAds);

		void GetCobrandAdsByPage(PartnerAdVector *pvAds,
								 int siteId, 
								 int partnerId,
								 PageTypeEnum pageType1,
								 PageTypeEnum pageType2);

		bool UpdateCobrandAd(clsPartnerAd *pAd);

		bool DeleteCobrandAd(clsPartnerAd *pAd);

		//
		// Top Sellers
		//
		void GetTopSellers(MarketPlaceId marketplace, int level, vector<int> *pvIds);
		
		//
		// Junk
		//
		void GetBetaCustomers(vector<int> *pVUsers);

		//
		// Routines made public to invalidate seller and bidder
		// lists
		//
		void InvalidateSellerList(MarketPlaceId marketplace,
								  int sellerId, int itemId = 0, time_t saleEnd = 0);

		void InvalidateBidderList(MarketPlaceId marketplace,
								  int BidderId, int itemId = 0, time_t saleEnd = 0);

		// delete seller and bidder lists
		void DeleteSellerList(MarketPlaceId marketplace,
								  int sellerId);

		void DeleteBidderList(MarketPlaceId marketplace,
								  int BidderId);

		void GetUsersWithBidderLists(vector <unsigned int> &pvUserList);
		void GetUsersWithSellerLists(vector <unsigned int> &pvUserList);



		// Referral counting.
		void IncrementPartnerCount(int id);
		void CountPartnerRegistrations(time_t forWhen);
		void CreateEmptyPartnerCountRecord(int id,
										   const char *pTime);
		//
		// Check whether an email is an anonymous email
		//
		bool IsAnonymousEmail(char* pEmail);

		// Get a list of adult category ids
		void GetAdultCategoryIds(clsMarketPlace *pMarketPlace, 
										vector<CategoryId> *pvAdultCategories);
		
		//
		//check whether user participed a particular survey
		//
		bool IsParticipatedSurvey(int survey_id, int userid);


		void AddUserToSurveyRecord(int survey_id, int userid);


		//
		// User pages.
		//
		void CreateUserPage(clsUserPage *pPage);
		void UpdateUserPage(clsUserPage *pPage);
		void GetUserPage(clsUserPage *pPage,
						bool withDictionary);
		void AddViewToUserPage(long userId,
							  int pageNumber);
		void RemoveUserPage(long userId,
						   int pageNumber);

        void CreateUserPageCategoryListing(clsUserPage *pPage);
        void UpdateUserPageCategoryListing(clsUserPage *pPage);
        void RemoveUserPageCategoryListing(long userId,
                                           long category);
        void RemoveAllUserPageCategoryListing(long userId);

        void GetUserPagesByUser(long userId,
            vector<clsUserPage *> *pvPages);

        void GetAllUserCategoryPages(vector<clsUserPage *> *pvPages);

		void CreateNeighbor(long userId,
						   long targetUserId,
						   const char *pComment);
		void ApproveNeighbor(long userId,
							long targetUserId,
							bool approve);
		void GetNeighbors(long userId, vector<clsNeighbor *> *pvNeighbors);


		//
		// Gift occasions.
		//
		void GetActiveGiftOccasions(MarketPlaceId marketplace,
									vector<clsGiftOccasion *> *pvOccasions);

		bool GetGiftOccasion(MarketPlaceId marketplace,
							 int id,
							 clsGiftOccasion *pOccasion);

		void AddGiftOccasions(vector<clsGiftOccasion *> *pvOccasions);

		void AddGiftOccasion(clsGiftOccasion *pOccasion);

		void DeleteAllGiftOccasions(MarketPlaceId marketplace);

		void DeleteGiftOccasion(MarketPlaceId marketplace,
								int id);

		void UpdateGiftOccasion(clsGiftOccasion *pOccasion);

		int GetNextGiftOccasionId();

		int GetGiftOccasionFlags(MarketPlaceId marketplace,
								 int id);

		void SetGiftOccasionFlags(MarketPlaceId marketplace,
								  int id,
								  int flags);


		// **********
		// Notes
		// **********
		void LoadNotes(unsigned int addressFilter, 
					   unsigned int aboutFilter,
					   unsigned int categoryFilter,
					   clsNoteAddressList *pFrom,
					   clsNoteAddressList *pTo,
					   clsNoteAddressList *pCC,
					   clsNoteAddressList *pAbout,
					   clsNoteList *plNotes);

		int GetNextNoteSequence();
		void AddNote(clsNote *pNote);		
		//
		// International.
		//
		void GetAllCountries(CountryVector *pvCountries);
		int DetermineNumCountries();

		void GetAllCurrencies(CurrencyVector *pvCurrencies);
		int DetermineNumCurrencies();

		// petra 07/02/99 
		void GetLocaleInfo(int localeId, int timezoneId, clsIntlLocale * pLocale);

		// Exchange rates
		bool GetRatesForCurrency(ExchangeRateVector *pvRates, int fromCurrency);
		int DetermineNumExchangeRates();

		// Trust/Safety things
		void GetNewBigSellers(MarketPlaceId marketplace,
			int maximumAge,
			int minimumSales,
			vector<int> &userVector);
		
		void GetAuctionIds(const vector<int>&vSellers, 
			vector<int>&vCandidateSellers,
			vector<int>&vCandidateAuctionIds,
			int limit);
		
		void GetAuctionsBidOn(const vector<int>&vBidders,
			vector<int>&vAllBidders,
			vector<int>&vAllAuctionsBidOn,
			int limit);

		void GetSellersOfAuctions(const vector<int>&vAuctions,
			hash_map<int, int, hash<int>, eqint>&mSellersAndAuctions,
			int limit);

		void GetGenericPairsFromSingleVectorWithLimit(const char *SQL,
			const vector<int> &vInputs,
			vector<int> &vOutput1,
			vector<int> &vOutput2,	
			int limit,
			unsigned char*& pCursor);

		void GetAuctionsWon(const vector<int>&vBidders,
			vector<int>&vItemNumbers,
			vector<int>&vReturnedBidders,
			int limit);

		void GetOurAuctionsBidOnByUs(const vector<int>&vItems,
			const vector<int>&vBidderIds,
			vector<int>&vReturnedIds,
			int limit);

		void GetShillInformationForOurAuctions(
			const vector<int>&vInputs,		// item ids
			hash_map<int, int, hash<int>, eqint>& mapItemsToCounts,
			hash_map<int, time_t, hash<int>, eqint>& mapItemsToDurations,
			hash_map<int, float, hash<int>, eqint>& mapItemsToReserves);

		void GetBidsFromTheseUsers(
			const vector<int>&vAuctionsByUsBidOnUs, 
			const vector<int>&vBidderIds,
			vector<int>&vBidderIdsOfBids,
			vector<int>&vOurBidItemNumbers,
			vector<int>&vOurBidTypes);

		void GetAuctionsWithRetractions(
			const vector<int>&vBidderIds,
			vector<int>&vReturnedBidders,
			vector<int>&vReturnedRetractions,
			int limit);

#ifdef SLURPING_FEEDBACK

		void GetFeedbackScores(const vector<int>&vIds,
			hash_map<int, int, hash<int>, eqint>&mIdsAndScores);

#endif

		// *******
		// Gallery
		// *******

		bool GetGalleryChangedItem(int sequenceID, clsGalleryChangedItem& item);
		bool AppendGalleryChangedItem(clsGalleryChangedItem& item);
		bool SetGalleryChangedItemState(int sequenceID, int newSequenceID, int attempts, int state);
		bool DeleteGalleryChangedItem(int sequenceID);
		bool GetGallerySequenceRange(int& minSequence, int& maxSequence);

		int GetCurrentGallerySequence();
		int GetNextGallerySequence();

		int GetCurrentGalleryReadSequence();
		int GetNextGalleryReadSequence();


		//
		// Deadbeats
		//
		void AddDeadbeat(int id,
						 int deadscore,
						 int creditRequests,
						 int warnings,
						 bool isValidDeadbeatScore,
						 bool isValidCreditRequestCount,
						 bool isValidWarningCount);

		clsDeadbeat *GetDeadbeat(int id);

		bool GetAllDeadbeats(DeadbeatVector *pvDeadbeats);

		void SetDeadbeatScore(int id, int score);

		void SetCreditRequestCount(int id, int count);

		void SetWarningCount(int id, int count);

		int GetDeadbeatScore(int bidder);

		bool IsDeadbeatUser(int bidder);

		int GetCreditRequestCount(int seller);

		bool UserHasCreditRequests(int seller);

		int GetWarningCount(int bidder);

		bool UserHasWarnings(int bidder);

		bool InvalidateDeadbeatScore(int id);

		bool ValidateDeadbeatScore(int id);

		bool InvalidateCreditRequestCount(int id);

		bool ValidateCreditRequestCount(int id);

		bool InvalidateWarningCount(int id);

		bool ValidateWarningCount(int id);

		//
		// Deadbeat Items
		//
		void ClearAllDeadbeatItems();

		bool GetDeadbeatItem(MarketPlaceId marketplace,
							 int id,
							 int seller,
							 int bidder,
							 clsDeadbeatItem *pItem,
							 char *pRowId = NULL,
							 time_t delta = 0);

		bool GetDeadbeatItem(int id,
							 int seller,
							 int bidder,
							 clsDeadbeatItem *pItem,
							 char *pRowId = NULL,
							 time_t delta = 0);

		bool GetDeadbeatItemsByBidderId(MarketPlaceId marketplace,
										int id,
										DeadbeatItemVector *pvItems);
										
		bool GetDeadbeatItemsByBidderId(int id,
										DeadbeatItemVector *pvItems);
										
		bool GetDeadbeatItemsBySellerId(MarketPlaceId marketplace,
										int id,
										DeadbeatItemVector *pvItems);
										
		bool GetDeadbeatItemsBySellerId(int id,
										DeadbeatItemVector *pvItems);
										
		bool GetAllDeadbeatItems(MarketPlaceId marketplace,
										DeadbeatItemVector *pvItems);
										
		bool GetAllDeadbeatItems(DeadbeatItemVector *pvItems);
										
		void AddDeadbeatItem(clsDeadbeatItem *pItem);

		void DeleteDeadbeatItem(MarketPlaceId marketplace,
								int id,
								int seller,
								int bidder);

		bool IsDeadbeatItem(MarketPlaceId marketplaceId, 
							int id,
							int seller,
							int bidder);

		int GetDeadbeatItemCountByBidderId(MarketPlaceId marketplace,
										   int bidder);
										
		int GetDeadbeatItemCountByBidderId(int bidder);
										
		int GetDeadbeatItemCountBySellerId(MarketPlaceId marketplace,
										   int seller);
										
		int GetDeadbeatItemCountBySellerId(int seller);

		int GetDeadbeatItemsWarnedCountByBidderId(MarketPlaceId marketplace,
												  int bidder);
										
		int GetDeadbeatItemsWarnedCountByBidderId(int bidder);
												
		bool GetDeadbeatItemsNotWarned(MarketPlaceId marketplace,
									   DeadbeatItemVector *pvItems);
										
		bool GetDeadbeatItemsNotWarned(DeadbeatItemVector *pvItems);

		bool UpdateDeadbeatItem(clsDeadbeatItem *pItem);

		bool SetDeadbeatItemWarned(int id, int seller, int bidder);

		// Auto Credits
		bool InsertItemCredit(CreditsVector *pvCredits);
		void GetCreditsForItem(int item_id, CreditsVector *pvCredits);
		int  GetNextBatchIdForCredits();
		void GetAllNewCredits(int batch_id, CreditsVector *pvCredits);

		//
		// Regions
		//
		void GetAllRegionInfo(vector<clsRegion*>* pvRegions);

		void GetAllRegionsAndZips(clsRegions* pRegions);

		//
		// Categories (Legal Buddy stuff)
		//
		bool		MaskCategory(MarketPlaceId marketplace,
								 CategoryId categoryId,
								 bool on);

		bool		FlagCategory(MarketPlaceId marketplace,
								 CategoryId categoryId,
								 bool on);

#if 0
		void		GetMaskedCategories(MarketPlaceId marketplace,
										CategoryVector *pvCategories);

		void		GetFlaggedCategories(MarketPlaceId marketplace,
										 CategoryVector *pvCategories);
#endif

		//
		// Category filters (Legal Buddy stuff)
		//
		bool			AddCategoryFilter(clsCategoryFilter *pFilter);

		void			DeleteCategoryFilter(CategoryId categoryId,
											 FilterId filterId);

		bool			UpdateCategoryFilter(CategoryId categoryId,
											 FilterId filterId,
											 FilterId newFilterId);

		clsCategoryFilter *
						GetCategoryFilter(CategoryId categoryId,
										  FilterId filterId);

		bool			GetCategoryFiltersByCategoryId(CategoryId categoryId,
													   vector<FilterId> *pvFilterIds);

		unsigned int	GetCategoryFilterCountByCategoryId(CategoryId categoryId);

		bool			GetCategoryFiltersByFilterId(FilterId filterId,
													 vector<CategoryId> *pvCategoryIds);

		unsigned int	GetCategoryFilterCountByFilterId(FilterId filterId);

		void			GetAllCategoryFilters(CategoryFilterVector *pvCategoryFilters);

		//
		// Filters (Legal Buddy stuff)
		//
		bool		AddFilter(clsFilter *pFilter);

		void		DeleteFilter(FilterId id);

		void		DeleteFilter(const char *pName);

		bool		UpdateFilter(FilterId id,
								 clsFilter *pFilter);

		bool		UpdateFilter(const char *pName,
								 clsFilter *pFilter);

		clsFilter *	GetFilter(FilterId id);

		clsFilter *	GetFilter(const char *pName);

		void		GetFilters(CategoryId categoryId,
							   FilterVector *pvFilters);

		void		GetAllFilters(FilterVector *pvFilters);

		FilterId	GetNextFilterId();

		FilterId	GetMaxFilterId();


		//
		// Category messages (Legal Buddy stuff)
		//
		bool					AddCategoryMessage(clsCategoryMessage *pCategoryMessage);

		void					DeleteCategoryMessage(CategoryId categoryId,
													  MessageId messageId);
		bool					UpdateCategoryMessage(CategoryId categoryId,
													  MessageId messageId,
													  clsCategoryMessage *pCategoryMessage);
		clsCategoryMessage *	GetCategoryMessage(CategoryId categoryId,
												   MessageId messageId);

		bool					GetCategoryMessagesByCategoryId(CategoryId categoryId,
																CategoryMessageVector *pvCategoryMessages);

		unsigned int			GetCategoryMessageCountByCategoryId(CategoryId categoryId);

		bool					GetCategoryMessagesByMessageId(MessageId messageId,
															   CategoryMessageVector *pvCategoryMessages);

		unsigned int			GetCategoryMessageCountByMessageId(MessageId messageId);

		bool					GetAllCategoryMessages(CategoryMessageVector *pvCategoryMessages);

		//
		// Filter messages (Legal Buddy stuff)
		//
		bool					AddMinFilterMessage(clsMinFilterMessage *pMinFilterMessage);

		void					DeleteMinFilterMessage(FilterId filterId,
													   MessageId messageId,
													   MessageType messageType);

		bool					UpdateMinFilterMessage(FilterId filterId,
													   MessageId messageId,
													   MessageType messageType,
													   clsMinFilterMessage *pMinFilterMessage);

		clsMinFilterMessage *	GetMinFilterMessage(FilterId filterId,
													MessageId messageId,
													MessageType messageType);

		bool					GetMinFilterMessagesByFilterId(FilterId filterId,
															   MinFilterMessageVector *pvMinFilterMessages);

		unsigned int			GetMinFilterMessageCountByFilterId(FilterId filterId);

		bool					GetMinFilterMessagesByMessageId(MessageId messageId,
																MinFilterMessageVector *pvMinFilterMessages);

		unsigned int			GetMinFilterMessageCountByMessageId(MessageId messageId);


		//
		// Messages (Legal Buddy stuff)
		//
		bool		AddMessage(clsMessage *pMessage);

		void		DeleteMessage(MessageId id);

		void		DeleteMessage(const char *pName);

		bool		UpdateMessage(MessageId id,
								  clsMessage *pMessage);

		bool		UpdateMessage(const char *pName,
								  clsMessage *pMessage);

		clsMessage *GetMessage(MessageId id);

		clsMessage *GetMessage(const char *pName);

		clsMessage *GetMessage(CategoryId categoryId,
							   MessageType messageType);

		bool		GetMessages(MessageQueryType queryType,
								MessageVector *pvMessages,
								unsigned int value = 0);

		MessageId	GetNextMessageId();

		MessageId	GetMaxMessageId();


		//
		// Blocked/flagged items (Legal Buddy stuff)
		//
		void			AddBlockedItem(clsItem *pItem);

		void			DeleteBlockedItem(MarketPlaceId marketplace,
										  ItemId id);

		void			DeleteBlockedItem(ItemId id);

		void			UpdateBlockedItem(clsItem *pItem);

		bool			GetBlockedItem(MarketPlaceId marketplace,
									   ItemId id,
									   clsItem *pItem);

		bool			GetBlockedItem(ItemId id,
									   clsItem *pItem);

		unsigned int	GetBlockedItemCountById(MarketPlaceId marketplace,
												ItemId id);

		unsigned int	GetBlockedItemCountById(ItemId id);

		unsigned int	GetBlockedItemCount(MarketPlaceId marketplace);

		unsigned int	GetBlockedItemCount();

		virtual void    GetUnconfirmedUsers(MarketPlaceId m, 
									vector<int> &vIds, int age);

		// invoice and balance aging state
		bool CreateInvAndBalAgingStateInfo(clsInvAndBalAgingState *pInvAndBalAgingState);
		
		bool GetInvAndBalAgingStateInfo(clsInvAndBalAgingState *pInvAndBalAgingState);
		
		bool UpdateInvAndBalAgingStateInfo(clsInvAndBalAgingState *pInvAndBalAgingState);
		
		void MakeInstanceComplete(clsInvAndBalAgingState *pInvAndBalAgingState);
		
		bool IsRangeOverlap(clsInvAndBalAgingState *pInvAndBalAgingState);
		
		void CleanUpOverlappedRecord(clsInvAndBalAgingState *pInvAndBalAgingState);
		

private:
		//
		// Widget Caching
		//
		void StoreItemList(int active, 
			int kind, CategoryId category, char *scope, int country, vector<unsigned long> *pStore);
		void RetrieveItemList(int active, 
			int kind, CategoryId category, int country, vector<unsigned long> *pStore);
		void GetCachedCategoryIds(list<int> *pStore);

		// 
		// Common Check Routine
		//
		void CheckOracleResult(char *pFromFile, int fromLine, int rc);

		// 
		// And it's friend
		//
		bool CheckForNoRowsFound();

		//
		//
		// 
		bool CheckForNoRowsUpdated();

		//
		// Common state routine
		//
		void SetStatement(unsigned char *pCDA);

		// 
		// Common SQL routine
		//
		void SetSQL(char *pSQL);

		// finding a suitable cursor for the table
		//
		unsigned char **DetermineCursor( int tableIndicator, CallingTypeEnum from );
		// 
		// Common Do - it - all
		// 
		void OpenAndParse(unsigned char **ppCDA,
						  const char *pSQL);

		//
		// Common cursor open routine
		//
		void Open(unsigned char **ppCDA);

		//
		// Common cursor parse
		//
		void Parse(char *pSQL);
		void Parse(const char *pSQL);

		//
		// Common Execute. 
		//
		void Execute();
		void ExecuteAndFetch(int count = 1);

		//
		// Common Fetch
		//
		void Fetch();

		//
		// Common Close
		//
		void Close(unsigned char **ppCDA,
				   bool force = false);

		//
		// Commit
		//
		void Commit();

		//
		// Helpers
		// 
		void Bind(const char *pBindName,
				  int *pVar,
				  short *pInd = NULL);
		void Bind(const char *pBindName,
				  unsigned int *pVar,
				  short *pInd = NULL);
		void Bind(const char *pBindName,
				  long *pVar,
				  short *pInd = NULL);
		void Bind(const char *pBindName,
				  unsigned long *pVar,
				  short *pInd = NULL);
		//void Bind(const char *pBindName,
		//		  ORACLE_DATE_STRUCT *pVar,
		//		  short *pInd = NULL);
		void Bind(const char *pBindName,
				  const char *pVar,
				  short *pInd = NULL);
		void Bind(const char *pName,
				  const char *pVar,
				  int length,
				  short *pInd = NULL);
		void Bind(const char *pBindName,
				  float *pVar,
				  short *pInd = NULL);
		void Bind(const char *pBindName,
				  double *pVar,
				  short *pInd = NULL);

		void BindLongRaw(const char *pName,
						 unsigned char *pVar,
						 int varLength,
						 short *pInd = NULL);

		void Define(int position,
					int *pTarget,
					short *pIndicator = NULL);

		void Define(int position,
					unsigned int *pTarget,
					short *pIndicator = NULL);

		void Define(int position,
					long *pTarget,
					short *pIndicator = NULL);

		void Define(int position,
					unsigned long *pTarget,
					short *pIndicator = NULL);

		void Define(int position,
					float *pTarget,
					short *pIndicator = NULL);

		void Define(int position,
					char *pTarget,
					int targetLength,
					short *pIndicator = NULL);

		void Define(int position,
					double *pTarget,
					short *pIndicator = NULL);

		//void Define(int position,
		//			ORACLE_DATE_STRUCT *pTarget,
		//			short *pIndicator = NULL);
		void DefineLongRaw(int position,
						   unsigned char *pTarget,
						   int targetLength,
						   short *pIndicator = NULL);

		void ArrayDefine(int position,
					   int *pTarget,
					   short skip,
					   short *pInd = NULL,
					   short indicatorSkip = 0);

		void ArrayDefine(int position,
					   char *pTarget,
					   int size,
					   short skip,
					   short *pInd = NULL,
					   short indicatorSkip = 0);

		// For sorting
		void SortItems(ItemVector *pvItems, ItemListSortEnum SortCode);

		// 
		// Internal routines to speed up seller list access
		//
		void GetSellerItemListFromItems(MarketPlaceId marketplace,
										int sellerId,
										SellerItemList *plItems);
public:		// needed by a console app
		bool GetSellerItemListFromSellerList(MarketPlaceId marketplace,
											 int sellerId,
											 SellerItemList *plItems);
private:
		void UpdateSellerList(MarketPlaceId marketplace,
							  int sellerId,
							  SellerItemList *plItems);


		// 
		// Internal routines to speed up Bidder item list access
		//

		void GetBidderItemListFromBids(MarketPlaceId marketplace,
										int BidderId,
										BidderItemList *plItems);


public: //Gurinder - For ReInstateItem Admin Option
		bool ReInstateItem(clsItem * pItem);		
		bool DoesItemExists(int *item_id, int *pmarketplace, int table_id);
		//Gurinder's code ends here

public:	// needed by a console app
		bool GetBidderItemListFromBidderList(MarketPlaceId marketplace,
											 int BidderId,
											 BidderItemList *plItems);
private:
		void UpdateBidderList(MarketPlaceId marketplace,
							  int BidderId,
							  BidderItemList *plItems);

		// (Reciprocal) Link Buttons
		int AddLinkButton(clsUser *pUser, RecipLinkEnum pWhichPic, const char *pUrls);

		//
		// Credit Card Authorize routines to process authorization 
		
		clsAuthorizationQueue *AddAuthorizationEntry(int	id,
													 char  *pCCNumber, 
													 time_t CCExpiryDate,
													 int	priority,
													 float	Amount, 
													 int	transaction_type,
													 char	*accholdername, 
													 char	*street_addr, 
													 char	*city_addr, 
													 char	*stateprov_addr,
													 char	*zipcode_addr, 
													 char	*country_addr,
													 char	*billingaccounttype
													 );
		void RemoveAuthorizationEntry(int refID);
		clsAuthorizationQueue *GetAuthorizationItemWithID(int refID);
		int GetAuthorizationTableSize();
		void GetAuthorizationItems(	int					 trans_type, 
									int					 priority,
									int					 status,
									bool				 bChangeState,
									int					 newStatus,								
									AuthorizationVector *pvAuthorizationItems
								  );

		int  GetAuthorizationStatusForID(int refID);
		void SetAuthorizationStatusForRefID(int refID, aTransResp aResult);
		void CommitCCBillingData(int id, int refID);
		void SetAuthorizationAttemptCount(int id, int count, bool isResetRequired);
		int  GetAuthorizationAttemptCount(int id);
		int  GetNextSettlementFileId();

		int GetUserFlags(int userId);
		void SetUserFlags(int userId, int choices);

		// for 10 x feedback
		char* FillFeedbackDetailTableName(const char* pSrcStatement,
										  int SubTable);

		char* FillFeedbackDetailTableNames(const char* pSrcStatement,
										   int SubTable1,
										   int SubTable2);

		// Feedback Cache buffer allocation/calculation
		//
		bool AllocateFeedbackListBuffer(int rowCountNeeded);

		// Locations
		bool LocationsIsValidZip(const char *targetZip);
		bool LocationsIsValidAC(int targetAC);
		bool LocationsIsValidCity(const char *targetCity);

		bool LocationsDoesACMatchZip(int ac, const char *zip);
		bool LocationsDoesACMatchState(int ac, const char *state);
		bool LocationsDoesZipMatchState(const char *zip, const char *state);
		bool LocationsDoesACMatchCity(int ac, const char *city);
		bool LocationsDoesZipMatchCity(const char *zip, const char *city);
		bool LocationsDoesCityMatchState(const char *city, const char *state);

		void LocationsGetLLForZip(const char *zip, double *lat, double *lon);
		void LocationsGetLLForAC(int ac, double *lat, double *lon);

		bool OracleErrorCheck(int rc,unsigned char *&mpCDA);

		// iEscrow
		bool IsABidderForThisItem (int Item, int UserId);

		// Currency Exchange Rate
		bool InsertExchangeRateRecord(time_t indate, int fromcurrency, double rate);

		// Gallery Admin Tool

		void AddSpecialItem(int item_id, int kind, time_t endDate);

		void DeleteSpecialItem(int item_id);

		void FlushSpecialItem();
		
	
		//
		// State
		//
		bool			mConnected;

		//
		// Pointer to the current cursor
		//
		unsigned char	*mpCDACurrent;

		//
		// Pointer to the current SQL statement
		//
		char			*mpCurrentSQL;

		//
		// Statements/Cursors
		//
		unsigned char	*mpCDAOneShot;
		unsigned char	*mpCDAAdjustMarketPlaceItemCount;
		unsigned char	*mpCDAGetSingleItem;
		unsigned char	*mpCDAGetSingleItemWithDescription;
		unsigned char	*mpCDAGetSingleItemRowId;
		unsigned char	*mpCDAGetSingleItemWithDescriptionRowId;
		unsigned char	*mpCDAGetSingleItemEnded;
		unsigned char	*mpCDAGetSingleItemWithDescriptionEnded;
		unsigned char	*mpCDAGetSingleItemRowIdEnded;
		unsigned char	*mpCDAGetSingleItemWithDescriptionRowIdEnded;
		unsigned char	*mpCDAGetItemDesc;
		unsigned char	*mpCDAGetItemDescLen;
		unsigned char	*mpCDAGetItemDescEnded;
		unsigned char	*mpCDAGetItemDescLenEnded;
		unsigned char	*mpCDASetNewDescription;
		unsigned char	*mpCDAUpdateItemDesc;
		unsigned char	*mpCDADeleteItem;
		unsigned char	*mpCDADeleteItemEnded;
		unsigned char	*mpCDADeleteItemDesc;
		unsigned char	*mpCDADeleteItemDescEnded;
		unsigned char	*mpCDAGetFeedback;
		unsigned char	*mpCDAGetFeedbackDetail[11];
		unsigned char	*mpCDAGetFeedbackDetailPages[11];
		unsigned char	*mpCDAAddFeedbackDetail[11];
		unsigned char	*mpCDAGetMinimalFeedbackDetail[11];
		unsigned char	*mpCDAGetFeedbackDetailLeftByUser;
		unsigned char	*mpCDAGetFeedbackDetailLeftByUserSplit;
		unsigned char	*mpCDAGetUserById;
		unsigned char	*mpCDAGetUserAndFeedbackById;
		unsigned char	*mpCDAGetUserByUserId;
		unsigned char	*mpCDAGetUserAndFeedbackByUserId;
		unsigned char	*mpCDAGetUserAndFeedbackByEmail;
		unsigned char	*mpCDAGetUserIdForRenamedUser;
		unsigned char	*mpCDAGetUserAndInfoById;
		unsigned char	*mpCDAGetUserInfo;
		unsigned char	*mpCDAAddManyUsers;
		unsigned char	*mpCDAAddManyUsersInfo;
		unsigned char	*mpCDAGetHighBidForUser;
		unsigned char	*mpCDAGetHighBidForUserEnded;
		unsigned char	*mpCDAGetHighBidsForItemEnded;
		unsigned char	*mpCDAGetHighBidsForItem;
		unsigned char	*mpCDAGetDutchHighBidders;
		unsigned char	*mpCDAAddBid;
		unsigned char	*mpCDAAddBlockedBid;
		unsigned char	*mpCDASetNewHighBidder;
		unsigned char	*mpCDASetNewHighBidderAndBidCount;
		unsigned char	*mpCDASetNewBidCount;
		unsigned char	*mpCDASetDutchHighBidder;
		unsigned char	*mpCDADeleteDutchHighBidder;
		unsigned char	*mpCDASetNewCategory;
		unsigned char	*mpCDAAdjustAccountBalance;
		unsigned char	*mpCDAGetBids;
		unsigned char	*mpCDAGetBidsEnded;
		unsigned char	*mpCDAGetItemsListedByUser;
		unsigned char	*mpCDAGetItemsListedByUserGetMoreStuff;
		unsigned char	*mpCDAGetItemsListedByUserEnded;
		unsigned char	*mpCDAGetItemsListedByUserGetMoreStuffEnded;
		unsigned char	*mpCDAGetItemsListedByUserWithCompleted;
		unsigned char	*mpCDAGetItemsListedByUserWithCompleted2;
		unsigned char	*mpCDAGetItemsHighBidByUser;
		unsigned char	*mpCDAGetItemsHighBidByUserWithCompleted;
		unsigned char	*mpCDAGetItemsHighBidByUserWithCompletedEnded;
		unsigned char	*mpCDAGetItemsBidByUser;
		unsigned char	*mpCDAGetItemsBidByUserEnded;
		unsigned char	*mpCDAGetItemsBidByUserGetMoreStuff;
		unsigned char	*mpCDAGetItemsBidByUserGetMoreStuffEnded;
		unsigned char	*mpCDAGetItemsBidByUserWithCompleted;
		unsigned char	*mpCDAGetItemsBidByUserWithCompletedByDate;
		unsigned char	*mpCDAGetItemsBidByUserWithCompletedByDate2;
		unsigned char	*mpCDAGetBillTime;
		unsigned char	*mpCDAGetBillTimeByRowId;
		unsigned char	*mpCDAGetBillTimeEnded;
		unsigned char	*mpCDAAddItemBilled;
		unsigned char	*mpCDAAddItemBilledByRowId;
		unsigned char	*mpCDAUpdateItemBilled;
		unsigned char	*mpCDAModifyItemBilled;
		unsigned char	*mpCDAUpdateItemEnded;
		unsigned char	*mpCDAUpdateItemBlocked;
		unsigned char	*mpCDAUpdateItem;
		unsigned char	*mpCDAAddItemNoticed;
		unsigned char	*mpCDAModifyItemNoticed;
		unsigned char	*mpCDAAddItemNoticedByRowId;
		unsigned char	*mpCDAGetItemsModifiedAfter;
		unsigned char	*mpCDAGetCategoryById;
//		unsigned char	*mpCDAGetCategoryByName;
		unsigned char	*mpCDAGetCategoryFirst;
		unsigned char	*mpCDAGetCategoryAll;
		unsigned char	*mpCDAGetCategoryTopLevel;
		unsigned char	*mpCDAGetCategoryChildren;
		unsigned char	*mpCDAGetCategoryDescendants;
//		unsigned char	*mpCDAGetCategorySiblings;
		unsigned char	*mpCDAGetCategoryLeaves;
		unsigned char	*mpCDAGetCategoryChildrenSorted;
		unsigned char	*mpCDAGetCategoryVector;
		unsigned char	*mpCDAGetCategorySiblingPrev;
		unsigned char	*mpCDAGetCategorySiblingNext;
		unsigned char	*mpCDAAddRawAccountDetail;
		unsigned char	*mpCDAAddRawAccountDetail_0;
		unsigned char	*mpCDAAddRawAccountDetail_1;
		unsigned char	*mpCDAAddRawAccountDetail_2;
		unsigned char	*mpCDAAddRawAccountDetail_3;
		unsigned char	*mpCDAAddRawAccountDetail_4;
		unsigned char	*mpCDAAddRawAccountDetail_5;
		unsigned char	*mpCDAAddRawAccountDetail_6;
		unsigned char	*mpCDAAddRawAccountDetail_7;
		unsigned char	*mpCDAAddRawAccountDetail_8;
		unsigned char	*mpCDAAddRawAccountDetail_9;
		unsigned char	*mpCDAGetPaymentsSince;
		unsigned char	*mpCDAGetPaymentsSince_0;
		unsigned char	*mpCDAGetPaymentsSince_1;
		unsigned char	*mpCDAGetPaymentsSince_2;
		unsigned char	*mpCDAGetPaymentsSince_3;
		unsigned char	*mpCDAGetPaymentsSince_4;
		unsigned char	*mpCDAGetPaymentsSince_5;
		unsigned char	*mpCDAGetPaymentsSince_6;
		unsigned char	*mpCDAGetPaymentsSince_7;
		unsigned char	*mpCDAGetPaymentsSince_8;
		unsigned char	*mpCDAGetPaymentsSince_9;
		unsigned char	*mpCDAAddAccountAWItemXref;
		unsigned char	*mpCDAAddItemDescArc;
		unsigned char	*mpCDAAddItemDescEnded;
		unsigned char	*mpCDAUpdateItemPassword;
		unsigned char	*mpCDAUpdateItemDescArc;
		unsigned char	*mpCDARecentFeedbackFromUser;
		unsigned char	*mpCDARecentFeedbackFromUserSplit;
		unsigned char	*mpCDARecentNegativeFeedbackFromUser;
		unsigned char	*mpCDARecentNegativeFeedbackFromUserSplit;
		unsigned char	*mpCDARecentFeedbackFromHost;
		unsigned char	*mpCDARecentFeedbackFromHostSplit;
		unsigned char	*mpCDARecentNegativeFeedbackFromHost;
		unsigned char	*mpCDARecentNegativeFeedbackFromHostSplit;
		unsigned char	*mpCDAGetUserByEmail;
		unsigned char	*mpCDAGetIdForPriorAlias;
		unsigned char	*mpCDAGetIdForPriorEmail;
		unsigned char	*mpCDAAddBBEntry;
		unsigned char	*mpCDAGetBulletinBoardStatistics;
		unsigned char	*mpCDAGetBulletinBoardEntries;
		unsigned char	*mpCDAGetBulletinBoardTimes;
		unsigned char	*mpCDAUpdateBBLastPostTime;
		unsigned char	*mpCDADeleteBulletinBoardEntries;
		unsigned char	*mpCDAGetSellerItemListFromItems;
		unsigned char	*mpCDAGetSellerItemListFromSellerList;
		unsigned char	*mpCDAGetSellerItemListFromItemsEnded;
		unsigned char	*mpCDAGetSellerListSize;
		unsigned char	*mpCDAAddSellerList;
		unsigned char	*mpCDAUpdateSellerList;
		unsigned char	*mpCDAInvalidateSellerList;
		unsigned char	*mpCDAInvalidateExtendedFeedback;
		unsigned char	*mpCDAUpdateExtendedFeedback;
		unsigned char	*mpCDAGetBidderItemListFromBids;
		unsigned char	*mpCDAGetBidderItemListFromBidsEnded;
		unsigned char	*mpCDAGetBidderItemListFromItems;
		unsigned char	*mpCDAGetBidderItemListFromBidderList;
		unsigned char	*mpCDAGetBidderListSize;
		unsigned char	*mpCDAGetBidderList;
		unsigned char	*mpCDAUpdateBidderList;
		unsigned char	*mpCDAInvalidateBidderList;
		unsigned char	*mpCDAAddBidderList;
		unsigned char	*mpCDAAddLinkButton;
		unsigned char	*mpCDASetFeedbackScore;
		unsigned char	*mpCDAAddReqEmailCount;
		unsigned char	*mpCDAInsertNumberAttribute;
		unsigned char	*mpCDAAddItemDesc;
		unsigned char	*mpCDAAddItem;
		unsigned char	*mpCDAGetNextItemId;
		unsigned char	*mpCDAUpdateItemStatus;
		unsigned char	*mpCDAUpdateItemStatusEnded;
		unsigned char	*mpCDAAddAccountDetail;
		unsigned char	*mpCDAAddAccountDetail_0;
		unsigned char	*mpCDAAddAccountDetail_1;
		unsigned char	*mpCDAAddAccountDetail_2;
		unsigned char	*mpCDAAddAccountDetail_3;
		unsigned char	*mpCDAAddAccountDetail_4;
		unsigned char	*mpCDAAddAccountDetail_5;
		unsigned char	*mpCDAAddAccountDetail_6;
		unsigned char	*mpCDAAddAccountDetail_7;
		unsigned char	*mpCDAAddAccountDetail_8;
		unsigned char	*mpCDAAddAccountDetail_9;
		unsigned char	*mpCDAAddAccountItemXref;
		unsigned char	*mpCDAGetNextTransactionId;
		unsigned char	*mpCDAGetAccount;
		unsigned char	*mpCDAGetAccountDetail;
		unsigned char	*mpCDAGetAccountDetail_0;
		unsigned char	*mpCDAGetAccountDetail_1;
		unsigned char	*mpCDAGetAccountDetail_2;
		unsigned char	*mpCDAGetAccountDetail_3;
		unsigned char	*mpCDAGetAccountDetail_4;
		unsigned char	*mpCDAGetAccountDetail_5;
		unsigned char	*mpCDAGetAccountDetail_6;
		unsigned char	*mpCDAGetAccountDetail_7;
		unsigned char	*mpCDAGetAccountDetail_8;
		unsigned char	*mpCDAGetAccountDetail_9;
		unsigned char	*mpCDASetReadOnly;
		unsigned char	*mpCDAGetAnnouncement;
		unsigned char	*mpCDAGetAnnouncementLen;
		unsigned char	*mpCDAGetManyItemsForCreditBatch;
		unsigned char	*mpCDAGetManyEndedItemsForCreditBatch;
		unsigned char	*mpCDAGetManyArcItemsForCreditBatch;
		unsigned char	*mpCDAGetManyUsersForCreditBatch;
		unsigned char	*mpCDAGetManyUsers;
		unsigned char	*mpCDAAddReciprocalLink;
		unsigned char	*mpCDAGetInterimBalances;
		unsigned char	*mpCDAIncrementPartnerCount;
		unsigned char	*mpCDAGetLastCountAndTime;
		unsigned char	*mpCDAUpdateReceiveInfo;
		unsigned char	*mpCDAGetAliasHistory;
		unsigned char	*mpCDAGetAdultCategoryIds;
		unsigned char	*mpCDAGetUserPage;
		unsigned char	*mpCDAGetUserPageText;
		unsigned char	*mpCDAAddViewToUserPage;

		unsigned char	*mpCDAGetEndOfMonthBalanceById;
		unsigned char	*mpCDAAddEndOfMonthBalance;

		unsigned char	*mpCDAGetAllCountries;
		unsigned char	*mpCDAGetAllCurrencies;

		unsigned char	*mpCDAGetResponse[11];
		unsigned char	*mpCDAGetFollowUp[11];
		unsigned char	*mpCDAUpdateFeedbackResponse[11];
		unsigned char	*mpCDAUpdateFeedbackFollowUp[11];
		unsigned char	*mpCDAGetFeedbackDetailCount[11];
		unsigned char	*mpCDAVoidFeedbackLeftByUser[11];
		unsigned char	*mpCDARestoreFeedbackLeftByUser[11];
		unsigned char	*mpCDATransferFeedbackLeft[11];
		unsigned char	*mpCDAUserHasFeedbackFromUser[11];
		unsigned char	*mpCDAGetFeedbackDetailRowID[11];
		unsigned char	*mpCDAGetFeedbackItem[11];

		unsigned char	*mpCDAAddTransactionRecord;
		unsigned char	*mpCDAGetTransactionRecord;
		unsigned char	*mpCDASetTransactionUsed;

		unsigned char	*mpCDADeleteTransactionRecord;
		unsigned char	*mpCDASplitFeedbackDetail[11]; //inna
		unsigned char	*mpCDAGetFeedbackDetailToSplit[11];	// inna
		unsigned char	*mpCDAGetRecomputedFeedbackScore[11];

		unsigned char	*mpCDAAddGiftOccasion;
		unsigned char	*mpCDADeleteGiftOccasion;
		unsigned char	*mpCDAGetNextGiftOccasionId;
		unsigned char	*mpCDAGetSingleGiftOccasion;

		unsigned char	*mpCDAGetFeedbackListFromFeedbackList;
		unsigned char	*mpCDAGetFeedbackListSize;
		unsigned char	*mpCDAAddFeedbackList;
		unsigned char	*mpCDAUpdateFeedbackList;
		unsigned char	*mpCDAInvalidateFeedbackList;

		unsigned char	*mpCDAGetFeedbackDetailFromList[11];
		unsigned char	*mpCDAGetFeedbackDetailFromListMinimal[11];

		unsigned char	*mpCDAGetManyItemsForAuctionEnd;
		unsigned char	*mpCDAGetManyEndedItemsForAuctionEnd;
		// Gallery
		unsigned char	*mpCDAGetGalleryChangedItem;
		unsigned char	*mpCDAAppendGalleryChangedItem;
		unsigned char	*mpCDASetGalleryChangedItemState;
		unsigned char	*mpCDADeleteGalleryChangedItem;
		unsigned char	*mpCDAGetCurrentGallerySequence;
		unsigned char	*mpCDAGetNextGallerySequence;
		unsigned char	*mpCDAGetCurrentGalleryReadSequence;
		unsigned char	*mpCDAGetNextGalleryReadSequence;
		unsigned char	*mpCDASetItemGalleryInfo;
		unsigned char	*mpCDAGetItemGalleryInfo;
		unsigned char	*mpCDAGetGallerySequenceRange;

		// Trust and safety
		unsigned char	*mpCDAGetAuctionIds;
		unsigned char	*mpCDAGetAuctionsWon;
		unsigned char	*mpCDAGetAuctionsBidOn;
		unsigned char	*mpCDAGetAuctionsWithRetractions;
		unsigned char	*mpCDAGetAuctionIdsEnded;
		unsigned char	*mpCDAGetAuctionsWonEnded;
		unsigned char	*mpCDAGetAuctionsBidOnEnded;
		unsigned char	*mpCDAGetAuctionsWithRetractionsEnded;
		unsigned char	*mpCDAGetSellersOfAuctionsEnded;
		unsigned char	*mpCDAGetSellersOfAuctions;
		unsigned char	*mpCDAGetOurAuctionsBidOnByUs;
		unsigned char	*mpCDAGetOurAuctionsBidOnByUsEnded;
		unsigned char	*mpCDAGetShillInformationForOurAuctionsEnded;
		unsigned char	*mpCDAGetBidsFromTheseUsersEnded;
		unsigned char	*mpCDAGetShillInformationForOurAuctions;
		unsigned char	*mpCDAGetBidsFromTheseUsers;
		unsigned char	*mpCDAClearAllDeadbeatItems;
		unsigned char	*mpCDADeleteDeadbeatItem;
		unsigned char	*mpCDAGetSingleDeadbeatItem;
		unsigned char	*mpCDAGetSingleDeadbeatItemRowId;
		unsigned char	*mpCDAGetDeadbeatItem;
		unsigned char	*mpCDAGetDeadbeatItemWithRowId;
		unsigned char	*mpCDAAddDeadbeatItem;
		unsigned char	*mpCDAGetDeadbeatScore;
		unsigned char	*mpCDAGetDeadbeatItemsByBidderId;
		unsigned char	*mpCDAGetDeadbeatItemsBySellerId;
		unsigned char	*mpCDAGetAllDeadbeatItems;
		unsigned char	*mpCDAAddDeadbeat;
		unsigned char	*mpCDAGetDeadbeat;
		unsigned char	*mpCDAGetAllDeadbeats;
		unsigned char	*mpCDASetDeadbeatScore;
		unsigned char	*mpCDASetCreditRequestCount;
		unsigned char	*mpCDAGetCreditRequestCount;
		unsigned char	*mpCDASetWarningCount;
		unsigned char	*mpCDAGetWarningCount;
		unsigned char	*mpCDAGetDeadbeatItemCountByBidderId;
		unsigned char	*mpCDAGetDeadbeatItemCountBySellerId;
		unsigned char	*mpCDAInvalidateDeadbeatScore;
		unsigned char	*mpCDAValidateDeadbeatScore;
		unsigned char	*mpCDAInvalidateCreditRequestCount;
		unsigned char	*mpCDAValidateCreditRequestCount;
		unsigned char	*mpCDAInvalidateWarningCount;
		unsigned char	*mpCDAValidateWarningCount;
		unsigned char	*mpCDAGetDeadbeatItemsWarnedCountByBidderId;
		unsigned char	*mpCDAGetDeadbeatItemsNotWarned;
		unsigned char	*mpCDAUpdateDeadbeatItem;
		unsigned char	*mpCDASetDeadbeatItemWarned;

		//item and item_info merge special: double writes
		unsigned char	*mpCDAAddItemBilledToItems;
		unsigned char	*mpCDAAddItemNoticedToItems;
		unsigned char	*mpCDAGetNoticeTime;
		unsigned char	*mpCDAGetNoticeTimeByRowId;
		unsigned char	*mpCDAGetNoticeTimeEnded;	
		unsigned char	*mpCDACopyItemInfo;
		unsigned char	*mpCDADeleteItemInfo;
		unsigned char	*mpCDAGetThisBids;
		unsigned char	*mpCDACopyBids;
		unsigned char	*mpCDADeleteBids;
		unsigned char	*mpCDADeleteBid;
		unsigned char	*mpCDACopyItems;
		unsigned char	*mpDeleteItemInfo;	
		unsigned char	*mpCDAInterimBalance;
		unsigned char	*mpCDAGetItemDescArc;
		unsigned char	*mpCDAGetThisBidsFromEnded;
		unsigned char	*mpCDADeleteBidsFromEnded;

		//Gurinder - cursors for reinstate item
		//copy
		unsigned char	*mpCDACopyArcItems;				
		unsigned char	*mpCDACopyArcBids;		
		//delete 
		unsigned char	*mpCDADeleteArcItems;
		unsigned char	*mpCDADeleteArcItemDesc;		
		unsigned char	*mpCDADeleteArcBids;
				
		//rec count
		unsigned char	*mpCDASQLGetThisArcBids;
		unsigned char	*mpCDASQLGetThisItem;
		unsigned char	*mpCDASQLGetThisItemBids;
		unsigned char	*mpCDASQLGetThisItemDesc;
		//Gurinder's code ends here

		//dutch gms for reports
		unsigned char	*mpCDAUpdateDutchGMS;
		unsigned char	*mpCDAUpdateDutchGMSByRowId;

		// for regional auctions
		unsigned char	*mpCDAGetAllRegions;
		unsigned char	*mpCDAGetRegionZips;

		//
		// cursors for queries on ebay_categories
		//
		unsigned char *mpCDAMaskCategory;
		unsigned char *mpCDAGetMaskedCategories;
		unsigned char *mpCDAFlagCategory;
		unsigned char *mpCDAGetFlaggedCategories;

		//
		// cursors for queries on and changes to ebay_category_filters
		//
		unsigned char *mpCDAAddCategoryFilter;
		unsigned char *mpCDADeleteCategoryFilter;
		unsigned char *mpCDAUpdateCategoryFilter;
		unsigned char *mpCDAGetCategoryFilter;
		unsigned char *mpCDAGetCategoryFiltersByCategoryId;
		unsigned char *mpCDAGetCategoryFilterCountByCategoryId;
		unsigned char *mpCDAGetCategoryFiltersByFilterId;
		unsigned char *mpCDAGetCategoryFilterCountByFilterId;
		unsigned char *mpCDAGetAllCategoryFilters;

		//
		// cursors for queries on and changes to ebay_filters
		//
		unsigned char *mpCDAAddFilter;
		unsigned char *mpCDADeleteFilterById;
		unsigned char *mpCDADeleteFilterByName;
		unsigned char *mpCDAUpdateFilterById;
		unsigned char *mpCDAUpdateFilterByName;
		unsigned char *mpCDAGetFilterById;
		unsigned char *mpCDAGetFilterByName;
		unsigned char *mpCDAGetFiltersByCategoryId;
		unsigned char *mpCDAGetAllFilters;
		unsigned char *mpCDAGetNextFilterId;
		unsigned char *mpCDAGetMaxFilterId;

		//
		// cursors for queries on and changes to ebay_category_messages
		//
		unsigned char *mpCDAAddCategoryMessage;
		unsigned char *mpCDADeleteCategoryMessage;
		unsigned char *mpCDAUpdateCategoryMessage;
		unsigned char *mpCDAGetCategoryMessage;
		unsigned char *mpCDAGetCategoryMessagesByCategoryId;
		unsigned char *mpCDAGetCategoryMessageCountByCategoryId;
		unsigned char *mpCDAGetCategoryMessagesByMessageId;
		unsigned char *mpCDAGetCategoryMessageCountByMessageId;
		unsigned char *mpCDAGetAllCategoryMessages;

		//
		// cursors for queries on and changes to ebay_filter_messages
		//
		unsigned char *mpCDAAddMinFilterMessage;
		unsigned char *mpCDADeleteMinFilterMessage;
		unsigned char *mpCDAUpdateMinFilterMessage;
		unsigned char *mpCDAGetMinFilterMessage;
		unsigned char *mpCDAGetMinFilterMessagesByFilterId;
		unsigned char *mpCDAGetMinFilterMessageCountByFilterId;
		unsigned char *mpCDAGetMinFilterMessagesByMessageId;
		unsigned char *mpCDAGetMinFilterMessageCountByMessageId;

		//
		// cursors for queries on and changes to ebay_messages
		//
		unsigned char *mpCDAAddMessage;
		unsigned char *mpCDADeleteMessageById;
		unsigned char *mpCDADeleteMessageByName;
		unsigned char *mpCDAUpdateMessageById;
		unsigned char *mpCDAUpdateMessageByName;
		unsigned char *mpCDAGetMessageById;
		unsigned char *mpCDAGetMessageLengthById;
		unsigned char *mpCDAGetMessageByName;
		unsigned char *mpCDAGetMessageByCategoryIdAndMessageType;
		unsigned char *mpCDAGetMessageLengthByName;
		unsigned char *mpCDAGetMessageLengthByCategoryIdAndMessageType;
		unsigned char *mpCDAGetMaxTextLen;
		unsigned char *mpCDAGetMaxTextLenByCategoryId;
		unsigned char *mpCDAGetMaxTextLenByFilterId;
		unsigned char *mpCDAGetMaxTextLenByMessageType;
		unsigned char *mpCDAGetMaxTextLenByCategoryIdAndMessageType;
		unsigned char *mpCDAGetAllMessages;
		unsigned char *mpCDAGetMessagesByCategoryId;
		unsigned char *mpCDAGetMessagesByFilterId;
		unsigned char *mpCDAGetMessagesByMessageType;
		unsigned char *mpCDAGetMessagesByCategoryIdAndMessageType;
		unsigned char *mpCDAGetNextMessageId;
		unsigned char *mpCDAGetMaxMessageId;

		//
		// cursors for queries on and changes to ebay_items and
		// ebay_items_blocked
		//
		unsigned char *mpCDAAddBlockedItem;
		unsigned char *mpCDAAddBlockedItemDesc;
		unsigned char *mpCDADeleteBlockedItem;
		unsigned char *mpCDADeleteBlockedItemDesc;
		unsigned char *mpCDAUpdateBlockedItem;
		unsigned char *mpCDAUpdateBlockedItemDesc;
		unsigned char *mpCDAGetBlockedItem;
		unsigned char *mpCDAGetBlockedItemRowId;
		unsigned char *mpCDAGetBlockedItemDesc;
		unsigned char *mpCDAGetBlockedItemDescLen;
		unsigned char *mpCDAGetBlockedItemCountById;
		unsigned char *mpCDAGetBlockedItemCount;
		unsigned char *mpCDAGetBlockedItemWithDescription;
		unsigned char *mpCDAGetBlockedItemWithDescriptionRowId;
		unsigned char *mpCDACopyItemsToEnded;	
		unsigned char *mpCDAGetThisBidsEnded;
		unsigned char *mpCDADeleteBidsEnded;
		unsigned char *mpCDACopyBidsToEnded;

		// And for deleteing bidder and seller lists
		unsigned char *mpCDADeleteBidderList;
		unsigned char *mpCDADeleteSellerList;

		// Cursors in clsDatabaseOracleLocations.cpp
		unsigned char *mpCDALocationsIsValidZip;
		unsigned char *mpCDALocationsIsValidAC;
		unsigned char *mpCDALocationsIsValidCity;
		unsigned char *mpCDALocationsDoesACMatchZip;
		unsigned char *mpCDALocationsDoesACMatchState;
		unsigned char *mpCDALocationsDoesZipMatchState;
		unsigned char *mpCDALocationsDoesACMatchCity;
		unsigned char *mpCDALocationsDoesZipMatchCity;	
		unsigned char *mpCDALocationsDoesCityMatchState;
		unsigned char *mpCDALocationsGetLLForZip;
		unsigned char *mpCDALocationsGetLLForAC;
		
		// for admin gallery tool to access ebay_special_items table
		unsigned char	*mpCDAAddSpecialItem;
		unsigned char   *mpCDADeleteSpecialItem;
		unsigned char	*mpCDAFlushSpecialItem;

		unsigned char	*mpCDASetFeedbackScoreToUsers;
		//

		//
		//
		// cobrand header/footers
		//
		unsigned char *mpCDAGetSiteHeadersAndFooters;
		unsigned char *mpCDAGetSitePartnerHeadersAndFooters;
		unsigned char *mpCDAGetPartnerHeaderText;
		unsigned char *mpCDALoadSites;
		unsigned char *mpCDALoadPartners;
		unsigned char *mpCDAGetAllMinimalSites;
		// kakiyama 06/18/99
		unsigned char *mpCDAGetSiteParsedStringAndId;
		unsigned char *mpCDAGetPartnerParsedStringAndId;
		unsigned char *mpCDAGetForeignSites;
		unsigned char *mpCDAGetPartnersWithParsedString;
		unsigned char *mpCDAGetLocales;			// petra
		// nsacco 08/03/99
		unsigned char *mpCDALoadSite;

		// exchange rates and currencies
		unsigned char *mpCDAGetNumCurrencies;
		unsigned char *mpCDAGetRatesForCurrency;
		unsigned char *mpCDAInsertExchangeRate;
		unsigned char *mpCDAGetNumExchangeRates;

		//
		// cobrand ad descriptions
		//
		unsigned char *mpCDAAddCobrandAdDesc;
		unsigned char *mpCDAGetCobrandAdDescTextLenById;
		unsigned char *mpCDAGetCobrandAdDescTextLenByName;
		unsigned char *mpCDAGetCobrandAdDescText;
		unsigned char *mpCDAGetMaxCobrandAdDescTextLen;
		unsigned char *mpCDAGetCobrandAdDescById;
		unsigned char *mpCDAGetCobrandAdDescByName;
		unsigned char *mpCDALoadAllCobrandAdDescs;
		unsigned char *mpCDAUpdateCobrandAdDescById;
		unsigned char *mpCDAUpdateCobrandAdDescByName;
		unsigned char *mpCDADeleteCobrandAdDescById;
		unsigned char *mpCDADeleteCobrandAdDescByName;
		unsigned char *mpCDAGetNextCobrandAdDescId;

		//
		// cobrand ads
		//
		unsigned char *mpCDAAddCobrandAd;
		unsigned char *mpCDAGetCobrandAdsById;
		unsigned char *mpCDAGetCobrandAdsBySite;
		unsigned char *mpCDAGetCobrandAdsBySiteAndPartner;
		unsigned char *mpCDAGetCobrandAdsByPage;
		unsigned char *mpCDALoadAllCobrandAds;
		unsigned char *mpCDAUpdateCobrandAd;
		unsigned char *mpCDADeleteCobrandAd;

		//
		// announcement
		//
		unsigned char *mpCDAAllAnnouncementsBySiteAndPartner;
		unsigned char *mpCDAAllAnnouncements;

		// Oracle control blocks. These are blinded
		// as char *'s so code which includes this
		// header file doesn't have to know about
		// Oracle internals.
		//
		unsigned char	*mpHDA;
		unsigned char	*mpLDA;

		// internal variable to be used in complex transaction
		bool			mInTransaction;

		// Description Buffer
		unsigned char	*mpDescriptionBuffer;

		// Seller List Buffer
		unsigned char	*mpSellerListBuffer;
		int				mSellerListBufferSize;

		// Bidder List Buffer
		unsigned char	*mpBidderListBuffer;
		int				mBidderListBufferSize;

		// 'About Me' Buffer.
		unsigned char *mpUserPageBuffer;
		int				mUserPageBufferSize;

		// Feedback List buffer
		unsigned char	*mpFeedbackListBuffer;
		int				mFeedbackListBufferSize;


};

#endif /* CLSDATABASEORACLE_INCLUDED */
