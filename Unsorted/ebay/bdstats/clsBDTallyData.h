/*	$Id: clsBDTallyData.h,v 1.2 1999/02/21 02:30:33 josh Exp $	*/
//
// Class Name:		clsBDTallyData
//
// Description:		Gets and tallies information.
//
// Author:			Chad Musick
//
#ifndef CLSBDTALLYDATA_INCLUDE
#define CLSBDTALLYDATA_INCLUDE

#include <time.h>

#include "vector.h"
#include "clsBDCategoryInfo.h"

class clsCategory;
class clsLogReadData;

class clsBDTallyData
{
public:
	clsBDTallyData(clsLogReadData *pLogReader);
	~clsBDTallyData();

	// Cogito ergo sum -- I think, therefore I add.
	void Tally();

	// Fills in all the information for the given time.
	// This erases any information it already has, too.
	void Initialize(time_t dayStart, time_t dayEnd);

	// Use this if you want to tally multiple times at once --
	// call initialize with the greatest span, then this
	// with all the spans to tally (followed by Tally(), of course)
	// (This _will_ erase all tallies, just not the information
	// beneath.
	void ResetTimeToTally(time_t dayStart, time_t dayEnd);

	// Store and clear.
	void StoreAndClearTallies();

	// Set the number of page views per category for the partner
	// The offset in the vector is the category number.
	void SetPageViews(int partner, vector<int> *pvViews);

private:
	// Various iterations of find functions.
	clsBDCategoryTally *FindCategoryTally(int partner, int category);
	clsBDCategoryTally *FindCategoryTally(vector<clsBDCategoryTally *> *pvPartner, 
										  int partner, int category);
	vector<clsBDCategoryTally *> *FindCategoryTallyVector(int partner);
	clsBDPartnerTally *FindPartnerTally(int partner);

	// Aggregate the information from sub-categories into their super categories.
	// The recursive function
	void AggregateOneCategory(clsCategory *pCategory);
	// This one starts the recursion.
	void AggregateCategories();

	time_t mDayStart;
	time_t mDayEnd;

	vector<clsBDUserToPartnerInfo *>	*mpvPartners;
	vector<clsBDRevenueInfo *>			*mpvRevenue;
	vector<clsBDBidInfo *>				*mpvBids;
	vector<clsBDItemInfo *>				*mpvItems;

	// Inner vector = categories; Outer vector = partners;
	vector<vector<clsBDCategoryTally *> *> *mpvTallies;

	vector<clsBDPartnerTally *> *mpvPartnerTallies;
	vector<clsCategory *> *mpvCategories;

	// The petulant object which perhaps makes sense
	// of server access logs -- we use it here to
	// get access counts.
	clsLogReadData *mpLogReader;
};

#endif /* CLSBDTALLYDATA_INCLUDE */
