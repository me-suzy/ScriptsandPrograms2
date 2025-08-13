/*	$Id: RebuildListType.h,v 1.3 1999/02/21 02:23:43 josh Exp $	*/
//
//	File:	RebuildListType.h
//
//	Class:	RebuildListType
//
//	Author:	Wen Wen
//
//	Function:
//			type definition for rebuild-list
//
// Modifications:
//				- 07/07/97	Wen - Created
//

#ifndef REBUILDLIST_TYPE
#define REBUILDLIST_TYPE

#ifndef __MAIN__
#define GLOBALDEF extern
#else
#define GLOBALDEF
#endif


#define ONE_DAY	24*60*60 // 24 hours
#define THREE_HOURS	5*60*60

#define LISTING_FILE_PATH		"./listings"

#define LIST_HEADING		"Current Auctions"
#define NEWTODAY_HEADING	"Today's New Items"
#define ENDTODAY_HEADING	"Items Ending Today"
#define COMPLETED_HEADING	"Auction Items Completed on "
#define GOING_HEADING		"Items Ending in 5 Hours"
 
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
	PAGE_LINK			= 9,
	SPONSOR				= 10,
	END_PORTION			= 11		// this has to be the last one
} Portion;

#endif // REBUILDLIST_TYPE
