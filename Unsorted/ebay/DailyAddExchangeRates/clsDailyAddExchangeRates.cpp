/*	$Id: clsDailyAddExchangeRates.cpp,v 1.1.2.8 1999/06/10 22:14:06 samuel Exp $	*/
//
//	File:	clsDailyAddExchangeRates.cpp
//
//	Class:	clsDailyAddExchangeRates
//
//	Author:	Samuel Au (samuel@ebay.com)
//
//	Function:
//			To add foreign exchange rates into ebay_exchange_rates
//			table to be used in the next 24 hours.  Run once a day.
//
// Modifications:
//				- 05/05/99 samuel - Created
//
#include "clsDailyAddExchangeRates.h"
#include "clsMail.h"

/********************* Constants *******************/

// The codeCurrency part of the array has to be in accordance with 
// clsCurrency.h CurrencyCode ENUM, and change MAX_NEW_RATES if more rates
// are added to the existing structure
CurrencyCodeRecord CurrencyCodeRecordArray[MAX_NEW_RATES] =
{
	{	"US DOLLAR SPOT"			,	1,		true	},
	{	"CANADIAN DOLLAR SPOT"		,	2,		false	},
	{	"BRITISH POUND SPOT"		,	3,		true	},
	{	"GERMAN MARK SPOT"			,	4,		false	},
	{	"AUSTRALIAN DOLLAR SPOT"	,	5,		true	},
	{	"JAPANESE YEN SPOT"			,	6,		false	},
	{	"EURO SPOT"					,	7,		true	},
	{	"FRENCH FRANC SPOT"			,	8,		false	},
	{	"ARGENTINE PESO SPOT"		,	9,		false	},
	{	"AUSTRIAN SCHILLING SPOT"	,	10,		false	},
	{	"BELGIAN FRANC SPOT"		,	11,		false	},
	{	"BRAZILIAN REAL SPOT"		,	12,		false	},
	{	"SWISS FRANC SPOT"			,	13,		false	},
	{	"CHINA RENMINBI SPOT"		,	14,		false	},
	{	"CHILEAN PESO SPOT"			,	15,		false	},
	{	"CZECH KORUNA SPOT"			,	16,		false	},
	{	"DANISH KRONE SPOT"			,	17,		false	},
	{	"EGYPTIAN POUND SPOT"		,	18,		false	},
	{	"SPANISH PESETA SPOT"		,	19,		false	},
	{	"FINNISH MARKKA SPOT"		,	20,		false	},
	{	"GREEK DRACHMA SPOT"		,	21,		false	},
	{	"HONG KONG DOLLAR SPOT"		,	22,		false	},
	{	"HUNGARIAN FORINT SPOT"		,	23,		false	},
	{	"INDONESIAN RUPIAH SPOT"	,	24,		false	},
	{	"IRISH PUNT SPOT"			,	25,		true	},
	{	"ISRAELI SHEKEL SPOT"		,	26,		false	},
	{	"ITALIAN LIRA SPOT"			,	27,		false	},
	{	"SOUTH KOREAN WON SPOT"		,	28,		false	},
	{	"LUXEMBOURG FRANC SPOT"		,	29,		false	},
	{	"MEXICAN PESO SPOT"			,	30,		false	},
	{	"DUTCH GUILDER SPOT"		,	31,		false	},
	{	"NORWEGIAN KRONE SPOT"		,	32,		false	},
	{	"NEW ZEALAND DOLLAR SPOT"	,	33,		true	},
	{	"PHILIPPINES PESO SPOT"		,	34,		false	},
	{	"POLISH ZLOTY SPOT"			,	35,		false	},
	{	"PORTUGUESE ESCUDO SPOT"	,	36,		false	},
	{	"RUSSIAN RUBLE SPOT"		,	37,		false	},
	{	"SWEDISH KRONA SPOT"		,	38,		false	},
	{	"SINGAPORE DOLLAR SPOT"		,	39,		false	},
	{	"THAI BAHT SPOT"			,	40,		false	},
	{	"TAIWAN DOLLAR SPOT"		,	41,		false	},
	{	"VENEZUELAN BOLIVAR SPOT"	,	42,		false	},
	{	"S. AFRICAN RAND SPOT"		,	43,		false	}
};


// Constructor
clsDailyAddExchangeRates::clsDailyAddExchangeRates()
{
	mpDatabase = (clsDatabase *)0;
}


// Function: ParseAndGet
// Purpose: This function is to find in 'string' the exchange rate 
//			specified in 'currency'
// Parameters: 
//				string - the pass-in file which holds all exchange rates
//				currency - the currency exchange rate code specified in 
//						   eBayTypes.h
// Return: a float, the exchange rate required; 1.0 if not found
float clsDailyAddExchangeRates::ParseAndGet(char* string, char* currency)
{	
	char *start=NULL;
	char *end=NULL;
	char *needed;
	float revRate;

	start = strstr(string, currency);
	if (start != NULL)
	{
		start = strstr(start, "|");
		start += 1;
		start = strstr(start, "|");
		if (start != NULL)
		{
			start += 1;
			end = strstr(start, "|");
			needed = new char[sizeof(end-start+1)];
			strncpy(needed, start, (end - start));
			
			if ((revRate = atof(needed)) != 0.0)
			{
#ifndef _MSC_VER
	delete needed;
#endif
				return revRate;
			}
			else
			{
#ifndef _MSC_VER
	delete needed;
#endif
				return 1.0;
			}
		}
		return 1.0;
	}
	return 1.0;
}


// Function: AddRateToDB
// Purpose: This function is to add 'rate' into our database table ebay_exchange_rates
//			specified in 'currency'
// Parameters:
//				currency - the currency code defined in eBayTypes.h to be inserted
//				rate - the actual rate in float to be inserted
// Return: true if successfully inserted rate into database; false otherwise
bool clsDailyAddExchangeRates::AddRateToDB(int currency, float rate)
{
	time_t now;

	now = time(0);

	return (mpDatabase->InsertExchangeRateRecord(now, currency, rate));
}


// Function: Run
// Purpose: This is the so-called "main" in this class which takes care of
//			everything, including finding the right rates and inserting them
//			into the database.  After some initialization done in the console
//			app's main, this function is called.
// Parameters: none
// Return: nothing
void clsDailyAddExchangeRates::Run()
{
	char inString[MAX_FILE_SIZE];
	float newRates[MAX_NEW_RATES];
	FILE *rateFile;
	int index;	
	int fcount;
	clsMail *pMail;
	ostrstream	*pMailStream;
	int rcMail;

	// instantiate clsMail object
	pMail = new clsMail;
	pMailStream	= pMail->OpenStream();
	
	// file I/O here
	rateFile = NULL;
	rateFile = fopen(FILE_EXCHANGE_RATE, "r");
	
	fcount = 0;
	if (rateFile)
	{
		fcount = fread(inString, sizeof(char), MAX_FILE_SIZE, rateFile);
		fclose(rateFile);
	}

	// Getting database object
	if (!mpDatabase)
	{
		mpDatabase = gApp->GetDatabase();
		// database error
		if (!mpDatabase)
		{
			//send an email to appropriate personnel
			*pMailStream << EMAIL_DATABASE_CONNECT_ERROR;
			rcMail = pMail->Send(TO_EMAIL_NORMAL, FROM_EMAIL, 
									SUBJECT_DATABASE_CONNECT_ERROR);
			delete pMail;
			printf("Database connect error: Please read email for detail\n");
			return;
		}

	}

	// check for file open/read errors
	// need database object before using clsMail to send an email
	
	// check for file open error
	if (!rateFile)
	{
		*pMailStream << EMAIL_FILE_OPEN_ERROR;
		rcMail = pMail->Send(TO_EMAIL_NORMAL, FROM_EMAIL, SUBJECT_FILE_OPEN_ERROR);
		delete pMail;
		printf("File Open error: Please read email for detail\n");
		return;
	}
	
	// when fcount equals 0, it is not ready anything from the 
	// file, and it could be: 1. file read error
	//						  2. FTP error
	if (!fcount)
	{
		// email possible errors to appropriate personnel
		*pMailStream << EMAIL_FILE_ERROR;
		rcMail = pMail->Send(TO_EMAIL_NORMAL, FROM_EMAIL, SUBJECT_FILE_ERROR);
		delete pMail;
		printf("File error: Please read email for detail\n");
		return;
	}
	

	// to parse the file and get the exchange rates into the array
	index = 0;
	while (index < MAX_NEW_RATES)
	{
		newRates[index] = ParseAndGet(inString, CurrencyCodeRecordArray[index].strCurrency);
		
		if (!CurrencyCodeRecordArray[index].toUSD)
		{
			newRates[index] = 1 / newRates[index];
		}
		
		index++;
	}

	// add the exchange rates into the database
	for (index = 1; index < MAX_NEW_RATES; index++)
	{
		if (!AddRateToDB(CurrencyCodeRecordArray[index].codeCurrency, newRates[index]))
		{
			// add to database error, send email to appropriate personnel
			*pMailStream << EMAIL_ADD_TO_DATABASE_ERROR;
			rcMail = pMail->Send(TO_EMAIL_NORMAL, FROM_EMAIL, 
				SUBJECT_ADD_TO_DATABASE_ERROR);
			delete pMail;
			printf("Database Add error: Please read email for detail\n");
			return;
		}
	}

	// just for testing purpose, print some output
	index = 0;
	while (index < MAX_NEW_RATES)
	{
		printf("%s\t: %f\n", CurrencyCodeRecordArray[index].strCurrency,
									newRates[index]);
		index++;
	}
	*pMailStream << EMAIL_RUNS_NORMAL;
	rcMail = pMail->Send(TO_EMAIL_NORMAL, FROM_EMAIL, SUBJECT_NORMAL);
	delete pMail;
	printf("Everything goes fine!\n");
}

// declare the object pointer
static clsDailyAddExchangeRates *pTestApp = NULL;


// console app's main()
void main()
{

#ifdef _MSC_VER
 g_tlsindex = 0;
#endif

	if (!pTestApp)
	{
		pTestApp = new clsDailyAddExchangeRates;
	}

	pTestApp->InitShell();
	pTestApp->Run();

	delete pTestApp;

	
}	