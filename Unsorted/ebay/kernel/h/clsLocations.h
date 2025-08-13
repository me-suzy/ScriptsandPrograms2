/*	$Id: clsLocations.h	*/
//
//	File:	clsLocations.h
//
//	Class:	clsLocations
//
//	Author:	Alex Poon
//
//	Function:
//
//			This class encapsulates location-related services.
//
// Modifications:
//				- 10/31/98 alex		- Created
//

#ifndef CLSLOCATIONS_INCLUDED

#include "eBayTypes.h"
#include "clsLocation.h"

#include <vector.h>

// Typedefs
typedef vector<clsLocation *> LocationVector;

// Class forward
class clsMarketPlace;

class clsLocations
{

	public:
		clsLocations(clsMarketPlace *pMarketPlace);
		~clsLocations();
		
		// All the services that clsLocations provides

		// existence checks
		bool IsValidZip(const char *targetZip) const;
		bool IsValidAC(int targetAC) const;
		bool IsValidCity(const char *targetCity) const;

		// correlations
		bool DoesACMatchZip(int ac, const char *zip) const;
		bool DoesACMatchState(int ac, const char *state) const;
		bool DoesZipMatchState(const char *zip, const char *state) const;
		bool DoesACMatchCity(int ac, const char *state) const;
		bool DoesZipMatchCity(const char *zip, const char *state) const;
		bool DoesCityMatchState(const char *city, const char *state) const;

		// distance lookups
		double DistanceZips(const char *zip1, const char *zip2) const;
		double DistanceACs(int ac1, int ac2) const;
		double DistanceZipAc(const char *zip, int ac) const;

		// lookups
		void GetLocationsByZip(LocationVector *pvLocations, const char *targetZip) const;
		void GetLocationsByAC(LocationVector *pvLocations, int targetAC) const;

		static const double INVALID_LL;
		static const double INVALID_DISTANCE;
		static const double PI;

	private:

		//
		// Parent MarketPlace
		//
		clsMarketPlace	*mpMarketPlace;

		// distance utilities
		// calculate great circle distance between two sets of lat/long
		double DistanceBetweenTwoLLPoints(double lat1, double lon1, double lat2, double lon2) const;

};

#define CLSLOCATIONS_INCLUDED 1
#endif CLSLOCATIONS_INCLUDED
