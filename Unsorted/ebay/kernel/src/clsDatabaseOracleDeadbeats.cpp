/*	$Id: clsDatabaseOracleDeadbeats.cpp,v 1.4 1999/05/07 02:32:42 wwen Exp $	*/
//
//	File:	clsDatabaseOracleDeadbeats.cpp
//
//	Class:	clsDatabaseOracleDeadbeats
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//			All deadbeat user- or item-related database activities.
//
//
// Modifications:
//				- 09/25/98 mila		- Created
//				- 10/02/98 mila		- added GetDeadbeatItemsByBidderId
//				- 10/13/98 mila		- added GetAllDeadbeatItems
//				- 12/01/98 mila		- added GetDeadbeatItemsBySellerId,
//									  AddDeadbeat, GetDeadbeat, et al
//				- 12/08/98 mila		- added GetAllDeadbeats; changed seller items
//									  and bidder items from lists to vectors
//				- 12/15/98 mila		- added GetDeadbeatItemCountBySellerId and
//									  GetDeadbeatItemCountByBidderId; various
//									  bug fixes in other methods
//				- 12/17/98 mila		- added order-by clause to GetAllDeadbeats
//									  SQL statement
//

#include "eBayKernel.h"

#include <string.h>
#include <stdio.h>
#include <fcntl.h>
#include <errno.h>
#include <time.h>


//============================= Deadbeats ===============================//

//
// AddDeadbeat
//
static const char *SQL_AddDeadbeat =
"insert into ebay_deadbeats				\
	(	id,								\
		creation_date,					\
		last_modified_date,				\
		valid_deadbeat_score,			\
		valid_credit_request_count,		\
		valid_warning_count,			\
		deadbeat_score,					\
		credit_request_count,			\
		warning_count					\
	)									\
	values								\
	(	:id,							\
		sysdate,						\
		sysdate,						\
		:valid_deadbeat_score,			\
		:valid_request_count,			\
		:valid_warning_count,			\
		:deadscore,						\
		:credit_requests,				\
		:warnings						\
	)";

void clsDatabaseOracle::AddDeadbeat(int id,
									int deadscore,
									int creditRequests,
									int warnings,
									bool isValidDeadbeatScore,
									bool isValidCreditRequestCount,
									bool isValidWarningCount)
{
	char	cValidDeadbeatScore[2];
	char	cValidCreditRequestCount[2];
	char	cValidWarningCount[2];

	if (isValidDeadbeatScore)
		strcpy(cValidDeadbeatScore, "1");
	else
		strcpy(cValidDeadbeatScore, "0");

	if (isValidCreditRequestCount)
		strcpy(cValidCreditRequestCount, "1");
	else
		strcpy(cValidCreditRequestCount, "0");

	if (isValidWarningCount)
		strcpy(cValidWarningCount, "1");
	else
		strcpy(cValidWarningCount, "0");

	OpenAndParse(&mpCDAOneShot, SQL_AddDeadbeat);

	// Bind it, baby
	Bind(":id", &id);
	Bind(":valid_deadbeat_score", (char *)cValidDeadbeatScore);
	Bind(":valid_request_count", (char *)cValidCreditRequestCount);
	Bind(":valid_warning_count", (char *)cValidWarningCount);
	Bind(":deadscore", &deadscore);
	Bind(":credit_requests", &creditRequests);
	Bind(":warnings", &warnings);

	// Do it...
	Execute();
	Commit();

	// Leave it!
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}

//
// GetDeadbeat
//
static const char *SQL_GetDeadbeat = 
 "select	TO_CHAR(creation_date,				\
					'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(last_modified_date,			\
					'YYYY-MM-DD HH24:MI:SS'),	\
			valid_deadbeat_score,				\
			valid_credit_request_count,			\
			valid_warning_count,				\
			deadbeat_score,						\
			credit_request_count,				\
			warning_count						\
 from ebay_deadbeats							\
  where id = :id";

clsDeadbeat *clsDatabaseOracle::GetDeadbeat(int id)
{
	int		backouts = 0;
	int		requests = 0;
	int		warnings = 0;

	sb2		backoutsInd;
	sb2		requestsInd;
	sb2		warningsInd;

	char	cValidBackouts[2];
	char	cValidRequests[2];
	char	cValidWarnings[2];

	bool	validBackouts;
	bool	validRequests;
	bool	validWarnings;

	time_t	creationTime;
	char	cCreationTime[32];

	time_t	lastModifiedTime;
	char	cLastModifiedTime[32];


	clsDeadbeat	*pDeadbeat;


	// Do some initialization of char arrays
	memset(cValidBackouts, 0, sizeof(cValidBackouts));
	memset(cValidRequests, 0, sizeof(cValidRequests));
	memset(cValidWarnings, 0, sizeof(cValidWarnings));

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	OpenAndParse(&mpCDAGetDeadbeat, SQL_GetDeadbeat);

	// Bind that input variable
	Bind(":id", &id);

	// Bind the output
	Define(1, cCreationTime, sizeof(cCreationTime));
	Define(2, cLastModifiedTime, sizeof(cLastModifiedTime));
	Define(3, (char *)&cValidBackouts, sizeof(cValidBackouts));
	Define(4, (char *)&cValidRequests, sizeof(cValidRequests));
	Define(5, (char *)&cValidWarnings, sizeof(cValidWarnings));
	Define(6, &backouts, &backoutsInd);
	Define(7, &requests, &requestsInd);
	Define(8, &warnings, &warningsInd);

	// Let's get it.
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetDeadbeat);
		SetStatement(NULL);
		AddDeadbeat(id, 0, 0, 0, true, true, true);
		creationTime = lastModifiedTime = time(0);
		pDeadbeat = new clsDeadbeat(id, creationTime, lastModifiedTime);
	}
	else
	{
		ORACLE_DATEToTime(cCreationTime, &creationTime);
		ORACLE_DATEToTime(cLastModifiedTime, &lastModifiedTime);

		if (backoutsInd == -1)
			backouts = 0;
		if (requestsInd == -1)
			requests = 0;
		if (warningsInd == -1)
			warnings = 0;

		validBackouts = (cValidBackouts[0] == '1');
		validRequests = (cValidRequests[0] == '1');
		validWarnings = (cValidWarnings[0] == '1');

		pDeadbeat = new clsDeadbeat(id,
									creationTime,
									lastModifiedTime,
									backouts,
									requests,
									warnings,
									validBackouts,
									validRequests,
									validWarnings);							

		Close(&mpCDAGetDeadbeat);
		SetStatement(NULL);
	}

	return pDeadbeat;
}

#define ORA_DEADBEAT_ARRAYSIZE 100

//
// GetAllDeadbeats
//
// Note: we want to return the records in order of decreasing
// deadbeat offenses, but since the deadbeat score is a non-positive
// number, this means ordering by ascending deadbeat score.  (mila)
static const char *SQL_GetAllDeadbeats = 
 "select	id,									\
			TO_CHAR(creation_date,				\
					'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(last_modified_date,			\
					'YYYY-MM-DD HH24:MI:SS'),	\
			valid_deadbeat_score,				\
			valid_credit_request_count,			\
			valid_warning_count,				\
			deadbeat_score,						\
			credit_request_count,				\
			warning_count						\
  from ebay_deadbeats							\
  order by deadbeat_score asc";

bool clsDatabaseOracle::GetAllDeadbeats(DeadbeatVector *pvDeadbeats)
{
	int		id[ORA_DEADBEAT_ARRAYSIZE];

	int		backouts[ORA_DEADBEAT_ARRAYSIZE];
	int		requests[ORA_DEADBEAT_ARRAYSIZE];
	int		warnings[ORA_DEADBEAT_ARRAYSIZE];

	sb2		backoutsInd[ORA_DEADBEAT_ARRAYSIZE];
	sb2		requestsInd[ORA_DEADBEAT_ARRAYSIZE];
	sb2		warningsInd[ORA_DEADBEAT_ARRAYSIZE];

	char	cValidBackouts[ORA_DEADBEAT_ARRAYSIZE][2];
	char	cValidRequests[ORA_DEADBEAT_ARRAYSIZE][2];
	char	cValidWarnings[ORA_DEADBEAT_ARRAYSIZE][2];

	bool	validBackouts[ORA_DEADBEAT_ARRAYSIZE];
	bool	validRequests[ORA_DEADBEAT_ARRAYSIZE];
	bool	validWarnings[ORA_DEADBEAT_ARRAYSIZE];

	time_t	creationTime[ORA_DEADBEAT_ARRAYSIZE];
	char	cCreationTime[ORA_DEADBEAT_ARRAYSIZE][32];

	time_t	lastModifiedTime[ORA_DEADBEAT_ARRAYSIZE];
	char	cLastModifiedTime[ORA_DEADBEAT_ARRAYSIZE][32];

	int		rowsFetched = 0;
	int		i, n;
	int		rc = 0;

	clsDeadbeat	*pDeadbeat;


	OpenAndParse(&mpCDAGetAllDeadbeats, SQL_GetAllDeadbeats);

	// Bind the output
	Define(1, &id[0]);
	Define(2, (char *)cCreationTime, sizeof(cCreationTime[0]));
	Define(3, (char *)cLastModifiedTime, sizeof(cLastModifiedTime[0]));
	Define(4, (char *)cValidBackouts, sizeof(cValidBackouts[0]));
	Define(5, (char *)cValidRequests, sizeof(cValidRequests[0]));
	Define(6, (char *)cValidWarnings, sizeof(cValidWarnings[0]));
	Define(7, &backouts[0], &backoutsInd[0]);
	Define(8, &requests[0], &requestsInd[0]);
	Define(9, &warnings[0], &warningsInd[0]);

	// Fetch
	Execute();

	if (CheckForNoRowsFound ())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetAllDeadbeats,true);
		SetStatement(NULL);
		return false;
	}

	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_DEADBEAT_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetAllDeadbeats,true);
			SetStatement(NULL);
			return false;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			ORACLE_DATEToTime(cCreationTime[i], &creationTime[i]);
			ORACLE_DATEToTime(cLastModifiedTime[i], &lastModifiedTime[i]);

			if (backoutsInd[i] == -1)
				backouts[i] = 0;
			if (requestsInd[i] == -1)
				requests[i] = 0;
			if (warningsInd[i] == -1)
				warnings[i] = 0;

			validBackouts[i] = (cValidBackouts[i][0] == '1');
			validRequests[i] = (cValidRequests[i][0] == '1');
			validWarnings[i] = (cValidWarnings[i][0] == '1');

			pDeadbeat = new clsDeadbeat(id[i],
										creationTime[i],
										lastModifiedTime[i],
										backouts[i],
										requests[i],
										warnings[i],
										validBackouts[i],
										validRequests[i],
										validWarnings[i]);

			pvDeadbeats->push_back(pDeadbeat);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetAllDeadbeats);
	SetStatement(NULL);

	return true;
}

//
// SetDeadbeatScore
// 
static const char *SQL_SetDeadbeatScore =
 "update ebay_deadbeats						\
	set deadbeat_score = :score,			\
		last_modified_date = sysdate		\
	where id = :id";

void clsDatabaseOracle::SetDeadbeatScore(int id, int score)
{
	OpenAndParse(&mpCDASetDeadbeatScore, SQL_SetDeadbeatScore);

	// Bind it, baby
	Bind(":id", &id);
	Bind(":score", &score);

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDASetDeadbeatScore);
		SetStatement(NULL);
		AddDeadbeat(id, score, 0, 0, true, true, true);
	}
	else
	{
		Commit();
		Close(&mpCDASetDeadbeatScore);
		SetStatement(NULL);
	}

	return;
}

//
// SetCreditRequestCount
// 
static const char *SQL_SetCreditRequestCount =
 "update ebay_deadbeats						\
	set credit_request_count = :count,		\
		last_modified_date = sysdate		\
	where id = :id";

void clsDatabaseOracle::SetCreditRequestCount(int id, int count)
{
	OpenAndParse(&mpCDASetCreditRequestCount, SQL_SetCreditRequestCount);

	// Bind it, baby
	Bind(":id", &id);
	Bind(":count", &count);

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDASetCreditRequestCount);
		SetStatement(NULL);
		AddDeadbeat(id, 0, count, 0, true, true, true);
	}
	else
	{
		Commit();
		Close(&mpCDASetCreditRequestCount);
		SetStatement(NULL);
	}

	return;
}

//
// SetWarningCount
// 
static const char *SQL_SetWarningCount =
 "update ebay_deadbeats					\
	set warning_count = :count,			\
		last_modified_date = sysdate	\
	where id = :id";

void clsDatabaseOracle::SetWarningCount(int id, int count)
{
	OpenAndParse(&mpCDASetWarningCount, SQL_SetWarningCount);

	// Bind it, baby
	Bind(":id", &id);
	Bind(":count", &count);

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDASetWarningCount);
		SetStatement(NULL);
		AddDeadbeat(id, 0, count, 0, true, true, true);
	}
	else
	{
		Commit();
		Close(&mpCDASetWarningCount);
		SetStatement(NULL);
	}

	return;
}

//
// GetDeadbeatScore
//
static const char *SQL_GetDeadbeatScore =
 "select deadbeat_score,			\
	valid_deadbeat_score			\
	from ebay_deadbeats				\
	where	id = :id";

int clsDatabaseOracle::GetDeadbeatScore(int id)
{
	int		score = 0;
	sb2		scoreInd;

	char	cValid[2];
	bool	isValid;

	memset(cValid, 0, sizeof(cValid));

	OpenAndParse(&mpCDAGetDeadbeatScore, SQL_GetDeadbeatScore);
	Bind(":id", &id);

	// Bind those happy little output variables. 
	Define(1, &score, &scoreInd);
	Define(2, (char *)&cValid, sizeof(cValid));

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		isValid = true;
	}
	else
	{
		if (scoreInd == -1)
			score = 0;
		isValid = (cValid[0] == '1');
	}

	// If the score is not valid, we must recompute it.
	if (!isValid)
	{
		score = -(GetDeadbeatItemCountByBidderId(id));
		SetDeadbeatScore(id, score);
		ValidateDeadbeatScore(id);
		isValid = true;
	}

	// Now everything is where it's supposed
	// to be.

	Close (&mpCDAGetDeadbeatScore);
	SetStatement(NULL);

	// Return score as non-positive number!!
	return score <= 0 ? score : -score;
}

//
// GetCreditRequestCount
//
static const char *SQL_GetCreditRequestCount =
 "select credit_request_count,		\
	valid_credit_request_count		\
	from ebay_deadbeats				\
	where	id = :id";

int clsDatabaseOracle::GetCreditRequestCount(int id)
{
	int		count = 0;
	sb2		countInd;

	char	cValid[2];
	bool	isValid;


	memset(cValid, 0, sizeof(cValid));

	OpenAndParse(&mpCDAGetCreditRequestCount,SQL_GetCreditRequestCount);
	Bind(":id", &id);

	// Bind those happy little output variables. 
	Define(1, &count, &countInd);
	Define(2, (char *)&cValid, sizeof(cValid));

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		isValid = true;
	}
	else
	{
		if (countInd == -1)
			count = 0;
		isValid = (cValid[0] == '1');
	}

	// If the count is not valid, we must recompute it.
	if (!isValid)
	{
		count = GetDeadbeatItemCountBySellerId(id);
//		count = GetCreditRequestCountBySellerId(id);
		SetCreditRequestCount(id, count);
		ValidateCreditRequestCount(id);
		isValid = true;
	}

	// Now everything is where it's supposed
	// to be.

	Close (&mpCDAGetCreditRequestCount);
	SetStatement(NULL);

	// Return score as non-positive number!!
	return count;
}

//
// GetWarningCount
//
static const char *SQL_GetWarningCount =
 "select warning_count,				\
	valid_warning_count				\
	from ebay_deadbeats				\
	where	id = :id";

int clsDatabaseOracle::GetWarningCount(int id)
{
	int		count = 0;
	sb2		countInd;

	char	cValid[2];
	bool	isValid;


	memset(cValid, 0, sizeof(cValid));

	OpenAndParse(&mpCDAGetWarningCount, SQL_GetWarningCount);
	Bind(":id", &id);

	// Bind those happy little output variables. 
	Define(1, &count, &countInd);
	Define(2, (char *)&cValid, sizeof(cValid));

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		isValid = true;
	}
	else
	{
		if (countInd == -1)
			count = 0;
		isValid = (cValid[0] == '1');
	}

	// If the score is not valid, we must recompute it.
	if (!isValid)
	{
		count = GetDeadbeatItemsWarnedCountByBidderId(id);
		SetWarningCount(id, count);
		ValidateWarningCount(id);
		isValid = true;
	}

	// Now everything is where it's supposed
	// to be.

	Close (&mpCDAGetWarningCount);
	SetStatement(NULL);

	// Return score as non-positive number!!
	return count;
}

//
// IsDeadbeatUser
//
bool clsDatabaseOracle::IsDeadbeatUser(int id)
{
	return GetDeadbeatScore(id) <= 0;
}

//
// UserHasCreditRequests
//
bool clsDatabaseOracle::UserHasCreditRequests(int id)
{
	return GetCreditRequestCount(id) > 0;
}

//
// UserHasWarnings
//
bool clsDatabaseOracle::UserHasWarnings(int id)
{
	return GetWarningCount(id) > 0;
}

//
// InvalidateDeadbeatScore
//
static const char *SQL_InvalidateDeadbeatScore =
 "update ebay_deadbeats					\
	set valid_deadbeat_score = \'0\',	\
		last_modified_date = sysdate	\
	where id = :id";

bool clsDatabaseOracle::InvalidateDeadbeatScore(int id)
{
	bool invalidated = false;

	OpenAndParse(&mpCDAInvalidateDeadbeatScore, SQL_InvalidateDeadbeatScore);

	// Bind it, baby
	Bind(":id", &id);

	// Do it...
	Execute();

	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAInvalidateDeadbeatScore);
		SetStatement(NULL);
		AddDeadbeat(id, 0, 0, 0, false, false, false);
	}
	else
	{
		Commit();
		Close(&mpCDAInvalidateDeadbeatScore);
		SetStatement(NULL);
		invalidated = true;
	}

	return invalidated;
}

//
// ValidateDeadbeatScore
//
static const char *SQL_ValidateDeadbeatScore =
 "update ebay_deadbeats					\
	set valid_deadbeat_score = \'1\',	\
		last_modified_date = sysdate	\
	where id = :id";

bool clsDatabaseOracle::ValidateDeadbeatScore(int id)
{
	bool validated = false;

	OpenAndParse(&mpCDAValidateDeadbeatScore, SQL_ValidateDeadbeatScore);

	// Bind it, baby
	Bind(":id", &id);

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAValidateDeadbeatScore);
		SetStatement(NULL);
	}
	else
	{
		Commit();
		Close(&mpCDAValidateDeadbeatScore);
		SetStatement(NULL);
		validated = true;
	}

	return validated;
}

//
// InvalidateCreditRequestCount
//
static const char *SQL_InvalidateCreditRequestCount =
 "update ebay_deadbeats							\
	set valid_credit_request_count = \'0\',		\
		last_modified_date = sysdate			\
	where id = :id";

bool clsDatabaseOracle::InvalidateCreditRequestCount(int id)
{
	bool invalidated = false;

	OpenAndParse(&mpCDAInvalidateCreditRequestCount, SQL_InvalidateCreditRequestCount);

	// Bind it, baby
	Bind(":id", &id);

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAInvalidateCreditRequestCount);
		SetStatement(NULL);
		AddDeadbeat(id, 0, 0, 0, false, false, false);
	}
	else
	{
		Commit();
		Close(&mpCDAInvalidateCreditRequestCount);
		SetStatement(NULL);
		invalidated = true;
	}

	return invalidated;
}

//
// ValidateCreditRequestCount
//
static const char *SQL_ValidateCreditRequestCount =
 "update ebay_deadbeats							\
	set valid_credit_request_count = \'1\',		\
		last_modified_date = sysdate			\
	where id = :id";

bool clsDatabaseOracle::ValidateCreditRequestCount(int id)
{
	bool validated = false;

	OpenAndParse(&mpCDAValidateCreditRequestCount, SQL_ValidateCreditRequestCount);

	// Bind it, baby
	Bind(":id", &id);

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAValidateCreditRequestCount);
		SetStatement(NULL);
	}
	else
	{
		Commit();
		Close(&mpCDAValidateCreditRequestCount);
		SetStatement(NULL);
		validated = true;
	}

	return validated;
}

//
// InvalidateWarningCount
//
static const char *SQL_InvalidateWarningCount =
 "update ebay_deadbeats					\
	set valid_warning_count = \'0\',	\
		last_modified_date = sysdate	\
	where id = :id";

bool clsDatabaseOracle::InvalidateWarningCount(int id)
{
	bool invalidated = false;

	OpenAndParse(&mpCDAInvalidateWarningCount, SQL_InvalidateWarningCount);

	// Bind it, baby
	Bind(":id", &id);

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAInvalidateWarningCount);
		SetStatement(NULL);
		AddDeadbeat(id, 0, 0, 0, false, false, false);
	}
	else
	{
		Commit();
		Close(&mpCDAInvalidateWarningCount);
		SetStatement(NULL);
		invalidated = true;
	}

	return invalidated;
}

//
// ValidateWarningCount
//
static const char *SQL_ValidateWarningCount =
 "update ebay_deadbeats					\
	set valid_warning_count = \'1\',	\
		last_modified_date = sysdate	\
	where id = :id";

bool clsDatabaseOracle::ValidateWarningCount(int id)
{
	bool validated = false;

	OpenAndParse(&mpCDAValidateWarningCount, SQL_ValidateWarningCount);

	// Bind it, baby
	Bind(":id", &id);

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAValidateWarningCount);
		SetStatement(NULL);
	}
	else
	{
		Commit();
		Close(&mpCDAValidateWarningCount);
		SetStatement(NULL);
		validated = true;
	}

	return validated;
}

//=========================== Deadbeat Items ============================//

//
// ClearAllDeadbeatItems
//
static char *SQL_DeleteAllDeadbeatItems =
 "delete from ebay_deadbeat_items";

void clsDatabaseOracle::ClearAllDeadbeatItems()
{
	OpenAndParse(&mpCDAOneShot, SQL_DeleteAllDeadbeatItems);
	Execute();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	Commit();
	return;
}

//
// GetDeadbeatItem
//
static const char *SQL_GetDeadbeatItem =
 "select	/*+ index(ebay_deadbeat_items ebay_deadbeat_item_id_index) */	\
			id,										\
			seller,									\
			bidder,									\
			TO_CHAR(sale_start,						\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),	\
			title,									\
			price,									\
			quantity,								\
			reason_code,							\
			transaction_id,							\
			notification_sent,						\
			TO_CHAR(created,						\
				'YYYY-MM-DD HH24:MI:SS'),			\
			TO_CHAR(last_modified,					\
				'YYYY-MM-DD HH24:MI:SS'),			\
			ROWIDTOCHAR(rowid)						\
	from ebay_deadbeat_items						\
	where	marketplace = :marketplace				\
	and		id = :itemid							\
	and		seller = :seller						\
	and		bidder = :bidder";

static const char *SQL_GetDeadbeatItemWithRowId =
 "select	id,										\
			seller,									\
			bidder,									\
			TO_CHAR(sale_start,						\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),	\
			title,									\
			price,									\
			quantity,								\
			reason_code,							\
			transaction_id,							\
			notification_sent,						\
			TO_CHAR(created,						\
				'YYYY-MM-DD HH24:MI:SS'),			\
			TO_CHAR(last_modified,					\
				'YYYY-MM-DD HH24:MI:SS'),			\
			ROWIDTOCHAR(rowid)						\
	from ebay_deadbeat_items						\
	where	rowid = CHARTOROWID(:thisrow)";

bool clsDatabaseOracle::GetDeadbeatItem(MarketPlaceId marketplace,
										int id,
										int seller,
										int bidder,
									    clsDeadbeatItem *pItem,
										char *pRowId,
										time_t delta)
{
	// Temporary slots for things to live in
	int					itemId;
	int					sellerId;
	int					bidderId;

	char				sale_start[32];
	time_t				sale_start_time;

	char				sale_end[32];
	time_t				sale_end_time;

	char				title[255];
	char				*pTitle;

	float				price;
	int					quantity;

	char				reasonCode[3];
	char				*pReasonCode;

	int					transactionId;

	char				notified[2];
	bool				notificationSent;

	time_t				creationTime;
	char				cCreationTime[32];

	time_t				lastModifiedTime;
	char				cLastModifiedTime[32];

	char				itemRowId[20];

	time_t				theTime;
	bool				userowid;

	// 3600 = 1 hour; we want a little over 1 hour for rowid to be obsolete.
	const	int	time_diff = 4000;

	// test if time is within limits, then use rowid to fetch
	time(&theTime);

	userowid = (theTime < (delta + 4000)) && (pRowId != NULL);

	// Bind the input variable(s)
	if (userowid)
	{
		// use rowid to query
		OpenAndParse(&mpCDAGetSingleDeadbeatItemRowId, SQL_GetDeadbeatItemWithRowId);
		Bind(":thisrow", (char *)&pRowId);

	}
	else
	{
		// use item id to query
		OpenAndParse(&mpCDAGetSingleDeadbeatItem, SQL_GetDeadbeatItem);
		Bind(":marketplace", &marketplace);
		Bind(":itemid", &id);
		Bind(":seller", &seller);
		Bind(":bidder", &bidder);
	}

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, &itemId);
	Define(2, &sellerId);
	Define(3, &bidderId);
	Define(4, sale_start, sizeof(sale_start));
	Define(5, sale_end, sizeof(sale_end));
	Define(6, title, sizeof(title));
	Define(7, &price);
	Define(8, &quantity);
	Define(9, reasonCode, sizeof(reasonCode));
	Define(10, &transactionId);
	Define(11, notified, sizeof(notified));
	Define(12, cCreationTime, sizeof(cCreationTime));
	Define(13, cLastModifiedTime, sizeof(cLastModifiedTime));
	Define(14, itemRowId, sizeof(itemRowId));

	// Fetch
	ExecuteAndFetch();

	// if we can't find it with rowid, or id mismatched,
	// get it with item id
	if (userowid && (CheckForNoRowsFound() || (id != itemId)))
	{
		Close(&mpCDAGetSingleDeadbeatItemRowId);
		SetStatement(NULL);
		// get it with item id.
		
		return GetDeadbeatItem(marketplace, id, seller, bidder, pItem);
	}

	// if no item found, then return
	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetSingleDeadbeatItem);
		SetStatement(NULL);
		return false;
	}

	// Now everything is where it's supposed
	// to be. Let's make copies of the title
	// and location for the item
	pTitle		= new char[strlen(title) + 1];
	strcpy(pTitle, (char *)title);

	// Time Conversions
	ORACLE_DATEToTime(sale_start, &sale_start_time);
	ORACLE_DATEToTime(sale_end, &sale_end_time);
	ORACLE_DATEToTime(cCreationTime, &creationTime);
	ORACLE_DATEToTime(cLastModifiedTime, &lastModifiedTime);

	// Transform notification flag.
	notificationSent	= (notified[0] == '1');

	pReasonCode	= new char[strlen(reasonCode) + 1];
	strcpy(pReasonCode, reasonCode);

	// Fill in the item
	pItem->Set(marketplace,
			   id,
			   seller,
			   bidder,
			   sale_start_time,
			   sale_end_time,
			   pTitle,
			   price,
			   quantity,
			   pReasonCode,
			   transactionId,
			   notificationSent,
			   creationTime,
			   lastModifiedTime,
			   itemRowId,
			   delta);

	if (userowid)
	{
		Close(&mpCDAGetSingleDeadbeatItemRowId);
		SetStatement(NULL);
	}
	else
	{
		Close(&mpCDAGetSingleDeadbeatItem);
		SetStatement(NULL);
	};

	return true;
}

bool clsDatabaseOracle::GetDeadbeatItem(int id,
										int seller,
										int bidder,
									    clsDeadbeatItem *pItem,
									    char *pRowId,
									    time_t delta)
{
	return GetDeadbeatItem(gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
						   id,
						   seller,
						   bidder,
						   pItem, 
						   pRowId, 
						   delta);
}

#define ORA_DEADBEAT_ITEM_ARRAYSIZE 100

//
// GetDeadbeatItemsByBidderId
//
static const char *SQL_GetDeadbeatItemsByBidderId =
 "select	/*+ index(ebay_deadbeat_items ebay_deadbeat_item_bid_index) */	\
			id,										\
			seller,									\
			bidder,									\
			TO_CHAR(sale_start,						\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),	\
			title,									\
			price,									\
			quantity,								\
			reason_code,							\
			transaction_id,							\
			notification_sent,						\
			TO_CHAR(created,						\
				'YYYY-MM-DD HH24:MI:SS'),			\
			TO_CHAR(last_modified,					\
				'YYYY-MM-DD HH24:MI:SS'),			\
			ROWIDTOCHAR(rowid)						\
	from ebay_deadbeat_items						\
	where	marketplace = :marketplace				\
	and		bidder = :bidderid						\
	order by created desc";

bool clsDatabaseOracle::GetDeadbeatItemsByBidderId(MarketPlaceId marketplace,
												   int id,
												   DeadbeatItemVector *pvItems)
{
	// Temporary slots for things to live in
	int					itemId[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				title[ORA_DEADBEAT_ITEM_ARRAYSIZE][255];
	int					seller[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				sale_start[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];
	time_t				sale_start_time[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				sale_end[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];
	time_t				sale_end_time[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	float				price[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	int					quantity[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	int					bidder[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	char				notified[ORA_DEADBEAT_ITEM_ARRAYSIZE][2];
	bool				notificationSent;

	char				*pTitle[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	time_t				creationTime[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				cCreation[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];

	time_t				lastModifiedTime[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				cLastModified[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];

	char				reasonCode[ORA_DEADBEAT_ITEM_ARRAYSIZE][3];
	char				*pReasonCode[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	int					transactionId[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	char				itemRowId[ORA_DEADBEAT_ITEM_ARRAYSIZE][20];

	time_t				theTime;

	clsDeadbeatItem*	pItem = NULL;

	int					rowsFetched = 0;
	int					i, n;
	int					rc = 0;

	// by definition, a deadbeat item must have a high bidder, so return false
	// if the id passed in is 0
	if (id == 0)
		return false;

	// test if time is within limits, then use rowid to fetch
	time(&theTime);

	// use item id to query
	OpenAndParse(&mpCDAGetDeadbeatItemsByBidderId, SQL_GetDeadbeatItemsByBidderId);
	Bind(":marketplace", &marketplace);
	Bind(":bidderid", &id);

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, (int *)&itemId);
	Define(2, (int *)&seller);
	Define(3, (int *)&bidder);
	Define(4, (char *)sale_start, sizeof(sale_start[0]));
	Define(5, (char *)sale_end, sizeof(sale_end[0]));
	Define(6, (char *)title, sizeof(title[0]));
	Define(7, (float *)&price);
	Define(8, (int *)&quantity);
	Define(9, (char *)reasonCode, sizeof(reasonCode[0]));
	Define(10, (int *)&transactionId);
	Define(11, (char *)notified, sizeof(notified[0]));
	Define(12, (char *)cCreation, sizeof(cCreation[0]));
	Define(13, (char *)cLastModified, sizeof(cLastModified[0]));
	Define(14, (char *)&itemRowId, sizeof(itemRowId[0]));

	// Fetch
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetDeadbeatItemsByBidderId);
		SetStatement(NULL);
		return false;
	}

	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_DEADBEAT_ITEM_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetDeadbeatItemsByBidderId);
			SetStatement(NULL);
			return false;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			// Now everything is where it's supposed
			// to be. Let's  copy the title of the item
			pTitle[i] = new char[strlen(title[i]) + 1];
			strcpy(pTitle[i], (char *)title[i]);

			// Time Conversions
			ORACLE_DATEToTime(sale_start[i], &sale_start_time[i]);
			ORACLE_DATEToTime(sale_end[i], &sale_end_time[i]);
			ORACLE_DATEToTime(cCreation[i], &creationTime[i]);
			ORACLE_DATEToTime(cLastModified[i], &lastModifiedTime[i]);

			// Transform flags.
			notificationSent = (notified[i][0] == '1');

			pReasonCode[i]	= new char[strlen(reasonCode[i]) + 1];
			strcpy(pReasonCode[i], reasonCode[i]);

			// Create and fill in the item
			pItem = new clsDeadbeatItem;
			pItem->Set(marketplace,
					   itemId[i],
					   seller[i],
					   bidder[i],
					   sale_start_time[i],
					   sale_end_time[i],
					   pTitle[i],
					   price[i],
					   quantity[i],
					   pReasonCode[i],
					   transactionId[i],
					   notificationSent,
					   creationTime[i],
					   lastModifiedTime[i],
					   itemRowId[i],
					   0);

			pvItems->push_back(pItem);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetDeadbeatItemsByBidderId);
	SetStatement(NULL);

	return true;
}

bool clsDatabaseOracle::GetDeadbeatItemsByBidderId(int id,
									    DeadbeatItemVector *pvItems)
{
	return GetDeadbeatItemsByBidderId(
					gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
					id,
					pvItems);
}

//
// GetDeadbeatItemsBySellerId
//
static const char *SQL_GetDeadbeatItemsBySellerId =
 "select	/*+ index(ebay_deadbeat_items ebay_deadbeat_item_sell_index) */	\
			id,										\
			seller,									\
			bidder,									\
			TO_CHAR(sale_start,						\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),	\
			title,									\
			price,									\
			quantity,								\
			reason_code,							\
			transaction_id,							\
			notification_sent,						\
			TO_CHAR(created,						\
				'YYYY-MM-DD HH24:MI:SS'),			\
			TO_CHAR(last_modified,					\
				'YYYY-MM-DD HH24:MI:SS'),			\
			ROWIDTOCHAR(rowid)						\
	from ebay_deadbeat_items						\
	where	marketplace = :marketplace				\
	and		seller = :sellerid						\
	order by created desc";

bool clsDatabaseOracle::GetDeadbeatItemsBySellerId(MarketPlaceId marketplace,
												   int id,
												   DeadbeatItemVector *pvItems)
{
	// Temporary slots for things to live in
	int					itemId[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				title[ORA_DEADBEAT_ITEM_ARRAYSIZE][255];
	int					seller[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				sale_start[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];
	time_t				sale_start_time[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				sale_end[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];
	time_t				sale_end_time[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	float				price[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	int					quantity[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	int					bidder[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	char				notified[ORA_DEADBEAT_ITEM_ARRAYSIZE][2];
	bool				notificationSent;

	char				*pTitle[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	time_t				creationTime[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				cCreation[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];

	time_t				lastModifiedTime[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				cLastModified[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];

	char				reasonCode[ORA_DEADBEAT_ITEM_ARRAYSIZE][3];
	char				*pReasonCode[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	int					transactionId[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	char				itemRowId[ORA_DEADBEAT_ITEM_ARRAYSIZE][20];

	time_t				theTime;

	clsDeadbeatItem*	pItem = NULL;

	int					rowsFetched = 0;
	int					i, n;
	int					rc = 0;

	// by definition, a deadbeat item must have a high bidder, so return false
	// if the id passed in is 0
	if (id == 0)
		return false;

	// test if time is within limits, then use rowid to fetch
	time(&theTime);

	// use item id to query
	OpenAndParse(&mpCDAGetDeadbeatItemsBySellerId, SQL_GetDeadbeatItemsBySellerId);

	Bind(":marketplace", &marketplace);
	Bind(":sellerid", &id);

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, (int *)&itemId);
	Define(2, (int *)&seller);
	Define(3, (int *)&bidder);
	Define(4, (char *)sale_start, sizeof(sale_start[0]));
	Define(5, (char *)sale_end, sizeof(sale_end[0]));
	Define(6, (char *)title, sizeof(title[0]));
	Define(7, (float *)&price);
	Define(8, (int *)&quantity);
	Define(9, (char *)reasonCode, sizeof(reasonCode[0]));
	Define(10, (int *)&transactionId);
	Define(11, (char *)notified, sizeof(notified[0]));
	Define(12, (char *)cCreation, sizeof(cCreation[0]));
	Define(13, (char *)cLastModified, sizeof(cLastModified[0]));
	Define(14, (char *)&itemRowId, sizeof(itemRowId[0]));

	// Fetch
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetDeadbeatItemsBySellerId);
		SetStatement(NULL);
		return false;
	}

	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_DEADBEAT_ITEM_ARRAYSIZE);

		assert(mpCDACurrent);
		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&mpCDAGetDeadbeatItemsBySellerId);
			SetStatement(NULL);
			return false;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			// Now everything is where it's supposed
			// to be. Let's make copy the title of the item
			pTitle[i] = new char[strlen(title[i]) + 1];
			strcpy(pTitle[i], (char *)title[i]);

			// Time Conversions
			ORACLE_DATEToTime(sale_start[i], &sale_start_time[i]);
			ORACLE_DATEToTime(sale_end[i], &sale_end_time[i]);
			ORACLE_DATEToTime(cCreation[i], &creationTime[i]);
			ORACLE_DATEToTime(cLastModified[i], &lastModifiedTime[i]);

			// Transform flags.
			notificationSent = (notified[i][0] == '1');

			pReasonCode[i]	= new char[strlen(reasonCode[i]) + 1];
			strcpy(pReasonCode[i], reasonCode[i]);

			// Create and fill in the item
			pItem = new clsDeadbeatItem;
			pItem->Set(marketplace,
					   itemId[i],
					   seller[i],
					   bidder[i],
					   sale_start_time[i],
					   sale_end_time[i],
					   pTitle[i],
					   price[i],
					   quantity[i],
					   pReasonCode[i],
					   transactionId[i],
					   notificationSent,
					   creationTime[i],
					   lastModifiedTime[i],
					   itemRowId[i],
					   0);

			pvItems->push_back(pItem);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetDeadbeatItemsBySellerId);
	SetStatement(NULL);

	return true;
}

bool clsDatabaseOracle::GetDeadbeatItemsBySellerId(int id,
									    DeadbeatItemVector *pvItems)
{
	return GetDeadbeatItemsBySellerId(
					gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
					id,
					pvItems);
}

//
// GetAllDeadbeatItems
//
static const char *SQL_GetAllDeadbeatItems =
 "select	/*+ index(ebay_deadbeat_items ebay_deadbeat_item_id_index) */	\
			id,										\
			seller,									\
			bidder,									\
			TO_CHAR(sale_start,						\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),	\
			title,									\
			price,									\
			quantity,								\
			reason_code,							\
			transaction_id,							\
			notification_sent,						\
			TO_CHAR(created,						\
				'YYYY-MM-DD HH24:MI:SS'),			\
			TO_CHAR(last_modified,					\
				'YYYY-MM-DD HH24:MI:SS'),			\
			ROWIDTOCHAR(rowid)						\
	from ebay_deadbeat_items						\
	where	marketplace = :marketplace";

bool clsDatabaseOracle::GetAllDeadbeatItems(MarketPlaceId marketplace,
										    DeadbeatItemVector *pvItems)
{
	// Temporary slots for things to live in
	int					itemId[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				title[ORA_DEADBEAT_ITEM_ARRAYSIZE][255];
	int					seller[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				sale_start[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];
	time_t				sale_start_time[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				sale_end[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];
	time_t				sale_end_time[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	float				price[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	int					quantity[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	int					bidder[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	char				notified[ORA_DEADBEAT_ITEM_ARRAYSIZE][2];
	bool				notificationSent;

	char				*pTitle[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	time_t				creationTime[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				cCreation[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];

	time_t				lastModifiedTime[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				cLastModified[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];

	char				reasonCode[ORA_DEADBEAT_ITEM_ARRAYSIZE][3];
	char				*pReasonCode[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	int					transactionId[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	char				itemRowId[ORA_DEADBEAT_ITEM_ARRAYSIZE][20];

	time_t				theTime;

	clsDeadbeatItem*	pItem = NULL;

	int					rowsFetched = 0;
	int					i, n;
	int					rc = 0;

	// test if time is within limits, then use rowid to fetch
	time(&theTime);

	// use item id to query
	OpenAndParse(&mpCDAGetAllDeadbeatItems, SQL_GetAllDeadbeatItems);
	Bind(":marketplace", &marketplace);

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, (int *)&itemId);
	Define(2, (int *)&seller);
	Define(3, (int *)&bidder);
	Define(4, (char *)sale_start, sizeof(sale_start[0]));
	Define(5, (char *)sale_end, sizeof(sale_end[0]));
	Define(6, (char *)title, sizeof(title[0]));
	Define(7, (float *)&price);
	Define(8, (int *)&quantity);
	Define(9, (char *)reasonCode, sizeof(reasonCode[0]));
	Define(10, (int *)&transactionId);
	Define(11, (char *)notified, sizeof(notified[0]));
	Define(12, (char *)cCreation, sizeof(cCreation[0]));
	Define(13, (char *)cLastModified, sizeof(cLastModified[0]));
	Define(14, (char *)&itemRowId, sizeof(itemRowId[0]));

	// Fetch
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetAllDeadbeatItems);
		SetStatement(NULL);
		return false;
	}

	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_DEADBEAT_ITEM_ARRAYSIZE);

		assert(mpCDACurrent);
		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&mpCDAGetAllDeadbeatItems);
			SetStatement(NULL);
			return false;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			// Now everything is where it's supposed
			// to be. Let's copy the title of the item
			pTitle[i] = new char[strlen(title[i]) + 1];
			strcpy(pTitle[i], (char *)title[i]);

			// Time Conversions
			ORACLE_DATEToTime(sale_start[i], &sale_start_time[i]);
			ORACLE_DATEToTime(sale_end[i], &sale_end_time[i]);
			ORACLE_DATEToTime(cCreation[i], &creationTime[i]);
			ORACLE_DATEToTime(cLastModified[i], &lastModifiedTime[i]);

			// Transform flags.
			notificationSent = (notified[i][0] == '1');

			pReasonCode[i]	= new char[strlen(reasonCode[i]) + 1];
			strcpy(pReasonCode[i], reasonCode[i]);

			// Create and fill in the item
			pItem = new clsDeadbeatItem;
			pItem->Set(marketplace,
					   itemId[i],
					   seller[i],
					   bidder[i],
					   sale_start_time[i],
					   sale_end_time[i],
					   pTitle[i],
					   price[i],
					   quantity[i],
					   pReasonCode[i],
					   transactionId[i],
					   notificationSent,
					   creationTime[i],
					   lastModifiedTime[i],
					   itemRowId[i],
					   0);

			pvItems->push_back(pItem);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetAllDeadbeatItems);
	SetStatement(NULL);

	return true;
}

bool clsDatabaseOracle::GetAllDeadbeatItems(DeadbeatItemVector *pvItems)
{
	return GetAllDeadbeatItems(
					gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
					pvItems);
}

//
// AddDeadbeatItem
//
static const char *SQL_AddDeadbeatItem =
 "insert into ebay_deadbeat_items			\
	(	marketplace,						\
		id,									\
		seller,								\
		bidder,								\
		sale_start,							\
		sale_end,							\
		title,								\
		price,								\
		quantity,							\
		reason_code,						\
		transaction_id,						\
		notification_sent,					\
		created,							\
		last_modified						\
	)										\
  values									\
    (	:marketplace,						\
		:itemid,							\
		:seller,							\
		:hibidder,							\
		TO_DATE(:sales,						\
				'YYYY-MM-DD HH24:MI:SS'),	\
		TO_DATE(:salee,						\
				'YYYY-MM-DD HH24:MI:SS'),	\
		:title,								\
		:price,								\
		:quantity,							\
		:reason,							\
		:xactionid,							\
		:notified,							\
		sysdate,							\
		sysdate								\
	)";

void clsDatabaseOracle::AddDeadbeatItem(clsDeadbeatItem *pItem)
{
	int					marketplaceid;
	int					id;
	int					seller;
	char				sale_start[32] = {0};
	char				sale_end[32] = {0};
	float				price;
	int					quantity;
	int					bidder;

	char				notified[2];

	struct tm			*pTheTime;
	time_t				tTime;

	char				reasonCode[3];
	int					transactionId;

	// Check for NULL item.
	if (pItem == NULL)
	{
		return;
	}

	// Extract things from the item into our
	// local variables to prevent any casting
	// confusion
	marketplaceid	= pItem->GetMarketPlaceId();
	id				= pItem->GetId();
	seller			= pItem->GetSeller();
	price			= pItem->GetPrice();
	quantity		= pItem->GetQuantity();
	bidder			= pItem->GetBidder();

	strcpy(reasonCode, pItem->GetReasonCode());

	transactionId	= pItem->GetTransactionId();

	// Transform Bools to chars
	if (pItem->GetNotified())
		strcpy(notified, "1");
	else
		strcpy(notified, "0");	

	// Date conversion
	tTime			= pItem->GetEndTime();
	pTheTime	= localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime,   sale_end);

	tTime			= pItem->GetStartTime();
	pTheTime	= localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime,   sale_start);

	// We don't use this statement very often,
	// so the cursor's not persistant. Let's 
	// prepare the statement
	OpenAndParse(&mpCDAAddDeadbeatItem, SQL_AddDeadbeatItem);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplaceid);
	Bind(":itemid", &id);
	Bind(":seller", &seller);
	Bind(":hibidder", &bidder);
	Bind(":sales", (char *)sale_start);
	Bind(":salee", (char *)sale_end);
	Bind(":title", (char *)pItem->GetTitle());
	Bind(":price", &price);
	Bind(":quantity", &quantity);
	Bind(":reason", (char *)reasonCode);
	Bind(":xactionid", &transactionId);
	Bind(":notified", (char *)notified);

	// Let's do it!
	Execute();

	// Commit
	Commit();

	// Free things
	Close(&mpCDAAddDeadbeatItem);
	SetStatement(NULL);

	return;
}


//
// DeleteDeadbeatItem
//
// Deletes an item and its description by id
//
static const char *SQL_DeleteDeadbeatItem =
 "delete from ebay_deadbeat_items		\
	where	marketplace = :marketplace	\
	and		id = :id					\
	and		seller = :seller			\
	and		bidder = :bidder";

void clsDatabaseOracle::DeleteDeadbeatItem(MarketPlaceId marketplace,
										   int id,
										   int seller,
										   int bidder)
{
	OpenAndParse(&mpCDADeleteDeadbeatItem, SQL_DeleteDeadbeatItem);

	// Ok, let's do some binds
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":seller", &seller);
	Bind(":bidder", &bidder);

	// Just do it!
	Execute();
	Commit();

	Close(&mpCDADeleteDeadbeatItem);
	SetStatement(NULL);
	return;
}

//
// IsDeadbeatItem
//
bool clsDatabaseOracle::IsDeadbeatItem(MarketPlaceId marketplaceId,
									   int id,
									   int seller,
									   int bidder)
{
	clsDeadbeatItem item;
	bool gotItem;

	if (GetDeadbeatItem(marketplaceId, id, seller, bidder, &item, NULL, 0))
	{
		gotItem = true;
	}
	else
	{
		gotItem = false;
	}

	return gotItem;
}

//
// GetDeadbeatItemCountByBidderId
//
static const char *SQL_GetDeadbeatItemCountByBidderId =
 "select count(*)						\
	from ebay_deadbeat_items			\
	where	marketplace = :marketplace	\
	and		bidder = :bidder";

int clsDatabaseOracle::GetDeadbeatItemCountByBidderId(MarketPlaceId marketplace, int bidder)
{
	int	count;

	OpenAndParse(&mpCDAGetDeadbeatItemCountByBidderId,SQL_GetDeadbeatItemCountByBidderId);
	Bind(":marketplace", &marketplace);
	Bind(":bidder", &bidder);

	// Bind those happy little output variables. 
	Define(1, &count);

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		count = 0;
	}

	// Now everything is where it's supposed
	// to be.

	Close (&mpCDAGetDeadbeatItemCountByBidderId);
	SetStatement(NULL);

	// Return score as non-positive number!!
	return count;
}

//
// GetDeadbeatItemCountByBidderId
//
int clsDatabaseOracle::GetDeadbeatItemCountByBidderId(int bidder)
{
	return GetDeadbeatItemCountByBidderId(
					gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
					bidder);
}

//
// GetDeadbeatItemCountBySellerId
//
static const char *SQL_GetDeadbeatItemCountBySellerId =
 "select count(*)						\
	from ebay_deadbeat_items			\
	where	marketplace = :marketplace	\
	and		seller = :seller";

int clsDatabaseOracle::GetDeadbeatItemCountBySellerId(MarketPlaceId marketplace, int seller)
{
	int	count;

	OpenAndParse(&mpCDAGetDeadbeatItemCountBySellerId,SQL_GetDeadbeatItemCountBySellerId);
	Bind(":marketplace", &marketplace);
	Bind(":seller", &seller);

	// Bind those happy little output variables. 
	Define(1, &count);

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		count = 0;
	}

	// Now everything is where it's supposed
	// to be.

	Close (&mpCDAGetDeadbeatItemCountBySellerId);
	SetStatement(NULL);

	// Return score as non-positive number!!
	return count;
}

//
// GetDeadbeatItemCountBySellerId
//
int clsDatabaseOracle::GetDeadbeatItemCountBySellerId(int seller)
{
	return GetDeadbeatItemCountBySellerId(
					gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
					seller);
}


//
// GetDeadbeatItemsWarnedCountByBidderId
//
static const char *SQL_GetDeadbeatItemsBidderWarnedCount =
 "select count(*)						\
	from ebay_deadbeat_items			\
	where	marketplace = :marketplace	\
	and		bidder = :bidder			\
	and		notification_sent = \'1\'";

int clsDatabaseOracle::GetDeadbeatItemsWarnedCountByBidderId(MarketPlaceId marketplace, int bidder)
{
	int	count;

	OpenAndParse(&mpCDAGetDeadbeatItemsWarnedCountByBidderId,SQL_GetDeadbeatItemsBidderWarnedCount);
	Bind(":marketplace", &marketplace);
	Bind(":bidder", &bidder);

	// Bind those happy little output variables. 
	Define(1, &count);

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		count = 0;
	}

	// Now everything is where it's supposed
	// to be.

	Close (&mpCDAGetDeadbeatItemsWarnedCountByBidderId);
	SetStatement(NULL);

	// Return score as non-positive number!!
	return count;
}

//
// GetDeadbeatItemsWarnedCountByBidderId
//
int clsDatabaseOracle::GetDeadbeatItemsWarnedCountByBidderId(int bidder)
{
	return GetDeadbeatItemsWarnedCountByBidderId(
					gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
					bidder);
}

//
// GetDeadbeatItemsNotWarned
//
static const char *SQL_GetDeadbeatItemsNotWarned =
 "select	id,										\
			seller,									\
			bidder,									\
			TO_CHAR(sale_start,						\
						'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(sale_end,						\
						'YYYY-MM-DD HH24:MI:SS'),	\
			title,									\
			price,									\
			quantity,								\
			reason_code,							\
			transaction_id,							\
			notification_sent,						\
			TO_CHAR(created,						\
				'YYYY-MM-DD HH24:MI:SS'),			\
			TO_CHAR(last_modified,					\
				'YYYY-MM-DD HH24:MI:SS'),			\
			ROWIDTOCHAR(rowid)						\
	from ebay_deadbeat_items						\
	where	marketplace = :marketplace				\
	and		notification_sent = \'0\'				\
	order by created asc";

bool clsDatabaseOracle::GetDeadbeatItemsNotWarned(MarketPlaceId marketplace,
												  DeadbeatItemVector *pvItems)
{
	// Temporary slots for things to live in
	int					itemId[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				title[ORA_DEADBEAT_ITEM_ARRAYSIZE][255];
	int					seller[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				sale_start[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];
	time_t				sale_start_time[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				sale_end[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];
	time_t				sale_end_time[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	float				price[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	int					quantity[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	int					bidder[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	char				notified[ORA_DEADBEAT_ITEM_ARRAYSIZE][2];
	bool				notificationSent;

	char				*pTitle[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	time_t				creationTime[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				cCreation[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];

	time_t				lastModifiedTime[ORA_DEADBEAT_ITEM_ARRAYSIZE];
	char				cLastModified[ORA_DEADBEAT_ITEM_ARRAYSIZE][32];

	char				reasonCode[ORA_DEADBEAT_ITEM_ARRAYSIZE][3];
	char				*pReasonCode[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	int					transactionId[ORA_DEADBEAT_ITEM_ARRAYSIZE];

	char				itemRowId[ORA_DEADBEAT_ITEM_ARRAYSIZE][20];

	time_t				theTime;

	clsDeadbeatItem*	pItem = NULL;

	int					rowsFetched = 0;
	int					i, n;
	int					rc = 0;

	// test if time is within limits, then use rowid to fetch
	time(&theTime);

	// use item id to query
	OpenAndParse(&mpCDAGetDeadbeatItemsNotWarned, SQL_GetDeadbeatItemsNotWarned);
	Bind(":marketplace", &marketplace);

	// Bind those happy little output variables. Note that
	// we're NOT Binding the description. We'll deal with
	// that presently.
	Define(1, (int *)&itemId);
	Define(2, (int *)&seller);
	Define(3, (int *)&bidder);
	Define(4, (char *)sale_start, sizeof(sale_start[0]));
	Define(5, (char *)sale_end, sizeof(sale_end[0]));
	Define(6, (char *)title, sizeof(title[0]));
	Define(7, (float *)&price);
	Define(8, (int *)&quantity);
	Define(9, (char *)reasonCode, sizeof(reasonCode[0]));
	Define(10, (int *)&transactionId);
	Define(11, (char *)notified, sizeof(notified[0]));
	Define(12, (char *)cCreation, sizeof(cCreation[0]));
	Define(13, (char *)cLastModified, sizeof(cLastModified[0]));
	Define(14, (char *)&itemRowId, sizeof(itemRowId[0]));

	// Fetch
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetDeadbeatItemsNotWarned);
		SetStatement(NULL);
		return false;
	}

	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_DEADBEAT_ITEM_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetDeadbeatItemsNotWarned);
			SetStatement(NULL);
			return false;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			// Now everything is where it's supposed
			// to be. Let's  copy the title of the item
			pTitle[i] = new char[strlen(title[i]) + 1];
			strcpy(pTitle[i], (char *)title[i]);

			// Time Conversions
			ORACLE_DATEToTime(sale_start[i], &sale_start_time[i]);
			ORACLE_DATEToTime(sale_end[i], &sale_end_time[i]);
			ORACLE_DATEToTime(cCreation[i], &creationTime[i]);
			ORACLE_DATEToTime(cLastModified[i], &lastModifiedTime[i]);

			// Transform flags.
			notificationSent = (notified[i][0] == '1');

			pReasonCode[i]	= new char[strlen(reasonCode[i]) + 1];
			strcpy(pReasonCode[i], reasonCode[i]);

			// Create and fill in the item
			pItem = new clsDeadbeatItem;
			pItem->Set(marketplace,
					   itemId[i],
					   seller[i],
					   bidder[i],
					   sale_start_time[i],
					   sale_end_time[i],
					   pTitle[i],
					   price[i],
					   quantity[i],
					   pReasonCode[i],
					   transactionId[i],
					   notificationSent,
					   creationTime[i],
					   lastModifiedTime[i],
					   itemRowId[i],
					   0);

			pvItems->push_back(pItem);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetDeadbeatItemsNotWarned);
	SetStatement(NULL);

	return true;
}

bool clsDatabaseOracle::GetDeadbeatItemsNotWarned(DeadbeatItemVector *pvItems)
{
	return GetDeadbeatItemsNotWarned(
					gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
					pvItems);
}

//
// UpdateDeadbeatItem
//
//	NOTE:  this method has not been buddy checked or
//	tested yet, so don't use it yet!!!!!  (mila 3/12/99)
//
static const char *SQL_UpdateDeadbeatItem =
 "update ebay_deadbeat_items			\
	set marketplace = :marketplace,		\
		id = :id,						\
		seller = :seller,				\
		bidder = :bidder,				\
		sale_start = :start,			\
		sale_end = :end,				\
		title = :title,					\
		price = :price,					\
		quantity = :qty,				\
		reason_code = :reason,			\
		transaction_id = :xaction_id,	\
		notification_sent = :notified,	\
		last_modified = sysdate			\
	where id = :id";

bool clsDatabaseOracle::UpdateDeadbeatItem(clsDeadbeatItem *pItem)
{
	int			marketplace;
	int			id;
	int			seller;
	int			bidder;
	char		cSaleStart[32] = {0};
	char		cSaleEnd[32] = {0};
	float		price;
	int			qty;
	int			xactionId;
	char		cNotified[2];

	struct tm *	pTheTime;
	time_t		tTime;

	bool		updated = false;

	marketplace = pItem->GetMarketPlaceId();
	id = pItem->GetId();
	seller = pItem->GetSeller();
	bidder = pItem->GetBidder();
	price = pItem->GetPrice();
	qty = pItem->GetQuantity();
	xactionId = pItem->GetTransactionId();

	if (pItem->GetNotified())
		strcpy(cNotified, "1");
	else
		strcpy(cNotified, "0");

	tTime = pItem->GetStartTime();
	pTheTime = localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime, cSaleStart);

	tTime = pItem->GetEndTime();
	pTheTime = localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime, cSaleEnd);

	OpenAndParse(&mpCDAUpdateDeadbeatItem, SQL_UpdateDeadbeatItem);

	// Bind it, baby
	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":seller", &seller);
	Bind(":bidder", &bidder);
	Bind(":start", cSaleStart);
	Bind(":end", cSaleEnd);
	Bind(":title", pItem->GetTitle());
	Bind(":price", &price);
	Bind(":qty", &qty);
	Bind(":reason", pItem->GetReasonCode());
	Bind(":xaction_id", &xactionId);
	Bind(":notified", cNotified);

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAUpdateDeadbeatItem);
		SetStatement(NULL);
	}
	else
	{
		Commit();
		Close(&mpCDAUpdateDeadbeatItem);
		SetStatement(NULL);
		updated = true;
	}

	return updated;
}

//
// SetDeadbeatItemWarned
//
static const char *SQL_SetDeadbeatItemWarned =
 "update ebay_deadbeat_items			\
	set notification_sent = \'1\',		\
		last_modified =	sysdate			\
	where	id = :id					\
	and		seller = :seller			\
	and		bidder = :bidder";

bool clsDatabaseOracle::SetDeadbeatItemWarned(int id, int seller, int bidder)
{
	bool updated = false;

	OpenAndParse(&mpCDASetDeadbeatItemWarned, SQL_SetDeadbeatItemWarned);

	// Bind it, baby
	Bind(":id", &id);
	Bind(":seller", &seller);
	Bind(":bidder", &bidder);

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDASetDeadbeatItemWarned);
		SetStatement(NULL);
	}
	else
	{
		Commit();
		Close(&mpCDASetDeadbeatItemWarned);
		SetStatement(NULL);
		updated = true;
	}

	return updated;
}

