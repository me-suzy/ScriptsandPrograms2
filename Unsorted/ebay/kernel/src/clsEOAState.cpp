/*	$Id: clsEOAState.cpp,v 1.2.96.1 1999/05/25 22:27:58 inna Exp $	*/
//
//	File:		clsEOAState.cpp
//
// Class:	clsEOAState
//
//	Author:	Inna Markov (inna@ebay.com)
//
//	Function: represents an end of auction instance state record
//
// Modifications:
//				- 02/03/99 inna	- Created
#include "eBayKernel.h"
#include "clsEOAState.h"


clsEOAState::clsEOAState(time_t Started, char *pPid)
{
	mStarted = Started;
	mpPid	= new char[10];
	strcpy(mpPid, pPid);
	mFrom_Time = 0;
	mEnd_Time = 0;
	mSeqId= 0;
}

clsEOAState::clsEOAState(time_t Started, time_t From_Time, 
						 time_t End_Time, char *pPid)
{
	mStarted = Started;
	mpPid	= new char[10];
	strcpy(mpPid, pPid);
	mFrom_Time = From_Time;
	mEnd_Time = End_Time;
	mSeqId = 0;
}

void clsEOAState::SetStarted(time_t Started)
{
	mStarted = Started;
	return;
}

void clsEOAState::SetFrom_Time(time_t From_Time)
{
	mFrom_Time = From_Time;
	return;
}

void clsEOAState::SetEnd_Time(time_t End_Time)
{
	mEnd_Time = End_Time;
	return;
}

void clsEOAState::SetpPid(char *pPid)
{
	strcpy(mpPid,pPid);
	return;
}

void clsEOAState::SetSeqId(int SeqId)
{
	mSeqId = SeqId;
	return;
}


//let's populate existing object with db data
bool	clsEOAState::GetEOAStateInfo()
{
	//this better have To and From Dates
	//and also new pid;
	char *save_pid = new char[10];

	strcpy(save_pid,mpPid);//will get overriden by db value

	if (gApp->GetDatabase()->GetEOAStateInfo(this))
	{
		//record found, check if not finished yet
		if(strcmp(mpPid, "YES")==0)
		{	
			delete save_pid;
			return false;
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
			//need to get new sequence and update database
			gApp->GetDatabase()->UpdateEOAStateInfo(this);
			delete save_pid;
			return true;
		}
		else
		{
			delete save_pid;
			//pid exist in the record and was not checked by the calling script
			//or pid still alive
			return false;
		}
	}
	else
	{
		//no record found
		delete save_pid;
		return false;
	}
}

//let's populate object for teh next avalable state from last db data
bool clsEOAState::CreateNextEOAStateInfo()
{
	//make it boolean just to be consistent
	return (gApp->GetDatabase()->CreateNextEOAStateInfo(this));

}

//just updates pid from number to a word YES
void clsEOAState::MakeInstanceComplete()
{
	strcpy(mpPid,"YES");
	gApp->GetDatabase()->MakeInstanceComplete(this);
}


