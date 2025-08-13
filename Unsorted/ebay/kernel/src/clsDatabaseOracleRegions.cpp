/*	$Id: clsDatabaseOracleRegions.cpp,v 1.3 1999/04/28 05:35:20 josh Exp $	*/
//
//	File:	clsDatabaseOracleRegions.cpp
//
//	Class:	clsDatabaseOracleRegions
//
//	Author:	Barry Boone (barry@ebay.com)
//
//	Function:
//  Database access specific to regional auctions.
//
// Modifications:
//				- 4/15/99 wen - created

#include "eBayKernel.h"
#include "clsRegion.h"
#include "clsRegions.h"

//
//  GetAllRegions
//
//	This routine gets all regions at once.
//

// How many Regions to get at once.
#define ORA_REGIONS_ARRAYSIZE 10

static const char *SQL_GetAllRegions =
"select	region_id, name from ebay_region_info";

// get all region info
void clsDatabaseOracle::GetAllRegionInfo(vector<clsRegion*>* pvRegions)
{
	int         pRegionId[ORA_REGIONS_ARRAYSIZE];
	char		ppRegionName[ORA_REGIONS_ARRAYSIZE][51];

	int			rowsFetched;
	int			rc;
	int			n;
	int			i;

	clsRegion	*pRegion;

	// Get ready.
	OpenAndParse(&mpCDAGetAllRegions, SQL_GetAllRegions);

	// Load 
	Define(1, pRegionId);
	Define(2, (char *)&ppRegionName, sizeof(ppRegionName[0]));

	Execute();

	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDAGetAllRegions, ORA_REGIONS_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDAGetAllRegions)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDAGetAllRegions);
			Close(&mpCDAGetAllRegions, true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to take care of time 
		// (always <= ORA_Regions_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDAGetAllRegions)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			pRegion = new clsRegion(pRegionId[i], ppRegionName[i]);
			pvRegions->push_back(pRegion);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetAllRegions);
	SetStatement(NULL);

	return;	
}


// How many zips to get at once
#define ORA_ZIP_ARRAYSIZE 1000

static const char *SQL_GetRegionZips =
"select	zip from ebay_regions "
"where region_id=:region_id";

// get zips for a region
//
void clsDatabaseOracle::GetAllRegionsAndZips(clsRegions* pRegions)
{
	char		ppZips[ORA_ZIP_ARRAYSIZE][51];

	int			rowsFetched;
	int			rc;
	int			n;
	int			i;
	int			RegionId;
	clsRegion*	pRegion;

	vector<clsRegion*>*	pvRegionVector = new vector<clsRegion*>;

	// Get all existing region info
	GetAllRegionInfo(pvRegionVector);

	// get zips for each region
	for (i = 0; i < pvRegionVector->size(); i++)
	{
		pRegion  = (*pvRegionVector)[i];
		RegionId = pRegion->GetID();

		// Get ready.
		OpenAndParse(&mpCDAGetRegionZips, SQL_GetRegionZips);

		Bind(":region_id", &RegionId);

		// Load 
		Define(1, (char *)&ppZips, sizeof(ppZips[0]));

		Execute();

		rowsFetched = 0;
		do
		{
			rc = ofen((struct cda_def *)mpCDAGetRegionZips, ORA_ZIP_ARRAYSIZE);

			if ((rc < 0 || rc >= 4)  && 
				((struct cda_def *)mpCDAGetRegionZips)->rc != 1403)	// something wrong
			{
				Check(rc);
				ocan((struct cda_def *)mpCDAGetRegionZips);
				Close(&mpCDAGetRegionZips, true);
				SetStatement(NULL);
				return;
			}

			// rpc is cumulative, so find out how many rows to take care of time 
			// (always <= ORA_ZIP_ARRAYSIZE). 
			n = ((struct cda_def *)mpCDAGetRegionZips)->rpc - rowsFetched;
			rowsFetched += n;

			for (i=0; i < n; i++)
			{
				pRegion->AddZip(ppZips[i]);
			}

		} while (!CheckForNoRowsFound());

		Close(&mpCDAGetRegionZips);
		SetStatement(NULL);
	}

	// the regions are filled. keep them
	pRegions->SetRegions(pvRegionVector);

	return;	
}



