/*	$Id: clsExchangeRates.cpp,v 1.4.140.2 1999/08/03 00:52:41 phofer Exp $	*/
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
//
//	Modifications:
//				- 2/21/99 Barry	- Created
//				- 05/21/99 nsacco - added Australian dollars
//
//////////////////////////////////////////////////////////////////////
#include "eBayKernel.h" 
#include "clsExchangeRates.h"
#include "clsUtilities.h"

//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

clsExchangeRate::clsExchangeRate(time_t when, double rate)
{
	mDay  = when;
	mRate = rate;
}

clsExchangeRate::~clsExchangeRate()
{
}

time_t clsExchangeRate::GetDate()
{
	return mDay;
}

double clsExchangeRate::GetRate(bool toUSD)
{
	if (toUSD)
		return mRate;
	else
		return 1.0/mRate;
}

// --------------------------------------------------

clsExchangeRates::clsExchangeRates()
{
	mToday = time(0);
	clsUtilities::TimeToMidnight(&mToday);

	InitVectors();
	Recache();
}

clsExchangeRates::~clsExchangeRates()
{
	EmptyVectors();
}

double clsExchangeRates::GetExchangeRate(time_t when, int fromCurrency, int toCurrency)
{
	ExchangeRateVector::iterator iter;
	ExchangeRateVector *pV;
	double rate;
	//samuel, 6/7/99
	bool oneOver;

	//samuel, 7/7/99
	// initialize pV to NULL
	pV = NULL;

	clsUtilities::TimeToMidnight(&when);

	if (when > mToday)
	{
		Recache(); // We are asking for the next day's rate, so refill the cache
	}

	/*
	// Currently, we only have exchange rates from other currencies
	// to US dollars, so to just get this working, I have made that
	// an assumption.
	switch (fromCurrency)
	{
	case Currency_CAD:
		pV = &mvCADtoUSD;
		break;
	case Currency_GBP:
		pV = &mvGBPtoUSD;
		break;
	// PH added 04/26/99 I wish I understood what this is for, so I can think of a better way
	case Currency_DEM:
		pV = &mvDEMtoUSD;
		break;
	// nsacco added 05/21/99 Australia 
	case Currency_AUD:
		pV = &mvAUDtoUSD;
	default:
		return 1.0; // ? Throw an exception? This means we don't even know about this currency
	}
	*/
	//samuel, 6/7/99
	if (fromCurrency == Currency_USD)
	{
		oneOver = true;
		pV = maXToUSD[toCurrency];
	}

	else if (toCurrency == Currency_USD)
	{
		oneOver = false;
		pV = maXToUSD[fromCurrency];
	}

	// Determine if the rate we want even exists.
	if (RateExists(pV, when))
	{
		iter = pV->begin();
		rate = (*iter)->GetRate(true); // Just in case we start out with GetDate() > when

		// The date is in a range we know about. Find it in the vector.
		for (iter = pV->begin(); iter != pV->end(); iter++)
		{
			// First see if we can find the exact day's rate we want.
			if ((*iter)->GetDate() == when)
			{
				//samuel, 6/7/99
				if (oneOver)
				{
					return (*iter)->GetRate(false); // Found it, return this day's rate
							// Again, we're assuming the rates are to USD
				}
				else
				{
					return (*iter)->GetRate(true); // Found it, return this day's rate
							// Again, we're assuming the rates are to USD
				}
			}
			else 
			{
				if ((*iter)->GetDate() > when)
				{
					if (oneOver)
					{
						return 1/rate; // We went past -- return the previous day's rate
					}
					else
					{
						return rate; // We went past -- return the previous day's rate
					}
				}
			}
			rate = (*iter)->GetRate(true); // We may need to return the rate from the day before
		}  
		if (oneOver)
		{
			return 1/rate; // return rate if "when" is after the latest day in database
		}
		else
		{
			return rate;  // return rate if "when" is after the latest day in database
		}
	}

	//samuel, 7/7/99
	// if rate is not found, return 0.0 rather than 1.0
	// so comment out the "return 1.00000" on 7/7/99
	// The rate is from before we started recording them.
	//return 1.000000000000000; // ? This should eventually try to read from the db
	return 0.0000000000;
}

//samuel, 7/14/99
// Function to return an exchange rate even if both pass-in currency ids are not 
// Currrency_USD
double clsExchangeRates::GetActualExchangeRate(time_t when, int fromCurrency, int toCurrency)
{
	double result1;
	double result2;

	if (fromCurrency == toCurrency)
	{
		return 1;
	}

	else if ((fromCurrency != Currency_USD) && (toCurrency != Currency_USD))
	{
		result1 = GetExchangeRate(when, fromCurrency, Currency_USD);
		result2 = GetExchangeRate(when, Currency_USD, toCurrency);

		return (result1 * result2);
	}

	else
	{
		return GetExchangeRate(when, fromCurrency, toCurrency);
	}
}

// Function to convert an amount from currency X to currency Y
double clsExchangeRates::FromAmountTo(int fromCurrency, double amount, int toCurrency,
										time_t when)
{
	double erate;
	double result;

	erate = GetActualExchangeRate(when, fromCurrency, toCurrency);

	result = amount * erate;

	return result;
}

// ----------- Private functions ------------

bool clsExchangeRates::RateExists(ExchangeRateVector *pV, time_t when)
{
	ExchangeRateVector::iterator iter;

	// Look at the first entry and see if we are looking for something
	// after that.
	// samuel, 7/7/99
	// check to see if pV is NULL
	if (pV)
	{
		iter = pV->begin();
		return ((*iter)->GetDate() <= when);
	}
	else
	{
		return false;
	}
}

void clsExchangeRates::Recache()
{
	int index;
	// Get new exchange rate values into this object.
	time_t when = time(0);
	clsUtilities::TimeToMidnight(&when);

	// we have to add 2 since currencyID 0 and currencyID 1 are there, but 
	// not rate is there for these 2 currencyIDs
	mMaxRates = gApp->GetDatabase()->DetermineNumExchangeRates() + 2;
	EmptyVectors();
	NewVectors();

	/*
	gApp->GetDatabase()->GetRatesForCurrency(&mvCADtoUSD, Currency_CAD);
	gApp->GetDatabase()->GetRatesForCurrency(&mvGBPtoUSD, Currency_GBP);
	gApp->GetDatabase()->GetRatesForCurrency(&mvDEMtoUSD, Currency_DEM); // PH added 04/26/99
	gApp->GetDatabase()->GetRatesForCurrency(&mvAUDtoUSD, Currency_AUD); // nsacco added 05/21/99
	*/

	//samuel, 6/7/99
	index = 2; // this starts at 2 since the first 2 in the Currency Enum won't be needed
	while (index < mMaxRates)
	{
		gApp->GetDatabase()->GetRatesForCurrency(maXToUSD[index], index);
		index++;
	}

	//samuel, 6/7/99
	mToday = when; // update mToday
}

void clsExchangeRates::EmptyVector(ExchangeRateVector *v)
{
	ExchangeRateVector::iterator iter;
	for (iter = v->begin(); iter != v->end(); iter++)
		delete *iter;
	v->clear();
}

void clsExchangeRates::EmptyVectors()
{
	/*
	EmptyVector(&mvCADtoUSD);
	EmptyVector(&mvGBPtoUSD);
	EmptyVector(&mvDEMtoUSD); // PH added 04/6/99
	EmptyVector(&mvAUDtoUSD); // nsacco added 05/21/99
	*/
	//samuel, 6/7/99
	int	index;

	index = 2; // this starts at 2 since the first 2 in the Currency Enum won't be needed
	while (index < mMaxRates)
	{
		if (maXToUSD[index])
		{
			EmptyVector(maXToUSD[index]);
			delete maXToUSD[index];
		}
		index++;
	}
}

void clsExchangeRates::NewVectors()
{
	int index;

	index = 2;
	while (index < mMaxRates)
	{
		maXToUSD[index] = new ExchangeRateVector;
		index++;
	}
}

//samuel, 6/17
void clsExchangeRates::InitVectors()
{
	int index;

	index = 0;

	while (index < MAX_RATES)
	{
		maXToUSD[index] = NULL;
		index++;
	}
}
