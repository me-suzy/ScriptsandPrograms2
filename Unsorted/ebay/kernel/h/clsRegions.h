/*	$Id: clsRegions.h,v 1.2 1999/04/18 01:59:11 wwen Exp $	*/
//
//	File:	clsRegions.h
//
//	Class:	clsRegions
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Handles regional related fuctions.
//				It is used to cache the zips.
//
//		Usage:
//			(1) Create the object cldsRegions
//			(2) Call Initialize() which retrieve the all the region information
//				currently stored in database.
//			(3) You can then get clsRegion Object or region id using a zip code
//			(4) You can also to get the clsRegion object by providing a region id.
//
//	normally you done call SetRegions() which is called in the database function.
//
// Modifications:
//				- 04/14/99 wen	- Created

#ifndef CLSREGIONS_INCLUDE
#define CLSREGIONS_INCLUDE

#include "vector.h"

class clsRegion;

class clsRegions
{
public:

	// constructor
	clsRegions() { mpRegions = NULL;}

	// destructor
	~clsRegions()
	{
		Cleanup();
	}

	// store a vector of regions normally retrieve from the database
	// clsRegions is responsible to release pRegion and its content
	void SetRegions(vector<clsRegion*>* pRegions);

	// get the region object of the zip belongs to
	clsRegion* GetRegion(const char* pZip);

	// get region by region id
	clsRegion* GetRegion(int RegionID);

	// Get region id
	int GetRegionID(const char* pZip);

	// initialzie (fill the region information from DB)
	void Initialize();
	
protected:

	// cleanup
	void Cleanup();

	// member variables
	vector<clsRegion*>*	mpRegions;
};

#endif // CLSREGIONS_INCLUDE
