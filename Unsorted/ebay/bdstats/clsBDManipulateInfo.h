/*	$Id: clsBDManipulateInfo.h,v 1.2 1999/02/21 02:30:28 josh Exp $	*/
//
// Class Name:		clsBDManipulateInfo
//
// Description:		Does sorts, partitions, merges, etc. for BD data.
//
// Author:			Chad Musick
//

#ifndef CLSBDMANIPULATEINFO_INCLUDE
#define CLSBDMANIPULATEINFO_INCLUDE

#include <time.h>

#include "vector.h"

#include "clsBDCategoryInfo.h"

//
// Information is merged in the following order to minimize merges:
//
// 1. Partners and Items are merged to give a partner id for every seller
//		[merge on partners.user = items.seller]
// 2. Partners and Bids are merged to give a partner id for every bidder
//		[merge on partners.user = bids.bidder]
// 3. Bids and Items are merged to give a partner id for every seller (in bids)
//		and a category id for every item (in bids), and a dutch bid quantity (in items)
//		[merge on bids.itemId = items.itemId]
// 4. Revenue and Items are merged to give a partner id for every user (in revenue)
//		and a category id for every transaction (in revenue)
//		[merge on Items.itemId = revenue.itemId]
// 5. The super category id is put into the total tally structure in the tally class,
//		and there not until the categories are aggregated. It's mentioned here for
//		the sake of being complete.
//

class clsBDManipulateInfo
{
public:

	// Returns false if cannot merge, usually from missing information
	static bool MergeInfo(vector<clsBDUserToPartnerInfo *> *pvPartners,
		vector<clsBDRevenueInfo *> *pvRevenue,
		vector<clsBDBidInfo *> *pvBids,
		vector<clsBDItemInfo *> *pvItems);

	static vector<clsBDItemInfo *>::iterator 
		GetAuctionsStartingBetween(time_t lowerTime, 
								   time_t upperTime,
								   vector<clsBDItemInfo *> *pvItems);

	static vector<clsBDItemInfo *>::iterator
		GetAuctionsEndBetween(time_t lowerTime,
						      time_t upperTime,
							  vector<clsBDItemInfo *> *pvItems);

	static vector<clsBDBidInfo *>::iterator
		GetBidsMadeBetween(time_t lowerTime,
						   time_t upperTime,
						   vector<clsBDBidInfo *> *pvBids);

	static vector<clsBDBidInfo *>::iterator
		GetBidsMadeOnItems(vector<clsBDItemInfo *>::iterator itemStart,
						   vector<clsBDItemInfo *>::iterator itemEnd,
						   vector<clsBDBidInfo *> *pvBids);

	static vector<clsBDItemInfo *>::iterator
		GetAuctionsOpenBetween(time_t lowerTime,
							   time_t upperTime,
							   vector<clsBDItemInfo *> *pvItems);

	static vector<clsBDRevenueInfo *>::iterator
		GetRevenueBetween(time_t lowerTime,
							   time_t upperTime,
							   vector<clsBDRevenueInfo *> *pvRevenue);
};

#endif /* CLSBDMANIPULATEINFO_INCLUDE */
