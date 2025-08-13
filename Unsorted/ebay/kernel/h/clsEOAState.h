/*	$Id: clsEOAState.h,v 1.2.96.1 1999/05/25 22:27:49 inna Exp $	*/
//
//	File:		clsEOAState.h
//
// Class:	clsEOAState
//
//	Author:	Inna Markov (inna@ebay.com)
//
//	Function: represents an end of auction instance state record
//
// Modifications:
//				- 02/03/99 inna	- Created
//
#ifndef CLSEOASTATE_INCLUDED
#define CLSEOASTATE_INCLUDED

class clsEOAState
{
	public:

		// Constructors
		clsEOAState(){;}
		clsEOAState(time_t Started, time_t From_Time, time_t To_Time,char *pPid);
		clsEOAState(time_t Started, char *pPid);
		// destructor
		~clsEOAState()
		{
				delete	[] mpPid;
		}

		// Gets
		int		GetSeqId(){return mSeqId;}
		time_t	GetStarted(){return mStarted;}
		time_t	GetFrom_Time(){return mFrom_Time;}
		time_t	GetEnd_Time(){return mEnd_Time;}	
		char*	GetpPid(){return mpPid;}

		// Sets
		void	SetStarted(time_t Started);
		void	SetFrom_Time(time_t From_Time);
		void	SetEnd_Time(time_t End_Time);
		void	SetpPid(char* pPid);
		void	SetSeqId(int SeqId);
		

		//put this balance into the end of month table
		bool	GetEOAStateInfo();
		bool	UpdateEOAStateInfo();
		bool	CreateNextEOAStateInfo();
		void	MakeInstanceComplete();

	private:
		int			mSeqId;
		time_t		mStarted;
		time_t		mFrom_Time;
		time_t		mEnd_Time;
		char		*mpPid;
};

#endif // CLSEOASTATE_INCLUDED
