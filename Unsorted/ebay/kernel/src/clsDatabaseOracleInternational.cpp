/*	$Id: clsDatabaseOracleInternational.cpp,v 1.5.140.5 1999/08/04 19:22:49 nsacco Exp $	*/
//
//	File:	clsDatabaseOracleInternational.cpp
//
//	Class:	clsDatabaseOracleInternational
//
//	Author:	Barry Boone (barry@ebay.com)
//
//	Function:
//  Database access specific to internationalization.
//
// Modifications:
//				- 11/22/98 barry - created
//				- 06/11/99 nsacco - added Australian dollars
//				- 06/28/99 petra - added nameResId to loading of clsCountry
//				- 07/02/99 petra	- added GetLocaleInfo
//				- 08/03/99 petra	- add name_res_id, and remove currency specific subclasses
#include "eBayKernel.h"
#include "clsExchangeRates.h"
#include "clsIntlLocale.h"

//
//  GetAllCountries
//
//	This routine gets all countries at once.
//

// How many countries to get at once.
#define ORA_COUNTRIES_ARRAYSIZE 20

static const char *SQL_DetermineNumCountries =
 "select	count(*)  \
	from ebay_countries";


int clsDatabaseOracle::DetermineNumCountries()
{
	int			numCountries;

	// Open and parse the statement
	OpenAndParse(&mpCDAOneShot, SQL_DetermineNumCountries);

	// Outputs.
	Define(1, &numCountries);

	Execute();

	Fetch();

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return numCountries;
}

static const char *SQL_GetAllCountries =
   "select	id,										\
			code,									\
			american_name,							\
			dir_name,                               \
			slander_strict,							\
			name_res_id								\
	from ebay_countries order by id";

void clsDatabaseOracle::GetAllCountries(CountryVector *pvCountries)
{
	int         pCountryId[ORA_COUNTRIES_ARRAYSIZE];
	char        ppCountryCode[ORA_COUNTRIES_ARRAYSIZE][3];
	char        ppAmericanName[ORA_COUNTRIES_ARRAYSIZE][64];
	char        ppDirName[ORA_COUNTRIES_ARRAYSIZE][32];
	char        ppSlanderStrict[ORA_COUNTRIES_ARRAYSIZE][2];
	int	    pNameResId[ORA_COUNTRIES_ARRAYSIZE];	// petra

	int			count = ORA_COUNTRIES_ARRAYSIZE;
	int			i;
	int         n;
	int			rc;
	int			rowsFetched;

	clsCountry *pCountry;

	// Set the character memory to something!
	memset(ppCountryCode, '\0', ORA_COUNTRIES_ARRAYSIZE * sizeof(ppCountryCode[0]));
	memset(ppAmericanName, '\0', ORA_COUNTRIES_ARRAYSIZE * sizeof(ppAmericanName[0]));
	memset(ppDirName, '\0', ORA_COUNTRIES_ARRAYSIZE * sizeof(ppDirName[0]));
	memset(ppSlanderStrict, '\0', ORA_COUNTRIES_ARRAYSIZE * sizeof(ppSlanderStrict[0]));

	// Get ready.
	OpenAndParse(&mpCDAGetAllCountries, SQL_GetAllCountries);

	// Load 'er up with addresses.
	Define(1, &pCountryId[0]);
	Define(2, (char *)&ppCountryCode, sizeof(ppCountryCode[0]));
	Define(3, (char *)&ppAmericanName, sizeof(ppAmericanName[0]));
	Define(4, (char *)&ppDirName, sizeof(ppDirName[0]));
	Define(5, (char *)&ppSlanderStrict, sizeof(ppSlanderStrict[0]));
	Define(6, &pNameResId[0]);	// petra

	Execute();

	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDAGetAllCountries, ORA_COUNTRIES_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDAGetAllCountries)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDAGetAllCountries);
			Close(&mpCDAGetAllCountries, true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to take care of time 
		// (always <= ORA_COUNTRIES_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDAGetAllCountries)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pCountry = new clsCountry(pCountryId[i], ppCountryCode[i], ppAmericanName[i], ppDirName[i], ppSlanderStrict[i],
							pNameResId[i]);	// petra
			pvCountries->push_back(pCountry);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetAllCountries);
	SetStatement(NULL);

	return;	
}

//
//  GetAllCurrencies
//
//	This routine gets all currencies at once.
//

// How many currencies to get at once.
#define ORA_CURRENCIES_ARRAYSIZE 20

static const char *SQL_DetermineNumCurrencies =
 "select	count(*)  \
	from ebay_currencies";


int clsDatabaseOracle::DetermineNumCurrencies()
{
	int			numCurrencies;

	

	// Open and parse the statement
	OpenAndParse(&mpCDAGetNumCurrencies, SQL_DetermineNumCurrencies);

	// Outputs.
	Define(1, &numCurrencies);

	Execute();

	Fetch();

	Close (&mpCDAGetNumCurrencies);
	SetStatement(NULL);

	return numCurrencies;
}

// petra 08/03/99 add name_res_id, and remove currency specific subclasses

static const char *SQL_GetAllCurrencies =
   "select	id,								\
			currency,						\
			currency_pl,					\
			symbol,                         \
			sub_currency,					\
			sub_currency_pl,				\
			sub_currency_ratio,				\
			iso_4217,						\
			name_res_id						\
  from ebay_currencies order by id";

void clsDatabaseOracle::GetAllCurrencies(CurrencyVector *pvCurrencies)
{
	int         pId[ORA_CURRENCIES_ARRAYSIZE];
	char        ppName[ORA_CURRENCIES_ARRAYSIZE][64];
	char        ppNamePlural[ORA_CURRENCIES_ARRAYSIZE][64];
	char        ppSymbol[ORA_CURRENCIES_ARRAYSIZE][16];
	char        ppSubCurrency[ORA_CURRENCIES_ARRAYSIZE][64];
	char        ppSubCurrencyPlural[ORA_CURRENCIES_ARRAYSIZE][64];
	int			pRatio[ORA_CURRENCIES_ARRAYSIZE];
	char        ppISO4217[ORA_CURRENCIES_ARRAYSIZE][8];
	int			pNameResId[ORA_CURRENCIES_ARRAYSIZE];

	int			count = ORA_CURRENCIES_ARRAYSIZE;
	int			i;
	int         n;
	int			rc;
	int			rowsFetched;

	clsCurrency *pCurrency;

	// Set the character memory to something!
	memset(ppName, '\0', ORA_CURRENCIES_ARRAYSIZE * sizeof(ppName[0]));
	memset(ppNamePlural, '\0', ORA_CURRENCIES_ARRAYSIZE * sizeof(ppNamePlural[0]));
	memset(ppSymbol, '\0', ORA_CURRENCIES_ARRAYSIZE * sizeof(ppSymbol[0]));
	memset(ppSubCurrency, '\0', ORA_CURRENCIES_ARRAYSIZE * sizeof(ppSubCurrency[0]));
	memset(ppSubCurrencyPlural, '\0', ORA_CURRENCIES_ARRAYSIZE * sizeof(ppSubCurrencyPlural[0]));
	memset(ppISO4217, '\0', ORA_CURRENCIES_ARRAYSIZE * sizeof(ppISO4217[0]));

	// Get ready.
	OpenAndParse(&mpCDAGetAllCurrencies, SQL_GetAllCurrencies);

	// Load 'er up with addresses.
	Define(1, &pId[0]);
	Define(2, (char *)&ppName, sizeof(ppName[0]));
	Define(3, (char *)&ppNamePlural, sizeof(ppNamePlural[0]));
	Define(4, (char *)&ppSymbol, sizeof(ppSymbol[0]));
	Define(5, (char *)&ppSubCurrency, sizeof(ppSubCurrency[0]));
	Define(6, (char *)&ppSubCurrencyPlural, sizeof(ppSubCurrencyPlural[0]));
	Define(7, &pRatio[0]);
	Define(8, (char *)&ppISO4217, sizeof(ppISO4217[0]));
	Define(9, &pNameResId[0]);

	Execute();

	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDAGetAllCurrencies, ORA_CURRENCIES_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDAGetAllCurrencies)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDAGetAllCurrencies);
			Close(&mpCDAGetAllCurrencies, true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to take care of time 
		// (always <= ORA_CURRENCIES_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDAGetAllCurrencies)->rpc - rowsFetched;

		for (i=0; i < n; i++)
		{
			pCurrency = new clsCurrency(pId[i], ppName[i], 
								ppNamePlural[i], ppSubCurrency[i], 
								ppSubCurrencyPlural[i], ppSymbol[i],
								ppISO4217[i], pRatio[i],
								pNameResId[i]);

			pvCurrencies->push_back(pCurrency);
		}

		rowsFetched += n;

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetAllCurrencies);
	SetStatement(NULL);

	return;	
}



// Get all the exchange rates so we can cache them.
#define ORA_RATES_ARRAYSIZE 10

static const char *SQL_GetRatesForCurrency =
  "select TO_CHAR(day_of_rate,				\
				'YYYY-MM-DD HH24-MI-SS'),	\
			rate							\
   from ebay_exchange_rates				    \
   where from_currency = :fromCurrency		\
   order by day_of_rate";

// Return true if we find the rate, false if we do not.
// The pvRates vector should be empty coming in!

bool clsDatabaseOracle::GetRatesForCurrency(ExchangeRateVector *pvRates, int fromCurrency)
{
	clsExchangeRate *pRate;

	int				i;
	int			    n;
	int				rc;
	int				rowsFetched;

	char   dayOfRate[ORA_RATES_ARRAYSIZE][32];
	double rate[ORA_RATES_ARRAYSIZE];

	time_t	day;

	// Set character memory to something
	memset(dayOfRate, '\0', ORA_RATES_ARRAYSIZE * sizeof(dayOfRate[0]));

	// Set up the SQL statement.
	OpenAndParse(&mpCDAGetRatesForCurrency, SQL_GetRatesForCurrency);

	// Bind.
	Define(1, (char *)&dayOfRate, sizeof(dayOfRate[0]));
	Define(2, rate);

	Bind(":fromCurrency", &fromCurrency);

	Execute();

	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDAGetRatesForCurrency, ORA_RATES_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDAGetRatesForCurrency)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDAGetRatesForCurrency);
			Close(&mpCDAGetRatesForCurrency);
			SetStatement(NULL);
			return false;
		}

		// rpc is cumulative, so find out how many rows to take care of time 
		// (always <= ORA_RATES_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDAGetRatesForCurrency)->rpc - rowsFetched;

		for (i=0; i < n; i++)
		{
			ORACLE_DATEToTime(dayOfRate[i], &day);
			pRate = new clsExchangeRate(day, rate[i]);
			pvRates->push_back(pRate);
		}

		rowsFetched += n;

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetRatesForCurrency);
	SetStatement(NULL);

	return true;
}
	
static const char *SQL_InsertCurrencyRecord =
 "insert into ebay_exchange_rates		\
	(   day_of_rate,					\
		from_currency,					\
		rate							\
	)									\
  values								\
	(   TO_DATE(:dayofrate,				\
			'YYYY-MM-DD HH24:MI:SS'),	\
		:fromcurrency,					\
		:rate							\
	)";
	
bool clsDatabaseOracle::InsertExchangeRateRecord(time_t indate, int fromcurrency, double rate)
{
	char   newdate[32];
	float  inrate;

	inrate = rate;
	// Time Conversion
	TimeToORACLE_DATE(indate, newdate);

	// Set up the SQL statement.
	OpenAndParse(&mpCDAInsertExchangeRate, SQL_InsertCurrencyRecord);

	// Bind
	Bind(":dayofrate", newdate);
	Bind(":fromcurrency", &fromcurrency);
	Bind(":rate", &inrate);
	
	// Let's do it!
	Execute();

	// Commit
	Commit();

	// Free things
	Close(&mpCDAInsertExchangeRate);
	SetStatement(NULL);
	
	return true;
} 

// Get all the information for one locale from all over the place

static const char *SQL_GetLocaleInfo =
  "select	l.country_id,						\
			t.name_standard,					\
			t.name_summer,						\
			t.timezone_offset,					\
			c.observes_summertime,				\
			c.summertime_begins_first,			\
			c.summertime_begins_month,			\
			c.summertime_ends_first,			\
			c.summertime_ends_month,			\
			l.date_format_short,				\
			l.date_format_medium,				\
			l.date_format_long,					\
			l.time_format_short,				\
			l.time_format_medium,				\
			l.time_format_long,					\
			l.positive_amount_format,			\
			l.negative_amount_format,			\
			l.decimal_symbol,					\
			l.grouping_symbol,					\
			l.digits_after_decimal,				\
			l.digits_in_group,					\
			l.negative_number_format,			\
			c.default_currency_id				\
   from ebay_locale l,						    \
		ebay_countries c,						\
		ebay_timezones t						\
   where	l.locale_id = :localeId				\
	and		t.timezone_id = :timezoneId			\
	and		c.id = l.country_id";

// Read all the values, compute what needs to go into clsIntlLocale,
// and set the members

void clsDatabaseOracle::GetLocaleInfo(int localeId, int timezoneId, clsIntlLocale * pLocale)
{
	int		countryId;
	char	nameStandard [EBAY_LOCALE_TIMEZONE];		// char_intl
	char	nameSummer [EBAY_LOCALE_TIMEZONE];		// char_intl
	double  timeZoneOffset;
	char    observesSummertime[2];
	bool	b_observesSummertime = false;
	char    summerTimeBeginsFirstOrLast[2];
	bool	b_summerTimeBeginsFirst = false;
	int		summerTimeBeginsMonth;
	char	summerTimeEndsFirstOrLast[2];
	bool	b_summerTimeEndsFirst = false;
	int		summerTimeEndsMonth;
	char	dateFormatShort[EBAY_LOCALE_SHORT_FORMAT];
	char	dateFormatMedium[EBAY_LOCALE_MEDIUM_FORMAT];
	char	dateFormatLong [EBAY_LOCALE_LONG_FORMAT];
	char	timeFormatShort [EBAY_LOCALE_SHORT_FORMAT];
	char	timeFormatMedium [EBAY_LOCALE_MEDIUM_FORMAT];
	char	timeFormatLong [EBAY_LOCALE_LONG_FORMAT];
	char	positiveAmountFormat [EBAY_LOCALE_MEDIUM_FORMAT];
	char	negativeAmountFormat [EBAY_LOCALE_MEDIUM_FORMAT];
	char	decimalSymbol[2];		// intl_char
	char	groupingSymbol[2];	// intl_char
	int		digitsAfterDecimal;
	int		digitsInGroup;
	char	negativeNumberFormat [EBAY_LOCALE_MEDIUM_FORMAT];
	int		defaultCurrencyId;

	// Set character memory to something
	memset(nameStandard,		'\0', EBAY_LOCALE_TIMEZONE);
	memset(nameSummer,			'\0', EBAY_LOCALE_TIMEZONE);
	memset(observesSummertime,			'\0', sizeof(observesSummertime));
	memset(summerTimeBeginsFirstOrLast, '\0', sizeof(summerTimeBeginsFirstOrLast));
	memset(summerTimeEndsFirstOrLast,	'\0', sizeof(summerTimeEndsFirstOrLast));
	memset(dateFormatShort,		'\0', EBAY_LOCALE_SHORT_FORMAT);
	memset(dateFormatMedium,	'\0', EBAY_LOCALE_MEDIUM_FORMAT);
	memset(dateFormatLong,		'\0', EBAY_LOCALE_LONG_FORMAT);
	memset(timeFormatShort,		'\0', EBAY_LOCALE_SHORT_FORMAT);
	memset(timeFormatMedium,	'\0', EBAY_LOCALE_MEDIUM_FORMAT);
	memset(timeFormatLong, 		'\0', EBAY_LOCALE_LONG_FORMAT);
	memset(positiveAmountFormat,'\0', EBAY_LOCALE_MEDIUM_FORMAT);
	memset(negativeAmountFormat,'\0', EBAY_LOCALE_MEDIUM_FORMAT);
	memset(decimalSymbol,		'\0', sizeof(decimalSymbol));
	memset(groupingSymbol,		'\0', sizeof(groupingSymbol));
	memset(negativeNumberFormat,'\0', EBAY_LOCALE_MEDIUM_FORMAT);

	// Set up the SQL statement.
	OpenAndParse(&mpCDAGetLocales, SQL_GetLocaleInfo);

	// Bind.
	Define(1, &countryId);
	Define(2, (char *)&nameStandard,	EBAY_LOCALE_TIMEZONE);
	Define(3, (char *)&nameSummer,		EBAY_LOCALE_TIMEZONE);
	Define(4, &timeZoneOffset);
	Define(5, (char *)&observesSummertime,			sizeof(observesSummertime));
	Define(6, (char *)&summerTimeBeginsFirstOrLast, sizeof(summerTimeBeginsFirstOrLast));
	Define(7, &summerTimeBeginsMonth);
	Define(8, (char *)&summerTimeEndsFirstOrLast,	sizeof(summerTimeEndsFirstOrLast));
	Define(9, &summerTimeEndsMonth);
	Define(10,(char *)&dateFormatShort,	EBAY_LOCALE_SHORT_FORMAT);
	Define(11,(char *)&dateFormatMedium,EBAY_LOCALE_MEDIUM_FORMAT);
	Define(12,(char *)&dateFormatLong,	EBAY_LOCALE_LONG_FORMAT);
	Define(13,(char *)&timeFormatShort,	EBAY_LOCALE_SHORT_FORMAT);
	Define(14,(char *)&timeFormatMedium,EBAY_LOCALE_MEDIUM_FORMAT);
	Define(15,(char *)&timeFormatLong,	EBAY_LOCALE_LONG_FORMAT);
	Define(16,(char *)&positiveAmountFormat,	EBAY_LOCALE_MEDIUM_FORMAT);
	Define(17,(char *)&negativeAmountFormat,	EBAY_LOCALE_MEDIUM_FORMAT);
	Define(18,(char *)&decimalSymbol,	sizeof(decimalSymbol));
	Define(19,(char *)&groupingSymbol,	sizeof(groupingSymbol));
	Define(20,&digitsAfterDecimal);
	Define(21,&digitsInGroup);
	Define(22,(char *)&negativeNumberFormat,	EBAY_LOCALE_MEDIUM_FORMAT);
	Define(23,&defaultCurrencyId);

	Bind(":localeId", &localeId);
	Bind(":timezoneId", &timezoneId);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		// We're done with the cursor
		Close(&mpCDAGetLocales);
		SetStatement(NULL);
		return;
	};

	// We're done with the cursor
	Close(&mpCDAGetLocales);
	SetStatement(NULL);


	if (strcmp(observesSummertime,"Y") == 0)
		b_observesSummertime = true;
	if (strcmp(summerTimeBeginsFirstOrLast,"F") == 0)
		b_summerTimeBeginsFirst = true;
	if (strcmp(summerTimeEndsFirstOrLast,"F") == 0)
		b_summerTimeEndsFirst = true;
	
	pLocale->SetFromDB (countryId,
						nameStandard,
						nameSummer,
						timeZoneOffset,
						b_observesSummertime,
						b_summerTimeBeginsFirst,
						summerTimeBeginsMonth,
						b_summerTimeEndsFirst,
						summerTimeEndsMonth,
						dateFormatShort,
						dateFormatMedium,
						dateFormatLong,
						timeFormatShort,
						timeFormatMedium,
						timeFormatLong,
						positiveAmountFormat,
						negativeAmountFormat,
						decimalSymbol,
						groupingSymbol,
						digitsAfterDecimal,
						digitsInGroup,
						negativeNumberFormat,
						defaultCurrencyId);

	return;
}
//samuel, 6/17
// check in ebay_exchange_rates how many exchange rates are
// in the table
// How many currencies to get at once.

static const char *SQL_DetermineNumExchangeRates =
 "select	MAX(from_currency)  \
	from ebay_exchange_rates";


int clsDatabaseOracle::DetermineNumExchangeRates()
{
	int			numExchangeRates;

	// Open and parse the statement
	OpenAndParse(&mpCDAGetNumExchangeRates, SQL_DetermineNumExchangeRates);

	// Outputs.
	Define(1, &numExchangeRates);

	Execute();

	Fetch();

	Close (&mpCDAGetNumExchangeRates);
	SetStatement(NULL);

	if (numExchangeRates > 0)
	{
		return (numExchangeRates-1);  // US Dollar is the one of the currencies, but 
										// not in the database table, so minus 1
	} 
	else
	{
		return numExchangeRates;
	}
}	