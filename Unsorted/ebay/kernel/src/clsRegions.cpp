/*	$Id: clsRegions.cpp,v 1.2 1999/04/18 01:59:20 wwen Exp $	*/
//
//	File:	clsRegions.cpp
//
//	Class:	clsRegions
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Handle regional related functions.
//				It is used to cache the zips.
//
// Modifications:
//				- 04/14/9 9 wen	- Created

#include "eBayKernel.h"
#include "clsRegion.h"
#include "clsRegions.h"

// clsRegions is responsible to release pRegion and its content
void clsRegions::SetRegions(vector<clsRegion*>* pRegions)
{
	// cleanup before setting a new one
	Cleanup();

	// keep it
	mpRegions = pRegions;
}


// get the region object of the zip belongs to
clsRegion* clsRegions::GetRegion(const char* pZip)
{
	int		i;

	if (pZip == NULL || !mpRegions || mpRegions->size() == 0)
		return NULL;

	// check whether we find the match for the zip
	for (i = 0; i < mpRegions->size(); i++)
	{
		if ((*mpRegions)[i]->IsInRegion(pZip))
		{
			// found it
			return (*mpRegions)[i];
		}
	}

	// no
	return NULL;
}

// Get region id based on zip
int clsRegions::GetRegionID(const char* pZip)
{
	clsRegion* pRegion = GetRegion(pZip);

	if (pRegion)
		return pRegion->GetID();

	return 0;
}

// Get region based on the region ID (code)
clsRegion* clsRegions::GetRegion(int RegionID)
{
	int		i;

	if (RegionID == Region_None || !mpRegions || mpRegions->size() == 0)
		return NULL;

	// check whether we find a match for the ID
	for (i = 0; i < mpRegions->size(); i++)
	{
		if ((*mpRegions)[i]->GetID() == RegionID)
		{
			// found it
			return (*mpRegions)[i];
		}
	}

	// no
	return NULL;
}

// fill the information from the database
void clsRegions::Initialize()
{
	gApp->GetDatabase()->GetAllRegionsAndZips(this);
}

// release the memory
void clsRegions::Cleanup()
{		
	int	i;

	if (mpRegions)
	{
		for (i = 0; i < mpRegions->size(); i++)
			delete (*mpRegions)[i];

		mpRegions->erase(mpRegions->begin(), mpRegions->end());

		delete mpRegions;
		mpRegions = NULL;
	}
}
