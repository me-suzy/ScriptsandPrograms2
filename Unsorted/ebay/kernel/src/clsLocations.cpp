/*	$Id: clsLocations.cpp,v 1.2 1999/02/21 02:47:43 josh Exp $	*/
//
//	File:	clsLocations.cpp
//
//	Class:	clsLocations
//
//	Author:	Alex Poon
//
//	Function:
//
//			This class encapsulates location-related services.
//
//	Modifications:
//				  mm/dd/yy
//				- 10/31/98 alex	- Created

#include "eBayKernel.h"
#include <math.h>

const double clsLocations::INVALID_LL = -999.99;
const double clsLocations::INVALID_DISTANCE = -999.99;
const double clsLocations::PI = 3.141592654;

//
// Default Constructor
//
clsLocations::clsLocations(clsMarketPlace *pMarketPlace)
{
	mpMarketPlace	= pMarketPlace;
}

//
// Destructor
//
clsLocations::~clsLocations()
{
}

// existence checks
bool clsLocations::IsValidZip(const char *targetZip) const
{
	return gApp->GetDatabase()->LocationsIsValidZip(targetZip);
}

bool clsLocations::IsValidAC(int targetAC) const
{
	return gApp->GetDatabase()->LocationsIsValidAC(targetAC);
}

bool clsLocations::IsValidCity(const char *targetCity) const
{
	return gApp->GetDatabase()->LocationsIsValidCity(targetCity);
}


// correlations
bool clsLocations::DoesACMatchZip(int ac, const char *zip) const
{
	return gApp->GetDatabase()->LocationsDoesACMatchZip(ac, zip);
}

bool clsLocations::DoesACMatchState(int ac, const char *state) const
{
	return gApp->GetDatabase()->LocationsDoesACMatchState(ac, state);
}

bool clsLocations::DoesZipMatchState(const char *zip, const char *state) const
{
	return gApp->GetDatabase()->LocationsDoesZipMatchState(zip, state);
}

bool clsLocations::DoesACMatchCity(int ac, const char *city) const
{
	return gApp->GetDatabase()->LocationsDoesACMatchCity(ac, city);
}

bool clsLocations::DoesZipMatchCity(const char *zip, const char *city) const
{
	return gApp->GetDatabase()->LocationsDoesZipMatchCity(zip, city);
}

bool clsLocations::DoesCityMatchState(const char *city, const char *state) const
{
	return gApp->GetDatabase()->LocationsDoesCityMatchState(city, state);
}



// distance lookups
double clsLocations::DistanceZips(const char *zip1, const char *zip2) const
{
	double lat1, lon1, lat2, lon2;

	// get the LL's for the two zips
	gApp->GetDatabase()->LocationsGetLLForZip(zip1, &lat1, &lon1);
	gApp->GetDatabase()->LocationsGetLLForZip(zip2, &lat2, &lon2);

	// if LL data isn't available, return INVALID_DISTANCE
	if (lat1 == INVALID_LL || lon1 == INVALID_LL || lat2 == INVALID_LL || lon2 == INVALID_LL)
		return INVALID_DISTANCE;

	// do it
	return (DistanceBetweenTwoLLPoints(lat1, lon1, lat2, lon2));

}

double clsLocations::DistanceACs(int ac1, int ac2) const
{
	double lat1, lon1, lat2, lon2;

	// get the LL's for the two areacodes
	gApp->GetDatabase()->LocationsGetLLForAC(ac1, &lat1, &lon1);
	gApp->GetDatabase()->LocationsGetLLForAC(ac2, &lat2, &lon2);

	// if LL data isn't available, return INVALID_DISTANCE
	if (lat1 == INVALID_LL || lon1 == INVALID_LL || lat2 == INVALID_LL || lon2 == INVALID_LL)
		return INVALID_DISTANCE;

	// do it
	return (DistanceBetweenTwoLLPoints(lat1, lon1, lat2, lon2));

}

double clsLocations::DistanceZipAc(const char *zip, int ac) const
{
	double lat1, lon1, lat2, lon2;

	// get the LL's 
	gApp->GetDatabase()->LocationsGetLLForZip(zip, &lat1, &lon1);
	gApp->GetDatabase()->LocationsGetLLForAC(ac, &lat2, &lon2);

	// if LL data isn't available, return INVALID_DISTANCE
	if (lat1 == INVALID_LL || lon1 == INVALID_LL || lat2 == INVALID_LL || lon2 == INVALID_LL)
		return INVALID_DISTANCE;

	// do it
	return (DistanceBetweenTwoLLPoints(lat1, lon1, lat2, lon2));

}

// lookups
void clsLocations::GetLocationsByZip(LocationVector *pvLocations, const char *targetZip) const
{
	// NOT YET IMPLEMENTED (not yet needed)
	return;
}

void clsLocations::GetLocationsByAC(LocationVector *pvLocations, const int targetAC) const
{
	// NOT YET IMPLEMENTED (not yet needed)
	return;
}

// distance utilities
// calculate great circle distance between two sets of lat/long
// LL's are given in degrees. Results is in miles.
// Algorithm gotten from http://www.auslig.gov.au/geodesy/calcs.htm.
double clsLocations::DistanceBetweenTwoLLPoints(double lat1, double lon1, double lat2, double lon2) const
{
	double radDistance;
	double degDistance;
	double milesDistance;
		
	// convert everything from degrees to radians
	lat1 = (lat1/180)*PI;
	lon1 = (lon1/180)*PI;
	lat2 = (lat2/180)*PI;
	lon2 = (lon2/180)*PI;

	// calculate distance in radians
	radDistance = acos(sin(lat1)*sin(lat2) + cos(lat1)*cos(lat2)*cos(lon2-lon1));

	// convert back to degrees
	degDistance = (radDistance/PI)*180;

	// convert to miles
	milesDistance = degDistance*1.151*60;

	return milesDistance;
}
