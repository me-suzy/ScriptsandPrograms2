/*	$Id: clsDatabaseOracleArchive.cpp,v 1.15.2.2 1999/05/25 23:49:29 inna Exp $	*/
//
//	File:	clsDatabaseOracleArchive.cc
//
//	Class:	clsDatabaseOracleArchive
//
//	Author:	Tini Widjojo (tini@ebay.com)
//
//	Function:
//		All functions that gets data from archived tables
//
// Modifications:
//				- 01/07/97 tini		- Created

#include "eBayKernel.h"

static const char *SQL_ClearItemsToBad = 
	"delete										\
		from	ebay_items_bad";

void clsDatabaseOracle::ClearItemsToBad()
{

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_ClearItemsToBad);

	// Execute
	Execute();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

static const char *SQL_SetItemsToBad = 
	"insert into ebay_items_bad			\
		values (:id, :btype)";

void clsDatabaseOracle::SetItemsToBad(int id, int badtype)
{
	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_SetItemsToBad);

	Bind(":id", &id);
	Bind(":btype", &badtype);

	// Execute
	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

// used to set description in archive item table
static const char*SQL_UpdateItemDescArc =
 (char *)
 "Update ebay_item_desc_arc					\
	set	description_len = :itemdesclen,		\
		description	= :itemdesc				\
	where marketplace = :marketplace		\
	and	  id = :id";

void clsDatabaseOracle::UpdateItemDescArc(clsItem *pItem)
{
	int					marketplaceid;
	int					id;
	// description length
	char				*pDescription;
	int					descriptionLen;

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
	
	// We don't use this statement very often,
	// so the cursor's not persistent. Let's 
	// prepare the statement
	OpenAndParse(&mpCDAUpdateItemDescArc, SQL_UpdateItemDescArc);

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
		Close(&mpCDAUpdateItemDescArc);
		SetStatement(NULL);
		AddItemDescArc(pItem);
		return;
	}

	// Commit
	Commit();

	// Free things
	Close(&mpCDAUpdateItemDescArc);
	SetStatement(NULL);

	return;
}

void clsDatabaseOracle::GetMonthYear(time_t saleStart, char *month, char *year)
{
	struct tm *saleStartTM;             
    saleStartTM = localtime( &saleStart); 	
	month = new char[3];
	year = new char[5];
	sprintf( month, "%d", saleStartTM->tm_mon+1 );
	sprintf( year, "%d", saleStartTM->tm_year );
	return;
}

//
// AddItemDesc
//

static const char*SQL_AddItemDescArcI =
 (char *)
 "insert into ";

static const char*SQL_AddItemDescArcII =
 (char *)
"	(	marketplace,			\
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
void clsDatabaseOracle::AddItemDescArcByMonth(clsItem *pItem)
{
	int					marketplaceid;
	int					id;
	// description length
	char				*pDescription;
	int					descriptionLen;
	char				*month;
	char				*year;
	char				*tableName;
	char				*SQL_CurrentArchiveStatement;

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
	if (descriptionLen == 0)
		return;
	tableName = new char[31];
	month = new char[3];
	year = new char[5];
	SQL_CurrentArchiveStatement =  new char[1024];

	GetMonthYear(pItem->GetEndTime(), month, year);
	sprintf(tableName, "ebay_item_desc_arc_%s%s", month, year);
	CombineSQLStatement( SQL_AddItemDescArcI, tableName, 
		SQL_AddItemDescArcII, SQL_CurrentArchiveStatement);
	delete [] tableName;
	delete [] month;
	delete [] year;

	// We don't use this statement very often,
	// so the cursor's not persistent. Let's 
	// prepare the statement
	OpenAndParse(&mpCDAOneShot, SQL_CurrentArchiveStatement);
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
	delete [] SQL_CurrentArchiveStatement;
	// Free things
//	Close(&mpCDAAddItemDescArc);
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

static const char*SQL_AddItemDescArc =
 (char *)
"insert into ebay_item_desc_arc \
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

void clsDatabaseOracle::AddItemDescArc(clsItem *pItem)
{
	int					marketplaceid;
	int					id;
	// description length
	char				*pDescription;

	int					descriptionLen;
//	char				*month;
//	char				*year;
	char				*tableName;
//	char				*SQL_CurrentArchiveStatement;


	// Extract things from the item into our
	// local variables to prevent any casting
	// confusion
	marketplaceid	= pItem->GetMarketPlaceId();
	id				= pItem->GetId();

	pDescription		= pItem->GetDescription();
	if (pDescription)
		descriptionLen	= strlen(pDescription);
	else
		pDescription	= "";
	if (descriptionLen == 0)
		return;

	tableName = new char[31];
//	GetMonthYear(pItem->GetStartTime(), month, year);
//	sprintf(tableName, "ebay_items_desc_arc_%d%d", month, year);
//	CombineSQLStatement( SQL_AddItemDescArcI, tableName, 
//		SQL_AddItemDescArcII, SQL_CurrentArchiveStatement);
//	delete [] tableName;

	// We don't use this statement very often,
	// so the cursor's not persistent. Let's 
	// prepare the statement
//	OpenAndParse(&mpCDAOneShot, SQL_CurrentArchiveStatement);
	OpenAndParse(&mpCDAAddItemDescArc, SQL_AddItemDescArc);

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
	Close(&mpCDAAddItemDescArc);
	SetStatement(NULL);

	return;
}

static const char *SQL_AddItemDescEnded =
 "insert into ebay_item_desc_ended	\
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

void clsDatabaseOracle::AddItemDescEnded(clsItem *pItem)
{
	int					marketplaceid;
	int					id;
	// description length
	char				*pDescription;
	int					descriptionLen;

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
	if (descriptionLen == 0)
		return;

	// We don't use this statement very often,
	// so the cursor's not persistent. Let's 
	// prepare the statement
	OpenAndParse(&mpCDAAddItemDescEnded, SQL_AddItemDescEnded);

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
	Close(&mpCDAAddItemDescEnded);
	SetStatement(NULL);

	return;
}

//
// GetItemsByEndDateArc - GetItemsByEndDate for Archived data
//
//	Gets a vector of items which ends within a given date range
//
//	** NOTE **
//	These are very much "stub" items -- they only
//	contain the marketplace and item id. 
//	** NOTE **
//
//
#define ORA_ITEMARCSELECT_ARRAYSIZE	500

/* inna please keep until next release
static const char *SQL_GetItemsByEndDateArc = 
	"select		distinct id	\
		from	ebay_items_arc									\
		where		marketplace = :marketplace				\
		and		sale_end	>= TO_DATE(:fromdate,'YYYY-MM-DD HH24:MI:SS')	\
		and		sale_end  <= TO_DATE(:todate,'YYYY-MM-DD HH24:MI:SS')	\
		order by category";
*/
/* here is new sql to make is faster */
static const char *SQL_GetItemsByEndDateArc = 
	"select		id	\
		from	ebay_items_arc									\
		where	sale_end	>= TO_DATE(:fromdate,'YYYY-MM-DD HH24:MI:SS')	\
		and		sale_end  <= TO_DATE(:todate,'YYYY-MM-DD HH24:MI:SS')";

void clsDatabaseOracle::GetItemsByEndDateArc(MarketPlaceId marketplace,
										  vector<int> *pvItems,
										  char *fromdate,
										  char *todate)
{
	int			id[ORA_ITEMARCSELECT_ARRAYSIZE];
	int			rowsFetched;
	int			rc;
	int			i,n;
	char		cFromDate[64];
	char		cToDate[64];

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetItemsByEndDateArc);

	strcpy(cFromDate, fromdate);
	strcpy(cToDate, todate);

	// Bind
//	Bind(":marketplace", (int *)&marketplace);
	Bind(":fromdate", cFromDate);
	Bind(":todate", cToDate);

	// Define
	Define(1, (int *)id);

	// Execute
	Execute();

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_ITEMARCSELECT_ARRAYSIZE);

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
		// (always <= ORA_ITEMARCSELECT_ARRAYSIZE). 
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

// gets abbreviated item for accounting batches
static const char *SQL_GetItemArc =
 "select	sale_type,							\
			seller,								\
			owner								\
	from ebay_items_arc							\
	where	id = :itemid";
// marketplace assumed 0

bool clsDatabaseOracle::GetItemArc(int id,
							    clsItem *pItem)
{
	// Temporary slots for things to live in
	AuctionTypeEnum		saleType;	
	int					seller;
	int					owner;

	OpenAndParse(&mpCDAOneShot, SQL_GetItemArc);

	// Bind the input variable
	Bind(":itemid", &id);

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, (int *)&saleType);
	Define(2, &seller);
	Define(3, &owner);

	// Fetch
	Execute();
	Fetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return false;
	}
	// Now everything is where it's supposed
	// to be. Let's make copies of the title
	// and location for the item

	// Fill in the item
	pItem->SetId(id);
	pItem->SetSeller(seller);
	pItem->SetOwner(owner);
	pItem->SetAuctionType(saleType);

	ocan((struct cda_def *)mpCDACurrent);
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return true;
}

//
// GetItem
//
/* inna please keep until next release
static const char *SQL_GetItemArcFull =
 "select	distinct sale_type,					\
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
				'YYYY-MM-DD HH24:MI:SS')		\
	from ebay_items_arc							\
	where	marketplace = :marketplace			\
	and		id = :itemid"; */

static const char *SQL_GetItemArcFull =
 "select	distinct sale_type,					\
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
				'YYYY-MM-DD HH24:MI:SS')		\
	from ebay_items_arc							\
	where	id = :itemid";

bool clsDatabaseOracle::GetItemArc(int marketplace,
								int id,
							    clsItem *pItem)
{
	// Temporary slots for things to live in
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

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	OpenAndParse(&mpCDAOneShot, SQL_GetItemArcFull);

	// Bind the input variable
	Bind(":itemid", &id);
	//Bind(":marketplace", &marketplace);

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, (int *)&saleType);
	Define(2, title, sizeof(title));
	Define(3, location, sizeof(location));
	Define(4, &seller);
	Define(5, &owner);
	Define(6, &password);
	Define(7, &category);
	Define(8, &quantity);
	Define(9, &bidcount);
	Define(10, sale_start, sizeof(sale_start));
	Define(11, sale_end, sizeof(sale_end));
	Define(12, &sale_status);
	Define(13, &current_price);
	Define(14, &start_price);
	Define(15, &reserve_price);
	Define(16, &high_bidder, &high_bidder_ind);
	Define(17, featured, sizeof(featured));
	Define(18, superFeatured, sizeof(superFeatured));
	Define(19, boldTitle, sizeof(boldTitle));
	Define(20, privateSale, sizeof(privateSale));
	Define(21, registeredOnly, sizeof(registeredOnly));
	Define(22, host, sizeof(host), &host_ind);
	Define(23, &visitcount);
	Define(24, pictureURL, sizeof(pictureURL),
			&pictureURL_ind);
	Define(25, last_modified, sizeof(last_modified));

	// Fetch
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
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

	// Fill in the item
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
			   password);

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return true;
}
// Get Item Description from Archive

static const char *SQL_GetItemDescArcLen =
 "select	description_len						\
	from ebay_item_desc_arc						\
	where	id = :itemid";

static const char *SQL_GetItemDescArc =
 "select	description							\
	from ebay_item_desc_arc						\
	where	id = :itemid";

bool clsDatabaseOracle::GetItemDescArc(int id,
							    clsItem *pItem)
{
	int					description_len;
	unsigned char		*pDescription;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	OpenAndParse(&mpCDAOneShot, SQL_GetItemDescArcLen);

	// Bind the input variable
	Bind(":itemid", &id);

	// Define the output
	Define(1, &description_len);

	// Fetch
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return false;
	}
	// Now we allocate space for description based on length
	pDescription	= new unsigned char[description_len + 1];

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	// get actual description
	OpenAndParse(&mpCDAGetItemDescArc,
				 SQL_GetItemDescArc);

	// Bind the input variable
	Bind(":itemid", &id);

	// Define the output - this won't work either!
	DefineLongRaw(1, pDescription, description_len);

	// Get it!
	ExecuteAndFetch();

	*(pDescription + description_len)	= '\0';

	// hardcode marketplace!
	pItem->SetDesc(0,
					id,
					(char *)pDescription);

	Close(&mpCDAGetItemDescArc);
	SetStatement(NULL);

	return true;
}

int clsDatabaseOracle::GetItemDescArc(int id,
							    unsigned char **description)
{
	int					description_len;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	OpenAndParse(&mpCDAOneShot, SQL_GetItemDescArcLen);

	// Bind the input variable
	Bind(":itemid", &id);

	// Define the output
	Define(1, &description_len);

	// Fetch
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return false;
	}
	// Now we allocate space for description based on length
	*description	= new unsigned char[description_len + 1];

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	// get actual description
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetItemDescArc);

	// Bind the input variable
	Bind(":itemid", &id);

	// Define the output - this won't work either!
	DefineLongRaw(1, *description, description_len);

	// Get it!
	ExecuteAndFetch();

	*(*description + description_len)	= '\0';
	// hardcode marketplace!
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return description_len;
}

static const char *SQL_GetItemIdsFromTemp =
//"select id from temp_item_arc where category = 2037";
"select id from ebay_items_ended";

void clsDatabaseOracle::GetItemsFromTemp(vector<int> *vItems)
{
	int id[ORA_ITEMARCSELECT_ARRAYSIZE];;
	int		rowsFetched;
	int		rc;
	int n, i;
	OpenAndParse(&mpCDAOneShot, SQL_GetItemIdsFromTemp);

	// Define the output
	Define(1, (int *)id);

	// Fetch
	Execute();
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_ITEMARCSELECT_ARRAYSIZE);

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
		// (always <= ORA_ITEMARCSELECT_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			vItems->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());
	Close(&mpCDAOneShot,true);
	SetStatement(NULL);
	return;
}


// GetItemWithDescArc
// removing get description for now; too slow with no index
static const char *SQL_GetItemWithDescArc =
 "select	items.sale_type,							\
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
			itemdesc.description_len,					\
			itemdesc.description,						\
			users1.userid,								\
			users1.user_state,							\
			users1.flags,								\
			users2.userid,								\
			users2.user_state,							\
			users2.flags,								\
			((((categories.name4 || ':') ||				\
					categories.name3 || ':') ||			\
						categories.name2 || ':') ||		\
							categories.name1 || ':') ||	\
								categories.name			\
	from ebay_items_arc items,							\
		 ebay_item_desc_arc itemdesc,					\
		 ebay_users users1,								\
		 ebay_users users2,								\
		 ebay_categories categories						\
	where	items.marketplace = 0						\
	and		items.id = :itemid							\
	and		items.id = itemdesc.id (+)					\
	and		items.seller = users1.id					\
	and		items.high_bidder = users2.id (+)			\
	and		categories.marketplace = 0					\
	and		items.category = categories.id";


bool clsDatabaseOracle::GetItemWithDescArc(
								int marketplace,
								int id,
							    clsItem *pItem)
{
	// Buffer Size
	int					description_buffer_size = 16384;

	// Temporary slots for things to live in
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
	sb2					sellerUserId_ind;
	int					sellerUserState;
	sb2					sellerUserState_ind;
	int					sellerUserFlags;
	char				highBidderUserId[255];
	sb2					highBidderUserId_ind;
	int					highBidderUserState;
	sb2					highBidderUserState_ind;
	int					highBidderUserFlags;
//	int					sellerFeedbackScore;
//	sb2					sellerFeedbackScore_ind;
//	int					highBidderFeedbackScore;
//	sb2					highBidderFeedbackScore_ind;


	char				*pLocation;
	char				*pTitle;
	char				*pSellerUserId;
	char				*pHighBidderUserId;
	char				*pCategoryName;

	unsigned char		*pDescription;

	// Let's see if we have out description buffer
	if (mpDescriptionBuffer	== NULL)
		mpDescriptionBuffer	= new unsigned char[description_buffer_size];


	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	OpenAndParse(&mpCDAOneShot, 
				 SQL_GetItemWithDescArc);

	// Bind the input variable
	Bind(":itemid", &id);
	//Bind(":marketplace", &marketplace);

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, (int *)&saleType);
	Define(2, (char *)title, sizeof(title));
	Define(3, (char *)location, sizeof(location));
	Define(4, &seller);
	Define(5, &owner);
	Define(6, &password);
	Define(7, &category);
	Define(8, &quantity);
	Define(9, &bidcount);
	Define(10, sale_start, sizeof(sale_start));
	Define(11, sale_end, sizeof(sale_end));
	Define(12, &sale_status);
	Define(13, &current_price);
	Define(14, &start_price);
	Define(15, &reserve_price);
	Define(16, &high_bidder, &high_bidder_ind);
	Define(17, featured, sizeof(featured));
	Define(18, superFeatured, sizeof(superFeatured));
	Define(19, boldTitle, sizeof(boldTitle));
	Define(20, privateSale, sizeof(privateSale));
	Define(21, registeredOnly, sizeof(registeredOnly));
	Define(22, host, sizeof(host), &host_ind);
	Define(23, &visitcount);
	Define(24, pictureURL, sizeof(pictureURL),
		   &pictureURL_ind);
	Define(25, last_modified, sizeof(last_modified));
	Define(26, &descriptionLen, &descriptionLen_ind);
	DefineLongRaw(27, mpDescriptionBuffer, 
					  description_buffer_size,
					  &description_ind);
	Define(28, sellerUserId, sizeof(sellerUserId),
			   &sellerUserId_ind);
	Define(29, &sellerUserState, 
			   &sellerUserState_ind);
	Define(30, &sellerUserFlags);
	Define(31, (char *)highBidderUserId, sizeof(highBidderUserId),
			   &highBidderUserId_ind);
	Define(32, &highBidderUserState, 
			   &highBidderUserState_ind);
	Define(33, &highBidderUserFlags);
	Define(34, (char *)categoryName, sizeof(categoryName),
			   &categoryName_ind);
//	Define(33, &sellerFeedbackScore, 
//			   &sellerFeedbackScore_ind);
//	Define(34, &highBidderFeedbackScore,
//			   &highBidderFeedbackScore_ind);

	// Fetch
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
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

//	if (sellerFeedbackScore_ind == -1)
//		sellerFeedbackScore = INT_MIN;

//	if (highBidderFeedbackScore_ind == -1)
//		highBidderFeedbackScore	= INT_MIN;


	// Fill in the item
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
			   INT_MIN,
			   INT_MIN,
			   0,
			   0,
			   last_modified_time,
			   NULL,
			   NULL,
			   password);

	// Close the nose
	Close(&mpCDAOneShot);
	SetStatement(NULL);


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
			GetItemDescArc(id, pItem);
		}
	}
	else
		pItem->SetDesc(marketplace,
					   id, 
					   NULL);

	return true;
}

//
// Get Archived Bids
// need to ensure no duplications
// 
/* inna please keep until next release 
static char *SQL_GetBidsArc =
 "select	distinct user_id,					\
			type,								\
			quantity,							\
			amount,								\
			value,								\
			TO_CHAR(created,					\
					'YYYY-MM-DD HH24:MI:SS'),	\
			reason								\
  from ebay_bids_arc							\
  where	marketplace = :marketplace				\
  and	item_id = :item_id";
*/
static char *SQL_GetBidsArc =
 "select	distinct user_id,					\
			type,								\
			quantity,							\
			amount,								\
			value,								\
			TO_CHAR(created,					\
					'YYYY-MM-DD HH24:MI:SS'),	\
			reason								\
  from ebay_bids_arc							\
  where	item_id = :item_id";

#define ORA_BIDSARC_ARRAYSIZE	20

void clsDatabaseOracle::GetBidsArc(MarketPlaceId /* marketplace */,
								int item_id,
								BidVector *pBids)
{
	int		user[ORA_BIDSARC_ARRAYSIZE];
	int		quantity[ORA_BIDSARC_ARRAYSIZE];
	float	amount[ORA_BIDSARC_ARRAYSIZE];
	float	value[ORA_BIDSARC_ARRAYSIZE];
	int		action[ORA_BIDSARC_ARRAYSIZE];
	char	created[ORA_BIDSARC_ARRAYSIZE][64];
	char	reason[ORA_BIDSARC_ARRAYSIZE][256];

	int		rowsFetched;
	int		rc;
	int		i, n;

	time_t	createTime;

	// We'll use this bid pointer over and over,
	//
	clsBid	*pBid = NULL;
	char	*pReason;


	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)

	OpenAndParse(&mpCDAOneShot,
				 SQL_GetBidsArc);

	// Bind the input variable
	//ind(":marketplace", (int *)&marketplace);
	Bind(":item_id", &item_id);

	// Bind those happy little output variables.
	Define(1, user);
	Define(2, action);
	Define(3, quantity);
	Define(4, amount);
	Define(5, value);
	Define(6, created[0], sizeof(created[0]));
	Define(7, reason[0], sizeof(reason[0]));

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}

	// Fetch until we retch (should use array 
	// fetch here!)
	rowsFetched	= 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_BIDSARC_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_BIDSARC_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			// Convert the time
			ORACLE_DATEToTime(created[i], &createTime);

			if (reason[i] != NULL)
			{
				pReason	= new char[strlen(reason[i]) + 1];
				strcpy(pReason, reason[i]);
			}
			else
				pReason	= reason[i];

			pBid	= new clsBid(createTime,
								 (BidActionEnum)action[i],
								 user[i],
								 amount[i],
								 quantity[i],
								 value[i],
								 pReason);

			pBids->push_back(pBid);

			if (reason[i] != NULL)
			{
				delete	[] pReason;
			}
		}
	} while (!CheckForNoRowsFound());

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

static char *SQL_GetThisBidsFromEnded =
"	select count(*) from ebay_bids_ended	"
"	where	marketplace = :marketplace	"
"	and item_id = :item_id";

static char *SQL_CopyBidsFromArcToEndedI =
"	insert into ";

static char *SQL_CopyBidsFromArcToEndedII =
"	select * from ebay_bids_ended	"
"	where	marketplace = :marketplace	"
"	and item_id = :item_id";

static char *SQL_DeleteBidsFromEnded =
"	delete from ebay_bids_ended	"
"	where	marketplace = :marketplace	"
"	and item_id = :item_id";

static char *SQL_CopyItemsToArcI =
"	insert into ";

static char *SQL_CopyItemsToArcII =
"	select * from ebay_items_ended	"
"	where	marketplace = :marketplace	"
"	and		id = :item_id";

static const char *SQL_DeleteItemFromEnded =
 "delete from ebay_items_ended				\
	where	marketplace = :marketplace	\
	and		id = :id";

static const char *SQL_DeleteItemDescEnded =
 "delete from ebay_item_desc_ended				\
	where	marketplace = :marketplace	\
	and		id = :id";


void clsDatabaseOracle::ArchiveItem(clsItem *pItem)
{
	int			marketplace;
	int			item_id;
	int			count;
	marketplace		= pItem->GetMarketPlaceId();
	item_id				= pItem->GetId();
	char		*SQL_CurrentArchiveStatement;
	char		*tableName;
	char *month;
	char *year;
	// check if pItem has its description, if not
	// get it and archive it; otherwise, just archive it.
	if (pItem->GetDescription() == 0)
		GetItemDescription(marketplace, pItem->GetId(), pItem);

	// put description to archive
	AddItemDescArcByMonth(pItem);

	//delete from ebay_item_desc
	OpenAndParse(&mpCDADeleteItemDescEnded, SQL_DeleteItemDescEnded);
	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &item_id);
	// Just do it!
	Execute();
	Close(&mpCDADeleteItemDescEnded);
	SetStatement(NULL);
	Commit();
	
	month = new char[3];
	year = new char[5];
	GetMonthYear(pItem->GetEndTime(), month, year);
	//check if ebay_bids has a record:
	OpenAndParse(&mpCDAGetThisBidsFromEnded,SQL_GetThisBidsFromEnded);
	// Bind the input variables
	Define(1, &count);
	Bind(":marketplace", &marketplace);
	Bind(":item_id", &item_id);
	// Execute and Fetch
	ExecuteAndFetch();
	if (!CheckForNoRowsFound() && count != 0)

	{
		Close(&mpCDAGetThisBidsFromEnded);
		SetStatement(NULL);
		//move to ebay_bids_ended
		tableName = new char[31];
		SQL_CurrentArchiveStatement =  new char[1024];
		sprintf(tableName, "ebay_bids_arc_%s%s", month, year);

		CombineSQLStatement( SQL_CopyBidsFromArcToEndedI, tableName, 
			SQL_CopyBidsFromArcToEndedII, SQL_CurrentArchiveStatement);
		delete [] tableName;
//		OpenAndParse(&mpCDAOneShot, SQL_CopyBidsFromArcToEnded);
		OpenAndParse(&mpCDAOneShot, SQL_CurrentArchiveStatement);
		// Ok, let's do some binds
		Bind(":marketplace", &marketplace);
		Bind(":item_id", &item_id);
		// Do it
		Execute();
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		delete [] SQL_CurrentArchiveStatement;
		//delete from ebay_bids
		OpenAndParse(&mpCDADeleteBidsFromEnded, SQL_DeleteBidsFromEnded);
		// Ok, let's do some binds	
		Bind(":marketplace", &marketplace);
		Bind(":item_id", &item_id);
		// Do it
		Execute();
		Commit();
		Close(&mpCDADeleteBidsFromEnded);
		SetStatement(NULL);
	}
	else
	{
		Close(&mpCDAGetThisBidsFromEnded);
		SetStatement(NULL);
	}

	tableName = new char[31];
	SQL_CurrentArchiveStatement =  new char[1024];
	sprintf(tableName, "ebay_items_arc_%s%s", month, year);
	CombineSQLStatement(SQL_CopyItemsToArcI, tableName, 
		SQL_CopyItemsToArcII, SQL_CurrentArchiveStatement);
	delete [] tableName;
	//move to ebay_items_ended
//	OpenAndParse(&mpCDAOneShot, SQL_CopyItemsToArc);
	OpenAndParse(&mpCDAOneShot, SQL_CurrentArchiveStatement);
	delete [] SQL_CurrentArchiveStatement;
	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":item_id", &item_id);
	// Do it
	Execute();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	//delete from ebay_items
	OpenAndParse(&mpCDADeleteItem, SQL_DeleteItemFromEnded);
	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &item_id);
	// Just do it!
	Execute();
	Close(&mpCDADeleteItem);
	SetStatement(NULL);

	//commit transaction for delete item
	Commit();
	return;
}//ended

static char *SQL_CopyBidsFromEnded =
"	insert into ebay_bids_arc"
"	select * from ebay_bids_ended	"
"	where	marketplace = :marketplace	"
"	and item_id = :item_id";

static char *SQL_CopyItemsFromEnded =
"	insert into ebay_items_arc"
"	select * from ebay_items_ended	"
"	where	marketplace = :marketplace	"
"	and		id = :item_id";

void clsDatabaseOracle::RemoveItemFromEnded(clsItem *pItem)
{
	int			marketplace;
	int			item_id;
	int			count;

	marketplace		= pItem->GetMarketPlaceId();
	item_id				= pItem->GetId();
	// check if pItem has its description, if not
	// get it and archive it; otherwise, just archive it.
	if (pItem->GetDescription() == 0)
		GetItemDescription(marketplace, pItem->GetId(), pItem);

	// put to archive
	AddItemDescArc(pItem);

	//check if ebay_bids has a record:
	OpenAndParse(&mpCDAGetThisBidsFromEnded,SQL_GetThisBidsFromEnded);
	// Bind the input variables
	Define(1, &count);
	Bind(":marketplace", &marketplace);
	Bind(":item_id", &item_id);
	// Execute and Fetch
	ExecuteAndFetch();
	if (!CheckForNoRowsFound() && count != 0)
	{
		Close(&mpCDAGetThisBidsFromEnded);
		SetStatement(NULL);
		//move to ebay_bids_arc
		OpenAndParse(&mpCDAOneShot, SQL_CopyBidsFromEnded);
		// Ok, let's do some binds
		Bind(":marketplace", &marketplace);
		Bind(":item_id", &item_id);
		// Do it
		Execute();
		Close(&mpCDAOneShot);
		SetStatement(NULL);

		//delete from ebay_bids
		OpenAndParse(&mpCDAOneShot, SQL_DeleteBidsFromEnded);
		// Ok, let's do some binds	
		Bind(":marketplace", &marketplace);
		Bind(":item_id", &item_id);
		// Do it
		Execute();
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		Commit();
	}
	Close(&mpCDAGetThisBidsFromEnded);
	SetStatement(NULL);

	//move to ebay_items_arc
	OpenAndParse(&mpCDAOneShot, SQL_CopyItemsFromEnded);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":item_id", &item_id);
	// Do it
	Execute();
	if (CheckForNoRowsUpdated())
		Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	Commit();

	//delete from ebay_items
	DeleteItem(marketplace, item_id, true /*ended*/);
	return;
}//inna-end

static char *SQL_GetThisBidsEnded =
"	select count(*) from ebay_bids	"
"	where	marketplace = :marketplace	"
"	and item_id = :item_id";

static char *SQL_CopyBidsToEnded =
"	insert into ebay_bids_ended "
"	select * from ebay_bids	"
"	where	marketplace = :marketplace	"
"	and item_id = :item_id";

static char *SQL_DeleteBidsEnded =
"	delete from ebay_bids	"
"	where	marketplace = :marketplace	"
"	and item_id = :item_id";

static char *SQL_CopyItemsToEnded =
"	insert into ebay_items_ended "
"	select * from ebay_items	"
"	where	marketplace = :marketplace	"
"	and		id = :item_id";


void clsDatabaseOracle::EndItem(clsItem *pItem)
{
	int			marketplace;
	int			item_id;
	int			count;

	marketplace		= pItem->GetMarketPlaceId();
	item_id				= pItem->GetId();

	// check if pItem has its description, if not
	// get it and archive it; otherwise, just archive it.
	if (pItem->GetDescription() == 0)
		GetItemDescription(marketplace, pItem->GetId(), pItem);

	// put description to ended
	AddItemDescEnded(pItem);

	//check if ebay_bids has a record:
	OpenAndParse(&mpCDAGetThisBidsEnded,SQL_GetThisBidsEnded);
	// Bind the input variables
	Define(1, &count);
	Bind(":marketplace", &marketplace);
	Bind(":item_id", &item_id);
	// Execute and Fetch
	ExecuteAndFetch();
	if (!CheckForNoRowsFound() && count != 0)
	{
		Close(&mpCDAGetThisBidsEnded);
		SetStatement(NULL);
		//move to ebay_bids_ended
		OpenAndParse(&mpCDACopyBidsToEnded, SQL_CopyBidsToEnded);
		// Ok, let's do some binds
		Bind(":marketplace", &marketplace);
		Bind(":item_id", &item_id);
		// Do it
		Execute();
		Close(&mpCDACopyBidsToEnded);
		SetStatement(NULL);

		//delete from ebay_bids
		OpenAndParse(&mpCDADeleteBidsEnded, SQL_DeleteBidsEnded);
		// Ok, let's do some binds	
		Bind(":marketplace", &marketplace);
		Bind(":item_id", &item_id);
		// Do it
		Execute();
		Commit();
		Close(&mpCDADeleteBidsEnded);
		SetStatement(NULL);
	}
	else
	{
		Close(&mpCDAGetThisBidsEnded);
		SetStatement(NULL);
	}

	//move to ebay_items_ended
	OpenAndParse(&mpCDACopyItemsToEnded, SQL_CopyItemsToEnded);
	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":item_id", &item_id);
	// Do it
	Execute();
	Close(&mpCDACopyItemsToEnded);
	SetStatement(NULL);
	Commit();
	//delete from ebay_item_info
	// no more item_info as of e118 DeleteFromItemInfo(pItem);

	//This will delete both the Item and it's description.
	//  The last 2 params are for ended and blocked tables 
	DeleteItem(marketplace, item_id, false, false);

	//commit transaction for delete item
	Commit();


	return;
}//ended
// inna 
//  Remove item from ebay tables and move it to ebay archive tables
//
/*static char *SQL_CopyItemInfo =
"	insert into ebay_item_info_arc"
"	select * from ebay_item_info	"
"	where	marketplace = :marketplace	"
"	and		id = :item_id"; 
*/
static char *SQL_GetThisBids =
"	select count(*) from ebay_bids	"
"	where	marketplace = :marketplace	"
"	and item_id = :item_id";

static char *SQL_CopyBids =
"	insert into ebay_bids_arc"
"	select * from ebay_bids	"
"	where	marketplace = :marketplace	"
"	and item_id = :item_id";

static char *SQL_DeleteBids =
"	delete from ebay_bids	"
"	where	marketplace = :marketplace	"
"	and item_id = :item_id";

static char *SQL_CopyItems =
"	insert into ebay_items_arc"
"	select * from ebay_items	"
"	where	marketplace = :marketplace	"
"	and		id = :item_id";

static char *SQL_DeleteItemInIdx =
"	delete from ebay_items_to_archive	"
"	where	id = :item_id"; 

void clsDatabaseOracle::RemoveItem(clsItem *pItem)
{
	int			marketplace;
	int			item_id;
	int			count;

	marketplace		= pItem->GetMarketPlaceId();
	item_id				= pItem->GetId();
	if (pItem->GetEnded())
	{
		RemoveItemFromEnded(pItem);
		return;
	}
	//move to ebay_items_info_arc no longer exists

	//delete from ebay_items_info
	// no more as of e118 DeleteFromItemInfo(pItem);

	// check if pItem has its description, if not
	// get it and archive it; otherwise, just archive it.
	if (pItem->GetDescription() == 0)
		GetItemDescription(marketplace, pItem->GetId(), pItem);

	// put to archive
	AddItemDescArc(pItem);

	//check if ebay_bids has a record:
	OpenAndParse(&mpCDAGetThisBids,SQL_GetThisBids);
	// Bind the input variables
	Define(1, &count);
	Bind(":marketplace", &marketplace);
	Bind(":item_id", &item_id);
	// Execute and Fetch
	ExecuteAndFetch();
	if (!CheckForNoRowsFound() && count != 0)

	{
		Close(&mpCDAGetThisBids);
		SetStatement(NULL);
		//move to ebay_bids_arc
		OpenAndParse(&mpCDACopyBids, SQL_CopyBids);
		// Ok, let's do some binds
		Bind(":marketplace", &marketplace);
		Bind(":item_id", &item_id);
		// Do it
		Execute();
		Close(&mpCDACopyBids);
		SetStatement(NULL);

		//delete from ebay_bids
		OpenAndParse(&mpCDADeleteBids, SQL_DeleteBids);
		// Ok, let's do some binds	
		Bind(":marketplace", &marketplace);
		Bind(":item_id", &item_id);
		// Do it
		Execute();
		Commit();
		Close(&mpCDADeleteBids);
		SetStatement(NULL);
	}
	Close(&mpCDAGetThisBids);
	SetStatement(NULL);

	//move to ebay_items_arc
	OpenAndParse(&mpCDACopyItems, SQL_CopyItems);
	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":item_id", &item_id);
	// Do it
	Execute();
	Close(&mpCDACopyItems);
	SetStatement(NULL);
	Commit();
	//This will delete both the Item and it's description.
	//  The last 2 params are for ended and blocked tables 
	DeleteItem(marketplace, item_id, false, false);

	//commit transaction for delete item
	Commit();

	return;
}//inna-end

/* no more item_info as of e118 static char *SQL_DeleteItemInfo =
"	delete from ebay_item_info	"
"	where	marketplace = :marketplace	"
"	and		id = :item_id"; 


void clsDatabaseOracle::DeleteFromItemInfo(clsItem *pItem)
{
	int			marketplace;
	int			item_id;
	item_id				= pItem->GetId();
	marketplace			= pItem->GetMarketPlaceId();

	OpenAndParse(&mpCDADeleteItemInfo, SQL_DeleteItemInfo);
	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":item_id", &item_id);
	// Do it
	Execute();
	if (CheckForNoRowsUpdated())
		Commit();
	Close(&mpCDADeleteItemInfo);
	SetStatement(NULL);
	Commit();
	return;
}
*/
