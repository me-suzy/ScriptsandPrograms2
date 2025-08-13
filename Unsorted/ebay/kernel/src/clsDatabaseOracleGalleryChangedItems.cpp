/*	$Id: clsDatabaseOracleGalleryChangedItems.cpp,v 1.2 1999/02/21 02:47:22 josh Exp $	*/
//
//	File:	clsDatabaseOracleGalleryChangedItemd.cc
//
//	Class:	clsDatabaseOracleGalleryChangedItemd
//

#include "eBayKernel.h"
//#include "clsDBh"
#include "clsGalleryChangedItem.h"

// Bitwise reverse of an integer
// This is used to reverse the sequence ID before inserting into the DB
// Since we index on the sequence ID, this helps keep our index tree balanced

int ReverseInt(int i)
{
#if 0
	unsigned int j = 0;
	int bits = sizeof(int) * 8;

	for (int n = 0; n < bits; n++)
	{
		j = j >> 1;

		if (i < 0)
		{
			int k = 1;
			k = k << (bits - 1);
			j |= k;
		}

		i = i << 1;

	}

	return (int) j;
#endif
	return i;
}


//
// EBAY_GALLERY_CHANGED_ITEM
//

#if 0
static const char *SQL_GetGalleryChangedItem =
"	select	id,	"
"			sequence_id, "
"			url, "
"			state, "
"			TO_CHAR(start_date, 'YYYY-MM-DD HH24:MI:SS'), "
"			TO_CHAR(end_date, 'YYYY-MM-DD HH24:MI:SS'), "
"			attempts, "
"			TO_CHAR(last_attempt, 'YYYY-MM-DD HH24:MI:SS') "
"	from ebay_gallery_changed_items "
"	where	sequence_id = :sequence_id";
#endif

static const char *SQL_GetGalleryChangedItem =
"	select	c.id,	"
"			c.sequence_id, "
"			c.url, "
"			c.state, "
"			TO_CHAR(c.start_date, 'YYYY-MM-DD HH24:MI:SS'), "
"			TO_CHAR(c.end_date, 'YYYY-MM-DD HH24:MI:SS'), "
"			c.attempts, "
"			TO_CHAR(c.last_attempt, 'YYYY-MM-DD HH24:MI:SS'), "
"			i.gallery_type "
"	from ebay_items i, "
"			ebay_gallery_changed_items c "
"	where	c.sequence_id = :sequence_id"
"	and		i.id = c.id ";

bool clsDatabaseOracle::GetGalleryChangedItem(int sequenceID, clsGalleryChangedItem& item)
{
	char startDateString[32];
	char endDateString[32];
	char lastAttemptDateString[32];
	int galleryType;
	sb2 galleryType_ind;

	item.mURL[0] = '\0';

	OpenAndParse(&mpCDAGetGalleryChangedItem, SQL_GetGalleryChangedItem);

	// Reverse the sequence ID
	int reversedSequenceID = ReverseInt(sequenceID);

	// Bind the input variable
	Bind(":sequence_id", &reversedSequenceID);

	// Bind those happy little output variables.
	Define(1, (int *)&item.mID);
	Define(2, (int *)&item.mSequenceID);
	Define(3, (char *)item.mURL, sizeof(item.mURL));
	Define(4, (int *)&item.mState);
	Define(5, (char *)startDateString, sizeof(startDateString));
	Define(6, (char *)endDateString, sizeof(endDateString));
	Define(7, (int *)&item.mAttempts);
	Define(8, (char *)lastAttemptDateString, sizeof(lastAttemptDateString));
	Define(9, (int *)&galleryType, &galleryType_ind);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetGalleryChangedItem);
		SetStatement(NULL);
		return false;
	}

	Close(&mpCDAGetGalleryChangedItem);
	SetStatement(NULL);
	
	// Time Conversions
	ORACLE_DATEToTime(startDateString, &item.mStartTime);
	ORACLE_DATEToTime(endDateString, &item.mEndTime);
	ORACLE_DATEToTime(lastAttemptDateString, &item.mLastAttempt);

	// Reverse the sequence ID
	item.mSequenceID = ReverseInt(item.mSequenceID);

	if (galleryType_ind == -1)
		galleryType = NoneGallery;

	item.mGalleryType = static_cast<GalleryTypeEnum>(galleryType);

	return true;
}

static const char *SQL_AppendGalleryChangedItem = (char *)
"insert into ebay_gallery_changed_items "
" (	"
"	id,	"
"	sequence_id, "
"	url, "
"	state, "
"	start_date, "
"	end_date, "
"	attempts, "
"	last_attempt "
" ) "
" values "
" ( "
"	:id, "
"	:sequence_id, "
"	:url, "
"	:state, "
"	TO_DATE(:start_date, 'YYYY-MM-DD HH24:MI:SS'), "
"	TO_DATE(:end_date, 'YYYY-MM-DD HH24:MI:SS'), "
"	:attempts, "
"	TO_DATE(:last_attempt, 'YYYY-MM-DD HH24:MI:SS') "
" ) ";


bool clsDatabaseOracle::AppendGalleryChangedItem(clsGalleryChangedItem& item)
{
	char startDateString[32] = { 0 };
	char endDateString[32] = { 0 };
	char lastAttemptDateString[32] = { 0 };

	TM_STRUCTToORACLE_DATE(localtime(&item.mStartTime), startDateString);
	TM_STRUCTToORACLE_DATE(localtime(&item.mEndTime), endDateString);
	TM_STRUCTToORACLE_DATE(localtime(&item.mLastAttempt), lastAttemptDateString);

	OpenAndParse(&mpCDAAppendGalleryChangedItem, SQL_AppendGalleryChangedItem);

	// Reverse the sequence ID
	int reversedSequenceID = ReverseInt(item.mSequenceID);

	// Bind the input variable
	Bind(":id", &item.mID);
	Bind(":sequence_id", &reversedSequenceID);
	Bind(":url", item.mURL);
	Bind(":state", &item.mState);
	Bind(":start_date", (char*) startDateString);
	Bind(":end_date", (char*) endDateString);
	Bind(":attempts", &item.mAttempts);
	Bind(":last_attempt", (char*) lastAttemptDateString);

	Execute();
	Commit();
	Close(&mpCDAAppendGalleryChangedItem);
	SetStatement(NULL);

	return true;
}

static const char *SQL_DeleteGalleryChangedItem =
" delete from ebay_gallery_changed_items "
"	where	sequence_id = :sequence_id ";

bool clsDatabaseOracle::DeleteGalleryChangedItem(int sequenceID)
{
	OpenAndParse(&mpCDADeleteGalleryChangedItem, SQL_DeleteGalleryChangedItem);

	// Reverse the sequence ID
	sequenceID = ReverseInt(sequenceID);

	// Bind the input variable
	Bind(":sequence_id", &sequenceID);

	Execute();

	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDADeleteGalleryChangedItem);
		SetStatement(NULL);
		return false;
	}

	Commit();
	Close(&mpCDADeleteGalleryChangedItem);
	SetStatement(NULL);

	return true;
}

static const char *SQL_SetGalleryChangedItemState = (char *)
"update ebay_gallery_changed_items "
" set state = :state, "
"	last_attempt = TO_DATE(:last_attempt, 'YYYY-MM-DD HH24:MI:SS'), "
"	sequence_id = :newSequence, "
"	attempts = :attempts "
" where sequence_id = :sequence_id" ;

bool clsDatabaseOracle::SetGalleryChangedItemState(int sequenceID, int newSequenceID, int attempts, int state)
{
	char lastAttemptDateString[32] = { 0 };

	OpenAndParse(&mpCDASetGalleryChangedItemState, SQL_SetGalleryChangedItemState);

	time_t now = time(NULL);

	TM_STRUCTToORACLE_DATE(localtime(&now), lastAttemptDateString);

	// Reverse the sequence ID
	sequenceID = ReverseInt(sequenceID);
	newSequenceID = ReverseInt(newSequenceID);

	// Bind the input variable
	Bind(":sequence_id", &sequenceID);
	Bind(":newSequence", &newSequenceID);
	Bind(":state", &state);
	Bind(":attempts", &attempts);
	Bind(":last_attempt", (char*) lastAttemptDateString);

	Execute();

	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDASetGalleryChangedItemState);
		SetStatement(NULL);
		return false;
	}

	Commit();
	Close(&mpCDASetGalleryChangedItemState);
	SetStatement(NULL);

	return true;
}

static const char *SQL_GetGallerySequenceRange =
"	select	min(sequence_id),	"
"			max(sequence_id) "
"	from ebay_gallery_changed_items ";

bool clsDatabaseOracle::GetGallerySequenceRange(int& minSequence, int& maxSequence)
{
	OpenAndParse(&mpCDAGetGallerySequenceRange, SQL_GetGallerySequenceRange);

	minSequence = 0;
	maxSequence = 0;

	// Bind those happy little output variables.
	Define(1, (int *)&minSequence);
	Define(2, (int *)&maxSequence);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetGallerySequenceRange);
		SetStatement(NULL);
		return false;
	}

	Close(&mpCDAGetGallerySequenceRange);
	SetStatement(NULL);
	
	return true;
}

//
// EBAY_GALLERY_SEQUENCE
//


static const char *SQL_GetCurrentGallerySequence =
 "select ebay_gallery_sequence.currval from dual";

int clsDatabaseOracle::GetCurrentGallerySequence()
{
	int currentID;

	OpenAndParse(&mpCDAGetCurrentGallerySequence, SQL_GetCurrentGallerySequence);
	Define(1, &currentID);

	ExecuteAndFetch();
	Close(&mpCDAGetCurrentGallerySequence);
	SetStatement(NULL);

	return currentID;
}

static const char *SQL_GetNextGallerySequence =
 "select ebay_gallery_sequence.nextval from dual";

int clsDatabaseOracle::GetNextGallerySequence()
{
	int nextID;

	OpenAndParse(&mpCDAGetNextGallerySequence, SQL_GetNextGallerySequence);
	Define(1, &nextID);

	ExecuteAndFetch();
	Close(&mpCDAGetNextGallerySequence);
	SetStatement(NULL);

	return nextID;
}

//
// EBAY_GALLERY_READ_SEQUENCE
//

static const char *SQL_GetCurrentGalleryReadSequence =
 "select ebay_gallery_read_sequence.currval from dual";

int clsDatabaseOracle::GetCurrentGalleryReadSequence()
{
	int currentID;

	OpenAndParse(&mpCDAGetCurrentGalleryReadSequence, SQL_GetCurrentGalleryReadSequence);
	Define(1, &currentID);

	ExecuteAndFetch();
	Close(&mpCDAGetCurrentGalleryReadSequence);
	SetStatement(NULL);

	return currentID;
}

static const char *SQL_GetNextGalleryReadSequence =
 "select ebay_gallery_read_sequence.nextval from dual";

int clsDatabaseOracle::GetNextGalleryReadSequence()
{
	int nextID;

	OpenAndParse(&mpCDAGetNextGalleryReadSequence, SQL_GetNextGalleryReadSequence);
	Define(1, &nextID);

	ExecuteAndFetch();
	Close(&mpCDAGetNextGalleryReadSequence);
	SetStatement(NULL);

	return nextID;
}

//
// EBAY_ITEMS
//

static const char *SQL_SetItemGalleryInfo = (char *)
"update ebay_items "
" set gallery_state = :state, "
"	gallery_url = :url, "
"	gallery_thumb_x_size = :xSize, "
"	gallery_thumb_y_size = :ySize "
" where id = :id" ;

bool clsDatabaseOracle::SetItemGalleryInfo(int itemID, clsItemGalleryInfo& info)
{
	OpenAndParse(&mpCDASetItemGalleryInfo, SQL_SetItemGalleryInfo);

	// Bind the input variable
	Bind(":state", &info.mState);
	Bind(":url", info.mURL);
	Bind(":xSize", &info.mXSize);
	Bind(":ySize", &info.mYSize);
	Bind(":id", &itemID);

	Execute();
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDASetItemGalleryInfo);
		SetStatement(NULL);
		return false;
	}

	Commit();
	Close(&mpCDASetItemGalleryInfo);
	SetStatement(NULL);
	
	return true;
}

static const char *SQL_GetItemGalleryInfo =
"	select	gallery_state,	"
"			gallery_url, "
"			gallery_thumb_x_size, "
"			gallery_thumb_y_size "
"	from ebay_items "
"	where	id = :id";

bool clsDatabaseOracle::GetItemGalleryInfo(int itemID, clsItemGalleryInfo& info)
{
#if 0
	sb2 state_ind;
	sb2 url_ind;
	sb2 xSize_ind;
	sb2 ySize_ind;
#endif

	info.mState = 0;
	info.mURL[0] = '\0';
	info.mXSize = 0;
	info.mYSize = 0;

	OpenAndParse(&mpCDAGetItemGalleryInfo, SQL_GetItemGalleryInfo);

	// Bind the input variable
	Bind(":id", &itemID);

	// Bind those happy little output variables.
	Define(1, (int *)&info.mState);
	Define(2, (char *)info.mURL, sizeof(info.mURL));
	Define(3, (int *)&info.mXSize);
	Define(4, (int *)&info.mYSize);

	ExecuteAndFetch();
	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetItemGalleryInfo);
		SetStatement(NULL);
		return false;
	}

	Close(&mpCDAGetItemGalleryInfo);
	SetStatement(NULL);

	return true;
}





