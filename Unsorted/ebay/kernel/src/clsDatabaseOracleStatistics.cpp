/*	$Id: clsDatabaseOracleStatistics.cpp,v 1.5.240.1 1999/05/25 22:27:57 inna Exp $	*/
//
//	File:	clsDatabaseOracleStatistics.cc
//
//	Class:	clsDatabaseOracle
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 10/08/97 wen	- Created
//              - 05/18/99 Lydia Mu
//			    - 05/24/99 inna - added deleet for ebay_finance_subtotals
//

#include "eBayKernel.h"

//
// update the daily statistics
//
// input pQuery should be in the form:
//		INSERT INTO ebay_dailystatistics
//		SELECT :marketplace, TO_DATE(:start_time 'YYYY_MM_DD HH24:MI:SS', :xactionid, 
//			   category, count(*), sum(start_price) sum(bidcount) FROM ebay_items 
//		WHERE marketplace = :marketplace and 
//			  sale_start >= TO_DATE(:start_time, 'YYYY-MM-DD HH24:MI:SS') and
//			  sale_end   <  TO_DATE(:end_time,   'YYYY-MM-DD HH24:MI:SS') and
//			  quantity = 1
//		GROUP BY category;
//
// pQuery is for calculating the statistics on Chinese auctions. 
// The last condition should be quantity > 0 if it is for Dutch auctions.
//

#define ORA_DAILYSTATS_ARRAY 31
#define ORA_DAILYFINANCE_ARRAY 300

void clsDatabaseOracle::UpdateDailyStatisticsOnLeafCategories(
						const char* pQuery,
						int		Marketplace,
						int		transactionId,
						time_t	StartTime)
{
	time_t		EndTime;
	struct tm*	pStartTime;
	struct tm*	pEndTime;

	char		cStartTime[64];
	char		cEndTime[64];


	// Open and parse the statement
	OpenAndParse(&mpCDAOneShot, pQuery);

	// cover times to oracle formats
	pStartTime = localtime(&StartTime);
	pStartTime->tm_hour = 0;
	pStartTime->tm_min  = 0;
	pStartTime->tm_sec  = 0;
	TM_STRUCTToORACLE_DATE(pStartTime, cStartTime);

	// Add one day as the end time
	EndTime = StartTime + ONE_DAY;
	pEndTime = localtime(&EndTime);
	pEndTime->tm_hour = 0;
	pEndTime->tm_min  = 0;
	pEndTime->tm_sec  = 0;
	TM_STRUCTToORACLE_DATE(pEndTime, cEndTime);

	// Bind the input variables
	Bind(":marketplace", &Marketplace);
	Bind(":xactionid",   &transactionId);
	Bind(":start_time",  cStartTime);
	Bind(":end_time",	 cEndTime);

	// Let's do the SQL
	Execute();

	// Commit
	Commit();

	// Free things
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// Update Statistics on Not Leaf Categories
//
static const char *SQL_UpdateDailyStatisticsOnNotLeafCat=
"INSERT INTO ebay_dailystatistics \
SELECT %d, TO_DATE('%s', 'YYYY-MM-DD HH24:MI:SS'), %d, %d, sum(items), sum(dollar), sum(bidcount) \
FROM  ebay_dailystatistics \
WHERE marketplace=%d and when=TO_DATE('%s', 'YYYY-MM-DD HH24:MI:SS') and	\
 transaction_type=%d and categoryid in (%s)";

void clsDatabaseOracle::UpdateDailyStatisticsOnNotLeafCategory(
				MarketPlaceId Marketplace,
				time_t	Today,
				int	XactionId,
				int	CatId,
				char*	pCatList)
{
	struct tm*	pStartTime;
	char		cStartTime[64];
	int		rc;

	char *		pStatement = new char[strlen(pCatList) + 400];

	// cover times to oracle formats
	pStartTime = localtime(&Today);
	pStartTime->tm_hour = 0;
	pStartTime->tm_min  = 0;
	pStartTime->tm_sec  = 0;
	TM_STRUCTToORACLE_DATE(pStartTime, cStartTime);

	// put it together
	sprintf(pStatement, SQL_UpdateDailyStatisticsOnNotLeafCat,
			Marketplace, cStartTime, XactionId, CatId,
			Marketplace, cStartTime, XactionId, pCatList);

	// Open and parse the statement
	OpenAndParse(&mpCDAOneShot, pStatement);

	// Let's do the SQL
	rc = oexec((struct cda_def *)mpCDACurrent);
	if ((rc < 0 || rc >= 4)  && 
		((struct cda_def *)mpCDACurrent)->rc != 1400)	// something wrong
	{
		Check(rc);
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		delete[] pStatement;
		return;
	}

	// Commit
	Commit();

	// Free things
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	delete[] pStatement;

	return;

}

//
// Update Statistics on Bids
//
static const char *SQL_UpdateDailyBids =
"INSERT INTO ebay_dailystatistics	\
( MARKETPLACE,						\
  WHEN,								\
  TRANSACTION_TYPE,					\
  CATEGORYID,						\
  ITEMS,								\
  DOLLAR,							\
  BIDCOUNT							\
)									\
values								\
( :marketplace,						\
  TO_DATE(:when, 'YYYY-MM-DD HH24:MI:SS'), \
  0,									\
  0,									\
  0,									\
  0.0,								\
  :bids							\
)";

void clsDatabaseOracle::UpdateDailyBids(
				MarketPlaceId Marketplace,
				time_t	Today,
				int	Bids)
{
	struct tm*	pStartTime;
	char		cStartTime[64];
	int			rc;

	// cover times to oracle formats
	pStartTime = localtime(&Today);
	pStartTime->tm_hour = 0;
	pStartTime->tm_min  = 0;
	pStartTime->tm_sec  = 0;
	TM_STRUCTToORACLE_DATE(pStartTime, cStartTime);

	// Open and parse the statement
	OpenAndParse(&mpCDAOneShot, SQL_UpdateDailyBids);

	// Bind the input variable
	Bind(":marketplace", &Marketplace);
	Bind(":when", cStartTime);
	Bind(":bids", &Bids);

	// Let's do it!
	// Let's do the SQL
	rc = oexec((struct cda_def *)mpCDACurrent);
	if ((rc < 0 || rc >= 4)  && 
		((struct cda_def *)mpCDACurrent)->rc != 1)	// duplicated record
	{
		Check(rc);
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}

	// Commit
	Commit();

	// Free things
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;

}

//
//	Get daily bids
//
static const char *SQL_GetDailyBids =
"SELECT count(*) from ebay_bids \
WHERE marketplace=:marketplace AND \
created>=TO_DATE(:start_time, 'YYYY-MM-DD HH24:MI:SS') and \
created <TO_DATE(:end_time, 'YYYY-MM-DD HH24:MI:SS')";

int clsDatabaseOracle::GetDailyBids(
				MarketPlaceId Marketplace,
				time_t	StartTime)
{
	time_t		EndTime;
	struct tm*	pStartTime;
	struct tm*	pEndTime;

	char		cStartTime[64];
	char		cEndTime[64];

	int			Bids;


	// cover times to oracle formats
	pStartTime = localtime(&StartTime);
	pStartTime->tm_hour = 0;
	pStartTime->tm_min  = 0;
	pStartTime->tm_sec  = 0;
	TM_STRUCTToORACLE_DATE(pStartTime, cStartTime);

	// Add one day as the end time
	EndTime = StartTime + ONE_DAY;
	pEndTime = localtime(&EndTime);
	pEndTime->tm_hour = 0;
	pEndTime->tm_min  = 0;
	pEndTime->tm_sec  = 0;
	TM_STRUCTToORACLE_DATE(pEndTime, cEndTime);

	// Open and parse the statement
	OpenAndParse(&mpCDAOneShot, SQL_GetDailyBids);

	// Bind the input variables
	Bind(":marketplace", &Marketplace);
	Bind(":start_time",  cStartTime);
	Bind(":end_time",	 cEndTime);

	Define(1, &Bids);

	Execute();

	Fetch();

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return Bids;
}

//
// GetStatisticsTransaction
//
static const char *SQL_GetStatisticsTransaction =
 "	select	id,								\
			description,					\
			query							\
	from ebay_statistics_desc		\
	where statistics_type = :stats_type";


#define NUMBER_XACTIONS	10

void clsDatabaseOracle::GetStatisticsTransaction(
						StatisticsEnum StatisticsType,
						StatsTransactionVector* pTransVector)
{
	int		id[NUMBER_XACTIONS];
	char	description[NUMBER_XACTIONS][51];
	char	query[NUMBER_XACTIONS][501];
	sb2		query_ind[NUMBER_XACTIONS];

	int				rowsFetched;
	int				rc;
	int				i,n;

	clsStatisticsTransaction*	pStatsXaction;

	// Open and parse the statement
	OpenAndParse(&mpCDAOneShot, SQL_GetStatisticsTransaction);

	// Bind the input variable
	Bind(":stats_type", (int *)&StatisticsType);

	// Bind the outputs
	Define(1, id);
	Define(2, description[0], sizeof(description[0]));
	Define(3, query[0], sizeof(query[0]), query_ind);

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
		rc = ofen((struct cda_def *)mpCDACurrent, NUMBER_XACTIONS);

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
			if (query_ind[i] == -1)
			{
				query[i][0] = '0';
			}

			// Fill in the item
			pStatsXaction	= new clsStatisticsTransaction;
			pStatsXaction->Set(id[i],
								StatisticsType,
								description[i],
								query[i]);

			pTransVector->push_back(pStatsXaction);
		}
	} while (!CheckForNoRowsFound());
	
	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// GetDailyStatistics
//
static const char *SQL_GetDailyStatistics =
 "	select TO_CHAR(when, 'YYYY-MM-DD HH24:MI:SS'),	\
		   items, dollar, bidcount \
	from ebay_dailystatistics		\
	where marketplace = :marketplace and \
		  when>=TO_DATE(:startdate, 'YYYY-MM-DD HH24:MI:SS') and \
		  when<=TO_DATE(:enddate, 'YYYY-MM-DD HH24:MI:SS') and \
		  transaction_type = :xactionid and \
		  categoryid = :catid";


void clsDatabaseOracle::GetDailyStatistics(MarketPlaceId Marketplace,
						time_t StartTime, 
						time_t EndTime,
						int	XactionId,
						CategoryId CatId,
						DailyStatsVector* pvDailyStats)
{
	time_t	TheTime;
	char	TimeArray[ORA_DAILYSTATS_ARRAY][32];
	int		Items[ORA_DAILYSTATS_ARRAY];
	float	Dollar[ORA_DAILYSTATS_ARRAY];
	int		BidCount[ORA_DAILYSTATS_ARRAY];

	char		cStartDate[20];
	struct tm	*pStartDate;	
	char		cEndDate[20];
	struct tm	*pEndDate;	

	int			i;
	int			rowsFetched;
	int			rc;
	int			n;
	clsDailyStatistics*	pDailyStats;


	// convert the time
	pStartDate	= localtime(&StartTime);
	TM_STRUCTToORACLE_DATE(pStartDate,
						   cStartDate);

	pEndDate	= localtime(&EndTime);
	TM_STRUCTToORACLE_DATE(pEndDate,
						   cEndDate);

	OpenAndParse(&mpCDAOneShot, SQL_GetDailyStatistics);

	// Bind the input variable
	Bind(":marketplace", &Marketplace);
	Bind(":startdate", cStartDate);
	Bind(":enddate", cEndDate);
	Bind(":xactionid", &XactionId);
	Bind(":catid", &CatId);

	// define the retrieving field
	Define(1, TimeArray[0], sizeof(TimeArray[0]));
	Define(2, Items);
	Define(3, Dollar);
	Define(4, BidCount);

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
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_DAILYSTATS_ARRAY);

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

			// Time Conversions
			ORACLE_DATEToTime(TimeArray[i], &TheTime);

			pDailyStats = new clsDailyStatistics(
								Marketplace,
								TheTime,
								XactionId,
								CatId,
								Items[i],
								Dollar[i],
								BidCount[i]);

			pvDailyStats->push_back(pDailyStats);
		}

	} while (!CheckForNoRowsFound());

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


//
// GetStatisticsTransaction
//
static const char *SQL_GetTransactionQuery =
 "	select	query							\
	from ebay_statistics_desc		\
	where id = :id and 							\
		  statistics_type = :stats_type";


void clsDatabaseOracle::GetTransactionQuery(
				int XactionId,
				StatisticsEnum StatisticType,
				char* pQuery,
				int	  Size)
{
	char	Query[500];

	// Open and parse the statement
	OpenAndParse(&mpCDAOneShot, SQL_GetTransactionQuery);

	// Bind the input variable
	Bind(":id", (int *)&XactionId);
	Bind(":stats_type", (int *)&StatisticType);

	// Bind the outputs
	Define(1, (char*)Query, sizeof(Query));

	// Let's do the SQL
	ExecuteAndFetch();

	// copy the Query to the output
	memset(pQuery, 0, Size);
	strncpy(pQuery, Query, Size-1);

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


//
//
// Update Daily Finance
//
static const char *SQL_DeleteDailyFinance =
"DELETE FROM ebay_finance WHERE when = TO_DATE(:start_time, 'YYYY_MM_DD HH24:MI:SS')";

static const char *SQL_DeleteDailyFinanceSub =
"DELETE FROM ebay_finance_subtotals WHERE when = TO_DATE(:start_time, 'YYYY_MM_DD HH24:MI:SS')";



// Lydia - get the data from new ebay_account_# table and put them into
// temporary ebay_finance_subtotals table
static const char *SQL_UpdateFinanceSubtotals1 =
"		INSERT INTO ebay_finance_subtotals	\
		SELECT TO_DATE(:start_time, 'YYYY_MM_DD HH24:MI:SS'), \
		  	   action, count(*), sum(amount) \
		FROM ";

static const char *SQL_UpdateFinanceSubtotals2 = 
"       WHERE  when >= TO_DATE(:start_time, 'YYYY-MM-DD HH24:MI:SS') and \
		       when <  TO_DATE(:end_time,   'YYYY-MM-DD HH24:MI:SS') and \
		       (amount > -300 and amount < 300 ) \
		GROUP BY action";

//static char *SQL_UpdateTableIndicator =
//"       UPDATE  ebay_finance_subtotals \
//        SET     table_indicator = :table_indicator \
//		WHERE   when >= TO_DATE(:start_time, 'YYYY-MM-DD HH24:MI:SS') and \
//		        when <  TO_DATE(:end_time,   'YYYY-MM-DD HH24:MI:SS')";
//

// Lydia - get the data from old ebay_account table when 
// table indicator is -1 and put them into ebay_finance_subtotals table
// Remove after all the accounts have been splitted to ebay_accounts_# tables
//

static char *SQL_UpdateFinanceSubtotalsNotSplit =
"INSERT INTO ebay_finance_subtotals \
SELECT TO_DATE(:start_time, 'YYYY_MM_DD HH24:MI:SS'), \
       b.action, count(*), sum(b.amount) \
FROM   TEMP_ACCOUNTS_NOT_SPLIT_ID a, \
       temp_accounts_by_date b \
WHERE  a.id = b.id AND \
       b.when >= TO_DATE(:start_time, 'YYYY-MM-DD HH24:MI:SS') AND \
       b.when <  TO_DATE(:end_time,   'YYYY-MM-DD HH24:MI:SS') AND \
       (b.amount > -300 and b.amount < 300 ) \
GROUP BY action";


		       

//static const char *SQL_UpdateDailyFinance =
//"		INSERT INTO ebay_finance	\
//		SELECT TO_DATE(:start_time, 'YYYY_MM_DD HH24:MI:SS'), \
//			   action, count(*), sum(amount) \
//		FROM   ebay_accounts \
//		WHERE  when >= TO_DATE(:start_time, 'YYYY-MM-DD HH24:MI:SS') and \
//			   when <  TO_DATE(:end_time,   'YYYY-MM-DD HH24:MI:SS') and \
//			   (amount > -5000 and amount < 5000 ) \
//		GROUP BY action";



// Update the ebay_finance table from ebay_finance_subtotals
static const char *SQL_UpdateDailyFinance =
"       INSERT INTO ebay_finance \
        SELECT TO_DATE(:start_time, 'YYYY_MM_DD HH24:MI:SS'), \
		       action, sum(count), sum(amount) \
		FROM   ebay_finance_subtotals \
		WHERE  when >= TO_DATE(:start_time, 'YYYY-MM-DD HH24:MI:SS') and \
		       when <  TO_DATE(:end_time,   'YYYY-MM-DD HH24:MI:SS') \
		GROUP BY action";



void clsDatabaseOracle::UpdateDailyFinance(time_t StartTime)
{
	time_t		EndTime;
	struct tm*	pStartTime;
	struct tm*	pEndTime;

	char		cStartTime[64];
	char		cEndTime[64];

	//Lydia	
	char        *SQL_UpdateFinanceSubtotals = NULL;
	char        *table_name;
	int         i;
	//int         TableIndNegativeOne = -1;


	// cover times to oracle formats
	pStartTime = localtime(&StartTime);
	pStartTime->tm_hour = 0;
	pStartTime->tm_min  = 0;
	pStartTime->tm_sec  = 0;
	TM_STRUCTToORACLE_DATE(pStartTime, cStartTime);

	// Add one day as the end time
	EndTime = StartTime + ONE_DAY;
	pEndTime = localtime(&EndTime);
	pEndTime->tm_hour = 0;
	pEndTime->tm_min  = 0;
	pEndTime->tm_sec  = 0;
	TM_STRUCTToORACLE_DATE(pEndTime, cEndTime);

	//
	// clean it up before updating
	//
	OpenAndParse(&mpCDAOneShot, SQL_DeleteDailyFinance);

	// Bind the input variables
	Bind(":start_time",  cStartTime);

	// Let's do the SQL
	Execute();

	// Free things
	Close(&mpCDAOneShot);
	SetStatement(NULL);

		// clean it up before updating
	//
	OpenAndParse(&mpCDAOneShot, SQL_DeleteDailyFinanceSub);

	// Bind the input variables
	Bind(":start_time",  cStartTime);

	// Let's do the SQL
	Execute();

	// Free things
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	//
	// Now do the update
	//
	// Remove following section after all the accounts have been splitted 
	// into ebay_accounts_# tables
	// Update the finance information from the ebay_accounts table 
	// into ebay_finance_subtotals. This finance info is not in the ten 
	// ebay_accounts_# tables.

	// Use the Create Table AS command to create two temporary tables which 
	// will handle the accounts that are not in the ten ebay_accounts_# tables.
	CTASAcctsNotSplitId();
	CTASAccountsByDate(cStartTime, cEndTime);

	// Update the finance information on the ebay_accounts table, which 
	// is not in the ten ebay_accounts_# tables.
	OpenAndParse(&mpCDAOneShot, SQL_UpdateFinanceSubtotalsNotSplit);
   
	//Bind the input variables
	Bind(":start_time",  cStartTime);
	Bind(":end_time",	cEndTime);

	// Let's do the SQL
	Execute();

	// Commit
	Commit();

	// Free things
	Close(&mpCDAOneShot);
	SetStatement(NULL);


    // update the table_indicator into ebay_finance_subtotals table
    //OpenAndParse(&mpCDAOneShot, SQL_UpdateTableIndicator);
	//Bind(":table_indicator",  &TableIndNegativeOne);
	//Execute();
	//Commit();
	//Close(&mpCDAOneShot);
	//SetStatement(NULL);

	// end of removing setion


    // Lydia - The ebay_account table has been split to 10 account tables
	// Get the data from those ten tables and put them into ebay_finance table
	//
    for (i=0; i < 10; i++)
    {
	   table_name= new char[31];
		sprintf(table_name, "ebay_accounts_%d", i);

	   SQL_UpdateFinanceSubtotals = new char[1024];
	   CombineSQLStatement(SQL_UpdateFinanceSubtotals1, table_name, 
		                   SQL_UpdateFinanceSubtotals2, SQL_UpdateFinanceSubtotals);
	   delete [] table_name;	

	   // Open and parse the statement
	   OpenAndParse(&mpCDAOneShot, SQL_UpdateFinanceSubtotals);
       delete [] SQL_UpdateFinanceSubtotals;

	   // Bind the input variables
	   Bind(":start_time",      cStartTime);
	   Bind(":end_time",	    cEndTime);
	   //Bind(":table_indicator", i);

	   // Let's do the SQL
	   Execute();

	   // Commit
	   Commit();

	   // Free things
	   Close(&mpCDAOneShot);
	   SetStatement(NULL);

       // update the table_indicator into ebay_finance_subtotals table
       //OpenAndParse(&mpCDAOneShot, SQL_UpdateTableIndicator);
	   //Bind(":table_indicator",   &i);
	   //Execute();
	   //Commit();
	   //Close(&mpCDAOneShot);
	   //SetStatement(NULL);

    } //end for


	// Open and parse the statement
	OpenAndParse(&mpCDAOneShot, SQL_UpdateDailyFinance);

	// Bind the input variables
	Bind(":start_time",  cStartTime);
	Bind(":end_time",	 cEndTime);

	// Let's do the SQL
	Execute();

	// Commit
	Commit();

	// Free things
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}



//Create a temp_accounts_not_split_id table
static char *SQL_CTASAcctsNotSplitId =
"create table TEMP_ACCOUNTS_NOT_SPLIT_ID (ID) \
tablespace USERS \
storage(initial 1M next 1M pctincrease 0)  unrecoverable \
AS \
select id from ebay_account_balances b \
where b.table_indicator = -1 OR b.table_indicator is NULL";


void clsDatabaseOracle::CTASAcctsNotSplitId()
{
	// check if ebay_accounts_not_split_id table exists in DB
	if (TempAcctsNotSplitIdExists())
		DropAcctsNotSplitId();

	OpenAndParse(&mpCDAOneShot, SQL_CTASAcctsNotSplitId);
	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}  // end CTASAcctsNotSplitId()


// Check if the TEMP_ACCOUNTS_NOT_SPLIT_ID table exists in the DB
// Return true if the table exists, otherwise return false.
static char *SQL_TempAcctsNotSplitIdExists =
"select count(*) \
from user_tables where table_name = 'TEMP_ACCOUNTS_NOT_SPLIT_ID'";


bool clsDatabaseOracle::TempAcctsNotSplitIdExists()
{
	int count = 0;
	bool found = false;
	OpenAndParse(&mpCDAOneShot, SQL_TempAcctsNotSplitIdExists);
	Define(1, &count);
	ExecuteAndFetch();
   
	if (count != 0)
	   found = true;
   
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return found;  
}  // end TempAcctsNotSplitIdExists()


// Drop the temp_accounts_not_split_id table
static char *SQL_DropAcctsNotSplitId =
"drop table temp_accounts_not_split_id";


void clsDatabaseOracle::DropAcctsNotSplitId()
{
	OpenAndParse(&mpCDAOneShot, SQL_DropAcctsNotSplitId);
	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
} //end DropAcctsNotSplitId()


// Create a temp_accounts_by_date table
static char *SQL_CTASAccountsByDateI =
"CREATE TABLE TEMP_ACCOUNTS_BY_DATE (ID ,WHEN,ACTION, AMOUNT) \
tablespace USERS \
storage(initial 10 M next 10 M pctincrease 0)  unrecoverable \
AS \
SELECT id, when, action, amount \
FROM   ebay_accounts \
WHERE  when >= TO_DATE('";

static char *SQL_CTASAccountsByDateII =
"','YYYY-MM-DD HH24:MI:SS') and \
	   when <  TO_DATE('";

static char *SQL_CTASAccountsByDateIII =
"','YYYY-MM-DD HH24:MI:SS')";


void clsDatabaseOracle::CTASAccountsByDate(char *cStartTime, char *cEndTime)
{
	char			*SQL_CTASAccountsByDate;

	if (TempAcctsByDateExists())
	     DropAcctsByDate();

	SQL_CTASAccountsByDate = new char[2000];
	int j = 0;
	j = sprintf( SQL_CTASAccountsByDate, "%s", SQL_CTASAccountsByDateI);
	j += sprintf( SQL_CTASAccountsByDate + j, "%s", cStartTime );
	j += sprintf( SQL_CTASAccountsByDate + j, "%s", SQL_CTASAccountsByDateII);
	j += sprintf( SQL_CTASAccountsByDate + j, "%s", cEndTime );
	j += sprintf( SQL_CTASAccountsByDate + j, "%s", SQL_CTASAccountsByDateIII);

	OpenAndParse(&mpCDAOneShot, SQL_CTASAccountsByDate);
	//Bind(":start_time", cStartTime);
	//Bind(":end_time", cEndTime);

	delete [] SQL_CTASAccountsByDate;
	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
} // end CTASAccountsByDate


// Check if the TEMP_ACCOUNTS_BY_DATE table exists in the DB
// Return true if the table exists, otherwise return false.
static char *SQL_TempAcctsByDateExists =
"select count(*) \
from user_tables where table_name = 'TEMP_ACCOUNTS_BY_DATE'";


bool clsDatabaseOracle::TempAcctsByDateExists()
{
	int count = 0;
	bool found = false;
	OpenAndParse(&mpCDAOneShot, SQL_TempAcctsByDateExists);
	Define(1, &count);
	ExecuteAndFetch();
   
	if (count != 0)
	   found = true;
   
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return found;  
}  // end TempAcctsByDateExists()


// Drop the temp_accounts_by_date table
static char *SQL_DropAcctsByDate =
"drop table temp_accounts_by_date";


void clsDatabaseOracle::DropAcctsByDate()
{
	OpenAndParse(&mpCDAOneShot, SQL_DropAcctsByDate);
	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
} //end DropAcctsByDate()




//
// Get Daily Finance
//
static const char *SQL_GetDailyFinance =
"		SELECT TO_CHAR(when, 'YYYY-MM-DD HH24:MI:SS'), \
			   action, count, amount \
		FROM   ebay_finance \
		WHERE  when >= TO_DATE(:start_time, 'YYYY-MM-DD HH24:MI:SS') and \
			   when <= TO_DATE(:end_time,   'YYYY-MM-DD HH24:MI:SS') \
		ORDER BY when asc";

void clsDatabaseOracle::GetDailyFinance(
						time_t StartTime, 
						time_t EndTime,
						DailyFinanceRawVector* pvDailyFinanceRaw,
						int *pMaxAction)
{
	time_t	TheTime;
	char	TimeArray[ORA_DAILYFINANCE_ARRAY][32];
	int		Action[ORA_DAILYFINANCE_ARRAY];
	int		Count[ORA_DAILYFINANCE_ARRAY];
	float	Amount[ORA_DAILYFINANCE_ARRAY];

	char		cStartDate[20];
	struct tm	*pStartDate;	
	char		cEndDate[20];
	struct tm	*pEndDate;	

	int			i;
	int			rowsFetched;
	int			rc;
	int			n;
	clsDailyFinanceRaw*	pDailyFinanceRaw;

	// set MaxAction to 0
	*pMaxAction = 0;

	// convert the time
	pStartDate	= localtime(&StartTime);
	TM_STRUCTToORACLE_DATE(pStartDate,
						   cStartDate);

	pEndDate	= localtime(&EndTime);
	TM_STRUCTToORACLE_DATE(pEndDate,
						   cEndDate);

	OpenAndParse(&mpCDAOneShot, SQL_GetDailyFinance);

	// Bind the input variable
	Bind(":start_time", cStartDate);
	Bind(":end_time", cEndDate);

	// define the retrieving field
	Define(1, TimeArray[0], sizeof(TimeArray[0]));
	Define(2, Action);
	Define(3, Count);
	Define(4, Amount);

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
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_DAILYFINANCE_ARRAY);

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

			// Time Conversions
			ORACLE_DATEToTime(TimeArray[i], &TheTime);

			pDailyFinanceRaw = new clsDailyFinanceRaw(
								TheTime,
								Action[i],
								Count[i],
								Amount[i]);

			// Get the Maximum action number
			if (*pMaxAction < Action[i]) *pMaxAction = Action[i];

			pvDailyFinanceRaw->push_back(pDailyFinanceRaw);
		}

	} while (!CheckForNoRowsFound());

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return;
}