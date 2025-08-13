/*	$Id: clsUserRelationships.cpp,v 1.5 1999/03/07 08:16:53 josh Exp $	*/
//
// clsUserRelationships.cpp: implementation of the clsUserRelationships class.
//	Author: Josh Gordon
//
//
//////////////////////////////////////////////////////////////////////

#include "eBayKernel.h"
#include "clsUserRelationships.h"
#include "clsUserIdWidget.h"
#include <set.h>
#pragma warning( disable : 4786 )
		
// Search comparison function for clsBidderSellerItem
bool clsBidderSellerItem::operator() (const clsBidderSellerItem& p1, const clsBidderSellerItem& p2)
{
 	if (p1.mSellerId < p2.mSellerId)
		return true;
	if (p1.mSellerId > p2.mSellerId)
		return false;
	return p1.mItemNumber < p2.mItemNumber;
}



//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

clsUserRelationships::clsUserRelationships(clsMarketPlace *pMarketPlace,
										   clsUsers *pUsers,
										   ostream *pStream,
										   clsApp *pApp) :
	mpUsers(pUsers), mpMarketPlace(pMarketPlace), mpStream(pStream), mpApp(pApp)
{

}

// Wipe out a vector of things that can be deleted.
template<class T> void wipe_vector(T& what)
{
	for (int i = 0; i < what.size(); i++)
		delete what[i];
	what.erase(what.begin(), what.end());
}

static void wipeItemListVector(ItemListVector& vec)
{

	for (int i = 0; i < vec.size(); i++)
	{
		ItemList::iterator iI;
		for (iI = vec[i].begin(); iI != vec[i].end(); iI++)
			delete (*iI).mpItem;
	}
	vec.erase(vec.begin(), vec.end());
}

clsUserRelationships::~clsUserRelationships()
{
	mpMarketPlace = NULL;
	mpUsers = NULL;
	wipe_vector(mvBidders);
	wipe_vector(mvSellers);
	wipeItemListVector(mvBidderListVector);
	wipeItemListVector(mvSellerListVector);
	// Wipe the feedback vector...
	mpStream = NULL;
	mpApp = NULL;
}

// Translate a userid list to a clsUser* vector. Stop when an invalid userid
// is provided; callers will compare vUserIds.size() to vUsers.size() to determine
// where the failure was. Return false on failure.
//
bool clsUserRelationships::UseridListToUserVector(CharPVector & vUserIds, UserVector & userVector)
{
	int usercount = vUserIds.size();

	assert(mpUsers);
	// Get the user object for each of these. 
	for (int i = 0; i < usercount; i++)
	{
		clsUser *pUser = mpUsers->GetUser(vUserIds[i]);
		if (pUser == NULL)
			return false;
		userVector.push_back(pUser);
	}
	return true;
}

bool clsUserRelationships::UseridListToSellerVector(CharPVector vUserIds)
{
	return UseridListToUserVector(vUserIds, mvSellers);
}

bool clsUserRelationships::UseridListToBidderVector(CharPVector vUserIds)
{
	return UseridListToUserVector(vUserIds, mvBidders);
}

// Fetch all the user's bid vectors.
bool clsUserRelationships::buildBiddersBidVectors(int limit)
{
	if (mvBidders.size() == 0)
		return false;

	for (int i = 0; i < mvBidders.size(); i++)
	{
		ItemList itemBidList;
		mvBidders[i]->GetBidItems(&itemBidList, limit, true,  SortItemsByUnknown, true);
		mvBidderListVector.push_back(itemBidList);
	}
	return true;
}




void clsUserRelationships::buildSellerIdVector()
{
	userVectorToIdVector(mvSellers, mvSellerIds);
}

void clsUserRelationships::buildBidderIdVector()
{
	userVectorToIdVector(mvBidders, mvBidderIds);
}

void clsUserRelationships::userVectorToIdVector(UserVector &vUsers, IntVector &vIds)
{
	int usercount = vUsers.size();
	for (int i = 0; i < usercount; i++)
		vIds.push_back(vUsers[i]->GetId());
}

// buildCommonAuctions builds a vector of pairs:
//		auction number	==> vector of indices into mvBidders
// Input contract:
//		UseridListToUserVector() has succeeded
//		setBidders() has occurred
//		buildBidderIdVector() has occurred
//		buildBiddersBidVectors() has occurred
//
//	Output:
//		returns false if there are too few users
//		returns false if there are no common auctions
//		Fills "result" with common auction data otherwise
//
//	Ownership:
//		Caller owns result
//		Items cleaned up by clsUserRelationships destructor
//
bool clsUserRelationships::buildCommonAuctions(IntVIntMap& vResult)
{
	int usercount = mvBidders.size();
	int i;

	if (usercount < 2)
		return false;

	for (i = 0; i < usercount; i++)
	{
		ItemList::iterator iItems;
		for (iItems = mvBidderListVector[i].begin(); iItems != mvBidderListVector[i].end(); iItems++)
			vResult[(*iItems).mpItem->GetId()].push_back(i);
	}

	if (vResult.size() == 0)
		return false;

	// See if any of the auctions have more than one bidder.
	IntVIntMap::iterator iAuctions;
	for (iAuctions == vResult.begin(); iAuctions != vResult.end(); iAuctions++)
	{
		if ((*iAuctions).second.size() > 1)	// Don't bother with one-bidder auctions
			break;
	}

	if (iAuctions == vResult.end())			// Didn't find any multiple-bidder auctions
		return false;

	return true;

}


void clsUserRelationships::buildFeedbackVectors()
{
	// We always build the FeedbackVector stuff from the bidder list.
	for (int n = 0; n < mvBidders.size(); n++)
	{
		clsFeedback *pFeedback = mvBidders[n]->GetFeedback();
		mvFeedbackPVector.push_back(pFeedback);
		if (pFeedback)
			mvFeedbackItemVectorPVector.push_back(pFeedback->GetItemsLeft());
		else
			mvFeedbackItemVectorPVector.push_back((FeedbackItemVector*)NULL);
	}
}



void clsUserRelationships::buildFeedbackSummaries()
{
	for (int i = 0; i < mvBidders.size(); i++)
	{
		// Look in this guy's feedback, see if any of the other user's ids
		// are in here. Keep count of matches; we'll display them later.
		int left_feedback = 0;
		int got_feedback = 0;
		int bidderId = mvBidderIds[i];

		for (int j = 0; j < mvBidders.size(); j++)
		{
			if (j != i)
			{
				// Iterate through the feedback vectors to count feedback relationships.
				// Don't bother counting self-feedback.

				FeedbackItemVector *pvItems = mvFeedbackItemVectorPVector[j];
				FeedbackItemVector::iterator iter2 = pvItems->begin();
				for (; iter2 != pvItems->end(); iter2++)
				{

#ifdef NEEDED
#pragma message("Warning! Kludge Alert in " __FILE__ " 'cause of duplicate feedback items!")
					if (iter2 != pvItems->begin() && 
						 (*iter2)->mTime == (*(iter2 - 1))->mTime)
						 continue;

#endif
					if (bidderId == (*iter2)->mCommentingId)
						got_feedback++;
				}
				pvItems = mvFeedbackItemVectorPVector[i];

				iter2 = pvItems->begin();
				for (; iter2 != pvItems->end(); iter2++)
				{
#ifdef NEEDED
#pragma message("Warning! Kludge Alert in " __FILE__ " 'cause of duplicate feedback items!")
					if (iter2 != pvItems->begin() && 
						 (*iter2)->mTime == (*(iter2 - 1))->mTime)
						 continue;
#endif

					if (mvBidderIds[j] == (*iter2)->mCommentingId)
						left_feedback++;
				}
			}
		}
		mvFeedbackLeftCount.push_back(left_feedback);
		mvFeedbackReceivedCount.push_back(got_feedback);
	}
	
}

void clsUserRelationships::buildSellerUseridMap()
{
	for (int i = 0; i < mvSellerUserids.size(); i++)
		mvSellerUseridMap[mvSellerIds[i]] = mvSellerUserids[i];
}

void clsUserRelationships::buildBidderUseridMap()
{
	for (int i = 0; i < mvBidderUserids.size(); i++)
		mvBidderUseridMap[mvBidderIds[i]] = mvBidderUserids[i];
}

// Nastier implementation stuff follows
// We have a hand-rolled User Id widget so we can control the link (we
// prefer UserSearch to an email link here.)
class clsUserIdShillWidget : public clsUserIdWidget
{
public:
	clsUserIdShillWidget();		// Do not implement
	clsUserIdShillWidget(clsMarketPlace *pMarketPlace, clsApp *pApp) 
		: clsUserIdWidget(pMarketPlace, pApp) {}
	bool EmitHTML(ostream *pStream);
};

bool clsUserIdShillWidget::EmitHTML(ostream *pStream)
{
	bool	UserIdIsEmail;

	if (mpMarketPlace == NULL || mpApp == NULL)
		return false;

	// check if user id is an email
	UserIdIsEmail = (strchr(mpUserId, '@') != NULL);

	// set the user id bold
	if (mBoldId) 
		*pStream << "<b>";

	*pStream << "<a href=\""
		<< mpMarketPlace->GetAdminPath()
		<< "eBayISAPI.dll?UserSearch&how=5&string="
		<< mpUserId
		<< "\">";

	*pStream << mpUserId;

	if (!UserIdIsEmail && mpEmail && strchr(mpEmail, '@') != NULL)
			*pStream << " / " << mpEmail;
	
	*pStream << "</a>";

	// end the user id bold
	if (mBoldId)
		*pStream << "</b>";

	if (mShowFeedback)
		EmitFeedback(pStream);

	if (mShowUserStatus)
		EmitUserStatus(pStream);

	if (mShowMask && NeedMask())
		EmitMask(pStream);

	return true;
}
struct ltint
{
	bool operator()(int s1, int s2) const { return s1 < s2; }
};

struct bidItem 
{
	int mBidder;
	int mSeller;
	int mItem;
	int mType;
	bidItem() : mBidder(0), mSeller(0), mItem(0), mType(0) {};
	bidItem(int bidder, int seller, int item, int type) : 
				mBidder(bidder), mSeller(seller), mItem(item), mType(type) {};
};

struct bidItemSort
{
	bidItemSort(const IntVector& vBidderIds) 
	{
		// Build the map from bidder id to index.
		for (int i = 0; i < vBidderIds.size(); i++)
		{
			mvBidderIdToIndex[vBidderIds[i]] = i;
		}
	}
	bool operator()(const bidItem& i1, const bidItem& i2)
	{
		int pos1 = mvBidderIdToIndex[i1.mBidder];
		int pos2 = mvBidderIdToIndex[i2.mBidder];
		if (pos1 < pos2)
			return true;
		if (pos1 > pos2)
			return false;
		if (i1.mSeller < i2.mSeller)
			return true;
		if (i1.mSeller > i2.mSeller)
			return false;
		if (i1.mItem < i2.mItem)
			return true;
		return false;
	}
	hash_map<int, int, hash<int>, eqint> mvBidderIdToIndex;
};
		
void clsUserRelationships::ShillRelationshipsKernel(CharPVector &useridVector,
													UserVector &vUsers,
													const char *details,
													int limit)
{
	bool bDetails = (strcmp(details, "on") == 0);	
	int i;

	time_t starting_time = time(0);

	setBidders(vUsers);
	buildBidderIdVector();
	setBidderUserids(useridVector);
	buildBidderUseridMap();

	setSellers(vUsers);	// Warning -- replace this with a dummy before exiting

	buildSellerIdVector();
	

	buildFeedbackVectors();
	buildFeedbackSummaries();
	int usercount = vUsers.size();	
	clsDatabase *pDb = gApp->GetDatabase();
	
	try
	{

		IntVector vCandidateSellers;	// Cross-reference for all auctions
		IntVector vCandidateAuctionIds;	// All auctions by all these users
		
		pDb->GetAuctionIds(mvBidderIds, vCandidateSellers, vCandidateAuctionIds, limit);
		
		IntVector vAllBidders;	// All our users with any bids
		IntVector vAllAuctionsBidOn;	// All the auctions our guys have bid on
		IntIntMap mapSellersAndAuctions;	// Who owned these auctions?
		IntVector vWonAuctions;			// Auctions our guys have won
		IntVector vBiddersOfWinners;	// Cross-ref to users for those auctions
		IntVector vAuctionsByUsBidOnByUs;	// All auctions by us bid on by us
		

		pDb->GetAuctionsBidOn(mvBidderIds, vAllBidders, vAllAuctionsBidOn, limit);
		pDb->GetSellersOfAuctions(vAllAuctionsBidOn, mapSellersAndAuctions, limit);
		pDb->GetAuctionsWon(mvBidderIds, vWonAuctions, vBiddersOfWinners, limit);

		IntIntMap mapWonAuctions;
		for (i = 0; i < vWonAuctions.size(); i++)
			mapWonAuctions[vWonAuctions[i]] = vBiddersOfWinners[i];

		pDb->GetOurAuctionsBidOnByUs(vCandidateAuctionIds, 
			mvBidderIds,
			vAuctionsByUsBidOnByUs,
			limit);

		// OK. Now we need the number of total bids for all those auctions. The same
		// request will also get us starting and ending time of the auctions, as well
		// as their reserves, if any.
		IntIntMap mapItemsToCounts;
		hash_map<int, time_t, hash<int>, eqint> mapItemsToDurations;
		hash_map<int, float, hash<int>, eqint> mapItemsToReserves;

		pDb->GetShillInformationForOurAuctions(vAuctionsByUsBidOnByUs, mapItemsToCounts,
			mapItemsToDurations, mapItemsToReserves);

		IntVector vBidderIdsOfBids;
		IntVector vOurBidItemNumbers;
		IntVector vOurBidTypes;
		// And we need all the bids from these users on these auctions. We'll also want 
		// the bid type (to count retractions). 
		pDb->GetBidsFromTheseUsers(vAuctionsByUsBidOnByUs, 
			mvBidderIds,
			vBidderIdsOfBids,
			vOurBidItemNumbers,
			vOurBidTypes);

		vector<bidItem> bidItems;
		for (i = 0; i < vOurBidItemNumbers.size(); i++)
		{
			int bidderid = vBidderIdsOfBids[i];
			int itemno = vOurBidItemNumbers[i];
			bidItems.push_back(bidItem(bidderid, mapSellersAndAuctions[itemno], itemno, vOurBidTypes[i]));
		}

		if (bidItems.size())
			sort(bidItems.begin(), bidItems.end(), bidItemSort(mvBidderIds));

		
		// Get all the retractions these users have left, too.
		IntVector vItemsWithRetractions;
		IntVector vBiddersOfRetractedItems;
		pDb->GetAuctionsWithRetractions(mvBidderIds,
			vBiddersOfRetractedItems,
			vItemsWithRetractions,
			limit);

				
		// All the information has been acquired. All that is left is to display it.
		// Now build a display table. These are the column headers.
		*mpStream << "<table border>"
			<<	"<tr>"
			"<th>User</th>"
			"<th>Age</th>"
			"<th>Sales</th>"
			"<th>Bids</th>"
			"<th>Distinct<br>Sellers</th>"
			"<th>Auctions<br>Won</th>"
			"<th>Retractions</th>"
			"<th>Feedback<br>Left</th>"
			"<th>Feedback<br>Received</th>";
		
		if (bDetails)
			*mpStream <<
			"<th>UV Rating</th>"
			"<th>Name</th>"
			"<th>Address</th>"
			"<th>City</th>"
			"<th>State</th>"
			"<th>Zip</th>"
			"<th>DayPhone</th>"
			"<th>NightPhone</th>"
			"<th>Host</th>";
		*mpStream <<
			"</tr>\n";
		
		
		
		for (i = 0; i < usercount; i++)
		{
			char *backgrounds[] = { "efefef", "ffffff" };
			int bidderid = mvBidderIds[i];
			
			clsUser *pUser = vUsers[i];
			*mpStream << "<tr bgcolor=#"
				<< backgrounds[i % 2]
				<< ">";
			
			*mpStream << "\t<td><pre>";
			
			clsUserIdShillWidget userIdWidget(mpMarketPlace, mpApp);
			userIdWidget.SetUserInfo(useridVector[i],
				pUser->GetEmail(),
				pUser->GetUserState(),
				mpMarketPlace->UserIdRecentlyChanged(pUser->GetUserIdLastModified()),
				mvFeedbackPVector[i]->GetScore());
			userIdWidget.SetShowFeedback(true);
			userIdWidget.SetShowUserStatus(true);
			userIdWidget.SetShowMask(false);
			userIdWidget.SetShowStar(false);
			userIdWidget.SetIncludeEmail(true);
			
			userIdWidget.EmitHTML(mpStream);
			*mpStream << "</pre></td>";
			
			// A couple of nasty macros to put out the rest of the table "cells".
			// docell just puts out
			// <td> some data </td>
			// while doxcell receives a possibly NULL string, in which case we put out a
			// space, since <td></td> with no contents doesn't build the cell properly.
			// The <pre> is because it looks ugly as heck without it.
#define docell(x) { *mpStream << "\t<td><pre>" << x << "</pre></td>"; }
#define doxcell(x) { char *s = x; *mpStream << "\t<td><pre>" << (s ? s : " ") << "</pre></td>";}
			
			// Get the age for this user
			time_t userTime	= vUsers[i]->GetCreated();
			time_t currentTime = time(0);
			double userAge = difftime(currentTime, userTime);
			int days = (int)(userAge / (24 * 60 * 60));
			docell(days);
			
			// Number of sales.
			// Sales count points to this guy's seller list.
			
			int auctionCount = 0;
			count(vCandidateSellers.begin(), vCandidateSellers.end(), mvBidderIds[i], auctionCount);
			
			*mpStream << "\t<td><pre><a href=\""
				<< mpMarketPlace->GetCGIPath(PageViewListedItems)
				<< "eBayISAPI.dll?ViewListedItems&completed=1&userid="
				<< useridVector[i]
				<< "\">"
				<< auctionCount
				<< "</a></pre></td>";
			
			
			
			// Counting the unique auctions owned by this bidder.			
			set<int, ltint> auctionset;
			set<int, ltint> sellerset;
			int j;
			for (j = 0; j < vAllBidders.size(); j++)
			{
				if (vAllBidders[j] == bidderid)
				{
					int myitem = vAllAuctionsBidOn[j];
					auctionset.insert(myitem);
					int sellerid = mapSellersAndAuctions[myitem];
					sellerset.insert(sellerid);
				}
			}

			// Auction count points to ViewBidItems
			*mpStream << "\t<td><pre><a href=\""
				<< mpMarketPlace->GetCGIPath(PageViewBidItems)
				<< "eBayISAPI.dll?ViewBidItems&completed=1&all=1&userid="
				<< useridVector[i]
				<< "\">"
				<< auctionset.size()
				<< "</a>";
			
			*mpStream << "</pre></td>";
			
			// Count the unique sellers on the bidder list. This
			// points to ShowBiddersSeller
			*mpStream << "\t<td><pre><a href=\""
				<< mpMarketPlace->GetAdminPath()
				<< "eBayISAPI.dll?AdminShowBiddersSellers&bidder="
				<< useridVector[i]
				<< "\">"
				<< sellerset.size()
				<< "</a></pre></td>";


			// Now find out how many won auctions this fool is being given
			// credit for
			auctionCount = 0;
			count(vBiddersOfWinners.begin(), vBiddersOfWinners.end(), bidderid, auctionCount);
			docell(auctionCount);

			auctionCount = 0;
			count(vBiddersOfRetractedItems.begin(), vBiddersOfRetractedItems.end(),
				bidderid, auctionCount);

			*mpStream << "\t<td><pre><a href=\""
				<< mpMarketPlace->GetAdminPath()
				<< "eBayISAPI.dll?AdminShowBiddersRetractions&id="
				<< bidderid
				<< "&limit="
				<< limit
				<< "\">"
				<< auctionCount	
				<< "</a></pre></td>";

			docell(mvFeedbackLeftCount[i]);
			docell(mvFeedbackReceivedCount[i]);
			
			if (bDetails) 
			{
				docell(pUser->GetUVRating());
				doxcell(pUser->GetName());
				doxcell(pUser->GetAddress());
				doxcell(pUser->GetCity());
				doxcell(pUser->GetState());
				doxcell(pUser->GetZip());
				doxcell(pUser->GetDayPhone());
				doxcell(pUser->GetNightPhone());
				doxcell(pUser->GetHost());
			}
#undef docell
#undef doxcell
			*mpStream<< "</tr>\n";
		}
		*mpStream << "</table>\n";
		*mpStream << 	 "<b>Feedback left</b> is the number of feedbacks left by this user for other users being analyzed.<br>\n"
			"<b>Feedback received</b> is the feedback received by this users from the others.<br>\n"
			"<b>Distinct sellers</b> is the number of <u>different</u> sellers this user has bid on.<br>\n"
			"<b>Sales</b> and <b>bids</b> are total numbers, not just for users being tested.<br>\n"
			"<b>Auctions won</b> does not include Dutch auctions.<br>\n";
		
		
		if (!bidItems.empty())
		{
			*mpStream << "<pre>" 
				"(#, #, #) is total number of bids, this bidder's bids, and this user's retractions or cancellations.\n"
				"* means this user won this auction.\n"
				"<font color=red>R</font> means this was a reserve auction.\n"
				"<font color=green>E</font> means this auction was closed early.\n"
				"\n";


			// Now just walk through bidItems(). It's already in order.
			int last_bidder = -1;
			int lastseller = -1;
			int column = 0;

			vector<bidItem>::iterator iB = bidItems.begin();;
			
			while (iB != bidItems.end())
			{
				int spaces = 0;

				int bidderid = (*iB).mBidder;
				if (bidderid != last_bidder)
				{
					*mpStream << endl << mvBidderUseridMap[bidderid];
					// Count the bids for this dude
					lastseller = -1;
					column = 0;
					last_bidder = bidderid;
				}
				int whose = (*iB).mSeller;
				int itemno = (*iB).mItem;
				if (whose != lastseller)
				{
					// Count the number of auctions bid on for this guy.
					int howmany = 0;
					int wonForHim = 0;

					vector<bidItem>::iterator iB2;
					int whatitem = -1;
					int thisitem;
					for (iB2 = iB; iB2 != bidItems.end() && (*iB2).mBidder == bidderid && (*iB2).mSeller == whose; ++iB2)
					{
						thisitem = (*iB2).mItem;
						if (thisitem == whatitem)
							continue;
						++howmany;
						whatitem = thisitem;;
						if (mapWonAuctions[thisitem] == bidderid)
							++wonForHim;

					}

					*mpStream << endl << "    bid on "
						<< howmany
						<< " auctions of "
						<< mvBidderUseridMap[whose]
						<< " and won "
						<< wonForHim;
					lastseller = whose;
					column = 0;
				}
				if (column++ % 4 == 0)
					*mpStream << endl << "\t";

				*mpStream << "<a href=\""
					<< mpMarketPlace->GetCGIPath(PageViewItem)
					<< "eBayISAPI.dll?ViewItem&item="
					<< itemno
					<< "\">"
					<< itemno
					<< "</a>";
				// How many total bids on this item?
				*mpStream << "(" << mapItemsToCounts[itemno] << ",";

				// Count the number of bids from me on this ITEM
				int bidcount = 0;
				int retractions = 0;
				for (; iB != bidItems.end() && (*iB).mBidder == bidderid && (*iB).mItem == itemno; ++iB)
				{
					++bidcount;
					int type = (*iB).mType;
					if (type == BID_CANCELLED || type == BID_RETRACTION)
						retractions++;
				}
				*mpStream << bidcount;

				// Am I a winner of this auction?
				if (mapWonAuctions[itemno] == bidderid)
					*mpStream << "*";
				else
					spaces++;

				// Did this user have any retractions?
				if (retractions != 0)
					*mpStream << "," << retractions;
				else
					spaces += 2;
					
				// Was it a reserve price auction?
				if (mapItemsToReserves[itemno] != 0.0)
					*mpStream << ",<font color=red>R</font>";
				else
					spaces += 2;

				// Was the duration a multiple of an exact hour? I kinda
				// assume that extensions and auctions periods will all be
				// exact hours long here. When we got to floating auction end
				// times, this will have tobe reconsidered.
				time_t duration = mapItemsToDurations[itemno];
				if (duration % (60 * 60) != 0)
					*mpStream << ",<font color=green>E</font>";
				else
					spaces += 2;

				*mpStream << ")";
				
				while (spaces--)
					*mpStream << " ";

				*mpStream << "\t";
			
					
			}
			*mpStream << endl << "</pre>";			
		}
		else
			*mpStream << "\n\n<b>There were no auction interactions among the users listed.</b>\n\n";
	}
	catch(...)
	{
		UserVector dummyVector;		// Gotta clear seller vector so not to delete it twice
		setSellers(dummyVector);				
		throw;
	}

	UserVector dummyVector;		// Gotta clear seller vector so not to delete it twice
	setSellers(dummyVector);				

	for (i = 0; i < usercount; i++)
		free(useridVector[i]);	// these are all strduped
	
	*mpStream << "</pre>" << endl;

	time_t elapsed_time = time(0) - starting_time;

	*mpStream << "<p><i><font size=-2>Elapsed time: "
		<< elapsed_time
		<< " seconds.</font></i><br>\n";
	
}


void clsUserRelationships::ShillRelationshipsByUsers(const char *details,
													 const char *userlist,
													 int limit)
{
	char *ouruserlist = NULL;
	int usercount = 0;
	CharPVector useridVector;
	UserVector vUsers;
	
	// If the first thing on the userlist is purely numeric, 
	// assume this is a list of user IDs.
	
	// Ripple through the list of users, picking them apart
	// and jamming them into the useridVector.
	// We strdup userlist because strtok is non-const.
	ouruserlist = strdup(userlist);
	char *p = strtok(ouruserlist, " ,\r\n");
	while(p)
	{
		usercount++;
		useridVector.push_back(strdup(p));
		p = strtok(NULL, " ,\r\n");
	}
	free(ouruserlist);
		
	if (usercount < 1)
	{
		*mpStream << "<hr><p>Please enter at least one user name or id\n";
		return;
	}
	
	// Get the user object for each of these. 
	for (int i = 0; i < usercount; i++)
	{
		// Is this a numeric userid?
		char *pUid = useridVector[i];
		clsUser *pUser = NULL;

		if (pUid[strspn(pUid, "0123456789")] == '\0')
		{
			// Purely numeric.
			pUser = mpUsers->GetUser(atoi(pUid));
			if (pUser)
			{
				free(useridVector[i]);
				useridVector[i] = strdup(pUser->GetUserId());
			}
		}
		else
			pUser = mpUsers->GetUser(useridVector[i]);

		if (pUser)
			vUsers.push_back(pUser);
		else
		{
			*mpStream << "<hr><p>Could not find information for user "
				<< useridVector[i]
				<< ". Please try again.<br>\n"
				<< mpMarketPlace->GetFooter()
				<< flush;
			// Clean up after ourselves
			UserVector::iterator uIter;
			for (uIter = vUsers.begin(); uIter != vUsers.end(); uIter++)
				delete (*uIter);
			
			CharPVector::iterator uidIter;
			for (uidIter = useridVector.begin(); uidIter != useridVector.end(); uidIter++)
				delete (*uidIter);

			return;
		}

	}
	*mpStream << "<hr>";
	ShillRelationshipsKernel(useridVector, vUsers, details, limit);
}


void clsUserRelationships::ShillRelationshipsByItem(const char *details, int item, int limit)
{
	clsItem *pItem = mpMarketPlace->GetItems()->GetItem(item, true);
	if (pItem == NULL)
	{
		*mpStream << "<br><b>Could not find item " << item << "</b>\n"
			<< mpMarketPlace->GetFooter()
			<< flush;
		return;
	}
	
	UserVector vUsers;
	IntVector vIds;
	CharPVector useridVector;
	
	// First, put the seller in.
	useridVector.push_back(strdup(pItem->GetSellerUserId()));
	vUsers.push_back(mpUsers->GetUser(pItem->GetSeller()));
	vIds.push_back(pItem->GetSeller());
	
	// Now get the bid vector.
	BidVector bidVector;
	BidVector::iterator iter;
	pItem->GetBids(&bidVector);

	// ByItem always gets the title
	*mpStream << "<p><center>"
		<< pItem->GetTitle()
		<< "</center><p>";

	delete pItem;

	// Get everyone who has bid on this item.
	for (iter = bidVector.begin(); iter != bidVector.end(); iter++)
	{
		clsUser *pUser = mpUsers->GetUser((*iter)->mUser);
		int myId = pUser->GetId();
		// Don't include another copy if this guy self-bid.
		// And don't put in dupes. 
		// Use the find() operation to see 
		// if we've already got this user in our list; if so, 
		// skip it.
		if (find(vIds.begin(), vIds.end(), myId) != vIds.end())
			delete pUser;
		else
		{
			useridVector.push_back(strdup(pUser->GetUserId()));
			vIds.push_back(myId);
			vUsers.push_back(pUser);
		}
		delete *iter;
	}
	
	ShillRelationshipsKernel(useridVector, vUsers, details, limit);
}

void 
clsUserRelationships::ShillRelationshipsByFeedback(const char *details,
											  const char *user,
											  const char *left,
											  int count,
											  int age,
											  int limit)
{
	CharPVector useridVector;
	UserVector vUsers;
	IntVector vIds;

	// Get the user object for this guy
	clsUser *pUser = mpUsers->GetUser((char *)user);
	if (pUser == NULL)
	{
		*mpStream << "<p>Could not find information for user "
				<< user
				<< ". Please try again.<br>\n";
		return;

	} 

	// Now get the feedback vector or the feedback left vector for this guy.
	clsFeedback *pFeedback = pUser->GetFeedback();
	if (pFeedback == NULL)
	{
		*mpStream << "<p>There is no feedback given or left by user "
			<< user
			<< ". Please try again.<br>\n";
		return;
	}

	vUsers.push_back(pUser);
	vIds.push_back(pUser->GetId());
	useridVector.push_back(strdup(user));

	bool bLefting = (strcmp(left, "on") == 0);
	// We either want the feedback left or the feedback given by this sucker.
	FeedbackItemVector *pvItems;
	int fbcount;

	if (bLefting)
		pvItems = pFeedback->GetItemsLeft();
	else
		pvItems = pFeedback->GetItems(1, 9999, &fbcount);


	// Now get the appropriate users. No dupes. 
	FeedbackItemVector::iterator iter;
	int entries = 0;
	time_t currentTime = time(0);


	for (iter = pvItems->begin(); iter != pvItems->end(); iter++)
	{
		int id;
		if (bLefting)
			id = (*iter)->mId;
		else
			id = (*iter)->mCommentingId;

		// Is this one unique?
		if (find(vIds.begin(), vIds.end(), id) == vIds.end())
		{
			pUser = mpUsers->GetUser(id);
			vUsers.push_back(pUser);
			vIds.push_back(id);
			useridVector.push_back(strdup(pUser->GetUserId()));
			if (age != 0)
			{
				// get the age of this item in days
				time_t itemTime	= (*iter)->mTime;
				double itemAge = difftime(currentTime, itemTime);
				int days = (int)(itemAge / (24 * 60 * 60));
				if (days > itemAge)
					break;
			}

			entries++;
			if (count != 0 && entries >= count)
				break;
		}
	}


	ShillRelationshipsKernel(useridVector, vUsers, details, limit);
}

void clsUserRelationships::ShowBiddersRetractions(int bidder, const char *userid, int limit)
{
	// Get all the retractions these users have left, too.
	IntVector vItemsWithRetractions;
	IntVector vBiddersOfRetractedItems;
	IntVector vBidderIds;

	vBidderIds.push_back(bidder);
	
	gApp->GetDatabase()->GetAuctionsWithRetractions(vBidderIds,
		vBiddersOfRetractedItems,
		vItemsWithRetractions,
		limit);

	if (vBiddersOfRetractedItems.empty())
	{
		*mpStream << "<b>There were no retractions for "
		<< userid
		<< "</b><p>";
		return;
	}

	// Sort and squeeze...
	sort(vItemsWithRetractions.begin(), vItemsWithRetractions.end());
	vItemsWithRetractions.erase(
		unique(vItemsWithRetractions.begin(), vItemsWithRetractions.end()),
		vItemsWithRetractions.end());

	*mpStream << "Bid retractions for "
		<< userid
		<< ":<p>\n";

	for (int i = 0; i < vItemsWithRetractions.size(); i++)
	{
		int itemno = vItemsWithRetractions[i];
		
		*mpStream << "<a href=\""
			<< mpMarketPlace->GetCGIPath(PageViewItem)
			<< "eBayISAPI.dll?ViewItem&item="
			<< itemno
			<< "\">"
			<< itemno
			<< "</a>\n";
	}

	*mpStream << "<p>";
}

