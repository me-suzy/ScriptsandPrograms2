/*	$Id: clsDatabaseOracleItemsBySeller.cpp,v 1.19.2.2.16.1 1999/08/04 16:51:33 nsacco Exp $	*/
//
//	File:	clsDatabaseOracleItemsBySeller.cpp
//
//	Class:	clsDatabaseOracle
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 02/22/97 michael	- Created
//				- 07/27/99 nsacco	- Added new params to SQL for GetItemsListedByUserActiveOrEnded
#include "eBayKernel.h"

//
// GetSellerItemListFromItems
//
//	Acquires a <list> of items <= 30 days old for a given seller
//	from the items database. NOT the fastest way!
//
#define ORA_SELLER_ITEMS_ARRAYSIZE	50

static const char *SQL_GetSellerItemListFromItems =
"select		id,											\
			TO_CHAR(sale_end, 'YYYY-MM-DD HH24:MI:SS')	\
 from		ebay_items									\
 where		marketplace = :marketplace					\
 and		seller = :seller							\
 and		sale_end > sysdate - 31";

static const char *SQL_GetSellerItemListFromItemsEnded =
"select		id,											\
			TO_CHAR(sale_end, 'YYYY-MM-DD HH24:MI:SS')	\
 from		ebay_items_ended									\
 where		marketplace = :marketplace					\
 and		seller = :seller							\
 and		sale_end > sysdate - 31";

void clsDatabaseOracle::GetSellerItemListFromItems(MarketPlaceId marketplace,
												   int sellerId,
												   SellerItemList *plItems)
{
	// Array fetch goodies
	int					rowsFetched;
	int					n;
	int					i;
	int					rc;

	// Arrays to read things into
	int			itemIds[ORA_SELLER_ITEMS_ARRAYSIZE];
	char		sale_end[ORA_SELLER_ITEMS_ARRAYSIZE][32];

	// For conversion
	time_t				sale_end_time;

	// The lil object..
//	clsSellerItem		*pSellerItem;

	
	// Hokay, let's get started
	OpenAndParse(&mpCDAGetSellerItemListFromItems,
				 SQL_GetSellerItemListFromItems);

	Bind(":marketplace", &marketplace);
	Bind(":seller", &sellerId);

	Define(1, itemIds);
	Define(2, sale_end[0], sizeof sale_end[0]);

	// Execute...
	Execute();

	// Loop around, fetching until we drop
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,
				  ORA_SELLER_ITEMS_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
//			ocan((struct cda_def *)mpCDACurrent);
//			Close(&mpCDAGetSellerItemListFromItems,true);
			Close(&mpCDAGetSellerItemListFromItems);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			// Time Conversion
			ORACLE_DATEToTime(sale_end[i], &sale_end_time);

			plItems->push_back(clsSellerItem(itemIds[i], sale_end_time));
//			pSellerItem	= new clsSellerItem(itemIds[i], sale_end_time);
//			plItems->push_back(*pSellerItem);
		}

	} while (!CheckForNoRowsFound());

	// And now, we're done!
	Close(&mpCDAGetSellerItemListFromItems);
	SetStatement(NULL);

	OpenAndParse(&mpCDAGetSellerItemListFromItemsEnded,
				 SQL_GetSellerItemListFromItemsEnded);

	Bind(":marketplace", &marketplace);
	Bind(":seller", &sellerId);

	Define(1, itemIds);
	Define(2, sale_end[0], sizeof sale_end[0]);

	// Execute...
	Execute();

	// Loop around, fetching until we drop
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,
				  ORA_SELLER_ITEMS_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
//			ocan((struct cda_def *)mpCDACurrent);
//			Close(&mpCDAGetSellerItemListFromItems,true);
			Close(&mpCDAGetSellerItemListFromItemsEnded);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			// Time Conversion
			ORACLE_DATEToTime(sale_end[i], &sale_end_time);

			plItems->push_back(clsSellerItem(itemIds[i], sale_end_time));
//			pSellerItem	= new clsSellerItem(itemIds[i], sale_end_time);
//			plItems->push_back(*pSellerItem);
		}

	} while (!CheckForNoRowsFound());

	// And now, we're done!
	Close(&mpCDAGetSellerItemListFromItemsEnded);
	SetStatement(NULL);
	return;
}


//
// GetSellerItemListFromSellerList
//
//	Acquires a <list> of items <= 30 days old for a given seller
//	from the seller list database (faster!).
//
//	This method returns TRUE if we got a legit item list this
//	way. It returns FALSE if it couldn't get a list, or got 
//	one, and it's been declared invalid.
//
//	This method uses a buffer for a long raw which grows as it's
//	needed. 
//
static const char *SQL_GetSellerItemListFromSellerList =
"select		item_count,									\
			item_list_size,								\
			item_list_size_used,						\
			item_list_valid,							\
			item_list									\
 from		ebay_seller_item_lists						\
 where		id = :seller";

bool clsDatabaseOracle::GetSellerItemListFromSellerList(MarketPlaceId marketplace,
														int sellerId,
														SellerItemList *plItems)
{
	// Things we'll get ;-)
	int				itemCount;
	int				itemListSize;
	int				itemListSizeUsed;
	char			itemListValid[2];

	// Indicators, etc
	sb2				item_list_ind;

	// Misc
	int				i;
	unsigned char	*pCurrentItemListItem;
	int				itemId;
	time_t			saleEndTime;
//	clsSellerItem	*pSellerItem;



	// Let's see if we need a buffer
	if (mpSellerListBuffer	== NULL)
	{
		mSellerListBufferSize	= INITIAL_SELLER_LIST_BUFFER_SIZE;
		mpSellerListBuffer	= new unsigned char[mSellerListBufferSize];
	}


	// Let's go!
	OpenAndParse(&mpCDAGetSellerItemListFromSellerList,
				 SQL_GetSellerItemListFromSellerList);

	Bind(":seller", &sellerId);

	Define(1, &itemCount);
	Define(2, &itemListSize);
	Define(3, &itemListSizeUsed);
	Define(4, itemListValid, sizeof(itemListValid));
	DefineLongRaw(5, mpSellerListBuffer, 
					 mSellerListBufferSize,
					 &item_list_ind);

	ExecuteAndFetch();

	// If we didn't get anything, return false
	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetSellerItemListFromSellerList);
		SetStatement(NULL);
		return false;
	}

	// We don't need no statement no more ;-)
	Close(&mpCDAGetSellerItemListFromSellerList);
	SetStatement(NULL);

	// If itemListEmptied is set, then "something" has declared
	// this list null and void, and we return "false", which 
	// indicates that the list is to be rebuilt.
	if (itemListValid[0] == 'N')
		return false;

#ifdef _MSC_VER
	// Paranoid code.
	// 1) make sure itemListSizeUsed is itemCount*(sizeof(int) + sizeof(time_t))
	// 2) check to see that itemListSizeUsed isn't something wacky (like greater than 30000 items)
	// 3) make sure buffer is valid
	if (itemListSizeUsed != (itemCount*(sizeof(int) + sizeof(time_t)))) 
		EDEBUG('*', "EDEBUG: for seller %d, itemCount (%d) is not in sync with itemListSizeUsed (%d)!!",
		sellerId, itemCount, itemListSizeUsed);
	if ((itemListSizeUsed < 0) || (itemListSizeUsed > (30000*(sizeof(int) + sizeof(time_t))))) 
		EDEBUG('*', "EDEBUG: Absurd itemListSizeUsed for seller %d, itemCount=%d, itemlistSizeUsed=%d!!",
		sellerId, itemCount, itemListSizeUsed);
	if (!AfxIsValidAddress(mpSellerListBuffer, mSellerListBufferSize, true)) 
		EDEBUG('*', "EDEBUG: mpSellerListBuffer is BAD!!");
#endif

	// See if the seller list buffer was too small. If so, just
	// allocate a new one, and recurse ;-). Now, let's be careful
	// here, if the itemListSize _Used_ is big enough, then we 
	// don't CARE if the whole buffer didn't make it in. That way,
	// we're not fragmenting the heck out of the table.
	if (itemListSizeUsed > mSellerListBufferSize)
	{
		delete[]	mpSellerListBuffer;
		mpSellerListBuffer	= NULL;
		mSellerListBufferSize	= mSellerListBufferSize * 2;
		mpSellerListBuffer	= new unsigned char[mSellerListBufferSize];

		return GetSellerItemListFromSellerList(marketplace,
											   sellerId,
											   plItems);
	}

	// If, somehow, the seller list is null, then we'll assume the
	// list is invalid, and return false!
	if (item_list_ind == -1)
		return false;

	// If, the count is 0, then we're done!
	if (itemCount == 0)
		return true;

	// Well! Looks like we got something. Let's get it! Now, we can't
	// guarantee that there's nice alignment in the buffer, so we step
	// through, copying each one TO a nice place, and build the object.
	for (i = 0,
		 pCurrentItemListItem = mpSellerListBuffer;
		 i < itemCount;
		 i++,
		 pCurrentItemListItem = pCurrentItemListItem +
								sizeof(itemId) +
								sizeof(saleEndTime))
	{
		memcpy(&itemId, pCurrentItemListItem, sizeof(itemId));
		memcpy(&saleEndTime, pCurrentItemListItem + sizeof(itemId),
			   sizeof(saleEndTime));
#ifndef _MSC_VER
		// Swap the bytes after reading
		itemId = clsUtilities::FixByteOrder32(itemId);
		saleEndTime = clsUtilities::FixByteOrder32(saleEndTime);
#endif
		plItems->push_back(clsSellerItem(itemId, saleEndTime));
//		pSellerItem	= new clsSellerItem(itemId, saleEndTime);
//		plItems->push_back(*pSellerItem);
	}

	// And, that's that! We're done
	return true;
}



//
// UpdateSellerList
//
//	Takes a <list> of clsSellerItem(s), and builds a nice, "fast"
//	access row for the seller list table. 
//
//	To keep the table from fragmenting, tries to get the size of 
//	the existing list, and use that, if possible.
//
static const char *SQL_GetSellerListSize =
"select	item_list_size_used								\
 from	ebay_seller_item_lists							\
 where	id = :seller";

static const char *SQL_UpdateSellerList = 
"update	ebay_seller_item_lists							\
 set	item_count			= :itemcount,				\
		item_list_size		= :listsize,				\
		item_list_size_used = :listused,				\
		item_list_valid		= \'Y\',						\
		item_list			= :itemlist					\
 where	id = :seller";

static const char *SQL_AddSellerList =
"insert into ebay_seller_item_lists						\
 (														\
	id,													\
	item_count,											\
	item_list_size,										\
	item_list_size_used,								\
	item_list_valid,									\
	item_list											\
 )														\
 values													\
 (														\
	:seller,											\
	:itemcount,											\
	:listsize,											\
	:listused,											\
	'Y',												\
	:itemlist											\
 )";

void clsDatabaseOracle::UpdateSellerList(MarketPlaceId marketplace,
										 int sellerId,
										 SellerItemList *plItems)
{
	int			rc;

	// Size we'll need
	int			listSizeNeeded;

	// Size of the current record, if any
	int			currentListSize;

	// Do we need a new reocrd?
	bool		needNewRow	= false;

	// Things about the new record, if any
	int			itemCount;
	int			listSize;
	int			listSizeUsed;

	SellerItemList::iterator	i;
	unsigned char				*pCurrentListItem;

	// Before we do anything, let's see if we've got enough
	// of a buffer, or even HAVE one ;-)
	if (mpSellerListBuffer	== NULL)
	{
		mSellerListBufferSize	= INITIAL_SELLER_LIST_BUFFER_SIZE;
		mpSellerListBuffer	= new unsigned char[mSellerListBufferSize];
	}

	itemCount		= plItems->size();
	listSizeNeeded	= itemCount * 
					  (sizeof(int) + sizeof(time_t));
#ifdef _MSC_VER
	// Paranoid code.
	// 1) make sure listSizeNeeded is itemCount*(sizeof(int) + sizeof(time_t))
	// 2) check to see that listSizeNeeded isn't something wacky (like greater than 30000 items)
	// 3) make sure buffer is valid
	if (listSizeNeeded != (itemCount*(sizeof(int) + sizeof(time_t)))) EDEBUG('*', "EDEBUG: itemCount is not in sync with listSizeNeeded!!");
	if ((listSizeNeeded < 0) || (listSizeNeeded > (30000*(sizeof(int) + sizeof(time_t))))) EDEBUG('*', "EDEBUG: listSizeNeeded=%d!!", listSizeNeeded);
	if (!AfxIsValidAddress(mpSellerListBuffer, mSellerListBufferSize, true)) EDEBUG('*', "EDEBUG: mpSellerListBuffer is BAD!!");
#endif

	// If we don't have enough buffer, reallocate, and recurse
	if (listSizeNeeded > mSellerListBufferSize)
	{
		delete[]	mpSellerListBuffer;
		mpSellerListBuffer	= NULL;
		mSellerListBufferSize	= mSellerListBufferSize * 2;
		mpSellerListBuffer	= new unsigned char[mSellerListBufferSize];

		UpdateSellerList(marketplace,
 						 sellerId,
						 plItems);
		return;
	}

	// Make pretty
	memset(mpSellerListBuffer, 0x00, mSellerListBufferSize);

	// First, see if we have a record to use ;-)
	OpenAndParse(&mpCDAGetSellerListSize,
				 SQL_GetSellerListSize);

	Bind(":seller", &sellerId);
	Define(1, &currentListSize);

	ExecuteAndFetch();

	// If we didn't find a row, well, remember it.
	if (CheckForNoRowsFound())
	{
		needNewRow		= true;
		currentListSize	= 0;
	}

	// We're done with the cursor
	Close(&mpCDAGetSellerListSize);
	SetStatement(NULL);

	// Ok, at this point, we know that the current buffer is 
	// big enough for the items in the list, so we can go ahead
	// and build the list in the buffer.
	for (i = plItems->begin(),
		 pCurrentListItem = mpSellerListBuffer;
		 i != plItems->end();
		 i++,
		 pCurrentListItem = pCurrentListItem +
							sizeof(int) +		// Was itemId
							sizeof(time_t))	// Was saleEndTime
	{
		// We can't guarantee the alignment of the buffer, so 
		// we'll use memcpy.
		int32_t id = (*i).mId;
		time_t saleend = (*i).mSaleEnd;
#ifndef _MSC_VER
		// Swap the bytes before writing 
		id = clsUtilities::FixByteOrder32(id);
		saleend = clsUtilities::FixByteOrder32((int32_t) saleend);
#endif
		memcpy(pCurrentListItem, &id, sizeof(int));
		memcpy(pCurrentListItem + sizeof(int),
			   &saleend,
			   sizeof(time_t));
	}

	// All righty. Whatever we do, the listSizeUsed will be the
	// listSizeNeeded, which we computed at the beginning.
	listSizeUsed	= listSizeNeeded;

	// Now, if there IS an existing record, and it's size is >=
	// the listSizeUsed, then we'll just keep that size so we
	// can update the record in place. If it's not, then we'll
	// just let it update. 
	if (!needNewRow &&
		(currentListSize >= listSizeNeeded))
	{
		listSize	= currentListSize;
	}
	else
	{
		listSize	= listSizeNeeded;
	}


	// We're all ready to "do the right thing"!
	if (needNewRow)
	{
		OpenAndParse(&mpCDAAddSellerList,
					 SQL_AddSellerList);
	}
	else
	{
		OpenAndParse(&mpCDAUpdateSellerList,
					 SQL_UpdateSellerList);
	}


	Bind(":seller", &sellerId);
	Bind(":itemcount", &itemCount);
	Bind(":listsize", &listSize);
	Bind(":listused", &listSizeUsed);
	BindLongRaw(":itemlist", mpSellerListBuffer, listSize);
	
	// Now, we can get an integrity violation here if someone
	// else just added a record while we weren't looking. 
	rc	= oexec((struct cda_def *)mpCDACurrent);

	//
	// If we got an ORA-0001 (Unique constraint violated), we just
	// close our cursors and recurse. We don't HAVE to do this, but
	// there's a chance the "new" list is out of date. 
	//
	if (needNewRow &&
		((struct cda_def *)mpCDACurrent)->rc == 1)
	{
		Close(&mpCDAAddSellerList);
		SetStatement(NULL);
		UpdateSellerList(marketplace,
						 sellerId,
						 plItems);
		return;
	}
	else
		Check(rc);

	Commit();

	// Well, THAT would be that ;p
	if (needNewRow)
		Close(&mpCDAAddSellerList);
	else
		Close(&mpCDAUpdateSellerList);
	SetStatement(NULL);

	return;
}



//
// InvalidateSellerList
//
//	Indicates that the user's seller list row, if any, is now
//	invalid.
//
static const char *SQL_InvalidateSellerList =
"update	ebay_seller_item_lists					\
 set	item_count = 0,							\
		item_list_size_used = 0,				\
		item_list_valid = \'N\'					\
 where	id = :seller";

void clsDatabaseOracle::InvalidateSellerList(MarketPlaceId marketplace,
											 int sellerId, int itemId, time_t saleEnd)

{
	if ((itemId != 0) && (saleEnd != 0))
	{
		time_t today;
		today = time(0);
		SellerItemList				lItems;
		SellerItemList::iterator	iItem, jItem;
		bool found = false;
		if (GetSellerItemListFromSellerList(marketplace, sellerId, &lItems))
		{
			for (iItem = lItems.begin();
				iItem != lItems.end();
				iItem++)
			{
				if (((*iItem).mId) == itemId)
				{
					found = true;
					break;
				}
				if (((*iItem).mSaleEnd < (today - 30*24*60*60)))
				{
					iItem--;
					jItem = iItem;
					lItems.erase(++jItem);
				}
			}
			if (found)
				return;
			lItems.push_back(clsSellerItem(itemId, saleEnd));
			UpdateSellerList(marketplace, sellerId, &lItems);
			return;
		}
	}
	// Simple
	OpenAndParse(&mpCDAInvalidateSellerList,
				 SQL_InvalidateSellerList);

	Bind(":seller", &sellerId);

	Execute();

	Commit();

	Close(&mpCDAInvalidateSellerList);
	SetStatement(NULL);

	return;
}

// this is only used when a user is "deleted", or merged into another user
// so there is no need for a seller list record; otherwise, use invalidate.
static const char *SQL_DeleteSellerList =
"delete	from ebay_seller_item_lists					\
 where	id = :seller";

void clsDatabaseOracle::DeleteSellerList(MarketPlaceId /* marketplace */,
											 int sellerId)
{
	// Simple
	OpenAndParse(&mpCDADeleteSellerList,
				 SQL_DeleteSellerList);

	Bind(":seller", &sellerId);

	Execute();

	Commit();

	Close(&mpCDADeleteSellerList);
	SetStatement(NULL);

	return;
}

//
// GetItemsListedByUser
//
//	This is the thing which gets items listed by a user.
//	Unfortunatly, it has to get them one by one once it
//	gets thie list, since Oracle array fetch can't have
//	"arrays" of where clauses. ;-(
//
//
#define ORA_ITEMS_LISTED_ARRAYSIZE 20

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetItemsListedByUser =
 "select	/*+ index(ebay_items items_pk ) */			\
			items.id,									\
			items.title,								\
			items.seller,								\
			items.quantity,								\
			items.bidcount,								\
			items.password,								\
			TO_CHAR(items.sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),		\
			TO_CHAR(items.sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),		\
			items.current_price,						\
			items.start_price,							\
			items.reserve_price,						\
			items.high_bidder,							\
			items.private_sale,							\
			items.icon_flags,							\
			items.country_id,							\
			items.currency_id,							\
			items.shipping_option,						\
			items.ship_region_flags,					\
			items.desc_lang,							\
			items.site_id,								\
			users1.userid,								\
			users1.email,								\
			users1.user_state,							\
			users1.flags,								\
			feedback1.score,							\
			TO_CHAR(users1.userid_last_change,			\
				'YYYY-MM-DD HH24:MI:SS'),				\
			users2.userid,								\
			users2.email,								\
			users2.user_state,							\
			users2.flags,								\
			feedback2.score,							\
			TO_CHAR(users2.userid_last_change,			\
				'YYYY-MM-DD HH24:MI:SS'),				\
			items.category								\
	from ebay_items items,								\
		 ebay_users users1,								\
		 ebay_feedback feedback1,						\
		 ebay_users users2,								\
		 ebay_feedback feedback2						\
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
	)													\
	and		items.high_bidder = users1.id (+)			\
	and		items.high_bidder = feedback1.id (+)		\
	and		items.seller = users2.id (+)				\
	and		items.seller = feedback2.id (+)";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetItemsListedByUserGetMoreStuff =
 "select	/*+ index(ebay_items items_pk ) */	"		
			"items.id, "		
			"items.title, "
			"items.seller, "
			"items.quantity,		\
			items.bidcount,		\
			items.password,     \
			TO_CHAR(items.sale_start,			\
						'YYYY-MM-DD HH24:MI:SS'),\
			TO_CHAR(items.sale_end,				\
						'YYYY-MM-DD HH24:MI:SS'),\
			items.current_price,				\
			items.start_price,					\
			items.reserve_price,				\
			items.high_bidder,					\
			items.private_sale,					\
			items.icon_flags,					\
			items.country_id,					\
			items.currency_id,					\
			items.shipping_option,	\
			items.ship_region_flags,	\
			items.desc_lang,	\
			items.site_id,	\
			users1.userid,						\
			users1.email,						\
			users1.user_state,					\
			users1.flags,						\
			feedback1.score,					\
			TO_CHAR(users1.userid_last_change,	\
				'YYYY-MM-DD HH24:MI:SS'),		\
			users2.userid,		\
			users2.email,		\
			users2.user_state,	\
			users2.flags,		\
			feedback2.score,	\
			TO_CHAR(users2.userid_last_change,	\
				'YYYY-MM-DD HH24:MI:SS'),		\
			items.category,		\
		items.location,						\
		((((categories.name4 || ':') ||		\
				categories.name3 || ':') ||	\
					categories.name2 || ':') ||		\
						categories.name1 || ':') ||	\
							categories.name,		\
		items.featured,				\
		items.super_featured,		\
		items.bold_title,			\
		categories.adult,			\
		items.picture_url			\
from ebay_items items,				\
	 ebay_users users1,				\
	 ebay_users users2,				\
	 ebay_categories categories,	\
	 ebay_feedback feedback1,		\
	 ebay_feedback feedback2		\
where	items.marketplace = :marketplace	\
and									\
(	items.id = :i00 or items.id = :i01 or	\
	items.id = :i02 or items.id = :i03 or	\
	items.id = :i04 or items.id = :i05 or	\
	items.id = :i06 or items.id = :i07 or	\
	items.id = :i08 or items.id = :i09 or	\
	items.id = :i10 or items.id = :i11 or	\
	items.id = :i12 or items.id = :i13 or	\
	items.id = :i14 or items.id = :i15 or	\
	items.id = :i16 or items.id = :i17 or	\
	items.id = :i18 or items.id = :i19		\
) \
and		items.seller = users2.id	\
and		items.high_bidder = users1.id (+)	\
and		categories.marketplace = :marketplace \
and		items.category = categories.id		\
	and		items.seller = feedback2.id (+)	\
	and		items.high_bidder = feedback1.id (+)";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetItemsListedByUserEnded =
 "select /*+ index(ebay_items_ended items_ended_pk ) */	\
			items.id,									\
			items.title,								\
			items.seller,								\
			items.quantity,								\
			items.bidcount,								\
			items.password,								\
			TO_CHAR(items.sale_start,					\
						'YYYY-MM-DD HH24:MI:SS'),		\
			TO_CHAR(items.sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),		\
			items.current_price,						\
			items.start_price,							\
			items.reserve_price,						\
			items.high_bidder,							\
			items.private_sale,							\
			items.icon_flags,							\
			items.country_id,					\
			items.currency_id,					\
			items.shipping_option,				\
			items.ship_region_flags,			\
			items.desc_lang,					\
			items.site_id,						\
			users1.userid,								\
			users1.email,								\
			users1.user_state,							\
			users1.flags,								\
			feedback1.score,							\
			TO_CHAR(users1.userid_last_change,			\
				'YYYY-MM-DD HH24:MI:SS'),				\
			users2.userid,								\
			users2.email,								\
			users2.user_state,							\
			users2.flags,								\
			feedback2.score,							\
			TO_CHAR(users2.userid_last_change,			\
				'YYYY-MM-DD HH24:MI:SS'),				\
			items.category								\
	from ebay_items_ended items,								\
		 ebay_users users1,								\
		 ebay_feedback feedback1,						\
		 ebay_users users2,								\
		 ebay_feedback feedback2						\
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
	)													\
	and		items.high_bidder = users1.id (+)			\
	and		items.high_bidder = feedback1.id (+)		\
	and		items.seller = users2.id (+)				\
	and		items.seller = feedback2.id (+)";

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
static const char *SQL_GetItemsListedByUserGetMoreStuffEnded =
 "select /*+ index(ebay_items_ended items_ended_pk ) */	"
			"items.id, "
			"items.title, "
			"items.seller, "
			"items.quantity, "
			"items.bidcount, "
			"items.password, \
			TO_CHAR(items.sale_start,			\
						'YYYY-MM-DD HH24:MI:SS'),\
			TO_CHAR(items.sale_end,				\
						'YYYY-MM-DD HH24:MI:SS'),\
			items.current_price,				\
			items.start_price,					\
			items.reserve_price,				\
			items.high_bidder,					\
			items.private_sale,					\
			items.icon_flags,					\
			items.country_id,					\
			items.currency_id,					\
			items.shipping_option,				\
			items.ship_region_flags,			\
			items.desc_lang,					\
			items.site_id,						\
			users1.userid,						\
			users1.email,						\
			users1.user_state,					\
			users1.flags,						\
			feedback1.score,					\
			TO_CHAR(users1.userid_last_change,	\
				'YYYY-MM-DD HH24:MI:SS'),		\
			users2.userid,		\
			users2.email,		\
			users2.user_state,	\
			users2.flags,		\
			feedback2.score,	\
			TO_CHAR(users2.userid_last_change,	\
				'YYYY-MM-DD HH24:MI:SS'),		\
			items.category,		\
		items.location,						\
		((((categories.name4 || ':') ||		\
				categories.name3 || ':') ||	\
					categories.name2 || ':') ||		\
						categories.name1 || ':') ||	\
							categories.name,		\
		items.featured,				\
		items.super_featured,		\
		items.bold_title,			\
		categories.adult,			\
		items.picture_url			\
from ebay_items_ended items,		\
	 ebay_users users1,				\
	 ebay_users users2,				\
	 ebay_categories categories,	\
	 ebay_feedback feedback1,		\
	 ebay_feedback feedback2		\
where	items.marketplace = :marketplace	\
and									\
(	items.id = :i00 or items.id = :i01 or	\
	items.id = :i02 or items.id = :i03 or	\
	items.id = :i04 or items.id = :i05 or	\
	items.id = :i06 or items.id = :i07 or	\
	items.id = :i08 or items.id = :i09 or	\
	items.id = :i10 or items.id = :i11 or	\
	items.id = :i12 or items.id = :i13 or	\
	items.id = :i14 or items.id = :i15 or	\
	items.id = :i16 or items.id = :i17 or	\
	items.id = :i18 or items.id = :i19		\
) \
and		items.seller = users2.id	\
and		items.high_bidder = users1.id (+)	\
and		categories.marketplace = :marketplace \
and		items.category = categories.id		\
	and		items.seller = feedback2.id (+)	\
	and		items.high_bidder = feedback1.id (+)";

void clsDatabaseOracle::GetItemsListedByUser(
								MarketPlaceId marketplace,
								int id,
								int	daysSince,
								ItemList *pItems,
								bool getMoreStuff /* = false */,
								ItemListSortEnum SortCode /* = SortItemsByUnknown */
								)
{

	// Our list of items ;-)
	SellerItemList				lItems;
	SellerItemList::iterator	iItem;
	SellerItemList				lItemsEnded;
	SellerItemList::iterator	iItemEnded;
	SellerItemList::iterator	iMissingItem;
	ItemList::iterator			pItem;

	bool						listGood = false;
	clsItem						*currentItem;


	// The item
//	clsItem				*pItem;


	// First, we need our list of items
	listGood	= GetSellerItemListFromSellerList(marketplace,
												  id,
												  &lItems);

	if (!listGood)
	{
		GetSellerItemListFromItems(marketplace,
								   id,
								   &lItems);
		UpdateSellerList(marketplace,
						 id,
						 &lItems);
	}


	// See if there's work to be done ;-)
	if (lItems.size() < 1)
		return;

	for (iItem = lItems.begin();
		 iItem != lItems.end();
		 iItem++)
	{
		if ((*iItem).mSaleEnd < time(0) - 60 * 60 * 24 * 21) // three weeks
		{
			lItemsEnded.push_back(*iItem);
		}
	}

	if (lItemsEnded.size() > 0 )
		GetItemsListedByUserActiveOrEnded(marketplace, id, daysSince, &lItemsEnded, pItems,
									getMoreStuff, SortCode, true); // look in the ended

	for (pItem = pItems->begin();
		 pItem != pItems->end();
		 pItem++)
	{
		currentItem = (*pItem).mpItem;

		for (iMissingItem = lItems.begin();
			 iMissingItem != lItems.end();
			 iMissingItem++)
		{
			if ((*iMissingItem).mId == currentItem->GetId())
			{
				lItems.erase(iMissingItem);
				break;
			}
		}
	}
	if (lItems.size() > 0 )
		GetItemsListedByUserActiveOrEnded(marketplace, id, daysSince, &lItems, pItems,
									getMoreStuff, SortCode, false); // now checking at the active

	for (pItem = pItems->begin();
		 pItem != pItems->end();
		 pItem++)
	{
		currentItem	= (*pItem).mpItem;

		for (iMissingItem = lItems.begin();
			 iMissingItem != lItems.end();
			 iMissingItem++)
		{
			if ((*iMissingItem).mId == currentItem->GetId())
			{
				lItems.erase(iMissingItem);
				break;
			}
		}
	}
	if (lItems.size() > 0 ) // so there were more ended after all
		GetItemsListedByUserActiveOrEnded(marketplace, id, daysSince, &lItems, pItems,
									getMoreStuff, SortCode, true);

	lItems.erase(lItems.begin(), lItems.end());
	lItemsEnded.erase(lItemsEnded.begin(), lItemsEnded.end());

	// Sort
	gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetItems()->mCurrentSortMode =
		SortCode;

	if (!pItems->empty())
		pItems->sort();
	return;
}

// nsacco 07/27/99 added shipping_option, ship_region_flags, desc_lang, site_id
void clsDatabaseOracle::GetItemsListedByUserActiveOrEnded(
								MarketPlaceId marketplace,
								int id,
								int	daysSince,
								SellerItemList *lItems,
								ItemList *pItems,
								bool getMoreStuff /* = false */,
								ItemListSortEnum SortCode /* = SortItemsByUnknown */,
								bool ended)
{
	// The following variables help us figure out the cutoff times
	// for the item.
	time_t						nowTime;
	time_t						saleEndsAfter;

	// Our list of items ;-)
	SellerItemList::iterator	iItem;

	// Things to manage our SQL statement
	bool						doneWithStatement;
	bool						doneWithList;
	int							iItemInStatement;

	// This thing is for up to ORA_ITEMS_LISTED_ARRAYSIZE slots
	// for item ids
	int							predicateItemIds[ORA_ITEMS_LISTED_ARRAYSIZE];

	// Array fetch goodies
	int					rowsFetched;
	int					n;
	int					i;
	int					rc;

	// Temporary slots for things to live in
	int					itemId[ORA_ITEMS_LISTED_ARRAYSIZE];
	char				title[ORA_ITEMS_LISTED_ARRAYSIZE][255];
	int					seller[ORA_ITEMS_LISTED_ARRAYSIZE];
	int					quantity[ORA_ITEMS_LISTED_ARRAYSIZE];
	int					bidcount[ORA_ITEMS_LISTED_ARRAYSIZE];
	int					password[ORA_ITEMS_LISTED_ARRAYSIZE];
	char				sale_start[ORA_ITEMS_LISTED_ARRAYSIZE][32];
	time_t				sale_start_time;
	char				sale_end[ORA_ITEMS_LISTED_ARRAYSIZE][32];
	time_t				sale_end_time;
	float				current_price[ORA_ITEMS_LISTED_ARRAYSIZE];
	float				start_price[ORA_ITEMS_LISTED_ARRAYSIZE];
	float				reserve_price[ORA_ITEMS_LISTED_ARRAYSIZE];
	int					high_bidder[ORA_ITEMS_LISTED_ARRAYSIZE];
	sb2					high_bidder_ind[ORA_ITEMS_LISTED_ARRAYSIZE];

	char				privateSale[ORA_ITEMS_LISTED_ARRAYSIZE][2];

	bool				isPrivate;
	char				*pTitle;

	char				highBidderUserId[ORA_ITEMS_LISTED_ARRAYSIZE][255];
	sb2					highBidderUserId_ind[ORA_ITEMS_LISTED_ARRAYSIZE];
	char				highBidderEmail[ORA_ITEMS_LISTED_ARRAYSIZE][65];
	sb2					highBidderEmail_ind[ORA_ITEMS_LISTED_ARRAYSIZE];
	int					highBidderFeedbackScore[ORA_ITEMS_LISTED_ARRAYSIZE];
	sb2					highBidderFeedbackScore_ind[ORA_ITEMS_LISTED_ARRAYSIZE];
	time_t				highbidder_id_last_modified_time[ORA_ITEMS_LISTED_ARRAYSIZE];
	char				highbidder_id_last_modified[ORA_ITEMS_LISTED_ARRAYSIZE][32];
	sb2					highbidder_last_change_ind[ORA_ITEMS_LISTED_ARRAYSIZE];
	int					highBidderUserState[ORA_ITEMS_LISTED_ARRAYSIZE];
	sb2					highBidderUserState_ind[ORA_ITEMS_LISTED_ARRAYSIZE];
	int					highBidderUserFlags[ORA_ITEMS_LISTED_ARRAYSIZE];
	char				*pHighBidderUserId;

	char				sellerUserId[ORA_ITEMS_LISTED_ARRAYSIZE][65];
	sb2					sellerUserId_ind[ORA_ITEMS_LISTED_ARRAYSIZE];
	char				sellerEmail[ORA_ITEMS_LISTED_ARRAYSIZE][65];
	sb2					sellerEmail_ind[ORA_ITEMS_LISTED_ARRAYSIZE];
	int					sellerFeedbackScore[ORA_ITEMS_LISTED_ARRAYSIZE];
	sb2					sellerFeedbackScore_ind[ORA_ITEMS_LISTED_ARRAYSIZE];
	time_t				seller_id_last_modified_time[ORA_ITEMS_LISTED_ARRAYSIZE];
	char				seller_id_last_modified[ORA_ITEMS_LISTED_ARRAYSIZE][32];
	sb2					seller_last_change_ind[ORA_ITEMS_LISTED_ARRAYSIZE];
	int					sellerUserState[ORA_ITEMS_LISTED_ARRAYSIZE];
	sb2					sellerUserState_ind[ORA_ITEMS_LISTED_ARRAYSIZE];
	int					sellerUserFlags[ORA_ITEMS_LISTED_ARRAYSIZE];
	char				*pSellerUserId;
	int					category[ORA_ITEMS_LISTED_ARRAYSIZE];
	int					countryId[ORA_ITEMS_LISTED_ARRAYSIZE];
	sb2					countryId_ind[ORA_ITEMS_LISTED_ARRAYSIZE];
	int					currencyId[ORA_ITEMS_LISTED_ARRAYSIZE];
	sb2					currencyId_ind[ORA_ITEMS_LISTED_ARRAYSIZE];

	// nsacco 07/27/99
	int					shipping_option[ORA_ITEMS_LISTED_ARRAYSIZE];
	long				ship_region_flags[ORA_ITEMS_LISTED_ARRAYSIZE];
	int					desc_lang[ORA_ITEMS_LISTED_ARRAYSIZE];
	int					site_id[ORA_ITEMS_LISTED_ARRAYSIZE];

	// for getMoreStuff
	char				location[ORA_ITEMS_LISTED_ARRAYSIZE][255];
	char				categoryName[ORA_ITEMS_LISTED_ARRAYSIZE][255];
	sb2					categoryName_ind[ORA_ITEMS_LISTED_ARRAYSIZE];
	char				featured[ORA_ITEMS_LISTED_ARRAYSIZE][2];
	char				superFeatured[ORA_ITEMS_LISTED_ARRAYSIZE][2];
	char				boldTitle[ORA_ITEMS_LISTED_ARRAYSIZE][2];
	char				pictureURL[ORA_ITEMS_LISTED_ARRAYSIZE][256];
	sb2					pictureURL_ind[ORA_ITEMS_LISTED_ARRAYSIZE];
	char				*pPictureURL;

	char				*pLocation;
	char				*pCategoryName;
	bool				isFeatured;
	bool				isSuperFeatured;
	bool				isBoldTitle;
	char				adult[ORA_ITEMS_LISTED_ARRAYSIZE][2];

	char				iconFlags[ORA_ITEMS_LISTED_ARRAYSIZE][3];
	sb2					iconFlags_ind[ORA_ITEMS_LISTED_ARRAYSIZE];
	char				*pIconFlags;

	// The item
	clsItem				*pItem;
//	clsItemPtr			*pItemPtr;

	if (lItems->size() < 1)
		return;

	// Set up the date criteria stuff
	nowTime		= time(0);
	if (daysSince == -1)
	{
		saleEndsAfter	= nowTime;
	}
	else
	{
		saleEndsAfter	= nowTime -
						  (daysSince * 24 * 60 * 60);
	}

	// Let's get our statement ready
	if (ended)
		if (!getMoreStuff)
			OpenAndParse(&mpCDAGetItemsListedByUserEnded, SQL_GetItemsListedByUserEnded);
		else
			OpenAndParse(&mpCDAGetItemsListedByUserGetMoreStuffEnded, SQL_GetItemsListedByUserGetMoreStuffEnded);
	else
		if (!getMoreStuff)
			OpenAndParse(&mpCDAGetItemsListedByUser, SQL_GetItemsListedByUser);
		else
			OpenAndParse(&mpCDAGetItemsListedByUserGetMoreStuff, SQL_GetItemsListedByUserGetMoreStuff);

	// Ok, first we bind 
	Bind(":marketplace", (int *)&marketplace);
	Bind(":i00", &predicateItemIds[0]);
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
	Define(2, title[0], sizeof(title[0]));
	Define(3, seller);
	Define(4, quantity);
	Define(5, bidcount);
	Define(6, password);
	Define(7, sale_start[0], sizeof(sale_start[0]));
	Define(8, sale_end[0], sizeof(sale_end[0]));
	Define(9, current_price);
	Define(10, start_price);
	Define(11, reserve_price);
	Define(12, high_bidder, high_bidder_ind);
	Define(13, privateSale[0], sizeof(privateSale[0]));
	Define(14, iconFlags[0], sizeof(iconFlags[0]), iconFlags_ind);
	Define(15, &countryId[0], countryId_ind);
	Define(16, &currencyId[0], currencyId_ind);
	// nsacco 07/27/99
	Define(17, shipping_option);
	Define(18, ship_region_flags);
	Define(19, desc_lang);
	Define(20, site_id);

	Define(21, highBidderUserId[0], sizeof(highBidderUserId[0]),
			   highBidderUserId_ind);
	Define(22, highBidderEmail[0], sizeof(highBidderEmail[0]),
			   highBidderEmail_ind);
	Define(23, highBidderUserState, highBidderUserState_ind);
	Define(24, highBidderUserFlags);
	Define(25, highBidderFeedbackScore, highBidderFeedbackScore_ind);
	Define(26, highbidder_id_last_modified[0], sizeof(highbidder_id_last_modified[0]), highbidder_last_change_ind);
	Define(27, sellerUserId[0], sizeof(sellerUserId[0]),
			   sellerUserId_ind);
	Define(28, sellerEmail[0], sizeof(sellerEmail[0]),
			   sellerEmail_ind);
	Define(29, sellerUserState, sellerUserState_ind);
	Define(30, sellerUserFlags);
	Define(31, sellerFeedbackScore, sellerFeedbackScore_ind);
	Define(32, seller_id_last_modified[0], sizeof(seller_id_last_modified[0]), seller_last_change_ind);
	Define(33, category);

	if (getMoreStuff)
	{
		Define(34, location[0], sizeof(location[0]));
		Define(35, categoryName[0], sizeof(categoryName[0]),
				   categoryName_ind);
		Define(36, featured[0], sizeof(featured[0]));
		Define(37, superFeatured[0], sizeof(superFeatured[0]));
		Define(38, boldTitle[0], sizeof(boldTitle[0]));
		Define(39, adult[0], sizeof(adult[0]));
		Define(40, pictureURL[0], sizeof(pictureURL[0]),
					pictureURL_ind);
	}

	// Ok, now, this is weird. In order to get the benefits of array
	// fetch, we needed a way to ask for _multiple_ items in one 
	// query. We either kludged this or did it very elegantly by 
	// having an "or" clause with 20 possible items in it. We now
	// need to traverse our list of items, and fill these in, one
	// by one. 

	iItemInStatement	= 0;
	doneWithStatement	= false;
	doneWithList		= false;

	for (iItem = lItems->begin();
		 ;
		 iItem++)
	{
		// If we're at the end of the list, fill out the rest
		// of the predicate item ids
		if (iItem == lItems->end())
		{
			// iItemInStatement is where we are now, fill it 
			// out to ORA_ITEMS_LISTED_ARRAYSIZE...
			for (;
				 iItemInStatement < ORA_ITEMS_LISTED_ARRAYSIZE;
				 iItemInStatement++)
			{
				predicateItemIds[iItemInStatement] = 0;
			}

			doneWithList		= true;
			doneWithStatement	= true;
		}
		else
		{
			// If the item's "Sale End" time ends at or after the
			// cutoff time, then we'll use it!
			if ((*iItem).mSaleEnd >= saleEndsAfter)
			{
				predicateItemIds[iItemInStatement] = (*iItem).mId;
				iItemInStatement++;

				// Let's see we've "filled up" a statement
				if (iItemInStatement >= ORA_ITEMS_LISTED_ARRAYSIZE)
					doneWithStatement	= true;
			}
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
					  ORA_ITEMS_LISTED_ARRAYSIZE);

			if ((rc < 0 || rc >= 4)  && 
				((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
			{
				Check(rc);
				ocan((struct cda_def *)mpCDACurrent);
				Close(&mpCDAGetItemsListedByUser, true);
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

				// Now everything is where it's supposed
				// to be. Let's make copies of the title
				// and location for the item
				pTitle		= new char[strlen(title[i]) + 1];
				strcpy(pTitle, (char *)title[i]);

				// Time Conversions
				ORACLE_DATEToTime(sale_start[i], &sale_start_time);
				ORACLE_DATEToTime(sale_end[i], &sale_end_time);

				// Handle null high bidder
				if (high_bidder_ind[i] == -1)
					high_bidder[i] = 0;

				if (privateSale[i][0] == '1')
					isPrivate	= true;
				else
					isPrivate	= false;

				if (highBidderUserId_ind[i] == -1)
					highBidderUserId[i][0] = '\0';

				pHighBidderUserId	= new char[strlen(highBidderUserId[i]) + 1];
				strcpy(pHighBidderUserId, highBidderUserId[i]);

				if (highBidderFeedbackScore_ind[i] == -1)
					highBidderFeedbackScore[i]	= INT_MIN;

				if (highbidder_last_change_ind[i] != -1)
					ORACLE_DATEToTime(highbidder_id_last_modified[i], &highbidder_id_last_modified_time[i]);
				else
					highbidder_id_last_modified_time[i] = 0;

				// seller id
				if (sellerUserId_ind[i] == -1)
					sellerUserId[i][0]	= '\0';

				if (countryId_ind[i] == -1)
					countryId[i] = Country_None;

				if (currencyId_ind[i] == -1)
					currencyId[i] = Currency_USD;

				pSellerUserId	= new char[strlen(sellerUserId[i]) + 1];
				strcpy(pSellerUserId, sellerUserId[i]);

				// seller feedback
				if (sellerFeedbackScore_ind[i] == -1)
					sellerFeedbackScore[i] = INT_MIN;

				// oracle time conversion
				if (seller_last_change_ind[i] != -1)
					ORACLE_DATEToTime(seller_id_last_modified[i], &seller_id_last_modified_time[i]);
				else
					seller_id_last_modified_time[i] = 0;

				if (iconFlags_ind[i] == -1)
				{
					pIconFlags	= NULL;
				}
				else
				{
					pIconFlags	= new char[strlen(iconFlags[i]) + 1];
					strcpy(pIconFlags, iconFlags[i]);
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

				if (getMoreStuff)
				{
					//lint -save -e644 -e645 we know stuff is initted OK
					// location
					pLocation	= new char[strlen(location[i]) + 1];
					strcpy(pLocation, (char *)location[i]);

					// category name
					if (categoryName_ind[i] == -1)
						categoryName[i][0] = '\0';


					pCategoryName	= new char[strlen(categoryName[i]) + 1];
					strcpy(pCategoryName, categoryName[i]);


					// featured
					if (featured[i][0] == '1')
						isFeatured	= true;
					else
						isFeatured	= false;

					// superfeatured
					if (superFeatured[i][0] == '1')
						isSuperFeatured	= true;
					else
						isSuperFeatured	= false;

					// Bold
					if (boldTitle[i][0] == '1')
						isBoldTitle	= true;
					else
						isBoldTitle	= false;


					//picture url
					if (pictureURL_ind[i] == -1)
					{
						pPictureURL	= NULL;
					}
					else
					{
						pPictureURL	= new char[strlen(pictureURL[i]) + 1];
						strcpy(pPictureURL, pictureURL[i]);
					}

				}

				if (getMoreStuff)
				{
					// Fill in the item
					pItem	= new clsItem;
					// nsacco 07/27/99 new params
					pItem->Set(marketplace,
							   itemId[i],
							   AuctionUnknown,
							   pTitle,
							   NULL,
							   pLocation,					// getMoreStuff
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
							   high_bidder[i],
							   isFeatured,					// getMoreStuff
							   isSuperFeatured,				// getMoreStuff
							   isBoldTitle,					// getMoreStuff
							   isPrivate, 
							   FALSE,
							   NULL,
							   0,
							   pPictureURL,					// getMoreStuff
							   pCategoryName,				// getMoreStuff
							   pSellerUserId,			
							   sellerUserState[i],	
							   sellerUserFlags[i],			
							   pHighBidderUserId,
							   highBidderUserState[i],	
							   highBidderUserFlags[i],								   
							   sellerFeedbackScore[i],		
							   highBidderFeedbackScore[i],
							   seller_id_last_modified_time[i],
							   highbidder_id_last_modified_time[i],
							   (time_t)0,
							   sellerEmail[i],
							   highBidderEmail[i],
							   password[i],
							   NULL,
								0,
								pIconFlags, // for ts and cs
								NULL,
								NoneGallery,
								kGalleryNotProcessed,
								countryId[i],
								currencyId[i],
								ended,
								NULL,
								Currency_USD,			// billing currency
								shipping_option[i],
								ship_region_flags[i],
								desc_lang[i],
								site_id[i]
								);   

					pItem->SetAdult(adult[i][0]);				// getMoreStuff
					//lint -restore
				}
				else
				{
					// Fill in the item
					pItem	= new clsItem;
					// nsacco 07/27/99 new params
					pItem->Set(marketplace,
							   itemId[i],
							   AuctionUnknown,
							   pTitle,
							   NULL,
							   NULL,
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
							   high_bidder[i],
							   FALSE,
							   FALSE,
							   FALSE,
							   isPrivate, 
							   FALSE,
							   NULL,
							   0,
							   NULL,
							   NULL,
							   pSellerUserId,				
							   sellerUserState[i],		
							   sellerUserFlags[i],			
							   pHighBidderUserId,
							   highBidderUserState[i],		
							   highBidderUserFlags[i],			
							   sellerFeedbackScore[i],		
							   highBidderFeedbackScore[i],
							   seller_id_last_modified_time[i],	
							   highbidder_id_last_modified_time[i],
							   (time_t)0,
							   sellerEmail[i],
							   highBidderEmail[i],
							   password[i],
								NULL,
								0,
								pIconFlags,   // for ts and cs
								NULL,
								NoneGallery,
								kGalleryNotProcessed,
								countryId[i],
								currencyId[i],
								ended,
								NULL,
								Currency_USD,		// billing currency
								shipping_option[i],
								ship_region_flags[i],
								desc_lang[i],
								site_id[i]
								);

				}

				pItems->push_back(clsItemPtr(pItem));
			}
		} while (!CheckForNoRowsFound());

		// Ok, we've handled ORA_ITEMS_LISTED_ARRAYSIZE items from
		// the array. If we're not done yet, let's reset some things,
		// and move on, otherwise, just break!
		if (doneWithList)
			break;
		
		iItemInStatement	= 0;
		doneWithStatement	= false;
	}	

	// Clean up
	if (ended)
		if (!getMoreStuff)
			Close(&mpCDAGetItemsListedByUserEnded);
		else
			Close(&mpCDAGetItemsListedByUserGetMoreStuffEnded);
	else
		if (!getMoreStuff)
			Close(&mpCDAGetItemsListedByUser);
		else
			Close(&mpCDAGetItemsListedByUserGetMoreStuff);
	SetStatement(NULL);
	return;
}



//
// GetUsersWithBidderLists
//
//	Used to get a vector of ids which have an entry in the
//	bidder cache.
//
#define ORA_USERS_WITH_BIDDER_LISTS_ARRAYSIZE 1000


void clsDatabaseOracle::GetUsersWithSellerLists(vector <unsigned int>& vUserList)
{
	static const char *SQL_GetUsersWithSellerLists =
		"select	id from ebay_seller_item_lists";

	
	// Here's where the user ids go
	unsigned int		ids[ORA_USERS_WITH_BIDDER_LISTS_ARRAYSIZE];

	// Array fetch goodies
	int					rowsFetched;
	int					n;
	int					rc;

	// *** NOTE ***
	// One-time swag
	// *** NOTE ***
	vUserList.reserve(1600000);

	// Ok, standard stuff
	OpenAndParse(&mpCDAOneShot, SQL_GetUsersWithSellerLists);

	Define(1, ids);

	// Run it...
	Execute();

	// Loop around, fetching until we drop
	rowsFetched = 0;

	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,
				  ORA_USERS_WITH_BIDDER_LISTS_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		copy(ids, ids + n, back_inserter(vUserList));
	} while (!CheckForNoRowsFound());

	Close(&mpCDAOneShot);
	SetStatement(NULL);

}
		

//
// IsABidderForThisItem
//

// This query is used to validate that the user had bid 
// on this item and did not cvancel or retract a bid
// inna- thsi is used by iEscrow validation
static char *SQL_IsABidderForThisItem =
 "select	item_id								\
  from ebay_bids /* index */					\
  where	item_id = :item_id						\
  and	user_id = :user_id						\
  and	type <> 0								\
  and	type <> 3								\
  and	type <> 4								\
  and	type <> 5								\
  and	type <> 6";
bool clsDatabaseOracle::IsABidderForThisItem (int Item, int UserId)
{
	int item;
	bool IsBidder = true;

	// Open + Parse
	OpenAndParse(&mpCDAOneShot, SQL_IsABidderForThisItem);

	// Bind
	Bind(":item_id", &Item);
	Bind(":user_id", &UserId);

	Define(1, &item);

	// do it
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
		IsBidder = false;

	// Leave it!
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return IsBidder;
}


	
