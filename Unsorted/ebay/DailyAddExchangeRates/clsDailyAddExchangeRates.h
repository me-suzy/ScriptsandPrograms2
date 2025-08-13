/*	$Id: clsDailyAddExchangeRates.h,v 1.1.2.6 1999/06/10 01:38:13 samuel Exp $	*/
//
//	File:	clsDailyAddExchangeRates.h
//
//	Class:	clsDailyAddExchangeRates
//
//	Author:	Samuel Au (samuel@ebay.com)
//
//	Function:
//
//		Add daily foreign currency exchange rates into database		mailer app. 
//
//
// Modifications:
//				- 05/05/99 samuel - Created
//
#ifndef CLSDAILYADDEXCHANGERATES_INCLUDED

/******************* Includes ********************/
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>

#include "clsApp.h"
#include "eBayTypes.h"
#include "clsDatabase.h"
#include "clsDatabaseOracle.h"

/******************* Defines *********************/

// need to change this if file size becomes bigger
#define MAX_FILE_SIZE 800000

// need to change this as the CurrencyList grows bigger,
// should be the same as number of currencies
const unsigned short MAX_NEW_RATES					= 43;

// Error codes
const unsigned short ERROR_CONNECTION_TO_BLOOMBERG	= 2;

// email sender
char * FROM_EMAIL = "samuel@ebay.com";

// email recipients, for testing purpose for now
char * TO_EMAIL_NORMAL = "au@efn.org";

// email subjects
char * SUBJECT_NORMAL = "Run Success";
char * SUBJECT_FILE_OPEN_ERROR = "File error: Not open";
char * SUBJECT_FILE_ERROR = "File error: FTP or read";
char * SUBJECT_DATABASE_CONNECT_ERROR = "Database error: connect database";
char * SUBJECT_ADD_TO_DATABASE_ERROR = "Database error: add to database";

// exchange rate file
#ifdef _MSC_VER
const char * FILE_EXCHANGE_RATE = "c:\\bloomberg\\Rates\\NEWFULL.out";
#else
const char * FILE_EXCHANGE_RATE = "~/curncy_namr.out";
#endif

// email contents
char * EMAIL_FILE_OPEN_ERROR = "Error: No file is open.\n";
char * EMAIL_FILE_ERROR = "Error: No content is read from exchange rate file.\n";
char * EMAIL_DATABASE_CONNECT_ERROR = "Error: Unable to connect to database \n";
char * EMAIL_ADD_TO_DATABASE_ERROR = "Error: Unable to add to database ebay_exchange_rates.\n";
char * EMAIL_RUNS_NORMAL = "DailyAddExchangeRates runs successfully!!!\n";


/******************* Typedefs ********************/

struct CurrencyCodeRecord
{
	char strCurrency[50];
	int  codeCurrency;
	bool toUSD;			// some spot rates are to USD from currency X, where some the
						// other way round; and Bloomberg has provided documentation on
						// which rate is in which direction
};


class clsDatabase;
class clsDatabaseOracle;

class clsDailyAddExchangeRates : public clsApp
{
public:
	clsDailyAddExchangeRates();
	~clsDailyAddExchangeRates() {};

	float ParseAndGet(char* string, char* currency);
	bool   AddRateToDB(int currency, float rate);
	void   Run();

private:
	clsDatabase *mpDatabase;
};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSDAILYADDEXCHANGERATES_INCLUDED 8
#endif 