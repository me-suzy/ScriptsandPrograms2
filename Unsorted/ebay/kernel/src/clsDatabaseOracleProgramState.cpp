/*	$Id: clsDatabaseOracleProgramState.cpp,v 1.1.2.1 1999/07/30 02:42:32 sliang Exp $	*/
//
//	File:		clsDatabaseOracleProgramState.cpp
//
// Class:	clsDatabaseOracle
//
//	Author:	Sonya Liang  (sliang@ebay.com)
//
//	Function: funtionality of read and write to ebay_inv_and_balaging_state table
//
// Modifications:
//				- 07/21/99 Sonya	- Created



#include "eBayKernel.h"
#include "clsInvAndBalAgingState.h"

#include <string.h>
#include <stdio.h>
#include <fcntl.h>
#include <errno.h>
#include <time.h>
#include "clsEnvironment.h"


/*** Sonya - needed by invoice and balance aging State functionality ****/

/*static const char *SQL_GetNextEOAStateTime =
"select to_char(max(end_time),'YYYY-MM-DD HH24:MI:SS') from ebay_EOA_State";	

static const char *SQL_GetNextEOAStateId =
 "select ebay_EAO_sequence.nextval from dual";*/

static const char *SQL_CreateInvAndBalAgingState =
"insert into ebay_inv_and_balaging_state ( "
" Invoice_time, "
" Start_Id, "
" End_Id, "
" Pid, "
" Program_Type, "
" Process_Count, "
" Start_time) "
" values (to_date(:InvoiceTime,'YYYY-MM-DD HH24:MI:SS'), "
" :StartId, "
" :EndId, "
" :pid, "
" :ProgramType, "
" :ProcessCount, "
" to_date(:StartTime,'YYYY-MM-DD HH24:MI:SS'))";	


//This method is called when the instance is the first one run on the batch
//It must have the six attributes to insert to the state table. 
// The start time is obtained right in this method.
bool clsDatabaseOracle::CreateInvAndBalAgingStateInfo(clsInvAndBalAgingState *pInvAndBalAgingState)
{	
	int rc;
	int nProgramType;
	int nProcessCount;
	int nStartId;
	int nEndId;
	struct tm*	pDateAsTm;
	time_t longTime;
	char	cStartTime[32];
	char	cInvoiceTime[32];
	char	pid[11];
	
	
	longTime=	pInvAndBalAgingState->GetInvoiceTime();
	pDateAsTm	= localtime(&longTime);
	TM_STRUCTToORACLE_DATE(pDateAsTm,cInvoiceTime);

	nStartId=	pInvAndBalAgingState->GetStartId();
	nEndId=		pInvAndBalAgingState->GetEndId();

	strcpy(pid, pInvAndBalAgingState->GetpPid());
	
	nProgramType=pInvAndBalAgingState->GetProgramType();
	nProcessCount=pInvAndBalAgingState->GetProcessCount();

	longTime=pInvAndBalAgingState->GetStartTime();
	pDateAsTm	= localtime(&longTime);
	TM_STRUCTToORACLE_DATE(pDateAsTm,cStartTime);

	OpenAndParse(&mpCDAOneShot, SQL_CreateInvAndBalAgingState);

	//binds
	Bind(":InvoiceTime", (char *)cInvoiceTime, sizeof(cInvoiceTime));
	Bind(":StartId", &nStartId);
	Bind(":EndId", &nEndId);
	Bind(":pid", (char *)pid);
	Bind(":ProgramType", &nProgramType);
	Bind(":ProcessCount", &nProcessCount);
	Bind(":StartTime", (char *)cStartTime, sizeof(cStartTime));

	rc	=oexec((struct cda_def *)mpCDACurrent);

	if(((struct cda_def *)mpCDACurrent)->rc==1)
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return false;
	}
	
	//Otherwise, the usual
	Check(rc); 
	Commit();
	//Clean!
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return true;
}


static const char *SQL_GetInvAndBalAgingStateInfo =
 "select	pid,	\
			process_count	\
			from ebay_inv_and_balaging_State	\
	where invoice_time = to_date(:InvoiceTime,'YYYY-MM-DD HH24:MI:SS')	\
			and start_id = :StartId	\
			and end_id = :EndId	\
			and program_type = :ProgramType";

//Given a instantce state, we search the table to see if a buddy process 
// has already record this state (i.e. is there a record with the same invoice time, start id, end id and 
// program type?), return false if there is no such record. return true if
// the record found. also the process count attricbutes will updated to the value in the  table.

bool clsDatabaseOracle::GetInvAndBalAgingStateInfo(clsInvAndBalAgingState *pInvAndBalAgingState)
{	
	char	pid[11];
	char	cInvoiceTime[32];
	int		nProgramType;
	int		nStartId;
	int		nEndId;
	int		nProcessCount;
	time_t	InvoiceTime;
	struct tm	*pDateAsTm;	
	
	InvoiceTime=pInvAndBalAgingState->GetInvoiceTime();
	//convert dates to Oracle format
	pDateAsTm	= localtime(&InvoiceTime);
	TM_STRUCTToORACLE_DATE(pDateAsTm, cInvoiceTime);

	nStartId=pInvAndBalAgingState->GetStartId();
	nEndId=pInvAndBalAgingState->GetEndId();
	nProgramType=pInvAndBalAgingState->GetProgramType();


	OpenAndParse(&mpCDAOneShot, SQL_GetInvAndBalAgingStateInfo);

	Bind(":InvoiceTime", (char *)cInvoiceTime, sizeof(cInvoiceTime));
	Bind(":StartId", &nStartId);
	Bind(":EndId", &nEndId);
	Bind(":ProgramType", &nProgramType);

	Define(1,(char *)pid, sizeof(pid));
	Define(2,(int*) &nProcessCount);
	ExecuteAndFetch();

	//if no item found, then return
	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return false;
	}

	pInvAndBalAgingState->SetpPid(pid);
	pInvAndBalAgingState->SetProcessCount(nProcessCount);

	Close (&mpCDAOneShot);
	SetStatement(NULL);
	return true;

}


static const char *SQL_UpdateInvAndBalAgingStateInfo =
" update  ebay_inv_and_balaging_State "
" set pid = :pid, "
" start_time = to_date(:StartTime, 'YYYY-MM-DD HH24:MI:SS'), " 
" process_count = :ProcessCount where invoice_time = to_date(:InvoiceTime,'YYYY-MM-DD HH24:MI:SS') "
" and start_id = :StartId"
" and end_id= :EndId"
" and program_type= :ProgramType";	

//This method update the existing record with the new pid, new starttime and the increamented processcount

bool clsDatabaseOracle::UpdateInvAndBalAgingStateInfo(clsInvAndBalAgingState *pInvAndBalAgingState)
{
	int		rc;
	int		nProgramType;
	int		nStartId;
	int		nEndId;
	int		nProcessCount;
	char	cInvoiceTime[32];
	char	cStartTime[32];
	char	pid[11];
	time_t	longTime;
	struct tm	*pDateAsTm;

	longTime = pInvAndBalAgingState->GetInvoiceTime();
	pDateAsTm	= localtime(&longTime);
	TM_STRUCTToORACLE_DATE(pDateAsTm, cInvoiceTime);
	
	nStartId=	pInvAndBalAgingState->GetStartId();
	nEndId=		pInvAndBalAgingState->GetEndId();
	
	strcpy(pid, pInvAndBalAgingState->GetpPid());

	nProgramType=pInvAndBalAgingState->GetProgramType();
	nProcessCount=pInvAndBalAgingState->GetProcessCount();

	longTime=	pInvAndBalAgingState->GetStartTime();
	pDateAsTm	= localtime(&longTime);
	TM_STRUCTToORACLE_DATE(pDateAsTm, cStartTime);	

	
	//open and parse
	OpenAndParse(&mpCDAOneShot, SQL_UpdateInvAndBalAgingStateInfo);

	//binds
	Bind(":InvoiceTime", (char *)cInvoiceTime, sizeof(cInvoiceTime));
	Bind(":StartId", &nStartId);
	Bind(":EndId", &nEndId);
	Bind(":pid", (char *)pid, sizeof(pid));
	Bind(":ProgramType", &nProgramType);
	Bind(":ProcessCount", &nProcessCount);
	Bind(":StartTime", (char *)cStartTime, sizeof(cStartTime));

	rc	=oexec((struct cda_def *)mpCDACurrent);

	if(((struct cda_def *)mpCDACurrent)->rc==1)
	{//atually this won't happen, since the update is not limited by constrain,
		//you can update many time you can using the same values
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return false;
	}
	
	//Otherwise, the usual
	Check(rc); 
	Commit();
	//Clean!
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return true;
}

static const char *SQL_UpdateInvAndBalAgingPidAndEndTime =
"update  ebay_inv_and_balaging_State "
" set pid = :pid, "
" end_time = to_date(:EndTime,'YYYY-MM-DD HH24:MI:SS') where invoice_time = to_date(:InvoiceTime,'YYYY-MM-DD HH24:MI:SS') "
" and start_id = :StartId"
" and end_id= :EndId"
" and program_type= :ProgramType"
" and process_count= :ProcessCount";	

//This method mark the pid to be "Yes" and set the endtime of the instance in the record.
void  clsDatabaseOracle::MakeInstanceComplete(clsInvAndBalAgingState *pInvAndBalAgingState)
{
	int		rc;
	int		nProgramType;
	int		nStartId;
	int		nEndId;
	int		nProcessCount;
	char	cInvoiceTime[32];
	char	cEndTime[32];
	char	pid[11];
	time_t	longTime;
	struct tm	*pDateAsTm;

	longTime = pInvAndBalAgingState->GetInvoiceTime();
	pDateAsTm	= localtime(&longTime);
	TM_STRUCTToORACLE_DATE(pDateAsTm, cInvoiceTime);
	
	nStartId=	pInvAndBalAgingState->GetStartId();
	nEndId=		pInvAndBalAgingState->GetEndId();
	
	strcpy(pid, "YES");

	nProgramType=pInvAndBalAgingState->GetProgramType();
	nProcessCount=pInvAndBalAgingState->GetProcessCount();

	longTime=pInvAndBalAgingState->GetEndTime();	
	pDateAsTm	= localtime(&longTime);
	TM_STRUCTToORACLE_DATE(pDateAsTm, cEndTime);	

	//open and parse
	OpenAndParse(&mpCDAOneShot, SQL_UpdateInvAndBalAgingPidAndEndTime);

	//binds
	Bind(":InvoiceTime", (char *)cInvoiceTime, sizeof(cInvoiceTime));
	Bind(":StartId", &nStartId);
	Bind(":EndId", &nEndId);
	Bind(":pid", (char *)pid);
	Bind(":ProgramType", &nProgramType);
	Bind(":ProcessCount", &nProcessCount);
	Bind(":EndTime", (char *)cEndTime, sizeof(cEndTime));

	rc	=oexec((struct cda_def *)mpCDACurrent);

	Check(rc); 
	Commit();
	//Clean!
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}

static const char *SQL_CountStartIdOverlap =
 "select	count(*)	\
			from ebay_inv_and_balaging_State	\
	where invoice_time = to_date(:InvoiceTime,'YYYY-MM-DD HH24:MI:SS')	\
			and start_id <= :StartId	\
			and end_id > :StartId	\
			and program_type = :ProgramType";

static const char *SQL_CountEndIdOverlap =
 "select	count(*)	\
			from ebay_inv_and_balaging_State	\
	where invoice_time = to_date(:InvoiceTime,'YYYY-MM-DD HH24:MI:SS')	\
			and start_id < :EndId	\
			and end_id >= :EndId	\
			and program_type = :ProgramType";

static const char *SQL_GetLargerRangeOverlapSamllerRange =
 "select	pid	\
			from ebay_inv_and_balaging_State	\
	where invoice_time = to_date(:InvoiceTime,'YYYY-MM-DD HH24:MI:SS')	\
			and start_id >= :StartId	\
			and end_id < :EndId	\
			and program_type = :ProgramType";


#define NUMBER_TESTING_PID	60

//This method look into the state table to see if there is a range overlop for the instance
bool clsDatabaseOracle::IsRangeOverlap(clsInvAndBalAgingState *pInvAndBalAgingState)
{
	int		rc;
	int		nProgramType;
	int		nStartId;
	int		nEndId;
	int		count;
	int		rowsFetched,n,i;
	struct	tm*	pDateAsTm;
	time_t	longTime;
	char	cInvoiceTime[32];
	char	cPid[NUMBER_TESTING_PID][11];
	bool	overlap=false;

	longTime=	pInvAndBalAgingState->GetInvoiceTime();
	pDateAsTm	= localtime(&longTime);
	TM_STRUCTToORACLE_DATE(pDateAsTm,cInvoiceTime);

	nStartId=	pInvAndBalAgingState->GetStartId();
	nEndId=		pInvAndBalAgingState->GetEndId();
	
	nProgramType=pInvAndBalAgingState->GetProgramType();
	

	OpenAndParse(&mpCDAOneShot, SQL_CountStartIdOverlap);

	//binds
	Bind(":InvoiceTime", (char *)cInvoiceTime, sizeof(cInvoiceTime));
	Bind(":StartId", (int*) &nStartId);
	Bind(":ProgramType", (int*) &nProgramType);
	Define(1, (int*) &count);

	ExecuteAndFetch();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	if(count!=(pInvAndBalAgingState->GetRangeOverlapCount()))
		return true; //Range is overlapped with record in state table

	OpenAndParse(&mpCDAOneShot, SQL_CountEndIdOverlap);

	//binds
	Bind(":InvoiceTime", (char *)cInvoiceTime, sizeof(cInvoiceTime));
	Bind(":EndId", &nEndId);
	Bind(":ProgramType", &nProgramType);
	Define(1, &count);

	ExecuteAndFetch();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	if(count!=(pInvAndBalAgingState->GetRangeOverlapCount()))
		return true;

	//if we are here, we have to check  one more thing: is the new range cover 
	// the ranges in the table? If yes, we need to be sure the covered ranges have no 
	// process running on them when our large range is going to be processed.
	
	memset(cPid, '\0',(11*NUMBER_TESTING_PID));
	OpenAndParse(&mpCDAOneShot, SQL_GetLargerRangeOverlapSamllerRange);

	//binds
	Bind(":InvoiceTime", (char *)cInvoiceTime, sizeof(cInvoiceTime));
	Bind(":StartId", (int*) &nStartId);
	Bind(":EndId", (int *) &nEndId);
	Bind(":ProgramType", (int*) &nProgramType);

	Define(1, cPid[0], sizeof(cPid[0]));

	// Let's do the SQL
	Execute();

	if (CheckForNoRowsFound ())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAOneShot,true);
		SetStatement(NULL);
		return false; //if no record found, there is no overlap
	}

	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, NUMBER_TESTING_PID);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot,true);
			SetStatement(NULL);
			return true; //if something run, we can not go further, so we return true as if overlapped
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			if((strcmp(cPid[i], "YES")!=0)&&(strcmp(cPid[i],"0")!=0))
			{		
				overlap=true;
				break;	//Once we found one pid is neither "Yes" nor "0", it's overlap
			}
		}
	} while (!CheckForNoRowsFound());
	
	Close (&mpCDAOneShot);
	SetStatement(NULL);

	if(overlap)
		return true;

	return false;	
}


static const char *SQL_DeleteInvAndBalAgingStateInfo =
 "delete from ebay_inv_and_balaging_State	\
	where invoice_time = to_date(:InvoiceTime,'YYYY-MM-DD HH24:MI:SS')	\
			and start_id = :StartId	\
			and end_id = :EndId	\
			and program_type = :ProgramType";
// This method delete the record from the table, it is used when an over lap is found.
void clsDatabaseOracle::CleanUpOverlappedRecord(clsInvAndBalAgingState *pInvAndBalAgingState)
{	
	int rc;
	char	cInvoiceTime[32];
	int		nProgramType;
	int		nStartId;
	int		nEndId;
	time_t	InvoiceTime;
	struct tm	*pDateAsTm;	
	
	InvoiceTime=pInvAndBalAgingState->GetInvoiceTime();
	//convert dates to Oracle format
	pDateAsTm	= localtime(&InvoiceTime);
	TM_STRUCTToORACLE_DATE(pDateAsTm, cInvoiceTime);

	nStartId=pInvAndBalAgingState->GetStartId();
	nEndId=pInvAndBalAgingState->GetEndId();
	nProgramType=pInvAndBalAgingState->GetProgramType();

	OpenAndParse(&mpCDAOneShot, SQL_DeleteInvAndBalAgingStateInfo);

	Bind(":InvoiceTime", (char *)cInvoiceTime, sizeof(cInvoiceTime));
	Bind(":StartId", &nStartId);
	Bind(":EndId", &nEndId);
	Bind(":ProgramType", &nProgramType);
	rc	=oexec((struct cda_def *)mpCDACurrent);

	Check(rc); 
	Commit();
	//Clean!
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;

}