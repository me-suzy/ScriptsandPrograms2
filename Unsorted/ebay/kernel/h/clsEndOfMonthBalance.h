/*	$Id: clsEndOfMonthBalance.h,v 1.4 1999/03/07 08:16:45 josh Exp $	*/
//
//	File:		clsEndOfMonthBalance.h
//
// Class:	clsEndOfMonthBalance
//
//	Author:	Inna Markov (inna@ebay.com)
//
//	Function: represents an end-of-month balance record
//
// Modifications:
//				- 09/23/98 inna	- Created
//
#ifndef CLSENDOFMONTHBALANCE_INCLUDED
#define CLSENDOFMONTHBALANCE_INCLUDED

//#include "eBayTypes.h"
//#include "time.h"
//#include "vector.h"

class clsEndOfMonthBalance
{
	public:

		// Constructors
		clsEndOfMonthBalance(){}
		clsEndOfMonthBalance(	int		Id,
								time_t	LastModified,
								float	Balance,
								time_t	PastDueBase,
								float	PastDue30Days,
								float	PastDue60Days,
								float	PastDue90Days,
								float	PastDue120Days,
								float	PastDueOver120Days);

		// destructor
		~clsEndOfMonthBalance(){;}

		// Gets
		int		GetId(){return mId;}
		time_t	GetLastModified(){return mLastModified;}
		float	GetBalance(){return mBalance;}
		time_t	GetPastDueBase(){return mPastDueBase;}
		float	GetPastDue30Days(){return mPastDue30Days;}
		float	GetPastDue60Days(){return mPastDue60Days;}
		float	GetPastDue90Days(){return mPastDue90Days;}
		float	GetPastDue120Days(){return mPastDue120Days;}
		float	GetPastDueOver120Days(){return mPastDueOver120Days;}	

		// Sets
		void	SetLastModified(time_t LastModified);
		void	SetBalance(float Balance);
		void	SetPastDueBase(time_t PastDueBase);
		void	SetPastDue30Days(float PastDue30Days);
		void	SetPastDue60Days(float PastDue60Days);
		void	SetPastDue90Days(float PastDue90Days);
		void	SetPastDue120Days(float PastDue120Days);
		void	SetPastDueOver120Days(float PastDueOver120Days);

		//put this balance into the end of month table
		void	AddEndOfMonthBalanceDelayed();
		void	AddEndOfMonthBalance();

	private:
		int			mId;
		time_t		mLastModified;
		float		mBalance;
		time_t		mPastDueBase;
		float		mPastDue30Days;
		float		mPastDue60Days;
		float		mPastDue90Days;
		float		mPastDue120Days;
		float		mPastDueOver120Days;
};

typedef vector<clsEndOfMonthBalance *> EndOfMonthBalanceVector;

#endif // CLSENDOFMONTHBALANCE_INCLUDED
