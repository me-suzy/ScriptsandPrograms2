/*	$Id: BuildAdvancedSearchPageType.h,v 1.2 1999/02/21 02:21:16 josh Exp $	*/
//
//	File:	BuildAdvancedSearchPageType.h
//
//	Class:	BuildAdvancedSearchPageType
//
//	Author:	Wen Wen
//
//	Function:
//			type definition for rebuild-list
//
// Modifications:
//				- 07/07/97	Wen - Created
//

#ifndef BuildAdvancedSearchPage_TYPE
#define BuildAdvancedSearchPage_TYPE

#ifndef __MAIN__
#define GLOBALDEF extern
#else
#define GLOBALDEF
#endif


#define ONE_DAY	24*60*60 // 24 hours
#define LISTING_FILE_PATH		"./listings"

#define LIST_HEADING		"<h2>AuctionWeb Listings</h2>"
#define NEWTODAY_HEADING	"<h2>AuctionWeb New Listings</h2>"
#define ENDTODAY_HEADING	"<h2>AuctionWeb Listings Ending Today</h2>"
#define COMPLETED_HEADING	"<h2>AuctionWeb Completed Auctions</h2>"

GLOBALDEF char TEMP_LISTING_FILE_PATH[256];

typedef enum
{
	NORMAL		= 0,
	FEATURED	= 1,
	HOT			= 2
} ItemType ;

typedef enum 
{
	HEADER				= 0,
	TRAILER				= 1,
	FOCAL_LINK			= 2,
	CATEGORY_NAVIGATOR	= 3,
	FEATURE_ITEM		= 4,
	CATEGORY			= 5,
	ITEM_LIST			= 6,
	HOT_ITEM			= 7,
	TIME				= 8,
	SHALLOW_NAVIGATOR	= 9,
	SHALLOW_CATEGORY	= 10,
	END_PORTION			= 11		// this has to be the last
} Portion;

#endif // BuildAdvancedSearchPage_TYPE
