/*	$Id: clseBayAppAdminAddExchangeRate.cpp,v 1.3.136.1 1999/08/01 02:51:38 barry Exp $	*/
//
//	File:		clseBayAppAdminAddExchangeRate.cpp
//
//	Class:		clseBayApp
//
//	Author:		Samuel Au (samuel@ebay.com)
//
//	Function:
//
//				Add exchange rate to ebay_exchange_rates table
//				through the web interface
//
//	Modifications:
//				- 04/20/99 samuel	- Created
//				- 05/21/99 nsacco	- Added Australian dollars and German marks
//

#include "ebihdr.h"
#include "clsExchangeRates.h"

void clseBayApp::AdminAddExchangeRate(const char *login,
										const char *password,
										int			month,
										int			day,
										int			year,
										int			fromcurrency,
										int			tocurrency,
										const char *rate)
{
	time_t			  now;
	time_t			  tomorrow;
	time_t			  intime;
	struct tm*		  compareTime;
	double			  inrate;
	double			  last_rate;
	double			  upper;
	double			  lower;
	clsExchangeRates *pexchangerates;
	const char		 *adminpass;
	char			 *fromstring;

	SetUp();

	// Emit header
	EmitHeader("Add New Exchange Rate");

	adminpass = mpMarketPlace->GetAdminSpecialPassword();

	// Check if the login/password pair is authorized
	if (strcmp(adminpass, password) != 0)
	{
		*mpStream	<< "\r\n<p><font color=red>"
					<< "<h3>Incorrect Password.</h3></font></p>\r\n"
					<< "<br>\r\n"
					<< "<p>Please click <b>BACK</b> to try again.</p>\r\n"
					<< flush;
		CleanUp();
		return;
	}

	// Set up time variables
	now = time(0);
	clsUtilities::TimeToMidnight(&now);
	tomorrow = now + (24 * 60 * 60);

	compareTime = localtime(&now);     // to give some value to compareTime
	compareTime->tm_year  = year;
	compareTime->tm_mon   = month - 1; //note
	compareTime->tm_mday  = day;
	compareTime->tm_hour  = 0;
	compareTime->tm_min   = 0;
	compareTime->tm_sec   = 0;

	intime = mktime(compareTime);

	// Check if the new rate is for a date later than tomorrow
	if (intime > tomorrow)
	{
		*mpStream	<< "\r\n<p><font color=red>"
					<< "<h3>No new rate is allowed after the date tomorrow"
					<< " from now.</h3></font></p>\r\n"
					<< "<br>\r\n"
					<< "<p>Please click <b>BACK</b> to try again.</p>\r\n"
					<< flush;
		CleanUp();
		return;
	} 

	// find if rate exists
	pexchangerates = mpMarketPlace->GetCurrencies()->GetExchangeRates();

	// Check if the new rate is within 20% of the previous day's rate
	last_rate = pexchangerates->GetExchangeRate(intime, fromcurrency, tocurrency);
	inrate = atof(rate);
	if (last_rate != 1.00000000000)
	{
		upper = last_rate * 1.200000000000000000;
		lower = last_rate * 0.800000000000000000;
		
		if (inrate > upper || inrate < lower)
		{
			*mpStream	<< "\r\n<p><font color=red>"
						<< "<h3>No new rate is allowed if it falls beyond"
						<< " 20% of old rate.</h3></font></p>\r\n"
						<< "<br>\r\n"
						<< "<p>Please click <b>BACK</b> to try again.</p>\r\n"
						<< flush;
			CleanUp();
			return;
		}											
	}

	if (!mpDatabase->InsertExchangeRateRecord(intime, fromcurrency, inrate))
	{
		// print some error saying not able to add to database
		*mpStream	<< "\r\n<font color=red>\r\n<p>"
					<< "<h3>Error in adding to database."
					<< "</h3></p>\r\n"
					<< "<p>Possible error: the same day's rate has been added."
					<< "</p>\r\n</font>\r\n"
					<< "<br>\r\n"
					<< "<p>Please click <b>BACK</b> to try again.</p>\r\n"
					<< flush;
		// NOTE: basically, if database won't allow to add a record, 
		//       it will always send a trap and the MYCATCH will get 
		//		 it before entering this loop.
	} 

	*mpStream   << "\r\n<p><font color=green><h3>"
				<< "Thanks for adding new exchange rate!</h3></font></p>\r\n";

	switch (fromcurrency)
	{
	case Currency_CAD:
						fromstring = "Canadian Dollars (CAD)";
						break;

	case Currency_GBP:  
						fromstring = "British Pounds (GBP)";
						break;

	// nsacco 05/21/99 added DEM and AUD
	case Currency_DEM:
						fromstring = "German Marks (DEM)";
						break;

	case Currency_AUD:
						fromstring = "Australian Dollars (AUD)";
						break;
	// nsacco 07/13/99
	case Currency_FRF:
						fromstring = "French francs (FRF)";
						break;
	case Currency_JPY:
						fromstring = "Japanese yen (JPY)";
						break;
	case Currency_EUR:
						fromstring = "Euros (EUR)";
						break;
	case Currency_SEK:
						fromstring = "Swedish Kronor (SEK)";
						break;
	case Currency_CNY:
						fromstring = "Chinese yuan (CNY)";
						break;
	case Currency_ESP:
						fromstring = "Spanish Peseta (ESP)";
						break;
	case Currency_NOK:
						fromstring = "Norwegian Krone (NOK)";
						break;
	case Currency_DKK:
						fromstring = "Danish Krones (DKK)";
						break;
	case Currency_FIM:
						fromstring = "Finnish Marrkas (FIM)";
						break;

	default:	
						fromstring = "British Pounds (GBP)";
	}

	*mpStream	<< "<p><font color=blue>"
				<< "<b>The new rate you have added:</p>\r\n"
				<< "<p>From: " << fromstring << "</p>\r\n"
				<< "<p>To  : US Dollars (USD)</p>\r\n"
				<< "<p>On " << month << "/" << day << "/" << year+1900 << "</p>\r\n"
				<< "<p>New Rate: " << rate << "</p></b>\r\n"
				<< flush;

	CleanUp();
} 


