/*	$Id: clsInternationalUtilities.h,v 1.2 1999/02/21 02:46:36 josh Exp $	*/
//
//	File:	clsCountries.h
//
//	Class:	clsCountries
//
//	Author: Barry Boone (barry@ebay.com)
//
//	Function:
//
//		Keep information about countries in a ca
//
// Modifications:
//				- 04/02/97 michael	- Created
//
#ifndef CLSINTERNATIONALUTILITIES_INCLUDED

#include "eBayTypes.h"

typedef vector<clsCountry *> CountryVector;

class clsInternationalUtilities
{
public:

	clsInternationalUtilities();
	~clsInternationalUtilities();

	// Format Information.
	char *FormatPhone(const char *part1, const char *part2, const char *part3, const char *part4);

	// Get and set member variables.
	void SetCurrentCountry(int id);
	void SetCurrentCountry(char* name);

private:

	// Index into the cache.
	int mCurrentCountry;
};

#define CLSINTERNATIONALUTILITIES_INCLUDED
#endif /* CLSINTERNATIONALUTILITIES_INCLUDED */