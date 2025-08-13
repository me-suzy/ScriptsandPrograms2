/*	$Id: clsInvAndBalAgingState.h,v 1.1.2.1 1999/07/30 02:38:54 sliang Exp $	*/
//
//	File:		clsInvAndBalAgingState.h
//
// Class:	clsInvAndBalAgingState
//
//	Author:	Sonya Liang (Sonya@ebay.com)
//
//	Function: represents an invoice or balanceaging instance state record
//
// Modifications:
//				- 07/21/99 Sonya	- Created
//
#ifndef CLSINVANDBALAGINGSTATE_INCLUDED
#define CLSINVANDBALAGINGSTATE_INCLUDED

typedef enum 
{
	Invoice=1,
	BalanceAging=2
}ProgramTypeEnum;


typedef enum
{
	BatchDown=1,	
	BatchNoClearance=2,
	BatchRerunOK=3,
	BatchNotExist=4
}PidCheckEnum;

class clsInvAndBalAgingState
{
	public:

		// Constructors
		clsInvAndBalAgingState(){;}
		clsInvAndBalAgingState(time_t InvoiceTime, int StartId, int EndId, ProgramTypeEnum  ProgramType, char *pPid);
	//	clsInvAndBalAgingState(time_t Started, char *pPid);
		// destructor
		~clsInvAndBalAgingState()
		{
				delete	[] mpPid;
		}

		// Gets
		//int		GetSeqId(){return mSeqId;}
		int		GetStartId(){return mStartId;}
		int		GetEndId(){return mEndId;}
		int		GetProcessCount(){return mProcessCount;}
		int		GetProgramType(){ return mProgramType;}
		int		GetRangeOverlapCount(){return mRangeOverlapCount;}
		time_t	GetInvoiceTime(){return mInvoiceTime;}
		time_t	GetStartTime(){return mStartTime;}
		time_t	GetEndTime(){return mEndTime;}	
		char*	GetpPid(){return mpPid;}

		// Sets
		void	SetInvoiceTime(time_t InvoiceTime);
		void	SetProgramType(int Programtype);
		void	SetProcessCount(int ProcessCount);
		void	SetStartId(int IdStart);
		void	SetEndId(int IdEnd);
		void	SetStartTime(time_t StartTime);
		void	SetEndTime(time_t EndTime);
		void	SetpPid(char* pPid);
		void	SetRangeOverlapCount(int RangeOverlapCount);
		void	IncrementProcessCount() { ++mProcessCount;}
		void	IncrementRangeOverlapCount(){ ++mRangeOverlapCount;}

		//put record into the invoice and  balance aging state table
		PidCheckEnum		GetInvAndBalAgingStateInfo();
		bool	UpdateInvAndBalAgingStateInfo();
		bool	CreateInvAndBalAgingStateInfo();
		bool	IsRangeOverlap();
		void	CleanUpOverlappedRecord();
		void	MakeInstanceComplete();

	private:
		
		time_t		mInvoiceTime;
		int			mStartId;
		int			mEndId;
		int			mProgramType;
		int			mProcessCount;
		int			mRangeOverlapCount;
		time_t		mStartTime; 
		time_t		mEndTime;	
		char		*mpPid;
};

#endif // clsInvAndBalAgingState_INCLUDED
