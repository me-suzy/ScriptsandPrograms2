/*	$Id: clsDatabaseOracleLocations.cpp	*/
//
//	File:	clsDatabaseOracleLocations.cc
//
//	Class:	clsDatabaseOracleLocations
//
//	Author:	Alex Poon
//
//	Function: Contains all database routines dealing with ebay_locations
//
//
// Modifications:
//				- 10/31/98 alex		- Created

#include "eBayKernel.h"

#include <string.h>
#include <stdio.h>
#include <fcntl.h>
#include <errno.h>
#include <time.h>
#include "clsUtilities.h"

// Interesting defines
#define PARSE_NO_DEFER	0
#define PARSE_DEFER		1
#define PARSE_V7_LNG	2


static const char *SQL_CountLocationsByZip =
"SELECT		count(ZIP) "
"FROM		ebay_locations "
"WHERE		ZIP = :zip";

static const char *SQL_CountLocationsByAreaCode =
"SELECT		count(AREACODE) "
"FROM		ebay_locations "
"WHERE		AREACODE = :ac";

static const char *SQL_CountLocationsByCity =
"SELECT		count(FASTCITY) "
"FROM		ebay_locations "
"WHERE		FASTCITY = :city";

/*
static const char *SQL_CountLocationsByCity =
"SELECT		count(CITY) "
"FROM		ebay_locations "
"WHERE		LOWER(TRANSLATE(CITY,'0 -','0')) = :city";
*/
 
bool clsDatabaseOracle::LocationsIsValidZip(const char *targetZip)
{
	int			count;

	// Open and parse the statement
	OpenAndParse(&mpCDALocationsIsValidZip, SQL_CountLocationsByZip);

	// Bind the input variables
	Bind(":zip", targetZip);

	// Define the output variables
	Define(1, &count);

	Execute();

	Fetch();

	// Close the curosr
	Close (&mpCDALocationsIsValidZip);
	SetStatement(NULL);

	return (count > 0);
}

bool clsDatabaseOracle::LocationsIsValidAC(int targetAC)
{
	int			count;

	// hack for 0
	if (targetAC == 0) return false;

	// Open and parse the statement
	OpenAndParse(&mpCDALocationsIsValidAC, SQL_CountLocationsByAreaCode);

	// Bind the input variables
	Bind(":ac", &targetAC);

	// Define the output variables
	Define(1, &count);

	Execute();

	Fetch();

	// Close the curosr
	Close (&mpCDALocationsIsValidAC);
	SetStatement(NULL);

	return (count > 0);
}


bool clsDatabaseOracle::LocationsIsValidCity(const char *targetCity)
{
	int			count;
	char		*lowerCaseCity;

	// make the city lowercase and no spaces, because that's how we'll compare
	//  them in the database
	lowerCaseCity = clsUtilities::StripNonAlphaNumsAndMakeLower(targetCity);

	// Open and parse the statement
	OpenAndParse(&mpCDALocationsIsValidCity, SQL_CountLocationsByCity);

	// Bind the input variables
	Bind(":city", lowerCaseCity);

	// Define the output variables
	Define(1, &count);

	Execute();

	Fetch();

	// Close the curosr
	Close (&mpCDALocationsIsValidCity);
	SetStatement(NULL);

	delete [] lowerCaseCity;

	return (count > 0);
}

static const char *SQL_CountLocationsByZipAndAreaCode =
"SELECT		count(AREACODE) "
"FROM		ebay_locations "
"WHERE		ZIP = :zip "
"AND		AREACODE = :ac";

static const char *SQL_CountLocationsByAreaCodeAndState =
"SELECT		count(STATE) "
"FROM		ebay_locations "
"WHERE		AREACODE = :ac "
"AND		STATE = :state";

static const char *SQL_CountLocationsByZipAndState =
"SELECT		count(STATE) "
"FROM		ebay_locations "
"WHERE		ZIP = :zip "
"AND		STATE = :state";

static const char *SQL_CountLocationsByAreaCodeAndCity =
"SELECT		count(AREACODE) "
"FROM		ebay_locations "
"WHERE		AREACODE = :ac "
"AND		FASTCITY = :city";

static const char *SQL_CountLocationsByZipAndCity =
"SELECT		count(ZIP) "
"FROM		ebay_locations "
"WHERE		ZIP = :zip "
"AND		FASTCITY = :city";

static const char *SQL_CountLocationsByCityAndState =
"SELECT		count(STATE) "
"FROM		ebay_locations "
"WHERE		STATE = :state "
"AND		FASTCITY = :city";

/*
static const char *SQL_CountLocationsByAreaCodeAndCity =
"SELECT		count(AREACODE) "
"FROM		ebay_locations "
"WHERE		AREACODE = :ac "
"AND		LOWER(TRANSLATE(CITY,'0 -','0')) = :city";

static const char *SQL_CountLocationsByZipAndCity =
"SELECT		count(ZIP) "
"FROM		ebay_locations "
"WHERE		ZIP = :zip "
"AND		LOWER(TRANSLATE(CITY,'0 -','0')) = :city";

static const char *SQL_CountLocationsByCityAndState =
"SELECT		count(STATE) "
"FROM		ebay_locations "
"WHERE		STATE = :state "
"AND		LOWER(TRANSLATE(CITY,'0 -','0')) = :city";
*/

bool clsDatabaseOracle::LocationsDoesACMatchZip(int ac, const char *zip)
{
	int			count;

	// Open and parse the statement
	OpenAndParse(&mpCDALocationsDoesACMatchZip, SQL_CountLocationsByZipAndAreaCode);

	// Bind the input variables
	Bind(":zip", zip);
	Bind(":ac", &ac);

	// Define the output variables
	Define(1, &count);

	Execute();

	Fetch();

	// Close the curosr
	Close (&mpCDALocationsDoesACMatchZip);
	SetStatement(NULL);

	return (count > 0);
}

bool clsDatabaseOracle::LocationsDoesACMatchState(int ac, const char *state)
{
	int			count;
	char		upperCaseState[64];

	// make the state uppercase, because that's the way it is in the database
	strncpy(upperCaseState, state, sizeof(upperCaseState)-1);
	clsUtilities::StringUpper(upperCaseState);

	// Open and parse the statement
	OpenAndParse(&mpCDALocationsDoesACMatchState, SQL_CountLocationsByAreaCodeAndState);

	// Bind the input variables
	Bind(":state", upperCaseState);
	Bind(":ac", &ac);

	// Define the output variables
	Define(1, &count);

	Execute();

	Fetch();

	// Close the curosr
	Close (&mpCDALocationsDoesACMatchState);
	SetStatement(NULL);

	return (count > 0);
}

bool clsDatabaseOracle::LocationsDoesZipMatchState(const char *zip, const char *state)
{
	int			count;
	char		upperCaseState[64];

	// make the state uppercase, because that's the way it is in the database
	strncpy(upperCaseState, state, sizeof(upperCaseState)-1);
	clsUtilities::StringUpper(upperCaseState);

	// Open and parse the statement
	OpenAndParse(&mpCDALocationsDoesZipMatchState, SQL_CountLocationsByZipAndState);

	// Bind the input variables
	Bind(":zip", zip);
	Bind(":state", upperCaseState);

	// Define the output variables
	Define(1, &count);

	Execute();

	Fetch();

	// Close the curosr
	Close (&mpCDALocationsDoesZipMatchState);
	SetStatement(NULL);

	return (count > 0);
}

bool clsDatabaseOracle::LocationsDoesACMatchCity(int ac, const char *city)
{
	int			count;
	char		*lowerCaseCity;

	// make the city lowercase and no spaces, because that's how we'll compare
	//  them in the database
	lowerCaseCity = clsUtilities::StripNonAlphaNumsAndMakeLower(city);

	// Open and parse the statement
	OpenAndParse(&mpCDALocationsDoesACMatchCity, SQL_CountLocationsByAreaCodeAndCity);

	// Bind the input variables
	Bind(":city", lowerCaseCity);
	Bind(":ac", &ac);

	// Define the output variables
	Define(1, &count);

	Execute();

	Fetch();

	// Close the curosr
	Close (&mpCDALocationsDoesACMatchCity);
	SetStatement(NULL);

	delete [] lowerCaseCity;

	return (count > 0);
}

bool clsDatabaseOracle::LocationsDoesZipMatchCity(const char *zip, const char *city)
{
	int			count;
	char		*lowerCaseCity;

	// make the city lowercase and no spaces, because that's how we'll compare
	//  them in the database
	lowerCaseCity = clsUtilities::StripNonAlphaNumsAndMakeLower(city);

	// Open and parse the statement
	OpenAndParse(&mpCDALocationsDoesZipMatchCity, SQL_CountLocationsByZipAndCity);

	// Bind the input variables
	Bind(":zip", zip);
	Bind(":city", lowerCaseCity);

	// Define the output variables
	Define(1, &count);

	Execute();

	Fetch();

	// Close the curosr
	Close (&mpCDALocationsDoesZipMatchCity);
	SetStatement(NULL);

	delete [] lowerCaseCity;

	return (count > 0);
}

bool clsDatabaseOracle::LocationsDoesCityMatchState(const char *city, const char *state)
{
	int			count;
	char		*lowerCaseCity;

	// make the city lowercase and no spaces, because that's how we'll compare
	//  them in the database
	lowerCaseCity = clsUtilities::StripNonAlphaNumsAndMakeLower(city);

	// Open and parse the statement
	OpenAndParse(&mpCDALocationsDoesCityMatchState, SQL_CountLocationsByCityAndState);

	// Bind the input variables
	Bind(":state", state);
	Bind(":city", lowerCaseCity);

	// Define the output variables
	Define(1, &count);

	Execute();

	Fetch();

	// Close the curosr
	Close (&mpCDALocationsDoesCityMatchState);
	SetStatement(NULL);

	delete [] lowerCaseCity;

	return (count > 0);
}

static const char *SQL_GetLLByZip =
"SELECT		AVG(LATITUDE), AVG(LONGITUDE) "
"FROM		ebay_locations "
"WHERE		ZIP = :zip ";


static const char *SQL_GetLLByAreaCode =
"SELECT		AVG(LATITUDE), AVG(LONGITUDE) "
"FROM		ebay_locations "
"WHERE		AREACODE = :ac ";

void clsDatabaseOracle::LocationsGetLLForZip(const char *zip, double *lat, double *lon)
{
	// in case we don't get anything back....
	*lat = *lon = clsLocations::INVALID_LL;

	// Open and parse the statement
	OpenAndParse(&mpCDALocationsGetLLForZip, SQL_GetLLByZip);

	// Bind the input variables
	Bind(":zip", zip);

	// Define the output variables
	Define(1, lat);
	Define(2, lon);

	Execute();

	Fetch();

	// just in case, for redundancy
	if (CheckForNoRowsFound())
		*lat = *lon = clsLocations::INVALID_LL;

	// Close the curosr
	Close (&mpCDALocationsGetLLForZip);
	SetStatement(NULL);
}

void clsDatabaseOracle::LocationsGetLLForAC(int ac, double *lat, double *lon)
{
	// in case we don't get anything back....
	*lat = *lon = clsLocations::INVALID_LL;

	// Open and parse the statement
	OpenAndParse(&mpCDALocationsGetLLForAC, SQL_GetLLByAreaCode);

	// Bind the input variables
	Bind(":ac", &ac);

	// Define the output variables
	Define(1, lat);
	Define(2, lon);

	Execute();

	Fetch();

	// just in case, for redundancy
	if (CheckForNoRowsFound())
		*lat = *lon = clsLocations::INVALID_LL;

	// Close the curosr
	Close (&mpCDALocationsGetLLForAC);
	SetStatement(NULL);
}