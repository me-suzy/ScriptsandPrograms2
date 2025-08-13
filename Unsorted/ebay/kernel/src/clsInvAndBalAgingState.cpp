/*	$Id: clsInvAndBalAgingState.cpp,v 1.1.2.1 1999/07/30 02:40:43 sliang Exp $	*/
//
//	File:		clsInvAndBalAgingState.cpp
//
// Class:	clsInvAndBalAgingState
//
//	Author:	Sonya Liang  (sliang@ebay.com)
//
//	Function: represents an invoice or balance aging instance state record
//
// Modifications:
//				- 07/21/99 Sonya	- Created


#include "eBayKernel.h"
#include "clsInvAndBalAgingState.h"


clsInvAndBalAgingState::clsInvAndBalAgingState(time_t InvoiceTime, int StartId, int EndId, ProgramTypeEnum ProgramType, char *pPid)
{
	mInvoiceTime=InvoiceTime;
	mStartId = StartId;
	mEndId=EndId;
	mProgramType=ProgramType;
	mpPid	= new char[11];
	strcpy(mpPid, pPid);
	mProcessCount=0;
	mRangeOverlapCount=0;

}

/*clsInvAndBalAgingState::clsInvAndBalAgingState(time_t Started, time_t From_Time, 
						 time_t End_Time, char *pPid)
{
	mStarted = Started;
	mpPid	= new char[10];
	strcpy(mpPid, pPid);
	mFrom_Time = From_Time;
	mEnd_Time = End_Time;
	mSeqId = 0;
}*/

void clsInvAndBalAgingState::SetStartId(int StartId)
{
	mStartId = StartId;
	return;
}

void clsInvAndBalAgingState::SetEndId(int EndId)
{
	mEndId = EndId;
	return;
}

void clsInvAndBalAgingState::SetProcessCount(int ProcessCount)
{
	mProcessCount = ProcessCount;
	return;
}

void clsInvAndBalAgingState::SetProgramType(int ProgramType)
{
	mProgramType = ProgramType;
	return;
}

void clsInvAndBalAgingState::SetRangeOverlapCount(int RangeOverlapCount)
{
	mRangeOverlapCount = RangeOverlapCount;
	return;
}

void clsInvAndBalAgingState::SetInvoiceTime(time_t InvoiceTime)
{
	mInvoiceTime = InvoiceTime;
	return;
}

void clsInvAndBalAgingState::SetStartTime(time_t StartTime)
{
	mStartTime = StartTime;
	return;
}

void clsInvAndBalAgingState::SetEndTime(time_t EndTime)
{
	mEndTime = EndTime;
	return;
}


void clsInvAndBalAgingState::SetpPid(char *pPid)
{
	strcpy(mpPid,pPid);
	return;
}




//query database with the attributes in this object (invoicetime, startid,endid and programtype
// check the pid value , return info accordingly.
PidCheckEnum	clsInvAndBalAgingState::GetInvAndBalAgingStateInfo()
{
	//current object has invoicetime, starid, endid, programtype, and pid
	//save pid in save_pid, mpPid will get overriden by db value
	char *save_pid = new char[11];

	strcpy(save_pid, mpPid);

	if (gApp->GetDatabase()->GetInvAndBalAgingStateInfo(this))
	{
		//record found, check if not finished yet
		if(strcmp(mpPid, "YES")==0)
		{	
			delete save_pid;
			return BatchDown;
		}
		//else let's check fo prpcess returned being alive?
		//note: in case thsi process was reborn under a new name
		//the database should have been updated by a calling 
		//script to set pid to 0, if program called without a script
		//this instance will not rerun BUT is it better not ran then do it twice
		if(strcmp(mpPid, "0") == 0)
		{
			//need to set new pid
			strcpy(mpPid,save_pid);
			delete save_pid;
			return BatchRerunOK;
		}
		else
		{
			delete save_pid;
			//pid exist in the record and was not checked by the calling script
			//or pid still alive
			return BatchNoClearance;
		}
	}
	else
	{
		//no record found
		delete save_pid;
		return BatchNotExist;
	}
}

bool clsInvAndBalAgingState::UpdateInvAndBalAgingStateInfo()
{
	return (gApp->GetDatabase()->UpdateInvAndBalAgingStateInfo(this));
}

//insert this new state into the db
bool clsInvAndBalAgingState::CreateInvAndBalAgingStateInfo()
{
	//check to see if this insertion is successful. To avoid race condition
	// under which two copy of the intance run at the same time.
	return (gApp->GetDatabase()->CreateInvAndBalAgingStateInfo(this));

}

//updates pid from number to a word YES, put in the endtime of this instance
void clsInvAndBalAgingState::MakeInstanceComplete()
{
	
	gApp->GetDatabase()->MakeInstanceComplete(this);
}


bool clsInvAndBalAgingState::IsRangeOverlap()
{
	return (gApp->GetDatabase()->IsRangeOverlap(this));
}

void clsInvAndBalAgingState::CleanUpOverlappedRecord()
{
	gApp->GetDatabase()->CleanUpOverlappedRecord(this);
}