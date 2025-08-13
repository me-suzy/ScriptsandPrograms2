/*	$Id: clsBDTallyData.cpp,v 1.2 1999/02/21 02:30:32 josh Exp $	*/
//
// Class Name:		clsBDTallyData
//
// Description:		Does all the tallies for the BDStats project,
//					making sense of the seemingly random effluvium
//					of information available.
//
// Author:			Chad Musick
//
#include "clsBDTallyData.h"
#include "clsBDManipulateInfo.h"
#include "clsApp.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "eBayTypes.h"
#include "clsCategories.h"
#include "clsCategory.h"
#include "clsLogReadData.h"

// Constructor -- sets up, but no queries.
clsBDTallyData::clsBDTallyData(clsLogReadData *pLogReader) : 
	mpvPartners(NULL), mpvRevenue(NULL), mpvBids(NULL), mpvItems(NULL),
	mpvCategories(NULL), mpLogReader(pLogReader)
{
	mpvTallies = new vector<vector<clsBDCategoryTally *> *>;
	mpvPartnerTallies = new vector<clsBDPartnerTally *>;
	mpvCategories = new CategoryVector;
}

// Destructor -- erases any stored data.
clsBDTallyData::~clsBDTallyData()
{
	vector<vector<clsBDCategoryTally *> *>::iterator i;
	vector<clsBDCategoryTally *>::iterator j;
	vector<clsBDUserToPartnerInfo *>::iterator			iPartners;
	vector<clsBDRevenueInfo *>::iterator				iRevenue;
	vector<clsBDBidInfo *>::iterator					iBids;
	vector<clsBDItemInfo *>::iterator					iItems;
	vector<clsBDPartnerTally *>::iterator				iPTally;
	CategoryVector::iterator							iCat;

	for (iPTally = mpvPartnerTallies->begin(); iPTally != mpvPartnerTallies->end(); ++iPTally)
	{
		delete (*iPTally);
	}
	mpvPartnerTallies->erase(mpvPartnerTallies->begin(), mpvPartnerTallies->end());

	for (i = mpvTallies->begin(); i != mpvTallies->end(); ++i)
	{
		if (!*i)
			continue;
		for (j = (*i)->begin(); j != (*i)->end(); ++j)
		{
			delete *j;
		}
		(*i)->erase((*i)->begin(), (*i)->end());

		delete *i;
	}
	mpvTallies->erase(mpvTallies->begin(), mpvTallies->end());

	if (mpvPartners)
	{
		for (iPartners = mpvPartners->begin(); iPartners != mpvPartners->end(); ++iPartners)
			delete (*iPartners);
		mpvPartners->erase(mpvPartners->begin(), mpvPartners->end());
		delete mpvPartners;
	}

	if (mpvRevenue)
	{
		for (iRevenue = mpvRevenue->begin(); iRevenue != mpvRevenue->end(); ++iRevenue)
			delete (*iRevenue);
		mpvRevenue->erase(mpvRevenue->begin(), mpvRevenue->end());
		delete mpvRevenue;
	}

	if (mpvBids)
	{
		for (iBids = mpvBids->begin(); iBids != mpvBids->end(); ++iBids)
			delete (*iBids);
		mpvBids->erase(mpvBids->begin(), mpvBids->end());
		delete mpvBids;
	}

	if (mpvItems)
	{
		for (iItems = mpvItems->begin(); iItems != mpvItems->end(); ++iItems)
			delete (*iItems);
		mpvItems->erase(mpvItems->begin(), mpvItems->end());
		delete mpvItems;
	}

	for (iCat = mpvCategories->begin(); iCat != mpvCategories->end(); ++iCat)
		delete (*iCat);
	mpvCategories->erase(mpvCategories->begin(), mpvCategories->end());

	delete mpvTallies;
	delete mpvPartnerTallies;
	delete mpvCategories;
}

// Initialize
// Sets the start and end times for the queries, then
// performs the queries (there are four).
//
// Gets revenue, users, auctions, and bids, then calls
// on class clsBDManipulateInfo to merge them so that
// no information is missing (except for superCategory,
// which is filled in after the fact.)
//
// Does not actually do any tallying, though.
//
void clsBDTallyData::Initialize(time_t dayStart, time_t dayEnd)
{
	vector<vector<clsBDCategoryTally *> *>::iterator	i;
	vector<clsBDCategoryTally *>::iterator				j;
	vector<clsBDUserToPartnerInfo *>::iterator			iPartners;
	vector<clsBDRevenueInfo *>::iterator				iRevenue;
	vector<clsBDBidInfo *>::iterator					iBids;
	vector<clsBDItemInfo *>::iterator					iItems;

	clsDatabase *pDatabase;

	mDayStart = dayStart;
	mDayEnd = dayEnd;

	for (i = mpvTallies->begin(); i != mpvTallies->end(); ++i)
	{
		if (!*i)
			continue;
		for (j = (*i)->begin(); j != (*i)->end(); ++j)
		{
			delete *j;
		}
		(*i)->erase((*i)->begin(), (*i)->end());

		delete *i;
	}
	mpvTallies->erase(mpvTallies->begin(), mpvTallies->end());

	if (mpvPartners)
	{
		for (iPartners = mpvPartners->begin(); iPartners != mpvPartners->end(); ++iPartners)
			delete (*iPartners);
		mpvPartners->erase(mpvPartners->begin(), mpvPartners->end());
	}
	else
		mpvPartners = new vector<clsBDUserToPartnerInfo *>;

	if (mpvRevenue)
	{
		for (iRevenue = mpvRevenue->begin(); iRevenue != mpvRevenue->end(); ++iRevenue)
			delete (*iRevenue);
		mpvRevenue->erase(mpvRevenue->begin(), mpvRevenue->end());
	}
	else
		mpvRevenue = new vector<clsBDRevenueInfo *>;

	if (mpvBids)
	{
		for (iBids = mpvBids->begin(); iBids != mpvBids->end(); ++iBids)
			delete (*iBids);
		mpvBids->erase(mpvBids->begin(), mpvBids->end());
	}
	else
		mpvBids = new vector<clsBDBidInfo *>;

	if (mpvItems)
	{
		for (iItems = mpvItems->begin(); iItems != mpvItems->end(); ++iItems)
			delete (*iItems);
		mpvItems->erase(mpvItems->begin(), mpvItems->end());
	}
	else
		mpvItems = new vector<clsBDItemInfo *>;

	pDatabase = (clsDatabase *) gApp->GetDatabase();

	cerr << "Getting users.\n" << flush;
	pDatabase->GetBDRegisteredUsers(mpvPartners);
	cerr << "Getting transactions.\n" << flush;
	pDatabase->GetBDTransactionDay(dayStart, dayEnd, mpvRevenue);
	cerr << "Getting auctions.\n" << flush;
	pDatabase->GetBDOpenAuctions(dayStart, dayEnd, mpvItems);
	cerr << "Getting bids.\n" << flush;
	pDatabase->GetBDBids((time_t) (dayStart - (86400 * 8)), dayEnd, mpvBids);
	cerr << "Merging info.\n" << flush;
	clsBDManipulateInfo::MergeInfo(mpvPartners, mpvRevenue, mpvBids, mpvItems);
	cerr << "Done merging info.\n" << flush;
}

// ResetTimeToTally
// Initialize can be called with a larger time span than may be required
// for an individual day (or whatever time period) -- ResetTimeToTally
// will change the start and end times without redoing the query --
// this only works properly if the Initialize times included the
// reset times entirely.
//
// Erases any tallies present.
void clsBDTallyData::ResetTimeToTally(time_t dayStart, time_t dayEnd)
{
	vector<vector<clsBDCategoryTally *> *>::iterator i;
	vector<clsBDCategoryTally *>::iterator j;
	vector<clsBDPartnerTally *>::iterator				iPTally;

	mDayStart = dayStart;
	mDayEnd = dayEnd;

	for (iPTally = mpvPartnerTallies->begin(); iPTally != mpvPartnerTallies->end(); ++iPTally)
	{
		delete (*iPTally);
	}
	mpvPartnerTallies->erase(mpvPartnerTallies->begin(), mpvPartnerTallies->end());

	for (i = mpvTallies->begin(); i != mpvTallies->end(); ++i)
	{
		if (!*i)
			continue;
		for (j = (*i)->begin(); j != (*i)->end(); ++j)
		{
			delete *j;
		}
		(*i)->erase((*i)->begin(), (*i)->end());

		delete *i;
	}
	mpvTallies->erase(mpvTallies->begin(), mpvTallies->end());

	return;
}

// FindPartnerTally
// Get a partner tally given a partner number
clsBDPartnerTally *
clsBDTallyData::FindPartnerTally(int partner)
{
	int i;

	if (partner >= mpvPartnerTallies->size())
	{
		i = partner - mpvPartnerTallies->size() + 1;
		while (i--)
			mpvPartnerTallies->push_back((clsBDPartnerTally *) NULL);
	}

	if (!(*mpvPartnerTallies)[partner])
	{
		(*mpvPartnerTallies)[partner] = new clsBDPartnerTally(partner, 
			mDayStart);
	}

	return (*mpvPartnerTallies)[partner];
}

// FindCategoryTally
// Get a category tally given a partner number and category number
clsBDCategoryTally *
clsBDTallyData::FindCategoryTally(int partner, int category)
{
	return FindCategoryTally(FindCategoryTallyVector(partner), partner, category);
}

// FindCategoryTallyVector
// The category tallies are stored in vectors, which is in turn stored in
// a vector, so that there is a category tally vector for each partner.
// This returns the entire vector for one partner, given that partner name.
vector<clsBDCategoryTally *> *
clsBDTallyData::FindCategoryTallyVector(int partner)
{
	vector<clsBDCategoryTally *> *pvCat;

	int i;

	if (partner >= mpvTallies->size())
	{
		i = partner - mpvTallies->size() + 1;
		while (i--)
			mpvTallies->push_back((vector<clsBDCategoryTally *> *) NULL);
	}

	pvCat = (*mpvTallies)[partner];
	if (!pvCat)
	{
		pvCat = (*mpvTallies)[partner] = new vector<clsBDCategoryTally *>;
		// This category is used for top level totals -- make sure we always
		// have it.
		FindCategoryTally(pvCat, partner, 0);
	}

	return pvCat;
}

// FindCategoryTally
// Given a tally vector, a partner, and a category, finds a category tally.
// This is more efficient than the partner/category method of FindCategoryTally,
// and is called by that other method.
clsBDCategoryTally *
clsBDTallyData::FindCategoryTally(vector<clsBDCategoryTally *> *pvPartner, 
								  int partner,
								  int category)
{
	clsBDCategoryTally *pCat;
	int i;

	if (category >= pvPartner->size())
	{
		i = category - pvPartner->size() + 1;
		while (i-- > 0)
			pvPartner->push_back((clsBDCategoryTally *) NULL);
	}

	pCat = (*pvPartner)[category];
	if (!pCat)
	{
		pCat = (*pvPartner)[category] = new clsBDCategoryTally(partner, category, mDayStart);
		if (pCat && mpLogReader)
			pCat->mPageViews = mpLogReader->GetCategoryViewCount(partner, category);
	}

	return pCat;
}

// Tally
// The whole reason to exist.
// Fills clsBDCategoryTally * and clsBDPartnerTally * structures up with
// the information gathered from the queries, bounded in time by
// the values given in ResetTimeToTally (if called) or Initialize (if Reset was not called)
//
// Does not store any values outside of the object.
void clsBDTallyData::Tally()
{
	vector<clsBDBidInfo *>::iterator iBids, iBidsEnd;
	vector<clsBDItemInfo *>::iterator iItems, iItemsEnd;
	vector<clsBDRevenueInfo *>::iterator iRevenue, iRevenueEnd;
	vector<clsBDUserToPartnerInfo *>::iterator iPartners;

	clsBDCategoryTally *pCat;
	clsBDPartnerTally *pPart;
	clsBDItemInfo *pItem;

	int dutchNumSucceed;
	int dutchNumFail;

	cerr << "Starting daily bids tally.\n" << flush;

	// Do the bids for the day
	iBidsEnd = clsBDManipulateInfo::GetBidsMadeBetween(mDayStart, 
		mDayEnd, mpvBids);

	for (iBids = mpvBids->begin(); iBids != iBidsEnd; ++iBids)
	{
		pCat = FindCategoryTally((*iBids)->mBidderPartnerId,
			(*iBids)->mCategoryId);

		pCat->mNewBidsMadeByPartner += (*iBids)->mQuantity;

		pCat = FindCategoryTally((*iBids)->mSellerPartnerId,
			(*iBids)->mCategoryId);

		pCat->mNewBidsMadeOnPartnerItem += (*iBids)->mQuantity;
	}

	cerr << "Starting new auctions tally.\n" << flush;

	// Do the new auctions for the day
	iItemsEnd = clsBDManipulateInfo::GetAuctionsStartingBetween(
		mDayStart, mDayEnd, mpvItems);

	for (iItems = mpvItems->begin(); iItems != iItemsEnd; ++iItems)
	{
		pCat = FindCategoryTally((*iItems)->mPartnerId,
			(*iItems)->mCategoryId);

		pCat->mNewItems += (*iItems)->mQuantity;
		if ((*iItems)->mFeatured)
			pCat->mNewFeatured++;
		if ((*iItems)->mSuperFeatured)
			pCat->mNewSuperFeatured++;
		if ((*iItems)->mBold)
			pCat->mNewBold++;
	}

	cerr << "Starting ended auctions tally.\n" << flush;

	// Do the auctions ended on the day
	iItemsEnd = clsBDManipulateInfo::GetAuctionsEndBetween(
		mDayStart, mDayEnd, mpvItems);

	for (iItems = mpvItems->begin(); iItems != iItemsEnd; ++iItems)
	{
		pItem = *iItems;
		pCat = FindCategoryTally(pItem->mPartnerId,
			pItem->mCategoryId);

		// Dutch auctions
		if (pItem->mQuantity > 1)
		{
			dutchNumSucceed = pItem->mNumBidsOnDutch;
			if (dutchNumSucceed > pItem->mQuantity)
				dutchNumSucceed = pItem->mQuantity;

			dutchNumFail = pItem->mQuantity - dutchNumSucceed;

			pCat->mSuccessfulAuctions += dutchNumSucceed;
			pCat->mUnsuccessfulAuctions += dutchNumFail;
			pCat->mAuctionDaysSuccessful += 
				(pItem->mSaleEnd - pItem->mSaleStart) * 4 / 86400 *
				dutchNumSucceed;
			pCat->mAuctionDaysUnsuccessful +=
				(pItem->mSaleEnd - pItem->mSaleStart) * 4 / 86400 *
				dutchNumFail;

			pCat->mClosingBidTotal += pItem->mCurrentPrice * dutchNumSucceed;
			pCat->mTotalMinSuccessful += pItem->mStartPrice * dutchNumSucceed;
			pCat->mTotalMinUnsuccessful += pItem->mStartPrice * dutchNumFail;

			if (pItem->mCurrentPrice > pCat->mHighestClosingBid)
				pCat->mHighestClosingBid = pItem->mCurrentPrice;
		}
		else // Single items
		{
			// Succeeded on these
			if (pItem->mBidCount > 0 && 
				pItem->mCurrentPrice >= pItem->mReservePrice)
			{
				pCat->mSuccessfulAuctions++;
				pCat->mAuctionDaysSuccessful +=
					(pItem->mSaleEnd - pItem->mSaleStart) * 4 / 86400;
				pCat->mClosingBidTotal += pItem->mCurrentPrice;
				pCat->mTotalMinSuccessful += max(pItem->mStartPrice, pItem->mReservePrice);
				if (pItem->mCurrentPrice > pCat->mHighestClosingBid)
					pCat->mHighestClosingBid = pItem->mCurrentPrice;
			}
			else // Failed on these
			{
				pCat->mUnsuccessfulAuctions++;
				pCat->mAuctionDaysUnsuccessful +=
					(pItem->mSaleEnd - pItem->mSaleStart) * 4 / 86400;
				pCat->mTotalMinUnsuccessful += max(pItem->mStartPrice, pItem->mReservePrice);
			}
		}
	}

	// Items are already partitioned for auctions closing on the day, ending at iItemsEnd
	// This call, then, gets all the bids made on those items.
	iBidsEnd = clsBDManipulateInfo::GetBidsMadeOnItems(
		mpvItems->begin(), iItemsEnd, mpvBids);

	cerr << "Starting closed bids tally.\n" << flush;

	for (iBids = mpvBids->begin(); iBids != iBidsEnd; ++iBids)
	{
		pCat = FindCategoryTally((*iBids)->mBidderPartnerId,
			(*iBids)->mCategoryId);

		pCat->mClosedBidsMadeByPartner += (*iBids)->mQuantity;

		pCat = FindCategoryTally((*iBids)->mSellerPartnerId,
			(*iBids)->mCategoryId);

		pCat->mClosedBidsMadeOnPartnerItem += (*iBids)->mQuantity;
	}

	cerr << "Starting tally auctions open on day.\n" << flush;

	// Do the auctions open on the day
	iItemsEnd = clsBDManipulateInfo::GetAuctionsOpenBetween(
		mDayStart, mDayEnd, mpvItems);

	for (iItems = mpvItems->begin(); iItems != iItemsEnd; ++iItems)
	{
		pCat = FindCategoryTally((*iItems)->mPartnerId,
			(*iItems)->mCategoryId);

		// Dutch
		if ((*iItems)->mQuantity > 1)
		{
			pCat->mTotalOpenValue += 
				(*iItems)->mCurrentPrice * (*iItems)->mQuantity;
		}
		else // Not dutch
		{
			// If they have a reserve, we count that as the 'value'.
			if ((*iItems)->mReservePrice > (*iItems)->mCurrentPrice)
				pCat->mTotalOpenValue += (*iItems)->mReservePrice;
			else
				pCat->mTotalOpenValue += (*iItems)->mCurrentPrice;
		}
	}

	cerr << "Starting tally for daily revenue.\n" << flush;

	// Do the revenue for the day
	iRevenueEnd = clsBDManipulateInfo::GetRevenueBetween(
		mDayStart, mDayEnd, mpvRevenue);

	for (iRevenue = mpvRevenue->begin(); iRevenue != iRevenueEnd; ++iRevenue)
	{
		switch ((*iRevenue)->mAction)
		{
		// Only these types of transactions are revenue for our counting
		case AccountDetailFeeInsertion:
		case AccountDetailFeeBold:
		case AccountDetailFeeFeatured:
		case AccountDetailFeeCategoryFeatured:
		case AccountDetailFeeFinalValue:
		case AccountDetailCreditCourtesy:
		case AccountDetailCreditNoSale:
		case AccountDetailCreditPartialSale:
		case AccountDetailRefundCC:
		case AccountDetailRefundCheck:
		case AccountDetailFinanceCharge:
		case AccountDetailCreditDuplicateListing:
		case AccountDetailFeePartialSale:
		case AccountDetailCreditInsertion:
		case AccountDetailCreditBold:
		case AccountDetailCreditFeatured:
		case AccountDetailCreditCategoryFeatured:
		case AccountDetailCreditFinalValue:
		case AccountDetailCreditTransferFrom:
		case AccountDetailDebitTransferTo:
			break;
		default:
			continue;
		}

		pCat = FindCategoryTally((*iRevenue)->mUserPartnerId,
			(*iRevenue)->mCategoryId);

		pCat->mTotalRevenue += -((*iRevenue)->mAmount);
	}

	cerr << "Starting registration tally.\n" << flush;

	// Do the registrations.
	for (iPartners = mpvPartners->begin(); iPartners != mpvPartners->end();
		++iPartners)
	{
		pPart = FindPartnerTally((*iPartners)->mPartnerId);

		if ((*iPartners)->mRegisteredOn >= mDayStart &&
			(*iPartners)->mRegisteredOn < mDayEnd)
			pPart->mNewRegistrations++;

		if ((*iPartners)->mRegisteredOn < mDayEnd)
			pPart->mCurrentTotalRegistrations++;
	}

	cerr << "Starting aggregation.\n" << flush;
	//And aggregate everything.
	AggregateCategories();

	cerr << "Done with tallies.\n" << flush;

	return;
}

// SetPageViews
// The idiot child of the family, this is supposed to
// set the number of category views in the tallies, but
// has a bug I haven't yet slain.
void clsBDTallyData::SetPageViews(int partner, vector<int> *pvViews)
{
	int i;
	vector<int>::iterator iViews;
	clsBDPartnerTally *pPartner;
	clsBDCategoryTally *pCategory;
	vector<clsBDCategoryTally *> *pvCategories;

	pPartner = FindPartnerTally(partner);
	pvCategories = FindCategoryTallyVector(partner);

	for (iViews = pvViews->begin(), i = 0; iViews != pvViews->end(); ++iViews, ++i)
	{
		if (!*iViews)
			continue;

		pCategory = FindCategoryTally(pvCategories, partner, i);

		pCategory->mPageViews = (*iViews);
		pPartner->mPageViews += (*iViews);
	}

	return;
}

// AggregateOneCategory
// A recursive function to sum all sub categories of pCategory
// with pCategory. We start this off with a category with
// id 0, so it gets _all_ categories eventually, and the
// terminating condition is no subcategories.
void clsBDTallyData::AggregateOneCategory(clsCategory *pCategory)
{
	int category;
	CategoryVector::iterator i;
	clsBDCategoryTally *tallyMe;
	clsBDCategoryTally *tallyChild;
	vector<vector<clsBDCategoryTally *> *>::iterator j;

	category = pCategory->GetId();

	if (pCategory->isLeaf())
		return;

	for (i = mpvCategories->begin(); i != mpvCategories->end(); ++i)
	{
		if ((*i)->GetLevel1() == category)
		{
			AggregateOneCategory(*i);
			for (j = mpvTallies->begin(); j != mpvTallies->end(); ++j)
			{
				if (!(*j))
					continue;

				if ((*j)->size() <= category)
					continue;
				if ((*j)->size() <= (*i)->GetId())
					continue;
				tallyMe = (*(*j))[category];
				tallyChild = (*(*j))[(*i)->GetId()];

				if (!tallyChild)
					continue;

				if (!tallyMe)
				{
					tallyMe = FindCategoryTally(*j, 
						(int) (j - mpvTallies->begin()), category);
				}

				(*tallyMe) += tallyChild;
				tallyChild->mSuperCategory = category;
			}
		}
	}
}

// AggregateCategories
// Starts the recursion in AggregateOneCategory
void clsBDTallyData::AggregateCategories()
{
	clsCategory *pFalseCategory;

	// If we make it id 0, top level categories will see it as a parent, giving
	// exactly the effect we want.
	pFalseCategory = new clsCategory(0);

	if (mpvCategories->empty())
		gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCategories()->
			All(mpvCategories);

	AggregateOneCategory(pFalseCategory);

	delete pFalseCategory;
}

// StoreAndClearTallies
// Stores the information gathered through
// Initialize and collated through Tally, then
// clears that information (the tallies, not
// the queried info) from memory.
void clsBDTallyData::StoreAndClearTallies()
{
	vector<vector<clsBDCategoryTally *> *>::iterator	i;
	vector<clsBDCategoryTally *>::iterator				j;
	vector<clsBDPartnerTally *>::iterator				iPTally;

	int ctr;
	char cTimeString[32];
	struct tm *pTheTime;

	clsDatabase *pDatabase;

	int count;
	int *pPartnerIds;
	int *pCategoryIds;
	char *pTimes;
	int timeLen;
	int *pNewItems;
	int *pNewByBids;
	int *pNewOnBids;
	int *pSuccessfulAuctions;
	int *pClosedByBids;
	int *pClosedOnBids;
	int *pUnsuccessfulAuctions;
	int *pDays4Success;
	int *pDays4Fail;
	int *pClosingBidTotal;
	int *pNewBold;
	int *pNewFeatured;
	int *pNewSuperFeatured;
	int *pNumPageViews;
	int *pTotalMinSuccess;
	int *pTotalMinFail;
	int *pHighestClosingBid;
	int *pTotalOpenValue;
	int *pTotalRevenue;
	int *pSuperCategory;

	pDatabase = gApp->GetDatabase();

	// This is how long our printed dates are.
	timeLen = 32;
	// Let's make the date string, since it's the same for all.
	pTheTime = localtime(&mDayStart);
	memset(cTimeString, '\0', timeLen);
	strftime(cTimeString, timeLen, "%Y-%m-%d %H:%M:%S", pTheTime);

	// First, count them.
	count = 0;

	for (i = mpvTallies->begin(); i != mpvTallies->end(); ++i)
	{
		if (!*i)
			continue;
		for (j = (*i)->begin(); j != (*i)->end(); ++j)
		{
			if (*j)
				++count;
		}
	}

	// Next, allocate space for them.
	pPartnerIds = new int [count];
	pCategoryIds = new int [count];
	pTimes = new char [count * timeLen];
	pNewItems = new int [count];
	pNewByBids = new int [count];
	pNewOnBids = new int [count];
	pSuccessfulAuctions = new int [count];
	pClosedByBids = new int [count];
	pClosedOnBids = new int [count];
	pUnsuccessfulAuctions = new int [count];
	pDays4Success = new int [count];
	pDays4Fail = new int [count];
	pClosingBidTotal = new int [count];
	pNewBold = new int [count];
	pNewFeatured = new int [count];
	pNewSuperFeatured = new int [count];
	pNumPageViews = new int [count];
	pTotalMinSuccess = new int [count];
	pTotalMinFail = new int [count];
	pHighestClosingBid = new int [count];
	pTotalOpenValue = new int [count];
	pTotalRevenue = new int [count];
	pSuperCategory = new int [count];

	// Now, fill them up.
	ctr = 0;
	for (i = mpvTallies->begin(); i != mpvTallies->end(); ++i)
	{
		if (!*i)
			continue;
		for (j = (*i)->begin(); j != (*i)->end(); ++j)
		{
			if (!*j)
				continue;

			pPartnerIds[ctr] = (*j)->mPartnerId;
			pCategoryIds[ctr] = (*j)->mCategoryId;
			memcpy(pTimes + (ctr * timeLen), cTimeString, timeLen);
			pNewItems[ctr] = (*j)->mNewItems;
			pNewByBids[ctr] = (*j)->mNewBidsMadeByPartner;
			pNewOnBids[ctr] = (*j)->mNewBidsMadeOnPartnerItem;
			pSuccessfulAuctions[ctr] = (*j)->mSuccessfulAuctions;
			pClosedByBids[ctr] = (*j)->mClosedBidsMadeByPartner;
			pClosedOnBids[ctr] = (*j)->mClosedBidsMadeOnPartnerItem;
			pUnsuccessfulAuctions[ctr] = (*j)->mUnsuccessfulAuctions;
			pDays4Success[ctr] = (*j)->mAuctionDaysSuccessful;
			pDays4Fail[ctr] = (*j)->mAuctionDaysUnsuccessful;
			pClosingBidTotal[ctr] = (*j)->mClosingBidTotal;
			pNewBold[ctr] = (*j)->mNewBold;
			pNewFeatured[ctr] = (*j)->mNewFeatured;
			pNewSuperFeatured[ctr] = (*j)->mNewSuperFeatured;
			pNumPageViews[ctr] = (*j)->mPageViews;
			pTotalMinSuccess[ctr] = (*j)->mTotalMinSuccessful;
			pTotalMinFail[ctr] = (*j)->mTotalMinUnsuccessful;
			pHighestClosingBid[ctr] = (*j)->mHighestClosingBid;
			pTotalOpenValue[ctr] = (*j)->mTotalOpenValue;
			pTotalRevenue[ctr] = (*j)->mTotalRevenue;
			pSuperCategory[ctr] = (*j)->mSuperCategory;

			++ctr;
		}
	}

	// Now, store them.
	pDatabase->InsertManyBDCategoryInfo(count,
		pPartnerIds,
		pCategoryIds,
		pTimes,
		timeLen,
		pNewItems,
		pNewByBids,
		pNewOnBids,
		pSuccessfulAuctions,
		pClosedByBids,
		pClosedOnBids,
		pUnsuccessfulAuctions,
		pDays4Success,
		pDays4Fail,
		pClosingBidTotal,
		pNewBold,
		pNewFeatured,
		pNewSuperFeatured,
		pNumPageViews,
		pTotalMinSuccess,
		pTotalMinFail,
		pHighestClosingBid,
		pTotalOpenValue,
		pTotalRevenue,
		pSuperCategory);

	for (iPTally = mpvPartnerTallies->begin(); iPTally != mpvPartnerTallies->end(); ++iPTally)
	{
		if (!*iPTally)
			continue;

		pDatabase->AddPartnerData((*iPTally)->mPartnerId,
			(*iPTally)->mPageViews,
			(*iPTally)->mDayStarting,
			(*iPTally)->mNewRegistrations,
			(*iPTally)->mCurrentTotalRegistrations);

	}

	// Now, reset the data.
	ResetTimeToTally(mDayStart, mDayEnd);

	delete [] pPartnerIds;
	delete [] pCategoryIds;
	delete [] pTimes;
	delete [] pNewItems;
	delete [] pNewByBids;
	delete [] pNewOnBids;
	delete [] pSuccessfulAuctions;
	delete [] pClosedByBids;
	delete [] pClosedOnBids;
	delete [] pUnsuccessfulAuctions;
	delete [] pDays4Success;
	delete [] pDays4Fail;
	delete [] pClosingBidTotal;
	delete [] pNewBold;
	delete [] pNewFeatured;
	delete [] pNewSuperFeatured;
	delete [] pNumPageViews;
	delete [] pTotalMinSuccess;
	delete [] pTotalMinFail;
	delete [] pHighestClosingBid;
	delete [] pTotalOpenValue;
	delete [] pTotalRevenue;
	delete [] pSuperCategory;

	return;
}
