/*	$Id: clsDatabaseODBC.cpp,v 1.2 1998/06/23 04:29:52 josh Exp $	*/
//
//	File:	clsDatabaseODBC.cc
//
//	Class:	clsDatabaseODBC
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 02/09/97 michael	- Created
//

#include "eBayTypes.h"
#include "eBayDebug.h"
#include "clsApp.h"
#include "clsDatabase.h"
#include "clsDatabaseODBC.h"
#include "clsLog.h"
#include "clsFeedback.h"
#include "clsItem.h"

#include <string.h>
#include <stdio.h>
#include <fcntl.h>
#include <errno.h>
#include <time.h>

//
// SetStatement
//
// Used when we're not using a persistant
// cursor
//
void clsDatabaseODBC::SetStatement(HSTMT pStmt)
{
	if (pStmt)
	{
		mCurrentHStmt	= pStmt;
	}
	else
	{
		mCurrentHStmt	= NULL;
	}
	return;
}

//
// Check routines
//

void clsDatabaseODBC::Check(RETCODE rc)
{
	RETCODE		myRc;
	HENV		pEnv;
	HDBC		pDbc;
	

	if (rc != SQL_SUCCESS && rc != SQL_SUCCESS_WITH_INFO)
	{
		if (mHaveEnv)
			pEnv	= mHEnv;
		else
			pEnv	= SQL_NULL_HENV;

		if (mHaveDbc)
			pDbc	= mHDbc;
		else
			pDbc	= SQL_NULL_HDBC;


		myRc	= SQLError(pEnv, pDbc, mCurrentHStmt,
						   &mErrorState,
						   &mErrorNativeError,
						   mErrorMsg,
						   sizeof(mErrorMsg),
						   &mErrorMsgSize);
		// Throw here
		return;
	}
	return;
}

// 
// Time conversion
//
void TimeToTIMESTAMP_STRUCT(time_t theTime,
							 TIMESTAMP_STRUCT *pTheStruct)
{
	struct tm	*pLocalTime;

	pLocalTime	= localtime(&theTime);
	
	pTheStruct->year		= pLocalTime->tm_year;
	pTheStruct->month		= pLocalTime->tm_mon + 1;
	pTheStruct->day			= pLocalTime->tm_mday;
	pTheStruct->hour		= pLocalTime->tm_hour;
	pTheStruct->minute		= pLocalTime->tm_min;
	pTheStruct->second		= pLocalTime->tm_sec;
	pTheStruct->fraction	= 0;

	return;
}

void TIMESTAMP_STRUCTToTime(TIMESTAMP_STRUCT *pTheStruct,
							time_t *pTheTime)
{
	struct tm	localTime;
	
	localTime.tm_year		= pTheStruct->year;
	localTime.tm_mon		= pTheStruct->month - 1;
	localTime.tm_mday		= pTheStruct->day;
	localTime.tm_hour		= pTheStruct->hour;
	localTime.tm_min		= pTheStruct->minute;
	localTime.tm_sec		= pTheStruct->second;

	*pTheTime	= mktime(&localTime);

	return;
}



//
// Common Paramter Bind routines
//
void clsDatabaseODBC::BindSDWORDToINT(int position,
									  SDWORD *pVar)
{
	RETCODE	rc;

	rc = SQLBindParameter(mCurrentHStmt,
						  position,
						  SQL_PARAM_INPUT,
						  SQL_C_LONG,
						  SQL_INTEGER,
						  0,
						  0,
						  pVar,
						  0,
						  NULL);
	Check(rc);

	return;
}


void clsDatabaseODBC::BindUCHARToVARCHAR(int position,
										 UCHAR *pVar,
										 SDWORD *pPcbValue)
{
	RETCODE	rc;
	SDWORD	it	= SQL_NTS;

	rc = SQLBindParameter(mCurrentHStmt,
						  position,
						  SQL_PARAM_INPUT,
						  SQL_C_CHAR,
						  SQL_VARCHAR,
						  strlen((char *)pVar),
						  0,
						  pVar,
						  0,
						  pPcbValue);
	Check(rc);

	return;
}

void clsDatabaseODBC::BindTIMESTAMPToTIMESTAMP(
										int position,
										 TIMESTAMP_STRUCT *pVar
											  )
{
	RETCODE	rc;

	rc = SQLBindParameter(mCurrentHStmt,
						  position,
						  SQL_PARAM_INPUT,
						  SQL_C_TIMESTAMP,
						  SQL_TIMESTAMP,
						  0,
						  0,
						  pVar,
						  0,
						  NULL);
	Check(rc);

	return;
}

void clsDatabaseODBC::BindSFLOATToFLOAT(int position,
										SFLOAT *pVar)
{
	RETCODE	rc;

	rc = SQLBindParameter(mCurrentHStmt,
						  position,
						  SQL_PARAM_INPUT,
						  SQL_C_FLOAT,
						  SQL_FLOAT,
						  15,
						  0,
						  pVar,
						  0,
						  NULL);
	Check(rc);

	return;
}

void clsDatabaseODBC::BindUCHARToLONGVARBINARY(int position,
											   UCHAR *pVar,
											   SDWORD *pPcbValue)
{
	RETCODE	rc;

	rc = SQLBindParameter(mCurrentHStmt,
						  position,
						  SQL_PARAM_INPUT,
						  SQL_C_BINARY,
						  SQL_LONGVARBINARY,
						  *pPcbValue,
						  0,
						  pVar,
						  0,
						  pPcbValue);
	Check(rc);

	return;
}

void clsDatabaseODBC::BindColToCHAR(int position,
									UCHAR *pTarget,
									SDWORD targetSize,
									SDWORD *pReturnedLength)
{
	RETCODE rc;

	rc = SQLBindCol(mCurrentHStmt,
					position,
					SQL_C_CHAR,
					pTarget,
					targetSize,
					pReturnedLength);
	Check(rc);
	return;
}

void clsDatabaseODBC::BindColToSDWORD(int position,
									  SDWORD *pTarget,
									  SDWORD *pLength)
{
	RETCODE rc;

	rc = SQLBindCol(mCurrentHStmt,
					position,
					SQL_C_LONG,
					pTarget,
					sizeof(SDWORD),
					pLength);
	Check(rc);
	return;
}

void clsDatabaseODBC::BindColToSFLOAT(int position,
									SFLOAT *pTarget,
									SDWORD *pLength)
{
	RETCODE rc;

	rc = SQLBindCol(mCurrentHStmt,
					position,
					SQL_C_FLOAT,
					pTarget,
					sizeof(SFLOAT),
					pLength);
	Check(rc);
	return;
}

void clsDatabaseODBC::BindColToTIMESTAMP(
								int position,
								TIMESTAMP_STRUCT *pTarget,
								SDWORD *pLength		
										)
{
	RETCODE rc;

	rc = SQLBindCol(mCurrentHStmt,
					position,
					SQL_C_TIMESTAMP,
					pTarget,
					sizeof(SQL_TIMESTAMP_STRUCT),
					pLength);
	Check(rc);
	return;
}

clsDatabaseODBC::clsDatabaseODBC(char *pHost) :
		clsDatabase(pHost)
{
	RETCODE		rc;

	// 
	// Make it all not so!
	// 
	mHaveEnv			= false;
	mHaveDbc			= false;
	mConnected			= false;
	mCurrentHStmt		= NULL;
	mStmtGetSingleItem	= NULL;
	mStmtGetItemXREF	= NULL;

	rc = SQLAllocEnv(&mHEnv);
	Check(rc);
	mHaveEnv	= true;

	rc = SQLAllocConnect(mHEnv, &mHDbc);
	Check(rc);
	mHaveDbc	= true;

	rc = SQLConnect(mHDbc, 
					(UCHAR *)"Local Oracle", SQL_NTS,
					(UCHAR *)"scott", SQL_NTS,
					(UCHAR *)"tiger", SQL_NTS);
	Check(rc);
	mConnected	= true;

	return;
}


clsDatabaseODBC::~clsDatabaseODBC()
{
	if (mConnected)
	{
		SQLDisconnect(mHDbc);
	}

	if (mHaveDbc)
	{
		SQLFreeConnect(mHDbc);
	}
	
	if (mHaveEnv)
	{
		SQLFreeEnv(mHEnv);
	}
	return;
}


//
// Begin
//
void clsDatabaseODBC::Begin()
{
	return;
}

//
// End
//
void clsDatabaseODBC::End()
{
}


//
// ClearAllItems
//

static UCHAR *SQL_DeleteAllItems =
 (UCHAR *)"delete from ebay_items";

		

void clsDatabaseODBC::ClearAllItems()
{
	RETCODE	rc;
	HSTMT	hstmt;

	SetStatement(hstmt);
	rc	= SQLAllocStmt(mHDbc, &hstmt);
	Check(rc);
	rc	= SQLExecDirect(hstmt, SQL_DeleteAllItems,
						SQL_NTS);
	Check(rc);
	SQLFreeStmt(hstmt, SQL_DROP);
	SetStatement(NULL);
	return;
}

//
// GetItemXREF
//
// This is a temporary routine, used to look up the
// mapping between an old style character item id
// and a new numeric one
//
static UCHAR *SQL_GetItemXREF =
 (UCHAR *)"select id from ebay_items_xref	\
			where old_id = ?";

int clsDatabaseODBC::GetItemXREF(char *pItemId)
{

	RETCODE		rc;
	SDWORD		itemIdPcbValue	= SQL_NTS;
	SDWORD		id;
	SDWORD		idLength;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	if (mStmtGetItemXREF == NULL)
	{
		rc	= SQLAllocStmt(mHDbc, &mStmtGetItemXREF);
		Check(rc);
		SetStatement(mStmtGetItemXREF);
		rc = SQLPrepare(mStmtGetItemXREF, SQL_GetItemXREF, SQL_NTS);
		Check(rc);
	}
	else
		SetStatement(mStmtGetItemXREF);

	// Bind that input variable
	BindUCHARToVARCHAR(1, (UCHAR *)pItemId,
					   &itemIdPcbValue);

	// Let's do it!
	rc	= SQLExecute(mStmtGetItemXREF);
	Check(rc);


	// Bind that column, baby
	BindColToSDWORD(1, &id, &idLength);

	// Fetch
	rc	= SQLFetch(mStmtGetItemXREF);
	Check(rc);

	// Clean
	SetStatement(NULL);

	return id;
}

//
// GetItem (old style)
//
bool clsDatabaseODBC::GetItem(char *pId,
							  clsItem *pItem)
{

	// We just look up the cross reference,
	// and pass this on to our bretherin
	return GetItem(GetItemXREF(pId),
				   pItem);
}



//
// GetItem
//
static UCHAR *SQL_GetItem =
 (UCHAR *)
 "select	title,				\
			location,			\
			seller,				\
			password,			\
			category,			\
			quantity,			\
			bidcount,			\
			sale_start,			\
			sale_end,			\
			sale_status,		\
			current_price,		\
			start_price,		\
			reserve_price,		\
			high_bidder,		\
			description_len,	\
			description			\
	from ebay_items				\
	where id = ?";

bool clsDatabaseODBC::GetItem(int id,
							  clsItem *pItem)
{
	RETCODE		rc;

	// Temporary slots for things to live in
	SDWORD				anotherId;
	UCHAR				title[255];
	SDWORD				titleLength;
	UCHAR				location[255];
	SDWORD				locationLength;
	SDWORD				seller;
	SDWORD				sellerLength;
	SDWORD				password;
	SDWORD				passwordLength;
	SDWORD				category;
	SDWORD				categoryLength;
	SDWORD				quantity;
	SDWORD				quantityLength;
	SDWORD				bidcount;
	SDWORD				bidcountLength;
	TIMESTAMP_STRUCT	sale_start;
	SDWORD				sale_startLength;
	time_t				sale_start_time;
	TIMESTAMP_STRUCT	sale_end;
	SDWORD				sale_endLength;
	time_t				sale_end_time;
	SDWORD				sale_status;
	SDWORD				sale_statusLength;
	SFLOAT				current_price;
	SDWORD				current_priceLength;
	SFLOAT				start_price;
	SDWORD				start_priceLength;
	SFLOAT				reserve_price;
	SDWORD				reserve_priceLength;
	SDWORD				high_bidder;
	SDWORD				high_bidderLength;
	SDWORD				description_length;
	SDWORD				description_lengthLength;

	// Description Handling
	SDWORD				descriptionLength;
	UCHAR				*pDescription;

	char				*pLocation;
	char				*pTitle;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	if (mStmtGetSingleItem == NULL)
	{
		rc	= SQLAllocStmt(mHDbc, &mStmtGetSingleItem);
		Check(rc);
		SetStatement(mStmtGetSingleItem);
		rc = SQLPrepare(mStmtGetSingleItem, SQL_GetItem, SQL_NTS);
		Check(rc);
	}
	else
		SetStatement(mStmtGetSingleItem);


	// Bind the input variable
	anotherId	= id;
	BindSDWORDToINT(1, &anotherId);

	// Execute
	rc	= SQLExecute(mStmtGetSingleItem);
	Check(rc);

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	BindColToCHAR(1, title, sizeof(title), &titleLength);
	BindColToCHAR(2, location, sizeof(location), &locationLength);
	BindColToSDWORD(3, &seller, &sellerLength);
	BindColToSDWORD(4, &password, &passwordLength);
	BindColToSDWORD(5, &category, &categoryLength);
	BindColToSDWORD(6, &quantity, &quantityLength);
	BindColToSDWORD(7, &bidcount, &bidcountLength);
	BindColToTIMESTAMP(8, &sale_start, &sale_startLength);
	BindColToTIMESTAMP(9, &sale_end, &sale_endLength);
	BindColToSDWORD(10, &sale_status, &sale_statusLength);
	BindColToSFLOAT(11, &current_price, &current_priceLength);
	BindColToSFLOAT(12, &start_price, &start_priceLength);
	BindColToSFLOAT(13, &reserve_price, &reserve_priceLength);
	BindColToSDWORD(14, &high_bidder, &high_bidderLength);
	BindColToSDWORD(15, &description_length, 
					&description_lengthLength);
	// Fetch
	rc	= SQLFetch(mStmtGetSingleItem);
	Check(rc);

	pDescription	= NULL;

	if (description_length > 0)
	{
		pDescription	= new UCHAR [description_length + 1];
		rc	= SQLGetData(mStmtGetSingleItem, 16,
						 SQL_C_BINARY,
						 pDescription,
						 description_length,
						 &descriptionLength);
		Check(rc);
		*(UCHAR *)(pDescription + descriptionLength) = '\0';
	}

	// Now everything is where it's supposed
	// to be. Let's make copies of the title
	// and location for the item
	pTitle		= new char[titleLength + 1];
	strcpy(pTitle, (char *)title);
	pLocation	= new char[locationLength + 1];
	strcpy(pLocation, (char *)location);

	// Time Conversions
	TIMESTAMP_STRUCTToTime(&sale_start, &sale_start_time);
	TIMESTAMP_STRUCTToTime(&sale_end, &sale_end_time);

	// Fill in the item
	pItem->Set(id,
			   pTitle,
			   (char *)pDescription,
			   pLocation,
			   seller,
			   password,
			   category,
			   bidcount,
			   quantity,
			   sale_start_time,
			   sale_end_time,
			   sale_status,
			   current_price,
			   start_price,
			   reserve_price,
			   high_bidder);


	return true;
}


//
// GetNextItemId
//
// Retrieves the next availible item id. Whether
// this is done with a sequence, or a column in
// a table is irrelevant
//
static UCHAR *SQL_GetNextItemId =
 (UCHAR *)
 "select ebay_items.nextval from dual";

int clsDatabaseODBC::GetNextItemId()
{
	RETCODE		rc;

	HSTMT		hstmt;
	
	SDWORD		nextId;
	SDWORD		nextIdLength;

	// Not used often, so we don't need a persistent
	// cursor
	rc	= SQLAllocStmt(mHDbc, &hstmt);
	Check(rc);
	SetStatement(hstmt);
	rc = SQLPrepare(hstmt, SQL_GetNextItemId, SQL_NTS);
	Check(rc);

	// Execute
	rc	= SQLExecute(hstmt);
	Check(rc);

	// Bind and Fetch
	BindColToSDWORD(1, &nextId, &nextIdLength);

	rc	= SQLFetch(hstmt);
	Check(rc);

	// Free things
	SQLFreeStmt(hstmt, SQL_DROP);
	SetStatement(NULL);

	return nextId;
}


//
// AddItem
//
static UCHAR *SQL_AddItem =
 (UCHAR *)
 "insert into ebay_items	\
	(	id,					\
		title,				\
		description,		\
		description_len,	\
		location,			\
		seller,				\
		password,			\
		category,			\
		quantity,			\
		bidcount,			\
		sale_start,			\
		sale_end,			\
		sale_status,		\
		current_price,		\
		start_price,		\
		reserve_price,		\
		high_bidder			\
	)						\
  values					\
    (	?, ?, ?, ?, ?,		\
		?, ?, ?, ?, ?,		\
		?,					\
		?,					\
		?, ?, ?,			\
		?, ?				\
	)";

void clsDatabaseODBC::AddItem(clsItem *pItem)
{
	RETCODE		rc;
	HSTMT		hstmt;

	// SQLTYPES.H style variables to prevent
	// any problems with the differences in 
	// the representation of data in the item
	// object as opposed to what ODBC wants.
	SDWORD				id;
	SDWORD				seller;
	SDWORD				password;
	SDWORD				category;
	SDWORD				quantity;
	SDWORD				bidcount;
	TIMESTAMP_STRUCT	sale_start;
	TIMESTAMP_STRUCT	sale_end;
	SDWORD				sale_status;
	SFLOAT				current_price;
	SFLOAT				start_price;
	SFLOAT				reserve_price;
	SDWORD				high_bidder;

	SDWORD				titlePcbValue = SQL_NTS;
	SDWORD				locationPcbValue = SQL_NTS;


	// Description Length
	SDWORD				descriptionLength;


	// We don't use this statement very often,
	// so the cursor's not persistant. Let's 
	// prepare the statement
	rc	= SQLAllocStmt(mHDbc, &hstmt);
	Check(rc);
	SetStatement(hstmt);
	rc = SQLPrepare(hstmt, SQL_AddItem, SQL_NTS);
	Check(rc);

	// Extract things from the item into our
	// local variables to prevent any casting
	// confusion
	id				= pItem->GetId();
	seller			= pItem->GetSeller();
	password		= pItem->GetPassword();
	category		= pItem->GetCategory();
	quantity		= pItem->GetQuantity();
	bidcount		= pItem->GetBidCount();
	sale_status		= pItem->GetStatus();
	current_price	= pItem->GetCurrentPrice();
	start_price		= pItem->GetStartPrice();
	reserve_price	= pItem->GetReservePrice();
	high_bidder		= pItem->GetHighBidder();

	// Date conversions. Ick.
	TimeToTIMESTAMP_STRUCT(pItem->GetStartTime(),
							&sale_start);
	TimeToTIMESTAMP_STRUCT(pItem->GetEndTime(),
							&sale_end);

	// Ok, let's do some binds..
	BindSDWORDToINT(1, &id);
	BindUCHARToVARCHAR(2, (UCHAR *)pItem->GetTitle(),
					   &titlePcbValue);
	descriptionLength	= strlen(pItem->GetDescription());
	BindUCHARToLONGVARBINARY(3, (UCHAR *)pItem->GetDescription(),
							 &descriptionLength);
	BindSDWORDToINT(4, &descriptionLength);
	BindUCHARToVARCHAR(5, (UCHAR *)pItem->GetLocation(),
					   &locationPcbValue);
	BindSDWORDToINT(6, &seller);
	BindSDWORDToINT(7, &password);
	BindSDWORDToINT(8, &category);
	BindSDWORDToINT(9, &quantity);
	BindSDWORDToINT(10, &bidcount);
	BindTIMESTAMPToTIMESTAMP(11, &sale_start);
	BindTIMESTAMPToTIMESTAMP(12, &sale_end);
	BindSDWORDToINT(13, &sale_status);
	BindSFLOATToFLOAT(14, &current_price);
	BindSFLOATToFLOAT(15, &start_price);
	BindSFLOATToFLOAT(16, &reserve_price);
	BindSDWORDToINT(17, &high_bidder);

	// Let's do it!
	rc	= SQLExecute(hstmt);
	Check(rc);

	// Commit
	rc	= SQLTransact(mHEnv, mHDbc, SQL_COMMIT);
	Check(rc);

	// Free things
	SQLFreeStmt(hstmt, SQL_DROP);
	SetStatement(NULL);

	// If there's a character item number buried
	// in the item, create a cross reference
	if (pItem->mpItemNo != NULL)
	{
		AddItemXREF(pItem->mpItemNo, id);
	}

	return;
}

//
// AddItemXREF
//
// This routine is used to add a cross reference 
// between an old item id (character) and a new 
// one (numeric)
//
static const UCHAR *SQL_AddItemXREF =
 (UCHAR *)
 "insert into ebay_items_xref		\
	(	old_id, id	)				\
  values							\
	(	?, ?	)";

void clsDatabaseODBC::AddItemXREF(char *pId,
								  SDWORD id)
{
	RETCODE		rc;
	HSTMT		hstmt;
	SDWORD		idPcbValue	= SQL_NTS;

	// We don't use this statement very often,
	// so the cursor's not persistant. Let's 
	// prepare the statement
	rc	= SQLAllocStmt(mHDbc, &hstmt);
	Check(rc);
	SetStatement(hstmt);
	rc = SQLPrepare(hstmt, (UCHAR *)SQL_AddItemXREF, SQL_NTS);
	Check(rc);

	// Bind those two input variables
	BindUCHARToVARCHAR(1, (UCHAR *)pId, &idPcbValue);
	BindSDWORDToINT(2, &id);

	// Let's do it!
	rc	= SQLExecute(hstmt);
	Check(rc);

	// Commit
	rc	= SQLTransact(mHEnv, mHDbc, SQL_COMMIT);
	Check(rc);

	// Free things
	SQLFreeStmt(hstmt, SQL_DROP);
	SetStatement(NULL);

	return;
}


//
// GetBids
//
bool clsDatabaseODBC::GetBids(char *pItemNo,
										DbBidCallBack *pBidCallBack,
										unsigned char *pArbitrary)
{
	return true;
}

//
// GetFeedBackScore
//
bool clsDatabaseODBC::GetFeedbackScore(
								char *pUserId,
								clsFeedback *pFeedback
												  )
{
	return true;
}


//
// GetFeedbackItems
//
bool clsDatabaseODBC::GetFeedbackItems(
					  char *pUserId,
					  clsFeedback *pFeedback)
{
	return true;
}



//
// CancelQuery
//
void clsDatabaseODBC::CancelQuery()
{
	return;
}

