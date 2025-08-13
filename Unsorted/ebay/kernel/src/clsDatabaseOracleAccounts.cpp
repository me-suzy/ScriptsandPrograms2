/*	$Id: clsDatabaseOracleAccounts.cpp,v 1.13.96.2 1999/07/12 22:50:44 sliang Exp $	*/
//
//	File:	clsDatabaseOracleAccounts.cc
//
//	Class:	clsDatabaseOracle
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 02/09/97 michael	- Created

#include "eBayKernel.h"
#include "math.h"
// *********
// Accounts 
// *********

//
// Get a user's summary account information
//
static const char *SQL_GetAccount =
 "select	TO_CHAR(last_modified,					\
					'YYYY-MM-DD HH24:MI:SS'),	\
			balance,					\
			TO_CHAR(pastduebase,				\
					'YYYY-MM-DD HH24:MI:SS'),	\
			pastDue30Days,					\
			pastDue60Days,					\
			pastDue90Days,					\
			pastDue120Days,					\
			pastDueOver120Days,				\
			cc_First4Digits,				\
			TO_CHAR(cc_Expiration_Date,			\
					'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(cc_Data_Last_Modified,			\
					'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(last_cc_expired_notice_sent,		\
					'YYYY-MM-DD HH24:MI:SS'),      \
		    table_indicator					\
  from ebay_account_balances						\
  where id = :id";

clsAccount *clsDatabaseOracle::GetAccount(int id)
{
	clsAccount	*pAccount;

	char		lastUpdate[32];
	time_t		lastUpdateTime;
	float		balance				= 0;
	sb2			balance_ind;
	char		pastDueBase[32];
	sb2			pastDueBase_ind		= 0;
	time_t		pastDueBaseTime;
	float		pastDue30Days		= 0;
	sb2			pastDue30Days_ind;
	float		pastDue60Days		= 0;
	sb2			pastDue60Days_ind;
	float		pastDue90Days		= 0;
	sb2			pastDue90Days_ind;
	float		pastDue120Days		= 0;
	sb2			pastDue120Days_ind;
	float		pastDueOver120Days	= 0;
	sb2			pastDueOver120Days_ind;
	int			cc_First4Digits		= 0;
	sb2			cc_First4Digits_ind;

    char            cc_Expiry_Date[32];
    sb2                     cc_Expiry_Date_ind = 0;
    time_t          cc_Expirydate;
    char            cc_Data_Last_Updated[32];
    sb2                     cc_Data_Last_Updated_ind = 0;
    time_t          cc_Data_Last_UpdateTime;
    char            last_cc_expired_notice_date[32];
    sb2                     last_cc_expired_notice_date_ind = 0;
    time_t          last_cc_expired_notice_sent;

	int			tableIndicator;
	sb2         tableIndicator_ind;


	// Initialize vars
	memset(lastUpdate, 0x00, sizeof(lastUpdate));
	memset(pastDueBase, 0x00, sizeof(pastDueBase));
	memset(cc_Expiry_Date, 0x00, sizeof(cc_Expiry_Date));
	memset(cc_Data_Last_Updated, 0x00, sizeof(cc_Data_Last_Updated));
	// Open + Parse
	OpenAndParse(&mpCDAGetAccount, SQL_GetAccount);

	// Bind And Define
	Bind(":id", &id);
	Define(1, lastUpdate, sizeof(lastUpdate));
	Define(2, &balance, &balance_ind);
	Define(3, pastDueBase, sizeof(pastDueBase), &pastDueBase_ind);
	Define(4, &pastDue30Days, &pastDue30Days_ind);
	Define(5, &pastDue60Days, &pastDue60Days_ind);
	Define(6, &pastDue90Days, &pastDue90Days_ind);
	Define(7, &pastDue120Days, &pastDue120Days_ind);
	Define(8, &pastDueOver120Days, &pastDueOver120Days_ind);
	Define(9, (int *)&cc_First4Digits, &cc_First4Digits_ind);

	Define(10, cc_Expiry_Date, sizeof(cc_Expiry_Date), &cc_Expiry_Date_ind);
    Define(11, cc_Data_Last_Updated, sizeof(cc_Data_Last_Updated), &cc_Data_Last_Updated_ind);
    Define(12, last_cc_expired_notice_date, sizeof(last_cc_expired_notice_date), &last_cc_expired_notice_date_ind);
	Define(13, &tableIndicator, &tableIndicator_ind);


	// Get and Do
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetAccount);
		SetStatement(NULL);
		return NULL;
	}
	
	// Convert and Zert
	ORACLE_DATEToTime(lastUpdate, &lastUpdateTime);
	if (pastDueBase_ind != -1)
		ORACLE_DATEToTime(pastDueBase, &pastDueBaseTime);
	else
		pastDueBaseTime = (time_t)0;
	
	if (cc_Expiry_Date_ind != -1)
		ORACLE_DATEToTime(cc_Expiry_Date, &cc_Expirydate);
	else
		cc_Expirydate = (time_t)0;
	
	if (cc_Data_Last_Updated_ind != -1)
		ORACLE_DATEToTime(cc_Data_Last_Updated, &cc_Data_Last_UpdateTime);
	else
		cc_Data_Last_UpdateTime = (time_t)0;
	
	if (last_cc_expired_notice_date_ind != -1)
		ORACLE_DATEToTime(last_cc_expired_notice_date, &last_cc_expired_notice_sent);
	else
		last_cc_expired_notice_sent = (time_t)0;
	
	// Eeek and Geek
	pAccount	= new clsAccount(balance,
		lastUpdateTime,
		pastDueBaseTime,
								 pastDue30Days,
								 pastDue60Days,
								 pastDue90Days,
								 pastDue120Days,
								 pastDueOver120Days,
								 cc_First4Digits,
								 cc_Expirydate,
								 cc_Data_Last_UpdateTime,
								 last_cc_expired_notice_sent,
								 tableIndicator);


	// Close your nose
	Close(&mpCDAGetAccount);
	SetStatement(NULL);

	return pAccount;
}

//==============================================================================

unsigned char **clsDatabaseOracle::DetermineCursor( int tableIndicator, CallingTypeEnum from )
{
	if ( from == AddAccountDetailEnum )
	{
		switch ( tableIndicator )
		{
			case -1:
				return &mpCDAAddAccountDetail;
			case 0:
				return &mpCDAAddAccountDetail_0;
			case 1:
				return &mpCDAAddAccountDetail_1;
			case 2:
				return &mpCDAAddAccountDetail_2;
			case 3:
				return &mpCDAAddAccountDetail_3;
			case 4:
				return &mpCDAAddAccountDetail_4;
			case 5:
				return &mpCDAAddAccountDetail_5;
			case 6:
				return &mpCDAAddAccountDetail_6;
			case 7:
				return &mpCDAAddAccountDetail_7;
			case 8:
				return &mpCDAAddAccountDetail_8;
			case 9:
				return &mpCDAAddAccountDetail_9;
			default:
				break;
		}
	}
	if ( from == AddRawAccountDetailEnum )
	{
		switch ( tableIndicator )
		{
			case -1:
				return &mpCDAAddRawAccountDetail;
			case 0:
				return &mpCDAAddRawAccountDetail_0;
			case 1:
				return &mpCDAAddRawAccountDetail_1;
			case 2:
				return &mpCDAAddRawAccountDetail_2;
			case 3:
				return &mpCDAAddRawAccountDetail_3;
			case 4:
				return &mpCDAAddRawAccountDetail_4;
			case 5:
				return &mpCDAAddRawAccountDetail_5;
			case 6:
				return &mpCDAAddRawAccountDetail_6;
			case 7:
				return &mpCDAAddRawAccountDetail_7;
			case 8:
				return &mpCDAAddRawAccountDetail_8;
			case 9:
				return &mpCDAAddRawAccountDetail_9;
			default:
				break;
		}
	}
	if ( from == GetAccountDetailEnum )
	{
		switch ( tableIndicator )
		{
			case -1:
				return &mpCDAGetAccountDetail;
			case 0:
				return &mpCDAGetAccountDetail_0;
			case 1:
				return &mpCDAGetAccountDetail_1;
			case 2:
				return &mpCDAGetAccountDetail_2;
			case 3:
				return &mpCDAGetAccountDetail_3;
			case 4:
				return &mpCDAGetAccountDetail_4;
			case 5:
				return &mpCDAGetAccountDetail_5;
			case 6:
				return &mpCDAGetAccountDetail_6;
			case 7:
				return &mpCDAGetAccountDetail_7;
			case 8:
				return &mpCDAGetAccountDetail_8;
			case 9:
				return &mpCDAGetAccountDetail_9;
			default:
				break;

		}
	}
	if ( from == GetAllPaymentsSince )
	{
		switch ( tableIndicator )
		{
			case -1:
				return &mpCDAGetPaymentsSince;
			case 0:
				return &mpCDAGetPaymentsSince_0;
			case 1:
				return &mpCDAGetPaymentsSince_1;
			case 2:
				return &mpCDAGetPaymentsSince_2;
			case 3:
				return &mpCDAGetPaymentsSince_3;
			case 4:
				return &mpCDAGetPaymentsSince_4;
			case 5:
				return &mpCDAGetPaymentsSince_5;
			case 6:
				return &mpCDAGetPaymentsSince_6;
			case 7:
				return &mpCDAGetPaymentsSince_7;
			case 8:
				return &mpCDAGetPaymentsSince_8;
			case 9:
				return &mpCDAGetPaymentsSince_9;
			default:
				break;

		}
	}
	return &mpCDAOneShot;

 
}  // clsDatabaseOracle::DetermineCursor

//===================================================================================

static char *SQL_UpdateIndicator = 
"update ebay_account_balances set table_indicator = :tableIndicator \
where id = :id";

//===============================================================================

bool clsDatabaseOracle::UpdateIndicator( int id, int indicator )
{

	OpenAndParse(&mpCDAOneShot,
				 SQL_UpdateIndicator);


	// Bind
	Bind(":id", &id);
	Bind(":tableIndicator", &indicator);

	// Do it
	Execute();

	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return false;
	}

	// Otherwise, we're done
	Commit();

	// Done
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return true;

}  //  clsDatabaseOracle::UpdateIndicator

//================================================================================

void clsDatabaseOracle::GetTableName( int tableIndicator, char *tableName )
{

	if ( tableIndicator >= 0 )
		sprintf(tableName, "ebay_accounts_%d", tableIndicator);
	return;

}  // clsDatabaseOracle::GetTableName

//===============================================================================

void clsDatabaseOracle::CombineSQLStatement( const char *firstPart, 
											char *table_name,
											const char *secondPart, 
											char *statement )
{
	int j = 0;
	j = sprintf( statement, "%s", firstPart );
	j += sprintf( statement + j, "%s", table_name );
	j += sprintf( statement + j, "%s", secondPart );
	return;

}  //*clsDatabaseOracle::CombineSQLStatement

//===============================================================================

void clsDatabaseOracle::SetTableIndicator( int id, int &tableIndicator )
{
	if ( SPLIT_ACCOUNTS_STARTED )	//lint !e506 !e774 This is a fixed value.
		tableIndicator = id%10;
	else
		tableIndicator = -1;
	return;

}  // clsDatabaseOracle::SetTableIndicator

//===============================================================================


//
// Update a user's Credit Card information
//
static const char *SQL_UpdateCCInfo =
 "Update	ebay_account_balances						\
	Set	cc_First4Digits		= :cc_First4Digits,			\
		cc_Expiration_Date	= TO_DATE(:cc_Expiration_Date,		\
						'YYYY-MM-DD HH24:MI:SS'),	\
		cc_Data_Last_Modified = TO_DATE(:cc_Data_Last_Modified,		\
						'YYYY-MM-DD HH24:MI:SS')	\
	where	id = :id";

void clsDatabaseOracle::UpdateCCDetails	(
						int id, 
						int cc_First4Digits, 
						time_t cc_Expirydate,
						time_t cc_Updatetime
									)
{
	struct tm	*pcc_Expirydate, *pcc_Updatetime;
	char		ccc_Expirydate[32], ccc_Updatetime[32];

	// Open + Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_UpdateCCInfo);


	if (cc_Expirydate == (time_t) 0)
		cc_Expirydate	= time(0);

	pcc_Expirydate	= localtime(&cc_Expirydate);
	TM_STRUCTToORACLE_DATE(pcc_Expirydate, ccc_Expirydate);

	if (cc_Updatetime == (time_t) 0)
		cc_Updatetime	= time(0);

	pcc_Updatetime	= localtime(&cc_Updatetime);
	TM_STRUCTToORACLE_DATE(pcc_Updatetime, ccc_Updatetime);

	// Bind
	Bind(":id", &id);
	Bind(":cc_First4Digits", &cc_First4Digits);
	Bind(":cc_Expiration_Date", ccc_Expirydate);
	Bind(":cc_Data_Last_Modified", ccc_Updatetime);

	// Do it
	Execute();

	// A user account with id must already be available
	Commit();

	// Done
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}



//
// Update last Credit Card Expiry Notification Send Date
//
static const char *SQL_LastCCExpiredNoticeSent =
 	"Update	ebay_account_balances						\
	Set last_cc_expired_notice_sent	= TO_DATE(:last_cc_expired_notice_sent,	\
					'YYYY-MM-DD HH24:MI:SS')		\
	where	id = :id";

void clsDatabaseOracle::UpdateExpiredNoticeSent	(
						int id, 
						time_t cc_NoticeSentDate
									)
{
	struct tm	*pcc_NoticeSentDate;
	char		ccc_NoticeSentDate[32];

	// Open + Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_LastCCExpiredNoticeSent);


	if (cc_NoticeSentDate == (time_t) 0)
		cc_NoticeSentDate	= time(0);


	pcc_NoticeSentDate	= localtime(&cc_NoticeSentDate);
	TM_STRUCTToORACLE_DATE(pcc_NoticeSentDate, ccc_NoticeSentDate);

	// Bind
	Bind(":id", &id);
	Bind(":last_cc_expired_notice_sent", ccc_NoticeSentDate);

	// Do it
	Execute();

	// A user account with id must already be available
	Commit();


	// Done
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// Get next transaction id
//
static const char *SQL_GetNextTransactionId = 
	"select ebay_transaction_sequence.nextval	\
		from dual";


void clsDatabaseOracle::GetNextTransactionId(TransactionId *pId)
{
	TransactionId	transactionId;

	// Open + Parse
	OpenAndParse(&mpCDAGetNextTransactionId,
				 SQL_GetNextTransactionId);

	// Define
	Define(1, (int *)&transactionId);

	// Execute
	ExecuteAndFetch();

	// Close and nose
	Close(&mpCDAGetNextTransactionId);
	SetStatement(NULL);

	*pId	= transactionId;

	return;
}



//
// Add an account detail entry
//
// Lena
static char *SQL_AddAccountDetailI = "insert into ";
static char *SQL_AddAccountDetailII =
"	(	id,										\
		when,									\
		action,									\
		amount,									\
		memo,									\
		transaction_id,							\
		item_id,								\
		batch_id								\
	)											\
	values										\
	(	:id,									\
		TO_DATE(:when,							\
				'YYYY-MM-DD HH24:MI:SS'),		\
		:action,								\
		:amount,								\
		:memo,									\
		:xid,									\
		:itemId,								\
		:batchId								\
	)";

static char *SQL_AddAccountDetailBefore =
"insert into ebay_accounts \
	(	id,										\
		when,									\
		action,									\
		amount,									\
		memo,									\
		transaction_id							\
	)											\
	values										\
	(	:id,									\
		TO_DATE(:when,							\
				'YYYY-MM-DD HH24:MI:SS'),		\
		:action,								\
		:amount,								\
		:memo,									\
		:xid									\
	)";


void clsDatabaseOracle::AddAccountDetail(
						int id,
						int tableIndicator,
						clsAccountDetail *pDetail )
{
	// Local variables to hold things in. These
	// aren't strictly necessary, since 
	// clsAccountDetail doesn't use getters and
	// setters (yet)
	int				action;
	float			amount;
	char			*pMemo;
	sb2				batchId_ind;
//	sb2				itemId_ind;
	// Date
	time_t			when;
	struct tm		*pLocalWhen;
	char			cWhen[32];
	TransactionId	transactionId;
	char            *table_name;
	char			*SQL_AddAccountDetail;
	int batchId = pDetail->mBatchId;
	int itemId = pDetail->mItemId;
	unsigned char **currentCursor;
	CallingTypeEnum from = AddAccountDetailEnum;
	GetNextTransactionId(&transactionId);

	SQL_AddAccountDetail = new char[1024];
	if ( tableIndicator == -1 )
		strcpy(SQL_AddAccountDetail, SQL_AddAccountDetailBefore);
	else
	{
		table_name = new char[31];
		GetTableName( tableIndicator, table_name );
		CombineSQLStatement( SQL_AddAccountDetailI, table_name, 
			SQL_AddAccountDetailII, SQL_AddAccountDetail );
		delete [] table_name;
	}
	// Open + Parse
	
	currentCursor = DetermineCursor( tableIndicator, from );

	OpenAndParse(currentCursor, SQL_AddAccountDetail);
	delete [] SQL_AddAccountDetail;
	// Fetch
	action		= (int)pDetail->mType;
	amount		= pDetail->mAmount;
	pMemo		= pDetail->mpMemo;
	if (!pMemo)
		pMemo	= "";
    if (pDetail->mBatchId == 0)
	   batchId_ind	= -1;
	// to set item_id to 0 only if it's an old Auction Web item, 
	// otherwise set to NULL
	// Commented out; itemId_ind was never used. -- jpg
	//if ( ( pDetail->mItemId == 0 ) && ( !(*pDetail->mOldItemId ) ) )
	//	itemId_ind = -1;
	when		= pDetail->mTime;
	if (when == (time_t) 0)
		when	= time(0);

	pLocalWhen	= localtime(&when);
	TM_STRUCTToORACLE_DATE(pLocalWhen, cWhen);

	// Bind And Define
	Bind(":id", &id);
	Bind(":when", cWhen);
	Bind(":action", &action);
	Bind(":amount", &amount);
	Bind(":memo", pMemo);
	Bind(":xid", (unsigned int *)&transactionId);
	if ( tableIndicator != -1 )
	{
		Bind(":itemId", &itemId );
		Bind(":batchId", &batchId, &batchId_ind);
	}

	Execute();
//	CheckForNoRowsUpdated();

	Commit();

	Close(currentCursor);
	SetStatement(NULL);
	// Let our client know which transaction they got
	pDetail->mTransactionId	= transactionId;
	return;
}

//===============================================================================

bool clsDatabaseOracle::LoadAccountDetail(
						int id,
						int tableIndicator,
						clsAccountDetail *pDetail )
{
//  load for accountsX10
	int				action;
	float			amount;
	char			*pMemo;
	sb2				batchId_ind;
	sb2				itemId_ind=0;
	// Date
	time_t			when;
	struct tm		*pLocalWhen;
	char			cWhen[32];
	TransactionId	transactionId = pDetail->mTransactionId;
	char            *table_name;
	char			*SQL_AddAccountDetail = NULL;
	int batchId = pDetail->mBatchId;
	int itemId = pDetail->mItemId;
	unsigned char **currentCursor=0;
	CallingTypeEnum from = AddAccountDetailEnum;
	if ( tableIndicator == -1 )
		return false;
	table_name = new char[31];
	GetTableName( tableIndicator, table_name );
	SQL_AddAccountDetail = new char[1024];
	CombineSQLStatement( SQL_AddAccountDetailI, table_name, 
		SQL_AddAccountDetailII, SQL_AddAccountDetail );
	delete [] table_name;
	// Open + Parse
	currentCursor = DetermineCursor( tableIndicator, from );

	OpenAndParse(currentCursor, SQL_AddAccountDetail);
	delete [] SQL_AddAccountDetail;
	// Fetch
	action		= (int)pDetail->mType;
	amount		= pDetail->mAmount;
	pMemo		= pDetail->mpMemo;
	if (!pMemo)
		pMemo	= "";
	if (pDetail->mBatchId == 0)
	   batchId_ind	= -1;

    // to set item_id to 0 only if it's an old Auction Web item, 
	// otherwise set to NULL
	if ( ( pDetail->mItemId == 0 ) && ( !(*pDetail->mOldItemId ) ) )
		itemId_ind = -1;

	when		= pDetail->mTime;
	if (when == (time_t) 0)
		when	= time(0);

	pLocalWhen	= localtime(&when);
	TM_STRUCTToORACLE_DATE(pLocalWhen, cWhen);

	// Bind And Define
	Bind(":id", &id);
	Bind(":when", cWhen);
	Bind(":action", &action);
	Bind(":amount", &amount);
	Bind(":memo", pMemo);
	Bind(":xid", (unsigned int *)&transactionId);
	Bind(":itemId", &itemId, &itemId_ind);
	Bind(":batchId", &batchId, &batchId_ind);

	Execute();
	if ( CheckForNoRowsUpdated() )
	{
		Close(currentCursor);
		SetStatement(NULL);
		return false;
	}
	Close(currentCursor);
	SetStatement(NULL);


	return true;

}  // clsDatabaseOracle::LoadAccountDetail

//===============================================================================

static const char *SQL_AddInterimBalance =
 "insert into ebay_interim_balances				\
	(	id,										\
		when,									\
		balance 								\
	)											\
	values										\
	(	:id,									\
      TO_DATE(:when,							\
            'YYYY-MM-DD HH24:MI:SS'),			\
		:amount								\
	)";

//===============================================================================

void clsDatabaseOracle::AddInterimBalance(
						int id,
						time_t theTime,
		       			double amount )
{
	// Local variables to hold things in. These
	// aren't strictly necessary, since 
	// clsAccountDetail doesn't use getters and
	// setters (yet)
	struct tm		*pTheTime;
	char			when[64];
	float           famount;

	// Open + Parse
	OpenAndParse(&mpCDAOneShot, SQL_AddInterimBalance);

	// Convert
    pTheTime = localtime(&theTime);
    TM_STRUCTToORACLE_DATE(pTheTime, when);
	famount = amount;
	// Bind And Define
	Bind(":id", &id);
	Bind(":when", (char *)when);
	Bind(":amount", &famount);

	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);


	return;

} // clsDatabaseOracle::AddInterimBalance

//=============================================================================
//
// Add a raw account detail entry
//
static const char *SQL_AddRawAccountDetailI = "insert into ";
static const char *SQL_AddRawAccountDetailII =
"	(	id,										\
		when,									\
		action,									\
		amount,									\
		memo,									\
		transaction_id,							\
		migration_batch_id,						\
		batch_id,								\
		item_id									\
	)											\
	values										\
	(	:id,									\
      TO_DATE(:when,							\
            'YYYY-MM-DD HH24:MI:SS'),			\
		:action,								\
		:amount,								\
		:memo,									\
		:seq,									\
		:mbi,									\
		:batchId,								\
		:itemId									\
	)";

static char *SQL_AddRawAccountDetailBefore =
"insert into ebay_accounts						\
	(	id,										\
		when,									\
		action,									\
		amount,									\
		memo,									\
		transaction_id,							\
		migration_batch_id						\
	)											\
	values										\
	(	:id,									\
      TO_DATE(:when,							\
            'YYYY-MM-DD HH24:MI:SS'),			\
		:action,								\
		:amount,								\
		:memo,									\
		:seq,									\
		:mbi									\
	)";
void clsDatabaseOracle::AddRawAccountDetail(
						int id,
						int tableIndicator,
						clsAccountDetail *pDetail,
						int migrationBatchId)
{
	// Local variables to hold things in. These
	// aren't strictly necessary, since 
	// clsAccountDetail doesn't use getters and
	// setters (yet)
	int				action;
	float			amount;
	char			*pMemo;
	time_t			theTime;
	struct tm		*pTheTime;
	char			when[64];
	unsigned int	seq;
	
	sb2				memo_ind;
	//inna need these indicator to be 0, in order for binds to work properly
	sb2				migrationBatchId_ind=0;
	sb2				batchId_ind=0;
	sb2				itemId_ind=0;
	char            *table_name;
	char			*SQL_AddRawAccountDetail;
	int				batchId = pDetail->mBatchId;
	int				itemId = pDetail->mItemId;
	unsigned char **currentCursor;
	
	// We need a transaction sequence
	GetNextTransactionId(&seq);
	SQL_AddRawAccountDetail = new char[1024];
	if ( tableIndicator == -1 )
		strcpy( SQL_AddRawAccountDetail,SQL_AddRawAccountDetailBefore );
	else
	{
		table_name = new char[31];
		GetTableName( tableIndicator, table_name );
		CombineSQLStatement( SQL_AddRawAccountDetailI, table_name, 
			SQL_AddRawAccountDetailII, SQL_AddRawAccountDetail );
		delete [] table_name;
	}
	
	// Open + Parse
	currentCursor = DetermineCursor( tableIndicator, AddRawAccountDetailEnum );
	OpenAndParse(currentCursor, SQL_AddRawAccountDetail);
	delete [] SQL_AddRawAccountDetail;
	
	// Fetch
	action		= (int)pDetail->mType;
	amount		= pDetail->mAmount;
	pMemo		= pDetail->mpMemo;
	
	// Convert
	theTime  = pDetail->mTime;
	pTheTime = localtime(&theTime);
	TM_STRUCTToORACLE_DATE(pTheTime, when);
	
	// Set Nulls 
	if (migrationBatchId == 0)
		migrationBatchId_ind	= -1;
	// Lena
	if (batchId == 0)
		batchId_ind	= -1;
	// to set item_id to 0 only if it's an old Auction Web item, 
	// otherwise set to NULL
	if (itemId == 0)
		itemId_ind	= -1;
	
	if (pMemo == NULL)
	{
		pMemo	= "";
		memo_ind	= -1;
	}
	else
		memo_ind	= 0;

	// Bind And Define
	Bind(":id", &id);
	Bind(":when", (char *)when);
	Bind(":action", &action);
	Bind(":amount", &amount);
	Bind(":memo", pMemo, &memo_ind);
	Bind(":seq", &seq);
	Bind(":mbi", &migrationBatchId, &migrationBatchId_ind);
	if ( tableIndicator != -1 )
	{
		Bind(":batchId", &batchId, &batchId_ind);
		Bind(":itemId", &itemId, &itemId_ind);
	}

	Execute();
	Commit();

	Close(currentCursor);
	SetStatement(NULL);
	pDetail->mTransactionId	= seq;

	return;
}

void clsDatabaseOracle::AddRawAccountDetail(
						int count,
						int *pId,
						int tableIndicator,
						char *pWhen,
						int *pAction,
						float *pAmount,
						char *pMemo,
						int *pSeq,
						int *pMigrationBatchId,
						int *pBatchId, int itemId)
{
	int		rc;
	char    *table_name;
	char    *SQL_AddRawAccountDetail;
	unsigned char **currentCursor;
	SQL_AddRawAccountDetail = new char[1024];
	if ( tableIndicator == -1 )
		strcpy(	SQL_AddRawAccountDetail, SQL_AddRawAccountDetailBefore );
	else
	{
		table_name = new char[31];
		GetTableName( tableIndicator, table_name );
		CombineSQLStatement( SQL_AddRawAccountDetailI, table_name, 
			SQL_AddRawAccountDetailII, SQL_AddRawAccountDetail );
		delete [] table_name;
	}
	currentCursor = DetermineCursor( tableIndicator, AddRawAccountDetailEnum );
	// Open + Parse
	OpenAndParse(currentCursor, SQL_AddRawAccountDetail);
	delete [] SQL_AddRawAccountDetail;


	// Bind And Define
	Bind(":id", pId);
	Bind(":when", pWhen, 32);
	Bind(":action", pAction);
	Bind(":amount", pAmount);
	Bind(":memo", pMemo, 81);
	Bind(":seq", pSeq);
	Bind(":mbi", pMigrationBatchId);
	if (tableIndicator != -1)
	{
		Bind(":batchId", pBatchId);
		Bind(":itemId", &itemId);
	}

	rc	= oexn((cda_def *)mpCDACurrent, count, 0);
	Check(rc);

	Commit();
	Close(currentCursor);
	SetStatement(NULL);

	return;
}



//
// Create an Account balance row
//
static const char *SQL_CreateAccount = 
 "insert into ebay_account_balances			\
  (	id,										\
	last_modified,							\
	balance,								\
	table_indicator							\
  )											\
  values									\
  (	:id,									\
	sysdate,								\
	:balance,								\
	:table_indicator							\
  )";

void clsDatabaseOracle::CreateAccount(int id,
									  double balance)
{
	float	myBalance	= balance;
	int tableIndicator;
	SetTableIndicator( id, tableIndicator );
	// Open + Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_CreateAccount);


	// Bind
	Bind(":id", &id);
	Bind(":balance", &myBalance);
	Bind(":table_indicator", &tableIndicator);


	// Do it
	Execute();

	// If we didn't update any rows, then we need to
	// create the account
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		CreateAccount(id, balance);
		return;
	}

	Commit();

	// Done
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


//
// Adjust the account balance
//
static const char *SQL_AdjustAccountBalance = 
 "update ebay_account_balances				\
	set		balance	= balance + :delta,		\
			last_modified = sysdate			\
	where	id = :id";

void clsDatabaseOracle::AdjustAccountBalance(int id,
											 double delta)
{
	float	myDelta	= delta;

	// Open + Parse
	OpenAndParse(&mpCDAAdjustAccountBalance,
				 SQL_AdjustAccountBalance);


	// Bind
	Bind(":id", &id);
	Bind(":delta", &myDelta);

	// Do it
	Execute();

	// If we didn't update any rows, then we need to
	// create the account
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAAdjustAccountBalance);
		SetStatement(NULL);
		CreateAccount(id, delta);
		return;
	}

	// Otherwise, we're done
	Commit();

	// Done
	Close(&mpCDAAdjustAccountBalance);
	SetStatement(NULL);

	return;
}

//============================================================================

static const char *SQL_GetInterimBalanceForMonth = 
"select count(*) from ebay_interim_balances where id=:id and \
when = to_date(:when,'YYYY-MM-DD HH24:MI:SS')";

//============================================================================

bool clsDatabaseOracle::GetInterimBalanceForMonth( int id, time_t the_time )
{
	char		when[32];
	int count;

	// Initialize vars
	memset(when, 0x00, sizeof(when));
	TimeToORACLE_DATE( the_time, when );
	// Open + Parse
	OpenAndParse( &mpCDAOneShot, SQL_GetInterimBalanceForMonth );

	// Bind And Define
	Bind(":id", &id);
	Bind(":when", (char *)when);

	Define(1, &count);
	// Get and Do
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return false;
	}
	// Close your nose
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	if ( count == 0 )
		return false;
	return true;

}  // clsDatabaseOracle::GetInterimBalanceForMonth

//============================================================================

static const char *SQL_GetEarliestInterimBalance = 
"select TO_CHAR(when,'YYYY-MM-DD HH24:MI:SS'),\
balance from ebay_interim_balances where id=:id \
and when = (select min(when) from ebay_interim_balances where id=:id)";

//============================================================================

static const char *SQL_GetInterimBalance =
  "select TO_CHAR(when,'YYYY-MM-DD HH24:MI:SS'),\
	balance                      \
   from ebay_interim_balances where id = :id \
   and when = (select max(when) from ebay_interim_balances where id=:id)";

//===============================================================================

bool clsDatabaseOracle::GetInterimBalance( int id, time_t &theTime, 
										  double &amount, bool first )
{
	float		balance				= 0;
	sb2			balance_ind;
	char		the_time[32];

	// Initialize vars
	memset(the_time, 0x00, sizeof(the_time));
	// Open + Parse
	if ( first )
		OpenAndParse( &mpCDAOneShot, SQL_GetEarliestInterimBalance );
	else
		OpenAndParse( &mpCDAInterimBalance, SQL_GetInterimBalance );

	// Bind And Define
	Bind(":id", &id);
	Define(1, the_time, sizeof(the_time));
	Define(2, &balance, &balance_ind);
	// Get and Do
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		if (first)
			Close(&mpCDAOneShot);
		else
			Close(&mpCDAInterimBalance);
		SetStatement(NULL);
		return false;
	}
	// Convert and Zert
	ORACLE_DATEToTime( the_time, &theTime );
	amount = balance;
	// Close your nose
	if (first)
		Close(&mpCDAOneShot);
	else
		Close(&mpCDAInterimBalance);

	SetStatement(NULL);
	return true;

}  // clsDatabaseOracle::GetInterimBalance

//=============================================================================

static char *SQL_GetMonthRange =
"select										\
TO_CHAR(min(when),'YYYY-MM-DD HH24:MI:SS'), \
TO_CHAR(max(when), 'YYYY-MM-DD HH24:MI:SS')  \
from ebay_interim_balances where id=:id";

//=============================================================================

bool clsDatabaseOracle::GetMonthRangeForUsers( int id, time_t &timeStart, 
											   time_t &timeEnd )
{
	char		the_timeStart[32];
	char		the_timeEnd[32];

	memset(the_timeStart, 0x00, sizeof(the_timeStart));
	memset(the_timeEnd, 0x00, sizeof(the_timeEnd));
	// Open + Parse
	OpenAndParse( &mpCDAOneShot, SQL_GetMonthRange );

	// Bind And Define
	Bind(":id", &id);
	Define(1, the_timeStart, sizeof(the_timeStart));
	Define(2, the_timeEnd, sizeof(the_timeEnd));
	// Get and Do
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return false;
	}
	// Convert and Zert

	ORACLE_DATEToTime( the_timeStart, &timeStart );
	ORACLE_DATEToTime( the_timeEnd, &timeEnd );
	if ( ( timeStart == -1 ) || ( timeEnd == -1 ) )
		return false;
	// Close your nose
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return true;

}  // clsDatabseOracle::GetMonthRangeForUsers

//=============================================================================

static const char *SQL_GetOldIdBalance =
"select balance from ebay_interim_balances where id=:oldId and \
when=to_date(:when,'YYYY-MM-DD HH24:MI:SS')";

static const char *SQL_UpdateInterimBalanceBalance = 
"update ebay_interim_balances set balance = balance + :oldBalance\
 where id=:newId and when=to_date(:when,'YYYY-MM-DD HH24:MI:SS')";

static const char *SQL_UpdateInterimBalanceUser =
"update ebay_interim_balances set id = :newId where \
id =:oldId and when = to_date(:when,'YYYY-MM-DD HH24:MI:SS')";
 
#ifdef NEEDED
static const char *SQL_InsertInterimBalance =
"insert into ebay_interim_balances ( id, when, balance ) values \
(:newId, to_date(:when, 'YYYY-MM-DD HH24:MI:SS'), :balance )";
#endif

static const char *SQL_DeleteOldInterimBalance =
" delete from ebay_interim_balances where id=:oldId and \
when=to_date(:when,'YYYY-MM-DD HH24:MI:SS')"; 

//============================================================================

bool clsDatabaseOracle::CombineInterimBalance( int oldId, int newId, 
											  time_t the_time )
{
	struct tm			*pTheTime;
	char				when[32];
	float               amount;

	gApp->GetDatabase()->Begin();

	OpenAndParse(&mpCDAOneShot,
				 SQL_GetOldIdBalance);

   // Convert
   pTheTime = localtime(&the_time);
   TM_STRUCTToORACLE_DATE(pTheTime, when);

	// Bind
	Bind(":oldId", &oldId);
	Bind(":when", (char *)when);
	Define(1, (float *)&amount);
//  Get old user's interim balance
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		gApp->GetDatabase()->Cancel();
		return false;
	}
	// Close
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	// OpenAndParse
	OpenAndParse(&mpCDAOneShot,
				 SQL_UpdateInterimBalanceBalance);

	// Bind
	Bind(":newId", &newId);
	Bind(":when", (char *)when);
	Bind(":oldBalance", &amount);

	// Do
	// update new user interim balance
	Execute();
	if (CheckForNoRowsUpdated())
	{
		// if no interim balance existed for the user - update the userid for new one
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		OpenAndParse(&mpCDAOneShot,
				 SQL_UpdateInterimBalanceUser);
		// Bind
		Bind(":newId", &newId);
		Bind(":oldId", &oldId);
		Bind(":when", (char *)when);
		Execute();
		if (CheckForNoRowsUpdated())
		{
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			gApp->GetDatabase()->Cancel();
			return false;
		}
	}

	// Close
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	OpenAndParse(&mpCDAOneShot,
				 SQL_DeleteOldInterimBalance);
	Bind(":oldId", &oldId);
	Bind(":when", (char *)when);
	Execute();
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		gApp->GetDatabase()->Cancel();
		return false;
	}

	Close(&mpCDAOneShot);
	SetStatement(NULL);
	//debug only
	gApp->GetDatabase()->End();
	return true;

}  // clsDatabaseOracle::CombineInterimBalance

//=============================================================================

void clsDatabaseOracle::CalculateDate( time_t &theDate )
{
	struct tm *the_date = localtime( &theDate );
	bool leapYear = false;
	if ( ( the_date->tm_year % 4 ) == 0 ) 
	{
		if ( ( ( the_date->tm_year + 1900 ) % 1000 ) == 0 )
			leapYear = false;
		else
			leapYear = true;
	}

	if ( the_date->tm_mon == 11 )
	{
		the_date->tm_year++;
		the_date->tm_mon = 0;
	}
	else
		the_date->tm_mon++;

	switch ( the_date->tm_mon + 1 )
	{
		case 1: 
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			the_date->tm_mday = 31;
			break;
		case 4: 
		case 6: 
		case 9: 
		case 11:
			the_date->tm_mday = 30;
			break;
		case 2:
			if ( leapYear )
				the_date->tm_mday = 29;
			else
				the_date->tm_mday = 28;
			break;
		default:
			break;
	}
	theDate = mktime( the_date );
	return;

}  //  clsDatabaseOracle::CalculateDate

//============================================================================

static const char *SQL_GetInterimBalances =
  "select	TO_CHAR(when,'YYYY-MM-DD HH24:MI:SS'),		\
			balance										\
   from		ebay_interim_balances						\
   where	id = :id									\
   order by	when asc";

//===============================================================================

#define ORA_INTERIM_BALANCES_ARRAYSIZE	10

void clsDatabaseOracle::GetInterimBalances(int id,
										   InterimBalanceList *plBalances)
{
	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;


	float		balance[ORA_INTERIM_BALANCES_ARRAYSIZE];
	char		cTime[ORA_INTERIM_BALANCES_ARRAYSIZE][32];

	time_t		theTime;

	clsInterimBalance	*pInterimBalance;

	// Open + Parse
	OpenAndParse( &mpCDAGetInterimBalances, SQL_GetInterimBalances );

	// Bind And Define
	Bind(":id", &id);

	Define(1, cTime[0], sizeof(cTime[0]));
	Define(2, balance);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetInterimBalances);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORA_INTERIM_BALANCES_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_INTERIM_BALANCES_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; 
			 i < n;
			 i++)
		{
			// Convert time
			ORACLE_DATEToTime(cTime[i],
							  &theTime);

			// Make it
			pInterimBalance	= new clsInterimBalance(id, theTime,
													balance[i]);

			plBalances->push_back(pInterimBalance);
		}
	} while (!CheckForNoRowsFound());


	// Close your nose
	Close(&mpCDAGetInterimBalances);
	SetStatement(NULL);
	return;

}  // clsDatabaseOracle::GetInterimBalances

//=============================================================================

bool clsDatabaseOracle::CombineInterimBalanceForUsers( int oldId, int newId )
{
	time_t monthStart, monthEnd, currentDate;
	monthStart = (time_t)0;
	monthEnd = (time_t)0;
	currentDate = (time_t)0;
	if ( !GetMonthRangeForUsers( oldId, monthStart, monthEnd ) )
		return false;
	currentDate = monthStart;
	while ( currentDate <= monthEnd )
	{
		CombineInterimBalance( oldId, newId, currentDate );
		CalculateDate( currentDate );
	}
	return true;

}  // void clsDatabaseOracle::CombineInterimBalanceForUsers( int id )

//=============================================================================
// Lena
static const char *SQL_RebalanceAccountI = 
 "update ebay_account_balances				\
	set balance =							\
		(select sum(amount)					\
			from ";
static const char *SQL_RebalanceAccountII = 
"		where id = :id),				\
		last_modified = sysdate				\
		where id = :id";

void clsDatabaseOracle::RebalanceAccount(int id, int tableIndicator )
{
	char *table_name;
	char *SQL_RebalanceAccount;

	table_name = new char[31];
	SQL_RebalanceAccount = new char[1024];
	if ( tableIndicator < 0 )
		strcpy( table_name, "ebay_accounts " );
	else
		GetTableName( tableIndicator, table_name );
	CombineSQLStatement( SQL_RebalanceAccountI, table_name, 
		SQL_RebalanceAccountII, SQL_RebalanceAccount );

	// Open + Parse
	OpenAndParse(&mpCDAOneShot, SQL_RebalanceAccount);
	delete [] table_name;
	delete [] SQL_RebalanceAccount;


	// Bind
	Bind(":id", &id);

	// Do it
	Execute();

	// If we didn't update any rows, then we need to
	// create the account
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		CreateAccount(id, 0);
		RebalanceAccount(id, tableIndicator );

		return;
	}

	Commit();

	// Done
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// GetAccountDetail
//

#define ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE 200
static char *SQL_GetAccountDetailBefore = 
"select TO_CHAR( a.when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			a.action,							\
			a.amount,							\
			a.transaction_id,					\
			a.memo,								\
			xawitems.aw_item,					\
			xitems.item_id						\
  from	ebay_accounts a,						\
		ebay_transaction_xref_aw_item xawitems,	\
		ebay_transaction_xref_item xitems		\
  where	a.id = :id								\
	and	a.transaction_id = xawitems.id (+)		\
	and	a.transaction_id = xitems.id (+)		\
  order	by a.when asc";

static const char *SQL_GetAccountDetailI = 
 "select	TO_CHAR(when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			action,							\
			amount,							\
			transaction_id,					\
			memo,						\
			NULL,						\
			item_id						\
  from	";
static const char *SQL_GetAccountDetailII = 
"  where	id = :id								\
  order	by when asc";

void clsDatabaseOracle::GetAccountDetail(int id, int tableIndicator,

										 AccountDetailVector *pvDetail)
{
	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	// Pointers to arrays of things
	char		when[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][32];
	int			action[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	float		amount[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			xactionId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		memo[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][256];
	sb2			memo_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		oldItemId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][16];
	sb2			oldItemId_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			itemId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	sb2			itemId_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];

	time_t					theWhen;
	char					*pTheMemo;
	char					*pTheOldItemId;
	int						theItemId;
	AccountDetailTypeEnum	theType;

	clsAccountDetail		*pDetail;
	char                    *table_name;
	char				*SQL_GetAccountDetail;
	unsigned char			**currentCursor;
	CallingTypeEnum from = GetAccountDetailEnum;
	SQL_GetAccountDetail = new char[1024];
	if ( tableIndicator == -1 )
		strcpy( SQL_GetAccountDetail, SQL_GetAccountDetailBefore );
	else
	{
		table_name = new char[31];
		GetTableName( tableIndicator, table_name );
		CombineSQLStatement( SQL_GetAccountDetailI, table_name, 
			SQL_GetAccountDetailII, SQL_GetAccountDetail );
		delete [] table_name;
	}
	// Let's open the cursor
	currentCursor = DetermineCursor( tableIndicator, from );
	OpenAndParse(currentCursor,
				 SQL_GetAccountDetail);
	delete [] SQL_GetAccountDetail;
	// Bind
	Bind(":id", &id);
	
	// Define
	Define(1, when[0], sizeof(when[0]));
	Define(2, action);
	Define(3, amount);
	Define(4, xactionId);
	Define(5, memo[0], sizeof(memo[0]), 
		      (short *)memo_ind);
	Define(6, oldItemId[0], sizeof(oldItemId[0]),
			  oldItemId_ind);
	Define(7, itemId, itemId_ind);

	Execute();
	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(currentCursor);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; 
			 i < n;
			 i++)
		{

			// Convert time
			ORACLE_DATEToTime(when[i],
								   &theWhen);

			// Convert Type
			theType	= (AccountDetailTypeEnum)action[i];

			// Check for NULL memo
			if (memo_ind[i] == -1)
				pTheMemo	= NULL;
			else
				pTheMemo	= (char *)&memo[i][0];

			// Check for NULL old item Id
			if (oldItemId_ind[i] == -1)
				pTheOldItemId	= NULL;
			else
				pTheOldItemId	= (char *)&oldItemId[i][0];

			if (itemId_ind[i] == -1)
				theItemId	= 0;
			else
				theItemId	= itemId[i];
			if ( ( theItemId == 0 ) && ( pTheOldItemId ) )
				pTheOldItemId = "Auction Web";

			pDetail	= new clsAccountDetail(id,
										   theWhen,
										   theType,
										   amount[i],
										   pTheMemo,
										   xactionId[i],
										   pTheOldItemId,
										   theItemId);

			pvDetail->push_back(pDetail);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(currentCursor);
	SetStatement(NULL);


	return;
}

//===============================================================================
// Lena
static char *SQL_GetAccountDetailSinceBefore = 
 "select	TO_CHAR(a.when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			a.action,							\
			a.amount,							\
			a.transaction_id,					\
			a.memo,								\
			xawitems.aw_item,					\
			xitems.item_id						\
  from	ebay_accounts a,						\
		ebay_transaction_xref_aw_item xawitems,	\
		ebay_transaction_xref_item xitems		\
  where	a.id = :id and							\
  a.when >= to_date(:when,'YYYY-MM-DD HH24:MI:SS')\
	and	a.transaction_id = xawitems.id (+)		\
	and	a.transaction_id = xitems.id (+)		\
  order	by a.when asc";

static const char *SQL_GetAccountDetailSinceI = 
 "select	TO_CHAR(when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			action,							\
			amount,							\
			transaction_id,					\
			memo,						\
			NULL,						\
			item_id						\
  from	";
static const char *SQL_GetAccountDetailSinceII = 
"  where	id = :id and							\
  when >= to_date(:when,'YYYY-MM-DD HH24:MI:SS')\
  order	by when asc";

static char *SQL_GetAccountDetailSinceWithoutAW = 
 "select	TO_CHAR(a.when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			a.action,							\
			a.amount,							\
			a.transaction_id,					\
			a.memo,								\
			NULL,								\
			xitems.item_id						\
  from	ebay_accounts a,						\
		ebay_transaction_xref_item xitems		\
  where	a.id = :id and							\
  a.when >= to_date(:when,'YYYY-MM-DD HH24:MI:SS')\
	and	a.transaction_id = xitems.id (+)		\
  order	by a.when asc";

//===============================================================================

void clsDatabaseOracle::GetAccountDetail(int id, int tableIndicator,
							AccountDetailVector *pvDetail, time_t since)
{
	// A Helpful time to know
	struct tm	awEndTimeTM;
	time_t		awEndTime;

	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	// Pointers to arrays of things
	char		when[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][32];
	int			action[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	float		amount[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			xactionId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		memo[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][256];
	sb2			memo_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		oldItemId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][16];
	sb2			oldItemId_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			itemId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	sb2			itemId_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];

	time_t					theWhen;
	char					*pTheMemo;
	char					*pTheOldItemId;
	int						theItemId;
	AccountDetailTypeEnum	theType;
	char					ccc_since[32];

	clsAccountDetail		*pDetail;
	char					*table_name;
	char					*SQL_GetAccountDetailSince;

	// Save ourselves a little time when we're not interested in 
	// AW items. It's conservativly set to 10/1/97.
	memset(&awEndTimeTM, 0x00, sizeof(awEndTimeTM));
	awEndTimeTM.tm_sec		= 0;
	awEndTimeTM.tm_min		= 0;
	awEndTimeTM.tm_hour		= 0;
	awEndTimeTM.tm_mday		= 1;
	awEndTimeTM.tm_mon		= 9;
	awEndTimeTM.tm_year		= 97;
	awEndTimeTM.tm_isdst	= -1;

	awEndTime				= mktime(&awEndTimeTM);

	TimeToORACLE_DATE(since, ccc_since);
	SQL_GetAccountDetailSince = new char[1024];
	if ( tableIndicator == -1 )
		strcpy( SQL_GetAccountDetailSince, SQL_GetAccountDetailSinceBefore );
	else
	{
		table_name = new char[31];
		GetTableName( tableIndicator, table_name );
		CombineSQLStatement( SQL_GetAccountDetailSinceI, table_name, 
			SQL_GetAccountDetailSinceII, SQL_GetAccountDetailSince );
		delete [] table_name;
	}

	// Let's open the cursor
	if ( (since > awEndTime) && (tableIndicator == -1 ) )

	{
		OpenAndParse(&mpCDAOneShot,
					 SQL_GetAccountDetailSinceWithoutAW);
	}
	else
	{
		OpenAndParse(&mpCDAOneShot,
					 SQL_GetAccountDetailSince);
	}

	delete [] SQL_GetAccountDetailSince;

	// Bind
	Bind(":id", &id);
	Bind( ":when", ccc_since );
	
	// Define
	Define(1, when[0], sizeof(when[0]));
	Define(2, action);
	Define(3, amount);
	Define(4, xactionId);
	Define(5, memo[0], sizeof(memo[0]), 
		      memo_ind);
	Define(6, oldItemId[0], sizeof(oldItemId[0]),
			  oldItemId_ind);
	Define(7, itemId, (short *)itemId_ind);

	Execute();
	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; 
			 i < n;
			 i++)
		{

			// Convert time
			ORACLE_DATEToTime(when[i],
								   &theWhen);

			// Convert Type
			theType	= (AccountDetailTypeEnum)action[i];

			// Check for NULL memo
			if (memo_ind[i] == -1)
				pTheMemo	= NULL;
			else
				pTheMemo	= (char *)&memo[i][0];

			// Check for NULL old item Id
			if (oldItemId_ind[i] == -1)
				pTheOldItemId	= NULL;
			else
				pTheOldItemId	= (char *)&oldItemId[i][0];

			if (itemId_ind[i] == -1)
				theItemId	= 0;
			else
				theItemId	= itemId[i];
			if ( ( theItemId == 0 ) && ( pTheOldItemId ) )
				pTheOldItemId = "Auction Web";


			pDetail	= new clsAccountDetail(id,
										   theWhen,
										   theType,
										   amount[i],
										   pTheMemo,
										   xactionId[i],
										   pTheOldItemId,
										   theItemId);

			pvDetail->push_back(pDetail);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDAOneShot);
	SetStatement(NULL);



	return;

}  // clsDatabaseOracle::GetAccountDetail

//==============================================================================

static char *SQL_GetAccountDetailUntilBefore = 
 "select	TO_CHAR(a.when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			a.action,							\
			a.amount,							\
			a.transaction_id,					\
			a.memo,								\
			xawitems.aw_item,					\
			xitems.item_id						\
  from	ebay_accounts a,						\
		ebay_transaction_xref_aw_item xawitems,	\
		ebay_transaction_xref_item xitems		\
  where	a.id = :id and							\
  a.when <= to_date(:when,'YYYY-MM-DD HH24:MI:SS')\
	and	a.transaction_id = xawitems.id (+)		\
	and	a.transaction_id = xitems.id (+)		\
  order	by a.when asc"; 

static const char *SQL_GetAccountDetailUntilI = 
 "select	TO_CHAR(when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			action,							\
			amount,							\
			transaction_id,					\
			memo,						\
			NULL,						\
			item_id						\
  from	";
static const char *SQL_GetAccountDetailUntilII = 
"  where	id = :id and							\
  when <= to_date(:when,'YYYY-MM-DD HH24:MI:SS')\
  order	by when asc";

//===============================================================================

void clsDatabaseOracle::GetAccountDetailUntil(int id, int tableIndicator,
							AccountDetailVector *pvDetail, time_t until)
{
		// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	// Pointers to arrays of things
	char		when[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][32];
	int			action[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	float		amount[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			xactionId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		memo[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][256];
	sb2			memo_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		oldItemId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][16];
	sb2			oldItemId_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			itemId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	sb2			itemId_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];

	time_t					theWhen;
	char					*pTheMemo;
	char					*pTheOldItemId;
	int						theItemId;
	AccountDetailTypeEnum	theType;
	struct tm	*pcc_until;
	char		ccc_until[32];
	char *table_name;
	char *SQL_GetAccountDetailUntil;

	pcc_until	= localtime(&until);
	TM_STRUCTToORACLE_DATE(pcc_until, ccc_until);

	clsAccountDetail		*pDetail;
	SQL_GetAccountDetailUntil = new char[1024];
	if ( tableIndicator == -1 )
		strcpy( SQL_GetAccountDetailUntil, SQL_GetAccountDetailUntilBefore );
	else
	{
		table_name = new char[31];
		GetTableName( tableIndicator, table_name );
		CombineSQLStatement( SQL_GetAccountDetailUntilI, table_name, 
			SQL_GetAccountDetailUntilII, SQL_GetAccountDetailUntil );
		delete [] table_name;
	}

	// Let's open the cursor
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetAccountDetailUntil);
	delete [] SQL_GetAccountDetailUntil;
	// Bind
	Bind(":id", &id);
	Bind( ":when", ccc_until );
	
	// Define
	Define(1, when[0], sizeof(when[0]));
	Define(2, action);
	Define(3, amount);
	Define(4, xactionId);
	Define(5, memo[0], sizeof(memo[0]), 
		      (short *)memo_ind);
	Define(6, oldItemId[0], sizeof(oldItemId[0]),
			  (short *)oldItemId_ind);
	Define(7, itemId, (short *)itemId_ind);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot);

		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{


		rc = ofen((struct cda_def *)mpCDACurrent,ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; 
			 i < n;
			 i++)
		{

			// Convert time
			ORACLE_DATEToTime(when[i],
								   &theWhen);

			// Convert Type
			theType	= (AccountDetailTypeEnum)action[i];

			// Check for NULL memo
			if (memo_ind[i] == -1)
				pTheMemo	= NULL;
			else
				pTheMemo	= (char *)&memo[i][0];

			// Check for NULL old item Id
			if (oldItemId_ind[i] == -1)
				pTheOldItemId	= NULL;
			else
				pTheOldItemId	= (char *)&oldItemId[i][0];

			if (itemId_ind[i] == -1)
				theItemId	= 0;
			else
				theItemId	= itemId[i];
			if ( ( theItemId == 0 ) && ( pTheOldItemId ) )
				pTheOldItemId = "Auction Web";

			pDetail	= new clsAccountDetail(id,
										   theWhen,
										   theType,
										   amount[i],
										   pTheMemo,
										   xactionId[i],
										   pTheOldItemId,
										   theItemId);

			pvDetail->push_back(pDetail);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDAOneShot);
	SetStatement(NULL);



	return;

}  // clsDatabaseOracle::GetAccountDetail

//==============================================================================
// Lena
static char *SQL_GetAccountDetailSinceUntilBefore = 
 "select	TO_CHAR(a.when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			a.action,							\
			a.amount,							\
			a.transaction_id,					\
			a.memo,								\
			xawitems.aw_item,					\
			xitems.item_id						\
  from	ebay_accounts a,						\
		ebay_transaction_xref_aw_item xawitems,	\
		ebay_transaction_xref_item xitems		\
  where	a.id = :id and							\
  a.when >= to_date(:when,'YYYY-MM-DD HH24:MI:SS')\
  and a.when <= to_date(:until,'YYYY-MM-DD HH24:MI:SS')\
	and	a.transaction_id = xawitems.id (+)		\
	and	a.transaction_id = xitems.id (+)		\
  order	by a.when asc";

static const char *SQL_GetAccountDetailSinceUntilI = 
 "select	TO_CHAR(when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			action,							\
			amount,							\
			transaction_id,					\
			memo,						\
			NULL,						\
			item_id						\
  from	";
static const char *SQL_GetAccountDetailSinceUntilII = 
"  where	id = :id and							\
  when >= to_date(:when,'YYYY-MM-DD HH24:MI:SS')\
  and when <= to_date(:until,'YYYY-MM-DD HH24:MI:SS')\
  order	by when asc";

//===============================================================================

void clsDatabaseOracle::GetAccountDetail(int id, int tableIndicator,
						AccountDetailVector *pvDetail, time_t since, time_t until)
{
		// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	// Pointers to arrays of things
	char		when[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][32];
	int			action[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	float		amount[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			xactionId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		memo[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][256];
	sb2			memo_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		oldItemId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][16];
	sb2			oldItemId_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			itemId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	sb2			itemId_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];

	time_t					theWhen;
	char					*pTheMemo;
	char					*pTheOldItemId;
	int						theItemId;
	AccountDetailTypeEnum	theType;
	struct tm	*pcc_since;
	char		ccc_since[32];
	struct tm	*pcc_until;
	char		ccc_until[32];
	char        *table_name;
	char        *SQL_GetAccountDetailSinceUntil;

	pcc_since	= localtime(&since);
	TM_STRUCTToORACLE_DATE(pcc_since, ccc_since);
	pcc_until	= localtime(&until);
	TM_STRUCTToORACLE_DATE(pcc_until, ccc_until);

	clsAccountDetail		*pDetail;
	SQL_GetAccountDetailSinceUntil = new char[1024];
	if ( tableIndicator == -1 )
		strcpy( SQL_GetAccountDetailSinceUntil, SQL_GetAccountDetailSinceUntilBefore );
	else
	{
		table_name = new char[31];
		GetTableName( tableIndicator, table_name );
		CombineSQLStatement( SQL_GetAccountDetailSinceUntilI, table_name, 
			SQL_GetAccountDetailSinceUntilII, SQL_GetAccountDetailSinceUntil );
		delete [] table_name;
	}
	// Let's open the cursor
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetAccountDetailSinceUntil);
	delete [] SQL_GetAccountDetailSinceUntil;
	// Bind
	Bind(":id", &id);
	Bind( ":when", ccc_since );
	Bind( ":until", ccc_until );
	
	// Define
	Define(1, when[0], sizeof(when[0]));
	Define(2, action);
	Define(3, amount);
	Define(4, xactionId);
	Define(5, memo[0], sizeof(memo[0]), 
		      (short *)memo_ind);
	Define(6, oldItemId[0], sizeof(oldItemId[0]),
			  (short *)oldItemId_ind);
	Define(7, itemId, (short *)itemId_ind);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; 
			 i < n;
			 i++)
		{

			// Convert time
			ORACLE_DATEToTime(when[i],
								   &theWhen);

			// Convert Type
			theType	= (AccountDetailTypeEnum)action[i];

			// Check for NULL memo
			if (memo_ind[i] == -1)
				pTheMemo	= NULL;
			else
				pTheMemo	= (char *)&memo[i][0];

			// Check for NULL old item Id
			if (oldItemId_ind[i] == -1)
				pTheOldItemId	= NULL;
			else
				pTheOldItemId	= (char *)&oldItemId[i][0];

			if (itemId_ind[i] == -1)
				theItemId	= 0;
			else
				theItemId	= itemId[i];
			if ( ( theItemId == 0 ) && ( pTheOldItemId ) )
				pTheOldItemId = "Auction Web";

			pDetail	= new clsAccountDetail(id,
										   theWhen,
										   theType,
										   amount[i],
										   pTheMemo,
										   xactionId[i],
										   pTheOldItemId,
										   theItemId);

			pvDetail->push_back(pDetail);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDAOneShot);
	SetStatement(NULL);



	return;

}  // clsDatabaseOracle::GetAccountDetail

//==============================================================================

//
// GetAccountDetail (LIST Version)
//
void clsDatabaseOracle::GetAccountDetail(int id, int tableIndicator,
										 AccountDetailList *plDetail)
{
	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	// Pointers to arrays of things
	char		when[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][32];
	int			action[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	float		amount[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			xactionId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		memo[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][256];
	sb2			memo_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		oldItemId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][16];
	sb2			oldItemId_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			itemId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	sb2			itemId_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];

	time_t					theWhen;
	char					*pTheMemo;
	char					*pTheOldItemId;
	int						theItemId;
	AccountDetailTypeEnum	theType;

	clsAccountDetail		*pDetail;
	char					*table_name;
	char				*SQL_GetAccountDetail;

	SQL_GetAccountDetail = new char[1024];
	if ( tableIndicator == -1 )
		strcpy( SQL_GetAccountDetail, SQL_GetAccountDetailBefore );
	else
	{
		table_name = new char[31];
		GetTableName( tableIndicator, table_name );
		CombineSQLStatement( SQL_GetAccountDetailI, table_name, 
			SQL_GetAccountDetailII, SQL_GetAccountDetail );
		delete [] table_name;
	}


	// Let's open the cursor
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetAccountDetail);
	delete [] SQL_GetAccountDetail;
	// Bind
	Bind(":id", &id);
	
	// Define
	Define(1, when[0], sizeof(when[0]));
	Define(2, action);
	Define(3, amount);
	Define(4, xactionId);
	Define(5, memo[0], sizeof(memo[0]), 
		      (short *)memo_ind);
	Define(6, oldItemId[0], sizeof(oldItemId[0]),
			  (short *)oldItemId_ind);
	Define(7, itemId, (short *)itemId_ind);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; 
			 i < n;
			 i++)
		{

			// Convert time
			ORACLE_DATEToTime(when[i],
								   &theWhen);

			// Convert Type
			theType	= (AccountDetailTypeEnum)action[i];

			// Check for NULL memo
			if (memo_ind[i] == -1)
				pTheMemo	= NULL;
			else
				pTheMemo	= (char *)&memo[i][0];

			// Check for NULL old item Id
			if (oldItemId_ind[i] == -1)
				pTheOldItemId	= NULL;
			else
				pTheOldItemId	= (char *)&oldItemId[i][0];

			if (itemId_ind[i] == -1)
				theItemId	= 0;
			else
				theItemId	= itemId[i];
			if ( ( theItemId == 0 ) && ( pTheOldItemId ) )
				pTheOldItemId = "Auction Web";
			pDetail	= new clsAccountDetail(id,
										   theWhen,
										   theType,
										   amount[i],
										   pTheMemo,
										   xactionId[i],
										   pTheOldItemId,
										   theItemId);

			plDetail->push_back(clsAccountDetailPtr(pDetail));
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDAOneShot);
	SetStatement(NULL);


	return;
}

//
// GetAccountDetailForItem
//
// Lena
static char *SQL_GetAccountDetailForItemBefore = 
 "select	TO_CHAR(a.when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			a.action,							\
			a.amount,							\
			a.transaction_id,					\
			a.memo,								\
			NULL,								\
			xitems.item_id						\
  from	ebay_accounts a,						\
		ebay_transaction_xref_item xitems		\
  where	a.id = :id								\
	and	xitems.id = a.transaction_id			\
	and	xitems.item_id = :itemid				\
  order	by a.when asc";

static const char *SQL_GetAccountDetailForItemI = 
 "select	TO_CHAR(when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			action,							\
			amount,							\
			transaction_id,					\
			memo,								\
			NULL,								\
			item_id						\
  from	";
static const char *SQL_GetAccountDetailForItemII =
 " where	id = :id								\
	and	item_id = :itemid				\
  order	by when asc";

void clsDatabaseOracle::GetAccountDetailForItem(int id,
												int tableIndicator,
												int item,
										 AccountDetailVector *pvDetail)
{
	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	// Pointers to arrays of things
	char		when[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][32];
	int			action[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	float		amount[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			xactionId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		memo[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][256];
	sb2			memo_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		oldItemId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][16];
	sb2			oldItemId_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			itemId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	sb2			itemId_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];

	time_t					theWhen;
	char					*pTheMemo;
	char					*pTheOldItemId;
	int						theItemId;
	AccountDetailTypeEnum	theType;

	clsAccountDetail		*pDetail;
	char					*table_name;
	char				*SQL_GetAccountDetailForItem;
	SQL_GetAccountDetailForItem = new char[1024];
	if ( tableIndicator == -1 )
		strcpy( SQL_GetAccountDetailForItem, SQL_GetAccountDetailForItemBefore );
	else
	{
		table_name = new char[31];
		GetTableName( tableIndicator, table_name );
		CombineSQLStatement( SQL_GetAccountDetailForItemI, table_name, 
			SQL_GetAccountDetailForItemII, SQL_GetAccountDetailForItem );
		delete [] table_name;
	}

	// Let's open the cursor
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetAccountDetailForItem);
	delete [] SQL_GetAccountDetailForItem;
	// Bind
	Bind(":id", &id);
	Bind(":itemid", &item);
	
	// Define
	Define(1, when[0], sizeof(when[0]));
	Define(2, action);
	Define(3, amount);
	Define(4, xactionId);
	Define(5, memo[0], sizeof(memo[0]), 
		      (short *)memo_ind);
	Define(6, oldItemId[0], sizeof(oldItemId[0]),
			  (short *)oldItemId_ind);
	Define(7, itemId, (short *)itemId_ind);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; 
			 i < n;
			 i++)
		{

			// Convert time
			ORACLE_DATEToTime(when[i],
								   &theWhen);

			// Convert Type
			theType	= (AccountDetailTypeEnum)action[i];

			// Check for NULL memo
			if (memo_ind[i] == -1)
				pTheMemo	= NULL;
			else
				pTheMemo	= (char *)&memo[i][0];

			// Check for NULL old item Id
			if (oldItemId_ind[i] == -1)
				pTheOldItemId	= NULL;
			else
				pTheOldItemId	= (char *)&oldItemId[i][0];

			if (itemId_ind[i] == -1)
				theItemId	= 0;
			else
				theItemId	= itemId[i];

			pDetail	= new clsAccountDetail(theWhen,
										   theType,
										   amount[i],
										   pTheMemo,
										   xactionId[i],
										   pTheOldItemId,
										   theItemId);

			pvDetail->push_back(pDetail);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDAOneShot);
	SetStatement(NULL);


	return;
}


//
// GetAccountDetailForItem
//
static const char *SQL_GetAccountDetailForAWItem = 
 "select	TO_CHAR(a.when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			a.action,							\
			a.amount,							\
			a.transaction_id,					\
			a.memo,								\
			xitems.aw_item,						\
			NULL								\
  from	ebay_accounts a,						\
		ebay_transaction_xref_aw_item xitems	\
  where	a.id = :id								\
	and	xitems.id = a.transaction_id			\
	and	xitems.aw_item = :itemid				\
  order	by a.when asc";

void clsDatabaseOracle::GetAccountDetailForAWItem(int id,
												  char *pItemId,
										 AccountDetailVector *pvDetail)
{
	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	// Pointers to arrays of things
	char		when[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][32];
	int			action[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	float		amount[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			xactionId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		memo[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][256];
	sb2			memo_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		oldItemId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][16];
	sb2			oldItemId_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			itemId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	sb2			itemId_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];

	time_t					theWhen;
	char					*pTheMemo;
	char					*pTheOldItemId;
	int						theItemId;
	AccountDetailTypeEnum	theType;

	clsAccountDetail		*pDetail;

	// Let's open the cursor
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetAccountDetailForAWItem);

	// Bind
	Bind(":id", &id);
	Bind(":itemid", pItemId);
	
	// Define
	Define(1, when[0], sizeof(when[0]));
	Define(2, action);
	Define(3, amount);
	Define(4, xactionId);
	Define(5, memo[0], sizeof(memo[0]), 
		      (short *)memo_ind);
	Define(6, oldItemId[0], sizeof(oldItemId[0]),
			  (short *)oldItemId_ind);
	Define(7, itemId, (short *)itemId_ind);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; 
			 i < n;
			 i++)
		{

			// Convert time
			ORACLE_DATEToTime(when[i],
								   &theWhen);

			// Convert Type
			theType	= (AccountDetailTypeEnum)action[i];

			// Check for NULL memo
			if (memo_ind[i] == -1)
				pTheMemo	= NULL;
			else
				pTheMemo	= (char *)&memo[i][0];

			// Check for NULL old item Id
			if (oldItemId_ind[i] == -1)
				pTheOldItemId	= NULL;
			else
				pTheOldItemId	= (char *)&oldItemId[i][0];

			if (itemId_ind[i] == -1)
				theItemId	= 0;
			else
				theItemId	= itemId[i];

			pDetail	= new clsAccountDetail(id,
										   theWhen,
										   theType,
										   amount[i],
										   pTheMemo,
										   xactionId[i],
										   pTheOldItemId,
										   theItemId);

			pvDetail->push_back(pDetail);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDAOneShot);
	SetStatement(NULL);


	return;
}

//
// GetAccountDetailByType
//
// Lena
static char *SQL_GetAccountDetailByTypeBefore = 
 "select	TO_CHAR(a.when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			a.action,							\
			a.amount,							\
			a.transaction_id,					\
			a.memo								\
  from	ebay_accounts a							\
  where	a.id = :id								\
  and	a.action = :type						\
  order	by a.when asc";

static const char *SQL_GetAccountDetailByTypeI = 
 "select	TO_CHAR(when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			action,							\
			amount,							\
			transaction_id,					\
			memo								\
  from	";
static const char *SQL_GetAccountDetailByTypeII = 
"  where	id = :id								\
  and	action = :type						\
  order	by when asc";

void clsDatabaseOracle::GetAccountDetailByType(int id, int tableIndicator,
											   AccountDetailTypeEnum type,
											   AccountDetailVector *pvDetail)
{
	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	// Pointers to arrays of things
	char		when[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][32];
	int			action[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	float		amount[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	int			xactionId[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];
	char		memo[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE][256];
	sb2			memo_ind[ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE];

	time_t					theWhen;
	char					*pTheMemo;
	AccountDetailTypeEnum	theType;

	clsAccountDetail		*pDetail;
	char					*table_name;
	char				*SQL_GetAccountDetailByType;

	SQL_GetAccountDetailByType = new char[1024];
	if ( tableIndicator == -1 )
		strcpy( SQL_GetAccountDetailByType, SQL_GetAccountDetailByTypeBefore );
	else
	{
		table_name = new char[31];
		GetTableName( tableIndicator, table_name );
		CombineSQLStatement( SQL_GetAccountDetailByTypeI, table_name, 
			SQL_GetAccountDetailByTypeII, SQL_GetAccountDetailByType );
		delete [] table_name;
	}

	// Let's open the cursor
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetAccountDetailByType);
	delete [] SQL_GetAccountDetailByType;
	// Bind
	Bind(":id", &id);
	Bind(":type", (int *)&type);
	
	// Define
	Define(1, when[0], sizeof(when[0]));
	Define(2, action);
	Define(3, amount);
	Define(4, xactionId);
	Define(5, memo[0], sizeof(memo[0]), 
		      (short *)memo_ind);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORACLE_ACCOUNT_DETAIL_ARRAY_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; 
			 i < n;
			 i++)
		{

			// Convert time
			ORACLE_DATEToTime(when[i],
								   &theWhen);

			// Convert Type
			theType	= (AccountDetailTypeEnum)action[i];

			// Check for NULL memo
			if (memo_ind[i] == -1)
				pTheMemo	= NULL;
			else
				pTheMemo	= (char *)&memo[i][0];


			pDetail	= new clsAccountDetail(id,
										   theWhen,
										   theType,
										   amount[i],
										   pTheMemo,
										   xactionId[i],
										   NULL,
										   0);

			pvDetail->push_back(pDetail);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDAOneShot);
	SetStatement(NULL);


	return;
}
//
// DeleteAccountDetailByTime
//
// Lena
static char *SQL_DeleteAccountDetailByTimeBefore = 
" delete from ebay_accounts								\
	where		id = :id								\
	and			when = 									\
					TO_DATE(:when,						\
							'YYYY-MM-DD HH24:MI:SS')";

static const char *SQL_DeleteAccountDetailByTimeI = 
" delete from ";
static const char *SQL_DeleteAccountDetailByTimeII = 
"	where		id = :id								\
	and			when = 									\
					TO_DATE(:when,						\
							'YYYY-MM-DD HH24:MI:SS')";

void clsDatabaseOracle::DeleteAccountDetailByTime(int id, int tableIndicator,
												  time_t theTime)
{
	struct tm			*pTheTime;
	char				when[32];
	char					*table_name;
	char					*SQL_DeleteAccountDetailByTime;

	SQL_DeleteAccountDetailByTime = new char[1024];
	if ( tableIndicator == -1 )
		strcpy( SQL_DeleteAccountDetailByTime, SQL_DeleteAccountDetailByTimeBefore );
	else
	{
		table_name = new char[31];
		GetTableName( tableIndicator, table_name );
		CombineSQLStatement( SQL_DeleteAccountDetailByTimeI, table_name, 
			SQL_DeleteAccountDetailByTimeII, SQL_DeleteAccountDetailByTime );
		delete [] table_name;
	}

	// OpenAndParse
	OpenAndParse(&mpCDAOneShot,
				 SQL_DeleteAccountDetailByTime);
	delete [] SQL_DeleteAccountDetailByTime;
   // Convert
   pTheTime = localtime(&theTime);
   TM_STRUCTToORACLE_DATE(pTheTime, when);

	// Bind
	Bind(":id", &id);
	Bind(":when", (char *)when);

	// Do
	Execute();

	// Commit
	Commit();

	// Close
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}

//
// DeleteAccountBalance for user
//
static const char *SQL_DeleteAccountBalance = 
" delete from ebay_account_balances						\
	where		id = :id";

void clsDatabaseOracle::DeleteAccountBalance(int id)
{

	// OpenAndParse
	OpenAndParse(&mpCDAOneShot,
				 SQL_DeleteAccountBalance);

	// Bind
	Bind(":id", &id);

	// Do
	Execute();

	// Commit
	Commit();

	// Close
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}


//
//	GetLastUpdateFromAWTime
//
static const char *SQL_GetLastUpdateFromAWTime =
 "select	TO_CHAR(last_modified,				\
					'YYYY-MM-DD HH24:MI:SS')	\
	from	ebay_migrated_accounts				\
	where	id = :id";


time_t clsDatabaseOracle::GetLastUpdateFromAWTime(int id)
{
	char	last_update[32];
	time_t	theTime;

	// OpenAndParse
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetLastUpdateFromAWTime);

	// Bind and define
	Bind(":id", &id);
	Define(1, last_update, sizeof(last_update));

	// Get it
	ExecuteAndFetch();

	if (!CheckForNoRowsFound())
	{
		ORACLE_DATEToTime(last_update,
						  &theTime, false);
	}
	else
		theTime	= 0;

	
	// Close
	Close(&mpCDAOneShot);
	SetStatement(NULL);


	return theTime;
}

//
// SetLastUpdateFromAWTime
//
static const char *SQL_SetLastUpdateFromAWTime =
" update ebay_migrated_accounts							\
	set last_modified =  TO_DATE(:when,					\
								 'YYYY-MM-DD HH24:MI:SS')	\
	where id = :id";

static const char *SQL_AddLastUpdateFromAWTime = 
"	insert into ebay_migrated_accounts					\
	(	id,												\
		last_modified									\
	)													\
	values												\
	(	:id,											\
      TO_DATE(:when,                   					\
            'YYYY-MM-DD HH24:MI:SS')	  				\
	)";

void clsDatabaseOracle::SetLastUpdateFromAWTime(int id,
												time_t when)
{
	struct tm			*pTheTime;
	char				theTime[32];

	// Convert
	pTheTime	= localtime(&when);
	TM_STRUCTToORACLE_DATE(pTheTime, theTime);

	// Open + Parse
	OpenAndParse(&mpCDAOneShot, SQL_SetLastUpdateFromAWTime);

	// Bind
	Bind(":id", &id);
	Bind(":when", theTime);

	// Execute
	Execute();

	// See if we need to add
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		OpenAndParse(&mpCDAOneShot, SQL_AddLastUpdateFromAWTime);
		Bind(":id", &id);
		Bind(":when", theTime);
		Execute();
	}

	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);
}

//
// GetAWAccountCrossReference
//
static const char *SQL_GetAWAccountCrossReference = 
"select	awid										\
 from	ebay_account_xref							\
 where	id = :id";

void clsDatabaseOracle::GetAWAccountCrossReference(int id,
												   int *pAWId)
{
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetAWAccountCrossReference);

	Bind(":id", &id);
	Define(1, pAWId);
	
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
		*pAWId	= 0;

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// GeteBayAccountCrossReference
//
static const char *SQL_GeteBayAccountCrossReference = 
"select	id										\
 from	ebay_account_xref							\
 where	awid = :id";

void clsDatabaseOracle::GeteBayAccountCrossReference(int id,
												     int *pEBayId)
{
	OpenAndParse(&mpCDAOneShot,
				 SQL_GeteBayAccountCrossReference);

	Bind(":id", &id);
	Define(1, pEBayId);
	
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
		*pEBayId	= 0;

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}



//
// AddAWAccountCrossReference
//
static const char *SQL_UpdateAWAccountCrossReference = 
" update ebay_account_xref \
	set awid = :awid where id = :id";

static const char *SQL_AddAWAccountCrossReference = 
"	insert into ebay_account_xref					\
	(	id,											\
		awid										\
	)												\
	values											\
	(	:id,										\
		:awid										\
	)";

void clsDatabaseOracle::AddAWAccountCrossReference(int id,
												   int awAccountId)
{
	// Try update first
	OpenAndParse(&mpCDAOneShot, SQL_UpdateAWAccountCrossReference);
	Bind(":id", &id);
	Bind(":awid", &awAccountId);

	Execute();
 	if (((struct cda_def *)mpCDACurrent)->rpc != 0)
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}

	// Open + Parse
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	OpenAndParse(&mpCDAOneShot, SQL_AddAWAccountCrossReference);

	// Bind
	Bind(":id", &id);
	Bind(":awid", &awAccountId);

	// Execute
	Execute();

	// See if we need to add
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// AddAccountAWItemXref
//
static const char *SQL_AddAccountAWItemXref = 
"insert into ebay_transaction_xref_aw_item			\
	(	id,											\
		aw_item										\
	)												\
	values											\
	(	:id,										\
		:itemid										\
	)";

void clsDatabaseOracle::AddAccountAWItemXref(int count,
											 unsigned int *pIds,
											 char *pItemIds)
{
	int		rc;

	OpenAndParse(&mpCDAAddAccountAWItemXref, 
				 SQL_AddAccountAWItemXref);

	Bind(":id", (unsigned int *)pIds);
	Bind(":itemid", pItemIds, 13);


	rc	= oexn((cda_def *)mpCDACurrent, count, 0);
	Check(rc);

	Commit();

	Close(&mpCDAAddAccountAWItemXref);
	SetStatement(NULL);

	return;
}


//
// AddAccountItemXref
//
static const char *SQL_AddAccountItemXref = 
"insert into ebay_transaction_xref_item				\
	(	id,											\
		item_id										\
	)												\
	values											\
	(	:id,										\
		:itemid										\
	)";

void clsDatabaseOracle::AddAccountItemXref(TransactionId id,
											 int itemId)
{
//	int		rc;

	OpenAndParse(&mpCDAAddAccountItemXref, 
				 SQL_AddAccountItemXref);

	Bind(":id", (unsigned int *)&id);
	Bind(":itemid", &itemId);


	Execute();
	Commit();

	Close(&mpCDAAddAccountItemXref);
	SetStatement(NULL);

	return;
}

//
//	GetUsersWithAccounts
//
#define ORA_ACCOUNTS_ARRAYSIZE	500

static const char *SQL_GetCountUsersWithAccounts =
"select count(*) from ebay_account_balances";

static const char *SQL_GetUsersWithAccounts =
"select	distinct(id)											\
	from ebay_refunds where id > 659110";


void clsDatabaseOracle::GetUsersWithAccounts(vector<unsigned int> *pvIds)
{
	int		count;
	int		id[ORA_ACCOUNTS_ARRAYSIZE];
	int		rowsFetched;
	int		rc;
	int		i,n;

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetCountUsersWithAccounts);

	// Define
	Define(1, &count);

	// Execute
	ExecuteAndFetch();

	// Close
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	// Make the vector nice and FAT
	pvIds->reserve(count);

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetUsersWithAccounts);

	// Define
	Define(1, (int *)id);

	// Execute
	Execute();

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORA_ACCOUNTS_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_ACCOUNTS_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pvIds->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// GetUserAccounts
//
static const char *SQL_GetUserAccounts =
 "select        id,                                                     \
                        TO_CHAR(last_modified,                          \
                                        'YYYY-MM-DD HH24:MI:SS'),       \
                        balance,                                        \
                        TO_CHAR(pastduebase,                            \
                                        'YYYY-MM-DD HH24:MI:SS'),       \
                        pastdue30days, pastdue60days, pastdue90days,    \
                        pastdue120days, pastdueover120days,             \
                        cc_first4digits,                                \
                        TO_CHAR(cc_expiration_date,                     \
                                        'YYYY-MM-DD HH24:MI:SS'),       \
                        TO_CHAR(cc_data_last_modified,                  \
                                        'YYYY-MM-DD HH24:MI:SS'),       \
                        TO_CHAR(last_cc_expired_notice_sent,            \
                                        'YYYY-MM-DD HH24:MI:SS')        \
  from  ebay_account_balances";

void clsDatabaseOracle::GetUserAccounts(AccountsVector *pvAccounts)

{
        int             id[ORA_ACCOUNTS_ARRAYSIZE];
        char            last_modified[ORA_ACCOUNTS_ARRAYSIZE][32];
        time_t          the_last_modified;
        float		balance[ORA_ACCOUNTS_ARRAYSIZE];
        sb2                     balance_ind[ORA_ACCOUNTS_ARRAYSIZE];
        char            pastduebase[ORA_ACCOUNTS_ARRAYSIZE][32];
        sb2                     pastduebase_ind[ORA_ACCOUNTS_ARRAYSIZE];
        time_t          the_pastduebase;
        float		pastdue30days[ORA_ACCOUNTS_ARRAYSIZE];
        sb2                     pastdue30days_ind[ORA_ACCOUNTS_ARRAYSIZE];
        float		pastdue60days[ORA_ACCOUNTS_ARRAYSIZE];
        sb2                     pastdue60days_ind[ORA_ACCOUNTS_ARRAYSIZE];
        float		pastdue90days[ORA_ACCOUNTS_ARRAYSIZE];
        sb2                     pastdue90days_ind[ORA_ACCOUNTS_ARRAYSIZE];
        float		pastdueover120days[ORA_ACCOUNTS_ARRAYSIZE];
        sb2                     pastdueover120days_ind[ORA_ACCOUNTS_ARRAYSIZE];
        float		pastdue120days[ORA_ACCOUNTS_ARRAYSIZE];
        sb2                     pastdue120days_ind[ORA_ACCOUNTS_ARRAYSIZE];
        int             cc_first4digits[ORA_ACCOUNTS_ARRAYSIZE];
        sb2                     cc_first4digits_ind[ORA_ACCOUNTS_ARRAYSIZE];
        char            cc_expiration_date[ORA_ACCOUNTS_ARRAYSIZE][32];
        sb2                     cc_expiration_date_ind[ORA_ACCOUNTS_ARRAYSIZE];
        time_t          the_cc_expiration_date;
        char            cc_data_last_modified[ORA_ACCOUNTS_ARRAYSIZE][32];
        sb2                     cc_data_last_modified_ind[ORA_ACCOUNTS_ARRAYSIZE];
        time_t          the_cc_data_last_modified;
        char            last_cc_expired_notice_sent[ORA_ACCOUNTS_ARRAYSIZE][32];
        sb2                     last_cc_expired_notice_sent_ind[ORA_ACCOUNTS_ARRAYSIZE];
        time_t          the_last_cc_expired_notice_sent;
        int             rowsFetched;
        int             rc;
        int             i,n;
        clsAccount      *pAccount;
        sUserAccount    *sAccount;


        // Open and Parse
        OpenAndParse(&mpCDAOneShot,
                                 SQL_GetUserAccounts);

        // Define
/*
        Define(1,  (int *)&id);
        Define(2,  (char *)&last_modified, sizeof(last_modified[0]));
        Define(3,  (float *)&balance, balance_ind);
        Define(4,  pastduebase[0], sizeof(pastduebase[0]), pastduebase_ind);
        Define(5,  (float *)&pastdue30days, &pastdue30days_ind[0]);
        Define(6,  (float *)&pastdue60days, &pastdue60days_ind[0]);
        Define(7,  (float *)&pastdue90days, &pastdue90days_ind[0]);
        Define(8,  (float *)&pastdue120days, &pastdue120days_ind[0]);
        Define(9,  (float *)&pastdueover120days, &pastdue120days_ind[0]);
        Define(10, (int *)&cc_first4digits, &cc_first4digits_ind[0]);
        Define(11, cc_expiration_date[0], sizeof(cc_expiration_date[0]),
                                        cc_expiration_date_ind);
        Define(12, cc_data_last_modified[0], sizeof(cc_data_last_modified[0]),
                                        cc_data_last_modified_ind);
        Define(13, last_cc_expired_notice_sent[0], sizeof(last_cc_expired_notice_sent[0]),
                                        last_cc_expired_notice_sent_ind);
*/

	Define(1,  id);
        Define(2,  (char *)last_modified[0], sizeof(last_modified[0]));
        Define(3,  balance, balance_ind);
        Define(4,  (char*)pastduebase[0], sizeof(pastduebase[0]),
			pastduebase_ind);
        Define(5,  &pastdue30days[0], &pastdue30days_ind[0]);
        Define(6,  &pastdue60days[0], &pastdue60days_ind[0]);
        Define(7,  &pastdue90days[0], &pastdue90days_ind[0]);
        Define(8,  &pastdue120days[0], &pastdue120days_ind[0]);
        Define(9,  &pastdueover120days[0], &pastdueover120days_ind[0]);
        Define(10, &cc_first4digits[0], &cc_first4digits_ind[0]);
        Define(11, (char*)cc_expiration_date[0],
			sizeof(cc_expiration_date[0]),
                                        cc_expiration_date_ind);
        Define(12, (char*)cc_data_last_modified[0],
			sizeof(cc_data_last_modified[0]),
                                        cc_data_last_modified_ind);
        Define(13, (char*)last_cc_expired_notice_sent[0],
			sizeof(last_cc_expired_notice_sent[0]),
			last_cc_expired_notice_sent_ind);

        // Execute
        Execute();

        // Now we fetch until we're done
        rowsFetched = 0;
        do
        {

                rc = ofen((struct cda_def *)mpCDACurrent,ORA_ACCOUNTS_ARRAYSIZE);

                if ((rc < 0 || rc >= 4)  &&
                        ((struct cda_def *)mpCDACurrent)->rc != 1403)   // something wrong
                {
                        Check(rc);
                        Close(&mpCDAOneShot);
                        SetStatement(NULL);
                        return;
                }

                // rpc is cumulative, so find out how many rows to display this time
                // (always <= ORA_ACCOUNTS_ARRAYSIZE).
                n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
                rowsFetched += n;

                for (i=0; i < n; i++)
                {
                        // NULL Check
                        if (cc_first4digits_ind[i] == -1)
                                cc_first4digits[i] = 0;
                        if (balance_ind[i] == -1)
                                balance[i] = 0.0;
                        if (pastdue30days_ind[i] == -1)
                                pastdue30days[i] = 0.0;
                        if (pastdue60days_ind[i] == -1)
                                pastdue60days[i] = 0.0;
                        if (pastdue90days_ind[i] == -1)
                                pastdue90days[i] = 0.0;
                        if (pastdue120days_ind[i] == -1)
                                pastdue120days[i] = 0.0;
                        if (pastdueover120days_ind[i] == -1)
                                pastdueover120days[i] = 0.0;

                        // Convert time
                        ORACLE_DATEToTime(last_modified[i], &the_last_modified);
                        if (pastduebase_ind[i] != -1)
                                ORACLE_DATEToTime(pastduebase[i], &the_pastduebase);
                        else
                                the_pastduebase = (time_t)0;

                        if (cc_expiration_date_ind[i] != -1)
                                ORACLE_DATEToTime(cc_expiration_date[i],
                                                   &the_cc_expiration_date);
                        else
                                the_cc_expiration_date = (time_t)0;

                        if (cc_data_last_modified_ind[i] != -1)
                                ORACLE_DATEToTime(cc_data_last_modified[i],
                                                  &the_cc_data_last_modified);
                        else
                                the_cc_data_last_modified = (time_t)0;

                        if (last_cc_expired_notice_sent_ind[i] != -1)
                                ORACLE_DATEToTime(last_cc_expired_notice_sent[i],
                                                  &the_last_cc_expired_notice_sent);
                        else
                                the_last_cc_expired_notice_sent = (time_t)0;

                        sAccount = new sUserAccount;

                        pAccount = new clsAccount(
                                                        balance[i],
                                                        the_last_modified,
                                                        the_pastduebase,
                                                        pastdue30days[i],
                                                        pastdue60days[i],
                                                        pastdue90days[i],
                                                        pastdue120days[i],
                                                        pastdueover120days[i],
                                                        cc_first4digits[i],
                                                        the_cc_expiration_date,
                                                        the_cc_data_last_modified,
                                                        the_last_cc_expired_notice_sent );

                        sAccount->id = id[i];
                        sAccount->pAccount = pAccount;
                        pvAccounts->push_back(sAccount);
                }

        } while (!CheckForNoRowsFound());

        Close(&mpCDAOneShot);
        SetStatement(NULL);

        return;
}

//===============================================================================

static const char *SQL_GetUsersWithAccountsRange =
"select	id											\
	from ebay_account_balances where id >= :idStart \
	and id < :idEnd and table_indicator = -1 order by id";

//==============================================================================

void clsDatabaseOracle::GetUsersWithUnsplitAccountsRange(
									vector<unsigned int> *pvIds,
									int idStart, int idEnd )
{
	int		id[ORA_ACCOUNTS_ARRAYSIZE];
	int		rowsFetched;
	int		rc;
	int		i,n;

	// Open and Parse
	pvIds->reserve(idEnd - idStart);

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetUsersWithAccountsRange);

	// Bind
	Bind(":idStart", &idStart);
	Bind(":idEnd", &idEnd);


	// Define
	Define(1, id);

	// Execute
	Execute();

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORA_ACCOUNTS_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_ACCOUNTS_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pvIds->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}
//================================================================================

//inna-start
static const char *SQL_GetAllUsersWithAccountsRange =
"select	id											\
	from ebay_account_balances where id >= :idStart \
	and id < :idEnd order by id";

void clsDatabaseOracle::GetAllUsersWithAccountsRange(
									vector<unsigned int> *pvIds,
									int idStart, int idEnd )
{
	int		id[ORA_ACCOUNTS_ARRAYSIZE];
	int		rowsFetched;
	int		rc;
	int		i,n;

	// Open and Parse
	pvIds->reserve(idEnd - idStart);

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetAllUsersWithAccountsRange);

	// Bind
	Bind(":idStart", &idStart);
	Bind(":idEnd", &idEnd);


	// Define
	Define(1, &id[0]);

	// Execute
	Execute();

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORA_ACCOUNTS_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_ACCOUNTS_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pvIds->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}//inna-end

//
// UpdateAccountPastDue
//
static const char *SQL_UpdateAccountPastDue =
" update	ebay_account_balances						\
	set		pastduebase =								\
				TO_DATE(:base,								\
						'YYYY-MM-DD HH24:MI:SS'),		\
			pastDue30Days =		:pd30,				\
			pastDue60Days =		:pd60,				\
			pastDue90Days =		:pd90,				\
			pastDue120Days =		:pd120,				\
			pastDueOver120Days	= :pdover120		\
	where	id = :id";

void clsDatabaseOracle::UpdateAccountPastDue(int id,
											 time_t pastDueBase,
											 double	pastDue30Days,
											 double pastDue60Days,
											 double pastDue90Days,
											 double pastDue120Days,
											 double pastDueOver120Days)
{
	char	cPastDueBase[32];
	float	fPastDue30Days;
	float	fPastDue60Days;
	float	fPastDue90Days;
	float	fPastDue120Days;
	float	fPastDueOver120Days;

	TimeToORACLE_DATE(pastDueBase, cPastDueBase);
	fPastDue30Days			= pastDue30Days;
	fPastDue60Days			= pastDue60Days;
	fPastDue90Days			= pastDue90Days;
	fPastDue120Days			= pastDue120Days;
	fPastDueOver120Days		= pastDueOver120Days;

	
	// Open 
	OpenAndParse(&mpCDAOneShot,
				 SQL_UpdateAccountPastDue);

	Bind(":id", &id);
	Bind(":base", cPastDueBase);
	Bind(":pd30", &fPastDue30Days);
	Bind(":pd60", &fPastDue60Days);
	Bind(":pd90", &fPastDue90Days);
	Bind(":pd120", &fPastDue120Days);
	Bind(":pdover120", &fPastDueOver120Days);

	Execute();

	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


//
// UpdateAccountDetailTime
//
// This routine seems a bit stupid, but I didn't want anyone to get
// themselves in trouble by inadvertently replacing ALL of an account 
// record
//
// Lena
static char *SQL_UpdateAccountDetailTimeBefore =
" update ebay_accounts										\
	set		when =		TO_DATE(:when,					\
								'YYYY-MM-DD HH24:MI:SS')	\
	where		id = :id											\
	and		transaction_id = :xid";

static const char *SQL_UpdateAccountDetailTimeI =
" update ";
static const char *SQL_UpdateAccountDetailTimeII =
"	set		when =		TO_DATE(:when,					\
								'YYYY-MM-DD HH24:MI:SS')	\
	where		id = :id											\
	and		transaction_id = :xid";

void clsDatabaseOracle::UpdateAccountDetailTime(int userId, int tableIndicator,
												clsAccountDetail *pDetail)
{
	struct tm		*pLocalWhen;
	char			cWhen[32];
	char            *table_name;
	char      *SQL_UpdateAccountDetailTime;
	unsigned int				transactionId;

	GetNextTransactionId(&transactionId);
	SQL_UpdateAccountDetailTime = new char[1024];
	if ( tableIndicator == -1 )
		strcpy( SQL_UpdateAccountDetailTime, SQL_UpdateAccountDetailTimeBefore );
	else
	{
		table_name = new char[31];
		GetTableName( tableIndicator, table_name );
		CombineSQLStatement( SQL_UpdateAccountDetailTimeI, table_name, 
			SQL_UpdateAccountDetailTimeII, SQL_UpdateAccountDetailTime );
		delete [] table_name;
	}
	
	pLocalWhen	= localtime(&pDetail->mTime);
	TM_STRUCTToORACLE_DATE(pLocalWhen, cWhen);

	OpenAndParse(&mpCDAOneShot,
				 SQL_UpdateAccountDetailTime);
	delete [] SQL_UpdateAccountDetailTime;
	Bind(":when", cWhen);
	Bind(":id", &userId);
	Bind(":xid", &pDetail->mTransactionId);

	Execute();

	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// GetLastInvoiceDate
//
// ** NOTE **
//	This code gets the last invoice date availible in ebay_accounts.
//	The "normal" use for this is to get the last invoice date, and then
//	get all users invoiced using this date minus some period as the start
//	and this date as the end date. 
//
// Lena
static const char *SQL_GetLastInvoiceDate = 
 "select	TO_CHAR(max(when),						\
						'YYYY-MM-DD HH24:MI:SS')	\
  from		ebay_interim_balances";

time_t clsDatabaseOracle::GetLastInvoiceDate()
{
	char	lastInvoiceDate[32];
//	sb2		lastInvoiceDate_ind = 0;
	time_t	lastInvoiceTime;

	// Open + Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetLastInvoiceDate);

	Define(1, lastInvoiceDate, sizeof(lastInvoiceDate));

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

//	Commented out jpg; lastInvoiceDate_ind appears invariant.
//	if (lastInvoiceDate_ind == -1)
//		return (time_t)0;
	
	ORACLE_DATEToTime(lastInvoiceDate, &lastInvoiceTime);

	return lastInvoiceTime;

}

//
//	GetUsersWithAccounts
//
#define ORA_INVOICED_ARRAYSIZE	50

// Lena
static const char *SQL_GetInvoicesForUserCC =
" select a.id,									\
		TO_CHAR( a.when,'YYYY-MM-DD HH24:MI:SS'),\
		a.balance									\
	from ebay_interim_balances	a, ebay_user_info b	\
	where a.id =:id and		\
	a.when >= TO_DATE(:istart,'YYYY-MM-DD HH24:MI:SS') \
	and a.when <= TO_DATE(:iend, 'YYYY-MM-DD HH24:MI:SS') \
	and a.id=b.id and b.credit_card_on_file='1' \
	order by a.id";

static const char *SQL_GetInvoicesCC = 
" select a.id,									\
		TO_CHAR( a.when,'YYYY-MM-DD HH24:MI:SS'),\
		a.balance									\
	from ebay_interim_balances	a, ebay_user_info b	\
	where \
	a.when >= TO_DATE(:istart,'YYYY-MM-DD HH24:MI:SS') \
	and a.when <= TO_DATE(:iend, 'YYYY-MM-DD HH24:MI:SS') \
	and a.id=b.id and b.credit_card_on_file='1' \
	order by a.id";

static const char *SQL_GetInvoicesForUser = 
" select a.id,									\
		TO_CHAR( a.when,'YYYY-MM-DD HH24:MI:SS'),\
		a.balance									\
	from ebay_interim_balances	a	\
	where a.id =:id and		\
	a.when >= TO_DATE(:istart,'YYYY-MM-DD HH24:MI:SS') \
	and a.when <= TO_DATE(:iend, 'YYYY-MM-DD HH24:MI:SS') \
	order by a.when desc";


void clsDatabaseOracle::GetInvoices(time_t invoicedStart,
									time_t invoicedEnd,
									InterimBalanceList *plBalances,
									int requestedId, bool all)
{
	char		cInvoiceStart[32];
	char		cInvoiceEnd[32];

	int		id[ORA_INVOICED_ARRAYSIZE];
	char	when[ORA_INVOICED_ARRAYSIZE][32];
	float		balance[ORA_INVOICED_ARRAYSIZE];

	time_t						theWhen;

	clsInterimBalance		*pInterimBalance;


	int			rowsFetched;
	int			rc;
	int			i,n;
	// Convert Dates
	TimeToORACLE_DATE(invoicedStart, cInvoiceStart);
	TimeToORACLE_DATE(invoicedEnd, cInvoiceEnd);

	// Open and Parse
	if (requestedId > 0)
	{
		if (all)
			OpenAndParse(&mpCDAOneShot, SQL_GetInvoicesForUser);
		else
			OpenAndParse(&mpCDAOneShot, SQL_GetInvoicesForUserCC);
	}
	else
		OpenAndParse(&mpCDAOneShot,
				 SQL_GetInvoicesCC);

	// Bind
	Bind(":istart", cInvoiceStart);
	Bind(":iend", cInvoiceEnd);
	if (requestedId > 0)
		Bind(":id", &requestedId);

	// Define
	Define(1, id);
	Define(2, when[0], sizeof(when[0]));
	Define(3, balance);

	// Execute
	Execute();

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORA_INVOICED_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_ACCOUNTS_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			// Convert time
			ORACLE_DATEToTime(when[i],
								   &theWhen);

			pInterimBalance	= new clsInterimBalance(id[i], theWhen,
													balance[i]);

			plBalances->push_back(pInterimBalance);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// HACK method for A/R Audit
//
static const char *SQL_XGetNextTransactionId = 
 "select ebay_transaction_sequence.nextval		\
	from dual";

static const char *SQL_XAddRawAccountDetail =
 "insert into ebay_xaccounts					\
	(	id,										\
		when,									\
		action,									\
		amount,									\
		memo,									\
		transaction_id,							\
		migration_batch_id						\
	)											\
	values										\
	(	:id,									\
      TO_DATE(:when,							\
            'YYYY-MM-DD HH24:MI:SS'),			\
		:action,								\
		:amount,								\
		:memo,									\
		:seq,									\
		:mbi									\
	)";


#if 1

int clsDatabaseOracle::XAddRawAccountDetail(
  						int count,
						int *pId,
						char *pWhen,
						int *pAction,
						float *pAmount,
						char *pMemo,
						int * /* pSeq */,
						int *pMigrationBatchId)
{
	int		rc;
	int		seq;

	OpenAndParse(&mpCDAOneShot, SQL_XGetNextTransactionId);

	Define(1, &seq);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	// Open + Parse
	OpenAndParse(&mpCDAAddRawAccountDetail, SQL_XAddRawAccountDetail);


	// Bind And Define
	Bind(":id", pId);
	Bind(":when", pWhen, 32);
	Bind(":action", pAction);
	Bind(":amount", pAmount);
	Bind(":memo", pMemo, 81);
	Bind(":seq", &seq);
	Bind(":mbi", pMigrationBatchId);

	rc	= oexn((cda_def *)mpCDACurrent, count, 0);
	Check(rc);

	Commit();

	Close(&mpCDAAddRawAccountDetail);
	SetStatement(NULL);

	return	seq;
}


//
// AddAccountAWItemXref
//

static const char *SQL_XAddAccountAWItemXref = 
"insert into ebay_transaction_xref_aw_item			\
	(	id,											\
		aw_item										\
	)												\
	values											\
	(	:id,										\
		:itemid										\
	)";

void clsDatabaseOracle::XAddAccountAWItemXref(int count,
											 unsigned int *pIds,
											 char *pItemIds)
{
	int		rc;

	OpenAndParse(&mpCDAAddAccountAWItemXref, 
				 SQL_XAddAccountAWItemXref);

	Bind(":id", (unsigned int *)pIds);
	Bind(":itemid", pItemIds, 13);


	rc	= oexn((cda_def *)mpCDACurrent, count, 0);
	Check(rc);

	Commit();

	Close(&mpCDAAddAccountAWItemXref);
	SetStatement(NULL);

	return;
}

//===============================================================================
// Lena
static const char *SQL_GetUsersWithAccountsNotInvoiced =
"select distinct(id) from ebay_account_balances  \
	minus                                        \
	select distinct(id) from ebay_interim_balances    \
	where   \
	when = TO_DATE(:invdate, 'YYYY-MM-DD HH24:MI:SS')";
static const char *SQL_GetUsersWithAccountsNotInvoicedFromTo =
"select distinct(id) from ebay_account_balances  \
 where id>=:aStart and id<:aEnd                  \
 minus                                           \
 select distinct(id) from ebay_interim_balances  \
 where id>=:idStart and id<:idEnd                \
 and when = TO_DATE(:invdate, 'YYYY-MM-DD HH24:MI:SS')";

//==============================================================================

void clsDatabaseOracle::GetUsersWithAccountsNotInvoiced( vector<unsigned int> *pvIds,
                                                        time_t tInvoiceDate, 
														int idStart, int idEnd )
{
        char            cInvDate[32];
        int             count;
        int             id[ORA_ACCOUNTS_ARRAYSIZE];
        int             rowsFetched;
        int             rc;
        int             i,n;
		int aStart = idStart;
		int aEnd = idEnd;
		
		if ( idEnd == 0 )
		{
			SetReadOnly();
			// Open and Parse
			OpenAndParse(&mpCDAOneShot,
                                 SQL_GetCountUsersWithAccounts);

			// Define
			Define(1, &count);

			// Execute

			ExecuteAndFetch();

			 // Close
			Close(&mpCDAOneShot);
			SetStatement(NULL);

			Commit();
		}
		else
			count = idEnd - idStart;

        // Make the vector nice and FAT
        pvIds->reserve(count);

        if (tInvoiceDate == (time_t) 0)
                tInvoiceDate = time(0);

        TimeToORACLE_DATE(tInvoiceDate, cInvDate);

        SetReadOnly();

        // Open and Parse
		if ( idEnd == 0 )
		{
			OpenAndParse(&mpCDAOneShot,
                                 SQL_GetUsersWithAccountsNotInvoiced);

			Bind(":invdate", cInvDate);
		}
	    else
		{
			OpenAndParse(&mpCDAOneShot,
                                 SQL_GetUsersWithAccountsNotInvoicedFromTo);
			Bind(":invdate", cInvDate);
			Bind(":aStart", &aStart);
			Bind(":aEnd", &aEnd);
			Bind(":idStart", &idStart);
			Bind(":idEnd", &idEnd);
		}

        // Define
        Define(1, (int *)id);

        // Execute
        Execute();

        // Now we fetch until we're done
        rowsFetched = 0;
        do
        {
			rc = ofen((struct cda_def *)mpCDACurrent,ORA_ACCOUNTS_ARRAYSIZE);
            if ((rc < 0 || rc >= 4)  &&
               ((struct cda_def *)mpCDACurrent)->rc != 1403)   // something wrong
            {
				Check(rc);
				Close(&mpCDAOneShot);
				SetStatement(NULL);
				return;
				
			}
			// rpc is cumulative, so find out how many rows to display this time
			// (always <= ORA_ACCOUNTS_ARRAYSIZE).
			n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
			rowsFetched += n;
			
			for (i=0; i < n; i++)
			{
				pvIds->push_back(id[i]);
			}
			
        } while (!CheckForNoRowsFound());


        Close(&mpCDAOneShot);
        SetStatement(NULL);

        Commit();

        return;
}

//================================================================================
//
// GetUsersWithPastDueNotCalculated
//
//      This method will get users for which we haven't calculated past
// due yet. It uses the past due base in ebay_account_balances to
// see which ones haven't, and then, to prune the list even more,
// subtracts users who have registered SINCE the past due date
// minue 28 days (because they can't possibly have any past due).
//

static const char *SQL_GetCountUsersWithPastDueNotCalculated =
"select count(*) from      \
 (                         \
        select  id         \
        from ebay_account_balances \
        where pastduebase < \
              TO_DATE(:pastbase, 'YYYY-MM-DD HH24:MI:SS')\
        minus               \
           select id from ebay_user_info \
           where creation >=  \
           TO_DATE(:pastbase, 'YYYY-MM-DD HH24:MI:SS') -   \
           28     \
 )";

static const char *SQL_GetUsersWithPastDueNotCalculated =
"select id                                                 \
 from  ebay_account_balances                               \
 where  pastduebase <                                      \
 TO_DATE(:pastbase, 'YYYY-MM-DD HH24:MI:SS')               \
 minus                                                     \
 select id from ebay_user_info                             \
 where  creation >=                                        \
           TO_DATE(:pastbase, 'YYYY-MM-DD HH24:MI:SS') -   \
                                28";


//=============================================================================

void clsDatabaseOracle::GetUsersWithPastDueNotCalculated(vector<unsigned int> *pvIds,
                                                                time_t tPastDueDate)
{
   char     cPastDueDate[32];
   int      count;
   int      id[ORA_ACCOUNTS_ARRAYSIZE];
   int      rowsFetched;
   int      rc;
   int      i,n;

   if (tPastDueDate == (time_t) 0)
       tPastDueDate = time(0);

   TimeToORACLE_DATE(tPastDueDate, cPastDueDate);

   OpenAndParse(&mpCDAOneShot,
                     SQL_GetCountUsersWithPastDueNotCalculated);

   // Define and bind
   Bind(":pastbase", cPastDueDate);
   Define(1, &count);

   // Execute
   ExecuteAndFetch();

   // Close
   Close(&mpCDAOneShot);
   SetStatement(NULL);

   // Make the vector nice and FAT
   pvIds->reserve(count);

   // Open and Parse
   OpenAndParse(&mpCDAOneShot,
             SQL_GetUsersWithPastDueNotCalculated);

   Bind(":pastbase", cPastDueDate);

   // Define
   Define(1, (int *)id);

   // Execute
   Execute();

   // Now we fetch until we're done
   rowsFetched = 0;
   do
   {

      rc = ofen((struct cda_def *)mpCDACurrent,ORA_ACCOUNTS_ARRAYSIZE);

      if ((rc < 0 || rc >= 4)  &&
         ((struct cda_def *)mpCDACurrent)->rc != 1403)   // something wrong
      {
         Check(rc);
         Close(&mpCDAOneShot);
         SetStatement(NULL);
         return;
      }

      // rpc is cumulative, so find out how many rows to display this time
      // (always <= ORA_ACCOUNTS_ARRAYSIZE).
      n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
      rowsFetched += n;


      for (i=0; i < n; i++)
      {
         pvIds->push_back(id[i]);
      }

   } while (!CheckForNoRowsFound());

   Close(&mpCDAOneShot);
   SetStatement(NULL);

   Commit();

   return;
}

static char *SQL_CountIds = 
"select count(*) from cc_billing where cc=:cc";

static char *SQL_UserId = 
"select id from cc_billing where cc=:cc";

static char *SQL_VerifyCCChargeAmount = 
"select balance from ebay_interim_balances where	\
id   = :id											\
and	when = TO_DATE(:maxwhen,						\
				'YYYY-MM-DD HH24:MI:SS')";

//==============================================================================

int clsDatabaseOracle::VerifyCCChargeAmount(char *cc, float amount, 
											time_t maxwhen, int UserId)
{
	float		balance				= 0;
	sb2			balance_ind;
	int			count = 0;
	sb2			count_ind = -1;
	int			id				= 0;
	sb2			id_ind;
	struct tm	*pcc_When;
	char		ccc_When[32];


	// find out what the real instance name on secure is going to be
//	if ( !GetInstanceName("ORACLE") ) // BILL_DB
//		return 1;

	OpenAndParse( &mpCDAOneShot, SQL_CountIds );

	// Bind And Define
	Bind(":cc", cc);
	Define(1, &count, &count_ind);
	// Get and Do
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return -1;
	}
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	if ( count <= 0 )
		return -1;

	if (count > 1)
		// Reduce fetch to that id only
		id = UserId;
	else
	{
		OpenAndParse( &mpCDAOneShot, SQL_UserId );
		// Bind And Define
		Bind(":cc", cc);
		Define(1, &id, &id_ind);
		// Get and Do
		ExecuteAndFetch();

		if (CheckForNoRowsFound())
		{
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return -1;
		}

		Close(&mpCDAOneShot);
		SetStatement(NULL);

		if (id <= 0)
			return -1;
	}		

	OpenAndParse( &mpCDAOneShot, SQL_VerifyCCChargeAmount );

	pcc_When	= localtime(&maxwhen);
	TM_STRUCTToORACLE_DATE(pcc_When, ccc_When);

	// Bind And Define
	Bind(":id", &id);
	Bind(":maxwhen", ccc_When);
	Define(1, &balance, &balance_ind);
	// Get and Do
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return id;
	}

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	if (amount != fabs(balance))
		return id;

	return 0;

}  // clsDatabaseOracle::VerifyCCChargeAmount


#endif


// hack to fix relist problem

//
// GetAccountDetail
//

#define ORACLE_BAD_DETAIL_ARRAY_SIZE 10

static char *SQL_GetBadAccountDetail = 
"select TO_CHAR( a.when,						\
					'YYYY-MM-DD HH24:MI:SS'),	\
			a.action,							\
			a.amount,							\
			a.transaction_id,					\
			a.memo,								\
			a.item_id							\
  from	ebay_insertion_fee_fix a				\
  where	a.id = :id";

void clsDatabaseOracle::GetBadAccountDetail(int id, 
										 AccountDetailVector *pvDetail)
{
	// Rows we've fetched
	int			rowsFetched;
	int			n;
	int			i;
	int			rc;

	// Pointers to arrays of things
	char		when[ORACLE_BAD_DETAIL_ARRAY_SIZE][32];
	int			action[ORACLE_BAD_DETAIL_ARRAY_SIZE];
	float		amount[ORACLE_BAD_DETAIL_ARRAY_SIZE];
	int			xactionId[ORACLE_BAD_DETAIL_ARRAY_SIZE];
	char		memo[ORACLE_BAD_DETAIL_ARRAY_SIZE][256];
	sb2			memo_ind[ORACLE_BAD_DETAIL_ARRAY_SIZE];
	int			itemId[ORACLE_BAD_DETAIL_ARRAY_SIZE];
	sb2			itemId_ind[ORACLE_BAD_DETAIL_ARRAY_SIZE];

	time_t					theWhen;
	char					*pTheMemo;
	int						theItemId;
	AccountDetailTypeEnum	theType;

	clsAccountDetail		*pDetail;

	OpenAndParse(&mpCDAOneShot,
				 SQL_GetBadAccountDetail);

	// Bind
	Bind(":id", &id);
	
	// Define
	Define(1, when[0], sizeof(when[0]));
	Define(2, action);
	Define(3, amount);
	Define(4, xactionId);
	Define(5, memo[0], sizeof(memo[0]), 
		      (short *)memo_ind);
	Define(6, itemId, itemId_ind);

	Execute();
	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORACLE_BAD_DETAIL_ARRAY_SIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; 
			 i < n;
			 i++)
		{

			// Convert time
			ORACLE_DATEToTime(when[i],
								   &theWhen);

			// Convert Type
			theType	= (AccountDetailTypeEnum)action[i];

			// Check for NULL memo
			if (memo_ind[i] == -1)
				pTheMemo	= NULL;
			else
				pTheMemo	= (char *)&memo[i][0];

			if (itemId_ind[i] == -1)
				theItemId	= 0;
			else
				theItemId	= itemId[i];

			pDetail	= new clsAccountDetail(id,
										   theWhen,
										   theType,
										   amount[i],
										   pTheMemo,
										   xactionId[i],
										   0,
										   theItemId);

			pvDetail->push_back(pDetail);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&mpCDAOneShot);
	SetStatement(NULL);


	return;
}

// GetUsersWithBadAccounts
// 

static const char *SQL_GetUsersWithBadAccounts =
"select	distinct id 											\
	from ebay_insertion_fee_fix		\
	order by id";

static const char *SQL_GetCountUsersWithBadAccounts =
"select	count(distinct id)									\
	from ebay_insertion_fee_fix";


void clsDatabaseOracle::GetUsersWithBadAccounts(vector<unsigned int> *pvIds)
{
	int		count;
	int		id[ORA_ACCOUNTS_ARRAYSIZE];
	int		rowsFetched;
	int		rc;
	int		i,n;

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetCountUsersWithBadAccounts);

	// Define
	Define(1, &count);

	// Execute
	ExecuteAndFetch();

	// Close
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	// Make the vector nice and FAT
	pvIds->reserve(count);

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetUsersWithBadAccounts);

	// Define
	Define(1, (int *)id);

	// Execute
	Execute();

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORA_ACCOUNTS_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_ACCOUNTS_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pvIds->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}
//inna-start
//this method returns sum of all payments and credits on 
//user's account sinca a given date (usually some interim balance date)
static const char *SQL_GetPaymentsSinceI = 
"select sum(amount)				"
"		from ";
static const char *SQL_GetPaymentsSinceII = 
"		where id = :id and 	"
"		amount > 0 and 		"
"		when > to_date(:when,'YYYY-MM-DD HH24:MI:SS')	";

void clsDatabaseOracle::GetPaymentsSince(int id, time_t tSinceDate, double &amount, int tableIndicator)
{
	struct tm		*pTheTime;
	char			cSinceDate[32];
	float	balance	= 0;
	sb2		balance_ind = 0;
	char	*table_name;
	char	*SQL_GetPaymentsSince;
	unsigned char **currentCursor;
	CallingTypeEnum from = GetAllPaymentsSince;

	table_name = new char[31];
	SQL_GetPaymentsSince= new char[1024];
	if (tableIndicator < 0)
		strcpy(table_name, "ebay_accounts ");
	else
		GetTableName(tableIndicator, table_name);
	CombineSQLStatement(SQL_GetPaymentsSinceI, table_name, 
		SQL_GetPaymentsSinceII, SQL_GetPaymentsSince);

   pTheTime = localtime(&tSinceDate);
   TM_STRUCTToORACLE_DATE(pTheTime, cSinceDate);

	// Open + Parse
	currentCursor = DetermineCursor( tableIndicator, from );
	OpenAndParse(currentCursor, SQL_GetPaymentsSince);
	delete [] table_name;
	delete [] SQL_GetPaymentsSince;


	// Bind and Define
	Bind(":id", &id);
	Bind(":when", cSinceDate);
	Define(1, &balance,&balance_ind);

	// Do it
	ExecuteAndFetch();

	// If we didn't find any rows, then we need to
	// create the account
	if (CheckForNoRowsFound())
	{
		Close(currentCursor);
		SetStatement(NULL);
		amount=0;
		return; // there were no payments for this user
	}

	// Done
	Close(currentCursor);
	SetStatement(NULL);
	amount=balance;
	return;

}
//this method returns sum of all payments and credits on 
//user's account sinca a given date (usually some interim balance date)
static const char *SQL_GetPaymentsByDateI = 
"select sum(amount)				"
"		from ";
static const char *SQL_GetPaymentsByDateII = 
"		where id = :id and 	"
"		amount > 0 and 		"
"		when > to_date(:when,'YYYY-MM-DD HH24:MI:SS') and " 
"		when <= to_date(:eom,'YYYY-MM-DD HH24:MI:SS')	";

void clsDatabaseOracle::GetPaymentsByDate(int id, time_t tSinceDate, time_t tEndDate, double &amount, int tableIndicator)
{
	struct tm		*pTheTime;
	char			cSinceDate[32];
	struct tm		*pEndTime;
	char			cEndDate[32];
	float	balance	= 0;
	sb2		balance_ind = 0;
	char	*table_name;
	char	*SQL_GetPaymentsByDate;

	table_name = new char[31];
	SQL_GetPaymentsByDate= new char[1024];
	if (tableIndicator < 0)
		strcpy(table_name, "ebay_accounts ");
	else
		GetTableName(tableIndicator, table_name);
	CombineSQLStatement(SQL_GetPaymentsByDateI, table_name, 
		SQL_GetPaymentsByDateII, SQL_GetPaymentsByDate);

   pTheTime = localtime(&tSinceDate);
   TM_STRUCTToORACLE_DATE(pTheTime, cSinceDate);

   pEndTime = localtime(&tEndDate);
   TM_STRUCTToORACLE_DATE(pEndTime, cEndDate);

	// Open + Parse
	OpenAndParse(&mpCDAOneShot, SQL_GetPaymentsByDate);
	delete [] table_name;
	delete [] SQL_GetPaymentsByDate;


	// Bind and Define
	Bind(":id", &id);
	Bind(":when", cSinceDate);
	Bind(":eom", cEndDate);
	Define(1, &balance,&balance_ind);

	// Do it
	ExecuteAndFetch();

	// If we didn't find any rows, then we need to
	// create the account
	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		amount=0;
		return; // there were no payments for this user
	}

	// Done
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	amount=balance;
	return;

}
static char *SQL_UpdateAccountBalance = 
"update ebay_account_balances set balance = :balance, \
last_modified = sysdate where id = :id";

void clsDatabaseOracle::SetAccountBalance(float balance, int id)
{
	OpenAndParse(&mpCDAOneShot,
				 SQL_UpdateAccountBalance);


	// Bind
	Bind(":id", &id);
	Bind(":balance", &balance);

	// Do it
	Execute();

	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}

	// Otherwise, we're done
	Commit();

	// Done
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;

} 

//inna-end

//lena
void clsDatabaseOracle::InvoiceTime(tm &thisTime, int month)
{
	thisTime.tm_sec			= 59;
	thisTime.tm_min			= 59;
	thisTime.tm_hour		= 23;
	if ( month > 0 )
		thisTime.tm_mon = month;
	if (thisTime.tm_mon == 0)
	{
		thisTime.tm_year = thisTime.tm_year-1;
		thisTime.tm_mon = 11;
	}
	else
		thisTime.tm_mon--;	// Month - 1!!!
	thisTime.tm_mday =	
		LastDayOfMonth( thisTime.tm_mon + 1, thisTime.tm_year );
	thisTime.tm_isdst			= -1;

}  // clsDatabaseOracle::InvoiceTime

int clsDatabaseOracle::LastDayOfMonth(int month, int year)
{
	unsigned int days = 0;
	switch ( month )
	{
		case 1: 
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			days = 31;
			break;
		case 4: 
		case 6: 
		case 9: 
		case 11:
			days = 30;
			break;
		case 2:
			{
			if ( LeapYear( year ) )
				days = 29;
			else
				days = 28;
			}
			break;
		default:
			break;
	}
	return days;

}  // clsDatabaseOracle::LastDayOfMonth

bool clsDatabaseOracle::LeapYear(int year)
{
	if ( ( year % 4 ) == 0 ) 
	{
		if ( ( ( year + 1900 ) % 1000 ) == 0 )
			return false;
		else
			return true;
	}
	return false;

}  // clsDatabaseOracle::LeapYear

//inna-start methods for ebay_eom_account_balances table
static char *SQL_AddEndOfMonthBalance=
"insert into ebay_eom_account_balances"
"	(	id,						"
"		last_modified,			"
"		balance,				"	
"		pastduebase,			"
"		pastdue30days,			"
"		pastdue60days,			"
"		pastdue90days,			"
"		pastdue120days,			"
"		pastdueover120days		"
"	)						    "
"	values						"
"	(	:id,					"
"		TO_DATE(:lastModified,				"
"				'YYYY-MM-DD HH24:MI:SS'),	"
"		:balance,							"
"		TO_DATE(:pastDueBase,				"
"				'YYYY-MM-DD HH24:MI:SS'),	"
"		:pastDue30Days,						"
"		:pastDue60Days,			  	  		"
"		:pastDue90Days,						"
"		:pastDue120Days,					"
"		:pastDueOver120Days					"
"	)";


//The next two methods write records into ebay_eom_balance_aging table
//the "delayed" method does not commit; 
void clsDatabaseOracle::AddEndOfMonthBalanceDelayed(
						clsEndOfMonthBalance *pEndOfMonthBalance)
{
	//variable to hole values
	int				id=pEndOfMonthBalance->GetId();
	float			balance=pEndOfMonthBalance->GetBalance();
	float			pastDue30Days=pEndOfMonthBalance->GetPastDue30Days();
	float			pastDue60Days=pEndOfMonthBalance->GetPastDue60Days();
	float			pastDue90Days=pEndOfMonthBalance->GetPastDue90Days();
	float			pastDue120Days=pEndOfMonthBalance->GetPastDue120Days();
	float			pastDueOver120Days=pEndOfMonthBalance->GetPastDueOver120Days();
	//date varibales
	time_t			lastModified;
	struct tm		*pLastModified;
	char			cLastModified[32];
	time_t			pastDueBase;
	struct tm		*pPastDueBase;
	char			cPastDueBase[32];


	//open and parse
	OpenAndParse(&mpCDAAddEndOfMonthBalance, SQL_AddEndOfMonthBalance);

	//change time_t to oracle format
	lastModified	= pEndOfMonthBalance->GetLastModified();
	if (lastModified == (time_t) 0)
		lastModified	= time(0);
	pLastModified	= localtime(&lastModified);
	TM_STRUCTToORACLE_DATE(pLastModified, cLastModified);

	pastDueBase	= pEndOfMonthBalance->GetPastDueBase();
	if (pastDueBase	 == (time_t) 0)
		pastDueBase		= time(0);
	pPastDueBase		= localtime(&pastDueBase);
	TM_STRUCTToORACLE_DATE(pPastDueBase, cPastDueBase);


	// Bind And Define
	Bind(":id", &id);
	Bind(":lastModified", cLastModified);
	Bind(":balance", &balance);
	Bind(":pastDueBase", cPastDueBase);
	Bind(":pastDue30Days", &pastDue30Days);
	Bind(":pastDue60Days", &pastDue60Days);
	Bind(":pastDue90Days", &pastDue90Days);
	Bind(":pastDue120Days", &pastDue120Days);
	Bind(":pastDueOver120Days", &pastDueOver120Days);


	Execute();

	//we will not commit here, because we want this table and
	//ebay_account
	//Commit();

	Close(&mpCDAAddEndOfMonthBalance);
	SetStatement(NULL);

	return;
}

void clsDatabaseOracle::AddEndOfMonthBalance(
						clsEndOfMonthBalance *pEndOfMonthBalance)
{
	//variable to hole values
	int				id=pEndOfMonthBalance->GetId();
	float			balance=pEndOfMonthBalance->GetBalance();
	float			pastDue30Days=pEndOfMonthBalance->GetPastDue30Days();
	float			pastDue60Days=pEndOfMonthBalance->GetPastDue60Days();
	float			pastDue90Days=pEndOfMonthBalance->GetPastDue90Days();
	float			pastDue120Days=pEndOfMonthBalance->GetPastDue120Days();
	float			pastDueOver120Days=pEndOfMonthBalance->GetPastDueOver120Days();
	//date varibales
	time_t			lastModified;
	struct tm		*pLastModified;
	char			cLastModified[32];
	time_t			pastDueBase;
	struct tm		*pPastDueBase;
	char			cPastDueBase[32];


	//open and parse
	OpenAndParse(&mpCDAAddEndOfMonthBalance, SQL_AddEndOfMonthBalance);

	//change time_t to oracle format
	lastModified	= pEndOfMonthBalance->GetLastModified();
	if (lastModified == (time_t) 0)
		lastModified	= time(0);
	pLastModified	= localtime(&lastModified);
	TM_STRUCTToORACLE_DATE(pLastModified, cLastModified);

	pastDueBase	= pEndOfMonthBalance->GetPastDueBase();
	if (pastDueBase	 == (time_t) 0)
		pastDueBase		= time(0);
	pPastDueBase		= localtime(&pastDueBase);
	TM_STRUCTToORACLE_DATE(pPastDueBase, cPastDueBase);


	// Bind And Define
	Bind(":id", &id);
	Bind(":lastModified", cLastModified);
	Bind(":balance", &balance);
	Bind(":pastDueBase", cPastDueBase);
	Bind(":pastDue30Days", &pastDue30Days);
	Bind(":pastDue60Days", &pastDue60Days);
	Bind(":pastDue90Days", &pastDue90Days);
	Bind(":pastDue120Days", &pastDue120Days);
	Bind(":pastDueOver120Days", &pastDueOver120Days);

	Execute();

	Commit();

	Close(&mpCDAAddEndOfMonthBalance);
	SetStatement(NULL);

	return;
}

static const char *SQL_GetEndOfMonthBalanceById =
  "select	balance,						\
			pastdue30days,					\
			pastdue60days,					\
			pastdue90days,					\
			pastdue120days,					\
			pastdueover120days,				\
			TO_CHAR(pastduebase,			\
				'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(last_modified,			\
				'YYYY-MM-DD HH24:MI:SS')	\
	from ebay_eom_account_balances			\
	where	id = :id and					\
	pastduebase = to_date(:tInvoiceDate,'YYYY-MM-DD HH24:MI:SS')";	

clsEndOfMonthBalance *clsDatabaseOracle::GetEndOfMonthBalanceById(int id, time_t tInvoiceDate)
{
	float	balance = 0.00;
	float	pastdue30days = 0.00;
	float	pastdue60days = 0.00;
	float	pastdue90days = 0.00;
	float	pastdue120days = 0.00;
	float	pastdueover120days = 0.00;

	char	last_modified[32];
	time_t	last_modified_time;
	char	pastduebase[32];
	time_t	pastduebase_time;

	clsEndOfMonthBalance	*pEndOfMonthBalance;

	char	when[32];


	// Initialize vars
	memset(when, 0x00, sizeof(when));
	TimeToORACLE_DATE( tInvoiceDate, when );

	// The usual suspects
	OpenAndParse(&mpCDAGetEndOfMonthBalanceById, SQL_GetEndOfMonthBalanceById);

	// Ok, let's do some binds
	Bind(":id", &id);
	//Bind a date
	Bind(":tInvoiceDate", (char *)when);

	// And zee defines

	Define(1, &balance);
	Define(2, &pastdue30days);
	Define(3, &pastdue60days);
	Define(4, &pastdue90days);
	Define(5, &pastdue120days);
	Define(6, &pastdueover120days);
	Define(7, pastduebase, sizeof(pastduebase));
	Define(8, last_modified, sizeof(last_modified));

	// Do it
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetEndOfMonthBalanceById);
		SetStatement(NULL);
		return NULL;
	}

	ORACLE_DATEToTime(last_modified, &last_modified_time);

	ORACLE_DATEToTime(pastduebase, &pastduebase_time);

	// Make it !
	pEndOfMonthBalance	= new clsEndOfMonthBalance(id,
											last_modified_time,
											balance,
											pastduebase_time,
											pastdue30days,
											pastdue60days,
											pastdue90days,
											pastdue120days,
											pastdueover120days);

	// Clean up 
	Close(&mpCDAGetEndOfMonthBalanceById);
	SetStatement(NULL);

	return pEndOfMonthBalance;
}

//
//	GetUsersForThisMonth
//
#define ORA_ACCOUNTS_ARRAYSIZE	500

static const char *SQL_GetUsersForThisMonth =
"select	id											\
	from ebay_eom_account_balances					\
	where id not in (select id from mw_gorp) and id  >= :aStart    \
	and id< :aEnd \
	and pastduebase = to_date(:tInvoiceDate,'YYYY-MM-DD HH24:MI:SS')";


void clsDatabaseOracle::GetUsersForThisMonth(vector<unsigned int> *pvIds, time_t tInvoiceDate, int idStart, int idEnd)
{
	int		id[ORA_ACCOUNTS_ARRAYSIZE];
	int		rowsFetched;
	int		rc;
	int		i,n;
	int		aStart=idStart;
	int		aEnd=idEnd;
	char	when[32];


	// Initialize vars
	memset(&when[0], 0x00, sizeof(when));
	TimeToORACLE_DATE( tInvoiceDate, when );

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetUsersForThisMonth);

	//Bind a date
	Bind(":tInvoiceDate", (char *)when);
	Bind(":aStart", &aStart);
	Bind(":aEnd", &aEnd);

	// Define
	Define(1, (int *)&id[0]);

	// Execute
	Execute();

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORA_ACCOUNTS_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_ACCOUNTS_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pvIds->push_back(id[i]);
		}

	} while (!CheckForNoRowsFound());


	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}
//inna- end
//inna -temporary
/*static const char *SQL_GetInactiveUsers =
"select id                                                 \
from ebay_inactive_users where email_sent <>1";

void clsDatabaseOracle::GetInactiveUsers(vector<unsigned int> *pvIds)
{
   int      count;
   int      id[5000];
   int      rowsFetched;
   int      rc;
   int      i,n;


   // Make the vector nice and FAT
   pvIds->reserve(5000);

   // Open and Parse
   OpenAndParse(&mpCDAOneShot,
                     SQL_GetInactiveUsers); 
 // Define
   Define(1, (int *)id);

   // Execute
   Execute();

   // Now we fetch until we're done
   rowsFetched = 0;
        //we only do it one time for 5000 records!
      rc = ofen((struct cda_def *)mpCDACurrent,5000);

      if ((rc < 0 || rc >= 4)  &&
         ((struct cda_def *)mpCDACurrent)->rc != 1403)   // something wrong
      {
         Check(rc);
         Close(&mpCDAOneShot);
         SetStatement(NULL);
         return;
      }                               
 n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
      rowsFetched += n;


      for (i=0; i < n; i++)
      {
         pvIds->push_back(id[i]);
      }


   Close(&mpCDAOneShot);
   SetStatement(NULL);

   Commit();

   return;
}                        

static char *SQL_UpdateInactiveSent =
"update ebay_inactive_users set email_sent = 1, \
 date_sent=sysdate where id = :id";

bool clsDatabaseOracle::UpdateInactiveSent(int id)
{

        OpenAndParse(&mpCDAOneShot,
                                 SQL_UpdateInactiveSent);


        // Bind
        Bind(":id", &id);

        // Do it
        Execute();

        if (CheckForNoRowsUpdated())
        {
                Close(&mpCDAOneShot);
                SetStatement(NULL);
                return false;        
        }
       // Otherwise, we're done
        Commit();

        // Done
        Close(&mpCDAOneShot);
        SetStatement(NULL);
        return true;
}*/
