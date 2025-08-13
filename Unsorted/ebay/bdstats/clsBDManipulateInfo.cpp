/*	$Id: clsBDManipulateInfo.cpp,v 1.2 1999/02/21 02:30:27 josh Exp $	*/
//
// Class Name:		clsBDManipulateInfo
//
// Description:		Does various utility functions to the
//					structures used to store business dev
//					information.
//
// Author:			Chad Musick
//
#include "clsBDManipulateInfo.h"
#include "algo.h"

// Sort functions
static bool s_SortPartnersById(clsBDUserToPartnerInfo *p1, 
							   clsBDUserToPartnerInfo *p2)
{
	return p1->mUserId < p2->mUserId;
}

static bool s_SortItemsBySeller(clsBDItemInfo *p1,
								clsBDItemInfo *p2)
{
	return p1->mSellerId < p2->mSellerId;
}

static bool s_SortBidsByBidder(clsBDBidInfo *p1,
							   clsBDBidInfo *p2)
{
	return p1->mBidderId < p2->mBidderId;
}

static bool s_SortItemsByItemId(clsBDItemInfo *p1,
								clsBDItemInfo *p2)
{
	return p1->mItemId < p2->mItemId;
}

static bool s_SortBidsByItemId(clsBDBidInfo *p1,
							   clsBDBidInfo *p2)
{
	if (p1->mItemId != p2->mItemId)
		return p1->mItemId < p2->mItemId;

	if (p1->mBidderId != p2->mBidderId)
		return p1->mBidderId < p2->mBidderId;

	// Sort in reverse date order.
	return p1->mCreated > p2->mCreated;
}

static bool s_SortRevenueByItemId(clsBDRevenueInfo *p1,
								  clsBDRevenueInfo *p2)
{
	return p1->mItemId < p2->mItemId;
}

// Partition classes
class s_clsPartitionBDByDate
{
public:
	time_t mLowerDate;
	time_t mUpperDate;
	int mPartitionType;

	s_clsPartitionBDByDate(time_t lowerDate, time_t upperDate, int partitionType)
	{ mLowerDate = lowerDate; mUpperDate = upperDate; 
	  mPartitionType = partitionType; return; }

	bool operator()(clsBDItemInfo *p);
};

bool s_clsPartitionBDByDate::operator()(clsBDItemInfo *p)
{
	switch (mPartitionType)
	{
	case 0:
		return p->mSaleStart >= mLowerDate && p->mSaleStart < mUpperDate;
	case 1:
		return p->mSaleEnd >= mLowerDate && p->mSaleEnd < mUpperDate;
	case 2:
		return p->mSaleEnd >= mLowerDate && p->mSaleStart < mUpperDate;
	default:
		return 0;
	}
}

class s_clsPartitionBDBidByDate
{
public:
	time_t mLowerDate;
	time_t mUpperDate;
	int mPartitionType;

	s_clsPartitionBDBidByDate(time_t lowerDate, time_t upperDate, int partitionType)
	{ mLowerDate = lowerDate; mUpperDate = upperDate; 
	  mPartitionType = partitionType; return; }

	bool operator()(clsBDBidInfo *p);
};

bool s_clsPartitionBDBidByDate::operator()(clsBDBidInfo *p)
{
	return p->mCreated >= mLowerDate && p->mCreated < mUpperDate;
}

class s_clsPartitionBDRevenueByDate
{
public:
	time_t mLowerDate;
	time_t mUpperDate;
	int mPartitionType;

	s_clsPartitionBDRevenueByDate(time_t lowerDate, time_t upperDate, int partitionType)
	{ mLowerDate = lowerDate; mUpperDate = upperDate; 
	  mPartitionType = partitionType; return; }

	bool operator()(clsBDRevenueInfo *p);
};

bool s_clsPartitionBDRevenueByDate::operator()(clsBDRevenueInfo *p)
{
	return p->mChargedWhen >= mLowerDate && p->mChargedWhen < mUpperDate;
}

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
//

bool clsBDManipulateInfo::MergeInfo(vector<clsBDUserToPartnerInfo *> *pvPartners,
							   vector<clsBDRevenueInfo *> *pvRevenue,
							   vector<clsBDBidInfo *> *pvBids,
							   vector<clsBDItemInfo *> *pvItems)
{
	vector<clsBDUserToPartnerInfo *>::iterator	iPartners, iPartnersEnd;
	vector<clsBDRevenueInfo *>::iterator		iRevenue;
	vector<clsBDBidInfo *>::iterator			iBids, iBidsDuplicateUser;
	vector<clsBDItemInfo *>::iterator			iItems, iItemsEnd;

	if (!pvPartners	||
		!pvRevenue	||
		!pvBids		||
		!pvItems)
		return false;

	sort(pvPartners->begin(), pvPartners->end(), s_SortPartnersById);
	sort(pvItems->begin(), pvItems->end(), s_SortItemsBySeller);
	
	iPartners = pvPartners->begin();
	iPartnersEnd = pvPartners->end();

	// Merge stage 1.
	for (iItems = pvItems->begin(); 
		iItems != pvItems->end() && iPartners != iPartnersEnd; ++iItems)
	{
		while (iPartners != iPartnersEnd &&
			(*iPartners)->mUserId < (*iItems)->mSellerId)
			++iPartners;

		if ((*iPartners)->mUserId == (*iItems)->mSellerId)
			(*iItems)->mPartnerId = (*iPartners)->mPartnerId;
		else
			(*iItems)->mPartnerId = 0;
	}

	// Partners are already sorted
	sort(pvBids->begin(), pvBids->end(), s_SortBidsByBidder);
	iPartners = pvPartners->begin();

	// Merge stage 2.
	for (iBids = pvBids->begin();
		iBids != pvBids->end() && iPartners != iPartnersEnd; ++iBids)
	{
		while (iPartners != iPartnersEnd &&
			(*iPartners)->mUserId < (*iBids)->mBidderId)
			++iPartners;

		if ((*iPartners)->mUserId == (*iBids)->mBidderId)
			(*iBids)->mBidderPartnerId = (*iPartners)->mPartnerId;
		else
			(*iBids)->mBidderPartnerId = 0;
	}

	sort(pvItems->begin(), pvItems->end(), s_SortItemsByItemId);
	sort(pvBids->begin(), pvBids->end(), s_SortBidsByItemId);

	iItemsEnd = pvItems->end();
	iItems = pvItems->begin();

	// Merge stage 3.
	for (iBids = pvBids->begin();
		iBids != pvBids->end() && iItems != iItemsEnd; ++iBids)
	{
		while (iItems != iItemsEnd &&
			(*iItems)->mItemId < (*iBids)->mItemId)
			++iItems;

		if ((*iItems)->mItemId == (*iBids)->mItemId)
		{
			// Wow, this is ugly! If there's more than one bid on a dutch
			// auction by the same user, we take the most recent one
			// (Sorting done in s_SortBidsByItemId) and delete the rest.
			if ((*iItems)->mQuantity > 1)
			{
				iBidsDuplicateUser = iBids + 1;
				while (iBidsDuplicateUser != pvBids->end() &&
					(*iBidsDuplicateUser)->mBidderId == (*iBids)->mBidderId)
					++iBidsDuplicateUser;
				if (iBidsDuplicateUser != (iBids + 1))
				{
					if (iBidsDuplicateUser == pvBids->end())
						pvBids->erase(iBids + 1, pvBids->end());
					else
						pvBids->erase(iBids + 1, iBidsDuplicateUser + 1);
				}
			}
				
			(*iBids)->mSellerPartnerId = (*iItems)->mPartnerId;
			(*iBids)->mCategoryId = (*iItems)->mCategoryId;
			if ((*iItems)->mQuantity > 1)
				(*iItems)->mNumBidsOnDutch += (*iBids)->mQuantity;
		}
		else
		{
			(*iBids)->mSellerPartnerId = 0;
			(*iBids)->mCategoryId = 0;
		}
	}

	// Items are already sorted
	sort(pvRevenue->begin(), pvRevenue->end(), s_SortRevenueByItemId);

	iItems = pvItems->begin();

	// Merge stage 4.
	for (iRevenue = pvRevenue->begin();
		iRevenue != pvRevenue->end() && iItems != iItemsEnd; ++iRevenue)
	{
		while (iItems != iItemsEnd &&
			(*iItems)->mItemId < (*iRevenue)->mItemId)
			++iItems;

		if ((*iItems)->mItemId == (*iRevenue)->mItemId)
		{
			(*iRevenue)->mUserPartnerId = (*iItems)->mPartnerId;
			(*iRevenue)->mCategoryId = (*iItems)->mCategoryId;
		}
		else
		{
			(*iRevenue)->mUserPartnerId = 0;
			(*iRevenue)->mCategoryId = 0;
		}
	}

	return true;
}

// GetAuctionsStartingBetween
// Return the upper bound of auction which were started between
// lowerTime (inclusive) and upperTime (exclusive) from
// pvItems.
vector<clsBDItemInfo *>::iterator
clsBDManipulateInfo::GetAuctionsStartingBetween(time_t lowerTime,
												time_t upperTime,
												vector<clsBDItemInfo *> *pvItems)
{
	return partition(pvItems->begin(), pvItems->end(), 
		s_clsPartitionBDByDate(lowerTime, upperTime, 0));
}

// GetAuctionsEndBetween 
// Return the upper bound of auctions which were ended between
// lowerTime (inclusive) and upperTime (exclusive) from
// pvItems.
vector<clsBDItemInfo *>::iterator
clsBDManipulateInfo::GetAuctionsEndBetween(time_t lowerTime,
										   time_t upperTime,
										   vector<clsBDItemInfo *> *pvItems)
{
	return partition(pvItems->begin(), pvItems->end(),
		s_clsPartitionBDByDate(lowerTime, upperTime, 1));
}

// GetAuctionsMadeBetween
// Return the upper bound of bids which were made between
// lowerTime (inclusive) and upperTime (exclusive) from
// pvItems.
vector<clsBDBidInfo *>::iterator
clsBDManipulateInfo::GetBidsMadeBetween(time_t lowerTime,
										time_t upperTime,
										vector<clsBDBidInfo *> *pvBids)
{
	return partition(pvBids->begin(), pvBids->end(),
		s_clsPartitionBDBidByDate(lowerTime, upperTime, 0));
}

// GetBidsMadeOnItems
// Given itemStart, itemEnd, and pvBids, return the upper
// bound in pvBids of those bids which refer to auctions
// in the range itemStart (inclusive) to itemEnd (exclusive)
vector<clsBDBidInfo *>::iterator
clsBDManipulateInfo::GetBidsMadeOnItems(vector<clsBDItemInfo *>::iterator itemStart,
										vector<clsBDItemInfo *>::iterator itemEnd,
										vector<clsBDBidInfo *> *pvBids)
{
	vector<clsBDBidInfo *> vBidsYes;
	vector<clsBDBidInfo *> vBidsNo;
	int numMatched;

	vector<clsBDBidInfo *>::iterator			iBids;

	// First sort them.
	sort(itemStart, itemEnd, s_SortItemsByItemId);

	sort(pvBids->begin(), pvBids->end(), s_SortBidsByItemId);
    
	for (iBids = pvBids->begin();
		iBids != pvBids->end() && itemStart != itemEnd; ++iBids)
	{
		while (itemStart != itemEnd &&
			(*itemStart)->mItemId < (*iBids)->mItemId)
			++itemStart;

		if ((*itemStart)->mItemId == (*iBids)->mItemId)
			vBidsYes.push_back(*iBids);
		else
			vBidsNo.push_back(*iBids);
	}
	
	numMatched = vBidsYes.size();

	pvBids->erase(pvBids->begin(), pvBids->end());
	pvBids->insert(pvBids->end(), vBidsYes.begin(), vBidsYes.end());
	pvBids->insert(pvBids->end(), vBidsNo.begin(), vBidsNo.end());

	vBidsYes.erase(vBidsYes.begin(), vBidsYes.end());
	vBidsNo.erase(vBidsNo.begin(), vBidsNo.end());

	return pvBids->begin() + numMatched;
}

// GetAuctionsOpenBetween
// Returns the upper bound in pvItems of auctions which
// were open between the given dates. That is, they
// started before the upperTime, and ended after the lowerTime.
vector<clsBDItemInfo *>::iterator
clsBDManipulateInfo::GetAuctionsOpenBetween(time_t lowerTime,
											time_t upperTime,
											vector<clsBDItemInfo *> *pvItems)
{
	return partition(pvItems->begin(), pvItems->end(),
		s_clsPartitionBDByDate(lowerTime, upperTime, 2));
}

// GetRevenueBetween
// Returns the upper bound in pvRevenue of transactions which
// occurred between lowerTime and upperTime.
vector<clsBDRevenueInfo *>::iterator
clsBDManipulateInfo::GetRevenueBetween(time_t lowerTime,
									   time_t upperTime,
									   vector<clsBDRevenueInfo *> *pvRevenue)
{
	return partition(pvRevenue->begin(), pvRevenue->end(),
		s_clsPartitionBDRevenueByDate(lowerTime, upperTime, 0));
}

