/*	$Id: clsDatabaseOracleItems.cpp,v 1.20.2.10.2.3 1999/08/04 16:51:30 nsacco Exp $	*/
//
//	File:	clsDatabaseOracleItems.cc
//
//	Class:	clsDatabaseOracleItems
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 02/09/97 michael	- Created
//				- 06/13/97 tini     - split off item related calls from 
//                                    clsDatabaseOracle
//				- 07/30/97 wen		- added function to retrieve item count
//									  for a marketplace. Modified
//									  AdjustMarketPlaceItemCount() such that
//									  it adjusts the count in ebay_marketplaces_info
//				- 09/02/97 wen		- added a function to retrieve all active
//									  listing items
//				- 09/29/97 wen		- Set password in the item in GetItemWithDescription()
//				- 10/13/97 poon		- Added GetItemIdsVector
//				- 08/19/98 inna		- aded RemoveItem method to be used by admin
//				- 09/08/98 wen		- added GetHighTicketItems()
//				- 09/29/98 mila		- modified GetManyItemsForCreditBatch to retrieve
//									  values for all table columns
//				- 02/03/99 inna		- added functions needed by EOAStaet table, 
//									  chaged items not notices to use bind vars 
//                                    for date ranges	
//				- 04/12/99 mila		- added new methods for flagging, blocking, and
//									  reinstating items for Legal Buddies project
//				- 04/30/99 Gurinder - added a admin function to reinstate the item
//				- 05/20/99 jennifer - added functions for Gallery Admin Tool
//				- 07/15/99 nsacco	- added siteid
//				- 07/27/99 nsacco	- Added descLang, and new shipping options to AddItem, 
//										UpdateItem.


#include "eBayKernel.h"
#include "clsEOAState.h"

#include <string.h>
#include <stdio.h>
#include <fcntl.h>
#include <errno.h>
#include <time.h>
#include "clsEnvironment.h"


// Interesting defines
#define PARSE_NO_DEFER	0
#define PARSE_DEFER		1
#define PARSE_V7_LNG	2

#define ORA_ITEM_ARRAYSIZE 100
#define MAX_CATEGORY_ID	3000

#define	ITEM_COUNT_1998_04_26	10523315

//
// ClearAllItems
//

static char *SQL_DeleteAllItems =
	"delete from ebay_items";

static char *SQL_DeleteAllItemDesc =
    "delete from ebay_item_desc";

void clsDatabaseOracle::ClearAllItems()
{
// delete item description first
	OpenAndParse(&mpCDAOneShot, SQL_DeleteAllItemDesc);
	Execute();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	OpenAndParse(&mpCDAOneShot, SQL_DeleteAllItems);
	Execute();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	Commit();
	return;
}


//
// GetItem
//
// nsacco 07/27/99 added site_id, shipping_option, ship_region_flags, desc_lang
static const char *SQL_GetItem =
 "select	id,									\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			visitcount,							\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),		\
			ROWIDTOCHAR(rowid),					\
			icon_flags,							\
			gallery_url,						\
			gallery_type,						\
			gallery_state,						\
			country_id,							\
			currency_id,						\
			zip,								\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id								\
	from %s										\
	where	marketplace = :marketplace			\
	and		id = :itemid";

// nsacco 07/27/99 added site_id, shipping_option, ship_region_flags, desc_lang
static const char *SQL_GetItemWithRowId =
 "select	id,									\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			visitcount,							\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),		\
			ROWIDTOCHAR(rowid)					\
			icon_flags,							\
			gallery_url,						\
			gallery_type,						\
			gallery_state,						\
			country_id,							\
			currency_id,						\
			zip,								\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id								\
	from %s										\
	where	rowid = CHARTOROWID(:thisrow)";

// nsacco 07/27/99 added site_id, shipping_option, ship_region_flags, desc_lang
static const char *SQL_GetItemEnded =
 "select	id,									\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			visitcount,							\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),		\
			ROWIDTOCHAR(rowid),					\
			icon_flags,							\
			gallery_url,						\
			gallery_type,						\
			gallery_state,						\
			country_id,							\
			currency_id,						\
			zip,								\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id								\
	from ebay_items_ended						\
	where	marketplace = :marketplace			\
	and		id = :itemid";

// nsacco 07/27/99 added site_id, shipping_option, ship_region_flags, desc_lang
static const char *SQL_GetItemWithRowIdEnded =
 "select	id,									\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			visitcount,							\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),		\
			ROWIDTOCHAR(rowid)					\
			icon_flags,							\
			gallery_url,						\
			gallery_type,						\
			gallery_state,						\
			country_id,							\
			currency_id,						\
			zip,								\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id								\
	from ebay_items_ended						\
	where	rowid = CHARTOROWID(:thisrow)";

// nsacco 07/27/99 added site_id, shipping_option, ship_region_flags, desc_lang
bool clsDatabaseOracle::GetItem(int marketplace,
								int id,
							    clsItem *pItem,
								char *pRowId,
								time_t delta,
								bool ended,
								bool blocked)	// if true, get from ebay_items_blocked
{
	// Temporary slots for things to live in
	int					itemId;
	AuctionTypeEnum		saleType;	
	char				title[255];
	char				location[255];
	int					seller;
	int					owner;
	int					password;
	int					category;
	int					quantity;
	int					bidcount;
	char				sale_start[32];
	time_t				sale_start_time;
	char				sale_end[32];
	time_t				sale_end_time;
	int					sale_status;
	float				current_price;
	float				start_price;
	float				reserve_price;
	int					high_bidder;
	sb2					high_bidder_ind;

	char				featured[2];
	char				superFeatured[2];
	char				boldTitle[2];
	char				privateSale[2];
	char				registeredOnly[2];
	char				host[65];
	sb2					host_ind;
	char				*pHost;
	int					visitcount;

	char				pictureURL[256];
	sb2					pictureURL_ind;
	char				*pPictureURL;

	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBold;
	bool				isPrivate;
	bool				isRegisteredOnly;

	char				*pLocation;
	char				*pTitle;

	time_t				last_modified_time;
	char				last_modified[32];

	char				itemrowid[20];
	char				*pItemRowId;
//	clsCategories*		pCategories;

	bool	userowid;
	char				iconFlags[3];
	sb2					iconFlags_ind;
	char				*pIconFlags;

	char				galleryURL[256];
	sb2					galleryURL_ind;
	char				*pGalleryURL;

	int					galleryType;
	sb2					galleryType_ind;
	int					countryId;
	sb2					countryId_ind;

	int					currencyId;
	sb2					currencyId_ind;

	int					galleryState;
	sb2					galleryState_ind;

	char				zip[EBAY_MAX_ZIP_SIZE + 1];
	sb2					zip_ind;
	char				*pZip;

	// nsacco 07/27/99 new params
	int					shipping_option;
	long				ship_region_flags;
	int					desc_lang;
	int					site_id;
	
	char				cTableName[64];
	unsigned int		tableNameLen = 0;

	char *				pSQLStatement = NULL;
	unsigned char **	ppCursor = NULL;

	// let's get a clsCategories
//	pCategories = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCategories();

	// need check for valid rowid format
	userowid = IsValidRowIdFormat(pRowId);

	// decide if we're going to use the active or blocked items
	// XXX we need this only if we're not getting ended items
	if (blocked)
		strcpy(cTableName, "ebay_items_blocked");
	else
		strcpy(cTableName, "ebay_items");

	tableNameLen = strlen(cTableName);

	if (userowid)
	{
		// use rowid to query
		if (ended)
			OpenAndParse(&mpCDAGetSingleItemRowIdEnded, SQL_GetItemWithRowIdEnded);
		else
		{
			// format the SQL statement with the table name
			pSQLStatement = new char[strlen(SQL_GetItemWithRowId) + tableNameLen + 1];
			sprintf(pSQLStatement, SQL_GetItemWithRowId, cTableName);

			if (blocked)
				ppCursor = &mpCDAGetBlockedItemRowId;
			else
				ppCursor = &mpCDAGetSingleItemRowId;

			OpenAndParse(ppCursor, pSQLStatement);
		}

		Bind(":thisrow", pRowId);
	}
	else
	{
		if (ended) 
			// use item id to query
			OpenAndParse(&mpCDAGetSingleItemEnded, SQL_GetItemEnded);
		else
		{
			// format the SQL statement with the table name
			pSQLStatement = new char[strlen(SQL_GetItem) + tableNameLen + 1];
			sprintf(pSQLStatement, SQL_GetItem, cTableName);

			if (blocked)
				ppCursor = &mpCDAGetBlockedItem;
			else
				ppCursor = &mpCDAGetSingleItem;

			// use item id to query
			OpenAndParse(ppCursor, pSQLStatement);
		}

		Bind(":itemid", &id);
		Bind(":marketplace", &marketplace);
	}

	// Bind the input variable

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, &itemId);
	Define(2, (int *)&saleType);
	Define(3, title, sizeof(title));
	Define(4, location, sizeof(location));
	Define(5, &seller);
	Define(6, &owner);
	Define(7, &password);
	Define(8, &category);
	Define(9, &quantity);
	Define(10, &bidcount);
	Define(11, sale_start, sizeof(sale_start));
	Define(12, sale_end, sizeof(sale_end));
	Define(13, &sale_status);
	Define(14, &current_price);
	Define(15, &start_price);
	Define(16, &reserve_price);
	Define(17, &high_bidder, &high_bidder_ind);
	Define(18, featured, sizeof(featured));
	Define(19, superFeatured, sizeof(superFeatured));
	Define(20, boldTitle, sizeof(boldTitle));
	Define(21, privateSale, sizeof(privateSale));
	Define(22, registeredOnly, sizeof(registeredOnly));
	Define(23, host, sizeof(host), &host_ind);
	Define(24, &visitcount);
	Define(25, pictureURL, sizeof(pictureURL),
			&pictureURL_ind);
	Define(26, last_modified, sizeof(last_modified));
	Define(27, itemrowid, sizeof(itemrowid));
	Define(28, iconFlags, sizeof(iconFlags), &iconFlags_ind);
	Define(29, (char *)&galleryURL, sizeof(galleryURL),
			&galleryURL_ind);
	Define(30, &galleryType, &galleryType_ind);
	Define(31, &galleryState, &galleryState_ind);
	Define(32, &countryId, &countryId_ind);
	Define(33, &currencyId, &currencyId_ind);
	Define(34, zip, sizeof(zip), &zip_ind);

	// nsacco 07/27/99 new params
	Define(35, &shipping_option);
	Define(36, &ship_region_flags);
	Define(37, &desc_lang);
	Define(38, &site_id);

	// Fetch
	ExecuteAndFetch();

	// if we can't find it with rowid, or id mismatched,
	// get it with item id
	if (userowid && (CheckForNoRowsFound() || (id != itemId)))
	{
		if (ended)
		{
			// log debugging statement
			if (gApp->GetEnvironment())
				EDEBUG('*', "Rowid error from %s rowid: %s item %d Error %s\n",
						gApp->GetEnvironment()->GetBrowser(), pRowId, itemId,
						(CheckForNoRowsFound() ? "Not Found" : "Not Matched"));
			else
				EDEBUG('*', "Rowid error from unknown browser rowid: %s item %d Error %s\n",
						pRowId, itemId,
						(CheckForNoRowsFound() ? "Not Found" : "Not Matched"));

			Close(&mpCDAGetSingleItemRowIdEnded);
			SetStatement(NULL);
			return GetItem(marketplace, id, pItem);
			// get it with item id.
		}
		else
		{
			Close(ppCursor);
			SetStatement(NULL);
			delete[] pSQLStatement;
			pSQLStatement = NULL;
			// if we're not getting blocked items, try getting ended items by row id
			if (!blocked)
				return GetItem(marketplace, id, pItem, pRowId, delta, true); 
			else
				return false;
		}
	}

	// if no item found, then return
	if (CheckForNoRowsFound())
	{
		if (ended)
		{
			Close(&mpCDAGetSingleItemEnded);
			SetStatement(NULL);
			return false;
		}
		else
		{
			Close(ppCursor);
			SetStatement(NULL);
			delete[] pSQLStatement;
			pSQLStatement = NULL;
			if (!blocked)
				return GetItem(marketplace, id, pItem, pRowId, delta, true); // try the ended now
			else
				return false;
		}
	}

	// Now everything is where it's supposed
	// to be. Let's make copies of the title
	// and location for the item
	pTitle		= new char[strlen(title) + 1];
	strcpy(pTitle, (char *)title);
	pLocation	= new char[strlen(location) + 1];
	strcpy(pLocation, (char *)location);

	// Time Conversions
	ORACLE_DATEToTime(sale_start, &sale_start_time);
	ORACLE_DATEToTime(sale_end, &sale_end_time);
	ORACLE_DATEToTime(last_modified, &last_modified_time);
	// Handle null high bidder
	if (high_bidder_ind == -1)
		high_bidder = 0;

	// Transform flags.
	if (featured[0] == '1')
		isFeatured	= true;
	else
		isFeatured	= false;

	if (superFeatured[0] == '1')
		isSuperFeatured	= true;
	else
		isSuperFeatured	= false;
	
	if (boldTitle[0] == '1')
		isBold	= true;
	else
		isBold	= false;

	if (privateSale[0] == '1')
		isPrivate	= true;
	else
		isPrivate	= false;

	if (registeredOnly[0] == '1')
		isRegisteredOnly	= true;
	else
		isRegisteredOnly	= false;

	if (host_ind == -1)
	{
		pHost	= NULL;
	}
	else
	{
		pHost	= new char[strlen(host) + 1];
		strcpy(pHost, host);
	}
	
	if (pictureURL_ind == -1)
	{
		pPictureURL	= NULL;
	}
	else
	{
		pPictureURL	= new char[strlen(pictureURL) + 1];
		strcpy(pPictureURL, pictureURL);
	}

	pItemRowId	= new char[strlen(itemrowid) + 1];
	strcpy(pItemRowId, itemrowid);

	if (iconFlags_ind == -1)
	{
		pIconFlags	= NULL;
	}
	else
	{
		pIconFlags	= new char[strlen(iconFlags) + 1];
		strcpy(pIconFlags, iconFlags);
	}

	if (galleryURL_ind == -1)
	{
		pGalleryURL	= NULL;
	}
	else
	{
		pGalleryURL	= new char[strlen(galleryURL) + 1];
		strcpy(pGalleryURL, galleryURL);
	}
	// Handle null 
	if (galleryType_ind == -1)
		galleryType = NoneGallery;

	// Handle null 
	if (galleryState_ind == -1)
		galleryState = kGalleryNotProcessed;

	if (countryId_ind == -1)
		countryId = Country_None;

	if (currencyId_ind == -1)
		currencyId = Currency_USD;

	if (zip_ind == -1)
	{
		pZip = NULL;
	}
	else
	{
		pZip	= new char[strlen(zip) + 1];
		strcpy(pZip, zip);
	}

	// nsacco 07/27/99 fill in new params
	if (shipping_option == -1)
	{
		if (password & ShippingInternationally)
		{
			// handle old items
			shipping_option = Worldwide;
			password = password & ~ShippingInternationally;
		}
		else
		{
			shipping_option = SiteOnly;
		}
	}
	
	if (ship_region_flags == -1)
	{
		ship_region_flags = ShipRegion_None;
	}

	if (desc_lang == -1)
	{
		desc_lang = English;
	}

	if (site_id == -1)
	{
		site_id = SITE_EBAY_MAIN;
	}

	// Fill in the item
	// nsacco 07/27/99 new params
	pItem->Set(marketplace,
			   id,
			   saleType,
			   pTitle,
			   NULL,
			   pLocation,
			   seller,
			   owner,
			   category,
			   bidcount,
			   quantity,
			   sale_start_time,
			   sale_end_time,
			   sale_status,
			   current_price,
			   start_price,
			   reserve_price,
			   high_bidder,
			   isFeatured,
			   isSuperFeatured,
			   isBold,
			   isPrivate, 
			   isRegisteredOnly,
			   pHost,
			   visitcount,
			   pPictureURL,
			   NULL,			// category name
			   NULL,			// seller user id
			   0,				// seller user state
			   0,				// seller user flags
			   NULL,			// high bidder user id
			   0,				// high bidder user state
			   0,				// high bidder user flags
			   0,				// seller feedback score
			   0,				// high bidder feedback score
			   0,				// seller id last change
			   0,				// high bidder id last change
			   last_modified_time,
			   NULL,
			   NULL,
			   password,
			   pItemRowId,
			   delta,
			   pIconFlags,
			   pGalleryURL,
			   (GalleryTypeEnum) galleryType,
			   (GalleryResultCode) galleryState,
			   countryId,
			   currencyId,
			   ended, /* if the item came from the ended table - useful later */
			   pZip,
			   Currency_USD,	// billing currency
			   shipping_option,
			   ship_region_flags,
			   desc_lang,
			   site_id
			   );


	if (userowid)
	{
		if (ended)
			Close(&mpCDAGetSingleItemRowIdEnded);
		else
			Close(ppCursor);
	}
	else
	{
		if (ended)
			Close(&mpCDAGetSingleItemEnded);
		else
			Close(ppCursor);
	}

	SetStatement(NULL);

	if (!ended)
		delete[] pSQLStatement;

	return true;
}

bool clsDatabaseOracle::GetItem(int id,
							    clsItem *pItem,
								char *pRowId,
								time_t delta,
								bool ended /* = false */,
								bool blocked /* = false */)
{
	return GetItem(gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
				   id,
				   pItem, 
				   pRowId, 
				   delta,
				   ended,
				   blocked);
}

// GetItemDescription

static const char *SQL_GetItemDescLen =
 "select	description_len						\
	from %s										\
	where	marketplace = :marketplace			\
	and		id = :itemid";

static const char *SQL_GetItemDesc =
 "select	description							\
	from %s										\
	where	marketplace = :marketplace			\
	and		id = :itemid";

static const char *SQL_GetItemDescLenEnded =
 "select	description_len						\
	from ebay_item_desc_ended					\
	where	marketplace = :marketplace			\
	and		id = :itemid";

static const char *SQL_GetItemDescEnded =
 "select	description							\
	from ebay_item_desc_ended					\
	where	marketplace = :marketplace			\
	and		id = :itemid";

bool clsDatabaseOracle::GetItemDescription(int marketplace,
								int id,
							    clsItem *pItem,
								bool blocked /* = false */)
{
	int					description_len = 0;
	unsigned char		*pDescription;
	bool				ended = pItem->GetEnded();

	char				cTableName[64];
	unsigned int		tableNameLen = 0;

	char *				pSQLStatement = NULL;
	unsigned char **	ppCursor = NULL;


	if (blocked)
		strcpy(cTableName, "ebay_item_desc_blocked");
	else
		strcpy(cTableName, "ebay_item_desc");

	tableNameLen = strlen(cTableName);

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	if (ended)
		OpenAndParse(&mpCDAGetItemDescLenEnded, SQL_GetItemDescLenEnded);
	else
	{
		// format the SQL statement with the table name
		pSQLStatement = new char[strlen(SQL_GetItemDescLen) + tableNameLen + 1];
		sprintf(pSQLStatement, SQL_GetItemDescLen, cTableName);

		if (blocked)
			ppCursor = &mpCDAGetBlockedItemDescLen;
		else
			ppCursor = &mpCDAGetItemDescLen;
		OpenAndParse(ppCursor, pSQLStatement);
	}

	// Bind the input variable
	Bind(":itemid", &id);
	Bind(":marketplace", &marketplace);

	// Define the output
	Define(1, &description_len);

	// Fetch
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		if (ended)
			Close(&mpCDAGetItemDescLenEnded);
		else
			Close(ppCursor);
		SetStatement(NULL);
		if (!ended)
			delete [] pSQLStatement;
		return false;
	}

	// Now we allocate space for description based on length
	pDescription	= new unsigned char[description_len + 1];

	if (ended)
	{
		Close(&mpCDAGetItemDescLenEnded);
		SetStatement(NULL);
		OpenAndParse(&mpCDAGetItemDescEnded, SQL_GetItemDescEnded);
	}
	else
	{
		Close(ppCursor);
		SetStatement(NULL);

		// do some clean up
		delete [] pSQLStatement;

		// format a new SQL statement with the table name
		pSQLStatement = new char[strlen(SQL_GetItemDesc) + tableNameLen + 1];
		sprintf(pSQLStatement, SQL_GetItemDesc, cTableName);

		if (blocked)
			ppCursor = &mpCDAGetBlockedItemDesc;
		else
			ppCursor = &mpCDAGetItemDesc;

		// get actual description
		OpenAndParse(ppCursor, pSQLStatement);
	}

	// Bind the input variable
	Bind(":itemid", &id);
	Bind(":marketplace", &marketplace);
	// Get it!
	// Define the output - this won't work either!
	DefineLongRaw(1, pDescription, description_len);
	ExecuteAndFetch();

	*(pDescription + description_len)	= '\0';

	pItem->SetDesc(marketplace,
					id,
					(char *)pDescription);
	if (ended)
		Close(&mpCDAGetItemDescEnded);
	else
		Close(ppCursor);
	SetStatement(NULL);

	if (!ended)
		delete[] pSQLStatement;

	return true;
}

//
// GetItemWithDescription
// - gets the full blown item, sellers, etc, and its description.
//
// nsacco 07/27/99 added site_id, shipping_option, ship_region_flags, desc_lang
static const char *SQL_GetItemWithDescription =
" select /*+ index(items items_pk ) index(feedback1 feedback_pk2 ) */ items.id,	"
"			items.sale_type,							"
"			items.title,								"
"			items.location,								"
"			items.seller,								"
"			items.owner,								"
"			items.password,								"
"			items.category,								"
"			items.quantity,								"
"			items.bidcount,								"
"			TO_CHAR(items.sale_start,					"
"						'YYYY-MM-DD HH24:MI:SS'),		"
"			TO_CHAR(items.sale_end,						"
"						'YYYY-MM-DD HH24:MI:SS'),		"
"			items.sale_status,							"
"			items.current_price,						"
"			items.start_price,							"
"			items.reserve_price,						"
"			items.high_bidder,							"
"			items.featured,								"
"			items.super_featured,						"
"			items.bold_title,							"
"			items.private_sale,							"
"			items.registered_only,						"
"			items.host,									"
"			items.visitcount,							"
"			items.picture_url,							"
"			TO_CHAR(items.last_modified,				"
"				'YYYY-MM-DD HH24:MI:SS'),				"
"			items.icon_flags,							"
"			items.gallery_url,							"
"			items.gallery_type,							"
"			items.country_id,							"
"			items.currency_id,							"
"			items.zip,									"
"			items.shipping_option,						"
"			items.ship_region_flags,					"
"			items.desc_lang,							"
"			items.site_id,								"
"			itemdesc.description_len,					"
"			itemdesc.description,						"
"			users1.userid,								"
"			users1.email,								"
"			users1.user_state,							"
"			users1.flags,								"
"			users2.userid,								"
"			users2.email,								"
"			users2.user_state,							"
"			users2.flags,								"
"			((((categories.name4 || ':') ||				"
"					categories.name3 || ':') ||			"
"						categories.name2 || ':') ||		"
"							categories.name1 || ':') ||	"
"								categories.name,		"
"			feedback1.score,							"
"			feedback2.score,							"
"			categories.adult,							"
"			TO_CHAR(users1.userid_last_change,			"
"				'YYYY-MM-DD HH24:MI:SS'),				"
"			TO_CHAR(users2.userid_last_change,			"
"				'YYYY-MM-DD HH24:MI:SS'),				"
"			ROWIDTOCHAR(items.rowid)					"
"		from %s items,									"
"		 %s itemdesc,									"
"		 ebay_users users1,								"
"		 ebay_users users2,								"
"		 ebay_categories categories,					"
"		 ebay_feedback feedback1,						"
"		 ebay_feedback feedback2						"
"	where	items.marketplace = :marketplace			"
"	and		items.id = :itemid							"
"	and		items.marketplace = itemdesc.marketplace (+)"
"	and		items.id = itemdesc.id (+)					"
"	and		items.seller = users1.id					"
"	and		items.high_bidder = users2.id (+)			"
"	and		categories.marketplace = :marketplace		"
"	and		items.category = categories.id				"
"	and		items.seller = feedback1.id (+)				"
"	and		items.high_bidder = feedback2.id (+)";

// nsacco 07/27/99 added site_id, shipping_option, ship_region_flags, desc_lang
static const char *SQL_GetItemWithDescriptionRowId =
" select /*+ index(items items_pk ) index(feedback1 feedback_pk2 ) */ items.id,	"
"			items.sale_type,							"
"			items.title,								"
"			items.location,								"
"			items.seller,								"
"			items.owner,								"
"			items.password,								"
"			items.category,								"
"			items.quantity,								"
"			items.bidcount,								"
"			TO_CHAR(items.sale_start,					"
"						'YYYY-MM-DD HH24:MI:SS'),		"
"			TO_CHAR(items.sale_end,						"
"						'YYYY-MM-DD HH24:MI:SS'),		"
"			items.sale_status,							"
"			items.current_price,						"
"			items.start_price,							"
"			items.reserve_price,						"
"			items.high_bidder,							"
"			items.featured,								"
"			items.super_featured,						"
"			items.bold_title,							"
"			items.private_sale,							"
"			items.registered_only,						"
"			items.host,									"
"			items.visitcount,							"
"			items.picture_url,							"
"			TO_CHAR(items.last_modified,				"
"				'YYYY-MM-DD HH24:MI:SS'),				"
"			items.icon_flags,							"
"			items.gallery_type,							"
"	        items.country_id,							"
"	        items.currency_id,							"
"			items.zip,									"
"			items.shipping_option,						"
"			items.ship_region_flags,					"
"			items.desc_lang,							"
"			items.site_id,								"
"			itemdesc.description_len,					"
"			itemdesc.description,						"
"			users1.userid,								"
"			users1.email,								"
"			users1.user_state,							"
"			users1.flags,								"
"			users2.userid,								"
"			users2.email,								"
"			users2.user_state,							"
"			users2.flags,								"
"			((((categories.name4 || ':') ||				"
"					categories.name3 || ':') ||			"
"						categories.name2 || ':') ||		"
"							categories.name1 || ':') ||	"
"								categories.name,		"
"			feedback1.score,							"
"			feedback2.score,							"
"			categories.adult,							"
"			TO_CHAR(users1.userid_last_change,			"
"				'YYYY-MM-DD HH24:MI:SS'),				"
"			TO_CHAR(users2.userid_last_change,			"
"				'YYYY-MM-DD HH24:MI:SS'),				"
"			ROWIDTOCHAR(items.rowid)					"
"		from %s items,									"
"		 %s itemdesc,									"
"		 ebay_users users1,								"
"		 ebay_users users2,								"
"		 ebay_categories categories,					"
"		 ebay_feedback feedback1,						"
"		 ebay_feedback feedback2						"
"	where	items.rowid = CHARTOROWID(:thisrow)			"
"	and		items.marketplace = itemdesc.marketplace (+)"
"	and		items.id = itemdesc.id (+)					"
"	and		items.seller = users1.id					"
"	and		items.high_bidder = users2.id (+)			"
"	and		categories.marketplace = items.marketplace	"
"	and		items.category = categories.id				"
"	and		items.seller = feedback1.id (+)				"
"	and		items.high_bidder = feedback2.id (+)";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetItemWithDescriptionEnded =
" select /*+  index(items items_ended_pk )  index(feedback1 feedback_pk2 ) */ items.id,	"
"			items.sale_type,							"
"			items.title,								"
"			items.location,								"
"			items.seller,								"
"			items.owner,								"
"			items.password,								"
"			items.category,								"
"			items.quantity,								"
"			items.bidcount,								"
"			TO_CHAR(items.sale_start,					"
"						'YYYY-MM-DD HH24:MI:SS'),		"
"			TO_CHAR(items.sale_end,						"
"						'YYYY-MM-DD HH24:MI:SS'),		"
"			items.sale_status,							"
"			items.current_price,						"
"			items.start_price,							"
"			items.reserve_price,						"
"			items.high_bidder,							"
"			items.featured,								"
"			items.super_featured,						"
"			items.bold_title,							"
"			items.private_sale,							"
"			items.registered_only,						"
"			items.host,									"
"			items.visitcount,							"
"			items.picture_url,							"
"			TO_CHAR(items.last_modified,				"
"				'YYYY-MM-DD HH24:MI:SS'),				"
"			items.icon_flags,							"
"			items.gallery_url,							"
"			items.gallery_type,							"
"			items.country_id,							"
"			items.currency_id,							"
"			items.zip,									"
"			items.shipping_option,						"
"			items.ship_region_flags,					"
"			items.desc_lang,							"
"			items.site_id,								"
"			itemdesc.description_len,					"
"			itemdesc.description,						"
"			users1.userid,								"
"			users1.email,								"
"			users1.user_state,							"
"			users1.flags,								"
"			users2.userid,								"
"			users2.email,								"
"			users2.user_state,							"
"			users2.flags,								"
"			((((categories.name4 || ':') ||				"
"					categories.name3 || ':') ||			"
"						categories.name2 || ':') ||		"
"							categories.name1 || ':') ||	"
"								categories.name,		"
"			feedback1.score,							"
"			feedback2.score,							"
"			categories.adult,							"
"			TO_CHAR(users1.userid_last_change,			"
"				'YYYY-MM-DD HH24:MI:SS'),				"
"			TO_CHAR(users2.userid_last_change,			"
"				'YYYY-MM-DD HH24:MI:SS'),				"
"			ROWIDTOCHAR(items.rowid)					"
"		from ebay_items_ended items,					"
"		 ebay_item_desc_ended itemdesc,					"
"		 ebay_users users1,								"
"		 ebay_users users2,								"
"		 ebay_categories categories,					"
"		 ebay_feedback feedback1,						"
"		 ebay_feedback feedback2						"
"	where	items.marketplace = :marketplace			"
"	and		items.id = :itemid							"
"	and		items.marketplace = itemdesc.marketplace (+)"
"	and		items.id = itemdesc.id (+)					"
"	and		items.seller = users1.id					"
"	and		items.high_bidder = users2.id (+)			"
"	and		categories.marketplace = :marketplace		"
"	and		items.category = categories.id				"
"	and		items.seller = feedback1.id (+)				"
"	and		items.high_bidder = feedback2.id (+)";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetItemWithDescriptionRowIdEnded =
" select /*+  index(items items_ended_pk )  index(feedback1 feedback_pk2 ) */ items.id,	"
"			items.sale_type,							"
"			items.title,								"
"			items.location,								"
"			items.seller,								"
"			items.owner,								"
"			items.password,								"
"			items.category,								"
"			items.quantity,								"
"			items.bidcount,								"
"			TO_CHAR(items.sale_start,					"
"						'YYYY-MM-DD HH24:MI:SS'),		"
"			TO_CHAR(items.sale_end,						"
"						'YYYY-MM-DD HH24:MI:SS'),		"
"			items.sale_status,							"
"			items.current_price,						"
"			items.start_price,							"
"			items.reserve_price,						"
"			items.high_bidder,							"
"			items.featured,								"
"			items.super_featured,						"
"			items.bold_title,							"
"			items.private_sale,							"
"			items.registered_only,						"
"			items.host,									"
"			items.visitcount,							"
"			items.picture_url,							"
"			TO_CHAR(items.last_modified,				"
"				'YYYY-MM-DD HH24:MI:SS'),				"
"			items.icon_flags,							"
"			items.gallery_type,							"
"	        items.country_id,							"
"			items.currency_id,							"
"			items.zip,									"
"			items.shipping_option,						"
"			items.ship_region_flags,					"
"			items.desc_lang,							"
"			items.site_id,								"
"			itemdesc.description_len,					"
"			itemdesc.description,						"
"			users1.userid,								"
"			users1.email,								"
"			users1.user_state,							"
"			users1.flags,								"
"			users2.userid,								"
"			users2.email,								"
"			users2.user_state,							"
"			users2.flags,								"
"			((((categories.name4 || ':') ||				"
"					categories.name3 || ':') ||			"
"						categories.name2 || ':') ||		"
"							categories.name1 || ':') ||	"
"								categories.name,		"
"			feedback1.score,							"
"			feedback2.score,							"
"			categories.adult,							"
"			TO_CHAR(users1.userid_last_change,			"
"				'YYYY-MM-DD HH24:MI:SS'),				"
"			TO_CHAR(users2.userid_last_change,			"
"				'YYYY-MM-DD HH24:MI:SS'),				"
"			ROWIDTOCHAR(items.rowid)					"
"		from ebay_items_ended items,					"
"		 ebay_item_desc_ended itemdesc,					"
"		 ebay_users users1,								"
"		 ebay_users users2,								"
"		 ebay_categories categories,					"
"		 ebay_feedback feedback1,						"
"		 ebay_feedback feedback2						"
"	where	items.rowid = CHARTOROWID(:thisrow)			"
"	and		items.marketplace = itemdesc.marketplace (+)"
"	and		items.id = itemdesc.id (+)					"
"	and		items.seller = users1.id					"
"	and		items.high_bidder = users2.id (+)			"
"	and		categories.marketplace = items.marketplace	"
"	and		items.category = categories.id				"
"	and		items.seller = feedback1.id (+)				"
"	and		items.high_bidder = feedback2.id (+)";

// nsacco 07/27/99 added site_id, shipping_option, ship_region_flags, desc_lang
bool clsDatabaseOracle::GetItemWithDescription(
								int marketplace,
								int id,
							    clsItem *pItem,
								char *pRowId,
								time_t delta,
								bool ended,
								bool blocked)
{
	// Buffer Size
	int					description_buffer_size = 16384;

	// Temporary slots for things to live in
	int					itemid;
	AuctionTypeEnum		saleType;
	char				title[255];
	char				location[255];
	int					seller;
	int					owner;
	int					password;
	int					category;
	int					quantity;
	int					bidcount;
	char				sale_start[32];
	time_t				sale_start_time;
	char				sale_end[32];
	time_t				sale_end_time;
	int					sale_status;
	float				current_price;
	float				start_price;
	float				reserve_price;
	int					high_bidder;
	sb2					high_bidder_ind;

	char				featured[2];
	char				superFeatured[2];
	char				boldTitle[2];
	char				privateSale[2];
	char				registeredOnly[2];
	char				host[65];
	sb2					host_ind;
	char				*pHost;
	int					visitcount;
	char				pictureURL[256];
	sb2					pictureURL_ind;
	char				*pPictureURL;

	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBold;
	bool				isPrivate;
	bool				isRegisteredOnly;

	time_t				last_modified_time;
	char				last_modified[32];

	int					descriptionLen;
	sb2					descriptionLen_ind;
	sb2					description_ind;

	char				categoryName[255];
	sb2					categoryName_ind;
	char				sellerUserId[255];
	char				sellerEmail[255];
	sb2					sellerUserId_ind;
	int					sellerUserState;
	sb2					sellerUserState_ind;
	char				highBidderUserId[255];
	char				highBidderEmail[255];
	sb2					highBidderUserId_ind;
	int					highBidderUserState;
	sb2					highBidderUserState_ind;
	int					sellerFeedbackScore;
	sb2					sellerFeedbackScore_ind;
	int					highBidderFeedbackScore;
	sb2					highBidderFeedbackScore_ind;

	int					sellerUserFlags;
	int					highBidderUserFlags;

	char				*pLocation;
	char				*pTitle;
	char				*pSellerUserId;
	char				*pHighBidderUserId;
	char				*pCategoryName;
	unsigned char		*pDescription;
	char				adult[2];

	time_t				seller_id_last_modified_time;
	char				seller_id_last_modified[32];
	sb2					seller_last_change_ind;

	time_t				highbidder_id_last_modified_time;
	char				highbidder_id_last_modified[32];
	sb2					highbidder_last_change_ind;

	char				itemrowid[20];
	char				*pItemRowId;
	int	rc;

	bool	userowid;
	char				iconFlags[3];
	char				*pIconFlags;
	sb2					iconFlags_ind;

	char				galleryURL[256];
	sb2					galleryURL_ind;
	char				*pGalleryURL;

	int					galleryType;
	sb2					galleryType_ind;
	int					countryId;
	sb2					countryId_ind;

	int					currencyId;
	sb2					currencyId_ind;

	//zip code
	char				zip[EBAY_MAX_ZIP_SIZE + 1];
	sb2					zip_ind;
	char				*pZip;

	// nsacco 07/27/99
	int					shipping_option;
	long				ship_region_flags;
	int					desc_lang;
	int					site_id;
	
	char				cItemsTableName[64];
	unsigned int		itemsTableNameLen = 0;

	char				cItemDescTableName[64];
	unsigned int		itemDescTableNameLen = 0;

	char *				pSQLStatement = NULL;
	unsigned char **	ppCursor = NULL;


	if (blocked)
	{
		strcpy(cItemsTableName, "ebay_items_blocked");
		strcpy(cItemDescTableName, "ebay_item_desc_blocked");
	}
	else
	{
		strcpy(cItemsTableName, "ebay_items");
		strcpy(cItemDescTableName, "ebay_item_desc");
	}

	itemsTableNameLen = strlen(cItemsTableName);
	itemDescTableNameLen = strlen(cItemDescTableName);

	// Let's see if we have out description buffer
	if (mpDescriptionBuffer	== NULL)
		mpDescriptionBuffer	= new unsigned char[description_buffer_size];

	// need check for valid rowid format?
	userowid = IsValidRowIdFormat(pRowId);

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	if (userowid)
	{
		if (ended)
			OpenAndParse(&mpCDAGetSingleItemWithDescriptionRowIdEnded, 
				 SQL_GetItemWithDescriptionRowIdEnded);
		else
		{
			pSQLStatement = new char[strlen(SQL_GetItemWithDescriptionRowId)
									+ itemsTableNameLen
									+ itemDescTableNameLen
									+ 1];
			sprintf(pSQLStatement,
					SQL_GetItemWithDescriptionRowId,
					cItemsTableName,
					cItemDescTableName);

			if (blocked)
				ppCursor = &mpCDAGetBlockedItemWithDescriptionRowId;
			else
				ppCursor = &mpCDAGetSingleItemWithDescriptionRowId;

			OpenAndParse(ppCursor, pSQLStatement);
		}

		Bind(":thisrow", pRowId);
	}
	else
	{
		if (ended)
			OpenAndParse(&mpCDAGetSingleItemWithDescriptionEnded, 
					 SQL_GetItemWithDescriptionEnded);
		else
		{
			pSQLStatement = new char[strlen(SQL_GetItemWithDescription)
									+ itemsTableNameLen
									+ itemDescTableNameLen
									+ 1];
			sprintf(pSQLStatement,
					SQL_GetItemWithDescription,
					cItemsTableName,
					cItemDescTableName);

			if (blocked)
				ppCursor = &mpCDAGetBlockedItemWithDescription;
			else
				ppCursor = &mpCDAGetSingleItemWithDescription;

			OpenAndParse(ppCursor, pSQLStatement);
			// Bind the input variable
		}

		Bind(":itemid", &id);
		Bind(":marketplace", &marketplace);
	}


	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, &itemid);
	Define(2, (int *)&saleType);
	Define(3, title, sizeof(title));
	Define(4, location, sizeof(location));
	Define(5, &seller);
	Define(6, &owner);
	Define(7, &password);
	Define(8, &category);
	Define(9, &quantity);
	Define(10, &bidcount);
	Define(11, sale_start, sizeof(sale_start));
	Define(12, sale_end, sizeof(sale_end));
	Define(13, &sale_status);
	Define(14, &current_price);
	Define(15, &start_price);
	Define(16, &reserve_price);
	Define(17, &high_bidder, &high_bidder_ind);
	Define(18, featured, sizeof(featured));
	Define(19, superFeatured, sizeof(superFeatured));
	Define(20, boldTitle, sizeof(boldTitle));
	Define(21, privateSale, sizeof(privateSale));
	Define(22, registeredOnly, sizeof(registeredOnly));
	Define(23, host, sizeof(host), &host_ind);
	Define(24, &visitcount);
	Define(25, pictureURL, sizeof(pictureURL),
		   &pictureURL_ind);
	Define(26, last_modified, sizeof(last_modified));
	Define(27, iconFlags, sizeof(iconFlags), &iconFlags_ind);
	Define(28, galleryURL, sizeof(galleryURL),
				&galleryURL_ind);
	Define(29, &galleryType, &galleryType_ind);
	Define(30, &countryId, &countryId_ind);
	Define(31, &currencyId, &currencyId_ind);
	Define(32, zip, sizeof(zip), &zip_ind);

	// nsacco 07/27/99
	Define(33, &shipping_option);
	Define(34, &ship_region_flags);
	Define(35, &desc_lang);
	Define(36, &site_id);

	Define(37, &descriptionLen, &descriptionLen_ind);
	DefineLongRaw(38, mpDescriptionBuffer, 
					  description_buffer_size,
					  &description_ind);
	Define(39, sellerUserId, sizeof(sellerUserId),
			   &sellerUserId_ind);
	Define(40, sellerEmail, sizeof (sellerEmail));
	Define(41, &sellerUserState, 
			   &sellerUserState_ind);
	Define(42, &sellerUserFlags);
	Define(43, highBidderUserId, sizeof(highBidderUserId),
			   &highBidderUserId_ind);
	Define(44, highBidderEmail, sizeof (highBidderEmail));
	Define(45, &highBidderUserState, 
			   &highBidderUserState_ind);
	Define(46, &highBidderUserFlags);
	Define(47, categoryName, sizeof(categoryName),
			   &categoryName_ind);
	Define(48, &sellerFeedbackScore, 
			   &sellerFeedbackScore_ind);
	Define(49, &highBidderFeedbackScore,
			   &highBidderFeedbackScore_ind);
	Define(50, adult, sizeof(adult));
	Define(51, seller_id_last_modified, sizeof(seller_id_last_modified), &seller_last_change_ind);
	Define(52, highbidder_id_last_modified, sizeof(highbidder_id_last_modified), &highbidder_last_change_ind);
	Define(53, itemrowid, sizeof(itemrowid));

	// If we get any oracle error with rowid, we want to junk the results and
	// get it with item id;
	// If we don't use rowid, we let check(rc) do its job.
	if (userowid)
	{
		// if execution of the query fails, we get it by item id
		rc	= oexec((struct cda_def *)mpCDACurrent);
		if (rc < 0 || rc >= 4)
		{
			ocan((struct cda_def *)mpCDACurrent);
			if (ended)
				Close(&mpCDAGetSingleItemWithDescriptionRowIdEnded);
			else
				Close(ppCursor);
			SetStatement(NULL);
			if (!ended && pSQLStatement != NULL)
			{
				delete [] pSQLStatement;
				pSQLStatement = NULL;
			}
			// get it with item number
			return GetItemWithDescription(marketplace, id, pItem);
		}

		// otherwise we fetch the results and compare
		rc = ofetch((struct cda_def *)mpCDACurrent);

		// if we can't find it with rowid, or id mismatched,
		// get it with item id
		if (CheckForNoRowsFound() || (id != itemid))
		{
			if (ended)
			{
				// log debugging statement
				if (gApp->GetEnvironment())
					EDEBUG('*', "Rowid error from %s rowid: %s item %d Error %s\n",
						gApp->GetEnvironment()->GetBrowser(), pRowId, itemid,
						(CheckForNoRowsFound() ? "Not Found" : "Not Matched"));
				else
					EDEBUG('*', "Rowid error from unknown browser rowid: %s item %d Error %s\n",
							pRowId, itemid,
							(CheckForNoRowsFound() ? "Not Found" : "Not Matched"));

				Close(&mpCDAGetSingleItemWithDescriptionRowIdEnded);
				SetStatement(NULL);

				// get it with item number
				return GetItemWithDescription(marketplace, id, pItem);
			}
			else
			{
				Close(ppCursor);
				SetStatement(NULL);
				if (pSQLStatement != NULL)
				{
					delete [] pSQLStatement;
					pSQLStatement = NULL;
				}
				if (!blocked)
					return GetItemWithDescription(marketplace, id, pItem, pRowId, delta, true);
				else
					return false;
			}

			Close(ppCursor);
			SetStatement(NULL);

			// get it with item number
			return GetItemWithDescription(marketplace, id, pItem);
		}
	}
	else
	{ // not using rowid
		ExecuteAndFetch();

		// if no item found, then return
		if (CheckForNoRowsFound())
		{
			if (ended)
			{
				Close(&mpCDAGetSingleItemWithDescriptionEnded);
				SetStatement(NULL);
				return false;
			}
			else
			{
				Close(ppCursor);
				SetStatement(NULL);
				if (pSQLStatement != NULL)
				{
					delete [] pSQLStatement;
					pSQLStatement = NULL;
				}
				if (!blocked)
					return GetItemWithDescription(marketplace, id, pItem, NULL, delta, true);
				else
					return false;
			}
		}
	}

	// Now everything is where it's supposed
	// to be. Let's make copies of the title
	// and location for the item
	pTitle		= new char[strlen(title) + 1];
	strcpy(pTitle, (char *)title);
	pLocation	= new char[strlen(location) + 1];
	strcpy(pLocation, (char *)location);

	// Time Conversions
	ORACLE_DATEToTime(sale_start, &sale_start_time);
	ORACLE_DATEToTime(sale_end, &sale_end_time);
	ORACLE_DATEToTime(last_modified, &last_modified_time);
	if (seller_last_change_ind != -1)
		ORACLE_DATEToTime(seller_id_last_modified, &seller_id_last_modified_time);
	else
		seller_id_last_modified_time = 0;
	if (highbidder_last_change_ind != -1)
		ORACLE_DATEToTime(highbidder_id_last_modified, &highbidder_id_last_modified_time);
	else
		highbidder_id_last_modified_time = 0;

	// Handle null high bidder
	if (high_bidder_ind == -1)
		high_bidder = 0;

	// Transform flags.
	if (featured[0] == '1')
		isFeatured	= true;
	else
		isFeatured	= false;

	if (superFeatured[0] == '1')
		isSuperFeatured	= true;
	else
		isSuperFeatured	= false;

	if (boldTitle[0] == '1')
		isBold	= true;
	else
		isBold	= false;

	if (privateSale[0] == '1')
		isPrivate	= true;
	else
		isPrivate	= false;

	if (registeredOnly[0] == '1')
		isRegisteredOnly	= true;
	else
		isRegisteredOnly	= false;


	// Handle other Nulls
	if (host_ind == -1)
	{
		pHost	= NULL;
	}
	else
	{
		pHost	= new char[strlen(host) + 1];
		strcpy(pHost, host);
	}
	
	if (pictureURL_ind == -1)
	{
		pPictureURL	= NULL;
	}
	else
	{
		pPictureURL	= new char[strlen(pictureURL) + 1];
		strcpy(pPictureURL, pictureURL);
	}

	if (iconFlags_ind == -1)
	{
		pIconFlags	= NULL;
	}
	else
	{
		pIconFlags	= new char[strlen(iconFlags) + 1];
		strcpy(pIconFlags, iconFlags);
	}

	if (galleryURL_ind == -1)
	{
		pGalleryURL	= NULL;
	}
	else
	{
		pGalleryURL	= new char[strlen(galleryURL) + 1];
		strcpy(pGalleryURL, galleryURL);
	}

	// Handle null gellerType
	if (galleryType_ind == -1)
		galleryType = NoneGallery;

	if (countryId_ind == -1)
		countryId = Country_None;

	if (currencyId_ind == -1)
		currencyId = Currency_USD;
	//zip code
	if (zip_ind == -1)
	{
		pZip = NULL;
	}
	else
	{
		pZip	= new char[strlen(zip) + 1];
		strcpy(pZip, zip);
	}

	// nsacco 07/27/99 handle nulls for new params
	if (shipping_option == -1)
	{
		if (password & ShippingInternationally)
		{
			// handle old items
			shipping_option = Worldwide;
			password = password & ~ShippingInternationally;
		}
		else
		{
			shipping_option = SiteOnly;
		}
	}

	if (ship_region_flags == -1)
	{
		ship_region_flags = ShipRegion_None;
	}

	if (desc_lang == -1)
	{
		desc_lang = English;
	}

	if (site_id == -1)
	{
		site_id = SITE_EBAY_MAIN;
	}


	if (categoryName_ind == -1)
		categoryName[0] = '\0';

	pCategoryName	= new char[strlen(categoryName) + 1];
	strcpy(pCategoryName, categoryName);

	if (sellerUserId_ind == -1)
		sellerUserId[0]	= '\0';

	pSellerUserId	= new char[strlen(sellerUserId) + 1];
	strcpy(pSellerUserId, sellerUserId);

	if (highBidderUserId_ind == -1)
		highBidderUserId[0] = '\0';
	
	pHighBidderUserId	= new char[strlen(highBidderUserId) + 1];
	strcpy(pHighBidderUserId, highBidderUserId);

	if (sellerFeedbackScore_ind == -1)
		sellerFeedbackScore = INT_MIN;

	if (highBidderFeedbackScore_ind == -1)
		highBidderFeedbackScore	= INT_MIN;

	pItemRowId	= new char[strlen(itemrowid) + 1];
	strcpy(pItemRowId, itemrowid);

	// Fill in the item
	// nsacco 07/27/99 added new params
	pItem->Set(marketplace,
			   id,
			   saleType,
			   pTitle,
			   NULL,
			   pLocation,
			   seller,
			   owner,
			   category,
			   bidcount,
			   quantity,
			   sale_start_time,
			   sale_end_time,
			   sale_status,
			   current_price,
			   start_price,
			   reserve_price,
			   high_bidder,
			   isFeatured,
			   isSuperFeatured,
			   isBold,
			   isPrivate, 
			   isRegisteredOnly,
			   pHost,
			   visitcount,
			   pPictureURL,
			   pCategoryName,
			   pSellerUserId,
			   sellerUserState,
			   sellerUserFlags,
			   pHighBidderUserId,
			   highBidderUserState,
			   highBidderUserFlags,
			   sellerFeedbackScore,
			   highBidderFeedbackScore,
			   seller_id_last_modified_time,
			   highbidder_id_last_modified_time,
			   last_modified_time,
			   sellerEmail,
			   highBidderEmail,
			   password,
			   pItemRowId,
			   delta,
			   pIconFlags,
			   pGalleryURL,
			   (GalleryTypeEnum) galleryType,
			   kGalleryNotProcessed,
			   countryId,
			   currencyId,
			   ended,
			   pZip,
			   Currency_USD,		// billing currency	
			   shipping_option,
			   ship_region_flags,
			   desc_lang,
			   site_id
			   );

	pItem->SetAdult(adult[0]);
	
	if (userowid)
	{
		if (ended)
		// Close the nose
			Close(&mpCDAGetSingleItemWithDescriptionRowIdEnded); // close fix
		else
			Close(ppCursor);
		SetStatement(NULL);
	}
	else
	{
		if (ended)
			Close(&mpCDAGetSingleItemWithDescriptionEnded);
		else
			Close(ppCursor);
		SetStatement(NULL);
	}

	if (!ended && pSQLStatement != NULL)
	{
		delete [] pSQLStatement;
		pSQLStatement = NULL;
	}

		// Now, get the description length and description
	if (description_ind != -1)
	{
		if (descriptionLen < description_buffer_size)
		{
			pDescription	= new unsigned char[descriptionLen + 1];
			memcpy(pDescription, mpDescriptionBuffer,
				   descriptionLen);
			*(pDescription + descriptionLen) = '\0';
			pItem->SetDesc(marketplace,
						   id, 
						   (char *)pDescription);
		}
		else
		{
			GetItemDescription(marketplace, id, pItem);
		}
	}
	else
		pItem->SetDesc(marketplace,
					   id, 
					   NULL);
	return true;
}


//
// GetItemsListedByUserCount
//
static const char *SQL_GetItemsListedByUserCount =
 "select	count(*)									\
	from ebay_items items								\
	where	items.marketplace = :marketplace			\
	and		items.seller = :seller						\
	and		items.sale_end > sysdate";

static const char *SQL_GetItemsListedByUserCountEnded =
 "select	count(*)									\
	from ebay_items_ended items							\
	where	items.marketplace = :marketplace			\
	and		items.seller = :seller						\
	and		items.sale_end > sysdate";

int clsDatabaseOracle::GetItemsListedByUserCount(MarketPlaceId marketplace,
												 int id)
{
	int		count = 0;
	int		countEnded = 0;

	OpenAndParse(&mpCDAOneShot,
				 SQL_GetItemsListedByUserCount);

	Bind(":marketplace", (int *)&marketplace);
	Bind(":seller", &id);

	Define(1, &count);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetItemsListedByUserCountEnded);

	Bind(":marketplace", (int *)&marketplace);
	Bind(":seller", &id);

	Define(1, &countEnded);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);
	count += countEnded;
	return count;
}



//
// GetItemsBidByUserCount
//
static const char *SQL_GetItemsBidByUserCount =
 "select	count(distinct bids.item_id)				\
	from ebay_bids bids,								\
		 ebay_items items								\
	where	bids.user_id = :id							\
	and		(bids.type = 1								\
			 or bids.type = 2)							\
	and		items.marketplace = :marketplace			\
	and		items.id = bids.item_id						\
	and		items.sale_end > sysdate";

static const char *SQL_GetItemsBidByUserCountEnded =
 "select	count(distinct bids.item_id)				\
	from ebay_bids_ended bids,								\
		 ebay_items_ended items								\
	where	bids.user_id = :id							\
	and		(bids.type = 1								\
			 or bids.type = 2)							\
	and		items.marketplace = :marketplace			\
	and		items.id = bids.item_id						\
	and		items.sale_end > sysdate";


int clsDatabaseOracle::GetItemsBidByUserCount(MarketPlaceId marketplace,
											  int id)
{
	int		count = 0;
	int countEnded = 0;
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetItemsBidByUserCount);

	Bind(":marketplace", (int *)&marketplace);
	Bind(":id", &id);

	Define(1, &count);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetItemsBidByUserCountEnded);

	Bind(":marketplace", (int *)&marketplace);
	Bind(":id", &id);

	Define(1, &countEnded);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);
	count += countEnded;
	return count;
}



// GetItemsHighBidByUser
//
//	This method gets all the items for which a user is
//	the high bidder.
//
//	*** NOTE ***
//	We don't use array fetch here because, well it
//	would be a nightmare, and I was too lazy to do
//	it ;-)
//	*** NOTE ***
//
static const char *SQL_GetItemsHighBidByUser =
 "select	items.id,									\
			items.title,								\
			items.quantity,								\
			items.bidcount,								\
			TO_CHAR(items.sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),		\
			TO_CHAR(items.sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),		\
			items.current_price,						\
			items.start_price,							\
			items.reserve_price,						\
			items.high_bidder,							\
			items.private_sale,							\
			users1.userid,								\
			users2.userid,								\
			items.category								\
	from ebay_items items,								\
		 ebay_users users1,								\
		 ebay_users users2								\
	where	items.high_bidder = :id						\
	and		items.marketplace = :marketplace			\
	and		items.private_sale <> '1'					\
	and		items.sale_end > sysdate					\
	and		items.seller = users1.id (+)				\
	and		items.high_bidder = users2.id (+)";

static const char *SQL_GetItemsHighBidByUserWithCompleted =
 "select	items.id,									\
			items.title,								\
			items.quantity,								\
			items.bidcount,								\
			TO_CHAR(items.sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),		\
			TO_CHAR(items.sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),		\
			items.current_price,						\
			items.start_price,							\
			items.reserve_price,						\
			items.high_bidder,							\
			items.private_sale,							\
			users1.userid,								\
			users2.userid,								\
			items.category								\
	from ebay_items items,								\
		 ebay_users users1,								\
		 ebay_users users2								\
	where	items.high_bidder = :id						\
	and		items.marketplace = :marketplace			\
	and		items.private_sale <> '1'					\
	and		items.seller = users1.id (+)				\
	and		items.high_bidder = users2.id (+)";

static const char *SQL_GetItemsHighBidByUserWithCompletedEnded =
 "select	items.id,									\
			items.title,								\
			items.quantity,								\
			items.bidcount,								\
			TO_CHAR(items.sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),		\
			TO_CHAR(items.sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),		\
			items.current_price,						\
			items.start_price,							\
			items.reserve_price,						\
			items.high_bidder,							\
			items.private_sale,							\
			users1.userid,								\
			users2.userid,								\
			items.category								\
	from ebay_items_ended items,								\
		 ebay_users users1,								\
		 ebay_users users2								\
	where	items.high_bidder = :id						\
	and		items.marketplace = :marketplace			\
	and		items.private_sale <> '1'					\
	and		items.seller = users1.id (+)				\
	and		items.high_bidder = users2.id (+)";

void clsDatabaseOracle::GetItemsHighBidByUser(
								MarketPlaceId marketplace,
								int id,
							    bool completed,
								ItemList *pItems,
								ItemListSortEnum SortCode /* = SortItemsByUnknown */, 
								bool ended)
{
	// Temporary slots for things to live in
	int					itemId;
	char				title[255];
	int					quantity;
	int					bidcount;
	char				sale_start[32];
	time_t				sale_start_time;
	char				sale_end[32];
	time_t				sale_end_time;
	float				current_price;
	float				start_price;
	float				reserve_price;
	int					high_bidder;
	sb2					high_bidder_ind;

	char				privateSale[2];
//	bool				isPrivate;

	char				sellerUserId[255];
	sb2					sellerUserId_ind;
	char				highBidderUserId[255];
	sb2					highBidderUserId_ind;

	char				*pTitle;
	char				*pSellerUserId;
	char				*pHighBidderUserId;

	int					category;

	// The item
	clsItem				*pItem;
//	clsItemPtr			*pItemPtr;

//	clsCategories*		pCategories = NULL;

	// let's get a clsCategories
//	pCategories = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCategories();


	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	if (!completed)
	{
		OpenAndParse(&mpCDAGetItemsHighBidByUser, 
					 SQL_GetItemsHighBidByUser);
	}
	else
	{
		if (ended)
			OpenAndParse(&mpCDAGetItemsHighBidByUserWithCompletedEnded,
					 SQL_GetItemsHighBidByUserWithCompletedEnded);
		else
			OpenAndParse(&mpCDAGetItemsHighBidByUserWithCompleted,
					 SQL_GetItemsHighBidByUserWithCompleted);
	}

	// Bind the input variable
	Bind(":marketplace", (int *)&marketplace);
	Bind(":id", &id);

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, &itemId);
	Define(2, title, sizeof(title));
	Define(3, &quantity);
	Define(4, &bidcount);
	Define(5, sale_start, sizeof(sale_start));
	Define(6, sale_end, sizeof(sale_end));
	Define(7, &current_price);
	Define(8, &start_price);
	Define(9, &reserve_price);
	Define(10, &high_bidder, &high_bidder_ind);
	Define(11, privateSale, sizeof(privateSale));
	Define(12, sellerUserId, sizeof(sellerUserId),
			   &sellerUserId_ind);
	Define(13, highBidderUserId, sizeof(highBidderUserId),
			   &highBidderUserId_ind);
	Define(14, &category);

	// Execute...
	Execute();

	// Loop around, fetching until we drop

	while(1)
	{
		Fetch();

		if (CheckForNoRowsFound())
		{
			if (completed)
			{
				if (ended)  // we already tried ended and didn't find anything
					break;
				else  
				{
					ended = true; // let's try ended
					Close(&mpCDAGetItemsHighBidByUserWithCompleted);
					SetStatement(NULL);
					GetItemsHighBidByUser(marketplace, id, completed, pItems, SortCode, ended);
					return;
				}
			}
			else
				break;
		}

		// Now everything is where it's supposed
		// to be. Let's make copies of the title
		// and location for the item
		pTitle		= new char[strlen(title) + 1];
		strcpy(pTitle, (char *)title);

		// Time Conversions
		ORACLE_DATEToTime(sale_start, &sale_start_time);
		ORACLE_DATEToTime(sale_end, &sale_end_time);

		// Handle null high bidder
		if (high_bidder_ind == -1)
			high_bidder = 0;

		// Transform flags.

//		if (privateSale[0] == '1')
//			isPrivate	= true;
//		else
//			isPrivate	= false;

		// Handle other Null
		if (sellerUserId_ind == -1)
			sellerUserId[0]	= '\0';

		pSellerUserId	= new char[strlen(sellerUserId) + 1];
		strcpy(pSellerUserId, sellerUserId);

		if (highBidderUserId_ind == -1)
			highBidderUserId[0] = '\0';
		
		pHighBidderUserId	= new char[strlen(highBidderUserId) + 1];
		strcpy(pHighBidderUserId, highBidderUserId);


		// Fill in the item
		// nsacco 07/27/99 added new params
		pItem	= new clsItem;
		pItem->Set(marketplace,
				   itemId,
				   AuctionUnknown,
				   pTitle,
				   NULL, //description
				   NULL, // location
				   id, // seller
				   id,  // owner
				   category,
				   bidcount,
				   quantity,
				   sale_start_time,
				   sale_end_time,
				   0, // status
				   current_price,
				   start_price,
				   reserve_price,
				   high_bidder,
				   false,  // featured
				   false,  // super featured
				   false,  // bold title
				   false,   // private
				   false,  // registered only
				   NULL,  // host
				   0,  // visit count
				   NULL,  // picure url
				   NULL,  // category name
				   pSellerUserId,  // seller userid
				   UserUnknown,  // seller user state
				   0,  // seller user flags
				   pHighBidderUserId, // high bidder userid
				   UserUnknown,  // high bidder user state
				   0,  // high bidder user flags
				   INT_MIN,  // seller feedback score
				   INT_MIN,  // high bidder feedback score
				   0,  // seller userid last change
				   0, // high bidder userid last change
				   (long)0,  // last modified
				   NULL, // seller email
				   NULL, // high bidder email
				   0, // password
				   NULL, // rowid
				   (long)0, //delta,
				   NULL, // icon flags
				   NULL, //gallery URL
				   NoneGallery, // GalleryTypeEnum
				   kGalleryNotProcessed, // Gallery result code
				   Country_None, // country id
				   Currency_USD, //currency id
				   ended,
				   NULL, // zip
				   Currency_USD,	// billing currency
				   SiteOnly, // shipping option
				   ShipRegion_None, // ship_region_flags
				   English, // desc_lang
				   SITE_EBAY_MAIN // site_id
				   ) ;

		pItems->push_back(clsItemPtr(pItem));
//		pItemPtr	= new clsItemPtr(pItem);
//		pItems->push_back(*pItemPtr);

	}

	// Clean up
	if (!completed)
		Close(&mpCDAGetItemsHighBidByUser);
	else
		if (ended)
			Close(&mpCDAGetItemsHighBidByUserWithCompletedEnded);
		else
			Close(&mpCDAGetItemsHighBidByUserWithCompleted);
	SetStatement(NULL);

	// Sort
	gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetItems()->mCurrentSortMode =
		SortCode;

	if (!pItems->empty())
		pItems->sort();

	if (!ended && completed)
		GetItemsHighBidByUser(marketplace, id, completed, pItems, SortCode, true);

	return;
}

//
// GetItemsActive
//
// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetItemsActive =
 "select /*+ index(ebay_items ebay_items_ending_index ) */	id,	\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			visitcount,							\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),		\
			icon_flags							\
			gallery_type,						\
			country_id,							\
			currency_id,						\
			zip,								\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id								\
	from ebay_items								\
	where	marketplace = :marketplace			\
	and		sale_end > 	TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')";	



//
// GetItemsFeatured
//
// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetItemsSuperFeatured =
 "select /*+ index(ebay_items ebay_items_ending_index ) */	id,	\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			visitcount,							\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),       \
			icon_flags,							\
			gallery_type,						\
			country_id,							\
			currency_id,						\
			zip,								\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id								\
	from ebay_items								\
	where	marketplace = :marketplace			\
	and		sale_end > 	TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		super_featured = '1'";	

//
// GetItemsHot
// get hotitemcount from MarketPlace
// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang
// nsacco 07/15/99 added site_id
static const char *SQL_GetItemsHot =
 "select /*+ index(ebay_items ebay_items_ending_index ) */	id,	\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			visitcount,							\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),		\
				icon_flags,						\
			gallery_type,						\
			country_id,							\
			currency_id,						\
			zip,								\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id								\
	from ebay_items								\
	where	marketplace = :marketplace			\
	and		sale_end > 	TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		bidcount > :hotcount";		

//
// GetItemsActive
//
// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang
// nsacco 07/15/99 added site_id
static const char *SQL_GetItemsCompleted =
 "select /*+ index(ebay_items ebay_items_ending_index ) */	id,	\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			visitcount,							\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),		\
				icon_flags,						\
			gallery_type,						\
			country_id,							\
			currency_id,						\
			zip,								\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id								\
	from ebay_items								\
	where	marketplace = :marketplace			\
	and		sale_end <= 	TO_DATE(:enddate,	\
				'YYYY-MM-DD HH24:MI:SS')";	


// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetItemsCompletedEnded =
 "select /*+ index(ebay_items_ended ebay_items_ending_end_idx ) */	id,	\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			visitcount,							\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),		\
				icon_flags,						\
			gallery_type,						\
			country_id,							\
			currency_id,						\
			zip,								\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id								\
	from ebay_items_ended						\
	where	marketplace = :marketplace			\
	and		sale_end <= 	TO_DATE(:enddate,	\
				'YYYY-MM-DD HH24:MI:SS')";	

//
// GetItemsEnding
//
// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetItemsEnding =
 "select /*+ index(ebay_items ebay_items_ending_index ) */	id,	\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			visitcount,							\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),		\
				icon_flags,						\
			gallery_type,						\
			country_id,							\
			currency_id,						\
			zip,								\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id								\
	from ebay_items								\
	where	marketplace = :marketplace			\
	and		sale_end > 	TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		sale_end <= TO_DATE(:endlimitdate,	\
				'YYYY-MM-DD HH24:MI:SS')";	

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetItemsEndingEnded =
 "select /*+ index(ebay_items_ended ebay_items_ending_end_idx ) */	id,	\
			sale_type,							\
			title,								\
			location,							\
			seller,								\
			owner,								\
			password,							\
			category,							\
			quantity,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			sale_status,						\
			current_price,						\
			start_price,						\
			reserve_price,						\
			high_bidder,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			private_sale,						\
			registered_only,					\
			host,								\
			visitcount,							\
			picture_url,						\
			TO_CHAR(last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),		\
				icon_flags,						\
			gallery_type,						\
			country_id,							\
			currency_id,						\
			zip,								\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id,							\
	from ebay_items_ended						\
	where	marketplace = :marketplace			\
	and		sale_end > 	TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		sale_end <= TO_DATE(:endlimitdate,	\
				'YYYY-MM-DD HH24:MI:SS')";	

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
void clsDatabaseOracle::GetItemsVector(MarketPlaceId marketplace, 
									   time_t enddate,
									   int hotcount,
									   int QueryCode, 
									   ItemVector *pvItems,
									   time_t end_limit_date /*=0*/,
									   ItemListSortEnum /* SortCode = SortItemsByUnknown */,
									   bool ended)
{
	// Temporary slots for things to live in
	int					id[ORA_ITEM_ARRAYSIZE];
	AuctionTypeEnum		saleType[ORA_ITEM_ARRAYSIZE];
	char				title[ORA_ITEM_ARRAYSIZE][255];
	char				location[ORA_ITEM_ARRAYSIZE][255];
	int					seller[ORA_ITEM_ARRAYSIZE];
	int					owner[ORA_ITEM_ARRAYSIZE];
	int					password[ORA_ITEM_ARRAYSIZE];
	int					category[ORA_ITEM_ARRAYSIZE];
	int					quantity[ORA_ITEM_ARRAYSIZE];
	int					bidcount[ORA_ITEM_ARRAYSIZE];
	char				sale_start[ORA_ITEM_ARRAYSIZE][32];
	time_t				sale_start_time;
	char				sale_end[ORA_ITEM_ARRAYSIZE][32];
	time_t				sale_end_time;
	int					sale_status[ORA_ITEM_ARRAYSIZE];
	float				current_price[ORA_ITEM_ARRAYSIZE];
	float				start_price[ORA_ITEM_ARRAYSIZE];
	float				reserve_price[ORA_ITEM_ARRAYSIZE];
	int					high_bidder[ORA_ITEM_ARRAYSIZE];
	sb2					high_bidder_ind[ORA_ITEM_ARRAYSIZE];

	char				featured[ORA_ITEM_ARRAYSIZE][2];
	char				superFeatured[ORA_ITEM_ARRAYSIZE][2];
	char				boldTitle[ORA_ITEM_ARRAYSIZE][2];
	char				privateSale[ORA_ITEM_ARRAYSIZE][2];
	char				registeredOnly[ORA_ITEM_ARRAYSIZE][2];
	char				host[ORA_ITEM_ARRAYSIZE][65];
	sb2					host_ind[ORA_ITEM_ARRAYSIZE];
	char				*pHost;
	int					visitcount[ORA_ITEM_ARRAYSIZE];
	char				pictureURL[ORA_ITEM_ARRAYSIZE][256];
	sb2					pictureURL_ind[ORA_ITEM_ARRAYSIZE];
	char				*pPictureURL;

	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBold;
	bool				isPrivate;
	bool				isRegisteredOnly;

	char				*pLocation;
	char				*pTitle;

	time_t				last_modified_time;
	char				last_modified[ORA_ITEM_ARRAYSIZE][32];
	char				cEndDate[64];
	struct tm			*pEndDate;	

	char				cEndLimitDate[64];
	struct tm			*pEndLimitDate;	

	clsItem				*pItem;

	int				rowsFetched;
	int				rc;
	int				i,n;
	char				iconFlags[ORA_ITEM_ARRAYSIZE][3];
	sb2					iconFlags_ind[ORA_ITEM_ARRAYSIZE];
	char				*pIconFlags;

	int					galleryType[ORA_ITEM_ARRAYSIZE];
	sb2					galleryType_ind[ORA_ITEM_ARRAYSIZE];

	clsCategories*		pCategories = NULL;
	int					countryId[ORA_ITEM_ARRAYSIZE];
	sb2					countryId_ind[ORA_ITEM_ARRAYSIZE];
	int					currencyId[ORA_ITEM_ARRAYSIZE];
	sb2					currencyId_ind[ORA_ITEM_ARRAYSIZE];

	//zip code
	char				zip[ORA_ITEM_ARRAYSIZE][EBAY_MAX_ZIP_SIZE + 1];
	sb2					zip_ind[ORA_ITEM_ARRAYSIZE];
	char				*pZip;

	// nsacco 07/27/99 new params
	int					shipping_option[ORA_ITEM_ARRAYSIZE];
	long				ship_region_flags[ORA_ITEM_ARRAYSIZE];
	int					desc_lang[ORA_ITEM_ARRAYSIZE];
	int					site_id[ORA_ITEM_ARRAYSIZE];

	// let's get a clsCategories
//	pCategories = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCategories();


	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)

	switch (QueryCode) 
	{
	case 1: 
		{
			OpenAndParse(&mpCDAOneShot, 
				 SQL_GetItemsActive);
			break;
		}
	case 2:
		{
			OpenAndParse(&mpCDAOneShot,
				SQL_GetItemsSuperFeatured);
			break;
		}
	case 3:
		{   // lookup marketplace hot count
			OpenAndParse(&mpCDAOneShot,
				SQL_GetItemsHot);
			Bind(":hotcount", &hotcount);
			break;
		}
	case 4:
		{   // lookup marketplace hot count  - put here something to go to ended too
			if (!ended)
				OpenAndParse(&mpCDAOneShot,
					SQL_GetItemsCompleted);
			else
				OpenAndParse(&mpCDAOneShot,
					SQL_GetItemsCompletedEnded);
			break;
		}
	case 5:
		{   // lookup items ending in three hours
			if (enddate >= end_limit_date)
				return;
			if (!ended)
				OpenAndParse(&mpCDAOneShot,
					SQL_GetItemsEnding);
			else
				OpenAndParse(&mpCDAOneShot,
					SQL_GetItemsEndingEnded);

			// get  the limit time
			pEndLimitDate	= localtime(&end_limit_date);
			TM_STRUCTToORACLE_DATE(pEndLimitDate, cEndLimitDate);

			// Bind the limit date
			Bind(":endlimitdate", cEndLimitDate);
			break;
 		}
	default:
		{ // currently assume 1
			OpenAndParse(&mpCDAOneShot,
				SQL_GetItemsActive);
			break;
		}
	}

	pEndDate	= localtime(&enddate);
	TM_STRUCTToORACLE_DATE(pEndDate,
						   cEndDate);

	// Bind the input variable
	Bind(":marketplace", (int *)&marketplace);
	Bind(":enddate", cEndDate);

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, id);
	Define(2, (int *)saleType);
	Define(3, title[0], sizeof(title[0]));
	Define(4, location[0], sizeof(location[0]));
	Define(5, seller);
	Define(6, owner);
	Define(7, password);
	Define(8, category);
	Define(9, quantity);
	Define(10, bidcount);
	Define(11, sale_start[0], sizeof(sale_start[0]));
	Define(12, sale_end[0], sizeof(sale_end[0]));
	Define(13, sale_status);
	Define(14, current_price);
	Define(15, start_price);
	Define(16, reserve_price);
	Define(17, high_bidder, high_bidder_ind);
	Define(18, featured[0], sizeof(featured[0]));
	Define(19, superFeatured[0], sizeof(superFeatured[0]));
	Define(20, boldTitle[0], sizeof(boldTitle[0]));
	Define(21, privateSale[0], sizeof(privateSale[0]));
	Define(22, registeredOnly[0], sizeof(registeredOnly[0]));
	Define(23, host[0], sizeof(host[0]), host_ind);
	Define(24, visitcount);
	Define(25, pictureURL[0], sizeof(pictureURL[0]), pictureURL_ind);
	Define(26, last_modified[0], sizeof(last_modified[0]));
	Define(27, iconFlags[0], sizeof(iconFlags[0]), iconFlags_ind);
	Define(28, galleryType, galleryType_ind);
	Define(29, countryId, countryId_ind);
	Define(30, currencyId, currencyId_ind);
	Define(31, zip[0], sizeof(zip[0]), zip_ind);
	
	// nsacco 07/27/99
	Define(32, shipping_option);
	Define(33, ship_region_flags);
	Define(34, desc_lang);
	Define(35, site_id);

	// Let's do the SQL
	Execute();

	if (CheckForNoRowsFound ())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot,true);
		SetStatement(NULL);
		if ((!ended) && ((QueryCode == 4) || (QueryCode == 5)))
			GetItemsVector(marketplace, enddate, hotcount, QueryCode, pvItems, 
					end_limit_date, SortItemsByUnknown, true);
		return;
	}

	// Fetch till we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_ITEM_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			// Now everything is where it's supposed
			// to be. Let's make copies of the title
			// and location for the item
			pTitle		= new char[strlen(title[i]) + 1];
			strcpy(pTitle, (char *)title[i]);
			pLocation	= new char[strlen(location[i]) + 1];
			strcpy(pLocation, (char *)location[i]);

			// Time Conversions
			ORACLE_DATEToTime(sale_start[i], &sale_start_time);
			ORACLE_DATEToTime(sale_end[i], &sale_end_time);
			ORACLE_DATEToTime(last_modified[i], &last_modified_time);
			// Handle null high bidder
			if (high_bidder_ind[i] == -1)
				high_bidder[i] = 0;

			// Transform flags.
			if (featured[i][0] == '1')
				isFeatured	= true;
			else
				isFeatured	= false;

			if (superFeatured[i][0] == '1')
				isSuperFeatured	= true;
			else
				isSuperFeatured	= false;

			if (boldTitle[i][0] == '1')
				isBold	= true;
			else
				isBold	= false;

			if (privateSale[i][0] == '1')
				isPrivate	= true;
			else
				isPrivate	= false;

			if (registeredOnly[i][0] == '1')
				isRegisteredOnly	= true;
			else
				isRegisteredOnly	= false;

			if (host_ind[i] == -1)
			{
				pHost	= NULL;
			}
			else
			{
				pHost	= new char[strlen(host[i]) + 1];
				strcpy(pHost, host[i]);
			}
			
			if (pictureURL_ind[i] == -1)
			{
				pPictureURL	= NULL;
			}
			else
			{
				pPictureURL	= new char[strlen(pictureURL[i]) + 1];
				strcpy(pPictureURL, pictureURL[i]);
			}
			if (iconFlags_ind[i] == -1)
			{
				pIconFlags	= NULL;
			}
			else
			{
				pIconFlags	= new char[strlen(iconFlags[i]) + 1];
				strcpy(pIconFlags, iconFlags[i]);
			}

			// Handle null gallery
			if (galleryType_ind[i] == -1)
				galleryType[i] = NoneGallery;

			if (countryId_ind[i] == -1)
				countryId[i] = Country_None;

			if (currencyId_ind[i] == -1)
				currencyId[i] = Currency_USD;

			if (zip_ind[i] == -1)
			{
				pZip	= NULL;
			}
			else
			{
				pZip	= new char[strlen(zip[i]) + 1];
				strcpy(pZip, zip[i]);
			}

			// nsacco 07/27/99
			// handle null new params
			if (shipping_option[i] == -1)
			{
				if (password[i] & ShippingInternationally)
				{
					// handle old items
					shipping_option[i] = Worldwide;
					password[i] = password[i] & ~ShippingInternationally;
				}
				else
				{
					shipping_option[i] = SiteOnly;
				}
			}

			if (ship_region_flags[i] == -1)
			{
				ship_region_flags[i] = ShipRegion_None;
			}

			if (desc_lang[i] == -1)
			{
				desc_lang[i] = English;
			}

			if (site_id[i] == -1)
			{
				site_id[i] = SITE_EBAY_MAIN;
			}

			// Fill in the item
			// nsacco 07/27/99 added new params
			pItem	= new clsItem;
			pItem->Set(marketplace,
					   id[i],
					   saleType[i],
					   pTitle,
					   NULL,
					   pLocation,
					   seller[i],
					   owner[i],
					   category[i],
					   bidcount[i],
					   quantity[i],
					   sale_start_time,
					   sale_end_time,
					   sale_status[i],
					   current_price[i],
					   start_price[i],
					   reserve_price[i],
					   high_bidder[i],
					   isFeatured,
					   isSuperFeatured,
					   isBold,
					   isPrivate, 
					   isRegisteredOnly,
					   pHost,
					   visitcount[i],
					   pPictureURL,
					   NULL,			// category name
					   NULL,			// seller user id
					   0,				// seller user state
					   0,				// seller user flags
					   NULL,			// high bidder user id
					   0,				// high bidder user state
					   0,				// high bidder user flags
					   0,				// seller feedback score
					   0,				// high bidder feedback score
					   0,				// seller id last change
					   0,				// high bidder id last change
					   last_modified_time,
					   NULL,			// seller email
					   NULL,			// high bidder email
					   0,				// password
					   0,				// rowid
					   0,				// delta
					   pIconFlags,
					   NULL,
					   (GalleryTypeEnum) galleryType[i],
					   kGalleryNotProcessed,
					   countryId[i],
					   currencyId[i],
					   ended,
					   pZip,
					   Currency_USD,	// billing currency
					   shipping_option[i],
					   ship_region_flags[i],
					   desc_lang[i],
					   site_id[i]);			// nsacco 07/27/99


			pvItems->push_back(pItem);
		}
	} while (!CheckForNoRowsFound());

	Close (&mpCDAOneShot);
	SetStatement(NULL);
	if ((!ended) && ((QueryCode == 4) || (QueryCode == 5)))
		GetItemsVector(marketplace, enddate, hotcount, QueryCode, pvItems, 
					end_limit_date, SortItemsByUnknown, true);

	return;
}



//
// GetNextItemId
//
// Retrieves the next availible item id. Whether
// this is done with a sequence, or a column in
// a table is irrelevant
//
static const char *SQL_GetNextItemId =
 "select ebay_items_sequence.nextval from dual";

int clsDatabaseOracle::GetNextItemId()
{
	int			nextId;

	// Not used often, so we don't need a persistent
	// cursor
	OpenAndParse(&mpCDAGetNextItemId, SQL_GetNextItemId);
	Define(1, &nextId);

	// Execute
	ExecuteAndFetch();

	// Close and Clean
	Close(&mpCDAGetNextItemId);
	SetStatement(NULL);

	return nextId;
}


//
// AddItemDesc
//

static const char*SQL_AddItemDesc =
 (char *)
 "insert into %s				\
	(	marketplace,			\
		id,						\
		description_len,		\
		description				\
	)							\
  values						\
  (		:marketplace,			\
		:id,					\
		:itemdesclen,			\
		:itemdesc				\
	)";

void clsDatabaseOracle::AddItemDesc(clsItem *pItem, bool blocked /* = false */)
{
	int					marketplaceid;
	int					id;
	// description length
	char				*pDescription;
	int					descriptionLen;

	char				cTableName[64];
	unsigned int		tableNameLen = 0;

	char *				pSQLStatement = NULL;
	unsigned char **	ppCursor = NULL;

	// Extract things from the item into our
	// local variables to prevent any casting
	// confusion
	marketplaceid	= pItem->GetMarketPlaceId();
	id				= pItem->GetId();

	pDescription		= pItem->GetDescription();
	if (pDescription)
		descriptionLen	= strlen(pDescription);
	else
	{
		pDescription	= "";
		descriptionLen	= 0;
	}

	if (blocked)
		strcpy(cTableName, "ebay_item_desc_blocked");
	else
		strcpy(cTableName, "ebay_item_desc");
	tableNameLen = strlen(cTableName);

	// construct the SQL statement with the proper table name
	pSQLStatement = new char[strlen(SQL_AddItemDesc) + tableNameLen + 1];
	sprintf(pSQLStatement, SQL_AddItemDesc, cTableName);

	if (blocked)
		ppCursor = &mpCDAAddBlockedItemDesc;
	else
		ppCursor = &mpCDAAddItemDesc;

	// We don't use this statement very often,
	// so the cursor's not persistent. Let's 
	// prepare the statement
	OpenAndParse(ppCursor, pSQLStatement);

	// Ok, let's do some binds
	// do this twice or use trick on description size to estimate,
	// then reallocate more if it does not fit.
	Bind(":marketplace", &marketplaceid);
	Bind(":id", &id);
	Bind(":itemdesclen", &descriptionLen);
	BindLongRaw(":itemdesc", 
				(unsigned char *)pDescription,
				descriptionLen);

	// Let's do it!
	Execute();

	// Commit
	Commit();

	// Free things
	Close(ppCursor);
	SetStatement(NULL);

	delete [] pSQLStatement;

	return;
}


//
// AddItem
//
// nsacco 07/27/99 added language and new shipping options
// nsacco 07/15/99 added siteid
static const char *SQL_AddItem =
 (char *)
 "insert into %s			\
	(	marketplace,		\
		id,					\
		sale_type,			\
		title,				\
		location,			\
		seller,				\
		owner,				\
		password,			\
		category,			\
		quantity,			\
		bidcount,			\
		created,			\
		sale_start,			\
		sale_end,			\
		sale_status,		\
		current_price,		\
		start_price,		\
		reserve_price,		\
		high_bidder,		\
		featured,			\
		super_featured,		\
		bold_title,			\
		private_sale,		\
		registered_only,	\
		host,				\
		visitcount,			\
		picture_url,		\
		last_modified,		\
		icon_flags,			\
		gallery_url,		\
		gallery_type,		\
		country_id,			\
		currency_id,		\
		zip,				\
		shipping_option,	\
		ship_region_flags,	\
		desc_lang,			\
		site_id  			\
	)						\
  values					\
    (	:marketplace,		\
		:itemid,			\
		:saletype,			\
		:itemtitle,			\
		:loc,				\
		:seller,			\
		:owner,				\
		:password,			\
		:category,			\
		:quantity,			\
		:bidcount,			\
		TO_DATE(:created,	\
				'YYYY-MM-DD HH24:MI:SS'),	\
		TO_DATE(:sales,		\
				'YYYY-MM-DD HH24:MI:SS'),	\
		TO_DATE(:salee,						\
				'YYYY-MM-DD HH24:MI:SS'),	\
		:stat,				\
		:cprice,			\
		:sprice,			\
		:rprice,			\
		:hibidder,			\
		:featured,			\
		:superfeatured,		\
		:bold,				\
		:private,			\
		:regonly,			\
		:host,				\
		:visitcount,		\
		:picurl,			\
		sysdate,			\
		:icon_flags,		\
		:galleryurl,		\
		:galleryType,		\
		:country_id,		\
		:currency_id,		\
		:zip,				\
		:shipping_option,	\
		:ship_region_flags,	\
		:desc_lang,			\
		:site_id			\
	)";

// nsacco 07/27/99 added new params
void clsDatabaseOracle::AddItem(clsItem *pItem, bool blocked /* = false */)
{
	// SQLTYPES.H style variables to prevent
	// any problems with the differences in 
	// the representation of data in the item
	// object as opposed to what ODBC wants.
	int					marketplaceid;
	int					id;
	int					saleType;
	int					seller;
//	int					owner;
	int					password;
	int					category;
	int					quantity;
	int					bidcount;
	char				created[32] = {0};
	char				sale_start[32] = {0};
	char				sale_end[32] = {0};
	int					sale_status;
	float				current_price;
	float				start_price;
	float				reserve_price;
	int					high_bidder;
	sb2					high_bidder_null = -1;

	char				featured[2];
	char				superFeatured[2];
	char				bold[2];
	char				privateSale[2];
	char				registered[2];

	char				*pHost;
	sb2					host_null;
	char				nullHost	= '\0';
	int					visitcount;

	char				*pPictureURL;
	sb2					pictureURL_null;
	char				nullPictureURL	= '\0';

	struct tm	*		pTheTime;
	time_t				tTime;

	char				*pIconFlags;
	sb2					iconFlags_null;
	char				nullIconFlags	= '\0';

	char				*pGalleryURL;
	sb2					galleryURL_null;
	char				nullGalleryURL	= '\0';

	int					galleryType;
	int					countryId;
	int					currencyId;

	// nsacco 07/27/99
	int					shipping_option;
	long				ship_region_flags;
	int					desc_lang;
	// nsacco 07/15/99
	int					siteId;

	char				*pZip;
	sb2					zipNull = -1;
	char				nullZip = '\0';

	char				cTableName[64];
	unsigned int		tableNameLen = 0;

	char *				pSQLStatement = NULL;
	unsigned char **	ppCursor = NULL;

	// Extract things from the item into our
	// local variables to prevent any casting
	// confusion
	marketplaceid	= pItem->GetMarketPlaceId();
	id				= pItem->GetId();
	saleType		= (int)pItem->GetAuctionType();
	seller			= pItem->GetSeller();
//	owner			= pItem->GetOwner();
	password		= pItem->GetPassword();
	category		= pItem->GetCategory();
	quantity		= pItem->GetQuantity();
	bidcount		= pItem->GetBidCount();
	sale_status		= pItem->GetStatus();
	current_price	= pItem->GetPrice();
	start_price		= pItem->GetStartPrice();
	reserve_price	= pItem->GetReservePrice();
	high_bidder		= pItem->GetHighBidder();
	visitcount		= pItem->GetVisitCount();
	galleryType		= pItem->GetGalleryType();
	countryId		= pItem->GetCountryId();
	currencyId		= pItem->GetCurrencyId();
	pZip			= pItem->GetZip();
	// nsacco 07/15/99
	siteId			= pItem->GetSiteId();
	// nsacco 07/27/99
	shipping_option = pItem->GetShippingOption();
	ship_region_flags = pItem->GetShipRegionFlags();
	desc_lang = pItem->GetDescLang();

	// Transform Bools to chars
	if (pItem->GetFeatured())
		strcpy(featured, "1");
	else
		strcpy(featured, "0");

	if (pItem->GetSuperFeatured())
		strcpy(superFeatured, "1");
	else
		strcpy(superFeatured, "0");

	if (pItem->GetBoldTitle())
		strcpy(bold, "1");
	else
		strcpy(bold, "0");

	if (pItem->GetPrivate())
		strcpy(privateSale, "1");
	else
		strcpy(privateSale, "0");

	if (pItem->GetRegisteredOnly())
		strcpy(registered, "1");
	else
		strcpy(registered, "0");

	pHost	= pItem->GetHost();

	if (pHost == NULL)
	{
		host_null	= -1;
	}
	else
	{
		host_null	= 0;
	}
	
	pPictureURL	= pItem->GetPictureURL();

	if (pPictureURL == NULL)
	{
		pictureURL_null	= -1;
	}
	else
	{
		pictureURL_null	= 0;
	}
	

	pIconFlags	= pItem->GetIconFlags();

	if (pIconFlags == NULL)
	{
		iconFlags_null	= -1;
	}
	else
	{
		iconFlags_null	= 0;
	}

	pGalleryURL	= pItem->GetGalleryURL();

	if (pGalleryURL == NULL)
	{
		galleryURL_null	= -1;
	}
	else
	{
		galleryURL_null	= 0;
	}
	

	// Date conversion

	tTime			= pItem->GetEndTime();
	pTheTime	= localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime,   sale_end);
	tTime			= pItem->GetStartTime();
	pTheTime	= localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime,   sale_start);
	tTime			= pItem->GetStartTime();	// ?
	pTheTime	= localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime,   created);


	// Get the next item id
	if (id == 0)
		id	= GetNextItemId();

	if (blocked)
		strcpy(cTableName, "ebay_items_blocked");
	else
		strcpy(cTableName, "ebay_items");

	tableNameLen = strlen(cTableName);

	// construct the SQL statement with the proper table name
	pSQLStatement = new char[strlen(SQL_AddItem) + tableNameLen + 1];
	sprintf(pSQLStatement, SQL_AddItem, cTableName);

	if (blocked)
		ppCursor = &mpCDAAddBlockedItem;
	else
		ppCursor = &mpCDAAddItem;

	// We don't use this statement very often,
	// so the cursor's not persistant. Let's 
	// prepare the statement
	OpenAndParse(ppCursor, pSQLStatement);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplaceid);
	Bind(":itemid", &id);
	Bind(":saletype", &saleType);
	Bind(":itemtitle", (char *)pItem->GetTitle());
	Bind(":loc", (char *)pItem->GetLocation());
	Bind(":seller", &seller);
	Bind(":owner", &seller);
	Bind(":password", &password);
	Bind(":category", &category);
	Bind(":quantity", &quantity);
	Bind(":bidcount", &bidcount);
	Bind(":created", (char *)created);
	Bind(":sales", (char *)sale_start);
	Bind(":salee", (char *)sale_end);
	Bind(":stat", &sale_status);
	Bind(":cprice", &current_price);
	Bind(":sprice", &start_price);
	Bind(":rprice", &reserve_price);
	if (high_bidder != 0)
		Bind(":hibidder", &high_bidder);
	else
		Bind(":hibidder", &high_bidder, &high_bidder_null);

	Bind(":featured", (char *)featured);
	Bind(":superfeatured", (char *)superFeatured);
	Bind(":bold", (char *)bold);
	Bind(":private", (char *)privateSale);
	Bind(":regonly", (char *)registered);

	if (pHost)
		Bind(":host", pHost);
	else
		Bind(":host", (char *)&nullHost, &host_null);

	Bind(":visitcount", &visitcount);

	if (pPictureURL)
		Bind(":picurl", pPictureURL);
	else
		Bind(":picurl", (char *)&nullPictureURL, &pictureURL_null);

	if (pIconFlags)
		Bind(":icon_flags", pIconFlags);
	else
		Bind(":icon_flags", (char *)&nullIconFlags, &iconFlags_null);

	if (pGalleryURL)
		Bind(":galleryurl", pGalleryURL);
	else
		Bind(":galleryurl", (char *)&nullGalleryURL, &galleryURL_null);


	Bind(":galleryType", &galleryType);
	Bind(":country_id", &countryId);
	Bind(":currency_id", &currencyId);

	if (pZip)
	{
		Bind(":zip", pZip);
	}
	else
	{
		Bind(":zip", &nullZip, &zipNull);
	}
	
	// nsacco 07/15/99
	Bind(":site_id", &siteId);

	// nsacco 07/27/99
	Bind(":shipping_option", &shipping_option);
	Bind(":ship_region_flags", &ship_region_flags);
	Bind(":desc_lang", &desc_lang);

	// Let's do it!
	Execute();

	// Commit
	Commit();

	// Free things
	Close(ppCursor);
	SetStatement(NULL);

	delete [] pSQLStatement;

	// Invalidate seller's item list
	//inna take out for EOA 
	#ifdef _MSC_VER
		InvalidateSellerList(pItem->GetMarketPlaceId(),
						 pItem->GetSeller(), pItem->GetId(), pItem->GetEndTime());
	#endif
	return;
}



//
// UpdateItem
//
// nsacco 07/27/99 added shipping option, ship_region_flags, desc_lang, site_id
static const char *SQL_UpdateItem =
 (char *)
 "update %s									\
  set	sale_type = :saletype,				\
		title = :itemtitle,					\
		location = :loc,					\
		seller = :seller,					\
		owner = :owner,						\
		password = :pass,					\
		category = :category,				\
		quantity = :quantity,				\
		bidcount = :bidcount,				\
		sale_start = TO_DATE(:sales,		\
				'YYYY-MM-DD HH24:MI:SS'),	\
		sale_end = 	TO_DATE(:salee,			\
				'YYYY-MM-DD HH24:MI:SS'),	\
		sale_status = :stat,				\
		current_price = :cprice,			\
		start_price = :sprice,				\
		reserve_price = :rprice,			\
		high_bidder = :hibidder,			\
		featured = :featured,				\
		super_featured = :superfeatured,	\
		bold_title = :bold,					\
		private_sale = :private,			\
		registered_only = :regonly,			\
		host = :host,						\
		picture_url = :picurl,				\
		last_modified = sysdate,			\
		icon_flags = :icon_flags,			\
		gallery_url = :gallery_url,			\
		gallery_type = :galleryType,		\
		country_id = :country_id,			\
		currency_id = :currency_id,			\
		zip = :zip,							\
		shipping_option = :shipping_option,	\
		ship_region_flags = :ship_region_flags, \
		desc_lang = :desc_lang,				\
		site_id = :site_id					\
	where marketplace = :marketplace		\
		and id = :id";

// nsacco 07/27/99 added shipping option, ship_region_flags, desc_lang, site_id
static const char *SQL_UpdateItemEnded =
 (char *)
 "update ebay_items_ended					\
  set	sale_type = :saletype,				\
		title = :itemtitle,					\
		location = :loc,					\
		seller = :seller,					\
		owner = :owner,						\
		password = :pass,					\
		category = :category,				\
		quantity = :quantity,				\
		bidcount = :bidcount,				\
		sale_start = TO_DATE(:sales,		\
				'YYYY-MM-DD HH24:MI:SS'),	\
		sale_end = 	TO_DATE(:salee,			\
				'YYYY-MM-DD HH24:MI:SS'),	\
		sale_status = :stat,				\
		current_price = :cprice,			\
		start_price = :sprice,				\
		reserve_price = :rprice,			\
		high_bidder = :hibidder,			\
		featured = :featured,				\
		super_featured = :superfeatured,	\
		bold_title = :bold,					\
		private_sale = :private,			\
		registered_only = :regonly,			\
		host = :host,						\
		picture_url = :picurl,				\
		last_modified = sysdate,			\
		icon_flags = :icon_flags,			\
		gallery_url = :gallery_url,			\
		gallery_type = :galleryType,		\
		country_id = :country_id,			\
		currency_id = :currency_id,			\
		zip = :zip,							\
		shipping_option = :shipping_option,	\
		ship_region_flags = :ship_region_flags, \
		desc_lang = :desc_lang,				\
		site_id = :site_id					\
	where marketplace = :marketplace		\
		and id = :id";

// nsacco 07/27/99 added shipping option, ship_region_flags, desc_lang, site_id
void clsDatabaseOracle::UpdateItem(clsItem *pItem, bool blocked /* = false */)
{
	// SQLTYPES.H style variables to prevent
	// any problems with the differences in 
	// the representation of data in the item
	// object as opposed to what ODBC wants.
	int					marketplaceid;
	int					id;
	int					saleType;
	int					seller;
//	int					owner;
	int					password;
	int					category;
	int					quantity;
	int					bidcount;
	char				sale_start[32] = {0};
	char				sale_end[32] = {0};
	int					sale_status;
	float				current_price;
	float				start_price;
	float				reserve_price;
	int					high_bidder;
	sb2					high_bidder_null = -1;

	char				featured[2];
	char				superFeatured[2];
	char				bold[2];
	char				privateSale[2];
	char				registered[2];

	char				*pHost;
//	sb2					host_null;
	char				nullHost	= '\0';
	
	char				*pPictureURL;
//	sb2					pictureURL_null;
	char				nullPictureURL	= '\0';

	char				*pIconFlags;
	sb2					iconFlags_null;
	char				nullIconFlags	= '\0';

	char				*pGalleryURL;
	sb2					galleryURL_null;
	char				nullGalleryURL	= '\0';

	int					galleryType;
	sb2					galleryType_null = -1;

	struct tm *			pTheTime;
	time_t				tTime;
	int					countryId;
	sb2					countryId_null = -1;

	int					currencyId;
	sb2					currencyId_null = -1;

	char				*pZip;
	sb2					zipNull = -1;
	char				nullZip	= '\0';

	// nsacco 07/27/99 new params
	int					shipping_option;
	long				ship_region_flags;
	int					desc_lang;
	int					site_id;

	char				cTableName[64];
	unsigned int		tableNameLen = 0;

	char *				pSQLStatement = NULL;


	// Extract things from the item into our
	// local variables to prevent any casting
	// confusion
	marketplaceid	= pItem->GetMarketPlaceId();
	id				= pItem->GetId();
	saleType		= (int)pItem->GetAuctionType();
	seller			= pItem->GetSeller();
//	owner			= pItem->GetOwner();
	password		= pItem->GetPassword();
	category		= pItem->GetCategory();
	quantity		= pItem->GetQuantity();
	bidcount		= pItem->GetBidCount();
	sale_status		= pItem->GetStatus();
	current_price	= pItem->GetPrice();
	start_price		= pItem->GetStartPrice();
	reserve_price	= pItem->GetReservePrice();
	high_bidder		= pItem->GetHighBidder();
	galleryType		= (int)pItem->GetGalleryType();
	countryId		= pItem->GetCountryId();
	currencyId		= pItem->GetCurrencyId();
	pZip			= pItem->GetZip();
	// nsacco 07/27/99
	shipping_option = pItem->GetShippingOption();
	ship_region_flags = pItem->GetShipRegionFlags();
	desc_lang = pItem->GetDescLang();
	site_id = pItem->GetSiteId();

	// Transform Bools to chars
	if (pItem->GetFeatured())
		strcpy(featured, "1");
	else
		strcpy(featured, "0");

	if (pItem->GetSuperFeatured())
		strcpy(superFeatured, "1");
	else
		strcpy(superFeatured, "0");

	if (pItem->GetBoldTitle())
		strcpy(bold, "1");
	else
		strcpy(bold, "0");

	if (pItem->GetPrivate())
		strcpy(privateSale, "1");
	else
		strcpy(privateSale, "0");

	if (pItem->GetRegisteredOnly())
		strcpy(registered, "0");
	else
		strcpy(registered, "1");

	pHost	= pItem->GetHost();
	if (pHost == NULL)
	{
		pHost	= (char  *)&nullHost;
//		host_null	= -1;
	}
//	else
//		host_null	= 0;

	pPictureURL	= pItem->GetPictureURL();
	if (pPictureURL == NULL)
	{
		pPictureURL	= (char  *)&nullPictureURL;
//		pictureURL_null	= -1;
	}
//	else
//		pictureURL_null	= 0;


	pGalleryURL	= pItem->GetGalleryURL();
	if (pGalleryURL == NULL)
	{
		pGalleryURL	= (char  *)&nullGalleryURL;
		galleryURL_null	= -1;
	}
	else
		galleryURL_null	= 0;

	// Date conversion

	pIconFlags	= pItem->GetIconFlags();
	if (pIconFlags == NULL)
	{
		pIconFlags	= (char  *)&nullIconFlags;
		iconFlags_null	= -1;
	}
	else
		iconFlags_null	= 0;

	tTime			= pItem->GetEndTime();
	pTheTime	= localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime,   sale_end);
	tTime			= pItem->GetStartTime();
	pTheTime	= localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime,   sale_start);

	if (blocked)
		strcpy(cTableName, "ebay_items_blocked");
	else
		strcpy(cTableName, "ebay_items");
	tableNameLen = strlen(cTableName);

	if (pItem->GetEnded())
		OpenAndParse(&mpCDAUpdateItemEnded, SQL_UpdateItemEnded);
	else
	{
		// construct the SQL statement with the proper table name
		pSQLStatement = new char[strlen(SQL_UpdateItem) + tableNameLen + 1];
		sprintf(pSQLStatement, SQL_UpdateItem, cTableName);
		if (blocked)
			OpenAndParse(&mpCDAUpdateItemBlocked, pSQLStatement);
		else
			OpenAndParse(&mpCDAUpdateItem, pSQLStatement);
	}

	// Ok, let's do some binds
	Bind(":marketplace", &marketplaceid);
	Bind(":id", &id);
	Bind(":saletype", &saleType);
	Bind(":itemtitle", pItem->GetTitle());
	Bind(":loc", pItem->GetLocation());
	Bind(":seller", &seller);
	Bind(":owner", &seller);
	Bind(":pass", &password);
	Bind(":category", &category);
	Bind(":quantity", &quantity);
	Bind(":bidcount", &bidcount);
	Bind(":sales", sale_start);
	Bind(":salee", sale_end);
	Bind(":stat", &sale_status);
	Bind(":cprice", &current_price);
	Bind(":sprice", &start_price);
	Bind(":rprice", &reserve_price);
	if (high_bidder != 0)
		Bind(":hibidder", &high_bidder);
	else
		Bind(":hibidder", &high_bidder, &high_bidder_null);

	Bind(":featured", featured);
	Bind(":superfeatured", superFeatured);
	Bind(":bold", bold);
	Bind(":private", privateSale);
	Bind(":regonly", registered);
	Bind(":host", pHost);
	Bind(":picurl", pPictureURL);
	if (iconFlags_null)
		Bind(":icon_flags", pIconFlags);
	else
		Bind(":icon_flags", pIconFlags, &iconFlags_null);

	if (pGalleryURL)
		Bind(":gallery_url", pGalleryURL);
	else
		Bind(":gallery_url", pGalleryURL, galleryURL_null);

	//handle gallery old item (null colunm)
	if (galleryType != 0)
		Bind(":galleryType", &galleryType);
	else
		Bind(":galleryType", &galleryType, &galleryType_null);
	Bind(":country_id", &countryId);
	Bind(":currency_id", &currencyId);

	if (pZip)
	{
		Bind(":zip", pZip);
	}
	else
	{
		Bind(":zip", &nullZip, &zipNull);
	}

	// nsacco 07/27/99
	Bind(":shipping_option", &shipping_option);
	Bind(":ship_region_flags", &ship_region_flags);
	Bind(":desc_lang", &desc_lang);
	Bind(":site_id", &site_id);
	
	// Let's do it!
	Execute();

	// Commit
	Commit();

	// Free things
	if (pItem->GetEnded())
		Close(&mpCDAUpdateItemEnded);
	else
		if (blocked)
			Close(&mpCDAUpdateItemBlocked);
		else
			Close(&mpCDAUpdateItem);

	SetStatement(NULL);

	if (!pItem->GetEnded())
		delete [] pSQLStatement;

	// Invalidate seller's item list
	//inna take out for EOA 
	#ifdef _MSC_VER
		InvalidateSellerList(pItem->GetMarketPlaceId(),
						 pItem->GetSeller(), pItem->GetId(), pItem->GetEndTime());
	#endif

//	UpdateItemDesc(pItem);

	return;
}

//
// UpdateItemDesc
//

static const char*SQL_UpdateItemDesc =
 (char *)
 "Update %s						\
	set	description_len = :itemdesclen,		\
		description	= :itemdesc				\
	where marketplace = :marketplace		\
	and	  id = :id";

static const char*SQL_UpdateItemDescEnded =
 (char *)
 "Update ebay_item_desc_ended				\
	set	description_len = :itemdesclen,		\
		description	= :itemdesc				\
	where marketplace = :marketplace		\
	and	  id = :id";

void clsDatabaseOracle::UpdateItemDesc(clsItem *pItem, bool blocked /* = false */)
{
	int					marketplaceid;
	int					id;
	// description length
	char				*pDescription;
	int					descriptionLen;

	char				cTableName[64];
	unsigned int		tableNameLen = 0;

	char *				pSQLStatement = NULL;
	unsigned char **	ppCursor = NULL;

	bool				ended;

	// Extract things from the item into our
	// local variables to prevent any casting
	// confusion
	marketplaceid	= pItem->GetMarketPlaceId();
	id				= pItem->GetId();

	pDescription	= pItem->GetDescription();
	if (pDescription)
		descriptionLen	= strlen(pDescription);
	else
	{
		pDescription	= "";
		descriptionLen	= 0;
	}
	
	if (blocked)
		strcpy(cTableName, "ebay_item_desc_blocked");
	else
		strcpy(cTableName, "ebay_item_desc");
	tableNameLen = strlen(cTableName);

	ended = pItem->GetEnded();

	// We don't use this statement very often,
	// so the cursor's not persistent. Let's 
	// prepare the statement
	if (ended)
		OpenAndParse(&mpCDAOneShot, SQL_UpdateItemDescEnded);
	else
	{
		// construct the SQL statement with the proper table name
		pSQLStatement = new char[strlen(SQL_UpdateItemDesc) + tableNameLen + 1];
		sprintf(pSQLStatement, SQL_UpdateItemDesc, cTableName);

		if (blocked)
			ppCursor = &mpCDAUpdateBlockedItemDesc;
		else
			ppCursor = &mpCDAUpdateItemDesc;

		OpenAndParse(ppCursor, pSQLStatement);
	}

	// Ok, let's do some binds
	Bind(":marketplace", &marketplaceid);
	Bind(":id", &id);
	Bind(":itemdesclen", &descriptionLen);
	BindLongRaw(":itemdesc", 
				(unsigned char *)pDescription,
				descriptionLen);

	// Let's do it!
	Execute();

	if (CheckForNoRowsUpdated())
	{
		if (ended)
			Close(&mpCDAOneShot);
		else
			Close(ppCursor);
		SetStatement(NULL);
		if (!ended)
			delete [] pSQLStatement;
		AddItemDesc(pItem);
		return;
	}

	// Commit
	Commit();

	// Free things
	if (ended)
		Close(&mpCDAOneShot);
	else
		Close(ppCursor);
	SetStatement(NULL);
	
	if (!ended)
		delete [] pSQLStatement;

	return;
}

static const char *SQL_UpdateItemStatus =
 (char *)
 "update ebay_items							\
  set	sale_status = :stat					\
	where marketplace = :marketplace		\
		and id = :id";

static const char *SQL_UpdateItemStatusEnded =
 (char *)
 "update ebay_items_ended					\
  set	sale_status = :stat					\
	where marketplace = :marketplace		\
		and id = :id";

void clsDatabaseOracle::UpdateItemStatus(clsItem *pItem)
{
	// SQLTYPES.H style variables to prevent
	// any problems with the differences in 
	// the representation of data in the item
	// object as opposed to what ODBC wants.
	int					marketplaceid;
	int					id;
	int					sale_status;

	// Extract things from the item into our
	// local variables to prevent any casting
	// confusion
	marketplaceid	= pItem->GetMarketPlaceId();
	id				= pItem->GetId();
	sale_status		= pItem->GetStatus();
	if (pItem->GetEnded())
		OpenAndParse(&mpCDAUpdateItemStatusEnded, SQL_UpdateItemStatusEnded);
	else
		OpenAndParse(&mpCDAUpdateItemStatus, SQL_UpdateItemStatus);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplaceid);
	Bind(":id", &id);
	Bind(":stat", &sale_status);

	// Let's do it!
	Execute();

	// Commit
	Commit();

	// Free things
	if (pItem->GetEnded())
		Close(&mpCDAUpdateItemStatusEnded);
	else
		Close(&mpCDAUpdateItemStatus);
	SetStatement(NULL);

	return;
}

//
// DeleteItem
//
// Deletes an item and its description by id
//
static const char *SQL_DeleteItem =
 "delete from %s							\
	where	marketplace = :marketplace		\
	and		id = :id";

static const char *SQL_DeleteItemDesc =
 "delete from %s							\
	where	marketplace = :marketplace		\
	and		id = :id";

static const char *SQL_DeleteItemEnded =
 "delete from ebay_items_ended				\
	where	marketplace = :marketplace		\
	and		id = :id";

static const char *SQL_DeleteItemDescEnded =
 "delete from ebay_item_desc_ended			\
	where	marketplace = :marketplace		\
	and		id = :id";


void clsDatabaseOracle::DeleteItem(int marketplace,
								   int id,
								   bool ended,
								   bool blocked /* = false */)
{
	char				cTableName[64];
	unsigned int		tableNameLen = 0;

	char				*pSQLStatement = NULL;
	unsigned char		**ppCursor = NULL;

	// delete item description first
	if (ended)
		OpenAndParse(&mpCDADeleteItemDescEnded, SQL_DeleteItemDescEnded);
	else
	{
		if (blocked)
		{
			strcpy(cTableName, "ebay_item_desc_blocked");
			ppCursor = &mpCDADeleteBlockedItemDesc;
		}
		else
		{
			strcpy(cTableName, "ebay_item_desc");
			ppCursor = &mpCDADeleteItemDesc;
		}

		tableNameLen = strlen(cTableName);

		// construct the SQL statement with the proper item desc table name
		pSQLStatement = new char[strlen(SQL_DeleteItemDesc) + tableNameLen + 1];
		sprintf(pSQLStatement, SQL_DeleteItemDesc, cTableName);

		// delete item description first
		OpenAndParse(ppCursor, pSQLStatement);
	}

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);

	// Just do it!
	Execute();

	// Clean up before we delete the item
	if (ended)
		Close(&mpCDADeleteItemDescEnded);
	else
	{
		// Free things
		Close(ppCursor);
		delete [] pSQLStatement;
	}

	// Need to clear either cursor
	SetStatement(NULL);


	// Now delete item
	if (ended)
		OpenAndParse(&mpCDADeleteItemEnded, SQL_DeleteItemEnded);
	else
	{
		if (blocked)
		{
			strcpy(cTableName, "ebay_items_blocked");
			ppCursor = &mpCDADeleteBlockedItem;
		}
		else
		{
			strcpy(cTableName, "ebay_items");
			ppCursor = &mpCDADeleteItem;
		}

		// Get test length
		tableNameLen = strlen(cTableName);

		// construct the SQL statement with the proper table name
		pSQLStatement = new char[strlen(SQL_DeleteItem) + tableNameLen + 1];
		sprintf(pSQLStatement, SQL_DeleteItem, cTableName);

		// delete item 
		OpenAndParse(ppCursor, pSQLStatement);
	}

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);

	// Just do it!
	Execute();
	Commit();

	// Clean up again
	if (ended)
		Close(&mpCDADeleteItemEnded);
	else
	{
		// Free things
		Close(ppCursor);
		delete [] pSQLStatement;
	}

	// Need to clear either cursor
	SetStatement(NULL);

	return;
}

// Setters: I'm leaving this here even though this will be rarely used;
// In most cases, one will do SetImmed<parm> for any updates, except for
// bidcounts.


//
// Set a new high bidder
//
static char *SQL_SetNewHighBidder =
"	update ebay_items					\
	set		current_price = :price,		\
			high_bidder = :bidder,		\
			bidcount = bidcount + 1,	\
			last_modified = sysdate		\
	where	marketplace = :marketplace	\
	and		id = :id";

void clsDatabaseOracle::SetNewHighBidder(clsItem *pItem)
{
	int		marketplace;
	int		id;
	float	price;
	int		highBidder;
	short	indHighBidder;

	marketplace		= pItem->GetMarketPlaceId();
	id				= pItem->GetId();
	price			= pItem->GetPrice();
	highBidder		= pItem->GetHighBidder();

	OpenAndParse(&mpCDASetNewHighBidder,
				 SQL_SetNewHighBidder);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":price", &price);
	Bind(":bidder", &highBidder, &indHighBidder);

	// If the high bidder's 0, indicate null
	if (highBidder == 0)
		indHighBidder	= -1;
	else
		indHighBidder	= 0;

	// Do it
	Execute();
	Commit();

	// Bye!
	Close(&mpCDASetNewHighBidder);
	SetStatement(NULL);
	return;
}

static char *SQL_SetNewBidCount =
"	update ebay_items					\
	set		bidcount = :bidcount,		\
			last_modified = sysdate		\
	where	marketplace = :marketplace	\
	and		id = :id";

void clsDatabaseOracle::SetNewBidCount(clsItem *pItem)
{
	int		marketplace;
	int		id;
	int		bidcount;

	marketplace		= pItem->GetMarketPlaceId();
	id				= pItem->GetId();
	bidcount		= pItem->GetBidCount();

	OpenAndParse(&mpCDASetNewBidCount,
				 SQL_SetNewBidCount);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":bidcount", &bidcount);

	// Do it
	Execute();
	Commit();

	// Bye!
	Close(&mpCDASetNewBidCount);
	SetStatement(NULL);
	return;
}

static char *SQL_SetNewHighBidderAndBidCount =
"	update ebay_items					\
	set		current_price = :price,		\
			high_bidder = :bidder,		\
			bidcount = :bidcount,		\
			last_modified = sysdate		\
	where	marketplace = :marketplace	\
	and		id = :id";

void clsDatabaseOracle::SetNewHighBidderAndBidCount(clsItem *pItem)
{
	int		marketplace;
	int		id;
	float	price;
	int		highBidder;
	short	indHighBidder;
	int		bidCount;

	marketplace		= pItem->GetMarketPlaceId();
	id				= pItem->GetId();
	price			= pItem->GetPrice();
	highBidder		= pItem->GetHighBidder();
	bidCount		= pItem->GetBidCount();

	OpenAndParse(&mpCDASetNewHighBidderAndBidCount,
				 SQL_SetNewHighBidderAndBidCount);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":price", &price);
	Bind(":bidder", &highBidder, &indHighBidder);
	Bind(":bidcount", &bidCount);

	// If the high bidder's 0, indicate null
	if (highBidder == 0)
		indHighBidder	= -1;
	else
		indHighBidder	= 0;

	// Do it
	Execute();
	Commit();

	// Bye!
	Close(&mpCDASetNewHighBidderAndBidCount);
	SetStatement(NULL);
	return;
}
// Set a new category
//
static char *SQL_SetNewCategory =
"	update ebay_items					\
	set		category = :category,		\
			last_modified = sysdate		\
	where	marketplace = :marketplace	\
	and		id = :id";

void clsDatabaseOracle::SetNewCategory(clsItem *pItem)
{
	int		marketplace;
	int		id;
	int		category;

	marketplace		= pItem->GetMarketPlaceId();
	id				= pItem->GetId();
	category		= pItem->GetCategory();

	OpenAndParse(&mpCDASetNewCategory,
				 SQL_SetNewCategory);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":category", &category);


	// Do it
	Execute();
	Commit();

	// Bye!
	Close(&mpCDASetNewCategory);
	SetStatement(NULL);
	return;
}


//
// AdjustMarketPlaceItemCount - note update item count via delta
//
#if 0
static char *SQL_AdjustMarketPlaceItemCount = 
 "update ebay_marketplaces_info							\
	set item_count = item_count + :delta,				\
		daily_item_count = daily_item_count + :delta	\
	where id = :id";
#endif
void clsDatabaseOracle::AdjustMarketPlaceItemCount(
								int /* marketPlaceId */,
								int /* delta */
												   )
{
	// Open + Parse
/*	wired off - done via dailystats results now.
	OpenAndParse(&mpCDAAdjustMarketPlaceItemCount, SQL_AdjustMarketPlaceItemCount);

	// Bind
	Bind(":id", &marketPlaceId);
	Bind(":delta", &delta);

	// Do it!
	Execute();
	Commit();

	// Close 
	Close(&mpCDAAdjustMarketPlaceItemCount);
	SetStatement(NULL);
*/
	return;
}

//
// Set a title
//
static char *SQL_SetNewTitle =
"	update ebay_items						\
	set		title = :itemtitle,				\
			last_modified=sysdate			\
	where	marketplace = :marketplace		\
	and		id = :itemid";

void clsDatabaseOracle::SetNewTitle(clsItem *pItem)
{
	int		marketplace;
	int		id;
	char	*pTitle;

	marketplace		= pItem->GetMarketPlaceId();
	id				= pItem->GetId();
	pTitle			= pItem->GetTitle();

	OpenAndParse(&mpCDAOneShot, SQL_SetNewTitle);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":itemid", &id);
	Bind(":itemtitle", pTitle);

	// Do it
	Execute();

	Commit();

	// Bye!
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}



//
// Set a description
// *** same as UpdateItemDesc
//
static char *SQL_SetNewDescription =
"	update ebay_item_desc					\
	set		description_len = :itemdesclen,	\
			description  = :itemdesc		\
	where	marketplace = :marketplace		\
	and		id = :itemid";


static char *SQL_SetNewDescriptionEnded =
"	update ebay_item_desc_ended					\
	set		description_len = :itemdesclen,	\
			description  = :itemdesc		\
	where	marketplace = :marketplace		\
	and		id = :itemid";


void clsDatabaseOracle::SetNewDescription(clsItem *pItem)
{
	int		marketplace;
	int		id;
	int		descLen;
	char	*pDesc;

	marketplace		= pItem->GetMarketPlaceId();
	id				= pItem->GetId();
	descLen			= strlen(pItem->GetDescription());
	pDesc			= pItem->GetDescription();
	if (pItem->GetEnded())
		OpenAndParse(&mpCDAOneShot, SQL_SetNewDescriptionEnded);
	else
		OpenAndParse(&mpCDASetNewDescription, SQL_SetNewDescription);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":itemid", &id);
	Bind(":itemdesclen", &descLen);
	BindLongRaw(":itemdesc", 
				(unsigned char *)pDesc,
				descLen);

	// Do it
	Execute();

	// If, somehow, we got now rows processed, there's
	// no description row. Just add it.
	if (CheckForNoRowsUpdated())
	{
		if (pItem->GetEnded())
			Close(&mpCDAOneShot);
		else
			Close(&mpCDASetNewDescription);
		SetStatement(NULL);
		AddItemDesc(pItem);
		return;
	}

	Commit();

	// Bye!
	if (pItem->GetEnded())
		Close(&mpCDAOneShot);
	else
		Close(&mpCDASetNewDescription);
	SetStatement(NULL);
	return;
}

//
// Set a new DUTCH high bidder; inserts one by one.
//
static char *SQL_SetDutchHighBidder =
"	insert into ebay_item_dutch_high_bidder		\
	(	marketplace,							\
		id,										\
		high_bidder,							\
		quantity,								\
		amount,									\
		value,									\
		bid_date								\
		)										\
	values										\
	(	:marketplace,							\
		:id,									\
		:highBidder,							\
		:quantity,								\
		:amount,								\
		:value,									\
		TO_DATE(:bid_date,						\
				'YYYY-MM-DD HH24:MI:SS')		\
		)";

void clsDatabaseOracle::SetDutchHighBidder(clsItem *pItem, clsBid *pBid)
{
	int		marketplace;
	int		id;
	int		highBidder;
	int		quantity;
	float	amount;
	float	value;
	time_t	bid_date;
	struct tm	*pBidDate;
	char	cBidDate[32] = {0};


	marketplace		= pItem->GetMarketPlaceId();
	id				= pItem->GetId();

	highBidder		= pBid->mUser;
	amount			= pBid->mAmount;
	value			= pBid->mValue;
	quantity		= pBid->mQuantity;

	bid_date	= pBid->mTime;

	pBidDate	= localtime(&bid_date);
	TM_STRUCTToORACLE_DATE(pBidDate, cBidDate);

	OpenAndParse(&mpCDASetDutchHighBidder,
				 SQL_SetDutchHighBidder);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":highbidder", &highBidder);
	Bind(":quantity", &quantity);
	Bind(":amount", &amount);
	Bind(":value", &value);
	Bind(":bid_date", (char *)cBidDate);

	// Do it
	Execute();
	Commit();

	// Bye!
	Close(&mpCDASetDutchHighBidder);
	SetStatement(NULL);
	return;
}

// Get Dutch High Bidders
//
// GetItemsActive
//
static const char *SQL_GetDutchHighBidders =
 "select	high_bidder,						\
			quantity,							\
			amount,								\
			value,								\
			TO_CHAR(bid_date,					\
				'YYYY-MM-DD HH24:MI:SS')		\
	from ebay_item_dutch_high_bidder			\
	where	marketplace = :marketplace			\
	and		id = :id							\
				order by value desc";	


void clsDatabaseOracle::GetDutchHighBidders(MarketPlaceId marketplace, 
										int ItemId, 
										BidVector *pvBids)
{
	// Temporary slots for things to live in

	int		high_bidder;
	int		quantity;
	int		type;
	float	amount;
	float	value;
	char	bid_date[32];
	time_t	bid_date_time;

	clsBid	*pBid;

	pBid = NULL;

	OpenAndParse(&mpCDAGetDutchHighBidders, 
				 SQL_GetDutchHighBidders);

	// Bind the input variable
	Bind(":marketplace", (int *)&marketplace);
	Bind(":id", (int *)&ItemId);

	// Bind those happy little output variables. 
	Define(1, &high_bidder);
	Define(2, &quantity);
	Define(3, &amount);
	Define(4, &value);
	Define(5, bid_date, sizeof(bid_date));

	// Let's do the SQL
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetDutchHighBidders);
		SetStatement(NULL);
		return;
	}

	// set default bid type - assume BID_BID
	type = BID_DUTCH_BID;

	// loop around fetching till we drop
	do
	{
		Fetch();
		
		if (CheckForNoRowsFound ())
		{
			break;
		}

		// Time Conversions
		ORACLE_DATEToTime(bid_date, &bid_date_time);
			// Fill in the item
			pBid	= new clsBid(bid_date_time,
						(BidActionEnum)type,
						high_bidder,
					    amount,
					    quantity,
					    value,
						NULL
					    );
			pvBids->push_back(pBid);
	
	} while (!CheckForNoRowsFound());

	Close (&mpCDAGetDutchHighBidders);
	SetStatement(NULL);

	return;
}

//
// Deletes all dutch high bidders for this item.
//
static char *SQL_DeleteDutchHighBidder =
"	delete from ebay_item_dutch_high_bidder		\
	where	marketplace = :marketplace			\
	and		id = :id";

void clsDatabaseOracle::DeleteDutchHighBidder(clsItem *pItem)
{
	int		marketplace;
	int		id;

	marketplace		= pItem->GetMarketPlaceId();
	id				= pItem->GetId();

	OpenAndParse(&mpCDADeleteDutchHighBidder,
				 SQL_DeleteDutchHighBidder);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);

	// Do it
	Execute();
	Commit();

	// Bye!
	Close(&mpCDADeleteDutchHighBidder);
	SetStatement(NULL);
	return;
}


//
// Set a new ending time
//
static char *SQL_SetNewEndTime =
"	update ebay_items						\
	set		sale_end =						\
			TO_DATE(:endtime,				\
				'YYYY-MM-DD HH24:MI:SS'),	\
			last_modified = sysdate			\
	where	marketplace = :marketplace		\
	and		id = :id";

void clsDatabaseOracle::SetNewEndTime(clsItem *pItem)
{
	int			marketplace;
	int			id;
	time_t		endTime;
	struct tm	*pTheTime;
	char		cEndTime[64];


	marketplace		= pItem->GetMarketPlaceId();
	id				= pItem->GetId();
	endTime			= pItem->GetEndTime();

	OpenAndParse(&mpCDAOneShot, SQL_SetNewEndTime);

	// Date conversion
	pTheTime	= localtime(&endTime);
	TM_STRUCTToORACLE_DATE(pTheTime,
						   cEndTime);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":endtime", cEndTime);

	// Do it
	Execute();
	Commit();

	// Bye!
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}


//
// Set a new featured status
//
static char *SQL_SetNewFeatured =
"	update ebay_items						\
	set		featured = :featured,			\
			last_modified = sysdate			\
	where	marketplace = :marketplace		\
	and		id = :id";

void clsDatabaseOracle::SetNewFeatured(clsItem *pItem)
{
	int			marketplace;
	int			id;
	char		featured[2];

	marketplace		= pItem->GetMarketPlaceId();
	id				= pItem->GetId();

	if (pItem->GetFeatured())
		strcpy(featured, "1");
	else
		strcpy(featured, "0");

	OpenAndParse(&mpCDAOneShot, SQL_SetNewFeatured);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":featured", featured);

	// Do it
	Execute();
	Commit();

	// Bye!
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}


//
// Set a new super featured status
//
static char *SQL_SetNewSuperFeatured =
"	update ebay_items						\
	set		super_featured = :superfeatured,	\
			last_modified = sysdate			\
	where	marketplace = :marketplace		\
	and		id = :id";

void clsDatabaseOracle::SetNewSuperFeatured(clsItem *pItem)
{
	int			marketplace;
	int			id;
	char		superFeatured[2];

	marketplace		= pItem->GetMarketPlaceId();
	id				= pItem->GetId();

	if (pItem->GetSuperFeatured())
		strcpy(superFeatured, "1");
	else
		strcpy(superFeatured, "0");

	OpenAndParse(&mpCDAOneShot, SQL_SetNewSuperFeatured);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":superfeatured", superFeatured);

	// Do it
	Execute();
	Commit();

	// Bye!
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}


//
// Increments visitcount by delta
//
static char *SQL_SetItemVisitCount =
"	update ebay_items							\
	set		visitcount = visitcount + :delta	\
	where	marketplace = :marketplace			\
	and		id = :id";

void clsDatabaseOracle::SetItemVisitCount(clsItem *pItem, int delta)
{
	int			marketplace;
	int			id;

	marketplace		= pItem->GetMarketPlaceId();
	id				= pItem->GetId();

	OpenAndParse(&mpCDAOneShot, SQL_SetItemVisitCount);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":delta", &delta);

	// Do it
	Execute();
	Commit();

	// Bye!
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}

//
// GetItemsUnnoticed
//
//	Gets a vector of items which haven't had their
//	end-of-auction notices sent out yet.
//
//	** NOTE **
//	These are very much "stub" items -- they only
//	contain the marketplace and item id. 
//	** NOTE **
//
//

// to check: take out marketplace from ebay_items so it will
//	use sale_end index
/* static const char *SQL_GetItemsNotNoticed = 
	"select	/*+ index(ebay_items ebay_items_ending_index ) */ /*	id	\
		from	ebay_items							\
		where	marketplace = :marketplace			\
		and		sale_end > sysdate - :since			\
		and		sale_end < sysdate					\
	 minus											\
		select	id									\
			from	ebay_item_info					\
			where	marketplace = :marketplace"; */
/* new statement - inna */ 

/*static const char *SQL_GetItemsNotNoticed =
"select /*+ index(ebay_items ebay_items_ending_index ) MERGE_AJ */   /*id		\
        from ebay_items														\
        where   marketplace = :marketplace									\
        and             sale_end > sysdate - :since							\
        and             sale_end < sysdate									\
		and id not in														\
		(select id from ebay_item_info										\
                        where   marketplace = :marketplace)"; */

//inna we need < sysdate until eos state tables are up and running!

#define ORA_EOA_ITEM_ARRAYSIZE 1000

static const char *SQL_GetItemsNotNoticed =
"select /*+ index(ebay_items ebay_items_ending_index ) MERGE_AJ */   id		\
        from ebay_items														\
        where   marketplace = :marketplace									\
		and      sale_end >= TO_DATE(:fromdate,'YYYY-MM-DD HH24:MI:SS')		\
        and     sale_end < TO_DATE(:todate,'YYYY-MM-DD HH24:MI:SS')			\
		and		sale_end < sysdate											\
		and notice_time is NULL";

void clsDatabaseOracle::GetItemsNotNoticed(MarketPlaceId marketplace,
											vector<int> *pvItems,
											time_t fromdate,
											time_t todate
											)
{
	int			id[ORA_EOA_ITEM_ARRAYSIZE];
//	clsItem		*pItem;
	int			rowsFetched;
	int			rc;
	int			i,n;

	char		cFromDate[32];
	char		cToDate[32];

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,SQL_GetItemsNotNoticed);

	struct tm*  pTheTime	= localtime(&fromdate);
	TM_STRUCTToORACLE_DATE(pTheTime, cFromDate);
	pTheTime	= localtime(&todate);
	TM_STRUCTToORACLE_DATE(pTheTime, cToDate);

	// Bind dates
	Bind(":fromdate", (char *)&cFromDate);
	Bind(":todate", (char *)&cToDate);

	Bind(":marketplace", (int *)&marketplace);

	// Define
	Define(1, id);

	// Execute
	Execute();

	// Fetch em, one at a time
//	while(1)
//	{
//		Fetch();

//		if (CheckForNoRowsFound())
//			break;

//		pItem	= new clsItem;
//		pItem->SetMarketPlaceId(marketplace);
//		pItem->SetId(id);

//		pvItems->push_back(pItem);
	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_EOA_ITEM_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_EOA_ITEM_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pvItems->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);

	// Sort
//	this->SortItems(pvItems, SortCode);

	return;
}

//Inna - user ROWID NOT item number
static const char *SQL_GetItemsNotNoticedRowId =
"select /*+ index(ebay_items ebay_items_ending_index ) MERGE_AJ */  id,\
		ROWIDTOCHAR(rowid)		\
        from ebay_items														\
        where   marketplace = :marketplace									\
		and      sale_end >= TO_DATE(:fromdate,'YYYY-MM-DD HH24:MI:SS')		\
        and     sale_end < TO_DATE(:todate,'YYYY-MM-DD HH24:MI:SS')			\
		and		sale_end < sysdate											\
		and notice_time is NULL";

void clsDatabaseOracle::GetItemsNotNoticedRowId(MarketPlaceId marketplace,
											vector<clsItemIdRowId* > *pvRows,
											//int daySince,
											time_t fromdate,
											time_t todate
											)
{
	int					id[ORA_EOA_ITEM_ARRAYSIZE];
	char				itemrowid[ORA_EOA_ITEM_ARRAYSIZE][20];
	clsItemIdRowId				*pItemIdRowId;

	int			rowsFetched;
	int			rc;
	int			i,n;

	char		cFromDate[32];
	char		cToDate[32];

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,SQL_GetItemsNotNoticedRowId);

	struct tm*  pTheTime	= localtime(&fromdate);
	TM_STRUCTToORACLE_DATE(pTheTime, cFromDate);
	pTheTime	= localtime(&todate);
	TM_STRUCTToORACLE_DATE(pTheTime, cToDate);

	// Bind dates
	Bind(":fromdate", (char *)&cFromDate);
	Bind(":todate", (char *)&cToDate);

	Bind(":marketplace", (int *)&marketplace);

	// Define
	Define(1, id);
	Define(2, itemrowid[0], sizeof(itemrowid[0]));

	// Execute
	Execute();

// do array fetch at a time
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_EOA_ITEM_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_ITEMSELECT_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;	

		for (i=0; i < n; i++)
		{
			//delete done by calling program
			pItemIdRowId= new clsItemIdRowId(itemrowid[i], id[i]);
				
			pvRows->push_back(pItemIdRowId);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// GetItemsNotBilled
//
//	Gets a vector of items which haven't had bills
//	sent out yet. This is determined by looking for
//	items which have had their end-of-auction notices
//	sent out, but not billed. 
//
//	** NOTE **
//	These are very much "stub" items -- they only
//	contain the marketplace and item id. 
//	** NOTE **
//
//
#define ORA_NOTBILLED_ARRAYSIZE	500

/*static const char *SQL_GetItemsNotBilled = 
	"select		i.id									\
		from	ebay_item_info i						\
		where		i.marketplace = :marketplace	\
		and		i.bill_time IS NULL				\
		and		rownum < 30000";
*/
static const char *SQL_GetItemsNotBilled = 
	"select		i.id									\
		from	ebay_items						\
		where		i.marketplace = :marketplace	\
		and		i.bill_time IS NULL				\
		and		rownum < 30000";

void clsDatabaseOracle::GetItemsNotBilled(MarketPlaceId marketplace,
											vector<int> *pvItems)
{
	int			id[ORA_NOTBILLED_ARRAYSIZE];
	int			rowsFetched;
	int			rc;
	int			i,n;

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetItemsNotBilled);

	// Bind
	Bind(":marketplace", (int *)&marketplace);

	// Define
	Define(1, (int *)id);

	// Execute
	Execute();

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORA_NOTBILLED_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pvItems->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


//
// AddItemNoticed
//
/* no more as of e118 static const char *SQL_AddItemNoticed = 
 "insert into ebay_item_info					\
  (	marketplace,								\
	id,											\
	notice_time,								\
	bill_time									\
  )												\
  values										\
  (	:marketplace,								\
	:id,										\
	TO_DATE(:when,								\
			'YYYY-MM-DD HH24:MI:SS'),			\
	null										\
  )";*/
// istead modify ebay_items to update the notice column
static const char *SQL_UpdateItemNoticed = 
 "update ebay_items					\
set notice_time =  	TO_DATE(:when,								\
			'YYYY-MM-DD HH24:MI:SS')			\
where marketplace=:marketplace and id=:id";

static const char *SQL_AddItemNoticedByRowId = 
 "update ebay_items					\
set notice_time =  	TO_DATE(:when,								\
			'YYYY-MM-DD HH24:MI:SS')			\
where rowid = CHARTOROWID(:thisrow)";

void clsDatabaseOracle::AddItemNoticed(clsItem *pItem)
{
	MarketPlaceId	marketplace;
	int				id;
	time_t			tTime;
	struct tm		*pTheTime;
	char			when[32];
	//deal with row id 
	bool	userowid;

	userowid = IsValidRowIdFormat(pItem->GetRowId());

	// Open And Parse
//	OpenAndParse(&mpCDAAddItemNoticed, 
//				 SQL_AddItemNoticed);
	
	if (userowid)
	{
		OpenAndParse(&mpCDAAddItemNoticedByRowId, 
				 SQL_AddItemNoticedByRowId);
		Bind(":thisrow", pItem->GetRowId());

	}
	else
	{
		OpenAndParse(&mpCDAModifyItemNoticed, 
				 SQL_UpdateItemNoticed);
	

		// Bind
		marketplace	=	pItem->GetMarketPlaceId();
		id			=	pItem->GetId();

		Bind(":marketplace", (int *)&marketplace);
		Bind(":id", &id);
	
	}
	//common binds
	tTime		= pItem->GetNoticeTime();
	pTheTime	= localtime(&tTime);		
	TM_STRUCTToORACLE_DATE(pTheTime, when);
	Bind(":when", when);

	// Do...
	Execute();

	Commit();

	if (userowid)
			Close(&mpCDAAddItemNoticedByRowId);
	else
		Close(&mpCDAModifyItemNoticed);

	SetStatement(NULL);
	return;
}

//
// AddItemBilled
//
//	Indicates that an item's been billed by setting
//	the bill_time in it's item_info row. Note that
//	this is done by an UPDATE since we expect the
//	row to have been created when the EndOfAuction
//	notice was sent out.
//
//
/*no more as of e118 const char *SQL_AddItemBilled = 
	"update	ebay_item_info							\
		set		bill_time =							\
				TO_DATE(:when,						\
						'YYYY-MM-DD HH24:MI:SS')	\
		where	marketplace = :marketplace			\
		and		id = :id"; */


const char *SQL_UpdateItemBilled = 
	"update	ebay_items						\
		set		bill_time =							\
				TO_DATE(:when,						\
						'YYYY-MM-DD HH24:MI:SS')	\
		where	marketplace = :marketplace			\
		and		id = :id";

const char *SQL_AddItemBilledByRowId = 
	"update	ebay_items						\
		set		bill_time =							\
				TO_DATE(:when,						\
						'YYYY-MM-DD HH24:MI:SS')	\
where rowid = CHARTOROWID(:thisrow)";

void clsDatabaseOracle::AddItemBilled(clsItem *pItem)
{
	MarketPlaceId	marketplace;
	int				id;
	time_t			tTime;
	struct tm		*pTheTime;
	char			when[32];

	//deal with row id 
	bool	userowid;

	userowid = IsValidRowIdFormat(pItem->GetRowId());

	// Open And Parse
//	OpenAndParse(&mpCDAAddItemBilled, 
//				 SQL_AddItemBilled);
	if (userowid)
	{
		OpenAndParse(&mpCDAAddItemBilledByRowId, 
				 SQL_AddItemBilledByRowId);
			Bind(":thisrow", pItem->GetRowId());
	}
	else
	{
		OpenAndParse(&mpCDAUpdateItemBilled, 
					SQL_UpdateItemBilled);
		// Bind
		marketplace	=	pItem->GetMarketPlaceId();
		id			=	pItem->GetId();
		Bind(":marketplace", &marketplace);
		Bind(":id", &id);
	}

	//common binds
	tTime		=	pItem->GetBillTime();
	pTheTime	=	localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime, when);
	Bind(":when", when);

	// Do...
	Execute();

	// Check for nothing happened


	// Commit
	Commit();
	if (userowid)
		Close(&mpCDAAddItemBilledByRowId);
	else
		Close(&mpCDAUpdateItemBilled);
	SetStatement(NULL);
	return;
}


/*static const char *SQL_GetBillTime =
 "select	TO_CHAR(bill_time,					\
						'YYYY-MM-DD HH24:MI:SS')	\
	from ebay_item_info								\
	where	marketplace = :marketplace			\
	and		id = :itemid"; */

static const char *SQL_GetBillTime =
 "select	TO_CHAR(bill_time,					\
						'YYYY-MM-DD HH24:MI:SS')	\
	from ebay_items								\
	where	marketplace = :marketplace			\
	and		id = :itemid";

static const char *SQL_GetBillTimeByRowId =
 "select	TO_CHAR(bill_time,					\
						'YYYY-MM-DD HH24:MI:SS')	\
	from ebay_items								\
	where rowid = CHARTOROWID(:thisrow)";

static const char *SQL_GetBillTimeEnded =
 "select	TO_CHAR(bill_time,					\
						'YYYY-MM-DD HH24:MI:SS')	\
	from ebay_items_ended								\
	where	marketplace = :marketplace			\
	and		id = :itemid";


long clsDatabaseOracle::GetBillTime(clsItem *pItem)
{
	// Temporary slots for things to live in
	char				bill_time[32];
	time_t				bill_time_time;
	sb2					bill_time_ind;
	MarketPlaceId		marketplace;
	int					id;

	//deal with row id 
	bool	userowid;

	userowid = IsValidRowIdFormat(pItem->GetRowId());

	marketplace = pItem->GetMarketPlaceId();
	id = pItem->GetId();

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	if (pItem->GetEnded())
	{
		OpenAndParse(&mpCDAGetBillTimeEnded, SQL_GetBillTimeEnded);
		
		// Bind the input variable
		Bind(":itemid", &id);
		Bind(":marketplace", &marketplace);
	}
	else
	{
		if (userowid)
		{
			OpenAndParse(&mpCDAGetBillTimeByRowId, SQL_GetBillTimeByRowId);
			Bind(":thisrow", pItem->GetRowId());
		}
		else
		{
			OpenAndParse(&mpCDAGetBillTime, SQL_GetBillTime);
		
			// Bind the input variable
			Bind(":itemid", &id);
			Bind(":marketplace", &marketplace);
		}
	}



	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, (char *)bill_time, sizeof(bill_time), &bill_time_ind);

	// Fetch
	ExecuteAndFetch();

	if (pItem->GetEnded())
		Close(&mpCDAGetBillTimeEnded);
	else
	{
		if (userowid)
			Close(&mpCDAGetBillTimeByRowId);
		else
			Close(&mpCDAGetBillTime);
	}

	SetStatement(NULL);
	// Time Conversions
	if (bill_time_ind == -1)
		bill_time_time = 0;
	else
		ORACLE_DATEToTime(bill_time, &bill_time_time);

	return bill_time_time;
}

//inna- this method is ONLY used by EOA, so i do not need
//code to look up in ended, I really do not need look
//ups by ID either just ROW ID but I will leave it in 
//just in case row id gets messed up somehow
const char *SQL_UpdateDutchGMS = 
	"update	ebay_items						\
		set		DUTCH_GMS =	 :price \
		where	marketplace = :marketplace			\
		and		id = :id";

const char *SQL_UpdateDutchGMSByRowId = 
	"update	ebay_items						\
		set		DUTCH_GMS =	 :price \
		where rowid = CHARTOROWID(:thisrow)";

void clsDatabaseOracle::SetDBDutchGMS(clsItem *pItem, float price)
{
	MarketPlaceId	marketplace;
	int				id;

	//deal with row id 
	bool	userowid;

	userowid = IsValidRowIdFormat(pItem->GetRowId());
	
	if (userowid)
	{
		OpenAndParse(&mpCDAUpdateDutchGMSByRowId, 
				 SQL_UpdateDutchGMSByRowId);
		Bind(":thisrow", pItem->GetRowId());
	}
	else
	{
		OpenAndParse(&mpCDAUpdateDutchGMS, 
					SQL_UpdateDutchGMS);
		// Bind
		marketplace	=	pItem->GetMarketPlaceId();
		id			=	pItem->GetId();

		Bind(":marketplace", &marketplace);
		Bind(":id", &id);
	}


	//common binds
	Bind(":price", &price);

	// Do...
	Execute();

	// Check for nothing happened


	// Commit
	Commit();

	if (userowid)
		Close(&mpCDAUpdateDutchGMSByRowId);
	else
		Close(&mpCDAUpdateDutchGMS);

	SetStatement(NULL);
	return;
}

static const char *SQL_GetManyItemDescLenMax =
 "select MAX(description_len)					\
	from ebay_item_desc							\
	where marketplace = :marketplace			\
	and id in									\
	(:i1, :i2, :i3, :i4, :i5, :i6, :i7, :i8,	\
	:i9, :i10, :i11, :i12, :i13, :i14, :i15,	\
	:i16, :i17, :i18, :i19, :i20, :i21, :i22,	\
	:i23, :i24, :i25, :i26, :i27, :i28, :i29,	\
	:i30, :i31, :i32, :i33, :i34, :i35, :i36,	\
	:i37, :i38, :i39, :i40, :i41, :i42, :i43,	\
	:i44, :i45, :i46, :i47, :i48, :i49, :i50)";

static const char *SQL_GetManyItemDescLenMaxEnded =
 "select MAX(description_len)					\
	from ebay_item_desc_ended					\
	where marketplace = :marketplace			\
	and id in									\
	(:i1, :i2, :i3, :i4, :i5, :i6, :i7, :i8,	\
	:i9, :i10, :i11, :i12, :i13, :i14, :i15,	\
	:i16, :i17, :i18, :i19, :i20, :i21, :i22,	\
	:i23, :i24, :i25, :i26, :i27, :i28, :i29,	\
	:i30, :i31, :i32, :i33, :i34, :i35, :i36,	\
	:i37, :i38, :i39, :i40, :i41, :i42, :i43,	\
	:i44, :i45, :i46, :i47, :i48, :i49, :i50)";

static const char *SQL_GetManyItemDescs =
 "select id, description, description_len			\
	from ebay_item_desc							\
	where marketplace = :marketplace			\
	and id in									\
	(:i1, :i2, :i3, :i4, :i5, :i6, :i7, :i8,	\
	:i9, :i10, :i11, :i12, :i13, :i14, :i15,	\
	:i16, :i17, :i18, :i19, :i20, :i21, :i22,	\
	:i23, :i24, :i25, :i26, :i27, :i28, :i29,	\
	:i30, :i31, :i32, :i33, :i34, :i35, :i36,	\
	:i37, :i38, :i39, :i40, :i41, :i42, :i43,	\
	:i44, :i45, :i46, :i47, :i48, :i49, :i50)";

static const char *SQL_GetManyItemDescsEnded =
 "select id, description, description_len			\
	from ebay_item_desc_ended						\
	where marketplace = :marketplace			\
	and id in									\
	(:i1, :i2, :i3, :i4, :i5, :i6, :i7, :i8,	\
	:i9, :i10, :i11, :i12, :i13, :i14, :i15,	\
	:i16, :i17, :i18, :i19, :i20, :i21, :i22,	\
	:i23, :i24, :i25, :i26, :i27, :i28, :i29,	\
	:i30, :i31, :i32, :i33, :i34, :i35, :i36,	\
	:i37, :i38, :i39, :i40, :i41, :i42, :i43,	\
	:i44, :i45, :i46, :i47, :i48, :i49, :i50)";

static char *sINames[] =
{ ":i1", ":i2", ":i3", ":i4", ":i5", ":i6", ":i7",
  ":i8", ":i9", ":i10", ":i11", ":i12", ":i13", ":i14",
  ":i15", ":i16", ":i17", ":i18", ":i19", ":i20", ":i21",
  ":i22", ":i23", ":i24", ":i25", ":i26", ":i27", ":i28",
  ":i29", ":i30", ":i31", ":i32", ":i33", ":i34", ":i35",
  ":i36", ":i37", ":i38", ":i39", ":i40", ":i41", ":i42",
  ":i43", ":i44", ":i45", ":i46", ":i47", ":i48", ":i49",
  ":i50" };

void clsDatabaseOracle::GetManyItemDescriptions(MarketPlaceId marketplace,
												ItemVector::iterator iStart,
												ItemVector::iterator iEnd, bool ended)
{
	int maxLen;
	int newMaxLen;
	int maxBind;
	int boundIds[sizeof (sINames) / sizeof (char *)];
	int id;
	int len;
	char *pDescBuffer;
	ItemVector::iterator boundStart; // Start of bound/defined objects.
	ItemVector::iterator boundEnd; // End of bound/defined objects.
	int k; // A counter

	boundStart = iStart;
	boundEnd = iStart;
	maxLen = 0;
	newMaxLen = 0;
	pDescBuffer = NULL;
	maxBind = sizeof (sINames) / sizeof (char *);

	do
	{
		boundStart = boundEnd;
		if (ended)
			OpenAndParse(&mpCDAOneShot, SQL_GetManyItemDescLenMaxEnded);
		else
			OpenAndParse(&mpCDAOneShot, SQL_GetManyItemDescLenMax);
		for (k = 0; k < maxBind; ++k)
		{
			if (boundEnd == iEnd)
				boundIds[k] = -1;
			else
			{
				boundIds[k] = (*boundEnd)->GetId();
				++boundEnd;
			}

			Bind(sINames[k], boundIds + k);
		}
		Bind(":marketplace", (int *) &marketplace);

		Define(1, &newMaxLen);

		ExecuteAndFetch();

		if (CheckForNoRowsFound())
		{
			Close(&mpCDAOneShot);
			SetStatement(NULL);

			delete [] pDescBuffer;
			return;
		}

		if (newMaxLen > maxLen)
		{
			delete [] pDescBuffer;
			pDescBuffer = new char [(newMaxLen + 1) * maxBind];
			maxLen = newMaxLen;
		}

		Close(&mpCDAOneShot);
		SetStatement(NULL);

		if (ended)
			OpenAndParse(&mpCDAOneShot, SQL_GetManyItemDescsEnded);
		else
			OpenAndParse(&mpCDAOneShot, SQL_GetManyItemDescs);
		for (k = 0; k < maxBind; ++k)
		{
			Bind(sINames[k], boundIds + k);
		}
		Bind(":marketplace", (int *) &marketplace);

		Define(1, &id);
		DefineLongRaw(2, (unsigned char *) pDescBuffer, maxLen);
		Define(3, &len);

		// Do the SQL
		Execute();

		do
		{
			Fetch();

			if (CheckForNoRowsFound())
				break;

			pDescBuffer[len] = '\0';
			for (k = 0; k < maxBind; ++k)
			{
				if((boundStart + k) == iEnd)
					break;

				if((*(boundStart + k)) != NULL)
				{ 
					if ((*(boundStart + k))->GetId() == id)
					{
						(*(boundStart + k))->SetDescription(pDescBuffer);
						break;
					}
				}
			}
		} while (!CheckForNoRowsFound());

		Close(&mpCDAOneShot);
		SetStatement(NULL);

	} while (boundEnd != iEnd);

	delete [] pDescBuffer;
}

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetItemsModifiedAfterMinimal =
 "select /*+ index(items ebay_items_ending_index ) */ items.id,	\
			items.title,								\
			items.category,								\
			items.bidcount,								\
			TO_CHAR(items.sale_start,					\
				'YYYY-MM-DD HH24:MI:SS'),				\
			TO_CHAR(items.sale_end,						\
				'YYYY-MM-DD HH24:MI:SS'),				\
			items.current_price,						\
			items.start_price,							\
			items.picture_url,							\
			items.icon_flags,							\
			items.country_id,							\
			items.currency_id,							\
			items.password,								\
			items.zip,									\
			items.shipping_option,						\
			items.ship_region_flags,					\
			items.desc_lang,							\
			items.site_id								\
	from ebay_items items								\
	where items.last_modified >= 						\
			TO_DATE(:mod_date_start, 'YYYY-MM-DD HH24:MI:SS')	\
	and items.last_modified <= \
			TO_DATE(:mod_date_end, 'YYYY-MM-DD HH24:MI:SS')	\
	and items.sale_end >= \
			TO_DATE(:mod_date_start, 'YYYY-MM-DD HH24:MI:SS') \
	and		items.marketplace = :marketplace";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetItemsStartedAfterMinimal =
 "select /*+ index(items ebay_items_starting_index) */ items.id,	\
			items.title,								\
			items.category,								\
			items.bidcount,								\
			TO_CHAR(items.sale_start,					\
				'YYYY-MM-DD HH24:MI:SS'),				\
			TO_CHAR(items.sale_end,						\
				'YYYY-MM-DD HH24:MI:SS'),				\
			items.current_price,						\
			items.start_price,							\
			items.picture_url,							\
			items.icon_flags,							\
			items.country_id,							\
			items.currency_id,							\
			items.password,								\
			items.zip,									\
			items.shipping_option,						\
			items.ship_region_flags,					\
			items.desc_lang,							\
			itesm.site_id								\
	from ebay_items items								\
	where items.sale_start >= 						\
			TO_DATE(:mod_date_start, 'YYYY-MM-DD HH24:MI:SS')	\
	and items.sale_start <= to_date(:mod_date_end, 'YYYY-MM-DD HH24:MI:SS')	\
	and		items.marketplace = :marketplace";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
void clsDatabaseOracle::GetItemsModifiedAfterMinimal(MarketPlaceId marketplace,
							 		   time_t modDateStart, time_t modDateEnd, 
									   ItemVector *pvItems, bool started)
{
	// Temporary slots for things to live in
	int					id;
	char				title[255];
	int					category;
	int					bidcount;
	char				sale_start[32];
	time_t				sale_start_time;
	char				sale_end[32];
	time_t				sale_end_time;
	float				current_price;
	float				start_price;
	char				pictureURL[256];
	sb2					pictureURL_ind;
	char				*pPictureURL;

	char				iconFlags[3];
	sb2					iconFlags_ind;
	char				*pIconFlags;

	char				*pTitle;

	char				cModDateStart[64];
	struct tm			*pModDateStart;
	char				cModDateEnd[64];
	struct tm			*pModDateEnd;
	
	clsItem				*pItem;
	
	int					countryId;
	sb2					countryId_ind;
	int					currencyId;
	sb2					currencyId_ind;
	int					password;

	char				zip[EBAY_MAX_ZIP_SIZE + 1];
	sb2					zip_ind;
	char				*pZip;

	// nsacco 07/27/99
	int					shipping_option;
	long				ship_region_flags;
	int					desc_lang;
	int					site_id;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	if (started)
		OpenAndParse(&mpCDAOneShot, SQL_GetItemsStartedAfterMinimal);
	else
		OpenAndParse(&mpCDAOneShot, SQL_GetItemsModifiedAfterMinimal);

	pModDateStart	= localtime(&modDateStart);
	TM_STRUCTToORACLE_DATE(pModDateStart,
						   cModDateStart);
	pModDateEnd	= localtime(&modDateEnd);
	TM_STRUCTToORACLE_DATE(pModDateEnd,
						   cModDateEnd);
	// Bind the input variable
	Bind(":marketplace", (int *)&marketplace);
	Bind(":mod_date_start", (char *)&cModDateStart);
	Bind(":mod_date_end", (char *)&cModDateEnd);

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, &id);
	Define(2, title, sizeof(title));
	Define(3, &category);
	Define(4, &bidcount);
	Define(5, sale_start, sizeof(sale_start));
	Define(6, sale_end, sizeof(sale_end));
	Define(7, &current_price);
	Define(8, &start_price);
	Define(9, pictureURL, sizeof(pictureURL),
			&pictureURL_ind);
	Define(10, iconFlags, sizeof(iconFlags),
			&iconFlags_ind);
	Define(11, &countryId, &countryId_ind);
	Define(12, &currencyId, &currencyId_ind);
	Define(13, &password);
	Define(14, zip, sizeof(zip), &zip_ind);
	// nsacco 07/27/99
	Define(15, &shipping_option);
	Define(16, &ship_region_flags);
	Define(17, &desc_lang);
	Define(18, &site_id);
	
	// Do the SQL
	Execute();

	// fetch the rows
	do
	{
		Fetch();

		if (CheckForNoRowsFound())
		{
			break;
		}
		// Now everything is where it's supposed
		// to be. Let's make copies of the title
		// and location for the item
		pTitle		= new char[strlen(title) + 1];
		strcpy(pTitle, (char *)title);

		// Time Conversions
		ORACLE_DATEToTime(sale_start, &sale_start_time);
		ORACLE_DATEToTime(sale_end, &sale_end_time);

		if (pictureURL_ind == -1)
		{
			pPictureURL	= NULL;
		}
		else
		{
			pPictureURL	= new char[strlen(pictureURL) + 1];
			strcpy(pPictureURL, pictureURL);
		}

		if (iconFlags_ind == -1)
		{
			pIconFlags	= NULL;
		}
		else
		{
			pIconFlags	= new char[strlen(iconFlags) + 1];
			strcpy(pIconFlags, iconFlags);
		}
		// Fill in the item
		if (countryId_ind == -1)
			countryId	= Country_None;
		if (currencyId_ind == -1)
			currencyId	= Currency_USD;

		if (zip_ind == -1)
		{
			pZip = NULL;
		}
		else
		{
			pZip	= new char[strlen(zip) + 1];
			strcpy(pZip, zip);
		}

		// nsacco 07/27/99
		if (shipping_option == -1)
		{
			if (password & ShippingInternationally)
			{
				// handle old items
				shipping_option = Worldwide;
				password = password & ~ShippingInternationally;
			}
			else
			{
				shipping_option = SiteOnly;
			}
		}

		if (ship_region_flags == -1)
		{
			ship_region_flags = ShipRegion_None;
		}

		if (desc_lang == -1)
		{
			desc_lang = English;
		}

		if (site_id == -1)
		{
			site_id = SITE_EBAY_MAIN;
		}


		pItem = new clsItem;
		// nsacco 07/27/99 added new params
		pItem->Set(marketplace,				//MarketPlaceId marketPlaceid, 
			id,							//int id,
			AuctionUnknown,				//AuctionTypeEnum auctionType,
			pTitle,						//char *pTitle,
			NULL,							//char *pDescription,
			NULL,							//char *pLocation,
			0,								//int seller,
			0,								//int owner,
			category,						//CategoryId category,
			bidcount,						//int bidCount,
			0,								//int quantity, 
			sale_start_time,				//long startTime,
			sale_end_time,					//long endTime,
			0L,								//long status, 
			current_price,					//double price,
			start_price,					//double startPrice,
			0.0,							//double reservePrice,
			0,								//int highBidder,
			false,							//bool featured,
			false,							//bool superFeatured,
			false,							//bool boldTitle,
			false,							//bool privateAuction,
			false,							//bool registeredOnly,
			NULL,							//char *pHost,
			0,								//int visitCount,
			pPictureURL,					//char *pPictureURL,
			NULL,							//char *pCategoryName,
			NULL,							//char *pSellerUserId,
			0,								//int sellerUserState,
			0,								//int sellerUserFlags,
			NULL,							//char *pHighBidderUserId,
			0,								//int highBidderUserState,
			0,								//int highBidderUserFlags,
			0,								//int sellerFeedbackScore,
			0,								//int highBidderFeedbackScore,
			0L,							//long sellerIdLastChange,
			0L,							//long highBidderIdLastChange,
			0L,							//long lastModified,
			NULL,							//const char *pSellerEmail = NULL,
			NULL,							//const char *pHighBidderEmail = NULL,
			password,								//int password = 0,
			NULL,							//char *pRowId = 0,
			0L,								//long delta = 0
			pIconFlags,						//iconFlags;
			NULL,
			NoneGallery,
			kGalleryNotProcessed,
			countryId,						// int countryId
			currencyId,
			0L,								// If the Item is from the ended table.. useful later
			pZip,
			Currency_USD,					// int billingCurrency
			shipping_option,				// int shipping_option
			ShipRegion_None,				// long ship_region_flags
			English,						// int desc_lang
			SITE_EBAY_MAIN					// int site_id
			);

		pvItems->push_back(pItem);
	} while (1 == 1);

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;	
}

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetItemsModifiedAfter =
 "select /*+ index(items ebay_items_last_modified_index ) */ items.id,	\
			items.sale_type,							\
			items.title,								\
			items.location,								\
			items.seller,								\
			items.owner,								\
			items.password,								\
			items.category,								\
			items.quantity,								\
			items.bidcount,								\
			TO_CHAR(items.sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),		\
			TO_CHAR(items.sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),		\
			items.sale_status,							\
			items.current_price,						\
			items.start_price,							\
			items.reserve_price,						\
			items.high_bidder,							\
			items.featured,								\
			items.super_featured,						\
			items.bold_title,							\
			items.private_sale,							\
			items.registered_only,						\
			items.host,									\
			items.visitcount,							\
			items.picture_url,							\
			TO_CHAR(items.last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),				\
			items.icon_flags,							\
			items.gallery_type,							\
			items.country_id,							\
			items.currency_id,							\
			items.zip,									\
			items.shipping_option,						\
			items.ship_region_flags,					\
			items.desc_lang,							\
			items.site_id,								\
			users1.userid,								\
			users1.user_state,							\
			users1.flags,								\
			users2.userid,								\
			users2.user_state,							\
			users2.flags,							\
			((((categories.name4 || ':') ||				\
					categories.name3 || ':') ||			\
						categories.name2 || ':') ||		\
							categories.name1 || ':') ||	\
								categories.name,		\
			feedback1.score,							\
			feedback2.score								\
	from ebay_items items,								\
		 ebay_users users1,								\
		 ebay_users users2,								\
		 ebay_categories categories,					\
		 ebay_feedback feedback1,						\
		 ebay_feedback feedback2						\
	where	items.last_modified >= TO_DATE(:mod_date,	\
				'YYYY-MM-DD HH24:MI:SS')				\
	and		items.marketplace = :marketplace			\
	and		items.seller = users1.id					\
	and		items.high_bidder = users2.id (+)			\
	and		items.category = categories.id				\
	and		categories.marketplace = items.marketplace	\
	and		items.seller = feedback1.id (+)				\
	and		items.high_bidder = feedback2.id (+)";

	
void clsDatabaseOracle::GetItemsModifiedAfter(MarketPlaceId marketplace,
							 		   time_t modDate,
									   ItemVector *pvItems,
						   ItemListSortEnum SortCode /* = SortItemsByUnknown */)
{
	// Temporary slots for things to live in
	int					id;
	AuctionTypeEnum		saleType;	
	char				title[255];
	char				location[255];
	int					seller;
	int					owner;
	int					password;
	int					category;
	int					quantity;
	int					bidcount;
	char				sale_start[32];
	time_t				sale_start_time;
	char				sale_end[32];
	time_t				sale_end_time;
	int					sale_status;
	float				current_price;
	float				start_price;
	float				reserve_price;
	int					high_bidder;
	sb2					high_bidder_ind;

	char				featured[2];
	char				superFeatured[2];
	char				boldTitle[2];
	char				privateSale[2];
	char				registeredOnly[2];
	char				host[65];
	sb2					host_ind;
	char				*pHost;
	int					visitcount;
	char				pictureURL[256];
	sb2					pictureURL_ind;
	char				*pPictureURL;

	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBold;
	bool				isPrivate;
	bool				isRegisteredOnly;

	char				*pLocation;
	char				*pTitle;

	char				cModDate[64];
	struct tm			*pModDate;
	
	time_t				last_modified_time;
	char				last_modified[32];

	char				iconFlags[3];
	sb2					pIconFlags_ind;
	char				*pIconFlags;

	int					galleryType;
	sb2					galleryType_ind;

	char				categoryName[255];
	sb2					categoryName_ind;
	char				sellerUserId[255];
	sb2					sellerUserId_ind;
	int					sellerUserState;
	sb2					sellerUserState_ind;
	int					sellerUserFlags;
	char				highBidderUserId[255];
	sb2					highBidderUserId_ind;
	int					highBidderUserState;
	sb2					highBidderUserState_ind;
	int					highBidderUserFlags;
	int					sellerFeedbackScore;
	sb2					sellerFeedbackScore_ind;
	int					highBidderFeedbackScore;
	sb2					highBidderFeedbackScore_ind;
	int					countryId;
	sb2					countryId_ind;
	int					currencyId;
	sb2					currencyId_ind;
	char				zip[EBAY_MAX_ZIP_SIZE + 1];
	sb2					pZip_ind;
	char				*pZip;
	// nsacco 07/27/99
	int					shipping_option;
	long				ship_region_flags;
	int					desc_lang;
	int					site_id;


	char				*pSellerUserId;
	char				*pHighBidderUserId;
	char				*pCategoryName;
	clsItem				*pItem;

//	clsCategories*		pCategories = NULL;

	// let's get a clsCategories
//	pCategories = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCategories();

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	OpenAndParse(&mpCDAGetItemsModifiedAfter, SQL_GetItemsModifiedAfter);

	pModDate	= localtime(&modDate);
	TM_STRUCTToORACLE_DATE(pModDate,
						   cModDate);
	// Bind the input variable
	Bind(":marketplace", &marketplace);
	Bind(":mod_date", cModDate);

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, &id);
	Define(2, (int *)&saleType);
	Define(3, title, sizeof(title));
	Define(4, location, sizeof(location));
	Define(5, &seller);
	Define(6, &owner);
	Define(7, &password);
	Define(8, &category);
	Define(9, &quantity);
	Define(10, &bidcount);
	Define(11, sale_start, sizeof(sale_start));
	Define(12, sale_end, sizeof(sale_end));
	Define(13, &sale_status);
	Define(14, &current_price);
	Define(15, &start_price);
	Define(16, &reserve_price);
	Define(17, &high_bidder, &high_bidder_ind);
	Define(18, featured, sizeof(featured));
	Define(19, superFeatured, sizeof(superFeatured));
	Define(20, boldTitle, sizeof(boldTitle));
	Define(21, privateSale, sizeof(privateSale));
	Define(22, registeredOnly, sizeof(registeredOnly));
	Define(23, host, sizeof(host), &host_ind);
	Define(24, &visitcount);
	Define(25, pictureURL, sizeof(pictureURL),
			&pictureURL_ind);
	Define(26, last_modified, sizeof(last_modified));
	Define(27, iconFlags, sizeof(iconFlags), &pIconFlags_ind);
	Define(28, &galleryType, &galleryType_ind);
	Define(29, &countryId, &countryId_ind);
	Define(30, &currencyId, &currencyId_ind);
	Define(31, zip, sizeof(zip), &pZip_ind);

	// nsacco 07/27/99
	Define(32, &shipping_option);
	Define(33, &ship_region_flags);
	Define(34, &desc_lang);
	Define(35, &site_id);

	Define(36, sellerUserId, sizeof(sellerUserId),
			   &sellerUserId_ind);
	Define(37, &sellerUserState, 
			   &sellerUserState_ind);
	Define(38, &sellerUserFlags);
	Define(39, highBidderUserId, sizeof(highBidderUserId),
			   &highBidderUserId_ind);
	Define(40, &highBidderUserState, 
			   &highBidderUserState_ind);
	Define(41, &highBidderUserFlags);
	Define(42, categoryName, sizeof(categoryName),
			   &categoryName_ind);
	Define(43, &sellerFeedbackScore, 
			   &sellerFeedbackScore_ind);
	Define(44, &highBidderFeedbackScore,
			   &highBidderFeedbackScore_ind);
	

	// Do the SQL
	Execute();

	// fetch the rows
	while(1)
	{
		Fetch();

		if (CheckForNoRowsFound())
		{
			break;
		}
		// Now everything is where it's supposed
		// to be. Let's make copies of the title
		// and location for the item
		pTitle		= new char[strlen(title) + 1];
		strcpy(pTitle, (char *)title);
		pLocation	= new char[strlen(location) + 1];
		strcpy(pLocation, (char *)location);

		// Time Conversions
		ORACLE_DATEToTime(sale_start, &sale_start_time);
		ORACLE_DATEToTime(sale_end, &sale_end_time);
		ORACLE_DATEToTime(last_modified, &last_modified_time);
		// Handle null high bidder
		if (high_bidder_ind == -1)
			high_bidder = 0;

		// Transform flags.
		if (featured[0] == '1')
			isFeatured	= true;
		else
			isFeatured	= false;

		if (superFeatured[0] == '1')
			isSuperFeatured	= true;
		else
			isSuperFeatured	= false;
	
		if (boldTitle[0] == '1')
			isBold	= true;
		else
			isBold	= false;

		if (privateSale[0] == '1')
			isPrivate	= true;
		else
			isPrivate	= false;

		if (registeredOnly[0] == '1')
			isRegisteredOnly	= true;
		else
			isRegisteredOnly	= false;

		if (host_ind == -1)
		{
			pHost	= NULL;
		}
		else
		{
			pHost	= new char[strlen(host) + 1];
			strcpy(pHost, host);
		}

		if (pictureURL_ind == -1)
		{
			pPictureURL	= NULL;
		}
		else
		{
			pPictureURL	= new char[strlen(pictureURL) + 1];
			strcpy(pPictureURL, pictureURL);
		}

		if (pIconFlags_ind == -1)
		{
			pIconFlags	= NULL;
		}
		else
		{
			pIconFlags	= new char[strlen(iconFlags) + 1];
			strcpy(pIconFlags, iconFlags);
		}

		// Handle null gallery
		if (galleryType_ind == -1)
			galleryType = NoneGallery;

		if (countryId_ind == -1)
			countryId = Country_None;

		if (currencyId_ind == -1)
			currencyId = Currency_USD;

		if (pZip_ind == -1)
		{
			pZip = NULL;
		}
		else
		{
			pZip	= new char[strlen(zip) + 1];
			strcpy(pZip, zip);
		}

		if (categoryName_ind == -1)
			categoryName[0] = '\0';

		pCategoryName	= new char[strlen(categoryName) + 1];
		strcpy(pCategoryName, categoryName);

		if (sellerUserId_ind == -1)
			sellerUserId[0]	= '\0';

		pSellerUserId	= new char[strlen(sellerUserId) + 1];
		strcpy(pSellerUserId, sellerUserId);

		if (highBidderUserId_ind == -1)
			highBidderUserId[0] = '\0';
	
		pHighBidderUserId	= new char[strlen(highBidderUserId) + 1];
		strcpy(pHighBidderUserId, highBidderUserId);

		if (sellerFeedbackScore_ind == -1)
			sellerFeedbackScore = INT_MIN;

		if (highBidderFeedbackScore_ind == -1)
			highBidderFeedbackScore	= INT_MIN;

		// nsacco 07/27/99
		if (shipping_option == -1)
		{
			if (password & ShippingInternationally)
			{
				// handle old items
				shipping_option = Worldwide;
				password = password & ~ShippingInternationally;
			}
			else
			{
				shipping_option = SiteOnly;
			}
		}

		if (ship_region_flags == -1)
		{
			ship_region_flags = ShipRegion_None;
		}

		if (desc_lang == -1)
		{
			desc_lang = English;
		}

		if (site_id == -1)
		{
			site_id = SITE_EBAY_MAIN;
		}


		// Fill in the item
		// nsacco 07/27/99 added new params
		pItem = new clsItem;
		pItem->Set(marketplace,
			id,
			saleType,
			pTitle,
			NULL,
			pLocation,
			seller,
			owner,
			category,
			bidcount,
			quantity,
			sale_start_time,
			sale_end_time,
			sale_status,
			current_price,
			start_price,
			reserve_price,
			high_bidder,
			isFeatured,
			isSuperFeatured,
			isBold,
			isPrivate, 
			isRegisteredOnly,
			pHost,
			visitcount,
			pPictureURL,
			pCategoryName,
			pSellerUserId,
			sellerUserState,
			sellerUserFlags,
			pHighBidderUserId,
			highBidderUserState,
			highBidderUserFlags,
			sellerFeedbackScore,
			highBidderFeedbackScore,
			0,
			0,
			last_modified_time,
			NULL,
			NULL,
			0,
			0,
			0,
			pIconFlags,
			NULL,
			(GalleryTypeEnum) galleryType,
			kGalleryNotProcessed,
			countryId,
			currencyId,
			false,
			pZip,
			Currency_USD,	// billing currency
			shipping_option,
			ship_region_flags,
			desc_lang,
			site_id);		// nsacco 07/27/99

		
		pvItems->push_back(pItem);
		
	}

	Close(&mpCDAGetItemsModifiedAfter);
	SetStatement(NULL);

	// Sort
	SortItems(pvItems, SortCode);
	
	return;	
}

//
// GetItemsActive
//
static const char *SQL_GetItemsCountOn =
 "select	/*+ index(ebay_items ebay_items_ending_index ) */ count(*)	\
	from ebay_items								\
	where	sale_end > 	TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')";
//	and	marketplace = :marketplace";

int clsDatabaseOracle::GetItemsCountOn(MarketPlaceId /* marketplace */, time_t enddate)
{
		// Temporary slots for things to live in
	int					count;
	char				cEndDate[64];
	struct tm			*pEndDate;	

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)

	pEndDate	= localtime(&enddate);
	TM_STRUCTToORACLE_DATE(pEndDate,
						   cEndDate);

	OpenAndParse(&mpCDAOneShot, 
		 SQL_GetItemsCountOn);
	Bind(":enddate", cEndDate);

	// Bind the rest of the input variables
//	Bind(":marketplace", &marketplace);
	
	// Bind those happy little output variables. 
	Define(1, &count);

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		// do nothing?
	}

	// Now everything is where it's supposed
	// to be.

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return count;
}

//
// Get item count since inception
//
static const char *SQL_GetItemCountSinceInception =
 "select	sum(items)										\
	from ebay_dailystatistics								\
	where	marketplace = :marketplaceid					\
	and		when > TO_DATE('1998-04-26', 'YYYY-MM-DD')		\
	and		(transaction_type = 1 or transaction_type = 3)	\
	and		categoryid = 0";

int clsDatabaseOracle::GetItemCountSinceInception(MarketPlaceId marketplaceId)
{
	// Temporary slots for things to live in
	int		count;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)

	OpenAndParse(&mpCDAOneShot, 
		 SQL_GetItemCountSinceInception);

	// Bind the rest of the input variables
	Bind(":marketplaceid", (int *)&marketplaceId);
	
	// Bind those happy little output variables. 
	Define(1, &count);

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		count = ITEM_COUNT_1998_04_26;
	}
	else
	{
		count += ITEM_COUNT_1998_04_26;
	}

	// Now everything is where it's supposed
	// to be.

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return count;
}

//
// Get daily item count
//
#if 0
static const char *SQL_GetDailyItemCount =
 "select	daily_item_count					\
	from ebay_marketplaces_info					\
	where	id = :mid";
#endif
int clsDatabaseOracle::GetDailyItemCount(MarketPlaceId /* marketplaceId */)
{
	return 0;

#if 0
	// Temporary slots for things to live in
	int					count;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)

	OpenAndParse(&mpCDAOneShot, 
		 SQL_GetDailyItemCount);

	// Bind the rest of the input variables
	Bind(":mid", (int *)&marketplaceId);
	
	// Bind those happy little output variables. 
	Define(1, &count);

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		count	= 0;
	}

	// Now everything is where it's supposed
	// to be.

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return count;
#endif
}

//
// GetActiveListingItems
//
// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id, password
static const char *SQL_GetActiveListingItems =
 "select /*+ index(ebay_items ebay_items_ending_index ) */	id,	\
			title,								\
			ROWIDTOCHAR(rowid),					\
			category,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			current_price,						\
			start_price,						\
			reserve_price,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			picture_url,						\
			icon_flags,							\
			gallery_type,						\
			gallery_state,						\
			country_id,							\
			currency_id,						\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id,							\
			password							\
	from ebay_items								\
	where	marketplace = :marketplace			\
	and		sale_end >=	TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')";

//
// GetActiveListingItems
//
// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id, password
static const char *SQL_GetCompletedListingItems =
 "select /*+ index(ebay_items ebay_items_ending_index ) */	id,	\
			title,								\
			ROWIDTOCHAR(rowid),					\
			category,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			current_price,						\
			start_price,						\
			reserve_price,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			picture_url,						\
			icon_flags,							\
			gallery_type,						\
			gallery_state,						\
			country_id,							\
			currency_id,						\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id,							\
			password							\
	from ebay_items								\
	where	marketplace = :marketplace			\
	and		sale_end < 	TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		sale_end >= TO_DATE(:enddate2,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	order by sale_end desc";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id, password
static const char *SQL_GetCompletedListingItemsEnded =
 "select /*+ index(ebay_items_ended ebay_items_ending_end_idx ) */	id,	\
			title,								\
			ROWIDTOCHAR(rowid),					\
			category,							\
			bidcount,							\
			TO_CHAR(sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			current_price,						\
			start_price,						\
			reserve_price,						\
			featured,							\
			super_featured,						\
			bold_title,							\
			picture_url,						\
			icon_flags,							\
			gallery_type,						\
			gallery_state,						\
			country_id,							\
			currency_id,						\
			shipping_option,					\
			ship_region_flags,					\
			desc_lang,							\
			site_id,							\
			password							\
	from ebay_items_ended						\
	where	marketplace = :marketplace			\
	and		sale_end < 	TO_DATE(:enddate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		sale_end >= TO_DATE(:enddate2,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	order by sale_end desc";


#define ORA_LISTINGITEM_ARRAYSIZE 5000

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id, password
void clsDatabaseOracle::GetListingItems(MarketPlaceId marketplace, 
										time_t enddate, 
										int	   ListType,
										ListingItemVector* pItemVector,
										bool splitIntoCategories, bool ended)
{
	// Temporary slots for things to live in
	int					id[ORA_LISTINGITEM_ARRAYSIZE];
	char				title[ORA_LISTINGITEM_ARRAYSIZE][255];
	char				rowid[ORA_LISTINGITEM_ARRAYSIZE][20];
	int					category[ORA_LISTINGITEM_ARRAYSIZE];
	int					bidcount[ORA_LISTINGITEM_ARRAYSIZE];
	char				sale_start[ORA_LISTINGITEM_ARRAYSIZE][32];
	time_t				sale_start_time;
	char				sale_end[ORA_LISTINGITEM_ARRAYSIZE][32];
	time_t				sale_end_time;
	float				current_price[ORA_LISTINGITEM_ARRAYSIZE];
	float				start_price[ORA_LISTINGITEM_ARRAYSIZE];
	float				reserve_price[ORA_LISTINGITEM_ARRAYSIZE];

	char				featured[ORA_LISTINGITEM_ARRAYSIZE][2];
	char				superFeatured[ORA_LISTINGITEM_ARRAYSIZE][2];
	char				boldTitle[ORA_LISTINGITEM_ARRAYSIZE][2];
	char				pictureURL[ORA_LISTINGITEM_ARRAYSIZE][256];
	sb2					pictureURL_ind[ORA_LISTINGITEM_ARRAYSIZE];
	char				iconFlags[ORA_LISTINGITEM_ARRAYSIZE][3];
	sb2					iconFlags_ind[ORA_LISTINGITEM_ARRAYSIZE];
	bool				isReserved;
	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBold;
	bool				hasPic;
	int 				giftType;
	bool				isGallery;
	bool				isFeaturedGallery;
	GalleryResultCode	theGalleryState;

	int					galleryType[ORA_LISTINGITEM_ARRAYSIZE];
	sb2					galleryType_ind[ORA_LISTINGITEM_ARRAYSIZE];

	GalleryResultCode	galleryState[ORA_LISTINGITEM_ARRAYSIZE];
	sb2					galleryState_ind[ORA_LISTINGITEM_ARRAYSIZE];


	char				cEndDate[64];
	struct tm			*pEndDate;

	time_t				enddate2;
	char				cEndDate2[64];
	struct tm			*pEndDate2;	

	clsListingItem		*pItem;

	int				rowsFetched;
	int				rc;
	int				i,n;

	int	SixDays = 6 * 24 * 60 * 60;
	int	DayIndex = 5;
	sb2					countryId_ind[ORA_LISTINGITEM_ARRAYSIZE];
	int					countryId[ORA_LISTINGITEM_ARRAYSIZE];

	sb2					currencyId_ind[ORA_LISTINGITEM_ARRAYSIZE];
	int					currencyId[ORA_LISTINGITEM_ARRAYSIZE];

	// nsacco 07/27/99
	int					shipping_option[ORA_LISTINGITEM_ARRAYSIZE];
	long				ship_region_flags[ORA_LISTINGITEM_ARRAYSIZE];
	int					desc_lang[ORA_LISTINGITEM_ARRAYSIZE];
	int					site_id[ORA_LISTINGITEM_ARRAYSIZE];
	int					password[ORA_LISTINGITEM_ARRAYSIZE];


	time_t	enddate1 = enddate + SixDays;
	enddate2 = enddate;

	// if it is for completed listing items, break it into 5 retrievals, each cover a 6 day period
	do 
	{
		enddate1 -= SixDays;

		if (ListType) // Active
		{
			OpenAndParse(&mpCDAOneShot, SQL_GetActiveListingItems);
		}
		else // Completed
		{
			if (ended)
				OpenAndParse(&mpCDAOneShot, SQL_GetCompletedListingItemsEnded);
			else
				OpenAndParse(&mpCDAOneShot, SQL_GetCompletedListingItems);

			// last 6 days
			enddate2 -= SixDays;
			pEndDate2 = localtime(&enddate2);
			TM_STRUCTToORACLE_DATE(pEndDate2,
							   cEndDate2);

			Bind(":enddate2", cEndDate2);
		}

		pEndDate	= localtime(&enddate1);
		TM_STRUCTToORACLE_DATE(pEndDate,
						   cEndDate);

		// Bind the input variable
		Bind(":marketplace", (int *)&marketplace);
		Bind(":enddate", cEndDate);

		// Bind those happy little output variables. Note that
		// we're NOT Binding the description. We'll deal with
		// that presently.
		Define(1, id);
		Define(2, title[0], sizeof(title[0]));
		Define(3, rowid[0], sizeof (rowid[0]));
		Define(4, category);
		Define(5, bidcount);
		Define(6, sale_start[0], sizeof(sale_start[0]));
		Define(7, sale_end[0], sizeof(sale_end[0]));
		Define(8, current_price);
		Define(9, start_price);
		Define(10, reserve_price);
		Define(11, featured[0], sizeof(featured[0]));
		Define(12, superFeatured[0], sizeof(superFeatured[0]));
		Define(13, boldTitle[0], sizeof(boldTitle[0]));
		Define(14, pictureURL[0], sizeof(pictureURL[0]), pictureURL_ind);
		Define(15, iconFlags[0], sizeof(iconFlags[0]), iconFlags_ind);
		Define(16, galleryType, galleryType_ind);
		Define(17, (int *)galleryState, galleryState_ind);
		Define(18, countryId, countryId_ind);
		Define(19, currencyId, currencyId_ind);
		// nsacco 07/27/99 new params
		Define(20, shipping_option);
		Define(21, ship_region_flags);
		Define(22, desc_lang);
		Define(23, site_id);
		Define(24, password);


		// Let's do the SQL
		Execute();

		if (CheckForNoRowsFound ())
		{
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			if (!ended)
				GetListingItems(marketplace, enddate, ListType, pItemVector,
										splitIntoCategories, true);
			return;
		}

		// Fetch till we're done
		rowsFetched = 0;
		do
		{
			rc = ofen((struct cda_def *)mpCDACurrent,ORA_LISTINGITEM_ARRAYSIZE);

			if ((rc < 0 || rc >= 4)  && 
				((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
			{
				Check(rc);
				ocan((struct cda_def *)mpCDACurrent);
				Close(&mpCDAOneShot,true);
				SetStatement(NULL);
				return;
			}

			// rpc is cumulative, so find out how many rows to display this time 
			// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
			n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
			rowsFetched += n;

			for (i=0; i < n; i++)
			{
				// Now everything is where it's supposed
				// to be. 
				// Time Conversions
				ORACLE_DATEToTime(sale_start[i], &sale_start_time);
				ORACLE_DATEToTime(sale_end[i], &sale_end_time);

				// Transform flags.
				isReserved = (reserve_price[i] != 0);
				isFeatured	= (featured[i][0] == '1');
				isSuperFeatured	= (superFeatured[i][0] == '1');
				isBold	= (boldTitle[i][0] == '1');
				hasPic = (pictureURL_ind[i] != -1);

				if (iconFlags_ind[i] != -1)
				{
					if (iconFlags[i][0] == 'g')
					{
						giftType = 1;
					}
					else
					{
						giftType = atoi(iconFlags[i]);
					}
				}
				else
					giftType = 0;

				// Handle null gallery
				if (galleryType_ind[i] == -1)
				galleryType[i] = NoneGallery;

				isGallery = (galleryType[i] == Gallery);
				isFeaturedGallery = (galleryType[i] == FeaturedGallery);

				if (galleryState_ind[i] == -1)
					galleryState[i] = kGalleryNotProcessed;

				theGalleryState = static_cast<GalleryResultCode>(galleryState[i]);

				if (countryId_ind[i] == -1)
					countryId[i] = Country_None;

				if (currencyId_ind[i] == -1)
					currencyId[i] = Currency_USD;

				// nsacco 07/27/99 new params
				if (shipping_option[i] == -1)
				{
					if (password[i] & ShippingInternationally)
					{
						// handle old items
						shipping_option[i] = Worldwide;
						password[i] = password[i] & ~ShippingInternationally;
					}
					else
					{
						shipping_option[i] = SiteOnly;
					}
				}

				if (ship_region_flags[i] == -1)
				{
					ship_region_flags[i] = ShipRegion_None;
				}

				if (desc_lang[i] == -1)
				{
					desc_lang[i] = English;
				}

				if (site_id[i] == -1)
				{
					site_id[i] = SITE_EBAY_MAIN;
				}

				// Fill in the item
				pItem	= new clsListingItem;
				// nsacco 07/27/99 added new params
				pItem->Set(id[i],
					   title[i],
					   rowid[i],
					   category[i],
					   bidcount[i],
					   sale_start_time,
					   sale_end_time,
					   (bidcount[i] > 0) ? current_price[i] : start_price[i],
					   isReserved,
					   isFeatured,
					   isSuperFeatured,
					   isBold,
					   hasPic,
					   giftType,
					   isGallery,
					   isFeaturedGallery,
					   theGalleryState,
					   countryId[i],
					   currencyId[i],
					   shipping_option[i],
					   ship_region_flags[i],
					   desc_lang[i],
					   site_id[i],
					   password[i]);

				if (splitIntoCategories)
					pItemVector[category[i]].push_back(pItem);
				else
					pItemVector->push_back(pItem);
			}
		} while (!CheckForNoRowsFound());

		Close (&mpCDAOneShot);
		SetStatement(NULL);
	} while (!ListType && --DayIndex > 0);
	if (!ended)
		GetListingItems(marketplace, enddate, ListType, pItemVector,
								splitIntoCategories, true);
	return;
}

//
// GetBetaCustomers
//
//
//	** NOTE **
//	Doesn't get Beta customers. Gets the ids of sellers
//	whose reserve auctions ended without meeting the selling
//	price! Used to fix a bug..
//	** NOTE **
static char *SQL_GetBetaCustomers = 
"select   distinct(seller)														\
from     ebay_items																\
where    sale_start > TO_DATE(\'08/24/97 00:01:00\', \'MM/DD/YY HH24:MI:SS\')	\
and      reserve_price > 0														\
and      sale_end < sysdate														\
and      current_price < reserve_price";				

void clsDatabaseOracle::GetBetaCustomers(vector<int> *pVUsers)
{
	int			id;
	
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetBetaCustomers);

	Define(1, &id);

	Execute();

	while(1)
	{
		Fetch();

		if (CheckForNoRowsFound())
			break;

		pVUsers->push_back(id);
	}

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


//
// GetItemsByEndDate
//
//	Gets a vector of items which ends within a given date range
//
//	** NOTE **
//	These are very much "stub" items -- they only
//	contain the marketplace and item id. 
//	** NOTE **
//
//
#define ORA_ITEMSELECT_ARRAYSIZE	1500

static const char *SQL_GetItemsByEndDate = 
	"select	/*+ index(ebay_items ebay_items_ending_index ) */	id	\
		from	ebay_items									\
		where		marketplace = :marketplace				\
		and		sale_end	>= TO_DATE(:fromdate,'YYYY-MM-DD HH24:MI:SS')	\
		and		sale_end  < TO_DATE(:todate,'YYYY-MM-DD HH24:MI:SS')	\
		order by category";

static const char *SQL_GetItemsByEndDateEnded = 
	"select	/*+ index(ebay_items_ended ebay_items_ending__end_idx ) */	id	\
		from	ebay_items_ended									\
		where		marketplace = :marketplace				\
		and		sale_end	>= TO_DATE(:fromdate,'YYYY-MM-DD HH24:MI:SS')	\
		and		sale_end  < TO_DATE(:todate,'YYYY-MM-DD HH24:MI:SS')	\
		order by category";

void clsDatabaseOracle::GetItemsByEndDate(MarketPlaceId marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate, bool ended)
{
	int			id[ORA_ITEMSELECT_ARRAYSIZE];
	int			rowsFetched;
	int			rc;
	int			i,n;
	char		cFromDate[64];
	char		cToDate[64];

	if (ended)
		OpenAndParse(&mpCDAOneShot,
				 SQL_GetItemsByEndDateEnded);
	else
	// Open and Parse
		OpenAndParse(&mpCDAOneShot,
				 SQL_GetItemsByEndDate);

	strcpy(cFromDate, fromdate);
	strcpy(cToDate, todate);

	// Bind
	Bind(":marketplace", &marketplace);
	Bind(":fromdate", cFromDate);
	Bind(":todate", cToDate);

	// Define
	Define(1, id);

	// Execute
	Execute();

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_ITEMSELECT_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_ITEMSELECT_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pvItems->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);
	if (!ended)
	{
		ended = true;
		GetItemsByEndDate(marketplace, pvItems, fromdate, todate, ended);
	}
	return;
}

//
//change item password
//
static const char *SQL_UpdateItemPassword = 
  (char *)
  "update ebay_items					\
   set password = :password				\
   where marketplace = :marketplace		\
		and id = :id";

static const char *SQL_UpdateItemPasswordEnded = 
  (char *)
  "update ebay_items_ended				\
   set password = :password				\
   where marketplace = :marketplace		\
		and id = :id";

static const char *SQL_UpdateItemPasswordArc = 
  (char *)
  "update ebay_items_arc				\
   set password = :password				\
   where marketplace = :marketplace     \
		and id = :id";

void clsDatabaseOracle::UpdateItemPassword(clsItem *pItem)
{
	int					marketplaceid;
	int					id;
    int                 password;
	bool				archive = false;

	// Extract things from the item into our
	// local variables to prevent any casting
	// confusion
	marketplaceid	= pItem->GetMarketPlaceId();
	id				= pItem->GetId();
    password        = pItem->GetPassword();
	// prepare the statement

	if (pItem->GetEnded())
		OpenAndParse(&mpCDAOneShot, SQL_UpdateItemPasswordEnded);
	else
		OpenAndParse(&mpCDAUpdateItemPassword, SQL_UpdateItemPassword);

	// Ok, let's do some binding
	Bind(":marketplace", &marketplaceid);
	Bind(":id", &id);
	Bind(":password", &password);

	// Let's do it!
	Execute();

	// If nothing was updated, then the item came from the archive
	// table. Update it there.
	if (CheckForNoRowsUpdated())
	{
		archive = true;
		if (pItem->GetEnded())
			Close(&mpCDAOneShot);
		else
			Close(&mpCDAUpdateItemPassword);
		SetStatement(NULL);

		OpenAndParse(&mpCDAOneShot, SQL_UpdateItemPasswordArc);

			// Ok, let's do some binding
		Bind(":marketplace", &marketplaceid);
		Bind(":id", &id);
		Bind(":password", &password);

			// Let's do it!
		Execute();

			// If we didn't find it in the arc, then just return. Hopeless!
		if (CheckForNoRowsUpdated())
		{
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

	//	return; INNA - if you return here cursor still opened!
	}

	// Commit
	Commit();

	// Free things
	if (pItem->GetEnded() || archive)
		Close(&mpCDAOneShot);
	else
		Close(&mpCDAUpdateItemPassword);

	SetStatement(NULL); 

	return;
}

static const char *SQL_GetItemsIdsActive =
 "select /*+ index(ebay_items ebay_items_ending_index ) */ id, category	\
	from	ebay_items								\
	where		sale_end > TO_DATE(:endDate,		\
				'YYYY-MM-DD HH24:MI:SS')";
//	and		marketplace = :marketPlace";	

static const char *SQL_GetItemsIdsSuperFeatured =
 "select /*+ index(ebay_items ebay_items_ending_index ) */ id, category	\
	from	ebay_items								\
	where	sale_end > TO_DATE(:endDate,			\
				'YYYY-MM-DD HH24:MI:SS')			\
	and		super_featured = '1'";
//	and		marketplace = :marketPlace";

static const char *SQL_GetItemsIdsAllFeatured =
 "select /*+ index(ebay_items ebay_items_ending_index ) */ id, category	\
	from	ebay_items								\
	where	sale_end > TO_DATE(:endDate,			\
				'YYYY-MM-DD HH24:MI:SS')			\
	and		(super_featured = '1' or featured = '1')";
//	and		marketplace = :marketPlace";	

static const char *SQL_GetItemsIdsHot =
 "select /*+ index(ebay_items ebay_items_ending_index ) */ id, category	\
	from	ebay_items							\
	where	sale_end > TO_DATE(:endDate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		bidcount > :hotcount				\
	and		reserve_price = 0";
//	and		marketplace = :marketPlace";	

static const char *SQL_GetItemsIdsCompleted =
 "select /*+ index(ebay_items ebay_items_ending_index ) */ id, category	\
	from	ebay_items								\
	where	sale_end <=	TO_DATE(:endDate,			\
				'YYYY-MM-DD HH24:MI:SS')";
//	and		marketplace = :marketPlace";	

static const char *SQL_GetItemsIdsCompletedEnded =
 "select /*+ index(ebay_items_ended ebay_items_ending_end_idx ) */ id, category	\
	from	ebay_items_ended						\
	where	sale_end <=	TO_DATE(:endDate,			\
				'YYYY-MM-DD HH24:MI:SS')";
//	and		marketplace = :marketPlace";	

static const char *SQL_GetItemsIdsEndLimit =
 "select /*+ index(ebay_items ebay_items_ending_index ) */ id, category	\
	from	ebay_items								\
	where	sale_end > 	TO_DATE(:endDate,		\
				'YYYY-MM-DD HH24:MI:SS')		\
	and		sale_end <= TO_DATE(:endLimitDate,	\
				'YYYY-MM-DD HH24:MI:SS')";
//	and		marketplace = :marketPlace";	

static const char *SQL_GetItemsIdsStaffPicks =
 //"select /*+ index(ebay_items ebay_items_ending_index ) */ ebay_special_items.id, ebay_items.category					
 "select ebay_special_items.id, ebay_items.category					\
	from	ebay_items, ebay_special_items			\
	where	ebay_special_items.id = ebay_items.id	\
	and		ebay_special_items.kind = '1'			\
	and		ebay_items.sale_end > TO_DATE(:endDate,	\
				'YYYY-MM-DD HH24:MI:SS')			\
	order by ebay_special_items.add_date desc";
//	and		ebay_special_items.marketplace = ebay_items.marketPlace	\
//	and		ebay_special_items.marketplace = :marketPlace";

static const char *SQL_GetItemsIdsBlackList =
// "select	/*+ index(ebay_items ebay_items_ending_index ) */ ebay_special_items.id, ebay_items.category					
 "select ebay_special_items.id, ebay_items.category					\
	from	ebay_items, ebay_special_items			\
	where	ebay_special_items.id = ebay_items.id	\
	and		ebay_special_items.kind = '2'			\
	and		ebay_items.sale_end > TO_DATE(:endDate,	\
				'YYYY-MM-DD HH24:MI:SS')			\
	order by ebay_special_items.add_date desc";
//	and		ebay_special_items.marketplace = ebay_items.marketPlace	\
//	and		ebay_special_items.marketplace = :marketPlace";

static const char *SQL_GetItemsIdsGalleryList =
// "select	/*+ index(ebay_items ebay_items_ending_index ) */ ebay_special_items.id, ebay_items.category					
 "select ebay_special_items.id, ebay_items.category	\
	from	ebay_items, ebay_special_items			\
	where	ebay_special_items.id = ebay_items.id	\
	and		ebay_special_items.kind = '3'			\
	and		ebay_items.sale_end > TO_DATE(:endDate,	\
				'YYYY-MM-DD HH24:MI:SS')			\
	order by ebay_special_items.add_date desc";
//	and		ebay_special_items.marketplace = ebay_items.marketPlace	\
//	and		ebay_special_items.marketplace = :marketPlace";

static const char *SQL_GetDescendantLeafCategoryIds =
 "select	id						\
	from	ebay_categories			\
	where	isLeaf='1'				\
	and		(id = :catId or			\
			level1 = :catId or		\
			level2 = :catId or		\
			level3 = :catId or		\
			level4 = :catId)";

static const char *SQL_GetItemsIdsHotNonDutch =
 "select /*+ index(ebay_items ebay_items_ending_index ) */ id, category	"
"	from	ebay_items							"
"	where	sale_end > TO_DATE(:endDate,		"
"				'YYYY-MM-DD HH24:MI:SS')		"
"	and		bidcount > :hotcount				"
"	and		reserve_price = 0"
"	and		quantity = 1"
"	and		marketplace = :marketPlace"
;

static const char *SQL_GetItemsIdsBigTicket =
"select	/*+ index(ebay_items ebay_items_ending_index ) */ id, category "
"	from	ebay_items "
"	where	sale_end > 	TO_DATE(:endDate, 'YYYY-MM-DD HH24:MI:SS') and "
"			current_price >= :price";

//
// GetItemIdsVector
//
// Retrieve item ids in a vector.
//
// WARNING: there is a cache associated with this routine. 
// the following item types have an up to 1 day old cache that items will be drawn from:
//		eGetSuperFeatured
//		eGetAllFeatured
//		eGetActiveRandom
//		eGetHighTicket
//		eGetHot
//
// if the cache is valid, the item IDs will be drawn from this cache instead of being calculated
// from the actual ebay_items table. this was done to speed up the hourly running of page widgets.
// the caller can set OKToUseCache to false and the cache will be ignored. it's used by default.
//

// Retrieve item ids in a vector.
void clsDatabaseOracle::GetItemIdsVector(clsMarketPlace *pMarketPlace, 
										vector<int> *pvItemIds,
										time_t endDate,
										GetItemIdsEnum queryCode /* = eGetActive */, 
										int catId /* = 0 */,
										time_t endLimitDate /* = 0 */, 
										 float price /* = 0 */,
										 bool OKToUseCache /* = true */,
										bool ended)
{
	// Temporary slots for things to live in
	int					id[ORA_ITEM_ARRAYSIZE];
	char				cEndDate[64];
	struct tm			*pEndDate;	

	char				cEndLimitDate[64];
	struct tm			*pEndLimitDate;	

	int				rowsFetched;
	int				rc;
	int				i,n;

	int				hotCount;
	int				marketPlaceId;

	int					category[ORA_ITEM_ARRAYSIZE];
	int					targetCategory[ORA_ITEM_ARRAYSIZE];
	bool				isTargetCategory[MAX_CATEGORY_ID];	// map assumes no category ids > 5000

	bool	cacheValid = false;
	clsItemsCache theCache;
	char	*scope;
	
	// check the item (widget) cache first for these items. if we cache this type, then we'll
	// look at the caches themselves for the item IDs.
	if(OKToUseCache && theCache.IsCachedItemQuery(queryCode))
		cacheValid = theCache.GetItemIdsVectorFromCache((vector<unsigned long> *) pvItemIds, 
		endDate, 
		queryCode, 
		catId,
		1 /* itemState - active or not */);	
	
	// if we have no cached data, then fall back to getting the data directly from ebay_items.
	if(!cacheValid)
	{				
	// if caller wants to limit items to a specific category, then first
	//  get the descendant category ids of the given category and 
	//  create the category boolean map of target categories
	if (catId)
	{
		for (i=0; i<MAX_CATEGORY_ID; i++) 
			isTargetCategory[i] = false;

		OpenAndParse(&mpCDAOneShot, SQL_GetDescendantLeafCategoryIds);
		Bind(":catId", &catId);
		Define(1, (int *)targetCategory);
		Execute();

		if (CheckForNoRowsFound ())
		{
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
		}

		// Fetch till we're done
		rowsFetched = 0;
		do
		{
			rc = ofen((struct cda_def *)mpCDACurrent,ORA_ITEM_ARRAYSIZE);

			if ((rc < 0 || rc >= 4)  && 
				((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
			{
				Check(rc);
				ocan((struct cda_def *)mpCDACurrent);
				Close(&mpCDAOneShot,true);
				SetStatement(NULL);
			}

			// rpc is cumulative, so find out how many rows to display this time 
			// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
			n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
			rowsFetched += n;

			// mark in the map the categories that are good
			for (i=0; i < n; i++)
			{
				isTargetCategory[targetCategory[i]] = true;
			}

		} while (!CheckForNoRowsFound());


		Close(&mpCDAOneShot);
		SetStatement(NULL);
	}

	switch (queryCode) 
	{
	case eGetActive: // active items
		OpenAndParse(&mpCDAOneShot, 
			SQL_GetItemsIdsActive);
		break;
	
	case eGetSuperFeatured: // superfeatured active items
		OpenAndParse(&mpCDAOneShot,
			SQL_GetItemsIdsSuperFeatured);
		break;
	
	case eGetHot:	// hot active items
		OpenAndParse(&mpCDAOneShot,
			SQL_GetItemsIdsHot);
		hotCount = pMarketPlace->GetHotItemCount();
		Bind(":hotcount", &hotCount);
		break;
	
	case eGetActiveRandom: // random - currently get active items. though we may want to limit this number in the future
			OpenAndParse(&mpCDAOneShot, 
				SQL_GetItemsIdsActive);
		break;

	case eGetCompleted:	// completed items
		if (ended)
			OpenAndParse(&mpCDAOneShot,
				SQL_GetItemsIdsCompletedEnded);
		else
			OpenAndParse(&mpCDAOneShot,
				SQL_GetItemsIdsCompleted);
		break;
	
	case eGetEnding:	// active items ending before endLimitDate (e.g, going going gone)
		if (endDate >= endLimitDate)
			return;
		
		OpenAndParse(&mpCDAOneShot,
			SQL_GetItemsIdsEndLimit);
		
		// get  the limit time
		pEndLimitDate	= localtime(&endLimitDate);
		TM_STRUCTToORACLE_DATE(pEndLimitDate, cEndLimitDate);
		
		// Bind the limit date
		Bind(":endLimitDate", (char *)&cEndLimitDate);
		break;
	
	case eGetStaffPicks: // active staff-pick items
		OpenAndParse(&mpCDAOneShot, 
			SQL_GetItemsIdsStaffPicks);
		break;
	
	case eGetBlackList: // active black-list items
		OpenAndParse(&mpCDAOneShot, 
			 SQL_GetItemsIdsBlackList);
		break;

	case eGetGalleryList: // active gallery-list items
		OpenAndParse(&mpCDAOneShot, 
			 SQL_GetItemsIdsGalleryList);
		break;

	case eGetAllFeatured: // all featured active items
		OpenAndParse(&mpCDAOneShot,
			SQL_GetItemsIdsAllFeatured);
		break;

	case eGetHotNonDutch:	// hot auctions, excluding dutch auctions
		OpenAndParse(&mpCDAOneShot,
			SQL_GetItemsIdsHotNonDutch);
		hotCount = pMarketPlace->GetHotItemCount();
		Bind(":hotcount", &hotCount);
		marketPlaceId = pMarketPlace->GetId();
		Bind(":marketPlace", &marketPlaceId);
		break;

	case eGetHighTicket:
			OpenAndParse(&mpCDAOneShot,
				SQL_GetItemsIdsBigTicket);
			Bind(":price", &price);
			break;
			
	default:
		// currently assume eGetActive
		OpenAndParse(&mpCDAOneShot,
			SQL_GetItemsIdsActive);
		break;
	}


	pEndDate	= localtime(&endDate);
	TM_STRUCTToORACLE_DATE(pEndDate,
						   cEndDate);

	// Bind the input variable
//	marketPlaceId = pMarketPlace->GetId();
//	Bind(":marketPlace", (int *)&marketPlaceId);
	Bind(":endDate", cEndDate);

	// Bind
	Define(1, id);
	Define(2, category);

	// Let's do the SQL
	Execute();


	if (CheckForNoRowsFound ())
	{
		if ((queryCode == eGetCompleted) && !ended)
		{
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			GetItemIdsVector(pMarketPlace, pvItemIds, endDate, queryCode, catId,
										endLimitDate, true);
		}
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot,true);
		SetStatement(NULL);
		return;
	}

	// Fetch till we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_ITEM_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			// return only those items that fit the given category
//			if ((catId == 0) || (isTargetCategory[category[i]]))	//lint !e644 We know isTargetCategory is OK
				if ((catId==0) || ((catId) && (isTargetCategory[category[i]])))
				pvItemIds->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);
	if ((queryCode == eGetCompleted) && !ended)
		GetItemIdsVector(pMarketPlace, pvItemIds, endDate, queryCode, catId,
										endLimitDate, true);

		// The stuff below is for updating the cache into the DB

		// ok, now we have a vector of item ids in pvItemIds.
		// if we've been asked to use the cache and we have too many item ids,
		//  then keep just clsItemsCache::mRandomPickLimit random ones.
		// (Don't want to be putting millions of active items in the cache!! :) )
		if ((OKToUseCache) && (theCache.IsCachedItemQuery(queryCode)) && (pvItemIds->size() > clsItemsCache::mRandomPickLimit))
		{
			scope = "R";	// signify in the cache that it will be random subset, rather
							// than a full "F" set.
			random_shuffle(pvItemIds->begin(), pvItemIds->end());
			pvItemIds->erase(pvItemIds->begin()+clsItemsCache::mRandomPickLimit,  pvItemIds->end());
		}
		else
		{
			scope = "F";
		}

		// check to see if this is an item type we cache. if so, cache it.
		if ((OKToUseCache) && (theCache.IsCachedItemQuery(queryCode)))
		{
			theCache.StoreItemIdsVectorInCache((vector<unsigned long> *) pvItemIds,
			scope,
			queryCode, 
			catId,
			1 /* itemState - active or not */);	
		}

	}  	// if(!cacheValid)
	return;

}


static const char *SQL_GetHighTicketItemIds =
"select	/*+ index(ebay_items ebay_items_ending_index ) */ id "
"	from	ebay_items "
"	where	sale_end > 	TO_DATE(:endDate, 'YYYY-MM-DD HH24:MI:SS') and "
"			current_price >= :price";
//
// Retrieve Active High Ticket Items (current_price >= Price and bidcount >= bids
//
void clsDatabaseOracle::GetHighTicketItems(vector<int> *pvItemIds, time_t endDate, float Price)
{
	// Temporary slots for things to live in
	int				id[ORA_ITEM_ARRAYSIZE];
	char			cEndDate[64];
	struct tm*		pEndDate;

	int				rowsFetched;
	int				rc;
	int				i,n;

	// Open and parse
	OpenAndParse(&mpCDAOneShot, SQL_GetHighTicketItemIds);

	pEndDate	= localtime(&endDate);
	TM_STRUCTToORACLE_DATE(pEndDate,
						   cEndDate);

	// Bind the input variable
	Bind(":endDate", cEndDate);
	Bind(":price", &Price);

	// Bind
	Define(1, (int *)id);

	// Let's do the SQL
	Execute();


	if (CheckForNoRowsFound ())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot,true);
		SetStatement(NULL);
		return;
	}

	// Fetch till we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_ITEM_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pvItemIds->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

static const char *SQL_GetItemsToArchive =
"select /*+ index(ebay_items_ended ebay_items_ending_end_idx ) */ \
	id \
	from ebay_items_ended \
	where	sale_end	>= TO_DATE(:fromdate,'YYYY-MM-DD HH24:MI:SS')	\
	and		sale_end  < TO_DATE(:todate,'YYYY-MM-DD HH24:MI:SS')" ;


void clsDatabaseOracle::GetItemsToArchive(MarketPlaceId marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate)
{
	char		cFromDate[64];
	char		cToDate[64];
	int			id[ORA_ITEMSELECT_ARRAYSIZE];
	int			rowsFetched;
	int			rc;
	int			i,n;

	// Open And Parse
	OpenAndParse(&mpCDAOneShot, 
					 SQL_GetItemsToArchive);

	strcpy(cFromDate, fromdate);
	strcpy(cToDate, todate);
	// Bind
	Bind(":fromdate", cFromDate);
	Bind(":todate", cToDate);

	// Define
	Define(1, id);

	// Execute
	Execute();

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_ITEMSELECT_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_ITEMSELECT_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pvItems->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

static const char *SQL_GetItemsToEnded =
//"select /*+ index(ebay_items ebay_items_ending_index ) */ \
//	id \
//	from ebay_items \
//	where	sale_end	>= TO_DATE(:fromdate,'YYYY-MM-DD HH24:MI:SS')	\
//	and		sale_end  < TO_DATE(:todate,'YYYY-MM-DD HH24:MI:SS') and \
//	bill_time <> NULL and notice_time <> NULL" ;
// this one for debug only 
"select /*+ index(ebay_items ebay_items_ending_index ) */ \
	id \
	from ebay_items \
	where	sale_end	>= TO_DATE(:fromdate,'YYYY-MM-DD HH24:MI:SS')	\
	and		sale_end  < TO_DATE(:todate,'YYYY-MM-DD HH24:MI:SS')" ;

void clsDatabaseOracle::GetItemsToEnded(MarketPlaceId marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate)
{
	char		cFromDate[64];
	char		cToDate[64];
	int			id[ORA_ITEMSELECT_ARRAYSIZE];
	int			rowsFetched;
	int			rc;
	int			i,n;

	// Open And Parse
	OpenAndParse(&mpCDAOneShot, 
				 SQL_GetItemsToEnded);

	strcpy(cFromDate, fromdate);
	strcpy(cToDate, todate);
	// Bind
	Bind(":fromdate", cFromDate);
	Bind(":todate", cToDate);

	// Define
	Define(1, id);

	// Execute
	Execute();

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_ITEMSELECT_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_ITEMSELECT_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pvItems->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
//	GetBiddersForItem
//
//		Returns a list of user ids who have open bids on an 
//		item (not counting retracted or cancelled bids)
//
const char *SQL_GetDistinctBiddersForItem =
"select		distinct(user_id)							\
	from	ebay_bids									\
	where	marketplace = :marketplace					\
	and		item_id = :id								\
	and		(		type = 1							\
				or	type = 2							\
			)";

const char *SQL_GetDistinctBiddersForItemEnded =
"select		distinct(user_id)							\
	from	ebay_bids_ended								\
	where	marketplace = :marketplace					\
	and		item_id = :id								\
	and		(		type = 1							\
				or	type = 2							\
			)";

void clsDatabaseOracle::GetBiddersForItem(MarketPlaceId marketplace,
										  int id,
										  list<int> *plUsers, bool ended)
{
	int			user_id;
	bool match = false;

	if (ended)
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetDistinctBiddersForItemEnded);
	else
		OpenAndParse(&mpCDAOneShot,
				 SQL_GetDistinctBiddersForItem);

	Bind(":marketplace", (int *)&marketplace);
	Bind(":id", &id);

	Define(1, &user_id);

	Execute();

	while(1)
	{
		Fetch();

		if (CheckForNoRowsFound())
		{
			ended = !ended;
			break;
		}
		plUsers->push_back(user_id);
		match = true;
	}

	Close(&mpCDAOneShot);
	SetStatement(NULL);
	if (ended && !match)
		GetBiddersForItem(marketplace, id, plUsers, ended);
	return;
}

void clsDatabaseOracle::SortItems(ItemVector *pvItems, ItemListSortEnum SortCode)
{
	// sort 'em (sort routines are declared/defined in clsItems.h/.cpp)
	if (pvItems->size() > 0)
	{
		switch	(SortCode)
		{
			case SortItemsById:
				sort(pvItems->begin(), pvItems->end(), sort_items_id);
				break;

			case SortItemsByIdReverse:
				sort(pvItems->begin(), pvItems->end(), sort_items_reverse_id);
				break;

			case SortItemsByStartTime:
				sort(pvItems->begin(), pvItems->end(), sort_items_start_time);
				break;

			case SortItemsByStartTimeReverse:
				sort(pvItems->begin(), pvItems->end(), sort_items_reverse_start_time);
				break;

			case SortItemsByEndTime:
				sort(pvItems->begin(), pvItems->end(), sort_items_end_time);
				break;

			case SortItemsByEndTimeReverse:
				sort(pvItems->begin(), pvItems->end(), sort_items_reverse_end_time);
				break;

			case SortItemsByStartPrice:
				sort(pvItems->begin(), pvItems->end(), sort_items_startprice);
				break;

			case SortItemsByStartPriceReverse:
				sort(pvItems->begin(), pvItems->end(), sort_items_reverse_startprice);
				break;

			case SortItemsByPrice:
				sort(pvItems->begin(), pvItems->end(), sort_items_price);
				break;

			case SortItemsByPriceReverse:
				sort(pvItems->begin(), pvItems->end(), sort_items_reverse_price);
				break;

			case SortItemsByReservePrice:
				sort(pvItems->begin(), pvItems->end(), sort_items_reserveprice);
				break;

			case SortItemsByReservePriceReverse:
				sort(pvItems->begin(), pvItems->end(), sort_items_reverse_reserveprice);
				break;

			case SortItemsByBidCount:
				sort(pvItems->begin(), pvItems->end(), sort_items_bidcount);
				break;

			case SortItemsByBidCountReverse:
				sort(pvItems->begin(), pvItems->end(), sort_items_reverse_bidcount);
				break;

			case SortItemsByQuantity:
				sort(pvItems->begin(), pvItems->end(), sort_items_quantity);
				break;

			case SortItemsByQuantityReverse:
				sort(pvItems->begin(), pvItems->end(), sort_items_reverse_quantity);
				break;

			case SortItemsByTitle:
				sort(pvItems->begin(), pvItems->end(), sort_items_title);
				break;

			case SortItemsByTitleReverse:
				sort(pvItems->begin(), pvItems->end(), sort_items_reverse_title);
				break;

			case SortItemsByUnknown:
			default:
				break;
		}
	}
}


//
// GetManyItemsForCreditBatch
//
//	This routine is highly optimized to get a gaggle of
//	items for credit batches. Since credit-batches are
//	only interested in a FEW fields, that's all it gets.
//
//	It also gets many items at once, so as to use array
//	fetch. We use the funky query below to take care of
//	it.
//
//
#define ORA_CREDIT_BATCH_ITEM_ARRAYSIZE 20
// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetManyItemsForCreditBatch = 
 "select	/*+ index(ebay_items items_pk ) */			\
			items.id,									\
			items.sale_type,							\
			items.title,								\
			items.location,								\
			items.seller,								\
			items.owner,								\
			items.password,								\
			items.category,								\
			items.quantity,								\
			items.bidcount,								\
			TO_CHAR(items.sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),		\
			TO_CHAR(items.sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),		\
			items.sale_status,							\
			items.current_price,						\
			items.start_price,							\
			items.reserve_price,						\
			items.high_bidder,							\
			items.featured,								\
			items.super_featured,						\
			items.bold_title,							\
			items.icon_flags,							\
			items.gallery_type,							\
			items.country_id,							\
			items.private_sale,							\
			items.registered_only,						\
			items.host,									\
			items.visitcount,							\
			items.picture_url,							\
			TO_CHAR(items.last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),				\
			ROWIDTOCHAR(items.rowid),					\
			items.currency_id,							\
			items.shipping_option,						\
			items.ship_region_flags,					\
			items.desc_lang,							\
			items.site_id								\
	from ebay_items items								\
	where	items.marketplace = :marketplace			\
	and													\
	(	items.id = :i00 or items.id = :i01 or			\
		items.id = :i02 or items.id = :i03 or			\
		items.id = :i04 or items.id = :i05 or			\
		items.id = :i06 or items.id = :i07 or			\
		items.id = :i08 or items.id = :i09 or			\
		items.id = :i10 or items.id = :i11 or			\
		items.id = :i12 or items.id = :i13 or			\
		items.id = :i14 or items.id = :i15 or			\
		items.id = :i16 or items.id = :i17 or			\
		items.id = :i18 or items.id = :i19				\
	)";



// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
void clsDatabaseOracle::GetManyItemsForCreditBatch(
								MarketPlaceId marketplace,
								list<unsigned int> *pItemIdList,
								ItemList *pItems)
{
	// Itcherator
	list<unsigned int>::iterator	iItem;

	// Things to manage our SQL statement
	bool						doneWithStatement;
	bool						doneWithList;
	int							iItemInStatement;

	// This thing is for up to ORA_CREDIT_BATCH_ITEM_ARRAYSIZE slots
	// for item ids
	int							predicateItemIds[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];

	// Array fetch goodies
	int					rowsFetched;
	int					n;
	int					i;
	int					rc;

	// Temporary slots for things to live in
	char				title[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][255];
	char				*pTitle;

	char				location[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][255];
	char				*pLocation;

	int					itemId[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	AuctionTypeEnum		saleType[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					seller[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					quantity[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					owner[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					category[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					bidcount[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					high_bidder[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					high_bidder_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	float				current_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	float				start_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	float				reserve_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				featured[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	char				superFeatured[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	char				bold[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	unsigned int		password[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				iconFlags[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][3];
	sb2					iconFlags_ind[ORA_ITEM_ARRAYSIZE];
	char				*pIconFlags;

	int					galleryType[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					galleryType_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];					

	char				privateSale[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	char				registeredOnly[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	int					visitcount[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				host[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][65];
	sb2					host_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				*pHost;

	char				pictureURL[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][256];
	sb2					pictureURL_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				*pPictureURL;

	char				sale_start[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][32];
	time_t				sale_start_time;
	char				sale_end[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][32];
	time_t				sale_end_time;
	long				sale_status[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];

	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBold;
	int					countryId[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					countryId_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];	
	
	// nsacco 07/27/99 new params
	int					shipping_option[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	long				ship_region_flags[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					desc_lang[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					site_id[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];

	bool				isPrivate;
	bool				isRegisteredOnly;

	char				last_modified[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][32];
	time_t				last_modified_time;

	int					currencyId[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					currencyId_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];	

	char				itemrowid[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][20];
	char				*pItemRowId;
	// The item
	clsItem				*pItem;


	// See if there's work to be done ;-)
	if (pItemIdList->size() < 1)
		return;

	// Let's get our statement ready
	OpenAndParse(&mpCDAGetManyItemsForCreditBatch, SQL_GetManyItemsForCreditBatch);

	// Ok, first we bind 
	Bind(":marketplace", (int *)&marketplace);
	Bind(":i00", predicateItemIds);
	Bind(":i01", &predicateItemIds[1]);
	Bind(":i02", &predicateItemIds[2]);
	Bind(":i03", &predicateItemIds[3]);
	Bind(":i04", &predicateItemIds[4]);
	Bind(":i05", &predicateItemIds[5]);
	Bind(":i06", &predicateItemIds[6]);
	Bind(":i07", &predicateItemIds[7]);
	Bind(":i08", &predicateItemIds[8]);
	Bind(":i09", &predicateItemIds[9]);
	Bind(":i10", &predicateItemIds[10]);
	Bind(":i11", &predicateItemIds[11]);
	Bind(":i12", &predicateItemIds[12]);
	Bind(":i13", &predicateItemIds[13]);
	Bind(":i14", &predicateItemIds[14]);
	Bind(":i15", &predicateItemIds[15]);
	Bind(":i16", &predicateItemIds[16]);
	Bind(":i17", &predicateItemIds[17]);
	Bind(":i18", &predicateItemIds[18]);
	Bind(":i19", &predicateItemIds[19]);



	// Now, define the output variables. 
	Define(1, itemId);
	Define(2, (int *)saleType);
	Define(3, title[0], sizeof(title[0]));
	Define(4, location[0], sizeof(location[0]));
	Define(5, seller);
	Define(6, owner);
	Define(7, password);
	Define(8, category);
	Define(9, quantity);
	Define(10, bidcount);
	Define(11, sale_start[0], sizeof(sale_start[0]));
	Define(12, sale_end[0], sizeof(sale_end[0]));
	Define(13, sale_status);
	Define(14, current_price);
	Define(15, start_price);
	Define(16, reserve_price);
	Define(17, high_bidder, high_bidder_ind);
	Define(18, featured[0], sizeof(featured[0]));
	Define(19, superFeatured[0], sizeof(superFeatured[0]));
	Define(20, bold[0], sizeof(bold[0]));
	Define(21, iconFlags[0], sizeof(iconFlags[0]), iconFlags_ind);
	Define(22, galleryType, galleryType_ind);
	Define(23, countryId, countryId_ind);
	Define(24, privateSale[0], sizeof(privateSale[0]));
	Define(25, registeredOnly[0], sizeof(registeredOnly[0]));
	Define(26, host[0], sizeof(host[0]), host_ind);
	Define(27, visitcount);
	Define(28, pictureURL[0], sizeof(pictureURL[0]), pictureURL_ind);
	Define(29, last_modified[0], sizeof(last_modified[0]));
	Define(30, itemrowid[0], sizeof(itemrowid[0]));
	Define(31, currencyId, currencyId_ind);
	// nsacco 07/27/99 new params
	Define(32, shipping_option);
	Define(33, ship_region_flags);
	Define(34, desc_lang);
	Define(35, site_id);
	

	// Ok, now, this is weird. In order to get the benefits of array
	// fetch, we needed a way to ask for _multiple_ items in one 
	// query. We either kludged this or did it very elegantly by 
	// having an "or" clause with 20 possible items in it. We now
	// need to traverse our list of items, and fill these in, one
	// by one. 

	iItemInStatement	= 0;
	doneWithStatement	= false;
	doneWithList		= false;

	for (iItem = pItemIdList->begin();
		 ;
		 iItem++)
	{
		// If we're at the end of the list, fill out the rest
		// of the predicate item ids
		if (iItem == pItemIdList->end())
		{
			// iItemInStatement is where we are now, fill it 
			// out to ORA_CREDIT_BATCH_ITEM_ARRAYSIZE...
			for (;
				 iItemInStatement < ORA_CREDIT_BATCH_ITEM_ARRAYSIZE;
				 iItemInStatement++)
			{
				predicateItemIds[iItemInStatement] = 0;
			}

			doneWithList		= true;
			doneWithStatement	= true;
		}
		else
		{
			predicateItemIds[iItemInStatement] = (*iItem);
			iItemInStatement++;

			// Let's see we've "filled up" a statement
			if (iItemInStatement >= ORA_CREDIT_BATCH_ITEM_ARRAYSIZE)
				doneWithStatement	= true;
		}

		// If we're not "done" filling in the predicate variables
		// for the statement, then just continue to get to the next
		// item in the list.
		if (!doneWithStatement)
			continue;

		// Ah! We're done filling in the predicates, so let's 
		// Execute the statement.
		Execute();

		// Now, we do the standard array fetch thing.
		rowsFetched = 0;
		do
		{
			rc = ofen((struct cda_def *)mpCDACurrent,
					  ORA_CREDIT_BATCH_ITEM_ARRAYSIZE);

			if ((rc < 0 || rc >= 4)  && 
				((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
			{
				Check(rc);
				ocan((struct cda_def *)mpCDACurrent);
				Close(&mpCDAGetManyItemsForCreditBatch);
				SetStatement(NULL);
				return;
			}

			// rpc is cumulative, so find out how many rows to display this time 
			// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
			n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
			rowsFetched += n;

			for (i=0; i < n; i++)
			{
				// If the item id is 0, then this row doesn't count
				if (itemId[i] == 0)
					continue;

				// Time Conversions
				ORACLE_DATEToTime(sale_start[i], &sale_start_time);
				ORACLE_DATEToTime(sale_end[i], &sale_end_time);
				ORACLE_DATEToTime(last_modified[i], &last_modified_time);
				// Transform flags.
				if (featured[i][0] == '1')
					isFeatured	= true;
				else
					isFeatured	= false;

				if (superFeatured[i][0] == '1')
					isSuperFeatured	= true;
				else
					isSuperFeatured	= false;

				if (bold[i][0] == '1')
					isBold	= true;
				else
					isBold	= false;

				if (iconFlags_ind[i] == -1)
				{
					pIconFlags	= NULL;
				}
				else
				{
					pIconFlags	= new char[strlen(iconFlags[i]) + 1];
					strcpy(pIconFlags, iconFlags[i]);
				}

				// Handle null gallery
				if (galleryType_ind[i] == -1)
					galleryType[i] = NoneGallery;

				if (privateSale[i][0] == '1')
					isPrivate	= true;
				else
					isPrivate	= false;

				if (registeredOnly[i][0] == '1')
					isRegisteredOnly	= true;
				else
					isRegisteredOnly	= false;

				pTitle	= new char[strlen(title[i]) + 1];
				strcpy(pTitle, title[i]);

				pLocation	= new char[strlen(location[i]) + 1];
				strcpy(pLocation, location[i]);

				if (host_ind[i] == -1)
				{
					pHost	= NULL;
				}
				else
				{
					pHost	= new char[strlen(host[i]) + 1];
					strcpy(pHost, host[i]);
				}
	
				if (pictureURL_ind[i] == -1)
				{
					pPictureURL	= NULL;
				}
				else
				{
					pPictureURL	= new char[strlen(pictureURL[i]) + 1];
					strcpy(pPictureURL, pictureURL[i]);
				}

				if (countryId_ind[i] == -1)
					countryId[i] = Country_None;

				if (high_bidder_ind[i] == -1)
					high_bidder[i] = 0;

				pItemRowId	= new char[strlen(itemrowid[i]) + 1];
				strcpy(pItemRowId, itemrowid[i]);

				if (currencyId_ind[i] == -1)
					currencyId[i] = Currency_USD;

				// nsacco 07/27/99 new params
				if (shipping_option[i] == -1)
				{
					if (password[i] & ShippingInternationally)
					{
						// handle old items
						shipping_option[i] = Worldwide;
						password[i] = password[i] & ~ShippingInternationally;
					}
					else
					{
						shipping_option[i] = SiteOnly;
					}
				}

				if (ship_region_flags[i] == -1)
				{
					ship_region_flags[i] = ShipRegion_None;
				}

				if (desc_lang[i] == -1)
				{
					desc_lang[i] = English;
				}

				if (site_id[i] == -1)
				{
					site_id[i] = SITE_EBAY_MAIN;
				}

				// Now everything is where it's supposed
				// to be. Fill in the item
				pItem	= new clsItem;
				// nsacco 07/27/99 added new params
				pItem->Set(marketplace,					//MarketPlaceId marketPlaceid, 
						   itemId[i],					// int id,
						   saleType[i],					// AuctionTypeEnum auctionType,
						   pTitle,						// char *pTitle,
						   NULL,						// char *pDescription,
						   pLocation,					// char *pLocation,
						   seller[i],					// int seller,
						   seller[i],					// int owner,
						   category[i],					// CategoryId category,
						   bidcount[i],					// int bidCount,
						   quantity[i],					// int quantity, 
						   sale_start_time,				// long startTime,
						   sale_end_time,				// long endTime,
						   sale_status[i],				// long status, 
						   current_price[i],			// double price,
						   start_price[i],				// double startPrice,
						   reserve_price[i],			// double reservePrice,
						   high_bidder[i],				// int highBidder,
						   isFeatured,					// bool featured,
						   isSuperFeatured,				// bool superFeatured,
						   isBold,						// bool boldTitle,
						   isPrivate,					// bool privateAuction,
						   isRegisteredOnly,			// bool registeredOnly,
						   pHost,						// char *pHost,
						   visitcount[i],				// int visitCount,
						   pPictureURL,					// char *pPictureURL,
						   NULL,						// char *pCategoryName,
						   NULL,						// char *pSellerUserId,
						   UserUnknown,					// int sellerUserState,
						   0,							// int sellerUserFlags,
						   NULL,						// char *pHighBidderUserId,
						   UserUnknown,					// int highBidderUserState,
						   0,							// int highBidderUserFlags,
						   INT_MIN,						// int sellerFeedbackScore,
						   INT_MIN,						// int highBidderFeedbackScore,
						   0L,							// long sellerIdLastChange,
						   0L,							// long highBidderIdLastChange,
						   last_modified_time,			// long lastModified,
						   NULL,						// const char *pSellerEmail
						   NULL,						// const char *pHighBidderEmail
						   password[i],					// int password
						   pItemRowId,					// char *pRowId
						   0,							// long delta
						   pIconFlags,					// char *pIconFlags
						   NULL,						// char *pGalleryURL
						   (GalleryTypeEnum) galleryType[i],	// GalleryTypeEnum galleryType
						   kGalleryNotProcessed,		// GalleryResultCode galleryState
						   countryId[i],				// int countryId
						   currencyId[i],				// int currencyId,
						   false,						// bool ended
						   NULL,						// const char *pZip
						   Currency_USD,				// int billingCurrency
						   shipping_option[i],
						   ship_region_flags[i],
						   desc_lang[i],
						   site_id[i]					// nsacco 07/27/99
						);

				pItems->push_back(clsItemPtr(pItem));
			}
		} while (!CheckForNoRowsFound());

		// Ok, we've handled ORA_CREDIT_BATCH_ITEM_ARRAYSIZE items from
		// the array. If we're not done yet, let's reset some things,
		// and move on, otherwise, just break!
		if (doneWithList)
			break;
		
		iItemInStatement	= 0;
		doneWithStatement	= false;
	}	

	// Clean up
	Close(&mpCDAGetManyItemsForCreditBatch);
	SetStatement(NULL);
	return;
}

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetManyEndedItemsForCreditBatch =
 "select	/*+ index(ebay_items_ended items_ended_pk ) */			\
			items.id,									\
			items.sale_type,							\
			items.title,								\
			items.location,								\
			items.seller,								\
			items.owner,								\
			items.password,								\
			items.category,								\
			items.quantity,								\
			items.bidcount,								\
			TO_CHAR(items.sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),		\
			TO_CHAR(items.sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),		\
			items.sale_status,							\
			items.current_price,						\
			items.start_price,							\
			items.reserve_price,						\
			items.high_bidder,							\
			items.featured,								\
			items.super_featured,						\
			items.bold_title,							\
			items.icon_flags,							\
			items.gallery_type,							\
			items.country_id,							\
			items.private_sale,							\
			items.registered_only,						\
			items.host,									\
			items.visitcount,							\
			items.picture_url,							\
			TO_CHAR(items.last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),				\
			ROWIDTOCHAR(items.rowid),					\
			items.currency_id,							\
			items.shipping_options,						\
			items.ship_region_flags,					\
			items.desc_lang,							\
			items.site_id								\
	from ebay_items_ended items							\
	where	items.marketplace = :marketplace and		\
	(	items.id = :i00 or items.id = :i01 or			\
		items.id = :i02 or items.id = :i03 or			\
		items.id = :i04 or items.id = :i05 or			\
		items.id = :i06 or items.id = :i07 or			\
		items.id = :i08 or items.id = :i09 or			\
		items.id = :i10 or items.id = :i11 or			\
		items.id = :i12 or items.id = :i13 or			\
		items.id = :i14 or items.id = :i15 or			\
		items.id = :i16 or items.id = :i17 or			\
		items.id = :i18 or items.id = :i19				\
	)";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
void clsDatabaseOracle::GetManyEndedItemsForCreditBatch(
								MarketPlaceId marketplace,
								list<unsigned int> *pItemIdList,
								ItemList *pItems)
{
	// Itcherator
	list<unsigned int>::iterator	iItem;

	// Things to manage our SQL statement
	bool						doneWithStatement;
	bool						doneWithList;
	int							iItemInStatement;

	// This thing is for up to ORA_CREDIT_BATCH_ITEM_ARRAYSIZE slots
	// for item ids
	int							predicateItemIds[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];

	// Array fetch goodies
	int					rowsFetched;
	int					n;
	int					i;
	int					rc;

	char				title[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][255];
	char				*pTitle;

	char				location[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][255];
	char				*pLocation;

	int					itemId[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	AuctionTypeEnum		saleType[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					seller[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					owner[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					category[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					quantity[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					bidcount[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					high_bidder[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					high_bidder_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	float				current_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	float				start_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	float				reserve_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				featured[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	char				superFeatured[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	char				bold[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	unsigned int		password[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				iconFlags[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][3];
	sb2					iconFlags_ind[ORA_ITEM_ARRAYSIZE];
	char				*pIconFlags;

	int					galleryType[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					galleryType_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];					

	char				privateSale[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	char				registeredOnly[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	int					visitcount[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				host[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][65];
	sb2					host_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				*pHost;

	char				pictureURL[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][256];
	sb2					pictureURL_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				*pPictureURL;

	char				sale_start[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][32];
	time_t				sale_start_time;
	char				sale_end[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][32];
	time_t				sale_end_time;
	long				sale_status[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];

	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBold;
	int					countryId[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					countryId_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];					


	bool				isPrivate;
	bool				isRegisteredOnly;

	char				last_modified[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][32];
	time_t				last_modified_time;

	int					currencyId[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					currencyId_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	
	// nsacco 07/27/99 new params
	int					shipping_option[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	long				ship_region_flags[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					desc_lang[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					site_id[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];

	char				itemrowid[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][20];
	char				*pItemRowId;
	// The item
	clsItem				*pItem;


	// See if there's work to be done ;-)
	if (pItemIdList->size() < 1)
		return;

	// Let's get our statement ready
	OpenAndParse(&mpCDAGetManyEndedItemsForCreditBatch, SQL_GetManyEndedItemsForCreditBatch);

	// Ok, first we bind 
	Bind(":marketplace", (int *)&marketplace);
	Bind(":i00", predicateItemIds);
	Bind(":i01", &predicateItemIds[1]);
	Bind(":i02", &predicateItemIds[2]);
	Bind(":i03", &predicateItemIds[3]);
	Bind(":i04", &predicateItemIds[4]);
	Bind(":i05", &predicateItemIds[5]);
	Bind(":i06", &predicateItemIds[6]);
	Bind(":i07", &predicateItemIds[7]);
	Bind(":i08", &predicateItemIds[8]);
	Bind(":i09", &predicateItemIds[9]);
	Bind(":i10", &predicateItemIds[10]);
	Bind(":i11", &predicateItemIds[11]);
	Bind(":i12", &predicateItemIds[12]);
	Bind(":i13", &predicateItemIds[13]);
	Bind(":i14", &predicateItemIds[14]);
	Bind(":i15", &predicateItemIds[15]);
	Bind(":i16", &predicateItemIds[16]);
	Bind(":i17", &predicateItemIds[17]);
	Bind(":i18", &predicateItemIds[18]);
	Bind(":i19", &predicateItemIds[19]);



	// Now, define the output variables. 
	Define(1, itemId);
	Define(2, (int *)saleType);
	Define(3, title[0], sizeof(title[0]));
	Define(4, location[0], sizeof(location[0]));
	Define(5, seller);
	Define(6, owner);
	Define(7, password);
	Define(8, category);
	Define(9, quantity);
	Define(10, bidcount);
	Define(11, sale_start[0], sizeof(sale_start[0]));
	Define(12, sale_end[0], sizeof(sale_end[0]));
	Define(13, sale_status);
	Define(14, current_price);
	Define(15, start_price);
	Define(16, reserve_price);
	Define(17, high_bidder, high_bidder_ind);
	Define(18, featured[0], sizeof(featured[0]));
	Define(19, superFeatured[0], sizeof(superFeatured[0]));
	Define(20, bold[0], sizeof(bold[0]));
	Define(21, iconFlags[0], sizeof(iconFlags[0]), iconFlags_ind);
	Define(22, galleryType, galleryType_ind);
	Define(23, countryId, countryId_ind);
	Define(24, privateSale[0], sizeof(privateSale[0]));
	Define(25, registeredOnly[0], sizeof(registeredOnly[0]));
	Define(26, host[0], sizeof(host[0]), host_ind);
	Define(27, visitcount);
	Define(28, pictureURL[0], sizeof(pictureURL[0]), pictureURL_ind);
	Define(29, last_modified[0], sizeof(last_modified[0]));
	Define(30, itemrowid[0], sizeof(itemrowid[0]));
	Define(31, currencyId, currencyId_ind);

	// nsacco 07/27/99
	Define(32, shipping_option);
	Define(33, ship_region_flags);
	Define(34, desc_lang);
	Define(35, site_id);
	

	// Ok, now, this is weird. In order to get the benefits of array
	// fetch, we needed a way to ask for _multiple_ items in one 
	// query. We either kludged this or did it very elegantly by 
	// having an "or" clause with 20 possible items in it. We now
	// need to traverse our list of items, and fill these in, one
	// by one. 

	iItemInStatement	= 0;
	doneWithStatement	= false;
	doneWithList		= false;

	for (iItem = pItemIdList->begin();
		 ;
		 iItem++)
	{
		// If we're at the end of the list, fill out the rest
		// of the predicate item ids
		if (iItem == pItemIdList->end())
		{
			// iItemInStatement is where we are now, fill it 
			// out to ORA_CREDIT_BATCH_ITEM_ARRAYSIZE...
			for (;
				 iItemInStatement < ORA_CREDIT_BATCH_ITEM_ARRAYSIZE;
				 iItemInStatement++)
			{
				predicateItemIds[iItemInStatement] = 0;
			}

			doneWithList		= true;
			doneWithStatement	= true;
		}
		else
		{
			predicateItemIds[iItemInStatement] = (*iItem);
			iItemInStatement++;

			// Let's see we've "filled up" a statement
			if (iItemInStatement >= ORA_CREDIT_BATCH_ITEM_ARRAYSIZE)
				doneWithStatement	= true;
		}

		// If we're not "done" filling in the predicate variables
		// for the statement, then just continue to get to the next
		// item in the list.
		if (!doneWithStatement)
			continue;

		// Ah! We're done filling in the predicates, so let's 
		// Execute the statement.
		Execute();

		// Now, we do the standard array fetch thing.
		rowsFetched = 0;
		do
		{
			rc = ofen((struct cda_def *)mpCDACurrent,
					  ORA_CREDIT_BATCH_ITEM_ARRAYSIZE);

			if ((rc < 0 || rc >= 4)  && 
				((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
			{
				Check(rc);
				ocan((struct cda_def *)mpCDACurrent);
				Close(&mpCDAGetManyItemsForCreditBatch);
				SetStatement(NULL);
				return;
			}

			// rpc is cumulative, so find out how many rows to display this time 
			// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
			n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
			rowsFetched += n;

			for (i=0; i < n; i++)
			{
				// If the item id is 0, then this row doesn't count
				if (itemId[i] == 0)
					continue;

				// Time Conversions
				ORACLE_DATEToTime(sale_start[i], &sale_start_time);
				ORACLE_DATEToTime(sale_end[i], &sale_end_time);
				ORACLE_DATEToTime(last_modified[i], &last_modified_time);
				// Transform flags.
				if (featured[i][0] == '1')
					isFeatured	= true;
				else
					isFeatured	= false;

				ORACLE_DATEToTime(sale_start[i], &sale_start_time);

				if (superFeatured[i][0] == '1')
					isSuperFeatured	= true;
				else
					isSuperFeatured	= false;

				if (bold[i][0] == '1')
					isBold	= true;
				else
					isBold	= false;

				if (iconFlags_ind[i] == -1)
				{
					pIconFlags	= NULL;
				}
				else
				{
					pIconFlags	= new char[strlen(iconFlags[i]) + 1];
					strcpy(pIconFlags, iconFlags[i]);
				}

				// Handle null gallery
				if (galleryType_ind[i] == -1)
					galleryType[i] = NoneGallery;

				if (privateSale[i][0] == '1')
					isPrivate	= true;
				else
					isPrivate	= false;

				if (registeredOnly[i][0] == '1')
					isRegisteredOnly	= true;
				else
					isRegisteredOnly	= false;

				pTitle	= new char[strlen(title[i]) + 1];
				strcpy(pTitle, title[i]);

				pLocation	= new char[strlen(location[i]) + 1];
				strcpy(pLocation, location[i]);

				if (host_ind[i] == -1)
				{
					pHost	= NULL;
				}
				else
				{
					pHost	= new char[strlen(host[i]) + 1];
					strcpy(pHost, host[i]);
				}
	
				if (pictureURL_ind[i] == -1)
				{
					pPictureURL	= NULL;
				}
				else
				{
					pPictureURL	= new char[strlen(pictureURL[i]) + 1];
					strcpy(pPictureURL, pictureURL[i]);
				}

				if (countryId_ind[i] == -1)
					countryId[i] = Country_None;

				pItemRowId	= new char[strlen(itemrowid[i]) + 1];
				strcpy(pItemRowId, itemrowid[i]);

				if (currencyId_ind[i] == -1)
					currencyId[i] = Currency_USD;

				// nsacco 07/27/99
				if (shipping_option[i] == -1)
				{
					if (password[i] & ShippingInternationally)
					{
						// handle old items
						shipping_option[i] = Worldwide;
						password[i] = password[i] & ~ShippingInternationally;
					}
					else
					{
						shipping_option[i] = SiteOnly;
					}
				}

				if (ship_region_flags[i] == -1)
				{
					ship_region_flags[i] = ShipRegion_None;
				}

				if (desc_lang[i] == -1)
				{
					desc_lang[i] = English;
				}

				if (site_id[i] == -1)
				{
					site_id[i] = SITE_EBAY_MAIN;
				}

				// Now everything is where it's supposed
				// to be. Fill in the item
				pItem	= new clsItem;

				// nsacco 07/27/99 added new params
				pItem->Set(marketplace,					//MarketPlaceId marketPlaceid, 
						   itemId[i],					// int id,
						   saleType[i],					// AuctionTypeEnum auctionType,
						   pTitle,						// char *pTitle,
						   NULL,						// char *pDescription,
						   pLocation,					// char *pLocation,
						   seller[i],					// int seller,
						   seller[i],					// int owner,
						   category[i],					// CategoryId category,
						   bidcount[i],					// int bidCount,
						   quantity[i],					// int quantity, 
						   sale_start_time,				// long startTime,
						   sale_end_time,				// long endTime,
						   sale_status[i],				// long status, 
						   current_price[i],			// double price,
						   start_price[i],				// double startPrice,
						   reserve_price[i],			// double reservePrice,
						   high_bidder[i],				// int highBidder,
						   isFeatured,					// bool featured,
						   isSuperFeatured,				// bool superFeatured,
						   isBold,						// bool boldTitle,
						   isPrivate,					// bool privateAuction,
						   isRegisteredOnly,			// bool registeredOnly,
						   pHost,						// char *pHost,
						   visitcount[i],				// int visitCount,
						   pPictureURL,					// char *pPictureURL,
						   NULL,						// char *pCategoryName,
						   NULL,						// char *pSellerUserId,
						   UserUnknown,					// int sellerUserState,
						   0,							// int sellerUserFlags,
						   NULL,						// char *pHighBidderUserId,
						   UserUnknown,					// int highBidderUserState,
						   0,							// int highBidderUserFlags,
						   INT_MIN,						// int sellerFeedbackScore,
						   INT_MIN,						// int highBidderFeedbackScore,
						   0L,							// long sellerIdLastChange,
						   0L,							// long highBidderIdLastChange,
						   last_modified_time,			// long lastModified,
						   NULL,						// const char *pSellerEmail
						   NULL,						// const char *pHighBidderEmail
						   password[i],					// int password
						   pItemRowId,					// char *pRowId
						   0,							// long delta
						   pIconFlags,					// char *pIconFlags
						   NULL,						// char *pGalleryURL
						   (GalleryTypeEnum) galleryType[i],	// GalleryTypeEnum galleryType
						   kGalleryNotProcessed,		// GalleryResultCode galleryState
						   countryId[i],				// int countryId
						   currencyId[i],				// int currencyId,
						   false,						// bool ended
						   NULL,						// const char* pZip
						   Currency_USD,				// int billingCurrency
						   shipping_option[i],			// int shipping_option
						   ship_region_flags[i],		// long ship_region_flags,
						   desc_lang[i],				// int desc_lang
						   site_id[i]					// int site_id
							);

				pItems->push_back(clsItemPtr(pItem));
			}
		} while (!CheckForNoRowsFound());

		// Ok, we've handled ORA_CREDIT_BATCH_ITEM_ARRAYSIZE items from
		// the array. If we're not done yet, let's reset some things,
		// and move on, otherwise, just break!
		if (doneWithList)
			break;
		
		iItemInStatement	= 0;
		doneWithStatement	= false;
	}	

	// Clean up
	Close(&mpCDAGetManyEndedItemsForCreditBatch);
	SetStatement(NULL);
	return;
}

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetManyArcItemsForCreditBatch =
 "select	/*+ index(ebay_items_arc ebay_itemsarc_id_index ) */	\
			items.id,									\
			items.sale_type,							\
			items.title,								\
			items.location,								\
			items.seller,								\
			items.owner,								\
			items.password,								\
			items.category,								\
			items.quantity,								\
			items.bidcount,								\
			TO_CHAR(items.sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),		\
			TO_CHAR(items.sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),		\
			items.sale_status,							\
			items.current_price,						\
			items.start_price,							\
			items.reserve_price,						\
			items.high_bidder,							\
			items.featured,								\
			items.super_featured,						\
			items.bold_title,							\
			items.icon_flags,							\
			items.gallery_type,							\
			items.country_id,							\
			items.private_sale,							\
			items.registered_only,						\
			items.host,									\
			items.visitcount,							\
			items.picture_url,							\
			TO_CHAR(items.last_modified,				\
				'YYYY-MM-DD HH24:MI:SS'),				\
			ROWIDTOCHAR(items.rowid),					\
			items.currency_id,							\
			items.shipping_option,						\
			items.ship_region_flags,					\
			items.desc_lang,							\
			items.site_id								\
	from ebay_items_arc items							\
	where	items.marketplace = :marketplace and		\
	(	items.id = :i00 or items.id = :i01 or			\
		items.id = :i02 or items.id = :i03 or			\
		items.id = :i04 or items.id = :i05 or			\
		items.id = :i06 or items.id = :i07 or			\
		items.id = :i08 or items.id = :i09 or			\
		items.id = :i10 or items.id = :i11 or			\
		items.id = :i12 or items.id = :i13 or			\
		items.id = :i14 or items.id = :i15 or			\
		items.id = :i16 or items.id = :i17 or			\
		items.id = :i18 or items.id = :i19				\
	)";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
void clsDatabaseOracle::GetManyArcItemsForCreditBatch(
								MarketPlaceId marketplace,
								list<unsigned int> *pItemIdList,
								ItemList *pItems)
{
	// Itcherator
	list<unsigned int>::iterator	iItem;

	// Things to manage our SQL statement
	bool						doneWithStatement;
	bool						doneWithList;
	int							iItemInStatement;

	// This thing is for up to ORA_CREDIT_BATCH_ITEM_ARRAYSIZE slots
	// for item ids
	int							predicateItemIds[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];

	// Array fetch goodies
	int					rowsFetched;
	int					n;
	int					i;
	int					rc;

	char				title[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][255];
	char				*pTitle;

	char				location[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][255];
	char				*pLocation;

	int					itemId[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	AuctionTypeEnum		saleType[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					owner[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					category[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					seller[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					quantity[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					bidcount[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					high_bidder[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					high_bidder_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	float				current_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	float				start_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	float				reserve_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				featured[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	char				superFeatured[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	char				bold[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	unsigned int		password[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				iconFlags[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][3];
	sb2					iconFlags_ind[ORA_ITEM_ARRAYSIZE];
	char				*pIconFlags;

	int					galleryType[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					galleryType_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];					

	char				privateSale[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	char				registeredOnly[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	int					visitcount[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				host[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][65];
	sb2					host_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				*pHost;

	char				pictureURL[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][256];
	sb2					pictureURL_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				*pPictureURL;

	char				sale_start[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][32];
	time_t				sale_start_time;
	char				sale_end[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][32];
	time_t				sale_end_time;
	long				sale_status[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];

	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBold;
	int					countryId[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					countryId_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];	
	
	// nsacco 07/27/99 new params
	int					shipping_option[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	long				ship_region_flags[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					desc_lang[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					site_id[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];

	bool				isPrivate;
	bool				isRegisteredOnly;

	char				last_modified[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][32];
	time_t				last_modified_time;

	int					currencyId[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					currencyId_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];	

	char				itemrowid[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][20];
	char				*pItemRowId;
	// The item
	clsItem				*pItem;


	// See if there's work to be done ;-)
	if (pItemIdList->size() < 1)
		return;

	// Let's get our statement ready
	OpenAndParse(&mpCDAGetManyArcItemsForCreditBatch, SQL_GetManyArcItemsForCreditBatch);

	// Ok, first we bind 
	Bind(":marketplace", (int *)&marketplace);
	Bind(":i00", predicateItemIds);
	Bind(":i01", &predicateItemIds[1]);
	Bind(":i02", &predicateItemIds[2]);
	Bind(":i03", &predicateItemIds[3]);
	Bind(":i04", &predicateItemIds[4]);
	Bind(":i05", &predicateItemIds[5]);
	Bind(":i06", &predicateItemIds[6]);
	Bind(":i07", &predicateItemIds[7]);
	Bind(":i08", &predicateItemIds[8]);
	Bind(":i09", &predicateItemIds[9]);
	Bind(":i10", &predicateItemIds[10]);
	Bind(":i11", &predicateItemIds[11]);
	Bind(":i12", &predicateItemIds[12]);
	Bind(":i13", &predicateItemIds[13]);
	Bind(":i14", &predicateItemIds[14]);
	Bind(":i15", &predicateItemIds[15]);
	Bind(":i16", &predicateItemIds[16]);
	Bind(":i17", &predicateItemIds[17]);
	Bind(":i18", &predicateItemIds[18]);
	Bind(":i19", &predicateItemIds[19]);



	// Now, define the output variables. 
	Define(1, itemId);
	Define(2, (int *)saleType);
	Define(3, title[0], sizeof(title[0]));
	Define(4, location[0], sizeof(location[0]));
	Define(5, seller);
	Define(6, owner);
	Define(7, password);
	Define(8, category);
	Define(9, quantity);
	Define(10, bidcount);
	Define(11, sale_start[0], sizeof(sale_start[0]));
	Define(12, sale_end[0], sizeof(sale_end[0]));
	Define(13, sale_status);
	Define(14, current_price);
	Define(15, start_price);
	Define(16, reserve_price);
	Define(17, high_bidder, high_bidder_ind);
	Define(18, featured[0], sizeof(featured[0]));
	Define(19, superFeatured[0], sizeof(superFeatured[0]));
	Define(20, bold[0], sizeof(bold[0]));
	Define(21, iconFlags[0], sizeof(iconFlags[0]), iconFlags_ind);
	Define(22, galleryType, galleryType_ind);
	Define(23, countryId, countryId_ind);
	Define(24, privateSale[0], sizeof(privateSale[0]));
	Define(25, registeredOnly[0], sizeof(registeredOnly[0]));
	Define(26, host[0], sizeof(host[0]), host_ind);
	Define(27, visitcount);
	Define(28, pictureURL[0], sizeof(pictureURL[0]), pictureURL_ind);
	Define(29, last_modified[0], sizeof(last_modified[0]));
	Define(30, itemrowid[0], sizeof(itemrowid[0]));
	Define(31, currencyId, currencyId_ind);

	// nsacco 07/27/99 new params
	Define(32, shipping_option);
	Define(33, ship_region_flags);
	Define(34, desc_lang);
	Define(35, site_id);
	

	// Ok, now, this is weird. In order to get the benefits of array
	// fetch, we needed a way to ask for _multiple_ items in one 
	// query. We either kludged this or did it very elegantly by 
	// having an "or" clause with 20 possible items in it. We now
	// need to traverse our list of items, and fill these in, one
	// by one. 

	iItemInStatement	= 0;
	doneWithStatement	= false;
	doneWithList		= false;

	for (iItem = pItemIdList->begin();
		 ;
		 iItem++)
	{
		// If we're at the end of the list, fill out the rest
		// of the predicate item ids
		if (iItem == pItemIdList->end())
		{
			// iItemInStatement is where we are now, fill it 
			// out to ORA_CREDIT_BATCH_ITEM_ARRAYSIZE...
			for (;
				 iItemInStatement < ORA_CREDIT_BATCH_ITEM_ARRAYSIZE;
				 iItemInStatement++)
			{
				predicateItemIds[iItemInStatement] = 0;
			}

			doneWithList		= true;
			doneWithStatement	= true;
		}
		else
		{
			predicateItemIds[iItemInStatement] = (*iItem);
			iItemInStatement++;

			// Let's see we've "filled up" a statement
			if (iItemInStatement >= ORA_CREDIT_BATCH_ITEM_ARRAYSIZE)
				doneWithStatement	= true;
		}

		// If we're not "done" filling in the predicate variables
		// for the statement, then just continue to get to the next
		// item in the list.
		if (!doneWithStatement)
			continue;

		// Ah! We're done filling in the predicates, so let's 
		// Execute the statement.
		Execute();

		// Now, we do the standard array fetch thing.
		rowsFetched = 0;
		do
		{
			rc = ofen((struct cda_def *)mpCDACurrent,
					  ORA_CREDIT_BATCH_ITEM_ARRAYSIZE);

			if ((rc < 0 || rc >= 4)  && 
				((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
			{
				Check(rc);
				ocan((struct cda_def *)mpCDACurrent);
				Close(&mpCDAGetManyItemsForCreditBatch);
				SetStatement(NULL);
				return;
			}

			// rpc is cumulative, so find out how many rows to display this time 
			// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
			n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
			rowsFetched += n;

			for (i=0; i < n; i++)
			{
				// If the item id is 0, then this row doesn't count
				if (itemId[i] == 0)
					continue;

				// Time Conversions
				ORACLE_DATEToTime(sale_start[i], &sale_start_time);
				ORACLE_DATEToTime(sale_end[i], &sale_end_time);
				ORACLE_DATEToTime(last_modified[i], &last_modified_time);
				// Transform flags.
				if (featured[i][0] == '1')
					isFeatured	= true;
				else
					isFeatured	= false;

				ORACLE_DATEToTime(sale_start[i], &sale_start_time);

				if (superFeatured[i][0] == '1')
					isSuperFeatured	= true;
				else
					isSuperFeatured	= false;

				if (bold[i][0] == '1')
					isBold	= true;
				else
					isBold	= false;

				if (iconFlags_ind[i] == -1)
				{
					pIconFlags	= NULL;
				}
				else
				{
					pIconFlags	= new char[strlen(iconFlags[i]) + 1];
					strcpy(pIconFlags, iconFlags[i]);
				}

				// Handle null gallery
				if (galleryType_ind[i] == -1)
					galleryType[i] = NoneGallery;

				if (privateSale[i][0] == '1')
					isPrivate	= true;
				else
					isPrivate	= false;

				if (registeredOnly[i][0] == '1')
					isRegisteredOnly	= true;
				else
					isRegisteredOnly	= false;

				pTitle	= new char[strlen(title[i]) + 1];
				strcpy(pTitle, title[i]);

				pLocation	= new char[strlen(location[i]) + 1];
				strcpy(pLocation, location[i]);

				if (host_ind[i] == -1)
				{
					pHost	= NULL;
				}
				else
				{
					pHost	= new char[strlen(host[i]) + 1];
					strcpy(pHost, host[i]);
				}
	
				if (pictureURL_ind[i] == -1)
				{
					pPictureURL	= NULL;
				}
				else
				{
					pPictureURL	= new char[strlen(pictureURL[i]) + 1];
					strcpy(pPictureURL, pictureURL[i]);
				}

				if (countryId_ind[i] == -1)
					countryId[i] = Country_None;

				pItemRowId	= new char[strlen(itemrowid[i]) + 1];
				strcpy(pItemRowId, itemrowid[i]);

				if (currencyId_ind[i] == -1)
					currencyId[i] = Currency_USD;

				// nsacco 07/27/99 new params
				if (shipping_option[i] == -1)
				{
					if (password[i] & ShippingInternationally)
					{
						// handle old items
						shipping_option[i] = Worldwide;
						password[i] = password[i] & ~ShippingInternationally;
					}
					else
					{
						shipping_option[i] = SiteOnly;
					}
				}

				if (ship_region_flags[i] == -1)
				{
					ship_region_flags[i] = ShipRegion_None;
				}

				if (desc_lang[i] == -1)
				{
					desc_lang[i] = English;
				}

				if (site_id[i] == -1)
				{
					site_id[i] = SITE_EBAY_MAIN;
				}

				// Now everything is where it's supposed
				// to be. Fill in the item
				pItem	= new clsItem;
				// nsacco 07/27/99 added new params
				pItem->Set(marketplace,					//MarketPlaceId marketPlaceid, 
						   itemId[i],					// int id,
						   saleType[i],					// AuctionTypeEnum auctionType,
						   pTitle,						// char *pTitle,
						   NULL,						// char *pDescription,
						   pLocation,					// char *pLocation,
						   seller[i],					// int seller,
						   seller[i],					// int owner,
						   category[i],					// CategoryId category,
						   bidcount[i],					// int bidCount,
						   quantity[i],					// int quantity, 
						   sale_start_time,				// long startTime,
						   sale_end_time,				// long endTime,
						   sale_status[i],				// long status, 
						   current_price[i],			// double price,
						   start_price[i],				// double startPrice,
						   reserve_price[i],			// double reservePrice,
						   high_bidder[i],				// int highBidder,
						   isFeatured,					// bool featured,
						   isSuperFeatured,				// bool superFeatured,
						   isBold,						// bool boldTitle,
						   isPrivate,					// bool privateAuction,
						   isRegisteredOnly,			// bool registeredOnly,
						   pHost,						// char *pHost,
						   visitcount[i],				// int visitCount,
						   pPictureURL,					// char *pPictureURL,
						   NULL,						// char *pCategoryName,
						   NULL,						// char *pSellerUserId,
						   UserUnknown,					// int sellerUserState,
						   0,							// int sellerUserFlags,
						   NULL,						// char *pHighBidderUserId,
						   UserUnknown,					// int highBidderUserState,
						   0,							// int highBidderUserFlags,
						   INT_MIN,						// int sellerFeedbackScore,
						   INT_MIN,						// int highBidderFeedbackScore,
						   0L,							// long sellerIdLastChange,
						   0L,							// long highBidderIdLastChange,
						   last_modified_time,			// long lastModified,
						   NULL,						// const char *pSellerEmail
						   NULL,						// const char *pHighBidderEmail
						   password[i],					// int password
						   pItemRowId,					// char *pRowId
						   0,							// long delta
						   pIconFlags,					// char *pIconFlags
						   NULL,						// char *pGalleryURL
						   (GalleryTypeEnum) galleryType[i],	// GalleryTypeEnum galleryType
						   kGalleryNotProcessed,		// GalleryResultCode galleryState
						   countryId[i],				// int countryId
						   currencyId[i],				// int currencyId,
						   false,						// bool ended
						   NULL,						// const char *pZip
						   Currency_USD,				// int billingCurrency
						   shipping_option[i],			// int shipping_option
						   ship_region_flags[i],		// long ship_region_flags
						   desc_lang[i],				// int desc_lang
						   site_id[i]					// int site_id
						);

				pItems->push_back(clsItemPtr(pItem));
			}
		} while (!CheckForNoRowsFound());

		// Ok, we've handled ORA_CREDIT_BATCH_ITEM_ARRAYSIZE items from
		// the array. If we're not done yet, let's reset some things,
		// and move on, otherwise, just break!
		if (doneWithList)
			break;
		
		iItemInStatement	= 0;
		doneWithStatement	= false;
	}	

	// Clean up
	Close(&mpCDAGetManyArcItemsForCreditBatch);
	SetStatement(NULL);
	return;
}

//different from GetBidCount()
//count all bids even the bid is canceled which check ebay_bids table
static char *SQL_GetItemBids =
"	select count(*) from ebay_bids	"
"	where	marketplace = :marketplace	"
"	and item_id = :item_id";

static char *SQL_GetItemBidsEnded =
"	select count(*) from ebay_bids_ended	"
"	where	marketplace = :marketplace	"
"	and item_id = :item_id";

int clsDatabaseOracle::GetItemBids(MarketPlaceId marketplace,
									int item_id, bool ended)
{
	int		count = 0;

	if (ended)
		OpenAndParse(&mpCDAOneShot,
				 SQL_GetItemBids);
	else
		OpenAndParse(&mpCDAOneShot,
				 SQL_GetItemBids);

	Bind(":marketplace", (int *)&marketplace);
	Bind(":item_id", &item_id);

	Define(1, &count);

	ExecuteAndFetch();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return count;
}

// AddTransactionRecord
// 
// Insert a transation record in the table when item is ended
//
static const char *SQL_AddTransactionRecord =
"insert into ebay_transact_record		\
 (	item,								\
	sellerid,							\
	bidderid,							\
	ending_date							\
 )										\
 values									\
 (	:item,								\
	:sellerid,							\
	:bidderid,							\
	TO_DATE(:ending_date,				\
			'YYYY-MM-DD HH24:MI:SS')	\
)";

void clsDatabaseOracle::AddTransactionRecord(int Item, 
											 int SellerId, 
											 int* pBidderIds, 
											 int BidderCount,
											 time_t Date)
{
	char EndingDate[32];
	int	 rc;
	
	// Date conversions
	TimeToORACLE_DATE(Date, EndingDate);

	// Open + Parse
	OpenAndParse(&mpCDAAddTransactionRecord, SQL_AddTransactionRecord);

	// Bind common fields
	Bind(":item", &Item);
	Bind(":sellerid", &SellerId);
	Bind(":ending_date", EndingDate);

	for (int i = 0; i < BidderCount; i++)
	{
		// Bind bidder id field for new record
		Bind(":bidderid", &pBidderIds[i]);

		// Do it...
		// Call oexec directly so we can check the return code
		rc	= oexec((struct cda_def *)mpCDACurrent);

		// Skip this one if its a duplicate of a record in the table
		if (rc == -9)
		{
			continue;
		}

		// Otherwise, keep going...
		Check(rc);
		Commit();
	}

	Commit();

	// Leave it!
	Close(&mpCDAAddTransactionRecord);
	SetStatement(NULL);

	return;
}

// IsValidTransaction
//
// Check whether it is a valid transaction based on the item number,
// seller id, and bidder id

static const char *SQL_GetTrasactionRecord =
" select used from ebay_transact_record		\
	where item=:item and					\
		  sellerid=:sellerid and			\
		  bidderid=:bidderid";

bool clsDatabaseOracle::IsValidTransaction(int Item, int SellerId, int BidderId, char NewFlag)
{
	char used[2];
	bool IsValid = true;

	// Open + Parse
	OpenAndParse(&mpCDAGetTransactionRecord, SQL_GetTrasactionRecord);

	// Bind it, baby
	Bind(":item", &Item);
	Bind(":sellerid", &SellerId);
	Bind(":bidderid", &BidderId);

	Define(1, used, 2);

	// do it
	Execute();

	Fetch();

	if (!CheckForNoRowsFound())
	{
		// check whether the bit is set
		if (used[0] & NewFlag)
		{
			IsValid = false;
		}
	}
	else
	{
		IsValid = false;
	}

	// Leave it!
	Close(&mpCDAGetTransactionRecord);
	SetStatement(NULL);

	return IsValid;
}


// SetTransactionUsed
//
// Set used for the transcation record

static const char *SQL_SetTransactionUsed =
" update ebay_transact_record "
"  set used=:used "
"	where item=:item and "
"		  sellerid=:sellerid and "
"		  bidderid=:bidderid";

static const char *SQL_DeleteTransactionRecord =
" delete from ebay_transact_record "
"	where item=:item and "
"		  sellerid=:sellerid and"
"		  bidderid=:bidderid";

void clsDatabaseOracle::SetTransactionUsed(int Item, int SellerId, int BidderId, char NewFlag)
{
	char	used[2];
	used[1] = 0;


	// get the used field first
	// Open + Parse
	OpenAndParse(&mpCDAGetTransactionRecord, SQL_GetTrasactionRecord);

	// Bind it, baby
	Bind(":item", &Item);
	Bind(":sellerid", &SellerId);
	Bind(":bidderid", &BidderId);

	Define(1, used, 2);

	// do it
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		// could not found it, do noting
		Close(&mpCDAGetTransactionRecord);
		SetStatement(NULL);
		return;
	}
	// Leave it!
	Close(&mpCDAGetTransactionRecord);
	SetStatement(NULL);

	// do one of them:
	// (1) if the transaction has been used by both seller and bidder, remove it
	// (2) if the transaction has not been used by either seller or bidder, set the flag

	if ( ((NewFlag == TRANSACT_USED_BY_SELLER) && (used[0] & TRANSACT_USED_BY_BIDDER)) ||
		 ((NewFlag == TRANSACT_USED_BY_BIDDER) && (used[0] & TRANSACT_USED_BY_SELLER)) )
	{
		// used by both seller and bidder, delete
		// Open + Parse
		OpenAndParse(&mpCDADeleteTransactionRecord, SQL_DeleteTransactionRecord);

		// Bind it, baby
		Bind(":item", &Item);
		Bind(":sellerid", &SellerId);
		Bind(":bidderid", &BidderId);

		// do it
		Execute();

		Commit();

		// Leave it!
		Close(&mpCDADeleteTransactionRecord);
		SetStatement(NULL);
	}
	else if ( (NewFlag & used[0]) == 0 )
	{
		// not been used set it
		// Open + Parse
		OpenAndParse(&mpCDASetTransactionUsed, SQL_SetTransactionUsed);

		// merge
		used[0] |= NewFlag;

		// Bind it, baby
		Bind(":used", used, 2);
		Bind(":item", &Item);
		Bind(":sellerid", &SellerId);
		Bind(":bidderid", &BidderId);

		// do it
		Execute();

		Commit();

		// Leave it!
		Close(&mpCDASetTransactionUsed);
		SetStatement(NULL);
	}

	return;
}


//
// GetManyItemsForAuctionEnd
//
//
//	This is a totally annoying thing. It's just like 
//	GetManyItemsForCreditBatch, except it outputs a 
//	vector, instead of a list. Stoopid.
//
// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetManyItemsForAuctionEnd =
 "select	/*+ index(ebay_items items_pk ) */			\
			items.id,									\
			items.sale_type,							\
 			items.title,								\
			items.seller,								\
			items.category,								\
			items.quantity,								\
			items.bidcount,								\
 			TO_CHAR(items.sale_start,					\
 						'YYYY-MM-DD HH24:MI:SS'),		\
 			TO_CHAR(items.sale_end,						\
 						'YYYY-MM-DD HH24:MI:SS'),		\
			items.current_price,						\
			items.start_price,							\
			items.reserve_price,						\
			items.featured,								\
			items.super_featured,						\
			items.bold_title,							\
			items.password,								\
			items.icon_flags,							\
			items.gallery_state,						\
			items.gallery_type,							\
 			items.location,								\
			items.shipping_option,						\
			items.ship_region_flags,					\
			items.desc_lang,							\
			items.site_id								\
	from ebay_items items								\
	where	items.marketplace = :marketplace			\
	and													\
	(	items.id = :i00 or items.id = :i01 or			\
		items.id = :i02 or items.id = :i03 or			\
		items.id = :i04 or items.id = :i05 or			\
		items.id = :i06 or items.id = :i07 or			\
		items.id = :i08 or items.id = :i09 or			\
		items.id = :i10 or items.id = :i11 or			\
		items.id = :i12 or items.id = :i13 or			\
		items.id = :i14 or items.id = :i15 or			\
		items.id = :i16 or items.id = :i17 or			\
		items.id = :i18 or items.id = :i19				\
	)";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
void clsDatabaseOracle::GetManyItemsForAuctionEnd(
								MarketPlaceId marketplace,
								list<unsigned int> *pItemIdList,
								vector<clsItemPtr> *pItems,
								bool bGetCompleteItem)
{
	// Itcherator
	list<unsigned int>::iterator	iItem;

	// Things to manage our SQL statement
	bool						doneWithStatement;
	bool						doneWithList;
	int							iItemInStatement;

	// This thing is for up to ORA_CREDIT_BATCH_ITEM_ARRAYSIZE slots
	// for item ids
	int							predicateItemIds[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];

	// Array fetch goodies
	int					rowsFetched;
	int					n;
	int					i;
	int					rc;

	// Temporary slots for things to live in
	int					itemId[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	AuctionTypeEnum		saleType[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				title[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][EBAY_MAX_TITLE_SIZE + 1];
	int					seller[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					category[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					quantity[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					bidcount[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				sale_start[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][32];
	char				sale_end[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][32];
	float				current_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	float				start_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	float				reserve_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				featured[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	char				superFeatured[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	char				bold[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	unsigned int		password[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				iconFlags[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][4];
	sb2					iconFlags_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					galleryState[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					galleryState_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					galleryType[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					galleryType_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				location[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][EBAY_MAX_LOCATION_SIZE + 1];

	// nsacco 07/27/99 new params
	int					shipping_option[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	long				ship_region_flags[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					desc_lang[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					site_id[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];

	char				*pTitle;
	char				*pLocation;

	time_t				sale_start_time;
	time_t				sale_end_time;

	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBold;

	char				*pIconFlags;

	// The item
	clsItem				*pItem;


	// See if there's work to be done ;-)
	if (pItemIdList->size() < 1)
		return;


	// Let's get our statement ready
	OpenAndParse(&mpCDAGetManyItemsForAuctionEnd, SQL_GetManyItemsForAuctionEnd);

	// Ok, first we bind 
	Bind(":marketplace", (int *)&marketplace);
	Bind(":i00", predicateItemIds);
	Bind(":i01", &predicateItemIds[1]);
	Bind(":i02", &predicateItemIds[2]);
	Bind(":i03", &predicateItemIds[3]);
	Bind(":i04", &predicateItemIds[4]);
	Bind(":i05", &predicateItemIds[5]);
	Bind(":i06", &predicateItemIds[6]);
	Bind(":i07", &predicateItemIds[7]);
	Bind(":i08", &predicateItemIds[8]);
	Bind(":i09", &predicateItemIds[9]);
	Bind(":i10", &predicateItemIds[10]);
	Bind(":i11", &predicateItemIds[11]);
	Bind(":i12", &predicateItemIds[12]);
	Bind(":i13", &predicateItemIds[13]);
	Bind(":i14", &predicateItemIds[14]);
	Bind(":i15", &predicateItemIds[15]);
	Bind(":i16", &predicateItemIds[16]);
	Bind(":i17", &predicateItemIds[17]);
	Bind(":i18", &predicateItemIds[18]);
	Bind(":i19", &predicateItemIds[19]);



	// Now, define the output variables. 
	Define(1, itemId);
	Define(2, (int *)saleType);
	Define(3, title[0], sizeof(title[0]));
	Define(4, seller);
	Define(5, category);
	Define(6, quantity);
	Define(7, bidcount);
	Define(8, sale_start[0], sizeof(sale_start[0]));
	Define(9, sale_end[0], sizeof(sale_end[0]));
	Define(10, current_price);
	Define(11, start_price);
	Define(12, reserve_price);
	Define(13, featured[0], sizeof(featured[0]));
	Define(14, superFeatured[0], sizeof(superFeatured[0]));
	Define(15, bold[0], sizeof(bold[0]));
	Define(16, password);
	Define(17, iconFlags[0], sizeof(iconFlags[0]), iconFlags_ind);
	Define(18, galleryState, galleryState_ind);
	Define(19, galleryType, galleryType_ind);
	Define(20, location[0], sizeof(location[0]));
	// nsacco 07/27/99 new params
	Define(21, shipping_option);
	Define(22, ship_region_flags);
	Define(23, desc_lang);
	Define(24, site_id);


	// Ok, now, this is weird. In order to get the benefits of array
	// fetch, we needed a way to ask for _multiple_ items in one 
	// query. We either kludged this or did it very elegantly by 
	// having an "or" clause with 20 possible items in it. We now
	// need to traverse our list of items, and fill these in, one
	// by one. 

	iItemInStatement	= 0;
	doneWithStatement	= false;
	doneWithList		= false;

	for (iItem = pItemIdList->begin();
		 ;
		 iItem++)
	{
		//Check to see if we are getting everything
		if (bGetCompleteItem)
		{
			// Check to see if we are done
			if (iItem == pItemIdList->end())
			{
				// Set done flags and get out
				doneWithStatement	= true;
				break;
			}

			// Create item
			pItem = new clsItem;

			// Call method to get full item info including the description
			if (!GetItemWithDescription(marketplace, (*iItem), pItem))
			{
				// We didn't get the Item, so clean up
				delete pItem;
			}
			else
			{
				// We did get the item, so save it
				pItems->push_back(clsItemPtr(pItem));
			}
			
			// Done for this item, jump over the rest of this code
			//	so we can get the next item.
			continue;
		}

		// If we're at the end of the list, fill out the rest
		// of the predicate item ids
		if (iItem == pItemIdList->end())
		{
			// iItemInStatement is where we are now, fill it 
			// out to ORA_CREDIT_BATCH_ITEM_ARRAYSIZE...
			for (;
				 iItemInStatement < ORA_CREDIT_BATCH_ITEM_ARRAYSIZE;
				 iItemInStatement++)
			{
				predicateItemIds[iItemInStatement] = 0;
			}

			doneWithList		= true;
			doneWithStatement	= true;
		}
		else
		{
			predicateItemIds[iItemInStatement] = (*iItem);
			iItemInStatement++;

			// Let's see we've "filled up" a statement
			if (iItemInStatement >= ORA_CREDIT_BATCH_ITEM_ARRAYSIZE)
				doneWithStatement	= true;
		}

		// If we're not "done" filling in the predicate variables
		// for the statement, then just continue to get to the next
		// item in the list.
		if (!doneWithStatement)
			continue;

		// Ah! We're done filling in the predicates, so let's 
		// Execute the statement.
		Execute();

		// Now, we do the standard array fetch thing.
		rowsFetched = 0;
		do
		{
			rc = ofen((struct cda_def *)mpCDACurrent,
					  ORA_CREDIT_BATCH_ITEM_ARRAYSIZE);

			if ((rc < 0 || rc >= 4)  && 
				((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
			{
				Check(rc);
				ocan((struct cda_def *)mpCDACurrent);
				Close(&mpCDAGetManyItemsForAuctionEnd);
				SetStatement(NULL);
				return;
			}

			// rpc is cumulative, so find out how many rows to display this time 
			// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
			n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
			rowsFetched += n;

			for (i=0; i < n; i++)
			{
				// If the item id is 0, then this row doesn't count
				if (itemId[i] == 0)
					continue;

				// Title
				pTitle		= new char[strlen(title[i]) + 1];
				strcpy(pTitle, (char *)title[i]);

				// Location
				pLocation	= new char[strlen(location[i]) + 1];
				strcpy(pLocation, (char *)location[i]);
				
				// Sale start, sale end
				ORACLE_DATEToTime(sale_start[i], &sale_start_time);
				ORACLE_DATEToTime(sale_end[i], &sale_end_time);

				// Transform flags.
				if (featured[i][0] == '1')
					isFeatured	= true;
				else
					isFeatured	= false;

				if (superFeatured[i][0] == '1')
					isSuperFeatured	= true;
				else
					isSuperFeatured	= false;

				if (bold[i][0] == '1')
					isBold	= true;
				else
					isBold	= false;


				if (iconFlags_ind[i] == -1)
				{
					pIconFlags	= NULL;
				}
				else
				{
					pIconFlags	= new char[strlen(iconFlags[i]) + 1];
					strcpy(pIconFlags, iconFlags[i]);
				}

				// Handle null 
				if (galleryType_ind[i] == -1)
					galleryType[i] = NoneGallery;

				// Handle null 
				if (galleryState_ind[i] == -1)
					galleryState[i] = kGalleryNotProcessed;

				// nsacco 07/27/99 new params
				if (shipping_option[i] == -1)
				{
					if (password[i] & ShippingInternationally)
					{
						// handle old items
						shipping_option[i] = Worldwide;
						password[i] = password[i] & ~ShippingInternationally;
					}
					else
					{
						shipping_option[i] = SiteOnly;
					}
				}

				if (ship_region_flags[i] == -1)
				{
					ship_region_flags[i] = ShipRegion_None;
				}

				if (desc_lang[i] == -1)
				{
					desc_lang[i] = English;
				}

				if (site_id[i] == -1)
				{
					site_id[i] = SITE_EBAY_MAIN;
				}

				// Now everything is where it's supposed
				// to be. Fill in the item
				pItem	= new clsItem;
				// nsacco 07/27/99 added new params
				pItem->Set(marketplace,
						   itemId[i],
						   saleType[i],
						   pTitle,
						   NULL,
						   pLocation,
						   seller[i],
						   seller[i],
						   category[i],
						   bidcount[i],
						   quantity[i],
						   sale_start_time,
						   sale_end_time,
						   0,
						   current_price[i],
						   start_price[i],
						   reserve_price[i],
						   0,
						   isFeatured,
						   isSuperFeatured,
						   isBold,
						   FALSE,				// Private
						   FALSE,				// RegisteredOnly
						   NULL,				// Host
						   0,					// VisitCount
						   NULL,				// PictureURL
						   NULL,				// Category Name
						   NULL,				// Seller UserId
						   UserUnknown,			// Seller User State
						   0,					// Seller User Flags
						   0,					// High Bidder Userid
						   UserUnknown,			// High Bidder User State
						   0,					// High Bidder User Flags
						   INT_MIN,				// Sellers Feedback score
						   INT_MIN,				// High bidder feedback score
						   (time_t)0,			// Seller userid last changed
						   (time_t)0,			// High bidder userid last changed
						   (time_t)0,			// Last modified
						   NULL,				// ??
						   NULL,				// ??
						   password[i],			// Password
						   NULL,				// Item rowid
						   0,					// Delta
						   pIconFlags,			// Icon Flags
						   NULL,				// Gallery URL
						   (GalleryTypeEnum) galleryType[i],
						   (GalleryResultCode) galleryState[i],
						   Country_None,		// Country
						   Currency_USD,		// Currency?
						   false,				// NOT ended
						   NULL,				// zip
						   Currency_USD,		// billing currency
						   shipping_option[i],	// shipping options
						   ship_region_flags[i],// shipping regions
						   desc_lang[i],		// description lang
						   site_id[i]			// site id
				);

				pItems->push_back(clsItemPtr(pItem));
			}
		} while (!CheckForNoRowsFound());

		// Ok, we've handled ORA_CREDIT_BATCH_ITEM_ARRAYSIZE items from
		// the array. If we're not done yet, let's reset some things,
		// and move on, otherwise, just break!
		if (doneWithList)
			break;
		
		iItemInStatement	= 0;
		doneWithStatement	= false;
	}	

	// Clean up
	Close(&mpCDAGetManyItemsForAuctionEnd);
	SetStatement(NULL);

	return;
}

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetManyEndedItemsForAuctionEnd =
 "select	/*+ index(ebay_items items_pk ) */			\
			items.id,									\
			items.sale_type,							\
 			items.title,								\
			items.seller,								\
			items.category,								\
			items.quantity,								\
			items.bidcount,								\
 			TO_CHAR(items.sale_start,					\
 						'YYYY-MM-DD HH24:MI:SS'),		\
 			TO_CHAR(items.sale_end,						\
 						'YYYY-MM-DD HH24:MI:SS'),		\
			items.current_price,						\
			items.start_price,							\
			items.reserve_price,						\
			items.featured,								\
			items.super_featured,						\
			items.bold_title,							\
			items.password,								\
			items.icon_flags,							\
			items.gallery_state,						\
			items.gallery_type,							\
 			items.location,								\
			items.shipping_option,						\
			items.ship_region_flags,					\
			items.desc_lang,							\
			items.site_id								\
	from ebay_items_ended items								\
	where	items.marketplace = :marketplace			\
	and													\
	(	items.id = :i00 or items.id = :i01 or			\
		items.id = :i02 or items.id = :i03 or			\
		items.id = :i04 or items.id = :i05 or			\
		items.id = :i06 or items.id = :i07 or			\
		items.id = :i08 or items.id = :i09 or			\
		items.id = :i10 or items.id = :i11 or			\
		items.id = :i12 or items.id = :i13 or			\
		items.id = :i14 or items.id = :i15 or			\
		items.id = :i16 or items.id = :i17 or			\
		items.id = :i18 or items.id = :i19				\
	)";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
void clsDatabaseOracle::GetManyEndedItemsForAuctionEnd(
								MarketPlaceId marketplace,
								list<unsigned int> *pItemIdList,
								vector<clsItemPtr> *pItems)
{
	// Itcherator
	list<unsigned int>::iterator	iItem;

	// Things to manage our SQL statement
	bool						doneWithStatement;
	bool						doneWithList;
	int							iItemInStatement;

	// This thing is for up to ORA_CREDIT_BATCH_ITEM_ARRAYSIZE slots
	// for item ids
	int							predicateItemIds[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];

	// Array fetch goodies
	int					rowsFetched;
	int					n;
	int					i;
	int					rc;

	// Temporary slots for things to live in
	int					itemId[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	AuctionTypeEnum		saleType[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				title[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][EBAY_MAX_TITLE_SIZE + 1];
	int					seller[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					category[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					quantity[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					bidcount[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				sale_start[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][32];
	char				sale_end[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][32];
	float				current_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	float				start_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	float				reserve_price[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				featured[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	char				superFeatured[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	char				bold[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][2];
	unsigned int		password[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				iconFlags[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][4];
	sb2					iconFlags_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					galleryState[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					galleryState_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					galleryType[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	sb2					galleryType_ind[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	char				location[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE][EBAY_MAX_LOCATION_SIZE + 1];

	// nsacco 07/27/99 new params
	int					shipping_option[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	long				ship_region_flags[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					desc_lang[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];
	int					site_id[ORA_CREDIT_BATCH_ITEM_ARRAYSIZE];

	char				*pTitle;
	char				*pLocation;

	time_t				sale_start_time;
	time_t				sale_end_time;

	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBold;

	char				*pIconFlags;

	// The item
	clsItem				*pItem;


	// See if there's work to be done ;-)
	if (pItemIdList->size() < 1)
		return;


	// Let's get our statement ready
	OpenAndParse(&mpCDAGetManyEndedItemsForAuctionEnd, SQL_GetManyEndedItemsForAuctionEnd);

	// Ok, first we bind 
	Bind(":marketplace", (int *)&marketplace);
	Bind(":i00", predicateItemIds);
	Bind(":i01", &predicateItemIds[1]);
	Bind(":i02", &predicateItemIds[2]);
	Bind(":i03", &predicateItemIds[3]);
	Bind(":i04", &predicateItemIds[4]);
	Bind(":i05", &predicateItemIds[5]);
	Bind(":i06", &predicateItemIds[6]);
	Bind(":i07", &predicateItemIds[7]);
	Bind(":i08", &predicateItemIds[8]);
	Bind(":i09", &predicateItemIds[9]);
	Bind(":i10", &predicateItemIds[10]);
	Bind(":i11", &predicateItemIds[11]);
	Bind(":i12", &predicateItemIds[12]);
	Bind(":i13", &predicateItemIds[13]);
	Bind(":i14", &predicateItemIds[14]);
	Bind(":i15", &predicateItemIds[15]);
	Bind(":i16", &predicateItemIds[16]);
	Bind(":i17", &predicateItemIds[17]);
	Bind(":i18", &predicateItemIds[18]);
	Bind(":i19", &predicateItemIds[19]);



	// Now, define the output variables. 
	Define(1, itemId);
	Define(2, (int *)saleType);
	Define(3, title[0], sizeof(title[0]));
	Define(4, seller);
	Define(5, category);
	Define(6, quantity);
	Define(7, bidcount);
	Define(8, sale_start[0], sizeof(sale_start[0]));
	Define(9, sale_end[0], sizeof(sale_end[0]));
	Define(10, current_price);
	Define(11, start_price);
	Define(12, reserve_price);
	Define(13, featured[0], sizeof(featured[0]));
	Define(14, superFeatured[0], sizeof(superFeatured[0]));
	Define(15, bold[0], sizeof(bold[0]));
	Define(16, password);
	Define(17, iconFlags[0], sizeof(iconFlags[0]), iconFlags_ind);
	Define(18, galleryState, galleryState_ind);
	Define(19, galleryType, galleryType_ind);
	Define(20, location[0], sizeof(location[0]));

	// nsacco 07/27/99 new params
	Define(21, shipping_option);
	Define(22, ship_region_flags);
	Define(23, desc_lang);
	Define(24, site_id);


	// Ok, now, this is weird. In order to get the benefits of array
	// fetch, we needed a way to ask for _multiple_ items in one 
	// query. We either kludged this or did it very elegantly by 
	// having an "or" clause with 20 possible items in it. We now
	// need to traverse our list of items, and fill these in, one
	// by one. 

	iItemInStatement	= 0;
	doneWithStatement	= false;
	doneWithList		= false;

	for (iItem = pItemIdList->begin();
		 ;
		 iItem++)
	{
		// If we're at the end of the list, fill out the rest
		// of the predicate item ids
		if (iItem == pItemIdList->end())
		{
			// iItemInStatement is where we are now, fill it 
			// out to ORA_CREDIT_BATCH_ITEM_ARRAYSIZE...
			for (;
				 iItemInStatement < ORA_CREDIT_BATCH_ITEM_ARRAYSIZE;
				 iItemInStatement++)
			{
				predicateItemIds[iItemInStatement] = 0;
			}

			doneWithList		= true;
			doneWithStatement	= true;
		}
		else
		{
			predicateItemIds[iItemInStatement] = (*iItem);
			iItemInStatement++;

			// Let's see we've "filled up" a statement
			if (iItemInStatement >= ORA_CREDIT_BATCH_ITEM_ARRAYSIZE)
				doneWithStatement	= true;
		}

		// If we're not "done" filling in the predicate variables
		// for the statement, then just continue to get to the next
		// item in the list.
		if (!doneWithStatement)
			continue;

		// Ah! We're done filling in the predicates, so let's 
		// Execute the statement.
		Execute();

		// Now, we do the standard array fetch thing.
		rowsFetched = 0;
		do
		{
			rc = ofen((struct cda_def *)mpCDACurrent,
					  ORA_CREDIT_BATCH_ITEM_ARRAYSIZE);

			if ((rc < 0 || rc >= 4)  && 
				((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
			{
				Check(rc);
				ocan((struct cda_def *)mpCDACurrent);
				Close(&mpCDAGetManyItemsForAuctionEnd);
				SetStatement(NULL);
				return;
			}

			// rpc is cumulative, so find out how many rows to display this time 
			// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
			n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
			rowsFetched += n;

			for (i=0; i < n; i++)
			{
				// If the item id is 0, then this row doesn't count
				if (itemId[i] == 0)
					continue;

				// Title
				pTitle		= new char[strlen(title[i]) + 1];
				strcpy(pTitle, (char *)title[i]);

				// Location
				pLocation	= new char[strlen(location[i]) + 1];
				strcpy(pLocation, (char *)location[i]);
				
				// Sale start, sale end
				ORACLE_DATEToTime(sale_start[i], &sale_start_time);
				ORACLE_DATEToTime(sale_end[i], &sale_end_time);

				// Transform flags.
				if (featured[i][0] == '1')
					isFeatured	= true;
				else
					isFeatured	= false;

				if (superFeatured[i][0] == '1')
					isSuperFeatured	= true;
				else
					isSuperFeatured	= false;

				if (bold[i][0] == '1')
					isBold	= true;
				else
					isBold	= false;


				if (iconFlags_ind[i] == -1)
				{
					pIconFlags	= NULL;
				}
				else
				{
					pIconFlags	= new char[strlen(iconFlags[i]) + 1];
					strcpy(pIconFlags, iconFlags[i]);
				}

				// Handle null 
				if (galleryType_ind[i] == -1)
					galleryType[i] = NoneGallery;

				// Handle null 
				if (galleryState_ind[i] == -1)
					galleryState[i] = kGalleryNotProcessed;

				// nsacco 07/27/99 new params
				if (shipping_option[i] == -1)
				{
					if (password[i] & ShippingInternationally)
					{
						// handle old items
						shipping_option[i] = Worldwide;
						password[i] = password[i] & ~ShippingInternationally;
					}
					else
					{
						shipping_option[i] = SiteOnly;
					}
				}

				if (ship_region_flags[i] == -1)
				{
					ship_region_flags[i] = ShipRegion_None;
				}

				if (desc_lang[i] == -1)
				{
					desc_lang[i] = English;
				}

				if (site_id[i] == -1)
				{
					site_id[i] = SITE_EBAY_MAIN;
				}

				// Now everything is where it's supposed
				// to be. Fill in the item
				pItem	= new clsItem;
				// nsacco 07/27/99 added new params
				pItem->Set(marketplace,
						   itemId[i],
						   saleType[i],
						   pTitle,
						   NULL,
						   pLocation,
						   seller[i],
						   seller[i],
						   category[i],
						   bidcount[i],
						   quantity[i],
						   sale_start_time,
						   sale_end_time,
						   0,
						   current_price[i],
						   start_price[i],
						   reserve_price[i],
						   0,
						   isFeatured,
						   isSuperFeatured,
						   isBold,
						   FALSE,				// Private
						   FALSE,				// RegisteredOnly
						   NULL,				// Host
						   0,					// VisitCount
						   NULL,				// PictureURL
						   NULL,				// Category Name
						   NULL,				// Seller UserId
						   UserUnknown,			// Seller User State
						   0,					// Seller User Flags
						   0,					// High Bidder Userid
						   UserUnknown,			// High Bidder User State
						   0,					// High Bidder User Flags
						   INT_MIN,				// Sellers Feedback score
						   INT_MIN,				// High bidder feedback score
						   (time_t)0,			// Seller userid last changed
						   (time_t)0,			// High bidder userid last changed
						   (time_t)0,			// Last modified
						   NULL,				// ??
						   NULL,				// ??
						   password[i],			// Password
						   NULL,				// Item rowid
						   0,					// Delta
						   pIconFlags,			// Icon Flags
						   NULL,				// Gallery URL
						   (GalleryTypeEnum) galleryType[i],
						   (GalleryResultCode) galleryState[i],
						   Country_None,		// Country
						   Currency_USD,		// currency 
						   false,				// ended
						   NULL,				// zip
						   Currency_USD,		// billing currency
						   shipping_option[i],	// shipping options
						   ship_region_flags[i],// shipping regions
						   desc_lang[i],		// description lang
						   site_id[i]			// site id
				);

				pItems->push_back(clsItemPtr(pItem));
			}
		} while (!CheckForNoRowsFound());

		// Ok, we've handled ORA_CREDIT_BATCH_ITEM_ARRAYSIZE items from
		// the array. If we're not done yet, let's reset some things,
		// and move on, otherwise, just break!
		if (doneWithList)
			break;
		
		iItemInStatement	= 0;
		doneWithStatement	= false;
	}	

	// Clean up
	Close(&mpCDAGetManyItemsForAuctionEnd);
	SetStatement(NULL);

	return;
}

static char *SQL_GetAllActiveItemRowids =
"select /*+ index(ebay_items ebay_items_ending_index ) */ ROWIDTOCHAR(rowid) from ebay_items where sale_end >= sysdate";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static char *SQL_GetManyItemsForListingByRowids =
"select id, "
"	title, "
"	ROWIDTOCHAR(rowid), "
"	password , "
"	category, "
"	bidcount, "
"	TO_CHAR(sale_start, 'YYYY-MM-DD HH24:MI:SS'), "
"	TO_CHAR(sale_end, 'YYYY-MM-DD HH24:MI:SS'), "
"	current_price, "
"	start_price, "
"	reserve_price, "
"	featured, "
"	super_featured, "
"	bold_title, "
"	picture_url, "
"   icon_flags,  "
"	gallery_type, "
"	gallery_state,	"
"   country_id,   "
"   currency_id,   "
"	shipping_option, "
"	ship_region_flags, "
"	desc_lang, "
"	site_id, "
" zip "
"	from ebay_items where rowid in "
"	( "
"%s"
"	)";

static char *sSqlRowId =
" CHARTOROWID(%s)";

// Size of an oracle block divided by the size of the rowids times 10?
static const int ORA_ACTIVEROWID_SIZE = (10 * 4096 / 8);

static char *sRowIdNames[] =
{
	":i00",
	":i01",
	":i02",
	":i03",
	":i04",
	":i05",
	":i06",
	":i07",
	":i08",
	":i09",
	":i10",
	":i11",
	":i12",
	":i13",
	":i14",
	":i15",
	":i16",
	":i17",
	":i18",
	":i19",
	":i20",
	":i21",
	":i22",
	":i23",
	":i24",
	":i25",
	":i26",
	":i27",
	":i28",
	":i29",
	":i30",
	":i31",
	":i32",
	":i33",
	":i34",
	":i35",
	":i36",
	":i37",
	":i38",
	":i39",
	":i40",
	":i41",
	":i42",
	":i43",
	":i44",
	":i45",
	":i46",
	":i47",
	":i48",
	":i49",
	":i50",
	":i51",
	":i52",
	":i53",
	":i54",
	":i55",
	":i56",
	":i57",
	":i58",
	":i59",
	":i60",
	":i61",
	":i62",
	":i63",
	":i64",
	":i65",
	":i66",
	":i67",
	":i68",
	":i69",
	":i70",
	":i71",
	":i72",
	":i73",
	":i74",
	":i75",
	":i76",
	":i77",
	":i78",
	":i79",
	":i80",
	":i81",
	":i82",
	":i83",
	":i84",
	":i85",
	":i86",
	":i87",
	":i88",
	":i89",
	":i90",
	":i91",
	":i92",
	":i93",
	":i94",
	":i95",
	":i96",
	":i97",
	":i98",
	":i99",
	":i100",
	":i101",
	":i102",
	":i103",
	":i104",
	":i105",
	":i106",
	":i107",
	":i108",
	":i109",
	":i110",
	":i111",
	":i112",
	":i113",
	":i114",
	":i115",
	":i116",
	":i117",
	":i118",
	":i119",
	":i120",
	":i121",
	":i122",
	":i123",
	":i124",
	":i125",
	":i126",
	":i127",
	":i128",
	":i129",
	":i130",
	":i131",
	":i132",
	":i133",
	":i134",
	":i135",
	":i136",
	":i137",
	":i138",
	":i139",
	":i140",
	":i141",
	":i142",
	":i143",
	":i144",
	":i145",
	":i146",
	":i147",
	":i148",
	":i149",
	":i150",
	":i151",
	":i152",
	":i153",
	":i154",
	":i155",
	":i156",
	":i157",
	":i158",
	":i159",
	":i160",
	":i161",
	":i162",
	":i163",
	":i164",
	":i165",
	":i166",
	":i167",
	":i168",
	":i169",
	":i170",
	":i171",
	":i172",
	":i173",
	":i174",
	":i175",
	":i176",
	":i177",
	":i178",
	":i179",
	":i180",
	":i181",
	":i182",
	":i183",
	":i184",
	":i185",
	":i186",
	":i187",
	":i188",
	":i189",
	":i190",
	":i191",
	":i192",
	":i193",
	":i194",
	":i195",
	":i196",
	":i197",
	":i198",
	":i199",
};

static const int sRowIdNamesSize = sizeof (sRowIdNames) / sizeof (char *);
static const char *sNobodyRowId = "00000000.0000.0000";

class clsItemRowId
{
public:
	clsItemRowId() { mRowId[0] = '\0'; }
	char mRowId[20];

	clsItemRowId(const char *pRowId) { strcpy(mRowId, pRowId); }
};

// Get a huge chunk of items by ending date
// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
void clsDatabaseOracle::GetAllActiveItems(ListingItemVector * pvItems)
{
	char			*pItemsQuery;
	char			*pItemsBaseQuery;
	char			*pAt;

	char			rowIds[ORA_ACTIVEROWID_SIZE][20];
	int				rowsFetched;
	int				rc;
	int				i,n;
	int				numBinds;
	time_t			theTime;

	vector<clsItemRowId> vRowIds;
	vector<clsItemRowId>::reverse_iterator jIt;

	// Begin storage for actual items.
	// Temporary slots for things to live in
	int					id[sRowIdNamesSize];
	char				title[sRowIdNamesSize][255];
	char				rowid[sRowIdNamesSize][20];
	int					category[sRowIdNamesSize];
	int					bidcount[sRowIdNamesSize];
	int 				password[sRowIdNamesSize];
	char				sale_start[sRowIdNamesSize][32];
	time_t				sale_start_time;
	char				sale_end[sRowIdNamesSize][32];
	time_t				sale_end_time;
	float				current_price[sRowIdNamesSize];
	float				start_price[sRowIdNamesSize];
	float				reserve_price[sRowIdNamesSize];

	char				featured[sRowIdNamesSize][2];
	char				superFeatured[sRowIdNamesSize][2];
	char				boldTitle[sRowIdNamesSize][2];
	char				pictureURL[sRowIdNamesSize][256];
	sb2					pictureURL_ind[sRowIdNamesSize];
	bool				isReserved;
	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBold;
	bool				hasPic;
	int					giftType;
	char				iconFlags[sRowIdNamesSize][3];
	sb2					iconFlags_ind[sRowIdNamesSize];

	bool				isGallery;
	bool				isFeaturedGallery;
	GalleryResultCode	theGalleryState;

	int					galleryType[sRowIdNamesSize];
	sb2					galleryType_ind[sRowIdNamesSize];

	GalleryResultCode	galleryState[sRowIdNamesSize];
	sb2					galleryState_ind[sRowIdNamesSize];

	int					countryId[sRowIdNamesSize];
	sb2					countryId_ind[sRowIdNamesSize];

	int					currencyId[sRowIdNamesSize];
	sb2					currencyId_ind[sRowIdNamesSize];
        char                            zip[sRowIdNamesSize][255];
	sb2				zip_ind[sRowIdNamesSize];

	// nsacco 07/27/99
	int					shipping_option[sRowIdNamesSize];
	long				ship_region_flags[sRowIdNamesSize];
	int					desc_lang[sRowIdNamesSize];
	int					site_id[sRowIdNamesSize];


int nTotBinds = 0;

	clsListingItem		*pItem;
	// End storage for actual items.

	// We just reserve one.one million now -- we _know_ this is huge, but
	// we also tend to have this many items...
	vRowIds.reserve(2000000);
	theTime = time(NULL);

	OpenAndParse(&mpCDAOneShot, SQL_GetAllActiveItemRowids);

	// Define
	Define(1, rowIds[0], sizeof (rowIds[0]));

	// Let's do the SQL
	Execute();
	time_t nowTime = time(NULL);
	if (CheckForNoRowsFound ()) // Heh. Uh-huh.
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot,true);
		SetStatement(NULL);
		return;
	}

	// Fetch till we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_ACTIVEROWID_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
			vRowIds.push_back(clsItemRowId(rowIds[i]));
	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);
        time_t timeDiff = time(NULL) - nowTime;
	cout << "after the rowid fetch " << timeDiff << " real seconds \n";
	cout.flush();
	numBinds = sRowIdNamesSize;

	// Well, first, let's smartly reserve for the items vector.
	pvItems->reserve(vRowIds.size());

	// Initialize this before we enter the loop.
	jIt = vRowIds.rbegin();

	// Okay, so I used a goto. Here's the deal:
	// we can't 'overbind' any rowids, since this causes an oracle
	// error, so we bind as many as possible, and then we bind the last
	// ones once. We use the jump to get back here...
bind_jump:
	// Well, we'd like to believe that we're done, but this would be untrue.
	// Now that we have all of the item rowids, our next task is to fetch them
	// into the passed vector.

	// First, let's build the real query we'll use -- we could do this statically,
	// but we only have to build it once, and this gives us cleaner code...
	pItemsBaseQuery = new char [numBinds * (strlen(sRowIdNames[numBinds - 1]) - 2 + 
		1 + strlen(sSqlRowId)) + 1 - 1];
	// Whew!!!
	// Well, the length calculation goes like this:
	// We know, for every bind name, we need:
	//		the length of the name (we use the length of the last name for this, so it must be longest)
	//		but, we don't need the 2 characters used for %s in the sprintf string.
	//		however, we need a comma
	//		and we need the length of the sprintf string
	// We do need a null terminator, though.
	// However, we already had an extra character from the comma.
	//		(Since we have n entries and only n-1 commas)
	// Now... since it's long enough, let's fill it out:
	pItemsBaseQuery[0] = '\0';
	pAt = pItemsBaseQuery;
	for (n = 0; n < numBinds; ++n)
	{
		sprintf(pAt, sSqlRowId, sRowIdNames[n]);
		pAt += strlen(pAt);
		*pAt = ',';
		++pAt;
	}
	// Now, if we're done, back up one to remove the comma.
	if (n)
	{
		--pAt;
		*pAt = '\0';
	}

	// We're done with building that part of the string. Let's make the larger query...
	pItemsQuery = new char[strlen(pItemsBaseQuery) + strlen(SQL_GetManyItemsForListingByRowids) + 1];
	sprintf(pItemsQuery, SQL_GetManyItemsForListingByRowids, pItemsBaseQuery);
	delete [] pItemsBaseQuery;

	// Now, open and parse the statement once -- we'll be rebinding many times,
	// but we can use ocan() to avoid a reparse.
	OpenAndParse(&mpCDAOneShot, pItemsQuery);

	while (jIt != vRowIds.rend())
	{
		unsigned int k;

		rowsFetched = 0;

		if ((vRowIds.rend() - jIt) < numBinds)
		{
			numBinds = vRowIds.rend() - jIt;
			delete [] pItemsQuery;
			Close(&mpCDAOneShot);
			SetStatement(NULL);

			goto bind_jump; // Ick.
		}

		// Bind all the vars.
		for (k = 0; jIt != vRowIds.rend() && k < numBinds; ++k, ++jIt)
		{
			Bind(sRowIdNames[k], (*jIt).mRowId);
			nTotBinds++;
		}

		// Define our outputs.
		Define(1, id);
		Define(2, title[0], sizeof(title[0]));
		Define(3, rowid[0], sizeof (rowid[0]));
		Define(4, password);
		Define(5, category);
		Define(6, bidcount);
		Define(7, sale_start[0], sizeof(sale_start[0]));
		Define(8, sale_end[0], sizeof(sale_end[0]));
		Define(9, current_price);
		Define(10, start_price);
		Define(11, reserve_price);
		Define(12, featured[0], sizeof(featured[0]));
		Define(13, superFeatured[0], sizeof(superFeatured[0]));
		Define(14, boldTitle[0], sizeof(boldTitle[0]));
		Define(15, pictureURL[0], sizeof(pictureURL[0]),
			pictureURL_ind);
		Define(16, iconFlags[0], sizeof(iconFlags[0]),
			iconFlags_ind);
		Define(17, galleryType, galleryType_ind);
		Define(18, (int *)galleryState, galleryState_ind);
		Define(19, countryId, countryId_ind);
		Define(20, currencyId, currencyId_ind);
        Define(21, zip[0], sizeof(zip[0]), zip_ind);
		// nsacco 07/27/99
		Define(22, shipping_option);
		Define(23, ship_region_flags);
		Define(24, desc_lang);
		Define(25, site_id);


		// Let's do the SQL
		Execute();

		if (CheckForNoRowsFound ())
		{
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			delete [] pItemsQuery;
			return; // Because we're done. However, this should not be executed, due to our previous checks.
		}

		rc = ofen((struct cda_def *)mpCDACurrent, sRowIdNamesSize + 1);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			delete [] pItemsQuery;
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			// Now everything is where it's supposed
			// to be. 
			// Time Conversions
			ORACLE_DATEToTime(sale_start[i], &sale_start_time);
			ORACLE_DATEToTime(sale_end[i], &sale_end_time);

			// Transform flags.
			isReserved = (reserve_price[i] != 0);
			isFeatured	= (featured[i][0] == '1');
			isSuperFeatured	= (superFeatured[i][0] == '1');
			isBold	= (boldTitle[i][0] == '1');
			hasPic = (pictureURL_ind[i] != -1);

			if (iconFlags_ind[i] != -1)
			{
				if (iconFlags[i][0] == 'g')
				{
					giftType = 1;
				}
				else
				{
					giftType = atoi(iconFlags[i]);
				}
			}
			else
				giftType = GiftIconUnknown;

			// Handle null gallery
			if (galleryType_ind[i] == -1)
				galleryType[i] = NoneGallery;

			if (countryId_ind[i] == -1)
				countryId[i] = Country_None;

			if (currencyId_ind[i] == -1)
				currencyId[i] = Currency_USD;

			isGallery = (galleryType[i] == Gallery);
			isFeaturedGallery = (galleryType[i] == FeaturedGallery);

			// nsacco 07/27/99
			// handle null new params
			if (shipping_option[i] == -1)
			{
				if (password[i] & ShippingInternationally)
				{
					// handle old items
					shipping_option[i] = Worldwide;
					password[i] = password[i] & ~ShippingInternationally;
				}
				else
				{
					shipping_option[i] = SiteOnly;
				}
			}

			if (ship_region_flags[i] == -1)
			{
				ship_region_flags[i] = ShipRegion_None;
			}

			if (desc_lang[i] == -1)
			{
				desc_lang[i] = English;
			}

			if (site_id[i] == -1)
			{
				site_id[i] = SITE_EBAY_MAIN;
			}
			// end check for new params

			if (galleryState_ind[i] == -1)
				galleryState[i] = kGalleryNotProcessed;
			theGalleryState = static_cast<GalleryResultCode>(galleryState[i]);
			if (zip_ind[i] == -1)
				zip[i][0] = '\0';
			if (sale_end_time < theTime)
				continue; // We skip this item if it's already ended by the time we see it.

			// Fill in the item
			pItem	= new clsListingItem;
			// nsacco 07/27/99 added new params
			pItem->Set(id[i],
				   title[i],
				   rowid[i],
				   category[i],
				   bidcount[i],
				   sale_start_time,
				   sale_end_time,
				   (bidcount[i] > 0) ? current_price[i] : start_price[i],
				   isReserved,
				   isFeatured,
				   isSuperFeatured,
				   isBold,
				   hasPic,
				   giftType,
				   isGallery,
				   isFeaturedGallery,
				   theGalleryState,
				   countryId[i],
				   currencyId[i],
				   shipping_option[i],
				   ship_region_flags[i],
				   desc_lang[i],
				   site_id[i],
				   password[i],
				   zip[i]);

			pvItems->push_back(pItem);
		}

		// We use ocan here rather than Close so that we keep the cursor open,
		// even though it's only the one shot cursor.
		// We want to just rebind and redefine, rather than reparse.
		ocan((struct cda_def *)mpCDAOneShot);
	}

	// Now -- close it.
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	delete [] pItemsQuery;

	// Hey, we're done!
	// We'll get automatic cleanup on the rowid Vector.

	return;
}

// InsertItemCredit
// 
// Insert one or more item credit row 
//
static const char *SQL_InsertItemCredit =
"insert into ebay_auction_credits		\
 (										\
	item_id,							\
	bidder_id,							\
	amount,								\
	last_modified,						\
	reason_code,						\
	credit_type,						\
	quantity,							\
	batch_id							\
 )										\
 values									\
 (										\
	:item_id,							\
	:bidder_id,							\
	:amount,							\
	sysdate,							\
	:reason_code,						\
	:credit_type,						\
	:quantity,							\
	0									\
)";

// Auto Credits
// void clsDatabaseOracle::InsertItemCredit(int item_no, int bidder_id, 
//								         float amt, char *pReason)
bool clsDatabaseOracle::InsertItemCredit(CreditsVector *pvCredits)
{
	int				rc=0;
	CreditsVector ::iterator CreditsIter;
	bool			didInsert=false; // if no entry was inserted then entry already exists
	char			reason[10];


	// Open + Parse
	OpenAndParse(&mpCDAOneShot, SQL_InsertItemCredit);

	// Chinese auction will have only one pass

    for (CreditsIter  = pvCredits->begin();
         CreditsIter != pvCredits->end();
         CreditsIter++)
    {
		// Update only if data provided by user
		if ((*CreditsIter)->bidder_id == 0)
			continue;

		// Bind common fields
	    Bind(":item_id", &(*CreditsIter)->item_id);
		Bind(":bidder_id", &(*CreditsIter)->bidder_id);
		Bind(":amount", &(*CreditsIter)->amt);
		sprintf(reason, "%d", (int)(*CreditsIter)->reason_code);
		Bind(":reason_code", reason);
		Bind(":credit_type", (*CreditsIter)->credit_type);
	    Bind(":quantity", &(*CreditsIter)->quantity);

		// Write this entry
		// Do it...
		// Call oexec directly so we can check the return code
		rc	= oexec((struct cda_def *)mpCDACurrent);

		// Skip this one if its a duplicate of a record in the table
		if (rc == -9)
		{
			continue;
		}
		// Otherwise, keep going...
		Check(rc);
		if (rc == 0)
			didInsert = true; // at least one entry was made
	}


	Commit();

	// Leave it!
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return didInsert;
}

#define ORA_CREDITS_ARRAYSIZE	500

static const char *SQL_GetAllCreditsForItem =
 "select	bidder_id,								\
			amount,									\
			TO_CHAR(last_modified,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			reason_code,							\
			credit_type,							\
			quantity,								\
			batch_id								\
	from	ebay_auction_credits					\
	where	item_id		= :item_id";

void clsDatabaseOracle::GetCreditsForItem(int item_id, CreditsVector *pvCredits)
{
    int             bidder_id[ORA_CREDITS_ARRAYSIZE];
    float			amount[ORA_CREDITS_ARRAYSIZE];
    char            last_modified[ORA_CREDITS_ARRAYSIZE][32];
    time_t          the_timestamp;
    char            reason[ORA_CREDITS_ARRAYSIZE][10];
    char            credit_type[ORA_CREDITS_ARRAYSIZE][2];
    int             quantity[ORA_CREDITS_ARRAYSIZE];
    int             batch_id[ORA_CREDITS_ARRAYSIZE];
    int             rowsFetched;
    int             rc;
    int             i,n;
	sItemCredits	*pCredits;

	
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetAllCreditsForItem);

	Bind(":item_id", &item_id);

    // Define
	Define(1,  &bidder_id[0]);
    Define(2,  &amount[0]);
    Define(3,  (char*)last_modified[0], sizeof(last_modified[0]));
	Define(4,  (char *)reason[0], sizeof(reason[0]));
	Define(5,  (char *)credit_type[0], sizeof(credit_type[0]));
	Define(6,  &quantity[0]);
	Define(7,  &batch_id[0]);

    // Execute
    Execute();

    // Now we fetch until we're done
    rowsFetched = 0;
    do
    {
       rc = ofen((struct cda_def *)mpCDACurrent, ORA_CREDITS_ARRAYSIZE);

        if ((rc < 0 || rc >= 4)  &&
                ((struct cda_def *)mpCDACurrent)->rc != 1403)   // something wrong
        {
                Check(rc);
                Close(&mpCDAOneShot);
                SetStatement(NULL);
                return;
        }

        // rpc is cumulative, so find out how many rows to display this time
        // (always <= ORA_CREDITS_ARRAYSIZE).
        n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
        rowsFetched += n;

        for (i=0; i < n; i++)
        {
                // Convert time
                ORACLE_DATEToTime(last_modified[i],
                                 &the_timestamp);

                pCredits	= new sItemCredits;

				pCredits->item_id		= item_id;
				pCredits->bidder_id		= bidder_id[i];
				pCredits->amt			= amount[i];
				pCredits->last_modified = the_timestamp;
				if (reason[i])
					pCredits->reason_code = (CreditTypeEnum)atoi(reason[i]);
				else
					pCredits->reason_code = (CreditTypeEnum)0;
				strcpy(pCredits->credit_type, credit_type[i]);
				pCredits->quantity		= quantity[i];
				pCredits->batch_id		= batch_id[i];

                pvCredits->push_back(pCredits);
		}

    } while (!CheckForNoRowsFound());

    Close(&mpCDAOneShot);
    SetStatement(NULL);

    return;

}

static const char *SQL_GetCount =
"select count(*) from ebay_auction_credits			\
	where batch_id=0";

static const char *SQL_GetLargestBatchId = 
"select max(batch_id) from ebay_auction_credits				\
	where last_modified >= 	TO_DATE(:current_date,			\
									'YYYY-MM-DD HH24:MI:SS')";

int clsDatabaseOracle::GetNextBatchIdForCredits()
{
	int		batch_id = 0;
	char	current_date[32];
	time_t	Date;
	struct tm *sDate;
	int		count=-1;


	OpenAndParse(&mpCDAOneShot, SQL_GetCount);
	Define(1, (int *)&count);	
	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	if (count == -1 || count == 0)
		return count;

	
	OpenAndParse(&mpCDAOneShot, SQL_GetLargestBatchId);

	// Date conversions
	time(&Date);
	sDate = localtime(&Date);
	// Normalize time to midnight
	sDate->tm_sec = 59;
	sDate->tm_min = 59;
	sDate->tm_hour = 23;
	sDate->tm_mday = sDate->tm_mday-1;
	//Make new time
	Date = mktime(sDate);
	// Convert to Oracle format
	TimeToORACLE_DATE(Date, current_date);

	Bind(":current_date", current_date);
	Define(1, (int *)&batch_id);

	ExecuteAndFetch();

	// Sam, 6/3/99, batch_id will be -ve only if item cnt is 0 or less
	if (CheckForNoRowsFound() && count <=0)
		batch_id = -1;

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return (batch_id=batch_id+1);  // next one
}

static const char *SQL_GetMinMaxItemId = 
 "select	max(item_id),							\
			min(item_id)							\
	from	ebay_auction_credits					\
	where	batch_id	= 0";

static const char *SQL_GetAllNewCredits =
 "select	bidder_id,								\
			amount,									\
			TO_CHAR(last_modified,					\
						'YYYY-MM-DD HH24:MI:SS'),	\
			reason_code,							\
			credit_type,							\
			quantity,								\
			item_id									\
	from	ebay_auction_credits					\
	where	batch_id		= 0						\
	and		item_id			>= :min_item_id			\
	and		item_id			<= :max_item_id			\
			order by item_id";

static const char *SQL_SetItemStatus = 
 "update ebay_auction_credits						\
		set batch_id		= :new_batch_id			\
	where item_id >= :min_item_id					\
	and   item_id <= :max_item_id";


void clsDatabaseOracle::GetAllNewCredits(int batch_id, CreditsVector *pvCredits)
{
	int				min_item_id=0, max_item_id=0;
    int             bidder_id[ORA_CREDITS_ARRAYSIZE];
    float			amount[ORA_CREDITS_ARRAYSIZE];
    char            last_modified[ORA_CREDITS_ARRAYSIZE][32];
    time_t          the_timestamp;
    char            reason[ORA_CREDITS_ARRAYSIZE][10];
    char            credit_type[ORA_CREDITS_ARRAYSIZE][2];
    int             quantity[ORA_CREDITS_ARRAYSIZE];
    int             item_id[ORA_CREDITS_ARRAYSIZE];
    int             rowsFetched;
    int             rc;
    int             i,n;
	sItemCredits	*pCredits=NULL, *prevCredit=NULL;
	int				new_batch_id=batch_id;


	// Get Max and Min unprocessed item_id's first
	OpenAndParse(&mpCDAOneShot, SQL_GetMinMaxItemId);

	Define(1, (int *)&max_item_id);
	Define(2, (int *)&min_item_id);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		// No entries in the credit table for this item
		return;
	}

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	// Get all items that are to be processed
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetAllNewCredits);

	// Bind
	Bind(":min_item_id", &min_item_id);
	Bind(":max_item_id", &max_item_id);
    // Define
	Define(1,  &bidder_id[0]);
    Define(2,  &amount[0]);
    Define(3,  (char*)last_modified[0], sizeof(last_modified[0]));
	Define(4,  (char *)reason[0], sizeof(reason[0]));
	Define(5,  (char *)credit_type[0], sizeof(credit_type[0]));
	Define(6,  &quantity[0]);
	Define(7,  &item_id[0]);

    // Execute
    Execute();

    // Now we fetch until we're done
    rowsFetched = 0;
    do
    {
       rc = ofen((struct cda_def *)mpCDACurrent, ORA_CREDITS_ARRAYSIZE);

        if ((rc < 0 || rc >= 4)  &&
                ((struct cda_def *)mpCDACurrent)->rc != 1403)   // something wrong
        {
                Check(rc);
                Close(&mpCDAOneShot);
                SetStatement(NULL);
                return;
        }

        // rpc is cumulative, so find out how many rows to display this time
        // (always <= ORA_CREDITS_ARRAYSIZE).
        n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
        rowsFetched += n;

        for (i=0; i < n; i++)
        {
                // Convert time
                ORACLE_DATEToTime(last_modified[i],
                                  &the_timestamp);

				// There will be multiple entries for the same item_id, don't care for
				// the reason code or timestamp as they will be updated for each entry in
				// the deadbeats table
				if (prevCredit && prevCredit->item_id == item_id[i])
				{
					// dutch auction credit request when multiple bidders backed out
					// This is the amount received by seller from high bidders
					prevCredit->amt			= amount[i]   + prevCredit->amt;
					// This is quantity that was not picked, "p" indicates item was picked
//					if (strcmpi(credit_type[i], "n")==0)
					prevCredit->quantity	= quantity[i] + prevCredit->quantity;
					// In the event if the very last record is same as the previous one
					// then we need to flag that
					// Always default to partial for dutch auctions
					strcpy(pCredits->credit_type, "p");
				}
				else
				{
					// New item id, first save the previous record as we are done with it
					if (prevCredit)
						pvCredits->push_back(prevCredit);

					pCredits				= new sItemCredits;
					pCredits->item_id		= item_id[i];
					pCredits->bidder_id		= bidder_id[i];
					pCredits->last_modified	= the_timestamp;
					if (reason[i])
						pCredits->reason_code = (CreditTypeEnum)atoi(reason[i]);
					else
						pCredits->reason_code = (CreditTypeEnum)0;
					strcpy(pCredits->credit_type, credit_type[i]);
					pCredits->amt			= amount[i];
//					if (strcmpi(credit_type[i], "n")==0)
					pCredits->quantity		= quantity[i];
//					else
//						pCredits->quantity		= 0;
					pCredits->batch_id		= new_batch_id;
					// Current now becomes previous record
					prevCredit = pCredits;
				}
		}

    } while (!CheckForNoRowsFound());

	// Check if last record was pushed in the vector
	if (prevCredit)
		pvCredits->push_back(prevCredit);	

    Close(&mpCDAOneShot);
    SetStatement(NULL);

	// Now increment the batch_id of our range
	OpenAndParse(&mpCDAOneShot, SQL_SetItemStatus);
	// Bind
	Bind(":new_batch_id", &new_batch_id);
	Bind(":min_item_id", &min_item_id);
	Bind(":max_item_id", &max_item_id);

	// Do it
	Execute();

	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

    return;

}

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static char *SQL_GetManyItemsForListingFullTable =
"select id, "
"title, "
"ROWIDTOCHAR(rowid), "
"password, "
"category, "
"bidcount, "
"TO_CHAR(sale_start, 'YYYY-MM-DD HH24:MI:SS'), "
"TO_CHAR(sale_end, 'YYYY-MM-DD HH24:MI:SS'), "
"current_price, "
"start_price, "
"reserve_price, "
"featured, "
"super_featured, "
"bold_title, "
"picture_url, "
"icon_flags,  "
"gallery_type, "
"gallery_state, "
"country_id,   "
"currency_id,   "
"shipping_option, "
"ship_region_flags, "
"desc_lang, "
"site_id, "
"zip	"
"from ebay_items where sale_end >= sysdate";

#define ORACLE_ITEMS_ARRAY_SIZE 1000

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
void clsDatabaseOracle::GetAllActiveItemsAllTable(ListingItemVector * pvItems)
{

	int				rowsFetched;
	int				rc;
	int				i,n;
	time_t			theTime;


	// Begin storage for actual items.
	// Temporary slots for things to live in
	int					id[ORACLE_ITEMS_ARRAY_SIZE];
	char				title[ORACLE_ITEMS_ARRAY_SIZE][255];
	char				rowid[ORACLE_ITEMS_ARRAY_SIZE][20];
	int					password[ORACLE_ITEMS_ARRAY_SIZE];
	int					category[ORACLE_ITEMS_ARRAY_SIZE];
	int					bidcount[ORACLE_ITEMS_ARRAY_SIZE];
	char				sale_start[ORACLE_ITEMS_ARRAY_SIZE][32];
	time_t				sale_start_time;
	char				sale_end[ORACLE_ITEMS_ARRAY_SIZE][32];
	time_t				sale_end_time;
	float				current_price[ORACLE_ITEMS_ARRAY_SIZE];
	float				start_price[ORACLE_ITEMS_ARRAY_SIZE];
	float				reserve_price[ORACLE_ITEMS_ARRAY_SIZE];

	char				featured[ORACLE_ITEMS_ARRAY_SIZE][2];
	char				superFeatured[ORACLE_ITEMS_ARRAY_SIZE][2];
	char				boldTitle[ORACLE_ITEMS_ARRAY_SIZE][2];
	char				pictureURL[ORACLE_ITEMS_ARRAY_SIZE][256];
	sb2					pictureURL_ind[ORACLE_ITEMS_ARRAY_SIZE];
	bool				isReserved;
	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBold;
	bool				hasPic;
	int 				giftType;
	char				iconFlags[ORACLE_ITEMS_ARRAY_SIZE][3];
	sb2					iconFlags_ind[ORACLE_ITEMS_ARRAY_SIZE];

	bool				isGallery;
	bool				isFeaturedGallery;
	GalleryResultCode	theGalleryState;

	int					galleryType[ORACLE_ITEMS_ARRAY_SIZE];
	sb2					galleryType_ind[ORACLE_ITEMS_ARRAY_SIZE];

	GalleryResultCode	galleryState[ORACLE_ITEMS_ARRAY_SIZE];
	sb2					galleryState_ind[ORACLE_ITEMS_ARRAY_SIZE];

	int					countryId[ORACLE_ITEMS_ARRAY_SIZE];
	sb2					countryId_ind[ORACLE_ITEMS_ARRAY_SIZE];

	int					currencyId[ORACLE_ITEMS_ARRAY_SIZE];
	sb2					currencyId_ind[ORACLE_ITEMS_ARRAY_SIZE];

	char				zip[ORACLE_ITEMS_ARRAY_SIZE][255];

	// nsacco 07/27/99
	int					shipping_option[ORACLE_ITEMS_ARRAY_SIZE];
	long				ship_region_flags[ORACLE_ITEMS_ARRAY_SIZE];
	int					desc_lang[ORACLE_ITEMS_ARRAY_SIZE];
	int					site_id[ORACLE_ITEMS_ARRAY_SIZE];


	clsListingItem		*pItem;
	pvItems->reserve(1800000);
	OpenAndParse(&mpCDAOneShot, SQL_GetManyItemsForListingFullTable);

	// Define our outputs.
	Define(1, id);
	Define(2, title[0], sizeof(title[0]));
	Define(3, rowid[0], sizeof (rowid[0]));
	Define(4, password);
	Define(5, category);
	Define(6, bidcount);
	Define(7, sale_start[0], sizeof(sale_start[0]));
	Define(8, sale_end[0], sizeof(sale_end[0]));
	Define(9, current_price);
	Define(10, start_price);
	Define(11, reserve_price);
	Define(12, featured[0], sizeof(featured[0]));
	Define(13, superFeatured[0], sizeof(superFeatured[0]));
	Define(14, boldTitle[0], sizeof(boldTitle[0]));
	Define(15, pictureURL[0], sizeof(pictureURL[0]),
		&pictureURL_ind[0]);
	Define(16, iconFlags[0], sizeof(iconFlags[0]),
		&iconFlags_ind[0]);
	Define(17, galleryType, galleryType_ind);
	Define(18, (int *)galleryState, galleryState_ind);
	Define(19, countryId, countryId_ind);
	Define(20, currencyId, currencyId_ind);
	Define(21, zip[0], sizeof(zip[0]));
	// nsacco 07/27/99 new params
	Define(22, shipping_option);
	Define(23, ship_region_flags);
	Define(24, desc_lang);
	Define(25, site_id);

	// Let's do the SQL
	Execute();

	if (CheckForNoRowsFound ())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot,true);
		SetStatement(NULL);
		return; 
	}
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORACLE_ITEMS_ARRAY_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;
		for (i=0; i < n; i++)
		{
			// Now everything is where it's supposed
			// to be. 
			// Time Conversions
			ORACLE_DATEToTime(sale_start[i], &sale_start_time);
			ORACLE_DATEToTime(sale_end[i], &sale_end_time);
			// Transform flags.
			isReserved = (reserve_price[i] != 0);
			isFeatured	= (featured[i][0] == '1');
			isSuperFeatured	= (superFeatured[i][0] == '1');
			isBold	= (boldTitle[i][0] == '1');
			hasPic = (pictureURL_ind[i] != -1);
			if (iconFlags_ind[i] != -1)
			{
				if (iconFlags[i][0] == 'g')
				{
					giftType = 1;
				}
				else
				{
					giftType = atoi(iconFlags[i]);
				}
			}
			else
				giftType = 0;

			// Handle null gallery
			if (galleryType_ind[i] == -1)
				galleryType[i] = NoneGallery;
			if (countryId_ind[i] == -1)
				countryId[i] = Country_None;
			if (currencyId_ind[i] == -1)
				currencyId[i] = Currency_USD;

			// nsacco 07/27/99
			// handle null new params
			if (shipping_option[i] == -1)
			{
				if (password[i] & ShippingInternationally)
				{
					// handle old items
					shipping_option[i] = Worldwide;
					password[i] = password[i] & ~ShippingInternationally;
				}
				else
				{
					shipping_option[i] = SiteOnly;
				}
			}

			if (ship_region_flags[i] == -1)
			{
				ship_region_flags[i] = ShipRegion_None;
			}

			if (desc_lang[i] == -1)
			{
				desc_lang[i] = English;
			}

			if (site_id[i] == -1)
			{
				site_id[i] = SITE_EBAY_MAIN;
			}
			// end check for new params

			isGallery = (galleryType[i] == Gallery);
			isFeaturedGallery = (galleryType[i] == FeaturedGallery);
			if (galleryState_ind[i] == -1)
				galleryState[i] = kGalleryNotProcessed;
			theGalleryState = static_cast<GalleryResultCode>(galleryState[i]);

			if (sale_end_time < theTime)
				continue; // We skip this item if it's already ended by the time we see it.

			// Fill in the item
			pItem	= new clsListingItem;
			// nsacco 07/27/99 added new params
			pItem->Set(id[i],
				   title[i],
				   rowid[i],
				   category[i],
				   bidcount[i],
				   sale_start_time,
				   sale_end_time,
				   (bidcount[i] > 0) ? current_price[i] : start_price[i],
				   isReserved,
				   isFeatured,
				   isSuperFeatured,
				   isBold,
				   hasPic,
				   giftType,
				   isGallery,
				   isFeaturedGallery,
				   theGalleryState,
				   countryId[i],
				   currencyId[i],
				   shipping_option[i],
				   ship_region_flags[i],
				   desc_lang[i],
				   site_id[i],
				   password[i],
				   zip[i]);

			pvItems->push_back(pItem);
		}
	} while (!CheckForNoRowsFound());

	// Now -- close it.
	Close(&mpCDAOneShot);
	SetStatement(NULL);
}


/*** inna - needed by EOA State tfunctionality ****/

static const char *SQL_GetNextEOAStateTime =
"select to_char(max(end_time),'YYYY-MM-DD HH24:MI:SS') from ebay_EOA_State";	

static const char *SQL_GetNextEOAStateId =
 "select ebay_EAO_sequence.nextval from dual";

static const char *SQL_CreateEOAState =
"insert into ebay_eoa_state ( "
" SeqId, "
" Started, "
" Pid, "
" From_Time, "
" End_Time ) "
"values (:nextSeq, "
" to_date(:started,'YYYY-MM-DD HH24:MI:SS'), "
" :pid, "
" to_date(:from_time,'YYYY-MM-DD HH24:MI:SS')," 
" to_date(:end_time,'YYYY-MM-DD HH24:MI:SS'))";	

//pEOAState must have PID and Started populated
//this method is called by EoA if no parameters were passed to it
//which means 'process next 2 hours'
bool clsDatabaseOracle::CreateNextEOAStateInfo(clsEOAState *pEOAState)
{	
	int		nextSeq;
	char	cNextFrom_Time[32];
	time_t	next_from_time;
	time_t	next_end_time;
	time_t  curr_time;
	struct tm*	pDateAsTm;
	char	cStarted[32];
	char	cEnd_Time[32];
	char	*pid;

	//let's do safety checks
	if (pEOAState->GetpPid()==NULL)
		return false;
	if (pEOAState->GetStarted()==0)
		return false;

	//lets get from time for this next new instance
	OpenAndParse(&mpCDAOneShot, SQL_GetNextEOAStateTime);

	Define(1, (char *)cNextFrom_Time, sizeof(cNextFrom_Time));

	ExecuteAndFetch();

	//lets set out new start time
	ORACLE_DATEToTime(cNextFrom_Time, &next_from_time);
	pEOAState->SetFrom_Time(next_from_time);

	//create our end time: sysdate-5minutes or just start_time +2 hours - 5minutes 
	//(3600 *2 seconds - 300 seconds)
	curr_time = time(0);

	if (curr_time < next_from_time)
	//sysdate is less the end time of the last instance running
	//can not run yet
		return false;

	if ((curr_time - next_from_time) < 7501) //diff is 2 hours 5 min max
		next_end_time = curr_time - 300; //sysdate -5 minutes
	else
		next_end_time = next_from_time + 7200;

	pEOAState->SetEnd_Time(next_end_time);

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	//all we have left is sequence
	OpenAndParse(&mpCDAOneShot, SQL_GetNextEOAStateId);
	Define(1, &nextSeq);

	// Execute
	ExecuteAndFetch();
	pEOAState->SetSeqId(nextSeq);
	// Close and Clean
	Close(&mpCDAOneShot);
	SetStatement(NULL);


	//now it is time to make this record
	time_t started=	pEOAState->GetStarted();
	//make started time into oracle
	pDateAsTm	= localtime(&started);
	TM_STRUCTToORACLE_DATE(pDateAsTm,cStarted);

	//make end time into oracle
	pDateAsTm	= localtime(&next_end_time);
	TM_STRUCTToORACLE_DATE(pDateAsTm, cEnd_Time);

	//from_time already is in a format since it is coming from db

	pid = new char[10];
	strcpy(pid, pEOAState->GetpPid());
	OpenAndParse(&mpCDAOneShot, SQL_CreateEOAState);
	//binds
	Bind(":started", (char *)cStarted);
	Bind(":from_time", (char *)cNextFrom_Time);
	Bind(":end_time", (char *)cEnd_Time);
	Bind(":pid", (char *)pid);
	Bind(":nextSeq", &nextSeq);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	delete pid;
	return true;
}

static const char *SQL_GetEOAStateInfo =
"select pid from ebay_EOA_State "
"where from_time = to_date(:from_time,'YYYY-MM-DD HH24:MI:SS') "
"and end_time =to_date(:end_time,'YYYY-MM-DD HH24:MI:SS')";	


//	pEOAState must have Started, To and From dates populated
//	this method is called by EoA if parameters WERE passed to it
//	which means reprocess, so lets get original info
//	this routine just gets info and populated object with old pid
bool clsDatabaseOracle::GetEOAStateInfo(clsEOAState *pEOAState)
{	
	char	pid[10];
	char	cFrom_Time[32];
	char	cEnd_Time[32];
	time_t	from_time = pEOAState->GetFrom_Time();
	time_t	end_time = pEOAState->GetEnd_Time();
	time_t	started = pEOAState->GetStarted();
	struct tm	*pDateAsTm;	
	
	//let's do safety checks
	if (pEOAState->GetStarted()==0)
		return false;
	if (pEOAState->GetFrom_Time()==0)
		return false;
	if (pEOAState->GetEnd_Time()==0)
		return false;

	//convert dates to Oracle format
	pDateAsTm	= localtime(&from_time);
	TM_STRUCTToORACLE_DATE(pDateAsTm, cFrom_Time);

	pDateAsTm	= localtime(&end_time);
	TM_STRUCTToORACLE_DATE(pDateAsTm, cEnd_Time);

	//lets get from time for this next new instance
	OpenAndParse(&mpCDAOneShot, SQL_GetEOAStateInfo);

	Bind(":from_time", (char *)cFrom_Time, sizeof(cFrom_Time));
	Bind(":end_time", (char *)cEnd_Time, sizeof(cEnd_Time));

	Define(1,(char *)pid, sizeof(pid));

	ExecuteAndFetch();

	// if no item found, then return
	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return false;
	}

	pEOAState->SetpPid(pid);

	Close (&mpCDAOneShot);
	SetStatement(NULL);
	return true;

}


static const char *SQL_UpdateEOAStateInfo =
"update ebay_EOA_State "
" set pid = :pid, "
" started = to_date(:started,'YYYY-MM-DD HH24:MI:SS'), " 
" seqid = :nextSeq where from_time = to_date(:from_time,'YYYY-MM-DD HH24:MI:SS') "
" and end_time =to_date(:end_time,'YYYY-MM-DD HH24:MI:SS')";	

//this method gets new avalable sequnce id
//updates existing record with a new sequnce id, pid and start date
bool clsDatabaseOracle::UpdateEOAStateInfo(clsEOAState *pEOAState)
{
	int nextSeq;
	//lets get all times
	char	cFrom_Time[32];
	char	cEnd_Time[32];
	char    cStarted[32];
	time_t	from_time = pEOAState->GetFrom_Time();
	time_t	end_time = pEOAState->GetEnd_Time();
	time_t	started = pEOAState->GetStarted();
	struct tm	*pDateAsTm;	
	//and get pid
	char  *pid;
	
	pid = new char[10];
	strcpy(pid, pEOAState->GetpPid());

	//all we have left is sequence
	OpenAndParse(&mpCDAOneShot, SQL_GetNextEOAStateId);
	Define(1, &nextSeq);

	// Execute
	ExecuteAndFetch();
	pEOAState->SetSeqId(nextSeq);
	// Close and Clean
	Close(&mpCDAOneShot);
	SetStatement(NULL);


	//lets scrub out dates:
	pDateAsTm	= localtime(&from_time);
	TM_STRUCTToORACLE_DATE(pDateAsTm,cFrom_Time);

	pDateAsTm	= localtime(&end_time);
	TM_STRUCTToORACLE_DATE(pDateAsTm,cEnd_Time);

	
	pDateAsTm	= localtime(&started);
	TM_STRUCTToORACLE_DATE(pDateAsTm,cStarted);

	//open and parse
	OpenAndParse(&mpCDAOneShot, SQL_UpdateEOAStateInfo);

	//binds
	Bind(":started", (char *)cStarted);
	Bind(":from_time", (char *)cFrom_Time);
	Bind(":end_time", (char *)cEnd_Time);
	Bind(":pid", (char *)pid);
	Bind(":nextSeq", &nextSeq);

	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	delete pid;

	return true;
}

static const char *SQL_UpdateEOAPid =
"update ebay_EOA_State "
" set pid = :pid "
"where from_time = to_date(:from_time,'YYYY-MM-DD HH24:MI:SS') "
"and end_time =to_date(:end_time,'YYYY-MM-DD HH24:MI:SS')";	

//this method gets new avalable sequnce id
//updates existing record with a new sequnce id, pid and start date
void  clsDatabaseOracle::MakeInstanceComplete(clsEOAState *pEOAState)
{

	char	cFrom_Time[32];
	char	cEnd_Time[32];
	time_t	from_time = pEOAState->GetFrom_Time();
	time_t	end_time = pEOAState->GetEnd_Time();
	struct tm	*pDateAsTm;	
	//and get pid
	char *pid = new char[10];
	strcpy (pid, pEOAState->GetpPid());

	//lets scrub out dates:
	pDateAsTm	= localtime(&from_time);
	TM_STRUCTToORACLE_DATE(pDateAsTm, cFrom_Time);

	pDateAsTm	= localtime(&end_time);
	TM_STRUCTToORACLE_DATE(pDateAsTm, cEnd_Time);

	//open and parse
	OpenAndParse(&mpCDAOneShot, SQL_UpdateEOAPid);

	//binds
	Bind(":from_time", (char *)cFrom_Time);
	Bind(":end_time", (char *)cEnd_Time);
	Bind(":pid", (char *)pid);

	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	delete pid;
	return;
}

/*static const char *SQL_GetNoticeTime =
 "select	TO_CHAR(notice_time,					\
						'YYYY-MM-DD HH24:MI:SS')	\
	from ebay_item_info								\
	where	marketplace = :marketplace			\
	and		id = :itemid";*/

static const char *SQL_GetNoticeTime =
 "select	TO_CHAR(notice_time,					\
						'YYYY-MM-DD HH24:MI:SS')	\
	from ebay_items								\
	where	marketplace = :marketplace			\
	and		id = :itemid";

static const char *SQL_GetNoticeTimeByRowId =
 "select	TO_CHAR(notice_time,					\
						'YYYY-MM-DD HH24:MI:SS')	\
	from ebay_items								\
	where rowid = CHARTOROWID(:thisrow)";

static const char *SQL_GetNoticeTimeEnded =
 "select	TO_CHAR(notice_time,					\
						'YYYY-MM-DD HH24:MI:SS')	\
	from ebay_items_ended								\
	where	marketplace = :marketplace			\
	and		id = :itemid";
long clsDatabaseOracle::GetNoticeTime(clsItem *pItem)
{
	// Temporary slots for things to live in
	char				notice_time[32];
	time_t				notice_time_time;
	sb2					notice_time_ind;
	MarketPlaceId		marketplace;
	int					id;

	//deal with row id 
	bool	userowid;

	userowid = IsValidRowIdFormat(pItem->GetRowId());

	marketplace = pItem->GetMarketPlaceId();
	id = pItem->GetId();

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	if (pItem->GetEnded())
	{
		OpenAndParse(&mpCDAGetNoticeTimeEnded, SQL_GetNoticeTimeEnded);
		// Bind the input variable
		Bind(":itemid", &id);
		Bind(":marketplace", &marketplace);
	}
	else
	{
		if (userowid)
		{
			OpenAndParse(&mpCDAGetNoticeTimeByRowId, SQL_GetNoticeTimeByRowId);
			Bind(":thisrow", pItem->GetRowId());
		}
		else
		{
				
			OpenAndParse(&mpCDAGetNoticeTime, SQL_GetNoticeTime);
			// Bind the input variable
			Bind(":itemid", &id);
			Bind(":marketplace", &marketplace);
		}

	}


	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, (char *)notice_time, sizeof(notice_time), &notice_time_ind);

	// Fetch
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		if (pItem->GetEnded())
			Close(&mpCDAGetNoticeTimeEnded);
		else
		{
			if (userowid)
				Close(&mpCDAGetNoticeTimeByRowId);
			else
				Close(&mpCDAGetNoticeTime);
		}

		SetStatement(NULL);
		return 0;
	}

	// Time Conversions
	if (notice_time_ind == -1)
		notice_time_time = 0;
	else
		ORACLE_DATEToTime(notice_time, &notice_time_time);

	if (pItem->GetEnded())
		Close(&mpCDAGetNoticeTimeEnded);
	else
	{
		if (userowid)
			Close(&mpCDAGetNoticeTimeByRowId);
		else
			Close(&mpCDAGetNoticeTime);
	}

	SetStatement(NULL);

	return notice_time_time;
}

/*static char *SQL_GetItemsToEnd =
"select id from ebay_items where marketplace = 0 and sale_end < sysdate and "
"sale_end > TO_DATE(:startdate,	'YYYY-MM-DD HH24:MI:SS')"
" and sale_end <= TO_DATE(:enddate, 'YYYY-MM-DD HH24:MI:SS')";

void clsDatabaseOracle::GetItemIdsToEnd(vector<int> *pvItems,
										time_t startDate, time_t endDate)
{
	int			id[ORA_NOTBILLED_ARRAYSIZE];
	int			rowsFetched;
	int			rc;
	int			i,n;
	char		cFromDate[32];
	char		cToDate[32];
	struct tm*  pTheTime	= localtime(&startDate);
	TM_STRUCTToORACLE_DATE(pTheTime, cFromDate);
	pTheTime	= localtime(&endDate);
	TM_STRUCTToORACLE_DATE(pTheTime, cToDate);
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetItemsToEnd);

	// Bind
	Bind(":marketplace", (int *)&marketplace);

	// Define
	Define(1, (int *)id);

	// Execute
	Execute();

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORA_NOTBILLED_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pvItems->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}
*/

//inna -start we nned this method because we need items from both tables
//in all sorted by category number
static const char *SQL_GetItemsByEndDateSortedByCat = 
	"select	id, category	\
		from	ebay_items									\
		where		marketplace = :marketplace				\
		and		sale_end	>= TO_DATE(:fromdate,'YYYY-MM-DD HH24:MI:SS')	\
		and		sale_end  < TO_DATE(:todate,'YYYY-MM-DD HH24:MI:SS') \
        UNION			\
        select	id, category	\
		from	ebay_items_ended \
		where	marketplace = :marketplace	\
		and sale_end	>= TO_DATE(:fromdate,'YYYY-MM-DD HH24:MI:SS')	\
		and		sale_end  < TO_DATE(:todate,'YYYY-MM-DD HH24:MI:SS')	\
		order by category";

void clsDatabaseOracle::GetItemsByEndDateSortedByCat(MarketPlaceId marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate)
{
	int			id[ORA_ITEMSELECT_ARRAYSIZE];
	int			rowsFetched;
	int			rc;
	int			i,n;
	int			cat[ORA_ITEMSELECT_ARRAYSIZE];
	char		cFromDate[64];
	char		cToDate[64];

	OpenAndParse(&mpCDAOneShot,
				 SQL_GetItemsByEndDateSortedByCat);

	strcpy(cFromDate, fromdate);
	strcpy(cToDate, todate);

	// Bind
	Bind(":marketplace", &marketplace);
	Bind(":fromdate", cFromDate);
	Bind(":todate", cToDate);

	// Define
	Define(1, id);
	Define(2, cat);

	// Execute
	Execute();

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_ITEMSELECT_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_ITEMSELECT_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pvItems->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}
//end - inna

//
// AddBlockedItem
//
// TODO - not a complete list - missing zip, dutch_gms, others
// nsacco 07/27/99 added site_id, shipping_option, ship_region_flags, desc_lang
static char *SQL_AddBlockedItem = 
"insert into ebay_items_blocked "
"	( "
"		marketplace, "
"		id, "
"		sale_type, "
"		title, "
"		location, "
"		seller, "
"		owner, "
"		password, "
"		category, "
"		quantity, "
"		bidcount, "
"		created, "
"		sale_start, "
"		sale_end, "
"		sale_status, "
"		current_price, "
"		start_price, "
"		reserve_price, "
"		high_bidder, "
"		featured, "
"		super_featured, "
"		bold_title, "
"		private_sale, "
"		registered_only, "
"		host, "
"		visitcount, "
"		picture_url, "
"		last_modified, "
"		icon_flags, "
"		gallery_url, "
"		gallery_type, "
"		country_id, "
"		shipping_option, "
"		ship_region_flags, "
"		desc_lang, "
"		site_id "
"	) "
"	values ( "
"		:marketplace, "
"		:itemid, "
"		:saletype, "
"		:itemtitle, "
"		:loc, "
"		:seller, "
"		:owner, "
"		:password, "
"		:category, "
"		:quantity, "
"		:bidcount, "
"		TO_DATE(:created, "
"				'YYYY-MM-DD HH24:MI:SS'), "
"		TO_DATE(:sales, "
"				'YYYY-MM-DD HH24:MI:SS'), "
"		TO_DATE(:salee,	"
"				'YYYY-MM-DD HH24:MI:SS'), "
"		:stat, "
"		:cprice, "
"		:sprice, "
"		:rprice, "
"		:hibidder, "
"		:featured, "
"		:superfeatured, "
"		:bold, "
"		:private, "
"		:regonly, "
"		:host, "
"		:visitcount, "
"		:picurl, "
"		sysdate, "
"		:icon_flags, "
"		:galleryurl, "
"		:galleryType, "
"		:country_id, "
"		:shipping_option, "
"		:ship_region_flags, "
"		:desc_lang, "
"		:site_id "
"	)";

// nsacco 07/27/99 added site_id, shipping_option, ship_region_flags, desc_lang
void clsDatabaseOracle::AddBlockedItem(clsItem *pItem)
{
	int					marketplaceid;
	int					id;
	int					saleType;
	int					seller;
//	int					owner;
	int					password;
	int					category;
	int					quantity;
	int					bidcount;
	char				created[32] = {0};
	char				sale_start[32] = {0};
	char				sale_end[32] = {0};
	int					sale_status;
	float				current_price;
	float				start_price;
	float				reserve_price;
	int					high_bidder;
	sb2					high_bidder_null = -1;

	char				featured[2];
	char				superFeatured[2];
	char				bold[2];
	char				privateSale[2];
	char				registered[2];

	char *				pHost;
	sb2					host_null;
	char				nullHost	= '\0';
	int					visitcount;

	char *				pPictureURL;
	sb2					pictureURL_null;
	char				nullPictureURL	= '\0';

	struct tm *			pTheTime;
	time_t				tTime;

	char *				pIconFlags;
	sb2					iconFlags_null;
	char				nullIconFlags	= '\0';

	char *				pGalleryURL;
	sb2					galleryURL_null;
	char				nullGalleryURL	= '\0';

	int					galleryType;
	int					countryId;

	// nsacco 07/27/99 new params
	int					shipping_option;
	long				ship_region_flags;
	int					desc_lang;
	int					site_id;

	// Extract things from the item into our
	// local variables to prevent any casting
	// confusion
	marketplaceid	= pItem->GetMarketPlaceId();
	id				= pItem->GetId();
	saleType		= (int)pItem->GetAuctionType();
	seller			= pItem->GetSeller();
//	owner			= pItem->GetOwner();
	password		= pItem->GetPassword();
	category		= pItem->GetCategory();
	quantity		= pItem->GetQuantity();
	bidcount		= pItem->GetBidCount();
	sale_status		= pItem->GetStatus();
	current_price	= pItem->GetPrice();
	start_price		= pItem->GetStartPrice();
	reserve_price	= pItem->GetReservePrice();
	high_bidder		= pItem->GetHighBidder();
	visitcount		= pItem->GetVisitCount();
	galleryType		= pItem->GetGalleryType();
	countryId		= pItem->GetCountryId();
	// nsacco 07/27/99 new params
	shipping_option = pItem->GetShippingOption();
	ship_region_flags = pItem->GetShipRegionFlags();
	desc_lang = pItem->GetDescLang();
	site_id = pItem->GetSiteId();

	// Transform Bools to chars
	if (pItem->GetFeatured())
		strcpy(featured, "1");
	else
		strcpy(featured, "0");

	if (pItem->GetSuperFeatured())
		strcpy(superFeatured, "1");
	else
		strcpy(superFeatured, "0");

	if (pItem->GetBoldTitle())
		strcpy(bold, "1");
	else
		strcpy(bold, "0");

	if (pItem->GetPrivate())
		strcpy(privateSale, "1");
	else
		strcpy(privateSale, "0");

	if (pItem->GetRegisteredOnly())
		strcpy(registered, "1");
	else
		strcpy(registered, "0");

	pHost	= pItem->GetHost();

	if (pHost == NULL)
	{
		host_null	= -1;
	}
	else
	{
		host_null	= 0;
	}
	
	pPictureURL	= pItem->GetPictureURL();

	if (pPictureURL == NULL)
	{
		pictureURL_null	= -1;
	}
	else
	{
		pictureURL_null	= 0;
	}
	
	pIconFlags	= pItem->GetIconFlags();

	if (pIconFlags == NULL)
	{
		iconFlags_null	= -1;
	}
	else
	{
		iconFlags_null	= 0;
	}

	pGalleryURL	= pItem->GetGalleryURL();

	if (pGalleryURL == NULL)
	{
		galleryURL_null	= -1;
	}
	else
	{
		galleryURL_null	= 0;
	}
	
	// Date conversion
	tTime			= pItem->GetEndTime();
	pTheTime	= localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime,   sale_end);
	tTime			= pItem->GetStartTime();
	pTheTime	= localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime,   sale_start);
	tTime			= pItem->GetStartTime();	// ?
	pTheTime	= localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime,   created);

	// Get the next item id
	if (id == 0)
		id	= GetNextItemId();

	// We don't use this statement very often,
	// so the cursor's not persistant. Let's 
	// prepare the statement
	OpenAndParse(&mpCDAAddBlockedItem, SQL_AddBlockedItem);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplaceid);
	Bind(":itemid", &id);
	Bind(":saletype", &saleType);
	Bind(":itemtitle", (char *)pItem->GetTitle());
	Bind(":loc", (char *)pItem->GetLocation());
	Bind(":seller", &seller);
	Bind(":owner", &seller);
	Bind(":password", &password);
	Bind(":category", &category);
	Bind(":quantity", &quantity);
	Bind(":bidcount", &bidcount);
	Bind(":created", (char *)created);
	Bind(":sales", (char *)sale_start);
	Bind(":salee", (char *)sale_end);
	Bind(":stat", &sale_status);
	Bind(":cprice", &current_price);
	Bind(":sprice", &start_price);
	Bind(":rprice", &reserve_price);
	if (high_bidder != 0)
		Bind(":hibidder", &high_bidder);
	else
		Bind(":hibidder", &high_bidder, &high_bidder_null);

	Bind(":featured", (char *)featured);
	Bind(":superfeatured", (char *)superFeatured);
	Bind(":bold", (char *)bold);
	Bind(":private", (char *)privateSale);
	Bind(":regonly", (char *)registered);

	if (pHost)
		Bind(":host", pHost);
	else
		Bind(":host", (char *)&nullHost, &host_null);

	Bind(":visitcount", &visitcount);

	if (pPictureURL)
		Bind(":picurl", pPictureURL);
	else
		Bind(":picurl", (char *)&nullPictureURL, &pictureURL_null);

	if (pIconFlags)
		Bind(":icon_flags", pIconFlags);
	else
		Bind(":icon_flags", (char *)&nullIconFlags, &iconFlags_null);

	if (pGalleryURL)
		Bind(":galleryurl", pGalleryURL);
	else
		Bind(":galleryurl", (char *)&nullGalleryURL, &galleryURL_null);

	Bind(":galleryType", &galleryType);
	Bind(":country_id", &countryId);
	
	// nsacco 07/27/99 bind new params
	Bind(":shipping_option", &shipping_option);
	Bind(":ship_region_flags", &ship_region_flags);
	Bind(":desc_lang", &desc_lang);
	Bind(":site_id", &site_id);

	// Let's do it!
	Execute();

	// Commit
	Commit();

	// Free things
	Close(&mpCDAAddBlockedItem);
	SetStatement(NULL);
}


//
// DeleteBlockedItem
//

static char *SQL_DeleteBlockedItem = 
"delete from ebay_items_blocked "
"	where	marketplace = :marketplace "
"	and		id = :id";

static char *SQL_DeleteBlockedItemDesc = 
"delete from ebay_item_desc_blocked "
"	where	marketplace = :marketplace "
"	and		id = :id";

void clsDatabaseOracle::DeleteBlockedItem(MarketPlaceId marketplace,
										  ItemId id)
{
	// Delete item description first
	OpenAndParse(&mpCDADeleteBlockedItemDesc, SQL_DeleteBlockedItemDesc);

	// Ok, let's do some binds
	Bind(":marketplace", (int *)&marketplace);
	Bind(":id", &id);

	// Just do it!
	Execute();

	OpenAndParse(&mpCDADeleteBlockedItem, SQL_DeleteBlockedItem);

	Bind(":marketplace", &marketplace);
	Bind(":id", &id);

	Execute();
	Commit();

	Close(&mpCDADeleteBlockedItemDesc);
	Close(&mpCDADeleteBlockedItem);
	SetStatement(NULL);

	return;
}

void clsDatabaseOracle::DeleteBlockedItem(ItemId id)
{
	MarketPlaceId	marketplace = 0;
	clsMarketPlace *pMarketPlace;

	pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
	if (pMarketPlace != NULL)
		marketplace = pMarketPlace->GetId();
	
	DeleteBlockedItem(marketplace, id);

	return;
}


//
// UpdateBlockedItem
//
// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static char *SQL_UpdateBlockedItem = 
"update ebay_items_blocked "
"	set sale_type = :saletype, "
"		title = :itemtitle, "
"		location = :loc, "
"		seller = :seller, "
"		owner = :owner, "
"		password = :pass, "
"		category = :category, "
"		quantity = :quantity, "
"		bidcount = :bidcount, "
"		sale_start = TO_DATE(:sales, "
"				'YYYY-MM-DD HH24:MI:SS'), "
"		sale_end = 	TO_DATE(:salee, "
"				'YYYY-MM-DD HH24:MI:SS'), "
"		sale_status = :stat, "
"		current_price = :cprice, "
"		start_price = :sprice, "
"		reserve_price = :rprice, "
"		high_bidder = :hibidder, "
"		featured = :featured, "
"		super_featured = :superfeatured, "
"		bold_title = :bold, "
"		private_sale = :private, "
"		registered_only = :regonly, "
"		host = :host, "
"		picture_url = :picurl, "
"		last_modified = sysdate, "
"		icon_flags = :icon_flags, "
"		gallery_url = :gallery_url, "
"		gallery_type = :galleryType, "
"		country_id = :country_id, "
"		shipping_option = :shipping_option, "
"		ship_region_flags = :ship_region_flags, "
"		desc_lang = :desc_lang, "
"		site_id = :site_id "
"	where	marketplace = :marketplace "
"	and		id = :id";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
// TODO - missing options such as zip, dutch_gms, etc.
void clsDatabaseOracle::UpdateBlockedItem(clsItem *pItem)
{
	int					marketplaceid;
	int					id;
	int					saleType;
	int					seller;
//	int					owner;
	int					password;
	int					category;
	int					quantity;
	int					bidcount;
	char				sale_start[32] = {0};
	char				sale_end[32] = {0};
	int					sale_status;
	float				current_price;
	float				start_price;
	float				reserve_price;
	int					high_bidder;
	sb2					high_bidder_null = -1;

	char				featured[2];
	char				superFeatured[2];
	char				bold[2];
	char				privateSale[2];
	char				registered[2];

	char				*pHost;
//	sb2					host_null;
	char				nullHost	= '\0';
	
	char				*pPictureURL;
//	sb2					pictureURL_null;
	char				nullPictureURL	= '\0';

	char				*pIconFlags;
	sb2					iconFlags_null;
	char				nullIconFlags	= '\0';

	char				*pGalleryURL;
	sb2					galleryURL_null;
	char				nullGalleryURL	= '\0';

	int					galleryType;
	sb2					galleryType_null = -1;

	struct tm			*pTheTime;
	time_t				tTime;
	int					countryId;
	sb2					countryId_null = -1;

	// nsacco 07/27/99
	int					shipping_option;
	long				ship_region_flags;
	int					desc_lang;
	int					site_id;

	// Extract things from the item into our
	// local variables to prevent any casting
	// confusion
	marketplaceid	= pItem->GetMarketPlaceId();
	id				= pItem->GetId();
	saleType		= (int)pItem->GetAuctionType();
	seller			= pItem->GetSeller();
//	owner			= pItem->GetOwner();
	password		= pItem->GetPassword();
	category		= pItem->GetCategory();
	quantity		= pItem->GetQuantity();
	bidcount		= pItem->GetBidCount();
	sale_status		= pItem->GetStatus();
	current_price	= pItem->GetPrice();
	start_price		= pItem->GetStartPrice();
	reserve_price	= pItem->GetReservePrice();
	high_bidder		= pItem->GetHighBidder();
	galleryType		= (int)pItem->GetGalleryType();
	countryId		= pItem->GetCountryId();
	// nsacco 07/27/99
	shipping_option = pItem->GetShippingOption();
	ship_region_flags = pItem->GetShipRegionFlags();
	desc_lang = pItem->GetDescLang();
	site_id = pItem->GetSiteId();

	// Transform Bools to chars
	if (pItem->GetFeatured())
		strcpy(featured, "1");
	else
		strcpy(featured, "0");

	if (pItem->GetSuperFeatured())
		strcpy(superFeatured, "1");
	else
		strcpy(superFeatured, "0");

	if (pItem->GetBoldTitle())
		strcpy(bold, "1");
	else
		strcpy(bold, "0");

	if (pItem->GetPrivate())
		strcpy(privateSale, "1");
	else
		strcpy(privateSale, "0");

	if (pItem->GetRegisteredOnly())
		strcpy(registered, "0");
	else
		strcpy(registered, "1");

	pHost	= pItem->GetHost();
	if (pHost == NULL)
	{
		pHost	= (char  *)&nullHost;
//		host_null	= -1;
	}
//	else
//		host_null	= 0;

	pPictureURL	= pItem->GetPictureURL();
	if (pPictureURL == NULL)
	{
		pPictureURL	= (char  *)&nullPictureURL;
//		pictureURL_null	= -1;
	}
//	else
//		pictureURL_null	= 0;

	pGalleryURL	= pItem->GetGalleryURL();
	if (pGalleryURL == NULL)
	{
		pGalleryURL	= (char  *)&nullGalleryURL;
		galleryURL_null	= -1;
	}
	else
		galleryURL_null	= 0;

	pIconFlags	= pItem->GetIconFlags();
	if (pIconFlags == NULL)
	{
		pIconFlags	= (char  *)&nullIconFlags;
		iconFlags_null	= -1;
	}
	else
		iconFlags_null	= 0;

	// Date conversion
	tTime			= pItem->GetEndTime();
	pTheTime	= localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime,   sale_end);
	tTime			= pItem->GetStartTime();
	pTheTime	= localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime,   sale_start);

	OpenAndParse(&mpCDAOneShot, SQL_UpdateBlockedItem);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplaceid);
	Bind(":id", &id);
	Bind(":saletype", &saleType);
	Bind(":itemtitle", pItem->GetTitle());
	Bind(":loc", pItem->GetLocation());
	Bind(":seller", &seller);
	Bind(":owner", &seller);
	Bind(":pass", &password);
	Bind(":category", &category);
	Bind(":quantity", &quantity);
	Bind(":bidcount", &bidcount);
	Bind(":sales", sale_start);
	Bind(":salee", sale_end);
	Bind(":stat", &sale_status);
	Bind(":cprice", &current_price);
	Bind(":sprice", &start_price);
	Bind(":rprice", &reserve_price);
	if (high_bidder != 0)
		Bind(":hibidder", &high_bidder);
	else
		Bind(":hibidder", &high_bidder, &high_bidder_null);

	Bind(":featured", featured);
	Bind(":superfeatured", superFeatured);
	Bind(":bold", bold);
	Bind(":private", privateSale);
	Bind(":regonly", registered);
	Bind(":host", pHost);
	Bind(":picurl", pPictureURL);
	Bind(":icon_flags", pIconFlags);

	if (pGalleryURL)
		Bind(":gallery_url", pGalleryURL);
	else
		Bind(":gallery_url", pGalleryURL, galleryURL_null);

	// handle gallery old item (null colunm)
	if (galleryType != 0)
		Bind(":galleryType", &galleryType);
	else
		Bind(":galleryType", &galleryType, &galleryType_null);
	Bind(":country_id", &countryId);
	// nsacco 07/27/99
	Bind(":shipping_option", &shipping_option);
	Bind(":ship_region_flags", &ship_region_flags);
	Bind(":desc_lang", &desc_lang);
	Bind(":site_id", &site_id);

	// Let's do it!
	Execute();

	// Commit
	Commit();

	// Free things
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


//
// GetBlockedItem
//
// TODO - missing options such as zip, dutch_gms, etc.
// nsacco 07/27/99 added site_id, shipping_option, ship_region_flags, desc_lang
static char *SQL_GetBlockedItem = 
"select id, "
"		sale_type, "
"		title, "
"		location, "
"		seller, "
"		owner, "
"		password, "
"		category, "
"		quantity, "
"		bidcount, "
"		created, "
"		sale_start, "
"		sale_end, "
"		sale_status, "
"		current_price, "
"		start_price, "
"		reserve_price, "
"		high_bidder, "
"		featured, "
"		super_featured, "
"		bold_title, "
"		private_sale, "
"		registered_only, "
"		host, "
"		visitcount, "
"		picture_url, "
"		last_modified, "
"		icon_flags, "
"		gallery_url, "
"		gallery_type, "
"		country_id, "
"		shipping_option, "
"		ship_region_flags, "
"		desc_lang, "
"		site_id  "
"	from ebay_items_blocked "
"	where	marketplace = :marketplace "
"	and		id = :id";

// TODO - missing options like currency
// nsacco 07/27/99 added site_id, shipping_option, ship_region_flags, desc_lang
bool clsDatabaseOracle::GetBlockedItem(MarketPlaceId marketplace,
									   ItemId id,
									   clsItem *pItem)
{
	// Temporary slots for things to live in
	ItemId				itemId;
	AuctionTypeEnum		saleType;	
	char				title[255];
	char				location[255];
	int					seller;
	int					owner;
	int					password;
	int					category;
	int					quantity;
	int					bidcount;
	char				sale_start[32];
	time_t				sale_start_time;
	char				sale_end[32];
	time_t				sale_end_time;
	int					sale_status;
	float				current_price;
	float				start_price;
	float				reserve_price;
	int					high_bidder;
	sb2					high_bidder_ind;

	char				featured[2];
	char				superFeatured[2];
	char				boldTitle[2];
	char				privateSale[2];
	char				registeredOnly[2];
	char				host[65];
	sb2					host_ind;
	char				*pHost;
	int					visitcount;

	char				pictureURL[256];
	sb2					pictureURL_ind;
	char				*pPictureURL;

	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBold;
	bool				isPrivate;
	bool				isRegisteredOnly;

	char				*pLocation;
	char				*pTitle;

	time_t				last_modified_time;
	char				last_modified[32];

	char				itemrowid[20];
	char				*pItemRowId;
//	clsCategories*		pCategories;

	char				iconFlags[3];
	sb2					iconFlags_ind;
	char				*pIconFlags;

	char				galleryURL[256];
	sb2					galleryURL_ind;
	char				*pGalleryURL;

	int					galleryType;
	sb2					galleryType_ind;
	int					countryId;
	sb2					countryId_ind;

	int					galleryState;
	sb2					galleryState_ind;

	// nsacco 07/27/99 new params
	int					shipping_option;
	long				ship_region_flags;
	int					desc_lang;
	int					site_id;

	// let's get a clsCategories
//	pCategories = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCategories();

	OpenAndParse(&mpCDAGetBlockedItem, SQL_GetBlockedItem);

	// Bind the input variables
	Bind(":itemid", &id);
	Bind(":marketplace", &marketplace);


	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, (int *)&itemId);
	Define(2, (int *)&saleType);
	Define(3, title, sizeof(title));
	Define(4, location, sizeof(location));
	Define(5, &seller);
	Define(6, &owner);
	Define(7, &password);
	Define(8, &category);
	Define(9, &quantity);
	Define(10, &bidcount);
	Define(11, sale_start, sizeof(sale_start));
	Define(12, sale_end, sizeof(sale_end));
	Define(13, &sale_status);
	Define(14, &current_price);
	Define(15, &start_price);
	Define(16, &reserve_price);
	Define(17, &high_bidder, &high_bidder_ind);
	Define(18, featured, sizeof(featured));
	Define(19, superFeatured, sizeof(superFeatured));
	Define(20, boldTitle, sizeof(boldTitle));
	Define(21, privateSale, sizeof(privateSale));
	Define(22, registeredOnly, sizeof(registeredOnly));
	Define(23, host, sizeof(host), &host_ind);
	Define(24, &visitcount);
	Define(25, pictureURL, sizeof(pictureURL),
			&pictureURL_ind);
	Define(26, last_modified, sizeof(last_modified));
	Define(27, itemrowid, sizeof(itemrowid));
	Define(28, iconFlags, sizeof(iconFlags), &iconFlags_ind);
	Define(29, (char *)&galleryURL, sizeof(galleryURL),
			&galleryURL_ind);
	Define(30, &galleryType, &galleryType_ind);
	Define(31, &galleryState, &galleryState_ind);
	Define(32, &countryId, &countryId_ind);
	// nsacco 07/27/99 new params
	Define(33, &shipping_option);
	Define(34, &ship_region_flags);
	Define(35, &desc_lang);
	Define(36, &site_id);


	// Fetch
	ExecuteAndFetch();

	// if no item found, then return
	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetBlockedItem);
		SetStatement(NULL);
		return false;
	}

	// Now everything is where it's supposed
	// to be. Let's make copies of the title
	// and location for the item
	pTitle		= new char[strlen(title) + 1];
	strcpy(pTitle, (char *)title);
	pLocation	= new char[strlen(location) + 1];
	strcpy(pLocation, (char *)location);

	// Time Conversions
	ORACLE_DATEToTime(sale_start, &sale_start_time);
	ORACLE_DATEToTime(sale_end, &sale_end_time);
	ORACLE_DATEToTime(last_modified, &last_modified_time);
	// Handle null high bidder
	if (high_bidder_ind == -1)
		high_bidder = 0;

	// Transform flags.
	if (featured[0] == '1')
		isFeatured	= true;
	else
		isFeatured	= false;

	if (superFeatured[0] == '1')
		isSuperFeatured	= true;
	else
		isSuperFeatured	= false;
	
	if (boldTitle[0] == '1')
		isBold	= true;
	else
		isBold	= false;

	if (privateSale[0] == '1')
		isPrivate	= true;
	else
		isPrivate	= false;

	if (registeredOnly[0] == '1')
		isRegisteredOnly	= true;
	else
		isRegisteredOnly	= false;

	if (host_ind == -1)
	{
		pHost	= NULL;
	}
	else
	{
		pHost	= new char[strlen(host) + 1];
		strcpy(pHost, host);
	}
	
	if (pictureURL_ind == -1)
	{
		pPictureURL	= NULL;
	}
	else
	{
		pPictureURL	= new char[strlen(pictureURL) + 1];
		strcpy(pPictureURL, pictureURL);
	}

	pItemRowId	= new char[strlen(itemrowid) + 1];
	strcpy(pItemRowId, itemrowid);

	if (iconFlags_ind == -1)
	{
		pIconFlags	= NULL;
	}
	else
	{
		pIconFlags	= new char[strlen(iconFlags) + 1];
		strcpy(pIconFlags, iconFlags);
	}

	if (galleryURL_ind == -1)
	{
		pGalleryURL	= NULL;
	}
	else
	{
		pGalleryURL	= new char[strlen(galleryURL) + 1];
		strcpy(pGalleryURL, galleryURL);
	}
	// Handle null 
	if (galleryType_ind == -1)
		galleryType = NoneGallery;

	// Handle null 
	if (galleryState_ind == -1)
		galleryState = kGalleryNotProcessed;

	if (countryId_ind == -1)
		countryId = Country_None;

	// nsacco 07/27/99
	if (shipping_option == -1)
	{
		if (password & ShippingInternationally)
		{
			// handle old items
			shipping_option = Worldwide;
			password = password & ~ShippingInternationally;
		}
		else
		{
			shipping_option = SiteOnly;
		}
	}

	if (ship_region_flags == -1)
	{
		ship_region_flags = ShipRegion_None;
	}

	if (desc_lang == -1)
	{
		desc_lang = English;
	}

	if (site_id == -1)
	{
		site_id = SITE_EBAY_MAIN;
	}

	// Fill in the item
	// nsacco 07/27/99 added new params
	pItem->Set(marketplace,
			   id,
			   saleType,
			   pTitle,
			   NULL,
			   pLocation,
			   seller,
			   owner,
			   category,
			   bidcount,
			   quantity,
			   sale_start_time,
			   sale_end_time,
			   sale_status,
			   current_price,
			   start_price,
			   reserve_price,
			   high_bidder,
			   isFeatured,
			   isSuperFeatured,
			   isBold,
			   isPrivate, 
			   isRegisteredOnly,
			   pHost,
			   visitcount,
			   pPictureURL,
			   NULL,			// category name
			   NULL,			// seller user id
			   0,				// seller user state
			   0,				// seller user flags
			   NULL,			// high bidder user id
			   0,				// high bidder user state
			   0,				// high bidder user flags
			   0,				// seller feedback score
			   0,				// high bidder feedback score
			   0,				// seller id last change
			   0,				// high bidder id last change
			   last_modified_time,
			   NULL,
			   NULL,
			   password,
			   pItemRowId,
			   0,
			   pIconFlags,
			   pGalleryURL,
			   (GalleryTypeEnum) galleryType,
			   (GalleryResultCode) galleryState,
			   countryId,
			   Currency_USD,			// TODO - get the real currency!!!
			   false,					// If the Item is from the ended table.. useful later
			   NULL,
			   Currency_USD,			// int billingCurrency
			   shipping_option,			// int shipping_option
			   ship_region_flags,		// long ship_region_flags
			   desc_lang,				// int desc_lang
			   site_id					// int site_id
			);

	Close(&mpCDAGetBlockedItem);
	SetStatement(NULL);

	return true;
}

bool clsDatabaseOracle::GetBlockedItem(ItemId id, clsItem *pItem)
{
	MarketPlaceId	marketplace = 0;
	clsMarketPlace *pMarketPlace;

	pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
	if (pMarketPlace != NULL)
		marketplace = pMarketPlace->GetId();
	
	return GetBlockedItem(marketplace, id, pItem);
}


//
// GetBlockedItemCountById
//

static char *SQL_GetBlockedItemCountById = 
"select count (*) from ebay_items_blocked "
"	where	marketplace = :marketplace "
"	and		id = :id";

unsigned int clsDatabaseOracle::GetBlockedItemCountById(MarketPlaceId marketplace,
														ItemId id)
{
	int count = 0;

	OpenAndParse(&mpCDAGetBlockedItemCountById, SQL_GetBlockedItemCountById);

	Bind(":marketplace", &marketplace);
	Bind(":id", &id);

	Define(1, &count);

	Execute();

	Fetch();

//	if (CheckForNoRowsFound())
//		count = 0;

	Close(&mpCDAGetBlockedItemCountById);
	SetStatement(NULL);

	return (unsigned int)count;
}

unsigned int clsDatabaseOracle::GetBlockedItemCountById(ItemId id)
{
	MarketPlaceId	marketplace = 0;
	clsMarketPlace *pMarketPlace;

	pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
	if (pMarketPlace != NULL)
		marketplace = pMarketPlace->GetId();
	
	return GetBlockedItemCountById(marketplace, id);
}


//
// GetBlockedItemCount
//

static char *SQL_GetBlockedItemCount = 
"select count (*) from ebay_items_blocked "
"	where	marketplace = :marketplace";

unsigned int clsDatabaseOracle::GetBlockedItemCount(MarketPlaceId marketplace)
{
	int count = 0;

	OpenAndParse(&mpCDAGetBlockedItemCount, SQL_GetBlockedItemCount);

	Bind(":marketplace", &marketplace);

	Define(1, &count);

	Execute();

	Fetch();

//	if (CheckForNoRowsFound())
//		count = 0;

	Close(&mpCDAGetBlockedItemCount);
	SetStatement(NULL);

	return (unsigned int)count;
}

unsigned int clsDatabaseOracle::GetBlockedItemCount()
{
	MarketPlaceId	marketplace = 0;
	clsMarketPlace *pMarketPlace;

	pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
	if (pMarketPlace != NULL)
		marketplace = pMarketPlace->GetId();
	
	return GetBlockedItemCount(marketplace);
}

//////////////////////////////////////////////////////////////////////
// Gurinder															//
// ReInstate the item from ebay archive tables and move it to ebay  //
// tables															//
//////////////////////////////////////////////////////////////////////

///////////////////// RECORD COUNT STATEMENTS ////////////////////
static char *SQL_GetThisArcBids =
"	select count(*) from ebay_bids_arc\
	where	marketplace = :marketplace\
	and item_id = :item_id";

static char *SQL_GetThisItem = 
"	select count(*) from ebay_items\
	where	marketplace = :marketplace\
	and id = :item_id";

static char *SQL_GetThisItemBids = 
"	select count(*) from ebay_bids\
	where	marketplace = :marketplace\
	and item_id = :item_id";

static char *SQL_GetThisItemDesc = 
"	select count(*) from ebay_item_desc \
	where	marketplace = :marketplace\
	and id = :item_id";



///////////////////// COPY STATEMENTS ///////////////////////////

static char *SQL_CopyArcBids =
"			insert into ebay_bids           \
			select * from ebay_bids_arc     \
	  where marketplace = :marketplace		\
		and item_id = :item_id";

//ebay_item_arc and ebay_item statements
static char *SQL_CopyArcItems =
"	insert into ebay_items\
	select * from ebay_items_arc\
	where	marketplace = :marketplace\
	and		id = :item_id";


//////////////// DELETE STATEMENTS ////////////////////////////
static char *SQL_DeleteArcBids =
"	delete from ebay_bids_arc	"
"	where	marketplace = :marketplace	"
"	and item_id = :item_id";

static char *SQL_DeleteArcItems =
"	delete from ebay_items_arc	"
"	where	marketplace = :marketplace	"
"	and id = :item_id";

static char *SQL_DeleteArcItemDesc =
"	delete from ebay_item_desc_arc"
"	where	marketplace = :marketplace	"
"	and id = :item_id";


////////////////// ROLLBACK SUPPORT STATEMENTS ////////////
static char* SQL_UndoInsertItem = 
"  delete from ebay_items " 
"	where	marketplace = :marketplace	"
"	and		id = :id";


static char *SQL_UndoInsertItemDesc =
"	delete from  ebay_item_desc  "
"	where	marketplace = :marketplace	"
"	and		id = :id";

///////////////////////////////////////////////////////////////


bool clsDatabaseOracle::ReInstateItem(clsItem * pItem)
{
	int		marketplace;
	int		item_id;
	int		count;
	
	marketplace = pItem->GetMarketPlaceId();
	item_id		= pItem->GetId();

//////////////////////////////////////////////////////////////////
//Copy recs from ebay_item_arc to ebay_item

	//Check if ebay_items has any records for this item	
	if (!DoesItemExists(&item_id, &marketplace, 0))
	{
		OpenAndParse(&mpCDACopyArcItems, SQL_CopyArcItems);
		Bind(":marketplace", &marketplace);
		Bind(":item_id", &item_id);

		Execute();	
		if (CheckForNoRowsUpdated())
		{		
			Close(&mpCDACopyArcItems);
			SetStatement(NULL);
			return false;
		}
		Close(&mpCDACopyArcItems);
		SetStatement(NULL);
		Commit();

	}


////////////////////////////////////////////////////////////////////
//Copy description records from ebay_bid_desc_arc to ebay_item_descp
	// check if pItem has its description
	
	if (pItem->GetDescription() &&
		!DoesItemExists(&item_id, &marketplace, 1))
	{
		AddItemDesc(pItem);
	}

////////////////////////////////////////////////////////////////////
//Copy all the bids from ebay_bids_arc to ebay_bids
	//First check if ebay_bids_arc has any records for this item	
	OpenAndParse(&mpCDASQLGetThisArcBids, SQL_GetThisArcBids);

	//Bind the input variables
	Define(1, &count);
	Bind(":marketplace", &marketplace);
	Bind(":item_id", &item_id);
	
	ExecuteAndFetch();

	if (!CheckForNoRowsFound() && 
		count !=0			   && 	
		!DoesItemExists(&item_id, &marketplace, 2))
	{ 
		//There are records in ebay_bids_arc for this item
		Close(&mpCDASQLGetThisArcBids);
		SetStatement(NULL);

		//Copy recs from ebay_bids_arc ebay_bids
		OpenAndParse(&mpCDACopyArcBids, SQL_CopyArcBids);
		Bind(":marketplace", &marketplace);
		Bind(":item_id", &item_id);

		Execute();		

		if (CheckForNoRowsUpdated())
		{		
			Close(&mpCDACopyArcBids);
			SetStatement(NULL);
			return false;
		}

		Close(&mpCDACopyArcBids);
		SetStatement(NULL);
		Commit();
	}

	Close(&mpCDASQLGetThisArcBids);
	SetStatement(NULL);		

//Well, if we are here that means everything went smooth and
//we are reasdy to delete the item records from ebay_item_arc,
//ebay_item_arc and ebay_item_desc_arc


	//Delete from ebay_bids_arc
	OpenAndParse(&mpCDADeleteArcBids, SQL_DeleteArcBids);
	Bind(":marketplace", &marketplace);
	Bind(":item_id", &item_id);

	Execute();
	Commit();
	Close(&mpCDADeleteArcBids);
	SetStatement(NULL);

	//Delete from ebay_items_desc_arc
	OpenAndParse(&mpCDADeleteArcItemDesc, SQL_DeleteArcItemDesc);
	Bind(":marketplace", &marketplace);
	Bind(":item_id", &item_id);

	Execute();
	Commit();
	Close(&mpCDADeleteArcItemDesc);
	SetStatement(NULL);


	//Delete from ebay_item_arc
	OpenAndParse(&mpCDADeleteArcItems, SQL_DeleteArcItems);
	Bind(":marketplace", &marketplace);
	Bind(":item_id", &item_id);

	Execute();
	Commit();
	Close(&mpCDADeleteArcItems);
	SetStatement(NULL);


	return true;
}

bool clsDatabaseOracle::DoesItemExists(int *pitem_id, int* pmarketplace, int table_id)
{	
	int count;
	unsigned char** cursor = NULL;

	switch (table_id)
	{
		case 0: //Items table
			OpenAndParse(&mpCDASQLGetThisItem, SQL_GetThisItem);
			cursor = &mpCDASQLGetThisItem;
			break;
		
		case 1: //Description table
			OpenAndParse(&mpCDASQLGetThisItemDesc, SQL_GetThisItemDesc);
			cursor = &mpCDASQLGetThisItemDesc;
			break;

		case 2: //Bids table
			OpenAndParse(&mpCDASQLGetThisItemBids, SQL_GetThisItemBids);
			cursor = &mpCDASQLGetThisItemBids;
			break;

		default: //this should never be the case
			return true;

	}
	
	//Bind the input variables
	Define(1, &count);
	Bind(":marketplace", pmarketplace);
	Bind(":item_id", pitem_id);
	ExecuteAndFetch();
	
	if (!CheckForNoRowsFound() && count !=0)
	{
		Close(cursor);
		SetStatement(NULL);		
		return true;
	}

	Close(cursor);
	SetStatement(NULL);		
	return false;		
}


