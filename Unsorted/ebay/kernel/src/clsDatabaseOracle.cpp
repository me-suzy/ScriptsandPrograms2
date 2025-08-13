/*	$Id: clsDatabaseOracle.cpp,v 1.21.2.10.4.4 1999/08/04 19:22:47 nsacco Exp $	*/
//
//	File:	clsDatabaseOracle.cpp
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
//				- 06/09/97 tini     - split off user related database accesses
//					to work with 2 user tables
//				- 06/13/97 tini     - removed item-xref methods; split off item
//					related database accesses to clsDatabaseOracleItems.cpp.
//				- 06/23/97 tini		- added Begin, End and Cancel for transactions;
//									  also modified Commit to check mInTransaction flag.		
//				- 07/30/97 wen		- Added functions to adjust and retrieve 
//									  bid count for a marketplace. Call
//									  AdjustMarketPlaceBidCount() in AddBid()
//				- 08/22/98 mila		- fixed bugs in feedback retrieval methods and
//									  sql statements; added indicators for getting
//									  feedback response and follow-up fields
//				- 08/25/98 mila		- changed FillFeedbackDetailTableName and
//									  FillFeedbackDetailTableNames to allocate
//									  memory for table name locally and return
//									  filled-in sql statement string; fixed memory leaks;
//									  fixed spelling of 'paginated'
//				- 09/04/98 mila		- Moved feedback methods to new file named
//									  clsDatabaseOracleFeedback.cpp.
//				- 09/19/97 chad		- Added functions to void and restore the value
//									  of a user's feedback -- this is used when the
//									  user is suspended, or restored after suspension
//									  respectively. Also added the helper static sorting
//									  function.
//				- 09/25/98 mila		- added cursors used in functions for deadbeat items
//									  and users
//				- 10/02/98 mila		- added cursor for GetDeadbeatItemsByBidderId
//				- 10/13/98 mila		- added cursor for GetAllDeadbeatItems
//				- 12/01/98 mila		- added cursors for GetDeadbeatItemsBySellerId,
//									  AddDeadbeat, GetDeadbeat, et al...
//				- 12/08/98 mila		- added cursor for GetAllDeadbeats
//				- 12/15/98 mila		- added cursors for GetDeadbeatItemCountBySellerId
//									  and GetDeadbeatItemCountByBidderId
//				- 04/13/99 mila		- added lots of new cursors for methods used in
//									  Legal Buddy project
//				- 05/20/99 jennifer - added cursors for methods used in Gallery Admin
//									- tool
//				- 06/04/99 petra	- removed unused category cursors
//

#include "eBayKernel.h"
#include "clsEnvironment.h" // for the IP address of the bidder

// Interesting defines
#define PARSE_NO_DEFER	0
//#define PARSE_DEFER		1
#define PARSE_V7_LNG	2

bool IIS_Server_is_down_flag=false;
//
// Begin, End, Cancel for multiple database transaction
//
void clsDatabaseOracle::Begin()
{
	// what if already in transaction? Need error handling?
	mInTransaction = true;
	return;
}


void clsDatabaseOracle::End()
{
	// commit changes and reset variable
	mInTransaction = false;
	Commit();

	return;
}


void clsDatabaseOracle::Cancel()
{
	// aborts transaction
	mInTransaction = false;
	int	rc;
	rc	= orol((cda_def *)mpLDA);
	Check(rc);
	return;
}

void clsDatabaseOracle::CancelPendingTransactions()
{
	if (InTransaction())
		Cancel();
}

bool clsDatabaseOracle::InTransaction()
{
	return mInTransaction;
}

//
// SetStatement
//
// Used when we're not using a persistant
// cursor
//
void clsDatabaseOracle::SetStatement(unsigned char *pCDA)
{
	if (pCDA)
	{
		mpCDACurrent	= pCDA;
	}
	else
	{
		mpCDACurrent	= NULL;
	}
	return;
}

//
// SetSQL
//
void clsDatabaseOracle::SetSQL(char *pSQL)
{
	mpCurrentSQL	= pSQL;
	return;
}

//
// Check routines
//

void clsDatabaseOracle::CheckOracleResult(char *pFromFile,
										  int  fromLine,
										  int rc)
{ 
	char	errorMsg[512];
	extern bool gIIS_Server_is_down_flag;

//	if (rc > 4 || rc < 0) { //new outage code, automatic failover
// HACK.. CGI Down feature turned off for the moment
//		gIIS_Server_is_down_flag = true;
//		return;
//	}

	if (rc > 4 || rc < 0)
	{
		if (mpCDACurrent)
		{
			oerhms((struct cda_def *)mpLDA,
					 ((struct cda_def *)mpCDACurrent)->rc,
					(unsigned char *)errorMsg,
					sizeof(errorMsg));
		}
		else
		{
			oerhms((struct cda_def *)mpLDA,
				    rc,
					(unsigned char *)errorMsg,
					sizeof(errorMsg));
		}

		EDEBUG('*', errorMsg);

		if (mpCDACurrent)
		{
			EDEBUG('*', "SQL Error from %s:%d\n",
					pFromFile, fromLine);
			EDEBUG('*', "Error Msg %s\n",
				   errorMsg); 	
			EDEBUG('*', "FT %d RPC %d PEO %d FC %d WARN %D OSE %D\n",
					((struct cda_def *)mpCDACurrent)->ft,
					((struct cda_def *)mpCDACurrent)->rpc,
					((struct cda_def *)mpCDACurrent)->peo,
					((struct cda_def *)mpCDACurrent)->fc,
					((struct cda_def *)mpCDACurrent)->wrn,
					((struct cda_def *)mpCDACurrent)->ose);
		}
	}


	// If the rc's > 4, then it's an error (as opposed to a warning)
	// and we need to throw an exception
	if (rc > 4 || rc < 0)
	{
		if (mpCDACurrent)
		{
			throw eBayOracleException(((struct cda_def *)mpCDACurrent)->rc,
									  ((struct cda_def *)mpCDACurrent)->ft,
									  ((struct cda_def *)mpCDACurrent)->rpc,
									  ((struct cda_def *)mpCDACurrent)->peo,
									  ((struct cda_def *)mpCDACurrent)->fc,
									  ((struct cda_def *)mpCDACurrent)->wrn,
									  ((struct cda_def *)mpCDACurrent)->ose,
									  (char *)errorMsg,
									  mpCurrentSQL,
									  pFromFile,
									  fromLine);
		}
		else
		{
			throw eBayOracleException(0, 
									  0,
									  0,
									  0,
									  0,
									  0,
									  0,
									  (char *)errorMsg,
									  NULL,
									  pFromFile,
									  fromLine);
		}
	}


	return;
}

bool clsDatabaseOracle::CheckForNoRowsFound()
{
	assert(mpCDACurrent);
	if (((struct cda_def *)mpCDACurrent)->rc == 1403)
	{
		return true;
	}
	return false;
}

bool clsDatabaseOracle::CheckForNoRowsUpdated()
{
	assert(mpCDACurrent);
	if (((struct cda_def *)mpCDACurrent)->rpc == 0)
	{
		return true;
	}
	return false;
}
// 
// Time conversion
//
// Converts a binary time to an Oracle
// string formatted date of the form
// 'YYYY-MM-DD HH:MM:SS'
//
void TM_STRUCTToORACLE_DATE(struct tm *pLocalTime,
					        char *pDate)
{
	sprintf(pDate, 
			"%02d-%02d-%02d %02d:%02d:%02d",
			pLocalTime->tm_year + 1900,
			pLocalTime->tm_mon + 1,
			pLocalTime->tm_mday,
			pLocalTime->tm_hour,
			pLocalTime->tm_min,
			pLocalTime->tm_sec);
	
	return;
}


void TimeToORACLE_DATE(time_t theTime,
					   char *pDate)
{
	struct tm	*pLocalTime;

	pLocalTime	= localtime(&theTime);

	TM_STRUCTToORACLE_DATE(pLocalTime,
						   pDate);

	return;
}

//
// ORALCE_DATEToTime
//
//	Converts Oracle string dates of the form
//	YYYY-MM-DD HH:MM:SS to a time_t
//
//	** NOTE **
//	All the old honorDST stuff is bogus. We set tm_isdst to -1
//	to let the C RTL figure out if the time is DST or not
//	** NOTE **
//
void ORACLE_DATEToTime(char *pDate,
					   time_t *pTheTime,
						bool /* honorDST */)
{
	struct tm	localTime;

	memset(&localTime, 0x00, sizeof(localTime));

	// We can use sscanf since Oracle is assumed
	// to be well behaved
	sscanf(pDate, "%d-%d-%d %d:%d:%d",
		   &localTime.tm_year,
		   &localTime.tm_mon,
		   &localTime.tm_mday,
		   &localTime.tm_hour,
		   &localTime.tm_min,
		   &localTime.tm_sec);

	localTime.tm_mon--;
	localTime.tm_year -= 1900;
	localTime.tm_isdst	= -1;

	
	*pTheTime		= mktime(&localTime);

	return;
}

//
//	Converts Oracle string dates of the form
//	YYYY-MM-DD HH:MM:SS to a tm struct
//
void ORACLE_DATEToTM_STRUCT(char *pDate,
					        struct tm *pTheTime)
{
	// We can use sscanf since Oracle is assumed
	// to be well behaved
	sscanf(pDate, "%d-%d-%d %d:%d:%d",
		   &pTheTime->tm_year,
		   &pTheTime->tm_mon,
		   &pTheTime->tm_mday,
		   &pTheTime->tm_hour,
		   &pTheTime->tm_min,
		   &pTheTime->tm_sec);

	pTheTime->tm_mon--;
	
	return;
}

//
// Integer Bind routine
//
void clsDatabaseOracle::Bind(const char *pName,
							 int *pVar,
							 short *pInd)
{
	int	rc;

	rc	= obndrv((struct cda_def *)mpCDACurrent,
						(unsigned char *)pName,
						-1,
						(ub1 *)pVar,
						sizeof(int),
						SQLT_INT,
						-1,
						pInd,
						NULL,
						(sword)-1,
						(sword)-1);

	assert(mpCDACurrent);
	if (((struct cda_def *)mpCDACurrent)->rc == 1036)
	{
		EDEBUG('*', "Ack! 1036! pName <%s>\n", pName);
	}

	Check(rc);

	return;
}

void clsDatabaseOracle::Bind(const char *pName,
							 long *pVar,
							 short *pInd)
{
	Bind(pName, (int *) pVar, pInd);
}

void clsDatabaseOracle::Bind(const char *pName,
							 unsigned long *pVar,
							 short *pInd)
{
	Bind(pName, (unsigned int *) pVar, pInd);
}

//
// Unsigned Integer Bind routine
//
void clsDatabaseOracle::Bind(const char *pName,
							 unsigned int *pVar,
							 short *pInd)
{
	int	rc;

	rc	= obndrv((struct cda_def *)mpCDACurrent,
						(unsigned char *)pName,
						-1,
						(ub1 *)pVar,
						sizeof(int),
						SQLT_UIN,
						-1,
						pInd,
						NULL,
						(sword)-1,
						(sword)-1);

	assert(mpCDACurrent);
	if (((struct cda_def *)mpCDACurrent)->rc == 1036)
	{
		EDEBUG('*', "Ack! 1036! pName <%s>\n", pName);
	}

	Check(rc);

	return;
}

//
// Character Bind routine
//
void clsDatabaseOracle::Bind(const char *pName,
							 const char *pVar,
							 short *pInd)
{
	int	rc;


	rc	= obndrv((struct cda_def *)mpCDACurrent,
						(unsigned char *)pName,
						-1,
						(ub1 *)pVar,
						strlen(pVar),
						SQLT_CHR,
						-1,
						pInd,
						NULL,
						(sword)-1,
						(sword)-1);

	assert(mpCDACurrent);
	if (((struct cda_def *)mpCDACurrent)->rc == 1036)
	{
		EDEBUG('*', "Ack! 1036! pName <%s>\n", pName);
	}


	Check(rc);

	return;
}

//
// Character Bind routine with length
// (used for array insert)
//
void clsDatabaseOracle::Bind(const char *pName,
							 const char *pVar,
							 int length,
							 short *pInd)
{
	int	rc;

	rc	= obindps((struct cda_def *)mpCDACurrent,
				  1,
				  (unsigned char *)pName,
				  -1,
				  (ub1 *)pVar,
				  length,
				  SQLT_STR,
				  (sword)0,
				  pInd,
				  0,
				  0,
				  length,
				  0,
				  0,
				  0,
				  0,
				  0,
				  0,
				  0,
				  0);

	assert(mpCDACurrent);
	if (((struct cda_def *)mpCDACurrent)->rc == 1036)
	{
		EDEBUG('*', "Ack! 1036! pName <%s>\n", pName);
	}


	Check(rc);

	return;
}


//
// Float Bind routine
//
void clsDatabaseOracle::Bind(const char *pName,
							 float *pVar,
							 short *pInd)
{
	int	rc;

	rc	= obndrv((struct cda_def *)mpCDACurrent,
						(unsigned char *)pName,
						-1,
						(ub1 *)pVar,
						sizeof(float),
						SQLT_FLT,
						-1,
						pInd,
						NULL,
						(sword)-1,
						(sword)-1);

	assert(mpCDACurrent);
	if (((struct cda_def *)mpCDACurrent)->rc == 1036)
	{
		EDEBUG('*', "Ack! 1036! pName <%s>\n", pName);
	}


	Check(rc);

	return;
}

//
// Long raw bind routine. We can't use polymorphisim here
// because we don't want C++ to mistake just any unsigned
// char for a long raw
//
void clsDatabaseOracle::BindLongRaw(const char *pName,
									unsigned char *pVar,
									int varlength,
									short *pInd)
{
	int	rc;

	rc	= obndrv((struct cda_def *)mpCDACurrent,
						(unsigned char *)pName,
						-1,
						(ub1 *)pVar,
						varlength,
						SQLT_LBI,
						-1,
						pInd,
						NULL,
						(sword)-1,
						(sword)-1);

	assert(mpCDACurrent);
	if (((struct cda_def *)mpCDACurrent)->rc == 1036)
	{
		EDEBUG('*', "Ack! 1036! pName <%s>\n", pName);
	}


	Check(rc);

	return;
}

//
// Integer Define
//
void clsDatabaseOracle::Define(int position,
							   int *pTarget,
							   short *pInd)
{
	int	rc;

	rc = odefin((struct cda_def *)mpCDACurrent, 
				position,
                (unsigned char *)pTarget,
                sizeof(int),
                SQLT_INT,
                -1,
                pInd,
                (text *) 0,
                -1,
                -1,
                NULL,
                NULL);
	Check(rc);
	return;
}

//
// Integer Array Define
//

void clsDatabaseOracle::ArrayDefine(int position,
							   int *pTarget,
							   short skip,
							   short *pInd,
							   short indicatorSkip)
{
	int	rc;

	rc = odefinps((struct cda_def *)mpCDACurrent,	// cursor
				1,									// opcode; 1 = array of structs
				position,							// pos; index for the select-list column
                (unsigned char *)pTarget,			// bufctx; pointer to the first instance of the destination value in the array
                sizeof(int),						// bufl; the length of the first instance of the destination value in the array
                SQLT_INT,							// ftype; the destination data type to convert to
                0,									// scale; not used. NOTE: with this call, unused params are passed as 0 not -1.
                pInd,								// indp; pointer to the first instance of the indicator value in the array
                (text *) 0,							// fmt; not used. NOTE: with this call, unused params are passed as 0 not -1.
                0,									// fmtl; not used. NOTE: with this call, unused params are passed as 0 not -1.
                0,									// fmtt; not used. NOTE: with this call, unused params are passed as 0 not -1.
                NULL,								// rlenp; not used. NOTE: with this call, unused params are passed as 0 not -1.
                NULL,								// rcodep; not used. NOTE: with this call, unused params are passed as 0 not -1.
				skip,								// buf_skip; bytes to skip to get to the next element in the value array.
				indicatorSkip,						// ind_skip; bytes to skip to get to the next element in the indicator array.
				0,									// len_skip; not used. NOTE: with this call, unused params are passed as 0 not -1.
				0									// rc_skip; not used. NOTE: with this call, unused params are passed as 0 not -1.
				);
	Check(rc);
	return;
}

//
// Character Array Define
//

void clsDatabaseOracle::ArrayDefine(int position,
							   char *pTarget,
							   int size,
							   short skip,
							   short *pInd,
							   short indicatorSkip)
{
	int	rc;

	rc = odefinps((struct cda_def *)mpCDACurrent,	// cursor
				1,									// opcode; 1 = array of structs
				position,							// pos; index for the select-list column
                (unsigned char *)pTarget,			// bufctx; pointer to the first instance of the destination value in the array
                size,								// bufl; the length of the first instance of the destination value in the array
                SQLT_STR,							// ftype; the destination data type to convert to
                0,									// scale; not used. NOTE: with this call, unused params are passed as 0 not -1.
                pInd,								// indp; pointer to the first instance of the indicator value in the array
                (text *) 0,							// fmt; not used. NOTE: with this call, unused params are passed as 0 not -1.
                0,									// fmtl; not used. NOTE: with this call, unused params are passed as 0 not -1.
                0,									// fmtt; not used. NOTE: with this call, unused params are passed as 0 not -1.
                NULL,								// rlenp; not used. NOTE: with this call, unused params are passed as 0 not -1.
                NULL,								// rcodep; not used. NOTE: with this call, unused params are passed as 0 not -1.
				skip,								// buf_skip; bytes to skip to get to the next element in the value array.
				indicatorSkip,						// ind_skip; bytes to skip to get to the next element in the indicator array.
				0,									// len_skip; not used. NOTE: with this call, unused params are passed as 0 not -1.
				0									// rc_skip; not used. NOTE: with this call, unused params are passed as 0 not -1.
				);
	Check(rc);
	return;
}

//
// Unsigned Integer Define
//
void clsDatabaseOracle::Define(int position,
							   unsigned int *pTarget,
							   short *pInd)
{
	int	rc;

	rc = odefin((struct cda_def *)mpCDACurrent, 
				position,
                (unsigned char *)pTarget,
                sizeof(int),
                SQLT_INT,
                -1,
                pInd,
                (text *) 0,
                -1,
                -1,
                NULL,
                NULL);
	Check(rc);
	return;
}

void clsDatabaseOracle::Define(int position,
							   long *pTarget,
							   short *pInd)
{
	Define(position, (int *) pTarget, pInd);
}

void clsDatabaseOracle::Define(int position,
							   unsigned long *pTarget,
							   short *pInd)
{
	Define(position, (unsigned int *) pTarget, pInd);
}

//
// Character Define
//
void clsDatabaseOracle::Define(int position,
							   char *pTarget,
							   int targetLength,
							   short *pInd)
{
	int	rc;

	rc = odefin((struct cda_def *)mpCDACurrent, 
				position,
                (unsigned char *)pTarget,
                targetLength,
                SQLT_STR,
                -1,
                pInd,
                (text *) 0,
                -1,
                -1,
                NULL,
                NULL);
	Check(rc);
	return;
}

//
// Float Define
//
void clsDatabaseOracle::Define(int position,
							   float *pTarget,
							   short *pInd)
{
	int	rc;

	rc = odefin((struct cda_def *)mpCDACurrent, 
				position,
                (unsigned char *)pTarget,
                sizeof(float),
                SQLT_FLT,
                -1,
                pInd,
                (text *) 0,
                -1,
                -1,
                NULL,
                NULL);
	Check(rc);
	return;
}


//
// Long raw define
//
void clsDatabaseOracle::DefineLongRaw(int position,
							   unsigned char *pTarget,
							   int targetLength,
							   short *pInd)
{
	int	rc;

	rc = odefin((struct cda_def *)mpCDACurrent, 
				position,
                (unsigned char *)pTarget,
                targetLength,
                SQLT_LBI,
                -1,
                pInd,
                (text *) 0,
                -1,
                -1,
                NULL,
                NULL);
	Check(rc);
	return;
}

//
// Double Define
//
void clsDatabaseOracle::Define(int position,
							   double *pTarget,
							   short *pInd)
{
	int	rc;

	rc = odefin((struct cda_def *)mpCDACurrent, 
				position,
                (unsigned char *)pTarget,
                sizeof(double),
                SQLT_FLT,
                -1,
                pInd,
                (text *) 0,
                -1,
                -1,
                NULL,
                NULL);
	Check(rc);
	return;
}

//
// OpenAndParse
//
void clsDatabaseOracle::OpenAndParse(unsigned char **ppCDA,
									 const char *pSQL)
{
	int	rc;
	
	// First, let's see if someone's left the OneShot curso
	// open
	if (ppCDA == &mpCDAOneShot &&
		mpCDACurrent != NULL)
	{
		EDEBUG('*', "OneShot cursor left open. SQL = <%s>\n",
			   pSQL);

		// Just close it, and damn the torpedoes
		oclose((struct cda_def *)*ppCDA);
		mpCDACurrent	= NULL;
		pSQL			= NULL;
	}


	//  Indicate the current statement in case something
	//	goes kaflooey
	SetSQL((char *)pSQL);

	// Open the cursor. If it's already open, then 
	// we're out of here
	if (*ppCDA == NULL)
	{
		*ppCDA	= (unsigned char *)new cda_def;

		rc = oopen((struct cda_def *)*ppCDA,
				   (struct cda_def *)mpLDA,
				   (text *) 0, -1, -1, (text *) 0, -1);

		Check(rc);
	}
	else
	{
		SetStatement(*ppCDA);
		return;
	}

	// Make it current
	SetStatement(*ppCDA);

	// Now, Parse it
	rc = oparse((struct cda_def *)*ppCDA,
				(text *)pSQL, (sb4) -1,
                (sword) PARSE_NO_DEFER, (ub4)PARSE_V7_LNG);
	Check(rc);

	return;
}

//
// OpenCursor
//	Allocates a CDA if needed, and opens the cursor
//
void clsDatabaseOracle::Open(unsigned char **ppCDA)
{
	int	rc;
	// Let's see if someone left the OneShot cursor
	// open
	if (*ppCDA == NULL)
	{
		*ppCDA	= (unsigned char *)new cda_def;

		rc = oopen((struct cda_def *)*ppCDA,
				   (struct cda_def *)mpLDA,
				   (text *) 0, -1, -1, (text *) 0, -1);

		Check(rc);
	}
}

//
// Parse
//
void clsDatabaseOracle::Parse(char *pSQL)
{
	int	rc;

	// Set the current SQL in case something goes 
	// kaflooie
	SetSQL(pSQL);

	rc = oparse((struct cda_def *)mpCDACurrent,
				(text *)pSQL, (sb4) -1,
                (sword) PARSE_NO_DEFER, (ub4)PARSE_V7_LNG);
	Check(rc);
}

void clsDatabaseOracle::Parse(const char *pSQL)
{
	Parse((char *)pSQL);
}

//
// Execute
//
void clsDatabaseOracle::Execute()
{
	int	rc;
	rc	= oexec((struct cda_def *)mpCDACurrent);
	Check(rc);
}

//
// Close
//	If this is a persistant cursor, close just
//	returns, unless the force parameter is set
//
void clsDatabaseOracle::Close(unsigned char **ppCDA,
							  bool force)
{
	int	rc;

	if (*ppCDA == mpCDAOneShot ||
		force)
	{
		rc = oclose((struct cda_def *)*ppCDA);
		Check(rc);
		delete	*ppCDA;
		*ppCDA	= NULL;
	}
	else
		ocan((struct cda_def *)*ppCDA);

	SetSQL(NULL);

	return;
}

//
// Commit
//
void clsDatabaseOracle::Commit()
{
	int	rc;

	if (!mInTransaction)
	{
		rc	= ocom((cda_def *)mpLDA);
		Check(rc);
	}
	return;
}

//
// Fetch
//
void clsDatabaseOracle::ExecuteAndFetch(int count)
{
	int	rc;

	rc = oexfet((struct cda_def *)mpCDACurrent, 
					 count, 1, 1);

	if (!CheckForNoRowsFound())
	{
		Check(rc);
	}

	return;
}

//
// Fetch
//
void clsDatabaseOracle::Fetch()
{
	int	rc;

	rc = ofetch((struct cda_def *)mpCDACurrent);

	if (!CheckForNoRowsFound())
	{
		Check(rc);
	}

	return;
}


//#define _PYTHON_CGI
//#define _PYTHON_LOCAL
#define _ALGEBRA_CGI
//#define _ALGEBRA_LOCAL

#if defined(_PYTHON_CGI)
#define CURRENT_DATABASE "Python CGI"
#elif defined(_PYTHON_LOCAL)
#define CURRENT_DATABASE "Python Local"
#elif defined(_ALGEBRA_CGI)
#define CURRENT_DATABASE "Algebra CGI"
#elif defined(_ALGEBRA_LOCAL)
#define CURRENT_DATABASE "Algebra Local"
#else
#error No current database defined!
#endif

char *CurrentDatabase = CURRENT_DATABASE;
bool gIIS_Server_is_down_flag=false; //new Outage-code

bool IIS_Server_is_down(void) { //new outage code
		extern bool gIIS_Server_is_down_flag;

		if (gIIS_Server_is_down_flag) return TRUE;
				else return FALSE;
}

clsDatabaseOracle::clsDatabaseOracle(char *pHost) :
		clsDatabase(pHost)
{
	int		rc;
	int		retries;
	int		retrylimit;
	int		i;

	mpCurrentSQL = NULL;

	//
	// If we're here, we obviously don't
	// have a LDA or HDA
	//
	mpLDA       = (unsigned char *)new Lda_Def;
	memset(mpLDA, '\0', sizeof (Lda_Def));

	mpHDA			= new unsigned char[512];
	memset(mpHDA, '\0', 512);

	mpDescriptionBuffer					= NULL;

	mpSellerListBuffer					= NULL;
	mSellerListBufferSize				= 0;

	mpBidderListBuffer					= NULL;
	mBidderListBufferSize				= 0;
 
	mpFeedbackListBuffer				= NULL;
	mFeedbackListBufferSize				= 0;

	mpUserPageBuffer					= NULL;
	mUserPageBufferSize					= 0;

	// Set some other things
	mpCDACurrent = NULL;

	mpCDAOneShot = NULL;
	mpCDAAdjustMarketPlaceItemCount = NULL;
	mpCDAGetSingleItem = NULL;
	mpCDAGetSingleItemWithDescription = NULL;
	mpCDAGetSingleItemRowId = NULL;
	mpCDAGetSingleItemWithDescriptionRowId = NULL;
	mpCDAGetSingleItemEnded = NULL;
	mpCDAGetSingleItemWithDescriptionEnded = NULL;
	mpCDAGetSingleItemRowIdEnded = NULL;
	mpCDAGetSingleItemWithDescriptionRowIdEnded = NULL;
	mpCDAGetItemDesc = NULL;
	mpCDAGetItemDescLen = NULL;
	mpCDAGetItemDescEnded = NULL;
	mpCDAGetItemDescLenEnded = NULL;
	mpCDADeleteItem = NULL;
	mpCDADeleteItemEnded = NULL;
	mpCDAUpdateItemDesc = NULL;
	mpCDASetNewDescription = NULL;
	mpCDAUpdateItemPassword = NULL;
	mpCDADeleteItemDesc = NULL;
	mpCDADeleteItemDescEnded = NULL;
	mpCDAGetFeedback = NULL;
	mpCDAGetFeedbackDetailLeftByUser = NULL;

	mpCDAGetFeedbackDetailLeftByUserSplit = NULL;
	mpCDAGetUserById = NULL;
	mpCDAGetUserAndFeedbackById = NULL;
	mpCDAGetUserByUserId = NULL;
	mpCDAGetUserAndFeedbackByUserId = NULL;
	mpCDAGetUserAndFeedbackByEmail = NULL;
	mpCDAGetUserIdForRenamedUser = NULL;
	mpCDAGetUserAndInfoById = NULL;
	mpCDAGetUserInfo = NULL;
	mpCDAAddManyUsers = NULL;
	mpCDAAddManyUsersInfo = NULL;
	mpCDAGetHighBidForUser = NULL;
	mpCDAGetHighBidForUserEnded = NULL;
	mpCDAGetHighBidsForItemEnded = NULL;
	mpCDAGetHighBidsForItem = NULL;
	mpCDAGetDutchHighBidders = NULL;
	mpCDAAddBid = NULL;
	mpCDAAddBlockedBid = NULL;
	mpCDASetNewHighBidder = NULL;
	mpCDASetNewHighBidderAndBidCount = NULL;
	mpCDASetNewBidCount = NULL;
	mpCDASetDutchHighBidder = NULL;
	mpCDADeleteDutchHighBidder = NULL;
	mpCDASetNewCategory = NULL;
	mpCDAAdjustAccountBalance = NULL;
	mpCDAGetBids = NULL;
	mpCDAGetBidsEnded = NULL;
	mpCDAGetItemsListedByUser = NULL;
	mpCDAGetItemsListedByUserGetMoreStuff = NULL;
	mpCDAGetItemsListedByUserEnded = NULL;
	mpCDAGetItemsListedByUserGetMoreStuffEnded = NULL;
	mpCDAGetItemsListedByUserWithCompleted = NULL;
	mpCDAGetItemsListedByUserWithCompleted2 = NULL;
	mpCDAGetItemsHighBidByUser = NULL;
	mpCDAGetItemsHighBidByUserWithCompleted = NULL;
	mpCDAGetItemsHighBidByUserWithCompletedEnded = NULL;
	mpCDAGetItemsBidByUser = NULL;
	mpCDAGetItemsBidByUserEnded = NULL;
	mpCDAGetItemsBidByUserGetMoreStuff = NULL;
	mpCDAGetItemsBidByUserGetMoreStuffEnded = NULL;
	mpCDAGetItemsBidByUserWithCompleted = NULL;
	mpCDAGetItemsBidByUserWithCompletedByDate = NULL;
	mpCDAGetItemsBidByUserWithCompletedByDate2 = NULL;
	mpCDAGetBillTime = NULL;
	mpCDAGetBillTimeByRowId = NULL;
	mpCDAGetBillTimeEnded = NULL;
	mpCDAAddItemBilled = NULL;
	mpCDAUpdateItemBilled = NULL;
	mpCDAAddItemBilledByRowId = NULL;
	mpCDAModifyItemBilled = NULL;
	mpCDAAddItemNoticedByRowId = NULL;
	mpCDAAddItemNoticed = NULL;
	mpCDAModifyItemNoticed = NULL;
	mpCDAGetItemsModifiedAfter = NULL;
	mpCDAUpdateItemEnded = NULL;
	mpCDAUpdateItemBlocked = NULL;
	mpCDAUpdateItem = NULL;
	mpCDAGetCategoryById = NULL;
//	mpCDAGetCategoryByName = NULL;
	mpCDAGetCategoryFirst = NULL;
	mpCDAGetCategoryAll = NULL;
	mpCDAGetCategoryTopLevel = NULL;
	mpCDAGetCategoryChildren = NULL;
	mpCDAGetCategoryDescendants = NULL;
//	mpCDAGetCategorySiblings = NULL;
	mpCDAGetCategoryLeaves = NULL;
	mpCDAGetCategoryChildrenSorted = NULL;
	mpCDAGetCategoryVector = NULL;
	mpCDAGetCategorySiblingPrev = NULL;
	mpCDAGetCategorySiblingNext = NULL;
	mpCDAAddRawAccountDetail = NULL;
	mpCDAAddRawAccountDetail_0 = NULL;
	mpCDAAddRawAccountDetail_1 = NULL;
	mpCDAAddRawAccountDetail_2 = NULL;
	mpCDAAddRawAccountDetail_3 = NULL;
	mpCDAAddRawAccountDetail_4 = NULL;
	mpCDAAddRawAccountDetail_5 = NULL;
	mpCDAAddRawAccountDetail_6 = NULL;
	mpCDAAddRawAccountDetail_7 = NULL;
	mpCDAAddRawAccountDetail_8 = NULL;
	mpCDAAddRawAccountDetail_9 = NULL;
	mpCDAGetPaymentsSince = NULL;
	mpCDAGetPaymentsSince_0 = NULL;
	mpCDAGetPaymentsSince_1 = NULL;
	mpCDAGetPaymentsSince_2 = NULL;
	mpCDAGetPaymentsSince_3 = NULL;
	mpCDAGetPaymentsSince_4 = NULL;
	mpCDAGetPaymentsSince_5 = NULL;
	mpCDAGetPaymentsSince_6 = NULL;
	mpCDAGetPaymentsSince_7 = NULL;
	mpCDAGetPaymentsSince_8 = NULL;
	mpCDAGetPaymentsSince_9 = NULL;
	mpCDAAddAccountAWItemXref = NULL;
	mpCDAAddItemDescArc = NULL;
	mpCDAAddItemDescEnded = NULL;
	mpCDAUpdateItemDescArc = NULL;
	mpCDAUpdateItemDesc = NULL;
	mpCDARecentFeedbackFromUser = NULL;
	mpCDARecentFeedbackFromUserSplit = NULL;
	mpCDARecentNegativeFeedbackFromUser = NULL;

	mpCDARecentNegativeFeedbackFromUserSplit = NULL;
	mpCDARecentFeedbackFromHost = NULL;
	mpCDARecentFeedbackFromHostSplit = NULL;
	mpCDARecentNegativeFeedbackFromHost = NULL;
	mpCDARecentNegativeFeedbackFromHostSplit = NULL;
	mpCDAGetUserByEmail = NULL;
	mpCDAGetIdForPriorAlias = NULL;
	mpCDAGetIdForPriorEmail = NULL;
	mpCDAAddBBEntry = NULL;
	mpCDAGetBulletinBoardStatistics = NULL;
	mpCDAGetBulletinBoardEntries = NULL;
	mpCDAGetBulletinBoardTimes = NULL;
	mpCDAUpdateBBLastPostTime = NULL;
	mpCDADeleteBulletinBoardEntries = NULL;
	mpCDAGetSellerItemListFromItems = NULL;
	mpCDAGetSellerItemListFromSellerList = NULL;
	mpCDAGetSellerItemListFromItemsEnded = NULL;
	mpCDAGetSellerListSize = NULL;
	mpCDAAddSellerList = NULL;
	mpCDAUpdateSellerList = NULL;
	mpCDAInvalidateSellerList = NULL;
	mpCDAInvalidateExtendedFeedback = NULL;
	mpCDAUpdateExtendedFeedback = NULL;
	mpCDAGetBidderItemListFromBids = NULL;
	mpCDAGetBidderItemListFromBidsEnded = NULL;
	mpCDAGetBidderItemListFromItems = NULL;
	mpCDAGetBidderItemListFromBidderList = NULL;
	mpCDAGetBidderListSize = NULL;
	mpCDAGetBidderList = NULL;
	mpCDAUpdateBidderList = NULL;
	mpCDAInvalidateBidderList = NULL;
	mpCDAAddBidderList = NULL;
	mpCDAAddLinkButton = NULL;
	mpCDASetFeedbackScore = NULL;
	mpCDAAddReqEmailCount = NULL;
	mpCDAInsertNumberAttribute = NULL;
	mpCDAAddItemDesc = NULL;
	mpCDAAddItem = NULL;
	mpCDAGetNextItemId = NULL;
	mpCDAUpdateItemStatus = NULL;
	mpCDAUpdateItemStatusEnded = NULL;
	mpCDAAddAccountDetail = NULL;
	mpCDAAddAccountDetail_0 = NULL;
	mpCDAAddAccountDetail_1 = NULL;
	mpCDAAddAccountDetail_2 = NULL;
	mpCDAAddAccountDetail_3 = NULL;
	mpCDAAddAccountDetail_4 = NULL;
	mpCDAAddAccountDetail_5 = NULL;
	mpCDAAddAccountDetail_6 = NULL;
	mpCDAAddAccountDetail_7 = NULL;
	mpCDAAddAccountDetail_8 = NULL;
	mpCDAAddAccountDetail_9 = NULL;
	mpCDAAddAccountItemXref = NULL;
	mpCDAGetNextTransactionId = NULL;
	mpCDAGetAccount = NULL;
	mpCDAGetAccountDetail = NULL;
	mpCDAGetAccountDetail_0 = NULL;
	mpCDAGetAccountDetail_1 = NULL;
	mpCDAGetAccountDetail_2 = NULL;
	mpCDAGetAccountDetail_3 = NULL;
	mpCDAGetAccountDetail_4 = NULL;
	mpCDAGetAccountDetail_5 = NULL;
	mpCDAGetAccountDetail_6 = NULL;
	mpCDAGetAccountDetail_7 = NULL;
	mpCDAGetAccountDetail_8 = NULL;
	mpCDAGetAccountDetail_9 = NULL;
	mpCDASetReadOnly = NULL;
	mpCDAGetAnnouncement = NULL;
	mpCDAGetAnnouncementLen = NULL;
	mpCDAGetManyItemsForCreditBatch = NULL;
	mpCDAGetManyEndedItemsForCreditBatch = NULL;
	mpCDAGetManyArcItemsForCreditBatch = NULL;
	mpCDAGetManyUsersForCreditBatch = NULL;
	mpCDAGetManyUsers = NULL;
	mpCDAAddReciprocalLink = NULL;
	mpCDAGetInterimBalances = NULL;
	mpCDAIncrementPartnerCount = NULL;
	mpCDAGetLastCountAndTime = NULL;
	mpCDAUpdateReceiveInfo = NULL;
	mpCDAGetAliasHistory = NULL;
	mpCDAGetAdultCategoryIds = NULL;
	mpCDAGetUserPage = NULL;
	mpCDAGetUserPageText = NULL;
	mpCDAAddViewToUserPage = NULL;

	mpCDAGetEndOfMonthBalanceById = NULL;
	mpCDAAddEndOfMonthBalance = NULL;

	mpCDAGetAllCountries = NULL;
	mpCDAGetAllCurrencies = NULL;


	mpCDAAddTransactionRecord = NULL;
	mpCDAGetTransactionRecord = NULL;
	mpCDASetTransactionUsed = NULL;

	mpCDADeleteTransactionRecord = NULL;

	mpCDAAddGiftOccasion = NULL;
	mpCDADeleteGiftOccasion = NULL;
	mpCDAGetNextGiftOccasionId = NULL;
	mpCDAGetSingleGiftOccasion = NULL;

	mpCDAGetFeedbackListFromFeedbackList = NULL;
	mpCDAGetFeedbackListSize = NULL;
	mpCDAAddFeedbackList = NULL;
	mpCDAUpdateFeedbackList = NULL;
	mpCDAInvalidateFeedbackList = NULL;

	mpCDAGetManyItemsForAuctionEnd = NULL;
	mpCDAGetManyEndedItemsForAuctionEnd = NULL;

	// Gallery
	mpCDAGetGalleryChangedItem = NULL;
	mpCDAAppendGalleryChangedItem = NULL;
	mpCDASetGalleryChangedItemState = NULL;
	mpCDADeleteGalleryChangedItem = NULL;
	mpCDAGetCurrentGallerySequence = NULL;
	mpCDAGetNextGallerySequence = NULL;
	mpCDAGetCurrentGalleryReadSequence = NULL;
	mpCDAGetNextGalleryReadSequence = NULL;
	mpCDASetItemGalleryInfo = NULL;
	mpCDAGetItemGalleryInfo = NULL;
	mpCDAGetGallerySequenceRange = NULL;

	// Trust and safety
	mpCDAGetAuctionIds = NULL;
	mpCDAGetAuctionsWon = NULL;
	mpCDAGetAuctionsBidOn = NULL;
	mpCDAGetAuctionsWithRetractions = NULL;
	mpCDAGetAuctionIdsEnded = NULL;
	mpCDAGetAuctionsWonEnded = NULL;
	mpCDAGetAuctionsBidOnEnded = NULL;
	mpCDAGetAuctionsWithRetractionsEnded = NULL;
	mpCDAGetSellersOfAuctionsEnded = NULL;
	mpCDAGetSellersOfAuctions = NULL;
	mpCDAGetOurAuctionsBidOnByUs = NULL;
	mpCDAGetOurAuctionsBidOnByUsEnded = NULL;
	mpCDAGetShillInformationForOurAuctionsEnded = NULL;
	mpCDAGetBidsFromTheseUsersEnded = NULL;
	mpCDAGetShillInformationForOurAuctions = NULL;
	mpCDAGetBidsFromTheseUsers = NULL;
	mpCDAClearAllDeadbeatItems = NULL;
	mpCDADeleteDeadbeatItem = NULL;
	mpCDAGetSingleDeadbeatItem = NULL;
	mpCDAGetSingleDeadbeatItemRowId = NULL;
	mpCDAGetDeadbeatItem = NULL;
	mpCDAGetDeadbeatItemWithRowId = NULL;
	mpCDAAddDeadbeatItem = NULL;
	mpCDAGetDeadbeatScore = NULL;
	mpCDAGetDeadbeatItemsByBidderId = NULL;
	mpCDAGetDeadbeatItemsBySellerId = NULL;
	mpCDAGetAllDeadbeatItems = NULL;
	mpCDAAddDeadbeat = NULL;
	mpCDAGetDeadbeat = NULL;
	mpCDAGetAllDeadbeats = NULL;
	mpCDASetDeadbeatScore = NULL;
	mpCDASetCreditRequestCount = NULL;
	mpCDAGetCreditRequestCount = NULL;
	mpCDASetWarningCount = NULL;
	mpCDAGetWarningCount = NULL;
	mpCDAGetDeadbeatItemCountByBidderId = NULL;
	mpCDAGetDeadbeatItemCountBySellerId = NULL;
	mpCDAInvalidateDeadbeatScore = NULL;
	mpCDAValidateDeadbeatScore = NULL;
	mpCDAInvalidateCreditRequestCount = NULL;
	mpCDAValidateCreditRequestCount = NULL;
	mpCDAInvalidateWarningCount = NULL;
	mpCDAValidateWarningCount = NULL;
	mpCDAGetDeadbeatItemsWarnedCountByBidderId = NULL;
	mpCDAGetDeadbeatItemsNotWarned = NULL;
	mpCDAUpdateDeadbeatItem = NULL;
	mpCDASetDeadbeatItemWarned = NULL;

	//item and item_info merge special: double writes
	mpCDAAddItemBilledToItems = NULL;
	mpCDAAddItemNoticedToItems = NULL;
	mpCDAGetNoticeTime = NULL;
	mpCDAGetNoticeTimeByRowId = NULL;
	mpCDAGetNoticeTimeEnded = NULL;	
	mpCDACopyItemInfo = NULL;
	mpCDADeleteItemInfo = NULL;
	mpCDAGetThisBids = NULL;
	mpCDACopyBids = NULL;
	mpCDADeleteBids = NULL;
	mpCDADeleteBid = NULL;
	mpCDACopyItems = NULL;
	mpDeleteItemInfo = NULL;	
	mpCDAInterimBalance = NULL;
	mpCDAGetItemDescArc = NULL;
	mpCDAGetThisBidsFromEnded = NULL;
	mpCDADeleteBidsFromEnded = NULL;
	mpCDAUpdateDutchGMS = NULL;
	mpCDAUpdateDutchGMSByRowId = NULL;

	//Gurinder - for ReInstate Item
	//copy
	mpCDACopyArcItems = NULL;				
	mpCDACopyArcBids = NULL;		
	
	//delete
	mpCDADeleteArcItems = NULL;
	mpCDADeleteArcItemDesc = NULL;		
	mpCDADeleteArcBids = NULL;
	//rec count
	mpCDASQLGetThisArcBids = NULL;
	mpCDASQLGetThisItem = NULL;
	mpCDASQLGetThisItemBids = NULL;
	mpCDASQLGetThisItemDesc = NULL;
	//Gurinder's code ends here

	// for regional auctions
	mpCDAGetAllRegions = NULL;
	mpCDAGetRegionZips = NULL;

	// for gallery admin tool to access ebay_special_items table
	mpCDAAddSpecialItem = NULL;
	mpCDADeleteSpecialItem = NULL;
	mpCDAFlushSpecialItem = NULL;

	for (i = 0; i < 11; i++)
	{
		mpCDAGetFeedbackDetail[i] = NULL;
		mpCDAGetFeedbackDetailPages[i] = NULL;
		mpCDAAddFeedbackDetail[i] = NULL;
		mpCDAGetMinimalFeedbackDetail[i] = NULL;
		mpCDAGetResponse[i] = NULL;
		mpCDAGetFollowUp[i] = NULL;
		mpCDAUpdateFeedbackResponse[i] = NULL;
		mpCDAUpdateFeedbackFollowUp[i] = NULL;
		mpCDAGetFeedbackDetailCount[i] = NULL;
		mpCDAVoidFeedbackLeftByUser[i] = NULL;
		mpCDARestoreFeedbackLeftByUser[i] = NULL;
		mpCDATransferFeedbackLeft[i] = NULL;
		mpCDAUserHasFeedbackFromUser[i] = NULL;
		mpCDAGetFeedbackDetailRowID[i] = NULL;
		mpCDAGetFeedbackItem[i] = NULL;
		mpCDASplitFeedbackDetail[i] = NULL; //inna
		mpCDAGetFeedbackDetailToSplit[i] = NULL;	// inna
		mpCDAGetRecomputedFeedbackScore[i] = NULL;
		mpCDAGetFeedbackDetailFromList[i] = NULL;
		mpCDAGetFeedbackDetailFromListMinimal[i] = NULL;
	}

	mpCDAGetDeadbeatItemsNotWarned			= NULL;
	mpCDAUpdateDeadbeatItem					= NULL;
	mpCDASetDeadbeatItemWarned				= NULL;

	// cursors for queries on ebay_categories
	mpCDAMaskCategory = NULL;
	mpCDAGetMaskedCategories = NULL;
	mpCDAFlagCategory = NULL;
	mpCDAGetFlaggedCategories = NULL;

	// cursors for queries on and changes to ebay_category_filters
	mpCDAAddCategoryFilter = NULL;
	mpCDADeleteCategoryFilter = NULL;
	mpCDAUpdateCategoryFilter = NULL;
	mpCDAGetCategoryFilter = NULL;
	mpCDAGetCategoryFiltersByCategoryId = NULL;
	mpCDAGetCategoryFilterCountByCategoryId = NULL;
	mpCDAGetCategoryFiltersByFilterId = NULL;
	mpCDAGetCategoryFilterCountByFilterId = NULL;
	mpCDAGetAllCategoryFilters = NULL;

	// cursors for queries on and changes to ebay_filters
	mpCDAAddFilter = NULL;
	mpCDADeleteFilterById = NULL;
	mpCDADeleteFilterByName = NULL;
	mpCDAUpdateFilterById = NULL;
	mpCDAUpdateFilterByName = NULL;
	mpCDAGetFilterById = NULL;
	mpCDAGetFilterByName = NULL;
	mpCDAGetFiltersByCategoryId = NULL;
	mpCDAGetAllFilters = NULL;
	mpCDAGetNextFilterId = NULL;
	mpCDAGetMaxFilterId = NULL;

	// cursors for queries on and changes to ebay_category_messages
	mpCDAAddCategoryMessage = NULL;
	mpCDADeleteCategoryMessage = NULL;
	mpCDAUpdateCategoryMessage = NULL;
	mpCDAGetCategoryMessage = NULL;
	mpCDAGetCategoryMessagesByCategoryId = NULL;
	mpCDAGetCategoryMessageCountByCategoryId = NULL;
	mpCDAGetCategoryMessagesByMessageId = NULL;
	mpCDAGetCategoryMessageCountByMessageId = NULL;
	mpCDAGetAllCategoryMessages = NULL;

	// cursors for queries on and changes to ebay_filter_messages
	mpCDAAddMinFilterMessage = NULL;
	mpCDADeleteMinFilterMessage = NULL;
	mpCDAUpdateMinFilterMessage = NULL;
	mpCDAGetMinFilterMessage = NULL;
	mpCDAGetMinFilterMessagesByFilterId = NULL;
	mpCDAGetMinFilterMessageCountByFilterId = NULL;
	mpCDAGetMinFilterMessagesByMessageId = NULL;
	mpCDAGetMinFilterMessageCountByMessageId = NULL;

	// cursors for queries on and changes to ebay_messages
	mpCDAAddMessage = NULL;
	mpCDADeleteMessageById = NULL;
	mpCDADeleteMessageByName = NULL;
	mpCDAUpdateMessageById = NULL;
	mpCDAUpdateMessageByName = NULL;
	mpCDAGetMessageById = NULL;
	mpCDAGetMessageLengthById = NULL;
	mpCDAGetMessageByName = NULL;
	mpCDAGetMessageLengthByName = NULL;
	mpCDAGetMessageByCategoryIdAndMessageType = NULL;
	mpCDAGetMessageLengthByCategoryIdAndMessageType = NULL;
	mpCDAGetMaxTextLen = NULL;
	mpCDAGetMaxTextLenByCategoryId = NULL;
	mpCDAGetMaxTextLenByFilterId = NULL;
	mpCDAGetMaxTextLenByMessageType = NULL;
	mpCDAGetMaxTextLenByCategoryIdAndMessageType = NULL;
	mpCDAGetAllMessages = NULL;
	mpCDAGetMessagesByCategoryId = NULL;
	mpCDAGetMessagesByFilterId = NULL;
	mpCDAGetMessagesByMessageType = NULL;
	mpCDAGetMessagesByCategoryIdAndMessageType = NULL;
	mpCDAGetNextMessageId = NULL;
	mpCDAGetMaxMessageId = NULL;

	// cursors for queries on and changes to ebay_items and
	// ebay_items_blocked
	mpCDAAddBlockedItem = NULL;
	mpCDAAddBlockedItemDesc = NULL;
	mpCDADeleteBlockedItem = NULL;
	mpCDADeleteBlockedItemDesc = NULL;
	mpCDAUpdateBlockedItem = NULL;
	mpCDAUpdateBlockedItemDesc = NULL;
	mpCDAGetBlockedItem = NULL;
	mpCDAGetBlockedItemRowId = NULL;
	mpCDAGetBlockedItemDesc = NULL;
	mpCDAGetBlockedItemDescLen = NULL;
	mpCDAGetBlockedItemCountById = NULL;
	mpCDAGetBlockedItemCount = NULL;
	mpCDAGetBlockedItemWithDescription = NULL;
	mpCDAGetBlockedItemWithDescriptionRowId = NULL;
//	End item cursors
	mpCDACopyItemsToEnded = NULL;	
	mpCDAGetThisBidsEnded = NULL;
	mpCDADeleteBidsEnded = NULL;
	mpCDACopyBidsToEnded = NULL;

	// A couple for BidListCleanup
	mpCDADeleteBidderList = NULL;
	mpCDADeleteSellerList = NULL;

	// Cursors for clsDatabaseOracleLocations.cpp
	mpCDALocationsIsValidZip = NULL;
	mpCDALocationsIsValidAC = NULL;
	mpCDALocationsIsValidCity = NULL;
	mpCDALocationsDoesACMatchZip = NULL;
	mpCDALocationsDoesACMatchState = NULL;
	mpCDALocationsDoesZipMatchState = NULL;
	mpCDALocationsDoesACMatchCity = NULL;
	mpCDALocationsDoesZipMatchCity = NULL;
	mpCDALocationsDoesCityMatchState = NULL;
	mpCDALocationsGetLLForZip = NULL;
	mpCDALocationsGetLLForAC = NULL;

	mpCDASetFeedbackScoreToUsers = NULL;

	//
	// cursors for cobrand headers/footers
	//
	mpCDAGetSiteHeadersAndFooters = NULL;
	mpCDAGetSitePartnerHeadersAndFooters = NULL;
	mpCDAGetPartnerHeaderText = NULL;
	mpCDALoadSites = NULL;
	mpCDALoadPartners = NULL;
	mpCDAGetAllMinimalSites = NULL;
	// kakiyama 06/23/99
	mpCDAGetForeignSites = NULL;
	mpCDAGetLocales = NULL;		// petra
	// nsacco 08/03/99
	mpCDALoadSite = NULL;

	// nsacco 08/03/99
	// exchange rates and currencies
	mpCDAGetNumCurrencies = NULL;
	mpCDAGetRatesForCurrency = NULL;
	mpCDAInsertExchangeRate = NULL;
	mpCDAGetNumExchangeRates = NULL;

	//
	// cursors for cobrand ad descriptions
	//
	mpCDAAddCobrandAdDesc = NULL;
	mpCDAGetCobrandAdDescTextLenById = NULL;
	mpCDAGetCobrandAdDescTextLenByName = NULL;
	mpCDAGetCobrandAdDescText = NULL;
	mpCDAGetMaxCobrandAdDescTextLen = NULL;
	mpCDAGetCobrandAdDescById = NULL;
	mpCDAGetCobrandAdDescByName = NULL;
	mpCDALoadAllCobrandAdDescs = NULL;
	mpCDAUpdateCobrandAdDescById = NULL;
	mpCDAUpdateCobrandAdDescByName = NULL;
	mpCDADeleteCobrandAdDescById = NULL;
	mpCDADeleteCobrandAdDescByName = NULL;
	mpCDAGetNextCobrandAdDescId = NULL;

	//
	// cursors for cobrand ads
	//
	mpCDAAddCobrandAd = NULL;
	mpCDAGetCobrandAdsById = NULL;
	mpCDAGetCobrandAdsBySite = NULL;
	mpCDAGetCobrandAdsBySiteAndPartner = NULL;
	mpCDAGetCobrandAdsByPage = NULL;
	mpCDALoadAllCobrandAds = NULL;
	mpCDAUpdateCobrandAd = NULL;
	mpCDADeleteCobrandAd = NULL;

	//
	// announcement
	//
	mpCDAAllAnnouncementsBySiteAndPartner = NULL;
	mpCDAAllAnnouncements = NULL;

	mpCDAOneShot						= NULL;
	mpCDACurrent						= NULL;


	// initialize mInTransaction
	mInTransaction = false;

#ifdef IS_MULTITHREADED
	// Make Thread Safe
	opinit(OCI_EV_TSF);
#endif // IS_MULTITHREADED

	// Connect to Oracle
	retries		= 0;
	retrylimit	= 5;

	mConnected	= false;
	if (IIS_Server_is_down()) return; //new outage code, automatic failover

	do
	{
		retries++;

//	PYTHON, ALGEBRA definitions moved up before this function

#if defined(_PYTHON_CGI)
		rc = olog((Lda_Def *)mpLDA, 
				  (ub1 *)mpHDA, 
				  (text *)"scott", -1, 
				  (text *)"mb5ic", -1,
				  (text *)"ebay", -1,
				  OCI_LM_DEF);  

#elif defined (_PYTHON_LOCAL)
		rc = olog((Lda_Def *)mpLDA, 
				  (ub1 *)mpHDA, 
				  (text *)"scott", -1, 
				  (text *)"mb5ic", -1,
				  (text *)NULL, -1,
				  OCI_LM_DEF);  
		
#elif defined(_ALGEBRA_CGI)
		rc = olog((Lda_Def *)mpLDA, 
				  (ub1 *)mpHDA, 
				  (text *)"e119", -1, // nsacco 07/09/99
				  (text *)"e119", -1,
				  (text *)"test", -1,
				  OCI_LM_DEF);  

#elif defined(_ALGEBRA_LOCAL)
		rc = olog((Lda_Def *)mpLDA, 
				  (ub1 *)mpHDA, 
				  (text *)"e119", -1, // nsacco 07/09/99 was ebayqa, pipsky
				  (text *)"e119", -1,
				  (text *)NULL, -1,
				  OCI_LM_DEF);  
#else
#error No database defined!
#endif


		// Let's see if we go a TNS could not connect to 
		// destination
		if (rc == -12154 ||
 			rc == -12203)
		{
			continue;
		}

		// The check will throw us an exception, and throw
		// us out of here, if there's a problem
		Check(rc);
		if (!(rc>4 || rc<0)) { //new outage code
			mConnected	= true;
			break;
		}
	} while (retries < retrylimit);

	// If we got here and we're not connected, do a Check
	// to get an exception
	if (!mConnected)
		Check(rc);

	return;
}

// A macro for our destructor
#define CLOSE_FORCE(cursor)			\
	if (cursor)						\
	{								\
		try							\
		{							\
	 		Close(&cursor, true);	\
		}							\
		catch(...)					\
		{							\
			;						\
		}							\
	}								\
	cursor	= NULL;

clsDatabaseOracle::~clsDatabaseOracle()
{
	int		rc;
	int		i;

	// Close any old cursors here
	CLOSE_FORCE(mpCDACurrent);
	CLOSE_FORCE(mpCDAOneShot);
	CLOSE_FORCE(mpCDAAdjustMarketPlaceItemCount);

	CLOSE_FORCE(mpCDAGetSingleItem);
	CLOSE_FORCE(mpCDAGetSingleItemWithDescription);
	CLOSE_FORCE(mpCDAGetSingleItemRowId);
	CLOSE_FORCE(mpCDAGetSingleItemWithDescriptionRowId);
	CLOSE_FORCE(mpCDAGetSingleItemEnded);
	CLOSE_FORCE(mpCDAGetSingleItemWithDescriptionEnded);
	CLOSE_FORCE(mpCDAGetSingleItemRowIdEnded);
	CLOSE_FORCE(mpCDAGetSingleItemWithDescriptionRowIdEnded);
	CLOSE_FORCE(mpCDAGetItemDesc);
	CLOSE_FORCE(mpCDAGetItemDescLen);
	CLOSE_FORCE(mpCDAGetItemDescEnded);
	CLOSE_FORCE(mpCDAGetItemDescLenEnded);
	CLOSE_FORCE(mpCDASetNewDescription);
	CLOSE_FORCE(mpCDAUpdateItemDesc);
	CLOSE_FORCE(mpCDAUpdateItemPassword);
	CLOSE_FORCE(mpCDADeleteItem);
	CLOSE_FORCE(mpCDADeleteItemEnded);
	CLOSE_FORCE(mpCDADeleteItemDesc);
	CLOSE_FORCE(mpCDADeleteItemDescEnded);
	CLOSE_FORCE(mpCDAGetFeedback);
	CLOSE_FORCE(mpCDAGetFeedbackDetailLeftByUser);
	CLOSE_FORCE(mpCDAGetFeedbackDetailLeftByUserSplit);
	CLOSE_FORCE(mpCDAGetUserById);
	CLOSE_FORCE(mpCDAGetUserAndFeedbackById);
	CLOSE_FORCE(mpCDAGetUserByUserId);
	CLOSE_FORCE(mpCDAGetUserAndFeedbackByUserId);
	CLOSE_FORCE(mpCDAGetUserAndFeedbackByEmail);
	CLOSE_FORCE(mpCDAGetUserIdForRenamedUser);
	CLOSE_FORCE(mpCDAGetUserAndInfoById);
	CLOSE_FORCE(mpCDAGetUserInfo);
	CLOSE_FORCE(mpCDAAddManyUsers);
	CLOSE_FORCE(mpCDAAddManyUsersInfo);
	CLOSE_FORCE(mpCDAGetHighBidForUser);
	CLOSE_FORCE(mpCDAGetHighBidForUserEnded);
	CLOSE_FORCE(mpCDAGetHighBidsForItemEnded);
	CLOSE_FORCE(mpCDAGetHighBidsForItem);
	CLOSE_FORCE(mpCDAGetDutchHighBidders);
	CLOSE_FORCE(mpCDAAddBid);
	CLOSE_FORCE(mpCDAAddBlockedBid);
	CLOSE_FORCE(mpCDASetNewHighBidder);
	CLOSE_FORCE(mpCDASetNewHighBidderAndBidCount);
	CLOSE_FORCE(mpCDASetNewBidCount);
	CLOSE_FORCE(mpCDASetDutchHighBidder);
	CLOSE_FORCE(mpCDADeleteDutchHighBidder);
	CLOSE_FORCE(mpCDASetNewCategory);
	CLOSE_FORCE(mpCDAAdjustAccountBalance);
	CLOSE_FORCE(mpCDAGetBids);
	CLOSE_FORCE(mpCDAGetBidsEnded);
	CLOSE_FORCE(mpCDAGetItemsListedByUser);
	CLOSE_FORCE(mpCDAGetItemsListedByUserGetMoreStuff);
	CLOSE_FORCE(mpCDAGetItemsListedByUserEnded);
	CLOSE_FORCE(mpCDAGetItemsListedByUserGetMoreStuffEnded);
	CLOSE_FORCE(mpCDAGetItemsListedByUserWithCompleted);
	CLOSE_FORCE(mpCDAGetItemsListedByUserWithCompleted2);
	CLOSE_FORCE(mpCDAGetItemsHighBidByUser);
	CLOSE_FORCE(mpCDAGetItemsHighBidByUserWithCompleted);
	CLOSE_FORCE(mpCDAGetItemsHighBidByUserWithCompletedEnded);
	CLOSE_FORCE(mpCDAGetItemsBidByUser);
	CLOSE_FORCE(mpCDAGetItemsBidByUserEnded);
	CLOSE_FORCE(mpCDAGetItemsBidByUserGetMoreStuff);
	CLOSE_FORCE(mpCDAGetItemsBidByUserGetMoreStuffEnded);
	CLOSE_FORCE(mpCDAGetItemsBidByUserWithCompleted);
	CLOSE_FORCE(mpCDAGetItemsBidByUserWithCompletedByDate);
	CLOSE_FORCE(mpCDAGetItemsBidByUserWithCompletedByDate2);
	CLOSE_FORCE(mpCDAGetBillTime);
	CLOSE_FORCE(mpCDAGetBillTimeByRowId);
	CLOSE_FORCE(mpCDAGetBillTimeEnded);
	CLOSE_FORCE(mpCDAAddItemBilled);
	CLOSE_FORCE(mpCDAUpdateItemBilled);
	CLOSE_FORCE(mpCDAAddItemBilledByRowId);
	CLOSE_FORCE(mpCDAModifyItemBilled);
	CLOSE_FORCE(mpCDAUpdateItemEnded);
	CLOSE_FORCE(mpCDAUpdateItemBlocked);
	CLOSE_FORCE(mpCDAUpdateItem);
	CLOSE_FORCE(mpCDAAddItemNoticed);
	CLOSE_FORCE(mpCDAAddItemNoticedByRowId);
	CLOSE_FORCE(mpCDAModifyItemNoticed);
	CLOSE_FORCE(mpCDAGetItemsModifiedAfter);
	CLOSE_FORCE(mpCDAGetCategoryById);
//	CLOSE_FORCE(mpCDAGetCategoryByName);
	CLOSE_FORCE(mpCDAGetCategoryFirst);
	CLOSE_FORCE(mpCDAGetCategoryAll);
	CLOSE_FORCE(mpCDAGetCategoryTopLevel);
	CLOSE_FORCE(mpCDAGetCategoryChildren);
	CLOSE_FORCE(mpCDAGetCategoryDescendants);
//	CLOSE_FORCE(mpCDAGetCategorySiblings);
	CLOSE_FORCE(mpCDAGetCategoryLeaves);
	CLOSE_FORCE(mpCDAGetCategoryChildrenSorted);
	CLOSE_FORCE(mpCDAGetCategoryVector);
	CLOSE_FORCE(mpCDAGetCategorySiblingPrev);
	CLOSE_FORCE(mpCDAGetCategorySiblingNext);
	CLOSE_FORCE(mpCDAAddRawAccountDetail);
	CLOSE_FORCE(mpCDAAddRawAccountDetail_0);
	CLOSE_FORCE(mpCDAAddRawAccountDetail_1);
	CLOSE_FORCE(mpCDAAddRawAccountDetail_2);
	CLOSE_FORCE(mpCDAAddRawAccountDetail_3);
	CLOSE_FORCE(mpCDAAddRawAccountDetail_4);
	CLOSE_FORCE(mpCDAAddRawAccountDetail_5);
	CLOSE_FORCE(mpCDAAddRawAccountDetail_6);
	CLOSE_FORCE(mpCDAAddRawAccountDetail_7);
	CLOSE_FORCE(mpCDAAddRawAccountDetail_8);
	CLOSE_FORCE(mpCDAAddRawAccountDetail_9);
	CLOSE_FORCE(mpCDAGetPaymentsSince);
	CLOSE_FORCE(mpCDAGetPaymentsSince_0);
	CLOSE_FORCE(mpCDAGetPaymentsSince_1);
	CLOSE_FORCE(mpCDAGetPaymentsSince_2);
	CLOSE_FORCE(mpCDAGetPaymentsSince_3);
	CLOSE_FORCE(mpCDAGetPaymentsSince_4);
	CLOSE_FORCE(mpCDAGetPaymentsSince_5);
	CLOSE_FORCE(mpCDAGetPaymentsSince_6);
	CLOSE_FORCE(mpCDAGetPaymentsSince_7);
	CLOSE_FORCE(mpCDAGetPaymentsSince_8);
	CLOSE_FORCE(mpCDAGetPaymentsSince_9);
	CLOSE_FORCE(mpCDAAddAccountAWItemXref);
	CLOSE_FORCE(mpCDAAddItemDescArc);
	CLOSE_FORCE(mpCDAAddItemDescEnded);
	CLOSE_FORCE(mpCDAUpdateItemDescArc);
	CLOSE_FORCE(mpCDAUpdateItemDesc);
	CLOSE_FORCE(mpCDARecentFeedbackFromUser);
	CLOSE_FORCE(mpCDARecentFeedbackFromUserSplit);
	CLOSE_FORCE(mpCDARecentNegativeFeedbackFromUser);
	CLOSE_FORCE(mpCDARecentNegativeFeedbackFromUserSplit);
	CLOSE_FORCE(mpCDARecentFeedbackFromHost);
	CLOSE_FORCE(mpCDARecentFeedbackFromHostSplit);
	CLOSE_FORCE(mpCDARecentNegativeFeedbackFromHost);
	CLOSE_FORCE(mpCDARecentNegativeFeedbackFromHostSplit);
	CLOSE_FORCE(mpCDAGetUserByEmail);
	CLOSE_FORCE(mpCDAGetIdForPriorAlias);
	CLOSE_FORCE(mpCDAGetIdForPriorEmail);
	CLOSE_FORCE(mpCDAAddBBEntry);
	CLOSE_FORCE(mpCDAGetBulletinBoardStatistics);
	CLOSE_FORCE(mpCDAGetBulletinBoardEntries);
	CLOSE_FORCE(mpCDAGetBulletinBoardTimes);
	CLOSE_FORCE(mpCDAUpdateBBLastPostTime);
	CLOSE_FORCE(mpCDADeleteBulletinBoardEntries);
	CLOSE_FORCE(mpCDAGetSellerItemListFromItems);
	CLOSE_FORCE(mpCDAGetSellerItemListFromSellerList);
	CLOSE_FORCE(mpCDAGetSellerItemListFromItemsEnded);
	CLOSE_FORCE(mpCDAGetSellerListSize);
	CLOSE_FORCE(mpCDAAddSellerList);
	CLOSE_FORCE(mpCDAUpdateSellerList);
	CLOSE_FORCE(mpCDAInvalidateSellerList);
	CLOSE_FORCE(mpCDAInvalidateExtendedFeedback);
	CLOSE_FORCE(mpCDAUpdateExtendedFeedback);
	CLOSE_FORCE(mpCDAGetBidderItemListFromBids);
	CLOSE_FORCE(mpCDAGetBidderItemListFromBidsEnded);
	CLOSE_FORCE(mpCDAGetBidderItemListFromItems);
	CLOSE_FORCE(mpCDAGetBidderItemListFromBidderList);
	CLOSE_FORCE(mpCDAGetBidderListSize);
	CLOSE_FORCE(mpCDAGetBidderList);
	CLOSE_FORCE(mpCDAUpdateBidderList);
	CLOSE_FORCE(mpCDAInvalidateBidderList);
	CLOSE_FORCE(mpCDAAddBidderList);
	CLOSE_FORCE(mpCDAAddLinkButton);
	CLOSE_FORCE(mpCDASetFeedbackScore);
	CLOSE_FORCE(mpCDAAddReqEmailCount);
	CLOSE_FORCE(mpCDAInsertNumberAttribute);
	CLOSE_FORCE(mpCDAAddItemDesc);
	CLOSE_FORCE(mpCDAAddItem);
	CLOSE_FORCE(mpCDAGetNextItemId);
	CLOSE_FORCE(mpCDAUpdateItemStatus);
	CLOSE_FORCE(mpCDAUpdateItemStatusEnded);
	CLOSE_FORCE(mpCDAAddAccountDetail);
	CLOSE_FORCE(mpCDAAddAccountDetail_0);
	CLOSE_FORCE(mpCDAAddAccountDetail_1);
	CLOSE_FORCE(mpCDAAddAccountDetail_2);
	CLOSE_FORCE(mpCDAAddAccountDetail_3);
	CLOSE_FORCE(mpCDAAddAccountDetail_4);
	CLOSE_FORCE(mpCDAAddAccountDetail_5);
	CLOSE_FORCE(mpCDAAddAccountDetail_6);
	CLOSE_FORCE(mpCDAAddAccountDetail_7);
	CLOSE_FORCE(mpCDAAddAccountDetail_8);
	CLOSE_FORCE(mpCDAAddAccountDetail_9);
	CLOSE_FORCE(mpCDAAddAccountItemXref);
	CLOSE_FORCE(mpCDAGetNextTransactionId);
	CLOSE_FORCE(mpCDAGetAccount);
	CLOSE_FORCE(mpCDAGetAccountDetail);
	CLOSE_FORCE(mpCDAGetAccountDetail_0);
	CLOSE_FORCE(mpCDAGetAccountDetail_1);
	CLOSE_FORCE(mpCDAGetAccountDetail_2);
	CLOSE_FORCE(mpCDAGetAccountDetail_3);
	CLOSE_FORCE(mpCDAGetAccountDetail_4);
	CLOSE_FORCE(mpCDAGetAccountDetail_5);
	CLOSE_FORCE(mpCDAGetAccountDetail_6);
	CLOSE_FORCE(mpCDAGetAccountDetail_7);
	CLOSE_FORCE(mpCDAGetAccountDetail_8);
	CLOSE_FORCE(mpCDAGetAccountDetail_9);
	CLOSE_FORCE(mpCDASetReadOnly);
	CLOSE_FORCE(mpCDAGetAnnouncement);
	CLOSE_FORCE(mpCDAGetAnnouncementLen);
	CLOSE_FORCE(mpCDAGetManyItemsForCreditBatch);
	CLOSE_FORCE(mpCDAGetManyEndedItemsForCreditBatch);
	CLOSE_FORCE(mpCDAGetManyArcItemsForCreditBatch);
	CLOSE_FORCE(mpCDAGetManyUsersForCreditBatch);
	CLOSE_FORCE(mpCDAGetManyUsers);
	CLOSE_FORCE(mpCDAAddReciprocalLink);
	CLOSE_FORCE(mpCDAGetInterimBalances);
	CLOSE_FORCE(mpCDAIncrementPartnerCount);
	CLOSE_FORCE(mpCDAGetLastCountAndTime);
	CLOSE_FORCE(mpCDAUpdateReceiveInfo);
	CLOSE_FORCE(mpCDAGetAliasHistory);
	CLOSE_FORCE(mpCDAGetAdultCategoryIds);
	CLOSE_FORCE(mpCDAGetUserPage);
	CLOSE_FORCE(mpCDAGetUserPageText);
	CLOSE_FORCE(mpCDAAddViewToUserPage);

	CLOSE_FORCE(mpCDAGetEndOfMonthBalanceById);
	CLOSE_FORCE(mpCDAAddEndOfMonthBalance);

	CLOSE_FORCE(mpCDAGetAllCountries);
	CLOSE_FORCE(mpCDAGetAllCurrencies);

	CLOSE_FORCE(mpCDAAddTransactionRecord);
	CLOSE_FORCE(mpCDAGetTransactionRecord);
	CLOSE_FORCE(mpCDASetTransactionUsed);

	CLOSE_FORCE(mpCDADeleteTransactionRecord);


	CLOSE_FORCE(mpCDAAddGiftOccasion);
	CLOSE_FORCE(mpCDADeleteGiftOccasion);
	CLOSE_FORCE(mpCDAGetNextGiftOccasionId);
	CLOSE_FORCE(mpCDAGetSingleGiftOccasion);

	CLOSE_FORCE(mpCDAGetFeedbackListFromFeedbackList);
	CLOSE_FORCE(mpCDAGetFeedbackListSize);
	CLOSE_FORCE(mpCDAAddFeedbackList);
	CLOSE_FORCE(mpCDAUpdateFeedbackList);
	CLOSE_FORCE(mpCDAInvalidateFeedbackList);

	CLOSE_FORCE(mpCDAGetManyItemsForAuctionEnd);
	CLOSE_FORCE(mpCDAGetManyEndedItemsForAuctionEnd);

	// Gallery
	CLOSE_FORCE(mpCDAGetGalleryChangedItem);
	CLOSE_FORCE(mpCDAAppendGalleryChangedItem);
	CLOSE_FORCE(mpCDASetGalleryChangedItemState);
	CLOSE_FORCE(mpCDADeleteGalleryChangedItem);
	CLOSE_FORCE(mpCDAGetCurrentGallerySequence);
	CLOSE_FORCE(mpCDAGetNextGallerySequence);
	CLOSE_FORCE(mpCDAGetCurrentGalleryReadSequence);
	CLOSE_FORCE(mpCDAGetNextGalleryReadSequence);
	CLOSE_FORCE(mpCDASetItemGalleryInfo);
	CLOSE_FORCE(mpCDAGetItemGalleryInfo);
	CLOSE_FORCE(mpCDAGetGallerySequenceRange);

	// Trust and safety
	CLOSE_FORCE(mpCDAGetAuctionIds);
	CLOSE_FORCE(mpCDAGetAuctionsWon);
	CLOSE_FORCE(mpCDAGetAuctionsBidOn);
	CLOSE_FORCE(mpCDAGetAuctionsWithRetractions);
	CLOSE_FORCE(mpCDAGetAuctionIdsEnded);
	CLOSE_FORCE(mpCDAGetAuctionsWonEnded);
	CLOSE_FORCE(mpCDAGetAuctionsBidOnEnded);
	CLOSE_FORCE(mpCDAGetAuctionsWithRetractionsEnded);
	CLOSE_FORCE(mpCDAGetSellersOfAuctionsEnded);
	CLOSE_FORCE(mpCDAGetSellersOfAuctions);
	CLOSE_FORCE(mpCDAGetOurAuctionsBidOnByUs);
	CLOSE_FORCE(mpCDAGetOurAuctionsBidOnByUsEnded);
	CLOSE_FORCE(mpCDAGetShillInformationForOurAuctionsEnded);
	CLOSE_FORCE(mpCDAGetBidsFromTheseUsersEnded);
	CLOSE_FORCE(mpCDAGetShillInformationForOurAuctions);
	CLOSE_FORCE(mpCDAGetBidsFromTheseUsers);
	CLOSE_FORCE(mpCDAClearAllDeadbeatItems);
	CLOSE_FORCE(mpCDADeleteDeadbeatItem);
	CLOSE_FORCE(mpCDAGetSingleDeadbeatItem);
	CLOSE_FORCE(mpCDAGetSingleDeadbeatItemRowId);
	CLOSE_FORCE(mpCDAGetDeadbeatItem);
	CLOSE_FORCE(mpCDAGetDeadbeatItemWithRowId);
	CLOSE_FORCE(mpCDAAddDeadbeatItem);
	CLOSE_FORCE(mpCDAGetDeadbeatScore);
	CLOSE_FORCE(mpCDAGetDeadbeatItemsByBidderId);
	CLOSE_FORCE(mpCDAGetDeadbeatItemsBySellerId);
	CLOSE_FORCE(mpCDAGetAllDeadbeatItems);
	CLOSE_FORCE(mpCDAAddDeadbeat);
	CLOSE_FORCE(mpCDAGetDeadbeat);
	CLOSE_FORCE(mpCDAGetAllDeadbeats);
	CLOSE_FORCE(mpCDASetDeadbeatScore);
	CLOSE_FORCE(mpCDASetCreditRequestCount);
	CLOSE_FORCE(mpCDAGetCreditRequestCount);
	CLOSE_FORCE(mpCDASetWarningCount);
	CLOSE_FORCE(mpCDAGetWarningCount);
	CLOSE_FORCE(mpCDAGetDeadbeatItemCountByBidderId);
	CLOSE_FORCE(mpCDAGetDeadbeatItemCountBySellerId);
	CLOSE_FORCE(mpCDAInvalidateDeadbeatScore);
	CLOSE_FORCE(mpCDAValidateDeadbeatScore);
	CLOSE_FORCE(mpCDAInvalidateCreditRequestCount);
	CLOSE_FORCE(mpCDAValidateCreditRequestCount);
	CLOSE_FORCE(mpCDAInvalidateWarningCount);
	CLOSE_FORCE(mpCDAValidateWarningCount);
	CLOSE_FORCE(mpCDAGetDeadbeatItemsWarnedCountByBidderId);
	CLOSE_FORCE(mpCDAGetDeadbeatItemsNotWarned);
	CLOSE_FORCE(mpCDAUpdateDeadbeatItem);
	CLOSE_FORCE(mpCDASetDeadbeatItemWarned);

	//item and item_info merge special: double writes
	CLOSE_FORCE(mpCDAAddItemBilledToItems);
	CLOSE_FORCE(mpCDAAddItemNoticedToItems);
	CLOSE_FORCE(mpCDAGetNoticeTime);
	CLOSE_FORCE(mpCDAGetNoticeTimeByRowId);
	CLOSE_FORCE(mpCDAGetNoticeTimeEnded);	
	CLOSE_FORCE(mpCDACopyItemInfo);
	CLOSE_FORCE(mpCDADeleteItemInfo);
	CLOSE_FORCE(mpCDAGetThisBids);
	CLOSE_FORCE(mpCDAGetThisBidsFromEnded);
	CLOSE_FORCE(mpCDADeleteBidsFromEnded);
	CLOSE_FORCE(mpCDACopyBids);
	CLOSE_FORCE(mpCDADeleteBids);
	CLOSE_FORCE(mpCDADeleteBid);
	CLOSE_FORCE(mpCDACopyItems);
	CLOSE_FORCE(mpDeleteItemInfo);	
	CLOSE_FORCE(mpCDAInterimBalance);
	CLOSE_FORCE(mpCDAGetItemDescArc);
	CLOSE_FORCE(mpCDAUpdateDutchGMS);
	CLOSE_FORCE(mpCDAUpdateDutchGMSByRowId);

	//Gurinder - for ReInstate Item
	//copy
	CLOSE_FORCE(mpCDACopyArcItems);				
	CLOSE_FORCE(mpCDACopyArcBids);		
	//delete
	CLOSE_FORCE(mpCDADeleteArcItems);
	CLOSE_FORCE(mpCDADeleteArcItemDesc);		
	CLOSE_FORCE(mpCDADeleteArcBids);
	//rec count
	CLOSE_FORCE(mpCDASQLGetThisArcBids);
	CLOSE_FORCE(mpCDASQLGetThisItem);
	CLOSE_FORCE(mpCDASQLGetThisItemBids);
	CLOSE_FORCE(mpCDASQLGetThisItemDesc);
	//Gurinder's code end here


	// for regional auctions
	CLOSE_FORCE(mpCDAGetAllRegions);
	CLOSE_FORCE(mpCDAGetRegionZips);

	// for gallery admin tool to access ebay_special_items table
	CLOSE_FORCE(mpCDAAddSpecialItem);
	CLOSE_FORCE(mpCDADeleteSpecialItem);
	CLOSE_FORCE(mpCDAFlushSpecialItem);

	for (i = 0; i < 11; i++)
	{
		CLOSE_FORCE(mpCDAGetFeedbackDetail[i]);
		CLOSE_FORCE(mpCDAGetFeedbackDetailPages[i]);
		CLOSE_FORCE(mpCDAAddFeedbackDetail[i]);
		CLOSE_FORCE(mpCDAGetMinimalFeedbackDetail[i]);
		CLOSE_FORCE(mpCDAGetResponse[i]);
		CLOSE_FORCE(mpCDAGetFollowUp[i]);
		CLOSE_FORCE(mpCDAUpdateFeedbackResponse[i]);
		CLOSE_FORCE(mpCDAUpdateFeedbackFollowUp[i]);
		CLOSE_FORCE(mpCDAGetFeedbackDetailCount[i]);
		CLOSE_FORCE(mpCDAVoidFeedbackLeftByUser[i]);
		CLOSE_FORCE(mpCDARestoreFeedbackLeftByUser[i]);
		CLOSE_FORCE(mpCDATransferFeedbackLeft[i]);
		CLOSE_FORCE(mpCDAUserHasFeedbackFromUser[i]);
		CLOSE_FORCE(mpCDAGetFeedbackDetailRowID[i]);
		CLOSE_FORCE(mpCDAGetFeedbackItem[i]);
		CLOSE_FORCE(mpCDASplitFeedbackDetail[i]); //inna
		CLOSE_FORCE(mpCDAGetFeedbackDetailToSplit[i]);	// inna
		CLOSE_FORCE(mpCDAGetRecomputedFeedbackScore[i]);
		CLOSE_FORCE(mpCDAGetFeedbackDetailFromList[i]);
		CLOSE_FORCE(mpCDAGetFeedbackDetailFromListMinimal[i]);
	}

	// cursors for queries on ebay_categories
	CLOSE_FORCE(mpCDAMaskCategory);
	CLOSE_FORCE(mpCDAGetMaskedCategories);
	CLOSE_FORCE(mpCDAFlagCategory);
	CLOSE_FORCE(mpCDAGetFlaggedCategories);

	// cursors for queries on and changes to ebay_category_filters
	CLOSE_FORCE(mpCDAAddCategoryFilter);
	CLOSE_FORCE(mpCDADeleteCategoryFilter);
	CLOSE_FORCE(mpCDAUpdateCategoryFilter);
	CLOSE_FORCE(mpCDAGetCategoryFilter);
	CLOSE_FORCE(mpCDAGetCategoryFiltersByCategoryId);
	CLOSE_FORCE(mpCDAGetCategoryFilterCountByCategoryId);
	CLOSE_FORCE(mpCDAGetCategoryFiltersByFilterId);
	CLOSE_FORCE(mpCDAGetCategoryFilterCountByFilterId);
	CLOSE_FORCE(mpCDAGetAllCategoryFilters);

	// cursors for queries on and changes to ebay_filters
	CLOSE_FORCE(mpCDAAddFilter);
	CLOSE_FORCE(mpCDADeleteFilterById);
	CLOSE_FORCE(mpCDADeleteFilterByName);
	CLOSE_FORCE(mpCDAUpdateFilterById);
	CLOSE_FORCE(mpCDAUpdateFilterByName);
	CLOSE_FORCE(mpCDAGetFilterById);
	CLOSE_FORCE(mpCDAGetFilterByName);
	CLOSE_FORCE(mpCDAGetFiltersByCategoryId);
	CLOSE_FORCE(mpCDAGetAllFilters);
	CLOSE_FORCE(mpCDAGetNextFilterId);
	CLOSE_FORCE(mpCDAGetMaxFilterId);

	// cursors for queries on and changes to ebay_category_messages
	CLOSE_FORCE(mpCDAAddCategoryMessage);
	CLOSE_FORCE(mpCDADeleteCategoryMessage);
	CLOSE_FORCE(mpCDAUpdateCategoryMessage);
	CLOSE_FORCE(mpCDAGetCategoryMessage);
	CLOSE_FORCE(mpCDAGetCategoryMessagesByCategoryId);
	CLOSE_FORCE(mpCDAGetCategoryMessageCountByCategoryId);
	CLOSE_FORCE(mpCDAGetCategoryMessagesByMessageId);
	CLOSE_FORCE(mpCDAGetCategoryMessageCountByMessageId);
	CLOSE_FORCE(mpCDAGetAllCategoryMessages);

	// cursors for queries on and changes to ebay_filter_messages
	CLOSE_FORCE(mpCDAAddMinFilterMessage);
	CLOSE_FORCE(mpCDADeleteMinFilterMessage);
	CLOSE_FORCE(mpCDAUpdateMinFilterMessage);
	CLOSE_FORCE(mpCDAGetMinFilterMessage);
	CLOSE_FORCE(mpCDAGetMinFilterMessagesByFilterId);
	CLOSE_FORCE(mpCDAGetMinFilterMessageCountByFilterId);
	CLOSE_FORCE(mpCDAGetMinFilterMessagesByMessageId);
	CLOSE_FORCE(mpCDAGetMinFilterMessageCountByMessageId);

	// cursors for queries on and changes to ebay_messages
	CLOSE_FORCE(mpCDAAddMessage);
	CLOSE_FORCE(mpCDADeleteMessageById);
	CLOSE_FORCE(mpCDADeleteMessageByName);
	CLOSE_FORCE(mpCDAUpdateMessageById);
	CLOSE_FORCE(mpCDAUpdateMessageByName);
	CLOSE_FORCE(mpCDAGetMessageById);
	CLOSE_FORCE(mpCDAGetMessageLengthById);
	CLOSE_FORCE(mpCDAGetMessageByName);
	CLOSE_FORCE(mpCDAGetMessageLengthByName);
	CLOSE_FORCE(mpCDAGetMessageByCategoryIdAndMessageType);
	CLOSE_FORCE(mpCDAGetMessageLengthByCategoryIdAndMessageType);
	CLOSE_FORCE(mpCDAGetMaxTextLen);
	CLOSE_FORCE(mpCDAGetMaxTextLenByCategoryId);
	CLOSE_FORCE(mpCDAGetMaxTextLenByFilterId);
	CLOSE_FORCE(mpCDAGetMaxTextLenByMessageType);
	CLOSE_FORCE(mpCDAGetMaxTextLenByCategoryIdAndMessageType);
	CLOSE_FORCE(mpCDAGetAllMessages);
	CLOSE_FORCE(mpCDAGetMessagesByCategoryId);
	CLOSE_FORCE(mpCDAGetMessagesByFilterId);
	CLOSE_FORCE(mpCDAGetMessagesByMessageType);
	CLOSE_FORCE(mpCDAGetMessagesByCategoryIdAndMessageType);
	CLOSE_FORCE(mpCDAGetNextMessageId);
	CLOSE_FORCE(mpCDAGetMaxMessageId);

	// cursors for queries on and changes to ebay_items and
	// ebay_items_blocked
	CLOSE_FORCE(mpCDAAddBlockedItem);
	CLOSE_FORCE(mpCDAAddBlockedItemDesc);
	CLOSE_FORCE(mpCDADeleteBlockedItem);
	CLOSE_FORCE(mpCDADeleteBlockedItemDesc);
	CLOSE_FORCE(mpCDAUpdateBlockedItem);
	CLOSE_FORCE(mpCDAUpdateBlockedItemDesc);
	CLOSE_FORCE(mpCDAGetBlockedItem);
	CLOSE_FORCE(mpCDAGetBlockedItemRowId);
	CLOSE_FORCE(mpCDAGetBlockedItemDesc);
	CLOSE_FORCE(mpCDAGetBlockedItemDescLen);
	CLOSE_FORCE(mpCDAGetBlockedItemCountById);
	CLOSE_FORCE(mpCDAGetBlockedItemCount);
	CLOSE_FORCE(mpCDAGetBlockedItemWithDescription);
	CLOSE_FORCE(mpCDAGetBlockedItemWithDescriptionRowId);
	CLOSE_FORCE(mpCDACopyItemsToEnded);	
	CLOSE_FORCE(mpCDAGetThisBidsEnded);
	CLOSE_FORCE(mpCDADeleteBidsEnded);
	CLOSE_FORCE(mpCDACopyBidsToEnded);

	// A couple for BidListCleanup
	CLOSE_FORCE(mpCDADeleteBidderList);
	CLOSE_FORCE(mpCDADeleteSellerList);

	// Cursors for clsDatabaseOracleLocations.cpp
	CLOSE_FORCE(mpCDALocationsIsValidZip);
	CLOSE_FORCE(mpCDALocationsIsValidAC);
	CLOSE_FORCE(mpCDALocationsIsValidCity);
	CLOSE_FORCE(mpCDALocationsDoesACMatchZip);
	CLOSE_FORCE(mpCDALocationsDoesACMatchState);
	CLOSE_FORCE(mpCDALocationsDoesZipMatchState);
	CLOSE_FORCE(mpCDALocationsDoesACMatchCity);
	CLOSE_FORCE(mpCDALocationsDoesZipMatchCity);
	CLOSE_FORCE(mpCDALocationsDoesCityMatchState);
	CLOSE_FORCE(mpCDALocationsGetLLForZip);
	CLOSE_FORCE(mpCDALocationsGetLLForAC);

	CLOSE_FORCE(mpCDASetFeedbackScoreToUsers);

	CLOSE_FORCE(mpCDAGetSiteHeadersAndFooters);
	CLOSE_FORCE(mpCDAGetSitePartnerHeadersAndFooters);
	CLOSE_FORCE(mpCDAGetPartnerHeaderText);
	CLOSE_FORCE(mpCDALoadSites);
	CLOSE_FORCE(mpCDALoadPartners);
	CLOSE_FORCE(mpCDAGetAllMinimalSites);
	// kakiyama 06/23/99
	CLOSE_FORCE(mpCDAGetForeignSites);
	CLOSE_FORCE(mpCDAGetLocales);		// petra
	// nsacco 08/03/99
	CLOSE_FORCE(mpCDALoadSite);
	CLOSE_FORCE(mpCDAGetNumCurrencies);
	CLOSE_FORCE(mpCDAGetRatesForCurrency);
	CLOSE_FORCE(mpCDAInsertExchangeRate);
	CLOSE_FORCE(mpCDAGetNumExchangeRates);

	CLOSE_FORCE(mpCDAAddCobrandAdDesc);
	CLOSE_FORCE(mpCDAGetCobrandAdDescTextLenById);
	CLOSE_FORCE(mpCDAGetCobrandAdDescTextLenByName);
	CLOSE_FORCE(mpCDAGetCobrandAdDescText);
	CLOSE_FORCE(mpCDAGetMaxCobrandAdDescTextLen);
	CLOSE_FORCE(mpCDAGetCobrandAdDescById);
	CLOSE_FORCE(mpCDAGetCobrandAdDescByName);
	CLOSE_FORCE(mpCDALoadAllCobrandAdDescs);
	CLOSE_FORCE(mpCDAUpdateCobrandAdDescById);
	CLOSE_FORCE(mpCDAUpdateCobrandAdDescByName);
	CLOSE_FORCE(mpCDADeleteCobrandAdDescById);
	CLOSE_FORCE(mpCDADeleteCobrandAdDescByName);
	CLOSE_FORCE(mpCDAGetNextCobrandAdDescId);

	CLOSE_FORCE(mpCDAAddCobrandAd);
	CLOSE_FORCE(mpCDAGetCobrandAdsById);
	CLOSE_FORCE(mpCDAGetCobrandAdsBySite);
	CLOSE_FORCE(mpCDAGetCobrandAdsBySiteAndPartner);
	CLOSE_FORCE(mpCDAGetCobrandAdsByPage);
	CLOSE_FORCE(mpCDALoadAllCobrandAds);
	CLOSE_FORCE(mpCDAUpdateCobrandAd);
	CLOSE_FORCE(mpCDADeleteCobrandAd);

	CLOSE_FORCE(mpCDAAllAnnouncementsBySiteAndPartner);
	CLOSE_FORCE(mpCDAAllAnnouncements);

	// Close the Connection to Oracle
	if (mConnected)
	{
		try
		{
	  		rc		= ologof((struct cda_def *)mpLDA);
			Check(rc);
		}
		catch(...)
		{
			;
		}
 	}
				
	mConnected		= 0;
	mInTransaction	= false;
	 
	// Delete the LDA and HDA
	delete mpLDA;
	delete [] mpHDA;

	delete [] mpSellerListBuffer;
	delete [] mpBidderListBuffer;
	delete [] mpFeedbackListBuffer;

	delete [] mpDescriptionBuffer;

	delete [] mpUserPageBuffer;

	// Bye!
	return;
}

//
// Various modes for transactions
//
const char *SQL_SetReadCommitted =
"SET TRANSACTION ISOLATION LEVEL READ COMMITTED";

void clsDatabaseOracle::SetReadCommitted()
{
	OpenAndParse(&mpCDAOneShot,
				 SQL_SetReadCommitted);

	Execute();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

const char *SQL_SetSerializable =
"SET TRANSACTION ISOLATION LEVEL SERIALIZABLE";

void clsDatabaseOracle::SetSerializable()
{
	OpenAndParse(&mpCDAOneShot,
				 SQL_SetSerializable);

	Execute();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

const char *SQL_SetReadOnly =
"SET TRANSACTION READ ONLY";

void clsDatabaseOracle::SetReadOnly()
{
	OpenAndParse(&mpCDASetReadOnly,
				 SQL_SetReadOnly);

	Execute();

	Close(&mpCDASetReadOnly);
	SetStatement(NULL);

	return;
}


//
// DeleteBid
//	
static char *SQL_DeleteBid =
"	delete from ebay_bids	"
"	where	item_id = :item_id "
"	and		user_id = :user_id";

void clsDatabaseOracle::DeleteBid(int item, int user)
{
	//delete from ebay_bids
	OpenAndParse(&mpCDADeleteBid, SQL_DeleteBid);

	// Ok, let's do some binds	
	Bind(":item_id", &item);
	Bind(":user_id", &user);

	// Do it
	Execute();
	Commit();

	Close(&mpCDADeleteBid);
	SetStatement(NULL);
}


//
// DeleteBids
//	
static char *SQL_DeleteBids =
"	delete from ebay_bids	"
"	where	marketplace = :marketplace	"
"	and item_id = :item_id";

void clsDatabaseOracle::DeleteBids(int marketplace, int item)
{
	//delete from ebay_bids
	OpenAndParse(&mpCDADeleteBids, SQL_DeleteBids);

	// Ok, let's do some binds	
	Bind(":marketplace", &marketplace);
	Bind(":item_id", &item);

	// Do it
	Execute();
	Commit();

	Close(&mpCDADeleteBids);
	SetStatement(NULL);
}


//
// GetBids
//	
static char *SQL_GetBids =
 "select	user_id,							\
			type,								\
			quantity,							\
			amount,								\
			value,								\
			TO_CHAR(created,					\
					'YYYY-MM-DD HH24:MI:SS'),	\
			reason								\
  from ebay_bids								\
  where	marketplace = :marketplace				\
  and	item_id = :item_id";

static char *SQL_GetBidsEnded =
 "select	user_id,							\
			type,								\
			quantity,							\
			amount,								\
			value,								\
			TO_CHAR(created,					\
					'YYYY-MM-DD HH24:MI:SS'),	\
			reason								\
  from ebay_bids_ended							\
  where	marketplace = :marketplace				\
  and	item_id = :item_id";

#define ORA_BIDS_ARRAYSIZE	20

void clsDatabaseOracle::GetBids(MarketPlaceId marketplace,
								int item_id,
								BidVector *pBids, bool ended)
{
	int		user[ORA_BIDS_ARRAYSIZE];
	int		quantity[ORA_BIDS_ARRAYSIZE];
	float	amount[ORA_BIDS_ARRAYSIZE];
	float	value[ORA_BIDS_ARRAYSIZE];
	int		action[ORA_BIDS_ARRAYSIZE];
	char	created[ORA_BIDS_ARRAYSIZE][64];
	char	reason[ORA_BIDS_ARRAYSIZE][256];

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

	// temp check for ended - Lena

	if (ended)
		OpenAndParse(&mpCDAGetBidsEnded,
				 SQL_GetBidsEnded);
	else
		OpenAndParse(&mpCDAGetBids,
					 SQL_GetBids);

	// Bind the input variable
	Bind(":marketplace", (int *)&marketplace);
	Bind(":item_id", &item_id);

	// Bind those happy little output variables.
	Define(1, user);
	Define(2, action);
	Define(3, quantity);
	Define(4, amount);
	Define(5, value);
	Define(6, (char *)created, sizeof(created[0]));
	Define(7, (char *)reason, sizeof(reason[0]));

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		if (ended)
			Close(&mpCDAGetBidsEnded);
		else
			Close(&mpCDAGetBids);
		SetStatement(NULL);
		return;
	}

	// Fetch until we retch (should use array 
	// fetch here!)
	rowsFetched	= 0;
	do
	{
		assert(mpCDACurrent);
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_BIDS_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Check(rc);
			if (ended)
				Close(&mpCDAGetBidsEnded);
			else
				Close(&mpCDAGetBids);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_BIDS_ARRAYSIZE). 
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
								 (double) amount[i],
								 quantity[i],
								 (double) value[i],
								 pReason);

			pBids->push_back(pBid);

			if (reason[i] != NULL)
			{
				delete	[] pReason;
			}
		}
	} while (!CheckForNoRowsFound());

	if (ended)
		Close(&mpCDAGetBidsEnded);
	else
		Close(&mpCDAGetBids);
	SetStatement(NULL);

	return;		//lint !e429 Don't worry about pBid, we know pushBack will eat it
}


//
// GetHighestBidForUser
//

// This query is used to validate a user's bid,
// and therefore doesn't return any boring information
// like retractions or reasons
static char *SQL_GetHighBidForUser =
 "select	quantity,							\
			amount,								\
			value,								\
			type,								\
			TO_CHAR(created,					\
					'YYYY-MM-DD HH24:MI:SS')	\
  from ebay_bids								\
  where	marketplace = :marketplace				\
  and	item_id = :item_id						\
  and	user_id = :user_id						\
  and	type <> 0								\
  and	type <> 3								\
  and	type <> 4								\
  and	type <> 5								\
  and	type <> 6								\
  order by	value desc,							\
			created";

static char *SQL_GetHighBidForUserEnded =
 "select	quantity,							\
			amount,								\
			value,								\
			type,								\
			TO_CHAR(created,					\
					'YYYY-MM-DD HH24:MI:SS')	\
  from ebay_bids_ended							\
  where	marketplace = :marketplace				\
  and	item_id = :item_id						\
  and	user_id = :user_id						\
  and	type <> 0								\
  and	type <> 3								\
  and	type <> 4								\
  and	type <> 5								\
  and	type <> 6								\
  order by	value desc,							\
			created";

clsBid *clsDatabaseOracle::GetHighestBidForUser(MarketPlaceId marketplace,
												int item_id,
												int user_id, bool ended)
{
	int		rc;
	int		quantity;
	float	amount;
	float	value;
	int		type;
	char	created[32];

	time_t	createTime;

	clsBid	*pBid = NULL;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	if (ended)
		OpenAndParse(&mpCDAGetHighBidForUserEnded,
					 SQL_GetHighBidForUserEnded);
	else
		OpenAndParse(&mpCDAGetHighBidForUser,
					 SQL_GetHighBidForUser);

	// Bind the input variable
	Bind(":marketplace", (int *)&marketplace);
	Bind(":item_id", &item_id);
	Bind(":user_id", &user_id);

	// Bind those happy little output variables.
	Define(1, &quantity);
	Define(2, &amount);
	Define(3, &value);
	Define(4, &type);
	Define(5, (char *)created, sizeof(created));

	// Ok, let's do it. We do our own oexfet here cause
	// we "allow" more than 1 row to be returned.
	rc = oexfet((struct cda_def *)mpCDACurrent, 
					 1, 1, 0);

	if (!CheckForNoRowsFound())
	{
		Check(rc);
	}

	if (CheckForNoRowsFound())
	{
		if (ended)
			Close(&mpCDAGetHighBidForUserEnded);
		else
			Close(&mpCDAGetHighBidForUser);
		SetStatement(NULL);
		return NULL;
	}

	// Time conversions
	ORACLE_DATEToTime((char *)created, &createTime);

	pBid	= new clsBid(createTime,
						 (BidActionEnum)type,
						 user_id,
						 (double) amount,
						 quantity,
						 (double) value,
						 NULL);

	if (ended)
		Close(&mpCDAGetHighBidForUserEnded);
	else
		Close(&mpCDAGetHighBidForUser);
	SetStatement(NULL);

	return pBid;
}

//
// GetHighestBidsForItem
//
// *** NOTE ***
// Since we do our own fetches here, we do our own
// call to ocan when the query is done to make sure
// the cursor's all cleaned up
// *** NOTE ***
//

// This query is used to validate a user's bid,
// and therefore doesn't return any boring information
// like retractions or reasons
static char *SQL_GetHighBidsForItem =
 "select	user_id,							\
			quantity,							\
			amount,								\
			value,								\
			type,								\
			TO_CHAR(created,					\
					'YYYY-MM-DD HH24:MI:SS')	\
  from ebay_bids								\
  where	marketplace = :marketplace				\
  and	item_id = :item_id						\
  and	type <> 0								\
  and	type <> 3								\
  and	type <> 4								\
  and	type <> 5								\
  and	type <> 6								\
  order by	value desc,							\
			created asc";

static char *SQL_GetHighBidsForItemEnded =
 "select	user_id,							\
			quantity,							\
			amount,								\
			value,								\
			type,								\
			TO_CHAR(created,					\
					'YYYY-MM-DD HH24:MI:SS')	\
  from ebay_bids_ended							\
  where	marketplace = :marketplace				\
  and	item_id = :item_id						\
  and	type <> 0								\
  and	type <> 3								\
  and	type <> 4								\
  and	type <> 5								\
  and	type <> 6								\
  order by	value desc,							\
			created asc";

void clsDatabaseOracle::GetHighestBidsForItem(
									bool /* lock */,
									MarketPlaceId marketplace,
									int item_id,
									clsBid **pHighestBid,
									clsBid **pNextHighestBid, bool ended)
{
	int		rc;
	int		user;
	int		quantity;
	float	amount;
	float	value;
	int		type;
	char	created[32];

	time_t	createTime;

	*pHighestBid		= NULL;
	*pNextHighestBid	= NULL;
	

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	if (ended)
		OpenAndParse(&mpCDAGetHighBidsForItemEnded,
					 SQL_GetHighBidsForItemEnded);
	else
		OpenAndParse(&mpCDAGetHighBidsForItem,
					 SQL_GetHighBidsForItem);

	// Bind the input variable
	Bind(":marketplace", (int *)&marketplace);
	Bind(":item_id", &item_id);

	// Bind those happy little output variables.
	Define(1, &user);
	Define(2, &quantity);
	Define(3, &amount);
	Define(4, &value);
	Define(5, &type);
	Define(6, created, sizeof(created));

	// Ok, let's do it.
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		if (ended)
			Close(&mpCDAGetHighBidsForItemEnded);
		else
			Close(&mpCDAGetHighBidsForItem);
		SetStatement(NULL);
		return;
	}


	// Fetch the first row
	rc = ofetch((struct cda_def *)mpCDACurrent);

	assert(mpCDACurrent);
	// Check for end-of-fetch
	if (rc != 0 &&
		((struct cda_def *)mpCDACurrent)->rc == 1403)
	{
		ocan((struct cda_def *)mpCDACurrent);
		if (ended)
			Close(&mpCDAGetHighBidsForItemEnded);
		else
			Close(&mpCDAGetHighBidsForItem);
		SetStatement(NULL);
		return;
	}
	Check(rc);


	// Time conversions
	ORACLE_DATEToTime((char *)created, &createTime);

	*pHighestBid	= new clsBid(createTime,
								 (BidActionEnum)type,
								 user,
								 (double)amount,
								 quantity,
								 (double) value,
								 NULL);

	// Fetch the next one
	rc = ofetch((struct cda_def *)mpCDACurrent);

	assert(mpCDACurrent);
	// Check for end-of-fetch
	if (rc != 0 &&
		((struct cda_def *)mpCDACurrent)->rc == 1403)
	{
		ocan((struct cda_def *)mpCDACurrent);
		if (ended)
			Close(&mpCDAGetHighBidsForItemEnded);
		else
			Close(&mpCDAGetHighBidsForItem);
		SetStatement(NULL);
		return;
	}
	Check(rc);


	// Time conversions
	ORACLE_DATEToTime(created, &createTime);

	*pNextHighestBid	= new clsBid(createTime,
									 (BidActionEnum)type,
									 user,
									 (double) amount,
									 quantity,
									 (double) value,
									 NULL);

	ocan((struct cda_def *)mpCDACurrent);
	if (ended)
		Close(&mpCDAGetHighBidsForItemEnded);
	else
		Close(&mpCDAGetHighBidsForItem);
	SetStatement(NULL);

	return;
}

//
// gets the item_qty vector of valid bids
//
void clsDatabaseOracle::GetHighestBidsForItem(
									bool /* lock */,
									MarketPlaceId marketplace,
									int item_id,
									int item_qty,
									BidVector *pvHighBids, bool ended)
{
	int		rc;
	int		user;
	int		quantity;
	float	amount;
	float	value;
	int		type;
	char	created[32];
	clsBid  *pHighBid;
	int		rowsFetched;

	time_t	createTime;

	pHighBid		= NULL;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	if (ended)
		OpenAndParse(&mpCDAGetHighBidsForItemEnded,
					 SQL_GetHighBidsForItemEnded);
	else
		OpenAndParse(&mpCDAGetHighBidsForItem,
					 SQL_GetHighBidsForItem);

	// Bind the input variable
	Bind(":marketplace", (int *)&marketplace);
	Bind(":item_id", &item_id);

	// Bind those happy little output variables.
	Define(1, &user);
	Define(2, &quantity);
	Define(3, &amount);
	Define(4, &value);
	Define(5, &type);
	Define(6, created, sizeof(created));

	// Ok, let's do it.
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		if (ended)
			Close(&mpCDAGetHighBidsForItemEnded);
		else
			Close(&mpCDAGetHighBidsForItem);
		SetStatement(NULL);
		return;
	}

	// Fetch till we've all the high bidders for the quantity
	rowsFetched = 0;
	do
	{
		// Fetch the first row
		rc = ofetch((struct cda_def *)mpCDACurrent);

		assert(mpCDACurrent);
		// Check for end-of-fetch
		if (rc != 0 &&
			((struct cda_def *)mpCDACurrent)->rc == 1403)
		{
			ocan((struct cda_def *)mpCDACurrent);
			if (ended)
				Close(&mpCDAGetHighBidsForItemEnded);
			else
				Close(&mpCDAGetHighBidsForItem);
			SetStatement(NULL);
			return;
		}
		Check(rc);
		rowsFetched = rowsFetched + 1;
		// Time conversions
		ORACLE_DATEToTime(created, &createTime);

		pHighBid	= new clsBid(createTime,
								 (BidActionEnum)type,
								 user,
								 (double) amount,
								 quantity,
								 (double) value,
								 NULL);
		pvHighBids->push_back(pHighBid);

	} while (!CheckForNoRowsFound() && (rowsFetched <= item_qty));
	return;		//lint !e429 Don't worry about pHighBid
}


//
// GetBidsForItemSorted
//
void clsDatabaseOracle::GetBidsForItemSorted(
									MarketPlaceId marketplace,
									int item_id,
									BidVector *pvHighBids, bool ended)
{
	int		rc;
	int		user;
	int		quantity;
	float	amount;
	float	value;
	int		type;
	char	created[32];
	clsBid  *pHighBid;
	int		rowsFetched;

	time_t	createTime;

	pHighBid		= NULL;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	if (ended)
		OpenAndParse(&mpCDAGetHighBidsForItemEnded,
					 SQL_GetHighBidsForItemEnded);
	else
		OpenAndParse(&mpCDAGetHighBidsForItem,
				 SQL_GetHighBidsForItem);

	// Bind the input variable
	Bind(":marketplace", (int *)&marketplace);
	Bind(":item_id", &item_id);

	// Bind those happy little output variables.
	Define(1, &user);
	Define(2, &quantity);
	Define(3, &amount);
	Define(4, &value);
	Define(5, &type);
	Define(6, created, sizeof(created));

	// Ok, let's do it.
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		if (ended)
			Close(&mpCDAGetHighBidsForItemEnded);
		else
			Close(&mpCDAGetHighBidsForItem);
		SetStatement(NULL);
		return;
	}

	// Fetch till we've all the high bidders for the quantity
	rowsFetched = 0;
	do
	{
		// Fetch the first row
		rc = ofetch((struct cda_def *)mpCDACurrent);

		assert(mpCDACurrent);
		// Check for end-of-fetch
		if (rc != 0 &&
			((struct cda_def *)mpCDACurrent)->rc == 1403)
		{
			ocan((struct cda_def *)mpCDACurrent);
			if (ended)
				Close(&mpCDAGetHighBidsForItemEnded);
			else
				Close(&mpCDAGetHighBidsForItem);
			SetStatement(NULL);
			return;
		}
		Check(rc);
		rowsFetched = rowsFetched + 1;
		// Time conversions
		ORACLE_DATEToTime(created, &createTime);

		pHighBid	= new clsBid(createTime,
								 (BidActionEnum)type,
								 user,
								 (double) amount,
								 quantity,
								 (double) value,
								 NULL);
		pvHighBids->push_back(pHighBid);

	} while (!CheckForNoRowsFound());

	// Done!
	if (ended)
		Close(&mpCDAGetHighBidsForItemEnded);
	else
		Close(&mpCDAGetHighBidsForItem);
	SetStatement(NULL);
	return;	//lint !e429 Don't worry about pHighBid
}
//
// AddBid
//
static const char *SQL_AddBid =
	"insert into %s							\
	 (	marketplace,						\
		item_id,							\
		user_id,							\
		quantity,							\
		amount,								\
		value,								\
		type,								\
		created,							\
		reason,								\
		host								\
	 )										\
	 values									\
	 (	:marketplace,						\
		:item,								\
		:user_id,							\
		:quantity,							\
		:amount,							\
		:value,								\
		:action,							\
		TO_DATE(:when,						\
				'YYYY-MM-DD HH24:MI:SS'),	\
		:reason,							\
		:host								\
	)";

void clsDatabaseOracle::AddBid(MarketPlaceId marketplace,
							   int item,
							   clsBid *pBid,
							   bool blocked /* = false */)
{
	int					user_id;
	int					quantity;
	float				amount;
	float				value;
	int					type;
	char				*pReason;
	char				date[32];
	char                *pHost;
	clsEnvironment		*pEnv;

	char				cTableName[64];
	unsigned int		tableNameLen = 0;

	char *				pSQLStatement = NULL;
	unsigned char **	ppCursor = NULL;
	
	// decide if we're going to use the active or blocked bids
	if (blocked)
	{
		strcpy(cTableName, "ebay_bids_blocked");
		ppCursor = &mpCDAAddBid;
	}
	else
	{
		strcpy(cTableName, "ebay_bids");
		ppCursor = &mpCDAAddBlockedBid;
	}

	// Get table name length
	tableNameLen = strlen(cTableName);

	// format the SQL statement with the table name
	pSQLStatement = new char[strlen(SQL_AddBid) + tableNameLen + 1];
	sprintf(pSQLStatement, SQL_AddBid, cTableName);

	// Extract what we need
	user_id			= pBid->mUser;
	amount			= pBid->mAmount;
	quantity		= pBid->mQuantity;
	value			= pBid->mValue;
	type			= (int)pBid->mAction;
	pReason			= pBid->mReason;
	TimeToORACLE_DATE(pBid->mTime,
					  date);

	pEnv = gApp->GetEnvironment();
	if (pEnv)
		pHost = pEnv->GetRemoteAddr();
	else
		pHost = "none";

	// Get the cursor ready
	// The usual suspects
	OpenAndParse(ppCursor, pSQLStatement);

	// Bind
	Bind(":marketplace", (int *)&marketplace);
	Bind(":item", &item);
	Bind(":user_id", &user_id);
	Bind(":amount", &amount);
	Bind(":quantity", &quantity);
	Bind(":value", &value);
	Bind(":action", &type);
	Bind(":when", date);
	Bind(":reason", pReason);
	Bind(":host", pHost);

	// Do it!
	Execute();

	// Commit, and complete
	Commit();

	Close(ppCursor);
	SetStatement(NULL);

	delete [] pSQLStatement;

	// adjust the bid count for the marketplace
	if (!blocked)
		AdjustMarketPlaceBidCount(marketplace, 1);

	return;
}

//
// Retract all of a user's bids on an item
//
static const char *SQL_RetractBids =
" update ebay_bids							\
	set		type = :type					\
	where	marketplace = :marketplace		\
	and		item_id = :item					\
	and		user_id = :id";

void clsDatabaseOracle::RetractBids(MarketPlaceId marketplace,
									int item,
									int user,
									BidActionEnum type)
{
	// This statement isn't used too often, so 
	// we don't use a permanent cursor for it
	OpenAndParse(&mpCDAOneShot, SQL_RetractBids);

	// Bind
	Bind(":type", (int *)&type);
	Bind(":marketplace", (int *)&marketplace);
	Bind(":item", &item);
	Bind(":id", &user);

	// Do it
	Execute();
	Commit();

	// Done wid that
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

// checks if pRowId is a valid RowId format
bool clsDatabaseOracle::IsValidRowIdFormat(const char *pRowId)
{
	// it has to be valid pointer
	if (pRowId == NULL)
		return false;
	// rowid must be 18 chars long
	if (strlen(pRowId) != 18)
	{
		if (strlen(pRowId) > 3)
			EDEBUG('*', "Faulty rowid, wrong length: %s\n", pRowId);
		return false;
	}
	
	// has dots in the right places
	if (pRowId[8] != '.' ||
		pRowId[13] != '.')
	{
		EDEBUG('*', "Fault rowid, wrong dots: %s\n", pRowId);
		return false;
	}

	// valid rowid
	return true;
}

//
// GetSuspendedUsers
//
static char *SQL_GetSuspendedUsers = 
 "select id from ebay_users					\
	where user_state = 0";

void clsDatabaseOracle::GetSuspendedUsers(vector<int> *pVUsers)
{
	int			id;

	//lint --e(506,774) Lint hates these const value Booleans
	if (UserSuspended != 0)
	{
#ifdef _MSC_VER
		gApp->LogEvent("Value of UserSuspended was changed. "
			"Cannot get suspended users.\n");
#endif
		return;
	}
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetSuspendedUsers);

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
// CancelQuery
//
void clsDatabaseOracle::CancelQuery()
{
	return;
}

//
// AdjustMarketPlaceBidCount - note update bid count via delta
//
#ifdef NEEDED
static char *SQL_AdjustMarketPlaceBidCount = 
 "update ebay_marketplaces_info						\
	set bid_count = bid_count + :delta					\
	where id = :id";
#endif
void clsDatabaseOracle::AdjustMarketPlaceBidCount(
								int /* marketPlaceId */,
								int /* delta */
												   )
{
	// wired off - code to update bidcount should be in 
	// sql after dailyStats is run.
	// Open + Parse
//	OpenAndParse(&mpCDAOneShot, SQL_AdjustMarketPlaceBidCount);

	// Bind
//	Bind(":id", &marketPlaceId);
//	Bind(":delta", &delta);

	// Do it!
//	Execute();
//	Commit();

	// Close 
//	Close(&mpCDAOneShot);
//	SetStatement(NULL);

	return;
}


//
// Get bid count since inception
//
static const char *SQL_GetBidCountSinceInception =
 "select	sum(bidcount)						\
	from ebay_dailystatistics					\
	where	marketplace = :marketplaceid		\
	and		transaction_type = 0				\
	and		categoryid = 0";

int clsDatabaseOracle::GetBidCountSinceInception(MarketPlaceId marketplaceId)
{
		// Temporary slots for things to live in
	int					count	= 0;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)

	OpenAndParse(&mpCDAOneShot, 
		 SQL_GetBidCountSinceInception);

	// Bind the rest of the input variables
	Bind(":marketplaceid", (int *)&marketplaceId);
	
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

// GetAnnouncement 
// need to add last modified date
static const char *SQL_GetAnnouncementLen =
 "select	description_len						\
	from ebay_announce_new						\
	where	marketplace = :marketplace			\
	and		site_id = :site_id					\
	and		partner_id = :partner_id			\
	and		id = :announceid					\
	and		location = :locn";

static const char *SQL_GetAnnouncement =
 "select	code,								\
			TO_CHAR(last_modified,				\
			'YYYY-MM-DD HH24:MI:SS'),			\
			description							\
	from ebay_announce_new						\
	where	marketplace = :marketplace			\
	and		site_id = :site_id					\
	and		partner_id = :partner_id			\
	and		id = :announceid					\
	and		location = :locn";

clsAnnouncement *clsDatabaseOracle::GetAnnouncement(int marketplace,
						int announceid, int where, int partner_id /*=0*/, int site_id/*=0*/)
{
	int					description_len;
	unsigned char		*pDescription;
	clsAnnouncement		*pAnnouncement;
	char				mod_date[32];
	time_t				mod_date_time;
	char				codestr[20];

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	OpenAndParse(&mpCDAGetAnnouncementLen, SQL_GetAnnouncementLen);

	// Bind the input variable
	Bind(":marketplace", &marketplace);
	Bind(":announceid", &announceid);
	Bind(":locn", &where);
	Bind(":partner_id", &partner_id);
	Bind(":site_id", &site_id);

	// Define the output
	Define(1, &description_len);

	// Fetch
	ExecuteAndFetch();

	if (CheckForNoRowsFound() || (description_len == 0))
	{
		pAnnouncement = 0;
		Close(&mpCDAGetAnnouncementLen);
		SetStatement(NULL);
		return pAnnouncement;
	}

	// Now we allocate space for description based on length
	pDescription	= new unsigned char[description_len + 1];

	Close(&mpCDAGetAnnouncementLen);
	SetStatement(NULL);

	// get actual description
	OpenAndParse(&mpCDAGetAnnouncement,
				 SQL_GetAnnouncement);

	// Bind the input variable
	Bind(":announceid", &announceid);
	Bind(":marketplace", &marketplace);
	Bind(":locn", &where);
	Bind(":partner_id", &partner_id);
	Bind(":site_id", &site_id);

	// Define the output - this won't work either!
	Define(1, (char *)codestr, sizeof(codestr));
	Define(2, mod_date, sizeof(mod_date));
	DefineLongRaw(3, pDescription, description_len);

	// Get it!
	ExecuteAndFetch();

	*(pDescription + description_len)	= '\0';

	// Time Conversions
	ORACLE_DATEToTime(mod_date, &mod_date_time);

	// create and stuff into announcement object
	pAnnouncement = new clsAnnouncement(marketplace, announceid, where, 
		mod_date_time, codestr, (char *)pDescription, partner_id, site_id);

	delete [] pDescription;

	Close(&mpCDAGetAnnouncement);
	SetStatement(NULL);

	return pAnnouncement;
}


static const char *SQL_UpdateAnnouncement =
 "update ebay_announce_new					\
	set description_len = :desclen,			\
		last_modified = sysdate,			\
		description = :description			\
	where	marketplace = :marketplace			\
	and		site_id = :site_id					\
	and		partner_id = :partner_id			\
	and		id = :announceid					\
	and		location = :locn";

bool clsDatabaseOracle::UpdateAnnouncement(clsAnnouncement *pAnnounce)
{
	int				descLen;
	int				announceid;
	int				marketplace;
	int				locn;
	int				partner_id;
	int				site_id;
	char			*pDesc;

	pDesc = pAnnounce->GetDesc();
	if (pDesc)
		descLen	= strlen(pDesc);
	else
	{
		pDesc	= "";
		descLen	= 0;
	}
	
	announceid = pAnnounce->GetId();
	marketplace = pAnnounce->GetMarketPlaceId();
	locn = pAnnounce->GetLocation();
	partner_id = pAnnounce->GetPartnerId();
	site_id = pAnnounce->GetSiteId();
	
	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	OpenAndParse(&mpCDAOneShot, SQL_UpdateAnnouncement);

	// Bind the input variable
	Bind(":announceid", &announceid);
	Bind(":marketplace", &marketplace);
	Bind(":locn", &locn);
	Bind(":desclen", &descLen);
	Bind(":partner_id", &partner_id);
	Bind(":site_id", &site_id);
	BindLongRaw(":description", 
				(unsigned char *)pDesc,
				descLen);

	// Fetch
	Execute();

	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		AddAnnouncement(marketplace, announceid, locn, pAnnounce->GetCode(), pDesc, partner_id, site_id);
		return false;
	}

	// Commit
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return true;
}

static const char *SQL_AddAnnouncement =
 "insert into ebay_announce_new			\
	(	marketplace,				\
		site_id						\
		partner_id,					\
		id,							\
		location,					\
		code,						\
		last_modified,				\
		description_len,			\
		description,				\
	)								\
	values							\
	(	:marketplace,				\
		:site_id					\
		:partner_id,				\
		:id,						\
		:locn,						\
		:code,						\
		sysdate,					\
		:desclen,					\
		:description,				\
		)";

bool clsDatabaseOracle::AddAnnouncement(int marketplace,
			int announceid, int where, char *pCode, char *pDesc, int partner_id, int site_id)
{
	int				descLen;

	if (pDesc)
		descLen	= strlen(pDesc);
	else
	{
		pDesc	= "";
		descLen	= 0;
	}
	
	OpenAndParse(&mpCDAOneShot, SQL_AddAnnouncement);

	// Bind the input variable
	Bind(":marketplace", &marketplace);
	Bind(":id", &announceid);
	Bind(":locn", &where);
	Bind(":code", (char *)pCode);
	Bind(":desclen", &descLen);
	Bind(":partner_id", &partner_id);
	Bind(":site_id", &site_id);
	BindLongRaw(":description", 
				(unsigned char *)pDesc,
				descLen);

	// Fetch
	Execute();

	// Commit
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return true;
}

static const char *SQL_GetAnnouncementStats =
 "select	count(*),						\
			max(description_len)			\
  from ebay_announce_new					\
  where marketplace = :marketplace";

static const char *SQL_GetAllAnnouncementsBySiteAndPartner =
 "select	id,									\
			location,							\
			description_len,					\
			code,								\
			TO_CHAR(last_modified,				\
			'YYYY-MM-DD HH24:MI:SS'),			\
			description							\
	from ebay_announce_new						\
	where	marketplace = :marketplace and		\
			site_id = :site_id and				\
			partner_id = :partner_id";


void clsDatabaseOracle::GetAllAnnouncementsBySiteAndPartner(int marketplace, 
										   AnnouncementVector *pvAnnouncements,
										   int SiteId,
										   int PartnerId)
{

	int					i;
	clsAnnouncement		*pAnnounce;
	time_t				mod_date_time;

	int					moddateLen;
	char				*pModdates;
	int					*pIds;
	int					*pDescLens;
	int					*pLocs;
	char				*pDescs;
	char				*pCodes;
	char				*pString;
	char				*pCode;

	char				*pCurrentModdate;
	int					*pCurrentId;
	char				*pCurrentCode;
	int					*pCurrentLoc;
	int					*pCurrentDescLen;
	char				*pCurrentDesc;
	sb2					*pDescInds;
//	sb2					*pCurrentDescInd;
	int					maxAnnounceLen;
	int					count;
	int					rc;

	OpenAndParse(&mpCDAOneShot, SQL_GetAnnouncementStats);
	// Bind the input variable
	Bind(":marketplace", &marketplace);

	// Define the output
	Define(1, &count);
	Define(2, &maxAnnounceLen);

	// Fetch
	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);
	if (count == 0)
	{
		return;
	}
	
	// Gross of the gross
	moddateLen		= strlen("YYYY-MM-DD HH:MM:SS") + 1;
	pModdates		= new char[moddateLen * count];
	pIds			= new int[count];
	pLocs			= new int[count];
	pDescLens		= new int[count];
	pCodes			= new char[(20 + 1) * count];
	pDescs			= new char[(maxAnnounceLen + 1) * count];
	pDescInds		= new sb2[count];	

	memset(pModdates, 0x00, moddateLen * count);
	memset(pDescs, 0x00, (maxAnnounceLen + 1) * count);

	// get actual descriptions
	OpenAndParse(&mpCDAAllAnnouncementsBySiteAndPartner, SQL_GetAllAnnouncementsBySiteAndPartner);

	// Bind the input variable
	Bind(":marketplace", &marketplace);
	Bind(":site_id", &SiteId);
	Bind(":partner_id", &PartnerId);

	// Define the output
	Define(1, pIds);
	Define(2, pLocs);
	Define(3, pDescLens);
	Define(4, pCodes, 20 + 1);
	Define(5, pModdates, moddateLen);
	DefineLongRaw(6, (unsigned char *)pDescs, maxAnnounceLen + 1, pDescInds);

	Execute();
	rc = ofen((struct cda_def *)mpCDACurrent, count);

	Check(rc);

	assert(mpCDACurrent);
	if (((struct cda_def *)mpCDACurrent)->rpc < (unsigned) count)
		count = ((struct cda_def *)mpCDACurrent)->rpc;
	
	pvAnnouncements->reserve(count);

	for (i = 0,
		 pCurrentModdate	= pModdates,
		 pCurrentId			= pIds,
		 pCurrentCode		= pCodes,
		 pCurrentLoc		= pLocs,
		 pCurrentDescLen	= pDescLens,
//		 pCurrentDescInd	= pDescInds,
		 pCurrentDesc		= pDescs;
		 i < count;
		 i++,
		 pCurrentModdate	+= moddateLen,
		 pCurrentId++,
		 pCurrentCode		+= (20 + 1),
		 pCurrentLoc++,
		 pCurrentDescLen++,
		 pCurrentDesc		+= maxAnnounceLen + 1)
	{

		ORACLE_DATEToTime(pCurrentModdate, &mod_date_time);

		pString	= new char[(*pCurrentDescLen) + 1];
		strcpy(pString, pCurrentDesc);

		pCode	= new char[strlen(pCurrentCode) + 1];
		strcpy(pCode, pCurrentCode);

		pAnnounce	= new clsAnnouncement(marketplace, *pCurrentId, *pCurrentLoc, 
			mod_date_time, (char *)pCode, (char *)pString,
			PartnerId, SiteId);

		pvAnnouncements->push_back(pAnnounce);

	}

	Close(&mpCDAAllAnnouncementsBySiteAndPartner);
	SetStatement(NULL);

	// Clean
	delete[]	pModdates;
	delete[]	pIds;
	delete[]	pLocs;
	delete[]	pDescLens;
	delete[]	pCodes;
	delete[]	pDescs;
	delete[]	pDescInds;

	return;		//lint !e429 Don't worry about pAnnounce

}

static const char *SQL_GetAllAnnouncements =
 "select	site_id,							\
			partner_id,							\
			id,									\
			location,							\
			description_len,					\
			code,								\
			TO_CHAR(last_modified,				\
			'YYYY-MM-DD HH24:MI:SS'),			\
			description							\
	from ebay_announce_new						\
	where	marketplace = :marketplace";


void clsDatabaseOracle::GetAllAnnouncements(int marketplace, 
										   AnnouncementVector *pvAnnouncements)
{

	int					i;
	clsAnnouncement		*pAnnounce;
	time_t				mod_date_time;

	int					moddateLen;
	char				*pModdates;
	int					*pSiteIds;
	int					*pPartnerIds;
	int					*pIds;
	int					*pDescLens;
	int					*pLocs;
	char				*pDescs;
	char				*pCodes;
	char				*pString;
	char				*pCode;

	char				*pCurrentModdate;
	int					*pCurrentSiteId;
	int					*pCurrentPartnerId;
	int					*pCurrentId;
	char				*pCurrentCode;
	int					*pCurrentLoc;
	int					*pCurrentDescLen;
	char				*pCurrentDesc;
	sb2					*pDescInds;
//	sb2					*pCurrentDescInd;
	int					maxAnnounceLen;
	int					count;
	int					rc;

	OpenAndParse(&mpCDAOneShot, SQL_GetAnnouncementStats);
	// Bind the input variable
	Bind(":marketplace", &marketplace);

	// Define the output
	Define(1, &count);
	Define(2, &maxAnnounceLen);

	// Fetch
	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);
	if (count == 0)
	{
		return;
	}
	
	// Gross of the gross
	moddateLen		= strlen("YYYY-MM-DD HH:MM:SS") + 1;
	pModdates		= new char[moddateLen * count];
	pSiteIds		= new int[count];
	pPartnerIds		= new int[count];
	pIds			= new int[count];
	pLocs			= new int[count];
	pDescLens		= new int[count];
	pCodes			= new char[(20 + 1) * count];
	pDescs			= new char[(maxAnnounceLen + 1) * count];
	pDescInds		= new sb2[count];	

	memset(pModdates, 0x00, moddateLen * count);
	memset(pDescs, 0x00, (maxAnnounceLen + 1) * count);

	// get actual descriptions
	OpenAndParse(&mpCDAAllAnnouncements, SQL_GetAllAnnouncements);

	// Bind the input variable
	Bind(":marketplace", &marketplace);

	// Define the output
	Define(1, pSiteIds);
	Define(2, pPartnerIds);
	Define(3, pIds);
	Define(4, pLocs);
	Define(5, pDescLens);
	Define(6, pCodes, 20 + 1);
	Define(7, pModdates, moddateLen);
	DefineLongRaw(8, (unsigned char *)pDescs, maxAnnounceLen + 1, pDescInds);

	Execute();
	rc = ofen((struct cda_def *)mpCDACurrent, count);

	Check(rc);

	assert(mpCDACurrent);
	if (((struct cda_def *)mpCDACurrent)->rpc < (unsigned) count)
		count = ((struct cda_def *)mpCDACurrent)->rpc;
	
	pvAnnouncements->reserve(count);

	for (i = 0,
		 pCurrentModdate	= pModdates,
		 pCurrentSiteId		= pSiteIds,
		 pCurrentPartnerId	= pPartnerIds,
		 pCurrentId			= pIds,
		 pCurrentCode		= pCodes,
		 pCurrentLoc		= pLocs,
		 pCurrentDescLen	= pDescLens,
//		 pCurrentDescInd	= pDescInds,
		 pCurrentDesc		= pDescs;
		 i < count;
		 i++,
		 pCurrentModdate	+= moddateLen,
		 pCurrentSiteId++,
		 pCurrentPartnerId++,
		 pCurrentId++,
		 pCurrentCode		+= (20 + 1),
		 pCurrentLoc++,
		 pCurrentDescLen++,
		 pCurrentDesc		+= maxAnnounceLen + 1)
	{

		ORACLE_DATEToTime(pCurrentModdate, &mod_date_time);

		pString	= new char[(*pCurrentDescLen) + 1];
		strcpy(pString, pCurrentDesc);

		pCode	= new char[strlen(pCurrentCode) + 1];
		strcpy(pCode, pCurrentCode);

		pAnnounce	= new clsAnnouncement(marketplace, *pCurrentId, *pCurrentLoc, 
			mod_date_time, (char *)pCode, (char *)pString,
			*pCurrentPartnerId, *pCurrentSiteId);

		pvAnnouncements->push_back(pAnnounce);

	}

	Close(&mpCDAAllAnnouncements);
	SetStatement(NULL);

	// Clean
	delete[]	pModdates;
	delete[]	pSiteIds;
	delete[]	pPartnerIds;
	delete[]	pIds;
	delete[]	pLocs;
	delete[]	pDescLens;
	delete[]	pCodes;
	delete[]	pDescs;
	delete[]	pDescInds;

	return;		//lint !e429 Don't worry about pAnnounce

}


static const char *SQL_GetUserCodeAll =
	"select question_id, \
	question_code,\
	order_no,	  \
	type_code,     \
	question	  \
	from ebay_user_code3 where order_no < 600 order by order_no";						
 
	
#define ORA_USERCODE_ARRAYSIZE		200

void clsDatabaseOracle::GetUserCodeVector(UserCodeVector *pvUserCodeVector)
{
	// Temporary slots for things to live in
	int				questionID[ORA_USERCODE_ARRAYSIZE];
	int				questionCode[ORA_USERCODE_ARRAYSIZE];
	int				orderNumber[ORA_USERCODE_ARRAYSIZE];
	int				typeCode[ORA_USERCODE_ARRAYSIZE];
	char			question[ORA_USERCODE_ARRAYSIZE][256];
	char			*pQuestion;	
	int				rowsFetched;
	int				rc;
	int				i,n;
	clsUserCode		*pUserCode;	

	OpenAndParse(&mpCDAOneShot, SQL_GetUserCodeAll);

	// Define
	Define(1, questionID);
	Define(2, questionCode);
	Define(3, orderNumber);
	Define(4, typeCode);
	Define(5, (char *)question, sizeof(question[0]));
	
	// Execute...
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
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_USERCODE_ARRAYSIZE);

		assert(mpCDACurrent);
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
			// and location for the category

			// Build a nice object for our caller.

			pQuestion 
				= new char[strlen(question[i]) + 1];
			strcpy(pQuestion, question[i]);

			pUserCode   = new clsUserCode(questionID[i],
							questionCode[i],
							orderNumber[i],
							typeCode[i],
							pQuestion
							);		

			pvUserCodeVector->push_back(pUserCode);
			}

	} while (!CheckForNoRowsFound());

	// clean up
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;		//lint !e429 Don't worry about pUserCode.
}

static const char *SQL_AddLinkButton =
 "insert into ebay_reciprocal_links	\
	(	userid,						\
		pictureid,					\
		userurl,					\
		request_date				\
	)								\
	values							\
	(	:userid,					\
		:pictureid,					\
		:userurl,					\
		sysdate						\
	)";

int clsDatabaseOracle::AddLinkButton(clsUser *pUser, RecipLinkEnum pWhichPic, const char *pUrls)
{
	int					userid;
	int					pictureid;
	const char				*userurl;

	// Extract what we need
	userid		= pUser->GetId();
	pictureid	= pWhichPic;
	userurl			= pUrls;

	// Get the cursor ready
	// The usual suspects
	OpenAndParse(&mpCDAAddLinkButton, SQL_AddLinkButton);

	// Bind
	Bind(":userid", &userid);
	Bind(":pictureid", &pictureid);
	Bind(":userurl", userurl);

	// Do it!
	Execute();

	// Commit, and complete
	Commit();

	Close(&mpCDAAddLinkButton);
	SetStatement(NULL);

	return 0;
}
static const char *SQL_AddRawSummaryReportData =
"	insert into ebay_summary_report	"
"	(	effective_date, "
"	category_name,  "
"	category_id, "
"	AllCount, "
"	RCount, "
"	RSoldCount, "	
"	RNotCount, "
"	NRCSoldCount, "	
"	NRCNotCount, "
"	DSoldCount, "	
"	DNotCount, "
"	AllSoldCount, "	
"	SumSoldPrice, "	
"	RSoldPrice, "	
"	NRCSoldPrice, "		
"	DSoldPrice, "
"	SumBoldFees, "	
"	SumFeatFees, "	
"	SumSuperFeatFees, "		
"	SumListFees, "	
"	SumFVFees, "
"   SumGallFees, "
"   SumFeatGalFees, "
"   SumGiftIconFees,  "
"	RSoldFees, "
"	RNotSoldFees, "		
"	NRCSoldFees, "	
"	NRCNotSoldFees, "	
"	DSoldFees, "	
"	DNotSoldFees, "		
"	SumFees	 "
"	) "		
"	values "
"	(TO_DATE(:fromdate,'YYYY-MM-DD HH24:MI:SS'), "
"	:category_name, "
"	:category_id,  "
"	:allCount, "
"	:rCount, "
"	:rSoldCount, "	
"	:rNotCount, "
"	:nRCSoldCount, "	
"	:nRCNotCount, "
"	:dSoldCount, "	
"	:dNotCount, "
"	:allSoldCount, "	
"	:sumSoldPrice, "	
"	:rSoldPrice, "	
"	:nRCSoldPrice, "		
"	:dSoldPrice, "
"	:sumBoldFees, "	
"	:sumFeatFees, "	
"	:sumSuperFeatFees, "		
"	:sumListFees, "	
"	:sumFVFees, "
"   :sumGalleryFee, "
"   :sumFeatureGalleryFee, "
"   :sumGiftIconFee, "
"	:rSoldFees, "
"	:rNotSoldFees, "		
"	:nRCSoldFees, "	
"	:nRCNotSoldFees, "	
"	:dSoldFees, "	
"	:dNotSoldFees, "		
"	:sumFees	 "				
"	)";

/** inna add raw data to summary report table */
void clsDatabaseOracle::AddRawSummaryReportData(char *pCatName,
							int allItem, 
							int rItem,
							int rItemSold,
							int rItemNot,
							int nRItemSold,
							int nRItemNot,
							int dItemSold,
							int dItemNot,
							int allItemSold,
							float sumAllItemSoldPrice,
							float sumRItemSoldPrice,
							float sumNRItemSoldPrice,
							float sumDItemSoldPrice,
							float sumBoldFee,
							float sumFeaturedFee,
							float sumSuperFeaturedFee,
							float sumListFee,
							float sumFVFee,
							float sumGalleryFee,
							float sumFeatureGalleryFee,
							float sumGiftIconFee,
							float rItemSoldFees,
							float rItemNotFees,
							float nRItemSoldFees,
							float nRItemNotFees,
							float dItemSoldFees,
							float dItemNotFees,
							float allItemFees,
							char * fromdate,
							int	category_id)
{
	char		cFromDate[64];

	OpenAndParse(&mpCDAOneShot, SQL_AddRawSummaryReportData);
	
	strcpy(cFromDate, fromdate);
	
	// Bind
	Bind(":fromdate", (char *)&cFromDate);
	Bind(":category_name",pCatName);
	Bind(":allCount",&allItem);
	Bind(":rCount", &rItem);
	Bind(":rSoldCount", &rItemSold);	
	Bind(":rNotCount", &rItemNot);
	Bind(":nRCSoldCount", &nRItemSold);
	Bind(":nRCNotCount", &nRItemNot);
	Bind(":dSoldCount", &dItemSold);	
	Bind(":dNotCount", &dItemNot);
	Bind(":allSoldCount", &allItemSold);	
	Bind(":sumSoldPrice", &sumAllItemSoldPrice);	
	Bind(":rSoldPrice", &sumRItemSoldPrice);
	Bind(":nRCSoldPrice", &sumNRItemSoldPrice);		
	Bind(":dSoldPrice", &sumDItemSoldPrice);
	Bind(":sumBoldFees", &sumBoldFee);
	Bind(":sumFeatFees", &sumFeaturedFee);	
	Bind(":sumSuperFeatFees", &sumSuperFeaturedFee);	
	Bind(":sumListFees", &sumListFee);
	Bind(":sumFVFees", &sumFVFee);
	Bind(":sumGalleryFee", &sumGalleryFee);
	Bind(":sumFeatureGalleryFee", &sumFeatureGalleryFee);
	Bind(":sumGiftIconFee", &sumGiftIconFee);
	Bind(":rSoldFees", &rItemSoldFees);
	Bind(":rNotSoldFees", &rItemNotFees);	
	Bind(":nRCSoldFees", &nRItemSoldFees);
	Bind(":nRCNotSoldFees", &nRItemNotFees);	
	Bind(":dSoldFees",	&dItemSoldFees);
	Bind(":dNotSoldFees", &dItemNotFees);		
	Bind(":sumFees", &allItemFees);
	Bind(":category_id", &category_id);

	// Do it!
	Execute();

	// Commit, and complete
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return ;
}
