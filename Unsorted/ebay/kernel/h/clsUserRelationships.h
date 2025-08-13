/*	$Id: clsUserRelationships.h,v 1.4 1999/02/21 02:46:54 josh Exp $	*/
//
// clsUserRelationships.h: interface for the clsUserRelationships class.
//
//////////////////////////////////////////////////////////////////////

#if !defined(_CLSUSERRELATIONSHIPS_H_)
#define _CLSUSERRELATIONSHIPS_H_

#if _MSC_VER >= 1000
#pragma once
#endif // _MSC_VER >= 1000


#include <hash_map.h>
// The following pragma disables obnoxious long name warnings.
#pragma warning( disable : 4786 )

// Typedefs that user relationships use a lot.
typedef vector<clsUser *> UserVector;
typedef vector<int> IntVector;
typedef vector<char *> CharPVector;
typedef hash_map<int, int, hash<int>, eqint> IntIntMap;
typedef vector<IntIntMap> IntIntMapVector;
typedef hash_map<int, vector<int>, hash<int>, eqint> IntVIntMap;
typedef hash_map<char *, vector<int>, hash<char *>, eqstr> CharPIntMap;
typedef hash_map<int, char *, hash<int>, eqint> IntCharPMap;
typedef vector<ItemList> ItemListVector;
typedef vector<FeedbackItemVector *> FeedbackItemVectorPVector;
typedef vector<clsFeedback *> FeedbackPVector;
typedef vector<IntVector> IntVectorVector;

const int timelimit = 30;		// How far back in the database do we look?

class clsBidderSellerItem
{
public:
	int mItemNumber;
	int mSellerId;
	bool mbIsHighBidder;
	int mNumberOfBids;
	int mNumberCancelledBids;
	int mTotalBids;
	bool mbReserve;
	bool mbClosedEarly;
	bool operator() (const clsBidderSellerItem& p1, const clsBidderSellerItem& p2);
	clsBidderSellerItem() : mItemNumber(0), mSellerId(0), mbIsHighBidder(false),
		mNumberOfBids(0), mNumberCancelledBids(0), mTotalBids(0), mbReserve(false),
		mbClosedEarly(false) {}
};

typedef vector<clsBidderSellerItem> BidderSellerItemVector;
typedef vector<BidderSellerItemVector> BidderSellerItemVectorVector;
typedef hash_map<int, clsBidderSellerItem, hash<int>, eqint> IntBSIMap;
typedef hash_map<int, BidVector *, hash<int>, eqint> IntBidVectorPMap;
typedef hash_map<int, BidderSellerItemVector, hash<int>, eqint> IntBSIVectorMap;

class clsUserRelationships  
{
public:
	clsUserRelationships(clsMarketPlace *pMarketPlace, clsUsers *pUsers, ostream *mpStream, clsApp *pApp);
	// The default constructor should not be used.
	clsUserRelationships() : mpUsers(NULL), mpMarketPlace(NULL) {}
	virtual ~clsUserRelationships();

	void ShillRelationshipsKernel(CharPVector &useridVector,
		UserVector &vUsers,
		const char *details,
		int limit);
	
	void ShillRelationshipsByUsers(const char *details,
		const char *userlist,
		int limit);
	
	void ShillRelationshipsByItem(const char *details, 
		int item, 
		int limit);
	
	void ShillRelationshipsByFeedback(const char *details,
		const char *user,
		const char *left,
		int count,
		int age,
		int limit);

	void ShowBiddersRetractions(int bidder, const char *userid, int limit);

    ItemListVector& getBidderListVector() { return mvBidderListVector; }

	// Routines to construct the relationships	
	// Build the feedback summaries -- amount of feedback left for and by each user
	// in the list.
	void buildFeedbackSummaries();
	// Build the feedback vectors for each of the users.
	void buildFeedbackVectors();
	// Build the auctions that the bidders have in common.
	bool buildCommonAuctions(IntVIntMap& result);
	// Build the vector of Ids for each bidder...
	void buildBidderIdVector();
	// ... and for each seller.
	void buildSellerIdVector();
	// For each bidder, build the list of auctions bid on.
	bool buildBiddersBidVectors(int limit=timelimit);
	// Build a map from id to userid for the sellers.
	void buildSellerUseridMap();
	// Build a map from id to userid for the bidders.
	void buildBidderUseridMap();
	// Turn a vector of userids to the seller vector.
	bool UseridListToSellerVector(CharPVector vUserIds);
	// Turn a vector of userids to the bidder vector.
	bool UseridListToBidderVector(CharPVector vUserIds);
	// Working tool used for these.
	bool UseridListToUserVector(CharPVector& vUserIds, UserVector &userVector);
	// Routines to initialize the sets being queried.
	void setSellers(UserVector& sellerVector) { mvSellers = sellerVector; }
	void setBidders(UserVector& bidderVector) { mvBidders = bidderVector; }
	void setSellerUserids(CharPVector& vSellerids) { mvSellerUserids = vSellerids; }
	void setBidderUserids(CharPVector& vBidderids) { mvBidderUserids = vBidderids; }



private:	
	IntVector mvFeedbackReceivedCount;
	IntVector mvFeedbackLeftCount;

	FeedbackPVector mvFeedbackPVector;
	FeedbackItemVectorPVector mvFeedbackItemVectorPVector;

	// Some usages of this class, in particular ShillHunter, use the same
	// vector for mvBidders and mvSellers. Be careful when doing this to
	// clear one of them before the destructor is called, to avoid double
	// deletes.
	UserVector mvBidders;	
	UserVector mvSellers;

	IntVector mvSellerIds;
	IntVector mvBidderIds;

	ItemListVector mvSellerListVector;
	ItemListVector mvBidderListVector;
	IntIntMapVector mvBidCounts;
	IntIntMapVector mvBidVictories;
	IntCharPMap mvSellerUseridMap;
	IntCharPMap mvBidderUseridMap;
	CharPVector mvBidderUserids;	// We don't own these pointers
	CharPVector mvSellerUserids;	// or these

	clsUsers* mpUsers;
	clsMarketPlace* mpMarketPlace;
	ostream *mpStream;
	clsApp *mpApp;

	static void userVectorToIdVector(UserVector& vUsers, IntVector& vUserIds);
};

#endif // !defined(_CLSUSERRELATIONSHIPS_H_)
