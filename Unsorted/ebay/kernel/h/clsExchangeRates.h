/*	$Id: clsExchangeRates.h,v 1.4.140.2 1999/08/03 00:52:39 phofer Exp $	*/
//
//	File:		clsExchangeRates.cpp
//
//	Class:		clsExchangeRates
//
//	Author:		Barry Boone (barry@ebay.com)
//
//	Function:
//		Maintain exchange rates for currencies we need to translate. 
//		Needed for Phase 1 for billing and account info. 
//		Eventually, this class will contribute to more flexible currency options.
//
//	Modifications:
//				- 2/21/99 Barry	- Created
//
//////////////////////////////////////////////////////////////////////

#ifndef CLSEXCHANGERATES_INCLUDED
#define CLSEXCHANGERATES_INCLUDED

#include "eBayTypes.h"
#include "clsCurrencies.h"
#include <vector.h>

class clsExchangeRate
{
public:
	clsExchangeRate(time_t when, double rate);
	~clsExchangeRate();

	double GetRate(bool toUSD);
	time_t GetDate();

private:
	time_t mDay;
	double mRate;
};

typedef vector<clsExchangeRate *> ExchangeRateVector;

//samuel, 6/7/99
// number of foreign exchange rates we have in database + 1
// since we use the Currency Enum to index into the array
// we declare clsExchangeRates.cpp
const unsigned short	MAX_RATES = 226;

//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

class clsExchangeRates
{
public:
	clsExchangeRates();
	~clsExchangeRates();

	double GetExchangeRate(time_t when, int fromCurrency, int toCurrency);
	//samuel, 7/14/99
	double GetActualExchangeRate(time_t when, int fromCurrency, int toCurrency);
	double FromAmountTo(int fromCurrency, double amount, int toCurrency, 
							time_t when=time(0));

private:

	bool RateExists(ExchangeRateVector *pV, time_t when);
	void EmptyVectors();
	//samuel, 6/17
	void NewVectors();
	void EmptyVector(ExchangeRateVector *v);
	void Recache();
	//samuel, 6/17
	void InitVectors();

	time_t			    mToday;
	int					mMaxRates;
	/*
	ExchangeRateVector  mvCADtoUSD;
	ExchangeRateVector  mvGBPtoUSD;
	ExchangeRateVector  mvDEMtoUSD;		// PH added 04/26/99
	ExchangeRateVector	mvAUDtoUSD;		// nsacco added 05/21/99
	// nsacco 07/13/99
	ExchangeRateVector	mvFRFtoUSD;
	ExchangeRateVector	mvJPYtoUSD;
	ExchangeRateVector	mvEURtoUSD;
	ExchangeRateVector	mvSEKtoUSD;
	ExchangeRateVector	mvCNYtoUSD;
	ExchangeRateVector	mvESPtoUSD;
	ExchangeRateVector	mvNOKtoUSD;
	ExchangeRateVector	mvDKKtoUSD;
	ExchangeRateVector	mvFIMtoUSD;
	*/	
	
	//samuel, 6/7/99
	ExchangeRateVector*	maXToUSD[MAX_RATES];

};

#endif /* CLSEXCHANGERATES_INCLUDED */

