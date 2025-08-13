/*	$Id: clsDailyFinance.h,v 1.3 1998/08/25 03:20:04 josh Exp $	*/
//
//	File:		clsDailyFinance.h
//
// Class:	clsDailyFinance
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Represents dialy statistics
//
// Modifications:
//				- 10/07/97 wen	- Created
//
#ifndef CLSDAILYFINANCE_INCLUDED
#define CLSDAILYFINANCE_INCLUDED

#include "eBayTypes.h"
#include "vector.h"
#include <time.h>

class clsDailyFinanceRaw
{
	public:

	// Constructors
	clsDailyFinanceRaw( time_t	TheDay,
						int		Action,
						int		Count,
						float	Amount)
	{
		mDay	= TheDay;
		mAction	= Action;
		mCount	= Count;
		mAmount	= Amount;
	}

	// destructor
	~clsDailyFinanceRaw(){;}

	// gets
	time_t	GetDate()   { return mDay; }
	int		GetAction() { return mAction; }
	int		GetCount() { return mCount; }
	float	GetAmount() { return mAmount; }

	protected:
		time_t	mDay;
		int		mAction;
		int		mCount;
		float	mAmount;
		
};

typedef vector<clsDailyFinanceRaw*> DailyFinanceRawVector;


class clsDailyFinance
{
	public:

		// Constructors
		clsDailyFinance(int MaxAction);

		// destructor
		~clsDailyFinance(){delete [] mpAmount;}

		// Sets
		void SetDate(time_t TheDay) { mTheDay = TheDay; }

		void SetData(clsDailyFinanceRaw *iDailyFinanceRaw);

		// Gets
		time_t	GetDate()			{ return mTheDay;}

		float	GetAmount(int Action);
		float	GetNoSaleCredits();
		float	GetOtherCRDR();

	protected:
		time_t		mTheDay;
		float*		mpAmount;
		int			mMaxAction;
};

typedef vector<clsDailyFinance *> DailyFinanceVector;

#endif // CLSDAILYFINANCE_INCLUDED
