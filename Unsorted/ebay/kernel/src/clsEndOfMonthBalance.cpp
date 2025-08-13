/*	$Id: clsEndOfMonthBalance.cpp,v 1.3 1999/02/21 02:47:33 josh Exp $	*/
//
// Class:	clsDailyStatistics
//
//	Author: inna markov(inna@ebay.com)
//
//	Function:
//
//				Information for End Of Month AccountBalances 
//
// Modifications:
//				- 09/23/98 inna	- Created
//

#include "eBayKernel.h"
#include "clsEndOfMonthBalance.h"


clsEndOfMonthBalance::clsEndOfMonthBalance(	int Id,
											time_t LastModified,
											float Balance,
											time_t PastDueBase,
											float PastDue30Days,
											float PastDue60Days,
											float PastDue90Days,
											float PastDue120Days,
											float PastDueOver120Days)
{
	mId = Id;
	mLastModified = LastModified;
	mBalance = Balance;
	mPastDueBase = PastDueBase;
	mPastDue30Days = PastDue30Days;
	mPastDue60Days = PastDue60Days;
	mPastDue90Days = PastDue90Days;
	mPastDue120Days = PastDue120Days;
	mPastDueOver120Days = PastDueOver120Days;
}

void clsEndOfMonthBalance::SetLastModified(time_t LastModified)
{
	mLastModified = LastModified;
	return;
}

void clsEndOfMonthBalance::SetBalance(float Balance)
{
	mBalance = Balance;
	return;
}

void clsEndOfMonthBalance::SetPastDueBase(time_t PastDueBase)
{
	mPastDueBase = PastDueBase;
	return;
}

void clsEndOfMonthBalance::SetPastDue30Days(float PastDue30Days)
{
	mPastDue30Days = PastDue30Days;
	return;
}

void clsEndOfMonthBalance::SetPastDue60Days(float PastDue60Days)
{
	mPastDue60Days = PastDue60Days;
	return;
}

void clsEndOfMonthBalance::SetPastDue90Days(float PastDue90Days)
{
	mPastDue90Days = PastDue90Days;
	return;
}

void clsEndOfMonthBalance::SetPastDue120Days(float PastDue120Days)
{
	mPastDue120Days = PastDue120Days;
	return;
}

void clsEndOfMonthBalance::SetPastDueOver120Days(float PastDueOver120Days)
{
	mPastDueOver120Days = PastDueOver120Days;
	return;
}


//put this balance into the end of month table
void clsEndOfMonthBalance::AddEndOfMonthBalanceDelayed()
{
		gApp->GetDatabase()->AddEndOfMonthBalanceDelayed(this);

}

//put this balance into the end of month table
void clsEndOfMonthBalance::AddEndOfMonthBalance()
{
		gApp->GetDatabase()->AddEndOfMonthBalanceDelayed(this);
}


