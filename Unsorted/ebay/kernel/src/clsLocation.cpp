/*	$Id: clsLocation.cpp,v 1.2 1999/02/21 02:47:42 josh Exp $	*/
//
//	File:	clsLocation.cpp
//
//	Class:	clsLocation
//
//	Author:	Alex Poon
//
//	Function:
//
//		Representation of a location in ebay_locations
//
//	Modifications:
//				- 10/31/98 alex	- Created
//

#include "eBayKernel.h"

// Some convienent macros

#define IINT_METHODS(variable)					\
int clsLocation::Get##variable()					\
{												\
	return m##variable;							\
}												\
void clsLocation::Set##variable(int newval)			\
{												\
	m##variable	= newval;						\
	return;										\
}												\

#define ISTRING_METHODS(variable)					\
char *clsLocation::Get##variable()					\
{													\
	return mp##variable;							\
}													\
void clsLocation::Set##variable(char *pNew)			\
{													\
	delete[] mp##variable;						\
	mp##variable = new char[strlen(pNew) + 1];		\
	strcpy(mp##variable, pNew);						\
	return;											\
}													\

#define IBOOL_METHODS(variable)						\
bool clsLocation::Get##variable()					\
{													\
	return m##variable;								\
}													\
void clsLocation::Set##variable(bool newval)		\
{													\
	m##variable	= newval;							\
	return;											\
} 

#define ICHAR_METHODS(variable)						\
char clsLocation::Get##variable()					\
{													\
	return m##variable;								\
}													\
void clsLocation::Set##variable(char newval)		\
{													\
	m##variable	= newval;							\
	return;											\
} 

#define ILONG_METHODS(variable)						\
long clsLocation::Get##variable()					\
{													\
	return m##variable;								\
}													\
void clsLocation::Set##variable(long newval)		\
{													\
	m##variable	= newval;							\
	return;											\
} 													

#define IDOUBLE_METHODS(variable)					\
double clsLocation::Get##variable()					\
{													\
	return m##variable;								\
}													\
void clsLocation::Set##variable(double newval)		\
{													\
	m##variable	= newval;							\
	return;											\
} 													

//
// Default Constructors
//
clsLocation::clsLocation() :
	mpZip(NULL),
	mpCity(NULL),
	mpState(NULL),
	mpCounty(NULL),
	mpCountry(NULL),
	mFIPS(0),
	mAreacode(0),
	mTz(0),
	mDst(false),
	mLatitude(0.0),
	mLongitude(0.0),
	mAlias('\0'),
	mSource(0)
{
}

//
// new and fill'er up
//
clsLocation::clsLocation(
				char *pZip,
				char *pCity,
				char *pState,
				char *pCounty,
				char *pCountry,
				int	fips,
				int areacode,
				int tz,
				bool dst,
				double latitude,
				double longitude,
				char alias,
				int source) :
	mpZip(pZip),
	mpCity(pCity),
	mpState(pState),
	mpCounty(pCounty),
	mpCountry(pCountry),
	mFIPS(fips),
	mAreacode(areacode),
	mTz(tz),
	mDst(dst),
	mLatitude(latitude),
	mLongitude(longitude),
	mAlias(alias),
	mSource(source)
{
}

//
// Destructor
//
clsLocation::~clsLocation()
{
	delete []	mpZip;
	delete []	mpCity;
	delete []	mpState;
	delete []	mpCounty;
	delete []	mpCountry;
}


// standard getters and setters

  ISTRING_METHODS(Zip);
  ISTRING_METHODS(City);
  ISTRING_METHODS(State);
  ISTRING_METHODS(County);
  ISTRING_METHODS(Country);

  IINT_METHODS(FIPS);
  IINT_METHODS(Areacode);
  IINT_METHODS(Tz);

  IBOOL_METHODS(Dst);

  IDOUBLE_METHODS(Latitude);
  IDOUBLE_METHODS(Longitude);

  ICHAR_METHODS(Alias);

  IINT_METHODS(Source);

