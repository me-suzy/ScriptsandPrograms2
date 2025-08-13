/*	$Id: clsLocation.h	*/
//
//	File:	clsLocation.h
//
//	Class:	clsLocation
//
//	Author:	Alex Poon
//
//	Function:
//
//		Representation of a location in ebay_locations
//
// Modifications:
//				- 10/31/98	 alex	- Created
//
//

#ifndef CLSLOCATION_INCLUDED
#include "eBayTypes.h"
#include "vector.h"

// class forward
class clsLocation;


#define CINT_VARIABLE(name)					\
private:									\
	int		m##name;						\
public:										\
	int		Get##name();					\
	void	Set##name(int new_value);

#define CSTRING_VARIABLE(name)				\
private:									\
	char	*mp##name;						\
public:										\
	char	*Get##name();					\
	void	Set##name(char *pNew);

#define  CBOOL_VARIABLE(name)				\
private:									\
	bool	m##name;						\
public:										\
	bool	Get##name();					\
	void	Set##name(bool new_value);

#define  CCHAR_VARIABLE(name)				\
private:									\
	char	m##name;						\
public:										\
	char	Get##name();					\
	void	Set##name(char new_value);

#define  CLONG_VARIABLE(name)				\
private:									\
	long	m##name;						\
public:										\
	long	Get##name();					\
	void	Set##name(long new_value);

#define  CDOUBLE_VARIABLE(name)				\
private:									\
	double	m##name;						\
public:										\
	double	Get##name();					\
	void	Set##name(double new_value);


class clsLocation
{

	public:
	//
	// vanilla ctor
	//
    clsLocation();

	// full blown constructor
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
				int source);

	// DTOR
	~clsLocation();

	CSTRING_VARIABLE(Zip);
	CSTRING_VARIABLE(City);
	CSTRING_VARIABLE(State);
	CSTRING_VARIABLE(County);
	CSTRING_VARIABLE(Country);

	CINT_VARIABLE(FIPS);			// FIPS county code
	CINT_VARIABLE(Areacode);
	CINT_VARIABLE(Tz);				// Timezone

	CBOOL_VARIABLE(Dst);			// Daylight savings time

	IDOUBLE_VARIABLE(Latitude);
	IDOUBLE_VARIABLE(Longitude);

	CCHAR_VARIABLE(Alias);			// Flag indicating main city name or alias

	CINT_VARIABLE(Source);			// Where we got this record

	
  private:
		
};

#define CLSLOCATION_INCLUDED 1
#endif CLSLOCATION_INCLUDED
